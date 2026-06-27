@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fas fa-tasks text-primary me-2"></i> Assignments Section</h3>
        <p class="text-muted small mb-0">Manage curriculum assignments, external credentials, and institutional progress.</p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="/admin" class="btn btn-outline-dark btn-sm fw-bold rounded-pill px-3 d-flex align-items-center"><i class="fas fa-arrow-left me-1"></i> Dashboard</a>
        <button class="btn btn-sm fw-bold rounded-pill px-3 shadow-sm text-white d-flex align-items-center" style="background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%) !important; border: none;" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
            <i class="fas fa-plus-circle me-1"></i> Create Assignment
        </button>
        <a href="/admin/ipdc/hackerrank/create" class="btn btn-sm fw-bold rounded-pill px-3 shadow-sm text-white d-flex align-items-center" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; border: none;">
            <i class="fab fa-hackerrank me-1"></i> Assign HackerRank
        </a>
        <button class="btn btn-dark btn-sm fw-bold rounded-pill px-3 shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#addStudentCertModal">
            <i class="fas fa-user-plus me-1"></i> Add Student Credential
        </button>
        <button class="btn btn-primary btn-sm fw-bold rounded-pill px-3 shadow-sm d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#uploadAssetModal">
            <i class="fas fa-upload me-1"></i> Upload Asset
        </button>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Tabbed Management Section -->
    <div class="col-lg-8">
        <!-- Tabs Navigation -->
        <div class="card border-0 shadow-sm rounded-4 p-3 mb-4 bg-white">
            <ul class="nav nav-pills nav-pills-custom gap-2" id="ipdcTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active px-4 py-2.5 rounded-pill fw-bold" id="modules-tab" data-bs-toggle="tab" data-bs-target="#modules" type="button" role="tab">
                        <i class="fas fa-book-reader me-2"></i> Curriculum Modules
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2.5 rounded-pill fw-bold" id="assignments-tab" data-bs-toggle="tab" data-bs-target="#assignments" type="button" role="tab">
                        <i class="fas fa-clipboard-list me-2"></i> Assignments Bank
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2.5 rounded-pill fw-bold" id="evaluations-tab" data-bs-toggle="tab" data-bs-target="#evaluations" type="button" role="tab">
                        <i class="fas fa-spell-check me-2"></i> Evaluation Desk
                        @if(count($submissions) > 0)
                            <span class="badge bg-danger ms-1 animate-pulse" style="font-size: 0.65rem;">{{ count($submissions) }}</span>
                        @endif
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link px-4 py-2.5 rounded-pill fw-bold" id="certs-tab" data-bs-toggle="tab" data-bs-target="#certs" type="button" role="tab">
                        <i class="fas fa-certificate me-2"></i> Certification Vault
                    </button>
                </li>
            </ul>
        </div>

        <!-- Tab Content Panes -->
        <div class="tab-content" id="ipdcTabContent">
            <!-- Tab 1: Core IPDC Curriculum Modules -->
            <div class="tab-pane fade show active" id="modules" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0"><i class="fas fa-book-reader text-primary me-2"></i> Core Curriculum & Mapped Subjects</h5>
                        <div class="d-flex gap-2">
                            <button class="btn btn-xs btn-dark rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addModuleModal">
                                <i class="fas fa-plus me-1"></i> New Course
                            </button>
                            <button class="btn btn-xs btn-outline-dark rounded-pill px-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#addSubjectModal">
                                <i class="fas fa-book-open me-1"></i> New Subject
                            </button>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Module Name</th>
                                        <th>Category</th>
                                        <th>Credit Value</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                     @forelse($ipdcCourses as $course)
                                     <tr class="hover-shadow-sm transition-all">
                                         <td class="ps-4 py-4">
                                             <div class="fw-bold text-dark fs-6">{{ $course->title }}</div>
                                             <div class="text-muted small mb-2"><i class="fas fa-user-tie me-1"></i> {{ $course->instructor ?? 'IPDC/Academic Faculty' }}</div>
                                             @if($course->subjects && $course->subjects->count() > 0)
                                                 <div class="d-flex flex-wrap gap-1.5 mt-2">
                                                     @foreach($course->subjects as $subject)
                                                         <span class="badge bg-soft-info text-info rounded-pill px-2.5 py-1.5 fw-bold" style="font-size: 0.7rem; border: 1px solid rgba(13, 148, 136, 0.12);">
                                                             <i class="fas fa-book-open me-1 text-primary"></i> {{ $subject->name }} 
                                                             <span class="text-muted font-monospace ms-0.5">({{ $subject->code }})</span>
                                                         </span>
                                                     @endforeach
                                                 </div>
                                             @else
                                                 <span class="text-muted small"><i class="fas fa-info-circle me-1 text-warning"></i> No subjects mapped</span>
                                             @endif
                                         </td>
                                         <td><span class="badge bg-soft-primary text-primary rounded-pill px-3 py-2 fw-bold">{{ ucfirst($course->level ?? 'Core') }}</span></td>
                                         <td><span class="fw-bold text-secondary fs-6"><i class="fas fa-coins text-warning me-1"></i> {{ $course->credits ?? '2.0' }} Credits</span></td>
                                         <td class="text-end pe-4">
                                             <div class="d-flex justify-content-end gap-2">
                                                 <button class="btn btn-sm btn-outline-primary border-0 bg-light rounded-pill px-3" title="Edit Transcript" onclick="openTranscriptEditor({{ $course->id }}, '{{ addslashes($course->transcript_content) }}')">
                                                     <i class="fas fa-file-signature me-1"></i> Transcript
                                                 </button>
                                                 <form action="/admin/ipdc/convert-to-assignment/{{ $course->id }}" method="POST" class="d-inline">
                                                     @csrf
                                                     <button type="submit" class="btn btn-sm btn-outline-success border-0 bg-light rounded-pill px-3" title="Convert to Assignment">
                                                         <i class="fas fa-sync-alt me-1"></i> Convert
                                                     </button>
                                                 </form>
                                                 <button class="btn btn-sm btn-outline-danger border-0 bg-light rounded-pill px-3" title="Delete">
                                                     <i class="fas fa-trash-alt"></i>
                                                 </button>
                                             </div>
                                         </td>
                                     </tr>
                                     @empty
                                     <tr>
                                         <td colspan="4" class="text-center py-5 text-muted">
                                             <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                                             <p>No academic courses mapped yet.</p>
                                         </td>
                                     </tr>
                                     @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Assignments Bank -->
            <div class="tab-pane fade" id="assignments" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0"><i class="fas fa-clipboard-list text-indigo me-2"></i> Active Assignments Bank</h5>
                        <button class="btn btn-xs btn-indigo rounded-pill px-3 text-white shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;" data-bs-toggle="modal" data-bs-target="#addAssignmentModal">
                            <i class="fas fa-plus me-1"></i> New Assignment
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Module / Course</th>
                                        <th>Assignment Title</th>
                                        <th>Points</th>
                                        <th>Due Date</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ipdcTasks as $task)
                                    <tr class="hover-shadow-sm transition-all">
                                        <td class="ps-4 py-3">
                                            <div class="fw-bold text-dark mb-1">{{ $task->course->title ?? 'N/A' }}</div>
                                            @if($task->subject)
                                                <div class="text-primary small mb-1"><i class="fas fa-book me-1"></i> {{ $task->subject->name }}</div>
                                            @endif
                                            <div class="d-flex gap-1.5 flex-wrap">
                                                @if($task->section)
                                                    <span class="badge bg-soft-info text-info rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">
                                                        Section: {{ $task->section }}
                                                    </span>
                                                @endif
                                                @if($task->assignment_type)
                                                    <span class="badge bg-soft-success text-success rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">
                                                        {{ ucfirst($task->assignment_type) }}
                                                    </span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="fw-bold text-dark">{{ $task->title }}</td>
                                        <td>
                                            <div class="fw-bold text-secondary">{{ $task->max_points ?? 100 }} pts</div>
                                            @if($task->passing_marks)
                                                <small class="text-muted d-block" style="font-size: 0.72rem;">Min: {{ $task->passing_marks }} pts</small>
                                            @endif
                                        </td>
                                        <td>
                                            @if($task->due_date)
                                                <span class="text-danger small fw-bold"><i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                                            @else
                                                <span class="text-muted small">No Deadline</span>
                                            @endif
                                        </td>
                                        <td class="text-end pe-4">
                                            <div class="d-flex justify-content-end gap-2">
                                                <button class="btn btn-xs btn-outline-secondary rounded-pill px-3 border-0 bg-light" onclick="alert('Viewing submissions for this assignment is handled at the Evaluation Desk.')">
                                                    <i class="fas fa-eye me-1"></i> View
                                                </button>
                                                <form action="/admin/ipdc/delete-task/{{ $task->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this assignment?')" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-xs btn-outline-danger rounded-pill px-3 border-0 bg-light" title="Delete Assignment">
                                                        <i class="fas fa-trash-alt text-danger me-1"></i> Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-5 text-muted">
                                            <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i>
                                            <p class="mb-0">No assignments created yet.</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Assignment Evaluation Desk -->
            <div class="tab-pane fade" id="evaluations" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0"><i class="fas fa-spell-check text-success me-2"></i> Assignment Evaluation Desk</h5>
                        <span class="badge bg-soft-success text-success rounded-pill px-3">{{ count($submissions) }} Pending</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small">
                                    <tr>
                                        <th class="ps-4">Student</th>
                                        <th>Task Title</th>
                                        <th>Submitted</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($submissions as $sub)
                                    <tr class="hover-shadow-sm transition-all">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle-sm bg-success text-white me-2">
                                                    {{ substr($sub->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark small">{{ $sub->user->name }}</div>
                                                    <small class="text-muted" style="font-size: 0.65rem;">#{{ $sub->user->enrollment_no }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small fw-bold">{{ $sub->task->title }}</div>
                                            <small class="text-muted" style="font-size: 0.65rem;">Max: {{ $sub->task->max_points }} pts</small>
                                        </td>
                                        <td class="small text-muted">{{ $sub->created_at->diffForHumans() }}</td>
                                        <td class="text-end pe-4">
                                            <textarea id="submission-code-{{ $sub->id }}" class="d-none">{{ $sub->file_content }}</textarea>
                                            <span id="submission-lang-{{ $sub->id }}" class="d-none">{{ $sub->mime_type }}</span>
                                            <button class="btn btn-xs btn-success rounded-pill px-4 fw-bold shadow-sm" onclick="toggleEvaluationRow({{ $sub->id }})">
                                                Evaluate
                                            </button>
                                        </td>
                                    </tr>
                                    <!-- Inline Evaluation Row -->
                                    <tr id="evaluation-row-{{ $sub->id }}" class="d-none bg-light evaluation-inline-row">
                                        <td colspan="4" class="p-4">
                                            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="border: 1px solid rgba(0,0,0,0.08) !important;">
                                                <div class="row g-0" style="min-height: 500px;">
                                                    <!-- Code Editor & Terminal (Left 8 columns) -->
                                                    <div class="col-md-8 bg-dark d-flex flex-column p-0" style="min-height: 500px;">
                                                        <!-- Editor Header / Controls -->
                                                        <div class="bg-dark text-white py-2 px-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
                                                            <div class="small fw-bold text-info"><i class="fas fa-file-code me-2"></i> {{ $sub->user->name }}'s Solution</div>
                                                            <div class="d-flex gap-2">
                                                                <select id="inlineLanguageSelect-{{ $sub->id }}" class="form-select form-select-sm bg-secondary border-0 text-white rounded-pill px-3" style="width: auto; font-size: 0.75rem;">
                                                                    <option value="python" {{ str_contains($sub->mime_type, 'python') ? 'selected' : '' }}>Python 3</option>
                                                                    <option value="javascript" {{ str_contains($sub->mime_type, 'javascript') ? 'selected' : '' }}>JavaScript</option>
                                                                    <option value="java" {{ str_contains($sub->mime_type, 'java') ? 'selected' : '' }}>Java</option>
                                                                </select>
                                                                <button onclick="runInlineCode({{ $sub->id }})" class="btn btn-success btn-xs rounded-pill px-3 fw-bold shadow-sm">
                                                                    <i class="fas fa-play me-1"></i> Run Code
                                                                </button>
                                                            </div>
                                                        </div>
                                                        <!-- Editor container -->
                                                        <div id="inlineEditorContainer-{{ $sub->id }}" style="height: 300px; width: 100%;"></div>
                                                        <!-- Fallback Textarea -->
                                                        <textarea id="inlineEditorFallback-{{ $sub->id }}" class="form-control d-none bg-dark text-white font-monospace p-3 border-0" style="height: 300px; resize: none; font-size: 13px;" readonly>{{ $sub->file_content }}</textarea>
                                                        
                                                        <!-- Terminal output -->
                                                        <div class="bg-black border-top border-secondary d-flex flex-column" style="height: 180px;">
                                                            <div class="bg-dark text-white-50 py-1.5 px-3 border-bottom border-secondary small d-flex justify-content-between align-items-center">
                                                                <span class="fw-bold"><i class="fas fa-terminal text-success me-1"></i> Execution Terminal Output</span>
                                                                <button class="btn btn-xs text-white-50 border-0 bg-transparent" onclick="clearInlineTerminal({{ $sub->id }})">Clear</button>
                                                            </div>
                                                            <div id="inlineTerminalBody-{{ $sub->id }}" class="p-3 text-white font-monospace small overflow-auto flex-grow-1" style="background: #000; height: 100%;">
                                                                <div class="text-secondary opacity-50">> Ready for execution...</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- Evaluation Form (Right 4 columns) -->
                                                    <div class="col-md-4 bg-white d-flex flex-column border-start p-4">
                                                        <h5 class="fw-bold mb-4" style="color: var(--baps-blue);"><i class="fas fa-marker text-success me-2"></i> Evaluation Desk</h5>
                                                        <form action="/admin/ipdc/grade-submission/{{ $sub->id }}" method="POST">
                                                            @csrf
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Grade / Points</label>
                                                                <div class="input-group">
                                                                    <input type="number" name="grade" class="form-control rounded-start-3" placeholder="0" max="{{ $sub->task->max_points }}" required>
                                                                    <span class="input-group-text bg-light border-start-0 small">/ {{ $sub->task->max_points }}</span>
                                                                </div>
                                                            </div>
                                                            <div class="mb-3">
                                                                <label class="form-label small fw-bold">Faculty Feedback</label>
                                                                <textarea name="feedback" class="form-control" rows="5" placeholder="Provide constructive feedback to the student..."></textarea>
                                                            </div>
                                                            <div class="alert alert-success small py-2 mb-3">
                                                                <i class="fas fa-info-circle me-1"></i> Finalizes grade & notifies the student.
                                                            </div>
                                                            <div class="d-flex gap-2">
                                                                <button type="submit" class="btn btn-success flex-grow-1 py-2.5 fw-bold rounded-3 shadow-sm">
                                                                    Finalize
                                                                </button>
                                                                <button type="button" class="btn btn-light border py-2.5 fw-bold rounded-3" onclick="toggleEvaluationRow({{ $sub->id }})">
                                                                    Cancel
                                                                </button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-5 text-muted">
                                            <i class="fas fa-check-double fa-3x mb-3 opacity-25 text-success"></i>
                                            <p class="fw-bold mb-0">All assignments evaluated!</p>
                                            <small>New submissions will appear here for grading.</small>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Graded Assignments History -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
                    <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold m-0"><i class="fas fa-history text-secondary me-2"></i> Graded Assignments History</h5>
                        <span class="badge bg-soft-secondary text-secondary rounded-pill px-3">{{ count($gradedSubmissions) }} Evaluated</span>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light text-muted small">
                                    <tr>
                                        <th class="ps-4">Student</th>
                                        <th>Task Title</th>
                                        <th>Evaluation Details</th>
                                        <th>Evaluated By</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($gradedSubmissions as $sub)
                                    <tr class="hover-shadow-sm transition-all">
                                        <td class="ps-4 py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle-sm bg-success text-white me-2" style="width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.8rem;">
                                                    {{ substr($sub->user->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="fw-bold text-dark small">{{ $sub->user->name }}</div>
                                                    <small class="text-muted" style="font-size: 0.65rem;">#{{ $sub->user->enrollment_no }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="small fw-bold">{{ $sub->task->title }}</div>
                                            <small class="text-muted" style="font-size: 0.65rem;">Max: {{ $sub->task->max_points }} pts</small>
                                        </td>
                                        <td>
                                            <div class="small fw-bold text-success">{{ $sub->grade }} / {{ $sub->task->max_points }}</div>
                                            @if($sub->feedback)
                                                <small class="text-muted text-truncate d-inline-block" style="max-width: 200px; font-size: 0.65rem;" title="{{ $sub->feedback }}">"{{ $sub->feedback }}"</small>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="small fw-bold text-dark"><i class="fas fa-user-shield text-secondary me-1"></i> {{ $sub->evaluator_name ?? 'BHAVIKKUMAR PATEL' }}</div>
                                            <small class="text-muted" style="font-size: 0.65rem;">{{ $sub->updated_at->diffForHumans() }}</small>
                                        </td>
                                        <td class="text-end pe-4">
                                            <a href="/ipdc/evaluation-pdf/{{ $sub->id }}" target="_blank" class="btn btn-xs btn-outline-success rounded-pill px-3 fw-bold">
                                                <i class="fas fa-file-pdf me-1"></i> Report
                                            </a>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-4 text-muted">
                                            <small>No assignments have been evaluated yet.</small>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 4: External Certification Vault -->
            <div class="tab-pane fade" id="certs" role="tabpanel">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-white py-3 border-0">
                        <h5 class="fw-bold m-0"><i class="fas fa-certificate text-warning me-2"></i> External Certification Vault</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Student</th>
                                        <th>Platform</th>
                                        <th>Certificate Title</th>
                                        <th class="text-end pe-4">Verification</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pendingCerts as $cert)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle-sm me-2 bg-primary text-white">{{ strtoupper(substr($cert->user->name, 0, 2)) }}</div>
                                                <div>
                                                    <div class="fw-bold small">{{ $cert->user->name }}</div>
                                                    <small class="text-muted" style="font-size: 0.7rem;">#{{ $cert->user->enrollment_no }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><span class="badge bg-dark rounded-pill px-3">{{ $cert->platform }}</span></td>
                                        <td class="small fw-bold">{{ $cert->title }}</td>
                                        <td class="text-end pe-4">
                                            @php $hasFile = ($cert->file_content || $cert->file_path); @endphp
                                            @if($cert->verification_status == 'pending')
                                                <button class="btn btn-xs btn-primary rounded-pill px-3 fw-bold" onclick="previewCert({{ $cert->id }}, '{{ $hasFile ? url('/cloud-file/cert/' . $cert->id) : $cert->credential_link }}', '{{ $hasFile ? 'pdf' : 'link' }}', false, '{{ $hasFile ? $cert->credential_link : '' }}')">
                                                    Verify Now
                                                </button>
                                            @else
                                                <div class="d-flex align-items-center justify-content-end gap-2">
                                                    <span class="badge bg-soft-success text-success rounded-pill px-3">
                                                        <i class="fas fa-check-circle me-1"></i> Verified
                                                    </span>
                                                    <button class="btn btn-xs btn-outline-primary rounded-pill px-3 fw-bold" onclick="previewCert({{ $cert->id }}, '{{ $hasFile ? url('/cloud-file/cert/' . $cert->id) : $cert->credential_link }}', '{{ $hasFile ? 'pdf' : 'link' }}', true, '{{ $hasFile ? $cert->credential_link : '' }}')">
                                                        <i class="fas fa-eye me-1"></i> View
                                                    </button>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted small">
                                            <i class="fas fa-check-circle text-success me-1"></i> All external certifications processed.
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
    </div>

    <!-- Right Column: Stats & Assets -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4 bg-dark text-white position-relative overflow-hidden">
            <div class="card-body text-center py-4 position-relative z-1">
                <div class="position-absolute top-0 end-0 p-3">
                    <span class="badge rounded-pill bg-success bg-opacity-25 text-success small fw-bold border border-success border-opacity-25">
                        <i class="fas fa-sync fa-spin me-1"></i> Synchronized
                    </span>
                </div>
                <i class="fas fa-graduation-cap fa-4x text-warning mb-3"></i>
                <h4 class="fw-bold">Global Impact</h4>
                <div class="row mt-4">
                    <div class="col-6 border-end border-white border-opacity-10">
                        <h2 class="fw-bold mb-0">
                            @if($totalCerts >= 1000)
                                {{ number_format($totalCerts / 1000, 1) }}k
                            @else
                                {{ $totalCerts }}
                            @endif
                        </h2>
                        <small class="text-white-50 text-uppercase ls-1" style="font-size: 0.65rem;">Certs Verified</small>
                    </div>
                    <div class="col-6">
                        <h2 class="fw-bold mb-0">
                            @if($totalSeva >= 1000)
                                {{ number_format($totalSeva / 1000, 1) }}k
                            @else
                                {{ $totalSeva }}
                            @endif
                        </h2>
                        <small class="text-white-50 text-uppercase ls-1" style="font-size: 0.65rem;">Seva Hours</small>
                    </div>
                </div>
            </div>
            <div class="position-absolute end-0 bottom-0 translate-middle-y opacity-10">
                <i class="fas fa-graduation-cap fa-8x me-n4"></i>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-4 border-primary">
            <div class="card-body">
                <h6 class="fw-bold mb-3 text-primary d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-folder-open me-2"></i> Workbook & Solutions Vault</span>
                </h6>
                <div class="list-group list-group-flush mb-3">
                    @forelse($assets as $asset)
                    <div class="list-group-item d-flex justify-content-between align-items-center small py-3 px-0 border-0 border-bottom">
                        <div class="d-flex align-items-center">
                            <i class="fas {{ $asset->type == 'workbook' ? 'fa-book text-danger' : ($asset->type == 'solution' ? 'fa-lightbulb text-warning' : 'fa-file-alt text-info') }} fs-5 me-3"></i>
                            <div>
                                <div class="fw-bold text-dark">{{ $asset->title }}</div>
                                <small class="text-muted">{{ ucfirst($asset->type) }} • {{ $asset->created_at->format('M d, Y') }}</small>
                            </div>
                        </div>
                        <a href="{{ url('/cloud-file/asset/' . $asset->id) }}" target="_blank" class="btn btn-sm btn-light border rounded-circle">
                            <i class="fas fa-download text-muted"></i>
                        </a>
                    </div>
                    @empty
                    <p class="text-center text-muted py-3 small">No assets uploaded yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3 d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-satellite-dish text-success me-2"></i> System Sync</span>
                    <span class="text-success small fw-normal"><i class="fas fa-circle fa-xs me-1 animate-pulse"></i> Live</span>
                </h6>
                <div class="small text-muted mb-4">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-bold">Database Integrity</span>
                        <span class="text-success fw-bold">99.9% Secure</span>
                    </div>
                    <div class="progress rounded-pill shadow-sm" style="height: 6px; background: rgba(0,0,0,0.05);">
                        <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" style="width: 99.9%;"></div>
                    </div>
                </div>
                <div class="activity-feed small mt-2">
                    <div class="activity-item d-flex gap-3 mb-3 border-start border-2 border-primary ps-3 position-relative" id="sync_cert_node">
                        <div class="activity-dot bg-primary shadow-sm" style="width: 12px; height: 12px; border-radius: 50%; position: absolute; left: -7px; top: 0; border: 2px solid white;"></div>
                        <div class="activity-content">
                            <div class="fw-bold text-dark">Certification Sync</div>
                            <div class="text-muted" style="font-size: 0.72rem; line-height: 1.3;">Verified achievements updated across student profiles.</div>
                        </div>
                    </div>
                    <div class="activity-item d-flex gap-3 border-start border-2 border-warning ps-3 position-relative" id="sync_seva_node">
                        <div class="activity-dot bg-warning shadow-sm" style="width: 12px; height: 12px; border-radius: 50%; position: absolute; left: -7px; top: 0; border: 2px solid white;"></div>
                        <div class="activity-content">
                            <div class="fw-bold text-dark">Seva Log Update</div>
                            <div class="text-muted" style="font-size: 0.72rem; line-height: 1.3;">Volunteer hours synchronized with institutional records.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Quick Management</h6>
                <div class="d-grid gap-2">
                    <a href="/admin/ipdc/logs" class="btn btn-outline-dark text-start border-0 bg-light rounded-3 py-2 px-3 mb-1">
                        <i class="fas fa-hands-helping me-2 text-danger"></i> Seva Log Portal
                    </a>
                    <a href="/admin/ipdc/certs" class="btn btn-outline-dark text-start border-0 bg-light rounded-3 py-2 px-3">
                        <i class="fas fa-history me-2 text-info"></i> Global Cert History
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Transcript Editor -->
<div class="modal fade" id="transcriptModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="" id="transcriptForm" method="POST" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-bold">Manage Transcript Content</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="small text-muted mb-3">This content will appear on the student's official IPDC transcript for this module.</p>
                <textarea name="content" id="transcriptContent" class="form-control" rows="12" style="font-size: 0.9rem; line-height: 1.6;"></textarea>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold">Save Content</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Upload Asset -->
<div class="modal fade" id="uploadAssetModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="/admin/ipdc/upload-asset" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-bold">Upload IPDC Asset</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Asset Title</label>
                    <input name="title" class="form-control" placeholder="e.g., Module 1 Workbook" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Asset Type</label>
                    <select name="type" class="form-select">
                        <option value="workbook">Official Workbook (PDF)</option>
                        <option value="solution">Solution Guide</option>
                        <option value="resource">Multimedia Resource</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Select File</label>
                    <input type="file" name="file" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="submit" class="btn btn-primary w-100 fw-bold">Upload to Repository</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Cert Preview & Verify -->
<div class="modal fade" id="previewCertModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden" style="height: 90vh;">
            <div class="row h-100 g-0">
                <div class="col-md-8 bg-light d-flex align-items-center justify-content-center p-0">
                    <iframe id="certIframe" src="" class="w-100 h-100 border-0" title="Credential Preview"></iframe>
                    <div id="certLinkPreview" class="text-center d-none p-5">
                        <i class="fas fa-external-link-alt fa-4x text-primary mb-3"></i>
                        <h4 class="fw-bold">External Credential Link</h4>
                        <p class="text-muted">This certificate is hosted on an external platform.</p>
                        <a href="" id="certBtnLink" target="_blank" class="btn btn-primary rounded-pill px-4 fw-bold mt-2">Open Link in New Tab</a>
                        <div id="dualModeSwitch" class="mt-4 d-none">
                            <hr>
                            <p class="small text-muted">A PDF was also uploaded. <a href="javascript:void(0)" onclick="togglePreviewMode('pdf')" class="fw-bold">Switch to PDF View</a></p>
                        </div>
                    </div>
                    <div id="pdfModeSwitch" class="position-absolute bottom-0 start-0 p-3 d-none">
                        <div class="bg-white shadow-sm rounded-pill px-3 py-1 small border border-primary border-opacity-25">
                            <i class="fas fa-link me-1 text-primary"></i> External link also available. <a href="javascript:void(0)" onclick="togglePreviewMode('link')" class="fw-bold">View Link</a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 bg-white d-flex flex-column border-start">
                    <div class="p-4 flex-grow-1 overflow-auto">
                        <h5 class="fw-bold mb-4">Verification Workflow</h5>
                        <form id="verifyForm" action="" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Final Decision</label>
                                <div class="d-flex gap-2">
                                    <input type="radio" class="btn-check" name="status" id="v_approve" value="verified" checked>
                                    <label class="btn btn-outline-success w-100 fw-bold" for="v_approve"><i class="fas fa-check me-1"></i> Approve</label>
                                    
                                    <input type="radio" class="btn-check" name="status" id="v_reject" value="rejected">
                                    <label class="btn btn-outline-danger w-100 fw-bold" for="v_reject"><i class="fas fa-times me-1"></i> Reject</label>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Admin Remarks</label>
                                <textarea name="remarks" class="form-control" rows="4" placeholder="Optional notes for the student..."></textarea>
                            </div>
                            <div class="alert alert-info small py-2 mb-4">
                                <i class="fas fa-info-circle me-1"></i> Approving will automatically update the student's achievement profile.
                            </div>
                            <button type="submit" class="btn btn-dark w-100 py-3 fw-bold rounded-3 shadow">
                                Confirm Verification
                            </button>
                        </form>
                    </div>
                    <div class="p-3 bg-light border-top text-center">
                        <button class="btn btn-sm text-muted" data-bs-dismiss="modal">Close Preview</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Add Student Credential (Manual) -->
<div class="modal fade" id="addStudentCertModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="/admin/ipdc/add-cert" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-graduate me-2"></i> Manual Student Credential Entry</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Target Student</label>
                    <select name="user_id" class="form-select rounded-3 shadow-sm border-0 bg-light" required>
                        <option value="">-- Select Registered Student --</option>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}">{{ $student->name }} (#{{ $student->enrollment_no }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Issuing Platform</label>
                    <select name="platform" class="form-select rounded-3 shadow-sm border-0 bg-light" onchange="toggleCustomPlatform(this, 'adminCustomPlatform')">
                        <option>NPTEL</option>
                        <option>HackerRank</option>
                        <option>Google Career Certificates</option>
                        <option>Meta Blueprint</option>
                        <option>Infosys Springboard</option>
                        <option>Oracle University</option>
                        <option value="Other">Other Institutional Provider</option>
                    </select>
                </div>
                <div id="adminCustomPlatform" class="mb-3 d-none">
                    <label class="form-label small fw-bold text-primary">Enter Provider Name</label>
                    <input name="custom_platform" class="form-control rounded-3 shadow-sm border-0 bg-light" placeholder="e.g., Coursera, Udemy, Microsoft">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Certification Title</label>
                    <input name="title" class="form-control rounded-3 shadow-sm border-0 bg-light" placeholder="e.g., Python for Data Science" required>
                </div>
                
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Issue Date</label>
                        <input type="date" name="issue_date" class="form-control rounded-3 shadow-sm border-0 bg-light">
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold">Credential Link (URL)</label>
                        <input name="link" class="form-control rounded-3 shadow-sm border-0 bg-light" placeholder="https://verify.platform.com/id...">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Upload Certificate (Optional)</label>
                    <input type="file" name="file" class="form-control rounded-3 shadow-sm border-0 bg-light">
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-dark w-100 py-2 fw-bold rounded-pill shadow">
                    Add & Verify Credential
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Map Course -->
<div class="modal fade" id="addModuleModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="/admin/ipdc/module" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> Map New Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Course Title</label>
                    <input name="title" class="form-control rounded-3 border-0 bg-light shadow-sm" placeholder="e.g., Bachelors - Data Structures and Algorithms" required>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Instructor</label>
                    <input name="instructor" class="form-control rounded-3 border-0 bg-light shadow-sm" placeholder="e.g., Dr. Jane Doe" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Program Level</label>
                        <select name="level" class="form-select rounded-3 border-0 bg-light shadow-sm">
                            <option value="Bachelors">Bachelors</option>
                            <option value="Masters">Masters</option>
                            <option value="Diploma">Diploma</option>
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Credit Value</label>
                        <input name="credits" type="number" step="0.5" class="form-control rounded-3 border-0 bg-light shadow-sm" value="3.0" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-dark w-100 py-2 fw-bold rounded-pill shadow">Map Course</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Map Subject -->
<div class="modal fade" id="addSubjectModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="/admin/ipdc/subject" method="POST" class="modal-content border-0 shadow">
            @csrf
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-book me-2"></i> Map Subject to Course</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Target Course</label>
                    <select name="course_id" class="form-select rounded-3 border-0 bg-light shadow-sm" required>
                        <option value="">-- Select Course --</option>
                        @foreach($ipdcCourses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Subject Name</label>
                    <input name="name" class="form-control rounded-3 border-0 bg-light shadow-sm" placeholder="e.g., Module 1: Introduction to Trees" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Subject Code</label>
                        <input name="code" class="form-control rounded-3 border-0 bg-light shadow-sm" placeholder="e.g., SUB-BAC-1" required>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold">Subject Type</label>
                        <select name="type" class="form-select rounded-3 border-0 bg-light shadow-sm">
                            <option value="theory">Theory</option>
                            <option value="practical">Practical</option>
                            <option value="pbl">PBL (Project-based)</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-dark w-100 py-2 fw-bold rounded-pill shadow">Map Subject</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Add Assignment -->
<div class="modal fade" id="addAssignmentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <form action="/admin/task" method="POST" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header bg-dark text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-plus-circle me-2"></i> Create Academic Assignment</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold"><i class="fas fa-graduation-cap text-primary me-1"></i> Target Course</label>
                        <select name="course_id" id="assignment_course_id" class="form-select rounded-3 shadow-sm border-0 bg-light" required>
                            <option value="">-- Select Course --</option>
                            @foreach($ipdcCourses as $c)
                                <option value="{{ $c->id }}">{{ $c->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold"><i class="fas fa-book text-success me-1"></i> Target Subject (Optional)</label>
                        <select name="subject_id" id="assignment_subject_id" class="form-select rounded-3 shadow-sm border-0 bg-light">
                            <option value="">-- Select Subject (Optional) --</option>
                        </select>
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label small fw-bold"><i class="fas fa-users text-info me-1"></i> Class Section</label>
                        <select name="section" class="form-select rounded-3 shadow-sm border-0 bg-light">
                            <option value="All">All Sections</option>
                            <option value="A">Section A</option>
                            <option value="B">Section B</option>
                            <option value="C">Section C</option>
                            <option value="D">Section D</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small fw-bold"><i class="fas fa-tags text-warning me-1"></i> Assignment Type</label>
                        <select name="assignment_type" class="form-select rounded-3 shadow-sm border-0 bg-light">
                            <option value="homework">Homework / Practice</option>
                            <option value="lab">Laboratory Work</option>
                            <option value="project">Capstone Project</option>
                            <option value="exam">Term Examination</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <label class="form-label small fw-bold mb-0"><i class="fas fa-heading text-danger me-1"></i> Assignment Title</label>
                        <button type="button" id="btn_generate_ai" class="btn btn-xs text-white px-3 py-1 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important; border: none; font-size: 0.7rem; font-weight: 600;">
                            <i class="fas fa-robot me-1"></i> Generate with AI
                        </button>
                    </div>
                    <input name="title" id="assignment_title_input" class="form-control rounded-3 shadow-sm border-0 bg-light" placeholder="e.g., Lab 1: Database Normalization" required>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold"><i class="fas fa-align-left text-muted me-1"></i> Instructions / Description</label>
                    <textarea name="description" id="assignment_desc_textarea" class="form-control rounded-3 shadow-sm border-0 bg-light" rows="4" placeholder="Provide detailed instructions for this assignment..."></textarea>
                </div>

                <div id="ai_generating_spinner" class="alert alert-info py-2 small mb-3 d-none animate-pulse" style="border: 1px solid rgba(59, 130, 246, 0.2); background: rgba(59, 130, 246, 0.05); color: #1e3a8a;">
                    <i class="fas fa-robot fa-spin me-2 text-primary"></i> <strong>Gemini 2.5 Pro</strong> is drafting a premium assignment... Please wait.
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label class="form-label small fw-bold"><i class="far fa-calendar-alt text-danger me-1"></i> Due Date</label>
                        <input name="due_date" type="date" class="form-control rounded-3 shadow-sm border-0 bg-light">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold"><i class="fas fa-star text-warning me-1"></i> Max Points</label>
                        <input name="max_points" type="number" class="form-control rounded-3 shadow-sm border-0 bg-light" value="100" min="1" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small fw-bold"><i class="fas fa-check-circle text-success me-1"></i> Passing Marks</label>
                        <input name="passing_marks" type="number" class="form-control rounded-3 shadow-sm border-0 bg-light" value="40" min="1" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-dark w-100 py-2.5 fw-bold rounded-pill shadow-sm">Create Assignment</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Assignment Evaluation (Grading) -->
<div class="modal fade" id="gradeSubmissionModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden" style="height: 85vh;">
            <div class="row h-100 g-0">
                <div class="col-md-8 bg-dark d-flex flex-column p-0 h-100">
                    <!-- Editor Header / Controls -->
                    <div class="bg-dark text-white py-2 px-3 border-bottom border-secondary d-flex justify-content-between align-items-center">
                        <div class="small fw-bold text-info"><i class="fas fa-file-code me-2"></i> student_solution</div>
                        <div class="d-flex gap-2">
                            <select id="evalLanguageSelect" class="form-select form-select-sm bg-secondary border-0 text-white rounded-pill px-3" style="width: auto; font-size: 0.75rem;">
                                <option value="python">Python 3</option>
                                <option value="javascript">JavaScript</option>
                                <option value="java">Java</option>
                            </select>
                            <button id="evalRunBtn" class="btn btn-success btn-xs rounded-pill px-3 fw-bold shadow-sm">
                                <i class="fas fa-play me-1"></i> Run Code
                            </button>
                        </div>
                    </div>
                    <!-- Monaco Editor container -->
                    <div id="evalEditorContainer" class="flex-grow-1" style="min-height: 50%;"></div>
                    <!-- Fallback Textarea -->
                    <textarea id="evalEditorFallback" class="form-control d-none bg-dark text-white font-monospace p-3 border-0" style="min-height: 50%; resize: none; font-size: 13px;" readonly></textarea>
                    <!-- Interactive Terminal output -->
                    <div class="bg-black border-top border-secondary d-flex flex-column" style="height: 35%;">
                        <div class="bg-dark text-white-50 py-1.5 px-3 border-bottom border-secondary small d-flex justify-content-between align-items-center">
                            <span class="fw-bold"><i class="fas fa-terminal text-success me-1"></i> Execution Terminal Output</span>
                            <button class="btn btn-xs text-white-50 border-0 bg-transparent" onclick="clearEvalTerminal()">Clear</button>
                        </div>
                        <div id="evalTerminalBody" class="p-3 text-white font-monospace small overflow-auto flex-grow-1" style="background: #000; height: 100%;">
                            <div class="text-secondary opacity-50">> Execution output will appear here...</div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 bg-white d-flex flex-column border-start h-100">
                    <div class="p-4 flex-grow-1 overflow-auto">
                        <h5 class="fw-bold mb-4"><i class="fas fa-marker text-success me-2"></i> Evaluation Desk</h5>
                        <form id="gradeForm" action="" method="POST">
                            @csrf
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Grade / Points</label>
                                <div class="input-group">
                                    <input type="number" name="grade" id="gradeInput" class="form-control rounded-start-3" placeholder="0" required>
                                    <span class="input-group-text bg-light border-start-0 small">/ <span id="maxPointsSpan">100</span></span>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label small fw-bold">Faculty Feedback</label>
                                <textarea name="feedback" class="form-control" rows="6" placeholder="Provide constructive feedback to the student..."></textarea>
                            </div>
                            <div class="alert alert-success small py-2 mb-4">
                                <i class="fas fa-info-circle me-1"></i> Submitting will finalize the grade and notify the student.
                            </div>
                            <button type="submit" class="btn btn-success w-100 py-3 fw-bold rounded-3 shadow">
                                Finalize Evaluation
                            </button>
                        </form>
                    </div>
                    <div class="p-3 bg-light border-top text-center">
                        <button class="btn btn-sm text-muted" data-bs-dismiss="modal">Close Evaluation</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monaco Editor CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>
<script>
    let inlineEditors = {};

    function toggleEvaluationRow(id) {
        const row = document.getElementById('evaluation-row-' + id);
        if (row.classList.contains('d-none')) {
            // Hide all other open evaluation rows first to keep UI neat
            document.querySelectorAll('.evaluation-inline-row').forEach(r => r.classList.add('d-none'));
            
            // Show this one
            row.classList.remove('d-none');
            
            // Initialize Monaco Editor if not done yet
            const container = document.getElementById('inlineEditorContainer-' + id);
            const fallback = document.getElementById('inlineEditorFallback-' + id);
            const codeVal = document.getElementById('submission-code-' + id).value;
            const mimeVal = document.getElementById('submission-lang-' + id).innerText;
            
            let lang = 'python';
            if (mimeVal.includes('javascript')) {
                lang = 'javascript';
            } else if (mimeVal.includes('java')) {
                lang = 'java';
            }
            
            if (typeof require !== 'undefined' && !inlineEditors[id]) {
                try {
                    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' }});
                    require(['vs/editor/editor.main'], function() {
                        inlineEditors[id] = monaco.editor.create(container, {
                            value: codeVal,
                            language: lang,
                            theme: 'vs-dark',
                            fontSize: 13,
                            fontFamily: "'Fira Code', 'Courier New', monospace",
                            automaticLayout: true,
                            padding: { top: 10 },
                            minimap: { enabled: false },
                            readOnly: false
                        });
                        container.classList.remove('d-none');
                        fallback.classList.add('d-none');
                    });
                } catch (e) {
                    console.error("Monaco error: ", e);
                    fallback.classList.remove('d-none');
                    container.classList.add('d-none');
                }
            } else if (!inlineEditors[id]) {
                fallback.classList.remove('d-none');
                container.classList.add('d-none');
            }
        } else {
            row.classList.add('d-none');
        }
    }

    function runInlineCode(id) {
        const editor = inlineEditors[id];
        const fallback = document.getElementById('inlineEditorFallback-' + id);
        const code = editor ? editor.getValue() : (fallback ? fallback.value : "");
        const lang = document.getElementById('inlineLanguageSelect-' + id).value;
        const terminal = document.getElementById('inlineTerminalBody-' + id);
        
        terminal.innerHTML += `<div class="text-info mt-2">>>> Executing ${lang}...</div>`;

        fetch('/api/execute-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-API-Key': 'wc_api_www.bhavikpatel180_2ef44332bd5b54bc0e0ee86dd27ddf79'
            },
            body: JSON.stringify({
                language: lang,
                files: [
                    {
                        name: lang === 'python' ? 'main.py' : (lang === 'javascript' ? 'main.js' : 'Main.java'),
                        content: code
                    }
                ]
            })
        })
        .then(res => res.json())
        .then(data => {
            const run = data.run || {};
            if (run.output) {
                terminal.innerHTML += `<div class="text-success">${run.output.replace(/\n/g, '<br>')}</div>`;
            }
            if (run.stderr) {
                terminal.innerHTML += `<div class="text-danger">${run.stderr.replace(/\n/g, '<br>')}</div>`;
            }
            if (!run.output && !run.stderr) {
                terminal.innerHTML += `<div class="text-secondary opacity-50">> Process finished with exit code ${run.code || 0}</div>`;
            }
            terminal.scrollTop = terminal.scrollHeight;
        })
        .catch(err => {
            terminal.innerHTML += `<div class="text-danger">Execution failed: Connection error.</div>`;
        });
    }

    function clearInlineTerminal(id) {
        document.getElementById('inlineTerminalBody-' + id).innerHTML = '<div class="text-secondary opacity-50">> Ready for execution...</div>';
    }
    function openTranscriptEditor(courseId, content) {
        document.getElementById('transcriptForm').action = '/admin/ipdc/update-transcript/' + courseId;
        document.getElementById('transcriptContent').value = content;
        new bootstrap.Modal(document.getElementById('transcriptModal')).show();
    }

    let currentPreviewData = { id: 0, file: '', link: '', type: '' };

    function previewCert(id, source, type, isViewOnly = false, altSource = '') {
        currentPreviewData = { id, file: type === 'pdf' ? source : altSource, link: type === 'link' ? source : altSource, type };
        
        const iframe = document.getElementById('certIframe');
        const linkPreview = document.getElementById('certLinkPreview');
        const btnLink = document.getElementById('certBtnLink');
        const verifyForm = document.getElementById('verifyForm');
        const workflowSection = document.querySelector('#previewCertModal .col-md-4');

        verifyForm.action = '/admin/ipdc/verify-cert/' + id;

        if (isViewOnly) {
            workflowSection.classList.add('d-none');
            document.querySelector('#previewCertModal .col-md-8').classList.replace('col-md-8', 'col-md-12');
        } else {
            workflowSection.classList.remove('d-none');
            document.querySelector('#previewCertModal .col-md-12')?.classList.replace('col-md-12', 'col-md-8');
        }

        togglePreviewMode(type);
        new bootstrap.Modal(document.getElementById('previewCertModal')).show();
    }

    function togglePreviewMode(mode) {
        const iframe = document.getElementById('certIframe');
        const linkPreview = document.getElementById('certLinkPreview');
        const btnLink = document.getElementById('certBtnLink');
        const dualModeSwitch = document.getElementById('dualModeSwitch');
        const pdfModeSwitch = document.getElementById('pdfModeSwitch');

        if (mode === 'pdf') {
            let url = currentPreviewData.file;
            if (url.startsWith('http://')) url = url.replace('http://', 'https://');
            
            iframe.src = url;
            
            iframe.classList.remove('d-none');
            linkPreview.classList.add('d-none');
            pdfModeSwitch.classList.toggle('d-none', !currentPreviewData.link);
        } else {
            iframe.classList.add('d-none');
            linkPreview.classList.remove('d-none');
            btnLink.href = currentPreviewData.link;
            dualModeSwitch.classList.toggle('d-none', !currentPreviewData.file);
            pdfModeSwitch.classList.add('d-none');
        }
    }
    function toggleCustomPlatform(select, targetId) {
        const target = document.getElementById(targetId);
        if (select.value === 'Other') {
            target.classList.remove('d-none');
        } else {
            target.classList.add('d-none');
        }
    }

    // Dynamic Course -> Subjects map
    const courseSubjectsMap = {
        @foreach($ipdcCourses as $c)
            "{{ $c->id }}": [
                @foreach($c->subjects as $s)
                    { id: "{{ $s->id }}", name: "{{ addslashes($s->name) }}", code: "{{ addslashes($s->code) }}" },
                @endforeach
            ],
        @endforeach
    };

    document.getElementById('assignment_course_id')?.addEventListener('change', function() {
        const courseId = this.value;
        const subjectSelect = document.getElementById('assignment_subject_id');
        if (!subjectSelect) return;
        
        subjectSelect.innerHTML = '<option value="">-- Select Subject (Optional) --</option>';
        if (courseId && courseSubjectsMap[courseId]) {
            courseSubjectsMap[courseId].forEach(sub => {
                const opt = document.createElement('option');
                opt.value = sub.id;
                opt.textContent = `${sub.name} (${sub.code})`;
                subjectSelect.appendChild(opt);
            });
        }
    });

    document.getElementById('btn_generate_ai')?.addEventListener('click', function() {
        const courseId = document.getElementById('assignment_course_id').value;
        const subjectId = document.getElementById('assignment_subject_id').value;
        const assignmentType = document.querySelector('select[name="assignment_type"]').value;

        if (!courseId) {
            alert('Please select a Target Course first to generate assignment content.');
            return;
        }

        const btn = this;
        const spinner = document.getElementById('ai_generating_spinner');
        const titleInput = document.getElementById('assignment_title_input');
        const descTextarea = document.getElementById('assignment_desc_textarea');

        btn.disabled = true;
        spinner.classList.remove('d-none');

        fetch('/admin/ipdc/generate-assignment-ai', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                course_id: courseId,
                subject_id: subjectId,
                assignment_type: assignmentType
            })
        })
        .then(res => {
            if (!res.ok) throw new Error('API server returned an error');
            return res.json();
        })
        .then(data => {
            if (data.title) titleInput.value = data.title;
            if (data.description) descTextarea.value = data.description;
            if (typeof showBapsToast === 'function') {
                showBapsToast('Assignment content prepared by Strong AI!', 'success', 'fa-robot');
            }
        })
        .catch(err => {
            console.error(err);
            alert('AI generation failed. Please try again or fill the details manually.');
        })
        .finally(() => {
            btn.disabled = false;
            spinner.classList.add('d-none');
        });
    });
</script>

<style>
    .btn-xs { padding: 0.25rem 0.6rem; font-size: 0.7rem; }
    .bg-soft-info { background: #e0f2fe; }
    .bg-soft-primary { background: rgba(13, 110, 253, 0.1); }
    .bg-soft-success { background: #dcfce7; }
    .bg-soft-warning { background: #fef3c7; }
    .avatar-circle-sm { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.75rem; }
    .hover-shadow-sm:hover { box-shadow: 0 .125rem .25rem rgba(0,0,0,.075)!important; }
    .transition-all { transition: all 0.2s ease-in-out; }
    .ls-1 { letter-spacing: 0.5px; }

    .nav-pills-custom .nav-link {
        color: #475569;
        background: transparent;
        transition: all 0.2s ease;
    }
    .nav-pills-custom .nav-link:hover {
        background: #f1f5f9;
        color: #0f172a;
    }
    .nav-pills-custom .nav-link.active {
        background: var(--primary-gradient) !important;
        color: white !important;
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.2);
    }
    .animate-pulse {
        animation: pulse-ring 2s infinite;
    }
    @keyframes pulse-ring {
        0% { transform: scale(0.95); opacity: 0.8; }
        50% { transform: scale(1.05); opacity: 1; }
        100% { transform: scale(0.95); opacity: 0.8; }
    }
</style>

@endsection
