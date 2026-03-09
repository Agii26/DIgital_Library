<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\SoftDeletes;


class User extends Authenticatable
{
    use HasRoles, Notifiable, SoftDeletes;

    protected $fillable = [
        'name', 'email', 'password', 'rfid_tag',
        'role', 'student_id', 'department', 'is_active',
        'password_set', 'set_password_token', 'set_password_token_expires_at'
    ];

    public function physicalBorrows()
    {
        return $this->hasMany(PhysicalBorrow::class);
    }

    public function digitalSessions()
    {
        return $this->hasMany(DigitalSession::class);
    }

    public function penalties()
    {
        return $this->hasMany(Penalty::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'receiver_id');
    }

    public function attendanceLogs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}