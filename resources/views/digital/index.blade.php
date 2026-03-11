@extends('layouts.app')

@section('page-title', 'Digital Books')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Digital Books</h1>
        <p class="page-subtitle">Read e-books directly in your browser</p>
    </div>
</div>

{{-- Books Grid --}}
@forelse($books as $book)
    @if($loop->first)
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;" class="ra-digital-grid">
    @endif

    <div class="card ra-digital-card" style="overflow:hidden;display:flex;flex-direction:column;transition:box-shadow 0.2s,transform 0.2s;">

        {{-- Cover --}}
        @if($book->cover_image)
            <img
                src="{{ asset('storage/' . $book->cover_image) }}"
                alt="{{ $book->title }}"
                style="width:100%;height:180px;object-fit:cover;display:block;border-bottom:1px solid var(--border);"
            />
        @else
            <div style="width:100%;height:180px;background:linear-gradient(135deg,#1a3a6b 0%,#2d1b6e 100%);display:flex;align-items:center;justify-content:center;border-bottom:1px solid var(--border);flex-shrink:0;position:relative;overflow:hidden;">
                {{-- Decorative lines --}}
                <div style="position:absolute;inset:0;opacity:0.07;">
                    <div style="position:absolute;top:20%;left:-10%;width:120%;height:1px;background:#fff;transform:rotate(-8deg);"></div>
                    <div style="position:absolute;top:45%;left:-10%;width:120%;height:1px;background:#fff;transform:rotate(-8deg);"></div>
                    <div style="position:absolute;top:70%;left:-10%;width:120%;height:1px;background:#fff;transform:rotate(-8deg);"></div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" width="44" height="44" fill="none" viewBox="0 0 24 24" stroke="rgba(212,169,58,0.65)" stroke-width="1.25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
        @endif

        {{-- Card Body --}}
        <div class="card-body" style="flex:1;display:flex;flex-direction:column;gap:0.2rem;padding:1rem;">

            {{-- Reading time badge --}}
            <div style="margin-bottom:0.35rem;">
                <span class="badge badge-blue" style="display:inline-flex;align-items:center;gap:4px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ \App\Models\Setting::get('digital_reading_time', 60) }} mins
                </span>
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

            {{-- Action --}}
            <div style="margin-top:auto;padding-top:0.75rem;">
                <a href="{{ route('digital.read', $book) }}" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;text-decoration:none;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:-2px;flex-shrink:0;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/>
                    </svg>
                    Read Now
                </a>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="empty-state-title">No Digital Books Yet</div>
                <div class="empty-state-text">There are no e-books available at the moment. Check back later or contact the library staff.</div>
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

{{-- Responsive styles --}}
<style>
    .ra-digital-card:hover {
        box-shadow: var(--shadow-md);
        transform: translateY(-2px);
    }

    @media (max-width: 1100px) {
        .ra-digital-grid {
            grid-template-columns: repeat(3, 1fr) !important;
        }
    }
    @media (max-width: 768px) {
        .ra-digital-grid {
            grid-template-columns: repeat(2, 1fr) !important;
        }
        .page-title-wrap {
            flex-direction: column;
            gap: 0.75rem;
        }
    }
    @media (max-width: 480px) {
        .ra-digital-grid {
            grid-template-columns: 1fr !important;
        }
        .ra-digital-card {
            display: flex !important;
            flex-direction: row !important;
        }
        .ra-digital-card > img,
        .ra-digital-card > div:first-child {
            width: 100px !important;
            height: auto !important;
            min-height: 130px !important;
            flex-shrink: 0;
        }
        .ra-digital-card .card-body {
            padding: 0.75rem !important;
        }
    }
</style>

@endsection