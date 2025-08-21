<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            // Regenerate session untuk security
            $request->session()->regenerate();
            
            $user = Auth::user();
            
            // Debug log
            \Log::info('User logged in', [
                'email' => $user->email,
                'role' => $user->role,
                'user_id' => $user->id
            ]);
            
            // Clear any previous session data
            $request->session()->forget(['login_attempts', 'failed_login']);
            
            $redirectRoute = match($user->role) {
                'admin' => 'admin.dashboard',
                'pelayan' => 'waiter.dashboard',
                'koki' => 'kitchen.dashboard',
                'pelanggan' => 'customer.home',
                default => 'login',
            };
            
            \Log::info('Redirecting to', ['route' => $redirectRoute]);
            
            // Use intended() untuk redirect yang lebih robust
            return redirect()->intended(route($redirectRoute))->with('success', 'Welcome back, ' . $user->name);
        }

        // Increment login attempts
        $attempts = $request->session()->get('login_attempts', 0) + 1;
        $request->session()->put('login_attempts', $attempts);

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
