@extends('layouts.app')

@section('page-title', 'RFID Attendance')

@section('content')

@if(session('success'))
    <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm font-semibold">
        {{ session('success') }}
    </div>
@endif

@if($errors->any())
    <div class="bg-red-100 text-red-600 px-4 py-3 rounded mb-4 text-sm">
        {{ $errors->first() }}
    </div>
@endif
<div class="flex justify-end mb-4">
    <a href="{{ route('admin.attendance.kiosk') }}"
        class="bg-purple-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-purple-700">
        🖥️ Kiosk Mode
    </a>
</div>

<!-- RFID Scanner -->
<div class="bg-white rounded-2xl shadow p-6 mb-6">
    <h3 class="text-lg font-semibold text-gray-700 mb-4">📡 RFID Scanner</h3>
    <form method="POST" action="{{ route('admin.attendance.scan') }}">
        @csrf
        <div class="flex gap-3 items-end">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Scan RFID Tag</label>
                <input type="text" name="rfid_tag" autofocus
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Scan or enter RFID tag..." required />
            </div>
            <button type="submit"
                class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                Record Attendance
            </button>
        </div>
    </form>
</div>

<!-- Analytics -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-blue-500">
        <p class="text-sm text-gray-500">Today's Visits</p>
        <h3 class="text-3xl font-bold text-blue-600">{{ $todayVisits }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-green-500">
        <p class="text-sm text-gray-500">Currently Inside</p>
        <h3 class="text-3xl font-bold text-green-600">{{ $currentlyIn }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-yellow-500">
        <p class="text-sm text-gray-500">This Week</p>
        <h3 class="text-3xl font-bold text-yellow-600">{{ $weekVisits }}</h3>
    </div>
    <div class="bg-white rounded-2xl shadow p-6 border-l-4 border-purple-500">
        <p class="text-sm text-gray-500">This Month</p>
        <h3 class="text-3xl font-bold text-purple-600">{{ $monthVisits }}</h3>
    </div>
</div>

<!-- Filters -->
<form method="GET" action="{{ route('admin.attendance.index') }}" class="bg-white rounded-2xl shadow p-4 mb-6 flex gap-4">
    <input type="text" name="search" value="{{ request('search') }}"
        placeholder="Search by name or ID..."
        class="flex-1 border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
    <input type="date" name="date" value="{{ request('date') }}"
        class="border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
    <select name="role" class="border border-gray-300 rounded-lg px-4 py-2 text-sm">
        <option value="">All Roles</option>
        <option value="faculty" {{ request('role') === 'faculty' ? 'selected' : '' }}>Faculty</option>
        <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
    </select>
    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-blue-700">
        Filter
    </button>
    <a href="{{ route('admin.attendance.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-semibold hover:bg-gray-300">
        Reset
    </a>
    <a href="{{ route('admin.attendance.export', request()->query()) }}"
        class="bg-green-600 text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-green-700">
        📥 Export CSV
    </a>
</form>

<!-- Logs Table -->
<div class="bg-white rounded-2xl shadow overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-gray-50 text-gray-600 uppercase text-xs">
            <tr>
                <th class="px-6 py-3 text-left">Name</th>
                <th class="px-6 py-3 text-left">ID No.</th>
                <th class="px-6 py-3 text-left">Role</th>
                <th class="px-6 py-3 text-left">Department</th>
                <th class="px-6 py-3 text-left">Type</th>
                <th class="px-6 py-3 text-left">Time</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($logs as $log)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 font-medium text-gray-800">{{ $log->user->name }}</td>
                <td class="px-6 py-4 text-gray-600">{{ $log->user->student_id ?? '-' }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $log->user->role === 'faculty' ? 'bg-purple-100 text-purple-700' : 'bg-blue-100 text-blue-700' }}">
                        {{ ucfirst($log->user->role) }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $log->user->department ?? '-' }}</td>
                <td class="px-6 py-4">
                    <span class="px-2 py-1 rounded-full text-xs font-semibold
                        {{ $log->type === 'time_in' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                        {{ $log->type === 'time_in' ? '✅ Time In' : '🚪 Time Out' }}
                    </span>
                </td>
                <td class="px-6 py-4 text-gray-600">{{ $log->scanned_at->format('M d, Y h:i A') }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-6 py-8 text-center text-gray-400">No attendance logs found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="px-6 py-4">
        {{ $logs->links() }}
    </div>
</div>
@endsection