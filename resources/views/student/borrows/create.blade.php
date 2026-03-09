@extends('layouts.app')

@section('page-title', 'Reserve a Book')

@section('content')
<div class="max-w-3xl">
    <div class="bg-white rounded-2xl shadow p-6">

        @if($errors->any())
            <div class="bg-red-100 text-red-600 text-sm px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('student.borrows.store') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Book</label>
                <select name="book_id"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    required>
                    <option value="">Choose a book...</option>
                    @foreach($books as $book)
                        <option value="{{ $book->id }}">{{ $book->title }} — {{ $book->author }} ({{ $book->accession_no }})</option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-3">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    Reserve Book
                </button>
                <a href="{{ route('student.borrows.index') }}"
                    class="bg-gray-200 text-gray-700 px-6 py-2 rounded-lg font-semibold hover:bg-gray-300">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>
@endsection