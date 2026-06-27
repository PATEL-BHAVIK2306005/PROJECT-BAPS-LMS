<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assignment Evaluation Report — {{ $submission->task->title }}</title>
    <!-- Fonts & Icons -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Merriweather:wght@400;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --baps-saffron: #f97316;
            --baps-blue: #1e3a8a;
            --baps-red: #dc2626;
            --baps-gold: #d97706;
            --baps-text: #1f2937;
            --baps-border: #e5e7eb;
        }
        body {
            background-color: #f3f4f6;
            color: var(--baps-text);
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }

        /* Floating Non-Printing Action Bar */
        .print-action-bar {
            background: #111827;
            color: #fff;
            padding: 16px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
        }
        .print-btn {
            background: var(--baps-saffron);
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
        }
        .print-btn:hover { background: #ea580c; }
        .download-btn {
            background: #2563eb;
            color: #fff;
            border: none;
            padding: 10px 24px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .download-btn:hover { background: #1d4ed8; }

        /* Printable Page Canvas */
        .document-page {
            background: #ffffff;
            width: 210mm;
            min-height: 297mm;
            margin: 40px auto;
            padding: 25mm 20mm;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            box-sizing: border-box;
            position: relative;
            background-image: radial-gradient(rgba(249, 115, 22, 0.03) 1px, transparent 0);
            background-size: 24px 24px;
        }

        /* Institutional Letterhead */
        .letterhead {
            border-bottom: 3px double var(--baps-blue);
            padding-bottom: 24px;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo-box {
            width: 90px;
            height: 90px;
            background: var(--baps-blue);
            color: #fff;
            border-radius: 16px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 1.8rem;
            line-height: 1;
            box-shadow: 0 4px 10px rgba(30,58,138,0.2);
        }
        .logo-box span { font-size: 0.7rem; font-weight: 600; letter-spacing: 2px; margin-top: 4px; }
        .inst-title {
            text-align: center;
            flex-grow: 1;
            padding: 0 20px;
        }
        .inst-name { font-family: 'Merriweather', serif; font-size: 2rem; font-weight: 700; color: var(--baps-blue); margin: 0 0 4px 0; line-height: 1.2; }
        .inst-sub { font-size: 0.9rem; font-weight: 600; color: #4b5563; text-transform: uppercase; letter-spacing: 1px; margin: 0 0 6px 0; }
        .inst-meta { font-size: 0.8rem; color: #6b7280; margin: 0; }
        .motto-box {
            text-align: right;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--baps-saffron);
            max-width: 150px;
        }

        /* Dispatch & Ref Bar */
        .dispatch-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: 'Space Mono', monospace;
            font-size: 0.85rem;
            color: #4b5563;
            margin-bottom: 32px;
            background: #f8fafc;
            padding: 10px 16px;
            border-radius: 8px;
            border: 1px solid var(--baps-border);
        }
        .dispatch-bar .fw-bold { color: #111827; font-weight: 700; }

        /* Document Title Header */
        .doc-title-header {
            text-align: center;
            margin-bottom: 36px;
        }
        .doc-title-header h2 {
            font-family: 'Merriweather', serif;
            font-size: 1.8rem;
            font-weight: 700;
            color: #111827;
            margin: 0 0 8px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .title-underline {
            width: 120px;
            height: 4px;
            background: var(--baps-saffron);
            margin: 0 auto;
            border-radius: 2px;
        }

        /* Student Bio Profile Box */
        .student-bio-box {
            background: #f8fafc;
            border: 1px solid var(--baps-border);
            border-left: 4px solid var(--baps-blue);
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 36px;
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .bio-avatar {
            width: 70px;
            height: 70px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .bio-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px 24px;
            flex-grow: 1;
        }
        .bio-item { font-size: 0.9rem; }
        .bio-label { font-weight: 600; color: #6b7280; font-size: 0.8rem; text-transform: uppercase; }
        .bio-val { font-weight: 700; color: #111827; }

        /* Section Headings */
        .section-header {
            font-family: 'Merriweather', serif;
            font-size: 1.2rem;
            color: var(--baps-blue);
            border-bottom: 2px solid var(--baps-border);
            padding-bottom: 6px;
            margin-top: 28px;
            margin-bottom: 16px;
            font-weight: 700;
        }

        /* Task Details and Description */
        .task-summary-box {
            background: #fafafa;
            border: 1px dashed var(--baps-border);
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 24px;
            font-size: 0.9rem;
        }
        .task-title { font-weight: 700; color: #111827; margin-bottom: 8px; }
        .task-desc { color: #4b5563; }

        /* Code Submission Box */
        .code-container {
            background: #0f172a;
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 32px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .code-header {
            background: #1e293b;
            color: #94a3b8;
            padding: 8px 16px;
            font-size: 0.8rem;
            font-family: 'Space Mono', monospace;
            display: flex;
            justify-content: space-between;
            border-bottom: 1px solid #334155;
        }
        .code-body {
            margin: 0;
            padding: 16px;
            overflow-x: auto;
        }
        .code-body pre {
            margin: 0;
            color: #38bdf8;
            font-family: 'Space Mono', 'Fira Code', monospace;
            font-size: 0.85rem;
            line-height: 1.5;
        }

        /* Evaluation Score Card */
        .score-card {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 32px;
        }
        .score-item {
            background: #f8fafc;
            border: 1px solid var(--baps-border);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
        }
        .score-item-title {
            font-size: 0.75rem;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 6px;
        }
        .score-value {
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--baps-blue);
            line-height: 1.2;
        }
        .score-value.success { color: #16a34a; }
        .score-value.warning { color: var(--baps-saffron); }

        /* Feedback Box */
        .feedback-box {
            background: #fffdf5;
            border: 1px solid #fef3c7;
            border-left: 4px solid var(--baps-gold);
            border-radius: 8px;
            padding: 16px;
            font-style: italic;
            font-size: 0.95rem;
            color: #78350f;
            margin-bottom: 40px;
        }

        /* Official Sign & Seal Footer */
        .doc-footer {
            margin-top: 64px;
            padding-top: 32px;
            border-top: 2px solid var(--baps-border);
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            page-break-inside: avoid;
        }
        .qr-box { display: flex; align-items: center; gap: 16px; }
        .qr-placeholder { width: 70px; height: 70px; background: #fff; border: 2px solid #111827; padding: 4px; display: flex; flex-direction: column; align-items: center; justify-content: center; font-family: 'Space Mono'; font-size: 0.6rem; font-weight: 700; text-align: center; }
        .qr-text { font-size: 0.8rem; color: #6b7280; max-width: 200px; }
        .seal-box { text-align: center; position: relative; }
        .seal-circle { width: 100px; height: 100px; border: 2px dashed var(--baps-saffron); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; color: var(--baps-saffron); text-transform: uppercase; margin: 0 auto 12px auto; transform: rotate(-15deg); opacity: 0.85; }
        .sign-box { text-align: center; }
        .sign-placeholder { 
            font-family: 'Merriweather', serif; 
            font-size: 1.2rem; 
            color: var(--baps-blue); 
            font-style: italic; 
            margin-bottom: 4px; 
            border-bottom: 1px solid #111827; 
            padding-bottom: 4px; 
            display: inline-block; 
            width: 160px;
            height: 40px;
        }
        .sign-placeholder svg { width: 100%; height: 100%; }
        .sign-label { font-size: 0.85rem; font-weight: 700; color: #111827; margin: 0; }
        .sign-sub { font-size: 0.75rem; color: #6b7280; margin: 0; }

        @media print {
            body { background: #fff; }
            .print-action-bar { display: none !important; }
            .document-page { box-shadow: none; margin: 0; padding: 0; width: 100%; min-height: auto; background-image: none; }
        }
    </style>
</head>
<body>

    <!-- Floating Non-Printing Action Bar -->
    <div class="print-action-bar">
        <div>
            <h4 style="margin: 0; font-size: 1.1rem; font-weight: 700;">BAPS Institutional Records System</h4>
            <div style="font-size: 0.8rem; opacity: 0.8;">Cryptographically verified official assignment evaluation reports</div>
        </div>
        <div style="display: flex; gap: 16px;">
            <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Print Report</button>
            <a href="javascript:window.print()" class="download-btn"><i class="fas fa-download"></i> Save PDF</a>
        </div>
    </div>

    <!-- Printable Page Canvas -->
    <div class="document-page">

        <!-- Institutional Letterhead -->
        <div class="letterhead">
            <div class="logo-box">
                BAPS<span>SVM 2026</span>
            </div>
            <div class="inst-title">
                <h1 class="inst-name">BAPS Swaminarayan Vidyamandir</h1>
                <div class="inst-sub">Accredited by NAAC with A++ Grade | AICTE Approved Institution</div>
                <div class="inst-meta">Pramukh Swami Marg, Institutional Area, Gujarat, India — 388120</div>
            </div>
            <div class="motto-box">
                <i class="fas fa-om" style="font-size: 1.4rem; margin-bottom: 4px;"></i><br>
                "Vidya Amrutam Ashnute"
            </div>
        </div>

        <!-- Dispatch & Ref Bar -->
        <div class="dispatch-bar">
            <div><span class="fw-bold">REF NO:</span> BAPS/IPDC/EVAL/{{ \Carbon\Carbon::now()->format('Y') }}/{{ strtoupper(substr(md5($submission->id), 0, 6)) }}</div>
            <div><span class="fw-bold">EVALUATION DATE:</span> {{ $submission->updated_at->format('F d, Y') }}</div>
            <div><span class="fw-bold">STATUS:</span> <span style="color: #16a34a; font-weight: 700;">GRADED & VERIFIED</span></div>
        </div>

        <!-- Document Title Header -->
        <div class="doc-title-header">
            <h2>{{ $docTitle }}</h2>
            <div class="title-underline"></div>
        </div>

        <!-- Student Bio Profile Box -->
        <div class="student-bio-box">
            <img src="https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=1e3a8a&color=fff&size=150" alt="Student Avatar" class="bio-avatar">
            <div class="bio-grid">
                <div class="bio-item"><span class="bio-label">Student Name:</span> <span class="bio-val">{{ $student->name }}</span></div>
                <div class="bio-item"><span class="bio-label">Enrollment No:</span> <span class="bio-val">{{ $student->enrollment_no }}</span></div>
                <div class="bio-item"><span class="bio-label">Academic Program:</span> <span class="bio-val">{{ $student->program ?? 'B.Tech CSE' }}</span></div>
                <div class="bio-item"><span class="bio-label">Department:</span> <span class="bio-val">{{ $student->department_name ?? 'Computer Science & Engineering' }}</span></div>
                <div class="bio-item"><span class="bio-label">Academic Course:</span> <span class="bio-val">{{ $submission->task->course->title ?? 'N/A' }}</span></div>
                <div class="bio-item"><span class="bio-label">Course Subject:</span> <span class="bio-val">{{ $submission->task->subject->name ?? 'Integrated Personality Development' }}</span></div>
            </div>
        </div>

        <!-- Section: Assignment Details -->
        <div class="section-header">Assignment Brief</div>
        <div class="task-summary-box">
            <div class="task-title">{{ $submission->task->title }}</div>
            <div class="task-desc">{{ strip_tags($submission->task->description) }}</div>
        </div>

        <!-- Section: Submission Content -->
        <div class="section-header">Submitted Source Code</div>
        <div class="code-container">
            <div class="code-header">
                <span>solution{{ $submission->mime_type === 'application/javascript' ? '.js' : ($submission->mime_type === 'text/x-python' ? '.py' : ($submission->mime_type === 'text/x-java-source' ? '.java' : '.txt')) }}</span>
                <span>Submitted {{ $submission->created_at->format('M d, Y H:i') }}</span>
            </div>
            <div class="code-body">
                <pre><code>{{ $submission->file_content ?? '# No code submitted.' }}</code></pre>
            </div>
        </div>

        <!-- Section: Evaluation Details -->
        <div class="section-header">Evaluation Marks & Review</div>
        
        <div class="score-card">
            <div class="score-item">
                <div class="score-item-title">Points Awarded</div>
                <div class="score-value success">{{ $submission->grade }}</div>
            </div>
            <div class="score-item">
                <div class="score-item-title">Max Points</div>
                <div class="score-value">{{ $submission->task->max_points }}</div>
            </div>
            <div class="score-item">
                <div class="score-item-title">Percentage</div>
                @php 
                    $pct = ($submission->task->max_points > 0) ? round(($submission->grade / $submission->task->max_points) * 100, 1) : 0; 
                @endphp
                <div class="score-value warning">{{ $pct }}%</div>
            </div>
        </div>

        @if($submission->feedback)
            <div class="bio-label" style="margin-bottom: 8px;">Faculty Evaluator Feedback:</div>
            <div class="feedback-box">
                "{{ $submission->feedback }}"
            </div>
        @endif

        <!-- Official Sign & Seal Footer -->
        <div class="doc-footer">
            <div class="qr-box">
                <div class="qr-placeholder">
                    QR<br>CODE
                </div>
                <div class="qr-text">
                    Scan to verify this official evaluation record on the BAPS University academic database registry.
                </div>
            </div>
            
            <div class="sign-box">
                <div class="sign-placeholder">
                    {!! $evaluatorSignature !!}
                </div>
                <h5 class="sign-label">{{ $evaluatorName }}</h5>
                <p class="sign-sub">Grader / Evaluator</p>
            </div>
            
            <div class="sign-box">
                <div class="sign-placeholder">
                    {!! $hodSignature !!}
                </div>
                <h5 class="sign-label">{{ $hodName }}</h5>
                <p class="sign-sub">Head of Department</p>
            </div>

            <div class="sign-box">
                <div class="sign-placeholder">
                    {!! $deanSignature !!}
                </div>
                <h5 class="sign-label">{{ $deanName }}</h5>
                <p class="sign-sub">Dean Academics</p>
            </div>
        </div>

    </div>
</body>
</html>
