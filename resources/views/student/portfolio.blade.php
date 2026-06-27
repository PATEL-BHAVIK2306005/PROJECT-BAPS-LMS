<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $student->name }} | Digital Portfolio</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; }
        .portfolio-header { background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); color: white; padding: 80px 0 60px; position: relative; overflow: hidden; }
        .badge-icon { width: 100px; height: 100px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 40px; margin-bottom: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); }
        .badge-platinum { background: linear-gradient(135deg, #e2e8f0 0%, #94a3b8 100%); color: #0f172a; }
        .badge-gold { background: linear-gradient(135deg, #fbbf24 0%, #d97706 100%); color: white; }
        .badge-silver { background: linear-gradient(135deg, #cbd5e1 0%, #64748b 100%); color: white; }
        .badge-bronze { background: linear-gradient(135deg, #d4a373 0%, #a26a42 100%); color: white; }
        
        .stat-card { background: white; border-radius: 16px; padding: 24px; text-align: center; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); transition: transform 0.2s; }
        .stat-card:hover { transform: translateY(-5px); }
        .stat-value { font-size: 32px; font-weight: 800; color: #0f172a; mb-1; }
        .stat-label { font-size: 13px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 1px; }
        
        .cert-card { background: white; border-radius: 12px; padding: 20px; box-shadow: 0 2px 4px rgba(0,0,0,0.02); border: 1px solid #e2e8f0; display: flex; align-items: center; gap: 16px; margin-bottom: 16px; transition: all 0.2s; }
        .cert-card:hover { border-color: #cbd5e1; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
        .cert-icon { width: 50px; height: 50px; border-radius: 10px; background: #f1f5f9; color: #0ea5e9; display: flex; align-items: center; justify-content: center; font-size: 20px; flex-shrink: 0; }
        .cert-title { font-weight: 700; color: #1e293b; margin-bottom: 4px; }
        .cert-verify { font-size: 12px; font-weight: 600; color: #10b981; }
        
        .course-tag { background: #f1f5f9; color: #475569; font-size: 13px; font-weight: 600; padding: 6px 12px; border-radius: 20px; display: inline-block; margin: 0 8px 8px 0; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>

<div class="portfolio-header text-center">
    <!-- Abstract Background Pattern -->
    <div style="position:absolute; top:-50%; left:-10%; width:120%; height:200%; background:radial-gradient(circle, rgba(255,255,255,0.03) 0%, transparent 60%); z-index:0; pointer-events:none;"></div>
    
    <div class="container position-relative" style="z-index: 1;">
        <div class="d-flex flex-column align-items-center">
            @php
                $badgeClass = 'badge-bronze'; $badgeIcon = 'fa-medal';
                if ($student->industryBadge == 'Platinum') { $badgeClass = 'badge-platinum'; $badgeIcon = 'fa-gem'; }
                elseif ($student->industryBadge == 'Gold') { $badgeClass = 'badge-gold'; $badgeIcon = 'fa-trophy'; }
                elseif ($student->industryBadge == 'Silver') { $badgeClass = 'badge-silver'; $badgeIcon = 'fa-award'; }
            @endphp
            <div class="mb-4">
                <img src="{{ ($student->profile_photo_data || $student->profile_photo) ? url('/profile/photo/user/' . $student->id) : 'https://ui-avatars.com/api/?name=' . urlencode($student->name) . '&background=6366f1&color=fff&size=200' }}" 
                     class="shadow-lg border border-3 border-white" 
                     style="width: 140px; height: 140px; border-radius: 30px; object-fit: cover;"
                     onerror="this.src='https://ui-avatars.com/api/?name={{ urlencode($student->name) }}&background=6366f1&color=fff&size=200'">
            </div>
            
            <h1 class="fw-bold mb-2">{{ $student->name }}</h1>
            <p class="fs-5 text-light opacity-75 mb-4">{{ $student->department ?? 'Computer Science & Engineering' }} | BAPS Innovation Campus</p>
            
            <div class="d-flex justify-content-center gap-3">
                <a href="mailto:{{ $student->email }}" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm"><i class="fas fa-envelope me-2"></i> Contact Me</a>
                <button class="btn btn-primary rounded-pill px-4 fw-bold shadow-sm" style="background:#ea580c; border:none;" onclick="window.print()"><i class="fas fa-print me-2"></i> Print Resume</button>
            </div>
        </div>
    </div>
</div>

<div class="container" style="margin-top: -30px; position: relative; z-index: 2;">
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-value text-primary">{{ $student->employabilityScore }}</div>
                <div class="stat-label">BAPS Employability Score</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-value text-success">{{ $certificates->count() }}</div>
                <div class="stat-label">Verified Certifications</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-card">
                <div class="stat-value text-info">{{ $enrollments->count() }}</div>
                <div class="stat-label">Courses Mastered</div>
            </div>
        </div>
    </div>

    <div class="row g-5">
        <!-- Left Column: Certifications & Projects -->
        <div class="col-md-8">
            <h4 class="fw-bold mb-4 border-bottom pb-2"><i class="fas fa-award text-success me-2"></i> Verified Credentials</h4>
            @forelse($certificates as $cert)
                <div class="cert-card">
                    <div class="cert-icon"><i class="fas fa-certificate"></i></div>
                    <div class="flex-grow-1">
                        <div class="cert-title">{{ $cert->course->title ?? 'Professional Certificate' }}</div>
                        <div class="text-muted small mb-1">Issued by BAPS e-Learn Platform</div>
                        <div class="cert-verify"><i class="fas fa-check-circle me-1"></i> Credential ID: {{ $cert->unique_code }}</div>
                    </div>
                </div>
            @empty
                <div class="text-muted fst-italic">Currently working towards first certification.</div>
            @endforelse

            <h4 class="fw-bold mt-5 mb-4 border-bottom pb-2"><i class="fas fa-laptop-code text-primary me-2"></i> Academic Exposure</h4>
            <div>
                @forelse($enrollments as $enr)
                    <div class="course-tag">{{ $enr->course->title ?? 'Active Course' }}</div>
                @empty
                    <div class="text-muted fst-italic">No active courses.</div>
                @endforelse
            </div>
        </div>

        <!-- Right Column: Profile Specs -->
        <div class="col-md-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-4"><i class="fas fa-user-tie text-secondary me-2"></i> Talent Profile</h5>
                    
                    <div class="mb-3">
                        <div class="small text-muted fw-bold text-uppercase">Enrollment No.</div>
                        <div class="fw-bold text-dark">{{ $student->enrollment_no }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="small text-muted fw-bold text-uppercase">ABC ID (Govt.)</div>
                        <div class="fw-bold text-dark">{{ $student->abc_card_id ?? 'Not Linked' }}</div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="small text-muted fw-bold text-uppercase">Program</div>
                        <div class="fw-bold text-dark">{{ $student->program ?? 'Bachelors' }}</div>
                    </div>

                    <div class="mb-0">
                        <div class="small text-muted fw-bold text-uppercase">Status</div>
                        <div><span class="badge bg-success bg-opacity-10 text-success border border-success">Ready for Placement</span></div>
                    </div>
                </div>
            </div>
            
            <div class="text-center text-muted small mt-4">
                <i class="fas fa-shield-alt me-1"></i> This is an officially auto-generated resume by the BAPS LMS framework. Data is cryptographically secure and strictly tracked based on academic performance.
            </div>
        </div>
    </div>
</div>

<footer class="mt-5 py-4 border-top text-center text-muted small">
    &copy; {{ date('Y') }} BAPS Innovation Campus. All Rights Reserved.
</footer>

</body>
</html>
