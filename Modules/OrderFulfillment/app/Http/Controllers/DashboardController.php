<?php

namespace Modules\OrderFulfillment\Http\Controllers;

use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ---- Stats row ----
        $ordersReceivedToday = DB::connection('order_fulfillment')->table('orders')->where('status', 'NEW')->count();
        $inPackingCount      = DB::connection('order_fulfillment')->table('orders')->where('status', 'PACKING')->count();
        $shippedTodayCount   = DB::connection('order_fulfillment')->table('orders')->where('status', 'SHIPPED')->count();
        $deliveredCount      = DB::connection('order_fulfillment')->table('orders')->where('status', 'DELIVERED')->count();
        $totalOrders         = DB::connection('order_fulfillment')->table('orders')->count();
        $onTimeRate          = $totalOrders > 0 ? round(($deliveredCount / $totalOrders) * 100) : 0;

        // ---- Board columns ----
        // The ORDERS column acts as a running log of every order, so it keeps
        // showing an order even after it moves on to packing/shipped/etc.
        $newOrders       = DB::connection('order_fulfillment')->table('orders')->orderByDesc('created_at')->get();
        $packingOrders   = DB::connection('order_fulfillment')->table('orders')->where('status', 'PACKING')->get();
        $shippedOrders   = DB::connection('order_fulfillment')->table('orders')->where('status', 'SHIPPED')->get();
        $cancelledOrders = DB::connection('order_fulfillment')->table('orders')->where('status', 'CANCELLED')->get();

        // ---- Sidebar ----.
        // Alerts should only reflect brand-new orders, not the full order log above.
        $alerts = $newOrders->where('status', 'NEW')->values();

        // Activity feed: packing + shipped + cancelled orders together, newest first.
        $activity = $packingOrders
            ->map(function ($order) {
                $order->activity_icon    = 'ÃƒÂ°Ã…Â¸Ã¢â‚¬Å“Ã‚Â¦';
                $order->activity_message = "Order {$order->id} moved to packing";
                $order->activity_time    = $order->updated_at ?? $order->created_at ?? null;
                return $order;
            })
            ->concat($shippedOrders->map(function ($order) {
                $order->activity_icon    = 'ÃƒÂ°Ã…Â¸Ã…Â¡Ã…Â¡';
                $order->activity_message = "Order {$order->id} has been shipped";
                $order->activity_time    = $order->updated_at ?? $order->created_at ?? null;
                return $order;
            }))
            ->concat($cancelledOrders->map(function ($order) {
                $order->activity_icon    = 'ÃƒÂ¢Ã‚ÂÃ…â€™';
                $order->activity_message = "Order {$order->id} has been cancelled";
                $order->activity_time    = $order->updated_at ?? $order->created_at ?? null;
                return $order;
            }))
            ->sortByDesc('activity_time')
            ->values();

        return view('order-fulfillment::dashboard', compact(
            'ordersReceivedToday',
            'inPackingCount',
            'shippedTodayCount',
            'deliveredCount',
            'totalOrders',
            'onTimeRate',
            'newOrders',
            'packingOrders',
            'shippedOrders',
            'cancelledOrders',
            'alerts',
            'activity'
        ));
    }
}
