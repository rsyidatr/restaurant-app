<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class StaffLoginController extends Controller
{
    /**
     * Show the staff login form
     */
    public function showLoginForm()
    {
        return view('auth.staff-login');
    }

    /**
     * Handle staff login
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
            'role' => 'required|in:admin,chef,waiter'
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password wajib diisi.',
            'role.required' => 'Pilih posisi Anda.',
            'role.in' => 'Posisi yang dipilih tidak valid.'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput($request->only('email', 'role'));
        }

        $credentials = $request->only('email', 'password');
        $requestedRole = $request->role;

        // Attempt authentication
        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $user = Auth::user();
            
            // Check if user has the requested role
            if (!$this->hasValidRole($user, $requestedRole)) {
                Auth::logout();
                return back()->withErrors([
                    'role' => 'Anda tidak memiliki akses untuk posisi ' . $this->getRoleDisplayName($requestedRole) . '.'
                ])->withInput($request->only('email', 'role'));
            }

            $request->session()->regenerate();

            // Store current role in session
            session(['current_role' => $requestedRole]);

            // Redirect based on role
            return redirect()->intended($this->getRedirectPath($requestedRole))
                ->with('success', 'Berhasil login sebagai ' . $this->getRoleDisplayName($requestedRole));
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->withInput($request->only('email', 'role'));
    }

    /**
     * Handle staff logout
     */
    public function logout(Request $request)
    {
        $role = session('current_role', 'staff');
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('staff.login')
            ->with('success', 'Berhasil logout dari sistem.');
    }

    /**
     * Check if user has valid role
     */
    private function hasValidRole($user, $requestedRole)
    {
        $userRole = strtolower($user->role);
        
        // Admin can access any staff role
        if ($userRole === 'admin') {
            return true;
        }
        
        // Map roles
        $roleMapping = [
            'chef' => ['chef', 'koki'],
            'waiter' => ['waiter', 'pelayan'],
            'admin' => ['admin']
        ];
        
        return in_array($userRole, $roleMapping[$requestedRole] ?? []);
    }

    /**
     * Get redirect path based on role
     */
    private function getRedirectPath($role)
    {
        switch ($role) {
            case 'admin':
                return route('admin.dashboard');
            case 'chef':
                return route('chef.dashboard');
            case 'waiter':
                return route('waiter.dashboard');
            default:
                return route('staff.dashboard');
        }
    }

    /**
     * Get role display name
     */
    private function getRoleDisplayName($role)
    {
        $names = [
            'admin' => 'Admin',
            'chef' => 'Chef',
            'waiter' => 'Waiter'
        ];

        return $names[$role] ?? ucfirst($role);
    }

    /**
     * Show password reset form
     */
    public function showPasswordRequestForm()
    {
        return view('auth.staff-password-reset');
    }
}
