@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Staff/Coordinator Bulk Enrollment</h3>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-dark text-white fw-bold">Multi-Level Enrollment Form</div>
            <div class="card-body">
                <form method="POST" action="/admin/bulk-enroll" enctype="multipart/form-data">
                    @csrf
                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2">Step 1: Student Source & Academic Profile</h6>
                    <ul class="nav nav-pills mb-3 border-bottom pb-3" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active rounded-pill fw-bold px-4" id="pills-excel-tab" data-bs-toggle="pill" data-bs-target="#pills-excel" type="button" role="tab"><i class="fas fa-file-excel me-2"></i> Excel Upload</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold px-4 ms-2" id="pills-manual-tab" data-bs-toggle="pill" data-bs-target="#pills-manual" type="button" role="tab"><i class="fas fa-keyboard me-2"></i> Manual Entry</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link rounded-pill fw-bold px-4 ms-2 text-dark" style="background:#eef2ff; border:1px solid #c7d2fe;" id="pills-existing-tab" data-bs-toggle="pill" data-bs-target="#pills-existing" type="button" role="tab"><i class="fas fa-users-cog text-primary me-2"></i> Map Registered Students</button>
                        </li>
                    </ul>
                    <div class="tab-content mb-4" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-excel" role="tabpanel">
                            <label class="small fw-bold text-muted mb-2">Upload Roster (.xlsx, .csv)</label>
                            <input type="file" name="excel_file" class="form-control bg-light border-primary shadow-sm" accept=".xlsx,.csv">
                            <small class="text-danger mt-1 d-block fw-bold"><i class="fas fa-exclamation-triangle me-1"></i> STRICT POLICY: EXACTLY 10 COLUMNS ARE COMPULSORY!</small>
                            <small class="text-muted d-block mt-1">Required headers: <b>Name, Email, ABC Card ID, Phone, DOB, Gender, Blood Group, Aadhar, Guardian Name, Address</b>.</small>
                        </div>
                        <div class="tab-pane fade" id="pills-manual" role="tabpanel">
                            <h6 class="text-danger small fw-bold mb-3 border-bottom pb-2">All 10 Fields are Compulsory for Manual Entry</h6>
                            <div class="row g-2 mb-2">
                                <div class="col-md-6"><input name="m_name" class="form-control form-control-sm" placeholder="Student Full Name"></div>
                                <div class="col-md-6"><input name="m_email" type="email" class="form-control form-control-sm" placeholder="Student Email"></div>
                                <div class="col-md-4"><input name="m_abc" class="form-control form-control-sm" placeholder="ABC Card ID"></div>
                                <div class="col-md-4"><input name="m_phone" class="form-control form-control-sm" placeholder="Phone Number"></div>
                                <div class="col-md-4"><input name="m_dob" type="date" class="form-control form-control-sm" placeholder="DOB"></div>
                                <div class="col-md-3">
                                    <select name="m_gender" class="form-control form-control-sm">
                                        <option value="">Gender...</option>
                                        <option value="Male">Male</option>
                                        <option value="Female">Female</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                                <div class="col-md-3"><input name="m_blood" class="form-control form-control-sm" placeholder="Blood Group"></div>
                                <div class="col-md-6"><input name="m_aadhar" class="form-control form-control-sm" placeholder="Aadhar Number"></div>
                                <div class="col-md-6"><input name="m_guardian" class="form-control form-control-sm" placeholder="Guardian Name"></div>
                                <div class="col-md-6"><input name="m_address" class="form-control form-control-sm" placeholder="Full Address"></div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="pills-existing" role="tabpanel">
                            <label class="small fw-bold text-dark mb-2">Select Previously Registered Students (Hold CTRL/CMD to select multiple)</label>
                            <select name="existing_students[]" multiple class="form-select shadow-sm border-primary" style="height: 160px; border-width: 2px;">
                                @foreach($students as $st)
                                    <option class="p-2 border-bottom" value="{{ $st->id }}">{{ $st->name }} (ID: {{ $st->enrollment_no ?? 'Unassigned' }}) | {{ $st->email }}</option>
                                @endforeach
                            </select>
                            <small class="text-primary mt-2 d-block fw-bold"><i class="fas fa-bolt me-1"></i> Fast-Track: Bypasses the 10-field requirement entirely since their profiles already exist in the database.</small>
                        </div>
                    </div>

                    <div class="row mb-3 mt-4">
                        <div class="col-md-6">
                            <label class="small fw-bold">Department</label>
                            <select name="department_name" class="form-control" required>
                                @foreach($departments as $d)
                                    <option value="{{ $d->name }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="small fw-bold">Program</label>
                            <select name="program" class="form-control" required>
                                <option value="Bachelors">Bachelors (4 Yrs)</option>
                                <option value="Masters">Masters (2 Yrs)</option>
                                <option value="PhD">PhD (Flexible)</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-4"><label class="small fw-bold">Year</label><input name="year" type="number" class="form-control" required></div>
                        <div class="col-md-4"><label class="small fw-bold">Semester</label><input name="semester" type="number" class="form-control" required></div>
                        <div class="col-md-4"><label class="small fw-bold">Class Section</label><input name="class_section" class="form-control" placeholder="01-05" required></div>
                    </div>

                    <h6 class="text-primary fw-bold mb-3 border-bottom pb-2 mt-5">Step 2: Course Selection</h6>
                    <div class="border p-3 rounded bg-light mb-3">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="enroll_type" value="all" id="typeAll" checked>
                            <label class="form-check-label fw-bold" for="typeAll">Enroll in ALL courses for this Semester</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="enroll_type" value="specific" id="typeSpecific">
                            <label class="form-check-label fw-bold" for="typeSpecific">Select Specific Courses</label>
                        </div>
                    </div>

                    <div id="courseSelection" class="d-none mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <label class="small fw-bold text-dark">Step 2: Choose Specific Courses</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="selectAllCourses">
                                <label class="form-check-label small fw-bold" for="selectAllCourses">Select All</label>
                            </div>
                        </div>
                        <input type="text" id="courseSearch" class="form-control form-control-sm mb-2 shadow-sm" placeholder="🔍 Search courses by name or ID...">
                        <div class="border rounded bg-white shadow-sm" style="max-height: 200px; overflow-y: auto;">
                            <div class="list-group list-group-flush" id="courseList">
                                @foreach($courses as $course)
                                    <label class="list-group-item list-group-item-action d-flex align-items-center py-2 px-3 course-item" style="cursor: pointer;" data-semester="{{ $course->semester }}">
                                        <input class="form-check-input me-3 course-checkbox" type="checkbox" name="course_ids[]" value="{{ $course->id }}">
                                        <div class="flex-grow-1">
                                            <div class="fw-bold small text-dark course-title">{{ $course->title }}</div>
                                            <div class="text-muted small" style="font-size: 0.7rem;">
                                                <i class="fas fa-fingerprint me-1"></i> ID: {{ $course->id }} 
                                                @if($course->semester) | <i class="fas fa-calendar-alt me-1"></i> Sem: {{ $course->semester }} @endif
                                                @if($course->program) | <i class="fas fa-graduation-cap me-1"></i> {{ $course->program }} @endif
                                            </div>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-primary w-100 py-2">Submit Enrollment</button>
                    @if(session('success'))
                        <div class="alert alert-success mt-3 py-2 small">{{ session('success') }}</div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle Specific Course Selection Visibility
    $('input[name="enroll_type"]').change(function() {
        if ($(this).val() == 'specific') {
            $('#courseSelection').removeClass('d-none');
        } else {
            $('#courseSelection').addClass('d-none');
        }
    });

    // Select All Courses Toggle
    $('#selectAllCourses').click(function() {
        $('.course-checkbox:visible').prop('checked', this.checked);
    });

    // Course Search/Filter
    $('#courseSearch').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $("#courseList .course-item").filter(function() {
            $(this).toggle($(this).find('.course-title').text().toLowerCase().indexOf(value) > -1 || 
                         $(this).text().toLowerCase().indexOf(value) > -1);
        });
    });

    // Auto-filter by Semester Input
    $('input[name="semester"]').on('input', function() {
        var sem = $(this).val();
        if (sem) {
            $("#courseList .course-item").each(function() {
                var courseSem = $(this).data('semester');
                if (courseSem == sem) {
                    $(this).show();
                } else {
                    $(this).hide();
                    $(this).find('.course-checkbox').prop('checked', false);
                }
            });
        } else {
            $("#courseList .course-item").show();
        }
    });
</script>

@endsection
