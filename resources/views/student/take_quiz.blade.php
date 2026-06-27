@extends('layouts.app')
@section('content')

<div class="mb-5">
    <a href="/courses/{{ $course->id }}" class="btn btn-light btn-sm rounded-pill mb-3 border shadow-sm">
        <i class="fas fa-arrow-left me-2"></i> Back to Course Overview
    </a>
    <h3 class="fw-bold"><i class="fas fa-pen-alt text-primary me-2"></i> {{ $quiz->title }}</h3>
    <p class="text-muted mb-0">Course: {{ $course->title }} | Passing Metric: <span class="badge bg-warning text-dark">{{ $quiz->min_score }} points</span></p>
</div>

@if($quiz->questions->isEmpty())
    <div class="glass-card p-5 text-center text-muted border-0 shadow-sm">
        <i class="fas fa-hammer fa-3x mb-3 text-secondary"></i>
        <p class="mb-0">This quiz is currently under construction.<br>No questions have been mapped by the instructor yet.</p>
    </div>
@else
    <form action="/courses/{{ $course->id }}/quiz/{{ $quiz->id }}" method="POST">
        @csrf
        <div class="row justify-content-center">
            <div class="col-md-10">
                @foreach($quiz->questions as $index => $q)
                    <div class="glass-card p-0 mb-4 border-0 shadow-sm position-relative overflow-hidden">
                        <div class="p-4 bg-white border-bottom d-flex justify-content-between align-items-center">
                            <h5 class="fw-bold mb-0">
                                <span class="text-primary me-1">{{ $index + 1 }}.</span> {{ $q->question_text }}
                            </h5>
                            <span class="badge {{ $q->question_type == 'code' ? 'bg-dark' : 'bg-light text-dark border' }}">{{ $q->points }} Points</span>
                        </div>
                        
                        <div class="p-4">
                            @if($q->question_type == 'code')
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <span class="badge bg-primary rounded-pill"><i class="fas fa-code me-1"></i> Interactive IDE ({{ strtoupper($q->language) }})</span>
                                </div>
                                
                                @if(!empty($q->test_cases))
                                    <div class="mb-3 p-3 bg-light border border-warning border-opacity-50 rounded">
                                        <h6 class="fw-bold mb-1 text-dark"><i class="fas fa-vial me-2 text-warning"></i> Standard Input Test Case:</h6>
                                        <div id="test_cases_{{ $q->id }}" class="font-monospace text-muted small" style="white-space: pre-wrap;">{{ $q->test_cases }}</div>
                                    </div>
                                @endif
                                
                                <div class="rounded overflow-hidden shadow-sm border mb-3" style="min-height: 250px; position: relative;">
                                    <div id="editor_{{ $q->id }}" style="position: absolute; top: 0; right: 0; bottom: 0; left: 0; font-size: 14px;"></div>
                                </div>
                                <input type="hidden" name="answers[{{ $q->id }}]" id="hidden_{{ $q->id }}" value="" required>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="fw-bold mb-0"><i class="fas fa-terminal me-2"></i> Live Terminal Output</h6>
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-secondary px-3 rounded-pill" onclick="runCode(this, {{ $q->id }}, '{{ $q->language }}')">
                                            <i class="fas fa-cogs me-1"></i> Compile
                                        </button>
                                        <button type="button" class="btn btn-sm btn-dark px-3 rounded-pill" onclick="runCode(this, {{ $q->id }}, '{{ $q->language }}')">
                                            <i class="fas fa-play me-1"></i> Run
                                        </button>
                                        <button type="button" class="btn btn-sm btn-warning text-dark px-3 rounded-pill" onclick="runCode(this, {{ $q->id }}, '{{ $q->language }}', true)">
                                            <i class="fas fa-vial me-1"></i> Save & Compile (with test case)
                                        </button>
                                    </div>
                                </div>
                                
                                <div id="terminal_{{ $q->id }}" class="bg-dark text-success p-3 rounded font-monospace small shadow-inner" style="min-height: 120px; border-left: 4px solid var(--bs-primary);">Waiting for execution...</div>

                            @elseif($q->question_type == 'vsq' || $q->question_type == 'long')
                                <textarea name="answers[{{ $q->id }}]" class="form-control" rows="4" placeholder="Write your subject-matter answer here..." required></textarea>
                            @else
                                <div class="mt-2">
                                    @foreach($q->options as $opt)
                                        <div class="form-check custom-radio mb-2 p-3 border rounded bg-white shadow-sm hover-option transition-all" style="cursor: pointer;" onclick="document.getElementById('opt_{{ $opt->id }}').checked = true;">
                                            <input class="form-check-input ms-1" type="radio" name="answers[{{ $q->id }}]" id="opt_{{ $opt->id }}" value="{{ $opt->id }}" required>
                                            <label class="form-check-label w-100 ms-2" for="opt_{{ $opt->id }}" style="cursor: pointer;">
                                                {{ $opt->option_text }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
                
                <div class="text-end mt-4 mb-5">
                    <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill fw-bold shadow position-relative overflow-hidden group">
                        <i class="fas fa-save me-2"></i> Final Save & Submit
                    </button>
                </div>
            </div>
        </div>
    </form>
@endif

<style>
.hover-option:hover {
    background-color: var(--bs-primary-bg-subtle) !important;
    border-color: var(--bs-primary) !important;
    transform: translateX(5px);
}
.transition-all {
    transition: all 0.2s ease;
}
.shadow-inner {
    box-shadow: inset 0 2px 4px rgba(0,0,0,.5);
}
</style>

<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.7/ace.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @foreach($quiz->questions as $q)
        @if($q->question_type == 'code')
            var editor_{{ $q->id }} = ace.edit("editor_{{ $q->id }}");
            editor_{{ $q->id }}.setTheme("ace/theme/monokai");
            editor_{{ $q->id }}.session.setMode("ace/mode/{{ $q->language == 'cpp' ? 'c_cpp' : $q->language }}");
            
            // Set initial value in hidden field
            document.getElementById('hidden_{{ $q->id }}').value = editor_{{ $q->id }}.getValue();
            
            // Update hidden field on change
            editor_{{ $q->id }}.session.on('change', function() {
                document.getElementById('hidden_{{ $q->id }}').value = editor_{{ $q->id }}.getValue();
            });
        @endif
    @endforeach
});

async function runCode(btn, qId, language, withTestCases = false) {
    const editorId = 'editor_' + qId;
    const terminalId = 'terminal_' + qId;
    const editor = ace.edit(editorId);
    const code = editor.getValue();
    const terminal = document.getElementById(terminalId);
    
    if(!code || code.trim() === '') {
        terminal.innerHTML = '<span class="text-danger">Error: Code array is empty! Cannot run.</span>';
        return;
    }
    
    // Map common languages to Piston equivalents
    let pistonLang = language;
    let version = "*";
    if(language === 'cpp') { pistonLang = 'c++'; }
    else if(language === 'java') { version = "15.0.2"; }
    else if(language === 'php') { version = "8.2.3"; }
    else if(language === 'python') { version = "3.10.0"; }
    else if(language === 'javascript') { version = "18.15.0"; }
    
    let originalText = btn.innerHTML;
    if (withTestCases) {
        terminal.innerHTML = '<span class="text-warning">Saving code and running compilation with test cases... <i class="fas fa-spinner fa-spin ms-2"></i></span>';
    } else {
        terminal.innerHTML = '<span class="text-warning">Running compilation matrix locally... please wait... <i class="fas fa-spinner fa-spin ms-2"></i></span>';
    }
    btn.innerHTML = '<i class="fas fa-spinner fa-spin border-0"></i> Processing';
    btn.disabled = true;

    // Check if test cases exist for this question to inject into STDIN
    const testCasesNode = document.getElementById('test_cases_' + qId);
    let stdinData = "";
    if (testCasesNode) {
        stdinData = testCasesNode.innerText;
    }

    try {
        const response = await fetch('/api/execute-code', {
            method: 'POST',
            headers: { 
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                api_key: '{{ env("VITE_WEBCONTAINER_API_KEY", "wc_api_www.bhavikpatel180_2ef44332bd5b54bc0e0ee86dd27ddf79") }}',
                language: pistonLang,
                files: [{ content: code }],
                stdin: stdinData
            })
        });
        const result = await response.json();
        
        btn.innerHTML = originalText;
        btn.disabled = false;
        
        if (result.run) {
             let output = result.run.output || (result.compile && result.compile.output ? result.compile.output : "Execution matched blank. (No console output returned)");
             // Sanitize HTML slightly
             output = output.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;");
             terminal.innerHTML = output.replace(/\n/g, '<br>');
             
             if(result.run.code !== 0) {
                 terminal.innerHTML += `<br><br><span class="text-danger">Process exited with code ${result.run.code}</span>`;
             }
        } else {
             terminal.innerHTML = '<span class="text-danger">Syntax Error: ' + (result.message || 'Engine aborted') + '</span>';
        }
    } catch(e) {
        btn.innerHTML = originalText;
        btn.disabled = false;
        terminal.innerHTML = '<span class="text-danger">Network Error: Piston IDE compilation engine took too long to respond.</span>';
    }
}
</script>
@endsection
