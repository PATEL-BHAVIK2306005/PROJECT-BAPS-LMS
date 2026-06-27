@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-success"><i class="fas fa-chart-line me-2"></i> Report Section</h3>
    <a href="/admin" class="btn btn-outline-dark btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
</div>

<div class="alert alert-success border-0 shadow-sm fw-bold d-flex justify-content-between align-items-center">
    <div>
        <i class="fas fa-shield-alt me-2 text-dark"></i> Access Level: <span class="text-dark">200% System Privilege (Admin / Dean only)</span>
    </div>
    <button class="btn btn-sm btn-dark fw-bold" data-bs-toggle="modal" data-bs-target="#accessMatrixModal">
        <i class="fas fa-sitemap me-1"></i> View Hierarchy Matrix
    </button>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="glass-card p-4 border-0 shadow-sm border-success border-top border-4">
            <h6 class="fw-bold text-muted text-uppercase mb-3">Academic Base</h6>
            <div class="d-flex justify-content-between align-items-center">
                <i class="fas fa-user-graduate fa-3x text-success opacity-50"></i>
                <h2 class="fw-bold mb-0 text-dark">{{ $metrics['total_students'] }}</h2>
            </div>
            <p class="small text-muted mt-2 mb-0">Total Registered Students</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card p-4 border-0 shadow-sm border-primary border-top border-4">
            <h6 class="fw-bold text-muted text-uppercase mb-3">Faculty & Depts</h6>
            <div class="d-flex justify-content-between align-items-center">
                <i class="fas fa-chalkboard-teacher fa-3x text-primary opacity-50"></i>
                <div class="text-end">
                    <h2 class="fw-bold mb-0 text-dark">{{ $metrics['total_staff'] }}</h2>
                    <small class="fw-bold text-primary">across {{ $metrics['total_departments'] }} Depts</small>
                </div>
            </div>
            <p class="small text-muted mt-2 mb-0">Total Institutional Staff</p>
        </div>
    </div>
    <div class="col-md-4">
        <div class="glass-card p-4 border-0 shadow-sm border-warning border-top border-4">
            <h6 class="fw-bold text-muted text-uppercase mb-3">Course Enrollments</h6>
            <div class="d-flex justify-content-between align-items-center">
                <i class="fas fa-book-open fa-3x text-warning opacity-50"></i>
                <div class="text-end">
                    <h2 class="fw-bold mb-0 text-dark">{{ $metrics['total_enrollments'] }}</h2>
                    <small class="fw-bold text-warning">in {{ $metrics['total_courses'] }} Courses</small>
                </div>
            </div>
            <p class="small text-muted mt-2 mb-0">Active Course Enrollments</p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white fw-bold py-3">
                <i class="fas fa-file-invoice me-2 text-success"></i> Comprehensive Institutional Reports
            </div>
            <div class="card-body">
                <p class="text-muted small">Select a category below to generate detailed CSV/PDF reports based on current database metrics.</p>
                <div class="row g-3">
                    <div class="col-md-3">
                        <button class="btn btn-outline-success w-100 py-3 fw-bold" onclick="alert('Student Demographics Report Generation initiated...')">
                            <i class="fas fa-users mb-2 fa-2x d-block"></i>
                            Student Demographics
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-primary w-100 py-3 fw-bold" onclick="alert('Staff Workload Report Generation initiated...')">
                            <i class="fas fa-briefcase mb-2 fa-2x d-block"></i>
                            Staff Workloads
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-warning w-100 py-3 fw-bold" onclick="alert('Academic Performance Report Generation initiated...')">
                            <i class="fas fa-chart-pie mb-2 fa-2x d-block"></i>
                            Academic Performance
                        </button>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-info w-100 py-3 fw-bold" onclick="alert('System Audit Log Report Generation initiated...')">
                            <i class="fas fa-clipboard-list mb-2 fa-2x d-block"></i>
                            System Audit Logs
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Access Hierarchy Matrix Modal -->
<div class="modal fade" id="accessMatrixModal" tabindex="-1" aria-labelledby="accessMatrixModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold" id="accessMatrixModalLabel"><i class="fas fa-shield-alt me-2 text-warning"></i> Institutional Access Matrix & Privilege Allocation</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 bg-light">
                <div class="table-responsive">
                    <table class="table table-hover table-bordered mb-0 align-middle">
                        <thead class="table-dark sticky-top" style="z-index: 1;">
                            <tr>
                                <th width="20%">System Role</th>
                                <th width="15%" class="text-center">Privilege Level</th>
                                <th width="65%">Detailed Description & Access Rights</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr>
                                <td class="fw-bold text-success"><i class="fas fa-user-shield me-2"></i> Administrator</td>
                                <td class="text-center fw-bold fs-5 text-success">200%</td>
                                <td class="small text-muted">
                                    <strong class="text-dark">Omnipotent System Control:</strong> Unrestricted God Mode access. Controls the BAPS Master Archive, Add Function GUI Injector, all reporting metrics, user creation, deep database deletion, password overrides, and final system structural settings. Includes all Dean and lower-level permissions.
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-success"><i class="fas fa-user-tie me-2"></i> Dean (Dept Head)</td>
                                <td class="text-center fw-bold fs-5 text-success">200%</td>
                                <td class="small text-muted">
                                    <strong class="text-dark">Executive Academic Control:</strong> Full institutional visibility. Access to the exclusive Report Section, cross-department academic modifications, faculty assignments, bulk grading approvals, and high-level structural oversight without deep database/technical injection rights.
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold" style="color: #fd7e14;"><i class="fas fa-building me-2"></i> HOD (Head of Dept)</td>
                                <td class="text-center fw-bold fs-5" style="color: #fd7e14;">100%</td>
                                <td class="small text-muted">
                                    <strong class="text-dark">Departmental Authority:</strong> Complete control within their specific assigned department. Approves student enrollments, controls departmental staff assignments, manages department-wide courses, views department analytics, and handles final grades for their sector.
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-purple"><i class="fas fa-chalkboard-teacher me-2"></i> Fac (Lecturer & Coord)</td>
                                <td class="text-center fw-bold fs-5 text-purple">75%</td>
                                <td class="small text-muted">
                                    <strong class="text-dark">Hybrid Command:</strong> Elevated faculty access. Responsible for standard teaching (courses, lessons, quizzes) while holding extended administrative rights to coordinate batches, manage bulk enrollments, and approve minor gatepass/leave requests.
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-primary"><i class="fas fa-project-diagram me-2"></i> Coordinator</td>
                                <td class="text-center fw-bold fs-5 text-primary">60%</td>
                                <td class="small text-muted">
                                    <strong class="text-dark">Operational Management:</strong> Focuses on student logistics. Access to Bulk Enrollment, Student Panels, and Talent Hub. Responsible for mapping students to courses and handling scheduling, without direct course content authorship rights.
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-info"><i class="fas fa-laptop-code me-2"></i> Fac (Lecturer & Lab)</td>
                                <td class="text-center fw-bold fs-5 text-info">50%</td>
                                <td class="small text-muted">
                                    <strong class="text-dark">Standard Academic Execution:</strong> Direct student engagement. Can create courses, assign lessons, manage quizzes, grade assignments, and track analytics for the specific courses they are officially assigned to teach.
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-warning text-dark"><i class="fas fa-star me-2"></i> CR (Class Rep)</td>
                                <td class="text-center fw-bold fs-5 text-warning text-dark">25%</td>
                                <td class="small text-muted">
                                    <strong class="text-dark">Student Leadership:</strong> Elevated student access. Can manage class-level logistics, view peer enrollments, access the Talent Hub, and assist in basic coordination tasks. Restricted from grading or exam creation.
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-secondary"><i class="fas fa-user-graduate me-2"></i> Student</td>
                                <td class="text-center fw-bold fs-5 text-secondary">10%</td>
                                <td class="small text-muted">
                                    <strong class="text-dark">Consumer Access:</strong> End-user experience. Limited to viewing enrolled courses, taking quizzes, submitting assignments, downloading certificates, and managing their personal learning profile and attendance.
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer bg-white border-0">
                <span class="small text-muted fw-bold"><i class="fas fa-info-circle text-primary me-1"></i> Percentages represent the scope of accessible endpoints and CRUD operations relative to the entire system capability.</span>
                <button type="button" class="btn btn-dark btn-sm fw-bold px-4" data-bs-dismiss="modal">Acknowledge</button>
            </div>
        </div>
    </div>
</div>

@endsection
