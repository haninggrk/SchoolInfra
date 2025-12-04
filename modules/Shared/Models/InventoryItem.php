<?php

namespace Modules\Shared\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'item_category',
        'item_type',
        'item_type_id',
        'item_name',
        'barcode',
        'prefix',
        'qty',
        'unit',
        'date_added',
        'purchase_date',
        'last_maintenance_date',
        'next_maintenance_date',
        'barcode_status',
        'status',
        'is_monitored',
        'notes',
    ];

    protected $casts = [
        'date_added' => 'date',
        'purchase_date' => 'date',
        'last_maintenance_date' => 'date',
        'next_maintenance_date' => 'date',
        'qty' => 'integer',
        'is_monitored' => 'boolean',
    ];

    /**
     * Get the room that owns the inventory item.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the item type for this inventory item.
     */
    public function itemTypeRelation(): BelongsTo
    {
        return $this->belongsTo(ItemType::class, 'item_type_id');
    }

    /**
     * Get the damage reports for this inventory item.
     */
    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class);
    }

    /**
     * Scope for digital items (monitoring).
     */
    public function scopeDigital($query)
    {
        return $query->where('is_monitored', true);
    }
    
    /**
     * Scope for monitored items.
     */
    public function scopeMonitored($query)
    {
        return $query->where('is_monitored', true);
    }

    /**
     * Scope for active barcodes.
     */
    public function scopeActiveBarcode($query)
    {
        return $query->where('barcode_status', 'active');
    }

    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'baik' => 'bg-green-100 text-green-800',
            'rusak' => 'bg-red-100 text-red-800',
            'dalam_perbaikan' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get urgency badge class for UI.
     */
    public function getUrgencyBadgeClassAttribute(): string
    {
        return match($this->urgency_level ?? 'sedang') {
            'rendah' => 'bg-green-100 text-green-800',
            'sedang' => 'bg-yellow-100 text-yellow-800',
            'tinggi' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get condition badge class for UI (alias for status).
     */
    public function getConditionBadgeClassAttribute(): string
    {
        return $this->getStatusBadgeClassAttribute();
    }

    /**
     * Get item_code attribute (alias for barcode).
     */
    public function getItemCodeAttribute(): string
    {
        return $this->barcode;
    }

    /**
     * Get item_condition attribute (alias for status).
     */
    public function getItemConditionAttribute(): string
    {
        return $this->status;
    }

    /**
     * Get brand attribute (using item_type or empty).
     */
    public function getBrandAttribute(): ?string
    {
        return $this->item_type ?? null;
    }

    /**
     * Get quantity attribute (alias for qty).
     */
    public function getQuantityAttribute(): int
    {
        return $this->qty;
    }
}
