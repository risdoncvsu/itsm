<?php

use App\Models\InventoryDeptStockLevel;
use App\Models\ManufacturingMachine;
use App\Models\ManufacturingWorkOrder;
use App\Models\FulfillmentShipment;
use App\Models\FulfillmentPackingMaterial;
use App\Models\ComplianceRisk;
use App\Models\ItsmTicket;
use App\Models\ProcurementOrder;
use App\Models\FinanceDeptInvoice;

use App\Http\Controllers\AIChatController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AIInsightsController;
use App\Http\Controllers\BusinessIntelligenceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SyncController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Services\DataService;
use App\Services\Departments\EcommerceService;
use App\Services\Departments\FinanceService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\ManufacturingService;
use App\Services\Departments\ProcurementService;
use App\Services\Departments\FulfillmentService;
use App\Services\Departments\ComplianceService;
use App\Services\Departments\ItsmService;
use App\Services\Departments\BiService;

// ============================================================
// LIVE MONITOR API
// ============================================================
Route::get('/api/live-feed', function () {
    $financeService = app(FinanceService::class);
    $inventoryService = app(InventoryService::class);
    $manufacturingService = app(ManufacturingService::class);
    $fulfillmentService = app(FulfillmentService::class);
    $complianceService = app(ComplianceService::class);
    $procurementService = app(ProcurementService::class);
    $itsmService = app(ItsmService::class);

    $alerts = [];
    $critical = $warning = $info = 0;

    $latestTimestamp = function ($model, $column = 'updated_at') {
        try {
            $record = $model::latest($column)->first();
            return $record && $record->$column ? $record->$column->toIso8601String() : now()->toIso8601String();
        } catch (\Throwable) {
            return now()->toIso8601String();
        }
    };

    // Inventory — low stock
    $lowStock = $inventoryService->lowStockCount();
    if ($lowStock > 0) {
        $critical++;
        $alerts[] = [
            'severity' => 'critical',
            'icon' => 'alert-triangle',
            'department' => 'Inventory',
            'title' => "{$lowStock} Items Low Stock",
            'description' => 'Stock levels have fallen below reorder thresholds.',
            'timestamp' => $latestTimestamp(InventoryDeptStockLevel::class),
            'metrics' => [
                ['label' => 'Low Stock', 'value' => $lowStock],
                ['label' => 'Out of Stock', 'value' => $inventoryService->outOfStockCount()],
            ],
        ];
    }

    // Manufacturing — machines down
    $down = $manufacturingService->machinesDownCount();
    if ($down > 0) {
        $warning++;
        $alerts[] = [
            'severity' => 'warning',
            'icon' => 'cpu',
            'department' => 'Manufacturing',
            'title' => "{$down} Machines Down",
            'description' => 'Production equipment offline or under maintenance.',
            'timestamp' => $latestTimestamp(ManufacturingMachine::class, 'last_status_change_at'),
            'metrics' => [
                ['label' => 'Down', 'value' => $down],
                ['label' => 'Completion Rate', 'value' => $manufacturingService->completionRatePercent() . '%'],
            ],
        ];
    }

    // Manufacturing — overdue builds
    $overdue = $manufacturingService->overdueBuildsCount();
    if ($overdue > 0) {
        $critical++;
        $alerts[] = [
            'severity' => 'critical',
            'icon' => 'clock-alert',
            'department' => 'Manufacturing',
            'title' => "{$overdue} Overdue Builds",
            'description' => 'Work orders past their due date.',
            'timestamp' => $latestTimestamp(ManufacturingWorkOrder::class),
            'metrics' => [
                ['label' => 'Overdue', 'value' => $overdue],
                ['label' => 'QC Pass Rate', 'value' => $manufacturingService->qcPassRatePercent() . '%'],
            ],
        ];
    }

    // Procurement — open orders
    $openPO = $procurementService->openOrdersCount();
    if ($openPO > 0) {
        $info++;
        $alerts[] = [
            'severity' => 'info',
            'icon' => 'file-text',
            'department' => 'Procurement',
            'title' => "{$openPO} Open Purchase Orders",
            'description' => 'Active purchase orders awaiting delivery or approval.',
            'timestamp' => $latestTimestamp(ProcurementOrder::class, 'created_at'),
            'metrics' => [
                ['label' => 'Open POs', 'value' => $openPO],
                ['label' => 'Total Value', 'value' => '₱' . number_format($procurementService->openOrdersValue())],
            ],
        ];
    }

    // Fulfillment — delayed shipments
    $delayed = $fulfillmentService->delayedShipmentsCount();
    if ($delayed && $delayed > 0) {
        $warning++;
        $alerts[] = [
            'severity' => 'warning',
            'icon' => 'truck',
            'department' => 'Fulfillment',
            'title' => "{$delayed} Delayed Shipments",
            'description' => 'Shipments past their due date.',
            'timestamp' => $latestTimestamp(FulfillmentShipment::class),
            'metrics' => [
                ['label' => 'Delayed', 'value' => $delayed],
                ['label' => 'Fulfillment Rate', 'value' => $fulfillmentService->fulfillmentRatePercent() . '%'],
            ],
        ];
    }

    // Fulfillment — low packing materials
    $lowPacking = $fulfillmentService->lowStockPackingMaterialsCount();
    if ($lowPacking > 0) {
        $warning++;
        $alerts[] = [
            'severity' => 'warning',
            'icon' => 'box',
            'department' => 'Fulfillment',
            'title' => "{$lowPacking} Packing Materials Low",
            'description' => 'Packing supplies below reorder threshold.',
            'timestamp' => $latestTimestamp(FulfillmentPackingMaterial::class),
            'metrics' => [
                ['label' => 'Low Stock', 'value' => $lowPacking],
                ['label' => 'Pending Orders', 'value' => $fulfillmentService->pendingOrdersCount()],
            ],
        ];
    }

    // Compliance — open risks
    $openRisks = $complianceService->openRisksCount();
    if ($openRisks > 0) {
        $critical++;
        $alerts[] = [
            'severity' => 'critical',
            'icon' => 'shield',
            'department' => 'Compliance',
            'title' => "{$openRisks} Open Compliance Risks",
            'description' => 'Active risks requiring review.',
            'timestamp' => $latestTimestamp(ComplianceRisk::class, 'identified_date'),
            'metrics' => [
                ['label' => 'Open Risks', 'value' => $openRisks],
                ['label' => 'High Severity', 'value' => $complianceService->highSeverityRisksCount()],
            ],
        ];
    }

    // ITSM — open tickets
    $openTickets = $itsmService->openTicketsCount();
    if ($openTickets > 0) {
        $info++;
        $alerts[] = [
            'severity' => 'info',
            'icon' => 'ticket',
            'department' => 'ITSM',
            'title' => "{$openTickets} Open Tickets",
            'description' => 'Active IT service tickets requiring attention.',
            'timestamp' => $latestTimestamp(ItsmTicket::class, 'opened_at'),
            'metrics' => [
                ['label' => 'Open', 'value' => $openTickets],
                ['label' => 'Critical', 'value' => $itsmService->criticalTicketsCount()],
            ],
        ];
    }

    // Finance — overdue payments
    $overduePayments = $financeService->overduePaymentsCount();
    if ($overduePayments > 0) {
        $warning++;
        $alerts[] = [
            'severity' => 'warning',
            'icon' => 'dollar-sign',
            'department' => 'Finance',
            'title' => "{$overduePayments} Overdue Invoices",
            'description' => 'Payments past due.',
            'timestamp' => $latestTimestamp(FinanceDeptInvoice::class),
            'metrics' => [
                ['label' => 'Overdue', 'value' => $overduePayments],
                ['label' => 'Total Due', 'value' => '₱' . number_format($financeService->overduePaymentsTotal())],
            ],
        ];
    }

    usort($alerts, fn($a, $b) => strtotime($b['timestamp']) - strtotime($a['timestamp']));

    return response()->json([
        'alerts' => $alerts,
        'summary' => ['critical' => $critical, 'warning' => $warning, 'info' => $info],
    ]);
});

// ============================================================
// PAGES
// ============================================================
Route::redirect('/', '/signin')->name('home');
Route::get('/signin', fn() => view('signIn'))->name('signin');
Route::get('/contactus', fn() => view('contactus'))->name('contactus');
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
Route::get('/api/sales-forecast', [DashboardController::class, 'salesForecast'])->name('dashboard.sales-forecast');
Route::get('/ai-insights', [AIInsightsController::class, 'index'])->name('ai-insights');
Route::get('/live-monitor', fn() => view('live-monitor'))->name('live-monitor');

// Department Analytics
Route::get('/department-analytics', fn() => view('department-analytics', [
    'departments' => DataService::getDepartmentList(),
]))->name('department-analytics');

// Department Analytics API
Route::get('/api/department/{dept}', function ($dept) {
    $financeService = app(FinanceService::class);
    $inventoryService = app(InventoryService::class);
    $ecommerceService = app(EcommerceService::class);
    $manufacturingService = app(ManufacturingService::class);
    $procurementService = app(ProcurementService::class);
    $fulfillmentService = app(FulfillmentService::class);
    $itsmService = app(ItsmService::class);

    return response()->json(match ($dept) {
        // Note: ecommerce_dept_* is a product catalog, not an orders
        // ledger — there's no revenue/units-sold data to chart, so
        // these are catalog-based proxies. See EcommerceService's
        // docblock for the full explanation.
        'ecommerce' => array_merge($ecommerceService->getSnapshot(), [
            'chart1' => ['type' => 'line', 'label' => 'New Listings Value (7d)', 'data' => $ecommerceService->catalogGrowthTrend(7)],
            'chart2' => ['type' => 'bar', 'barDirection' => 'horizontal', 'label' => 'Top Products by Price', 'data' => collect($ecommerceService->topProducts(5))->map(fn($row) => ['label' => $row['product_name'], 'value' => $row['price']])->toArray()],
        ]),
        'inventory' => array_merge(
            $inventoryService->getSnapshot(),
            [
                'chart1' => ['type' => 'doughnut', 'label' => 'Stock by Category', 'data' => DB::table('inventory_dept_items')->join('inventory_dept_categories', 'inventory_dept_items.source_category_id', '=', 'inventory_dept_categories.source_id')->selectRaw("inventory_dept_categories.name as category, COUNT(*) as count")->groupBy('inventory_dept_categories.name')->get()->map(fn($r) => ['label' => $r->category, 'value' => $r->count])->values()->toArray()],
                'chart2' => ['type' => 'bar', 'barDirection' => 'horizontal', 'label' => 'Stock by Warehouse', 'data' => DB::table('inventory_dept_stock_levels')->join('inventory_dept_warehouses', 'inventory_dept_stock_levels.source_warehouse_id', '=', 'inventory_dept_warehouses.source_id')->selectRaw("inventory_dept_warehouses.name as warehouse, SUM(quantity_on_hand) as total")->groupBy('inventory_dept_warehouses.name')->get()->map(fn($r) => ['label' => $r->warehouse, 'value' => $r->total])->values()->toArray()],
            ]
        ),
        'manufacturing' => array_merge($manufacturingService->getSnapshot(), [
            'chart1' => ['type' => 'doughnut', 'label' => 'Machine Status', 'data' => collect($manufacturingService->machineStatusBreakdown())->map(fn($c, $s) => ['label' => ucfirst($s), 'value' => $c])->values()->toArray()],
            'chart2' => ['type' => 'doughnut', 'label' => 'Work Order Status', 'data' => collect($manufacturingService->workOrderStatusBreakdown())->map(fn($c, $s) => ['label' => $s, 'value' => $c])->values()->toArray()],
        ]),
        'procurement' => array_merge($procurementService->getSnapshot(), [
            'chart1' => ['type' => 'doughnut', 'label' => 'Orders by Status', 'data' => collect($procurementService->ordersByStatus())->map(fn($c, $s) => ['label' => ucfirst($s), 'value' => $c])->values()->toArray()],
            'chart2' => ['type' => 'bar', 'label' => 'Open Orders Overview', 'data' => [['label' => 'Open Orders', 'value' => $procurementService->openOrdersCount()], ['label' => 'Expedited', 'value' => $procurementService->expeditedOrdersCount()]]],
        ]),
        'finance' => array_merge(
            $financeService->getSnapshot(),
            [
                'chart1' => ['type' => 'line', 'label' => 'Revenue Trend (7d)', 'data' => $financeService->revenueTrend(7)],
                'chart2' => [
                    'type' => 'doughnut',
                    'label' => 'Expense Breakdown',
                    'data' => (function () {
                            $expenses = DB::table('finance_dept_expenses')->latest('id')->first();
                            if (!$expenses)
                                return [];
                            return [
                            ['label' => 'Manufacturing', 'value' => (float) ($expenses->manufacturing ?? 0)],
                            ['label' => 'Procurement', 'value' => (float) ($expenses->procurement ?? 0)],
                            ['label' => 'Inventory', 'value' => (float) ($expenses->inventory ?? 0)],
                            ['label' => 'Fulfillment', 'value' => (float) ($expenses->order_fulfillment ?? 0)],
                            ];
                        })()
                ],
                'expense_summary' => (function () {
                        $expenses = DB::table('finance_dept_expenses')->latest('id')->first();
                        if (!$expenses)
                            return null;
                        return [
                        'manufacturing' => (float) ($expenses->manufacturing ?? 0),
                        'procurement' => (float) ($expenses->procurement ?? 0),
                        'inventory' => (float) ($expenses->inventory ?? 0),
                        'order_fulfillment' => (float) ($expenses->order_fulfillment ?? 0),
                        'total_expenses' => (float) ($expenses->total_expenses ?? 0),
                        ];
                    })(),
            ]
        ),
        'fulfillment' => array_merge($fulfillmentService->getSnapshot() ?: ['pending_orders' => $fulfillmentService->pendingOrdersCount()], [
            'chart1' => ['type' => 'bar', 'label' => 'Fulfillment Overview', 'data' => [['label' => 'Pending Orders', 'value' => $fulfillmentService->pendingOrdersCount()], ['label' => 'Total Orders', 'value' => $fulfillmentService->totalOrdersCount()], ['label' => 'Total Shipments', 'value' => $fulfillmentService->totalShipmentsCount()]]],
            'chart2' => ['type' => 'bar', 'barDirection' => 'horizontal', 'label' => 'Low Stock Materials', 'data' => collect($fulfillmentService->lowStockPackingMaterials())->map(fn($m) => ['label' => $m['name'], 'value' => $m['stock_qty']])->values()->toArray()],
        ]),
        'itsm' => array_merge(
            $itsmService->getSnapshot(),
            [
                'chart1' => [
                    'type' => 'doughnut',
                    'label' => 'Resolution Status',
                    'data' => [
                        ['label' => 'Open', 'value' => $itsmService->openTicketsCount()],
                        ['label' => 'Resolved', 'value' => max(0, (DB::table('itsm_tickets')->count() ?? 0) - $itsmService->openTicketsCount())],
                    ]
                ],
                'chart2' => ['type' => 'bar', 'label' => 'Ticket Overview', 'data' => [['label' => 'Open', 'value' => $itsmService->openTicketsCount()], ['label' => 'Critical', 'value' => $itsmService->criticalTicketsCount()]]],
            ]
        ),
        'bi' => array_merge(
            app(BiService::class)->getSnapshot(),
            [
                'chart1' => [
                    'type' => 'doughnut',
                    'label' => 'Department Connections',
                    'data' => [
                        ['label' => 'Connected', 'value' => app(BiService::class)->connectedDepartmentsCount()],
                        ['label' => 'Pending', 'value' => app(BiService::class)->totalDepartmentsCount() - app(BiService::class)->connectedDepartmentsCount()],
                    ]
                ],
                'chart2' => ['type' => 'bar', 'barDirection' => 'horizontal', 'label' => 'Records by Department', 'data' => collect(app(BiService::class)->recordsByDepartment())->map(fn($count, $dept) => ['label' => $dept, 'value' => $count])->values()->toArray()],
            ]
        ),
        default => ['title' => $dept, 'desc' => 'No data available', 'chart1' => null, 'chart2' => null],
    });
});

Route::post('/api/sync-all', [SyncController::class, 'syncAll'])->name('sync.all');

Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login');

Route::prefix('nexora-ai')->name('ai.')->group(function () {
    Route::get('/current-report', [AIController::class, 'current'])->name('current');
    Route::post('/refresh', [AIController::class, 'refresh'])->name('refresh');
    Route::post('/chat', [AIChatController::class, 'respond'])->name('chat');
});

Route::get('/', fn() => redirect()->route('bi.dashboard'));
Route::middleware('bi.access')->name('bi.')->group(function (): void {
    Route::get('/dashboard', [BusinessIntelligenceController::class, 'dashboard'])->name('dashboard');
    Route::get('/department-analytics', [BusinessIntelligenceController::class, 'departmentAnalytics'])->name('department-analytics');
    Route::get('/live-monitor', [BusinessIntelligenceController::class, 'liveMonitor'])->name('live-monitor');
    Route::get('/ai-insights', [BusinessIntelligenceController::class, 'aiInsights'])->name('ai-insights');
    Route::post('/api/ai/chat', [BusinessIntelligenceController::class, 'aiChat'])->name('ai.chat');

    Route::get('/api/live-feed', [BusinessIntelligenceController::class, 'liveFeed'])->name('live-feed');
    Route::get('/api/department/{department}', [BusinessIntelligenceController::class, 'departmentData'])
        ->whereIn('department', ['finance', 'inventory', 'procurement', 'manufacturing', 'fulfillment', 'ecommerce'])
        ->name('department-data');
    Route::get('/api/sales-forecast', [BusinessIntelligenceController::class, 'salesForecast'])->name('sales-forecast');
});
