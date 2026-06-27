<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAPS-e.learn-LMS | Secure Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        :root {
            --primary: #ea580c;
            --primary-light: #f97316;
            --primary-dark: #c2410c;
            --surface: #ffffff;
            --background: #f8fafc;
            --text-main: #0f172a;
            --text-muted: #64748b;
        }

        body { 
            font-family: 'Outfit', sans-serif; 
            margin: 0; 
            padding: 0; 
            height: 100vh; 
            overflow: hidden; 
            background: var(--background); 
        }

        .split-layout { display: flex; height: 100vh; }
        
        /* Left Panel - Dynamic Imagery */
        .carousel-panel { 
            width: 55%; 
            position: relative; 
            overflow: hidden; 
        }
        .carousel-image { 
            width: 100%; 
            height: 100%; 
            object-fit: cover; 
            position: absolute; 
            top: 0; 
            left: 0; 
            opacity: 0; 
            transition: opacity 1.5s cubic-bezier(0.4, 0, 0.2, 1), transform 6s linear; 
            transform: scale(1.05);
        }
        .carousel-image.active { 
            opacity: 1; 
            transform: scale(1);
        }
        
        .overlay-gradient {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: linear-gradient(180deg, rgba(0,0,0,0.1) 0%, rgba(0,0,0,0.8) 100%);
            z-index: 1;
        }

        .carousel-content {
            position: absolute; 
            bottom: 10%; 
            left: 8%; 
            z-index: 2;
            color: white;
            max-width: 80%;
            animation: fadeInUp 1s ease-out;
        }

        .carousel-content h2 {
            font-size: 3.5rem;
            font-weight: 700;
            line-height: 1.1;
            margin-bottom: 1rem;
            text-shadow: 0 4px 20px rgba(0,0,0,0.4);
            letter-spacing: -1px;
        }

        .carousel-content p {
            font-size: 1.25rem;
            opacity: 0.9;
            font-weight: 300;
            border-left: 4px solid var(--primary);
            padding-left: 1rem;
            text-shadow: 0 2px 10px rgba(0,0,0,0.4);
        }

        /* Right Panel - Authentication */
        .login-panel { 
            width: 45%; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            position: relative; 
            background: var(--surface);
            box-shadow: -20px 0 40px rgba(0,0,0,0.05);
            z-index: 10;
        }
        
        .login-box { 
            width: 100%; 
            max-width: 460px; 
            padding: 2.5rem; 
            animation: slideInRight 0.8s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .logo-box { 
            width: 54px; 
            height: 54px; 
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%); 
            border-radius: 16px; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            color: white; 
            font-size: 24px; 
            box-shadow: 0 8px 20px rgba(234, 88, 12, 0.25); 
            transition: transform 0.3s ease;
        }
        .logo-box:hover { transform: scale(1.05) rotate(-5deg); }

        h2.welcome-text { 
            font-weight: 700; 
            color: var(--text-main); 
            margin-bottom: 6px; 
            font-size: 2rem;
            letter-spacing: -0.5px;
        }
        p.subtitle { color: var(--text-muted); font-size: 15px; margin-bottom: 32px; font-weight: 400; }
        
        /* Role Toggles */
        .nav-pills { 
            background: var(--background); 
            border-radius: 12px; 
            padding: 6px; 
            margin-bottom: 32px; 
            border: 1px solid rgba(0,0,0,0.03);
        }
        .nav-pills .nav-link { 
            border-radius: 8px; 
            font-weight: 600; 
            color: var(--text-muted); 
            padding: 12px 0; 
            transition: all 0.3s ease;
        }
        .nav-pills .nav-link.active { 
            background: var(--surface); 
            color: var(--primary); 
            box-shadow: 0 4px 15px rgba(0,0,0,0.08); 
        }
        
        /* Form Controls */
        .form-floating > .form-control {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            font-weight: 500;
            color: var(--text-main);
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.01);
        }
        .form-floating > .form-control:focus {
            border-color: var(--primary-light);
            box-shadow: 0 0 0 4px rgba(234, 88, 12, 0.1);
        }
        .form-floating > label {
            color: var(--text-muted);
            font-weight: 400;
        }
        
        /* Buttons */
        .btn-submit { 
            background: linear-gradient(135deg, var(--primary-light) 0%, var(--primary) 100%); 
            border: none; 
            padding: 14px; 
            border-radius: 12px; 
            font-weight: 600; 
            font-size: 16px;
            width: 100%; 
            color: white; 
            transition: all 0.3s ease; 
            box-shadow: 0 8px 20px rgba(234, 88, 12, 0.2); 
            position: relative;
            overflow: hidden;
        }
        .btn-submit:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 12px 25px rgba(234, 88, 12, 0.3); 
        }
        .btn-submit::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.2), transparent);
            transform: skewX(-25deg);
            transition: all 0.5s ease;
        }
        .btn-submit:hover::after { left: 150%; }

        .btn-social {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            background: var(--surface);
            border: 1.5px solid #e2e8f0;
            color: var(--text-main);
            font-weight: 600;
            padding: 12px;
            border-radius: 12px;
            width: 100%;
            transition: all 0.2s ease;
            text-decoration: none;
        }
        .btn-social:hover {
            background: var(--background);
            transform: translateY(-1px);
            border-color: #cbd5e1;
        }
        .btn-social img { width: 20px; height: 20px; }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 24px 0;
            color: var(--text-muted);
            font-size: 13px;
            font-weight: 500;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #e2e8f0;
        }
        .divider:not(:empty)::before { margin-right: 15px; }
        .divider:not(:empty)::after { margin-left: 15px; }

        /* Maintenance */
        .maintenance-ribbon { 
            position: absolute; 
            bottom: 0; 
            left: 0; 
            width: 100%; 
            background: rgba(254, 226, 226, 0.9); 
            backdrop-filter: blur(10px);
            border-top: 1px solid #fca5a5; 
            padding: 12px 24px; 
            text-align: center; 
            color: #b91c1c; 
            font-size: 13px; 
            font-weight: 600; 
            display: flex; 
            align-items: center; 
            justify-content: center; 
            gap: 8px; 
        }

        .footer-links {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        .footer-links a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: color 0.2s;
        }
        .footer-links a:hover { color: var(--primary); }

        /* Animations */
        @keyframes slideInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 991px) {
            .carousel-panel { display: none; }
            .login-panel { width: 100%; }
            .login-box { padding: 2rem; }
        }
    </style>
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

<style>
@keyframes spin-clockwise {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
@keyframes spin-counterclockwise {
    0% { transform: rotate(360deg); }
    100% { transform: rotate(0deg); }
}
@keyframes pulse-glow {
    0% { transform: scale(0.96); box-shadow: 0 0 20px rgba(234, 88, 12, 0.4); }
    100% { transform: scale(1.04); box-shadow: 0 0 45px rgba(234, 88, 12, 0.8), 0 0 20px rgba(212, 175, 55, 0.6); }
}
</style>

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

<div class="split-layout">
    <!-- Left Screen: Image Carousel -->
    <div class="carousel-panel">
        <div class="overlay-gradient"></div>
        <img src="/images/auth/carousel_student_reading_1774540389353.png" class="carousel-image active">
        <img src="/images/auth/carousel_university_gate_1774540410147.png" class="carousel-image">
        <img src="/images/auth/carousel_students_studying_1774540436742.png" class="carousel-image">
        <img src="/images/auth/carousel_premium_lounge_1774540478014.png" class="carousel-image">
        <img src="/images/auth/baps_campus_day_1774540583089.png" class="carousel-image">
        <img src="/images/auth/baps_campus_night_1774540609401.png" class="carousel-image">
        
        <div class="carousel-content">
            <span class="badge bg-white text-dark mb-3 px-3 py-2 rounded-pill fw-bold shadow-sm" style="font-size: 14px;"><i class="fas fa-star text-warning me-1"></i> #1 Ranked Institution</span>
            <h2>BAPS Innovation Campus</h2>
            <p>Where visionary values meet state-of-the-art modern education. Join our community of global leaders today.</p>
        </div>
    </div>

    <!-- Right Screen: Authentication Portal -->
    <div class="login-panel">
        <div class="login-box">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="logo-box"><i class="fas fa-graduation-cap"></i></div>
                <div>
                    <h4 class="fw-bold mb-0 text-dark fs-3" style="letter-spacing: -0.5px;">BAPS-e.learn-LMS</h4>
                    <span class="badge bg-light text-secondary border mt-1">Enterprise Portal</span>
                </div>
            </div>
            
            <h2 class="welcome-text">Welcome Back</h2>
            <p class="subtitle">Please enter your details to sign in to your workspace.</p>

            @if(session('error'))
                <div class="alert alert-danger py-2 px-3 small fw-bold border-0 bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center mb-4"><i class="fas fa-exclamation-circle me-2 fs-5"></i> {{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success py-2 px-3 small fw-bold border-0 bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center mb-4"><i class="fas fa-check-circle me-2 fs-5"></i> {{ session('success') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger py-2 px-3 small fw-bold border-0 bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center mb-4"><i class="fas fa-exclamation-circle me-2 fs-5"></i> {{ $errors->first() }}</div>
            @endif

            <!-- Role Toggle Pills -->
            <ul class="nav nav-pills nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-student-tab" data-bs-toggle="pill" data-bs-target="#pills-student" type="button" role="tab"><i class="fas fa-user-graduate me-2"></i> Student Portal</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-staff-tab" data-bs-toggle="pill" data-bs-target="#pills-staff" type="button" role="tab"><i class="fas fa-chalkboard-teacher me-2"></i> Faculty / Staff</button>
                </li>
            </ul>

            <div class="tab-content" id="pills-tabContent">
                <!-- Student Form -->
                <div class="tab-pane fade show active" id="pills-student" role="tabpanel">
                    <form method="POST" action="/login">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="email" class="form-control" id="studentEmail" placeholder="name@example.com" required>
                            <label for="studentEmail">Email or Enrollment No.</label>
                        </div>
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="password" class="form-control" id="studentPassword" placeholder="Password" required>
                            <label for="studentPassword">Password</label>
                            <i class="fas fa-eye-slash text-muted position-absolute" style="right: 16px; top: 20px; cursor: pointer; z-index: 10;"></i>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label text-muted small fw-medium" for="rememberMe">Remember me</label>
                            </div>
                            <a href="/forgot-password" class="text-decoration-none small fw-bold" style="color: var(--primary);">Forgot Password?</a>
                        </div>
                        
                        <button type="submit" class="btn-submit mb-3">Sign In as Student <i class="fas fa-arrow-right ms-2"></i></button>
                        
                        <a href="/courses" class="btn btn-light w-100 fw-bold border rounded-3 py-3 text-dark shadow-sm bg-white text-decoration-none d-flex align-items-center justify-content-center" style="border-radius: 12px !important;">
                            <i class="fas fa-user-secret text-secondary me-2 fs-5"></i> Browse as Guest
                        </a>
                    </form>
                </div>

                <!-- Staff Form -->
                <div class="tab-pane fade" id="pills-staff" role="tabpanel">
                    <form method="POST" action="/admin/login" class="mt-4">
                        @csrf
                        <div class="form-floating mb-3">
                            <input type="text" name="username" class="form-control" id="staffEmail" placeholder="staff@institution.edu" required>
                            <label for="staffEmail">Staff Email ID</label>
                        </div>
                        <div class="form-floating mb-3 position-relative">
                            <input type="password" name="password" class="form-control" id="staffPassword" placeholder="Password" required>
                            <label for="staffPassword">Admin Password / Access Code</label>
                            <i class="fas fa-eye-slash text-muted position-absolute" style="right: 16px; top: 20px; cursor: pointer; z-index: 10;"></i>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="showPasswordStaff" onclick="document.getElementById('staffPassword').type = this.checked ? 'text' : 'password'">
                                <label class="form-check-label text-muted small fw-medium" for="showPasswordStaff">Show Password</label>
                            </div>
                            <a href="/forgot-password" class="text-decoration-none small fw-bold" style="color: var(--primary);">Need Help?</a>
                        </div>
                        
                        <button type="submit" class="btn-submit"><i class="fas fa-shield-alt me-2"></i> Authenticate Staff</button>
                    </form>
                </div>
            </div>
            
            <div class="footer-links">
                <a href="/register">Create Student Account</a>
                <a href="/parent/register" style="color: var(--primary); font-weight: bold;"><i class="fas fa-user-shield me-1"></i> Register as Parent</a>
                <a href="/user-manual">User Manual</a>
                <a href="/support">Support</a>
            </div>
            
        </div>
        
        <!-- Maintenance Ribbon -->
        <div class="maintenance-ribbon">
            <i class="fas fa-tools"></i>
            <span>System Maintenance occurs daily between 12:00 AM - 12:30 AM (IST)</span>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Automated Image Carousel Logic
    document.addEventListener("DOMContentLoaded", function () {
        const images = document.querySelectorAll('.carousel-image');
        let currentIndex = 0;

        setInterval(() => {
            images[currentIndex].classList.remove('active');
            currentIndex = (currentIndex + 1) % images.length;
            images[currentIndex].classList.add('active');
        }, 6000); // 6 second interval
        
        // Password toggle logic for student form
        const eyeIcons = document.querySelectorAll('.fa-eye-slash');
        eyeIcons.forEach(icon => {
            icon.addEventListener('click', function() {
                const input = this.previousElementSibling;
                if (input.type === 'password') {
                    input.type = 'text';
                    this.classList.remove('fa-eye-slash');
                    this.classList.add('fa-eye');
                } else {
                    input.type = 'password';
                    this.classList.remove('fa-eye');
                    this.classList.add('fa-eye-slash');
                }
            });
        });

        // Static Demo Login Mocking
        const isStatic = window.location.hostname.includes('github.io') || window.location.protocol === 'file:';
        if (isStatic) {
            const getRedirectUrl = (page) => {
                const pathParts = window.location.pathname.split('/');
                if (pathParts.length > 2 && pathParts[1] !== '') {
                    return '/' + pathParts[1] + '/' + page;
                }
                return '/' + page;
            };

            // Intercept student login form
            const studentForm = document.querySelector('form[action="/login"]');
            if (studentForm) {
                studentForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    window.location.href = getRedirectUrl('dashboard.html');
                });
            }

            // Intercept staff/admin login form
            const staffForm = document.querySelector('form[action="/admin/login"]');
            if (staffForm) {
                staffForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const username = document.getElementById('staffEmail').value;
                    const password = document.getElementById('staffPassword').value;
                    
                    if (username === 'admin.bhavik@baps.ac.in' && password === 'BHAVIKKUMAR@123') {
                        window.location.href = getRedirectUrl('admin.html');
                    } else {
                        alert('Invalid Admin credentials for static demo!\nUse:\nEmail: admin.bhavik@baps.ac.in\nPassword: BHAVIKKUMAR@123');
                    }
                });
            }
        }
    });
</script>
</body>
</html>
