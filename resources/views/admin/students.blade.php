@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="fas fa-user-graduate me-2"></i> Student Registration & Management</h3>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

<div class="row g-4">
    <!-- Registration Form -->
    <div class="col-lg-4">
        <h5 class="fw-bold mb-3">Register New Student</h5>
        
        @if(session('success'))
            <div class="alert alert-success py-2 small fw-bold shadow-sm">{{ session('success') }}</div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2 small fw-bold shadow-sm">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <div class="glass-card p-4 border-0 shadow-sm">
            <form action="/admin/students" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="John Doe">
                </div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="john@university.edu">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Enrollment Number</label>
                    <input type="text" name="enrollment_no" class="form-control" required placeholder="e.g. 210xxxx">
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">Phone Number</label>
                        <input type="text" name="phone" class="form-control" required placeholder="10 Digits">
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">ABC Card ID</label>
                        <input type="text" name="abc_card_id" class="form-control" required placeholder="ABC-XXXX">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Initial Password</label>
                    <input type="text" name="password" class="form-control" value="password123" required>
                    <small class="text-muted d-block mt-1">Default password. Students can change this later.</small>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">Department</label>
                        <select name="department_id" class="form-select">
                            <option value="">-- General --</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-6">
                        <label class="form-label small fw-bold text-muted">Program</label>
                        <select name="program" class="form-select" required>
                            <option value="Diploma">Diploma</option>
                            <option value="Bachelors" selected>Bachelors</option>
                            <option value="Masters">Masters</option>
                        </select>
                    </div>
                </div>

                <div class="row g-2 mb-3">
                    <div class="col-4">
                        <label class="form-label small fw-bold text-muted">Year</label>
                        <input type="number" name="year" class="form-control" value="1" min="1" max="4" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label small fw-bold text-muted">Semester</label>
                        <input type="number" name="semester" class="form-control" value="1" min="1" max="8" required>
                    </div>
                    <div class="col-4">
                        <label class="form-label small fw-bold text-muted">Division/Branch</label>
                        <input type="text" name="class_section" class="form-control" placeholder="e.g. Class 01" required>
                    </div>
                </div>

                <div class="p-3 bg-light rounded-3 border mb-4">
                    <span class="small text-muted d-block fw-bold mb-2"><i class="fas fa-bed text-primary me-1"></i> BAPS Hostel Details (Optional)</span>
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted" style="font-size: 0.72rem;">Room Number</label>
                            <input type="text" name="hostel_room_no" class="form-control" placeholder="e.g. A-101">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted" style="font-size: 0.72rem;">Hostel Warden / Swami</label>
                            <select name="hostel_swami_id" class="form-select">
                                <option value="">-- None / Outside --</option>
                                @foreach($wardens as $w)
                                    <option value="{{ $w->id }}">{{ $w->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold shadow-sm rounded-pill">
                    <i class="fas fa-user-plus me-1"></i> Confirm Registration
                </button>
            </form>
            
        </div>
    </div>

    <!-- Student List / Management -->
    <div class="col-lg-8">
        <!-- Roster Analytics Quick Stats -->
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="glass-card p-3 border-0 shadow-sm d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.9); border-left: 4px solid #4f46e5 !important;">
                    <div class="p-3 bg-primary-subtle rounded-3 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-users fs-4"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold uppercase tracking-wider" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Total Registered</div>
                        <h4 class="mb-0 fw-extrabold text-dark">{{ $students->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-3 border-0 shadow-sm d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.9); border-left: 4px solid #22c55e !important;">
                    <div class="p-3 bg-success-subtle rounded-3 text-success d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-check-circle fs-4"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold uppercase tracking-wider" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Verified Identities</div>
                        <h4 class="mb-0 fw-extrabold text-dark">{{ $students->where('is_verified', true)->count() }}</h4>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="glass-card p-3 border-0 shadow-sm d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.9); border-left: 4px solid #f97316 !important;">
                    <div class="p-3 bg-warning-subtle rounded-3 text-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                        <i class="fas fa-trophy fs-4 text-warning"></i>
                    </div>
                    <div>
                        <div class="small text-muted fw-semibold uppercase tracking-wider" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Elite Achievers</div>
                        <h4 class="mb-0 fw-extrabold text-dark">{{ $students->where('level', '>', 1)->count() }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Registered Students Roster</h5>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small" id="rosterFilteredCount">Showing {{ $students->count() }} students</span>
        </div>

        <style>
            .table-cards {
                border-collapse: separate !important;
                border-spacing: 0 12px !important;
                background: transparent !important;
                width: 100% !important;
            }
            .table-cards thead th {
                border: none !important;
                color: #475569 !important;
                font-weight: 700 !important;
                font-size: 0.75rem !important;
                text-transform: uppercase !important;
                letter-spacing: 1px !important;
                padding-bottom: 12px !important;
                background: transparent !important;
            }
            .table-cards tbody tr {
                background: #ffffff !important;
                box-shadow: 0 4px 15px -3px rgba(0, 0, 0, 0.04), 0 2px 6px -2px rgba(0, 0, 0, 0.02) !important;
                border-radius: 14px !important;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
                position: relative;
            }
            .table-cards tbody tr:hover {
                transform: translateY(-3px) scale(1.005) !important;
                box-shadow: 0 10px 25px -5px rgba(99, 102, 241, 0.08), 0 8px 16px -6px rgba(99, 102, 241, 0.05) !important;
                background: #ffffff !important;
            }
            .table-cards tbody td {
                border: none !important;
                padding-top: 18px !important;
                padding-bottom: 18px !important;
                background: transparent !important;
            }
            .table-cards tbody tr td:first-child {
                border-top-left-radius: 14px !important;
                border-bottom-left-radius: 14px !important;
                border-left: 4px solid #cbd5e1 !important;
                transition: border-color 0.25s ease !important;
            }
            .table-cards tbody tr.is-verified-row td:first-child {
                border-left-color: #22c55e !important;
            }
            .table-cards tbody tr.student-row td:first-child:not(.is-verified-row) {
                border-left-color: #6366f1 !important;
            }
            .table-cards tbody tr td:last-child {
                border-top-right-radius: 14px !important;
                border-bottom-right-radius: 14px !important;
            }
            .badge-enrollment {
                background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%) !important;
                color: #334155 !important;
                border: 1px solid #e2e8f0 !important;
                font-family: 'SFMono-Regular', Consolas, monospace !important;
                font-size: 0.85rem !important;
                font-weight: 700 !important;
                box-shadow: inset 0 1px 2px rgba(0,0,0,0.02) !important;
                border-radius: 8px !important;
                padding: 6px 12px !important;
            }
            
            .badge-level {
                background: rgba(249, 115, 22, 0.08) !important;
                color: #ea580c !important;
                border: 1px solid rgba(249, 115, 22, 0.15) !important;
                font-size: 0.8rem !important;
                font-weight: 700 !important;
                border-radius: 8px !important;
                padding: 4px 10px !important;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }
            .badge-xp {
                background: rgba(99, 102, 241, 0.08) !important;
                color: #4f46e5 !important;
                border: 1px solid rgba(99, 102, 241, 0.15) !important;
                font-size: 0.8rem !important;
                font-weight: 700 !important;
                border-radius: 8px !important;
                padding: 4px 10px !important;
                display: inline-flex;
                align-items: center;
                gap: 4px;
            }
            
            .avatar-container {
                position: relative;
                width: 48px;
                height: 48px;
                border-radius: 50%;
                padding: 2px;
                background: linear-gradient(135deg, #f97316 0%, #6366f1 100%);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
                transition: transform 0.25s ease !important;
            }
            .avatar-container:hover {
                transform: scale(1.08) rotate(3deg) !important;
            }
            .avatar-img-wrapper {
                width: 100%;
                height: 100%;
                border-radius: 50%;
                overflow: hidden;
                border: 2px solid #ffffff;
                background: #ffffff;
            }
            .avatar-img-wrapper img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }
            
            .btn-actions-dropdown {
                background: #ffffff !important;
                border: 1px solid #e2e8f0 !important;
                border-radius: 30px !important;
                padding: 6px 16px !important;
                font-size: 0.85rem !important;
                font-weight: 600 !important;
                color: #475569 !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.02) !important;
                transition: all 0.2s ease !important;
            }
            .btn-actions-dropdown:hover {
                background: #f8fafc !important;
                border-color: #cbd5e1 !important;
                color: #1e293b !important;
                box-shadow: 0 4px 12px rgba(0,0,0,0.05) !important;
                transform: translateY(-1px) !important;
            }
            .dropdown-menu {
                border-radius: 12px !important;
                border: 1px solid rgba(0,0,0,0.05) !important;
                box-shadow: 0 10px 30px rgba(0,0,0,0.1) !important;
                padding: 6px !important;
                animation: fadeInDropdown 0.2s ease-out;
            }
            .dropdown-item {
                border-radius: 8px !important;
                font-weight: 550 !important;
                font-size: 0.85rem !important;
                color: #475569 !important;
                transition: all 0.15s ease !important;
                padding: 8px 14px !important;
            }
            .dropdown-item:hover {
                background-color: #f1f5f9 !important;
                color: #1e293b !important;
            }
            .dropdown-item.text-danger:hover {
                background-color: rgba(239, 68, 68, 0.08) !important;
                color: #dc2626 !important;
            }
            @keyframes fadeInDropdown {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
            .form-control:focus, .form-select:focus {
                border-color: #6366f1 !important;
                box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.15) !important;
            }
            .actions-collapse-row { transition: all 0.3s ease !important; }
            .actions-collapse-row:not(.show-row) { display: none !important; }
            .actions-collapse-row.show-row { display: table-row !important; }
            .hover-shadow:hover {
                background-color: #ede9fe !important;
                border-color: #6366f1 !important;
                box-shadow: 0 4px 12px rgba(99, 102, 241, 0.08) !important;
                transform: translateY(-1px);
            }
        </style>

        <!-- Roster Search & Filter Utilities -->
        <div class="glass-card p-3 border-0 shadow-sm mb-4" style="background: rgba(255, 255, 255, 0.9);">
            <div class="row g-2 align-items-center">
                <!-- Search Input -->
                <div class="col-md-6">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" id="rosterSearch" class="form-control ps-5 border-light-subtle rounded-3" placeholder="Search by name, email, enrollment, abc card..." style="border-radius: 10px !important;">
                    </div>
                </div>
                <!-- Department Filter -->
                <div class="col-md-3">
                    <select id="filterDepartment" class="form-select border-light-subtle" style="border-radius: 10px !important;">
                        <option value="">All Departments</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->name }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Level Filter -->
                <div class="col-md-3">
                    <select id="filterLevel" class="form-select border-light-subtle" style="border-radius: 10px !important;">
                        <option value="">All Levels</option>
                        <option value="1">Level 1 Only</option>
                        <option value="2">Level 2 and above</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="border-0 p-0 shadow-none text-nowrap bg-transparent">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover table-cards mb-0 align-middle">
                    <thead class="text-muted">
                        <tr>
                            <th class="ps-4"><i class="fas fa-id-badge text-primary me-1"></i> ID / Name</th>
                            <th><i class="fas fa-graduation-cap text-success me-1"></i> Enrollment No.</th>
                            <th class="text-center"><i class="fas fa-address-book text-warning me-1"></i> Contact</th>
                            <th><i class="fas fa-chart-line text-info me-1"></i> Metrics</th>
                            <th class="text-end pe-4"><i class="fas fa-sliders-h text-secondary me-1"></i> Management Actions</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @foreach($students as $user)
                        <tr class="student-row @if($user->is_verified) is-verified-row @endif" 
                            id="studentRow{{ $user->id }}"
                            data-name="{{ strtolower($user->name) }}" 
                            data-email="{{ strtolower($user->email) }}" 
                            data-enrollment="{{ strtolower($user->enrollment_no ?? '') }}" 
                            data-abc="{{ strtolower($user->abc_card_id ?? '') }}"
                            data-department="{{ $user->department->name ?? '' }}" 
                            data-level="{{ $user->level }}">
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-container me-3">
                                        <div class="avatar-img-wrapper">
                                            <img id="studentAvatar{{ $user->id }}" 
                                                 src="{{ ($user->profile_photo_data || $user->profile_photo) ? url('/profile/photo/user/' . $user->id) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&background=6366f1&color=fff&size=50' }}" 
                                                 title="Admin: Click to upload student photo"
                                                 onclick="document.getElementById('studentPhotoInput{{ $user->id }}').click()"
                                                 style="cursor: pointer;">
                                        </div>
                                        <input type="file" id="studentPhotoInput{{ $user->id }}" accept="image/*" style="display:none;" onchange="uploadStudentPhoto({{ $user->id }}, this)">
                                        
                                        <div class="position-absolute bottom-0 end-0 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center border border-white" 
                                             style="width: 15px; height: 15px; transform: translate(5%, 5%); cursor: pointer;"
                                             onclick="document.getElementById('studentPhotoInput{{ $user->id }}').click()"
                                             title="Click to change student photo">
                                            <i class="fas fa-camera" style="font-size: 7px;"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">
                                            {{ $user->name }}
                                            @if($user->is_verified)
                                                <i class="fas fa-check-circle text-success ms-1" title="Verified Identity"></i>
                                            @endif
                                            @if($user->status === 'rejected')
                                                <span class="badge bg-danger ms-1 px-2 text-white" style="font-size: 0.7rem; font-weight: 700;">Suspended</span>
                                            @endif
                                            @if($user->manual_badge)
                                                <span class="ms-1" title="Honor Badge: {{ $user->manual_badge }}">
                                                    @if($user->manual_badge == 'Platinum') 🏆 @elseif($user->manual_badge == 'Gold') 🥇 @elseif($user->manual_badge == 'Silver') 🥈 @else 🥉 @endif
                                                </span>
                                            @endif
                                        </div>
                                        <div class="small text-muted" style="font-size: 0.8rem;">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge badge-enrollment">
                                    {{ $user->enrollment_no ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($user->phone)
                                    <a href="tel:{{ $user->phone }}" class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1.5 rounded-pill text-decoration-none fw-semibold">
                                        <i class="fas fa-phone-alt me-1 text-primary"></i> {{ $user->phone }}
                                    </a>
                                @else
                                    <span class="badge bg-light text-muted border border-light-subtle px-2.5 py-1.5 rounded-pill">
                                        <i class="fas fa-phone-slash me-1 opacity-50"></i> Unlinked
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <div>
                                        <span class="badge-level">
                                            <i class="fas fa-shield-alt"></i> Lvl {{ $user->level }}
                                        </span>
                                    </div>
                                    <div>
                                        <span class="badge-xp">
                                            <i class="fas fa-bolt"></i> {{ $user->xp }} XP
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-actions-dropdown px-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#actionsCollapse{{ $user->id }}" aria-expanded="false">
                                    <i class="fas fa-cog me-1 text-muted"></i> Actions <i class="fas fa-chevron-down ms-1 small"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Collapsible Management Panel for student -->
                        <tr class="actions-collapse-row" id="actionsRow{{ $user->id }}">
                            <td colspan="5" class="p-0 border-0">
                                <div class="collapse" id="actionsCollapse{{ $user->id }}" data-student-id="{{ $user->id }}">
                                    <div class="p-4" style="background: rgba(248, 250, 252, 0.9) !important; border-radius: 12px !important; border: 1px solid #e2e8f0 !important; margin: 10px 15px 15px 15px; box-shadow: inset 0 2px 8px rgba(0,0,0,0.02) !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                            <span class="small fw-extrabold text-uppercase tracking-wider text-muted"><i class="fas fa-sliders-h me-1 text-primary"></i> Control Panel: {{ $user->name }} ({{ $user->enrollment_no ?? 'No Enrollment' }})</span>
                                            <button type="button" class="btn-close" style="font-size: 0.75rem;" data-bs-toggle="collapse" data-bs-target="#actionsCollapse{{ $user->id }}"></button>
                                        </div>

                                        <div class="row g-3 mb-4 text-wrap">
                                            <!-- Parent Mapping (Up to 4) -->
                                            <div class="col-md-6">
                                                <div class="p-3 bg-white rounded-3 border h-100 shadow-sm">
                                                    <span class="small text-muted d-block fw-bold mb-2"><i class="fas fa-user-friends text-danger me-1"></i> Linked Parents (Max 4)</span>
                                                    @php
                                                        $linkedParents = $user->linked_parents;
                                                    @endphp
                                                    @if($linkedParents->isEmpty())
                                                        <span class="small text-muted">No parent accounts currently linked.</span>
                                                    @else
                                                        <div class="d-flex flex-column gap-1">
                                                            @foreach($linkedParents as $idx => $p)
                                                                <span class="small text-dark fw-semibold">
                                                                    Parent {{ $idx + 1 }}: <strong class="text-primary">{{ $p->name }}</strong> ({{ $p->email }})
                                                                </span>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>

                                            <!-- Hostel Swami Mapping -->
                                            <div class="col-md-6">
                                                <div class="p-3 bg-white rounded-3 border h-100 shadow-sm">
                                                    <span class="small text-muted d-block fw-bold mb-2"><i class="fas fa-bed text-success me-1"></i> BAPS Hostel Swami/Warden Assignment</span>
                                                    @if($user->hostelSwami)
                                                        <span class="small text-dark fw-semibold d-block">
                                                            Assigned Warden: <strong class="text-success">{{ $user->hostelSwami->name }}</strong>
                                                        </span>
                                                        <span class="small text-muted d-block">
                                                            Room: <strong class="text-dark">{{ $user->hostel_room_no ?? 'Unassigned Room' }}</strong>
                                                        </span>
                                                    @else
                                                        <span class="small text-muted">Student does not reside in the BAPS hostel.</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>

                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow" data-bs-toggle="modal" data-bs-target="#editIdentityModal{{ $user->id }}">
                                                <i class="fas fa-edit text-primary me-1"></i> Edit Identity
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow" data-bs-toggle="modal" data-bs-target="#editPasscodeModal{{ $user->id }}">
                                                <i class="fas fa-key text-warning me-1"></i> Reset Password
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow" data-bs-toggle="modal" data-bs-target="#editIdentityModal{{ $user->id }}">
                                                <i class="fas fa-building text-secondary me-1"></i> Change Department
                                            </button>
                                            <a href="/admin/students/{{ $user->id }}/progress" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow">
                                                <i class="fas fa-chart-line text-info me-1"></i> Academic Progress
                                            </a>
                                            <a href="mailto:{{ $user->email }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow">
                                                <i class="fas fa-paper-plane text-primary me-1"></i> Send Email
                                            </a>
                                            
                                            <form action="/admin/students/{{ $user->id }}/verify" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow">
                                                    <i class="fas fa-user-check text-success me-1"></i> {{ $user->is_verified ? 'Remove Verification' : 'Verify Identity' }}
                                                </button>
                                            </form>
                                            
                                            @if($user->role === 'cr')
                                                <form action="/admin/students/{{ $user->id }}/revoke-cr" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to revoke Class Representative (CR) status for {{ $user->name }}?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-danger hover-shadow">
                                                        <i class="fas fa-user-slash me-1"></i> Revoke CR
                                                    </button>
                                                </form>
                                            @else
                                                <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow" data-bs-toggle="modal" data-bs-target="#makeCrModal{{ $user->id }}">
                                                    <i class="fas fa-user-shield text-primary me-1"></i> Make CR
                                                </button>
                                            @endif
                                            
                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow" data-bs-toggle="modal" data-bs-target="#grantBadgeModal{{ $user->id }}">
                                                <i class="fas fa-medal text-warning me-1"></i> Grant Badge
                                            </button>

                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow" data-bs-toggle="modal" data-bs-target="#generateBillModal{{ $user->id }}">
                                                <i class="fas fa-file-invoice-dollar text-success me-1"></i> Generate Bill
                                            </button>
                                            
                                            <form action="/admin/students/{{ $user->id }}/suspend" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold hover-shadow {{ $user->status === 'rejected' ? 'text-success' : 'text-warning' }}">
                                                    <i class="fas {{ $user->status === 'rejected' ? 'fa-check-circle' : 'fa-ban' }} me-1"></i> {{ $user->status === 'rejected' ? 'Unsuspend Account' : 'Suspend Account' }}
                                                </button>
                                            </form>
                                            
                                            <form action="/admin/students/{{ $user->id }}/delete" method="POST" onsubmit="return confirm('Are you absolutely sure you want to expel and delete {{ $user->name }} from the system? All academic records and course enrollments will be permanently purged.')" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-danger hover-shadow">
                                                    <i class="fas fa-user-times me-1"></i> Expel / Delete
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-between align-items-center mt-3 bg-white shadow-sm rounded-4 border">
                <span class="small text-muted fw-bold" id="rosterShowingText">Showing {{ $students->count() }} students</span>
                <div class="d-flex gap-2">
                    <a href="/admin/students/download-pdf" class="btn btn-sm btn-primary rounded-pill px-3 fw-bold" style="background: linear-gradient(135deg, #ea580c 0%, #f97316 100%); border: none; color: white;">
                        <i class="fas fa-file-pdf me-1"></i> Download All User PDF
                    </a>
                    <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="document.getElementById('rosterSearch').value=''; document.getElementById('filterDepartment').value=''; document.getElementById('filterLevel').value=''; document.getElementById('rosterSearch').dispatchEvent(new Event('input'));">Reset Filters</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Identity Modals -->
@foreach($students as $user)
<div class="modal fade" id="editIdentityModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-edit text-primary me-2"></i> Edit Student Identity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/students/{{ $user->id }}/update" method="POST">
                @csrf
                <div class="modal-body pb-0">
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">Full Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">Email Configuration</label>
                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="small text-muted fw-bold">Enrollment No.</label>
                            <input type="text" name="enrollment_no" class="form-control" value="{{ $user->enrollment_no }}" required>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted fw-bold">Phone Contact</label>
                            <input type="text" name="phone" class="form-control" value="{{ $user->phone }}">
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="small text-muted fw-bold">Department Assignment</label>
                            <select name="department_id" class="form-select">
                                <option value="">Select Dept</option>
                                @foreach($departments as $dept)
                                    <option value="{{ $dept->id }}" {{ ($user->department_id == $dept->id) ? 'selected' : '' }}>{{ $dept->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="small text-muted fw-bold">Program</label>
                            <select name="program" class="form-select">
                                <option value="Diploma" {{ ($user->program == 'Diploma') ? 'selected' : '' }}>Diploma</option>
                                <option value="Bachelors" {{ ($user->program == 'Bachelors' || empty($user->program)) ? 'selected' : '' }}>Bachelors</option>
                                <option value="Masters" {{ ($user->program == 'Masters') ? 'selected' : '' }}>Masters</option>
                            </select>
                        </div>
                    </div>
                    <div class="row g-3 mb-3">
                        <div class="col-4">
                            <label class="small text-muted fw-bold">Year</label>
                            <input type="number" name="year" class="form-control" value="{{ $user->year ?? 1 }}" required>
                        </div>
                        <div class="col-4">
                            <label class="small text-muted fw-bold">Semester</label>
                            <input type="number" name="semester" class="form-control" value="{{ $user->semester ?? 1 }}" required>
                        </div>
                        <div class="col-4">
                            <label class="small text-muted fw-bold">Division/Branch</label>
                            <input type="text" name="class_section" class="form-control" value="{{ $user->class_section ?? '' }}" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">ABC Card ID</label>
                        <input type="text" name="abc_card_id" class="form-control" value="{{ $user->abc_card_id }}">
                    </div>
                    <div class="p-3 bg-light rounded-3 border mb-4">
                        <span class="small text-muted d-block fw-bold mb-2"><i class="fas fa-bed text-primary me-1"></i> BAPS Hostel Details</span>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted" style="font-size: 0.72rem;">Room Number</label>
                                <input type="text" name="hostel_room_no" class="form-control" value="{{ $user->hostel_room_no }}" placeholder="e.g. A-101">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted" style="font-size: 0.72rem;">Hostel Warden / Swami</label>
                                <select name="hostel_swami_id" class="form-select">
                                    <option value="">-- None / Outside --</option>
                                    @foreach($wardens as $w)
                                        <option value="{{ $w->id }}" {{ ($user->hostel_swami_id == $w->id) ? 'selected' : '' }}>{{ $w->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 bg-light-subtle rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 border shadow-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Save Identity Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Edit Passcode Modals -->
@foreach($students as $user)
<div class="modal fade" id="editPasscodeModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-key text-warning me-2"></i> Reset Student Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/admin/students/{{ $user->id }}/password" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-4">You are forcefully resetting the standard login password for <strong class="text-dark">{{ $user->name }}</strong> (Enrollment: {{ $user->enrollment_no }}).</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">New Secure Password</label>
                        <input type="text" name="password" class="form-control font-monospace form-control-lg text-center fw-bold text-primary" required minlength="4" placeholder="Enter new password">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">Reset Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="grantBadgeModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content glass-card border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h6 class="modal-title fw-bold"><i class="fas fa-medal text-warning me-2"></i> Grant Manual Badge</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/students/{{ $user->id }}/badge" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">Select Honor Badge</label>
                        <select name="badge" class="form-select border-0 bg-light shadow-sm">
                            <option value="">-- None / Remove --</option>
                            <option value="Platinum" {{ $user->manual_badge == 'Platinum' ? 'selected' : '' }}>🏆 Platinum (Top 1%)</option>
                            <option value="Gold" {{ $user->manual_badge == 'Gold' ? 'selected' : '' }}>🥇 Gold (Excellence)</option>
                            <option value="Silver" {{ $user->manual_badge == 'Silver' ? 'selected' : '' }}>🥈 Silver (High Achiever)</option>
                            <option value="Bronze" {{ $user->manual_badge == 'Bronze' ? 'selected' : '' }}>🥉 Bronze (Rising Star)</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-primary w-100 rounded-pill shadow-sm">Grant Badge</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="makeCrModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-shield text-primary me-2"></i> Promote to Class Representative (CR)</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            @php
                $cleanName = preg_replace('/[^a-zA-Z]/', '', strtolower($user->name));
                $nameParts = explode(' ', preg_replace('/\s+/', ' ', trim(strtolower($user->name))));
                $firstPart = $nameParts[0] ?? 'cr';
                $lastPart = $nameParts[1] ?? 'student';
                $defaultEmail = $firstPart . '.' . $lastPart . '.cr@itmbu.ac.in';
                $defaultPassword = 'CR@' . mt_rand(100000, 999999);
            @endphp
            <form action="/admin/students/{{ $user->id }}/make-cr" method="POST">
                @csrf
                <div class="modal-body pb-0">
                    <p class="text-muted small mb-3">Elevating <strong class="text-dark">{{ $user->name }}</strong> will grant them <strong>120% Access Level</strong> in the system and automatically generate a corresponding Staff account for CR administration.</p>
                    
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">CR Staff Email Address</label>
                        <input type="email" name="staff_email" class="form-control" value="{{ $defaultEmail }}" required>
                        <div class="form-text">This email will be used for CR staff dashboard access.</div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">CR Staff Account Password</label>
                        <input type="text" name="staff_password" class="form-control font-monospace fw-bold text-primary" value="{{ $defaultPassword }}" required>
                        <div class="form-text">Share these credentials with the student. They can log in via `/admin/login`.</div>
                    </div>
                    
                    <div class="mb-4">
                        <div class="p-3 bg-light rounded-3 border text-wrap">
                            <span class="small text-muted d-block fw-bold mb-1"><i class="fas fa-info-circle text-primary me-1"></i> Privilege Details</span>
                            <span class="small text-secondary">- Access Level set to 120 (120% system coverage)<br>- Access to Course Enrollment &amp; Attendance sheets<br>- Access to Student Registry &amp; Directory exports</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm">Confirm Promotion</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="generateBillModal{{ $user->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-invoice-dollar text-success me-2"></i> Generate Bill / Fee Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/students/{{ $user->id }}/generate-bill" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-3">Create a new outstanding fee token for <strong class="text-dark">{{ $user->name }}</strong> (Enrollment: {{ $user->enrollment_no }}).</p>
                    
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">Fee Type / Category</label>
                        <select name="fee_type" class="form-select border-0 bg-light shadow-sm" required>
                            <option value="Library and LMS">Library & LMS Fee</option>
                            <option value="Tuition Fee">Tuition Fee</option>
                            <option value="Exam Fee">Exam Fee</option>
                            <option value="Hostel Fee">Hostel Fee</option>
                            <option value="Fine / Penalty">Fine / Penalty</option>
                        </select>
                    </div>
                    
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">Billing Amount (₹)</label>
                        <input type="number" name="amount" class="form-control" placeholder="e.g. 1200" required min="1">
                    </div>
                    
                    <div class="p-3 bg-light rounded-3 border">
                        <span class="small text-muted d-block fw-bold mb-1"><i class="fas fa-info-circle text-primary me-1"></i> Note</span>
                        <span class="small text-secondary">Generating this bill will create a pending transaction in the student's dashboard with a unique 4-digit token. The student will be notified upon login.</span>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-light rounded-pill px-4 border" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm text-white">Generate Bill</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach


<div class="row g-4 mt-2">
    <div class="col-12">
        <h5 class="fw-bold mb-3"><i class="fas fa-file-invoice-dollar me-2 text-success"></i> Pending Fee Gateway Logs</h5>
        <div class="glass-card p-0 border-0 shadow-sm overflow-hidden text-nowrap rounded-4 border">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light text-muted small text-uppercase fw-bold border-bottom">
                        <tr>
                            <th class="ps-4 py-3">Student Details</th>
                            <th class="py-3">Amount Due</th>
                            <th class="text-end pe-4 py-3">Initiate Transaction</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($pendingFees as $fee)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold text-dark">{{ $fee->user->name ?? 'Unknown Student' }}</div>
                                <div class="small text-muted font-monospace">{{ $fee->user->enrollment_no ?? 'N/A' }} | {{ !empty($fee->user->program) ? strtoupper($fee->user->program) : 'UNASSIGNED STREAM' }}</div>
                            </td>
                            <td>
                                <div class="fs-5 fw-bold text-success">₹{{ $fee->amount }}</div>
                                <div class="small text-muted">Library & LMS Mainframe</div>
                            </td>
                            <td class="text-end pe-4">
                                <button type="button" class="btn btn-success btn-sm rounded-pill fw-bold shadow-sm" data-bs-toggle="collapse" data-bs-target="#feeCollapse{{ $fee->id }}" aria-expanded="false" aria-controls="feeCollapse{{ $fee->id }}">
                                    <i class="fas fa-cash-register me-1"></i> Process Payment
                                </button>
                            </td>
                        </tr>

                        <!-- Interactive Fee Processing Collapse -->
                        <tr class="collapse" id="feeCollapse{{ $fee->id }}">
                            <td colspan="3" class="p-0 border-0">
                                <div class="bg-success bg-opacity-10 p-4 border border-success border-top-0 rounded-bottom">
                                    <div class="alert alert-warning border-0 fw-bold mb-4 shadow-sm">
                                        <i class="fas fa-exclamation-triangle me-2"></i> You are acting as an authorized payee. Ensure funds are received before submitting.
                                        <div class="mt-2 text-dark">Collecting <span class="fs-5 text-success">₹{{ $fee->amount }}</span> from <span class="text-primary">{{ $fee->user->name }}</span></div>
                                    </div>
                                    
                                    <form action="/admin/approvals/fee/{{ $fee->id }}/process" method="POST">
                                        @csrf 
                                        <input type="hidden" name="action" value="approve">
                                            
                                            <div class="row g-3 mb-3">
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Student Auth Token (4-Digit) *</label>
                                                    <input type="text" name="token_number" class="form-control fw-bold text-center fs-5 border-success" maxlength="4" placeholder="e.g. 8492" required>
                                                    <div class="form-text">Ask student for their token.</div>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Payment Method *</label>
                                                    <select name="payment_method" class="form-select border-success" required>
                                                        <option value="UPI">UPI</option>
                                                        <option value="Cash">Cash</option>
                                                        <option value="Card">Credit/Debit Card</option>
                                                        <option value="Bank Transfer">Bank Transfer / NEFT</option>
                                                    </select>
                                                </div>
                                                
                                                <div class="col-md-4">
                                                    <label class="form-label small fw-bold text-muted">Date of Payment *</label>
                                                    <input type="date" name="payment_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                                                </div>
                                                <div class="col-md-8">
                                                    <label class="form-label small fw-bold text-muted">Transaction ID / Receipt No.</label>
                                                    <input type="text" name="transaction_id" class="form-control" placeholder="Optional for Cash">
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Bank Name (If Applicable)</label>
                                                    <input type="text" name="bank_name" class="form-control" placeholder="SBI, HDFC, etc.">
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Payer Name (If not Student)</label>
                                                    <input type="text" name="payer_name" class="form-control" placeholder="Father's Name, etc.">
                                                </div>

                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-muted">LMS Clearance Field 1</label>
                                                    <select name="approval_field_1" class="form-select">
                                                        <option value="">-- Optional --</option>
                                                        <option value="Accounts Cleared">Accounts Cleared</option>
                                                        <option value="Pending Bank Sync">Pending Bank Sync</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-label small fw-bold text-muted">Library Clearance Field 2</label>
                                                    <select name="approval_field_2" class="form-select">
                                                        <option value="">-- Optional --</option>
                                                        <option value="Library Authorized">Library Authorized</option>
                                                    </select>
                                                </div>

                                                <div class="col-12">
                                                    <label class="form-label small fw-bold text-muted">Audit Remarks</label>
                                                    <textarea name="remarks" class="form-control" rows="2" placeholder="Any internal notes..."></textarea>
                                                </div>
                                            </div>


                                        <div class="d-flex justify-content-end gap-2 mt-4 border-top border-success pt-3">
                                            <button type="button" class="btn btn-light shadow-sm" data-bs-toggle="collapse" data-bs-target="#feeCollapse{{ $fee->id }}">Cancel</button>
                                            <button type="submit" class="btn btn-success fw-bold px-4 shadow-sm"><i class="fas fa-check me-2"></i> Finalize Payment Log</button>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>

                        @empty
                        <tr><td colspan="3" class="text-center py-5 text-muted"><i class="fas fa-file-invoice-dollar fs-1 mb-3 text-success opacity-50"></i><p class="mb-0 fw-bold">No Pending Fee Verifications</p></td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
function uploadStudentPhoto(studentId, input) {
    const file = input.files[0];
    if (!file) return;

    if (file.size > 2 * 1024 * 1024) {
        alert('File too large! Your server (php.ini) limits uploads to 2MB. Please use a smaller image.');
        return;
    }

    const img = document.getElementById('studentAvatar' + studentId);
    const previousSrc = img.src;
    
    // Preview
    const reader = new FileReader();
    reader.onload = e => { img.src = e.target.result; img.style.opacity = '0.5'; };
    reader.readAsDataURL(file);

    const formData = new FormData();
    formData.append('photo', file);
    formData.append('target_id', studentId);
    formData.append('target_type', 'user');
    formData.append('_token', '{{ csrf_token() }}');

    fetch('/profile/upload-photo', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(async r => {
        const data = await r.json();
        img.style.opacity = '1';
        if (data.success) {
            img.src = data.url;
            // Use parent BAPS toast if available, otherwise standard alert
            if (window.showBapsToast) window.showBapsToast('Student photo updated!', 'success');
            else if (typeof showBapsToast === 'function') showBapsToast('Student photo updated!', 'success');
        } else {
            img.src = previousSrc;
            alert('Error: ' + (data.error || 'Upload failed'));
        }
    })
    .catch(err => {
        img.style.opacity = '1';
        img.src = previousSrc;
        alert('Network error during upload.');
    });
}

// ═══════════════════════════════════════
// LIVE ROSTER CLIENT-SIDE SEARCH ENGINE
// ═══════════════════════════════════════
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('rosterSearch');
    const deptFilter = document.getElementById('filterDepartment');
    const lvlFilter = document.getElementById('filterLevel');
    const rows = document.querySelectorAll('.student-row');
    const countSpan = document.getElementById('rosterFilteredCount');
    const countShowingText = document.getElementById('rosterShowingText');

    function filterRoster() {
        if (!searchInput) return;
        const query = searchInput.value.toLowerCase().trim();
        const deptValue = deptFilter ? deptFilter.value.toLowerCase().trim() : '';
        const lvlValue = lvlFilter ? lvlFilter.value : '';
        let visibleCount = 0;

        rows.forEach(row => {
            const name = row.getAttribute('data-name') || '';
            const email = row.getAttribute('data-email') || '';
            const enrollment = row.getAttribute('data-enrollment') || '';
            const abcCard = row.getAttribute('data-abc') || '';
            const dept = (row.getAttribute('data-department') || '').toLowerCase().trim();
            const lvl = parseInt(row.getAttribute('data-level')) || 1;

            const matchesQuery = !query || 
                name.includes(query) || 
                email.includes(query) || 
                enrollment.includes(query) || 
                abcCard.toLowerCase().includes(query);

            const matchesDept = !deptValue || dept === deptValue;
            
            let matchesLvl = true;
            if (lvlValue === '1') {
                matchesLvl = lvl === 1;
            } else if (lvlValue === '2') {
                matchesLvl = lvl >= 2;
            }

            if (matchesQuery && matchesDept && matchesLvl) {
                row.style.setProperty('display', '', 'important');
                visibleCount++;
            } else {
                row.style.setProperty('display', 'none', 'important');
                
                // Hide any associated actions row and collapse it
                const studentId = row.id.replace('studentRow', '');
                const actionsRow = document.getElementById('actionsRow' + studentId);
                const collapseDiv = document.getElementById('actionsCollapse' + studentId);
                if (actionsRow) {
                    actionsRow.classList.remove('show-row');
                }
                if (collapseDiv) {
                    collapseDiv.classList.remove('show');
                }
            }
        });

        if (countSpan) {
            countSpan.textContent = `Showing ${visibleCount} of ${rows.length} students`;
        }
        if (countShowingText) {
            countShowingText.textContent = `Showing ${visibleCount} students`;
        }
    }

    if (searchInput) searchInput.addEventListener('input', filterRoster);
    if (deptFilter) deptFilter.addEventListener('change', filterRoster);
    if (lvlFilter) lvlFilter.addEventListener('change', filterRoster);

    // Parse URL parameter and filter
    const urlParams = new URLSearchParams(window.location.search);
    const searchQuery = urlParams.get('search');
    if (searchQuery && searchInput) {
        searchInput.value = searchQuery;
        filterRoster();
        
        // Auto-expand if exactly one student matches
        const visibleRows = Array.from(rows).filter(r => r.style.display !== 'none');
        if (visibleRows.length === 1) {
            const studentId = visibleRows[0].id.replace('studentRow', '');
            const collapseDiv = document.getElementById('actionsCollapse' + studentId);
            if (collapseDiv) {
                setTimeout(() => {
                    const bsCollapse = new bootstrap.Collapse(collapseDiv, { show: true });
                    bsCollapse.show();
                }, 300);
            }
        }
    }

    // Listen for bootstrap collapse show/hide events to toggle row visibility class
    document.querySelectorAll('.actions-collapse-row div.collapse').forEach(el => {
        el.addEventListener('show.bs.collapse', function() {
            const tr = this.closest('tr');
            if (tr) tr.classList.add('show-row');
        });
        el.addEventListener('hidden.bs.collapse', function() {
            const tr = this.closest('tr');
            if (tr) tr.classList.remove('show-row');
        });
    });
});
</script>

@endsection
