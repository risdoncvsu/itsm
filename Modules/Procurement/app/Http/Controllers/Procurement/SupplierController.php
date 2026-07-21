<?php

namespace Modules\Procurement\Http\Controllers\Procurement;

use App\Http\Controllers\Controller;
use Modules\Procurement\Models\Supplier;
use Modules\Procurement\Models\SupplierProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Services\ErpIntegrationService;

class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::orderBy('name')->get();

        return view('procurement::procurement.partials.suppliers', compact('suppliers'));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'product_name' => ['nullable', 'string', 'max:255'],
            'product_price' => ['nullable', 'numeric', 'min:0'],
            'product_uom' => ['nullable', 'string', 'max:50'],
            'product_qty' => ['nullable', 'integer', 'min:1'],
            'product_items' => ['nullable', 'string'],
            'products' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:active,inactive,blacklisted'],
        ]);

        $productRows = json_decode($data['products'] ?? '[]', true) ?: [];
        $productItems = [];

        $supplier = Supplier::create([
            'name' => $data['name'],
            'contact_person' => $data['contact'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'category' => $data['category'] ?? null,
            'product_items' => null,
            'status' => $data['status'] ?? 'active',
        ]);

        foreach ($productRows as $productRow) {
            if (empty($productRow['name'])) {
                continue;
            }

            $sku = trim((string) ($productRow['sku'] ?? ''));
            $unitPrice = isset($productRow['supply_price']) ? (float) $productRow['supply_price'] : 0.0;

            SupplierProduct::create([
                'supplier_id' => $supplier->id,
                'name' => $productRow['name'],
                'sku' => $sku ?: null,
                'unit_price' => $unitPrice,
                'uom' => null,
                'metadata' => null,
            ]);

            $productItems[] = trim($productRow['name'] . ($unitPrice > 0 ? ' @ â‚±' . number_format($unitPrice, 2) : ''));
        }

        if (! empty($productItems)) {
            $supplier->update(['product_items' => implode(' | ', $productItems)]);
        }

        app(ErpIntegrationService::class)->supplierChanged((int) session('employee_client_id'), $supplier->fresh());

        return response()->json([
            'success' => true,
            'data' => $supplier,
            'delete_url' => route('procurement.suppliers.destroy', $supplier),
        ], 201);
    }

    public function update(Request $request, Supplier $supplier): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'contact' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'category' => ['nullable', 'string', 'max:255'],
            'product_name' => ['nullable', 'string', 'max:255'],
            'product_price' => ['nullable', 'numeric', 'min:0'],
            'product_uom' => ['nullable', 'string', 'max:50'],
            'product_qty' => ['nullable', 'integer', 'min:1'],
            'product_items' => ['nullable', 'string'],
            'status' => ['nullable', 'string', 'in:active,inactive,blacklisted'],
            'terms' => ['nullable', 'string', 'max:255'],
            'lastActivity' => ['nullable', 'string', 'max:255'],
        ]);

        $productItems = $data['product_items'] ?? '';
        if (! empty($data['product_name'])) {
            $parts = [];
            $parts[] = $data['product_name'];
            if (! empty($data['product_qty'])) {
                $parts[] = "Ã— {$data['product_qty']}";
            }
            if (! empty($data['product_uom'])) {
                $parts[] = $data['product_uom'];
            }
            if (! empty($data['product_price'])) {
                $parts[] = "@ â‚±" . number_format($data['product_price'], 2);
            }
            $line = implode(' ', array_filter($parts));
            $productItems = trim($line . ($productItems ? ' | ' . $productItems : ''));
        }

        $supplier->update([
            'name' => $data['name'],
            'contact_person' => $data['contact'] ?? null,
            'email' => $data['email'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'category' => $data['category'] ?? null,
            'product_items' => $productItems ?: null,
            'status' => $data['status'] ?? 'active',
        ]);

        app(ErpIntegrationService::class)->supplierChanged((int) session('employee_client_id'), $supplier->fresh());

        return response()->json(['success' => true, 'data' => $supplier]);
    }

    public function destroy(Supplier $supplier): JsonResponse
    {
        app(ErpIntegrationService::class)->supplierChanged((int) session('employee_client_id'), $supplier, true);
        $supplier->delete();

        return response()->json(['success' => true]);
    }
}

