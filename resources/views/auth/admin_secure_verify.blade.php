<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAPS Admin Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Cinzel:wght@600;700;800&display=swap" rel="stylesheet">
    
    <!-- EmailJS SDK Browser Integration -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>

    <style>
        :root {
            --baps-red: #d32f2f;
            --baps-dark-red: #9a0007;
            --baps-gold: #ffb300;
            --baps-cream: #fffdf8;
        }

        body {
            background-color: #fbf9f2;
            background-image: 
                radial-gradient(circle at center, transparent 0%, #f3ede0 100%), 
                url('https://www.transparenttextures.com/patterns/mandala-pattern.png');
            font-family: 'Outfit', sans-serif;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            overflow-x: hidden;
            position: relative;
            padding: 20px;
            transition: background 0.4s ease;
        }

        /* Ambient glowing background */
        .bg-glow {
            position: absolute;
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(211,47,47,0.06) 0%, transparent 70%);
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 0;
            animation: pulseGlow 5s infinite alternate;
            pointer-events: none;
        }

        .baps-card {
            background: rgba(255, 255, 255, 0.94);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-radius: 28px;
            box-shadow: 
                0 25px 60px rgba(154, 0, 7, 0.08), 
                0 10px 25px rgba(0, 0, 0, 0.04),
                inset 0 1px 0 rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(211, 47, 47, 0.12);
            position: relative;
            z-index: 10;
            overflow: hidden;
            width: 100%;
            max-width: 440px;
            animation: slideUp 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            transition: all 0.3s ease;
        }

        .baps-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 6px;
            background: linear-gradient(90deg, var(--baps-red), var(--baps-gold), var(--baps-red));
            background-size: 200% 100%;
            animation: gradientMove 4s linear infinite;
        }

        .security-icons {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.25rem;
            margin-bottom: 1.5rem;
            position: relative;
        }

        .guard-icon {
            color: var(--baps-red);
            opacity: 0.75;
            font-size: 1.5rem;
            filter: drop-shadow(0 4px 6px rgba(211,47,47,0.15));
            transition: all 0.3s ease;
        }

        .central-shield {
            color: var(--baps-gold);
            font-size: 3.5rem;
            filter: drop-shadow(0 8px 24px rgba(255,179,0,0.45));
            animation: float 3s ease-in-out infinite;
            transition: all 0.3s ease;
        }

        .baps-title {
            font-family: 'Cinzel', serif;
            background: linear-gradient(135deg, var(--baps-red) 0%, var(--baps-dark-red) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-weight: 800;
            letter-spacing: 2px;
            font-size: 1.75rem;
            margin-bottom: 0.5rem;
            text-transform: uppercase;
        }

        .subtitle {
            font-size: 0.95rem;
            color: #475569;
            font-weight: 500;
            line-height: 1.6;
            margin-bottom: 1.8rem;
            padding: 0 15px;
        }

        .mfa-input-wrapper {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .mfa-input {
            width: 100%;
            background: #fffdf8;
            border: 2px solid rgba(255, 179, 0, 0.35);
            border-radius: 16px;
            padding: 1rem;
            font-size: 1.25rem;
            font-weight: 700;
            letter-spacing: 6px;
            text-align: center;
            color: var(--baps-dark-red);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
        }

        .mfa-input:focus {
            outline: none;
            border-color: var(--baps-red);
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.08), inset 0 2px 4px rgba(0,0,0,0.01);
            transform: translateY(-1.5px);
        }

        .mfa-input::placeholder {
            color: #d32f2f;
            opacity: 0.3;
            letter-spacing: 2px;
            font-size: 1rem;
            font-weight: 600;
        }

        /* Email field input box */
        .email-field-box {
            width: 100%;
            background: #fffdf8;
            border: 2px solid rgba(2, 132, 199, 0.3);
            border-radius: 16px;
            padding: 0.9rem;
            font-size: 1.05rem;
            font-weight: 600;
            color: #1e293b;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.01);
        }
        .email-field-box:focus {
            outline: none;
            border-color: #0284c7;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(2, 132, 199, 0.08);
            transform: translateY(-1px);
        }

        .verify-btn {
            background: linear-gradient(135deg, var(--baps-red) 0%, var(--baps-dark-red) 100%);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 1rem;
            font-weight: 700;
            font-size: 0.95rem;
            letter-spacing: 1px;
            width: 100%;
            text-transform: uppercase;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 20px rgba(211, 47, 47, 0.2);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .verify-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(211, 47, 47, 0.3);
            background: linear-gradient(135deg, #e53935 0%, #b71c1c 100%);
        }

        .verify-btn::after {
            content: '';
            position: absolute;
            top: 0; left: -100%; width: 50%; height: 100%;
            background: linear-gradient(to right, transparent, rgba(255,255,255,0.15), transparent);
            transform: skewX(-25deg);
            transition: all 0.75s ease;
        }

        .verify-btn:hover::after {
            left: 150%;
        }

        /* Email Action Area */
        .email-action-wrapper {
            margin-bottom: 1.5rem;
            display: none;
        }
        .email-send-btn {
            background: linear-gradient(135deg, var(--baps-gold) 0%, #d89600 100%);
            color: #3b2300;
            border: none;
            border-radius: 16px;
            padding: 1rem;
            font-weight: 700;
            font-size: 0.95rem;
            width: 100%;
            text-transform: uppercase;
            box-shadow: 0 8px 20px rgba(255, 179, 0, 0.25);
            transition: all 0.25s ease;
        }
        .email-send-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 25px rgba(255, 179, 0, 0.35);
        }
        .email-send-btn:disabled {
            background: #cbd5e1;
            color: #64748b;
            transform: none;
            box-shadow: none;
            cursor: not-allowed;
        }

        /* Options Panel layout */
        .extra-options-wrapper {
            margin-top: 1.5rem;
            border-top: 1px dashed rgba(211, 47, 47, 0.12);
            padding-top: 1.5rem;
        }
        .options-title {
            font-size: 11px;
            text-transform: uppercase;
            font-weight: 700;
            color: #64748b;
            letter-spacing: 1.2px;
            margin-bottom: 12px;
            text-align: left;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .options-title i {
            color: var(--baps-gold);
        }
        .options-list {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .option-item-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(211, 47, 47, 0.08);
            border-radius: 12px;
            padding: 12px 16px;
            text-align: left;
            width: 100%;
            cursor: pointer;
            transition: all 0.25s ease;
        }
        .option-item-btn:hover {
            background: #ffffff;
            border-color: rgba(255, 179, 0, 0.5);
            box-shadow: 0 4px 12px rgba(154, 0, 7, 0.04);
            transform: translateY(-1.5px);
        }
        .option-icon-box {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: rgba(211, 47, 47, 0.05);
            color: var(--baps-red);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            transition: all 0.25s ease;
        }
        .option-item-btn:hover .option-icon-box {
            background: rgba(255, 179, 0, 0.15);
            color: var(--baps-dark-red);
        }
        .option-details {
            display: flex;
            flex-direction: column;
        }
        .option-name {
            font-size: 13.5px;
            font-weight: 600;
            color: #1e293b;
        }
        .option-desc {
            font-size: 11px;
            color: #64748b;
        }

        /* Return Link */
        .return-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--baps-red);
            font-weight: 600;
            font-size: 0.9rem;
            text-decoration: none;
            opacity: 0.85;
            transition: all 0.3s ease;
            margin-top: 1.5rem;
            width: 100%;
            justify-content: center;
        }
        .return-link:hover {
            opacity: 1;
            transform: translateX(-3px);
            color: var(--baps-dark-red);
        }

        /* Toast notifications */
        .toast-container {
            position: fixed;
            top: 24px;
            right: 24px;
            z-index: 9999;
        }
        .custom-toast {
            background: #ffffff;
            border-left: 4px solid var(--baps-gold);
            border-radius: 12px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            min-width: 320px;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        .custom-toast.show {
            transform: translateX(0);
        }
        .toast-icon {
            color: var(--baps-gold);
            font-size: 22px;
        }
        .toast-title {
            font-size: 13.5px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 2px;
        }
        .toast-msg {
            font-size: 12.5px;
            color: var(--baps-dark-red);
            font-weight: bold;
        }

        /* Mode active visual states */
        body.recovery-active .central-shield {
            color: var(--baps-red);
            filter: drop-shadow(0 8px 24px rgba(211,47,47,0.4));
        }
        body.recovery-active .guard-icon {
            color: var(--baps-gold);
        }
        body.email-active .central-shield {
            color: #0284c7;
            filter: drop-shadow(0 8px 24px rgba(2,132,199,0.45));
        }

        /* Animations */
        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-6px); }
        }
        @keyframes pulseGlow {
            from { transform: translate(-50%, -50%) scale(1); opacity: 0.6; }
            to { transform: translate(-50%, -50%) scale(1.05); opacity: 1; }
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }
    </style>
</head>
<body>

<div class="bg-glow"></div>

<!-- Custom toast for Email OTP Dispatch -->
<div class="toast-container">
    <div id="otpToast" class="custom-toast">
        <i class="fas fa-envelope-open-text toast-icon" id="toastIconStyle" style="color: #0284c7;"></i>
        <div class="toast-content">
            <div class="toast-title" id="toastHeader">Email OTP Dispatched</div>
            <div class="toast-msg" id="toastOtpText">OTP code sent to email successfully.</div>
        </div>
    </div>
</div>

<div class="container position-relative">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5 col-xl-4 d-flex justify-content-center">
            <div class="baps-card p-4 p-md-5 text-center">
                
                <div class="security-icons">
                    <i class="fas fa-user-lock guard-icon" id="leftGuardIcon"></i>
                    <i class="fas fa-shield-alt central-shield" id="mainShieldIcon"></i>
                    <i class="fas fa-user-lock guard-icon" id="rightGuardIcon"></i>
                </div>

                <!-- Dynamic Page Title & Instruction -->
                <h3 class="baps-title" id="pageTitle">BAPS Admin Protocol</h3>
                <p class="subtitle" id="pageInstruction">
                    Level-1 credentials verified. Please present the Master MFA Code to authorize secure bypass.
                </p>
                
                @if(session('error'))
                    <div class="alert alert-danger fw-bold small py-3 px-3 border-0 bg-danger bg-opacity-10 text-danger rounded-4 shadow-sm mb-4 d-flex align-items-center justify-content-center gap-2">
                        <i class="fas fa-exclamation-triangle fs-5"></i> 
                        <span>{{ session('error') }}</span>
                    </div>
                @endif
                
                <form action="/admin/secure-verify" method="POST" id="verifyForm">
                    @csrf
                    
                    <!-- Send Email UI Container (Visible in Email mode initially) -->
                    <div class="email-action-wrapper" id="emailActionWrapper">
                        <div class="mb-3 text-start">
                            <label class="form-label small fw-bold text-secondary">Verify & Receive OTP via Email</label>
                            <input type="email" id="emailjsTargetField" class="email-field-box" placeholder="Enter email address" value="{{ $email }}" required>
                        </div>
                        <button type="button" class="email-send-btn" id="emailSendBtn" onclick="requestEmailOtp()">
                            <i class="fas fa-paper-plane me-2"></i> Send Verification Email
                        </button>
                    </div>

                    <!-- Input code field -->
                    <div class="mfa-input-wrapper" id="inputWrapper">
                        <input type="password" name="admin_code" id="mfaInputField" class="mfa-input" placeholder="ENTER SECURE CODE" required autofocus autocomplete="off">
                        
                        <!-- Remember Me Option -->
                        <div class="mt-3 d-flex align-items-center justify-content-center gap-2">
                            <input class="form-check-input" type="checkbox" name="remember_device" id="rememberDevice" style="
                                cursor: pointer;
                                width: 17px;
                                height: 17px;
                                border: 2px solid rgba(211, 47, 47, 0.35);
                                border-radius: 5px;
                                margin-top: 0;
                                accent-color: var(--baps-red);
                            ">
                            <label class="small fw-semibold text-secondary mb-0" for="rememberDevice" style="cursor: pointer; user-select: none;">
                                Trust this device for 15 days
                            </label>
                        </div>
                    </div>
                    
                    <button type="submit" class="verify-btn" id="submitBtn">
                        <i class="fas fa-fingerprint fs-5"></i> Authorize Access
                    </button>
                </form>

                <!-- Authentication options grid -->
                <div class="extra-options-wrapper">
                    <div class="options-title">
                        <i class="fas fa-shield-alt"></i> Authentication Options
                    </div>
                    <div class="options-list" id="optionsList">
                        <!-- Option: Verify via Email OTP (Toggles Email OTP Mode) -->
                        <div class="option-item-btn" id="optEmailOtp" onclick="switchMode('email_otp')">
                            <div class="option-icon-box">
                                <i class="fas fa-envelope-open-text"></i>
                            </div>
                            <div class="option-details">
                                <span class="option-name">Verify via Email OTP</span>
                                <span class="option-desc">Send 6-digit email code to custom email</span>
                            </div>
                        </div>

                        <!-- Option: Emergency Recovery Key (Toggles Recovery Mode) -->
                        <div class="option-item-btn" id="optRecovery" onclick="switchMode('recovery')">
                            <div class="option-icon-box">
                                <i class="fas fa-key"></i>
                            </div>
                            <div class="option-details">
                                <span class="option-name">Emergency Recovery Key</span>
                                <span class="option-desc">Authenticate using your master backup key</span>
                            </div>
                        </div>
                        
                        <!-- Option: Standard MFA (Only visible when not in MFA mode) -->
                        <div class="option-item-btn" id="optMfa" style="display: none;" onclick="switchMode('mfa')">
                            <div class="option-icon-box">
                                <i class="fas fa-fingerprint"></i>
                            </div>
                            <div class="option-details">
                                <span class="option-name">Use Master MFA Code</span>
                                <span class="option-desc">Authenticate using time-based MFA token</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <a href="/admin/login" class="return-link">
                    <i class="fas fa-arrow-left"></i> Disengage & Return
                </a>

            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Global JavaScript Error Logger to Laravel Logs
    window.addEventListener('error', function(event) {
        fetch('/admin/log-emailjs-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                type: 'window_error',
                message: event.message,
                filename: event.filename,
                lineno: event.lineno,
                colno: event.colno,
                error_stack: event.error ? event.error.stack : null
            })
        }).catch(err => console.error('Failed to log client-side window error:', err));
    });

    window.addEventListener('unhandledrejection', function(event) {
        fetch('/admin/log-emailjs-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                type: 'unhandled_promise_rejection',
                reason: event.reason ? (event.reason.message || event.reason.toString()) : null,
                stack: event.reason && event.reason.stack ? event.reason.stack : null
            })
        }).catch(err => console.error('Failed to log client-side promise rejection:', err));
    });

    // Load EmailJS environment configuration
    const emailjsPublicKey = "{{ config('services.emailjs.public_key') }}";
    const emailjsServiceId = "{{ config('services.emailjs.service_id') }}";
    const emailjsTemplateId = "{{ config('services.emailjs.template_id') }}";

    // Initialize EmailJS if key is provided
    if (emailjsPublicKey && emailjsPublicKey !== "YOUR_PUBLIC_KEY") {
        if (typeof emailjs !== 'undefined') {
            emailjs.init({
                publicKey: emailjsPublicKey,
            });
        } else {
            console.error('EmailJS SDK not loaded from CDN.');
        }
    }

    // Modes: 'mfa', 'email_otp', 'recovery'
    let currentMode = 'mfa';
    let emailResendTimer = null;

    // Switch verification mode UI
    function switchMode(mode) {
        currentMode = mode;
        const body = document.body;
        const title = document.getElementById('pageTitle');
        const instruction = document.getElementById('pageInstruction');
        const input = document.getElementById('mfaInputField');
        const inputWrapper = document.getElementById('inputWrapper');
        const emailWrapper = document.getElementById('emailActionWrapper');
        const submitBtn = document.getElementById('submitBtn');
        
        // Options buttons
        const optEmailOtp = document.getElementById('optEmailOtp');
        const optRecovery = document.getElementById('optRecovery');
        const optMfa = document.getElementById('optMfa');

        // Reset visual classes
        body.classList.remove('recovery-active', 'email-active');
        emailWrapper.style.display = 'none';
        inputWrapper.style.display = 'block';
        submitBtn.style.display = 'flex';
        input.value = "";

        if (mode === 'mfa') {
            title.textContent = "BAPS Admin Protocol";
            instruction.textContent = "Level-1 credentials verified. Please present the Master MFA Code to authorize secure bypass.";
            input.placeholder = "ENTER SECURE CODE";
            submitBtn.innerHTML = '<i class="fas fa-fingerprint fs-5"></i> Authorize Access';
            
            optMfa.style.display = 'none';
            optEmailOtp.style.display = 'flex';
            optRecovery.style.display = 'flex';
            input.focus();
        } 
        else if (mode === 'email_otp') {
            body.classList.add('email-active');
            title.textContent = "Email OTP Verify";
            instruction.textContent = "Please enter your email address below to receive the 6-digit verification code.";
            
            // Hide input and authorization button initially until Email is sent
            inputWrapper.style.display = 'none';
            submitBtn.style.display = 'none';
            emailWrapper.style.display = 'block';

            optMfa.style.display = 'flex';
            optEmailOtp.style.display = 'none';
            optRecovery.style.display = 'flex';
            document.getElementById('emailjsTargetField').focus();
        } 
        else if (mode === 'recovery') {
            body.classList.add('recovery-active');
            title.textContent = "Emergency Recovery";
            instruction.textContent = "Please present your Master Emergency Recovery Key (e.g. BAPS2026RECOVERY) to override.";
            input.placeholder = "ENTER RECOVERY KEY";
            submitBtn.innerHTML = '<i class="fas fa-key fs-5"></i> Execute Override';

            optMfa.style.display = 'flex';
            optEmailOtp.style.display = 'flex';
            optRecovery.style.display = 'none';
            input.focus();
        }
    }

    // Dispatch OTP via EmailJS to the user's typed email
    function requestEmailOtp() {
        const emailInput = document.getElementById('emailjsTargetField');
        const userEmail = emailInput.value.trim();

        if (!userEmail || !userEmail.includes('@')) {
            alert('Please enter a valid email address.');
            emailInput.focus();
            return;
        }

        const sendBtn = document.getElementById('emailSendBtn');
        sendBtn.disabled = true;
        sendBtn.innerHTML = '<i class="fas fa-circle-notch fa-spin me-2"></i> Generating...';

        // Call backend to generate code in session, passing the typed email
        fetch('/admin/send-otp', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ email: userEmail })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // If EmailJS credentials are set, trigger dispatch via EmailJS SDK
                if (emailjsPublicKey && emailjsServiceId && emailjsTemplateId && emailjsPublicKey !== "YOUR_PUBLIC_KEY") {
                    sendBtn.innerHTML = '<i class="fas fa-paper-plane fa-spin me-2"></i> Sending Email...';
                    
                    const templateParams = {
                        // Recipient Email options
                        to_email: data.email,
                        email: data.email,
                        user_email: data.email,
                        to: data.email,
                        recipient: data.email,
                        recipient_email: data.email,

                        // Recipient Name options
                        to_name: data.name,
                        name: data.name,
                        user_name: data.name,

                        // OTP options
                        passcode: data.otp,
                        otp: data.otp,
                        code: data.otp,

                        // Other details
                        office_no: "Office No: 402 (Dean Block)",
                        office: "Office No: 402 (Dean Block)",
                        request_time: new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit', second: '2-digit' }),
                        portal_name: "BAPS SVM LMS Academic Portal",
                        time: new Date(new Date().getTime() + 15 * 60000).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' }),
                        ip_address: data.ip,
                        ip: data.ip
                    };

                    emailjs.send(emailjsServiceId, emailjsTemplateId, templateParams)
                        .then(function(response) {
                            console.log('EmailJS SUCCESS!', response.status, response.text);
                            // Report client-side EmailJS success to server logs
                            fetch('/admin/log-emailjs-status', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    type: 'emailjs_success',
                                    status: response.status,
                                    text: response.text,
                                    emailjsServiceId: emailjsServiceId,
                                    emailjsTemplateId: emailjsTemplateId,
                                    targetEmail: data.email
                                })
                            }).catch(err => console.error('Failed to report EmailJS success to server:', err));

                            completeEmailOtpDispatch(data.otp, data.email, false);
                        }, function(error) {
                            console.error('EmailJS FAILED...', error);
                            // Report client-side EmailJS dispatch failure to server logs
                            fetch('/admin/log-emailjs-status', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                body: JSON.stringify({
                                    type: 'emailjs_send_failure',
                                    error: error,
                                    emailjsServiceId: emailjsServiceId,
                                    emailjsTemplateId: emailjsTemplateId,
                                    targetEmail: data.email
                                })
                            }).catch(err => console.error('Failed to report EmailJS error to server:', err));

                            // Fallback to demo mode if EmailJS fails so you don't get locked out
                            completeEmailOtpDispatch(data.otp, data.email, true, "EmailJS Error: " + JSON.stringify(error));
                        });
                } else {
                    // Fallback to local demo mode if credentials are missing
                    completeEmailOtpDispatch(data.otp, data.email, true);
                }
            } else {
                alert('Error generating OTP. Please try again.');
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Send Verification Email';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('A connection error occurred. Please try again.');
            sendBtn.disabled = false;
            sendBtn.innerHTML = '<i class="fas fa-paper-plane me-2"></i> Send Verification Email';
        });
    }

    // Switch input state and show toast when email dispatch is processed
    function completeEmailOtpDispatch(otp, targetEmail, isDemo, errorMessage = null) {
        document.getElementById('inputWrapper').style.display = 'block';
        document.getElementById('submitBtn').style.display = 'flex';
        
        const input = document.getElementById('mfaInputField');
        input.placeholder = "ENTER 6-DIGIT OTP";
        input.focus();
        
        document.getElementById('submitBtn').innerHTML = '<i class="fas fa-shield-alt fs-5"></i> Verify OTP & Access';
        document.getElementById('pageInstruction').textContent = "OTP dispatched. Enter the 6-digit verification code sent to " + targetEmail + ".";

        const toastIcon = document.getElementById('toastIconStyle');
        const toastHeader = document.getElementById('toastHeader');
        const toastText = document.getElementById('toastOtpText');
        const toast = document.getElementById('otpToast');

        if (isDemo) {
            // Do not show the demo mode toast on screen to avoid exposing the OTP
            console.log("Demo Mode Active. OTP Code: " + otp + (errorMessage ? " | Error: " + errorMessage : ""));
        } else {
            toastIcon.className = "fas fa-envelope-open-text toast-icon";
            toastIcon.style.color = "#0284c7";
            toastHeader.textContent = "Email OTP Dispatched";
            toastText.textContent = "The secure verification code was sent directly to " + targetEmail + ".";
            toastText.style.color = "#0284c7";
            toast.classList.add('show');
            setTimeout(() => { toast.classList.remove('show'); }, 6000);
        }

        // Start Email Resend Countdown
        startResendCountdown(30);
    }

    // Email Resend timer implementation
    function startResendCountdown(seconds) {
        const sendBtn = document.getElementById('emailSendBtn');
        let timeLeft = seconds;

        if (emailResendTimer) {
            clearInterval(emailResendTimer);
        }

        emailResendTimer = setInterval(() => {
            timeLeft--;
            if (timeLeft <= 0) {
                clearInterval(emailResendTimer);
                sendBtn.disabled = false;
                sendBtn.innerHTML = '<i class="fas fa-redo me-2"></i> Resend Verification Email';
            } else {
                sendBtn.disabled = true;
                sendBtn.innerHTML = `<i class="fas fa-hourglass-half me-2"></i> Resend Email in ${timeLeft}s`;
            }
        }, 1000);
    }
</script>
</body>
</html>
