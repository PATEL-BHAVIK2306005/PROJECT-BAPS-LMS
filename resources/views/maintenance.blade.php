<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Portal — Scheduled Maintenance</title>
    <!-- Modern Premium Typography -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <style>
        *, *::before, *::after { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #040408;
            color: #f3f4f6;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow-x: hidden;
            position: relative;
            padding: 40px 20px;
        }

        /* Animated Grid Overlay */
        .bg-grid {
            position: fixed; 
            inset: 0; 
            z-index: 0;
            background-image:
                linear-gradient(rgba(99, 102, 241, 0.02) 1px, transparent 1px),
                linear-gradient(90deg, rgba(99, 102, 241, 0.02) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridMove 25s linear infinite;
            pointer-events: none;
        }
        @keyframes gridMove {
            0% { background-position: 0 0; }
            100% { background-position: 50px 50px; }
        }

        /* Glowing ambient orbs */
        .orb {
            position: fixed; 
            border-radius: 50%; 
            filter: blur(120px);
            animation: float 12s ease-in-out infinite; 
            z-index: 0;
            opacity: 0.45;
            pointer-events: none;
        }
        .orb-1 { 
            width: 600px; 
            height: 600px; 
            background: radial-gradient(circle, rgba(99, 102, 241, 0.15) 0%, transparent 80%); 
            top: -250px; 
            left: -150px; 
        }
        .orb-2 { 
            width: 500px; 
            height: 500px; 
            background: radial-gradient(circle, rgba(245, 158, 11, 0.08) 0%, transparent 80%); 
            bottom: -200px; 
            right: -100px; 
            animation-delay: -6s; 
        }
        .orb-3 { 
            width: 400px; 
            height: 400px; 
            background: radial-gradient(circle, rgba(16, 185, 129, 0.06) 0%, transparent 80%); 
            top: 50%; 
            left: 50%; 
            transform: translate(-50%, -50%); 
            animation-delay: -3s; 
        }

        @keyframes float {
            0%, 100% { transform: translateY(0) scale(1) rotate(0deg); }
            50% { transform: translateY(-25px) scale(1.05) rotate(10deg); }
        }

        /* Floating tiny particles */
        .particles { 
            position: fixed; 
            inset: 0; 
            z-index: 1; 
            pointer-events: none; 
        }
        .particle {
            position: absolute; 
            width: 2.5px; 
            height: 2.5px;
            background: rgba(245, 158, 11, 0.25); 
            border-radius: 50%;
            animation: rise linear infinite;
        }
        @keyframes rise {
            0%   { transform: translateY(105vh) scale(0); opacity: 0; }
            10%  { opacity: 0.8; }
            90%  { opacity: 0.8; }
            100% { transform: translateY(-5vh) scale(1.2); opacity: 0; }
        }

        /* Glassmorphism Card Container */
        .card {
            position: relative; 
            z-index: 10;
            background: rgba(13, 14, 24, 0.65);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 24px;
            padding: 45px 50px;
            max-width: 680px;
            width: 100%;
            text-align: center;
            box-shadow: 
                0 30px 70px rgba(0, 0, 0, 0.5), 
                inset 0 1px 1px rgba(255, 255, 255, 0.1),
                0 0 40px rgba(99, 102, 241, 0.03);
            animation: cardIn 1s cubic-bezier(0.16, 1, 0.3, 1) both;
        }
        @keyframes cardIn {
            from { opacity: 0; transform: translateY(40px) scale(0.96); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        /* Header Logo & Style */
        .logo-wrap {
            display: inline-flex; 
            align-items: center; 
            gap: 10px;
            margin-bottom: 25px;
        }
        .logo-icon {
            font-size: 22px; 
            color: #fbbf24;
            filter: drop-shadow(0 0 10px rgba(251, 191, 36, 0.4));
        }
        .logo-text {
            font-family: 'Outfit', sans-serif;
            font-size: 15px; 
            font-weight: 800; 
            letter-spacing: 3px;
            color: rgba(255, 255, 255, 0.85); 
            text-transform: uppercase;
        }

        /* Top Pulse Badge */
        .badge {
            display: inline-flex; 
            align-items: center; 
            gap: 8px;
            background: rgba(245, 158, 11, 0.08);
            border: 1px solid rgba(245, 158, 11, 0.2);
            color: #f59e0b;
            font-size: 11px; 
            font-weight: 700;
            letter-spacing: 1.5px; 
            text-transform: uppercase;
            padding: 7px 16px; 
            border-radius: 100px;
            margin-bottom: 22px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }
        .badge .dot {
            width: 6.5px; 
            height: 6.5px; 
            border-radius: 50%;
            background: #f59e0b;
            animation: blink 1.5s ease-in-out infinite;
        }
        @keyframes blink { 
            0%, 100% { opacity: 1; filter: drop-shadow(0 0 4px #f59e0b); } 
            50% { opacity: 0.35; filter: drop-shadow(0 0 0px #f59e0b); } 
        }

        h1 {
            font-family: 'Outfit', sans-serif;
            font-size: 34px; 
            font-weight: 800;
            background: linear-gradient(135deg, #ffffff 40%, #fbbf24 100%);
            -webkit-background-clip: text; 
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px; 
            line-height: 1.2;
            letter-spacing: -0.5px;
        }

        .subtitle {
            font-size: 14.5px; 
            color: rgba(243, 244, 246, 0.7);
            line-height: 1.6; 
            margin-bottom: 28px;
            max-width: 540px; 
            margin-left: auto; 
            margin-right: auto;
        }

        /* SVG Graphic styling */
        .graphic-container {
            width: 100%;
            max-width: 440px;
            margin: 0 auto 28px;
            position: relative;
        }
        .plug-svg {
            width: 100%;
            height: auto;
            display: block;
        }
        
        @keyframes leftPlugMove {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(16px); }
        }
        @keyframes rightPlugMove {
            0%, 100% { transform: translateX(0); }
            50% { transform: translateX(-16px); }
        }
        @keyframes sparkCrackle {
            0%, 100% { opacity: 0.15; transform: scaleY(1); }
            45% { opacity: 0.4; }
            50% { opacity: 1; transform: scaleY(1.15); }
            55% { opacity: 0.4; }
        }
        @keyframes prongGlowPulse {
            0%, 100% { filter: drop-shadow(0 0 1px rgba(96, 165, 250, 0.4)); }
            50% { filter: drop-shadow(0 0 8px rgba(96, 165, 250, 0.9)); }
        }
        @keyframes socketGlowPulse {
            0%, 100% { filter: drop-shadow(0 0 1px rgba(52, 211, 153, 0.4)); }
            50% { filter: drop-shadow(0 0 8px rgba(52, 211, 153, 0.9)); }
        }

        .left-group { animation: leftPlugMove 4s ease-in-out infinite; }
        .right-group { animation: rightPlugMove 4s ease-in-out infinite; }
        .prong { animation: prongGlowPulse 4s ease-in-out infinite; }
        .right-group rect[fill="#34d399"] { animation: socketGlowPulse 4s ease-in-out infinite; }
        .sparks { animation: sparkCrackle 4s ease-in-out infinite; transform-origin: 400px 160px; }

        .spark { animation: sparkJitter 0.5s linear infinite; transform-origin: center; }
        .spark-2 { animation-delay: 0.15s; }
        .spark-3 { animation-delay: 0.3s; }
        @keyframes sparkJitter {
            0%, 100% { transform: skewX(0deg) scale(1); opacity: 0.8; }
            50% { transform: skewX(6deg) scale(0.9); opacity: 1; }
        }

        /* Access Bypass Portals Container */
        .portal-section {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 18px;
            padding: 24px;
            margin-bottom: 28px;
            text-align: left;
        }
        .portal-title {
            font-family: 'Outfit', sans-serif;
            font-size: 13.5px;
            font-weight: 700;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 15px;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .portal-title i {
            color: #fbbf24;
        }
        .portal-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
        }
        @media (max-width: 580px) {
            .portal-grid {
                grid-template-columns: 1fr;
            }
        }
        
        /* Interactive portal button */
        .portal-btn {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
            border-radius: 12px;
            padding: 16px 20px;
            color: #ffffff;
            text-decoration: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        .portal-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(99, 102, 241, 0.15) 0%, rgba(245, 158, 11, 0.05) 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
            z-index: 1;
        }
        .portal-btn:hover {
            transform: translateY(-3px);
            border-color: rgba(251, 191, 36, 0.35);
            box-shadow: 
                0 12px 20px rgba(0, 0, 0, 0.25),
                0 0 15px rgba(251, 191, 36, 0.1);
        }
        .portal-btn:hover::before {
            opacity: 1;
        }
        .portal-btn-content {
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 2;
            text-align: left;
        }
        .portal-btn-icon {
            font-size: 20px;
            color: #fbbf24;
            transition: transform 0.3s ease;
        }
        .portal-btn:hover .portal-btn-icon {
            transform: scale(1.15) rotate(-5deg);
        }
        .portal-btn-text .btn-label {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: rgba(255, 255, 255, 0.45);
            margin-bottom: 2px;
        }
        .portal-btn-text .btn-title {
            font-family: 'Outfit', sans-serif;
            font-size: 14px;
            font-weight: 600;
            color: #ffffff;
        }
        .portal-btn-arrow {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.3);
            transition: transform 0.3s ease, color 0.3s ease;
            z-index: 2;
        }
        .portal-btn:hover .portal-btn-arrow {
            transform: translateX(4px);
            color: #fbbf24;
        }

        /* Allowed Roles Checklist Section */
        .roles-allow-wrap {
            margin-top: 15px;
            border-top: 1px dashed rgba(255, 255, 255, 0.08);
            padding-top: 15px;
        }
        .roles-allow-title {
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: rgba(255, 255, 255, 0.4);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 5px;
        }
        .roles-chips {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }
        .role-chip {
            font-size: 11px;
            font-weight: 600;
            background: rgba(59, 130, 246, 0.05);
            border: 1px solid rgba(59, 130, 246, 0.15);
            color: #60a5fa;
            padding: 4px 10px;
            border-radius: 6px;
            display: inline-flex;
            align-items: center;
            gap: 5px;
            transition: all 0.2s ease;
        }
        .role-chip:hover {
            background: rgba(59, 130, 246, 0.1);
            border-color: rgba(59, 130, 246, 0.3);
            transform: scale(1.03);
        }
        .role-chip.staff-type {
            background: rgba(16, 185, 129, 0.05);
            border-color: rgba(16, 185, 129, 0.15);
            color: #34d399;
        }
        .role-chip.staff-type:hover {
            background: rgba(16, 185, 129, 0.1);
            border-color: rgba(16, 185, 129, 0.3);
        }

        /* Info pills Grid */
        .info-grid {
            display: grid; 
            grid-template-columns: 1fr 1fr;
            gap: 12px; 
            margin-bottom: 28px;
        }
        .info-pill {
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.04);
            border-radius: 12px; 
            padding: 12px 16px;
            text-align: left;
            transition: all 0.3s ease;
        }
        .info-pill:hover {
            border-color: rgba(99, 102, 241, 0.12);
            background: rgba(255, 255, 255, 0.03);
            transform: translateY(-1px);
        }
        .info-pill .label { 
            font-size: 10px; 
            color: rgba(255, 255, 255, 0.4); 
            text-transform: uppercase; 
            letter-spacing: 0.8px; 
            margin-bottom: 4px; 
        }
        .info-pill .value { 
            font-size: 13px; 
            font-weight: 600; 
            color: rgba(255, 255, 255, 0.85); 
        }

        /* Sleek progress indicator */
        .progress-container {
            background: rgba(255, 255, 255, 0.04);
            border-radius: 100px; 
            height: 4px;
            overflow: hidden; 
            margin-bottom: 28px;
            position: relative;
        }
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #fbbf24, #10b981, #fbbf24);
            background-size: 200% auto;
            border-radius: 100px;
            animation: progressShift 2.5s linear infinite;
            width: 100%;
        }
        @keyframes progressShift {
            0% { background-position: 0% 50%; }
            100% { background-position: 200% 50%; }
        }

        /* Footer */
        .footer {
            font-size: 12.5px; 
            color: rgba(255, 255, 255, 0.35);
            border-top: 1px solid rgba(255, 255, 255, 0.06);
            padding-top: 22px;
            line-height: 1.5;
        }
        .footer a { 
            color: #fbbf24; 
            text-decoration: none; 
            font-weight: 500; 
            transition: all 0.2s; 
        }
        .footer a:hover { 
            color: #f59e0b; 
            text-decoration: underline; 
        }

        #clock {
            font-size: 13.5px; 
            font-weight: 700; 
            color: #fbbf24;
            font-variant-numeric: tabular-nums;
            letter-spacing: 0.5px;
        }
    </style>
</head>
<body>

    <div class="bg-grid"></div>
    <div class="orb orb-1"></div>
    <div class="orb orb-2"></div>
    <div class="orb orb-3"></div>
    <div class="particles" id="particles"></div>

    <div class="card">
        <!-- Logo Header -->
        <div class="logo-wrap">
            <i class="fas fa-graduation-cap logo-icon"></i>
            <span class="logo-text">BAPS SVM LMS</span>
        </div>

        <!-- Pulse Badge -->
        <div class="badge">
            <span class="dot"></span> Maintenance Mode Active
        </div>

        <!-- Title & Subtitle -->
        <h1>Undergoing Upgrades</h1>
        <p class="subtitle">
            {{ $message ?? 'The system is undergoing scheduled maintenance to add exciting new content and features. We apologize for any inconvenience.' }}
        </p>

        <!-- Plug Connecting SVG Graphic -->
        <div class="graphic-container">
            <svg class="plug-svg" viewBox="0 0 800 260" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <!-- Gold plug gradient -->
                    <linearGradient id="goldPlugGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#b45309" />
                        <stop offset="100%" stop-color="#fbbf24" />
                    </linearGradient>
                    <!-- Green plug gradient -->
                    <linearGradient id="greenPlugGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                        <stop offset="0%" stop-color="#10b981" />
                        <stop offset="100%" stop-color="#059669" />
                    </linearGradient>
                    <!-- Glow filters -->
                    <filter id="glow" x="-20%" y="-20%" width="140%" height="140%">
                        <feGaussianBlur stdDeviation="8" result="blur" />
                        <feComposite in="SourceGraphic" in2="blur" operator="over" />
                    </filter>
                    <filter id="glow-light" x="-10%" y="-10%" width="120%" height="120%">
                        <feGaussianBlur stdDeviation="3" result="blur" />
                        <feComposite in="SourceGraphic" in2="blur" operator="over" />
                    </filter>
                </defs>

                <!-- LEFT GROUP -->
                <g class="left-group">
                    <!-- Left Wire -->
                    <path class="cable left-cable" d="M 0 100 L 220 100 A 15 15 0 0 1 235 115 L 235 145 A 15 15 0 0 0 250 160 L 295 160" fill="none" stroke="#fbbf24" stroke-width="8" stroke-linecap="round" filter="url(#glow-light)" />
                    <!-- Left Plug Body -->
                    <rect x="290" y="130" width="60" height="60" rx="10" fill="url(#goldPlugGrad)" stroke="#f59e0b" stroke-width="2" />
                    <!-- Ridge lines -->
                    <line x1="305" y1="138" x2="305" y2="182" stroke="#78350f" stroke-width="4" stroke-linecap="round" />
                    <line x1="318" y1="138" x2="318" y2="182" stroke="#78350f" stroke-width="4" stroke-linecap="round" />
                    <line x1="331" y1="138" x2="331" y2="182" stroke="#78350f" stroke-width="4" stroke-linecap="round" />
                    <!-- Front Cover -->
                    <rect x="348" y="138" width="10" height="44" rx="4" fill="#fef08a" />
                    <!-- Prongs -->
                    <rect class="prong prong-top" x="358" y="144" width="28" height="10" rx="3" fill="#fef08a" />
                    <rect class="prong prong-bottom" x="358" y="166" width="28" height="10" rx="3" fill="#fef08a" />
                </g>

                <!-- RIGHT GROUP -->
                <g class="right-group">
                    <!-- Right Wire -->
                    <path class="cable right-cable" d="M 800 100 L 580 100 A 15 15 0 0 0 565 115 L 565 145 A 15 15 0 0 1 550 160 L 505 160" fill="none" stroke="#34d399" stroke-width="8" stroke-linecap="round" filter="url(#glow-light)" />
                    <!-- Right Plug Body -->
                    <rect x="450" y="130" width="60" height="60" rx="10" fill="url(#greenPlugGrad)" stroke="#10b981" stroke-width="2" />
                    <!-- Ridge lines -->
                    <line x1="495" y1="138" x2="495" y2="182" stroke="#065f46" stroke-width="4" stroke-linecap="round" />
                    <line x1="482" y1="138" x2="482" y2="182" stroke="#065f46" stroke-width="4" stroke-linecap="round" />
                    <line x1="469" y1="138" x2="469" y2="182" stroke="#065f46" stroke-width="4" stroke-linecap="round" />
                    <!-- Sleeve Front -->
                    <rect x="440" y="135" width="12" height="50" rx="5" fill="#34d399" />
                    <!-- Receptacle Slots -->
                    <rect x="438" y="144" width="4" height="10" rx="1" fill="#064e3b" />
                    <rect x="438" y="166" width="4" height="10" rx="1" fill="#064e3b" />
                </g>

                <!-- SPARKS AND GLOW -->
                <circle cx="400" cy="160" r="30" fill="#fef08a" opacity="0.1" filter="url(#glow)" />
                <g class="sparks">
                    <path class="spark spark-1" d="M 384 149 Q 400 135 440 149" fill="none" stroke="#fbbf24" stroke-width="2.5" stroke-linecap="round" filter="url(#glow-light)" />
                    <path class="spark spark-2" d="M 384 171 Q 400 185 440 171" fill="none" stroke="#34d399" stroke-width="2.5" stroke-linecap="round" filter="url(#glow-light)" />
                    <path class="spark spark-3" d="M 384 160 L 438 160" fill="none" stroke="#fef08a" stroke-width="2" stroke-dasharray="3,3" stroke-linecap="round" />
                </g>
            </svg>
        </div>

        <!-- Authorized Access Portals (Bypass Entrance) -->
        <div class="portal-section">
            <div class="portal-title">
                <i class="fas fa-key"></i> Authorized Academic Access Portals
            </div>
            <div class="portal-grid">
                <a href="{{ url('/admin/login') }}" class="portal-btn">
                    <div class="portal-btn-content">
                        <i class="fas fa-user-shield portal-btn-icon"></i>
                        <div class="portal-btn-text">
                            <div class="btn-label">Institutional</div>
                            <div class="btn-title">Staff & Admin Login</div>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right portal-btn-arrow"></i>
                </a>
                <a href="{{ url('/login') }}" class="portal-btn">
                    <div class="portal-btn-content">
                        <i class="fas fa-user-graduate portal-btn-icon"></i>
                        <div class="portal-btn-text">
                            <div class="btn-label">Student Portal</div>
                            <div class="btn-title">Student & CR Login</div>
                        </div>
                    </div>
                    <i class="fas fa-arrow-right portal-btn-arrow"></i>
                </a>
            </div>

            <!-- Allowed Roles Chip Bar -->
            <div class="roles-allow-wrap">
                <div class="roles-allow-title">
                    <i class="fas fa-info-circle" style="color: rgba(255,255,255,0.45); font-size:11px;"></i> Roles allowed to bypass maintenance:
                </div>
                <div class="roles-chips">
                    <span class="role-chip"><i class="fas fa-user-lock"></i> Admin</span>
                    <span class="role-chip"><i class="fas fa-user-tie"></i> Dean</span>
                    <span class="role-chip"><i class="fas fa-user-circle"></i> HOD</span>
                    <span class="role-chip staff-type"><i class="fas fa-user-edit"></i> Office Asst</span>
                    <span class="role-chip staff-type"><i class="fas fa-star"></i> CR</span>
                </div>
            </div>
        </div>

        <!-- Info Pills Grid -->
        <div class="info-grid">
            <div class="info-pill">
                <div class="label">Initiated By</div>
                <div class="value">
                    <i class="fas fa-shield-alt" style="color:#fbbf24; margin-right:6px;"></i>{{ ucfirst($enabled_by ?? 'Admin') }}
                </div>
            </div>
            <div class="info-pill">
                <div class="label">Started At</div>
                <div class="value">
                    <i class="fas fa-calendar-alt" style="color:#fbbf24; margin-right:6px;"></i>{{ \Carbon\Carbon::parse($enabled_at ?? now())->format('h:i A') }}
                </div>
            </div>
            <div class="info-pill">
                <div class="label">Academic Access Gate</div>
                <div class="value" style="color:#34d399;">
                    <i class="fas fa-lock-open" style="margin-right:6px;"></i>Bypass Available
                </div>
            </div>
            <div class="info-pill">
                <div class="label">Current Time</div>
                <div class="value" id="clock">--:--:-- --</div>
            </div>
        </div>

        <!-- Sleek linear Progress bar -->
        <div class="progress-container">
            <div class="progress-bar"></div>
        </div>

        <!-- Footer -->
        <p class="footer">
            Bypass maintenance mode by logging in with authorized credentials above. Need help?<br>
            Please email us at <a href="mailto:admin.bhavik@baps.ac.in">admin.bhavik@baps.ac.in</a>
            <span style="color:rgba(255,255,255,0.18); margin-top:8px; display:block;">
                &copy; {{ date('Y') }} BAPS SVM Institutional LMS &mdash; Academic Portal
            </span>
        </p>
    </div>

    <script>
        // Live clock implementation
        function updateClock() {
            const now = new Date();
            const h = now.getHours(), m = now.getMinutes(), s = now.getSeconds();
            const ampm = h >= 12 ? 'PM' : 'AM';
            const hh = (h % 12 || 12).toString().padStart(2,'0');
            const mm = m.toString().padStart(2,'0');
            const ss = s.toString().padStart(2,'0');
            document.getElementById('clock').textContent = `${hh}:${mm}:${ss} ${ampm}`;
        }
        setInterval(updateClock, 1000);
        updateClock();

        // Ambient floating particles
        const container = document.getElementById('particles');
        const count = 25;
        for (let i = 0; i < count; i++) {
            const p = document.createElement('div');
            p.className = 'particle';
            p.style.left = Math.random() * 100 + 'vw';
            p.style.animationDuration = (7 + Math.random() * 9) + 's';
            p.style.animationDelay = (Math.random() * 7) + 's';
            p.style.width = p.style.height = (Math.random() * 2.5 + 1) + 'px';
            container.appendChild(p);
        }
    </script>
</body>
</html>
