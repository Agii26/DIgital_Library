@extends('layouts.app')

@section('page-title', 'Penalties Management')

@section('content')

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Summary -->
<div class="bg-white rounded-2xl shadow p-6 mb-6 border-l-4 border-red-500">
    <p class="text-sm text-gray-500">Total Unpaid Penalties</p>
    <h3 class="text-3xl font-bold text-red-500">₱{{ number_format($totalUnpaid, 2) }}</h3>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.penalties.index') }}" class="bg-white rounded-2xl shadow p-4 mb-6 flex gap-4">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search by user name..."
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
    <select name="type" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
        <option value="">All Types</option>
        <option value="overdue" {{ request('type') === 'overdue' ? 'selected' : '' }}>Overdue</option>
        <option value="damaged" {{ request('type') === 'damaged' ? 'selected' : '' }}>Damaged</option>
        <option value="lost" {{ request('type') === 'lost' ? 'selected' : '' }}>Lost</option>
    </select>
    <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
        <option value="">All Status</option>
        <option value="unpaid" {{ request('status') === 'unpaid' ? 'selected' : '' }}>Unpaid</option>
        <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
    </select>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
        Search
    </button>
    <a href="{{ route('admin.penalties.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300">
        Reset
    </a>
</form>

<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">User</th>
                <th class="px-6 py-3 text-left">Book</th>
                <th class="px-6 py-3 text-left">Type</th>
                <th class="px-6 py-3 text-left">Amount</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Date</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($penalties as $penalty)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4">
                    <p class="font-medium text-gray-800">
                        {{ $penalty->user->name ?? 'Deleted User' }}
                        @if($penalty->user && $penalty->user->trashed())
                            <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-600">Deleted</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-500">{{ $penalty->user ? ucfirst($penalty->user->role) : '-' }}</p>
                </td>
                <td class="px-6 py-4 text-gray-600">
                    {{ $penalty->physicalBorrow->book->title ?? '-' }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $penalty->type === 'overdue' ? 'bg-yellow-100 text-yellow-700' :
                           ($penalty->type === 'damaged' ? 'bg-orange-100 text-orange-700' :
                           'bg-red-100 text-red-700') }}">
                        {{ ucfirst($penalty->type) }}
                    </span>
                </td>
                <td class="px-6 py-4 font-semibold text-red-500">
                    ₱{{ number_format($penalty->amount, 2) }}
                </td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $penalty->is_paid ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $penalty->is_paid ? 'Paid' : 'Unpaid' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $penalty->created_at->format('M d, Y') }}</td>
                <td class="px-6 py-4 flex gap-2">
                    @if(!$penalty->is_paid)
                    <form method="POST" action="{{ route('admin.penalties.mark-paid', $penalty) }}">
                        @csrf
                        <button type="submit"
                            class="text-xs px-3 py-1 rounded-lg border border-green-400 text-green-500 hover:bg-green-50">
                            Mark Paid
                        </button>
                    </form>
                    @else
                        <span class="text-xs text-gray-400">{{ $penalty->paid_at?->format('M d, Y') }}</span>
                    @endif
                    @if($penalty->is_paid)
                    <a href="{{ route('admin.penalties.receipt', $penalty) }}" target="_blank"
                        class="text-xs px-3 py-1 rounded-lg border border-blue-400 text-blue-500 hover:bg-blue-50">
                        🖨️ Receipt
                    </a>
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="px-6 py-8 text-center text-gray-400">No penalties found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">
        {{ $penalties->links() }}
    </div>
</div>
@endsection