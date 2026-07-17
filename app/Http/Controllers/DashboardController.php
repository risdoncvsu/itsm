<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $role = session('employee_role');
        $department = strtolower(trim(session('employee_department', '')));

        if ($role !== 'admin' && $department !== 'human resources') {
            return redirect()->route('employee.dashboard');
        }

        $employeeCount = Employee::count();
        $presentToday = Attendance::whereDate('attendance_date', today())
            ->whereNotNull('time_in')
            ->whereNull('time_out')
            ->count();

        return view('dashboard.index', compact('employeeCount', 'presentToday'));
    }

    public function employeeIndex()
    {
        $role = session('employee_role');
        $department = strtolower(trim(session('employee_department', '')));

        if ($role === 'admin' || $department === 'human resources') {
            return redirect()->route('dashboard');
        }

        $employeeCount = Employee::count();

        return view('dashboard.employee-dashboard', compact('employeeCount'));
    }
}