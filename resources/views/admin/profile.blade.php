@extends('layouts.app')
@section('content')

<style>
    .profile-banner {
        height: 200px;
        background: var(--primary-gradient);
        border-radius: 16px 16px 0 0;
        position: relative;
        overflow: hidden;
    }
    .profile-banner::after {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: url('https://www.transparenttextures.com/patterns/cubes.png');
        opacity: 0.15;
        z-index: 1;
    }
    .profile-avatar-wrapper {
        position: relative;
        margin-top: -60px;
        z-index: 2;
        padding-left: 2rem;
        display: flex;
        align-items: flex-end;
        gap: 1.5rem;
    }
    .profile-avatar {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        background: #ffffff;
        padding: 6px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    .profile-avatar-inner {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: var(--primary-gradient);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3.5rem;
        font-weight: bold;
    }
    .profile-card {
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid var(--glass-border);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .profile-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    .stat-badge {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: 12px;
        background: #f8fafc;
        border: 1px solid #e2e8f0;
        font-weight: 600;
        color: #334155;
    }
    .activity-timeline {
        border-left: 2px solid #e2e8f0;
        padding-left: 24px;
        margin-left: 12px;
    }
    .timeline-item {
        position: relative;
        margin-bottom: 1.8rem;
    }
    .timeline-item::before {
        content: '';
        position: absolute;
        left: -31px;
        top: 6px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #6366f1;
        border: 2px solid #ffffff;
        box-shadow: 0 0 0 2px #e2e8f0;
    }
    .timeline-item:last-child { margin-bottom: 0; }
    
    @media (max-width: 768px) {
        .profile-avatar-wrapper {
            flex-direction: column;
            align-items: center;
            text-align: center;
            padding-left: 0;
        }
        .profile-avatar-wrapper .ms-auto {
            margin-left: 0 !important;
            padding-right: 0 !important;
            margin-top: 1rem;
        }
        .d-flex.align-items-center.gap-3 {
            flex-direction: column;
            gap: 0.5rem !important;
        }
    }
</style>

<div class="container-fluid py-2">
    <!-- Header Hero Banner -->
    <div class="glass-card border-0 shadow-sm position-relative overflow-hidden mb-4 bg-white" style="border-radius: 16px;">
        <div class="profile-banner d-flex align-items-center justify-content-end px-5">
            <i class="fas fa-shield-alt fa-10x opacity-10 text-white" style="transform: rotate(15deg) translateY(20px);"></i>
        </div>
        
        <div class="profile-avatar-wrapper mb-4">
            <div class="profile-avatar">
                <div class="profile-avatar-inner shadow-sm">
                    {{ strtoupper(substr(session('staff_name', 'AD'), 0, 2)) }}
                </div>
            </div>
            <div class="mb-2">
                <h2 class="fw-bolder mb-1 text-dark" style="letter-spacing: -0.5px;">{{ session('staff_name', 'System Administrator') }} <i class="fas fa-check-circle text-primary fs-5 ms-1" title="Verified System Admin"></i></h2>
                <div class="d-flex align-items-center gap-3 mt-2">
                    <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold" style="letter-spacing: 0.5px;">
                        <i class="fas fa-user-shield me-1"></i> {{ strtoupper(session('user_role', 'Admin')) }}
                    </span>
                    <span class="text-muted fw-medium small"><i class="fas fa-envelope text-secondary me-1"></i> admin@baps.edu.in</span>
                    <span class="text-muted fw-medium small"><i class="fas fa-map-marker-alt text-danger me-1"></i> BAPS Innovation Campus</span>
                </div>
            </div>
            
            <div class="ms-auto pe-4 mb-3">
                <button class="btn btn-dark rounded-pill px-4 fw-bold shadow-sm"><i class="fas fa-edit me-2"></i> Edit Profile</button>
            </div>
        </div>
        
        <!-- Key Metrics Row -->
        <div class="row g-4 px-4 pb-4 border-top pt-4 mx-0 bg-light bg-opacity-50">
            <div class="col-md-3">
                <div class="stat-badge w-100 shadow-sm bg-white transition-all">
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-building fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Department Access</div>
                        <div class="fw-bolder text-dark fs-6">All Global Depts</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-badge w-100 shadow-sm bg-white">
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-lock fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Account Security</div>
                        <div class="fw-bolder text-dark fs-6">Highly Secure</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-badge w-100 shadow-sm bg-white">
                    <div class="rounded-circle bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-users fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">Managed Users</div>
                        <div class="fw-bolder text-dark fs-6">12,450+ Active</div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-badge w-100 shadow-sm bg-white">
                    <div class="rounded-circle bg-info bg-opacity-10 text-info d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fas fa-server fs-5"></i>
                    </div>
                    <div>
                        <div class="text-muted fw-bold" style="font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.5px;">System Health</div>
                        <div class="fw-bolder text-dark fs-6">Optimal 99.9%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="row g-4">
        <!-- Left Column (Security) -->
        <div class="col-lg-4">
            <div class="profile-card p-4 h-100">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-user-shield"></i></div>
                    <h5 class="fw-bold m-0 text-dark">Security Credentials</h5>
                </div>
                
                <p class="text-muted mb-4 text-sm lh-lg">Your administrator account grants you unrestricted root access to courses, financial records, student data, and global institution configurations.</p>
                
                <div class="d-flex flex-column gap-3 mb-4">
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-4 bg-light border shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-fingerprint text-secondary fs-4"></i>
                            <div>
                                <h6 class="m-0 fw-bold fs-6">2FA Authentication</h6>
                                <small class="text-success fw-bold"><i class="fas fa-check-circle me-1"></i> Active via App</small>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center p-3 rounded-4 bg-light border shadow-sm">
                        <div class="d-flex align-items-center gap-3">
                            <i class="fas fa-desktop text-secondary fs-4"></i>
                            <div>
                                <h6 class="m-0 fw-bold fs-6">Active Sessions</h6>
                                <small class="text-muted fw-medium">3 Devices Connected</small>
                            </div>
                        </div>
                    </div>
                </div>
                
                <button class="btn btn-outline-primary fw-bold rounded-pill w-100 py-2"><i class="fas fa-key me-2"></i> Manage Access Tokens</button>
            </div>
        </div>

        <!-- Right Column (Activity) -->
        <div class="col-lg-8">
            <div class="profile-card p-4 h-100">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2 border-bottom">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;"><i class="fas fa-history"></i></div>
                        <h5 class="fw-bold m-0 text-dark">Management Activity Log</h5>
                    </div>
                    <button class="btn btn-sm btn-light border text-muted fw-bold rounded-pill px-3"><i class="fas fa-filter me-1"></i> Filter Logs</button>
                </div>

                <div class="activity-timeline mt-4">
                    <div class="timeline-item">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold text-dark m-0">Modified Course Structure</h6>
                            <span class="badge bg-light text-muted border px-2 py-1">Today, 10:45 AM</span>
                        </div>
                        <p class="text-muted small mb-0 mt-1">Updated curriculum modules for <strong class="text-dark">PHP Masters</strong> and injected 3 new video assets into the core syllabus.</p>
                    </div>

                    <div class="timeline-item">
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold text-dark m-0">Bulk Enrollment Execution</h6>
                            <span class="badge bg-light text-muted border px-2 py-1">Yesterday, 04:30 PM</span>
                        </div>
                        <p class="text-muted small mb-0 mt-1">Successfully mapped <strong class="text-dark">45 Students</strong> to the Advanced Node.js Database cohort via the talent hub mapper.</p>
                    </div>

                    <div class="timeline-item" style="--bs-border-color: #10b981;">
                        <style>.timeline-item:nth-child(3)::before { background: #10b981; }</style>
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold text-dark m-0">System Security Policy Update</h6>
                            <span class="badge bg-light text-muted border px-2 py-1">May 01, 2026</span>
                        </div>
                        <p class="text-muted small mb-0 mt-1">Granted <strong class="text-dark">HOD</strong> level access privileges to Prof. Dhaval Shah for the Computer Engineering department.</p>
                    </div>
                    
                    <div class="timeline-item" style="--bs-border-color: #f97316;">
                        <style>.timeline-item:nth-child(4)::before { background: #f97316; }</style>
                        <div class="d-flex justify-content-between align-items-start mb-1">
                            <h6 class="fw-bold text-dark m-0">Provision Store Clearance</h6>
                            <span class="badge bg-light text-muted border px-2 py-1">Apr 30, 2026</span>
                        </div>
                        <p class="text-muted small mb-0 mt-1">Verified incoming supplier delivery for <strong class="text-dark">Stationery (200 Units)</strong> into the Central Logistics Hub.</p>
                    </div>
                </div>
                
                <div class="text-center mt-4 pt-3">
                    <a href="#" class="text-decoration-none fw-bold" style="color: #6366f1;">View Complete System Audit Log <i class="fas fa-arrow-right ms-1"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
