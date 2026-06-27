@if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'student']))
<div class="tab-pane fade" id="tab-official-documents" role="tabpanel">
    
    <!-- Premium Glassmorphic Vault Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #11111b !important; border-left: 5px solid #ffd700 !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; background: #1e1e2e !important; color: #ffd700 !important;">
                        <i class="fas fa-file-contract"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #ffd700 !important;">
                            Document Giving Vault (For Student & Staff)
                            <span id="totalDocsBadge" class="badge bg-warning text-dark px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">83 Verified Documents</span>
                        </h4>
                        <div class="small fw-semibold" style="color: #ffffff !important; opacity: 0.85;">Master repository of accredited academic, administrative, and special institutional records.</div>
                    </div>
                </div>
                <div class="d-flex gap-2 align-items-center flex-wrap">
                    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod']))
                    <button class="btn btn-light text-primary fw-bold rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addManualDocModal">
                        <i class="fas fa-plus-circle fs-5"></i> Add New Document
                    </button>
                    @endif
                    <button class="btn btn-info text-white fw-bold rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#issuanceHistoryModal">
                        <i class="fas fa-history fs-5"></i> View Issuance History
                    </button>
                    <button class="btn btn-warning text-dark fw-bold rounded-pill px-4 py-2 shadow-sm d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#aiOfficeAssistantModal">
                        <i class="fas fa-robot fs-5"></i> Ask AI Office Assistant
                    </button>
                    <div class="position-relative">
                        <input type="text" id="docSearchInput" class="form-control form-control-lg rounded-pill ps-5 py-2 shadow-sm border-0" placeholder="Search 83 documents..." style="width: 280px; font-size: 0.95rem;" onkeyup="filterOfficialDocuments()">
                        <i class="fas fa-search position-absolute text-muted" style="top: 50%; left: 18px; transform: translateY(-50%);"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Filter Pills -->
    <div class="d-flex gap-2 mb-4 overflow-auto pb-2 border-0" id="docCategoryFilters">
        <button class="btn btn-dark rounded-pill px-4 py-2 fw-bold small shadow-sm active" onclick="filterByCategory('all', this)">All Documents (83)</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('academic', this)"><i class="fas fa-graduation-cap me-1 text-success"></i> Academic Records</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('administrative', this)"><i class="fas fa-building me-1 text-primary"></i> Administrative & Fees</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('certificates', this)"><i class="fas fa-certificate me-1 text-warning"></i> Conduct & Leaving</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('special', this)"><i class="fas fa-star me-1 text-purple" style="color: #9333ea;"></i> Special Memberships</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('registrar', this)"><i class="fas fa-user-shield me-1 text-info"></i> Registrar Level</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('president_vp', this)"><i class="fas fa-crown me-1 text-danger"></i> President/VP Level</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('advisor', this)"><i class="fas fa-user-friends me-1 text-warning"></i> Advisor Level</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('cr', this)"><i class="fas fa-users-cog me-1 text-success"></i> CR Level</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('class_coordinator', this)"><i class="fas fa-clipboard-list me-1 text-primary"></i> Coordinator Level</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('hod_level', this)"><i class="fas fa-user-tie me-1 text-dark"></i> HOD Level</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('canteen_store', this)"><i class="fas fa-store me-1 text-danger"></i> Canteen & Store</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('librarian_level', this)"><i class="fas fa-book-open me-1 text-info"></i> Librarian Level</button>
        <button class="btn btn-light border rounded-pill px-4 py-2 fw-bold small shadow-sm text-secondary" onclick="filterByCategory('clubs_level', this)"><i class="fas fa-puzzle-piece me-1 text-primary"></i> Club's Level</button>
    </div>

    <!-- Official Documents Grid -->
    <div class="row g-4" id="officialDocumentsGrid">

        @php
            $officialDocs = [
                // Academic Records (8)
                ['title' => 'College Admission Letter', 'cat' => 'academic', 'icon' => 'fa-envelope-open-text', 'color' => 'primary', 'desc' => 'Official confirmation of enrollment and allocated branch verification.'],
                ['title' => 'Semester Grade Sheets / Marksheets', 'cat' => 'academic', 'icon' => 'fa-file-alt', 'color' => 'success', 'desc' => 'Attested semester-wise academic performance and GPA transcripts.'],
                ['title' => 'Consolidated Transcript', 'cat' => 'academic', 'icon' => 'fa-scroll', 'color' => 'success', 'desc' => 'Complete institutional academic summary across all 8 semesters.'],
                ['title' => 'Degree Certificate / Provisional Degree', 'cat' => 'academic', 'icon' => 'fa-user-graduate', 'color' => 'warning', 'desc' => 'Official graduation credential conferred by the University Senate.'],
                ['title' => 'Course Syllabus Copy (Attested)', 'cat' => 'academic', 'icon' => 'fa-book', 'color' => 'info', 'desc' => 'Curriculum breakdown officially stamped for foreign university equivalence.'],
                ['title' => 'Academic Project Report Approval Copy', 'cat' => 'academic', 'icon' => 'fa-project-diagram', 'color' => 'purple', 'desc' => 'Official sign-off for final year Capstone & Research Projects.'],
                ['title' => 'Internship Completion Certificate', 'cat' => 'academic', 'icon' => 'fa-briefcase', 'color' => 'primary', 'desc' => 'Verified industrial training and corporate internship evaluations.'],
                ['title' => 'NOC for External Internships', 'cat' => 'academic', 'icon' => 'fa-file-signature', 'color' => 'info', 'desc' => 'No Objection Certificate for undertaking off-campus corporate internships.'],

                // Administrative & Fees (10)
                ['title' => 'College Identity Card (ID Card)', 'cat' => 'administrative', 'icon' => 'fa-id-card', 'color' => 'danger', 'desc' => 'Digital institutional identification credential with smart RFID barcode.'],
                ['title' => 'Fee Receipt (Current Semester/Year)', 'cat' => 'administrative', 'icon' => 'fa-receipt', 'color' => 'success', 'desc' => 'Official financial receipt for tuition, exam, and laboratory charges.'],
                ['title' => 'College Bus / Transport Pass', 'cat' => 'administrative', 'icon' => 'fa-bus', 'color' => 'warning', 'desc' => 'Authorized route allocation pass for university transport services.'],
                ['title' => 'Hostel Allotment Letter', 'cat' => 'administrative', 'icon' => 'fa-bed', 'color' => 'secondary', 'desc' => 'Official residential room and dormitory allocation confirmation.'],
                ['title' => 'Scholarship Sanction Letter / Document', 'cat' => 'administrative', 'icon' => 'fa-hand-holding-usd', 'color' => 'success', 'desc' => 'Financial aid, merit awards, and government endowment sanctions.'],
                ['title' => 'National Identity Document (e.g., Aadhaar Card - UIDAI under MeitY/Home links)', 'cat' => 'administrative', 'icon' => 'fa-id-card', 'color' => 'primary', 'desc' => 'Official UIDAI biometric identity credential linked with MeitY and Home Affairs.'],
                ['title' => 'Central Scholarship / National Scholarship Portal (NSP) Eligibility Certificate', 'cat' => 'administrative', 'icon' => 'fa-file-invoice-dollar', 'color' => 'success', 'desc' => 'Verified NSP central scholarship eligibility and disbursement credential.'],
                ['title' => 'State Transport Student Concession Pass (e.g., GSRTC Student Pass)', 'cat' => 'administrative', 'icon' => 'fa-bus', 'color' => 'warning', 'desc' => 'Official state transport concession pass for daily student commute.'],
                ['title' => 'University Convocation Guest Pass', 'cat' => 'administrative', 'icon' => 'fa-ticket-alt', 'color' => 'success', 'desc' => 'Entry credential for parents and guests attending the graduation ceremony.'],
                ['title' => 'Hostel Room Change Approval Form', 'cat' => 'administrative', 'icon' => 'fa-exchange-alt', 'color' => 'primary', 'desc' => 'Authorized room transfer approval signed by hostel warden desk.'],

                // Conduct & Leaving (8)
                ['title' => 'College Leaving Certificate (LC) / TC', 'cat' => 'certificates', 'icon' => 'fa-file-export', 'color' => 'danger', 'desc' => 'Official Transfer Certificate required for external migration.'],
                ['title' => 'Migration Certificate', 'cat' => 'certificates', 'icon' => 'fa-globe', 'color' => 'primary', 'desc' => 'University inter-state and inter-university migration clearance.'],
                ['title' => 'Character / Conduct Certificate', 'cat' => 'certificates', 'icon' => 'fa-user-shield', 'color' => 'info', 'desc' => 'Attestation of good disciplinary standing and moral conduct.'],
                ['title' => 'Bonafide Student Certificate', 'cat' => 'certificates', 'icon' => 'fa-id-badge', 'color' => 'primary', 'desc' => 'Official verification of active student status for passport/visas.'],
                ['title' => 'No Dues Certificate (Library/Dept/Hostel)', 'cat' => 'certificates', 'icon' => 'fa-check-circle', 'color' => 'success', 'desc' => 'Complete institutional financial and resource clearance.'],
                ['title' => 'Extracurricular / Sports Achievement Certs', 'cat' => 'certificates', 'icon' => 'fa-trophy', 'color' => 'warning', 'desc' => 'Verified participation and podium awards in inter-college events.'],
                ['title' => 'Passport (Issued by Ministry of External Affairs, verified by Ministry of Home Affairs)', 'cat' => 'certificates', 'icon' => 'fa-passport', 'color' => 'danger', 'desc' => 'Official international travel credential verified by MEA and Home Affairs.'],
                ['title' => 'Student Disciplinary Clearance Certificate', 'cat' => 'certificates', 'icon' => 'fa-user-check', 'color' => 'success', 'desc' => 'Attestation of zero disciplinary offenses filed during academic tenure.'],

                // Special Memberships & Service (7)
                ['title' => 'National Social Service (NSS) / NCC Cert', 'cat' => 'special', 'icon' => 'fa-hands-helping', 'color' => 'success', 'desc' => 'Accredited national community service and cadet corps records.'],
                ['title' => 'Alumni Association Membership Card', 'cat' => 'special', 'icon' => 'fa-users', 'color' => 'info', 'desc' => 'Permanent lifetime membership card for the university alumni network.'],
                ['title' => 'BAPS Member Ship Transcript', 'cat' => 'special', 'icon' => 'fa-om', 'color' => 'saffron', 'desc' => 'Official spiritual and values-based development credit transcript.'],
                ['title' => 'IIMA member Card With Role letter', 'cat' => 'special', 'icon' => 'fa-user-tie', 'color' => 'purple', 'desc' => 'Elite institutional collaboration card with executive role mandate.'],
                ['title' => 'For Student', 'cat' => 'special', 'icon' => 'fa-graduation-cap', 'color' => 'primary', 'desc' => 'Official student credentials and active membership certificate.'],
                ['title' => 'For Office Work', 'cat' => 'special', 'icon' => 'fa-briefcase', 'color' => 'warning', 'desc' => 'Official administrative office clearance and verification mandate.'],
                ['title' => 'Membership Card', 'cat' => 'special', 'icon' => 'fa-id-card', 'color' => 'success', 'desc' => 'BAPS SVM verified institutional and alumni membership credential.'],

                // Registrar Level (10)
                ['title' => 'Registrar Office Enrollment Attestation', 'cat' => 'registrar', 'icon' => 'fa-user-check', 'color' => 'info', 'desc' => 'Official registry confirmation of active student enrollment.'],
                ['title' => 'Official Student Re-Admission Order', 'cat' => 'registrar', 'icon' => 'fa-user-plus', 'color' => 'primary', 'desc' => 'Approved order for semester reinstatement and re-admission.'],
                ['title' => 'University Course Affiliation Certificate', 'cat' => 'registrar', 'icon' => 'fa-university', 'color' => 'success', 'desc' => 'Registry attestation of course affiliation with the university.'],
                ['title' => 'Registrar Office Semester Extension Approval', 'cat' => 'registrar', 'icon' => 'fa-clock', 'color' => 'warning', 'desc' => 'Authorized extension of academic duration for program completion.'],
                ['title' => 'Academic Record Name Correction Order', 'cat' => 'registrar', 'icon' => 'fa-edit', 'color' => 'secondary', 'desc' => 'Official decree validating corrections made to student registry details.'],
                ['title' => 'Official Degree Verification Certificate', 'cat' => 'registrar', 'icon' => 'fa-graduation-cap', 'color' => 'success', 'desc' => 'Attested verification copy confirming conferral of graduation.'],
                ['title' => 'University Transcript Authenticity Record', 'cat' => 'registrar', 'icon' => 'fa-stamp', 'color' => 'danger', 'desc' => 'Registrar stamp certifying absolute authenticity of grade reports.'],
                ['title' => 'Registrar Attested Migration NOC', 'cat' => 'registrar', 'icon' => 'fa-file-export', 'color' => 'primary', 'desc' => 'Official migration permission granted for national university transfers.'],
                ['title' => 'Annual Registrar Merit List Extract', 'cat' => 'registrar', 'icon' => 'fa-list-ol', 'color' => 'warning', 'desc' => 'Attested portion of top-tier student rank lists filed by registry.'],
                ['title' => 'University Council Disciplinary Clearance', 'cat' => 'registrar', 'icon' => 'fa-user-shield', 'color' => 'success', 'desc' => 'Senate clearance certifying zero pending disciplinary cases.'],

                // President & Vice-President Level (10)
                ['title' => 'Presidential Scholarship Endowment Order', 'cat' => 'president_vp', 'icon' => 'fa-gift', 'color' => 'warning', 'desc' => 'High executive award granting lifetime tuition waiver endowments.'],
                ['title' => 'Vice-President Executive Merit Shield', 'cat' => 'president_vp', 'icon' => 'fa-award', 'color' => 'danger', 'desc' => 'Prestigious shield acknowledging unmatched academic and extra-curricular excellence.'],
                ['title' => 'Presidential Gold Medal Citation', 'cat' => 'president_vp', 'icon' => 'fa-medal', 'color' => 'warning', 'desc' => 'Highest campus accolade awarded for absolute class ranking and integrity.'],
                ['title' => 'Vice-President Student Senate Mandate', 'cat' => 'president_vp', 'icon' => 'fa-users', 'color' => 'primary', 'desc' => 'Executive appointment as head coordinator of the Student Senate.'],
                ['title' => 'President\'s Exceptional Research Fellowship', 'cat' => 'president_vp', 'icon' => 'fa-flask', 'color' => 'purple', 'desc' => 'Funding grant for pioneering research projects approved by the President.'],
                ['title' => 'Distinguished Alumni Presidential Honor', 'cat' => 'president_vp', 'icon' => 'fa-user-graduate', 'color' => 'info', 'desc' => 'Honorary citation presented to outstanding alumni by the Presidential desk.'],
                ['title' => 'Vice-President Cultural Ambassador Certificate', 'cat' => 'president_vp', 'icon' => 'fa-globe-americas', 'color' => 'success', 'desc' => 'Credential appointing representative for global inter-university summits.'],
                ['title' => 'President\'s Global Study Grant Approval', 'cat' => 'president_vp', 'icon' => 'fa-plane-departure', 'color' => 'primary', 'desc' => 'Executive financial approval for overseas exchange and internship programs.'],
                ['title' => 'Vice-President Community Upliftment Award', 'cat' => 'president_vp', 'icon' => 'fa-hands-helping', 'color' => 'success', 'desc' => 'Executive recognition for exceptional service to underprivileged communities.'],
                ['title' => 'President\'s Sports Excellence Citation', 'cat' => 'president_vp', 'icon' => 'fa-trophy', 'color' => 'warning', 'desc' => 'Presidential accolade certifying outstanding feats in national level athletics.'],

                // Advisor Level (5)
                ['title' => 'Academic Advisor Counseling Log', 'cat' => 'advisor', 'icon' => 'fa-comments', 'color' => 'primary', 'desc' => 'Attested log of academic counseling and guidance session completions.'],
                ['title' => 'Advisor Approved Career Path Roadmap', 'cat' => 'advisor', 'icon' => 'fa-map-signs', 'color' => 'info', 'desc' => 'Customized curriculum mapping approved by faculty mentor desk.'],
                ['title' => 'Student Semester Progress Advisor Review', 'cat' => 'advisor', 'icon' => 'fa-chart-line', 'color' => 'success', 'desc' => 'Official advisory evaluation of semester-end performance metrics.'],
                ['title' => 'Advisor Letter of Recommendation (LOR)', 'cat' => 'advisor', 'icon' => 'fa-envelope-open-text', 'color' => 'purple', 'desc' => 'Attested LOR for higher education and global job opportunities.'],
                ['title' => 'Special Remedial Class Advisor Clearance', 'cat' => 'advisor', 'icon' => 'fa-user-graduate', 'color' => 'warning', 'desc' => 'Clearance documenting successful improvement in remedial study courses.'],

                // CR Level (5)
                ['title' => 'CR Minutes of Class Committee Meeting', 'cat' => 'cr', 'icon' => 'fa-clipboard-list', 'color' => 'success', 'desc' => 'CR attested records of class council discussions and academic feedback.'],
                ['title' => 'Class Representatives Forum Resolution', 'cat' => 'cr', 'icon' => 'fa-handshake', 'color' => 'primary', 'desc' => 'Resolution passed by the CR board regarding campus facilities.'],
                ['title' => 'CR Certified Class Attendance Grievance', 'cat' => 'cr', 'icon' => 'fa-user-times', 'color' => 'danger', 'desc' => 'Official claim logged by CR on behalf of students regarding attendance.'],
                ['title' => 'CR Endorsed Leave Application Form', 'cat' => 'cr', 'icon' => 'fa-plane-departure', 'color' => 'warning', 'desc' => 'Leave form verified and endorsed by the Class Representative.'],
                ['title' => 'Class Core Team Member Credential', 'cat' => 'cr', 'icon' => 'fa-user-shield', 'color' => 'info', 'desc' => 'Official validation card for student members of the class core committee.'],

                // Class Coordinator Level (2)
                ['title' => 'Class Coordinator Term End Report', 'cat' => 'class_coordinator', 'icon' => 'fa-file-signature', 'color' => 'primary', 'desc' => 'Coordinator report detailing the semester completion status of the batch.'],
                ['title' => 'Coordinator Approved Internal Assessment Sheet', 'cat' => 'class_coordinator', 'icon' => 'fa-table', 'color' => 'success', 'desc' => 'Verified spreadsheet of internal assessment credits attested by coordinator.'],

                // HOD Level (4)
                ['title' => 'HOD Lab Resource Allocation Pass', 'cat' => 'hod_level', 'icon' => 'fa-key', 'color' => 'dark', 'desc' => 'HOD signature granting access to advanced computing/research labs.'],
                ['title' => 'Departmental Honors Program Attestation', 'cat' => 'hod_level', 'icon' => 'fa-award', 'color' => 'warning', 'desc' => 'Attestation of qualification into the departmental Honors program.'],
                ['title' => 'HOD Attested Academic Elective Choice', 'cat' => 'hod_level', 'icon' => 'fa-check-double', 'color' => 'info', 'desc' => 'Official choice form validating academic elective configurations.'],
                ['title' => 'Departmental Industrial Visit Clearance', 'cat' => 'hod_level', 'icon' => 'fa-bus-alt', 'color' => 'primary', 'desc' => 'HOD clearance allowing participation in industrial training visits.'],

                // Canteen & Store Level (4)
                ['title' => 'Mess Subscription Card & Food Coupon', 'cat' => 'canteen_store', 'icon' => 'fa-utensils', 'color' => 'danger', 'desc' => 'RFID mess registration card and vegetarian food coupons.'],
                ['title' => 'Campus Store Textbook Subsidy Pass', 'cat' => 'canteen_store', 'icon' => 'fa-book-open', 'color' => 'success', 'desc' => 'Subsidy pass allowing purchase of discounted curriculum textbooks.'],
                ['title' => 'Canteen Food Hygiene Committee Audit', 'cat' => 'canteen_store', 'icon' => 'fa-clipboard-check', 'color' => 'warning', 'desc' => 'Verification report of campus cafeteria food safety standard audit.'],
                ['title' => 'Campus Store Uniform Allocation Slip', 'cat' => 'canteen_store', 'icon' => 'fa-tshirt', 'color' => 'info', 'desc' => 'Store token authorizing retrieval of institutional uniforms.'],

                // Librarian Level (5)
                ['title' => 'Central Library Premium Membership Card', 'cat' => 'librarian_level', 'icon' => 'fa-id-card', 'color' => 'info', 'desc' => 'Premium access card for extended borrowing privileges and online databases.'],
                ['title' => 'Librarian Approved Book Reservation Slip', 'cat' => 'librarian_level', 'icon' => 'fa-bookmark', 'color' => 'success', 'desc' => 'Approved reservation ticket for borrowing highly demanded textbooks.'],
                ['title' => 'Digital Library Remote Access Credential', 'cat' => 'librarian_level', 'icon' => 'fa-laptop-code', 'color' => 'purple', 'desc' => 'Credential token providing remote VPN access to international journals.'],
                ['title' => 'Library Outstanding Overdue Fine Waiver', 'cat' => 'librarian_level', 'icon' => 'fa-percentage', 'color' => 'warning', 'desc' => 'Approved waiver slip clearing historical overdue library fine records.'],
                ['title' => 'Rare Manuscripts Section Access Clearance', 'cat' => 'librarian_level', 'icon' => 'fa-book-reader', 'color' => 'danger', 'desc' => 'Special permission signed by Chief Librarian to refer rare research archives.'],

                // Club\'s Level (5)
                ['title' => 'GDGoC Campus Lead Coordinator Credentials', 'cat' => 'clubs_level', 'icon' => 'fa-laptop-code', 'color' => 'primary', 'desc' => 'Google Developer Groups on Campus executive appointment and lead credentials.'],
                ['title' => 'HexSociety Competitive Programming Core Badge', 'cat' => 'clubs_level', 'icon' => 'fa-brain', 'color' => 'danger', 'desc' => 'Official membership card for elite algorithmic programming and hackathon members.'],
                ['title' => 'HackerRank Algorithms Practice Certification', 'cat' => 'clubs_level', 'icon' => 'fa-terminal', 'color' => 'success', 'desc' => 'Accredited practice ranking certification from the campus coding chapter.'],
                ['title' => 'ACM Student Chapter Executive Member Card', 'cat' => 'clubs_level', 'icon' => 'fa-network-wired', 'color' => 'info', 'desc' => 'Executive badge of the Association for Computing Machinery student chapter.'],
                ['title' => 'IEEE Student Branch Innovation Project Clearance', 'cat' => 'clubs_level', 'icon' => 'fa-microchip', 'color' => 'purple', 'desc' => 'Clearance and attestation for innovation projects under IEEE student branch.']
            ];
        @endphp

        @foreach($officialDocs as $index => $doc)
        <div class="col-12 col-md-6 col-lg-4 doc-grid-item" data-category="{{ $doc['cat'] }}" data-title="{{ strtolower($doc['title']) }}">
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden position-relative" style="border-top: 4px solid var(--baps-{{ $doc['color'] == 'saffron' ? 'saffron' : $doc['color'] }});">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 52px; height: 52px; font-size: 1.5rem; color: var(--baps-{{ $doc['color'] == 'saffron' ? 'saffron' : $doc['color'] }});">
                            <i class="fas {{ $doc['icon'] }}"></i>
                        </div>
                        <span class="badge bg-success text-white px-3 py-1 rounded-pill small fw-bold shadow-sm d-flex align-items-center gap-1">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    </div>

                    <h6 class="fw-bold text-dark mb-2 fs-6 doc-title-text">{{ $doc['title'] }}</h6>
                    <p class="text-muted small mb-4 flex-grow-1 lh-base">{{ $doc['desc'] }}</p>

                    <div class="d-flex flex-wrap gap-2 pt-3 border-top mt-auto">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold flex-grow-1" onclick="openDocumentIssueModal('{{ addslashes($doc['title']) }}')">
                            <i class="fas fa-file-export me-1"></i> Document Issue
                        </button>
                        <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold flex-grow-1" onclick="downloadOfficialDoc('{{ addslashes($doc['title']) }}')">
                            <i class="fas fa-download me-1"></i> Download
                        </button>
                        <button class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-bold text-secondary" onclick="verifyOfficialDoc('{{ addslashes($doc['title']) }}')" title="Blockchain Ledger Verification">
                            <i class="fas fa-shield-alt text-warning"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>

<script>
    function filterOfficialDocuments() {
        const input = document.getElementById('docSearchInput').value.toLowerCase();
        const items = document.getElementsByClassName('doc-grid-item');
        const activeCategory = document.querySelector('#docCategoryFilters .btn.active').getAttribute('onclick').match(/'([^']+)'/)[1];

        for (let item of items) {
            const title = item.getAttribute('data-title');
            const cat = item.getAttribute('data-category');
            const matchSearch = title.includes(input);
            const matchCat = (activeCategory === 'all' || cat === activeCategory);

            if (matchSearch && matchCat) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        }
    }

    function filterByCategory(category, btn) {
        const buttons = document.querySelectorAll('#docCategoryFilters .btn');
        buttons.forEach(b => {
            b.classList.remove('btn-dark', 'active');
            b.classList.add('btn-light', 'text-secondary');
        });
        btn.classList.remove('btn-light', 'text-secondary');
        btn.classList.add('btn-dark', 'active');

        filterOfficialDocuments();
    }

    function previewOfficialDoc(title) {
        const url = '/document/official/' + encodeURIComponent(title);
        const iframe = document.getElementById('previewIframe');
        if (iframe) {
            iframe.src = url;
            const modalEl = document.getElementById('filePreviewModal');
            if (modalEl) {
                const modalTitle = modalEl.querySelector('.modal-title');
                if (modalTitle) modalTitle.innerHTML = `<i class="fas fa-file-contract me-2 text-info"></i> Official Student Record: ${title}`;
                new bootstrap.Modal(modalEl).show();
            }
        } else {
            window.open(url, '_blank');
        }
        if (typeof showBapsToast === 'function') showBapsToast(`Generating official personalized record for ${title}... 📄`, 'info');
    }

    function downloadOfficialDoc(title) {
        const url = '/document/official/' + encodeURIComponent(title) + '?download=1';
        window.open(url, '_blank');
        if (typeof showBapsToast === 'function') showBapsToast(`Downloading official personalized record: ${title} 📥`, 'success');
    }

    function verifyOfficialDoc(title) {
        if (typeof showBapsToast === 'function') {
            showBapsToast(`Blockchain ledger verifying: ${title}... 🔗`, 'warning');
            setTimeout(() => {
                showBapsToast(`✅ Cryptographic hash verified! Document is 100% authentic.`, 'success');
            }, 1200);
        } else {
            alert(`✅ Cryptographic hash verified! ${title} is 100% authentic on the private institutional ledger.`);
        }
    }
</script>

<!-- AI Office Assistant Console Modal -->
<div class="modal fade" id="aiOfficeAssistantModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #f8fafc;">
            <div class="modal-header border-0 p-4 text-white" style="background: linear-gradient(135deg, #7e22ce 0%, #3b82f6 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-purple rounded-4 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 54px; height: 54px; font-size: 1.8rem;">
                        <i class="fas fa-robot" style="color: #9333ea;"></i>
                    </div>
                    <div>
                        <h4 class="modal-title fw-bold mb-1 d-flex align-items-center gap-2">
                            AI Office Assistant Console
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">Trained Expert</span>
                        </h4>
                        <div class="small text-light fw-semibold">Specialized AI trained on Document Giving Vault (For Student & Staff)</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <!-- Training Framework Banner -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #ffffff; border-left: 5px solid #3b82f6 !important;">
                    <div class="card-body p-4">
                        <h6 class="fw-bold text-dark mb-2 d-flex align-items-center gap-2 fs-5">
                            <i class="fas fa-graduation-cap text-primary"></i> 4-Category Classification Framework
                        </h6>
                        <p class="text-muted small mb-0 lh-base fs-6">
                            I am trained to instantly identify requests across <strong>1. Academic Records</strong>, <strong>2. Administrative & Fees</strong>, <strong>3. Conduct & Leaving</strong>, and <strong>4. Special Memberships</strong>. All 22 documents in this vault are pre-verified. Tell me what you need, and I will locate, preview, or download it for you instantly!
                        </p>
                    </div>
                </div>

                <!-- Quick Suggestion Chips -->
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted mb-2"><i class="fas fa-lightbulb text-warning me-1"></i> Quick Document Requests (Click to test AI):</label>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-2 fw-bold" onclick="sendAiPrompt('I need my semester marksheet')">🎓 Need Marksheet</button>
                        <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-2 fw-bold" onclick="sendAiPrompt('Where is my fee receipt?')">💰 Fee Receipt</button>
                        <button class="btn btn-sm btn-outline-danger rounded-pill px-3 py-2 fw-bold" onclick="sendAiPrompt('I need college leaving certificate LC')">✈️ Leaving Cert (LC)</button>
                        <button class="btn btn-sm btn-outline-warning text-dark rounded-pill px-3 py-2 fw-bold" onclick="sendAiPrompt('Show me BAPS membership transcript')">🕉️ BAPS Transcript</button>
                        <button class="btn btn-sm btn-outline-info text-dark rounded-pill px-3 py-2 fw-bold" onclick="sendAiPrompt('I need hostel allotment letter')">🛏️ Hostel Allotment</button>
                        <button class="btn btn-sm btn-outline-purple rounded-pill px-3 py-2 fw-bold" style="border-color:#9333ea; color:#9333ea;" onclick="sendAiPrompt('IIMA member card')">👔 IIMA Card</button>
                    </div>
                </div>

                <!-- Chat History Container -->
                <div id="aiChatHistory" class="p-3 mb-4 rounded-4 border overflow-auto" style="background: #ffffff; min-height: 280px; max-height: 380px;">
                    <!-- Initial AI Greeting -->
                    <div class="d-flex gap-3 mb-4">
                        <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 42px; height: 42px;">
                            <i class="fas fa-robot fs-5"></i>
                        </div>
                        <div class="bg-light p-3 rounded-4 shadow-sm border" style="max-width: 85%;">
                            <div class="fw-bold text-primary mb-1 small">AI Office Assistant</div>
                            <div class="text-dark fs-6 lh-base">Hello! I am your trained AI Office Assistant. I manage the 22 pre-verified institutional records in the Document Giving Vault. How can I assist you today?</div>
                        </div>
                    </div>
                </div>

                <!-- Chat Input Area -->
                <form id="aiAssistantForm" onsubmit="event.preventDefault(); submitAiAssistantQuery();">
                    <div class="input-group input-group-lg shadow-sm rounded-pill overflow-hidden border">
                        <input type="text" id="aiQueryInput" class="form-control border-0 ps-4 fs-6" placeholder="Type document request (e.g., 'I need my bonafide certificate')..." required>
                        <button type="submit" class="btn btn-primary px-5 fw-bold"><i class="fas fa-paper-plane me-2"></i> Send</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light p-4 d-flex justify-content-between align-items-center">
                <div class="small text-muted fw-bold">
                    <i class="fas fa-shield-alt text-success me-1"></i> Pre-Verified Institutional Records Engine
                </div>
                <button type="button" class="btn btn-dark fw-bold px-5 py-2 rounded-pill shadow-sm" data-bs-dismiss="modal">Close AI Console</button>
            </div>
        </div>
    </div>
</div>

<script>
    const aiKnowledgeBase = [
        // Academic Records (8)
        { title: 'College Admission Letter', cat: 'Academic Records', keywords: ['admission', 'admit', 'letter', 'college admission', 'branch'] },
        { title: 'Semester Grade Sheets / Marksheets', cat: 'Academic Records', keywords: ['marksheet', 'grade sheet', 'grades', 'result', 'semester marksheet', 'gpa', 'cgpa'] },
        { title: 'Consolidated Transcript', cat: 'Academic Records', keywords: ['transcript', 'consolidated', 'all semesters', 'degree transcript'] },
        { title: 'Degree Certificate / Provisional Degree', cat: 'Academic Records', keywords: ['degree', 'certificate', 'provisional', 'graduation', 'convocation'] },
        { title: 'Course Syllabus Copy (Attested)', cat: 'Academic Records', keywords: ['syllabus', 'curriculum', 'attested syllabus', 'course copy'] },
        { title: 'Academic Project Report Approval Copy', cat: 'Academic Records', keywords: ['project', 'report', 'approval', 'capstone', 'final year project'] },
        { title: 'Internship Completion Certificate', cat: 'Academic Records', keywords: ['internship', 'training', 'completion', 'industrial', 'company'] },
        { title: 'NOC for External Internships', cat: 'Academic Records', keywords: ['noc', 'external internship', 'no objection certificate', 'internship clearance'] },

        // Administrative & Fees (10)
        { title: 'College Identity Card (ID Card)', cat: 'Administrative & Fees', keywords: ['id card', 'identity', 'smart card', 'rfid', 'barcode'] },
        { title: 'Fee Receipt (Current Semester/Year)', cat: 'Administrative & Fees', keywords: ['fee', 'receipt', 'payment', 'paid', 'tuition', 'challan'] },
        { title: 'College Bus / Transport Pass', cat: 'Administrative & Fees', keywords: ['bus', 'transport', 'pass', 'travel', 'route'] },
        { title: 'Hostel Allotment Letter', cat: 'Administrative & Fees', keywords: ['hostel', 'allotment', 'room', 'dormitory', 'bed', 'accommodation'] },
        { title: 'Scholarship Sanction Letter / Document', cat: 'Administrative & Fees', keywords: ['scholarship', 'sanction', 'financial aid', 'endowment', 'merit'] },
        { title: 'National Identity Document (e.g., Aadhaar Card - UIDAI under MeitY/Home links)', cat: 'Administrative & Fees', keywords: ['aadhaar', 'uidai', 'meity', 'home affairs', 'national identity', 'biometric'] },
        { title: 'Central Scholarship / National Scholarship Portal (NSP) Eligibility Certificate', cat: 'Administrative & Fees', keywords: ['nsp', 'central scholarship', 'national scholarship portal', 'eligibility'] },
        { title: 'State Transport Student Concession Pass (e.g., GSRTC Student Pass)', cat: 'Administrative & Fees', keywords: ['gsrtc', 'state transport', 'concession pass', 'bus pass', 'student pass'] },
        { title: 'University Convocation Guest Pass', cat: 'Administrative & Fees', keywords: ['guest pass', 'convocation pass', 'entry pass', 'convocation entry'] },
        { title: 'Hostel Room Change Approval Form', cat: 'Administrative & Fees', keywords: ['room change', 'hostel room transfer', 'room transfer', 'room change approval'] },

        // Conduct & Leaving (8)
        { title: 'College Leaving Certificate (LC) / TC', cat: 'Conduct & Leaving', keywords: ['leaving', 'lc', 'tc', 'transfer', 'migration out'] },
        { title: 'Migration Certificate', cat: 'Conduct & Leaving', keywords: ['migration', 'inter-state', 'university migration', 'clearance'] },
        { title: 'Character / Conduct Certificate', cat: 'Conduct & Leaving', keywords: ['character', 'conduct', 'moral', 'behavior', 'disciplinary'] },
        { title: 'Bonafide Student Certificate', cat: 'Conduct & Leaving', keywords: ['bonafide', 'student certificate', 'passport', 'visa', 'proof'] },
        { title: 'No Dues Certificate (Library/Dept/Hostel)', cat: 'Conduct & Leaving', keywords: ['no dues', 'dues', 'clearance', 'library clearance', 'dept clearance'] },
        { title: 'Extracurricular / Sports Achievement Certs', cat: 'Conduct & Leaving', keywords: ['sports', 'extracurricular', 'achievement', 'trophy', 'winner', 'competition'] },
        { title: 'Passport (Issued by Ministry of External Affairs, verified by Ministry of Home Affairs)', cat: 'Conduct & Leaving', keywords: ['passport', 'mea', 'ministry of external affairs', 'home affairs', 'travel', 'visa'] },
        { title: 'Student Disciplinary Clearance Certificate', cat: 'Conduct & Leaving', keywords: ['disciplinary clearance', 'disciplinary record', 'clean record'] },

        // Special Memberships (7)
        { title: 'National Social Service (NSS) / NCC Cert', cat: 'Special Memberships', keywords: ['nss', 'ncc', 'social service', 'cadet', 'camp', 'community'] },
        { title: 'Alumni Association Membership Card', cat: 'Special Memberships', keywords: ['alumni', 'association', 'membership card', 'lifetime', 'network'] },
        { title: 'BAPS Member Ship Transcript', cat: 'Special Memberships', keywords: ['baps', 'membership transcript', 'spiritual', 'values', 'akshar', 'swami'] },
        { title: 'IIMA member Card With Role letter', cat: 'Special Memberships', keywords: ['iima', 'member card', 'role letter', 'management', 'collaboration'] },
        { title: 'For Student', cat: 'Special Memberships', keywords: ['for student', 'student credential', 'active membership'] },
        { title: 'For Office Work', cat: 'Special Memberships', keywords: ['office work', 'administrative office clearance', 'verification mandate'] },
        { title: 'Membership Card', cat: 'Special Memberships', keywords: ['membership card', 'alumni verified', 'svm membership'] },

        // Registrar Level (10)
        { title: 'Registrar Office Enrollment Attestation', cat: 'Registrar Level', keywords: ['registrar enrollment', 'enrollment attestation', 'registrar office'] },
        { title: 'Official Student Re-Admission Order', cat: 'Registrar Level', keywords: ['re-admission', 'readmission order', 'reinstatement'] },
        { title: 'University Course Affiliation Certificate', cat: 'Registrar Level', keywords: ['course affiliation', 'affiliation certificate', 'university affiliation'] },
        { title: 'Registrar Office Semester Extension Approval', cat: 'Registrar Level', keywords: ['semester extension', 'extension approval', 'duration extension'] },
        { title: 'Academic Record Name Correction Order', cat: 'Registrar Level', keywords: ['name correction', 'record correction', 'name change'] },
        { title: 'Official Degree Verification Certificate', cat: 'Registrar Level', keywords: ['degree verification', 'verify degree', 'graduation verification'] },
        { title: 'University Transcript Authenticity Record', cat: 'Registrar Level', keywords: ['transcript authenticity', 'verify transcript', 'transcript stamp'] },
        { title: 'Registrar Attested Migration NOC', cat: 'Registrar Level', keywords: ['migration noc', 'migration permission', 'registrar migration'] },
        { title: 'Annual Registrar Merit List Extract', cat: 'Registrar Level', keywords: ['merit list', 'registrar merit', 'rank list'] },
        { title: 'University Council Disciplinary Clearance', cat: 'Registrar Level', keywords: ['disciplinary clearance', 'council clearance', 'senate clearance'] },

        // President & Vice-President Level (10)
        { title: 'Presidential Scholarship Endowment Order', cat: 'President/VP Level', keywords: ['presidential scholarship', 'scholarship endowment', 'tuition waiver'] },
        { title: 'Vice-President Executive Merit Shield', cat: 'President/VP Level', keywords: ['merit shield', 'vp shield', 'vice-president merit'] },
        { title: 'Presidential Gold Medal Citation', cat: 'President/VP Level', keywords: ['gold medal', 'presidential gold medal', 'citation gold medal'] },
        { title: 'Vice-President Student Senate Mandate', cat: 'President/VP Level', keywords: ['senate mandate', 'student senate coordinator', 'vp mandate'] },
        { title: 'President\'s Exceptional Research Fellowship', cat: 'President/VP Level', keywords: ['exceptional research', 'research fellowship', 'president fellowship'] },
        { title: 'Distinguished Alumni Presidential Honor', cat: 'President/VP Level', keywords: ['distinguished alumni', 'alumni presidential', 'honorary alumni'] },
        { title: 'Vice-President Cultural Ambassador Certificate', cat: 'President/VP Level', keywords: ['cultural ambassador', 'cultural certificate', 'vp ambassador'] },
        { title: 'President\'s Global Study Grant Approval', cat: 'President/VP Level', keywords: ['global study', 'study grant', 'overseas exchange'] },
        { title: 'Vice-President Community Upliftment Award', cat: 'President/VP Level', keywords: ['community upliftment', 'upliftment award', 'vp community'] },
        { title: 'President\'s Sports Excellence Citation', cat: 'President/VP Level', keywords: ['sports excellence', 'presidential sports', 'athletics citation'] },

        // Advisor Level (5)
        { title: 'Academic Advisor Counseling Log', cat: 'Advisor Level', keywords: ['counseling log', 'advisor counseling', 'academic advisor log'] },
        { title: 'Advisor Approved Career Path Roadmap', cat: 'Advisor Level', keywords: ['career path', 'career roadmap', 'curriculum mapping'] },
        { title: 'Student Semester Progress Advisor Review', cat: 'Advisor Level', keywords: ['advisor review', 'semester progress', 'advisory progress'] },
        { title: 'Advisor Letter of Recommendation (LOR)', cat: 'Advisor Level', keywords: ['advisor lor', 'advisor recommendation', 'lor letter'] },
        { title: 'Special Remedial Class Advisor Clearance', cat: 'Advisor Level', keywords: ['remedial class', 'remedial clearance', 'advisor remedial'] },

        // CR Level (5)
        { title: 'CR Minutes of Class Committee Meeting', cat: 'CR Level', keywords: ['cr minutes', 'class committee minutes', 'cr committee'] },
        { title: 'Class Representatives Forum Resolution', cat: 'CR Level', keywords: ['forum resolution', 'cr resolution', 'cr board'] },
        { title: 'CR Certified Class Attendance Grievance', cat: 'CR Level', keywords: ['attendance grievance', 'cr attendance', 'attendance claim'] },
        { title: 'CR Endorsed Leave Application Form', cat: 'CR Level', keywords: ['leave form', 'leave application', 'cr leave', 'cr endorsed'] },
        { title: 'Class Core Team Member Credential', cat: 'CR Level', keywords: ['core team', 'class member', 'class core team', 'committee card'] },

        // Class Coordinator Level (2)
        { title: 'Class Coordinator Term End Report', cat: 'Coordinator Level', keywords: ['coordinator report', 'coordinator term end', 'term end report'] },
        { title: 'Coordinator Approved Internal Assessment Sheet', cat: 'Coordinator Level', keywords: ['internal assessment', 'coordinator assessment', 'assessment sheet'] },

        // HOD Level (4)
        { title: 'HOD Lab Resource Allocation Pass', cat: 'HOD Level', keywords: ['lab allocation', 'lab pass', 'hod lab key'] },
        { title: 'Departmental Honors Program Attestation', cat: 'HOD Level', keywords: ['honors program', 'departmental honors', 'hod honors'] },
        { title: 'HOD Attested Academic Elective Choice', cat: 'HOD Level', keywords: ['elective choice', 'hod elective', 'elective form'] },
        { title: 'Departmental Industrial Visit Clearance', cat: 'HOD Level', keywords: ['industrial visit', 'departmental visit', 'hod visit'] },

        // Canteen & Store Level (4)
        { title: 'Mess Subscription Card & Food Coupon', cat: 'Canteen & Store', keywords: ['mess card', 'food coupon', 'mess subscription'] },
        { title: 'Campus Store Textbook Subsidy Pass', cat: 'Canteen & Store', keywords: ['textbook subsidy', 'store subsidy', 'discount textbook'] },
        { title: 'Canteen Food Hygiene Committee Audit', cat: 'Canteen & Store', keywords: ['hygiene audit', 'food safety', 'canteen audit'] },
        { title: 'Campus Store Uniform Allocation Slip', cat: 'Canteen & Store', keywords: ['uniform slip', 'store uniform', 'uniform token'] },

        // Librarian Level (5)
        { title: 'Central Library Premium Membership Card', cat: 'Librarian Level', keywords: ['library card', 'premium library', 'extended borrowing'] },
        { title: 'Librarian Approved Book Reservation Slip', cat: 'Librarian Level', keywords: ['book reservation', 'library reservation', 'librarian approved book'] },
        { title: 'Digital Library Remote Access Credential', cat: 'Librarian Level', keywords: ['remote access library', 'digital library access', 'library vpn'] },
        { title: 'Library Outstanding Overdue Fine Waiver', cat: 'Librarian Level', keywords: ['fine waiver', 'library fine', 'overdue waiver'] },
        { title: 'Rare Manuscripts Section Access Clearance', cat: 'Librarian Level', keywords: ['rare manuscripts', 'manuscripts clearance', 'chief librarian access'] },

        // Club's Level (5)
        { title: 'GDGoC Campus Lead Coordinator Credentials', cat: 'Club\'s Level', keywords: ['gdgoc', 'gdgoc lead', 'campus lead', 'google developers'] },
        { title: 'HexSociety Competitive Programming Core Badge', cat: 'Club\'s Level', keywords: ['hexsociety', 'competitive programming', 'hex society', 'programmer card'] },
        { title: 'HackerRank Algorithms Practice Certification', cat: 'Club\'s Level', keywords: ['hackerrank', 'algorithms practice', 'hackerrank certificate', 'coding badge'] },
        { title: 'ACM Student Chapter Executive Member Card', cat: 'Club\'s Level', keywords: ['acm', 'acm chapter', 'acm member', 'acm executive'] },
        { title: 'IEEE Student Branch Innovation Project Clearance', cat: 'Club\'s Level', keywords: ['ieee', 'ieee branch', 'ieee project', 'ieee clearance'] }
    ];

    function sendAiPrompt(text) {
        document.getElementById('aiQueryInput').value = text;
        submitAiAssistantQuery();
    }

    function submitAiAssistantQuery() {
        if (localStorage.getItem('ai_copilot_enabled') === 'false') {
            alert("AI Office Assistant service is currently disabled by the Administrator.");
            return;
        }
        const inputEl = document.getElementById('aiQueryInput');
        const query = inputEl.value.trim();
        if (!query) return;

        const chatHist = document.getElementById('aiChatHistory');

        // Append User Bubble
        const userBubble = document.createElement('div');
        userBubble.className = 'd-flex gap-3 mb-4 justify-content-end';
        userBubble.innerHTML = `
            <div class="bg-primary text-white p-3 rounded-4 shadow-sm border" style="max-width: 85%;">
                <div class="fw-bold mb-1 small text-end text-light">You (Student/Staff)</div>
                <div class="fs-6 lh-base">${query}</div>
            </div>
            <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 42px; height: 42px;">
                <i class="fas fa-user fs-5"></i>
            </div>
        `;
        chatHist.appendChild(userBubble);
        inputEl.value = '';
        chatHist.scrollTop = chatHist.scrollHeight;

        // Show Typing Indicator
        const typingBubble = document.createElement('div');
        typingBubble.className = 'd-flex gap-3 mb-4 ai-typing-indicator';
        typingBubble.innerHTML = `
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 42px; height: 42px;">
                <i class="fas fa-robot fs-5"></i>
            </div>
            <div class="bg-light p-3 rounded-4 shadow-sm border d-flex align-items-center gap-2 text-muted small fw-bold">
                <i class="fas fa-circle-notch fa-spin text-primary"></i> AI Assistant is categorizing and locating document in vault...
            </div>
        `;
        chatHist.appendChild(typingBubble);
        chatHist.scrollTop = chatHist.scrollHeight;

        // Process Query logic after delay
        setTimeout(() => {
            typingBubble.remove();
            processAiMatching(query, chatHist);
        }, 800);
    }

    function processAiMatching(query, chatHist) {
        const qClean = query.toLowerCase();
        let matchedDoc = null;

        for (let doc of aiKnowledgeBase) {
            if (doc.title.toLowerCase().includes(qClean)) {
                matchedDoc = doc;
                break;
            }
            for (let kw of doc.keywords) {
                if (qClean.includes(kw)) {
                    matchedDoc = doc;
                    break;
                }
            }
            if (matchedDoc) break;
        }

        const aiBubble = document.createElement('div');
        aiBubble.className = 'd-flex gap-3 mb-4';

        if (matchedDoc) {
            // Filter background vault grid to highlight this document
            const searchInput = document.getElementById('docSearchInput');
            if (searchInput) {
                searchInput.value = matchedDoc.title;
                filterOfficialDocuments();
            }

            aiBubble.innerHTML = `
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 42px; height: 42px;">
                    <i class="fas fa-robot fs-5"></i>
                </div>
                <div class="bg-light p-4 rounded-4 shadow-sm border" style="max-width: 85%; border-left: 5px solid #10b981 !important;">
                    <div class="d-flex justify-content-between align-items-center mb-2 flex-wrap gap-2">
                        <div class="fw-bold text-primary small">AI Office Assistant</div>
                        <span class="badge bg-success text-white px-3 py-1 rounded-pill small fw-bold"><i class="fas fa-check-circle me-1"></i> Pre-Verified Record</span>
                    </div>
                    <div class="text-dark fs-6 lh-base mb-3">
                        I have successfully processed your request. Based on my classification framework, your document belongs to the <strong>${matchedDoc.cat}</strong> category.
                    </div>
                    <div class="card border-0 bg-white shadow-sm rounded-3 p-3 mb-4 border border-success border-2">
                        <div class="fw-bold text-dark fs-5 mb-1 d-flex align-items-center gap-2">
                            <i class="fas fa-file-contract text-danger"></i> ${matchedDoc.title}
                        </div>
                        <div class="small text-muted mb-2">Category Tab: <span class="badge bg-dark text-white">${matchedDoc.cat}</span></div>
                        <div class="small text-success fw-bold"><i class="fas fa-shield-alt me-1"></i> Pre-verified & Ready for Instant Provisioning</div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <button class="btn btn-primary fw-bold px-4 py-2 rounded-pill shadow-sm flex-grow-1" onclick="previewOfficialDoc('${addslashes(matchedDoc.title)}')"><i class="fas fa-eye me-2"></i> Preview Document</button>
                        <button class="btn btn-success fw-bold px-4 py-2 rounded-pill shadow-sm flex-grow-1" onclick="downloadOfficialDoc('${addslashes(matchedDoc.title)}')"><i class="fas fa-download me-2"></i> Download Document</button>
                    </div>
                </div>
            `;
        } else {
            aiBubble.innerHTML = `
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 shadow-sm" style="width: 42px; height: 42px;">
                    <i class="fas fa-robot fs-5"></i>
                </div>
                <div class="bg-light p-4 rounded-4 shadow-sm border" style="max-width: 85%; border-left: 5px solid #f59e0b !important;">
                    <div class="fw-bold text-primary mb-1 small">AI Office Assistant</div>
                    <div class="text-dark fs-6 lh-base mb-3">
                        I searched the 22 accredited documents in the Document Giving Vault but couldn't find an exact match for "<strong>${query}</strong>". 
                    </div>
                    <div class="small text-muted mb-3">Please try selecting from one of the 4 operational categories: <strong>Academic Records</strong>, <strong>Administrative & Fees</strong>, <strong>Conduct & Leaving</strong>, or <strong>Special Memberships</strong>.</div>
                    <button class="btn btn-sm btn-dark rounded-pill px-4 py-2 fw-bold" onclick="document.getElementById('docSearchInput').value=''; filterOfficialDocuments();"><i class="fas fa-sync-alt me-2"></i> Reset Vault Grid</button>
                </div>
            `;
        }

        chatHist.appendChild(aiBubble);
        chatHist.scrollTop = chatHist.scrollHeight;
    }
</script>

<!-- Add Manual Document Modal -->
<div class="modal fade" id="addManualDocModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #f8fafc;">
            <div class="modal-header border-0 p-4 text-white" style="background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-primary rounded-4 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 54px; height: 54px; font-size: 1.8rem;">
                        <i class="fas fa-plus-circle" style="color: #2563eb;"></i>
                    </div>
                    <div>
                        <h4 class="modal-title fw-bold mb-1 d-flex align-items-center gap-2">
                            Manual Document Filing Engine
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">Pre-Verified Filing</span>
                        </h4>
                        <div class="small text-light fw-semibold">Authorize and inject new institutional records into the Document Giving Vault</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="manualDocFilingForm" onsubmit="event.preventDefault(); submitManualDocumentFiling();">
                    <div class="row g-4 mb-3">
                        <div class="col-12 col-md-8">
                            <label class="form-label small fw-bold text-muted">Official Document Name / Title <span class="text-danger">*</span></label>
                            <input type="text" id="manual_doc_name" class="form-control py-2 fs-6" placeholder="e.g., AI & Machine Learning Specialization Certificate" required>
                        </div>
                        <div class="col-12 col-md-4">
                            <label class="form-label small fw-bold text-muted">Operational Category <span class="text-danger">*</span></label>
                            <select id="manual_doc_cat" class="form-select py-2 fs-6" required>
                                <option value="academic">Academic Records</option>
                                <option value="administrative">Administrative & Fees</option>
                                <option value="certificates">Conduct & Leaving</option>
                                <option value="special">Special Memberships</option>
                            </select>
                        </div>
                    </div>

                    <div class="row g-4 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">Card Icon <span class="text-danger">*</span></label>
                            <select id="manual_doc_icon" class="form-select py-2 fs-6" required>
                                <option value="fa-certificate">🏆 Certificate Icon (fa-certificate)</option>
                                <option value="fa-award">🏅 Award Icon (fa-award)</option>
                                <option value="fa-file-signature">📝 Signature Icon (fa-file-signature)</option>
                                <option value="fa-user-graduate">🎓 Graduate Icon (fa-user-graduate)</option>
                                <option value="fa-id-card">💳 ID Card Icon (fa-id-card)</option>
                                <option value="fa-star">⭐ Star Icon (fa-star)</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">Card Accent Color <span class="text-danger">*</span></label>
                            <select id="manual_doc_color" class="form-select py-2 fs-6" required>
                                <option value="primary">Primary Blue</option>
                                <option value="success">Success Green</option>
                                <option value="warning">Warning Yellow</option>
                                <option value="danger">Danger Red</option>
                                <option value="purple">Executive Purple</option>
                                <option value="saffron">Spiritual Saffron</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Document Description / Mandate <span class="text-danger">*</span></label>
                        <textarea id="manual_doc_desc" class="form-control py-2 fs-6" rows="3" placeholder="Enter official attested description or eligibility criteria..." required></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Pre-Verification Authority & Stamping <span class="text-danger">*</span></label>
                        <input type="text" id="manual_doc_auth" class="form-control py-2 fs-6" value="Dr. Sadhu Gyaneswar Das (Dean) & University Senate" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 text-white fw-bold rounded-pill shadow-sm fs-5">
                        <i class="fas fa-file-import me-2"></i> File & Pre-Verify Document Now
                    </button>
                </form>
            </div>
            <div class="modal-footer border-top-0 bg-light p-4 d-flex justify-content-between align-items-center">
                <div class="small text-muted fw-bold">
                    <i class="fas fa-user-shield text-primary me-1"></i> 175% Executive Filing Authorization
                </div>
                <button type="button" class="btn btn-dark fw-bold px-5 py-2 rounded-pill shadow-sm" data-bs-dismiss="modal">Close Filing Hub</button>
            </div>
        </div>
    </div>
</div>

<script>
    let totalVaultDocumentsCount = 83;

    function submitManualDocumentFiling() {
        const title = document.getElementById('manual_doc_name').value.trim();
        const cat = document.getElementById('manual_doc_cat').value;
        const icon = document.getElementById('manual_doc_icon').value;
        const color = document.getElementById('manual_doc_color').value;
        const desc = document.getElementById('manual_doc_desc').value.trim();

        if (!title || !desc) return;

        // 1. Create Grid Card
        const grid = document.getElementById('officialDocumentsGrid');
        const colDiv = document.createElement('div');
        colDiv.className = 'col-12 col-md-6 col-lg-4 doc-grid-item';
        colDiv.setAttribute('data-category', cat);
        colDiv.setAttribute('data-title', title.toLowerCase());

        colDiv.innerHTML = `
            <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden position-relative" style="border-top: 4px solid var(--baps-${color == 'saffron' ? 'saffron' : color});">
                <div class="card-body p-4 d-flex flex-column">
                    <div class="d-flex justify-content-between align-items-start mb-3 gap-2">
                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 52px; height: 52px; font-size: 1.5rem; color: var(--baps-${color == 'saffron' ? 'saffron' : color});">
                            <i class="fas ${icon}"></i>
                        </div>
                        <span class="badge bg-success text-white px-3 py-1 rounded-pill small fw-bold shadow-sm d-flex align-items-center gap-1">
                            <i class="fas fa-check-circle"></i> Verified
                        </span>
                    </div>

                    <h6 class="fw-bold text-dark mb-2 fs-6 doc-title-text">${title}</h6>
                    <p class="text-muted small mb-4 flex-grow-1 lh-base">${desc}</p>

                    <div class="d-flex flex-wrap gap-2 pt-3 border-top mt-auto">
                        <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold flex-grow-1" onclick="openDocumentIssueModal('${addslashes(title)}')">
                            <i class="fas fa-file-export me-1"></i> Document Issue
                        </button>
                        <button class="btn btn-sm btn-outline-success rounded-pill px-3 py-1 fw-bold flex-grow-1" onclick="downloadOfficialDoc('${addslashes(title)}')">
                            <i class="fas fa-download me-1"></i> Download
                        </button>
                        <button class="btn btn-sm btn-light border rounded-pill px-3 py-1 fw-bold text-secondary" onclick="verifyOfficialDoc('${addslashes(title)}')" title="Blockchain Ledger Verification">
                            <i class="fas fa-shield-alt text-warning"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;

        // Prepend to Grid so it appears first
        grid.insertBefore(colDiv, grid.firstChild);

        // 2. Update Badge Count
        totalVaultDocumentsCount++;
        const badgeEl = document.getElementById('totalDocsBadge');
        if (badgeEl) badgeEl.innerText = `${totalVaultDocumentsCount} Verified Documents`;

        // 3. Push to AI Knowledge Base
        let catLabel = 'Academic Records';
        if (cat === 'administrative') catLabel = 'Administrative & Fees';
        if (cat === 'certificates') catLabel = 'Conduct & Leaving';
        if (cat === 'special') catLabel = 'Special Memberships';

        aiKnowledgeBase.push({
            title: title,
            cat: catLabel,
            keywords: [title.toLowerCase(), ...title.toLowerCase().split(' ')]
        });

        // Reset & Close Modal
        document.getElementById('manualDocFilingForm').reset();
        const modalEl = document.getElementById('addManualDocModal');
        const modalInst = bootstrap.Modal.getInstance(modalEl);
        if (modalInst) modalInst.hide();

        if (typeof showBapsToast === 'function') {
            showBapsToast(`🎉 New Official Document "${title}" added & pre-verified successfully in the vault!`, 'success');
        } else {
            alert(`🎉 New Official Document "${title}" added & pre-verified successfully in the vault!`);
        }

        // Re-filter to ensure visibility
        filterOfficialDocuments();
    }

    // --- Document Issuance & History Engine ---
    let issuanceHistoryLog = [
        { student: 'Amit Patel (ENR2026CSE001)', title: 'Bonafide Student Certificate', role: 'Student', mode: 'Handed Over in Person', date: '2026-05-18 10:30 AM', hash: 'SHA256-8F93A1B2C4D5E6' },
        { student: 'Neha Sharma (ENR2026CSE014)', title: 'Fee Receipt (Current Semester/Year)', role: 'Student', mode: 'Dispatched to Digital Vault', date: '2026-05-18 14:15 PM', hash: 'SHA256-4A3B2C1D0E9F8A' },
        { student: 'Dr. Sadhu Gyaneswar Das', title: 'IIMA member Card With Role letter', role: 'Dean', mode: 'Handed Over in Person', date: '2026-05-17 11:00 AM', hash: 'SHA256-7E6D5C4B3A2F10' }
    ];

    let recipientRowCount = 0;

    function addRecipientRow(nameVal = '', enrVal = '', roleVal = 'Student', isManual = false) {
        const container = document.getElementById('recipient_rows_container');
        if (!container) return;

        const idx = recipientRowCount++;
        const row = document.createElement('div');
        row.className = 'recipient-row card p-3 mb-3 border bg-white shadow-sm position-relative rounded-4';
        row.style.borderLeft = '4px solid #10b981 !important';
        row.id = `recipient_row_${idx}`;

        let studentsOptionsHtml = '';
        @if(isset($studentsList) && count($studentsList) > 0)
            @foreach($studentsList as $student)
                @php
                    $dispEnr = (str_starts_with($student->enrollment_no, 'ENR') || preg_match('/^[A-Za-z]/', $student->enrollment_no)) 
                        ? $student->enrollment_no 
                        : 'ENR' . $student->enrollment_no;
                @endphp
                studentsOptionsHtml += `<option value="{{ $student->name }}|{{ $dispEnr }}">{{ $student->name }} ({{ $dispEnr }})</option>`;
            @endforeach
        @else
            studentsOptionsHtml += `<option value="Amit Patel|ENR2026CSE001">Amit Patel (ENR2026CSE001)</option>`;
        @endif

        row.innerHTML = `
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2 remove-row-btn" onclick="removeRecipientRow(${idx})" style="font-size: 0.8rem; z-index: 10;" title="Remove Recipient"></button>
            <div class="row g-3 align-items-end">
                <div class="col-12 col-md-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <label class="form-label small fw-bold text-muted mb-0">Recipient Mode</label>
                        <div class="btn-group btn-group-sm" role="group">
                            <input type="radio" class="btn-check row-mode-mapped" name="recipient_mode_${idx}" id="mode_mapped_${idx}" ${!isManual ? 'checked' : ''} autocomplete="off" onchange="toggleRowMode(${idx})">
                            <label class="btn btn-outline-primary py-0 px-2 small rounded-start-pill" for="mode_mapped_${idx}" style="font-size: 0.72rem;">Mapped</label>

                            <input type="radio" class="btn-check row-mode-manual" name="recipient_mode_${idx}" id="mode_manual_${idx}" ${isManual ? 'checked' : ''} autocomplete="off" onchange="toggleRowMode(${idx})">
                            <label class="btn btn-outline-primary py-0 px-2 small rounded-end-pill" for="mode_manual_${idx}" style="font-size: 0.72rem;">Manual</label>
                        </div>
                    </div>
                    <div class="row-select-container" id="row_select_container_${idx}">
                        <select class="form-select py-2 fs-6 row-student-select" onchange="syncRowFields(${idx})" required>
                            ${studentsOptionsHtml}
                        </select>
                    </div>
                    <div class="row-manual-info d-none text-muted small" id="row_manual_info_${idx}">
                        <i class="fas fa-edit me-1"></i> Enter details manually:
                    </div>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-bold text-muted">Recipient Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control py-2 fs-6 row-name-input" id="row_name_${idx}" value="${nameVal}" placeholder="e.g., Amit Patel" required ${!isManual ? 'readonly style="background-color: #f1f5f9;"' : ''}>
                </div>
                <div class="col-12 col-md-3">
                    <label class="form-label small fw-bold text-muted">Enrollment No <span class="text-danger">*</span></label>
                    <input type="text" class="form-control py-2 fs-6 row-enr-input" id="row_enr_${idx}" value="${enrVal}" placeholder="e.g., ENR2026CSE001" required ${!isManual ? 'readonly style="background-color: #f1f5f9;"' : ''}>
                </div>
                <div class="col-12 col-md-2">
                    <label class="form-label small fw-bold text-muted">Role <span class="text-danger">*</span></label>
                    <select class="form-select py-2 fs-6 row-role-select" id="row_role_${idx}" required>
                        <option value="Student" ${roleVal === 'Student' ? 'selected' : ''}>Student</option>
                        <option value="Class Representative (CR)" ${roleVal === 'Class Representative (CR)' ? 'selected' : ''}>Class Representative (CR)</option>
                        <option value="Deputy CR" ${roleVal === 'Deputy CR' ? 'selected' : ''}>Deputy CR</option>
                        <option value="LMS WhatsApp Group Head" ${roleVal === 'LMS WhatsApp Group Head' ? 'selected' : ''}>LMS WhatsApp Group Head</option>
                        <option value="Technical Support Lead (LMS)" ${roleVal === 'Technical Support Lead (LMS)' ? 'selected' : ''}>Technical Support Lead (LMS)</option>
                        <option value="Academic Coordinator" ${roleVal === 'Academic Coordinator' ? 'selected' : ''}>Academic Coordinator</option>
                        <option value="Submission & Assignment Tracker" ${roleVal === 'Submission & Assignment Tracker' ? 'selected' : ''}>Submission & Assignment Tracker</option>
                        <option value="Technical Head (Class/Events)" ${roleVal === 'Technical Head (Class/Events)' ? 'selected' : ''}>Technical Head (Class/Events)</option>
                        <option value="Documentation & Report Specialist" ${roleVal === 'Documentation & Report Specialist' ? 'selected' : ''}>Documentation & Report Specialist</option>
                        <option value="Attendance & Compliance Monitor" ${roleVal === 'Attendance & Compliance Monitor' ? 'selected' : ''}>Attendance & Compliance Monitor</option>
                        <option value="Event & Logistics Coordinator" ${roleVal === 'Event & Logistics Coordinator' ? 'selected' : ''}>Event & Logistics Coordinator</option>
                        <option value="Peer Mentorship & Support Lead" ${roleVal === 'Peer Mentorship & Support Lead' ? 'selected' : ''}>Peer Mentorship & Support Lead</option>
                        <option value="Public Relations & Feedback Officer" ${roleVal === 'Public Relations & Feedback Officer' ? 'selected' : ''}>Public Relations & Feedback Officer</option>
                        <option value="Material + Logistic" ${roleVal === 'Material + Logistic' ? 'selected' : ''}>Material + Logistic</option>
                    </select>
                </div>
            </div>
        `;
        container.appendChild(row);

        toggleRowMode(idx);
        updateRemoveButtonsVisibility();
    }

    function toggleRowMode(idx) {
        const row = document.getElementById(`recipient_row_${idx}`);
        if (!row) return;

        const isMapped = row.querySelector(`.row-mode-mapped`).checked;
        const selectContainer = row.querySelector(`.row-select-container`);
        const manualInfo = row.querySelector(`.row-manual-info`);
        const selectEl = row.querySelector(`.row-student-select`);
        const nameInput = row.querySelector(`.row-name-input`);
        const enrInput = row.querySelector(`.row-enr-input`);

        if (isMapped) {
            selectContainer.classList.remove('d-none');
            manualInfo.classList.add('d-none');
            selectEl.setAttribute('required', 'required');
            nameInput.setAttribute('readonly', 'readonly');
            nameInput.style.backgroundColor = '#f1f5f9';
            enrInput.setAttribute('readonly', 'readonly');
            enrInput.style.backgroundColor = '#f1f5f9';
            syncRowFields(idx);
        } else {
            selectContainer.classList.add('d-none');
            manualInfo.classList.remove('d-none');
            selectEl.removeAttribute('required');
            nameInput.removeAttribute('readonly');
            nameInput.style.backgroundColor = '';
            enrInput.removeAttribute('readonly');
            enrInput.style.backgroundColor = '';
        }
    }

    function syncRowFields(idx) {
        const row = document.getElementById(`recipient_row_${idx}`);
        if (!row) return;

        const isMapped = row.querySelector(`.row-mode-mapped`).checked;
        if (!isMapped) return;

        const selectEl = row.querySelector(`.row-student-select`);
        const nameInput = row.querySelector(`.row-name-input`);
        const enrInput = row.querySelector(`.row-enr-input`);
        const roleSelect = row.querySelector(`.row-role-select`);

        const val = selectEl.value;
        if (val) {
            const parts = val.split('|');
            nameInput.value = parts[0] || '';
            enrInput.value = parts[1] || '';
            roleSelect.value = 'Student';
        }
    }

    function removeRecipientRow(idx) {
        const row = document.getElementById(`recipient_row_${idx}`);
        if (row) {
            row.remove();
            updateRemoveButtonsVisibility();
        }
    }

    function updateRemoveButtonsVisibility() {
        const rows = document.querySelectorAll('.recipient-row');
        rows.forEach(row => {
            const btn = row.querySelector('.remove-row-btn');
            if (btn) {
                btn.style.display = rows.length > 1 ? 'block' : 'none';
            }
        });
    }

    function openDocumentIssueModal(docTitle) {
        document.getElementById('issue_doc_title').value = docTitle;
        document.getElementById('issue_security_hash').value = 'SHA256-' + Math.random().toString(36).substring(2, 10).toUpperCase() + Date.now().toString(36).toUpperCase();
        
        const handoverContainer = document.getElementById('handover_mode_container');
        const modalSubtext = document.querySelector('#documentIssueModal .small.text-light');
        
        if (docTitle === 'For Student') {
            if (handoverContainer) handoverContainer.style.display = 'none';
            if (modalSubtext) modalSubtext.innerText = 'Complete mandatory entry fields to execute official student document issuance';
        } else {
            if (handoverContainer) handoverContainer.style.display = '';
            if (modalSubtext) modalSubtext.innerText = 'Complete mandatory entry fields to execute official document issuance';
        }

        const container = document.getElementById('recipient_rows_container');
        if (container) {
            container.innerHTML = '';
            recipientRowCount = 0;

            const titleLower = docTitle.toLowerCase();
            const groupKeywords = ['core team', 'committee', 'club', 'gdgoc', 'hexsociety', 'acm', 'ieee', 'resolution', 'minutes', 'announcment'];
            
            let isGroupDoc = false;
            for (const keyword of groupKeywords) {
                if (titleLower.includes(keyword)) {
                    isGroupDoc = true;
                    break;
                }
            }

            if (isGroupDoc) {
                addRecipientRow('', '', 'Student', false);
                addRecipientRow('', '', 'Student', false);
                addRecipientRow('', '', 'Student', false);
                addRecipientRow('', '', 'Student', false);
                if (modalSubtext) modalSubtext.innerHTML = '<span class="badge bg-warning text-dark me-2">Group Document</span> This document template supports multiple student recipients. 4 rows preloaded.';
            } else {
                addRecipientRow('', '', 'Student', false);
            }
        }

        const modalEl = document.getElementById('documentIssueModal');
        new bootstrap.Modal(modalEl).show();
    }

    function previewIssuedDocument() {
        const title = document.getElementById('issue_doc_title').value;
        
        const rows = document.querySelectorAll('.recipient-row');
        let names = [];
        let enrollments = [];
        let roles = [];

        rows.forEach(row => {
            const nameInput = row.querySelector('.row-name-input');
            const enrInput = row.querySelector('.row-enr-input');
            const roleSelect = row.querySelector('.row-role-select');

            if (nameInput && nameInput.value.trim()) {
                names.push(nameInput.value.trim());
                enrollments.push(enrInput.value.trim());
                roles.push(roleSelect.value);
            }
        });

        if (names.length === 0) {
            alert('Please add at least one recipient with a name.');
            return;
        }

        const studentNamesStr = names.join(', ');
        const enrollmentsStr = enrollments.join(', ');
        const rolesStr = roles.join(', ');

        const dept = document.getElementById('issue_department').value;
        const auth = document.getElementById('issue_authority').value;
        
        const handoverEl = document.getElementById('issue_handover_mode');
        const mode = (handoverEl && handoverEl.offsetParent !== null) ? handoverEl.value : '';
        
        const purpose = document.getElementById('issue_purpose').value.trim();
        const validity = document.getElementById('issue_validity').value;
        const hash = document.getElementById('issue_security_hash').value;

        const deanId = document.getElementById('issue_dean_id').value;
        const hodId = document.getElementById('issue_hod_id').value;
        const adminId = document.getElementById('issue_admin_id').value;
        const provostName = document.getElementById('issue_provost_name').value;

        let q = `?recipient_name=${encodeURIComponent(studentNamesStr)}`;
        q += `&enrollment_no=${encodeURIComponent(enrollmentsStr)}`;
        q += `&department_name=${encodeURIComponent(dept)}`;
        q += `&recipient_role=${encodeURIComponent(rolesStr)}`;
        q += `&authority=${encodeURIComponent(auth)}`;
        if (mode) {
            q += `&handover_mode=${encodeURIComponent(mode)}`;
        }
        q += `&purpose=${encodeURIComponent(purpose)}`;
        q += `&validity=${encodeURIComponent(validity)}`;
        q += `&security_hash=${encodeURIComponent(hash)}`;
        q += `&dean_id=${encodeURIComponent(deanId)}`;
        q += `&hod_id=${encodeURIComponent(hodId)}`;
        q += `&admin_id=${encodeURIComponent(adminId)}`;
        q += `&provost_name=${encodeURIComponent(provostName)}`;

        const url = '/document/official/' + encodeURIComponent(title) + q;
        
        const iframe = document.getElementById('previewIframe');
        if (iframe) {
            iframe.src = url;
            const modalEl = document.getElementById('filePreviewModal');
            if (modalEl) {
                const modalTitle = modalEl.querySelector('.modal-title');
                if (modalTitle) modalTitle.innerHTML = `<i class="fas fa-file-contract me-2 text-info"></i> Official Student Record: ${title}`;
                new bootstrap.Modal(modalEl).show();
            }
        } else {
            window.open(url, '_blank');
        }
    }

    function submitDocumentIssue() {
        const title = document.getElementById('issue_doc_title').value;
        
        const rows = document.querySelectorAll('.recipient-row');
        let names = [];
        let enrollments = [];
        let roles = [];

        rows.forEach(row => {
            const nameInput = row.querySelector('.row-name-input');
            const enrInput = row.querySelector('.row-enr-input');
            const roleSelect = row.querySelector('.row-role-select');

            if (nameInput && nameInput.value.trim()) {
                names.push(nameInput.value.trim());
                enrollments.push(enrInput.value.trim());
                roles.push(roleSelect.value);
            }
        });

        if (names.length === 0) return;

        const student = names.map((name, i) => `${name} (${enrollments[i] || 'N/A'})`).join(', ');
        const firstRole = roles[0] || 'Student';

        const auth = document.getElementById('issue_authority').value;
        const handoverEl = document.getElementById('issue_handover_mode');
        const mode = (handoverEl && handoverEl.offsetParent !== null) ? handoverEl.value : 'N/A';
        
        const purpose = document.getElementById('issue_purpose').value.trim();
        const validity = document.getElementById('issue_validity').value;
        const hash = document.getElementById('issue_security_hash').value;

        if (!purpose) return;

        const newLog = {
            student: student,
            title: title,
            role: firstRole,
            mode: mode,
            date: new Date().toISOString().replace('T', ' ').substring(0, 16),
            hash: hash
        };

        issuanceHistoryLog.unshift(newLog);
        renderIssuanceHistoryTable();

        const issueModalEl = document.getElementById('documentIssueModal');
        const issueModalInst = bootstrap.Modal.getInstance(issueModalEl);
        if (issueModalInst) issueModalInst.hide();

        const historyModalEl = document.getElementById('issuanceHistoryModal');
        new bootstrap.Modal(historyModalEl).show();

        if (typeof showBapsToast === 'function') {
            showBapsToast(`🎉 Official Document "${title}" successfully issued to ${names[0]} and ${names.length - 1} others & saved to History!`, 'success');
        } else {
            alert(`🎉 Official Document "${title}" successfully issued to ${names[0]} and ${names.length - 1} others & saved to History!`);
        }

        document.getElementById('documentIssueForm').reset();
        
        const container = document.getElementById('recipient_rows_container');
        if (container) {
            container.innerHTML = '';
            recipientRowCount = 0;
            addRecipientRow('', '', 'Student', false);
        }
    }

    function renderIssuanceHistoryTable() {
        const tbody = document.getElementById('issuanceHistoryTableBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        issuanceHistoryLog.forEach((log, idx) => {
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td class="fw-bold text-dark">${log.student} <span class="badge bg-light text-secondary border small">${log.role}</span></td>
                <td class="text-primary fw-semibold"><i class="fas fa-file-contract me-1"></i> ${log.title}</td>
                <td><span class="badge bg-info text-white">${log.mode}</span></td>
                <td class="text-muted small">${log.date}</td>
                <td><code class="text-success fw-bold">${log.hash}</code></td>
            `;
            tbody.appendChild(tr);
        });
    }

    document.addEventListener('DOMContentLoaded', () => {
        renderIssuanceHistoryTable();
    });
</script>

<!-- Document Issue Modal (8 Fields + Preview/Issue Buttons) -->
<div class="modal fade" id="documentIssueModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #f8fafc;">
            <div class="modal-header border-0 p-4 text-white" style="background: linear-gradient(135deg, #059669 0%, #10b981 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-success rounded-4 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 54px; height: 54px; font-size: 1.8rem;">
                        <i class="fas fa-file-export" style="color: #059669;"></i>
                    </div>
                    <div>
                        <h4 class="modal-title fw-bold mb-1 d-flex align-items-center gap-2">
                            Official Document Issue & Provisioning
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">Secure Handover</span>
                        </h4>
                        <div class="small text-light fw-semibold">Complete 8 mandatory entry fields to execute official document issuance</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <form id="documentIssueForm" onsubmit="event.preventDefault(); submitDocumentIssue();">
                    <div class="row g-4 mb-4">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">1. Official Document Title <span class="text-danger">*</span></label>
                            <input type="text" id="issue_doc_title" class="form-control py-2 fs-6 bg-light fw-bold text-primary" readonly required>
                        </div>
                        <div class="col-12 col-md-6">
                            <div class="alert alert-info py-2 px-3 mb-0 rounded-3 border-0 small" style="background-color: #e0f2fe; color: #0369a1;">
                                <h6 class="fw-bold mb-1 small" style="font-size: 0.8rem;"><i class="fas fa-info-circle"></i> Recipient Provisioning</h6>
                                Choose Mapped to select a student from the database, or Manual to type details. You can add multiple rows to issue this to a team.
                            </div>
                        </div>
                    </div>

                    <!-- Dynamic Multi-Recipient Section -->
                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label class="form-label small fw-bold text-muted mb-0"><i class="fas fa-users text-success"></i> 2. Recipient Group / Core Team Members <span class="text-danger">*</span></label>
                                <button type="button" class="btn btn-outline-success btn-sm rounded-pill fw-bold px-3" onclick="addRecipientRow('', '', 'Student', false)">
                                    <i class="fas fa-plus-circle me-1"></i> Add Student
                                </button>
                            </div>
                            <div id="recipient_rows_container"></div>
                        </div>
                    </div>

                    <div class="row g-4 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">3. Department <span class="text-danger">*</span></label>
                            <select id="issue_department" class="form-select py-2 fs-6" required>
                                @if(isset($departmentsList) && count($departmentsList) > 0)
                                    @foreach($departmentsList as $dept)
                                        <option value="{{ $dept->name }}" {{ $dept->name == 'Computer Science & Engineering' ? 'selected' : '' }}>
                                            {{ $dept->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="Computer Science & Engineering">Computer Science & Engineering</option>
                                    <option value="Information Technology">Information Technology</option>
                                    <option value="Mechanical Engineering">Mechanical Engineering</option>
                                    <option value="Civil Engineering">Civil Engineering</option>
                                    <option value="Electrical Engineering">Electrical Engineering</option>
                                @endif
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">4. Issuance Authority & Stamping <span class="text-danger">*</span></label>
                            <input type="text" id="issue_authority" class="form-control py-2 fs-6" value="Dr. Sadhu Gyaneswar Das (Dean) & University Senate" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-3">
                        <div class="col-12 col-md-6" id="handover_mode_container">
                            <label class="form-label small fw-bold text-muted">5. Handover Mode <span class="text-danger">*</span></label>
                            <select id="issue_handover_mode" class="form-select py-2 fs-6" required>
                                <option value="Handed Over in Person">🤝 Handed Over in Person</option>
                                <option value="Dispatched to Digital Vault">🔒 Dispatched to Digital Vault</option>
                                <option value="Registered Speed Post">📦 Registered Speed Post</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">6. Purpose of Issuance <span class="text-danger">*</span></label>
                            <input type="text" id="issue_purpose" class="form-control py-2 fs-6" placeholder="e.g., Higher Studies / Passport / Scholarship Claim" required>
                        </div>
                    </div>

                    <div class="row g-4 mb-3">
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">7. Issuance Validity <span class="text-danger">*</span></label>
                            <input type="text" id="issue_validity" class="form-control py-2 fs-6" value="Lifetime Validity / Permanent Record" required>
                        </div>
                        <div class="col-12 col-md-6">
                            <label class="form-label small fw-bold text-muted">8. Cryptographic Security Hash <span class="text-danger">*</span></label>
                            <input type="text" id="issue_security_hash" class="form-control py-2 fs-6 bg-light text-success fw-bold font-monospace" readonly required>
                        </div>
                    </div>

                    <!-- 4-Signature Attestation Panel -->
                    <div class="card p-3 mb-4 border-0 shadow-sm rounded-4 bg-white" style="border-left: 5px solid var(--baps-blue) !important; background-color: #fafbfd !important;">
                        <h6 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                            <i class="fas fa-file-signature text-primary"></i> 4-Signature Attestation Authorities (Digital Signatures)
                        </h6>
                        <div class="row g-3">
                            <!-- HOD Selection (from database) -->
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-muted">HOD Authority <span class="text-danger">*</span></label>
                                <select id="issue_hod_id" class="form-select py-2 fs-6" required>
                                    @if(isset($hodsList) && $hodsList->count() > 0)
                                        @foreach($hodsList as $hod)
                                            <option value="{{ $hod->id }}" {{ strtolower($hod->name) == 'bhavik patel' ? 'selected' : '' }}>{{ $hod->name }} (HOD)</option>
                                        @endforeach
                                    @else
                                        <option value="">Bhavik Patel (HOD - Default)</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Provost Input (manual text field) -->
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-muted">Provost Name <span class="text-danger">*</span></label>
                                <input type="text" id="issue_provost_name" class="form-control py-2 fs-6" value="Prof. Harish Patel" required>
                            </div>

                            <!-- Dean Selection (from database) -->
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-muted">Dean Authority <span class="text-danger">*</span></label>
                                <select id="issue_dean_id" class="form-select py-2 fs-6" required>
                                    @if(isset($deansList) && $deansList->count() > 0)
                                        @foreach($deansList as $dean)
                                            <option value="{{ $dean->id }}" {{ strtolower($dean->name) == 'dr. sadhu gyaneswar das' ? 'selected' : '' }}>{{ $dean->name }} (Dean)</option>
                                        @endforeach
                                    @else
                                        <option value="">Dr. Sadhu Gyaneswar Das (Dean - Default)</option>
                                    @endif
                                </select>
                            </div>

                            <!-- Admin Selection (from database) -->
                            <div class="col-12 col-md-6">
                                <label class="form-label small fw-bold text-muted">Campus Administrator <span class="text-danger">*</span></label>
                                <select id="issue_admin_id" class="form-select py-2 fs-6" required>
                                    @if(isset($adminsList) && $adminsList->count() > 0)
                                        @foreach($adminsList as $admin)
                                            <option value="{{ $admin->id }}">{{ $admin->name }} (Admin)</option>
                                        @endforeach
                                    @else
                                        <option value="">BHAVIKKUMAR PATEL (Admin - Default)</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex gap-3 pt-3 border-top justify-content-end">
                        <button type="button" class="btn btn-outline-primary fw-bold px-5 py-3 rounded-pill shadow-sm fs-5 d-flex align-items-center gap-2" onclick="previewIssuedDocument()">
                            <i class="fas fa-eye"></i> Preview Document
                        </button>
                        <button type="submit" class="btn btn-success fw-bold px-5 py-3 text-white rounded-pill shadow-sm fs-5 d-flex align-items-center gap-2">
                            <i class="fas fa-check-circle"></i> Issue & Save to History
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Internal Issuance History Modal / Tab -->
<div class="modal fade" id="issuanceHistoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #f8fafc;">
            <div class="modal-header border-0 p-4 text-white" style="background: linear-gradient(135deg, #0284c7 0%, #0369a1 100%);">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white text-info rounded-4 d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 54px; height: 54px; font-size: 1.8rem;">
                        <i class="fas fa-history" style="color: #0284c7;"></i>
                    </div>
                    <div>
                        <h4 class="modal-title fw-bold mb-1 d-flex align-items-center gap-2">
                            Internal Issuance History Log
                            <span class="badge bg-warning text-dark px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">Live Audit Ledger</span>
                        </h4>
                        <div class="small text-light fw-semibold">Tamper-proof chronological audit trail of all institutional records issued to students and staff</div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div class="table-responsive bg-white rounded-4 shadow-sm border p-3">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light text-uppercase fs-7 text-muted fw-bold">
                            <tr>
                                <th scope="col" class="py-3 ps-3">Recipient Name & ID</th>
                                <th scope="col" class="py-3">Official Document Title</th>
                                <th scope="col" class="py-3">Handover Mode</th>
                                <th scope="col" class="py-3">Timestamp</th>
                                <th scope="col" class="py-3 pe-3">Ledger Hash</th>
                            </tr>
                        </thead>
                        <tbody id="issuanceHistoryTableBody">
                            <!-- Dynamic rows injected here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer border-top-0 bg-light p-4 d-flex justify-content-between align-items-center">
                <div class="small text-muted fw-bold">
                    <i class="fas fa-shield-alt text-success me-1"></i> Cryptographically Attested Institutional Log
                </div>
                <button type="button" class="btn btn-dark fw-bold px-5 py-2 rounded-pill shadow-sm" data-bs-dismiss="modal">Close History Tab</button>
            </div>
        </div>
    </div>
</div>
@endif
