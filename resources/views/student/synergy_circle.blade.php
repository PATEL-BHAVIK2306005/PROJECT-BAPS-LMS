@extends('layouts.app')
@section('content')

<!-- Syntax Highlighting & Markdown CDNs -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/github-dark.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<style>
    .synergy-header {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
        border-radius: 1.5rem;
        color: white;
        padding: 3rem 2rem;
        box-shadow: 0 10px 30px rgba(79, 70, 229, 0.2);
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    .synergy-header::after {
        content: '';
        position: absolute;
        width: 300px;
        height: 300px;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 50%;
        top: -100px;
        right: -100px;
    }
    .glass-card {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        border-radius: 1.25rem;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.04);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .glass-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 40px 0 rgba(31, 38, 135, 0.08);
    }
    .badge-card {
        background: radial-gradient(circle at 10% 20%, #1e1b4b 0%, #0f172a 100%);
        border: 2px solid rgba(255, 255, 255, 0.1);
        border-radius: 1.25rem;
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .badge-card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        background: linear-gradient(135deg, rgba(239, 68, 68, 0.15) 0%, rgba(249, 115, 22, 0.15) 50%, rgba(99, 102, 241, 0.15) 100%);
        opacity: 0.6;
        z-index: 1;
    }
    .badge-card:hover {
        transform: scale(1.03) rotate(1deg);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.3), 0 0 20px rgba(99, 102, 241, 0.2);
        border-color: rgba(255, 255, 255, 0.25);
    }
    .badge-content {
        position: relative;
        z-index: 2;
    }
    .badge-glow {
        position: absolute;
        width: 120px;
        height: 120px;
        background: radial-gradient(circle, rgba(99, 102, 241, 0.4) 0%, transparent 70%);
        top: -20px;
        right: -20px;
        border-radius: 50%;
    }
    .badge-hash {
        font-family: 'Courier New', Courier, monospace;
        font-weight: bold;
        letter-spacing: 2px;
        color: #fbbf24;
    }
    .signature-container {
        background: rgba(255, 255, 255, 0.08);
        border: 1px dashed rgba(255, 255, 255, 0.2);
        border-radius: 8px;
        padding: 6px 12px;
        display: inline-block;
        margin-top: 10px;
    }
    .rating-star {
        color: #fbbf24;
    }
    .status-badge {
        padding: 0.35rem 0.75rem;
        border-radius: 50rem;
        font-weight: 600;
        font-size: 0.8rem;
    }
    .status-pending { background: #fffbeb; color: #d97706; border: 1px solid #fde68a; }
    .status-reviewed { background: #ecfdf5; color: #059669; border: 1px solid #a7f3d0; }
    .status-approved { background: #f0fdf4; color: #16a34a; border: 1px solid #bbf7d0; }
    .status-rejected { background: #fef2f2; color: #dc2626; border: 1px solid #fecaca; }

    /* Dark Mode Fallbacks */
    body.dark-mode .glass-card {
        background: rgba(30, 41, 59, 0.8) !important;
        border-color: rgba(255, 255, 255, 0.05) !important;
        color: #f8fafc !important;
    }
    body.dark-mode .glass-card .text-dark {
        color: #ffffff !important;
    }
</style>

<div class="container-fluid py-4">
    <!-- Header -->
    <div class="synergy-header">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h1 class="fw-bold mb-2">Synergy Circle</h1>
                <p class="mb-0 text-white-50">Accelerate your skills with peer-level code review, earn digitally verified Excellence Credentials, and unlock exclusive lab privileges.</p>
            </div>
            <div>
                <a href="/dashboard" class="btn btn-outline-light rounded-pill px-4 fw-bold shadow-sm">
                    <i class="fas fa-arrow-left me-2"></i> Back to Dashboard
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Notifications -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">
        <!-- Main Workspace (Left Column) -->
        <div class="col-lg-8">
            <!-- Submit Form Card -->
            <div class="glass-card p-4 mb-4">
                <div class="d-flex align-items-center mb-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 me-3">
                        <i class="fas fa-code-branch fs-4"></i>
                    </div>
                    <h4 class="fw-bold mb-0 text-dark">Submit Snippet for Code Review</h4>
                </div>
                <form action="/synergy-circle/request" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Task / Project Title</label>
                            <input type="text" name="title" class="form-control" placeholder="e.g. Optimized Dijkstra using Fibonacci Heap" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-secondary">Language</label>
                            <select name="language" class="form-select" required>
                                <option value="javascript">JavaScript</option>
                                <option value="php">PHP</option>
                                <option value="python">Python</option>
                                <option value="cpp">C++</option>
                                <option value="java">Java</option>
                                <option value="rust">Rust</option>
                                <option value="sql">SQL</option>
                                <option value="html/css">HTML/CSS</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-secondary">Category</label>
                            <select name="category" class="form-select" required>
                                <option value="Data Structures">Data Structures</option>
                                <option value="Algorithms">Algorithms</option>
                                <option value="AI / ML">AI / ML</option>
                                <option value="Web Development">Web Development</option>
                                <option value="Database Systems">Database Systems</option>
                                <option value="Cybersecurity">Cybersecurity</option>
                                <option value="Cloud Infrastructure">Cloud Infrastructure</option>
                            </select>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary">Snippet Context / Objectives</label>
                            <textarea name="description" rows="2" class="form-control" placeholder="Provide background on what your code accomplishes, algorithm details, or specific help needed..." required></textarea>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-secondary">Code Snippet</label>
                            <textarea name="code_snippet" rows="8" class="form-control font-monospace" placeholder="Paste your clean code snippet here..." style="font-size: 0.9rem;" required></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-secondary">Assign Mentor / Faculty</label>
                            <select name="mentor_id" class="form-select" required>
                                <option value="">Select Faculty Reviewer...</option>
                                @foreach($mentors as $mentor)
                                    <option value="{{ $mentor->id }}">{{ $mentor->name }} ({{ implode(', ', $mentor->positions ?? [$mentor->role]) }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2.5 rounded-pill shadow-sm"><i class="fas fa-paper-plane me-2"></i> Submit for Mentor Review</button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Submitted Snippets list -->
            <div class="glass-card p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-history me-2 text-primary"></i> Your Snippets Submission History</h5>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Submission Details</th>
                                <th>Category / Language</th>
                                <th>Reviewing Mentor</th>
                                <th>Status</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($requests as $req)
                                <tr class="border-bottom border-light">
                                    <td>
                                        <div class="fw-bold text-dark">{{ $req->title }}</div>
                                        <small class="text-muted"><i class="far fa-clock me-1"></i> {{ $req->created_at->diffForHumans() }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2.5 py-1 mb-1 d-inline-block">{{ $req->category }}</span>
                                        <div><span class="badge bg-primary bg-opacity-10 text-primary px-2 py-0.5" style="font-size: 0.75rem;">{{ strtoupper($req->language) }}</span></div>
                                    </td>
                                    <td>
                                        <div class="fw-bold text-secondary">{{ $req->mentor->name ?? 'Unassigned' }}</div>
                                        <small class="text-muted" style="font-size: 0.8rem;">Reviewer</small>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $req->status }}">{{ ucfirst($req->status) }}</span>
                                    </td>
                                    <td class="text-end">
                                        <button class="btn btn-sm btn-outline-primary rounded-circle" style="width:36px; height:36px; display:inline-flex; align-items:center; justify-content:center;" onclick="viewRequestDetails({{ json_encode($req) }}, {{ json_encode($req->feedback) }})" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">
                                        <i class="fas fa-folder-open fs-2 mb-2 opacity-50"></i>
                                        <p class="mb-0">No code review submissions yet. Start by submitting your snippet above!</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Badges & Lab Privilege Showcase (Right Column) -->
        <div class="col-lg-4">
            <!-- Badges Section -->
            <div class="glass-card p-4 mb-4">
                <h4 class="fw-bold text-dark mb-3"><i class="fas fa-shield-halved text-warning me-2"></i> Earned Credentials</h4>
                <p class="text-muted small">Earn 3+ stars from faculty code reviews to unlock Synergy Circle Badges. These Badges verify your programming skills on-chain and grant institutional benefits.</p>

                @if($badges->count() > 0)
                    <div class="d-flex flex-column gap-3">
                        @foreach($badges as $badge)
                            <div class="badge-card p-4">
                                <div class="badge-glow"></div>
                                <div class="badge-content">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="badge bg-warning text-dark fw-bold px-3 py-1 rounded-pill" style="font-size: 0.75rem;"><i class="fas fa-ribbon me-1"></i> EXCELLENCE BADGE</span>
                                        <div class="badge-hash text-warning" style="font-size: 0.8rem;">{{ $badge->badge_hash }}</div>
                                    </div>
                                    <h5 class="fw-bold mb-1">{{ $badge->request->title ?? 'Snippet' }}</h5>
                                    <div class="small text-white-50 mb-2">Category: {{ $badge->request->category ?? 'N/A' }}</div>
                                    
                                    <!-- Ratings -->
                                    <div class="mb-3">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fas fa-star {{ $i <= $badge->rating ? 'rating-star' : 'text-white-50' }}"></i>
                                        @endfor
                                        <span class="ms-2 fw-bold text-warning" style="font-size:0.9rem;">{{ $badge->rating }}.0/5</span>
                                    </div>

                                    <!-- Signed By and SVG Rendered Signature -->
                                    <div class="border-top border-white border-opacity-10 pt-3">
                                        <div class="small text-white-50">Digitally Verified By:</div>
                                        <div class="fw-bold text-white mb-1">{{ $badge->reviewer->name ?? 'Faculty Mentor' }}</div>
                                        @if($badge->signature_data)
                                            <div class="signature-container">
                                                {!! $badge->signature_data !!}
                                            </div>
                                        @else
                                            <div class="small text-muted italic">Signature Unchecked</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4 border rounded border-dashed text-muted">
                        <i class="fas fa-award fs-2 mb-2 text-muted opacity-50"></i>
                        <p class="mb-0 small">No verified badges earned yet.</p>
                    </div>
                @endif
            </div>

            <!-- Lab Privileges Request Form -->
            <div class="glass-card p-4 mb-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-key text-indigo me-2"></i> Request Lab Privilege</h5>
                <p class="text-muted small">Leverage your earned Synergy Circle Badges to apply for high-end research privileges. Administrative staff will evaluate your credentials.</p>

                <form action="/synergy-circle/apply-privilege" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Supporting Badge</label>
                        <select name="feedback_id" class="form-select" required>
                            <option value="">Select supporting badge...</option>
                            @foreach($badges as $badge)
                                <option value="{{ $badge->id }}">{{ $badge->badge_hash }} ({{ $badge->request->title }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Requested Privilege</label>
                        <select name="privilege_type" class="form-select" required>
                            <option value="GPU Server Time (NVIDIA H100)">GPU Server Time (NVIDIA H100)</option>
                            <option value="Overnight Academic Lab Access">Overnight Academic Lab Access</option>
                            <option value="Advanced IoT Development Kit Checkout">Advanced IoT Development Kit Checkout</option>
                            <option value="High-Performance Compute Cluster (HPC) Slot">High-Performance Compute Cluster (HPC) Slot</option>
                            <option value="Sandbox Deployment Subdomain (Virtual Host)">Sandbox Deployment Subdomain (Virtual Host)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-secondary">Justification Statement</label>
                        <textarea name="justification" rows="3" class="form-control" placeholder="Describe the research, testing, or development work that warrants this special permission..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark w-100 fw-bold rounded-pill shadow-sm" {{ $badges->isEmpty() ? 'disabled' : '' }}>
                        <i class="fas fa-check-double me-1"></i> Apply for Permission
                    </button>
                    @if($badges->isEmpty())
                        <div class="text-danger small mt-2 text-center"><i class="fas fa-lock"></i> Requires at least 1 earned Excellence Badge.</div>
                    @endif
                </form>
            </div>

            <!-- Active / Pending Privileges Status Feed -->
            <div class="glass-card p-4">
                <h5 class="fw-bold text-dark mb-3"><i class="fas fa-door-open text-primary me-2"></i> Lab Access Privilege Status</h5>
                <div class="d-flex flex-column gap-3">
                    @forelse($privileges as $p)
                        <div class="p-3 border rounded shadow-sm bg-white bg-opacity-50">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <span class="fw-bold text-dark" style="font-size:0.9rem;">{{ $p->privilege_type }}</span>
                                <span class="status-badge status-{{ $p->status }}" style="font-size:0.7rem; padding: 0.2rem 0.5rem;">{{ ucfirst($p->status) }}</span>
                            </div>
                            <div class="small text-muted mb-2">Supporting Badge: <strong class="text-primary">{{ $p->feedback->badge_hash }}</strong></div>
                            <div class="small text-secondary mb-2" style="font-size: 0.85rem; line-height: 1.4;">"{{ $p->justification }}"</div>
                            @if($p->processed_by)
                                <div class="border-top border-light pt-2 mt-2" style="font-size: 0.75rem;">
                                    <span class="text-muted">Processed By:</span> <strong>{{ $p->processor->name ?? 'Staff' }}</strong>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-4 text-muted small">
                            <i class="fas fa-info-circle opacity-50 mb-1 d-block"></i> No privilege applications submitted yet.
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Request Detail Preview Modal -->
<div class="modal fade" id="requestDetailModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            <div class="modal-header bg-dark text-white border-0 p-4 d-flex justify-content-between align-items-center">
                <h5 class="modal-title fw-bold"><i class="fas fa-code me-2 text-info"></i> Code Snippet & Review Workspace</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="row g-4">
                    <!-- Left: Code & Metadata -->
                    <div class="col-lg-7">
                        <div class="p-3 bg-white rounded-3 shadow-sm mb-3">
                            <h4 id="detailTitle" class="fw-bold text-dark mb-1">Snippet Title</h4>
                            <div class="d-flex gap-2 align-items-center mb-3">
                                <span id="detailCategory" class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1">Category</span>
                                <span id="detailLanguage" class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">LANGUAGE</span>
                                <small class="text-muted ms-auto" id="detailTime">Posted just now</small>
                            </div>
                            <p class="text-secondary small" id="detailDescription">Description text goes here...</p>
                        </div>
                        <div class="position-relative">
                            <span class="position-absolute top-0 end-0 badge bg-dark m-3 z-3 text-uppercase" id="codeBadge">Code</span>
                            <pre class="m-0 rounded-3 overflow-hidden shadow-sm" style="max-height: 480px;"><code id="detailCode" class="language-javascript"></code></pre>
                        </div>
                    </div>
                    <!-- Right: Review Comments & Credentials -->
                    <div class="col-lg-5">
                        <div class="p-4 bg-white rounded-3 shadow-sm h-100 d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="bg-warning bg-opacity-10 text-warning rounded-circle p-2 me-3">
                                    <i class="fas fa-comment-code fs-4"></i>
                                </div>
                                <h4 class="fw-bold mb-0 text-dark">Mentor Feedback</h4>
                            </div>

                            <div id="reviewDetailsSection" class="flex-grow-1">
                                <div class="d-flex align-items-center justify-content-between mb-3 border-bottom pb-2">
                                    <div>
                                        <div class="fw-bold text-dark" id="reviewerName">Dr. Reviewer Name</div>
                                        <small class="text-muted">Reviewer Faculty</small>
                                    </div>
                                    <div class="text-end">
                                        <div id="ratingStars">
                                            <!-- Stars dynamically rendered -->
                                        </div>
                                        <small class="text-warning fw-bold" id="ratingValue">5.0/5 Stars</small>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <h6 class="fw-bold text-secondary">Comments & Recommendations:</h6>
                                    <div class="p-3 bg-light rounded-3 text-secondary" id="reviewerComments" style="min-height: 120px; font-size: 0.95rem; line-height: 1.6;">
                                        Markdown comments rendered here...
                                    </div>
                                </div>

                                <div class="border-top pt-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="badge bg-warning text-dark fw-bold px-2 py-0.5" style="font-size:0.7rem;"><i class="fas fa-award"></i> Verification Badge</span>
                                        <strong class="text-secondary small font-monospace" id="badgeHashValue">SC-ALG-XXXXX</strong>
                                    </div>
                                    <div class="text-center p-3 bg-dark rounded-3 text-white border border-opacity-10 position-relative overflow-hidden mb-3">
                                        <div class="small text-white-50 mb-1">Excellence Credentials Signed By:</div>
                                        <div class="fw-bold mb-2 text-warning" id="reviewerSignName">Dr. Reviewer</div>
                                        <div class="signature-container bg-white bg-opacity-10 d-inline-block px-3 py-1 rounded" id="signatureDisplay">
                                            <!-- Signature SVG inline -->
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div id="pendingReviewSection" class="text-center py-5 my-auto text-muted">
                                <i class="fas fa-hourglass-half fs-1 mb-3 text-warning animate-pulse"></i>
                                <h5 class="fw-bold mb-1">Review is Currently Pending</h5>
                                <p class="small mb-0">Your assigned mentor is evaluating your code snippet. You will receive a notification as soon as the review is complete.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Initialize Highlight.js on code blocks
    document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre code').forEach((el) => {
            hljs.highlightElement(el);
        });
    });

    // Handle view request details modal logic
    function viewRequestDetails(request, feedback) {
        const modalElement = document.getElementById('requestDetailModal');
        const modal = new bootstrap.Modal(modalElement);

        document.getElementById('detailTitle').textContent = request.title;
        document.getElementById('detailCategory').textContent = request.category;
        document.getElementById('detailLanguage').textContent = request.language.toUpperCase();
        document.getElementById('detailTime').textContent = new Date(request.created_at).toLocaleString();
        document.getElementById('detailDescription').textContent = request.description;

        // Set Code Snippet
        const codeElem = document.getElementById('detailCode');
        codeElem.className = 'language-' + request.language;
        codeElem.textContent = request.code_snippet;
        hljs.highlightElement(codeElem);

        document.getElementById('codeBadge').textContent = request.language;

        // Display Review or Pending Placeholder
        if (request.status === 'reviewed' && feedback) {
            document.getElementById('reviewDetailsSection').style.display = 'block';
            document.getElementById('pendingReviewSection').style.display = 'none';

            // Mentor Metadata
            document.getElementById('reviewerName').textContent = request.mentor ? request.mentor.name : 'Faculty Mentor';
            document.getElementById('reviewerSignName').textContent = request.mentor ? request.mentor.name : 'Faculty Mentor';
            
            // Rating Stars
            let starsHtml = '';
            for (let i = 1; i <= 5; i++) {
                if (i <= feedback.rating) {
                    starsHtml += '<i class="fas fa-star text-warning"></i>';
                } else {
                    starsHtml += '<i class="far fa-star text-muted"></i>';
                }
            }
            document.getElementById('ratingStars').innerHTML = starsHtml;
            document.getElementById('ratingValue').textContent = feedback.rating + '.0/5 Stars';

            // Markdown Feedback Parsing
            document.getElementById('reviewerComments').innerHTML = marked.parse(feedback.comments);

            // Badge Hash & Signature
            document.getElementById('badgeHashValue').textContent = feedback.badge_hash;
            if (feedback.signature_data) {
                document.getElementById('signatureDisplay').innerHTML = feedback.signature_data;
                document.getElementById('signatureDisplay').style.display = 'inline-block';
            } else {
                document.getElementById('signatureDisplay').style.display = 'none';
            }
        } else {
            document.getElementById('reviewDetailsSection').style.display = 'none';
            document.getElementById('pendingReviewSection').style.display = 'block';
        }

        modal.show();
    }
</script>

@endsection
