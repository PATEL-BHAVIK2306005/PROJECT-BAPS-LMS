@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fas fa-hands-helping text-danger me-2"></i> Seva Log Portal</h3>
        <p class="text-muted small mb-0">Review and approve student volunteer hours for IPDC credits.</p>
    </div>
    <a href="/admin/ipdc" class="btn btn-outline-dark btn-sm fw-bold rounded-pill px-3"><i class="fas fa-arrow-left me-1"></i> Back to IPDC</a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-5 text-center">
        <i class="fas fa-tools fa-4x text-muted mb-4 opacity-25"></i>
        <h4 class="fw-bold">Log Portal Integration Active</h4>
        <p class="text-muted">The Seva Log Portal is currently synchronizing with the institutional database.<br>Pending approvals will appear here shortly.</p>
        <button class="btn btn-primary rounded-pill px-4 fw-bold mt-3" onclick="location.reload()">Refresh Sync</button>
    </div>
</div>

@endsection
