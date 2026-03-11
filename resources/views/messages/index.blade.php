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
            {{-- Mobile close button (shown only when panel is open on mobile) --}}
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
                        <option value="{{ route('messages.show', $u) }}">
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
                @endphp
                <a href="{{ route('messages.show', $otherUser) }}"
                   class="ra-convo-item"
                   style="display:flex;align-items:center;gap:0.75rem;padding:0.85rem 1rem;border-bottom:1px solid var(--border);text-decoration:none;transition:background 0.15s;"
                   onmouseover="this.style.background='var(--surface-2)'"
                   onmouseout="this.style.background='transparent'">
                    <div class="avatar avatar-sm" style="flex-shrink:0;">{{ strtoupper(substr($otherUser->name, 0, 1)) }}</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-weight:500;font-size:0.82rem;color:var(--text-head);white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $otherUser->name }}</div>
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

    {{-- Empty / placeholder panel --}}
    <div class="card ra-main-panel" style="flex:1;display:flex;align-items:center;justify-content:center;position:relative;">

        {{-- Mobile: "back to conversations" bar shown inside main panel on mobile --}}
        <button class="ra-show-sidebar btn btn-secondary btn-sm" style="display:none;position:absolute;top:1rem;left:1rem;align-items:center;gap:6px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Conversations
        </button>

        <div class="empty-state">
            <div class="empty-state-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.25">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
                </svg>
            </div>
            <div class="empty-state-title">Select a conversation</div>
            <div class="empty-state-text">Choose an existing thread from the sidebar, or start a new one using the dropdown above</div>
        </div>
    </div>

</div>

<style>
    /* ── Tablet: narrow sidebar ── */
    @media (max-width: 900px) {
        .ra-sidebar-panel {
            width: 240px !important;
        }
    }

    /* ── Mobile: sidebar slides over main panel ── */
    @media (max-width: 640px) {
        .ra-messages-layout {
            position: relative;
            height: calc(100svh - 160px) !important;
            overflow: hidden;
        }

        /* Sidebar becomes a full-width overlay, hidden off-screen by default */
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

        /* Main panel fills full width */
        .ra-main-panel {
            width: 100%;
            min-width: 0;
        }

        /* Show mobile controls */
        .ra-sidebar-close {
            display: flex !important;
        }
        .ra-show-sidebar {
            display: inline-flex !important;
        }
    }
</style>

<script>
    // Redirect on new conversation select
    document.getElementById('new-conversation').addEventListener('change', function () {
        if (this.value) window.location.href = this.value;
    });

    // Mobile sidebar toggle
    const sidebar   = document.querySelector('.ra-sidebar-panel');
    const showBtn   = document.querySelector('.ra-show-sidebar');
    const closeBtn  = document.querySelector('.ra-sidebar-close');

    function openSidebar()  { sidebar.classList.add('is-open'); }
    function closeSidebar() { sidebar.classList.remove('is-open'); }

    if (showBtn)  showBtn.addEventListener('click', openSidebar);
    if (closeBtn) closeBtn.addEventListener('click', closeSidebar);

    // On mobile, open the sidebar by default (no conversation selected yet)
    if (window.innerWidth <= 640) {
        openSidebar();
    }
</script>

@endsection