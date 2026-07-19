<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Services\HrEmployeeProfileProvisioner;
use App\Services\TenantEmployeeTable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CompanyController extends Controller
{
    public function __construct(
        private readonly TenantEmployeeTable $tenantEmployeeTable,
        private readonly HrEmployeeProfileProvisioner $hrEmployeeProfileProvisioner,
    )
    {
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:100'],
            'company_email' => ['required', 'email', 'max:255', 'unique:companies,company_email'],
            'phone_no' => ['nullable', 'string', 'max:50'],
            'admin_name' => ['required', 'string', 'max:255'],
        ]);

        $baseUsername = $this->buildAdminUsername($validated['company_name'], $validated['admin_name']);
        $username = $this->uniqueUsername($baseUsername);
        $password = Str::random(14);

        DB::transaction(function () use ($validated, $username, $password): void {
            $company = Company::create($validated + ['status' => 'Active']);

            $admin = User::create([
                'name' => $validated['admin_name'],
                'username' => $username,
                'email' => $username,
                'password' => Hash::make($password),
                'role' => 'company_admin',
                'company_id' => $company->id,
            ]);

            $company->update(['admin_user_id' => $admin->id]);
            $company->refresh();

            $this->tenantEmployeeTable->ensure($company);
            $this->tenantEmployeeTable->seedCompanyAdmin($company);
        });

        return redirect()
            ->route('admin.itsm.clients')
            ->with('success', 'Company registered successfully.')
            ->with('generated_credentials', [
                'username' => $username,
                'password' => $password,
            ]);
    }

    public function update(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'company_name' => ['required', 'string', 'max:255'],
            'industry' => ['required', 'string', 'max:100'],
            'company_email' => ['required', 'email', 'max:255', 'unique:companies,company_email,' . $company->id],
            'phone_no' => ['nullable', 'string', 'max:50'],
            'admin_name' => ['required', 'string', 'max:255'],
            'status' => ['required', 'string', 'max:50'],
            'admin_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $companyValues = collect($validated)->except([
            'admin_password',
            'admin_password_confirmation',
        ])->all();

        $company->update($companyValues);

        if ($company->adminUser) {
            $company->adminUser->update(['name' => $validated['admin_name']]);

            if (! empty($validated['admin_password'])) {
                $company->adminUser->update(['password' => Hash::make($validated['admin_password'])]);
            }

            $this->tenantEmployeeTable->seedCompanyAdmin($company->fresh('adminUser'));
        }

        return redirect()
            ->route('admin.itsm.clients')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Request $request, Company $company): RedirectResponse
    {
        $validated = $request->validate([
            'admin_name_confirmation' => ['required', 'string'],
        ]);

        if (! hash_equals($company->admin_name, $validated['admin_name_confirmation'])) {
            return redirect()
                ->route('admin.itsm.clients')
                ->withErrors(['admin_name_confirmation' => 'The system admin name confirmation did not match.']);
        }

        DB::transaction(function () use ($company): void {
            $adminUser = $company->adminUser;
            $employeeTableName = $company->employee_table_name;
            $hrEmployeeId = $company->hr_employee_id;

            $company->delete();

            if ($adminUser) {
                $adminUser->delete();
            }

            if ($employeeTableName && Schema::hasTable($employeeTableName)) {
                Schema::drop($employeeTableName);
            }

            if ($hrEmployeeId) {
                $this->hrEmployeeProfileProvisioner->deleteHrEmployee((int) $hrEmployeeId);
            }
        });

        return redirect()
            ->route('admin.itsm.clients')
            ->with('success', 'Client deleted successfully.');
    }

    private function buildAdminUsername(string $companyName, string $adminName): string
    {
        $company = $this->credentialSegment($companyName, 'company');
        $admin = $this->credentialSegment($adminName, 'admin');

        return "{$company}.{$admin}@nexora.mail";
    }

    private function credentialSegment(string $value, string $fallback): string
    {
        $segment = Str::of($value)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '')
            ->toString();

        return $segment !== '' ? $segment : $fallback;
    }

    private function uniqueUsername(string $username): string
    {
        if (! User::where('username', $username)->exists()) {
            return $username;
        }

        [$local, $domain] = explode('@', $username, 2);
        $counter = 2;

        do {
            $candidate = "{$local}{$counter}@{$domain}";
            $counter++;
        } while (User::where('username', $candidate)->exists());

        return $candidate;
    }
}
