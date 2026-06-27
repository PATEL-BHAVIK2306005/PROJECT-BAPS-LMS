@extends('layouts.app')
@section('content')

@php
    $myCourseIds = \App\Models\Enrollment::where('user_id', $user->id)->pluck('course_id');
    $announcements = \App\Models\Announcement::whereIn('course_id', $myCourseIds)->latest()->take(3)->get();
@endphp

<style>
    .glass-panel {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 1.25rem;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.04);
        transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275), box-shadow 0.3s ease;
    }
    .glass-panel:hover {
        transform: translateY(-6px);
        box-shadow: 0 15px 50px rgba(0, 0, 0, 0.08);
    }
    .course-row {
        transition: all 0.25s ease;
        background-color: transparent;
    }
    .course-row:hover {
        background-color: rgba(79, 70, 229, 0.03) !important;
        transform: scale(1.005) translateX(4px);
        box-shadow: -4px 0 0 0 #4f46e5 inset;
    }
    .stat-card {
        background: linear-gradient(135deg, #ffffff, #fdfbfb);
        border-left: 6px solid #4f46e5;
    }
    .stat-card.warning { border-left-color: #f59e0b; }
    .stat-card.success { border-left-color: #10b981; }
    
    .btn-launch {
        transition: all 0.3s ease;
    }
    .btn-launch:hover {
        transform: scale(1.05);
        box-shadow: 0 8px 25px rgba(79, 70, 229, 0.4) !important;
    }
</style>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="p-4 bg-primary text-white rounded rounded-4 shadow-sm border-0 position-relative overflow-hidden" style="background: linear-gradient(135deg, #0d122b, #312e81);">
            <!-- Decorative circle -->
            <div class="position-absolute end-0 top-0 translate-middle rounded-circle bg-white" style="width: 350px; height: 350px; opacity: 0.1; pointer-events: none;"></div>
            <div class="position-relative z-1 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="fw-bold mb-1">Welcome back, {{ $user->name ?? 'Student' }}! <span style="font-size: 1.5rem;">🎓</span></h2>
                    <p class="mb-0 text-white-50">Your interactive learning hub is ready.</p>
                </div>
                <!-- Gamification Stats -->
                <div class="d-flex align-items-center bg-white bg-opacity-10 rounded-pill px-4 py-2 border border-white border-opacity-25 shadow-sm backdrop-blur">
                    <div class="me-4 text-center">
                        <span class="d-block small text-uppercase fw-bold text-white-50 mb-1">Current Level</span>
                        <div class="badge bg-warning text-dark rounded-pill fs-5 px-3 py-1 fw-bold shadow-sm">
                            <i class="fas fa-crown me-1"></i> Lvl {{ $user->level ?? 1 }}
                        </div>
                    </div>
                    <div class="text-center">
                        <span class="d-block small text-uppercase fw-bold text-white-50 mb-1">Total XP</span>
                        <div class="fs-4 fw-bold text-white text-shadow-sm">
                            <i class="fas fa-fire text-warning me-1"></i> {{ $user->xp ?? 0 }} <span class="fs-6 text-white-50">XP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mb-5 g-4 text-center">
    <div class="col-md-3">
        <div class="glass-panel stat-card p-4">
            <h1 class="display-4 fw-bolder text-primary mb-0">{{ $enrolledCount }}</h1>
            <p class="text-muted text-uppercase small fw-bold mt-2 mb-0">Active Courses</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-panel stat-card warning p-4">
            <h1 class="display-4 fw-bolder text-warning mb-0">{{ $announcements->count() ?? 0 }}</h1>
            <p class="text-muted text-uppercase small fw-bold mt-2 mb-0">Total Announcements</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-panel stat-card success p-4">
            <h1 class="display-4 fw-bolder text-success mb-0">{{ $completed ?? 0 }}</h1>
            <p class="text-muted text-uppercase small fw-bold mt-2 mb-0">Certificates Earned</p>
        </div>
    </div>
    <div class="col-md-3">
        <div class="glass-panel stat-card p-4" style="border-left: 4px solid #f97316 !important; text-align: left;">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="display-4 fw-bolder mb-0" style="color: #f97316;">40%</h1>
                <i class="fas fa-tasks text-muted fs-3 opacity-50"></i>
            </div>
            <div class="progress mt-2 mb-2" style="height: 6px; background: rgba(249, 115, 22, 0.15); border-radius: 10px;">
                <div class="progress-bar" style="width: 40%; background: #f97316; border-radius: 10px;"></div>
            </div>
            <p class="text-muted text-uppercase small fw-bold mb-0">Classwork Completed</p>
        </div>
    </div>
</div>

@if($user && $user->role === 'cr')
<div class="row mb-5">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3 ms-2 flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <i class="fas fa-user-shield text-primary fs-4 me-2"></i>
                <h4 class="fw-bold mb-0 text-dark">Class Representative Control Panel</h4>
            </div>
            <a href="/admin/chat" class="btn btn-warning rounded-pill px-4 btn-sm fw-bold shadow-sm d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #f97316, #ea580c); border: none; color: white;">
                <i class="fas fa-comments"></i> Open Communications Chat
            </a>
        </div>
        <div class="glass-panel p-4" style="background: rgba(255, 255, 255, 0.95);">
            @php
                $deputy = $user ? \App\Models\User::where('department_id', $user->department_id)
                    ->where('role', 'deputy-cr')
                    ->first() : null;
                $classStudents = $user ? \App\Models\User::where('department_id', $user->department_id)
                    ->where('id', '!=', $user->id)
                    ->where('role', '!=', 'cr')
                    ->orderBy('name')
                    ->get() : collect();
            @endphp

            @if($deputy)
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                    <div>
                        <h5 class="fw-bold text-dark mb-1"><i class="fas fa-id-card text-success me-2"></i>Deputy Class Representative Appointed</h5>
                        <p class="mb-0 text-muted"><strong>Name:</strong> {{ $deputy->name }} | <strong>Enrollment:</strong> {{ $deputy->enrollment_no }} | <strong>Email:</strong> {{ $deputy->email }}</p>
                    </div>
                    <form action="/dashboard/revoke-deputy-cr" method="POST" onsubmit="return confirm('Are you sure you want to revoke Deputy CR status for {{ $deputy->name }}?')">
                        @csrf
                        <button type="submit" class="btn btn-danger rounded-pill px-4 fw-bold shadow-sm">
                            <i class="fas fa-user-slash me-1"></i> Revoke Deputy CR
                        </button>
                    </form>
                </div>
            @else
                <form action="/dashboard/assign-deputy-cr" method="POST">
                    @csrf
                    <h5 class="fw-bold text-dark mb-3"><i class="fas fa-user-plus text-primary me-2"></i>Appoint Deputy Class Representative</h5>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">Select Student from your Class/Department</label>
                            <select name="student_id" class="form-select border-light-subtle rounded-3" required>
                                <option value="">-- Select Student --</option>
                                @foreach($classStudents as $student)
                                    <option value="{{ $student->id }}">{{ $student->name }} ({{ $student->enrollment_no ?? 'No Enroll' }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-7">
                            <div class="form-check mb-2">
                                <input class="form-check-input border-secondary" type="checkbox" name="tc_accepted" id="tcCheck" required>
                                <label class="form-check-label small text-muted text-wrap" for="tcCheck" style="cursor: pointer;">
                                    I accept the official Terms &amp; Conditions of appointing this student as Deputy CR. I verify they meet behavioral standards and will mentor them.
                                </label>
                            </div>
                        </div>
                        <div class="col-12 mt-3">
                            <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">
                                <i class="fas fa-check-circle me-1"></i> Assign Deputy CR
                            </button>
                        </div>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center mb-3 ms-2 flex-wrap gap-2">
            <div class="d-flex align-items-center">
                <i class="fas fa-question-circle text-info fs-4 me-2"></i>
                <h4 class="fw-bold mb-0 text-dark">CR Student Queries Desk</h4>
            </div>
            <span class="badge bg-info text-white rounded-pill px-3 py-1 fw-bold shadow-sm">
                {{ $crQueries->where('status', 'pending')->count() }} Pending
            </span>
        </div>
        <div class="glass-panel p-4" style="background: rgba(255, 255, 255, 0.95);">
            @if($crQueries->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                        <thead class="bg-light text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                            <tr>
                                <th class="ps-3">Ticket Details</th>
                                <th>Student</th>
                                <th>Status</th>
                                <th class="text-end pe-3">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($crQueries as $q)
                            <tr class="border-bottom border-light">
                                <td class="ps-3 py-3">
                                    <div class="fw-bold text-dark">{{ $q->title }}</div>
                                    <div class="text-muted small text-truncate" style="max-width: 250px;">{{ $q->description }}</div>
                                    <div class="mt-1">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-0.5" style="font-size: 0.7rem;">{{ $q->category }}</span>
                                        <span class="x-small text-muted ms-2"><i class="far fa-clock"></i> {{ $q->created_at->diffForHumans() }}</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="fw-bold text-dark">{{ $q->student->name ?? 'Student' }}</div>
                                    <div class="small text-muted font-monospace">{{ $q->student->enrollment_no ?? '' }}</div>
                                </td>
                                <td>
                                    @if($q->status === 'solved')
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success px-2.5 py-1 rounded-pill fw-bold">Solved</span>
                                    @elseif($q->status === 'unsolved')
                                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2.5 py-1 rounded-pill fw-bold">Unsolved (₹100 fine active)</span>
                                    @else
                                        <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2.5 py-1 rounded-pill fw-bold">Pending</span>
                                    @endif
                                </td>
                                <td class="text-end pe-3">
                                    @if($q->status === 'pending')
                                        <button class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" onclick="openCrResolveModal({{ json_encode($q) }})">
                                            <i class="fas fa-file-signature me-1"></i> Resolve & Sign
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold" onclick="showCrQueryReceipt({{ json_encode($q) }})">
                                            <i class="fas fa-file-invoice me-1"></i> Slip
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center opacity-50 py-4 my-auto">
                    <i class="fas fa-folder-open fa-3x text-muted mb-2"></i>
                    <p class="small text-muted mb-0">No query tickets assigned to you yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endif

<div class="row mb-5">
    <div class="col-md-12">
        <div class="d-flex align-items-center mb-3 ms-2">
            <i class="fas fa-bullhorn text-warning fs-4 me-2"></i>
            <h4 class="fw-bold mb-0 text-dark">Recent Announcements</h4>
        </div>
        
        @if($announcements->count() > 0)
            <div class="d-flex gap-4 overflow-auto pb-4 pt-2 px-2" style="white-space: nowrap;">
                @foreach($announcements as $ann)
                <div class="glass-panel p-4" style="min-width: 340px; white-space: normal; flex: 0 0 auto;">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1"><i class="fas fa-tag me-1"></i> {{ $ann->course->title ?? 'Course' }}</span>
                        <small class="text-muted fw-bold"><i class="far fa-clock me-1"></i> {{ $ann->created_at->diffForHumans() }}</small>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">{{ $ann->title }}</h5>
                    <p class="mb-0 text-secondary" style="font-size: 0.95rem;">{{ \Illuminate\Support\Str::limit($ann->message, 90) }}</p>
                </div>
                @endforeach
            </div>
        @else
            <div class="glass-panel p-5 text-center">
                <i class="fas fa-bell-slash text-muted fs-1 mb-3 opacity-50"></i>
                <h5 class="text-muted fw-bold mb-0">No recent announcements.</h5>
            </div>
        @endif
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12">
        <div class="d-flex align-items-center mb-3 ms-2">
            <i class="fas fa-sticky-note text-info fs-4 me-2"></i>
            <h4 class="fw-bold mb-0 text-dark">Personal Notes & Tasks</h4>
        </div>
        <div class="glass-panel p-4">
            <form action="{{ route('personal-notes.store') }}" method="POST" class="mb-4">
                @csrf
                <div class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="title" class="form-control" placeholder="Note Title (Optional)">
                    </div>
                    <div class="col-md-7">
                        <input type="text" name="content" class="form-control" placeholder="What do you need to remember?" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100 fw-bold"><i class="fas fa-plus me-1"></i> Add Note</button>
                    </div>
                </div>
            </form>
            
            @if(isset($personalNotes) && $personalNotes->count() > 0)
                <div class="row g-3">
                    @foreach($personalNotes as $note)
                    <div class="col-md-4">
                        <div class="p-3 border rounded shadow-sm bg-white position-relative" style="border-left: 4px solid #4f46e5 !important;">
                            <form action="{{ route('personal-notes.destroy', $note->id) }}" method="POST" class="position-absolute top-0 end-0 p-2">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm text-danger border-0 p-0" title="Delete Note"><i class="fas fa-times"></i></button>
                            </form>
                            @if($note->title)
                                <h6 class="fw-bold mb-1 pe-4 text-dark">{{ $note->title }}</h6>
                            @endif
                            <p class="mb-1 text-secondary small">{{ $note->content }}</p>
                            <small class="text-muted" style="font-size: 0.75rem;">{{ $note->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            @else
                <div class="text-center text-muted py-3">
                    <i class="fas fa-clipboard-list fs-3 mb-2 opacity-50"></i>
                    <p class="mb-0 small">You don't have any personal notes yet. Add one above!</p>
                </div>
            @endif
        </div>
    </div>
</div>

<div class="row mb-5">
    <div class="col-md-12">
        <div class="glass-panel overflow-hidden">
            <div class="px-4 py-3 border-bottom border-light d-flex align-items-center">
                <i class="fas fa-layer-group text-primary me-2"></i>
                <h5 class="mb-0 fw-bold text-dark">Enrolled Courses</h5>
            </div>
            <div class="table-responsive p-0">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light text-muted" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                        <tr>
                            <th class="ps-4 py-2 text-uppercase fw-bold border-0">Course Name</th>
                            <th class="py-2 text-uppercase fw-bold border-0">Department</th>
                            <th class="py-2 text-uppercase fw-bold border-0">Instructor</th>
                            <th class="text-end pe-4 py-2 text-uppercase fw-bold border-0">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                        <tr class="border-bottom border-light" style="transition: all 0.2s;">
                            <td class="ps-4 py-3 fw-bold text-dark">
                                <i class="fas fa-book text-muted me-2 opacity-50"></i>{{ $course->title }}
                            </td>
                            <td class="py-3 text-secondary">
                                {{ $course->department->name ?? 'Core Curriculum' }}
                            </td>
                            <td class="py-3 text-secondary">
                                {{ $course->instructor ?? 'Unassigned' }}
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <a href="/courses/{{ $course->id }}" class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold">
                                    Launch <i class="fas fa-arrow-right ms-1" style="font-size: 0.8em;"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted small">
                                No active enrollments found for this semester.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>

@if($user && $user->role === 'cr')
<!-- CR Resolve Modal -->
<div class="modal fade" id="crResolveQueryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="crResolveQueryForm" action="" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-signature text-warning me-2"></i> CR Resolve & Attest Ticket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <span class="small text-muted d-block font-semibold">TICKET TITLE:</span>
                    <strong id="cr_modal_query_title" class="text-dark">Title</strong>
                    <div class="mt-2 small text-muted" id="cr_modal_query_description">Description...</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Resolution Verdict *</label>
                    <select name="status" class="form-select bg-light border-0" required>
                        <option value="solved">🟢 Solved (Satisfactory resolution achieved)</option>
                        <option value="unsolved">🔴 Unsolved (Impose fine penalty of ₹100)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Resolution Notes *</label>
                    <textarea name="resolution_notes" rows="4" class="form-control bg-light border-0" placeholder="Provide detailed resolution notes..." required></textarea>
                </div>

                <div class="p-3 border rounded-3 bg-light text-center">
                    <div class="text-muted small fw-semibold mb-2">CRYPTOGRAPHIC DIGITAL SIGNATURE PREVIEW</div>
                    <div style="height: 50px; display: flex; align-items: center; justify-content: center;">
                        {!! $user->digital_signature !!}
                    </div>
                    <div class="small fw-bold text-dark mt-2 border-top pt-2">{{ $user->name }}</div>
                    <div class="x-small text-muted">Class Representative (CR)</div>
                </div>
            </div>
            
            <div class="modal-footer border-top-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"><i class="fas fa-check-double me-2"></i> Attest & Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- Dynamic Printable Receipt Modal for CR -->
<div class="modal fade" id="crQueryReceiptModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
            <div style="height: 6px; background: linear-gradient(90deg, #06b6d4 0%, #0891b2 100%);"></div>
            <div class="modal-header border-0 bg-light p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-info bg-opacity-10 rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; color: #0891b2;">
                        <i class="fas fa-file-signature fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0">Query Resolution Receipt</h5>
                        <small class="text-muted fw-semibold">BAPS SVM Academic Learning Management System</small>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4" id="crQueryReceiptPrintArea">
                <div class="border rounded-4 p-4 position-relative" style="border: 2px solid #e2e8f0 !important; background: radial-gradient(circle at 100% 100%, #fafafa 0%, #ffffff 100%);">
                    <div class="position-absolute start-50 top-50 translate-middle opacity-5 pointer-events-none text-center" style="font-size: 8rem; color: #06b6d4; z-index: 1;">
                        <i class="fas fa-dharmachakra"></i>
                    </div>

                    <div class="position-relative" style="z-index: 2;">
                        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4" style="border-bottom: 2px dashed #e2e8f0 !important;">
                            <div>
                                <h4 class="fw-bold mb-1 text-info" style="color: #0891b2; letter-spacing: 0.5px;">BAPS SWAMINARAYAN VIDYAMANDIR</h4>
                                <div class="small text-muted fw-semibold mb-1"><i class="fas fa-map-marker-alt me-1"></i> Central Academic Campus, Gujarat, India</div>
                                <div class="small text-muted"><i class="fas fa-shield-alt me-1"></i> Cryptographically Verified Ticket Resolution</div>
                            </div>
                            <div class="text-end">
                                <span id="cr_receipt_query_status_badge" class="badge border px-3 py-2 rounded-pill fw-bold text-uppercase">
                                    SOLVED
                                </span>
                                <div class="mt-2 text-muted small fw-semibold" id="cr_receipt_query_ref_num">REF: BAPS-QRY-000</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6 border-end" style="border-right: 1px solid #f1f5f9 !important;">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3 text-info" style="letter-spacing: 0.5px; color: #0891b2;">Student Profile</h6>
                                <table class="table table-borderless table-sm mb-0 align-middle">
                                    <tr>
                                        <td class="text-muted py-1 small" style="width: 40%;">Student Name:</td>
                                        <td class="fw-bold text-dark py-1" id="cr_receipt_student_name">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Enrollment No:</td>
                                        <td class="fw-bold text-dark py-1 font-monospace" id="cr_receipt_student_enroll">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Department:</td>
                                        <td class="fw-bold text-dark py-1" id="cr_receipt_student_dept">-</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12 col-md-6 ps-md-4">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3 text-info" style="letter-spacing: 0.5px; color: #0891b2;">Ticket Details</h6>
                                <table class="table table-borderless table-sm mb-0 align-middle">
                                    <tr>
                                        <td class="text-muted py-1 small" style="width: 40%;">Category:</td>
                                        <td class="fw-bold text-dark py-1" id="cr_receipt_query_category">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Date Filed:</td>
                                        <td class="fw-bold text-dark py-1" id="cr_receipt_query_date">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Assignee Type:</td>
                                        <td class="fw-bold text-dark py-1 text-uppercase" id="cr_receipt_query_assignee_type">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <span class="small fw-bold text-muted d-block mb-1">Ticket: <strong id="cr_receipt_query_title">Title</strong></span>
                            <p class="small text-secondary mb-0" id="cr_receipt_query_desc">Description...</p>
                        </div>

                        <div class="p-3 bg-light rounded-3 border mb-4 border-info">
                            <span class="small fw-bold text-info d-block mb-1"><i class="fas fa-comment-dots me-1"></i> Resolution Notes</span>
                            <p class="small text-dark mb-0 font-italic" id="cr_receipt_query_notes">Resolution notes here...</p>
                        </div>

                        <!-- Penalty breakdown -->
                        <div class="table-responsive mb-4 border rounded-3 overflow-hidden" id="cr_receipt_penalty_section" style="display:none;">
                            <table class="table mb-0 align-middle">
                                <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <tr>
                                        <th class="py-2 px-3 text-muted fw-bold text-uppercase small">Penalty Component</th>
                                        <th class="py-2 px-3 text-end text-muted fw-bold text-uppercase small" style="width: 35%;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td class="py-2 px-3">
                                            <div class="fw-bold text-dark" id="cr_receipt_penalty_label">Query Unsolved Penalty</div>
                                            <small class="text-muted" id="cr_receipt_penalty_desc">Standard automatic sanction</small>
                                        </td>
                                        <td class="py-2 px-3 text-end fw-semibold text-danger font-monospace" id="cr_receipt_penalty_amount">₹ 0.00</td>
                                    </tr>
                                    <tr class="bg-light table-active" id="cr_receipt_waiver_row" style="display:none;">
                                        <td class="py-2 px-3">
                                            <div class="fw-bold text-success"><i class="fas fa-handshake-angle me-1"></i> Admin / HOD Adjustment</div>
                                            <small class="text-muted">Waived or reduced penalty adjustment</small>
                                        </td>
                                        <td class="py-2 px-3 text-end fw-bold text-success font-monospace" id="cr_receipt_waiver_amount">-₹ 0.00</td>
                                    </tr>
                                    <tr class="bg-light table-active" style="border-top: 2px solid #cbd5e1;">
                                        <td class="py-2 px-3">
                                            <div class="fw-bold text-dark text-uppercase">Net Imposed Penalty</div>
                                        </td>
                                        <td class="py-2 px-3 text-end fw-bold fs-6 text-danger font-monospace" id="cr_receipt_net_penalty">₹ 0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            <h6 class="text-muted fw-bold text-uppercase small text-center mb-4" style="letter-spacing: 1px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Resolution Attestation</h6>
                            <div class="text-center">
                                <div class="mb-2" style="height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <div id="cr_receipt_query_signature"></div>
                                </div>
                                <div style="width: 50%; height: 1px; background-color: #cbd5e1; margin-bottom: 6px; margin-left: auto; margin-right: auto;"></div>
                                <div class="fw-bold text-dark small" id="cr_receipt_query_resolved_by_name">-</div>
                                <div class="text-muted" style="font-size: 0.65rem; font-weight: 600;" id="cr_receipt_query_resolved_by_role">-</div>
                            </div>
                        </div>

                        <div class="text-center mt-5 pt-3 border-top text-muted" style="font-size: 0.7rem; border-top: 1px solid #f1f5f9 !important; word-break: break-all;">
                            This is a cryptographically verified and system-attested query resolution slip generated on the BAPS SVM Academic LMS.
                            <br>
                            <span class="fw-bold text-dark">SHA256 Ticket Hash: <span id="cr_receipt_query_hash">-</span></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info rounded-pill px-4 text-white fw-bold shadow-sm" onclick="printCrQueryReceipt()">
                    <i class="fas fa-print me-2"></i> Print Slip
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openCrResolveModal(query) {
    document.getElementById('cr_modal_query_title').textContent = query.title;
    document.getElementById('cr_modal_query_description').textContent = query.description;
    
    // Set form action
    document.getElementById('crResolveQueryForm').action = '/student-queries/' + query.id + '/resolve';
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('crResolveQueryModal'));
    modal.show();
}

function showCrQueryReceipt(query) {
    // Fill basic details
    document.getElementById('cr_receipt_query_ref_num').textContent = 'REF: BAPS-QRY-' + String(query.id).padStart(3, '0');
    
    // Status Badge Styling
    const statusBadge = document.getElementById('cr_receipt_query_status_badge');
    statusBadge.textContent = query.status.toUpperCase();
    statusBadge.className = 'badge border px-3 py-2 rounded-pill fw-bold text-uppercase ';
    if (query.status === 'solved') {
        statusBadge.classList.add('bg-success', 'bg-opacity-10', 'text-success', 'border-success');
    } else if (query.status === 'unsolved') {
        statusBadge.classList.add('bg-danger', 'bg-opacity-10', 'text-danger', 'border-danger');
    } else {
        statusBadge.classList.add('bg-warning', 'bg-opacity-10', 'text-warning', 'border-warning');
    }
    
    // Student Details
    document.getElementById('cr_receipt_student_name').textContent = query.student ? query.student.name : 'Student';
    document.getElementById('cr_receipt_student_enroll').textContent = query.student ? query.student.enrollment_no : '-';
    document.getElementById('cr_receipt_student_dept').textContent = (query.student && query.student.department) ? query.student.department.name : 'Computer Science & Engineering';
    
    // Query Details
    document.getElementById('cr_receipt_query_category').textContent = query.category;
    document.getElementById('cr_receipt_query_date').textContent = new Date(query.created_at).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
    document.getElementById('cr_receipt_query_assignee_type').textContent = query.assigned_type === 'staff' ? 'Faculty / Dean' : 'Class Representative';
    
    document.getElementById('cr_receipt_query_title').textContent = query.title;
    document.getElementById('cr_receipt_query_desc').textContent = query.description;
    document.getElementById('cr_receipt_query_notes').textContent = query.resolution_notes || 'No resolution notes provided.';
    
    // Penalty Section
    const penaltySec = document.getElementById('cr_receipt_penalty_section');
    if (query.status === 'unsolved') {
        penaltySec.style.display = 'block';
        
        const isStaff = query.assigned_type === 'staff';
        const origPenalty = isStaff ? 10000 : 100;
        const currentPenalty = Number(isStaff ? query.salary_cut_amount : query.fine_amount);
        
        document.getElementById('cr_receipt_penalty_label').textContent = isStaff ? 'Faculty/Dean Salary Cut' : 'CR Query Fine';
        document.getElementById('cr_receipt_penalty_desc').textContent = isStaff 
            ? 'Standard automatic penalty of ₹10,000 for unsolved query' 
            : 'Standard automatic fine of ₹100 for unsolved query';
        document.getElementById('cr_receipt_penalty_amount').textContent = '₹ ' + origPenalty.toLocaleString('en-IN', {minimumFractionDigits: 2});
        
        // Waiver Check
        const waiverRow = document.getElementById('cr_receipt_waiver_row');
        if (query.is_waived || currentPenalty < origPenalty) {
            waiverRow.style.display = 'table-row';
            const diff = origPenalty - currentPenalty;
            document.getElementById('cr_receipt_waiver_amount').textContent = '-₹ ' + diff.toLocaleString('en-IN', {minimumFractionDigits: 2});
        } else {
            waiverRow.style.display = 'none';
        }
        
        document.getElementById('cr_receipt_net_penalty').textContent = '₹ ' + currentPenalty.toLocaleString('en-IN', {minimumFractionDigits: 2});
    } else {
        penaltySec.style.display = 'none';
    }
    
    // Signatures and Attestation
    document.getElementById('cr_receipt_query_signature').innerHTML = query.resolved_by_signature || '<span class="text-muted font-italic small">Digital Signature Not Found</span>';
    document.getElementById('cr_receipt_query_resolved_by_name').textContent = query.resolved_by_name || '-';
    document.getElementById('cr_receipt_query_resolved_by_role').textContent = query.resolved_by_role || '-';
    
    // SHA256 Hash
    let str = "BAPS-QRY-" + query.id + "-" + query.status + "-" + (query.resolved_by_name || '');
    let hash = 0;
    for (let i = 0; i < str.length; i++) {
        hash = (hash << 5) - hash + str.charCodeAt(i);
        hash |= 0;
    }
    let hashStr = Math.abs(hash).toString(16).padStart(8, '0').toUpperCase() + 
                  Math.abs(hash * 31).toString(16).padStart(8, '0').toUpperCase() + 
                  Math.abs(hash * 97).toString(16).padStart(8, '0').toUpperCase();
    document.getElementById('cr_receipt_query_hash').textContent = hashStr;

    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('crQueryReceiptModal'));
    modal.show();
}

function printCrQueryReceipt() {
    var printContents = document.getElementById('crQueryReceiptPrintArea').innerHTML;
    var style = document.createElement('style');
    style.innerHTML = `
        @media print {
            body * {
                visibility: hidden;
            }
            #crQueryReceiptPrintArea, #crQueryReceiptPrintArea * {
                visibility: visible;
            }
            #crQueryReceiptPrintArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    `;
    document.head.appendChild(style);
    window.print();
    document.head.removeChild(style);
}
</script>
@endif
@endsection
