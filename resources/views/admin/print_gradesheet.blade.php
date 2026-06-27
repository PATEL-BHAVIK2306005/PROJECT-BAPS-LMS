<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Statement of Grade - {{ $user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Roboto', sans-serif; background: #fff; color: #000; padding: 20px; }
        .page-container {
            max-width: 1000px;
            margin: 0 auto;
            background: #fff;
            padding: 40px;
            position: relative;
        }
        .header-section {
            display: flex;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .logo-box {
            width: 150px;
            height: 80px;
            background: #dc3545; /* Placeholder for logo red color */
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 900;
            font-size: 24px;
            margin-right: 30px;
        }
        .header-text {
            flex: 1;
            text-align: center;
        }
        .univ-name {
            font-size: 26px;
            font-weight: 900;
            margin-bottom: 5px;
            letter-spacing: 1px;
        }
        .univ-address {
            font-size: 14px;
            margin-bottom: 10px;
        }
        .doc-title {
            font-size: 28px;
            font-weight: 900;
            letter-spacing: 2px;
        }
        .sheet-no {
            font-size: 14px;
            margin-top: 10px;
        }

        .student-info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .school-name {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 15px;
            color: #000;
        }
        .info-table {
            width: 100%;
        }
        .info-table td {
            padding: 5px 0;
            font-size: 15px;
        }
        .info-table td:first-child {
            width: 180px;
        }
        .photo-box {
            width: 120px;
            height: 140px;
            border: 1px solid #000;
            margin-left: 20px;
            background: url('https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&size=150&background=0D8ABC&color=fff') center/cover no-repeat;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            border: 2px solid #000;
        }
        .results-table th, .results-table td {
            border: 1px solid #000;
            padding: 8px 10px;
            text-align: center;
            font-size: 14px;
        }
        .results-table th {
            font-weight: 700;
        }
        .results-table td.text-left {
            text-align: left;
        }

        .summary-bar {
            display: flex;
            justify-content: space-between;
            margin-bottom: 60px;
        }
        .summary-box {
            border: 2px solid #000;
            padding: 5px 15px;
            font-size: 14px;
            font-weight: 700;
            display: flex;
            align-items: center;
        }
        .summary-box span.val {
            margin-left: 10px;
            padding-left: 10px;
            border-left: 2px solid #000;
        }

        .signatures-section {
            display: flex;
            justify-content: space-between;
            margin-top: 80px;
            margin-bottom: 40px;
        }
        .sig-block {
            text-align: center;
            width: 25%;
        }
        .sig-line {
            border-bottom: 1px solid #000;
            margin-bottom: 5px;
            height: 40px;
        }
        .sig-title {
            font-size: 14px;
            font-weight: 700;
        }

        .footer-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: 50px;
        }
        .barcode-box {
            height: 60px;
            width: 200px;
            background: repeating-linear-gradient(
                90deg,
                #000,
                #000 2px,
                #fff 2px,
                #fff 4px,
                #000 4px,
                #000 8px,
                #fff 8px,
                #fff 10px
            );
            margin-bottom: 10px;
        }
        .footer-note {
            font-size: 12px;
        }

        .excellence-badge {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 100px;
            height: 100px;
            background: radial-gradient(circle, #ffd700, #daa520);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #000;
            font-weight: 900;
            text-align: center;
            box-shadow: 0 0 15px rgba(218, 165, 32, 0.6);
            border: 4px dashed #fff;
            outline: 2px solid #daa520;
            transform: rotate(15deg);
        }

        @media print {
            body { background: #fff; padding: 0; }
            .btn-print { display: none !important; }
            .page-container { padding: 0; box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

<div class="text-center mb-4 btn-print d-flex justify-content-center gap-2">
    @php
        $isStudent = (auth()->check() && auth()->user()->role === 'student') || session('demo_user_id') || session('student_id');
        $backUrl = $isStudent ? '/profile' : '/admin/students';
    @endphp
    <a href="{{ $backUrl }}" class="btn btn-dark fw-bold px-4 rounded shadow text-white d-inline-flex align-items-center gap-2">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>
    <button class="btn btn-primary fw-bold px-4 rounded shadow" onclick="window.print()">
        <i class="fas fa-print me-2"></i> Print Grade Sheet
    </button>
    @if(in_array($sgpa_grade, ['O', 'O+']))
        @php
            $isStudent = (auth()->check() && auth()->user()->role === 'student') || session('demo_user_id') || session('student_id');
            $excellenceUrl = $isStudent ? '/exam/excellence-cert' : "/admin/exam/results/student/{$user->id}/excellence-cert";
        @endphp
        <a href="{{ $excellenceUrl }}" class="btn btn-warning fw-bold px-4 rounded shadow text-dark">
            <i class="fas fa-certificate me-2"></i> Download Excellence Certificate
        </a>
    @endif
</div>

<div class="page-container">
    @if(in_array($sgpa_grade, ['O', 'O+']))
        <div class="excellence-badge">
            <small>BADGE OF<br>EXCELLENCE</small>
        </div>
    @endif
    
    <!-- HEADER -->
    <div class="header-section">
        <div class="logo-box">
            BAPS<br>CAMPUS
        </div>
        <div class="header-text">
            <div class="univ-name">BAPS INNOVATION CAMPUS, VADODARA</div>
            <div class="univ-address">Knowledge Highway, Vadodara-391510 Gujarat, India</div>
            <div class="doc-title">STATEMENT OF GRADE</div>
        </div>
    </div>
    <div class="sheet-no">Grade Sheet No. GS{{ date('Y') }}{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}</div>

    <!-- STUDENT INFO -->
    <div class="student-info-section mt-4">
        <div style="flex: 1;">
            <div class="school-name">School of Computer Science, Engineering and Technology</div>
            <table class="info-table">
                <tr>
                    <td>Name</td>
                    <td>: <strong>{{ strtoupper($user->name) }}</strong></td>
                </tr>
                <tr>
                    <td>Enrollment Number</td>
                    <td>: <strong>{{ $user->enrollment_no }}</strong></td>
                </tr>
                <tr>
                    <td>Branch</td>
                    <td>: <strong>B. Tech in Computer Science & Engineering</strong></td>
                </tr>
            </table>
            
            <div class="mt-3 mb-3 fw-bold">
                Grade Sheet for {{ $examTitle }} of Bachelor of Technology
            </div>
            
            <table class="info-table">
                <tr>
                    <td>Examination</td>
                    <td>: <strong>{{ strtoupper($examTitle) }}</strong></td>
                    <td style="width: 100px;">Exam Seat No</td>
                    <td>: <strong>{{ $user->enrollment_no }}{{ mt_rand(10,99) }}</strong></td>
                </tr>
            </table>
        </div>
        <div class="photo-box">
            <!-- Photo Background via CSS -->
        </div>
    </div>

    <!-- RESULTS TABLE -->
    <table class="results-table">
        <thead>
            <tr>
                <th rowspan="2">Course<br>Code</th>
                <th rowspan="2" style="width: 40%;">Course Name</th>
                <th rowspan="2">Credits</th>
                <th colspan="3">Marks Obtained</th>
                <th rowspan="2">Overall<br>Grade</th>
                <th rowspan="2">Remark</th>
            </tr>
            <tr>
                <th>Int.</th>
                <th>Prac.</th>
                <th>Ext.</th>
            </tr>
        </thead>
        <tbody>
            @foreach($results as $r)
            <tr>
                <td>{{ 'C' . date('y') . '10C' . $loop->iteration }}</td> <!-- Mock Course Code if none exists -->
                <td class="text-left">{{ $r->course->title ?? 'Unknown Subject' }}</td>
                <td>{{ number_format($r->course->credits ?? 4, 2) }}</td>
                
                @if(($r->course->type ?? 'theory') == 'pbl')
                    <td></td>
                    <td></td>
                    <td>{{ number_format($r->total_obtained, 2) }}</td>
                @else
                    <td>{{ number_format($r->internal_marks, 2) }}</td>
                    <td>{{ number_format($r->practical_marks, 2) }}</td>
                    <td>{{ number_format($r->external_marks_final, 2) }}</td>
                @endif
                
                <td><strong>{{ $r->grade }}</strong></td>
                <td>{{ $r->grade == 'F' ? 'FAIL' : 'PASS' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SUMMARY BAR -->
    <div class="summary-bar">
        <div class="summary-box">SGPA <span class="val">{{ number_format($sgpa, 2) }} ({{ $sgpa_grade }})</span></div>
        <div class="summary-box">Current Backlog <span class="val">{{ $currentBacklog }}</span></div>
        <div class="summary-box" style="color: {{ $status == 'FAIL' ? 'red' : 'green' }}">Status <span class="val">{{ $status }}</span></div>
        <div class="summary-box">CGPA <span class="val">{{ number_format($cgpa, 2) }}</span></div>
        <div class="summary-box">Total Backlog <span class="val">{{ $totalBacklog }}</span></div>
    </div>

    @if(!empty($customRemark))
    <div class="mt-2 mb-4 p-3 border border-danger text-danger fw-bold" style="background: #fff5f5;">
        {{ $customRemark }}
    </div>
    @endif

    <!-- SIGNATURES -->
    <div class="signatures-section">
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-title">Exam Controller</div>
        </div>
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-title">Admin / Dean</div>
        </div>
        <div class="sig-block">
            <div class="sig-line"></div>
            <div class="sig-title">Registrar</div>
        </div>
    </div>

    <!-- FOOTER -->
    <div class="footer-section">
        <div>
            <div class="barcode-box"></div>
            <div class="footer-note fw-bold">Date: <span style="margin-left: 20px;">{{ date('d-m-Y') }}</span></div>
        </div>
        <div class="footer-note">
            Note : This is a computer generated mark-sheet.
        </div>
    </div>

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</body>
</html>
