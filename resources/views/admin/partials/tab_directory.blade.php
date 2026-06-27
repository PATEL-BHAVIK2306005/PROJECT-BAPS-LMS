@if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'cr']))
<div class="tab-pane fade" id="tab-directory" role="tabpanel">
    <div class="row g-4">
        <div class="col-12 col-sm-6 col-lg-3"><a href="/admin/departments" class="action-btn py-4 shadow-sm"><i class="fas fa-building text-secondary me-2 fs-4"></i> Departments</a></div>
        <div class="col-12 col-sm-6 col-lg-3"><a href="/admin/staff" class="action-btn py-4 shadow-sm"><i class="fas fa-chalkboard-teacher text-info me-2 fs-4"></i> Staff Directory</a></div>
        <div class="col-12 col-sm-6 col-lg-3"><a href="/admin/students" class="action-btn py-4 shadow-sm"><i class="fas fa-user-graduate text-success me-2 fs-4"></i> Student Database</a></div>
        <div class="col-12 col-sm-6 col-lg-3"><a href="/admin/parents" class="action-btn py-4 shadow-sm"><i class="fas fa-user-shield text-danger me-2 fs-4"></i> Parent Directory</a></div>
    </div>
</div>
@endif
