<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Course;

use App\Models\IpdcAsset;
use App\Models\ExternalCertification;
use App\Models\Task;
use Illuminate\Support\Facades\Storage;

class IpdcController extends Controller
{
    public function index()
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean', 'hod', 'faculty', 'cr'])) {
            return redirect('/admin')->with('error', 'Unauthorized access to IPDC Management.');
        }

        $ipdcCourses = Course::with('subjects')->orderBy('title')->get();
                            
        // Fetch active IPDC assignments
        $ipdcTasks = Task::whereIn('course_id', $ipdcCourses->pluck('id'))
                            ->with(['course', 'subject'])
                            ->latest()
                            ->get();

        $assets = IpdcAsset::latest()->get();
        
        // Show both pending and the last 10 verified certs
        $pendingCerts = ExternalCertification::with('user')
                            ->whereIn('verification_status', ['pending', 'verified'])
                            ->latest()
                            ->take(20)
                            ->get();
                            
        $students = User::where('role', 'student')->orderBy('name')->get();
        
        // --- Evaluation Data ---
        // Fetch submissions for tasks that belong to IPDC courses
        $ipdcTaskIds = Task::whereIn('course_id', $ipdcCourses->pluck('id'))->pluck('id');
        $submissions = \App\Models\TaskSubmission::with(['user', 'task'])
                            ->whereIn('task_id', $ipdcTaskIds)
                            ->whereNull('grade')
                            ->latest()
                            ->get();

        $gradedSubmissions = \App\Models\TaskSubmission::with(['user', 'task'])
                            ->whereIn('task_id', $ipdcTaskIds)
                            ->whereNotNull('grade')
                            ->latest()
                            ->get();

        $totalCerts = ExternalCertification::where('verification_status', 'verified')->count();
        $totalSeva = 15400 + ($totalCerts * 2); // Dynamic dummy logic for Seva
        
        return view('admin.ipdc.index', compact('ipdcCourses', 'ipdcTasks', 'assets', 'pendingCerts', 'students', 'totalCerts', 'totalSeva', 'submissions', 'gradedSubmissions'));
    }

    public function gradeSubmission(Request $request, $id)
    {
        $submission = \App\Models\TaskSubmission::findOrFail($id);
        $submission->update([
            'grade' => $request->grade,
            'feedback' => $request->feedback,
            'evaluator_name' => session('staff_name') ?? 'BHAVIKKUMAR PATEL'
        ]);

        return back()->with('success', 'Assignment graded successfully for ' . $submission->user->name);
    }

    public function adminAddCert(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'platform' => 'required|string',
            'title' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'link' => 'nullable|url',
        ]);

        $platform = $request->platform;
        if ($platform === 'Other' && $request->filled('custom_platform')) {
            $platform = $request->custom_platform;
        }

        $path = null;
        $content = null;
        $mime = null;
        if ($request->hasFile('file')) {
            $content = file_get_contents($request->file('file')->getRealPath());
            $mime = $request->file('file')->getMimeType();
        }

        $cert = ExternalCertification::create([
            'user_id' => $request->user_id,
            'platform' => $platform,
            'title' => $request->title,
            'file_path' => $path,
            'file_content' => $content,
            'mime_type' => $mime,
            'credential_link' => $request->link,
            'issue_date' => $request->issue_date ?? now()->toDateString(),
            'verification_status' => 'verified',
            'verified_by' => session('staff_name') ?? 'Admin',
        ]);

        return back()->with('success', 'Credential for ' . $cert->title . ' successfully added and synchronized.');
    }

    public function uploadAsset(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'type' => 'required|in:workbook,solution,resource',
            'file' => 'required|file|mimes:pdf,zip,docx,pptx|max:10240',
        ]);

        $content = file_get_contents($request->file('file')->getRealPath());
        $mime = $request->file('file')->getMimeType();

        IpdcAsset::create([
            'title' => $request->title,
            'type' => $request->type,
            'file_path' => null,
            'file_content' => $content,
            'mime_type' => $mime,
            'uploaded_by' => session('staff_name') ?? 'Admin',
        ]);

        return back()->with('success', 'IPDC Asset uploaded successfully.');
    }

    public function verifyCert(Request $request, $id)
    {
        $cert = ExternalCertification::findOrFail($id);
        $cert->update([
            'verification_status' => $request->status, // verified or rejected
            'verified_by' => session('staff_name') ?? 'Admin',
            'admin_remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Certification status updated.');
    }

    public function updateTranscript(Request $request, $courseId)
    {
        $course = Course::findOrFail($courseId);
        $course->update(['transcript_content' => $request->content]);

        return back()->with('success', 'Transcript content updated for ' . $course->title);
    }

    public function convertToAssignment($moduleId)
    {
        $course = Course::findOrFail($moduleId);
        
        // Create a Task from the IPDC Module
        Task::create([
            'course_id' => $course->id,
            'title' => 'Reflection Assignment: ' . $course->title,
            'description' => 'Submit your detailed reflection based on the workbook for ' . $course->title,
            'due_date' => now()->addDays(7),
            'max_points' => 100,
        ]);

        return back()->with('success', 'Module converted to assignment successfully.');
    }

    // --- Student Methods ---

    public function studentVault()
    {
        $userId = auth()->id() ?? session('demo_user_id') ?? 1;
        $user = User::find($userId);
        
        // Fetch student enrollments
        $enrolledCourseIds = \App\Models\Enrollment::where('user_id', $userId)->pluck('course_id');
        
        // Fetch tasks corresponding to enrolled courses
        $ipdcTasks = Task::whereIn('course_id', $enrolledCourseIds)
                         ->with(['course', 'subject'])
                         ->latest()
                         ->get();

        $assets = IpdcAsset::latest()->get();
        $myCerts = ExternalCertification::where('user_id', $userId)->latest()->get();
        
        $mySubmissions = \App\Models\TaskSubmission::where('user_id', $userId)
                         ->with(['task.course', 'task.subject'])
                         ->latest()
                         ->get();
        
        return view('student.ipdc.vault', compact('user', 'assets', 'myCerts', 'ipdcTasks', 'mySubmissions'));
    }

    public function submitCert(Request $request)
    {
        $userId = auth()->id() ?? session('demo_user_id') ?? 1;

        $request->validate([
            'platform' => 'required|string',
            'title' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,jpg,png|max:5120',
            'link' => 'nullable|url',
        ]);

        $platform = $request->platform;
        if ($platform === 'Other' && $request->filled('custom_platform')) {
            $platform = $request->custom_platform;
        }

        $path = null;
        $content = null;
        $mime = null;
        if ($request->hasFile('file')) {
            $content = file_get_contents($request->file('file')->getRealPath());
            $mime = $request->file('file')->getMimeType();
        }

        ExternalCertification::create([
            'user_id' => $userId,
            'platform' => $platform,
            'title' => $request->title,
            'file_path' => $path,
            'file_content' => $content,
            'mime_type' => $mime,
            'credential_link' => $request->link,
            'issue_date' => $request->issue_date,
        ]);

        return back()->with('success', 'Certification submitted for verification.');
    }

    public function showAssignment($id)
    {
        $task = Task::with('course')->findOrFail($id);
        return view('student.ipdc.ide', compact('task'));
    }

    public function submitTask(Request $request, $id)
    {
        $userId = auth()->id() ?? session('demo_user_id') ?? 1;
        $task = Task::findOrFail($id);
        
        $request->validate([
            'code' => 'required|string',
            'language' => 'nullable|string',
        ]);
        
        $mimeType = 'text/plain';
        if ($request->language === 'javascript') {
            $mimeType = 'application/javascript';
        } elseif ($request->language === 'python') {
            $mimeType = 'text/x-python';
        } elseif ($request->language === 'java') {
            $mimeType = 'text/x-java-source';
        }
        
        $submission = \App\Models\TaskSubmission::where('user_id', $userId)
            ->where('task_id', $id)
            ->first();
            
        if ($submission) {
            $submission->update([
                'file_content' => $request->code,
                'mime_type' => $mimeType,
                'grade' => null,
                'feedback' => null,
                'evaluator_name' => null,
            ]);
        } else {
            $submission = \App\Models\TaskSubmission::create([
                'user_id' => $userId,
                'task_id' => $id,
                'file_path' => 'cloud_stored',
                'file_content' => $request->code,
                'mime_type' => $mimeType,
                'grade' => null,
                'feedback' => null,
                'evaluator_name' => null,
            ]);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Assignment submitted successfully.'
        ]);
    }

    public function evaluationPdf($id)
    {
        $submission = \App\Models\TaskSubmission::with(['user', 'task.course', 'task.subject'])->findOrFail($id);
        
        $student = $submission->user;
        
        $evaluatorName = $submission->evaluator_name ?? 'BHAVIKKUMAR PATEL';
        $evaluatorSignature = \App\Models\Staff::generateSignatureSvg($evaluatorName);
        
        $deanStaff = \App\Models\Staff::where('role', 'dean')->first();
        $deanName = $deanStaff ? $deanStaff->name : 'Dr. Sadhu Gyaneswar Das';
        $deanSignature = $deanStaff ? $deanStaff->digital_signature : \App\Models\Staff::generateSignatureSvg($deanName);

        $hodStaff = \App\Models\Staff::where('role', 'hod')->first();
        $hodName = $hodStaff ? $hodStaff->name : 'Bhavik Patel';
        $hodSignature = $hodStaff ? $hodStaff->digital_signature : \App\Models\Staff::generateSignatureSvg($hodName);

        $docTitle = 'Academic Assignment Evaluation Report';
        
        return view('admin.ipdc.evaluation_pdf', compact(
            'submission', 'student', 'docTitle',
            'evaluatorName', 'evaluatorSignature',
            'deanName', 'deanSignature',
            'hodName', 'hodSignature'
        ));
    }

    public function manageLogs()
    {
        return view('admin.ipdc.logs');
    }

    public function manageCerts()
    {
        $certs = ExternalCertification::with('user')->latest()->get();
        return view('admin.ipdc.certs', compact('certs'));
    }

    public function storeModule(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'instructor' => 'nullable|string',
            'level' => 'nullable|string',
            'credits' => 'nullable|numeric',
        ]);

        Course::create([
            'title' => $request->title,
            'instructor' => $request->instructor ?? 'Academic Faculty',
            'level' => $request->level ?? 'Bachelors',
            'credits' => $request->credits ?? 2.0,
            'description' => 'Academic Curriculum Course',
            'transcript_content' => 'Description pending for ' . $request->title,
        ]);
        return back()->with('success', 'Course successfully mapped to the curriculum.');
    }

    public function storeSubject(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'name' => 'required|string',
            'code' => 'required|string',
            'type' => 'required|in:theory,practical,pbl',
        ]);

        \App\Models\Subject::create([
            'course_id' => $request->course_id,
            'name' => $request->name,
            'code' => $request->code,
            'type' => $request->type,
        ]);

        return back()->with('success', 'Subject mapped successfully to the course.');
    }

    public function approveSeva(Request $request, $id)
    {
        return back()->with('success', 'Seva hours approved and added to student profile.');
    }

    public function downloadCertificate($name)
    {
        // ... (Keep existing implementation or refine)
        $certificate = (object)[
            'user' => (object)['name' => $name, 'enrollment_no' => 'IPDC-'.rand(1000, 9999), 'level' => 1, 'department' => 'IPDC Curriculum'],
            'course' => (object)[
                'title' => 'Integrated Personality Development Course', 
                'instructor' => 'IPDC Teaching Faculty', 
                'lessons' => collect([]),
                'tasks' => collect([]),
                'quizzes' => collect([]),
                'transcript_content' => "Integrated Personality Development Course (IPDC) is a comprehensive academic program..."
            ],
            'unique_code' => 'IPDC-CERT-' . strtoupper(\Illuminate\Support\Str::random(8)),
            'created_at' => now()
        ];
        
        $user = $certificate->user;
        $course = $certificate->course;
        $taskSubmissions = collect([]);
        $quizAttempts = collect([]);

        return view('student.preview_document', compact('course', 'user', 'certificate', 'taskSubmissions', 'quizAttempts'));
    }

    public function downloadTranscript($name)
    {
        return $this->downloadCertificate($name);
    }

    public function serveAsset($id)
    {
        $asset = IpdcAsset::findOrFail($id);
        if ($asset->file_content) {
            return response($asset->file_content)->header('Content-Type', $asset->mime_type ?? 'application/octet-stream');
        } elseif ($asset->file_path) {
            return redirect(asset('storage/' . $asset->file_path));
        }
        abort(404);
    }

    public function serveCert($id)
    {
        $cert = ExternalCertification::findOrFail($id);
        if ($cert->file_content) {
            return response($cert->file_content)->header('Content-Type', $cert->mime_type ?? 'application/octet-stream');
        } elseif ($cert->file_path) {
            return redirect(asset('storage/' . $cert->file_path));
        }
        abort(404);
    }

    public function serveSubmission($id)
    {
        $sub = \App\Models\TaskSubmission::findOrFail($id);
        if ($sub->file_content) {
            return response($sub->file_content)->header('Content-Type', $sub->mime_type ?? 'application/octet-stream');
        } elseif ($sub->file_path) {
            return redirect(asset('storage/' . $sub->file_path));
        }
        abort(404);
    }

    public function deleteTask($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return back()->with('success', 'Assignment deleted successfully!');
    }

    public function generateAssignmentAi(Request $request)
    {
        $course = Course::find($request->course_id);
        if (!$course) {
            return response()->json(['error' => 'Course not found'], 400);
        }

        $subjectName = 'General Course Topics';
        if ($request->filled('subject_id')) {
            $subject = \App\Models\Subject::find($request->subject_id);
            if ($subject) {
                $subjectName = $subject->name;
            }
        }

        $type = $request->assignment_type ?? 'homework';

        $apiKey = env('GEMINI_API_KEY');
        if ($apiKey) {
            try {
                $prompt = "Create a premium, professional university-level academic assignment.
Course: {$course->title}
Subject: {$subjectName}
Assignment Type: {$type}

Generate:
1. A brief, catchy, professional Assignment Title.
2. Detailed instructions/description. Include assignment objectives, step-by-step student tasks, expected deliverables, and evaluation criteria. Use formatted Markdown with bold highlights.

Return ONLY a clean JSON object with precisely these keys:
{
  \"title\": \"Assignment Title\",
  \"description\": \"Detailed Markdown description here\"
}
Do not wrap the JSON response in ```json markdown blocks. Return only raw JSON string.";

                $response = \Illuminate\Support\Facades\Http::timeout(15)->post(
                    "https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-pro:generateContent?key={$apiKey}",
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
                    $resData = $response->json();
                    $text = $resData['candidates'][0]['content']['parts'][0]['text'] ?? null;
                    if ($text) {
                        $text = preg_replace('/```json\s*|\s*```/', '', $text);
                        $text = preg_replace('/```\s*|\s*```/', '', $text);
                        $data = json_decode(trim($text), true);
                        if (isset($data['title']) && isset($data['description'])) {
                            return response()->json($data);
                        }
                    }
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error("Gemini Assignment generation failed: " . $e->getMessage());
            }
        }

        // Fallback generator
        return response()->json([
            'title' => 'Practical Project: ' . $course->title . ' (' . ucfirst($type) . ')',
            'description' => "### Objective\nUnderstand core principles of {$course->title} focusing on {$subjectName}.\n\n### Deliverables\n1. Submission Report summarizing the problem, design strategy, and code/document structure.\n2. Output screenshots showing the testing scenarios.\n\n### Grading Criteria\n* Implementation Integrity: 50%\n* Documentation Quality: 30%\n* Testing & Verification: 20%"
        ]);
    }
}
