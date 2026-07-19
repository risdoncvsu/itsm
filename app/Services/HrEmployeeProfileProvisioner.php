<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class HrEmployeeProfileProvisioner
{
    public function generateHrEmail(Company $company, array $manager): string
    {
        $companySegment = $this->credentialSegment($company->company_name, 'company');
        $nameSegment = $this->credentialSegment($manager['first_name'] . ' ' . $manager['last_name'], 'hrmanager');
        $baseEmail = "{$companySegment}.{$nameSegment}@nexora.hr";

        return $this->uniqueHrEmail($baseEmail);
    }

    public function recordPendingHrManager(Company $company, array $manager): int
    {
        if (! $this->hrSchema()->hasTable('employees')) {
            return 0;
        }

        return $this->upsertEmployee([
            'employee_id' => $manager['employee_id'],
            'first_name' => $manager['first_name'],
            'last_name' => $manager['last_name'],
            'email' => $manager['personal_email'],
            'company_email' => null,
            'temporary_password' => null,
            'phone' => $company->phone_no,
            'department' => 'Human Resources',
            'position' => 'HR Manager',
            'hire_date' => now()->toDateString(),
            'work_schedule' => '08:00-17:00',
        ]);
    }

    public function provisionApprovedHrManager(Company $company, object $manager, string $plainPassword): array
    {
        $names = preg_split('/\s+/', trim($manager->name), 2);
        $firstName = $names[0] ?: 'HR';
        $lastName = $names[1] ?? 'Manager';
        $hrEmail = $this->generateHrEmail($company, [
            'first_name' => $firstName,
            'last_name' => $lastName,
        ]);

        $employeeId = $this->upsertEmployee([
            'employee_id' => $manager->employee_code,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $manager->email,
            'company_email' => $hrEmail,
            'temporary_password' => Hash::make($plainPassword),
            'phone' => $company->phone_no,
            'department' => 'Human Resources',
            'position' => 'HR Manager',
            'hire_date' => now()->toDateString(),
            'work_schedule' => '08:00-17:00',
        ]);

        return ['employee_id' => $employeeId, 'email' => $hrEmail];
    }

    public function attemptHrLogin(string $companyEmail, string $password): bool
    {
        if (
            ! $this->hrSchema()->hasTable('employees') ||
            ! $this->hrSchema()->hasColumn('employees', 'company_email') ||
            ! $this->hrSchema()->hasColumn('employees', 'temporary_password')
        ) {
            return false;
        }

        $employee = $this->hrDb()->table('employees')
            ->where('company_email', $companyEmail)
            ->first();

        if (! $employee || ! $employee->temporary_password || ! Hash::check($password, $employee->temporary_password)) {
            return false;
        }

        $this->putEmployeeSession($employee);

        return true;
    }

    public function deleteHrEmployee(int $employeeId): void
    {
        if (! $this->hrSchema()->hasTable('employees')) {
            return;
        }

        $this->hrDb()->table('employees')->where('id', $employeeId)->delete();
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

        $existing = $this->hrDb()->table('employees')
            ->when($companyEmail && $this->hrSchema()->hasColumn('employees', 'company_email'), function ($query) use ($companyEmail) {
                $query->where('company_email', $companyEmail);
            })
            ->when($this->hrSchema()->hasColumn('employees', 'email'), function ($query) use ($email) {
                $query->orWhere('email', $email);
            })
            ->first();

        if ($existing) {
            $this->hrDb()->table('employees')->where('id', $existing->id)->update($values);

            return (int) $existing->id;
        }

        return (int) $this->hrDb()->table('employees')->insertGetId($values + ['created_at' => now()]);
    }

    public function putHrSessionFor(User $admin): void
    {
        if (! $this->hrSchema()->hasTable('employees') || ! $this->hrSchema()->hasColumn('employees', 'company_email')) {
            return;
        }

        $employee = $this->hrDb()->table('employees')->where('company_email', $admin->username)->first();

        if (! $employee) {
            return;
        }

        $this->putEmployeeSession($employee);
    }

    private function onlyExistingEmployeeColumns(array $values): array
    {
        return collect($values)
            ->filter(fn ($value, string $column): bool => $this->hrSchema()->hasColumn('employees', $column))
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

    private function uniqueHrEmail(string $email): string
    {
        if (
            ! $this->hrSchema()->hasTable('employees') ||
            ! $this->hrSchema()->hasColumn('employees', 'company_email')
        ) {
            return $email;
        }

        if (! $this->hrDb()->table('employees')->where('company_email', $email)->exists()) {
            return $email;
        }

        [$local, $domain] = explode('@', $email, 2);
        $counter = 2;

        do {
            $candidate = "{$local}{$counter}@{$domain}";
            $counter++;
        } while ($this->hrDb()->table('employees')->where('company_email', $candidate)->exists());

        return $candidate;
    }

    private function credentialSegment(string $value, string $fallback): string
    {
        $segment = Str::of($value)
            ->lower()
            ->replaceMatches('/[^a-z0-9]+/', '')
            ->toString();

        return $segment !== '' ? $segment : $fallback;
    }

    private function hrDb(): ConnectionInterface
    {
        return DB::connection('modules');
    }

    private function hrSchema()
    {
        return Schema::connection('modules');
    }
}
