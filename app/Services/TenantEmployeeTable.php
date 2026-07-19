<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Collection;
use Illuminate\Database\ConnectionInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class TenantEmployeeTable
{
    public function tableName(Company $company): string
    {
        return 'company_employees_' . $company->id;
    }

    public function ensure(Company $company): string
    {
        $tableName = $company->employee_table_name ?: $this->tableName($company);

        if (! $this->schema()->hasTable($tableName)) {
            $this->schema()->create($tableName, function (Blueprint $table): void {
                $table->id();
                $table->string('employee_code')->nullable();
                $table->string('username')->nullable();
                $table->string('name');
                $table->string('email')->nullable();
                $table->string('department')->nullable();
                $table->string('status')->default('Active');
                $table->timestamps();
            });
        }

        if ($company->employee_table_name !== $tableName) {
            $company->forceFill(['employee_table_name' => $tableName])->save();
        }

        return $tableName;
    }

    public function seedCompanyAdmin(Company $company): void
    {
        $tableName = $this->ensure($company);
        $admin = $company->adminUser;

        if (! $admin) {
            return;
        }

        $this->stagingDb()->table($tableName)->updateOrInsert([
            'email' => $admin->email,
        ], [
            'employee_code' => 'EMP-' . str_pad((string) $admin->id, 5, '0', STR_PAD_LEFT),
            'username' => $admin->username,
            'name' => $admin->name,
            'department' => 'Administration',
            'status' => 'Active',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }

    public function employeesFor(Company $company): Collection
    {
        $tableName = $this->ensure($company);

        return $this->stagingDb()->table($tableName)->orderBy('id')->get();
    }

    public function updateEmployee(Company $company, int $employeeId, array $values): void
    {
        $tableName = $this->ensure($company);

        $this->stagingDb()->table($tableName)
            ->where('id', $employeeId)
            ->update($values + ['updated_at' => now()]);
    }

    public function createEmployee(Company $company, array $values): int
    {
        $tableName = $this->ensure($company);

        return $this->stagingDb()->table($tableName)->insertGetId($values + [
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function queueHrManagerApproval(Company $company, array $manager): void
    {
        $tableName = $this->ensure($company);

        $this->stagingDb()->table($tableName)->updateOrInsert([
            'employee_code' => $manager['employee_id'],
        ], [
            'employee_code' => $manager['employee_id'],
            'name' => trim($manager['first_name'] . ' ' . $manager['last_name']),
            'email' => $manager['email'],
            'department' => 'Human Resources',
            'username' => null,
            'status' => 'Pending',
            'updated_at' => now(),
            'created_at' => now(),
        ]);
    }

    public function find(Company $company, int $employeeId): ?object
    {
        return $this->stagingDb()->table($this->ensure($company))->find($employeeId);
    }

    private function stagingDb(): ConnectionInterface
    {
        return DB::connection('staging');
    }

    private function schema()
    {
        return Schema::connection('staging');
    }
}
