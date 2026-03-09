@extends('layouts.app')

@section('page-title', 'My Penalties')

@section('content')

@if($totalUnpaid > 0)
<div class="bg-red-50 border border-red-200 rounded-2xl p-4 mb-6 flex items-center gap-4">
    <span class="text-2xl">⚠️</span>
    <div>
        <p class="font-semibold text-red-700">You have unpaid penalties!</p>
        <p class="text-sm text-red-500">Total unpaid: ₱{{ number_format($totalUnpaid, 2) }} — Please settle at the library.</p>
    </div>
</div>
@endif

<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Book</th>
                <th class="px-6 py-3 text-left">Type</th>
                <th class="px-6 py-3 text-left">Amount</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Date</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($penalties as $penalty)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">
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
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-400">No penalties found. 🎉</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">
        {{ $penalties->links() }}
    </div>
</div>
@endsection