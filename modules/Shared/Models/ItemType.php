<?php

namespace Modules\Shared\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ItemType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'icon_path',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the inventory items for this item type (by name matching).
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(InventoryItem::class, 'item_type', 'name');
    }

    /**
     * Get the icon URL.
     */
    public function getIconUrlAttribute(): ?string
    {
        if (!$this->icon_path) {
            return null;
        }

        // If it's already a full URL, return it
        if (filter_var($this->icon_path, FILTER_VALIDATE_URL)) {
            return $this->icon_path;
        }

        // Otherwise, return the storage URL
        return asset('storage/' . $this->icon_path);
    }
}
