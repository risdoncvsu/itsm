<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\HrEmployeeProfileProvisioner;
use App\Services\TenantEmployeeTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(
        private readonly TenantEmployeeTable $tenantEmployeeTable,
        private readonly HrEmployeeProfileProvisioner $hrEmployeeProfileProvisioner,
    )
    {
    }

    public function index()
    {
        return $this->employees();
    }

    public function clients()
    {
        $companies = Company::with('adminUser')->orderByDesc('created_at')->get();

        return view('users.index', [
            'users' => $companies,
            'portal' => 'admin',
            'active' => 'clients',
            'title' => 'Client Management',
            'entityLabel' => 'client',
            'entityLabelPlural' => 'clients',
            'primaryIdLabel' => 'Client ID',
        ]);
    }

    public function employees()
    {
        $company = Company::find(Auth::user()->company_id);

        if ($company) {
            $this->tenantEmployeeTable->seedCompanyAdmin($company->fresh('adminUser'));
        }

        $employees = $company ? $this->tenantEmployeeTable->employeesFor($company) : collect();

        return view('users.index', [
            'users' => $employees,
            'portal' => 'client',
            'active' => 'employees',
            'title' => 'Employee Management',
            'entityLabel' => 'employee',
            'entityLabelPlural' => 'employees',
            'primaryIdLabel' => 'Employee ID',
        ]);
    }

    public function storeEmployee(Request $request): \Illuminate\Http\RedirectResponse
    {
        $company = Company::findOrFail(Auth::user()->company_id);
        $validated = $request->validate([
            'username' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        if (empty($validated['username']) && empty($validated['email'])) {
            return back()
                ->withErrors(['email' => 'Enter either a username or an email so this employee can sign in.'])
                ->withInput();
        }

        $temporaryPassword = Str::random(14);
        $employeeId = $this->tenantEmployeeTable->createEmployee($company, $validated);
        $employeeCode = 'EMP-' . str_pad((string) $employeeId, 5, '0', STR_PAD_LEFT);
        $this->tenantEmployeeTable->updateEmployee($company, $employeeId, [
            'employee_code' => $employeeCode,
        ]);
        $this->hrEmployeeProfileProvisioner->provisionItsmEmployee($company, $validated + [
            'employee_code' => $employeeCode,
        ], $temporaryPassword);

        return redirect()
            ->route('client.itsm.employees')
            ->with('success', 'Employee created successfully.')
            ->with('generated_employee_credentials', [
                'username' => $validated['username'] ?: $validated['email'],
                'password' => $temporaryPassword,
            ]);
    }

    public function updateEmployee(Request $request, int $employee): \Illuminate\Http\RedirectResponse
    {
        $company = Company::findOrFail(Auth::user()->company_id);
        $validated = $request->validate([
            'username' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
        ]);

        $this->tenantEmployeeTable->updateEmployee($company, $employee, $validated);

        return redirect()
            ->route('client.itsm.employees')
            ->with('success', 'Employee updated successfully.');
    }
}
