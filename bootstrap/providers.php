<?php

use App\Providers\AppServiceProvider;

// Composer rebuilds the module namespace during deployment. This fallback
// keeps local CLI commands usable before that rebuild.
spl_autoload_register(static function (string $class): void {
    $prefix = 'Modules\\Inventory\\';

    if (! str_starts_with($class, $prefix)) {
        return;
    }

    $path = __DIR__.'/../Modules/Inventory/app/'.str_replace('\\', '/', substr($class, strlen($prefix))).'.php';

    if (is_file($path)) {
        require_once $path;
    }
});

require_once __DIR__.'/../Modules/Inventory/app/Providers/InventoryServiceProvider.php';

return [
    AppServiceProvider::class,
];
