<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property int                                                                $id
 * @property \Carbon\Carbon                                                     $created_at
 * @property \Carbon\Carbon                                                     $updated_at
 * @property \Illuminate\Database\Eloquent\Relations\HasOne|Purchase            $purchase
 */
class Review extends Model
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'public_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['public_ts'];

    /**
     * Get the purchase's created time.
     */
    public function publicTs(): Attribute
    {
        return new Attribute(
            get: fn () => $this->public_at->toDateTimeString(),
        );
    }

    public function purchase(): HasOne
    {
        return $this->hasOne(Purchase::class)->with('product');
    }
}
