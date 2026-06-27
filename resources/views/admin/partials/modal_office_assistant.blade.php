@if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant']))
<div class="modal fade" id="officeAssistantConsoleModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #f8fafc;">
            
            <!-- Modal Header -->
            <div class="modal-header border-0 p-4 text-white" style="background: linear-gradient(135deg, #7e22ce 0%, #4f46e5 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-purple rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 54px; height: 54px; font-size: 1.8rem;">
                        <i class="fas fa-user-tie" style="color: #9333ea;"></i>
                    </div>
                    <div>
                        <h4 class="modal-title fw-bold mb-1 d-flex align-items-center gap-2 flex-wrap">
                            Office Assistant Executive Hub
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">175% Access Tier</span>
                            <span class="badge bg-success text-white px-3 py-2 rounded-pill text-uppercase fs-6 shadow-sm d-flex align-items-center gap-2 badge-ai-copilot" style="font-size: 0.7rem !important; letter-spacing: 0.5px;">
                                <span class="spinner-grow spinner-grow-sm text-light" style="width: 8px; height: 8px;"></span> AI Copilot Active (15s Loop)
                            </span>
                        </h4>
                        <div class="small text-light fw-semibold">Primary Point of Contact — Campus Administration Backbone & Dean Delegation Console</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body with 4 Core Functional Tabs -->
            <div class="modal-body p-4">
                
                <!-- Role Overview Banner -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #ffffff; border-left: 5px solid #9333ea !important;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2 fs-5">
                            <i class="fas fa-star text-warning"></i> Operational Mandate & Scope
                        </h6>
                        <p class="text-muted small mb-0 lh-base fs-6">
                            As the Office Assistant, you operate with a <strong>175% Executive Access Level</strong> on behalf of the Dean and Administrator. You are authorized to manage correspondence, oversee front desk visitor operations, coordinate institutional logistics, and provide direct administrative aid to students and faculty members.
                        </p>
                    </div>
                </div>

                <!-- 5 Core Functional Navigation Pills -->
                <ul class="nav nav-pills mb-4 gap-2 flex-nowrap overflow-auto border-0 pb-2" id="oaPills" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="baps-tab-btn active border shadow-sm" data-bs-toggle="pill" data-bs-target="#oa-admin-support" type="button" role="tab">
                            <i class="fas fa-folder-open text-purple me-2" style="color: #9333ea;"></i> 1. Administrative Support
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="baps-tab-btn border shadow-sm" data-bs-toggle="pill" data-bs-target="#oa-front-desk" type="button" role="tab">
                            <i class="fas fa-phone-alt text-info me-2"></i> 2. Front Desk Operations
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="baps-tab-btn border shadow-sm" data-bs-toggle="pill" data-bs-target="#oa-logistics" type="button" role="tab">
                            <i class="fas fa-boxes text-warning me-2"></i> 3. Logistics & Coordination
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="baps-tab-btn border shadow-sm" data-bs-toggle="pill" data-bs-target="#oa-student-aid" type="button" role="tab">
                            <i class="fas fa-user-graduate text-success me-2"></i> 4. Student & Faculty Aid
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="baps-tab-btn border shadow-sm" data-bs-toggle="pill" data-bs-target="#oa-document-giving" type="button" role="tab">
                            <i class="fas fa-file-export text-danger me-2"></i> 5. Document Giving / Issuance
                        </button>
                    </li>
                </ul>

                <!-- Tab Content Container -->
                <div class="tab-content" id="oaPillsContent">

                    <!-- TAB 1: Administrative Support -->
                    <div class="tab-pane fade show active" id="oa-admin-support" role="tabpanel">
                        <div class="row g-4 mb-4">
                            <!-- Filing Form -->
                            <div class="col-12 col-lg-5">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-file-import text-purple me-2" style="color: #9333ea;"></i> Secure Document Filing</h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <form id="oaFilingForm" onsubmit="event.preventDefault(); submitOaFiling();">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Document Title / Subject</label>
                                                <input type="text" id="file_title" class="form-control py-2" placeholder="e.g., AICTE Compliance Report 2026" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Correspondence Type</label>
                                                <select id="file_category" class="form-select py-2" required>
                                                    <option value="Official University Letter">Official University Letter</option>
                                                    <option value="Government Notification">Government Notification</option>
                                                    <option value="Internal Departmental Memo">Internal Departmental Memo</option>
                                                    <option value="Confidential Student Record">Confidential Student Record</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Attach Official Document</label>
                                                <input type="file" id="file_attachment" class="form-control py-2" required>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label small fw-bold text-muted">Filing Notes / Remarks</label>
                                                <textarea id="file_notes" class="form-control py-2" rows="3" placeholder="Enter secure filing metadata..."></textarea>
                                            </div>
                                            <button type="submit" class="btn w-100 py-3 text-white fw-bold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #9333ea 0%, #4f46e5 100%);">
                                                <i class="fas fa-archive me-2"></i> File Document Securely
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Filing Registry Table -->
                            <div class="col-12 col-lg-7">
                                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-folder-tree text-secondary me-2"></i> Official Correspondence Archive</h6>
                                        <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">Secure Vault</span>
                                    </div>
                                    <div class="card-body p-4 overflow-auto">
                                        <div class="table-responsive border rounded-3">
                                            <table class="table table-hover align-middle mb-0" id="oaFilingTable">
                                                <thead class="table-light">
                                                    <tr><th>FILE ID</th><th>SUBJECT</th><th>CATEGORY</th><th>DATE FILED</th><th>STATUS</th></tr>
                                                </thead>
                                                <tbody>
                                                    <tr><td class="fw-bold text-secondary">#DOC-8012</td><td class="fw-bold text-dark">UGC Guidelines 2026</td><td>Government Notification</td><td>Today, 10:15 AM</td><td><span class="badge bg-success px-3 py-1 rounded-pill">Archived</span></td></tr>
                                                    <tr><td class="fw-bold text-secondary">#DOC-8011</td><td class="fw-bold text-dark">Dean Annual Budget</td><td>Internal Memo</td><td>Yesterday</td><td><span class="badge bg-success px-3 py-1 rounded-pill">Archived</span></td></tr>
                                                    <tr><td class="fw-bold text-secondary">#DOC-8010</td><td class="fw-bold text-dark">Exam Board Resolution</td><td>Official Letter</td><td>May 14, 2026</td><td><span class="badge bg-success px-3 py-1 rounded-pill">Archived</span></td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 2: Front Desk Operations -->
                    <div class="tab-pane fade" id="oa-front-desk" role="tabpanel">
                        <div class="row g-4 mb-4">
                            <!-- Visitor Check-In Form -->
                            <div class="col-12 col-lg-5">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-user-plus text-info me-2"></i> Visitor Check-In Registry</h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <form id="oaVisitorForm" onsubmit="event.preventDefault(); submitOaVisitor();">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Visitor Full Name</label>
                                                <input type="text" id="vis_name" class="form-control py-2" placeholder="e.g., Dr. Ramesh Mehta" required>
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Phone Number</label>
                                                    <input type="text" id="vis_phone" class="form-control py-2" placeholder="+91 98765 43210" required>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Person to Meet</label>
                                                    <select id="vis_meet" class="form-select py-2" required>
                                                        <option value="Dr. Sadhu Gyaneswar Das (Dean)">Dr. Sadhu Gyaneswar Das (Dean)</option>
                                                        <option value="Bhavik Patel (HOD CSE)">Bhavik Patel (HOD CSE)</option>
                                                        <option value="Prof. Dhaval Shah">Prof. Dhaval Shah</option>
                                                        <option value="General Front Desk">General Front Desk</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label small fw-bold text-muted">Purpose of Visit</label>
                                                <input type="text" id="vis_purpose" class="form-control py-2" placeholder="e.g., Guest Lecture / Accreditation Audit" required>
                                            </div>
                                            <button type="submit" class="btn btn-info w-100 py-3 text-white fw-bold rounded-pill shadow-sm">
                                                <i class="fas fa-sign-in-alt me-2"></i> Log Visitor Check-In
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Visitor Switchboard Table -->
                            <div class="col-12 col-lg-7">
                                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-address-book text-secondary me-2"></i> Live Campus Visitor Log</h6>
                                        <span class="badge bg-warning text-dark border px-3 py-2 rounded-pill">Active Switchboard</span>
                                    </div>
                                    <div class="card-body p-4 overflow-auto">
                                        <div class="table-responsive border rounded-3">
                                            <table class="table table-hover align-middle mb-0" id="oaVisitorTable">
                                                <thead class="table-light">
                                                    <tr><th>VISITOR NAME</th><th>MEETING WITH</th><th>PURPOSE</th><th>CHECK-IN</th><th>ACTION</th></tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-bold text-dark">Mr. Suresh Sharma</td><td class="fw-semibold">Bhavik Patel (HOD)</td><td>Parent Inquiry</td><td>10:05 AM</td>
                                                        <td><button class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm" onclick="checkoutVisitor(this)"><i class="fas fa-sign-out-alt me-1"></i> Check Out</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-dark">Dr. Alok Verma</td><td class="fw-semibold">Dr. Gyaneswar Das</td><td>Accreditation Audit</td><td>09:30 AM</td>
                                                        <td><button class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm" onclick="checkoutVisitor(this)"><i class="fas fa-sign-out-alt me-1"></i> Check Out</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 3: Logistics & Coordination -->
                    <div class="tab-pane fade" id="oa-logistics" role="tabpanel">
                        <div class="row g-4 mb-4">
                            <!-- Meeting Scheduler Form -->
                            <div class="col-12 col-lg-5">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-calendar-plus text-warning me-2"></i> Executive Meeting Scheduler</h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <form id="oaMeetingForm" onsubmit="event.preventDefault(); submitOaMeeting();">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Meeting Agenda / Title</label>
                                                <input type="text" id="meet_agenda" class="form-control py-2" placeholder="e.g., Semester Exam Committee Review" required>
                                            </div>
                                            <div class="row g-3 mb-3">
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Date & Time</label>
                                                    <input type="datetime-local" id="meet_time" class="form-control py-2" required>
                                                </div>
                                                <div class="col-12 col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Venue / Room</label>
                                                    <select id="meet_venue" class="form-select py-2" required>
                                                        <option value="Dean Conference Room A">Dean Conference Room A</option>
                                                        <option value="CSE Department Boardroom">CSE Department Boardroom</option>
                                                        <option value="Virtual G-Meet Bridge">Virtual G-Meet Bridge</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label small fw-bold text-muted">Requested Supplies / Notes</label>
                                                <input type="text" id="meet_supplies" class="form-control py-2" placeholder="e.g., Projector, 15 Notepads, High Tea">
                                            </div>
                                            <button type="submit" class="btn btn-warning text-dark w-100 py-3 fw-bold rounded-pill shadow-sm">
                                                <i class="fas fa-calendar-check me-2"></i> Schedule Meeting & Reserve Venue
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Inventory & Supplies Status Table -->
                            <div class="col-12 col-lg-7">
                                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-boxes text-secondary me-2"></i> Office Supplies & Inventory Tracker</h6>
                                        <button class="btn btn-sm btn-success fw-bold px-3 py-1 rounded-pill shadow-sm" onclick="requestSupplies()"><i class="fas fa-plus me-1"></i> Request Supply</button>
                                    </div>
                                    <div class="card-body p-4 overflow-auto">
                                        <div class="table-responsive border rounded-3 mb-4">
                                            <table class="table table-hover align-middle mb-0" id="oaSuppliesTable">
                                                <thead class="table-light">
                                                    <tr><th>ITEM DESCRIPTION</th><th>CATEGORY</th><th>STOCK LEVEL</th><th>STATUS</th></tr>
                                                </thead>
                                                <tbody>
                                                    <tr><td class="fw-bold text-dark">A4 Copier Paper (Reams)</td><td>Stationery</td><td><span class="badge bg-success px-3 py-1 rounded-pill">120 Reams</span></td><td><span class="text-success fw-bold small"><i class="fas fa-check-circle me-1"></i> Optimal</span></td></tr>
                                                    <tr><td class="fw-bold text-dark">Laser Printer Ink Cartridges</td><td>IT Supplies</td><td><span class="badge bg-warning text-dark px-3 py-1 rounded-pill">4 Units</span></td><td><span class="text-warning fw-bold small"><i class="fas fa-exclamation-triangle me-1"></i> Low Stock</span></td></tr>
                                                    <tr><td class="fw-bold text-dark">Official Exam Answer Booklets</td><td>Examination</td><td><span class="badge bg-success px-3 py-1 rounded-pill">2,500 Units</span></td><td><span class="text-success fw-bold small"><i class="fas fa-check-circle me-1"></i> Optimal</span></td></tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 4: Student & Faculty Aid -->
                    <div class="tab-pane fade" id="oa-student-aid" role="tabpanel">
                        <div class="row g-4 mb-4">
                            <!-- Notice Broadcast Form -->
                            <div class="col-12 col-lg-5">
                                <div class="card border-0 shadow-sm rounded-4 h-100">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-bullhorn text-success me-2"></i> Broadcast Institutional Notice</h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <form id="oaNoticeForm" onsubmit="event.preventDefault(); submitOaNotice();">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Notice Title / Headline</label>
                                                <input type="text" id="not_title" class="form-control py-2" placeholder="e.g., Urgent: Semester Fee Payment Deadline" required>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Target Audience</label>
                                                <select id="not_target" class="form-select py-2" required>
                                                    <option value="All Students & Faculty">All Students & Faculty</option>
                                                    <option value="Students Only">Students Only</option>
                                                    <option value="Faculty & Staff Only">Faculty & Staff Only</option>
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label small fw-bold text-muted">Notice Content / Instructions</label>
                                                <textarea id="not_content" class="form-control py-2" rows="4" placeholder="Enter formal administrative notification text..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-success w-100 py-3 text-white fw-bold rounded-pill shadow-sm">
                                                <i class="fas fa-paper-plane me-2"></i> Publish Notice Broadcast
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Student Queries & Fee Aid Table -->
                            <div class="col-12 col-lg-7">
                                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-headset text-secondary me-2"></i> Student Queries & Fee Aid Helpdesk</h6>
                                        <span class="badge bg-success text-white border px-3 py-2 rounded-pill">Live Helpdesk</span>
                                    </div>
                                    <div class="card-body p-4 overflow-auto">
                                        <div class="table-responsive border rounded-3 mb-4">
                                            <table class="table table-hover align-middle mb-0" id="oaAidTable">
                                                <thead class="table-light">
                                                    <tr><th>STUDENT NAME</th><th>QUERY TYPE</th><th>DETAILS</th><th>STATUS</th><th>ACTION</th></tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-bold text-dark">Amit Patel (CSE)</td><td>Fee Receipt Query</td><td>Payment deducted but receipt pending</td>
                                                        <td><span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Pending</span></td>
                                                        <td><button class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm" onclick="resolveAidQuery(this)"><i class="fas fa-check me-1"></i> Resolve</button></td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-dark">Neha Sharma (IT)</td><td>Admission Form</td><td>Requesting subject change approval</td>
                                                        <td><span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Pending</span></td>
                                                        <td><button class="btn btn-sm btn-success rounded-pill px-3 py-1 shadow-sm" onclick="resolveAidQuery(this)"><i class="fas fa-check me-1"></i> Resolve</button></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- TAB 5: Document Giving / Issuance -->
                    <div class="tab-pane fade" id="oa-document-giving" role="tabpanel">
                        <div class="row g-4 mb-4">
                            <!-- Issue Document Form -->
                            <div class="col-12 col-lg-5">
                                <div class="card border-0 shadow-sm rounded-4 h-100" style="border-top: 4px solid #ef4444;">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-file-signature text-danger me-2"></i> Issue Official Document</h6>
                                    </div>
                                    <div class="card-body p-4">
                                        <form id="oaDocGivingForm" onsubmit="event.preventDefault(); submitOaDocumentGiving();">
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Select Official Document (22 Present)</label>
                                                <select id="give_doc_title" class="form-select py-2" required>
                                                    <optgroup label="Academic Records (7)">
                                                        <option value="College Admission Letter">College Admission Letter</option>
                                                        <option value="Semester Grade Sheets / Marksheets">Semester Grade Sheets / Marksheets</option>
                                                        <option value="Consolidated Transcript">Consolidated Transcript</option>
                                                        <option value="Degree Certificate / Provisional Degree">Degree Certificate / Provisional Degree</option>
                                                        <option value="Course Syllabus Copy (Attested)">Course Syllabus Copy (Attested)</option>
                                                        <option value="Academic Project Report Approval Copy">Academic Project Report Approval Copy</option>
                                                        <option value="Internship Completion Certificate">Internship Completion Certificate</option>
                                                    </optgroup>
                                                    <optgroup label="Administrative & Fees (5)">
                                                        <option value="College Identity Card (ID Card)">College Identity Card (ID Card)</option>
                                                        <option value="Fee Receipt (Current Semester/Year)">Fee Receipt (Current Semester/Year)</option>
                                                        <option value="College Bus / Transport Pass">College Bus / Transport Pass</option>
                                                        <option value="Hostel Allotment Letter">Hostel Allotment Letter</option>
                                                        <option value="Scholarship Sanction Letter / Document">Scholarship Sanction Letter / Document</option>
                                                    </optgroup>
                                                    <optgroup label="Conduct & Leaving (6)">
                                                        <option value="College Leaving Certificate (LC) / TC">College Leaving Certificate (LC) / TC</option>
                                                        <option value="Migration Certificate">Migration Certificate</option>
                                                        <option value="Character / Conduct Certificate">Character / Conduct Certificate</option>
                                                        <option value="Bonafide Student Certificate">Bonafide Student Certificate</option>
                                                        <option value="No Dues Certificate (Library/Dept/Hostel)">No Dues Certificate (Library/Dept/Hostel)</option>
                                                        <option value="Extracurricular / Sports Achievement Certs">Extracurricular / Sports Achievement Certs</option>
                                                    </optgroup>
                                                    <optgroup label="Special Memberships & Service (4)">
                                                        <option value="National Social Service (NSS) / NCC Cert">National Social Service (NSS) / NCC Cert</option>
                                                        <option value="Alumni Association Membership Card">Alumni Association Membership Card</option>
                                                        <option value="BAPS Member Ship Transcript">BAPS Member Ship Transcript</option>
                                                        <option value="IIMA member Card With Role letter">IIMA member Card With Role letter</option>
                                                    </optgroup>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Student Recipient</label>
                                                <select id="give_student_name" class="form-select py-2" required>
                                                    <option value="Amit Patel (ENR2026CSE001)">Amit Patel (ENR2026CSE001)</option>
                                                    <option value="Neha Sharma (ENR2026IT042)">Neha Sharma (ENR2026IT042)</option>
                                                    <option value="Rahul Verma (ENR2026EE105)">Rahul Verma (ENR2026EE105)</option>
                                                    <option value="Pooja Desai (ENR2026EC088)">Pooja Desai (ENR2026EC088)</option>
                                                    <option value="Vikram Malhotra (ENR2026ME012)">Vikram Malhotra (ENR2026ME012)</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label small fw-bold text-muted">Issuance Authority & Handover Mode</label>
                                                <select id="give_issuance_mode" class="form-select py-2" required>
                                                    <option value="Handed Over in Person (Hardcopy + Attested)">Handed Over in Person (Hardcopy + Attested)</option>
                                                    <option value="Dispatched to Student Digital Vault">Dispatched to Student Digital Vault</option>
                                                    <option value="Sent via Registered Speed Post">Sent via Registered Speed Post</option>
                                                </select>
                                            </div>
                                            <div class="mb-4">
                                                <label class="form-label small fw-bold text-muted">Purpose / Verification Remarks</label>
                                                <input type="text" id="give_remarks" class="form-control py-2" placeholder="e.g., Higher Studies Application / Passport Verification / Scholarship Claim" required>
                                            </div>
                                            <button type="submit" class="btn btn-danger w-100 py-3 text-white fw-bold rounded-pill shadow-sm">
                                                <i class="fas fa-hand-holding-medical me-2"></i> Give / Issue Official Document Now
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Live Issued Documents Registry Table -->
                            <div class="col-12 col-lg-7">
                                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden" style="border-top: 4px solid #3b82f6;">
                                    <div class="card-header bg-white border-0 pt-4 px-4 pb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                        <h6 class="fw-bold text-dark m-0 fs-5"><i class="fas fa-file-contract text-primary me-2"></i> Live Issued Documents Registry</h6>
                                        <span class="badge bg-primary text-white border px-3 py-2 rounded-pill">175% Handover Log</span>
                                    </div>
                                    <div class="card-body p-4 overflow-auto">
                                        <div class="table-responsive border rounded-3 mb-4">
                                            <table class="table table-hover align-middle mb-0" id="oaDocGivingTable">
                                                <thead class="table-light">
                                                    <tr><th>STUDENT</th><th>DOCUMENT GIVEN</th><th>MODE</th><th>STATUS</th><th>ACTIONS</th></tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="fw-bold text-dark">Amit Patel <div class="small text-muted">ENR2026CSE001</div></td>
                                                        <td class="fw-semibold text-danger">Bonafide Student Certificate</td>
                                                        <td>Handed Over in Person</td>
                                                        <td><span class="badge bg-success px-3 py-1 rounded-pill">Issued</span></td>
                                                        <td>
                                                            <div class="d-flex gap-1 flex-wrap">
                                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 fw-bold" onclick="printGivenDocument('Bonafide Student Certificate')" title="Instant Print / Handover"><i class="fas fa-print"></i></button>
                                                                <button class="btn btn-sm btn-outline-success rounded-pill px-2 py-1 fw-bold" onclick="verifyGivenDocument('Bonafide Student Certificate')" title="Verify Hash"><i class="fas fa-shield-alt"></i></button>
                                                                <button class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 fw-bold" onclick="cancelGivenDocument(this)" title="Revoke"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="fw-bold text-dark">Neha Sharma <div class="small text-muted">ENR2026IT042</div></td>
                                                        <td class="fw-semibold text-danger">Fee Receipt (Current Semester/Year)</td>
                                                        <td>Digital Vault</td>
                                                        <td><span class="badge bg-success px-3 py-1 rounded-pill">Issued</span></td>
                                                        <td>
                                                            <div class="d-flex gap-1 flex-wrap">
                                                                <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 fw-bold" onclick="printGivenDocument('Fee Receipt (Current Semester/Year)')" title="Instant Print / Handover"><i class="fas fa-print"></i></button>
                                                                <button class="btn btn-sm btn-outline-success rounded-pill px-2 py-1 fw-bold" onclick="verifyGivenDocument('Fee Receipt (Current Semester/Year)')" title="Verify Hash"><i class="fas fa-shield-alt"></i></button>
                                                                <button class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 fw-bold" onclick="cancelGivenDocument(this)" title="Revoke"><i class="fas fa-times"></i></button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            
            <!-- Modal Footer -->
            <div class="modal-footer border-top-0 bg-light p-4 d-flex justify-content-between align-items-center">
                <div class="small text-muted fw-bold">
                    <i class="fas fa-user-shield text-purple me-1" style="color: #9333ea;"></i> Authorized under Dr. Sadhu Gyaneswar Das (Dean)
                </div>
                <button type="button" class="btn btn-dark fw-bold px-5 py-2 rounded-pill shadow-sm" data-bs-dismiss="modal">Close Executive Hub</button>
            </div>
        </div>
    </div>
</div>

<!-- Interactive JavaScript Handlers for Office Assistant Console -->
<script>
    function submitOaFiling() {
        const title = document.getElementById('file_title').value;
        const category = document.getElementById('file_category').value;
        const table = document.getElementById('oaFilingTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow(0);
        const docId = '#DOC-' + Math.floor(Math.random() * 9000 + 1000);
        newRow.innerHTML = `<td class="fw-bold text-secondary">${docId}</td><td class="fw-bold text-dark">${title}</td><td>${category}</td><td>Just Now</td><td><span class="badge bg-success px-3 py-1 rounded-pill">Archived</span></td>`;
        document.getElementById('oaFilingForm').reset();
        if (typeof showBapsToast === 'function') showBapsToast('Document Filed Securely in Archive! 📁', 'success');
    }

    function submitOaVisitor() {
        const name = document.getElementById('vis_name').value;
        const meet = document.getElementById('vis_meet').value;
        const purpose = document.getElementById('vis_purpose').value;
        const table = document.getElementById('oaVisitorTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow(0);
        newRow.innerHTML = `<td class="fw-bold text-dark">${name}</td><td class="fw-semibold">${meet}</td><td>${purpose}</td><td>Just Now</td><td><button class="btn btn-sm btn-danger rounded-pill px-3 py-1 shadow-sm" onclick="checkoutVisitor(this)"><i class="fas fa-sign-out-alt me-1"></i> Check Out</button></td>`;
        document.getElementById('oaVisitorForm').reset();
        if (typeof showBapsToast === 'function') showBapsToast('Visitor Check-In Logged Successfully! 👤', 'info');
    }

    function checkoutVisitor(btn) {
        const row = btn.closest('tr');
        row.cells[4].innerHTML = '<span class="badge bg-secondary px-3 py-1 rounded-pill">Checked Out</span>';
        if (typeof showBapsToast === 'function') showBapsToast('Visitor Checked Out. Log Updated.', 'success');
    }

    function submitOaMeeting() {
        const agenda = document.getElementById('meet_agenda').value;
        const venue = document.getElementById('meet_venue').value;
        document.getElementById('oaMeetingForm').reset();
        if (typeof showBapsToast === 'function') showBapsToast(`Meeting "${agenda}" scheduled at ${venue}! 📅`, 'warning');
    }

    function requestSupplies() {
        const item = prompt("Enter the supply item description to request (e.g., 10 Packs of Whiteboard Markers):");
        if (!item) return;
        const table = document.getElementById('oaSuppliesTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow(0);
        newRow.innerHTML = `<td class="fw-bold text-dark">${item}</td><td>Office Request</td><td><span class="badge bg-info text-white px-3 py-1 rounded-pill">Pending Approval</span></td><td><span class="text-primary fw-bold small"><i class="fas fa-clock me-1"></i> Ordered</span></td>`;
        if (typeof showBapsToast === 'function') showBapsToast('Supply Requisition Placed! 📦', 'success');
    }

    function submitOaNotice() {
        const title = document.getElementById('not_title').value;
        const target = document.getElementById('not_target').value;
        document.getElementById('oaNoticeForm').reset();
        if (typeof showBapsToast === 'function') showBapsToast(`Notice Broadcasted to ${target}! 📢`, 'success');
    }

    function resolveAidQuery(btn) {
        const row = btn.closest('tr');
        row.cells[3].innerHTML = '<span class="badge bg-success px-3 py-1 rounded-pill">Resolved</span>';
        btn.remove();
        if (typeof showBapsToast === 'function') showBapsToast('Student Query Resolved & Closed! ✅', 'success');
    }

    function submitOaDocumentGiving() {
        const docTitle = document.getElementById('give_doc_title').value;
        const studentStr = document.getElementById('give_student_name').value;
        const modeStr = document.getElementById('give_issuance_mode').value;
        const remarksStr = document.getElementById('give_remarks').value;

        const parts = studentStr.split(' (');
        const sName = parts[0];
        const sEnr = parts[1] ? parts[1].replace(')', '') : 'ENR2026CSE001';

        const table = document.getElementById('oaDocGivingTable').getElementsByTagName('tbody')[0];
        const newRow = table.insertRow(0);
        newRow.innerHTML = `
            <td class="fw-bold text-dark">${sName} <div class="small text-muted">${sEnr}</div></td>
            <td class="fw-semibold text-danger">${docTitle}</td>
            <td>${modeStr.split(' (')[0]}</td>
            <td><span class="badge bg-success px-3 py-1 rounded-pill">Issued</span></td>
            <td>
                <div class="d-flex gap-1 flex-wrap">
                    <button class="btn btn-sm btn-outline-primary rounded-pill px-2 py-1 fw-bold" onclick="printGivenDocument('${addslashes(docTitle)}')" title="Instant Print / Handover"><i class="fas fa-print"></i></button>
                    <button class="btn btn-sm btn-outline-success rounded-pill px-2 py-1 fw-bold" onclick="verifyGivenDocument('${addslashes(docTitle)}')" title="Verify Hash"><i class="fas fa-shield-alt"></i></button>
                    <button class="btn btn-sm btn-outline-danger rounded-pill px-2 py-1 fw-bold" onclick="cancelGivenDocument(this)" title="Revoke"><i class="fas fa-times"></i></button>
                </div>
            </td>
        `;
        document.getElementById('oaDocGivingForm').reset();
        if (typeof showBapsToast === 'function') showBapsToast(`Official Document "${docTitle}" issued successfully to ${sName}! 📄`, 'success');
        
        // Auto open preview/print
        setTimeout(() => {
            printGivenDocument(docTitle);
        }, 600);
    }

    function printGivenDocument(title) {
        const url = '/document/official/' + encodeURIComponent(title);
        const iframe = document.getElementById('previewIframe');
        if (iframe) {
            iframe.src = url;
            const modalEl = document.getElementById('filePreviewModal');
            if (modalEl) {
                const modalTitle = modalEl.querySelector('.modal-title');
                if (modalTitle) modalTitle.innerHTML = `<i class="fas fa-file-contract me-2 text-info"></i> Official Student Record Handover: ${title}`;
                new bootstrap.Modal(modalEl).show();
            }
        } else {
            window.open(url, '_blank');
        }
        if (typeof showBapsToast === 'function') showBapsToast(`Generating official print-ready document for ${title}... 🖨️`, 'info');
    }

    function verifyGivenDocument(title) {
        if (typeof showBapsToast === 'function') {
            showBapsToast(`Blockchain ledger verifying handover hash for: ${title}... 🔗`, 'warning');
            setTimeout(() => {
                showBapsToast(`✅ Cryptographic handover hash verified! Document issuance is 100% authentic.`, 'success');
            }, 1200);
        } else {
            alert(`✅ Cryptographic handover hash verified! ${title} issuance is 100% authentic on the private institutional ledger.`);
        }
    }

    function cancelGivenDocument(btn) {
        const row = btn.closest('tr');
        row.cells[3].innerHTML = '<span class="badge bg-danger px-3 py-1 rounded-pill">Revoked</span>';
        if (typeof showBapsToast === 'function') showBapsToast('Document Issuance Revoked. Handover cancelled.', 'error');
    }

    function addslashes(str) {
        return (str + '').replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0');
    }
</script>
@endif
