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
                    <option value="{{ route('messages.show', $u) }}" {{ $u->id === $user->id ? 'selected' : '' }}>
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
                    class="flex items-center gap-3 px-4 py-3 hover:bg-gray-50 border-b {{ $otherUser->id === $user->id ? 'bg-blue-50' : '' }}">
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

    <!-- Chat Window -->
    <div class="flex-1 bg-white rounded-2xl shadow flex flex-col">
        <!-- Chat Header -->
        <div class="px-6 py-4 border-b flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                {{ strtoupper(substr($user->name, 0, 1)) }}
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                <p class="text-xs text-gray-500">{{ ucfirst($user->role) }} • {{ $user->department ?? '' }}</p>
            </div>
        </div>

        <!-- Messages -->
        <div class="flex-1 overflow-y-auto p-6 space-y-4" id="messages-container">
            @forelse($messages as $message)
                <div class="flex {{ $message->sender_id === Auth::id() ? 'justify-end' : 'justify-start' }}">
                    <div class="max-w-xs lg:max-w-md">
                        <div class="px-4 py-2 rounded-2xl text-sm
                            {{ $message->sender_id === Auth::id()
                                ? 'bg-blue-600 text-white rounded-br-none'
                                : 'bg-gray-100 text-gray-800 rounded-bl-none' }}">
                            {{ $message->body }}
                        </div>
                        <p class="text-xs text-gray-400 mt-1 {{ $message->sender_id === Auth::id() ? 'text-right' : '' }}">
                            {{ $message->created_at->format('h:i A') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="flex items-center justify-center h-full text-gray-400">
                    <p>No messages yet. Say hello! 👋</p>
                </div>
            @endforelse
        </div>

        <!-- Message Input -->
        <div class="px-6 py-4 border-t">
            <form method="POST" action="{{ route('messages.store') }}" class="flex gap-3">
                @csrf
                <input type="hidden" name="receiver_id" value="{{ $user->id }}" />
                <input type="text" name="body"
                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Type a message..." required autofocus />
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    Send
                </button>
            </form>
        </div>
    </div>

</div>

<script>
    // Auto scroll to bottom
    const container = document.getElementById('messages-container');
    container.scrollTop = container.scrollHeight;

    document.getElementById('new-conversation').addEventListener('change', function() {
        if (this.value) window.location.href = this.value;
    });
</script>
@endsection