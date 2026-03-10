@extends('layouts.app')

@section('page-title', 'User Management')

@section('content')
@if(session('set_password_url'))
<div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 mb-5">
    <div class="flex items-start justify-between gap-4">
        <div class="flex-1">
            <p class="font-semibold text-blue-800 text-sm mb-1">
                📋 Share this password setup link with <strong>{{ session('set_password_name') }}</strong>:
            </p>
            <code class="text-xs text-blue-700 break-all bg-blue-100 px-3 py-2 rounded-lg block mt-1">
                {{ session('set_password_url') }}
            </code>
        </div>
        <button onclick="copyLink()"
            class="shrink-0 bg-blue-600 text-white px-4 py-2 rounded-xl text-sm font-semibold hover:bg-blue-700 transition">
            Copy Link
        </button>
    </div>
</div>

<script>
function copyLink() {
    navigator.clipboard.writeText('{{ session('set_password_url') }}');
    alert('Link copied!');
}
</script>
@endif

<div class="mb-6 flex justify-between items-center">
    <div class="flex gap-3">
        <a href="{{ route('admin.users.create') }}"
            class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
            + Add User
        </a>
        <form method="POST" action="{{ route('admin.users.import') }}" enctype="multipart/form-data" class="flex gap-2">
            @csrf
            <input type="file" name="file" accept=".xlsx,.csv,.xls"
                class="text-sm border border-gray-300 rounded-lg px-3 py-2" required />
            <button type="submit"
                class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
                Import CSV/Excel
            </button>
        </form>
    </div>
</div>

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif
@if($errors->has('delete'))
    <div class="bg-red-100 text-red-600 px-4 py-3 rounded mb-4 text-sm">
        ⚠️ {{ $errors->first('delete') }}
    </div>
@endif

<div class="bg-white rounded-2xl shadow overflow-hidden">
    <!-- Search -->
    <form method="GET" action="{{ route('admin.users.index') }}" class="bg-white rounded-2xl shadow p-4 mb-6 flex gap-4">
        <input type="text" name="search" value="{{ request('search') }}"
            placeholder="Search name, email, ID, department..."
            class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
        <select name="role" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
            <option value="">All Roles</option>
            <option value="faculty" {{ request('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
            <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
            Search
        </button>
        <a href="{{ route('admin.users.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300">
            Reset
        </a>
    </form>
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">Email</th>
                <th class="px-6 py-3 text-left">Role</th>
                <th class="px-6 py-3 text-left">Student ID</th>
                <th class="px-6 py-3 text-left">Department</th>
                <th class="px-6 py-3 text-left">RFID</th>
                <th class="px-6 py-3 text-left">Password</th>
                <th class="px-6 py-3 text-left">Status</th>
                <th class="px-6 py-3 text-left">Actions</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($users as $user)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $user->name }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $user->email }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $user->role === 'faculty' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($user->role) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $user->student_id ?? '-' }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $user->department ?? '-' }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $user->rfid_tag ?? '-' }}</td>
                <td class="px-6 py-4">
                    @if($user->password_set)
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Set</span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-yellow-100 text-yellow-700">Pending</span>
                    @endif
                </td>
                <td class="px-6 py-4">
                    @if($user->is_active)
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-green-100 text-green-700">Active</span>
                    @else
                        <span class="px-2 py-1 rounded-full text-xs font-semibold bg-red-100 text-red-700">Inactive</span>
                    @endif
                </td>
                <td class="px-6 py-4 flex gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}"
                        class="text-xs px-3 py-1 rounded-lg border border-yellow-400 text-yellow-500 hover:bg-yellow-50">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1 rounded-lg border
                            {{ $user->is_active ? 'border-red-400 text-red-500 hover:bg-red-50' : 'border-green-400 text-green-500 hover:bg-green-50' }}">
                            {{ $user->is_active ? 'Deactivate' : 'Activate' }}
                        </button>
                    </form>
                    @if(!$user->password_set)
                    <form method="POST" action="{{ route('admin.users.resend', $user) }}">
                        @csrf
                        <button type="submit" class="text-xs px-3 py-1 rounded-lg border border-blue-400 text-blue-500 hover:bg-blue-50">
                            Resend Email
                        </button>
                    </form>
                    @endif
                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Are you sure you want to delete this user?')"
                            class="text-xs px-3 py-1 rounded-lg border border-red-400 text-red-500 hover:bg-red-50">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="px-6 py-8 text-center text-gray-400">No users found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">
        {{ $users->links() }}
    </div>
</div>
@endsection