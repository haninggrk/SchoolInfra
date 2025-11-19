<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'nis',
        'name',
        'unit',
        'class',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Get the display name with NIS.
     */
    public function getDisplayNameAttribute(): string
    {
        return "{$this->name} ({$this->nis})";
    }

    /**
     * Get damage reports for this student.
     */
    public function damageReports()
    {
        return $this->hasMany(\Modules\Shared\Models\DamageReport::class);
    }
}
