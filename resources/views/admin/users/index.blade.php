@extends('layouts.app')

@section('page-title', 'User Management')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">User Management</h1>
        <p class="page-subtitle">Manage library patrons, faculty, and staff accounts</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.users.template') }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        Download Template
    </a>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Add User
        </a>
    </div>
</div>

@if($errors->has('delete'))
<div class="alert alert-danger">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
    <span>{{ $errors->first('delete') }}</span>
</div>
@endif

{{-- Search & Filter --}}
<div class="card" style="margin-bottom:1.5rem;">
    <div class="card-body" style="padding:1.125rem 1.5rem;display:flex;gap:1rem;align-items:center;flex-wrap:wrap;justify-content:space-between;">

        {{-- Search Form --}}
        <form method="GET" action="{{ route('admin.users.index') }}" style="display:flex;gap:0.75rem;align-items:center;flex-wrap:wrap;flex:1;">
            <div style="position:relative;flex:1;min-width:220px;">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="position:absolute;left:0.75rem;top:50%;transform:translateY(-50%);width:14px;height:14px;color:var(--text-dim);pointer-events:none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search name, email, ID, department..."
                    class="form-control" style="padding-left:2.25rem;">
            </div>
            <select name="role" class="form-control" style="width:auto;min-width:140px;">
                <option value="">All Roles</option>
                <option value="faculty" {{ request('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
                <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Reset</a>
        </form>

        {{-- Import Form --}}
        <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" style="display:flex;gap:0.5rem;align-items:center;">
            @csrf
            <input type="file" name="file" accept=".xlsx,.csv,.xls" class="form-control" style="width:auto;font-size:0.78rem;padding:0.4rem 0.6rem;" required />
            <button type="submit" class="btn btn-success">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Import
            </button>
        </form>

    </div>
</div>

{{-- Users Table --}}
<div class="card">
    <div class="table-wrapper" style="border:none;border-radius:var(--radius-lg);box-shadow:none;">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Student ID</th>
                    <th>Department</th>
                    <th>RFID</th>
                    <th>Password</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                <tr>
                    <td>
                        <div style="display:flex;align-items:center;gap:0.625rem;">
                            <div class="avatar avatar-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                            <span style="font-weight:500;color:var(--text-head);">{{ $user->name }}</span>
                        </div>
                    </td>
                    <td style="color:var(--text-muted);">{{ $user->email }}</td>
                    <td>
                        <span class="badge {{ $user->role === 'faculty' ? 'badge-blue' : 'badge-muted' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td style="color:var(--text-muted);">{{ $user->student_id ?? '—' }}</td>
                    <td style="color:var(--text-muted);">{{ $user->department ?? '—' }}</td>
                    <td style="color:var(--text-muted);font-family:monospace;font-size:0.8rem;">{{ $user->rfid_tag ?? '—' }}</td>
                    <td>
                        @if($user->password_set)
                            <span class="badge badge-success">Set</span>
                        @else
                            <span class="badge badge-warning">Pending</span>
                        @endif
                    </td>
                    <td>
                        @if($user->is_active)
                            <span class="badge badge-success">Active</span>
                        @else
                            <span class="badge badge-danger">Inactive</span>
                        @endif
                    </td>
                    <td>
                        <div style="display:flex;gap:0.375rem;align-items:center;flex-wrap:wrap;">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-secondary">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $user->is_active ? 'btn-danger' : 'btn-success' }}">
                                    {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>
                            @if(!$user->password_set)
                            <form method="POST" action="{{ route('admin.users.resend', $user) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-outline">
                                    Resend Link
                                </button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to delete {{ addslashes($user->name) }}?')"
                                    class="btn btn-sm btn-danger">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            </div>
                            <p class="empty-state-title">No users found</p>
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
            {{ $users->links() }}
        </div>
    </div>
    @endif
</div>

@endsection