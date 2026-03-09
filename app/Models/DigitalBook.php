<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DigitalBook extends Model
{
    protected $fillable = ['book_id', 'file_path'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}