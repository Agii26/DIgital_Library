@extends('layouts.app')

@section('page-title', 'System Settings')

@section('content')

{{-- Page Header --}}
<div class="page-title-wrap">
    <div>
        <h1 class="page-title">System Settings</h1>
        <p class="page-subtitle">Configure borrowing rules, digital access limits, and fine policies</p>
    </div>
</div>

<div style="max-width:760px;">
<form method="POST" action="{{ route('admin.settings.update') }}">
    @csrf

    {{-- Borrow Limits --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:8px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25" />
                </svg>
                Borrow Limits
            </h2>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">

                <div class="form-group">
                    <label class="form-label">
                        Student Borrow Limit
                    </label>
                    <input
                        type="number"
                        name="student_borrow_limit"
                        value="{{ $settings['student_borrow_limit']->value ?? 2 }}"
                        min="1"
                        class="form-control"
                    />
                    <span class="form-hint">Maximum books a student may borrow at one time</span>
                </div>

                <div class="form-group">
                    <label class="form-label">
                        Faculty Borrow Limit
                    </label>
                    <input
                        type="number"
                        name="faculty_borrow_limit"
                        value="{{ $settings['faculty_borrow_limit']->value ?? 5 }}"
                        min="1"
                        class="form-control"
                    />
                    <span class="form-hint">Maximum books a faculty member may borrow at one time</span>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">
                        Student Borrow Days
                    </label>
                    <input
                        type="number"
                        name="student_borrow_days"
                        value="{{ $settings['student_borrow_days']->value ?? 2 }}"
                        min="1"
                        class="form-control"
                    />
                    <span class="form-hint">Number of days students are allowed to keep a book</span>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">
                        Faculty Borrow Days
                    </label>
                    <input
                        type="number"
                        name="faculty_borrow_days"
                        value="{{ $settings['faculty_borrow_days']->value ?? 7 }}"
                        min="1"
                        class="form-control"
                    />
                    <span class="form-hint">Number of days faculty members are allowed to keep a book</span>
                </div>

            </div>
        </div>
    </div>

    {{-- Digital Books --}}
    <div class="card" style="margin-bottom:1.5rem;">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:8px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0H3" />
                </svg>
                Digital Books
            </h2>
        </div>
        <div class="card-body">
            <div style="max-width:360px;">
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Reading Time Limit</label>
                    <input
                        type="number"
                        name="digital_reading_time"
                        value="{{ $settings['digital_reading_time']->value ?? 60 }}"
                        min="1"
                        class="form-control"
                    />
                    <span class="form-hint">Maximum continuous reading session duration, in minutes</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Fines & Penalties --}}
    <div class="card" style="margin-bottom:1.75rem;">
        <div class="card-header">
            <h2 class="card-title">
                <svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:8px;vertical-align:middle;color:var(--gold);">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Fines &amp; Penalties
            </h2>
        </div>
        <div class="card-body">
            <div style="display:grid;grid-template-columns:repeat(2,1fr);gap:1.25rem;">

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Overdue Fine Per Day</label>
                    <input
                        type="number"
                        name="overdue_fine_per_day"
                        value="{{ $settings['overdue_fine_per_day']->value ?? 5 }}"
                        min="0"
                        step="0.01"
                        class="form-control"
                    />
                    <span class="form-hint">Amount charged in &#8369; for each day a book is overdue</span>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Damaged Book Fine Multiplier</label>
                    <input
                        type="number"
                        name="damaged_book_fine_multiplier"
                        value="{{ $settings['damaged_book_fine_multiplier']->value ?? 0.5 }}"
                        min="0"
                        max="1"
                        step="0.01"
                        class="form-control"
                    />
                    <span class="form-hint">Fraction of book price charged for damage &mdash; e.g. 0.5 = 50%</span>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Lost Book Fine Multiplier</label>
                    <input
                        type="number"
                        name="lost_book_fine_multiplier"
                        value="{{ $settings['lost_book_fine_multiplier']->value ?? 1 }}"
                        min="0"
                        step="0.01"
                        class="form-control"
                    />
                    <span class="form-hint">Fraction of book price charged when a book is lost &mdash; e.g. 1 = 100%</span>
                </div>

            </div>
        </div>
    </div>

    {{-- Submit --}}
    <div>
        <button type="submit" class="btn btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.75" style="margin-right:6px;vertical-align:middle;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            Save Settings
        </button>
    </div>

</form>
</div>

@endsection