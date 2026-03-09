@extends('layouts.app')

@section('page-title', 'My Profile')

@section('content')
<div class="max-w-2xl">

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <!-- Profile Info -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">👤 Profile Information</h3>

        @if($errors->hasAny(['name', 'department']))
            <div class="bg-red-100 text-red-600 text-sm px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" value="{{ $user->email }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-sm"
                        disabled />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <input type="text" value="{{ ucfirst($user->role) }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-sm"
                        disabled />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID No.</label>
                    <input type="text" value="{{ $user->student_id ?? '-' }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-sm"
                        disabled />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Department</label>
                    <input type="text" name="department" value="{{ old('department', $user->department) }}"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">RFID Tag</label>
                    <input type="text" value="{{ $user->rfid_tag ?? '-' }}"
                        class="w-full border border-gray-200 bg-gray-50 rounded-lg px-4 py-2 text-sm"
                        disabled />
                </div>
            </div>
            <div class="mt-4">
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
                    Update Profile
                </button>
            </div>
        </form>
    </div>

    <!-- Change Password -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h3 class="text-lg font-semibold text-gray-700 mb-4">🔒 Change Password</h3>

        @if($errors->hasAny(['current_password', 'password']))
            <div class="bg-red-100 text-red-600 text-sm px-4 py-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.change-password') }}">
            @csrf
            <div class="grid grid-cols-1 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Password</label>
                    <input type="password" name="current_password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                    <input type="password" name="password"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        placeholder="Minimum 8 characters" required />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirm New Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                        required />
                </div>
            </div>
            <div class="mt-4">
                <button type="submit"
                    class="bg-red-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-red-700">
                    Change Password
                </button>
            </div>
        </form>
    </div>

</div>
@endsection