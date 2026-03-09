<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'accession_no', 'title', 'author', 'category',
        'type', 'status', 'price', 'cover_image', 'description'
    ];

    public function digitalBook()
    {
        return $this->hasOne(DigitalBook::class);
    }

    public function physicalBorrows()
    {
        return $this->hasMany(PhysicalBorrow::class);
    }

    public function digitalSessions()
    {
        return $this->hasMany(DigitalSession::class);
    }
}