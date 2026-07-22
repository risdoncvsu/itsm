<?php

namespace App\Console\Commands;

use App\Http\Controllers\DepartmentAnalyticsController;
use App\Services\Departments\ComplianceService;
use App\Services\Departments\EcommerceService;
use App\Services\Departments\FinanceService;
use App\Services\Departments\FulfillmentService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\ItsmService;
use App\Services\Departments\ManufacturingService;
use App\Services\Departments\ProcurementService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class WarmDashboardCache extends Command
{
    protected $signature = 'cache:warm-dashboard';

    protected $description = 'Pre-compute dashboard + department analytics snapshots so page loads read cache instead of hitting the DB live';

    protected const TTL_SECONDS = 60;

    public function handle(
        FinanceService $finance,
        InventoryService $inventory,
        ManufacturingService $manufacturing,
        ProcurementService $procurement,
        ComplianceService $compliance,
        ItsmService $itsm,
        EcommerceService $ecommerce,
        FulfillmentService $fulfillment,
    ): int {
        $this->info('Warming dashboard cache...');

        // Same keys DashboardController already reads via Cache::remember
        Cache::put('dashboard_finance_snapshot', $finance->getSnapshot(), self::TTL_SECONDS);
        Cache::put('dashboard_inventory_snapshot', $inventory->getSnapshot(), self::TTL_SECONDS);
        Cache::put('dashboard_fulfillment_total_orders', $fulfillment->totalOrdersCount(), self::TTL_SECONDS);
        Cache::put('dashboard_fulfillment_orders_change', $fulfillment->ordersMonthOverMonthChangePercent(), self::TTL_SECONDS);
        Cache::put('dashboard_fulfillment_rate', $fulfillment->fulfillmentRatePercent(), self::TTL_SECONDS);

        foreach ([7, 30, 365] as $days) {
            Cache::put("dashboard_revenue_trend_{$days}", $finance->revenueTrend($days), self::TTL_SECONDS);
        }

        $products = $fulfillment->topProductsByUnitsSold(10);
        if ($products->isNotEmpty()) {
            $names = $products->pluck('name')->all();
            $stock = $inventory->availableStockByProductName($names);
            $avgSales = $fulfillment->averageMonthlyUnitsSoldByProduct($names);

            $topProducts = $products->map(function (array $p) use ($stock, $avgSales, $inventory) {
                $s = (float) ($stock[$p['name']] ?? 0);
                $a = (float) ($avgSales[$p['name']] ?? 0);
                return array_merge($p, $inventory->inventoryCoverage($s, $a));
            })->values()->toArray();
        } else {
            $topProducts = [];
        }
        Cache::put('dashboard_top_products', $topProducts, self::TTL_SECONDS);

        // Department Analytics tabs — currently uncached, so cache them here too
        Cache::put('deptanalytics_finance', $finance->getSnapshot(), self::TTL_SECONDS);
        Cache::put('deptanalytics_inventory', $inventory->getSnapshot(), self::TTL_SECONDS);
        Cache::put('deptanalytics_manufacturing', $manufacturing->getSnapshot(), self::TTL_SECONDS);
        Cache::put('deptanalytics_procurement', $procurement->getSnapshot(), self::TTL_SECONDS);
        Cache::put('deptanalytics_itsm', $itsm->getSnapshot(), self::TTL_SECONDS);
        Cache::put('deptanalytics_compliance', $compliance->getSnapshot(), self::TTL_SECONDS);
        Cache::put('deptanalytics_ecommerce', $ecommerce->getSnapshot(), self::TTL_SECONDS);

        $this->info('Dashboard cache warmed.');

        return self::SUCCESS;
    }
}