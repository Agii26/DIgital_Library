@extends('layouts.app')

@section('page-title', 'Borrowing Management')

@section('content')

<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Borrowing Management</h1>
        <p class="page-subtitle">Track and manage all book reservations and loans</p>
    </div>
    @if($stats['pending'] > 0)
    <div class="page-actions">
        <form method="POST" action="{{ route('admin.borrows.approve-all') }}">
            @csrf
            <button type="submit" class="btn btn-success"
                onclick="return confirm('Approve all {{ $stats['pending'] }} pending reservation(s)? All users will be notified.')">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Approve All Pending ({{ $stats['pending'] }})
            </button>
        </form>
    </div>
    @endif
</div>

@if(session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

@if(session('error'))
<div class="alert alert-danger">{{ session('error') }}</div>
@endif

{{-- Stat Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-bottom:1.75rem;">
    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="stat-label">Pending Approval</p>
        <p class="stat-value">{{ $stats['pending'] }}</p>
        <p class="stat-sub">Awaiting review</p>
    </div>
    <div class="stat-card">
        <div class="stat-card-accent gold"></div>
        <div class="stat-icon gold">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 13l4 4L19 7"/></svg>
        </div>
        <p class="stat-label">Approved</p>
        <p class="stat-value">{{ $stats['approved'] }}</p>
        <p class="stat-sub">Ready for pickup</p>
    </div>
    <div class="stat-card">
        <div class="stat-card-accent green"></div>
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <p class="stat-label">Currently Claimed</p>
        <p class="stat-value">{{ $stats['claimed'] }}</p>
        <p class="stat-sub">Books out on loan</p>
    </div>
    <div class="stat-card">
        <div class="stat-card-accent red"></div>
        <div class="stat-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <p class="stat-label">Overdue</p>
        <p class="stat-value">{{ $stats['overdue'] }}</p>
        <p class="stat-sub">Past due date</p>
    </div>
</div>

{{-- Tabs --}}
<div style="display:flex;gap:0;border-bottom:2px solid var(--border);margin-bottom:1.5rem;">
    <a href="{{ route('admin.borrows.index') }}"
        style="padding:0.625rem 1.375rem;font-size:0.845rem;font-weight:500;text-decoration:none;border-bottom:2px solid {{ $activeTab === 'pending' ? 'var(--blue-bright)' : 'transparent' }};margin-bottom:-2px;color:{{ $activeTab === 'pending' ? 'var(--blue-bright)' : 'var(--text-muted)' }};display:flex;align-items:center;gap:0.5rem;">
        Pending Approvals
        @if($stats['pending'] > 0)
        <span style="background:var(--blue-bright);color:#fff;font-size:0.65rem;font-weight:700;padding:0.1rem 0.45rem;border-radius:999px;">{{ $stats['pending'] }}</span>
        @endif
    </a>
    <a href="{{ route('admin.borrows.index', ['tab' => 'all']) }}"
        style="padding:0.625rem 1.375rem;font-size:0.845rem;font-weight:500;text-decoration:none;border-bottom:2px solid {{ $activeTab === 'all' ? 'var(--blue-bright)' : 'transparent' }};margin-bottom:-2px;color:{{ $activeTab === 'all' ? 'var(--blue-bright)' : 'var(--text-muted)' }};">
        All Borrows
    </a>
</div>

{{-- ── PENDING TAB ── --}}
@if($activeTab === 'pending')
@if($pending->isEmpty())
<div class="card">
    <div class="empty-state">
        <div class="empty-state-icon">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="empty-state-title">All caught up!</p>
        <p class="empty-state-text">No pending reservations at this time.</p>
    </div>
</div>
@else
<div class="card">
    <div class="table-wrapper" style="border:none;border-radius:var(--radius-lg);box-shadow:none;">
        <table>
            <thead>
                <tr>
                    <th>Borrower</th>
                    <th>Book</th>
                    <th>Accession No.</th>
                    <th>Borrow Limit</th>
                    <th>Reserved</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pending as $borrow)
                @php
                    $activeBorrows = $borrow->user
                        ? \App\Models\PhysicalBorrow::where('user_id', $borrow->user->id)
                            ->whereIn('status', ['approved','claimed'])->count()
                        : 0;
                    $limit = $borrow->user?->role === 'faculty' ? 5 : 2;
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.625rem;">
                            <div class="avatar avatar-sm">{{ strtoupper(substr($borrow->user?->name ?? 'D', 0, 1)) }}</div>
                            <div>
                                <p style="font-size:0.845rem;font-weight:500;color:var(--text-head);">{{ $borrow->user?->name ?? 'Deleted User' }}</p>
                                <p style="font-size:0.72rem;color:var(--text-muted);">{{ ucfirst($borrow->user?->role ?? '—') }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <p style="font-size:0.845rem;font-weight:500;color:var(--text-head);max-width:180px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $borrow->book->title }}</p>
                        <p style="font-size:0.72rem;color:var(--text-muted);">{{ $borrow->book->author }}</p>
                    </td>
                    <td>
                        <code style="font-size:0.75rem;color:var(--text-muted);background:var(--surface-2);padding:0.2rem 0.5rem;border-radius:var(--radius);border:1px solid var(--border);">{{ $borrow->book->accession_no }}</code>
                    </td>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <div style="display:flex;gap:3px;">
                                @for($i = 0; $i < $limit; $i++)
                                <div style="width:10px;height:10px;border-radius:2px;background:{{ $i < $activeBorrows ? 'var(--blue-bright)' : 'var(--border)' }};"></div>
                                @endfor
                            </div>
                            <span style="font-size:0.72rem;color:var(--text-muted);">{{ $activeBorrows }}/{{ $limit }}</span>
                        </div>
                    </td>
                    <td style="font-size:0.835rem;color:var(--text-muted);white-space:nowrap;">
                        {{ $borrow->reserved_at?->format('M d, Y h:i A') ?? '—' }}
                    </td>
                    <td>
                        <div style="display:flex;gap:0.375rem;">
                            <form method="POST" action="{{ route('admin.borrows.approve', $borrow) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-success">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.borrows.cancel', $borrow) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger"
                                    onclick="return confirm('Deny this reservation?')">Deny</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endif
@endif

{{-- ── ALL BORROWS TAB ── --}}
@if($activeTab === 'all')

{{-- RFID Quick Checkout Panel --}}
<div class="card" style="margin-bottom:1.5rem;border-left:3px solid var(--gold);">
    <div class="card-header">
        <div>
            <h3 class="card-title">Quick Book Checkout</h3>
            <p style="font-size:0.78rem;color:var(--text-muted);margin-top:0.2rem;">Scan a student's RFID card to view and confirm their approved books</p>
        </div>
    </div>
    <div class="card-body">
        <div style="display:flex;gap:0.75rem;align-items:flex-end;flex-wrap:wrap;">
            <div class="form-group" style="flex:1;min-width:220px;margin-bottom:0;">
                <label class="form-label" for="rfid-input">RFID Card</label>
                <input type="text" id="rfid-input" class="form-control"
                    placeholder="Scan or type RFID tag..."
                    autofocus autocomplete="off">
            </div>
            <button type="button" onclick="lookupRfid()" class="btn btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                Lookup
            </button>
        </div>

        {{-- Result panel --}}
        <div id="rfid-result" style="display:none;margin-top:1.25rem;border-top:1px solid var(--border);padding-top:1.25rem;">
            <div id="rfid-error" style="display:none;" class="alert alert-danger"></div>
            <div id="rfid-found" style="display:none;">
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:1rem;">
                    <div id="rfid-avatar" class="avatar avatar-md"></div>
                    <div>
                        <p id="rfid-name" style="font-size:0.9rem;font-weight:600;color:var(--text-head);"></p>
                        <p id="rfid-role" style="font-size:0.775rem;color:var(--text-muted);"></p>
                    </div>
                </div>
                <div id="rfid-books" style="display:flex;flex-direction:column;gap:0.5rem;margin-bottom:1.25rem;"></div>
                <form method="POST" action="{{ route('admin.borrows.claim-all') }}" id="claim-all-form">
                    @csrf
                    <input type="hidden" name="user_id" id="claim-user-id">
                    <button type="submit" class="btn btn-success"
                        onclick="return confirm('Confirm checkout for all listed books?')">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        Confirm Checkout
                    </button>
                    <button type="button" onclick="clearRfid()" class="btn btn-secondary" style="margin-left:0.5rem;">Clear</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Search & Filter --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1.125rem 1.5rem;">
        <form method="GET" action="{{ route('admin.borrows.index') }}" style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;">
            <input type="hidden" name="tab" value="all">
            <div style="position:relative;flex:1;min-width:220px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text-dim);pointer-events:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by name or email..."
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
            <a href="{{ route('admin.borrows.index', ['tab' => 'all']) }}" class="btn btn-secondary">Reset</a>
        </form>
    </div>
</div>

{{-- Users Table --}}
<div class="card">
    <div class="table-wrapper" style="border:none;border-radius:var(--radius-lg);box-shadow:none;">
        <table>
            <thead>
                <tr>
                    <th>Borrower</th>
                    <th>Role</th>
                    <th>Borrow Summary</th>
                    <th>Active</th>
                    <th>Overdue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                @php
                    $borrows   = $user->physicalBorrows;
                    $active    = $borrows->whereIn('status', ['reserved','approved','claimed'])->count();
                    $overdue   = $borrows->filter(fn($b) => $b->status === 'claimed' && $b->due_date && now()->isAfter($b->due_date))->count();
                    $reserved  = $borrows->where('status', 'reserved')->count();
                    $approved  = $borrows->where('status', 'approved')->count();
                    $claimed   = $borrows->where('status', 'claimed')->count();
                    $returned  = $borrows->where('status', 'returned')->count();
                    $cancelled = $borrows->where('status', 'cancelled')->count();
                    $total     = $borrows->count();
                @endphp
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.625rem;">
                            <div class="avatar avatar-sm">{{ strtoupper(substr($user->name ?? 'D', 0, 1)) }}</div>
                            <div>
                                <p style="font-size:0.845rem;font-weight:500;color:var(--text-head);">
                                    {{ $user->name ?? 'Deleted User' }}
                                    @if($user->trashed())
                                        <span class="badge badge-danger" style="font-size:0.6rem;margin-left:0.3rem;">Deleted</span>
                                    @endif
                                </p>
                                <p style="font-size:0.72rem;color:var(--text-muted);">{{ $user->email }}</p>
                            </div>
                        </div>
                    </td>
                    <td>
                        <span class="badge {{ $user->role === 'faculty' ? 'badge-blue' : 'badge-muted' }}">
                            {{ ucfirst($user->role ?? '—') }}
                        </span>
                    </td>
                    <td>
                        <div style="display:flex;gap:0.35rem;flex-wrap:wrap;">
                            @if($reserved)  <span class="badge badge-blue">{{ $reserved }} Reserved</span>       @endif
                            @if($approved)  <span class="badge badge-warning">{{ $approved }} Approved</span>   @endif
                            @if($claimed)   <span class="badge badge-gold">{{ $claimed }} Claimed</span>        @endif
                            
                            
                        </div>
                    </td>
                    <td>
                        @if($active)
                            <span style="font-size:0.845rem;font-weight:600;color:var(--blue);">{{ $active }}</span>
                        @else
                            <span style="color:var(--text-dim);">—</span>
                        @endif
                    </td>
                    <td>
                        @if($overdue)
                            <span class="badge badge-danger">{{ $overdue }} Overdue</span>
                        @else
                            <span style="color:var(--text-dim);">—</span>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('admin.borrows.show', $user) }}" class="btn btn-sm btn-primary">
                            Manage
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                            </div>
                            <p class="empty-state-title">No records found</p>
                            <p class="empty-state-text">Try adjusting your search or filters.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($users->hasPages())
    <div class="card-footer" style="display:flex;align-items:center;justify-content:space-between;">
        <p style="font-size:0.8rem;color:var(--text-muted);">
            Showing {{ $users->firstItem() }}–{{ $users->lastItem() }} of {{ $users->total() }} users
        </p>
        <div class="pagination">
            {{ $users->appends(request()->except('page'))->links() }}
        </div>
    </div>
    @endif
</div>

@endif

@endsection

@push('scripts')
<script>
const rfidLookupUrl = '{{ route('admin.borrows.rfid-lookup') }}';
const csrfToken     = '{{ csrf_token() }}';

function lookupRfid() {
    const rfid = document.getElementById('rfid-input').value.trim();
    if (!rfid) return;

    fetch(rfidLookupUrl, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({ rfid_tag: rfid }),
    })
    .then(r => r.json())
    .then(data => {
        const result  = document.getElementById('rfid-result');
        const error   = document.getElementById('rfid-error');
        const found   = document.getElementById('rfid-found');

        result.style.display = 'block';

        if (!data.found) {
            error.style.display = 'block';
            error.textContent   = data.message;
            found.style.display = 'none';
            return;
        }

        error.style.display = 'none';

        if (!data.borrows || data.borrows.length === 0) {
            error.style.display = 'block';
            error.textContent   = data.message;
            found.style.display = 'none';
            return;
        }

        // Populate user info
        document.getElementById('rfid-avatar').textContent = data.user.name.charAt(0).toUpperCase();
        document.getElementById('rfid-name').textContent   = data.user.name;
        document.getElementById('rfid-role').textContent   = data.user.role.charAt(0).toUpperCase() + data.user.role.slice(1);
        document.getElementById('claim-user-id').value     = data.user.id;

        // Populate books list
        const booksEl = document.getElementById('rfid-books');
        booksEl.innerHTML = data.borrows.map(b => `
            <div style="display:flex;align-items:center;justify-content:space-between;padding:0.625rem 0.875rem;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius);gap:1rem;">
                <div>
                    <p style="font-size:0.855rem;font-weight:500;color:var(--text-head);">${b.title}</p>
                    <p style="font-size:0.75rem;color:var(--text-muted);">${b.author} &middot; <code style="font-size:0.72rem;">${b.accession_no}</code></p>
                </div>
                <span class="badge badge-warning">Approved</span>
            </div>
        `).join('');

        found.style.display = 'block';
    })
    .catch(() => {
        document.getElementById('rfid-result').style.display = 'block';
        document.getElementById('rfid-error').style.display  = 'block';
        document.getElementById('rfid-error').textContent    = 'An error occurred. Please try again.';
        document.getElementById('rfid-found').style.display  = 'none';
    });
}

function clearRfid() {
    document.getElementById('rfid-input').value      = '';
    document.getElementById('rfid-result').style.display = 'none';
    document.getElementById('rfid-found').style.display  = 'none';
    document.getElementById('rfid-error').style.display  = 'none';
    document.getElementById('rfid-input').focus();
}

// Allow pressing Enter on RFID input to trigger lookup
document.getElementById('rfid-input')?.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') { e.preventDefault(); lookupRfid(); }
});
</script>
@endpush