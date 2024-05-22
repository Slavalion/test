<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Enums\UserRole;
use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('admin-access', function (User $user) {
            return $user->role === UserRole::ADMIN;
        });

        Gate::define('courier-access', function (User $user) {
            return in_array($user->role, [
                UserRole::ADMIN,
                UserRole::COURIER,
                UserRole::MANAGER,
            ]);
        });

        Gate::define('manager-access', function (User $user) {
            return in_array($user->role, [
                UserRole::ADMIN,
                UserRole::MANAGER,
            ]);
        });
    }
}
