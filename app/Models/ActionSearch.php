<?php

namespace App\Models;

use App\Enums\ActionItemStatus;
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
 * @property int                        $view_time
 * @property string                     $keywords
 * @property int                        $status
 * @property \Carbon\Carbon             $start_at
 * @property \Carbon\Carbon             $created_at
 * @property \Carbon\Carbon             $updated_at
 * @property ActionSearchGroup          $actionSearchGroup
 * @property User                       $user
 */
class ActionSearch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task_id',
        'action_search_group_id',
        'user_id',
        'product_id',
        'keywords',
        'view_time',
        'start_at',
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'status' => ActionItemStatus::class,
        'start_at' => 'datetime',
    ];

    public function actionSearchGroup(): BelongsTo
    {
        return $this->belongsTo(ActionSearchGroup::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
