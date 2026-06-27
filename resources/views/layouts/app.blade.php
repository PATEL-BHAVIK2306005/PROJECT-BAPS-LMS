<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAPS-e.learn-LMS | Premium Learning</title>
    <!-- Modern Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5.3 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

<!-- BAPS Premium Unified Circular Loader Overlay -->
<div id="baps-global-loader" style="
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background: rgba(15, 23, 42, 0.75);
    backdrop-filter: blur(20px) saturate(180%);
    -webkit-backdrop-filter: blur(20px) saturate(180%);
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    z-index: 999999;
    transition: opacity 0.5s ease, visibility 0.5s ease;
">
    <div style="position: relative; width: 150px; height: 150px; display: flex; align-items: center; justify-content: center;">
        <!-- Outer Glowing Ring -->
        <div style="
            position: absolute;
            width: 140px;
            height: 140px;
            border-radius: 50%;
            border: 4px solid transparent;
            border-top: 4px solid #ea580c;
            border-bottom: 4px solid #d4af37;
            box-shadow: 0 0 15px rgba(234, 88, 12, 0.3);
            animation: spin-clockwise 2s linear infinite;
        "></div>
        
        <!-- Middle Dotted Ring -->
        <div style="
            position: absolute;
            width: 105px;
            height: 105px;
            border-radius: 50%;
            border: 4px dotted #6366f1;
            animation: spin-counterclockwise 3s linear infinite;
        "></div>

        <!-- Inner Pulse Saffron/Gold Glow Circle -->
        <div style="
            position: absolute;
            width: 72px;
            height: 72px;
            border-radius: 50%;
            background: linear-gradient(135deg, #ea580c 0%, #d4af37 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 30px rgba(234, 88, 12, 0.6);
            animation: pulse-glow 1.5s ease-in-out infinite alternate;
        ">
            <i class="fas fa-graduation-cap" style="color: white; font-size: 28px;"></i>
        </div>
    </div>
    
    <div style="margin-top: 28px; text-align: center;">
        <h4 style="
            font-family: 'Inter', sans-serif;
            font-weight: 800;
            color: #ffffff;
            margin: 0;
            letter-spacing: 0.5px;
            font-size: 1.25rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.5);
        ">BAPS Innovation Campus</h4>
        <p style="
            font-family: 'Inter', sans-serif;
            font-size: 0.82rem;
            color: rgba(255,255,255,0.7);
            margin: 8px 0 0 0;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
        " id="baps-loader-text">Initializing Secure Workspace...</p>
    </div>
</div>



<script>
document.addEventListener("DOMContentLoaded", function() {
    const texts = [
        "Initializing Secure Workspace...",
        "Establishing Database Connection...",
        "Syncing Distributed Nodes...",
        "Optimizing Performance Parameters..."
    ];
    let i = 0;
    const textEl = document.getElementById("baps-loader-text");
    const interval = setInterval(() => {
        if(textEl) {
            i = (i + 1) % texts.length;
            textEl.innerText = texts[i];
        }
    }, 400);

    setTimeout(function() {
        clearInterval(interval);
        const loader = document.getElementById("baps-global-loader");
        if (loader) {
            loader.style.opacity = "0";
            setTimeout(() => {
                loader.style.display = "none";
            }, 500);
        }
    }, 1500);
});
</script>

<div class="mobile-overlay" id="mobileOverlay"></div>

<aside class="sidebar" id="mainSidebar">
    <div class="mb-5 d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center gap-2">
            <div class="sidebar-logo-container">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <h4 class="mb-0 fw-bold fs-5">BAPS-e.learn</h4>
        </div>
        <button class="btn btn-light d-lg-none border-0 rounded-circle" id="closeSidebarBtn">
            <i class="fas fa-times"></i>
        </button>
    </div>

    <nav>
        @if(!session('user_role') && !auth()->check() && !session('demo_user_id'))
        <div class="px-2 mt-2 mb-3">
            <a href="/login" class="btn btn-premium w-100 py-2 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 fw-bold text-decoration-none text-white">
                <i class="fas fa-sign-in-alt"></i> Login to Account
            </a>
        </div>
        <div class="sidebar-divider"></div>
        @endif

        <a href="/dashboard" class="nav-link-custom {{ request()->is('dashboard') ? 'active' : '' }}">
            <i class="fas fa-th-large"></i> Dashboard
        </a>
        <a href="/courses" class="nav-link-custom {{ request()->is('courses') ? 'active' : '' }}">
            <i class="fas fa-book"></i> All Courses
        </a>
        <a href="/hub" class="nav-link-custom {{ request()->is('hub') ? 'active' : '' }}">
            <i class="fas fa-layer-group text-primary"></i> My Hub & Requests
        </a>
        <a href="/time-capsule" class="nav-link-custom {{ request()->is('time-capsule') ? 'active' : '' }}">
            <i class="fas fa-hourglass-half text-warning"></i> Future Goal Capsule
        </a>
        <a href="/timetables" class="nav-link-custom {{ request()->is('timetables') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> Class Timetables
        </a>
        <a href="/profile" class="nav-link-custom {{ request()->is('profile') ? 'active' : '' }}">
            <i class="fas fa-id-card-clip"></i> Student Management
        </a>
        <a href="/circulars-notices" class="nav-link-custom {{ request()->is('circulars-notices') ? 'active' : '' }}">
            <i class="fas fa-bullhorn text-warning"></i> Circulars & Notices
        </a>
        @if(session('user_role') && in_array(session('user_role'), ['admin', 'dean']))
        <a href="/synergy-circle" class="nav-link-custom {{ request()->is('synergy-circle*') ? 'active' : '' }}">
            <i class="fas fa-circle-nodes text-indigo" style="color: #6366f1;"></i> Synergy Circle
        </a>
        @endif
        <a href="/ipdc/vault" class="nav-link-custom {{ request()->is('ipdc/vault*') ? 'active' : '' }}">
            <i class="fas fa-tasks" style="color: #f97316;"></i> Assignments Vault
        </a>
        @if(session('user_role') && session('user_role') !== 'student')
        <div class="mt-5 pt-4 border-top">
            <p class="text-uppercase text-muted small fw-bold mb-3">Management</p>
            @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr']))
                <a href="/admin/chat" class="nav-link-custom {{ request()->is('admin/chat') ? 'active' : '' }}">
                    <i class="fas fa-comments"></i> Communications Chat
                </a>
            @endif
            @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'cr']))
                <a href="/admin/timetables" class="nav-link-custom {{ request()->is('admin/timetables') ? 'active' : '' }}">
                    <i class="fas fa-calendar-plus"></i> Manage Timetables
                </a>
            @endif
            @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'faculty', 'cr']))
                <a href="/admin/attendance" class="nav-link-custom {{ request()->is('admin/attendance') ? 'active' : '' }}">
                    <i class="fas fa-user-check"></i> Mark Attendance
                </a>
            @endif
            @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant']))
                <a href="/admin/placement" class="nav-link-custom {{ request()->is('admin/placement*') ? 'active' : '' }}">
                    <i class="fas fa-briefcase text-primary"></i> Placement Cell
                </a>
            @endif
            <a href="/admin/profile" class="nav-link-custom {{ request()->is('admin/profile') ? 'active' : '' }}">
                <i class="fas fa-user-shield"></i> My Admin Profile
            </a>
            <a href="/admin/ipdc" class="nav-link-custom {{ request()->is('admin/ipdc*') ? 'active' : '' }}">
                <i class="fas fa-project-diagram text-warning"></i> Assignments Mgmt
            </a>
            <a href="/admin/exam/schedule" class="nav-link-custom {{ request()->is('admin/exam/*') ? 'active' : '' }}">
                <i class="fas fa-file-invoice text-info"></i> Exam Center Mgmt
            </a>
            @if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant', 'hod', 'cr']))
            <a href="/admin" class="nav-link-custom mt-3 px-3 py-2 text-white shadow-sm d-flex align-items-center" style="background: linear-gradient(135deg, #ea580c 0%, #d97706 100%); border-radius: 12px; font-weight: 500;">
                <i class="fas fa-lock fs-5 me-2 text-white"></i> Management Portal
            </a>
            @endif
        </div>
        @endif

        {{-- Maintenance Toggle (Admin only) --}}
        @if(session('user_role') === 'admin')
        <div class="mt-4 mx-1" id="maintenanceSidebarWidget">
            <div class="rounded-3 p-3" id="maintenanceWidgetCard"
                 style="background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="small fw-bold text-danger d-flex align-items-center gap-2">
                        <i class="fas fa-tools"></i> Maintenance
                    </span>
                    <span id="maintenanceSidebarBadge" class="badge rounded-pill" style="font-size:9px;">Checking...</span>
                </div>
                <button onclick="openMaintenanceModal()" class="btn btn-sm w-100 fw-bold rounded-3" id="maintenanceSidebarBtn"
                        style="background: rgba(239,68,68,0.15); color:#ef4444; border:1px solid rgba(239,68,68,0.3); font-size:12px;">
                    <i class="fas fa-power-off me-1"></i> <span id="maintenanceSidebarBtnTxt">Toggle Mode</span>
                </button>
            </div>
        </div>
        @endif
    </nav>
</aside>


<main>
    <div class="top-nav">
        <div class="d-flex align-items-center gap-3 w-100 w-lg-auto justify-content-between justify-content-lg-start">
            <button class="btn btn-light d-lg-none border-0 rounded-circle shadow-sm" id="mobileMenuBtn" style="width: 40px; height: 40px;">
                <i class="fas fa-bars"></i>
            </button>
            <form action="/courses" method="GET" class="search-bar position-relative flex-grow-1 flex-lg-grow-0" style="width: 300px; max-width: 100%;">
                <i class="fas fa-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                <input type="text" name="search" class="form-control ps-5 border-0 bg-light rounded-pill w-100" placeholder="Search courses..." value="{{ request('search') }}">
            </form>
        </div>
        <div class="d-flex align-items-center gap-2 gap-md-3 mt-2 mt-lg-0">
            @if(session('user_role') === 'dean' && session('staff_id') === 888)
                <a href="/admin/exit-demo" class="btn btn-outline-warning btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-sign-out-alt me-1"></i> Exit Dean Demo
                </a>
            @endif
            <a href="/logout" class="btn btn-outline-danger border-0 rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;" title="Logout">
                <i class="fas fa-power-off"></i>
            </a>
            <button id="themeToggle" class="btn btn-light rounded-circle shadow-sm" style="width: 40px; height: 40px;">
                <i class="fas fa-moon"></i>
            </button>
            @if(session('demo_user_id'))
                <a href="/admin/exit-demo" class="btn btn-danger btn-sm rounded-pill px-3 shadow-sm">
                    <i class="fas fa-times me-2"></i> Exit Student Demo
                </a>
            @endif
            @php 
                $userId = session('demo_user_id') ?? auth()->id() ?? 1;
                $user = auth()->user() ?? \App\Models\User::find($userId); 
            @endphp
            @if(session('user_role') && session('user_role') !== 'student')
                <div class="user-badge shadow-sm bg-primary-subtle border-primary-subtle">
                    <span class="ms-1 text-primary fw-bold"><i class="fas fa-shield-alt me-1"></i> {{ ucfirst(session('user_role')) }}</span>
                </div>
                <div class="avatar-circle bg-dark text-white shadow-sm">{{ strtoupper(substr(session('staff_name') ?? 'AD', 0, 2)) }}</div>
            @elseif(auth()->check() || session('demo_user_id'))
                <div class="user-badge shadow-sm">
                    <span class="text-secondary small fw-bold">LVL {{ $user->level ?? 1 }}</span>
                    <div class="progress rounded-pill" style="width: 50px; height: 6px; background: #e2e8f0;">
                        <div class="progress-bar rounded-pill" style="width: {{ ($user->xp ?? 0) % 100 }}%; background-image: var(--primary-gradient) !important;"></div>
                    </div>
                    <span class="ms-1 text-dark fw-bold">{{ explode(' ', $user->name)[0] ?? 'Student' }} <span class="text-muted fw-normal ms-1 font-monospace" style="font-size: 0.8em;">#{{ $user->enrollment_no ?? '' }}</span></span>
                </div>
                <div class="avatar-circle shadow-sm">{{ strtoupper(substr($user->name ?? 'ST', 0, 2)) }}</div>
            @else
                <a href="/login" class="btn btn-premium btn-sm rounded-pill px-3 shadow-sm d-flex align-items-center gap-2 me-1">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <div class="user-badge shadow-sm bg-light text-muted">
                    <i class="fas fa-user-secret me-1"></i>
                    <span class="fw-bold">Guest</span>
                </div>
                <div class="avatar-circle shadow-sm bg-secondary text-white">G</div>
            @endif
        </div>
    </div>

    <div class="main-content">
        @yield('content')

        <!-- âœ… GLOBAL TOAST NOTIFICATION SYSTEM -->
        <div id="baps-toast-container" style="
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 99999;
            display: flex;
            flex-direction: column;
            gap: 12px;
            pointer-events: none;
        "></div>

        <!-- System Footer -->
        <footer class="mt-5 pt-4 border-top text-center text-muted small">
            <div class="mb-2">
                <i class="fas fa-graduation-cap me-1" style="color: #ea580c;"></i> 
                <strong>BAPS Innovation Campus</strong>
            </div>
            <p class="mb-0">&copy; {{ date('Y') }} BAPS-e.learn-LMS. Developed for Academic Excellence.</p>
        </footer>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Dark Mode Toggle Logic
    const themeToggleBtn = document.getElementById('themeToggle');
    const body = document.body;
    
    // Check local storage for theme
    if (localStorage.getItem('theme') === 'dark') {
        body.classList.add('dark-mode');
        themeToggleBtn.innerHTML = '<i class="fas fa-sun text-warning"></i>';
        themeToggleBtn.classList.replace('btn-light', 'btn-dark');
    }

    themeToggleBtn.addEventListener('click', () => {
        body.classList.toggle('dark-mode');
        if (body.classList.contains('dark-mode')) {
            localStorage.setItem('theme', 'dark');
            themeToggleBtn.innerHTML = '<i class="fas fa-sun text-warning"></i>';
            themeToggleBtn.classList.replace('btn-light', 'btn-dark');
        } else {
            localStorage.setItem('theme', 'light');
            themeToggleBtn.innerHTML = '<i class="fas fa-moon"></i>';
            themeToggleBtn.classList.replace('btn-dark', 'btn-light');
        }
    });

</script>

<script>
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
// BAPS GLOBAL TOAST NOTIFICATION ENGINE
// â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•â•
const toastQueue = [];

function showBapsToast(message, type = 'success', icon = null) {
    const container = document.getElementById('baps-toast-container');
    const id = 'toast-' + Date.now();

    const configs = {
        success:     { bg: 'linear-gradient(135deg,#22c55e,#16a34a)', icon: 'fa-check-circle',   border: '#bbf7d0' },
        error:       { bg: 'linear-gradient(135deg,#ef4444,#dc2626)', icon: 'fa-times-circle',   border: '#fecaca' },
        info:        { bg: 'linear-gradient(135deg,#3b82f6,#2563eb)', icon: 'fa-info-circle',    border: '#bfdbfe' },
        warning:     { bg: 'linear-gradient(135deg,#f59e0b,#d97706)', icon: 'fa-exclamation-triangle', border: '#fde68a' },
        enrollment:  { bg: 'linear-gradient(135deg,#6366f1,#4f46e5)', icon: 'fa-user-check',    border: '#c7d2fe' },
        course:      { bg: 'linear-gradient(135deg,#0ea5e9,#0284c7)', icon: 'fa-book-open',     border: '#bae6fd' },
        content:     { bg: 'linear-gradient(135deg,#10b981,#059669)', icon: 'fa-upload',         border: '#a7f3d0' },
    };

    const cfg = configs[type] || configs.success;
    const finalIcon = icon || cfg.icon;

    const toast = document.createElement('div');
    toast.id = id;
    toast.style.cssText = `
        pointer-events: all;
        background: white;
        border-radius: 16px;
        box-shadow: 0 20px 40px rgba(0,0,0,0.15), 0 0 0 1px ${cfg.border};
        padding: 0;
        min-width: 320px;
        max-width: 420px;
        overflow: hidden;
        transform: translateX(120%);
        transition: transform 0.4s cubic-bezier(0.34,1.56,0.64,1), opacity 0.3s ease;
        opacity: 0;
    `;

    toast.innerHTML = `
        <div style="background:${cfg.bg}; padding:14px 18px; display:flex; align-items:center; gap:12px;">
            <i class="fas ${finalIcon}" style="font-size:1.4rem; color:white; flex-shrink:0;"></i>
            <span style="color:white; font-weight:700; font-size:0.95rem; font-family:'Inter',sans-serif; line-height:1.4;">${message}</span>
            <button onclick="closeBapsToast('${id}')" style="margin-left:auto; background:rgba(255,255,255,0.25); border:none; border-radius:50%; width:28px; height:28px; display:flex; align-items:center; justify-content:center; cursor:pointer; flex-shrink:0;">
                <i class="fas fa-times" style="color:white; font-size:0.75rem;"></i>
            </button>
        </div>
        <div style="height:4px; background:#f1f5f9; overflow:hidden;">
            <div id="${id}-bar" style="height:100%; background:${cfg.bg}; width:100%; transition:width 4s linear;"></div>
        </div>
    `;

    container.appendChild(toast);

    // Animate in
    requestAnimationFrame(() => {
        requestAnimationFrame(() => {
            toast.style.transform = 'translateX(0)';
            toast.style.opacity = '1';
        });
    });

    // Progress bar drain
    setTimeout(() => {
        const bar = document.getElementById(id + '-bar');
        if (bar) bar.style.width = '0%';
    }, 100);

    // Auto-close after 4.5s
    setTimeout(() => closeBapsToast(id), 4600);
}

function closeBapsToast(id) {
    const toast = document.getElementById(id);
    if (!toast) return;
    toast.style.transform = 'translateX(120%)';
    toast.style.opacity = '0';
    setTimeout(() => toast.remove(), 400);
}

// â”€â”€ Fire toasts from Laravel flash session â”€â”€
document.addEventListener('DOMContentLoaded', () => {
    @if(session('success'))
        @php $msg = session('success'); @endphp
        @if(Str::contains($msg, ['register', 'Register', 'enrolled', 'enrollment', 'Enrollment', 'Student officially']))
            showBapsToast('Registration Confirmed âœ…', 'enrollment');
            if (typeof confetti === 'function') confetti({ particleCount:80, spread:60, origin:{y:0.3} });
        @elseif(Str::contains($msg, ['Course created', 'course created', 'Course structure']))
            showBapsToast('Course Created ðŸŽ“', 'course');
        @elseif(Str::contains($msg, ['Content', 'content', 'Lesson', 'lesson', 'Upload', 'upload', 'PDF', 'Question', 'question', 'mapped', 'injected']))
            showBapsToast('Content Added Successfully ðŸ“š', 'content');
        @elseif(Str::contains($msg, ['Staff', 'staff', 'enrolled successfully']))
            showBapsToast('Staff Member Enrolled âœ…', 'enrollment');
        @elseif(Str::contains($msg, ['switched', 'ONLINE', 'OFFLINE']))
            showBapsToast('{{ $msg }}', 'info', 'fa-sync-alt');
        @else
            showBapsToast('{{ addslashes($msg) }}', 'success');
        @endif
    @endif

    @if(session('error'))
        showBapsToast('{{ addslashes(session("error")) }}', 'error');
    @endif
});
</script>

<script>
// Mobile Sidebar Logic
const mobileMenuBtn = document.getElementById('mobileMenuBtn');
const closeSidebarBtn = document.getElementById('closeSidebarBtn');
const mainSidebar = document.getElementById('mainSidebar');
const mobileOverlay = document.getElementById('mobileOverlay');

if (mobileMenuBtn) {
    mobileMenuBtn.addEventListener('click', () => {
        mainSidebar.classList.add('show');
        mobileOverlay.classList.add('show');
    });
}

function closeSidebar() {
    mainSidebar.classList.remove('show');
    mobileOverlay.classList.remove('show');
}

if (closeSidebarBtn) closeSidebarBtn.addEventListener('click', closeSidebar);
if (mobileOverlay) mobileOverlay.addEventListener('click', closeSidebar);

// Vanilla JS Fallback for Bootstrap Tabs & Modals if CDN fails
document.addEventListener('DOMContentLoaded', function() {
    if (typeof bootstrap === 'undefined') {
        console.warn('Bootstrap JS CDN failed to load. Initializing BAPS Vanilla JS Fallback for Tabs & Modals.');
        // Tab switching logic
        document.querySelectorAll('[data-bs-toggle="tab"], [data-bs-toggle="pill"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetSelector = this.getAttribute('data-bs-target');
                const targetPane = document.querySelector(targetSelector);
                if (!targetPane) return;
                
                // Remove active from peers
                const nav = this.closest('.nav');
                if (nav) {
                    nav.querySelectorAll('.active').forEach(b => b.classList.remove('active'));
                }
                this.classList.add('active');
                
                // Remove active/show from panes
                const tabContent = targetPane.closest('.tab-content');
                if (tabContent) {
                    tabContent.querySelectorAll('.tab-pane.active').forEach(p => {
                        p.classList.remove('active', 'show');
                        p.style.display = 'none';
                    });
                }
                targetPane.classList.add('active', 'show');
                targetPane.style.display = 'block';
            });
        });

        // Modal opening logic
        document.querySelectorAll('[data-bs-toggle="modal"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const targetSelector = this.getAttribute('data-bs-target');
                const modal = document.querySelector(targetSelector);
                if (modal) {
                    modal.classList.add('show');
                    modal.style.display = 'block';
                    // Add backdrop
                    let backdrop = document.querySelector('.modal-backdrop');
                    if (!backdrop) {
                        backdrop = document.createElement('div');
                        backdrop.className = 'modal-backdrop fade show';
                        document.body.appendChild(backdrop);
                    }
                }
            });
        });

        // Modal closing logic
        document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const modal = this.closest('.modal');
                if (modal) {
                    modal.classList.remove('show');
                    modal.style.display = 'none';
                    const backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                }
            });
        });
    }
});
</script>
<script>
function showAccessDenied(feature, requiredRole) {
    const msg = `Access to "${feature}" requires ${requiredRole} role or higher.`;
    if (typeof showBapsToast === 'function') {
        showBapsToast(msg, 'warning');
    } else {
        alert(msg);
    }
    return false;
}
</script>

@stack('scripts')

@if(session('user_role') === 'admin')
{{-- ══════════════════════════════════════════════════════════
     MAINTENANCE MODE MODAL
══════════════════════════════════════════════════════════ --}}
<div class="modal fade" id="maintenanceModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden">

            {{-- Header --}}
            <div class="modal-header py-4 px-4" id="maintModalHeader"
                 style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-3 d-flex align-items-center justify-content-center"
                             style="width:44px;height:44px;background:rgba(255,255,255,0.1);">
                            <i class="fas fa-tools text-white fs-5"></i>
                        </div>
                        <div>
                            <h5 class="modal-title fw-bold text-white mb-0">Maintenance Control</h5>
                            <small class="text-white opacity-50">BAPS LMS System Settings</small>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body p-4">

                {{-- Current Status Banner --}}
                <div id="maintStatusBanner" class="rounded-3 p-3 mb-4 d-flex align-items-center gap-3"
                     style="background:#f0fdf4; border:1px solid #bbf7d0;">
                    <i class="fas fa-circle-check text-success fs-4"></i>
                    <div>
                        <div class="fw-bold text-success small">Portal is ONLINE</div>
                        <div class="text-muted" style="font-size:11px;">All users can access the system normally.</div>
                    </div>
                    <span class="badge ms-auto rounded-pill" id="maintStatusBadge"
                          style="background:#22c55e; font-size:10px;">ONLINE</span>
                </div>

                {{-- Password --}}
                <div class="mb-3">
                    <label class="form-label small fw-bold text-muted">
                        <i class="fas fa-key me-1 text-warning"></i> Maintenance Password
                    </label>
                    <div class="input-group">
                        <input type="password" id="maintPassword" class="form-control rounded-start-3"
                               placeholder="Enter: BAPS2026MAN" autocomplete="off">
                        <button class="btn btn-outline-secondary" type="button"
                                onclick="togglePwdVisibility()" id="togglePwdBtn">
                            <i class="fas fa-eye" id="pwdEyeIcon"></i>
                        </button>
                    </div>
                    <div id="maintPwdError" class="text-danger small mt-1 d-none">
                        <i class="fas fa-exclamation-circle me-1"></i> <span></span>
                    </div>
                </div>

                {{-- Custom Message --}}
                <div class="mb-4">
                    <label class="form-label small fw-bold text-muted">
                        <i class="fas fa-comment-alt me-1 text-info"></i> Message to Users <span class="text-muted fw-normal">(optional)</span>
                    </label>
                    <textarea id="maintMessage" class="form-control rounded-3" rows="2"
                              placeholder="e.g. Scheduled upgrade in progress. Back by 3 PM."></textarea>
                </div>

                {{-- Action Buttons --}}
                <div class="row g-2">
                    <div class="col-6">
                        <button onclick="setMaintenance('on')"
                                class="btn w-100 fw-bold rounded-3 py-2"
                                style="background:linear-gradient(135deg,#ef4444,#dc2626);color:#fff;border:none;"
                                id="btnEnableMaint">
                            <i class="fas fa-power-off me-2"></i> Enable Maintenance
                        </button>
                    </div>
                    <div class="col-6">
                        <button onclick="setMaintenance('off')"
                                class="btn w-100 fw-bold rounded-3 py-2"
                                style="background:linear-gradient(135deg,#22c55e,#16a34a);color:#fff;border:none;"
                                id="btnDisableMaint">
                            <i class="fas fa-check-circle me-2"></i> Disable Maintenance
                        </button>
                    </div>
                </div>

                {{-- Info --}}
                <div class="mt-3 rounded-3 p-3" style="background:#fefce8; border:1px solid #fde68a;">
                    <div class="small text-warning-emphasis d-flex gap-2">
                        <i class="fas fa-shield-halved mt-1 flex-shrink-0"></i>
                        <div>
                            When <strong>Maintenance is ON</strong>: Only <strong>Admin</strong> and <strong>Dean</strong> can access the portal.
                            All other users see a maintenance page.
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer border-0 px-4 pb-4 pt-0">
                <button class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

{{-- Top maintenance ribbon (shows when active) --}}
<div id="maintRibbon" class="d-none text-center py-2 px-3 text-white small fw-bold"
     style="background:linear-gradient(90deg,#dc2626,#b91c1c); position:fixed; top:0; left:0; right:0; z-index:99999; letter-spacing:0.3px;">
    <i class="fas fa-tools me-2"></i>
    <span id="maintRibbonText">⚠ MAINTENANCE MODE IS ACTIVE — Only Admin & Dean have access</span>
    <button onclick="openMaintenanceModal()" class="btn btn-sm btn-light ms-3 rounded-pill px-3 py-0 fw-bold" style="font-size:11px;">
        Manage
    </button>
</div>

<script>
    const MAINT_CSRF = '{{ csrf_token() }}';

    function openMaintenanceModal() {
        refreshMaintenanceStatus();
        new bootstrap.Modal(document.getElementById('maintenanceModal')).show();
    }

    function togglePwdVisibility() {
        const input = document.getElementById('maintPassword');
        const icon  = document.getElementById('pwdEyeIcon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    function refreshMaintenanceStatus() {
        fetch('/admin/maintenance/status')
            .then(r => r.json())
            .then(data => updateMaintenanceUI(data.enabled, data))
            .catch(() => {});
    }

    function updateMaintenanceUI(isEnabled, data) {
        const banner = document.getElementById('maintStatusBanner');
        const ribbon = document.getElementById('maintRibbon');
        const sidebarBadge = document.getElementById('maintenanceSidebarBadge');
        const sidebarCard  = document.getElementById('maintenanceWidgetCard');
        const sidebarBtnTxt = document.getElementById('maintenanceSidebarBtnTxt');

        if (isEnabled) {
            if (banner) {
                banner.style.background = '#fef2f2';
                banner.style.border = '1px solid #fecaca';
                banner.innerHTML = `<i class="fas fa-triangle-exclamation text-danger fs-4"></i>
                    <div><div class="fw-bold text-danger small">⚠ Maintenance Mode is ACTIVE</div>
                    <div class="text-muted" style="font-size:11px;">Enabled by: <strong>${data.enabled_by ?? 'Admin'}</strong></div></div>
                    <span class="badge ms-auto rounded-pill" style="background:#ef4444; font-size:10px;">OFFLINE</span>`;
            }
            if (ribbon) ribbon.classList.remove('d-none');
            if (sidebarBadge) { sidebarBadge.textContent = 'ACTIVE'; sidebarBadge.style.background = '#ef4444'; }
            if (sidebarCard) { sidebarCard.style.background = 'rgba(239,68,68,0.15)'; }
            if (sidebarBtnTxt) sidebarBtnTxt.textContent = 'Disable Maintenance';
            document.body.style.paddingTop = '40px';
        } else {
            if (banner) {
                banner.style.background = '#f0fdf4';
                banner.style.border = '1px solid #bbf7d0';
                banner.innerHTML = `<i class="fas fa-circle-check text-success fs-4"></i>
                    <div><div class="fw-bold text-success small">Portal is ONLINE</div></div>
                    <span class="badge ms-auto rounded-pill" style="background:#22c55e; font-size:10px;">ONLINE</span>`;
            }
            if (ribbon) ribbon.classList.add('d-none');
            document.body.style.paddingTop = '';
            if (sidebarBadge) { sidebarBadge.textContent = 'OFF'; sidebarBadge.style.background = '#6b7280'; }
            if (sidebarCard) { sidebarCard.style.background = 'rgba(239,68,68,0.08)'; }
            if (sidebarBtnTxt) sidebarBtnTxt.textContent = 'Enable Maintenance';
        }
    }

    function setMaintenance(action) {
        const password = document.getElementById('maintPassword').value.trim();
        const message  = document.getElementById('maintMessage').value.trim();
        const errBox   = document.getElementById('maintPwdError');
        if (!password) { errBox.classList.remove('d-none'); errBox.querySelector('span').textContent = 'Password is required.'; return; }
        
        const btn = action === 'on' ? document.getElementById('btnEnableMaint') : document.getElementById('btnDisableMaint');
        btn.disabled = true;
        fetch('/admin/maintenance/toggle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': MAINT_CSRF },
            body: JSON.stringify({ password, action, message }),
        })
        .then(r => r.json())
        .then(data => {
            btn.disabled = false;
            if (data.error) { errBox.classList.remove('d-none'); errBox.querySelector('span').textContent = data.error; return; }
            updateMaintenanceUI(data.enabled, { enabled_by: '{{ session("staff_name") ?? "Admin" }}' });
            document.getElementById('maintPassword').value = '';
            showMaintenanceToast(data.message, data.enabled ? 'danger' : 'success');
        });
    }

    function showMaintenanceToast(msg, type) {
        const toastEl = document.createElement('div');
        toastEl.className = `toast align-items-center text-bg-${type} border-0 show position-fixed`;
        toastEl.style.cssText = 'bottom:24px; right:24px; z-index:999999; min-width:300px; border-radius:14px;';
        toastEl.innerHTML = `<div class="d-flex p-3 gap-3 align-items-center"><i class="fas ${type === 'danger' ? 'fa-tools' : 'fa-check-circle'} fs-5"></i><div class="fw-semibold small">${msg}</div><button type="button" class="btn-close btn-close-white ms-auto" onclick="this.closest('.toast').remove()"></button></div>`;
        document.body.appendChild(toastEl);
        setTimeout(() => toastEl.remove(), 5000);
    }

    document.addEventListener('DOMContentLoaded', () => {
        refreshMaintenanceStatus();
        setInterval(refreshMaintenanceStatus, 30000);
    });
</script>
@endif

<!-- Intercept Browser Back & Forward Navigation -->
<script>
(function() {
    // Only intercept back/forward navigation on quiz/exam/workspace pages where integrity is required
    const securePages = ['/quiz', '/take-quiz', '/workspace', '/exam/admit-card'];
    const currentPath = window.location.pathname;
    const isSecurePage = securePages.some(page => currentPath.includes(page));
    
    if (isSecurePage) {
        // Push state initially to establish our history stack entry
        history.pushState(null, document.title, window.location.href);
        
        window.addEventListener('popstate', function(event) {
            // Immediately push current URL back to history to overwrite popstate movement
            history.pushState(null, document.title, window.location.href);
            
            // Show premium BAPS toast alerting user to use page buttons
            if (typeof showBapsToast === 'function') {
                showBapsToast('Browser Back/Forward navigation is disabled. Please use the "Back to Dashboard" button.', 'warning', 'fa-arrow-left');
            } else {
                alert('Browser Back/Forward navigation is disabled. Please use the "Back to Dashboard" button.');
            }
        });
    }
})();
</script>

<!-- Global File Preview Modal -->
<div class="modal fade" id="filePreviewModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 p-4">
                <h5 class="modal-title fw-bold"><i class="fas fa-file-alt me-2 text-info"></i> Document Preview Engine</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-0" style="height: 80vh; background: #e2e8f0;">
                <iframe id="previewIframe" src="" style="width: 100%; height: 100%; border: none;"></iframe>
            </div>
        </div>
    </div>
</div>

<script>
    function previewFile(url) {
        document.getElementById('previewIframe').src = url;
        new bootstrap.Modal(document.getElementById('filePreviewModal')).show();
    }

    // Static Demo Global Router Interceptor
    (function() {
        const isStatic = window.location.hostname.includes('github.io') || window.location.protocol === 'file:';
        if (!isStatic) return;

        function getRedirectUrl(page) {
            const pathParts = window.location.pathname.split('/');
            if (pathParts.length > 2 && pathParts[1] !== '') {
                return '/' + pathParts[1] + '/' + page;
            }
            return '/' + page;
        }

        function showStaticToast(message) {
            const existing = document.getElementById('static-demo-toast');
            if (existing) existing.remove();

            const toast = document.createElement('div');
            toast.id = 'static-demo-toast';
            toast.style.cssText = `
                position: fixed;
                bottom: 24px;
                right: 24px;
                background: linear-gradient(135deg, #1e293b, #0f172a);
                color: #f8fafc;
                padding: 16px 24px;
                border-radius: 12px;
                box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.3), 0 8px 10px -6px rgba(0, 0, 0, 0.3);
                z-index: 999999;
                font-family: 'Inter', sans-serif;
                font-size: 14px;
                font-weight: 500;
                display: flex;
                align-items: center;
                gap: 12px;
                border: 1px solid rgba(255, 255, 255, 0.1);
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            `;
            toast.innerHTML = '<i class="fas fa-info-circle" style="color: #fb923c;"></i> <span>' + message + '</span>';
            document.body.appendChild(toast);

            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 100);

            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(20px)';
                setTimeout(() => toast.remove(), 300);
            }, 4000);
        }

        // Intercept logout routes
        document.addEventListener('click', function(e) {
            const link = e.target.closest('a');
            if (link) {
                const href = link.getAttribute('href');
                if (href && (href.endsWith('/logout') || href.includes('logout.html') || href.endsWith('/admin/logout'))) {
                    e.preventDefault();
                    window.location.href = getRedirectUrl('login.html');
                }
            }
        });

        // Intercept form submissions to prevent 405 Method Not Allowed
        document.addEventListener('submit', function(e) {
            // Check if it's the login form (login page has its own handler)
            if (e.target.closest('form[action="/login"]') || e.target.closest('form[action="/admin/login"]')) {
                return;
            }
            e.preventDefault();
            showStaticToast('Demo Mode: Submitting forms is disabled in this static GitHub Pages demo.');
        });
    })();
</script>

</body>
</html>
