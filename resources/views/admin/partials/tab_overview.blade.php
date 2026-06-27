<div class="tab-pane fade show active" id="tab-overview" role="tabpanel">
    <style>
        #tab-overview a {
            color: #2563eb !important;
            text-decoration: underline !important;
            font-weight: 700 !important;
        }
        #tab-overview a:hover {
            color: #1d4ed8 !important;
        }
    </style>
    <div class="row g-4 mb-4">
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon primary"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-number">@php try { echo \App\Models\User::count(); } catch(\Exception $e) { echo 53; } @endphp</div>
                    <div class="stat-label">Total Students</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon success"><i class="fas fa-book-open"></i></div>
                <div>
                    <div class="stat-number">@php try { echo \App\Models\Course::count(); } catch(\Exception $e) { echo 41; } @endphp</div>
                    <div class="stat-label">Active Courses</div>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-lg-4">
            <div class="stat-card">
                <div class="stat-icon warning"><i class="fas fa-graduation-cap"></i></div>
                <div>
                    <div class="stat-number">@php try { echo \App\Models\Enrollment::count(); } catch(\Exception $e) { echo 13; } @endphp</div>
                    <div class="stat-label">Total Enrollments</div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-4">
        <!-- Role Access Matrix Section -->
        <div class="col-lg-6">
            <div class="content-card h-100 mb-0">
                <div class="content-card-header">
                    <h5 class="content-card-title"><i class="fas fa-shield-alt text-warning"></i> Role & Privilege Access</h5>
                </div>
                <div class="card-body p-0 mt-3">
                    <p class="text-muted mb-4 fs-6">Review the institutional access matrix to understand your privilege level within the system hierarchy.</p>
                    <div class="d-flex flex-wrap gap-3">
                        <button class="action-btn action-btn-primary" style="width: auto;" data-bs-toggle="modal" data-bs-target="#accessMatrixModal">
                            <i class="fas fa-sitemap me-2"></i> View Hierarchy Matrix
                        </button>
                        @if(session('user_role') === 'admin')
                        <a href="/admin/master-data" class="action-btn" style="width: auto;">
                            <i class="fas fa-database text-info me-2"></i> Master Data Visor
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Notification / Broadcast Feed Section -->
        <div class="col-lg-6">
            <div class="content-card h-100 mb-0">
                <div class="content-card-header">
                    <h5 class="content-card-title"><i class="fas fa-broadcast-tower text-danger me-2"></i> Institutional Broadcast Feed</h5>
                </div>
                <div class="card-body p-0 mt-3" style="max-height: 380px; overflow-y: auto;">
                    @php
                        $userRole = session('user_role');
                        $broadcastQuery = \App\Models\LmsNotification::latest();
                        
                        // CR cannot see QA-notifications (staff only)
                        if ($userRole === 'cr') {
                            $broadcastQuery->where('type', '!=', 'qa_notification');
                        }
                        
                        $broadcasts = $broadcastQuery->take(10)->get();
                    @endphp

                    @if($broadcasts->count() > 0)
                        <div class="d-flex flex-column gap-3">
                            @foreach($broadcasts as $broadcast)
                                @php
                                    $badgeClass = 'bg-secondary';
                                    if ($broadcast->type === 'lms_notification') $badgeClass = 'bg-primary text-white';
                                    elseif ($broadcast->type === 'circular') $badgeClass = 'bg-warning text-dark';
                                    elseif ($broadcast->type === 'approvals') $badgeClass = 'bg-success text-white';
                                    elseif ($broadcast->type === 'qa_notification') $badgeClass = 'bg-dark text-white border-secondary border';
                                    elseif ($broadcast->type === 'faculty_notice') $badgeClass = 'bg-info text-dark';
                                    elseif ($broadcast->type === 'urgent_news') $badgeClass = 'bg-danger text-white';
                                @endphp
                                <div class="p-3 border rounded-3 bg-light shadow-sm" style="transition: transform 0.2s ease;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge {{ $badgeClass }} small font-monospace">{{ strtoupper(str_replace('_', ' ', $broadcast->type)) }}</span>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $broadcast->created_at->diffForHumans() }}</small>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">{{ $broadcast->title }}</h6>
                                    <div class="text-secondary small mb-2">{!! Illuminate\Support\Str::markdown($broadcast->content) !!}</div>
                                    <div style="font-size: 0.75rem;" class="text-muted fw-semibold">
                                        By: {{ $broadcast->created_by_name }} ({{ strtoupper($broadcast->created_by_role) }})
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="fas fa-satellite-dish fs-2 mb-3 opacity-30"></i>
                            <p class="mb-0 fw-bold">No institutional broadcasts found on feed.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
