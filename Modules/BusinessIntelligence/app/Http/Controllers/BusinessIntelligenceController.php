<?php

use App\Models\InventoryDeptStockLevel;
use App\Models\ManufacturingMachine;
use App\Models\ManufacturingWorkOrder;
use App\Models\FulfillmentShipment;
use App\Models\FulfillmentPackingMaterial;
use App\Models\ComplianceRisk;
use App\Models\ItsmTicket;
use App\Models\ProcurementOrder;
use App\Models\FinanceDeptInvoice;

use App\Http\Controllers\AIChatController;
use App\Http\Controllers\AIController;
use App\Http\Controllers\AIInsightsController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\DashboardController;
use Modules\BusinessIntelligence\Http\Controllers\BusinessIntelligenceController; // FIX: was missing — caused "Class not found" on every bi.* route. Note the module namespace, NOT App\Http\Controllers.
use App\Http\Controllers\SyncController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

use App\Services\DataService;
use App\Services\Departments\EcommerceService;
use App\Services\Departments\FinanceService;
use App\Services\Departments\InventoryService;
use App\Services\Departments\ManufacturingService;
use App\Services\Departments\ProcurementService;
use App\Services\Departments\FulfillmentService;
use App\Services\Departments\ComplianceService;
use App\Services\Departments\ItsmService;
use App\Services\Departments\BiService;

// ============================================================
// PUBLIC / UNAUTHENTICATED PAGES
// ============================================================
// FIX: '/' now only redirects to signin. The old duplicate
// `Route::get('/', ...)` further down that redirected to
// `bi.dashboard` has been removed — Laravel takes the first
// matching route, so that second definition was always dead code.
Route::redirect('/', '/signin')->name('home');
Route::get('/signin', fn() => view('signIn'))->name('signin');
Route::get('/contactus', fn() => view('contactus'))->name('contactus');

Route::get('/department-analytics', fn() => view('department-analytics', [
    'departments' => DataService::getDepartmentList(),
]))->name('department-analytics');

Route::post('/api/sync-all', [SyncController::class, 'syncAll'])->name('sync.all');

Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::prefix('nexora-ai')->name('ai.')->group(function () {
    Route::get('/current-report', [AIController::class, 'current'])->name('current');
    Route::post('/refresh', [AIController::class, 'refresh'])->name('refresh');
    Route::post('/chat', [AIChatController::class, 'respond'])->name('chat');
});

// ============================================================
// FIX: Removed duplicate, unprotected definitions of:
//   /dashboard, /live-monitor, /ai-insights, /api/sales-forecast,
//   /api/live-feed, /api/department/{dept}
// These previously sat ABOVE the `bi.access`-protected group below
// and matched every request first, silently bypassing the
// `bi.access` middleware entirely (the guarded copies were
// unreachable dead code). The bi.* group below is now the single
// source of truth for these URIs.
//
// Verified against BusinessIntelligenceController's source:
// @liveFeed and @departmentData already implement equivalent
// (client-scoped) logic to the deleted closures, so nothing was
// lost by removing them here.
// ============================================================

Route::middleware('bi.access')->name('bi.')->group(function (): void {
    Route::get('/dashboard', [BusinessIntelligenceController::class, 'dashboard'])->name('dashboard');
    Route::get('/department-analytics', [BusinessIntelligenceController::class, 'departmentAnalytics'])->name('department-analytics');
    Route::get('/live-monitor', [BusinessIntelligenceController::class, 'liveMonitor'])->name('live-monitor');
    Route::get('/ai-insights', [BusinessIntelligenceController::class, 'aiInsights'])->name('ai-insights');
    Route::post('/api/ai/chat', [BusinessIntelligenceController::class, 'aiChat'])->name('ai.chat');

    Route::get('/api/live-feed', [BusinessIntelligenceController::class, 'liveFeed'])->name('live-feed');
    Route::get('/api/department/{department}', [BusinessIntelligenceController::class, 'departmentData'])
        ->whereIn('department', ['finance', 'inventory', 'procurement', 'manufacturing', 'fulfillment', 'ecommerce'])
        ->name('department-data');
    Route::get('/api/sales-forecast', [BusinessIntelligenceController::class, 'salesForecast'])->name('sales-forecast');
});