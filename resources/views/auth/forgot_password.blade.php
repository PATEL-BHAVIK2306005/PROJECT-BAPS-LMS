<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAPS-e.learn-LMS | Forgot Password</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; margin: 0; padding: 0; height: 100vh; overflow: hidden; background: #fff; }
        .split-layout { display: flex; height: 100vh; }
        .carousel-panel { width: 50%; position: relative; overflow: hidden; background: #1e293b; }
        .login-panel { width: 50%; display: flex; align-items: center; justify-content: center; position: relative; }
        .carousel-image { width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0; opacity: 1; }
        
        .login-box { width: 100%; max-width: 440px; padding: 2rem; }
        .logo-box { width: 60px; height: 60px; background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); border-radius: 14px; display: flex; align-items: center; justify-content: center; color: white; font-size: 28px; margin-bottom: 24px; box-shadow: 0 4px 15px rgba(234, 88, 12, 0.3); }
        h2 { font-weight: 700; color: #1e293b; margin-bottom: 8px; }
        p.subtitle { color: #64748b; font-size: 15px; margin-bottom: 32px; }
        
        .form-label { font-size: 13px; font-weight: 600; color: #334155; margin-bottom: 6px; }
        .form-control { background: #f8fafc; border: 1px solid #e2e8f0; padding: 12px 16px; border-radius: 8px; font-size: 15px; transition: all 0.2s; }
        .form-control:focus { background: #fff; border-color: #f97316; box-shadow: 0 0 0 3px rgba(249, 115, 22, 0.15); outline: none; }
        .btn-submit { background: #f97316; border: none; padding: 12px; border-radius: 8px; font-weight: 600; width: 100%; color: white; transition: all 0.2s; box-shadow: 0 4px 10px rgba(234, 88, 12, 0.2); }
        .btn-submit:hover { background: #ea580c; box-shadow: 0 6px 15px rgba(234, 88, 12, 0.3); transform: translateY(-1px); }
        
        .maintenance-ribbon { position: absolute; bottom: 0; left: 0; width: 100%; background: #fee2e2; border-top: 1px solid #fca5a5; padding: 12px 24px; text-align: center; color: #b91c1c; font-size: 13px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px; }
        
        @media (max-width: 991px) {
            .carousel-panel { display: none; }
            .login-panel { width: 100%; }
        }
    </style>
</head>
<body>

<div class="split-layout">
    <!-- Left Screen: Image Panel -->
    <div class="carousel-panel">
        <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark" style="opacity: 0.2; z-index: 1;"></div>
        <img src="/images/auth/baps_campus_day_1774540583089.png" class="carousel-image">
        <div class="position-absolute bottom-0 start-0 p-5 text-white" style="z-index: 2;">
            <h2 class="text-white mb-2" style="font-size: 2.5rem; text-shadow: 0 2px 10px rgba(0,0,0,0.3);">BAPS Innovation Campus</h2>
            <p class="fs-5 opacity-75" style="text-shadow: 0 2px 5px rgba(0,0,0,0.3);">Security Protocol & Account Recovery</p>
        </div>
    </div>

    <!-- Right Screen: Forgot Password Portal -->
    <div class="login-panel">
        <div class="login-box">
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="logo-box"><i class="fas fa-key"></i></div>
                <h4 class="fw-bold mb-0 text-dark">LMS Recovery</h4>
            </div>
            
            <h2>Forgot Password</h2>
            <p class="subtitle">Request a new password. An administrator will review and securely set your password.</p>

            @if(session('error'))
                <div class="alert alert-danger py-2 small fw-bold border-0 bg-danger bg-opacity-10 text-danger"><i class="fas fa-exclamation-circle me-1"></i> {{ session('error') }}</div>
            @endif
            @if(session('success'))
                <div class="alert alert-success py-2 small fw-bold border-0 bg-success bg-opacity-10 text-success"><i class="fas fa-check-circle me-1"></i> {{ session('success') }}</div>
            @endif

            <form method="POST" action="/forgot-password">
                @csrf
                <div class="mb-3">
                    <label class="form-label">Email ID</label>
                    <input type="email" name="email" class="form-control" placeholder="example@baps.ac.in" required>
                </div>
                <div class="mb-4">
                    <label class="form-label">New Password to Request</label>
                    <div class="position-relative">
                        <input type="password" name="requested_password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="form-check mt-3">
                        <input class="form-check-input" type="checkbox" id="showPassword" onclick="document.getElementsByName('requested_password')[0].type = this.checked ? 'text' : 'password'">
                        <label class="form-check-label text-muted small fw-bold" for="showPassword">Show Password</label>
                    </div>
                </div>
                <button type="submit" class="btn-submit mb-3"><i class="fas fa-paper-plane me-2"></i> Submit Request to Admin</button>
                <div class="text-center mt-3">
                    <a href="/login" class="text-decoration-none fw-bold" style="color: #64748b;"><i class="fas fa-arrow-left me-1"></i> Back to Login</a>
                </div>
            </form>
        </div>
        
        <!-- Maintenance Ribbon -->
        <div class="maintenance-ribbon">
            <i class="fas fa-shield-alt"></i>
            <span>All password requests are manually audited for security assurance.</span>
        </div>
    </div>
</div>

</body>
</html>
