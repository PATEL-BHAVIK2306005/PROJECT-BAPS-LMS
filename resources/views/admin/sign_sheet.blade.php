@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
        <h3 class="mb-0">Class-wise Sign Sheet</h3>
    </div>
    <div class="btn-group">
        <a href="/admin/exam/schedule" class="btn btn-outline-primary btn-sm">Schedule</a>
        <a href="/admin/exam/seating" class="btn btn-outline-primary btn-sm">Seating</a>
        <a href="/admin/exam/sign-sheet" class="btn btn-primary btn-sm active">Sign Sheet</a>
        <a href="/admin/exam/results-grading" class="btn btn-outline-primary btn-sm">Results</a>
    </div>
</div>

<div class="card border-0 shadow-sm overflow-hidden">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Sign Sheet Generator</h5>
        <button class="btn btn-dark btn-sm rounded-pill px-3" onclick="window.print()">
            <i class="fas fa-print me-1"></i> Print All Sheets
        </button>
    </div>
    <table class="table table-bordered mb-0 bg-white small">
        <thead class="table-light">
            <tr class="text-center">
                <th>Sr No</th>
                <th>Enrollment No</th>
                <th>Student Name</th>
                <th>Subject</th>
                <th>Answer Sheet No</th>
                <th>Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $index => $e)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="fw-bold">{{ $e->user->enrollment_no ?? 'N/A' }}</td>
                <td>{{ $e->user->name ?? $e->name }}</td>
                <td>{{ $e->course->title }}</td>
                <td style="width: 150px;"></td>
                <td style="width: 150px;"></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<style>
@media print {
    .sidebar, .top-nav, .btn-group, .btn-dark, .btn-baps-back, footer {
        display: none !important;
    }
    .main-content {
        margin-left: 0 !important;
        padding: 0 !important;
    }
    .card {
        box-shadow: none !important;
        border: none !important;
    }
    table {
        font-size: 10pt !important;
    }
}
</style>

@endsection
