<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EmployeeAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! session('employee_logged_in')) {
            return redirect()->route('signin');
        }

        $role = session('employee_role');
        $department = strtolower(trim(session('employee_department', '')));

        if ($role === 'employee' && $department !== 'human resources') {
            if (! $request->routeIs('employee.dashboard') && ! $request->routeIs('logout')) {
                return redirect()->route('employee.dashboard');
            }
        }

        return $next($request);
    }
}
