<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

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
        $this->registerPolicies();

        Gate::define('isAdmin', function ($user)
        {
            return $user->role === 'admin';
        });
        
        Gate::define('isEditor', function ($user)
        {
            return $user->role == 'editor';
        });
        
        Gate::define('isEmployee', function ($user)
        {
            return $user->role === 'employee';
        });
        
        Gate::define('isEditorOrAdmin', function ($user)
        {
            return $user->role === 'editor' || $user->role === 'admin';
        });
        
        Gate::define('isEmployeeOrEditor', function ($user)
        {
            return $user->role === 'employee' || $user->role === 'editor';
        });
    }
}
