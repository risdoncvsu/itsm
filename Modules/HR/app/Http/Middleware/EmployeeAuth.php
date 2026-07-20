<?php

namespace Modules\HR\Http\Middleware;

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
        if (config('nexora.root_admin_module_testing') && $request->user()?->role === 'root_admin') {
            return $next($request);
        }

        if (! session('employee_logged_in')) {
            return redirect()->route('login');
        }

        if (! session('employee_client_id')) {
            session()->forget(['employee_logged_in', 'employee_role', 'employee_id', 'employee_name', 'employee_email', 'employee_department', 'employee_client_id']);

            return redirect()->route('login')->withErrors([
                'username' => 'This HR account is not linked to a client company.',
            ]);
        }

        $role = session('employee_role');
        $department = strtolower(trim(session('employee_department', '')));

        if ($role === 'employee' && $department !== 'human resources') {
            if (! $request->routeIs('hr.employee.dashboard') && ! $request->routeIs('logout')) {
                return redirect()->route('hr.employee.dashboard');
            }
        }

        return $next($request);
    }
}
