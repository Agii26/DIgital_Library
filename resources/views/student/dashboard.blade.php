@extends('layouts.app')

@section('page-title', 'My Dashboard')

@push('styles')
{{--
  RESPONSIVE STRATEGY — Student Dashboard (prefix: sd-)
  ──────────────────────────────────────────────────────────────────
  The layout has a fixed sidebar of ~256px (var --sidebar-width).
  Breakpoints are viewport-width offsets, NOT content-width values.

  Tier 1  >1100px  Desktop.        Sidebar visible. 4-col grids.
  Tier 2  640-1100 Narrow desktop. Sidebar still shows. 2-col grids.
  Tier 3  ≤640px   True mobile.    Sidebar hidden/overlay. Stack all.

  Why these numbers:
    4-col comfortable content min ≈ 600px  → 600+256 = 856  (use 1100 for breathing room)
    2-col comfortable content min ≈ 360px  → 360+256 = 616  (use 640)

  The global app.css .grid-4 breakpoint fires at 1024px which is
  WRONG for this layout (ignores the 256px sidebar). We use .sd-grid-4
  here with corrected sidebar-aware breakpoints instead.

  NEVER write breakpoints at 768px, 900px, or 480px — they ignore
  the sidebar and will break the desktop view.
──────────────────────────────────────────────────────────────────
--}}
<style>

/* ── Dashboard 4-col grid — sidebar-aware breakpoints ───────── */
.sd-grid-4 {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.25rem;
    margin-bottom: 1.5rem;
}

/* Tier 2: Narrow desktop / tablet — sidebar still visible       */
@media (max-width: 1100px) {
    .sd-grid-4 {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Tier 3: True mobile — sidebar hidden, tighten gap             */
@media (max-width: 640px) {
    .sd-grid-4 {
        grid-template-columns: repeat(2, 1fr);
        gap: 0.75rem;
        margin-bottom: 1rem;
    }
}

/* ── Alert banners ─────────────────────────────────────────── */
.sd-alert-inner {
    display: flex;
    align-items: center;
    gap: 1rem;
    width: 100%;
}

.sd-alert-text { flex: 1; min-width: 0; }
.sd-alert-cta  { flex-shrink: 0; }

@media (max-width: 640px) {
    .sd-alert-inner   { flex-wrap: wrap; gap: 0.75rem; }
    .sd-alert-cta     { width: 100%; }
    .sd-alert-cta .btn { width: 100%; justify-content: center; }
}

/* ── Recent borrow rows ─────────────────────────────────────── */
.sd-borrow-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 0;
    border-bottom: 1px solid var(--border, #e5e7eb);
}

.sd-borrow-row-meta {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-shrink: 0;
}

@media (max-width: 640px) {
    .sd-borrow-row      { flex-direction: column; align-items: flex-start; gap: 0.4rem; }
    .sd-borrow-row-meta { width: 100%; justify-content: flex-end; }
}

</style>
@endpush

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">My Dashboard</h1>
        <p class="page-subtitle">Welcome back, {{ Auth::user()->name }}</p>
    </div>
</div>

{{-- Unpaid Penalties Banner --}}
@if($unpaidPenalties > 0)
<div class="alert alert-danger" style="margin-bottom:1.25rem;">
    <div class="sd-alert-inner">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
        </svg>
        <div class="sd-alert-text">
            <div style="font-weight:600;font-size:0.875rem;">You have unpaid penalties</div>
            <div style="font-size:0.8rem;margin-top:2px;">Total: &#8369;{{ number_format($unpaidPenalties, 2) }} &mdash; Please settle at the library counter.</div>
        </div>
        <div class="sd-alert-cta">
            <a href="{{ route('student.penalties.index') }}" class="btn btn-danger btn-sm">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
                </svg>
                View Penalties
            </a>
        </div>
    </div>
</div>
@endif

{{-- Overdue Borrows Banner --}}
@if($overdueBorrows > 0)
<div class="alert alert-warning" style="margin-bottom:1.25rem;">
    <div class="sd-alert-inner">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
        </svg>
        <div class="sd-alert-text">
            <div style="font-weight:600;font-size:0.875rem;">You have {{ $overdueBorrows }} overdue book{{ $overdueBorrows > 1 ? 's' : '' }}</div>
            <div style="font-size:0.8rem;margin-top:2px;">Please return them immediately to avoid additional fines.</div>
        </div>
        <div class="sd-alert-cta">
            <a href="{{ route('student.borrows.index') }}" class="btn btn-sm btn-outline">
                <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                </svg>
                View Borrows
            </a>
        </div>
    </div>
</div>
@endif

{{-- Stat Cards --}}
<div class="sd-grid-4">

    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
            </svg>
        </div>
        <div class="stat-label">Active Borrows</div>
        <div class="stat-value">{{ $activeBorrows }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent gold"></div>
        <div class="stat-icon gold">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="stat-label">Pending Reservations</div>
        <div class="stat-value">{{ $pendingReservations }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent green"></div>
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <div class="stat-label">Available Books</div>
        <div class="stat-value">{{ $availableBooks }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3" />
            </svg>
        </div>
        <div class="stat-label">Digital Books</div>
        <div class="stat-value">{{ $digitalBooks }}</div>
    </div>

</div>

{{-- Quick Actions --}}
<div class="sd-grid-4">

    <a href="{{ route('student.borrows.create') }}" class="card" style="text-align:center;padding:1.5rem 1rem;text-decoration:none;transition:box-shadow 0.2s,transform 0.15s;background:var(--blue,#1a3a6b);"
       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='0 8px 24px rgba(26,58,107,0.18)'"
       onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">
        <div style="width:44px;height:44px;border-radius:50%;background:rgba(255,255,255,0.15);display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="white" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div style="font-weight:600;font-size:0.85rem;color:#ffffff;">Reserve Book</div>
    </a>

    <a href="{{ route('student.borrows.index') }}" class="card" style="text-align:center;padding:1.5rem 1rem;text-decoration:none;transition:box-shadow 0.2s,transform 0.15s;"
       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='var(--shadow-md)'"
       onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">
        <div style="width:44px;height:44px;border-radius:50%;background:var(--blue-ultra-pale,#eef3fb);display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="var(--blue,#1a3a6b)" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
            </svg>
        </div>
        <div style="font-weight:600;font-size:0.85rem;color:var(--text-head);">My Borrows</div>
    </a>

    <a href="{{ route('digital.index') }}" class="card" style="text-align:center;padding:1.5rem 1rem;text-decoration:none;transition:box-shadow 0.2s,transform 0.15s;"
       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='var(--shadow-md)'"
       onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">
        <div style="width:44px;height:44px;border-radius:50%;background:var(--blue-ultra-pale,#eef3fb);display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="var(--blue,#1a3a6b)" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3" />
            </svg>
        </div>
        <div style="font-weight:600;font-size:0.85rem;color:var(--text-head);">Digital Books</div>
    </a>

    <a href="{{ route('student.penalties.index') }}" class="card" style="text-align:center;padding:1.5rem 1rem;text-decoration:none;transition:box-shadow 0.2s,transform 0.15s;"
       onmouseover="this.style.transform='translateY(-2px)';this.style.boxShadow='var(--shadow-md)'"
       onmouseout="this.style.transform='translateY(0)';this.style.boxShadow=''">
        <div style="width:44px;height:44px;border-radius:50%;background:var(--blue-ultra-pale,#eef3fb);display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;">
            <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="none" viewBox="0 0 24 24" stroke="var(--blue,#1a3a6b)" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div style="font-weight:600;font-size:0.85rem;color:var(--text-head);">My Penalties</div>
    </a>

</div>

{{-- Recent Borrows --}}
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:7px;vertical-align:middle;color:var(--gold);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            Recent Borrowings
        </h2>
        <a href="{{ route('student.borrows.index') }}" class="btn btn-outline btn-sm">
            View all
            <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-left:4px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
            </svg>
        </a>
    </div>
    <div class="card-body" style="padding-top:0.25rem;">
        @forelse($recentBorrows as $borrow)
        <div class="sd-borrow-row">
            <div>
                <div style="font-weight:500;font-size:0.875rem;color:var(--text-head);">{{ $borrow->book->title }}</div>
                <div style="font-size:0.72rem;color:var(--text-muted);margin-top:2px;">{{ $borrow->reserved_at?->format('M d, Y') ?? '&mdash;' }}</div>
            </div>
            <div class="sd-borrow-row-meta">
                @if($borrow->due_date && $borrow->status === 'claimed')
                    <span style="font-size:0.72rem;font-weight:{{ now()->isAfter($borrow->due_date) ? '600' : '400' }};color:{{ now()->isAfter($borrow->due_date) ? 'var(--danger,#b91c1c)' : 'var(--text-muted)' }};">
                        Due {{ $borrow->due_date->format('M d, Y') }}
                    </span>
                @endif
                @php
                    $statusMap = [
                        'reserved' => 'badge-blue',
                        'approved' => 'badge-warning',
                        'claimed'  => 'badge-gold',
                        'returned' => 'badge-success',
                    ];
                    $badgeClass = $statusMap[$borrow->status] ?? 'badge-muted';
                @endphp
                <span class="badge {{ $badgeClass }}">{{ ucfirst($borrow->status) }}</span>
            </div>
        </div>
        @empty
        <div class="empty-state" style="padding:2rem 0;">
            <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
            </div>
            <div class="empty-state-title">No borrowings yet</div>
            <div class="empty-state-text">Reserve a book to get started</div>
        </div>
        @endforelse
    </div>
</div>

@endsection