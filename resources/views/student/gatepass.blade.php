@extends('layouts.app')
@section('content')

<div class="container py-4">
    <div class="row g-4">
        <!-- New Gatepass Form -->
        <div class="col-md-5">
            <div class="glass-card p-4 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width: 50px; height: 50px; background: var(--primary-gradient); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-ticket-alt fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">Apply for Gatepass</h4>
                        <p class="text-muted small mb-0">Submit a departure authorization request.</p>
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success border-0 rounded-3 shadow-sm bg-success text-white">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    </div>
                @endif
                @if($errors->any())
                    <div class="alert alert-danger border-0 rounded-3 bg-danger text-white">
                        <ul class="mb-0 ps-3">
                            @foreach($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                        </ul>
                    </div>
                @endif

                <form action="/gatepass" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Destination Address</label>
                        <input type="text" name="destination" class="form-control bg-light border-0" placeholder="Where are you going?" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Purpose / Reason</label>
                        <textarea name="reason" rows="3" class="form-control bg-light border-0" placeholder="Provide a valid reason for departure" required></textarea>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Out Time</label>
                            <input type="datetime-local" name="out_time" class="form-control bg-light border-0 text-muted" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">In Time</label>
                            <input type="datetime-local" name="in_time" class="form-control bg-light border-0 text-muted" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-premium w-100 rounded-pill shadow-sm py-2">Submit Application</button>
                </form>
            </div>
        </div>

        <!-- Request History -->
        <div class="col-md-7">
            <div class="glass-card p-4 border-0 shadow-sm h-100 d-flex flex-column">
                <h5 class="fw-bold mb-4"><i class="fas fa-history text-secondary me-2"></i> Authorization History</h5>
                
                @if($gatepasses->count() > 0)
                    <div class="table-responsive flex-grow-1">
                        <table class="table table-hover align-middle">
                            <thead class="table-light text-uppercase small text-muted">
                                <tr>
                                    <th>Date</th>
                                    <th>Destination</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($gatepasses as $gp)
                                <tr>
                                    <td><span class="fw-bold text-dark">{{ $gp->created_at->format('M d') }}</span><br><span class="x-small text-muted">{{ $gp->created_at->format('Y') }}</span></td>
                                    <td><span class="fw-semibold text-dark">{{ $gp->destination }}</span><br><span class="x-small text-muted text-truncate d-inline-block" style="max-width: 150px;">{{ $gp->reason }}</span></td>
                                    <td>
                                        <div class="x-small text-muted"><i class="fas fa-sign-out-alt text-danger me-1"></i> {{ \Carbon\Carbon::parse($gp->out_time)->format('h:i A') }}</div>
                                        <div class="x-small text-muted"><i class="fas fa-sign-in-alt text-success me-1"></i> {{ \Carbon\Carbon::parse($gp->in_time)->format('h:i A') }}</div>
                                    </td>
                                    <td>
                                        @if($gp->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i> Approved</span>
                                        @elseif($gp->status === 'rejected')
                                            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger px-3 py-2 rounded-pill"><i class="fas fa-times-circle me-1"></i> Rejected</span>
                                        @else
                                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning px-3 py-2 rounded-pill"><i class="fas fa-clock me-1"></i> Pending</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="d-flex flex-column align-items-center justify-content-center h-100 text-center opacity-50">
                        <i class="fas fa-file-invoice fa-3x text-muted mb-3"></i>
                        <h6 class="fw-bold text-secondary">No Gatepass Records</h6>
                        <p class="small text-muted">You haven't requested any authorizations yet.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
