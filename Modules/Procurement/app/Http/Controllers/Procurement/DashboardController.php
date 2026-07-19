<?php

namespace Modules\Procurement\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Modules\Procurement\Models\Delivery;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Procurement\Models\Requisition;
use Modules\Procurement\Models\Supplier;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'activePos' => PurchaseOrder::whereIn('status', ['pending', 'processing'])->count(),
            'totalPos' => PurchaseOrder::count(),
            'suppliers' => Supplier::count(),
            'activeSuppliers' => Supplier::where('status', 'active')->count(),
            'requisitions' => Requisition::count(),
            'approvedRequisitions' => Requisition::where('status', 'processing')->count(),
            'deliveries' => Delivery::count(),
        ];

        $recentPos = PurchaseOrder::with('supplier')->latest('order_date')->take(5)->get();
        $topSuppliers = Supplier::withSum('purchaseOrders', 'amount')
            ->whereHas('purchaseOrders', fn ($query) => $query->where('amount', '>', 0))
            ->orderByDesc('purchase_orders_sum_amount')
            ->take(5)
            ->get();
        $deliveries = Delivery::with(['supplier', 'purchaseOrder'])->orderBy('delivery_date')->take(5)->get();

        $poStatusCounts = PurchaseOrder::selectRaw('status, count(*) as c')->groupBy('status')->pluck('c', 'status');

        return view('procurement::procurement.partials.dashboard', compact(
            'stats', 'recentPos', 'topSuppliers', 'deliveries', 'poStatusCounts'
        ));
    }
}

