<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        # Gate defines authorization rules.
        # Use this gate to restrict access to admin-only sections of the website
        Gate::define('admin', function ($user) {
            # Checks if user has admin role ID.
            return $user->role_id === User::ADMIN_ROLE_ID;
        });
    }
}
