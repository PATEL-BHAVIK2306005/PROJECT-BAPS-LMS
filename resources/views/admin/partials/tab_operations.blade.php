@if(in_array(session('user_role'), ['admin', 'cr', 'coordinator', 'faculty-lecturer-coordinator']) || session('staff_name') == 'Rajunakum Sir')
<div class="tab-pane fade" id="tab-operations" role="tabpanel">
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4"><a href="/admin/bulk-enroll" class="action-btn py-4 shadow-sm"><i class="fas fa-users-cog text-primary me-2 fs-4"></i> Bulk Enrollment</a></div>
        <div class="col-12 col-md-4"><a href="/admin/talent-hub" class="action-btn py-4 shadow-sm"><i class="fas fa-briefcase text-warning me-2 fs-4"></i> Campus Talent Hub</a></div>
        <div class="col-12 col-md-4"><a href="/admin/assignments" class="action-btn py-4 shadow-sm"><i class="fas fa-clipboard-list text-info me-2 fs-4"></i> Assignment Grading</a></div>
    </div>

    <div class="content-card mb-4" style="border-top: 4px solid var(--baps-red);">
        <div class="content-card-header"><h5 class="content-card-title"><i class="fas fa-thumbtack text-danger"></i> Assign Custom Task</h5></div>
        <form method="POST" action="/admin/task">
            @csrf
            <select name="course_id" class="form-select mb-3" required>
                <option value="">-- Select Course --</option>
                @foreach($courses as $c)
                <option value="{{ $c->id }}">{{ $c->title }}</option>
                @endforeach
            </select>
            <input name="title" class="form-control mb-3" placeholder="Task Title" required>
            <textarea name="description" class="form-control mb-3" placeholder="Instructions..." rows="3"></textarea>
            <input name="due_date" type="date" class="form-control mb-4">
            <button class="action-btn action-btn-primary"><i class="fas fa-paper-plane me-2"></i> Push Task to Students</button>
        </form>
    </div>

    <!-- Campus Services Hub Section -->
    <div class="content-card mb-4" style="border-top: 4px solid var(--baps-gold);">
        <div class="content-card-header mb-4">
            <h5 class="content-card-title"><i class="fas fa-store text-warning"></i> Campus Services Hub</h5>
        </div>
        
        <ul class="nav nav-pills mb-4 gap-2 flex-nowrap overflow-auto border-0 pb-2" id="campusServicesTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="baps-tab-btn active border shadow-sm" data-bs-toggle="pill" data-bs-target="#serv-canteen" type="button" role="tab"><i class="fas fa-hamburger text-warning me-2"></i> Canteen</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="baps-tab-btn border shadow-sm" data-bs-toggle="pill" data-bs-target="#serv-store" type="button" role="tab"><i class="fas fa-shopping-basket text-success me-2"></i> Provision Store</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="baps-tab-btn border shadow-sm" data-bs-toggle="pill" data-bs-target="#serv-zerox" type="button" role="tab"><i class="fas fa-print text-primary me-2"></i> Zerox Center</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="baps-tab-btn border shadow-sm" data-bs-toggle="pill" data-bs-target="#serv-stationery" type="button" role="tab"><i class="fas fa-pencil-ruler text-info me-2"></i> Stationery</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="baps-tab-btn border shadow-sm" data-bs-toggle="pill" data-bs-target="#serv-laundry" type="button" role="tab"><i class="fas fa-tshirt text-secondary me-2"></i> Laundry</button>
            </li>
        </ul>

        <div class="tab-content bg-light p-4 rounded-4 shadow-sm border" id="campusServicesTabsContent">
            <div class="tab-pane fade show active" id="serv-canteen" role="tabpanel">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-4">
                        <div class="stat-card py-3">
                            <div class="stat-icon primary"><i class="fas fa-list"></i></div>
                            <h6 class="fw-bold mb-0">Daily Menu Items</h6>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="stat-card py-3">
                            <div class="stat-icon success"><i class="fas fa-receipt"></i></div>
                            <h6 class="fw-bold mb-0">Process Orders</h6>
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <div class="stat-card py-3">
                            <div class="stat-icon warning"><i class="fas fa-boxes"></i></div>
                            <h6 class="fw-bold mb-0">Inventory Stock</h6>
                        </div>
                    </div>
                </div>
                
                <h6 class="fw-bold mb-3"><i class="fas fa-utensils text-primary me-2"></i> Live Canteen Order's Queue</h6>
                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light">
                            <tr><th>Order ID</th><th>Student/Staff Name</th><th>Items Ordered</th><th>Payment Mode</th><th>Billed (₹)</th><th>Status</th><th>Action</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-secondary">#ORD-8821</td><td class="fw-bold text-dark">Smit Dave</td><td>2x Samosa, 1x Tea</td><td>Wallet</td>
                                <td class="fw-bold text-success">₹ 45.00</td>
                                <td><span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Preparing</span></td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-info text-white me-1 shadow-sm" title="View Slip"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-success shadow-sm" title="Mark Ready"><i class="fas fa-check"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <td class="fw-bold text-secondary">#ORD-8822</td><td class="fw-bold text-dark">Prof. Dhaval Shah</td><td>Faculty Thali</td><td>Salary Deduct</td>
                                <td class="fw-bold text-success">₹ 120.00</td>
                                <td><span class="badge bg-danger px-3 py-2 rounded-pill">Pending Payment</span></td>
                                <td class="text-nowrap">
                                    <button class="btn btn-sm btn-info text-white me-1 shadow-sm" title="View Slip"><i class="fas fa-eye"></i></button>
                                    <button class="btn btn-sm btn-primary shadow-sm" title="Process Payment"><i class="fas fa-play"></i></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="serv-store" role="tabpanel">
                <div class="row g-4 mb-4">
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="stat-card py-3 px-2 text-center d-flex flex-column align-items-center justify-content-center" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#posCollapse">
                            <div class="stat-icon primary mb-2"><i class="fas fa-desktop"></i></div>
                            <h6 class="fw-bold mb-0 small">Point of Sale</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="stat-card py-3 px-2 text-center d-flex flex-column align-items-center justify-content-center" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#requestsCollapse">
                            <div class="stat-icon warning mb-2"><i class="fas fa-clipboard-list"></i></div>
                            <h6 class="fw-bold mb-0 small">Student Req</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="stat-card py-3 px-2 text-center d-flex flex-column align-items-center justify-content-center" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#deliveriesCollapse">
                            <div class="stat-icon success mb-2"><i class="fas fa-truck-loading"></i></div>
                            <h6 class="fw-bold mb-0 small">Deliveries</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="stat-card py-3 px-2 text-center d-flex flex-column align-items-center justify-content-center" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#addItemsCollapse">
                            <div class="stat-icon purple mb-2"><i class="fas fa-plus"></i></div>
                            <h6 class="fw-bold mb-0 small">Add Items</h6>
                        </div>
                    </div>
                    <div class="col-6 col-md-4 col-lg-2">
                        <div class="stat-card py-3 px-2 text-center d-flex flex-column align-items-center justify-content-center" style="cursor: pointer;" data-bs-toggle="collapse" data-bs-target="#manageItemsCollapse">
                            <div class="stat-icon danger mb-2"><i class="fas fa-boxes"></i></div>
                            <h6 class="fw-bold mb-0 small">Manage Items</h6>
                        </div>
                    </div>
                </div>

                <div id="storePanels">
                    <!-- POS Collapse -->
                    <div class="collapse mb-4" id="posCollapse" data-bs-parent="#storePanels">
                        <div class="content-card bg-white" style="border-top: 4px solid #0dcaf0;">
                            <div class="content-card-header mb-3"><h5 class="content-card-title"><i class="fas fa-desktop text-info"></i> Point of Sale Terminal</h5></div>
                            <div class="row g-4">
                                <div class="col-12 col-lg-8">
                                    <div class="input-group mb-3 shadow-sm rounded-3 overflow-hidden">
                                        <span class="input-group-text bg-white border-end-0"><i class="fas fa-barcode text-muted"></i></span>
                                        <input type="text" class="form-control border-start-0 ps-0 py-2" placeholder="Scan Barcode or Search Item...">
                                        <button class="btn btn-info text-white fw-bold px-4 py-2">Search</button>
                                    </div>
                                    <div class="p-5 border rounded-4 text-center bg-light shadow-sm">
                                        <i class="fas fa-shopping-cart fa-3x mb-3 text-secondary" style="opacity: 0.4;"></i>
                                        <h6 class="fw-bold text-dark fs-5">Cart is empty</h6>
                                        <p class="small text-muted mb-0">Scan an item to begin the transaction</p>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4">
                                    <div class="card border shadow-sm h-100 rounded-4 overflow-hidden">
                                        <div class="card-body bg-white p-4 d-flex flex-column">
                                            <h6 class="fw-bold border-bottom pb-3 mb-3 fs-5">Order Summary</h6>
                                            <div class="d-flex justify-content-between mb-2 small text-muted fw-bold"><span>Subtotal</span><span>₹ 0.00</span></div>
                                            <div class="d-flex justify-content-between mb-2 small text-danger fw-bold"><span>Discount</span><span>- ₹ 0.00</span></div>
                                            <hr class="my-3">
                                            <div class="d-flex justify-content-between mb-4 fw-bold fs-4 text-dark"><span>Total</span><span>₹ 0.00</span></div>
                                            <div class="mt-auto">
                                                <button class="btn btn-info text-white w-100 fw-bold py-3 rounded-pill shadow-sm"><i class="fas fa-credit-card me-2"></i> Process Payment</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Deliveries Collapse -->
                    <div class="collapse mb-4" id="deliveriesCollapse" data-bs-parent="#storePanels">
                        <div class="content-card bg-white" style="border-top: 4px solid #6c757d;">
                            <div class="content-card-header mb-3 d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <h5 class="content-card-title m-0"><i class="fas fa-truck text-secondary"></i> Supplier Deliveries Log</h5>
                                <button class="btn btn-sm btn-secondary fw-bold px-3 py-2 rounded-pill shadow-sm" onclick="alert('Opening New Delivery Form...')"><i class="fas fa-plus me-1"></i> New Delivery</button>
                            </div>
                            <div class="table-responsive border rounded bg-white">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr><th class="ps-3">Delivery ID</th><th>Supplier Name</th><th>Date & Time</th><th>Items Count</th><th>Status</th><th class="text-center">Action</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="ps-3 fw-bold text-secondary">#DEL-4021</td><td class="fw-bold text-dark">A1 Stationers</td>
                                            <td><span class="text-muted small"><i class="far fa-clock me-1"></i>Today, 10:30 AM</span></td>
                                            <td><span class="badge bg-light text-dark border px-3 py-2 rounded-pill">450 Units</span></td>
                                            <td><span id="status-del-4021" class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-spinner fa-spin me-1"></i> Checking</span></td>
                                            <td class="text-center text-nowrap"><button class="btn btn-sm btn-success shadow-sm" title="Verify Stock" onclick="verifyDelivery('4021', this)"><i class="fas fa-check-double"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Student Requests Collapse -->
                    <div class="collapse mb-4 show" id="requestsCollapse" data-bs-parent="#storePanels">
                        <div class="content-card bg-white" style="border-top: 4px solid #ffc107;">
                            <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
                                <h6 class="fw-bold mb-0 text-dark fs-5"><i class="fas fa-shopping-basket text-success me-2"></i> Store Fulfillment Queue</h6>
                                <span class="badge bg-warning text-dark border border-warning px-3 py-2 rounded-pill shadow-sm" id="queue-count">1 Pending</span>
                            </div>
                            <div class="table-responsive border rounded bg-white">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="table-light">
                                        <tr><th class="ps-3">Req ID</th><th>Student Name</th><th>Requested Items</th><th>Hostel Room</th><th>Cost (₹)</th><th>Status</th><th class="text-center">Action</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr id="row-str-901">
                                            <td class="ps-3 fw-bold text-secondary">#STR-901</td><td class="fw-bold text-dark">Priya Sharma</td><td>Engineering Drawing Board</td>
                                            <td><span class="badge bg-light text-dark border px-3 py-2 rounded-pill"><i class="fas fa-door-closed text-muted me-1"></i> A-204</span></td>
                                            <td style="width: 140px;"><input type="number" class="form-control form-control-sm border-success bg-success bg-opacity-10 fw-bold text-success py-2" placeholder="Enter ₹" id="cost-str-901"></td>
                                            <td><span id="status-str-901" class="badge bg-warning text-dark px-3 py-2 rounded-pill"><i class="fas fa-box me-1"></i> Packing</span></td>
                                            <td class="text-center text-nowrap"><button class="btn btn-sm btn-success shadow-sm" title="Dispatch to Room" onclick="dispatchOrder('901', this)"><i class="fas fa-truck"></i></button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <script>
                                function verifyDelivery(id, btn) {
                                    let statusBadge = document.getElementById('status-del-' + id);
                                    if(statusBadge) { statusBadge.className = 'badge bg-success px-3 py-2 rounded-pill'; statusBadge.innerHTML = '<i class="fas fa-check me-1"></i> Received'; }
                                    btn.className = 'btn btn-sm btn-outline-secondary'; btn.title = 'View Invoice'; btn.innerHTML = '<i class="fas fa-file-invoice"></i>'; btn.onclick = function() { alert('Viewing Invoice #DEL-' + id); };
                                }
                                function dispatchOrder(id, btn) {
                                    let costInput = document.getElementById('cost-str-' + id);
                                    if(costInput && costInput.value === '') { alert('Please enter the Cost (₹) before dispatching!'); costInput.focus(); return; }
                                    let statusBadge = document.getElementById('status-str-' + id);
                                    if(statusBadge) { statusBadge.className = 'badge bg-primary px-3 py-2 rounded-pill'; statusBadge.innerHTML = '<i class="fas fa-truck-fast me-1"></i> Dispatched'; }
                                    btn.className = 'btn btn-sm btn-outline-secondary'; btn.title = 'View Receipt'; btn.innerHTML = '<i class="fas fa-receipt"></i>'; btn.onclick = function() { alert('Viewing Receipt for #STR-' + id); };
                                    let badgeCount = document.getElementById('queue-count'); if(badgeCount) { badgeCount.className = 'badge bg-success text-white px-3 py-2 rounded-pill shadow-sm'; badgeCount.innerText = '0 Pending'; }
                                }
                            </script>
                        </div>
                    </div>

                    <!-- Manage Items Collapse -->
                    <div class="collapse mb-4" id="manageItemsCollapse" data-bs-parent="#storePanels">
                        <div class="content-card bg-white" style="border-top: 4px solid var(--baps-saffron);">
                            <div class="content-card-header mb-4"><h5 class="content-card-title"><i class="fas fa-sitemap text-primary"></i> Items Management Categories</h5></div>
                            <div class="row g-4">
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="stat-card py-4" style="border-left: 5px solid #f97316 !important;">
                                        <div>
                                            <h6 class="fw-bold mb-1 fs-5"><i class="fas fa-hamburger me-2 text-warning"></i> 1) Food & Snacks</h6>
                                            <p class="small text-muted mb-0">Manage daily food items and snack inventory.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="stat-card py-4" style="border-left: 5px solid #3b82f6 !important;">
                                        <div>
                                            <h6 class="fw-bold mb-1 fs-5"><i class="fas fa-pencil-ruler me-2 text-primary"></i> 2) Stationery</h6>
                                            <p class="small text-muted mb-0">Academic and office stationery supplies.</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-sm-6 col-lg-4">
                                    <div class="stat-card py-4" style="border-left: 5px solid #10b981 !important;">
                                        <div>
                                            <h6 class="fw-bold mb-1 fs-5"><i class="fas fa-receipt me-2 text-success"></i> 3) Orders</h6>
                                            <p class="small text-muted mb-0">Track and manage store fulfillment orders.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Add Items Collapse -->
                    <div class="collapse mb-4" id="addItemsCollapse" data-bs-parent="#storePanels">
                        <div class="content-card bg-white" style="border-top: 4px solid #10b981;">
                            <div class="content-card-header mb-4"><h5 class="content-card-title"><i class="fas fa-plus-circle text-success"></i> Add New Store Item</h5></div>
                            <form>
                                <div class="row g-4">
                                    <div class="col-12 col-md-6"><label class="form-label small fw-bold text-muted">Item Name</label><input type="text" class="form-control py-2" placeholder="E.g. Engineering Drawing Board"></div>
                                    <div class="col-12 col-md-6"><label class="form-label small fw-bold text-muted">Category</label><select class="form-select py-2"><option>1) Food & Snacks</option><option>2) Stationery</option><option>3) General Items</option></select></div>
                                    <div class="col-12 col-md-4"><label class="form-label small fw-bold text-muted">Unit Price (₹)</label><input type="number" class="form-control py-2" placeholder="0.00"></div>
                                    <div class="col-12 col-md-4"><label class="form-label small fw-bold text-muted">Initial Stock</label><input type="number" class="form-control py-2" placeholder="100"></div>
                                    <div class="col-12 col-md-4"><label class="form-label small fw-bold text-muted">SKU / Barcode</label><input type="text" class="form-control py-2" placeholder="Scan or Enter SKU"></div>
                                    <div class="col-12 mt-4 text-end"><button type="button" class="btn btn-success px-5 py-3 fw-bold rounded-pill shadow-sm" onclick="alert('Item Added Successfully')"><i class="fas fa-check me-2"></i> Save Item into Inventory</button></div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-pane fade" id="serv-zerox" role="tabpanel">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-4"><div class="stat-card py-3"><div class="stat-icon danger"><i class="fas fa-file-pdf"></i></div><h6 class="fw-bold mb-0">Pending Print Jobs</h6></div></div>
                    <div class="col-12 col-md-4"><div class="stat-card py-3"><div class="stat-icon primary"><i class="fas fa-tint"></i></div><h6 class="fw-bold mb-0">Ink & Paper Supplies</h6></div></div>
                    <div class="col-12 col-md-4"><div class="stat-card py-3"><div class="stat-icon warning"><i class="fas fa-tools"></i></div><h6 class="fw-bold mb-0">Machine Maintenance</h6></div></div>
                </div>
                <h6 class="fw-bold mb-3"><i class="fas fa-tasks text-primary me-2"></i> Live Zerox Task's Queue</h6>
                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light"><tr><th>Job ID</th><th>Student/Staff Name</th><th>Document Type</th><th>Pages</th><th>Billed Amount (₹)</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody>
                            <tr>
                                <td class="fw-bold text-secondary">#ZRX-1001</td><td class="fw-bold text-dark">Vasant Patel</td><td>A4 B&W (Double Sided)</td><td>45</td>
                                <td><input type="number" class="form-control form-control-sm border-success py-2" placeholder="Enter ₹" style="width: 120px;"></td>
                                <td><span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Printing</span></td>
                                <td class="text-nowrap"><button class="btn btn-sm btn-info text-white me-1 shadow-sm" onclick="previewFile('/dummy.pdf')" title="Preview"><i class="fas fa-eye"></i></button><a href="/dummy.pdf" download class="btn btn-sm btn-secondary me-1 shadow-sm" title="Download"><i class="fas fa-download"></i></a><button class="btn btn-sm btn-success shadow-sm" title="Finalize" onclick="finalizeZeroxTask(this)"><i class="fas fa-check"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="serv-stationery" role="tabpanel">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-6"><div class="stat-card py-3"><div class="stat-icon primary"><i class="fas fa-box"></i></div><h6 class="fw-bold mb-0">Manage Kits</h6></div></div>
                    <div class="col-12 col-md-6"><div class="stat-card py-3"><div class="stat-icon success"><i class="fas fa-barcode"></i></div><h6 class="fw-bold mb-0">Scan Barcode</h6></div></div>
                </div>
                <h6 class="fw-bold mb-3"><i class="fas fa-pencil-ruler text-info me-2"></i> Term-Start Kit Distribution</h6>
                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light"><tr><th>Token ID</th><th>Student Name</th><th>Branch & Year</th><th>Kit Type</th><th>Eligibility</th><th>Action</th></tr></thead>
                        <tbody><tr><td class="fw-bold text-secondary">#TKN-100</td><td class="fw-bold text-dark">Rahul Verma</td><td>Computer Eng. (1st Year)</td><td>Standard Kit</td><td><span class="badge bg-success px-3 py-2 rounded-pill">Fees Paid</span></td><td class="text-nowrap"><button class="btn btn-sm btn-primary px-3 py-2 rounded-pill shadow-sm">Scan & Issue</button></td></tr></tbody>
                    </table>
                </div>
            </div>

            <div class="tab-pane fade" id="serv-laundry" role="tabpanel">
                <div class="row g-4 mb-4">
                    <div class="col-12 col-md-6"><div class="stat-card py-3"><div class="stat-icon warning"><i class="fas fa-tag"></i></div><h6 class="fw-bold mb-0">Collect Clothes</h6></div></div>
                    <div class="col-12 col-md-6"><div class="stat-card py-3"><div class="stat-icon primary"><i class="fas fa-check-circle"></i></div><h6 class="fw-bold mb-0">Mark Ready</h6></div></div>
                </div>
                <h6 class="fw-bold mb-3"><i class="fas fa-tshirt text-secondary me-2"></i> Live Laundry Tracking Log</h6>
                <div class="table-responsive bg-white">
                    <table class="table table-hover border align-middle mb-0">
                        <thead class="table-light"><tr><th>Bag ID</th><th>Student Name</th><th>Hostel Room</th><th>Weight (KG)</th><th>Pieces</th><th>Status</th><th>Action</th></tr></thead>
                        <tbody><tr><td class="fw-bold text-secondary">#BAG-5012</td><td class="fw-bold text-dark">Vasant Patel</td><td>C-102</td><td>2.5 KG</td><td>14</td><td><span class="badge bg-warning text-dark px-3 py-2 rounded-pill">Washing</span></td><td class="text-nowrap"><button class="btn btn-sm btn-success px-3 py-2 rounded-pill shadow-sm">Mark Ironing</button></td></tr></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
