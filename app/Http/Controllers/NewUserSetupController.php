<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Services\HrEmployeeProfileProvisioner;
use App\Services\TenantEmployeeTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\View\View;

class NewUserSetupController extends Controller
{
    public function __construct(
        private readonly HrEmployeeProfileProvisioner $hrEmployeeProfileProvisioner,
        private readonly TenantEmployeeTable $tenantEmployeeTable,
    )
    {
    }

    public function show(Request $request): View|RedirectResponse
    {
        $company = $this->company();

        if (! $company) {
            return redirect()->route('admin.itsm.registration');
        }

        if ($company->setup_completed_at && ! $request->boolean('review')) {
            return redirect()->route('client.itsm.employees');
        }

        $stage = (string) $request->query('stage', '1');
        if (! in_array($stage, ['1', '2', '3', '4'], true)) {
            $stage = '1';
        }

        return view('newuser', [
            'stage' => $stage,
            'company' => $company,
            'admin' => Auth::user(),
        ]);
    }

    public function storePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'new_password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])\S+$/'],
        ], [
            'new_password.regex' => 'Password must include uppercase, lowercase, number, special character, and no spaces.',
        ]);

        Auth::user()->forceFill([
            'password' => Hash::make($validated['new_password']),
        ])->save();

        return redirect()->route('newuser.show', ['stage' => 2]);
    }

    public function storeLogo(Request $request): RedirectResponse
    {
        $company = $this->company();
        abort_unless($company, 403);

        $validated = $request->validate([
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ]);

        if ($request->hasFile('logo')) {
            $validated['logo_path'] = $request->file('logo')->store('company-logos', 'public');
            $company->update(['logo_path' => $validated['logo_path']]);
        }

        return redirect()->route('newuser.show', ['stage' => 3]);
    }

    public function storeHrManager(Request $request): RedirectResponse
    {
        $company = $this->company();
        abort_unless($company, 403);

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'employee_id' => ['required', 'string', 'max:255'],
        ]);

        $hrEmail = $this->hrEmployeeProfileProvisioner->generateHrEmail($company, $validated);
        $password = Str::random(14);

        $employeeId = $this->hrEmployeeProfileProvisioner->provisionHrManager(
            $company,
            $validated + [
                'personal_email' => $validated['email'],
                'email' => $hrEmail,
            ],
            $password
        );
        $this->tenantEmployeeTable->queueHrManagerApproval($company, $validated, $hrEmail);

        $company->update([
            'hr_employee_id' => $employeeId,
            'setup_completed_at' => now(),
        ]);

        return redirect()
            ->route('newuser.show', ['stage' => 4, 'review' => 1])
            ->with('hr_credentials', [
                'company_email' => $hrEmail,
                'personal_email' => $validated['email'],
                'password' => $password,
            ]);
    }

    private function company(): ?Company
    {
        $user = Auth::user();

        if (! $user || $user->role !== 'company_admin') {
            return null;
        }

        return Company::find($user->company_id);
    }
}
