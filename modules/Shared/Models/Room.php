<?php

namespace Modules\Shared\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'floor',
        'building',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the inventory items for the room.
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class);
    }

    /**
     * Get the damage reports for the room.
     */
    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class);
    }

    /**
     * Get the digital inventory items (monitoring subset).
     */
    public function digitalInventoryItems(): HasMany
    {
        return $this->inventoryItems()->where('item_category', 'Elektronik');
    }

    /**
     * Get the QR code URL for this room.
     */
    public function getQrCodeUrlAttribute(): string
    {
        return route('room.show', $this->code);
    }

    /**
     * Get the bookings for this room.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(RoomBooking::class);
    }
}
