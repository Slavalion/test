<?php

namespace App\Models;

use App\Enums\ReviewReactionStatus;
use App\Enums\ReviewReactionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property int                        $id
 * @property int                        $user_id
 * @property int                        $product_id
 * @property string                     $review_id
 * @property int                        $type
 * @property int                        $period
 * @property int                        $total
 * @property int                        $progress
 * @property int                        $status
 * @property \Carbon\Carbon             $created_at
 * @property \Carbon\Carbon             $updated_at
 * @property ReviewReactionGroup        $reviewReactionGroup
 * @property User                       $user
 */
class ReviewReaction extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'task_id',
        'review_reaction_group_id',
        'product_id',
        'review_id',
        'type',
        'total',
        'period',
        'progress',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => ReviewReactionType::class,
        'status' => ReviewReactionStatus::class,
    ];

    public function reviewReactionGroup(): BelongsTo
    {
        return $this->belongsTo(ReviewReactionGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
