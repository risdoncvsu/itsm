<?php

namespace Modules\OrderFulfillment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\OrderFulfillment\Models\Order;
use Modules\OrderFulfillment\Models\Shipment;
use Modules\OrderFulfillment\Models\DeliveryMan;

class ShippingController extends Controller
{
    public function index()
    {
        // Promote anything that's been sitting at SHIPPED for 24+ hours to
        // READY_TO_SHIP. Runs here instead of a scheduled command, so it
        // only recalculates when someone actually loads this page.
        Shipment::where('status', 'SHIPPED')
            ->whereNotNull('shipped_at')
            ->where('shipped_at', '<=', now()->subDay())
            ->update(['status' => 'READY_TO_SHIP']);

        $shipments = Shipment::select(
            'shipment_id',
            'customer_name',
            'product_name',
            'status',
            'due_date',
            'address',
            'tracking_number',
            'courier',
            'amount',
            'delivery_man_id'
        )
        ->whereIn('status', [
            'SHIPPED',
            'READY_TO_SHIP',
            'OUT_FOR_DELIVERY',
            'DELAYED',
            'DELIVERED',
        ])
        ->get();

        $shippedToday = Order::whereDate('updated_at', today())
            ->where('status', 'SHIPPED')
            ->count();

        $inTransit = Order::whereDate('updated_at', today())
            ->where('status', 'OUT_FOR_DELIVERY')
            ->count();

        $delayed = Order::whereDate('updated_at', today())
            ->where('status', 'DELAYED')
            ->count();

        $delivered = Order::whereDate('updated_at', today())
            ->where('status', 'DELIVERED')
            ->count();

        $onTimeRate = $delivered
            ? round(($delivered / ($delivered + $delayed)) * 100)
            : 0;

        return view('order-fulfillment::shipping', compact(
            'shipments',
            'shippedToday',
            'inTransit',
            'delayed',
            'delivered',
            'onTimeRate'
        ));
    }

    /**
     * Available drivers for this shipment's courier ÃƒÂ¢Ã¢â€šÂ¬Ã¢â‚¬Â feeds the
     * "Assign Driver" modal.
     *
     * GET /shipping/{shipmentId}/drivers
     */
    public function drivers(string $shipmentId)
    {
        $shipment = Shipment::where('shipment_id', $shipmentId)->firstOrFail();

        $drivers = DeliveryMan::available()
            ->forCourier($shipment->courier)
            ->orderBy('name')
            ->get(['id', 'name', 'vehicle_type', 'plate_number']);

        return response()->json($drivers);
    }

    /**
     * Assign a driver to a shipment: the shipment moves to OUT_FOR_DELIVERY
     * and the driver flips to UNAVAILABLE until the shipment is delivered.
     *
     * POST /shipping/{shipmentId}/assign-driver
     */
    public function assignDriver(Request $request, string $shipmentId)
    {
        $validated = $request->validate([
            'driver_id' => 'required|string|exists:delivery_men,id',
        ]);

        $shipment = Shipment::where('shipment_id', $shipmentId)->firstOrFail();

        $driver = DeliveryMan::where('id', $validated['driver_id'])
            ->where('status', DeliveryMan::STATUS_AVAILABLE)
            ->first();

        if (! $driver) {
            return response()->json([
                'message' => 'That driver is no longer available. Please pick another.',
            ], 422);
        }

        DB::connection('order_fulfillment')->transaction(function () use ($shipment, $driver) {
            $shipment->update([
                'delivery_man_id' => $driver->id,
                'status' => 'OUT_FOR_DELIVERY',
            ]);

            $driver->update(['status' => DeliveryMan::STATUS_UNAVAILABLE]);
        });

        return response()->json([
            'message' => "{$driver->name} assigned to {$shipment->shipment_id}",
            'status' => $shipment->status,
        ]);
    }
}
