@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3>Build Interactive Timetable</h3>
    <a href="{{ url('/admin/timetables') }}" class="btn btn-outline-secondary btn-sm">Back</a>
</div>

<div class="card p-4 shadow-sm border-0">
    <form method="POST" action="{{ isset($timetable) ? url('/admin/timetables/'.$timetable->id.'/update') : url('/admin/timetables/build') }}">
        @csrf
        
        <div class="row mb-4">
            <div class="col-md-4">
                <label class="form-label fw-bold">Title</label>
                <input type="text" name="title" class="form-control" value="{{ $timetable->title ?? '' }}" placeholder="e.g. Sem IV Class-1" required>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Department</label>
                <select name="department_id" class="form-select">
                    <option value="">-- All Departments --</option>
                    @foreach($departments as $d)
                        <option value="{{ $d->id }}" {{ (isset($timetable) && $timetable->department_id == $d->id) ? 'selected' : '' }}>{{ $d->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-bold">Semester</label>
                <select name="semester" class="form-select" required>
                    <option value="">-- Select Semester --</option>
                    @for($i = 1; $i <= 8; $i++)
                        <option value="{{ $i }}" {{ (isset($timetable) && $timetable->semester == $i) ? 'selected' : '' }}>Semester {{ $i }}</option>
                    @endfor
                </select>
            </div>
        </div>

        <h5 class="mb-3">Timetable Grid Configuration</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle text-center small">
                <thead class="table-light">
                    <tr>
                        <th style="width: 80px;">Slot / Day</th>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            <th>
                                {{ $day }}
                                @if($day == 'Saturday')
                                    <br><small class="text-muted fw-normal" style="font-size: 0.7rem;">10:00 AM - 2:30 PM</small>
                                @endif
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @for($slot = 1; $slot <= 6; $slot++)
                    
                    @if($slot == 3)
                    <!-- LUNCH BREAK ROW -->
                    <tr class="table-warning fw-bold text-dark">
                        <th class="table-light text-muted py-2" style="font-size: 0.8rem;">Recess</th>
                        <td colspan="6" class="py-2 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
                            <i class="fas fa-utensils me-2"></i> LUNCH RECESS &nbsp;|&nbsp; Mon-Fri: 12:00 PM - 12:55 PM &nbsp;•&nbsp; Sat: 11:50 AM - 12:30 PM
                        </td>
                    </tr>
                    @endif
                    
                    @if($slot == 5)
                    <!-- TEA BREAK ROW -->
                    <tr class="table-warning fw-bold text-dark">
                        <th class="table-light text-muted py-2" style="font-size: 0.8rem;">Break</th>
                        <td colspan="6" class="py-2 text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">
                            <i class="fas fa-coffee me-2"></i> TEA BREAK &nbsp;|&nbsp; Mon-Fri: 2:30 PM - 2:55 PM &nbsp;•&nbsp; Sat: N/A
                        </td>
                    </tr>
                    @endif

                    <tr>
                        <th class="table-light text-center py-3">
                            <div class="fw-bold text-dark">Slot {{ $slot }}</div>
                            <div class="text-muted fw-normal mt-1" style="font-size: 0.68rem; line-height: 1.2;">
                                @if($slot == 1)
                                    Mon-Fri: 10:10 - 11:05<br>Sat: 10:00 - 10:55
                                @elseif($slot == 2)
                                    Mon-Fri: 11:05 - 12:00<br>Sat: 10:55 - 11:50
                                @elseif($slot == 3)
                                    Mon-Fri: 12:55 - 1:50<br>Sat: 12:30 - 1:30
                                @elseif($slot == 4)
                                    Mon-Fri: 1:50 - 2:30<br>Sat: 1:30 - 2:30
                                @elseif($slot == 5)
                                    Mon-Fri: 2:55 - 3:45<br>Sat: N/A
                                @elseif($slot == 6)
                                    Mon-Fri: 3:45 - 4:30<br>Sat: N/A
                                @endif
                            </div>
                        </th>
                        @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                            @if($day == 'Saturday' && $slot >= 5)
                                <td class="bg-light text-muted align-middle fw-bold" style="background-color: #f8f9fa !important; font-size: 1.1rem; letter-spacing: 2px;">
                                    -
                                </td>
                            @else
                                @php
                                    $entry = $grid[$day][$slot] ?? null;
                                    $isCancelled = $entry && ($entry->is_cancelled || str_contains(strtolower($entry->subject), 'cancel'));
                                    $cancelReason = $entry ? ($entry->cancel_reason ?: 'Administrative Cancellation') : '';
                                    $isExtra = $entry && $entry->is_extra;
                                    $extraReason = $entry ? ($entry->extra_reason ?: 'Remedial / Extra Class') : '';
                                @endphp
                                <td class="{{ $isCancelled ? 'bg-danger-subtle' : ($isExtra ? 'bg-success-subtle' : '') }}" id="td_{{$day}}_{{$slot}}">
                                    <input type="hidden" name="slots[{{ $day }}][{{ $slot }}][is_cancelled]" value="{{ $isCancelled ? 1 : 0 }}" id="is_cancelled_{{$day}}_{{$slot}}">
                                    <input type="hidden" name="slots[{{ $day }}][{{ $slot }}][cancel_reason]" value="{{ $cancelReason }}" id="cancel_reason_{{$day}}_{{$slot}}">
                                    <input type="hidden" name="slots[{{ $day }}][{{ $slot }}][faculty_cancel_reason]" value="{{ $entry->faculty_cancel_reason ?? $cancelReason }}" id="faculty_cancel_reason_{{$day}}_{{$slot}}">
                                    <input type="hidden" name="slots[{{ $day }}][{{ $slot }}][student_cancel_reason]" value="{{ $entry->student_cancel_reason ?? $cancelReason }}" id="student_cancel_reason_{{$day}}_{{$slot}}">
                                    <input type="hidden" name="slots[{{ $day }}][{{ $slot }}][is_extra]" value="{{ $isExtra ? 1 : 0 }}" id="is_extra_{{$day}}_{{$slot}}">
                                    <input type="hidden" name="slots[{{ $day }}][{{ $slot }}][extra_reason]" value="{{ $extraReason }}" id="extra_reason_{{$day}}_{{$slot}}">
                                    
                                    <input type="text" name="slots[{{ $day }}][{{ $slot }}][subject]" id="subj_{{$day}}_{{$slot}}" class="form-control form-control-sm mb-1 text-center {{ $isCancelled ? 'text-danger fw-bold' : '' }}" value="{{ $entry->subject ?? '' }}" placeholder="Subject" {{ $isCancelled ? 'readonly' : '' }}>
                                    <div class="input-group input-group-sm mb-1" id="fac_room_{{$day}}_{{$slot}}" style="{{ $isCancelled ? 'display: none;' : '' }}">
                                        <select name="slots[{{ $day }}][{{ $slot }}][faculty]" id="fac_select_{{$day}}_{{$slot}}" class="form-select form-select-sm text-center">
                                            <option value="">-- Faculty --</option>
                                            @foreach($faculties as $fac)
                                                <option value="{{ $fac->name }}" {{ (isset($entry) && $entry->faculty == $fac->name) ? 'selected' : '' }}>{{ $fac->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="text" name="slots[{{ $day }}][{{ $slot }}][room]" id="room_{{$day}}_{{$slot}}" class="form-control form-control-sm text-center" value="{{ $entry->room ?? '' }}" placeholder="Room">
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mt-1" style="font-size: 0.75rem;">
                                        <div class="form-check form-check-inline m-0">
                                            <input class="form-check-input" type="checkbox" name="slots[{{ $day }}][{{ $slot }}][duration]" value="2" id="lab_{{$day}}_{{$slot}}" {{ (isset($entry) && $entry->duration > 1) ? 'checked' : '' }}>
                                            <label class="form-check-label text-muted" for="lab_{{$day}}_{{$slot}}">2-Hour Lab</label>
                                        </div>
                                        <div class="d-flex gap-2">
                                            <button type="button" class="btn btn-sm p-0 text-{{ $isCancelled ? 'primary' : 'danger' }}" onclick="toggleCancel('{{$day}}', '{{$slot}}')" title="Cancel Slot">
                                                <i class="fas fa-{{ $isCancelled ? 'undo' : 'ban' }}"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm p-0 text-{{ $isExtra ? 'secondary' : 'success' }}" onclick="toggleExtra('{{$day}}', '{{$slot}}')" title="Extra Lecture">
                                                <i class="fas fa-{{ $isExtra ? 'minus-circle' : 'plus-circle' }}"></i>
                                            </button>
                                        </div>
                                    </div>
                                    @if($isCancelled)
                                        <div class="text-primary small mt-1 fw-bold" id="fac_reason_text_{{$day}}_{{$slot}}">Faculty: {{ $entry ? ($entry->faculty_cancel_reason ?: $cancelReason) : $cancelReason }}</div>
                                        <div class="text-danger small fw-bold" id="stu_reason_text_{{$day}}_{{$slot}}">Student: {{ $entry ? ($entry->student_cancel_reason ?: $cancelReason) : $cancelReason }}</div>
                                    @else
                                        <div class="text-primary small mt-1 fw-bold" id="fac_reason_text_{{$day}}_{{$slot}}" style="display:none;"></div>
                                        <div class="text-danger small fw-bold" id="stu_reason_text_{{$day}}_{{$slot}}" style="display:none;"></div>
                                    @endif
                                    @if($isExtra)
                                        <div class="text-success small mt-1 fw-bold" id="extra_reason_text_{{$day}}_{{$slot}}">Extra Lecture Topic: {{ $extraReason }}</div>
                                    @else
                                        <div class="text-success small mt-1 fw-bold" id="extra_reason_text_{{$day}}_{{$slot}}" style="display:none;"></div>
                                    @endif
                                </td>
                            @endif
                        @endforeach
                    </tr>
                    @endfor
                </tbody>
            </table>
        </div>
        
        <div class="text-end mt-4">
            <button type="button" class="btn btn-outline-success px-4 fw-bold shadow-sm me-2" id="btn-generate-ai">
                <i class="fas fa-magic me-2"></i> Generate with Smart AI
            </button>
            <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">Save Timetable Configuration</button>
        </div>
    </form>
</div>

<script>
function toggleCancel(day, slot) {
    let isCancelledInput = document.getElementById('is_cancelled_' + day + '_' + slot);
    let cancelReasonInput = document.getElementById('cancel_reason_' + day + '_' + slot);
    let facultyCancelReasonInput = document.getElementById('faculty_cancel_reason_' + day + '_' + slot);
    let studentCancelReasonInput = document.getElementById('student_cancel_reason_' + day + '_' + slot);
    let subjInput = document.getElementById('subj_' + day + '_' + slot);
    let facRoomDiv = document.getElementById('fac_room_' + day + '_' + slot);
    let td = document.getElementById('td_' + day + '_' + slot);
    let facReasonText = document.getElementById('fac_reason_text_' + day + '_' + slot);
    let stuReasonText = document.getElementById('stu_reason_text_' + day + '_' + slot);
    let btnIcon = document.querySelector(`button[onclick="toggleCancel('${day}', '${slot}')"] i`);
    let btn = document.querySelector(`button[onclick="toggleCancel('${day}', '${slot}')"]`);

    if (isCancelledInput.value === '0') {
        let facReason = prompt('Please enter the cancellation reason for FACULTY (Internal View):');
        let stuReason = prompt('Please enter the cancellation reason for STUDENTS (Public View):');
        if (facReason || stuReason) {
            facReason = facReason || 'Administrative Cancellation';
            stuReason = stuReason || 'Class Cancelled';
            isCancelledInput.value = '1';
            cancelReasonInput.value = facReason;
            facultyCancelReasonInput.value = facReason;
            studentCancelReasonInput.value = stuReason;
            subjInput.dataset.original = subjInput.value;
            subjInput.value = 'Cancelled Slot';
            subjInput.classList.add('text-danger', 'fw-bold');
            subjInput.readOnly = true;
            facRoomDiv.style.display = 'none';
            td.classList.add('bg-danger-subtle');
            facReasonText.innerText = 'Faculty: ' + facReason;
            facReasonText.style.display = 'block';
            stuReasonText.innerText = 'Student: ' + stuReason;
            stuReasonText.style.display = 'block';
            btnIcon.classList.remove('fa-ban');
            btnIcon.classList.add('fa-undo');
            btn.classList.remove('text-danger');
            btn.classList.add('text-primary');
        }
    } else {
        isCancelledInput.value = '0';
        cancelReasonInput.value = '';
        facultyCancelReasonInput.value = '';
        studentCancelReasonInput.value = '';
        subjInput.value = subjInput.dataset.original || '';
        subjInput.classList.remove('text-danger', 'fw-bold');
        subjInput.readOnly = false;
        facRoomDiv.style.display = 'flex';
        td.classList.remove('bg-danger-subtle');
        facReasonText.style.display = 'none';
        stuReasonText.style.display = 'none';
        btnIcon.classList.remove('fa-undo');
        btnIcon.classList.add('fa-ban');
        btn.classList.remove('text-primary');
        btn.classList.add('text-danger');
    }
}

function toggleExtra(day, slot) {
    let isExtraInput = document.getElementById('is_extra_' + day + '_' + slot);
    let extraReasonInput = document.getElementById('extra_reason_' + day + '_' + slot);
    let td = document.getElementById('td_' + day + '_' + slot);
    let extraReasonText = document.getElementById('extra_reason_text_' + day + '_' + slot);
    let btnIcon = document.querySelector(`button[onclick="toggleExtra('${day}', '${slot}')"] i`);
    let btn = document.querySelector(`button[onclick="toggleExtra('${day}', '${slot}')"]`);

    if (isExtraInput.value === '0') {
        let reason = prompt('Please enter the reason/topic for the Extra Lecture (Universal View):');
        if (reason) {
            isExtraInput.value = '1';
            extraReasonInput.value = reason;
            td.classList.add('bg-success-subtle');
            extraReasonText.innerText = 'Extra Lecture Topic: ' + reason;
            extraReasonText.style.display = 'block';
            btnIcon.classList.remove('fa-plus-circle');
            btnIcon.classList.add('fa-minus-circle');
            btn.classList.remove('text-success');
            btn.classList.add('text-secondary');
        }
    } else {
        isExtraInput.value = '0';
        extraReasonInput.value = '';
        td.classList.remove('bg-success-subtle');
        extraReasonText.style.display = 'none';
        btnIcon.classList.remove('fa-minus-circle');
        btnIcon.classList.add('fa-plus-circle');
        btn.classList.remove('text-secondary');
        btn.classList.add('text-success');
    }
}

document.getElementById('btn-generate-ai').addEventListener('click', function() {
    let deptId = document.querySelector('select[name="department_id"]').value;
    let semester = document.querySelector('input[name="semester"]').value;
    
    if (!confirm("Are you sure you want to auto-generate slot configurations using Smart AI? This will overwrite the current input grid.")) {
        return;
    }
    
    let btn = this;
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Generating...';
    
    fetch('/admin/timetables/generate-ai', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            department_id: deptId,
            semester: semester
        })
    })
    .then(async res => {
        const text = await res.text();
        if (!res.ok) {
            throw new Error(`HTTP ${res.status}: ${text.substring(0, 200)}`);
        }
        try {
            return JSON.parse(text);
        } catch (e) {
            throw new Error(`Invalid JSON response: ${text.substring(0, 200)}`);
        }
    })
    .then(data => {
        if (data.success && data.slots) {
            Object.keys(data.slots).forEach(day => {
                Object.keys(data.slots[day]).forEach(slot => {
                    let info = data.slots[day][slot];
                    
                    let isSaturdaySlot5Or6 = (day === 'Saturday' && parseInt(slot) >= 5);
                    if (isSaturdaySlot5Or6) return;
                    
                    let subjInput = document.getElementById('subj_' + day + '_' + slot);
                    let facSelect = document.getElementById('fac_select_' + day + '_' + slot);
                    let roomInput = document.getElementById('room_' + day + '_' + slot);
                    let labCheckbox = document.getElementById('lab_' + day + '_' + slot);
                    
                    if (subjInput) subjInput.value = info.subject || '';
                    if (facSelect) facSelect.value = info.faculty || '';
                    if (roomInput) roomInput.value = info.room || '';
                    if (labCheckbox) {
                        labCheckbox.checked = (info.duration === 2);
                    }
                });
            });
            alert('Timetable generated successfully with Smart AI! You can now review, edit, and click Save.');
        } else {
            alert('AI Timetable Generation failed: ' + (data.error || 'Unknown error'));
        }
    })
    .catch(err => {
        console.error(err);
        alert('An error occurred during generation: ' + err.message);
    })
    .finally(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-magic me-2"></i> Generate with Smart AI';
    });
});
</script>

@endsection
