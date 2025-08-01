<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class MultiSessionAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  $guard
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard = null)
    {
        // Set session name based on guard
        if ($guard) {
            $sessionName = config('session.cookie') . '_' . $guard;
            config(['session.cookie' => $sessionName]);
        }

        // Check if user is authenticated for this guard
        if ($guard && !Auth::guard($guard)->check()) {
            return redirect()->route('login', ['guard' => $guard]);
        }

        return $next($request);
    }
}
