<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PickpointAddress extends Model
{
    use HasFactory;

    public function livecargoDeliveries(): HasMany
    {
        return $this->hasMany(LivecargoDelivery::class);
    }
}
