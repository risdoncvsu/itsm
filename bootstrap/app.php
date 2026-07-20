<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->alias([
            'hr.access' => \Modules\HR\Http\Middleware\EmployeeAuth::class,
            'inventory.access' => \Modules\Inventory\Http\Middleware\InventoryAccess::class,
            'procurement.access' => \Modules\Procurement\Http\Middleware\ProcurementAccess::class,
            'order-fulfillment.access' => \Modules\OrderFulfillment\Http\Middleware\OrderFulfillmentAccess::class,
            'ecommerce.client' => \Modules\Ecommerce\Http\Middleware\ResolveStorefrontClient::class,
            'root.admin' => \App\Http\Middleware\EnsureRootAdmin::class,
            'client.admin' => \App\Http\Middleware\EnsureClientAdmin::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
