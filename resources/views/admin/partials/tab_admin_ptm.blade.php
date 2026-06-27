@php
    $students = \App\Models\User::where('role', 'student')->orderBy('name')->get();
    $reports = \App\Models\PtmReport::with('student')->orderBy('created_at', 'desc')->get();
@endphp

<div class="tab-pane fade" id="tab-admin-ptm" role="tabpanel">
    <!-- PTM Hub Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #11111b !important; border-left: 5px solid #ffd700 !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center gap-3">
                <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; background: #1e1e2e !important; color: #ffd700 !important;">
                    <i class="fas fa-comments"></i>
                </div>
                <div>
                    <h4 class="fw-bold mb-1" style="color: #ffd700 !important;">Parent-Teacher Meeting (PTM) & Child Report Hub</h4>
                    <div class="small fw-semibold" style="color: #ffffff !important; opacity: 0.85;">Submit behavioral and academic warnings directly to parents, and track parent responses.</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Side: Submit Feedback Form -->
        <div class="col-12 col-md-5">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-plus text-primary me-2"></i> Post Child Progress Report</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form action="/admin/ptm/report" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Select Student</label>
                            <select name="student_id" class="form-select select2-enable" required>
                                <option value="">-- Choose Student --</option>
                                @foreach($students as $stud)
                                    <option value="{{ $stud->id }}">{{ $stud->name }} ({{ $stud->enrollment_no }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Report Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Academic">Academic Performance</option>
                                <option value="Behavior">Behavioral Standing</option>
                                <option value="Attendance">Attendance Warning</option>
                                <option value="Exams">Examination Standing</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Subject Summary</label>
                            <input type="text" name="subject" class="form-control" placeholder="e.g. Critical attendance warning: below 75%" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Detailed Comments / Action Plan</label>
                            <textarea name="report_content" class="form-control" rows="4" placeholder="Explain the feedback or disciplinary concern clearly..." required></textarea>
                        </div>

                        <button type="submit" class="btn btn-danger w-100 fw-bold py-2.5 rounded-3"><i class="fas fa-paper-plane me-1"></i> Send Report to Parent</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Side: List of PTM Reports & Parent Replies -->
        <div class="col-12 col-md-7">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-list text-primary me-2"></i> PTM Reports & Parent Responses</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    @if($reports->isEmpty())
                        <div class="alert alert-light border text-center py-4 text-muted small fw-bold">No PTM reports posted yet.</div>
                    @else
                        <div class="d-flex flex-column gap-3" style="max-height: 550px; overflow-y: auto;">
                            @foreach($reports as $rep)
                                <div class="border rounded-3 p-3 bg-light">
                                    <div class="d-flex justify-content-between align-items-start gap-2 mb-2 flex-wrap">
                                        <div>
                                            <span class="badge bg-danger text-white px-2 py-0.5 rounded small text-uppercase" style="font-size: 0.65rem;">{{ $rep->category }}</span>
                                            <strong class="ms-1 text-dark">{{ $rep->subject }}</strong>
                                        </div>
                                        <span class="small text-muted">{{ date('d M Y H:i', strtotime($rep->created_at)) }}</span>
                                    </div>
                                    <div class="small text-muted mb-2">
                                        Child: <strong>{{ $rep->student->name ?? 'Unknown Student' }}</strong> ({{ $rep->student->enrollment_no ?? 'N/A' }}) | 
                                        Posted by: <strong>{{ $rep->created_by_name }} ({{ ucfirst($rep->created_by_role) }})</strong>
                                    </div>
                                    <div class="p-2 border rounded bg-white small text-muted mb-3">{{ $rep->report_content }}</div>

                                    <!-- Parent Reply -->
                                    <div class="border-top pt-2">
                                        @if($rep->parent_reply)
                                            <div class="p-2.5 rounded bg-success-subtle border border-success-subtle small text-dark">
                                                <div class="d-flex justify-content-between align-items-center mb-1">
                                                    <span class="fw-bold text-success"><i class="fas fa-user-shield me-1"></i> Parent Reply:</span>
                                                    <span class="text-muted small">{{ date('d M Y H:i', strtotime($rep->parent_replied_at)) }}</span>
                                                </div>
                                                <div>{{ $rep->parent_reply }}</div>
                                            </div>
                                        @else
                                            <span class="text-warning small fw-bold"><i class="fas fa-clock me-1 animate-pulse"></i> Awaiting Parent Response...</span>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
