<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            ['key' => 'student_borrow_limit',      'value' => '2',  'description' => 'Max books a student can borrow'],
            ['key' => 'faculty_borrow_limit',       'value' => '5',  'description' => 'Max books a faculty can borrow'],
            ['key' => 'student_borrow_days',        'value' => '2',  'description' => 'Days a student can borrow a book'],
            ['key' => 'faculty_borrow_days',        'value' => '7',  'description' => 'Days a faculty can borrow a book'],
            ['key' => 'digital_reading_time',       'value' => '60', 'description' => 'Digital book reading time limit in minutes'],
            ['key' => 'overdue_fine_per_day',       'value' => '5',  'description' => 'Fine per day for overdue books'],
            ['key' => 'damaged_book_fine_multiplier', 'value' => '0.5', 'description' => 'Fine as multiplier of book price for damaged books (e.g. 0.5 = 50%)'],
            ['key' => 'lost_book_fine_multiplier',  'value' => '1',  'description' => 'Multiplier of book price for lost books'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}