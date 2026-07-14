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
            
            $destination = Auth::user()->role === 'company_admin'
                ? route('client.itsm.employees')
                : route('admin.itsm.registration');

            return redirect()->intended($destination);
        }

        // 3. If it fails, send them back
        return back()->withErrors(['username' => 'Invalid credentials.']);
    }
}
