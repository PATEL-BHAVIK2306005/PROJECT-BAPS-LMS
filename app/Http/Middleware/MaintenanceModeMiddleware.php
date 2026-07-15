<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class MaintenanceModeMiddleware
{
    /**
     * Bypass routes — always accessible even in maintenance
     */
    protected $bypass = [
        'login',
        'admin/login',
        'logout',
        'admin/logout',
        'forgot-password',
        'register',
        'parent/register',
        'track-application',
        'track-application/submit-tc',
        'admin/secure-verify',
        'admin/maintenance/toggle',
        'admin/maintenance/status',
        'storage',
        'up',
    ];

    public function handle(Request $request, Closure $next)
    {
        // Check if maintenance file exists and is enabled
        $maintenanceFile = storage_path('app/maintenance_state.json');

        if (!file_exists($maintenanceFile)) {
            return $next($request);
        }

        $state = json_decode(file_get_contents($maintenanceFile), true);

        if (empty($state['enabled'])) {
            return $next($request);
        }

        // Always allow bypass routes and their sub-paths
        foreach ($this->bypass as $route) {
            if ($request->is($route) || $request->is($route . '/*')) {
                return $next($request);
            }
        }

        // Allow Admin, Dean, HOD, Office-Assistant, and CR through — they can always access
        $role = strtolower(trim(session('user_role')));
        if (in_array($role, ['admin', 'dean', 'hod', 'cr', 'office-assistant'])) {
            return $next($request);
        }

        // Everyone else → maintenance page
        return response()->view('maintenance', [
            'message'    => $state['message'] ?? 'System under maintenance.',
            'enabled_by' => $state['enabled_by'] ?? 'Admin',
            'enabled_at' => $state['enabled_at'] ?? now()->toDateTimeString(),
        ], 503);
    }
}
