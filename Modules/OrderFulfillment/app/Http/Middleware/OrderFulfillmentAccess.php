<?php

namespace Modules\OrderFulfillment\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class OrderFulfillmentAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (config('nexora.root_admin_module_testing') && $request->user()?->role === 'root_admin') {
            return $next($request);
        }

        if (! session('employee_logged_in') || ! session('employee_client_id')) {
            return redirect()->route('login')->withErrors([
                'username' => 'Sign in with your approved Order Fulfillment employee account to access Order Fulfillment.',
            ]);
        }

        $department = strtolower((string) session('employee_department', ''));
        abort_unless(
            str_contains($department, 'fulfillment') || str_contains($department, 'operations') || str_contains($department, 'order'),
            403
        );

        return $next($request);
    }
}
