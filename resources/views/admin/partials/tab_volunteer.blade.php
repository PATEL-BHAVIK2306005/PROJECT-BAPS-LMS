<div class="tab-pane fade" id="tab-volunteer" role="tabpanel">
    <!-- Volunteer Service Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #11111b !important; border-left: 5px solid #ffd700 !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; background: #1e1e2e !important; color: #ffd700 !important;">
                        <i class="fas fa-hands-helping"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #ffd700 !important;">
                            Volunteer Service Ledger
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">Community Outreach</span>
                        </h4>
                        <div class="small fw-semibold" style="color: #ffffff !important; opacity: 0.85;">Track, verify, and reward student volunteer service hours for community and values-driven initiatives.</div>
                    </div>
                </div>
                <div>
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill shadow-sm fw-bold">
                        <i class="fas fa-clock text-warning me-1"></i> Total Logged: 1,420 Hours
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Log Hours Form -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-plus-circle text-teal me-2"></i> Log Volunteer Hours</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form id="volunteerLogForm" onsubmit="event.preventDefault(); submitVolunteerLog();">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Select Student <span class="text-danger">*</span></label>
                            <select id="vol_student" class="form-select py-2 fs-6" required>
                                @if(isset($studentsList) && count($studentsList) > 0)
                                    @foreach($studentsList as $student)
                                        <option value="{{ $student->name }} (ENR{{ $student->enrollment_no }})">{{ $student->name }}</option>
                                    @endforeach
                                @else
                                    <option value="Akshar Patel (ENR20260001)">Akshar Patel</option>
                                    <option value="Shanti Patel (ENR20260002)">Shanti Patel</option>
                                    <option value="Vinu Patel (ENR20260003)">Vinu Patel</option>
                                @endif
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Activity / Event <span class="text-danger">*</span></label>
                            <select id="vol_activity" class="form-select py-2 fs-6" required>
                                <option value="IPDC Seminar Assistance">IPDC Seminar Assistance</option>
                                <option value="Green Campus Cleanliness Drive">Green Campus Cleanliness Drive</option>
                                <option value="Community Kitchen Service">Community Kitchen Service</option>
                                <option value="Annual Sports Meet Volunteer">Annual Sports Meet Volunteer</option>
                                <option value="Accreditation Helpdesk Assistant">Accreditation Helpdesk Assistant</option>
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Hours Logged <span class="text-danger">*</span></label>
                                <input type="number" id="vol_hours" class="form-control py-2 fs-6" min="1" max="100" value="4" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Date <span class="text-danger">*</span></label>
                                <input type="date" id="vol_date" class="form-control py-2 fs-6" value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-teal w-100 py-3 rounded-pill text-white fw-bold shadow-sm" style="background-color: #0d9488;">
                            <i class="fas fa-save me-2"></i> File Service Log
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Volunteer Logs Registry -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-clipboard-list text-teal me-2"></i> Service Registry & Approvals</h5>
                    <span class="badge bg-teal-light text-teal px-3 py-1 rounded-pill small fw-bold">Live Audit Trail</span>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-muted fw-bold">
                                <tr>
                                    <th>Student</th>
                                    <th>Activity</th>
                                    <th>Hours</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="volunteerLogTableBody">
                                <!-- Pre-populated mock values -->
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">Akshar Patel</div>
                                        <div class="small text-muted">ENR20260001</div>
                                    </td>
                                    <td>IPDC Seminar Assistance</td>
                                    <td><span class="badge bg-light text-dark border px-3 py-1 rounded-pill fw-bold">8 Hours</span></td>
                                    <td>2026-05-18</td>
                                    <td><span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Pending</span></td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-success rounded-circle me-1" onclick="approveVolunteerLog(this)" title="Approve"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-sm btn-danger rounded-circle" onclick="declineVolunteerLog(this)" title="Decline"><i class="fas fa-times"></i></button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">Shanti Patel</div>
                                        <div class="small text-muted">ENR20260002</div>
                                    </td>
                                    <td>Green Campus Cleanliness Drive</td>
                                    <td><span class="badge bg-light text-dark border px-3 py-1 rounded-pill fw-bold">5 Hours</span></td>
                                    <td>2026-05-17</td>
                                    <td><span class="badge bg-success px-3 py-1 rounded-pill">Approved</span></td>
                                    <td class="text-end text-muted small fw-bold"><i class="fas fa-check-double text-success me-1"></i> Verified</td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">Vinu Patel</div>
                                        <div class="small text-muted">ENR20260003</div>
                                    </td>
                                    <td>Community Kitchen Service</td>
                                    <td><span class="badge bg-light text-dark border px-3 py-1 rounded-pill fw-bold">12 Hours</span></td>
                                    <td>2026-05-16</td>
                                    <td><span class="badge bg-success px-3 py-1 rounded-pill">Approved</span></td>
                                    <td class="text-end text-muted small fw-bold"><i class="fas fa-check-double text-success me-1"></i> Verified</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function approveVolunteerLog(btn) {
        const row = btn.closest('tr');
        row.cells[4].innerHTML = '<span class="badge bg-success px-3 py-1 rounded-pill">Approved</span>';
        row.cells[5].innerHTML = '<span class="text-success fw-bold small"><i class="fas fa-check-double me-1"></i> Verified</span>';
        if (typeof showBapsToast === 'function') showBapsToast('Volunteer Hours Approved! 🌟', 'success');
    }

    function declineVolunteerLog(btn) {
        const row = btn.closest('tr');
        row.cells[4].innerHTML = '<span class="badge bg-danger px-3 py-1 rounded-pill">Declined</span>';
        row.cells[5].innerHTML = '<span class="text-danger fw-bold small"><i class="fas fa-ban me-1"></i> Rejected</span>';
        if (typeof showBapsToast === 'function') showBapsToast('Volunteer Hours Declined. ❌', 'error');
    }

    function submitVolunteerLog() {
        const studentInfo = document.getElementById('vol_student').value;
        const activity = document.getElementById('vol_activity').value;
        const hours = document.getElementById('vol_hours').value;
        const date = document.getElementById('vol_date').value;

        if (!studentInfo || !hours) return;

        const parts = studentInfo.split(' (');
        const name = parts[0];
        const enr = parts[1] ? parts[1].replace(')', '') : 'N/A';

        const tbody = document.getElementById('volunteerLogTableBody');
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>
                <div class="fw-bold text-dark">${name}</div>
                <div class="small text-muted">${enr}</div>
            </td>
            <td>${activity}</td>
            <td><span class="badge bg-light text-dark border px-3 py-1 rounded-pill fw-bold">${hours} Hours</span></td>
            <td>${date}</td>
            <td><span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Pending</span></td>
            <td class="text-end">
                <button class="btn btn-sm btn-success rounded-circle me-1" onclick="approveVolunteerLog(this)" title="Approve"><i class="fas fa-check"></i></button>
                <button class="btn btn-sm btn-danger rounded-circle" onclick="declineVolunteerLog(this)" title="Decline"><i class="fas fa-times"></i></button>
            </td>
        `;
        tbody.insertBefore(tr, tbody.firstChild);

        if (typeof showBapsToast === 'function') showBapsToast('New Volunteer Service log registered successfully! ✍️', 'success');
        document.getElementById('volunteerLogForm').reset();
    }
</script>
