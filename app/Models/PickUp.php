<?php

namespace App\Models;

use App\Enums\PickUpStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property int            $id
 * @property int            $user_id
 * @property int            $product_id
 * @property string         $address
 * @property string         $phone
 * @property string         $code
 * @property string         $qr_code_link
 * @property bool           $to_decline
 * @property int            $status
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property User           $user
 * @property Product        $product
 */
class PickUp extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'external_id',
        'product_id',
        'address',
        'phone',
        'code',
        'qr_code_link',
        'to_decline',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => PickUpStatus::class,
        'to_decline' => 'bool',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'remote_id', 'product_id');
    }

    public function livecargoDelivery(): MorphOne
    {
        return $this->morphOne(LivecargoDelivery::class, 'deliveryable');
    }

    public function livecargoDeliveries(): MorphMany
    {
        return $this->morphMany(LivecargoDelivery::class, 'deliveryable');
    }
}
