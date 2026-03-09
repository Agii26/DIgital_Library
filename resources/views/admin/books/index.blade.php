@extends('layouts.app')

@section('page-title', 'Book Management')

@section('content')

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-4 text-sm font-medium">
        {{ session('success') }}
    </div>
@endif

<!-- Header -->
<div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
    <a href="{{ route('admin.books.create') }}"
        class="bg-blue-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-blue-800 transition shadow-sm">
        + Add Book
    </a>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.books.index') }}"
    class="bg-white rounded-2xl border border-gray-100 shadow-sm p-4 mb-6 flex flex-wrap gap-3">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search title, author, accession no..."
        class="flex-1 min-w-[200px] border border-gray-200 bg-gray-50 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400 focus:bg-white transition" />
    <select name="type" class="border border-gray-200 bg-gray-50 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="">All Types</option>
        <option value="physical" {{ request('type') === 'physical' ? 'selected' : '' }}>Physical</option>
        <option value="digital" {{ request('type') === 'digital' ? 'selected' : '' }}>Digital</option>
    </select>
    <select name="status" class="border border-gray-200 bg-gray-50 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
        <option value="">All Status</option>
        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
        <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
        <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
        <option value="damaged" {{ request('status') === 'damaged' ? 'selected' : '' }}>Damaged</option>
        <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
    </select>
    <button type="submit"
        class="bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-800 transition">
        Search
    </button>
    <a href="{{ route('admin.books.index') }}"
        class="bg-gray-100 text-gray-600 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
        Reset
    </a>
</form>

<!-- Table -->
<div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Cover</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Accession No.</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Title & Author</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Category</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Type</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Price</th>
                <th class="px-6 py-4 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($books as $book)
            <tr class="hover:bg-slate-50 transition-colors">
                <td class="px-6 py-4">
                    @if($book->cover_image)
                        <img src="{{ asset('storage/' . $book->cover_image) }}"
                            class="w-9 h-13 object-cover rounded-lg shadow-sm" />
                    @else
                        <div class="w-9 h-13 bg-gray-100 rounded-lg flex items-center justify-center">
                            <span class="text-gray-300 text-xs font-bold">N/A</span>
                        </div>
                    @endif
                </td>
                <td class="px-6 py-4">
                    <span class="font-mono text-xs text-gray-500 bg-gray-50 px-2 py-1 rounded-lg">{{ $book->accession_no }}</span>
                </td>
                <td class="px-6 py-4">
                    <p class="font-semibold text-gray-800">{{ $book->title }}</p>
                    <p class="text-xs text-gray-400 mt-0.5">{{ $book->author }}</p>
                </td>
                <td class="px-6 py-4 text-gray-500 text-sm">{{ $book->category ?? '—' }}</td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                        {{ $book->type === 'physical' ? 'bg-orange-50 text-orange-700' : 'bg-purple-50 text-purple-700' }}">
                        {{ ucfirst($book->type) }}
                    </span>
                </td>
                <td class="px-6 py-4">
                    <span class="px-2.5 py-1 rounded-lg text-xs font-semibold
                        {{ $book->status === 'available' ? 'bg-green-50 text-green-700' :
                           ($book->status === 'borrowed' ? 'bg-yellow-50 text-yellow-700' :
                           ($book->status === 'reserved' ? 'bg-blue-50 text-blue-700' :
                           ($book->status === 'damaged' ? 'bg-red-50 text-red-700' :
                           'bg-gray-50 text-gray-600'))) }}">
                        {{ ucfirst($book->status) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-700 font-medium">₱{{ number_format($book->price, 2) }}</td>
                <td class="px-6 py-4">
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.books.show', $book) }}"
                            class="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-blue-50 hover:text-blue-700 font-medium transition">
                            View
                        </a>
                        <a href="{{ route('admin.books.edit', $book) }}"
                            class="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-yellow-50 hover:text-yellow-700 font-medium transition">
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.books.destroy', $book) }}">
                            @csrf
                            @method('DELETE')
                            <button onclick="return confirm('Delete this book?')"
                                class="text-xs px-3 py-1.5 rounded-lg bg-gray-100 text-gray-600 hover:bg-red-50 hover:text-red-700 font-medium transition">
                                Delete
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" class="px-6 py-16 text-center">
                    <p class="text-gray-300 text-4xl mb-3">📚</p>
                    <p class="text-gray-400 text-sm font-medium">No books found.</p>
                    <a href="{{ route('admin.books.create') }}" class="text-blue-600 text-sm font-semibold mt-2 inline-block hover:underline">Add your first book →</a>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4 border-t border-gray-50">
        {{ $books->links() }}
    </div>
</div>
@endsection