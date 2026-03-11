@extends('layouts.app')

@section('page-title', 'My Dashboard')

@section('content')

{{-- Penalty Alert --}}
@if($unpaidPenalties > 0)
<div class="alert alert-danger" style="margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:0.75rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z"/></svg>
        <div>
            <p style="font-weight:600;font-size:0.875rem;">You have unpaid penalties!</p>
            <p style="font-size:0.78rem;opacity:0.85;margin-top:0.1rem;">Total: <strong>&#8369;{{ number_format($unpaidPenalties, 2) }}</strong> — Please settle at the library.</p>
        </div>
    </div>
    <a href="{{ route('faculty.penalties.index') }}" class="btn btn-danger btn-sm" style="flex-shrink:0;">View Penalties</a>
</div>
@endif

{{-- Overdue Alert --}}
@if($overdueBorrows > 0)
<div class="alert alert-warning" style="margin-bottom:1.25rem;display:flex;align-items:center;justify-content:space-between;gap:1rem;flex-wrap:wrap;">
    <div style="display:flex;align-items:center;gap:0.75rem;">
        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        <div>
            <p style="font-weight:600;font-size:0.875rem;">You have {{ $overdueBorrows }} overdue book(s)!</p>
            <p style="font-size:0.78rem;opacity:0.85;margin-top:0.1rem;">Please return them immediately to avoid additional fines.</p>
        </div>
    </div>
    <a href="{{ route('faculty.borrows.index') }}" class="btn btn-sm" style="flex-shrink:0;background:#b45309;color:#fff;border:none;">View Borrows</a>
</div>
@endif

{{-- Welcome Banner --}}
<div style="background:linear-gradient(135deg,#0d1b2e 0%,#1a3a6b 100%);border-radius:var(--radius-lg);padding:1.75rem 2rem;margin-bottom:1.5rem;position:relative;overflow:hidden;">
    <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;background:rgba(184,146,42,0.08);border-radius:50%;"></div>
    <div style="position:absolute;bottom:-40px;right:60px;width:100px;height:100px;background:rgba(184,146,42,0.05);border-radius:50%;"></div>
    <p style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.12em;color:rgba(255,255,255,0.45);margin-bottom:0.35rem;">Welcome back</p>
    <h2 style="font-family:var(--font-serif);font-size:1.5rem;font-weight:700;color:#fff;margin-bottom:0.25rem;">{{ Auth::user()->name }}</h2>
    <p style="font-size:0.82rem;color:rgba(255,255,255,0.5);">Faculty &mdash; {{ now()->format('l, F d, Y') }}</p>
</div>

{{-- Stat Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-bottom:1.5rem;">

    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
        </div>
        <div class="stat-label">Active Borrows</div>
        <div class="stat-value">{{ $activeBorrows }}</div>
        <div class="stat-sub">Currently borrowed</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent gold"></div>
        <div class="stat-icon gold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-label">Pending Reservations</div>
        <div class="stat-value">{{ $pendingReservations }}</div>
        <div class="stat-sub">Awaiting approval</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent green"></div>
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="stat-label">Available Books</div>
        <div class="stat-value">{{ $availableBooks }}</div>
        <div class="stat-sub">Ready to reserve</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent red"></div>
        <div class="stat-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3"/></svg>
        </div>
        <div class="stat-label">Digital Books</div>
        <div class="stat-value">{{ $digitalBooks }}</div>
        <div class="stat-sub">Available to read</div>
    </div>

</div>

{{-- Quick Actions + Recent Borrows --}}
<div style="display:grid;grid-template-columns:280px 1fr;gap:1.25rem;">

    {{-- Quick Actions --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Quick Actions</h3>
        </div>
        <div class="card-body" style="display:flex;flex-direction:column;gap:0.5rem;padding:1rem;">
            <a href="{{ route('faculty.books.index') }}" class="btn btn-primary" style="justify-content:flex-start;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                Reserve a Book
            </a>
            <a href="{{ route('faculty.borrows.index') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/></svg>
                My Borrows
            </a>
            <a href="{{ route('digital.index') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3"/></svg>
                Digital Books
            </a>
            <a href="{{ route('faculty.penalties.index') }}" class="btn btn-secondary" style="justify-content:flex-start;">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                My Penalties
            </a>
        </div>
    </div>

    {{-- Recent Borrows --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Recent Borrowings</h3>
            <a href="{{ route('faculty.borrows.index') }}" style="font-size:0.78rem;color:var(--blue-bright);text-decoration:none;">View all</a>
        </div>
        @forelse($recentBorrows as $borrow)
        <div style="display:flex;align-items:center;justify-content:space-between;padding:0.875rem 1.5rem;border-bottom:1px solid var(--border);gap:1rem;">
            <div style="min-width:0;">
                <p style="font-size:0.855rem;font-weight:500;color:var(--text-head);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $borrow->book->title }}</p>
                <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.1rem;">Reserved {{ $borrow->reserved_at?->format('M d, Y') }}</p>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-shrink:0;">
                @if($borrow->due_date && $borrow->status === 'claimed')
                    <span style="font-size:0.75rem;color:{{ now()->isAfter($borrow->due_date) ? 'var(--danger)' : 'var(--text-dim)' }};font-weight:{{ now()->isAfter($borrow->due_date) ? '600' : '400' }};">
                        Due {{ $borrow->due_date->format('M d, Y') }}
                    </span>
                @endif
                <span class="badge
                    {{ $borrow->status === 'reserved'  ? 'badge-blue' :
                       ($borrow->status === 'approved'  ? 'badge-warning' :
                       ($borrow->status === 'claimed'   ? 'badge-gold' :
                       ($borrow->status === 'returned'  ? 'badge-success' :
                       'badge-muted'))) }}">
                    {{ ucfirst($borrow->status) }}
                </span>
            </div>
        </div>
        @empty
        <div class="card-body">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0118 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <p class="empty-state-title">No borrowings yet</p>
                <p class="empty-state-text">Reserve a book to get started.</p>
            </div>
        </div>
        @endforelse
    </div>

</div>

@endsection