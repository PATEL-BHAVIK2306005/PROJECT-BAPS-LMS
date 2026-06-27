@extends('layouts.app')
@section('content')

<div class="container py-4">
    <div class="d-flex align-items-center gap-3 mb-4">
        <div style="width: 50px; height: 50px; background: var(--primary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
            <i class="fas fa-layer-group fa-lg"></i>
        </div>
        <div>
            <h4 class="fw-bold mb-0">My Hub & Requests</h4>
            <p class="text-muted small mb-0">Manage your favorite courses and institutional applications.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success border-0 rounded-3 shadow-sm bg-success text-white">
            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger border-0 rounded-3 bg-danger text-white">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
            </ul>
        </div>
    @endif

    <!-- Bootstrap Nav Tabs -->
    <ul class="nav nav-pills bg-white shadow-sm rounded-pill p-1 mb-4 border" id="hubTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active rounded-pill px-4 fw-bold text-dark" id="favorites-tab" data-bs-toggle="pill" data-bs-target="#favorites" type="button" role="tab"><i class="fas fa-heart text-danger me-2"></i> Favorite Courses</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-bold text-dark" id="gatepass-tab" data-bs-toggle="pill" data-bs-target="#gatepass" type="button" role="tab"><i class="fas fa-ticket-alt text-primary me-2"></i> Gatepass Applications</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-bold text-dark" id="leave-tab" data-bs-toggle="pill" data-bs-target="#leave" type="button" role="tab"><i class="fas fa-calendar-minus text-danger me-2"></i> Official Leave Request</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-bold text-dark" id="fee-tab" data-bs-toggle="pill" data-bs-target="#fee" type="button" role="tab"><i class="fas fa-rupee-sign text-success me-2"></i> LMS & Library Fees</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link rounded-pill px-4 fw-bold text-dark" id="queries-tab" data-bs-toggle="pill" data-bs-target="#queries" type="button" role="tab"><i class="fas fa-question-circle text-info me-2"></i> Student Queries</button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="hubTabsContent">
        
        <!-- Tab 1: Favorites -->
        <div class="tab-pane fade show active" id="favorites" role="tabpanel">
            <div class="glass-card p-4 border-0 shadow-sm">
                <h5 class="fw-bold mb-4">Pinned Courses</h5>
                @if($favorites->count() > 0)
                    <div class="row g-4">
                        @foreach($favorites as $course)
                            <div class="col-md-4">
                                <!-- Example basic favorite tile -->
                                <div class="card h-100 border-0 shadow-sm rounded-4 overflow-hidden position-relative" style="background: linear-gradient(135deg, #f8fafc, #f1f5f9);">
                                    <div class="card-body p-4 d-flex flex-column">
                                        <div class="d-flex justify-content-between align-items-start mb-3">
                                            <div class="badge bg-white text-primary border rounded-pill shadow-sm px-3 py-2 fw-semibold">
                                                <i class="fas fa-users me-1"></i> {{ $course->credits ?? 3 }} Credits
                                            </div>
                                            <button onclick="toggleFavoriteFromHub(this, {{ $course->id }})" class="btn btn-light rounded-circle shadow-sm favorite-btn" style="width: 35px; height: 35px;">
                                                <i class="fas fa-heart text-danger"></i>
                                            </button>
                                        </div>
                                        <h5 class="fw-bold text-dark mb-2">{{ $course->title }}</h5>
                                        <p class="small text-muted mb-4">{{ Str::limit($course->description, 80) }}</p>
                                        <div class="mt-auto">
                                            <a href="/courses/{{ $course->id }}" class="btn btn-primary btn-sm w-100 rounded-pill">View Details</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center opacity-50 py-5">
                        <i class="far fa-heart fa-3x text-muted mb-3"></i>
                        <h6 class="fw-bold text-secondary">No Favorites Yet</h6>
                        <p class="small text-muted">You can pin courses you are interested in here.</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tab 2: Gatepass -->
        <div class="tab-pane fade" id="gatepass" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="glass-card p-4 border-0 shadow-sm h-100">
                        <h5 class="fw-bold mb-4">New Application</h5>
                        <form action="/gatepass" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Destination Address</label>
                                <input type="text" name="destination" class="form-control bg-light border-0" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Reason</label>
                                <textarea name="reason" rows="2" class="form-control bg-light border-0" required></textarea>
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted">Out Time</label>
                                    <input type="datetime-local" name="out_time" class="form-control bg-light border-0" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted">In Time</label>
                                    <input type="datetime-local" name="in_time" class="form-control bg-light border-0" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm py-2">Submit Gatepass</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="glass-card p-4 border-0 shadow-sm h-100 d-flex flex-column">
                        <h5 class="fw-bold mb-4"><i class="fas fa-history text-secondary me-2"></i> Gatepass History</h5>
                        @if($gatepasses->count() > 0)
                            <div class="table-responsive flex-grow-1">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-uppercase small text-muted">
                                        <tr>
                                            <th>Destination</th>
                                            <th>Duration</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($gatepasses as $gp)
                                        <tr>
                                            <td><span class="fw-semibold text-dark">{{ $gp->destination }}</span><br><span class="x-small text-muted">{{ Str::limit($gp->reason, 20) }}</span></td>
                                            <td>
                                                <div class="x-small text-muted"><i class="fas fa-arrow-right text-danger me-1"></i> {{ \Carbon\Carbon::parse($gp->out_time)->format('M d, h:i A') }}</div>
                                                <div class="x-small text-muted"><i class="fas fa-arrow-left text-success me-1"></i> {{ \Carbon\Carbon::parse($gp->in_time)->format('M d, h:i A') }}</div>
                                            </td>
                                            <td>
                                                @if($gp->status === 'approved')
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill">Approved</span>
                                                @elseif($gp->status === 'rejected')
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 rounded-pill">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 rounded-pill">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center opacity-50 py-5">
                                <p class="small text-muted">No gatepasses found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Leave -->
        <div class="tab-pane fade" id="leave" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="glass-card p-4 border-0 shadow-sm h-100">
                        <h5 class="fw-bold mb-4">Leave Request</h5>
                        <form action="/leave" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Category</label>
                                <select name="leave_type" class="form-select bg-light border-0" required>
                                    <option value="Medical Leave">Medical Leave</option>
                                    <option value="Personal Emergency">Personal Emergency</option>
                                </select>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted">Start Date</label>
                                    <input type="date" name="start_date" class="form-control bg-light border-0" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted">End Date</label>
                                    <input type="date" name="end_date" class="form-control bg-light border-0" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Reason</label>
                                <textarea name="reason" rows="3" class="form-control bg-light border-0" required></textarea>
                            </div>
                            <button type="submit" class="btn btn-danger w-100 rounded-pill shadow-sm py-2">Request Leave</button>
                        </form>
                    </div>
                </div>
                <div class="col-md-7">
                    <div class="glass-card p-4 border-0 shadow-sm h-100 d-flex flex-column">
                        <h5 class="fw-bold mb-4"><i class="fas fa-history text-secondary me-2"></i> Leave Authorizations</h5>
                        @if($leaves->count() > 0)
                            <div class="table-responsive flex-grow-1">
                                <table class="table table-hover border-top align-middle">
                                    <thead class="table-light text-uppercase small text-muted">
                                        <tr>
                                            <th>Leave Period</th>
                                            <th>Type</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($leaves as $lv)
                                        <tr>
                                            <td>
                                                <div class="fw-semibold text-primary"><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($lv->start_date)->format('d M') }} TO {{ \Carbon\Carbon::parse($lv->end_date)->format('d M') }}</div>
                                            </td>
                                            <td><span class="fw-bold text-dark">{{ $lv->leave_type }}</span><br><span class="x-small text-muted">{{ Str::limit($lv->reason, 20) }}</span></td>
                                            <td>
                                                @if($lv->status === 'approved')
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill">Approved</span>
                                                @elseif($lv->status === 'rejected')
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 rounded-pill">Rejected</span>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 rounded-pill">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center opacity-50 py-5">
                                <p class="small text-muted">No formal leave records found.</p>
                            </div>
                        @endif
                    </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Tab 4: Fee Gateway -->
        <div class="tab-pane fade" id="fee" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-5">
                    <div class="glass-card p-4 border-0 shadow-sm h-100 bg-white">
                        <h5 class="fw-bold mb-3"><i class="fas fa-wallet text-success me-2"></i> Initiate Fee Payment</h5>
                        <p class="small text-muted mb-4">Generate a 4-digit payment token for institutional LMS and Library access. You must provide this token to your Coordinator, Admin, or authorized Faculty when explicitly processing your physical/UPI payment.</p>
                        
                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <span class="small fw-bold text-muted d-block mb-1">Assessed Fee For Your Program</span>
                            <h4 class="fw-bold text-dark mb-0">
                                @if(str_contains(strtolower($user->program), 'master')) ₹3500 
                                @elseif(str_contains(strtolower($user->program), 'phd')) ₹5000 
                                @else ₹1200 
                                @endif
                                <span class="fs-6 text-muted fw-normal">/ semester</span>
                            </h4>
                        </div>
                        
                        <form action="/hub/fee-token" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Full Name *</label>
                                <input type="text" name="name" class="form-control bg-light border-0" value="{{ $user->name }}" required>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted">Enrollment No *</label>
                                    <input type="text" name="enrollment_no" class="form-control bg-light border-0" value="{{ $user->enrollment_no }}" required>
                                </div>
                                <div class="col-6">
                                    <label class="form-label small fw-bold text-muted">Phone No *</label>
                                    <input type="text" name="phone" class="form-control bg-light border-0" value="{{ $user->phone }}" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Academic Stream *</label>
                                <select name="program" class="form-select bg-light border-0" required>
                                    <option value="Bachelors" {{ str_contains(strtolower($user->program ?? 'bachelors'), 'bach') ? 'selected' : '' }}>Bachelors Degree</option>
                                    <option value="Masters" {{ str_contains(strtolower($user->program ?? ''), 'master') ? 'selected' : '' }}>Masters Degree</option>
                                    <option value="PhD" {{ str_contains(strtolower($user->program ?? ''), 'phd') ? 'selected' : '' }}>PhD / Doctorate</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-success w-100 rounded-pill shadow-sm py-2 fw-bold"><i class="fas fa-qrcode me-2"></i> Generate Payment Token</button>
                        </form>
                    </div>
                </div>
                
                <div class="col-md-7">
                    <div class="glass-card p-4 border-0 shadow-sm h-100 d-flex flex-column bg-white">
                        <h5 class="fw-bold mb-4"><i class="fas fa-history text-secondary me-2"></i> Formal Fee Records</h5>
                        @if($feePayments->count() > 0)
                            <div class="table-responsive flex-grow-1">
                                <table class="table table-hover align-middle border-top">
                                    <thead class="table-light text-uppercase small text-muted">
                                        <tr>
                                            <th>Token No.</th>
                                            <th>Amount</th>
                                            <th>Service Type</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($feePayments as $fee)
                                        <tr>
                                            <td>
                                                <div class="fs-5 fw-bold font-monospace bg-light border d-inline-block px-2 rounded-2 text-dark">{{ $fee->token_number }}</div>
                                            </td>
                                            <td><span class="fw-bold text-success">₹{{ $fee->amount }}</span></td>
                                            <td><span class="text-muted small fw-bold">{{ $fee->fee_type }}</span></td>
                                            <td>
                                                @if($fee->status === 'paid')
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill"><i class="fas fa-check-circle me-1"></i> Paid (Verified)</span>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 rounded-pill"><i class="fas fa-clock me-1"></i> Awaiting Payment</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center opacity-50 py-5">
                                <i class="fas fa-file-invoice-dollar fa-3x text-muted mb-3"></i>
                                <p class="small text-muted fw-bold">No active fee requests found.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 5: Student Queries -->
        <div class="tab-pane fade" id="queries" role="tabpanel">
            <div class="row g-4">
                <!-- File a new query -->
                <div class="col-md-5">
                    <div class="glass-card p-4 border-0 shadow-sm h-100 bg-white">
                        <h5 class="fw-bold mb-3"><i class="fas fa-question-circle text-info me-2"></i> Submit Query Ticket</h5>
                        <p class="small text-muted mb-4">File an official query to resolve institutional issues. Unsolved queries result in penalties (₹10,000 salary cut for Faculty/Dean, ₹100 fine for CR).</p>
                        
                        <form action="/student-queries/store" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Category *</label>
                                <select name="category" class="form-select bg-light border-0" required>
                                    <option value="Schedule">Schedule</option>
                                    <option value="Class Cancel">Class Cancel</option>
                                    <option value="Fees Issue">Fees Issue</option>
                                    <option value="LMS Issue">LMS Issue</option>
                                    <option value="Other/Document Request">Other/Document Request</option>
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Assign To *</label>
                                <select name="assigned_type" id="query_assigned_type" class="form-select bg-light border-0" onchange="toggleAssigneeFields()" required>
                                    <option value="staff">Dean / Faculty Member</option>
                                    <option value="cr">Class Representative (CR)</option>
                                </select>
                            </div>

                            <!-- Staff Assignee Field -->
                            <div class="mb-3" id="staff_assignee_div">
                                <label class="form-label small fw-bold text-muted">Select Faculty / Dean *</label>
                                <select name="assigned_staff_id" id="query_staff_id" class="form-select bg-light border-0" onchange="checkStaffStatus()">
                                    <option value="">-- Select Faculty --</option>
                                    @foreach($faculties as $f)
                                        @php
                                            $fStatus = $f->status ?? 'active';
                                            $fStatusLabel = 'Active';
                                            if($fStatus === 'dnd') $fStatusLabel = 'DND';
                                            elseif($fStatus === 'out_of_station') $fStatusLabel = 'Out of Station';
                                        @endphp
                                        <option value="{{ $f->id }}" data-status="{{ $fStatus }}">
                                            {{ $f->name }} ({{ ucfirst($f->role) }} - {{ $fStatusLabel }})
                                        </option>
                                    @endforeach
                                </select>
                                <div id="staff_status_warning" class="mt-2 p-2.5 rounded-3 border border-warning text-warning bg-warning bg-opacity-10 small fw-bold" style="display:none; font-size: 0.8rem;">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Warning: Selected Faculty is currently <span id="warned_status">DND</span>. Responses may be delayed.
                                </div>
                            </div>

                            <!-- CR Assignee Field -->
                            <div class="mb-3" id="cr_assignee_div" style="display:none;">
                                <label class="form-label small fw-bold text-muted">Select Class Representative *</label>
                                <select name="assigned_cr_id" id="query_cr_id" class="form-select bg-light border-0">
                                    <option value="">-- Select CR --</option>
                                    @foreach($crs as $c)
                                        <option value="{{ $c->id }}">{{ $c->name }} (#{{ $c->enrollment_no }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Query Title *</label>
                                <input type="text" name="title" class="form-control bg-light border-0" placeholder="e.g. Schedule clash in Sem 4 Lecture" required>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold text-muted">Detailed Description *</label>
                                <textarea name="description" rows="3" class="form-control bg-light border-0" placeholder="Please describe your issue in detail..." required></textarea>
                            </div>

                            <button type="submit" class="btn btn-info w-100 rounded-pill text-white fw-bold shadow-sm py-2"><i class="fas fa-paper-plane me-2"></i> Submit Query Ticket</button>
                        </form>
                    </div>
                </div>

                <!-- Query History -->
                <div class="col-md-7">
                    <div class="glass-card p-4 border-0 shadow-sm h-100 d-flex flex-column bg-white">
                        <h5 class="fw-bold mb-4"><i class="fas fa-history text-secondary me-2"></i> Query Ticket History</h5>
                        @if($queries->count() > 0)
                            <div class="table-responsive flex-grow-1" style="max-height: 480px; overflow-y: auto;">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light text-uppercase small text-muted">
                                        <tr>
                                            <th>Ticket Info</th>
                                            <th>Resolver</th>
                                            <th>Status</th>
                                            <th class="text-end">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($queries as $q)
                                        <tr>
                                            <td>
                                                <span class="fw-semibold text-dark">{{ $q->title }}</span>
                                                <div class="small"><span class="badge bg-secondary bg-opacity-10 text-secondary border px-2 py-0.5" style="font-size:0.7rem;">{{ $q->category }}</span></div>
                                                <span class="x-small text-muted"><i class="far fa-clock me-1"></i> {{ $q->created_at->diffForHumans() }}</span>
                                            </td>
                                            <td>
                                                @if($q->assigned_type === 'staff')
                                                    <span class="fw-bold text-dark">{{ $q->assignedStaff->name ?? 'Unassigned' }}</span><br>
                                                    <span class="x-small text-muted">Faculty / Dean</span>
                                                @else
                                                    <span class="fw-bold text-dark">{{ $q->assignedCr->name ?? 'Unassigned' }}</span><br>
                                                    <span class="x-small text-muted">CR</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($q->status === 'solved')
                                                    <span class="badge bg-success bg-opacity-10 text-success border border-success px-2 py-1 rounded-pill">Solved</span>
                                                @elseif($q->status === 'unsolved')
                                                    <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-2 py-1 rounded-pill">Unsolved</span>
                                                @else
                                                    <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-2 py-1 rounded-pill">Pending</span>
                                                @endif
                                            </td>
                                            <td class="text-end">
                                                @if($q->status !== 'pending')
                                                    <button class="btn btn-sm btn-outline-info rounded-pill px-2.5 fw-bold" onclick="showQueryReceipt({{ json_encode($q) }})">
                                                        <i class="fas fa-file-invoice me-1"></i> Receipt
                                                    </button>
                                                @else
                                                    <span class="text-muted small">Awaiting</span>
                                                @endif
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center opacity-50 py-5 my-auto">
                                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                                <p class="small text-muted fw-bold">No active query tickets filed.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Query Resolution Receipt Modal -->
<div class="modal fade" id="queryReceiptModal" tabindex="-1">
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
            
            <div class="modal-body p-4" id="queryReceiptPrintArea">
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
                                <span id="receipt_query_status_badge" class="badge border px-3 py-2 rounded-pill fw-bold text-uppercase">
                                    SOLVED
                                </span>
                                <div class="mt-2 text-muted small fw-semibold" id="receipt_query_ref_num">REF: BAPS-QRY-000</div>
                            </div>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-12 col-md-6 border-end" style="border-right: 1px solid #f1f5f9 !important;">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3 text-info" style="letter-spacing: 0.5px; color: #0891b2;">Student Profile</h6>
                                <table class="table table-borderless table-sm mb-0 align-middle">
                                    <tr>
                                        <td class="text-muted py-1 small" style="width: 40%;">Student Name:</td>
                                        <td class="fw-bold text-dark py-1" id="receipt_student_name">{{ $user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Enrollment No:</td>
                                        <td class="fw-bold text-dark py-1 font-monospace" id="receipt_student_enroll">{{ $user->enrollment_no }}</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Department:</td>
                                        <td class="fw-bold text-dark py-1" id="receipt_student_dept">{{ $user->department->name ?? 'CSE' }}</td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-12 col-md-6 ps-md-4">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3 text-info" style="letter-spacing: 0.5px; color: #0891b2;">Ticket Details</h6>
                                <table class="table table-borderless table-sm mb-0 align-middle">
                                    <tr>
                                        <td class="text-muted py-1 small" style="width: 40%;">Category:</td>
                                        <td class="fw-bold text-dark py-1" id="receipt_query_category">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Date Filed:</td>
                                        <td class="fw-bold text-dark py-1" id="receipt_query_date">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Assignee Type:</td>
                                        <td class="fw-bold text-dark py-1 text-uppercase" id="receipt_query_assignee_type">-</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <div class="p-3 bg-light rounded-3 border mb-4">
                            <span class="small fw-bold text-muted d-block mb-1">Ticket: <strong id="receipt_query_title">Title</strong></span>
                            <p class="small text-secondary mb-0" id="receipt_query_desc">Description...</p>
                        </div>

                        <div class="p-3 bg-light rounded-3 border mb-4 border-info">
                            <span class="small fw-bold text-info d-block mb-1"><i class="fas fa-comment-dots me-1"></i> Resolution Notes</span>
                            <p class="small text-dark mb-0 font-italic" id="receipt_query_notes">Resolution notes here...</p>
                        </div>

                        <!-- Penalty breakdown -->
                        <div class="table-responsive mb-4 border rounded-3 overflow-hidden" id="receipt_penalty_section" style="display:none;">
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
                                            <div class="fw-bold text-dark" id="receipt_penalty_label">Query Unsolved Penalty</div>
                                            <small class="text-muted" id="receipt_penalty_desc">Standard automatic sanction</small>
                                        </td>
                                        <td class="py-2 px-3 text-end fw-semibold text-danger font-monospace" id="receipt_penalty_amount">₹ 0.00</td>
                                    </tr>
                                    <tr class="bg-light table-active" id="receipt_waiver_row" style="display:none;">
                                        <td class="py-2 px-3">
                                            <div class="fw-bold text-success"><i class="fas fa-handshake-angle me-1"></i> Admin / HOD Adjustment</div>
                                            <small class="text-muted">Waived or reduced penalty adjustment</small>
                                        </td>
                                        <td class="py-2 px-3 text-end fw-bold text-success font-monospace" id="receipt_waiver_amount">-₹ 0.00</td>
                                    </tr>
                                    <tr class="bg-light table-active" style="border-top: 2px solid #cbd5e1;">
                                        <td class="py-2 px-3">
                                            <div class="fw-bold text-dark text-uppercase">Net Imposed Penalty</div>
                                        </td>
                                        <td class="py-2 px-3 text-end fw-bold fs-6 text-danger font-monospace" id="receipt_net_penalty">₹ 0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-5">
                            <h6 class="text-muted fw-bold text-uppercase small text-center mb-4" style="letter-spacing: 1px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Resolution Attestation</h6>
                            <div class="text-center">
                                <div class="mb-2" style="height: 50px; display: flex; align-items: center; justify-content: center;">
                                    <div id="receipt_query_signature"></div>
                                </div>
                                <div style="width: 50%; height: 1px; background-color: #cbd5e1; margin-bottom: 6px; margin-left: auto; margin-right: auto;"></div>
                                <div class="fw-bold text-dark small" id="receipt_query_resolved_by_name">-</div>
                                <div class="text-muted" style="font-size: 0.65rem; font-weight: 600;" id="receipt_query_resolved_by_role">-</div>
                            </div>
                        </div>

                        <div class="text-center mt-5 pt-3 border-top text-muted" style="font-size: 0.7rem; border-top: 1px solid #f1f5f9 !important; word-break: break-all;">
                            This is a cryptographically verified and system-attested query resolution slip generated on the BAPS SVM Academic LMS.
                            <br>
                            <span class="fw-bold text-dark">SHA256 Ticket Hash: <span id="receipt_query_hash">-</span></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-info rounded-pill px-4 text-white fw-bold shadow-sm" onclick="printQueryReceipt()">
                    <i class="fas fa-print me-2"></i> Print Slip
                </button>
            </div>
        </div>
    </div>
</div>
    </div>
</div>

<script>
function toggleFavoriteFromHub(btn, courseId) {
    if(!confirm('Remove this course from your Favorites?')) return;

    fetch('/favorites/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ course_id: courseId })
    }).then(res => res.json()).then(data => {
        if(data.status === 'detached') {
            btn.closest('.col-md-4').remove();
            if(document.querySelectorAll('#favorites .col-md-4').length === 0) {
                location.reload();
            }
        }
    });
}

function toggleAssigneeFields() {
    const type = document.getElementById('query_assigned_type').value;
    const staffDiv = document.getElementById('staff_assignee_div');
    const crDiv = document.getElementById('cr_assignee_div');
    const staffSelect = document.getElementById('query_staff_id');
    const crSelect = document.getElementById('query_cr_id');
    
    if (type === 'staff') {
        staffDiv.style.display = 'block';
        crDiv.style.display = 'none';
        staffSelect.required = true;
        crSelect.required = false;
        crSelect.value = '';
    } else {
        staffDiv.style.display = 'none';
        crDiv.style.display = 'block';
        staffSelect.required = false;
        crSelect.required = true;
        staffSelect.value = '';
        document.getElementById('staff_status_warning').style.display = 'none';
    }
}

function checkStaffStatus() {
    const select = document.getElementById('query_staff_id');
    const selectedOption = select.options[select.selectedIndex];
    const warningDiv = document.getElementById('staff_status_warning');
    const warnedStatus = document.getElementById('warned_status');
    
    if (selectedOption && selectedOption.value !== '') {
        const status = selectedOption.getAttribute('data-status');
        if (status === 'dnd' || status === 'out_of_station') {
            const statusLabel = status === 'dnd' ? 'DND (Do Not Disturb)' : 'Out of Station (On Leave)';
            warnedStatus.textContent = statusLabel;
            warningDiv.style.display = 'block';
        } else {
            warningDiv.style.display = 'none';
        }
    } else {
        warningDiv.style.display = 'none';
    }
}

function showQueryReceipt(query) {
    // Fill basic details
    document.getElementById('receipt_query_ref_num').textContent = 'REF: BAPS-QRY-' + String(query.id).padStart(3, '0');
    
    // Status Badge Styling
    const statusBadge = document.getElementById('receipt_query_status_badge');
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
    document.getElementById('receipt_student_name').textContent = query.student ? query.student.name : '{{ $user->name }}';
    document.getElementById('receipt_student_enroll').textContent = query.student ? query.student.enrollment_no : '{{ $user->enrollment_no }}';
    document.getElementById('receipt_student_dept').textContent = (query.student && query.student.department) ? query.student.department.name : 'Computer Science & Engineering';
    
    // Query Details
    document.getElementById('receipt_query_category').textContent = query.category;
    document.getElementById('receipt_query_date').textContent = new Date(query.created_at).toLocaleDateString('en-IN', {
        day: '2-digit',
        month: 'short',
        year: 'numeric'
    });
    document.getElementById('receipt_query_assignee_type').textContent = query.assigned_type === 'staff' ? 'Faculty / Dean' : 'Class Representative';
    
    document.getElementById('receipt_query_title').textContent = query.title;
    document.getElementById('receipt_query_desc').textContent = query.description;
    document.getElementById('receipt_query_notes').textContent = query.resolution_notes || 'No resolution notes provided.';
    
    // Penalty Section
    const penaltySec = document.getElementById('receipt_penalty_section');
    if (query.status === 'unsolved') {
        penaltySec.style.display = 'block';
        
        const isStaff = query.assigned_type === 'staff';
        const origPenalty = isStaff ? 10000 : 100;
        const currentPenalty = Number(isStaff ? query.salary_cut_amount : query.fine_amount);
        
        document.getElementById('receipt_penalty_label').textContent = isStaff ? 'Faculty/Dean Salary Cut' : 'CR Query Fine';
        document.getElementById('receipt_penalty_desc').textContent = isStaff 
            ? 'Standard automatic penalty of ₹10,000 for unsolved query' 
            : 'Standard automatic fine of ₹100 for unsolved query';
        document.getElementById('receipt_penalty_amount').textContent = '₹ ' + origPenalty.toLocaleString('en-IN', {minimumFractionDigits: 2});
        
        // Waiver Check
        const waiverRow = document.getElementById('receipt_waiver_row');
        if (query.is_waived || currentPenalty < origPenalty) {
            waiverRow.style.display = 'table-row';
            const diff = origPenalty - currentPenalty;
            document.getElementById('receipt_waiver_amount').textContent = '-₹ ' + diff.toLocaleString('en-IN', {minimumFractionDigits: 2});
        } else {
            waiverRow.style.display = 'none';
        }
        
        document.getElementById('receipt_net_penalty').textContent = '₹ ' + currentPenalty.toLocaleString('en-IN', {minimumFractionDigits: 2});
    } else {
        penaltySec.style.display = 'none';
    }
    
    // Signatures and Attestation
    document.getElementById('receipt_query_signature').innerHTML = query.resolved_by_signature || '<span class="text-muted font-italic small">Digital Signature Not Found</span>';
    document.getElementById('receipt_query_resolved_by_name').textContent = query.resolved_by_name || '-';
    document.getElementById('receipt_query_resolved_by_role').textContent = query.resolved_by_role || '-';
    
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
    document.getElementById('receipt_query_hash').textContent = hashStr;

    // Show modal
    var modal = new bootstrap.Modal(document.getElementById('queryReceiptModal'));
    modal.show();
}

function printQueryReceipt() {
    var printContents = document.getElementById('queryReceiptPrintArea').innerHTML;
    var style = document.createElement('style');
    style.innerHTML = `
        @media print {
            body * {
                visibility: hidden;
            }
            #queryReceiptPrintArea, #queryReceiptPrintArea * {
                visibility: visible;
            }
            #queryReceiptPrintArea {
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
@endsection
