<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\TenantEmployeeTable;
use App\Services\HrEmployeeProfileProvisioner;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

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

    public function updateEmployee(Request $request, int $employee): \Illuminate\Http\RedirectResponse
    {
        $company = Company::findOrFail(Auth::user()->company_id);
        $validated = $request->validate([
            'username' => ['nullable', 'string', 'max:255'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'department' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:Active,Inactive,Pending,Suspended'],
        ]);

        $currentEmployee = $this->tenantEmployeeTable->find($company, $employee);
        abort_unless($currentEmployee, 404);

        $credentials = null;
        if (
            $currentEmployee->status === 'Pending'
            && $validated['status'] === 'Active'
            && $currentEmployee->department === 'Human Resources'
        ) {
            $password = Str::password(16, symbols: true);
            $provisioned = $this->hrEmployeeProfileProvisioner->provisionApprovedHrManager($company, $currentEmployee, $password);
            $validated['username'] = $provisioned['email'];
            $credentials = [
                'username' => $provisioned['email'],
                'password' => $password,
            ];

            $company->update(['hr_employee_id' => $provisioned['employee_id']]);
        }

        $this->tenantEmployeeTable->updateEmployee($company, $employee, $validated);

        $response = redirect()
            ->route('client.itsm.employees')
            ->with('success', $credentials ? 'HR manager approved and login credentials generated.' : 'Employee updated successfully.');

        return $credentials ? $response->with('hr_credentials', $credentials) : $response;
    }
}
