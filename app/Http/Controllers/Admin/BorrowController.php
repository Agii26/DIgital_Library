<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PhysicalBorrow;
use App\Models\Penalty;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ReservationStatusNotification;

class BorrowController extends Controller
{
    public function index(Request $request)
    {
        $stats = [
            'pending'  => PhysicalBorrow::where('status', 'reserved')->count(),
            'approved' => PhysicalBorrow::where('status', 'approved')->count(),
            'claimed'  => PhysicalBorrow::where('status', 'claimed')->count(),
            'overdue'  => PhysicalBorrow::where('status', 'claimed')
                            ->whereNotNull('due_date')
                            ->where('due_date', '<', now())
                            ->count(),
        ];

        $pending = PhysicalBorrow::with(['user' => fn($q) => $q->withTrashed(), 'book'])
            ->where('status', 'reserved')
            ->latest()
            ->get();

        $query = User::withTrashed()->whereHas('physicalBorrows');

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%'.$request->search.'%')
                  ->orWhere('email', 'like', '%'.$request->search.'%');
            });
        }

        if ($request->status) {
            $query->whereHas('physicalBorrows', fn($q) => $q->where('status', $request->status));
        }

        $users = $query->with(['physicalBorrows' => function($q) use ($request) {
            if ($request->status) {
                $q->where('status', $request->status);
            }
            $q->with('book')->latest();
        }])->latest()->paginate(15);

        $activeTab = $request->tab === 'all' ? 'all' : 'pending';

        return view('admin.borrows.index', compact('stats', 'pending', 'users', 'activeTab'));
    }

    // RFID lookup — returns user + their approved books as JSON
    public function rfidLookup(Request $request)
    {
        $request->validate(['rfid_tag' => 'required|string']);

        $user = User::where('rfid_tag', $request->rfid_tag)->first();

        if (!$user) {
            return response()->json(['found' => false, 'message' => 'No user found with this RFID tag.']);
        }

        $approvedBorrows = PhysicalBorrow::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        if ($approvedBorrows->isEmpty()) {
            return response()->json([
                'found'   => true,
                'message' => 'No approved reservations found for ' . $user->name . '.',
                'borrows' => [],
                'user'    => ['name' => $user->name, 'role' => $user->role],
            ]);
        }

        return response()->json([
            'found'   => true,
            'user'    => ['id' => $user->id, 'name' => $user->name, 'role' => $user->role],
            'borrows' => $approvedBorrows->map(fn($b) => [
                'id'           => $b->id,
                'title'        => $b->book->title,
                'author'       => $b->book->author,
                'accession_no' => $b->book->accession_no,
            ]),
        ]);
    }

    // Claim all approved borrows for a user
    public function claimAll(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);

        $user = User::findOrFail($request->user_id);

        $borrows = PhysicalBorrow::with('book')
            ->where('user_id', $user->id)
            ->where('status', 'approved')
            ->get();

        if ($borrows->isEmpty()) {
            return back()->with('error', 'No approved reservations found for this user.');
        }

        $borrowDays = (int) ($user->role === 'faculty'
            ? Setting::get('faculty_borrow_days', 7)
            : Setting::get('student_borrow_days', 2));

        foreach ($borrows as $borrow) {
            $borrow->update([
                'status'     => 'claimed',
                'claimed_at' => now(),
                'due_date'   => now()->addDays($borrowDays),
            ]);
            $borrow->book->update(['status' => 'borrowed']);
        }

        return back()->with('success',
            $borrows->count() . ' book(s) claimed for ' . $user->name .
            '. Due date: ' . now()->addDays($borrowDays)->format('M d, Y') . '.'
        );
    }

    public function approveAll()
    {
        $borrows = PhysicalBorrow::with('user', 'book')->where('status', 'reserved')->get();

        foreach ($borrows as $borrow) {
            $borrow->update(['status' => 'approved', 'approved_at' => now()]);
            try {
                $borrow->user->notify(new ReservationStatusNotification($borrow, 'approved'));
            } catch (\Exception $e) {
                \Log::error('Mail error: ' . $e->getMessage());
            }
        }

        return redirect()->route('admin.borrows.index')
            ->with('success', $borrows->count() . ' reservation(s) approved and users notified.');
    }

    public function show(User $user)
    {
        $borrows = PhysicalBorrow::with(['book', 'penalties'])
            ->where('user_id', $user->id)
            ->where('status', 'claimed')
            ->latest()
            ->get();

        $borrows->each(fn($b) => $b->setRelation('penalties', $b->penalties ?? collect()));

        return view('admin.borrows.show', compact('user', 'borrows'));
    }

    public function approve(PhysicalBorrow $borrow)
    {
        $borrow->update(['status' => 'approved', 'approved_at' => now()]);

        try {
            $borrow->user->notify(new ReservationStatusNotification($borrow, 'approved'));
        } catch (\Exception $e) {
            \Log::error('Mail error: ' . $e->getMessage());
        }

        return back()->with('success', 'Reservation approved! User has been notified.');
    }

    public function returning(Request $request, PhysicalBorrow $borrow)
    {
        $request->validate(['condition' => 'required|in:good,damaged,lost']);

        $borrow->update([
            'status'      => 'returned',
            'returned_at' => now(),
            'condition'   => $request->condition,
        ]);

        $this->computeFines($borrow, $request->condition);

        $borrow->book->update(['status' => match($request->condition) {
            'lost'  => 'lost',
            default => 'available',
        }]);

        return back()->with('success', 'Book returned and assessed successfully!');
    }

    public function cancel(PhysicalBorrow $borrow)
    {
        $borrow->update(['status' => 'cancelled']);
        $borrow->book->update(['status' => 'available']);

        try {
            $borrow->user->notify(new ReservationStatusNotification($borrow, 'cancelled'));
        } catch (\Exception $e) {
            \Log::error('Mail error: ' . $e->getMessage());
        }

        return back()->with('success', 'Reservation cancelled. User has been notified.');
    }

    private function computeFines(PhysicalBorrow $borrow, string $condition)
    {
        $fines = [];

        if ($borrow->due_date && now()->isAfter($borrow->due_date)) {
            $daysOverdue = now()->diffInDays($borrow->due_date);
            $finePerDay  = Setting::get('overdue_fine_per_day', 5);
            $fines[] = [
                'user_id'            => $borrow->user_id,
                'physical_borrow_id' => $borrow->id,
                'type'               => 'overdue',
                'amount'             => $daysOverdue * $finePerDay,
                'is_paid'            => false,
            ];
        }

        if ($condition === 'damaged') {
            $multiplier        = (float) Setting::get('damaged_book_fine_multiplier', 0.5);
            $damageCount       = $borrow->book->damage_count;
            $reducedMultiplier = max(0.1, $multiplier - (0.1 * $damageCount));
            $fines[] = [
                'user_id'            => $borrow->user_id,
                'physical_borrow_id' => $borrow->id,
                'type'               => 'damaged',
                'amount'             => $borrow->book->price * $reducedMultiplier,
                'is_paid'            => false,
            ];
            $borrow->book->increment('damage_count');
        }

        if ($condition === 'lost') {
            $fines[] = [
                'user_id'            => $borrow->user_id,
                'physical_borrow_id' => $borrow->id,
                'type'               => 'lost',
                'amount'             => $borrow->book->price * Setting::get('lost_book_fine_multiplier', 1),
                'is_paid'            => false,
            ];
        }

        foreach ($fines as $fine) {
            Penalty::create($fine);
        }
    }
}