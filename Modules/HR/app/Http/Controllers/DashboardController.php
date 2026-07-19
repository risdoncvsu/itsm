<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Attendance;
use Carbon\Carbon;
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

        $currentYear = today()->year;
        $monthStats = Attendance::selectRaw('EXTRACT(MONTH FROM attendance_date)::int as month, COUNT(*) as clocked_in, COUNT(DISTINCT employee_id) as unique_employees')
            ->whereYear('attendance_date', $currentYear)
            ->whereNotNull('time_in')
            ->groupBy('month')
            ->get()
            ->keyBy(function ($item) {
                return (int) $item->month;
            });

        $monthlyAttendance = collect(range(1, 12))->map(function ($month) use ($monthStats, $employeeCount, $currentYear) {
            $stats = $monthStats->get($month);
            $clockedIn = $stats?->clocked_in ?? 0;
            $uniqueEmployees = $stats?->unique_employees ?? 0;

            return [
                'month' => Carbon::create($currentYear, $month, 1)->format('M'),
                'month_name' => Carbon::create($currentYear, $month, 1)->format('F'),
                'month_number' => $month,
                'clocked_in' => $clockedIn,
                'unique_employees' => $uniqueEmployees,
                'rate' => $employeeCount > 0 ? round($uniqueEmployees / $employeeCount * 100) : 0,
            ];
        });

        $currentMonth = today()->month;

        return view('dashboard.index', compact('employeeCount', 'presentToday', 'monthlyAttendance', 'currentMonth'));
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