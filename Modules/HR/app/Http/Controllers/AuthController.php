<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'company_email' => 'required',
            'password' => 'required',
        ]);

        $employee = Employee::where(
            'company_email',
            $request->company_email
        )->first();

        if (!$employee) {
            return back()->with('error', 'Invalid email or password. Please try again.');
        }

        if (! $employee->temporary_password || ! Hash::check($request->password, $employee->temporary_password)) {
            return back()->with('error', 'Invalid email or password. Please try again.');
        }

        session([
            'employee_logged_in' => true,
            'employee_role' => 'employee',
            'employee_id' => $employee->id,
            'employee_name' => $employee->first_name,
            'employee_email' => $employee->company_email,
            'employee_department' => $employee->department,
        ]);

        $department = strtolower(trim($employee->department ?? ''));
        $route = $department === 'human resources'
            ? 'dashboard'
            : 'employee.dashboard';

        return redirect()->route($route);
    }

    public function logout(Request $request)
    {
        session()->flush();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('signin');
    }
}
