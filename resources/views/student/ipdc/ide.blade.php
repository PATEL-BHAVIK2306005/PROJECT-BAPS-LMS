@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold text-dark mb-1"><i class="fas fa-terminal me-2 text-primary"></i> IPDC Assignment IDE</h3>
        <p class="text-muted small mb-0">{{ $task->title }} • {{ $task->course->title }}</p>
    </div>
    <div class="d-flex gap-2">
        <a href="/ipdc/vault" class="btn btn-outline-dark btn-sm rounded-pill px-3 fw-bold"><i class="fas fa-arrow-left me-1"></i> Back to Vault</a>
        <button id="runBtn" class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm">
            <i class="fas fa-play me-1"></i> Run Code
        </button>
        <button id="submitBtn" class="btn btn-primary btn-sm rounded-pill px-4 fw-bold shadow-sm">
            <i class="fas fa-paper-plane me-1"></i> Submit Task
        </button>
    </div>
</div>

<div class="row g-4" style="height: calc(100vh - 200px);">
    <!-- Left Column: Instructions & Solution -->
    <div class="col-lg-4 h-100">
        <div class="d-flex flex-column h-100">
            <!-- Assignment Description -->
            <div class="card border-0 shadow-sm rounded-4 mb-3 flex-grow-1 overflow-auto">
                <div class="card-header bg-white py-3 border-0">
                    <h6 class="fw-bold m-0 text-primary"><i class="fas fa-info-circle me-2"></i> Instructions</h6>
                </div>
                <div class="card-body pt-0">
                    <div class="assignment-desc">
                        {!! nl2br(e($task->description)) !!}
                    </div>
                    
                    <div class="alert alert-warning small py-2 mt-4 border-0 rounded-3">
                        <i class="fas fa-lightbulb me-1"></i> <strong>Tip:</strong> Use the standard input for testing.
                    </div>
                </div>
            </div>

            <!-- Solution Section (Gated) -->
            <div class="card border-0 shadow-sm rounded-4 bg-dark text-white">
                <div class="card-header border-0 bg-dark text-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0"><i class="fas fa-eye me-2"></i> Reference Solution</h6>
                    <span id="solutionLocked" class="badge bg-warning text-dark rounded-pill px-3">Locked</span>
                </div>
                <div class="card-body p-0 position-relative">
                    <div id="solutionOverlay" class="position-absolute w-100 h-100 d-flex flex-column align-items-center justify-content-center bg-dark bg-opacity-75 z-index-10" style="backdrop-filter: blur(8px); border-radius: 0 0 1rem 1rem;">
                        <i class="fas fa-lock fa-2x mb-2 text-white-50"></i>
                        <p class="small text-white-50 px-4 text-center">Solution becomes available after your first successful code run.</p>
                        <button class="btn btn-sm btn-outline-warning rounded-pill px-3 fw-bold mt-2" onclick="unlockSolution()">Request Unlock</button>
                    </div>
                    <div id="solutionCode" class="p-3 d-none">
                        <pre class="m-0 text-success small" style="font-family: 'Fira Code', monospace;">
def solve():
    # Example logic for IPDC module
    print("Selfless service is the rent we pay for our room here on earth.")

solve()</pre>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Column: IDE & Terminal -->
    <div class="col-lg-8 h-100">
        <div class="d-flex flex-column h-100">
            <!-- Code Editor -->
            <div class="card border-0 shadow-sm rounded-4 mb-3 overflow-hidden" style="flex: 3;">
                <div class="card-header bg-dark text-white py-2 border-0 d-flex justify-content-between align-items-center">
                    <div class="small fw-bold"><i class="fas fa-file-code me-2 text-info"></i> solution.py</div>
                    <select id="languageSelect" class="form-select form-select-sm bg-secondary border-0 text-white rounded-pill px-3" style="width: auto;">
                        <option value="python">Python 3</option>
                        <option value="javascript">JavaScript</option>
                        <option value="java">Java</option>
                    </select>
                </div>
                <div id="editorContainer" class="flex-grow-1"></div>
            </div>

            <!-- Interactive Terminal -->
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-black" style="flex: 1;">
                <div class="card-header bg-dark text-white-50 py-1 border-0 small d-flex justify-content-between">
                    <span>Terminal / Output</span>
                    <button class="btn btn-xs text-white-50" onclick="clearTerminal()">Clear</button>
                </div>
                <div id="terminalBody" class="card-body p-3 text-white font-monospace small overflow-auto">
                    <div class="text-secondary opacity-50">> Ready for input...</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Monaco Editor CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs/loader.min.js"></script>

<script>
    let editor;
    
    require.config({ paths: { 'vs': 'https://cdnjs.cloudflare.com/ajax/libs/monaco-editor/0.44.0/min/vs' }});
    require(['vs/editor/editor.main'], function() {
        editor = monaco.editor.create(document.getElementById('editorContainer'), {
            value: "# Start your IPDC reflection code here...\nprint('Reflection on Module: {{ $task->course->title }}')\n\n",
            language: 'python',
            theme: 'vs-dark',
            fontSize: 14,
            fontFamily: "'Fira Code', 'Courier New', monospace",
            automaticLayout: true,
            padding: { top: 20 },
            minimap: { enabled: false }
        });
    });

    document.getElementById('runBtn').addEventListener('click', function() {
        const code = editor.getValue();
        const lang = document.getElementById('languageSelect').value;
        const terminal = document.getElementById('terminalBody');
        
        this.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Running...';
        this.disabled = true;

        terminal.innerHTML += `<div class="text-info mt-2">>>> Executing ${lang}...</div>`;

        fetch('/api/execute-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-API-Key': 'wc_api_www.bhavikpatel180_2ef44332bd5b54bc0e0ee86dd27ddf79'
            },
            body: JSON.stringify({
                language: lang,
                files: [
                    {
                        name: 'main.py',
                        content: code
                    }
                ]
            })
        })
        .then(res => res.json())
        .then(data => {
            this.innerHTML = '<i class="fas fa-play me-1"></i> Run Code';
            this.disabled = false;
            
            const run = data.run || {};
            if (run.output) {
                terminal.innerHTML += `<div class="text-success">${run.output.replace(/\n/g, '<br>')}</div>`;
                // Successful run might unlock solution
                unlockSolution();
            }
            if (run.stderr) {
                terminal.innerHTML += `<div class="text-danger">${run.stderr.replace(/\n/g, '<br>')}</div>`;
            }
            if (!run.output && !run.stderr) {
                terminal.innerHTML += `<div class="text-secondary opacity-50">> Process finished with exit code ${run.code || 0}</div>`;
            }
            terminal.scrollTop = terminal.scrollHeight;
        })
        .catch(err => {
            this.innerHTML = '<i class="fas fa-play me-1"></i> Run Code';
            this.disabled = false;
            terminal.innerHTML += `<div class="text-danger">Execution failed: Connection error.</div>`;
        });
    });

    function unlockSolution() {
        const overlay = document.getElementById('solutionOverlay');
        const code = document.getElementById('solutionCode');
        const badge = document.getElementById('solutionLocked');
        
        if (overlay.classList.contains('d-none')) return;

        overlay.classList.add('d-none');
        code.classList.remove('d-none');
        badge.innerText = 'Unlocked';
        badge.classList.replace('bg-warning', 'bg-success');
        badge.classList.replace('text-dark', 'text-white');
    }

    function clearTerminal() {
        document.getElementById('terminalBody').innerHTML = '<div class="text-secondary opacity-50">> Ready for input...</div>';
    }

    document.getElementById('submitBtn').addEventListener('click', function() {
        const btn = this;
        const code = editor.getValue();
        const lang = document.getElementById('languageSelect').value;
        
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Submitting...';
        btn.disabled = true;
        
        fetch('/ipdc/submit-task/{{ $task->id }}', {
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
            if (data.success) {
                showBapsToast('Task Submitted Successfully! Institutional review pending.', 'success');
                setTimeout(() => {
                    window.location.href = '/ipdc/vault';
                }, 1500);
            } else {
                showBapsToast(data.message || 'Submission failed.', 'danger');
                btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Submit Task';
                btn.disabled = false;
            }
        })
        .catch(err => {
            showBapsToast('Submission failed: Connection error.', 'danger');
            btn.innerHTML = '<i class="fas fa-paper-plane me-1"></i> Submit Task';
            btn.disabled = false;
        });
    });
</script>

<style>
    #editorContainer { width: 100%; height: 100%; }
    #terminalBody { max-height: 100%; font-family: 'Fira Code', monospace; background: #000; }
    .assignment-desc { font-size: 0.95rem; line-height: 1.7; color: #334155; }
    .btn-xs { padding: 0.15rem 0.5rem; font-size: 0.7rem; }
    .z-index-10 { z-index: 10; }
    
    /* Dark Mode Support */
    body.dark-mode .assignment-desc { color: #cbd5e1; }
    body.dark-mode .card-header { background-color: #1e293b !important; color: white !important; }
    body.dark-mode .card { background-color: #0f172a !important; }
</style>

@endsection
