<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        // 1. Validate the inputs
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // 2. Find the user in the database
        $user = User::where('username', $request->username)->first();

        // 3. Manual plain-text comparison
        if ($user && $request->password === $user->password) {
            
            // Log them in and secure the session
            Auth::login($user);
            $request->session()->regenerate();
            
            // TODO: Fire a 'UserLoggedIn' event for the ITSM audit trail
            
            // 4. Auto-route based on the user's role
            $destination = $user->role === 'company_admin'
                ? route('client.itsm.employees')
                : route('admin.itsm.registration');

            return redirect()->intended($destination);
        }

        // 5. If it fails, send them back with an error
        return back()->withErrors(['username' => 'Invalid credentials.']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}