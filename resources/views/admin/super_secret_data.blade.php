@extends('layouts.app')
@section('content')
<style>
    .nav-tabs .nav-link { font-size: 13px; font-weight: 600; padding: 8px 12px; margin-bottom: 2px; }
    .table-responsive { max-height: 600px; overflow-y: auto; }
    .animate-fade-in { animation: fadeIn 0.3s ease-in-out; }
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(5px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>
<div class="d-flex justify-content-between align-items-center mb-3 border-bottom pb-2">
    <div>
        <h3 class="fw-bold text-danger mb-0"><i class="fas fa-user-secret me-2"></i> BAPS Master Archive</h3>
        <small class="text-muted fw-bold">God Mode Data Visor: Strictly Confidential</small>
    </div>
    <a href="/admin" class="btn btn-outline-dark btn-sm"><i class="fas fa-arrow-left me-1"></i> Return</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-header bg-white p-0 border-bottom">
        <ul class="nav nav-tabs border-0 flex-wrap px-2 pt-2" id="dbTabs" role="tablist">
            @foreach(['user', 'staff', 'department', 'course', 'enrollment', 'lesson', 'content', 'task', 'quiz', 'question', 'option', 'quizAttempt', 'certificate', 'progress', 'attendance', 'gatepass', 'leave', 'timetable', 'timetableEntry', 'announcement', 'notification'] as $index => $model)
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $index == 0 ? 'active text-primary' : 'text-muted' }}" id="tab-{{$model}}" data-bs-toggle="tab" data-bs-target="#content-{{$model}}" type="button" role="tab">{{ strtoupper($model) }}</button>
            </li>
            @endforeach
            <li class="nav-item" role="presentation">
                <button class="nav-link text-success fw-bold" id="tab-system-control" data-bs-toggle="tab" data-bs-target="#content-system-control" type="button" role="tab"><i class="fas fa-server me-1"></i> SYSTEM CONTROL</button>
            </li>
            <li class="nav-item ms-auto" role="presentation">
                <button class="nav-link text-danger fw-bold bg-danger-subtle" id="tab-add-function" data-bs-toggle="tab" data-bs-target="#content-add-function" type="button" role="tab"><i class="fas fa-magic me-1"></i> ADD FUNCTION</button>
            </li>
        </ul>
    </div>
    <div class="card-body p-0">
        <div class="tab-content" id="dbTabsContent">
            @foreach(['user', 'staff', 'department', 'course', 'enrollment', 'lesson', 'content', 'task', 'quiz', 'question', 'option', 'quizAttempt', 'certificate', 'progress', 'attendance', 'gatepass', 'leave', 'timetable', 'timetableEntry', 'announcement', 'notification'] as $index => $model)
            <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }} p-3 animate-fade-in" id="content-{{$model}}" role="tabpanel">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="fw-bold mb-0 text-uppercase">[{{ $model }}] Records</h5>
                    <button class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold small" onclick="loadTabRecords('{{ $model }}')"><i class="fas fa-sync-alt me-1"></i> Refresh Data</button>
                </div>
                <div id="records-container-{{$model}}">
                    <div class="text-center py-5 text-muted">
                        <i class="fas fa-spinner fa-spin fa-2x mb-2 text-danger"></i>
                        <p class="small fw-bold">Loading records...</p>
                    </div>
                </div>
            </div>
            @endforeach
            
            <!-- SYSTEM CONTROL (Health & Switch) Tab -->
            <div class="tab-pane fade p-4 animate-fade-in" id="content-system-control" role="tabpanel">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card border-primary shadow-sm mb-4">
                            <div class="card-header bg-primary text-white fw-bold">
                                <i class="fas fa-heartbeat me-2"></i> HYBRID DATABASE HEALTH MONITOR
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush" id="db-health-list">
                                    <div class="text-center py-4 text-muted">
                                        <i class="fas fa-spinner fa-spin fa-2x mb-2 text-primary"></i>
                                        <p class="small fw-bold">Loading Database Health Stats...</p>
                                    </div>
                                </div>
                                <div id="db-health-error" class="alert alert-danger mt-3 small p-2 mb-0 d-none"></div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card border-danger shadow-sm mb-4">
                            <div class="card-header bg-danger text-white fw-bold">
                                <i class="fas fa-toggle-on me-2"></i> SWITCH DATABASE STATE
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-4">Toggle between Local and Cloud databases. <strong>Warning:</strong> Switching states will change where data is read and written!</p>
                                
                                <form action="/admin/master-data/switch-db" method="POST">
                                    @csrf
                                    <div class="d-grid gap-2">
                                        <div class="btn-group" role="group">
                                            <input type="radio" class="btn-check" name="state" id="state-offline" value="offline">
                                            <label class="btn btn-outline-secondary fw-bold" for="state-offline"><i class="fas fa-plug me-2"></i> OFFLINE MODE</label>

                                            <input type="radio" class="btn-check" name="state" id="state-online" value="online">
                                            <label class="btn btn-outline-primary fw-bold" for="state-online"><i class="fas fa-cloud me-2"></i> ONLINE MODE</label>
                                        </div>
                                        <button type="submit" class="btn btn-danger mt-3 fw-bold py-2"><i class="fas fa-sync-alt me-2"></i> APPLY PERSISTENT SWITCH</button>
                                    </div>
                                </form>
                                
                                <div class="mt-4 p-3 bg-light rounded border border-warning shadow-sm">
                                     <h6 class="fw-bold text-warning mb-3 small"><i class="fas fa-chart-pie me-1"></i> RESOURCE MONITORING</h6>
                                     <p class="mb-1 small fw-bold text-dark">Current Database Connection: <span class="text-primary" id="monitor-active-conn">LOADING...</span></p>
                                     
                                     <!-- Database storage usage out of limit -->
                                     <div class="mt-3">
                                         <div class="d-flex justify-content-between align-items-center mb-1">
                                             <span class="small fw-bold text-dark"><i class="fas fa-database me-1 text-info"></i> DB Storage Allocated</span>
                                             <span class="small fw-bold text-muted" id="monitor-db-size-text">0 MB / 5 GB (0%)</span>
                                         </div>
                                         <div class="progress" style="height: 10px;">
                                             <div class="progress-bar bg-info progress-bar-striped progress-bar-animated" id="monitor-db-size-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                         </div>
                                     </div>

                                     <!-- PHP System memory used out of limit -->
                                     <div class="mt-3">
                                         <div class="d-flex justify-content-between align-items-center mb-1">
                                             <span class="small fw-bold text-dark"><i class="fas fa-memory me-1 text-success"></i> PHP System Memory</span>
                                             <span class="small fw-bold text-muted" id="monitor-php-mem-text">0 MB / 0 MB (0%)</span>
                                         </div>
                                         <div class="progress" style="height: 10px;">
                                             <div class="progress-bar bg-success progress-bar-striped progress-bar-animated" id="monitor-php-mem-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                         </div>
                                     </div>
                                  </div>
                             </div>
                        </div>

                        <!-- CONFIGURE DATABASE CONNECTION Card -->
                        <div class="card border-indigo shadow-sm mb-4" style="border-color: #6366f1;">
                            <div class="card-header bg-indigo text-white fw-bold" style="background-color: #6366f1;">
                                <i class="fas fa-network-wired me-2"></i> CONFIGURE DATABASE CONNECTION
                            </div>
                            <div class="card-body">
                                <p class="small text-muted mb-3">Dynamically adjust parameters (Host, Port, Database, User, Pass) for any of the 5 hybrid data connections below.</p>
                                <form action="/admin/master-data/update-db-config" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label small fw-bold text-dark"><i class="fas fa-database me-1"></i> Connection Target</label>
                                            <select name="connection_id" id="config-connection-id" class="form-select fw-bold border-secondary" required onchange="populateConfigFields(this.value)">
                                                <option value="">-- Select Connection --</option>
                                                <option value="mysql">Offline (Local XAMPP)</option>
                                                <option value="mysql_online">Online (TiDB Cloud)</option>
                                                <option value="gcp">Google Cloud SQL (GCP)</option>
                                                <option value="itmbu_server">ITMBU Server</option>
                                                <option value="mongodb">MongoDB Database</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div id="connectionFieldsContainer" class="d-none animate-fade-in">
                                        <div class="row">
                                            <div class="col-md-8 mb-3">
                                                <label class="form-label small fw-bold text-dark">Database Host</label>
                                                <input type="text" name="host" id="config-host" class="form-control border-secondary" placeholder="e.g. 127.0.0.1" required>
                                            </div>
                                            <div class="col-md-4 mb-3">
                                                <label class="form-label small fw-bold text-dark">Port</label>
                                                <input type="number" name="port" id="config-port" class="form-control border-secondary" placeholder="e.g. 3306" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mb-3">
                                                <label class="form-label small fw-bold text-dark">Database Name</label>
                                                <input type="text" name="database" id="config-database" class="form-control border-secondary" placeholder="e.g. elearning" required>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-bold text-dark">Username</label>
                                                <input type="text" name="username" id="config-username" class="form-control border-secondary" placeholder="e.g. root">
                                            </div>
                                            <div class="col-md-6 mb-3">
                                                <label class="form-label small fw-bold text-dark">Password</label>
                                                <div class="input-group">
                                                    <input type="password" name="password" id="config-password" class="form-control border-secondary" placeholder="••••••••">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePasswordVisibility('config-password', this)">
                                                        <i class="fas fa-eye-slash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-grid mt-2">
                                            <button type="submit" class="btn btn-indigo fw-bold text-white" style="background-color: #6366f1;"><i class="fas fa-save me-2"></i> SAVE & UPDATE CONNECTION</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            
            <!-- ADD FUNCTION (GUI Injector) Tab -->
            <div class="tab-pane fade p-4 animate-fade-in" id="content-add-function" role="tabpanel">
                <div class="border border-danger border-2 rounded p-4 bg-danger-subtle bg-opacity-10">
                    <h5 class="fw-bold mb-3 text-danger"><i class="fas fa-magic me-2"></i> GUI Database Injector (No Coding Required)</h5>
                    <p class="text-dark small mb-4 fw-bold">Select a target table to dynamically inject records directly into the live database without writing SQL or code.</p>
                    <form action="/admin/master-data/inject" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-5 mb-4">
                                <label class="fw-bold small text-danger mb-1">Target Database Table</label>
                                <select name="target_table" class="form-select border-danger shadow-sm fw-bold" required onchange="loadTableSchema(this.value)">
                                    <option value="">-- Select Table --</option>
                                    @foreach(['users', 'staff', 'departments', 'courses', 'enrollments', 'lessons', 'contents', 'tasks', 'quizzes', 'questions', 'options', 'quiz_attempts', 'certificates', 'progress', 'attendances', 'gatepasses', 'leaves', 'timetables', 'timetable_entries', 'announcements', 'notifications', 'question_banks'] as $tbl)
                                        <option value="{{ $tbl }}">{{ strtoupper($tbl) }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div id="dynamicSchemaFields" class="row bg-white p-4 rounded shadow-sm border border-danger border-opacity-25 d-none mb-4">
                            <!-- Dynamic fields will be injected here via JS -->
                        </div>
                        
                        <button type="submit" class="btn btn-danger fw-bold px-5 py-2 shadow" id="injectBtn" style="display:none; font-size: 16px;"><i class="fas fa-database me-2"></i> Execute Injection</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Keep track of loaded tabs
    const loadedTabs = new Set();

    document.addEventListener('DOMContentLoaded', function () {
        // Load default tab (first tab, 'user')
        loadTabRecords('user');

        // Listen for tab switch events
        const tabElList = document.querySelectorAll('button[data-bs-toggle="tab"]');
        tabElList.forEach(tabEl => {
            tabEl.addEventListener('shown.bs.tab', function (event) {
                const targetId = event.target.getAttribute('data-bs-target');
                if (targetId.startsWith('#content-')) {
                    const model = targetId.replace('#content-', '');
                    if (model === 'system-control') {
                        loadSystemStatus();
                    } else if (model === 'add-function') {
                        // Add function tab doesn't load records
                    } else {
                        if (!loadedTabs.has(model)) {
                            loadTabRecords(model);
                        }
                    }
                }
            });
        });
    });

    async function loadTabRecords(model) {
        const container = document.getElementById(`records-container-${model}`);
        if (!container) return;

        container.innerHTML = `
            <div class="text-center py-5 text-muted animate-fade-in">
                <div class="spinner-border text-danger" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="small fw-bold mt-2">Loading records from database...</p>
            </div>
        `;

        try {
            const response = await fetch(`/admin/master-data/records/${model}`);
            const data = await response.json();
            if (data.error) {
                container.innerHTML = `<div class="alert alert-danger py-2 border-0 fw-bold small m-3"><i class="fas fa-exclamation-triangle me-2"></i> Error: ${data.error}</div>`;
            } else {
                container.innerHTML = data.html;
                loadedTabs.add(model);
            }
        } catch (e) {
            container.innerHTML = `<div class="alert alert-danger py-2 border-0 fw-bold small m-3"><i class="fas fa-exclamation-triangle me-2"></i> Connection failed to fetch records.</div>`;
        }
    }

    async function loadSystemStatus() {
        const healthList = document.getElementById('db-health-list');
        const healthError = document.getElementById('db-health-error');
        
        healthList.innerHTML = `
            <div class="text-center py-4 text-muted">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="small fw-bold mt-2">Loading Hybrid Database Health...</p>
            </div>
        `;
        healthError.classList.add('d-none');

        try {
            const response = await fetch('/admin/master-data/system-status');
            const data = await response.json();

            if (data.error) {
                healthList.innerHTML = `<div class="text-danger p-3 fw-bold small">Failed to load system status.</div>`;
                return;
            }

            // Cache connections config locally for dynamic form population
            window.dbConfigs = data.dbConfigs || {};


            // Render health details
            const dbs = [
                { id: 'offline', name: 'Offline (Local XAMPP)', key: 'offline', icon: 'fa-plug' },
                { id: 'online', name: 'Online (TiDB Cloud)', key: 'online', icon: 'fa-cloud' },
                { id: 'gcp', name: 'Google Cloud SQL (GCP)', key: 'gcp', icon: 'fa-server' },
                { id: 'itmbu', name: 'ITMBU Server', key: 'itmbu', icon: 'fa-network-wired' },
                { id: 'mongodb', name: 'MongoDB Database', key: 'mongodb', icon: 'fa-database' }
            ];

            let html = '';
            let errorText = '';

            dbs.forEach(db => {
                const info = data.dbHealth[db.key];
                if (!info) return;

                const isConnected = info.status === 'connected';
                const badgeColor = isConnected ? 
                    (info.grade === 'OUTSTANDING' ? 'success' : 
                     info.grade === 'EXCELLENT' ? 'info' : 
                     info.grade === 'VERY GOOD' ? 'primary' : 
                     info.grade === 'GOOD' ? 'warning' : 'secondary') 
                    : 'danger';

                const gradeLabel = isConnected ? info.grade : 'DISCONNECTED';
                const latencyText = isConnected ? `Latency: ${info.latency}ms` : 'Unreachable';

                html += `
                    <div class="list-group-item d-flex justify-content-between align-items-center py-3 animate-fade-in">
                        <div>
                            <h6 class="mb-0 fw-bold"><i class="fas ${db.icon} me-2 text-secondary"></i>${db.name}</h6>
                            <small class="text-muted"><i class="fas fa-bolt me-1"></i> ${latencyText}</small>
                        </div>
                        <div>
                            <span class="badge bg-${badgeColor} px-3 py-2 fw-bold shadow-sm">
                                ${gradeLabel}
                            </span>
                        </div>
                    </div>
                `;

                if (info.error) {
                    errorText += `<div><strong>${db.name} Error:</strong> ${info.error}</div>`;
                }
            });

            healthList.innerHTML = html;
            if (errorText) {
                healthError.innerHTML = errorText;
                healthError.classList.remove('d-none');
            }

            // Update Resource Monitoring section
            document.getElementById('monitor-active-conn').innerText = `${data.dbState.toUpperCase()} (${data.activeConnection})`;
            
            // Set switch state radios
            document.getElementById('state-offline').checked = (data.dbState === 'offline');
            document.getElementById('state-online').checked = (data.dbState === 'online');

            // DB Storage
            const dbSizeMB = (data.dbSize / 1048576).toFixed(2);
            const dbLimitGB = (data.dbLimit / 1073741824).toFixed(0);
            const dbPct = Math.min(100, ((data.dbSize / data.dbLimit) * 100).toFixed(2));
            document.getElementById('monitor-db-size-text').innerText = `${dbSizeMB} MB / ${dbLimitGB} GB (${dbPct}%)`;
            document.getElementById('monitor-db-size-bar').style.width = `${dbPct}%`;
            document.getElementById('monitor-db-size-bar').setAttribute('aria-valuenow', dbPct);

            // PHP System memory used out of limit
            const memUsedMB = (data.phpMemoryUsed / 1048576).toFixed(2);
            const memLimitMB = (data.phpMemoryLimit / 1048576).toFixed(2);
            const memPct = Math.min(100, ((data.phpMemoryUsed / data.phpMemoryLimit) * 100).toFixed(2));
            document.getElementById('monitor-php-mem-text').innerText = `${memUsedMB} MB / ${memLimitMB} MB (${memPct}%)`;
            document.getElementById('monitor-php-mem-bar').style.width = `${memPct}%`;
            document.getElementById('monitor-php-mem-bar').setAttribute('aria-valuenow', memPct);

        } catch (e) {
            healthList.innerHTML = `<div class="text-danger p-3 fw-bold small">Connection failed to load system metrics.</div>`;
        }
    }

    async function loadTableSchema(tableName) {
        let container = document.getElementById('dynamicSchemaFields');
        let btn = document.getElementById('injectBtn');
        
        if (!tableName) {
            container.classList.add('d-none');
            btn.style.display = 'none';
            return;
        }

        container.innerHTML = '<div class="col-12 text-center text-danger"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Reading Database Schema...</p></div>';
        container.classList.remove('d-none');

        try {
            let response = await fetch('/admin/master-data/schema/' + tableName);
            let columns = await response.json();
            
            if (columns.error) {
                container.innerHTML = `<div class="col-12 text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> Error: ${columns.error}</div>`;
                return;
            }

            let html = '<h6 class="fw-bold text-danger mb-3 border-bottom pb-2">Dynamically Generated Fields for ' + tableName.toUpperCase() + '</h6>';
            
            columns.forEach(col => {
                html += `
                <div class="col-md-4 mb-3">
                    <label class="small fw-bold text-muted mb-1">${col.toUpperCase()}</label>
                    <input type="text" name="${col}" class="form-control border-secondary" placeholder="Value for ${col}...">
                </div>`;
            });

            container.innerHTML = html;
            btn.style.display = 'inline-block';
        } catch (e) {
            container.innerHTML = '<div class="col-12 text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> Failed to read schema.</div>';
        }
    }

    function populateConfigFields(connectionId) {
        const container = document.getElementById('connectionFieldsContainer');
        if (!connectionId || !window.dbConfigs || !window.dbConfigs[connectionId]) {
            container.classList.add('d-none');
            return;
        }

        const config = window.dbConfigs[connectionId];
        document.getElementById('config-host').value = config.host || '';
        document.getElementById('config-port').value = config.port || '';
        document.getElementById('config-database').value = config.database || '';
        document.getElementById('config-username').value = config.username || '';
        document.getElementById('config-password').value = config.password || '';

        container.classList.remove('d-none');
    }

    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        }
    }
</script>

@endsection
