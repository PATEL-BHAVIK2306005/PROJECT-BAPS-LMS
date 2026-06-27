<?php

namespace App\Http\Controllers;

use App\Models\CodeReviewRequest;
use App\Models\CodeReviewFeedback;
use App\Models\PrivilegeApplication;
use App\Models\Staff;
use App\Models\User;
use App\Models\LmsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SynergyCircleController extends Controller
{
    /**
     * Display student Synergy Circle workspace.
     */
    public function studentIndex()
    {
        $role = session('user_role');
        if (!in_array($role, ['admin', 'dean'])) {
            abort(403, 'Synergy Circle is currently open for testing only for Admin and Dean.');
        }

        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::find($userId);

        if (!$user) {
            return redirect('/login')->with('error', 'Student profile not found.');
        }

        // 1. Fetch student's code review requests
        $requests = CodeReviewRequest::where('user_id', $userId)
            ->with(['mentor', 'feedback.reviewer'])
            ->latest()
            ->get();

        // 2. Fetch all staff members that can act as mentors
        $mentors = Staff::whereIn('role', ['admin', 'dean', 'hod', 'faculty', 'office-assistant', 'coordinator'])->get();

        // 3. Fetch student's earned badges (feedbacks received)
        $badges = CodeReviewFeedback::whereHas('request', function ($query) use ($userId) {
            $query->where('user_id', $userId);
        })->with(['request.mentor', 'reviewer'])->latest()->get();

        // 4. Fetch privilege applications
        $privileges = PrivilegeApplication::where('user_id', $userId)
            ->with(['feedback.request', 'processor'])
            ->latest()
            ->get();

        return view('student.synergy_circle', compact('user', 'requests', 'mentors', 'badges', 'privileges'));
    }

    /**
     * Store a new code review request.
     */
    public function storeRequest(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'code_snippet' => 'required|string',
            'language' => 'required|string',
            'category' => 'required|string',
            'mentor_id' => 'required|exists:staff,id',
        ]);

        $userId = session('demo_user_id') ?? auth()->id() ?? 1;

        CodeReviewRequest::create([
            'user_id' => $userId,
            'mentor_id' => $request->mentor_id,
            'title' => $request->title,
            'description' => $request->description,
            'code_snippet' => $request->code_snippet,
            'language' => $request->language,
            'category' => $request->category,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Code review request submitted to your mentor successfully!');
    }

    /**
     * Submit feedback and issue a badge (Faculty action).
     */
    public function storeFeedback(Request $request, $requestId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comments' => 'required|string',
            'signature_type' => 'required|string|in:mapped,manual',
            'manual_signature_name' => 'nullable|required_if:signature_type,manual|string|max:255',
        ]);

        $codeRequest = CodeReviewRequest::findOrFail($requestId);

        $reviewerId = session('staff_id') ?? 1;
        $reviewer = Staff::find($reviewerId);
        $reviewerName = session('staff_name') ?? ($reviewer ? $reviewer->name : 'Mentor');
        $reviewerRole = session('user_role') ?? 'faculty';

        // Signature configuration
        $sigSvg = null;
        if ($request->signature_type === 'manual') {
            $sigSvg = Staff::generateSignatureSvg($request->manual_signature_name ?? $reviewerName);
        } else {
            $sigSvg = $reviewer ? $reviewer->digital_signature : Staff::generateSignatureSvg($reviewerName);
        }

        // Unique credential verification hash
        $categoryPrefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $codeRequest->category), 0, 3));
        $badgeHash = 'SC-' . $categoryPrefix . '-' . strtoupper(Str::random(6));

        // Ensure hash uniqueness
        while (CodeReviewFeedback::where('badge_hash', $badgeHash)->exists()) {
            $badgeHash = 'SC-' . $categoryPrefix . '-' . strtoupper(Str::random(6));
        }

        $feedback = CodeReviewFeedback::create([
            'request_id' => $requestId,
            'reviewer_id' => $reviewerId,
            'rating' => $request->rating,
            'comments' => $request->comments,
            'signature_type' => $request->signature_type,
            'signature_data' => $sigSvg,
            'badge_hash' => $badgeHash,
        ]);

        // Update request status
        $codeRequest->status = 'reviewed';
        $codeRequest->save();

        // Proactively generate notification for the student
        LmsNotification::create([
            'title' => 'Code Reviewed: ' . $codeRequest->title,
            'content' => 'Your mentor ' . $reviewerName . ' has reviewed your code. Rating: ' . $request->rating . '/5 Stars. Excellence Badge issued! Badge Hash: ' . $badgeHash,
            'type' => 'lms_notification',
            'created_by_name' => $reviewerName,
            'created_by_role' => $reviewerRole,
            'created_by_id' => $reviewerId,
        ]);

        return redirect()->back()->with('success', 'Code review feedback submitted and digital badge issued successfully!');
    }

    /**
     * Submit an application for lab privileges using an earned badge.
     */
    public function applyPrivilege(Request $request)
    {
        $request->validate([
            'feedback_id' => 'required|exists:code_review_feedbacks,id',
            'privilege_type' => 'required|string',
            'justification' => 'required|string',
        ]);

        $userId = session('demo_user_id') ?? auth()->id() ?? 1;

        // Ensure student actually owns the feedback/badge
        $feedback = CodeReviewFeedback::where('id', $request->feedback_id)
            ->whereHas('request', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })->firstOrFail();

        PrivilegeApplication::create([
            'user_id' => $userId,
            'feedback_id' => $request->feedback_id,
            'privilege_type' => $request->privilege_type,
            'justification' => $request->justification,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Lab privilege application submitted successfully! Staff will review it shortly.');
    }

    /**
     * Approve or reject a student privilege application (Staff action).
     */
    public function processPrivilege(Request $request, $applicationId)
    {
        $request->validate([
            'status' => 'required|string|in:approved,rejected',
        ]);

        $application = PrivilegeApplication::with(['user', 'feedback.request.mentor'])->findOrFail($applicationId);

        $processorId = session('staff_id') ?? 1;
        $processor = Staff::find($processorId);
        $processorName = session('staff_name') ?? ($processor ? $processor->name : 'Administrator');
        $processorRole = session('user_role') ?? 'admin';

        $application->status = $request->status;
        $application->processed_by = $processorId;
        $application->save();

        // Send a personal notification to the student
        LmsNotification::create([
            'title' => 'Privilege Request Update',
            'content' => 'Your request for ' . $application->privilege_type . ' has been ' . $request->status . ' by ' . $processorName . '.',
            'type' => 'lms_notification',
            'created_by_name' => $processorName,
            'created_by_role' => $processorRole,
            'created_by_id' => $processorId,
        ]);

        // If approved, broadcast to the main Overview/Broadcast Feed
        if ($request->status === 'approved') {
            $studentName = $application->user->name;
            $privilegeType = $application->privilege_type;
            $badgeHash = $application->feedback->badge_hash;
            $mentorName = $application->feedback->reviewer->name ?? 'Faculty';
            $category = $application->feedback->request->category;

            LmsNotification::create([
                'title' => '🏆 Lab Privilege Granted',
                'content' => 'Student **' . $studentName . '** has been granted **' . $privilegeType . '** after earning the Synergy Circle Badge (**' . $badgeHash . '**) in ' . $category . ' (Reviewed by ' . $mentorName . ').',
                'type' => 'circular', // Broadcast / Circular type so it appears in main feed/circulars
                'created_by_name' => $processorName,
                'created_by_role' => $processorRole,
                'created_by_id' => $processorId,
            ]);
        }

        return redirect()->back()->with('success', 'Privilege application has been successfully ' . $request->status . '.');
    }
}
