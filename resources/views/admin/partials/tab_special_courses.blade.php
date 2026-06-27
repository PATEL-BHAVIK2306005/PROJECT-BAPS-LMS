@php
    $role = session('user_role');
    $staffId = session('staff_id');
    
    // Fetch all courses
    $allCourses = \App\Models\Course::with(['faculty', 'department'])->get();
    
    // Fetch pending enrollments (extra course requests)
    $pendingEnrollRequests = \App\Models\Enrollment::with(['user', 'course'])
        ->where('status', 'pending')
        ->orderBy('created_at', 'desc')
        ->get();
    
    $totalCoursesCount = $allCourses->count();
    $pendingRequestsCount = $pendingEnrollRequests->count();
    
    // Count special configurations
    $specialConfigsCount = 0;
    foreach($allCourses as $c) {
        $isSpecial = !empty($c->password) || 
                     $c->class_mode !== 'offline' || 
                     $c->type === 'pbl' || 
                     $c->approval_status !== 'approved' || 
                     in_array(strtolower($c->program), ['special', 'extra']);
        if ($isSpecial) {
            $specialConfigsCount++;
        }
    }

    // Optimize enrollment counting via grouping
    $enrollmentCounts = \App\Models\Enrollment::where('status', 'approved')
        ->select('course_id', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
        ->groupBy('course_id')
        ->pluck('count', 'course_id')
        ->toArray();
@endphp

<div class="tab-pane fade" id="tab-special-courses" role="tabpanel">
    
    <!-- Header Card -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #11111b !important; border-left: 5px solid #ffd700 !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; background: #1e1e2e !important; color: #ffd700 !important;">
                        <i class="fas fa-puzzle-piece"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #ffd700 !important;">
                            Special & Extra Courses Dashboard
                            <span class="badge bg-saffron text-white px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px; background-color: var(--baps-saffron);">Management Desk</span>
                        </h4>
                        <div class="small fw-semibold" style="color: #ffffff !important; opacity: 0.85;">Monitor academic overrides, handle manual enrollment requests, and manage course configurations.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Row -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="stat-card" onclick="filterSpecialTab('all')" style="cursor: pointer;">
                <div class="stat-icon primary"><i class="fas fa-book"></i></div>
                <div>
                    <div class="stat-number">{{ $totalCoursesCount }}</div>
                    <div class="stat-label">Total System Courses</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" onclick="filterSpecialTab('special')" style="cursor: pointer;">
                <div class="stat-icon danger"><i class="fas fa-sliders-h" style="color: #ef4444;"></i></div>
                <div>
                    <div class="stat-number">{{ $specialConfigsCount }}</div>
                    <div class="stat-label">Special Configurations</div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card" onclick="scrollToPendingRequests()" style="cursor: pointer;">
                <div class="stat-icon warning"><i class="fas fa-hourglass-half" style="color: var(--baps-saffron);"></i></div>
                <div>
                    <div class="stat-number">{{ $pendingRequestsCount }}</div>
                    <div class="stat-label">Pending Extra Requests</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Success & Error Alert Feeds -->
    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 fw-bold">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 fw-bold">
            <i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- 1. Pending Extra Course Requests Queue -->
        <div class="col-12">
            <div class="content-card">
                <div class="content-card-header">
                    <h5 class="content-card-title"><i class="fas fa-paper-plane text-warning"></i> Pending Extra Course Enrollment Requests</h5>
                    <span class="badge bg-warning text-dark border px-3 py-1 rounded-pill fw-semibold">{{ $pendingRequestsCount }} Requests</span>
                </div>
                
                @if($pendingRequestsCount > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Student Details</th>
                                    <th>Requested Course</th>
                                    <th>Program & Sem</th>
                                    <th>Date Requested</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingEnrollRequests as $enr)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $enr->name ?? $enr->user->name ?? 'Unknown Student' }}</div>
                                        <div class="small text-muted"><i class="far fa-envelope me-1"></i> {{ $enr->email ?? $enr->user->email ?? 'N/A' }}</div>
                                        <div class="small text-muted font-monospace mt-1"><span class="badge bg-light text-secondary border">Roll: {{ $enr->roll_no ?? $enr->user->enrollment_no ?? 'N/A' }}</span></div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-primary"><i class="fas fa-book me-1"></i> {{ $enr->course->title ?? 'Unknown Course' }}</div>
                                        <div class="small text-muted">ID: {{ $enr->course_id }}</div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $enr->program ?? 'Core' }}</div>
                                        <div class="small text-muted">Semester {{ $enr->semester ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="small text-dark">{{ $enr->created_at ? $enr->created_at->format('d-M-Y') : 'N/A' }}</div>
                                        <div class="x-small text-muted">{{ $enr->created_at ? $enr->created_at->diffForHumans() : '' }}</div>
                                    </td>
                                    <td class="text-end pe-4">
                                        <div class="d-flex justify-content-end gap-2">
                                            <!-- Reject Request -->
                                            <form action="/admin/approvals/enrollment/{{ $enr->id }}/process" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="action" value="reject">
                                                <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold" onclick="return confirm('Reject this course enrollment request?')">
                                                    <i class="fas fa-times me-1"></i> Reject
                                                </button>
                                            </form>
                                            <!-- Approve Request (Grant Access) -->
                                            <form action="/admin/approvals/enrollment/{{ $enr->id }}/process" method="POST" class="m-0">
                                                @csrf
                                                <input type="hidden" name="action" value="approve">
                                                <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                                    <i class="fas fa-check me-1"></i> Grant Access
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-double fa-3x text-success opacity-50 mb-3"></i>
                        <h6 class="fw-bold text-secondary">All Clear! No Pending Requests</h6>
                        <p class="small text-muted mb-0">No students are currently awaiting manual approval for extra/special courses.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 2. Course Configuration & Overrides Matrix -->
        <div class="col-12">
            <div class="content-card">
                <div class="content-card-header d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <h5 class="content-card-title"><i class="fas fa-cogs text-primary"></i> Course Configuration & Special Overrides Matrix</h5>
                    <div class="d-flex align-items-center gap-2">
                        <input type="text" id="specialCourseSearch" class="form-control form-control-sm rounded-pill bg-light border-0" placeholder="Search courses..." style="width: 250px;" onkeyup="filterSpecialCoursesTable()">
                    </div>
                </div>

                <div class="table-responsive mt-2">
                    <table class="table table-hover align-middle" id="specialCoursesTable">
                        <thead class="table-light">
                            <tr>
                                <th class="ps-4">Course Identity</th>
                                <th>Department & Program</th>
                                <th>Semester & Year</th>
                                <th>Course Settings</th>
                                <th>Approved Students</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($allCourses as $c)
                                @php
                                    $hasPassword = !empty($c->password);
                                    $isOnline = $c->class_mode !== 'offline';
                                    $isPbl = $c->type === 'pbl';
                                    $isNotApproved = $c->approval_status !== 'approved';
                                    $isSpecialProgram = in_array(strtolower($c->program), ['special', 'extra']);
                                    
                                    $isSpecialConfig = $hasPassword || $isOnline || $isPbl || $isNotApproved || $isSpecialProgram;
                                    $enrolledCount = $enrollmentCounts[$c->id] ?? 0;
                                @endphp
                                <tr data-is-special="{{ $isSpecialConfig ? '1' : '0' }}" style="{{ $isSpecialConfig ? 'background-color: rgba(249, 115, 22, 0.02);' : '' }}">
                                    <td class="ps-4 py-3">
                                        <div class="fw-bold text-dark">{{ $c->title }}</div>
                                        <div class="small text-muted">Instructor: {{ $c->instructor ?? $c->faculty->name ?? 'Faculty Coordinator' }}</div>
                                        <div class="small text-muted font-monospace mt-1"><span class="badge bg-light text-secondary border">Credits: {{ $c->credits ?? 'N/A' }}</span></div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">{{ $c->department->name ?? 'Global' }}</div>
                                        <div class="small text-muted">Program: <span class="fw-bold">{{ $c->program ?? 'Core' }}</span></div>
                                    </td>
                                    <td>
                                        <div class="fw-semibold text-dark">Semester {{ $c->semester ?? 'N/A' }}</div>
                                        <div class="small text-muted">Academic Year {{ $c->year ?? 'N/A' }}</div>
                                    </td>
                                    <td>
                                        <div class="d-flex flex-wrap gap-1.5">
                                            @if($hasPassword)
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill" title="Requires Password for Student Enrollment">
                                                    <i class="fas fa-lock me-1"></i> Pass Protected
                                                </span>
                                            @endif
                                            @if($isOnline)
                                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary rounded-pill" title="Digital mode of lectures (online/hybrid)">
                                                    <i class="fas fa-globe me-1"></i> {{ ucfirst($c->class_mode) }}
                                                </span>
                                            @endif
                                            @if($isPbl)
                                                <span class="badge bg-purple bg-opacity-10 text-purple border border-purple rounded-pill" title="Project-Based Learning Course">
                                                    <i class="fas fa-project-diagram me-1"></i> PBL Course
                                                </span>
                                            @endif
                                            @if($isSpecialProgram)
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning rounded-pill" title="Non-Standard cohort/academic program">
                                                    <i class="fas fa-crown me-1"></i> Special Program
                                                </span>
                                            @endif
                                            @if(!$isSpecialConfig)
                                                <span class="badge bg-light text-muted border rounded-pill">
                                                    <i class="fas fa-check-circle text-muted me-1"></i> Standard
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-dark font-monospace">{{ $enrolledCount }}</span>
                                        <span class="small text-muted d-block">Active Approved</span>
                                    </td>
                                    <td>
                                        @if($isNotApproved)
                                            <span class="badge bg-warning text-dark px-2.5 py-1 rounded-pill fw-bold" style="font-size:0.75rem;"><i class="fas fa-hourglass-half me-1"></i> Draft / Pending</span>
                                        @else
                                            <span class="badge bg-success text-white px-2.5 py-1 rounded-pill fw-bold" style="font-size:0.75rem;"><i class="fas fa-check-circle me-1"></i> Published</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function filterSpecialCoursesTable() {
    var input = document.getElementById("specialCourseSearch");
    var filter = input.value.toLowerCase();
    var table = document.getElementById("specialCoursesTable");
    var tr = table.getElementsByTagName("tr");

    for (var i = 1; i < tr.length; i++) {
        var tdTitle = tr[i].getElementsByTagName("td")[0];
        var tdDept = tr[i].getElementsByTagName("td")[1];
        var tdSettings = tr[i].getElementsByTagName("td")[3];
        
        if (tdTitle || tdDept || tdSettings) {
            var txtValueTitle = tdTitle.textContent || tdTitle.innerText;
            var txtValueDept = tdDept.textContent || tdDept.innerText;
            var txtValueSettings = tdSettings.textContent || tdSettings.innerText;
            
            if (txtValueTitle.toLowerCase().indexOf(filter) > -1 || 
                txtValueDept.toLowerCase().indexOf(filter) > -1 ||
                txtValueSettings.toLowerCase().indexOf(filter) > -1) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
}

window.filterSpecialTab = function(type) {
    var table = document.getElementById("specialCoursesTable");
    var tr = table.getElementsByTagName("tr");
    var searchInput = document.getElementById("specialCourseSearch");
    if (searchInput) searchInput.value = "";

    for (var i = 1; i < tr.length; i++) {
        var row = tr[i];
        var isSpecial = row.getAttribute("data-is-special");
        if (type === 'all') {
            row.style.display = "";
        } else if (type === 'special') {
            if (isSpecial === "1") {
                row.style.display = "";
            } else {
                row.style.display = "none";
            }
        }
    }
};

window.scrollToPendingRequests = function() {
    var element = document.querySelector(".content-card-header .fa-paper-plane");
    if (element) {
        element.closest(".content-card").scrollIntoView({ behavior: 'smooth', block: 'start' });
        // Briefly flash card border style to guide the user's attention
        var card = element.closest(".content-card");
        card.style.transition = "box-shadow 0.5s ease, border-color 0.5s ease";
        card.style.boxShadow = "0 0 25px rgba(249, 115, 22, 0.4)";
        card.style.borderColor = "var(--baps-saffron)";
        setTimeout(function() {
            card.style.boxShadow = "";
            card.style.borderColor = "";
        }, 1500);
    }
};
</script>
