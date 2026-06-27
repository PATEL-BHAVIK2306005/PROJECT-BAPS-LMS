@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Timetable Management</h3>
    <a href="/admin" class="btn btn-outline-secondary btn-sm">Dashboard</a>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="card p-3 shadow-sm border-0 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Interactive Builder</h5>
            </div>
            <p class="text-muted small">Create a detailed, slot-by-slot timetable that students can view dynamically with support for 2-hour labs.</p>
            <a href="/admin/timetables/build" class="btn btn-outline-primary w-100 fw-bold">
                <i class="fas fa-hammer me-2"></i> Build Manually
            </a>
        </div>
        
        <div class="card p-3 shadow-sm border-0">
            <h5 class="mb-3">Upload PDF Timetable</h5>
            <form method="POST" action="/admin/timetables" enctype="multipart/form-data">
                @csrf
                <input name="title" class="form-control mb-2" placeholder="Title (e.g. Sem IV Class-1)" required>
                <select name="department_id" class="form-select mb-2">
                    <option value="">-- Select Department --</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}">{{ $d->name }}</option>
                    @endforeach
                </select>
                <select name="semester" class="form-select mb-2">
                    <option value="">-- Select Semester (Optional) --</option>
                    @for($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}">Semester {{ $i }}</option>
                    @endfor
                </select>
                <input type="file" name="file" class="form-control mb-2" accept=".pdf,image/*" required>
                <button class="btn btn-primary w-100">Upload Timetable</button>
            </form>
        </div>
    </div>
    
    <div class="col-md-8">
        <div class="card p-3 shadow-sm border-0">
            <h5>Existing Timetables</h5>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Title</th>
                            <th>Department</th>
                            <th>Semester</th>
                            <th>Uploaded</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($timetables as $t)
                        <tr>
                            <td>{{ $t->title }}</td>
                            <td>{{ $t->department->name ?? 'N/A' }}</td>
                            <td>{{ $t->semester ?? 'N/A' }}</td>
                            <td>{{ $t->created_at->format('d M Y') }}</td>
                            <td>
                                @if($t->file_path)
                                    <a href="{{ asset('storage/' . $t->file_path) }}" target="_blank" class="btn btn-sm btn-info text-white">View PDF</a>
                                @else
                                    <a href="/timetables/{{ $t->id }}" target="_blank" class="btn btn-sm btn-success text-white">View Grid</a>
                                    <a href="/admin/timetables/{{ $t->id }}/faculty-view" class="btn btn-sm btn-info text-white"><i class="fas fa-chalkboard-teacher me-1"></i> Faculty View</a>
                                    @if(in_array(session('user_role'), ['cr', 'hod', 'dean', 'admin']))
                                        <a href="/admin/timetables/{{ $t->id }}/edit" class="btn btn-sm btn-warning text-dark"><i class="fas fa-edit"></i> Edit Faculty/Slots</a>
                                    @endif
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No timetables uploaded yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection
