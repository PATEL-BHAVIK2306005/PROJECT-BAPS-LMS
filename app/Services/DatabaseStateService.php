<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use Exception;

class DatabaseStateService
{
    /**
     * Check the health of both Offline and Online databases.
     * 
     * @return array
     */
    /**
     * Check the health of both Offline and Online databases with descriptive grades.
     * 
     * @return array
     */
    public function checkHealth()
    {
        $offlineHealth = \Illuminate\Support\Facades\Cache::remember('db_health_offline_status', 120, function() {
            return $this->measureConnection('mysql');
        });

        $onlineHealth = \Illuminate\Support\Facades\Cache::remember('db_health_online_status', 120, function() {
            return $this->measureConnection('mysql_online');
        });

        $gcpHealth = \Illuminate\Support\Facades\Cache::remember('db_health_gcp_status', 120, function() {
            return $this->measureConnection('gcp');
        });

        $itmbuHealth = \Illuminate\Support\Facades\Cache::remember('db_health_itmbu_status', 120, function() {
            return $this->measureConnection('itmbu_server');
        });

        $mongodbHealth = \Illuminate\Support\Facades\Cache::remember('db_health_mongodb_status', 120, function() {
            return $this->measureConnection('mongodb');
        });

        return [
            'offline' => $offlineHealth,
            'online' => $onlineHealth,
            'gcp' => $gcpHealth,
            'itmbu' => $itmbuHealth,
            'mongodb' => $mongodbHealth,
            'current' => config('database.default'),
        ];
    }

    /**
     * Measure connection latency and assign a health grade.
     */
    private function measureConnection($connectionName)
    {
        $start = microtime(true);
        try {
            if ($connectionName === 'mongodb') {
                if (!extension_loaded('mongodb')) {
                    throw new Exception("MongoDB PHP extension is not installed.");
                }
                $conn = DB::connection($connectionName);
                $conn->reconnect();
            } else {
                DB::connection($connectionName)->getPdo();
            }
            $latency = (microtime(true) - $start) * 1000; // in ms
            
            $grade = 'GOOD';
            if ($latency < 30) $grade = 'OUTSTANDING';
            elseif ($latency < 70) $grade = 'EXCELLENT';
            elseif ($latency < 150) $grade = 'VERY GOOD';
            elseif ($latency < 400) $grade = 'GOOD';
            else $grade = 'NEED TO OBSERVE';

            return [
                'status' => 'connected',
                'grade' => $grade,
                'latency' => round($latency, 2),
                'error' => null
            ];
        } catch (Exception $e) {
            return [
                'status' => 'disconnected',
                'grade' => 'CRITICAL',
                'latency' => 0,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Switch the database state in the environment.
     * Note: This only changes the current runtime config. 
     * To persist, the user must update the .env file.
     * 
     * @param string $state 'online' or 'offline'
     */
    public function switchState($state)
    {
        if ($state === 'online') {
            Config::set('database.default', 'mysql_online');
        } else {
            Config::set('database.default', 'mysql');
        }
    }
    /**
     * Switch the database state persistently by updating the .env file.
     * 
     * @param string $state 'online' or 'offline'
     * @return bool
     */
    public function persistState($state)
    {
        $path = base_path('.env');

        // Clear health check caches when state is switched to ensure fresh readings
        \Illuminate\Support\Facades\Cache::forget('db_health_offline_status');
        \Illuminate\Support\Facades\Cache::forget('db_health_online_status');
        \Illuminate\Support\Facades\Cache::forget('db_health_mysql_online');
        \Illuminate\Support\Facades\Cache::forget('db_health_gcp_status');
        \Illuminate\Support\Facades\Cache::forget('db_health_itmbu_status');
        \Illuminate\Support\Facades\Cache::forget('db_health_mongodb_status');

        if (file_exists($path)) {
            $content = file_get_contents($path);
            $newContent = preg_replace('/DB_STATE=.*/', "DB_STATE=$state", $content);
            file_put_contents($path, $newContent);
            return true;
        }

        return false;
    }

    /**
     * Run migrations on the specified connection.
     * 
     * @param string $connection 'mysql' or 'mysql_online'
     * @return string
     */
    public function runMigrations($connection)
    {
        try {
            \Illuminate\Support\Facades\Artisan::call('migrate', [
                '--database' => $connection,
                '--force' => true,
            ]);
            return "Migrations completed successfully on $connection.";
        } catch (Exception $e) {
            return "Error running migrations on $connection: " . $e->getMessage();
        }
    }

    /**
     * Summary of database statistics for 10,000+ users.
     */
    public function getStats()
    {
        $connection = config('database.default');
        
        return [
            'active_connection' => $connection,
            'user_count' => DB::connection($connection)->table('users')->count(),
            'state' => env('DB_STATE', 'offline'),
        ];
    }
}
