<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\TenantEmployeeTable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function __construct(private readonly TenantEmployeeTable $tenantEmployeeTable)
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

        $employeeId = $this->tenantEmployeeTable->createEmployee($company, $validated);
        $this->tenantEmployeeTable->updateEmployee($company, $employeeId, [
            'employee_code' => 'EMP-' . str_pad((string) $employeeId, 5, '0', STR_PAD_LEFT),
        ]);

        return redirect()
            ->route('client.itsm.employees')
            ->with('success', 'Employee created successfully.');
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
