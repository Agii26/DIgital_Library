@extends('layouts.app')

@section('page-title', 'System Settings')

@section('content')
<div class="max-w-3xl">

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <!-- Borrow Limits -->
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">📚 Borrow Limits</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Student Borrow Limit
                        <span class="text-xs text-gray-400">(max books at a time)</span>
                    </label>
                    <input type="number" name="student_borrow_limit"
                        value="{{ $settings['student_borrow_limit']->value ?? 2 }}"
                        min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Faculty Borrow Limit
                        <span class="text-xs text-gray-400">(max books at a time)</span>
                    </label>
                    <input type="number" name="faculty_borrow_limit"
                        value="{{ $settings['faculty_borrow_limit']->value ?? 5 }}"
                        min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Student Borrow Days
                        <span class="text-xs text-gray-400">(days allowed)</span>
                    </label>
                    <input type="number" name="student_borrow_days"
                        value="{{ $settings['student_borrow_days']->value ?? 2 }}"
                        min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Faculty Borrow Days
                        <span class="text-xs text-gray-400">(days allowed)</span>
                    </label>
                    <input type="number" name="faculty_borrow_days"
                        value="{{ $settings['faculty_borrow_days']->value ?? 7 }}"
                        min="1"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>
        </div>

        <!-- Digital Books -->
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">💻 Digital Books</h3>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">
                    Reading Time Limit
                    <span class="text-xs text-gray-400">(in minutes)</span>
                </label>
                <input type="number" name="digital_reading_time"
                    value="{{ $settings['digital_reading_time']->value ?? 60 }}"
                    min="1"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
            </div>
        </div>

        <!-- Fines -->
        <div class="bg-white rounded-2xl shadow p-6 mb-6">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">💰 Fines & Penalties</h3>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Overdue Fine Per Day
                        <span class="text-xs text-gray-400">(₱ per day)</span>
                    </label>
                    <input type="number" name="overdue_fine_per_day"
                        value="{{ $settings['overdue_fine_per_day']->value ?? 5 }}"
                        min="0" step="0.01"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Damaged Book Fine Multiplier
                        <span class="text-xs text-gray-400">(e.g. 0.5 = 50% of book price)</span>
                    </label>
                    <input type="number" name="damaged_book_fine_multiplier"
                        value="{{ $settings['damaged_book_fine_multiplier']->value ?? 0.5 }}"
                        min="0" max="1" step="0.01"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        Lost Book Fine Multiplier
                        <span class="text-xs text-gray-400">(e.g. 1 = 100% of book price)</span>
                    </label>
                    <input type="number" name="lost_book_fine_multiplier"
                        value="{{ $settings['lost_book_fine_multiplier']->value ?? 1 }}"
                        min="0" step="0.01"
                        class="w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400" />
                </div>
            </div>
        </div>

        <button type="submit"
            class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700">
            Save Settings
        </button>
    </form>
</div>
@endsection