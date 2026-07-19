<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ResolvesPerPage;
use App\Http\Controllers\Concerns\RespondsWithAjaxList;
use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class ReportsAnalyticsController extends Controller
{
    use ResolvesPerPage;
    use RespondsWithAjaxList;

    public function index(Request $request)
    {
        $employees = Employee::when($request->search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('id', $search)
                    ->orWhere('employee_id', 'like', "%{$search}%");
            })
            ->when($request->department, function ($query, $department) {
                $query->where('department', $department);
            })
            ->orderBy('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        if ($this->wantsAjaxList($request)) {
            return $this->ajaxListResponse(
                'reports-analytics.partials.attendance-overview-results',
                compact('employees')
            );
        }

        return view(
            'reports-analytics.attendance-overview',
            compact('employees')
        );
    }

    public function employeeAttendance(Request $request, $employee)
    {
        $employee = Employee::findOrFail($employee);

        $attendances = Attendance::where('employee_id', $employee->id)
            ->orderByDesc('attendance_date')
            ->orderByDesc('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        $attendances->getCollection()->each(
            fn (Attendance $row) => $row->setRelation('employee', $employee)
        );

        if ($this->wantsAjaxList($request)) {
            return $this->ajaxListResponse(
                'reports-analytics.partials.employee-attendance-results',
                compact('attendances')
            );
        }

        $allRecords = Attendance::where('employee_id', $employee->id)->get();

        $stats = [
            'present' => $allRecords->filter(fn (Attendance $row) => $row->displayStatus() === 'Present')->count(),
            'absent' => $allRecords->filter(fn (Attendance $row) => $row->displayStatus() === 'Absent' && $row->status !== 'Leave')->count(),
            'leave' => $allRecords->where('status', 'Leave')->count(),
            'total' => $allRecords->count(),
        ];

        return view(
            'reports-analytics.employee-attendance',
            compact('employee', 'attendances', 'stats')
        );
    }

    public function leave(Request $request)
    {
        $employees = Employee::when($request->search, function ($query, $search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('id', $search)
                    ->orWhere('employee_id', 'like', "%{$search}%");
            })
            ->when($request->department, function ($query, $department) {
                $query->where('department', $department);
            })
            ->orderBy('id')
            ->paginate($this->perPage($request))
            ->withQueryString();

        if ($this->wantsAjaxList($request)) {
            return $this->ajaxListResponse(
                'reports-analytics.partials.leave-results',
                compact('employees')
            );
        }

        return view('reports-analytics.leave', compact('employees'));
    }
}
