@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="fas fa-clipboard-list text-purple me-2"></i> Assignments Management</h3>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

<div class="row g-4">
    <div class="col-md-4">
        <h5 class="fw-bold">Create New Assignment</h5>
        <div class="glass-card p-4 border-0 shadow-sm">
            <form method="POST" action="/admin/task">
                @csrf
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Target Course</label>
                    <select name="course_id" class="form-control" required>
                        <option value="">-- Select Course --</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}">{{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Assignment Title</label>
                    <input name="title" class="form-control" placeholder="e.g. Lab 1: Database Normalization" required>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Instructions / Description</label>
                    <textarea name="description" class="form-control" placeholder="Detailed assignment instructions..." rows="4"></textarea>
                </div>
                <div class="mb-3">
                    <label class="small fw-bold mb-1">Due Date</label>
                    <input name="due_date" type="date" class="form-control">
                </div>
                <button type="submit" class="btn btn-dark w-100 fw-bold shadow-sm"><i class="fas fa-plus me-1"></i> Issue Assignment</button>
            </form>
        </div>
    </div>

    <div class="col-md-8">
        <h5 class="fw-bold">Active Assignments Bank</h5>
        <div class="glass-card p-0 border-0 shadow-sm overflow-hidden">
            <table class="table table-hover mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>ID</th>
                        <th>Course</th>
                        <th>Assignment Title</th>
                        <th>Due Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tasks as $t)
                    <tr>
                        <td class="fw-bold text-muted">#{{ $t->id }}</td>
                        <td><span class="badge bg-primary-subtle text-primary">{{ $t->course->title ?? 'N/A' }}</span></td>
                        <td class="fw-bold">{{ $t->title }}</td>
                        <td>
                            @if($t->due_date)
                                <span class="text-danger small fw-bold"><i class="far fa-clock me-1"></i> {{ \Carbon\Carbon::parse($t->due_date)->format('M d, Y') }}</span>
                            @else
                                <span class="text-muted small">No Deadline</span>
                            @endif
                        </td>
                        <td>
                            <button class="btn btn-sm btn-outline-info" onclick="alert('Viewing submissions for this assignment will be active in the next module.')">
                                <i class="fas fa-eye"></i> View Submissions
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center p-4 text-muted">
                            <i class="fas fa-folder-open fa-2x mb-2 opacity-50"></i>
                            <p class="mb-0">No assignments created yet.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.text-purple { color: #6f42c1; }
.bg-purple { background-color: #6f42c1; color: white; }
</style>

@endsection
