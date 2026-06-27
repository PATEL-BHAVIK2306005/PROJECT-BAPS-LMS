<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class DatabaseSyncService
{
    protected $savedModels = [];
    protected $deletedModels = [];
    protected static $syncing = false;

    /**
     * Record a model save event.
     */
    public function recordSaved(Model $model)
    {
        if (self::$syncing) return;

        $table = $model->getTable();
        $excluded = ['sessions', 'cache', 'jobs', 'failed_jobs', 'migrations', 'personal_access_tokens', 'stored_files'];
        if (in_array($table, $excluded)) return;

        $class = get_class($model);
        $key = $model->getKeyName();
        $id = $model->getKey();

        // Use class and ID as unique key to prevent redundant syncing within the same request
        $this->savedModels["{$class}:{$id}"] = [
            'class' => $class,
            'table' => $table,
            'key' => $key,
            'id' => $id,
            'attributes' => $model->getAttributes()
        ];
    }

    /**
     * Record a model delete event.
     */
    public function recordDeleted(Model $model)
    {
        if (self::$syncing) return;

        $table = $model->getTable();
        $excluded = ['sessions', 'cache', 'jobs', 'failed_jobs', 'migrations', 'personal_access_tokens', 'stored_files'];
        if (in_array($table, $excluded)) return;

        $class = get_class($model);
        $key = $model->getKeyName();
        $id = $model->getKey();

        $this->deletedModels["{$class}:{$id}"] = [
            'class' => $class,
            'table' => $table,
            'key' => $key,
            'id' => $id
        ];
    }

    /**
     * Synchronize all collected writes to the mirror database connection.
     */
    public function syncPending()
    {
        if (empty($this->savedModels) && empty($this->deletedModels)) {
            return;
        }

        if (self::$syncing) return;
        self::$syncing = true;

        $activeConn = config('database.default');
        $targetConn = ($activeConn === 'mysql_online') ? 'mysql' : 'mysql_online';

        // Check cache for database health first
        $status = \Illuminate\Support\Facades\Cache::get("db_health_{$targetConn}");
        if ($status === 'unhealthy') {
            self::$syncing = false;
            return;
        }

        try {
            // Fast ping target connection
            DB::connection($targetConn)->getPdo();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Cache::put("db_health_{$targetConn}", 'unhealthy', now()->addMinutes(5));
            Log::warning("Database sync skipped. Mirror connection '{$targetConn}' is offline: " . $e->getMessage());
            self::$syncing = false;
            return;
        }

        // Process deletions
        foreach ($this->deletedModels as $del) {
            try {
                DB::connection($targetConn)
                    ->table($del['table'])
                    ->where($del['key'], $del['id'])
                    ->delete();
            } catch (\Exception $e) {
                Log::error("Async DB deletion sync failed on table '{$del['table']}' for ID '{$del['id']}': " . $e->getMessage());
            }
        }

        // Process saves (inserts/updates)
        foreach ($this->savedModels as $save) {
            try {
                $attributes = $save['attributes'];

                // Strip password if null to prevent overwriting with blanks
                if (isset($attributes['password']) && is_null($attributes['password'])) {
                    unset($attributes['password']);
                }

                // Filter out attributes that are not present in target database schema
                $columns = Schema::connection($targetConn)->getColumnListing($save['table']);
                if (!empty($columns)) {
                    $attributes = array_intersect_key($attributes, array_flip($columns));
                }

                $exists = DB::connection($targetConn)
                    ->table($save['table'])
                    ->where($save['key'], $save['id'])
                    ->exists();

                if ($exists) {
                    DB::connection($targetConn)
                        ->table($save['table'])
                        ->where($save['key'], $save['id'])
                        ->update($attributes);
                } else {
                    DB::connection($targetConn)
                        ->table($save['table'])
                        ->insert($attributes);
                }
            } catch (\Exception $e) {
                Log::error("Async DB save sync failed on table '{$save['table']}' for ID '{$save['id']}': " . $e->getMessage());
            }
        }

        // Clear stored queues
        $this->savedModels = [];
        $this->deletedModels = [];
        
        self::$syncing = false;
    }
}
