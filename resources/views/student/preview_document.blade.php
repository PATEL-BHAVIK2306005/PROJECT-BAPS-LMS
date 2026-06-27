<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Document Preview - {{ $course->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-gradient: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        }

        body {
            background-color: #f1f5f9;
            margin: 0;
            padding: 20px;
        }

        .no-print-zone {
            max-width: 297mm;
            margin: 0 auto 20px auto;
            text-align: right;
        }

        /* Essential Print Styles */
        @media print {
            .no-print { display: none !important; }
            body.print-only-transcript .certificate-page { display: none !important; }
            body.print-only-certificate .transcript-page { display: none !important; }
            
            body { 
                background: white !important; 
                padding: 0 !important; 
                margin: 0 !important; 
            }
            
            @page {
                margin: 0;
            }

            @page transcript {
                size: A4 portrait;
            }

            @page certificate {
                size: A4 landscape;
            }

            .transcript-page { 
                page: transcript;
                width: 210mm !important;
                height: 297mm !important;
                display: flex !important;
                flex-direction: column !important;
                padding: 15mm !important;
            }

            .certificate-page { 
                page: certificate;
                width: 297mm !important;
                height: 210mm !important;
                display: flex !important;
                flex-direction: column !important;
                padding: 12mm !important;
            }

            .print-certificate {
                border: 12px solid #1e293b !important;
                padding: 10mm !important;
            }
            
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
        }

        /* Screen Preview Styles */
        .print-page {
            background: white;
            margin: 0 auto 40px auto;
            box-shadow: 0 30px 60px rgba(0,0,0,0.15);
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            display: flex;
            flex-direction: column;
        }

        .transcript-page {
            width: 210mm;
            height: 297mm;
            padding: 20mm;
        }

        .certificate-page {
            width: 297mm;
            height: 210mm;
            padding: 15mm;
        }

        .print-certificate {
            border: 15px solid #1e293b;
            background: radial-gradient(circle at center, #ffffff 0%, #f8fafc 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .certificate-inner {
            border: 2px solid #94a3b8;
            width: 100%;
            height: 100%;
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            position: relative;
        }

        .transcript-page {
            border-left: 8px solid #4f46e5;
        }

        .signature-line {
            border-bottom: 2px solid #1e293b;
            width: 220px;
            margin-bottom: 10px;
        }

        .tracking-widest { letter-spacing: 0.1em; }
    </style>
</head>
<body>

<div class="no-print-zone no-print">
    <button onclick="printTranscript()" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm">
        <i class="fas fa-file-invoice me-2"></i> Print Transcript Only
    </button>
    <button onclick="printCertificate()" class="btn btn-dark fw-bold rounded-pill px-4 shadow-sm ms-2">
        <i class="fas fa-certificate me-2"></i> Print Certificate Only
    </button>
    <button onclick="printBoth()" class="btn btn-outline-primary fw-bold rounded-pill px-4 shadow-sm ms-2">
        <i class="fas fa-print me-2"></i> Print Both Pages
    </button>
    <button onclick="window.close()" class="btn btn-outline-secondary fw-bold rounded-pill px-4 shadow-sm ms-2">
        Close Preview
    </button>
</div>

<!-- PAGE 1: Academic Transcript -->
<div class="print-page transcript-page">
    <div class="d-flex justify-content-between border-bottom pb-3 mb-3">
        <div class="d-flex align-items-center gap-3">
            <div style="width: 50px; height: 50px; background: var(--primary-gradient); border-radius: 10px; display: flex; align-items: center; justify-content: center; color: white; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                <i class="fas fa-university fa-lg"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">BAPS-e.learn-LMS</h4>
                <p class="text-primary text-uppercase small tracking-widest mb-0 fw-bold" style="font-size: 0.65rem;">Official Academic Credential & Transcript</p>
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

    <h5 class="fw-bold text-dark border-bottom pb-1 mb-2" style="font-size: 1.1rem;"><i class="fas fa-chart-bar text-primary me-2"></i> Performance Metrics</h5>
    
    <div class="row">
        <div class="col-6">
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.75rem; text-uppercase: uppercase;">Task Submissions</h6>
            @if($course->tasks->count() > 0)
                <table class="table table-bordered table-sm small mb-0">
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
                <div class="border p-2 text-center text-muted small rounded bg-light">No assignments recorded.</div>
            @endif
        </div>

        <div class="col-6">
            <h6 class="fw-bold text-dark mb-1" style="font-size: 0.75rem; text-uppercase: uppercase;">Interactive Quizzes</h6>
            @if($course->quizzes->count() > 0)
                <table class="table table-bordered table-sm small mb-0">
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
                <div class="border p-2 text-center text-muted small rounded bg-light">No assessments recorded.</div>
            @endif
        </div>
    </div>

    @if($course->transcript_content)
    <div class="mt-3 pb-2">
        <h6 class="fw-bold text-dark text-uppercase border-bottom pb-1 mb-2" style="font-size: 0.75rem;"><i class="fas fa-book-open text-primary me-2"></i> Detailed Course Curriculum & Learning Outcomes</h6>
        <div class="text-dark" style="white-space: pre-line; font-size: 0.7rem; column-count: 2; column-gap: 30px; line-height: 1.3; text-align: justify;">{{ $course->transcript_content }}</div>
    </div>
    @endif

    <div class="mt-auto pt-4 text-center text-muted small border-top">
        <p class="mb-0">This transcript is automatically generated by BAPS-e.learn-LMS and does not require a physical signature for academic verification.</p>
    </div>
</div>

<!-- PAGE 2: Certificate of Completion -->
<div class="print-page print-certificate certificate-page">
    <div class="certificate-inner">
        <!-- Absolute Corner Elements (No Layout Impact) -->
        <div class="position-absolute top-0 end-0 p-4 text-end">
            <p class="text-muted mb-0" style="font-size: 0.65rem;">Date: {{ \Carbon\Carbon::parse($certificate->created_at)->format('M d, Y') }}</p>
            <p class="text-muted fw-bold mb-0" style="font-size: 0.65rem;">ID: {{ $certificate->unique_code }}</p>
        </div>

        <div class="text-center" style="margin-top: 60px;">
            <h1 class="display-4 fw-bold text-dark mb-0" style="font-family: 'Times New Roman', serif; letter-spacing: -1px;">Certificate of Outstanding Completion</h1>
            <p class="text-muted text-uppercase tracking-widest small fw-bold mt-2">BAPS-e.learn-LMS Authenticated Credential</p>
        </div>

        <div class="text-center my-5">
            <p class="fst-italic text-muted mb-3" style="font-size: 1.1rem;">This is to certify that</p>
            <h1 class="display-1 fw-bold text-dark border-bottom d-inline-block px-5 pb-2 mb-4" style="font-family: 'Times New Roman', serif; border-color: #cbd5e1 !important;">{{ $user->name }}</h1>
            <p class="text-muted mb-4" style="font-size: 1.1rem;">has successfully mastered the coursework and achieved excellence in</p>
            <h3 class="fw-bold text-primary display-5">{{ $course->title }}</h3>
        </div>
        
        <div class="d-flex justify-content-between align-items-end mt-auto w-100 px-4 pb-4">
            <div class="text-center signature-block">
                <div class="signature-line"></div>
                <p class="fw-bold text-dark mb-0 fs-5" style="font-family: 'Georgia', serif;">Hon. Kothari / VC</p>
                <span class="small text-muted text-uppercase tracking-widest" style="font-size: 0.7rem;">University Head</span>
            </div>

            <div class="text-center signature-block">
                <div class="signature-line"></div>
                <p class="fw-bold text-dark mb-0 fs-5" style="font-family: 'Georgia', serif;">Admin / Dean</p>
                <span class="small text-muted text-uppercase tracking-widest" style="font-size: 0.7rem;">Academic Affairs</span>
            </div>

            <div class="text-center signature-block">
                <div class="signature-line"></div>
                <p class="fw-bold text-dark mb-0 fs-5" style="font-family: 'Georgia', serif;">{{ $course->instructor ?? 'Lead Faculty' }}</p>
                <span class="small text-muted text-uppercase tracking-widest" style="font-size: 0.7rem;">Lead Instructor</span>
            </div>
        </div>
    </div>
</div>

<script>
    function printTranscript() {
        document.body.classList.add('print-only-transcript');
        document.body.classList.remove('print-only-certificate');
        window.print();
    }
    
    function printCertificate() {
        document.body.classList.add('print-only-certificate');
        document.body.classList.remove('print-only-transcript');
        window.print();
    }

    function printBoth() {
        document.body.classList.remove('print-only-transcript');
        document.body.classList.remove('print-only-certificate');
        window.print();
    }

    // Default: Just show preview, don't auto-print everything
    window.onload = function() { 
        console.log("Premium Document Preview Loaded.");
    };
</script>

</body>
</html>
