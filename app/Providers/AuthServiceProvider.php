<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::before(function ($user, $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });

        // the gate checks if the user is an admin or a superadmin
        Gate::define('accessAdminpanel', function($user) {
            return $user->hasRole('admin');
        });

        // the gate checks if the user is a member
        Gate::define('accessProfile', function($user) {
            return $user->hasRoles(['admin', 'agent']);
        });
    }
}
