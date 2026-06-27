@extends('layouts.app')
@section('content')

<style>
    .proctor-cam {
        position: relative;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        height: 180px;
        display: flex;
        align-items: center;
        justify-content: center;
        border: 2px solid transparent;
        transition: all 0.3s ease;
    }
    .proctor-cam.alerting {
        border-color: #dc3545;
        box-shadow: 0 0 15px rgba(220, 53, 69, 0.4);
    }
    .proctor-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0,0,0,0.7);
        color: white;
        padding: 5px 12px;
        font-size: 12px;
        display: flex;
        justify-content: space-between;
    }
    .status-dot {
        height: 10px;
        width: 10px;
        background-color: #198754;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }
    .status-dot.danger {
        background-color: #dc3545;
        animation: pulse 1s infinite;
    }
    @keyframes pulse {
        0% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.3); opacity: 0.7; }
        100% { transform: scale(1); opacity: 1; }
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-video text-danger me-2"></i> Live Exam Proctoring</h3>
    <a href="/admin" class="btn btn-outline-dark btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
</div>

<div class="card p-0 border-0 bg-white shadow-sm rounded-4 overflow-hidden">
    <div class="card-header bg-white border-bottom pt-4 pb-0 px-4">
        <ul class="nav nav-tabs nav-tabs-custom border-0" id="proctorTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active fw-bold text-dark border-0 pb-3" id="monitoring-tab" data-bs-toggle="tab" data-bs-target="#monitoring" type="button" role="tab"><i class="fas fa-eye text-primary me-2"></i>Active Monitoring</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-dark border-0 pb-3" id="incidents-tab" data-bs-toggle="tab" data-bs-target="#incidents" type="button" role="tab">
                    <i class="fas fa-exclamation-triangle text-warning me-2"></i>Incident Logs 
                    <span class="badge bg-danger ms-1 rounded-pill">3</span>
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link fw-bold text-dark border-0 pb-3" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings" type="button" role="tab"><i class="fas fa-cog text-secondary me-2"></i>System Settings</button>
            </li>
        </ul>
    </div>
    
    <div class="card-body p-4 bg-light">
        <div class="tab-content" id="proctorTabsContent">
            <!-- Tab 1: Active Monitoring -->
            <div class="tab-pane fade show active" id="monitoring" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0">Ongoing Examination Feeds</h5>
                    <div class="d-flex gap-2">
                        <select class="form-select form-select-sm" style="width: auto;">
                            <option>Filter by Course</option>
                            <option>Operating Systems (OS-101)</option>
                            <option>Computer Networks (CN-202)</option>
                        </select>
                        <button class="btn btn-sm btn-dark"><i class="fas fa-sync-alt me-1"></i> Refresh Streams</button>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="card border border-warning shadow-sm h-100">
                            <div class="card-header bg-warning-subtle text-dark fw-bold border-0">
                                <i class="fas fa-exchange-alt me-2"></i> Tab Switches
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-user-circle text-muted me-2"></i>A. Sharma (ST-103)</span>
                                        <span class="badge bg-danger rounded-pill">12 flags</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center bg-light">
                                        <span><i class="fas fa-user-circle text-muted me-2"></i>R. Mehta (ST-555)</span>
                                        <span class="badge bg-warning text-dark rounded-pill">4 flags</span>
                                    </li>
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <span><i class="fas fa-user-circle text-muted me-2"></i>N. Desai (ST-819)</span>
                                        <span class="badge bg-warning text-dark rounded-pill">2 flags</span>
                                    </li>
                                </ul>
                            </div>
                            <div class="card-footer bg-white text-center border-0 pb-3">
                                <button class="btn btn-sm btn-outline-dark fw-bold"><i class="fas fa-list me-1"></i> View All</button>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border border-danger shadow-sm h-100">
                            <div class="card-header bg-danger-subtle text-danger fw-bold border-0">
                                <i class="fas fa-copy me-2"></i> Copy/Paste Data Flags
                            </div>
                            <div class="card-body p-0">
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item">
                                        <div class="fw-bold"><i class="fas fa-paste text-danger me-1"></i> K. Verma (ST-401)</div>
                                        <div class="text-muted" style="font-size: 11px;">Pasted 450 characters on Q.4</div>
                                        <div class="text-end mt-1"><button class="btn btn-xs btn-danger" style="font-size:10px;">Review Text</button></div>
                                    </li>
                                    <li class="list-group-item bg-light">
                                        <div class="fw-bold"><i class="fas fa-paste text-danger me-1"></i> S. Patel (ST-892)</div>
                                        <div class="text-muted" style="font-size: 11px;">Copied question text on Q.2</div>
                                        <div class="text-end mt-1"><button class="btn btn-xs btn-outline-danger" style="font-size:10px;">Review</button></div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card border border-info shadow-sm h-100">
                            <div class="card-header bg-info-subtle text-dark fw-bold border-0">
                                <i class="fas fa-history me-2"></i> Version History
                            </div>
                            <div class="card-body p-3 small text-muted">
                                <p class="mb-2"><i class="fas fa-info-circle text-info me-1"></i> Tracking student IP changes, rapid browser fingerprint changes, and simultaneous logins.</p>
                                <div class="alert alert-light border shadow-sm p-2 mb-2">
                                    <div class="fw-bold text-dark">M. Joshi (ST-229)</div>
                                    <div style="font-size: 11px;">IP changed from <span class="text-danger">192.168.1.5</span> to <span class="text-danger">10.0.0.44</span> mid-exam.</div>
                                </div>
                                <div class="alert alert-light border shadow-sm p-2 mb-0">
                                    <div class="fw-bold text-dark">A. Sharma (ST-103)</div>
                                    <div style="font-size: 11px;">Mismatch in User-Agent header detected.</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Incident Logs -->
            <div class="tab-pane fade" id="incidents" role="tabpanel">
                <h5 class="fw-bold mb-3">AI Flagged Incidents</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-sm bg-white text-center align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Timestamp</th>
                                <th>Student</th>
                                <th>Exam / Course</th>
                                <th>Flag Type</th>
                                <th>Confidence</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="text-muted small">Today, 10:45 AM</span></td>
                                <td>A. Sharma (ST-103)</td>
                                <td>Midterm - OS-101</td>
                                <td><span class="badge bg-danger"><i class="fas fa-user-friends"></i> Multiple Faces</span></td>
                                <td class="fw-bold text-danger">94%</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary py-0"><i class="fas fa-play"></i> Review Clip</button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="text-muted small">Today, 10:12 AM</span></td>
                                <td>R. Mehta (ST-555)</td>
                                <td>Quiz 1 - CN-202</td>
                                <td><span class="badge bg-warning text-dark"><i class="fas fa-eye-slash"></i> Looked Away</span></td>
                                <td class="fw-bold text-warning">82%</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary py-0"><i class="fas fa-play"></i> Review Clip</button>
                                </td>
                            </tr>
                            <tr>
                                <td><span class="text-muted small">Today, 09:55 AM</span></td>
                                <td>N. Desai (ST-819)</td>
                                <td>Midterm - OS-101</td>
                                <td><span class="badge bg-danger"><i class="fas fa-volume-up"></i> Background Voice</span></td>
                                <td class="fw-bold text-danger">88%</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary py-0"><i class="fas fa-play"></i> Review Clip</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Tab 3: System Settings -->
            <div class="tab-pane fade" id="settings" role="tabpanel">
                <h5 class="fw-bold mb-4">Proctoring Configuration</h5>
                <div class="row">
                    <div class="col-md-6">
                        <div class="bg-white p-3 border rounded">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">AI Sensitivities</h6>
                            
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold d-block text-dark">Face Tracking Strictness</span>
                                    <span class="small text-muted">How strictly the system monitors gaze</span>
                                </div>
                                <select class="form-select form-select-sm" style="width: 120px;">
                                    <option>Low</option>
                                    <option selected>Medium</option>
                                    <option>High</option>
                                </select>
                            </div>

                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div>
                                    <span class="fw-bold d-block text-dark">Audio Sensitivity</span>
                                    <span class="small text-muted">Detection of whispers or typing</span>
                                </div>
                                <select class="form-select form-select-sm" style="width: 120px;">
                                    <option>Low</option>
                                    <option>Medium</option>
                                    <option selected>High</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="bg-white p-3 border rounded">
                            <h6 class="fw-bold mb-3 border-bottom pb-2">Hardware Requirements</h6>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="reqVideo" checked>
                                <label class="form-check-label fw-bold" for="reqVideo">Enforce Video Stream</label>
                                <div class="small text-muted">Student cannot start test without webcam</div>
                            </div>
                            
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="reqAudio" checked>
                                <label class="form-check-label fw-bold" for="reqAudio">Enforce Microphone Access</label>
                                <div class="small text-muted">Student must allow audio capture</div>
                            </div>

                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="browserLock">
                                <label class="form-check-label fw-bold" for="browserLock">Browser Lockdown Mode</label>
                                <div class="small text-muted">Prevent switching tabs or minimizing browser</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-4 text-end">
                    <button class="btn btn-primary"><i class="fas fa-save me-1"></i> Save Configuration</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling to make tabs look cleaner */
    .nav-tabs-custom .nav-link {
        border-bottom: 3px solid transparent !important;
        background: transparent !important;
        margin-right: 15px;
        opacity: 0.7;
    }
    .nav-tabs-custom .nav-link:hover {
        opacity: 1;
    }
    .nav-tabs-custom .nav-link.active {
        border-color: #0d6efd !important;
        opacity: 1;
        color: #0d6efd !important;
    }
</style>

@endsection
