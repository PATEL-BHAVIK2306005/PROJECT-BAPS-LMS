@extends('layouts.app')
@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center bg-primary rounded-4 p-4 text-white shadow" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); position: relative; overflow: hidden;">
            <div class="position-relative z-1">
                <h3 class="fw-bold mb-1"><i class="fas fa-bullhorn me-2"></i> Student Corner</h3>
                <p class="mb-0 opacity-75">Connect, share, and stay updated with your campus community.</p>
            </div>
            <i class="fas fa-users fa-5x position-absolute end-0 bottom-0 opacity-25 me-4 mb-n2"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Left Column: Community Discussions -->
    <div class="col-lg-7">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Recent Discussions</h5>
            <button class="btn btn-primary btn-sm rounded-pill px-3 shadow-sm"><i class="fas fa-plus me-1"></i> New Topic</button>
        </div>

        <div class="glass-card mb-3 p-3 border-0 shadow-sm transition-hover">
            <div class="d-flex gap-3">
                <img src="https://ui-avatars.com/api/?name=Darshan+Patel&background=e2e8f0&color=475569" class="rounded-circle" width="48" height="48">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold mb-1"><a href="#" class="text-dark text-decoration-none">Study Group for Data Structures (Mid Terms)</a></h6>
                        <small class="text-muted">2h ago</small>
                    </div>
                    <p class="text-muted small mb-2">Hey everyone! I'm organizing a study group this weekend for the upcoming Data Structures exam. Let me know if you want to join!</p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-primary-subtle text-primary rounded-pill px-2"><i class="fas fa-tag me-1"></i> Academics</span>
                        <span class="small text-muted"><i class="far fa-comment-alt me-1"></i> 14 Replies</span>
                        <span class="small text-muted"><i class="far fa-heart me-1"></i> 32 Likes</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="glass-card mb-3 p-3 border-0 shadow-sm transition-hover">
            <div class="d-flex gap-3">
                <img src="https://ui-avatars.com/api/?name=Priya+Sharma&background=fee2e2&color=ef4444" class="rounded-circle" width="48" height="48">
                <div class="flex-grow-1">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold mb-1"><a href="#" class="text-dark text-decoration-none">Lost AirPods Pro in Library</a></h6>
                        <small class="text-muted">5h ago</small>
                    </div>
                    <p class="text-muted small mb-2">Has anyone seen a white AirPods Pro case near the 2nd floor computers? Please DM me!</p>
                    <div class="d-flex align-items-center gap-3">
                        <span class="badge bg-danger-subtle text-danger rounded-pill px-2"><i class="fas fa-exclamation-circle me-1"></i> Lost & Found</span>
                        <span class="small text-muted"><i class="far fa-comment-alt me-1"></i> 2 Replies</span>
                        <span class="small text-muted"><i class="far fa-heart me-1"></i> 5 Likes</span>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="text-center mt-3">
            <button class="btn btn-outline-primary btn-sm rounded-pill px-4">Load More Discussions</button>
        </div>
    </div>

    <!-- Right Column: Resources & Help -->
    <div class="col-lg-5">
        <h5 class="fw-bold mb-3">Quick Links & Resources</h5>
        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="glass-card p-3 border-0 shadow-sm text-center h-100 transition-hover" style="background: rgba(99, 102, 241, 0.05);">
                        <i class="fas fa-calendar-alt fa-2x text-primary mb-2"></i>
                        <h6 class="fw-bold mb-0">Academic Calendar</h6>
                    </div>
                </a>
            </div>
            <div class="col-sm-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="glass-card p-3 border-0 shadow-sm text-center h-100 transition-hover" style="background: rgba(34, 197, 94, 0.05);">
                        <i class="fas fa-file-invoice-dollar fa-2x text-success mb-2"></i>
                        <h6 class="fw-bold mb-0">Fee Portal</h6>
                    </div>
                </a>
            </div>
            <div class="col-sm-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="glass-card p-3 border-0 shadow-sm text-center h-100 transition-hover" style="background: rgba(245, 158, 11, 0.05);">
                        <i class="fas fa-book-reader fa-2x text-warning mb-2"></i>
                        <h6 class="fw-bold mb-0">Library System</h6>
                    </div>
                </a>
            </div>
            <div class="col-sm-6">
                <a href="#" class="text-decoration-none text-dark">
                    <div class="glass-card p-3 border-0 shadow-sm text-center h-100 transition-hover" style="background: rgba(239, 68, 68, 0.05);">
                        <i class="fas fa-bus fa-2x text-danger mb-2"></i>
                        <h6 class="fw-bold mb-0">Transport Route</h6>
                    </div>
                </a>
            </div>
        </div>

        <h5 class="fw-bold mb-3">Important Contacts</h5>
        <div class="glass-card p-0 border-0 shadow-sm overflow-hidden">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-transparent">
                    <div>
                        <h6 class="fw-bold mb-0"><i class="fas fa-headset text-primary me-2"></i> IT Support Desk</h6>
                        <small class="text-muted">For LMS & WiFi Issues</small>
                    </div>
                    <a href="mailto:it@university.edu" class="btn btn-light btn-sm rounded-circle"><i class="fas fa-envelope"></i></a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-transparent">
                    <div>
                        <h6 class="fw-bold mb-0"><i class="fas fa-user-md text-success me-2"></i> Health Center</h6>
                        <small class="text-muted">Medical emergencies</small>
                    </div>
                    <a href="tel:+1234567890" class="btn btn-light btn-sm rounded-circle"><i class="fas fa-phone"></i></a>
                </li>
                <li class="list-group-item d-flex justify-content-between align-items-center py-3 bg-transparent">
                    <div>
                        <h6 class="fw-bold mb-0"><i class="fas fa-building text-warning me-2"></i> Admin Office</h6>
                        <small class="text-muted">Documents & Fees</small>
                    </div>
                    <button class="btn btn-light btn-sm rounded-circle"><i class="fas fa-info"></i></button>
                </li>
            </ul>
        </div>
    </div>
</div>

<style>
.transition-hover {
    transition: transform 0.2s, box-shadow 0.2s;
}
.transition-hover:hover {
    transform: translateY(-3px);
    box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important;
}
</style>

@endsection
