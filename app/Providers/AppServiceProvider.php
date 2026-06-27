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
        // Dynamic Database Fallback Mechanism for web requests
        if (!$this->app->runningInConsole()) {
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

