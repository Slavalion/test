<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Casts\UserPreferencesCast;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 * @mixin \Illuminate\Database\Eloquent\Builder
 *
 * @property int                                      $id
 * @property UserRole                                 $role
 * @property string                                   $name
 * @property string                                   $email
 * @property int                                      $telegram_id
 * @property string                                   $ref_code
 * @property int                                      $inviter_id
 * @property \Illuminate\Database\Eloquent\Collection $prices
 * @property array                                    $preferences
 * @property \Carbon\Carbon                           $created_at
 * @property \Carbon\Carbon                           $updated_at
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'balance',
        'password',
        'ip',
        'telegram_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        // 'role',
    ];

    /**
     * The model's default values for attributes.
     *
     * @var array
     */
    protected $attributes = [
        'preferences' => '{"use_livecargo": false}',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_admin' => 'boolean',
        'preferences' => UserPreferencesCast::class,
        'role' => UserRole::class,
    ];

    public function wallets()
    {
        return $this->hasMany(Wallet::class, 'user_id', 'id');
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function walletTransactions(): HasMany
    {
        return $this->hasMany(WalletTransaction::class);
    }

    public function tgTransactions(): HasMany
    {
        return $this->hasMany(TelegramTransaction::class);
    }

    public function prices(): HasMany
    {
        return $this->hasMany(UserPrice::class);
    }

    public function inviter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'inviter_id');
    }

    public function livecargoDeliveries(): HasMany
    {
        return $this->hasMany(LivecargoDelivery::class, 'courier_id');
    }

    public function managerUsers(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'manager_user', 'manager_id');
    }

    public function managers(): BelongsToMany
    {
        return $this->BelongsToMany(User::class, 'manager_user', 'user_id', 'manager_id');
    }
}
