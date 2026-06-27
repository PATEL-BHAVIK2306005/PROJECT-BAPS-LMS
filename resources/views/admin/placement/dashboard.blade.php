@extends('layouts.app')
@section('content')

<style>
    .glass-stat-card {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.9) 0%, rgba(255, 255, 255, 0.7) 100%);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.8);
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(99, 102, 241, 0.12);
        border-color: rgba(99, 102, 241, 0.2);
    }
    .dark-mode .glass-stat-card {
        background: linear-gradient(135deg, rgba(30, 41, 59, 0.9) 0%, rgba(15, 23, 42, 0.8) 100%);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    .dark-mode .glass-stat-card:hover {
        border-color: rgba(99, 102, 241, 0.3);
    }
    .tab-trigger {
        font-weight: 600;
        border-radius: 12px !important;
        padding: 10px 24px !important;
        transition: all 0.2s ease;
    }
    .tab-trigger.active {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(99, 102, 241, 0.35);
    }
    .nav-pills .nav-link:not(.active) {
        color: #64748b;
    }
    .dark-mode .nav-pills .nav-link:not(.active) {
        color: #94a3b8;
    }
    .eligibility-badge {
        font-size: 0.75rem;
        padding: 5px 12px;
        border-radius: 30px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .badge-eligible {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .badge-ineligible {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .drive-status-badge {
        font-size: 0.75rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 8px;
    }
    .status-open { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
    .status-ongoing { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
    .status-completed { background: rgba(100, 116, 139, 0.15); color: #64748b; }
    .status-upcoming { background: rgba(16, 185, 129, 0.15); color: #10b981; }
</style>

<div class="container-fluid py-4">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center bg-dark rounded-4 p-4 text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #312e81 0%, #1e1b4b 100%);">
                <div class="position-relative z-1">
                    <h2 class="fw-bold mb-1"><i class="fas fa-briefcase text-warning me-2"></i> Placement & Training Cell</h2>
                    <p class="mb-0 opacity-75">Corporate Relations, Technical Training Trackers, and Career Analytics Dashboard</p>
                </div>
                <i class="fas fa-graduation-cap fa-6x position-absolute end-0 bottom-0 opacity-10 me-4 mb-n2"></i>
            </div>
        </div>
    </div>

    <!-- Quick Statistics Cards -->
    <div class="row g-4 mb-5">
        <div class="col-md-4 col-lg-2">
            <div class="glass-stat-card p-4 text-center">
                <div class="rounded-circle bg-primary-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: rgba(79, 70, 229, 0.1);">
                    <i class="fas fa-user-graduate text-primary fs-4"></i>
                </div>
                <h5 class="text-muted small fw-bold text-uppercase mb-1">Total Students</h5>
                <h3 class="fw-bold text-dark mb-0">{{ $totalRegistered }}</h3>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="glass-stat-card p-4 text-center">
                <div class="rounded-circle bg-success-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.1);">
                    <i class="fas fa-check-double text-success fs-4"></i>
                </div>
                <h5 class="text-muted small fw-bold text-uppercase mb-1">Eligible Students</h5>
                <h3 class="fw-bold text-dark mb-0">{{ $totalEligible }}</h3>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="glass-stat-card p-4 text-center">
                <div class="rounded-circle bg-info-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: rgba(6, 182, 212, 0.1);">
                    <i class="fas fa-handshake text-info fs-4"></i>
                </div>
                <h5 class="text-muted small fw-bold text-uppercase mb-1">Placed Offfers</h5>
                <h3 class="fw-bold text-dark mb-0">{{ $totalPlaced }}</h3>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="glass-stat-card p-4 text-center">
                <div class="rounded-circle bg-warning-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: rgba(245, 158, 11, 0.1);">
                    <i class="fas fa-percent text-warning fs-4"></i>
                </div>
                <h5 class="text-muted small fw-bold text-uppercase mb-1">Placement Rate</h5>
                <h3 class="fw-bold text-dark mb-0">{{ $placementRate }}%</h3>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="glass-stat-card p-4 text-center">
                <div class="rounded-circle bg-danger-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.1);">
                    <i class="fas fa-award text-danger fs-4"></i>
                </div>
                <h5 class="text-muted small fw-bold text-uppercase mb-1">Average CTC</h5>
                <h3 class="fw-bold text-dark mb-0">{{ $avgPackage }}</h3>
            </div>
        </div>
        <div class="col-md-4 col-lg-2">
            <div class="glass-stat-card p-4 text-center">
                <div class="rounded-circle bg-dark-subtle d-inline-flex align-items-center justify-content-center mb-3" style="width: 50px; height: 50px; background: rgba(100, 116, 139, 0.1);">
                    <i class="fas fa-star text-warning fs-4"></i>
                </div>
                <h5 class="text-muted small fw-bold text-uppercase mb-1">Highest CTC</h5>
                <h3 class="fw-bold text-dark mb-0">{{ $highestPackage }}</h3>
            </div>
        </div>
    </div>

    <!-- Navigation Pills (Consolidated tab switcher) -->
    <div class="d-flex justify-content-center mb-4">
        <ul class="nav nav-pills bg-white shadow-sm rounded-4 p-2 gap-2 border border-light" id="placementTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link tab-trigger active" id="drives-tab" data-bs-toggle="pill" data-bs-target="#tab-drives" type="button" role="tab">
                    <i class="fas fa-bullhorn me-2"></i>Job Drives Portal
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link tab-trigger" id="training-tab" data-bs-toggle="pill" data-bs-target="#tab-training" type="button" role="tab">
                    <i class="fas fa-laptop-code me-2"></i>Training & HackerRank Tracker
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link tab-trigger" id="analytics-tab" data-bs-toggle="pill" data-bs-target="#tab-analytics" type="button" role="tab">
                    <i class="fas fa-chart-line me-2"></i>Placement Analytics
                </button>
            </li>
        </ul>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content" id="placementTabsContent">
        
        <!-- Tab 1: Drives Portal -->
        <div class="tab-pane fade show active" id="tab-drives" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold m-0"><i class="fas fa-business-time text-primary me-2"></i> Active Placement Drives</h5>
                    <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#addDriveModal">
                        <i class="fas fa-plus-circle me-1"></i> Post New Drive
                    </button>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4">Company Name</th>
                                    <th>Target Role</th>
                                    <th>Scheduled Date</th>
                                    <th>Package Package</th>
                                    <th>Location</th>
                                    <th>Registered Students</th>
                                    <th class="text-end pe-4">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($drives as $drive)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle-sm bg-primary-subtle text-primary me-3" style="width:36px; height:36px; border-radius:10px; display:flex; align-items:center; justify-content:center; font-weight:700;">
                                                {{ substr($drive['company'], 0, 2) }}
                                            </div>
                                            <div class="fw-bold text-dark">{{ $drive['company'] }}</div>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-secondary-subtle text-dark border px-2 py-1 rounded">{{ $drive['role'] }}</span></td>
                                    <td class="text-muted small">{{ \Carbon\Carbon::parse($drive['date'])->format('M d, Y') }}</td>
                                    <td class="fw-bold text-success">{{ $drive['package'] }}</td>
                                    <td class="small text-muted"><i class="fas fa-map-marker-alt me-1 text-danger"></i> {{ $drive['location'] }}</td>
                                    <td class="fw-bold text-center">{{ $drive['registered_students'] }}</td>
                                    <td class="text-end pe-4">
                                        <span class="drive-status-badge status-{{ strtolower($drive['status']) }}">{{ $drive['status'] }}</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Training & HackerRank Tracker -->
        <div class="tab-pane fade" id="tab-training" role="tabpanel">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0"><i class="fas fa-users-cog text-success me-2"></i> Student Eligibility & Training Tracker</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light text-muted small">
                                <tr>
                                    <th class="ps-4">Student Details</th>
                                    <th>Enrollment No</th>
                                    <th>CGPA</th>
                                    <th>Backlogs</th>
                                    <th>IPDC HackerRank Solved</th>
                                    <th>XP Points</th>
                                    <th class="text-end pe-4">Placement Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <td class="ps-4 py-3">
                                        <div class="d-flex align-items-center">
                                            <div class="avatar-circle-sm bg-info text-white me-3" style="width:36px; height:36px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-weight:700;">
                                                {{ strtoupper(substr($student->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark">{{ $student->name }}</div>
                                                <small class="text-muted">{{ $student->email }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="font-monospace small">#{{ $student->enrollment_no ?? '1092837' }}</td>
                                    <td class="fw-bold">{{ number_format($student->cgpa, 2) }}</td>
                                    <td>
                                        @if(($student->backlogs ?? 0) === 0)
                                            <span class="badge bg-soft-success text-success rounded-pill px-2">None</span>
                                        @else
                                            <span class="badge bg-soft-danger text-danger rounded-pill px-2">{{ $student->backlogs }} Active</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <div class="progress flex-grow-1" style="height: 6px; min-width: 60px;">
                                                <div class="progress-bar bg-success" style="width: {{ min(100, (($student->solved_problems ?? 0) / 10) * 100) }}%"></div>
                                            </div>
                                            <span class="fw-bold small text-success">{{ $student->solved_problems ?? 0 }} Solved</span>
                                        </div>
                                    </td>
                                    <td><span class="badge bg-dark-subtle text-dark"><i class="fas fa-bolt text-warning me-1"></i> {{ $student->xp ?? 0 }}</span></td>
                                    <td class="text-end pe-4">
                                        @if($student->is_eligible)
                                            <span class="eligibility-badge badge-eligible"><i class="fas fa-check-circle"></i> Eligible</span>
                                        @else
                                            <span class="eligibility-badge badge-ineligible"><i class="fas fa-times-circle"></i> Not Eligible</span>
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

        <!-- Tab 3: Placement Analytics -->
        <div class="tab-pane fade" id="tab-analytics" role="tabpanel">
            <div class="row g-4">
                <!-- Recruiter Share -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="fw-bold m-0"><i class="fas fa-chart-pie text-warning me-2"></i> Recruiter Share Analytics</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span>TCS</span>
                                    <span>35% of Hires</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: 35%; background: linear-gradient(90deg, #4f46e5, #6366f1)"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span>Infosys</span>
                                    <span>22% of Hires</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: 22%; background: linear-gradient(90deg, #10b981, #34d399)"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span>Google India</span>
                                    <span>8% of Hires</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: 8%; background: linear-gradient(90deg, #f59e0b, #fbbf24)"></div>
                                </div>
                            </div>
                            <div class="mb-4">
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span>Reliance Industries</span>
                                    <span>15% of Hires</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: 15%; background: linear-gradient(90deg, #ef4444, #f87171)"></div>
                                </div>
                            </div>
                            <div>
                                <div class="d-flex justify-content-between small fw-bold mb-1">
                                    <span>Other Recruiter Networks</span>
                                    <span>20% of Hires</span>
                                </div>
                                <div class="progress" style="height: 10px;">
                                    <div class="progress-bar" style="width: 20%; background: linear-gradient(90deg, #64748b, #94a3b8)"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Salary Package Spread -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm rounded-4 h-100">
                        <div class="card-header bg-white py-3 border-0">
                            <h5 class="fw-bold m-0"><i class="fas fa-chart-bar text-danger me-2"></i> Package Ranges Placement Distribution</h5>
                        </div>
                        <div class="card-body">
                            <div class="d-flex align-items-end justify-content-around h-100 pt-5" style="min-height: 250px;">
                                <div class="text-center w-100">
                                    <div class="fw-bold text-dark">45 Hires</div>
                                    <div class="bg-primary rounded-top mx-auto" style="height: 150px; width: 45px; background: linear-gradient(0deg, #4f46e5, #818cf8);"></div>
                                    <div class="small text-muted mt-2">3-5 LPA</div>
                                </div>
                                <div class="text-center w-100">
                                    <div class="fw-bold text-dark">21 Hires</div>
                                    <div class="bg-success rounded-top mx-auto" style="height: 90px; width: 45px; background: linear-gradient(0deg, #10b981, #34d399);"></div>
                                    <div class="small text-muted mt-2">5-8 LPA</div>
                                </div>
                                <div class="text-center w-100">
                                    <div class="fw-bold text-dark">6 Hires</div>
                                    <div class="bg-warning rounded-top mx-auto" style="height: 45px; width: 45px; background: linear-gradient(0deg, #f59e0b, #fbbf24);"></div>
                                    <div class="small text-muted mt-2">8-15 LPA</div>
                                </div>
                                <div class="text-center w-100">
                                    <div class="fw-bold text-dark">2 Hires</div>
                                    <div class="bg-danger rounded-top mx-auto" style="height: 20px; width: 45px; background: linear-gradient(0deg, #ef4444, #f87171);"></div>
                                    <div class="small text-muted mt-2">15+ LPA</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- Modal: Post New Drive -->
<div class="modal fade" id="addDriveModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form action="/admin/placement/drives" method="POST" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header bg-dark text-white border-0 py-3" style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%) !important;">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> Post New Placement Drive</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Company Name</label>
                    <input name="company" class="form-control rounded-3" placeholder="e.g. Amazon India" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Target Job Role</label>
                    <input name="role" class="form-control rounded-3" placeholder="e.g. Associate Software Engineer" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Drive Date</label>
                        <input type="date" name="date" class="form-control rounded-3" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Status</label>
                        <select name="status" class="form-select rounded-3">
                            <option>Upcoming</option>
                            <option>Open</option>
                            <option>Ongoing</option>
                            <option>Completed</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Salary Package Range (LPA)</label>
                    <input name="package" class="form-control rounded-3" placeholder="e.g. 5.5 - 8.0 LPA" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Work Location</label>
                    <input name="location" class="form-control rounded-3" placeholder="e.g. Pune / Ahmedabad" required>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill shadow">
                    Announce Drive & Notify Students
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function showPlacementTab(tabId) {
        // Find corresponding trigger button
        const trigger = document.getElementById(tabId + '-tab');
        if (trigger) {
            // Activate using Bootstrap tab API
            const tab = new bootstrap.Tab(trigger);
            tab.show();
        }

        // Highlight selected nav-link in the sidebar
        document.querySelectorAll('.sidebar .nav-link-custom').forEach(link => {
            if (link.getAttribute('href').includes('/admin/placement#' + tabId)) {
                link.classList.add('active');
            } else if (link.getAttribute('href').includes('/admin/placement')) {
                link.classList.remove('active');
            }
        });
    }

    // Check hash on load
    document.addEventListener('DOMContentLoaded', () => {
        const hash = window.location.hash.replace('#', '');
        if (['drives', 'training', 'analytics'].includes(hash)) {
            showPlacementTab(hash);
        }
    });
</script>

<style>
    .bg-soft-success { background: #dcfce7; }
    .bg-soft-danger { background: #fee2e2; }
</style>

@endsection
