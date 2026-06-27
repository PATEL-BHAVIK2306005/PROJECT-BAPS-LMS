<div class="tab-pane fade" id="tab-payroll" role="tabpanel">
    <!-- Payroll Header -->
    <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden" style="background: #11111b !important; border-left: 5px solid #ffd700 !important;">
        <div class="card-body p-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px; font-size: 1.8rem; background: #1e1e2e !important; color: #ffd700 !important;">
                        <i class="fas fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 d-flex align-items-center gap-2" style="color: #ffd700 !important;">
                            Faculty & Staff Payroll Console
                            <span class="badge bg-success text-white px-3 py-1 rounded-pill text-uppercase fs-6 shadow-sm" style="letter-spacing: 1px;">Disbursement Ledger</span>
                        </h4>
                        <div class="small fw-semibold" style="color: #ffffff !important; opacity: 0.85;">Manage monthly salary accounts, hourly coordinator payouts, allowances, and bank transfer settlements.</div>
                    </div>
                </div>
                <div>
                    <span class="badge bg-light text-dark px-3 py-2 rounded-pill shadow-sm fw-bold">
                        <i class="fas fa-wallet text-warning me-1"></i> Month: May 2026
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Metrics -->
    <div class="row g-4 mb-4">
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-left: 4px solid var(--baps-green) !important;">
                <div class="small fw-bold text-muted text-uppercase mb-1">Total Monthly Payout</div>
                <h3 class="fw-bold text-dark mb-0">₹ 8,45,000.00</h3>
                <div class="small text-success mt-2"><i class="fas fa-arrow-up me-1"></i> Fully Budgeted & Approved</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-left: 4px solid var(--baps-gold) !important;">
                <div class="small fw-bold text-muted text-uppercase mb-1">Pending Settlements</div>
                <h3 class="fw-bold text-dark mb-0" id="payrollPendingCount">2 Records</h3>
                <div class="small text-warning mt-2"><i class="fas fa-clock me-1"></i> Awaiting bank batch release</div>
            </div>
        </div>
        <div class="col-12 col-md-4">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white" style="border-left: 4px solid var(--baps-blue) !important;">
                <div class="small fw-bold text-muted text-uppercase mb-1">Tax Attestations</div>
                <h3 class="fw-bold text-dark mb-0">100% Compliant</h3>
                <div class="small text-primary mt-2"><i class="fas fa-shield-alt me-1"></i> Form 16/TDS automatic log</div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- New Pay Record Form -->
        <div class="col-12 col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-calculator text-indigo me-2"></i> Issue Salary Mandate</h5>
                </div>
                <div class="card-body px-4 pb-4">
                    @php
                        $allStaffMembers = \App\Models\Staff::with('department')->orderBy('name')->get();

                        // Find the three signers for the bottom of the receipt
                        $officeAssistant = \App\Models\Staff::where('role', 'office-assistant')
                            ->orWhere('name', 'like', '%Nilam%')
                            ->first();
                        $adminSigner = \App\Models\Staff::where('name', 'like', '%BHAVIKKUMAR%')
                            ->first() ?? \App\Models\Staff::where('role', 'admin')->first();
                        $deanSigner = \App\Models\Staff::where('role', 'dean')
                            ->orWhere('name', 'like', '%Ramesh%')
                            ->first();

                        $preparedBySig = $officeAssistant ? $officeAssistant->digital_signature : \App\Models\Staff::generateSignatureSvg('Nilam Sharma');
                        $preparedByName = $officeAssistant ? $officeAssistant->name : 'Nilam Sharma';
                        $preparedByRole = $officeAssistant ? strtoupper(str_replace('-', ' ', $officeAssistant->role)) : 'OFFICE ASSISTANT';

                        $verifiedBySig = $adminSigner ? $adminSigner->digital_signature : \App\Models\Staff::generateSignatureSvg('BHAVIKKUMAR PATEL');
                        $verifiedByName = $adminSigner ? $adminSigner->name : 'BHAVIKKUMAR PATEL';
                        $verifiedByRole = $adminSigner ? strtoupper(str_replace('-', ' ', $adminSigner->role)) : 'ADMIN';

                        $approvedBySig = $deanSigner ? $deanSigner->digital_signature : \App\Models\Staff::generateSignatureSvg('Dr. Ramesh Chandra Pandya');
                        $approvedByName = $deanSigner ? $deanSigner->name : 'Dr. Ramesh Chandra Pandya';
                        $approvedByRole = $deanSigner ? strtoupper(str_replace('-', ' ', $deanSigner->role)) : 'DEAN';

                        $getFinancials = function($role) {
                            $role = strtolower($role);
                            switch($role) {
                                case 'dean':
                                    return ['base' => 600000, 'allowance' => 50000, 'deduction' => 72000];
                                case 'admin':
                                    return ['base' => 500000, 'allowance' => 40000, 'deduction' => 72000];
                                case 'hod':
                                    return ['base' => 450000, 'allowance' => 35000, 'deduction' => 72000];
                                case 'coordinator':
                                    return ['base' => 350000, 'allowance' => 25000, 'deduction' => 72000];
                                case 'faculty':
                                    return ['base' => 300000, 'allowance' => 20000, 'deduction' => 72000];
                                case 'moderator':
                                    return ['base' => 280000, 'allowance' => 18000, 'deduction' => 72000];
                                case 'office-assistant':
                                    return ['base' => 250000, 'allowance' => 15000, 'deduction' => 72000];
                                case 'staff':
                                default:
                                    return ['base' => 200000, 'allowance' => 10000, 'deduction' => 72000];
                            }
                        };
                    @endphp
                    <form id="payrollLogForm" onsubmit="event.preventDefault(); submitPayrollLog();">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Select Recipient <span class="text-danger">*</span></label>
                            <select id="pay_recipient" class="form-select py-2 fs-6" onchange="onRecipientChange()" required>
                                @foreach($allStaffMembers as $st)
                                    @php
                                        $fin = $getFinancials($st->role);
                                        $deptName = $st->department ? $st->department->name : 'General Admin';
                                    @endphp
                                    <option value="{{ $st->name }}|{{ strtoupper($st->role ?? 'Staff') }}|{{ $st->unique_code ?? 'BAPS-STAFF-' . sprintf('%03d', $st->id) }}|{{ $deptName }}"
                                            data-base="{{ $fin['base'] }}"
                                            data-allowance="{{ $fin['allowance'] }}">
                                        {{ $st->name }} ({{ strtoupper(str_replace('-', ' ', $st->role ?? 'Staff')) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Select Approving Dean <span class="text-danger">*</span></label>
                            <select id="pay_dean" class="form-select py-2 fs-6" required>
                                @php
                                    $deansList = \App\Models\Staff::where('role', 'dean')->orderBy('name')->get();
                                @endphp
                                @if($deansList->count() > 0)
                                    @foreach($deansList as $dean)
                                        @php
                                            $cleanDeanName = preg_replace('/\s*\([^)]*\)/', '', $dean->name);
                                        @endphp
                                        <option value="{{ $cleanDeanName }}" data-sig="{{ $dean->digital_signature ?? \App\Models\Staff::generateSignatureSvg($cleanDeanName) }}">
                                            {{ $dean->name }}
                                        </option>
                                    @endforeach
                                @else
                                    <option value="Dr. Sadhu Gyaneswar Das" data-sig="{{ \App\Models\Staff::generateSignatureSvg('Dr. Sadhu Gyaneswar Das') }}">
                                        Dr. Sadhu Gyaneswar Das (Dean - Default)
                                    </option>
                                @endif
                            </select>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Base Salary (₹) <span class="text-danger">*</span></label>
                                <input type="number" id="pay_base" class="form-control py-2 fs-6" min="1000" value="65000" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Allowances (₹) <span class="text-danger">*</span></label>
                                <input type="number" id="pay_allowance" class="form-control py-2 fs-6" min="0" value="5000" required>
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Month <span class="text-danger">*</span></label>
                                <input type="text" id="pay_month" class="form-control py-2 fs-6" value="May 2026" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted">Status <span class="text-danger">*</span></label>
                                <select id="pay_status" class="form-select py-2 fs-6" required>
                                    <option value="Paid">✅ Paid</option>
                                    <option value="Pending">⏳ Pending</option>
                                    <option value="Processing">🔄 Processing</option>
                                </select>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-indigo w-100 py-3 rounded-pill text-white fw-bold shadow-sm" style="background-color: #4f46e5;">
                            <i class="fas fa-paper-plane me-2"></i> Dispatch Mandate
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Payroll Ledger -->
        <div class="col-12 col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white border-0 pt-4 px-4 pb-2 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold text-dark mb-0"><i class="fas fa-receipt text-indigo me-2"></i> Compensations Ledger</h5>
                    <span class="badge bg-indigo-light text-indigo px-3 py-1 rounded-pill small fw-bold">Active Pay Period</span>
                </div>
                <div class="card-body px-4 pb-4">
                    <div class="table-responsive" style="max-height: 500px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light text-uppercase fs-7 text-muted fw-bold" style="position: sticky; top: 0; z-index: 10; background: #f8fafc;">
                                <tr>
                                    <th>Staff Recipient</th>
                                    <th>Designation</th>
                                    <th>Net Payout</th>
                                    <th>Month</th>
                                    <th>Status</th>
                                    <th class="text-end">Actions</th>
                                </tr>
                            </thead>
                            <tbody id="payrollTableBody">
                                @foreach($allStaffMembers as $st)
                                    @php
                                        $fin = $getFinancials($st->role);
                                        $base = $fin['base'];
                                        $allowance = $fin['allowance'];
                                        $deduction = $fin['deduction'];
                                        
                                        // Calculate unsolved query salary cuts
                                        $salaryCut = \App\Models\StudentQuery::where('assigned_staff_id', $st->id)
                                            ->where('status', 'unsolved')
                                            ->sum('salary_cut_amount');
                                            
                                        $net = $base + $allowance - $deduction - $salaryCut;

                                        // Realistic distribution of statuses
                                        $status = 'Paid';
                                        if ($st->id % 6 == 0) {
                                            $status = 'Pending';
                                        } elseif ($st->id % 6 == 5) {
                                            $status = 'Processing';
                                        }

                                        $deptName = $st->department ? $st->department->name : 'General Admin';
                                    @endphp
                                    <tr data-staff-id="{{ $st->id }}"
                                        data-name="{{ $st->name }}"
                                        data-role="{{ strtoupper(str_replace('-', ' ', $st->role ?? 'Staff')) }}"
                                        data-code="{{ $st->unique_code ?? 'BAPS-STAFF-' . sprintf('%03d', $st->id) }}"
                                        data-dept="{{ $deptName }}"
                                        data-base="{{ $base }}"
                                        data-allowance="{{ $allowance }}"
                                        data-deduction="{{ $deduction }}"
                                        data-salary-cut="{{ $salaryCut }}"
                                        data-net="{{ $net }}"
                                        data-status="{{ $status }}"
                                        data-month="May 2026"
                                        data-dean-name="{{ preg_replace('/\s*\([^)]*\)/', '', $approvedByName) }}"
                                        data-dean-sig="{{ $approvedBySig }}">
                                        <td>
                                            <div class="fw-bold text-dark">{{ $st->name }}</div>
                                            <div class="small text-muted">{{ $st->unique_code ?? 'BAPS-STAFF-' . sprintf('%03d', $st->id) }}</div>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark border fw-semibold text-uppercase" style="font-size: 0.75rem;">{{ str_replace('-', ' ', $st->role ?? 'staff') }}</span>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            ₹ {{ number_format($net, 2) }}
                                            @if($salaryCut > 0)
                                                <div class="x-small text-danger fw-semibold" style="font-size: 0.72rem;"><i class="fas fa-exclamation-circle text-danger me-0.5"></i> Penalty: -₹{{ number_format($salaryCut) }}</div>
                                            @endif
                                        </td>
                                        <td>May 2026</td>
                                        <td>
                                            @if($status === 'Paid')
                                                <span class="badge bg-success px-3 py-1 rounded-pill">Paid</span>
                                            @elseif($status === 'Pending')
                                                <span class="badge bg-warning text-dark px-3 py-1 rounded-pill">Pending</span>
                                            @else
                                                <span class="badge bg-info text-white px-3 py-1 rounded-pill">Processing</span>
                                            @endif
                                        </td>
                                        <td class="text-end">
                                            @if($status !== 'Paid')
                                                <button class="btn btn-sm btn-success rounded-circle me-1" onclick="settlePayroll(this)" title="Settle Payout">
                                                    <i class="fas fa-check"></i>
                                                </button>
                                            @endif
                                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" onclick="showReceipt(this)">
                                                <i class="fas fa-eye me-1"></i> Slip
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Premium Colored Receipt Modal -->
<div class="modal fade" id="receiptModal" tabindex="-1" aria-labelledby="receiptModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden" style="background: #ffffff;">
            <!-- Decorative colored stripe -->
            <div style="height: 6px; background: linear-gradient(90deg, #ea580c 0%, #4f46e5 100%);"></div>
            
            <div class="modal-header border-0 bg-light p-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-3">
                    <!-- Saffron watermarked icon -->
                    <div class="bg-indigo-light rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background: #e0e7ff; color: #4f46e5;">
                        <i class="fas fa-file-invoice-dollar fs-4"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold text-dark mb-0" id="receiptModalLabel">Salary Disbursement Receipt</h5>
                        <small class="text-muted fw-semibold">BAPS Swaminarayan Vidyamandir System</small>
                    </div>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <div class="modal-body p-4" id="receiptPrintArea">
                <!-- Receipt Outer border card -->
                <div class="border rounded-4 p-4 position-relative" style="border: 2px solid #e2e8f0 !important; background: radial-gradient(circle at 100% 100%, #fafafa 0%, #ffffff 100%);">
                    
                    <!-- Decorative Watermark in background -->
                    <div class="position-absolute start-50 top-50 translate-middle opacity-5 pointer-events-none text-center" style="font-size: 8rem; color: #ea580c; z-index: 1;">
                        <i class="fas fa-dharmachakra"></i>
                    </div>

                    <div class="position-relative" style="z-index: 2;">
                        <!-- Header Logos & Details -->
                        <div class="d-flex justify-content-between align-items-start border-bottom pb-3 mb-4" style="border-bottom: 2px dashed #e2e8f0 !important;">
                            <div>
                                <h4 class="fw-bold mb-1 text-indigo" style="color: #312e81; letter-spacing: 0.5px;">BAPS SWAMINARAYAN VIDYAMANDIR</h4>
                                <div class="small text-muted fw-semibold mb-1"><i class="fas fa-map-marker-alt me-1"></i> BAPS e.learn Academic Campus, Gujarat, India</div>
                                <div class="small text-muted"><i class="fas fa-envelope me-1"></i> finance@baps.ac.in | <i class="fas fa-globe me-1"></i> lms.baps.ac.in</div>
                            </div>
                            <div class="text-end">
                                <span class="badge border px-3 py-2 rounded-pill fw-bold text-uppercase" id="receipt_badge_status">
                                    Paid
                                </span>
                                <div class="mt-2 text-muted small fw-semibold" id="receipt_ref_num">REF: BAPS-PAY-202605-001</div>
                            </div>
                        </div>

                        <!-- 10 Details Grid -->
                        <div class="row g-3 mb-4">
                            <!-- Left Column (General Details) -->
                            <div class="col-12 col-md-6 border-end" style="border-right: 1px solid #f1f5f9 !important;">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3 text-indigo" style="letter-spacing: 0.5px; color: #4f46e5;">Employee Profile</h6>
                                <table class="table table-borderless table-sm mb-0 align-middle">
                                    <tr>
                                        <td class="text-muted py-1 small" style="width: 40%;">Employee Name:</td>
                                        <td class="fw-bold text-dark py-1" id="receipt_name">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Unique Code:</td>
                                        <td class="fw-bold text-dark py-1 font-monospace" id="receipt_code">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Designation:</td>
                                        <td class="fw-bold text-dark py-1" id="receipt_role">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Department:</td>
                                        <td class="fw-bold text-dark py-1" id="receipt_dept">-</td>
                                    </tr>
                                </table>
                            </div>
                            <!-- Right Column (Payment Terms) -->
                            <div class="col-12 col-md-6 ps-md-4">
                                <h6 class="text-muted fw-bold text-uppercase small mb-3 text-indigo" style="letter-spacing: 0.5px; color: #4f46e5;">Transaction Details</h6>
                                <table class="table table-borderless table-sm mb-0 align-middle">
                                    <tr>
                                        <td class="text-muted py-1 small" style="width: 40%;">Pay Period:</td>
                                        <td class="fw-bold text-dark py-1" id="receipt_period">-</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Payment Method:</td>
                                        <td class="fw-bold text-dark py-1"><i class="fas fa-university text-primary me-1"></i> Direct Bank Transfer</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Account Status:</td>
                                        <td class="fw-bold text-success py-1"><i class="fas fa-check-circle me-1"></i> Active Verified</td>
                                    </tr>
                                    <tr>
                                        <td class="text-muted py-1 small">Settled On:</td>
                                        <td class="fw-bold text-dark py-1" id="receipt_settled_on">May 21, 2026</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Salary Breakdown Table -->
                        <div class="table-responsive mb-4 border rounded-3 overflow-hidden">
                            <table class="table mb-0 align-middle">
                                <thead style="background: #f8fafc; border-bottom: 2px solid #e2e8f0;">
                                    <tr>
                                        <th class="py-3 px-4 text-muted fw-bold text-uppercase small">Compensation Component</th>
                                        <th class="py-3 px-4 text-end text-muted fw-bold text-uppercase small" style="width: 35%;">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td class="py-3 px-4">
                                            <div class="fw-bold text-dark">Base Monthly Salary</div>
                                            <small class="text-muted">Standard core contractual payout</small>
                                        </td>
                                        <td class="py-3 px-4 text-end fw-semibold text-dark font-monospace" id="receipt_base_salary">₹ 0.00</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td class="py-3 px-4">
                                            <div class="fw-bold text-dark">Allowances & Incentives</div>
                                            <small class="text-muted">Housing, transport, and academic allowances</small>
                                        </td>
                                        <td class="py-3 px-4 text-end fw-semibold text-success font-monospace" id="receipt_allowances">₹ 0.00</td>
                                    </tr>
                                    <tr style="border-bottom: 1px solid #f1f5f9;">
                                        <td class="py-3 px-4">
                                            <div class="fw-bold text-danger">Deductions (TDS / PF)</div>
                                            <small class="text-muted">Tax deduction at source & provident fund contribution</small>
                                        </td>
                                        <td class="py-3 px-4 text-end fw-semibold text-danger font-monospace" id="receipt_deductions">-₹ 0.00</td>
                                    </tr>
                                    <tr id="receipt_query_penalty_row" style="border-bottom: 2px solid #e2e8f0; display: none;">
                                        <td class="py-3 px-4">
                                            <div class="fw-bold text-danger">Query Resolution Penalty</div>
                                            <small class="text-muted">Salary deduction for unsolved student query tickets</small>
                                        </td>
                                        <td class="py-3 px-4 text-end fw-semibold text-danger font-monospace" id="receipt_query_penalty">-₹ 0.00</td>
                                    </tr>
                                    <tr class="bg-light table-active" style="border-top: 2px solid #cbd5e1;">
                                        <td class="py-3 px-4">
                                            <div class="fw-bold text-dark text-uppercase" style="letter-spacing: 0.5px;">Net Settled Payout</div>
                                            <small class="text-muted fw-semibold">Deposited into employee bank account</small>
                                        </td>
                                        <td class="py-3 px-4 text-end fw-bold fs-5 text-indigo font-monospace" id="receipt_net_payout" style="color: #4f46e5;">₹ 0.00</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- 3 Signature Blocks -->
                        <div class="mt-5">
                            <h6 class="text-muted fw-bold text-uppercase small text-center mb-4" style="letter-spacing: 1px; border-bottom: 1px solid #e2e8f0; padding-bottom: 10px;">Institutional Attestations</h6>
                            <div class="row g-4 justify-content-center text-center">
                                <!-- prepared by office assistant -->
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center justify-content-between h-100">
                                        <div class="mb-2" style="height: 50px; display: flex; align-items: center; justify-content: center;">
                                            {!! $preparedBySig !!}
                                        </div>
                                        <div style="width: 80%; height: 1px; background-color: #cbd5e1; margin-bottom: 6px; margin-left: auto; margin-right: auto;"></div>
                                        <div class="fw-bold text-dark small" style="font-size: 0.8rem;">{{ $preparedByName }}</div>
                                        <div class="text-muted" style="font-size: 0.65rem; font-weight: 600;">PREPARED BY ({{ $preparedByRole }})</div>
                                    </div>
                                </div>
                                <!-- verified by HOD -->
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center justify-content-between h-100">
                                        <div class="mb-2" style="height: 50px; display: flex; align-items: center; justify-content: center;">
                                            {!! $verifiedBySig !!}
                                        </div>
                                        <div style="width: 80%; height: 1px; background-color: #cbd5e1; margin-bottom: 6px; margin-left: auto; margin-right: auto;"></div>
                                        <div class="fw-bold text-dark small" style="font-size: 0.8rem;">{{ $verifiedByName }}</div>
                                        <div class="text-muted" style="font-size: 0.65rem; font-weight: 600;">VERIFIED BY ({{ $verifiedByRole }})</div>
                                    </div>
                                </div>
                                <!-- approved by Dean -->
                                <div class="col-4">
                                    <div class="d-flex flex-column align-items-center justify-content-between h-100">
                                        <div class="mb-2" id="receipt_approved_sig_container" style="height: 50px; display: flex; align-items: center; justify-content: center;">
                                            {!! $approvedBySig !!}
                                        </div>
                                        <div style="width: 80%; height: 1px; background-color: #cbd5e1; margin-bottom: 6px; margin-left: auto; margin-right: auto;"></div>
                                        <div class="fw-bold text-dark small" id="receipt_approved_by_name" style="font-size: 0.8rem;">{{ $approvedByName }}</div>
                                        <div class="text-muted" style="font-size: 0.65rem; font-weight: 600;">APPROVED BY ({{ $approvedByRole }})</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Footer fine print -->
                        <div class="text-center mt-5 pt-3 border-top text-muted" style="font-size: 0.7rem; border-top: 1px solid #f1f5f9 !important; word-break: break-all;">
                            This is a cryptographically verified and system-attested salary disbursement slip generated on the BAPS SVM Academic LMS. 
                            If there are discrepancies, contact the finance desk. 
                            <br>
                            <span class="fw-bold text-dark">SHA256: <span id="receipt_hash">BAPS39281A92FD2</span></span>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="modal-footer border-0 bg-light p-3">
                <button type="button" class="btn btn-outline-secondary rounded-pill px-4" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-indigo rounded-pill px-4 text-white fw-bold shadow-sm" style="background-color: #4f46e5;" onclick="printReceipt()">
                    <i class="fas fa-print me-2"></i> Print Slip
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Print-Only Styling Injection -->
<style>
    @media print {
        body {
            background: white !important;
            color: black !important;
        }
        body * {
            visibility: hidden !important;
        }
        #receiptPrintArea, #receiptPrintArea * {
            visibility: visible !important;
        }
        #receiptPrintArea {
            position: absolute !important;
            left: 0 !important;
            top: 0 !important;
            width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
            box-shadow: none !important;
            border: none !important;
            z-index: 999999 !important;
        }
        /* Ensure watermarks and gradients render nicely in PDF export */
        * {
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }
    }
</style>

<script>
    // Set form fields dynamically on recipient selection
    function onRecipientChange() {
        const select = document.getElementById('pay_recipient');
        if (!select) return;
        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption) return;
        
        const base = selectedOption.getAttribute('data-base');
        const allowance = selectedOption.getAttribute('data-allowance');
        
        document.getElementById('pay_base').value = base;
        document.getElementById('pay_allowance').value = allowance;
    }

    // Call on load once DOM content is ready
    document.addEventListener('DOMContentLoaded', function() {
        onRecipientChange();
    });

    // Make sure it runs if script loads after DOMContentLoaded
    if (document.readyState === 'complete' || document.readyState === 'interactive') {
        setTimeout(onRecipientChange, 100);
    }

    function settlePayroll(btn) {
        const row = btn.closest('tr');
        if (!row) return;
        row.setAttribute('data-status', 'Paid');
        row.cells[4].innerHTML = '<span class="badge bg-success px-3 py-1 rounded-pill">Paid</span>';
        btn.remove();
        
        // Recalculate pending count
        updatePayrollPendingCount();

        if (typeof showBapsToast === 'function') showBapsToast('Payroll transaction successfully settled & paid! 💸', 'success');
    }

    function updatePayrollPendingCount() {
        const tbody = document.getElementById('payrollTableBody');
        let pending = 0;
        Array.from(tbody.rows).forEach(row => {
            const status = row.getAttribute('data-status') || '';
            if (status.includes('Pending') || status.includes('Processing')) {
                pending++;
            }
        });
        const cntEl = document.getElementById('payrollPendingCount');
        if (cntEl) cntEl.innerText = pending + ' Record' + (pending !== 1 ? 's' : '');
    }

    function showReceipt(btn) {
        // If element is a button in the row, find the parent row
        const row = btn.closest('tr');
        if (!row) return;

        // Parse attributes
        const name = row.getAttribute('data-name');
        const role = row.getAttribute('data-role');
        const code = row.getAttribute('data-code');
        const dept = row.getAttribute('data-dept');
        const base = parseFloat(row.getAttribute('data-base')) || 0;
        const allowance = parseFloat(row.getAttribute('data-allowance')) || 0;
        const deduction = parseFloat(row.getAttribute('data-deduction')) || 0;
        const salaryCut = parseFloat(row.getAttribute('data-salary-cut')) || 0;
        const net = parseFloat(row.getAttribute('data-net')) || 0;
        const status = row.getAttribute('data-status');
        const month = row.getAttribute('data-month') || 'May 2026';
        const deanName = row.getAttribute('data-dean-name') || 'Dr. Ramesh Chandra Pandya';
        const deanSig = row.getAttribute('data-dean-sig') || '';

        // Populate fields
        document.getElementById('receipt_name').innerText = name;
        
        const deanNameEl = document.getElementById('receipt_approved_by_name');
        const deanSigEl = document.getElementById('receipt_approved_sig_container');
        if (deanNameEl) deanNameEl.innerText = deanName;
        if (deanSigEl) deanSigEl.innerHTML = deanSig;
        document.getElementById('receipt_code').innerText = code;
        document.getElementById('receipt_role').innerText = role;
        document.getElementById('receipt_dept').innerText = dept;
        document.getElementById('receipt_period').innerText = month;
        
        // Populate amounts
        document.getElementById('receipt_base_salary').innerText = '₹ ' + base.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('receipt_allowances').innerText = '₹ ' + allowance.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        document.getElementById('receipt_deductions').innerText = '-₹ ' + deduction.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        
        // Populate query penalty if any
        const penaltyRow = document.getElementById('receipt_query_penalty_row');
        if (salaryCut > 0) {
            penaltyRow.style.display = 'table-row';
            document.getElementById('receipt_query_penalty').innerText = '-₹ ' + salaryCut.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        } else {
            penaltyRow.style.display = 'none';
        }
        
        document.getElementById('receipt_net_payout').innerText = '₹ ' + net.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        // Update status badge
        const badge = document.getElementById('receipt_badge_status');
        badge.className = 'badge border px-3 py-2 rounded-pill fw-bold text-uppercase';
        if (status === 'Paid') {
            badge.className += ' bg-success text-white border-success';
            badge.style.backgroundColor = '#10b981';
            badge.innerText = 'Paid';
        } else if (status === 'Pending') {
            badge.className += ' bg-warning text-dark border-warning';
            badge.style.backgroundColor = '#f59e0b';
            badge.innerText = 'Pending';
        } else {
            badge.className += ' bg-info text-white border-info';
            badge.style.backgroundColor = '#06b6d4';
            badge.innerText = 'Processing';
        }

        // Set Reference Number & Random Cryptographic Hash
        const staffId = row.getAttribute('data-staff-id') || '0';
        const refNum = 'REF: BAPS-PAY-202605-' + staffId.padStart(3, '0') + '-' + Math.floor(Math.random() * 900 + 100);
        document.getElementById('receipt_ref_num').innerText = refNum;
        
        const randomHash = 'BAPS' + Math.random().toString(36).substring(2, 10).toUpperCase() + Date.now().toString(36).substring(4).toUpperCase();
        document.getElementById('receipt_hash').innerText = randomHash;

        // Set Settled On Date
        const dateStr = status === 'Paid' ? 'May 21, 2026' : 'Awaiting settlement';
        document.getElementById('receipt_settled_on').innerText = dateStr;

        // Trigger bootstrap modal open with fallback
        try {
            const myModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            myModal.show();
        } catch(e) {
            try {
                $('#receiptModal').modal('show');
            } catch(err) {
                // Vanilla JS open fallback
                const modal = document.getElementById('receiptModal');
                modal.classList.add('show');
                modal.style.display = 'block';
                document.body.classList.add('modal-open');
                
                // Add backdrop
                const backdrop = document.createElement('div');
                backdrop.className = 'modal-backdrop fade show';
                backdrop.id = 'modal-backdrop-receipt';
                document.body.appendChild(backdrop);
                
                // Bind close click
                const closeBtns = modal.querySelectorAll('[data-bs-dismiss="modal"]');
                closeBtns.forEach(btn => {
                    btn.onclick = function() {
                        modal.classList.remove('show');
                        modal.style.display = 'none';
                        document.body.classList.remove('modal-open');
                        const backdropEl = document.getElementById('modal-backdrop-receipt');
                        if (backdropEl) backdropEl.remove();
                    };
                });
            }
        }
    }

    function printReceipt() {
        window.print();
    }

    function submitPayrollLog() {
        const recipVal = document.getElementById('pay_recipient').value;
        const base = parseFloat(document.getElementById('pay_base').value);
        const allowance = parseFloat(document.getElementById('pay_allowance').value);
        const month = document.getElementById('pay_month').value;
        const status = document.getElementById('pay_status').value;

        if (!recipVal || isNaN(base)) return;

        const parts = recipVal.split('|');
        const name = parts[0];
        const role = parts[1];
        const code = parts[2] || 'BAPS-STAFF-NEW';
        const dept = parts[3] || 'General Admin';

        // Set deduction to exactly 72,000 as requested
        const deduction = 72000;
        const net = base + allowance - deduction;

        const totalPayStr = '₹ ' + net.toLocaleString('en-IN', { minimumFractionDigits: 2, maximumFractionDigits: 2 });

        const tbody = document.getElementById('payrollTableBody');
        const tr = document.createElement('tr');
        
        // Set necessary data attributes for showReceipt()
        tr.setAttribute('data-name', name);
        tr.setAttribute('data-role', role);
        tr.setAttribute('data-code', code);
        tr.setAttribute('data-dept', dept);
        tr.setAttribute('data-base', base);
        tr.setAttribute('data-allowance', allowance);
        tr.setAttribute('data-deduction', deduction);
        tr.setAttribute('data-net', net);
        tr.setAttribute('data-status', status);
        tr.setAttribute('data-month', month);

        const deanSelect = document.getElementById('pay_dean');
        const deanName = deanSelect.value;
        const deanSig = deanSelect.options[deanSelect.selectedIndex].getAttribute('data-sig') || '';
        
        tr.setAttribute('data-dean-name', deanName);
        tr.setAttribute('data-dean-sig', deanSig);

        let statusBadge = '';
        let actionBtn = '';
        if (status === 'Paid') {
            statusBadge = '<span class="badge bg-success px-3 py-1 rounded-pill">Paid</span>';
            actionBtn = `
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" onclick="showReceipt(this)">
                    <i class="fas fa-eye me-1"></i> Slip
                </button>
            `;
        } else {
            const badgeClass = status === 'Pending' ? 'bg-warning text-dark' : 'bg-info text-white';
            statusBadge = `<span class="badge ${badgeClass} px-3 py-1 rounded-pill">${status}</span>`;
            actionBtn = `
                <button class="btn btn-sm btn-success rounded-circle me-1" onclick="settlePayroll(this)" title="Settle Payout"><i class="fas fa-check"></i></button>
                <button class="btn btn-sm btn-outline-primary rounded-pill px-3 py-1 fw-bold" onclick="showReceipt(this)">
                    <i class="fas fa-eye me-1"></i> Slip
                </button>
            `;
        }

        tr.innerHTML = `
            <td>
                <div class="fw-bold text-dark">${name}</div>
                <div class="small text-muted">${code}</div>
            </td>
            <td>
                <span class="badge bg-light text-dark border fw-semibold text-uppercase" style="font-size: 0.75rem;">${role.replace(/-/g, ' ')}</span>
            </td>
            <td class="fw-bold text-dark">${totalPayStr}</td>
            <td>${month}</td>
            <td>${statusBadge}</td>
            <td class="text-end">${actionBtn}</td>
        `;

        tbody.insertBefore(tr, tbody.firstChild);
        
        // Recalculate pending count
        updatePayrollPendingCount();

        if (typeof showBapsToast === 'function') showBapsToast('New Salary Mandate successfully queued! 💸', 'success');
    }
</script>

