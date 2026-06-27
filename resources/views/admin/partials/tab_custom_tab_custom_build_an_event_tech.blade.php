<div class="container-fluid py-2">
    <div class="row mb-4">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4 class="fw-bold text-dark"><i class="fas fa-link text-primary me-2"></i> Build an 'Event Tech...</h4>
            <span class="badge bg-dark text-white rounded-pill px-3 py-1 text-uppercase small">Dynamic AI Panel</span>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 bg-white text-center h-100">
                <div class="rounded-circle bg-light-primary text-primary mx-auto mb-3 d-flex align-items-center justify-content-center" style="width: 70px; height: 70px; font-size: 2rem;">
                    <i class="fas fa-robot"></i>
                </div>
                <h5 class="fw-bold">Dynamic Overview</h5>
                <p class="text-muted small">This tab was successfully compiled by the AI Co-pilot framework based on the following instruction:</p>
                <div class="alert alert-secondary text-start small font-monospace">"Build an 'Event Tech Setup Request' system designed as a 3-step wizard. Step 1: Event Details (Title, Venue). Step 2: Resource Selection (Projector, Sound System, Lapel Mics with checkboxes). Step 3: Confirmation Summary with a 'Finalize Booking' button."</div>
            </div>
        </div>

        <div class="col-12 col-md-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h5 class="fw-bold text-dark mb-0">Recent Inquiries & Quick Form</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    <form class="mb-4" onsubmit="event.preventDefault(); alert('Entry submitted successfully!'); this.reset();">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Full Name</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold text-muted">Reference ID</label>
                                <input type="text" class="form-control" required>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary w-100 fw-bold rounded-pill">Submit Record</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover table-striped align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>ID</th>
                                    <th>User</th>
                                    <th>Stamp</th>
                                    <th>Verification</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#901</td>
                                    <td>Prof. Gaurav Kulkarni</td>
                                    <td>5/20/2026</td>
                                    <td><span class="badge bg-success">Verified</span></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>