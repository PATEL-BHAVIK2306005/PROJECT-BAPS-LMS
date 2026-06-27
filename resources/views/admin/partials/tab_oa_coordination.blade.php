@if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod']))
<div class="tab-pane fade" id="tab-oa-coordination" role="tabpanel">
    
    <!-- Executive Banner -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #11111b !important; border-left: 5px solid #ffd700 !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; background: #1e1e2e !important; color: #ffd700 !important;">
                        <i class="fas fa-handshake"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #ffd700 !important;">
                            Office Assistant & Admin Coordination Bridge
                            <span class="badge bg-primary text-white px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">175% ⇄ 200% Sync</span>
                        </h4>
                        <div class="small fw-semibold" style="color: #ffffff !important; opacity: 0.85;">Centralized task delegation, priority escalations, and executive dual-approval workflows.</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-3">
                    <span class="badge bg-success text-white px-3 py-2 rounded-pill text-uppercase fs-6 shadow-sm d-flex align-items-center gap-2 badge-ai-copilot" style="font-size: 0.75rem !important; letter-spacing: 0.5px;">
                        <span class="spinner-grow spinner-grow-sm text-light" style="width: 8px; height: 8px;"></span> AI Copilot Active (15s Loop)
                    </span>
                    <button class="btn btn-light fw-bold px-4 py-2 rounded-pill shadow-sm text-dark" data-bs-toggle="modal" data-bs-target="#officeAssistantConsoleModal">
                        <i class="fas fa-external-link-alt me-2 text-purple" style="color: #9333ea;"></i> Open OA Hub
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-4">
        <!-- Assign Task Form (Admin/Dean to OA) -->
        <div class="col-12 col-lg-5">
            <div class="card border-0 shadow-sm rounded-4 h-100" style="border-top: 4px solid #3b82f6;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                    <h5 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-tasks text-primary me-2"></i> Delegate Task to Office Assistant</h5>
                </div>
                <div class="card-body p-4">
                    <form id="coordinationTaskForm" onsubmit="event.preventDefault(); submitCoordinationTask();">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Task Headline / Directive</label>
                            <input type="text" id="coord_task_title" class="form-control py-2" placeholder="e.g., Audit UGC Compliance Records / Setup Convocation Hall" required>
                        </div>
                        <div class="row g-3 mb-3">
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-muted">Priority Level</label>
                                <select id="coord_task_priority" class="form-select py-2" required>
                                    <option value="Critical (Immediate)">🔴 Critical (Immediate)</option>
                                    <option value="High (24 Hours)" selected>🟠 High (24 Hours)</option>
                                    <option value="Medium (3 Days)">🟡 Medium (3 Days)</option>
                                    <option value="Routine">🟢 Routine</option>
                                </select>
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-muted">Assigned By</label>
                                <select id="coord_task_assigner" class="form-select py-2" required>
                                    <option value="Dr. Sadhu Gyaneswar Das (Dean)">Dr. Sadhu Gyaneswar Das (Dean)</option>
                                    <option value="Bhavik Patel (Administrator)">Bhavik Patel (Administrator)</option>
                                    <option value="System Automated Directive">System Automated Directive</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">Detailed Instructions & Expected Deliverable</label>
                            <textarea id="coord_task_desc" class="form-control py-2" rows="3" placeholder="Provide full context, necessary files, and completion criteria..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm">
                            <i class="fas fa-paper-plane me-2"></i> Dispatch Directive to Office Assistant
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Live Task Delegation Kanban / List -->
        <div class="col-12 col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="border-top: 4px solid #f59e0b;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <h5 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-clipboard-list text-warning me-2"></i> Active Executive Directives</h5>
                    <span class="badge bg-warning text-dark border px-3 py-2 rounded-pill">Real-Time Sync</span>
                </div>
                <div class="card-body p-4 overflow-auto">
                    <div class="table-responsive border rounded-3 mb-4">
                        <table class="table table-hover align-middle mb-0" id="coordinationTaskTable">
                            <thead class="table-light">
                                <tr><th>DIRECTIVE / TASK</th><th>ASSIGNER</th><th>PRIORITY</th><th>STATUS</th><th>ACTION</th></tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">UGC Inspection File Sealing</div>
                                        <div class="small text-muted">Verify all 22 official documents for NAAC audit</div>
                                    </td>
                                    <td class="fw-semibold">Dr. Gyaneswar Das</td>
                                    <td><span class="badge bg-danger px-3 py-1 rounded-pill">Critical</span></td>
                                    <td><span class="badge bg-warning text-dark px-3 py-1 rounded-pill">In Progress</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="completeCoordTask(this)">
                                            <i class="fas fa-check me-1"></i> Sign-Off
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">Convocation VIP Seating & Logistics</div>
                                        <div class="small text-muted">Coordinate with hostel and transport desks</div>
                                    </td>
                                    <td class="fw-semibold">Bhavik Patel (Admin)</td>
                                    <td><span class="badge bg-warning text-dark px-3 py-1 rounded-pill">High</span></td>
                                    <td><span class="badge bg-info text-white px-3 py-1 rounded-pill">Assigned</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="completeCoordTask(this)">
                                            <i class="fas fa-check me-1"></i> Sign-Off
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark">Semester Fee Defaulter Notices</div>
                                        <div class="small text-muted">Dispatch official warnings to student portals</div>
                                    </td>
                                    <td class="fw-semibold">Bhavik Patel (Admin)</td>
                                    <td><span class="badge bg-secondary px-3 py-1 rounded-pill">Routine</span></td>
                                    <td><span class="badge bg-success px-3 py-1 rounded-pill">Completed</span></td>
                                    <td><span class="text-success fw-bold small"><i class="fas fa-check-double me-1"></i> Verified</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Priority Escalation & Memo Board -->
    <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-top: 4px solid #9333ea;">
        <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-exclamation-triangle text-purple me-2" style="color: #9333ea;"></i> Priority Escalation & Executive Memo Board</h5>
            <button class="btn btn-sm btn-purple fw-bold px-4 py-2 rounded-pill shadow-sm text-white" style="background: #9333ea;" onclick="triggerOaMemoPrompt()">
                <i class="fas fa-plus me-1"></i> Dispatch Urgent Memo
            </button>
        </div>
        <div class="card-body p-4">
            <div class="row g-4" id="memoCardsContainer">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border border-2 border-danger rounded-4 h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-danger px-3 py-1 rounded-pill text-white fw-bold">URGENT ESCALATION</span>
                                <span class="small text-muted fw-bold">10:30 AM</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Server Rack UPS Battery Failure</h6>
                            <p class="text-muted small mb-3">The primary UPS backup for the CSE server room is indicating a fault. Requesting immediate administrative approval for emergency replacement.</p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <span class="small fw-bold text-secondary"><i class="fas fa-user-tie me-1"></i> Office Assistant Desk</span>
                                <button class="btn btn-sm btn-outline-danger rounded-pill px-3 py-1 fw-bold" onclick="acknowledgeMemo(this)">Acknowledge</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border border-2 border-warning rounded-4 h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill fw-bold">HIGH PRIORITY</span>
                                <span class="small text-muted fw-bold">Yesterday</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Guest House VIP Suite Allocation</h6>
                            <p class="text-muted small mb-3">AICTE inspection committee arriving tomorrow evening. Suite #101 and #102 have been reserved and inspected. Awaiting Dean sign-off.</p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <span class="small fw-bold text-secondary"><i class="fas fa-user-tie me-1"></i> Office Assistant Desk</span>
                                <button class="btn btn-sm btn-outline-warning rounded-pill px-3 py-1 fw-bold text-dark" onclick="acknowledgeMemo(this)">Acknowledge</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="card border border-2 border-info rounded-4 h-100 shadow-sm">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-info text-white px-3 py-1 rounded-pill fw-bold">ADMIN DIRECTIVE</span>
                                <span class="small text-muted fw-bold">May 15</span>
                            </div>
                            <h6 class="fw-bold text-dark mb-2">Biometric Attendance Verification</h6>
                            <p class="text-muted small mb-3">Monthly staff biometric logs have been compiled. Discrepancies noted in 3 lab assistant profiles. Report forwarded to HOD CSE.</p>
                            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                <span class="small fw-bold text-secondary"><i class="fas fa-user-shield me-1"></i> Bhavik Patel (Admin)</span>
                                <span class="badge bg-success px-3 py-1 rounded-pill">Acknowledged</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function submitCoordinationTask() {
        const title = document.getElementById('coord_task_title').value;
        const priority = document.getElementById('coord_task_priority').value;
        const assigner = document.getElementById('coord_task_assigner').value;
        const desc = document.getElementById('coord_task_desc').value;
        
        let badgeClass = 'bg-primary';
        if (priority.includes('Critical')) badgeClass = 'bg-danger';
        if (priority.includes('High')) badgeClass = 'bg-warning text-dark';
        if (priority.includes('Medium')) badgeClass = 'bg-info text-white';

        const table = document.getElementById('coordinationTaskTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow(0);
        newRow.innerHTML = `
            <td>
                <div class="fw-bold text-dark">${title}</div>
                <div class="small text-muted">${desc}</div>
            </td>
            <td class="fw-semibold">${assigner}</td>
            <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill">${priority.split(' ')[1] || priority}</span></td>
            <td><span class="badge bg-info text-white px-3 py-1 rounded-pill">Assigned</span></td>
            <td>
                <button class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="completeCoordTask(this)">
                    <i class="fas fa-check me-1"></i> Sign-Off
                </button>
            </td>
        `;
        document.getElementById('coordinationTaskForm').reset();
        if (typeof showBapsToast === 'function') showBapsToast('Directive dispatched to Office Assistant successfully! 📋', 'success');
    }

    function completeCoordTask(btn) {
        const row = btn.closest('tr');
        row.cells[3].innerHTML = '<span class="badge bg-success px-3 py-1 rounded-pill">Completed</span>';
        row.cells[4].innerHTML = '<span class="text-success fw-bold small"><i class="fas fa-check-double me-1"></i> Verified</span>';
        if (typeof showBapsToast === 'function') showBapsToast('Task signed off and archived! ✅', 'success');
    }

    function triggerOaMemoPrompt() {
        const title = prompt("Enter Memo Headline / Subject:");
        if (!title) return;
        const desc = prompt("Enter Memo Details / Justification:");
        if (!desc) return;
        
        const container = document.getElementById('memoCardsContainer');
        const newCard = document.createElement('div');
        newCard.className = 'col-12 col-md-6 col-lg-4';
        newCard.innerHTML = `
            <div class="card border border-2 border-purple rounded-4 h-100 shadow-sm" style="border-color: #9333ea !important;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge px-3 py-1 rounded-pill text-white fw-bold" style="background: #9333ea;">NEW MEMO</span>
                        <span class="small text-muted fw-bold">Just Now</span>
                    </div>
                    <h6 class="fw-bold text-dark mb-2">${title}</h6>
                    <p class="text-muted small mb-3">${desc}</p>
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <span class="small fw-bold text-secondary"><i class="fas fa-user-tie me-1"></i> Office Assistant Desk</span>
                        <button class="btn btn-sm btn-outline-purple rounded-pill px-3 py-1 fw-bold" style="border-color: #9333ea; color: #9333ea;" onclick="acknowledgeMemo(this)">Acknowledge</button>
                    </div>
                </div>
            </div>
        `;
        container.insertBefore(newCard, container.firstChild);
        if (typeof showBapsToast === 'function') showBapsToast('Urgent Memo dispatched to Executive Board! 🚨', 'warning');
    }

    function acknowledgeMemo(btn) {
        const cardBody = btn.closest('.card-body');
        const footer = btn.parentElement;
        footer.innerHTML = '<span class="small fw-bold text-secondary"><i class="fas fa-user-shield me-1"></i> Exec Board</span><span class="badge bg-success px-3 py-1 rounded-pill">Acknowledged</span>';
        if (typeof showBapsToast === 'function') showBapsToast('Memo Acknowledged by Executive Board. ✅', 'success');
    }

    // AI Copilot 15-second Simulation Engine
    document.addEventListener('DOMContentLoaded', function() {
        const aiDirectives = [
            { title: "Audit Semester 03 Timetable", desc: "Verify Saturday slots and class conflicts", priority: "High (24 Hours)", assigner: "AI Campus Copilot" },
            { title: "Check IPDC Material Stocks", desc: "Verify course books for incoming batch", priority: "Routine", assigner: "AI Campus Copilot" },
            { title: "Review Hostel Exit Logs", desc: "Flag students out of campus past curfew", priority: "Critical (Immediate)", assigner: "AI Campus Copilot" },
            { title: "Accreditation Self-Study Report", desc: "Pre-fill criteria 4 details via AI model", priority: "Medium (3 Days)", assigner: "AI Campus Copilot" },
            { title: "Sync Student Email Directory", desc: "Import newly registered students to Google Groups", priority: "Routine", assigner: "AI Campus Copilot" }
        ];

        const aiMemos = [
            { title: "AI Energy Audit Flag", desc: "Classroom 402 AC left running without occupants. Auto-shutdown scheduled.", type: "URGENT ESCALATION", border: "border-danger", badge: "bg-danger" },
            { title: "Accreditation Doc Pre-Audit", desc: "UGC checklist missing 2 provisional degree templates. Upload required.", type: "HIGH PRIORITY", border: "border-warning", badge: "bg-warning text-dark" },
            { title: "AI Timetable Recommendation", desc: "Faculty over-scheduling detected in Wednesday labs. Suggest slot shift.", type: "AI RECOMMENDATION", border: "border-purple", badge: "bg-purple text-white" }
        ];

        const aiVisitors = [
            { name: "Dr. Arvind Patel", meet: "Bhavik Patel (HOD CSE)", purpose: "AI Research Panel Invite", checkin: "Just Now" },
            { name: "Prof. Rajeshwari Sen", meet: "Dr. Sadhu Gyaneswar Das (Dean)", purpose: "Ph.D Viva Examiner", checkin: "Just Now" },
            { name: "Mr. Tushar Mehta", meet: "General Front Desk", purpose: "Accreditation Documents Courier", checkin: "Just Now" }
        ];

        const aiQueries = [
            { name: "Rahul Patel (CSE)", type: "Marksheet Re-eval", details: "Applied for Sem 2 Grade sheet review" },
            { name: "Sneha Dave (IT)", type: "NSP Scholarship", details: "Bonafide attachment verification" },
            { name: "Hardik Shah (ME)", type: "Bus Pass Status", details: "Pass renewal payment confirmation" }
        ];

        const aiSupplies = [
            { item: "Whiteboard Dry Erase Markers", category: "Stationery", qty: "10 Packs" },
            { item: "A4 Certificate Cardstock", category: "Office Supplies", qty: "500 Sheets" },
            { item: "LAN Patch Cables (Cat6)", category: "IT Infrastructure", qty: "15 Units" }
        ];

        setInterval(function() {
            if (localStorage.getItem('ai_copilot_enabled') === 'false') {
                return;
            }
            // Select one of the 5 categories randomly
            const categoryIndex = Math.floor(Math.random() * 5);
            let toastMsg = "";

            if (categoryIndex === 0) {
                // Add Directive Task
                const task = aiDirectives[Math.floor(Math.random() * aiDirectives.length)];
                const table = document.getElementById('coordinationTaskTable')?.getElementsByTagName('tbody')[0];
                if (table) {
                    let badgeClass = 'bg-primary';
                    if (task.priority.includes('Critical')) badgeClass = 'bg-danger';
                    if (task.priority.includes('High')) badgeClass = 'bg-warning text-dark';
                    if (task.priority.includes('Medium')) badgeClass = 'bg-info text-white';

                    const newRow = table.insertRow(0);
                    newRow.style.transition = 'background-color 1s ease';
                    newRow.style.backgroundColor = '#ecfdf5';
                    setTimeout(() => newRow.style.backgroundColor = '', 2000);
                    
                    newRow.innerHTML = `
                        <td>
                            <div class="fw-bold text-dark">${task.title} <span class="badge bg-purple text-white ms-1" style="font-size:0.7rem;">AI</span></div>
                            <div class="small text-muted">${task.desc}</div>
                        </td>
                        <td class="fw-semibold">${task.assigner}</td>
                        <td><span class="badge ${badgeClass} px-3 py-1 rounded-pill">${task.priority.split(' ')[0] || task.priority}</span></td>
                        <td><span class="badge bg-info text-white px-3 py-1 rounded-pill">Assigned</span></td>
                        <td>
                            <button class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm fw-bold" onclick="completeCoordTask(this)">
                                <i class="fas fa-check me-1"></i> Sign-Off
                            </button>
                        </td>
                    `;
                    toastMsg = `AI Assistant dispatched task: "${task.title}" 🤖`;
                }
            } else if (categoryIndex === 1) {
                // Add Memo Card
                const memo = aiMemos[Math.floor(Math.random() * aiMemos.length)];
                const container = document.getElementById('memoCardsContainer');
                if (container) {
                    const newCard = document.createElement('div');
                    newCard.className = 'col-12 col-md-6 col-lg-4';
                    newCard.innerHTML = `
                        <div class="card border border-2 ${memo.border} rounded-4 h-100 shadow-sm" style="transition: background-color 1s ease; background-color: #fdf6ff;">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge ${memo.badge} px-3 py-1 rounded-pill fw-bold">${memo.type}</span>
                                    <span class="small text-muted fw-bold">Just Now</span>
                                </div>
                                <h6 class="fw-bold text-dark mb-2">${memo.title}</h6>
                                <p class="text-muted small mb-3">${memo.desc}</p>
                                <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                                    <span class="small fw-bold text-secondary"><i class="fas fa-user-tie me-1"></i> AI Assistant</span>
                                    <button class="btn btn-sm btn-outline-purple rounded-pill px-3 py-1 fw-bold" style="border-color: #9333ea; color: #9333ea;" onclick="acknowledgeMemo(this)">Acknowledge</button>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertBefore(newCard, container.firstChild);
                    setTimeout(() => {
                        const innerCard = newCard.querySelector('.card');
                        if (innerCard) innerCard.style.backgroundColor = '';
                    }, 2000);
                    toastMsg = `AI Assistant raised urgent memo: "${memo.title}" 🚨`;
                }
            } else if (categoryIndex === 2) {
                // Add Visitor Log
                const vis = aiVisitors[Math.floor(Math.random() * aiVisitors.length)];
                const table = document.getElementById('oaVisitorTable')?.getElementsByTagName('tbody')[0];
                if (table) {
                    const newRow = table.insertRow(0);
                    newRow.style.transition = 'background-color 1s ease';
                    newRow.style.backgroundColor = '#ecfeff';
                    setTimeout(() => newRow.style.backgroundColor = '', 2000);
                    newRow.innerHTML = `
                        <td class="fw-bold text-dark">${vis.name} <span class="badge bg-purple text-white ms-1" style="font-size:0.6rem;">AI</span></td>
                        <td class="fw-semibold">${vis.meet}</td>
                        <td>${vis.purpose}</td>
                        <td>${vis.checkin}</td>
                        <td><button class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm" onclick="checkoutVisitor(this)"><i class="fas fa-sign-out-alt me-1"></i> Check Out</button></td>
                    `;
                    toastMsg = `AI Assistant logged visitor check-in: ${vis.name} 👤`;
                }
            } else if (categoryIndex === 3) {
                // Add Helpdesk Query
                const query = aiQueries[Math.floor(Math.random() * aiQueries.length)];
                const table = document.getElementById('oaAidTable')?.getElementsByTagName('tbody')[0];
                if (table) {
                    const newRow = table.insertRow(0);
                    newRow.style.transition = 'background-color 1s ease';
                    newRow.style.backgroundColor = '#f0fdf4';
                    setTimeout(() => newRow.style.backgroundColor = '', 2000);
                    newRow.innerHTML = `
                        <td class="fw-bold text-dark">${query.name} <span class="badge bg-purple text-white ms-1" style="font-size:0.6rem;">AI</span></td>
                        <td>${query.type}</td>
                        <td>${query.details}</td>
                        <td><span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Pending</span></td>
                        <td><button class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm" onclick="resolveAidQuery(this)"><i class="fas fa-check me-1"></i> Resolve</button></td>
                    `;
                    toastMsg = `AI Assistant logged student helpdesk query 📞`;
                }
            } else if (categoryIndex === 4) {
                // Add Supply Requisition
                const item = aiSupplies[Math.floor(Math.random() * aiSupplies.length)];
                const table = document.getElementById('oaSuppliesTable')?.getElementsByTagName('tbody')[0];
                if (table) {
                    const newRow = table.insertRow(0);
                    newRow.style.transition = 'background-color 1s ease';
                    newRow.style.backgroundColor = '#fffbeb';
                    setTimeout(() => newRow.style.backgroundColor = '', 2000);
                    newRow.innerHTML = `
                        <td class="fw-bold text-dark">${item.item} <span class="badge bg-purple text-white ms-1" style="font-size:0.6rem;">AI</span></td>
                        <td>${item.category}</td>
                        <td><span class="badge bg-info text-white px-3 py-1 rounded-pill">${item.qty}</span></td>
                        <td><span class="text-primary fw-bold small"><i class="fas fa-clock me-1"></i> Ordered</span></td>
                    `;
                    toastMsg = `AI Assistant recommended purchase: ${item.item} 📦`;
                }
            }

            if (toastMsg && typeof showBapsToast === 'function') {
                showBapsToast(toastMsg, 'info');
            }
        }, 15000);
    });
</script>
@endif
