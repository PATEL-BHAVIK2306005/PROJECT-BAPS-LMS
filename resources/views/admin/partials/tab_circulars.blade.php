<div class="tab-pane fade" id="tab-circulars" role="tabpanel">
    <div class="row g-4">
        <!-- Publish Section -->
        <div class="col-lg-5">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white p-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bullhorn text-warning me-2"></i> Publish Official Circular</h5>
                    <span class="badge bg-warning text-dark fw-bold">CIRCULAR ENGINE</span>
                </div>
                <div class="card-body p-4 bg-white">
                    <form action="/admin/circulars/store" method="POST" id="circularPublishForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Circular Category <span class="text-danger">*</span></label>
                            <select name="category" class="form-select @error('category') is-invalid @enderror" required id="circularCategorySelect">
                                <option value="" disabled selected>Select Category...</option>
                                <option value="academic">Academic Affairs</option>
                                <option value="exams">Exams & Evaluation</option>
                                <option value="administrative">Administrative & Office Works</option>
                                <option value="student_cr">Student & CR Announcements</option>
                                <option value="urgent">Urgent Circulars</option>
                            </select>
                            @error('category')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Circular Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. End Semester Exam Seating Arrangement" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Circular Content <span class="text-danger">*</span></label>
                            <textarea name="content" rows="6" class="form-control @error('content') is-invalid @enderror" placeholder="Enter official body text of circular..." required></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Signature Authorization Mode <span class="text-danger">*</span></label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="signature_type" id="sigMapped" value="mapped" checked onclick="toggleSignatureFields(false)">
                                    <label class="form-check-label fw-semibold text-dark" for="sigMapped">
                                        Mapped Signature (Auto)
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="signature_type" id="sigManual" value="manual" onclick="toggleSignatureFields(true)">
                                    <label class="form-check-label fw-semibold text-dark" for="sigManual">
                                        Manual Signature (Custom)
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Manual Signature Input Fields (Hidden by default) -->
                        <div id="manualSignatureInputs" style="display: none;" class="p-3 bg-light rounded-3 border mb-3">
                            <div class="mb-3">
                                <label class="form-label fw-bold text-secondary small">Signer Name <span class="text-danger">*</span></label>
                                <input type="text" name="manual_signature_name" id="manualNameInput" class="form-control" placeholder="e.g. Dr. Sadhu Gyaneswar Das">
                            </div>
                            <div>
                                <label class="form-label fw-bold text-secondary small">Signer Designation <span class="text-danger">*</span></label>
                                <input type="text" name="manual_signature_designation" id="manualDesignationInput" class="form-control" placeholder="e.g. Controller of Examinations">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-premium w-100 py-3 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 fw-bold text-white" style="background: var(--baps-saffron); border: none;">
                            <i class="fas fa-file-pdf"></i> Generate & Publish Circular
                        </button>
                    </form>
                </div>
            </div>

            <!-- Publish Notification Section -->
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white p-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-bell text-info me-2"></i> Post LMS Notification</h5>
                    <span class="badge bg-info text-dark fw-bold">BROADCAST</span>
                </div>
                <div class="card-body p-4 bg-white">
                    <form action="/admin/notifications/store" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Notification Type <span class="text-danger">*</span></label>
                            <select name="type" class="form-select @error('type') is-invalid @enderror" required id="notifTypeSelect">
                                <option value="" disabled selected>Select Broadcast Type...</option>
                                <option value="lms_notification">LMS Notification</option>
                                <option value="circular">Circular Reference</option>
                                <option value="approvals">Approval Update</option>
                                @if(session('user_role') !== 'cr')
                                <option value="qa_notification">QA-Notification (Staff Only)</option>
                                @endif
                                <option value="faculty_notice">Faculty Notice</option>
                                <option value="news">Campus News</option>
                                <option value="urgent_news">Urgent Announcement</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Notification Title <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" placeholder="e.g. Network Maintenance Schedule" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-secondary small">Brief Content Message <span class="text-danger">*</span></label>
                            <textarea name="content" rows="4" class="form-control @error('content') is-invalid @enderror" placeholder="Enter notification message text..." required></textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-3 rounded-3 shadow-sm d-flex align-items-center justify-content-center gap-2 fw-bold text-white border-0">
                            <i class="fas fa-paper-plane text-warning"></i> Broadcast Notification
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- History/List Section -->
        <div class="col-lg-7">
            <div class="card shadow-sm border-0 rounded-4 overflow-hidden mb-4">
                <div class="card-header bg-dark text-white p-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-history text-success me-2"></i> Circular History & PDF Archives</h5>
                    <span class="badge bg-success text-white fw-bold">{{ \App\Models\Circular::count() }} FILES</span>
                </div>
                <div class="card-body p-4 bg-light">
                    @php
                        $circularList = \App\Models\Circular::latest()->get();
                    @endphp
                    @if($circularList->count() > 0)
                        <div class="row g-3">
                            @foreach($circularList as $circ)
                                <div class="col-md-12">
                                    <div class="p-3 border rounded-3 bg-white shadow-sm d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center gap-3">
                                            <div class="bg-light p-3 rounded-3 text-center border" style="min-width: 60px;">
                                                <i class="fas fa-file-pdf text-danger fs-3"></i>
                                            </div>
                                            <div>
                                                <h6 class="fw-bold mb-1 text-dark">{{ $circ->title }}</h6>
                                                <div class="d-flex gap-2 align-items-center flex-wrap" style="font-size: 0.75rem;">
                                                    <span class="badge bg-secondary">{{ strtoupper($circ->category) }}</span>
                                                    <span class="text-muted"><i class="far fa-clock"></i> {{ $circ->created_at->format('d-M-Y h:i A') }} ({{ $circ->created_at->diffForHumans() }})</span>
                                                    <span class="text-muted fw-bold">By: {{ $circ->created_by_name }} ({{ strtoupper($circ->created_by_role) }})</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center gap-2">
                                            <button onclick="previewFile('/circulars/{{ $circ->id }}/view')" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                                <i class="fas fa-eye me-1"></i> View
                                            </button>
                                            <a href="/circulars/{{ $circ->id }}/download" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold shadow-sm">
                                                <i class="fas fa-download me-1"></i> Download PDF
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-file-pdf fs-1 mb-3 opacity-30"></i>
                            <p class="mb-0 fw-bold">No circulars have been generated or archived yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-dark text-white p-4 d-flex align-items-center justify-content-between">
                    <h5 class="mb-0 fw-bold"><i class="fas fa-envelope-open-text text-primary me-2"></i> Broadcast History</h5>
                    <span class="badge bg-primary text-white fw-bold">{{ \App\Models\LmsNotification::count() }} POSTS</span>
                </div>
                <div class="card-body p-4 bg-light">
                    @php
                        $notifList = \App\Models\LmsNotification::latest()->get();
                    @endphp
                    @if($notifList->count() > 0)
                        <div class="row g-3 tab-circulars-notif-feed" style="max-height: 400px; overflow-y: auto;">
                            <style>
                                .tab-circulars-notif-feed a {
                                    color: #2563eb !important;
                                    text-decoration: underline !important;
                                    font-weight: 700 !important;
                                }
                                .tab-circulars-notif-feed a:hover {
                                    color: #1d4ed8 !important;
                                }
                            </style>
                            @foreach($notifList as $notif)
                                <div class="col-md-12">
                                    <div class="p-3 border rounded-3 bg-white shadow-sm">
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25">{{ strtoupper($notif->type) }}</span>
                                            <small class="text-muted fw-semibold">{{ $notif->created_at->diffForHumans() }}</small>
                                        </div>
                                        <h6 class="fw-bold text-dark mb-1">{{ $notif->title }}</h6>
                                        <div class="text-secondary small mb-1">{!! Illuminate\Support\Str::markdown($notif->content) !!}</div>
                                        <small class="text-muted fw-bold">Broadcasted by: {{ $notif->created_by_name }} ({{ strtoupper($notif->created_by_role) }})</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-bell-slash fs-1 mb-3 opacity-30"></i>
                            <p class="mb-0 fw-bold">No broadcast notifications found in active history.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleSignatureFields(show) {
        const fieldBlock = document.getElementById('manualSignatureInputs');
        const nameInput = document.getElementById('manualNameInput');
        const designationInput = document.getElementById('manualDesignationInput');
        
        if (show) {
            fieldBlock.style.display = 'block';
            nameInput.setAttribute('required', 'true');
            designationInput.setAttribute('required', 'true');
        } else {
            fieldBlock.style.display = 'none';
            nameInput.removeAttribute('required');
            designationInput.removeAttribute('required');
        }
    }
</script>
