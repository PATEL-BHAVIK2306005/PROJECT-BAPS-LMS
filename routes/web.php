<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\ProgressController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\CodeExecutionController;
use App\Http\Controllers\PersonalNoteController;

use App\Http\Controllers\CourseRatingController;

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ServiceController;

// Public Authentication
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword']);
Route::post('/forgot-password', [AuthController::class, 'submitForgotPassword']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/parent/register', [AuthController::class, 'showParentRegister']);
Route::post('/parent/register', [AuthController::class, 'parentRegister']);
Route::post('/track-application', [AuthController::class, 'trackApplication']);
Route::post('/track-application/submit-tc', [AuthController::class, 'submitTc']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Public Student Routes
Route::get('/', [CourseController::class, 'index']);
Route::get('/dashboard', [CourseController::class, 'dashboard'])->name('dashboard');
Route::post('/dashboard/assign-deputy-cr', [CourseController::class, 'assignDeputyCr']);
Route::post('/dashboard/revoke-deputy-cr', [CourseController::class, 'revokeDeputyCr']);
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);
Route::post('/courses/{course}/rate', [CourseRatingController::class, 'store']);
Route::post('/courses/task/{taskId}/submit', [CourseController::class, 'submitTask']);
Route::get('/courses/{course}/quiz/{quiz}', [CourseController::class, 'takeQuiz']);
Route::post('/courses/{course}/quiz/{quiz}', [CourseController::class, 'submitQuiz']);
Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll']);
Route::get('/profile', [ProfileController::class, 'index']);
Route::post('/profile/unlock', [ProfileController::class, 'unlock']);
Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto']);
Route::get('/profile/photo/{type}/{id}', [ProfileController::class, 'servePhoto']);
Route::get('/portfolio/{enrollment}', [ProfileController::class, 'publicPortfolio']);
Route::get('/workspace', function() {
    return view('student.workspace');
})->middleware('webcontainer');

Route::post('/api/execute-code', [CodeExecutionController::class, 'execute']);

Route::post('/personal-notes', [PersonalNoteController::class, 'store'])->name('personal-notes.store');
Route::delete('/personal-notes/{id}', [PersonalNoteController::class, 'destroy'])->name('personal-notes.destroy');

// Cloud File Serving Endpoints
Route::get('/cloud-file/asset/{id}', [\App\Http\Controllers\IpdcController::class, 'serveAsset']);
Route::get('/cloud-file/cert/{id}', [\App\Http\Controllers\IpdcController::class, 'serveCert']);
Route::get('/cloud-file/submission/{id}', [\App\Http\Controllers\IpdcController::class, 'serveSubmission']);
// Institutional Services & My Hub
Route::get('/hub', [ServiceController::class, 'hubPage']);
Route::post('/hub/gatepass', [ServiceController::class, 'submitGatepass']);
Route::post('/hub/leave', [ServiceController::class, 'submitLeave']);
Route::post('/hub/fee-token', [ServiceController::class, 'requestFeeToken']);
Route::get('/timetables', [\App\Http\Controllers\TimetableController::class, 'studentIndex']);
Route::get('/timetables/{id}', [\App\Http\Controllers\TimetableController::class, 'show']);
Route::post('/enroll/submit', [EnrollmentController::class, 'store']); // Submits 8 fields
Route::post('/enroll/{courseId}', [EnrollmentController::class, 'enrollForm']); // Redirects to form

// Student Synergy Circle Routes
Route::get('/synergy-circle', [\App\Http\Controllers\SynergyCircleController::class, 'studentIndex']);
Route::post('/synergy-circle/request', [\App\Http\Controllers\SynergyCircleController::class, 'storeRequest']);
Route::post('/synergy-circle/apply-privilege', [\App\Http\Controllers\SynergyCircleController::class, 'applyPrivilege']);

// User Manual Route
Route::get('/user-manual', function() {
    return view('user_manual');
});

// Student Exam Form & Admit Card Routes
Route::get('/exam/admit-card', [\App\Http\Controllers\ExamController::class, 'studentAdmitCard']);
Route::post('/exam/form/submit', [\App\Http\Controllers\ExamController::class, 'submitExamForm']);
Route::post('/exam/re-check', [\App\Http\Controllers\ExamController::class, 'reCheckRequest']);
Route::post('/exam/duplicate', [\App\Http\Controllers\ExamController::class, 'duplicateRequest']);
Route::get('/exam/results', [\App\Http\Controllers\ExamController::class, 'studentResults'])->name('exam.results');
Route::get('/exam/excellence-cert', [\App\Http\Controllers\ExamController::class, 'studentExcellenceCert'])->name('exam.excellence-cert');

// Admin Exam Form & Admit Card Routes
Route::get('/admin/exam/forms', [\App\Http\Controllers\ExamController::class, 'adminForms']);
Route::post('/admin/exam/forms/submit', [\App\Http\Controllers\ExamController::class, 'adminSubmitForm']);
Route::post('/admin/exam/forms/{id}/publish', [\App\Http\Controllers\ExamController::class, 'publishAdmitCard']);
Route::get('/admin/exam/admit-card/{userId}', [\App\Http\Controllers\ExamController::class, 'adminViewAdmitCard']);

// Admin / CR / CC Chat Routes
Route::get('/admin/chat', [\App\Http\Controllers\ChatController::class, 'index']);
Route::post('/admin/chat/send', [\App\Http\Controllers\ChatController::class, 'store']);
Route::post('/progress', [ProgressController::class, 'update']);
Route::post('/favorites/toggle', [CourseController::class, 'toggleFavorite']);
Route::get('/certificate/{courseId}', function($courseId) {
    if (!auth()->check() && !session('demo_user_id')) {
        return redirect('/login')->with('error', 'Authentication required to view certificate.');
    }
    
    $uid = session('demo_user_id') ?? auth()->id() ?? 1;
    $user = \App\Models\User::find($uid);
    $course = \App\Models\Course::with(['tasks', 'quizzes'])->findOrFail($courseId);
    
    $certificate = \App\Models\Certificate::where('user_id', $uid)->where('course_id', $courseId)->first();
    
    if (!$certificate) {
        return redirect('/courses/'.$courseId)->with('error', 'Certificate not yet unlocked for this course.');
    }

    $taskSubmissions = \Illuminate\Support\Facades\DB::table('task_submissions')
        ->whereIn('task_id', $course->tasks->pluck('id'))->where('user_id', $uid)->get();
        
    $quizAttempts = \App\Models\QuizAttempt::whereIn('quiz_id', $course->quizzes->pluck('id'))
        ->where('user_id', $uid)->get();

    return view('student.certificate', compact('course', 'user', 'certificate', 'taskSubmissions', 'quizAttempts'));
});

Route::get('/certificate/{courseId}/preview', function($courseId) {
    if (!auth()->check() && !session('demo_user_id')) {
        return redirect('/login')->with('error', 'Authentication required.');
    }
    
    $uid = session('demo_user_id') ?? auth()->id() ?? 1;
    $user = \App\Models\User::find($uid);
    $course = \App\Models\Course::with(['tasks', 'quizzes'])->findOrFail($courseId);
    $certificate = \App\Models\Certificate::where('user_id', $uid)->where('course_id', $courseId)->first();
    
    if (!$certificate) return redirect('/courses/'.$courseId);

    $taskSubmissions = \Illuminate\Support\Facades\DB::table('task_submissions')
        ->whereIn('task_id', $course->tasks->pluck('id'))->where('user_id', $uid)->get();
        
    $quizAttempts = \App\Models\QuizAttempt::whereIn('quiz_id', $course->quizzes->pluck('id'))
        ->where('user_id', $uid)->get();

    return view('student.preview_document', compact('course', 'user', 'certificate', 'taskSubmissions', 'quizAttempts'));
});

// Admin Password// Login routes
Route::get('/admin/login', [AdminController::class, 'loginPage'])->name('admin.login');
Route::post('/admin/login', [AdminController::class, 'loginSubmit']);
Route::get('/admin/secure-verify', [AdminController::class, 'secureVerifyPage']);
Route::post('/admin/secure-verify', [AdminController::class, 'secureVerifySubmit']);
Route::post('/admin/send-otp', [AdminController::class, 'sendOtp']);
Route::post('/admin/log-emailjs-status', function (\Illuminate\Http\Request $request) {
    $type = $request->input('type');
    if ($type === 'emailjs_success') {
        \Illuminate\Support\Facades\Log::info("Client-side EmailJS Success: " . json_encode($request->all(), JSON_PRETTY_PRINT));
    } else {
        \Illuminate\Support\Facades\Log::error("Client-side EmailJS Error: " . json_encode($request->all(), JSON_PRETTY_PRINT));
    }
    return response()->json(['success' => true]);
});

// Admin Logout
Route::match(['get', 'post'], '/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

// Protected Admin/Instructor/Moderator Routes
Route::middleware([\App\Http\Middleware\RoleMiddleware::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard']);
    Route::get('/admin/reports', [AdminController::class, 'reportsSection']);
    Route::post('/admin/config/module-access', [AdminController::class, 'updateModuleAccess']);

    // Timetables accessible by Faculty and above (including CR)
    Route::middleware([RoleMiddleware::class.':faculty,cr'])->group(function() {
        Route::get('/admin/timetables', [\App\Http\Controllers\TimetableController::class, 'index']);
        Route::get('/admin/timetables/{id}/faculty-view', [\App\Http\Controllers\TimetableController::class, 'facultyShow']);
    });

    // Timetable building and Staff Directory accessible by Dean and CR (and above)
    Route::middleware([RoleMiddleware::class.':dean,cr'])->group(function() {
        Route::post('/admin/timetables', [\App\Http\Controllers\TimetableController::class, 'store']);
        Route::get('/admin/timetables/build', [\App\Http\Controllers\TimetableController::class, 'buildManual']);
        Route::post('/admin/timetables/build', [\App\Http\Controllers\TimetableController::class, 'storeManual']);
        Route::post('/admin/timetables/generate-ai', [\App\Http\Controllers\TimetableController::class, 'generateAi']);
        Route::get('/admin/timetables/{id}/edit', [\App\Http\Controllers\TimetableController::class, 'editManual']);
        Route::post('/admin/timetables/{id}/update', [\App\Http\Controllers\TimetableController::class, 'updateManual']);
        Route::get('/admin/staff', [AdminController::class, 'manageStaff']);
        Route::get('/admin/staff/download-latest-pdf', [AdminController::class, 'downloadLatestStaffPdf']);
        Route::get('/admin/departments', [AdminController::class, 'manageDepartments']);
        Route::get('/admin/parents', [AdminController::class, 'manageParents']);
        Route::post('/admin/parents', [AdminController::class, 'storeParent']);
        Route::post('/admin/parents/{id}/update', [AdminController::class, 'updateParentIdentity']);
        Route::post('/admin/parents/{id}/password', [AdminController::class, 'updateParentPassword']);
        Route::post('/admin/parents/{id}/delete', [AdminController::class, 'deleteParent']);
    });

    // Admin & Dean can manage departments and staff write operations
    Route::middleware([RoleMiddleware::class.':dean'])->group(function() {
        Route::get('/admin/master-data', [AdminController::class, 'masterData']);
        Route::post('/admin/master-data/unlock', [AdminController::class, 'unlockMasterData']);
        Route::get('/admin/master-data/records/{model}', [AdminController::class, 'getModelRecords']);
        Route::get('/admin/master-data/system-status', [AdminController::class, 'getSystemStatus']);
        Route::get('/admin/master-data/schema/{table}', [AdminController::class, 'getTableSchema']);
        Route::post('/admin/master-data/inject', [AdminController::class, 'injectMasterData']);
        Route::post('/admin/master-data/switch-db', [AdminController::class, 'switchDatabaseState']);
        Route::post('/admin/master-data/update-db-config', [AdminController::class, 'updateDatabaseConfig']);


        Route::get('/admin/add-function-module', [AdminController::class, 'addFunctionModule']);
        Route::post('/admin/add-function-module/unlock', [AdminController::class, 'unlockAddFunctionModule']);
        Route::post('/admin/departments', [AdminController::class, 'storeDepartment']);
        Route::post('/admin/departments/assign-hod', [AdminController::class, 'assignHodToDepartment']);
        Route::post('/admin/departments/{id}/update', [AdminController::class, 'updateDepartment']);
        Route::get('/admin/departments/{id}/update', function() {
            return redirect('/admin/departments');
        });
        Route::post('/admin/departments/{id}/delete', [AdminController::class, 'deleteDepartment']);
        Route::get('/admin/departments/{id}/delete', function() {
            return redirect('/admin/departments');
        });
        Route::post('/admin/staff', [AdminController::class, 'storeStaff']);
        Route::post('/admin/super-admins/promote', [AdminController::class, 'promoteToSuperAdmin']);
        Route::post('/admin/super-admins/{id}/demote', [AdminController::class, 'demoteFromSuperAdmin']);
        Route::post('/admin/staff/{id}/update', [AdminController::class, 'updateStaff']);
        Route::get('/admin/staff/{id}/update', function() {
            return redirect('/admin/departments');
        });
        Route::post('/admin/staff/reset-staff-password', [AdminController::class, 'resetStaffPassword']);
        Route::post('/admin/staff/{id}/delete', [AdminController::class, 'deleteStaff']);
        Route::get('/admin/staff/{id}/delete', function() {
            return redirect('/admin/departments');
        });
        Route::post('/admin/staff/allocate', [AdminController::class, 'allocateCourseToStaff']);
        Route::post('/admin/staff/bulk-delete', [AdminController::class, 'bulkDeleteStaff']);
        Route::post('/admin/staff/bulk-enroll', [AdminController::class, 'bulkEnrollStaff']);
        Route::post('/admin/custom-tabs/create', [AdminController::class, 'createCustomTabFile']);
        Route::post('/admin/custom-tabs/delete', [AdminController::class, 'deleteCustomTabFile']);
    });

    // ── Maintenance Mode (Strictly Admin & Dean/Provost) ───────────────────────────
    Route::middleware([RoleMiddleware::class.':admin,dean'])->group(function() {
        Route::post('/admin/maintenance/toggle', [AdminController::class, 'toggleMaintenance']);
        Route::get('/admin/maintenance/status',  [AdminController::class, 'maintenanceStatus']);
        Route::post('/admin/maintenance/run-task', [AdminController::class, 'runMaintenanceTask']);
    });

    // Admin, Dean & HOD can manage students
    Route::middleware([RoleMiddleware::class.':hod'])->group(function() {
        Route::get('/admin/enrollments', [AdminController::class, 'enrollments']);
    });

    // Bulk Enrollment & Student Management accessible by Admin, CR, Rajunakum Sir (Staff)
    Route::middleware([RoleMiddleware::class.':faculty,cr'])->group(function() {
        Route::get('/admin/bulk-enroll', [AdminController::class, 'bulkEnrollPage']);
        Route::post('/admin/bulk-enroll', [AdminController::class, 'storeBulkEnroll']);
        Route::get('/admin/students', [AdminController::class, 'manageStudents']);
        Route::get('/admin/students/download-pdf', [AdminController::class, 'downloadAllStudentsPdf']);
        Route::post('/admin/students', [AdminController::class, 'storeStudent']);
        Route::post('/admin/students/{id}/password', [AdminController::class, 'updateStudentPassword']);
        Route::post('/admin/students/{id}/update', [AdminController::class, 'updateStudentIdentity']);
        Route::post('/admin/students/{id}/delete', [AdminController::class, 'deleteStudent']);
        Route::post('/admin/students/{id}/suspend', [AdminController::class, 'toggleSuspension']);
        Route::post('/admin/students/{id}/make-cr', [AdminController::class, 'makeCr']);
        Route::post('/admin/students/{id}/revoke-cr', [AdminController::class, 'revokeCr']);
        Route::post('/admin/students/{id}/generate-bill', [AdminController::class, 'generateBill']);
    });

    // Admin, Dean & Faculty can manage content (CR can view dashboard)
    Route::middleware([RoleMiddleware::class.':faculty,cr'])->group(function() {
        Route::get('/admin/attendance', [\App\Http\Controllers\AttendanceController::class, 'index']);
        Route::post('/admin/attendance', [\App\Http\Controllers\AttendanceController::class, 'store']);
        Route::get('/admin', [AdminController::class, 'dashboard']);
        Route::post('/admin/course', [AdminController::class, 'storeCourse']);
        Route::post('/admin/course/{id}', [AdminController::class, 'updateCourse']);
        Route::post('/admin/course/{id}/request-approval', [AdminController::class, 'requestCourseApproval']);
        Route::post('/admin/lesson', [AdminController::class, 'storeLesson']);
        Route::get('/admin/talent-hub', [AdminController::class, 'talentHub']);
        Route::get('/admin/course-management', [AdminController::class, 'courseManagement']);
        Route::post('/admin/course-management/{id}/allocate-faculty', [AdminController::class, 'allocateFaculty']);
        Route::post('/admin/course-management/{id}/update', [AdminController::class, 'updateCourseManagement']);
        Route::post('/admin/course-management/{id}/stop-meet', [AdminController::class, 'stopMeeting']);
        Route::post('/admin/task', [AdminController::class, 'storeTask']);
        Route::post('/admin/quiz', [AdminController::class, 'storeQuiz']);
        Route::get('/admin/quiz/{id}/builder', [AdminController::class, 'quizBuilder']);
        Route::post('/admin/quiz/{id}/questions', [AdminController::class, 'storeQuizQuestion']);
        Route::post('/admin/quiz/{id}/questions/pdf', [AdminController::class, 'storeQuizQuestionFromPdf']);
        Route::post('/admin/quiz/{id}/questions/{questionId}/update', [AdminController::class, 'updateQuizQuestion']);
        Route::post('/admin/quiz/{id}/questions/{questionId}/delete', [AdminController::class, 'deleteQuizQuestion']);
        Route::post('/admin/quiz/{id}/toggle-publish', [AdminController::class, 'toggleQuizPublish']);
        Route::post('/admin/question-bank', [AdminController::class, 'storeBankQuestion']);
        Route::post('/admin/announcement', [AdminController::class, 'storeAnnouncement']);

        // Exam Center Routes
        Route::get('/admin/exam/quiz-management', [AdminController::class, 'quizManagement']);
        Route::get('/admin/exam/schedule', [AdminController::class, 'examSchedule']);
        Route::post('/admin/exam/schedule', [AdminController::class, 'storeExamSchedule']);
        Route::get('/admin/exam/seating', [AdminController::class, 'seatingArrangement']);
        Route::post('/admin/exam/seating', [AdminController::class, 'storeSeatingArrangement']);
        Route::get('/admin/exam/sign-sheet', [AdminController::class, 'classSignSheet']);
        Route::get('/admin/exam/question-bank', [AdminController::class, 'questionBank']);
        Route::get('/admin/exam/live-proctoring', [AdminController::class, 'liveProctoring']);
        Route::get('/admin/exam/results-grading', [AdminController::class, 'resultsGrading']);
        Route::post('/admin/exam/results', [AdminController::class, 'storeResult']);
        Route::post('/admin/exam/results/batch', [AdminController::class, 'storeBatchResult']);
        Route::get('/admin/exam/results/{id}/print', [AdminController::class, 'printResult']);
        Route::post('/admin/exam/results/{id}/update', [AdminController::class, 'updateResult']);
        Route::get('/admin/exam/results/student/{studentId}/print-gradesheet', [AdminController::class, 'printStudentGradeSheet']);
        Route::get('/admin/exam/results/student/{id}/excellence-cert', [AdminController::class, 'printExcellenceCertificate']);
        Route::get('/admin/exam/results/{studentId}/enrollments', [AdminController::class, 'getStudentEnrollments']);
        Route::get('/admin/exam/results/course/{courseId}/students', [AdminController::class, 'getCourseStudents']);
        Route::get('/admin/exam/seating/{id}/print', [AdminController::class, 'printSeating']);
        Route::get('/admin/students/{id}/progress', [AdminController::class, 'studentProgress']);
        Route::post('/admin/students/{id}/verify', [AdminController::class, 'toggleVerification']);
        Route::post('/admin/students/{id}/badge', [AdminController::class, 'grantBadge']);

        // Master Data Visor and Add Function Module are secured under the Dean/Admin middleware group above

        // IPDC Management Routes
        Route::get('/admin/ipdc', [\App\Http\Controllers\IpdcController::class, 'index']);
        Route::get('/admin/ipdc/logs', [\App\Http\Controllers\IpdcController::class, 'manageLogs']);
        Route::get('/admin/ipdc/certs', [\App\Http\Controllers\IpdcController::class, 'manageCerts']);
        Route::get('/admin/ipdc/download-cert/{name}', [\App\Http\Controllers\IpdcController::class, 'downloadCertificate']);
        Route::get('/admin/ipdc/download-transcript/{name}', [\App\Http\Controllers\IpdcController::class, 'downloadTranscript']);
        Route::post('/admin/ipdc/module', [\App\Http\Controllers\IpdcController::class, 'storeModule']);
        Route::post('/admin/ipdc/subject', [\App\Http\Controllers\IpdcController::class, 'storeSubject']);
        Route::post('/admin/ipdc/approve-seva/{id}', [\App\Http\Controllers\IpdcController::class, 'approveSeva']);
        
        // NEW IPDC ROUTES
        Route::post('/admin/ipdc/upload-asset', [\App\Http\Controllers\IpdcController::class, 'uploadAsset']);
        Route::post('/admin/ipdc/verify-cert/{id}', [\App\Http\Controllers\IpdcController::class, 'verifyCert']);
        Route::post('/admin/ipdc/grade-submission/{id}', [\App\Http\Controllers\IpdcController::class, 'gradeSubmission']);
        Route::post('/admin/ipdc/add-cert', [\App\Http\Controllers\IpdcController::class, 'adminAddCert']);
        Route::post('/admin/ipdc/update-transcript/{courseId}', [\App\Http\Controllers\IpdcController::class, 'updateTranscript']);
        Route::post('/admin/ipdc/convert-to-assignment/{moduleId}', [\App\Http\Controllers\IpdcController::class, 'convertToAssignment']);
        Route::post('/admin/ipdc/delete-task/{id}', [\App\Http\Controllers\IpdcController::class, 'deleteTask']);
        Route::post('/admin/ipdc/generate-assignment-ai', [\App\Http\Controllers\IpdcController::class, 'generateAssignmentAi']);

        // Placements & Talent
        Route::get('/admin/talent-hub', [AdminController::class, 'talentHub']);

        // Placement Dean routes
        Route::get('/admin/placement', [\App\Http\Controllers\PlacementController::class, 'index']);
        Route::post('/admin/placement/drives', [\App\Http\Controllers\PlacementController::class, 'storeDrive']);

        // HackerRank IPDC Practice routes
        Route::get('/admin/ipdc/hackerrank/create', [\App\Http\Controllers\IpdcHackerrankController::class, 'createProblem']);
        Route::post('/admin/ipdc/hackerrank/store', [\App\Http\Controllers\IpdcHackerrankController::class, 'storeProblem']);
    });

    // STUDENT IPDC ROUTES
    Route::get('/ipdc/vault', [\App\Http\Controllers\IpdcController::class, 'studentVault']);
    Route::post('/ipdc/submit-cert', [\App\Http\Controllers\IpdcController::class, 'submitCert']);
    Route::get('/ipdc/assignment/{id}', [\App\Http\Controllers\IpdcController::class, 'showAssignment']);
    Route::post('/ipdc/submit-task/{id}', [\App\Http\Controllers\IpdcController::class, 'submitTask']);
    Route::get('/ipdc/evaluation-pdf/{id}', [\App\Http\Controllers\IpdcController::class, 'evaluationPdf']);
    Route::get('/ipdc/practice/{id}', [\App\Http\Controllers\IpdcHackerrankController::class, 'showProblem']);
    Route::post('/api/ipdc/hackerrank/run/{id}', [\App\Http\Controllers\IpdcHackerrankController::class, 'runCode']);
    Route::post('/api/ipdc/hackerrank/submit/{id}', [\App\Http\Controllers\IpdcHackerrankController::class, 'submitCode']);

    // Admin, Dean, HOD, CR & Moderator can see enrollments
    Route::middleware([RoleMiddleware::class.':moderator,cr'])->group(function() {
        Route::get('/admin/enrollments', [AdminController::class, 'enrollments']);
        Route::get('/admin/demo-student/{id}', [AdminController::class, 'enterDemoMode']);
        Route::get('/admin/exit-demo', [AdminController::class, 'exitDemoMode']);
        Route::get('/admin/demo-dean', function() {
            session([
                'user_role' => 'dean',
                'staff_id' => 888,
                'staff_name' => 'Dr. Sadhu Gyaneswar Das (Dean)',
                'dept_id' => null
            ]);
            return redirect('/admin/placement')->with('success', 'Logged in as Dean (Demo Mode)');
        });
        Route::post('/admin/issue-certificate/{id}', [AdminController::class, 'issueCertificate']);
        Route::post('/admin/issue-certificates-bulk', [AdminController::class, 'issueCertificatesBulk']);
    });

    // Multi-Tier Pending Application Evaluations
    Route::middleware([RoleMiddleware::class.':dean,hod,cr'])->group(function() {
        Route::get('/admin/approvals', [AdminController::class, 'approvalsList']);
        Route::post('/admin/approvals/{modelType}/{id}/process', [AdminController::class, 'processStatus']);
    });

    Route::get('/certificate/view/{code}', [AdminController::class, 'viewCertificate']);
    Route::get('/certificate/download/{code}', [AdminController::class, 'downloadCertificate']);
    Route::get('/admin/certificate/preview/{code}', [AdminController::class, 'previewCertificate']);
    Route::get('/admin/profile', [AdminController::class, 'profile']);
    Route::get('/document/official/{docTitle}', [AdminController::class, 'generateOfficialDocument']);

    // --- Circulars and Official Works Routes ---
    Route::get('/circulars-notices', [\App\Http\Controllers\CircularNotificationController::class, 'studentIndex']);
    Route::get('/circulars/{id}/download', [\App\Http\Controllers\CircularNotificationController::class, 'downloadCircularPdf']);
    Route::get('/circulars/{id}/view', [\App\Http\Controllers\CircularNotificationController::class, 'viewCircularPdf']);
    Route::post('/admin/circulars/store', [\App\Http\Controllers\CircularNotificationController::class, 'storeCircular']);
    Route::post('/admin/notifications/store', [\App\Http\Controllers\CircularNotificationController::class, 'storeNotification']);

    // --- Synergy Circle Admin/Mentor Routes ---
    Route::post('/admin/synergy-circle/feedback/{requestId}', [\App\Http\Controllers\SynergyCircleController::class, 'storeFeedback']);
    Route::post('/admin/synergy-circle/privilege/{applicationId}/process', [\App\Http\Controllers\SynergyCircleController::class, 'processPrivilege']);

    // --- Student Queries Routes ---
    Route::post('/student-queries/store', [\App\Http\Controllers\StudentQueryController::class, 'store']);
    Route::post('/admin/student-queries/update-status', [\App\Http\Controllers\StudentQueryController::class, 'updateStatus']);
    Route::post('/student-queries/{id}/resolve', [\App\Http\Controllers\StudentQueryController::class, 'resolve']);
    Route::post('/admin/student-queries/{id}/waive-reduce', [\App\Http\Controllers\StudentQueryController::class, 'waiveOrReduce']);

    // --- Student Time Capsule Routes ---
    Route::get('/time-capsule', [\App\Http\Controllers\TimeCapsuleController::class, 'index']);
    Route::post('/time-capsule/store', [\App\Http\Controllers\TimeCapsuleController::class, 'store']);
    Route::post('/time-capsule/{id}/unlock', [\App\Http\Controllers\TimeCapsuleController::class, 'unlock']);
    Route::delete('/time-capsule/{id}', [\App\Http\Controllers\TimeCapsuleController::class, 'destroy']);

    // --- PTM & Parent Portal Routes ---
    Route::post('/admin/ptm/report', [\App\Http\Controllers\ParentController::class, 'submitPtmReport']);

    Route::middleware([RoleMiddleware::class.':parent'])->group(function() {
        Route::get('/parent/dashboard', [\App\Http\Controllers\ParentController::class, 'dashboard']);
        Route::post('/parent/ptm/{id}/reply', [\App\Http\Controllers\ParentController::class, 'submitReply']);
        Route::post('/parent/gatepass', [\App\Http\Controllers\ParentController::class, 'submitGatepass']);
        Route::post('/parent/leave', [\App\Http\Controllers\ParentController::class, 'submitLeave']);
        Route::post('/parent/query', [\App\Http\Controllers\ParentController::class, 'submitQuery']);
    });
});

Route::get('/storage/{path}', [App\Http\Controllers\FileController::class, 'serve'])->where('path', '.*');

if (app()->environment('testing')) {
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
}
