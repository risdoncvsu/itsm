<?php

namespace Modules\Ecommerce\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Modules\Ecommerce\Support\EcommerceClientContext;
use Modules\Ecommerce\Console\Commands\EnsureEcommerceClientColumns;
use Modules\Ecommerce\Console\Commands\AssignEcommerceCatalogToClient;

class EcommerceServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->scoped(EcommerceClientContext::class, fn (): EcommerceClientContext => new EcommerceClientContext());
        $this->commands([
            EnsureEcommerceClientColumns::class,
            AssignEcommerceCatalogToClient::class,
        ]);
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'ecommerce');
        Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components', 'ecommerce');

        Route::middleware('web')
            ->group(__DIR__.'/../../routes/web.php');
    }
}
