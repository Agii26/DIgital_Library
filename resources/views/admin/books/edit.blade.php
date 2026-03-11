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

<form method="POST" action="{{ route('admin.books.update', $book) }}" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div style="display:grid;grid-template-columns:1fr;gap:1.25rem;">

        <!-- Book Information -->
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
                            class="form-control"
                            placeholder="e.g. Introduction to Algorithms"
                            required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Author <span class="required">*</span></label>
                        <input type="text" name="author" value="{{ old('author', $book->author) }}"
                            class="form-control"
                            placeholder="e.g. Thomas H. Cormen"
                            required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="{{ old('category', $book->category) }}"
                            class="form-control"
                            placeholder="e.g. Science, Fiction" />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price (&#8369;) <span class="required">*</span></label>
                        <input type="number" name="price" value="{{ old('price', $book->price) }}"
                            step="0.01" min="0"
                            class="form-control"
                            placeholder="0.00"
                            required />
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
                        <textarea name="description" rows="3"
                            class="form-control"
                            placeholder="Brief description of the book..."
                            style="resize:vertical;">{{ old('description', $book->description) }}</textarea>
                    </div>

                </div>
            </div>
        </div>

        <!-- Cover Image -->
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
                            <input type="file" name="cover_image" accept="image/*" id="cover-input"
                                class="form-control" />
                            <span class="form-hint">Leave blank to keep the current image. Accepted formats: JPG, PNG, WEBP.</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="vertical-align:middle;margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg>
                Update Book
            </button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                Cancel
            </a>
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