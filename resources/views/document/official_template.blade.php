<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $docTitle }} — Official Student Record</title>
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
        .dispatch-bar fw-bold { color: #111827; }

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
            padding: 24px;
            margin-bottom: 36px;
            display: flex;
            align-items: center;
            gap: 24px;
        }
        .bio-avatar {
            width: 80px;
            height: 80px;
            border-radius: 12px;
            object-fit: cover;
            border: 2px solid #fff;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .bio-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px 24px;
            flex-grow: 1;
        }
        .bio-item { font-size: 0.95rem; }
        .bio-label { font-weight: 600; color: #6b7280; font-size: 0.85rem; text-transform: uppercase; }
        .bio-val { font-weight: 700; color: #111827; }

        /* Document Body Content */
        .doc-body {
            font-size: 1rem;
            color: #374151;
            line-height: 1.8;
            margin-bottom: 48px;
        }
        .doc-body p { margin-top: 0; margin-bottom: 20px; text-align: justify; }

        /* Data Tables */
        .doc-table {
            width: 100%;
            border-collapse: collapse;
            margin: 28px 0;
        }
        .doc-table th { background: #f1f5f9; color: #1e293b; font-weight: 700; font-size: 0.9rem; text-transform: uppercase; padding: 12px 16px; border: 1px solid var(--baps-border); text-align: left; }
        .doc-table td { padding: 12px 16px; border: 1px solid var(--baps-border); font-size: 0.95rem; color: #334155; }

        /* ID Card & Special Layouts */
        .id-card-wrapper {
            width: 340px;
            border: 2px solid var(--baps-blue);
            border-radius: 16px;
            overflow: hidden;
            margin: 20px auto;
            box-shadow: 0 10px 25px rgba(30,58,138,0.15);
            background: #fff;
        }
        .id-header { background: var(--baps-blue); color: #fff; padding: 16px; text-align: center; }
        .id-header h4 { margin: 0; font-size: 1.2rem; font-family: 'Merriweather', serif; }
        .id-header p { margin: 2px 0 0 0; font-size: 0.75rem; opacity: 0.8; }
        .id-body { padding: 24px; text-align: center; }
        .id-photo { width: 110px; height: 110px; border-radius: 50%; object-fit: cover; border: 3px solid var(--baps-saffron); margin-bottom: 16px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        .id-name { font-size: 1.3rem; font-weight: 800; color: #111827; margin: 0 0 4px 0; }
        .id-enr { font-family: 'Space Mono', monospace; font-size: 0.95rem; color: var(--baps-saffron); font-weight: 700; margin: 0 0 16px 0; }
        .id-details { text-align: left; background: #f8fafc; padding: 16px; border-radius: 12px; font-size: 0.85rem; margin-bottom: 20px; }
        .id-details div { margin-bottom: 6px; border-bottom: 1px solid #e2e8f0; padding-bottom: 4px; }
        .id-details div:last-child { border: none; margin: 0; padding: 0; }
        .id-footer { background: #f1f5f9; padding: 12px; text-align: center; font-size: 0.75rem; color: #64748b; font-weight: 600; border-top: 1px solid var(--baps-border); }

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
        .sign-placeholder { font-family: 'Merriweather', serif; font-size: 1.6rem; color: var(--baps-blue); font-style: italic; margin-bottom: 8px; border-bottom: 1px solid #111827; padding-bottom: 4px; display: inline-block; width: 180px; }
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
            <div style="font-size: 0.8rem; opacity: 0.8;">Cryptographically verified official student document generation engine</div>
        </div>
        <div style="display: flex; gap: 16px;">
            <button class="print-btn" onclick="window.print()"><i class="fas fa-print"></i> Print Official Record</button>
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
            <div><span class="fw-bold">REF NO:</span> BAPS/SVM/2026/{{ strtoupper(substr(md5($docTitle), 0, 6)) }}-{{ $student->id ?? 101 }}</div>
            <div><span class="fw-bold">DISPATCH DATE:</span> {{ \Carbon\Carbon::now()->format('F d, Y') }}</div>
            <div><span class="fw-bold">STATUS:</span> <span style="color: #16a34a; font-weight: 700;">VERIFIED & ATTESTED</span></div>
        </div>

        <!-- Document Title Header -->
        <div class="doc-title-header">
            <h2>{{ $docTitle }}</h2>
            <div class="title-underline"></div>
        </div>

        <!-- Student Bio Profile Box -->
        <div class="student-bio-box">
            @php
                $names = array_map('trim', explode(',', $studentName));
                $enrollments = array_map('trim', explode(',', $enrollmentNo));
                $roles = isset($recipientRole) ? array_map('trim', explode(',', $recipientRole)) : ['Student'];
                $isMultiple = count($names) > 1;
            @endphp
            @if(!$isMultiple)
                <img src="{{ $photoUrl ?? 'https://ui-avatars.com/api/?name='.urlencode($studentName).'&background=1e3a8a&color=fff&size=150' }}" alt="Student Avatar" class="bio-avatar">
                <div class="bio-grid">
                    <div class="bio-item"><span class="bio-label">Student Name:</span> <span class="bio-val">{{ $studentName }}</span></div>
                    <div class="bio-item"><span class="bio-label">Enrollment No:</span> <span class="bio-val">{{ $enrollmentNo }}</span></div>
                    <div class="bio-item"><span class="bio-label">Academic Program:</span> <span class="bio-val">{{ $program }}</span></div>
                    <div class="bio-item"><span class="bio-label">Department:</span> <span class="bio-val">{{ $departmentName }}</span></div>
                    <div class="bio-item"><span class="bio-label">Current Year / Sem:</span> <span class="bio-val">Year {{ $year }} / Semester {{ $semester }}</span></div>
                    <div class="bio-item"><span class="bio-label">Institutional Email:</span> <span class="bio-val">{{ strtolower(str_replace(' ', '.', $studentName)) }}@baps.edu.in</span></div>
                </div>
            @else
                <div style="width: 100%;">
                    <h5 class="fw-bold mb-3" style="color: var(--baps-blue) !important; font-weight: 700; font-size: 1.1rem; display: flex; align-items: center; gap: 8px; margin-top: 0;">
                        <i class="fas fa-users"></i> Recipient Group / Core Team Members
                    </h5>
                    <div style="overflow-x: auto;">
                        <table class="doc-table" style="margin: 0; width: 100%;">
                            <thead>
                                <tr>
                                    <th style="padding: 8px 12px; font-size: 0.85rem;">#</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem;">Student Name</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem;">Enrollment No</th>
                                    <th style="padding: 8px 12px; font-size: 0.85rem;">Role / Designation</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($names as $idx => $name)
                                    <tr>
                                        <td style="padding: 8px 12px; font-size: 0.9rem;">{{ $idx + 1 }}</td>
                                        <td style="padding: 8px 12px; font-size: 0.9rem; font-weight: 700;">{{ $name }}</td>
                                        <td style="padding: 8px 12px; font-size: 0.9rem;"><code>{{ $enrollments[$idx] ?? 'N/A' }}</code></td>
                                        <td style="padding: 8px 12px; font-size: 0.9rem;">
                                            <span style="background: #f1f5f9; color: #475569; border: 1px solid #cbd5e1; padding: 2px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: 600;">
                                                {{ $roles[$idx] ?? $roles[0] ?? 'Student' }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>

        <!-- Document Body Content (Dynamic AI & Accredited Templates) -->
        <div class="doc-body">
            @if(isset($generatedBody) && !empty($generatedBody))
                {!! $generatedBody !!}
            @elseif($docTitle == 'College Admission Letter')
                <p>This is to officially certify that <strong>{{ $studentName }}</strong> has been formally admitted to the <strong>{{ $program }}</strong> degree program in the <strong>{{ $departmentName }}</strong> at BAPS Swaminarayan Vidyamandir for the academic batch starting 2026.</p>
                <p>The admission has been granted under the General Merit Quota following the successful clearance of the Institutional Entrance Examination and rigorous counseling verifications. The student has satisfied all statutory eligibility criteria prescribed by the University Senate and AICTE.</p>
                <table class="doc-table">
                    <thead><tr><th>Admission Category</th><th>Allotted Branch</th><th>Reporting Date</th><th>Academic Year</th></tr></thead>
                    <tbody><tr><td>Merit Quota (General)</td><td>{{ $departmentName }}</td><td>July 15, 2026</td><td>2026-2030</td></tr></tbody>
                </table>
                <p>The student is advised to maintain strict adherence to the Panchvartman moral code and academic regulations throughout their tenure at the institution.</p>

            @elseif($docTitle == 'College Identity Card (ID Card)')
                <div class="id-card-wrapper">
                    <div class="id-header">
                        <h4>BAPS VIDYAMANDIR</h4>
                        <p>STUDENT IDENTIFICATION CREDENTIAL</p>
                    </div>
                    <div class="id-body">
                        <img src="{{ $photoUrl ?? 'https://ui-avatars.com/api/?name='.urlencode($studentName).'&background=1e3a8a&color=fff&size=150' }}" class="id-photo">
                        <h5 class="id-name">{{ strtoupper($studentName) }}</h5>
                        <div class="id-enr">{{ $enrollmentNo }}</div>
                        <div class="id-details">
                            <div><strong>Program:</strong> {{ $program }}</div>
                            <div><strong>Branch:</strong> {{ $departmentName }}</div>
                            <div><strong>Valid Thru:</strong> June 2030</div>
                            <div><strong>Blood Group:</strong> O+ (Positive)</div>
                            <div><strong>Emergency Ph:</strong> +91 98765 43210</div>
                        </div>
                    </div>
                    <div class="id-footer">PROPERTY OF BAPS INSTITUTIONAL CAMPUS</div>
                </div>

            @elseif($docTitle == 'Semester Grade Sheets / Marksheets')
                <p>Official statement of academic performance and evaluation grades achieved by <strong>{{ $studentName }}</strong> (Enrollment: {{ $enrollmentNo }}) for Semester {{ $semester }} examinations held in May 2026.</p>
                <table class="doc-table">
                    <thead><tr><th>Course Code</th><th>Course Title</th><th>Credits</th><th>Grade Awarded</th><th>Grade Points</th></tr></thead>
                    <tbody>
                        <tr><td>CSE-301</td><td>Advanced Data Structures & Algorithms</td><td>4.0</td><td>A+</td><td>10.0</td></tr>
                        <tr><td>CSE-302</td><td>Database Management Systems</td><td>4.0</td><td>A</td><td>9.0</td></tr>
                        <tr><td>CSE-303</td><td>Operating Systems & Linux Internals</td><td>4.0</td><td>A+</td><td>10.0</td></tr>
                        <tr><td>MTH-301</td><td>Discrete Mathematics & Logic</td><td>3.0</td><td>B+</td><td>8.0</td></tr>
                        <tr><td>IPDC-101</td><td>Integrated Personality Development</td><td>2.0</td><td>A+</td><td>10.0</td></tr>
                    </tbody>
                </table>
                <div style="background: #f1f5f9; padding: 16px; border-radius: 8px; font-weight: 700; display: flex; justify-content: space-between;">
                    <span>SEMESTER GPA (SGPA): 9.42</span>
                    <span>CUMULATIVE GPA (CGPA): 9.38</span>
                    <span style="color: #16a34a;">RESULT: PASS (FIRST CLASS DISTINCTION)</span>
                </div>

            @elseif($docTitle == 'Consolidated Transcript')
                <p>This Consolidated Academic Transcript reflects the complete, cumulative academic credits and grades earned by <strong>{{ $studentName }}</strong> across all completed semesters in the {{ $program }} program.</p>
                <table class="doc-table">
                    <thead><tr><th>Semester</th><th>Total Credits</th><th>SGPA Earned</th><th>CGPA Cumulative</th><th>Status</th></tr></thead>
                    <tbody>
                        <tr><td>Semester I</td><td>22.0</td><td>9.10</td><td>9.10</td><td>Cleared</td></tr>
                        <tr><td>Semester II</td><td>24.0</td><td>9.45</td><td>9.28</td><td>Cleared</td></tr>
                        <tr><td>Semester III</td><td>24.0</td><td>9.42</td><td>9.33</td><td>Cleared</td></tr>
                        <tr><td>Semester IV</td><td>24.0</td><td>9.50</td><td>9.38</td><td>Cleared</td></tr>
                    </tbody>
                </table>
                <p><strong>Conversion Formula:</strong> Percentage (%) = (CGPA - 0.5) × 10. The academic medium of instruction and examination is strictly English.</p>

            @elseif($docTitle == 'Degree Certificate / Provisional Degree Certificate')
                <div style="border: 4px double #d97706; padding: 32px; text-align: center; border-radius: 12px; margin: 20px 0;">
                    <h3 style="font-family: 'Merriweather', serif; color: #1e3a8a; margin-bottom: 8px;">PROVISIONAL DEGREE CONFERRAL</h3>
                    <p style="font-size: 1.1rem; margin-bottom: 24px;">The Senate of BAPS Swaminarayan University hereby confers upon</p>
                    <h2 style="font-size: 2.2rem; color: #111827; margin: 0 0 8px 0; font-family: 'Merriweather', serif;">{{ $studentName }}</h2>
                    <p style="font-family: 'Space Mono', monospace; color: #d97706; font-size: 1.1rem; margin-bottom: 28px;">ENROLLMENT NUMBER: {{ $enrollmentNo }}</p>
                    <p style="font-size: 1.1rem; line-height: 1.8; margin-bottom: 32px;">the degree of <strong>{{ $program }} in {{ $departmentName }}</strong> with all the rights, honors, and privileges pertaining thereto, having successfully completed the prescribed curriculum and secured <strong>First Class with Distinction</strong>.</p>
                    <div style="font-size: 0.85rem; color: #6b7280; text-transform: uppercase; letter-spacing: 2px;">Issued under the seal of the University at Anand, Gujarat</div>
                </div>

            @elseif($docTitle == 'College Leaving Certificate (LC) / Transfer Certificate (TC)')
                <p>This is to certify that <strong>{{ $studentName }}</strong> was a bonafide student of BAPS Swaminarayan Vidyamandir from July 2022 to June 2026.</p>
                <table class="doc-table">
                    <tbody>
                        <tr><th>1. Name of Pupil</th><td>{{ $studentName }}</td></tr>
                        <tr><th>2. Enrollment / Registry No.</th><td>{{ $enrollmentNo }}</td></tr>
                        <tr><th>3. Nationality / Religion</th><td>Indian / Hindu</td></tr>
                        <tr><th>4. Date of First Admission</th><td>15th July 2022</td></tr>
                        <tr><th>5. Date of Leaving Institution</th><td>30th June 2026</td></tr>
                        <tr><th>6. Cause of Leaving</th><td>Completion of Degree Program</td></tr>
                        <tr><th>7. Conduct & Disciplinary Remark</th><td style="color: #16a34a; font-weight: 700;">EXEMPLARY / GOOD</td></tr>
                    </tbody>
                </table>
                <p>No academic or financial liabilities remain outstanding against the student. We wish them success in all future academic and professional endeavors.</p>

            @elseif($docTitle == 'Migration Certificate')
                <p>This is to certify that BAPS Swaminarayan Vidyamandir has no objection to the migration of <strong>{{ $studentName }}</strong> (Enrollment: {{ $enrollmentNo }}), a student of {{ $program }} in {{ $departmentName }}, to any other recognized University or academic institution for further higher studies.</p>
                <p>The student has successfully cleared all institutional requirements and has been issued their consolidated academic transcripts and leaving credentials.</p>

            @elseif($docTitle == 'Character / Conduct Certificate')
                <p>This is to formally certify that <strong>{{ $studentName }}</strong> (Enrollment: {{ $enrollmentNo }}) has been a bonafide student of the {{ $program }} degree program at BAPS Swaminarayan Vidyamandir.</p>
                <p>During their academic tenure at this institution, their conduct, moral character, and disciplinary record have been <strong>EXEMPLARY</strong>. They have actively demonstrated adherence to institutional values, ethical principles, and peer leadership.</p>
                <p>To the best of my knowledge, the student bears a good moral character and has never been involved in any act of indiscipline or misconduct. I wish them the very best for their future career.</p>

            @elseif($docTitle == 'Bonafide Student Certificate')
                <p>This is to certify that <strong>{{ $studentName }}</strong>, bearing Enrollment Number <strong>{{ $enrollmentNo }}</strong>, is a bonafide, active student of BAPS Swaminarayan Vidyamandir pursuing the <strong>{{ $program }}</strong> degree in <strong>{{ $departmentName }}</strong>.</p>
                <p>This certificate is being issued upon the specific request of the student for the purpose of passport issuance, education loan verification, and foreign embassy visa processing. The institution bears full accreditation under AICTE and UGC guidelines.</p>

            @elseif($docTitle == 'Fee Receipt (Current Semester/Year)')
                <p>Official acknowledgment of academic and institutional fee remittance by <strong>{{ $studentName }}</strong> for the academic session 2025-2026.</p>
                <table class="doc-table">
                    <thead><tr><th>Fee Head Description</th><th>Amount (₹)</th><th>Transaction ID</th><th>Payment Status</th></tr></thead>
                    <tbody>
                        <tr><td>Tuition Fee (Semester {{ $semester }})</td><td>₹ 65,000.00</td><td>TXN-89210491</td><td>Paid Online</td></tr>
                        <tr><td>Laboratory & Computing Fee</td><td>₹ 10,000.00</td><td>TXN-89210491</td><td>Paid Online</td></tr>
                        <tr><td>Library & E-Resources Fee</td><td>₹ 3,500.00</td><td>TXN-89210491</td><td>Paid Online</td></tr>
                        <tr><td>Student Activity & IPDC Fee</td><td>₹ 1,500.00</td><td>TXN-89210491</td><td>Paid Online</td></tr>
                        <tr style="background: #f1f5f9; font-weight: 700;"><td>TOTAL REMITTED AMOUNT</td><td>₹ 80,000.00</td><td>SUCCESS</td><td style="color: #16a34a;">VERIFIED</td></tr>
                    </tbody>
                </table>
                <p>This is a computer-generated official financial receipt and does not require physical signature endorsement.</p>

            @elseif($docTitle == 'No Dues Certificate (from Library/Department/Hostel)')
                <p>This is to certify that <strong>{{ $studentName }}</strong> (Enrollment: {{ $enrollmentNo }}) has successfully cleared all institutional dues, library books, laboratory equipment, and residential hostel liabilities.</p>
                <table class="doc-table">
                    <thead><tr><th>Department / Section</th><th>Clearance Officer</th><th>Outstanding Dues</th><th>Status</th></tr></thead>
                    <tbody>
                        <tr><td>Central University Library</td><td>Dr. R. K. Sharma (Chief Librarian)</td><td>₹ 0.00</td><td><span style="color: #16a34a; font-weight: 700;">CLEARED</span></td></tr>
                        <tr><td>{{ $departmentName }} Laboratories</td><td>Bhavik Patel (HOD CSE)</td><td>₹ 0.00</td><td><span style="color: #16a34a; font-weight: 700;">CLEARED</span></td></tr>
                        <tr><td>Hostel & Mess Administration</td><td>Swami Aksharcharan (Chief Warden)</td><td>₹ 0.00</td><td><span style="color: #16a34a; font-weight: 700;">CLEARED</span></td></tr>
                        <tr><td>Accounts & Finance Desk</td><td>Mr. Hasmukh Patel (Accounts Officer)</td><td>₹ 0.00</td><td><span style="color: #16a34a; font-weight: 700;">CLEARED</span></td></tr>
                    </tbody>
                </table>

            @elseif($docTitle == 'Internship Completion Certificate')
                <p>This is to certify that <strong>{{ $studentName }}</strong>, a student of {{ $program }} in {{ $departmentName }} at BAPS Swaminarayan Vidyamandir, has successfully completed a 12-week industrial training internship.</p>
                <p><strong>Project Title:</strong> Enterprise Cloud Architecture & API Microservices Integration.<br>
                <strong>Host Organization:</strong> Infosys Global Advanced Engineering Center, Pune.<br>
                <strong>Duration:</strong> May 15, 2025 to August 15, 2025.</p>
                <p>During the internship, the student demonstrated excellent technical proficiency, problem-solving skills, and professional dedication. Their overall project evaluation grade was awarded as <strong>A+ (Outstanding)</strong>.</p>

            @elseif($docTitle == 'Academic Project Report Approval Copy')
                <p>Official evaluation and approval certificate for the final-year Major Capstone Project submitted in partial fulfillment of the requirements for the award of the degree of {{ $program }}.</p>
                <div style="background: #f8fafc; padding: 24px; border: 1px solid var(--baps-border); border-radius: 12px; margin: 24px 0;">
                    <h4 style="margin: 0 0 12px 0; color: #1e3a8a;">PROJECT TITLE: AI-Powered Institutional Student Risk Predictor</h4>
                    <p style="margin: 0 0 6px 0;"><strong>Submitted By:</strong> {{ $studentName }} ({{ $enrollmentNo }})</p>
                    <p style="margin: 0 0 6px 0;"><strong>Project Guide:</strong> Prof. Dhaval Shah (Associate Professor, CSE)</p>
                    <p style="margin: 0 0 0 0;"><strong>External Examiner Evaluation:</strong> APPROVED WITH HIGHEST HONORS (Grade A+)</p>
                </div>
                <p>The examiners certify that this project report represents an authentic, original contribution to applied computer science and meets all academic standards prescribed by the University.</p>

            @elseif($docTitle == 'Course Syllabus Copy (Attested)')
                <p>Official attested copy of the curriculum and examination syllabus for the course <strong>CSE-301: Advanced Data Structures & Algorithms</strong>, as prescribed by the Board of Studies for the {{ $program }} program.</p>
                <div style="background: #fff; border: 1px solid var(--baps-border); padding: 20px; border-radius: 8px; font-size: 0.95rem;">
                    <h5 style="margin: 0 0 8px 0; color: #111827;">Module 1: Non-Linear Data Structures (12 Hours)</h5>
                    <p style="margin: 0 0 16px 0;">AVL Trees, Red-Black Trees, B-Trees, B+ Trees, Fibonacci Heaps, and Disjoint Set Data Structures.</p>
                    <h5 style="margin: 0 0 8px 0; color: #111827;">Module 2: Advanced Graph Algorithms (14 Hours)</h5>
                    <p style="margin: 0 0 16px 0;">Bellman-Ford Algorithm, Floyd-Warshall All-Pairs Shortest Path, Kruskal & Prim MST, Topological Sorting, and Network Flow (Ford-Fulkerson).</p>
                    <h5 style="margin: 0 0 8px 0; color: #111827;">Module 3: Algorithm Design Paradigms (14 Hours)</h5>
                    <p style="margin: 0 0 0 0;">Dynamic Programming (Matrix Chain Multiplication, TSP), Greedy Strategies, Backtracking, and Branch & Bound techniques.</p>
                </div>
                <p style="margin-top: 16px; font-weight: 700; color: #16a34a;">Attested & Stamped by Bhavik Patel (Head of Department, CSE).</p>

            @elseif($docTitle == 'College Bus / Transport Pass')
                <div style="border: 2px solid #2563eb; padding: 24px; border-radius: 12px; max-width: 450px; margin: 20px auto; background: #fff; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
                    <div style="text-align: center; border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 16px;">
                        <h4 style="margin: 0; color: #1e3a8a;">BAPS UNIVERSITY TRANSPORT SERVICE</h4>
                        <span style="background: #f59e0b; color: #111827; padding: 2px 12px; border-radius: 10px; font-size: 0.75rem; font-weight: 700;">ACADEMIC PASS 2025-2026</span>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.9rem;">
                        <div><strong>Student Name:</strong><br>{{ $studentName }}</div>
                        <div><strong>Enrollment:</strong><br>{{ $enrollmentNo }}</div>
                        <div><strong>Allocated Route:</strong><br>Route #04 (Ahmedabad - Campus)</div>
                        <div><strong>Pickup Point:</strong><br>Maninagar Circle (07:15 AM)</div>
                        <div><strong>Bus Number:</strong><br>GJ-01-ED-2026</div>
                        <div><strong>Valid Thru:</strong><br>May 31, 2026</div>
                    </div>
                    <div style="text-align: center; margin-top: 16px; padding-top: 12px; border-top: 1px solid #e5e7eb; font-size: 0.8rem; color: #6b7280;">Authorized by Campus Transport Desk</div>
                </div>

            @elseif($docTitle == 'Hostel Allotment Letter')
                <p>This is to officially certify that <strong>{{ $studentName }}</strong> (Enrollment: {{ $enrollmentNo }}) has been allocated residential dormitory accommodation on the institutional campus for the academic year 2025-2026.</p>
                <table class="doc-table">
                    <tbody>
                        <tr><th>Allocated Hostel Block</th><td>Block A (Pramukh Swami Chhatralaya)</td></tr>
                        <tr><th>Room Number & Floor</th><td>Room #204 (Second Floor, East Wing)</td></tr>
                        <tr><th>Room Type</th><td>Triple Occupancy with Attached Study Desk</td></tr>
                        <tr><th>Mess Allocation</th><td>Central Dining Hall A (Pure Vegetarian)</td></tr>
                        <tr><th>Chief Warden Sign-Off</th><td style="color: #16a34a; font-weight: 700;">VERIFIED & ALLOCATED</td></tr>
                    </tbody>
                </table>
                <p>The student is required to abide by all hostel curfew timings (09:30 PM) and maintain absolute cleanliness and disciplinary harmony in the residential premises.</p>

            @elseif($docTitle == 'Scholarship Sanction Letter / Document')
                <p>We are pleased to officially inform you that <strong>{{ $studentName }}</strong> (Enrollment: {{ $enrollmentNo }}) has been awarded the prestigious <strong>Dean's Academic Excellence Endowment Scholarship</strong> for the current academic session.</p>
                <p>Based on your outstanding SGPA of 9.42 and top-tier standing in the CSE department, the Scholarship Committee has sanctioned a <strong>50% tuition fee waiver</strong> amounting to ₹ 32,500.00 for the current semester.</p>
                <p>This scholarship is renewable for subsequent semesters subject to maintaining a minimum CGPA of 8.50 and an exemplary disciplinary record.</p>

            @elseif($docTitle == 'Extracurricular / Sports Achievement Certificates')
                <div style="border: 4px double #10b981; padding: 32px; text-align: center; border-radius: 12px; margin: 20px 0; background: #f0fdf4;">
                    <i class="fas fa-trophy" style="font-size: 3rem; color: #10b981; margin-bottom: 12px;"></i>
                    <h3 style="font-family: 'Merriweather', serif; color: #111827; margin-bottom: 8px;">CERTIFICATE OF MERIT & EXCELLENCE</h3>
                    <p style="font-size: 1.1rem; margin-bottom: 16px;">This honor is proudly presented to</p>
                    <h2 style="font-size: 2.2rem; color: #10b981; margin: 0 0 8px 0; font-family: 'Merriweather', serif;">{{ $studentName }}</h2>
                    <p style="font-size: 1.1rem; margin-bottom: 24px;">for securing <strong>First Position (Gold Medal)</strong> in the Inter-University National Coding Hackathon & Algorithm Design Competition held during the Annual TechFest 2026.</p>
                    <div style="font-size: 0.85rem; color: #6b7280; text-transform: uppercase;">Awarded by the Institutional Sports & Cultural Committee</div>
                </div>

            @elseif($docTitle == 'National Social Service (NSS) / NCC Certificate')
                <p>This is to certify that <strong>{{ $studentName }}</strong> (Enrollment: {{ $enrollmentNo }}) has successfully completed <strong>120 hours of mandatory community service</strong> under the National Service Scheme (NSS) institutional unit during 2024-2026.</p>
                <p>The student actively participated in rural digital literacy drives, blood donation camps, environmental tree plantation initiatives, and village health awareness campaigns. Their dedication to national service and social upliftment is highly commendable.</p>

            @elseif($docTitle == 'Alumni Association Membership Card')
                <div style="border: 2px solid #9333ea; padding: 24px; border-radius: 16px; max-width: 420px; margin: 20px auto; background: linear-gradient(135deg, #fdf4ff 0%, #fae8ff 100%); box-shadow: 0 4px 15px rgba(147,51,234,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #e879f9; padding-bottom: 12px; margin-bottom: 16px;">
                        <h4 style="margin: 0; color: #7e22ce; font-family: 'Merriweather';">BAPS ALUMNI NETWORK</h4>
                        <i class="fas fa-user-graduate" style="font-size: 1.5rem; color: #9333ea;"></i>
                    </div>
                    <div style="display: grid; grid-template-columns: 1fr; gap: 8px; font-size: 0.95rem; color: #4c1d95;">
                        <div><strong>Alumni Name:</strong> {{ $studentName }}</div>
                        <div><strong>Degree & Branch:</strong> {{ $program }} in {{ $departmentName }}</div>
                        <div><strong>Graduation Batch:</strong> Class of 2026</div>
                        <div><strong>Permanent Alumni ID:</strong> ALUM-2026-CSE-{{ $student->id ?? 101 }}</div>
                        <div><strong>Membership Type:</strong> Lifetime Permanent Member</div>
                    </div>
                    <div style="margin-top: 16px; padding-top: 12px; border-top: 1px solid #f0abfc; font-size: 0.75rem; color: #7e22ce; text-align: center;">BAPS Global University Alumni Network — Connecting Excellence</div>
                </div>

            @elseif($docTitle == 'BAPS Member Ship Transcript')
                <p>Official transcript of Values-Based Education, Ethical Leadership, and Spiritual Heritage credits earned by <strong>{{ $studentName }}</strong> under the BAPS Swaminarayan institutional mandate.</p>
                <table class="doc-table">
                    <thead><tr><th>Module Description</th><th>Hours Completed</th><th>Evaluation Grade</th><th>Credit Awarded</th></tr></thead>
                    <tbody>
                        <tr><td>IPDC-101: Integrated Personality Development</td><td>30 Hours</td><td>A+ (Outstanding)</td><td>2.0 Credits</td></tr>
                        <tr><td>ETH-201: Professional Ethics & Panchvartman Values</td><td>25 Hours</td><td>A+ (Outstanding)</td><td>2.0 Credits</td></tr>
                        <tr><td>SEVA-301: Campus & Community Voluntary Seva</td><td>40 Hours</td><td>A (Excellent)</td><td>2.0 Credits</td></tr>
                        <tr style="background: #f1f5f9; font-weight: 700;"><td>TOTAL VALUES CREDITS EARNED</td><td>95 Hours</td><td>A+ (DISTINCTION)</td><td style="color: #ea580c;">6.0 CREDITS</td></tr>
                    </tbody>
                </table>
                <p>This transcript reflects the institution's commitment to holistic student development, combining academic rigor with moral purity and spiritual harmony.</p>

            @elseif($docTitle == 'IIMA member Card With Role letter')
                <div style="border: 2px solid #111827; padding: 28px; border-radius: 16px; margin: 20px 0; background: #fff; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #b91c1c; padding-bottom: 16px; margin-bottom: 24px;">
                        <div>
                            <h3 style="margin: 0; font-family: 'Merriweather', serif; color: #111827;">IIMA - BAPS JOINT ACADEMIC INITIATIVE</h3>
                            <div style="font-size: 0.85rem; color: #6b7280; font-weight: 600;">Executive Student Leadership & Research Fellowship</div>
                        </div>
                        <div style="background: #b91c1c; color: #fff; padding: 8px 16px; border-radius: 8px; font-weight: 800; font-size: 1.1rem;">IIMA FELLOW</div>
                    </div>
                    <p style="font-size: 1.05rem; line-height: 1.8; margin-bottom: 24px;">This is to certify that <strong>{{ $studentName }}</strong> ({{ $enrollmentNo }}) has been selected as an Executive Student Fellow under the joint academic collaboration between Indian Institute of Management Ahmedabad (IIMA) and BAPS Swaminarayan Vidyamandir.</p>
                    <p style="font-size: 1.05rem; line-height: 1.8; margin-bottom: 24px;"><strong>Role Mandate:</strong> The fellow is authorized to participate in advanced management workshops, access IIMA Vikram Sarabhai Library online resources, and co-author institutional research whitepapers in technology management.</p>
                    <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 0.9rem;">
                        <span><strong>Fellowship ID:</strong> IIMA-BAPS-2026-FLW{{ $student->id ?? 101 }}</span>
                        <span style="color: #b91c1c; font-weight: 700;">VALID TILL: JUNE 2027</span>
                    </div>
                </div>

            @elseif($docTitle == 'For Student')
                <div style="border: 2px solid var(--baps-blue); padding: 32px; border-radius: 12px; background: #fafafb; margin: 20px 0;">
                    <h3 style="font-family: 'Merriweather', serif; color: var(--baps-blue); margin-bottom: 12px; border-bottom: 2px solid var(--baps-blue); padding-bottom: 8px;">Official Student Credentials & Active Membership Certificate</h3>
                    <p style="font-size: 1.05rem; line-height: 1.8;">This is to certify that <strong>{{ $studentName }}</strong> (Enrollment Number: <strong>{{ $enrollmentNo }}</strong>) is registered as an active member in good standing under the <strong>{{ $departmentName }}</strong> department at BAPS Swaminarayan Vidyamandir.</p>
                    <p style="font-size: 1.05rem; line-height: 1.8;">The student has cleared all primary verification steps for the current academic session and is hereby granted all associated student privileges, campus access rights, and library resource permissions.</p>
                    <div style="background: #f1f5f9; padding: 16px; border-radius: 8px; font-weight: 700; margin-top: 20px;">
                        <span>MEMBERSHIP LEVEL: STUDENT MEMBER</span>
                        <span style="float: right; color: #2563eb;">VALIDITY: {{ $validity }}</span>
                    </div>
                </div>

            @elseif($docTitle == 'For Office Work')
                <div style="border: 2px solid var(--baps-saffron); padding: 32px; border-radius: 12px; background: #fffdfa; margin: 20px 0;">
                    <h3 style="font-family: 'Merriweather', serif; color: var(--baps-saffron); margin-bottom: 12px; border-bottom: 2px solid var(--baps-saffron); padding-bottom: 8px;">Official Administrative Office Clearance & Verification Mandate</h3>
                    <p style="font-size: 1.05rem; line-height: 1.8;">This official document acts as a verified clearance sheet and authorization register for administrative purposes. It is certified that the record of <strong>{{ $studentName }}</strong> (Enrollment/ID: <strong>{{ $enrollmentNo }}</strong>) has been processed for institutional office work requirements.</p>
                    <p style="font-size: 1.05rem; line-height: 1.8;">This document authorizes the recipient under the designated role of <strong>{{ $recipientRole }}</strong> to execute the specified tasks and operations as defined by the administrative board.</p>
                    <table class="doc-table">
                        <thead><tr><th>Task Classification</th><th>Authorized Officer</th><th>Handover Type</th><th>Filing Status</th></tr></thead>
                        <tbody>
                            <tr><td>Institutional Administration</td><td>Registrar Office Desk</td><td>{{ $handoverMode ?? 'Direct Filing' }}</td><td><span style="color: #16a34a; font-weight: 700;">PROCESSED</span></td></tr>
                        </tbody>
                    </table>
                </div>

            @elseif($docTitle == 'Membership Card')
                <div style="border: 2px solid var(--baps-blue); padding: 32px; border-radius: 16px; background: linear-gradient(135deg, #eff6ff 0%, #dbeafe 100%); margin: 20px auto; max-width: 500px; box-shadow: 0 8px 24px rgba(37,99,235,0.15); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: -50px; right: -50px; width: 150px; height: 150px; background: rgba(37, 99, 235, 0.05); border-radius: 50%;"></div>
                    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--baps-blue); padding-bottom: 12px; margin-bottom: 20px;">
                        <div>
                            <h4 style="margin: 0; color: var(--baps-blue); font-family: 'Merriweather', serif; font-size: 1.15rem;">BAPS INSTITUTIONAL MEMBERSHIP</h4>
                            <span style="font-size: 0.75rem; text-transform: uppercase; font-weight: 700; color: var(--baps-saffron); letter-spacing: 1px;">Official Verified Credential</span>
                        </div>
                        <i class="fas fa-id-card" style="font-size: 2.2rem; color: var(--baps-blue);"></i>
                    </div>
                    <div style="display: flex; gap: 20px; align-items: center; margin-bottom: 20px;">
                        <img src="{{ $photoUrl ?? 'https://ui-avatars.com/api/?name='.urlencode($studentName).'&background=1e3a8a&color=fff&size=150' }}" alt="Member Photo" style="width: 80px; height: 80px; border-radius: 12px; object-fit: cover; border: 2px solid #fff; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                        <div style="font-size: 0.95rem; line-height: 1.5; color: #1e293b;">
                            <div><strong>Member Name:</strong> {{ $studentName }}</div>
                            <div><strong>Enrollment / ID:</strong> {{ $enrollmentNo }}</div>
                            <div><strong>Role / Class:</strong> {{ $recipientRole }}</div>
                            <div><strong>Department:</strong> {{ $departmentName }}</div>
                        </div>
                    </div>
                    <div style="background: #fff; padding: 12px; border-radius: 8px; border: 1px solid #bfdbfe; font-size: 0.85rem; color: #3b82f6; display: flex; justify-content: space-between; align-items: center;">
                        <span>Validity Period: <strong>{{ $validity }}</strong></span>
                        <span style="font-family: 'Space Mono', monospace; font-size: 0.75rem; font-weight: 700;">{{ strtoupper(substr(md5($docTitle), 0, 8)) }}</span>
                    </div>
                </div>

            @else
                <p>Official certified institutional document: <strong>{{ $docTitle }}</strong> issued to {{ $studentName }} (Enrollment: {{ $enrollmentNo }}). This record bears full administrative verification under the BAPS Swaminarayan Vidyamandir academic registry.</p>
            @endif

        </div>

        <!-- Dynamic Issuance Details Box -->
        @if(isset($purpose) || isset($authority) || isset($securityHash))
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-left: 4px solid #16a34a; border-radius: 12px; padding: 20px; margin-bottom: 30px; margin-top: 30px;">
            <h5 style="margin: 0 0 12px 0; color: #14532d; font-family: 'Merriweather', serif; font-size: 1.05rem; display: flex; align-items: center; gap: 8px;">
                <i class="fas fa-shield-alt text-success"></i> Official Issuance & Handover Metadata
            </h5>
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px 20px; font-size: 0.9rem;">
                <div><strong style="color: #166534;">Recipient Role:</strong> {{ $recipientRole }}</div>
                <div><strong style="color: #166534;">Issuing Authority:</strong> {{ $authority }}</div>
                <div><strong style="color: #166534;">Attesting Dean:</strong> {{ $deanName ?? 'Dr. Sadhu Gyaneswar Das' }}</div>
                <div><strong style="color: #166534;">Attesting Provost:</strong> {{ $provostName ?? 'Prof. Harish Patel' }}</div>
                @if(isset($handoverMode) && $handoverMode)
                <div><strong style="color: #166534;">Handover Mode:</strong> {{ $handoverMode }}</div>
                @endif
                <div><strong style="color: #166534;">Purpose of Issue:</strong> {{ $purpose ?? 'N/A' }}</div>
                <div><strong style="color: #166534;">Validity Period:</strong> {{ $validity }}</div>
                <div style="grid-column: span 2;"><strong style="color: #166534;">Cryptographic Hash:</strong> <code style="background: #dcfce7; padding: 2px 6px; border-radius: 4px; font-family: 'Space Mono', monospace; font-size: 0.85rem; color: #15803d;">{{ $securityHash ?? 'N/A' }}</code></div>
            </div>
        </div>
        @endif

        <!-- Official Sign & Seal Footer -->
        <div class="doc-footer" style="flex-direction: column; align-items: stretch; gap: 20px; border-top: 2px solid var(--baps-border); padding-top: 24px; margin-top: 48px;">
            
            <!-- Top row: QR Code and Official Seal -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                <div class="qr-box">
                    <div class="qr-placeholder">
                        <i class="fas fa-qrcode" style="font-size: 1.8rem; margin-bottom: 2px;"></i>
                        <span>VERIFY HASH</span>
                    </div>
                    <div class="qr-text">Scan cryptographic QR code to verify document authenticity on the BAPS institutional ledger.</div>
                </div>
                <div class="seal-box">
                    <div class="seal-circle" style="margin: 0;">BAPS SVM<br>OFFICIAL SEAL<br>GUJARAT</div>
                </div>
            </div>

            <!-- Bottom row: 4 Digital Signatures Grid -->
            <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; text-align: center; align-items: flex-end; margin-top: 15px; background: #fafafa; padding: 15px; border-radius: 12px; border: 1px solid var(--baps-border);">
                <!-- HOD -->
                <div class="sign-box">
                    <div style="height: 45px; display: flex; align-items: center; justify-content: center;">
                        {!! $hodSignature !!}
                    </div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #111827; margin-top: 4px; border-top: 1px dashed #cbd5e1; padding-top: 4px;">{{ $hodName }}</div>
                    <div style="font-size: 0.7rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">HOD (CSE Dept)</div>
                </div>

                <!-- PROVOST -->
                <div class="sign-box">
                    <div style="height: 45px; display: flex; align-items: center; justify-content: center;">
                        {!! $provostSignature !!}
                    </div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #111827; margin-top: 4px; border-top: 1px dashed #cbd5e1; padding-top: 4px;">{{ $provostName }}</div>
                    <div style="font-size: 0.7rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Provost</div>
                </div>

                <!-- DEAN -->
                <div class="sign-box">
                    <div style="height: 45px; display: flex; align-items: center; justify-content: center;">
                        {!! $deanSignature !!}
                    </div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #111827; margin-top: 4px; border-top: 1px dashed #cbd5e1; padding-top: 4px;">{{ $deanName }}</div>
                    <div style="font-size: 0.7rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Dean</div>
                </div>

                <!-- ADMIN -->
                <div class="sign-box">
                    <div style="height: 45px; display: flex; align-items: center; justify-content: center;">
                        {!! $adminSignature !!}
                    </div>
                    <div style="font-size: 0.85rem; font-weight: 700; color: #111827; margin-top: 4px; border-top: 1px dashed #cbd5e1; padding-top: 4px;">{{ $adminName }}</div>
                    <div style="font-size: 0.7rem; color: #6b7280; font-weight: 600; text-transform: uppercase;">Admin</div>
                </div>
            </div>

        </div>

    </div>

</body>
</html>
