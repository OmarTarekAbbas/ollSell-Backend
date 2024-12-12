<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
       // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Passport::tokensExpireIn(now()->addYears(15));

        Gate::before(function ($admin, $permission) {
            if (Auth::guard('web')->check() && $admin->permissions()->contains($permission)) {
                return true;
            }
            return false;
        });
    }
}
