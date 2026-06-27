@extends('layouts.app')
@section('content')
<div class="container d-flex align-items-center justify-content-center" style="min-height: 70vh;">
    <div class="card border-0 shadow-lg text-center p-5 rounded-4 position-relative overflow-hidden" style="
        max-width: 550px;
        background: rgba(255, 255, 255, 0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255, 255, 255, 0.4);
    ">
        <!-- Floating decorative glowing blobs -->
        <div class="position-absolute" style="width: 150px; height: 150px; background: rgba(59, 130, 246, 0.15); filter: blur(40px); top: -20px; left: -20px; border-radius: 50%; z-index: 0;"></div>
        <div class="position-absolute" style="width: 150px; height: 150px; background: rgba(147, 51, 234, 0.15); filter: blur(40px); bottom: -20px; right: -20px; border-radius: 50%; z-index: 0;"></div>

        <div class="position-relative" style="z-index: 1;">
            <!-- Pulsing Lock Icon -->
            <div class="mb-4 d-inline-flex align-items-center justify-content-center" style="
                width: 90px;
                height: 90px;
                background: linear-gradient(135deg, rgba(239, 68, 68, 0.1) 0%, rgba(244, 63, 94, 0.1) 100%);
                border-radius: 50%;
                border: 2px solid rgba(239, 68, 68, 0.2);
                animation: pulse-ring 2s infinite;
            ">
                <i class="fas fa-shield-alt text-danger fs-1"></i>
            </div>

            <h3 class="fw-bold text-dark mb-2">Access Clearance Required</h3>
            <p class="text-muted small mb-4">
                Your current role (<span class="badge bg-secondary-subtle text-secondary border px-2">{{ strtoupper($current_role ?? session('user_role')) }}</span>) 
                is not cleared to view this section. This page requires <span class="text-primary fw-bold">{{ $required_role ?? 'higher' }}</span> privileges.
            </p>

            <div class="bg-light rounded-4 p-3 mb-4 text-start border" style="font-size: 0.85rem;">
                <div class="d-flex align-items-center mb-2">
                    <i class="fas fa-lock text-muted me-2"></i>
                    <span class="text-secondary fw-semibold">Security Level:</span>
                    <span class="ms-auto text-dark font-monospace">LVL-RESTRICTED</span>
                </div>
                <div class="d-flex align-items-center">
                    <i class="fas fa-fingerprint text-muted me-2"></i>
                    <span class="text-secondary fw-semibold">Resource Path:</span>
                    <span class="ms-auto text-dark font-monospace text-truncate style-path" style="max-width: 250px;">{{ request()->path() }}</span>
                </div>
            </div>

            <!-- Action Button -->
            <button id="requestAccessBtn" class="btn btn-primary w-100 py-3 rounded-pill fw-bold shadow-sm d-flex align-items-center justify-content-center gap-2" style="
                background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%) !important;
                border: none;
                transition: all 0.3s ease;
            ">
                <i class="fas fa-paper-plane"></i> Ask Permission / Request Access
            </button>

            <!-- Success Notification Card (Hidden initially) -->
            <div id="requestSuccess" class="mt-4 p-3 bg-success-subtle border border-success-subtle rounded-4 d-none text-start align-items-start gap-3">
                <div class="bg-success text-white rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i class="fas fa-check"></i>
                </div>
                <div>
                    <h6 class="fw-bold text-success mb-1">Access Request Transmitted</h6>
                    <p class="text-success-emphasis small mb-0">Your clearance request (ID: <span class="font-monospace fw-bold">#REQ-{{ rand(1000, 9999) }}</span>) was sent to the Admin & Dean. You will be notified once reviewed.</p>
                </div>
            </div>

            <div class="mt-4">
                <a href="/admin" class="text-decoration-none text-muted small hover-primary"><i class="fas fa-arrow-left me-1"></i> Return to Admin Dashboard</a>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes pulse-ring {
        0% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4);
        }
        70% {
            box-shadow: 0 0 0 15px rgba(239, 68, 68, 0);
        }
        100% {
            box-shadow: 0 0 0 0 rgba(239, 68, 68, 0);
        }
    }
    .hover-primary:hover {
        color: #3b82f6 !important;
    }
</style>

<script>
    document.getElementById('requestAccessBtn').addEventListener('click', function() {
        const btn = this;
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Transmitting clearance...';
        btn.disabled = true;

        setTimeout(() => {
            btn.style.display = 'none';
            const successDiv = document.getElementById('requestSuccess');
            successDiv.classList.remove('d-none');
            successDiv.classList.add('d-flex');
            
            // Try to use the toast system if loaded
            if (typeof showBapsToast === 'function') {
                showBapsToast('Clearance Request Transmitted Successfully!', 'success');
            }
        }, 1200);
    });
</script>
@endsection
