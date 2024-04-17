<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PickpointZones extends Model
{
    use HasFactory;

    public function addresses(): HasMany
    {
        return $this->hasMany(PickpointAddress::class, 'pickpoint_zone_id');
    }
}
