@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-danger"><i class="fas fa-magic me-2"></i> Add Function Module</h3>
    <a href="/admin" class="btn-baps-back"><i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span></a>
</div>

<div class="card border-danger border-2 shadow-sm rounded">
    <div class="card-header bg-danger text-white fw-bold">
        <i class="fas fa-database me-1"></i> Global Function Injector
    </div>
    <div class="card-body p-4 bg-danger-subtle bg-opacity-10">
        <p class="text-dark small mb-4 fw-bold">Select a target table to dynamically inject records directly into the live database without writing SQL or code.</p>
        <form action="/admin/master-data/inject" method="POST">
            @csrf
            <div class="row">
                <div class="col-md-5 mb-4">
                    <label class="fw-bold small text-danger mb-1">Target Database Table</label>
                    <select name="target_table" class="form-select border-danger shadow-sm fw-bold" required onchange="loadTableSchema(this.value)">
                        <option value="">-- Select Table --</option>
                        @foreach(['users', 'staff', 'departments', 'courses', 'enrollments', 'lessons', 'contents', 'tasks', 'quizzes', 'questions', 'options', 'quiz_attempts', 'certificates', 'progress', 'attendances', 'gatepasses', 'leaves', 'timetables', 'timetable_entries', 'announcements', 'notifications', 'question_banks'] as $tbl)
                            <option value="{{ $tbl }}">{{ strtoupper($tbl) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div id="dynamicSchemaFields" class="row bg-white p-4 rounded shadow-sm border border-danger border-opacity-25 d-none mb-4">
                <!-- Dynamic fields will be injected here via JS -->
            </div>
            
            <button type="submit" class="btn btn-danger fw-bold px-5 py-2 shadow" id="injectBtn" style="display:none; font-size: 16px;"><i class="fas fa-cogs me-2"></i> Execute Function Injection</button>
        </form>
    </div>
</div>

<script>
    async function loadTableSchema(tableName) {
        let container = document.getElementById('dynamicSchemaFields');
        let btn = document.getElementById('injectBtn');
        
        if (!tableName) {
            container.classList.add('d-none');
            btn.style.display = 'none';
            return;
        }

        container.innerHTML = '<div class="col-12 text-center text-danger"><i class="fas fa-spinner fa-spin fa-2x"></i><p class="mt-2">Reading Database Schema...</p></div>';
        container.classList.remove('d-none');

        try {
            let response = await fetch('/admin/master-data/schema/' + tableName);
            let columns = await response.json();
            
            if (columns.error) {
                container.innerHTML = `<div class="col-12 text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> Error: ${columns.error}</div>`;
                return;
            }

            let html = '<h6 class="fw-bold text-danger mb-3 border-bottom pb-2">Dynamically Generated Fields for ' + tableName.toUpperCase() + '</h6>';
            
            columns.forEach(col => {
                html += `
                <div class="col-md-4 mb-3">
                    <label class="small fw-bold text-muted mb-1">${col.toUpperCase()}</label>
                    <input type="text" name="${col}" class="form-control border-secondary" placeholder="Value for ${col}...">
                </div>`;
            });

            container.innerHTML = html;
            btn.style.display = 'inline-block';
        } catch (e) {
            container.innerHTML = '<div class="col-12 text-danger fw-bold"><i class="fas fa-exclamation-triangle"></i> Failed to read schema.</div>';
        }
    }
</script>
@endsection
