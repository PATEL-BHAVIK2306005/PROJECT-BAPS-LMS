@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3><i class="fas fa-file-alt text-primary me-2"></i> {{ $title }} - Under Construction</h3>
    <a href="/admin" class="btn btn-outline-dark btn-sm"><i class="fas fa-arrow-left me-1"></i> Back to Dashboard</a>
</div>

<div class="card p-5 border-0 bg-white shadow-sm text-center">
    <i class="fas fa-hammer text-warning mb-3" style="font-size: 3rem;"></i>
    <h4 class="fw-bold">Module In Progress</h4>
    <p class="text-muted">The {{ $title }} module is currently being developed and will be available soon.</p>
</div>

@endsection
