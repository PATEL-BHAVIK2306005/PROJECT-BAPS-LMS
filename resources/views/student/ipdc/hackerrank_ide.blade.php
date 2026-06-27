@extends('layouts.app')
@section('content')

<style>
    #editorContainer {
        width: 100%;
        height: 100%;
        min-height: 450px;
    }
    #terminalBody {
        background: #090d16;
        font-family: 'Fira Code', 'Courier New', monospace;
        color: #f8fafc;
        max-height: 200px;
        overflow-y: auto;
    }
    .problem-section {
        max-height: calc(100vh - 200px);
        overflow-y: auto;
    }
    .code-box {
        background: #1e293b;
        color: #e2e8f0;
        padding: 10px 14px;
        border-radius: 8px;
        font-family: 'Fira Code', monospace;
        font-size: 0.85rem;
        border: 1px solid rgba(255, 255, 255, 0.05);
    }
    .testcase-pill {
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 0.75rem;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .testcase-passed {
        background: rgba(16, 185, 129, 0.15);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    .testcase-failed {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    .btn-run {
        background: rgba(255,255,255,0.08);
        border: 1px solid rgba(255,255,255,0.1);
        color: #cbd5e1;
        transition: all 0.2s ease;
    }
    .btn-run:hover {
        background: rgba(255,255,255,0.15);
        color: #ffffff;
    }
    .dark-mode .btn-run {
        background: rgba(255,255,255,0.05);
    }
</style>

<div class="container-fluid">
    <div class="row mb-4 align-items-center">
        <div class="col-md-7">
            <h3 class="fw-bold text-dark mb-1"><i class="fab fa-hackerrank text-success me-2"></i> HackerRank Practice Workspace</h3>
            <p class="text-muted small mb-0">{{ $problem->title }} • {{ $problem->difficulty }} ({{ $problem->points }} XP)</p>
        </div>
        <div class="col-md-5 text-end d-flex gap-2 justify-content-md-end mt-3 mt-md-0">
            <a href="/ipdc/vault" class="btn btn-outline-dark rounded-pill px-3 fw-bold"><i class="fas fa-arrow-left me-1"></i> Back to Vault</a>
            <button id="runBtn" class="btn btn-run rounded-pill px-4 fw-bold shadow-sm" style="border: 1px solid #cbd5e1; color: #334155;">
                <i class="fas fa-play me-1 text-success"></i> Run Code
            </button>
            <button id="submitBtn" class="btn btn-success rounded-pill px-4 fw-bold shadow-sm" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important; border:none;">
                <i class="fas fa-upload me-1"></i> Submit Solution
            </button>
        </div>
    </div>

    <div class="row g-4">
        <!-- Left: Problem Specification -->
        <div class="col-lg-5 problem-section">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-primary"><i class="fas fa-file-alt me-2"></i> Challenge Description</h5>
                </div>
                <div class="card-body pt-0">
                    <p class="text-dark-light" style="line-height: 1.7; font-size: 0.95rem;">
                        {!! nl2br(e($problem->description)) !!}
                    </p>
                    
                    @if($problem->input_format)
                    <div class="mt-4">
                        <h6 class="fw-bold text-dark mb-2">Input Format</h6>
                        <p class="small text-muted">{{ $problem->input_format }}</p>
                    </div>
                    @endif

                    @if($problem->constraints)
                    <div class="mt-3">
                        <h6 class="fw-bold text-dark mb-2">Constraints</h6>
                        <p class="small text-muted font-monospace bg-light p-2 rounded">{{ $problem->constraints }}</p>
                    </div>
                    @endif

                    @if($problem->output_format)
                    <div class="mt-3">
                        <h6 class="fw-bold text-dark mb-2">Output Format</h6>
                        <p class="small text-muted">{{ $problem->output_format }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Sample Input & Output -->
            @if($problem->sample_input || $problem->sample_output)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-flask text-warning me-2"></i> Sample Cases</h5>
                </div>
                <div class="card-body pt-0">
                    @if($problem->sample_input)
                    <div class="mb-3">
                        <div class="small fw-bold text-muted mb-2">Sample Input</div>
                        <pre class="code-box">{{ $problem->sample_input }}</pre>
                    </div>
                    @endif

                    @if($problem->sample_output)
                    <div>
                        <div class="small fw-bold text-muted mb-2">Sample Output</div>
                        <pre class="code-box">{{ $problem->sample_output }}</pre>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Submission History -->
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white py-3 border-0">
                    <h5 class="fw-bold m-0 text-dark"><i class="fas fa-history text-indigo me-2"></i> Solution History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead class="small bg-light text-muted">
                                <tr>
                                    <th class="ps-4">Submitted At</th>
                                    <th>Language</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Score</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($submissions as $sub)
                                <tr>
                                    <td class="ps-4 py-2 small text-muted">{{ \Carbon\Carbon::parse($sub->created_at)->format('M d, g:i A') }}</td>
                                    <td><span class="badge bg-secondary-subtle text-dark border">{{ $sub->language }}</span></td>
                                    <td>
                                        @if($sub->status === 'Passed')
                                            <span class="testcase-pill testcase-passed"><i class="fas fa-check-circle"></i> Passed</span>
                                        @else
                                            <span class="testcase-pill testcase-failed"><i class="fas fa-times-circle"></i> Failed</span>
                                        @endif
                                    </td>
                                    <td class="text-end pe-4 fw-bold">{{ $sub->passed_test_cases }} / {{ $sub->total_test_cases }}</td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted small">No submission records.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Code Workspace & Terminal Console -->
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-3">
                <div class="card-header bg-dark text-white py-2 border-0 d-flex justify-content-between align-items-center">
                    <div class="small fw-bold"><i class="fas fa-laptop-code me-2 text-success"></i> Solution Sandbox</div>
                    <select id="languageSelect" class="form-select form-select-sm bg-secondary border-0 text-white rounded-pill px-3" style="width: auto;">
                        <option value="python">Python 3</option>
                        <option value="javascript">JavaScript</option>
                        <option value="java">Java</option>
                        <option value="php">PHP</option>
                    </select>
                </div>
                <div id="editorContainer"></div>
            </div>

            <!-- Custom Input Sandbox -->
            <div class="card border-0 shadow-sm rounded-4 p-3 mb-3 bg-white">
                <div class="form-check form-switch mb-2">
                    <input class="form-check-input" type="checkbox" role="switch" id="customInputSwitch">
                    <label class="form-check-label small fw-bold text-muted" for="customInputSwitch">Run with Custom Input</label>
                </div>
                <textarea id="customInputText" rows="2" class="form-control rounded-3 font-monospace small d-none" placeholder="Provide test arguments here..."></textarea>
            </div>

            <!-- Terminal Output Console -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden" style="background: #090d16;">
                <div class="card-header border-0 py-2 d-flex justify-content-between align-items-center" style="background: #111827; color: #9ca3af;">
                    <div class="small fw-bold font-monospace"><i class="fas fa-terminal me-2 text-warning"></i> Sandbox Output console</div>
                    <button class="btn btn-link btn-sm text-decoration-none text-muted py-0 small" onclick="clearTerminal()">Clear</button>
                </div>
                <div id="terminalBody" class="p-3 font-monospace small">
                    <div class="text-secondary opacity-50">> Sandbox ready. Click 'Run Code' or 'Submit Solution' to begin.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monaco Editor Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>

<script>
    let editor;
    const starterTemplates = {
        python: `# Write your Python 3 solution here\nimport sys\n\ndef solve():\n    # Read input from stdin\n    # lines = sys.stdin.read().splitlines()\n    # print("Your output here")\n    pass\n\nif __name__ == '__main__':\n    solve()\n`,
        javascript: `// Write your JavaScript solution here\nconst fs = require('fs');\n\nfunction solve() {\n    const input = fs.readFileSync('/dev/stdin', 'utf-8');\n    // console.log("Your output here");\n}\n\nsolve();\n`,
        java: `// Write your Java solution here\nimport java.util.*;\n\npublic class Solution {\n    public static void main(String[] args) {\n        Scanner scanner = new Scanner(System.in);\n        // int n = scanner.nextInt();\n        // System.out.println(n);\n    }\n}\n`,
        php: "<\x3fphp\n// Write your PHP solution here\n// \$input = fgets(STDIN);\n// echo \"Your output\\n\";\n"
    };

    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' }});
    require(['vs/editor/editor.main'], function() {
        editor = monaco.editor.create(document.getElementById('editorContainer'), {
            value: starterTemplates.python,
            language: 'python',
            theme: 'vs-dark',
            fontSize: 14,
            fontFamily: "'Fira Code', 'Courier New', monospace",
            automaticLayout: true,
            padding: { top: 16 },
            minimap: { enabled: false }
        });
    });

    // Handle language switch template loading
    document.getElementById('languageSelect').addEventListener('change', function() {
        const lang = this.value;
        let monacoLang = lang;
        if (lang === 'node') monacoLang = 'javascript';
        
        monaco.editor.setModelLanguage(editor.getModel(), monacoLang);
        editor.setValue(starterTemplates[lang] || '');
    });

    // Custom input toggle
    document.getElementById('customInputSwitch').addEventListener('change', function() {
        const textarea = document.getElementById('customInputText');
        if (this.checked) {
            textarea.classList.remove('d-none');
            textarea.focus();
        } else {
            textarea.classList.add('d-none');
        }
    });

    // Clear console logs
    function clearTerminal() {
        document.getElementById('terminalBody').innerHTML = '<div class="text-secondary opacity-50">> Console cleared.</div>';
    }

    // Run Code Sandbox action
    document.getElementById('runBtn').addEventListener('click', function() {
        const code = editor.getValue();
        const lang = document.getElementById('languageSelect').value;
        const terminal = document.getElementById('terminalBody');
        const customInputText = document.getElementById('customInputText').value;
        const customInputActive = document.getElementById('customInputSwitch').checked;

        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Compiling...';
        this.disabled = true;

        terminal.innerHTML += `<div class="text-info mt-2">>>> Spawning execution task [${lang}]...</div>`;

        fetch('/api/ipdc/hackerrank/run/{{ $problem->id }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: code,
                language: lang,
                custom_input: customInputActive ? customInputText : '{{ addslashes($problem->sample_input ?? "") }}'
            })
        })
        .then(res => res.json())
        .then(data => {
            this.innerHTML = '<i class="fas fa-play me-1 text-success"></i> Run Code';
            this.disabled = false;

            if (data.stderr) {
                terminal.innerHTML += `<div class="text-danger fw-bold mt-1">Runtime Compilation Error:</div>`;
                terminal.innerHTML += `<pre class="text-danger mt-1 mb-0">${escapeHtml(data.stderr)}</pre>`;
            } else {
                terminal.innerHTML += `<div class="text-muted mt-1">Standard Output:</div>`;
                terminal.innerHTML += `<pre class="text-success mt-1 mb-0 fw-bold bg-dark p-2 rounded border border-secondary">${escapeHtml(data.output || "(Empty output)")}</pre>`;
            }
            terminal.scrollTop = terminal.scrollHeight;
        })
        .catch(err => {
            this.innerHTML = '<i class="fas fa-play me-1 text-success"></i> Run Code';
            this.disabled = false;
            terminal.innerHTML += `<div class="text-danger mt-2">Execution Pipeline Failure: Connection reset.</div>`;
        });
    });

    // Submit Solution action
    document.getElementById('submitBtn').addEventListener('click', function() {
        const code = editor.getValue();
        const lang = document.getElementById('languageSelect').value;
        const terminal = document.getElementById('terminalBody');

        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Evaluating...';
        this.disabled = true;

        terminal.innerHTML += `<div class="text-warning mt-2">>>> Initiating evaluation suite. Testing all locked cases...</div>`;

        fetch('/api/ipdc/hackerrank/submit/{{ $problem->id }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                code: code,
                language: lang
            })
        })
        .then(res => res.json())
        .then(data => {
            this.innerHTML = '<i class="fas fa-upload me-1"></i> Submit Solution';
            this.disabled = false;

            if (data.status === 'Passed') {
                terminal.innerHTML += `<div class="text-success fw-bold mt-2" style="font-size:1.1rem;"><i class="fas fa-check-circle"></i> CONGRATULATIONS! ALL ${data.passed_count}/${data.total_count} TEST CASES PASSED.</div>`;
                if (data.points_awarded > 0) {
                    terminal.innerHTML += `<div class="text-warning fw-bold">+${data.points_awarded} XP Added to your profile!</div>`;
                    if (typeof confetti === 'function') confetti({ particleCount: 120, spread: 80, origin: { y: 0.6 } });
                }
                showBapsToast('Challenge Solved successfully!', 'success');
            } else {
                const fc = data.failed_case || {};
                terminal.innerHTML += `<div class="text-danger fw-bold mt-2" style="font-size:1.1rem;"><i class="fas fa-times-circle"></i> JUDGE CRITERIA FAILED [Test Case #${fc.index || 1}]</div>`;
                if (fc.error) {
                    terminal.innerHTML += `<pre class="text-danger bg-dark p-2 rounded mt-1">${escapeHtml(fc.error)}</pre>`;
                } else {
                    terminal.innerHTML += `<div class="row g-2 mt-2">
                        <div class="col-6">
                            <span class="small fw-bold text-muted">Your Output:</span>
                            <pre class="bg-dark text-danger p-2 rounded border border-danger small mb-0 font-monospace">${escapeHtml(fc.actual || "")}</pre>
                        </div>
                        <div class="col-6">
                            <span class="small fw-bold text-muted">Expected Output:</span>
                            <pre class="bg-dark text-success p-2 rounded border border-success small mb-0 font-monospace">${escapeHtml(fc.expected || "")}</pre>
                        </div>
                    </div>`;
                }
                showBapsToast('Some test cases failed. Keep refining your logic!', 'warning');
            }
            terminal.scrollTop = terminal.scrollHeight;
        })
        .catch(err => {
            this.innerHTML = '<i class="fas fa-upload me-1"></i> Submit Solution';
            this.disabled = false;
            terminal.innerHTML += `<div class="text-danger mt-2">System Error: Evaluation abort.</div>`;
        });
    });

    function escapeHtml(text) {
        return text
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
</script>

@endsection
