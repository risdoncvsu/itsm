<?php

use App\Providers\AppServiceProvider;

// Composer rebuilds the module namespace during deployment. This fallback
// keeps local CLI commands usable before that rebuild.
spl_autoload_register(static function (string $class): void {
    $prefixes = [
        'Modules\\Inventory\\' => __DIR__.'/../Modules/Inventory/app/',
        'Modules\\Procurement\\' => __DIR__.'/../Modules/Procurement/app/',
    ];

    foreach ($prefixes as $prefix => $basePath) {
        if (! str_starts_with($class, $prefix)) {
            continue;
        }

        $path = $basePath.str_replace('\\', '/', substr($class, strlen($prefix))).'.php';

        if (is_file($path)) {
            require_once $path;
        }

        return;
    }
});

require_once __DIR__.'/../Modules/Inventory/app/Providers/InventoryServiceProvider.php';
require_once __DIR__.'/../Modules/Procurement/app/Providers/ProcurementServiceProvider.php';

return [
    AppServiceProvider::class,
];
