<?php

namespace Modules\Procurement\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Modules\Procurement\Models\PurchaseOrder;
use Modules\Procurement\Models\PurchaseOrderItem;
use Modules\Procurement\Models\Requisition;
use Modules\Procurement\Models\RequisitionItem;
use Modules\Procurement\Models\Supplier;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PurchaseOrderController extends Controller
{
    public function index(): View
    {
        $purchaseOrders = PurchaseOrder::with('supplier')->latest('order_date')->get();

        $counts = [
            'all' => $purchaseOrders->count(),
            'pending' => $purchaseOrders->where('status', 'pending')->count(),
            'processing' => $purchaseOrders->where('status', 'processing')->count(),
            'cancel' => $purchaseOrders->where('status', 'cancel')->count(),
            'completed' => $purchaseOrders->where('status', 'completed')->count(),
        ];

        // Next PO number's sequence, derived from the highest existing
        // "NXR-PO-#####" number, so the "+ New PO" form always auto-fills
        // the true next number instead of a hardcoded guess.
        $nextPoSeq = ($purchaseOrders->pluck('po_number')
            ->map(fn (string $n) => (int) preg_replace('/\D/', '', $n))
            ->max() ?? 0) + 1;

        return view('procurement::procurement.partials.purchase-orders', compact('purchaseOrders', 'counts', 'nextPoSeq'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'po' => ['required', 'string', 'max:255'],
            'supplier' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:1'],
            'orderDate' => ['required', 'date'],
            'expected' => ['required', 'date'],
            'item' => ['required', 'string', 'max:255'],
            'uom' => ['required', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:0'],
            'remarks' => ['nullable', 'string'],
            'req' => ['nullable', 'string', 'max:255'],
            'createdBy' => ['nullable', 'string', 'max:255'],
        ]);

        $supplier = Supplier::where('name', $data['supplier'])->first();

        if (! $supplier) {
            $supplier = Supplier::create([
                'name' => $data['supplier'],
                'contact_person' => 'Pending',
                'email' => null,
                'phone' => null,
                'address' => null,
                'category' => $data['category'],
                'status' => 'active',
            ]);
        }

        $requisition = null;
        $requisitionId = null;
        $requisitionReference = null;

        if (! empty($data['req'])) {
            $requisition = Requisition::where('req_number', $data['req'])->first();
            $requisitionId = $requisition?->id;
            $requisitionReference = $data['req'];
        }

        $remarks = $data['remarks'] ?? null;
        if (! empty($requisitionReference)) {
            $remarks = trim(($remarks ? $remarks . ' ' : '') . "(Requisition: {$requisitionReference})");
        }

        $purchaseOrder = PurchaseOrder::create([
            'po_number' => $data['po'],
            'supplier_id' => $supplier->id,
            'category' => $data['category'],
            'item' => $data['item'],
            'qty' => (int) $data['qty'],
            'uom' => $data['uom'],
            'amount' => (float) $data['amount'],
            'delivery_status' => 'pending',
            'status' => 'pending',
            'order_date' => $data['orderDate'],
            'expected_delivery_date' => $data['expected'],
            'created_by' => $data['createdBy'] ?? null,
            'remarks' => $remarks,
            'requisition_id' => $requisitionId,
            'requisition_reference' => $requisitionReference,
        ]);

        PurchaseOrderItem::create([
            'purchase_order_id' => $purchaseOrder->id,
            'requisition_item_id' => $requisition?->items()->first()?->id,
            'supplier_product_id' => null,
            'name' => $data['item'],
            'qty' => (int) $data['qty'],
            'uom' => $data['uom'],
            'unit_price' => $data['qty'] > 0 ? (float) ($data['amount'] / $data['qty']) : 0,
            'amount' => (float) $data['amount'],
        ]);

        if ($requisition) {
            $purchaseOrder->update([
                'requisition_id' => $requisition->id,
                'requisition_reference' => $requisition->req_number,
            ]);

            $requisition->update([
                'status' => 'processing',
                'delivery_status' => 'shipment',
                'amount' => (float) $purchaseOrder->amount,
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $purchaseOrder,
            'delete_url' => route('procurement.purchase-orders.destroy', $purchaseOrder),
            'update_url' => route('procurement.purchase-orders.update', $purchaseOrder),
        ], 201);
    }

    public function update(Request $request, PurchaseOrder $purchaseOrder): JsonResponse
    {
        $data = $request->validate([
            'po' => ['required', 'string', 'max:255'],
            'supplier' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:1'],
            'orderDate' => ['required', 'date'],
            'expected' => ['nullable', 'date'],
            'item' => ['required', 'string', 'max:255'],
            'uom' => ['nullable', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:0'],
            'status' => ['nullable', 'string', 'max:255'],
            'delivery' => ['nullable', 'string', 'max:255'],
            'remarks' => ['nullable', 'string'],
        ]);

        $supplier = Supplier::where('name', $data['supplier'])->first();
        if (! $supplier) {
            $supplier = Supplier::create([
                'name' => $data['supplier'],
                'contact_person' => 'Pending',
                'email' => null,
                'phone' => null,
                'address' => null,
                'category' => $data['category'],
                'status' => 'active',
            ]);
        }

        $newStatus = $this->normalizeStatus($data['status'] ?? null);

        if ($newStatus === 'cancel') {
            $purchaseOrder->deliveries()->update(['status' => 'cancel', 'stage' => 0]);
        }

        $purchaseOrder->update([
            'po_number' => $data['po'],
            'supplier_id' => $supplier->id,
            'category' => $data['category'],
            'item' => $data['item'],
            'qty' => (int) $data['qty'],
            'uom' => $data['uom'] ?? null,
            'amount' => (float) $data['amount'],
            'delivery_status' => $this->normalizeDeliveryStatus($data['delivery'] ?? null),
            'status' => $newStatus,
            'order_date' => $data['orderDate'],
            'expected_delivery_date' => $data['expected'] ?? $purchaseOrder->expected_delivery_date,
            'remarks' => $data['remarks'] ?? null,
        ]);

        $shouldCancelRequisition = $newStatus === 'cancel' || $this->normalizeDeliveryStatus($data['delivery'] ?? null) === 'cancel';
        if ($shouldCancelRequisition) {
            $requisition = $purchaseOrder->requisition
                ?? Requisition::where('req_number', trim((string) $purchaseOrder->requisition_reference))->first();
            if (! $requisition) {
                $item = $purchaseOrder->items()->with('requisitionItem.requisition')->first();
                $requisition = $item?->requisitionItem?->requisition;
            }
            if ($requisition) {
                $requisition->update(['status' => 'cancel', 'delivery_status' => 'cancel']);
            }
        }

        $item = $purchaseOrder->items()->first();
        if ($item) {
            $item->update([
                'name' => $data['item'],
                'qty' => (int) $data['qty'],
                'uom' => $data['uom'] ?? null,
                'unit_price' => $data['qty'] > 0 ? (float) ($data['amount'] / $data['qty']) : 0,
                'amount' => (float) $data['amount'],
            ]);
        }

        return response()->json(['success' => true, 'data' => $purchaseOrder]);
    }

    public function destroy(PurchaseOrder $purchaseOrder): JsonResponse
    {
        $purchaseOrder->delete();

        return response()->json(['success' => true]);
    }

    private function normalizeDeliveryStatus(?string $value): string
    {
        return match (strtolower((string) $value)) {
            'pending' => 'pending',
            'partial' => 'intransit',
            'complete' => 'complete',
            'delivered' => 'delivered',
            'in transit' => 'intransit',
            'in-transit' => 'intransit',
            'shipment' => 'shipment',
            'scheduled' => 'pending',
            'cancel' => 'cancel',
            default => 'pending',
        };
    }

    private function normalizeStatus(?string $value): string
    {
        return match (strtolower((string) $value)) {
            'approved' => 'processing',
            'rejected' => 'cancel',
            'completed' => 'completed',
            'processing' => 'processing',
            'pending' => 'pending',
            default => 'pending',
        };
    }
}

