@extends('layouts.app')
@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center bg-dark rounded-4 p-4 text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);">
            <div class="position-relative z-1">
                <h3 class="fw-bold mb-1"><i class="fas fa-tasks text-warning me-2"></i> Assignments Section</h3>
                <p class="mb-0 opacity-75">Your official portal for assignments, workbooks, and verified external credentials.</p>
            </div>
            <i class="fas fa-award fa-6x position-absolute end-0 bottom-0 opacity-10 me-4 mb-n2"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: My Certifications & Submissions -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0"><i class="fas fa-certificate text-primary me-2"></i> My Certification Vault</h5>
                <button class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm" data-bs-toggle="modal" data-bs-target="#uploadCertModal">
                    <i class="fas fa-plus me-1"></i> Add Credential
                </button>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-4">Platform</th>
                                <th>Certificate Title</th>
                                <th>Date</th>
                                <th class="text-end pe-4">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myCerts as $cert)
                            <tr>
                                <td class="ps-4 py-3">
                                    <span class="badge bg-dark rounded-pill px-3">{{ $cert->platform }}</span>
                                </td>
                                <td class="fw-bold text-dark">{{ $cert->title }}</td>
                                <td class="text-muted small">{{ $cert->issue_date ?? 'N/A' }}</td>
                                <td class="text-end pe-4">
                                    @if($cert->verification_status == 'pending')
                                        <span class="badge bg-soft-warning text-warning rounded-pill px-3"><i class="fas fa-clock me-1"></i> Pending</span>
                                    @elseif($cert->verification_status == 'verified')
                                        <span class="badge bg-soft-success text-success rounded-pill px-3"><i class="fas fa-check-circle me-1"></i> Verified</span>
                                    @else
                                        <span class="badge bg-soft-danger text-danger rounded-pill px-3"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                                    @endif
                                    @php $hasFile = ($cert->file_content || $cert->file_path); @endphp
                                    <button class="btn btn-sm btn-light border rounded-circle ms-2" onclick="previewMyCert('{{ $hasFile ? url('/cloud-file/cert/' . $cert->id) : $cert->credential_link }}', '{{ $hasFile ? 'pdf' : 'link' }}', '{{ $hasFile ? $cert->credential_link : '' }}')">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    <i class="fas fa-shield-alt fa-3x mb-3 opacity-25"></i>
                                    <p>No external certifications uploaded yet.<br><small>Include NPTEL, HackerRank, Google, etc. to boost your profile!</small></p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- IPDC Learning Progress -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white py-3 border-0">
                <h5 class="fw-bold m-0"><i class="fas fa-tasks text-info me-2"></i> Current Assignments</h5>
            </div>
            <div class="card-body">
                @forelse($ipdcTasks as $task)
                <div class="d-flex justify-content-between align-items-center p-3 border rounded-3 mb-3 bg-light bg-opacity-50 hover-shadow transition-all">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-sm bg-info text-white rounded-3 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas {{ $task->assignment_type == 'lab' ? 'fa-flask' : ($task->assignment_type == 'exam' ? 'fa-file-signature' : 'fa-laptop-code') }}"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap mb-1">
                                <h6 class="fw-bold mb-0 text-dark">{{ $task->title }}</h6>
                                @if($task->assignment_type)
                                    <span class="badge bg-soft-success text-success rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">{{ ucfirst($task->assignment_type) }}</span>
                                @endif
                                @if($task->section && $task->section !== 'All')
                                    <span class="badge bg-soft-info text-info rounded-pill px-2 py-0.5" style="font-size: 0.65rem;">Sec {{ $task->section }}</span>
                                @endif
                            </div>
                            <div class="text-muted small" style="font-size: 0.75rem;">
                                <span class="me-3"><i class="fas fa-graduation-cap me-1"></i> {{ $task->course->title ?? 'N/A' }}</span>
                                @if($task->subject)
                                    <span class="me-3"><i class="fas fa-book me-1"></i> {{ $task->subject->name }}</span>
                                @endif
                                @if($task->due_date)
                                    <span class="text-danger fw-bold"><i class="far fa-clock me-1"></i> Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d, Y') }}</span>
                                @else
                                    <span class="text-muted"><i class="far fa-clock me-1"></i> No deadline</span>
                                @endif
                            </div>
                        </div>
                    </div>
                    <a href="/ipdc/assignment/{{ $task->id }}" class="btn btn-sm btn-info text-white fw-bold rounded-pill px-4 shadow-sm">Launch IDE</a>
                </div>
                @empty
                <div class="text-center py-5 text-muted small">
                    <i class="fas fa-clipboard-list fa-3x mb-3 opacity-25"></i>
                    <p class="mb-0">No active academic assignments found for your courses.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- IPDC Graded Assignments & Reviews -->
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0"><i class="fas fa-poll-h text-success me-2"></i> Graded Assignments & Reviews</h5>
                <span class="badge bg-soft-success text-success rounded-pill px-3 fw-bold">{{ $mySubmissions->whereNotNull('grade')->count() }} Evaluated</span>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light text-muted small">
                            <tr>
                                <th class="ps-4">Assignment / Course</th>
                                <th>Grade / Score</th>
                                <th>Evaluated By</th>
                                <th class="text-end pe-4">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($mySubmissions as $sub)
                            <tr>
                                <td class="ps-4 py-3">
                                    <div class="fw-bold text-dark">{{ $sub->task->title }}</div>
                                    <small class="text-muted"><i class="fas fa-graduation-cap me-1"></i> {{ $sub->task->course->title ?? 'N/A' }}</small>
                                </td>
                                <td>
                                    @if($sub->grade !== null)
                                        <div class="fw-bold text-success">{{ $sub->grade }} <span class="text-muted small">/ {{ $sub->task->max_points }}</span></div>
                                        @if($sub->feedback)
                                            <div class="text-muted small" style="font-size: 0.75rem; max-width: 250px; font-style: italic;">"{{ $sub->feedback }}"</div>
                                        @endif
                                    @else
                                        <span class="badge bg-soft-warning text-warning rounded-pill px-3"><i class="fas fa-clock me-1"></i> Review Pending</span>
                                    @endif
                                </td>
                                <td class="small">
                                    @if($sub->grade !== null)
                                        <div class="fw-semibold text-dark"><i class="fas fa-user-shield text-secondary me-1"></i> {{ $sub->evaluator_name ?? 'BHAVIKKUMAR PATEL' }}</div>
                                        <small class="text-muted">{{ $sub->updated_at->format('M d, Y') }}</small>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    @if($sub->grade !== null)
                                        <a href="/ipdc/evaluation-pdf/{{ $sub->id }}" target="_blank" class="btn btn-sm btn-outline-success rounded-pill px-3 fw-bold">
                                            <i class="fas fa-file-pdf me-1"></i> Report
                                        </a>
                                    @else
                                        <button class="btn btn-sm btn-light border rounded-pill px-3 small fw-bold" disabled>Pending</button>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted small">
                                    <i class="fas fa-clipboard-check fa-3x mb-3 opacity-25 text-success"></i>
                                    <p class="mb-0">No submitted assignments found.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- IPDC HackerRank Practice Section -->
        <div class="card border-0 shadow-sm rounded-4 mt-4">
            <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold m-0"><i class="fas fa-code text-indigo me-2"></i> HackerRank Coding Practice</h5>
                <span class="badge bg-indigo-subtle text-indigo rounded-pill px-3 fw-bold">Live Judge Sandbox</span>
            </div>
            <div class="card-body">
                @php
                    $problems = \App\Models\IpdcHackerrankProblem::latest()->get();
                    $userId = auth()->id() ?? session('demo_user_id') ?? 1;
                @endphp
                @forelse($problems as $problem)
                @php
                    $submission = \App\Models\IpdcHackerrankSubmission::where('user_id', $userId)
                                                                        ->where('problem_id', $problem->id)
                                                                        ->latest()
                                                                        ->first();
                @endphp
                <div class="d-flex justify-content-between align-items-center p-3 border rounded-3 mb-3 bg-white hover-shadow transition-all">
                    <div class="d-flex align-items-center">
                        <div class="icon-box-sm bg-indigo text-white rounded-3 me-3" style="width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, #6366f1, #4f46e5) !important;">
                            <i class="fas fa-terminal"></i>
                        </div>
                        <div>
                            <div class="d-flex align-items-center gap-2 flex-wrap">
                                <h6 class="fw-bold mb-0 text-dark">{{ $problem->title }}</h6>
                                <span class="badge bg-light text-muted border" style="font-size: 0.65rem;">{{ $problem->difficulty }}</span>
                                <span class="badge bg-dark-subtle text-dark" style="font-size: 0.65rem;"><i class="fas fa-bolt text-warning me-1"></i>{{ $problem->points }} XP</span>
                            </div>
                            <small class="text-muted text-truncate d-inline-block" style="max-width: 320px;">{{ Str::limit(strip_tags($problem->description), 80) }}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-3">
                        @if($submission)
                            @if($submission->status === 'Passed')
                                <span class="badge bg-soft-success text-success rounded-pill px-3 py-2 small fw-bold"><i class="fas fa-check-circle me-1"></i> Solved</span>
                            @else
                                <span class="badge bg-soft-danger text-danger rounded-pill px-3 py-2 small fw-bold"><i class="fas fa-times-circle me-1"></i> Failed</span>
                            @endif
                        @else
                            <span class="badge bg-soft-warning text-warning rounded-pill px-3 py-2 small fw-bold"><i class="fas fa-clock me-1"></i> Unattempted</span>
                        @endif
                        <a href="/ipdc/practice/{{ $problem->id }}" class="btn btn-sm btn-indigo text-white fw-bold rounded-pill px-4 shadow-sm" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%) !important;">
                            Practice IDE <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">
                    <i class="fas fa-code-branch fa-3x mb-3 opacity-25"></i>
                    <p>No HackerRank practice problems assigned by the faculty yet.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Right Column: Institutional Assets -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 mb-4 border-top border-5 border-warning">
            <div class="card-body">
                <h5 class="fw-bold mb-4 d-flex align-items-center">
                    <i class="fas fa-layer-group text-warning me-2"></i> Institutional Assets
                </h5>
                <div class="list-group list-group-flush">
                    @forelse($assets as $asset)
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 px-0 border-0 border-bottom">
                        <div class="d-flex align-items-center">
                            <div class="avatar-circle-sm me-3 {{ $asset->type == 'workbook' ? 'bg-danger-subtle text-danger' : ($asset->type == 'solution' ? 'bg-warning-subtle text-warning' : 'bg-info-subtle text-info') }}">
                                <i class="fas {{ $asset->type == 'workbook' ? 'fa-book' : ($asset->type == 'solution' ? 'fa-lightbulb' : 'fa-file-alt') }}"></i>
                            </div>
                            <div>
                                <div class="fw-bold small text-dark">{{ $asset->title }}</div>
                                <small class="text-muted text-uppercase" style="font-size: 0.6rem;">{{ $asset->type }}</small>
                            </div>
                        </div>
                        <a href="{{ url('/cloud-file/asset/' . $asset->id) }}" target="_blank" class="btn btn-sm btn-light border rounded-pill px-3 small fw-bold">Download</a>
                    </div>
                    @empty
                    <p class="text-center text-muted py-3 small">No institutional assets available yet.</p>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 bg-primary text-white mb-4 overflow-hidden">
            <div class="card-body position-relative z-1">
                <h6 class="fw-bold mb-3">IPDC Completion Status</h6>
                <div class="progress rounded-pill mb-3" style="height: 12px; background: rgba(255,255,255,0.2);">
                    <div class="progress-bar bg-white progress-bar-striped progress-bar-animated" style="width: 65%;"></div>
                </div>
                <div class="d-flex justify-content-between small fw-bold">
                    <span>65% Complete</span>
                    <span>12 / 20 Credits</span>
                </div>
            </div>
            <i class="fas fa-check-double fa-6x position-absolute end-0 bottom-0 opacity-10 me-n3 mb-n3"></i>
        </div>
    </div>
</div>

<!-- Modal: Upload Certification -->
<div class="modal fade" id="uploadCertModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="/ipdc/submit-cert" method="POST" enctype="multipart/form-data" class="modal-content border-0 shadow-lg">
            @csrf
            <div class="modal-header bg-primary text-white border-0 py-3">
                <h5 class="modal-title fw-bold"><i class="fas fa-award me-2"></i> Submit External Credential</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="mb-3">
                    <label class="form-label small fw-bold">Issuing Platform</label>
                    <select name="platform" class="form-select rounded-3" onchange="toggleStudentCustomPlatform(this)">
                        <option>NPTEL</option>
                        <option>HackerRank</option>
                        <option>Google Career Certificates</option>
                        <option>Meta Blueprint</option>
                        <option>Infosys Springboard</option>
                        <option>Oracle University</option>
                        <option value="Other">Other Institutional Provider</option>
                    </select>
                </div>
                <div id="studentCustomPlatform" class="mb-3 d-none">
                    <label class="form-label small fw-bold text-primary">Enter Provider Name</label>
                    <input name="custom_platform" class="form-control rounded-3" placeholder="e.g., Coursera, Udemy, Microsoft">
                </div>
                <div class="mb-3">
                    <label class="form-label small fw-bold">Certification Title</label>
                    <input name="title" class="form-control rounded-3" placeholder="e.g., Python for Data Science" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-6">
                        <label class="form-label small fw-bold">Issue Date</label>
                        <input type="date" name="issue_date" class="form-control rounded-3">
                    </div>
                </div>
                
                <hr class="my-4 opacity-10">
                
                <div class="mb-3">
                    <label class="form-label small fw-bold">Credential Link (URL)</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-link text-muted"></i></span>
                        <input name="link" class="form-control border-start-0" placeholder="https://verify.platform.com/id...">
                    </div>
                    <div class="form-text small">Provide a direct verification link if available.</div>
                </div>
                
                <div class="text-center my-3 fw-bold text-muted small">OR</div>

                <div class="mb-3">
                    <label class="form-label small fw-bold">Upload Document (PDF/Image)</label>
                    <input type="file" name="file" class="form-control rounded-3">
                    <div class="form-text small">Max size: 5MB. Must be a clear scan/export.</div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0">
                <button type="submit" class="btn btn-primary w-100 py-2 fw-bold rounded-pill shadow">
                    Submit for Institutional Verification
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: My Cert Preview -->
<div class="modal fade" id="previewMyCertModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden">
            <div class="modal-header border-0 pb-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: 70vh;">
                <iframe id="myCertIframe" src="" class="w-100 h-100 border-0" title="Credential Preview"></iframe>
                <div id="myCertLinkPreview" class="text-center d-none p-5">
                    <i class="fas fa-external-link-alt fa-4x text-primary mb-3"></i>
                    <h4 class="fw-bold">External Credential</h4>
                    <a href="" id="myCertBtnLink" target="_blank" class="btn btn-primary rounded-pill px-4 fw-bold mt-2">View Certificate</a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let currentStudentPreview = { file: '', link: '' };
    function previewMyCert(source, type, altSource = '') {
        currentStudentPreview = { 
            file: type === 'pdf' ? source : altSource, 
            link: type === 'link' ? source : altSource 
        };

        toggleStudentPreviewMode(type);
        new bootstrap.Modal(document.getElementById('previewMyCertModal')).show();
    }

    function toggleStudentPreviewMode(mode) {
        const iframe = document.getElementById('myCertIframe');
        const linkPreview = document.getElementById('myCertLinkPreview');
        const btnLink = document.getElementById('myCertBtnLink');

        if (mode === 'pdf') {
            let url = currentStudentPreview.file;
            
            // Fix ngrok mixed-content issues (HTTP iframe in HTTPS page)
            if (url.startsWith('http://')) {
                url = url.replace('http://', 'https://');
            }

            // Use native browser rendering to bypass ngrok external fetch blocks
            iframe.src = url;
            
            iframe.classList.remove('d-none');
            linkPreview.classList.add('d-none');
        } else {
            iframe.classList.add('d-none');
            linkPreview.classList.remove('d-none');
            btnLink.href = currentStudentPreview.link;
        }
    }
    function toggleStudentCustomPlatform(select) {
        const target = document.getElementById('studentCustomPlatform');
        if (select.value === 'Other') {
            target.classList.remove('d-none');
        } else {
            target.classList.add('d-none');
        }
    }
</script>

<style>
    .bg-soft-info { background: #e0f2fe; }
    .bg-soft-success { background: #dcfce7; }
    .bg-soft-warning { background: #fef3c7; }
    .bg-soft-danger { background: #fee2e2; }
    .avatar-circle-sm { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem; }
    .bg-danger-subtle { background: rgba(239, 68, 68, 0.1); }
    .bg-warning-subtle { background: rgba(245, 158, 11, 0.1); }
    .bg-info-subtle { background: rgba(6, 182, 212, 0.1); }
    .icon-box-sm { width: 40px; height: 40px; display: flex; align-items: center; justify-content: center; }
</style>

@endsection
