@extends('layouts.app')

@section('page-title', 'Edit User')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Edit User</h1>
        <p class="page-subtitle">Update account details for {{ $user->name }}</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Users
        </a>
    </div>
</div>

<div style="max-width:680px;">
    <div class="card">
        <div class="card-header">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div class="avatar avatar-md">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div>
                    <h3 class="card-title">{{ $user->name }}</h3>
                    <p style="font-size:0.775rem;color:var(--text-muted);margin-top:0.1rem;">{{ $user->email }}</p>
                </div>
            </div>
            <span class="badge {{ $user->is_active ? 'badge-success' : 'badge-danger' }}">
                {{ $user->is_active ? 'Active' : 'Inactive' }}
            </span>
        </div>
        <div class="card-body">

            @if($errors->any())
            <div class="alert alert-danger">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px;height:16px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('admin.users.update', $user) }}">
                @csrf
                @method('PUT')

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.25rem;">

                    <div class="form-group">
                        <label class="form-label" for="name">Full Name <span class="required">*</span></label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                            class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" value="{{ $user->email }}"
                            class="form-control"
                            style="background:var(--surface-2);color:var(--text-muted);cursor:not-allowed;"
                            disabled>
                        <p class="form-hint">Email cannot be changed.</p>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="role">Role <span class="required">*</span></label>
                        <select id="role" name="role" class="form-control" required>
                            <option value="faculty" {{ $user->role === 'faculty' ? 'selected' : '' }}>Faculty</option>
                            <option value="student" {{ $user->role === 'student' ? 'selected' : '' }}>Student</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="student_id">Student / Faculty ID</label>
                        <input type="text" id="student_id" name="student_id"
                            value="{{ old('student_id', $user->student_id) }}"
                            class="form-control" placeholder="e.g. 2024-00001">
                    </div>

                    <div class="form-group">
    <label class="form-label" for="department">Department</label>
    <select id="department" name="department" class="form-control">
        <option value="">Select grade level...</option>
        <optgroup label="Elementary">
            <option value="Grade 1" {{ old('department', $user->department) === 'Grade 1' ? 'selected' : '' }}>Grade 1</option>
            <option value="Grade 2" {{ old('department', $user->department) === 'Grade 2' ? 'selected' : '' }}>Grade 2</option>
            <option value="Grade 3" {{ old('department', $user->department) === 'Grade 3' ? 'selected' : '' }}>Grade 3</option>
            <option value="Grade 4" {{ old('department', $user->department) === 'Grade 4' ? 'selected' : '' }}>Grade 4</option>
            <option value="Grade 5" {{ old('department', $user->department) === 'Grade 5' ? 'selected' : '' }}>Grade 5</option>
            <option value="Grade 6" {{ old('department', $user->department) === 'Grade 6' ? 'selected' : '' }}>Grade 6</option>
        </optgroup>
        <optgroup label="Junior High School">
            <option value="Grade 7" {{ old('department', $user->department) === 'Grade 7' ? 'selected' : '' }}>Grade 7</option>
            <option value="Grade 8" {{ old('department', $user->department) === 'Grade 8' ? 'selected' : '' }}>Grade 8</option>
            <option value="Grade 9" {{ old('department', $user->department) === 'Grade 9' ? 'selected' : '' }}>Grade 9</option>
            <option value="Grade 10" {{ old('department', $user->department) === 'Grade 10' ? 'selected' : '' }}>Grade 10</option>
        </optgroup>
        <optgroup label="Senior High School">
            <option value="Grade 11" {{ old('department', $user->department) === 'Grade 11' ? 'selected' : '' }}>Grade 11</option>
            <option value="Grade 12" {{ old('department', $user->department) === 'Grade 12' ? 'selected' : '' }}>Grade 12</option>
        </optgroup>
    </select>
</div>

                    <div class="form-group">
                        <label class="form-label" for="rfid_tag">RFID Tag</label>
                        <input type="text" id="rfid_tag" name="rfid_tag"
                            value="{{ old('rfid_tag', $user->rfid_tag) }}"
                            class="form-control" placeholder="e.g. A1B2C3D4">
                        <p class="form-hint">Leave blank if no RFID card assigned.</p>
                    </div>

                </div>

                <div style="margin-top:0.5rem;padding-top:1.25rem;border-top:1px solid var(--border);display:flex;gap:0.75rem;">
                    <button type="submit" class="btn btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Changes
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection