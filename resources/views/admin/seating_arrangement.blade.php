@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
        <h3 class="mb-0">Exam Seating Arrangement</h3>
    </div>
    <div class="btn-group">
        <a href="/admin/exam/schedule" class="btn btn-outline-primary btn-sm">Schedule</a>
        <a href="/admin/exam/seating" class="btn btn-primary btn-sm active">Seating</a>
        <a href="/admin/exam/sign-sheet" class="btn btn-outline-primary btn-sm">Sign Sheet</a>
        <a href="/admin/exam/results-grading" class="btn btn-outline-primary btn-sm">Results</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm border-0">
            <h5>New Arrangement</h5>
            <form action="/admin/exam/seating" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="small fw-bold">Select Schedule</label>
                    <select name="exam_schedule_id" class="form-select" required>
                        @foreach($schedules as $s)
                            <option value="{{ $s->id }}">{{ $s->title }} ({{ $s->date }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="small fw-bold">Room No</label>
                    <input name="room_no" class="form-control" placeholder="E.g. LH-101" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Capacity</label>
                    <input name="capacity" type="number" class="form-control" value="40" required>
                </div>
                <button class="btn btn-primary w-100">Save Arrangement</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <table class="table table-hover mb-0 bg-white small">
                <thead class="table-light">
                    <tr>
                        <th>Schedule</th>
                        <th>Room</th>
                        <th>Capacity</th>
                        <th>Allocated</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($arrangements as $a)
                    <tr>
                        <td>{{ $a->examSchedule->title }}</td>
                        <td class="fw-bold">{{ $a->room_no }}</td>
                        <td>{{ $a->capacity }}</td>
                        <td><span class="text-success">Auto-Mapped</span></td>
                        <td>
                            <a href="/admin/exam/seating/{{$a->id}}/print" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-print me-1"></i> Print
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
