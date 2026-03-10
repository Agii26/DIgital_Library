@extends('layouts.app')

@section('page-title', 'Password Setup Links')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Password Setup Links</h1>
        <p class="page-subtitle">Users who have not yet set their password</p>
    </div>
    <div class="page-actions">
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Users
        </a>
    </div>
</div>

@if($users->isEmpty())
<div class="card">
    <div class="empty-state">
        <div class="empty-state-icon" style="background:var(--success-pale);color:var(--success);">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <p class="empty-state-title">All passwords have been set</p>
        <p class="empty-state-text">No pending password setup links at this time.</p>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back to Users</a>
    </div>
</div>

@else
<div class="card">
    @foreach($users as $user)
    <div style="padding:1.25rem 1.5rem;border-bottom:1px solid var(--border);">

        {{-- User Info Row --}}
        <div style="display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:0.875rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <div class="avatar avatar-md">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                <div>
                    <p style="font-size:0.875rem;font-weight:600;color:var(--text-head);">{{ $user->name }}</p>
                    <p style="font-size:0.75rem;color:var(--text-muted);margin-top:0.1rem;">
                        {{ $user->email }}
                        <span style="margin:0 0.3rem;color:var(--border-strong);">&middot;</span>
                        <span class="badge badge-muted" style="font-size:0.62rem;">{{ ucfirst($user->role) }}</span>
                    </p>
                    @if($user->set_password_token_expires_at)
                    <p style="font-size:0.72rem;color:var(--warning);margin-top:0.25rem;">
                        Expires: {{ $user->set_password_token_expires_at->format('M d, Y h:i A') }}
                    </p>
                    @else
                    <p style="font-size:0.72rem;color:var(--text-dim);margin-top:0.25rem;">No active token</p>
                    @endif
                </div>
            </div>
            <form method="POST" action="{{ route('admin.users.resend', $user) }}">
                @csrf
                <button type="submit" class="btn btn-sm btn-secondary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    Regenerate
                </button>
            </form>
        </div>

        {{-- Link Row --}}
        @if($user->set_password_token)
        <div style="display:flex;align-items:center;gap:0.75rem;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius);padding:0.625rem 0.875rem;">
            <code id="link-{{ $user->id }}" style="font-size:0.72rem;color:var(--text-muted);word-break:break-all;flex:1;font-family:monospace;">{{ url('/set-password') . '?token=' . $user->set_password_token . '&email=' . urlencode($user->email) }}</code>
            <button onclick="copyLink('{{ $user->id }}')" class="btn btn-sm btn-primary" style="flex-shrink:0;">
                Copy
            </button>
        </div>
        @else
        <div style="background:var(--warning-pale);border:1px solid #f0dfa0;border-radius:var(--radius);padding:0.625rem 0.875rem;">
            <p style="font-size:0.775rem;color:var(--warning);">No token generated yet. Click Regenerate to create a link.</p>
        </div>
        @endif

    </div>
    @endforeach
</div>
@endif

<script>
function copyLink(userId) {
    const link = document.getElementById('link-' + userId).innerText.trim();
    navigator.clipboard.writeText(link).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>

@endsection