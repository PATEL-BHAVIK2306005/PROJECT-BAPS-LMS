@extends('layouts.app')
@section('content')

@php
    $currentUserRole = session('user_role');

    // Retrieve other university positions
    $president = $president ?? \App\Models\Staff::where('role', 'dean')->where('email', 'president@itmbu.ac.in')->first()
                ?? \App\Models\Staff::where('role', 'dean')->where('name', 'like', '%President%')->first();
    $vicePresident = $vicePresident ?? \App\Models\Staff::where('role', 'dean')->where('email', 'vp@itmbu.ac.in')->first()
                      ?? \App\Models\Staff::where('role', 'dean')->where('name', 'like', '%Vice%')->first();
    $provost = $provost ?? \App\Models\Staff::where('role', 'dean')->where('email', 'vedvas.dwivedi.provost@itmbu.ac.in')->first()
                ?? \App\Models\Staff::where('role', 'dean')->where('name', 'like', '%Provost%')->first();
    $registrar = $registrar ?? \App\Models\Staff::where('role', 'dean')->where('email', 'registrar@itmbu.ac.in')->first()
                ?? \App\Models\Staff::where('role', 'dean')->where('name', 'like', '%Registrar%')->first();

    // Retrieve Super Admins
    $superAdmins = \App\Models\Staff::where('role', 'admin')->get();

    // Retrieve non-admin staff for promotion dropdown
    $nonAdmins = \App\Models\Staff::where('role', '!=', 'admin')->orderBy('name')->get();

    // Retrieve directors
    $directors = \App\Models\Staff::where('role', 'director')
                ->orWhereJsonContains('positions', 'Director')
                ->orWhereJsonContains('positions', 'director')
                ->get();

    // Retrieve board members
    $boardMembers = \App\Models\Staff::where('role', 'board-member')
                ->orWhereJsonContains('positions', 'Board Member')
                ->orWhereJsonContains('positions', 'board member')
                ->orWhereJsonContains('positions', 'Bord of member')
                ->get();

    // Retrieve external coordinators
    $externalCoordinators = \App\Models\Staff::where('role', 'external-coordinator')
                ->orWhereJsonContains('positions', 'External Coordinator')
                ->orWhereJsonContains('positions', 'external coordinator')
                ->orWhereJsonContains('positions', 'Company Coordinator')
                ->orWhereJsonContains('positions', 'company coordinator')
                ->get();

    // Dynamically retrieve all staff with role = 'dean' who are NOT executive officers, directors, board members, or external coordinators
    $execIds = array_filter(array_merge(
        [
            $president?->id,
            $vicePresident?->id,
            $provost?->id,
            $registrar?->id
        ],
        $directors->pluck('id')->toArray(),
        $boardMembers->pluck('id')->toArray(),
        $externalCoordinators->pluck('id')->toArray()
    ));

    $allDeansQuery = \App\Models\Staff::where('role', 'dean');
    if (!empty($execIds)) {
        $allDeansQuery->whereNotIn('id', $execIds);
    }
    $dynamicDeans = $allDeansQuery->get();

    // Map them to titles and background gradients
    $deansList = [];
    foreach ($dynamicDeans as $dModel) {
        $nameLower = strtolower($dModel->name);
        $emailLower = strtolower($dModel->email);
        $positions = is_array($dModel->positions) ? array_map('strtolower', $dModel->positions) : [];

        // Identify title and color
        if (str_contains($nameLower, 'main dean') || in_array('main dean', $positions) || str_contains($emailLower, 'pradeep.laxkar')) {
            $title = 'Main Dean';
            $color = 'linear-gradient(135deg, #0f172a, #1e293b)';
        } elseif (str_contains($nameLower, 'placement') || in_array('placement dean', $positions) || in_array('placement & development head', $positions) || str_contains($emailLower, 'maddona')) {
            $title = 'Placement Dean';
            $color = 'linear-gradient(135deg, #9333ea, #a855f7)';
        } elseif (str_contains($nameLower, 'associate') || in_array('associate dean', $positions) || str_contains($emailLower, 'gaurav.kulkarni') || str_contains($emailLower, 'kalpana.matre')) {
            $title = 'Associate Dean';
            $color = 'linear-gradient(135deg, #475569, #334155)';
        } elseif (str_contains($nameLower, 'co-dean') || in_array('co-dean', $positions) || str_contains($emailLower, 'asutosh.abhangi')) {
            $title = 'Co-Dean';
            $color = 'linear-gradient(135deg, #64748b, #475569)';
        } else {
            // General Dean case (detecting new dean or related post)
            if (str_contains($nameLower, 'co-dean') || in_array('co-dean', $positions)) {
                $title = 'Co-Dean';
                $color = 'linear-gradient(135deg, #64748b, #475569)';
            } elseif (str_contains($nameLower, 'associate') || in_array('associate dean', $positions)) {
                $title = 'Associate Dean';
                $color = 'linear-gradient(135deg, #475569, #334155)';
            } else {
                $title = 'Dean';
                $color = 'linear-gradient(135deg, #3b82f6, #1d4ed8)'; // Nice blue gradient for new/general deans
            }
        }

        $deansList[] = [
            'model' => $dModel,
            'title' => $title,
            'color' => $color
        ];
    }

    // Sort the list so Main Dean is first, then Associate Deans, then Co-Deans, then Deans, then Placement Dean
    usort($deansList, function($a, $b) {
        $order = [
            'main dean' => 1,
            'associate dean' => 2,
            'co-dean' => 3,
            'dean' => 4,
            'placement dean' => 5
        ];
        $ta = strtolower($a['title']);
        $tb = strtolower($b['title']);
        $oa = $order[$ta] ?? 99;
        $ob = $order[$tb] ?? 99;
        return $oa <=> $ob;
    });

    // Find the main dean from the deans list for backward compatibility
    $mainDeanModel = null;
    foreach ($deansList as $d) {
        if ($d['title'] === 'Main Dean') {
            $mainDeanModel = $d['model'];
            break;
        }
    }
    $mainDean = $mainDean ?? $mainDeanModel ?? \App\Models\Staff::where('role', 'dean')->where('email', 'pradeep.laxkar.dean@itmbu.ac.in')->first();
@endphp

<!-- Header Section with Saffron Accents & Glassmorphism -->
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-left: 5px solid #ea580c !important;">
    <div class="card-body p-4 text-white">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-white text-dark rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; color: #ea580c;">
                    <i class="fas fa-building text-orange" style="color: #ea580c;"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1">Department Management & Roster</h4>
                    <div class="small text-light opacity-75 fw-semibold">View and configure academic departments, assign Head of Departments (HOD), and manage contact information.</div>
                </div>
            </div>
            <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
        </div>
    </div>
</div>

<!-- Stats Counters Grid -->
<div class="row g-4 mb-4 text-center">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-bottom: 3px solid #ea580c;">
            <div class="fs-2 fw-bold text-dark">{{ $departments->count() }}</div>
            <div class="text-muted small fw-semibold text-uppercase">Departments</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-bottom: 3px solid #6366f1;">
            <div class="fs-2 fw-bold text-dark">{{ \App\Models\Staff::where('role', 'hod')->count() }}</div>
            <div class="text-muted small fw-semibold text-uppercase">Total HODs</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-bottom: 3px solid #10b981;">
            <div class="fs-2 fw-bold text-dark">{{ ($president ? 1 : 0) + ($vicePresident ? 1 : 0) + ($provost ? 1 : 0) + ($registrar ? 1 : 0) + count($deansList) + count($directors) + count($boardMembers) + count($externalCoordinators) }}</div>
            <div class="text-muted small fw-semibold text-uppercase">Leadership Team</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-bottom: 3px solid #a855f7;">
            <div class="fs-2 fw-bold text-dark">{{ \App\Models\Staff::where('role', 'faculty')->count() }}</div>
            <div class="text-muted small fw-semibold text-uppercase">Faculty Members</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left column: Admin Actions -->
    @if(in_array($currentUserRole, ['admin', 'dean']))
    <div class="col-12 col-lg-4">
        <div class="d-flex flex-column gap-4">
            <!-- 1. Create Department Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white glass-card">
                <h5 class="fw-bold mb-3 d-flex align-items-center text-dark"><i class="fas fa-folder-plus text-orange me-2" style="color: #ea580c;"></i> Create Department</h5>
                <form method="POST" action="/admin/departments">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Department Name</label>
                        <input name="name" class="form-control rounded-3" placeholder="Full Name (e.g. Computer Science)" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Code</label>
                        <input name="code" class="form-control rounded-3" placeholder="Code (e.g. CSE)" required>
                    </div>

                    <!-- Program & Branch Configuration -->
                    <div class="mb-3 border-top pt-3">
                        <label class="form-label small fw-bold text-muted mb-2 d-block"><i class="fas fa-graduation-cap text-orange me-1" style="color: #ea580c;"></i> Academic Levels & Programs</label>
                        
                        <!-- 1. Diploma -->
                        <div class="mb-3 border-bottom pb-2">
                            <div class="form-check form-switch small mb-1">
                                <input class="form-check-input" type="checkbox" id="checkDiploma_create" name="programs[diploma][enabled]" value="1" onchange="toggleLevelContainer('diploma', 'create', this.checked)">
                                <label class="form-check-label fw-semibold text-dark" for="checkDiploma_create">Diploma Programs</label>
                            </div>
                            <div id="diplomaContainer_create" class="d-none ms-2 ps-2 border-start border-2 border-orange-subtle mt-2">
                                <div class="program-list" id="diplomaList_create"></div>
                                <button type="button" class="btn btn-xs btn-outline-orange rounded-pill mt-1" onclick="addProgramRow('diploma', 'create')">
                                    <i class="fas fa-plus me-1"></i> Add Program
                                </button>
                            </div>
                        </div>

                        <!-- 2. Bachelors -->
                        <div class="mb-3 border-bottom pb-2">
                            <div class="form-check form-switch small mb-1">
                                <input class="form-check-input" type="checkbox" id="checkBachelors_create" name="programs[bachelors][enabled]" value="1" onchange="toggleLevelContainer('bachelors', 'create', this.checked)">
                                <label class="form-check-label fw-semibold text-dark" for="checkBachelors_create">Bachelor's Programs (UG)</label>
                            </div>
                            <div id="bachelorsContainer_create" class="d-none ms-2 ps-2 border-start border-2 border-orange-subtle mt-2">
                                <div class="program-list" id="bachelorsList_create"></div>
                                <button type="button" class="btn btn-xs btn-outline-orange rounded-pill mt-1" onclick="addProgramRow('bachelors', 'create')">
                                    <i class="fas fa-plus me-1"></i> Add Program
                                </button>
                            </div>
                        </div>

                        <!-- 3. Honours Bachelors -->
                        <div class="mb-3 border-bottom pb-2">
                            <div class="form-check form-switch small mb-1">
                                <input class="form-check-input" type="checkbox" id="checkHonsBachelors_create" name="programs[hons_bachelors][enabled]" value="1" onchange="toggleLevelContainer('hons_bachelors', 'create', this.checked)">
                                <label class="form-check-label fw-semibold text-dark" for="checkHonsBachelors_create">Honours Bachelor's Programs (Hons)</label>
                            </div>
                            <div id="hons_bachelorsContainer_create" class="d-none ms-2 ps-2 border-start border-2 border-orange-subtle mt-2">
                                <div class="program-list" id="hons_bachelorsList_create"></div>
                                <button type="button" class="btn btn-xs btn-outline-orange rounded-pill mt-1" onclick="addProgramRow('hons_bachelors', 'create')">
                                    <i class="fas fa-plus me-1"></i> Add Program
                                </button>
                            </div>
                        </div>

                        <!-- 4. Masters -->
                        <div class="mb-3 border-bottom pb-2">
                            <div class="form-check form-switch small mb-1">
                                <input class="form-check-input" type="checkbox" id="checkMasters_create" name="programs[masters][enabled]" value="1" onchange="toggleLevelContainer('masters', 'create', this.checked)">
                                <label class="form-check-label fw-semibold text-dark" for="checkMasters_create">Master's Programs (PG)</label>
                            </div>
                            <div id="mastersContainer_create" class="d-none ms-2 ps-2 border-start border-2 border-orange-subtle mt-2">
                                <div class="program-list" id="mastersList_create"></div>
                                <button type="button" class="btn btn-xs btn-outline-orange rounded-pill mt-1" onclick="addProgramRow('masters', 'create')">
                                    <i class="fas fa-plus me-1"></i> Add Program
                                </button>
                            </div>
                        </div>

                        <!-- 5. PhD -->
                        <div class="mb-3">
                            <div class="form-check form-switch small mb-1">
                                <input class="form-check-input" type="checkbox" id="checkPhd_create" name="programs[phd][enabled]" value="1" onchange="toggleLevelContainer('phd', 'create', this.checked)">
                                <label class="form-check-label fw-semibold text-dark" for="checkPhd_create">PhD Programs (Doctoral)</label>
                            </div>
                            <div id="phdContainer_create" class="d-none ms-2 ps-2 border-start border-2 border-orange-subtle mt-2">
                                <div class="program-list" id="phdList_create"></div>
                                <button type="button" class="btn btn-xs btn-outline-orange rounded-pill mt-1" onclick="addProgramRow('phd', 'create')">
                                    <i class="fas fa-plus me-1"></i> Add Program
                                </button>
                            </div>
                        </div>
                    </div>

                    <button class="btn btn-premium w-100 py-2 rounded-pill fw-bold shadow-sm" style="background: linear-gradient(135deg, #ea580c 0%, #d97706 100%); color: white; border: none;">Add Department</button>
                </form>
            </div>

            <!-- 2. HOD Special Arrangement Card -->
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white glass-card">
                <h5 class="fw-bold mb-1 d-flex align-items-center text-dark"><i class="fas fa-user-shield text-indigo me-2" style="color: #6366f1;"></i> HOD Special Assignment</h5>
                <p class="text-muted small mb-3">Admin/Dean special arrangement panel to allocate or change department assignments for HODs.</p>
                <form method="POST" action="/admin/departments/assign-hod">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Select HOD</label>
                        <select name="staff_id" class="form-select rounded-3" required>
                            <option value="">-- Select HOD --</option>
                            @foreach($allHods as $hodStaff)
                                <option value="{{ $hodStaff->id }}">{{ $hodStaff->name }} ({{ $hodStaff->department->code ?? 'Unassigned' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Assign to Department</label>
                        <select name="department_id" class="form-select rounded-3">
                            <option value="">-- Unassign / General Pool --</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->code }})</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="btn w-100 py-2 rounded-pill fw-bold text-white shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%); border: none;">Apply Special Arrangement</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-8">
    @else
    <div class="col-12">
    @endif
        <!-- Modern Navigation Capsule Pills -->
        <ul class="nav nav-pills nav-fill gap-2 p-2 bg-light rounded-pill mb-4 shadow-sm" id="rosterTabs" role="tablist" style="border: 1px solid rgba(0,0,0,0.06);">
            <li class="nav-item" role="presentation">
                <button class="nav-link active rounded-pill fw-bold py-2 px-3 text-secondary-custom transition-all" id="governance-tab" data-bs-toggle="tab" data-bs-target="#governance-pane" type="button" role="tab" aria-controls="governance-pane" aria-selected="true">
                    <i class="fas fa-university me-2 text-indigo-custom"></i> Governance Council
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill fw-bold py-2 px-3 text-secondary-custom transition-all" id="departments-tab" data-bs-toggle="tab" data-bs-target="#departments-pane" type="button" role="tab" aria-controls="departments-pane" aria-selected="false">
                    <i class="fas fa-building me-2 text-orange-custom"></i> Departments Directory
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link rounded-pill fw-bold py-2 px-3 text-secondary-custom transition-all" id="verification-tab" data-bs-toggle="tab" data-bs-target="#verification-pane" type="button" role="tab" aria-controls="verification-pane" aria-selected="false" style="color: #ea580c !important;">
                    <i class="fas fa-file-signature me-2" style="color: #ea580c;"></i> AI Verification Ledger
                </button>
            </li>
        </ul>

        <!-- Tab Panes Content -->
        <div class="tab-content" id="rosterTabsContent">
            
            <!-- 1. GOVERNANCE COUNCIL PANE -->
            <div class="tab-pane fade show active" id="governance-pane" role="tabpanel" aria-labelledby="governance-tab">
                <!-- Super Admin Directory -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4 mb-4" style="border-left: 5px solid #dc2626 !important; background: linear-gradient(to right, #ffffff, #fff5f5);">
                    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                        <h5 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                            <i class="fas fa-user-shield text-danger" style="color: #dc2626;"></i>
                            <span>Super Admin Directory</span>
                            <span class="badge bg-danger-subtle text-danger px-3 py-1 rounded-pill small" style="font-size: 0.75rem;">300% System Access</span>
                        </h5>
                        @if(in_array($currentUserRole, ['admin', 'dean']))
                            <div class="d-flex gap-2">
                                <button class="btn btn-sm btn-danger rounded-pill px-3 py-1.5 fw-bold" onclick="document.getElementById('promoteAdminFormContainer').classList.toggle('d-none')">
                                    <i class="fas fa-user-plus me-1"></i> Add / Promote Super Admin
                                </button>
                            </div>
                        @endif
                    </div>

                    @if(in_array($currentUserRole, ['admin', 'dean']))
                    <!-- Promote Admin Form Collapse -->
                    <div id="promoteAdminFormContainer" class="d-none mb-4 p-4 bg-light rounded-4 border border-danger shadow-sm">
                        <div class="row g-3">
                            <!-- Section 1: Promote Existing -->
                            <div class="col-12 col-md-6 border-end pr-md-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-arrow-up text-danger me-1"></i> Promote Existing Staff to Super Admin</h6>
                                <form method="POST" action="/admin/super-admins/promote">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">Select Staff Member</label>
                                        <select name="staff_id" class="form-select rounded-3" required>
                                            <option value="">-- Choose Staff --</option>
                                            @foreach($nonAdmins as $na)
                                                <option value="{{ $na->id }}">{{ $na->name }} (Role: {{ ucfirst($na->role) }})</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4 fw-bold">Promote to Super Admin</button>
                                </form>
                            </div>
                            
                            <!-- Section 2: Create New Super Admin -->
                            <div class="col-12 col-md-6 pl-md-4">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-user-plus text-danger me-1"></i> Create Brand New Super Admin</h6>
                                <form method="POST" action="/admin/staff">
                                    @csrf
                                    <input type="hidden" name="role" value="admin">
                                    <input type="hidden" name="scope" value="universal">
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 col-sm-6">
                                            <input name="name" class="form-control form-control-sm rounded" placeholder="Full Name" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input name="unique_code" class="form-control form-control-sm rounded" placeholder="Unique Code (e.g. ITM_ADMIN_01)" required>
                                        </div>
                                    </div>
                                    <div class="row g-2 mb-2">
                                        <div class="col-12 col-sm-6">
                                            <input type="email" name="email" class="form-control form-control-sm rounded" placeholder="Email Address" required>
                                        </div>
                                        <div class="col-12 col-sm-6">
                                            <input type="password" name="password" class="form-control form-control-sm rounded" placeholder="Password (Min 6 chars)">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-outline-danger btn-sm rounded-pill px-4 fw-bold">Create & Enroll Admin</button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="row g-4">
                        @forelse($superAdmins as $admin)
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="p-3 bg-light rounded-4 border h-100 shadow-sm d-flex flex-column justify-content-between hover-grow">
                                    <div>
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="avatar-circle text-white shadow-sm" style="width: 48px; height: 48px; font-size: 1.2rem; background: linear-gradient(135deg, #dc2626, #b91c1c); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">
                                                {{ strtoupper(substr($admin->name, 0, 2)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-between gap-1 flex-wrap">
                                                    <span>{{ $admin->name }}</span>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="badge bg-danger text-white small" style="font-size: 0.65rem;">Super Admin</span>
                                                        @if(in_array($currentUserRole, ['admin', 'dean']))
                                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 0.75rem;" onclick="document.getElementById('editAdminForm_{{ $admin->id }}').classList.toggle('d-none')">
                                                                <i class="fas fa-edit me-1"></i> Edit
                                                            </button>
                                                            @if(session('staff_id') != $admin->id)
                                                                <form method="POST" action="/admin/super-admins/{{ $admin->id }}/demote" class="d-inline" onsubmit="return confirm('Are you sure you want to remove this user from Super Admins? They will be demoted to Faculty role.')">
                                                                    @csrf
                                                                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill px-2 py-0" style="font-size: 0.75rem;">
                                                                        <i class="fas fa-user-minus me-1"></i> Remove
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        @endif
                                                    </div>
                                                </h6>
                                                <div class="text-secondary small mb-1"><i class="fas fa-envelope me-2 text-muted"></i>{{ $admin->email }}</div>
                                                <div class="text-secondary small mb-1"><i class="fas fa-phone me-2 text-muted"></i>{{ $admin->phone ?? 'N/A' }}</div>
                                                <div class="text-secondary small">
                                                    <i class="fas fa-shield-alt me-2 text-muted"></i>
                                                    <strong>Access Level:</strong> 300% (Super Supreme Access)
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(in_array($currentUserRole, ['admin', 'dean']))
                                    <!-- Inline Admin Edit Form -->
                                    <div id="editAdminForm_{{ $admin->id }}" class="d-none mt-3 p-3 bg-white rounded border border-warning">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-user-edit text-orange me-1" style="color: #ea580c;"></i> Edit Admin Contact</h6>
                                        <form method="POST" action="/admin/staff/{{ $admin->id }}/update">
                                            @csrf
                                            <input type="hidden" name="role" value="{{ $admin->role }}">
                                            <input type="hidden" name="unique_code" value="{{ $admin->unique_code }}">
                                            <input type="hidden" name="department_id" value="{{ $admin->department_id }}">
                                            
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Full Name</label>
                                                <input type="text" name="name" class="form-control form-control-sm rounded" value="{{ $admin->name }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Email Address</label>
                                                <input type="email" name="email" class="form-control form-control-sm rounded" value="{{ $admin->email }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Phone Number</label>
                                                <input type="text" name="phone" class="form-control form-control-sm rounded" value="{{ $admin->phone }}" placeholder="e.g. +91 98765 43210">
                                            </div>
                                            <div class="d-flex gap-2 mt-2">
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">Save Changes</button>
                                                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 border" onclick="document.getElementById('editAdminForm_{{ $admin->id }}').classList.add('d-none')">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-3">
                                <span class="text-muted small">No Super Admins registered in the directory.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- University Executive Officers -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4 mb-4" style="border-left: 5px solid #1e3a8a !important;">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                        <i class="fas fa-user-tie text-primary" style="color: #1e3a8a;"></i>
                        <span>University Executive Council</span>
                        <span class="badge bg-primary-subtle text-primary px-3 py-1 rounded-pill small" style="font-size: 0.75rem;">Central Governance</span>
                    </h5>
                    
                    <div class="row g-4">
                        @php
                            $executives = [
                                ['model' => $president, 'title' => 'President / Chancellor', 'color' => 'linear-gradient(135deg, #1e3a8a, #3b82f6)'],
                                ['model' => $vicePresident, 'title' => 'Vice President', 'color' => 'linear-gradient(135deg, #3b82f6, #60a5fa)'],
                                ['model' => $provost, 'title' => 'Provost', 'color' => 'linear-gradient(135deg, #0d9488, #14b8a6)'],
                                ['model' => $registrar, 'title' => 'Registrar', 'color' => 'linear-gradient(135deg, #4f46e5, #818cf8)'],
                            ];
                        @endphp
                        
                        @foreach($executives as $exec)
                            @if($exec['model'])
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="p-3 bg-light rounded-4 border h-100 shadow-sm d-flex flex-column justify-content-between hover-grow">
                                        <div>
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="avatar-circle text-white shadow-sm" style="width: 48px; height: 48px; font-size: 1.2rem; background: {{ $exec['color'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">
                                                    {{ strtoupper(substr($exec['model']->name, 0, 2)) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-between gap-1 flex-wrap">
                                                        <span>{{ $exec['model']->name }}</span>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <span class="badge bg-secondary-subtle text-secondary small" style="font-size: 0.65rem;">{{ $exec['title'] }}</span>
                                                            @if(in_array($currentUserRole, ['admin', 'dean']))
                                                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 0.75rem;" onclick="document.getElementById('editExecForm_{{ $exec['model']->id }}').classList.toggle('d-none')">
                                                                    <i class="fas fa-edit me-1"></i> Edit
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </h6>
                                                    <div class="text-secondary small mb-1"><i class="fas fa-envelope me-2 text-muted"></i>{{ $exec['model']->email }}</div>
                                                    <div class="text-secondary small"><i class="fas fa-phone me-2 text-muted"></i>{{ $exec['model']->phone ?? 'N/A' }}</div>
                                                </div>
                                            </div>
                                        </div>

                                        @if(in_array($currentUserRole, ['admin', 'dean']))
                                        <!-- Inline Exec Edit Form -->
                                        <div id="editExecForm_{{ $exec['model']->id }}" class="d-none mt-3 p-3 bg-white rounded border border-warning">
                                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-user-edit text-orange me-1" style="color: #ea580c;"></i> Edit Contact Info</h6>
                                            <form method="POST" action="/admin/staff/{{ $exec['model']->id }}/update">
                                                @csrf
                                                <input type="hidden" name="role" value="{{ $exec['model']->role }}">
                                                <input type="hidden" name="unique_code" value="{{ $exec['model']->unique_code }}">
                                                <input type="hidden" name="department_id" value="{{ $exec['model']->department_id }}">
                                                
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-muted mb-1">Full Name</label>
                                                    <input type="text" name="name" class="form-control form-control-sm rounded" value="{{ $exec['model']->name }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-muted mb-1">Email Address</label>
                                                    <input type="email" name="email" class="form-control form-control-sm rounded" value="{{ $exec['model']->email }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-muted mb-1">Phone Number</label>
                                                    <input type="text" name="phone" class="form-control form-control-sm rounded" value="{{ $exec['model']->phone }}" placeholder="e.g. +91 98765 43210">
                                                </div>
                                                <div class="d-flex gap-2 mt-2">
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">Save Changes</button>
                                                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 border" onclick="document.getElementById('editExecForm_{{ $exec['model']->id }}').classList.add('d-none')">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Academic Deans Card -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4 mb-4" style="border-left: 5px solid #6366f1 !important;">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                        <i class="fas fa-graduation-cap text-indigo" style="color: #6366f1;"></i>
                        <span>Academic Deans & Leadership</span>
                        <span class="badge bg-indigo-subtle text-indigo px-3 py-1 rounded-pill small" style="font-size: 0.75rem;">Dean Council</span>
                    </h5>
                    
                    <div class="row g-4">
                        @php
                            $deans = $deansList;
                        @endphp
                        
                        @foreach($deans as $dean)
                            @if($dean['model'])
                                <div class="col-12 col-md-6 col-lg-6">
                                    <div class="p-3 bg-light rounded-4 border h-100 shadow-sm d-flex flex-column justify-content-between hover-grow">
                                        <div>
                                            <div class="d-flex align-items-start gap-3">
                                                <div class="avatar-circle text-white shadow-sm" style="width: 48px; height: 48px; font-size: 1.2rem; background: {{ $dean['color'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">
                                                    {{ strtoupper(substr($dean['model']->name, 0, 2)) }}
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-between gap-1 flex-wrap">
                                                        <span>{{ $dean['model']->name }}</span>
                                                        <div class="d-flex align-items-center gap-1">
                                                            <span class="badge bg-info-subtle text-info small" style="font-size: 0.65rem;">{{ $dean['title'] }}</span>
                                                            @if(in_array($currentUserRole, ['admin', 'dean']))
                                                                <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 0.75rem;" onclick="document.getElementById('editDeanForm_{{ $dean['model']->id }}').classList.toggle('d-none')">
                                                                    <i class="fas fa-edit me-1"></i> Edit
                                                                </button>
                                                            @endif
                                                        </div>
                                                    </h6>
                                                    <div class="text-secondary small mb-1"><i class="fas fa-envelope me-2 text-muted"></i>{{ $dean['model']->email }}</div>
                                                    <div class="text-secondary small mb-1"><i class="fas fa-phone me-2 text-muted"></i>{{ $dean['model']->phone ?? 'N/A' }}</div>
                                                    <div class="text-secondary small">
                                                        <i class="fas fa-sitemap me-2 text-muted"></i>
                                                        <strong>Scope:</strong> {{ $dean['model']->department ? ($dean['model']->department->name . ' (' . $dean['model']->department->code . ')') : 'Universal / University Level' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        @if(in_array($currentUserRole, ['admin', 'dean']))
                                        <!-- Inline Dean Edit Form -->
                                        <div id="editDeanForm_{{ $dean['model']->id }}" class="d-none mt-3 p-3 bg-white rounded border border-warning">
                                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-user-edit text-orange me-1" style="color: #ea580c;"></i> Edit Dean Contact</h6>
                                            <form method="POST" action="/admin/staff/{{ $dean['model']->id }}/update">
                                                @csrf
                                                <input type="hidden" name="role" value="{{ $dean['model']->role }}">
                                                <input type="hidden" name="unique_code" value="{{ $dean['model']->unique_code }}">
                                                <input type="hidden" name="department_id" value="{{ $dean['model']->department_id }}">
                                                
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-muted mb-1">Full Name</label>
                                                    <input type="text" name="name" class="form-control form-control-sm rounded" value="{{ $dean['model']->name }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-muted mb-1">Email Address</label>
                                                    <input type="email" name="email" class="form-control form-control-sm rounded" value="{{ $dean['model']->email }}" required>
                                                </div>
                                                <div class="mb-2">
                                                    <label class="form-label small fw-bold text-muted mb-1">Phone Number</label>
                                                    <input type="text" name="phone" class="form-control form-control-sm rounded" value="{{ $dean['model']->phone }}" placeholder="e.g. +91 98765 43210">
                                                </div>
                                                <div class="d-flex gap-2 mt-2">
                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">Save Changes</button>
                                                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 border" onclick="document.getElementById('editDeanForm_{{ $dean['model']->id }}').classList.add('d-none')">Cancel</button>
                                                </div>
                                            </form>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- University Directors & Board of Members -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4 mb-4" style="border-left: 5px solid #0f172a !important;">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                        <i class="fas fa-users-cog text-dark" style="color: #0f172a;"></i>
                        <span>University Directors & Board of Members</span>
                        <span class="badge bg-dark-subtle text-dark px-3 py-1 rounded-pill small" style="font-size: 0.75rem; background-color: rgba(15, 23, 42, 0.1);">Governance & Oversight</span>
                    </h5>
                    
                    <div class="row g-4">
                        @php
                            $govMembers = [];
                            foreach($directors as $dir) {
                                $govMembers[] = ['model' => $dir, 'title' => 'Director', 'color' => 'linear-gradient(135deg, #1e293b, #475569)'];
                            }
                            foreach($boardMembers as $bm) {
                                $govMembers[] = ['model' => $bm, 'title' => 'Board Member', 'color' => 'linear-gradient(135deg, #334155, #64748b)'];
                            }
                        @endphp
                        
                        @forelse($govMembers as $member)
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="p-3 bg-light rounded-4 border h-100 shadow-sm d-flex flex-column justify-content-between hover-grow">
                                    <div>
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="avatar-circle text-white shadow-sm" style="width: 48px; height: 48px; font-size: 1.2rem; background: {{ $member['color'] }}; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">
                                                {{ strtoupper(substr($member['model']->name, 0, 2)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-between gap-1 flex-wrap">
                                                    <span>{{ $member['model']->name }}</span>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="badge bg-dark text-white small" style="font-size: 0.65rem;">{{ $member['title'] }}</span>
                                                        @if(in_array($currentUserRole, ['admin', 'dean']))
                                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 0.75rem;" onclick="document.getElementById('editGovForm_{{ $member['model']->id }}').classList.toggle('d-none')">
                                                                <i class="fas fa-edit me-1"></i> Edit
                                                            </button>
                                                        @endif
                                                    </div>
                                                </h6>
                                                <div class="text-secondary small mb-1"><i class="fas fa-envelope me-2 text-muted"></i>{{ $member['model']->email }}</div>
                                                <div class="text-secondary small mb-1"><i class="fas fa-phone me-2 text-muted"></i>{{ $member['model']->phone ?? 'N/A' }}</div>
                                                @if(is_array($member['model']->positions) && count($member['model']->positions) > 0)
                                                    <div class="text-secondary small">
                                                        <i class="fas fa-tags me-2 text-muted"></i>
                                                        <strong>Positions:</strong> {{ implode(', ', $member['model']->positions) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(in_array($currentUserRole, ['admin', 'dean']))
                                    <!-- Inline Gov Member Edit Form -->
                                    <div id="editGovForm_{{ $member['model']->id }}" class="d-none mt-3 p-3 bg-white rounded border border-warning">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-user-edit text-orange me-1" style="color: #ea580c;"></i> Edit Member Contact</h6>
                                        <form method="POST" action="/admin/staff/{{ $member['model']->id }}/update">
                                            @csrf
                                            <input type="hidden" name="role" value="{{ $member['model']->role }}">
                                            <input type="hidden" name="unique_code" value="{{ $member['model']->unique_code }}">
                                            <input type="hidden" name="department_id" value="{{ $member['model']->department_id }}">
                                            
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Full Name</label>
                                                <input type="text" name="name" class="form-control form-control-sm rounded" value="{{ $member['model']->name }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Email Address</label>
                                                <input type="email" name="email" class="form-control form-control-sm rounded" value="{{ $member['model']->email }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Phone Number</label>
                                                <input type="text" name="phone" class="form-control form-control-sm rounded" value="{{ $member['model']->phone }}" placeholder="e.g. +91 98765 43210">
                                            </div>
                                            <div class="d-flex gap-2 mt-2">
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">Save Changes</button>
                                                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 border" onclick="document.getElementById('editGovForm_{{ $member['model']->id }}').classList.add('d-none')">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-3">
                                <span class="text-muted small">No Directors or Board Members currently registered.</span>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- External University & Company Coordinators -->
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4 mb-4" style="border-left: 5px solid #10b981 !important;">
                    <h5 class="fw-bold text-dark mb-4 d-flex align-items-center gap-2">
                        <i class="fas fa-handshake text-success" style="color: #10b981;"></i>
                        <span>External University & Company Coordinators</span>
                        <span class="badge bg-success-subtle text-success px-3 py-1 rounded-pill small" style="font-size: 0.75rem;">Industry Relations</span>
                    </h5>
                    
                    <div class="row g-4">
                        @forelse($externalCoordinators as $ext)
                            <div class="col-12 col-md-6 col-lg-6">
                                <div class="p-3 bg-light rounded-4 border h-100 shadow-sm d-flex flex-column justify-content-between hover-grow">
                                    <div>
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="avatar-circle text-white shadow-sm" style="width: 48px; height: 48px; font-size: 1.2rem; background: linear-gradient(135deg, #10b981, #059669); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">
                                                {{ strtoupper(substr($ext->name, 0, 2)) }}
                                            </div>
                                            <div class="flex-grow-1">
                                                <h6 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-between gap-1 flex-wrap">
                                                    <span>{{ $ext->name }}</span>
                                                    <div class="d-flex align-items-center gap-1">
                                                        <span class="badge bg-success text-white small" style="font-size: 0.65rem;">External Coordinator</span>
                                                        @if(in_array($currentUserRole, ['admin', 'dean']))
                                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 0.75rem;" onclick="document.getElementById('editExtForm_{{ $ext->id }}').classList.toggle('d-none')">
                                                                <i class="fas fa-edit me-1"></i> Edit
                                                            </button>
                                                        @endif
                                                    </div>
                                                </h6>
                                                <div class="text-secondary small mb-1"><i class="fas fa-envelope me-2 text-muted"></i>{{ $ext->email }}</div>
                                                <div class="text-secondary small mb-1"><i class="fas fa-phone me-2 text-muted"></i>{{ $ext->phone ?? 'N/A' }}</div>
                                                @if(is_array($ext->positions) && count($ext->positions) > 0)
                                                    <div class="text-secondary small">
                                                        <i class="fas fa-tags me-2 text-muted"></i>
                                                        <strong>Positions:</strong> {{ implode(', ', $ext->positions) }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @if(in_array($currentUserRole, ['admin', 'dean']))
                                    <!-- Inline External Edit Form -->
                                    <div id="editExtForm_{{ $ext->id }}" class="d-none mt-3 p-3 bg-white rounded border border-warning">
                                        <h6 class="fw-bold text-dark mb-2"><i class="fas fa-user-edit text-orange me-1" style="color: #ea580c;"></i> Edit Coordinator Contact</h6>
                                        <form method="POST" action="/admin/staff/{{ $ext->id }}/update">
                                            @csrf
                                            <input type="hidden" name="role" value="{{ $ext->role }}">
                                            <input type="hidden" name="unique_code" value="{{ $ext->unique_code }}">
                                            <input type="hidden" name="department_id" value="{{ $ext->department_id }}">
                                            
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Full Name</label>
                                                <input type="text" name="name" class="form-control form-control-sm rounded" value="{{ $ext->name }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Email Address</label>
                                                <input type="email" name="email" class="form-control form-control-sm rounded" value="{{ $ext->email }}" required>
                                            </div>
                                            <div class="mb-2">
                                                <label class="form-label small fw-bold text-muted mb-1">Phone Number</label>
                                                <input type="text" name="phone" class="form-control form-control-sm rounded" value="{{ $ext->phone }}" placeholder="e.g. +91 98765 43210">
                                            </div>
                                            <div class="d-flex gap-2 mt-2">
                                                <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">Save Changes</button>
                                                <button type="button" class="btn btn-sm btn-light rounded-pill px-3 border" onclick="document.getElementById('editExtForm_{{ $ext->id }}').classList.add('d-none')">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-3">
                                <span class="text-muted small">No External University/Company Coordinators currently registered.</span>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 2. DEPARTMENTS DIRECTORY PANE -->
            <div class="tab-pane fade" id="departments-pane" role="tabpanel" aria-labelledby="departments-tab">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4">
                    <h5 class="fw-bold text-dark mb-4"><i class="fas fa-building text-orange me-2" style="color: #ea580c;"></i> Academic Department Directory</h5>
                    
                    <div class="d-flex flex-column gap-4">
                        @foreach($departments as $d)
                        @php
                            $hods = \App\Models\Staff::where('role', 'hod')->where('department_id', $d->id)->get();
                        @endphp
                        
                        <div class="border rounded-4 p-4 shadow-sm position-relative overflow-hidden bg-light hover-shadow transition-all" style="border-left: 5px solid #ea580c !important;">
                            <div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2 flex-wrap gap-2">
                                <div>
                                    <h5 class="fw-bold text-dark mb-0 d-inline-flex align-items-center gap-2">
                                        {{ $d->name }} 
                                        <span class="badge bg-secondary-subtle text-secondary px-3 py-1 rounded-pill small" style="font-size: 0.75rem;">{{ $d->code }}</span>
                                    </h5>
                                    @if(in_array($currentUserRole, ['admin', 'dean']))
                                        <button class="btn btn-xs btn-outline-secondary rounded-pill px-2.5 ms-2 py-0.5" style="font-size: 0.75rem;" onclick="toggleEditDeptForm({{ $d->id }})">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        @if($d->code !== 'SCSET')
                                            <form method="POST" action="/admin/departments/{{ $d->id }}/delete" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this department? Associated staff will be unassigned.')">
                                                @csrf
                                                <button type="submit" class="btn btn-xs btn-outline-danger rounded-pill px-2.5 ms-1 py-0.5" style="font-size: 0.75rem;">
                                                    <i class="fas fa-trash me-1"></i> Delete
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                                <span class="small text-muted font-monospace">DEPT ID: #{{ $d->id }}</span>
                            </div>

                            <!-- Edit Department Collapse Panel -->
                            @if(in_array($currentUserRole, ['admin', 'dean']))
                            <div id="editDeptForm_{{ $d->id }}" class="d-none mb-4 p-4 bg-white rounded-4 border border-warning shadow-sm">
                                <h6 class="fw-bold text-dark mb-3"><i class="fas fa-edit text-orange me-1" style="color: #ea580c;"></i> Edit Department: {{ $d->name }}</h6>
                                <form method="POST" action="/admin/departments/{{ $d->id }}/update">
                                    @csrf
                                    <div class="row g-3 mb-3">
                                        <div class="col-12 col-md-8">
                                            <label class="form-label small fw-bold text-muted mb-1">Department Name</label>
                                            <input name="name" class="form-control rounded-3" value="{{ $d->name }}" required>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <label class="form-label small fw-bold text-muted mb-1">Code</label>
                                            <input name="code" class="form-control rounded-3" value="{{ $d->code }}" required>
                                        </div>
                                    </div>

                                    <div class="mb-3 border-top pt-3">
                                        <label class="form-label small fw-bold text-muted mb-2 d-block"><i class="fas fa-graduation-cap text-orange me-1" style="color: #ea580c;"></i> Academic Levels & Programs</label>
                                        
                                        @foreach(['diploma' => 'Diploma Programs', 'bachelors' => 'Bachelor\'s Programs (UG)', 'hons_bachelors' => 'Honours Bachelor\'s Programs (Hons)', 'masters' => 'Master\'s Programs (PG)', 'phd' => 'PhD Programs (Doctoral)'] as $levelKey => $levelLabel)
                                            @php
                                                $levelData = $d->branches[$levelKey] ?? [];
                                                $isEnabled = !empty($levelData);
                                            @endphp
                                            <div class="mb-3 border-bottom pb-2">
                                                <div class="form-check form-switch small mb-1">
                                                    <input class="form-check-input" type="checkbox" id="check_{{ $levelKey }}_{{ $d->id }}" name="programs[{{ $levelKey }}][enabled]" value="1" {{ $isEnabled ? 'checked' : '' }} onchange="toggleLevelContainer('{{ $levelKey }}', 'edit_{{ $d->id }}', this.checked)">
                                                    <label class="form-check-label fw-semibold text-dark" for="check_{{ $levelKey }}_{{ $d->id }}">{{ $levelLabel }}</label>
                                                </div>
                                                <div id="{{ $levelKey }}Container_edit_{{ $d->id }}" class="{{ $isEnabled ? '' : 'd-none' }} ms-2 ps-2 border-start border-2 border-orange-subtle mt-2">
                                                    <div class="program-list" id="{{ $levelKey }}List_edit_{{ $d->id }}"></div>
                                                    <button type="button" class="btn btn-xs btn-outline-orange rounded-pill mt-1" onclick="addProgramRow('{{ $levelKey }}', 'edit_{{ $d->id }}')">
                                                        <i class="fas fa-plus me-1"></i> Add Program
                                                    </button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    
                                    <div class="d-flex gap-2 justify-content-end">
                                        <button type="submit" class="btn btn-sm btn-success rounded-pill px-4 fw-bold">Save Changes</button>
                                        <button type="button" class="btn btn-sm btn-light border rounded-pill px-4" onclick="toggleEditDeptForm({{ $d->id }})">Cancel</button>
                                    </div>
                                </form>
                            </div>
                            @endif
                            
                            <div class="row g-4">
                                <div class="col-12 col-md-6">
                                    <div class="p-3 bg-white rounded-3 border h-100 shadow-sm">
                                        <span class="badge bg-warning-subtle text-warning mb-2 px-2 py-1" style="background-color: rgba(249, 115, 22, 0.1); color: #ea580c !important;"><i class="fas fa-user-tie me-1"></i> Department HOD(s)</span>
                                        @if($hods->count() > 0)
                                            <div class="row g-3">
                                                @foreach($hods as $hod)
                                                    <div class="col-12 col-md-6 border-bottom pb-2">
                                                        <div class="d-flex align-items-start gap-3">
                                                            <div class="avatar-circle text-white shadow-sm" style="width: 44px; height: 44px; font-size: 1.1rem; background: linear-gradient(135deg, #ea580c, #f97316); border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; flex-shrink: 0;">
                                                                {{ strtoupper(substr($hod->name, 0, 2)) }}
                                                             </div>
                                                            <div class="flex-grow-1">
                                                                <h6 class="fw-bold text-dark mb-1 d-flex align-items-center justify-content-between gap-1 flex-wrap">
                                                                    <span>{{ $hod->name }}</span>
                                                                    <div class="d-flex align-items-center gap-1">
                                                                        @if(is_array($hod->positions) && in_array('Primary HOD', $hod->positions))
                                                                            <span class="badge bg-success-subtle text-success small" style="font-size: 0.65rem;">Primary</span>
                                                                        @elseif(is_array($hod->positions) && in_array('Secondary HOD', $hod->positions))
                                                                            <span class="badge bg-info-subtle text-info small" style="font-size: 0.65rem;">Secondary</span>
                                                                        @else
                                                                            @if($loop->first)
                                                                                <span class="badge bg-success-subtle text-success small" style="font-size: 0.65rem;">Primary</span>
                                                                            @else
                                                                                <span class="badge bg-info-subtle text-info small" style="font-size: 0.65rem;">Secondary</span>
                                                                            @endif
                                                                        @endif
                                                                        
                                                                        @if(in_array($currentUserRole, ['admin', 'dean']))
                                                                            <button class="btn btn-sm btn-outline-secondary rounded-pill px-2 py-0" style="font-size: 0.75rem;" onclick="document.getElementById('editHodForm_{{ $hod->id }}_{{ $d->id }}').classList.toggle('d-none')">
                                                                                <i class="fas fa-edit me-1"></i> Edit
                                                                            </button>
                                                                        @endif
                                                                    </div>
                                                                </h6>
                                                                <div class="text-secondary small mb-1"><i class="fas fa-envelope me-2 text-muted"></i>{{ $hod->email }}</div>
                                                                <div class="text-secondary small"><i class="fas fa-phone me-2 text-muted"></i>{{ $hod->phone ?? 'N/A' }}</div>
                                                            </div>
                                                        </div>
                                                        
                                                        @if(in_array($currentUserRole, ['admin', 'dean']))
                                                        <!-- Inline HOD Edit Form -->
                                                        <div id="editHodForm_{{ $hod->id }}_{{ $d->id }}" class="d-none mt-3 p-3 bg-light rounded border border-warning">
                                                            <h6 class="fw-bold text-dark mb-2"><i class="fas fa-user-edit text-orange me-1" style="color: #ea580c;"></i> Edit HOD Contact</h6>
                                                            <form method="POST" action="/admin/staff/{{ $hod->id }}/update">
                                                                @csrf
                                                                <input type="hidden" name="role" value="{{ $hod->role }}">
                                                                <input type="hidden" name="unique_code" value="{{ $hod->unique_code }}">
                                                                <input type="hidden" name="department_id" value="{{ $hod->department_id }}">
                                                                
                                                                <div class="mb-2">
                                                                    <label class="form-label small fw-bold text-muted mb-1">Full Name</label>
                                                                    <input type="text" name="name" class="form-control form-control-sm rounded" value="{{ $hod->name }}" required>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label small fw-bold text-muted mb-1">Email Address</label>
                                                                    <input type="email" name="email" class="form-control form-control-sm rounded" value="{{ $hod->email }}" required>
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label small fw-bold text-muted mb-1">Phone Number</label>
                                                                    <input type="text" name="phone" class="form-control form-control-sm rounded" value="{{ $hod->phone }}" placeholder="e.g. +91 98765 43210">
                                                                </div>
                                                                <div class="mb-2">
                                                                    <label class="form-label small fw-bold text-muted mb-1">HOD Priority / Type</label>
                                                                    <select name="positions[]" class="form-select form-select-sm rounded">
                                                                        <option value="Primary HOD" {{ (is_array($hod->positions) && in_array('Primary HOD', $hod->positions)) || (!is_array($hod->positions) && $loop->first) ? 'selected' : '' }}>Primary HOD</option>
                                                                        <option value="Secondary HOD" {{ (is_array($hod->positions) && in_array('Secondary HOD', $hod->positions)) || (!is_array($hod->positions) && !$loop->first) ? 'selected' : '' }}>Secondary HOD</option>
                                                                    </select>
                                                                </div>
                                                                <div class="d-flex gap-2 mt-2">
                                                                    <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 fw-bold">Save Changes</button>
                                                                    <button type="button" class="btn btn-sm btn-light rounded-pill px-3 border" onclick="document.getElementById('editHodForm_{{ $hod->id }}_{{ $d->id }}').classList.add('d-none')">Cancel</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <div class="text-muted small py-2"><i class="fas fa-exclamation-circle me-1"></i> No HOD assigned to this department yet.</div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="p-3 bg-white rounded-3 border h-100 shadow-sm d-flex flex-column justify-content-between">
                                        <div>
                                            <span class="badge bg-indigo-subtle text-indigo mb-2 px-2 py-1" style="background-color: rgba(99, 102, 241, 0.1); color: #6366f1 !important;">
                                                <i class="fas fa-graduation-cap me-1"></i> Offered Programs & Branches
                                            </span>
                                            
                                            @if(!empty($d->branches))
                                                <div class="d-flex flex-column gap-3 mt-2">
                                                    @foreach($d->branches as $level => $programsOrList)
                                                        @if(!empty($programsOrList) && is_array($programsOrList))
                                                            @php
                                                                $levelIcon = 'fa-graduation-cap';
                                                                $levelColor = '#6366f1';
                                                                $levelTitle = ucfirst($level);
                                                                
                                                                if ($level === 'diploma') {
                                                                    $levelIcon = 'fa-id-card';
                                                                    $levelColor = '#ea580c';
                                                                    $levelTitle = 'Diploma Programs';
                                                                } elseif ($level === 'bachelors') {
                                                                    $levelIcon = 'fa-graduation-cap';
                                                                    $levelColor = '#3b82f6';
                                                                    $levelTitle = 'Bachelor\'s Programs (UG)';
                                                                } elseif ($level === 'hons_bachelors') {
                                                                    $levelIcon = 'fa-award';
                                                                    $levelColor = '#a855f7';
                                                                    $levelTitle = 'Honours Bachelor\'s Programs (Hons)';
                                                                } elseif ($level === 'masters') {
                                                                    $levelIcon = 'fa-book-reader';
                                                                    $levelColor = '#0d9488';
                                                                    $levelTitle = 'Master\'s Programs (PG)';
                                                                } elseif ($level === 'phd') {
                                                                    $levelIcon = 'fa-microscope';
                                                                    $levelColor = '#e11d48';
                                                                    $levelTitle = 'PhD Programs (Doctoral)';
                                                                }

                                                                $isStructured = false;
                                                                if (count($programsOrList) > 0) {
                                                                    $firstElement = reset($programsOrList);
                                                                    if (is_array($firstElement) && isset($firstElement['program'])) {
                                                                        $isStructured = true;
                                                                    }
                                                                }
                                                            @endphp

                                                            <div>
                                                                <div class="small fw-bold mb-1 d-flex align-items-center gap-1.5" style="color: {{ $levelColor }};">
                                                                    <i class="fas {{ $levelIcon }}"></i>
                                                                    <span>{{ $levelTitle }}</span>
                                                                </div>
                                                                
                                                                @if($isStructured)
                                                                    <div class="ps-3 d-flex flex-column gap-2 border-start border-1" style="border-color: rgba(0,0,0,0.08) !important;">
                                                                        @foreach($programsOrList as $progItem)
                                                                            <div class="bg-light p-2.5 rounded-3 border border-light shadow-2xs">
                                                                                <div class="d-flex justify-content-between align-items-start flex-wrap gap-1 mb-1">
                                                                                    <span class="small fw-bold text-dark"><i class="fas fa-caret-right text-muted me-1"></i>{{ $progItem['program'] }}</span>
                                                                                    
                                                                                    <div class="d-flex flex-wrap gap-1">
                                                                                        @if(!empty($progItem['heads']))
                                                                                            @foreach($progItem['heads'] as $headConfig)
                                                                                                @php
                                                                                                    $staffMember = $allStaff->firstWhere('id', $headConfig['staff_id']);
                                                                                                    $isTemp = ($headConfig['type'] ?? 'perm') === 'temp';
                                                                                                    $badgeStyle = $isTemp ? 'background-color: rgba(245, 158, 11, 0.1); color: #d97706 !important; border: 1px solid rgba(245, 158, 11, 0.2);' : 'background-color: rgba(16, 185, 129, 0.1); color: #059669 !important; border: 1px solid rgba(16, 185, 129, 0.2);';
                                                                                                    $roleLabel = $isTemp ? 'Temp Head' : 'Head';
                                                                                                @endphp
                                                                                                @if($staffMember)
                                                                                                    <span class="badge rounded-pill d-inline-flex align-items-center" style="font-size: 0.62rem; font-weight: 700; {{ $badgeStyle }}" title="{{ $staffMember->email }} | {{ $staffMember->phone ?? 'No Phone' }}">
                                                                                                        <i class="fas {{ $isTemp ? 'fa-hourglass-half' : 'fa-check-circle' }} me-0.5"></i>
                                                                                                        {{ $staffMember->name }} ({{ $roleLabel }})
                                                                                                    </span>
                                                                                                @endif
                                                                                            @endforeach
                                                                                        @else
                                                                                            <span class="badge bg-secondary-subtle text-secondary rounded-pill" style="font-size: 0.6rem;">No Head Assigned</span>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>
                                                                                
                                                                                <div class="d-flex flex-wrap gap-1 ps-2">
                                                                                    @if(!empty($progItem['branches']))
                                                                                        @foreach($progItem['branches'] as $branch)
                                                                                            <span class="badge bg-white text-dark border rounded-pill px-2 py-0.5" style="font-size: 0.68rem; font-weight: 600; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
                                                                                                {{ $branch }}
                                                                                            </span>
                                                                                        @endforeach
                                                                                    @else
                                                                                        <span class="text-muted small italic" style="font-size: 0.65rem;">General / No Specializations</span>
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                        @endforeach
                                                                    </div>
                                                                @else
                                                                    <div class="d-flex flex-wrap gap-1 ps-2">
                                                                        @foreach($programsOrList as $branch)
                                                                            <span class="badge bg-light text-dark border rounded-pill px-2.5 py-1" style="font-size: 0.72rem; font-weight: 600;">
                                                                                {{ $branch }}
                                                                            </span>
                                                                        @endforeach
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            @else
                                                <div class="text-muted small py-3 text-center">
                                                    <i class="fas fa-info-circle me-1"></i> No academic programs or branches configured yet.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- 3. AI SIGNATURE VERIFICATION PANE -->
            <div class="tab-pane fade" id="verification-pane" role="tabpanel" aria-labelledby="verification-tab">
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                        <div>
                            <h5 class="fw-bold text-dark mb-1"><i class="fas fa-shield-alt text-orange me-2" style="color: #ea580c;"></i> AI Verification & Sign-off Block</h5>
                            <p class="text-muted small mb-0">Select an academic department and program to generate a dynamically sealed signature block template.</p>
                        </div>
                        <button class="btn btn-sm btn-outline-dark rounded-pill px-3 fw-bold" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Print Slip
                        </button>
                    </div>

                    <div class="row g-4">
                        <div class="col-12 col-md-5">
                            <div class="d-flex flex-column gap-3">
                                <div>
                                    <label class="form-label small fw-bold text-muted mb-1">Select Academic Department</label>
                                    <select id="verificationDeptSelect" class="form-select rounded-3 p-2.5 font-monospace" style="border: 2px solid rgba(0,0,0,0.1); font-size: 0.9rem;">
                                        @foreach($departments as $d)
                                            <option value="{{ $d->id }}">{{ $d->name }} ({{ $d->code }})</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label small fw-bold text-muted mb-1">Select Academic Level</label>
                                    <select id="verificationLevelSelect" class="form-select rounded-3 p-2.5 font-monospace" style="border: 2px solid rgba(0,0,0,0.1); font-size: 0.9rem;">
                                        <option value="diploma">Diploma Programs</option>
                                        <option value="bachelors" selected>Bachelor's Programs (UG)</option>
                                        <option value="hons_bachelors">Honours Bachelor's Programs (Hons)</option>
                                        <option value="masters">Master's Programs (PG)</option>
                                        <option value="phd">PhD Programs (Doctoral)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label small fw-bold text-muted mb-1">Select Program / Degree</label>
                                    <select id="verificationProgSelect" class="form-select rounded-3 p-2.5 font-monospace" style="border: 2px solid rgba(0,0,0,0.1); font-size: 0.9rem;">
                                        <option value="">-- Select Program --</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label small fw-bold text-muted mb-1">Select Branch / Specialization</label>
                                    <select id="verificationBranchSelect" class="form-select rounded-3 p-2.5 font-monospace" style="border: 2px solid rgba(0,0,0,0.1); font-size: 0.9rem;">
                                        <option value="">-- Select Branch --</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="form-label small fw-bold text-muted mb-1">Select Signatory HOD</label>
                                    <select id="verificationSignatorySelect" class="form-select rounded-3 p-2.5 font-monospace" style="border: 2px solid rgba(0,0,0,0.1); font-size: 0.9rem;">
                                        <option value="">-- Select HOD/Signatory --</option>
                                    </select>
                                </div>

                                <div class="p-3 rounded-4 bg-light border" style="font-size: 0.85rem;">
                                    <div class="fw-bold text-dark mb-2"><i class="fas fa-microchip text-orange me-1" style="color: #ea580c;"></i> AI Ledger Details:</div>
                                    <p class="text-muted mb-2">This module dynamically aggregates database references for deans and HODs of the selected branch. The digital signatures are rendered in vector format (SVG) using secure hash-based styling.</p>
                                    <div class="d-flex align-items-center gap-2">
                                        <span class="badge bg-success-subtle text-success py-1 px-2" style="background-color: rgba(16, 185, 129, 0.1); color: #059669 !important; border: 1px solid rgba(16, 185, 129, 0.2);"><i class="fas fa-check-circle me-1"></i> Database Synced</span>
                                        <span class="badge bg-orange-subtle text-orange py-1 px-2" style="background-color: rgba(234, 88, 12, 0.1); color: #ea580c !important; border: 1px solid rgba(234, 88, 12, 0.2);"><i class="fas fa-signature me-1"></i> Vector SVG</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Printable Verification Slip Layout -->
                        <div class="col-12 col-md-7">
                            <div id="printable-verification-slip" class="p-4 rounded-4 bg-white shadow-sm border position-relative" style="border: 4px double #1e3a8a !important; background-image: radial-gradient(rgba(0, 0, 0, 0.015) 1px, transparent 0); background-size: 16px 16px;">
                                <!-- Elegant watermark badge -->
                                <div class="position-absolute opacity-10" style="right: 15px; top: 15px; font-size: 3rem; color: #ea580c; pointer-events: none;">
                                    <i class="fas fa-university"></i>
                                </div>

                                <!-- University Header -->
                                <div class="text-center border-bottom pb-2 mb-3">
                                    <div class="fw-extrabold text-uppercase letter-spacing-1 text-primary-custom" style="font-size: 0.95rem; font-weight: 800; color: #1e3a8a;">School Verification & Sign-off Register</div>
                                    <div class="text-muted font-monospace mt-1" id="slipHeaderHash" style="font-size: 0.72rem;">SEC-HASH: N/A</div>
                                </div>

                                <!-- Slip Body with 10 structured details -->
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle mb-3 small-table text-start" style="border-collapse: collapse; width: 100%; border: 1px solid #cbd5e1; font-size: 0.85rem;">
                                        <thead>
                                            <tr class="table-light text-uppercase font-monospace" style="background-color: #f8fafc; border-bottom: 2px solid #cbd5e1; font-size: 0.75rem;">
                                                <th style="width: 8%; text-align: center; font-weight: 700; border: 1px solid #cbd5e1; padding: 6px;">Ref</th>
                                                <th style="width: 32%; font-weight: 700; border: 1px solid #cbd5e1; padding: 6px;">Verification Detail</th>
                                                <th style="width: 60%; font-weight: 700; border: 1px solid #cbd5e1; padding: 6px;">Registered System Record</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[01]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">Issuing Institution</td>
                                                <td id="slipInstitution" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: 600; color: #1e293b;">ITM (SLS) Baroda University</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[02]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">Academic Department</td>
                                                <td id="slipDeptName" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: 600; color: #1e293b;">N/A</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[03]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">Academic Level</td>
                                                <td id="slipAcademicLevel" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: 600; color: #1e293b;">N/A</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[04]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">Program / Degree</td>
                                                <td id="slipProgramName" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: 600; color: #1e293b;">N/A</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[05]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">Branch / Specialization</td>
                                                <td id="slipBranchName" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: 600; color: #1e293b;">N/A</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[06]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">Dean of School</td>
                                                <td id="slipDeanName" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: 600; color: #1e293b;">N/A</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[07]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">Dean Contact Details</td>
                                                <td id="slipDeanContact" style="border: 1px solid #cbd5e1; padding: 6px; font-family: monospace; font-size: 0.75rem; color: #475569;">N/A</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[08]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">Program HOD / Signatory</td>
                                                <td id="slipHodName" style="border: 1px solid #cbd5e1; padding: 6px; font-weight: 600; color: #1e293b;">N/A</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[09]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">HOD Contact Details</td>
                                                <td id="slipHodContact" style="border: 1px solid #cbd5e1; padding: 6px; font-family: monospace; font-size: 0.75rem; color: #475569;">N/A</td>
                                            </tr>
                                            <tr>
                                                <td class="text-center font-monospace fw-bold" style="border: 1px solid #cbd5e1; padding: 6px; color: #ea580c;">[10]</td>
                                                <td class="fw-bold text-secondary" style="border: 1px solid #cbd5e1; padding: 6px;">Ledger Security Hash</td>
                                                <td id="slipHash" style="border: 1px solid #cbd5e1; padding: 6px; font-family: monospace; font-weight: 700; color: #1e3a8a; font-size: 0.8rem;">N/A</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Side-by-Side Signatures -->
                                <div class="row text-center mt-3">
                                    <!-- Dean Sign -->
                                    <div class="col-6">
                                        <div class="d-flex flex-column align-items-center">
                                            <div id="slipDeanSignature" class="mb-1 d-flex align-items-center justify-content-center" style="height: 48px; width: 140px; overflow: hidden;">
                                                <!-- Dynamic Signature -->
                                            </div>
                                            <div class="text-secondary small font-monospace" style="letter-spacing: 1px; opacity: 0.5;">..........................................</div>
                                            <div class="fw-bold small text-dark mt-1">Signature of Dean</div>
                                            <div class="text-muted" style="font-size: 0.62rem;" id="slipDeanDeptSubtext">School of Computer Science Engineering & Technology</div>
                                        </div>
                                    </div>

                                    <!-- HOD Sign -->
                                    <div class="col-6">
                                        <div class="d-flex flex-column align-items-center">
                                            <div id="slipHodSignature" class="mb-1 d-flex align-items-center justify-content-center" style="height: 48px; width: 140px; overflow: hidden;">
                                                <!-- Dynamic Signature -->
                                            </div>
                                            <div class="text-secondary small font-monospace" style="letter-spacing: 1px; opacity: 0.5;">..........................................</div>
                                            <div id="slipHodLabel" class="fw-bold small text-dark mt-1" style="transition: color 0.2s ease;">Signature of HOD (Permanent)</div>
                                            <div class="text-muted" style="font-size: 0.62rem;" id="slipHodBranchSubtext">According to Branchvice</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Dynamic Verification Slip Javascript & Dynamic CRUD Forms JS -->
<script>
    // Global data representation
    const allStaffForDropdown = [
        @foreach($allStaff as $staff)
            { id: "{{ $staff->id }}", name: "{{ e($staff->name) }}", role: "{{ e($staff->role) }}" },
        @endforeach
    ];

    const mainDeanData = {
        name: "{{ $mainDean ? e($mainDean->name) : 'Dr.Prof. Dr. Pradeep Laxkar(Main Dean(Already Exist)) (Dual PHD)' }}",
        email: "{{ $mainDean ? e($mainDean->email) : 'pradeep.laxkar.dean@itmbu.ac.in' }}",
        phone: "{{ $mainDean && $mainDean->phone ? e($mainDean->phone) : 'N/A' }}",
        signature: `{!! $mainDean ? $mainDean->digital_signature : '' !!}`
    };

    const deptsData = {
        @foreach($departments as $d)
            "{{ $d->id }}": {
                name: "{{ e($d->name) }}",
                code: "{{ e($d->code) }}",
                branches: {!! json_encode($d->branches ?? []) !!},
                hods: [
                    @php
                        $deptHods = \App\Models\Staff::where('department_id', $d->id)->get();
                    @endphp
                    @foreach($deptHods as $h)
                        {
                            id: "{{ $h->id }}",
                            name: "{{ e($h->name) }}",
                            email: "{{ e($h->email) }}",
                            phone: "{{ $h->phone ?? 'N/A' }}",
                            role: "{{ e($h->role) }}",
                            positions: {!! json_encode($h->positions ?? []) !!},
                            digital_signature: `{!! $h->digital_signature !!}`
                        },
                    @endforeach
                ]
            },
        @endforeach
    };

    let programCounter = 0;
    let hodCounter = 0;

    // Helper functions for dynamic department CRUD
    function toggleLevelContainer(level, context, checked) {
        const container = document.getElementById(`${level}Container_${context}`);
        if (container) {
            container.classList.toggle('d-none', !checked);
            if (checked) {
                const list = document.getElementById(`${level}List_${context}`);
                if (list && list.children.length === 0) {
                    addProgramRow(level, context);
                }
            }
        }
    }

    function addProgramRow(level, context, existingData = null) {
        const list = document.getElementById(`${level}List_${context}`);
        if (!list) return;

        const pIdx = programCounter++;
        
        const card = document.createElement('div');
        card.className = 'card p-3 mb-3 border border-dashed rounded-3 bg-light position-relative';
        card.style.borderColor = '#ea580c';
        card.setAttribute('data-program-index', pIdx);

        // Render card inputs
        card.innerHTML = `
            <button type="button" class="btn-close position-absolute top-0 end-0 m-2" style="font-size: 0.7rem;" onclick="this.closest('.card').remove()"></button>
            <div class="mb-2">
                <label class="form-label small fw-bold text-muted mb-1">Program/Degree Name</label>
                <input type="text" name="programs[${level}][programs][${pIdx}][program]" class="form-control form-control-sm rounded" placeholder="e.g. B.Tech, BCA" value="${existingData ? escapeHtml(existingData.program) : ''}" required>
            </div>
            <div class="mb-2">
                <label class="form-label small fw-bold text-muted mb-1">Specializations / Branches (Comma-separated)</label>
                <input type="text" name="programs[${level}][programs][${pIdx}][branches]" class="form-control form-control-sm rounded" placeholder="e.g. CSE, CSN, IT" value="${existingData ? escapeHtml(existingData.branches.join(', ')) : ''}">
            </div>
            <div class="hod-section mt-2 border-top pt-2">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small fw-bold text-muted">Assigned HODs / Program Heads</span>
                    <button type="button" class="btn btn-xs btn-outline-success py-0 px-2 rounded-pill" style="font-size: 0.65rem;" onclick="addHodRow('${level}', ${pIdx}, this, '${context}')">
                        <i class="fas fa-plus"></i> Add HOD
                    </button>
                </div>
                <div class="hod-list-container"></div>
            </div>
        `;

        list.appendChild(card);

        // Populate existing HODs if any
        if (existingData && existingData.heads && existingData.heads.length > 0) {
            const containerBtn = card.querySelector('.hod-section button');
            existingData.heads.forEach(head => {
                addHodRow(level, pIdx, containerBtn, context, head);
            });
        } else {
            const containerBtn = card.querySelector('.hod-section button');
            addHodRow(level, pIdx, containerBtn, context);
        }
    }

    function addHodRow(level, pIdx, button, context, existingData = null) {
        const hodListContainer = button.closest('.hod-section').querySelector('.hod-list-container');
        if (!hodListContainer) return;

        const hIdx = hodCounter++;
        const div = document.createElement('div');
        div.className = 'd-flex align-items-center gap-1 mb-1';

        // Build staff options
        let staffOptions = '<option value="">-- Select HOD/Faculty --</option>';
        allStaffForDropdown.forEach(staff => {
            const selected = (existingData && existingData.staff_id == staff.id) ? 'selected' : '';
            staffOptions += `<option value="${staff.id}" ${selected}>${escapeHtml(staff.name)} (${escapeHtml(staff.role)})</option>`;
        });

        div.innerHTML = `
            <select name="programs[${level}][programs][${pIdx}][heads][${hIdx}][staff_id]" class="form-select form-select-sm rounded" style="flex: 2; font-size: 0.75rem;" required>
                ${staffOptions}
            </select>
            <select name="programs[${level}][programs][${pIdx}][heads][${hIdx}][type]" class="form-select form-select-sm rounded" style="flex: 1; font-size: 0.75rem;">
                <option value="perm" ${(existingData && existingData.type === 'perm') ? 'selected' : ''}>PERM HOD</option>
                <option value="temp" ${(existingData && existingData.type === 'temp') ? 'selected' : ''}>TEMP HOD</option>
            </select>
            <button type="button" class="btn btn-sm btn-outline-danger px-1 py-0" style="font-size: 0.75rem;" onclick="this.closest('.d-flex').remove()">
                <i class="fas fa-trash"></i>
            </button>
        `;

        hodListContainer.appendChild(div);
    }

    function toggleEditDeptForm(id) {
        const formDiv = document.getElementById(`editDeptForm_${id}`);
        if (formDiv) {
            formDiv.classList.toggle('d-none');
        }
    }

    function escapeHtml(text) {
        if (!text) return '';
        return String(text)
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }

    // Dynamic Verification Ledger Selection script
    document.addEventListener('DOMContentLoaded', function() {
        const deptSelect = document.getElementById('verificationDeptSelect');
        const levelSelect = document.getElementById('verificationLevelSelect');
        const progSelect = document.getElementById('verificationProgSelect');
        const branchSelect = document.getElementById('verificationBranchSelect');
        const signatorySelect = document.getElementById('verificationSignatorySelect');

        function populateLevels() {
            populatePrograms();
        }

        function populatePrograms() {
            progSelect.innerHTML = '<option value="">-- Select Program --</option>';
            branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
            signatorySelect.innerHTML = '<option value="">-- Select HOD/Signatory --</option>';

            const deptId = deptSelect.value;
            const level = levelSelect.value;

            if (!deptId || !level || !deptsData[deptId]) return;

            const branchesObj = deptsData[deptId].branches;
            const levelPrograms = branchesObj[level] || [];

            levelPrograms.forEach((progItem, index) => {
                const opt = document.createElement('option');
                opt.value = index;
                opt.textContent = progItem.program;
                progSelect.appendChild(opt);
            });
            
            if (levelPrograms.length > 0) {
                progSelect.value = 0;
                populateBranches();
            } else {
                updateSlip();
            }
        }

        function populateBranches() {
            branchSelect.innerHTML = '<option value="">-- Select Branch --</option>';
            signatorySelect.innerHTML = '<option value="">-- Select HOD/Signatory --</option>';

            const deptId = deptSelect.value;
            const level = levelSelect.value;
            const progIndex = progSelect.value;

            if (!deptId || !level || progIndex === "" || !deptsData[deptId]) return;

            const progItem = deptsData[deptId].branches[level][progIndex];
            const branches = progItem.branches || [];

            branches.forEach(branch => {
                const opt = document.createElement('option');
                opt.value = branch;
                opt.textContent = branch;
                branchSelect.appendChild(opt);
            });

            if (branches.length > 0) {
                branchSelect.value = branches[0];
            }

            populateSignatories();
        }

        function populateSignatories() {
            signatorySelect.innerHTML = '<option value="">-- Select HOD/Signatory --</option>';

            const deptId = deptSelect.value;
            const level = levelSelect.value;
            const progIndex = progSelect.value;

            if (!deptId || !deptsData[deptId]) return;

            const dept = deptsData[deptId];
            let assignedHeads = [];

            if (level && progIndex !== "") {
                const progItem = dept.branches[level][progIndex];
                assignedHeads = progItem.heads || [];
            }

            // Populate from assigned HODs
            if (assignedHeads.length > 0) {
                assignedHeads.forEach(headRef => {
                    const staff = dept.hods.find(h => h.id == headRef.staff_id) || allStaffForDropdown.find(s => s.id == headRef.staff_id);
                    if (staff) {
                        const opt = document.createElement('option');
                        opt.value = staff.id;
                        opt.setAttribute('data-type', headRef.type);
                        const labelType = headRef.type === 'temp' ? 'Temporary HOD' : 'Permanent HOD';
                        opt.textContent = `${staff.name} (${labelType})`;
                        signatorySelect.appendChild(opt);
                    }
                });
            }

            // Fallback: list all department HODs if no assigned heads
            if (signatorySelect.options.length <= 1) {
                dept.hods.forEach(staff => {
                    const opt = document.createElement('option');
                    opt.value = staff.id;
                    opt.setAttribute('data-type', 'perm');
                    opt.textContent = `${staff.name} (HOD)`;
                    signatorySelect.appendChild(opt);
                });
            }

            if (signatorySelect.options.length > 1) {
                signatorySelect.selectedIndex = 1;
            }

            updateSlip();
        }

        function updateSlip() {
            if (!deptSelect) return;
            const deptId = deptSelect.value;
            if (!deptId || !deptsData[deptId]) return;

            const dept = deptsData[deptId];
            const level = levelSelect.value;
            const progIndex = progSelect.value;
            const branch = branchSelect.value;
            const signatoryId = signatorySelect.value;

            // Slip department name
            const deptText = dept.name + ' (' + dept.code + ')';
            document.getElementById('slipDeptName').innerText = deptText;
            
            // Slip Academic Level
            const levelText = levelSelect.options[levelSelect.selectedIndex].text;
            document.getElementById('slipAcademicLevel').innerText = levelText;
            
            // Slip program & branch
            const programText = (progIndex !== "" && dept.branches[level] && dept.branches[level][progIndex]) ? dept.branches[level][progIndex].program : 'N/A';
            document.getElementById('slipProgramName').innerText = programText;
            document.getElementById('slipBranchName').innerText = branch ? branch : 'N/A';

            // Slip Dean Details
            document.getElementById('slipDeanName').innerText = mainDeanData.name;
            document.getElementById('slipDeanContact').innerText = `Email: ${mainDeanData.email} | Phone: ${mainDeanData.phone}`;
            document.getElementById('slipDeanSignature').innerHTML = mainDeanData.signature;
            document.getElementById('slipDeanDeptSubtext').innerText = dept.name;

            // Slip HOD / Signatory Details
            let selectedStaff = null;
            let signatoryType = 'perm';

            if (signatoryId) {
                selectedStaff = dept.hods.find(h => h.id == signatoryId) || allStaffForDropdown.find(s => s.id == signatoryId);
                const activeOption = signatorySelect.options[signatorySelect.selectedIndex];
                if (activeOption) {
                    signatoryType = activeOption.getAttribute('data-type') || 'perm';
                }
            }

            const labelEl = document.getElementById('slipHodLabel');
            const subtextEl = document.getElementById('slipHodBranchSubtext');
            const branchSubtext = branch ? `${programText} (${branch})` : programText;
            subtextEl.innerText = branchSubtext;
            
            if (selectedStaff) {
                document.getElementById('slipHodName').innerText = selectedStaff.name;
                document.getElementById('slipHodContact').innerText = `Email: ${selectedStaff.email || 'N/A'} | Phone: ${selectedStaff.phone || 'N/A'}`;
                
                if (selectedStaff.digital_signature) {
                    document.getElementById('slipHodSignature').innerHTML = selectedStaff.digital_signature;
                } else {
                    const testSig = dept.hods.find(h => h.id == selectedStaff.id)?.digital_signature;
                    document.getElementById('slipHodSignature').innerHTML = testSig ? testSig : '<span class="text-muted small">No signature available</span>';
                }

                if (signatoryType === 'temp') {
                    labelEl.innerText = 'Signature of HOD (Temporary / Acting)';
                    labelEl.style.color = '#d97706';
                } else {
                    labelEl.innerText = 'Signature of HOD (Permanent)';
                    labelEl.style.color = '#059669';
                }
            } else {
                document.getElementById('slipHodName').innerText = 'No HOD Assigned';
                document.getElementById('slipHodContact').innerText = 'Email: N/A | Phone: N/A';
                document.getElementById('slipHodSignature').innerHTML = '<span class="text-muted small">No signature available</span>';
                labelEl.innerText = 'Signature of HOD';
                labelEl.style.color = '#374151';
            }

            // Generate Verification Security Hash
            const secString = dept.name + level + (branch || '');
            const hash = tempMd5(secString).toUpperCase();
            document.getElementById('slipHash').innerText = hash;
            document.getElementById('slipHeaderHash').innerText = 'SEC-HASH: ' + hash;
        }

        function tempMd5(string) {
            let hash = 0;
            if (string.length == 0) return '0000000000000000';
            for (let i = 0; i < string.length; i++) {
                const char = string.charCodeAt(i);
                hash = ((hash << 5) - hash) + char;
                hash = hash & hash;
            }
            const absHash = Math.abs(hash).toString(16);
            return (absHash + '89abcdef01234567').substring(0, 16);
        }

        if (deptSelect) {
            deptSelect.addEventListener('change', populateLevels);
            levelSelect.addEventListener('change', populatePrograms);
            progSelect.addEventListener('change', populateBranches);
            branchSelect.addEventListener('change', populateSignatories);
            signatorySelect.addEventListener('change', updateSlip);
            
            populateLevels();
        }

        // Initialize Edit Forms dynamically with their database programs
        @foreach($departments as $d)
            @if(!empty($d->branches))
                @foreach($d->branches as $levelKey => $progs)
                    @if(is_array($progs))
                        @foreach($progs as $prog)
                            @php
                                $cleanProg = [
                                    'program' => $prog['program'] ?? '',
                                    'branches' => $prog['branches'] ?? [],
                                    'heads' => $prog['heads'] ?? []
                                ];
                            @endphp
                            addProgramRow("{{ $levelKey }}", "edit_{{ $d->id }}", {!! json_encode($cleanProg) !!});
                        @endforeach
                    @endif
                @endforeach
            @endif
        @endforeach
    });
</script>

<style>
    .hover-grow {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
    }
    .hover-grow:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0,0,0,0.08) !important;
    }
    .text-secondary-custom {
        color: #4b5563;
    }
    .text-secondary-custom.active {
        background: white !important;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .nav-pills .nav-link.active {
        background-color: white !important;
        color: #1e3a8a !important;
        border: 1px solid rgba(0,0,0,0.08);
    }
    .nav-pills .nav-link {
        color: #4b5563 !important;
    }
    .transition-all {
        transition: all 0.2s ease-in-out;
    }

    .btn-xs {
        padding: 0.125rem 0.4rem;
        font-size: 0.72rem;
        line-height: 1.2;
        border-radius: 0.2rem;
    }

    .btn-outline-orange {
        color: #ea580c;
        border-color: #ea580c;
    }
    .btn-outline-orange:hover {
        background-color: #ea580c;
        color: white;
    }

    /* Print styling to strictly output only the verification slip when printing in that mode */
    @media print {
        @page {
            size: A4 portrait;
            margin: 10mm 15mm 15mm 15mm;
        }
        body * {
            visibility: hidden;
        }
        #printable-verification-slip, #printable-verification-slip * {
            visibility: visible;
        }
        #printable-verification-slip {
            position: fixed;
            left: 5%;
            top: 0;
            width: 90%;
            max-width: 180mm;
            height: auto;
            border: 4px double #1e3a8a !important;
            box-shadow: none !important;
            background: white !important;
            padding: 25px !important;
            margin: 0 auto !important;
            box-sizing: border-box;
            z-index: 9999;
            background-image: none !important; /* Hide subtle grid background in print for speed & crispness */
        }
        .small-table th, .small-table td {
            padding: 6px 10px !important;
            font-size: 0.85rem !important;
            border: 1px solid #94a3b8 !important;
        }
    }
</style>

    </div>
</div>

<style>
    .hover-shadow:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important;
    }
</style>

@endsection
