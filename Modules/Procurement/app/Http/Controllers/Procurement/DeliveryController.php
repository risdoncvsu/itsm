<?php

namespace Modules\Procurement\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Modules\Procurement\Models\Delivery;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Procurement\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class DeliveryController extends Controller
{
    /**
     * Requisitions live on external connections (orderfullfillment /
     * manufacturing), NOT on the procurement DB, so we look them up with
     * raw queries the same way RequisitionController does. Returns a small
     * object holding the connection name + row id, or null if not found.
     */
    private function findRequisition($purchaseOrder): ?object
    {
        if (! $purchaseOrder) {
            return null;
        }

        foreach (['orderfullfillment', 'manufacturing'] as $name) {
            try {
                $conn = DB::connection($name);
                if (! $conn->getSchemaBuilder()->hasTable('requisitions')) {
                    continue;
                }

                $row = null;
                if (! empty($purchaseOrder->requisition_reference)) {
                    $row = $conn->table('requisitions')
                        ->where('req_number', $purchaseOrder->requisition_reference)
                        ->first();
                }
                if (! $row && ! empty($purchaseOrder->requisition_id)) {
                    $row = $conn->table('requisitions')
                        ->where('id', $purchaseOrder->requisition_id)
                        ->first();
                }

                if ($row) {
                    return (object) ['connection' => $name, 'id' => $row->id];
                }
            } catch (\Exception $e) {
                // ignore broken / unavailable external connections
            }
        }

        return null;
    }

    /**
     * Update a requisition row on whichever external connection it lives on.
     * Silently skips columns/tables that don't exist so a schema mismatch
     * never crashes delivery logging.
     */
    private function updateRequisition(?object $requisition, array $values): void
    {
        if (! $requisition) {
            return;
        }

        try {
            $conn = DB::connection($requisition->connection);

            // Only keep columns that actually exist on the external table.
            $filtered = [];
            foreach ($values as $column => $value) {
                if ($conn->getSchemaBuilder()->hasColumn('requisitions', $column)) {
                    $filtered[$column] = $value;
                }
            }

            if ($filtered === []) {
                return;
            }

            if ($conn->getSchemaBuilder()->hasColumn('requisitions', 'updated_at')) {
                $filtered['updated_at'] = now();
            }

            $conn->table('requisitions')
                ->where('id', $requisition->id)
                ->update($filtered);
        } catch (\Exception $e) {
            // ignore if the external connection can't be written
        }
    }

    public function index(): View
    {
        $deliveries = Delivery::with(['supplier', 'purchaseOrder'])->orderBy('delivery_date')->get();

        $counts = [
            'all' => $deliveries->count(),
            'pending' => $deliveries->where('status', 'pending')->count(),
            'shipment' => $deliveries->where('status', 'shipment')->count(),
            'intransit' => $deliveries->whereIn('status', ['intransit', 'shipment'])->count(),
            'delivered' => $deliveries->where('status', 'delivered')->count(),
            'complete' => $deliveries->whereIn('status', ['complete', 'delivered'])->count(),
            'delayed' => $deliveries->where('status', 'delayed')->count(),
        ];

        // Next shipment sequence, derived from the highest existing
        // "SHP-#####" number, so the "+ Log Delivery" form always
        // auto-fills the true next number instead of a hardcoded guess.
        $nextShipmentSeq = ($deliveries->pluck('shipment_number')
            ->map(fn (string $n) => (int) preg_replace('/\D/', '', $n))
            ->max() ?? 0) + 1;

        return view('procurement::pages.deliveries', compact('deliveries', 'counts', 'nextShipmentSeq'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'dr' => ['required', 'string', 'max:255'],
            'po' => ['required', 'string', 'max:255'],
            'supplier' => ['required', 'string', 'max:255'],
            'delDate' => ['required', 'date'],
            'timer_minutes' => ['required', 'integer', 'min:1'],
            'items' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:1'],
            'remarks' => ['nullable', 'string'],
        ]);

        $purchaseOrder = PurchaseOrder::where('po_number', $data['po'])->first();
        $supplier = Supplier::where('name', $data['supplier'])->first();

        if (! $supplier) {
            $supplier = Supplier::create([
                'name' => $data['supplier'],
                'contact_person' => 'Pending',
                'email' => null,
                'phone' => null,
                'address' => null,
                'status' => 'active',
            ]);
        }

        $delivery = Delivery::create([
            'shipment_number' => $data['dr'],
            'purchase_order_id' => $purchaseOrder?->id,
            'supplier_id' => $supplier->id,
            'status' => 'shipment',
            'qty' => (int) $data['qty'],
            'qty_expected' => (int) $data['qty'],
            'items' => $data['items'],
            'remarks' => $data['remarks'] ?? null,
            'delivery_date' => $data['delDate'],
        ]);

        if ($purchaseOrder) {
            $purchaseOrder->update(['status' => 'processing']);

            $requisition = $this->findRequisition($purchaseOrder);
            $this->updateRequisition($requisition, ['delivery_status' => 'shipment']);
        }

        return response()->json([
            'success' => true,
            'data' => $delivery,
            'delete_url' => route('procurement.deliveries.destroy', $delivery),
        ], 201);
    }

    public function update(Request $request, Delivery $delivery): JsonResponse
    {
        $data = $request->validate([
            'ship' => ['nullable', 'string', 'max:255'],
            'po' => ['nullable', 'string', 'max:255'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'date' => ['nullable', 'date'],
            'status' => ['nullable', 'string', 'in:pending,shipment,intransit,delayed,delivered,complete,cancel'],
            'carrier' => ['nullable', 'string', 'max:255'],
            'note' => ['nullable', 'string'],
        ]);

        $purchaseOrder = $data['po']
            ? PurchaseOrder::where('po_number', $data['po'])->first()
            : $delivery->purchaseOrder;
        $supplier = $data['supplier']
            ? Supplier::where('name', $data['supplier'])->first()
            : $delivery->supplier;

        if ($data['supplier'] && ! $supplier) {
            $supplier = Supplier::create([
                'name' => $data['supplier'],
                'contact_person' => 'Pending',
                'email' => null,
                'phone' => null,
                'address' => null,
                'status' => 'active',
            ]);
        }

        $status = strtolower((string) ($data['status'] ?? $delivery->status));

        $updateData = [
            'status' => $status,
        ];

        if ($data['ship'] ?? null) {
            $updateData['shipment_number'] = $data['ship'];
        }
        if ($data['po'] ?? null) {
            $updateData['purchase_order_id'] = $purchaseOrder?->id;
        }
        if ($data['supplier'] ?? null) {
            $updateData['supplier_id'] = $supplier?->id;
        }
        if ($data['date'] ?? null) {
            $updateData['delivery_date'] = $data['date'];
        }
        if ($data['note'] ?? null) {
            $updateData['remarks'] = $data['note'];
        }

        $delivery->update($updateData);

        if ($purchaseOrder && $status === 'complete') {
            $purchaseOrder->update(['status' => 'completed']);

            $requisition = $this->findRequisition($purchaseOrder);
            $this->updateRequisition($requisition, ['status' => 'completed', 'delivery_status' => 'complete']);
        }

        return response()->json(['success' => true, 'data' => $delivery]);
    }

    public function destroy(Delivery $delivery): JsonResponse
    {
        $delivery->delete();

        return response()->json(['success' => true]);
    }
}