@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="fas fa-user-shield me-2 text-danger"></i> Parent Directory & Registry</h3>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

@if(session('success'))
    <div class="alert alert-success py-2 px-3 mb-4 small fw-bold shadow-sm rounded-3">
        <i class="fas fa-check-circle me-1"></i> {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div class="alert alert-danger py-2 px-3 mb-4 small fw-bold shadow-sm rounded-3">
        <i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}
    </div>
@endif
@if($errors->any())
    <div class="alert alert-danger py-2 px-3 mb-4 small fw-bold shadow-sm rounded-3">
        <i class="fas fa-exclamation-triangle me-1"></i>
        @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
        @endforeach
    </div>
@endif

<!-- Analytics & Quick Stats -->
<div class="row g-3 mb-4">
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-3 border-0 shadow-sm d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.9); border-left: 4px solid #dc2626 !important;">
            <div class="p-3 bg-danger-subtle rounded-3 text-danger d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="fas fa-user-shield fs-4"></i>
            </div>
            <div>
                <div class="small text-muted fw-semibold uppercase tracking-wider" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Registered Parents</div>
                <h4 class="mb-0 fw-extrabold text-dark">{{ $parents->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-3 border-0 shadow-sm d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.9); border-left: 4px solid #2563eb !important;">
            <div class="p-3 bg-primary-subtle rounded-3 text-primary d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="fas fa-link fs-4"></i>
            </div>
            <div>
                <div class="small text-muted fw-semibold uppercase tracking-wider" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Linked Student Profiles</div>
                <h4 class="mb-0 fw-extrabold text-dark">{{ $parents->filter(fn($p) => $p->child !== null)->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-4">
        <div class="glass-card p-3 border-0 shadow-sm d-flex align-items-center gap-3" style="background: rgba(255, 255, 255, 0.9); border-left: 4px solid #d97706 !important;">
            <div class="p-3 bg-warning-subtle rounded-3 text-warning d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                <i class="fas fa-unlink fs-4"></i>
            </div>
            <div>
                <div class="small text-muted fw-semibold uppercase tracking-wider" style="font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.5px;">Unlinked Accounts</div>
                <h4 class="mb-0 fw-extrabold text-dark">{{ $parents->filter(fn($p) => $p->child === null)->count() }}</h4>
            </div>
        </div>
    </div>
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
        box-shadow: 0 10px 25px -5px rgba(220, 38, 38, 0.08), 0 8px 16px -6px rgba(220, 38, 38, 0.05) !important;
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
        border-left: 4px solid #dc2626 !important;
        transition: border-color 0.25s ease !important;
    }
    .table-cards tbody tr.unlinked-parent-row td:first-child {
        border-left-color: #d97706 !important;
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
        border-radius: 8px !important;
        padding: 6px 12px !important;
    }
    .avatar-container {
        position: relative;
        width: 48px;
        height: 48px;
        border-radius: 50%;
        padding: 2px;
        background: linear-gradient(135deg, #dc2626 0%, #2563eb 100%);
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
    .form-control:focus, .form-select:focus {
        border-color: #dc2626 !important;
        box-shadow: 0 0 0 4px rgba(220, 38, 38, 0.15) !important;
    }
    .actions-collapse-row { transition: all 0.3s ease !important; }
    .actions-collapse-row:not(.show-row) { display: none !important; }
    .actions-collapse-row.show-row { display: table-row !important; }
    .hover-shadow:hover {
        background-color: #fee2e2 !important;
        border-color: #dc2626 !important;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.08) !important;
        transform: translateY(-1px);
    }
</style>

<div class="row g-4">
    <!-- Registration Section -->
    <div class="col-lg-4">
        <h5 class="fw-bold mb-3"><i class="fas fa-user-plus text-danger me-2"></i> Register New Parent</h5>
        
        <div class="glass-card p-4 border-0 shadow-sm" style="background: rgba(255, 255, 255, 0.9);">
            <form action="/admin/parents" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Parent Full Name</label>
                    <input type="text" name="name" class="form-control" required placeholder="e.g. Mukesh Patel" value="{{ old('name') }}">
                </div>
                
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Parent Email Address</label>
                    <input type="email" name="email" class="form-control" required placeholder="e.g. mukesh@gmail.com" value="{{ old('email') }}">
                </div>

                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">Select Child Student</label>
                    <select name="student_enrollment" class="form-select" required>
                        <option value="">-- Choose Student --</option>
                        @foreach($students as $st)
                            <option value="{{ $st->enrollment_no }}" {{ old('student_enrollment') == $st->enrollment_no ? 'selected' : '' }}>
                                {{ $st->name }} ({{ $st->enrollment_no }})
                            </option>
                        @endforeach
                    </select>
                    <div class="form-text text-muted small">The parent will be instantly linked to this student profile.</div>
                </div>

                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">Portal Login Password</label>
                    <input type="text" name="password" class="form-control" value="parent123" required>
                    <div class="form-text text-muted small">Default access password. Parents can reset this later from their dashboard.</div>
                </div>
                
                <button type="submit" class="btn btn-danger w-100 py-2 fw-bold shadow-sm rounded-pill" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); border: none; color: white;">
                    <i class="fas fa-user-plus me-1"></i> Register Parent
                </button>
            </form>
        </div>
    </div>

    <!-- Parent Listing Section -->
    <div class="col-lg-8">
        <!-- Roster Search & Filter Utilities -->
        <div class="glass-card p-3 border-0 shadow-sm mb-4" style="background: rgba(255, 255, 255, 0.9);">
            <div class="row g-2 align-items-center">
                <!-- Search Input -->
                <div class="col-md-6">
                    <div class="position-relative">
                        <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" id="parentSearch" class="form-control ps-5 border-light-subtle" placeholder="Search by name, email, child, enrollment..." style="border-radius: 10px !important;">
                    </div>
                </div>
                <!-- Child Department Filter -->
                <div class="col-md-3">
                    <select id="filterChildDepartment" class="form-select border-light-subtle" style="border-radius: 10px !important;">
                        <option value="">All Child Departments</option>
                        @foreach($departments as $d)
                            <option value="{{ $d->name }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                </div>
                <!-- Link Status Filter -->
                <div class="col-md-3">
                    <select id="filterLinkStatus" class="form-select border-light-subtle" style="border-radius: 10px !important;">
                        <option value="">All Accounts</option>
                        <option value="linked">Linked Profiles</option>
                        <option value="unlinked">Unlinked Profiles</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Registered Parents Roster</h5>
            <span class="badge bg-light text-secondary border px-3 py-2 rounded-pill small" id="parentFilteredCount">Showing {{ $parents->count() }} parent accounts</span>
        </div>

        <div class="border-0 p-0 shadow-none text-nowrap bg-transparent">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-hover table-cards mb-0 align-middle">
                    <thead class="text-muted">
                        <tr>
                            <th class="ps-4"><i class="fas fa-user-shield text-danger me-1"></i> Parent Profile</th>
                            <th><i class="fas fa-link text-primary me-1"></i> Associated Child</th>
                            <th><i class="fas fa-building text-info me-1"></i> Child's Dept</th>
                            <th><i class="fas fa-calendar-alt text-warning me-1"></i> Registered Date</th>
                            <th class="text-end pe-4"><i class="fas fa-sliders-h text-secondary me-1"></i> Management</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                        @forelse($parents as $parent)
                        @php
                            $isLinked = $parent->child !== null;
                        @endphp
                        <tr class="parent-row @if(!$isLinked) unlinked-parent-row @endif" 
                            id="parentRow{{ $parent->id }}"
                            data-parent-name="{{ strtolower($parent->name) }}" 
                            data-parent-email="{{ strtolower($parent->email) }}" 
                            data-child-name="{{ $isLinked ? strtolower($parent->child->name) : '' }}" 
                            data-child-enrollment="{{ $isLinked ? strtolower($parent->child->enrollment_no ?? '') : '' }}"
                            data-child-department="{{ $isLinked ? ($parent->child->department->name ?? '') : '' }}" 
                            data-link-status="{{ $isLinked ? 'linked' : 'unlinked' }}">
                            
                            <td class="ps-4">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-container me-3">
                                        <div class="avatar-img-wrapper">
                                            <img src="https://ui-avatars.com/api/?name={{ urlencode($parent->name) }}&background={{ $isLinked ? 'dc2626' : 'd97706' }}&color=fff&size=50" alt="{{ $parent->name }}">
                                        </div>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark">
                                            {{ $parent->name }}
                                        </div>
                                        <div class="small text-muted" style="font-size: 0.8rem;">{{ $parent->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                @if($isLinked)
                                    <div class="d-flex flex-column align-items-start gap-1">
                                        <span class="fw-bold text-dark">{{ $parent->child->name }}</span>
                                        <div class="d-flex gap-2 align-items-center">
                                            <span class="badge badge-enrollment">
                                                {{ $parent->child->enrollment_no ?? 'N/A' }}
                                            </span>
                                            <a href="/admin/students?search={{ urlencode($parent->child->enrollment_no) }}" 
                                               class="btn btn-xs btn-outline-primary py-0.5 px-2 rounded-pill fw-bold text-decoration-none shadow-sm text-center" 
                                               style="font-size: 0.72rem; padding: 2px 8px; border: 1px solid #2563eb; color: #2563eb; background: white; transition: all 0.2s;"
                                               title="View student control panel & mapped details"
                                               onmouseover="this.style.background='#2563eb'; this.style.color='white';"
                                               onmouseout="this.style.background='white'; this.style.color='#2563eb';">
                                                <i class="fas fa-external-link-alt me-1" style="font-size: 0.65rem;"></i> View
                                            </a>
                                        </div>
                                    </div>
                                @else
                                    <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2.5 py-1.5 rounded-pill">
                                        <i class="fas fa-unlink me-1"></i> Unlinked Account
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if($isLinked && $parent->child->department)
                                    <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1.5 rounded-pill fw-semibold">
                                        {{ $parent->child->department->name }}
                                    </span>
                                @else
                                    <span class="text-muted small">N/A</span>
                                @endif
                            </td>
                            <td>
                                <span class="small text-muted fw-bold">
                                    {{ $parent->created_at ? $parent->created_at->format('d M Y') : 'N/A' }}
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <button class="btn btn-sm btn-actions-dropdown px-3 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#actionsCollapse{{ $parent->id }}" aria-expanded="false">
                                    <i class="fas fa-cog me-1 text-muted"></i> Actions <i class="fas fa-chevron-down ms-1 small"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Collapsible Management Panel for parent -->
                        <tr class="actions-collapse-row" id="actionsRow{{ $parent->id }}">
                            <td colspan="5" class="p-0 border-0">
                                <div class="collapse" id="actionsCollapse{{ $parent->id }}" data-parent-id="{{ $parent->id }}">
                                    <div class="p-4" style="background: rgba(248, 250, 252, 0.9) !important; border-radius: 12px !important; border: 1px solid #e2e8f0 !important; margin: 10px 15px 15px 15px; box-shadow: inset 0 2px 8px rgba(0,0,0,0.02) !important;">
                                        <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                            <span class="small fw-extrabold text-uppercase tracking-wider text-muted"><i class="fas fa-sliders-h me-1 text-danger"></i> Parent Control Panel: {{ $parent->name }}</span>
                                            <button type="button" class="btn-close" style="font-size: 0.75rem;" data-bs-toggle="collapse" data-bs-target="#actionsCollapse{{ $parent->id }}"></button>
                                        </div>
                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow" data-bs-toggle="modal" data-bs-target="#editParentModal{{ $parent->id }}">
                                                <i class="fas fa-edit text-primary me-1"></i> Edit Details
                                            </button>
                                            <button type="button" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow" data-bs-toggle="modal" data-bs-target="#resetParentPasswordModal{{ $parent->id }}">
                                                <i class="fas fa-key text-warning me-1"></i> Reset Password
                                            </button>
                                            <a href="mailto:{{ $parent->email }}" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-dark hover-shadow">
                                                <i class="fas fa-paper-plane text-info me-1"></i> Send Email
                                            </a>
                                            <form action="/admin/parents/{{ $parent->id }}/delete" method="POST" onsubmit="return confirm('Are you sure you want to permanently delete parent account {{ $parent->name }}? This action cannot be undone.')" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-light border rounded-pill px-3 py-1.5 fw-bold text-danger hover-shadow">
                                                    <i class="fas fa-trash-alt me-1"></i> Delete Account
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="no-records-row">
                            <td colspan="5" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center gap-2 py-4">
                                    <i class="fas fa-user-shield fs-1 text-danger opacity-25 mb-2"></i>
                                    <h6 class="fw-bold mb-0 text-dark">No Parent Accounts Registered</h6>
                                    <span class="small text-muted">Use the registration panel on the left to add a new parent profile.</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3 d-flex justify-content-between align-items-center mt-3 bg-white shadow-sm rounded-4 border">
                <span class="small text-muted fw-bold" id="parentShowingText">Showing {{ $parents->count() }} parent records</span>
                <button class="btn btn-sm btn-outline-secondary rounded-pill px-3" onclick="document.getElementById('parentSearch').value=''; document.getElementById('filterChildDepartment').value=''; document.getElementById('filterLinkStatus').value=''; document.getElementById('parentSearch').dispatchEvent(new Event('input'));">Reset Filters</button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Parent Details Modals -->
@foreach($parents as $parent)
<div class="modal fade" id="editParentModal{{ $parent->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold"><i class="fas fa-user-edit text-danger me-2"></i> Edit Parent Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/parents/{{ $parent->id }}/update" method="POST">
                @csrf
                <div class="modal-body pb-0">
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">Parent / Guardian Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $parent->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold">Parent Email Address</label>
                        <input type="email" name="email" class="form-control" value="{{ $parent->email }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="small text-muted fw-bold">Associated Student Child</label>
                        <select name="student_enrollment" class="form-select font-monospace" required>
                            <option value="">-- Choose Student --</option>
                            @foreach($students as $st)
                                <option value="{{ $st->enrollment_no }}" {{ ($parent->child && $parent->child->enrollment_no == $st->enrollment_no) ? 'selected' : '' }}>
                                    {{ $st->name }} ({{ $st->enrollment_no }})
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text text-muted small">Update the child student record linked to this parent account.</div>
                    </div>
                </div>
                <div class="modal-footer border-0 pt-0 bg-light-subtle rounded-bottom-4">
                    <button type="button" class="btn btn-light rounded-pill px-4 border shadow-sm" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm" style="background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%); border: none; color: white;">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<!-- Reset Parent Password Modals -->
@foreach($parents as $parent)
<div class="modal fade" id="resetParentPasswordModal{{ $parent->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold"><i class="fas fa-key text-warning me-2"></i> Reset Parent Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="/admin/parents/{{ $parent->id }}/password" method="POST">
                @csrf
                <div class="modal-body">
                    <p class="text-muted small mb-4">Resetting secure password for parent <strong class="text-dark">{{ $parent->name }}</strong>.</p>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-dark">New Password</label>
                        <input type="text" name="password" class="form-control font-monospace form-control-lg text-center fw-bold text-danger" required minlength="4" placeholder="Enter new password">
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
@endforeach

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('parentSearch');
    const deptFilter = document.getElementById('filterChildDepartment');
    const statusFilter = document.getElementById('filterLinkStatus');
    const rows = document.querySelectorAll('.parent-row');
    const countSpan = document.getElementById('parentFilteredCount');
    const countShowingText = document.getElementById('parentShowingText');

    function filterParents() {
        if (!searchInput) return;
        const query = searchInput.value.toLowerCase().trim();
        const deptValue = deptFilter ? deptFilter.value.toLowerCase().trim() : '';
        const statusValue = statusFilter ? statusFilter.value : '';
        let visibleCount = 0;

        rows.forEach(row => {
            const pName = row.getAttribute('data-parent-name') || '';
            const pEmail = row.getAttribute('data-parent-email') || '';
            const cName = row.getAttribute('data-child-name') || '';
            const cEnrollment = row.getAttribute('data-child-enrollment') || '';
            const cDept = (row.getAttribute('data-child-department') || '').toLowerCase().trim();
            const linkStatus = row.getAttribute('data-link-status') || '';

            const matchesQuery = !query || 
                pName.includes(query) || 
                pEmail.includes(query) || 
                cName.includes(query) || 
                cEnrollment.includes(query);

            const matchesDept = !deptValue || cDept === deptValue;
            
            const matchesStatus = !statusValue || linkStatus === statusValue;

            if (matchesQuery && matchesDept && matchesStatus) {
                row.style.setProperty('display', '', 'important');
                visibleCount++;
            } else {
                row.style.setProperty('display', 'none', 'important');
                
                // Hide and collapse the action row
                const parentId = row.id.replace('parentRow', '');
                const actionsRow = document.getElementById('actionsRow' + parentId);
                const collapseDiv = document.getElementById('actionsCollapse' + parentId);
                if (actionsRow) {
                    actionsRow.classList.remove('show-row');
                }
                if (collapseDiv) {
                    collapseDiv.classList.remove('show');
                }
            }
        });

        if (countSpan) {
            countSpan.textContent = `Showing ${visibleCount} of ${rows.length} parent accounts`;
        }
        if (countShowingText) {
            countShowingText.textContent = `Showing ${visibleCount} parent records`;
        }
    }

    if (searchInput) searchInput.addEventListener('input', filterParents);
    if (deptFilter) deptFilter.addEventListener('change', filterParents);
    if (statusFilter) statusFilter.addEventListener('change', filterParents);

    // Bootstrap collapse show/hide events to toggle row visibility class
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
