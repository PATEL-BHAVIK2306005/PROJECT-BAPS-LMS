<div class="tab-pane fade" id="tab-settings" role="tabpanel">
    <!-- Settings Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #11111b !important; border-left: 5px solid #ffd700 !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; background: #1e1e2e !important; color: #ffd700 !important;">
                        <i class="fas fa-sliders-h"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #ffd700 !important;">
                            Global Administration settings
                            <span class="badge bg-saffron text-white px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px; background-color: var(--baps-saffron);">System Config</span>
                        </h4>
                        <div class="small fw-semibold" style="color: #ffffff !important; opacity: 0.85;">Configure institutional academic parameters, security keys, and manage automated AI copilot services.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <form id="globalSettingsForm" onsubmit="event.preventDefault(); saveGlobalSettings();">
        <div class="row g-4">
            <!-- Left Side: System & Academic Variables -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 mb-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-university text-primary me-2"></i> Academic & Security Variables</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Current Academic Term</label>
                            <input type="text" id="set_term" class="form-control py-2 fs-6" value="2026 Even Semester">
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Min Attendance Threshold (%)</label>
                                <input type="number" id="set_attendance" class="form-control py-2 fs-6" min="50" max="100" value="75">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Hostel Curfew Time</label>
                                <input type="time" id="set_curfew" class="form-control py-2 fs-6" value="21:30">
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Security Encryption Key</label>
                            <input type="text" id="set_security_key" class="form-control py-2 fs-6 font-monospace" value="AES256-ITMBU-BAPS-SVM-SECURE-KEY" readonly>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Side: AI Modules Toggle Switches -->
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm rounded-4 mb-4 h-100">
                    <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                        <h5 class="fw-bold text-dark mb-0"><i class="fas fa-robot text-saffron me-2" style="color: var(--baps-saffron);"></i> AI Module Autonomy Switches</h5>
                    </div>
                    <div class="card-body px-4 pb-4">
                        <p class="text-muted small mb-4">Disable or enable AI-generated services across various segments of the institutional portal in real-time.</p>

                        <!-- Switch 1: OA Coordination Copilot -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 mb-3" style="background-color: #fafbfc;">
                            <div>
                                <h6 class="fw-bold text-dark mb-1"><i class="fas fa-handshake text-primary me-2"></i> Office Assistant AI Copilot</h6>
                                <p class="text-muted small mb-0">Simulates 15-second background admin activities and coordination directives.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" id="switch_ai_copilot" checked onchange="toggleAiFeature('ai_copilot_enabled', this.checked)">
                            </div>
                        </div>

                        <!-- Switch 2: LMS Lesson Content Generator -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 mb-3" style="background-color: #fafbfc;">
                            <div>
                                <h6 class="fw-bold text-dark mb-1"><i class="fas fa-graduation-cap text-success me-2"></i> LMS Course Content Generator</h6>
                                <p class="text-muted small mb-0">Generates lecture notes, syllabus briefs, and automated lesson modules.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" id="switch_ai_lms" checked onchange="toggleAiFeature('ai_lms_enabled', this.checked)">
                            </div>
                        </div>

                        <!-- Switch 3: Academic Timetable Scheduler -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3 mb-3" style="background-color: #fafbfc;">
                            <div>
                                <h6 class="fw-bold text-dark mb-1"><i class="fas fa-calendar-alt text-warning me-2"></i> Timetable AI Optimizer</h6>
                                <p class="text-muted small mb-0">Fills Saturday slots, resolves section conflicts, and suggests extra lectures.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" id="switch_ai_timetable" checked onchange="toggleAiFeature('ai_timetable_enabled', this.checked)">
                            </div>
                        </div>

                        <!-- Switch 4: Exam Seat Allocator -->
                        <div class="d-flex align-items-center justify-content-between p-3 border rounded-3" style="background-color: #fafbfc;">
                            <div>
                                <h6 class="fw-bold text-dark mb-1"><i class="fas fa-user-friends text-danger me-2"></i> Exam Seating AI Allocator</h6>
                                <p class="text-muted small mb-0">Calculates hall distribution maps and dynamically assigns row indices.</p>
                            </div>
                            <div class="form-check form-switch fs-4">
                                <input class="form-check-input" type="checkbox" id="switch_ai_exams" checked onchange="toggleAiFeature('ai_exams_enabled', this.checked)">
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-end gap-3 mt-4">
            <button type="submit" class="btn btn-dark fw-bold px-5 py-3 rounded-pill shadow-sm fs-5">
                <i class="fas fa-save me-2"></i> Save & Apply Configuration
            </button>
        </div>
    </form>
</div>

<script>
    function toggleAiFeature(key, isEnabled) {
        localStorage.setItem(key, isEnabled ? 'true' : 'false');
        
        if (key === 'ai_copilot_enabled') {
            updateAiCopilotBadge();
        }

        const statusStr = isEnabled ? 'ENABLED' : 'DISABLED';
        const toastType = isEnabled ? 'success' : 'warning';
        if (typeof showBapsToast === 'function') {
            showBapsToast(`AI Module "${key.replace('_enabled', '').replace('_', ' ').toUpperCase()}" has been ${statusStr}! 🤖`, toastType);
        }
    }

    function saveGlobalSettings() {
        const term = document.getElementById('set_term').value;
        const attendance = document.getElementById('set_attendance').value;
        const curfew = document.getElementById('set_curfew').value;

        localStorage.setItem('set_term', term);
        localStorage.setItem('set_attendance', attendance);
        localStorage.setItem('set_curfew', curfew);

        if (typeof showBapsToast === 'function') {
            showBapsToast('Global system configuration saved successfully! ⚙️', 'success');
        } else {
            alert('Global system configuration saved successfully!');
        }
    }

    // Load initial states
    document.addEventListener('DOMContentLoaded', () => {
        const keys = ['ai_copilot_enabled', 'ai_lms_enabled', 'ai_timetable_enabled', 'ai_exams_enabled'];
        keys.forEach(key => {
            const val = localStorage.getItem(key);
            const checkbox = document.getElementById('switch_' + key.replace('_enabled', ''));
            if (checkbox) {
                checkbox.checked = (val !== 'false');
            }
        });

        // Set variables inputs
        if (localStorage.getItem('set_term')) document.getElementById('set_term').value = localStorage.getItem('set_term');
        if (localStorage.getItem('set_attendance')) document.getElementById('set_attendance').value = localStorage.getItem('set_attendance');
        if (localStorage.getItem('set_curfew')) document.getElementById('set_curfew').value = localStorage.getItem('set_curfew');

        updateAiCopilotBadge();
    });

    function updateAiCopilotBadge() {
        const enabled = localStorage.getItem('ai_copilot_enabled') !== 'false';
        const badges = document.querySelectorAll('.badge-ai-copilot');
        
        badges.forEach(badge => {
            if (enabled) {
                badge.className = "badge bg-success text-white px-3 py-2 rounded-pill text-uppercase fs-6 shadow-sm d-flex align-items-center gap-2 badge-ai-copilot";
                badge.innerHTML = `<span class="spinner-grow spinner-grow-sm text-light" style="width: 8px; height: 8px;"></span> AI Copilot Active (15s Loop)`;
            } else {
                badge.className = "badge bg-danger text-white px-3 py-2 rounded-pill text-uppercase fs-6 shadow-sm d-flex align-items-center gap-2 badge-ai-copilot";
                badge.innerHTML = `<i class="fas fa-ban"></i> AI Copilot Stopped`;
            }
        });
    }
</script>


