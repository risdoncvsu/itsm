<?php

namespace Modules\OrderFulfillment\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class OrderFulfillmentServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'order-fulfillment');
        Route::middleware('web')->group(__DIR__.'/../../routes/web.php');
    }
}
