@php
    $currentStaffId = session('staff_id') ?? 1;
    $currentStaff = \App\Models\Staff::find($currentStaffId);
    $currentRole = session('user_role');

    // If the staff member is Admin or Dean, let them see and review ALL requests in the system for testing/oversight
    $isAdminOrDean = in_array($currentRole, ['admin', 'dean']);

    $myPendingReviewsQuery = \App\Models\CodeReviewRequest::where('status', 'pending')->with('user');
    if (!$isAdminOrDean) {
        $myPendingReviewsQuery->where('mentor_id', $currentStaffId);
    }
    $myPendingReviews = $myPendingReviewsQuery->latest()->get();

    $myCompletedReviewsQuery = \App\Models\CodeReviewRequest::where('status', 'reviewed')->with(['user', 'feedback']);
    if (!$isAdminOrDean) {
        $myCompletedReviewsQuery->where('mentor_id', $currentStaffId);
    }
    $myCompletedReviews = $myCompletedReviewsQuery->latest()->get();

    // Fetch privilege applications for staff (Admin, Dean, HOD, Office Assistant)
    $isStaffManager = in_array($currentRole, ['admin', 'dean', 'office-assistant', 'hod']);
    
    $pendingPrivileges = $isStaffManager 
        ? \App\Models\PrivilegeApplication::where('status', 'pending')->with(['user', 'feedback.request.mentor'])->latest()->get()
        : collect();

    $processedPrivileges = $isStaffManager 
        ? \App\Models\PrivilegeApplication::whereIn('status', ['approved', 'rejected'])->with(['user', 'feedback.request.mentor', 'processor'])->latest()->get()
        : collect();
@endphp

<!-- Syntax Highlighting & Markdown CDNs -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>


<div class="tab-pane fade" id="tab-synergy-circle" role="tabpanel">
    <div class="content-card">
        <div class="content-card-header">
            <div class="content-card-title">
                <i class="fas fa-circle-nodes text-indigo" style="color: #6366f1;"></i> Synergy Circle Management Console
            </div>
            <span class="badge bg-indigo text-white px-3 py-1.5 rounded-pill" style="background: #4f46e5;">Interactive LMS Network</span>
        </div>

        <p class="text-muted mb-4">
            Connect students with advanced code reviews and dynamic credentialing. Review pending programming snippets to issue verified badges, or process lab privilege applications to authorize GPU clusters, overnight access, or IoT hardware slot assignments.
        </p>

        <!-- MENTOR WORKSPACE (Visible to Faculty & Higher roles) -->
        @if(in_array($currentRole, ['admin', 'dean', 'hod', 'faculty', 'coordinator', 'faculty-lecturer-coordinator']))
            <div class="mb-5">
                <h4 class="fw-bold text-dark mb-3"><i class="fas fa-chalkboard-teacher text-primary me-2"></i> Mentor Review Desk</h4>
                
                <ul class="nav nav-tabs mb-3" id="mentorTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold text-secondary" id="pending-reviews-tab" data-bs-toggle="tab" data-bs-target="#pending-reviews" type="button">
                            Pending Reviews <span class="badge bg-danger ms-1">{{ $myPendingReviews->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="completed-reviews-tab" data-bs-toggle="tab" data-bs-target="#completed-reviews" type="button">
                            Completed Reviews <span class="badge bg-secondary ms-1">{{ $myCompletedReviews->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="mentorTabsContent">
                    <!-- Pending Reviews Pane -->
                    <div class="tab-pane fade show active" id="pending-reviews" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Title & Language</th>
                                        <th>Category</th>
                                        <th>Submitted</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myPendingReviews as $review)
                                        <tr class="border-bottom border-light">
                                            <td>
                                                <div class="fw-bold text-dark">{{ $review->user->name }}</div>
                                                <small class="text-muted">Enrollment: {{ $review->user->enrollment_no }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold">{{ $review->title }}</div>
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-0.5" style="font-size:0.75rem;">{{ strtoupper($review->language) }}</span>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1">{{ $review->category }}</span>
                                            </td>
                                            <td>
                                                <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $review->created_at->diffForHumans() }}</small>
                                            </td>
                                            <td class="text-end">
                                                <button class="btn btn-sm btn-primary px-3 rounded-pill fw-bold" onclick="openReviewModal({{ json_encode($review) }})">
                                                    <i class="fas fa-edit me-1"></i> Review & Sign
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-clipboard-check fs-2 mb-2 opacity-50"></i>
                                                <p class="mb-0">No pending reviews assigned to you. Outstanding job!</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Completed Reviews Pane -->
                    <div class="tab-pane fade" id="completed-reviews" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Title / Hash</th>
                                        <th>Category</th>
                                        <th>Rating</th>
                                        <th>Reviewed Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($myCompletedReviews as $review)
                                        <tr class="border-bottom border-light">
                                            <td>
                                                <div class="fw-bold text-dark">{{ $review->user->name }}</div>
                                                <small class="text-muted">Enrollment: {{ $review->user->enrollment_no }}</small>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-secondary">{{ $review->title }}</div>
                                                <strong class="text-warning font-monospace small" style="font-size: 0.8rem;">{{ $review->feedback->badge_hash ?? 'N/A' }}</strong>
                                            </td>
                                            <td>
                                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1">{{ $review->category }}</span>
                                            </td>
                                            <td>
                                                <div class="text-warning">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="fas fa-star {{ $i <= ($review->feedback->rating ?? 5) ? '' : 'far text-muted' }}"></i>
                                                    @endfor
                                                </div>
                                            </td>
                                            <td>
                                                <small class="text-muted"><i class="far fa-calendar-check me-1"></i> {{ $review->feedback ? $review->feedback->created_at->format('M d, Y') : 'N/A' }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-folder-open fs-2 mb-2 opacity-50"></i>
                                                <p class="mb-0">You have not completed any code reviews yet.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- PRIVILEGES DESK (Visible to Administrators/Managers) -->
        @if($isStaffManager)
            <div class="border-top pt-4">
                <h4 class="fw-bold text-dark mb-3"><i class="fas fa-key text-success me-2"></i> Lab Access Privilege Desk</h4>
                
                <ul class="nav nav-tabs mb-3" id="privilegeTabs" role="tablist">
                    <li class="nav-item">
                        <button class="nav-link active fw-bold text-secondary" id="pending-privileges-tab" data-bs-toggle="tab" data-bs-target="#pending-privileges" type="button">
                            Pending Permissions <span class="badge bg-danger ms-1">{{ $pendingPrivileges->count() }}</span>
                        </button>
                    </li>
                    <li class="nav-item">
                        <button class="nav-link fw-bold text-secondary" id="processed-privileges-tab" data-bs-toggle="tab" data-bs-target="#processed-privileges" type="button">
                            Review Log <span class="badge bg-secondary ms-1">{{ $processedPrivileges->count() }}</span>
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="privilegeTabsContent">
                    <!-- Pending Permissions Pane -->
                    <div class="tab-pane fade show active" id="pending-privileges" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Requested Privilege</th>
                                        <th>Supporting Badge</th>
                                        <th>Justification</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingPrivileges as $p)
                                        <tr class="border-bottom border-light">
                                            <td>
                                                <div class="fw-bold text-dark">{{ $p->user->name }}</div>
                                                <small class="text-muted">Dept: {{ $p->user->department->name ?? 'CSE' }}</small>
                                            </td>
                                            <td>
                                                <span class="badge bg-dark text-white px-2.5 py-1 rounded">{{ $p->privilege_type }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-primary font-monospace" style="font-size:0.85rem;">{{ $p->feedback->badge_hash }}</strong>
                                                <div class="small text-muted">Signed by: {{ $p->feedback->reviewer->name ?? 'Faculty' }}</div>
                                            </td>
                                            <td>
                                                <div class="small text-secondary" style="max-width: 250px; line-height: 1.4;">"{{ $p->justification }}"</div>
                                            </td>
                                            <td class="text-end">
                                                <div class="d-flex gap-2 justify-content-end">
                                                    <form action="/admin/synergy-circle/privilege/{{ $p->id }}/process" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="approved">
                                                        <button type="submit" class="btn btn-sm btn-success px-2.5 rounded-pill fw-bold" title="Grant Lab Privilege">
                                                            <i class="fas fa-check me-1"></i> Approve
                                                        </button>
                                                    </form>
                                                    <form action="/admin/synergy-circle/privilege/{{ $p->id }}/process" method="POST" class="d-inline">
                                                        @csrf
                                                        <input type="hidden" name="status" value="rejected">
                                                        <button type="submit" class="btn btn-sm btn-outline-danger px-2.5 rounded-pill fw-bold" title="Reject Request">
                                                            <i class="fas fa-times me-1"></i> Reject
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-inbox fs-2 mb-2 opacity-50"></i>
                                                <p class="mb-0">No pending permission requests.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Processed Permissions Log Pane -->
                    <div class="tab-pane fade" id="processed-privileges" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Student</th>
                                        <th>Requested Privilege</th>
                                        <th>Supporting Badge</th>
                                        <th>Decision Status</th>
                                        <th>Processed By</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($processedPrivileges as $p)
                                        <tr class="border-bottom border-light">
                                            <td>
                                                <div class="fw-bold text-dark">{{ $p->user->name }}</div>
                                                <small class="text-muted">Enrollment: {{ $p->user->enrollment_no }}</small>
                                            </td>
                                            <td>
                                                <strong class="text-secondary small">{{ $p->privilege_type }}</strong>
                                            </td>
                                            <td>
                                                <strong class="text-primary font-monospace">{{ $p->feedback->badge_hash }}</strong>
                                            </td>
                                            <td>
                                                <span class="status-badge status-{{ $p->status }}">{{ ucfirst($p->status) }}</span>
                                            </td>
                                            <td>
                                                <div class="fw-bold text-dark">{{ $p->processor->name ?? 'Staff Administrator' }}</div>
                                                <small class="text-muted"><i class="far fa-calendar-alt me-1"></i> {{ $p->updated_at->format('M d, Y') }}</small>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center py-4 text-muted">
                                                <i class="fas fa-folder-open fs-2 mb-2 opacity-50"></i>
                                                <p class="mb-0">No processed permission logs found.</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>

<!-- Mentor Feedback Submission Modal -->
<div class="modal fade" id="adminReviewModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold"><i class="fas fa-code-commit me-2 text-info"></i> Faculty Evaluation Panel</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form id="adminReviewForm" action="" method="POST">
                @csrf
                <div class="modal-body p-4">
                    <div class="row g-3">
                        <div class="col-md-12">
                            <div class="p-3 bg-light rounded-3 mb-3">
                                <h5 class="fw-bold text-dark mb-1" id="reviewModalTitle">Code Submission</h5>
                                <div class="small text-muted mb-2">Student: <strong id="reviewModalStudent">Bhavik Patel</strong> | Language: <strong class="text-primary text-uppercase" id="reviewModalLanguage">JavaScript</strong></div>
                                <div class="small text-secondary" id="reviewModalDescription">Context info...</div>
                            </div>
                        </div>

                        <!-- Code Display -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary">Code Snippet Preview</label>
                            <pre class="m-0 rounded-3 overflow-hidden shadow-sm" style="max-height: 250px;"><code id="reviewModalCode" class="language-javascript"></code></pre>
                        </div>

                        <!-- Star Rating -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary">Score Rating (1-5 Stars)</label>
                            <select name="rating" class="form-select" required>
                                <option value="5">⭐⭐⭐⭐⭐ (5 - Perfect)</option>
                                <option value="4">⭐⭐⭐⭐ (4 - Excellent)</option>
                                <option value="3">⭐⭐⭐ (3 - Competent)</option>
                                <option value="2">⭐⭐ (2 - Needs Work)</option>
                                <option value="1">⭐ (1 - Major Issues)</option>
                            </select>
                        </div>

                        <!-- Digital Signature Selector -->
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-secondary">Verify Signature Type</label>
                            <select name="signature_type" id="adminSigType" class="form-select" required>
                                <option value="mapped">Mapped Signature (SVG Profile)</option>
                                <option value="manual">Manual Cursive Signature</option>
                            </select>
                        </div>

                        <!-- Manual Signature Name Input -->
                        <div class="col-md-4" id="adminSigNameDiv" style="display:none;">
                            <label class="form-label fw-bold text-secondary">Manual Cursive Name</label>
                            <input type="text" name="manual_signature_name" class="form-control" placeholder="e.g. Dr. Sadhu Gyaneswar" value="{{ session('staff_name') }}">
                        </div>

                        <!-- Markdown Comments -->
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary">Review Comments & Recommendations (Supports Markdown)</label>
                            <textarea name="comments" rows="5" class="form-control" placeholder="Write constructive feedback for the student... You can use standard Markdown tags for lists, headers, or inline code formatting." required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 bg-light p-4">
                    <button type="button" class="btn btn-light px-4 py-2 fw-bold border rounded-pill me-2" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary px-4 py-2 fw-bold rounded-pill shadow-sm"><i class="fas fa-award me-1"></i> Submit Review & Issue Badge</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Toggle manual signature input field visibility based on signature type selection
    document.getElementById('adminSigType')?.addEventListener('change', function() {
        const manualNameDiv = document.getElementById('adminSigNameDiv');
        if (this.value === 'manual') {
            manualNameDiv.style.display = 'block';
            manualNameDiv.querySelector('input').setAttribute('required', 'required');
        } else {
            manualNameDiv.style.display = 'none';
            manualNameDiv.querySelector('input').removeAttribute('required');
        }
    });

    // Populate and open Review Modal
    function openReviewModal(review) {
        const modalElement = document.getElementById('adminReviewModal');
        const modal = new bootstrap.Modal(modalElement);

        document.getElementById('reviewModalTitle').textContent = review.title;
        document.getElementById('reviewModalStudent').textContent = review.user ? review.user.name : 'Student';
        document.getElementById('reviewModalLanguage').textContent = review.language.toUpperCase();
        document.getElementById('reviewModalDescription').textContent = review.description;

        // Set Code Snippet Preview in Modal
        const codeElem = document.getElementById('reviewModalCode');
        codeElem.className = 'language-' + review.language;
        codeElem.textContent = review.code_snippet;
        if (typeof hljs !== 'undefined') {
            hljs.highlightElement(codeElem);
        }

        // Configure Form Route Action
        document.getElementById('adminReviewForm').action = '/admin/synergy-circle/feedback/' + review.id;

        modal.show();
    }
</script>
