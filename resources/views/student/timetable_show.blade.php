@extends('layouts.app')
@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold text-primary">{{ $timetable->title }}</h3>
    <a href="/timetables" class="btn btn-light shadow-sm btn-sm border">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>
</div>

<div class="card shadow border-0 overflow-hidden">
    <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between">
        <span class="fw-bold text-muted"><i class="fas fa-building me-1"></i> {{ $timetable->department->name ?? 'All Departments' }}</span>
        <span class="fw-bold text-muted"><i class="fas fa-calendar me-1"></i> Semester: {{ $timetable->semester ?? 'N/A' }}</span>
    </div>
    
    <div class="table-responsive p-0">
        <table class="table table-bordered mb-0 text-center align-middle" style="min-width: 900px; font-size: 0.9rem;">
            <thead class="table-light">
                <tr>
                    <th style="width: 10%;" class="bg-primary text-white">Slot / Day</th>
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                        <th style="width: 15%;" class="bg-primary text-white">
                            {{ $day }}
                            @if($day == 'Saturday')
                                <br><small class="text-white-50 fw-normal" style="font-size: 0.75rem;">10:00 AM - 2:30 PM</small>
                            @endif
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @php
                    $skipSlots = [];
                @endphp
                
                @for($slot = 1; $slot <= 6; $slot++)
                
                @if($slot == 3)
                <tr class="table-warning fw-bold text-dark text-center">
                    <td class="bg-primary text-white text-center py-2" style="font-size: 0.85rem;">Recess</td>
                    <td colspan="6" class="py-2 text-uppercase text-center" style="font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="fas fa-utensils me-2"></i> LUNCH RECESS &nbsp;|&nbsp; Mon-Fri: 12:00 PM - 12:55 PM &nbsp;•&nbsp; Sat: 11:50 AM - 12:30 PM
                    </td>
                </tr>
                @endif
                
                @if($slot == 5)
                <tr class="table-warning fw-bold text-dark text-center">
                    <td class="bg-primary text-white text-center py-2" style="font-size: 0.85rem;">Break</td>
                    <td colspan="6" class="py-2 text-uppercase text-center" style="font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="fas fa-coffee me-2"></i> TEA BREAK &nbsp;|&nbsp; Mon-Fri: 2:30 PM - 2:55 PM &nbsp;•&nbsp; Sat: N/A
                    </td>
                </tr>
                @endif

                <tr>
                    <th class="bg-primary text-white text-center py-3">
                        <div class="fw-bold">Slot {{ $slot }}</div>
                        <div class="text-white-50 fw-normal mt-1" style="font-size: 0.7rem; line-height: 1.2;">
                            @if($slot == 1)
                                Mon-Fri: 10:10-11:05<br>Sat: 10:00-10:55
                            @elseif($slot == 2)
                                Mon-Fri: 11:05-12:00<br>Sat: 10:55-11:50
                            @elseif($slot == 3)
                                Mon-Fri: 12:55-1:50<br>Sat: 12:30-1:30
                            @elseif($slot == 4)
                                Mon-Fri: 1:50-2:30<br>Sat: 1:30-2:30
                            @elseif($slot == 5)
                                Mon-Fri: 2:55-3:45<br>Sat: N/A
                            @elseif($slot == 6)
                                Mon-Fri: 3:45-4:30<br>Sat: N/A
                            @endif
                        </div>
                    </th>
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                        @if($day == 'Saturday' && $slot >= 5)
                            <td class="bg-light text-muted align-middle fw-bold">-</td>
                        @else
                            @php
                                $entry = $grid[$day][$slot] ?? null;
                                $isContinuation = false;
                                
                                if (!$entry && $slot > 1) {
                                    $prevEntry = $grid[$day][$slot - 1] ?? null;
                                    if ($prevEntry && $prevEntry->duration == 2 && !$prevEntry->is_cancelled && !str_contains(strtolower($prevEntry->subject), 'cancel')) {
                                        $entry = $prevEntry;
                                        $isContinuation = true;
                                    }
                                }

                                $isCancelled = $entry && ($entry->is_cancelled || str_contains(strtolower($entry->subject), 'cancel'));
                                $cancelReason = $entry ? ($entry->student_cancel_reason ?: ($entry->cancel_reason ?: 'Class Cancelled (Reason not specified)')) : '';
                                $isExtra = $entry && $entry->is_extra;
                                $extraReason = $entry ? ($entry->extra_reason ?: 'Remedial / Extra Class') : '';
                            @endphp
                            
                            <td class="{{ $entry ? ($isCancelled ? 'bg-danger-subtle border-danger' : ($isExtra ? 'bg-success-subtle border-success' : ($isContinuation ? 'bg-light-subtle text-secondary' : 'bg-white'))) : 'bg-light' }} p-3 position-relative" style="vertical-align: middle;">
                                @if($entry)
                                    @if($isCancelled)
                                        <div class="fw-bold text-danger fs-6 mb-1"><i class="fas fa-ban me-1"></i> Cancelled Slot</div>
                                        <div class="text-danger small fw-bold mt-1">Remark: {{ $cancelReason }}</div>
                                    @else
                                        @if($isExtra)
                                            <div class="badge bg-success text-white mb-2 px-2 py-1"><i class="fas fa-plus-circle me-1"></i> Extra Lecture</div>
                                            <div class="text-success small fw-bold mb-1"><i class="fas fa-bullhorn me-1"></i> {{ $extraReason }}</div>
                                        @endif
                                        <div class="fw-bold text-dark fs-6 mb-1">
                                            {{ $entry->subject }}
                                            @if($isContinuation)
                                                <span class="text-muted small d-block" style="font-size: 0.75rem;">(Continuation)</span>
                                            @endif
                                        </div>
                                        @if($entry->faculty)
                                            <div class="text-secondary small fw-bold mb-1"><i class="fas fa-user-circle me-1"></i> {{ $entry->faculty }}</div>
                                        @endif
                                        @if($entry->room)
                                            <div class="badge bg-secondary px-2 py-1"><i class="fas fa-map-marker-alt me-1"></i> {{ $entry->room }}</div>
                                        @endif
                                    @endif
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                        @endif
                    @endforeach
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

@endsection
