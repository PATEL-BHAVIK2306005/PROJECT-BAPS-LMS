@if(in_array(session('user_role'), ['admin', 'cr', 'hod', 'dean', 'office-assistant']) || session('staff_name') == 'Rajunakum Sir')
<div class="tab-pane fade" id="tab-exams" role="tabpanel">
    <div class="row g-4">
        <div class="col-12 col-sm-6 col-lg-4"><a class="action-btn py-4 shadow-sm" href="/admin/exam/quiz-management"><i class="fas fa-tasks text-primary me-2 fs-4"></i> Quiz Management</a></div>
        <div class="col-12 col-sm-6 col-lg-4"><a class="action-btn py-4 shadow-sm" href="/admin/exam/question-bank"><i class="fas fa-database text-info me-2 fs-4"></i> Question Bank</a></div>
        <div class="col-12 col-sm-6 col-lg-4"><a class="action-btn py-4 shadow-sm" href="/admin/exam/schedule"><i class="fas fa-calendar-check text-success me-2 fs-4"></i> Exam Schedule</a></div>
        <div class="col-12 col-sm-6 col-lg-4"><a class="action-btn py-4 shadow-sm" href="/admin/exam/live-proctoring"><i class="fas fa-video text-danger me-2 fs-4"></i> Live Proctoring</a></div>
        <div class="col-12 col-sm-6 col-lg-4"><a class="action-btn py-4 shadow-sm" href="/admin/exam/forms"><i class="fas fa-id-card text-purple me-2 fs-4" style="color: #9333ea;"></i> Admit Cards & Forms</a></div>
        <div class="col-12 col-sm-6 col-lg-4"><a class="action-btn py-4 shadow-sm" href="/admin/chat"><i class="fas fa-comments text-info me-2 fs-4"></i> Comms & Chat Center</a></div>
    </div>
</div>
@endif
