@extends('layouts.public')

@section('title', 'Resources — BES Digital Library')
@section('hero-badge', 'Our Collection')
@section('hero-title') Resources &amp; <em>Books</em> @endsection

@section('extra-styles')
<style>
    /* ── SEARCH ── */
    .res-toolbar {
        display: flex; gap: 1rem; align-items: center;
        flex-wrap: wrap; margin-bottom: 1.5rem;
    }

    .res-search-form {
        display: flex; align-items: center;
        flex: 1; min-width: 240px;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 6px; overflow: hidden;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .res-search-form:focus-within {
        border-color: var(--blue);
        box-shadow: 0 0 0 3px rgba(37,99,176,0.1);
    }

    .res-search-icon {
        padding: 0 0.875rem; color: var(--text-muted);
        display: flex; align-items: center;
    }

    .res-search-icon svg { width: 15px; height: 15px; }

    .res-search-form input {
        flex: 1; padding: 0.7rem 0.5rem;
        font-size: 0.875rem; font-family: var(--font-sans);
        border: none; background: transparent;
        color: var(--text); outline: none;
    }

    .res-search-form input::placeholder { color: var(--text-muted); }

    .res-search-form button {
        padding: 0.7rem 1.25rem;
        background: var(--navy-mid); color: #fff;
        font-size: 0.82rem; font-weight: 500;
        font-family: var(--font-sans);
        border: none; cursor: pointer;
        transition: background 0.18s; white-space: nowrap;
    }

    .res-search-form button:hover { background: var(--navy-light); }

    /* ── FILTER TABS ── */
    .filter-tabs { display: flex; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 2rem; }

    .filter-tab {
        padding: 0.375rem 1rem;
        background: var(--white);
        border: 1px solid var(--border);
        border-radius: 999px;
        font-size: 0.78rem; font-weight: 500;
        color: var(--text-muted);
        text-decoration: none;
        transition: all 0.18s;
    }

    .filter-tab:hover { border-color: var(--navy-mid); color: var(--navy); }

    .filter-tab.active {
        background: var(--navy-mid); color: #fff;
        border-color: var(--navy-mid);
    }

    /* ── BOOKS GRID ── */
    .books-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 1.25rem;
        margin-bottom: 2.5rem;
    }

    .book-card {
        background: var(--bg);
        border: 1px solid var(--border);
        border-radius: 8px; overflow: hidden;
        cursor: pointer;
        transition: all 0.22s;
    }

    .book-card:hover {
        border-color: var(--blue-pale);
        box-shadow: 0 6px 18px rgba(13,27,46,0.1);
        transform: translateY(-3px);
    }

    .book-cover {
        width: 100%; aspect-ratio: 3/4;
        background: linear-gradient(135deg, var(--navy-mid), var(--navy));
        display: flex; align-items: center; justify-content: center;
        position: relative; overflow: hidden;
    }

    .book-cover img { width: 100%; height: 100%; object-fit: cover; }

    .book-cover-placeholder {
        display: flex; flex-direction: column;
        align-items: center; justify-content: center;
        gap: 0.5rem; color: rgba(255,255,255,0.4);
        padding: 1rem; text-align: center;
    }

    .book-cover-placeholder svg { width: 32px; height: 32px; opacity: 0.5; }
    .book-cover-placeholder span { font-family: var(--font-serif); font-size: 0.75rem; color: rgba(255,255,255,0.6); line-height: 1.3; }

    .book-badge {
        position: absolute; top: 0.6rem; right: 0.6rem;
        padding: 0.2rem 0.5rem;
        background: rgba(184,146,42,0.9);
        color: var(--navy-deep);
        font-size: 0.62rem; font-weight: 700;
        border-radius: 3px; text-transform: uppercase; letter-spacing: 0.06em;
    }

    .book-info { padding: 0.875rem 1rem; }

    .book-title {
        font-family: var(--font-serif);
        font-size: 0.875rem; font-weight: 600;
        color: var(--navy); margin-bottom: 0.25rem;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .book-author {
        font-size: 0.75rem; color: var(--text-muted);
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
    }

    .book-category {
        display: inline-block; margin-top: 0.5rem;
        padding: 0.15rem 0.5rem;
        background: var(--blue-pale); color: var(--blue);
        font-size: 0.65rem; font-weight: 600;
        border-radius: 3px; text-transform: uppercase; letter-spacing: 0.06em;
    }

    /* ── MODAL ── */
    .modal-backdrop {
        display: none; position: fixed; inset: 0;
        background: rgba(9,21,37,0.65);
        backdrop-filter: blur(4px);
        z-index: 200;
        align-items: center; justify-content: center;
        padding: 1.5rem;
    }

    .modal-backdrop.open { display: flex; }

    .modal-box {
        background: var(--white);
        border-radius: 10px; overflow: hidden;
        width: 100%; max-width: 760px;
        box-shadow: 0 24px 64px rgba(9,21,37,0.35);
        animation: fadeUp 0.3s ease;
        max-height: 90vh; overflow-y: auto;
    }

    .modal-head {
        background: var(--navy);
        padding: 1.125rem 1.5rem;
        display: flex; align-items: center; justify-content: space-between;
        position: sticky; top: 0;
        border-bottom: 2px solid var(--gold);
    }

    .modal-head h2 {
        font-family: var(--font-serif);
        font-size: 1rem; color: #fff; font-weight: 600;
    }

    .modal-close {
        background: rgba(255,255,255,0.1); border: none;
        color: #fff; width: 28px; height: 28px;
        border-radius: 4px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.1rem; transition: background 0.18s;
    }

    .modal-close:hover { background: rgba(255,255,255,0.2); }

    .modal-body {
        padding: 1.75rem;
        display: grid;
        grid-template-columns: 180px 1fr;
        gap: 1.75rem;
    }

    .modal-cover-img {
        width: 100%; aspect-ratio: 3/4;
        object-fit: cover; border-radius: 6px;
        box-shadow: 0 4px 14px rgba(13,27,46,0.15);
    }

    .modal-cover-ph {
        width: 100%; aspect-ratio: 3/4;
        background: linear-gradient(135deg, var(--navy-mid), var(--navy));
        border-radius: 6px;
        display: flex; align-items: center; justify-content: center;
    }

    .modal-cover-ph svg { width: 36px; height: 36px; color: rgba(255,255,255,0.25); }

    .modal-details h3 {
        font-family: var(--font-serif);
        font-size: 1.3rem; font-weight: 700;
        color: var(--navy); margin-bottom: 1rem; line-height: 1.3;
    }

    .modal-info { display: flex; flex-direction: column; gap: 0.5rem; margin-bottom: 1.25rem; }

    .modal-info-row { display: flex; gap: 0.5rem; font-size: 0.845rem; }
    .modal-info-label { font-weight: 600; color: var(--navy); min-width: 80px; flex-shrink: 0; }
    .modal-info-value { color: var(--text-muted); }

    .modal-desc {
        background: var(--bg);
        border-left: 3px solid var(--gold-border);
        border-radius: 0 6px 6px 0;
        padding: 0.875rem 1rem;
        font-size: 0.875rem; line-height: 1.75;
        color: var(--text-muted); margin-bottom: 1.25rem;
    }

    .modal-login-btn {
        display: inline-flex; align-items: center; gap: 0.5rem;
        padding: 0.65rem 1.5rem;
        background: linear-gradient(135deg, var(--gold), var(--gold-light));
        color: var(--navy-deep);
        border-radius: 4px; text-decoration: none;
        font-size: 0.855rem; font-weight: 600;
        font-family: var(--font-sans);
        transition: all 0.2s; width: 100%; justify-content: center;
    }

    .modal-login-btn:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 18px rgba(184,146,42,0.35);
    }

    /* ── PAGINATION ── */
    .pag { display: flex; gap: 0.35rem; justify-content: center; flex-wrap: wrap; }

    .pag a, .pag span {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 34px; height: 34px; padding: 0 0.5rem;
        border-radius: 4px; font-size: 0.8rem;
        border: 1px solid var(--border);
        background: var(--white); color: var(--text-muted);
        text-decoration: none; transition: all 0.18s;
    }

    .pag a:hover { border-color: var(--blue); color: var(--blue); }
    .pag .active span { background: var(--navy-mid); color: #fff; border-color: var(--navy-mid); font-weight: 600; }
    .pag [aria-disabled="true"] span { opacity: 0.4; cursor: not-allowed; }

    /* ── EMPTY ── */
    .empty-state {
        text-align: center; padding: 4rem 2rem;
        color: var(--text-muted);
    }

    .empty-state svg { width: 48px; height: 48px; margin: 0 auto 1rem; display: block; opacity: 0.25; }

    .empty-state h3 {
        font-family: var(--font-serif);
        font-size: 1.1rem; color: var(--navy); margin-bottom: 0.4rem;
    }

    .empty-state p { font-size: 0.875rem; }

    @media (max-width: 768px) {
        .books-grid { grid-template-columns: repeat(2, 1fr); }
        .modal-body { grid-template-columns: 1fr; }
        .modal-cover-img, .modal-cover-ph { max-width: 160px; margin: 0 auto; }
    }
</style>
@endsection

@section('content')

{{-- Search --}}
<div class="res-toolbar">
    <form method="GET" action="{{ route('resources') }}" class="res-search-form">
        <span class="res-search-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
            </svg>
        </span>
        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search books, authors, categories…">
        @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
        <button type="submit">Search</button>
    </form>
</div>

{{-- Filter tabs --}}
<div class="filter-tabs">
    <a href="{{ route('resources') }}" class="filter-tab {{ !request('type') ? 'active' : '' }}">All ({{ $totalBooks }})</a>
    <a href="{{ route('resources', ['type' => 'physical']) }}" class="filter-tab {{ request('type') === 'physical' ? 'active' : '' }}">Physical</a>
    <a href="{{ route('resources', ['type' => 'digital']) }}" class="filter-tab {{ request('type') === 'digital' ? 'active' : '' }}">Digital</a>
</div>

{{-- Books grid --}}
@if($books->count())
<div class="books-grid">
    @foreach($books as $book)
    <div class="book-card"
        onclick="openModal(
            '{{ addslashes($book->title) }}',
            '{{ addslashes($book->author) }}',
            '{{ addslashes($book->category ?? '') }}',
            '{{ $book->type }}',
            '{{ $book->cover_image ? asset('storage/'.$book->cover_image) : '' }}',
            '{{ addslashes($book->description ?? '') }}'
        )">
        <div class="book-cover">
            @if($book->cover_image)
                <img src="{{ asset('storage/'.$book->cover_image) }}" alt="{{ $book->title }}">
            @else
                <div class="book-cover-placeholder">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/>
                    </svg>
                    <span>{{ Str::limit($book->title, 28) }}</span>
                </div>
            @endif
            <span class="book-badge">{{ ucfirst($book->type) }}</span>
        </div>
        <div class="book-info">
            <div class="book-title">{{ $book->title }}</div>
            <div class="book-author">{{ $book->author }}</div>
            @if($book->category)
                <span class="book-category">{{ $book->category }}</span>
            @endif
        </div>
    </div>
    @endforeach
</div>

@if($books->hasPages())
<div class="pag">{{ $books->appends(request()->query())->links() }}</div>
@endif

@else
<div class="empty-state">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
    </svg>
    <h3>No books found</h3>
    <p>Try adjusting your search or filters.</p>
</div>
@endif

{{-- Modal --}}
<div class="modal-backdrop" id="bookModal">
    <div class="modal-box">
        <div class="modal-head">
            <h2>Book Details</h2>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function openModal(title, author, category, type, coverUrl, description) {
    const coverHtml = coverUrl
        ? `<img src="${coverUrl}" alt="${title}" class="modal-cover-img">`
        : `<div class="modal-cover-ph"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg></div>`;

    document.getElementById('modalBody').innerHTML = `
        <div>${coverHtml}</div>
        <div class="modal-details">
            <h3>${title}</h3>
            <div class="modal-info">
                <div class="modal-info-row">
                    <span class="modal-info-label">Author</span>
                    <span class="modal-info-value">${author || '—'}</span>
                </div>
                <div class="modal-info-row">
                    <span class="modal-info-label">Category</span>
                    <span class="modal-info-value">${category || '—'}</span>
                </div>
                <div class="modal-info-row">
                    <span class="modal-info-label">Type</span>
                    <span class="modal-info-value">${type.charAt(0).toUpperCase() + type.slice(1)}</span>
                </div>
            </div>
            ${description ? `<div class="modal-desc">${description}</div>` : ''}
            <a href="{{ route('login') }}" class="modal-login-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                Sign in to Borrow
            </a>
        </div>
    `;

    document.getElementById('bookModal').classList.add('open');
    document.body.style.overflow = 'hidden';
}

function closeModal() {
    document.getElementById('bookModal').classList.remove('open');
    document.body.style.overflow = '';
}

document.getElementById('bookModal').addEventListener('click', function(e) {
    if (e.target === this) closeModal();
});

document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
</script>
@endsection