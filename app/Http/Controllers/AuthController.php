<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        // Fetch the user
        $user = User::where('username', $request->username)->first();

        // Plain-text password comparison for shared auth
        if ($user && $user->password === $request->password) {
            Auth::login($user);
            
            // TODO: Fire a 'UserLoggedIn' event for the ITSM audit trail
            
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['username' => 'Invalid credentials.']);
    }
}