<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>
        @page { size: A4 landscape; margin: 0; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            margin: 0; padding: 0; background: #fff; color: #333; 
        }
        .outer-border {
            width: 100%; height: 100%; 
            border: 20px solid #1e293b; 
            box-sizing: border-box;
            background: #ffffff;
            padding: 30px;
            page-break-after: always;
        }
        .inner-border {
            width: 100%; height: 100%;
            border: 2px solid #94a3b8;
            box-sizing: border-box;
            padding: 40px;
            text-align: center;
            position: relative;
        }
        .icon-box {
            width: 70px; height: 70px;
            background: #4f46e5;
            margin: 0 auto;
            border-radius: 15px;
            color: white;
            line-height: 70px;
            font-size: 35px;
            font-weight: bold;
        }
        .cert-title {
            font-family: 'Times New Roman', serif;
            font-size: 38px;
            font-weight: bold;
            margin-top: 20px;
            margin-bottom: 5px;
            color: #1e293b;
        }
        .subtitle {
            text-transform: uppercase;
            letter-spacing: 4px;
            font-size: 10px;
            color: #64748b;
            font-weight: bold;
            margin-bottom: 30px;
        }
        .certify-text {
            font-style: italic;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .student-name {
            font-family: 'Times New Roman', serif;
            font-size: 45px;
            font-weight: bold;
            color: #1e293b;
            border-bottom: 1.5px solid #e2e8f0;
            display: inline-block;
            margin-bottom: 25px;
            padding-bottom: 5px;
            width: 70%;
        }
        .achievement-text {
            font-style: italic;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .course-title {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 40px;
        }
        .footer-table {
            width: 100%;
            position: absolute;
            bottom: 40px;
            left: 0;
            padding: 0 40px;
        }
        .sig-line {
            border-bottom: 1.5px solid #1e293b;
            width: 180px;
            margin: 0 auto 5px auto;
        }
        .sig-name {
            font-weight: bold;
            font-size: 14px;
            color: #1e293b;
        }
        .sig-label {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: #64748b;
        }
        .meta-info {
            position: absolute;
            top: 20px;
            right: 20px;
            text-align: right;
            font-size: 9px;
            color: #64748b;
        }
        .qr-code {
            width: 80px;
            height: 80px;
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    @foreach($certificates as $certificate)
    <div class="outer-border">
        <div class="inner-border">
            <div class="meta-info">
                Date: <b>{{ $certificate->created_at->format('M d, Y') }}</b><br>
                ID: <b>{{ $certificate->unique_code }}</b>
            </div>

            <div class="icon-box">🎓</div>
            
            <div class="cert-title">Certificate of Outstanding Completion</div>
            <div class="subtitle">BAPS-E.LEARN-LMS AUTHENTICATED CREDENTIAL</div>
            
            <div class="certify-text">This is to certify that</div>
            <div class="student-name">{{ $certificate->user->name }}</div>
            
            <div class="achievement-text">has successfully mastered the coursework and achieved excellence in</div>
            <div class="course-title">{{ $certificate->course->title }}</div>

            <table class="footer-table">
                <tr>
                    <td width="33%" style="text-align: center;">
                        <div style="height: 40px;"></div>
                        <div class="sig-line"></div>
                        <div class="sig-name">Hon. Kothari / VC</div>
                        <div class="sig-label">University Head</div>
                    </td>
                    <td width="33%" style="text-align: center;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=80x80&data={{ $certificate->unique_code }}" class="qr-code">
                        <div class="sig-line"></div>
                        <div class="sig-name">Admin / Dean</div>
                        <div class="sig-label">Academic Affairs</div>
                    </td>
                    <td width="33%" style="text-align: center;">
                        <div style="height: 40px;"></div>
                        <div class="sig-line"></div>
                        <div class="sig-name">{{ $certificate->course->instructor ?? 'Lead Instructor' }}</div>
                        <div class="sig-label">Verified Faculty</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
    @endforeach
</body>
</html>
