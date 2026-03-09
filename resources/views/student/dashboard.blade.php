@extends('layouts.app')

@section('page-title', 'My Dashboard')

@section('content')

@if($unpaidPenalties > 0)
<div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-center gap-4">
    <span class="text-2xl">⚠️</span>
    <div>
        <p class="font-semibold text-red-700">You have unpaid penalties!</p>
        <p class="text-sm text-red-500">Total: ₱{{ number_format($unpaidPenalties, 2) }} — Please settle at the library.</p>
    </div>
    <a href="{{ route('student.penalties.index') }}" class="ml-auto bg-red-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-red-600">
        View Penalties
    </a>
</div>
@endif

@if($overdueBorrows > 0)
<div class="bg-orange-50 border border-orange-200 rounded-2xl p-4 mb-6 flex items-center gap-4">
    <span class="text-2xl">📅</span>
    <div>
        <p class="font-semibold text-orange-700">You have {{ $overdueBorrows }} overdue book(s)!</p>
        <p class="text-sm text-orange-500">Please return them immediately to avoid additional fines.</p>
    </div>
    <a href="{{ route('student.borrows.index') }}" class="ml-auto bg-orange-500 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-orange-600">
        View Borrows
    </a>
</div>
@endif

<!-- Stats -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white rounded-2xl shadow p-5 border-l-4 border-blue-500">
        <p class="text-xs text-gray-500">Active Borrows</p>
        <h3 class="text-3xl font-bold text-blue-600">{{ $activeBorrows }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 border-l-4 border-yellow-500">
        <p class="text-xs text-gray-500">Pending Reservations</p>
        <h3 class="text-3xl font-bold text-yellow-500">{{ $pendingReservations }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 border-l-4 border-green-500">
        <p class="text-xs text-gray-500">Available Books</p>
        <h3 class="text-3xl font-bold text-green-500">{{ $availableBooks }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-5 border-l-4 border-purple-500">
        <p class="text-xs text-gray-500">Digital Books</p>
        <h3 class="text-3xl font-bold text-purple-500">{{ $digitalBooks }}</h3>
    </div>
</div>

<!-- Quick Actions -->
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <a href="{{ route('student.borrows.create') }}"
        class="bg-blue-600 text-white rounded-2xl p-4 text-center hover:bg-blue-700 transition">
        <p class="text-2xl mb-1">📚</p>
        <p class="text-sm font-semibold">Reserve Book</p>
    </a>
    <a href="{{ route('student.borrows.index') }}"
        class="bg-white rounded-2xl shadow p-4 text-center hover:shadow-md transition">
        <p class="text-2xl mb-1">📋</p>
        <p class="text-sm font-semibold text-gray-700">My Borrows</p>
    </a>
    <a href="{{ route('digital.index') }}"
        class="bg-white rounded-2xl shadow p-4 text-center hover:shadow-md transition">
        <p class="text-2xl mb-1">💻</p>
        <p class="text-sm font-semibold text-gray-700">Digital Books</p>
    </a>
    <a href="{{ route('student.penalties.index') }}"
        class="bg-white rounded-2xl shadow p-4 text-center hover:shadow-md transition">
        <p class="text-2xl mb-1">💰</p>
        <p class="text-sm font-semibold text-gray-700">My Penalties</p>
    </a>
</div>

<!-- Recent Borrows -->
<div class="bg-white rounded-2xl shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h3 class="text-lg font-semibold text-gray-700">Recent Borrowings</h3>
        <a href="{{ route('student.borrows.index') }}" class="text-xs text-blue-500 hover:underline">View all</a>
    </div>
    @forelse($recentBorrows as $borrow)
    <div class="flex justify-between items-center py-3 border-b last:border-0">
        <div>
            <p class="font-medium text-gray-800 text-sm">{{ $borrow->book->title }}</p>
            <p class="text-xs text-gray-500">{{ $borrow->reserved_at?->format('M d, Y') }}</p>
        </div>
        <div class="flex items-center gap-3">
            @if($borrow->due_date && $borrow->status === 'claimed')
                <p class="text-xs {{ now()->isAfter($borrow->due_date) ? 'text-red-500 font-semibold' : 'text-gray-400' }}">
                    Due: {{ $borrow->due_date->format('M d, Y') }}
                </p>
            @endif
            <span class="px-2 py-1 rounded-full text-xs font-semibold
                {{ $borrow->status === 'reserved' ? 'bg-blue-100 text-blue-700' :
                   ($borrow->status === 'approved' ? 'bg-yellow-100 text-yellow-700' :
                   ($borrow->status === 'claimed' ? 'bg-orange-100 text-orange-700' :
                   ($borrow->status === 'returned' ? 'bg-green-100 text-green-700' :
                   'bg-gray-100 text-gray-700'))) }}">
                {{ ucfirst($borrow->status) }}
            </span>
        </div>
    </div>
    @empty
    <p class="text-gray-400 text-sm">No borrowings yet.</p>
    @endforelse
</div>
@endsection