@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div class="d-flex align-items-center gap-3">
        <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
        <h3 class="mb-0">Exam Center Management</h3>
    </div>
    <div class="btn-group">
        <a href="/admin/exam/schedule" class="btn btn-primary btn-sm active">Schedule</a>
        <a href="/admin/exam/seating" class="btn btn-outline-primary btn-sm">Seating</a>
        <a href="/admin/exam/sign-sheet" class="btn btn-outline-primary btn-sm">Sign Sheet</a>
        <a href="/admin/exam/results-grading" class="btn btn-outline-primary btn-sm">Results</a>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm border-0">
            <h5>Publish Exam Schedule</h5>
            <form action="/admin/exam/schedule" method="POST">
                @csrf
                <div class="mb-2">
                    <label class="small fw-bold">Department</label>
                    <select name="department_id" class="form-select" required>
                        @foreach($departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-2">
                    <label class="small fw-bold">Exam Title</label>
                    <input name="title" class="form-control" placeholder="Mid Sem / End Sem" required>
                </div>
                <div class="mb-2">
                    <label class="small fw-bold">Date</label>
                    <input name="date" type="date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold">Time Slot</label>
                    <input name="time_slot" class="form-control" placeholder="10:00 AM - 01:00 PM" required>
                </div>
                <button class="btn btn-success w-100">Publish Schedule</button>
            </form>
        </div>
    </div>
    <div class="col-md-8">
        <div class="card border-0 shadow-sm overflow-hidden">
            <table class="table table-hover mb-0 bg-white small">
                <thead class="table-light">
                    <tr>
                        <th>Dept</th>
                        <th>Exam</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($schedules as $s)
                    <tr>
                        <td>{{ $s->department->code ?? 'GEN' }}</td>
                        <td class="fw-bold">{{ $s->title }}</td>
                        <td>{{ \Carbon\Carbon::parse($s->date)->format('d M Y') }}</td>
                        <td>{{ $s->time_slot }}</td>
                        <td><span class="badge bg-success">Live</span></td>
                    </tr>
                    @endforeach
                    @if($schedules->isEmpty())
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No schedules published yet.</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
