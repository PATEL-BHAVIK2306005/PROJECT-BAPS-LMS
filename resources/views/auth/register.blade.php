<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .reg-container {
            max-width: 900px;
            margin: 3rem auto;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .reg-header {
            background: linear-gradient(135deg, #1e1b4b, #4338ca);
            color: white;
            padding: 3rem;
            text-align: center;
        }
        .form-control, .form-select {
            border-radius: 0.5rem;
            padding: 0.75rem;
            background-color: #f8f9fa;
            border: 1px solid #e9ecef;
        }
        .form-control:focus, .form-select:focus {
            box-shadow: none;
            border-color: #4f46e5;
            background-color: white;
        }
        .btn-apply {
            background: #4f46e5;
            color: white;
            padding: 1rem;
            border-radius: 0.5rem;
            font-weight: bold;
            transition: all 0.3s ease;
        }
        .btn-apply:hover {
            background: #4338ca;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(79, 70, 229, 0.4);
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

<div class="container pb-5">
    <div class="reg-container">
        <div class="reg-header">
            <h2 class="fw-bold mb-2"><i class="fas fa-university me-2"></i> Official Enrollment Application</h2>
            <p class="mb-0 text-white-50">Please provide all 13 details accurately. Your application will be reviewed by the Head of Department.</p>
        </div>

        <div class="p-4 p-md-5">
            @if(session('success'))
                <div class="alert alert-success fs-5 p-4 text-center fw-bold shadow-sm">
                    <i class="fas fa-check-circle fs-1 d-block mb-3 text-success"></i>
                    {{ session('success') }}
                </div>
            @endif
            
            @if($errors->any())
                <div class="alert alert-danger p-3 mb-4">
                    <ul class="mb-0 small fw-bold">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('tracking_id'))
                <div class="alert alert-info fs-5 p-4 text-center fw-bold shadow-sm mb-4">
                    Your Application Tracking ID is: <span class="badge bg-primary fs-4">{{ session('tracking_id') }}</span><br>
                    <small class="fw-normal mt-2 d-block text-dark">Please save this ID to track your application status below.</small>
                </div>
            @endif

            <div class="card mb-5 border-0 shadow-sm" style="background: #eef2ff;">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-primary mb-3" style="color: #4f46e5 !important;"><i class="fas fa-search me-2"></i> Track Application Status</h5>
                    <form action="/track-application" method="POST" class="d-flex gap-2 mb-3">
                        @csrf
                        <input type="email" name="tracking_id" class="form-control form-control-lg" placeholder="Enter Registered Email ID" required>
                        <button type="submit" class="btn btn-primary px-4 fw-bold" style="background: #4f46e5; border-color: #4f46e5;">Check Status</button>
                    </form>
                    
                    @if(session('track_error'))
                        <div class="alert alert-danger mt-3 mb-0 fw-bold">
                            {{ session('track_error') }}
                        </div>
                    @endif

                    @if(session('tracked_user_id'))
                        @php
                            $trackedUser = \App\Models\User::find(session('tracked_user_id'));
                            $stage = $trackedUser->application_stage;
                        @endphp
                        
                        <div class="mt-4 p-4 bg-white rounded-3 shadow-sm border">
                            <h6 class="fw-bold text-dark mb-4 border-bottom pb-2">Application Progress: {{ $trackedUser->email }}</h6>
                            
                            <div class="position-relative mb-5">
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" role="progressbar" style="width: {{ ($stage - 1) * 25 }}%; background-color: #4f46e5;" aria-valuenow="{{ ($stage - 1) * 25 }}" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <div class="d-flex justify-content-between position-absolute w-100" style="top: -12px;">
                                    <div class="text-center" style="width: 30px;">
                                        <div class="rounded-circle {{ $stage >= 1 ? 'bg-primary text-white' : 'bg-light text-muted border' }} d-flex align-items-center justify-content-center mx-auto" style="width: 30px; height: 30px; font-size: 12px; background-color: {{ $stage >= 1 ? '#4f46e5 !important' : '' }};"><i class="fas fa-file-alt"></i></div>
                                        <div class="mt-2 small fw-bold {{ $stage >= 1 ? 'text-primary' : 'text-muted' }}" style="font-size: 0.7rem; color: {{ $stage >= 1 ? '#4f46e5 !important' : '' }};">Submitted</div>
                                    </div>
                                    <div class="text-center" style="width: 30px;">
                                        <div class="rounded-circle {{ $stage >= 2 ? 'bg-primary text-white' : 'bg-light text-muted border' }} d-flex align-items-center justify-content-center mx-auto" style="width: 30px; height: 30px; font-size: 12px; background-color: {{ $stage >= 2 ? '#4f46e5 !important' : '' }};"><i class="fas fa-user-clock"></i></div>
                                        <div class="mt-2 small fw-bold {{ $stage >= 2 ? 'text-primary' : 'text-muted' }}" style="font-size: 0.7rem; color: {{ $stage >= 2 ? '#4f46e5 !important' : '' }};">Review</div>
                                    </div>
                                    <div class="text-center" style="width: 30px;">
                                        <div class="rounded-circle {{ $stage >= 3 ? 'bg-primary text-white' : 'bg-light text-muted border' }} d-flex align-items-center justify-content-center mx-auto" style="width: 30px; height: 30px; font-size: 12px; background-color: {{ $stage >= 3 ? '#4f46e5 !important' : '' }};"><i class="fas fa-file-signature"></i></div>
                                        <div class="mt-2 small fw-bold {{ $stage >= 3 ? 'text-primary' : 'text-muted' }}" style="font-size: 0.7rem; color: {{ $stage >= 3 ? '#4f46e5 !important' : '' }};">T&C</div>
                                    </div>
                                    <div class="text-center" style="width: 30px;">
                                        <div class="rounded-circle {{ $stage >= 4 ? 'bg-primary text-white' : 'bg-light text-muted border' }} d-flex align-items-center justify-content-center mx-auto" style="width: 30px; height: 30px; font-size: 12px; background-color: {{ $stage >= 4 ? '#4f46e5 !important' : '' }};"><i class="fas fa-shield-alt"></i></div>
                                        <div class="mt-2 small fw-bold {{ $stage >= 4 ? 'text-primary' : 'text-muted' }}" style="font-size: 0.7rem; color: {{ $stage >= 4 ? '#4f46e5 !important' : '' }};">Verifying</div>
                                    </div>
                                    <div class="text-center" style="width: 30px;">
                                        <div class="rounded-circle {{ $stage >= 5 ? 'bg-success text-white' : 'bg-light text-muted border' }} d-flex align-items-center justify-content-center mx-auto" style="width: 30px; height: 30px; font-size: 12px;"><i class="fas fa-check"></i></div>
                                        <div class="mt-2 small fw-bold {{ $stage >= 5 ? 'text-success' : 'text-muted' }}" style="font-size: 0.7rem;">Unlocked</div>
                                    </div>
                                </div>
                            </div>

                            @if($stage == 3)
                                <div class="alert alert-warning p-4 border border-warning rounded-3 shadow-sm">
                                    <h5 class="fw-bold text-dark"><i class="fas fa-exclamation-circle text-warning me-2"></i> Action Required: Accept Terms & Conditions</h5>
                                    <p class="small text-muted mb-3">Your application has been approved by the Administration. To proceed, please read and accept our Institutional policies and apply your digital signature below.</p>
                                    
                                    <div class="bg-white border p-3 rounded mb-3" style="max-height: 150px; overflow-y: auto; font-size: 0.85rem;">
                                        <h6 class="fw-bold text-dark">13 Rules of Conduct</h6>
                                        <ol class="ps-3 text-muted"><li>Maintain discipline on campus.</li><li>Respect all faculty and peers.</li><li>Wear ID card at all times.</li><li>Attend 75% of lectures.</li><li>Do not damage campus property.</li><li>No ragging or bullying.</li><li>No unauthorized visitors.</li><li>Follow library rules.</li><li>Abide by exam regulations.</li><li>Use internet for academic purposes only.</li><li>No smoking or alcohol.</li><li>Park vehicles in designated areas.</li><li>Report issues to designated authorities.</li></ol>
                                        <h6 class="fw-bold text-dark mt-2">14 Terms of Use</h6>
                                        <p class="text-muted mb-0">By using this system, you agree to: 1. Maintain confidentiality of login credentials. 2. Not misuse data. 3. Update profile regularly. 4. Check notices. 5. Pay fees on time. 6. Follow the code of ethics. 7. Respect IP rights. 8. Report bugs. 9. Do not share access. 10. Follow data privacy norms. 11. Do not upload malicious files. 12. Cooperate with audits. 13. Subject to jurisdiction. 14. Understand that access can be revoked.</p>
                                        <h6 class="fw-bold text-dark mt-2">10 Code of Conduct Guidelines</h6>
                                        <p class="text-muted mb-0">1. Integrity. 2. Honesty. 3. Respect. 4. Responsibility. 5. Accountability. 6. Professionalism. 7. Punctuality. 8. Dress code compliance. 9. Safe environment promotion. 10. Adherence to all statutory laws.</p>
                                    </div>

                                    <form action="/track-application/submit-tc" method="POST" id="tcForm">
                                        @csrf
                                        <input type="hidden" name="tracking_id" value="{{ $trackedUser->email }}">
                                        
                                        <div class="form-check mb-3">
                                            <input class="form-check-input" type="checkbox" name="tc_accepted" value="1" id="tcCheck" required>
                                            <label class="form-check-label small fw-bold text-dark" for="tcCheck">
                                                I have read and agree to all 13 Rules, 14 Terms, and 10 Code of Conduct Guidelines.
                                            </label>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold text-dark">Digital Signature <span class="text-danger">*</span></label>
                                            <div class="border rounded bg-white" style="width: 100%; height: 150px; position: relative;">
                                                <canvas id="signatureCanvas" style="width: 100%; height: 100%; cursor: crosshair;"></canvas>
                                                <button type="button" class="btn btn-sm btn-light position-absolute top-0 end-0 m-1 border shadow-sm fw-bold" onclick="clearSignature()"><i class="fas fa-eraser"></i> Clear</button>
                                            </div>
                                            <input type="hidden" name="digital_signature" id="signatureData" required>
                                            <div class="form-text text-muted small">Please draw your signature using your mouse or touch screen.</div>
                                        </div>

                                        <button type="submit" class="btn btn-success w-100 fw-bold shadow-sm" onclick="return saveSignature()"><i class="fas fa-file-signature me-2"></i> Submit & Apply Signature</button>
                                    </form>
                                    
                                    <script>
                                        const canvas = document.getElementById('signatureCanvas');
                                        const ctx = canvas.getContext('2d');
                                        let isDrawing = false;

                                        function resizeCanvas() {
                                            const rect = canvas.parentElement.getBoundingClientRect();
                                            canvas.width = rect.width;
                                            canvas.height = rect.height;
                                        }
                                        window.addEventListener('resize', resizeCanvas);
                                        // initial resize might need a delay due to rendering
                                        setTimeout(resizeCanvas, 100);

                                        function getCoordinates(e) {
                                            const rect = canvas.getBoundingClientRect();
                                            const clientX = e.clientX || e.touches[0].clientX;
                                            const clientY = e.clientY || e.touches[0].clientY;
                                            return {
                                                x: clientX - rect.left,
                                                y: clientY - rect.top
                                            };
                                        }

                                        function startDrawing(e) {
                                            isDrawing = true;
                                            const coords = getCoordinates(e);
                                            ctx.beginPath();
                                            ctx.moveTo(coords.x, coords.y);
                                            e.preventDefault();
                                        }

                                        function draw(e) {
                                            if (!isDrawing) return;
                                            const coords = getCoordinates(e);
                                            ctx.lineTo(coords.x, coords.y);
                                            ctx.strokeStyle = '#000';
                                            ctx.lineWidth = 2;
                                            ctx.lineCap = 'round';
                                            ctx.stroke();
                                            e.preventDefault();
                                        }

                                        function stopDrawing() {
                                            isDrawing = false;
                                        }

                                        canvas.addEventListener('mousedown', startDrawing);
                                        canvas.addEventListener('mousemove', draw);
                                        canvas.addEventListener('mouseup', stopDrawing);
                                        canvas.addEventListener('mouseout', stopDrawing);

                                        canvas.addEventListener('touchstart', startDrawing);
                                        canvas.addEventListener('touchmove', draw);
                                        canvas.addEventListener('touchend', stopDrawing);

                                        function clearSignature() {
                                            ctx.clearRect(0, 0, canvas.width, canvas.height);
                                        }

                                        function saveSignature() {
                                            const blank = document.createElement('canvas');
                                            blank.width = canvas.width;
                                            blank.height = canvas.height;
                                            if(canvas.toDataURL() == blank.toDataURL()) {
                                                alert('Please provide your digital signature.');
                                                return false;
                                            }
                                            document.getElementById('signatureData').value = canvas.toDataURL();
                                            return true;
                                        }
                                    </script>
                                </div>
                            @elseif($stage == 5)
                                <div class="alert alert-success p-4 border border-success rounded-3 shadow-sm text-center">
                                    <h4 class="fw-bold text-success mb-3"><i class="fas fa-check-circle fs-2 d-block mb-2"></i> Profile Unlocked!</h4>
                                    <p class="text-muted">Your application has been fully processed and approved. Here are your system credentials:</p>
                                    
                                    <div class="bg-white p-3 rounded border my-3 d-inline-block text-start shadow-sm">
                                        <div class="mb-2"><span class="text-muted small fw-bold">USER ID (EMAIL):</span> <span class="fw-bold text-dark fs-5 ms-2">{{ $trackedUser->email }}</span></div>
                                        <div><span class="text-muted small fw-bold">PASSWORD:</span> <span class="fw-bold text-primary fs-5 ms-2" style="font-family: monospace;">{{ $trackedUser->generated_password }}</span></div>
                                    </div>
                                    
                                    <p class="small text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> Please save this password immediately.</p>
                                    
                                    <a href="/login" class="btn btn-primary fw-bold px-5 py-2 mt-2 rounded-pill shadow-sm" style="background: #4f46e5; border: none;">Login to System</a>
                                </div>
                            @else
                                <div class="text-center p-3">
                                    @if($stage == 1 || $stage == 2)
                                        <h5 class="fw-bold text-dark mb-2">Application Under Review</h5>
                                        <p class="text-muted small">Your application is currently being reviewed by the administration. Please check back later.</p>
                                    @elseif($stage == 4)
                                        <h5 class="fw-bold text-dark mb-2">Verifying Signature & T&C</h5>
                                        <p class="text-muted small">We are verifying your digital signature. Your profile will be unlocked shortly.</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            </div>

            <form action="/register" method="POST">
                @csrf
                <div class="row g-4">
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-0 mt-4"><i class="fas fa-user-tag me-2"></i> Identity Details</h5>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Full Name (As per Aadhar)</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Official Email Address</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Student Enrollment Number</label>
                        <input type="text" name="enrollment_no" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Academic Bank of Credits (ABC) ID</label>
                        <input type="text" name="abc_card_id" class="form-control" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Aadhar / National ID Number</label>
                        <input type="text" name="aadhar_no" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Department Selection</label>
                        <select name="department_id" class="form-select" required>
                            <option value="">-- Choose Department --</option>
                            @foreach($departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-0 mt-5"><i class="fas fa-heartbeat me-2"></i> Personal Bio</h5>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Date of Birth</label>
                        <input type="date" name="dob" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Gender</label>
                        <select name="gender" class="form-select" required>
                            <option value="">Select</option>
                            <option value="Male">Male</option>
                            <option value="Female">Female</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label text-muted small fw-bold">Blood Group</label>
                        <select name="blood_group" class="form-select" required>
                            <option value="">Select</option>
                            <option value="A+">A+</option>
                            <option value="B+">B+</option>
                            <option value="O+">O+</option>
                            <option value="AB+">AB+</option>
                            <option value="A-">A-</option>
                            <option value="B-">B-</option>
                            <option value="O-">O-</option>
                        </select>
                    </div>
                    
                    <h5 class="fw-bold text-primary border-bottom pb-2 mb-0 mt-5"><i class="fas fa-address-book me-2"></i> Contact & Emergency</h5>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Primary Contact Number</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Guardian / Father's Name</label>
                        <input type="text" name="guardian_name" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted small fw-bold">Permanent Address</label>
                        <textarea name="address" class="form-control" rows="3" required></textarea>
                    </div>

                </div>

                <div class="mt-5">
                    <button type="submit" class="btn btn-apply w-100 fs-5"><i class="fas fa-paper-plane me-2"></i> Submit Application for HOD Approval</button>
                    <div class="text-center mt-3">
                        <a href="/login" class="text-muted text-decoration-none small fw-bold"><i class="fas fa-arrow-left me-1"></i> Back to Passcode Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
