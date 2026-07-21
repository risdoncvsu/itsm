<?php

namespace Modules\Ecommerce\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Models\Contracts\HasName;
use Filament\Panel;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;

class EcommerceAdmin extends Authenticatable implements FilamentUser, HasName
{
    protected $connection = 'hr';
    protected $table = 'ecommerce';

    // Disable timestamps as this is a view
    public $timestamps = false;

    // All records in the ecommerce view are considered admins
    public function canAccessPanel(Panel $panel): bool
    {
        return true;
    }

    public function getAuthPassword()
    {
        // The view contains a plaintext 'temporary_password'.
        // We hash it dynamically so Laravel's default Auth Hash::check() succeeds.
        return Hash::make($this->temporary_password);
    }

    public function getFilamentName(): string
    {
        return trim("{$this->first_name} {$this->last_name}");
    }

    /**
     * Look up the Company record for this admin via hr_employee_id.
     */
    public function getCompany(): ?Company
    {
        return Company::forHrEmployee($this->id);
    }
}

