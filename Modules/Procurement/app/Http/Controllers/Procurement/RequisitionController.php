<?php

namespace Modules\Procurement\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Modules\Procurement\Models\Requisition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RequisitionController extends Controller
{
    public function index(): View
    {
        $requisitions = Requisition::latest('date_requested')->get();

        $counts = [
            'all' => $requisitions->count(),
            'pending' => $requisitions->where('status', 'pending')->count(),
            'approved' => $requisitions->where('status', 'processing')->count(),
            'rejected' => $requisitions->where('status', 'rejected')->count(),
        ];

        // Next requisition sequence, derived from the highest existing
        // "REQ-YYYY-####" number, so the "+ New Requisition" form always
        // auto-fills the true next number instead of a hardcoded guess.
        $nextReqSeq = ($requisitions->pluck('req_number')
            ->map(fn (string $n) => (int) preg_replace('/\D/', '', substr($n, -4)))
            ->max() ?? 0) + 1;

        return view('procurement::procurement.partials.requisition', compact('requisitions', 'counts', 'nextReqSeq'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'rq' => ['required', 'string', 'max:255'],
            'requester' => ['required', 'string', 'max:255'],
            'dept' => ['required', 'string', 'max:255'],
            'dateReq' => ['required', 'date'],
            'supplier' => ['required', 'string', 'max:255'],
            'item' => ['required', 'string', 'max:255'],
            'supplier_item' => ['nullable', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:1'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'uom' => ['nullable', 'string', 'max:50'],
            'notes' => ['nullable', 'string'],
        ]);

        $requisition = Requisition::create([
            'req_number' => $data['rq'],
            'item' => $data['item'],
            'qty' => (int) $data['qty'],
            'amount' => (float) ($data['amount'] ?? 0),
            'uom' => $data['uom'] ?? null,
            'delivery_status' => 'pending',
            'department' => $data['dept'],
            'requested_by' => $data['requester'],
            'status' => 'pending',
            'date_requested' => $data['dateReq'],
            'notes' => $this->buildRequisitionNotes($data),
        ]);

        $requisition->items()->create([
            'supplier_product_id' => null,
            'name' => $data['item'],
            'qty' => (int) $data['qty'],
            'uom' => $data['uom'] ?? null,
            'unit_price' => $data['qty'] > 0 ? (float) (($data['amount'] ?? 0) / $data['qty']) : 0,
            'amount' => (float) ($data['amount'] ?? 0),
        ]);

        return response()->json([
            'success' => true,
            'data' => $requisition,
            'delete_url' => route('procurement.requisitions.destroy', $requisition),
        ], 201);
    }

    public function update(Request $request, Requisition $requisition): JsonResponse
    {
        $data = $request->validate([
            'ref' => ['required', 'string', 'max:255'],
            'requester' => ['required', 'string', 'max:255'],
            'dept' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'supplier' => ['nullable', 'string', 'max:255'],
            'item' => ['required', 'string', 'max:255'],
            'supplier_item' => ['nullable', 'string', 'max:255'],
            'qty' => ['required', 'integer', 'min:1'],
            'amount' => ['nullable', 'numeric', 'min:0'],
            'uom' => ['nullable', 'string', 'max:50'],
            'delivery' => ['nullable', 'string', 'max:255'],
            'status' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
        ]);

        $requisition->update([
            'req_number' => $data['ref'],
            'item' => $data['item'],
            'qty' => (int) $data['qty'],
            'amount' => (float) ($data['amount'] ?? $requisition->amount ?? 0),
            'uom' => $data['uom'] ?? null,
            'delivery_status' => $this->normalizeDeliveryStatus($data['delivery'] ?? null),
            'department' => $data['dept'],
            'requested_by' => $data['requester'],
            'status' => $this->normalizeStatus($data['status'] ?? null),
            'date_requested' => $data['date'],
            'notes' => $this->buildRequisitionNotes($data, $requisition->notes),
        ]);

        $item = $requisition->items()->first();
        if ($item) {
            $item->update([
                'name' => $data['item'],
                'qty' => (int) $data['qty'],
                'uom' => $data['uom'] ?? null,
                'unit_price' => $data['qty'] > 0 ? (float) (($data['amount'] ?? 0) / $data['qty']) : 0,
                'amount' => (float) ($data['amount'] ?? $requisition->amount ?? 0),
            ]);
        }

        return response()->json(['success' => true, 'data' => $requisition]);
    }

    public function destroy(Requisition $requisition): JsonResponse
    {
        $requisition->delete();

        return response()->json(['success' => true]);
    }

    private function buildRequisitionNotes(array $data, ?string $existing = null): string
    {
        $notes = trim((string) ($data['notes'] ?? ''));
        $supplier = trim((string) ($data['supplier'] ?? ''));
        $supplierItem = trim((string) ($data['supplier_item'] ?? ''));

        $segments = [];
        if ($existing) {
            $segments[] = $existing;
        }
        if ($supplier !== '') {
            $segments[] = 'Supplier: '.$supplier;
        }
        if ($supplierItem !== '') {
            $segments[] = 'Item: '.$supplierItem;
        }
        if ($notes !== '') {
            $segments[] = $notes;
        }

        return implode(' | ', array_filter($segments));
    }

    private function normalizeDeliveryStatus(?string $value): string
    {
        return match (strtolower((string) $value)) {
            'in transit' => 'intransit',
            'in-transit' => 'intransit',
            'delivered' => 'delivered',
            'delayed' => 'delayed',
            'scheduled' => 'pending',
            'shipment' => 'shipment',
            default => 'pending',
        };
    }

    private function normalizeStatus(?string $value): string
    {
        return match (strtolower((string) $value)) {
            'approved' => 'processing',
            'rejected' => 'cancel',
            'pending' => 'pending',
            'completed', 'complete' => 'completed',
            default => 'pending',
        };
    }
}

