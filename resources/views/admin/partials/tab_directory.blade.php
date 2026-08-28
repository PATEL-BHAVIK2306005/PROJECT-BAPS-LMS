@if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'cr']))
<div class="tab-pane fade" id="tab-directory" role="tabpanel">
    <!-- Sub-Pills for Directory Domain -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4 pb-2 border-bottom">
        <div>
            <h4 class="fw-bold mb-1 text-dark"><i class="fas fa-users text-info me-2"></i> Directory & Institutional Stakeholders</h4>
            <p class="text-muted small mb-0">Manage university faculties, student records, guardians, and pending credential approvals.</p>
        </div>
        <div class="d-flex flex-wrap gap-2">
            <button class="btn btn-sm btn-outline-warning fw-bold rounded-pill px-3" onclick="activateTab('tab-approvals')">
                <i class="fas fa-check-circle me-1"></i> Approvals Desk
            </button>
            <button class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3" onclick="activateTab('tab-admin-ptm')">
                <i class="fas fa-comments me-1"></i> PTM Hub
            </button>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="/admin/departments" class="action-btn py-4 shadow-sm text-center d-flex flex-column align-items-center justify-content-center gap-2">
                <i class="fas fa-building text-secondary fs-2"></i>
                <span class="fw-bold text-dark fs-6">Academic Departments</span>
                <small class="text-muted">Engineering & Science Wings</small>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="/admin/staff" class="action-btn py-4 shadow-sm text-center d-flex flex-column align-items-center justify-content-center gap-2">
                <i class="fas fa-chalkboard-teacher text-info fs-2"></i>
                <span class="fw-bold text-dark fs-6">Faculty & Staff Roster</span>
                <small class="text-muted">Professors, HODs & Assistants</small>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="/admin/students" class="action-btn py-4 shadow-sm text-center d-flex flex-column align-items-center justify-content-center gap-2">
                <i class="fas fa-user-graduate text-success fs-2"></i>
                <span class="fw-bold text-dark fs-6">Student Database</span>
                <small class="text-muted">Enrollments & Progress Cards</small>
            </a>
        </div>
        <div class="col-12 col-sm-6 col-lg-3">
            <a href="/admin/parents" class="action-btn py-4 shadow-sm text-center d-flex flex-column align-items-center justify-content-center gap-2">
                <i class="fas fa-user-shield text-danger fs-2"></i>
                <span class="fw-bold text-dark fs-6">Parent Directory</span>
                <small class="text-muted">Guardian Records & PTM Access</small>
            </a>
        </div>
    </div>

    <!-- Quick Stakeholder Summary -->
    <div class="row g-4">
        <div class="col-md-6">
            <div class="content-card h-100 mb-0">
                <div class="content-card-header">
                    <h5 class="content-card-title"><i class="fas fa-check-double text-warning"></i> Quick Approvals Gateway</h5>
                </div>
                <p class="text-muted small mb-3">Authorize student course requests, gatepasses, hostel leaves, and access codes.</p>
                <button class="action-btn action-btn-primary" onclick="activateTab('tab-approvals')">
                    <i class="fas fa-clipboard-check me-2"></i> Open Approvals Management Center
                </button>
            </div>
        </div>
        <div class="col-md-6">
            <div class="content-card h-100 mb-0">
                <div class="content-card-header">
                    <h5 class="content-card-title"><i class="fas fa-comments text-danger"></i> Parent-Teacher Engagement</h5>
                </div>
                <p class="text-muted small mb-3">Schedule guardian meetings, broadcast notices to parents, and track student attendance reports.</p>
                <button class="action-btn" onclick="activateTab('tab-admin-ptm')">
                    <i class="fas fa-handshake text-danger me-2"></i> Open PTM & Guardian Portal
                </button>
            </div>
        </div>
    </div>
</div>
@endif
