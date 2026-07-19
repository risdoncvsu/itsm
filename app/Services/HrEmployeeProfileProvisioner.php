<?php

namespace App\Services;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use RuntimeException;

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
            throw new RuntimeException('The HR employee database is unavailable. The HR manager was not created.');
        }

        return $this->upsertEmployee([
            'employee_id' => $manager['employee_id'],
            'first_name' => $manager['first_name'],
            'last_name' => $manager['last_name'],
            'email' => $manager['personal_email'],
            'company_email' => null,
            'temporary_password' => null,
            'itsm_company_id' => $company->id,
            'approval_status' => 'Pending',
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
            'itsm_company_id' => $company->id,
            'approval_status' => 'Active',
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

        if (! $employee || ! $employee->temporary_password || ($employee->approval_status ?? 'Active') !== 'Active') {
            return false;
        }

        $storedPassword = (string) $employee->temporary_password;
        $passwordMatches = str_starts_with($storedPassword, '$')
            ? Hash::check($password, $storedPassword)
            : hash_equals($storedPassword, $password);

        if (! $passwordMatches) {
            return false;
        }

        // Existing HR records used plaintext temporary passwords. Upgrade a
        // valid legacy login immediately so future checks use a password hash.
        if (! str_starts_with($storedPassword, '$')) {
            $this->hrDb()->table('employees')->where('id', $employee->id)->update([
                'temporary_password' => Hash::make($password),
                'updated_at' => now(),
            ]);
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

    public function employeesForCompany(Company $company): Collection
    {
        if (! $this->hrSchema()->hasTable('employees') || ! $this->hrSchema()->hasColumn('employees', 'itsm_company_id')) {
            return collect();
        }

        return $this->hrDb()->table('employees')
            ->where('itsm_company_id', $company->id)
            ->orderBy('id')
            ->get()
            ->map(fn (object $employee): object => $this->employeeForItsm($employee));
    }

    public function findEmployeeForCompany(Company $company, int $employeeId): ?object
    {
        if (! $this->hrSchema()->hasTable('employees') || ! $this->hrSchema()->hasColumn('employees', 'itsm_company_id')) {
            return null;
        }

        $employee = $this->hrDb()->table('employees')
            ->where('itsm_company_id', $company->id)
            ->where('id', $employeeId)
            ->first();

        return $employee ? $this->employeeForItsm($employee) : null;
    }

    public function hasEmployeeForCompany(Company $company, int $employeeId): bool
    {
        return $this->findEmployeeForCompany($company, $employeeId) !== null;
    }

    public function updateEmployeeForCompany(Company $company, int $employeeId, array $values): void
    {
        $employee = $this->findEmployeeForCompany($company, $employeeId);
        abort_unless($employee, 404);

        $names = preg_split('/\s+/', trim($values['name']), 2);
        $this->hrDb()->table('employees')->where('id', $employeeId)->update($this->onlyExistingEmployeeColumns([
            'first_name' => $names[0] ?: 'Employee',
            'last_name' => $names[1] ?? '',
            'email' => $values['email'],
            'department' => $values['department'],
            'approval_status' => $values['status'],
            'updated_at' => now(),
        ]));
    }

    public function deleteEmployeesForCompany(Company $company): void
    {
        if ($this->hrSchema()->hasTable('employees') && $this->hrSchema()->hasColumn('employees', 'itsm_company_id')) {
            $this->hrDb()->table('employees')->where('itsm_company_id', $company->id)->delete();
        }
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
            'itsm_company_id' => $attributes['itsm_company_id'],
            'approval_status' => $attributes['approval_status'],
            'phone' => $attributes['phone'],
            'department' => $attributes['department'],
            'position' => $attributes['position'],
            'hire_date' => $attributes['hire_date'],
            'work_schedule' => $attributes['work_schedule'],
            'updated_at' => now(),
        ]);

        $existing = $this->hrDb()->table('employees')
            ->when($this->hrSchema()->hasColumn('employees', 'itsm_company_id'), function ($query) use ($attributes) {
                $query->where('itsm_company_id', $attributes['itsm_company_id']);
            })
            ->where(function ($query) use ($companyEmail, $email): void {
                if ($companyEmail && $this->hrSchema()->hasColumn('employees', 'company_email')) {
                    $query->where('company_email', $companyEmail);
                }

                if ($this->hrSchema()->hasColumn('employees', 'email')) {
                    $companyEmail ? $query->orWhere('email', $email) : $query->where('email', $email);
                }
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

    private function employeeForItsm(object $employee): object
    {
        return (object) [
            'id' => $employee->id,
            'employee_code' => $employee->employee_id ?? null,
            'username' => $employee->company_email ?? null,
            'name' => trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')),
            'email' => $employee->email ?? null,
            'department' => $employee->department ?? null,
            'status' => $employee->approval_status ?? 'Active',
        ];
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
        return DB::connection('hr');
    }

    private function hrSchema()
    {
        return Schema::connection('hr');
    }
}
