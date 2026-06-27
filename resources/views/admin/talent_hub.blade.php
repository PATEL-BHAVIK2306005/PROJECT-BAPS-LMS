@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-1"><i class="fas fa-briefcase text-primary me-2"></i> BAPS Talent Hub</h3>
        <p class="text-muted small mb-0">Institutional Employability & Placements Dashboard</p>
    </div>
    <a href="/admin" class="btn btn-outline-dark btn-sm rounded-pill px-3 shadow-sm"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
</div>

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 bg-primary bg-opacity-10 shadow-sm text-primary p-3 rounded-4">
            <h6 class="fw-bold"><i class="fas fa-gem me-2"></i> Platinum Ready</h6>
            <h3 class="fw-bold mb-0">{{ $students->where('industryBadge', 'Platinum')->count() }} Students</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-warning bg-opacity-10 shadow-sm text-warning p-3 rounded-4">
            <h6 class="fw-bold text-dark"><i class="fas fa-medal me-2"></i> Gold Talent</h6>
            <h3 class="fw-bold mb-0 text-dark">{{ $students->where('industryBadge', 'Gold')->count() }} Students</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-secondary bg-opacity-10 shadow-sm text-secondary p-3 rounded-4">
            <h6 class="fw-bold"><i class="fas fa-award me-2"></i> Silver Pool</h6>
            <h3 class="fw-bold mb-0">{{ $students->where('industryBadge', 'Silver')->count() }} Students</h3>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 bg-success bg-opacity-10 shadow-sm text-success p-3 rounded-4 text-center d-flex justify-content-center align-items-center cursor-pointer" onclick="alert('Recruiter Pack Export Initiated!')">
            <h6 class="fw-bold mb-0"><i class="fas fa-download mb-1 d-block fs-4"></i> Export Recruiter Pack</h6>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase fw-bold">
                    <tr>
                        <th class="ps-4 py-3">Rank</th>
                        <th class="py-3">Talent Profile</th>
                        <th class="py-3">Achievements</th>
                        <th class="py-3">Employability Score</th>
                        <th class="text-end pe-4 py-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($students as $index => $student)
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#{{ $index + 1 }}</td>
                        <td>
                            <div class="fw-bold text-dark fs-6">{{ $student->name }}</div>
                            <div class="small text-muted font-monospace">{{ $student->enrollment_no }} | {{ $student->department ?? 'CS' }}</div>
                        </td>
                        <td>
                            <span class="badge bg-success bg-opacity-10 text-success border border-success me-1"><i class="fas fa-certificate me-1"></i> {{ $student->certsCount }} Certs</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary"><i class="fas fa-book me-1"></i> {{ $student->enrollsCount }} Courses</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <h5 class="fw-bold text-dark mb-0 me-2">{{ $student->employabilityScore }}</h5>
                                @if($student->industryBadge == 'Platinum') <span class="badge bg-primary">Platinum</span>
                                @elseif($student->industryBadge == 'Gold') <span class="badge bg-warning text-dark">Gold</span>
                                @elseif($student->industryBadge == 'Silver') <span class="badge bg-secondary">Silver</span>
                                @else <span class="badge bg-dark bg-opacity-25 text-dark">Bronze</span> @endif
                            </div>
                        </td>
                        <td class="text-end pe-4">
                            <a href="/portfolio/{{ $student->enrollment_no }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill fw-bold shadow-sm">
                                <i class="fas fa-external-link-alt me-1"></i> View Portfolio
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
