@if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator']))
@php
    $academicStaffList = \App\Models\Staff::with('department')->whereIn('role', ['faculty', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'hod'])->get();
    $academicDeptList = \App\Models\Department::all();
@endphp
<div class="tab-pane fade" id="tab-academic" role="tabpanel">
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-6">
            <a href="/admin/course-management" class="action-btn py-3 shadow-sm"><i class="fas fa-video text-danger me-2 fs-5"></i> Live G-Meet & Courses</a>
        </div>
        <div class="col-12 col-md-6">
            <button class="action-btn py-3 shadow-sm" type="button" data-bs-toggle="collapse" data-bs-target="#createCourseCollapse"><i class="fas fa-plus text-success me-2 fs-5"></i> Create New Course</button>
        </div>
    </div>

    <div class="collapse mb-4" id="createCourseCollapse">
        <div class="content-card" style="border-top: 4px solid var(--baps-green);">
            <div class="content-card-header"><h5 class="content-card-title"><i class="fas fa-plus-circle text-success"></i> Create Course</h5></div>
            <form method="POST" action="/admin/course">
                @csrf
                <input name="title" class="form-control mb-3" placeholder="Course Title" required>
                <textarea name="description" class="form-control mb-3" placeholder="Description" rows="3"></textarea>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-8">
                        <select name="faculty_id" class="form-select" required>
                            <option value="">-- Select Instructor --</option>
                            @foreach($academicStaffList as $s)
                                <option value="{{ $s->id }}">{{ $s->name }} | {{ $s->department->name ?? 'General' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-md-4"><input name="duration" class="form-control" placeholder="Duration (e.g. 12 Weeks)"></div>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-12 col-md-4"><input name="credits" type="number" class="form-control" placeholder="Credits" required></div>
                    <div class="col-12 col-md-4"><input name="deadline" type="date" class="form-control" title="Course Deadline"></div>
                    <div class="col-12 col-md-4"><input name="live_time" type="datetime-local" class="form-control" title="Live Session Time"></div>
                </div>
                <input name="live_link" class="form-control mb-3" placeholder="Live Zoom/Meet Link (Optional)">
                <div class="row g-3 mb-4">
                    <div class="col-12 col-sm-6 col-md-3">
                        <select name="department_id" class="form-select">
                            <option value="">-- Dept (Optional) --</option>
                            @foreach($academicDeptList as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-3">
                        <select name="program" id="academic_program_select" class="form-select" onchange="toggleAcademicPlaceholder()" required>
                            <option value="Diploma">Diploma</option>
                            <option value="Bachelors" selected>Bachelors</option>
                            <option value="Masters">Masters</option>
                        </select>
                    </div>
                    <div class="col-12 col-sm-6 col-md-2"><input name="year" type="number" class="form-control" placeholder="Year" required></div>
                    <div class="col-12 col-sm-6 col-md-2"><input name="semester" type="number" class="form-control" placeholder="Semester" required></div>
                    <div class="col-12 col-sm-6 col-md-2"><input name="class_section" id="academic_section_input" type="text" class="form-control" placeholder="Section (e.g. Class 01)" required></div>
                </div>
                <button class="action-btn action-btn-primary"><i class="fas fa-check me-2"></i> Publish Course</button>
            </form>
        </div>
    </div>

    <div class="content-card mb-4" style="border-top: 4px solid var(--baps-blue);">
        <div class="content-card-header"><h5 class="content-card-title"><i class="fas fa-upload text-primary"></i> Upload Lesson Material</h5></div>
        <form method="POST" action="/admin/lesson" enctype="multipart/form-data">
            @csrf
            <select name="course_id" class="form-select mb-3" required>
                <option value="">-- Select Course --</option>
                @foreach($courses as $c)
                <option value="{{ $c->id }}">{{ $c->title }}</option>
                @endforeach
            </select>
            <input name="title" class="form-control mb-3" placeholder="Lesson Title" required>
            <select name="type" id="lesson_type" class="form-select mb-3">
                <option value="video">Upload Video File (.mp4)</option>
                <option value="youtube">YouTube Link</option>
                <option value="pdf">PDF Document</option>
            </select>
            <div id="file_input_div"><input type="file" name="file" id="file_field" class="form-control mb-3" required></div>
            <div class="d-none" id="url_input_div"><input name="url" id="url_field" class="form-control mb-3" placeholder="https://youtube.com/..."></div>
            <button class="action-btn action-btn-primary"><i class="fas fa-cloud-upload-alt me-2"></i> Upload Content</button>
        </form>
    </div>

    <div class="content-card">
        <div class="content-card-header">
            <h5 class="content-card-title"><i class="fas fa-list text-primary"></i> Course Catalog</h5>
            @if(session('user_role') == 'admin' || session('user_role') == 'moderator')
            <a href="/admin/enrollments" class="btn btn-sm btn-outline-primary fw-bold px-3 py-2 rounded-pill">View Enrollments</a>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead><tr><th>ID</th><th>Title</th><th>Instructor</th><th>Credits</th><th>Deadline</th><th>Action</th></tr></thead>
                <tbody>
                    @foreach($courses as $c)
                    <tr>
                        <td class="fw-bold text-secondary">#{{ $c->id }}</td>
                        <td class="fw-bold text-dark">{{ $c->title }}</td>
                        <td>
                            <div class="fw-semibold">{{ $c->faculty->name ?? 'N/A' }}</div>
                            <div class="small text-muted">{{ strtoupper($c->faculty->role ?? '') }}</div>
                        </td>
                        <td><span class="badge bg-primary rounded-pill px-3 py-2">{{ $c->credits }} Credits</span></td>
                        <td><span class="text-danger small fw-bold">{{ $c->deadline ? \Carbon\Carbon::parse($c->deadline)->format('M d, Y') : 'None' }}</span></td>
                        <td>
                            <button class="btn btn-sm btn-light border shadow-sm" data-bs-toggle="modal" data-bs-target="#editCourse{{$c->id}}" title="Edit Course"><i class="fas fa-edit text-primary"></i></button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Edit Course Modals -->
        @foreach($courses as $c)
        <div class="modal fade" id="editCourse{{$c->id}}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content border-0 shadow-lg rounded-4">
                    <div class="modal-header border-0 bg-light p-4">
                        <h5 class="modal-title fw-bold"><i class="fas fa-edit text-primary me-2"></i> Edit Course #{{ $c->id }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <form action="/admin/course/{{$c->id}}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Course Title</label>
                                <input type="text" name="title" class="form-control" value="{{ $c->title }}" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ $c->description }}</textarea>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-8">
                                    <label class="form-label small fw-bold text-muted">Instructor</label>
                                    <select name="faculty_id" class="form-select" required>
                                        <option value="">-- Select Instructor --</option>
                                        @foreach($academicStaffList as $s)
                                            <option value="{{ $s->id }}" {{ $c->faculty_id == $s->id ? 'selected' : '' }}>{{ $s->name }} | {{ $s->department->name ?? 'General' }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold text-muted">Duration</label>
                                    <input name="duration" class="form-control" value="{{ $c->duration }}">
                                </div>
                            </div>
                            <div class="row g-3 mb-3">
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold text-muted">Credits</label>
                                    <input name="credits" type="number" class="form-control" value="{{ $c->credits }}" required>
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold text-muted">Deadline</label>
                                    <input name="deadline" type="date" class="form-control" value="{{ $c->deadline ? \Carbon\Carbon::parse($c->deadline)->format('Y-m-d') : '' }}">
                                </div>
                                <div class="col-12 col-md-4">
                                    <label class="form-label small fw-bold text-muted">Live Session Time</label>
                                    <input name="live_time" type="datetime-local" class="form-control" value="{{ $c->live_time ? \Carbon\Carbon::parse($c->live_time)->format('Y-m-d\TH:i') : '' }}">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">Live Zoom/Meet Link</label>
                                <input name="live_link" class="form-control" value="{{ $c->live_link }}">
                            </div>
                            <div class="row g-3 mb-4">
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label small fw-bold text-muted">Department</label>
                                    <select name="department_id" class="form-select">
                                        <option value="">-- Dept --</option>
                                        @foreach($academicDeptList as $d)
                                            <option value="{{ $d->id }}" {{ $c->department_id == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-3">
                                    <label class="form-label small fw-bold text-muted">Program</label>
                                    <select name="program" class="form-select" required>
                                        <option value="Diploma" {{ $c->program == 'Diploma' ? 'selected' : '' }}>Diploma</option>
                                        <option value="Bachelors" {{ $c->program == 'Bachelors' ? 'selected' : '' }}>Bachelors</option>
                                        <option value="Masters" {{ $c->program == 'Masters' ? 'selected' : '' }}>Masters</option>
                                    </select>
                                </div>
                                <div class="col-12 col-sm-6 col-md-2">
                                    <label class="form-label small fw-bold text-muted">Year</label>
                                    <input name="year" type="number" class="form-control" value="{{ $c->year }}" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-2">
                                    <label class="form-label small fw-bold text-muted">Semester</label>
                                    <input name="semester" type="number" class="form-control" value="{{ $c->semester }}" required>
                                </div>
                                <div class="col-12 col-sm-6 col-md-2">
                                    <label class="form-label small fw-bold text-muted">Branch/Section</label>
                                    <input name="class_section" type="text" class="form-control" value="{{ $c->class_section }}" placeholder="e.g. Class 01" required>
                                </div>
                            </div>
                            <div class="text-end">
                                <button type="button" class="btn btn-light px-4 py-2 rounded-pill fw-bold border me-2" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill fw-bold shadow-sm">Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @endforeach

    </div>
</div>
<script>
function toggleAcademicPlaceholder() {
    const progSelect = document.getElementById('academic_program_select');
    const sectInput = document.getElementById('academic_section_input');
    if (!progSelect || !sectInput) return;
    
    if (progSelect.value === 'Diploma') {
        sectInput.placeholder = 'Branch (e.g. IT, CE, ME)';
    } else {
        sectInput.placeholder = 'Section (e.g. Class 01)';
    }
}
// Trigger initially to setup correct placeholder on page ready
document.addEventListener('DOMContentLoaded', toggleAcademicPlaceholder);
</script>
@endif
