<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\HrEmployeeProfileProvisioner;

class AuthController extends Controller
{
    public function __construct(private readonly HrEmployeeProfileProvisioner $hrEmployeeProfileProvisioner)
    {
    }

    public function login(Request $request)
    {
        // 1. Validate the inputs
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // 2. Use Auth::attempt() instead of manual plain-text comparison
        // This automatically hashes the input and checks it against the database
        if (Auth::attempt($credentials)) {
            
            // Regenerate session to prevent session fixation attacks (Best Practice)
            $request->session()->regenerate();
            
            // TODO: Fire a 'UserLoggedIn' event for the ITSM audit trail

            $user = Auth::user();
            $destination = $user->role === 'company_admin'
                ? $this->companyAdminDestination($user)
                : route('admin.itsm.registration');

            // Do not allow a stale intended URL (for example, an admin page
            // visited before login) to override the portal assigned by role.
            return redirect()->to($destination);
        }

        if ($this->hrEmployeeProfileProvisioner->attemptHrLogin($credentials['username'], $credentials['password'])) {
            $request->session()->regenerate();

            $hrDashboardUrl = config('services.hr.dashboard_url');

            if (! $hrDashboardUrl) {
                return back()->withErrors([
                    'username' => 'The HR module URL has not been configured.',
                ]);
            }

            return redirect()->away($hrDashboardUrl);
        }

        // 3. If it fails, send them back
        return back()->withErrors(['username' => 'Invalid credentials.']);
    }

    private function companyAdminDestination($user): string
    {
        $company = $user->company_id ? \App\Models\Company::find($user->company_id) : null;

        if ($company && ! $company->setup_completed_at) {
            return route('newuser.show');
        }

        if ($company && (! $company->hr_employee_id || ! $this->hrEmployeeProfileProvisioner->hasEmployeeForCompany($company, (int) $company->hr_employee_id))) {
            return route('newuser.show', ['stage' => 3]);
        }

        return route('client.itsm.employees');
    }
}
