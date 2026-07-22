<?php

namespace App\Http\Controllers;

use App\Services\Departments\ComplianceService;
use App\Services\Departments\FinanceService;
use App\Services\Departments\FulfillmentService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\ManufacturingService;
use App\Services\Departments\ItsmService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function __construct(
        protected FinanceService $financeService,
        protected InventoryService $inventoryService,
        protected ManufacturingService $manufacturingService,
        protected ComplianceService $complianceService,
        protected FulfillmentService $fulfillmentService,
        protected ItsmService $itsmService,
    ) {
    }

    public function index(): View
    {
        $finance = Cache::remember('dashboard_finance_snapshot', 60, fn() => $this->financeService->getSnapshot());
        $inventory = Cache::remember('dashboard_inventory_snapshot', 60, fn() => $this->inventoryService->getSnapshot());
        $topProducts = Cache::remember('dashboard_top_products', 60, fn() => $this->buildTopProducts(10));

        $totalRevenue = $finance['revenue'];
        $grossProfit = $finance['revenue'] - $finance['expenses'];

        $totalOrders = Cache::remember('dashboard_fulfillment_total_orders', 60, fn() => $this->fulfillmentService->totalOrdersCount());
        $ordersChangePercent = Cache::remember('dashboard_fulfillment_orders_change', 60, fn() => $this->fulfillmentService->ordersMonthOverMonthChangePercent());

        $inventoryValue = $inventory['inventory_value'];

        $fulfillmentRate = Cache::remember('dashboard_fulfillment_rate', 60, fn() => $this->fulfillmentService->fulfillmentRatePercent());

        $kpis = [
            [
                'icon' => 'dollar-sign',
                'label' => 'Total Revenue',
                'value' => '₱' . number_format($totalRevenue, 2),
                'change' => '',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'pie-chart',
                'label' => 'Gross Profit',
                'value' => '₱' . number_format($grossProfit, 2),
                'change' => '',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'shopping-cart',
                'label' => 'Orders',
                'value' => number_format($totalOrders),
                'change' => $ordersChangePercent !== null
                    ? ($ordersChangePercent >= 0 ? '↑ ' . $ordersChangePercent . '%' : '↓ ' . abs($ordersChangePercent) . '%')
                    : '',
                'change_class' => ($ordersChangePercent !== null && $ordersChangePercent < 0) ? 'change-down' : 'change-up',
            ],
            [
                'icon' => 'package',
                'label' => 'Inventory Value',
                'value' => '₱' . number_format($inventoryValue, 2),
                'change' => '',
                'change_class' => 'change-up',
            ],
            [
                'icon' => 'truck',
                'label' => 'On-Time Delivery',
                'value' => $fulfillmentRate !== null ? $fulfillmentRate . '%' : 'N/A',
                'change' => $fulfillmentRate !== null
                    ? ''
                    : $this->fulfillmentService->pendingOrdersCount() . ' orders queued, no shipments yet',
                'change_class' => 'change-up',
            ],
        ];

        $operationalEfficiency = $this->buildOperationalEfficiency();

        return view('dashboard', [
            'kpis' => $kpis,
            'topProducts' => $topProducts,
            'operationalEfficiency' => $operationalEfficiency,
        ]);
    }

    public function salesForecast(Request $request): JsonResponse
    {
        $range = $request->query('range', '7d');
        $days = match ($range) {
            '1m' => 30,
            '1y' => 365,
            default => 7,
        };

        $trend = Cache::remember(
            "dashboard_revenue_trend_{$days}",
            60,
            fn() => $this->financeService->revenueTrend($days)
        );

        $labels = collect($trend)->map(fn($row) => Carbon::parse($row['date']));
        $sales = collect($trend)->pluck('total');

        if ($range === '1m') {
            $weeks = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            $weeklySales = [0, 0, 0, 0];
            $labels->each(function ($date, $i) use ($sales, &$weeklySales) {
                $weekIndex = min((int) floor($i / 7), 3);
                $weeklySales[$weekIndex] += $sales[$i];
            });
            return response()->json([
                'labels' => $weeks,
                'sales' => $weeklySales,
            ]);
        }

        if ($range === '1y') {
            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $monthlySales = array_fill(0, 12, 0);
            $labels->each(function ($date, $i) use ($sales, &$monthlySales) {
                $monthIndex = (int) $date->format('n') - 1;
                $monthlySales[$monthIndex] += $sales[$i];
            });
            return response()->json([
                'labels' => $monthNames,
                'sales' => $monthlySales,
                'year' => now()->year,
            ]);
        }

        return response()->json([
            'labels' => $labels->map(fn($date) => $date->format('D'))->all(),
            'sales' => $sales->all(),
        ]);
    }

    protected function buildTopProducts(int $limit = 10): array
    {
        $products = $this->fulfillmentService->topProductsByUnitsSold($limit);

        if ($products->isEmpty()) {
            return [];
        }

        $productNames = $products->pluck('name')->all();

        $availableStock = $this->inventoryService->availableStockByProductName($productNames);
        $averageMonthlySales = $this->fulfillmentService->averageMonthlyUnitsSoldByProduct($productNames);

        return $products
            ->map(function (array $product) use ($availableStock, $averageMonthlySales) {
                $stock = (float) ($availableStock[$product['name']] ?? 0);
                $avgSales = (float) ($averageMonthlySales[$product['name']] ?? 0);

                $coverage = $this->inventoryService->inventoryCoverage($stock, $avgSales);

                return array_merge($product, $coverage);
            })
            ->values()
            ->toArray();
    }

    protected function buildOperationalEfficiency(): array
    {
        $completionRate = $this->manufacturingService->completionRatePercent();
        $qualityRate = $this->manufacturingService->qcPassRatePercent();
        $overdueBuilds = $this->manufacturingService->overdueBuildsCount();
        $manufacturingHealth = round(($completionRate + $qualityRate) / 2, 1);

        $fulfillmentRate = $this->fulfillmentService->fulfillmentRatePercent();
        $delayedShipments = $this->fulfillmentService->delayedShipmentsCount();
        $hasFulfillmentData = $fulfillmentRate !== null;

        $fulfillmentHealthValue = $hasFulfillmentData ? $fulfillmentRate : null;

        [$overallStatus, $overallClass, $overallHealth] = $this->computeOverall($manufacturingHealth, $fulfillmentHealthValue);
        [$mfgStatus, $mfgClass] = $this->healthStatus($manufacturingHealth);

        if ($hasFulfillmentData) {
            [$flfStatus, $flfClass] = $this->healthStatus($fulfillmentRate);
            $flfPercent = $fulfillmentRate;
        } else {
            $flfStatus = 'No Data';
            $flfClass = 'health-yellow';
            $flfPercent = 0;
        }

        $lowStock = $this->inventoryService->lowStockCount();
        $machinesDown = $this->manufacturingService->machinesDownCount();
        $overdueBuildsRisk = $this->manufacturingService->overdueBuildsCount();
        $delayedShipmentsRisk = $this->fulfillmentService->delayedShipmentsCount() ?? 0;
        $lowPacking = $this->fulfillmentService->lowStockPackingMaterialsCount();
        $openRisks = $this->complianceService->openRisksCount();
        $highSeverityRisks = $this->complianceService->highSeverityRisksCount();
        $risksBySeverity = $this->complianceService->risksBySeverity();
        $openTickets = $this->itsmService->openTicketsCount();
        $overduePayments = $this->financeService->overduePaymentsCount();
        $openPOs = 0;

        $criticalCount = ($lowStock > 0 ? 1 : 0)
            + ($machinesDown > 0 ? 1 : 0)
            + ($overdueBuildsRisk > 0 ? 1 : 0)
            + $highSeverityRisks;

        $warningCount = ($delayedShipmentsRisk > 0 ? 1 : 0)
            + ($lowPacking > 0 ? 1 : 0)
            + ($risksBySeverity['medium'] ?? 0)
            + ($overduePayments > 0 ? 1 : 0);

        $infoCount = ($openTickets > 0 ? 1 : 0)
            + ($openPOs > 0 ? 1 : 0);

        $totalAlerts = $criticalCount + $warningCount + $infoCount;
        $totalSeverity = $totalAlerts > 0 ? $totalAlerts : 1;
        $criticalPct = (int) round(($criticalCount / $totalSeverity) * 100);
        $warningPct = (int) round(($warningCount / $totalSeverity) * 100);
        $minorPct = max(0, 100 - $criticalPct - $warningPct);

        $lowStockMaterials = $this->fulfillmentService->lowStockPackingMaterialsCount();

        $summaryText = $overallHealth >= 80
            ? "Manufacturing is running at {$completionRate}% completion with a {$qualityRate}% QC pass rate. "
            . ($hasFulfillmentData ? "Fulfillment is at {$fulfillmentRate}% on-time. Overall operations are healthy." : 'Order Fulfillment is pending initial shipments.')
            : ($overallHealth >= 60
                ? "Manufacturing is at {$completionRate}% completion. "
                . ($hasFulfillmentData ? "Fulfillment is at {$fulfillmentRate}% on-time. Some metrics need attention." : 'Fulfillment data is pending. Manufacturing requires monitoring.')
                : ($overallHealth >= 40
                    ? "Manufacturing completion has dropped to {$completionRate}%. "
                    . ($hasFulfillmentData ? "Fulfillment is at {$fulfillmentRate}%. Several metrics are below targets." : 'Fulfillment data is pending. Manufacturing needs immediate review.')
                    : "Critical: Manufacturing is at {$completionRate}% completion. "
                    . ($hasFulfillmentData ? "Fulfillment is at {$fulfillmentRate}%. Urgent action required across all operations." : 'Fulfillment data is pending. Manufacturing is in critical state.')));

        return [
            'overall' => [
                'percent' => $overallHealth,
                'status' => $overallStatus,
                'class' => $overallClass,
            ],
            'summary_text' => $summaryText,
            'manufacturing' => [
                'percent' => $manufacturingHealth,
                'health' => $mfgStatus,
                'class' => $mfgClass,
                'detail' => 'Live data synced from the Manufacturing department.',
                'metrics' => [
                    ['icon' => 'check-circle', 'label' => 'Completion Rate', 'value' => $completionRate . '%'],
                    ['icon' => 'shield-check', 'label' => 'Quality Pass Rate', 'value' => $qualityRate . '%'],
                    ['icon' => 'clock-alert', 'label' => 'Overdue Builds', 'value' => $overdueBuilds],
                ],
            ],
            'fulfillment' => [
                'percent' => $flfPercent,
                'health' => $flfStatus,
                'class' => $flfClass,
                'detail' => $hasFulfillmentData
                    ? 'Live data synced from the Order Fulfillment department.'
                    : 'No shipments recorded yet — metrics will populate once deliveries begin.',
                'metrics' => [
                    ['icon' => 'package-check', 'label' => 'Fulfillment Rate', 'value' => $hasFulfillmentData ? $fulfillmentRate . '%' : 'No data yet'],
                    ['icon' => 'clock-alert', 'label' => 'Delayed Shipments', 'value' => $delayedShipments ?? 'No data yet'],
                    ['icon' => 'box', 'label' => 'Low Stock Packing Supplies', 'value' => $lowStockMaterials],
                ],
            ],
            'risks' => [
                'total_active' => $totalAlerts,
                'alert_counts' => [
                    'critical' => $criticalCount,
                    'warning' => $warningCount,
                    'info' => $infoCount,
                ],
                'severity_breakdown' => [
                    'critical' => $criticalPct,
                    'warning' => $warningPct,
                    'minor' => $minorPct,
                ],
                'top_issues' => $this->complianceService->topIssuesByAge(5),
            ],
        ];
    }

    protected function computeOverall(float $manufacturingHealth, ?float $fulfillmentHealth): array
    {
        $overallHealth = $fulfillmentHealth === null
            ? $manufacturingHealth
            : round(($manufacturingHealth + $fulfillmentHealth) / 2, 1);

        [$status, $class] = $this->healthStatus($overallHealth);

        return [$status, $class, $overallHealth];
    }

    protected function healthStatus(float $value): array
    {
        if ($value >= 80) {
            return ['Healthy', 'health-green'];
        }

        if ($value >= 60) {
            return ['Stable', 'health-yellow'];
        }

        if ($value >= 40) {
            return ['Warning', 'health-orange'];
        }

        return ['Critical', 'health-red'];
    }
}