@extends('layouts.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap');

    :root {
        --baps-saffron: #f97316;
        --baps-saffron-dark: #ea580c;
        --baps-bg: #fdfaf6;
        --baps-text: #1e293b;
        --baps-border: #fed7aa;
        --glass-bg: rgba(255, 255, 255, 0.95);
        --glass-shadow: 0 10px 30px 0 rgba(234, 88, 12, 0.05);
    }

    body {
        background-color: var(--baps-bg) !important;
        font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif !important;
        color: var(--baps-text);
    }

    .parent-header-card {
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: 18px;
        padding: 30px 24px;
        margin-bottom: 28px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
        border-left: 6px solid var(--baps-saffron);
    }

    .parent-tab-btn {
        background: transparent !important;
        color: #64748b !important;
        border: none !important;
        border-radius: 12px !important;
        padding: 14px 22px !important;
        font-weight: 600 !important;
        font-size: 0.92rem !important;
        white-space: nowrap;
        transition: all 0.25s ease !important;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .parent-tab-btn:hover {
        background: rgba(249, 115, 22, 0.08) !important;
        color: var(--baps-saffron-dark) !important;
    }
    .parent-tab-btn.active {
        background: var(--baps-saffron) !important;
        color: #ffffff !important;
        box-shadow: 0 6px 18px rgba(249, 115, 22, 0.3) !important;
    }

    .metric-card {
        background: #ffffff;
        border-radius: 16px;
        padding: 24px;
        border: 1px solid rgba(249, 115, 22, 0.1);
        box-shadow: var(--glass-shadow);
        transition: all 0.3s ease;
        height: 100%;
        display: flex;
        align-items: center;
        gap: 16px;
    }
    .metric-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(234, 88, 12, 0.1);
        border-color: var(--baps-saffron);
    }

    .metric-icon {
        width: 54px;
        height: 54px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.4rem;
        flex-shrink: 0;
    }
    .metric-icon.saffron { background: #fff7ed; color: #ea580c; }
    .metric-icon.blue { background: #eff6ff; color: #3b82f6; }
    .metric-icon.green { background: #f0fdf4; color: #22c55e; }
    .metric-icon.red { background: #fef2f2; color: #ef4444; }

    .ptm-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid rgba(249, 115, 22, 0.1);
        box-shadow: var(--glass-shadow);
        transition: all 0.3s ease;
    }
    .ptm-card:hover {
        box-shadow: 0 12px 24px rgba(234, 88, 12, 0.08);
    }

    .attendance-circle {
        position: relative;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: conic-gradient(var(--baps-saffron) calc(var(--percentage) * 1%), #e2e8f0 0);
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 10px rgba(0,0,0,0.05);
    }
    .attendance-circle::before {
        content: "";
        position: absolute;
        width: 96px;
        height: 96px;
        border-radius: 50%;
        background: #ffffff;
    }
    .attendance-percentage {
        position: relative;
        font-size: 1.5rem;
        font-weight: 800;
        color: #0f172a;
    }

    /* Dark Mode Fallbacks */
    body.dark-mode {
        background-color: #0b0f19 !important;
    }
    body.dark-mode .metric-card,
    body.dark-mode .ptm-card,
    body.dark-mode .card {
        background-color: #111827 !important;
        border-color: #374151 !important;
        color: #f3f4f6 !important;
    }
    body.dark-mode .attendance-circle::before {
        background: #111827 !important;
    }
    body.dark-mode .attendance-percentage {
        color: #ffffff !important;
    }
    body.dark-mode .text-dark {
        color: #ffffff !important;
    }
</style>

<!-- Parent Portal Header -->
<div class="parent-header-card text-white">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-4">
        <div class="d-flex align-items-center gap-4">
            <div class="bg-white rounded-4 p-2 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                <img src="/img/baps_logo.png" alt="BAPS Logo" onerror="this.src='https://placehold.co/80x80/f97316/white?text=BAPS'" style="max-width: 100%; max-height: 100%; object-fit: contain;">
            </div>
            <div>
                <h3 class="fw-bold mb-1 d-flex align-items-center gap-2">
                    Jay Swaminarayan, Parent Portal
                </h3>
                <p class="mb-0 text-white-50 small fw-semibold"><i class="fas fa-child me-1"></i> Child Associated: <strong>{{ $student->name }}</strong> ({{ $student->enrollment_no }})</p>
                <p class="mb-0 text-white-50 small mt-1"><i class="fas fa-university me-1"></i> Department: {{ $student->department->name ?? 'N/A' }} | 60% Parent Level Access</p>
            </div>
        </div>

        <!-- Spiritual Quote Box -->
        <div class="p-3 rounded-4 bg-white bg-opacity-10 border border-white border-opacity-10 text-center text-md-start" style="max-width: 320px;">
            <p class="small mb-1 italic fw-bold" style="font-style: italic;">"In the progress of others lies our own. In the good of others lies our own."</p>
            <span class="small opacity-50 fw-semibold">— HH Pramukh Swami Maharaj</span>
        </div>
    </div>
</div>

<!-- 5 parent functions tab navigation -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="card-body p-2">
        <ul class="nav nav-pills d-flex flex-nowrap overflow-x-auto gap-2" id="parentDashboardTabs" role="tablist" style="scroll-behavior: smooth;">
            <li class="nav-item" role="presentation">
                <button class="nav-link active parent-tab-btn" data-bs-toggle="tab" data-bs-target="#parent-tab-academic" type="button" role="tab">
                    <i class="fas fa-graduation-cap"></i> 1. Academic Progress
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link parent-tab-btn" data-bs-toggle="tab" data-bs-target="#parent-tab-attendance" type="button" role="tab">
                    <i class="fas fa-calendar-check"></i> 2. Attendance Tracker
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link parent-tab-btn" data-bs-toggle="tab" data-bs-target="#parent-tab-exams" type="button" role="tab">
                    <i class="fas fa-file-invoice"></i> 3. Exams & Grades
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link parent-tab-btn" data-bs-toggle="tab" data-bs-target="#parent-tab-requests" type="button" role="tab">
                    <i class="fas fa-hotel"></i> 4. Campus & Hostel Logs
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link parent-tab-btn" data-bs-toggle="tab" data-bs-target="#parent-tab-ptm" type="button" role="tab">
                    <i class="fas fa-comments text-danger"></i> 5. PTM Reports Hub
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link parent-tab-btn" data-bs-toggle="tab" data-bs-target="#parent-tab-queries" type="button" role="tab">
                    <i class="fas fa-question-circle"></i> Child Query Support
                </button>
            </li>
        </ul>
    </div>
</div>

<!-- Tab Content -->
<div class="tab-content" id="parentDashboardTabsContent">
    
    <!-- 1. Academic Progress -->
    <div class="tab-pane fade show active" id="parent-tab-academic" role="tabpanel">
        <div class="row g-4 mb-4">
            <div class="col-12 col-md-4">
                <div class="metric-card">
                    <div class="metric-icon saffron"><i class="fas fa-trophy"></i></div>
                    <div>
                        <div class="text-muted small fw-bold">CHILD ACADEMIC LEVEL</div>
                        <h4 class="fw-bold text-dark mb-0">Level {{ $student->level ?? 1 }}</h4>
                        <span class="small text-muted">{{ $student->xp ?? 0 }} Total XP Earned</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="metric-card">
                    <div class="metric-icon blue"><i class="fas fa-book-open"></i></div>
                    <div>
                        <div class="text-muted small fw-bold">ENROLLED COURSES</div>
                        <h4 class="fw-bold text-dark mb-0">{{ $enrollments->count() }} Active</h4>
                        <span class="small text-muted">Core Academic Curriculum</span>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <div class="metric-card">
                    <div class="metric-icon green"><i class="fas fa-star"></i></div>
                    <div>
                        <div class="text-muted small fw-bold">COMPLETED MODULES</div>
                        <h4 class="fw-bold text-dark mb-0">
                            {{ $enrollments->where('progress_percent', 100)->count() }} Completed
                        </h4>
                        <span class="small text-muted">100% finished syllabus</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-book text-primary me-2"></i> Child's Enrolled Course Syllabus Progress</h5>
            </div>
            <div class="card-body px-4 pb-4">
                @if($enrollments->isEmpty())
                    <div class="alert alert-warning py-3 mb-0 fw-semibold"><i class="fas fa-exclamation-triangle me-2"></i> No active course enrollments found for this student.</div>
                @else
                    <div class="table-responsive border rounded-3 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="40%">Course Title</th>
                                    <th width="30%">Progress Bar</th>
                                    <th width="15%" class="text-center">XP Level</th>
                                    <th width="15%" class="text-center">Syllabus Access</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($enrollments as $enr)
                                    <tr>
                                        <td>
                                            <div class="fw-bold text-dark">{{ $enr->course->title ?? 'N/A' }}</div>
                                            <span class="small text-muted">Faculty: {{ $enr->course->faculty->name ?? 'Campus Faculty' }}</span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <div class="progress rounded-pill flex-grow-1" style="height: 8px; background: #f1f5f9;">
                                                    <div class="progress-bar rounded-pill bg-success" style="width: {{ $enr->progress_percent ?? 0 }}%;"></div>
                                                </div>
                                                <span class="small fw-bold text-dark">{{ $enr->progress_percent ?? 0 }}%</span>
                                            </div>
                                        </td>
                                        <td class="text-center fw-bold text-primary">{{ $enr->xp_earned ?? 0 }} XP</td>
                                        <td class="text-center">
                                            <a href="/courses/{{ $enr->course_id }}" class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold"><i class="fas fa-eye me-1"></i> Read Materials</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 2. Attendance Tracker -->
    <div class="tab-pane fade" id="parent-tab-attendance" role="tabpanel">
        <div class="row g-4">
            <div class="col-12 col-md-4">
                <div class="card border-0 shadow-sm rounded-4 mb-4 text-center p-4">
                    <h5 class="fw-bold text-dark mb-4"><i class="fas fa-percent text-saffron me-1"></i> Total Ratio</h5>
                    <div class="d-flex justify-content-center mb-3">
                        <div class="attendance-circle animate-pulse" style="--percentage: {{ $attendancePercentage }}">
                            <div class="attendance-percentage">{{ $attendancePercentage }}%</div>
                        </div>
                    </div>
                    @if($attendancePercentage < 75)
                        <div class="alert alert-danger py-2 mb-0 mt-3 small fw-bold"><i class="fas fa-exclamation-triangle"></i> ATTENDANCE CRITICAL: Below 75% limit!</div>
                    @else
                        <div class="alert alert-success py-2 mb-0 mt-3 small fw-bold"><i class="fas fa-check-circle"></i> Good Attendance Status</div>
                    @endif
                </div>
            </div>

            <div class="col-12 col-md-8">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-list text-primary me-2"></i> Attendance Log Summary</h5>
                        <p class="text-muted small mb-0 mt-1">Total recorded classes: <strong>{{ $totalClasses }}</strong> | Present: <strong>{{ $presentClasses }}</strong></p>
                    </div>
                    <div class="card-body px-4 pb-4">
                        @if($attendances->isEmpty())
                            <div class="alert alert-warning py-3 mb-0 fw-semibold"><i class="fas fa-exclamation-triangle me-2"></i> No attendance entries recorded yet for this term.</div>
                        @else
                            <div class="table-responsive border rounded-3 overflow-hidden" style="max-height: 300px; overflow-y: auto;">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light sticky-top">
                                        <tr>
                                            <th>Date</th>
                                            <th>Course / Subject</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($attendances->sortByDesc('date') as $att)
                                            <tr>
                                                <td class="fw-semibold">{{ date('d M Y', strtotime($att->date)) }}</td>
                                                <td>{{ $att->course->title ?? 'Class Session' }}</td>
                                                <td class="text-center">
                                                    @if($att->status === 'present')
                                                        <span class="badge bg-success-subtle text-success px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.75rem;">Present</span>
                                                    @elseif($att->status === 'late')
                                                        <span class="badge bg-warning-subtle text-warning px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.75rem;">Late</span>
                                                    @else
                                                        <span class="badge bg-danger-subtle text-danger px-3 py-1.5 rounded-pill fw-bold text-uppercase" style="font-size: 0.75rem;">Absent</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. Exams & Grades -->
    <div class="tab-pane fade" id="parent-tab-exams" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-file-invoice-dollar text-primary me-2"></i> University Examination Results</h5>
                <p class="text-muted small mb-0 mt-1">Official gradesheets generated directly from BAPS Innovation Campus Exam Center.</p>
            </div>
            <div class="card-body px-4 pb-4">
                @if($results->isEmpty())
                    <div class="alert alert-warning py-3 mb-0 fw-semibold"><i class="fas fa-exclamation-triangle me-2"></i> No published examination results found for this student.</div>
                @else
                    <div class="table-responsive border rounded-3 overflow-hidden mb-3">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Subject / Course</th>
                                    <th class="text-center">Internal (60)</th>
                                    <th class="text-center">External (40)</th>
                                    <th class="text-center">Total (100)</th>
                                    <th class="text-center">Grade</th>
                                    <th class="text-center">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $res)
                                    <tr>
                                        <td class="fw-bold text-dark">{{ $res->course->title ?? 'N/A' }}</td>
                                        <td class="text-center fw-bold">{{ $res->internal_marks ?? 0 }}</td>
                                        <td class="text-center fw-bold">{{ $res->external_marks_final ?? 0 }}</td>
                                        <td class="text-center fw-bold text-primary">{{ $res->total_obtained ?? 0 }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-primary px-3 py-1.5 rounded-3 fw-bold fs-6">{{ $res->grade }}</span>
                                        </td>
                                        <td class="text-center small text-muted">{{ $res->remarks ?: 'No remarks' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end">
                        <a href="/exam/results?print=1" target="_blank" class="btn btn-dark fw-bold rounded-pill px-4 shadow-sm"><i class="fas fa-print me-2"></i> Print Official Report Card</a>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-id-badge text-primary me-2"></i> Admit Card Verification Status</h5>
            </div>
            <div class="card-body px-4 pb-4">
                @if(!$admitCard)
                    <div class="alert alert-info py-3 mb-0 fw-semibold"><i class="fas fa-info-circle me-2"></i> Child has not applied for current semester admit card yet.</div>
                @else
                    <div class="p-3 border rounded-3 bg-light d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <h6 class="fw-bold text-dark mb-1">Admit Card: {{ $admitCard->academic_term ?: '2026 Semester' }}</h6>
                            <span class="small text-muted">Status: <strong>{{ ucfirst($admitCard->status) }}</strong></span>
                        </div>
                        <div>
                            @if(strtolower($admitCard->status) === 'published')
                                <a href="/exam/admit-card" target="_blank" class="btn btn-success fw-bold btn-sm rounded-pill px-4"><i class="fas fa-download me-1"></i> View Admit Card</a>
                            @else
                                <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold"><i class="fas fa-clock me-1"></i> Awaiting Verification</span>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 4. Campus & Hostel Logs -->
    <div class="tab-pane fade" id="parent-tab-requests" role="tabpanel">
        <div class="row g-4 mb-4">
            <!-- Submit Gatepass -->
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-door-open text-danger me-2"></i> Submit Outing Gatepass Request</h5>
                        <p class="text-muted small mb-0 mt-1">Authorize outing permissions directly on behalf of your child.</p>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <form action="/parent/gatepass" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Outing Destination</label>
                                <input type="text" name="destination" class="form-control" placeholder="e.g. Swaminarayan Mandir, Atladara" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-bold">Out-Time</label>
                                    <input type="datetime-local" name="out_time" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-bold">Expected In-Time</label>
                                    <input type="datetime-local" name="in_time" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Reason for Gatepass</label>
                                <textarea name="reason" class="form-control" rows="2" placeholder="Describe the reason for campus outing" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 fw-bold rounded-3"><i class="fas fa-paper-plane me-1"></i> Submit Parent Gatepass</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Submit Leave -->
            <div class="col-12 col-md-6">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-plane-departure text-success me-2"></i> Submit Hostel Leave Request</h5>
                        <p class="text-muted small mb-0 mt-1">Submit long leaves or home visits for hostel rector review.</p>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <form action="/parent/leave" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Leave Type</label>
                                <select name="leave_type" class="form-select" required>
                                    <option value="Home Visit">Home Visit / Home Town</option>
                                    <option value="Medical Emergency">Medical Leave</option>
                                    <option value="Social Event">Social / Family Event</option>
                                    <option value="Academic Project">Academic Project Outreach</option>
                                </select>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-bold">Start Date</label>
                                    <input type="date" name="start_date" class="form-control" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label text-muted small fw-bold">End Date</label>
                                    <input type="date" name="end_date" class="form-control" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Detailed Reason</label>
                                <textarea name="reason" class="form-control" rows="2" placeholder="Provide reason for hostel leave" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-success w-100 fw-bold rounded-3"><i class="fas fa-check-circle me-1"></i> Submit Parent Leave Authorization</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logs tables -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-door-closed text-primary me-2"></i> Gatepass Logs</h5>
            </div>
            <div class="card-body px-4 pb-4">
                @if($gatepasses->isEmpty())
                    <div class="alert alert-light border py-3 mb-0 text-center text-muted small fw-bold">No gatepass requests recorded.</div>
                @else
                    <div class="table-responsive border rounded-3 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Reason</th>
                                    <th>Destination</th>
                                    <th>Out-Time</th>
                                    <th>In-Time</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gatepasses as $gp)
                                    <tr>
                                        <td>{{ $gp->reason }}</td>
                                        <td>{{ $gp->destination }}</td>
                                        <td class="small">{{ date('d-M-Y H:i', strtotime($gp->out_time)) }}</td>
                                        <td class="small">{{ date('d-M-Y H:i', strtotime($gp->in_time)) }}</td>
                                        <td class="text-center">
                                            @if($gp->status === 'approved')
                                                <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold">APPROVED</span>
                                            @elseif($gp->status === 'pending')
                                                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">PENDING</span>
                                            @else
                                                <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold">REJECTED</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0"><i class="fas fa-plane-departure text-primary me-2"></i> Leave Logs</h5>
            </div>
            <div class="card-body px-4 pb-4">
                @if($leaves->isEmpty())
                    <div class="alert alert-light border py-3 mb-0 text-center text-muted small fw-bold">No leave requests recorded.</div>
                @else
                    <div class="table-responsive border rounded-3 overflow-hidden">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Type</th>
                                    <th>Reason</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th class="text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $lv)
                                    <tr>
                                        <td><strong>{{ $lv->leave_type }}</strong></td>
                                        <td>{{ $lv->reason }}</td>
                                        <td class="small">{{ date('d-M-Y', strtotime($lv->start_date)) }}</td>
                                        <td class="small">{{ date('d-M-Y', strtotime($lv->end_date)) }}</td>
                                        <td class="text-center">
                                            @if($lv->status === 'approved')
                                                <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold">APPROVED</span>
                                            @elseif($lv->status === 'pending')
                                                <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">PENDING</span>
                                            @else
                                                <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold">REJECTED</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- 5. PTM Reports & Reply Hub -->
    <div class="tab-pane fade" id="parent-tab-ptm" role="tabpanel">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                <h5 class="fw-bold text-dark mb-0">
                    <i class="fas fa-comments text-danger me-2"></i> PTM Child Reports & Feedback Hub
                </h5>
                <p class="text-muted small mb-0 mt-1">Direct feedback from Admin, Dean, HODs, CCs, and course instructors.</p>
            </div>
            <div class="card-body px-4 pb-4">
                @if($ptmReports->isEmpty())
                    <div class="alert alert-info py-4 text-center fw-semibold mb-0">
                        <i class="fas fa-info-circle fs-3 d-block mb-2 text-primary"></i>
                        No PTM Reports have been issued yet. The student's academic and behavioral standing is in good order!
                    </div>
                @else
                    <div class="d-flex flex-column gap-4">
                        @foreach($ptmReports as $rep)
                            <div class="ptm-card border rounded-4 overflow-hidden">
                                <div class="bg-light p-3 border-bottom d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <span class="badge bg-danger text-white px-3 py-1 rounded-pill text-uppercase small" style="font-size: 0.7rem;">{{ $rep->category }} Report</span>
                                        <span class="ms-2 text-dark fw-bold">{{ $rep->subject }}</span>
                                    </div>
                                    <div class="small text-muted">
                                        <i class="fas fa-calendar-alt"></i> {{ date('d-M-Y H:i', strtotime($rep->created_at)) }}
                                    </div>
                                </div>
                                <div class="p-3">
                                    <!-- Sender details -->
                                    <div class="d-flex align-items-center gap-2 mb-3">
                                        <div class="avatar-circle bg-saffron text-white shadow-sm" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                            {{ strtoupper(substr($rep->created_by_name, 0, 2)) }}
                                        </div>
                                        <span class="small fw-bold text-dark">{{ $rep->created_by_name }} ({{ ucfirst($rep->created_by_role) }})</span>
                                        <span class="badge bg-light text-secondary border small text-lowercase ms-2">{{ $rep->academic_term }}</span>
                                    </div>

                                    <div class="p-3 rounded bg-light border text-muted small mb-3" style="white-space: pre-wrap;">{{ $rep->report_content }}</div>

                                    <!-- Parent Reply Section -->
                                    <div class="border-top pt-3">
                                        @if($rep->parent_reply)
                                            <div class="p-3 rounded bg-warning-subtle border border-warning-subtle small text-dark mb-2">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-bold"><i class="fas fa-user-shield text-danger me-1"></i> Your Reply:</span>
                                                    <span class="text-muted small">{{ date('d-M-Y H:i', strtotime($rep->parent_replied_at)) }}</span>
                                                </div>
                                                <div>{{ $rep->parent_reply }}</div>
                                            </div>
                                        @else
                                            <form action="/parent/ptm/{{ $rep->id }}/reply" method="POST">
                                                @csrf
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-dark"><i class="fas fa-reply me-1"></i> Submit Parent Response / Action Plan</label>
                                                    <textarea name="reply" class="form-control rounded-3 text-muted small" rows="2" placeholder="Type your reply to the faculty report..." required></textarea>
                                                </div>
                                                <button type="submit" class="btn btn-sm btn-danger fw-bold rounded-pill px-4 shadow-sm"><i class="fas fa-paper-plane me-1"></i> Submit Reply</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Child Query Support -->
    <div class="tab-pane fade" id="parent-tab-queries" role="tabpanel">
        <div class="row g-4">
            <!-- Submit Ticket -->
            <div class="col-12 col-md-5">
                <div class="card border-0 shadow-sm rounded-4">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-envelope-open text-primary me-2"></i> Submit Support Query</h5>
                        <p class="text-muted small mb-0 mt-1">Submit support tickets to campus coordinators or HODs directly.</p>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <form action="/parent/query" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Query Type</label>
                                <select name="query_type" class="form-select" required>
                                    <option value="Academic">Academic Support</option>
                                    <option value="Fee Waiver">Fee Waiver / Refund Inquiry</option>
                                    <option value="Hostel Curfew">Hostel Curfew Adjustment</option>
                                    <option value="Disciplinary Inquiry">Disciplinary Inquiry</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Target Staff Role</label>
                                <select name="assigned_role" class="form-select" required>
                                    <option value="cr">Class Representative (CR)</option>
                                    <option value="faculty">Course Faculty Lecturer</option>
                                    <option value="hod">Head of Department (HOD)</option>
                                    <option value="dean">Dean / Academic Lead</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Subject Summary</label>
                                <input type="text" name="subject" class="form-control" placeholder="e.g. Leave authorization request due to sickness" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted small fw-bold">Description Details</label>
                                <textarea name="description" class="form-control" rows="3" placeholder="Provide full context here..." required></textarea>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold rounded-3 shadow-sm" style="background-color: var(--baps-saffron); border-color: var(--baps-saffron);"><i class="fas fa-paper-plane me-1"></i> Submit Query Ticket</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Ticket Logs -->
            <div class="col-12 col-md-7">
                <div class="card border-0 shadow-sm rounded-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-ticket-alt text-primary me-2"></i> Query Ticket History</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        @if($queries->isEmpty())
                            <div class="alert alert-light border py-3 mb-0 text-center text-muted small fw-bold">No query tickets filed yet.</div>
                        @else
                            <div class="table-responsive border rounded-3 overflow-hidden">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Subject</th>
                                            <th>Type</th>
                                            <th>Staff Role</th>
                                            <th class="text-center">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($queries as $qry)
                                            <tr>
                                                <td>
                                                    <div class="fw-bold text-dark">{{ $qry->subject }}</div>
                                                    <span class="small text-muted">{{ date('d-M-Y', strtotime($qry->created_at)) }}</span>
                                                </td>
                                                <td><span class="badge bg-light text-dark border">{{ $qry->query_type }}</span></td>
                                                <td><span class="text-muted small fw-bold text-uppercase">{{ $qry->assigned_role }}</span></td>
                                                <td class="text-center">
                                                    @if($qry->status === 'resolved')
                                                        <span class="badge bg-success text-white px-3 py-1.5 rounded-pill fw-bold">RESOLVED</span>
                                                    @elseif($qry->status === 'pending')
                                                        <span class="badge bg-warning text-dark px-3 py-1.5 rounded-pill fw-bold">PENDING</span>
                                                    @else
                                                        <span class="badge bg-danger text-white px-3 py-1.5 rounded-pill fw-bold">OPEN</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

@endsection
