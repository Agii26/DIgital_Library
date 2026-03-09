@extends('layouts.app')

@section('page-title', 'Book Details')

@section('content')
<div class="max-w-4xl">

    <!-- Book Card -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 mb-5">
        <div class="flex gap-6">

            <!-- Cover -->
            <div class="shrink-0">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                        class="w-32 h-44 object-cover rounded-xl shadow-md" />
                @else
                    <div class="w-32 h-44 bg-gray-100 rounded-xl flex items-center justify-center">
                        <span class="text-gray-300 text-xs font-semibold">No Cover</span>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div class="flex-1 min-w-0">
                <div class="flex justify-between items-start gap-4">
                    <div>
                        <h2 class="text-2xl font-black text-gray-900">{{ $book->title }}</h2>
                        <p class="text-gray-400 mt-1 text-sm">by {{ $book->author }}</p>
                    </div>
                    <a href="{{ route('admin.books.edit', $book) }}"
                        class="shrink-0 bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-50 hover:text-blue-700 transition">
                        Edit Book
                    </a>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mt-5">
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Accession No.</p>
                        <p class="font-mono text-sm font-semibold text-gray-800 mt-1">{{ $book->accession_no }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Category</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">{{ $book->category ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Price</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">₱{{ number_format($book->price, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Type</p>
                        <span class="mt-1 inline-block px-2.5 py-1 rounded-lg text-xs font-semibold
                            {{ $book->type === 'physical' ? 'bg-orange-50 text-orange-700' : 'bg-purple-50 text-purple-700' }}">
                            {{ ucfirst($book->type) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Status</p>
                        <span class="mt-1 inline-block px-2.5 py-1 rounded-lg text-xs font-semibold
                            {{ $book->status === 'available' ? 'bg-green-50 text-green-700' :
                               ($book->status === 'borrowed' ? 'bg-yellow-50 text-yellow-700' :
                               ($book->status === 'reserved' ? 'bg-blue-50 text-blue-700' :
                               ($book->status === 'damaged' ? 'bg-red-50 text-red-700' :
                               'bg-gray-50 text-gray-600'))) }}">
                            {{ ucfirst($book->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Damage Count</p>
                        <p class="text-sm font-semibold text-gray-800 mt-1">{{ $book->damage_count ?? 0 }}x</p>
                    </div>
                </div>

                @if($book->description)
                <div class="mt-5 pt-5 border-t border-gray-50">
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Description</p>
                    <p class="text-sm text-gray-600 leading-relaxed">{{ $book->description }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Borrowing History -->
    @if($book->type === 'physical')
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
        <div class="px-6 py-4 border-b border-gray-100">
            <h3 class="font-bold text-gray-800">Borrowing History</h3>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($book->physicalBorrows()->with('user')->latest()->get() as $borrow)
            <div class="flex justify-between items-center px-6 py-4">
                <div>
                    <p class="font-semibold text-gray-800 text-sm">
                        {{ $borrow->user->name ?? 'Deleted User' }}
                        @if($borrow->user && $borrow->user->trashed())
                            <span class="ml-1 px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-600">Deleted</span>
                        @endif
                    </p>
                    <p class="text-xs text-gray-400 mt-0.5">
                        Reserved: {{ $borrow->reserved_at?->format('M d, Y') ?? '—' }}
                        @if($borrow->returned_at)
                            · Returned: {{ $borrow->returned_at->format('M d, Y') }}
                        @endif
                    </p>
                </div>
                <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                    {{ $borrow->status === 'returned' ? 'bg-green-50 text-green-700' :
                       ($borrow->status === 'claimed' ? 'bg-orange-50 text-orange-700' :
                       ($borrow->status === 'approved' ? 'bg-yellow-50 text-yellow-700' :
                       ($borrow->status === 'cancelled' ? 'bg-gray-50 text-gray-600' :
                       'bg-blue-50 text-blue-700'))) }}">
                    {{ ucfirst($borrow->status) }}
                </span>
            </div>
            @empty
            <div class="px-6 py-12 text-center text-gray-400 text-sm">
                No borrowing history yet.
            </div>
            @endforelse
        </div>
    </div>
    @endif

</div>
@endsection