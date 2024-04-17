<?php

namespace App\Models;

use App\Enums\ActionGroupStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property int                        $id
 * @property int                        $user_id
 * @property int                        $product_id
 * @property int                        $total
 * @property int                        $progress
 * @property int                        $status
 * @property \Carbon\Carbon             $created_at
 * @property \Carbon\Carbon             $updated_at
 * @property User                       $user
 */
class ActionCartGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'product_id',
        'total',
        'progress',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => ActionGroupStatus::class,
    ];

    public function actionCarts(): HasMany
    {
        return $this->hasMany(ActionCart::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id', 'remote_id');
    }

    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'target', 'target');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
