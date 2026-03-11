@extends('layouts.app')

@section('page-title', 'Messages')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">Messages</h1>
        <p class="page-subtitle">Internal correspondence between library staff and users</p>
    </div>
</div>

<div class="ra-messages-layout" style="display:flex;gap:1.5rem;height:72vh;">

    {{-- Conversations Sidebar --}}
    <div class="card ra-sidebar-panel" style="width:300px;flex-shrink:0;display:flex;flex-direction:column;padding:0;overflow:hidden;">

        {{-- Panel header --}}
        <div style="padding:1rem 1.25rem;border-bottom:1px solid var(--border);flex-shrink:0;display:flex;align-items:center;justify-content:space-between;">
            <div style="font-family:var(--font-serif);font-size:0.95rem;font-weight:600;color:var(--text-head);display:flex;align-items:center;gap:0.5rem;">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                </svg>
                Conversations
            </div>
            {{-- Mobile close button --}}
            <button class="ra-sidebar-close btn btn-secondary btn-sm" style="display:none;padding:4px 8px;" aria-label="Close sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- New conversation select --}}
        <div style="padding:0.85rem 1rem;border-bottom:1px solid var(--border);flex-shrink:0;">
            <div class="form-group" style="margin-bottom:0;">
                <select id="new-conversation" class="form-control" style="font-size:0.82rem;">
                    <option value="">New conversation&hellip;</option>
                    @foreach($users as $u)
                        <option value="{{ route('messages.show', $u) }}" {{ $u->id === $user->id ? 'selected' : '' }}>
                            {{ $u->name }} &mdash; {{ ucfirst($u->role) }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Conversation list --}}
        <div style="flex:1;overflow-y:auto;">
            @forelse($conversations as $userId => $message)
                @php
                    $otherUser = $message->sender_id === Auth::id() ? $message->receiver : $message->sender;
                    $isActive  = $otherUser->id === $user->id;
                @endphp
                <a href="{{ route('messages.show', $otherUser) }}"
                   class="ra-convo-item{{ $isActive ? ' is-active' : '' }}"
                   style="display:flex;align-items:center;gap:0.75rem;padding:0.85rem 1rem;border-bottom:1px solid var(--border);text-decoration:none;background:{{ $isActive ? 'var(--blue-ultra-pale,#eef3fb)' : 'transparent' }};transition:background 0.15s;"
                   onmouseover="this.style.background='var(--surface-2)'"
                   onmouseout="this.style.background='{{ $isActive ? 'var(--blue-ultra-pale,#eef3fb)' : 'transparent' }}'">
                    <div class="avatar avatar-sm" style="flex-shrink:0;{{ $isActive ? 'background:var(--blue);' : '' }}">{{ strtoupper(substr($otherUser->name, 0, 1)) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:{{ $isActive ? '600' : '500' }};font-size:0.82rem;color:var(--text-head);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $otherUser->name }}</div>
                        <div style="font-size:0.72rem;color:var(--text-muted);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;margin-top:2px;">{{ $message->body }}</div>
                    </div>
                    <div style="font-size:0.68rem;color:var(--text-dim);flex-shrink:0;white-space:nowrap;">{{ $message->created_at->diffForHumans() }}</div>
                </a>
            @empty
                <div class="empty-state" style="padding:2.5rem 1rem;">
                    <div class="empty-state-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                        </svg>
                    </div>
                    <div class="empty-state-title">No conversations</div>
                    <div class="empty-state-text">Start one using the dropdown above</div>
                </div>
            @endforelse
        </div>
    </div>

    {{-- Chat Window --}}
    <div class="card ra-main-panel" style="flex:1;display:flex;flex-direction:column;padding:0;overflow:hidden;min-width:0;">

        {{-- Chat header --}}
        <div style="padding:1rem 1.5rem;border-bottom:1px solid var(--border);display:flex;align-items:center;gap:0.85rem;flex-shrink:0;">
            {{-- Mobile back button --}}
            <button class="ra-show-sidebar btn btn-secondary btn-sm" style="display:none;flex-shrink:0;padding:5px 8px;" aria-label="Back to conversations">
                <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
                </svg>
            </button>

            <div class="avatar avatar-md" style="flex-shrink:0;">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
            <div style="min-width:0;">
                <div style="font-family:var(--font-serif);font-weight:600;font-size:1rem;color:var(--text-head);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $user->name }}</div>
                <div style="font-size:0.75rem;color:var(--text-muted);margin-top:2px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ ucfirst($user->role) }}
                    @if($user->department)
                        &bull; {{ $user->department }}
                    @endif
                </div>
            </div>
        </div>

        {{-- Message thread --}}
        <div id="messages-container" style="flex:1;overflow-y:auto;padding:1.25rem 1.5rem;display:flex;flex-direction:column;gap:0.85rem;">
            @forelse($messages as $message)
                @php $isMine = $message->sender_id === Auth::id(); @endphp
                <div style="display:flex;justify-content:{{ $isMine ? 'flex-end' : 'flex-start' }};">
                    <div class="ra-bubble-wrap" style="max-width:60%;">
                        <div style="
                            padding:0.6rem 1rem;
                            border-radius:{{ $isMine ? '14px 14px 2px 14px' : '14px 14px 14px 2px' }};
                            font-size:0.875rem;
                            line-height:1.5;
                            background:{{ $isMine ? 'var(--blue)' : 'var(--surface-2)' }};
                            color:{{ $isMine ? '#ffffff' : 'var(--text-head)' }};
                            word-break:break-word;
                        ">{{ $message->body }}</div>
                        <div style="font-size:0.68rem;color:var(--text-dim);margin-top:4px;text-align:{{ $isMine ? 'right' : 'left' }};">
                            {{ $message->created_at->format('h:i A') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="empty-state" style="margin:auto;">
                    <div class="empty-state-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 12a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375M21 12c0 4.556-4.03 8.25-9 8.25a9.764 9.764 0 01-2.555-.337A5.972 5.972 0 015.41 20.97a5.969 5.969 0 01-.474-.065 4.48 4.48 0 00.978-2.025c.09-.457-.133-.901-.467-1.226C3.93 16.178 3 14.189 3 12c0-4.556 4.03-8.25 9-8.25s9 3.694 9 8.25z" />
                        </svg>
                    </div>
                    <div class="empty-state-title">No messages yet</div>
                    <div class="empty-state-text">Send a message below to start the conversation</div>
                </div>
            @endforelse
        </div>

        {{-- Message input --}}
        <div style="padding:0.9rem 1.25rem;border-top:1px solid var(--border);flex-shrink:0;">
            <form method="POST" action="{{ route('messages.store') }}">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $user->id }}" />
                <div style="display:flex;gap:0.65rem;align-items:center;">
                    <input
                        type="text"
                        name="body"
                        class="form-control"
                        placeholder="Type a message&hellip;"
                        required
                        autofocus
                        style="flex:1;margin-bottom:0;"
                    />
                    <button type="submit" class="btn btn-primary ra-send-btn" style="flex-shrink:0;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="vertical-align:middle;" class="ra-send-icon-label">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                        </svg>
                        <span class="ra-send-label" style="margin-left:5px;">Send</span>
                    </button>
                </div>
            </form>
        </div>

    </div>

</div>

<style>
    /* ── Tablet: narrow sidebar ── */
    @media (max-width: 900px) {
        .ra-sidebar-panel {
            width: 240px !important;
        }
        .ra-bubble-wrap {
            max-width: 75% !important;
        }
    }

    /* ── Mobile: full-width single-panel mode ── */
    @media (max-width: 640px) {
        .ra-messages-layout {
            position: relative;
            height: calc(100svh - 160px) !important;
            overflow: hidden;
        }

        /* Sidebar slides in as overlay */
        .ra-sidebar-panel {
            position: absolute !important;
            inset: 0;
            width: 100% !important;
            z-index: 10;
            transform: translateX(-100%);
            transition: transform 0.25s ease;
            border-radius: var(--radius-lg);
        }
        .ra-sidebar-panel.is-open {
            transform: translateX(0);
        }

        /* Main panel fills container */
        .ra-main-panel {
            width: 100%;
            min-width: 0;
        }

        /* Show mobile controls */
        .ra-sidebar-close,
        .ra-show-sidebar {
            display: inline-flex !important;
        }

        /* Bubbles can go wider on small screens */
        .ra-bubble-wrap {
            max-width: 82% !important;
        }

        /* Icon-only Send button on very small screens */
        .ra-send-label {
            display: none;
        }
        .ra-send-btn {
            padding: 0.45rem 0.65rem !important;
        }
    }
</style>

<script>
    // Auto-scroll to latest message
    const container = document.getElementById('messages-container');
    if (container) container.scrollTop = container.scrollHeight;

    // Redirect on new conversation select
    document.getElementById('new-conversation').addEventListener('change', function () {
        if (this.value) window.location.href = this.value;
    });

    // Mobile sidebar toggle
    const sidebar  = document.querySelector('.ra-sidebar-panel');
    const showBtn  = document.querySelector('.ra-show-sidebar');
    const closeBtn = document.querySelector('.ra-sidebar-close');

    function openSidebar()  { sidebar.classList.add('is-open'); }
    function closeSidebar() { sidebar.classList.remove('is-open'); }

    if (showBtn)  showBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);

    // On mobile, since a conversation IS active here, show the chat panel by default
    // (sidebar stays hidden unless user taps the back button)
</script>

@endsection