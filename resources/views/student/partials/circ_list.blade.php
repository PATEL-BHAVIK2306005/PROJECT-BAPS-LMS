@if($list->count() > 0)
    <div class="row g-3">
        @foreach($list as $item)
            <div class="col-md-12">
                <div class="p-3 border rounded-3 bg-white shadow-sm d-flex align-items-center justify-content-between hover-shadow transition">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-light p-3 rounded-3 text-center border" style="min-width: 55px;">
                            <i class="fas fa-file-pdf text-danger fs-4"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold mb-1 text-dark">{{ $item->title }}</h6>
                            <div class="d-flex gap-2 align-items-center flex-wrap" style="font-size: 0.75rem;">
                                <span class="badge bg-secondary">{{ strtoupper($item->category) }}</span>
                                <span class="text-muted"><i class="far fa-clock"></i> {{ $item->created_at->format('d-M-Y h:i A') }} ({{ $item->created_at->diffForHumans() }})</span>
                                <span class="text-muted fw-bold">By: {{ $item->created_by_name }} ({{ strtoupper($item->created_by_role) }})</span>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <button onclick="previewFile('/circulars/{{ $item->id }}/view')" class="btn btn-outline-primary btn-sm rounded-pill px-3 fw-bold shadow-sm">
                            <i class="fas fa-eye me-1"></i> View
                        </button>
                        <a href="/circulars/{{ $item->id }}/download" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold shadow-sm">
                            <i class="fas fa-download me-1"></i> Download PDF
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-4 text-muted">
        <i class="fas fa-file-excel fs-3 mb-2 opacity-40"></i>
        <p class="mb-0 small fw-bold">No circular files published under this category.</p>
    </div>
@endif
