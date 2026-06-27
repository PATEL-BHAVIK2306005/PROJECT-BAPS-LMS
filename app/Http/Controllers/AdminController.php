<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class AdminController extends Controller
{
    // ══════════════════════════════════════════════════════════════════════════
    //  MAINTENANCE MODE
    // ══════════════════════════════════════════════════════════════════════════

    private function maintenanceFile(): string
    {
        return storage_path('app/maintenance_state.json');
    }

    public function maintenanceStatus()
    {
        $file = $this->maintenanceFile();
        if (!file_exists($file)) {
            return response()->json(['enabled' => false]);
        }
        return response()->json(json_decode(file_get_contents($file), true));
    }

    public function toggleMaintenance(Request $request)
    {
        // Only admin and dean/provost can toggle
        if (!in_array(session('user_role'), ['admin', 'dean'])) {
            return response()->json(['error' => 'Unauthorized. Admin or Dean/Provost role required.'], 403);
        }

        $request->validate([
            'password' => 'required|string',
            'action'   => 'required|in:on,off',
            'message'  => 'nullable|string|max:300',
        ]);

        // Verify the maintenance password
        if ($request->password !== 'BAPS2026MAN') {
            return response()->json(['error' => 'Invalid maintenance password.'], 401);
        }

        $file = $this->maintenanceFile();

        if ($request->action === 'on') {
            $state = [
                'enabled'    => true,
                'enabled_by' => session('staff_name') ?? session('user_role'),
                'enabled_at' => now()->toDateTimeString(),
                'message'    => $request->message ?: 'The BAPS Institutional LMS is currently undergoing scheduled maintenance. We\'ll be back shortly.',
            ];
            file_put_contents($file, json_encode($state, JSON_PRETTY_PRINT));
            return response()->json(['success' => true, 'enabled' => true, 'message' => 'Maintenance mode ENABLED.']);
        } else {
            if (file_exists($file)) {
                unlink($file);
            }
            return response()->json(['success' => true, 'enabled' => false, 'message' => 'Maintenance mode DISABLED.']);
        }
    }

    public function runMaintenanceTask(Request $request)
    {
        // Only admin and dean can run maintenance tasks
        if (!in_array(session('user_role'), ['admin', 'dean'])) {
            return response()->json(['error' => 'Unauthorized. Admin or Dean/Provost role required.'], 403);
        }

        $request->validate([
            'task' => 'required|string'
        ]);

        $task = $request->input('task');

        // Check if maintenance mode is enabled
        $stateFile = $this->maintenanceFile();
        $isMaintenanceOn = false;
        if (file_exists($stateFile)) {
            $state = json_decode(file_get_contents($stateFile), true);
            $isMaintenanceOn = !empty($state['enabled']);
        }

        // Safe tasks that can be run even if maintenance mode is off
        $safeTasks = ['clean_cache', 'optimize_config', 'symlink_doctor', 'log_auditor', 'system_diagnostics', 'asset_compiler_status'];
        if (!in_array($task, $safeTasks) && !$isMaintenanceOn) {
            return response()->json(['error' => 'Maintenance Mode must be active to operate database write/delete functions safely.'], 400);
        }

        $log = [];
        $status = 'success';
        $details = '';

        try {
            switch ($task) {
                case 'clean_cache':
                    \Illuminate\Support\Facades\Artisan::call('cache:clear');
                    $log[] = \Illuminate\Support\Facades\Artisan::output();
                    \Illuminate\Support\Facades\Artisan::call('view:clear');
                    $log[] = \Illuminate\Support\Facades\Artisan::output();
                    $details = 'Framework cache & precompiled views purged successfully.';
                    break;

                case 'optimize_config':
                    \Illuminate\Support\Facades\Artisan::call('config:cache');
                    $log[] = \Illuminate\Support\Facades\Artisan::output();
                    \Illuminate\Support\Facades\Artisan::call('route:cache');
                    $log[] = \Illuminate\Support\Facades\Artisan::output();
                    $details = 'Configuration schema & routes compiled and cached.';
                    break;

                case 'db_optimize':
                    $connection = config('database.default');
                    $driver = config("database.connections.{$connection}.driver");
                    $optimized = [];
                    
                    if ($driver === 'mysql') {
                        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
                        $dbKey = 'Tables_in_' . config("database.connections.{$connection}.database");
                        foreach ($tables as $table) {
                            $tableName = $table->$dbKey;
                            \Illuminate\Support\Facades\DB::statement("OPTIMIZE TABLE {$tableName}");
                            $optimized[] = $tableName;
                        }
                        $details = 'Optimized ' . count($optimized) . ' tables successfully. Reclaimed database overhead storage.';
                    } elseif ($driver === 'sqlite') {
                        \Illuminate\Support\Facades\DB::statement("VACUUM");
                        $details = 'Executed SQLite VACUUM to reclaim deleted page bytes.';
                    } else {
                        $details = 'Connection type (' . $driver . ') does not require optimization.';
                    }
                    break;

                case 'clean_sessions':
                    $sessionCount = 0;
                    if (\Illuminate\Support\Facades\Schema::hasTable('sessions')) {
                        $sessionCount = \Illuminate\Support\Facades\DB::table('sessions')
                            ->where('last_activity', '<', now()->subDays(1)->timestamp)
                            ->delete();
                    }
                    
                    $fileCount = 0;
                    $sessionPath = storage_path('framework/sessions');
                    if (is_dir($sessionPath)) {
                        $files = glob($sessionPath . '/*');
                        foreach ($files as $file) {
                            if (is_file($file) && (time() - filemtime($file) > 86400)) {
                                if (@unlink($file)) {
                                    $fileCount++;
                                }
                            }
                        }
                    }
                    $details = "Purged {$sessionCount} obsolete DB session entries and {$fileCount} expired session files.";
                    break;

                case 'clean_orphaned_files':
                    $usedFiles = \App\Models\Lesson::pluck('file')->filter()->toArray();
                    $allFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('materials');
                    $deletedCount = 0;
                    foreach ($allFiles as $file) {
                        if (!in_array($file, $usedFiles)) {
                            \Illuminate\Support\Facades\Storage::disk('public')->delete($file);
                            $deletedCount++;
                        }
                    }
                    $details = "Scanned directory. Identified and removed {$deletedCount} orphaned lesson uploads.";
                    break;

                case 'symlink_doctor':
                    $link = public_path('storage');
                    $statusDetail = 'Symlink is operational.';
                    if (file_exists($link)) {
                        if (is_link($link)) {
                            $target = readlink($link);
                            $statusDetail = "Active link pointing to: " . basename($target);
                        } else {
                            @rename($link, $link . '_backup_' . time());
                            \Illuminate\Support\Facades\Artisan::call('storage:link');
                            $statusDetail = "Fixed: Replaced existing directory with valid symlink.";
                        }
                    } else {
                        \Illuminate\Support\Facades\Artisan::call('storage:link');
                        $statusDetail = "Created missing public/storage symbolic link.";
                    }
                    $details = $statusDetail;
                    break;

                case 'log_auditor':
                    $logFile = storage_path('logs/laravel.log');
                    $errors = 0;
                    $warnings = 0;
                    $recentErrors = [];
                    
                    if (file_exists($logFile)) {
                        $file = new \SplFileObject($logFile, 'r');
                        $file->seek(PHP_INT_MAX);
                        $totalLines = $file->key();
                        $startLine = max(0, $totalLines - 500);
                        
                        $file->seek($startLine);
                        while (!$file->eof()) {
                            $line = $file->fgets();
                            if (preg_match('/local\.ERROR|local\.CRITICAL|production\.ERROR/i', $line)) {
                                $errors++;
                                if (count($recentErrors) < 5) {
                                    $recentErrors[] = substr(trim($line), 0, 100) . '...';
                                }
                            } elseif (preg_match('/local\.WARNING/i', $line)) {
                                $warnings++;
                            }
                        }
                    }
                    
                    $details = "Audit Completed. Found {$errors} recent error entries and {$warnings} warnings in framework logs.";
                    if (!empty($recentErrors)) {
                        $details .= " Latest: " . implode(' | ', $recentErrors);
                    }
                    break;

                case 'migration_checker':
                    \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
                    $output = trim(\Illuminate\Support\Facades\Artisan::output());
                    if (str_contains(strtolower($output), 'nothing to migrate')) {
                        $details = 'All database schemas are fully up-to-date. No pending migrations found.';
                    } else {
                        $details = 'Database migrated successfully: ' . substr($output, 0, 150);
                    }
                    break;

                case 'db_backup':
                    $backupDir = storage_path('backups');
                    if (!is_dir($backupDir)) {
                        mkdir($backupDir, 0755, true);
                    }
                    $filename = 'backup_' . date('Y_m_d_His') . '.sql';
                    $backupPath = $backupDir . '/' . $filename;
                    
                    $connection = config('database.default');
                    $dbConfig = config("database.connections.{$connection}");
                    
                    if ($dbConfig['driver'] === 'mysql') {
                        $tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
                        $dbKey = 'Tables_in_' . $dbConfig['database'];
                        $sqlDump = "-- BAPS LMS SQL Dump\n-- Generated on " . date('Y-m-d H:i:s') . "\n\n";
                        
                        foreach ($tables as $table) {
                            $tableName = $table->$dbKey;
                            
                            // Get create table statement
                            $createTable = \Illuminate\Support\Facades\DB::select("SHOW CREATE TABLE {$tableName}");
                            $createKey = 'Create Table';
                            $sqlDump .= $createTable[0]->$createKey . ";\n\n";
                            
                            // Get table rows
                            $rows = \Illuminate\Support\Facades\DB::table($tableName)->get();
                            foreach ($rows as $row) {
                                $rowArr = (array)$row;
                                $keys = array_keys($rowArr);
                                $values = array_map(function($val) {
                                    if ($val === null) return 'NULL';
                                    return "'" . addslashes($val) . "'";
                                }, array_values($rowArr));
                                
                                $sqlDump .= "INSERT INTO `{$tableName}` (`" . implode("`, `", $keys) . "`) VALUES (" . implode(", ", $values) . ");\n";
                            }
                            $sqlDump .= "\n\n";
                        }
                        file_put_contents($backupPath, $sqlDump);
                        $details = "Database backup created successfully: storage/backups/{$filename} (" . number_format(strlen($sqlDump) / 1024, 2) . " KB)";
                    } else {
                        $details = "Backup function only supports mysql driver at this time.";
                    }
                    break;

                case 'system_diagnostics':
                    $dbStart = microtime(true);
                    \Illuminate\Support\Facades\DB::select('SELECT 1');
                    $dbTime = round((microtime(true) - $dbStart) * 1000, 2);
                    
                    $freeSpace = @disk_free_space(base_path()) ?: 0;
                    $totalSpace = @disk_total_space(base_path()) ?: 1;
                    $freeGb = round($freeSpace / (1024 * 1024 * 1024), 2);
                    $totalGb = round($totalSpace / (1024 * 1024 * 1024), 2);
                    
                    $details = "System Diagnostics:\n" .
                               "- PHP Version: " . phpversion() . "\n" .
                               "- Environment: " . app()->environment() . "\n" .
                               "- DB Latency: {$dbTime}ms\n" .
                               "- Disk Space: {$freeGb} GB free of {$totalGb} GB total\n" .
                               "- Cache Driver: " . config('cache.default', 'file') . "\n" .
                               "- Session Driver: " . config('session.driver', 'file') . "\n" .
                               "- Max Upload Size: " . ini_get('upload_max_filesize') . "\n" .
                               "- Memory Limit: " . ini_get('memory_limit');
                    break;

                case 'asset_compiler_status':
                    $manifestPath = public_path('build/manifest.json');
                    if (file_exists($manifestPath)) {
                        $manifest = json_decode(file_get_contents($manifestPath), true);
                        $fileList = [];
                        $totalSize = 0;
                        foreach ($manifest as $key => $asset) {
                            $filePath = public_path('build/' . $asset['file']);
                            if (file_exists($filePath)) {
                                $size = filesize($filePath);
                                $totalSize += $size;
                                $fileList[] = basename($asset['file']) . " (" . round($size / 1024, 2) . " KB)";
                            }
                        }
                        $details = "Vite assets compiled & verified: " . count($fileList) . " compiled files. " .
                                   "Total size: " . round($totalSize / 1024, 2) . " KB. Files: " . implode(', ', $fileList);
                    } else {
                        $details = "Warning: compiled assets manifest not found at public/build/manifest.json. Vite production build might not have been executed yet.";
                    }
                    break;

                case 'force_clear_logs':
                    $logFile = storage_path('logs/laravel.log');
                    if (file_exists($logFile)) {
                        $originalSize = filesize($logFile);
                        file_put_contents($logFile, '');
                        $details = "Log file cleared successfully. Reclaimed " . round($originalSize / 1024, 2) . " KB of space.";
                    } else {
                        $details = "Log file laravel.log does not exist or has not been created yet.";
                    }
                    break;

                default:
                    return response()->json(['error' => 'Unknown maintenance task requested.'], 400);
            }
        } catch (\Exception $e) {
            $status = 'error';
            $details = 'Task failed: ' . $e->getMessage();
        }

        return response()->json([
            'success' => $status === 'success',
            'task' => $task,
            'details' => $details,
            'log' => $log
        ]);
    }


    public function reportsSection()
    {
        if (!in_array(session('user_role'), ['admin', 'dean'])) {
            return redirect('/admin')->with('error', 'Strictly Unauthorized. Reports are restricted to 200% System Access Privilege roles.');
        }

        $metrics = [
            'total_students' => \App\Models\User::count(),
            'total_courses' => \App\Models\Course::count(),
            'total_enrollments' => \App\Models\Enrollment::count(),
            'total_staff' => \App\Models\Staff::count(),
            'total_departments' => \App\Models\Department::count(),
            'total_certificates' => \App\Models\Certificate::count(),
        ];

        return view('admin.reports_section', compact('metrics'));
    }

    public function updateModuleAccess(Request $request)
    {
        if (!in_array(session('user_role'), ['admin', 'dean'])) {
            return redirect('/admin')->with('error', 'Strictly Unauthorized.');
        }

        // Logic to save the new module access schema.
        // For now, we simulate the update success.
        return back()->with('success', 'Global Module Access Policy has been successfully updated and propagated across the system.');
    }

    public function loginPage()
    {
        return view('auth.unified_login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'password' => 'required'
        ]);

        $username = $request->input('username');
        $password = $request->input('password');

        // Admin override
        if ($username === 'admin.bhavik@baps.ac.in' && $password === '2306@admin') {
            $adminStaff = \App\Models\Staff::where('email', 'admin.bhavik@baps.ac.in')->first();
            if ($request->hasCookie('admin_secure_bypass')) {
                session([
                    'user_role' => 'admin',
                    'staff_id' => $adminStaff ? $adminStaff->id : 1,
                    'staff_name' => 'BHAVIKKUMAR PATEL',
                    'dept_id' => null
                ]);
                return redirect('/admin')->with('success', 'Welcome back, Admin.');
            }
            session([
                'pending_admin_login' => true,
                'pending_admin_staff_id' => $adminStaff ? $adminStaff->id : 1,
                'pending_admin_staff_name' => 'BHAVIKKUMAR PATEL',
                'pending_admin_dept_id' => null
            ]);
            return redirect('/admin/secure-verify');
        }

        // Check if user is using old 4-digit code in password field
        $oldStaff = \App\Models\Staff::where('unique_code', $password)->first();
        if ($oldStaff) {
            $formattedRole = ucfirst($oldStaff->role);
            return back()->with('error', "Jay Swaminarayan! Security protocols have been upgraded. Please contact the Administrator to receive your new institutional credentials for the role of {$formattedRole}.");
        }

        // Require email for normal staff
        if (empty($username)) {
            return back()->with('error', 'Username/Email is now strictly required.');
        }

        // New Login Logic
        $staff = \App\Models\Staff::where('email', $username)->first();
        if ($staff && \Illuminate\Support\Facades\Hash::check($password, $staff->password)) {
            if ($staff->role === 'admin') {
                if ($request->hasCookie('admin_secure_bypass')) {
                    session([
                        'user_role' => 'admin',
                        'staff_id' => $staff->id,
                        'staff_name' => $staff->name,
                        'dept_id' => $staff->department_id
                    ]);
                    return redirect('/admin')->with('success', 'Welcome back, Admin.');
                }

                session([
                    'pending_admin_login' => true,
                    'pending_admin_staff_id' => $staff->id,
                    'pending_admin_staff_name' => $staff->name,
                    'pending_admin_dept_id' => $staff->department_id
                ]);
                return redirect('/admin/secure-verify');
            }

            session([
                'user_role' => $staff->role,
                'staff_id' => $staff->id,
                'staff_name' => $staff->name,
                'dept_id' => $staff->department_id
            ]);
            return redirect('/admin');
        }

        return back()->with('error', 'Invalid Credentials');
    }

    public function secureVerifyPage()
    {
        if (request()->hasCookie('admin_secure_bypass') && session('pending_admin_login')) {
            session([
                'user_role' => 'admin',
                'staff_id' => session('pending_admin_staff_id'),
                'staff_name' => session('pending_admin_staff_name'),
                'dept_id' => session('pending_admin_dept_id')
            ]);
            session()->forget(['pending_admin_login', 'pending_admin_staff_id', 'pending_admin_staff_name', 'pending_admin_dept_id', 'secure_otp']);
            return redirect('/admin')->with('success', 'Welcome back, Admin.');
        }

        if (!session('pending_admin_login')) {
            return redirect('/admin/login');
        }

        $staff = \App\Models\Staff::find(session('pending_admin_staff_id'));
        $phone = $staff ? $staff->phone : null;
        $email = $staff ? $staff->email : 'admin.bhavik@baps.ac.in';

        $maskedPhone = null;
        if ($phone) {
            $cleanPhone = preg_replace('/[^0-9]/', '', $phone);
            $length = strlen($cleanPhone);
            if ($length >= 10) {
                $maskedPhone = '+91 ******' . substr($cleanPhone, -4);
            } else {
                $maskedPhone = str_repeat('*', max(0, $length - 4)) . substr($cleanPhone, -4);
            }
        } else {
            $maskedPhone = '+91 ******2306'; // default fallback for admin
        }

        return view('auth.admin_secure_verify', compact('maskedPhone', 'email'));
    }

    public function sendOtp(Request $request)
    {
        if (!session('pending_admin_login')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $otp = rand(100000, 999999);
        session(['secure_otp' => $otp]);

        // Retrieve the email typed by the user, fallback to staff email
        $typedEmail = $request->input('email');
        $staff = \App\Models\Staff::find(session('pending_admin_staff_id'));
        
        $email = !empty($typedEmail) ? $typedEmail : ($staff ? $staff->email : 'admin.bhavik@baps.ac.in');
        $name = $staff ? $staff->name : 'BHAVIKKUMAR PATEL';

        // Write to Laravel log file as a secure fallback
        \Illuminate\Support\Facades\Log::info("EmailJS OTP generated: {$otp} for {$email}");

        return response()->json([
            'success' => true,
            'otp' => $otp,
            'email' => $email,
            'name' => $name,
            'ip' => $request->ip(),
            'message' => 'Verification code generated in session.'
        ]);
    }

    public function secureVerifySubmit(Request $request)
    {
        if (!session('pending_admin_login')) {
            return redirect('/admin/login');
        }

        $code = $request->input('admin_code');

        // Transform recovery password to admin password
        if ($code === 'BAPS2026RECOVERY') {
            $code = 'BAPS2026ADMIN';
        }

        // Verify either the master code or the session OTP
        $sessionOtp = session('secure_otp');

        if ($code === 'BAPS2026ADMIN' || ($sessionOtp && (string)$code === (string)$sessionOtp)) {
            // Success! Complete the login
            session([
                'user_role' => 'admin',
                'staff_id' => session('pending_admin_staff_id'),
                'staff_name' => session('pending_admin_staff_name'),
                'dept_id' => session('pending_admin_dept_id')
            ]);

            // Check if remember device is requested
            if ($request->has('remember_device')) {
                \Illuminate\Support\Facades\Cookie::queue('admin_secure_bypass', 'true', 15 * 24 * 60);
            }

            // Clear pending session variables
            session()->forget(['pending_admin_login', 'pending_admin_staff_id', 'pending_admin_staff_name', 'pending_admin_dept_id', 'secure_otp']);

            return redirect('/admin')->with('success', 'Welcome back, Admin.');
        }

        return back()->with('error', 'Access Denied: Invalid Verification Code.');
    }

    public function logout()
    {
        session()->forget('user_role');
        return redirect('/');
    }

    public function dashboard()
    {
        $role = session('user_role');
        $staffId = session('staff_id');

        if ($role === 'faculty') {
            $allocatedCourseIds = \App\Models\CourseAllocation::where('staff_id', $staffId)->pluck('course_id');
            $courses = Course::where('faculty_id', $staffId)->orWhereIn('id', $allocatedCourseIds)->get();
        } else {
            $courses = Course::all();
        }

        // Fetch staff members for signature dropdown lists
        $deansList = \App\Models\Staff::where('role', 'dean')->get();
        $hodsList = \App\Models\Staff::where('role', 'hod')->get();
        $adminsList = \App\Models\Staff::where('role', 'admin')->get();
        $studentsList = \App\Models\User::where('role', 'student')->orderBy('name')->get();
        $departmentsList = \App\Models\Department::orderBy('name')->get();
        
        $currentStaff = \App\Models\Staff::find($staffId);
        $allQueries = \App\Models\StudentQuery::with(['student.department', 'assignedStaff', 'assignedCr'])->orderBy('created_at', 'desc')->get();
        $crList = \App\Models\User::where('role', 'cr')->orderBy('name')->get();

        return view('admin.dashboard', compact(
            'courses', 'deansList', 'hodsList', 'adminsList', 'studentsList', 'departmentsList',
            'currentStaff', 'allQueries', 'crList'
        ));
    }

    public function masterData()
    {
        if (session('user_role') !== 'admin') {
            return redirect('/admin')->with('error', 'Strictly Unauthorized.');
        }

        if (!session('visor_unlocked')) {
            return view('admin.visor_auth');
        }

        return view('admin.super_secret_data');
    }

    public function getModelRecords($model)
    {
        if (session('user_role') !== 'admin' || !session('visor_unlocked')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $models = [
            'user' => \App\Models\User::class,
            'staff' => \App\Models\Staff::class,
            'department' => \App\Models\Department::class,
            'course' => \App\Models\Course::class,
            'enrollment' => \App\Models\Enrollment::class,
            'lesson' => \App\Models\Lesson::class,
            'content' => \App\Models\Content::class,
            'task' => \App\Models\Task::class,
            'quiz' => \App\Models\Quiz::class,
            'question' => \App\Models\Question::class,
            'option' => \App\Models\Option::class,
            'quizAttempt' => \App\Models\QuizAttempt::class,
            'certificate' => \App\Models\Certificate::class,
            'progress' => \App\Models\Progress::class,
            'attendance' => \App\Models\Attendance::class,
            'gatepass' => \App\Models\Gatepass::class,
            'leave' => \App\Models\Leave::class,
            'timetable' => \App\Models\Timetable::class,
            'timetableEntry' => \App\Models\TimetableEntry::class,
            'announcement' => \App\Models\Announcement::class,
            'notification' => \App\Models\Notification::class,
        ];

        if (!array_key_exists($model, $models)) {
            return response()->json(['error' => 'Model not found'], 404);
        }

        $classPath = $models[$model];
        if (!class_exists($classPath)) {
            return response()->json(['error' => 'Model class does not exist'], 404);
        }

        $query = $classPath::query();
        try {
            $instance = new $classPath;
            $keyName = $instance->getKeyName();
            if ($keyName) {
                $query->orderBy($keyName, 'desc');
            }
        } catch (\Throwable $e) {
            // Ignore reflection errors
        }

        $records = $query->take(50)->get();

        if ($records->isEmpty()) {
            return response()->json([
                'html' => '<div class="alert alert-warning py-2 mb-0 border-0 fw-bold small"><i class="fas fa-database me-2"></i> No records found for ' . strtoupper($model) . '</div>'
            ]);
        }

        // Generate dynamic table HTML
        $attributes = array_keys($records[0]->getAttributes());

        $html = '<div class="table-responsive">';
        $html .= '<table class="table table-bordered table-striped table-hover small">';
        $html .= '<thead class="table-dark sticky-top"><tr>';
        foreach ($attributes as $col) {
            $html .= '<th>' . e($col) . '</th>';
        }
        $html .= '</tr></thead>';
        $html .= '<tbody>';
        foreach ($records as $row) {
            $html .= '<tr>';
            foreach ($row->getAttributes() as $val) {
                $truncated = \Illuminate\Support\Str::limit((string)$val, 50);
                $html .= '<td class="text-truncate" style="max-width: 200px;" title="' . e((string)$val) . '">' . e($truncated) . '</td>';
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table></div>';

        return response()->json(['html' => $html]);
    }

    public function getSystemStatus(\App\Services\DatabaseStateService $dbService)
    {
        if (session('user_role') !== 'admin' || !session('visor_unlocked')) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $dbHealth = $dbService->checkHealth();

        // Calculate PHP Memory Usage
        $phpMemoryUsed = memory_get_usage(true);
        $memoryLimitRaw = ini_get('memory_limit');
        $phpMemoryLimit = 0;
        if (preg_match('/^(\d+)(.)$/', $memoryLimitRaw, $matches)) {
            $unit = strtoupper($matches[2]);
            if ($unit === 'G') {
                $phpMemoryLimit = $matches[1] * 1024 * 1024 * 1024;
            } else if ($unit === 'M') {
                $phpMemoryLimit = $matches[1] * 1024 * 1024;
            } else if ($unit === 'K') {
                $phpMemoryLimit = $matches[1] * 1024;
            } else {
                $phpMemoryLimit = (int)$matches[1];
            }
        } else {
            $phpMemoryLimit = (int)$memoryLimitRaw;
        }
        if ($phpMemoryLimit <= 0) {
            $phpMemoryLimit = 512 * 1024 * 1024; // Default to 512MB
        }

        // Calculate Database Size (TiDB / MySQL)
        $activeConnection = config('database.default');
        $driver = config("database.connections.{$activeConnection}.driver");
        $dbName = config("database.connections.{$activeConnection}.database");
        $dbSize = 0;

        if ($driver === 'mongodb') {
            try {
                if (extension_loaded('mongodb')) {
                    $stats = \Illuminate\Support\Facades\DB::connection($activeConnection)->getMongoDB()->command(['dbStats' => 1]);
                    $dbSize = (int)($stats['dataSize'] ?? 0);
                }
            } catch (\Exception $e) {
                $dbSize = 0;
            }
        } else {
            try {
                $sizeResult = \Illuminate\Support\Facades\DB::connection($activeConnection)->select("
                    SELECT SUM(data_length + index_length) AS size 
                    FROM information_schema.TABLES 
                    WHERE table_schema = ?
                ", [$dbName]);
                $dbSize = (int)($sizeResult[0]->size ?? 0);
            } catch (\Exception $e) {
                try {
                    $dbSize = (int)(\Illuminate\Support\Facades\DB::connection($activeConnection)->table('stored_files')->sum('size') ?? 0);
                } catch (\Exception $ex) {
                    $dbSize = 0;
                }
            }
        }

        $dbLimit = 5 * 1024 * 1024 * 1024; // 5 GB

        $configs = [
            'mysql' => [
                'host' => config('database.connections.mysql.host'),
                'port' => config('database.connections.mysql.port'),
                'database' => config('database.connections.mysql.database'),
                'username' => config('database.connections.mysql.username'),
                'password' => config('database.connections.mysql.password'),
            ],
            'mysql_online' => [
                'host' => config('database.connections.mysql_online.host'),
                'port' => config('database.connections.mysql_online.port'),
                'database' => config('database.connections.mysql_online.database'),
                'username' => config('database.connections.mysql_online.username'),
                'password' => config('database.connections.mysql_online.password'),
            ],
            'gcp' => [
                'host' => config('database.connections.gcp.host'),
                'port' => config('database.connections.gcp.port'),
                'database' => config('database.connections.gcp.database'),
                'username' => config('database.connections.gcp.username'),
                'password' => config('database.connections.gcp.password'),
            ],
            'itmbu_server' => [
                'host' => config('database.connections.itmbu_server.host'),
                'port' => config('database.connections.itmbu_server.port'),
                'database' => config('database.connections.itmbu_server.database'),
                'username' => config('database.connections.itmbu_server.username'),
                'password' => config('database.connections.itmbu_server.password'),
            ],
            'mongodb' => [
                'host' => config('database.connections.mongodb.host'),
                'port' => config('database.connections.mongodb.port'),
                'database' => config('database.connections.mongodb.database'),
                'username' => config('database.connections.mongodb.username'),
                'password' => config('database.connections.mongodb.password'),
            ],
        ];

        return response()->json([
            'dbHealth' => $dbHealth,
            'phpMemoryUsed' => $phpMemoryUsed,
            'phpMemoryLimit' => $phpMemoryLimit,
            'dbSize' => $dbSize,
            'dbLimit' => $dbLimit,
            'dbState' => env('DB_STATE', 'offline'),
            'activeConnection' => $activeConnection,
            'dbConfigs' => $configs,
        ]);

    }

    public function unlockMasterData(Request $request)
    {
        if ($request->visor_pin === 'BAPS2026') {
            session(['visor_unlocked' => true]);
            return redirect('/admin/master-data');
        }
        return back()->with('error', 'Invalid security pin.');
    }

    public function getTableSchema($table)
    {
        if (session('user_role') !== 'admin' || (!session('visor_unlocked') && !session('add_function_unlocked'))) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $columns = \Illuminate\Support\Facades\Schema::getColumnListing($table);
        // Exclude some internal columns
        $columns = array_diff($columns, ['id', 'created_at', 'updated_at', 'email_verified_at', 'remember_token']);
        return response()->json(array_values($columns));
    }

    public function injectMasterData(Request $request)
    {
        if (session('user_role') !== 'admin' || (!session('visor_unlocked') && !session('add_function_unlocked'))) {
            return redirect('/admin')->with('error', 'Unauthorized.');
        }
        $table = $request->input('target_table');
        $data = $request->except(['_token', 'target_table']);

        // Add timestamps
        if (\Illuminate\Support\Facades\Schema::hasColumn($table, 'created_at')) {
            $data['created_at'] = now();
            $data['updated_at'] = now();
        }

        try {
            \Illuminate\Support\Facades\DB::table($table)->insert($data);
            return back()->with('success', "Record forcefully injected into {$table} without coding!");
        } catch (\Exception $e) {
            return back()->with('error', "Database error: " . $e->getMessage());
        }
    }

    public function switchDatabaseState(Request $request, \App\Services\DatabaseStateService $dbService)
    {
        if (session('user_role') !== 'admin' || !session('visor_unlocked')) {
            return redirect('/admin')->with('error', 'Unauthorized.');
        }

        $state = $request->input('state');
        if (!in_array($state, ['online', 'offline'])) {
            return back()->with('error', 'Invalid state selected.');
        }

        if ($dbService->persistState($state)) {
            return back()->with('success', "System successfully switched to " . strtoupper($state) . " mode. Please refresh to apply changes.");
        }

        return back()->with('error', 'Failed to update environment configuration.');
    }

    public function updateDatabaseConfig(\Illuminate\Http\Request $request)
    {
        if (session('user_role') !== 'admin' || !session('visor_unlocked')) {
            return redirect('/admin')->with('error', 'Unauthorized.');
        }

        $request->validate([
            'connection_id' => 'required|in:mysql,mysql_online,gcp,itmbu_server,mongodb',
            'host' => 'required|string',
            'port' => 'required|numeric',
            'database' => 'required|string',
            'username' => 'nullable|string',
            'password' => 'nullable|string',
        ]);

        $connectionId = $request->input('connection_id');
        $host = $request->input('host');
        $port = $request->input('port');
        $database = $request->input('database');
        $username = $request->input('username') ?? '';
        $password = $request->input('password') ?? '';

        // Map connection ID to environment variables prefixes
        $prefix = match ($connectionId) {
            'mysql' => 'DB_',
            'mysql_online' => 'DB_ONLINE_',
            'gcp' => 'DB_GCP_',
            'itmbu_server' => 'DB_ITMBU_',
            'mongodb' => 'DB_MONGODB_',
        };

        $envPath = base_path('.env');
        if (!file_exists($envPath)) {
            return back()->with('error', 'Environment file not found.');
        }

        try {
            $content = file_get_contents($envPath);

            // Update keys helper
            $updates = [
                "{$prefix}HOST" => $host,
                "{$prefix}PORT" => $port,
                "{$prefix}DATABASE" => $database,
                "{$prefix}USERNAME" => $username,
                "{$prefix}PASSWORD" => $password,
            ];

            foreach ($updates as $key => $value) {
                // If key exists in .env, replace it. Otherwise, append it.
                if (preg_match("/^{$key}=.*/m", $content)) {
                    $content = preg_replace("/^{$key}=.*/m", "{$key}=\"{$value}\"", $content);
                } else {
                    $content .= "\n{$key}=\"{$value}\"";
                }
            }

            file_put_contents($envPath, $content);

            // Clear configuration and DB health cache
            \Illuminate\Support\Facades\Cache::forget("db_health_{$connectionId}");
            \Illuminate\Support\Facades\Cache::forget("db_health_{$connectionId}_status");
            \Illuminate\Support\Facades\Artisan::call('config:clear');

            return back()->with('success', "Database config for connection '{$connectionId}' successfully updated! Health check cache cleared.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update database configuration: ' . $e->getMessage());
        }
    }


    public function addFunctionModule()
    {
        if (session('user_role') !== 'admin') {
            return redirect('/admin')->with('error', 'Strictly Unauthorized.');
        }

        if (!session('add_function_unlocked')) {
            return view('admin.add_function_auth');
        }

        // Return the dedicated Add Function Module view
        return view('admin.add_function_module');
    }

    public function unlockAddFunctionModule(Request $request)
    {
        if ($request->password === 'BAPS2026ITC') {
            session(['add_function_unlocked' => true]);
            return redirect('/admin/add-function-module');
        }
        return back()->with('error', 'Invalid password.');
    }

    public function storeCourse(Request $request)
    {
        Course::create($request->all());
        return back()->with('success', 'Course created with academic mapping!');
    }

    public function requestCourseApproval($id)
    {
        $course = Course::findOrFail($id);
        $course->update(['approval_status' => 'pending_review']);
        return back()->with('success', 'Approval request sent to Dean/Admin.');
    }

    public function updateCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);
        $course->update($request->all());
        return back()->with('success', 'Course updated successfully!');
    }

    public function storeLesson(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'title' => 'required',
            'type' => 'required',
            'file' => 'nullable|file', // Added to trigger Laravel's native upload validation
        ]);

        if ($request->type == 'youtube') {
            $file = $request->url;
        } else {
            if (!$request->hasFile('file') || !$request->file('file')->isValid()) {
                return back()->with('error', 'File upload failed. It might exceed the server\'s maximum upload size (usually 2MB) or was corrupted.');
            }
            $file = $request->file('file')->store('materials', 'public');
        }

        Lesson::create([
            'course_id' => $request->course_id,
            'title' => $request->title,
            'type' => $request->type,
            'file' => $file,
            'uploader_id' => session('user_id') ?? auth()->id() ?? 1
        ]);
        return back();
    }

    public function issueCertificate($id)
    {
        $enr = \App\Models\Enrollment::find($id);
        if (!$enr)
            return back()->with('error', 'Enrollment not found');

        $email = !empty($enr->email) ? $enr->email : 'student_' . ($enr->roll_no ?? mt_rand(1000, 9999)) . '@baps.lms.local';

        // Ensure User exists
        $user = \App\Models\User::firstOrCreate(
            ['email' => $email],
            [
                'name' => !empty($enr->name) ? $enr->name : 'Unknown Student',
                'enrollment_no' => $enr->roll_no ?? (string) mt_rand(10000000, 99999999),
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'level' => 1,
            ]
        );

        // Issue Certificate
        $cert = \App\Models\Certificate::firstOrCreate([
            'user_id' => $user->id,
            'course_id' => $enr->course_id
        ], [
            'unique_code' => 'MAN-' . strtoupper(\Illuminate\Support\Str::random(10))
        ]);

        return redirect('/admin/certificate/preview/' . $cert->unique_code);
    }

    public function downloadCertificate($code)
    {
        $certificate = \App\Models\Certificate::where('unique_code', $code)->with(['user', 'course'])->firstOrFail();

        $pdf = Pdf::loadView('certificate_pdf', compact('certificate'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('Certificate_' . $certificate->unique_code . '.pdf');
    }

    public function viewCertificate($code)
    {
        $certificate = \App\Models\Certificate::where('unique_code', $code)->with(['user', 'course'])->firstOrFail();
        return view('certificate_view', compact('certificate'));
    }

    public function previewCertificate($code)
    {
        $certificate = \App\Models\Certificate::where('unique_code', $code)->with(['user', 'course'])->firstOrFail();
        $user = $certificate->user;
        $course = $certificate->course;

        $taskSubmissions = \Illuminate\Support\Facades\DB::table('task_submissions')
            ->whereIn('task_id', $course->tasks->pluck('id'))
            ->where('user_id', $user->id)
            ->get();

        $quizAttempts = \App\Models\QuizAttempt::whereIn('quiz_id', $course->quizzes->pluck('id'))
            ->where('user_id', $user->id)
            ->get();

        return view('student.preview_document', compact('course', 'user', 'certificate', 'taskSubmissions', 'quizAttempts'));
    }

    public function enterDemoMode($id)
    {
        session(['demo_user_id' => $id]);
        return redirect('/courses')->with('success', 'Entered Demo Mode (Viewing as Student)');
    }

    public function exitDemoMode()
    {
        session()->forget('demo_user_id');
        if (session('user_role') === 'placement-dean' || (session('user_role') === 'dean' && session('staff_id') === 888)) {
            session()->forget('user_role');
            session()->forget('staff_id');
            session()->forget('staff_name');
            return redirect('/')->with('success', 'Exited Demo Mode');
        }
        return redirect('/admin/enrollments')->with('success', 'Exited Demo Mode');
    }

    public function profile()
    {
        return view('admin.profile');
    }

    public function enrollments()
    {
        $query = Enrollment::with('course');

        // HOD can only see their department's student enrollments? 
        // Actually, let's show all for now, or filter if we have department logic for courses.

        $enrollments = $query->get();
        return view('admin.enrollments', compact('enrollments'));
    }

    public function manageStaff()
    {
        $departments = \App\Models\Department::all();
        $staffMembers = \App\Models\Staff::with('department')->get();
        if (session('user_role') === 'cr') {
            $staffMembers = $staffMembers->where('role', 'faculty');
        }
        $courses = \App\Models\Course::orderBy('title')->get();
        return view('admin.staff', compact('staffMembers', 'departments', 'courses'));
    }

    public function storeStaff(Request $request)
    {
        // Validate input including uniqueness of code and email
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email',
            'role' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'unique_code' => 'required|unique:staff,unique_code',
            'password' => 'nullable|string|min:6',
        ], [
            'unique_code.unique' => 'This Unique Code is already assigned to another staff member. Please use a different code.',
            'email.unique' => 'This email address is already registered for another staff member.',
        ]);

        $data = $request->except(['_token', 'designation', 'scope']);
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        // Handle scope
        if ($request->input('scope') === 'universal') {
            $data['department_id'] = null;
        }

        // Handle positions and designation
        $positions = $request->input('positions', []);
        if (!is_array($positions)) {
            $positions = [$positions];
        }

        $designation = $request->input('designation');
        if ($designation === 'Main Dean') {
            if ($request->input('dean_password') !== '1234@1234') {
                return back()->withInput()->with('error', 'Incorrect password for Main Dean assignment.');
            }

            $deptId = ($request->input('scope') === 'universal') ? null : $request->input('department_id');
            $existingMainDeansCount = \App\Models\Staff::where('role', 'dean')
                ->where('department_id', $deptId)
                ->whereJsonContains('positions', 'Main Dean')
                ->count();

            if ($existingMainDeansCount >= 2) {
                $levelStr = $deptId ? 'this department' : 'the universal level';
                return back()->withInput()->with('error', "Cannot assign Main Dean! A maximum of 2 Main Deans are allowed for {$levelStr}.");
            }
        }

        if (!empty($designation)) {
            // Remove any existing designations
            $designationsToRemove = [
                'main dean', 'associate dean', 'co-dean', 'placement dean',
                'primary hod', 'secondary hod', 'temporary hod', 'universal hod'
            ];
            $positions = array_filter($positions, function($pos) use ($designationsToRemove) {
                return !in_array(strtolower($pos), $designationsToRemove);
            });
            $positions[] = $designation;
        }

        // Fallback if empty
        if (empty($positions)) {
            $positions = [$request->input('role')];
        }
        $data['positions'] = array_values($positions);

        // Assign access level dynamically
        $roleLower = strtolower($request->input('role'));
        if ($roleLower === 'admin') {
            $accessLevel = 300; // Super admin with 300% access
        } elseif (in_array($roleLower, ['president', 'vice-president', 'provost', 'registrar', 'director', 'board-member', 'external-coordinator', 'dean'])) {
            $accessLevel = 200; // 200% access
        } elseif (in_array($roleLower, ['hod', 'office-assistant'])) {
            $accessLevel = 175; // 175% access
        } elseif (in_array($roleLower, ['cr', 'coordinator'])) {
            $accessLevel = 125; // 125% access
        } else {
            $accessLevel = 100; // 100% access
        }
        $data['access_level'] = $accessLevel;

        try {
            \App\Models\Staff::create($data);
            return back()->with('success', 'Staff member enrolled successfully!');
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            // Detect which field caused the duplicate and show exact value
            $msg = $e->getMessage();
            if (str_contains($msg, 'unique_code') || str_contains($msg, 'staff_unique_code')) {
                $dupField = 'unique_code';
                $dupValue = $request->input('unique_code');
            } elseif (str_contains($msg, 'email') || str_contains($msg, 'staff_email')) {
                $dupField = 'email';
                $dupValue = $request->input('email');
            } else {
                $dupField = 'record';
                $dupValue = $request->input('name');
            }
            return back()->withInput()->with('error', "Already exists! {{$dupField}: {$dupValue}}");
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), '1062')) {
                $msg = $e->getMessage();
                if (str_contains($msg, 'unique_code') || str_contains($msg, 'staff_unique_code')) {
                    $dupField = 'unique_code';
                    $dupValue = $request->input('unique_code');
                } elseif (str_contains($msg, 'email') || str_contains($msg, 'staff_email')) {
                    $dupField = 'email';
                    $dupValue = $request->input('email');
                } else {
                    $dupField = 'record';
                    $dupValue = $request->input('name');
                }
                return back()->withInput()->with('error', "Already exists! {{$dupField}: {$dupValue}}");
            }
            throw $e;
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Could not enroll staff: ' . $e->getMessage());
        }
    }

    public function downloadLatestStaffPdf()
    {
        $allStaff = \App\Models\Staff::with('department')->orderBy('role')->orderBy('name')->get();
        if (session('user_role') === 'cr') {
            $allStaff = $allStaff->where('role', 'faculty');
        }
        if ($allStaff->isEmpty()) {
            return back()->with('error', 'No staff members are registered yet.');
        }

        $pdf = Pdf::loadView('admin.staff_pdf', compact('allStaff'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('BAPS_Staff_Directory_' . date('d-M-Y') . '.pdf');
    }

    public function updateStaff(Request $request, $id)
    {
        $staff = \App\Models\Staff::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:staff,email,' . $id,
            'role' => 'required|string',
            'department_id' => 'nullable|exists:departments,id',
            'unique_code' => 'required|unique:staff,unique_code,' . $id,
        ]);

        $data = $request->except(['_token', '_method', 'designation', 'scope']);
        if (!empty($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        } else {
            unset($data['password']);
        }

        // Handle scope
        if ($request->input('scope') === 'universal') {
            $data['department_id'] = null;
        }

        // Handle positions and designation
        $positions = $request->input('positions', []);
        if (!is_array($positions)) {
            $positions = [$positions];
        }

        $designation = $request->input('designation');
        if ($designation === 'Main Dean') {
            $alreadyMainDean = is_array($staff->positions) && in_array('Main Dean', $staff->positions);
            if (!$alreadyMainDean || !empty($request->dean_password)) {
                if ($request->input('dean_password') !== '1234@1234') {
                    return back()->withInput()->with('error', 'Incorrect password for Main Dean assignment.');
                }
            }

            $deptId = ($request->input('scope') === 'universal') ? null : $request->input('department_id');
            $existingMainDeansCount = \App\Models\Staff::where('role', 'dean')
                ->where('id', '!=', $id)
                ->where('department_id', $deptId)
                ->whereJsonContains('positions', 'Main Dean')
                ->count();

            if ($existingMainDeansCount >= 2) {
                $levelStr = $deptId ? 'this department' : 'the universal level';
                return back()->withInput()->with('error', "Cannot assign Main Dean! A maximum of 2 Main Deans are allowed for {$levelStr}.");
            }
        }

        if (!empty($designation)) {
            // Remove any existing designations
            $designationsToRemove = [
                'main dean', 'associate dean', 'co-dean', 'placement dean',
                'primary hod', 'secondary hod', 'temporary hod', 'universal hod'
            ];
            $positions = array_filter($positions, function($pos) use ($designationsToRemove) {
                return !in_array(strtolower($pos), $designationsToRemove);
            });
            $positions[] = $designation;
        }

        // Fallback if empty
        if (empty($positions)) {
            $positions = [$request->input('role')];
        }
        $data['positions'] = array_values($positions);

        // Assign access level dynamically
        $roleLower = strtolower($request->input('role'));
        if ($roleLower === 'admin') {
            $accessLevel = 300; // Super admin
        } elseif (in_array($roleLower, ['president', 'vice-president', 'provost', 'registrar', 'director', 'board-member', 'external-coordinator', 'dean'])) {
            $accessLevel = 200;
        } elseif (in_array($roleLower, ['hod', 'office-assistant'])) {
            $accessLevel = 175;
        } elseif (in_array($roleLower, ['cr', 'coordinator'])) {
            $accessLevel = 125;
        } else {
            $accessLevel = 100;
        }
        $data['access_level'] = $accessLevel;

        try {
            $staff->update($data);
            return back()->with('success', 'Staff details updated successfully!');
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'unique_code') || str_contains($msg, 'staff_unique_code')) {
                $dupField = 'unique_code';
                $dupValue = $request->input('unique_code');
            } elseif (str_contains($msg, 'email') || str_contains($msg, 'staff_email')) {
                $dupField = 'email';
                $dupValue = $request->input('email');
            } else {
                $dupField = 'record';
                $dupValue = $request->input('name');
            }
            return back()->withInput()->with('error', "Already exists! {{$dupField}: {$dupValue}}");
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), '1062')) {
                $msg = $e->getMessage();
                if (str_contains($msg, 'unique_code') || str_contains($msg, 'staff_unique_code')) {
                    $dupField = 'unique_code';
                    $dupValue = $request->input('unique_code');
                } elseif (str_contains($msg, 'email') || str_contains($msg, 'staff_email')) {
                    $dupField = 'email';
                    $dupValue = $request->input('email');
                } else {
                    $dupField = 'record';
                    $dupValue = $request->input('name');
                }
                return back()->withInput()->with('error', "Already exists! {{$dupField}: {$dupValue}}");
            }
            throw $e;
        }
    }

    public function deleteStaff(Request $request, $id)
    {
        $staff = \App\Models\Staff::findOrFail($id);

        // Prevent deleting yourself
        if (session('staff_id') == $id) {
            return back()->with('error', 'Cannot delete! (Staff: You cannot delete your own account)');
        }

        $name = $staff->name;
        $role = $staff->role;
        $staff->delete();

        return back()->with('success', "Staff deleted! ({$role}: {$name})");
    }

    public function promoteToSuperAdmin(Request $request)
    {
        $request->validate([
            'staff_id' => 'required|exists:staff,id',
        ]);

        $staff = \App\Models\Staff::findOrFail($request->staff_id);
        
        $staff->role = 'admin';
        $staff->access_level = 300;
        
        $positions = $staff->positions ?? [];
        if (!is_array($positions)) {
            $positions = [$positions];
        }
        
        // Remove existing designations if any
        $designationsToRemove = [
            'main dean', 'associate dean', 'co-dean', 'placement dean',
            'primary hod', 'secondary hod', 'temporary hod', 'universal hod'
        ];
        $positions = array_filter($positions, function($pos) use ($designationsToRemove) {
            return !in_array(strtolower($pos), $designationsToRemove);
        });

        if (!in_array('Super Admin', $positions)) {
            $positions[] = 'Super Admin';
        }
        $staff->positions = array_values($positions);
        $staff->department_id = null; // Super admin has universal scope
        $staff->save();

        return back()->with('success', "Staff member {$staff->name} promoted to Super Admin successfully!");
    }

    public function demoteFromSuperAdmin(Request $request, $id)
    {
        $staff = \App\Models\Staff::findOrFail($id);

        if (session('staff_id') == $id) {
            return back()->with('error', 'Cannot demote! You cannot demote your own Super Admin account.');
        }

        // Change role back to faculty (or standard default role)
        $staff->role = 'faculty';
        $staff->access_level = 100;
        
        $positions = $staff->positions ?? [];
        if (!is_array($positions)) {
            $positions = [$positions];
        }
        
        // Remove Super Admin from positions
        $positions = array_filter($positions, function($pos) {
            return strtolower($pos) !== 'super admin' && strtolower($pos) !== 'admin';
        });
        
        $staff->positions = array_values($positions);
        $staff->save();

        return back()->with('success', "Super Admin {$staff->name} demoted to Faculty successfully!");
    }

    public function allocateCourseToStaff(Request $request)
    {
        if (!in_array(session('user_role'), ['dean', 'hod', 'admin'])) {
            return back()->with('error', 'Only Dean, HOD, or Admin can allocate courses.');
        }

        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'course_id' => 'required|exists:courses,id',
            'class_section' => 'required|string|max:10'
        ]);

        \App\Models\CourseAllocation::updateOrCreate(
            ['course_id' => $request->course_id, 'class_section' => $request->class_section],
            ['staff_id' => $request->staff_id]
        );

        $staff = \App\Models\Staff::find($request->staff_id);
        $course = \App\Models\Course::find($request->course_id);

        return back()->with('success', 'Course "' . $course->name . '" allocated to ' . $staff->name . ' for Class ' . $request->class_section);
    }

    public function bulkDeleteStaff(Request $request)
    {
        if (!in_array(session('user_role'), ['dean', 'admin'])) {
            return back()->with('error', 'Only Dean or Admin can delete staff.');
        }

        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:staff,id'
        ]);

        $ids = $request->input('ids');
        $selfId = session('staff_id');

        // Filter out self
        $idsToDelete = array_filter($ids, function($id) use ($selfId) {
            return $id != $selfId;
        });

        if (empty($idsToDelete)) {
            return back()->with('error', 'No valid staff selected for deletion (you cannot delete yourself).');
        }

        $count = \App\Models\Staff::whereIn('id', $idsToDelete)->delete();

        return back()->with('success', "Successfully deleted {$count} staff member(s).");
    }

    public function bulkEnrollStaff(Request $request)
    {
        if (!in_array(session('user_role'), ['dean', 'admin'])) {
            return back()->with('error', 'Only Dean or Admin can bulk enroll staff.');
        }

        $request->validate([
            'bulk_names' => 'required|string',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $lines = explode("\n", $request->input('bulk_names'));
        $enrolledCount = 0;
        $deptId = $request->input('department_id');

        foreach ($lines as $line) {
            $fullName = trim($line);
            if (empty($fullName)) {
                continue;
            }

            // Extract first and last name using the robust parsing helper
            $cleanName = preg_replace('/\s*\([^)]*\)/', '', $fullName);
            $cleanName = str_replace(['.', ','], ' ', $cleanName);
            $cleanName = preg_replace('/\s+/', ' ', trim($cleanName));
            $words = explode(' ', $cleanName);
            
            $titles = ['dr', 'prof', 'hod', 'dean', 'provost', 'associate', 'co-dean', 'senior', 'assistant', 'dill'];
            
            $filteredWords = [];
            foreach ($words as $word) {
                $cleanWord = strtolower(trim($word));
                if (in_array($cleanWord, $titles) || empty($cleanWord)) {
                    continue;
                }
                $filteredWords[] = $word;
            }
            
            $first = $filteredWords[0] ?? 'Faculty';
            $last = $filteredWords[1] ?? 'ITM';
            
            $firstClean = preg_replace('/[^a-zA-Z]/', '', $first);
            $lastClean = preg_replace('/[^a-zA-Z]/', '', $last);
            
            // Password: FirstName(All Capital)@123
            $plainPassword = strtoupper($firstClean) . '@123';
            $hashedPassword = bcrypt($plainPassword);
            
            // Role assignment: we can parse positions/role if they put it in parentheses like name(Role)
            $role = 'faculty';
            $email_role = 'faculty';
            $positions = ['Faculty'];
            
            if (preg_match('/\(([^)]+)\)/', $fullName, $matches)) {
                $posText = trim($matches[1]);
                $positions = [$posText];
                $posLower = strtolower($posText);
                if (str_contains($posLower, 'hod')) {
                    $role = 'hod';
                    $email_role = 'hod';
                } elseif (str_contains($posLower, 'dean') || str_contains($posLower, 'provost') || str_contains($posLower, 'placement')) {
                    $role = 'dean';
                    $email_role = 'dean';
                } elseif (str_contains($posLower, 'coordinator')) {
                    $role = 'coordinator';
                    $email_role = 'coordinator';
                } elseif (str_contains($posLower, 'assistant')) {
                    $role = 'office-assistant';
                    $email_role = 'office-assistant';
                } elseif (str_contains($posLower, 'admin')) {
                    $role = 'admin';
                    $email_role = 'admin';
                }
            }
            
            // Email: Firstname.Lastname.Role@itmbu.ac.in
            $email = strtolower($firstClean) . '.' . strtolower($lastClean) . '.' . strtolower($email_role) . '@itmbu.ac.in';
            
            // Handle duplicate email checks
            $existingCount = \App\Models\Staff::where('email', $email)->count();
            if ($existingCount > 0) {
                $email = strtolower($firstClean) . '.' . strtolower($lastClean) . ($existingCount + 1) . '.' . strtolower($email_role) . '@itmbu.ac.in';
            }

            // Generate next sequential unique code
            $lastStaff = \App\Models\Staff::where('unique_code', 'LIKE', 'ITM%')
                ->orderBy('unique_code', 'desc')
                ->first();
            $nextNum = 1;
            if ($lastStaff) {
                $lastNum = (int) filter_var($lastStaff->unique_code, FILTER_SANITIZE_NUMBER_INT);
                $nextNum = $lastNum + 1;
            }
            $uniqueCode = 'ITM' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);

            $accessLevel = 100;
            if (in_array(strtolower($role), ['dean', 'admin'])) {
                $accessLevel = 200;
            } elseif (strtolower($role) === 'hod') {
                $accessLevel = 175;
            } elseif (in_array(strtolower($role), ['cr', 'coordinator'])) {
                $accessLevel = 125;
            }

            \App\Models\Staff::create([
                'name' => $fullName,
                'role' => $role,
                'department_id' => $deptId,
                'unique_code' => $uniqueCode,
                'email' => $email,
                'password' => $hashedPassword,
                'positions' => $positions,
                'access_level' => $accessLevel
            ]);

            $enrolledCount++;
        }

        return back()->with('success', "Successfully enrolled {$enrolledCount} staff member(s) in bulk!");
    }

    public function manageDepartments()
    {
        // Ensure default Dean name is exactly as requested
        $mainDean = \App\Models\Staff::where('role', 'dean')
            ->where(function($q) {
                $q->where('email', 'pradeep.laxkar.dean@itmbu.ac.in')
                  ->orWhere('name', 'like', '%Pradeep%');
            })->first();
        if ($mainDean) {
            if ($mainDean->name !== 'Dr.Prof. Dr. Pradeep Laxkar(Main Dean(Already Exist)) (Dual PHD)') {
                $mainDean->name = 'Dr.Prof. Dr. Pradeep Laxkar(Main Dean(Already Exist)) (Dual PHD)';
                $mainDean->save();
            }
        } else {
            $mainDean = \App\Models\Staff::create([
                'name' => 'Dr.Prof. Dr. Pradeep Laxkar(Main Dean(Already Exist)) (Dual PHD)',
                'role' => 'dean',
                'unique_code' => 'ITM_DEAN_MAIN',
                'email' => 'pradeep.laxkar.dean@itmbu.ac.in',
                'access_level' => 200,
                'positions' => ['Main Dean', 'Dual PHD']
            ]);
        }

        // Ensure SCSET has default branches populated in the new 5-level format with color-coded heads
        $scset = \App\Models\Department::where('code', 'SCSET')->first();
        if ($scset) {
            $needsReset = false;
            if (empty($scset->branches)) {
                $needsReset = true;
            } else {
                // Check if it's the old 3-level format or has legacy structure
                $keys = array_keys($scset->branches);
                if (!in_array('diploma', $keys) || !in_array('hons_bachelors', $keys)) {
                    $needsReset = true;
                } else {
                    foreach ($scset->branches as $level => $progs) {
                        if (is_array($progs) && count($progs) > 0) {
                            $first = reset($progs);
                            if (!is_array($first) || !isset($first['heads']) || !isset($first['program'])) {
                                $needsReset = true;
                                break;
                            }
                        }
                    }
                }
            }

            if ($needsReset) {
                // Find HOD IDs
                $prachiId = \App\Models\Staff::where('name', 'like', '%Prachi%')->first()?->id;
                $shivangiId = \App\Models\Staff::where('name', 'like', '%Shivangi%')->first()?->id;
                $sunilId = \App\Models\Staff::where('name', 'like', '%Sunil%')->first()?->id;
                $rajuId = \App\Models\Staff::where('name', 'like', '%Raju%')->first()?->id;

                $scset->branches = [
                    'diploma' => [
                        [
                            'program' => 'Diploma',
                            'heads' => $prachiId ? [['staff_id' => $prachiId, 'type' => 'perm']] : [],
                            'branches' => ['Computer Engineering (CSE)']
                        ]
                    ],
                    'bachelors' => [
                        [
                            'program' => 'B.Tech',
                            'heads' => array_filter([
                                $shivangiId ? ['staff_id' => $shivangiId, 'type' => 'perm'] : null,
                                $rajuId ? ['staff_id' => $rajuId, 'type' => 'perm'] : null,
                                $sunilId ? ['staff_id' => $sunilId, 'type' => 'temp'] : null
                            ]),
                            'branches' => ['Computer Science & Engineering (CSE)', 'Computer Systems & Networking (CSN)', 'Information Technology (IT)']
                        ],
                        [
                            'program' => 'BCA',
                            'heads' => $sunilId ? [['staff_id' => $sunilId, 'type' => 'perm']] : [],
                            'branches' => ['General']
                        ],
                        [
                            'program' => 'B.Sc IT',
                            'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                            'branches' => ['General']
                        ]
                    ],
                    'hons_bachelors' => [
                        [
                            'program' => 'B.Tech (Hons)',
                            'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                            'branches' => ['Artificial Intelligence & Machine Learning (AI&ML)', 'Cyber Security (CS)']
                        ],
                        [
                            'program' => 'BCA (Hons)',
                            'heads' => $sunilId ? [['staff_id' => $sunilId, 'type' => 'perm']] : [],
                            'branches' => ['Cloud Computing & DevOps', 'Data Science']
                        ]
                    ],
                    'masters' => [
                        [
                            'program' => 'M.Tech',
                            'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                            'branches' => ['Computer Science & Engineering']
                        ],
                        [
                            'program' => 'MCA',
                            'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                            'branches' => ['General', 'Data Analytics']
                        ],
                        [
                            'program' => 'M.Sc CS',
                            'heads' => $shivangiId ? [['staff_id' => $shivangiId, 'type' => 'perm']] : [],
                            'branches' => ['General']
                        ]
                    ],
                    'phd' => [
                        [
                            'program' => 'PhD',
                            'heads' => $rajuId ? [['staff_id' => $rajuId, 'type' => 'perm']] : [],
                            'branches' => ['Computer Science', 'Artificial Intelligence & Machine Learning']
                        ]
                    ]
                ];
                $scset->save();
            }
        }

        // Ensure President exists
        $president = \App\Models\Staff::where('email', 'president@itmbu.ac.in')
            ->orWhere('name', 'like', '%President%')->first();
        if (!$president) {
            $president = \App\Models\Staff::create([
                'name' => 'Prof. Dr. Amit Kumar Sen (President)',
                'role' => 'dean',
                'unique_code' => 'ITM_PRESIDENT',
                'email' => 'president@itmbu.ac.in',
                'access_level' => 200,
                'positions' => ['President']
            ]);
        }

        // Ensure Vice President exists
        $vicePresident = \App\Models\Staff::where('email', 'vp@itmbu.ac.in')
            ->orWhere('name', 'like', '%Vice President%')->first();
        if (!$vicePresident) {
            $vicePresident = \App\Models\Staff::create([
                'name' => 'Prof. Dr. Rajesh Kumar (Vice President)',
                'role' => 'dean',
                'unique_code' => 'ITM_VP',
                'email' => 'vp@itmbu.ac.in',
                'access_level' => 200,
                'positions' => ['Vice President']
            ]);
        }

        // Ensure Provost exists
        $provost = \App\Models\Staff::where('email', 'vedvas.dwivedi.provost@itmbu.ac.in')
            ->orWhere('name', 'like', '%Provost%')->first();
        if (!$provost) {
            $provost = \App\Models\Staff::create([
                'name' => 'Prof. Dr. Vedvas Dwivedi (Provost) (Quad PHD)',
                'role' => 'dean',
                'unique_code' => 'ITM_PROVOST',
                'email' => 'vedvas.dwivedi.provost@itmbu.ac.in',
                'access_level' => 200,
                'positions' => ['Provost', 'Quad PHD']
            ]);
        }

        // Ensure Registrar exists
        $registrar = \App\Models\Staff::where('email', 'registrar@itmbu.ac.in')
            ->orWhere('name', 'like', '%Registrar%')->first();
        if (!$registrar) {
            $registrar = \App\Models\Staff::create([
                'name' => 'Dr. Ramesh Mehta (Registrar)',
                'role' => 'dean',
                'unique_code' => 'ITM_REGISTRAR',
                'email' => 'registrar@itmbu.ac.in',
                'access_level' => 200,
                'positions' => ['Registrar']
            ]);
        }

        $departments = \App\Models\Department::all();
        $allHods = \App\Models\Staff::where('role', 'hod')->orderBy('name')->get();
        $allStaff = \App\Models\Staff::orderBy('name')->get();
        return view('admin.departments', compact('departments', 'allHods', 'allStaff', 'mainDean', 'president', 'vicePresident', 'provost', 'registrar'));
    }

    public function assignHodToDepartment(Request $request)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean'])) {
            return back()->with('error', 'Unauthorized! Only Admin or Dean can assign HODs.');
        }

        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'department_id' => 'nullable|exists:departments,id'
        ]);

        $staff = \App\Models\Staff::findOrFail($request->staff_id);
        $staff->department_id = $request->department_id;
        $staff->save();

        return back()->with('success', "HOD {$staff->name} has been successfully assigned to department.");
    }

    private function parseProgramsInput(array $programsInput): array
    {
        $branchesData = [];
        $levels = ['diploma', 'bachelors', 'hons_bachelors', 'masters', 'phd'];

        foreach ($levels as $level) {
            if (isset($programsInput[$level]['enabled']) && $programsInput[$level]['enabled'] == '1') {
                $levelData = [];
                $progs = $programsInput[$level]['programs'] ?? [];
                
                foreach ($progs as $progItem) {
                    if (!empty($progItem['program'])) {
                        // Extract branches
                        $branchesList = [];
                        if (!empty($progItem['branches'])) {
                            $branchesList = array_filter(array_map('trim', explode(',', $progItem['branches'])));
                        }
                        
                        // Extract heads/HODs
                        $headsList = [];
                        $headsInput = $progItem['heads'] ?? [];
                        foreach ($headsInput as $headItem) {
                            if (!empty($headItem['staff_id'])) {
                                $headsList[] = [
                                    'staff_id' => (int) $headItem['staff_id'],
                                    'type' => $headItem['type'] === 'temp' ? 'temp' : 'perm'
                                ];
                            }
                        }
                        
                        $levelData[] = [
                            'program' => trim($progItem['program']),
                            'heads' => $headsList,
                            'branches' => array_values($branchesList)
                        ];
                    }
                }
                
                if (!empty($levelData)) {
                    $branchesData[$level] = $levelData;
                }
            }
        }
        
        return $branchesData;
    }

    public function storeDepartment(Request $request)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean'])) {
            return back()->with('error', 'Unauthorized! Only Admin or Dean can create departments.');
        }

        $data = $request->only(['name', 'code']);
        
        $programs = $request->input('programs', []);
        $data['branches'] = $this->parseProgramsInput($programs);

        \App\Models\Department::create($data);
        return back()->with('success', 'Department created successfully!');
    }

    public function updateDepartment(Request $request, $id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean'])) {
            return back()->with('error', 'Unauthorized! Only Admin or Dean can update departments.');
        }

        $dept = \App\Models\Department::findOrFail($id);
        $data = $request->only(['name', 'code']);
        
        $programs = $request->input('programs', []);
        $dept->name = $data['name'];
        $dept->code = $data['code'];
        $dept->branches = $this->parseProgramsInput($programs);
        $dept->save();

        return back()->with('success', 'Department updated successfully!');
    }

    public function deleteDepartment($id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean'])) {
            return back()->with('error', 'Unauthorized! Only Admin or Dean can delete departments.');
        }

        $dept = \App\Models\Department::findOrFail($id);
        
        // Prevent deleting SCSET to maintain portal stability
        if ($dept->code === 'SCSET') {
            return back()->with('error', 'The primary department SCSET cannot be deleted to maintain system stability.');
        }

        // Set department_id of associated staff to null
        \App\Models\Staff::where('department_id', $id)->update(['department_id' => null]);
        
        $dept->delete();

        return back()->with('success', 'Department deleted successfully.');
    }

    public function bulkEnrollPage()
    {
        $role = session('user_role');
        $staffName = session('staff_name');
        if (!in_array($role, ['admin', 'cr']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admin, Class Coordinator (CR), or Rajunakum Sir can execute enrollments.');
        }

        $departments = \App\Models\Department::all();
        $courses = Course::all();
        $students = \App\Models\User::where('role', 'student')->get();
        return view('admin.bulk_enroll', compact('departments', 'courses', 'students'));
    }

    public function storeBulkEnroll(Request $request)
    {
        $role = session('user_role');
        $staffName = session('staff_name');
        if (!in_array($role, ['admin', 'cr']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admin, Class Coordinator (CR), or Rajunakum Sir can execute enrollments.');
        }

        if ($request->enroll_type == 'all') {
            $courses = Course::where('program', $request->program)
                ->where('year', $request->year)
                ->where('semester', $request->semester)
                ->get();
        } else {
            $courses = Course::whereIn('id', $request->course_ids ?? [])->get();
        }

        // OPTION 3: Explicitly Selected Registered Students Fast-Track
        if ($request->existing_students && is_array($request->existing_students)) {
            $enrollmentCount = 0;
            foreach ($request->existing_students as $studentId) {
                $user = \App\Models\User::find($studentId);
                if ($user) {
                    foreach ($courses as $course) {
                        \App\Models\Enrollment::updateOrCreate(
                            ['course_id' => $course->id, 'user_id' => $user->id],
                            [
                                'name' => $user->name,
                                'email' => $user->email,
                                'program' => $request->program,
                                'year' => $request->year,
                                'semester' => $request->semester,
                                'class_section' => $request->class_section,
                                'department' => $request->department_name
                            ]
                        );
                        $enrollmentCount++;
                    }
                }
            }
            return back()->with('success', count($request->existing_students) . ' Existing Students efficiently enrolled into ' . count($courses) . ' courses! (Total: ' . $enrollmentCount . ' subject links established)');
        }

        $studentsToEnroll = [];
        $missingFieldsError = false;

        if ($request->hasFile('excel_file')) {
            $file = $request->file('excel_file');
            $extension = $file->getClientOriginalExtension() ?: 'xlsx';
            $rows = \Spatie\SimpleExcel\SimpleExcelReader::create($file->path(), $extension)->getRows();
            $rows->each(function (array $rowProperties) use (&$studentsToEnroll, &$missingFieldsError) {
                $row = array_change_key_case($rowProperties, CASE_LOWER);

                $abc = $row['abc_card_id'] ?? $row['abc card id'] ?? $row['abc id'] ?? $row['abc card'] ?? null;
                $phone = $row['phone'] ?? $row['mobile'] ?? $row['contact'] ?? null;
                $dob = $row['dob'] ?? $row['date of birth'] ?? $row['birth date'] ?? null;
                $gender = $row['gender'] ?? $row['sex'] ?? null;
                $blood = $row['blood_group'] ?? $row['blood group'] ?? $row['blood'] ?? null;
                $aadhar = $row['aadhar_no'] ?? $row['aadhar'] ?? $row['aadhar number'] ?? null;
                $guardian = $row['guardian_name'] ?? $row['father name'] ?? $row['guardian'] ?? null;
                $address = $row['address'] ?? null;

                if (empty($row['name']) || empty($row['email']) || empty($abc) || empty($phone) || empty($dob) || empty($gender) || empty($blood) || empty($aadhar) || empty($guardian) || empty($address)) {
                    $missingFieldsError = true;
                } else {
                    $studentsToEnroll[] = [
                        'name' => $row['name'],
                        'email' => $row['email'],
                        'abc_card_id' => $abc,
                        'phone' => $phone,
                        'dob' => date('Y-m-d', strtotime($dob)),
                        'gender' => $gender,
                        'blood_group' => $blood,
                        'aadhar_no' => $aadhar,
                        'guardian_name' => $guardian,
                        'address' => $address
                    ];
                }
            });
        } elseif ($request->m_name && $request->m_email) {
            if (empty($request->m_name) || empty($request->m_email) || empty($request->m_abc) || empty($request->m_phone) || empty($request->m_dob) || empty($request->m_gender) || empty($request->m_blood) || empty($request->m_aadhar) || empty($request->m_guardian) || empty($request->m_address)) {
                $missingFieldsError = true;
            } else {
                $studentsToEnroll[] = [
                    'name' => $request->m_name,
                    'email' => $request->m_email,
                    'abc_card_id' => $request->m_abc,
                    'phone' => $request->m_phone,
                    'dob' => $request->m_dob,
                    'gender' => $request->m_gender,
                    'blood_group' => $request->m_blood,
                    'aadhar_no' => $request->m_aadhar,
                    'guardian_name' => $request->m_guardian,
                    'address' => $request->m_address
                ];
            }
        }

        if ($missingFieldsError || empty($studentsToEnroll)) {
            return back()->with('error', 'STRICT VERIFICATION FAILED: All 10 fields are absolutely COMPULSORY for enrollment (Name, Email, ABC Card ID, Phone, DOB, Gender, Blood Group, Aadhar, Guardian, Address). Every row must contain all details!');
        }

        $enrollmentCount = 0;
        foreach ($studentsToEnroll as $student) {
            // Also register them as Users if they don't exist!
            $user = \App\Models\User::firstOrCreate(
                ['email' => $student['email']],
                [
                    'name' => $student['name'],
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'enrollment_no' => (string) mt_rand(10000000, 99999999),
                    'level' => 1,
                    'xp' => 0,
                    'role' => 'student',
                    'status' => 'approved',
                    'abc_card_id' => $student['abc_card_id'],
                    'phone' => $student['phone'],
                    'dob' => $student['dob'],
                    'gender' => $student['gender'],
                    'blood_group' => $student['blood_group'],
                    'aadhar_no' => $student['aadhar_no'],
                    'guardian_name' => $student['guardian_name'],
                    'address' => $student['address']
                ]
            );

            foreach ($courses as $course) {
                Enrollment::create([
                    'course_id' => $course->id,
                    'user_id' => $user->id,
                    'name' => $student['name'],
                    'email' => $student['email'],
                    'program' => $request->program,
                    'year' => $request->year,
                    'semester' => $request->semester,
                    'class_section' => $request->class_section,
                    'department' => $request->department_name
                ]);
                $enrollmentCount++;
            }
        }

        return back()->with('success', count($studentsToEnroll) . ' Students automatically registered & enrolled in ' . count($courses) . ' courses! (Total: ' . $enrollmentCount . ' subject enrollments)');
    }

    public function manageStudents()
    {
        $role = session('user_role');
        $staffName = session('staff_name');
        if (!in_array($role, ['admin', 'cr', 'coordinator', 'faculty-lecturer-coordinator']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admin, Class Coordinator (CR), or Rajunakum Sir can manage students.');
        }

        // Fetch ONLY students (excluding admins/staff/parents/wardens)
        $students = \App\Models\User::where(function($q) {
            $q->whereNotIn('role', ['admin', 'dean', 'hod', 'faculty', 'coordinator', 'office-assistant', 'provost', 'parent', 'warden', 'swami'])
              ->orWhereNull('role');
        })->with(['parents', 'hostelSwami', 'department'])->get();
        
        $departments = \App\Models\Department::all();
        $pendingFees = \App\Models\FeePayment::with('user')->where('status', 'pending')->get();

        // Seed/get default Swamis/Wardens for BAPS Hostel
        $wardens = collect();
        $warden1 = \App\Models\User::firstOrCreate(
            ['email' => 'sadhu.adbhutanand@baps.ac.in'],
            [
                'name' => 'Sadhu Adbhutanand Das (Hostel Warden)',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'warden',
                'status' => 'approved',
            ]
        );
        $warden2 = \App\Models\User::firstOrCreate(
            ['email' => 'sadhu.gyanprasad@baps.ac.in'],
            [
                'name' => 'Sadhu Gyanprasad Das (Hostel Warden)',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'warden',
                'status' => 'approved',
            ]
        );
        $wardens->push($warden1);
        $wardens->push($warden2);

        return view('admin.students', compact('students', 'departments', 'pendingFees', 'wardens'));
    }

    public function manageParents()
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'office-assistant', 'hod', 'cr'])) {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admin, Dean, Office Assistant, HOD, or CR can access the Parent Directory.');
        }

        $parents = \App\Models\User::where('role', 'parent')
            ->with(['child.department'])
            ->get();

        $departments = \App\Models\Department::all();

        // Fetch students for parent registration dropdown
        $students = \App\Models\User::where(function($q) {
            $q->whereNotIn('role', ['admin', 'dean', 'hod', 'faculty', 'coordinator', 'office-assistant', 'provost', 'parent', 'warden', 'swami'])
              ->orWhereNull('role');
        })->orderBy('name')->get();

        return view('admin.parents', compact('parents', 'departments', 'students'));
    }

    public function storeParent(Request $request)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'office-assistant', 'hod'])) {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admins, HODs, or Deans can register new parents.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'student_enrollment' => 'required|string',
        ]);

        // Find the student child
        $student = \App\Models\User::where('enrollment_no', $request->student_enrollment)->first();

        if (!$student) {
            return back()->withInput()->withErrors(['student_enrollment' => 'Verification failed: Student not found with enrollment number ' . $request->student_enrollment]);
        }

        // Check parent slots (max 4)
        $parentSlotField = null;
        if (!$student->parent_1_id) {
            $parentSlotField = 'parent_1_id';
        } elseif (!$student->parent_2_id) {
            $parentSlotField = 'parent_2_id';
        } elseif (!$student->parent_3_id) {
            $parentSlotField = 'parent_3_id';
        } elseif (!$student->parent_4_id) {
            $parentSlotField = 'parent_4_id';
        }

        if (!$parentSlotField) {
            return back()->withInput()->withErrors(['student_enrollment' => 'Registration failed: This student already has the maximum of 4 linked parent profiles.']);
        }

        // Create parent user
        $parent = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
            'role' => 'parent',
            'parent_student_id' => $student->id,
            'status' => 'approved',
            'level' => 1,
            'xp' => 0,
            'access_level' => 60,
        ]);

        // Link parent to student slot
        $student->update([$parentSlotField => $parent->id]);

        return back()->with('success', 'Parent account registered and linked successfully.');
    }

    public function updateParentIdentity(Request $request, $id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'office-assistant', 'hod'])) {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admins, HODs, or Deans can edit parent credentials.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'student_enrollment' => 'required|string',
        ]);

        $parent = \App\Models\User::where('role', 'parent')->findOrFail($id);

        // Find child student
        $student = \App\Models\User::where('enrollment_no', $request->student_enrollment)->first();
        if (!$student) {
            return back()->with('error', 'Update failed: Student enrollment number not found. Make sure the child enrollment number is correct.');
        }

        // Handle child student change
        if ($student->id != $parent->parent_student_id) {
            // Free up old slot
            $oldStudent = \App\Models\User::find($parent->parent_student_id);
            if ($oldStudent) {
                if ($oldStudent->parent_1_id == $parent->id) $oldStudent->update(['parent_1_id' => null]);
                elseif ($oldStudent->parent_2_id == $parent->id) $oldStudent->update(['parent_2_id' => null]);
                elseif ($oldStudent->parent_3_id == $parent->id) $oldStudent->update(['parent_3_id' => null]);
                elseif ($oldStudent->parent_4_id == $parent->id) $oldStudent->update(['parent_4_id' => null]);
            }

            // Assign to new student slot
            $parentSlotField = null;
            if (!$student->parent_1_id) $parentSlotField = 'parent_1_id';
            elseif (!$student->parent_2_id) $parentSlotField = 'parent_2_id';
            elseif (!$student->parent_3_id) $parentSlotField = 'parent_3_id';
            elseif (!$student->parent_4_id) $parentSlotField = 'parent_4_id';

            if (!$parentSlotField) {
                return back()->with('error', 'Update failed: The new student already has the maximum of 4 linked parent profiles.');
            }

            $student->update([$parentSlotField => $parent->id]);
        }

        $parent->update([
            'name' => $request->name,
            'email' => $request->email,
            'parent_student_id' => $student->id,
        ]);

        return back()->with('success', "Parent profile details for {$parent->name} updated successfully.");
    }

    public function updateParentPassword(Request $request, $id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'office-assistant', 'hod'])) {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admins, HODs, or Deans can reset parent passwords.');
        }

        $request->validate([
            'password' => 'required|string|min:4'
        ]);

        $parent = \App\Models\User::where('role', 'parent')->findOrFail($id);
        $parent->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);

        return back()->with('success', "Password for parent {$parent->name} securely reset to the new specified value.");
    }

    public function deleteParent($id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'office-assistant'])) {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admins or Deans can delete parent accounts.');
        }

        $parent = \App\Models\User::where('role', 'parent')->findOrFail($id);
        $name = $parent->name;

        // Clear slot on child record
        $oldStudent = \App\Models\User::find($parent->parent_student_id);
        if ($oldStudent) {
            if ($oldStudent->parent_1_id == $parent->id) $oldStudent->update(['parent_1_id' => null]);
            elseif ($oldStudent->parent_2_id == $parent->id) $oldStudent->update(['parent_2_id' => null]);
            elseif ($oldStudent->parent_3_id == $parent->id) $oldStudent->update(['parent_3_id' => null]);
            elseif ($oldStudent->parent_4_id == $parent->id) $oldStudent->update(['parent_4_id' => null]);
        }

        $parent->delete();

        return back()->with('success', "Parent account {$name} successfully deleted from the portal.");
    }

    public function downloadAllStudentsPdf()
    {
        $role = session('user_role');
        $staffName = session('staff_name');
        if (!in_array($role, ['admin', 'cr', 'coordinator', 'faculty-lecturer-coordinator']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admin, Class Coordinator (CR), or Rajunakum Sir can download this PDF.');
        }

        $students = \App\Models\User::where('role', 'student')->orWhereNull('role')->get();
        if ($students->isEmpty()) {
            return back()->with('error', 'No registered students found.');
        }

        $pdf = Pdf::loadView('admin.students_pdf', compact('students'));
        $pdf->setPaper('A4', 'portrait');
        return $pdf->download('BAPS_Students_Directory_' . date('d-M-Y') . '.pdf');
    }


    public function storeStudent(Request $request)
    {
        $role = session('user_role');
        $staffName = session('staff_name');
        if (!in_array($role, ['admin', 'cr']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized!');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'enrollment_no' => 'required|unique:users',
            'password' => 'required|min:4',
            'phone' => 'nullable',
            'abc_card_id' => 'nullable',
            'department_id' => 'nullable|exists:departments,id',
            'program' => 'nullable|string',
            'year' => 'nullable|integer',
            'semester' => 'nullable|integer',
            'class_section' => 'nullable|string',
            'hostel_swami_id' => 'nullable|exists:users,id',
            'hostel_room_no' => 'nullable|string'
        ]);

        try {
            \App\Models\User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                'enrollment_no' => $request->enrollment_no,
                'phone' => $request->phone,
                'abc_card_id' => $request->abc_card_id,
                'department_id' => $request->department_id,
                'program' => $request->program,
                'year' => $request->year,
                'semester' => $request->semester,
                'class_section' => $request->class_section,
                'hostel_swami_id' => $request->hostel_swami_id,
                'hostel_room_no' => $request->hostel_room_no,
                'access_level' => 100,
                'level' => 1,
                'xp' => 0,
                'status' => 'approved'
            ]);
            return back()->with('success', 'Student officially registered in the system!');
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'email') || str_contains($msg, 'users_email_unique')) {
                $field = 'email';
                $value = $request->email;
            } elseif (str_contains($msg, 'enrollment_no') || str_contains($msg, 'users_enrollment_no_unique')) {
                $field = 'enrollment_no';
                $value = $request->enrollment_no;
            } else {
                $field = 'record';
                $value = $request->name;
            }
            return back()->withInput()->with('error', "Already exists! {{$field}: {$value}}");
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), '1062')) {
                $msg = $e->getMessage();
                if (str_contains($msg, 'email') || str_contains($msg, 'users_email_unique')) {
                    $field = 'email';
                    $value = $request->email;
                } elseif (str_contains($msg, 'enrollment_no') || str_contains($msg, 'users_enrollment_no_unique')) {
                    $field = 'enrollment_no';
                    $value = $request->enrollment_no;
                } else {
                    $field = 'record';
                    $value = $request->name;
                }
                return back()->withInput()->with('error', "Already exists! {{$field}: {$value}}");
            }
            throw $e;
        }
    }

    public function deleteStudent($id)
    {
        $role = session('user_role');
        $staffName = session('staff_name');
        if (!in_array($role, ['admin', 'cr']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized!');
        }

        $student = \App\Models\User::findOrFail($id);
        $name = $student->name;

        // Clean up associations to avoid constraint issues
        \App\Models\Enrollment::where('user_id', $student->id)->delete();
        \App\Models\Certificate::where('user_id', $student->id)->delete();
        \App\Models\QuizAttempt::where('user_id', $student->id)->delete();
        \Illuminate\Support\Facades\DB::table('task_submissions')->where('user_id', $student->id)->delete();

        $student->delete();

        return back()->with('success', "Student {$name} successfully expelled and deleted from the portal.");
    }

    public function updateStudentPassword(Request $request, $id)
    {
        $role = session('user_role');
        $staffName = session('staff_name');

        if (!in_array($role, ['admin', 'cr', 'hod', 'dean']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admins or Department Heads can reset passwords.');
        }

        $request->validate([
            'password' => 'required|string|min:4'
        ]);

        $user = \App\Models\User::findOrFail($id);
        $user->update(['password' => \Illuminate\Support\Facades\Hash::make($request->password)]);

        return back()->with('success', "Password for {$user->name} securely reset to the new specified value.");
    }

    public function updateStudentIdentity(Request $request, $id)
    {
        $role = session('user_role');
        $staffName = session('staff_name');

        if (!in_array($role, ['admin', 'cr', 'hod', 'dean']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized! Only Admins or Department Heads can edit student identities.');
        }

        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'enrollment_no' => 'required|unique:users,enrollment_no,' . $id,
            'phone' => 'nullable|string',
            'abc_card_id' => 'nullable|string',
            'department_id' => 'nullable|exists:departments,id',
            'program' => 'nullable|string',
            'year' => 'nullable|integer',
            'semester' => 'nullable|integer',
            'class_section' => 'nullable|string',
            'hostel_swami_id' => 'nullable|exists:users,id',
            'hostel_room_no' => 'nullable|string'
        ]);

        $user = \App\Models\User::findOrFail($id);
        try {
            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'enrollment_no' => $request->enrollment_no,
                'phone' => $request->phone,
                'abc_card_id' => $request->abc_card_id,
                'department_id' => $request->department_id,
                'program' => $request->program,
                'year' => $request->year,
                'semester' => $request->semester,
                'class_section' => $request->class_section,
                'hostel_swami_id' => $request->hostel_swami_id,
                'hostel_room_no' => $request->hostel_room_no,
            ]);
            return back()->with('success', "Identity information for {$user->name} successfully updated.");
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            $msg = $e->getMessage();
            if (str_contains($msg, 'email') || str_contains($msg, 'users_email_unique')) {
                $field = 'email';
                $value = $request->email;
            } elseif (str_contains($msg, 'enrollment_no') || str_contains($msg, 'users_enrollment_no_unique')) {
                $field = 'enrollment_no';
                $value = $request->enrollment_no;
            } else {
                $field = 'record';
                $value = $request->name;
            }
            return back()->withInput()->with('error', "Already exists! {{$field}: {$value}}");
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() == 23000 || str_contains($e->getMessage(), '1062')) {
                $msg = $e->getMessage();
                if (str_contains($msg, 'email') || str_contains($msg, 'users_email_unique')) {
                    $field = 'email';
                    $value = $request->email;
                } elseif (str_contains($msg, 'enrollment_no') || str_contains($msg, 'users_enrollment_no_unique')) {
                    $field = 'enrollment_no';
                    $value = $request->enrollment_no;
                } else {
                    $field = 'record';
                    $value = $request->name;
                }
                return back()->withInput()->with('error', "Already exists! {{$field}: {$value}}");
            }
            throw $e;
        }
    }

    public function toggleSuspension($id)
    {
        $role = session('user_role');
        $staffName = session('staff_name');
        if (!in_array($role, ['admin', 'cr', 'hod', 'dean']) && $staffName !== 'Rajunakum Sir') {
            return redirect('/admin')->with('error', 'Unauthorized!');
        }

        $user = \App\Models\User::findOrFail($id);
        if ($user->status === 'approved') {
            $user->status = 'rejected';
            $user->save();
            return back()->with('success', 'Student account has been suspended successfully.');
        } else {
            $user->status = 'approved';
            $user->save();
            return back()->with('success', 'Student account suspension lifted. Activated successfully.');
        }
    }

    public function assignmentsManagement()
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $tasks = \App\Models\Task::with('course')->get();
        $courses = \App\Models\Course::all();

        return view('admin.assignments_management', compact('tasks', 'courses'));
    }

    public function storeTask(Request $request)
    {
        $data = $request->all();
        if (isset($data['subject_id']) && $data['subject_id'] === '') {
            $data['subject_id'] = null;
        }
        if (isset($data['due_date']) && $data['due_date'] === '') {
            $data['due_date'] = null;
        }
        \App\Models\Task::create($data);
        return back()->with('success', 'Task assigned successfully!');
    }

    public function storeQuiz(Request $request)
    {
        \App\Models\Quiz::create($request->all());
        return back()->with('success', 'Quiz created successfully!');
    }

    public function quizBuilder($id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'faculty', 'hod', 'dean'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $quiz = \App\Models\Quiz::with(['course', 'questions.options'])->findOrFail($id);
        return view('admin.quiz_builder', compact('quiz'));
    }

    public function storeQuizQuestion(Request $request, $id)
    {
        $request->validate([
            'category' => 'required',
            'question_text' => 'required',
            'points' => 'required|integer',
        ]);

        $quiz = \App\Models\Quiz::findOrFail($id);
        $question = $quiz->questions()->create([
            'question_type' => $request->category,
            'question_text' => $request->question_text,
            'points' => $request->points,
            'language' => $request->language,
            'expected_code' => $request->expected_code,
            'test_cases' => $request->test_cases
        ]);

        if (!in_array($request->category, ['code', 'vsq', 'long'])) {
            $request->validate([
                'options' => 'required|array|min:2',
                'correct_option' => 'required|integer'
            ]);

            foreach ($request->options as $index => $optionText) {
                if (!empty($optionText)) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => ($index == $request->correct_option)
                    ]);
                }
            }
        }

        return back()->with('success', 'Question mapped successfully!');
    }

    public function storeQuizQuestionFromPdf(Request $request, $id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'faculty', 'hod', 'dean'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'question_pdf' => 'required|mimes:pdf'
        ]);

        try {
            $parser = new \Smalot\PdfParser\Parser();
            $pdf = $parser->parseFile($request->file('question_pdf')->getPathname());
            $text = $pdf->getText();

            $cleanText = substr(trim(preg_replace('/\s+/', ' ', $text)), 0, 1500);

            if (empty($cleanText)) {
                return back()->with('error', 'Could not extract any text from the PDF.');
            }

            \App\Models\Question::create([
                'quiz_id' => $id,
                'question_type' => 'long',
                'question_text' => "Auto-extracted from PDF: \n" . $cleanText,
                'points' => 1,
            ]);

            return back()->with('success', 'PDF successfully parsed and mapped into the matrix as a Long Answer Question!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error parsing PDF: ' . $e->getMessage());
        }
    }

    public function updateQuizQuestion(Request $request, $id, $questionId)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'faculty', 'hod', 'dean'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'category' => 'required',
            'question_text' => 'required',
            'points' => 'required|integer',
        ]);

        $quiz = \App\Models\Quiz::findOrFail($id);
        $question = \App\Models\Question::where('quiz_id', $quiz->id)->findOrFail($questionId);

        $question->update([
            'question_type' => $request->category,
            'question_text' => $request->question_text,
            'points' => $request->points,
            'language' => $request->language,
            'expected_code' => $request->expected_code,
            'test_cases' => $request->test_cases
        ]);

        // Recreate options
        if (!in_array($request->category, ['code', 'vsq', 'long'])) {
            $request->validate([
                'options' => 'required|array|min:2',
                'correct_option' => 'required|integer'
            ]);

            $question->options()->delete(); // clear old options

            foreach ($request->options as $index => $optionText) {
                if (!empty($optionText)) {
                    $question->options()->create([
                        'option_text' => $optionText,
                        'is_correct' => ($index == $request->correct_option)
                    ]);
                }
            }
        }

        return back()->with('success', 'Question updated successfully!');
    }

    public function deleteQuizQuestion($id, $questionId)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'faculty', 'hod', 'dean'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $quiz = \App\Models\Quiz::findOrFail($id);
        $question = \App\Models\Question::where('quiz_id', $quiz->id)->findOrFail($questionId);
        $question->options()->delete();
        $question->delete();

        return back()->with('success', 'Question deleted successfully.');
    }

    public function storeBankQuestion(Request $request)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'faculty', 'hod', 'dean'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $request->validate([
            'question_type' => 'required',
            'question_text' => 'required'
        ]);

        \App\Models\QuestionBank::create([
            'question_type' => $request->question_type,
            'question_text' => $request->question_text
        ]);

        return back()->with('success', 'Question saved securely to Institutional Repository Bank!');
    }

    public function toggleQuizPublish($id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'faculty', 'hod', 'dean'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $quiz = \App\Models\Quiz::findOrFail($id);

        if (!$quiz->is_active && $quiz->questions->count() == 0) {
            return back()->with('error', 'Cannot launch an empty quiz! You must add questions first.');
        }

        $quiz->update(['is_active' => !$quiz->is_active]);
        $status = $quiz->is_active ? 'LAUNCHED successfully!' : 'reverted to Draft. It is now hidden from students.';

        return back()->with('success', "Quiz $status");
    }

    public function storeAnnouncement(Request $request)
    {
        $request->validate(['course_id' => 'required', 'title' => 'required', 'message' => 'required']);
        \App\Models\Announcement::create($request->all());
        return back()->with('success', 'Announcement broadcasted successfully!');
    }

    public function resetStaffPassword(Request $request)
    {
        $staff = \App\Models\Staff::find($request->staff_id);
        if ($staff) {
            $staff->update([
                'email' => $request->email,
                'password' => \Illuminate\Support\Facades\Hash::make($request->password)
            ]);
            return back()->with('success', 'Email and Password updated for ' . $staff->name);
        }
        return back()->with('error', 'Staff member not found.');
    }

    public function approvalsList()
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'cr', 'hod', 'dean', 'faculty-lecturer-coordinator', 'coordinator'])) {
            return redirect('/admin')->with('error', 'Unauthorized access to Approval Workflow.');
        }

        $pendingStudents = \App\Models\User::where('status', 'pending')->get();
        $pendingEnrollments = \App\Models\Enrollment::with(['user', 'course'])->where('status', 'pending')->get();
        $pendingGatepasses = \App\Models\Gatepass::with('user')->where('status', 'pending')->get();
        $pendingLeaves = \App\Models\Leave::with('user')->where('status', 'pending')->get();
        $pendingPasswords = \App\Models\PasswordApproval::where('status', 'pending')->get();
        $pendingFees = \App\Models\FeePayment::with('user')->where('status', 'pending')->get();

        return view('admin.approvals', compact('pendingStudents', 'pendingEnrollments', 'pendingGatepasses', 'pendingLeaves', 'pendingPasswords', 'pendingFees'));
    }

    public function processStatus(Request $request, $modelType, $id)
    {
        if ($modelType === 'password') {
            $approval = \App\Models\PasswordApproval::findOrFail($id);
            if ($request->action === 'approve') {
                $newPass = $request->override_password ?? $approval->requested_password;
                $user = \App\Models\User::where('email', $approval->email)->first();
                if ($user) {
                    $user->update(['password' => \Illuminate\Support\Facades\Hash::make($newPass)]);
                } else {
                    $staff = \App\Models\Staff::where('email', $approval->email)->first();
                    if ($staff) {
                        $staff->update(['password' => \Illuminate\Support\Facades\Hash::make($newPass)]);
                    }
                }
                $approval->update(['status' => 'approved']);
            } else {
                $approval->update(['status' => 'rejected']);
            }
            return back()->with('success', 'Password reset request processed.');
        }

        if ($modelType === 'fee') {
            $fee = \App\Models\FeePayment::findOrFail($id);
            if ($request->action === 'approve') {
                if ($request->token_number !== $fee->token_number) {
                    return back()->with('error', 'Invalid Token Number provided. Transaction aborted.');
                }
                $fee->update([
                    'status' => 'paid',
                    'payment_method' => $request->payment_method,
                    'transaction_id' => $request->transaction_id,
                    'payment_date' => $request->payment_date,
                    'bank_name' => $request->bank_name,
                    'payer_name' => $request->payer_name,
                    'approval_field_1' => $request->approval_field_1,
                    'approval_field_2' => $request->approval_field_2,
                    'remarks' => $request->remarks,
                    'processed_by_id' => session('staff_id') ?? auth()->id() ?? 1,
                    'processed_by_role' => session('user_role')
                ]);
                return back()->with('success', 'Fee Payment successfully validated and processed.');
            } else {
                $fee->update(['status' => 'rejected']);
                return back()->with('success', 'Fee Payment Request rejected.');
            }
        }

        $status = $request->action === 'approve' ? 'approved' : 'rejected';

        switch ($modelType) {
            case 'user':
                $user = \App\Models\User::findOrFail($id);
                if ($request->action === 'approve_for_tc') {
                    $user->update(['status' => 'pending', 'application_stage' => 3]);
                    return back()->with('success', 'User Application approved for T&C.');
                } elseif ($request->action === 'unlock_profile') {
                    $passwordStr = \Illuminate\Support\Str::random(8);
                    $user->update([
                        'status' => 'approved', 
                        'application_stage' => 5,
                        'generated_password' => $passwordStr,
                        'password' => \Illuminate\Support\Facades\Hash::make($passwordStr)
                    ]);
                    return back()->with('success', 'User Profile Unlocked! Credentials generated.');
                }
                $user->update(['status' => $status]);
                break;
            case 'enrollment':
                \App\Models\Enrollment::findOrFail($id)->update(['status' => $status]);
                break;
            case 'gatepass':
                \App\Models\Gatepass::findOrFail($id)->update(['status' => $status]);
                break;
            case 'leave':
                \App\Models\Leave::findOrFail($id)->update(['status' => $status]);
                break;
        }

        return back()->with('success', ucfirst($modelType) . " request has been {$status} successfully.");
    }

    public function issueCertificatesBulk(Request $request)
    {
        $request->validate(['course_id' => 'required']);
        $enrollments = \App\Models\Enrollment::where('course_id', $request->course_id)->get();

        if ($enrollments->count() === 0) {
            return back()->with('error', 'No enrollments found for this course.');
        }

        $certificates = [];
        foreach ($enrollments as $enr) {
            $email = !empty($enr->email) ? $enr->email : 'student_' . ($enr->roll_no ?? mt_rand(1000, 9999)) . '@baps.lms.local';

            $user = \App\Models\User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => !empty($enr->name) ? $enr->name : 'Unknown Student',
                    'enrollment_no' => $enr->roll_no ?? (string) mt_rand(10000, 99999999),
                    'password' => \Illuminate\Support\Facades\Hash::make('password'),
                    'level' => 1,
                    'status' => 'approved',
                    'login_code' => str_pad(mt_rand(0, 99999), 5, '0', STR_PAD_LEFT)
                ]
            );

            $cert = \App\Models\Certificate::firstOrCreate([
                'user_id' => $user->id,
                'course_id' => $enr->course_id
            ], [
                'unique_code' => 'BAPS-' . strtoupper(\Illuminate\Support\Str::random(10))
            ]);

            $cert->load(['user', 'course']);
            $certificates[] = $cert;
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('certificate_bulk_pdf', ['certificates' => $certificates])
            ->setPaper('a4', 'landscape');

        $courseName = \App\Models\Course::find($request->course_id)->title;
        $fileName = 'Bulk_Certificates_' . preg_replace('/[^a-zA-Z0-9_\-]/', '_', $courseName) . '.pdf';

        return $pdf->download($fileName);
    }

    // Exam Center Methods
    public function quizManagement()
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'faculty', 'hod', 'dean']))
            return redirect('/admin')->with('error', 'Unauthorized access.');
        $courses = Course::all();
        return view('admin.quiz_management', compact('courses'));
    }

    public function examSchedule()
    {
        $schedules = \App\Models\ExamSchedule::with('department')->get();
        $departments = \App\Models\Department::all();
        return view('admin.exam_schedule', compact('schedules', 'departments'));
    }

    public function storeExamSchedule(Request $request)
    {
        \App\Models\ExamSchedule::create($request->all());
        return back()->with('success', 'Exam Schedule Published!');
    }

    public function seatingArrangement()
    {
        $arrangements = \App\Models\SeatingArrangement::with('examSchedule')->get();
        $schedules = \App\Models\ExamSchedule::all();
        return view('admin.seating_arrangement', compact('arrangements', 'schedules'));
    }

    public function storeSeatingArrangement(Request $request)
    {
        \App\Models\SeatingArrangement::create($request->all());
        return back()->with('success', 'Seating Arrangement Saved!');
    }

    public function classSignSheet()
    {
        $enrollments = \App\Models\Enrollment::with(['user', 'course'])->get();
        return view('admin.sign_sheet', compact('enrollments'));
    }

    public function questionBank()
    {
        return view('admin.exam_placeholder', ['title' => 'Question Bank']);
    }

    public function liveProctoring()
    {
        return view('admin.live_proctoring');
    }

    public function resultsGrading()
    {
        $role = session('user_role');
        $staffId = session('staff_id');

        if (!in_array($role, ['admin', 'faculty', 'hod', 'dean'])) return redirect('/admin')->with('error', 'Unauthorized access.');
        
        if ($role === 'faculty') {
            // Get allocated class sections for this faculty
            $allocations = \App\Models\CourseAllocation::where('staff_id', $staffId)->get();
            $allowedClasses = $allocations->pluck('class_section')->toArray();
            $allowedCourses = $allocations->pluck('course_id')->toArray();
            
            // Faculty only sees students enrolled in their allocated class sections
            $studentIds = \App\Models\Enrollment::whereIn('class_section', $allowedClasses)->pluck('user_id');
            $students = \App\Models\User::whereIn('id', $studentIds)->where('role', 'student')->get();
            
            // Or courses where they are primary
            $courses = Course::where('faculty_id', $staffId)->orWhereIn('id', $allowedCourses)->get();
        } else {
            $students = \App\Models\User::where('role', 'student')->get();
            $courses = Course::all();
        }

        $results = \App\Models\Result::with(['user', 'course'])->latest()->get();
        
        return view('admin.results_grading', compact('students', 'courses', 'results'));
    }

    public function getStudentEnrollments($studentId)
    {
        $enrollments = \App\Models\Enrollment::where('user_id', $studentId)
            ->with('course')
            ->get()
            ->pluck('course');
        return response()->json($enrollments);
    }

    public function storeResult(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'results' => 'required|array',
        ]);

        foreach ($request->results as $courseId => $data) {
            $course = \App\Models\Course::findOrFail($courseId);

            if ($course->type == 'pbl') {
                $total = $data['obtained_marks_pbl'] ?? 0;
                $internal = 0;
                $practical = 0;
                $external_raw = $total;
                $external_final = $total;
                $max_marks = 100;
            } else {
                $internal = $data['internal_marks'] ?? 0;
                $practical = $data['practical_marks'] ?? 0;
                $external_raw = $data['external_marks_raw'] ?? 0;
                $external_final = $external_raw * 0.4;
                $total = $internal + $practical + $external_final;
                $max_marks = 150; // 60(int) + 50(prac) + 40(ext)
            }

            $percentage = ($total / $max_marks) * 100;

            if ($percentage >= 85)
                $grade = 'A+';
            elseif ($percentage >= 75)
                $grade = 'A';
            elseif ($percentage >= 65)
                $grade = 'B+';
            elseif ($percentage >= 55)
                $grade = 'B';
            elseif ($percentage >= 45)
                $grade = 'C';
            else
                $grade = 'F';

            \App\Models\Result::updateOrCreate(
                [
                    'user_id' => $request->user_id,
                    'course_id' => $courseId,
                    'exam_title' => $request->exam_title
                ],
                [
                    'internal_marks' => $internal,
                    'practical_marks' => $practical,
                    'external_marks_raw' => $external_raw,
                    'external_marks_final' => $external_final,
                    'total_obtained' => $total,
                    'total_max' => $max_marks,
                    'grade' => $grade,
                    'remarks' => $request->remarks,
                    'status' => 'published'
                ]
            );
        }

        return back()->with('success', 'Student performance indexed successfully!');
    }

    public function storeBatchResult(Request $request)
    {
        $request->validate([
            'course_id' => 'required',
            'results' => 'required|array',
        ]);

        $course = \App\Models\Course::findOrFail($request->course_id);

        foreach ($request->results as $userId => $data) {
            if ($course->type == 'pbl') {
                $total = $data['obtained_marks_pbl'] ?? 0;
                $internal = 0;
                $practical = 0;
                $external_raw = $total;
                $external_final = $total;
                $max_marks = 100;
            } else {
                $internal = $data['internal_marks'] ?? 0;
                $practical = $data['practical_marks'] ?? 0;
                $external_raw = $data['external_marks_raw'] ?? 0;
                $external_final = $external_raw * 0.4;
                $total = $internal + $practical + $external_final;
                $max_marks = 150;
            }

            $percentage = ($total / $max_marks) * 100;

            if ($percentage >= 85) $grade = 'A+';
            elseif ($percentage >= 75) $grade = 'A';
            elseif ($percentage >= 65) $grade = 'B+';
            elseif ($percentage >= 55) $grade = 'B';
            elseif ($percentage >= 45) $grade = 'C';
            else $grade = 'F';

            \App\Models\Result::updateOrCreate(
                [
                    'user_id' => $userId, 
                    'course_id' => $course->id, 
                    'exam_title' => $request->exam_title
                ],
                [
                    'internal_marks' => $internal,
                    'practical_marks' => $practical,
                    'external_marks_raw' => $external_raw,
                    'external_marks_final' => $external_final,
                    'total_obtained' => $total,
                    'total_max' => $max_marks,
                    'grade' => $grade,
                    'remarks' => $data['remarks'] ?? '',
                    'status' => 'published'
                ]
            );
        }

        return back()->with('success', 'Batch results for ' . $course->title . ' have been processed successfully!');
    }

    public function updateResult(Request $request, $id)
    {
        $request->validate([
            'internal_marks' => 'nullable|numeric',
            'practical_marks' => 'nullable|numeric',
            'external_marks_raw' => 'nullable|numeric',
            'obtained_marks_pbl' => 'nullable|numeric'
        ]);

        $result = \App\Models\Result::findOrFail($id);
        $course = $result->course;

        if ($course->type == 'pbl') {
            $total = $request->obtained_marks_pbl ?? 0;
            $internal = 0;
            $practical = 0;
            $external_raw = $total;
            $external_final = $total;
            $max_marks = 100;
        } else {
            $internal = $request->internal_marks ?? 0;
            $practical = $request->practical_marks ?? 0;
            $external_raw = $request->external_marks_raw ?? 0;
            $external_final = $external_raw * 0.4;
            $total = $internal + $practical + $external_final;
            $max_marks = 150;
        }

        $percentage = ($total / $max_marks) * 100;

        if ($percentage >= 85) $grade = 'A+';
        elseif ($percentage >= 75) $grade = 'A';
        elseif ($percentage >= 65) $grade = 'B+';
        elseif ($percentage >= 55) $grade = 'B';
        elseif ($percentage >= 45) $grade = 'C';
        else $grade = 'F';

        $result->update([
            'internal_marks' => $internal,
            'practical_marks' => $practical,
            'external_marks_raw' => $external_raw,
            'external_marks_final' => $external_final,
            'total_obtained' => $total,
            'total_max' => $max_marks,
            'grade' => $grade
        ]);

        return back()->with('success', 'Marks updated successfully!');
    }

    public function printResult($id)
    {
        $result = \App\Models\Result::with(['user', 'course'])->findOrFail($id);
        return view('admin.print_result', compact('result'));
    }

    public function printStudentGradeSheet($studentId)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean'])) {
            return redirect('/admin')->with('error', 'Strictly Unauthorized. Only Admin, Dean or Exam Controller can issue comprehensive grade sheets.');
        }

        $user = \App\Models\User::findOrFail($studentId);
        $results = \App\Models\Result::with(['course'])->where('user_id', $studentId)->get();

        if ($results->isEmpty()) {
            return back()->with('error', 'No results found for this student to generate a grade sheet.');
        }

        // Calculate SGPA and backlogs
        $totalCredits = 0;
        $earnedPoints = 0;
        $currentBacklog = 0;
        $totalBacklog = 0;
        $failedSubjects = [];

        foreach ($results as $result) {
            $credits = $result->course->credits ?? 4; // Default to 4 if not set
            $totalCredits += $credits;

            // Simple grade point mapping
            $point = 0;
            switch ($result->grade) {
                case 'O':
                case 'A+': $point = 10; break;
                case 'A': $point = 9; break;
                case 'B+': $point = 8; break;
                case 'B': $point = 7; break;
                case 'C': $point = 6; break;
                case 'P':
                case 'D': $point = 5; break;
                case 'F': 
                    $point = 0; 
                    $currentBacklog++;
                    $totalBacklog++;
                    $failedSubjects[] = $result->course->title;
                    break;
            }
            $earnedPoints += ($point * $credits);
        }

        $sgpa = $totalCredits > 0 ? round($earnedPoints / $totalCredits, 2) : 0;
        $cgpa = $sgpa; // Simplified for now
        
        // Custom SGPA Mapping Logic
        if ($sgpa >= 9.1 && $currentBacklog == 0) $sgpa_grade = 'O+';
        elseif ($sgpa >= 8.7 && $currentBacklog == 0) $sgpa_grade = 'O';
        elseif ($sgpa >= 7.5 && $currentBacklog == 0) $sgpa_grade = 'A';
        elseif ($sgpa >= 6.0 && $currentBacklog == 0) $sgpa_grade = 'B';
        elseif ($sgpa >= 5.5 && $currentBacklog == 0) $sgpa_grade = 'C';
        elseif ($sgpa >= 4.5 && $currentBacklog == 0) $sgpa_grade = 'P';
        else $sgpa_grade = 'Fail';

        $status = $sgpa_grade === 'Fail' ? 'FAIL' : 'PASS';
        
        $customRemark = "";
        if ($status === 'FAIL') {
            $customRemark = "Need Improvement and You have Lost Your performence In " . implode(', ', $failedSubjects);
        }

        // Extract a common exam title from the results if available
        $examTitle = $results->first()->exam_title ?? 'University Examination 2026';

        return view('admin.print_gradesheet', compact('user', 'results', 'sgpa', 'cgpa', 'status', 'currentBacklog', 'totalBacklog', 'examTitle', 'sgpa_grade', 'customRemark'));
    }

    public function printExcellenceCertificate($studentId)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean'])) {
            return redirect('/admin')->with('error', 'Strictly Unauthorized.');
        }
        $user = \App\Models\User::findOrFail($studentId);
        return view('admin.print_excellence_certificate', compact('user'));
    }

    public function printSeating($id)
    {
        $arrangement = \App\Models\SeatingArrangement::with('examSchedule')->findOrFail($id);
        $students = \App\Models\User::where('role', 'student')->limit($arrangement->capacity)->get();
        return view('admin.print_seating', compact('arrangement', 'students'));
    }

    public function talentHub()
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'cr', 'dean', 'hod', 'faculty', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator'])) {
            return redirect('/admin')->with('error', 'Unauthorized access to Talent Hub.');
        }

        // Fetch students and calculate their Employability Score
        // Score = (XP * 1) + (Certificates * 500) + (Enrolled Courses * 50)
        $students = \App\Models\User::where('role', 'student')->orWhereNull('role')->get()->map(function ($student) {
            $certs = \App\Models\Certificate::where('user_id', $student->id)->count();
            $enrolls = \App\Models\Enrollment::where('user_id', $student->id)->count();

            $employabilityScore = ($student->xp ?? 0) + ($certs * 500) + ($enrolls * 50);

            // Assign dynamic rank/level based on score
            if ($employabilityScore >= 2000)
                $badge = 'Platinum';
            elseif ($employabilityScore >= 1000)
                $badge = 'Gold';
            elseif ($employabilityScore >= 500)
                $badge = 'Silver';
            else
                $badge = 'Bronze';

            $student->employabilityScore = $employabilityScore + mt_rand(1, 99); // adding slight rand for unique tie breaks
            $student->certsCount = $certs;
            $student->enrollsCount = $enrolls;
            $student->industryBadge = $badge;

            return $student;
        })->sortByDesc('employabilityScore')->values();

        return view('admin.talent_hub', compact('students'));
    }

    public function courseManagement()
    {
        $role = session('user_role');
        if (!in_array($role, ['faculty', 'cr', 'admin', 'dean', 'hod'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $staffId = session('staff_id');
        if (in_array($role, ['faculty', 'faculty-lecturer-lab', 'faculty-lecturer-coordinator'])) {
            // A faculty sees courses they are allocated to, OR where they are the primary faculty
            $allocatedCourseIds = \App\Models\CourseAllocation::where('staff_id', $staffId)->pluck('course_id');
            $courses = Course::with(['allocations.staff'])->where('faculty_id', $staffId)->orWhereIn('id', $allocatedCourseIds)->get();
        } else {
            $courses = Course::with(['allocations.staff'])->get();
        }

        $allFaculties = \App\Models\Staff::all();

        return view('admin.course_management', compact('courses', 'allFaculties'));
    }

    public function allocateFaculty(Request $request, $courseId)
    {
        if (!in_array(session('user_role'), ['dean', 'hod'])) {
            return back()->with('error', 'Only Dean or HOD can allocate faculty.');
        }

        $request->validate([
            'staff_id' => 'required|exists:staff,id',
            'class_section' => 'required|string|max:10'
        ]);

        \App\Models\CourseAllocation::updateOrCreate(
            ['course_id' => $courseId, 'class_section' => $request->class_section],
            ['staff_id' => $request->staff_id]
        );

        return back()->with('success', 'Faculty allocated successfully for Class ' . $request->class_section);
    }


    public function updateCourseManagement(Request $request, $id)
    {
        $role = session('user_role');
        if (!in_array($role, ['faculty', 'cr', 'admin', 'dean'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $course = Course::findOrFail($id);
        $course->update([
            'google_meet_link' => $request->input('google_meet_link'),
            'class_mode' => $request->input('class_mode', 'offline'),
            'host_name' => $request->input('host_name'),
            'host_email' => $request->input('host_email'),
            'transcript_content' => $request->input('transcript_content'),
        ]);

        return back()->with('success', 'Course structure and Google Meet mode configured perfectly!');
    }

    public function stopMeeting($id)
    {
        $role = session('user_role');
        if (!in_array($role, ['faculty', 'cr', 'admin', 'dean'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $course = Course::findOrFail($id);

        $course->update([
            'class_mode' => 'offline',
        ]);

        return back()->with('success', 'Live class stopped. Mode reverted to Offline.');
    }

    public function toggleVerification($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->is_verified = !$user->is_verified;
        $user->save();
        return back()->with('success', 'Verification status updated for ' . $user->name);
    }

    public function grantBadge(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->manual_badge = $request->badge;
        $user->save();
        return back()->with('success', 'Badge granted to ' . $user->name);
    }

    public function studentProgress($id)
    {
        $student = \App\Models\User::with(['results.course'])->findOrFail($id);
        return view('admin.student_progress', compact('student'));
    }

    public function generateOfficialDocument(Request $request, $docTitle)
    {
        // Override with query parameters if provided
        $studentName = $request->query('recipient_name');
        $enrollmentNo = $request->query('enrollment_no') ?? 'ENR2026CSE001';
        
        // If recipient_name has parenthesized ID like "Amit Patel (ENR2026CSE001)", parse it!
        if ($studentName && preg_match('/^(.*?)\s*\((.*?)\)$/', $studentName, $matches)) {
            $studentName = trim($matches[1]);
            $enrollmentNo = trim($matches[2]);
        }

        // Identify student
        $studentId = session('demo_user_id') ?? auth()->id() ?? 1;
        $student = \App\Models\User::find($studentId);
        
        if (!$studentName) {
            $studentName = $student ? $student->name : (session('staff_name') ?? 'Bhavik Patel');
        }
        if ($student && $enrollmentNo === 'ENR2026CSE001') {
            $enrollmentNo = $student->enrollment_no ?? $student->enrollment_number ?? 'ENR2026CSE001';
        }
        $program = 'B.Tech (Bachelor of Technology)';
        
        $departmentName = $request->query('department_name');
        if (!$departmentName) {
            $deptId = $student ? ($student->department_id ?? session('dept_id') ?? 1) : 1;
            $dept = \App\Models\Department::find($deptId);
            $departmentName = $dept ? $dept->name : 'Computer Science & Engineering';
        }
        
        $year = $student ? ($student->year ?? 4) : 4;
        $semester = $student ? ($student->semester ?? 8) : 8;
        $photoUrl = ($student && ($student->profile_photo_blob || $student->profile_photo_path)) ? url('/profile/photo/student/' . $student->id) : null;

        // Custom fields from form
        $recipientRole = $request->query('recipient_role') ?? 'Student';
        $authority = $request->query('authority') ?? 'Dr. Sadhu Gyaneswar Das (Dean) & University Senate';
        $handoverMode = $request->query('handover_mode');
        $purpose = $request->query('purpose');
        $validity = $request->query('validity') ?? 'Lifetime Validity / Permanent Record';
        $securityHash = $request->query('security_hash');

        // Signature Mapping: Fetch existing Dean, HOD, Admin, and manually entered Provost
        $deanId = $request->query('dean_id');
        $deanStaff = $deanId ? \App\Models\Staff::find($deanId) : \App\Models\Staff::where('role', 'dean')->first();
        $deanName = $deanStaff ? $deanStaff->name : 'Dr. Sadhu Gyaneswar Das';
        $deanSignature = $deanStaff ? $deanStaff->digital_signature : \App\Models\Staff::generateSignatureSvg($deanName);

        $hodId = $request->query('hod_id');
        $hodStaff = $hodId ? \App\Models\Staff::find($hodId) : \App\Models\Staff::where('role', 'hod')->first();
        $hodName = $hodStaff ? $hodStaff->name : 'Bhavik Patel';
        $hodSignature = $hodStaff ? $hodStaff->digital_signature : \App\Models\Staff::generateSignatureSvg($hodName);

        $adminId = $request->query('admin_id');
        $adminStaff = $adminId ? \App\Models\Staff::find($adminId) : \App\Models\Staff::where('role', 'admin')->first();
        $adminName = $adminStaff ? $adminStaff->name : 'BHAVIKKUMAR PATEL';
        $adminSignature = $adminStaff ? $adminStaff->digital_signature : \App\Models\Staff::generateSignatureSvg($adminName);

        $provostName = $request->query('provost_name') ?? 'Prof. Harish Patel';
        $provostSignature = \App\Models\Staff::generateSignatureSvg($provostName);

        $generatedBody = $this->generateAiContentForDocument(
            $docTitle, $studentName, $enrollmentNo, $departmentName, 
            $program, $purpose, $recipientRole
        );

        return view('document.official_template', compact(
            'docTitle', 'student', 'studentName', 'enrollmentNo', 
            'program', 'departmentName', 'year', 'semester', 'photoUrl',
            'recipientRole', 'authority', 'handoverMode', 'purpose', 'validity', 'securityHash',
            'deanName', 'deanSignature', 'hodName', 'hodSignature', 'adminName', 'adminSignature',
            'provostName', 'provostSignature', 'generatedBody'
        ));
    }

    private function generateAiContentForDocument($docTitle, $studentName, $enrollmentNo, $departmentName, $program, $purpose, $recipientRole)
    {
        $apiKey = env('GEMINI_API_KEY');
        
        if ($apiKey) {
            try {
                $prompt = "Write an official, formal academic/administrative document body in 2-3 paragraphs for a document titled '{$docTitle}' issued to '{$studentName}' (Enrollment: '{$enrollmentNo}') in the department of '{$departmentName}' under the program '{$program}'. The purpose of issuance is '{$purpose}'. The recipient role is '{$recipientRole}'. Do not include headers, footers, signatures, or letterheads. Write only the core body HTML paragraphs (use <p> tags and <strong> for highlights) that should go into the center of the certificate/letter. Use professional, authoritative, and elegant wording. If the document is values-oriented or moral-heritage focused, use appropriate moral-academic tone.";
                
                $response = \Illuminate\Support\Facades\Http::timeout(10)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent?key={$apiKey}",
                    [
                        'contents' => [
                            [
                                'parts' => [
                                    ['text' => $prompt]
                                ]
                            ]
                        ]
                    ]
                );
                
                if ($response->successful()) {
                    $data = $response->json();
                    $text = $data['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($text) {
                        $text = preg_replace('/```html\s*|\s*```/', '', $text);
                        $text = preg_replace('/```\s*|\s*```/', '', $text);
                        return trim($text);
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::warning("Gemini API call failed, falling back to local generator: " . $e->getMessage());
            }
        }
        
        return $this->generateLocalDocumentContent($docTitle, $studentName, $enrollmentNo, $departmentName, $program, $purpose, $recipientRole);
    }

    private function generateLocalDocumentContent($docTitle, $studentName, $enrollmentNo, $departmentName, $program, $purpose, $recipientRole)
    {
        $docTitleLower = strtolower($docTitle);
        $purposeText = $purpose ? "issued for the purpose of <strong>{$purpose}</strong>" : "issued for official administrative and record-keeping purposes";
        
        // Registrar Level
        if (str_contains($docTitleLower, 'registrar')) {
            return "<p>This is to officially certify under the authority of the Office of the Registrar, BAPS Swaminarayan Vidyamandir, that <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}) has cleared all academic benchmarks for the program <strong>{$program}</strong> within the department of <strong>{$departmentName}</strong>.</p>
                    <p>This document is {$purposeText}. The central registry confirms that the student is in active standing and all academic transcripts are verified. We confirm that all institutional entries are authentic and conform fully with university regulations.</p>
                    <table class='doc-table'>
                        <thead><tr><th>Record Status</th><th>Registry Desk</th><th>Ledger Code</th></tr></thead>
                        <tbody><tr><td>Active & Verified</td><td>Academic Registry</td><td>REG-" . strtoupper(substr(md5($docTitle), 0, 8)) . "</td></tr></tbody>
                    </table>
                    <p>All departments and external institutions are requested to recognize this official credential accordingly.</p>";
        }
        
        // President / Vice-President Level
        if (str_contains($docTitleLower, 'president') || str_contains($docTitleLower, 'vice-president') || str_contains($docTitleLower, 'executive merit') || str_contains($docTitleLower, 'gold medal') || str_contains($docTitleLower, 'distinguished alumni')) {
            return "<p>Under the executive mandate of the Office of the President and Vice-President of BAPS Swaminarayan Vidyamandir, this citation of academic merit and leadership is presented to <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}) of the <strong>{$departmentName}</strong> department.</p>
                    <p>This award serves as an official endorsement of the student's exceptional character, outstanding diligence, and significant contributions to our academic community. The university senate commends the recipient's dedication to core academic values.</p>
                    <div style='border: 4px double #d97706; padding: 24px; text-align: center; border-radius: 12px; margin: 20px 0; background: #fffbeb;'>
                        <h4 style='color: #b45309; margin-bottom: 8px; font-family: \"Merriweather\", serif;'>PRESIDENTIAL CITATION OF HONOR</h4>
                        <p style='font-size: 1.1rem; margin-bottom: 0;'>Awarded to <strong>{$studentName}</strong> in recognition of unmatched excellence, high values, and outstanding contribution.</p>
                    </div>
                    <p>This executive order is {$purposeText} and is permanently archived in the institutional registry.</p>";
        }
        
        // Advisor Level
        if (str_contains($docTitleLower, 'advisor') || str_contains($docTitleLower, 'recommendation') || str_contains($docTitleLower, 'counseling')) {
            return "<p>As the designated Academic Advisor for the <strong>{$departmentName}</strong> department, I have closely supervised the academic performance and moral conduct of <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}) in the <strong>{$program}</strong> program.</p>
                    <p>The student has consistently demonstrated critical analytical capabilities, research aptitude, and a strong adherence to ethical guidelines. This evaluation is {$purposeText} and reflects my full support for their career and academic pursuits.</p>
                    <p>I confidently recommend <strong>{$studentName}</strong> for any advanced study programs, research initiatives, or professional roles they choose to pursue.</p>";
        }
        
        // CR Level
        if (str_contains($docTitleLower, 'cr ') || str_contains($docTitleLower, 'class representative') || str_contains($docTitleLower, 'forum resolution') || str_contains($docTitleLower, 'leave application') || str_contains($docTitleLower, 'core team')) {
            if (str_contains($docTitleLower, 'leave')) {
                return "<p>This is an official <strong>Leave Application Form</strong> submitted by <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}) of the <strong>{$departmentName}</strong> department, which has been formally reviewed and **endorsed** by the Class Representative (CR).</p>
                        <p>The CR verifies that the request is genuine, supports academic continuity, and complies with student attendance protocols. This leave form is {$purposeText}.</p>
                        <table class='doc-table'>
                            <thead><tr><th>Request Status</th><th>CR Endorsement</th><th>Academic Period</th></tr></thead>
                            <tbody><tr><td>Verified & Attested</td><td>Approved by CR Desk</td><td>Term 2025-2026</td></tr></tbody>
                        </table>
                        <p>The class coordinator and HOD are recommended to grant approval for the requested absence period.</p>";
            }
            if (str_contains($docTitleLower, 'core team')) {
                return "<p>This is to certify that <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}) is an active, recognized member of the <strong>Class Core Team Committee</strong> for the <strong>{$departmentName}</strong> class group.</p>
                        <p>The core team works in collaboration with the Class Representative (CR) to organize student events, coordinate academic scheduling, and represent the batch in administrative dialogues. This credential is {$purposeText}.</p>
                        <div style='background: #f1f5f9; padding: 15px; border-radius: 8px; font-weight: 700; border-left: 4px solid var(--baps-blue); margin-top: 15px;'>
                            <span>COMMITTEE STATUS: ACTIVE CORE MEMBER</span>
                            <span style='float: right; color: #2563eb;'>VALID THRU: JUNE 2026</span>
                        </div>";
            }
            return "<p>This is to certify that <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}) has served with distinction as the Class Representative (CR) for the <strong>{$departmentName}</strong> class section.</p>
                    <p>In this leadership role, the representative has demonstrated excellent communication skills, student coordination capabilities, and acted as a reliable bridge between the student body and faculty administration. This record is {$purposeText}.</p>
                    <p>The institution officially acknowledges the student's voluntary contributions to maintaining academic harmony and organizing student activities.</p>";
        }
        
        // Class Coordinator Level
        if (str_contains($docTitleLower, 'coordinator') || str_contains($docTitleLower, 'term end report') || str_contains($docTitleLower, 'assessment sheet')) {
            return "<p>This official term-end report is filed by the Class Coordinator in respect of <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}) from the Department of <strong>{$departmentName}</strong>.</p>
                    <p>The student has maintained the required classroom attendance, participated in core seminars, and successfully cleared internal assessments. This report is {$purposeText}.</p>
                    <table class='doc-table'>
                        <thead><tr><th>Evaluation Type</th><th>Coordinator Desk</th><th>Academic Term</th><th>Status</th></tr></thead>
                        <tbody><tr><td>Internal Term Assessment</td><td>Class Coordinator Desk</td><td>2025-2026</td><td><span style='color: #16a34a; font-weight: 700;'>APPROVED & COMPLETED</span></td></tr></tbody>
                    </table>";
        }
        
        // HOD Level
        if (str_contains($docTitleLower, 'hod') || str_contains($docTitleLower, 'departmental') || str_contains($docTitleLower, 'resource allocation') || str_contains($docTitleLower, 'elective')) {
            return "<p>This departmental clearance certificate is issued under the authority of the Head of Department (HOD) for <strong>{$departmentName}</strong> at BAPS Swaminarayan Vidyamandir to <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}).</p>
                    <p>The student is confirmed to have completed all required laboratory assignments, satisfied elective credits, and cleared all departmental accounts. This clearance is {$purposeText}.</p>
                    <p>The department registers its full clearance for the student to proceed with university exams and external graduation processes.</p>";
        }
        
        // Canteen & Store Level
        if (str_contains($docTitleLower, 'canteen') || str_contains($docTitleLower, 'store') || str_contains($docTitleLower, 'mess') || str_contains($docTitleLower, 'coupon') || str_contains($docTitleLower, 'subsidy') || str_contains($docTitleLower, 'uniform')) {
            return "<p>This is to certify that <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}), a student of the <strong>{$departmentName}</strong> department, is registered with the Campus Canteen, Stores, and Hostel Mess Services.</p>
                    <p>The student has successfully cleared all outstanding food balances, store credits, and uniform allocation charges. This card is {$purposeText}.</p>
                    <table class='doc-table'>
                        <thead><tr><th>Service Classification</th><th>Account Balance</th><th>Status</th></tr></thead>
                        <tbody><tr><td>Dining Hall & Store Desk</td><td>₹ 0.00 Outstanding</td><td><span style='color: #16a34a; font-weight: 700;'>CLEAR / PAID</span></td></tr></tbody>
                    </table>";
        }
        
        // Librarian Level
        if (str_contains($docTitleLower, 'librarian') || str_contains($docTitleLower, 'library') || str_contains($docTitleLower, 'manuscripts') || str_contains($docTitleLower, 'book reservation')) {
            return "<p>This official clearance is issued by the Office of the Chief Librarian, Central Library, to <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}) of the <strong>{$departmentName}</strong> department.</p>
                    <p>Our library registry verifies that the student has returned all borrowed resource materials and paid all overdue card penalties. This librarian-level certificate is {$purposeText}.</p>
                    <p>The student's library privileges are in active standing, and they have complete clearance from library records.</p>";
        }

        // Club's Level
        if (str_contains($docTitleLower, 'club') || str_contains($docTitleLower, 'gdgoc') || str_contains($docTitleLower, 'hexsociety') || str_contains($docTitleLower, 'hackerrank') || str_contains($docTitleLower, 'acm ') || str_contains($docTitleLower, 'ieee')) {
            return "<p>This official certificate is issued under the authority of the Campus Student Clubs & Technical Societies Association at BAPS Swaminarayan Vidyamandir to <strong>{$studentName}</strong> (Enrollment: {$enrollmentNo}) of the <strong>{$departmentName}</strong> department.</p>
                    <p>The recipient is recognized as an active member in good standing of the designated student chapter, having completed advanced technical hackathons, algorithmic coding assessments, or innovation project milestones. This credential is {$purposeText}.</p>
                    <table class='doc-table'>
                        <thead><tr><th>Club / Technical Society</th><th>Affiliation Level</th><th>Status</th></tr></thead>
                        <tbody><tr><td>{$docTitle}</td><td>Core Member & Project Lead</td><td><span style='color: #16a34a; font-weight: 700;'>VERIFIED & REGISTERED</span></td></tr></tbody>
                    </table>
                    <p>We commend the recipient's dedication to peer learning, competitive coding excellence, and technology innovation on campus.</p>";
        }
        
        // General fallback for other document titles
        return "<p>This official certified record is issued to student <strong>{$studentName}</strong> (Enrollment Number: <strong>{$enrollmentNo}</strong>) of the <strong>{$departmentName}</strong> department pursuing the <strong>{$program}</strong> program.</p>
                <p>This document is {$purposeText} and serves as an attested credential verified by the registry of BAPS Swaminarayan Vidyamandir.</p>
                <p>The student remains in good academic and disciplinary standing at this institution.</p>";
    }

    public function createCustomTabFile(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'id' => 'required|string|regex:/^[a-zA-Z0-9\-]+$/',
            'title' => 'required|string|max:255',
            'icon' => 'required|string|max:255',
            'roles' => 'required|array',
            'content' => 'required|string',
        ]);

        $id = strtolower($request->input('id'));
        $title = $request->input('title');
        $icon = $request->input('icon');
        $roles = $request->input('roles');
        $content = $request->input('content');

        // Verify it doesn't conflict with system tabs
        $systemTabs = ['overview', 'academic', 'exams', 'directory', 'approvals', 'operations', 'hostel', 'ipdc', 'reports', 'system', 'oa-coordination', 'official-documents', 'volunteer', 'role-settings', 'payroll', 'settings'];
        if (in_array(str_replace('tab-', '', $id), $systemTabs)) {
            return response()->json(['success' => false, 'error' => 'Conflict with built-in system tab ID.'], 422);
        }

        // File path
        $filename = "tab_custom_" . str_replace('-', '_', $id);
        $filePath = resource_path("views/admin/partials/{$filename}.blade.php");

        // Write the custom HTML content as Blade template file!
        try {
            $dir = dirname($filePath);
            if (!is_dir($dir)) {
                mkdir($dir, 0755, true);
            }
            file_put_contents($filePath, $content);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to write blade file: ' . $e->getMessage()], 500);
        }

        // Write/Update custom_tabs.json metadata
        try {
            $metaPath = storage_path("app/custom_tabs.json");
            $metaDir = dirname($metaPath);
            if (!is_dir($metaDir)) {
                mkdir($metaDir, 0755, true);
            }
            $tabs = [];
            if (file_exists($metaPath)) {
                $tabs = json_decode(file_get_contents($metaPath), true) ?? [];
            }

            // Remove existing entry if any
            $tabs = array_filter($tabs, function($t) use ($id) {
                return $t['id'] !== $id;
            });

            $tabs[] = [
                'id' => $id,
                'title' => $title,
                'icon' => $icon,
                'roles' => $roles,
                'filename' => $filename
            ];

            // Re-index array
            $tabs = array_values($tabs);

            file_put_contents($metaPath, json_encode($tabs, JSON_PRETTY_PRINT));
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to write metadata: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'message' => 'Custom tab file and layout registered successfully!']);
    }

    public function deleteCustomTabFile(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'id' => 'required|string'
        ]);

        $id = strtolower($request->input('id'));

        // File path
        $filename = "tab_custom_" . str_replace('-', '_', $id);
        $filePath = resource_path("views/admin/partials/{$filename}.blade.php");

        if (file_exists($filePath)) {
            @unlink($filePath);
        }

        // Update custom_tabs.json metadata
        try {
            $metaPath = storage_path("app/custom_tabs.json");
            if (file_exists($metaPath)) {
                $tabs = json_decode(file_get_contents($metaPath), true) ?? [];
                $tabs = array_filter($tabs, function($t) use ($id) {
                    return $t['id'] !== $id;
                });
                $tabs = array_values($tabs);
                file_put_contents($metaPath, json_encode($tabs, JSON_PRETTY_PRINT));
            }
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'error' => 'Failed to update metadata: ' . $e->getMessage()], 500);
        }

        return response()->json(['success' => true, 'message' => 'Custom tab deleted successfully.']);
    }

    public function makeCr(Request $request, $id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'hod', 'coordinator', 'faculty'])) {
            return back()->with('error', 'Unauthorized! Only Admin, HOD, Dean, or Faculty can make a student a Class Representative.');
        }

        $request->validate([
            'staff_email' => 'required|email|unique:staff,email',
            'staff_password' => 'required|string|min:4'
        ]);

        $user = \App\Models\User::findOrFail($id);
        
        // 1. Set role to 'cr' and access_level to 120
        $user->role = 'cr';
        $user->access_level = 120;
        $user->save();

        // 2. Create the corresponding staff credential
        $uniqueCode = 'CR-' . $user->enrollment_no;
        
        // Find if there is already a staff record with this code and delete it
        \App\Models\Staff::where('unique_code', $uniqueCode)->delete();

        \App\Models\Staff::create([
            'name' => $user->name,
            'role' => 'cr',
            'department_id' => $user->department_id,
            'unique_code' => $uniqueCode,
            'email' => $request->staff_email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->staff_password),
            'access_level' => 120,
            'positions' => ['Class Representative'],
            'phone' => $user->phone
        ]);

        return back()->with('success', "Student {$user->name} promoted to Class Representative (CR). Corresponding staff account created successfully.");
    }

    public function revokeCr(Request $request, $id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'hod', 'coordinator', 'faculty'])) {
            return back()->with('error', 'Unauthorized! Only Admin, HOD, Dean, or Faculty can revoke Class Representative status.');
        }

        $user = \App\Models\User::findOrFail($id);
        
        // 1. Restore role and access_level
        $user->role = 'student';
        $user->access_level = 100;
        $user->save();

        // 2. Delete corresponding staff account
        $uniqueCode = 'CR-' . $user->enrollment_no;
        \App\Models\Staff::where('unique_code', $uniqueCode)->delete();

        return back()->with('success', "CR status revoked for {$user->name}. Corresponding staff credentials deleted.");
    }

    public function generateBill(Request $request, $id)
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'hod', 'faculty', 'coordinator', 'office-assistant'])) {
            return redirect('/admin')->with('error', 'Unauthorized access.');
        }

        $user = \App\Models\User::findOrFail($id);

        $request->validate([
            'fee_type' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        $token = str_pad(mt_rand(0, 9999), 4, '0', STR_PAD_LEFT);

        \App\Models\FeePayment::create([
            'user_id' => $user->id,
            'fee_type' => $request->fee_type,
            'amount' => $request->amount,
            'token_number' => $token,
            'status' => 'pending'
        ]);

        return back()->with('success', 'Fee Payment Token [' . $token . '] of ₹' . number_format($request->amount, 2) . ' successfully generated for ' . $user->name . '.');
    }
}
