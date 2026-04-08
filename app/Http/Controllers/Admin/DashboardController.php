<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookCopy;
use App\Models\PhysicalBorrow;
use App\Models\Penalty;
use App\Models\User;
use App\Models\AttendanceLog;

class DashboardController extends Controller
{
    public function index()
    {
        // Total unique book titles
        $totalBooks = Book::count();

        // Available = books that have at least 1 available copy
        $availableBooks = Book::whereHas('copies', fn($q) =>
            $q->where('status', 'available')
        )->count();

        // Borrowed = books where ALL copies are unavailable
        $borrowedBooks = Book::whereDoesntHave('copies', fn($q) =>
            $q->where('status', 'available')
        )->count();

        $totalUsers      = User::whereIn('role', ['faculty', 'student'])->count();
        $pendingReserve  = PhysicalBorrow::where('status', 'reserved')->count();
        $totalUnpaid     = Penalty::where('is_paid', false)->sum('amount');

        $todayAttendance = AttendanceLog::whereDate('scanned_at', today())
            ->where('type', 'time_in')->count();

        $currentlyIn = AttendanceLog::where('type', 'time_in')
            ->whereDate('scanned_at', today())
            ->whereDoesntHave('user', function ($q) {
                $q->whereHas('attendanceLogs', function ($q2) {
                    $q2->where('type', 'time_out')->whereDate('scanned_at', today());
                });
            })->count();

        // Recent borrows — book() still belongs to Book via book_id on PhysicalBorrow
        $recentBorrows = PhysicalBorrow::with(['user', 'book'])
            ->latest()
            ->limit(5)
            ->get();

        $recentAttendance = AttendanceLog::with('user')
            ->latest('scanned_at')
            ->limit(5)
            ->get();

        $overdueBorrows = PhysicalBorrow::where('status', 'claimed')
            ->where('due_date', '<', now())
            ->count();

        return view('admin.dashboard', compact(
            'totalBooks', 'availableBooks', 'borrowedBooks',
            'totalUsers', 'pendingReserve', 'totalUnpaid',
            'todayAttendance', 'currentlyIn', 'recentBorrows',
            'recentAttendance', 'overdueBorrows'
        ));
    }
}