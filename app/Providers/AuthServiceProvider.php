<?php

namespace App\Providers;

use App\Models\Memorial;
use App\Models\User;
use App\Policies\MemorialPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Memorial::class => MemorialPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        // Explicitly register policies
        $this->registerPolicies();
        
        // Explicitly bind policy to gate
        Gate::policy(Memorial::class, MemorialPolicy::class);
        
        // Admin override - admins can do everything
        Gate::before(function (User $user, string $ability) {
            if ($user->isAdmin()) {
                return true;
            }
        });
    }
}
