<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class SyncController extends Controller
{
    /**
     * Signature => human label, in the order they should run.
     *
     * @var array<string, string>
     */
    protected const COMMANDS = [
        'sync:finance' => 'Finance',
        'sync:inventory' => 'Inventory',
        'sync:manufacturing' => 'Manufacturing',
        'sync:procurement' => 'Procurement',
        'sync:fulfillment' => 'Fulfillment',
        'sync:ecommerce' => 'Ecommerce',
    ];

    /**
     * Run every department sync command and return a per-department
     * result. One department failing (e.g. its source DB is down)
     * does not stop the others from running.
     *
     * This runs synchronously — each command is now chunked/batched so
     * it should finish in a few seconds even with a few thousand rows
     * per table. If your source tables grow large enough that this
     * starts timing out the HTTP request, switch this to dispatch a
     * queued job instead and poll for completion.
     */
    public function syncAll(): JsonResponse
    {
        $results = [];
        $anyFailed = false;

        foreach (self::COMMANDS as $signature => $label) {
            try {
                Artisan::call($signature);

                $results[] = [
                    'department' => $label,
                    'command' => $signature,
                    'status' => 'ok',
                    'output' => trim(Artisan::output()),
                ];
            } catch (\Throwable $e) {
                $anyFailed = true;

                $results[] = [
                    'department' => $label,
                    'command' => $signature,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                ];
            }
        }

        // The dashboard KPI cards are cached for 60s (see DashboardController) —
        // clear them so the very next page load reflects what we just synced,
        // instead of waiting up to a minute for the cache to expire on its own.
        Cache::forget('dashboard_finance_snapshot');
        Cache::forget('dashboard_inventory_snapshot');
        Cache::forget('dashboard_sales_snapshot');
        Cache::forget('dashboard_top_products');
        Cache::forget('dashboard_fulfillment_rate');

        return response()->json([
            'message' => $anyFailed
                ? 'Sync completed with some errors — see results.'
                : 'All departments synced successfully.',
            'synced_at' => now()->toIso8601String(),
            'results' => $results,
        ], $anyFailed ? 207 : 200);
    }
}
