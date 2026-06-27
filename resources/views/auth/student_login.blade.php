<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Passcode Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 1.5rem;
            box-shadow: 0 15px 35px rgba(0,0,0,0.05);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
        }
        .login-header {
            background: linear-gradient(135deg, #4f46e5, #3b82f6);
            color: white;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        .form-control {
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
        .form-control:focus {
            box-shadow: 0 0 0 0.25rem rgba(79, 70, 229, 0.25);
            border-color: #4f46e5;
        }
        .btn-brand {
            background: linear-gradient(135deg, #4f46e5, #4338ca);
            color: white;
            border-radius: 0.75rem;
            padding: 0.75rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-brand:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(79, 70, 229, 0.3);
            color: white;
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

<div class="login-card">
    <div class="login-header">
        <div class="mb-3">
            <i class="fas fa-user-graduate" style="font-size: 3rem;"></i>
        </div>
        <h3 class="fw-bold mb-1">Student Portal</h3>
        <p class="mb-0 text-white-50">Log in using your 5-digit passcode.</p>
    </div>

    <div class="p-4 p-md-5">
        @if(session('info'))
            <div class="alert alert-info py-2 small fw-bold text-center mb-4">
                <i class="fas fa-info-circle me-1"></i> {{ session('info') }}
            </div>
        @endif
        @if($errors->any())
            <div class="alert alert-danger py-2 small fw-bold text-center mb-4">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="/login" method="POST">
            @csrf
            <div class="mb-4">
                <label class="form-label text-muted fw-bold small">Email or Enrollment Number</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-user"></i></span>
                    <input type="text" name="email" class="form-control border-start-0" placeholder="e.g. 210100..." required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label text-muted fw-bold small">Authorized 5-Digit Passcode</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="fas fa-key"></i></span>
                    <input type="password" name="login_code" class="form-control border-start-0" placeholder="•••••" maxlength="5" pattern="\d{5}" title="Please enter exactly 5 numbers" required>
                </div>
            </div>

            <button type="submit" class="btn btn-brand w-100 mb-4">
                <i class="fas fa-sign-in-alt me-2"></i> Enter Authorized Session
            </button>

            <div class="text-center">
                <p class="text-muted small mb-0">New Student?</p>
                <a href="/register" class="text-primary text-decoration-none fw-bold small">Apply for Registration</a>
                <div class="mt-3 pt-3 border-top">
                    <a href="/user-manual" class="text-muted text-decoration-none small"><i class="fas fa-book me-1"></i> System User Manual</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
