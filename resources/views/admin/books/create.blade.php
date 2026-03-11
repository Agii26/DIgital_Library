@extends('layouts.app')

@section('page-title', 'Add New Book')

@section('content')

<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Add New Book</h1>
        <p class="page-subtitle">Register a new book into the library collection</p>
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

<form method="POST" action="{{ route('admin.books.store') }}" enctype="multipart/form-data">
    @csrf

    <div style="display:grid;grid-template-columns:1fr;gap:1.25rem;">

        <!-- Book Information -->
        <div class="card">
            <div class="card-header">
                <span class="card-title">Book Information</span>
            </div>
            <div class="card-body">
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">

                    <div class="form-group">
                        <label class="form-label">Accession No. <span class="required">*</span></label>
                        <input type="text" name="accession_no" value="{{ old('accession_no') }}"
                            class="form-control"
                            placeholder="e.g. ACC-001"
                            required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Type <span class="required">*</span></label>
                        <select name="type" id="book-type" class="form-control" required>
                            <option value="">Select type...</option>
                            <option value="physical" {{ old('type') === 'physical' ? 'selected' : '' }}>Physical</option>
                            <option value="digital"  {{ old('type') === 'digital'  ? 'selected' : '' }}>Digital</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Title <span class="required">*</span></label>
                        <input type="text" name="title" value="{{ old('title') }}"
                            class="form-control"
                            placeholder="Book title"
                            required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Author <span class="required">*</span></label>
                        <input type="text" name="author" value="{{ old('author') }}"
                            class="form-control"
                            placeholder="Author name"
                            required />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Category</label>
                        <input type="text" name="category" value="{{ old('category') }}"
                            class="form-control"
                            placeholder="e.g. Science, Fiction" />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Price (&#8369;) <span class="required">*</span></label>
                        <input type="number" name="price" value="{{ old('price', 0) }}"
                            step="0.01" min="0"
                            class="form-control"
                            placeholder="0.00"
                            required />
                    </div>

                    <div class="form-group" style="grid-column:1 / -1;">
                        <label class="form-label">Description</label>
                        <textarea name="description" rows="3"
                            class="form-control"
                            placeholder="Brief description of the book..."
                            style="resize:vertical;">{{ old('description') }}</textarea>
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
                        <span style="font-size:0.7rem;color:var(--text-dim);text-align:center;line-height:1.5;">No<br>Image</span>
                    </div>

                    <div style="flex:1;">
                        <div class="form-group">
                            <label class="form-label">Upload Cover</label>
                            <input type="file" name="cover_image" accept="image/*" id="cover-input"
                                class="form-control" />
                            <span class="form-hint">JPG, PNG or WEBP. Max 2MB.</span>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Digital Book Fields (hidden by default) -->
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

        <!-- Form Actions -->
        <div style="display:flex;align-items:center;gap:0.75rem;">
            <button type="submit" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="vertical-align:middle;margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Add Book
            </button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </div>

    </div>
</form>

<script>
    // Show/hide digital fields
    document.getElementById('book-type').addEventListener('change', function () {
        document.getElementById('digital-fields').style.display = this.value === 'digital' ? 'block' : 'none';
    });

    // Restore digital fields visibility on validation error
    if ('{{ old('type') }}' === 'digital') {
        document.getElementById('digital-fields').style.display = 'block';
    }

    // Cover image preview
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