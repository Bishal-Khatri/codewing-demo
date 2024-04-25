<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Laravel\Socialite\Facades\Socialite;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Display the login view.
     */
    public function redirectToProvider($provider)
    {
        try{
            /*
             * Redirect user to provider auth screen
             */
            return Socialite::driver($provider)
                ->scopes(['read:user'])
                ->redirectUrl(route('provider.callback', 'provider='.$provider))
                ->redirect();

        }catch (\Exception $exception){
            Session::flash('error-message', 'Something went wrong. Please try another method.');
            return redirect('/login');
        }
    }

    public function providerCallback(Request $request)
    {
        try{
            $provider = $request->provider;

            $providerUser = Socialite::driver($provider)->user();

            /*
             * Get User
             */
            $user = User::where('provider', $provider)->where('provider_id', $providerUser->id)->first();
            if ($user){
                $user->provider_token = $providerUser->token;
                $user->provider_refresh_token = $providerUser->refreshToken;
                $user->save();
            }else{
                $user = User::create([
                    'name' => $providerUser->name,
                    'email' => $providerUser->email,
                    'provider' => $provider,
                    'provider_id' => $providerUser->id,
                    'provider_token' => $providerUser->token,
                    'provider_refresh_token' => $providerUser->refreshToken,
                ]);
            }

            /*
             * Authenticate user and redirect to dashboard
             */
            Auth::login($user);
            return redirect('/dashboard');
            
        }catch (\Exception $exception){
            Session::flash('error-message', 'Error while authenticating this user.');
            return redirect('/login');
        }
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
