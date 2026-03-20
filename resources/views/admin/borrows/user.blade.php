@extends('layouts.app')

@section('page-title', 'Borrows — ' . $user->name)

@section('content')

<div class="page-title-wrap">
    <div>
        <h1 class="page-title">{{ $user->name }}'s Borrows</h1>
        <p class="page-subtitle">All borrowing records for this user</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.borrows.index', ['tab' => 'all']) }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back
        </a>
    </div>
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if($errors->any())
<div class="alert alert-danger">
    @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
</div>
@endif

{{-- User Info Strip --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1.125rem 1.5rem;display:flex;align-items:center;gap:1.25rem;flex-wrap:wrap;">
        <div class="avatar avatar-md">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
        <div style="flex:1;">
            <p style="font-size:0.95rem;font-weight:600;color:var(--text-head);">{{ $user->name }}</p>
            <p style="font-size:0.78rem;color:var(--text-muted);">{{ $user->email }}</p>
        </div>
        <div style="display:flex;gap:1.5rem;flex-wrap:wrap;">
            <div style="text-align:center;">
                <p style="font-size:1.25rem;font-weight:700;color:var(--blue);font-family:var(--font-serif);">{{ $borrows->count() }}</p>
                <p style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;">Total</p>
            </div>
            <div style="text-align:center;">
                <p style="font-size:1.25rem;font-weight:700;color:var(--gold);font-family:var(--font-serif);">{{ $borrows->whereIn('status', ['approved','claimed'])->count() }}</p>
                <p style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;">Active</p>
            </div>
            <div style="text-align:center;">
                <p style="font-size:1.25rem;font-weight:700;color:var(--danger);font-family:var(--font-serif);">{{ $borrows->filter(fn($b) => $b->status === 'claimed' && $b->due_date && now()->isAfter($b->due_date))->count() }}</p>
                <p style="font-size:0.7rem;color:var(--text-muted);text-transform:uppercase;letter-spacing:0.08em;">Overdue</p>
            </div>
            <div>
                <span class="badge {{ $user->role === 'faculty' ? 'badge-blue' : 'badge-muted' }}" style="margin-top:0.25rem;">
                    {{ ucfirst($user->role) }}
                </span>
            </div>
        </div>
    </div>
</div>

{{-- Borrows List --}}
@if($borrows->isEmpty())
<div class="card">
    <div class="empty-state">
        <div class="empty-state-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <p class="empty-state-title">No borrow records</p>
        <p class="empty-state-text">This user has no borrowing history.</p>
    </div>
</div>
@else
<div style="display:flex;flex-direction:column;gap:1rem;">
    @foreach($borrows as $borrow)
    @php
        $isOverdue = $borrow->status === 'claimed' && $borrow->due_date && now()->isAfter($borrow->due_date);
    @endphp
    <div class="card">
        <div class="card-header">
            {{-- Book info --}}
            <div style="display:flex;align-items:center;gap:0.875rem;flex:1;">
                @if($borrow->book->cover_image)
                <img src="{{ asset('storage/'.$borrow->book->cover_image) }}" alt="{{ $borrow->book->title }}"
                    style="width:38px;height:52px;object-fit:cover;border-radius:3px;flex-shrink:0;box-shadow:var(--shadow-sm);">
                @else
                <div style="width:38px;height:52px;background:linear-gradient(135deg,var(--navy-mid),var(--navy));border-radius:3px;flex-shrink:0;display:flex;align-items:center;justify-content:center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="rgba(255,255,255,0.4)" stroke-width="1.5"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"/><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"/></svg>
                </div>
                @endif
                <div>
                    <p style="font-size:0.9rem;font-weight:600;color:var(--text-head);">{{ $borrow->book->title }}</p>
                    <p style="font-size:0.775rem;color:var(--text-muted);">{{ $borrow->book->author }}
                        &nbsp;&middot;&nbsp;
                        <code style="font-size:0.72rem;background:var(--surface-2);padding:0.1rem 0.35rem;border-radius:3px;border:1px solid var(--border);">{{ $borrow->book->accession_no }}</code>
                    </p>
                </div>
            </div>
            {{-- Status --}}
            <div style="display:flex;align-items:center;gap:0.5rem;">
                <span class="badge {{ $borrow->status === 'reserved' ? 'badge-blue' : ($borrow->status === 'approved' ? 'badge-warning' : ($borrow->status === 'claimed' ? 'badge-gold' : ($borrow->status === 'returned' ? 'badge-success' : 'badge-muted'))) }}">
                    {{ ucfirst($borrow->status) }}
                </span>
                @if($isOverdue)
                    <span class="badge badge-danger" style="font-size:0.6rem;">Overdue</span>
                @endif
            </div>
        </div>

        <div class="card-body" style="padding:1rem 1.5rem;">
            <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1rem;margin-bottom:{{ in_array($borrow->status, ['reserved','approved','claimed']) ? '1.25rem' : '0' }};">
                <div>
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-dim);margin-bottom:0.25rem;">Reserved</p>
                    <p style="font-size:0.845rem;color:var(--text-head);">{{ $borrow->reserved_at?->format('M d, Y') ?? '—' }}</p>
                </div>
                <div>
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-dim);margin-bottom:0.25rem;">Due Date</p>
                    <p style="font-size:0.845rem;font-weight:{{ $isOverdue ? '600' : '400' }};color:{{ $isOverdue ? 'var(--danger)' : 'var(--text-head)' }};">
                        {{ $borrow->due_date?->format('M d, Y') ?? '—' }}
                        @if($isOverdue)
                            <span style="font-size:0.72rem;">({{ now()->diffInDays($borrow->due_date) }}d overdue)</span>
                        @endif
                    </p>
                </div>
                <div>
                    <p style="font-size:0.7rem;text-transform:uppercase;letter-spacing:0.08em;color:var(--text-dim);margin-bottom:0.25rem;">Returned</p>
                    <p style="font-size:0.845rem;color:var(--text-head);">{{ $borrow->returned_at?->format('M d, Y') ?? '—' }}</p>
                </div>
            </div>

            {{-- Penalties --}}
            @if($borrow->penalties->count())
            <div style="background:var(--danger-pale);border:1px solid #f5c6c6;border-radius:var(--radius);padding:0.625rem 0.875rem;margin-bottom:{{ in_array($borrow->status, ['reserved','approved','claimed']) ? '1.25rem' : '0' }};display:flex;align-items:center;justify-content:space-between;">
                <span style="font-size:0.82rem;color:var(--danger);">
                    {{ $borrow->penalties->count() }} penalty/penalties
                    &nbsp;&middot;&nbsp;
                    {{ $borrow->penalties->where('is_paid', false)->count() }} unpaid
                </span>
                <span style="font-size:0.9rem;font-weight:700;color:var(--danger);">
                    &#8369;{{ number_format($borrow->penalties->sum('amount'), 2) }}
                </span>
            </div>
            @endif

            {{-- Actions --}}
            @if(in_array($borrow->status, ['reserved', 'approved', 'claimed']))
            <div style="padding-top:{{ $borrow->penalties->count() ? '0' : '0' }};border-top:1px solid var(--border);padding-top:1rem;">

                {{-- Reserved --}}
                @if($borrow->status === 'reserved')
                <div style="display:flex;gap:0.625rem;align-items:center;flex-wrap:wrap;">
                    <span style="font-size:0.8rem;color:var(--text-muted);flex:1;">Awaiting approval</span>
                    <form method="POST" action="{{ route('admin.borrows.approve', $borrow) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                    </form>
                    <form method="POST" action="{{ route('admin.borrows.cancel', $borrow) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-danger"
                            onclick="return confirm('Cancel this reservation?')">Deny</button>
                    </form>
                    <a href="{{ route('admin.borrows.show', $borrow) }}" class="btn btn-sm btn-secondary">Details</a>
                </div>
                @endif

                {{-- Approved --}}
                @if($borrow->status === 'approved')
                <div style="display:flex;gap:0.625rem;align-items:flex-end;flex-wrap:wrap;">
                    <div class="form-group" style="flex:1;min-width:180px;margin-bottom:0;">
                        <label class="form-label" style="font-size:0.75rem;" for="rfid_{{ $borrow->id }}">Scan RFID to Claim</label>
                        <form method="POST" action="{{ route('admin.borrows.claim', $borrow) }}" style="display:flex;gap:0.5rem;">
                            @csrf
                            <input type="text" id="rfid_{{ $borrow->id }}" name="rfid_tag"
                                class="form-control" style="font-size:0.835rem;"
                                placeholder="Scan RFID card..." required>
                            <button type="submit" class="btn btn-sm btn-primary" style="flex-shrink:0;">Claim</button>
                        </form>
                    </div>
                    <form method="POST" action="{{ route('admin.borrows.cancel', $borrow) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-secondary"
                            onclick="return confirm('Cancel this reservation? Student did not show up?')">
                            Cancel
                        </button>
                    </form>
                    <a href="{{ route('admin.borrows.show', $borrow) }}" class="btn btn-sm btn-secondary">Details</a>
                </div>
                @endif

                {{-- Claimed --}}
                @if($borrow->status === 'claimed')
                <div style="display:flex;gap:0.625rem;align-items:flex-end;flex-wrap:wrap;">
                    <div class="form-group" style="flex:1;min-width:180px;margin-bottom:0;">
                        <label class="form-label" style="font-size:0.75rem;" for="cond_{{ $borrow->id }}">Return Book</label>
                        <form method="POST" action="{{ route('admin.borrows.return', $borrow) }}" style="display:flex;gap:0.5rem;">
                            @csrf
                            <select id="cond_{{ $borrow->id }}" name="condition" class="form-control" style="font-size:0.835rem;" required>
                                <option value="">Condition...</option>
                                <option value="good">Good</option>
                                <option value="damaged">Damaged</option>
                                <option value="lost">Lost</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-gold" style="flex-shrink:0;">Return</button>
                        </form>
                    </div>
                    <a href="{{ route('admin.borrows.show', $borrow) }}" class="btn btn-sm btn-secondary">Details</a>
                </div>
                @endif

            </div>
            @else
            <div style="display:flex;justify-content:flex-end;">
                <a href="{{ route('admin.borrows.show', $borrow) }}" class="btn btn-sm btn-secondary">View Details</a>
            </div>
            @endif
        </div>
    </div>
    @endforeach
</div>
@endif

@endsection