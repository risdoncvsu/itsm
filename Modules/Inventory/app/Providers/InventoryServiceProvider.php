<?php

namespace Modules\Inventory\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class InventoryServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'inventory');
        View::addLocation(__DIR__.'/../../resources/views');
        Route::middleware('web')
            ->prefix('inventory')
            ->group(__DIR__.'/../../routes/web.php');
    }
}
