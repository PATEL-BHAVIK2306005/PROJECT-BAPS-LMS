@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="fas fa-chart-line me-2 text-info"></i> Academic Progress Report</h3>
    <a href="/admin/students" class="btn btn-outline-dark btn-sm rounded-pill px-3 shadow-sm"><i class="fas fa-arrow-left me-1"></i> Back to Roster</a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <div class="glass-card p-4 text-center">
            <img src="{{ ($student->profile_photo_data || $student->profile_photo) ? url('/profile/photo/user/' . $student->id) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=6366f1&color=fff&size=100' }}" 
                 class="rounded-circle shadow-sm border mb-3" style="width: 100px; height: 100px; object-fit: cover;">
            <h4 class="fw-bold mb-1">{{ $student->name }}</h4>
            <div class="badge bg-light text-dark border mb-3">{{ $student->enrollment_no }}</div>
            
            <div class="row g-2 mt-2">
                <div class="col-6">
                    <div class="p-2 border rounded bg-light">
                        <div class="small text-muted">Level</div>
                        <div class="fw-bold fs-5">{{ $student->level }}</div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="p-2 border rounded bg-light">
                        <div class="small text-muted">XP</div>
                        <div class="fw-bold fs-5">{{ $student->xp }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <div class="glass-card p-4">
            <h5 class="fw-bold mb-4 border-bottom pb-2">Course Performance Summary</h5>
            @if($student->results->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Subject</th>
                                <th class="text-center">Internal</th>
                                <th class="text-center">External</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($student->results as $r)
                            <tr>
                                <td class="fw-bold">{{ $r->course->title }}</td>
                                <td class="text-center">{{ number_format($r->internal_marks, 2) }}</td>
                                <td class="text-center">{{ number_format($r->external_marks_final, 2) }}</td>
                                <td class="text-center fw-bold text-primary">{{ number_format($r->total_obtained, 2) }}</td>
                                <td class="text-center">
                                    <span class="badge {{ $r->grade == 'F' ? 'bg-danger' : 'bg-success' }}">{{ $r->grade }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5">
                    <i class="fas fa-ghost fs-1 text-muted opacity-25 mb-3"></i>
                    <p class="text-muted">No examination results indexed for this student yet.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@endsection
