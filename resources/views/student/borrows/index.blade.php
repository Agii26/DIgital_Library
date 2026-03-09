@extends('layouts.app')

@section('page-title', 'My Borrows')

@section('content')
<div class="mb-6">
    <a href="{{ route('student.borrows.create') }}"
        class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
        + Reserve a Book
    </a>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Book</th>
                <th class="px-6 py-3 text-left">Accession No.</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Reserved</th>
                <th class="px-6 py-3 text-left">Due Date</th>
                <th class="px-6 py-3 text-left">Condition</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($borrows as $borrow)
            <tr class="hover:bg-gray-50">
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
                <td class="px-6 py-4 text-gray-600">{{ $borrow->condition ? ucfirst($borrow->condition) : '-' }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-400">No borrowing records yet.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">
        {{ $borrows->links() }}
    </div>
</div>
@endsection