<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Official Seating Arrangement - {{ $arrangement->examSchedule->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print { display: none; }
            body { padding: 20px; background: white !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .official-header { border-bottom: 3px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .institution-logo { font-size: 24px; font-weight: 800; color: #0d6efd; text-transform: uppercase; letter-spacing: 1px; }
        .seating-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin-top: 30px; }
        .seat-card { border: 1px solid #dee2e6; padding: 15px; text-align: center; border-radius: 8px; background: #fff; }
        .seat-no { font-size: 10px; color: #6c757d; display: block; margin-bottom: 5px; }
        .student-name { font-weight: 700; font-size: 13px; display: block; }
        .student-enrollment { font-size: 11px; color: #495057; }
        .xx-small { font-size: 0.65rem; font-weight: bold; text-transform: uppercase; }
        .stamp-block { margin-top: 50px; text-align: right; }
        .official-stamp { border: 2px solid #0d6efd; color: #0d6efd; padding: 10px 20px; display: inline-block; font-weight: 800; border-radius: 50%; opacity: 0.6; transform: rotate(-15deg); }
    </style>
</head>
<body>

<div class="container my-5">
    <div class="no-print text-center mb-4">
        <button onclick="window.print()" class="btn btn-primary btn-lg shadow">
            <i class="fas fa-print me-2"></i> Print Official Seating Chart
        </button>
    </div>

    <div class="card p-5 shadow-sm bg-white">
        <!-- Official Institutional Header -->
        <div class="official-header d-flex justify-content-between align-items-center">
            <div>
                <div class="institution-logo">ITBU UNIVERSITY</div>
                <div class="text-muted small">Examination Section - Seating Management</div>
            </div>
            <div class="text-end">
                <div class="fw-bold text-uppercase">Seating Arrangement</div>
                <div class="small text-muted">Academic Session 2026-27</div>
            </div>
        </div>

        <!-- Arrangement Details -->
        <div class="row mb-4">
            <div class="col-6">
                <div class="mb-2"><strong>Examination:</strong> {{ $arrangement->examSchedule->title }}</div>
                <div class="mb-2"><strong>Date:</strong> {{ \Carbon\Carbon::parse($arrangement->examSchedule->date)->format('d M, Y') }}</div>
                <div class="mb-2"><strong>Time:</strong> {{ $arrangement->examSchedule->time }}</div>
            </div>
            <div class="col-6 text-end">
                <div class="mb-2"><strong>Room Number:</strong> <span class="badge bg-dark fs-6">{{ $arrangement->room_no }}</span></div>
                <div class="mb-2"><strong>Capacity:</strong> {{ $arrangement->capacity }} Students</div>
                <div class="mb-2"><strong>Block Status:</strong> Verified</div>
            </div>
        </div>

        <div class="alert alert-secondary py-2 small">
            <strong>Note to Students:</strong> Please find your allotted seat according to the enrollment number. Maintain silence.
        </div>

        <!-- Seating Grid -->
        <div class="seating-grid">
            @foreach($students as $index => $student)
            <div class="seat-card">
                <span class="seat-no">SEAT #{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="student-name">{{ $student->name }}</span>
                <span class="student-enrollment">{{ $student->enrollment_no }}</span>
                <div class="mt-2 pt-1 border-top" style="border-style: dotted !important; border-top-width: 1px !important;">
                    <span class="xx-small text-muted">Student Signature</span>
                </div>
            </div>
            @endforeach
            
            @for($i = count($students) + 1; $i <= $arrangement->capacity; $i++)
            <div class="seat-card border-dashed" style="border-style: dashed; background: #fafafa;">
                <span class="seat-no">SEAT #{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}</span>
                <span class="text-muted small">VACANT</span>
            </div>
            @endfor
        </div>

        <!-- Official Signatures -->
        <div class="row mt-5 pt-5">
            <div class="col-4 text-center">
                <div class="border-top pt-2">Room Supervisor</div>
            </div>
            <div class="col-4 text-center">
                <div class="border-top pt-2">Exam Coordinator</div>
            </div>
            <div class="col-4 text-center">
                <div class="border-top pt-2">Controller of Exams</div>
            </div>
        </div>

        <div class="stamp-block">
            <div class="official-stamp">VERIFIED</div>
        </div>
    </div>
</div>

<script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</body>
</html>
