<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HrEmployeeProfileProvisioner
{
    public function provisionHrManager(Company $company, array $manager, string $plainPassword): int
    {
        if (! Schema::hasTable('employees')) {
            return 0;
        }

        return $this->upsertEmployee([
            'employee_id' => $manager['employee_id'],
            'first_name' => $manager['first_name'],
            'last_name' => $manager['last_name'],
            'email' => $manager['email'],
            'company_email' => $manager['email'],
            'temporary_password' => $plainPassword,
            'phone' => $company->phone_no,
            'department' => 'Human Resources',
            'position' => 'HR Manager',
            'hire_date' => now()->toDateString(),
            'work_schedule' => '08:00-17:00',
        ]);
    }

    public function attemptHrLogin(string $companyEmail, string $password): bool
    {
        if (
            ! Schema::hasTable('employees') ||
            ! Schema::hasColumn('employees', 'company_email') ||
            ! Schema::hasColumn('employees', 'temporary_password')
        ) {
            return false;
        }

        $employee = DB::table('employees')
            ->where('company_email', $companyEmail)
            ->where('temporary_password', $password)
            ->first();

        if (! $employee) {
            return false;
        }

        $this->putEmployeeSession($employee);

        return true;
    }

    private function upsertEmployee(array $attributes): int
    {
        $companyEmail = $attributes['company_email'];
        $email = $attributes['email'];

        $values = $this->onlyExistingEmployeeColumns([
            'employee_id' => $attributes['employee_id'],
            'first_name' => $attributes['first_name'],
            'last_name' => $attributes['last_name'],
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

            return (int) $existing->id;
        }

        return (int) DB::table('employees')->insertGetId($values + ['created_at' => now()]);
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

        $this->putEmployeeSession($employee);
    }

    private function onlyExistingEmployeeColumns(array $values): array
    {
        return collect($values)
            ->filter(fn ($value, string $column): bool => Schema::hasColumn('employees', $column))
            ->all();
    }

    private function putEmployeeSession(object $employee): void
    {
        session([
            'employee_logged_in' => true,
            'employee_role' => 'employee',
            'employee_id' => $employee->id,
            'employee_name' => $employee->first_name ?? 'HR Manager',
            'employee_email' => $employee->company_email,
            'employee_department' => $employee->department ?? 'Human Resources',
        ]);
    }
}
