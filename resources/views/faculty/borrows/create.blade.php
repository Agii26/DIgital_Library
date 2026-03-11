@extends('layouts.app')

@section('page-title', 'Reserve a Book')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Reserve a Book</h1>
        <p class="page-subtitle">Choose an available title to add to your borrowing queue</p>
    </div>
</div>

{{-- Form Card --}}
<div style="max-width:680px;">
    <div class="card">
        <div class="card-header">
            <span class="card-title">Reservation Details</span>
        </div>

        <div class="card-body">

            @if($errors->any())
                <div class="alert alert-danger" style="margin-bottom:1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('faculty.borrows.store') }}">
                @csrf

                <div class="form-group">
                    <label class="form-label">
                        Select Book <span class="required">*</span>
                    </label>
                    <select name="book_id" class="form-control" required>
                        <option value="">Choose a book...</option>
                        @foreach($books as $book)
                            <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}>
                                {{ $book->title }} &mdash; {{ $book->author }} ({{ $book->accession_no }})
                            </option>
                        @endforeach
                    </select>
                    <span class="form-hint">Only available books are listed. Reserved or borrowed titles will not appear.</span>
                </div>

                <div style="display:flex;gap:0.75rem;margin-top:1.5rem;flex-wrap:wrap;">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:6px;vertical-align:-2px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/>
                        </svg>
                        Reserve Book
                    </button>
                    <a href="{{ route('faculty.borrows.index') }}" class="btn btn-secondary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:6px;vertical-align:-2px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                        </svg>
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection