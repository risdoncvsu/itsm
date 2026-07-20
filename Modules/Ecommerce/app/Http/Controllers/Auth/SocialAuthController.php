<?php

namespace Modules\Ecommerce\Http\Controllers\Auth;

use Modules\Ecommerce\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Modules\Ecommerce\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class SocialAuthController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function redirect($provider)
    {
        return redirect()->route('ecommerce.login')->withErrors([
            'email' => 'Social sign-in is not enabled for this storefront.',
        ]);
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param  string  $provider
     * @return \Illuminate\Http\Response
     */
    public function callback($provider)
    {
        return $this->redirect($provider);

        try {
            $driver = Socialite::driver($provider);
            if (app()->environment('local')) {
                $driver->setHttpClient(new \GuzzleHttp\Client(['verify' => false]));
            }
            $socialUser = $driver->user();
        } catch (\Exception $e) {
            \Log::error('SocialAuth Exception: ' . $e->getMessage(), ['exception' => $e]);
            return redirect()->route('ecommerce.login')->withErrors(['login' => 'Authentication failed or was cancelled.']);
        }

        // Find existing user by provider ID or email
        $user = User::where('provider', $provider)
                    ->where('provider_id', $socialUser->getId())
                    ->first();

        if (!$user) {
            // Check if user exists with the same email
            $user = User::where('email', $socialUser->getEmail())->first();

            if ($user) {
                // Link the social account to the existing user
                $user->update([
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
            } else {
                // Redirect to complete registration with a password
                session()->put('social_new_user', [
                    'name' => $socialUser->getName() ?? $socialUser->getNickname() ?? 'User',
                    'email' => $socialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $socialUser->getId(),
                ]);
                return redirect()->route('ecommerce.social.complete-registration');
            }
        }

        Auth::guard('ecommerce')->login($user);

        if (session()->has('redirect_after_auth')) {
            return redirect(session()->pull('redirect_after_auth'))->with('success', 'Logged in successfully with ' . ucfirst($provider) . '!');
        }

        return redirect()->intended('/')->with('success', 'Logged in successfully with ' . ucfirst($provider) . '!');
    }

    public function completeRegistration()
    {
        if (!session()->has('social_new_user')) {
            return redirect()->route('ecommerce.login');
        }
        
        $socialUser = session()->get('social_new_user');
        return view('ecommerce::auth.social-password', compact('socialUser'));
    }

    public function processRegistration(Request $request)
    {
        if (!session()->has('social_new_user')) {
            return redirect()->route('ecommerce.login');
        }

        $request->validate([
            'password' => ['required', 'string', 'min:8'],
        ]);

        $socialData = session()->pull('social_new_user');
        $username = explode('@', $socialData['email'])[0] . rand(1000, 9999);

        $user = User::create([
            'name' => $socialData['name'],
            'username' => $username,
            'email' => $socialData['email'],
            'provider' => $socialData['provider'],
            'provider_id' => $socialData['provider_id'],
            'password' => Hash::make($request->password),
        ]);

        Auth::guard('ecommerce')->login($user, $request->has('remember'));

        if (session()->has('redirect_after_auth')) {
            return redirect(session()->pull('redirect_after_auth'))->with('success', 'Account created successfully!');
        }

        return redirect()->route('ecommerce.account.profile')->with('success', 'Account created successfully! Please complete your profile.');
    }
}
