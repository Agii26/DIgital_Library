@extends('layouts.app')

@section('page-title', 'Digital Books')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
    @forelse($books as $book)
    <div class="bg-white rounded-2xl shadow hover:shadow-md transition overflow-hidden">
        @if($book->cover_image)
            <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-48 object-cover" />
        @else
            <div class="w-full h-48 bg-gradient-to-br from-blue-100 to-purple-100 flex items-center justify-center">
                <span class="text-5xl">📖</span>
            </div>
        @endif
        <div class="p-4">
            <h3 class="font-semibold text-gray-800 text-sm">{{ $book->title }}</h3>
            <p class="text-xs text-gray-500 mt-1">{{ $book->author }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $book->category ?? 'Uncategorized' }}</p>
            <div class="flex items-center justify-between mt-3">
                <span class="text-xs text-purple-600 font-semibold">
                    ⏱ {{ \App\Models\Setting::get('digital_reading_time', 60) }} mins
                </span>
                <a href="{{ route('digital.read', $book) }}"
                    class="bg-blue-600 text-white text-xs px-3 py-1 rounded-lg hover:bg-blue-700">
                    Read Now
                </a>
            </div>
        </div>
    </div>
    @empty
    <div class="col-span-4 text-center text-gray-400 py-12">
        <p class="text-4xl mb-3">📚</p>
        <p>No digital books available yet.</p>
    </div>
    @endforelse
</div>

<div class="mt-6">
    {{ $books->links() }}
</div>
@endsection