<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Grade Sheet - {{ $result->user->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #fff; padding: 40px; }
        .cert-container {
            border: 10px solid #f8f9fa;
            padding: 50px;
            position: relative;
            max-width: 900px;
            margin: auto;
            box-shadow: 0 0 20px rgba(0,0,0,0.05);
        }
        .header-logo { width: 100px; margin-bottom: 20px; }
        .univ-name { font-weight: 800; color: #1e293b; font-size: 1.8rem; text-transform: uppercase; letter-spacing: 1px; }
        .doc-title { background: #1e293b; color: white; padding: 5px 20px; border-radius: 4px; display: inline-block; font-weight: 600; margin-top: 10px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 40px; }
        .info-item { border-bottom: 1px dashed #cbd5e1; padding-bottom: 5px; }
        .info-label { font-size: 0.8rem; color: #64748b; text-transform: uppercase; font-weight: 600; }
        .info-value { font-weight: 700; color: #1e293b; font-size: 1.05rem; }
        .result-table { margin-top: 40px; width: 100%; border-collapse: collapse; }
        .result-table th { background: #f1f5f9; padding: 12px; text-align: left; border: 1px solid #e2e8f0; font-size: 0.9rem; }
        .result-table td { padding: 15px; border: 1px solid #e2e8f0; font-weight: 600; }
        .grade-badge { font-size: 1.5rem; font-weight: 800; color: #1e293b; }
        .footer-sigs { display: flex; justify-content: space-between; margin-top: 80px; text-align: center; }
        .sig-box { width: 200px; }
        .sig-line { border-top: 2px solid #1e293b; margin-top: 40px; padding-top: 5px; font-weight: 700; font-size: 0.9rem; }
        .official-stamp {
            position: absolute;
            bottom: 60px;
            right: 250px;
            width: 120px;
            height: 120px;
            border: 4px solid rgba(220, 38, 38, 0.4);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: rgba(220, 38, 38, 0.4);
            font-weight: 800;
            text-transform: uppercase;
            transform: rotate(-15deg);
            pointer-events: none;
            font-size: 0.8rem;
            text-align: center;
        }

        @media print {
            body { padding: 0; }
            .btn-print { display: none; }
            .cert-container { border: none; box-shadow: none; width: 100%; max-width: 100%; }
        }
    </style>
</head>
<body>

<div class="text-center mb-4 btn-print">
    <button class="btn btn-dark fw-bold px-4 rounded-pill shadow" onclick="window.print()">
        <i class="fas fa-print me-2"></i> Click to Print Official Grade Sheet
    </button>
</div>

<div class="cert-container">
    <div class="text-center">
        <div class="univ-name">BAPS Innovation Campus</div>
        <div class="text-muted small">School of Computer Science Engineering and Technology</div>
        <div class="doc-title">OFFICIAL GRADE SHEET</div>
    </div>

    <div class="info-grid">
        <div class="info-item">
            <div class="info-label">Student Name</div>
            <div class="info-value">{{ strtoupper($result->user->name) }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Enrollment No</div>
            <div class="info-value">{{ $result->user->enrollment_no }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Examination</div>
            <div class="info-value">{{ strtoupper($result->exam_title) }}</div>
        </div>
        <div class="info-item">
            <div class="info-label">Session</div>
            <div class="info-value">January - June 2026</div>
        </div>
    </div>

    <table class="result-table">
        <thead>
            <tr>
                <th>Subject / Module Title</th>
                <th class="text-center">Internal (60)</th>
                <th class="text-center">External (40)</th>
                <th class="text-center">Total Obtained</th>
                <th class="text-center">Grade</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $result->subject->name ?? $result->course->title }}</td>
                <td class="text-center">{{ number_format($result->internal_marks, 2) }}</td>
                <td class="text-center">{{ number_format($result->external_marks_final, 2) }}</td>
                <td class="text-center fw-bold">{{ number_format($result->total_obtained, 2) }}</td>
                <td class="text-center grade-badge">{{ $result->grade }}</td>
            </tr>
        </tbody>
    </table>

    <div class="mt-4 p-3 bg-light border rounded">
        <div class="info-label mb-1">Remarks</div>
        <div class="fst-italic text-muted">"{{ $result->remarks ?: 'Candidate has successfully completed the examination module.' }}"</div>
    </div>

    <div class="official-stamp">
        ACADEMIC<br>OFFICE<br>VERIFIED
    </div>

    <div class="footer-sigs">
        <div class="sig-box">
            <div class="sig-line">Controller of Exams</div>
        </div>
        <div class="sig-box">
            <div class="sig-line">Dean, SCSET</div>
        </div>
        <div class="sig-box">
            <div class="sig-line">Registrar</div>
        </div>
    </div>

    <div class="mt-5 text-center text-muted small" style="border-top: 1px solid #e2e8f0; padding-top: 10px;">
        This is an electronically generated document. Authentic verification can be done via student portal.
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/js/all.min.js"></script>
</body>
</html>
