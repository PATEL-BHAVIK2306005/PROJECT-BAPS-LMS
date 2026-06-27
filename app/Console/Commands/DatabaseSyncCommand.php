<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;

class DatabaseSyncCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'db:sync';

    /**
     * The console command description.
     */
    protected $description = 'Perform conflict-free bi-directional synchronization between local and remote databases';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info("Initializing database synchronization pipeline...");

        $conn1 = 'mysql';         // Local DB
        $conn2 = 'mysql_online';  // TiDB Cloud / Remote DB

        // Check health of local DB before proceeding
        try {
            DB::connection($conn1)->getPdo();
        } catch (\Exception $e) {
            $this->error("Connection to local database '{$conn1}' failed (check if MySQL is started in XAMPP): " . $e->getMessage());
            return Command::FAILURE;
        }

        // Check health of remote DB before proceeding
        try {
            DB::connection($conn2)->getPdo();
        } catch (\Exception $e) {
            $this->error("Connection to mirror '{$conn2}' failed: " . $e->getMessage());
            return Command::FAILURE;
        }


        $tables = [
            'users',
            'departments',
            'staff',
            'courses',
            'lessons',
            'contents',
            'enrollments',
            'progress',
            'favorites',
            'tasks',
            'quizzes',
            'certificates',
            'notifications',
            'course_ratings',
            'achievements',
            'announcements',
            'attendances',
            'timetables',
            'timetable_entries',
            'gatepasses',
            'leaves',
            'password_approvals',
            'fee_payments',
            'code_executions',
            'question_banks',
            'personal_notes',
            'ipdc_assets',
            'external_certifications',
            'exam_forms',
            'messages',
            'results',
            'circulars',
            'lms_notifications',
            'ptm_reports',
            'time_capsules',
            'student_queries'
        ];

        foreach ($tables as $table) {
            if (!Schema::connection($conn1)->hasTable($table) || !Schema::connection($conn2)->hasTable($table)) {
                $this->warn("Skipping table '{$table}' (does not exist on both environments)");
                continue;
            }

            $this->info("Syncing table: {$table}...");

            try {
                // Get primary key
                $primaryKey = 'id'; // default fallback
                
                // Get columns for mapping filtering
                $cols1 = Schema::connection($conn1)->getColumnListing($table);
                $cols2 = Schema::connection($conn2)->getColumnListing($table);
                $commonCols = array_intersect($cols1, $cols2);

                if (empty($commonCols)) {
                    continue;
                }

                // Fetch all records
                $localRecords = DB::connection($conn1)->table($table)->get()->keyBy($primaryKey);
                $remoteRecords = DB::connection($conn2)->table($table)->get()->keyBy($primaryKey);

                $allIds = $localRecords->keys()->merge($remoteRecords->keys())->unique();

                $pushed = 0;
                $pulled = 0;

                foreach ($allIds as $id) {
                    $local = $localRecords->get($id);
                    $remote = $remoteRecords->get($id);

                    if ($local && !$remote) {
                        // Exists locally but not online -> Push to online
                        $data = array_intersect_key((array)$local, array_flip($commonCols));
                        DB::connection($conn2)->table($table)->insert($data);
                        $pushed++;
                    } elseif (!$local && $remote) {
                        // Exists online but not locally -> Pull to local
                        $data = array_intersect_key((array)$remote, array_flip($commonCols));
                        DB::connection($conn1)->table($table)->insert($data);
                        $pulled++;
                    } elseif ($local && $remote) {
                        // Exists on both -> Compare timestamps
                        $localUpdated = isset($local->updated_at) ? strtotime($local->updated_at) : 0;
                        $remoteUpdated = isset($remote->updated_at) ? strtotime($remote->updated_at) : 0;

                        if ($localUpdated > $remoteUpdated) {
                            // Local is newer -> Push
                            $data = array_intersect_key((array)$local, array_flip($commonCols));
                            DB::connection($conn2)->table($table)->where($primaryKey, $id)->update($data);
                            $pushed++;
                        } elseif ($remoteUpdated > $localUpdated) {
                            // Remote is newer -> Pull
                            $data = array_intersect_key((array)$remote, array_flip($commonCols));
                            DB::connection($conn1)->table($table)->where($primaryKey, $id)->update($data);
                            $pulled++;
                        }
                    }
                }

                if ($pushed > 0 || $pulled > 0) {
                    $this->line("   [✓] Sync Complete: {$pushed} pushed, {$pulled} pulled.");
                }
            } catch (\Exception $e) {
                $this->error("Error syncing table '{$table}': " . $e->getMessage());
                Log::error("Artisan db:sync failed for table '{$table}': " . $e->getMessage());
            }
        }

        $this->info("Database synchronization pipeline completed successfully.");
        return Command::SUCCESS;
    }
}
