@extends('layouts.app')
@section('content')

@php
    $user = Auth::user();
    if (!$user) {
        $userId = session('demo_user_id') ?? session('student_id') ?? session('user_id');
        $user = \App\Models\User::find($userId);
    }
    if (!$user) $user = \App\Models\User::find(1); // Final fallback
    
    $studentEnrollments = \App\Models\Enrollment::where('user_id', $user->id)->where('status', 'approved')->with('course')->get();
    $enrolledCourses = $studentEnrollments->count();
    $badges = $user->achievements ? $user->achievements->count() : 0;
@endphp

@if(session('success'))
    <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 fw-bold">
        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger border-0 shadow-sm rounded-4 mb-4 fw-bold">
        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
    </div>
@endif

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h3 class="fw-bold mb-1">Student Management Hub</h3>
                <p class="text-muted small mb-0">Unified portal for identity, financial dues, and academic requests.</p>
            </div>
            <span class="badge bg-primary fs-6 px-3 py-2 rounded-pill shadow-sm">
                <i class="fas fa-id-card me-2"></i> Enrollment No: {{ $user->enrollment_no }}
            </span>
        </div>
    </div>
</div>

{{-- Modern Tab Navigation --}}
<ul class="nav nav-pills mb-4 gap-2" id="profileTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold px-4 rounded-pill shadow-sm" id="profile-tab" data-bs-toggle="pill" data-bs-target="#tab-profile" type="button" role="tab">
            <i class="fas fa-user-circle me-2"></i> 1) Profile & Service
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-4 rounded-pill shadow-sm" id="hub-tab" data-bs-toggle="pill" data-bs-target="#tab-hub" type="button" role="tab">
            <i class="fas fa-hubspot me-2"></i> 2) Hub & Request
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-4 rounded-pill shadow-sm" id="courses-tab" data-bs-toggle="pill" data-bs-target="#tab-courses" type="button" role="tab">
            <i class="fas fa-book me-2"></i> 3) My Courses
        </button>
    </li>
</ul>

<div class="tab-content" id="profileTabsContent">
    
    {{-- TAB 1: PROFILE & SERVICE --}}
    <div class="tab-pane fade show active" id="tab-profile" role="tabpanel">
        <div class="row g-4">
            <div class="col-lg-5">
                {{-- Existing Photo & Details logic --}}
                <div class="glass-card p-4 text-center mb-4 border-0 shadow-sm">
                    <div class="position-relative mx-auto mb-3" style="width: 200px; height: 200px;">
                        <img id="profileAvatar"
                            src="{{ ($user->profile_photo_data || $user->profile_photo) ? url('/profile/photo/user/' . $user->id) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff&size=200' }}"
                            class="w-100 h-100 shadow"
                            style="object-fit: cover; border-radius: 20px;"
                            onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=6366f1&color=fff&size=200'">
                        <input type="file" id="photoInput" name="photo" accept="image/*" style="display:none;">
                        <label for="photoInput"
                            class="position-absolute bottom-0 end-0 bg-primary text-white d-flex align-items-center justify-content-center border border-3 border-white shadow"
                            style="width: 42px; height: 42px; border-radius: 14px; cursor:pointer; margin: 8px;" title="Upload photo">
                            <i class="fas fa-camera" id="cameraIcon"></i>
                        </label>
                    </div>
                    <h4 class="fw-bold mb-1">{{ $user->name }}</h4>
                    <p class="text-muted small mb-4">{{ $user->email }}</p>
                    <div class="d-flex justify-content-around border-top pt-4">
                        <div><h5 class="fw-bold mb-0 text-primary">{{ $enrolledCourses }}</h5><small class="text-muted fw-bold">Courses</small></div>
                        <div class="border-start"></div>
                        <div><h5 class="fw-bold mb-0 text-warning">{{ $user->level }}</h5><small class="text-muted fw-bold">Level</small></div>
                        <div class="border-start"></div>
                        <div><h5 class="fw-bold mb-0 text-success">{{ $user->xp }}</h5><small class="text-muted fw-bold">XP</small></div>
                    </div>
                </div>

                <div class="glass-card p-4 border-0 shadow-sm">
                    <h6 class="fw-bold mb-3"><i class="fas fa-user-edit text-primary me-2"></i> Edit Details</h6>
                    <form action="/profile/update" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Full Name</label>
                            <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Email Address</label>
                            <input type="email" class="form-control bg-light text-muted" value="{{ $user->email }}" readonly>
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <small class="text-muted" style="font-size:0.75rem;"><i class="fas fa-lock me-1"></i>Contact admin to change email</small>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">New Password (Optional)</label>
                            <input type="password" name="password" class="form-control" placeholder="Leave blank to keep current">
                        </div>
                        <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill shadow-sm">Save Changes</button>
                    </form>
                </div>

                {{-- BAPS Hostel & Family Mapping Card --}}
                <div class="glass-card p-4 border-0 shadow-sm mt-4 text-start" style="border-left: 4px solid #f97316 !important;">
                    <h6 class="fw-bold mb-3" style="color: #ea580c;"><i class="fas fa-bed text-warning me-2"></i> BAPS Hostel & Family Mapping</h6>
                    
                    <!-- BAPS Hostel Details -->
                    <div class="mb-4 pb-3 border-bottom text-start">
                        <span class="small text-muted d-block fw-bold mb-2"><i class="fas fa-hotel text-success me-1"></i> BAPS Hostel Residence</span>
                        @if($user->hostelSwami)
                            <div class="d-flex align-items-center gap-3 bg-light p-3 rounded-3 border">
                                <div class="avatar-circle bg-success text-white shadow-sm" style="width: 38px; height: 38px; font-size: 0.9rem;">
                                    {{ strtoupper(substr($user->hostelSwami->name, 0, 2)) }}
                                </div>
                                <div class="text-start">
                                    <div class="small fw-bold text-dark">{{ $user->hostelSwami->name }}</div>
                                    <div class="x-small text-muted">Hostel Warden / Swami | Room: <strong>{{ $user->hostel_room_no ?? 'Unassigned' }}</strong></div>
                                </div>
                            </div>
                        @else
                            <div class="p-3 bg-light rounded-3 text-center text-muted small">
                                <i class="fas fa-info-circle me-1 text-primary"></i> Not registered in BAPS Hostel.
                            </div>
                        @endif
                    </div>
                    
                    <!-- Mapped Parents -->
                    <div class="text-start">
                        <span class="small text-muted d-block fw-bold mb-2"><i class="fas fa-user-friends text-danger me-1"></i> Linked Parents / Guardians (Max 4)</span>
                        @php
                            $linkedParents = $user->linked_parents;
                        @endphp
                        @if($linkedParents->isEmpty())
                            <div class="p-3 bg-light rounded-3 text-center text-muted small">
                                <i class="fas fa-user-slash me-1 text-danger"></i> No parent accounts currently linked.
                            </div>
                        @else
                            <div class="d-flex flex-column gap-2">
                                @foreach($linkedParents as $idx => $p)
                                    <div class="d-flex align-items-center justify-content-between bg-light p-2.5 rounded-3 border small">
                                        <div class="text-start">
                                            <span class="fw-bold text-dark">{{ $p->name }}</span>
                                            <div class="x-small text-muted">{{ $p->email }}</div>
                                        </div>
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill px-2.5 py-1">Parent {{ $idx + 1 }}</span>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-7">
                {{-- Quick Service Links --}}
                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <div class="glass-card p-4 border-0 shadow-sm h-100">
                            <h6 class="fw-bold mb-3"><i class="fas fa-id-badge text-info me-2"></i> Identity Services</h6>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0 small d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-print me-2 text-muted"></i> Download ID Card</span>
                                    <i class="fas fa-chevron-right text-muted" style="font-size: 0.7rem;"></i>
                                </a>
                                <a href="{{ url('/portfolio/' . ($user->enrollment_no ?? 'me')) }}" class="list-group-item list-group-item-action border-0 px-0 small d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-briefcase me-2 text-muted"></i> My Public Portfolio</span>
                                    <i class="fas fa-chevron-right text-muted" style="font-size: 0.7rem;"></i>
                                </a>
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0 small d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-user-check me-2 text-muted"></i> Request Verification</span>
                                    <span class="badge bg-secondary rounded-pill" style="font-size: 0.6rem;">Coming Soon</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="glass-card p-4 border-0 shadow-sm h-100">
                            <h6 class="fw-bold mb-3"><i class="fas fa-certificate text-warning me-2"></i> Credentials</h6>
                            <div class="list-group list-group-flush">
                                <a href="#" class="list-group-item list-group-item-action border-0 px-0 small d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-award me-2 text-muted"></i> Course Certificates</span>
                                    <span class="badge bg-primary rounded-pill" style="font-size: 0.6rem;">{{ $enrolledCourses }} Active</span>
                                </a>
                                <a href="/exam/results" class="list-group-item list-group-item-action border-0 px-0 small d-flex justify-content-between align-items-center">
                                    <span><i class="fas fa-file-signature me-2 text-muted"></i> Official Transcripts</span>
                                    <i class="fas fa-chevron-right text-muted" style="font-size: 0.7rem;"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Academic Assessment Summary --}}
                <div class="glass-card p-4 border-0 shadow-sm mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold mb-0"><i class="fas fa-graduation-cap text-primary me-2"></i> Academic Assessment Summary</h6>
                        <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill small px-3">Current Semester</span>
                    </div>
                    
                    @php
                        $studentEnrollments = \App\Models\Enrollment::where('user_id', $user->id)->with('course')->get();
                    @endphp

                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0 small">
                            <thead class="text-muted">
                                <tr>
                                    <th class="border-0 ps-0">Subject</th>
                                    <th class="border-0 text-center">Type</th>
                                    <th class="border-0 text-center">Score</th>
                                    <th class="border-0 text-end pe-0">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($studentEnrollments as $enr)
                                    @php
                                        $res = \App\Models\Result::where('user_id', $user->id)->where('course_id', $enr->course_id)->first();
                                    @endphp
                                    <tr>
                                        <td class="ps-0 py-3">
                                            <div class="fw-bold text-dark">{{ $enr->course->title }}</div>
                                            <div class="text-muted xx-small">Sem: {{ $enr->course->semester ?? 'N/A' }}</div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge rounded-pill {{ ($enr->course->type ?? 'theory') == 'pbl' ? 'bg-warning text-dark' : 'bg-info' }}" style="font-size: 0.6rem;">
                                                {{ strtoupper($enr->course->type ?? 'Theory') }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            @if($res)
                                                <span class="fw-bold text-success">{{ number_format($res->total_obtained, 1) }}</span>
                                            @else
                                                <span class="text-muted">--</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-0">
                                            @if($res)
                                                <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2" style="font-size: 0.6rem;">Graded: {{ $res->grade }}</span>
                                            @else
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-2" style="font-size: 0.6rem;">Pending</span>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Achievement Section --}}
                <div class="glass-card p-4 border-0 shadow-sm">
                    <h6 class="fw-bold mb-4"><i class="fas fa-medal text-warning me-2"></i> Achievement Badges</h6>
                    <div class="row g-3">
                        <div class="col-md-4 col-6 text-center">
                            <div class="p-3 border rounded-4 bg-light h-100 shadow-sm">
                                <i class="fas fa-fire fa-2x text-warning mb-2"></i>
                                <p class="small fw-bold mb-0">First Login</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 text-center">
                            <div class="p-3 border rounded-4 bg-light h-100 shadow-sm {{ $enrolledCourses >= 1 ? '' : 'opacity-25' }}">
                                <i class="fas fa-book-reader fa-2x text-info mb-2"></i>
                                <p class="small fw-bold mb-0">Course Enrolled</p>
                            </div>
                        </div>
                        <div class="col-md-4 col-6 text-center">
                            <div class="p-3 border rounded-4 bg-light h-100 shadow-sm {{ $user->level >= 5 ? '' : 'opacity-25' }}">
                                <i class="fas fa-crown fa-2x text-warning mb-2"></i>
                                <p class="small fw-bold mb-0">Level 5 Master</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- TAB 2: HUB & REQUEST --}}
    <div class="tab-pane fade" id="tab-hub" role="tabpanel">
        <div class="row g-4">
            {{-- Left Column: Fees & Finance --}}
            <div class="col-lg-6">
                <div class="glass-card p-4 border-0 shadow-sm mb-4 h-100">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0"><i class="fas fa-file-invoice-dollar text-success me-2"></i> Fees & Payments</h5>
                        <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-bold">Live Billing</span>
                    </div>

                    @php
                        $pendingFees = \App\Models\FeePayment::where('user_id', $user->id)->where('status', 'pending')->get();
                        $totalDue = $pendingFees->sum('amount');
                    @endphp

                    @if($totalDue > 0)
                        <div class="alert alert-warning border-0 shadow-sm rounded-4 mb-4">
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-warning text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 45px; height: 45px; flex-shrink:0;">
                                    <i class="fas fa-exclamation-triangle"></i>
                                </div>
                                <div>
                                    <div class="small fw-bold text-dark">Action Required: Outstanding Dues</div>
                                    <div class="fs-4 fw-bold text-dark">₹{{ number_format($totalDue, 2) }}</div>
                                </div>
                            </div>
                        </div>

                        <div class="list-group list-group-flush mb-4">
                            @foreach($pendingFees as $fee)
                            <div class="list-group-item border-0 px-0 bg-transparent">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="fw-bold mb-0">Academic Fee #{{ $fee->id }}</div>
                                        <div class="small text-muted">LMS Maintenance & Library Access</div>
                                    </div>
                                    <div class="text-end">
                                        <div class="fw-bold text-dark">₹{{ number_format($fee->amount, 2) }}</div>
                                        <button class="btn btn-sm btn-primary rounded-pill px-3 mt-1 fw-bold shadow-sm" onclick="alert('Payment Gateway Loading...')">Pay Now</button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="bg-success-subtle text-success rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 70px; height: 70px;">
                                <i class="fas fa-check-double fa-2x"></i>
                            </div>
                            <h6 class="fw-bold">No Pending Payments</h6>
                            <p class="text-muted small">All your institutional dues are currently cleared.</p>
                        </div>
                    @endif

                    <div class="mt-auto pt-4 border-top">
                        <a href="#" class="btn btn-light w-100 rounded-pill fw-bold border shadow-sm small text-muted">
                            <i class="fas fa-history me-2"></i> View Payment History
                        </a>
                    </div>
                </div>
            </div>

            {{-- Right Column: Exam & Requests --}}
            <div class="col-lg-6">
                <div class="glass-card p-4 border-0 shadow-sm mb-4">
                    <h5 class="fw-bold mb-4"><i class="fas fa-file-alt text-primary me-2"></i> Examination Hub</h5>
                    
                    <div class="d-flex gap-3 mb-4">
                        <div class="flex-grow-1 p-3 border rounded-4 bg-light shadow-sm text-center">
                            <i class="fas fa-id-card-alt fa-2x text-primary mb-2"></i>
                            <div class="small fw-bold d-block mb-2">Admit Card</div>
                            <a href="/exam/admit-card" class="btn btn-sm btn-primary w-100 rounded-pill fw-bold shadow-sm">Download</a>
                        </div>
                        <div class="flex-grow-1 p-3 border rounded-4 bg-light shadow-sm text-center">
                            <i class="fas fa-edit fa-2x text-success mb-2"></i>
                            <div class="small fw-bold d-block mb-2">Exam Form</div>
                            <button class="btn btn-sm btn-success w-100 rounded-pill fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#examFormModal">Fill Form</button>
                        </div>
                        <div class="flex-grow-1 p-3 border rounded-4 bg-light shadow-sm text-center">
                            <i class="fas fa-poll fa-2x text-info mb-2"></i>
                            <div class="small fw-bold d-block mb-2">Exam Results</div>
                            <a href="/exam/results" class="btn btn-sm btn-info w-100 rounded-pill fw-bold shadow-sm text-white">View Results</a>
                        </div>
                    </div>

                    <div class="bg-primary-subtle p-3 rounded-4 border border-primary border-opacity-10 mb-4">
                        <div class="d-flex gap-3 align-items-center">
                            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; flex-shrink:0;">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <div class="small text-primary-emphasis fw-semibold">
                                Summer 2026 Examination cycle is now open. Ensure all fees are paid before filling the form.
                            </div>
                        </div>
                    </div>

                    <h6 class="fw-bold mb-3 small text-uppercase text-muted">Special Requests</h6>
                    <div class="row g-2">
                        <div class="col-6">
                            <form action="/exam/re-check" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill py-2 small fw-bold"><i class="fas fa-redo me-1"></i> Re-Check</button>
                            </form>
                        </div>
                        <div class="col-6">
                            <form action="/exam/duplicate" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-secondary w-100 rounded-pill py-2 small fw-bold"><i class="fas fa-copy me-1"></i> Duplicate</button>
                            </form>
                        </div>
                    </div>
                </div>

                </div>
            </div>
        </div>
    </div>

    {{-- TAB 3: MY COURSES --}}
    <div class="tab-pane fade" id="tab-courses" role="tabpanel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5 class="fw-bold mb-0"><i class="fas fa-book-open text-primary me-2"></i> Active Course Enrollments</h5>
            <div class="d-flex gap-2">
                <span class="badge bg-light text-dark border shadow-sm px-3 py-2 rounded-pill small">
                    <i class="fas fa-layer-group me-1"></i> Total: {{ $enrolledCourses }}
                </span>
            </div>
        </div>

        @if($enrolledCourses > 0)
            <div class="row g-4">
                @foreach($studentEnrollments as $enr)
                    <div class="col-md-6 col-xl-4">
                        <div class="glass-card p-0 border-0 shadow-sm overflow-hidden h-100 d-flex flex-column hover-lift">
                            <div class="position-relative">
                                <img src="https://images.unsplash.com/photo-1516321318423-f06f85e504b3?w=500&q=80" class="w-100" style="height: 140px; object-fit: cover;" alt="{{ $enr->course->title }}">
                                <div class="position-absolute top-0 end-0 m-3">
                                    <span class="badge bg-primary rounded-pill shadow">{{ strtoupper($enr->course->type ?? 'Theory') }}</span>
                                </div>
                            </div>
                            <div class="p-4 flex-grow-1">
                                <h6 class="fw-bold mb-1 text-truncate">{{ $enr->course->title }}</h6>
                                <p class="xx-small text-muted mb-3"><i class="fas fa-user-tie me-1"></i> {{ $enr->course->instructor ?? 'Faculty Member' }}</p>
                                
                                <div class="mb-3">
                                    <div class="d-flex justify-content-between small fw-bold mb-1">
                                        <span>Course Progress</span>
                                        <span class="text-primary">{{ $enr->progress ?? 0 }}%</span>
                                    </div>
                                    <div class="progress rounded-pill shadow-xs" style="height: 6px;">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: {{ $enr->progress ?? 0 }}%"></div>
                                    </div>
                                </div>

                                <div class="row g-2 mb-3">
                                    <div class="col-6">
                                        <div class="p-2 border rounded-3 bg-light text-center">
                                            <div class="xx-small text-muted fw-bold">SEM</div>
                                            <div class="small fw-bold">{{ $enr->course->semester ?? '1' }}</div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="p-2 border rounded-3 bg-light text-center">
                                            <div class="xx-small text-muted fw-bold">CREDITS</div>
                                            <div class="small fw-bold">{{ $enr->course->credits ?? '4.0' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="p-4 pt-0 mt-auto">
                                <a href="/courses/{{ $enr->course_id }}" class="btn btn-primary w-100 rounded-pill fw-bold shadow-sm py-2">
                                    <i class="fas fa-play-circle me-2"></i> Resume Learning
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-5 glass-card border-0 shadow-sm">
                <i class="fas fa-book-open fa-3x text-muted mb-3 opacity-25"></i>
                <h5 class="text-muted fw-bold">No active enrollments found</h5>
                <p class="text-muted small">You haven't been enrolled in any courses for the current semester yet.</p>
                <a href="/courses" class="btn btn-outline-primary rounded-pill px-4 fw-bold mt-2">Explore Courses</a>
            </div>
        @endif
    </div>
</div>

{{-- Exam Form Modal --}}
<div class="modal fade" id="examFormModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow-lg">
            <div class="modal-header border-0 bg-success text-white py-4 rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-edit me-2"></i> Summer 2026 Exam Enrollment Form</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/exam/form/submit" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="alert alert-info border-0 shadow-sm small fw-bold mb-4">
                        <i class="fas fa-info-circle me-2"></i> Please verify all pre-filled details. Incorrect data may lead to disqualification.
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="small fw-bold text-muted">Enrollment No.</label><input type="text" class="form-control bg-light" value="{{ $user->enrollment_no }}" readonly></div>
                        <div class="col-md-6"><label class="small fw-bold text-muted">Student Name</label><input type="text" class="form-control bg-light" value="{{ $user->name }}" readonly></div>
                        <div class="col-12"><label class="small fw-bold text-muted">Subjects for Examination</label>
                            <div class="p-3 border rounded-4 bg-light-subtle">
                                @forelse($studentEnrollments as $enrollment)
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="course_ids[]" value="{{ $enrollment->course_id }}" checked id="course{{ $enrollment->course_id }}">
                                        <label class="form-check-label small fw-semibold" for="course{{ $enrollment->course_id }}">
                                            {{ $enrollment->course->title }} ({{ $enrollment->course->code ?? 'N/A' }})
                                        </label>
                                    </div>
                                @empty
                                    <p class="small text-muted mb-0">No active course enrollments found.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" id="examConsent" required>
                        <label class="form-check-label small text-muted" for="examConsent">
                            I hereby declare that all information provided is true and I am eligible to appear for the exams.
                        </label>
                    </div>
                </div>
                <div class="modal-footer border-0 p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-success rounded-pill px-5 fw-bold shadow-sm">Submit Exam Form</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
document.getElementById('photoInput').addEventListener('change', function () {
    const file = this.files[0];
    if (!file) return;

    // Server-side limit is 2MB (Detected)
    if (file.size > 2 * 1024 * 1024) {
        showBapsToast('File too large for server! Max 2MB allowed by PHP.', 'error');
        return;
    }

    // Instant local preview
    const avatar = document.getElementById('profileAvatar');
    const previousSrc = avatar.src;
    const reader = new FileReader();
    reader.onload = e => { avatar.src = e.target.result; };
    reader.readAsDataURL(file);

    // Show spinner
    const icon = document.getElementById('cameraIcon');
    icon.className = 'fas fa-spinner fa-spin';

    // AJAX upload
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}');

    fetch('/profile/upload-photo', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async response => {
        const text = await response.text();
        try {
            return JSON.parse(text);
        } catch {
            // Server returned HTML (error page)
            console.error('Server response:', text.substring(0, 300));
            return { success: false, error: 'Server error (HTTP ' + response.status + '). Check console.' };
        }
    })
    .then(data => {
        icon.className = 'fas fa-camera';
        if (data.success) {
            // Use server URL (from cloud/local) with cache-busting
            avatar.src = data.url;
            if (typeof showBapsToast === 'function') showBapsToast('Profile photo updated! 📸', 'success');
        } else {
            // Revert preview on failure
            avatar.src = previousSrc;
            const msg = data.error || 'Upload failed.';
            if (typeof showBapsToast === 'function') showBapsToast('❌ ' + msg, 'error');
        }
    })
    .catch(err => {
        icon.className = 'fas fa-camera';
        avatar.src = previousSrc;
        console.error('Upload error:', err);
        if (typeof showBapsToast === 'function') showBapsToast('Network error. Check your connection.', 'error');
    });
});
</script>
@endpush
