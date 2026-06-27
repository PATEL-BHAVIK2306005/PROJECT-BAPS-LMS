<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excellence Certificate - {{ $user->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body { margin: 0; padding: 20px; background: #e0e0e0; display: flex; justify-content: center; font-family: 'Roboto', sans-serif; }
        .cert-container {
            width: 1100px;
            height: 750px;
            background: #fff;
            padding: 40px;
            position: relative;
            box-shadow: 0 0 20px rgba(0,0,0,0.2);
            text-align: center;
            border: 20px solid #2c3e50;
            box-sizing: border-box;
        }
        .inner-border {
            border: 4px solid #d4af37;
            height: 100%;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
        }
        .title {
            font-family: 'Playfair Display', serif;
            font-size: 55px;
            color: #2c3e50;
            margin-bottom: 10px;
            text-transform: uppercase;
        }
        .subtitle {
            font-size: 22px;
            color: #d4af37;
            text-transform: uppercase;
            letter-spacing: 5px;
            margin-bottom: 60px;
            font-weight: 700;
        }
        .presented-to {
            font-size: 18px;
            color: #7f8c8d;
            margin-bottom: 20px;
        }
        .name {
            font-family: 'Playfair Display', serif;
            font-size: 60px;
            color: #2c3e50;
            margin-bottom: 30px;
            border-bottom: 2px solid #d4af37;
            display: inline-block;
            padding: 0 40px;
        }
        .reason {
            font-size: 20px;
            color: #34495e;
            line-height: 1.6;
            max-width: 800px;
            margin: 0 auto;
        }
        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 100px;
            padding: 0 50px;
        }
        .sig-block {
            width: 250px;
            text-align: center;
        }
        .sig-line {
            border-bottom: 2px solid #2c3e50;
            margin-bottom: 10px;
        }
        .sig-title {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        .seal {
            position: absolute;
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
            width: 120px;
            height: 120px;
            background: #d4af37;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
            border: 5px dashed #fff;
            box-shadow: 0 0 10px rgba(0,0,0,0.3);
        }

        .btn-print { margin-bottom: 20px; text-align: center; width: 100%; display: flex; justify-content: center; }
        .btn-print button { padding: 10px 20px; font-size: 16px; background: #2c3e50; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; }
        
        @media print {
            body { background: none; padding: 0; }
            .btn-print { display: none; }
            .cert-container { border: none; box-shadow: none; width: 100%; height: 100%; }
        }
    </style>
</head>
<body>

    <div class="btn-print" style="display: flex; gap: 10px; justify-content: center; margin-bottom: 20px;">
        @php
            $isStudent = (auth()->check() && auth()->user()->role === 'student') || session('demo_user_id') || session('student_id');
            $backUrl = $isStudent ? '/profile' : '/admin/students';
        @endphp
        <a href="{{ $backUrl }}" style="padding: 10px 20px; font-size: 16px; background: #7f8c8d; color: #fff; border: none; border-radius: 5px; cursor: pointer; font-weight: bold; text-decoration: none; display: inline-flex; align-items: center;">
            ← Back to Dashboard
        </a>
        <button onclick="window.print()">Print Certificate</button>
    </div>

    <div class="cert-container">
        <div class="inner-border">
            <div class="title">Certificate of Excellence</div>
            <div class="subtitle">Academic Brilliance Award</div>

            <div class="presented-to">This is proudly presented to</div>
            
            <div class="name">{{ strtoupper($user->name) }}</div>
            
            <div class="reason">
                In profound recognition of your outstanding academic performance and securing the <strong>Badge of Excellence (Grade 'O' / 'O+')</strong> in the University Examinations. Your relentless dedication to your studies and pursuit of knowledge is highly commendable.
            </div>

            <div class="seal">
                OFFICIAL<br>SEAL
            </div>

            <div class="signatures">
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-title">Dean / Principal</div>
                </div>
                <div class="sig-block">
                    <div class="sig-line"></div>
                    <div class="sig-title">Controller of Examinations</div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
