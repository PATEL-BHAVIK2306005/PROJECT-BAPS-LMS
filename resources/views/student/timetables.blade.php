@extends('layouts.app')
@section('content')

<div class="container py-4">
    <h3 class="mb-4 fw-bold">Class Timetables</h3>

    <div class="row">
        @forelse($timetables as $timetable)
            <div class="col-md-4 mb-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <h5 class="card-title text-primary fw-bold">{{ $timetable->title }}</h5>
                        <p class="card-text text-muted mb-1">
                            <i class="fas fa-building me-1"></i> {{ $timetable->department->name ?? 'All Departments' }}
                        </p>
                        <p class="card-text text-muted mb-3">
                            <i class="fas fa-layer-group me-1"></i> Semester: {{ $timetable->semester ?? 'N/A' }}
                        </p>
                        @if($timetable->file_path)
                            <a href="{{ asset('storage/' . $timetable->file_path) }}" target="_blank" class="btn btn-outline-primary shadow-sm rounded-pill w-100">
                                <i class="fas fa-file-pdf me-1"></i> View PDF Timetable
                            </a>
                        @else
                            <a href="/timetables/{{ $timetable->id }}" class="btn btn-outline-success shadow-sm rounded-pill w-100">
                                <i class="fas fa-table me-1"></i> View Interactive Grid
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted">
                <div class="p-5 bg-white shadow-sm rounded">
                    <i class="fas fa-calendar-times fa-3x mb-3"></i>
                    <h5>No timetables available yet.</h5>
                </div>
            </div>
        @endforelse
    </div>
</div>

@endsection
