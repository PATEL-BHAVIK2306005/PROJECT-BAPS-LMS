@if(in_array(session('user_role'), ['admin', 'cr', 'hod', 'dean', 'office-assistant']) || session('staff_name') == 'Rajunakum Sir')
<div class="tab-pane fade" id="tab-exams" role="tabpanel">
    @php
        $examSchedules = \App\Models\ExamSchedule::with('department')->get();
        $departments = \App\Models\Department::all();
        $seatingArrangements = \App\Models\SeatingArrangement::with('examSchedule')->get();
        $courses = \App\Models\Course::all();
        $results = \App\Models\Result::with(['user', 'course'])->latest()->take(20)->get();
        $students = \App\Models\User::where('role', 'student')->get();
    @endphp

    <!-- Exam Center Header Banner -->
    <div class="content-card mb-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; border: none;">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
            <div>
                <div class="d-flex align-items-center gap-2 mb-2">
                    <span class="badge bg-danger px-3 py-2 rounded-pill fw-bold text-uppercase" style="letter-spacing: 1px;"><i class="fas fa-shield-alt me-1"></i> Examination Controller</span>
                    <span class="badge bg-warning text-dark px-3 py-2 rounded-pill fw-bold"><i class="fas fa-satellite-dish me-1"></i> Live Session Active</span>
                </div>
                <h3 class="fw-bold mb-1 text-white">Central Examination & Assessment Center</h3>
                <p class="text-white-50 mb-0 small">Automated schedules, AI proctoring surveillance, hall seating allotment, and real-time grading suite.</p>
            </div>
            <div class="d-flex flex-wrap gap-2">
                <a href="/admin/exam/schedule" class="btn btn-warning fw-bold px-3 py-2 rounded-3 text-dark shadow-sm">
                    <i class="fas fa-external-link-alt me-1"></i> Standalone View
                </a>
            </div>
        </div>
    </div>

    <!-- Exam Center Sub Navigation Pills -->
    <ul class="nav nav-pills baps-nav-pills mb-4" id="examSubTabs" role="tablist" style="background: #ffffff; border: 1px solid var(--baps-border);">
        <li class="nav-item" role="presentation">
            <button class="nav-link active baps-tab-btn" data-bs-toggle="pill" data-bs-target="#exam-sub-schedule" type="button" role="tab">
                <i class="fas fa-calendar-check text-success"></i> 1. Exam Schedules
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link baps-tab-btn" data-bs-toggle="pill" data-bs-target="#exam-sub-seating" type="button" role="tab">
                <i class="fas fa-chair text-primary"></i> 2. Hall & Seating Matrix
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link baps-tab-btn" data-bs-toggle="pill" data-bs-target="#exam-sub-proctoring" type="button" role="tab">
                <i class="fas fa-video text-danger"></i> 3. AI Live Proctoring
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link baps-tab-btn" data-bs-toggle="pill" data-bs-target="#exam-sub-results" type="button" role="tab">
                <i class="fas fa-award text-warning"></i> 4. Results & Grading
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link baps-tab-btn" data-bs-toggle="pill" data-bs-target="#exam-sub-quizzes" type="button" role="tab">
                <i class="fas fa-tasks text-purple" style="color: #9333ea;"></i> 5. Quiz & Question Bank
            </button>
        </li>
    </ul>

    <!-- Exam Sub Tab Contents -->
    <div class="tab-content" id="examSubTabsContent">
        
        <!-- SUB-TAB 1: EXAM SCHEDULES -->
        <div class="tab-pane fade show active" id="exam-sub-schedule" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="content-card mb-0 h-100">
                        <div class="content-card-header">
                            <h5 class="content-card-title"><i class="fas fa-plus-circle text-primary"></i> Publish Exam Schedule</h5>
                        </div>
                        <form action="/admin/exam/schedule" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="small fw-bold mb-1 text-dark">Target Department</label>
                                <select name="department_id" class="form-select" required>
                                    @foreach($departments as $d)
                                        <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->code }})</option>
                                    @endforeach
                                    @if($departments->isEmpty())
                                        <option value="1">Computer Science & Engineering</option>
                                        <option value="2">Information Technology</option>
                                        <option value="3">Mechanical Engineering</option>
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold mb-1 text-dark">Exam Title / Semester</label>
                                <input name="title" class="form-control" placeholder="e.g. End-Sem Winter Assessment 2026" required>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold mb-1 text-dark">Exam Date</label>
                                <input name="date" type="date" class="form-control" value="{{ date('Y-m-d', strtotime('+3 days')) }}" required>
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold mb-1 text-dark">Time Slot</label>
                                <input name="time_slot" class="form-control" placeholder="e.g. 10:00 AM - 01:00 PM" value="10:00 AM - 01:00 PM" required>
                            </div>
                            <button type="submit" class="btn action-btn-primary w-100 py-3 fw-bold rounded-3">
                                <i class="fas fa-paper-plane me-2"></i> Publish Timetable Schedule
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="content-card mb-0 h-100">
                        <div class="content-card-header">
                            <h5 class="content-card-title"><i class="fas fa-calendar-alt text-success"></i> Active Examination Timetable</h5>
                            <span class="badge bg-success px-3 py-2 rounded-pill font-monospace">{{ $examSchedules->count() }} Schedules</span>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Department</th>
                                        <th>Exam Title</th>
                                        <th>Date</th>
                                        <th>Time Slot</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($examSchedules as $s)
                                    <tr>
                                        <td><span class="badge bg-light text-dark border fw-bold">{{ $s->department->code ?? 'CSE' }}</span></td>
                                        <td class="fw-bold text-dark">{{ $s->title }}</td>
                                        <td><i class="far fa-calendar-alt text-muted me-1"></i> {{ \Carbon\Carbon::parse($s->date)->format('d M Y') }}</td>
                                        <td><i class="far fa-clock text-muted me-1"></i> {{ $s->time_slot }}</td>
                                        <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">Active Live</span></td>
                                        <td class="text-end">
                                            <a href="/admin/exam/schedule" class="btn btn-sm btn-outline-dark rounded-3"><i class="fas fa-print"></i></a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td><span class="badge bg-light text-dark border fw-bold">CSE</span></td>
                                        <td class="fw-bold text-dark">Mid-Term IPDC & AI Assessment</td>
                                        <td><i class="far fa-calendar-alt text-muted me-1"></i> {{ date('d M Y', strtotime('+2 days')) }}</td>
                                        <td><i class="far fa-clock text-muted me-1"></i> 10:00 AM - 01:00 PM</td>
                                        <td class="text-center"><span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill fw-bold">Active Live</span></td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-dark rounded-3"><i class="fas fa-print"></i></button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><span class="badge bg-light text-dark border fw-bold">IT</span></td>
                                        <td class="fw-bold text-dark">Data Structures & Algorithm Practical</td>
                                        <td><i class="far fa-calendar-alt text-muted me-1"></i> {{ date('d M Y', strtotime('+4 days')) }}</td>
                                        <td><i class="far fa-clock text-muted me-1"></i> 02:00 PM - 05:00 PM</td>
                                        <td class="text-center"><span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-bold">Scheduled</span></td>
                                        <td class="text-end">
                                            <button class="btn btn-sm btn-outline-dark rounded-3"><i class="fas fa-print"></i></button>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 2: SEATING ARRANGEMENT -->
        <div class="tab-pane fade" id="exam-sub-seating" role="tabpanel">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="content-card mb-0 h-100">
                        <div class="content-card-header">
                            <h5 class="content-card-title"><i class="fas fa-chair text-primary"></i> Allocate Exam Hall</h5>
                        </div>
                        <form action="/admin/exam/seating" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="small fw-bold mb-1 text-dark">Select Scheduled Exam</label>
                                <select name="exam_schedule_id" class="form-select" required>
                                    @foreach($examSchedules as $s)
                                        <option value="{{ $s->id }}">{{ $s->title }} ({{ $s->date }})</option>
                                    @endforeach
                                    @if($examSchedules->isEmpty())
                                        <option value="1">End-Sem Assessment 2026 (Winter)</option>
                                    @endif
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold mb-1 text-dark">Hall / Room Number</label>
                                <input name="room_no" class="form-control" placeholder="e.g. Block-B Lecture Hall 204" required>
                            </div>
                            <div class="mb-4">
                                <label class="small fw-bold mb-1 text-dark">Bench Capacity</label>
                                <input name="capacity" type="number" class="form-control" value="60" required>
                            </div>
                            <button type="submit" class="btn action-btn-primary w-100 py-3 fw-bold rounded-3">
                                <i class="fas fa-save me-2"></i> Save Hall Allocation
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="content-card mb-0 h-100">
                        <div class="content-card-header">
                            <h5 class="content-card-title"><i class="fas fa-building text-info"></i> Hall Allocation Matrix</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Schedule Target</th>
                                        <th>Hall / Room</th>
                                        <th>Capacity</th>
                                        <th>Assigned Students</th>
                                        <th class="text-end">Sheet</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($seatingArrangements as $a)
                                    <tr>
                                        <td class="fw-bold">{{ $a->examSchedule->title ?? 'Mid-Sem Assessment' }}</td>
                                        <td><span class="badge bg-dark text-white px-3 py-2 rounded-3">{{ $a->room_no }}</span></td>
                                        <td>{{ $a->capacity }} Benches</td>
                                        <td><span class="badge bg-info text-dark">{{ $a->capacity }} Students</span></td>
                                        <td class="text-end"><a href="/admin/exam/seating" class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1"></i> Print Desk Slip</a></td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="fw-bold">Mid-Term IPDC & AI Assessment</td>
                                        <td><span class="badge bg-dark text-white px-3 py-2 rounded-3">Hall A-101 (Main Auditorium)</span></td>
                                        <td>120 Benches</td>
                                        <td><span class="badge bg-info text-dark">120 Students</span></td>
                                        <td class="text-end"><button class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1"></i> Print Desk Slip</button></td>
                                    </tr>
                                    <tr>
                                        <td class="fw-bold">Data Structures & Algorithm Practical</td>
                                        <td><span class="badge bg-dark text-white px-3 py-2 rounded-3">Lab LH-302 (Computer Wing)</span></td>
                                        <td>45 Terminals</td>
                                        <td><span class="badge bg-info text-dark">45 Students</span></td>
                                        <td class="text-end"><button class="btn btn-sm btn-outline-primary"><i class="fas fa-print me-1"></i> Print Desk Slip</button></td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 3: AI LIVE PROCTORING -->
        <div class="tab-pane fade" id="exam-sub-proctoring" role="tabpanel">
            <div class="content-card mb-0">
                <div class="content-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <span class="status-dot danger"></span>
                        <h5 class="content-card-title mb-0">Live AI Surveillance Feeds</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <span class="badge bg-danger px-3 py-2 rounded-pill font-monospace"><i class="fas fa-shield-virus me-1"></i> Proctor AI Engine V2.4 Active</span>
                    </div>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-md-4">
                        <div class="card bg-dark text-white border-0 rounded-4 overflow-hidden position-relative shadow" style="height: 200px;">
                            <div class="d-flex align-items-center justify-content-center h-100 flex-column">
                                <i class="fas fa-user-circle fs-1 text-white-50 mb-2"></i>
                                <span class="fw-bold">CAM 01 - BHAVIK PATEL</span>
                                <small class="text-success"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> Face Verified (99.8%)</small>
                            </div>
                            <div class="position-absolute bottom-0 start-0 end-0 p-2 bg-dark bg-opacity-75 d-flex justify-content-between small">
                                <span>FPS: 30</span>
                                <span class="badge bg-success">Normal</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-dark text-white border-0 rounded-4 overflow-hidden position-relative shadow border border-danger border-2" style="height: 200px;">
                            <div class="d-flex align-items-center justify-content-center h-100 flex-column">
                                <i class="fas fa-user-circle fs-1 text-danger mb-2"></i>
                                <span class="fw-bold">CAM 02 - STUDENT 2306012</span>
                                <small class="text-danger fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> Multi-Face Detected</small>
                            </div>
                            <div class="position-absolute bottom-0 start-0 end-0 p-2 bg-dark bg-opacity-75 d-flex justify-content-between small">
                                <span>FPS: 30</span>
                                <span class="badge bg-danger">Flagged Alert</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-dark text-white border-0 rounded-4 overflow-hidden position-relative shadow" style="height: 200px;">
                            <div class="d-flex align-items-center justify-content-center h-100 flex-column">
                                <i class="fas fa-user-circle fs-1 text-white-50 mb-2"></i>
                                <span class="fw-bold">CAM 03 - STUDENT 2306045</span>
                                <small class="text-success"><i class="fas fa-circle me-1" style="font-size: 8px;"></i> Gaze Centered</small>
                            </div>
                            <div class="position-absolute bottom-0 start-0 end-0 p-2 bg-dark bg-opacity-75 d-flex justify-content-between small">
                                <span>FPS: 30</span>
                                <span class="badge bg-success">Normal</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 border">
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small fw-bold text-muted"><i class="fas fa-info-circle me-1"></i> Live Proctoring monitors tab switches, background noise, multiple faces, and screen captures.</span>
                        <a href="/admin/exam/live-proctoring" class="btn btn-sm btn-dark px-4 rounded-pill fw-bold">Open Full Proctoring Dashboard</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 4: RESULTS & GRADING -->
        <div class="tab-pane fade" id="exam-sub-results" role="tabpanel">
            <div class="content-card mb-0">
                <div class="content-card-header">
                    <h5 class="content-card-title"><i class="fas fa-chart-line text-warning"></i> Examination Results & Marksheet Gradebook</h5>
                    <a href="/admin/exam/results-grading" class="btn btn-sm btn-primary px-3 rounded-pill fw-bold">Full Gradebook Hub</a>
                </div>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Enrollment No</th>
                                <th>Course Title</th>
                                <th>Marks Obtained</th>
                                <th>Grade</th>
                                <th>SGPA</th>
                                <th class="text-end">Certificate</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($results as $r)
                            <tr>
                                <td class="fw-bold text-dark">{{ $r->user->name ?? 'PATEL BHAVIK' }}</td>
                                <td><span class="badge bg-light text-secondary border font-monospace">{{ $r->user->enrollment_no ?? '2306005' }}</span></td>
                                <td>{{ $r->course->title ?? 'IPDC Values & Leadership' }}</td>
                                <td class="fw-bold text-success">{{ $r->marks ?? '95' }} / 100</td>
                                <td><span class="badge bg-success px-3 py-1 rounded-pill">{{ $r->grade ?? 'A+' }}</span></td>
                                <td class="fw-bold text-primary">{{ $r->sgpa ?? '9.6' }}</td>
                                <td class="text-end"><button class="btn btn-sm btn-outline-dark rounded-3"><i class="fas fa-download me-1"></i> PDF</button></td>
                            </tr>
                            @empty
                            <tr>
                                <td class="fw-bold text-dark">PATEL BHAVIK</td>
                                <td><span class="badge bg-light text-secondary border font-monospace">2306005</span></td>
                                <td>IPDC Values, Ethics & Leadership (IPDC-101)</td>
                                <td class="fw-bold text-success">98 / 100</td>
                                <td><span class="badge bg-success px-3 py-1 rounded-pill">O (Outstanding)</span></td>
                                <td class="fw-bold text-primary">10.0</td>
                                <td class="text-end"><button class="btn btn-sm btn-outline-dark rounded-3"><i class="fas fa-download me-1"></i> PDF</button></td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-dark">SHAH PRIYA</td>
                                <td><span class="badge bg-light text-secondary border font-monospace">2306018</span></td>
                                <td>Advanced Web Engineering & Cloud Fullstack</td>
                                <td class="fw-bold text-success">92 / 100</td>
                                <td><span class="badge bg-success px-3 py-1 rounded-pill">A+</span></td>
                                <td class="fw-bold text-primary">9.4</td>
                                <td class="text-end"><button class="btn btn-sm btn-outline-dark rounded-3"><i class="fas fa-download me-1"></i> PDF</button></td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- SUB-TAB 5: QUIZ & QUESTION BANK -->
        <div class="tab-pane fade" id="exam-sub-quizzes" role="tabpanel">
            <div class="row g-4">
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="innovation-card accent-blue">
                        <div class="innovation-icon"><i class="fas fa-brain"></i></div>
                        <h4 class="innovation-title">AI Question Paper Builder</h4>
                        <p class="innovation-desc">Generate high-rigor multiple choice, coding challenges, and subjective exam papers aligned with Blooms Taxonomy.</p>
                        <a href="/admin/exam/question-bank" class="innovation-btn text-decoration-none text-center">Launch Generator</a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="innovation-card accent-green">
                        <div class="innovation-icon"><i class="fas fa-tasks"></i></div>
                        <h4 class="innovation-title">Live Quizzes & Tests</h4>
                        <p class="innovation-desc">Manage timed online quizzes, automated evaluation engines, and immediate scoreboard releases.</p>
                        <a href="/admin/exam/quiz-management" class="innovation-btn text-decoration-none text-center">Manage Active Quizzes</a>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-4">
                    <div class="innovation-card accent-purple">
                        <div class="innovation-icon"><i class="fas fa-id-card"></i></div>
                        <h4 class="innovation-title">Admit Cards & Hall Tickets</h4>
                        <p class="innovation-desc">Generate and authorize examination hall tickets with digital QR code authentication signatures.</p>
                        <a href="/admin/exam/forms" class="innovation-btn text-decoration-none text-center">Admit Card Vault</a>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endif
