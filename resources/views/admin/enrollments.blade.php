@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Enrollment Records (All Details)</h3>
    <a href="/admin" class="btn btn-outline-secondary btn-sm">Back to Dashboard</a>
</div>

<div class="glass-card p-4 mb-4 shadow-sm border-0 rounded-4" style="background: linear-gradient(135deg, #ffffff, #fdfbfb);">
    <h6 class="fw-bold mb-3 text-primary"><i class="fas fa-layer-group me-2"></i> Bulk Class Certificate Generator (For CRs & Faculty)</h6>
    <form action="/admin/issue-certificates-bulk" method="POST" class="d-flex flex-wrap gap-3 align-items-center">
        @csrf
        <div class="flex-grow-1" style="max-width: 400px;">
            <select name="course_id" class="form-select border-primary shadow-sm" required>
                <option value="">-- Select Class / Course Context --</option>
                @php $uniqueCourses = \App\Models\Course::all(); @endphp
                @foreach($uniqueCourses as $c)
                    <option value="{{ $c->id }}">{{ $c->title }} - {{ $c->department->name ?? 'Core' }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary shadow-sm fw-bold px-4" style="border-radius: 0.75rem;">
            <i class="fas fa-file-pdf me-2"></i> Download Full Class PDF Set
        </button>
    </form>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-sm small">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Course</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>College</th>
                <th>Dept</th>
                <th>Roll No</th>
                <th>Sem</th>
                <th>Passcode</th>
                <th>Address</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $e)
            <tr>
                <td>{{ $e->id }}</td>
                <td><b>{{ $e->course->title }}</b></td>
                <td>{{ $e->name }}</td>
                <td>{{ $e->email }}</td>
                <td>{{ $e->phone }}</td>
                <td>{{ $e->college }}</td>
                <td>{{ $e->department }}</td>
                <td>{{ $e->roll_no }}</td>
                <td>{{ $e->semester }}</td>
                <td class="text-center"><span class="badge bg-warning text-dark px-2 shadow-sm font-monospace">{{ $e->user->login_code ?? 'None' }}</span></td>
                <td>{{ $e->address }}</td>
                <td>{{ $e->created_at->format('d M Y') }}</td>
                <td>
                    {{-- Assuming we can find the user by email or enrollment_no --}}
                    @php 
                        $demoUser = \App\Models\User::where('email', $e->email)->first();
                    @endphp
                    @if($demoUser)
                        <a href="/admin/demo-student/{{ $demoUser->id }}" class="btn btn-primary btn-xs py-0 px-2" style="font-size: 10px;">View as Student</a>
                    @endif

                    @if(in_array(session('user_role'), ['admin', 'faculty', 'hod']))
                        @php 
                            $cert = null;
                            if($e->user) {
                                $cert = \App\Models\Certificate::where('user_id', $e->user->id)->where('course_id', $e->course_id)->first();
                            }
                        @endphp

                        @if($cert)
                            <div class="d-flex gap-1 mb-1">
                                <a href="/admin/certificate/preview/{{ $cert->unique_code }}" target="_blank" class="btn btn-dark btn-xs py-0 px-2 fw-bold shadow-sm" style="font-size: 10px;">
                                    <i class="fas fa-certificate me-1"></i> Cert
                                </a>
                                <a href="/admin/certificate/preview/{{ $cert->unique_code }}" target="_blank" class="btn btn-outline-dark btn-xs py-0 px-2 fw-bold shadow-sm" style="font-size: 10px;">
                                    <i class="fas fa-file-invoice me-1"></i> Transcript
                                </a>
                            </div>
                        @else
                            <form action="/admin/issue-certificate/{{ $e->id }}" method="POST" target="_blank" style="display:inline">
                                @csrf
                                <button class="btn btn-success btn-xs py-0 px-2" style="font-size: 10px;">
                                    <i class="fas fa-certificate text-xs me-1"></i> Generate
                                </button>
                            </form>
                        @endif
                    @endif

                    @if($e->user)
                        <button type="button" class="btn btn-warning btn-xs py-0 px-2 shadow-sm text-dark mt-1" style="font-size: 10px; font-weight: bold;" data-bs-toggle="modal" data-bs-target="#editPasscodeModal{{ $e->user->id }}">
                            <i class="fas fa-key me-1"></i> Edit Passcode
                        </button>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Edit Passcode Modals -->
@foreach($enrollments as $e)
    @if($e->user)
        <div class="modal fade" id="editPasscodeModal{{ $e->user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content glass-card border-0 shadow">
                    <div class="modal-header border-bottom-0 pb-0">
                        <h5 class="modal-title fw-bold"><i class="fas fa-key text-warning me-2"></i> Edit Student Passcode</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="/admin/students/{{ $e->user->id }}/passcode" method="POST">
                        @csrf
                        <div class="modal-body">
                            <p class="text-muted small mb-4">You are updating the course login code for <strong class="text-dark">{{ $e->user->name }}</strong>.</p>
                            
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-dark">5-Digit Login Code</label>
                                <input type="text" name="login_code" class="form-control font-monospace form-control-lg text-center fw-bold text-primary" value="{{ $e->user->login_code }}" required maxlength="5" pattern="\d{5}">
                            </div>
                        </div>
                        <div class="modal-footer border-top-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Update Passcode</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif
@endforeach

@endsection
