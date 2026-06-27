@extends('layouts.app')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 mt-5">
        <div class="glass-card p-4 text-center border-0 shadow-sm border-danger border-top border-4">
            <i class="fas fa-magic fa-3x text-danger mb-3"></i>
            <h4 class="fw-bold text-danger mb-1">Add Function Module</h4>
            <p class="text-muted small mb-4">Highly protected Admin-only module. Enter the required password.</p>
            
            <form action="/admin/add-function-module/unlock" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="password" name="password" class="form-control form-control-lg text-center" style="letter-spacing: 5px; font-weight: bold;" placeholder="••••••••" required autofocus>
                </div>
                <button type="submit" class="btn btn-danger w-100 fw-bold shadow-sm rounded-pill py-2"><i class="fas fa-unlock-alt me-1"></i> Authorize & Access</button>
            </form>
        </div>
    </div>
</div>
@endsection
