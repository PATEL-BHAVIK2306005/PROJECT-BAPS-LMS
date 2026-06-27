<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin'       => \App\Http\Middleware\AdminMiddleware::class,
            'webcontainer'=> \App\Http\Middleware\WebContainerHeaders::class,
            'maintenance' => \App\Http\Middleware\MaintenanceModeMiddleware::class,
        ]);
        // Apply maintenance check to all web routes (append so sessions are started first)
        $middleware->web(append: [
            \App\Http\Middleware\MaintenanceModeMiddleware::class,
            \App\Http\Middleware\DatabaseSyncMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Illuminate\Database\QueryException $e, $request) {
            $message = $e->getMessage();
            $isConnectionError = false;

            if (
                str_contains($message, 'SQLSTATE[HY000] [2002]') ||
                str_contains($message, 'SQLSTATE[HY000] [2003]') ||
                str_contains($message, 'Connection refused') ||
                str_contains($message, 'Unknown database') ||
                str_contains($message, 'Access denied for user') ||
                str_contains($message, 'SQLSTATE[08006]') ||
                str_contains($message, 'SQLSTATE[08001]') ||
                $e->getCode() == 2002 ||
                $e->getCode() == 2003
            ) {
                $isConnectionError = true;
            }

            if ($isConnectionError) {
                $defaultConn = config('database.default');
                $backupConn = 'mysql';

                if ($defaultConn !== $backupConn) {
                    $cacheKey = "db_health_{$defaultConn}";
                    \Illuminate\Support\Facades\Cache::put($cacheKey, 'unhealthy', now()->addMinutes(10));

                    \Illuminate\Support\Facades\Log::error("QueryException database connection failed. Falling back to local backup. Error: " . $message);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'error' => 'Database connection failed. Retrying with local backup.',
                            'fallback' => true
                        ], 503);
                    }

                    return redirect()->refresh()->with('warning', 'Database connection issues detected. Switched to local backup.');
                }
            }

            return null;
        });

        $exceptions->render(function (\PDOException $e, $request) {
            $message = $e->getMessage();
            $isConnectionError = false;

            if (
                str_contains($message, 'SQLSTATE[HY000] [2002]') ||
                str_contains($message, 'SQLSTATE[HY000] [2003]') ||
                str_contains($message, 'Connection refused') ||
                str_contains($message, 'Unknown database') ||
                str_contains($message, 'Access denied for user') ||
                str_contains($message, 'SQLSTATE[08006]') ||
                str_contains($message, 'SQLSTATE[08001]') ||
                $e->getCode() == 2002 ||
                $e->getCode() == 2003
            ) {
                $isConnectionError = true;
            }

            if ($isConnectionError) {
                $defaultConn = config('database.default');
                $backupConn = 'mysql';

                if ($defaultConn !== $backupConn) {
                    $cacheKey = "db_health_{$defaultConn}";
                    \Illuminate\Support\Facades\Cache::put($cacheKey, 'unhealthy', now()->addMinutes(10));

                    \Illuminate\Support\Facades\Log::error("PDOException database connection failed. Falling back to local backup. Error: " . $message);

                    if ($request->expectsJson()) {
                        return response()->json([
                            'error' => 'Database connection failed. Retrying with local backup.',
                            'fallback' => true
                        ], 503);
                    }

                    return redirect()->refresh()->with('warning', 'Database connection issues detected. Switched to local backup.');
                }
            }

            return null;
        });
    })->create();
