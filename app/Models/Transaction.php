<?php

namespace App\Models;

use App\Enums\TransactionTarget;
use App\Enums\TransactionType;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property int               $id
 * @property int               $user_id
 * @property int               $target_id
 * @property int               $amount
 * @property TransactionType   $type
 * @property TransactionTarget $target
 * @property \Carbon\Carbon    $created_at
 * @property \Carbon\Carbon    $updated_at
 */
class Transaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'target_id',
        'amount',
        'type',
        'target',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => TransactionType::class,
        'target' => TransactionTarget::class,
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'created_ts',
    ];

    /**
     * Get the purchase's created time.
     */
    public function createdTs(): Attribute
    {
        return new Attribute(
            get: fn () => $this->created_at ? $this->created_at->toDateTimeString() : null,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
