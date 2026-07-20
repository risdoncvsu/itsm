<?php

namespace Modules\OrderFulfillment\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class OrderFulfillmentAccess
{
    public function handle(Request $request, Closure $next)
    {
        $department = strtolower((string) session('employee_department', ''));
        abort_unless(session('employee_logged_in') && session('employee_client_id') && (str_contains($department, 'fulfillment') || str_contains($department, 'operations')), 403);
        return $next($request);
    }
}
