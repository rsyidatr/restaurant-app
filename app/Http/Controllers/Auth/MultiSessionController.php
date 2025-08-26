<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Helpers\MultiSessionHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MultiSessionController extends Controller
{
    /**
     * Show the multi-session dashboard
     */
    public function dashboard()
    {
        $activeSessions = $this->getActiveSessions();
        
        return view('auth.multi-session', compact('activeSessions'));
    }

    /**
     * Login with specific guard
     */
    public function loginWithGuard(Request $request, $guard = 'web')
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Set unique session name for this guard
        $this->setSessionForGuard($guard);

        $credentials = $request->only('email', 'password');

        if (Auth::guard($guard)->attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Store session info
            $this->storeSessionInfo($guard, Auth::guard($guard)->user());
            
            return redirect()->intended($this->getRedirectPath($guard));
        }

        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }

    /**
     * Logout from specific guard
     */
    public function logoutFromGuard(Request $request, $guard = 'web')
    {
        $this->setSessionForGuard($guard);
        
        Auth::guard($guard)->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Remove session info
        $this->removeSessionInfo($guard);
        
        return redirect()->route('multi-session.dashboard');
    }

    /**
     * Set session configuration for specific guard
     */
    private function setSessionForGuard($guard)
    {
        MultiSessionHelper::startGuardSession($guard);
    }

    /**
     * Get all active sessions
     */
    private function getActiveSessions()
    {
        $sessions = session('multi_sessions', []);
        $activeSessions = [];
        
        foreach ($sessions as $guard => $sessionData) {
            if ($this->isSessionActive($guard)) {
                $activeSessions[$guard] = $sessionData;
            }
        }
        
        return $activeSessions;
    }

    /**
     * Store session information
     */
    private function storeSessionInfo($guard, $user)
    {
        $sessions = session('multi_sessions', []);
        $sessions[$guard] = [
            'user_id' => $user->id,
            'user_name' => $user->name,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'login_time' => now(),
            'session_id' => session()->getId(),
        ];
        
        session(['multi_sessions' => $sessions]);
    }

    /**
     * Remove session information
     */
    private function removeSessionInfo($guard)
    {
        $sessions = session('multi_sessions', []);
        unset($sessions[$guard]);
        session(['multi_sessions' => $sessions]);
    }

    /**
     * Check if session is still active
     */
    private function isSessionActive($guard)
    {
        $this->setSessionForGuard($guard);
        return Auth::guard($guard)->check();
    }

    /**
     * Get redirect path based on guard and user role
     */
    private function getRedirectPath($guard)
    {
        // For staff guard, check user's actual role
        if ($guard === 'staff') {
            $user = Auth::guard($guard)->user();
            if ($user) {
                switch ($user->role) {
                    case 'pelayan':
                        return route('waiter.dashboard');
                    case 'koki':
                        return route('kitchen.dashboard');
                    case 'admin':
                        return route('admin.dashboard');
                    default:
                        return route('waiter.dashboard'); // Default fallback
                }
            }
            return route('waiter.dashboard'); // Fallback if no user
        }
        
        // For other guards
        switch ($guard) {
            case 'admin':
                return route('admin.dashboard');
            case 'customer':
                return route('customer.home');
            case 'web':
            default:
                // For web guard, check user role
                $user = Auth::guard($guard)->user();
                if ($user) {
                    switch ($user->role) {
                        case 'admin':
                            return route('admin.dashboard');
                        case 'pelayan':
                            return route('waiter.dashboard');
                        case 'koki':
                            return route('kitchen.dashboard');
                        case 'customer':
                            return route('customer.home');
                        default:
                            return route('home');
                    }
                }
                return route('home');
        }
    }

    /**
     * Switch between sessions
     */
    public function switchSession(Request $request, $guard)
    {
        if (!$this->isSessionActive($guard)) {
            return redirect()->route('multi-session.dashboard')
                ->with('error', 'Sesi untuk ' . $guard . ' tidak aktif.');
        }

        $this->setSessionForGuard($guard);
        
        return redirect($this->getRedirectPath($guard))
            ->with('success', 'Berhasil beralih ke sesi ' . $guard);
    }
}
