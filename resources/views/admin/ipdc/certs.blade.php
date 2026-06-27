@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fas fa-history text-info me-2"></i> Global Certification History</h3>
        <p class="text-muted small mb-0">Complete record of all student-submitted external credentials.</p>
    </div>
    <a href="/admin/ipdc" class="btn btn-outline-dark btn-sm fw-bold rounded-pill px-3"><i class="fas fa-arrow-left me-1"></i> Back to IPDC</a>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">Student</th>
                        <th>Platform</th>
                        <th>Certificate Title</th>
                        <th>Date Submitted</th>
                        <th>Status</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($certs as $cert)
                    <tr>
                        <td class="ps-4">
                            <div class="d-flex align-items-center">
                                <div class="avatar-circle-sm me-2 bg-primary text-white">{{ strtoupper(substr($cert->user->name, 0, 2)) }}</div>
                                <div>
                                    <div class="fw-bold small">{{ $cert->user->name }}</div>
                                    <small class="text-muted" style="font-size: 0.7rem;">#{{ $cert->user->enrollment_no }}</small>
                                </div>
                            </div>
                        </td>
                        <td><span class="badge bg-dark rounded-pill px-3">{{ $cert->platform }}</span></td>
                        <td class="small fw-bold text-dark">{{ $cert->title }}</td>
                        <td class="text-muted small">{{ $cert->created_at->format('M d, Y') }}</td>
                        <td>
                            @if($cert->verification_status == 'pending')
                                <span class="badge bg-soft-warning text-warning rounded-pill px-3">Pending</span>
                            @elseif($cert->verification_status == 'verified')
                                <span class="badge bg-soft-success text-success rounded-pill px-3">Verified</span>
                            @else
                                <span class="badge bg-soft-danger text-danger rounded-pill px-3">Rejected</span>
                            @endif
                        </td>
                        <td class="text-end pe-4">
                            @php $hasFile = ($cert->file_content || $cert->file_path); @endphp
                            <button class="btn btn-sm btn-outline-primary rounded-pill px-3 fw-bold" onclick="previewCert({{ $cert->id }}, '{{ $hasFile ? url('/cloud-file/cert/' . $cert->id) : $cert->credential_link }}', '{{ $hasFile ? 'pdf' : 'link' }}', true)">
                                <i class="fas fa-eye me-1"></i> View Cert
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="fas fa-folder-open fa-3x mb-3 opacity-25"></i>
                            <p>No certifications recorded in the history vault.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Preview (Reuse logic from index) -->
<div class="modal fade" id="previewCertModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg overflow-hidden" style="height: 90vh;">
            <div class="row h-100 g-0">
                <div class="col-md-8 bg-light d-flex align-items-center justify-content-center p-0">
                    <iframe id="certIframe" src="" class="w-100 h-100 border-0" title="Credential Preview"></iframe>
                    <div id="certLinkPreview" class="text-center d-none p-5">
                        <i class="fas fa-external-link-alt fa-4x text-primary mb-3"></i>
                        <h4 class="fw-bold">External Credential Link</h4>
                        <a href="" id="certBtnLink" target="_blank" class="btn btn-primary rounded-pill px-4 fw-bold mt-2">Open Link</a>
                    </div>
                </div>
                <div class="col-md-4 bg-white d-flex flex-column border-start">
                    <div class="p-4 flex-grow-1">
                        <h5 class="fw-bold mb-4">Certification Details</h5>
                        <div id="certDetails" class="small">
                            <!-- Dynamic content -->
                        </div>
                    </div>
                    <div class="p-3 bg-light border-top text-center">
                        <button class="btn btn-sm text-muted" data-bs-dismiss="modal">Close Preview</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function previewCert(id, source, type, isViewOnly = false) {
        const iframe = document.getElementById('certIframe');
        const linkPreview = document.getElementById('certLinkPreview');
        const btnLink = document.getElementById('certBtnLink');
        const workflowSection = document.querySelector('#previewCertModal .col-md-4');

        if (isViewOnly) {
            workflowSection.classList.add('d-none');
            document.querySelector('#previewCertModal .col-md-8').classList.replace('col-md-8', 'col-md-12');
        } else {
            workflowSection.classList.remove('d-none');
            document.querySelector('#previewCertModal .col-md-12')?.classList.replace('col-md-12', 'col-md-8');
        }

        if (type === 'pdf') {
            let url = source;
            if (url.startsWith('http://')) url = url.replace('http://', 'https://');

            iframe.src = url;
            
            iframe.classList.remove('d-none');
            linkPreview.classList.add('d-none');
        } else {
            iframe.classList.add('d-none');
            linkPreview.classList.remove('d-none');
            btnLink.href = source;
        }

        new bootstrap.Modal(document.getElementById('previewCertModal')).show();
    }
</script>

<style>
    .bg-soft-info { background: #e0f2fe; }
    .bg-soft-success { background: #dcfce7; }
    .bg-soft-warning { background: #fef3c7; }
    .bg-soft-danger { background: #fee2e2; }
    .avatar-circle-sm { width: 32px; height: 32px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.75rem; }
</style>

@endsection
