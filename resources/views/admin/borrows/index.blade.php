@extends('layouts.app')

@section('page-title', 'Borrowing Management')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Borrowing Management</h1>
        <p class="page-subtitle">Track and manage all book reservations and loans</p>
    </div>
</div>

{{-- Search & Filters --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1.125rem 1.5rem;">
        <form method="GET" action="{{ route('admin.borrows.index') }}" style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <div style="position:relative;flex:1;min-width:220px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text-dim);pointer-events:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search user or book..."
                    class="form-control" style="padding-left:2.25rem;">
            </div>
            <select name="status" class="form-control" style="width:auto;min-width:150px;">
                <option value="">All Status</option>
                <option value="reserved"  {{ request('status') === 'reserved'  ? 'selected' : '' }}>Reserved</option>
                <option value="approved"  {{ request('status') === 'approved'  ? 'selected' : '' }}>Approved</option>
                <option value="claimed"   {{ request('status') === 'claimed'   ? 'selected' : '' }}>Claimed</option>
                <option value="returned"  {{ request('status') === 'returned'  ? 'selected' : '' }}>Returned</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

{{-- Borrows Table --}}
<div class="card">
    <div class="table-wrapper" style="border:none;border-radius:var(--radius-lg);box-shadow:none;">
        <table>
            <thead>
                <tr>
                    <th>Borrower</th>
                    <th>Book</th>
                    <th>Accession No.</th>
                    <th>Status</th>
                    <th>Reserved</th>
                    <th>Due Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $borrow)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.625rem;">
                            <div class="avatar avatar-sm">{{ strtoupper(substr($borrow->user->name ?? 'D', 0, 1)) }}</div>
                            <div>
                                <p style="font-size:0.845rem;font-weight:500;color:var(--text-head);white-space:nowrap;">
                                    {{ $borrow->user->name ?? 'Deleted User' }}
                                    @if($borrow->user && $borrow->user->trashed())
                                        <span class="badge badge-danger" style="margin-left:0.35rem;font-size:0.6rem;">Deleted</span>
                                    @endif
                                </p>
                                <p style="font-size:0.72rem;color:var(--text-muted);margin-top:0.1rem;">
                                    {{ $borrow->user ? ucfirst($borrow->user->role) : '—' }}
                                </p>
                            </div>
                        </div>
                    </td>
                    <td style="font-size:0.845rem;font-weight:500;color:var(--text-head);max-width:200px;">
                        <span style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;display:block;">
                            {{ $borrow->book->title }}
                        </span>
                    </td>
                    <td>
                        <code style="font-size:0.75rem;color:var(--text-muted);background:var(--surface-2);padding:0.2rem 0.5rem;border-radius:var(--radius);border:1px solid var(--border);">
                            {{ $borrow->book->accession_no }}
                        </code>
                    </td>
                    <td>
                        <span class="badge
                            {{ $borrow->status === 'reserved'  ? 'badge-blue' :
                               ($borrow->status === 'approved'  ? 'badge-warning' :
                               ($borrow->status === 'claimed'   ? 'badge-gold' :
                               ($borrow->status === 'returned'  ? 'badge-success' :
                               'badge-muted'))) }}">
                            {{ ucfirst($borrow->status) }}
                        </span>
                    </td>
                    <td style="font-size:0.835rem;color:var(--text-muted);white-space:nowrap;">
                        {{ $borrow->reserved_at?->format('M d, Y') ?? '—' }}
                    </td>
                    <td style="white-space:nowrap;">
                        @if($borrow->due_date)
                            <span style="font-size:0.835rem;font-weight:{{ now()->isAfter($borrow->due_date) ? '600' : '400' }};color:{{ now()->isAfter($borrow->due_date) ? 'var(--danger)' : 'var(--text-muted)' }};">
                                {{ $borrow->due_date->format('M d, Y') }}
                            </span>
                            @if(now()->isAfter($borrow->due_date) && $borrow->status === 'claimed')
                                <span class="badge badge-danger" style="margin-left:0.35rem;font-size:0.6rem;">Overdue</span>
                            @endif
                        @else
                            <span style="color:var(--text-dim);">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.borrows.show', $borrow) }}" class="btn btn-sm btn-primary">
                            Manage
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </div>
                            <p class="empty-state-title">No borrowing records found</p>
                            <p class="empty-state-text">Try adjusting your search or filters.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($borrows->hasPages())
    <div class="card-footer" style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:0.8rem;color:var(--text-muted);">
            Showing {{ $borrows->firstItem() }}–{{ $borrows->lastItem() }} of {{ $borrows->total() }} records
        </p>
        <div class="pagination">
            {{ $borrows->links() }}
        </div>
    </div>
    @endif
</div>

@endsection