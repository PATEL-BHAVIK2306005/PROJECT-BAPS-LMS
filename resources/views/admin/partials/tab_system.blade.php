<div class="tab-pane fade" id="tab-system" role="tabpanel">
    <!-- Header -->
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-2">
        <h4 class="fw-bold text-dark m-0 d-flex align-items-center gap-2 fs-3">
            <i class="fas fa-microchip text-warning"></i> LMS Site Optimization & Production Manager
        </h4>
        <span class="badge px-4 py-2 text-white fw-bold rounded-pill shadow-sm" style="background: linear-gradient(135deg, #ea580c 0%, #d4af37 100%); font-size: 0.85rem;">
            <i class="fas fa-user-shield me-1"></i> Admin & Dean Console
        </span>
    </div>

    <!-- Status Cards Grid -->
    <div class="row g-3 mb-4">
        <!-- Cache Driver -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card p-3 h-100 border-0 bg-white shadow-sm" style="border-radius:16px;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Cache Driver</span>
                    <i class="fas fa-memory text-primary fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0 font-monospace">{{ strtoupper(config('cache.default', 'file')) }}</h5>
                <small class="text-success mt-1"><i class="fas fa-check-circle me-1"></i> Operational</small>
            </div>
        </div>
        <!-- Session Driver -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card p-3 h-100 border-0 bg-white shadow-sm" style="border-radius:16px;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Session Driver</span>
                    <i class="fas fa-cookie-bite text-info fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0 font-monospace">{{ strtoupper(config('session.driver', 'file')) }}</h5>
                <small class="text-muted mt-1"><i class="fas fa-info-circle me-1"></i> Lifetime: {{ config('session.lifetime') }}m</small>
            </div>
        </div>
        <!-- Database State -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card p-3 h-100 border-0 bg-white shadow-sm" style="border-radius:16px;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Database Connection</span>
                    <i class="fas fa-database text-warning fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0 font-monospace">{{ strtoupper(config('database.default')) }}</h5>
                @php
                    $isOnline = config('database.default') === 'mysql_online';
                @endphp
                <span class="badge {{ $isOnline ? 'bg-success-subtle text-success' : 'bg-warning-subtle text-warning' }} rounded-pill align-self-start mt-1 fw-bold" style="font-size:10px;">
                    {{ $isOnline ? 'Cloud Database' : 'Local XAMPP' }}
                </span>
            </div>
        </div>
        <!-- Vite Compilation Status -->
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card p-3 h-100 border-0 bg-white shadow-sm" style="border-radius:16px;">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted small fw-semibold">Vite Production Assets</span>
                    <i class="fas fa-bolt text-danger fs-5"></i>
                </div>
                @php
                    $manifestExists = file_exists(public_path('build/manifest.json'));
                @endphp
                <h5 class="fw-bold text-dark mb-0">{{ $manifestExists ? 'COMPILED' : 'PENDING' }}</h5>
                <small class="{{ $manifestExists ? 'text-success' : 'text-danger' }} mt-1">
                    <i class="fas {{ $manifestExists ? 'fa-check-circle' : 'fa-exclamation-triangle' }} me-1"></i>
                    {{ $manifestExists ? 'Assets Versioned' : 'Run Vite Compiler' }}
                </small>
            </div>
        </div>
    </div>

    <!-- Optimization Panel & Terminal Grid -->
    <div class="row g-4">
        <!-- Controls HUD -->
        <div class="col-12 col-lg-6">
            <div class="card p-4 h-100 border-0 shadow-sm" style="border-radius:20px;">
                <h5 class="fw-bold mb-3 text-dark d-flex align-items-center gap-2">
                    <i class="fas fa-sliders-h text-primary"></i> Optimization Operations
                </h5>
                <p class="text-muted small mb-4">Trigger framework operations to optimize class loading, database index lookups, and clear outdated caching nodes.</p>
                
                <div class="d-flex flex-column gap-3">
                    <!-- Section: Speed & Caching -->
                    <div>
                        <div class="text-uppercase text-muted fw-bold small mb-2" style="font-size:0.75rem; letter-spacing:0.05em;">Caching & Rebuilds</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button onclick="runSysTask('clean_cache')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-trash-can me-1 text-danger"></i> Clear Cache & Views
                                </button>
                            </div>
                            <div class="col-6">
                                <button onclick="runSysTask('optimize_config')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-bolt me-1 text-warning"></i> Re-Cache Config & Routes
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Database Controls -->
                    <div>
                        <div class="text-uppercase text-muted fw-bold small mb-2" style="font-size:0.75rem; letter-spacing:0.05em;">Database Operations</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button onclick="runSysTask('db_backup')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-file-export me-1 text-success"></i> Backup SQL Database
                                </button>
                            </div>
                            <div class="col-6">
                                <button onclick="runSysTask('db_optimize')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-broom me-1 text-info"></i> Optimize DB Tables
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section: System Diagnostics & Logs -->
                    <div>
                        <div class="text-uppercase text-muted fw-bold small mb-2" style="font-size:0.75rem; letter-spacing:0.05em;">Diagnostics & Monitoring</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button onclick="runSysTask('system_diagnostics')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-heartbeat me-1 text-danger"></i> System Diagnostics
                                </button>
                            </div>
                            <div class="col-6">
                                <button onclick="runSysTask('asset_compiler_status')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-code me-1 text-purple"></i> Vite Assets Audit
                                </button>
                            </div>
                            <div class="col-6 mt-2">
                                <button onclick="runSysTask('log_auditor')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-file-medical me-1 text-warning"></i> Audit System Logs
                                </button>
                            </div>
                            <div class="col-6 mt-2">
                                <button onclick="runSysTask('force_clear_logs')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-eraser me-1 text-danger"></i> Purge Laravel Logs
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Section: Housekeeping -->
                    <div>
                        <div class="text-uppercase text-muted fw-bold small mb-2" style="font-size:0.75rem; letter-spacing:0.05em;">Housekeeping & Integrity</div>
                        <div class="row g-2">
                            <div class="col-6">
                                <button onclick="runSysTask('clean_sessions')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-user-clock me-1 text-warning"></i> Purge Expired Sessions
                                </button>
                            </div>
                            <div class="col-6">
                                <button onclick="runSysTask('clean_orphaned_files')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-folder-minus me-1 text-danger"></i> Remove Orphaned Files
                                </button>
                            </div>
                            <div class="col-6 mt-2">
                                <button onclick="runSysTask('symlink_doctor')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-link me-1 text-info"></i> Storage Symlink Check
                                </button>
                            </div>
                            <div class="col-6 mt-2">
                                <button onclick="runSysTask('migration_checker')" class="btn btn-outline-primary btn-sm w-100 py-2 rounded-3 text-start fw-semibold shadow-xs">
                                    <i class="fas fa-database me-1 text-success"></i> Run Migration Checker
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons: Security Matrix & Security Logout -->
                <div class="mt-4 pt-3 border-top d-flex gap-2">
                    @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant']))
                        <button class="btn btn-secondary btn-sm px-3 rounded-3" data-bs-toggle="modal" data-bs-target="#configMatrixModal">
                            <i class="fas fa-sliders-h text-info me-1"></i> Security Matrix
                        </button>
                    @endif
                    @if(session('user_role') == 'admin')
                        <a href="/admin/add-function-module" class="btn btn-secondary btn-sm px-3 rounded-3 text-danger">
                            <i class="fas fa-magic me-1"></i> Add Function Module
                        </a>
                    @endif
                    <a href="/admin/logout" class="btn btn-danger btn-sm px-3 rounded-3 ms-auto shadow-xs" style="background:#fee2e2; border-color:#fca5a5; color:#dc2626;">
                        <i class="fas fa-sign-out-alt me-1"></i> Terminate Session
                    </a>
                </div>
            </div>
        </div>

        <!-- Terminal Console Console -->
        <div class="col-12 col-lg-6">
            <div class="card h-100 border-0 shadow-sm overflow-hidden d-flex flex-column" style="border-radius:20px; background:#0f172a !important;">
                <!-- Terminal Header -->
                <div class="px-4 py-3 bg-dark border-bottom border-secondary d-flex align-items-center justify-content-between" style="border-bottom-color: rgba(255,255,255,0.05) !important;">
                    <div class="d-flex align-items-center gap-2">
                        <div class="rounded-circle bg-danger" style="width:12px;height:12px;"></div>
                        <div class="rounded-circle bg-warning" style="width:12px;height:12px;"></div>
                        <div class="rounded-circle bg-success" style="width:12px;height:12px;"></div>
                        <span class="text-light small font-monospace ms-2"><i class="fas fa-terminal me-1 text-muted"></i> System-Task-Console</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button onclick="clearConsoleLog()" class="btn btn-link btn-sm text-secondary p-0 text-decoration-none font-monospace small" title="Clear Console">
                            <i class="fas fa-ban me-1"></i> Clear
                        </button>
                    </div>
                </div>
                <!-- Terminal Log Output Area -->
                <div id="sys-terminal-output" class="flex-grow-1 p-4 font-monospace text-light overflow-y-auto" style="min-height:350px; max-height:450px; font-size:13px; line-height:1.5; background:#0f172a;">
                    <div class="text-success">// BAPS Innovation System Terminal initialized.</div>
                    <div class="text-secondary">// Waiting for task triggers...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- AJAX JavaScript for Systems Management Tasks -->
<script>
    const SYSTEM_CSRF = '{{ csrf_token() }}';

    function printToConsole(text, type = 'info') {
        const consoleEl = document.getElementById('sys-terminal-output');
        if (!consoleEl) return;

        let color = '#f8fafc'; // Default off-white
        let prefix = '⚙ ';
        
        if (type === 'success') {
            color = '#4ade80'; // Neon green
            prefix = '✔ [SUCCESS] ';
        } else if (type === 'error') {
            color = '#f87171'; // Neon red
            prefix = '✘ [ERROR] ';
        } else if (type === 'warning') {
            color = '#fbbf24'; // Yellow
            prefix = '⚠ [WARN] ';
        } else if (type === 'cmd') {
            color = '#38bdf8'; // Blue
            prefix = '$ run: ';
        } else if (type === 'output') {
            color = '#94a3b8'; // Slate grey for raw logs
            prefix = '';
        }

        // Format code blocks / newlines nicely
        const formattedText = text.replace(/\n/g, '<br>');
        
        const line = document.createElement('div');
        line.style.color = color;
        line.style.marginBottom = '6px';
        line.innerHTML = `<strong>${prefix}</strong>${formattedText}`;
        consoleEl.appendChild(line);
        consoleEl.scrollTop = consoleEl.scrollHeight;
    }

    function clearConsoleLog() {
        const consoleEl = document.getElementById('sys-terminal-output');
        if (consoleEl) {
            consoleEl.innerHTML = `<div class="text-success">// Console log buffer cleared.</div><div class="text-secondary">// Waiting for task triggers...</div>`;
        }
    }

    function runSysTask(taskName) {
        printToConsole(taskName, 'cmd');
        printToConsole(`Initiating request to execute system task [${taskName}]...`, 'info');

        // Disable task buttons during execution to avoid race conditions
        const buttons = document.querySelectorAll('#tab-system button');
        buttons.forEach(btn => btn.disabled = true);

        fetch('/admin/maintenance/run-task', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': SYSTEM_CSRF
            },
            body: JSON.stringify({ task: taskName })
        })
        .then(response => {
            // Read status code to catch specific maintenance failures (like 400 Bad Request)
            if (response.status === 400) {
                return response.json().then(data => {
                    throw new Error(data.error || 'Maintenance mode must be active to run this task safely.');
                });
            } else if (response.status === 403) {
                throw new Error('Unauthorized. You must have Administrator or Provost/Dean permissions.');
            } else if (!response.ok) {
                throw new Error(`Server returned HTTP ${response.status} status error.`);
            }
            return response.json();
        })
        .then(data => {
            buttons.forEach(btn => btn.disabled = false);
            if (data.success) {
                printToConsole(data.details || 'Task execution completed successfully.', 'success');
                if (data.log && data.log.length > 0) {
                    printToConsole('--- Framework output ---', 'warning');
                    data.log.forEach(l => {
                        if(l.trim()) printToConsole(l, 'output');
                    });
                }
                
                // Show generic toast
                if (typeof showBapsToast === 'function') {
                    showBapsToast('Task completed successfully!', 'success');
                }
            } else {
                printToConsole(data.details || 'Task execution failed on server side.', 'error');
            }
        })
        .catch(error => {
            buttons.forEach(btn => btn.disabled = false);
            printToConsole(error.message, 'error');
            if (typeof showBapsToast === 'function') {
                showBapsToast(error.message, 'error');
            }
        });
    }
</script>
