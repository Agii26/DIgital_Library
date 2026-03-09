<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhysicalBorrow extends Model
{
    protected $fillable = [
        'user_id', 'book_id', 'status',
        'reserved_at', 'approved_at', 'claimed_at',
        'due_date', 'returned_at', 'condition'
    ];

    protected $casts = [
        'reserved_at' => 'datetime',
        'approved_at' => 'datetime',
        'claimed_at' => 'datetime',
        'due_date' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function penalties()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }
}