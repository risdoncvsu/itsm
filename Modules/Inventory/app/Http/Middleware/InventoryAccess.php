<?php

namespace Modules\Inventory\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InventoryAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('employee_logged_in') || ! session('employee_client_id')) {
            return redirect()->route('login')->withErrors([
                'username' => 'Sign in with your approved HR employee account to access Inventory.',
            ]);
        }

        return $next($request);
    }
}
