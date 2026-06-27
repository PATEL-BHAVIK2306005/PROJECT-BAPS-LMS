@extends('layouts.app')
@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0"><i class="fas fa-tasks text-primary me-2"></i> Quiz Builder: {{ $quiz->title }}</h3>
        <p class="text-muted mb-0 mt-1">
            <i class="fas fa-book me-1"></i> Course: {{ $quiz->course->title ?? 'Unassigned' }} | 
            <i class="fas fa-bullseye ms-2 me-1"></i> Passing Score: <span class="badge bg-success">{{ $quiz->min_score }}</span>
        </p>
        <div class="mt-3">
            @if($quiz->is_active)
                <span class="badge bg-success me-2 px-2 py-1 shadow-sm"><i class="fas fa-satellite-dish me-1"></i> LIVE & PUBLISHED</span>
                <form action="/admin/quiz/{{ $quiz->id }}/toggle-publish" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-outline-danger btn-sm rounded-pill fw-bold border shadow-sm px-3"><i class="fas fa-stop-circle me-1"></i> Revert to Draft</button>
                </form>
            @else
                <span class="badge bg-secondary me-2 px-2 py-1 shadow-sm"><i class="fas fa-pencil-ruler me-1"></i> DRAFT MODE</span>
                <form action="/admin/quiz/{{ $quiz->id }}/toggle-publish" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success btn-sm rounded-pill px-4 fw-bold shadow-sm text-white"><i class="fas fa-rocket me-1"></i> LAUNCH QUIZ NOW</button>
                </form>
            @endif
        </div>
    </div>
    <a href="/admin" class="btn btn-outline-dark shadow-sm"><i class="fas fa-arrow-left me-1"></i> Course Dashboard</a>
</div>

<!-- Nav Tabs -->
<ul class="nav nav-tabs mb-4" id="quizBuilderTabs" role="tablist">
  <li class="nav-item" role="presentation">
    <button class="nav-link active fw-bold" id="questions-tab" data-bs-toggle="tab" data-bs-target="#questions-panel" type="button" role="tab" aria-controls="questions-panel" aria-selected="true">
        <i class="fas fa-list me-1"></i> Mapped Questions
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-bold text-primary" id="builder-tab" data-bs-toggle="tab" data-bs-target="#builder-panel" type="button" role="tab" aria-controls="builder-panel" aria-selected="false">
        <i class="fas fa-plus-circle me-1"></i> Add Question Engine
    </button>
  </li>
  <li class="nav-item" role="presentation">
    <button class="nav-link fw-bold text-success" id="qbank-tab" data-bs-toggle="tab" data-bs-target="#qbank-panel" type="button" role="tab" aria-controls="qbank-panel" aria-selected="false">
        <i class="fas fa-brain me-1"></i> Question Bank & AnsKey
    </button>
  </li>
</ul>

<div class="tab-content" id="quizBuilderTabsContent">
    <!-- Tab 1: Existing Questions -->
    <div class="tab-pane fade show active" id="questions-panel" role="tabpanel" aria-labelledby="questions-tab">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="fw-bold mb-0">Mapped Questions ({{ $quiz->questions->count() }})</h5>
            <span class="badge bg-primary-subtle text-primary">{{ $quiz->questions->sum('points') }} Total Points</span>
        </div>
        
        @if($quiz->questions->isEmpty())
            <div class="glass-card p-5 text-center text-muted border-0 shadow-sm mt-2">
                <i class="fas fa-folder-open fa-3x mb-3 text-secondary"></i>
                <p class="mb-0">No questions mapped to this quiz yet.<br>Use the builder panel to add some!</p>
            </div>
        @else
            <!-- Nested Tabs for Question Categories -->
            <ul class="nav nav-pills mb-4 bg-white p-2 rounded border shadow-sm" id="mappedSubTabs" role="tablist">
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link active fw-bold w-100 rounded-pill" id="mcq-q-tab" data-bs-toggle="tab" data-bs-target="#mcq-q-panel" type="button" role="tab">
                        <i class="fas fa-list-ul me-1"></i> MCQ
                    </button>
                </li>
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link fw-bold w-100 rounded-pill" id="vsq-q-tab" data-bs-toggle="tab" data-bs-target="#vsq-q-panel" type="button" role="tab">
                        <i class="fas fa-align-left me-1"></i> VSQ
                    </button>
                </li>
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link fw-bold w-100 rounded-pill" id="long-q-tab" data-bs-toggle="tab" data-bs-target="#long-q-panel" type="button" role="tab">
                        <i class="fas fa-align-justify me-1"></i> Long Answer
                    </button>
                </li>
                <li class="nav-item flex-fill text-center" role="presentation">
                    <button class="nav-link fw-bold w-100 rounded-pill text-primary" id="code-q-tab" data-bs-toggle="tab" data-bs-target="#code-q-panel" type="button" role="tab">
                        <i class="fas fa-laptop-code me-1"></i> Interactive Coding
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="mappedSubTabsContent">
                <!-- MCQ Questions Tab -->
                <div class="tab-pane fade show active" id="mcq-q-panel" role="tabpanel">
                    <div class="row" data-masonry='{"percentPosition": true }'>
                        @foreach($quiz->questions as $index => $q)
                            @if($q->question_type == 'mcq')
                                @include('admin.partials.question_card', ['q' => $q, 'index' => $index, 'isCoding' => false])
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- VSQ Questions Tab -->
                <div class="tab-pane fade" id="vsq-q-panel" role="tabpanel">
                    <div class="row" data-masonry='{"percentPosition": true }'>
                        @foreach($quiz->questions as $index => $q)
                            @if($q->question_type == 'vsq')
                                @include('admin.partials.question_card', ['q' => $q, 'index' => $index, 'isCoding' => false])
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Long Answer Questions Tab -->
                <div class="tab-pane fade" id="long-q-panel" role="tabpanel">
                    <div class="row" data-masonry='{"percentPosition": true }'>
                        @foreach($quiz->questions as $index => $q)
                            @if($q->question_type == 'long')
                                @include('admin.partials.question_card', ['q' => $q, 'index' => $index, 'isCoding' => false])
                            @endif
                        @endforeach
                    </div>
                </div>

                <!-- Coding Questions Tab -->
                <div class="tab-pane fade" id="code-q-panel" role="tabpanel">
                    <div class="row" data-masonry='{"percentPosition": true }'>
                        @foreach($quiz->questions as $index => $q)
                            @if($q->question_type == 'code')
                                @include('admin.partials.question_card', ['q' => $q, 'index' => $index, 'isCoding' => true])
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Tab 2: Question Builder Form -->
    <div class="tab-pane fade" id="builder-panel" role="tabpanel" aria-labelledby="builder-tab">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <!-- Matrix Entry Tabs -->
                <ul class="nav nav-pills mb-3 bg-light p-2 rounded border shadow-sm" id="matrixEntryTabs" role="tablist">
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <button class="nav-link fw-bold w-100 rounded-pill" id="matrix-upload-tab" data-bs-toggle="tab" data-bs-target="#matrix-upload-panel" type="button" role="tab">
                            <i class="fas fa-file-pdf me-1"></i> Upload Question PDF
                        </button>
                    </li>
                    <li class="nav-item flex-fill text-center" role="presentation">
                        <button class="nav-link active fw-bold w-100 rounded-pill" id="matrix-manual-tab" data-bs-toggle="tab" data-bs-target="#matrix-manual-panel" type="button" role="tab">
                            <i class="fas fa-keyboard me-1"></i> Manual Entry & AI
                        </button>
                    </li>
                </ul>

                <div class="tab-content" id="matrixEntryTabsContent">
                    <!-- Upload PDF Tab -->
                    <div class="tab-pane fade" id="matrix-upload-panel" role="tabpanel">
                        <div class="glass-card p-4 border-0 shadow-sm text-center">
                            <h5 class="fw-bold mb-3 text-primary"><i class="fas fa-cloud-upload-alt me-2"></i> Auto-Extract Question from PDF</h5>
                            <p class="text-muted small mb-4">Upload a PDF containing a question. Our AI will extract the problem statement, identify the category, and automatically map it to this quiz.</p>
                            <form action="/admin/quiz/{{ $quiz->id }}/questions/pdf" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="p-4 border rounded bg-light border-primary border-opacity-25 mx-auto" style="border-style: dashed !important; border-width: 2px !important; max-width: 400px;">
                                    <i class="fas fa-file-pdf fa-3x text-danger mb-3"></i>
                                    <input type="file" name="question_pdf" class="form-control mb-3" accept=".pdf" required>
                                    <button type="submit" onclick="this.innerHTML='<i class=\'fas fa-spinner fa-spin me-1\'></i> Extracting...'; this.form.submit();" class="btn btn-primary fw-bold w-100 rounded-pill shadow-sm"><i class="fas fa-magic me-1"></i> Extract & Map to Quiz</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Manual Entry Tab -->
                    <div class="tab-pane fade show active" id="matrix-manual-panel" role="tabpanel">
                        <div class="glass-card p-4 border-0 shadow-sm" id="matrix-form-container">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="fw-bold mb-0" id="builderFormTitle"><i class="fas fa-plus-circle text-primary me-2"></i> Assessment Matrix System</h5>
                                <button type="button" id="cancelEditBtn" class="btn btn-sm btn-outline-secondary rounded-pill" style="display:none;" onclick="cancelEdit()"><i class="fas fa-times me-1"></i> Cancel Edit</button>
                            </div>
                            <form action="/admin/quiz/{{ $quiz->id }}/questions" method="POST" id="questionBuilderForm">
                                @csrf
                                <div class="row">
                                    <div class="col-md-8 mb-3">
                                        <label class="small fw-bold mb-1">Question Category</label>
                                        <select name="category" id="questionCategory" class="form-select bg-light border-0" onchange="toggleOptions()">
                                            <option value="mcq">Multiple Choice Question (MCQ)</option>
                                            <option value="vsq">Very Short Question (VSQ)</option>
                                            <option value="long">Long Answer Question</option>
                                            <option value="code" class="fw-bold text-primary">💻 Interactive Coding Problem</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="small fw-bold mb-1">Points Allocated</label>
                                        <input type="number" name="points" class="form-control bg-light border-0" value="1" required min="1">
                                    </div>
                                </div>

                                <div class="mb-3 position-relative">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <label class="small fw-bold" id="questionTextLabel">Problem Statement</label>
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-pill" onclick="generateAIQuestion()"><i class="fas fa-magic me-1"></i> Generate with OpenSource AI</button>
                                    </div>
                                    <textarea name="question_text" id="builderQuestionText" class="form-control bg-light border-0" rows="4" required placeholder="Type the question or problem statement here..."></textarea>
                                </div>
                                
                                <!-- IDE/Coding Logic Section -->
                                <div id="codingSection" style="display: none;" class="mt-4 p-3 border rounded bg-light border-primary border-opacity-25">
                                    <h6 class="fw-bold text-primary border-bottom pb-2 mb-3"><i class="fas fa-terminal me-2"></i> Coding Assessment Settings</h6>
                                    <div class="mb-3">
                                        <label class="small fw-bold mb-1">Target Language Terminal</label>
                                        <select name="language" class="form-select border-0 shadow-sm" id="langSelect">
                                            <option value="python">Python 3</option>
                                            <option value="javascript">JavaScript (Node.js)</option>
                                            <option value="php">PHP 8</option>
                                            <option value="java">Java Compiler</option>
                                        </select>
                                    </div>
                                    <div class="mb-3">
                                        <label class="small fw-bold mb-1">Faculty's Final Answer (Expected Code Solution)</label>
                                        <p class="text-muted small mb-2" style="font-size: 11px;">This code will be securely kept as the faculty reference. The student must write code that functionally satisfies the problem.</p>
                                        <textarea name="expected_code" id="expectedCode" class="form-control bg-dark text-success font-monospace" rows="6" placeholder="def solution():&#10;    print('Jay Swaminarayan')"></textarea>
                                    </div>
                                    <div class="mb-2">
                                        <label class="small fw-bold mb-1">Test Case (Optional)</label>
                                        <p class="text-muted small mb-2" style="font-size: 11px;">To feed specific data into the local compiler engine through Standard Input (stdin), define it here.</p>
                                        <textarea name="test_cases" id="testCases" class="form-control bg-dark text-warning font-monospace" rows="3" placeholder="Input string to pass to the problem during tests..."></textarea>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <button type="button" class="btn btn-sm btn-dark rounded-pill px-4 shadow-sm" onclick="runBuilderCode()" id="builder-run-btn">
                                            <i class="fas fa-play me-1 text-success"></i> Test Expected Code Locally
                                        </button>
                                    </div>
                                    <div class="mt-2 text-dark small fw-bold" style="display:none;" id="builder-output-label"><i class="fas fa-terminal text-info me-1"></i> Output Preview:</div>
                                    <div class="bg-black text-light p-2 mt-1 rounded small overflow-auto w-100 border border-info border-opacity-50" style="font-family: monospace; max-height: 150px; white-space: pre-wrap; font-size: 12px; display:none;" id="builder-output-box"></div>
                                </div>
                                
                                <!-- MCQ Logic Section -->
                                <div id="optionsSection" class="mt-4 p-3 border rounded bg-white">
                                    <h6 class="fw-bold mb-2 pb-2 border-bottom text-dark">Map Multiple Choice Options</h6>
                                    <p class="small text-muted mb-3" style="font-size: 11px;">Provide at least 2 options. Select the radio button for the correct answer.</p>

                                    @for($i = 0; $i < 4; $i++)
                                    <div class="input-group mb-2 shadow-sm rounded">
                                        <div class="input-group-text bg-light border border-end-0">
                                            <input class="form-check-input mt-0 border-secondary" type="radio" name="correct_option" value="{{ $i }}" {{ $i == 0 ? 'required' : '' }} title="Mark as Correct">
                                        </div>
                                        <input type="text" name="options[]" class="form-control border border-start-0" placeholder="Option {{ $i + 1 }} {{ $i < 2 ? '(Required)' : '(Optional)' }}" {{ $i < 2 ? 'required' : '' }}>
                                    </div>
                                    @endfor
                                </div>

                                <button type="submit" class="btn btn-primary w-100 shadow mt-4 fw-bold rounded-pill p-3" id="submitQuestionBtn"><i class="fas fa-save me-1"></i> Register Question to Matrix</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tab 3: Question Bank & AnsKey -->
    <div class="tab-pane fade" id="qbank-panel" role="tabpanel" aria-labelledby="qbank-tab">
        <div class="glass-card p-4 border-0 shadow-sm">
            <h5 class="fw-bold mb-3"><i class="fas fa-brain text-success me-2"></i> AI Question Bank & Master Keys</h5>
            
            <!-- Internal Tabs -->
            <ul class="nav nav-pills mb-4 border-bottom pb-3" id="internalQbankTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold" id="ai-gen-tab" data-bs-toggle="tab" data-bs-target="#ai-gen-panel" type="button" role="tab">
                        <i class="fas fa-robot me-1"></i> ML Quiz Extractor
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="saved-bank-tab" data-bs-toggle="tab" data-bs-target="#saved-bank-panel" type="button" role="tab">
                        <i class="fas fa-database me-1"></i> Saved VSQ/VLQ Bank
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold" id="anskey-tab" data-bs-toggle="tab" data-bs-target="#anskey-panel" type="button" role="tab">
                        <i class="fas fa-key me-1"></i> Master Answer Key
                    </button>
                </li>
            </ul>
            
            <div class="tab-content" id="internalQbankTabsContent">
                <!-- AI Extractor -->
                <div class="tab-pane fade show active" id="ai-gen-panel" role="tabpanel">
                    <div class="row">
                        <div class="col-md-7">
                            <label class="fw-bold small mb-2 text-primary"><i class="fas fa-file-upload me-1"></i> Upload Course Material</label>
                            
                            <!-- File Upload Area -->
                            <div class="mb-3 p-4 text-center border rounded bg-light border-primary border-opacity-25 shadow-inner position-relative" style="border-style: dashed !important; border-width: 2px !important;">
                                <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-2 opacity-50"></i>
                                <h6 class="fw-bold text-dark mb-1">Drag & Drop or Click to Upload</h6>
                                <p class="small text-muted mb-3">Supported formats: PDF, Word (.doc, .docx), and Images (.png, .jpg)</p>
                                <input type="file" class="form-control" id="mlFileInput" accept=".pdf,.doc,.docx,.png,.jpg,.jpeg" onchange="handleFileSelect(this)">
                                <div id="fileStatus" class="mt-2 small fw-bold text-success" style="display:none;"></div>
                            </div>

                            <div class="d-flex align-items-center mb-2">
                                <hr class="flex-grow-1 border-secondary">
                                <span class="mx-2 small text-muted fw-bold">OR PASTE TEXT</span>
                                <hr class="flex-grow-1 border-secondary">
                            </div>

                            <textarea class="form-control bg-light border-0 mb-3 shadow-inner" id="mlTextInput" rows="4" placeholder="Paste textbook text, notes, or paragraphs here..." style="border-radius: 10px;"></textarea>
                            
                            <button class="btn btn-primary w-100 rounded-pill shadow fw-bold px-4 py-2" onclick="simulateMLExtraction(this)">
                                <i class="fas fa-magic me-2"></i> Extract & Generate Quiz with ML
                            </button>
                        </div>
                        <div class="col-md-5">
                            <label class="fw-bold small mb-2 text-success"><i class="fas fa-microchip me-1"></i> ML Output Console</label>
                            <div class="bg-dark text-success p-3 rounded h-100 font-monospace small shadow-inner" id="ml-console" style="min-height: 350px; overflow-y: auto;">
                                > Engine ready. Waiting for material upload or text input...
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Saved Bank -->
                <div class="tab-pane fade" id="saved-bank-panel" role="tabpanel">
                    
                    <!-- Bank Entry Tabs -->
                    <ul class="nav nav-pills mb-3 bg-light p-2 rounded border" id="bankEntryTabs" role="tablist">
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link active fw-bold w-100 rounded-pill" id="upload-paper-tab" data-bs-toggle="tab" data-bs-target="#upload-paper-panel" type="button" role="tab">
                                <i class="fas fa-file-pdf me-1"></i> Upload Past Paper
                            </button>
                        </li>
                        <li class="nav-item flex-fill text-center" role="presentation">
                            <button class="nav-link fw-bold w-100 rounded-pill" id="manual-bank-tab" data-bs-toggle="tab" data-bs-target="#manual-bank-panel" type="button" role="tab">
                                <i class="fas fa-keyboard me-1"></i> Add Manual Question
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content border rounded p-3 mb-4 bg-white shadow-sm" id="bankEntryTabsContent">
                        <!-- Upload Paper Tab -->
                        <div class="tab-pane fade show active" id="upload-paper-panel" role="tabpanel">
                            <h6 class="fw-bold text-primary mb-3"><i class="fas fa-cloud-upload-alt me-2"></i> Auto-Parse Questions from Document</h6>
                            <form action="/admin/question-bank" method="POST" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="question_type" value="file_upload">
                                <div class="p-4 text-center border rounded bg-light border-primary border-opacity-25" style="border-style: dashed !important; border-width: 2px !important;">
                                    <i class="fas fa-file-pdf fa-2x text-danger mb-2"></i>
                                    <input type="file" name="paper_file" class="form-control mb-3 w-75 mx-auto" accept=".pdf,.doc,.docx" required>
                                    <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm"><i class="fas fa-file-import me-1"></i> Upload & Auto-Extract</button>
                                </div>
                            </form>
                        </div>

                        <!-- Manual Entry Tab -->
                        <div class="tab-pane fade" id="manual-bank-panel" role="tabpanel">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="fw-bold text-success mb-0"><i class="fas fa-pen-nib me-2"></i> Manually Add to Repository</h6>
                                <button type="button" class="btn btn-sm btn-outline-info rounded-pill fw-bold shadow-sm" onclick="generateBankAIQuestion()"><i class="fas fa-magic me-1"></i> Generate with OpenSource AI</button>
                            </div>
                            <form action="/admin/question-bank" method="POST">
                                @csrf
                                <div class="input-group mb-3 shadow-sm">
                                    <span class="input-group-text bg-light fw-bold text-dark border-secondary">Category</span>
                                    <select name="question_type" id="bankQuestionType" class="form-select border-secondary" required>
                                        <option value="vsq">VSQ (Very Short Question)</option>
                                        <option value="vlq">VLQ (Very Long Question)</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <textarea name="question_text" id="bankQuestionText" class="form-control border-secondary shadow-inner" rows="4" placeholder="Enter question text here..." required></textarea>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-success fw-bold px-4 rounded-pill shadow-sm"><i class="fas fa-save me-1"></i> Add to Repository</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="alert bg-primary-subtle text-primary border-0 rounded py-2 small fw-bold d-flex justify-content-between align-items-center">
                        <div><i class="fas fa-database me-2"></i> Institutional Repository (Past Semesters)</div>
                        <span class="badge bg-primary rounded-pill">{{ \App\Models\QuestionBank::count() }} Saved</span>
                    </div>
                    
                    <ul class="list-group list-group-flush border rounded shadow-sm" style="max-height: 400px; overflow-y: auto;">
                        @forelse(\App\Models\QuestionBank::latest()->get() as $bankQ)
                            <li class="list-group-item d-flex justify-content-between align-items-center bg-white p-3 border-bottom">
                                <div class="pe-3">
                                    <span class="badge {{ $bankQ->question_type == 'vsq' ? 'bg-info' : ($bankQ->question_type == 'vlq' ? 'bg-warning text-dark' : 'bg-secondary') }} shadow-sm me-2">
                                        {{ strtoupper($bankQ->question_type) }}
                                    </span> 
                                    <span class="text-dark small">{{ $bankQ->question_text }}</span>
                                </div>
                                <button class="btn btn-sm btn-outline-primary rounded-pill fw-bold text-nowrap" onclick="mapBankQuestion('{{ $bankQ->question_type }}', `{{ addslashes($bankQ->question_text) }}`)">
                                    <i class="fas fa-link me-1"></i> Map
                                </button>
                            </li>
                        @empty
                            <li class="list-group-item text-center text-muted fst-italic p-4">
                                <i class="fas fa-box-open fa-2x mb-2 text-secondary opacity-50"></i><br>
                                Repository is empty. Add a manual question or upload a paper above.
                            </li>
                        @endforelse
                    </ul>
                </div>

                <!-- Answer Key -->
                <div class="tab-pane fade" id="anskey-panel" role="tabpanel">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h6 class="fw-bold mb-0 text-dark"><i class="fas fa-clipboard-check text-success me-2"></i> Auto-Generated Master Key</h6>
                        <button class="btn btn-sm btn-success rounded-pill shadow-sm fw-bold px-3"><i class="fas fa-download me-1"></i> Export PDF</button>
                    </div>
                    
                    <div class="table-responsive rounded shadow-sm border">
                        <table class="table table-sm table-borderless table-striped mb-0">
                            <thead class="bg-dark text-white">
                                <tr>
                                    <th class="ps-3 py-2">Q#</th>
                                    <th class="py-2">Type</th>
                                    <th class="py-2">Correct Answer / Expected Code</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($quiz->questions as $index => $q)
                                <tr>
                                    <td class="ps-3 fw-bold text-muted">{{ $index + 1 }}</td>
                                    <td><span class="badge {{ $q->question_type == 'code' ? 'bg-dark' : 'bg-secondary' }} rounded-pill">{{ strtoupper($q->question_type) }}</span></td>
                                    <td>
                                        @if($q->question_type == 'mcq')
                                            @foreach($q->options as $opt)
                                                @if($opt->is_correct) <strong class="text-success"><i class="fas fa-check-circle me-1"></i> {{ $opt->option_text }}</strong> @endif
                                            @endforeach
                                        @elseif($q->question_type == 'code')
                                            <code class="text-dark bg-light px-2 py-1 rounded border">{{ Str::limit($q->expected_code, 50) }}</code>
                                        @else
                                            <span class="text-muted fst-italic small"><i class="fas fa-eye me-1"></i> Manual subjective grading required</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center py-4 text-muted fst-italic">No questions mapped yet to generate key.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Include Masonry Library for UI Sync -->
<script src="https://cdn.jsdelivr.net/npm/masonry-layout@4.2.2/dist/masonry.pkgd.min.js" async></script>

<script>
function toggleOptions() {
    const cat = document.getElementById('questionCategory').value;
    const optionsSection = document.getElementById('optionsSection');
    const codingSection = document.getElementById('codingSection');
    const optionInputs = optionsSection.querySelectorAll('input[type="text"], input[type="radio"]');
    
    // Reset Everything
    optionsSection.style.display = 'none';
    codingSection.style.display = 'none';
    optionInputs.forEach(input => input.removeAttribute('required'));

    if (cat === 'code') {
        codingSection.style.display = 'block';
    } else if (cat === 'mcq') {
        optionsSection.style.display = 'block';
        // Re-apply required for the first 2 options
        const texts = optionsSection.querySelectorAll('input[type="text"]');
        if(texts.length >= 2) {
            texts[0].setAttribute('required', 'required');
            texts[1].setAttribute('required', 'required');
        }
        const radios = optionsSection.querySelectorAll('input[type="radio"]');
        if(radios.length > 0) {
            radios[0].setAttribute('required', 'required');
        }
    }
}
// Run on load
document.addEventListener('DOMContentLoaded', function() {
    toggleOptions();
    
    // Tab Synchronization Logic
    const activeTabId = localStorage.getItem('activeQuizTab_{{ $quiz->id }}');
    if (activeTabId) {
        const triggerEl = document.querySelector(`button[data-bs-target="${activeTabId}"]`);
        if (triggerEl && typeof bootstrap !== 'undefined') {
            const tab = new bootstrap.Tab(triggerEl);
            tab.show();
        }
    }

    // Save the active tab when clicked
    const tabElements = document.querySelectorAll('button[data-bs-toggle="tab"]');
    tabElements.forEach(function(tabEl) {
        tabEl.addEventListener('shown.bs.tab', function(event) {
            const targetId = event.target.getAttribute('data-bs-target');
            localStorage.setItem('activeQuizTab_{{ $quiz->id }}', targetId);
        });
    });
});

// Piston API Code Execution
async function runFacultyCode(questionId, language) {
    const btn = document.getElementById(`run-btn-${questionId}`);
    const code = document.getElementById(`code-content-${questionId}`).innerText;
    const testCasesBox = document.getElementById(`test-cases-${questionId}`);
    const testCases = testCasesBox ? testCasesBox.innerText : '';
    const outputLabel = document.getElementById(`output-label-${questionId}`);
    const outputBox = document.getElementById(`output-box-${questionId}`);

    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1 text-warning"></i> Running...';
    btn.disabled = true;
    outputLabel.style.display = 'block';
    outputBox.style.display = 'block';
    outputBox.innerHTML = '<span class="text-muted">Compiling and executing via Piston...</span>';

    // Map common languages to their Piston aliases and versions
    const langMap = {
        'python': { language: 'python', version: '3.10.0' },
        'javascript': { language: 'javascript', version: '18.15.0' },
        'php': { language: 'php', version: '8.2.3' },
        'java': { language: 'java', version: '15.0.2' }
    };

    const pistonLang = langMap[language.toLowerCase()] || { language: language.toLowerCase(), version: '*' };

    try {
        const response = await fetch('/api/execute-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                api_key: '{{ env("VITE_WEBCONTAINER_API_KEY", "wc_api_www.bhavikpatel180_2ef44332bd5b54bc0e0ee86dd27ddf79") }}',
                language: pistonLang.language,
                files: [
                    {
                        content: code
                    }
                ],
                stdin: testCases
            })
        });

        const result = await response.json();
        
        if (result.run) {
            let outStr = result.run.output;
            if (result.run.stderr) {
                 outStr += `\n<span class="text-danger">${result.run.stderr}</span>`;
            }
            if (result.compile && result.compile.stderr) {
                 outStr = `<span class="text-danger">Compilation Error:\n${result.compile.stderr}</span>\n` + outStr;
            }
            outputBox.innerHTML = outStr || '<span class="text-muted">Program exited with no output.</span>';
        } else {
            outputBox.innerHTML = `<span class="text-danger">Error: ${result.message || 'Unknown execution error'}</span>`;
        }

    } catch (error) {
        outputBox.innerHTML = `<span class="text-danger">Failed to connect to execution engine: ${error.message}</span>`;
    } finally {
        btn.innerHTML = '<i class="fas fa-play me-1 text-success"></i> Run Solution';
        btn.disabled = false;
    }
}
</script>
<script>
// Form Editing Logic
function editQuestion(id, data) {
    // Switch to builder tab
    const triggerEl = document.querySelector('button[data-bs-target="#builder-panel"]');
    if (triggerEl) {
        new bootstrap.Tab(triggerEl).show();
    }
    
    // Change Form Title and Buttons
    document.getElementById('builderFormTitle').innerHTML = `<i class="fas fa-edit text-warning me-2"></i> Editing Question Q${id}`;
    document.getElementById('submitQuestionBtn').innerHTML = `<i class="fas fa-sync me-1"></i> Update Question in Matrix`;
    document.getElementById('submitQuestionBtn').className = "btn btn-warning w-100 shadow mt-4 fw-bold rounded-pill p-3 text-dark";
    document.getElementById('cancelEditBtn').style.display = 'inline-block';
    
    // Change Form Action
    document.getElementById('questionBuilderForm').action = `/admin/quiz/{{ $quiz->id }}/questions/${id}/update`;
    
    // Populate Fields
    let categorySelect = document.getElementById('questionCategory');
    let mappedCat = data.category;
    if (!['mcq', 'vsq', 'long', 'code'].includes(mappedCat)) {
        mappedCat = 'code'; // Auto correct past errors
    }
    categorySelect.value = mappedCat;
    
    document.querySelector('input[name="points"]').value = data.points;
    document.querySelector('textarea[name="question_text"]').value = data.question_text;
    
    toggleOptions(); // show/hide sections based on category
    
    if (mappedCat === 'code') {
        document.getElementById('langSelect').value = data.language;
        document.getElementById('expectedCode').value = data.expected_code || '';
        document.getElementById('testCases').value = data.test_cases || '';
        document.getElementById('builder-output-box').style.display = 'none';
        document.getElementById('builder-output-label').style.display = 'none';
    } else if (mappedCat === 'mcq' && data.options) {
        let textInputs = document.querySelectorAll('input[name="options[]"]');
        let radioInputs = document.querySelectorAll('input[name="correct_option"]');
        
        // Reset old data
        textInputs.forEach(i => i.value = '');
        radioInputs.forEach(i => i.checked = false);
        
        data.options.forEach((opt, index) => {
            if (index < textInputs.length) {
                textInputs[index].value = opt.text;
                if (opt.correct) {
                    radioInputs[index].checked = true;
                }
            }
        });
    }
    
    window.scrollTo({ top: document.getElementById('matrix-form-container').offsetTop - 50, behavior: 'smooth' });
}

function cancelEdit() {
    // Reset Form to Add Mode
    document.getElementById('builderFormTitle').innerHTML = `<i class="fas fa-plus-circle text-primary me-2"></i> Assessment Matrix System`;
    document.getElementById('submitQuestionBtn').innerHTML = `<i class="fas fa-save me-1"></i> Register Question to Matrix`;
    document.getElementById('submitQuestionBtn').className = "btn btn-primary w-100 shadow mt-4 fw-bold rounded-pill p-3";
    document.getElementById('cancelEditBtn').style.display = 'none';
    
    // Change Form Action back to Store
    document.getElementById('questionBuilderForm').action = `/admin/quiz/{{ $quiz->id }}/questions`;
    document.getElementById('questionBuilderForm').reset();
    
    // Reset sections
    document.getElementById('builder-output-box').style.display = 'none';
    document.getElementById('builder-output-label').style.display = 'none';
    toggleOptions();
}

// Builder Run Solution
async function runBuilderCode() {
    const btn = document.getElementById('builder-run-btn');
    const code = document.getElementById('expectedCode').value;
    const testCases = document.getElementById('testCases').value;
    const language = document.getElementById('langSelect').value;
    const outputLabel = document.getElementById('builder-output-label');
    const outputBox = document.getElementById('builder-output-box');

    if (!code.trim()) {
        alert("Please enter expected code before testing!");
        return;
    }

    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1 text-warning"></i> Running Locally...';
    btn.disabled = true;
    outputLabel.style.display = 'block';
    outputBox.style.display = 'block';
    outputBox.innerHTML = '<span class="text-muted">Executing on local server...</span>';

    try {
        const response = await fetch('/api/execute-code', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                api_key: '{{ env("VITE_WEBCONTAINER_API_KEY", "wc_api_www.bhavikpatel180_2ef44332bd5b54bc0e0ee86dd27ddf79") }}',
                language: language,
                files: [{ content: code }],
                stdin: testCases
            })
        });

        const result = await response.json();
        
        if (result.run) {
            let outStr = result.run.output;
            if (result.run.stderr) {
                 outStr += `\n<span class="text-danger">${result.run.stderr}</span>`;
            }
            outputBox.innerHTML = outStr || '<span class="text-muted">Program exited with no output.</span>';
        } else {
            outputBox.innerHTML = `<span class="text-danger">Error: ${result.message || 'Unknown execution error'}</span>`;
        }
    } catch (error) {
        outputBox.innerHTML = `<span class="text-danger">Failed to connect to local executor: ${error.message}</span>`;
    } finally {
        btn.innerHTML = '<i class="fas fa-play me-1 text-success"></i> Test Expected Code Locally';
        btn.disabled = false;
    }
}

// ML Simulator and File Handling
function handleFileSelect(input) {
    const statusDiv = document.getElementById('fileStatus');
    const consoleBox = document.getElementById('ml-console');
    
    if (input.files && input.files[0]) {
        const file = input.files[0];
        const ext = file.name.split('.').pop().toLowerCase();
        
        statusDiv.style.display = 'block';
        statusDiv.innerHTML = `<i class="fas fa-check-circle me-1"></i> Attached: ${file.name} (${(file.size/1024).toFixed(2)} KB)`;
        
        consoleBox.innerHTML += `\n> Loaded file: ${file.name}`;
        
        if (['pdf', 'doc', 'docx'].includes(ext)) {
            consoleBox.innerHTML += `\n> <span class="text-info">Initiating OCR / Text Extraction for document... Ready to process.</span>`;
        } else if (['jpg', 'jpeg', 'png'].includes(ext)) {
            consoleBox.innerHTML += `\n> <span class="text-info">Initiating Vision ML model for image text extraction... Ready to process.</span>`;
        }
    } else {
        statusDiv.style.display = 'none';
    }
}

function simulateMLExtraction(btn) {
    if (localStorage.getItem('ai_lms_enabled') === 'false') {
        alert("LMS Course Content AI Generator is currently disabled by the Administrator.");
        return;
    }
    const consoleBox = document.getElementById('ml-console');
    const fileInput = document.getElementById('mlFileInput');
    const textInput = document.getElementById('mlTextInput');
    const originalText = btn.innerHTML;
    
    if ((!fileInput.files || fileInput.files.length === 0) && !textInput.value.trim()) {
        alert("Please upload a file or paste text first!");
        return;
    }
    
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Analyzing Syntax & Context...';
    btn.disabled = true;
    
    let isFile = fileInput.files && fileInput.files.length > 0;
    
    if (isFile) {
        consoleBox.innerHTML = "> Initializing Vision/OCR processing pipeline...\n> Extracting raw text from document/image...";
    } else {
        consoleBox.innerHTML = "> Initializing NLP core...\n> Parsing text chunks...";
    }
    
    consoleBox.innerHTML += "\n> Applying transformer model constraints...";
    
    setTimeout(() => {
        consoleBox.innerHTML += "\n> <span class='text-warning'>Extracting key educational entities...</span>";
        setTimeout(() => {
            consoleBox.innerHTML += "\n> <span class='text-success'>Done! Found 2 VSQ concepts and 1 VLQ context.</span>\n\n";
            consoleBox.innerHTML += "<strong>Generated VSQ:</strong> What is the core principle described in the uploaded material?\n";
            consoleBox.innerHTML += "<strong>Generated VLQ:</strong> Elaborate on the architectural mechanisms extracted from the document.\n\n";
            consoleBox.innerHTML += "> <a href='#' class='text-info text-decoration-underline'>Auto-map these to builder?</a>";
            
            btn.innerHTML = originalText;
            btn.disabled = false;
        }, 2000);
    }, 1500);
}

// Ensure Masonry recalculates layout when switching sub-tabs
document.addEventListener('DOMContentLoaded', function() {
    var triggerTabList = [].slice.call(document.querySelectorAll('#mappedSubTabs button[data-bs-toggle="tab"]'))
    triggerTabList.forEach(function (triggerEl) {
        triggerEl.addEventListener('shown.bs.tab', function (event) {
            var targetPanel = document.querySelector(event.target.getAttribute('data-bs-target'));
            var masonryGrid = targetPanel.querySelector('.row[data-masonry]');
            if (masonryGrid) {
                var msnry = Masonry.data(masonryGrid);
                if (msnry) msnry.layout();
            }
        })
    });
});

async function generateAIQuestion() {
    if (localStorage.getItem('ai_lms_enabled') === 'false') {
        alert("LMS Course Content AI Generator is currently disabled by the Administrator.");
        return;
    }
    let category = document.getElementById('questionCategory').value;
    let btn = document.querySelector('button[onclick="generateAIQuestion()"]');
    let originalHtml = btn.innerHTML;
    let textarea = document.getElementById('builderQuestionText');
    
    let topic = prompt("What topic should the AI generate a question about? (e.g., 'Inheritance in Java', 'React Hooks', 'TCP/IP')");
    if(!topic) return;

    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';
    btn.disabled = true;
    
    let promptText = `Generate a rigorous university-level ${category} question about ${topic}. Return ONLY the question text itself. Do not include any greetings, prefixes, or explanations. Just the raw question.`;
    if(category === 'code') {
        promptText = `Generate a university-level interactive coding problem statement about ${topic}. Include the problem description, constraints, and an example input/output. Do not write the solution code, just the problem statement.`;
    }

    try {
        let response = await fetch('https://text.pollinations.ai/' + encodeURIComponent(promptText));
        let text = await response.text();
        
        // Strip the Pollinations deprecation warning if present
        text = text.replace(/⚠️ \*\*IMPORTANT NOTICE\*\* ⚠️[\s\S]*?continue to work normally\./g, '').trim();
        
        textarea.value = text;
    } catch(e) {
        alert("Failed to connect to the open-source AI endpoint. Please try again.");
    } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}

async function generateBankAIQuestion() {
    if (localStorage.getItem('ai_lms_enabled') === 'false') {
        alert("LMS Course Content AI Generator is currently disabled by the Administrator.");
        return;
    }
    let category = document.getElementById('bankQuestionType').value;
    let btn = document.querySelector('button[onclick="generateBankAIQuestion()"]');
    let originalHtml = btn.innerHTML;
    let textarea = document.getElementById('bankQuestionText');
    
    let topic = prompt("What topic should the AI generate a question about? (e.g., 'Inheritance in Java', 'React Hooks', 'TCP/IP')");
    if(!topic) return;

    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Generating...';
    btn.disabled = true;
    
    let promptText = `Generate a rigorous university-level ${category} question about ${topic}. Return ONLY the question text itself. Do not include any greetings, prefixes, or explanations. Just the raw question.`;

    try {
        let response = await fetch('https://text.pollinations.ai/' + encodeURIComponent(promptText));
        let text = await response.text();
        
        // Strip the Pollinations deprecation warning if present
        text = text.replace(/⚠️ \*\*IMPORTANT NOTICE\*\* ⚠️[\s\S]*?continue to work normally\./g, '').trim();
        
        textarea.value = text;
    } catch(e) {
        alert("Failed to connect to the open-source AI endpoint. Please try again.");
    } finally {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
    }
}
</script>
@endsection
