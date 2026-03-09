<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penalty extends Model
{
    protected $fillable = [
        'user_id', 'physical_borrow_id', 'type', 'amount', 'is_paid', 'paid_at'
    ];

    protected $casts = [
        'is_paid' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function physicalBorrow()
    {
        return $this->belongsTo(PhysicalBorrow::class);
    }
}