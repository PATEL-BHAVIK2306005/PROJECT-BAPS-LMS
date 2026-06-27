<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Force HTTPS URL generation when behind an HTTPS proxy (Codespaces / Render)
        if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        // If MySQL extension is not installed, automatically fallback to sqlite and file drivers
        if (!extension_loaded('pdo_mysql')) {
            config([
                'database.default' => 'sqlite',
                'cache.default' => 'file',
                'session.driver' => 'file',
            ]);
        }

        // Dynamic Database Fallback Mechanism for web requests
        if (extension_loaded('pdo_mysql') && !$this->app->runningInConsole()) {
            $defaultConn = config('database.default');
            $backupConn = 'mysql';

            if ($defaultConn !== $backupConn) {
                $cacheKey = "db_health_{$defaultConn}";
                $status = \Illuminate\Support\Facades\Cache::get($cacheKey);

                if ($status === 'unhealthy') {
                    // Fast path: bypass connection attempt and immediately use local backup
                    config(['database.default' => $backupConn]);
                } elseif ($status === null) {
                    // Assume healthy to avoid blocking boot time. If queries fail,
                    // global exception handler will catch it and mark connection as unhealthy.
                    \Illuminate\Support\Facades\Cache::put($cacheKey, 'healthy', now()->addDays(1));
                }
            }
        }

        // Register custom database storage driver
        \Illuminate\Support\Facades\Storage::extend('database', function ($app, $config) {
            return new \League\Flysystem\Filesystem(new \App\Filesystem\DatabaseAdapter());
        });

        // Register global event listeners for database replication sync
        if (!$this->app->environment('testing')) {
            \Illuminate\Support\Facades\Event::listen('eloquent.saved: *', function ($event, $data) {
                if (!empty($data[0]) && $data[0] instanceof \Illuminate\Database\Eloquent\Model) {
                    app(\App\Services\DatabaseSyncService::class)->recordSaved($data[0]);
                }
            });

            \Illuminate\Support\Facades\Event::listen('eloquent.deleted: *', function ($event, $data) {
                if (!empty($data[0]) && $data[0] instanceof \Illuminate\Database\Eloquent\Model) {
                    app(\App\Services\DatabaseSyncService::class)->recordDeleted($data[0]);
                }
            });
        }
    }
}

