@extends('layouts.app')
@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');

    :root {
        --baps-primary: #0f172a;
        --baps-saffron: #f97316;
        --baps-saffron-hover: #ea580c;
        --baps-gold: #f59e0b;
        --baps-blue: #3b82f6;
        --baps-bg: #f8fafc;
        --baps-surface: #ffffff;
        --baps-text: #1e293b;
        --baps-muted: #64748b;
        --baps-border: #e2e8f0;
    }

    body {
        font-family: 'Inter', sans-serif !important;
        background-color: var(--baps-bg) !important;
        color: var(--baps-text);
    }

    .baps-page-header {
        background: linear-gradient(135deg, var(--baps-primary) 0%, #1e293b 100%);
        border-radius: 16px;
        padding: 2.5rem 3rem;
        margin-bottom: 2rem;
        box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);
        color: white;
        position: relative;
        overflow: hidden;
    }

    .baps-page-header::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--baps-saffron), var(--baps-gold));
    }

    .glass-card {
        background: var(--baps-surface);
        border-radius: 16px;
        border: 1px solid var(--baps-border);
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -1px rgba(0,0,0,0.03);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 20px -5px rgba(0,0,0,0.08);
    }

    .card-title-grad {
        background: linear-gradient(90deg, var(--baps-primary), var(--baps-blue));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800;
        font-size: 1.2rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .baps-btn-primary {
        background: linear-gradient(135deg, var(--baps-saffron), var(--baps-gold));
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 8px;
        font-weight: 700;
        letter-spacing: 0.5px;
        transition: all 0.2s ease;
        box-shadow: 0 4px 10px rgba(249, 115, 22, 0.3);
    }
    .baps-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(249, 115, 22, 0.4);
        color: white;
    }

    .form-select, .form-control {
        border-radius: 8px;
        border: 1px solid var(--baps-border);
        padding: 0.75rem 1rem;
        font-size: 0.95rem;
        color: var(--baps-text);
        background-color: #f8fafc;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .form-select:focus, .form-control:focus {
        border-color: var(--baps-saffron);
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.1);
        background-color: white;
    }

    .courses-box {
        background: white;
        border: 1px solid var(--baps-border);
        border-radius: 12px;
        padding: 1.5rem;
        max-height: 280px;
        overflow-y: auto;
    }

    .custom-checkbox .form-check-input {
        width: 1.2em;
        height: 1.2em;
        margin-top: 0.15em;
        border-color: #cbd5e1;
    }
    .custom-checkbox .form-check-input:checked {
        background-color: var(--baps-saffron);
        border-color: var(--baps-saffron);
    }
    .custom-checkbox .form-check-label {
        font-weight: 600;
        color: var(--baps-text);
        margin-left: 0.5rem;
        cursor: pointer;
    }

    .table-premium {
        border-collapse: separate;
        border-spacing: 0 8px;
        margin-top: -8px;
    }
    .table-premium thead th {
        border: none;
        background: transparent;
        color: var(--baps-muted);
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 1px;
        padding: 1rem 1.5rem;
    }
    .table-premium tbody tr {
        background: white;
        box-shadow: 0 2px 4px rgba(0,0,0,0.02);
        transition: transform 0.2s;
    }
    .table-premium tbody tr:hover {
        transform: scale(1.01);
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    .table-premium tbody td {
        border-top: 1px solid var(--baps-border);
        border-bottom: 1px solid var(--baps-border);
        padding: 1rem 1.5rem;
        vertical-align: middle;
    }
    .table-premium tbody td:first-child {
        border-left: 1px solid var(--baps-border);
        border-radius: 12px 0 0 12px;
    }
    .table-premium tbody td:last-child {
        border-right: 1px solid var(--baps-border);
        border-radius: 0 12px 12px 0;
    }

    .badge-modern {
        padding: 6px 12px;
        border-radius: 50px;
        font-weight: 700;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
    }
    .badge-pending { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .badge-published { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .badge-staff { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .badge-self { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

    .action-icon-btn {
        width: 36px; height: 36px;
        border-radius: 8px;
        display: inline-flex; align-items: center; justify-content: center;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
    }
    .btn-publish { background: #eff6ff; color: #3b82f6; }
    .btn-publish:hover { background: #3b82f6; color: white; transform: translateY(-2px); box-shadow: 0 4px 6px rgba(59, 130, 246, 0.2); }
    
    .btn-print { background: #f1f5f9; color: #475569; }
    .btn-print:hover { background: #1e293b; color: white; transform: translateY(-2px); box-shadow: 0 4px 6px rgba(30, 41, 59, 0.2); }

</style>

<div class="container py-4">
    
    <div class="baps-page-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-2 m-0" style="font-size: 2rem; letter-spacing: -0.5px;">
                <i class="fas fa-file-signature me-3" style="color: var(--baps-saffron);"></i>Admit Cards & Exam Registry
            </h2>
            <p class="mb-0" style="color: #cbd5e1; font-weight: 500; font-size: 1.05rem;">Manage examination applications and dynamically release official Hall Tickets.</p>
        </div>
        <a href="/admin" class="btn btn-light rounded-pill px-4 py-2 fw-bold" style="color: var(--baps-primary); box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <i class="fas fa-arrow-left me-2"></i> Dashboard Hub
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center mb-4" style="border-radius: 12px; border: none; background: #f0fdf4; color: #166534; box-shadow: 0 2px 4px rgba(0,0,0,0.02);">
            <i class="fas fa-check-circle fs-4 me-3 text-success"></i>
            <div class="fw-bold">{{ session('success') }}</div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-5">
        <!-- Submission Panel -->
        <div class="col-lg-4">
            <div class="glass-card h-100 p-4">
                <div class="mb-4">
                    <span class="card-title-grad"><i class="fas fa-user-edit text-success"></i> Override Form Submission</span>
                    <p class="small mt-2" style="color: var(--baps-muted); font-weight: 500;">Authorized staff may configure and submit exam forms on behalf of students.</p>
                </div>
                
                <form method="POST" action="/admin/exam/forms/submit">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; color: var(--baps-primary);">Select Target Student</label>
                        <select name="student_id" id="admin_student_select" class="form-select shadow-sm" required onchange="showStudentCourses(this.value)">
                            <option value="">-- Search & Choose Student --</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}">{{ $student->enrollment_no }} — {{ $student->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4" id="courses_container" style="display: none;">
                        <label class="form-label fw-bold text-uppercase" style="font-size: 0.8rem; letter-spacing: 1px; color: var(--baps-primary);">Select Examination Subjects</label>
                        <div class="courses-box" id="courses_list">
                            <!-- Checkboxes populated by JS -->
                        </div>
                    </div>

                    <button type="submit" class="baps-btn-primary w-100 d-flex justify-content-center align-items-center gap-2">
                        <i class="fas fa-paper-plane"></i> Finalize & Submit Form
                    </button>
                </form>

                <script>
                    const studentEnrollments = {
                        @foreach($students as $student)
                            "{{ $student->id }}": [
                                @foreach($student->enrollments as $enrollment)
                                    @if($enrollment->status == 'approved' && $enrollment->course)
                                        { id: "{{ $enrollment->course_id }}", title: "{{ addslashes($enrollment->course->title) }}" },
                                    @endif
                                @endforeach
                            ],
                        @endforeach
                    };

                    function showStudentCourses(studentId) {
                        const container = document.getElementById('courses_container');
                        const list = document.getElementById('courses_list');
                        list.innerHTML = '';

                        if (!studentId || !studentEnrollments[studentId] || studentEnrollments[studentId].length === 0) {
                            list.innerHTML = '<div class="text-center text-danger small fw-bold py-3"><i class="fas fa-exclamation-triangle mb-2 fs-5"></i><br>No approved enrollments found for this student.</div>';
                            container.style.display = 'block';
                            return;
                        }

                        studentEnrollments[studentId].forEach(course => {
                            list.innerHTML += `
                                <div class="form-check custom-checkbox mb-3 p-2 rounded" style="background: #f8fafc; border: 1px solid #e2e8f0; transition: 0.2s;">
                                    <input class="form-check-input ms-1" type="checkbox" name="course_ids[]" value="${course.id}" id="course_${course.id}" checked>
                                    <label class="form-check-label w-100" for="course_${course.id}">
                                        ${course.title}
                                        <div style="font-size: 0.75rem; color: #94a3b8; margin-top: 2px;">Subject Code: CS${String(course.id).padStart(3, '0')}</div>
                                    </label>
                                </div>
                            `;
                        });
                        container.style.display = 'block';
                    }
                </script>
            </div>
        </div>

        <!-- Registry List Panel -->
        <div class="col-lg-8">
            <div class="glass-card h-100 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <span class="card-title-grad"><i class="fas fa-list-alt text-info"></i> Submitted Form Registry</span>
                    <span class="badge bg-light text-dark border px-3 py-2 rounded-pill fw-bold">{{ count($forms) }} Total Records</span>
                </div>
                
                <div class="table-responsive" style="overflow-x: auto; padding-bottom: 1rem;">
                    <table class="table table-premium w-100">
                        <thead>
                            <tr>
                                <th>Student Identity</th>
                                <th>Department</th>
                                <th>Prepared By</th>
                                <th>Lifecycle</th>
                                <th class="text-end">Controls</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($forms as $form)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center gap-3">
                                            <div style="width: 40px; height: 40px; border-radius: 10px; background: #eff6ff; color: #3b82f6; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 1.1rem;">
                                                {{ substr($form->user->name, 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $form->user->name }}</div>
                                                <div style="color: #94a3b8; font-size: 0.8rem; font-family: monospace; font-weight: 600;">#{{ $form->user->enrollment_no }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-semibold" style="color: var(--baps-text); font-size: 0.9rem;">
                                            {{ $form->user->department->name ?? 'Core Menu' }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($form->filled_by_name == 'Self')
                                            <span class="badge-modern badge-self"><i class="fas fa-user me-1"></i> Self</span>
                                        @else
                                            <span class="badge-modern badge-staff"><i class="fas fa-id-badge me-1"></i> {{ str_replace('Staff: ', '', $form->filled_by_name) ?? 'Unknown' }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($form->status == 'pending')
                                            <span class="badge-modern badge-pending"><i class="fas fa-spinner fa-spin me-1"></i> Under Review</span>
                                        @else
                                            <span class="badge-modern badge-published"><i class="fas fa-check-circle me-1"></i> Published</span>
                                        @endif
                                    </td>
                                    <td class="text-end">
                                        <div class="d-flex justify-content-end gap-2">
                                            @if($form->status == 'pending')
                                                <form method="POST" action="/admin/exam/forms/{{ $form->id }}/publish" class="m-0 p-0">
                                                    @csrf
                                                    <button type="submit" class="action-icon-btn btn-publish" title="Publish Hall Ticket" onclick="return confirm('Officially publish Hall Ticket for {{ $form->user->name }}?');">
                                                        <i class="fas fa-bullhorn"></i>
                                                    </button>
                                                </form>
                                            @endif
                                            
                                            <a href="/admin/exam/admit-card/{{ $form->user_id }}" target="_blank" class="action-icon-btn btn-print text-decoration-none" title="View/Print Hall Ticket PDF">
                                                <i class="fas fa-print"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="text-center py-5">
                                            <div style="font-size: 3rem; color: #cbd5e1; margin-bottom: 1rem;"><i class="fas fa-folder-open"></i></div>
                                            <h5 class="fw-bold text-dark">Registry Empty</h5>
                                            <p class="text-muted mb-0">No examination forms have been submitted for the current term.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
