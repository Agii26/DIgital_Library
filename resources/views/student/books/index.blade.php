@extends('layouts.app')

@section('page-title', 'Browse Physical Books')

@section('content')

<!-- Filters -->
<form method="GET" action="{{ route('student.books.index') }}" class="bg-white rounded-2xl shadow p-4 mb-6 flex gap-4">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search title, author, accession no..."
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
    <select name="category" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
        <option value="">All Categories</option>
        @foreach($categories as $category)
            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                {{ $category }}
            </option>
        @endforeach
    </select>
    <select name="status" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
        <option value="">All Status</option>
        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
        <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
        <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
    </select>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
        Search
    </button>
    <a href="{{ route('student.books.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300">
        Reset
    </a>
</form>

<!-- Books Grid -->
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($books as $book)
    <div class="bg-white rounded-2xl shadow hover:shadow-md transition overflow-hidden">
        @if($book->cover_image)
            <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-48 object-cover" />
        @else
            <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-green-100 flex items-center justify-center">
                <span class="text-5xl">📚</span>
            </div>
        @endif
        <div class="p-4">
            <h3 class="font-semibold text-gray-800 text-sm">{{ $book->title }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ $book->author }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $book->category ?? 'Uncategorized' }}</p>
            <p class="text-xs font-mono text-gray-400 mt-1">{{ $book->accession_no }}</p>
            <div class="flex items-center justify-between mt-3">
                <span class="px-2 py-1 rounded-full text-xs font-semibold
                    {{ $book->status === 'available' ? 'bg-green-100 text-green-700' :
                       ($book->status === 'borrowed' ? 'bg-yellow-100 text-yellow-700' :
                       ($book->status === 'reserved' ? 'bg-blue-100 text-blue-700' :
                       'bg-red-100 text-red-700')) }}">
                    {{ ucfirst($book->status) }}
                </span>
                @if($book->status === 'available')
                <form method="POST" action="{{ route('student.borrows.store') }}">
                    @csrf
                    <input type="hidden" name="book_id" value="{{ $book->id }}" />
                    <button type="submit"
                        class="bg-blue-600 text-white text-xs px-3 py-1 rounded-lg hover:bg-blue-700">
                        Reserve
                    </button>
                </form>
                @else
                <span class="text-xs text-gray-400">Unavailable</span>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-4 text-center text-gray-400 py-12">
        <p class="text-4xl mb-3">📚</p>
        <p>No books found.</p>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $books->links() }}
</div>
@endsection