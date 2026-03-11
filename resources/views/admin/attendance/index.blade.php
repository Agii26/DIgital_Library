@extends('layouts.app')

@section('page-title', 'RFID Attendance')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">RFID Attendance</h1>
        <p class="page-subtitle">Monitor and record library entry &amp; exit via RFID scan</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.attendance.kiosk') }}" class="btn btn-gold">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:6px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3" />
            </svg>
            Kiosk Mode
        </a>
    </div>
</div>

{{-- RFID Scanner --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-header">
        <h2 class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:8px;vertical-align:middle;color:var(--gold);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M8.288 15.038a5.25 5.25 0 017.424 0M5.106 11.856c3.807-3.808 9.98-3.808 13.788 0M1.924 8.674c5.565-5.565 14.587-5.565 20.152 0M12.53 18.22l-.53.53-.53-.53a.75.75 0 011.06 0z" />
            </svg>
            RFID Scanner
        </h2>
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.attendance.scan') }}">
            @csrf
            <div style="display:flex;gap:1rem;align-items:flex-end;">
                <div class="form-group" style="flex:1;margin-bottom:0;">
                    <label class="form-label">Scan RFID Tag</label>
                    <input
                        type="text"
                        name="rfid_tag"
                        autofocus
                        class="form-control"
                        placeholder="Scan or enter RFID tag..."
                        required
                    />
                </div>
                <button type="submit" class="btn btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:6px;vertical-align:middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75V16.5zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                    </svg>
                    Record Attendance
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Analytics Stat Cards --}}
<div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1.25rem;margin-bottom:1.5rem;">

    <div class="stat-card">
        <div class="stat-card-accent blue"></div>
        <div class="stat-icon blue">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
        </div>
        <div class="stat-label">Today's Visits</div>
        <div class="stat-value">{{ $todayVisits }}</div>
        <div class="stat-sub">Scans recorded today</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent green"></div>
        <div class="stat-icon green">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
            </svg>
        </div>
        <div class="stat-label">Currently Inside</div>
        <div class="stat-value">{{ $currentlyIn }}</div>
        <div class="stat-sub">Active library users</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent gold"></div>
        <div class="stat-icon gold">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5" />
            </svg>
        </div>
        <div class="stat-label">This Week</div>
        <div class="stat-value">{{ $weekVisits }}</div>
        <div class="stat-sub">Weekly total visits</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-accent red"></div>
        <div class="stat-icon red">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
            </svg>
        </div>
        <div class="stat-label">This Month</div>
        <div class="stat-value">{{ $monthVisits }}</div>
        <div class="stat-sub">Monthly total visits</div>
    </div>

</div>

{{-- Filters --}}
<form method="GET" action="{{ route('admin.attendance.index') }}">
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-body" style="display:flex;gap:0.75rem;align-items:flex-end;flex-wrap:wrap;">
            <div class="form-group" style="flex:2;min-width:200px;margin-bottom:0;">
                <label class="form-label">Search</label>
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    class="form-control"
                    placeholder="Search by name or ID..."
                />
            </div>
            <div class="form-group" style="flex:1;min-width:140px;margin-bottom:0;">
                <label class="form-label">Date</label>
                <input
                    type="date"
                    name="date"
                    value="{{ request('date') }}"
                    class="form-control"
                />
            </div>
            <div class="form-group" style="flex:1;min-width:140px;margin-bottom:0;">
                <label class="form-label">Role</label>
                <select name="role" class="form-control">
                    <option value="">All Roles</option>
                    <option value="faculty" {{ request('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                </select>
            </div>
            <div style="display:flex;gap:0.5rem;padding-bottom:1px;">
                <button type="submit" class="btn btn-primary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                    </svg>
                    Filter
                </button>
                <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                    </svg>
                    Reset
                </a>
                <a href="{{ route('admin.attendance.export', request()->query()) }}" class="btn btn-success btn-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:5px;vertical-align:middle;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export CSV
                </a>
            </div>
        </div>
    </div>
</form>

{{-- Logs Table --}}
<div class="card">
    <div class="card-header">
        <h2 class="card-title">
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:8px;vertical-align:middle;color:var(--gold);">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z" />
            </svg>
            Attendance Logs
        </h2>
        <span class="badge badge-blue">{{ $logs->total() }} records</span>
    </div>
    <div class="table-wrapper">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>ID No.</th>
                    <th>Role</th>
                    <th>Department</th>
                    <th>Type</th>
                    <th>Time</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.6rem;">
                            <div class="avatar avatar-sm">{{ strtoupper(substr($log->user->name, 0, 1)) }}</div>
                            <span style="font-weight:500;color:var(--text-head);">{{ $log->user->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);">{{ $log->user->student_id ?? '&mdash;' }}</td>
                    <td>
                        @if($log->user->role === 'faculty')
                            <span class="badge badge-gold">Faculty</span>
                        @else
                            <span class="badge badge-blue">Student</span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);">{{ $log->user->department ?? '&mdash;' }}</td>
                    <td>
                        @if($log->type === 'time_in')
                            <span class="badge badge-success">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:3px;vertical-align:middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 9V5.25A2.25 2.25 0 0110.5 3h6a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0116.5 21h-6a2.25 2.25 0 01-2.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H2.25" />
                                </svg>
                                Time In
                            </span>
                        @else
                            <span class="badge badge-danger">
                                <svg xmlns="http://www.w3.org/2000/svg" width="11" height="11" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" style="margin-right:3px;vertical-align:middle;">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                Time Out
                            </span>
                        @endif
                    </td>
                    <td style="color:var(--text-muted);">{{ $log->scanned_at->format('M d, Y h:i A') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                                </svg>
                            </div>
                            <div class="empty-state-title">No Attendance Logs Found</div>
                            <div class="empty-state-text">No records match your current filters. Try adjusting the search or date criteria.</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
    <div class="card-footer">
        {{ $logs->links() }}
    </div>
    @endif
</div>

@endsection