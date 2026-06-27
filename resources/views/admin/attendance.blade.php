@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Mark Attendance</h3>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

<div class="row mb-4">
    <div class="col-md-12">
        <form method="GET" action="/admin/attendance" class="d-flex gap-2">
            <select name="course_id" class="form-select" required>
                <option value="">-- Select Course --</option>
                @foreach($courses as $c)
                    <option value="{{ $c->id }}" {{ request('course_id') == $c->id ? 'selected' : '' }}>{{ $c->title }}</option>
                @endforeach
            </select>
            <input type="date" name="date" class="form-control" value="{{ $date }}" required>
            <button class="btn btn-primary">Load Students</button>
        </form>
    </div>
</div>

@if($selectedCourse && count($students) > 0)
<div class="card p-3 shadow-sm border-0">
    <h5 class="mb-3">Attendance for {{ $selectedCourse->title }} ({{ \Carbon\Carbon::parse($date)->format('d M Y') }})</h5>
    <form method="POST" action="/admin/attendance">
        @csrf
        <input type="hidden" name="course_id" value="{{ $selectedCourse->id }}">
        <input type="hidden" name="date" value="{{ $date }}">

        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Enrollment No</th>
                    <th>Student Name</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($students as $student)
                <tr>
                    <td>{{ $student->enrollment_no }}</td>
                    <td>{{ $student->name }}</td>
                    <td>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="attendance[{{ $student->id }}]" value="present" id="p{{ $student->id }}" checked>
                            <label class="form-check-label text-success fw-bold" for="p{{ $student->id }}">Present</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="attendance[{{ $student->id }}]" value="absent" id="a{{ $student->id }}">
                            <label class="form-check-label text-danger fw-bold" for="a{{ $student->id }}">Absent</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="attendance[{{ $student->id }}]" value="late" id="l{{ $student->id }}">
                            <label class="form-check-label text-warning fw-bold" for="l{{ $student->id }}">Late</label>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <button type="submit" class="btn btn-success">Save Attendance</button>
    </form>
</div>
@elseif($selectedCourse)
<div class="alert alert-warning">No students enrolled in this course.</div>
@endif

@endsection
