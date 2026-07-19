<?php

namespace Modules\HR\Providers;

use Illuminate\Support\ServiceProvider;

class HRServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any module services.
     */
    public function boot(): void
    {
        // Tell Laravel where to find the HR web routes
        // __DIR__ is app/Providers, so ../../ steps out to the HR root
        $this->loadRoutesFrom(__DIR__ . '/../../Routes/web.php');
        
        // Tell Laravel where to find the HR blade files
        // The 'hr' alias means you can call views like: view('hr::dashboard')
        $this->loadViewsFrom(__DIR__ . '/../../Resources/views', 'hr');
    }

    /**
     * Register any module services.
     */
    public function register(): void
    {
        // 
    }
}