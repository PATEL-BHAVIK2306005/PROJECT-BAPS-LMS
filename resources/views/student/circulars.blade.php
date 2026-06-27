@extends('layouts.app')
@section('content')

<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 1.25rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-panel:hover {
        transform: translateY(-4px);
        box-shadow: 0 15px 45px rgba(0, 0, 0, 0.07);
    }
    .nav-tabs-custom {
        border-bottom: 2px solid #e2e8f0;
        margin-bottom: 1.5rem;
    }
    .nav-tabs-custom .nav-link {
        border: none;
        color: #64748b;
        font-weight: 600;
        padding: 10px 20px;
        transition: all 0.2s ease;
        border-bottom: 3px solid transparent;
    }
    .nav-tabs-custom .nav-link:hover {
        color: var(--baps-saffron, #f97316);
    }
    .nav-tabs-custom .nav-link.active {
        color: var(--baps-saffron, #f97316);
        background: transparent;
        border-bottom: 3px solid var(--baps-saffron, #f97316);
    }
    .notif-card {
        border-left: 4px solid #cbd5e1;
        transition: all 0.2s ease;
    }
    .notif-card.type-lms_notification { border-left-color: #3b82f6; }
    .notif-card.type-circular { border-left-color: #f59e0b; }
    .notif-card.type-faculty_notice { border-left-color: #10b981; }
    .notif-card.type-news { border-left-color: #8b5cf6; }
    .notif-card.type-urgent_news { border-left-color: #ef4444; }
    
    .notif-card:hover {
        transform: translateX(4px);
        background-color: #f8fafc;
    }
</style>

<!-- Main Header -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="p-4 text-white rounded rounded-4 shadow-sm border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0f172a, #1e293b);">
            <div class="position-absolute end-0 top-0 translate-middle rounded-circle bg-white" style="width: 250px; height: 250px; opacity: 0.05; pointer-events: none;"></div>
            <div class="position-relative z-1">
                <h2 class="fw-bold mb-1"><i class="fas fa-bullhorn text-warning me-2"></i> Circulars & Notices Portal</h2>
                <p class="mb-0 text-white-50">Official university publications, academic circulars, and campus broadcasts.</p>
            </div>
        </div>
    </div>
</div>

<!-- 5 Tabs Notification Section -->
<div class="row mb-5">
    <div class="col-md-12">
        <div class="glass-panel p-4">
            <h4 class="fw-bold text-dark mb-4"><i class="fas fa-bell text-primary me-2"></i> Institutional Broadcasts</h4>
            
            <ul class="nav nav-tabs nav-tabs-custom" id="notificationTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="notif-lms-tab" data-bs-toggle="tab" data-bs-target="#notif-lms" type="button" role="tab">
                        <i class="fas fa-desktop me-1"></i> LMS Notices
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="notif-circ-tab" data-bs-toggle="tab" data-bs-target="#notif-circ" type="button" role="tab">
                        <i class="fas fa-file-pdf me-1"></i> Circular Refs
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="notif-faculty-tab" data-bs-toggle="tab" data-bs-target="#notif-faculty" type="button" role="tab">
                        <i class="fas fa-chalkboard-teacher me-1"></i> Faculty Notices
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="notif-news-tab" data-bs-toggle="tab" data-bs-target="#notif-news" type="button" role="tab">
                        <i class="fas fa-newspaper me-1"></i> Campus News
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-danger" id="notif-urgent-tab" data-bs-toggle="tab" data-bs-target="#notif-urgent" type="button" role="tab">
                        <i class="fas fa-exclamation-triangle me-1"></i> Urgent News
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="notificationTabsContent">
                <!-- LMS Notices -->
                <div class="tab-pane fade show active" id="notif-lms" role="tabpanel">
                    @include('student.partials.notif_list', ['list' => $notifications->where('type', 'lms_notification'), 'type' => 'lms_notification'])
                </div>
                <!-- Circular Reference -->
                <div class="tab-pane fade" id="notif-circ" role="tabpanel">
                    @include('student.partials.notif_list', ['list' => $notifications->where('type', 'circular'), 'type' => 'circular'])
                </div>
                <!-- Faculty Notices -->
                <div class="tab-pane fade" id="notif-faculty" role="tabpanel">
                    @include('student.partials.notif_list', ['list' => $notifications->where('type', 'faculty_notice'), 'type' => 'faculty_notice'])
                </div>
                <!-- News -->
                <div class="tab-pane fade" id="notif-news" role="tabpanel">
                    @include('student.partials.notif_list', ['list' => $notifications->where('type', 'news'), 'type' => 'news'])
                </div>
                <!-- Urgent News -->
                <div class="tab-pane fade" id="notif-urgent" role="tabpanel">
                    @include('student.partials.notif_list', ['list' => $notifications->where('type', 'urgent_news'), 'type' => 'urgent_news'])
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 5 Internal Tabs Circular Section & History -->
<div class="row mb-5">
    <div class="col-md-12">
        <div class="glass-panel p-4">
            <h4 class="fw-bold text-dark mb-4"><i class="fas fa-file-invoice text-success me-2"></i> Circulars & Official Works</h4>
            
            <ul class="nav nav-tabs nav-tabs-custom" id="circularTabs" role="tablist">
                <li class="nav-item">
                    <button class="nav-link active" id="circ-academic-tab" data-bs-toggle="tab" data-bs-target="#circ-academic" type="button" role="tab">
                        Academic Circulars
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="circ-exams-tab" data-bs-toggle="tab" data-bs-target="#circ-exams" type="button" role="tab">
                        Exams & Testing
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="circ-admin-tab" data-bs-toggle="tab" data-bs-target="#circ-admin" type="button" role="tab">
                        Administrative Works
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" id="circ-student-tab" data-bs-toggle="tab" data-bs-target="#circ-student" type="button" role="tab">
                        Student & CR Announcements
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link text-danger" id="circ-urgent-tab" data-bs-toggle="tab" data-bs-target="#circ-urgent" type="button" role="tab">
                        Urgent Alerts
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="circularTabsContent">
                <!-- Academic -->
                <div class="tab-pane fade show active" id="circ-academic" role="tabpanel">
                    @include('student.partials.circ_list', ['list' => $academicCirculars])
                </div>
                <!-- Exams -->
                <div class="tab-pane fade" id="circ-exams" role="tabpanel">
                    @include('student.partials.circ_list', ['list' => $examCirculars])
                </div>
                <!-- Administrative -->
                <div class="tab-pane fade" id="circ-admin" role="tabpanel">
                    @include('student.partials.circ_list', ['list' => $adminCirculars])
                </div>
                <!-- Student & CR -->
                <div class="tab-pane fade" id="circ-student" role="tabpanel">
                    @include('student.partials.circ_list', ['list' => $studentCirculars])
                </div>
                <!-- Urgent -->
                <div class="tab-pane fade" id="circ-urgent" role="tabpanel">
                    @include('student.partials.circ_list', ['list' => $urgentCirculars])
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
