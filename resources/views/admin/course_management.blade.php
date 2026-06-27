@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3><i class="fas fa-chalkboard-teacher text-primary me-2"></i> G-MEET AND CORCE TAB ({{ ucfirst(session('user_role')) }})</h3>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

<div class="alert alert-info border-0 shadow-sm d-flex align-items-center">
    <i class="fas fa-info-circle fa-2x me-3"></i>
    <div>
        <strong>Jay Swaminarayan!</strong> Welcome to the G-MEET AND CORCE TAB.<br>
        Configure live sessions, designate the host for today, and manage your online/offline class mode.
    </div>
    <a href="#" class="btn btn-light ms-auto fw-bold shadow-sm" style="border: 1px solid #ccc; border-radius: 20px; padding: 6px 15px;">
        <i class="fab fa-google text-danger" style="margin-right: 8px;"></i>
        Connect Google Account
    </a>
</div>

<div class="row g-4 mb-4">
    @foreach($courses as $c)
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-dark text-white fw-bold d-flex justify-content-between align-items-center">
                <span class="text-truncate" title="{{ $c->title }}" style="max-width: 80%;">{{ $c->title }}</span>
                <span class="badge bg-light text-dark">ID: {{ $c->id }}</span>
            </div>
            <div class="card-body bg-light">
                <div class="mb-3">
                    <span class="fw-bold small text-muted text-uppercase">Status:</span>
                    @if($c->class_mode == 'online')
                        <span class="badge bg-success float-end"><i class="fas fa-video me-1"></i> Live (Online)</span>
                    @else
                        <span class="badge bg-secondary float-end"><i class="fas fa-building me-1"></i> Offline Mode</span>
                    @endif
                </div>

                <div class="mb-3 p-2 bg-white border rounded">
                    <div class="small fw-bold text-primary mb-1"><i class="fas fa-user-tie me-1"></i> Today's Host</div>
                    <div class="fw-bold">{{ $c->host_name ?: 'Not Assigned' }}</div>
                    <div class="text-muted" style="font-size: 0.8rem;">{{ $c->host_email ?: 'No Email' }}</div>
                </div>

                <div class="mb-3">
                    <span class="fw-bold small text-muted text-uppercase d-block mb-1">Meet Link:</span>
                    @if($c->class_mode == 'online' && $c->google_meet_link)
                        <a href="{{ $c->google_meet_link }}" target="_blank" class="text-decoration-none fw-bold text-primary" style="word-break: break-all;">
                            <i class="fas fa-link me-1"></i> {{ $c->google_meet_link }}
                        </a>
                    @else
                        <span class="text-muted small fst-italic">No Meet link generated</span>
                    @endif
                </div>
            </div>
            <div class="card-footer bg-white border-top-0 pt-0 pb-3">
                <div class="d-grid gap-2">
                    <button class="btn btn-outline-primary fw-bold" data-bs-toggle="modal" data-bs-target="#manageCourse{{$c->id}}">
                        <i class="fas fa-cog me-1"></i> Configure Class
                    </button>
                    <button class="btn btn-outline-dark fw-bold" data-bs-toggle="modal" data-bs-target="#manageCourse{{$c->id}}">
                        <i class="fas fa-file-invoice me-1"></i> Add Transcript Content
                    </button>
                    @if(in_array(session('user_role'), ['dean', 'hod']))
                    <button class="btn btn-outline-info fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#allocateFaculty{{$c->id}}">
                        <i class="fas fa-users-cog me-1"></i> Allocate Faculties
                    </button>
                    @endif
                    @if($c->approval_status != 'approved')
                    <form action="/admin/course/{{$c->id}}/request-approval" method="POST" class="d-grid">
                        @csrf
                        <button type="submit" class="btn btn-warning fw-bold text-dark">
                            <i class="fas fa-paper-plane me-1"></i> Send for Approval
                        </button>
                    </form>
                    @else
                    <button class="btn btn-success fw-bold" disabled>
                        <i class="fas fa-check-circle me-1"></i> Course Approved
                    </button>
                    @endif
                    @if($c->class_mode == 'online')
                    <div class="d-flex gap-2">
                        @if($c->google_meet_link)
                        <a href="{{ $c->google_meet_link }}" target="_blank" class="btn btn-success fw-bold w-50">
                            <i class="fas fa-play me-1"></i> Start
                        </a>
                        @endif
                        <form action="/admin/course-management/{{$c->id}}/stop-meet" method="POST" class="w-50 m-0">
                            @csrf
                            <button type="submit" class="btn btn-danger fw-bold w-100" title="Switch to Offline Mode">
                                <i class="fas fa-stop me-1"></i> Stop
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Manage Modal -->
    <div class="modal fade" id="manageCourse{{$c->id}}" tabindex="-1">
        <div class="modal-dialog">
            <form action="/admin/course-management/{{$c->id}}/update" method="POST" class="modal-content border-0 shadow-lg">
                @csrf
                <div class="modal-header bg-primary text-white border-0">
                    <h5 class="modal-title"><i class="fas fa-cog me-2"></i> Configure: {{ $c->title }}</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <div class="mb-3">
                        <label class="fw-bold mb-1 small text-muted">Class Mode</label>
                        <select name="class_mode" class="form-control" id="modeSelect{{$c->id}}">
                            <option value="offline" {{ $c->class_mode == 'offline' ? 'selected' : '' }}>Offline (Campus Room)</option>
                            <option value="online" {{ $c->class_mode == 'online' ? 'selected' : '' }}>Online (Google Meet)</option>
                        </select>
                    </div>
                    
                    <div id="meetLinkDiv{{$c->id}}" style="{{ $c->class_mode == 'online' ? '' : 'display:none;' }}">
                        <hr class="text-muted">
                        <div class="mb-3">
                            <label class="fw-bold mb-1 small text-muted">Today's Host Name</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-user text-primary"></i></span>
                                <input type="text" name="host_name" class="form-control" value="{{ $c->host_name }}" placeholder="E.g. Swami Akshar">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold mb-1 small text-muted">Host Email ID</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-envelope text-primary"></i></span>
                                <input type="email" name="host_email" class="form-control" value="{{ $c->host_email }}" placeholder="host@example.com">
                            </div>
                            <small class="text-muted">Required for sending automated materials later.</small>
                        </div>

                        <div class="mb-3">
                            <label class="fw-bold mb-1 small text-muted">Google Meet Link</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white"><i class="fas fa-link text-primary"></i></span>
                                <input type="text" name="google_meet_link" id="meetLinkInput{{$c->id}}" class="form-control" value="{{ $c->google_meet_link }}" placeholder="https://meet.google.com/xxx-yyyy-zzz">
                            </div>
                            <div class="mt-2 d-flex justify-content-between">
                                <button type="button" class="btn btn-sm btn-outline-primary fw-bold" onclick="generateDemoLink{{$c->id}}()">
                                    <i class="fas fa-magic me-1"></i> Generate Demo Link
                                </button>
                                <a href="https://meet.google.com/new" target="_blank" class="btn btn-sm btn-outline-danger fw-bold">
                                    <i class="fas fa-external-link-alt me-1"></i> Create Real Meet & Paste
                                </a>
                            </div>
                        </div>
                    </div>

                    <hr class="text-muted">
                    <div class="mb-3">
                        <label class="fw-bold mb-1 small text-muted"><i class="fas fa-file-invoice me-1"></i> Transcript Academic Content</label>
                        <textarea name="transcript_content" class="form-control" rows="4" placeholder="Enter course-specific transcript content (e.g., Core syllabus items, special modules covered)...">{{ $c->transcript_content }}</textarea>
                        <small class="text-muted">This content will appear on the official Academic Transcript for all students enrolled in this course.</small>
                    </div>
                </div>
                <div class="modal-footer border-0 bg-white">
                    <button type="button" class="btn btn-light fw-bold" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-bold"><i class="fas fa-save me-1"></i> Save Configuration</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Allocate Faculty Modal -->
    @if(in_array(session('user_role'), ['dean', 'hod']))
    <div class="modal fade" id="allocateFaculty{{$c->id}}" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content border-0 shadow-lg">
                <div class="modal-header bg-info text-dark border-0">
                    <h5 class="modal-title fw-bold"><i class="fas fa-users-cog me-2"></i> Allocate Faculties: {{ $c->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body bg-light">
                    <!-- Existing Allocations -->
                    <div class="mb-4">
                        <h6 class="fw-bold text-primary border-bottom pb-2">Current Allocations</h6>
                        @if(isset($c->allocations) && $c->allocations->count() > 0)
                            <table class="table table-sm table-bordered bg-white">
                                <thead class="table-light">
                                    <tr>
                                        <th>Class Section</th>
                                        <th>Faculty Name</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($c->allocations as $alloc)
                                    <tr>
                                        <td class="fw-bold text-center align-middle">{{ $alloc->class_section }}</td>
                                        <td class="align-middle">
                                            @if($alloc->staff)
                                                <i class="fas fa-user-tie text-muted me-1"></i> {{ $alloc->staff->name }}
                                            @else
                                                <span class="text-danger fst-italic">Unknown Faculty ID: {{ $alloc->staff_id }}</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="text-muted fst-italic small">No faculties allocated yet. The primary coordinator will handle all classes by default.</div>
                        @endif
                    </div>

                    <!-- New Allocation Form -->
                    <form action="/admin/course-management/{{$c->id}}/allocate-faculty" method="POST">
                        @csrf
                        <h6 class="fw-bold text-primary border-bottom pb-2">New Allocation</h6>
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="fw-bold mb-1 small text-muted">Class Section</label>
                                <select name="class_section" class="form-control" required>
                                    <option value="">Select Class...</option>
                                    <option value="Class 01">Class 01</option>
                                    <option value="Class 02">Class 02</option>
                                    <option value="Class 03">Class 03</option>
                                    <option value="Class 04">Class 04</option>
                                    <option value="General">General / Open</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="fw-bold mb-1 small text-muted">Assign Faculty</label>
                                <select name="staff_id" class="form-control" required>
                                    <option value="">Select Faculty...</option>
                                    @if(isset($allFaculties))
                                        @foreach($allFaculties as $f)
                                            <option value="{{ $f->id }}">{{ $f->name }} ({{ strtoupper($f->role) }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="mt-3 text-end">
                            <button type="submit" class="btn btn-success fw-bold"><i class="fas fa-plus me-1"></i> Add Allocation</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif


    <script>
        function generateDemoLink{{$c->id}}() {
            const chars = 'abcdefghijklmnopqrstuvwxyz';
            const randomStr = (length) => {
                let result = '';
                for ( let i = 0; i < length; i++ ) {
                    result += chars.charAt(Math.floor(Math.random() * chars.length));
                }
                return result;
            };
            document.getElementById('meetLinkInput{{$c->id}}').value = 'https://meet.google.com/' + randomStr(3) + '-' + randomStr(4) + '-' + randomStr(3);
        }

        document.getElementById('modeSelect{{$c->id}}').addEventListener('change', function() {
            if(this.value === 'online') {
                document.getElementById('meetLinkDiv{{$c->id}}').style.display = 'block';
                if(!document.getElementById('meetLinkInput{{$c->id}}').value) {
                    generateDemoLink{{$c->id}}();
                }
            } else {
                document.getElementById('meetLinkDiv{{$c->id}}').style.display = 'none';
            }
        });
    </script>
    @endforeach
</div>
@endsection
