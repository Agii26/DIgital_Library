@extends('layouts.app')

@section('page-title', 'Borrowing Management')

@section('content')

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Filters -->
<form method="GET" action="{{ route('admin.borrows.index') }}" class="bg-white rounded-2xl shadow p-4 mb-6 flex gap-4">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search user or book..."
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
    <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="">All Status</option>
        <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
        <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
        <option value="claimed" {{ request('status') === 'claimed' ? 'selected' : '' }}>Claimed</option>
        <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
        <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
    </select>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
        Search
    </button>
    <a href="{{ route('admin.borrows.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300">
        Reset
    </a>
</form>

<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Borrower</th>
                <th class="px-6 py-3 text-left">Book</th>
                <th class="px-6 py-3 text-left">Accession No.</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Reserved</th>
                <th class="px-6 py-3 text-left">Due Date</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($borrows as $borrow)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">
                        {{ $borrow->user->name ?? 'Deleted User' }}
                        @if($borrow->user && $borrow->user->trashed())
                            <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-600">Deleted</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-500">{{ $borrow->user ? ucfirst($borrow->user->role) : '-' }}</p>
                </td>
                <td class="px-6 py-4 font-medium text-gray-800">{{ $borrow->book->title }}</td>
                <td class="px-6 py-4 font-mono text-gray-600">{{ $borrow->book->accession_no }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $borrow->status === 'reserved' ? 'bg-blue-100 text-blue-700' :
                           ($borrow->status === 'approved' ? 'bg-yellow-100 text-yellow-700' :
                           ($borrow->status === 'claimed' ? 'bg-orange-100 text-orange-700' :
                           ($borrow->status === 'returned' ? 'bg-green-100 text-green-700' :
                           'bg-gray-100 text-gray-700'))) }}">
                        {{ ucfirst($borrow->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $borrow->reserved_at?->format('M d, Y') }}</td>
                <td class="px-6 py-4 text-gray-600">
                    @if($borrow->due_date)
                        <span class="{{ now()->isAfter($borrow->due_date) ? 'text-red-500 font-semibold' : '' }}">
                            {{ $borrow->due_date->format('M d, Y') }}
                        </span>
                    @else
                        -
                    @endif
                </td>
                <td class="px-6 py-4">
                    <a href="{{ route('admin.borrows.show', $borrow) }}"
                        class="text-xs px-3 py-1 rounded-lg border border-blue-400 text-blue-500 hover:bg-blue-50">
                        Manage
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-400">No borrowing records found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">
        {{ $borrows->links() }}
    </div>
</div>
@endsection