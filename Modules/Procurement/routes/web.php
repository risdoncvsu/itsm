<?php

use Illuminate\Support\Facades\Route;
use Modules\Procurement\Http\Controllers\Procurement\DashboardController;
use Modules\Procurement\Http\Controllers\Procurement\DeliveryController;
use Modules\Procurement\Http\Controllers\Procurement\PurchaseOrderController;
use Modules\Procurement\Http\Controllers\Procurement\RequisitionController;
use Modules\Procurement\Http\Controllers\Procurement\SupplierController;

Route::prefix('procurement')->name('procurement.')->group(function (): void {
    Route::redirect('/', '/procurement/dashboard');

    Route::post('/logout', function () {
        session()->forget([
            'employee_logged_in', 'employee_role', 'employee_id', 'employee_name',
            'employee_email', 'employee_department', 'employee_client_id',
        ]);

        return redirect()->route('login');
    })->name('logout');

    Route::middleware('procurement.access')->group(function (): void {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('purchase-orders');
        Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('purchase-orders.store');
        Route::put('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'update'])->name('purchase-orders.update');
        Route::delete('/purchase-orders/{purchaseOrder}', [PurchaseOrderController::class, 'destroy'])->name('purchase-orders.destroy');

        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');

        Route::get('/requisitions', [RequisitionController::class, 'index'])->name('requisitions');
        Route::post('/requisitions', [RequisitionController::class, 'store'])->name('requisitions.store');
        Route::put('/requisitions/{requisition}', [RequisitionController::class, 'update'])->name('requisitions.update');
        Route::delete('/requisitions/{requisition}', [RequisitionController::class, 'destroy'])->name('requisitions.destroy');

        Route::get('/deliveries', [DeliveryController::class, 'index'])->name('deliveries');
        Route::post('/deliveries', [DeliveryController::class, 'store'])->name('deliveries.store');
        Route::put('/deliveries/{delivery}', [DeliveryController::class, 'update'])->name('deliveries.update');
        Route::delete('/deliveries/{delivery}', [DeliveryController::class, 'destroy'])->name('deliveries.destroy');
    });
});
