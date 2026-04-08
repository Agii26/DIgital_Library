<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title', 'author', 'category',
        'type', 'quantity', 'price', 'cover_image', 'description',
    ];

    // ── Relationships ───────────────────────────────────────────────

    public function copies()
    {
        return $this->hasMany(BookCopy::class);
    }

    public function digitalBook()
    {
        return $this->hasOne(DigitalBook::class);
    }

    public function digitalSessions()
    {
        return $this->hasMany(DigitalSession::class);
    }

    // physical_borrows still references book_id directly.
    public function physicalBorrows()
    {
        return $this->hasMany(PhysicalBorrow::class);
    }

    // ── Computed status ─────────────────────────────────────────────

    /**
     * Auto-derive status from available copy count.
     * "available" when at least one copy is free, "borrowed" when none are.
     */
    public function getStatusAttribute(): string
    {
        $available = $this->copies()->where('status', 'available')->count();
        return $available > 0 ? 'available' : 'borrowed';
    }

    public function getAvailableCopiesAttribute(): int
    {
        return $this->copies()->where('status', 'available')->count();
    }

    // ── Quantity sync ───────────────────────────────────────────────

    /**
     * Recalculate and persist the quantity cache from the copies table.
     */
    public function syncQuantity(): void
    {
        $this->update(['quantity' => $this->copies()->count()]);
    }
}