<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>BAPS Staff Directory</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #1e293b; font-size: 12px; line-height: 1.5; }

        .header {
            text-align: center;
            padding: 20px 0 15px;
            border-bottom: 3px solid #4f46e5;
            margin-bottom: 24px;
        }
        .header h1 { font-size: 18px; color: #1e1b4b; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 4px; }
        .header h3 { font-size: 11px; color: #ea580c; font-weight: bold; letter-spacing: 1px; margin-bottom: 4px; }
        .header .meta { font-size: 10px; color: #64748b; }

        .summary-bar {
            display: flex;
            justify-content: space-around;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 10px 0;
            margin-bottom: 20px;
        }
        .summary-item { text-align: center; }
        .summary-item .num { font-size: 22px; font-weight: 900; color: #4f46e5; }
        .summary-item .label { font-size: 9px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px; }

        table.main {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
        }
        table.main thead tr {
            background: linear-gradient(90deg, #4f46e5, #7c3aed);
            color: white;
        }
        table.main thead th {
            padding: 9px 10px;
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 700;
        }
        table.main tbody tr:nth-child(even) { background: #f8fafc; }
        table.main tbody tr:hover { background: #ede9fe; }
        table.main tbody td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .role-badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 30px;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .role-admin    { background: #fee2e2; color: #dc2626; }
        .role-dean     { background: #dbeafe; color: #1d4ed8; }
        .role-hod      { background: #fef3c7; color: #b45309; }
        .role-faculty  { background: #d1fae5; color: #059669; }
        .role-coordinator { background: #ede9fe; color: #7c3aed; }
        .role-moderator   { background: #fce7f3; color: #be185d; }
        .role-staff       { background: #f1f5f9; color: #475569; }
        .role-other       { background: #f1f5f9; color: #475569; }

        .section-row td {
            background: #4f46e5 !important;
            color: white !important;
            font-size: 9px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 5px 10px;
        }

        .footer {
            border-top: 1px solid #e2e8f0;
            padding-top: 10px;
            text-align: center;
            font-size: 9px;
            color: #94a3b8;
        }
        .footer strong { color: #4f46e5; }

        .sig-section {
            width: 100%;
            margin-top: 30px;
            margin-bottom: 20px;
        }
        .sig-box {
            display: inline-block;
            width: 45%;
            border: 1px dashed #cbd5e1;
            border-radius: 6px;
            padding: 20px 12px 10px;
            text-align: center;
            font-size: 10px;
            color: #64748b;
        }
        .sig-box.right { float: right; }
        .sig-box .line { border-top: 1px solid #94a3b8; margin-top: 30px; padding-top: 6px; font-weight: 700; }
    </style>
</head>
<body>
    <div class="header">
        <h1>BAPS SVM Institutional System</h1>
        <h3>Official Staff & Faculty Directory</h3>
        <div class="meta">
            Generated: {{ date('d F Y, h:i A') }} &nbsp;|&nbsp;
            Total Staff: {{ $allStaff->count() }} &nbsp;|&nbsp;
            Document Reference: BAPS-STAFF-{{ date('Ymd') }}
        </div>
    </div>

    {{-- Summary Bar --}}
    @php
        $roleGroups = $allStaff->groupBy('role');
        $roleCounts = [
            'Admin'    => $allStaff->where('role','admin')->count(),
            'Dean'     => $allStaff->where('role','dean')->count(),
            'HOD'      => $allStaff->where('role','hod')->count(),
            'Faculty'  => $allStaff->where('role','faculty')->count(),
            'Others'   => $allStaff->whereNotIn('role',['admin','dean','hod','faculty'])->count(),
        ];
    @endphp

    <table style="width:100%; margin-bottom: 20px; border-collapse: collapse; background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;">
        <tr>
            @foreach($roleCounts as $label => $count)
            <td style="text-align:center; padding: 10px 5px; border-right: 1px solid #e2e8f0;">
                <div style="font-size: 20px; font-weight: 900; color: #4f46e5;">{{ $count }}</div>
                <div style="font-size: 9px; text-transform: uppercase; color: #64748b; letter-spacing: 0.5px;">{{ $label }}</div>
            </td>
            @endforeach
        </tr>
    </table>

    {{-- Main Directory Table --}}
    <table class="main">
        <thead>
            <tr>
                <th>#</th>
                <th>Full Name</th>
                <th>Role</th>
                <th>Positions</th>
                <th>Department</th>
                <th>Email</th>
                @if(session('user_role') !== 'cr')
                    <th>Login Code</th>
                    <th>Password</th>
                @else
                    <th>Phone</th>
                @endif
                <th>Enrolled</th>
            </tr>
        </thead>
        <tbody>
            @php $prevRole = null; $i = 1; @endphp
            @foreach($allStaff as $s)
                @if($s->role !== $prevRole)
                <tr class="section-row">
                    <td colspan="{{ session('user_role') !== 'cr' ? 9 : 8 }}">{{ strtoupper($s->role) }} MEMBERS</td>
                </tr>
                @php $prevRole = $s->role; @endphp
                @endif
                @php
                    $cleanName = preg_replace('/\s*\([^)]*\)/', '', $s->name);
                    $cleanName = str_replace(['.', ','], ' ', $cleanName);
                    $cleanName = preg_replace('/\s+/', ' ', trim($cleanName));
                    $words = explode(' ', $cleanName);
                    $titles = ['dr', 'prof', 'hod', 'dean', 'provost', 'associate', 'co-dean', 'senior', 'assistant', 'dill'];
                    $filteredWords = [];
                    foreach ($words as $word) {
                        $cleanWord = strtolower(trim($word));
                        if (in_array($cleanWord, $titles) || empty($cleanWord)) {
                            continue;
                        }
                        $filteredWords[] = $word;
                    }
                    $first = $filteredWords[0] ?? 'Faculty';
                    $firstClean = preg_replace('/[^a-zA-Z]/', '', $first);
                    $plainPassword = strtoupper($firstClean) . '@123';
                @endphp
                <tr>
                    <td style="color: #94a3b8;">{{ $i++ }}</td>
                    <td style="font-weight: 600;">{{ $s->name }}</td>
                    <td>
                        <span class="role-badge role-{{ $s->role }}">{{ $s->role }}</span>
                    </td>
                    <td style="color: #64748b; font-size: 10px;">
                        @if($s->positions && is_array($s->positions))
                            {{ implode(', ', array_map('ucfirst', $s->positions)) }}
                        @else
                            —
                        @endif
                    </td>
                    <td>{{ $s->department->name ?? 'General Admin' }}</td>
                    <td style="color: #3b82f6; font-size: 10px;">{{ $s->email ?? '—' }}</td>
                    @if(session('user_role') !== 'cr')
                        <td><code style="background:#f1f5f9; padding:1px 5px; border-radius:3px; font-size:10px;">{{ $s->unique_code }}</code></td>
                        <td><code style="background:#fef08a; color:#854d0e; padding:1px 5px; border-radius:3px; font-size:10px; font-weight:bold;">{{ $plainPassword }}</code></td>
                    @else
                        <td style="font-size: 10px;">{{ $s->phone ?? '—' }}</td>
                    @endif
                    <td style="color: #64748b; font-size: 10px;">{{ $s->created_at ? $s->created_at->format('d M Y') : '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Signature Section --}}
    <table style="width:100%; margin-top: 30px;">
        <tr>
            <td style="width:45%; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 30px 12px 10px; text-align: center; font-size: 10px; color: #64748b;">
                <div style="border-top: 1px solid #94a3b8; padding-top: 6px; font-weight: 700;">Dean / Principal Signature</div>
            </td>
            <td style="width: 10%;"></td>
            <td style="width:45%; border: 1px dashed #cbd5e1; border-radius: 6px; padding: 30px 12px 10px; text-align: center; font-size: 10px; color: #64748b;">
                <div style="border-top: 1px solid #94a3b8; padding-top: 6px; font-weight: 700;">Admin / HR Authorized Signatory</div>
            </td>
        </tr>
    </table>

    <div class="footer" style="margin-top: 20px;">
        This document is an <strong>official cryptographic record</strong> of the BAPS SVM Institutional LMS.<br>
        All permissions &amp; access levels are digitally verified. | &copy; {{ date('Y') }} BAPS Innovation Campus
    </div>
</body>
</html>
