<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function clockIn(Request $request)
    {
        $request->validate([
            'employee_id' => 'required|string|exists:employees,employee_id',
            'action' => 'nullable|in:clock_in,clock_out',
        ]);

        $employeeCode = $request->input('employee_id');
        $employee = Employee::where('employee_id', $employeeCode)->first();
        $today = now()->toDateString();
        $action = $request->input('action', 'clock_in');

        if ($action === 'clock_out') {
            $attendance = Attendance::where('employee_id', $employee->id)
                ->where('attendance_date', $today)
                ->first();

            if (! $attendance || ! $attendance->time_in) {
                return back()->with('error', 'No clock-in found for today.');
            }

            if ($attendance->time_out) {
                return back()->with('error', 'This employee already clocked out today.');
            }

            $attendance->time_out = now()->format('H:i:s');
            $attendance->save();

            return redirect()->route('clockinout')
                ->with('success', 'Clock out recorded for employee #' . $employeeCode)
                ->with('employee_id', $employeeCode)
                ->with('clock_in', $attendance->time_in)
                ->with('clock_out', $attendance->time_out)
                ->with('clocked_in', true)
                ->with('clocked_out', true)
                ->withInput(['employee_id' => $employeeCode]);
        }

        $attendance = Attendance::firstOrNew([
            'employee_id' => $employee->id,
            'attendance_date' => $today,
        ]);

        if ($attendance->exists && $attendance->time_in) {
            if ($attendance->time_out) {
                return back()->with('error', 'This employee already clocked out today.');
            }

            return back()->with('error', 'This employee already clocked in today.');
        }

        $attendance->time_in = now()->format('H:i:s');
        $attendance->status = 'Present';
        $attendance->save();

        return redirect()->route('clockinout')
            ->with('success', 'Clock in recorded for employee #' . $employeeCode)
            ->with('employee_id', $employeeCode)
            ->with('clock_in', $attendance->time_in)
            ->with('clocked_in', true)
            ->withInput(['employee_id' => $employeeCode]);
    }
}
