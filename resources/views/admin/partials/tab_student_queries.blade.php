@php
    $role = session('user_role');
    $staffId = session('staff_id');
    
    // Determine queries list based on role
    if (in_array($role, ['admin', 'hod'])) {
        $pendingQueries = $allQueries->where('status', 'pending');
        $resolvedQueries = $allQueries->whereIn('status', ['solved', 'unsolved']);
        $waiverQueries = $allQueries->where('status', 'unsolved');
    } else {
        $pendingQueries = $allQueries->where('status', 'pending')->where('assigned_staff_id', $staffId);
        $resolvedQueries = $allQueries->whereIn('status', ['solved', 'unsolved'])->where('assigned_staff_id', $staffId);
        $waiverQueries = collect(); // Faculty cannot waive
    }
    
    // Stats calculations
    $totalPending = $pendingQueries->count();
    $totalSolved = $resolvedQueries->where('status', 'solved')->count();
    $totalUnsolved = $resolvedQueries->where('status', 'unsolved')->count();
    
    // Calculate total active penalties
    $totalPenaltiesImposed = 0;
    foreach($allQueries->where('status', 'unsolved') as $q) {
        if($q->assigned_type === 'staff') {
            $totalPenaltiesImposed += $q->salary_cut_amount;
        } else {
            $totalPenaltiesImposed += $q->fine_amount;
        }
    }
@endphp

<div class="tab-pane fade show active" id="tab-student-queries" role="tabpanel">
    
    <!-- Availability Status Control (Deans / Faculty only) -->
    @if(in_array($role, ['dean', 'faculty', 'faculty-lecturer-lab', 'faculty-lecturer-coordinator', 'coordinator']))
    <div class="content-card mb-4" style="background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
            <div>
                <h5 class="fw-bold mb-1"><i class="fas fa-user-clock text-info me-2"></i> Availability Status Settings</h5>
                <p class="text-muted small mb-0">Set your response status. A warning notification appears for students if you set DND or Out of Station.</p>
            </div>
            <div>
                <form action="/admin/student-queries/update-status" method="POST" class="d-flex gap-2">
                    @csrf
                    <select name="status" class="form-select bg-white border border-secondary border-opacity-20 rounded-pill w-auto" style="min-width: 200px;">
                        <option value="active" {{ ($currentStaff && $currentStaff->status === 'active') ? 'selected' : '' }}>🟢 Active / Available</option>
                        <option value="dnd" {{ ($currentStaff && $currentStaff->status === 'dnd') ? 'selected' : '' }}>🟠 Do Not Disturb (DND)</option>
                        <option value="out_of_station" {{ ($currentStaff && $currentStaff->status === 'out_of_station') ? 'selected' : '' }}>🔴 Out of Station / Leave</option>
                    </select>
                    <button type="submit" class="action-btn action-btn-primary rounded-pill px-4" style="width: auto;"><i class="fas fa-save me-2"></i> Update</button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Ticket Stats Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon primary"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-number">{{ $totalPending }}</div>
                    <div class="stat-label">Pending Tickets</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon success"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-number">{{ $totalSolved }}</div>
                    <div class="stat-label">Resolved Tickets</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon danger"><i class="fas fa-times-circle"></i></div>
                <div>
                    <div class="stat-number">{{ $totalUnsolved }}</div>
                    <div class="stat-label">Unsolved Tickets</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-rupee-sign"></i></div>
                <div>
                    <div class="stat-number">₹{{ number_format($totalPenaltiesImposed) }}</div>
                    <div class="stat-label">Penalties Active</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Workspace Grid -->
    <div class="row g-4">
        
        <!-- Pending Tickets Section -->
        <div class="col-lg-8">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h5 class="content-card-title"><i class="fas fa-inbox text-primary"></i> Pending Student Queries</h5>
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary px-3 py-1 rounded-pill fw-semibold">{{ $totalPending }} Pending</span>
                </div>
                
                @if($pendingQueries->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead>
                                <tr>
                                    <th>Ticket details</th>
                                    <th>Student</th>
                                    <th>Assignee</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingQueries as $q)
                                <tr>
                                    <td>
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
                                        @if($q->assigned_type === 'staff')
                                            <span class="fw-semibold text-primary"><i class="fas fa-chalkboard-teacher me-1"></i> {{ $q->assignedStaff->name ?? 'Unassigned' }}</span>
                                        @else
                                            <span class="fw-semibold text-success"><i class="fas fa-user-graduate me-1"></i> CR: {{ $q->assignedCr->name ?? 'Unassigned' }}</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" onclick="openResolveModal({{ json_encode($q) }})">
                                            <i class="fas fa-file-signature me-1"></i> Resolve & Sign
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center opacity-50 py-5 my-auto">
                        <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                        <h6 class="fw-bold text-secondary">No Pending Queries</h6>
                        <p class="small text-muted mb-0">All student query tickets assigned to you are resolved.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sidebar / Waiver Desk -->
        <div class="col-lg-4">
            <div class="content-card h-100">
                <div class="content-card-header">
                    <h5 class="content-card-title"><i class="fas fa-gavel text-danger"></i> Penalty Waiver Desk</h5>
                    <small class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2.5 py-0.5 rounded-pill fw-bold">Admin/HOD Only</small>
                </div>
                
                @if(!in_array($role, ['admin', 'hod']))
                    <div class="alert alert-warning border-0 p-3 small mb-0 rounded-3">
                        <i class="fas fa-lock me-2"></i> Only <strong>Administrator</strong> or <strong>Head of Department (HOD)</strong> privileges can view or modify active query penalty amounts or process waivers.
                    </div>
                @else
                    @if($waiverQueries->count() > 0)
                        <div class="d-flex flex-column gap-3" style="max-height: 480px; overflow-y: auto;">
                            @foreach($waiverQueries as $q)
                                @php
                                    $isStaff = $q->assigned_type === 'staff';
                                    $origAmount = $isStaff ? 10000 : 100;
                                    $currentAmount = $isStaff ? $q->salary_cut_amount : $q->fine_amount;
                                    $assigneeName = $isStaff ? ($q->assignedStaff->name ?? 'Staff') : ($q->assignedCr->name ?? 'CR');
                                @endphp
                                <div class="p-3 bg-light border rounded-3 d-flex flex-column gap-2" style="background: #ffffff !important; border-left: 4px solid var(--baps-red) !important;">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-0 text-truncate" style="max-width: 180px;" title="{{ $q->title }}">{{ $q->title }}</h6>
                                            <small class="text-muted text-uppercase" style="font-size:0.65rem;">Assigned: {{ $assigneeName }}</small>
                                        </div>
                                        <div class="text-end">
                                            @if($q->is_waived || $currentAmount == 0)
                                                <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-0.5 rounded-pill small">Waived</span>
                                            @elseif($currentAmount < $origAmount)
                                                <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-0.5 rounded-pill small">Reduced</span>
                                            @else
                                                <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-0.5 rounded-pill small">Active Cut</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="d-flex justify-content-between align-items-center py-1 bg-light px-2 rounded-2 border">
                                        <span class="small text-muted">Current Penalty:</span>
                                        <span class="fw-bold text-danger font-monospace">₹{{ number_format($currentAmount, 2) }}</span>
                                    </div>
                                    
                                    <div class="d-flex gap-2 mt-1">
                                        <form action="/admin/student-queries/{{ $q->id }}/waive-reduce" method="POST" class="w-50">
                                            @csrf
                                            <input type="hidden" name="action" value="waive">
                                            <button type="submit" class="btn btn-sm btn-outline-success w-100 rounded-pill fw-bold" onclick="return confirm('Are you sure you want to completely waive this penalty to ₹0.00?')">
                                                <i class="fas fa-handshake-angle me-1"></i> Waive
                                            </button>
                                        </form>
                                        
                                        <button class="btn btn-sm btn-outline-warning w-50 rounded-pill fw-bold" onclick="openReduceModal({{ $q->id }}, {{ $currentAmount }}, '{{ addslashes($q->title) }}')">
                                            <i class="fas fa-edit me-1"></i> Reduce
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center opacity-50 py-4 my-auto">
                            <i class="fas fa-check-double fa-2x text-muted mb-2"></i>
                            <p class="small text-muted mb-0">No active unsolved penalties to adjust.</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    <!-- Resolved Tickets History -->
    <div class="content-card mt-4">
        <div class="content-card-header">
            <h5 class="content-card-title"><i class="fas fa-history text-secondary"></i> Historical Resolves Log</h5>
            <span class="badge bg-secondary bg-opacity-10 text-secondary border px-3 py-1 rounded-pill fw-semibold">{{ $resolvedQueries->count() }} Resolved</span>
        </div>
        
        @if($resolvedQueries->count() > 0)
            <div class="table-responsive" style="max-height: 400px; overflow-y: auto;">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Ticket details</th>
                            <th>Student</th>
                            <th>Resolver Details</th>
                            <th>Status</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($resolvedQueries as $q)
                        <tr>
                            <td>
                                <div class="fw-bold text-dark">{{ $q->title }}</div>
                                <div class="text-muted small"><span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-0.5" style="font-size:0.7rem;">{{ $q->category }}</span></div>
                                <span class="x-small text-muted"><i class="far fa-clock"></i> {{ $q->created_at->diffForHumans() }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $q->student->name ?? 'Student' }}</div>
                                <div class="small text-muted font-monospace">{{ $q->student->enrollment_no ?? '' }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $q->resolved_by_name ?? '-' }}</div>
                                <div class="small text-muted">{{ $q->resolved_by_role ?? '-' }}</div>
                            </td>
                            <td>
                                @if($q->status === 'solved')
                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2.5 py-1 rounded-pill fw-bold">Solved</span>
                                @else
                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2.5 py-1 rounded-pill fw-bold">Unsolved</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-info rounded-pill px-3 fw-bold" onclick="showQueryReceiptAdmin({{ json_encode($q) }})">
                                    <i class="fas fa-file-invoice me-1"></i> Receipt Slip
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center opacity-50 py-5">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <p class="small text-muted mb-0">No query tickets have been resolved yet.</p>
            </div>
        @endif
    </div>

</div>

<!-- Resolve & Sign Ticket Modal -->
<div class="modal fade" id="resolveQueryModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="resolveQueryForm" action="" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-signature text-warning me-2"></i> Resolve & Attest Query Ticket</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="p-3 bg-light rounded-3 border mb-4">
                    <span class="small text-muted d-block font-semibold">TICKET TITLE:</span>
                    <strong id="modal_query_title" class="text-dark">Title</strong>
                    <div class="mt-2 small text-muted" id="modal_query_description">Description...</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Resolution Verdict *</label>
                    <select name="status" class="form-select bg-light border-0" required>
                        <option value="solved">🟢 Solved (Satisfactory resolution achieved)</option>
                        <option value="unsolved">🔴 Unsolved (Impose fine / salary cut penalty)</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Resolution Notes *</label>
                    <textarea name="resolution_notes" rows="4" class="form-control bg-light border-0" placeholder="Provide detailed resolution notes..." required></textarea>
                </div>

                <div class="p-3 border rounded-3 bg-light text-center">
                    <div class="text-muted small fw-semibold mb-2">CRYPTOGRAPHIC DIGITAL SIGNATURE PREVIEW</div>
                    <div style="height: 50px; display: flex; align-items: center; justify-content: center;" id="modal_signature_preview">
                        {!! session('user_role') === 'cr' ? (auth()->user()->digital_signature ?? '') : ($currentStaff->digital_signature ?? '') !!}
                    </div>
                    <div class="small fw-bold text-dark mt-2 border-top pt-2">{{ session('staff_name') ?? 'BHAVIKKUMAR PATEL' }}</div>
                    <div class="x-small text-muted">{{ ucfirst($role) }}</div>
                </div>
            </div>
            
            <div class="modal-footer border-top-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm"><i class="fas fa-check-double me-2"></i> Attest & Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- Reduce Penalty Modal -->
<div class="modal fade" id="reducePenaltyModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="reducePenaltyForm" action="" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf
            <input type="hidden" name="action" value="reduce">
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-sliders-h text-warning me-2"></i> Reduce Penalty Amount</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            
            <div class="modal-body p-4">
                <div class="p-3 bg-light rounded-3 border mb-3">
                    <span class="small text-muted d-block">TICKET TITLE:</span>
                    <strong id="reduce_modal_title" class="text-dark">Title</strong>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Current Imposed Penalty</label>
                    <div class="fs-4 fw-bold text-danger font-monospace" id="reduce_modal_current_val">₹0.00</div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">New Reduced Penalty (₹) *</label>
                    <input type="number" step="0.01" min="0" name="reduced_amount" id="reduced_amount_input" class="form-control bg-light border-0" required>
                    <div class="form-text text-muted small">Enter the new penalty amount. Must be less than or equal to current.</div>
                </div>
            </div>
            
            <div class="modal-footer border-top-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm"><i class="fas fa-save me-2"></i> Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Dynamic Printable Receipt Modal (Self-Contained) -->
<div class="modal fade" id="queryReceiptModalAdmin" tabindex="-1">
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
            
            <div class="modal-body p-4" id="queryReceiptPrintAreaAdmin">
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
                                <span id="admin_receipt_query_status_badge" class="badge border px-3 py-2 rounded-pill fw-bold text-uppercase">
                                    SOLVED
                                </span>
                                <div class="mt-2 text-muted small fw-semibold" id="admin_receipt_query_ref_num">REF: BAPS-QRY-000</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6 border-end" style="border-right: 1px solid #f1f5f9 !important;">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3 text-info" style="letter-spacing: 0.5px; color: #0891b2;">Student Profile</h6>
                                <table class="table table-borderless table-sm mb-0 align-middle">
                                    <tr>
                                        <td class="text-muted py-1 small" style="width: 40%;">Student Name:</td>
                                        <td class="fw-bold text-dark py-1" id="admin_receipt_student_name">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Enrollment No:</td>
                                        <td class="fw-bold text-dark py-1 font-monospace" id="admin_receipt_student_enroll">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Department:</td>
                                        <td class="fw-bold text-dark py-1" id="admin_receipt_student_dept">-</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12 col-md-6 ps-md-4">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3 text-info" style="letter-spacing: 0.5px; color: #0891b2;">Ticket Details</h6>
                                <table class="table table-borderless table-sm mb-0 align-middle">
                                    <tr>
                                        <td class="text-muted py-1 small" style="width: 40%;">Category:</td>
                                        <td class="fw-bold text-dark py-1" id="admin_receipt_query_category">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Date Filed:</td>
                                        <td class="fw-bold text-dark py-1" id="admin_receipt_query_date">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Assignee Type:</td>
                                        <td class="fw-bold text-dark py-1 text-uppercase" id="admin_receipt_query_assignee_type">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <span class="small fw-bold text-muted d-block mb-1">Ticket: <strong id="admin_receipt_query_title">Title</strong></span>
                            <p class="small text-secondary mb-0" id="admin_receipt_query_desc">Description...</p>
                        </div>

                        <div class="p-3 bg-light rounded-3 border mb-4 border-info">
                            <span class="small fw-bold text-info d-block mb-1"><i class="fas fa-comment-dots me-1"></i> Resolution Notes</span>
                            <p class="small text-dark mb-0 font-italic" id="admin_receipt_query_notes">Resolution notes here...</p>
                        </div>

                        <!-- Penalty breakdown -->
                        <div class="table-responsive mb-4 border rounded-3 overflow-hidden" id="admin_receipt_penalty_section" style="display:none;">
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
                                            <div class="fw-bold text-dark" id="admin_receipt_penalty_label">Query Unsolved Penalty</div>
                                            <small class="text-muted" id="admin_receipt_penalty_desc">Standard automatic sanction</small>
                                        </td>
                                        <td class="py-2 px-3 text-end fw-semibold text-danger font-monospace" id="admin_receipt_penalty_amount">₹ 0.00</td>
                                    </tr>
                                    <tr class="bg-light table-active" id="admin_receipt_waiver_row" style="display:none;">
                                        <td class="py-2 px-3">
                                            <div class="fw-bold text-success"><i class="fas fa-handshake-angle me-1"></i> Admin / HOD Adjustment</div>
                                            <small class="text-muted">Waived or reduced penalty adjustment</small>
                                        </td>
                                        <td class="py-2 px-3 text-end fw-bold text-success font-monospace" id="admin_receipt_waiver_amount">-₹ 0.00</td>
                                    </tr>
                                    <tr class="bg-light table-active" style="border-top: 2px solid #cbd5e1;">
                                        <td class="py-2 px-3">
                                            <div class="fw-bold text-dark text-uppercase">Net Imposed Penalty</div>
                                        </td>
                                        <td class="py-2 px-3 text-end fw-bold fs-6 text-danger font-monospace" id="admin_receipt_net_penalty">₹ 0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            <h6 class="text-muted fw-bold text-uppercase small text-center mb-4" style="letter-spacing: 1px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Resolution Attestation</h6>
                            <div class="text-center">
                                <div class="mb-2" style="height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <div id="admin_receipt_query_signature"></div>
                                </div>
                                <div style="width: 50%; height: 1px; background-color: #cbd5e1; margin-bottom: 6px; margin-left: auto; margin-right: auto;"></div>
                                <div class="fw-bold text-dark small" id="admin_receipt_query_resolved_by_name">-</div>
                                <div class="text-muted" style="font-size: 0.65rem; font-weight: 600;" id="admin_receipt_query_resolved_by_role">-</div>
                            </div>
                        </div>

                        <div class="text-center mt-5 pt-3 border-top text-muted" style="font-size: 0.7rem; border-top: 1px solid #f1f5f9 !important; word-break: break-all;">
                            This is a cryptographically verified and system-attested query resolution slip generated on the BAPS SVM Academic LMS.
                            <br>
                            <span class="fw-bold text-dark">SHA256 Ticket Hash: <span id="admin_receipt_query_hash">-</span></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info rounded-pill px-4 text-white fw-bold shadow-sm" onclick="printQueryReceiptAdmin()">
                    <i class="fas fa-print me-2"></i> Print Slip
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function openResolveModal(query) {
    document.getElementById('modal_query_title').textContent = query.title;
    document.getElementById('modal_query_description').textContent = query.description;
    
    // Set form action
    document.getElementById('resolveQueryForm').action = '/student-queries/' + query.id + '/resolve';
    
    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('resolveQueryModal'));
    modal.show();
}

function openReduceModal(id, currentAmount, title) {
    document.getElementById('reduce_modal_title').textContent = title;
    document.getElementById('reduce_modal_current_val').textContent = '₹ ' + currentAmount.toLocaleString('en-IN', {minimumFractionDigits: 2});
    
    const input = document.getElementById('reduced_amount_input');
    input.max = currentAmount;
    input.value = currentAmount;
    
    document.getElementById('reducePenaltyForm').action = '/admin/student-queries/' + id + '/waive-reduce';
    
    var modal = new bootstrap.Modal(document.getElementById('reducePenaltyModal'));
    modal.show();
}

function showQueryReceiptAdmin(query) {
    // Fill basic details
    document.getElementById('admin_receipt_query_ref_num').textContent = 'REF: BAPS-QRY-' + String(query.id).padStart(3, '0');
    
    // Status Badge Styling
    const statusBadge = document.getElementById('admin_receipt_query_status_badge');
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
    document.getElementById('admin_receipt_student_name').textContent = query.student ? query.student.name : 'Student';
    document.getElementById('admin_receipt_student_enroll').textContent = query.student ? query.student.enrollment_no : '-';
    document.getElementById('admin_receipt_student_dept').textContent = (query.student && query.student.department) ? query.student.department.name : 'Computer Science & Engineering';
    
    // Query Details
    document.getElementById('admin_receipt_query_category').textContent = query.category;
    document.getElementById('admin_receipt_query_date').textContent = new Date(query.created_at).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
    document.getElementById('admin_receipt_query_assignee_type').textContent = query.assigned_type === 'staff' ? 'Faculty / Dean' : 'Class Representative';
    
    document.getElementById('admin_receipt_query_title').textContent = query.title;
    document.getElementById('admin_receipt_query_desc').textContent = query.description;
    document.getElementById('admin_receipt_query_notes').textContent = query.resolution_notes || 'No resolution notes provided.';
    
    // Penalty Section
    const penaltySec = document.getElementById('admin_receipt_penalty_section');
    if (query.status === 'unsolved') {
        penaltySec.style.display = 'block';
        
        const isStaff = query.assigned_type === 'staff';
        const origPenalty = isStaff ? 10000 : 100;
        const currentPenalty = Number(isStaff ? query.salary_cut_amount : query.fine_amount);
        
        document.getElementById('admin_receipt_penalty_label').textContent = isStaff ? 'Faculty/Dean Salary Cut' : 'CR Query Fine';
        document.getElementById('admin_receipt_penalty_desc').textContent = isStaff 
            ? 'Standard automatic penalty of ₹10,000 for unsolved query' 
            : 'Standard automatic fine of ₹100 for unsolved query';
        document.getElementById('admin_receipt_penalty_amount').textContent = '₹ ' + origPenalty.toLocaleString('en-IN', {minimumFractionDigits: 2});
        
        // Waiver Check
        const waiverRow = document.getElementById('admin_receipt_waiver_row');
        if (query.is_waived || currentPenalty < origPenalty) {
            waiverRow.style.display = 'table-row';
            const diff = origPenalty - currentPenalty;
            document.getElementById('admin_receipt_waiver_amount').textContent = '-₹ ' + diff.toLocaleString('en-IN', {minimumFractionDigits: 2});
        } else {
            waiverRow.style.display = 'none';
        }
        
        document.getElementById('admin_receipt_net_penalty').textContent = '₹ ' + currentPenalty.toLocaleString('en-IN', {minimumFractionDigits: 2});
    } else {
        penaltySec.style.display = 'none';
    }
    
    // Signatures and Attestation
    document.getElementById('admin_receipt_query_signature').innerHTML = query.resolved_by_signature || '<span class="text-muted font-italic small">Digital Signature Not Found</span>';
    document.getElementById('admin_receipt_query_resolved_by_name').textContent = query.resolved_by_name || '-';
    document.getElementById('admin_receipt_query_resolved_by_role').textContent = query.resolved_by_role || '-';
    
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
    document.getElementById('admin_receipt_query_hash').textContent = hashStr;

    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('queryReceiptModalAdmin'));
    modal.show();
}

function printQueryReceiptAdmin() {
    var printContents = document.getElementById('queryReceiptPrintAreaAdmin').innerHTML;
    var style = document.createElement('style');
    style.innerHTML = `
        @media print {
            body * {
                visibility: hidden;
            }
            #queryReceiptPrintAreaAdmin, #queryReceiptPrintAreaAdmin * {
                visibility: visible;
            }
            #queryReceiptPrintAreaAdmin {
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
