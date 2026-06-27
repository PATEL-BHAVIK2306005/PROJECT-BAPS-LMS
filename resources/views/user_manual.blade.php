<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAPS LMS - System User Manual</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="card shadow-sm border-0" style="border-radius: 16px;">
        <div class="card-header bg-dark text-white p-4" style="border-top-left-radius: 16px; border-top-right-radius: 16px;">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="fw-bold mb-0"><i class="fas fa-book me-2 text-warning"></i> BAPS LMS - System User Manual</h2>
                <a href="javascript:history.back()" class="btn btn-outline-light rounded-pill"><i class="fas fa-arrow-left me-1"></i> Go Back</a>
            </div>
            <p class="mb-0 mt-2 text-white-50">Official Documentation & Protocol Guide</p>
        </div>
        
        <div class="card-body p-5">
            <h4 class="fw-bold text-primary border-bottom pb-2 mb-3">1. Authentication & Security Protocols</h4>
            <div class="mb-4">
                <h6 class="fw-bold">Unified Login Portal</h6>
                <p class="text-muted">All users access the system via the Unified Login Portal. Students use their 5-digit passcode and enrollment number, while Staff/Administrators use their institutional email address. Legacy unique codes have been deprecated.</p>
                <h6 class="fw-bold mt-3">System Access Hierarchy Matrix</h6>
                <ul class="text-muted">
                    <li><strong class="text-success">Administrator (200%) & Dean (200%):</strong> Executive control, God Mode, global reports.</li>
                    <li><strong class="text-warning">HOD (175%):</strong> Departmental authority. Approves enrollments for their sector.</li>
                    <li><strong class="text-primary">Coordinator / CC (125%):</strong> Bulk enrollment, Talent Hub management.</li>
                    <li><strong class="text-secondary">CR (125%):</strong> Student representative. Assists in coordination.</li>
                    <li><strong style="color: #d946ef;">Fac-Coordinator (75%) & Fac-Lab (50%):</strong> Academic execution and grading.</li>
                </ul>
            </div>

            <h4 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-5">2. Student Portal Overview</h4>
            <div class="mb-4 text-muted">
                <p><strong>Learning Hub:</strong> Browse courses, watch videos, and take quizzes.</p>
                <p><strong>Assignments Section (IPDC):</strong> Download interactive worksheets, read the Satsang Diksha, and log mandatory 15 hours of Seva.</p>
                <p><strong>Examinations:</strong> Submit your dynamic Exam Form. Once Published by the Admin, you can download your official Hall Ticket PDF.</p>
                <p><strong>Digital Credentials:</strong> 100% completion unlocks a triple-signed Certificate and Transcript.</p>
            </div>

            <h4 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-5">3. Administrative Dashboard</h4>
            <div class="mb-4 text-muted">
                <p><strong>Approvals Engine:</strong> Manage Registration, Enrollments, Gatepasses, Leaves, and Fee queues.</p>
                <p><strong>Student Directory & Bulk Logistics:</strong> View students and securely map enrollments in bulk.</p>
                <p><strong>Examination Center:</strong> Manage Quizzes, Question Banks, and release student Admit Cards dynamically.</p>
                <p><strong>Communications Center (Chat):</strong> Gated 5-section chat room (General, Academics, Exams, Placements, Administration) available to CRs, CCs, HODs, Deans, and Admins. Automatic Role Tagging enforced.</p>
                <p><strong>Reports Engine:</strong> 200% Privilege analytics export.</p>
                <p><strong>Master Data / God Mode:</strong> 8-digit PIN protected restricted area for database GUI overrides.</p>
            </div>

            <h4 class="fw-bold text-primary border-bottom pb-2 mb-3 mt-5">4. Hybrid Class Management</h4>
            <div class="mb-4 text-muted">
                <p>Faculty can toggle <code>class_mode</code> between Offline and Online. When set to Online with a Google Meet link, students will see a "Join Live Class" button on their portal.</p>
            </div>
            
            <div class="alert alert-info mt-5 border-0 bg-light">
                <i class="fas fa-headset me-2 text-info"></i> For advanced troubleshooting, please utilize the <strong>IT Helpdesk</strong> ticket system located in the Admin Dashboard.
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
