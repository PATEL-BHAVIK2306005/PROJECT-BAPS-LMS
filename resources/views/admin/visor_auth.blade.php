@extends('layouts.app')
@section('content')
<div class="row justify-content-center mt-5">
    <div class="col-md-5 mt-5">
        <div class="glass-card p-4 text-center border-0 shadow-sm border-danger border-top border-4">
            <i class="fas fa-user-secret fa-3x text-danger mb-3"></i>
            <h4 class="fw-bold text-danger mb-1">Restricted Area</h4>
            <p class="text-muted small mb-4">God Mode Data Visor is highly protected. An authorization pin is required to proceed.</p>
            
            <form action="/admin/master-data/unlock" method="POST">
                @csrf
                <div class="mb-3">
                    <input type="password" name="visor_pin" class="form-control form-control-lg text-center" style="letter-spacing: 5px; font-weight: bold;" placeholder="••••••••" required autofocus>
                </div>
                <button type="submit" class="btn btn-danger w-100 fw-bold shadow-sm rounded-pill py-2"><i class="fas fa-unlock-alt me-1"></i> Authenticate & Unlock</button>
            </form>
        </div>
    </div>
</div>
@endsection
