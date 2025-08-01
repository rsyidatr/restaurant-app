<?php

namespace App\Helpers;

class MultiSessionHelper
{
    /**
     * Get unique session name for guard
     */
    public static function getSessionName($guard = 'web')
    {
        return config('session.cookie') . '_' . $guard;
    }

    /**
     * Start session for specific guard
     */
    public static function startGuardSession($guard = 'web')
    {
        $sessionName = self::getSessionName($guard);
        
        // Close current session if active
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        
        // Set new session name and start
        session_name($sessionName);
        session_start();
        
        // Update Laravel session config
        config(['session.cookie' => $sessionName]);
    }

    /**
     * Get all available guards
     */
    public static function getAvailableGuards()
    {
        return ['web', 'admin', 'staff', 'customer'];
    }

    /**
     * Get guard display name
     */
    public static function getGuardDisplayName($guard)
    {
        $names = [
            'web' => 'Regular',
            'admin' => 'Admin',
            'staff' => 'Staff',
            'customer' => 'Customer'
        ];

        return $names[$guard] ?? ucfirst($guard);
    }

    /**
     * Get guard color class
     */
    public static function getGuardColor($guard)
    {
        $colors = [
            'web' => 'gray',
            'admin' => 'blue',
            'staff' => 'green',
            'customer' => 'purple'
        ];

        return $colors[$guard] ?? 'gray';
    }
}
