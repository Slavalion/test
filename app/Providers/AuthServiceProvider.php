<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
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
            return $user->role === User::ROLE_ADMIN;
        });

        Gate::define('courier-access', function (User $user) {
            return in_array($user->role, [
                User::ROLE_ADMIN,
                User::ROLE_COURIER
            ]);
        });
    }
}
