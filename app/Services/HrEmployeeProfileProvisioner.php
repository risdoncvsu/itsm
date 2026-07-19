<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HrEmployeeProfileProvisioner
{
    public function provisionCompanyAdmin(Company $company, User $admin, string $plainPassword): void
    {
        if (! Schema::hasTable('employees')) {
            return;
        }

        $this->upsertEmployee([
            'employee_id' => 'HR-ADMIN-' . str_pad((string) $company->id, 5, '0', STR_PAD_LEFT),
            'name' => $admin->name,
            'email' => $admin->email ?: $admin->username,
            'company_email' => $admin->username,
            'temporary_password' => $plainPassword,
            'phone' => $company->phone_no,
            'department' => 'Human Resources',
            'position' => 'Company Administrator',
            'hire_date' => now()->toDateString(),
            'work_schedule' => '08:00-17:00',
        ]);
    }

    public function provisionItsmEmployee(Company $company, array $employee, string $plainPassword): void
    {
        if (! Schema::hasTable('employees')) {
            return;
        }

        $login = $employee['username'] ?: $employee['email'];

        if (! $login) {
            return;
        }

        $this->upsertEmployee([
            'employee_id' => $employee['employee_code'] ?? null,
            'name' => $employee['name'],
            'email' => $employee['email'] ?: $login,
            'company_email' => $login,
            'temporary_password' => $plainPassword,
            'phone' => $company->phone_no,
            'department' => $employee['department'] ?: 'General',
            'position' => $employee['department'] ?: 'Employee',
            'hire_date' => now()->toDateString(),
            'work_schedule' => '08:00-17:00',
        ]);
    }

    private function upsertEmployee(array $attributes): void
    {
        $name = $this->splitName($attributes['name']);
        $companyEmail = $attributes['company_email'];
        $email = $attributes['email'];

        $values = $this->onlyExistingEmployeeColumns([
            'employee_id' => $attributes['employee_id'],
            'first_name' => $name['first_name'],
            'last_name' => $name['last_name'],
            'email' => $email,
            'company_email' => $companyEmail,
            'temporary_password' => $attributes['temporary_password'],
            'phone' => $attributes['phone'],
            'department' => $attributes['department'],
            'position' => $attributes['position'],
            'hire_date' => $attributes['hire_date'],
            'work_schedule' => $attributes['work_schedule'],
            'updated_at' => now(),
        ]);

        $existing = DB::table('employees')
            ->when(Schema::hasColumn('employees', 'company_email'), function ($query) use ($companyEmail) {
                $query->where('company_email', $companyEmail);
            })
            ->when(Schema::hasColumn('employees', 'email'), function ($query) use ($email) {
                $query->orWhere('email', $email);
            })
            ->first();

        if ($existing) {
            DB::table('employees')->where('id', $existing->id)->update($values);

            return;
        }

        DB::table('employees')->insert($values + ['created_at' => now()]);
    }

    public function putHrSessionFor(User $admin): void
    {
        if (! Schema::hasTable('employees') || ! Schema::hasColumn('employees', 'company_email')) {
            return;
        }

        $employee = DB::table('employees')->where('company_email', $admin->username)->first();

        if (! $employee) {
            return;
        }

        session([
            'employee_logged_in' => true,
            'employee_role' => 'employee',
            'employee_id' => $employee->id,
            'employee_name' => $employee->first_name ?? $admin->name,
            'employee_email' => $employee->company_email ?? $admin->username,
            'employee_department' => $employee->department ?? 'Human Resources',
        ]);
    }

    private function onlyExistingEmployeeColumns(array $values): array
    {
        return collect($values)
            ->filter(fn ($value, string $column): bool => Schema::hasColumn('employees', $column))
            ->all();
    }

    private function splitName(string $name): array
    {
        $parts = preg_split('/\s+/', trim($name), 2) ?: [];

        return [
            'first_name' => $parts[0] ?? 'Company',
            'last_name' => $parts[1] ?? 'Administrator',
        ];
    }
}
