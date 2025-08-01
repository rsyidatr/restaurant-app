<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class MultiSessionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Register helper files
        $this->loadHelpers();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Load helper files
     */
    private function loadHelpers()
    {
        foreach (glob(app_path('Helpers') . '/*.php') as $file) {
            require_once $file;
        }
    }
}
