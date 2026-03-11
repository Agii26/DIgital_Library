@extends('layouts.app')

@section('page-title', 'My Profile')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">My Profile</h1>
        <p class="page-subtitle">Manage your account information and password</p>
    </div>
</div>

<div style="max-width:680px;">

    {{-- Profile Information --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:8px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                Profile Information
            </h2>
        </div>
        <div class="card-body">

            @if($errors->hasAny(['name', 'department']))
                <div class="alert alert-danger" style="margin-bottom:1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">

                    <div class="form-group">
                        <label class="form-label">Full Name <span class="required">*</span></label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            class="form-control"
                            required
                        />
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input
                            type="email"
                            value="{{ $user->email }}"
                            class="form-control"
                            disabled
                            style="opacity:0.6;cursor:not-allowed;"
                        />
                        <span class="form-hint">Email cannot be changed</span>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Role</label>
                        <input
                            type="text"
                            value="{{ ucfirst($user->role) }}"
                            class="form-control"
                            disabled
                            style="opacity:0.6;cursor:not-allowed;"
                        />
                    </div>

                    <div class="form-group">
                        <label class="form-label">ID No.</label>
                        <input
                            type="text"
                            value="{{ $user->student_id ?? '&mdash;' }}"
                            class="form-control"
                            disabled
                            style="opacity:0.6;cursor:not-allowed;"
                        />
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Department</label>
                        <input
                            type="text"
                            name="department"
                            value="{{ old('department', $user->department) }}"
                            class="form-control"
                            placeholder="e.g. College of Engineering"
                        />
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">RFID Tag</label>
                        <input
                            type="text"
                            value="{{ $user->rfid_tag ?? '&mdash;' }}"
                            class="form-control"
                            disabled
                            style="opacity:0.6;cursor:not-allowed;"
                        />
                        <span class="form-hint">Assigned by the administrator</span>
                    </div>

                </div>

                <div style="margin-top:1.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:6px;vertical-align:middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Update Profile
                    </button>
                </div>
            </form>

        </div>
    </div>

    {{-- Change Password --}}
    <div class="card">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:8px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
                Change Password
            </h2>
        </div>
        <div class="card-body">

            @if($errors->hasAny(['current_password', 'password']))
                <div class="alert alert-danger" style="margin-bottom:1.25rem;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('profile.change-password') }}">
                @csrf
                <div style="display:grid;grid-template-columns:1fr;gap:1.25rem;">

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Current Password <span class="required">*</span></label>
                        <input
                            type="password"
                            name="current_password"
                            class="form-control"
                            required
                        />
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">New Password <span class="required">*</span></label>
                        <input
                            type="password"
                            name="password"
                            class="form-control"
                            placeholder="Minimum 8 characters"
                            required
                        />
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Confirm New Password <span class="required">*</span></label>
                        <input
                            type="password"
                            name="password_confirmation"
                            class="form-control"
                            required
                        />
                    </div>

                </div>

                <div style="margin-top:1.5rem;">
                    <button type="submit" class="btn btn-danger">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:6px;vertical-align:middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                        </svg>
                        Change Password
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

@endsection