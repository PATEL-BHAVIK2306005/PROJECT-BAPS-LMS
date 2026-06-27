@extends('layouts.app')
@section('content')

<div class="container py-4">
    <div class="text-end mb-4 no-print text-center text-md-end">
        <a href="/courses/{{ $course->id }}" class="btn btn-outline-secondary rounded-pill fw-bold me-2 shadow-sm"><i class="fas fa-arrow-left me-1"></i> Back to Course</a>
        <button onclick="window.open('/certificate/{{ $course->id }}/preview', '_blank')" class="btn btn-premium rounded-pill fw-bold px-4 shadow-sm"><i class="fas fa-print me-2"></i> Print Official Document</button>
    </div>

    <!-- 2-Tab Navigation Container -->
    <div class="row no-print mb-4">
        <div class="col-12 d-flex justify-content-center">
            <ul class="nav nav-pills bg-white shadow-sm rounded-pill p-1 border" id="certificateTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active rounded-pill px-4 fw-bold text-dark" id="transcript-tab" data-bs-toggle="pill" data-bs-target="#transcript-content" type="button" role="tab"><i class="fas fa-list-alt text-primary me-2"></i> Academic Transcript</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link rounded-pill px-4 fw-bold text-dark" id="certificate-tab" data-bs-toggle="pill" data-bs-target="#certificate-content" type="button" role="tab"><i class="fas fa-award text-warning me-2"></i> Premium Certificate</button>
                </li>
            </ul>
        </div>
    </div>

    <!-- Tab Contents -->
    <div class="tab-content" id="certificateTabsContent">
        
        <!-- TAB 1: Academic Transcript -->
        <div class="tab-pane fade show active" id="transcript-content" role="tabpanel">
            <div class="print-page bg-white shadow-lg mx-auto position-relative print-transcript p-4 p-md-5" style="max-width: 297mm; min-height: 210mm;">
        
        <div class="d-flex justify-content-between border-bottom pb-4 mb-4">
            <div class="d-flex align-items-center gap-3">
                <div style="width: 60px; height: 60px; background: var(--primary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                    <i class="fas fa-university fa-2x"></i>
                </div>
                <div>
                    <h3 class="fw-bold mb-0 text-dark">BAPS-e.learn-LMS</h3>
                    <p class="text-muted text-uppercase small tracking-widest mb-0 fw-bold">Official Academic Transcript</p>
                </div>
            </div>
            <div class="text-end">
                <p class="mb-0 text-muted small">Reference ID</p>
                <div class="fw-bold text-dark">{{ $certificate->unique_code }}</div>
                <div class="text-muted small mt-1">Issued: {{ $certificate->created_at->format('M d, Y') }}</div>
            </div>
        </div>

        <div class="row mb-5">
            <div class="col-6">
                <h6 class="text-uppercase text-muted fw-bold small mb-2 border-bottom pb-1">Student Particulars</h6>
                <table class="table table-sm table-borderless text-dark">
                    <tr><td width="130" class="text-muted">Student Name</td><td class="fw-bold">{{ $user->name }}</td></tr>
                    <tr><td class="text-muted">Enrollment No.</td><td class="fw-bold">{{ $user->enrollment_no }}</td></tr>
                    <tr><td class="text-muted">Department</td><td class="fw-bold">{{ $user->department ?? 'Core Curriculum' }}</td></tr>
                    <tr><td class="text-muted">Academic Level</td><td class="fw-bold">Level {{ $user->level }}</td></tr>
                </table>
            </div>
            <div class="col-6">
                <h6 class="text-uppercase text-muted fw-bold small mb-2 border-bottom pb-1">Course Identity</h6>
                <table class="table table-sm table-borderless text-dark">
                    <tr><td width="130" class="text-muted">Course Title</td><td class="fw-bold">{{ $course->title }}</td></tr>
                    <tr><td class="text-muted">Faculty</td><td class="fw-bold">{{ $course->instructor ?? 'Assigned Instructor' }}</td></tr>
                    <tr><td class="text-muted">Credits/Modules</td><td class="fw-bold">{{ $course->lessons->count() }}</td></tr>
                    <tr><td class="text-muted">Platform Sync</td><td class="fw-bold text-success"><i class="fas fa-check-circle"></i> Authenticated</td></tr>
                </table>
            </div>
        </div>

        <h5 class="fw-bold text-dark border-bottom pb-2 mb-3"><i class="fas fa-chart-bar text-primary me-2"></i> Performance Metrics</h5>
        
        <div class="row h-100">
            <div class="col-md-6 mb-4">
                <h6 class="fw-bold text-dark small text-uppercase">Task Submissions</h6>
                @if($course->tasks->count() > 0)
                    <table class="table table-bordered table-sm small">
                        <thead class="table-light"><tr><th>Assignment</th><th>Grade</th></tr></thead>
                        <tbody>
                            @foreach($course->tasks as $task)
                                @php $sub = $taskSubmissions->where('task_id', $task->id)->first(); @endphp
                                <tr>
                                    <td>{{ $task->title }}</td>
                                    <td class="text-center fw-bold text-{{ $sub ? 'success' : 'danger' }}">{{ $sub ? ($sub->grade ?? 'Pending') : 'N/A' }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="border p-3 text-center text-muted small rounded">No assignments recorded.</div>
                @endif
            </div>

            <div class="col-md-6 mb-4">
                <h6 class="fw-bold text-dark small text-uppercase">Interactive Quizzes</h6>
                @if($course->quizzes->count() > 0)
                    <table class="table table-bordered table-sm small">
                        <thead class="table-light"><tr><th>Assessment</th><th>Score</th><th>Status</th></tr></thead>
                        <tbody>
                            @foreach($course->quizzes as $quiz)
                                @php $qSub = $quizAttempts->where('quiz_id', $quiz->id)->first(); @endphp
                                <tr>
                                    <td>{{ $quiz->title }}</td>
                                    <td class="text-center fw-bold">{{ $qSub ? $qSub->score : '0' }} / {{ $quiz->questions->sum('points') }}</td>
                                    <td class="text-center fw-bold text-{{ ($qSub && $qSub->passed) ? 'success' : 'danger' }}">
                                        {{ ($qSub && $qSub->passed) ? 'PASS' : 'FAIL' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="border p-3 text-center text-muted small rounded">No assessments recorded.</div>
                @endif
            </div>
        </div>

        @if($course->transcript_content)
        <div class="mt-2">
            <h6 class="fw-bold text-dark small text-uppercase border-bottom pb-1">Course Curriculum Highlights</h6>
            <div class="text-dark small" style="white-space: pre-line;">{{ $course->transcript_content }}</div>
        </div>
        @endif

        <div class="position-absolute bottom-0 start-0 w-100 p-5 text-center text-muted small">
            <p class="mb-0">This transcript is automatically generated by BAPS-e.learn-LMS and does not require a signature.</p>
        </div>
            </div>
        </div>

        <!-- TAB 2: Certificate of Completion -->
        <div class="tab-pane fade" id="certificate-content" role="tabpanel">
            <div class="print-page bg-white shadow-lg mx-auto position-relative print-certificate mt-0 d-flex flex-column justify-content-center align-items-center text-center p-4 p-md-5" 
         style="max-width: 297mm; min-height: 210mm; border: 15px solid #1e293b; background: radial-gradient(circle at center, #ffffff 0%, #f8fafc 100%);">
        
        <div class="certificate-inner border border-2 border-opacity-25 w-100 h-100 p-5 d-flex flex-column justify-content-center align-items-center position-relative" style="border-color: #94a3b8 !important;">
            
            <div style="width: 80px; height: 80px; background: var(--primary-gradient); border-radius: 20px; display: flex; align-items: center; justify-content: center; color: white; margin: 0 auto; box-shadow: 0 10px 25px rgba(79, 70, 229, 0.4);">
                <i class="fas fa-graduation-cap fa-2x"></i>
            </div>
            
            <h1 class="mt-4 mb-0 text-dark" style="font-family: 'Georgia', serif; font-size: 3rem; font-weight: 700; letter-spacing: -1px;">Certificate of Outstanding Completion</h1>
            <p class="text-muted text-uppercase tracking-widest mt-2 fw-bold" style="letter-spacing: 5px;">BAPS-e.learn-LMS Authenticated Credential</p>
            
            <div class="my-5 w-100">
                <p class="lead text-muted mb-2 font-italic" style="font-style: italic;">This is to certify that</p>
                <h2 class="display-3 fw-bold text-dark text-capitalize" style="font-family: 'Georgia', serif; border-bottom: 2px solid #e2e8f0; max-width: 70%; margin: 0 auto; padding-bottom: 5px;">{{ $user->name }}</h2>
                
                <p class="lead text-muted mt-4 mb-2" style="font-style: italic;">has successfully mastered the coursework and achieved excellence in</p>
                <h3 class="fw-bold text-primary display-6">{{ $course->title }}</h3>
            </div>
            
            <div class="d-flex justify-content-between align-items-end mt-auto w-100 px-4">
                <!-- Signature 1: Kothari/VC -->
                <div class="text-center signature-block">
                    <div class="signature-line" style="border-bottom: 2px solid #1e293b; width: 220px; margin-bottom: 10px; height: 40px;"></div>
                    <p class="fw-bold text-dark mb-0 fs-5" style="font-family: 'Georgia', serif;">Hon. Kothari / VC</p>
                    <span class="small text-muted text-uppercase tracking-widest" style="font-size: 0.7rem;">University Head</span>
                </div>

                <!-- Signature 2: Admin/Dean -->
                <div class="text-center signature-block">
                    <div class="mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=90x90&data={{ $certificate->unique_code }}" alt="QR Validation" class="border p-1 bg-white shadow-sm rounded">
                    </div>
                    <div class="signature-line" style="border-bottom: 2px solid #1e293b; width: 220px; margin-bottom: 10px;"></div>
                    <p class="fw-bold text-dark mb-0 fs-5" style="font-family: 'Georgia', serif;">Admin / Dean</p>
                    <span class="small text-muted text-uppercase tracking-widest" style="font-size: 0.7rem;">Academic Affairs</span>
                </div>

                <!-- Signature 3: Teaching Faculty -->
                <div class="text-center signature-block">
                    <div class="signature-line" style="border-bottom: 2px solid #1e293b; width: 220px; margin-bottom: 10px; height: 40px;"></div>
                    <p class="fw-bold text-dark mb-0 fs-5" style="font-family: 'Georgia', serif;">{{ $course->instructor ?? 'Faculty' }}</p>
                    <span class="small text-muted text-uppercase tracking-widest" style="font-size: 0.7rem;">Lead Instructor</span>
                </div>
            </div>

            <div class="position-absolute top-0 end-0 p-4">
                <p class="mb-0 text-muted" style="font-size: 0.7rem;">Date: <b>{{ $certificate->created_at->format('M d, Y') }}</b><br>ID: <b>{{ $certificate->unique_code }}</b></p>
            </div>
        </div>
    </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Base print preparations */
@media print {
    /* Hide layout cruft definitively */
    .no-print, .sidebar, .top-nav, #global-loader, footer, .theme-switch { 
        display: none !important; 
    }
    
    body, html {
        background: white !important;
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    
    .main-content {
        margin: 0 !important;
        padding: 0 !important;
        background: white !important;
        box-shadow: none !important;
    }
    
    /* Reveal all Tabs for Printing */
    .tab-content > .tab-pane {
        display: block !important;
        opacity: 1 !important;
        visibility: visible !important;
    }

    /* Force landscape paper globally */
    @page {
        size: A4 landscape;
        margin: 0;
    }

    .container {
        max-width: none !important;
        width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    /* Target specific print pages */
    .print-page {
        width: 296mm !important;
        height: 209mm !important;
        max-width: none !important;
        min-height: auto !important;
        margin: 0 !important;
        box-shadow: none !important;
        page-break-after: always;
        break-after: page;
        padding: 15mm !important;
    }

    .print-certificate {
        background: white !important;
        border: 10px solid #1e293b !important; /* Retain premium dark frame */
        /* Center content effectively */
        padding: 10mm !important;
    }
    
    /* Ensure colors are printed */
    * {
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
}
</style>

@endsection
