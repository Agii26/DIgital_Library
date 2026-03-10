@extends('layouts.app')

@section('page-title', 'Password Setup Links')

@section('content')
<div class="max-w-4xl">

    <div class="flex items-center justify-between mb-5">
        <div>
            <h2 class="text-xl font-black text-gray-900">Password Setup Links</h2>
            <p class="text-sm text-gray-400 mt-0.5">Users who haven't set their password yet</p>
        </div>
        <a href="{{ route('admin.users.index') }}"
            class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-sm font-semibold hover:bg-gray-200 transition">
            ← Back to Users
        </a>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-5 text-sm font-medium">
        {{ session('success') }}
    </div>
    @endif

    @if($users->isEmpty())
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
        <p class="text-4xl mb-3">🎉</p>
        <p class="font-semibold text-gray-700">All users have set their passwords!</p>
        <p class="text-sm text-gray-400 mt-1">No pending password setup links.</p>
    </div>
    @else
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm divide-y divide-gray-50">
        @foreach($users as $user)
        <div class="p-5">
            <div class="flex items-start justify-between gap-4 mb-3">
                <div>
                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-xs text-gray-400">{{ $user->email }} · {{ ucfirst($user->role) }}</p>
                    <p class="text-xs text-orange-500 mt-0.5">
                        Expires: {{ $user->set_password_token_expires_at->format('M d, Y h:i A') }}
                    </p>
                </div>
                <form method="POST" action="{{ route('admin.users.resend-set-password', $user) }}">
                    @csrf
                    <button type="submit"
                        class="shrink-0 bg-gray-100 text-gray-700 px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-50 hover:text-blue-700 transition">
                        🔄 Regenerate
                    </button>
                </form>
            </div>

            <div class="flex items-center gap-2 bg-gray-50 rounded-xl px-3 py-2">
                <code class="text-xs text-gray-600 break-all flex-1" id="link-{{ $user->id }}">{{ url('/set-password?token=' . $user->set_password_token . '&email=' . urlencode($user->email)) }}</code>
                <button onclick="copyLink('{{ $user->id }}')"
                    class="shrink-0 bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-blue-700 transition whitespace-nowrap">
                    Copy
                </button>
            </div>
        </div>
        @endforeach
    </div>
    @endif

</div>

<script>
function copyLink(userId) {
    const link = document.getElementById('link-' + userId).innerText;
    navigator.clipboard.writeText(link).then(() => {
        alert('Link copied to clipboard!');
    });
}
</script>
@endsection