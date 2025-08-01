<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaffMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$roles
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect()->route('staff.login');
        }

        $user = Auth::user();
        $currentRole = session('current_role', $user->role);

        // Check if user has required role
        if (!empty($roles) && !in_array($currentRole, $roles) && !in_array($user->role, $roles)) {
            abort(403, 'Akses tidak diizinkan untuk role Anda.');
        }

        return $next($request);
    }
}
