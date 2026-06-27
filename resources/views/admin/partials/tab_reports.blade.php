@if(in_array(session('user_role'), ['admin', 'dean', 'office-assistant']))
<div class="tab-pane fade" id="tab-reports" role="tabpanel">
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon success bg-success bg-opacity-10 text-success"><i class="fas fa-rupee-sign"></i></div>
                <div>
                    <div class="stat-number">₹ 4,52,500</div>
                    <div class="stat-label">Total Fee Collection</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon primary bg-primary bg-opacity-10 text-primary"><i class="fas fa-print"></i></div>
                <div>
                    <div class="stat-number">₹ 12,450</div>
                    <div class="stat-label">Zerox/Store Revenue</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon warning bg-warning bg-opacity-10 text-warning"><i class="fas fa-utensils"></i></div>
                <div>
                    <div class="stat-number">₹ 85,200</div>
                    <div class="stat-label">Canteen & Hostel Dues</div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-card mb-4" style="border-top: 4px solid var(--baps-green);">
        <div class="content-card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="content-card-title m-0"><i class="fas fa-file-invoice-dollar text-success me-2"></i> Financial Reporting: User Revenue Generated</h5>
            <button class="btn btn-sm btn-outline-success fw-bold px-4 py-2 rounded-pill shadow-sm"><i class="fas fa-file-csv me-2"></i> Export CSV</button>
        </div>
        <div class="table-responsive bg-white">
            <table class="table table-hover border align-middle mb-0">
                <thead class="table-light">
                    <tr><th class="text-secondary ps-3">USER ID</th><th>NAME</th><th>ROLE</th><th>SERVICE CATEGORY</th><th>PAYMENT MODE</th><th>TOTAL RUPEES (₹)</th><th>DATE</th></tr>
                </thead>
                <tbody>
                    <tr><td class="ps-3 fw-bold text-secondary">USR-8902</td><td class="fw-bold text-dark">Vasant Patel</td><td>Student</td><td>Hostel Fee (Q2)</td><td>UPI</td><td class="fw-bold text-success">₹ 15,000.00</td><td>Today, 10:30 AM</td></tr>
                    <tr><td class="ps-3 fw-bold text-secondary">USR-1145</td><td class="fw-bold text-dark">Smit Dave</td><td>Student</td><td>Zerox & Print</td><td>Wallet</td><td class="fw-bold text-success">₹ 145.00</td><td>Today, 09:15 AM</td></tr>
                    <tr><td class="ps-3 fw-bold text-secondary">STF-0012</td><td class="fw-bold text-dark">Prof. Dhaval Shah</td><td>Faculty</td><td>Canteen Pass</td><td>Salary Deduct</td><td class="fw-bold text-success">₹ 1,200.00</td><td>Yesterday</td></tr>
                    <tr><td class="ps-3 fw-bold text-secondary">USR-5532</td><td class="fw-bold text-dark">Priya Sharma</td><td>Student</td><td>Stationery Kit</td><td>Card</td><td class="fw-bold text-success">₹ 850.00</td><td>Yesterday</td></tr>
                    <tr><td class="ps-3 fw-bold text-secondary">USR-7721</td><td class="fw-bold text-dark">Rahul Verma</td><td>Student</td><td>Library Fine</td><td>Cash</td><td class="fw-bold text-success">₹ 50.00</td><td>May 01, 2026</td></tr>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="text-center mt-4">
        <a href="/admin/reports" class="action-btn py-3 px-5 shadow-sm" style="width: auto;"><i class="fas fa-external-link-alt me-2 fs-5"></i> Access Advanced Analytics Engine</a>
    </div>
</div>
@endif
