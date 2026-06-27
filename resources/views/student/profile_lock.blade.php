@extends('layouts.app')
@section('content')

<div class="row justify-content-center mt-5">
    <div class="col-md-5">
        <div class="glass-card p-5 border-0 text-center shadow-lg position-relative overflow-hidden" style="background: rgba(255, 255, 255, 0.9);">
            <!-- Decorative Background -->
            <div class="position-absolute top-0 start-0 w-100 h-100 opacity-25" style="background: var(--primary-gradient); z-index: 0;"></div>
            
            <div class="position-relative z-1">
                <div class="bg-white rounded-circle shadow-sm d-flex align-items-center justify-content-center mx-auto mb-4" style="width: 80px; height: 80px;">
                    <i class="fas fa-lock fa-2x text-primary"></i>
                </div>
                
                <h3 class="fw-bold mb-2">Profile Locked</h3>
                <p class="text-muted small mb-4">For your security, please enter your password or access code to view your profile and sensitive information.</p>
                
                @if(session('error'))
                    <div class="alert alert-danger py-2 small fw-bold shadow-sm rounded-pill">{{ session('error') }}</div>
                @endif
                
                <form action="/profile/unlock" method="POST">
                    @csrf
                    <div class="mb-4 text-start">
                        <label class="form-label small fw-bold text-uppercase text-muted ms-2">Access Password</label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0 rounded-start-pill ps-4 text-muted"><i class="fas fa-key"></i></span>
                            <input type="password" name="password" class="form-control border-start-0 rounded-end-pill py-2 focus-ring px-3" placeholder="Enter password..." required autofocus>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn w-100 text-white rounded-pill py-2 fw-bold shadow-sm" style="background: var(--primary-gradient);">
                        <i class="fas fa-unlock-alt me-2"></i> Unlock Profile
                    </button>
                    
                    <a href="/dashboard" class="btn btn-light w-100 rounded-pill py-2 mt-2 fw-bold border">
                        <i class="fas fa-arrow-left me-2"></i> Return to Dashboard
                    </a>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
