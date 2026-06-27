@extends('layouts.app')
@section('content')

<style>
    .bg-premium {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%) !important;
    }
</style>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <span class="badge bg-info text-white mb-1 px-3 py-1 rounded-pill"><i class="fas fa-chalkboard-teacher me-1"></i> Faculty Portal</span>
        <h3 class="fw-bold text-dark mb-0">{{ $timetable->title }} - Detailed Faculty View</h3>
    </div>
    <a href="{{ url('/admin/timetables') }}" class="btn btn-light shadow-sm btn-sm border rounded-pill px-3 fw-bold">
        <i class="fas fa-arrow-left me-1"></i> Back to Timetables
    </a>
</div>

<div class="card shadow-lg border-0 overflow-hidden rounded-4 mb-5">
    <div class="card-header bg-light-subtle border-bottom p-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
                <i class="fas fa-building fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark">{{ $timetable->department->name ?? 'Computer Science & Engineering' }}</h6>
                <span class="text-muted small">Academic Department Allocation</span>
            </div>
        </div>
        <div class="text-end">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-bold fs-6">
                <i class="fas fa-graduation-cap me-1"></i> Semester {{ $timetable->semester ?? '3' }}
            </span>
            <div class="text-muted small mt-1"><i class="fas fa-clock me-1"></i> Uploaded: {{ $timetable->created_at->format('d M Y') }}</div>
        </div>
    </div>
    
    <div class="table-responsive p-0">
        <table class="table table-bordered mb-0 text-center align-middle" style="min-width: 1000px; font-size: 0.9rem;">
            <thead class="table-light">
                <tr>
                    <th style="width: 10%;" class="bg-dark text-white py-3 fs-6">Slot / Day</th>
                    @foreach(['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'] as $day)
                        <th style="width: 15%;" class="bg-dark text-white py-3 fs-6">
                            {{ $day }}
                            @if($day == 'Saturday')
                                <br><span class="badge bg-warning text-dark mt-1 fw-bold" style="font-size: 0.75rem;">10:00 AM - 2:30 PM</span>
                            @else
                                <br><span class="badge bg-secondary mt-1 fw-normal" style="font-size: 0.75rem;">Regular Hours</span>
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
                    <td class="bg-dark text-white text-center py-2" style="font-size: 0.85rem;">Recess</td>
                    <td colspan="6" class="py-2 text-uppercase text-center" style="font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="fas fa-utensils me-2"></i> LUNCH RECESS &nbsp;|&nbsp; Mon-Fri: 12:00 PM - 12:55 PM &nbsp;•&nbsp; Sat: 11:50 AM - 12:30 PM
                    </td>
                </tr>
                @endif
                
                @if($slot == 5)
                <tr class="table-warning fw-bold text-dark text-center">
                    <td class="bg-dark text-white text-center py-2" style="font-size: 0.85rem;">Break</td>
                    <td colspan="6" class="py-2 text-uppercase text-center" style="font-size: 0.85rem; letter-spacing: 1px;">
                        <i class="fas fa-coffee me-2"></i> TEA BREAK &nbsp;|&nbsp; Mon-Fri: 2:30 PM - 2:55 PM &nbsp;•&nbsp; Sat: N/A
                    </td>
                </tr>
                @endif

                <tr>
                    <th class="table-light text-dark py-4 shadow-none">
                        <span class="fw-bold d-block fs-6">Slot {{ $slot }}</span>
                        <span class="text-muted small fw-normal mt-1 d-block" style="font-size: 0.7rem; line-height: 1.2;">
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
                        </span>
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
                                $cancelReason = $entry ? ($entry->faculty_cancel_reason ?: ($entry->cancel_reason ?: 'Administrative Cancellation (Internal reason not specified)')) : '';
                                $isExtra = $entry && $entry->is_extra;
                                $extraReason = $entry ? ($entry->extra_reason ?: 'Remedial / Extra Class') : '';
                            @endphp
                            
                            <td class="{{ $entry ? ($isCancelled ? 'bg-danger-subtle border-danger' : ($isExtra ? 'bg-success-subtle border-success' : ($isContinuation ? 'bg-light-subtle text-secondary' : 'bg-white'))) : 'bg-light-subtle' }} p-3 position-relative" style="vertical-align: middle;">
                                @if($entry)
                                    @if($isCancelled)
                                        <div class="mb-2">
                                            <span class="badge bg-danger shadow-sm"><i class="fas fa-ban me-1"></i> CANCELLED</span>
                                        </div>
                                        <div class="fw-bold text-danger fs-6 mb-2 mt-1"><i class="fas fa-exclamation-triangle me-1"></i> Cancelled Slot</div>
                                        <div class="p-2 bg-white rounded-3 border border-danger text-danger small fw-bold shadow-sm d-inline-block mb-1 text-start w-100">
                                            <i class="fas fa-user-shield me-1"></i> Faculty Remark: {{ $cancelReason }}
                                        </div>
                                        <div class="text-muted small mt-1"><del>{{ $entry->subject }}</del></div>
                                    @else
                                        @if($isExtra)
                                            <div class="mb-2">
                                                <span class="badge bg-success text-white shadow-sm"><i class="fas fa-plus-circle me-1"></i> EXTRA LECTURE</span>
                                            </div>
                                            <div class="p-2 bg-white rounded-3 border border-success text-success small fw-bold shadow-sm d-inline-block mb-2 text-start w-100">
                                                <i class="fas fa-bullhorn me-1"></i> Extra Topic: {{ $extraReason }}
                                            </div>
                                        @elseif($isContinuation)
                                            <div class="mb-2">
                                                <span class="badge bg-secondary text-white shadow-sm"><i class="fas fa-hourglass-half me-1"></i> Lab Cont.</span>
                                            </div>
                                        @elseif($entry->duration == 2)
                                            <div class="mb-2">
                                                <span class="badge bg-premium text-white shadow-sm"><i class="fas fa-hourglass-half me-1"></i> 2-Hour Lab</span>
                                            </div>
                                        @endif
                                        <div class="fw-bold text-dark fs-6 mb-2 mt-2">
                                            {{ $entry->subject }}
                                            @if($isContinuation)
                                                <span class="text-muted small d-block" style="font-size: 0.75rem;">(Continuation)</span>
                                            @endif
                                        </div>
                                        
                                        <div class="d-flex flex-column gap-1 align-items-center justify-content-center mt-2 pt-1 border-top border-light-subtle">
                                            @if($entry->faculty)
                                                <span class="badge bg-light text-dark border border-secondary-subtle px-3 py-1 rounded-pill fw-bold shadow-sm w-100 text-truncate">
                                                    <i class="fas fa-user-tie text-primary me-1"></i> {{ $entry->faculty }}
                                                </span>
                                            @else
                                                <span class="badge bg-light text-muted border border-secondary-subtle px-3 py-1 rounded-pill fw-normal w-100">
                                                    <i class="fas fa-user-slash me-1"></i> Faculty TBD
                                                </span>
                                            @endif
 
                                            @if($entry->room)
                                                <span class="badge bg-dark text-white px-3 py-1 rounded-pill shadow-sm w-100">
                                                    <i class="fas fa-map-marker-alt text-warning me-1"></i> {{ $entry->room }}
                                                </span>
                                            @endif
                                        </div>
                                    @endif
                                @else
                                    <span class="text-muted small fw-bold">-</span>
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
