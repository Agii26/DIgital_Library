@extends('layouts.app')

@section('page-title', 'Edit Book')

@section('content')

<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Edit Book</h1>
        <p class="page-subtitle">Update book details and cover image</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
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

<form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:1fr;gap:1.25rem;">

        {{-- Book Information --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Book Information</span>
                <span class="badge badge-muted">Accession #{{ $book->accession_no }}</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">

                    <div class="form-group">
                        <label class="form-label">Accession No.</label>
                        <input type="text" value="{{ $book->accession_no }}"
                            class="form-control"
                            style="background:var(--surface-2);color:var(--text-muted);cursor:not-allowed;"
                            disabled />
                        <span class="form-hint">Read-only — cannot be changed</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Type</label>
                        <input type="text" value="{{ ucfirst($book->type) }}"
                            class="form-control"
                            style="background:var(--surface-2);color:var(--text-muted);cursor:not-allowed;"
                            disabled />
                        <span class="form-hint">Read-only — cannot be changed</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Title <span class="required">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $book->title) }}"
                            class="form-control" placeholder="e.g. Introduction to Algorithms" required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Author <span class="required">*</span></label>
                        <input type="text" name="author" value="{{ old('author', $book->author) }}"
                            class="form-control" placeholder="e.g. Thomas H. Cormen" required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="{{ old('category', $book->category) }}"
                            class="form-control" placeholder="e.g. Science, Fiction" />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price (&#8369;) <span class="required">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $book->price) }}"
                            step="0.01" min="0" class="form-control" placeholder="0.00" required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Status <span class="required">*</span></label>
                        <select name="status" class="form-control" required>
                            <option value="available" {{ old('status', $book->status) === 'available' ? 'selected' : '' }}>Available</option>
                            <option value="borrowed"  {{ old('status', $book->status) === 'borrowed'  ? 'selected' : '' }}>Borrowed</option>
                            <option value="reserved"  {{ old('status', $book->status) === 'reserved'  ? 'selected' : '' }}>Reserved</option>
                            <option value="damaged"   {{ old('status', $book->status) === 'damaged'   ? 'selected' : '' }}>Damaged</option>
                            <option value="lost"      {{ old('status', $book->status) === 'lost'      ? 'selected' : '' }}>Lost</option>
                        </select>
                    </div>

                    <div class="form-group" style="grid-column:1 / -1;">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3" class="form-control"
                            placeholder="Brief description of the book..."
                            style="resize:vertical;">{{ old('description', $book->description) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        {{-- Cover Image --}}
        <div class="card">
            <div class="card-header">
                <span class="card-title">Cover Image</span>
            </div>
            <div class="card-body">
                <div style="display:flex;align-items:flex-start;gap:1.5rem;">
                    <div id="cover-preview" style="width:90px;height:126px;border-radius:var(--radius);overflow:hidden;border:1px solid var(--border);background:var(--surface-2);flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;" alt="Cover" />
                        @else
                            <span style="font-size:0.7rem;color:var(--text-dim);text-align:center;padding:0.25rem;line-height:1.4;">No<br>Image</span>
                        @endif
                    </div>
                    <div style="flex:1;">
                        <div class="form-group">
                            <label class="form-label">Replace Cover</label>
                            <input type="file" name="cover_image" accept="image/*" id="cover-input" class="form-control" />
                            <span class="form-hint">Leave blank to keep the current image. Accepted: JPG, PNG, WEBP.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- PDF File (digital books only) --}}
        @if($book->type === 'digital')
        <div class="card">
            <div class="card-header">
                <span class="card-title">PDF File</span>
                @if($book->digitalBook)
                    <span class="badge badge-success">Uploaded</span>
                @else
                    <span class="badge badge-danger">Missing</span>
                @endif
            </div>
            <div class="card-body">

                {{-- Current file info --}}
                @if($book->digitalBook)
                <div style="display:flex;align-items:center;gap:0.875rem;padding:0.875rem 1rem;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius);margin-bottom:1.25rem;">
                    <div style="width:36px;height:36px;background:#fef2f2;border-radius:var(--radius);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="#ef4444" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/></svg>
                    </div>
                    <div style="flex:1;min-width:0;">
                        <p style="font-size:0.8rem;font-weight:600;color:var(--text-head);margin-bottom:0.1rem;">Current PDF</p>
                        <p style="font-size:0.72rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                            {{ basename($book->digitalBook->file_path) }}
                        </p>
                    </div>
                    <a href="{{ asset('storage/' . $book->digitalBook->file_path) }}" target="_blank"
                        class="btn btn-sm btn-secondary" style="flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Preview
                    </a>
                </div>
                @else
                <div class="alert alert-warning" style="margin-bottom:1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
                    No PDF uploaded yet. This book is not readable until a PDF is provided.
                </div>
                @endif

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">
                        {{ $book->digitalBook ? 'Replace PDF' : 'Upload PDF' }}
                        @if(!$book->digitalBook)<span class="required">*</span>@endif
                    </label>
                    <input type="file" name="file_path" accept="application/pdf" class="form-control" />
                    <span class="form-hint">
                        {{ $book->digitalBook ? 'Leave blank to keep the current PDF. ' : 'Required — upload a PDF file. ' }}
                        Max file size: 50MB.
                    </span>
                </div>

            </div>
        </div>
        @endif

        {{-- Form Actions --}}
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Update Book
            </button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Cancel</a>
        </div>

    </div>
</form>

<script>
    document.getElementById('cover-input').addEventListener('change', function () {
        const preview = document.getElementById('cover-preview');
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                preview.innerHTML = '<img src="' + e.target.result + '" style="width:100%;height:100%;object-fit:cover;" alt="Cover preview" />';
            };
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>

@endsection