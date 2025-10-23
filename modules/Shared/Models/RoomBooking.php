<?php

namespace Modules\Shared\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class RoomBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'user_id',
        'subject',
        'description',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
    ];

    /**
     * Get the room for this booking.
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user (guru) who made this booking.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for upcoming bookings.
     */
    public function scopeUpcoming($query)
    {
        return $query->where('booking_date', '>=', now()->toDateString())
            ->orderBy('booking_date')
            ->orderBy('start_time');
    }

    /**
     * Scope for today's bookings.
     */
    public function scopeToday($query)
    {
        return $query->where('booking_date', now()->toDateString());
    }

    /**
     * Check if booking conflicts with existing bookings.
     */
    public function hasConflict(): bool
    {
        return static::where('room_id', $this->room_id)
            ->where('booking_date', $this->booking_date)
            ->where('id', '!=', $this->id ?? 0)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) {
                $query->whereBetween('start_time', [$this->start_time, $this->end_time])
                    ->orWhereBetween('end_time', [$this->start_time, $this->end_time])
                    ->orWhere(function ($q) {
                        $q->where('start_time', '<=', $this->start_time)
                          ->where('end_time', '>=', $this->end_time);
                    });
            })
            ->exists();
    }

    /**
     * Get status badge class for UI.
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'approved' => 'bg-green-100 text-green-800',
            'rejected' => 'bg-red-100 text-red-800',
            'completed' => 'bg-blue-100 text-blue-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}


