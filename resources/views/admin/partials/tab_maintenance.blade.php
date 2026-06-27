<div class="tab-pane fade" id="tab-maintenance" role="tabpanel">
    <!-- Maintenance Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #11111b !important; border-left: 5px solid #ffd700 !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; background: #1e1e2e !important; color: #ffd700 !important;">
                        <i class="fas fa-tools"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #ffd700 !important;">
                            LMS System Maintenance Center
                            <span class="badge bg-danger text-white px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">Admin Only</span>
                        </h4>
                        <div class="small fw-semibold" style="color: #ffffff !important; opacity: 0.85;">Operate isolated system optimizations, log diagnostics, database vacuuming, and file clearing safely.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left Side: Maintenance Mode Switch -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 h-100 overflow-hidden border-top border-4 border-danger" style="border-top-color: #ef4444 !important;">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <div class="d-flex align-items-center justify-content-between">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-power-off text-danger me-2"></i> Mode Control</h5>
                        <span id="tabMaintModeBadge" class="badge rounded-pill bg-success text-white px-3 py-1 shadow-sm">ONLINE</span>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="text-muted small mb-4">
                        Enable maintenance mode to restrict LMS portal access. All users except authorized administrative roles will see a beautiful glassmorphic maintenance screen.
                    </p>

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">
                            <i class="fas fa-key me-1 text-warning"></i> Maintenance Password
                        </label>
                        <div class="input-group">
                            <input type="password" id="maintTabPassword" class="form-control py-2 fs-6 rounded-start-3"
                                   placeholder="Enter: BAPS2026MAN" autocomplete="new-password">
                            <button class="btn btn-outline-secondary border-start-0" type="button" onclick="toggleMaintTabPwdVisibility()">
                                <i class="fas fa-eye" id="maintTabPwdEyeIcon"></i>
                            </button>
                        </div>
                        <div id="maintTabPwdError" class="text-danger small mt-1 d-none">
                            <i class="fas fa-exclamation-circle me-1"></i> <span></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">
                            <i class="fas fa-comment-alt me-1 text-info"></i> Broadcast Custom Message
                        </label>
                        <textarea id="maintTabMessage" class="form-control rounded-3" rows="3"
                                  placeholder="e.g. Scheduled database optimization in progress. Back online shortly."></textarea>
                    </div>

                    <div class="d-flex flex-column gap-2">
                        <button type="button" onclick="setTabMaintMode('on')" id="btnTabMaintEnable" class="btn btn-danger fw-bold py-2.5 px-4 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #ef4444, #dc2626); border: none;">
                            <i class="fas fa-power-off"></i> Enable Maintenance
                        </button>
                        <button type="button" onclick="setTabMaintMode('off')" id="btnTabMaintDisable" class="btn btn-success fw-bold py-2.5 px-4 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2" style="background: linear-gradient(135deg, #22c55e, #16a34a); border: none;">
                            <i class="fas fa-check-circle"></i> Disable Maintenance
                        </button>
                    </div>

                    <div id="tabMaintModeStatusDetails" class="mt-4 p-3 rounded-3 bg-light border small text-muted d-none">
                        <div><i class="fas fa-user-shield me-1 text-primary"></i> Enabled by: <strong id="maintModeUser">Admin</strong></div>
                        <div class="mt-1"><i class="fas fa-calendar-alt me-1 text-primary"></i> Activated at: <span id="maintModeTime"></span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Side: Workflow & 8 Internal Functions -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4 position-relative" id="maintenanceWorkflowCard">
                <!-- Lock Overlay when Offline -->
                <div id="maintWorkflowLockOverlay" class="position-absolute top-0 start-0 w-100 h-100 rounded-4 d-flex flex-column align-items-center justify-content-center px-4 text-center" style="background: rgba(255, 255, 255, 0.95); z-index: 10; backdrop-filter: blur(4px); transition: all 0.3s ease;">
                    <div class="bg-warning-subtle text-warning border border-warning rounded-circle d-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px; font-size: 2.2rem;">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-2">Maintenance Workflow Locked</h5>
                    <p class="text-muted small max-width-500 mb-4" style="max-width: 500px;">
                        To execute deep application optimization tasks, clear system caches, clean database tables, and run migrations safely, you must first <strong>Enable Maintenance Mode</strong>.
                    </p>
                    <button onclick="document.getElementById('maintTabPassword').focus();" class="btn btn-dark fw-bold rounded-pill px-4 shadow-sm">
                        <i class="fas fa-arrow-left me-1"></i> Unlock Control Panel
                    </button>
                </div>

                <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex align-items-center justify-content-between flex-wrap gap-2" style="z-index: 1;">
                    <div>
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-sync text-primary me-2"></i> 8-Step Maintenance Pipeline</h5>
                        <p class="text-muted small mb-0 mt-1">Execute automated routines to optimize server speed, check tables, and scan error files.</p>
                    </div>
                    <button type="button" onclick="startFullMaintenanceWorkflow()" id="btnRunFullWorkflow" class="btn btn-dark fw-bold rounded-pill px-4 shadow-sm d-flex align-items-center gap-2">
                        <i class="fas fa-play"></i> Run Full Pipeline
                    </button>
                </div>

                <div class="card-body px-4 pb-4" style="z-index: 1;">
                    <!-- Overall Progress bar -->
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-1 text-muted small">
                            <span class="fw-bold">Overall Progress</span>
                            <span class="fw-bold" id="workflowProgressTxt">0% Completed</span>
                        </div>
                        <div class="progress rounded-pill shadow-sm" style="height: 10px; background: #e2e8f0;">
                            <div class="progress-bar rounded-pill bg-success progress-bar-striped progress-bar-animated" id="workflowProgressBar" style="width: 0%; transition: width 0.4s ease;"></div>
                        </div>
                    </div>

                    <!-- Steps Timeline -->
                    <div class="maintenance-timeline position-relative">
                        <!-- Connecting Line -->
                        <div class="position-absolute h-100 border-start border-2" style="left: 20px; top: 10px; border-color: #cbd5e1 !important; z-index: 0; width: 0;"></div>
                        
                        <div class="d-flex flex-column gap-4 position-relative" style="z-index: 1;">
                            <!-- Step 1 -->
                            <div class="d-flex align-items-start gap-3 workflow-step-row" id="step-clean_cache" data-task="clean_cache">
                                <div class="step-num-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 42px; height: 42px; font-weight: 700; font-size: 0.95rem;">1</div>
                                <div class="flex-grow-1 border p-3 rounded-4 bg-light shadow-sm-hover transition-all">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><i class="fas fa-broom me-2 text-primary"></i> Clear Application & View Cache</h6>
                                            <p class="text-muted small mb-0">Clears routes, view templates, application data caches, and framework config schemas.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <span class="badge rounded-pill bg-secondary text-uppercase step-status-badge">Pending</span>
                                            <button type="button" onclick="runSingleMaintenanceTask('clean_cache')" class="btn btn-outline-dark btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center run-single-btn" style="width: 28px; height: 28px;" title="Execute Step"><i class="fas fa-play" style="font-size: 0.75rem;"></i></button>
                                        </div>
                                    </div>
                                    <div class="step-log-output d-none mt-2 p-2 bg-dark text-success font-monospace small rounded-3" style="max-height: 120px; overflow-y: auto; white-space: pre-wrap;"></div>
                                </div>
                            </div>

                            <!-- Step 2 -->
                            <div class="d-flex align-items-start gap-3 workflow-step-row" id="step-optimize_config" data-task="optimize_config">
                                <div class="step-num-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 42px; height: 42px; font-weight: 700; font-size: 0.95rem;">2</div>
                                <div class="flex-grow-1 border p-3 rounded-4 bg-light shadow-sm-hover transition-all">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><i class="fas fa-bolt me-2 text-warning"></i> Optimize & Cache Configurations</h6>
                                            <p class="text-muted small mb-0">Re-caches route hierarchies and global config bindings to optimize loading speeds.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <span class="badge rounded-pill bg-secondary text-uppercase step-status-badge">Pending</span>
                                            <button type="button" onclick="runSingleMaintenanceTask('optimize_config')" class="btn btn-outline-dark btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center run-single-btn" style="width: 28px; height: 28px;" title="Execute Step"><i class="fas fa-play" style="font-size: 0.75rem;"></i></button>
                                        </div>
                                    </div>
                                    <div class="step-log-output d-none mt-2 p-2 bg-dark text-success font-monospace small rounded-3" style="max-height: 120px; overflow-y: auto; white-space: pre-wrap;"></div>
                                </div>
                            </div>

                            <!-- Step 3 -->
                            <div class="d-flex align-items-start gap-3 workflow-step-row" id="step-db_optimize" data-task="db_optimize">
                                <div class="step-num-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 42px; height: 42px; font-weight: 700; font-size: 0.95rem;">3</div>
                                <div class="flex-grow-1 border p-3 rounded-4 bg-light shadow-sm-hover transition-all">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><i class="fas fa-database me-2 text-info"></i> Database Tables Optimization</h6>
                                            <p class="text-muted small mb-0">Performs overhead scans and structural table vacuuming on active database drivers.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <span class="badge rounded-pill bg-secondary text-uppercase step-status-badge">Pending</span>
                                            <button type="button" onclick="runSingleMaintenanceTask('db_optimize')" class="btn btn-outline-dark btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center run-single-btn" style="width: 28px; height: 28px;" title="Execute Step"><i class="fas fa-play" style="font-size: 0.75rem;"></i></button>
                                        </div>
                                    </div>
                                    <div class="step-log-output d-none mt-2 p-2 bg-dark text-success font-monospace small rounded-3" style="max-height: 120px; overflow-y: auto; white-space: pre-wrap;"></div>
                                </div>
                            </div>

                            <!-- Step 4 -->
                            <div class="d-flex align-items-start gap-3 workflow-step-row" id="step-clean_sessions" data-task="clean_sessions">
                                <div class="step-num-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 42px; height: 42px; font-weight: 700; font-size: 0.95rem;">4</div>
                                <div class="flex-grow-1 border p-3 rounded-4 bg-light shadow-sm-hover transition-all">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><i class="fas fa-user-slash me-2 text-danger"></i> Session & Security Cleaner</h6>
                                            <p class="text-muted small mb-0">Cleans out expired user login sessions and stale CSRF token cache logs.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <span class="badge rounded-pill bg-secondary text-uppercase step-status-badge">Pending</span>
                                            <button type="button" onclick="runSingleMaintenanceTask('clean_sessions')" class="btn btn-outline-dark btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center run-single-btn" style="width: 28px; height: 28px;" title="Execute Step"><i class="fas fa-play" style="font-size: 0.75rem;"></i></button>
                                        </div>
                                    </div>
                                    <div class="step-log-output d-none mt-2 p-2 bg-dark text-success font-monospace small rounded-3" style="max-height: 120px; overflow-y: auto; white-space: pre-wrap;"></div>
                                </div>
                            </div>

                            <!-- Step 5 -->
                            <div class="d-flex align-items-start gap-3 workflow-step-row" id="step-clean_orphaned_files" data-task="clean_orphaned_files">
                                <div class="step-num-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 42px; height: 42px; font-weight: 700; font-size: 0.95rem;">5</div>
                                <div class="flex-grow-1 border p-3 rounded-4 bg-light shadow-sm-hover transition-all">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><i class="fas fa-trash-alt me-2 text-success"></i> Orphaned File Sweeper</h6>
                                            <p class="text-muted small mb-0">Deletes unused course uploads and files not linked to active lesson records.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <span class="badge rounded-pill bg-secondary text-uppercase step-status-badge">Pending</span>
                                            <button type="button" onclick="runSingleMaintenanceTask('clean_orphaned_files')" class="btn btn-outline-dark btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center run-single-btn" style="width: 28px; height: 28px;" title="Execute Step"><i class="fas fa-play" style="font-size: 0.75rem;"></i></button>
                                        </div>
                                    </div>
                                    <div class="step-log-output d-none mt-2 p-2 bg-dark text-success font-monospace small rounded-3" style="max-height: 120px; overflow-y: auto; white-space: pre-wrap;"></div>
                                </div>
                            </div>

                            <!-- Step 6 -->
                            <div class="d-flex align-items-start gap-3 workflow-step-row" id="step-symlink_doctor" data-task="symlink_doctor">
                                <div class="step-num-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 42px; height: 42px; font-weight: 700; font-size: 0.95rem;">6</div>
                                <div class="flex-grow-1 border p-3 rounded-4 bg-light shadow-sm-hover transition-all">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><i class="fas fa-link me-2 text-indigo" style="color: #6366f1;"></i> Symlink Doctor</h6>
                                            <p class="text-muted small mb-0">Checks and restores public/storage symlinks to ensure valid media paths.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <span class="badge rounded-pill bg-secondary text-uppercase step-status-badge">Pending</span>
                                            <button type="button" onclick="runSingleMaintenanceTask('symlink_doctor')" class="btn btn-outline-dark btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center run-single-btn" style="width: 28px; height: 28px;" title="Execute Step"><i class="fas fa-play" style="font-size: 0.75rem;"></i></button>
                                        </div>
                                    </div>
                                    <div class="step-log-output d-none mt-2 p-2 bg-dark text-success font-monospace small rounded-3" style="max-height: 120px; overflow-y: auto; white-space: pre-wrap;"></div>
                                </div>
                            </div>

                            <!-- Step 7 -->
                            <div class="d-flex align-items-start gap-3 workflow-step-row" id="step-log_auditor" data-task="log_auditor">
                                <div class="step-num-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 42px; height: 42px; font-weight: 700; font-size: 0.95rem;">7</div>
                                <div class="flex-grow-1 border p-3 rounded-4 bg-light shadow-sm-hover transition-all">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><i class="fas fa-file-signature me-2 text-teal" style="color: #0d9488;"></i> System Log Auditor</h6>
                                            <p class="text-muted small mb-0">Audits recent framework errors, warnings, and uncaught exceptions in laravel.log.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <span class="badge rounded-pill bg-secondary text-uppercase step-status-badge">Pending</span>
                                            <button type="button" onclick="runSingleMaintenanceTask('log_auditor')" class="btn btn-outline-dark btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center run-single-btn" style="width: 28px; height: 28px;" title="Execute Step"><i class="fas fa-play" style="font-size: 0.75rem;"></i></button>
                                        </div>
                                    </div>
                                    <div class="step-log-output d-none mt-2 p-2 bg-dark text-success font-monospace small rounded-3" style="max-height: 120px; overflow-y: auto; white-space: pre-wrap;"></div>
                                </div>
                            </div>

                            <!-- Step 8 -->
                            <div class="d-flex align-items-start gap-3 workflow-step-row" id="step-migration_checker" data-task="migration_checker">
                                <div class="step-num-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 42px; height: 42px; font-weight: 700; font-size: 0.95rem;">8</div>
                                <div class="flex-grow-1 border p-3 rounded-4 bg-light shadow-sm-hover transition-all">
                                    <div class="d-flex justify-content-between align-items-start gap-2">
                                        <div>
                                            <h6 class="fw-bold text-dark mb-1"><i class="fas fa-server me-2 text-dark"></i> Migration Engine Checker</h6>
                                            <p class="text-muted small mb-0">Performs DB integrity checks and executes outstanding database migrations.</p>
                                        </div>
                                        <div class="d-flex align-items-center gap-2 flex-shrink-0">
                                            <span class="badge rounded-pill bg-secondary text-uppercase step-status-badge">Pending</span>
                                            <button type="button" onclick="runSingleMaintenanceTask('migration_checker')" class="btn btn-outline-dark btn-sm rounded-circle p-0 d-flex align-items-center justify-content-center run-single-btn" style="width: 28px; height: 28px;" title="Execute Step"><i class="fas fa-play" style="font-size: 0.75rem;"></i></button>
                                        </div>
                                    </div>
                                    <div class="step-log-output d-none mt-2 p-2 bg-dark text-success font-monospace small rounded-3" style="max-height: 120px; overflow-y: auto; white-space: pre-wrap;"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const TAB_MAINT_CENTER_CSRF = '{{ csrf_token() }}';
    const maintenanceTaskKeys = [
        'clean_cache',
        'optimize_config',
        'db_optimize',
        'clean_sessions',
        'clean_orphaned_files',
        'symlink_doctor',
        'log_auditor',
        'migration_checker'
    ];
    let isWorkflowRunning = false;

    function toggleMaintTabPwdVisibility() {
        const input = document.getElementById('maintTabPassword');
        const icon  = document.getElementById('maintTabPwdEyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    function refreshTabMaintModeStatus() {
        fetch('/admin/maintenance/status')
            .then(r => r.json())
            .then(data => {
                updateTabMaintUI(data.enabled, data);
                // Also notify general maintenance widget
                if (typeof updateMaintenanceUI === 'function') {
                    updateMaintenanceUI(data.enabled, data);
                }
            })
            .catch(() => {});
    }

    function updateTabMaintUI(isEnabled, data) {
        const badge = document.getElementById('tabMaintModeBadge');
        const details = document.getElementById('tabMaintModeStatusDetails');
        const userSpan = document.getElementById('maintModeUser');
        const timeSpan = document.getElementById('maintModeTime');
        const btnEnable = document.getElementById('btnTabMaintEnable');
        const btnDisable = document.getElementById('btnTabMaintDisable');
        const overlay = document.getElementById('maintWorkflowLockOverlay');

        if (isEnabled) {
            if (badge) {
                badge.textContent = 'OFFLINE / UNDER MAINTENANCE';
                badge.className = 'badge rounded-pill bg-danger text-white px-3 py-1 shadow-sm';
            }
            if (details) {
                details.classList.remove('d-none');
                if (userSpan) userSpan.textContent = data.enabled_by ?? 'Admin';
                if (timeSpan) timeSpan.textContent = data.enabled_at ?? '';
            }
            if (btnEnable) btnEnable.disabled = true;
            if (btnDisable) btnDisable.disabled = false;
            
            // Remove the lock overlay
            if (overlay) {
                overlay.style.opacity = '0';
                overlay.style.pointerEvents = 'none';
            }
        } else {
            if (badge) {
                badge.textContent = 'ONLINE';
                badge.className = 'badge rounded-pill bg-success text-white px-3 py-1 shadow-sm';
            }
            if (details) {
                details.classList.add('d-none');
            }
            if (btnEnable) btnEnable.disabled = false;
            if (btnDisable) btnDisable.disabled = true;

            // Show the lock overlay
            if (overlay) {
                overlay.style.opacity = '1';
                overlay.style.pointerEvents = 'auto';
            }

            resetWorkflowUI();
        }
    }

    function setTabMaintMode(action) {
        const passwordInput = document.getElementById('maintTabPassword');
        const password = passwordInput.value.trim();
        const message  = document.getElementById('maintTabMessage').value.trim();
        const errBox   = document.getElementById('maintTabPwdError');

        if (errBox) errBox.classList.add('d-none');

        if (!password) {
            if (errBox) {
                errBox.classList.remove('d-none');
                errBox.querySelector('span').textContent = 'Password is required.';
            }
            return;
        }

        const btn = action === 'on' ? document.getElementById('btnTabMaintEnable') : document.getElementById('btnTabMaintDisable');
        const originalHtml = btn.innerHTML;
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Processing...';

        fetch('/admin/maintenance/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': TAB_MAINT_CENTER_CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ password, action, message }),
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;

            if (data.error) {
                if (errBox) {
                    errBox.classList.remove('d-none');
                    errBox.querySelector('span').textContent = data.error;
                }
                return;
            }

            // Clear password
            passwordInput.value = '';

            // Update local and global UI
            updateTabMaintUI(data.enabled, data);
            if (typeof updateMaintenanceUI === 'function') {
                updateMaintenanceUI(data.enabled, data);
            }

            // Show toast message
            const toastType = data.enabled ? 'danger' : 'success';
            if (typeof showBapsToast === 'function') {
                showBapsToast(data.message, toastType);
            } else {
                alert(data.message);
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = originalHtml;
            if (errBox) {
                errBox.classList.remove('d-none');
                errBox.querySelector('span').textContent = 'Network error. Please try again.';
            }
        });
    }

    function resetWorkflowUI() {
        isWorkflowRunning = false;
        document.getElementById('workflowProgressTxt').textContent = '0% Completed';
        document.getElementById('workflowProgressBar').style.width = '0%';
        
        maintenanceTaskKeys.forEach(task => {
            const stepRow = document.getElementById('step-' + task);
            if (stepRow) {
                const badge = stepRow.querySelector('.step-status-badge');
                if (badge) {
                    badge.textContent = 'Pending';
                    badge.className = 'badge rounded-pill bg-secondary text-uppercase step-status-badge';
                }
                const log = stepRow.querySelector('.step-log-output');
                if (log) {
                    log.classList.add('d-none');
                    log.textContent = '';
                }
                const numIcon = stepRow.querySelector('.step-num-icon');
                if (numIcon) {
                    numIcon.className = 'step-num-icon bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0';
                    numIcon.innerHTML = (maintenanceTaskKeys.indexOf(task) + 1);
                }
            }
        });
    }

    function runSingleMaintenanceTask(taskKey) {
        if (isWorkflowRunning) return;
        
        const stepRow = document.getElementById('step-' + taskKey);
        if (!stepRow) return;

        const badge = stepRow.querySelector('.step-status-badge');
        const numIcon = stepRow.querySelector('.step-num-icon');
        const logBox = stepRow.querySelector('.step-log-output');
        const btn = stepRow.querySelector('.run-single-btn');

        // Update to Running
        badge.textContent = 'Running';
        badge.className = 'badge rounded-pill bg-warning text-dark text-uppercase step-status-badge';
        numIcon.className = 'step-num-icon bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0';
        numIcon.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        if (logBox) logBox.classList.add('d-none');
        if (btn) btn.disabled = true;

        return fetch('/admin/maintenance/run-task', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': TAB_MAINT_CENTER_CSRF,
                'Accept': 'application/json',
            },
            body: JSON.stringify({ task: taskKey })
        })
        .then(r => r.json())
        .then(data => {
            if (btn) btn.disabled = false;
            
            if (data.success) {
                badge.textContent = 'Completed';
                badge.className = 'badge rounded-pill bg-success text-white text-uppercase step-status-badge';
                numIcon.className = 'step-num-icon bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0';
                numIcon.innerHTML = '<i class="fas fa-check"></i>';
                
                if (logBox) {
                    logBox.classList.remove('d-none');
                    logBox.textContent = data.details + (data.log && data.log.length ? '\n\n' + data.log.join('\n') : '');
                }
                
                if (typeof showBapsToast === 'function') {
                    showBapsToast(`Task "${taskKey.replace('_', ' ').toUpperCase()}" completed!`, 'success');
                }
                return true;
            } else {
                badge.textContent = 'Failed';
                badge.className = 'badge rounded-pill bg-danger text-white text-uppercase step-status-badge';
                numIcon.className = 'step-num-icon bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0';
                numIcon.innerHTML = '<i class="fas fa-times"></i>';
                
                if (logBox) {
                    logBox.classList.remove('d-none');
                    logBox.textContent = 'Error: ' + data.details;
                }
                
                if (typeof showBapsToast === 'function') {
                    showBapsToast(`Task "${taskKey.replace('_', ' ').toUpperCase()}" failed!`, 'error');
                }
                return false;
            }
        })
        .catch(err => {
            if (btn) btn.disabled = false;
            badge.textContent = 'Failed';
            badge.className = 'badge rounded-pill bg-danger text-white text-uppercase step-status-badge';
            numIcon.className = 'step-num-icon bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0';
            numIcon.innerHTML = '<i class="fas fa-times"></i>';
            if (logBox) {
                logBox.classList.remove('d-none');
                logBox.textContent = 'Network or system failure running task.';
            }
            return false;
        });
    }

    async function startFullMaintenanceWorkflow() {
        if (isWorkflowRunning) return;
        
        isWorkflowRunning = true;
        const mainBtn = document.getElementById('btnRunFullWorkflow');
        const originalHtml = mainBtn.innerHTML;
        mainBtn.disabled = true;
        mainBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Executing Pipeline...';

        resetWorkflowUI();
        isWorkflowRunning = true; // reset clears it, re-enable

        let completedTasks = 0;
        const progressBar = document.getElementById('workflowProgressBar');
        const progressTxt = document.getElementById('workflowProgressTxt');

        for (let i = 0; i < maintenanceTaskKeys.length; i++) {
            const taskKey = maintenanceTaskKeys[i];
            
            // Scroll step into view smoothly
            const row = document.getElementById('step-' + taskKey);
            if (row) {
                row.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }

            // Temporarily clear running lock so we can invoke it sequentially
            isWorkflowRunning = false;
            const success = await runSingleMaintenanceTask(taskKey);
            isWorkflowRunning = true;

            if (success) {
                completedTasks++;
                const pct = Math.round((completedTasks / maintenanceTaskKeys.length) * 100);
                progressBar.style.width = pct + '%';
                progressTxt.textContent = pct + '% Completed';
            } else {
                // Halt sequential execution if a step fails
                if (typeof showBapsToast === 'function') {
                    showBapsToast('Pipeline halted due to step failure.', 'error');
                }
                break;
            }
            
            // Short delay for visual pacing
            await new Promise(res => setTimeout(res, 800));
        }

        isWorkflowRunning = false;
        mainBtn.disabled = false;
        mainBtn.innerHTML = originalHtml;

        if (completedTasks === maintenanceTaskKeys.length) {
            if (typeof showBapsToast === 'function') {
                showBapsToast('Full system maintenance completed successfully! 🎉', 'success');
            }
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        // Initial sync of UI states
        refreshTabMaintModeStatus();
        setInterval(refreshTabMaintModeStatus, 15000);
    });
</script>
