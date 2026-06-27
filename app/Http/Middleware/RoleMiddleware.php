<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $sessionRole = session('user_role');

        if (!$sessionRole) {
            return redirect('/admin/login');
        }

        $roleHierarchy = [
            'admin' => 5,
            'office-assistant' => 4.75, // 175% access level (behalf of Dean and Admin)
            'dean' => 4,
            'hod' => 3,
            'faculty' => 2,
            'faculty-lecturer-coordinator' => 2.5, // 75% access
            'coordinator' => 2.3,
            'faculty-lecturer-lab' => 2.1,
            'cr' => 1.5,
            'deputy-cr' => 1.4,
            'staff' => 1.2,
            'moderator' => 1,
            'parent' => 0.6
        ];

        $userLevel = $roleHierarchy[$sessionRole] ?? 0;

        if (!empty($roles)) {
            $requiredLevel = min(array_map(fn($r) => $roleHierarchy[$r] ?? 99, $roles));
            
            // 90% Access for HOD (Exception for Dean-level tasks)
            if ($sessionRole === 'hod' && $requiredLevel <= 4) {
                 // HOD can access level 4 (Dean) routes for 90% coverage
                 return $next($request);
            }

            // Office Assistant operates on behalf of Dean
            if ($sessionRole === 'office-assistant' && $requiredLevel <= 4) {
                 // Office assistant can access all Dean operations
                 return $next($request);
            }

            if ($userLevel < $requiredLevel) {
                return abort(403, 'Unauthorized access levels.');
            }
        }

        return $next($request);
    }
}
