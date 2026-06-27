<div class="col-md-6 mb-4">
    <div class="card border-0 shadow-sm glass-card d-flex flex-column" style="border-radius: 12px; overflow: hidden; transition: transform 0.2s ease;">
        <div class="card-header bg-white border-bottom-0 pt-3 pb-2 d-flex justify-content-between align-items-start">
            <span class="fw-bold fs-6 flex-grow-1" style="white-space: pre-wrap; margin-right: 15px;">Q{{ $index + 1 }}. {{ $q->question_text }}</span>
            <span class="badge {{ $q->question_type == 'code' ? 'bg-dark' : 'bg-primary' }} rounded-pill shadow-sm" style="min-width: 75px;">{{ $q->points }} Points</span>
        </div>
        <div class="card-body pt-0 flex-grow-1 d-flex flex-column">
            @if($isCoding)
                <!-- Auto-Correction for mistakenly categorized coding questions -->
                @php 
                    $lang = $q->language ?: (str_contains(strtolower($q->question_text), 'java') ? 'java' : 'python'); 
                    $codeContent = $q->expected_code ?: '';
                    $testCases = $q->test_cases ?: '';
                @endphp

                <div class="p-3 bg-light rounded border border-primary border-opacity-25 mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2 border-bottom pb-2">
                        <div class="text-primary fw-bold small"><i class="fas fa-laptop-code me-1"></i> Developer Environment ({{ strtoupper($lang) }})</div>
                        <button class="btn btn-sm btn-dark rounded-pill shadow-sm" onclick="runFacultyCode({{ $q->id }}, '{{ $lang }}')" id="run-btn-{{ $q->id }}" {{ empty($codeContent) ? 'disabled' : '' }}>
                            <i class="fas fa-play me-1 text-success"></i> Run Solution
                        </button>
                    </div>
                    
                    <div class="mt-2">
                        <label class="small fw-bold text-success"><i class="fas fa-check-double me-1"></i> Expected Solution Data</label>
                        @if(empty($codeContent))
                            <div class="alert alert-warning py-2 small mb-2 border-0 shadow-sm"><i class="fas fa-exclamation-triangle me-1"></i> No expected code was provided for this question. Edit question to add it.</div>
                            <div id="code-content-{{ $q->id }}" style="display:none;"></div>
                        @else
                            <div class="bg-dark text-success p-2 mt-1 rounded small overflow-auto w-100 shadow-inner" style="font-family: monospace; max-height: 150px; white-space: pre-wrap; font-size: 12px;" id="code-content-{{ $q->id }}">{{ $codeContent }}</div>
                        @endif
                    </div>
                    
                    <div class="mt-2">
                        <label class="small fw-bold text-warning"><i class="fas fa-vial me-1"></i> Test Cases (Stdin)</label>
                        @if(empty($testCases))
                            <div class="text-muted small fst-italic mb-2 ps-1">No test cases assigned.</div>
                            <div id="test-cases-{{ $q->id }}" style="display:none;"></div>
                        @else
                            <div class="bg-white text-secondary border border-warning border-opacity-50 p-2 mt-1 rounded small overflow-auto w-100 shadow-sm" style="font-family: monospace; max-height: 100px; white-space: pre-wrap; font-size: 12px;" id="test-cases-{{ $q->id }}">{{ $testCases }}</div>
                        @endif
                    </div>

                    <div class="mt-3 text-dark small fw-bold" style="display:none;" id="output-label-{{ $q->id }}"><i class="fas fa-terminal text-info me-1"></i> Execution Output:</div>
                    <div class="bg-black text-light p-2 mt-1 rounded small overflow-auto w-100 border border-info border-opacity-50 shadow-inner" style="font-family: monospace; max-height: 150px; white-space: pre-wrap; font-size: 12px; display:none;" id="output-box-{{ $q->id }}"></div>
                </div>

            @elseif($q->question_type == 'vsq' || $q->question_type == 'long')
                <div class="mt-3 p-2 bg-light rounded border-start border-4 border-info text-muted small shadow-sm"><i class="fas fa-pen me-2"></i> Subjective Assessment (Requires Manual Grading)</div>
            @else
                <ul class="list-group list-group-flush small rounded-bottom mt-2 border w-100">
                    @foreach($q->options as $opt)
                        <li class="list-group-item {{ $opt->is_correct ? 'bg-success-subtle fw-bold text-success' : 'text-muted' }} border-light py-1">
                            @if($opt->is_correct) <i class="fas fa-check-circle me-2"></i> @else <i class="far fa-circle me-2"></i> @endif
                            {{ $opt->option_text }}
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="card-footer bg-white border-top-0 pt-0 d-flex justify-content-end gap-2">
            <button type="button" class="btn btn-sm btn-outline-primary px-3 shadow-sm rounded-pill" onclick="editQuestion({{ $q->id }}, {{ json_encode([
                'category' => $q->question_type,
                'points' => $q->points,
                'question_text' => $q->question_text,
                'language' => $q->language ?? (str_contains(strtolower($q->question_text), 'java') ? 'java' : 'python'),
                'expected_code' => $q->expected_code ?? '',
                'test_cases' => $q->test_cases ?? '',
                'options' => $q->options->map(fn($o) => ['text' => $o->option_text, 'correct' => $o->is_correct])
            ]) }})"><i class="fas fa-edit me-1"></i> Edit</button>
            <form action="/admin/quiz/{{ $quiz->id }}/questions/{{ $q->id }}/delete" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to permanently delete this mapping?');">
                @csrf
                <button type="submit" class="btn btn-sm btn-outline-danger px-3 shadow-sm rounded-pill"><i class="fas fa-trash me-1"></i> Delete Content</button>
            </form>
        </div>
    </div>
</div>
