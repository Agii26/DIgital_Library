@extends('layouts.app')

@section('page-title', 'Reports & Analytics')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Reports &amp; Analytics</h1>
        <p class="page-subtitle">Overview of library activity, circulation, and financial data</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.reports.export', ['year' => now()->year]) }}" class="btn btn-success">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:6px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
            </svg>
            Export to Excel
        </a>
    </div>
</div>

{{-- Overview Stat Cards --}}
<div style="display:grid;grid-template-columns:repeat(6,1fr);gap:1rem;margin-bottom:1.5rem;">

    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
        </div>
        <div class="stat-label">Total Books</div>
        <div class="stat-value">{{ $totalBooks }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent green"></div>
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
        </div>
        <div class="stat-label">Total Users</div>
        <div class="stat-value">{{ $totalUsers }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent gold"></div>
        <div class="stat-icon gold">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
            </svg>
        </div>
        <div class="stat-label">Total Borrows</div>
        <div class="stat-value">{{ $totalBorrows }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent red"></div>
        <div class="stat-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
        </div>
        <div class="stat-label">Total Penalties</div>
        <div class="stat-value" style="font-size:1.4rem;">&#8369;{{ number_format($totalPenalties, 2) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent red"></div>
        <div class="stat-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
            </svg>
        </div>
        <div class="stat-label">Unpaid</div>
        <div class="stat-value" style="font-size:1.4rem;">&#8369;{{ number_format($totalUnpaid, 2) }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent green"></div>
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
        </div>
        <div class="stat-label">Collected</div>
        <div class="stat-value" style="font-size:1.4rem;">&#8369;{{ number_format($totalCollected, 2) }}</div>
    </div>

</div>

{{-- Charts Row 1: Monthly Borrows + Attendance --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;margin-bottom:1.5rem;">

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:7px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                </svg>
                Monthly Borrows &mdash; {{ $year }}
            </h2>
        </div>
        <div class="card-body">
            <canvas id="borrowsChart" style="max-height:260px;"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:7px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                </svg>
                Monthly Attendance &mdash; {{ $year }}
            </h2>
        </div>
        <div class="card-body">
            <canvas id="attendanceChart" style="max-height:260px;"></canvas>
        </div>
    </div>

</div>

{{-- Charts Row 2: Books by Status + Penalty Breakdown --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;margin-bottom:1.5rem;">

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:7px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                Books by Status
            </h2>
        </div>
        <div class="card-body" style="display:flex;justify-content:center;">
            <canvas id="booksStatusChart" style="max-height:260px;max-width:260px;"></canvas>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:7px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Penalty Breakdown
            </h2>
        </div>
        <div class="card-body" style="display:flex;justify-content:center;">
            <canvas id="penaltyChart" style="max-height:260px;max-width:260px;"></canvas>
        </div>
    </div>

</div>

{{-- Most Borrowed + Most Active Borrowers --}}
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;margin-bottom:1.5rem;">

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:7px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 013 3h-15a3 3 0 013-3m9 0v-3.375c0-.621-.503-1.125-1.125-1.125h-.871M7.5 18.75v-3.375c0-.621.504-1.125 1.125-1.125h.872m5.007 0H9.497m5.007 0a7.454 7.454 0 01-.982-3.172M9.497 14.25a7.454 7.454 0 00.981-3.172M5.25 4.236c-.982.143-1.954.317-2.916.52A6.003 6.003 0 007.73 9.728M5.25 4.236V4.5c0 2.108.966 3.99 2.48 5.228M5.25 4.236V2.721C7.456 2.41 9.71 2.25 12 2.25c2.291 0 4.545.16 6.75.47v1.516M7.73 9.728a6.726 6.726 0 002.748 1.35m8.272-6.842V4.5c0 2.108-.966 3.99-2.48 5.228m2.48-5.492a46.32 46.32 0 012.916.52 6.003 6.003 0 01-5.395 4.972m0 0a6.726 6.726 0 01-2.749 1.35m0 0a6.772 6.772 0 01-3.044 0" />
                </svg>
                Most Borrowed Books
            </h2>
        </div>
        <div class="card-body" style="padding-top:0.25rem;">
            @forelse($mostBorrowed as $borrow)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.7rem 0;border-bottom:1px solid var(--border, #e5e7eb);">
                <div>
                    <div style="font-weight:500;font-size:0.875rem;color:var(--text-head);">{{ $borrow->book->title }}</div>
                    <div style="font-size:0.75rem;color:var(--text-muted);">{{ $borrow->book->accession_no }}</div>
                </div>
                <span class="badge badge-blue">{{ $borrow->borrow_count }}x</span>
            </div>
            @empty
            <div class="empty-state" style="padding:2rem 0;">
                <div class="empty-state-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                    </svg>
                </div>
                <div class="empty-state-title">No data yet</div>
            </div>
            @endforelse
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:7px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Most Active Borrowers
            </h2>
        </div>
        <div class="card-body" style="padding-top:0.25rem;">
            @forelse($mostActive as $borrow)
            <div style="display:flex;justify-content:space-between;align-items:center;padding:0.7rem 0;border-bottom:1px solid var(--border, #e5e7eb);">
                <div style="display:flex;align-items:center;gap:0.6rem;">
                    <div class="avatar avatar-sm">{{ strtoupper(substr($borrow->user->name, 0, 1)) }}</div>
                    <div>
                        <div style="font-weight:500;font-size:0.875rem;color:var(--text-head);">{{ $borrow->user->name }}</div>
                        <div style="font-size:0.75rem;color:var(--text-muted);">{{ ucfirst($borrow->user->role) }} &bull; {{ $borrow->user->department ?? '&mdash;' }}</div>
                    </div>
                </div>
                <span class="badge badge-success">{{ $borrow->borrow_count }}x</span>
            </div>
            @empty
            <div class="empty-state" style="padding:2rem 0;">
                <div class="empty-state-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                    </svg>
                </div>
                <div class="empty-state-title">No data yet</div>
            </div>
            @endforelse
        </div>
    </div>

</div>

{{-- ============================================================ --}}
{{-- DETAILED REPORT TABLE WITH VIEW FILTER TABS                  --}}
{{-- ============================================================ --}}
<div class="card" style="margin-bottom:1.5rem;">

    {{-- Card Header --}}
    <div class="card-header">
        <h2 class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:7px;vertical-align:middle;color:var(--gold);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125h-1.5m2.625-1.5V5.625a1.125 1.125 0 00-1.125-1.125H4.5A1.125 1.125 0 003.375 5.625m18.75 0v1.5c0 .621-.504 1.125-1.125 1.125M9 8.625h6m-6 3h6m-6 3h4.5" />
            </svg>
            Detailed Report Table
        </h2>

        {{-- Search --}}
        <div style="margin-left:auto;position:relative;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="position:absolute;left:0.6rem;top:50%;transform:translateY(-50%);color:var(--text-muted);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 15.803 7.5 7.5 0 0016.803 15.803z" />
            </svg>
            <input type="text" id="drillSearch" class="form-control" placeholder="Search name, book, accession…" style="font-size:0.8rem;padding:0.35rem 0.6rem 0.35rem 2rem;width:220px;" />
        </div>
    </div>

    {{-- Filter Tabs --}}
    <div style="padding:0 1.25rem;border-bottom:1px solid var(--border,#e5e7eb);display:flex;gap:0;overflow-x:auto;">
        <button class="drill-tab active" data-view="books">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:5px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
            </svg>
            Total Books
            <span class="drill-tab-count" id="countBooks">{{ count($reportBooks ?? []) }}</span>
        </button>
        <button class="drill-tab" data-view="users">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:5px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            Total Users
            <span class="drill-tab-count" id="countUsers">{{ count($reportUsers ?? []) }}</span>
        </button>
        <button class="drill-tab" data-view="borrows">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:5px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.75V16.5L12 14.25 7.5 16.5V3.75m9 0H18A2.25 2.25 0 0120.25 6v12A2.25 2.25 0 0118 20.25H6A2.25 2.25 0 013.75 18V6A2.25 2.25 0 016 3.75h1.5m9 0h-9" />
            </svg>
            Total Borrows
            <span class="drill-tab-count" id="countBorrows">{{ count($reportBorrows ?? []) }}</span>
        </button>
        <button class="drill-tab" data-view="penalties">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:5px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            Total Penalties
            <span class="drill-tab-count" id="countPenalties">{{ count($reportPenalties ?? []) }}</span>
        </button>
        <button class="drill-tab" data-view="unpaid">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:5px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75" />
            </svg>
            Unpaid
            <span class="drill-tab-count drill-tab-count-red" id="countUnpaid">{{ count($reportUnpaid ?? []) }}</span>
        </button>
        <button class="drill-tab" data-view="collected">
            <svg xmlns="http://www.w3.org/2000/svg" width="13" height="13" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:5px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
            </svg>
            Collected
            <span class="drill-tab-count drill-tab-count-green" id="countCollected">{{ count($reportCollected ?? []) }}</span>
        </button>
    </div>

    {{-- Table Container --}}
    <div style="overflow-x:auto;">
        <table class="table" id="drillTable">
            <thead id="drillThead"></thead>
            <tbody id="drillTbody"></tbody>
        </table>

        {{-- Empty state --}}
        <div id="drillEmpty" style="display:none;padding:3rem 0;">
            <div class="empty-state">
                <div class="empty-state-icon">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div class="empty-state-title">No records found</div>
                <div class="empty-state-text">Try a different search or check back later.</div>
            </div>
        </div>
    </div>

    {{-- Footer count --}}
    <div style="padding:0.6rem 1.25rem;border-top:1px solid var(--border,#e5e7eb);font-size:0.78rem;color:var(--text-muted);display:flex;justify-content:space-between;align-items:center;">
        <span id="drillFooterText">Showing all records</span>
        <span id="drillFooterAmount" style="font-weight:600;font-size:0.85rem;"></span>
    </div>
</div>

<style>
.drill-tab {
    display: inline-flex;
    align-items: center;
    padding: 0.7rem 1rem;
    font-size: 0.8rem;
    font-weight: 500;
    color: var(--text-muted, #64748b);
    border: none;
    border-bottom: 2px solid transparent;
    background: none;
    cursor: pointer;
    white-space: nowrap;
    transition: color 0.15s, border-color 0.15s;
    gap: 0;
}
.drill-tab:hover { color: var(--text-head, #1a3a6b); }
.drill-tab.active {
    color: var(--navy, #1a3a6b);
    border-bottom-color: var(--navy, #1a3a6b);
    font-weight: 600;
}
.drill-tab-count {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin-left: 6px;
    min-width: 20px;
    padding: 1px 6px;
    border-radius: 999px;
    font-size: 0.7rem;
    font-weight: 600;
    background: #e2e8f0;
    color: #475569;
}
.drill-tab.active .drill-tab-count { background: #dbeafe; color: #1d4ed8; }
.drill-tab-count-red  { background: #fee2e2 !important; color: #b91c1c !important; }
.drill-tab-count-green { background: #dcfce7 !important; color: #15803d !important; }
</style>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    const navyBlue   = 'rgba(26,58,107,1)';
    const navyFill   = 'rgba(26,58,107,0.18)';
    const gold       = 'rgba(184,146,42,1)';
    const goldFill   = 'rgba(184,146,42,0.15)';
    const green      = 'rgba(37,138,90,1)';
    const greenFill  = 'rgba(37,138,90,0.15)';
    const red        = 'rgba(185,28,28,1)';
    const amber      = 'rgba(217,119,6,1)';
    const slate      = 'rgba(100,116,139,1)';
    const teal       = 'rgba(14,116,144,1)';

    const baseFont   = { family: "'Inter', sans-serif", size: 12 };
    const gridColor  = 'rgba(0,0,0,0.06)';

    Chart.defaults.font = baseFont;
    Chart.defaults.color = '#64748b';

    // Monthly Borrows
    new Chart(document.getElementById('borrowsChart'), {
        type: 'bar',
        data: {
            labels: months,
            datasets: [{
                label: 'Borrows',
                data: @json($monthlyBorrowsData),
                backgroundColor: navyFill,
                borderColor: navyBlue,
                borderWidth: 2,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: gridColor } },
                y: { grid: { color: gridColor }, beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // Monthly Attendance
    new Chart(document.getElementById('attendanceChart'), {
        type: 'line',
        data: {
            labels: months,
            datasets: [{
                label: 'Visits',
                data: @json($monthlyAttendanceData),
                backgroundColor: goldFill,
                borderColor: gold,
                borderWidth: 2,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: gold,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { color: gridColor } },
                y: { grid: { color: gridColor }, beginAtZero: true, ticks: { precision: 0 } }
            }
        }
    });

    // Books by Status
    new Chart(document.getElementById('booksStatusChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($booksByStatus)),
            datasets: [{
                data: @json(array_values($booksByStatus)),
                backgroundColor: [green, amber, navyBlue, red, slate],
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, boxWidth: 12 } }
            },
            cutout: '62%'
        }
    });

    // Penalty Breakdown
    new Chart(document.getElementById('penaltyChart'), {
        type: 'doughnut',
        data: {
            labels: @json(array_keys($penaltyBreakdown)),
            datasets: [{
                data: @json(array_values($penaltyBreakdown)),
                backgroundColor: [amber, red, slate],
                borderWidth: 2,
                borderColor: '#ffffff',
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'bottom', labels: { padding: 16, boxWidth: 12 } }
            },
            cutout: '62%'
        }
    });

    // ============================================================
    // DRILL-DOWN REPORT TABLE — tab-based view switcher
    // ============================================================

    /**
     * Pass these variables from your controller:
     *
     * $reportBooks     — collection of Book records
     *   Fields: title, accession_no, author, category, status, created_at
     *
     * $reportUsers     — collection of User records
     *   Fields: name, role, department, email, created_at
     *
     * $reportBorrows   — collection of Borrow records (with user & book eager-loaded)
     *   Fields: user.name, book.title, book.accession_no, borrowed_at, due_date, returned_at, status
     *
     * $reportPenalties — collection of Penalty records (with user & borrow.book eager-loaded)
     *   Fields: user.name, borrow.book.title, amount, status, created_at
     *
     * $reportUnpaid    — same as penalties but filtered where status = 'unpaid'
     *
     * $reportCollected — same as penalties but filtered where status = 'paid'
     */

    const drillDatasets = {
        books: @json($reportBooks ?? []),
        users: @json($reportUsers ?? []),
        borrows: @json($reportBorrows ?? []),
        penalties: @json($reportPenalties ?? []),
        unpaid: @json($reportUnpaid ?? []),
        collected: @json($reportCollected ?? []),
    };

    const fmt    = (n) => '₱' + Number(n).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    const fmtDate = (d) => d ? new Date(d).toLocaleDateString('en-PH', { year:'numeric', month:'short', day:'numeric' }) : '—';

    // Table schema per view: { headers[], rowFn(row) => html string, amountKey? }
    const drillSchemas = {
        books: {
            headers: ['Accession No.', 'Title', 'Author', 'Category', 'Status', 'Date Added'],
            searchFields: ['accession_no', 'title', 'author', 'category'],
            rowFn: (r) => `
                <td style="white-space:nowrap;font-size:0.8rem;color:var(--text-muted);">${r.accession_no ?? '—'}</td>
                <td style="font-weight:500;">${r.title ?? '—'}</td>
                <td>${r.author ?? '—'}</td>
                <td>${r.category ?? '—'}</td>
                <td><span class="badge ${statusBadge(r.status)}">${r.status ?? '—'}</span></td>
                <td style="white-space:nowrap;color:var(--text-muted);font-size:0.8rem;">${fmtDate(r.created_at)}</td>
            `,
        },
        users: {
            headers: ['Name', 'Role', 'Department', 'Email', 'Joined'],
            searchFields: ['name', 'role', 'department', 'email'],
            rowFn: (r) => `
                <td>
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <div class="avatar avatar-sm">${(r.name ?? '?')[0].toUpperCase()}</div>
                        <span style="font-weight:500;">${r.name ?? '—'}</span>
                    </div>
                </td>
                <td><span class="badge badge-blue">${r.role ? r.role.charAt(0).toUpperCase() + r.role.slice(1) : '—'}</span></td>
                <td>${r.department ?? '—'}</td>
                <td style="font-size:0.8rem;color:var(--text-muted);">${r.email ?? '—'}</td>
                <td style="white-space:nowrap;color:var(--text-muted);font-size:0.8rem;">${fmtDate(r.created_at)}</td>
            `,
        },
        borrows: {
            headers: ['Borrower', 'Book Title', 'Accession No.', 'Borrowed', 'Due Date', 'Returned', 'Status'],
            searchFields: ['user.name', 'book.title'],
            rowFn: (r) => {
                const accessions = r.book?.copies?.map(c => c.accession_no).join(', ') ?? '—';
                return `
                    <td>
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <div class="avatar avatar-sm">${((r.user?.name ?? '?')[0]).toUpperCase()}</div>
                            <span style="font-weight:500;">${r.user?.name ?? '—'}</span>
                        </div>
                    </td>
                    <td style="font-weight:500;">${r.book?.title ?? '—'}</td>
                    <td style="font-size:0.8rem;color:var(--text-muted);">${accessions}</td>
                    <td style="white-space:nowrap;font-size:0.8rem;color:var(--text-muted);">${fmtDate(r.borrowed_at ?? r.created_at)}</td>
                    <td style="white-space:nowrap;font-size:0.8rem;color:var(--text-muted);">${fmtDate(r.due_date)}</td>
                    <td style="white-space:nowrap;font-size:0.8rem;color:var(--text-muted);">${r.returned_at ? fmtDate(r.returned_at) : '<span style="color:#d97706;">Pending</span>'}</td>
                    <td><span class="badge ${statusBadge(r.status)}">${r.status ?? '—'}</span></td>
                `;
            },
        },
        penalties: {
            headers: ['User', 'Book', 'Amount', 'Status', 'Date'],
            searchFields: ['user.name', 'physical_borrow.book.title'],
            amountKey: 'amount',
            rowFn: (r) => `
                <td>
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <div class="avatar avatar-sm">${((r.user?.name ?? '?')[0]).toUpperCase()}</div>
                        <span style="font-weight:500;">${r.user?.name ?? '—'}</span>
                    </div>
                </td>
                <td style="font-weight:500;">${r.physical_borrow?.book?.title ?? '—'}</td>
                <td style="text-align:right;font-weight:600;">${fmt(r.amount ?? 0)}</td>
                <td><span class="badge ${r.is_paid ? 'badge-success' : 'badge-danger'}">${r.is_paid ? 'Paid' : 'Unpaid'}</span></td>
                <td style="white-space:nowrap;font-size:0.8rem;color:var(--text-muted);">${fmtDate(r.created_at)}</td>
            `,
        },
        unpaid: {
            headers: ['User', 'Book', 'Amount Owed', 'Due Date', 'Days Overdue'],
            searchFields: ['user.name', 'physical_borrow.book.title'],
            amountKey: 'amount',
            rowFn: (r) => {
                const due  = r.physical_borrow?.due_date ?? r.due_date;
                const days = due ? Math.max(0, Math.floor((Date.now() - new Date(due)) / 86400000)) : null;
                return `
                    <td>
                        <div style="display:flex;align-items:center;gap:0.5rem;">
                            <div class="avatar avatar-sm">${((r.user?.name ?? r.user_name ?? '?')[0]).toUpperCase()}</div>
                            <div>
                                <div style="font-weight:500;">${r.user?.name ?? r.user_name ?? '—'}</div>
                                <div style="font-size:0.75rem;color:var(--text-muted);">${r.user?.department ?? r.department ?? ''}</div>
                            </div>
                        </div>
                    </td>
                    <td style="font-weight:500;">${r.physical_borrow?.book?.title ?? '—'}</td>
                    <td style="text-align:right;font-weight:700;color:#b91c1c;">${fmt(r.amount ?? 0)}</td>
                    <td style="white-space:nowrap;font-size:0.8rem;color:var(--text-muted);">${fmtDate(due)}</td>
                    <td style="text-align:center;">
                        ${days !== null ? `<span class="badge ${days > 30 ? 'badge-danger' : days > 7 ? 'badge-warning' : 'badge-blue'}">${days}d</span>` : '—'}
                    </td>
                `;
            },
        },
        collected: {
            headers: ['User', 'Book', 'Amount Paid', 'Payment Date'],
            searchFields: ['user.name', 'physical_borrow.book.title'],
            amountKey: 'amount',
            rowFn: (r) => `
                <td>
                    <div style="display:flex;align-items:center;gap:0.5rem;">
                        <div class="avatar avatar-sm">${((r.user?.name ?? r.user_name ?? '?')[0]).toUpperCase()}</div>
                        <div>
                            <div style="font-weight:500;">${r.user?.name ?? r.user_name ?? '—'}</div>
                            <div style="font-size:0.75rem;color:var(--text-muted);">${r.user?.department ?? r.department ?? ''}</div>
                        </div>
                    </div>
                </td>
                <td style="font-weight:500;">${r.physical_borrow?.book?.title ?? '—'}</td>
                <td style="text-align:right;font-weight:700;color:#15803d;">${fmt(r.amount ?? 0)}</td>
                <td style="white-space:nowrap;font-size:0.8rem;color:var(--text-muted);">${fmtDate(r.updated_at ?? r.created_at)}</td>
            `,
        },
    };

    function statusBadge(status) {
        const map = {
            available: 'badge-success', returned: 'badge-success', paid: 'badge-success',
            borrowed: 'badge-blue', active: 'badge-blue',
            overdue: 'badge-danger', unpaid: 'badge-danger', lost: 'badge-danger',
            reserved: 'badge-warning', pending: 'badge-warning',
        };
        return map[(status ?? '').toLowerCase()] ?? 'badge-blue';
    }

    function getNestedVal(obj, path) {
        return path.split('.').reduce((o, k) => (o && o[k] != null ? o[k] : ''), obj);
    }

    let currentView   = 'books';
    let currentSearch = '';

    function renderDrillTable() {
        const schema  = drillSchemas[currentView];
        const allData = drillDatasets[currentView] ?? [];
        const search  = currentSearch.toLowerCase().trim();

        const data = search
            ? allData.filter(r => schema.searchFields.some(f => String(getNestedVal(r, f)).toLowerCase().includes(search)))
            : allData;

        // Header
        document.getElementById('drillThead').innerHTML =
            '<tr>' + schema.headers.map(h => `<th style="white-space:nowrap;">${h}</th>`).join('') + '</tr>';

        const tbody    = document.getElementById('drillTbody');
        const empty    = document.getElementById('drillEmpty');
        const footer   = document.getElementById('drillFooterText');
        const footAmt  = document.getElementById('drillFooterAmount');

        tbody.innerHTML = '';

        if (!data.length) {
            empty.style.display = 'block';
            footer.textContent  = 'No records found';
            footAmt.textContent = '';
            return;
        }

        empty.style.display = 'none';
        let totalAmount = 0;

        data.forEach(r => {
            if (schema.amountKey) totalAmount += Number(r[schema.amountKey] ?? 0);
            const tr = document.createElement('tr');
            tr.innerHTML = schema.rowFn(r);
            tbody.appendChild(tr);
        });

        footer.textContent = `Showing ${data.length} of ${allData.length} record${allData.length !== 1 ? 's' : ''}`;
        footAmt.textContent = schema.amountKey ? `Total: ${fmt(totalAmount)}` : '';
        footAmt.style.color = currentView === 'unpaid' ? '#b91c1c' : currentView === 'collected' ? '#15803d' : '';
    }

    // Tab click
    document.querySelectorAll('.drill-tab').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.drill-tab').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            currentView   = btn.dataset.view;
            currentSearch = '';
            document.getElementById('drillSearch').value = '';
            renderDrillTable();
        });
    });

    // Search
    document.getElementById('drillSearch').addEventListener('input', e => {
        currentSearch = e.target.value;
        renderDrillTable();
    });

    // Initial render
    renderDrillTable();
</script>

@endsection