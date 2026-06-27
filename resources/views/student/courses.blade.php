@extends('layouts.app')
@section('content')

<!-- CSS styling for modern Skeleton loader, premium tabs, and hover effects -->
<style>
    @keyframes pulse-bg {
        0% { background-color: #f1f5f9; }
        50% { background-color: #e2e8f0; }
        100% { background-color: #f1f5f9; }
    }
    .skeleton-shimmer {
        animation: pulse-bg 1.5s infinite ease-in-out;
    }
    .itm-card { 
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        border-radius: 16px !important;
    }
    .itm-card:hover { 
        transform: translateY(-6px); 
        box-shadow: 0 12px 30px rgba(0,0,0,0.12) !important; 
    }
    .nav-link-custom {
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        color: #475569 !important;
        transition: all 0.3s ease;
    }
    .nav-link-custom.active {
        background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;
        color: #ffffff !important;
        border-color: #4f46e5 !important;
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25) !important;
    }
    .request-badge {
        font-size: 0.75rem;
        padding: 6px 12px;
        border-radius: 20px;
        font-weight: 700;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4 mt-2">
    <div>
        <h3 class="fw-bold text-dark"><i class="fas fa-book-reader text-primary me-2"></i> Course Catalog</h3>
        <p class="text-muted small">Explore your assigned curriculum or request enrollment in special extra courses.</p>
    </div>
    @if($user && $user->program)
    <div class="text-end">
        <span class="badge bg-secondary rounded-pill px-3 py-2 text-wrap shadow-xs">
            <i class="fas fa-graduation-cap me-1"></i> Profile: {{ ucfirst($user->program) }} - Sem {{ $user->semester }} - Div {{ $user->class_section ?? 'N/A' }}
        </span>
    </div>
    @endif
</div>

<!-- 2-Second Skeleton Loader -->
<div id="skeleton-loader" class="container px-0">
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-5">
        @for($i = 0; $i < 4; $i++)
        <div class="col">
            <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden" style="background-color: #ffffff;">
                <div class="skeleton-shimmer" style="height: 180px;"></div>
                <div class="card-body p-4 text-center">
                    <div class="skeleton-shimmer mb-3 mx-auto" style="height: 20px; width: 85%; border-radius: 6px;"></div>
                    <div class="skeleton-shimmer mx-auto" style="height: 14px; width: 50%; border-radius: 4px;"></div>
                </div>
                <div class="card-footer bg-white border-0 py-3 text-center">
                    <div class="skeleton-shimmer mx-auto" style="height: 35px; width: 130px; border-radius: 20px;"></div>
                </div>
            </div>
        </div>
        @endfor
    </div>
</div>

<!-- Real Content (Initially Hidden) -->
<div id="courses-content" style="display: none;">
    <!-- Tabs to toggle between Curriculum and Special Courses -->
    <ul class="nav nav-pills mb-4 gap-2" id="courseCategoryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-link-custom active fw-bold px-4 py-2 rounded-pill shadow-xs" id="curriculum-tab" data-bs-toggle="pill" data-bs-target="#curriculum-pane" type="button" role="tab">
                <i class="fas fa-layer-group me-2"></i> Assigned Curriculum ({{ $courses->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link nav-link-custom fw-bold px-4 py-2 rounded-pill shadow-xs" id="special-tab" data-bs-toggle="pill" data-bs-target="#special-pane" type="button" role="tab">
                <i class="fas fa-puzzle-piece me-2"></i> Special / Extra Courses ({{ $specialCourses->count() }})
            </button>
        </li>
    </ul>

    @if(session('success'))
        <div class="alert alert-success border-0 shadow-sm rounded-4 mb-4 fw-bold">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 fw-bold">
            <i class="fas fa-info-circle me-2"></i> {{ session('info') }}
        </div>
    @endif

    <div class="tab-content" id="courseCategoryTabsContent">
        <!-- TAB 1: Curriculum / Assigned Courses -->
        <div class="tab-pane fade show active" id="curriculum-pane" role="tabpanel">
            @if($courses->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-5">
                    @foreach($courses as $course)
                        @php
                            $enr = $enrollmentsMap->get($course->id);
                            $isApproved = $enr && $enr->status === 'approved';
                        @endphp
                        <div class="col">
                            <a href="{{ $isApproved ? '/courses/'.$course->id : '#' }}" class="text-decoration-none" onclick="if(!{{ $isApproved ? 'true' : 'false' }}) { event.preventDefault(); showBapsToast('Please enroll to access course modules.', 'warning'); }">
                                <div class="card h-100 border-0 shadow-sm overflow-hidden itm-card" style="background-color: #ffffff;">
                                    <div class="position-relative p-4 d-flex justify-content-center align-items-center" style="background: linear-gradient(135deg, #38b2ac 0%, #319795 100%); height: 180px;">
                                        <!-- Diagonal background styles -->
                                        <div class="position-absolute w-100 h-100" style="background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.04) 0px, rgba(255,255,255,0.04) 2px, transparent 2px, transparent 10px);"></div>
                                        
                                        <!-- Semester Pill -->
                                        <div class="position-absolute top-0 start-0 m-3 z-3">
                                            <span class="badge" style="background-color: #ef4444; border-radius: 12px; padding: 5px 12px; font-weight: 600;">Semester {{ $course->semester }}</span>
                                        </div>

                                        <!-- Favorite Button -->
                                        <div class="position-absolute top-0 end-0 m-3 z-3">
                                            <button class="btn btn-light rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 35px; height: 35px; border: none; opacity: 0.95;" onclick="event.preventDefault(); event.stopPropagation(); toggleFavorite(this, {{ $course->id }})" title="Toggle Favorite">
                                                @php 
                                                    $isFav = $user && $user->favorites()->where('course_id', $course->id)->exists(); 
                                                @endphp
                                                <i class="{{ $isFav ? 'fas' : 'far' }} fa-heart text-danger fs-5"></i>
                                            </button>
                                        </div>
                                        
                                        <div class="position-relative z-2 text-center" style="transform: scale(1.15);">
                                            <i class="fas fa-desktop" style="font-size: 4.5rem; color: #1e293b;"></i>
                                            <i class="fas fa-graduation-cap text-warning position-absolute" style="font-size: 3.2rem; top: -15px; left: 25px; text-shadow: 0 2px 4px rgba(0,0,0,0.2);"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body py-4 text-center d-flex flex-column justify-content-between">
                                        <h6 class="mb-3 text-dark fw-bold" style="font-size: 0.95rem; line-height: 1.4;">
                                            {{ $course->title }}
                                        </h6>
                                        <div class="text-muted small mb-2"><i class="fas fa-user-tie me-1"></i> {{ $course->instructor ?? $course->faculty->name ?? 'Faculty Coordinator' }}</div>
                                    </div>

                                    <div class="card-footer bg-white border-top-0 pb-4 text-center">
                                        @if($isApproved)
                                            <a href="/courses/{{ $course->id }}" class="btn btn-sm btn-success px-4 rounded-pill fw-bold shadow-xs">
                                                <i class="fas fa-play-circle me-1"></i> Enter Course
                                            </a>
                                        @elseif($enr && $enr->status === 'pending')
                                            <span class="badge bg-warning text-dark request-badge"><i class="fas fa-hourglass-half me-1"></i> Pending Approval</span>
                                        @elseif($enr && $enr->status === 'rejected')
                                            <button class="btn btn-sm btn-outline-danger px-4 rounded-pill fw-bold" onclick="event.preventDefault(); enrollCourse({{ $course->id }}, '{{ session('user_role') }}')">
                                                <i class="fas fa-redo me-1"></i> Re-apply Course
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-primary px-4 rounded-pill fw-bold" onclick="event.preventDefault(); enrollCourse({{ $course->id }}, '{{ session('user_role') }}')">
                                                <i class="fas fa-user-plus me-1"></i> Enroll Course
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 glass-card border-0 shadow-sm rounded-4">
                    <i class="fas fa-book-open fa-3x text-muted mb-3 opacity-25"></i>
                    <h5 class="text-muted fw-bold">No assigned courses found</h5>
                    <p class="text-muted small">You don't have any assigned curriculum courses for this semester.</p>
                </div>
            @endif
        </div>

        <!-- TAB 2: Special / Extra Courses -->
        <div class="tab-pane fade" id="special-pane" role="tabpanel">
            <!-- Access & Visibility Policy Banner -->
            <div class="alert alert-info border-0 shadow-sm rounded-4 mb-4 p-4" style="background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); border-left: 5px solid #2563eb !important;">
                <div class="d-flex align-items-start gap-3">
                    <div class="text-primary fs-3"><i class="fas fa-shield-alt"></i></div>
                    <div>
                        <h6 class="fw-bold text-dark mb-1">Special Course Visibility & Access Policy</h6>
                        <p class="text-muted small mb-0">
                            <strong>Policy Declaration:</strong> Only students selected by the <strong>DEAN, ADMINISTRATOR, or HOD</strong> are authorized to see the course content on a course-selection basis.
                            If you wish to enroll and unlock visibility for any of these special/extra courses, please contact your <strong>Class Representative (CR), HOD, Dean, or Administrator</strong> directly to enable access for your account.
                        </p>
                    </div>
                </div>
            </div>

            @if($specialCourses->count() > 0)
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4 mb-5">
                    @foreach($specialCourses as $course)
                        @php
                            $enr = $enrollmentsMap->get($course->id);
                            $isApproved = $enr && $enr->status === 'approved';
                        @endphp
                        <div class="col">
                            <a href="{{ $isApproved ? '/courses/'.$course->id : '#' }}" class="text-decoration-none" onclick="if(!{{ $isApproved ? 'true' : 'false' }}) { event.preventDefault(); showBapsToast('Extra course enrollment is pending approval.', 'warning'); }">
                                <div class="card h-100 border-0 shadow-sm overflow-hidden itm-card" style="background-color: #ffffff;">
                                    <div class="position-relative p-4 d-flex justify-content-center align-items-center" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); height: 180px;">
                                        <div class="position-absolute w-100 h-100" style="background-image: repeating-linear-gradient(45deg, rgba(255,255,255,0.04) 0px, rgba(255,255,255,0.04) 2px, transparent 2px, transparent 10px);"></div>
                                        
                                        <!-- Program Indicator -->
                                        <div class="position-absolute top-0 start-0 m-3 z-3">
                                            <span class="badge bg-white text-primary rounded-pill shadow-xs px-2.5 py-1 fw-bold">{{ ucfirst($course->program ?? 'Special') }}</span>
                                        </div>

                                        <div class="position-relative z-2 text-center" style="transform: scale(1.15);">
                                            <i class="fas fa-puzzle-piece text-white-50" style="font-size: 4.5rem;"></i>
                                            <i class="fas fa-crown text-warning position-absolute" style="font-size: 2rem; top: -10px; right: -10px; transform: rotate(15deg);"></i>
                                        </div>
                                    </div>
                                    
                                    <div class="card-body py-4 text-center d-flex flex-column justify-content-between">
                                        <div>
                                            <h6 class="mb-1 text-dark fw-bold" style="font-size: 0.95rem;">
                                                {{ $course->title }}
                                            </h6>
                                            <span class="badge bg-light text-secondary rounded-pill xx-small mb-3">Sem {{ $course->semester }} | Credits: {{ $course->credits }}</span>
                                        </div>
                                        <div class="text-muted small"><i class="fas fa-user-tie me-1"></i> {{ $course->instructor ?? $course->faculty->name ?? 'Faculty Coordinator' }}</div>
                                    </div>

                                    <div class="card-footer bg-white border-top-0 pb-4 text-center">
                                        @if($isApproved)
                                            <a href="/courses/{{ $course->id }}" class="btn btn-sm btn-success px-4 rounded-pill fw-bold shadow-xs">
                                                <i class="fas fa-play-circle me-1"></i> Enter Course
                                            </a>
                                        @elseif($enr && $enr->status === 'pending')
                                            <span class="badge bg-warning text-dark request-badge shadow-xs"><i class="fas fa-hourglass-half me-1"></i> Pending CR/Dean Approval</span>
                                        @elseif($enr && $enr->status === 'rejected')
                                            <button class="btn btn-sm btn-outline-danger px-4 rounded-pill fw-bold" onclick="event.preventDefault(); enrollCourse({{ $course->id }}, '{{ session('user_role') }}')">
                                                <i class="fas fa-redo me-1"></i> Re-apply Permission
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-warning px-4 rounded-pill fw-bold text-dark shadow-xs" onclick="event.preventDefault(); enrollCourse({{ $course->id }}, '{{ session('user_role') }}')">
                                                <i class="fas fa-paper-plane me-1"></i> Request Permission
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-5 glass-card border-0 shadow-sm rounded-4">
                    <i class="fas fa-smile fa-3x text-muted mb-3 opacity-25"></i>
                    <h5 class="text-muted fw-bold">No special/extra courses available</h5>
                    <p class="text-muted small">All active courses match your current curriculum roster.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Skeleton Loader simulation (exactly 2 seconds)
document.addEventListener("DOMContentLoaded", function() {
    setTimeout(function() {
        document.getElementById('skeleton-loader').style.display = 'none';
        document.getElementById('courses-content').style.display = 'block';
    }, 2000);
});

function toggleFavorite(btn, courseId) {
    const icon = btn.querySelector('i');
    
    $.ajax({
        url: '/favorites/toggle',
        method: 'POST',
        data: {
            course_id: courseId,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'attached') {
                icon.classList.replace('far', 'fas');
                btn.style.transform = 'scale(1.25)';
                setTimeout(() => btn.style.transform = 'scale(1)', 200);
            } else {
                icon.classList.replace('fas', 'far');
            }
        }
    });
}

function enrollCourse(courseId, role) {
    if (['admin', 'faculty', 'hod', 'dean'].includes(role)) {
        // Staff bypasses the form directly
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/courses/' + courseId + '/enroll';
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        document.body.appendChild(form);
        form.submit();
        return;
    }

    // Pop up Application Form Modal for students
    document.getElementById('enrollModalForm').action = '/courses/' + courseId + '/enroll';
    var enrollModal = new bootstrap.Modal(document.getElementById('enrollModal'));
    enrollModal.show();
}
</script>

<!-- Application Form Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1" aria-labelledby="enrollModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px; overflow: hidden;">
            <div class="modal-header text-white border-0 bg-primary" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%) !important;">
                <h5 class="modal-title" id="enrollModalLabel"><i class="fas fa-file-signature me-2"></i> Extra Course Enrollment Request</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="enrollModalForm" action="" method="POST">
                @csrf
                <div class="modal-body p-4 bg-light">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-2 shadow-xs" style="width: 70px; height: 70px; font-size: 28px; font-weight: bold;">
                            {{ substr(($user->name ?? 'Student'), 0, 1) }}
                        </div>
                        <h5 class="fw-bold text-dark mb-1">{{ $user->name ?? 'Student' }}</h5>
                        <div class="text-muted small"><i class="fas fa-envelope me-1"></i> {{ $user->email ?? '' }}</div>
                    </div>

                    <div class="bg-warning bg-opacity-10 p-3 rounded-4 border border-warning border-opacity-25 mb-4 text-center">
                        <span class="small text-warning-emphasis fw-bold d-block mb-1"><i class="fas fa-info-circle me-1"></i> Special Course Access Protocol</span>
                        <span class="small text-muted d-block mt-1">
                            Only students selected by the <strong>DEAN, HOD, or Administrator</strong> are permitted to see the contents of this course.
                        </span>
                        <span class="small text-dark fw-bold d-block mt-2">
                            To enroll and make this course visible in your workspace, please contact your Class Representative (CR), HOD, Dean, or Administrator.
                        </span>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-white p-3 rounded-3 shadow-xs border text-center">
                                <div class="small text-muted mb-1 fw-bold">Enrollment No.</div>
                                <div class="fw-bold text-dark font-monospace">{{ $user->enrollment_no ?? 'Pending' }}</div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-white p-3 rounded-3 shadow-xs border text-center">
                                <div class="small text-muted mb-1 fw-bold">Phone Number</div>
                                <div class="fw-bold text-dark">{{ $user->phone ?? 'Not Linked' }}</div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Hidden inputs to submit securely -->
                    <input type="hidden" name="name" value="{{ $user->name ?? '' }}">
                    <input type="hidden" name="email" value="{{ $user->email ?? '' }}">
                    <input type="hidden" name="phone" value="{{ $user->phone ?? '' }}">
                    <input type="hidden" name="roll_no" value="{{ $user->enrollment_no ?? '' }}">
                </div>
                <div class="modal-footer border-0 bg-white p-4 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn rounded-pill px-5 fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #4f46e5 0%, #6366f1 100%);">Submit Request <i class="fas fa-paper-plane ms-1"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
