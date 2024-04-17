<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
 * @property int            $task_id
 * @property int            $user_id
 * @property int            $group_id
 * @property int            $quantity
 * @property string         $status
 * @property string         $type
 * @property bool           $test_mode
 * @property \Carbon\Carbon $purchase_at
 * @property bool           $to_decline
 * @property Product        $product
 * @property Review         $review
 * @property User           $user
 * @property Task           $task
 */
class Purchase extends Model
{
    use HasFactory;

    /**
     * Purchase statuses
     */
    public const STATUS_PENDING = 'pending';

    public const STATUS_PROCESSING = 'processing';

    public const STATUS_UNAVAILABLE = 'unavailable';

    public const STATUS_NOT_ENOUGH_W_BALANCE = 'not_enough_w_balance';

    public const STATUS_PICKPOINT_OVERLOADED = 'pickpoint_overloaded';

    public const STATUS_DONE = 'done';

    /**
     * Delivery statuses
     */
    public const DELIVERY_STATUS_NONE = 'none';

    public const DELIVERY_STATUS_PROCESSING = 'processing';

    public const DELIVERY_STATUS_SORTED = 'sorted';

    public const DELIVERY_STATUS_PENDING = 'pending';

    public const DELIVERY_STATUS_ON_THE_WAY = 'on_the_way';

    public const DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP = 'available_for_pick_up';

    public const DELIVERY_STATUS_PICKED_UP = 'picked_up';

    public const DELIVERY_STATUS_CANCELED = 'canceled';

    public const DELIVERY_STATUS_NOT_PAID = 'not_paid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'is_updating_delivery',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'created_ts',
        'purchase_ts',
        'delivery_status_updated_ts',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'size' => 'array',
        'purchase_at' => 'datetime',
        'to_decline' => 'bool',
        'delivery_status_updated_at' => 'datetime',
        'type' => \App\Enums\PurchaseTypesEnum::class,
        'test_mode' => 'bool',
        // 'created_at' => 'datetime:Y-m-d h:i',
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'remote_id', 'product_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function review(): BelongsTo
    {
        return $this->belongsTo(Review::class);
    }

    public function livecargoDelivery(): MorphOne
    {
        return $this->morphOne(LivecargoDelivery::class, 'deliveryable')->orderByDesc('created_at');
    }

    public function livecargoDeliveries(): MorphMany
    {
        return $this->morphMany(LivecargoDelivery::class, 'deliveryable');
    }

    /**
     * Get the purchase's created time.
     */
    public function createdTs(): Attribute
    {
        return new Attribute(
            get: fn () => $this->created_at ? $this->created_at->toDateTimeString() : null,
        );
    }

    /**
     * Get the purchase's publish time.
     */
    public function purchaseTs(): Attribute
    {
        return new Attribute(
            get: fn () => $this->purchase_at ? $this->purchase_at->toDateTimeString() : null,
        );
    }

    /**
     * Get the purchase's publish time.
     */
    public function deliveryStatusUpdatedTs(): Attribute
    {
        return new Attribute(
            get: fn () => $this->delivery_status_updated_at ? $this->delivery_status_updated_at->toDateTimeString() : null,
        );
    }

    public function scopeAvailableForPickUp(Builder $query): void
    {
        $query->where('delivery_status', self::DELIVERY_STATUS_AVAILABLE_FOR_PICK_UP);
    }

    public function scopeAvailableReviews(Builder $query): void
    {
        $query->where('review_id', 0)->where('delivery_status', Purchase::DELIVERY_STATUS_PICKED_UP);
    }

    public function walletTransaction(): HasOne
    {
        return $this->hasOne(WalletTransaction::class);
    }

    public function telegramTransaction(): HasOne
    {
        return $this->hasOne(TelegramTransaction::class);
    }
}
