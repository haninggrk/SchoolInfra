<?php

namespace Modules\Shared\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'inventory_item_id',
        'reporter_name',
        'reporter_class',
        'reporter_contact',
        'description',
        'photos',
        'urgency_level',
        'status',
        'admin_notes',
        'reported_at',
        'resolved_at',
    ];

    protected $casts = [
        'photos' => 'array',
        'reported_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    /**
     * Get the room that owns the damage report.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the inventory item that was damaged.
     */
    public function inventoryItem(): BelongsTo
    {
        return $this->belongsTo(InventoryItem::class);
    }

    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        $status = $this->report_status ?? $this->status;
        return match($status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_progress', 'sedang_diproses' => 'bg-blue-100 text-blue-800',
            'completed', 'selesai' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'baru' => 'bg-blue-100 text-blue-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get severity badge class for UI.
     */
    public function getSeverityBadgeClassAttribute(): string
    {
        $severity = $this->damage_severity ?? $this->urgency_level;
        return match($severity) {
            'ringan', 'rendah' => 'bg-green-100 text-green-800',
            'sedang' => 'bg-yellow-100 text-yellow-800',
            'berat', 'tinggi' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get urgency badge class for UI (backwards compatibility).
     */
    public function getUrgencyBadgeClassAttribute(): string
    {
        return $this->getSeverityBadgeClassAttribute();
    }

    /**
     * Check if report is resolved.
     */
    public function isResolved(): bool
    {
        return $this->status === 'selesai';
    }

    /**
     * Mark report as resolved.
     */
    public function markAsResolved(): void
    {
        $this->update([
            'status' => 'selesai',
            'resolved_at' => now(),
        ]);
    }
}
