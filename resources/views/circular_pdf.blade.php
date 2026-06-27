<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Circular - {{ $circular->title }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 20mm 15mm 20mm 15mm;
        }
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            background: #fff;
            margin: 0;
            padding: 0;
        }
        .letterhead {
            text-align: center;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 3px double #000;
        }
        .institution-logo-placeholder {
            font-size: 26pt;
            font-weight: bold;
            letter-spacing: 2px;
            color: #1e3a8a;
            margin: 0;
            text-transform: uppercase;
        }
        .institution-name {
            font-size: 18pt;
            font-weight: bold;
            margin: 2px 0;
            text-transform: uppercase;
        }
        .institution-subtitle {
            font-size: 11pt;
            font-style: italic;
            margin: 2px 0;
            color: #333;
        }
        .institution-meta {
            font-size: 9pt;
            margin: 2px 0;
            color: #555;
            letter-spacing: 0.5px;
        }
        .doc-title {
            text-align: center;
            font-size: 15pt;
            font-weight: bold;
            text-decoration: underline;
            margin: 20px 0;
            letter-spacing: 1px;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            font-size: 11pt;
        }
        .meta-table td {
            padding: 4px 0;
            vertical-align: top;
        }
        .meta-label {
            font-weight: bold;
            width: 15%;
        }
        .meta-value {
            width: 35%;
        }
        .subject-line {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 20px;
            padding: 6px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            text-transform: uppercase;
        }
        .content-body {
            text-align: justify;
            margin-bottom: 50px;
            min-height: 250px;
        }
        .content-body h1, .content-body h2, .content-body h3 {
            margin-top: 15px;
            margin-bottom: 8px;
            font-weight: bold;
            font-size: 13pt;
        }
        .content-body p {
            margin-top: 0;
            margin-bottom: 12px;
        }
        .content-body ul, .content-body ol {
            margin-top: 5px;
            margin-bottom: 12px;
            padding-left: 20px;
        }
        .content-body li {
            margin-bottom: 4px;
        }
        .footer-section {
            width: 100%;
            margin-top: 40px;
        }
        .signature-container {
            float: right;
            width: 250px;
            text-align: center;
        }
        .signature-image {
            height: 60px;
            margin-bottom: 5px;
        }
        .signature-placeholder {
            height: 60px;
            font-family: 'Georgia', serif;
            font-style: italic;
            font-size: 16pt;
            color: #1e3a8a;
            line-height: 60px;
            border-bottom: 1px dashed #ccc;
            margin-bottom: 5px;
        }
        .signer-name {
            font-weight: bold;
            font-size: 11pt;
            margin: 0;
        }
        .signer-title {
            font-size: 9.5pt;
            color: #444;
            margin: 2px 0 0 0;
            line-height: 1.3;
        }
        .seal-container {
            float: left;
            width: 120px;
            height: 120px;
            border: 2px double #888;
            border-radius: 50%;
            text-align: center;
            color: #888;
            font-size: 8pt;
            margin-top: 20px;
        }
        .seal-text {
            line-height: 1.3;
            margin-top: 38px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .clearfix {
            clear: both;
        }
    </style>
</head>
<body>

    <!-- Letterhead -->
    <div class="letterhead">
        <div class="institution-logo-placeholder">BAPS</div>
        <div class="institution-name">BAPS Innovation Campus</div>
        <div class="institution-subtitle">School of Computer Science Engineering and Technology</div>
        <div class="institution-meta">Post Box No. 12, Gandhinagar-Ahmedabad Highway, Gandhinagar, Gujarat 382007</div>
        <div class="institution-meta">Phone: +91-79-23260000 | Email: info@baps.lms.local | Web: www.baps.ac.in</div>
    </div>

    <!-- Title -->
    <div class="doc-title">Official Circular</div>

    <!-- Metadata Block -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Circular No:</td>
            <td class="meta-value">BAPS/SCSET/2026/{{ str_pad($circular->id, 3, '0', STR_PAD_LEFT) }}</td>
            <td class="meta-label" style="text-align: right; padding-right: 15px;">Date:</td>
            <td class="meta-value">{{ $circular->created_at->format('d-M-Y') }}</td>
        </tr>
        <tr>
            <td class="meta-label">Category:</td>
            <td class="meta-value">
                @if($circular->category === 'academic')
                    Academic Affairs
                @elseif($circular->category === 'exams')
                    Exams & Evaluation
                @elseif($circular->category === 'administrative')
                    Administrative & Office Works
                @elseif($circular->category === 'student_cr')
                    Student & CR Announcements
                @elseif($circular->category === 'urgent')
                    Urgent notices
                @else
                    {{ ucfirst($circular->category) }}
                @endif
            </td>
            <td class="meta-label" style="text-align: right; padding-right: 15px;">Status:</td>
            <td class="meta-value" style="color: #b91c1c; font-weight: bold;">OFFICIAL / APPROVED</td>
        </tr>
    </table>

    <!-- Subject Line -->
    <div class="subject-line">
        SUBJECT: {{ $circular->title }}
    </div>

    <!-- Content Body -->
    <div class="content-body">{!! Illuminate\Support\Str::markdown($circular->content) !!}</div>

    <!-- Footer Seals & Signatures -->
    <div class="footer-section">
        <!-- Stamp Seal Box -->
        <div class="seal-container">
            <div class="seal-text">
                BAPS SCSET<br>
                OFFICIAL SEAL<br>
                ESTD. 2026
            </div>
        </div>

        <!-- Signature Block -->
        <div class="signature-container">
            @if($sigBase64)
                <img src="{{ $sigBase64 }}" class="signature-image" alt="Digital Signature">
            @else
                <div class="signature-placeholder">{{ $signerName }}</div>
            @endif
            <div style="border-top: 1.5px solid #000; margin-top: 5px; padding-top: 5px;">
                <p class="signer-name">{{ $signerName }}</p>
                <p class="signer-title">{{ $signerDesignation }}</p>
            </div>
        </div>
        
        <div class="clearfix"></div>
    </div>

</body>
</html>
