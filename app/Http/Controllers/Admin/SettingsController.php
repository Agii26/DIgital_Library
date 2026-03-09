<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->keyBy('key');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'student_borrow_limit'         => 'required|integer|min:1',
            'faculty_borrow_limit'         => 'required|integer|min:1',
            'student_borrow_days'          => 'required|integer|min:1',
            'faculty_borrow_days'          => 'required|integer|min:1',
            'digital_reading_time'         => 'required|integer|min:1',
            'overdue_fine_per_day'         => 'required|numeric|min:0',
            'damaged_book_fine_multiplier' => 'required|numeric|min:0|max:1',
            'lost_book_fine_multiplier'    => 'required|numeric|min:0',
        ]);

        foreach ($request->except('_token') as $key => $value) {
            Setting::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}