<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BAPS parent portal registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #fdf8f5;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        }
        .reg-container {
            max-width: 650px;
            margin: 3.5rem auto;
            background: white;
            border-radius: 1.25rem;
            box-shadow: 0 15px 40px rgba(249, 115, 22, 0.06), 0 5px 15px rgba(0,0,0,0.03);
            overflow: hidden;
            border: 1px solid rgba(249, 115, 22, 0.1);
        }
        .reg-header {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            padding: 2.5rem;
            text-align: center;
        }
        .form-control {
            border-radius: 0.5rem;
            padding: 0.75rem 1rem;
            background-color: #fafbfc;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        .form-control:focus {
            box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
            border-color: #f97316;
            background-color: white;
        }
        .btn-register {
            background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);
            color: white;
            padding: 0.85rem;
            border: none;
            border-radius: 0.5rem;
            font-weight: 700;
            transition: all 0.25s ease;
        }
        .btn-register:hover {
            transform: translateY(-1.5px);
            box-shadow: 0 6px 20px rgba(234, 88, 12, 0.3);
            color: white;
        }
    </style>
</head>
<body>

<div class="container pb-5">
    <div class="reg-container">
        <div class="reg-header">
            <h3 class="fw-bold mb-2"><i class="fas fa-user-shield me-2"></i> Parent/Guardian Registration</h3>
            <p class="mb-0 text-white-50 small">Jay Swaminarayan! Register here to access child monitoring tools & PTM reports.</p>
        </div>

        <div class="p-4 p-md-5">
            @if($errors->any())
                <div class="alert alert-danger p-3 mb-4 rounded-3 border-0 shadow-sm">
                    <ul class="mb-0 small fw-bold text-danger">
                        @foreach($errors->all() as $error)
                            <li><i class="fas fa-exclamation-circle me-1"></i> {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="/parent/register" method="POST">
                @csrf
                
                <h5 class="fw-bold mb-3 border-bottom pb-2" style="color: #ea580c;"><i class="fas fa-id-card me-2"></i> Parent Account Credentials</h5>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Parent / Guardian Full Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter your full name" value="{{ old('name') }}" required>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted small fw-bold">Parent Email Address</label>
                    <input type="email" name="email" class="form-control" placeholder="Enter your email address" value="{{ old('email') }}" required>
                </div>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Login Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Create password" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" required>
                    </div>
                </div>

                <h5 class="fw-bold mb-3 border-bottom pb-2 mt-4" style="color: #ea580c;"><i class="fas fa-user-graduate me-2"></i> Student Verification</h5>
                <p class="text-muted small mb-3">Provide the child's official enrollment details for verification and mapping link.</p>

                <div class="row g-3 mb-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Child Enrollment Number (8 Digits)</label>
                        <input type="text" name="student_enrollment" class="form-control" placeholder="e.g. 23024921" value="{{ old('student_enrollment') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted small fw-bold">Child Date of Birth</label>
                        <input type="date" name="student_dob" class="form-control" value="{{ old('student_dob') }}" required>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-register w-100 fs-5"><i class="fas fa-user-plus me-2"></i> Register Parent Account</button>
                    <div class="text-center mt-4">
                        <a href="/login" class="text-muted text-decoration-none small fw-bold"><i class="fas fa-arrow-left me-1"></i> Back to Portal Login</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
