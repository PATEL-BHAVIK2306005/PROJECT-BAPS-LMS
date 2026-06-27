<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Registered Students Roster</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; font-size: 11px; line-height: 1.4; padding: 30px; }

        .header {
            text-align: center;
            padding: 15px 0 10px;
            border-bottom: 3px solid #ea580c;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 18px; color: #1e1b4b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px; }
        .header h3 { font-size: 11px; color: #ea580c; font-weight: bold; letter-spacing: 0.5px; margin-bottom: 4px; }
        .header .meta { font-size: 9px; color: #64748b; }

        .summary-bar {
            width: 100%;
            margin-bottom: 15px;
            border-collapse: collapse;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .summary-bar td {
            text-align: center;
            padding: 8px 5px;
            border-right: 1px solid #e2e8f0;
        }
        .summary-bar td:last-child {
            border-right: none;
        }
        .summary-num { font-size: 18px; font-weight: 800; color: #ea580c; }
        .summary-label { font-size: 8px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; }

        table.main {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.main thead tr {
            background: linear-gradient(90deg, #ea580c, #f97316);
            background-color: #ea580c;
            color: white;
        }
        table.main thead th {
            padding: 8px 6px;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
            text-align: left;
            border: 1px solid #ea580c;
        }
        table.main tbody tr:nth-child(even) { background: #f8fafc; }
        table.main tbody td {
            padding: 6px;
            border: 1px solid #e2e8f0;
            font-size: 10px;
            vertical-align: middle;
        }
        
        .signature-img {
            max-width: 100px;
            max-height: 32px;
            display: block;
        }

        .sig-section {
            width: 100%;
            margin-top: 30px;
            page-break-inside: avoid;
        }

        .footer {
            margin-top: 25px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            text-align: center;
            font-size: 8px;
            color: #94a3b8;
        }
        .footer strong { color: #ea580c; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BAPS SVM Learning Portal</h1>
        <h3>Official Student Registry & Credentials</h3>
        <div class="meta">
            Generated: {{ date('d F Y, h:i A') }} &nbsp;|&nbsp;
            Total Students: {{ $students->count() }} &nbsp;|&nbsp;
            Document Ref: BAPS-STUDENTS-{{ date('Ymd') }}
        </div>
    </div>

    {{-- Quick Summary Stats --}}
    <table class="summary-bar">
        <tr>
            <td>
                <div class="summary-num">{{ $students->count() }}</div>
                <div class="summary-label">Total Users</div>
            </td>
            <td>
                <div class="summary-num">{{ $students->where('is_verified', true)->count() }}</div>
                <div class="summary-label">Verified Accounts</div>
            </td>
            <td>
                <div class="summary-num">{{ $students->where('level', '>', 1)->count() }}</div>
                <div class="summary-label">Active Earners</div>
            </td>
            <td>
                <div class="summary-num">{{ date('d-M-Y') }}</div>
                <div class="summary-label">Record Date</div>
            </td>
        </tr>
    </table>

    {{-- Main Directory Table --}}
    <table class="main">
        <thead>
            <tr>
                <th style="width: 7%;">ID</th>
                <th style="width: 15%;">Enrollment No</th>
                <th style="width: 20%;">Name</th>
                <th style="width: 20%;">Email ID</th>
                <th style="width: 12%;">Password</th>
                <th style="width: 16%;">Badges</th>
                <th style="width: 10%;">Signature</th>
            </tr>
        </thead>
        <tbody>
            @foreach($students as $user)
                <tr>
                    <td style="font-weight: bold; color: #475569;">#{{ $user->id }}</td>
                    <td style="font-family: monospace; font-size: 10px;">{{ $user->enrollment_no ?? 'N/A' }}</td>
                    <td style="font-weight: 600; color: #1e293b;">{{ $user->name }}</td>
                    <td style="color: #4b5563;">{{ $user->email }}</td>
                    <td style="font-family: monospace; font-size: 9px; color: #64748b;">
                        {{ $user->generated_password ?? '••••••••' }}
                    </td>
                    <td>
                        @if($user->role === 'cr')
                            <span style="background-color: #ede9fe; color: #7c3aed; padding: 1px 4px; border-radius: 3px; font-size: 8px; font-weight: bold; border: 1px solid #ddd; margin-right: 2px;">CR</span>
                        @elseif($user->role === 'deputy-cr')
                            <span style="background-color: #dbeafe; color: #1d4ed8; padding: 1px 4px; border-radius: 3px; font-size: 8px; font-weight: bold; border: 1px solid #ddd; margin-right: 2px;">D.CR</span>
                        @endif
                        @if($user->manual_badge)
                            <span style="background-color: #fef3c7; color: #d97706; padding: 1px 4px; border-radius: 3px; font-size: 8px; font-weight: bold; border: 1px solid #ddd; margin-right: 2px;">{{ $user->manual_badge }}</span>
                        @endif
                        @if($user->is_verified)
                            <span style="background-color: #dcfce7; color: #166534; padding: 1px 4px; border-radius: 3px; font-size: 8px; font-weight: bold; border: 1px solid #ddd;">✓ VERIFIED</span>
                        @endif
                    </td>
                    <td>
                        <!-- Signature field left blank intentionally -->
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Last Admin Dean HOD and CR Signature Section --}}
    <div class="sig-section">
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td style="width: 22%; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 25px 8px 10px; text-align: center; font-size: 9px; color: #64748b;">
                    <div style="height: 35px;"></div>
                    <div style="border-top: 1px solid #94a3b8; padding-top: 5px; font-weight: bold; text-transform: uppercase;">CR Signature</div>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 22%; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 25px 8px 10px; text-align: center; font-size: 9px; color: #64748b;">
                    <div style="height: 35px;"></div>
                    <div style="border-top: 1px solid #94a3b8; padding-top: 5px; font-weight: bold; text-transform: uppercase;">HOD Signature</div>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 22%; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 25px 8px 10px; text-align: center; font-size: 9px; color: #64748b;">
                    <div style="height: 35px;"></div>
                    <div style="border-top: 1px solid #94a3b8; padding-top: 5px; font-weight: bold; text-transform: uppercase;">Dean Signature</div>
                </td>
                <td style="width: 4%;"></td>
                <td style="width: 22%; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 25px 8px 10px; text-align: center; font-size: 9px; color: #64748b;">
                    <div style="height: 35px;"></div>
                    <div style="border-top: 1px solid #94a3b8; padding-top: 5px; font-weight: bold; text-transform: uppercase;">Admin Signature</div>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        This document is an <strong>official registry extract</strong> of the BAPS SVM Institutional Portal.<br>
        All records are system-verified. | &copy; {{ date('Y') }} BAPS SVM Learning Systems
    </div>
</body>
</html>
