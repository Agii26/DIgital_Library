<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhysicalBorrow;
use App\Models\Book;
use App\Models\Penalty;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ReservationStatusNotification;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $query = PhysicalBorrow::with(['user', 'book']);

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })->orWhereHas('book', function($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('accession_no', 'like', '%' . $request->search . '%');
            });
        }

        $borrows = $query->latest()->paginate(15);
        return view('admin.borrows.index', compact('borrows'));
    }

    public function show(PhysicalBorrow $borrow)
    {
        $borrow->load(['user' => function($q) {
            $q->withTrashed();
        }, 'book', 'penalties']);
        
        return view('admin.borrows.show', compact('borrow'));
    }

    public function approve(PhysicalBorrow $borrow)
    {
        $borrow->update([
            'status'      => 'approved',
            'approved_at' => now(),
        ]);

        $borrow->user->notify(new ReservationStatusNotification($borrow, 'approved'));

        return back()->with('success', 'Reservation approved! User has been notified.');
    }

    public function claim(Request $request, PhysicalBorrow $borrow)
    {
        $request->validate([
            'rfid_tag' => 'required|string',
        ]);

        $user = User::where('rfid_tag', $request->rfid_tag)->first();

        if (!$user || $user->id !== $borrow->user_id) {
            return back()->withErrors(['rfid_tag' => 'RFID does not match the borrower!']);
        }

        $borrowDays = (int) ($borrow->user->role === 'faculty'
            ? Setting::get('faculty_borrow_days', 7)
            : Setting::get('student_borrow_days', 2));

        $borrow->update([
            'status'      => 'claimed',
            'claimed_at'  => now(),
            'due_date'    => now()->addDays($borrowDays),
        ]);

        $borrow->book->update(['status' => 'borrowed']);

        return back()->with('success', 'Book claimed successfully! Due date: ' . now()->addDays($borrowDays)->format('M d, Y'));
    }

    public function returning(Request $request, PhysicalBorrow $borrow)
    {
        $request->validate([
            'condition' => 'required|in:good,damaged,lost',
        ]);

        $borrow->update([
            'status'       => 'returned',
            'returned_at'  => now(),
            'condition'    => $request->condition,
        ]);

        // Compute fines
        $this->computeFines($borrow, $request->condition);

        // Update book status
        $bookStatus = match($request->condition) {
            'damaged' => 'available',
            'lost'    => 'lost',
            default   => 'available',
        };

        $borrow->book->update(['status' => $bookStatus]);

        return back()->with('success', 'Book returned and assessed successfully!');
    }

    private function computeFines(PhysicalBorrow $borrow, string $condition)
    {
        $fines = [];

        // Overdue fine
        if ($borrow->due_date && now()->isAfter($borrow->due_date)) {
            $daysOverdue = now()->diffInDays($borrow->due_date);
            $finePerDay  = Setting::get('overdue_fine_per_day', 5);
            $fines[] = [
                'user_id'           => $borrow->user_id,
                'physical_borrow_id'=> $borrow->id,
                'type'              => 'overdue',
                'amount'            => $daysOverdue * $finePerDay,
                'is_paid'           => false,
            ];
        }

        if ($condition === 'damaged') {
            $multiplier = (float) Setting::get('damaged_book_fine_multiplier', 0.5);
            $reductionPerDamage = 0.1; // 10% reduction per previous damage
            $damageCount = $borrow->book->damage_count;
            $reducedMultiplier = max(0.1, $multiplier - ($reductionPerDamage * $damageCount));
            $amount = $borrow->book->price * $reducedMultiplier;

            $fines[] = [
                'user_id'            => $borrow->user_id,
                'physical_borrow_id' => $borrow->id,
                'type'               => 'damaged',
                'amount'             => $amount,
                'is_paid'            => false,
            ];

            // Increment damage count
            $borrow->book->increment('damage_count');
        }

        // Lost fine
        if ($condition === 'lost') {
            $multiplier = Setting::get('lost_book_fine_multiplier', 1);
            $fines[] = [
                'user_id'            => $borrow->user_id,
                'physical_borrow_id' => $borrow->id,
                'type'               => 'lost',
                'amount'             => $borrow->book->price * $multiplier,
                'is_paid'            => false,
            ];
        }

        foreach ($fines as $fine) {
            Penalty::create($fine);
        }
    }

    public function cancel(PhysicalBorrow $borrow)
    {
        $borrow->update(['status' => 'cancelled']);
        $borrow->book->update(['status' => 'available']);
        $borrow->user->notify(new ReservationStatusNotification($borrow, 'cancelled'));
        return back()->with('success', 'Reservation cancelled. User has been notified.');
    }
}