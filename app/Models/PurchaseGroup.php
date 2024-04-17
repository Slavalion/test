<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class PurchaseGroup extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'total_sum',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['created_ts'];

    /**
     * Get the purchase's created time.
     */
    public function createdTs(): Attribute
    {
        return new Attribute(
            get: fn () => $this->created_at->format('d/m/Y H:i:s'),
        );
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'group_id')->with('product');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transaction(): MorphOne
    {
        return $this->morphOne(Transaction::class, 'target', 'target');
    }
}
