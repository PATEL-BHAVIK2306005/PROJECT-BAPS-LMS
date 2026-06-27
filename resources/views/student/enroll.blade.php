@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Enroll in: {{ $course->title }}</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="/enroll/submit">
                    @csrf
                    <input type="hidden" name="course_id" value="{{ $course->id }}">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Full Name</label>
                            <input name="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Phone Number</label>
                            <input name="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">College Name</label>
                            <input name="college" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Department (Branch)</label>
                            <select name="department" class="form-control" required>
                                <option value="">-- Select --</option>
                                @foreach($departments as $d)
                                    <option value="{{ $d->name }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Roll Number / ID</label>
                            <input name="roll_no" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Current Semester</label>
                            <select name="semester" class="form-control" required>
                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                                <option value="3">Semester 3</option>
                                <option value="4">Semester 4</option>
                                <option value="5">Semester 5</option>
                                <option value="6">Semester 6</option>
                                <option value="7">Semester 7</option>
                                <option value="8">Semester 8</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label small fw-bold">Home Address</label>
                            <input name="address" class="form-control" required>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100 py-2 fw-bold">Confirm Enrollment</button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
