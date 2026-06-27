@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
        <h3 class="mb-0">Subject-wise Results & Grading</h3>
    </div>
    <div class="d-flex gap-2">
        <ul class="nav nav-pills bg-white shadow-sm rounded-pill p-1" id="gradingModeTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill px-3 py-1 fw-bold small" data-bs-toggle="pill" data-bs-target="#mode-student" type="button"><i class="fas fa-user me-1"></i> Student Mode</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill px-3 py-1 fw-bold small" data-bs-toggle="pill" data-bs-target="#mode-course" type="button"><i class="fas fa-book me-1"></i> Course Mode</button>
            </li>
        </ul>
        <div class="btn-group shadow-sm">
            <a href="/admin/exam/schedule" class="btn btn-outline-primary btn-sm">Schedule</a>
            <a href="/admin/exam/seating" class="btn btn-outline-primary btn-sm">Seating</a>
        </div>
    </div>
</div>

<div class="tab-content">
    {{-- STUDENT WISE MODE --}}
    <div class="tab-pane fade show active" id="mode-student">
        <div class="row">
            <div class="col-md-4">
                <div class="card p-4 shadow-sm border-0 glass-card">
                    <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-id-card me-2"></i> Student Performance Sheet</h5>
                    <form action="/admin/exam/results" method="POST" id="marksForm">
                        @csrf
                        <div class="mb-3">
                            <label class="small fw-bold">Select Student</label>
                            <select name="user_id" id="studentSelect" class="form-select border-0 bg-light shadow-sm" required>
                                <option value="">-- Choose Student --</option>
                                @foreach($students as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }} ({{ $s->enrollment_no }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div id="subjectsContainer" class="mt-4" style="display: none;">
                            <h6 class="fw-bold text-muted border-bottom pb-2 mb-3">Enrolled Subjects & PBLs</h6>
                            <div id="subjectsList"></div>
                        </div>

                        <div id="formActions" style="display: none;">
                            <div class="mb-3 mt-4">
                                <label class="small fw-bold">Exam Title</label>
                                <input name="exam_title" class="form-control border-0 bg-light shadow-sm" value="University Examination 2026" required>
                            </div>
                            
                            <div class="mb-3">
                                <label class="small fw-bold">Faculty Remarks</label>
                                <textarea name="remarks" class="form-control border-0 bg-light shadow-sm" rows="2" placeholder="Overall performance remarks..."></textarea>
                            </div>
                            <button class="btn btn-primary w-100 fw-bold shadow-sm py-2">
                                <i class="fas fa-check-double me-2"></i> Finalize Student Results
                            </button>
                        </div>

                        <div id="emptyMessage" class="text-center py-4 text-muted small" style="display: none;">
                            No enrollments found for this student.
                        </div>
                        <div id="loadingSpinner" class="text-center py-4" style="display: none;">
                            <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="col-md-8">
                <div class="card border-0 shadow-sm overflow-hidden glass-card">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">Academic Performance Registry</h5>
                        <span class="badge bg-primary">AY 2026</span>
                    </div>
                    <div class="accordion p-3" id="resultsAccordion">
                        @php $groupedResults = $results->groupBy('user_id'); @endphp
                        @foreach($groupedResults as $userId => $userResults)
                            @php $student = $userResults->first()->user; @endphp
                            <div class="accordion-item border mb-2 shadow-xs rounded overflow-hidden">
                                <h2 class="accordion-header" id="heading{{$userId}}">
                                    <button class="accordion-button collapsed bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{$userId}}" aria-expanded="false" aria-controls="collapse{{$userId}}">
                                        <div class="d-flex justify-content-between w-100 pe-3 align-items-center">
                                            <div>
                                                <div class="fw-bold text-dark">{{ $student->name }}</div>
                                                <div class="text-muted" style="font-size: 0.75rem;">{{ $student->enrollment_no }}</div>
                                            </div>
                                            <div>
                                                <span class="badge bg-light text-dark border">{{ $userResults->count() }} Subjects</span>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="collapse{{$userId}}" class="accordion-collapse collapse" aria-labelledby="heading{{$userId}}" data-bs-parent="#resultsAccordion">
                                    <div class="accordion-body p-0">
                                        <!-- Actions Bar -->
                                        <div class="bg-light p-2 text-end border-bottom">
                                            <a href="/admin/exam/results/student/{{$userId}}/print-gradesheet" target="_blank" class="btn btn-primary btn-sm rounded-pill shadow-xs" title="Print Comprehensive Grade Sheet">
                                                <i class="fas fa-print me-1"></i> Print Full Grade Sheet
                                            </a>
                                        </div>
                                        <!-- Subjects Table -->
                                        <table class="table table-hover mb-0 bg-white small table-borderless">
                                            <thead class="table-light">
                                                <tr>
                                                    <th class="ps-3">Subject</th>
                                                    <th class="text-center">Type</th>
                                                    <th class="text-center">Marks Detail (I+P+E)</th>
                                                    <th class="text-center">Total</th>
                                                    <th>Grade</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($userResults as $r)
                                                <tr class="border-bottom">
                                                    <td class="ps-3">
                                                        <div class="fw-bold text-primary">{{ $r->course->title }}</div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge {{ ($r->course->type ?? 'theory') == 'pbl' ? 'bg-warning text-dark' : 'bg-info' }}">
                                                            {{ strtoupper($r->course->type ?? 'Theory') }}
                                                        </span>
                                                    </td>
                                                    <td class="text-center text-muted">
                                                        @if(($r->course->type ?? 'theory') == 'pbl')
                                                            {{ number_format($r->total_obtained, 2) }} (PBL)
                                                        @else
                                                            {{ number_format($r->internal_marks, 2) }} + {{ number_format($r->practical_marks, 2) }} + {{ number_format($r->external_marks_final, 2) }}
                                                        @endif
                                                    </td>
                                                    <td class="text-center fw-bold text-success">{{ number_format($r->total_obtained, 2) }}</td>
                                                    <td><span class="badge {{ $r->grade == 'F' ? 'bg-danger' : 'bg-success' }}">{{ $r->grade }}</span></td>
                                                    <td>
                                                        <div class="d-flex gap-1">
                                                            <a href="/admin/exam/results/{{$r->id}}/print" target="_blank" class="btn btn-light btn-sm rounded-pill border shadow-xs" title="Print Single Subject">
                                                                <i class="fas fa-file-alt text-secondary"></i>
                                                            </a>
                                                            <button class="btn btn-warning btn-sm rounded-pill shadow-xs text-dark" data-bs-toggle="collapse" data-bs-target="#editRow{{$r->id}}" title="Edit Marks">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                        </div>
                                                    </td>
                                                </tr>

                                                <!-- Inline Edit Form Row -->
                                                <tr id="editRow{{$r->id}}" class="collapse bg-light">
                                                    <td colspan="6" class="p-3 border-bottom shadow-inner">
                                                        <form action="/admin/exam/results/{{$r->id}}/update" method="POST" class="d-flex align-items-center gap-4 flex-wrap">
                                                            @csrf
                                                            <div class="fw-bold text-primary small"><i class="fas fa-pen me-1"></i> Quick Edit</div>
                                                            @if(($r->course->type ?? 'theory') == 'pbl')
                                                                <div class="d-flex gap-2 align-items-center">
                                                                    <label class="small fw-bold mb-0 text-muted">PBL Marks (Max 100):</label>
                                                                    <input type="number" step="0.5" name="obtained_marks_pbl" class="form-control form-control-sm w-auto" value="{{ $r->total_obtained }}" required>
                                                                </div>
                                                            @else
                                                                <div class="d-flex gap-2 align-items-center">
                                                                    <label class="small fw-bold mb-0 text-muted">Internal (60):</label>
                                                                    <input type="number" step="0.5" name="internal_marks" class="form-control form-control-sm text-center" style="width: 70px;" value="{{ $r->internal_marks }}" required>
                                                                </div>
                                                                <div class="d-flex gap-2 align-items-center">
                                                                    <label class="small fw-bold mb-0 text-muted">Practical (50):</label>
                                                                    <input type="number" step="0.5" name="practical_marks" class="form-control form-control-sm text-center" style="width: 70px;" value="{{ $r->practical_marks }}" required>
                                                                </div>
                                                                <div class="d-flex gap-2 align-items-center">
                                                                    <label class="small fw-bold mb-0 text-muted">Ext. Raw (100):</label>
                                                                    <input type="number" step="0.5" name="external_marks_raw" class="form-control form-control-sm text-center" style="width: 70px;" value="{{ $r->external_marks_raw }}" required>
                                                                </div>
                                                            @endif
                                                            <button type="submit" class="btn btn-primary btn-sm fw-bold shadow-sm ms-auto px-4 rounded-pill">Save Changes</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                                

                                                
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    {{-- COURSE WISE MODE --}}
    <div class="tab-pane fade" id="mode-course">
        <div class="row">
            <div class="col-md-3">
                <div class="card p-3 shadow-sm border-0 glass-card">
                    <h6 class="fw-bold mb-3"><i class="fas fa-search me-2"></i> Find Subject</h6>
                    <input type="text" id="courseFilter" class="form-control form-control-sm mb-3" placeholder="Filter subjects...">
                    <div class="list-group list-group-flush border rounded" id="courseSelectionList" style="max-height: 500px; overflow-y: auto;">
                        @foreach($courses as $c)
                            <button type="button" class="list-group-item list-group-item-action py-2 px-3 small border-bottom course-select-btn" data-id="{{ $c->id }}" data-type="{{ $c->type ?? 'theory' }}" data-title="{{ $c->title }}">
                                <div class="fw-bold text-dark">{{ $c->title }}</div>
                                <div class="text-muted xx-small">ID: {{ $c->id }} | {{ strtoupper($c->type ?? 'Theory') }}</div>
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div id="courseAssessmentCard" class="card shadow-sm border-0 glass-card" style="display: none;">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0 fw-bold" id="selectedCourseTitle">Subject Name</h5>
                            <small class="text-muted" id="selectedCourseMeta">Type: Theory | Max: 100</small>
                        </div>
                        <button class="btn btn-success btn-sm fw-bold px-4 rounded-pill shadow-sm" onclick="submitCourseBatch()">
                            <i class="fas fa-save me-2"></i> Save Batch Results
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <form id="courseBatchForm" action="/admin/exam/results/batch" method="POST">
                            @csrf
                            <input type="hidden" name="course_id" id="batchCourseId">
                            <input type="hidden" name="exam_title" value="University Examination 2026">
                            <div class="table-responsive">
                                <table class="table table-hover align-middle mb-0">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Student</th>
                                            <th class="text-center" id="th-internal">Internal (60)</th>
                                            <th class="text-center" id="th-external">External (100)</th>
                                            <th class="text-center" id="th-pbl" style="display: none;">PBL Marks (100)</th>
                                            <th class="text-center">Final Score</th>
                                            <th class="pe-4">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody id="courseStudentList">
                                        {{-- Populated via JS --}}
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    </div>
                </div>
                <div id="courseSelectPlaceholder" class="card shadow-sm border-0 glass-card text-center py-5">
                    <i class="fas fa-book-open fa-3x text-muted mb-3 opacity-25"></i>
                    <h5 class="text-muted fw-bold">Select a subject from the left to start assessment</h5>
                    <p class="text-muted small">You can grade all students in a subject simultaneously in this mode.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const studentSelect = document.getElementById('studentSelect');
    const subjectsContainer = document.getElementById('subjectsContainer');
    const subjectsList = document.getElementById('subjectsList');
    const formActions = document.getElementById('formActions');
    const emptyMessage = document.getElementById('emptyMessage');
    const loadingSpinner = document.getElementById('loadingSpinner');

    studentSelect.addEventListener('change', function() {
        const studentId = this.value;
        subjectsList.innerHTML = '';
        subjectsContainer.style.display = 'none';
        formActions.style.display = 'none';
        emptyMessage.style.display = 'none';
        
        if (!studentId) return;

        loadingSpinner.style.display = 'block';

        fetch(`/admin/exam/results/${studentId}/enrollments`)
            .then(res => res.json())
            .then(courses => {
                loadingSpinner.style.display = 'none';
                if (courses.length > 0) {
                    subjectsContainer.style.display = 'block';
                    formActions.style.display = 'block';
                    courses.forEach(subject => {
                        const subjectDiv = document.createElement('div');
                        subjectDiv.className = 'p-3 mb-3 border rounded bg-white shadow-xs border-start border-4 ' + (subject.type === 'pbl' ? 'border-warning' : 'border-info');
                        
                        if (subject.type === 'pbl') {
                            subjectDiv.innerHTML = `
                                <div class="fw-bold small mb-2"><i class="fas fa-project-diagram me-2 text-warning"></i> ${subject.title}</div>
                                <div class="input-group input-group-sm">
                                    <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('pbl_${subject.id}', -1)">-</button>
                                    <input name="results[${subject.id}][obtained_marks_pbl]" id="pbl_${subject.id}" type="number" step="0.5" class="form-control text-center bg-light fw-bold" placeholder="PBL Marks (Max 100)" value="0">
                                    <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('pbl_${subject.id}', 1)">+</button>
                                </div>
                            `;
                        } else {
                            subjectDiv.innerHTML = `
                                <div class="fw-bold small mb-2"><i class="fas fa-book me-2 text-info"></i> ${subject.title}</div>
                                <div class="row g-2">
                                    <div class="col-4">
                                        <label class="xx-small text-muted">Internal (60)</label>
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('int_${subject.id}', -1)">-</button>
                                            <input name="results[${subject.id}][internal_marks]" id="int_${subject.id}" type="number" step="0.5" class="form-control text-center bg-light fw-bold px-1" value="0">
                                            <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('int_${subject.id}', 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="xx-small text-muted">Practical (50)</label>
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('prac_${subject.id}', -1)">-</button>
                                            <input name="results[${subject.id}][practical_marks]" id="prac_${subject.id}" type="number" step="0.5" class="form-control text-center bg-light fw-bold px-1" value="0">
                                            <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('prac_${subject.id}', 1)">+</button>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <label class="xx-small text-muted">External (100)</label>
                                        <div class="input-group input-group-sm">
                                            <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('ext_${subject.id}', -1)">-</button>
                                            <input name="results[${subject.id}][external_marks_raw]" id="ext_${subject.id}" type="number" step="0.5" class="form-control text-center bg-light fw-bold px-1" value="0">
                                            <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('ext_${subject.id}', 1)">+</button>
                                        </div>
                                    </div>
                                </div>
                            `;
                        }
                        subjectsList.appendChild(subjectDiv);
                    });
                } else {
                    emptyMessage.style.display = 'block';
                }
            })
            .catch(err => {
                loadingSpinner.style.display = 'none';
                console.error(err);
            });
    });

    function adjustMarks(id, amount) {
        const input = document.getElementById(id);
        let val = parseFloat(input.value) || 0;
        val += amount;
        if (val < 0) val = 0;
        if (id.startsWith('int_') && val > 60) val = 60;
        if ((id.startsWith('ext_') || id.startsWith('pbl_')) && val > 100) val = 100;
        input.value = val;
        
        // If course mode, update the final score preview
        if (id.includes('batch_')) {
            updateFinalPreview(id.split('_').pop());
        }
    }

    // COURSE MODE LOGIC
    const courseBtns = document.querySelectorAll('.course-select-btn');
    const courseAssessmentCard = document.getElementById('courseAssessmentCard');
    const courseSelectPlaceholder = document.getElementById('courseSelectPlaceholder');
    const courseStudentList = document.getElementById('courseStudentList');
    const batchCourseId = document.getElementById('batchCourseId');
    const selectedCourseTitle = document.getElementById('selectedCourseTitle');
    const selectedCourseMeta = document.getElementById('selectedCourseMeta');

    const thInternal = document.getElementById('th-internal');
    const thExternal = document.getElementById('th-external');
    const thPbl = document.getElementById('th-pbl');

    courseBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const courseId = this.dataset.id;
            const type = this.dataset.type;
            const title = this.dataset.title;

            // Update UI State
            courseBtns.forEach(b => b.classList.remove('active', 'bg-primary', 'text-white'));
            this.classList.add('active', 'bg-primary', 'text-white');

            selectedCourseTitle.innerText = title;
            selectedCourseMeta.innerText = `Type: ${type.toUpperCase()} | Max: 100`;
            batchCourseId.value = courseId;

            // Toggle Columns
            if (type === 'pbl') {
                thInternal.style.display = 'none';
                thExternal.style.display = 'none';
                thPbl.style.display = 'table-cell';
            } else {
                thInternal.style.display = 'table-cell';
                thExternal.style.display = 'table-cell';
                thPbl.style.display = 'none';
            }

            courseSelectPlaceholder.style.display = 'none';
            courseAssessmentCard.style.display = 'block';
            courseStudentList.innerHTML = '<tr><td colspan="5" class="text-center py-4"><div class="spinner-border spinner-border-sm text-primary"></div> Loading students...</td></tr>';

            fetch(`/admin/exam/results/course/${courseId}/students`)
                .then(res => res.json())
                .then(students => {
                    courseStudentList.innerHTML = '';
                    if (students.length === 0) {
                        courseStudentList.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">No students enrolled in this subject.</td></tr>';
                        return;
                    }

                    students.forEach(s => {
                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td class="ps-4">
                                <div class="fw-bold">${s.name}</div>
                                <div class="text-muted small">${s.enrollment_no}</div>
                                <input type="hidden" name="results[${s.id}][user_id]" value="${s.id}">
                            </td>
                            ${type === 'pbl' ? `
                                <td class="text-center">
                                    <div class="input-group input-group-sm mx-auto" style="width: 130px;">
                                        <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('batch_pbl_${s.id}', -5)">-</button>
                                        <input name="results[${s.id}][obtained_marks_pbl]" id="batch_pbl_${s.id}" type="number" class="form-control text-center fw-bold" value="0" oninput="updateFinalPreview(${s.id})">
                                        <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('batch_pbl_${s.id}', 5)">+</button>
                                    </div>
                                </td>
                            ` : `
                                <td class="text-center">
                                    <div class="input-group input-group-sm mx-auto" style="width: 120px;">
                                        <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('batch_int_${s.id}', -2)">-</button>
                                        <input name="results[${s.id}][internal_marks]" id="batch_int_${s.id}" type="number" class="form-control text-center fw-bold" value="0" oninput="updateFinalPreview(${s.id})">
                                        <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('batch_int_${s.id}', 2)">+</button>
                                    </div>
                                </td>
                                <td class="text-center">
                                    <div class="input-group input-group-sm mx-auto" style="width: 120px;">
                                        <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('batch_ext_${s.id}', -5)">-</button>
                                        <input name="results[${s.id}][external_marks_raw]" id="batch_ext_${s.id}" type="number" class="form-control text-center fw-bold" value="0" oninput="updateFinalPreview(${s.id})">
                                        <button type="button" class="btn btn-outline-secondary" onclick="adjustMarks('batch_ext_${s.id}', 5)">+</button>
                                    </div>
                                </td>
                            `}
                            <td class="text-center fw-bold text-primary" id="preview_total_${s.id}">0.00</td>
                            <td class="pe-4">
                                <input name="results[${s.id}][remarks]" class="form-control form-control-sm border-0 bg-light" placeholder="Note...">
                            </td>
                        `;
                        courseStudentList.appendChild(tr);
                    });
                });
        });
    });

    function updateFinalPreview(studentId) {
        const intInput = document.getElementById(`batch_int_${studentId}`);
        const extInput = document.getElementById(`batch_ext_${studentId}`);
        const pblInput = document.getElementById(`batch_pbl_${studentId}`);
        const totalDisp = document.getElementById(`preview_total_${studentId}`);

        if (pblInput) {
            totalDisp.innerText = parseFloat(pblInput.value || 0).toFixed(2);
        } else {
            const total = parseFloat(intInput.value || 0) + (parseFloat(extInput.value || 0) * 0.4);
            totalDisp.innerText = total.toFixed(2);
        }
    }

    function submitCourseBatch() {
        if (confirm('Are you sure you want to finalize results for the entire batch?')) {
            document.getElementById('courseBatchForm').submit();
        }
    }

    // Filter Logic
    document.getElementById('courseFilter').addEventListener('keyup', function() {
        const query = this.value.toLowerCase();
        document.querySelectorAll('.course-select-btn').forEach(btn => {
            const text = btn.innerText.toLowerCase();
            btn.style.display = text.includes(query) ? 'block' : 'none';
        });
    });
</script>

<style>
    .xx-small { font-size: 0.65rem; font-weight: bold; text-transform: uppercase; }
    .shadow-xs { box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
</style>

@endsection
