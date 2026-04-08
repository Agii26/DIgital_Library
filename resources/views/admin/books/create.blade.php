@extends('layouts.app')

@section('page-title', 'Add New Book')

@section('content')

<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Add Book / Copy</h1>
        <p class="page-subtitle">Register a new book or add a copy to an existing one</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="vertical-align:middle;margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
            Back to Books
        </a>
    </div>
</div>

@if ($errors->any())
    <div class="alert alert-danger" style="margin-bottom:1.25rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
        {{ $errors->first() }}
    </div>
@endif

<form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data" id="book-form">
    @csrf

    <div style="display:grid;grid-template-columns:1fr;gap:1.25rem;">

        {{-- Step 1: Accession No. + Type --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Copy Details</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Accession No. <span class="required">*</span></label>
                        <input type="text" name="accession_no" id="accession_no"
                            value="{{ old('accession_no') }}"
                            class="form-control" placeholder="e.g. ACC-001" required />
                        <span class="form-hint">Must be unique — identifies this physical copy.</span>
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Type <span class="required">*</span></label>
                        <select name="type" id="book-type" class="form-control" required>
                            <option value="">Select type...</option>
                            <option value="physical" {{ old('type') === 'physical' ? 'selected' : '' }}>Physical</option>
                            <option value="digital"  {{ old('type') === 'digital'  ? 'selected' : '' }}>Digital</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        {{-- Step 2: Book selection --}}
        <div class="card" id="book-selection-card" style="display:none;">
            <div class="card-header">
                <span class="card-title">Book</span>
            </div>
            <div class="card-body">

                <div class="form-group" style="position:relative;margin-bottom:0;">
                    <label class="form-label">Search existing books or add new <span class="required">*</span></label>
                    <input type="text" id="book-search-input" class="form-control"
                        placeholder="Type title or author to search..." autocomplete="off" />
                    <div id="book-dropdown" style="display:none;position:absolute;top:100%;left:0;right:0;z-index:50;background:var(--surface);border:1px solid var(--border);border-radius:var(--radius);box-shadow:var(--shadow-md);max-height:280px;overflow-y:auto;margin-top:2px;"></div>
                </div>

                <input type="hidden" name="existing_book_id" id="existing_book_id" value="{{ old('existing_book_id') }}">

                {{-- Existing book preview --}}
                <div id="existing-book-preview" style="display:none;align-items:center;gap:1rem;padding:0.875rem 1rem;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius);margin-top:0.875rem;">
                    <img id="preview-cover" src="" alt="Cover" style="width:48px;height:66px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border);flex-shrink:0;" />
                    <div id="preview-no-cover" style="display:none;width:48px;height:66px;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius);flex-shrink:0;align-items:center;justify-content:center;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:var(--text-dim)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    </div>
                    <div>
                        <p id="preview-title" style="font-weight:600;font-size:0.9rem;color:var(--text-head);margin:0 0 0.2rem;"></p>
                        <p id="preview-author" style="font-size:0.8rem;color:var(--text-muted);margin:0 0 0.4rem;"></p>
                        <span style="font-size:0.72rem;color:var(--color-text-success);font-weight:600;">&#10003; A new copy will be added to this book</span>
                    </div>
                    <button type="button" id="clear-selection" style="margin-left:auto;background:none;border:none;cursor:pointer;color:var(--text-dim);font-size:1.1rem;line-height:1;" title="Clear selection">&#10005;</button>
                </div>

                {{-- New book indicator --}}
                <div id="new-book-indicator" style="display:none;padding:0.6rem 0.875rem;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius);margin-top:0.875rem;font-size:0.82rem;color:var(--text-muted);">
                    &#xFF0B; Filling in details for a <strong style="color:var(--text-head);">new book</strong>
                </div>

            </div>
        </div>

        {{-- New book fields --}}
        <div id="new-book-fields" style="display:none;grid-template-columns:1fr;gap:1.25rem;">

            <div class="card">
                <div class="card-header"><span class="card-title">Book Information</span></div>
                <div class="card-body">
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

                        <div class="form-group">
                            <label class="form-label">Title <span class="required">*</span></label>
                            <input type="text" name="title" id="title-input"
                                value="{{ old('title') }}"
                                class="form-control" placeholder="Book title" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Author <span class="required">*</span></label>
                            <input type="text" name="author"
                                value="{{ old('author') }}"
                                class="form-control" placeholder="Author name" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Category</label>
                            <input type="text" name="category"
                                value="{{ old('category') }}"
                                class="form-control" placeholder="e.g. Science, Fiction" />
                        </div>

                        <div class="form-group">
                            <label class="form-label">Price (&#8369;) <span class="required">*</span></label>
                            <input type="number" name="price"
                                value="{{ old('price', 0) }}"
                                step="0.01" min="0"
                                class="form-control" placeholder="0.00" />
                        </div>

                        <div class="form-group" style="grid-column:1 / -1;">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="3" class="form-control"
                                placeholder="Brief description..." style="resize:vertical;">{{ old('description') }}</textarea>
                        </div>

                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header"><span class="card-title">Cover Image</span></div>
                <div class="card-body">
                    <div style="display:flex;align-items:flex-start;gap:1.5rem;">
                        <div id="cover-preview" style="width:90px;height:126px;border-radius:var(--radius);overflow:hidden;border:1px solid var(--border);background:var(--surface-2);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                            <span style="font-size:0.7rem;color:var(--text-dim);text-align:center;line-height:1.5;">No<br>Image</span>
                        </div>
                        <div style="flex:1;">
                            <div class="form-group">
                                <label class="form-label">Upload Cover</label>
                                <input type="file" name="cover_image" accept="image/*" id="cover-input" class="form-control" />
                                <span class="form-hint">JPG, PNG or WEBP. Max 2MB.</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="digital-fields" class="card" style="display:none;border-color:var(--blue-pale);">
                <div class="card-header" style="border-color:var(--blue-pale);">
                    <span class="card-title" style="color:var(--blue-bright);">Digital Book File</span>
                    <span class="badge badge-blue">Digital only</span>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">PDF File <span class="required">*</span></label>
                        <input type="file" name="file_path" accept=".pdf" class="form-control" />
                        <span class="form-hint">PDF files only. Max 50MB.</span>
                    </div>
                </div>
            </div>

        </div>

        {{-- Form Actions --}}
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <button type="submit" class="btn btn-primary" id="submit-btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="vertical-align:middle;margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                <span id="submit-label">Add Book</span>
            </button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Cancel</a>
        </div>

    </div>
</form>

<script>
const SEARCH_URL = "{{ route('admin.books.search') }}";

const typeSelect       = document.getElementById('book-type');
const selectionCard    = document.getElementById('book-selection-card');
const searchInput      = document.getElementById('book-search-input');
const dropdown         = document.getElementById('book-dropdown');
const existingBookId   = document.getElementById('existing_book_id');
const existingPreview  = document.getElementById('existing-book-preview');
const newBookIndicator = document.getElementById('new-book-indicator');
const newBookFields    = document.getElementById('new-book-fields');
const digitalFields    = document.getElementById('digital-fields');
const coverInput       = document.getElementById('cover-input');
const coverPreview     = document.getElementById('cover-preview');
const submitLabel      = document.getElementById('submit-label');
const titleInput       = document.getElementById('title-input');

let selectedMode  = null;
let debounceTimer = null;

// Show selection card when type is chosen
typeSelect.addEventListener('change', function () {
    selectionCard.style.display = this.value ? 'block' : 'none';
    if (selectedMode === 'new') {
        digitalFields.style.display = this.value === 'digital' ? 'block' : 'none';
    }
    if (!this.value) resetSelection();
});

// Search input with debounce
searchInput.addEventListener('input', function () {
    clearTimeout(debounceTimer);
    const q = this.value.trim();
    if (!q) { dropdown.style.display = 'none'; return; }
    debounceTimer = setTimeout(() => fetchBooks(q), 250);
});

searchInput.addEventListener('focus', function () {
    if (this.value.trim()) fetchBooks(this.value.trim());
});

document.addEventListener('click', function (e) {
    if (!e.target.closest('#book-selection-card')) dropdown.style.display = 'none';
});

function fetchBooks(q) {
    fetch(`${SEARCH_URL}?q=${encodeURIComponent(q)}&type=${encodeURIComponent(typeSelect.value)}`)
        .then(r => r.json())
        .then(books => renderDropdown(books, q));
}

function renderDropdown(books, q) {
    dropdown.innerHTML = '';

    books.forEach(book => {
        const item = document.createElement('div');
        item.style.cssText = 'display:flex;align-items:center;gap:0.75rem;padding:0.6rem 0.875rem;cursor:pointer;border-bottom:1px solid var(--border);transition:background 0.1s;';
        item.innerHTML = `
            <div style="flex-shrink:0;width:32px;height:44px;border-radius:3px;overflow:hidden;background:var(--surface-2);border:1px solid var(--border);">
                ${book.cover_url
                    ? `<img src="${book.cover_url}" style="width:100%;height:100%;object-fit:cover;">`
                    : `<div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;"><svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="color:var(--text-dim)"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></div>`
                }
            </div>
            <div style="flex:1;min-width:0;">
                <div style="font-size:0.855rem;font-weight:600;color:var(--text-head);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">${book.title}</div>
                <div style="font-size:0.75rem;color:var(--text-muted);">${book.author}</div>
            </div>`;
        item.addEventListener('mouseenter', () => item.style.background = 'var(--surface-2)');
        item.addEventListener('mouseleave', () => item.style.background = '');
        item.addEventListener('click',      () => selectExistingBook(book));
        dropdown.appendChild(item);
    });

    // "Add as new book" always at bottom
    const newItem = document.createElement('div');
    newItem.style.cssText = 'display:flex;align-items:center;gap:0.5rem;padding:0.65rem 0.875rem;cursor:pointer;font-size:0.855rem;font-weight:600;color:var(--color-text-info);';
    newItem.innerHTML = `<span style="font-size:1.1rem;">&#xFF0B;</span> Add "<strong>${q}</strong>" as a new book`;
    newItem.addEventListener('mouseenter', () => newItem.style.background = 'var(--surface-2)');
    newItem.addEventListener('mouseleave', () => newItem.style.background = '');
    newItem.addEventListener('click',      () => selectNewBook(q));
    dropdown.appendChild(newItem);

    dropdown.style.display = 'block';
}

function selectExistingBook(book) {
    selectedMode = 'existing';
    existingBookId.value = book.id;
    searchInput.value    = book.title + ' — ' + book.author;
    dropdown.style.display = 'none';

    const cover   = document.getElementById('preview-cover');
    const noCover = document.getElementById('preview-no-cover');
    if (book.cover_url) {
        cover.src = book.cover_url;
        cover.style.display   = 'block';
        noCover.style.display = 'none';
    } else {
        cover.style.display   = 'none';
        noCover.style.display = 'flex';
    }
    document.getElementById('preview-title').textContent  = book.title;
    document.getElementById('preview-author').textContent = 'by ' + book.author;
    existingPreview.style.display  = 'flex';
    newBookIndicator.style.display = 'none';
    newBookFields.style.display    = 'none';
    setNewBookRequired(false);
    submitLabel.textContent = 'Add Copy';
}

function selectNewBook(q) {
    selectedMode = 'new';
    existingBookId.value   = '';
    searchInput.value      = q;
    dropdown.style.display = 'none';

    existingPreview.style.display  = 'none';
    newBookIndicator.style.display = 'block';
    newBookFields.style.display    = 'grid';
    digitalFields.style.display    = typeSelect.value === 'digital' ? 'block' : 'none';

    if (titleInput) titleInput.value = q;
    setNewBookRequired(true);
    submitLabel.textContent = 'Add Book';
}

document.getElementById('clear-selection').addEventListener('click', resetSelection);

function resetSelection() {
    selectedMode         = null;
    existingBookId.value = '';
    searchInput.value    = '';
    dropdown.style.display         = 'none';
    existingPreview.style.display  = 'none';
    newBookIndicator.style.display = 'none';
    newBookFields.style.display    = 'none';
    setNewBookRequired(false);
    submitLabel.textContent = 'Add Book';
}

function setNewBookRequired(required) {
    ['title', 'author', 'price'].forEach(name => {
        const el = document.querySelector(`[name="${name}"]`);
        if (el) el.required = required;
    });
}

if (coverInput) {
    coverInput.addEventListener('change', function () {
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                coverPreview.innerHTML = `<img src="${e.target.result}" style="width:100%;height:100%;object-fit:cover;">`;
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
}

// Restore state on validation error
@if(old('type'))
    typeSelect.value = '{{ old('type') }}';
    selectionCard.style.display = 'block';
    @if(old('existing_book_id'))
        existingBookId.value   = '{{ old('existing_book_id') }}';
        selectedMode           = 'existing';
        submitLabel.textContent = 'Add Copy';
        searchInput.value      = '{{ old('title', '') }}';
    @elseif(old('title'))
        selectNewBook('{{ old('title') }}');
    @endif
@endif
</script>

@endsection