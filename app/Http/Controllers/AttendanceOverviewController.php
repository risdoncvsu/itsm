<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class ReportsAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::when($request->search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            })
            ->when($request->department, function ($query, $department) {
                $query->where('department', $department);
            })
            ->orderBy('id')
            ->paginate(5)
            ->withQueryString();

        return view(
            'reports-analytics.attendance-overview',
            compact('employees')
        );
    }

    public function employeeAttendance($employee)
    {
        $employee = Employee::findOrFail($employee);

        return view('reports-analytics.employee-attendance', compact('employee'));
    }
}