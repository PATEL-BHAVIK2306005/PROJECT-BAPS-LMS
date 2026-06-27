<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exam Admit Card - {{ $user->name }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #0f172a;
            --secondary: #3b82f6;
            --accent: #f59e0b;
            --surface: #ffffff;
            --background: #f1f5f9;
            --text: #1e293b;
            --border: #e2e8f0;
        }

        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--background);
            color: var(--text);
            margin: 0;
            padding: 2rem;
            display: flex;
            justify-content: center;
        }

        .admit-card {
            background-color: var(--surface);
            width: 100%;
            max-width: 900px;
            box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.1);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border);
            position: relative;
        }

        .admit-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 8px;
            background: linear-gradient(90deg, var(--secondary), var(--accent));
        }

        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 2.5rem 3rem;
            border-bottom: 2px solid var(--border);
            background-color: #f8fafc;
        }

        .university-info h1 {
            margin: 0;
            font-size: 1.75rem;
            font-weight: 800;
            color: var(--primary);
            letter-spacing: -0.5px;
        }

        .university-info p {
            margin: 0.25rem 0 0 0;
            color: #64748b;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .header-title {
            text-align: right;
        }

        .header-title h2 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--secondary);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header-title p {
            margin: 0;
            font-size: 0.85rem;
            font-weight: 600;
            background-color: var(--primary);
            color: white;
            padding: 4px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 0.5rem;
        }

        .content {
            padding: 3rem;
            display: flex;
            gap: 3rem;
        }

        .student-details {
            flex: 1;
        }

        .photo-section {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 1.5rem;
            width: 150px;
        }

        .photo-placeholder {
            width: 150px;
            height: 180px;
            border: 2px dashed #cbd5e1;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f8fafc;
            color: #94a3b8;
            font-size: 0.85rem;
            font-weight: 500;
            text-align: center;
            padding: 1rem;
            box-sizing: border-box;
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);
        }

        .detail-row {
            display: flex;
            margin-bottom: 1.25rem;
            align-items: flex-start;
        }

        .detail-label {
            width: 160px;
            font-weight: 600;
            color: #64748b;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .detail-value {
            font-weight: 700;
            color: var(--primary);
            font-size: 1.05rem;
            flex: 1;
            border-bottom: 1px solid var(--border);
            padding-bottom: 4px;
        }

        .barcode {
            font-family: 'Libre Barcode 39', cursive;
            font-size: 4rem;
            line-height: 1;
            color: var(--primary);
            text-align: center;
            margin-top: 1rem;
        }

        .schedule-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-top: 2rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            overflow: hidden;
        }

        .schedule-table th {
            background-color: #f8fafc;
            color: var(--primary);
            font-weight: 700;
            text-transform: uppercase;
            font-size: 0.8rem;
            letter-spacing: 1px;
            padding: 1.25rem 1rem;
            border-bottom: 2px solid var(--border);
        }

        .schedule-table td {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid var(--border);
            color: var(--text);
            font-size: 0.95rem;
            font-weight: 500;
        }

        .schedule-table tr:last-child td {
            border-bottom: none;
        }

        .course-code {
            font-weight: 700;
            color: var(--secondary);
            background-color: #eff6ff;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 0.85rem;
        }

        .footer {
            padding: 2.5rem 3rem;
            background-color: #f8fafc;
            border-top: 2px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-line {
            border-top: 2px solid var(--primary);
            margin-top: 4rem;
            padding-top: 0.75rem;
            font-weight: 600;
            font-size: 0.9rem;
            color: var(--primary);
        }

        .instructions {
            margin-top: 2rem;
            padding: 2rem 3rem;
            background-color: #fffbeb;
            border-top: 1px solid #fde68a;
        }

        .instructions h3 {
            margin-top: 0;
            color: #b45309;
            font-size: 1.1rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .instructions ul {
            margin: 0;
            padding-left: 1.5rem;
            color: #92400e;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .instructions li {
            margin-bottom: 0.5rem;
        }

        .print-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background-color: var(--secondary);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .print-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(59, 130, 246, 0.5);
            background-color: #2563eb;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
            }
            .admit-card {
                box-shadow: none;
                border: none;
            }
            .print-btn {
                display: none;
            }
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39&display=swap" rel="stylesheet">
</head>
<body>

    @if(isset($isAdminView) && $isAdminView)
        {{-- ADMIN/STAFF/CR VIEW: Show Actual Admit Card --}}
        <button class="print-btn" onclick="window.print()">
            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Print Admit Card
        </button>

        <div class="admit-card">
            <div class="header">
                <div class="university-info">
                    <h1>BAPS University</h1>
                    <p>Advanced Institute of Technology & Management</p>
                </div>
                <div class="header-title">
                    <h2>Hall Ticket</h2>
                    <p>Term End Examination 2026</p>
                </div>
            </div>

            <div class="content">
                <div class="student-details">
                    <div class="detail-row">
                        <div class="detail-label">Student Name</div>
                        <div class="detail-value">{{ strtoupper($user->name) }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Enrollment No.</div>
                        <div class="detail-value">{{ $user->enrollment_no }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Program</div>
                        <div class="detail-value">Bachelor of Technology</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Department</div>
                        <div class="detail-value">{{ $user->department->name ?? 'Computer Science' }}</div>
                    </div>
                    <div class="detail-row">
                        <div class="detail-label">Gender / DOB</div>
                        <div class="detail-value">{{ ucfirst($user->gender ?? 'N/A') }} / {{ $user->dob ? \Carbon\Carbon::parse($user->dob)->format('d M Y') : 'N/A' }}</div>
                    </div>
                </div>

                <div class="photo-section">
                    <div class="photo-placeholder">
                        Affix Recent Passport Size Photograph Here
                    </div>
                    <div class="barcode">
                        *{{ $barcodeString ?? '12345678' }}*
                    </div>
                </div>
            </div>

            <div style="padding: 0 3rem;">
                <h3 style="margin:0; color:var(--primary); font-size:1.1rem;">Exam Timetable</h3>
                <table class="schedule-table">
                    <thead>
                        <tr>
                            <th style="text-align: left;">Course Code</th>
                            <th style="text-align: left;">Subject Title</th>
                            <th style="text-align: left;">Date</th>
                            <th style="text-align: left;">Time</th>
                            <th style="text-align: left;">Room</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($examSchedule ?? [] as $exam)
                            <tr>
                                <td><span class="course-code">{{ $exam['course_code'] }}</span></td>
                                <td style="font-weight: 600;">{{ $exam['course_name'] }}</td>
                                <td>{{ $exam['date'] }}</td>
                                <td>{{ $exam['time'] }}</td>
                                <td>{{ $exam['room'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align: center; color: #64748b;">No scheduled examinations found for your active enrollments.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="instructions">
                <h3>
                    <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    Important Instructions
                </h3>
                <ul>
                    <li>Students must carry this Admit Card along with valid Institutional ID.</li>
                    <li>Report to the examination hall 30 minutes before commencement.</li>
                    <li>Electronic devices, smart watches, and unauthorized materials are strictly prohibited.</li>
                </ul>
            </div>

            <div class="footer">
                <div class="signature-box">
                    <div class="signature-line">Student Signature</div>
                </div>
                <div class="signature-box">
                    <div class="signature-line">Controller of Examinations</div>
                </div>
            </div>
        </div>
    @else
        {{-- STUDENT VIEW --}}
        <div class="admit-card" style="max-width: 600px; text-align: center; padding: 4rem 3rem;">
            @if(session('success'))
                <div style="background: #f0fdf4; color: #16a34a; padding: 1rem; border-radius: 8px; margin-bottom: 2rem; border: 1px solid #bbf7d0;">
                    {{ session('success') }}
                </div>
            @endif

            @if(!$examForm)
                <div style="font-size: 3rem; color: var(--secondary); margin-bottom: 1rem;"><i class="fas fa-file-alt"></i></div>
                <h2 style="color: var(--primary); font-weight: 800; margin-bottom: 1rem;">Exam Registration Pending</h2>
                <p style="color: #64748b; margin-bottom: 2rem;">You have not yet submitted your examination form for the current semester. Please select your subjects and apply.</p>
                
                <form method="POST" action="/exam/form/submit" style="text-align: left; background: white; padding: 2rem; border-radius: 12px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border: 1px solid var(--border);">
                    @csrf
                    <h4 style="margin-top: 0; color: var(--primary); font-weight: 700; border-bottom: 1px solid var(--border); padding-bottom: 10px; margin-bottom: 15px;">Select Subjects for Examination</h4>
                    
                    @if(isset($enrollments) && $enrollments->count() > 0)
                        <div style="max-height: 250px; overflow-y: auto; margin-bottom: 20px;">
                            @foreach($enrollments as $enrollment)
                                @if($enrollment->course)
                                    <div style="padding: 10px; border: 1px solid var(--border); border-radius: 6px; margin-bottom: 8px; display: flex; align-items: center; gap: 10px;">
                                        <input type="checkbox" name="course_ids[]" value="{{ $enrollment->course_id }}" id="course_{{ $enrollment->course_id }}" style="width: 18px; height: 18px;" checked>
                                        <label for="course_{{ $enrollment->course_id }}" style="font-weight: 600; color: var(--text); cursor: pointer; flex: 1;">
                                            {{ $enrollment->course->title }} 
                                            <span style="color: #64748b; font-size: 0.85rem; font-weight: 400;">(CS{{ str_pad($enrollment->course_id, 3, '0', STR_PAD_LEFT) }})</span>
                                        </label>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                        <div style="text-align: center;">
                            <button type="submit" style="background: var(--secondary); color: white; border: none; padding: 1rem 2.5rem; border-radius: 50px; font-weight: 700; font-size: 1rem; cursor: pointer; box-shadow: 0 10px 15px -3px rgba(59, 130, 246, 0.4);">Submit Exam Form Now</button>
                        </div>
                    @else
                        <div style="color: #ef4444; padding: 1rem; background: #fef2f2; border-radius: 6px;">You have no approved course enrollments to apply for exams.</div>
                    @endif
                </form>
            @elseif($examForm->status == 'pending')
                <div style="font-size: 3rem; color: #f59e0b; margin-bottom: 1rem;"><i class="fas fa-clock"></i></div>
                <h2 style="color: var(--primary); font-weight: 800; margin-bottom: 1rem;">Form Submitted</h2>
                <p style="color: #64748b; margin-bottom: 1rem;">Your exam registration form is currently under review by the Controller of Examinations.</p>
                <div style="background: #fffbeb; padding: 1rem; border-radius: 8px; color: #b45309; font-weight: 600; border: 1px solid #fde68a;">
                    Waiting for Admit Card to be published.
                </div>
            @elseif($examForm->status == 'published')
                <div style="font-size: 3rem; color: #10b981; margin-bottom: 1rem;"><i class="fas fa-check-circle"></i></div>
                <h2 style="color: var(--primary); font-weight: 800; margin-bottom: 1rem;">Hall Ticket Generated!</h2>
                <div style="background: #f0fdf4; padding: 1.5rem; border-radius: 12px; border: 1px solid #bbf7d0; margin-top: 2rem;">
                    <p style="color: #166534; font-size: 1.1rem; font-weight: 600; margin: 0;">
                        Your official hall ticket is generated.
                    </p>
                    <p style="color: #15803d; margin-top: 0.5rem; font-weight: 500;">
                        Get PDF By CR or collect your Hard Copy via your Class Coordinator or HOD.
                    </p>
                </div>
            @endif
        </div>
    @endif

</body>
</html>
