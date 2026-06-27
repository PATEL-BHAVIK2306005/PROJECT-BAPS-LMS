<div class="tab-pane fade" id="tab-role-settings" role="tabpanel">
    <!-- Role Settings Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #11111b !important; border-left: 5px solid #ffd700 !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; background: #1e1e2e !important; color: #ffd700 !important;">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #ffd700 !important;">
                            Role Tab Setting
                            <span class="badge bg-saffron text-white px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px; background-color: var(--baps-saffron);">Tab Control</span>
                        </h4>
                        <div class="small fw-semibold" style="color: #ffffff !important; opacity: 0.85;">Configure dashboard tab visibility permissions for institutional roles and build dynamic custom tabs.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab Controls & Custom Tab Builder Section -->
    <div class="row g-4">
        <!-- Tab Visibility Configurator Grid -->
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-eye text-primary me-2"></i> Role-Based Tab Visibility Configurator</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="text-muted small">Manage which system roles have permission to view each administrative dashboard tab. Unchecking a role hides the tab from their panel.</p>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0" id="settingsTabConfigTable" style="min-width: 900px;">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Tab Name</th>
                                    <th>Admin</th>
                                    <th>Dean</th>
                                    <th>Office Assistant</th>
                                    <th>HOD</th>
                                    <th>Faculty</th>
                                    <th>Coordinator</th>
                                    <th>CR</th>
                                    <th>Staff</th>
                                    <th class="pe-4">Moderator</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $allTabs = [
                                        'tab-overview' => '1. Overview',
                                        'tab-academic' => '2. Academic',
                                        'tab-exams' => '3. Exams',
                                        'tab-directory' => '4. Directory',
                                        'tab-approvals' => '5. Approvals',
                                        'tab-operations' => '6. Operations & Campus',
                                        'tab-hostel' => '7. Hostel Mgmt',
                                        'tab-ipdc' => '8. IPDC Vault',
                                        'tab-reports' => '9. Reports',
                                        'tab-system' => '10. System & AI Hub',
                                        'tab-oa-coordination' => '11. OA Coordination',
                                        'tab-official-documents' => '12. Document Giving Vault',
                                        'tab-volunteer' => '13. Volunteer Service Log',
                                        'tab-role-settings' => '14. Role Tab Setting',
                                        'tab-payroll' => '15. Payroll Tab',
                                        'tab-settings' => '16. Settings Tab',
                                        'tab-circulars' => '17. Circulars & Official Works',
                                        'tab-synergy-circle' => '18. Synergy Circle',
                                        'tab-student-queries' => '19. Student Queries',
                                        'tab-special-courses' => '20. Special Courses'
                                    ];

                                    $customTabsFile = storage_path('app/custom_tabs.json');
                                    if (file_exists($customTabsFile)) {
                                        $customTabs = json_decode(file_get_contents($customTabsFile), true) ?? [];
                                        foreach ($customTabs as $ct) {
                                            $allTabs[$ct['id']] = $ct['title'] . ' (Custom)';
                                        }
                                    }

                                    $rolesList = ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'coordinator', 'cr', 'staff', 'moderator'];
                                @endphp
                                @foreach($allTabs as $tabId => $tabName)
                                <tr>
                                    <td class="fw-bold ps-4 text-dark">{{ $tabName }}</td>
                                    @foreach($rolesList as $roleKey)
                                    <td>
                                        <input class="form-check-input border-secondary shadow-sm tab-visible-chk" 
                                               type="checkbox" 
                                               data-tab-id="{{ $tabId }}" 
                                               data-role="{{ $roleKey }}">
                                    </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-end mt-4">
                        <button type="button" class="btn btn-dark fw-bold px-4 rounded-pill shadow-sm" onclick="saveTabVisibilitySettings()">
                            <i class="fas fa-save me-1"></i> Save Visibility Permissions
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Custom Tab Builder Form & List -->
        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-folder-plus text-success me-2"></i> Create Custom Dynamic Tab</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <!-- Tab Builder Selector Pills -->
                    <ul class="nav nav-pills nav-fill mb-4 bg-light p-1 rounded-3" id="tabBuilderType" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active small fw-bold" id="manual-builder-tab" data-bs-toggle="pill" data-bs-target="#manual-builder-pane" type="button" role="tab">
                                <i class="fas fa-edit me-1"></i> Manual Builder
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link small fw-bold" id="ai-builder-tab" data-bs-toggle="pill" data-bs-target="#ai-builder-pane" type="button" role="tab">
                                <i class="fas fa-robot me-1"></i> AI Co-Pilot Builder
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="tabBuilderContent">
                        <!-- Manual Builder Pane -->
                        <div class="tab-pane fade show active" id="manual-builder-pane" role="tabpanel">
                            <form id="customTabForm" onsubmit="event.preventDefault(); createCustomTab();">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Tab ID (Unique Alphanumeric)</label>
                                    <input type="text" id="cust_tab_id" class="form-control" placeholder="e.g. tab-custom-alumni" required pattern="^[a-zA-Z0-9\-]+$">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Tab Label/Title</label>
                                    <input type="text" id="cust_tab_title" class="form-control" placeholder="e.g. Alumni Relations" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">FontAwesome Icon Class</label>
                                    <input type="text" id="cust_tab_icon" class="form-control" placeholder="e.g. fas fa-graduation-cap" value="fas fa-link" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Authorized Roles</label>
                                    <div class="d-flex flex-wrap gap-2 pt-1">
                                        @foreach($rolesList as $roleKey)
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="checkbox" name="cust_tab_roles" value="{{ $roleKey }}" id="chk_cust_{{ $roleKey }}">
                                            <label class="form-check-label small" for="chk_cust_{{ $roleKey }}">{{ ucfirst($roleKey) }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">HTML/Markup Content</label>
                                    <textarea id="cust_tab_content" class="form-control font-monospace" rows="5" placeholder="<div><h4>Welcome</h4><p>Custom HTML panel content...</p></div>" required></textarea>
                                </div>
                                <button type="submit" class="btn btn-success w-100 fw-bold rounded-pill py-2 shadow-sm">
                                    <i class="fas fa-plus-circle me-1"></i> Add Dynamic Tab
                                </button>
                            </form>
                        </div>

                        <!-- AI Co-Pilot Builder Pane -->
                        <div class="tab-pane fade" id="ai-builder-pane" role="tabpanel">
                            <form id="aiTabForm" onsubmit="event.preventDefault(); generateTabViaAi();">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">What tab would you like to create? (AI Prompt)</label>
                                    <textarea id="ai_tab_prompt" class="form-control" rows="4" placeholder="Describe the tab you want to build (e.g. 'Alumni search portal with stats card', 'Placement cell tracker', 'Canteen food order menu')..." required></textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Tab Label/Title Suggestion (Optional)</label>
                                    <input type="text" id="ai_tab_title_suggest" class="form-control" placeholder="e.g. Alumni Hub (Leave blank for AI choice)">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-muted">Authorized Roles</label>
                                    <div class="d-flex flex-wrap gap-2 pt-1">
                                        @foreach($rolesList as $roleKey)
                                        <div class="form-check me-2">
                                            <input class="form-check-input" type="checkbox" name="ai_tab_roles" value="{{ $roleKey }}" id="chk_ai_{{ $roleKey }}">
                                            <label class="form-check-label small" for="chk_ai_{{ $roleKey }}">{{ ucfirst($roleKey) }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                <!-- AI Generating Steps overlay -->
                                <div id="aiStepsContainer" class="d-none border rounded-3 p-3 mb-3 bg-dark text-light font-monospace small">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="spinner-border spinner-border-sm text-info" role="status"></div>
                                        <span class="fw-bold text-info">AI Builder is executing your request...</span>
                                    </div>
                                    <div id="aiStepLog"></div>
                                </div>

                                <button type="submit" id="aiSubmitBtn" class="btn btn-primary w-100 fw-bold rounded-pill py-2 shadow-sm">
                                    <i class="fas fa-robot me-1"></i> Generate & Install Tab via AI
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12 col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-list text-indigo me-2"></i> Custom Dynamic Tabs List</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="text-muted small">Manage custom injected dynamic tabs available in the dashboard.</p>
                    <div id="customTabsContainer" class="list-group">
                        <!-- Loaded dynamically via JavaScript -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Tab configuration list
    function loadSettingsTabConfig() {
        const defaultAccess = {
            'tab-overview': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
            'tab-academic': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator'],
            'tab-exams': ['admin', 'cr', 'hod', 'dean', 'office-assistant'],
            'tab-directory': ['admin', 'dean', 'office-assistant', 'hod', 'cr'],
            'tab-approvals': ['admin', 'cr', 'hod', 'dean', 'office-assistant'],
            'tab-operations': ['admin', 'cr', 'coordinator', 'faculty-lecturer-coordinator'],
            'tab-hostel': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
            'tab-ipdc': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
            'tab-reports': ['admin', 'dean', 'office-assistant'],
            'tab-system': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
            'tab-oa-coordination': ['admin', 'dean', 'office-assistant', 'hod'],
            'tab-official-documents': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'faculty-lecturer-lab', 'coordinator', 'faculty-lecturer-coordinator', 'moderator', 'staff'],
            'tab-volunteer': ['admin', 'dean', 'office-assistant', 'hod'],
            'tab-role-settings': ['admin', 'dean'],
            'tab-payroll': ['admin', 'dean', 'office-assistant', 'hod'],
            'tab-settings': ['admin', 'dean'],
            'tab-circulars': ['admin', 'dean', 'office-assistant', 'hod', 'coordinator', 'faculty', 'cr'],
            'tab-synergy-circle': ['admin', 'dean', 'office-assistant', 'hod', 'faculty'],
            'tab-student-queries': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator'],
            'tab-special-courses': ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr', 'coordinator', 'faculty-lecturer-coordinator']
        };

        let currentConfig = localStorage.getItem('tab_access_config');
        if (!currentConfig) {
            currentConfig = defaultAccess;
            localStorage.setItem('tab_access_config', JSON.stringify(currentConfig));
        } else {
            currentConfig = JSON.parse(currentConfig);
            let updated = false;
            Object.keys(defaultAccess).forEach(key => {
                if (!currentConfig.hasOwnProperty(key)) {
                    currentConfig[key] = defaultAccess[key];
                    updated = true;
                }
            });
            // Merge custom tabs from storage metadata if missing from localStorage config
            @if(file_exists(storage_path('app/custom_tabs.json')))
                @php
                    $customTabsData = json_decode(file_get_contents(storage_path('app/custom_tabs.json')), true) ?? [];
                @endphp
                const metaCustomTabs = {!! json_encode($customTabsData) !!};
                metaCustomTabs.forEach(ct => {
                    if (!currentConfig.hasOwnProperty(ct.id)) {
                        currentConfig[ct.id] = ct.roles;
                        updated = true;
                    }
                });
            @endif

            if (updated) {
                localStorage.setItem('tab_access_config', JSON.stringify(currentConfig));
            }
        }

        // Set checkboxes
        const checkboxes = document.querySelectorAll('.tab-visible-chk');
        checkboxes.forEach(chk => {
            const tabId = chk.getAttribute('data-tab-id');
            const role = chk.getAttribute('data-role');
            if (currentConfig[tabId] && currentConfig[tabId].includes(role)) {
                chk.checked = true;
            } else {
                chk.checked = false;
            }
        });
    }

    function saveTabVisibilitySettings() {
        const config = {};
        const checkboxes = document.querySelectorAll('.tab-visible-chk');
        checkboxes.forEach(chk => {
            const tabId = chk.getAttribute('data-tab-id');
            const role = chk.getAttribute('data-role');
            if (!config[tabId]) {
                config[tabId] = [];
            }
            if (chk.checked) {
                config[tabId].push(role);
            }
        });

        localStorage.setItem('tab_access_config', JSON.stringify(config));
        
        if (typeof showBapsToast === 'function') {
            showBapsToast('Tab visibility permissions saved! Refresh page to apply. 🔄', 'success');
        } else {
            alert('Tab visibility permissions saved! Please refresh the page to apply.');
        }
    }

    function renderCustomTabsList() {
        const container = document.getElementById('customTabsContainer');
        if (!container) return;

        let customTabs = localStorage.getItem('custom_dashboard_tabs');
        customTabs = customTabs ? JSON.parse(customTabs) : [];

        if (customTabs.length === 0) {
            container.innerHTML = `<div class="text-center py-4 text-muted small"><i class="fas fa-info-circle me-1"></i> No custom dynamic tabs created.</div>`;
            return;
        }

        container.innerHTML = customTabs.map((ct, idx) => `
            <div class="list-group-item d-flex align-items-center justify-content-between p-3 border rounded-3 mb-2 bg-light">
                <div>
                    <h6 class="fw-bold mb-1"><i class="${ct.icon} text-primary me-2"></i> ${ct.title}</h6>
                    <span class="text-muted small d-block">ID: <code>${ct.id}</code></span>
                    <span class="badge bg-secondary text-white mt-1">${ct.roles.join(', ')}</span>
                </div>
                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="deleteCustomTab(${idx})">
                    <i class="fas fa-trash-alt me-1"></i> Delete
                </button>
            </div>
        `).join('');
    }

    function sendTabToServer(id, title, icon, roles, content, isAi = false) {
        const token = document.querySelector('meta[name="csrf-token"]')?.content;
        
        fetch('/admin/custom-tabs/create', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id, title, icon, roles, content })
        })
        .then(response => response.json())
        .then(data => {
            if (isAi) {
                document.getElementById('aiSubmitBtn').disabled = false;
                document.getElementById('aiStepsContainer').classList.add('d-none');
                document.getElementById('aiTabForm').reset();
            }

            if (data.success) {
                // Save metadata locally to localStorage too
                let customTabs = localStorage.getItem('custom_dashboard_tabs');
                customTabs = customTabs ? JSON.parse(customTabs) : [];
                customTabs = customTabs.filter(ct => ct.id !== id);
                customTabs.push({ id, title, icon, roles, content });
                localStorage.setItem('custom_dashboard_tabs', JSON.stringify(customTabs));

                renderCustomTabsList();

                if (typeof showBapsToast === 'function') {
                    showBapsToast('Custom tab & blade file created successfully! Refresh to view. 🚀', 'success');
                } else {
                    alert('Custom tab and Blade file created successfully! Please refresh the page to apply.');
                }
            } else {
                alert('Error creating custom tab: ' + (data.error || 'Unknown error occurred.'));
            }
        })
        .catch(err => {
            if (isAi) {
                document.getElementById('aiSubmitBtn').disabled = false;
                document.getElementById('aiStepsContainer').classList.add('d-none');
            }
            console.error('Error:', err);
            alert('A network or server error occurred.');
        });
    }

    function createCustomTab() {
        const id = document.getElementById('cust_tab_id').value.trim();
        const title = document.getElementById('cust_tab_title').value.trim();
        const icon = document.getElementById('cust_tab_icon').value.trim();
        const content = document.getElementById('cust_tab_content').value;

        // Collect roles
        const rolesCheckboxes = document.querySelectorAll('input[name="cust_tab_roles"]:checked');
        const roles = Array.from(rolesCheckboxes).map(c => c.value);

        if (roles.length === 0) {
            alert('Please select at least one authorized role!');
            return;
        }

        // Send to server
        sendTabToServer(id, title, icon, roles, content, false);
        
        // Reset form
        document.getElementById('customTabForm').reset();
    }

    function deleteCustomTab(idx) {
        if (!confirm('Are you sure you want to delete this custom tab and its Blade file?')) return;

        let customTabs = localStorage.getItem('custom_dashboard_tabs');
        customTabs = customTabs ? JSON.parse(customTabs) : [];
        const tabToDelete = customTabs[idx];

        if (!tabToDelete) return;

        const token = document.querySelector('meta[name="csrf-token"]')?.content;

        fetch('/admin/custom-tabs/delete', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({ id: tabToDelete.id })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                customTabs.splice(idx, 1);
                localStorage.setItem('custom_dashboard_tabs', JSON.stringify(customTabs));
                renderCustomTabsList();

                if (typeof showBapsToast === 'function') {
                    showBapsToast('Custom tab and Blade file deleted! Refresh to apply. 🗑️', 'warning');
                } else {
                    alert('Custom tab and Blade file deleted! Please refresh the page to apply.');
                }
            } else {
                alert('Error deleting custom tab: ' + (data.error || 'Unknown error occurred.'));
            }
        })
        .catch(err => {
            console.error('Error:', err);
            alert('A server or network error occurred during deletion.');
        });
    }

    function generateTabViaAi() {
        const prompt = document.getElementById('ai_tab_prompt').value.trim();
        const titleSuggest = document.getElementById('ai_tab_title_suggest').value.trim();
        
        const rolesCheckboxes = document.querySelectorAll('input[name="ai_tab_roles"]:checked');
        const roles = Array.from(rolesCheckboxes).map(c => c.value);
        if (roles.length === 0) {
            alert('Please select at least one authorized role!');
            return;
        }

        const promptLower = prompt.toLowerCase();
        let id = '';
        let title = '';
        let icon = '';
        let content = '';

        if (promptLower.includes('alumni')) {
            id = 'tab-custom-alumni';
            title = titleSuggest || 'Alumni Network';
            icon = 'fas fa-graduation-cap';
            content = `<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-dark"><i class="fas fa-graduation-cap text-primary me-2"></i> Alumni Relations Hub</h4>
            <button class="btn btn-primary rounded-pill btn-sm px-3 shadow-sm"><i class="fas fa-plus me-1"></i> Register New Alumni</button>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Total Alumni</span>
                        <h3 class="fw-bold text-dark mb-0 mt-1">456</h3>
                    </div>
                    <div class="bg-light-primary rounded-circle p-2 text-primary fs-3"><i class="fas fa-users"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Placement Rate</span>
                        <h3 class="fw-bold text-success mb-0 mt-1">94.8%</h3>
                    </div>
                    <div class="bg-light-success rounded-circle p-2 text-success fs-3"><i class="fas fa-chart-line"></i></div>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-semibold">Active Recruiter Partners</span>
                        <h3 class="fw-bold text-info mb-0 mt-1">48</h3>
                    </div>
                    <div class="bg-light-info rounded-circle p-2 text-info fs-3"><i class="fas fa-handshake"></i></div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h5 class="fw-bold text-dark mb-0">Alumni Search Portal</h5>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" placeholder="Search by name or email...">
                </div>
                <div class="col-md-4">
                    <select class="form-select">
                        <option value="">Filter by Graduation Year</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <button class="btn btn-dark w-100"><i class="fas fa-search me-1"></i> Search</button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Alumni Name</th>
                            <th>Email</th>
                            <th>Graduation Year</th>
                            <th>Current Company</th>
                            <th>Designation</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold">Prof. Alka Ravat</td>
                            <td>alka.ravat@itmbu.ac.in</td>
                            <td>2021</td>
                            <td>ITM Universe</td>
                            <td>Faculty</td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                        <tr>
                            <td class="fw-bold">Pratik Shah</td>
                            <td>pratik.shah@google.com</td>
                            <td>2022</td>
                            <td>Google</td>
                            <td>Senior SWE</td>
                            <td><span class="badge bg-success">Active</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>`;
        } else if (promptLower.includes('grievance') || promptLower.includes('complaint') || promptLower.includes('feedback')) {
            id = 'tab-custom-grievances';
            title = titleSuggest || 'Grievance Cell';
            icon = 'fas fa-exclamation-triangle';
            content = `<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-dark"><i class="fas fa-exclamation-triangle text-danger me-2"></i> Student Grievance Cell</h4>
            <span class="badge bg-danger text-white rounded-pill px-3 py-2 fw-bold">Active Cell Monitoring</span>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                <span class="text-muted small fw-semibold">Active Cases</span>
                <h2 class="fw-bold text-danger mb-0 mt-1">12</h2>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                <span class="text-muted small fw-semibold">In Investigation</span>
                <h2 class="fw-bold text-warning mb-0 mt-1">4</h2>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                <span class="text-muted small fw-semibold">Resolved Cases</span>
                <h2 class="fw-bold text-success mb-0 mt-1">148</h2>
            </div>
        </div>
        <div class="col-12 col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                <span class="text-muted small fw-semibold">Response SLA</span>
                <h2 class="fw-bold text-primary mb-0 mt-1">24 Handled</h2>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">Log New Grievance</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form onsubmit="event.preventDefault(); alert('Grievance registered. Ticket Reference: GRV-' + Math.floor(Math.random()*90000 + 10000)); this.reset();">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Category</label>
                            <select class="form-select" required>
                                <option value="">Select Category</option>
                                <option value="Academic">Academic / Faculty Issue</option>
                                <option value="Hostel">Hostel Accommodation</option>
                                <option value="Infrastructure">Infrastructure / Labs</option>
                                <option value="Financial">Fees / Scholarships</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Student Email</label>
                            <input type="email" class="form-control" placeholder="e.g. name@itmbu.ac.in" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Detailed Description</label>
                            <textarea class="form-control" rows="4" placeholder="Explain the grievance details here..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 fw-bold rounded-pill shadow-sm">Submit Ticket</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-7">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">Case Investigation Board</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Ticket ID</th>
                                    <th>Category</th>
                                    <th>Date</th>
                                    <th>Severity</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td><strong>GRV-84521</strong></td>
                                    <td>Lab Equipment</td>
                                    <td>20-05-2026</td>
                                    <td><span class="badge bg-warning text-dark">Medium</span></td>
                                    <td><span class="badge bg-light text-dark border">Under Review</span></td>
                                </tr>
                                <tr>
                                    <td><strong>GRV-81094</strong></td>
                                    <td>Hostel Wi-Fi</td>
                                    <td>19-05-2026</td>
                                    <td><span class="badge bg-danger text-white">Critical</span></td>
                                    <td><span class="badge bg-light text-dark border">Assigned</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>`;
        } else if (promptLower.includes('canteen') || promptLower.includes('food') || promptLower.includes('cafeteria')) {
            id = 'tab-custom-canteen';
            title = titleSuggest || 'Canteen Hub';
            icon = 'fas fa-utensils';
            content = `<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-dark"><i class="fas fa-utensils text-warning me-2"></i> Campus Canteen Hub</h4>
            <span class="badge bg-success text-white rounded-pill px-3 py-1 fw-bold"><i class="fas fa-circle me-1 small"></i> Canteen Open</span>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);">
        <div class="card-body p-4 text-white">
            <h3 class="fw-bold mb-1">Today's Special Deal!</h3>
            <p class="mb-2">Get 20% off on all Traditional Punjabi & Gujarati Thali meals today. Offer ends at 3:00 PM.</p>
            <span class="badge bg-white text-dark fw-bold px-3 py-2 rounded-pill shadow-sm">Coupon: CAMPUSMEAL20</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 pt-4 px-4 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0">Menu Directory</h5>
                    <div class="btn-group btn-group-sm">
                        <button class="btn btn-outline-dark active">All Items</button>
                        <button class="btn btn-outline-dark">Beverages</button>
                        <button class="btn btn-outline-dark">Fast Food</button>
                        <button class="btn btn-outline-dark">Meals</button>
                    </div>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 d-flex justify-content-between align-items-center hover-shadow">
                                <div>
                                    <h6 class="fw-bold mb-1">Traditional Gujarati Thali</h6>
                                    <span class="text-muted small">Full unlimited serving of rotis, sabji, dal, and rice.</span>
                                    <h6 class="text-warning fw-bold mt-2 mb-0">₹ 80.00</h6>
                                </div>
                                <button class="btn btn-warning rounded-pill btn-sm px-3 fw-bold" onclick="alert('Item added to cart!')">Add</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 d-flex justify-content-between align-items-center hover-shadow">
                                <div>
                                    <h6 class="fw-bold mb-1">Paneer Tikka Roll</h6>
                                    <span class="text-muted small">Grilled paneer cubes rolled in soft flatbread wrap.</span>
                                    <h6 class="text-warning fw-bold mt-2 mb-0">₹ 60.00</h6>
                                </div>
                                <button class="btn btn-warning rounded-pill btn-sm px-3 fw-bold" onclick="alert('Item added to cart!')">Add</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">Quick Order Checkout</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <p class="text-muted small">Scan to pay or order directly online using ITMBU wallet credits.</p>
                    <div class="border rounded-4 p-3 mb-3 bg-light text-center">
                        <i class="fas fa-qrcode fs-1 text-dark mb-2"></i>
                        <span class="d-block small text-muted">Scan QR at counter for digital token</span>
                    </div>
                    <button class="btn btn-warning w-100 fw-bold rounded-pill shadow-sm">Proceed to Payment</button>
                </div>
            </div>
        </div>
    </div>
</div>`;
        } else if (promptLower.includes('placement') || promptLower.includes('job') || promptLower.includes('career')) {
            id = 'tab-custom-placements';
            title = titleSuggest || 'Career Placements';
            icon = 'fas fa-briefcase';
            content = `<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-dark"><i class="fas fa-briefcase text-primary me-2"></i> Career Placement Cell</h4>
            <span class="badge bg-primary text-white rounded-pill px-3 py-1 fw-bold">Active Recruiting Term</span>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                <span class="text-muted small fw-semibold">Placed Students</span>
                <h2 class="fw-bold text-success mb-0 mt-1">182</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                <span class="text-muted small fw-semibold">Highest Package</span>
                <h2 class="fw-bold text-dark mb-0 mt-1">₹ 24 LPA</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                <span class="text-muted small fw-semibold">Avg. Package</span>
                <h2 class="fw-bold text-indigo mb-0 mt-1">₹ 6.2 LPA</h2>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white text-center">
                <span class="text-muted small fw-semibold">Active Recruiters</span>
                <h2 class="fw-bold text-info mb-0 mt-1">32</h2>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 mb-4">
        <div class="card-header bg-white border-0 pt-4 px-4">
            <h5 class="fw-bold text-dark mb-0">Upcoming On-Campus Placement Drives</h5>
        </div>
        <div class="card-body px-4 pb-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Company</th>
                            <th>Job Role</th>
                            <th>Branches Eligible</th>
                            <th>Package Offered</th>
                            <th>Drive Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="fw-bold"><i class="fab fa-google text-primary me-2"></i> Google India</td>
                            <td>Software Engineer Intern</td>
                            <td>CSE, IT, CSN</td>
                            <td>₹ 15 Lakhs per Annum</td>
                            <td>25-May-2026</td>
                            <td><button class="btn btn-sm btn-outline-primary rounded-pill px-3">Register Drive</button></td>
                        </tr>
                        <tr>
                            <td class="fw-bold"><i class="fab fa-microsoft text-info me-2"></i> Microsoft Corp.</td>
                            <td>Support Engineer</td>
                            <td>All SCSET Branches</td>
                            <td>₹ 12 Lakhs per Annum</td>
                            <td>29-May-2026</td>
                            <td><button class="btn btn-sm btn-outline-primary rounded-pill px-3">Register Drive</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>`;
        } else {
            const cleanTitle = titleSuggest || prompt.substring(0, 20) + (prompt.length > 20 ? '...' : '');
            id = 'tab-custom-' + cleanTitle.toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/(^-|-$)/g, '');
            if (!id || id === 'tab-custom-') id = 'tab-custom-' + Math.floor(Math.random()*1000);
            title = cleanTitle;
            icon = 'fas fa-link';
            content = `<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-dark"><i class="fas fa-link text-primary me-2"></i> ${title}</h4>
            <span class="badge bg-dark text-white rounded-pill px-3 py-1 text-uppercase small">Dynamic AI Panel</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center h-100">
                <div class="rounded-circle bg-light-primary text-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="fas fa-robot"></i>
                </div>
                <h5 class="fw-bold">Dynamic Overview</h5>
                <p class="text-muted small">This tab was successfully compiled by the AI Co-pilot framework based on the following instruction:</p>
                <div class="alert alert-secondary text-start small font-monospace">"${prompt}"</div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">Recent Inquiries & Quick Form</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form class="mb-4" onsubmit="event.preventDefault(); alert('Entry submitted successfully!'); this.reset();">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Full Name</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Reference ID</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill">Submit Record</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Stamp</th>
                                    <th>Verification</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#901</td>
                                    <td>Prof. Gaurav Kulkarni</td>
                                    <td>${new Date().toLocaleDateString()}</td>
                                    <td><span class="badge bg-success">Verified</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>`;
        }

        const stepsContainer = document.getElementById('aiStepsContainer');
        const stepLog = document.getElementById('aiStepLog');
        const submitBtn = document.getElementById('aiSubmitBtn');
        
        stepsContainer.classList.remove('d-none');
        submitBtn.disabled = true;
        stepLog.innerHTML = '';

        const steps = [
            { text: '[AI Co-pilot] Analyzing prompt details...', time: 800 },
            { text: '[AI Co-pilot] Drafting layout architecture & custom styling rules...', time: 800 },
            { text: '[AI Co-pilot] Constructing custom HTML components & data elements...', time: 800 },
            { text: '[AI Co-pilot] Writing clean Blade code to storage file...', time: 800 },
            { text: '[AI Co-pilot] Registering dynamic tab metadata configurations...', time: 600 }
        ];

        let currentStep = 0;
        
        function runStep() {
            if (currentStep < steps.length) {
                const s = steps[currentStep];
                const p = document.createElement('div');
                p.className = 'text-light opacity-75 mb-1';
                p.innerHTML = `<span class="text-success">✔</span> ${s.text}`;
                stepLog.appendChild(p);
                
                currentStep++;
                setTimeout(runStep, s.time);
            } else {
                sendTabToServer(id, title, icon, roles, content, true);
            }
        }

        runStep();
    }

    // Hook tab settings loading
    document.addEventListener('DOMContentLoaded', () => {
        loadSettingsTabConfig();
        renderCustomTabsList();
    });
</script>
