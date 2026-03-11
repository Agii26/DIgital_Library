@extends('layouts.app')

@section('page-title', 'Manage Borrow')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Manage Borrow</h1>
        <p class="page-subtitle">Review details and take action on this borrowing record</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Borrows
        </a>
    </div>
</div>

<div style="max-width:760px;display:flex;flex-direction:column;gap:1.5rem;">

    {{-- Borrow Info Card --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Borrow Details</h3>
            <span class="badge
                {{ $borrow->status === 'reserved'  ? 'badge-blue' :
                   ($borrow->status === 'approved'  ? 'badge-warning' :
                   ($borrow->status === 'claimed'   ? 'badge-gold' :
                   ($borrow->status === 'returned'  ? 'badge-success' :
                   'badge-muted'))) }}" style="font-size:0.75rem;padding:0.3rem 0.75rem;">
                {{ ucfirst($borrow->status) }}
            </span>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

                {{-- Borrower --}}
                <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;">
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-dim);margin-bottom:0.5rem;">Borrower</p>
                    <div style="display:flex;align-items:center;gap:0.625rem;">
                        <div class="avatar avatar-sm">{{ strtoupper(substr($borrow->user->name ?? 'D', 0, 1)) }}</div>
                        <div>
                            <p style="font-size:0.875rem;font-weight:600;color:var(--text-head);">
                                {{ $borrow->user->name ?? 'Deleted User' }}
                                @if($borrow->user && $borrow->user->trashed())
                                    <span class="badge badge-danger" style="font-size:0.6rem;margin-left:0.3rem;">Deleted</span>
                                @endif
                            </p>
                            @if($borrow->user)
                            <p style="font-size:0.72rem;color:var(--text-muted);margin-top:0.1rem;">
                                {{ ucfirst($borrow->user->role) }}
                                @if($borrow->user->student_id)
                                    &middot; {{ $borrow->user->student_id }}
                                @endif
                            </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Book --}}
                <div style="background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius);padding:1rem;">
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-dim);margin-bottom:0.5rem;">Book</p>
                    <p style="font-size:0.875rem;font-weight:600;color:var(--text-head);margin-bottom:0.2rem;">{{ $borrow->book->title }}</p>
                    <code style="font-size:0.72rem;color:var(--text-muted);background:var(--border);padding:0.15rem 0.4rem;border-radius:3px;">{{ $borrow->book->accession_no }}</code>
                </div>

                {{-- Reserved At --}}
                <div>
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-dim);margin-bottom:0.25rem;">Reserved At</p>
                    <p style="font-size:0.875rem;font-weight:500;color:var(--text-head);">
                        {{ $borrow->reserved_at?->format('M d, Y h:i A') ?? '—' }}
                    </p>
                </div>

                {{-- Due Date --}}
                @if($borrow->due_date)
                <div>
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-dim);margin-bottom:0.25rem;">Due Date</p>
                    <p style="font-size:0.875rem;font-weight:600;color:{{ now()->isAfter($borrow->due_date) ? 'var(--danger)' : 'var(--text-head)' }};">
                        {{ $borrow->due_date->format('M d, Y') }}
                        @if(now()->isAfter($borrow->due_date))
                            <span class="badge badge-danger" style="margin-left:0.375rem;font-size:0.62rem;">
                                {{ now()->diffInDays($borrow->due_date) }}d overdue
                            </span>
                        @endif
                    </p>
                </div>
                @endif

                {{-- Condition --}}
                @if($borrow->condition)
                <div>
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-dim);margin-bottom:0.25rem;">Return Condition</p>
                    <span class="badge {{ $borrow->condition === 'good' ? 'badge-success' : ($borrow->condition === 'damaged' ? 'badge-warning' : 'badge-danger') }}">
                        {{ ucfirst($borrow->condition) }}
                    </span>
                </div>
                @endif

            </div>
        </div>
    </div>

    {{-- Actions Card --}}
    @if(in_array($borrow->status, ['reserved', 'approved', 'claimed']))
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Actions</h3>
        </div>
        <div class="card-body">

            @if($borrow->status === 'reserved')
            <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:1rem;">
                This reservation is pending approval. Approve to notify the borrower their book is ready for pickup, or cancel if unavailable.
            </p>
            <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">
                <form method="POST" action="{{ route('admin.borrows.approve', $borrow) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Approve Reservation
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.borrows.cancel', $borrow) }}">
                    @csrf
                    <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Cancel this reservation?')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        Cancel Reservation
                    </button>
                </form>
            </div>
            @endif

            @if($borrow->status === 'approved')
            <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:1rem;">
                Scan the borrower's RFID card to confirm book pickup.
            </p>
            <form method="POST" action="{{ route('admin.borrows.claim', $borrow) }}">
                @csrf
                <div style="display:flex;gap:0.75rem;align-items:flex-end;flex-wrap:wrap;">
                    <div class="form-group" style="flex:1;min-width:200px;margin-bottom:0;">
                        <label class="form-label" for="rfid_tag">
                            RFID Tag
                            <span style="font-size:0.7rem;color:var(--text-dim);font-weight:400;"> — scan or type</span>
                        </label>
                        <input type="text" id="rfid_tag" name="rfid_tag"
                            class="form-control" placeholder="Scan RFID tag..." autofocus required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        Mark as Claimed
                    </button>
                </div>
            </form>
            @endif

            @if($borrow->status === 'claimed')
            <p style="font-size:0.82rem;color:var(--text-muted);margin-bottom:1rem;">
                Select the condition of the returned book. Selecting "Damaged" or "Lost" will generate a penalty automatically.
            </p>
            <form method="POST" action="{{ route('admin.borrows.return', $borrow) }}">
                @csrf
                <div style="display:flex;gap:0.75rem;align-items:flex-end;flex-wrap:wrap;">
                    <div class="form-group" style="flex:1;min-width:180px;margin-bottom:0;">
                        <label class="form-label" for="condition">Book Condition</label>
                        <select id="condition" name="condition" class="form-control" required>
                            <option value="">Select condition...</option>
                            <option value="good">Good</option>
                            <option value="damaged">Damaged</option>
                            <option value="lost">Lost</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-gold" style="flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/></svg>
                        Process Return
                    </button>
                </div>
            </form>
            @endif

        </div>
    </div>
    @endif

    {{-- Penalties Card --}}
    @if($borrow->penalties && $borrow->penalties->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Penalties</h3>
            <span style="font-size:0.8rem;color:var(--text-muted);">
                Total: <strong style="color:var(--danger);">&#8369;{{ number_format($borrow->penalties->sum('amount'), 2) }}</strong>
            </span>
        </div>
        @foreach($borrow->penalties as $penalty)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:0.875rem 1.5rem;border-bottom:1px solid var(--border);">
            <div>
                <p style="font-size:0.855rem;font-weight:500;color:var(--text-head);">{{ ucfirst($penalty->type) }} Fine</p>
                <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.1rem;">{{ $penalty->created_at->format('M d, Y') }}</p>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <p style="font-size:0.9rem;font-weight:600;color:var(--danger);">&#8369;{{ number_format($penalty->amount, 2) }}</p>
                <span class="badge {{ $penalty->is_paid ? 'badge-success' : 'badge-danger' }}">
                    {{ $penalty->is_paid ? 'Paid' : 'Unpaid' }}
                </span>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

@endsection