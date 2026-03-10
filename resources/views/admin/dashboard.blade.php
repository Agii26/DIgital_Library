@extends('layouts.app')

@section('page-title', 'Dashboard')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Dashboard</h1>
        <p class="page-subtitle">{{ now()->format('l, F d, Y') }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Book
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add User
        </a>
    </div>
</div>

{{-- Welcome Banner --}}
<div style="background: linear-gradient(135deg, var(--blue) 0%, var(--blue-mid) 60%, var(--blue-bright) 100%); border-radius: var(--radius-lg); padding: 1.75rem 2rem; margin-bottom: 1.75rem; position: relative; overflow: hidden;">
    <div style="position:absolute;top:-40px;right:-40px;width:180px;height:180px;border-radius:50%;background:rgba(255,255,255,0.04);pointer-events:none;"></div>
    <div style="position:absolute;bottom:-60px;right:80px;width:220px;height:220px;border-radius:50%;background:rgba(255,255,255,0.03);pointer-events:none;"></div>
    <div style="position:relative;z-index:1;">
        <p style="font-size:0.78rem;color:rgba(255,255,255,0.65);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.35rem;">
            Library Administration
        </p>
        <h2 style="font-family:var(--font-serif);font-size:1.5rem;font-weight:600;color:#ffffff;margin-bottom:0.3rem;">
            Good {{ now()->hour < 12 ? 'morning' : (now()->hour < 17 ? 'afternoon' : 'evening') }}, {{ Auth::user()->name }}.
        </h2>
        <p style="font-size:0.835rem;color:rgba(255,255,255,0.6);">
            Here is an overview of today's library activity.
        </p>
    </div>
</div>

{{-- Stats Grid --}}
<div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.25rem;margin-bottom:1.75rem;">

    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
        </div>
        <div class="stat-label">Total Books</div>
        <div class="stat-value">{{ $totalBooks }}</div>
        <div class="stat-sub">{{ $availableBooks }} available &middot; {{ $borrowedBooks }} borrowed</div>
        @if($totalBooks > 0)
        <div style="margin-top:0.875rem;height:3px;background:var(--border);border-radius:2px;overflow:hidden;">
            <div style="height:100%;width:{{ ($availableBooks / $totalBooks) * 100 }}%;background:linear-gradient(90deg,var(--blue-bright),var(--blue-light));border-radius:2px;transition:width 0.4s;"></div>
        </div>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-card-accent {{ $overdueBorrows > 0 ? 'red' : 'blue' }}"></div>
        <div class="stat-icon {{ $overdueBorrows > 0 ? 'red' : 'blue' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
        </div>
        <div class="stat-label">Currently Borrowed</div>
        <div class="stat-value">{{ $borrowedBooks }}</div>
        @if($overdueBorrows > 0)
            <div class="stat-sub text-danger">{{ $overdueBorrows }} overdue</div>
        @else
            <div class="stat-sub">No overdue books</div>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="stat-label">Registered Users</div>
        <div class="stat-value">{{ $totalUsers }}</div>
        <div class="stat-sub">Faculty &amp; students</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent {{ $pendingReserve > 0 ? 'gold' : 'blue' }}"></div>
        <div class="stat-icon {{ $pendingReserve > 0 ? 'gold' : 'blue' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-label">Pending Reservations</div>
        <div class="stat-value" style="{{ $pendingReserve > 0 ? 'color:var(--warning)' : '' }}">{{ $pendingReserve }}</div>
        @if($pendingReserve > 0)
            <a href="{{ route('admin.borrows.index') }}" class="stat-sub text-blue" style="text-decoration:underline;text-underline-offset:2px;">Review now</a>
        @else
            <div class="stat-sub">All clear</div>
        @endif
    </div>

    <div class="stat-card">
        <div class="stat-card-accent {{ $totalUnpaid > 0 ? 'red' : 'green' }}"></div>
        <div class="stat-icon {{ $totalUnpaid > 0 ? 'red' : 'green' }}">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-label">Unpaid Penalties</div>
        <div class="stat-value" style="font-size:1.6rem;">&#8369;{{ number_format($totalUnpaid, 2) }}</div>
        <div class="stat-sub">Outstanding balance</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        </div>
        <div class="stat-label">Today's Visitors</div>
        <div class="stat-value">{{ $todayAttendance }}</div>
        <div class="stat-sub">{{ $currentlyIn }} currently inside</div>
    </div>

</div>

{{-- Quick Actions --}}
<div class="card mb-6">
    <div class="card-header">
        <h3 class="card-title">Quick Actions</h3>
    </div>
    <div class="card-body" style="display:flex;gap:0.75rem;flex-wrap:wrap;">
        <a href="{{ route('admin.books.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add Book
        </a>
        <a href="{{ route('admin.users.create') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add User
        </a>
        <a href="{{ route('admin.attendance.kiosk') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            Kiosk Mode
        </a>
        <a href="{{ route('admin.borrows.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            Manage Borrows
        </a>
        <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            View Reports
        </a>
        <a href="{{ route('admin.users.password-links') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
            Password Links
        </a>
    </div>
</div>



{{-- Recent Activity --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;">

    {{-- Recent Borrows --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Borrowings</h3>
            <a href="{{ route('admin.borrows.index') }}" class="btn btn-sm btn-secondary">View all</a>
        </div>
        @forelse($recentBorrows as $borrow)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:0.875rem 1.5rem;border-bottom:1px solid var(--border);">
            <div style="overflow:hidden;">
                <p style="font-size:0.85rem;font-weight:500;color:var(--text-head);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $borrow->user->name ?? 'Deleted User' }}
                </p>
                <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.1rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $borrow->book->title }}
                </p>
            </div>
            <span class="badge {{ $borrow->status === 'reserved' ? 'badge-blue' : ($borrow->status === 'approved' ? 'badge-warning' : ($borrow->status === 'claimed' ? 'badge-warning' : ($borrow->status === 'returned' ? 'badge-success' : 'badge-muted'))) }}" style="margin-left:1rem;flex-shrink:0;">
                {{ ucfirst($borrow->status) }}
            </span>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
            </div>
            <p class="empty-state-title">No borrowings yet</p>
        </div>
        @endforelse
    </div>

    {{-- Recent Attendance --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Attendance</h3>
            <a href="{{ route('admin.attendance.index') }}" class="btn btn-sm btn-secondary">View all</a>
        </div>
        @forelse($recentAttendance as $log)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:0.875rem 1.5rem;border-bottom:1px solid var(--border);">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div class="avatar avatar-sm">{{ strtoupper(substr($log->user->name, 0, 1)) }}</div>
                <div>
                    <p style="font-size:0.85rem;font-weight:500;color:var(--text-head);">{{ $log->user->name }}</p>
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.1rem;">{{ $log->scanned_at->format('h:i A') }}</p>
                </div>
            </div>
            <span class="badge {{ $log->type === 'time_in' ? 'badge-success' : 'badge-danger' }}" style="flex-shrink:0;">
                {{ $log->type === 'time_in' ? 'Time In' : 'Time Out' }}
            </span>
        </div>
        @empty
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            </div>
            <p class="empty-state-title">No attendance logs yet</p>
        </div>
        @endforelse
    </div>

</div>{{-- end grid --}}

@endsection