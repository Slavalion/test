<?php

namespace App\Models;

use App\Enums\ReviewComplaintStatus;
use App\Enums\ReviewComplaintType;
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
 * @property ReviewComplaintGroup       $reviewComplaintGroup
 * @property User                       $user
 */
class ReviewComplaint extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'review_complaint_group_id',
        'user_id',
        'product_id',
        'review_id',
        'type',
        'period',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'type' => ReviewComplaintType::class,
        'status' => ReviewComplaintStatus::class,
    ];

    public function reviewComplaintGroup(): BelongsTo
    {
        return $this->belongsTo(ReviewComplaintGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
