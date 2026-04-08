@extends('layouts.app')

@section('page-title', 'Book Details')

@section('content')

<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Book Details</h1>
        <p class="page-subtitle">Viewing record for &ldquo;{{ $book->title }}&rdquo;</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.books.edit', $book) }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="vertical-align:middle;margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/></svg>
            Edit Book
        </a>
        <a href="{{ route('admin.books.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="vertical-align:middle;margin-right:6px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 15 3 9m0 0 6-6M3 9h12a6 6 0 0 1 0 12h-3"/></svg>
            Back to Books
        </a>
    </div>
</div>

<!-- Book Card -->
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-body">
        <div style="display:flex;gap:1.75rem;align-items:flex-start;">

            <!-- Cover -->
            <div style="flex-shrink:0;">
                @if($book->cover_image)
                    <img src="{{ asset('storage/' . $book->cover_image) }}"
                        style="width:128px;height:176px;object-fit:cover;border-radius:var(--radius);box-shadow:var(--shadow-md);display:block;"
                        alt="{{ $book->title }} cover" />
                @else
                    <div style="width:128px;height:176px;background:var(--surface-2);border-radius:var(--radius);border:1px solid var(--border);display:flex;align-items:center;justify-content:center;">
                        <span style="font-size:0.7rem;color:var(--text-dim);text-align:center;line-height:1.5;">No<br>Cover</span>
                    </div>
                @endif
            </div>

            <!-- Info -->
            <div style="flex:1;min-width:0;">
                <div style="margin-bottom:1.25rem;">
                    <h2 style="font-family:var(--font-serif);font-size:1.5rem;font-weight:700;color:var(--text-head);margin:0 0 0.25rem;">{{ $book->title }}</h2>
                    <p style="color:var(--text-muted);font-size:0.875rem;margin:0;">by {{ $book->author }}</p>
                </div>

                <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;">

                    <div>
                        <p style="font-size:0.7rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.08em;margin:0 0 0.35rem;">Category</p>
                        <p style="font-size:0.875rem;font-weight:600;color:var(--text-head);margin:0;">{{ $book->category ?? '&mdash;' }}</p>
                    </div>

                    <div>
                        <p style="font-size:0.7rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.08em;margin:0 0 0.35rem;">Price</p>
                        <p style="font-size:0.875rem;font-weight:600;color:var(--text-head);margin:0;">&#8369;{{ number_format($book->price, 2) }}</p>
                    </div>

                    <div>
                        <p style="font-size:0.7rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.08em;margin:0 0 0.35rem;">Type</p>
                        <span class="badge {{ $book->type === 'physical' ? 'badge-gold' : 'badge-blue' }}">{{ ucfirst($book->type) }}</span>
                    </div>

                    <div>
                        <p style="font-size:0.7rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.08em;margin:0 0 0.35rem;">Total Copies</p>
                        <p style="font-size:0.875rem;font-weight:600;color:var(--text-head);margin:0;">{{ $book->quantity }}</p>
                    </div>

                    <div>
                        <p style="font-size:0.7rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.08em;margin:0 0 0.35rem;">Available</p>
                        <p style="font-size:0.875rem;font-weight:600;color:var(--text-head);margin:0;">{{ $book->available_copies }}</p>
                    </div>

                    <div>
                        <p style="font-size:0.7rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.08em;margin:0 0 0.35rem;">Status</p>
                        @php $status = $book->status; @endphp
                        <span class="badge {{ $status === 'available' ? 'badge-success' : 'badge-warning' }}">
                            {{ ucfirst($status) }}
                        </span>
                    </div>

                </div>

                @if($book->description)
                <div style="margin-top:1.25rem;padding-top:1.25rem;border-top:1px solid var(--border);">
                    <p style="font-size:0.7rem;font-weight:700;color:var(--text-dim);text-transform:uppercase;letter-spacing:0.08em;margin:0 0 0.5rem;">Description</p>
                    <p style="font-size:0.875rem;color:var(--text-muted);line-height:1.65;margin:0;">{{ $book->description }}</p>
                </div>
                @endif
            </div>

        </div>
    </div>
</div>

<!-- Copies -->
@if($book->type === 'physical')
<div class="card" style="margin-bottom:1.25rem;">
    <div class="card-header">
        <span class="card-title">Copies</span>
        <span class="badge badge-muted">{{ $book->copies->count() }} total</span>
    </div>
    <div class="table-wrapper" style="border:none;box-shadow:none;">
        <table>
            <thead>
                <tr>
                    <th>Accession No.</th>
                    <th>Status</th>
                    <th>Added</th>
                </tr>
            </thead>
            <tbody>
                @forelse($book->copies as $copy)
                <tr>
                    <td>
                        <code style="font-size:0.75rem;color:var(--text-muted);background:var(--surface-2);padding:0.2rem 0.5rem;border-radius:var(--radius);border:1px solid var(--border);">
                            {{ $copy->accession_no }}
                        </code>
                    </td>
                    <td>
                        <span class="badge
                            {{ $copy->status === 'available' ? 'badge-success' :
                               ($copy->status === 'borrowed'  ? 'badge-warning' :
                               ($copy->status === 'reserved'  ? 'badge-blue' :
                               ($copy->status === 'damaged'   ? 'badge-danger' : 'badge-muted'))) }}">
                            {{ ucfirst($copy->status) }}
                        </span>
                    </td>
                    <td style="font-size:0.8rem;color:var(--text-muted);">
                        {{ $copy->created_at->format('M d, Y') }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="3" style="text-align:center;color:var(--text-dim);padding:1.5rem;font-size:0.85rem;">No copies found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Borrowing History — aggregated across all copies -->
<div class="card">
    <div class="card-header">
        <span class="card-title">Borrowing History</span>
        <span class="badge badge-muted">{{ $book->physicalBorrows()->count() }} record(s)</span>
    </div>

    @php $borrows = $book->physicalBorrows()->with('user')->latest()->get(); @endphp

    @if($borrows->isEmpty())
        <div class="card-body">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <p class="empty-state-title">No borrowing history yet</p>
                <p class="empty-state-text">This book has not been borrowed by anyone.</p>
            </div>
        </div>
    @else
        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>Borrower</th>
                        <th>Reserved</th>
                        <th>Returned</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($borrows as $borrow)
                    <tr>
                        <td>
                            <div style="display:flex;align-items:center;gap:0.625rem;">
                                <div class="avatar avatar-sm">{{ strtoupper(substr($borrow->user->name ?? 'D', 0, 1)) }}</div>
                                <div>
                                    <div style="font-weight:600;font-size:0.875rem;color:var(--text-head);">
                                        {{ $borrow->user->name ?? 'Deleted User' }}
                                    </div>
                                    @if($borrow->user && $borrow->user->trashed())
                                        <span class="badge badge-danger" style="margin-top:2px;">Deleted</span>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td style="font-size:0.875rem;color:var(--text-muted);">
                            {{ $borrow->reserved_at?->format('M d, Y') ?? '&mdash;' }}
                        </td>
                        <td style="font-size:0.875rem;color:var(--text-muted);">
                            {{ $borrow->returned_at?->format('M d, Y') ?? '&mdash;' }}
                        </td>
                        <td>
                            @if($borrow->status === 'returned')
                                <span class="badge badge-success">Returned</span>
                            @elseif($borrow->status === 'claimed')
                                <span class="badge badge-warning">Claimed</span>
                            @elseif($borrow->status === 'approved')
                                <span class="badge badge-gold">Approved</span>
                            @elseif($borrow->status === 'cancelled')
                                <span class="badge badge-muted">Cancelled</span>
                            @else
                                <span class="badge badge-blue">{{ ucfirst($borrow->status) }}</span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endif

@endsection