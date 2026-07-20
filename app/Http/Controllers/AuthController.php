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
            'username' => 'required|string',
            'password' => 'required'
        ]);

        // 2. Use Auth::attempt() instead of manual plain-text comparison
        // This automatically hashes the input and checks it against the database
        $itsmCredentials = ['username' => $credentials['username'], 'password' => $credentials['password']];
        if (str_contains($credentials['username'], '@')) {
            $itsmCredentials = ['email' => $credentials['username'], 'password' => $credentials['password']];
        }

        if (Auth::attempt($itsmCredentials)) {
            
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

        $hrLogin = $this->hrEmployeeProfileProvisioner->authenticateHrAccount($credentials['username'], $credentials['password']);

        if ($hrLogin['success']) {
            // HR employees use their own session identity. A previously
            // authenticated ITSM account in the same browser must never carry
            // into a module session or remain usable through a direct URL.
            Auth::logout();
            $request->session()->regenerate();

            if ($hrLogin['requires_password_change']) {
                return redirect()->route('hr.first-login.password');
            }

            $department = strtolower((string) session('employee_department', ''));

            if (str_contains($department, 'inventory') || str_contains($department, 'warehouse')) {
                return redirect()->route('inventory.index');
            }

            if (str_contains($department, 'procurement') || str_contains($department, 'purchasing')) {
                return redirect()->route('procurement.dashboard');
            }

            if (str_contains($department, 'fulfillment') || str_contains($department, 'operations')) {
                return redirect()->route('order-fulfillment.dashboard');
            }

            if (str_contains($department, 'manufacturing') || str_contains($department, 'production')) {
                return redirect()->route('manufacturing.dashboard');
            }

            return redirect()->route('hr.dashboard');
        }

        return back()->withErrors(['username' => $hrLogin['message']]);
    }

    public function showHrFirstLoginPassword()
    {
        abort_unless(session('hr_password_change_employee_id'), 403);

        return view('auth.first-login-password');
    }

    public function storeHrFirstLoginPassword(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z\d])\S+$/'],
        ], [
            'password.regex' => 'Password must include uppercase, lowercase, number, special character, and no spaces.',
        ]);

        abort_unless($this->hrEmployeeProfileProvisioner->completeFirstHrLogin($validated['password']), 403);

        $request->session()->regenerate();

        $department = strtolower((string) session('employee_department', ''));

        if (str_contains($department, 'inventory') || str_contains($department, 'warehouse')) {
            return redirect()->route('inventory.index');
        }

        if (str_contains($department, 'procurement') || str_contains($department, 'purchasing')) {
            return redirect()->route('procurement.dashboard');
        }

        if (str_contains($department, 'fulfillment') || str_contains($department, 'operations')) {
            return redirect()->route('order-fulfillment.dashboard');
        }

        if (str_contains($department, 'manufacturing') || str_contains($department, 'production')) {
            return redirect()->route('manufacturing.dashboard');
        }

        return redirect()->route('hr.dashboard');
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
