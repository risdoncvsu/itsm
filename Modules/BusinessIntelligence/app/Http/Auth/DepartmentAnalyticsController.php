<?php

namespace App\Http\Controllers;

use App\Services\Departments\ComplianceService;
use App\Services\Departments\EcommerceService;
use App\Services\Departments\FinanceService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\ItsmService;
use App\Services\Departments\ManufacturingService;
use App\Services\Departments\ProcurementService;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;

class DepartmentAnalyticsController extends Controller
{
    public function __construct(
        protected FinanceService $financeService,
        protected InventoryService $inventoryService,
        protected ManufacturingService $manufacturingService,
        protected ProcurementService $procurementService,
        protected ComplianceService $complianceService,
        protected ItsmService $itsmService,
        protected EcommerceService $ecommerceService,
    ) {
    }

    public function index(): View
    {
        return view('department-analytics', [
            'departments' => [
                'finance' => $this->financeTab(),
                'inventory' => $this->inventoryTab(),
                'manufacturing' => $this->manufacturingTab(),
                'procurement' => $this->procurementTab(),
                'itsm' => $this->itsmTab(),
                'ecommerce' => $this->ecommerceTab(),
            ],
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    protected function financeTab(): array
    {
        $finance = Cache::remember('deptanalytics_finance', 60, fn() => $this->financeService->getSnapshot());

        return [
            'title' => 'Finance & Accounting',
            'desc' => 'Revenue, expenses, and payment health across the business.',
            'stats' => [
                ['icon' => 'dollar-sign', 'label' => 'Total Revenue', 'value' => $this->money($finance['revenue']), 'change' => '', 'cls' => ''],
                ['icon' => 'trending-up', 'label' => 'Profit Margin', 'value' => $finance['profit_margin'] . '%', 'change' => '', 'cls' => ''],
                ['icon' => 'credit-card', 'label' => 'Total Expenses', 'value' => $this->money($finance['expenses']), 'change' => '', 'cls' => ''],
                ['icon' => 'alert-circle', 'label' => 'Overdue Payments', 'value' => $this->money($finance['overdue_payments']), 'change' => $finance['overdue_count'] . ' invoices', 'cls' => $finance['overdue_count'] > 0 ? 'change-down' : 'change-up'],
            ],
            'leftTitle' => 'Revenue Trend (7 Days)',
            'rightTitle' => 'Revenue by Category',
            'bottomCards' => [
                [
                    'title' => 'Revenue by Category',
                    'type' => 'table',
                    'headers' => ['Category', 'Total'],
                    'rows' => collect($finance['revenue_by_category'])
                        ->map(fn($row) => [$row['category'], $this->money($row['total'])])
                        ->toArray(),
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function inventoryTab(): array
    {
        $inventory = Cache::remember('deptanalytics_inventory', 60, fn() => $this->inventoryService->getSnapshot());

        return [
            'title' => 'Inventory & Warehouse',
            'desc' => 'Stock levels and warehouse capacity metrics.',
            'stats' => [
                ['icon' => 'package', 'label' => 'Total SKUs', 'value' => number_format($inventory['total_skus']), 'change' => '', 'cls' => ''],
                ['icon' => 'alert-triangle', 'label' => 'Low Stock Items', 'value' => (string) $inventory['low_stock_count'], 'change' => '', 'cls' => $inventory['low_stock_count'] > 0 ? 'change-down' : 'change-up'],
                ['icon' => 'x-circle', 'label' => 'Out of Stock', 'value' => (string) $inventory['out_of_stock_count'], 'change' => '', 'cls' => $inventory['out_of_stock_count'] > 0 ? 'change-down' : 'change-up'],
                ['icon' => 'warehouse', 'label' => 'Inventory Value', 'value' => $this->money($inventory['inventory_value']), 'change' => '', 'cls' => ''],
            ],
            'leftTitle' => 'Inventory Value by Category',
            'rightTitle' => 'Low Stock Items',
            'bottomCards' => [
                [
                    'title' => 'Low Stock Alerts',
                    'type' => 'table',
                    'headers' => ['Item', 'Stock', 'Threshold'],
                    'rows' => collect($inventory['low_stock_items'])
                        ->map(fn($row) => [$row['name'], $row['quantity_on_hand'], $row['reorder_threshold']])
                        ->toArray(),
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function manufacturingTab(): array
    {
        $manufacturing = Cache::remember('deptanalytics_manufacturing', 60, fn() => $this->manufacturingService->getSnapshot());
        $statusBreakdown = $manufacturing['machine_status'];

        return [
            'title' => 'Manufacturing & Production',
            'desc' => 'Machine status, downtime, and production output.',
            'stats' => [
                ['icon' => 'gauge', 'label' => 'Production Rate', 'value' => $manufacturing['production_rate_percent'] . '%', 'change' => '', 'cls' => ''],
                ['icon' => 'alert-triangle', 'label' => 'Machines Down', 'value' => (string) $manufacturing['machines_down'], 'change' => '', 'cls' => $manufacturing['machines_down'] > 0 ? 'change-down' : 'change-up'],
                ['icon' => 'clock', 'label' => 'Downtime (min today)', 'value' => (string) $manufacturing['total_downtime_minutes'], 'change' => '', 'cls' => ''],
                ['icon' => 'percent', 'label' => 'Defect Rate', 'value' => $manufacturing['defect_rate_percent'] . '%', 'change' => '', 'cls' => ''],
            ],
            'leftTitle' => 'Machine Status Breakdown',
            'rightTitle' => 'Production Rate',
            'bottomCards' => [
                [
                    'title' => 'Machine Status',
                    'type' => 'table',
                    'headers' => ['Status', 'Machines'],
                    'rows' => collect($statusBreakdown)
                        ->map(fn($count, $status) => [ucfirst($status), $count])
                        ->values()
                        ->toArray(),
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function procurementTab(): array
    {
        $procurement = Cache::remember('deptanalytics_procurement', 60, fn() => $this->procurementService->getSnapshot());

        return [
            'title' => 'Procurement',
            'desc' => 'Purchase order pipeline and supplier spend.',
            'stats' => [
                ['icon' => 'shopping-bag', 'label' => 'Open Orders', 'value' => (string) $procurement['open_orders'], 'change' => '', 'cls' => ''],
                ['icon' => 'dollar-sign', 'label' => 'Open Orders Value', 'value' => $this->money($procurement['open_orders_value']), 'change' => '', 'cls' => ''],
                ['icon' => 'zap', 'label' => 'Expedited Orders', 'value' => (string) $procurement['expedited_orders'], 'change' => '', 'cls' => ''],
            ],
            'leftTitle' => 'Orders by Status',
            'rightTitle' => 'Open Order Value',
            'bottomCards' => [
                [
                    'title' => 'Orders by Status',
                    'type' => 'table',
                    'headers' => ['Status', 'Orders'],
                    'rows' => collect($procurement['orders_by_status'])
                        ->map(fn($count, $status) => [ucfirst(str_replace('_', ' ', $status)), $count])
                        ->values()
                        ->toArray(),
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function itsmTab(): array
    {
        $itsm = Cache::remember('deptanalytics_itsm', 60, fn() => $this->itsmService->getSnapshot());
        $compliance = Cache::remember('deptanalytics_compliance', 60, fn() => $this->complianceService->getSnapshot());

        return [
            'title' => 'ITSM, Compliance & Risk Management',
            'desc' => 'IT service management, compliance tracking, and risk assessment metrics.',
            'stats' => [
                ['icon' => 'ticket', 'label' => 'Open Tickets', 'value' => (string) $itsm['open_tickets'], 'change' => '', 'cls' => ''],
                ['icon' => 'shield', 'label' => 'Compliance Score', 'value' => $compliance['compliance_score_percent'] . '%', 'change' => '', 'cls' => ''],
                ['icon' => 'alert-triangle', 'label' => 'High Risks', 'value' => (string) $compliance['high_severity_risks'], 'change' => '', 'cls' => $compliance['high_severity_risks'] > 0 ? 'change-down' : 'change-up'],
                ['icon' => 'clock', 'label' => 'Avg Resolution', 'value' => $itsm['avg_resolution_hours'] . 'h', 'change' => '', 'cls' => ''],
            ],
            'leftTitle' => 'Ticket Volume by Priority',
            'rightTitle' => 'Risk by Severity',
            'bottomCards' => [
                [
                    'title' => 'Tickets by Priority',
                    'type' => 'table',
                    'headers' => ['Priority', 'Open Tickets'],
                    'rows' => collect($itsm['tickets_by_priority'])
                        ->map(fn($count, $priority) => [ucfirst($priority), $count])
                        ->values()
                        ->toArray(),
                ],
                [
                    'title' => 'Risks by Severity',
                    'type' => 'table',
                    'headers' => ['Severity', 'Open Risks'],
                    'rows' => collect($compliance['risks_by_severity'])
                        ->map(fn($count, $severity) => [ucfirst($severity), $count])
                        ->values()
                        ->toArray(),
                ],
            ],
        ];
    }

    /**
     * E-Commerce & CRM tab. Backed by EcommerceService, which reads the
     * ecommerce_dept_* product-catalog tables (gaming laptops, prebuilt
     * configs, configurator configs) — there is no orders/customers
     * table in the synced data, so these stats are catalog-based
     * proxies for the old order-based ones. See the docblock on
     * EcommerceService for the full explanation of each substitution.
     *
     * @return array<string, mixed>
     */
    protected function ecommerceTab(): array
    {
        $ecommerce = Cache::remember('deptanalytics_ecommerce', 60, fn() => $this->ecommerceService->getSnapshot());

        return [
            'title' => 'E-Commerce & CRM',
            'desc' => 'Catalog value, product availability, and customer ratings across the storefront.',
            'stats' => [
                ['icon' => 'dollar-sign', 'label' => 'Catalog Value', 'value' => $this->money($ecommerce['catalog_value']), 'change' => '', 'cls' => ''],
                ['icon' => 'shopping-cart', 'label' => 'Products Listed', 'value' => number_format($ecommerce['total_products']), 'change' => '', 'cls' => ''],
                ['icon' => 'x-circle', 'label' => 'Sold Out Items', 'value' => number_format($ecommerce['sold_out_count']), 'change' => '', 'cls' => $ecommerce['sold_out_count'] > 0 ? 'change-down' : 'change-up'],
                ['icon' => 'star', 'label' => 'Average Rating', 'value' => number_format($ecommerce['average_rating'], 2), 'change' => '', 'cls' => ''],
            ],
            'leftTitle' => 'New Listings Value (7 Days)',
            'rightTitle' => 'Top Products by Price',
            'bottomCards' => [
                [
                    'title' => 'Top Products by Price',
                    'type' => 'table',
                    'headers' => ['Product', 'Category', 'Price'],
                    'rows' => collect($ecommerce['top_products'])
                        ->take(5)
                        ->map(fn($row) => [$row['product_name'], $row['source'], $this->money($row['price'])])
                        ->toArray(),
                ],
            ],
        ];
    }

    protected function money(float $amount): string
    {
        return '₱' . number_format($amount, 2);
    }
}
