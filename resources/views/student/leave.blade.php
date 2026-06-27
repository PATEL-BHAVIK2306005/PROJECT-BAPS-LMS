@extends('layouts.app')
@section('content')

<div class="container py-4">
    <div class="row g-4">
        <!-- New Leave Form -->
        <div class="col-md-5">
            <div class="glass-card p-4 border-0 shadow-sm h-100">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, #ef4444, #b91c1c); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-calendar-minus fa-lg"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0">Apply for Leave</h4>
                        <p class="text-muted small mb-0">Submit a formal absence authorization.</p>
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

                <form action="/leave" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold text-muted">Leave Type</label>
                        <select name="leave_type" class="form-select bg-light border-0" required>
                            <option value="">Select Category</option>
                            <option value="Medical Leave">Medical Leave</option>
                            <option value="Personal Emergency">Personal Emergency</option>
                            <option value="Family Event">Family Event</option>
                            <option value="Academic Visit">Academic Visit / Event</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">Start Date</label>
                            <input type="date" name="start_date" class="form-control bg-light border-0 text-muted" required>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold text-muted">End Date</label>
                            <input type="date" name="end_date" class="form-control bg-light border-0 text-muted" required>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <label class="form-label small fw-bold text-muted">Detailed Reason</label>
                        <textarea name="reason" rows="4" class="form-control bg-light border-0" placeholder="Provide a detailed explanation for your absence to the HOD/Dean." required></textarea>
                    </div>

                    <button type="submit" class="btn text-white w-100 rounded-pill shadow-sm py-2" style="background: linear-gradient(135deg, #ef4444, #b91c1c);">Submit Leave Request</button>
                </form>
            </div>
        </div>

        <!-- Request History -->
        <div class="col-md-7">
            <div class="glass-card p-4 border-0 shadow-sm h-100 d-flex flex-column">
                <h5 class="fw-bold mb-4"><i class="fas fa-history text-secondary me-2"></i> Leave Authorizations</h5>
                
                @if($leaves->count() > 0)
                    <div class="table-responsive flex-grow-1">
                        <table class="table table-hover border-top align-middle">
                            <thead class="table-light text-uppercase small text-muted">
                                <tr>
                                    <th>Submission</th>
                                    <th>Leave Period</th>
                                    <th>Category & Reason</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($leaves as $lv)
                                <tr>
                                    <td><span class="fw-bold text-dark">{{ $lv->created_at->format('M d') }}</span><br><span class="x-small text-muted">{{ $lv->created_at->format('Y') }}</span></td>
                                    <td>
                                        <div class="fw-semibold text-primary"><i class="fas fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($lv->start_date)->format('d M') }}</div>
                                        <div class="x-small text-muted text-center fw-bold">TO</div>
                                        <div class="fw-semibold text-primary"><i class="fas fa-calendar-check me-1"></i> {{ \Carbon\Carbon::parse($lv->end_date)->format('d M') }}</div>
                                    </td>
                                    <td><span class="fw-bold text-dark">{{ $lv->leave_type }}</span><br><span class="x-small text-muted text-truncate d-inline-block" style="max-width: 150px;">{{ $lv->reason }}</span></td>
                                    <td>
                                        @if($lv->status === 'approved')
                                            <span class="badge bg-success bg-opacity-10 text-success border border-success px-3 py-2 rounded-pill"><i class="fas fa-check-circle me-1"></i> Approved</span>
                                        @elseif($lv->status === 'rejected')
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
                        <i class="fas fa-bed fa-3x text-muted mb-3"></i>
                        <h6 class="fw-bold text-secondary">No Formal Leave Records</h6>
                        <p class="small text-muted">You have a clean attendance record.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
/* Adjust blade layout height dynamically */
html, body {
    height: 100%;
}
</style>
@endsection
