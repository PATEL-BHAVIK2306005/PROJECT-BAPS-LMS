@extends('layouts.app')
@section('content')

<div class="row justify-content-center">
    <div class="col-md-4">
        <div class="card mt-5 shadow-sm">
            <div class="card-body text-center">
                <h4 class="mb-4">Admin Access</h4>
                <form method="POST" action="/admin/login">
                    @csrf
                    <input type="password" name="password" class="form-control mb-3 text-center" placeholder="Enter Access Code" required autofocus>
                    @if(session('error'))
                        <div class="text-danger small mb-3">{{ session('error') }}</div>
                    @endif
                    <button class="btn btn-dark w-100">Unlock Panel</button>
                </form>
                <div class="mt-4 small text-muted">
                    Contact system owner if you've forgotten your code.
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
