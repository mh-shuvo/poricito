<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Load helper functions
        $this->loadHelpersFile();
    }

    /**
     * Load helper functions from the helpers file
     */
    private function loadHelpersFile(): void
    {
        $helpersPath = app_path('Helpers/helpers.php');
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
