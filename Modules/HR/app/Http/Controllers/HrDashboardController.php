<?php

namespace Modules\HR\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class HrDashboardController
{
    public function dashboard(): View|RedirectResponse
    {
        if (! session('employee_logged_in') || strtolower((string) session('employee_department')) !== 'human resources') {
            return redirect()->route('login');
        }

        $employeeCount = Schema::connection('hr')->hasTable('employees')
            ? DB::connection('hr')->table('employees')->count()
            : 0;

        return view('hr::dashboard.index', [
            'employeeCount' => $employeeCount,
            'presentToday' => 0,
            'monthlyAttendance' => collect(),
            'currentMonth' => now()->month,
        ]);
    }

    public function logout(): RedirectResponse
    {
        session()->forget([
            'employee_logged_in',
            'employee_role',
            'employee_id',
            'employee_name',
            'employee_email',
            'employee_department',
        ]);

        return redirect()->route('login');
    }
}
