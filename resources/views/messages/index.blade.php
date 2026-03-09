@extends('layouts.app')

@section('page-title', 'Messages')

@section('content')
<div class="flex gap-6 h-[70vh]">

    <!-- Conversations List -->
    <div class="w-80 bg-white rounded-2xl shadow flex flex-col">
        <div class="p-4 border-b">
            <h3 class="font-semibold text-gray-700">Conversations</h3>
        </div>

        <!-- New Message -->
        <div class="p-4 border-b">
            <select id="new-conversation"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="">✉️ New conversation...</option>
                @foreach($users as $u)
                    <option value="{{ route('messages.show', $u) }}">
                        {{ $u->name }} ({{ ucfirst($u->role) }})
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse($conversations as $userId => $message)
                @php
                    $otherUser = $message->sender_id === Auth::id() ? $message->receiver : $message->sender;
                @endphp
                <a href="{{ route('messages.show', $otherUser) }}"
                    class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 border-b">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold text-sm">
                        {{ strtoupper(substr($otherUser->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-medium text-gray-800 text-sm">{{ $otherUser->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $message->body }}</p>
                    </div>
                    <span class="text-xs text-gray-400">{{ $message->created_at->diffForHumans() }}</span>
                </a>
            @empty
                <p class="text-center text-gray-400 text-sm p-6">No conversations yet.</p>
            @endforelse
        </div>
    </div>

    <!-- Empty State -->
    <div class="flex-1 bg-white rounded-2xl shadow flex items-center justify-center">
        <div class="text-center text-gray-400">
            <p class="text-4xl mb-3">💬</p>
            <p class="font-medium">Select a conversation</p>
            <p class="text-sm">or start a new one</p>
        </div>
    </div>

</div>

<script>
    document.getElementById('new-conversation').addEventListener('change', function() {
        if (this.value) window.location.href = this.value;
    });
</script>
@endsection