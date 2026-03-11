@extends('layouts.app')

@section('page-title', 'Browse Physical Books')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Browse Physical Books</h1>
        <p class="page-subtitle">Search and reserve books from the library collection</p>
    </div>
</div>

{{-- Filters --}}
<form method="GET" action="{{ route('student.books.index') }}">
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body" style="padding:1rem 1.25rem;">
            <div style="display:flex;flex-wrap:wrap;gap:0.75rem;align-items:center;">
                <div style="flex:1;min-width:200px;">
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search title, author, accession no..."
                        class="form-control"
                        style="margin-bottom:0;"
                    />
                </div>
                <div style="min-width:160px;">
                    <select name="category" class="form-control" style="margin-bottom:0;">
                        <option value="">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') === $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div style="min-width:140px;">
                    <select name="status" class="form-control" style="margin-bottom:0;">
                        <option value="">All Status</option>
                        <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                        <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                        <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                    </select>
                </div>
                <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                    <button type="submit" class="btn btn-primary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:-2px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 11A6 6 0 1 1 5 11a6 6 0 0 1 12 0z"/>
                        </svg>
                        Search
                    </button>
                    <a href="{{ route('student.books.index') }}" class="btn btn-secondary btn-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:-2px;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

{{-- Books Grid --}}
@forelse($books as $book)
    @if($loop->first)
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;" class="ra-book-grid">
    @endif

    <div class="card ra-book-card" style="overflow:hidden;display:flex;flex-direction:column;transition:box-shadow 0.2s,transform 0.2s;">

        {{-- Cover Image --}}
        @if($book->cover_image)
            <img
                src="{{ asset('storage/' . $book->cover_image) }}"
                alt="{{ $book->title }}"
                style="width:100%;height:180px;object-fit:cover;display:block;border-bottom:1px solid var(--border);"
            />
        @else
            <div style="width:100%;height:180px;background:linear-gradient(135deg,#1a3a6b 0%,#0d1b2e 100%);display:flex;align-items:center;justify-content:center;border-bottom:1px solid var(--border);flex-shrink:0;">
                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="none" viewBox="0 0 24 24" stroke="rgba(212,169,58,0.6)" stroke-width="1.25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                </svg>
            </div>
        @endif

        {{-- Card Body --}}
        <div class="card-body" style="flex:1;display:flex;flex-direction:column;gap:0.25rem;padding:1rem;">

            {{-- Status Badge --}}
            <div style="margin-bottom:0.35rem;">
                @if($book->status === 'available')
                    <span class="badge badge-success">Available</span>
                @elseif($book->status === 'borrowed')
                    <span class="badge badge-warning">Borrowed</span>
                @elseif($book->status === 'reserved')
                    <span class="badge badge-blue">Reserved</span>
                @else
                    <span class="badge badge-danger">{{ ucfirst($book->status) }}</span>
                @endif
            </div>

            {{-- Title --}}
            <div style="font-family:var(--font-serif);font-size:0.9rem;font-weight:600;color:var(--text-head);line-height:1.35;margin-bottom:0.15rem;">
                {{ $book->title }}
            </div>

            {{-- Author --}}
            <div style="font-size:0.78rem;color:var(--text-muted);">
                {{ $book->author ?? '&mdash;' }}
            </div>

            {{-- Category --}}
            <div style="font-size:0.75rem;color:var(--text-dim);margin-top:0.1rem;">
                {{ $book->category ?? 'Uncategorized' }}
            </div>

            {{-- Accession No --}}
            <div style="font-family:monospace;font-size:0.72rem;color:var(--text-dim);margin-top:0.1rem;letter-spacing:0.03em;">
                {{ $book->accession_no ?? '&mdash;' }}
            </div>

            {{-- Action --}}
            <div style="margin-top:auto;padding-top:0.75rem;">
                @if($book->status === 'available')
                    <form method="POST" action="{{ route('student.borrows.store') }}">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}" />
                        <button type="submit" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:-2px;flex-shrink:0;">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 14v6m-3-3h6M6 10h2a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2zm10 0h2a2 2 0 002-2V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v2a2 2 0 002 2zM6 20h2a2 2 0 002-2v-2a2 2 0 00-2-2H6a2 2 0 00-2 2v2a2 2 0 002 2z"/>
                            </svg>
                            Reserve
                        </button>
                    </form>
                @else
                    <button class="btn btn-secondary btn-sm" disabled style="width:100%;justify-content:center;opacity:0.55;cursor:not-allowed;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:-2px;flex-shrink:0;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                        </svg>
                        Unavailable
                    </button>
                @endif
            </div>
        </div>
    </div>

    @if($loop->last)
    </div>
    @endif

@empty
    <div class="card">
        <div class="card-body">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                </div>
                <div class="empty-state-title">No Books Found</div>
                <div class="empty-state-text">Try adjusting your search filters or reset to browse all available titles.</div>
            </div>
        </div>
    </div>
@endforelse

{{-- Pagination --}}
@if($books->hasPages())
<div class="card" style="margin-top:1.5rem;">
    <div class="card-footer" style="display:flex;justify-content:center;">
        {{ $books->links() }}
    </div>
</div>
@endif

{{-- Mobile Responsive Styles --}}
<style>
    .ra-book-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    /* 4-col default → 3-col → 2-col → 1-col */
    @media (max-width: 1100px) {
        .ra-book-grid {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    @media (max-width: 768px) {
        .ra-book-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        .page-title-wrap {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
    @media (max-width: 480px) {
        .ra-book-grid {
            grid-template-columns: 1fr !important;
        }
        .ra-book-card {
            display: flex !important;
            flex-direction: row !important;
        }
        .ra-book-card > img,
        .ra-book-card > div:first-child {
            width: 100px !important;
            height: auto !important;
            min-height: 130px !important;
            flex-shrink: 0;
        }
        .ra-book-card .card-body {
            padding: 0.75rem !important;
        }
    }
</style>

@endsection