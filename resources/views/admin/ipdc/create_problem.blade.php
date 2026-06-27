@extends('layouts.app')
@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center bg-dark rounded-4 p-4 text-white shadow-lg position-relative overflow-hidden" style="background: linear-gradient(135deg, #4f46e5 0%, #312e81 100%);">
            <div class="position-relative z-1">
                <h3 class="fw-bold mb-1"><i class="fas fa-plus-circle text-warning me-2"></i> Assign IPDC HackerRank Problem</h3>
                <p class="mb-0 opacity-75">Create coding challenges for students to solve moral/ethical scenarios via sandbox algorithms.</p>
            </div>
            <i class="fas fa-laptop-code fa-6x position-absolute end-0 bottom-0 opacity-10 me-4 mb-n2"></i>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <form action="/admin/ipdc/hackerrank/store" method="POST" class="card border-0 shadow-sm rounded-4 p-4">
            @csrf
            
            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Problem Title</label>
                    <input name="title" class="form-control rounded-3" placeholder="e.g. Integrity Backlog check program" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Difficulty</label>
                    <select name="difficulty" class="form-select rounded-3">
                        <option>Easy</option>
                        <option>Medium</option>
                        <option>Hard</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Points Awarded</label>
                    <input type="number" name="points" class="form-control rounded-3" value="100" required>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label small fw-bold">Problem Statement (Description)</label>
                <textarea name="description" rows="5" class="form-control rounded-3" placeholder="Describe the task and the scenario clearly..." required></textarea>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Input Format</label>
                    <textarea name="input_format" rows="3" class="form-control rounded-3" placeholder="e.g. A single line containing integer N..."></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Constraints</label>
                    <textarea name="constraints" rows="3" class="form-control rounded-3" placeholder="e.g. 1 <= N <= 10^5"></textarea>
                </div>
                <div class="col-md-4">
                    <label class="form-label small fw-bold">Output Format</label>
                    <textarea name="output_format" rows="3" class="form-control rounded-3" placeholder="e.g. Print true if criteria met, else false..."></textarea>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Sample Input</label>
                    <textarea name="sample_input" rows="3" class="form-control rounded-3" placeholder="Sample Input text..."></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label small fw-bold">Sample Output</label>
                    <textarea name="sample_output" rows="3" class="form-control rounded-3" placeholder="Sample Output text..."></textarea>
                </div>
            </div>

            <!-- Test Cases Evaluator Setup -->
            <div class="card border-light bg-light bg-opacity-50 p-4 rounded-4 mb-4">
                <h5 class="fw-bold mb-3 text-dark d-flex align-items-center">
                    <i class="fas fa-vial text-info me-2"></i> Evaluation Test Cases
                </h5>
                <p class="small text-muted mb-4">Provide the test cases used by the online sandbox judge to compile and evaluate student submissions. The outputs will be compared strictly.</p>

                <div id="testCasesContainer">
                    <div class="row g-3 mb-3 align-items-end test-case-row">
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">Test Input</label>
                            <textarea name="test_inputs[]" rows="2" class="form-control rounded-3" placeholder="Input passed to stdin..." required></textarea>
                        </div>
                        <div class="col-md-5">
                            <label class="form-label small fw-bold text-muted">Expected Output</label>
                            <textarea name="test_outputs[]" rows="2" class="form-control rounded-3" placeholder="Expected stdout..." required></textarea>
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-outline-danger w-100 py-2 rounded-3 border-0" onclick="removeTestCaseRow(this)"><i class="fas fa-trash-alt"></i> Delete</button>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-outline-primary btn-sm rounded-pill px-4 mt-2" onclick="addTestCaseRow()">
                    <i class="fas fa-plus me-1"></i> Add Test Case
                </button>
            </div>

            <div class="d-flex justify-content-end gap-3">
                <a href="/admin/ipdc" class="btn btn-light rounded-pill px-4 fw-bold">Cancel</a>
                <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">Assign Problem Statement</button>
            </div>

        </form>
    </div>
</div>

<script>
    function addTestCaseRow() {
        const container = document.getElementById('testCasesContainer');
        const newRow = document.createElement('div');
        newRow.className = 'row g-3 mb-3 align-items-end test-case-row';
        newRow.innerHTML = `
            <div class="col-md-5">
                <textarea name="test_inputs[]" rows="2" class="form-control rounded-3" placeholder="Input passed to stdin..." required></textarea>
            </div>
            <div class="col-md-5">
                <textarea name="test_outputs[]" rows="2" class="form-control rounded-3" placeholder="Expected stdout..." required></textarea>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-outline-danger w-100 py-2 rounded-3 border-0" onclick="removeTestCaseRow(this)"><i class="fas fa-trash-alt"></i> Delete</button>
            </div>
        `;
        container.appendChild(newRow);
    }

    function removeTestCaseRow(button) {
        const rows = document.querySelectorAll('.test-case-row');
        if (rows.length > 1) {
            button.closest('.test-case-row').remove();
        } else {
            alert('At least one test case must remain.');
        }
    }
</script>

@endsection
