<?php

namespace App\Models;

use App\Enums\LivecargoDeliveryPickedUpEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property int                        $picked_up_status
 * @property \Illuminate\Support\Carbon $status_updated_at
 * @property int                        $courier_id
 *
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class LivecargoDelivery extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'deliveryable_id',
        'deliveryable_type',
        'livecargo_order_id',
        'pickpoint_address_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'picked_up_status' => LivecargoDeliveryPickedUpEnum::class,
        'status_updated_at' => 'datetime',
    ];

    public function deliveryable(): MorphTo
    {
        return $this->morphTo();
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class);
    }

    public function product(): HasManyThrough
    {
        return $this->hasManyThrough(WalletTransaction::class, Wallet::class);
    }

    public function livecargoOrder(): BelongsTo
    {
        return $this->belongsTo(LivecargoOrder::class);
    }

    public function pickpointAddress(): BelongsTo
    {
        return $this->belongsTo(PickpointAddress::class);
    }
}
