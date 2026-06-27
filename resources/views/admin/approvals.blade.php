@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="fas fa-clipboard-check text-primary me-2"></i> Master Approvals Center</h3>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

@if(session('error'))
    <div class="alert alert-danger py-3 fw-bold shadow-sm rounded-4"><i class="fas fa-exclamation-triangle me-2"></i> {{ session('error') }}</div>
@endif
@if(session('success'))
    <div class="alert alert-success mt-3 p-3 text-center fw-bold shadow-sm rounded-4 border-0" style="background: linear-gradient(135deg, #10b981, #059669); color: white;">
        <i class="fas fa-check-circle me-1 d-inline-block"></i> {{ session('success') }}
    </div>
@endif

<!-- Master Tabs -->
<ul class="nav nav-tabs border-0 mb-4" id="approvalsTab" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active fw-bold px-4 py-3 rounded-top-4" data-bs-toggle="tab" data-bs-target="#registrations" type="button" role="tab" style="color:#1e293b;">
            <i class="fas fa-user-plus me-2 text-primary"></i> <span class="d-none d-md-inline" >New Registrations</span>
            <span class="badge bg-primary rounded-pill ms-2">{{ $pendingStudents->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-4 py-3 rounded-top-4" data-bs-toggle="tab" data-bs-target="#enrollments" type="button" role="tab" style="color:#1e293b;">
            <i class="fas fa-book-reader me-2 text-success"></i> <span class="d-none d-md-inline">Course Access</span>
            <span class="badge bg-success rounded-pill ms-2">{{ $pendingEnrollments->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-4 py-3 rounded-top-4" data-bs-toggle="tab" data-bs-target="#gatepasses" type="button" role="tab" style="color:#1e293b;">
            <i class="fas fa-ticket-alt me-2 text-warning"></i> <span class="d-none d-md-inline">Gatepass Requests</span>
            <span class="badge bg-warning text-dark rounded-pill ms-2">{{ $pendingGatepasses->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-4 py-3 rounded-top-4" data-bs-toggle="tab" data-bs-target="#leaves" type="button" role="tab" style="color:#1e293b;">
            <i class="fas fa-calendar-minus me-2 text-danger"></i> <span class="d-none d-md-inline">Leave Applications</span>
            <span class="badge bg-danger rounded-pill ms-2">{{ $pendingLeaves->count() }}</span>
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-4 py-3 rounded-top-4" data-bs-toggle="tab" data-bs-target="#passwords" type="button" role="tab" style="color:#1e293b;">
            <i class="fas fa-key me-2 text-secondary"></i> <span class="d-none d-md-inline">Password Resets</span>
    <li class="nav-item" role="presentation">
        <button class="nav-link fw-bold px-4 py-3 rounded-top-4" data-bs-toggle="tab" data-bs-target="#passwords" type="button" role="tab" style="color:#1e293b;">
            <i class="fas fa-key me-2 text-secondary"></i> <span class="d-none d-md-inline">Password Resets</span>
            <span class="badge bg-secondary rounded-pill ms-2">{{ $pendingPasswords->count() }}</span>
        </button>
    </li>
</ul>

<div class="tab-content" id="approvalsTabContent">
    <!-- REGISTRATIONS TAB -->
    <div class="tab-pane fade show active" id="registrations" role="tabpanel">
        <div class="glass-card p-0 border-0 shadow-sm overflow-hidden text-nowrap rounded-bottom-4 border">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase fw-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3">Applicant Identity</th>
                            <th class="py-3">Enrollment / Origin</th>
                            <th class="py-3">Contact</th>
                            <th class="text-end pe-4 py-3">Authorization</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingStudents as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" style="width: 40px; height: 40px;">{{ substr($user->name, 0, 1) }}</div>
                                    <div>
                                        <div class="fw-bold text-dark fs-6">{{ $user->name }}</div>
                                        <div class="small text-muted"><i class="far fa-envelope me-1"></i> {{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="mb-1"><span class="badge bg-dark bg-opacity-10 text-dark border px-2 py-1 shadow-sm font-monospace">{{ $user->enrollment_no }}</span></div>
                                <div class="small text-muted">Aadhar: {{ $user->aadhar_no }}</div>
                                @if($user->tracking_id)
                                <div class="small mt-1"><span class="badge bg-info text-white shadow-sm" style="font-size: 0.7rem;"><i class="fas fa-barcode"></i> {{ $user->tracking_id }}</span></div>
                                @endif
                            </td>
                            <td>{{ $user->phone }}</td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="/admin/approvals/user/{{ $user->id }}/process" method="POST">
                                        @csrf <input type="hidden" name="action" value="reject">
                                        <button class="btn btn-outline-danger btn-sm rounded-pill shadow-sm fw-bold" title="Reject"><i class="fas fa-times"></i></button>
                                    </form>
                                    @if($user->application_stage < 3)
                                        <form action="/admin/approvals/user/{{ $user->id }}/process" method="POST">
                                            @csrf <input type="hidden" name="action" value="approve_for_tc">
                                            <button class="btn btn-primary btn-sm rounded-pill shadow-sm fw-bold"><i class="fas fa-file-signature"></i> Approve for T&C</button>
                                        </form>
                                    @elseif($user->application_stage == 3)
                                        <button class="btn btn-secondary btn-sm rounded-pill shadow-sm fw-bold" disabled><i class="fas fa-clock"></i> Pending User T&C</button>
                                    @elseif($user->application_stage == 4)
                                        <button type="button" class="btn btn-info btn-sm rounded-pill shadow-sm fw-bold text-white" onclick="viewSignature('{{ $user->digital_signature }}')"><i class="fas fa-eye"></i> View Signature</button>
                                        <form action="/admin/approvals/user/{{ $user->id }}/process" method="POST">
                                            @csrf <input type="hidden" name="action" value="unlock_profile">
                                            <button class="btn btn-success btn-sm rounded-pill shadow-sm fw-bold"><i class="fas fa-unlock-alt"></i> Verify & Unlock</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted"><i class="fas fa-check-double fs-1 mb-3 text-success opacity-50"></i><h5 class="fw-bold text-dark">Caught Up!</h5></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- ENROLLMENTS TAB -->
    <div class="tab-pane fade" id="enrollments" role="tabpanel">
        <div class="glass-card p-0 border-0 shadow-sm overflow-hidden text-nowrap rounded-bottom-4 border">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase fw-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3">Applicant Details</th>
                            <th class="py-3">Course & Academic Data</th>
                            <th class="text-end pe-4 py-3">Authorization</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingEnrollments as $enr)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="fw-bold text-dark mb-1">{{ $enr->name ?? $enr->user->name ?? 'Unknown Student' }}</div>
                                <div class="small text-muted"><i class="fas fa-envelope me-1"></i> {{ $enr->email ?? $enr->user->email ?? 'N/A' }}</div>
                                <div class="small text-muted mb-2"><i class="fas fa-phone-alt me-1"></i> {{ $enr->phone ?? $enr->user->phone ?? 'N/A' }}</div>
                                <div><span class="badge bg-dark bg-opacity-10 text-dark border px-2 py-1 shadow-sm font-monospace" title="Enrollment Number">ID: {{ $enr->roll_no ?? $enr->user->enrollment_no ?? 'N/A' }}</span></div>
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-primary mb-2"><i class="fas fa-book me-1"></i> {{ $enr->course->title ?? 'Unknown Course' }}</div>
                                <div class="small text-muted mb-1"><span class="fw-bold text-dark">Department:</span> {{ $enr->department ?? 'N/A' }}</div>
                                <div class="small text-muted"><span class="fw-bold text-dark">Program & Semester:</span> {{ $enr->program ?? 'Core' }} — Sem {{ $enr->semester ?? 'N/A' }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="/admin/approvals/enrollment/{{ $enr->id }}/process" method="POST">
                                        @csrf <input type="hidden" name="action" value="reject">
                                        <button class="btn btn-outline-danger btn-sm rounded-pill shadow-sm fw-bold"><i class="fas fa-times"></i></button>
                                    </form>
                                    <form action="/admin/approvals/enrollment/{{ $enr->id }}/process" method="POST">
                                        @csrf <input type="hidden" name="action" value="approve">
                                        <button class="btn btn-success btn-sm rounded-pill shadow-sm fw-bold"><i class="fas fa-check"></i> Grant Access</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-5 text-muted"><i class="fas fa-book-reader fs-1 mb-3 text-success opacity-50"></i><h5 class="fw-bold text-dark">No Pending Enrollments</h5></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- GATEPASSES TAB -->
    <div class="tab-pane fade" id="gatepasses" role="tabpanel">
        <div class="glass-card p-0 border-0 shadow-sm overflow-hidden text-nowrap rounded-bottom-4 border">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase fw-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3">Student</th>
                            <th class="py-3">Destination & Reason</th>
                            <th class="py-3">Duration</th>
                            <th class="text-end pe-4 py-3">Authorization</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingGatepasses as $gp)
                        <tr>
                            <td class="ps-4"><div class="fw-bold text-dark">{{ $gp->user->name }}</div><div class="small text-muted font-monospace">{{ $gp->user->enrollment_no }}</div></td>
                            <td><div class="fw-bold text-dark">{{ $gp->destination }}</div><div class="small text-muted text-truncate" style="max-width:200px;">{{ $gp->reason }}</div></td>
                            <td>
                                <div class="small text-danger"><i class="fas fa-sign-out-alt me-1"></i> {{ \Carbon\Carbon::parse($gp->out_time)->format('M d, h:i A') }}</div>
                                <div class="small text-success"><i class="fas fa-sign-in-alt me-1"></i> {{ \Carbon\Carbon::parse($gp->in_time)->format('M d, h:i A') }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="/admin/approvals/gatepass/{{ $gp->id }}/process" method="POST">
                                        @csrf <input type="hidden" name="action" value="reject">
                                        <button class="btn btn-outline-danger btn-sm rounded-pill shadow-sm fw-bold"><i class="fas fa-times"></i></button>
                                    </form>
                                    <form action="/admin/approvals/gatepass/{{ $gp->id }}/process" method="POST">
                                        @csrf <input type="hidden" name="action" value="approve">
                                        <button class="btn btn-success btn-sm rounded-pill shadow-sm fw-bold"><i class="fas fa-check"></i> Authorize</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted"><i class="fas fa-ticket-alt fs-1 mb-3 text-success opacity-50"></i><h5 class="fw-bold text-dark">No Pending Gatepasses</h5></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- LEAVES TAB -->
    <div class="tab-pane fade" id="leaves" role="tabpanel">
        <div class="glass-card p-0 border-0 shadow-sm overflow-hidden text-nowrap rounded-bottom-4 border">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase fw-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3">Student</th>
                            <th class="py-3">Type & Reason</th>
                            <th class="py-3">Leave Dates</th>
                            <th class="text-end pe-4 py-3">Authorization</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingLeaves as $lv)
                        <tr>
                            <td class="ps-4"><div class="fw-bold text-dark">{{ $lv->user->name }}</div><div class="small text-muted font-monospace">{{ $lv->user->enrollment_no }}</div></td>
                            <td>
                                <div class="badge bg-danger bg-opacity-10 text-danger border border-danger rounded-pill mb-1">{{ $lv->leave_type }}</div>
                                <div class="small text-muted text-truncate" style="max-width:200px;">{{ $lv->reason }}</div>
                            </td>
                            <td>
                                <div class="fw-bold text-primary">{{ \Carbon\Carbon::parse($lv->start_date)->format('M d, Y') }}</div>
                                <div class="small text-muted fw-bold text-center">TO</div>
                                <div class="fw-bold text-primary">{{ \Carbon\Carbon::parse($lv->end_date)->format('M d, Y') }}</div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    <form action="/admin/approvals/leave/{{ $lv->id }}/process" method="POST">
                                        @csrf <input type="hidden" name="action" value="reject">
                                        <button class="btn btn-outline-danger btn-sm rounded-pill shadow-sm fw-bold"><i class="fas fa-times"></i></button>
                                    </form>
                                    <form action="/admin/approvals/leave/{{ $lv->id }}/process" method="POST">
                                        @csrf <input type="hidden" name="action" value="approve">
                                        <button class="btn btn-success btn-sm rounded-pill shadow-sm fw-bold"><i class="fas fa-check"></i> Sanction</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center py-5 text-muted"><i class="fas fa-bed fs-1 mb-3 text-success opacity-50"></i><h5 class="fw-bold text-dark">No Pending Leaves</h5></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- PASSWORDS TAB -->
    <div class="tab-pane fade" id="passwords" role="tabpanel">
        <div class="glass-card p-0 border-0 shadow-sm overflow-hidden text-nowrap rounded-bottom-4 border">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase fw-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3">Account Email</th>
                            <th class="py-3">Requested Password</th>
                            <th class="text-end pe-4 py-3">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingPasswords as $pwd)
                        <tr>
                            <td class="ps-4"><div class="fw-bold text-dark">{{ $pwd->email }}</div></td>
                            <td>
                                <div class="small fw-bold text-muted mb-1">User requested to set: <span class="text-primary">{{ $pwd->requested_password }}</span></div>
                            </td>
                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2 align-items-center">
                                    <form action="/admin/approvals/password/{{ $pwd->id }}/process" method="POST" class="d-flex align-items-center m-0">
                                        @csrf <input type="hidden" name="action" value="approve">
                                        <input type="text" name="override_password" value="{{ $pwd->requested_password }}" class="form-control form-control-sm me-2 shadow-sm" style="width: 150px;" required>
                                        <button class="btn btn-success btn-sm rounded-pill shadow-sm fw-bold text-nowrap"><i class="fas fa-check"></i> Set & Approve</button>
                                    </form>
                                    <form action="/admin/approvals/password/{{ $pwd->id }}/process" method="POST" class="m-0">
                                        @csrf <input type="hidden" name="action" value="reject">
                                        <button class="btn btn-outline-danger btn-sm rounded-pill shadow-sm fw-bold"><i class="fas fa-times"></i> Reject</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center py-5 text-muted"><i class="fas fa-shield-alt fs-1 mb-3 text-success opacity-50"></i><h5 class="fw-bold text-dark">No Pending Resets</h5></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.nav-tabs .nav-link {
    border-color: transparent !important;
    background: transparent;
    transition: all 0.3s ease;
}
.nav-tabs .nav-link.active {
    background: #fff;
    border: 1px solid #e2e8f0 !important;
    border-bottom-color: #fff !important;
    box-shadow: 0 -4px 10px rgba(0,0,0,0.02);
}
.nav-tabs .nav-link:hover:not(.active) {
    background: #f8fafc;
    transform: translateY(-2px);
}
</style>

<!-- Signature Modal -->
<div class="modal fade" id="signatureModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header bg-dark text-white border-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-signature me-2 text-info"></i> Digital Signature Verification</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center bg-light">
                <img id="signatureImage" src="" alt="Digital Signature" class="img-fluid border bg-white rounded shadow-sm" style="max-height: 200px;">
                <div class="mt-3 text-muted small fw-bold">By accepting the T&C and applying this signature, the student has bound themselves to the Institutional Code of Conduct.</div>
            </div>
            <div class="modal-footer border-top-0">
                <button type="button" class="btn btn-secondary px-4 fw-bold" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
function viewSignature(dataUrl) {
    document.getElementById('signatureImage').src = dataUrl;
    new bootstrap.Modal(document.getElementById('signatureModal')).show();
}
</script>

@endsection
