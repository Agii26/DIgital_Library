@extends('layouts.app')

@section('page-title', 'My Borrows')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">My Borrows</h1>
        <p class="page-subtitle">Track your reserved and borrowed books</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('student.borrows.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:6px;vertical-align:-2px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
            </svg>
            Reserve a Book
        </a>
    </div>
</div>

{{-- Borrows Table --}}
<div class="card">
    <div class="card-header">
        <span class="card-title">Borrowing Records</span>
    </div>

    <div class="table-wrapper">
        <table style="width:100%;">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Accession No.</th>
                    <th>Status</th>
                    <th>Reserved</th>
                    <th>Due Date</th>
                    <th>Condition</th>
                </tr>
            </thead>
            <tbody>
                @forelse($borrows as $borrow)
                <tr>
                    <td>
                        <span style="font-family:var(--font-serif);font-weight:600;color:var(--text-head);font-size:0.88rem;">
                            {{ $borrow->book->title }}
                        </span>
                    </td>
                    <td>
                        <span style="font-family:monospace;font-size:0.82rem;color:var(--text-muted);">
                            {{ $borrow->book->accession_no ?? '&mdash;' }}
                        </span>
                    </td>
                    <td>
                        @if($borrow->status === 'reserved')
                            <span class="badge badge-blue">Reserved</span>
                        @elseif($borrow->status === 'approved')
                            <span class="badge badge-gold">Approved</span>
                        @elseif($borrow->status === 'claimed')
                            <span class="badge badge-warning">Claimed</span>
                        @elseif($borrow->status === 'returned')
                            <span class="badge badge-success">Returned</span>
                        @else
                            <span class="badge badge-muted">{{ ucfirst($borrow->status) }}</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:0.85rem;">
                        {{ $borrow->reserved_at?->format('M d, Y') ?? '&mdash;' }}
                    </td>
                    <td style="font-size:0.85rem;">
                        @if($borrow->due_date)
                            @if(now()->isAfter($borrow->due_date))
                                <span style="color:var(--danger);font-weight:600;display:inline-flex;align-items:center;gap:4px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                                    </svg>
                                    {{ $borrow->due_date->format('M d, Y') }}
                                </span>
                            @else
                                <span style="color:var(--text-muted);">{{ $borrow->due_date->format('M d, Y') }}</span>
                            @endif
                        @else
                            <span style="color:var(--text-dim);">&mdash;</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);font-size:0.85rem;">
                        {{ $borrow->condition ? ucfirst($borrow->condition) : '&mdash;' }}
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:0;">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 00-1.883 2.542l.857 6a2.25 2.25 0 002.227 1.932H19.05a2.25 2.25 0 002.227-1.932l.857-6a2.25 2.25 0 00-1.883-2.542m-16.5 0V6A2.25 2.25 0 016 3.75h3.879a1.5 1.5 0 011.06.44l2.122 2.12a1.5 1.5 0 001.06.44H18A2.25 2.25 0 0120.25 9v.776"/>
                                </svg>
                            </div>
                            <div class="empty-state-title">No Borrowing Records Yet</div>
                            <div class="empty-state-text">You haven't reserved or borrowed any books. Browse the collection to get started.</div>
                            <a href="{{ route('student.books.index') }}" class="btn btn-primary btn-sm" style="margin-top:1rem;">
                                Browse Books
                            </a>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($borrows->hasPages())
    <div class="card-footer" style="display:flex;justify-content:flex-end;">
        {{ $borrows->links() }}
    </div>
    @endif
</div>

{{-- Mobile: stack table as cards --}}
<style>
    @media (max-width: 640px) {
        .table-wrapper table,
        .table-wrapper thead,
        .table-wrapper tbody,
        .table-wrapper th,
        .table-wrapper td,
        .table-wrapper tr {
            display: block;
        }
        .table-wrapper thead {
            display: none;
        }
        .table-wrapper tbody tr {
            border-bottom: 2px solid var(--border);
            padding: 0.85rem 1rem;
            position: relative;
        }
        .table-wrapper tbody tr:last-child {
            border-bottom: none;
        }
        .table-wrapper tbody td {
            padding: 0.2rem 0;
            border: none;
            display: flex;
            align-items: flex-start;
            gap: 0.4rem;
            font-size: 0.83rem;
        }
        .table-wrapper tbody td::before {
            content: attr(data-label);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-dim);
            min-width: 90px;
            flex-shrink: 0;
            padding-top: 1px;
        }
    }
</style>

{{-- Add data-label attributes for mobile via JS --}}
<script>
    (function () {
        const labels = ['Book', 'Accession No.', 'Status', 'Reserved', 'Due Date', 'Condition'];
        document.querySelectorAll('.table-wrapper tbody tr').forEach(function (row) {
            row.querySelectorAll('td').forEach(function (td, i) {
                if (labels[i]) td.setAttribute('data-label', labels[i]);
            });
        });
    })();
</script>

@endsection