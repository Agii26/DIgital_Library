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
<div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.5rem;">

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
</script>

@endsection