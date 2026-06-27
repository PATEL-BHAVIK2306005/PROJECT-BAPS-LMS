@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-tasks text-primary me-2"></i> Quiz Management</h3>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

<div class="row g-4">
    <div class="col-md-6">
        <h5 class="fw-bold">Create New Quiz Shell</h5>
        <div class="card p-4 border-0 bg-white shadow-sm">
            <form method="POST" action="/admin/quiz">
            @csrf
            <label class="small fw-bold mb-1">Select Course</label>
            <select name="course_id" class="form-control mb-3" required>
                <option value="">-- Select Course --</option>
                @foreach($courses as $c)
                <option value="{{ $c->id }}">{{ $c->title }}</option>
                @endforeach
            </select>
            <label class="small fw-bold mb-1">Quiz Title</label>
            <input name="title" class="form-control mb-3" placeholder="e.g. Midterm Examination" required>
            <label class="small fw-bold mb-1">Total Maximum Marks</label>
            <input name="total_marks" type="number" class="form-control mb-3" placeholder="Default: 100" value="100" required>
            <label class="small fw-bold mb-1">Minimum Passing Score</label>
            <input name="min_score" type="number" class="form-control mb-4" placeholder="Default: 80">
            <button class="btn btn-warning w-100 shadow-sm fw-bold"><i class="fas fa-plus-circle me-1"></i> Create Quiz Shell</button>
            </form>
        </div>
    </div>
    
    <div class="col-md-6">
        <h5 class="fw-bold">Manage Existing Quizzes</h5>
        <div class="card p-4 border-0 bg-white shadow-sm">
            <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                @foreach(\App\Models\Quiz::with('course', 'questions')->latest()->get() as $qz)
                    <div class="list-group-item px-0 py-3 d-flex justify-content-between align-items-center border-light">
                        <div>
                            <span class="fw-bold text-dark fs-5">{{ $qz->title }}</span> 
                            <span class="badge {{ $qz->is_active ? 'bg-success' : 'bg-secondary' }} ms-2 shadow-sm">{{ $qz->is_active ? 'Published' : 'Draft' }}</span><br>
                            <span class="text-muted small"><i class="fas fa-book me-1"></i> {{ $qz->course->title ?? 'Unassigned' }}</span><br>
                            <span class="text-muted" style="font-size: 12px;"><i class="fas fa-question-circle me-1"></i> {{ $qz->questions->count() }} Questions</span>
                        </div>
                        <a href="/admin/quiz/{{ $qz->id }}/builder" class="btn btn-outline-primary rounded-pill px-3 shadow-sm fw-bold"><i class="fas fa-hammer me-1"></i> Build</a>
                    </div>
                @endforeach
                @if(\App\Models\Quiz::count() == 0)
                    <p class="text-muted text-center pt-3">No quizzes have been created yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@endsection
