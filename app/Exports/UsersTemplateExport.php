<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UsersTemplateExport implements FromArray, WithHeadings
{
    public function headings(): array
    {
        return ['name', 'email', 'role', 'student_id', 'department', 'rfid_tag'];
    }

    public function array(): array
    {
        return [
            ['Juan Dela Cruz', 'juan@example.com', 'student', '2024-0001', 'Grade 6', ''],
            ['Maria Santos',   'maria@example.com', 'faculty', '',          'Math Dept', ''],
        ];
    }
}