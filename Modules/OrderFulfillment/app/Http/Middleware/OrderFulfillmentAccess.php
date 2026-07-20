<?php

namespace Modules\OrderFulfillment\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OrderFulfillmentAccess
{
    public function handle(Request $request, Closure $next)
    {
        if (config('nexora.root_admin_module_testing') && $request->user()?->role === 'root_admin') {
            return $next($request);
        }

        $department = strtolower((string) session('employee_department', ''));
        abort_unless(session('employee_logged_in') && session('employee_client_id') && (str_contains($department, 'fulfillment') || str_contains($department, 'operations')), 403);
        return $next($request);
    }
}
