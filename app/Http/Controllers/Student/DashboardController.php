<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\PhysicalBorrow;
use App\Models\Penalty;
use App\Models\Book;
use App\Models\DigitalSession;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeBorrows = PhysicalBorrow::where('user_id', $user->id)
            ->whereIn('status', ['reserved', 'approved', 'claimed'])
            ->count();

        $totalBorrows = PhysicalBorrow::where('user_id', $user->id)->count();

        $unpaidPenalties = Penalty::where('user_id', $user->id)
            ->where('is_paid', false)
            ->sum('amount');

        $pendingReservations = PhysicalBorrow::where('user_id', $user->id)
            ->where('status', 'reserved')
            ->count();

        $overdueBorrows = PhysicalBorrow::where('user_id', $user->id)
            ->where('status', 'claimed')
            ->where('due_date', '<', now())
            ->count();

        $recentBorrows = PhysicalBorrow::where('user_id', $user->id)
            ->with('book')
            ->latest()
            ->limit(5)
            ->get();

        $availableBooks = Book::where('type', 'physical')
            ->whereHas('copies', fn($q) => $q->where('status', 'available'))
            ->count();

        $digitalBooks = Book::where('type', 'digital')
            ->whereHas('copies', fn($q) => $q->where('status', 'available'))
            ->count();

        return view('student.dashboard', compact(
            'activeBorrows', 'totalBorrows', 'unpaidPenalties',
            'pendingReservations', 'overdueBorrows', 'recentBorrows',
            'availableBooks', 'digitalBooks'
        ));
    }
}