<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class HrPortalController extends Controller
{
    public function dashboard(): View|RedirectResponse
    {
        if (! session('employee_logged_in') || strtolower((string) session('employee_department')) !== 'human resources') {
            return redirect()->route('login');
        }

        return view('hr.dashboard', [
            'employeeName' => session('employee_name', 'HR Manager'),
            'employeeEmail' => session('employee_email'),
        ]);
    }
}
