@if(in_array(session('user_role'), ['admin', 'cr', 'hod', 'dean', 'office-assistant']) || session('staff_name') == 'Rajunakum Sir')
<div class="tab-pane fade" id="tab-approvals" role="tabpanel">

    @php
        try { $apvStudents   = \App\Models\User::where('status','pending')->count(); } catch(\Exception $e) { $apvStudents = 0; }
        try { $apvEnroll     = \App\Models\Enrollment::where('status','pending')->count(); } catch(\Exception $e) { $apvEnroll = 0; }
        try { $apvGatepass   = \App\Models\Gatepass::where('status','pending')->count(); } catch(\Exception $e) { $apvGatepass = 0; }
        try { $apvLeave      = \App\Models\Leave::where('status','pending')->count(); } catch(\Exception $e) { $apvLeave = 0; }
        try { $apvPassword   = \App\Models\PasswordApproval::where('status','pending')->count(); } catch(\Exception $e) { $apvPassword = 0; }
        try { $apvFee        = \App\Models\FeePayment::where('status','pending')->count(); } catch(\Exception $e) { $apvFee = 0; }
        $totalPending  = $apvStudents + $apvEnroll + $apvGatepass + $apvLeave + $apvPassword + $apvFee;

        try { $apvStudentsApproved  = \App\Models\User::where('status','approved')->count(); } catch(\Exception $e) { $apvStudentsApproved = 0; }
        try { $apvEnrollApproved    = \App\Models\Enrollment::where('status','approved')->count(); } catch(\Exception $e) { $apvEnrollApproved = 0; }
        try { $apvGatepassApproved  = \App\Models\Gatepass::where('status','approved')->count(); } catch(\Exception $e) { $apvGatepassApproved = 0; }
        try { $apvLeaveApproved     = \App\Models\Leave::where('status','approved')->count(); } catch(\Exception $e) { $apvLeaveApproved = 0; }
        try { $apvPasswordApproved  = \App\Models\PasswordApproval::where('status','approved')->count(); } catch(\Exception $e) { $apvPasswordApproved = 0; }
        try { $apvFeeApproved       = \App\Models\FeePayment::where('status','paid')->count(); } catch(\Exception $e) { $apvFeeApproved = 0; }
        $totalApproved = $apvStudentsApproved + $apvEnrollApproved + $apvGatepassApproved + $apvLeaveApproved + $apvPasswordApproved + $apvFeeApproved;

        try { $apvStudentsRejected  = \App\Models\User::where('status','rejected')->count(); } catch(\Exception $e) { $apvStudentsRejected = 0; }
        try { $apvEnrollRejected    = \App\Models\Enrollment::where('status','rejected')->count(); } catch(\Exception $e) { $apvEnrollRejected = 0; }
        try { $apvGatepassRejected  = \App\Models\Gatepass::where('status','rejected')->count(); } catch(\Exception $e) { $apvGatepassRejected = 0; }
        try { $apvLeaveRejected     = \App\Models\Leave::where('status','rejected')->count(); } catch(\Exception $e) { $apvLeaveRejected = 0; }
        try { $apvPasswordRejected  = \App\Models\PasswordApproval::where('status','rejected')->count(); } catch(\Exception $e) { $apvPasswordRejected = 0; }
        try { $apvFeeRejected       = \App\Models\FeePayment::where('status','rejected')->count(); } catch(\Exception $e) { $apvFeeRejected = 0; }
        $totalRejected = $apvStudentsRejected + $apvEnrollRejected + $apvGatepassRejected + $apvLeaveRejected + $apvPasswordRejected + $apvFeeRejected;

        $grandTotal = $totalPending + $totalApproved + $totalRejected;
        $pendingPct  = $grandTotal > 0 ? round(($totalPending  / $grandTotal) * 100) : 0;
        $approvedPct = $grandTotal > 0 ? round(($totalApproved / $grandTotal) * 100) : 0;
        $rejectedPct = $grandTotal > 0 ? round(($totalRejected / $grandTotal) * 100) : 0;
    @endphp

    <style>
        .apv-summary-bar { background:#fff; border:1px solid var(--baps-border); border-radius:16px; padding:24px 28px; margin-bottom:28px; box-shadow:0 4px 12px rgba(0,0,0,0.03); }
        .apv-progress-track { height:12px; border-radius:99px; background:#f1f5f9; overflow:hidden; display:flex; margin:18px 0 14px; }
        .apv-progress-track span { display:block; height:100%; transition:width .6s ease; }
        .apv-cat-card { background:#fff; border:1px solid var(--baps-border); border-radius:14px; padding:22px 24px; display:flex; align-items:center; gap:18px; box-shadow:0 4px 12px rgba(0,0,0,0.03); transition:all .3s ease; height: 100%; }
        .apv-cat-card:hover { transform:translateY(-4px); box-shadow:0 12px 24px rgba(0,0,0,0.08); border-color:#cbd5e1; }
        .apv-cat-icon { width:54px; height:54px; border-radius:14px; display:flex; align-items:center; justify-content:center; font-size:1.5rem; flex-shrink:0; transition:transform .3s ease; }
        .apv-cat-card:hover .apv-cat-icon { transform:scale(1.1); }
        .apv-cat-name  { font-size:.9rem; font-weight:700; color:#64748b; text-transform:uppercase; letter-spacing:.5px; }
        .apv-cat-count { font-size:1.8rem; font-weight:800; line-height:1.1; color:#0f172a; margin: 4px 0; }
        .apv-cat-sub   { font-size:.8rem; color:#94a3b8; }
        .apv-badge-pending  { background:#fff7ed; color:#ea580c; border:1px solid #fed7aa; border-radius:8px; padding:4px 12px; font-size:.8rem; font-weight:700; }
        .apv-badge-approved { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; border-radius:8px; padding:4px 12px; font-size:.8rem; font-weight:700; }
    </style>

    {{-- SUMMARY BAR --}}
    <div class="apv-summary-bar">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-4">
            <div>
                <div class="fw-bold text-dark fs-5">
                    <i class="fas fa-clipboard-list me-2" style="color:var(--baps-saffron);"></i>
                    Approvals Analytics — Live Overview
                </div>
                <div class="small text-muted mt-1 fs-6">Real-time counts across all institutional approval queues</div>
            </div>
            <div class="d-flex gap-4 flex-wrap">
                <div class="text-center">
                    <div style="font-size:1.8rem; font-weight:800; color:#f97316; line-height:1;">{{ $totalPending }}</div>
                    <div class="small text-muted fw-bold mt-1">PENDING</div>
                </div>
                <div class="text-center">
                    <div style="font-size:1.8rem; font-weight:800; color:#22c55e; line-height:1;">{{ $totalApproved }}</div>
                    <div class="small text-muted fw-bold mt-1">APPROVED</div>
                </div>
                <div class="text-center">
                    <div style="font-size:1.8rem; font-weight:800; color:#ef4444; line-height:1;">{{ $totalRejected }}</div>
                    <div class="small text-muted fw-bold mt-1">REJECTED</div>
                </div>
            </div>
        </div>
        <div class="apv-progress-track">
            <span style="width:{{ $approvedPct }}%; background:#22c55e;" title="Approved: {{ $approvedPct }}%"></span>
            <span style="width:{{ $pendingPct }}%; background:#f97316;" title="Pending: {{ $pendingPct }}%"></span>
            <span style="width:{{ $rejectedPct }}%; background:#ef4444;" title="Rejected: {{ $rejectedPct }}%"></span>
        </div>
        <div class="d-flex gap-4 flex-wrap" style="font-size:.85rem; font-weight:600;">
            <span class="d-flex align-items-center"><span style="display:inline-block; width:12px; height:12px; border-radius:50%; background:#22c55e; margin-right:8px;"></span>Approved {{ $approvedPct }}%</span>
            <span class="d-flex align-items-center"><span style="display:inline-block; width:12px; height:12px; border-radius:50%; background:#f97316; margin-right:8px;"></span>Pending {{ $pendingPct }}%</span>
            <span class="d-flex align-items-center"><span style="display:inline-block; width:12px; height:12px; border-radius:50%; background:#ef4444; margin-right:8px;"></span>Rejected {{ $rejectedPct }}%</span>
        </div>
    </div>

    {{-- CATEGORY CARDS --}}
    <div class="row g-4 mb-4">

        <div class="col-12 col-md-6 col-lg-4">
            <div class="apv-cat-card">
                <div class="apv-cat-icon" style="background:#eff6ff; color:#3b82f6;"><i class="fas fa-user-plus"></i></div>
                <div class="flex-grow-1">
                    <div class="apv-cat-name">New Registrations</div>
                    <div class="apv-cat-count">{{ $apvStudents }}</div>
                    <div class="apv-cat-sub">{{ $apvStudentsApproved }} approved &nbsp;·&nbsp; {{ $apvStudentsRejected }} rejected</div>
                </div>
                @if($apvStudents > 0)<span class="apv-badge-pending">{{ $apvStudents }} Pending</span>@else<span class="apv-badge-approved">All Clear</span>@endif
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="apv-cat-card">
                <div class="apv-cat-icon" style="background:#f0fdf4; color:#22c55e;"><i class="fas fa-book-reader"></i></div>
                <div class="flex-grow-1">
                    <div class="apv-cat-name">Course Enrollments</div>
                    <div class="apv-cat-count">{{ $apvEnroll }}</div>
                    <div class="apv-cat-sub">{{ $apvEnrollApproved }} approved &nbsp;·&nbsp; {{ $apvEnrollRejected }} rejected</div>
                </div>
                @if($apvEnroll > 0)<span class="apv-badge-pending">{{ $apvEnroll }} Pending</span>@else<span class="apv-badge-approved">All Clear</span>@endif
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="apv-cat-card">
                <div class="apv-cat-icon" style="background:#fffbeb; color:#f59e0b;"><i class="fas fa-ticket-alt"></i></div>
                <div class="flex-grow-1">
                    <div class="apv-cat-name">Gatepass Requests</div>
                    <div class="apv-cat-count">{{ $apvGatepass }}</div>
                    <div class="apv-cat-sub">{{ $apvGatepassApproved }} approved &nbsp;·&nbsp; {{ $apvGatepassRejected }} rejected</div>
                </div>
                @if($apvGatepass > 0)<span class="apv-badge-pending">{{ $apvGatepass }} Pending</span>@else<span class="apv-badge-approved">All Clear</span>@endif
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="apv-cat-card">
                <div class="apv-cat-icon" style="background:#fef2f2; color:#ef4444;"><i class="fas fa-calendar-minus"></i></div>
                <div class="flex-grow-1">
                    <div class="apv-cat-name">Leave Applications</div>
                    <div class="apv-cat-count">{{ $apvLeave }}</div>
                    <div class="apv-cat-sub">{{ $apvLeaveApproved }} approved &nbsp;·&nbsp; {{ $apvLeaveRejected }} rejected</div>
                </div>
                @if($apvLeave > 0)<span class="apv-badge-pending">{{ $apvLeave }} Pending</span>@else<span class="apv-badge-approved">All Clear</span>@endif
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="apv-cat-card">
                <div class="apv-cat-icon" style="background:#f8fafc; color:#64748b;"><i class="fas fa-key"></i></div>
                <div class="flex-grow-1">
                    <div class="apv-cat-name">Password Resets</div>
                    <div class="apv-cat-count">{{ $apvPassword }}</div>
                    <div class="apv-cat-sub">{{ $apvPasswordApproved }} approved &nbsp;·&nbsp; {{ $apvPasswordRejected }} rejected</div>
                </div>
                @if($apvPassword > 0)<span class="apv-badge-pending">{{ $apvPassword }} Pending</span>@else<span class="apv-badge-approved">All Clear</span>@endif
            </div>
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="apv-cat-card">
                <div class="apv-cat-icon" style="background:#fdf4ff; color:#a855f7;"><i class="fas fa-rupee-sign"></i></div>
                <div class="flex-grow-1">
                    <div class="apv-cat-name">Fee Verifications</div>
                    <div class="apv-cat-count">{{ $apvFee }}</div>
                    <div class="apv-cat-sub">{{ $apvFeeApproved }} paid &nbsp;·&nbsp; {{ $apvFeeRejected }} rejected</div>
                </div>
                @if($apvFee > 0)<span class="apv-badge-pending">{{ $apvFee }} Pending</span>@else<span class="apv-badge-approved">All Clear</span>@endif
            </div>
        </div>

    </div>

    {{-- ENTER PORTAL BUTTON --}}
    <div class="text-center pt-2 mb-4">
        <a href="/admin/approvals" class="action-btn action-btn-primary py-3 px-5 shadow-sm" style="width: auto; font-size:1.05rem;">
            <i class="fas fa-sign-in-alt me-2"></i> Enter Approvals Portal
            @if($totalPending > 0)
            <span class="badge bg-white text-danger fw-bold ms-2 px-2 py-1">{{ $totalPending }}</span>
            @endif
        </a>
    </div>

</div>
@endif
