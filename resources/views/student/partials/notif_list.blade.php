@if($list->count() > 0)
    <style>
        .notif-card a {
            color: #2563eb !important;
            text-decoration: underline !important;
            font-weight: 700 !important;
        }
        .notif-card a:hover {
            color: #1d4ed8 !important;
        }
    </style>
    <div class="row g-3">
        @foreach($list as $item)
            <div class="col-md-12">
                <div class="p-3 border rounded-3 bg-white shadow-sm notif-card type-{{ $type }}">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <h6 class="fw-bold text-dark mb-0">{{ $item->title }}</h6>
                        <small class="text-muted fw-bold"><i class="far fa-clock"></i> {{ $item->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="text-secondary small mb-2">{!! Illuminate\Support\Str::markdown($item->content) !!}</div>
                    <div class="d-flex align-items-center gap-2" style="font-size: 0.75rem;">
                        <span class="text-muted fw-semibold">Published by: {{ $item->created_by_name }} ({{ strtoupper($item->created_by_role) }})</span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="text-center py-4 text-muted">
        <i class="fas fa-bell-slash fs-3 mb-2 opacity-40"></i>
        <p class="mb-0 small fw-bold">No announcements in this category.</p>
    </div>
@endif
