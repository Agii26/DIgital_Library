@extends('layouts.app')

@section('page-title', 'Book Management')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Book Management</h1>
        <p class="page-subtitle">Manage the library's physical and digital collection</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Book
        </a>
    </div>
</div>

{{-- Import Books --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header">
        <h3 class="card-title">Import Books</h3>
        <a href="{{ route('admin.books.template') }}" class="btn btn-sm btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
            Download Template
        </a>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.books.import') }}" enctype="multipart/form-data">
            @csrf
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;margin-bottom:1.25rem;">

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">
                        CSV / Excel File <span class="required">*</span>
                    </label>
                    <input type="file" name="file" accept=".zip,.xlsx,.csv,.xls" ...>
                    <p class="form-hint">
                        For covers and PDFs, upload a <code>.zip</code> containing your 
                        <code>import.csv</code>, a <code>covers/</code> folder, and a <code>pdfs/</code> folder.
                        Name files after their <code>accession_no</code>.
                    </p>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">
                        PDF Files
                        <span style="font-size:0.7rem;color:var(--text-dim);font-weight:400;"> — for digital books only</span>
                    </label>
                    <input type="file" name="pdfs[]" accept=".pdf"
                        class="form-control" style="font-size:0.8rem;padding:0.45rem 0.6rem;" multiple>
                    <p class="form-hint">
                        Optional. Name each PDF after its <code style="font-size:0.72rem;background:var(--surface-2);padding:0.1rem 0.3rem;border-radius:3px;border:1px solid var(--border);">accession_no</code>
                        in the spreadsheet — e.g. <code style="font-size:0.72rem;background:var(--surface-2);padding:0.1rem 0.3rem;border-radius:3px;border:1px solid var(--border);">ACC-00002.pdf</code>.
                    </p>
                </div>

            </div>

            <div style="display:flex;align-items:center;gap:0.75rem;padding-top:1rem;border-top:1px solid var(--border);">
                <button type="submit" class="btn btn-success">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Import Books
                </button>
                <p style="font-size:0.775rem;color:var(--text-dim);margin:0;">
                    Physical books don't need a PDF. Digital books without a PDF can have one added later via Edit.
                </p>
            </div>

        </form>
    </div>
</div>

{{-- Search & Filters --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1.125rem 1.5rem;">
        <form method="GET" action="{{ route('admin.books.index') }}" style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:220px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text-dim);pointer-events:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search title, author, accession no..."
                    class="form-control" style="padding-left:2.25rem;">
            </div>
            <select name="type" class="form-control" style="width:auto;min-width:130px;">
                <option value="">All Types</option>
                <option value="physical" {{ request('type') === 'physical' ? 'selected' : '' }}>Physical</option>
                <option value="digital" {{ request('type') === 'digital' ? 'selected' : '' }}>Digital</option>
            </select>
            <select name="status" class="form-control" style="width:auto;min-width:140px;">
                <option value="">All Status</option>
                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>Available</option>
                <option value="borrowed" {{ request('status') === 'borrowed' ? 'selected' : '' }}>Borrowed</option>
                <option value="reserved" {{ request('status') === 'reserved' ? 'selected' : '' }}>Reserved</option>
                <option value="damaged" {{ request('status') === 'damaged' ? 'selected' : '' }}>Damaged</option>
                <option value="lost" {{ request('status') === 'lost' ? 'selected' : '' }}>Lost</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

{{-- Books Table --}}
<div class="card">
    <div class="table-wrapper" style="border:none;border-radius:var(--radius-lg);box-shadow:none;">
        <table>
            <thead>
                <tr>
                    <th>Cover</th>
                    <th>Accession No.</th>
                    <th>Title &amp; Author</th>
                    <th>Category</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($books as $book)
                <tr>
                    <td>
                        @if($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}"
                                style="width:36px;height:48px;object-fit:cover;border-radius:var(--radius);border:1px solid var(--border);">
                        @else
                            <div style="width:36px;height:48px;background:var(--blue-ultra-pale);border-radius:var(--radius);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:14px;height:14px;color:var(--text-dim);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                        @endif
                    </td>
                    <td>
                        <code style="font-size:0.75rem;color:var(--text-muted);background:var(--surface-2);padding:0.2rem 0.5rem;border-radius:var(--radius);border:1px solid var(--border);">{{ $book->accession_no }}</code>
                    </td>
                    <td>
                        <p style="font-weight:500;color:var(--text-head);font-size:0.875rem;">{{ $book->title }}</p>
                        <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.15rem;">{{ $book->author }}</p>
                    </td>
                    <td style="color:var(--text-muted);font-size:0.845rem;">{{ $book->category ?? '—' }}</td>
                    <td>
                        <span class="badge {{ $book->type === 'physical' ? 'badge-gold' : 'badge-blue' }}">
                            {{ ucfirst($book->type) }}
                        </span>
                    </td>
                    <td>
                        <span class="badge
                            {{ $book->status === 'available' ? 'badge-success' :
                               ($book->status === 'borrowed' ? 'badge-warning' :
                               ($book->status === 'reserved' ? 'badge-blue' :
                               ($book->status === 'damaged' ? 'badge-danger' : 'badge-muted'))) }}">
                            {{ ucfirst($book->status) }}
                        </span>
                    </td>
                    <td style="font-size:0.855rem;font-weight:500;color:var(--text-head);">
                        &#8369;{{ number_format($book->price, 2) }}
                    </td>
                    <td>
                        <div style="display:flex;gap:0.375rem;align-items:center;">
                            <a href="{{ route('admin.books.show', $book) }}" class="btn btn-sm btn-secondary">View</a>
                            <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-sm btn-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.books.destroy', $book) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Delete \'{{ addslashes($book->title) }}\'?')"
                                    class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                            </div>
                            <p class="empty-state-title">No books found</p>
                            <p class="empty-state-text">Try adjusting your search or filters.</p>
                            <a href="{{ route('admin.books.create') }}" class="btn btn-primary">Add First Book</a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($books->hasPages())
    <div class="card-footer" style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:0.8rem;color:var(--text-muted);">
            Showing {{ $books->firstItem() }}–{{ $books->lastItem() }} of {{ $books->total() }} books
        </p>
        <div class="pagination">
            {{ $books->links() }}
        </div>
    </div>
    @endif
</div>

@endsection