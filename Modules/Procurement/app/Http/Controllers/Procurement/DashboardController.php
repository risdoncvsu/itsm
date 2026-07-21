<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the procurement dashboard (stat cards, category chart,
     * PO status donut, and recent deliveries preview).
     */
    public function index(Request $request)
    {
        $poCount = DB::table('purchase_orders')->count();
        $poStatusBreakdown = DB::table('purchase_orders')
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $supplierCount = DB::table('suppliers')
            ->where('status', 'active')
            ->count();

        $requisitionCount = 0;
        try {
            $requisitionConnection = DB::connection('orderfullfillment');
            if ($requisitionConnection->getSchemaBuilder()->hasTable('requisitions')) {
                $requisitionCount = $requisitionConnection->table('requisitions')->count();
            }
        } catch (\Exception $e) {
            $requisitionCount = 0;
        }

        $deliveryCount = DB::table('deliveries')->count();
        $pendingDeliveries = DB::table('deliveries')
            ->whereIn('status', ['pending', 'scheduled', 'intransit'])
            ->count();

        $recentPOs = DB::table('purchase_orders')
            ->select('id', 'po_number', 'supplier_id', 'qty', 'amount', 'status', 'priority', 'order_date', 'expected_delivery_date', 'item', 'brand')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $supplierIds = $recentPOs->pluck('supplier_id')->filter()->unique()->toArray();
        $suppliersMap = [];
        if (!empty($supplierIds)) {
            $suppliersMap = DB::table('suppliers')
                ->whereIn('id', $supplierIds)
                ->pluck('name', 'id')
                ->toArray();
        }

        $recentDeliveries = DB::table('deliveries')
            ->select('id', 'shipment_number', 'purchase_order_id', 'supplier_id', 'status', 'delivery_date', 'estimated_arrival', 'actual_arrival', 'carrier')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $deliverySupplierIds = $recentDeliveries->pluck('supplier_id')->filter()->unique()->toArray();
        $deliverySuppliersMap = [];
        if (!empty($deliverySupplierIds)) {
            $deliverySuppliersMap = DB::table('suppliers')
                ->whereIn('id', $deliverySupplierIds)
                ->pluck('name', 'id')
                ->toArray();
        }

        $spendByBrand = DB::table('purchase_orders')
            ->select('brand', DB::raw('SUM(amount) as total'))
            ->whereNotNull('brand')
            ->where('brand', '!=', '')
            ->groupBy('brand')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $totalSpend = DB::table('purchase_orders')
            ->where('status', '!=', 'cancelled')
            ->where('status', '!=', 'rejected')
            ->sum('amount');

        // Top suppliers by total PO spend — grouped/summed per supplier so the
        // same supplier never appears twice (previously missing entirely, which
        // is why the "Top Suppliers" panel always showed "No top suppliers to
        // display" no matter how much data was in the database).
        $topSuppliers = DB::table('purchase_orders')
            ->join('suppliers', 'purchase_orders.supplier_id', '=', 'suppliers.id')
            ->select('suppliers.id', 'suppliers.name', DB::raw('SUM(purchase_orders.amount) as total_spend'))
            ->where('purchase_orders.status', '!=', 'cancelled')
            ->where('purchase_orders.status', '!=', 'rejected')
            ->groupBy('suppliers.id', 'suppliers.name')
            ->orderByDesc('total_spend')
            ->limit(5)
            ->get()
            ->map(function ($supplier) {
                $supplier->formatted_total_spend = $supplier->total_spend >= 1000
                    ? '₱' . number_format($supplier->total_spend / 1000, 1) . 'k'
                    : '₱' . number_format($supplier->total_spend, 2);
                return $supplier;
            });

        $totalSpendFormatted = '₱' . number_format($totalSpend, 2);

        $spendByBrand = $spendByBrand->map(function ($item) {
            $item->formatted_total = $item->total >= 1000
                ? '₱' . number_format($item->total / 1000, 1) . 'k'
                : '₱' . number_format($item->total, 2);
            return $item;
        });

        // Low stock alerts — populated by `php artisan inventory:check-low-stock`
        // (scheduled hourly). Previously nothing on the dashboard ever read
        // this table, so there was no way to tell if the check was working.
        $lowStockAlerts = collect();
        try {
            $lowStockAlerts = DB::table('low_stock_alerts')
                ->orderBy('stock', 'asc')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get();
        } catch (\Exception $e) {
            $lowStockAlerts = collect();
        }

        return view('pages.dashboard', [
            'poCount' => $poCount,
            'poStatusBreakdown' => $poStatusBreakdown,
            'supplierCount' => $supplierCount,
            'requisitionCount' => $requisitionCount,
            'deliveryCount' => $deliveryCount,
            'pendingDeliveries' => $pendingDeliveries,
            'recentPOs' => $recentPOs,
            'suppliersMap' => $suppliersMap,
            'recentDeliveries' => $recentDeliveries,
            'deliverySuppliersMap' => $deliverySuppliersMap,
            'spendByBrand' => $spendByBrand,
            'totalSpend' => $totalSpend,
            'totalSpendFormatted' => $totalSpendFormatted,
            'topSuppliers' => $topSuppliers,
            'lowStockAlerts' => $lowStockAlerts,
        ]);
    }
}
