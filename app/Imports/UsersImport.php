<?php

namespace App\Imports;

use App\Models\User;
use App\Notifications\SetPasswordNotification;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class UsersImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row): User
    {
        $token = Str::random(64);

        $user = User::create([
            'name'                          => $row['name'],
            'email'                         => $row['email'],
            'password'                      => bcrypt(Str::random(16)),
            'role'                          => $row['role'],
            'student_id'                    => $row['student_id'] ?? null,
            'department'                    => $row['department'] ?? null,
            'is_active'                     => true,
            'password_set'                  => false,
            'set_password_token'            => $token,
            'set_password_token_expires_at' => now()->addHours(48),
        ]);

        $user->assignRole($row['role']);
        $user->notify(new SetPasswordNotification($token));

        return $user;
    }

    public function rules(): array
    {
        return [
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email',
            'role'  => 'required|in:faculty,student',
        ];
    }
}