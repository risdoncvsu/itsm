<?php

namespace Modules\Procurement\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ProcurementServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'procurement');
        $this->commands([\Modules\Procurement\Console\Commands\EnsureProcurementClientColumns::class]);

        Route::middleware('web')->group(__DIR__.'/../../routes/web.php');
    }
}
