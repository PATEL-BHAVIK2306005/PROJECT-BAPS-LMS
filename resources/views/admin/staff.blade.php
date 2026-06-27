@extends('layouts.app')
@section('content')

<style>
    :root {
        --card-bg: #ffffff;
        --card-border: #e2e8f0;
        --text-main: #1e293b;
        --text-sub: #64748b;
        --table-header-bg: #f8fafc;
        --table-row-hover: #f8fafc;
        --border-color: #f1f5f9;
        --input-bg: #ffffff;
        --input-border: #cbd5e1;
    }
    body.dark-mode {
        --card-bg: #1e293b;
        --card-border: #334155;
        --text-main: #f8fafc;
        --text-sub: #cbd5e1;
        --table-header-bg: #111827;
        --table-row-hover: rgba(255, 255, 255, 0.02);
        --border-color: #334155;
        --input-bg: #1e293b;
        --input-border: #475569;
    }

    .premium-card {
        background: var(--card-bg);
        border: 1px solid var(--card-border);
        border-radius: 16px;
        box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow: hidden;
    }
    
    .premium-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.08);
    }

    /* Stat Cards Styling */
    .stat-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.3rem;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .table-responsive {
        border-radius: 12px;
        overflow-x: auto;
        border: 1px solid var(--border-color);
        -webkit-overflow-scrolling: touch;
        scrollbar-width: thin;
        scrollbar-color: #f97316 var(--table-header-bg);
    }
    
    /* Beautiful inline scrollbar for the table responsive container */
    .table-responsive::-webkit-scrollbar {
        height: 8px;
    }
    .table-responsive::-webkit-scrollbar-track {
        background: var(--table-header-bg);
        border-radius: 0 0 12px 12px;
    }
    .table-responsive::-webkit-scrollbar-thumb {
        background: linear-gradient(90deg, #f97316, #ea580c);
        border-radius: 10px;
    }
    .table-responsive::-webkit-scrollbar-thumb:hover {
        filter: brightness(1.1);
    }
    
    .table {
        color: var(--text-main) !important;
        margin-bottom: 0;
    }
    
    .table th {
        font-weight: 700;
        text-transform: uppercase;
        font-size: 0.72rem;
        letter-spacing: 0.7px;
        color: var(--text-sub) !important;
        background-color: var(--table-header-bg) !important;
        border-bottom: 2px solid var(--card-border) !important;
        padding: 14px 16px;
    }
    
    .table td {
        padding: 14px 16px;
        vertical-align: middle;
        border-bottom: 1px solid var(--border-color) !important;
        background-color: transparent !important;
    }
    
    .table tbody tr {
        transition: all 0.2s ease;
    }
    
    .table tbody tr:hover {
        background-color: var(--table-row-hover) !important;
    }

    /* Action Icons */
    .btn-action-icon {
        width: 34px;
        height: 34px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        font-size: 0.9rem;
        transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1px solid var(--card-border);
        cursor: pointer;
        background-color: var(--card-bg);
    }
    
    .btn-edit {
        color: #4f46e5;
        border-color: rgba(79, 70, 229, 0.15);
    }
    .btn-edit:hover {
        color: #ffffff;
        background-color: #4f46e5 !important;
        border-color: #4f46e5;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.35);
    }
    
    .btn-reset {
        color: #d97706;
        border-color: rgba(217, 119, 6, 0.15);
    }
    .btn-reset:hover {
        color: #ffffff;
        background-color: #d97706 !important;
        border-color: #d97706;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(217, 119, 6, 0.35);
    }
    
    .btn-allocate {
        color: #0891b2;
        border-color: rgba(8, 145, 178, 0.15);
    }
    .btn-allocate:hover {
        color: #ffffff;
        background-color: #0891b2 !important;
        border-color: #0891b2;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(8, 145, 178, 0.35);
    }
    
    .btn-delete {
        color: #dc2626;
        border-color: rgba(220, 38, 38, 0.15);
    }
    .btn-delete:hover:not(.disabled) {
        color: #ffffff;
        background-color: #dc2626 !important;
        border-color: #dc2626;
        transform: translateY(-2px) scale(1.05);
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.35);
    }
    
    .btn-delete.disabled {
        color: #94a3b8;
        background-color: var(--table-header-bg) !important;
        border-color: var(--card-border);
        cursor: not-allowed;
        opacity: 0.4;
    }

    /* Custom Checkbox Styling */
    .custom-chk {
        width: 18px;
        height: 18px;
        border-radius: 6px;
        border: 2px solid var(--text-sub);
        background-color: var(--input-bg);
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .custom-chk:checked {
        background-color: #f97316 !important; /* Saffron signature theme */
        border-color: #f97316 !important;
    }

    /* Badge Customization */
    .baps-badge {
        padding: 4px 10px;
        font-weight: 700;
        border-radius: 30px;
        font-size: 0.68rem;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        display: inline-block;
        border: 1px solid transparent;
    }
    
    .badge-primary-baps {
        background-color: rgba(99, 102, 241, 0.08) !important;
        color: #6366f1 !important;
        border-color: rgba(99, 102, 241, 0.15) !important;
    }
    
    .badge-info-baps {
        background-color: rgba(14, 165, 233, 0.08) !important;
        color: #0284c7 !important;
        border-color: rgba(14, 165, 233, 0.15) !important;
    }

    /* Code Block styling */
    .code-badge {
        background-color: var(--border-color);
        color: var(--text-main);
        font-family: 'Courier New', Courier, monospace;
        font-weight: 700;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 0.72rem;
    }
    
    .form-control, .form-select {
        background-color: var(--input-bg) !important;
        border: 1.5px solid var(--input-border) !important;
        color: var(--text-main) !important;
        border-radius: 8px;
        padding: 10px 14px;
        font-size: 0.9rem;
        transition: all 0.2s ease;
    }
    
    .form-control:focus, .form-select:focus {
        border-color: #f97316 !important;
        box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15) !important;
    }

    /* Titles text color */
    .text-dark {
        color: var(--text-main) !important;
    }
    .text-muted {
        color: var(--text-sub) !important;
    }

    /* Form Icons input group prefix styling */
    .input-group-text {
        background-color: var(--table-header-bg) !important;
        border: 1.5px solid var(--input-border) !important;
        color: var(--text-sub) !important;
        border-radius: 8px 0 0 8px;
    }
    .form-control-prefixed {
        border-radius: 0 8px 8px 0 !important;
        border-left: none !important;
    }
    
    /* Reveal Password Glow Class */
    .password-revealed-glow {
        box-shadow: 0 0 8px rgba(234, 179, 8, 0.3);
        border: 1px solid rgba(234, 179, 8, 0.5) !important;
        transition: all 0.3s ease;
    }
</style>

{{-- Flash Messages --}}
@if(session('success'))
<div class="alert d-flex align-items-center gap-3 border-0 shadow-sm mb-4 px-4 py-3 rounded-4"
     style="background: linear-gradient(135deg,#d1fae5,#a7f3d0); color:#065f46;">
    <i class="fas fa-check-circle fa-lg"></i>
    <div><strong>Done!</strong> {{ session('success') }}</div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

@if(session('error'))
<div class="alert d-flex align-items-center gap-3 border-0 shadow-sm mb-4 px-4 py-3 rounded-4"
     style="background: linear-gradient(135deg,#fee2e2,#fecaca); color:#991b1b;">
    <i class="fas fa-exclamation-circle fa-lg"></i>
    <div>
        <strong>⚠ Error!</strong><br>
        <span class="font-monospace fw-semibold" style="font-size:0.95rem;">{{ session('error') }}</span>
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

@if($errors->any())
<div class="alert d-flex align-items-start gap-3 border-0 shadow-sm mb-4 px-4 py-3 rounded-4"
     style="background: linear-gradient(135deg,#fef3c7,#fde68a); color:#92400e;">
    <i class="fas fa-triangle-exclamation fa-lg mt-1"></i>
    <div>
        <strong>Validation Error:</strong>
        <ul class="mb-0 mt-1">
            @foreach($errors->all() as $err)
                <li class="small">{{ $err }}</li>
            @endforeach
        </ul>
    </div>
    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- Header Banner Area --}}
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
    <div>
        <h3 class="fw-bold mb-1 text-dark"><i class="fas fa-id-card text-primary me-2"></i>Staff & Faculty Hub</h3>
        <p class="text-muted small mb-0">Manage personnel credentials, roles, allocations, and downloads.</p>
    </div>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

{{-- Dynamic Statistics Cards --}}
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="premium-card p-3 d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                <i class="fas fa-users-cog text-white"></i>
            </div>
            <div>
                <div class="text-muted small">Total Directory</div>
                <div class="fs-5 fw-bold text-dark">{{ count($staffMembers) }} Staff</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="premium-card p-3 d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: linear-gradient(135deg, #ea580c, #f97316);">
                <i class="fas fa-shield-halved text-white"></i>
            </div>
            <div>
                <div class="text-muted small">Leadership</div>
                <div class="fs-5 fw-bold text-dark">{{ $staffMembers->whereIn('role', ['dean', 'hod'])->count() }} Leaders</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="premium-card p-3 d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: linear-gradient(135deg, #0ea5e9, #0284c7);">
                <i class="fas fa-chalkboard-teacher text-white"></i>
            </div>
            <div>
                <div class="text-muted small">Active Faculty</div>
                <div class="fs-5 fw-bold text-dark">{{ $staffMembers->where('role', 'faculty')->count() }} Faculty</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="premium-card p-3 d-flex align-items-center gap-3">
            <div class="stat-icon" style="background: linear-gradient(135deg, #10b981, #059669);">
                <i class="fas fa-user-shield text-white"></i>
            </div>
            <div>
                <div class="text-muted small">Admin & Support</div>
                <div class="fs-5 fw-bold text-dark">{{ $staffMembers->whereNotIn('role', ['dean', 'hod', 'faculty'])->count() }} Support</div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    {{-- Left Side: Enrollment Tools --}}
    @if(session('user_role') !== 'cr')
    <div class="col-lg-4">
        {{-- Enroll Staff Card --}}
        <div class="premium-card p-4 mb-4">
            <h5 class="fw-bold mb-3 text-dark"><i class="fas fa-user-plus text-primary me-2"></i>Enroll Staff</h5>

            <form method="POST" action="/admin/staff">
                @csrf
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input name="name" class="form-control form-control-prefixed @error('name') is-invalid @enderror" placeholder="Full Name" value="{{ old('name') }}" required>
                    </div>
                </div>
                
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                        <select name="role" id="enroll_role_select" class="form-select form-control-prefixed @error('role') is-invalid @enderror" onchange="handleRoleChange('enroll')" required>
                            <option value="">-- Primary Role --</option>
                            <option value="admin">Admin (Super Admin)</option>
                            <option value="president">President</option>
                            <option value="vice-president">Vice President</option>
                            <option value="provost">Provost</option>
                            <option value="registrar">Registrar</option>
                            <option value="director">Director</option>
                            <option value="board-member">Board of Member</option>
                            <option value="external-coordinator">External University (Company Coordinator)</option>
                            <option value="dean">Dean</option>
                            <option value="office-assistant">Office Assistant</option>
                            <option value="hod">HOD</option>
                            <option value="faculty">Faculty</option>
                            <option value="coordinator">Coordinator</option>
                            <option value="moderator">Moderator</option>
                        </select>
                    </div>
                </div>

                <!-- Scope Select -->
                <div class="mb-3" id="enroll_scope_container">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                        <select name="scope" id="enroll_scope_select" class="form-select form-control-prefixed" onchange="handleScopeChange('enroll')">
                            <option value="department">Department-Specific Scope</option>
                            <option value="universal">Universal / University-Level Scope</option>
                        </select>
                    </div>
                </div>

                <!-- Designation Select (Shows dynamically) -->
                <div class="mb-3 d-none" id="enroll_designation_container">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-award"></i></span>
                        <select name="designation" id="enroll_designation_select" class="form-select form-control-prefixed" onchange="handleDesignationChange('enroll')">
                            <!-- Populated via Javascript -->
                        </select>
                    </div>
                </div>

                <!-- Dean Password Input (Shows dynamically when Main Dean is selected) -->
                <div class="mb-3 d-none" id="enroll_dean_password_container">
                    <label class="small fw-bold text-muted mb-1"><i class="fas fa-key text-danger"></i> Dean Authorization Password</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key text-danger"></i></span>
                        <input type="password" name="dean_password" id="enroll_dean_password" class="form-control form-control-prefixed" placeholder="Enter Password for Main Dean ('1234@1234')">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="small fw-bold text-muted mb-1"><i class="fas fa-tags me-1"></i>Additional Positions</label>
                    <select name="positions[]" class="form-select" multiple size="4" style="height: 100px;">
                        <option value="lecturer">Lecturer</option>
                        <option value="lab">Lab Assistant</option>
                        <option value="coordinator">Coordinator</option>
                        <option value="researcher">Researcher</option>
                        <option value="moderator">Moderator</option>
                    </select>
                </div>

                <div class="mb-3" id="enroll_dept_container">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                        <select name="department_id" id="enroll_dept_select" class="form-select form-control-prefixed">
                            <option value="">-- Department --</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                        <input name="unique_code" class="form-control form-control-prefixed @error('unique_code') is-invalid @enderror" placeholder="Unique Login Code" required>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        <input name="email" type="email" class="form-control form-control-prefixed @error('email') is-invalid @enderror" placeholder="Email">
                    </div>
                </div>

                <div class="mb-4">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input name="password" type="password" class="form-control form-control-prefixed" placeholder="Password (Optional)">
                    </div>
                </div>

                <button class="btn btn-success w-100 py-2.5 rounded-3 fw-bold shadow-sm" style="background: linear-gradient(135deg, #10b981, #059669); border:none;"><i class="fas fa-user-plus me-1"></i> Enroll Staff Member</button>
            </form>
        </div>

        {{-- Bulk Enroll Faculty Card --}}
        <div class="premium-card p-4 mb-4">
            <h5 class="fw-bold mb-2 text-dark"><i class="fas fa-users text-success me-2"></i>Bulk Enroll Faculty</h5>
            <p class="small text-muted">Enter faculty names (one per line). Format: <code>Name(Role)</code> to auto-detect roles.</p>
            <form method="POST" action="/admin/staff/bulk-enroll">
                @csrf
                <div class="mb-3">
                    <textarea name="bulk_names" class="form-control font-monospace" rows="5" placeholder="Riya Modi&#10;Prachi Rajput(HOD Diploma)&#10;Dr. Kalpana Matre(Co-Dean)" required style="font-size: 0.85rem;"></textarea>
                </div>
                <div class="mb-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                        <select name="department_id" class="form-select form-control-prefixed" required>
                            <option value="">-- Department --</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}" {{ $d->code == 'SCSET' ? 'selected' : '' }}>{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button class="btn btn-outline-success w-100 py-2 rounded-3 fw-bold"><i class="fas fa-file-import me-1"></i> Bulk Enroll Faculty</button>
            </form>
        </div>

        {{-- Enrollment Vault Card --}}
        <div class="premium-card p-4 bg-white border mb-4">
            <h5 class="fw-bold text-dark"><i class="fas fa-file-pdf text-danger me-2"></i>Enrollment Vault</h5>
            <p class="small text-muted">Download the complete <strong>Staff & Faculty Directory</strong> PDF — featuring credentials and dynamic plain text passwords for official records.</p>
            <a href="/admin/staff/download-latest-pdf" class="btn btn-primary w-100 fw-bold py-2.5 rounded-pill shadow-sm" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border:none;">
                <i class="fas fa-cloud-download-alt me-1"></i> Download All Staff PDF
            </a>
        </div>
    </div>
    @endif
    
    {{-- Right Side: Faculty Roster --}}
    <div class="{{ session('user_role') !== 'cr' ? 'col-lg-8' : 'col-lg-12' }}">
        <div class="premium-card">
            {{-- Table Control Header --}}
            <div class="card-header bg-transparent py-4 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-3">
                <h5 class="mb-0 fw-bold text-dark"><i class="fas fa-users-viewfinder text-primary me-2"></i>Faculty Roster</h5>
                
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if(session('user_role') === 'cr')
                        <a href="/admin/staff/download-latest-pdf" class="btn btn-primary btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-2" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border:none; color: white;">
                            <i class="fas fa-cloud-download-alt"></i> Download Staff PDF
                        </a>
                    @endif

                    @if(session('user_role') !== 'cr')
                        {{-- Reveal Passwords Button --}}
                        <button type="button" id="revealPasswordsBtn" class="btn btn-outline-warning btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-2">
                            <i class="fas fa-key"></i> Reveal Passwords
                        </button>
                    @endif
                    
                    {{-- Client-side Roster Filter --}}
                    <div class="position-relative" style="width: 200px;">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" id="rosterSearch" class="form-control ps-5 border bg-light rounded-pill w-100" placeholder="Filter Roster..." style="font-size: 0.85rem; height: 36px; padding-top:4px; padding-bottom:4px;">
                    </div>

                    @if(session('user_role') !== 'cr')
                        {{-- Bulk Delete Button --}}
                        <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm rounded-pill px-3 py-1.5 fw-bold d-none">
                            <i class="fas fa-trash-alt me-1"></i> Delete (<span id="selectedCount">0</span>)
                        </button>
                    @endif
                </div>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle text-nowrap">
                    <thead>
                        <tr>
                            @if(session('user_role') !== 'cr')
                                <th width="40"><input type="checkbox" id="selectAll" class="form-check-input custom-chk"></th>
                            @endif
                            <th style="min-width: 200px;">Staff Details</th>
                            <th>Positions</th>
                            <th style="min-width: 140px;">Dept</th>
                            <th>@if(session('user_role') !== 'cr') Credentials & Password @else Contact Info @endif</th>
                            @if(session('user_role') !== 'cr')
                                <th style="min-width: 160px; text-align: center;">Actions</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($staffMembers as $s)
                        @php
                            // Compute deterministic plain password
                            $cleanName = preg_replace('/\s*\([^)]*\)/', '', $s->name);
                            $cleanName = str_replace(['.', ','], ' ', $cleanName);
                            $cleanName = preg_replace('/\s+/', ' ', trim($cleanName));
                            $wordsList = explode(' ', $cleanName);
                            $titles = ['dr', 'prof', 'hod', 'dean', 'provost', 'associate', 'co-dean', 'senior', 'assistant', 'dill'];
                            $filteredWords = [];
                            foreach ($wordsList as $word) {
                                $cleanWord = strtolower(trim($word));
                                if (in_array($cleanWord, $titles) || empty($cleanWord)) {
                                    continue;
                                }
                                $filteredWords[] = $word;
                            }
                            $first = $filteredWords[0] ?? 'Faculty';
                            $firstClean = preg_replace('/[^a-zA-Z]/', '', $first);
                            $plainPassword = strtoupper($firstClean) . '@123';

                            // Initials for avatar
                            $initials = '';
                            $wordCount = 0;
                            foreach($filteredWords as $w) {
                                $w = trim($w);
                                if(!empty($w)) {
                                    $initials .= strtoupper($w[0]);
                                    $wordCount++;
                                    if($wordCount >= 2) break;
                                }
                            }
                            if(empty($initials)) {
                                $initials = strtoupper(substr($s->name, 0, 2));
                            }
                            
                            // Programmatic Gradient
                            $gradients = [
                                'linear-gradient(135deg, #6366f1, #a855f7)',
                                'linear-gradient(135deg, #f97316, #ec4899)',
                                'linear-gradient(135deg, #10b981, #059669)',
                                'linear-gradient(135deg, #0ea5e9, #2563eb)',
                                'linear-gradient(135deg, #f59e0b, #ea580c)',
                            ];
                            $grad = $gradients[$s->id % count($gradients)];
                        @endphp
                        <tr>
                            @if(session('user_role') !== 'cr')
                            <td>
                                @if(session('staff_id') != $s->id)
                                    <input type="checkbox" class="staff-checkbox form-check-input custom-chk" data-id="{{ $s->id }}">
                                @endif
                            </td>
                            @endif
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center text-white fw-bold shadow-sm" 
                                         style="background: {{ $grad }}; width: 40px; height: 40px; border-radius: 12px; font-size: 0.95rem; border: 2px solid #ffffff; flex-shrink: 0;">
                                        {{ $initials }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.95rem;">{{ $s->name }}</div>
                                        <div class="text-muted small mt-0.5">
                                            ID: <span class="fw-semibold">{{ $s->id }}</span>
                                            @if(session('user_role') !== 'cr')
                                                &bull; Code: <span class="code-badge">{{ $s->unique_code }}</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="d-flex flex-wrap gap-1">
                                    <span class="badge baps-badge badge-primary-baps">{{ $s->role }}</span>
                                    @if($s->positions)
                                        @foreach($s->positions as $pos)
                                            @if($pos != $s->role)
                                                <span class="badge baps-badge badge-info-baps">{{ $pos }}</span>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-dark small">{{ $s->department->code ?? 'UNIVERSAL' }}</div>
                                <div class="text-muted" style="font-size: 0.72rem; line-height: 1.2;" title="{{ $s->department->name ?? 'Universal / University Level' }}">{{ $s->department->name ?? 'Universal Department' }}</div>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-1">
                                    <a href="mailto:{{ $s->email }}" class="text-decoration-none text-primary small d-inline-flex align-items-center gap-1.5">
                                        <i class="far fa-envelope text-muted"></i>
                                        <span>{{ $s->email }}</span>
                                    </a>
                                    @if(session('user_role') !== 'cr')
                                        <div class="password-container mt-1">
                                            <span class="password-masked text-muted small font-monospace" data-pwd="{{ $plainPassword }}">
                                                <i class="fas fa-lock text-warning opacity-75 me-1" style="font-size: 0.75rem;"></i>••••••••
                                            </span>
                                        </div>
                                    @elseif($s->phone)
                                        <div class="mt-1 text-muted small">
                                            <i class="fas fa-phone text-muted me-1" style="font-size: 0.75rem;"></i>{{ $s->phone }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            @if(session('user_role') !== 'cr')
                            <td>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <button class="btn btn-action-icon btn-edit" title="Edit Faculty Details" data-bs-toggle="modal" data-bs-target="#editStaffModal{{$s->id}}">
                                        <i class="fas fa-user-edit"></i>
                                    </button>
                                    <button class="btn btn-action-icon btn-reset" title="Reset Credentials" data-bs-toggle="modal" data-bs-target="#resetModal{{$s->id}}">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button class="btn btn-action-icon btn-allocate" title="Allocate Course" data-bs-toggle="modal" data-bs-target="#allocateModal{{$s->id}}">
                                        <i class="fas fa-book-open"></i>
                                    </button>
                                    @if(session('staff_id') != $s->id)
                                    <button class="btn btn-action-icon btn-delete" title="Delete Faculty Member" data-bs-toggle="modal" data-bs-target="#deleteStaffModal{{$s->id}}">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    @else
                                    <button class="btn btn-action-icon btn-delete disabled" disabled title="Cannot delete yourself">
                                        <i class="fas fa-ban"></i>
                                    </button>
                                    @endif
                                </div>
                            </td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Staff Modals -->
@foreach($staffMembers as $s)
    <!-- Edit Modal -->
    <div class="modal fade" id="editStaffModal{{$s->id}}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content border-0 shadow">
                <form action="/admin/staff/{{$s->id}}/update" method="POST">
                    @csrf
                    <div class="modal-header py-3 text-white" style="background: linear-gradient(135deg, #4f46e5, #7c3aed);">
                        <h5 class="modal-title fw-bold"><i class="fas fa-user-edit me-2"></i>Edit Staff: {{ $s->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Full Name</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user"></i></span>
                                <input name="name" class="form-control form-control-prefixed" value="{{ $s->name }}" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Primary Role</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                <select name="role" id="edit_{{ $s->id }}_role_select" class="form-select form-control-prefixed" onchange="handleRoleChange('edit_{{ $s->id }}')" required>
                                    <option value="admin" {{ $s->role=='admin'?'selected':'' }}>Admin (Super Admin)</option>
                                    <option value="president" {{ $s->role=='president'?'selected':'' }}>President</option>
                                    <option value="vice-president" {{ $s->role=='vice-president'?'selected':'' }}>Vice President</option>
                                    <option value="provost" {{ $s->role=='provost'?'selected':'' }}>Provost</option>
                                    <option value="registrar" {{ $s->role=='registrar'?'selected':'' }}>Registrar</option>
                                    <option value="director" {{ $s->role=='director'?'selected':'' }}>Director</option>
                                    <option value="board-member" {{ $s->role=='board-member'?'selected':'' }}>Board of Member</option>
                                    <option value="external-coordinator" {{ $s->role=='external-coordinator'?'selected':'' }}>External University (Company Coordinator)</option>
                                    <option value="dean" {{ $s->role=='dean'?'selected':'' }}>Dean</option>
                                    <option value="office-assistant" {{ $s->role=='office-assistant'?'selected':'' }}>Office Assistant</option>
                                    <option value="hod" {{ $s->role=='hod'?'selected':'' }}>HOD</option>
                                    <option value="faculty" {{ $s->role=='faculty'?'selected':'' }}>Faculty</option>
                                    <option value="coordinator" {{ $s->role=='coordinator'?'selected':'' }}>Coordinator</option>
                                    <option value="moderator" {{ $s->role=='moderator'?'selected':'' }}>Moderator</option>
                                </select>
                            </div>
                        </div>

                        <!-- Scope Select -->
                        <div class="mb-3" id="edit_{{ $s->id }}_scope_container">
                            <label class="small fw-bold text-muted mb-1">Scope</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                <select name="scope" id="edit_{{ $s->id }}_scope_select" class="form-select form-control-prefixed" onchange="handleScopeChange('edit_{{ $s->id }}')">
                                    <option value="department" {{ $s->department_id ? 'selected' : '' }}>Department-Specific Scope</option>
                                    <option value="universal" {{ !$s->department_id ? 'selected' : '' }}>Universal / University-Level Scope</option>
                                </select>
                            </div>
                        </div>

                        <!-- Designation Select -->
                        @php
                            $currentDesignation = '';
                            $designationsList = ['main dean', 'associate dean', 'co-dean', 'placement dean', 'primary hod', 'secondary hod', 'temporary hod', 'universal hod'];
                            if (is_array($s->positions)) {
                                foreach ($s->positions as $pos) {
                                    if (in_array(strtolower($pos), $designationsList)) {
                                        $currentDesignation = $pos;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="mb-3 {{ in_array($s->role, ['dean', 'hod']) ? '' : 'd-none' }}" id="edit_{{ $s->id }}_designation_container">
                            <label class="small fw-bold text-muted mb-1">Specific Designation</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-award"></i></span>
                                <select name="designation" id="edit_{{ $s->id }}_designation_select" class="form-select form-control-prefixed" onchange="handleDesignationChange('edit_{{ $s->id }}')">
                                    @if($s->role == 'dean')
                                        <option value="Main Dean" {{ strtolower($currentDesignation) == 'main dean' ? 'selected' : '' }}>Main Dean</option>
                                        <option value="Associate Dean" {{ strtolower($currentDesignation) == 'associate dean' ? 'selected' : '' }}>Associate Dean</option>
                                        <option value="Co-Dean" {{ strtolower($currentDesignation) == 'co-dean' ? 'selected' : '' }}>Co-Dean</option>
                                        <option value="Placement Dean" {{ strtolower($currentDesignation) == 'placement dean' ? 'selected' : '' }}>Placement Dean</option>
                                    @elseif($s->role == 'hod')
                                        <option value="Primary HOD" {{ strtolower($currentDesignation) == 'primary hod' ? 'selected' : '' }}>Primary HOD</option>
                                        <option value="Secondary HOD" {{ strtolower($currentDesignation) == 'secondary hod' ? 'selected' : '' }}>Secondary HOD</option>
                                        <option value="Temporary HOD" {{ strtolower($currentDesignation) == 'temporary hod' ? 'selected' : '' }}>Temporary HOD</option>
                                        <option value="Universal HOD" {{ strtolower($currentDesignation) == 'universal hod' ? 'selected' : '' }}>Universal HOD</option>
                                    @endif
                                </select>
                            </div>
                        </div>

                        <!-- Dean Password Input -->
                        <div class="mb-3 {{ (strtolower($currentDesignation) == 'main dean' && $s->role == 'dean') ? '' : 'd-none' }}" id="edit_{{ $s->id }}_dean_password_container">
                            <label class="small fw-bold text-muted mb-1"><i class="fas fa-key text-danger"></i> Dean Authorization Password</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-key text-danger"></i></span>
                                <input type="password" name="dean_password" id="edit_{{ $s->id }}_dean_password" class="form-control form-control-prefixed" placeholder="Enter Password ('1234@1234')">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Additional Positions</label>
                            <select name="positions[]" class="form-select" multiple size="4" style="height: 100px;">
                                @foreach(['lecturer', 'lab', 'coordinator', 'researcher', 'moderator'] as $pos)
                                    <option value="{{$pos}}" {{ (is_array($s->positions) && in_array($pos, $s->positions)) ? 'selected' : '' }}>{{ ucfirst($pos) }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3" id="edit_{{ $s->id }}_dept_container" style="{{ !$s->department_id ? 'opacity: 0.5;' : '' }}">
                            <label class="small fw-bold text-muted mb-1">Department</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-sitemap"></i></span>
                                <select name="department_id" id="edit_{{ $s->id }}_dept_select" class="form-select form-control-prefixed" {{ $s->department_id ? 'required' : '' }}>
                                    <option value="">-- None / Universal --</option>
                                    @foreach($departments as $d)
                                        <option value="{{ $d->id }}" {{ $s->department_id==$d->id?'selected':'' }}>{{ $d->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row g-2 mb-3">
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-1">Unique Code</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                                    <input name="unique_code" class="form-control form-control-prefixed" value="{{ $s->unique_code }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="small fw-bold text-muted mb-1">Email</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    <input name="email" type="email" class="form-control form-control-prefixed" value="{{ $s->email }}">
                                </div>
                            </div>
                        </div>
                        <hr class="my-3 opacity-10">
                        <div class="mb-1">
                            <label class="small fw-bold text-muted mb-1">Exam Controller Hierarchy</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-file-invoice"></i></span>
                                <select name="exam_role" class="form-select form-control-prefixed">
                                    <option value="none" {{ $s->exam_role=='none'?'selected':'' }}>None</option>
                                    <option value="universal" {{ $s->exam_role=='universal'?'selected':'' }}>Universal Exam Controller</option>
                                    <option value="department" {{ $s->exam_role=='department'?'selected':'' }}>Department Exam Controller</option>
                                    <option value="assistant" {{ $s->exam_role=='assistant'?'selected':'' }}>Assistant Controller</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary rounded-pill px-4" style="background: linear-gradient(135deg, #4f46e5, #7c3aed); border:none;">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirm Modal -->
    <div class="modal fade" id="deleteStaffModal{{$s->id}}" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header py-3" style="background: linear-gradient(135deg,#fee2e2,#fecaca);">
                    <h6 class="modal-title fw-bold text-danger">
                        <i class="fas fa-triangle-exclamation me-2"></i> Confirm Delete
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center py-4">
                    <div class="mb-3">
                        <i class="fas fa-user-slash fa-3x text-danger opacity-75"></i>
                    </div>
                    <p class="fw-semibold mb-1 text-dark">{{ $s->name }}</p>
                    <span class="badge bg-danger-subtle text-danger border border-danger-subtle mb-3">{{ strtoupper($s->role) }}</span>
                    <p class="small text-muted mb-0">This will permanently remove this staff member. This action <strong>cannot be undone</strong>.</p>
                </div>
                <div class="modal-footer py-2 border-0 justify-content-center">
                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <form action="/admin/staff/{{ $s->id }}/delete" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm rounded-pill px-4">
                            <i class="fas fa-trash-alt me-1"></i> Yes, Delete
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Reset Modal -->
    <div class="modal fade" id="resetModal{{$s->id}}" tabindex="-1">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="/admin/staff/reset-staff-password" method="POST">
                    @csrf
                    <input type="hidden" name="staff_id" value="{{ $s->id }}">
                    <div class="modal-header py-3 text-white" style="background: linear-gradient(135deg, #d97706, #f59e0b);">
                        <h6 class="modal-title fw-bold"><i class="fas fa-key me-2"></i>Reset Credentials</h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-3">
                        <div class="mb-2">
                            <label class="small fw-bold text-muted mb-1">Email</label>
                            <input type="email" name="email" class="form-control form-control-sm" value="{{ $s->email }}" placeholder="New Email" required>
                        </div>
                        <div>
                            <label class="small fw-bold text-muted mb-1">New Password</label>
                            <input type="text" name="password" class="form-control form-control-sm" placeholder="New Password" required minlength="4">
                        </div>
                    </div>
                    <div class="modal-footer py-2 border-0">
                        <button class="btn btn-warning btn-sm w-100 rounded-pill text-white fw-bold" style="background: #d97706; border:none;">Update Credentials</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Allocate Course Modal -->
    <div class="modal fade" id="allocateModal{{$s->id}}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <form action="/admin/staff/allocate" method="POST">
                    @csrf
                    <input type="hidden" name="staff_id" value="{{ $s->id }}">
                    <div class="modal-header py-3 text-white" style="background: linear-gradient(135deg, #0891b2, #0ea5e9);">
                        <h5 class="modal-title fw-bold"><i class="fas fa-book-open me-2"></i>Allocate Course: {{ $s->name }}</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body p-4">
                        <div class="mb-3">
                            <label class="small fw-bold text-muted mb-1">Select Course</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-book"></i></span>
                                <select name="course_id" class="form-select form-control-prefixed" required>
                                    <option value="">-- Choose Course --</option>
                                    @foreach($courses as $c)
                                        <option value="{{ $c->id }}">{{ $c->title }} ({{ $c->code }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-1">
                            <label class="small fw-bold text-muted mb-1">Class Section</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="fas fa-chalkboard"></i></span>
                                <input name="class_section" class="form-control form-control-prefixed" placeholder="e.g. 01, B.Tech-A" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-0">
                        <button type="button" class="btn btn-secondary rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-info rounded-pill px-4 text-white fw-bold" style="background: #0891b2; border:none;">Allocate Course</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach

{{-- Admin Verification Modal for revealing passwords --}}
<div class="modal fade" id="revealVerifyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header py-3 text-white" style="background: linear-gradient(135deg, #ea580c, #f97316);">
                <h6 class="modal-title fw-bold">
                    <i class="fas fa-user-shield me-2"></i> Security Verification
                </h6>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="mb-3 animate-pulse">
                    <i class="fas fa-key fa-3x text-warning"></i>
                </div>
                <p class="small text-muted mb-3">Please enter the administrator password to reveal all credentials.</p>
                <div class="form-group position-relative">
                    <input type="password" id="adminPwdInput" class="form-control rounded-pill text-center font-monospace" placeholder="••••••••" style="border: 2px solid #cbd5e1; font-size: 1.1rem; letter-spacing: 0.1em;">
                    <div class="invalid-feedback mt-2" id="adminPwdError">Invalid Administrator Password.</div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" id="submitRevealBtn" class="btn btn-warning w-100 fw-bold rounded-pill text-white" style="background: linear-gradient(135deg, #ea580c, #f97316); border: none;">Verify & Reveal</button>
            </div>
        </div>
    </div>
</div>

<form id="bulkDeleteForm" action="/admin/staff/bulk-delete" method="POST" class="d-none">
    @csrf
    <div id="bulkDeleteInputs"></div>
</form>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // ----------------------------------------
    // Search Live Filter Logic
    // ----------------------------------------
    const rosterSearch = document.getElementById('rosterSearch');
    if (rosterSearch) {
        rosterSearch.addEventListener('input', function () {
            const query = this.value.toLowerCase().trim();
            document.querySelectorAll('tbody tr').forEach(row => {
                const name = row.querySelector('.fw-bold.text-dark')?.textContent.toLowerCase() || '';
                const code = row.querySelector('.code-badge')?.textContent.toLowerCase() || '';
                const email = row.querySelector('a[href^="mailto:"]')?.textContent.toLowerCase() || '';
                const role = row.querySelector('.baps-badge')?.textContent.toLowerCase() || '';
                
                if (name.includes(query) || code.includes(query) || email.includes(query) || role.includes(query)) {
                    row.style.setProperty('display', '', 'important');
                } else {
                    row.style.setProperty('display', 'none', 'important');
                }
            });
        });
    }

    // ----------------------------------------
    // Password Reveal Logic
    // ----------------------------------------
    const revealBtn = document.getElementById('revealPasswordsBtn');
    const revealModal = new bootstrap.Modal(document.getElementById('revealVerifyModal'));
    const adminPwdInput = document.getElementById('adminPwdInput');
    const submitRevealBtn = document.getElementById('submitRevealBtn');
    const adminPwdError = document.getElementById('adminPwdError');
    let passwordsUnlocked = false;

    if (revealBtn) {
        revealBtn.addEventListener('click', function () {
            if (passwordsUnlocked) {
                lockPasswords();
            } else {
                adminPwdInput.value = '';
                adminPwdInput.classList.remove('is-invalid');
                adminPwdError.style.display = 'none';
                revealModal.show();
            }
        });
    }

    if (submitRevealBtn) {
        submitRevealBtn.addEventListener('click', verifyAdminPassword);
    }
    if (adminPwdInput) {
        adminPwdInput.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                verifyAdminPassword();
            }
        });
    }

    function verifyAdminPassword() {
        const pwd = adminPwdInput.value.trim();
        if (pwd === 'BAPS2026ADMIN') {
            unlockPasswords();
            revealModal.hide();
            if (typeof showBapsToast === 'function') {
                showBapsToast('Administrator verified. Passwords revealed!', 'success');
            }
        } else {
            adminPwdInput.classList.add('is-invalid');
            adminPwdError.style.display = 'block';
        }
    }

    function unlockPasswords() {
        passwordsUnlocked = true;
        document.querySelectorAll('.password-masked').forEach(span => {
            const pwd = span.dataset.pwd;
            span.innerHTML = `<i class="fas fa-eye text-warning me-1"></i><span class="fw-bold font-monospace px-2 py-0.5 rounded" style="background: rgba(234,179,8,0.15); color: #b45309;">${pwd}</span>`;
            span.classList.add('password-revealed-glow');
        });
        
        revealBtn.innerHTML = '<i class="fas fa-eye-slash"></i> Hide Passwords';
        revealBtn.className = 'btn btn-success btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-2';
        revealBtn.style.setProperty('background', 'linear-gradient(135deg, #10b981, #059669)', 'important');
        revealBtn.style.setProperty('border', 'none', 'important');
    }

    function lockPasswords() {
        passwordsUnlocked = false;
        document.querySelectorAll('.password-masked').forEach(span => {
            span.innerHTML = '<i class="fas fa-lock text-warning opacity-75 me-1" style="font-size: 0.75rem;"></i>••••••••';
            span.classList.remove('password-revealed-glow');
        });
        
        revealBtn.innerHTML = '<i class="fas fa-key"></i> Reveal Passwords';
        revealBtn.className = 'btn btn-outline-warning btn-sm rounded-pill px-3 py-1.5 fw-bold d-inline-flex align-items-center gap-2';
        revealBtn.style.removeProperty('background');
        revealBtn.style.removeProperty('border');
        if (typeof showBapsToast === 'function') {
            showBapsToast('Credentials masked.', 'info');
        }
    }

    // ----------------------------------------
    // Bulk Select & Delete Checkboxes Logic
    // ----------------------------------------
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.staff-checkbox');
    const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
    const selectedCount = document.getElementById('selectedCount');
    const bulkDeleteForm = document.getElementById('bulkDeleteForm');
    const bulkDeleteInputs = document.getElementById('bulkDeleteInputs');

    function updateBulkDeleteButton() {
        const checked = document.querySelectorAll('.staff-checkbox:checked');
        selectedCount.textContent = checked.length;
        if (checked.length > 0) {
            bulkDeleteBtn.classList.remove('d-none');
        } else {
            bulkDeleteBtn.classList.add('d-none');
        }
    }

    if (selectAll) {
        selectAll.addEventListener('change', function () {
            checkboxes.forEach(cb => cb.checked = selectAll.checked);
            updateBulkDeleteButton();
        });
    }

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function () {
            updateBulkDeleteButton();
            const allChecked = document.querySelectorAll('.staff-checkbox:checked').length === checkboxes.length;
            if (selectAll) selectAll.checked = allChecked;
        });
    });

    if (bulkDeleteBtn) {
        bulkDeleteBtn.addEventListener('click', function () {
            if (confirm('Are you sure you want to delete the selected staff members?')) {
                bulkDeleteInputs.innerHTML = '';
                const checked = document.querySelectorAll('.staff-checkbox:checked');
                checked.forEach(cb => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'ids[]';
                    input.value = cb.dataset.id;
                    bulkDeleteInputs.appendChild(input);
                });
                bulkDeleteForm.submit();
            }
        });
    }
});

// ----------------------------------------
// Dynamic Designation & Scope Handling
// ----------------------------------------
window.handleRoleChange = function(prefix) {
    const roleSelect = document.getElementById(prefix + '_role_select');
    const designationContainer = document.getElementById(prefix + '_designation_container');
    const designationSelect = document.getElementById(prefix + '_designation_select');
    const scopeSelect = document.getElementById(prefix + '_scope_select');

    if (!roleSelect) return;

    const role = roleSelect.value;

    // Reset designations
    if (designationSelect) {
        designationSelect.innerHTML = '';
    }
    if (designationContainer) {
        designationContainer.classList.add('d-none');
    }
    
    if (role === 'dean') {
        if (designationContainer && designationSelect) {
            designationContainer.classList.remove('d-none');
            designationSelect.innerHTML = `
                <option value="Main Dean">Main Dean</option>
                <option value="Associate Dean">Associate Dean</option>
                <option value="Co-Dean">Co-Dean</option>
                <option value="Placement Dean">Placement Dean</option>
            `;
        }
        // Default Main Dean to Universal scope
        if (scopeSelect) {
            scopeSelect.value = 'universal';
            window.handleScopeChange(prefix);
        }
    } else if (role === 'hod') {
        if (designationContainer && designationSelect) {
            designationContainer.classList.remove('d-none');
            designationSelect.innerHTML = `
                <option value="Primary HOD">Primary HOD</option>
                <option value="Secondary HOD">Secondary HOD</option>
                <option value="Temporary HOD">Temporary HOD</option>
                <option value="Universal HOD">Universal HOD</option>
            `;
        }
        // Default HOD to Department-specific scope
        if (scopeSelect) {
            scopeSelect.value = 'department';
            window.handleScopeChange(prefix);
        }
    } else if (['president', 'vice-president', 'provost', 'registrar', 'director', 'board-member', 'external-coordinator', 'admin'].includes(role)) {
        // These are universal roles by definition
        if (scopeSelect) {
            scopeSelect.value = 'universal';
            window.handleScopeChange(prefix);
        }
    } else {
        // Default other roles to Department scope
        if (scopeSelect) {
            scopeSelect.value = 'department';
            window.handleScopeChange(prefix);
        }
    }
    window.handleDesignationChange(prefix);
};

window.handleScopeChange = function(prefix) {
    const scopeSelect = document.getElementById(prefix + '_scope_select');
    const deptSelect = document.getElementById(prefix + '_dept_select');
    const deptContainer = document.getElementById(prefix + '_dept_container');

    if (!scopeSelect || !deptSelect) return;

    if (scopeSelect.value === 'universal') {
        deptSelect.value = ''; // Reset department selection
        deptSelect.removeAttribute('required');
        if (deptContainer) {
            deptContainer.style.opacity = '0.5';
        }
    } else {
        deptSelect.setAttribute('required', 'required');
        if (deptContainer) {
            deptContainer.style.opacity = '1';
        }
    }
};

window.handleDesignationChange = function(prefix) {
    const designationSelect = document.getElementById(prefix + '_designation_select');
    const pwdContainer = document.getElementById(prefix + '_dean_password_container');
    const pwdInput = document.getElementById(prefix + '_dean_password');

    if (!designationSelect || !pwdContainer) return;

    if (designationSelect.value === 'Main Dean') {
        pwdContainer.classList.remove('d-none');
        if (pwdInput) pwdInput.setAttribute('required', 'required');
    } else {
        pwdContainer.classList.add('d-none');
        if (pwdInput) {
            pwdInput.removeAttribute('required');
            pwdInput.value = '';
        }
    }
};
</script>

@endsection
