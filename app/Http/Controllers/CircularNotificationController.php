<?php

namespace App\Http\Controllers;

use App\Models\Circular;
use App\Models\LmsNotification;
use App\Models\Staff;
use App\Models\User;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CircularNotificationController extends Controller
{
    /**
     * Store a new circular.
     */
    public function storeCircular(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'category' => 'required|string|in:academic,exams,administrative,student_cr,urgent',
            'signature_type' => 'required|string|in:mapped,manual',
            'manual_signature_name' => 'nullable|required_if:signature_type,manual|string|max:255',
            'manual_signature_designation' => 'nullable|required_if:signature_type,manual|string|max:255',
        ]);

        $role = session('user_role');
        $staffId = session('staff_id');
        $staffName = session('staff_name') ?? 'Institutional Authority';

        // Check permission roles:
        // DEAN , cONTROLLER OF EXAM (admin/office-assistant/dean), PROVOST, PRESIDENT, ADMIN, OFFICE ASSISTANT, HOD'S, Class Coordinator, Respected Faculty
        // CR: Only student circular category allowed
        $allowedRoles = ['admin', 'dean', 'office-assistant', 'hod', 'coordinator', 'faculty', 'cr'];
        if (!in_array($role, $allowedRoles)) {
            return back()->with('error', 'Unauthorized. You do not have permission to publish circulars.');
        }

        if ($role === 'cr' && $request->category !== 'student_cr') {
            return back()->with('error', 'CR is restricted to publishing Student Circulars (Student & CR Announcements) only.');
        }

        $svg = null;
        if ($request->signature_type === 'manual') {
            // Generate a beautiful cursive SVG for the manual name
            $svg = $this->generateCursiveSignatureSvg($request->manual_signature_name);
        }

        $circular = Circular::create([
            'title' => $request->title,
            'content' => $request->content,
            'category' => $request->category,
            'created_by_name' => $staffName,
            'created_by_role' => $role,
            'created_by_id' => $staffId,
            'signature_type' => $request->signature_type,
            'manual_signature_name' => $request->manual_signature_name,
            'manual_signature_designation' => $request->manual_signature_designation,
            'manual_signature_svg' => $svg
        ]);

        // Proactively generate a corresponding LMS Notification too!
        LmsNotification::create([
            'title' => 'New Circular: ' . $request->title,
            'content' => 'An official circular has been published under category: ' . ucfirst($request->category) . '. [Click to view.](/circulars/' . $circular->id . '/download)',
            'type' => 'circular',
            'created_by_name' => $staffName,
            'created_by_role' => $role,
            'created_by_id' => $staffId
        ]);

        return back()->with('success', 'Official circular published and broadcasted to LMS Notifications successfully.');
    }

    /**
     * Store a new notification.
     */
    public function storeNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:lms_notification,circular,approvals,qa_notification,faculty_notice,news,urgent_news',
        ]);

        $role = session('user_role');
        $staffId = session('staff_id');
        $staffName = session('staff_name') ?? 'Institutional System';

        // Check if role is allowed
        $allowedRoles = ['admin', 'dean', 'office-assistant', 'hod', 'coordinator', 'faculty', 'cr'];
        if (!in_array($role, $allowedRoles)) {
            return back()->with('error', 'Unauthorized to publish notifications.');
        }

        // Restrict QA notification to staff only
        if ($request->type === 'qa_notification' && $role === 'cr') {
            return back()->with('error', 'CR cannot create QA notifications (restricted for staff only).');
        }

        LmsNotification::create([
            'title' => $request->title,
            'content' => $request->content,
            'type' => $request->type,
            'created_by_name' => $staffName,
            'created_by_role' => $role,
            'created_by_id' => $staffId
        ]);

        return back()->with('success', 'Notification posted successfully.');
    }

    /**
     * Download circular as a PDF styled in Times New Roman (A4 format).
     */
    public function downloadCircularPdf($id)
    {
        $circular = Circular::findOrFail($id);

        // Fetch signature SVG
        $sigSvg = null;
        $signerName = $circular->created_by_name;
        $signerDesignation = ucfirst($circular->created_by_role);

        if ($circular->signature_type === 'mapped') {
            if ($circular->created_by_role === 'cr') {
                $user = User::find($circular->created_by_id);
                if ($user) {
                    $sigSvg = $user->digital_signature;
                    $signerName = $user->name;
                    $signerDesignation = 'Class Representative (CR)';
                }
            } else {
                $staff = Staff::find($circular->created_by_id);
                if ($staff) {
                    $sigSvg = $staff->digital_signature;
                    $signerName = $staff->name;
                    // Format designation nicely
                    $signerDesignation = implode(', ', $staff->positions ?? [ucfirst($staff->role)]);
                }
            }
        } else {
            $sigSvg = $circular->manual_signature_svg;
            $signerName = $circular->manual_signature_name;
            $signerDesignation = $circular->manual_signature_designation;
        }

        // Clean up SVG string for HTML embedding (sometimes base64 encoding it is safer for DomPDF)
        $sigBase64 = null;
        if ($sigSvg) {
            $sigBase64 = 'data:image/svg+xml;base64,' . base64_encode($sigSvg);
        }

        $pdf = Pdf::loadView('circular_pdf', compact('circular', 'sigBase64', 'signerName', 'signerDesignation'))
            ->setPaper('a4', 'portrait');

        $safeName = str_replace(' ', '_', $circular->title);
        return $pdf->download('Circular_' . $circular->id . '_' . $safeName . '.pdf');
    }

    /**
     * Display student panel with circular history and notifications.
     */
    public function studentIndex()
    {
        $userId = session('demo_user_id') ?? auth()->id() ?? 1;
        $user = User::find($userId);

        // 5 tabs of notifications for student (excludes QA-NOTIFICATION and APPROVALS)
        $studentTypes = ['lms_notification', 'circular', 'faculty_notice', 'news', 'urgent_news'];
        $notifications = LmsNotification::whereIn('type', $studentTypes)->latest()->get();

        // 5 circular categories
        $academicCirculars = Circular::where('category', 'academic')->latest()->get();
        $examCirculars = Circular::where('category', 'exams')->latest()->get();
        $adminCirculars = Circular::where('category', 'administrative')->latest()->get();
        $studentCirculars = Circular::where('category', 'student_cr')->latest()->get();
        $urgentCirculars = Circular::where('category', 'urgent')->latest()->get();

        return view('student.circulars', compact(
            'user',
            'notifications',
            'academicCirculars',
            'examCirculars',
            'adminCirculars',
            'studentCirculars',
            'urgentCirculars'
        ));
    }

    /**
     * Preview circular as a PDF inline stream.
     */
    public function viewCircularPdf($id)
    {
        $circular = Circular::findOrFail($id);

        $sigSvg = null;
        $signerName = $circular->created_by_name;
        $signerDesignation = ucfirst($circular->created_by_role);

        if ($circular->signature_type === 'mapped') {
            if ($circular->created_by_role === 'cr') {
                $user = User::find($circular->created_by_id);
                if ($user) {
                    $sigSvg = $user->digital_signature;
                    $signerName = $user->name;
                    $signerDesignation = 'Class Representative (CR)';
                }
            } else {
                $staff = Staff::find($circular->created_by_id);
                if ($staff) {
                    $sigSvg = $staff->digital_signature;
                    $signerName = $staff->name;
                    $signerDesignation = implode(', ', $staff->positions ?? [ucfirst($staff->role)]);
                }
            }
        } else {
            $sigSvg = $circular->manual_signature_svg;
            $signerName = $circular->manual_signature_name;
            $signerDesignation = $circular->manual_signature_designation;
        }

        $sigBase64 = null;
        if ($sigSvg) {
            $sigBase64 = 'data:image/svg+xml;base64,' . base64_encode($sigSvg);
        }

        $pdf = Pdf::loadView('circular_pdf', compact('circular', 'sigBase64', 'signerName', 'signerDesignation'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream('Circular_' . $circular->id . '.pdf');
    }

    /**
     * Generate cursive SVG text signature dynamically.
     */
    private function generateCursiveSignatureSvg($name)
    {
        $cleanName = preg_replace('/[^a-zA-Z0-9\s.]/', '', $name);
        $cleanName = substr($cleanName, 0, 30);
        $fonts = ['Dancing Script', 'Caveat', 'Sacramento', 'Yellowtail', 'Great Vibes', 'Allura'];
        $index = abs(crc32($cleanName)) % count($fonts);
        $font = $fonts[$index];
        $seed = abs(crc32($cleanName));
        $cp1_y = 35 + ($seed % 10);
        $cp2_y = 40 - (($seed >> 1) % 10);
        
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 160 50" width="120" height="40">';
        $svg .= '<defs>';
        $svg .= '<style>';
        $svg .= '@import url(\'https://fonts.googleapis.com/css2?family=' . urlencode($font) . '&amp;display=swap\');';
        $svg .= '.sig-txt { font-family: \'' . $font . '\', cursive; font-size: 20px; fill: #1e3a8a; font-style: italic; }';
        $svg .= '</style>';
        $svg .= '</defs>';
        $svg .= '<path d="M 10 ' . $cp1_y . ' Q 80 ' . $cp2_y . ' 150 38" fill="none" stroke="#ea580c" stroke-width="1.5" stroke-linecap="round" opacity="0.65"/>';
        $svg .= '<text x="15" y="32" class="sig-txt">' . e($cleanName) . '</text>';
        $svg .= '</svg>';
        return $svg;
    }
}
