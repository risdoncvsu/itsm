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
        // Filament belongs to the original standalone admin panel and is not
        // required for the unified storefront. Do not prevent the ERP from
        // booting when that optional package is absent.
        if (class_exists(\Filament\PanelProvider::class)) {
            $this->app->register(\Modules\Ecommerce\Providers\Filament\EcommercePanelProvider::class);
        }
    }

    public function boot(): void
    {
        $this->loadViewsFrom(__DIR__.'/../../resources/views', 'ecommerce');
        Route::middleware('web')->group(__DIR__.'/../../routes/web.php');
        // The standalone storefront uses <x-navbar>, <x-footer>, and related
        // anonymous components directly, so retain those component names after
        // moving its views into this module.
        Blade::anonymousComponentPath(__DIR__.'/../../resources/views/components');

    }
}
