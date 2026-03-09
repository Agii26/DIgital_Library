<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalSession extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'started_at', 'expires_at', 'is_active'
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}