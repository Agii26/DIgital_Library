@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

<!-- Welcome Banner -->
<div class="bg-gradient-to-r from-blue-900 to-blue-700 rounded-2xl p-6 mb-6 text-white">
    <p class="text-blue-300 text-sm">{{ now()->format('l, F d, Y') }}</p>
    <h2 class="text-2xl font-bold mt-1">Welcome back, {{ Auth::user()->name }}.</h2>
    <p class="text-blue-300 text-sm mt-1">Here's what's happening in the library today.</p>
</div>

<!-- Stats Grid -->
<div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    <div class="hover-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Total Books</p>
        <h3 class="text-4xl font-black text-gray-900 mt-2">{{ $totalBooks }}</h3>
        <p class="text-xs text-gray-400 mt-2">{{ $availableBooks }} available · {{ $borrowedBooks }} borrowed</p>
        <div class="mt-3 h-0.5 bg-gray-100 rounded-full overflow-hidden">
            <div class="h-full bg-blue-600 rounded-full transition-all" style="width: {{ $totalBooks > 0 ? ($availableBooks / $totalBooks) * 100 : 0 }}%"></div>
        </div>
    </div>

    <div class="hover-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Currently Borrowed</p>
        <h3 class="text-4xl font-black text-gray-900 mt-2">{{ $borrowedBooks }}</h3>
        @if($overdueBorrows > 0)
            <p class="text-xs text-red-500 font-semibold mt-2">{{ $overdueBorrows }} overdue</p>
        @else
            <p class="text-xs text-gray-400 mt-2">No overdue books</p>
        @endif
    </div>

    <div class="hover-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Registered Users</p>
        <h3 class="text-4xl font-black text-gray-900 mt-2">{{ $totalUsers }}</h3>
        <p class="text-xs text-gray-400 mt-2">Faculty & students</p>
    </div>

    <div class="hover-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Pending Reservations</p>
        <h3 class="text-4xl font-black {{ $pendingReserve > 0 ? 'text-red-600' : 'text-gray-900' }} mt-2">{{ $pendingReserve }}</h3>
        @if($pendingReserve > 0)
            <a href="{{ route('admin.borrows.index') }}" class="text-xs text-blue-600 font-semibold mt-2 inline-block hover:underline">Review now →</a>
        @else
            <p class="text-xs text-gray-400 mt-2">All clear</p>
        @endif
    </div>

    <div class="hover-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Unpaid Penalties</p>
        <h3 class="text-4xl font-black {{ $totalUnpaid > 0 ? 'text-orange-600' : 'text-gray-900' }} mt-2">₱{{ number_format($totalUnpaid, 2) }}</h3>
        <p class="text-xs text-gray-400 mt-2">Outstanding balance</p>
    </div>

    <div class="hover-card bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
        <p class="text-xs font-semibold text-gray-400 uppercase tracking-widest">Today's Visitors</p>
        <h3 class="text-4xl font-black text-gray-900 mt-2">{{ $todayAttendance }}</h3>
        <p class="text-xs text-gray-400 mt-2">{{ $currentlyIn }} currently inside</p>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
    <a href="{{ route('admin.books.create') }}"
        class="hover-card bg-blue-700 text-white rounded-2xl px-5 py-4 font-semibold text-sm hover:bg-blue-800 transition">
        + Add Book
    </a>
    <a href="{{ route('admin.users.create') }}"
        class="hover-card bg-white border border-gray-200 text-gray-700 rounded-2xl px-5 py-4 font-semibold text-sm hover:border-blue-300 hover:text-blue-700 transition">
        + Add User
    </a>
    <a href="{{ route('admin.attendance.kiosk') }}"
        class="hover-card bg-white border border-gray-200 text-gray-700 rounded-2xl px-5 py-4 font-semibold text-sm hover:border-blue-300 hover:text-blue-700 transition">
        Kiosk Mode
    </a>
    <a href="{{ route('admin.reports.index') }}"
        class="hover-card bg-white border border-gray-200 text-gray-700 rounded-2xl px-5 py-4 font-semibold text-sm hover:border-blue-300 hover:text-blue-700 transition">
        View Reports
    </a>
</div>

<!-- Recent Activity -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    <!-- Recent Borrows -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Recent Borrowings</h3>
            <a href="{{ route('admin.borrows.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentBorrows as $borrow)
            <div class="flex justify-between items-center px-6 py-3.5">
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ $borrow->user->name ?? 'Deleted User' }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $borrow->book->title }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                    {{ $borrow->status === 'reserved' ? 'bg-blue-50 text-blue-700' :
                       ($borrow->status === 'approved' ? 'bg-yellow-50 text-yellow-700' :
                       ($borrow->status === 'claimed' ? 'bg-orange-50 text-orange-700' :
                       ($borrow->status === 'returned' ? 'bg-green-50 text-green-700' :
                       'bg-gray-50 text-gray-600'))) }}">
                    {{ ucfirst($borrow->status) }}
                </span>
            </div>
            @empty
            <div class="px-6 py-10 text-center text-gray-400 text-sm">No borrowings yet.</div>
            @endforelse
        </div>
    </div>

    <!-- Recent Attendance -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="flex justify-between items-center px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Recent Attendance</h3>
            <a href="{{ route('admin.attendance.index') }}" class="text-xs text-blue-600 font-semibold hover:underline">View all →</a>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($recentAttendance as $log)
            <div class="flex justify-between items-center px-6 py-3.5">
                <div>
                    <p class="font-semibold text-gray-800 text-sm">{{ $log->user->name }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $log->scanned_at->format('h:i A') }}</p>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                    {{ $log->type === 'time_in' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700' }}">
                    {{ $log->type === 'time_in' ? 'Time In' : 'Time Out' }}
                </span>
            </div>
            @empty
            <div class="px-6 py-10 text-center text-gray-400 text-sm">No attendance logs yet.</div>
            @endforelse
        </div>
    </div>
</div>

@endsection