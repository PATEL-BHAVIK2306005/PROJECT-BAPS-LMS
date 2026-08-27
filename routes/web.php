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
use App\Http\Controllers\TimetableController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\IpdcController;
use App\Http\Controllers\IpdcHackerrankController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\PlacementController;
use App\Http\Controllers\CircularNotificationController;
use App\Http\Controllers\SynergyCircleController;
use App\Http\Controllers\StudentQueryController;
use App\Http\Controllers\TimeCapsuleController;
use App\Http\Controllers\ParentController;
use App\Http\Controllers\FileController;

/*
|--------------------------------------------------------------------------
| Public Authentication Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'submitForgotPassword'])->name('password.email');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/parent/register', [AuthController::class, 'showParentRegister'])->name('parent.register');
Route::post('/parent/register', [AuthController::class, 'parentRegister']);
Route::post('/track-application', [AuthController::class, 'trackApplication']);
Route::post('/track-application/submit-tc', [AuthController::class, 'submitTc']);

Route::get('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    session()->flush();
    return redirect('/admin/login')->with('success', 'You have been successfully logged out.');
});

Route::post('/logout', function () {
    \Illuminate\Support\Facades\Auth::logout();
    session()->invalidate();
    session()->regenerateToken();
    return redirect('/');
})->name('logout');

/*
|--------------------------------------------------------------------------
| Public & Student Academic Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [CourseController::class, 'index'])->name('home');
Route::get('/dashboard', [CourseController::class, 'dashboard'])->name('dashboard');
Route::post('/dashboard/assign-deputy-cr', [CourseController::class, 'assignDeputyCr']);
Route::post('/dashboard/revoke-deputy-cr', [CourseController::class, 'revokeDeputyCr']);
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
Route::post('/courses/{course}/rate', [CourseRatingController::class, 'store']);
Route::post('/courses/task/{taskId}/submit', [CourseController::class, 'submitTask']);
Route::get('/courses/{course}/quiz/{quiz}', [CourseController::class, 'takeQuiz']);
Route::post('/courses/{course}/quiz/{quiz}', [CourseController::class, 'submitQuiz']);
Route::post('/courses/{course}/enroll', [CourseController::class, 'enroll']);

Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/unlock', [ProfileController::class, 'unlock']);
Route::post('/profile/update', [ProfileController::class, 'updateProfile']);
Route::post('/profile/upload-photo', [ProfileController::class, 'uploadPhoto']);
Route::get('/profile/photo/{type}/{id}', [ProfileController::class, 'servePhoto']);
Route::get('/portfolio/{enrollment}', [ProfileController::class, 'publicPortfolio']);

Route::get('/workspace', function () {
    return view('student.workspace');
})->middleware('webcontainer');

Route::post('/api/execute-code', [CodeExecutionController::class, 'execute']);
Route::post('/personal-notes', [PersonalNoteController::class, 'store'])->name('personal-notes.store');
Route::delete('/personal-notes/{id}', [PersonalNoteController::class, 'destroy'])->name('personal-notes.destroy');
Route::post('/progress', [ProgressController::class, 'update']);
Route::post('/favorites/toggle', [CourseController::class, 'toggleFavorite']);

// Cloud File Serving Endpoints
Route::get('/cloud-file/asset/{id}', [IpdcController::class, 'serveAsset']);
Route::get('/cloud-file/cert/{id}', [IpdcController::class, 'serveCert']);
Route::get('/cloud-file/submission/{id}', [IpdcController::class, 'serveSubmission']);

// Institutional Services & My Hub
Route::get('/hub', [ServiceController::class, 'hubPage'])->name('hub');
Route::post('/hub/gatepass', [ServiceController::class, 'submitGatepass']);
Route::post('/hub/leave', [ServiceController::class, 'submitLeave']);
Route::post('/hub/fee-token', [ServiceController::class, 'requestFeeToken']);
Route::get('/timetables', [TimetableController::class, 'studentIndex'])->name('timetables.student');
Route::get('/timetables/{id}', [TimetableController::class, 'show'])->name('timetables.show');
Route::post('/enroll/submit', [EnrollmentController::class, 'store']);
Route::post('/enroll/{courseId}', [EnrollmentController::class, 'enrollForm']);

// User Manual Route
Route::get('/user-manual', function () {
    return view('user_manual');
})->name('user-manual');

// Student Synergy Circle Routes
Route::get('/synergy-circle', [SynergyCircleController::class, 'studentIndex'])->name('synergy-circle');
Route::post('/synergy-circle/request', [SynergyCircleController::class, 'storeRequest']);
Route::post('/synergy-circle/apply-privilege', [SynergyCircleController::class, 'applyPrivilege']);

// Student Exam Form & Admit Card Routes
Route::get('/exam/admit-card', [ExamController::class, 'studentAdmitCard'])->name('exam.admit-card');
Route::post('/exam/form/submit', [ExamController::class, 'submitExamForm']);
Route::post('/exam/re-check', [ExamController::class, 'reCheckRequest']);
Route::post('/exam/duplicate', [ExamController::class, 'duplicateRequest']);
Route::get('/exam/results', [ExamController::class, 'studentResults'])->name('exam.results');
Route::get('/exam/excellence-cert', [ExamController::class, 'studentExcellenceCert'])->name('exam.excellence-cert');

// Student Certificate Routes
Route::get('/certificate/{courseId}', function ($courseId) {
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

Route::get('/certificate/{courseId}/preview', function ($courseId) {
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

Route::get('/certificate/view/{code}', [AdminController::class, 'viewCertificate'])->name('certificate.view');
Route::get('/certificate/download/{code}', [AdminController::class, 'downloadCertificate'])->name('certificate.download');

// Student IPDC Routes
Route::get('/ipdc/vault', [IpdcController::class, 'studentVault'])->name('ipdc.vault');
Route::post('/ipdc/submit-cert', [IpdcController::class, 'submitCert']);
Route::get('/ipdc/assignment/{id}', [IpdcController::class, 'showAssignment']);
Route::post('/ipdc/submit-task/{id}', [IpdcController::class, 'submitTask']);
Route::get('/ipdc/evaluation-pdf/{id}', [IpdcController::class, 'evaluationPdf']);
Route::get('/ipdc/practice/{id}', [IpdcHackerrankController::class, 'showProblem']);
Route::post('/api/ipdc/hackerrank/run/{id}', [IpdcHackerrankController::class, 'runCode']);
Route::post('/api/ipdc/hackerrank/submit/{id}', [IpdcHackerrankController::class, 'submitCode']);

// Circulars & Notices (Student/Public)
Route::get('/circulars-notices', [CircularNotificationController::class, 'studentIndex'])->name('circulars.index');
Route::get('/circulars/{id}/download', [CircularNotificationController::class, 'downloadCircularPdf']);
Route::get('/circulars/{id}/view', [CircularNotificationController::class, 'viewCircularPdf']);

// Student Queries Routes
Route::post('/student-queries/store', [StudentQueryController::class, 'store']);
Route::post('/student-queries/{id}/resolve', [StudentQueryController::class, 'resolve']);

// Student Time Capsule Routes
Route::get('/time-capsule', [TimeCapsuleController::class, 'index'])->name('time-capsule.index');
Route::post('/time-capsule/store', [TimeCapsuleController::class, 'store']);
Route::post('/time-capsule/{id}/unlock', [TimeCapsuleController::class, 'unlock']);
Route::delete('/time-capsule/{id}', [TimeCapsuleController::class, 'destroy']);

/*
|--------------------------------------------------------------------------
| Parent Portal Routes
|--------------------------------------------------------------------------
*/
Route::middleware([RoleMiddleware::class.':parent'])->group(function () {
    Route::get('/parent/dashboard', [ParentController::class, 'dashboard'])->name('parent.dashboard');
    Route::post('/parent/ptm/{id}/reply', [ParentController::class, 'submitReply']);
    Route::post('/parent/gatepass', [ParentController::class, 'submitGatepass']);
    Route::post('/parent/leave', [ParentController::class, 'submitLeave']);
    Route::post('/parent/query', [ParentController::class, 'submitQuery']);
});

/*
|--------------------------------------------------------------------------
| Admin Authentication Routes
|--------------------------------------------------------------------------
*/
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
Route::get('/admin/logout', [AdminController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| Protected Admin / Faculty / Staff / CR Routes
|--------------------------------------------------------------------------
*/
Route::middleware([RoleMiddleware::class])->group(function () {
    Route::get('/admin', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/reports', [AdminController::class, 'reportsSection']);
    Route::post('/admin/config/module-access', [AdminController::class, 'updateModuleAccess']);
    Route::get('/admin/profile', [AdminController::class, 'profile']);
    Route::get('/admin/certificate/preview/{code}', [AdminController::class, 'previewCertificate']);
    Route::get('/document/official/{docTitle}', [AdminController::class, 'generateOfficialDocument']);

    // Admin / CR / CC Chat Routes
    Route::get('/admin/chat', [ChatController::class, 'index']);
    Route::post('/admin/chat/send', [ChatController::class, 'store']);

    // Admin Exam Form & Admit Card Routes
    Route::get('/admin/exam/forms', [ExamController::class, 'adminForms']);
    Route::post('/admin/exam/forms/submit', [ExamController::class, 'adminSubmitForm']);
    Route::post('/admin/exam/forms/{id}/publish', [ExamController::class, 'publishAdmitCard']);
    Route::get('/admin/exam/admit-card/{userId}', [ExamController::class, 'adminViewAdmitCard']);

    // Circulars / Notifications Admin Store
    Route::post('/admin/circulars/store', [CircularNotificationController::class, 'storeCircular']);
    Route::post('/admin/notifications/store', [CircularNotificationController::class, 'storeNotification']);

    // Synergy Circle Admin/Mentor Routes
    Route::post('/admin/synergy-circle/feedback/{requestId}', [SynergyCircleController::class, 'storeFeedback']);
    Route::post('/admin/synergy-circle/privilege/{applicationId}/process', [SynergyCircleController::class, 'processPrivilege']);

    // Student Queries Admin Routes
    Route::post('/admin/student-queries/update-status', [StudentQueryController::class, 'updateStatus']);
    Route::post('/admin/student-queries/{id}/waive-reduce', [StudentQueryController::class, 'waiveOrReduce']);

    // PTM Report
    Route::post('/admin/ptm/report', [ParentController::class, 'submitPtmReport']);

    // Timetables accessible by Faculty and above (including CR)
    Route::middleware([RoleMiddleware::class.':faculty,cr'])->group(function () {
        Route::get('/admin/timetables', [TimetableController::class, 'index']);
        Route::get('/admin/timetables/{id}/faculty-view', [TimetableController::class, 'facultyShow']);
    });

    // Timetable building and Staff Directory accessible by Dean and CR (and above)
    Route::middleware([RoleMiddleware::class.':dean,cr'])->group(function () {
        Route::post('/admin/timetables', [TimetableController::class, 'store']);
        Route::get('/admin/timetables/build', [TimetableController::class, 'buildManual']);
        Route::post('/admin/timetables/build', [TimetableController::class, 'storeManual']);
        Route::post('/admin/timetables/generate-ai', [TimetableController::class, 'generateAi']);
        Route::get('/admin/timetables/{id}/edit', [TimetableController::class, 'editManual']);
        Route::post('/admin/timetables/{id}/update', [TimetableController::class, 'updateManual']);
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
    Route::middleware([RoleMiddleware::class.':dean'])->group(function () {
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
        Route::get('/admin/departments/{id}/update', function () {
            return redirect('/admin/departments');
        });
        Route::post('/admin/departments/{id}/delete', [AdminController::class, 'deleteDepartment']);
        Route::get('/admin/departments/{id}/delete', function () {
            return redirect('/admin/departments');
        });
        Route::post('/admin/staff', [AdminController::class, 'storeStaff']);
        Route::post('/admin/super-admins/promote', [AdminController::class, 'promoteToSuperAdmin']);
        Route::post('/admin/super-admins/{id}/demote', [AdminController::class, 'demoteFromSuperAdmin']);
        Route::post('/admin/staff/{id}/update', [AdminController::class, 'updateStaff']);
        Route::get('/admin/staff/{id}/update', function () {
            return redirect('/admin/staff');
        });
        Route::post('/admin/staff/reset-staff-password', [AdminController::class, 'resetStaffPassword']);
        Route::post('/admin/staff/{id}/delete', [AdminController::class, 'deleteStaff']);
        Route::get('/admin/staff/{id}/delete', function () {
            return redirect('/admin/staff');
        });
        Route::post('/admin/staff/allocate', [AdminController::class, 'allocateCourseToStaff']);
        Route::post('/admin/staff/bulk-delete', [AdminController::class, 'bulkDeleteStaff']);
        Route::post('/admin/staff/bulk-enroll', [AdminController::class, 'bulkEnrollStaff']);
        Route::post('/admin/custom-tabs/create', [AdminController::class, 'createCustomTabFile']);
        Route::post('/admin/custom-tabs/delete', [AdminController::class, 'deleteCustomTabFile']);
    });

    // Maintenance Mode (Strictly Admin & Dean/Provost)
    Route::middleware([RoleMiddleware::class.':admin,dean'])->group(function () {
        Route::post('/admin/maintenance/toggle', [AdminController::class, 'toggleMaintenance']);
        Route::get('/admin/maintenance/status',  [AdminController::class, 'maintenanceStatus']);
        Route::post('/admin/maintenance/run-task', [AdminController::class, 'runMaintenanceTask']);
    });

    // Bulk Enrollment & Student Management accessible by Admin, Faculty, CR
    Route::middleware([RoleMiddleware::class.':faculty,cr'])->group(function () {
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

        Route::get('/admin/attendance', [AttendanceController::class, 'index']);
        Route::post('/admin/attendance', [AttendanceController::class, 'store']);
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

        // IPDC Management Routes
        Route::get('/admin/ipdc', [IpdcController::class, 'index']);
        Route::get('/admin/ipdc/logs', [IpdcController::class, 'manageLogs']);
        Route::get('/admin/ipdc/certs', [IpdcController::class, 'manageCerts']);
        Route::get('/admin/ipdc/download-cert/{name}', [IpdcController::class, 'downloadCertificate']);
        Route::get('/admin/ipdc/download-transcript/{name}', [IpdcController::class, 'downloadTranscript']);
        Route::post('/admin/ipdc/module', [IpdcController::class, 'storeModule']);
        Route::post('/admin/ipdc/subject', [IpdcController::class, 'storeSubject']);
        Route::post('/admin/ipdc/approve-seva/{id}', [IpdcController::class, 'approveSeva']);
        
        // NEW IPDC ROUTES
        Route::post('/admin/ipdc/upload-asset', [IpdcController::class, 'uploadAsset']);
        Route::post('/admin/ipdc/verify-cert/{id}', [IpdcController::class, 'verifyCert']);
        Route::post('/admin/ipdc/grade-submission/{id}', [IpdcController::class, 'gradeSubmission']);
        Route::post('/admin/ipdc/add-cert', [IpdcController::class, 'adminAddCert']);
        Route::post('/admin/ipdc/update-transcript/{courseId}', [IpdcController::class, 'updateTranscript']);
        Route::post('/admin/ipdc/convert-to-assignment/{moduleId}', [IpdcController::class, 'convertToAssignment']);
        Route::post('/admin/ipdc/delete-task/{id}', [IpdcController::class, 'deleteTask']);
        Route::post('/admin/ipdc/generate-assignment-ai', [IpdcController::class, 'generateAssignmentAi']);

        // Placement Dean routes
        Route::get('/admin/placement', [PlacementController::class, 'index']);
        Route::post('/admin/placement/drives', [PlacementController::class, 'storeDrive']);

        // HackerRank IPDC Practice routes
        Route::get('/admin/ipdc/hackerrank/create', [IpdcHackerrankController::class, 'createProblem']);
        Route::post('/admin/ipdc/hackerrank/store', [IpdcHackerrankController::class, 'storeProblem']);
    });

    // Admin, Dean, HOD, CR & Moderator can see enrollments & demo modes
    Route::middleware([RoleMiddleware::class.':moderator,cr'])->group(function () {
        Route::get('/admin/enrollments', [AdminController::class, 'enrollments']);
        Route::get('/admin/demo-student/{id}', [AdminController::class, 'enterDemoMode']);
        Route::get('/admin/demo-staff/{id}', [AdminController::class, 'enterStaffDemoMode']);
        Route::get('/admin/exit-demo', [AdminController::class, 'exitDemoMode']);
        Route::get('/admin/demo-dean', function () {
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
    Route::middleware([RoleMiddleware::class.':dean,hod,cr'])->group(function () {
        Route::get('/admin/approvals', [AdminController::class, 'approvalsList']);
        Route::post('/admin/approvals/{modelType}/{id}/process', [AdminController::class, 'processStatus']);
    });
});

/*
|--------------------------------------------------------------------------
| Storage & File Serving
|--------------------------------------------------------------------------
*/
Route::get('/storage/{path}', [FileController::class, 'serve'])->where('path', '.*');

if (app()->environment('testing')) {
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

    require __DIR__.'/auth.php';
}
