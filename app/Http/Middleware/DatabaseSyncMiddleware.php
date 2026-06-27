<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Services\DatabaseSyncService;

class DatabaseSyncMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (app()->environment('testing')) {
            return $next($request);
        }

        // Throttled background bi-directional sync (runs every 30 seconds in a background process)
        if (!Cache::has('db_bidirectional_sync_running')) {
            Cache::put('db_bidirectional_sync_running', true, now()->addSeconds(30));
            $this->triggerBackgroundSync();
        }

        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser.
     */
    public function terminate(Request $request, $response)
    {
        if (app()->environment('testing')) {
            return;
        }
        app(DatabaseSyncService::class)->syncPending();
    }

    /**
     * Spawn the `php artisan db:sync` command in a non-blocking background process.
     */
    protected function triggerBackgroundSync()
    {
        try {
            $artisan = base_path('artisan');
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows background task execution
                pclose(popen("start /B php \"{$artisan}\" db:sync > NUL 2>&1", "r"));
            } else {
                // UNIX background task execution
                exec("php \"{$artisan}\" db:sync > /dev/null 2>&1 &");
            }
        } catch (\Exception $e) {
            // Ignore execution errors for background processes
        }
    }
}
