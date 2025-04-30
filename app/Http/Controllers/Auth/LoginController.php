<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return 'email';
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        // Check for hardcoded admin credentials
        if ($credentials['email'] === 'admin' && $credentials['password'] === 'admin') {
            // Create or get admin user
            $admin = \App\Models\User::firstOrCreate(
                ['email' => 'admin@example.com'],
                [
                    'name' => 'Admin',
                    'password' => bcrypt('admin'),
                    'role' => 'admin'
                ]
            );

            Auth::login($admin);
            $request->session()->regenerate();
            return redirect()->intended($this->redirectPath());
        }

        // Check for hardcoded regular user credentials
        if ($credentials['email'] === 'user' && $credentials['password'] === 'user') {
            // Create or get regular user
            $user = \App\Models\User::firstOrCreate(
                ['email' => 'user@example.com'],
                [
                    'name' => 'Regular User',
                    'password' => bcrypt('user'),
                    'role' => 'user'
                ]
            );

            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended($this->redirectPath());
        }

        return back()->withErrors([
            'email' => 'Invalid credentials. Use admin/admin or user/user to login.',
        ])->onlyInput('email');
    }
}
