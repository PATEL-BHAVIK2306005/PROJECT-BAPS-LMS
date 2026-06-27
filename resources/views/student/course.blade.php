@extends('layouts.app')
@section('content')

<div class="mb-5">
    <a href="/courses" class="btn btn-light btn-sm rounded-pill mb-3 border shadow-sm">
        <i class="fas fa-arrow-left me-2"></i> Back to Courses
    </a>
    <h2 class="fw-bold">{{ $course->title }}</h2>
    <p class="text-muted mb-2">{{ $course->description }}</p>
    
    <div class="mt-2 mb-3">
        <span class="badge bg-primary rounded-pill px-3 py-2 fw-bold shadow-sm">
            <i class="fas fa-chalkboard-teacher me-1"></i> 
            Instructor: {{ isset($assignedFaculty) ? $assignedFaculty->name : 'TBD' }}
            @if(isset($enrollment) && $enrollment->class_section && $enrollment->class_section !== 'Self-Enrolled')
                ({{ $enrollment->class_section }})
            @endif
        </span>
    </div>

    <!-- 5-Star Rating System -->
    @php
        $avgRating = $course->ratings()->avg('rating') ?? 0;
        $totalRatings = $course->ratings()->count();
        $uid = auth()->check() ? auth()->id() : 1;
        $userRating = $course->ratings()->where('user_id', $uid)->first();
    @endphp
    
    <div class="d-flex align-items-center gap-3 mt-3">
        <div class="d-flex text-warning fs-5">
            @for($i=1; $i<=5; $i++)
                <i class="fas fa-star {{ $i <= round($avgRating) ? '' : 'text-light' }}"></i>
            @endfor
        </div>
        <span class="text-muted fw-bold">{{ number_format($avgRating, 1) }} (<i class="fas fa-user-friends ms-1"></i> {{ $totalRatings }})</span>
        
        @if(!$userRating)
            <button class="btn btn-sm btn-outline-premium ms-3 rounded-pill" data-bs-toggle="modal" data-bs-target="#ratingModal">
                <i class="fas fa-star me-1"></i> Rate Course
            </button>
        @else
            <span class="badge bg-success-subtle text-success ms-3 rounded-pill px-3 py-2">
                <i class="fas fa-check-circle me-1"></i> You rated this {{ $userRating->rating }} stars
            </span>
        @endif
    </div>
</div>

<!-- Rating Modal -->
<div class="modal fade" id="ratingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card border-0">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold">Rate this Course</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="/courses/{{ $course->id }}/rate" method="POST">
                @csrf
                <div class="modal-body text-center">
                    <p class="text-muted mb-4">How would you rate your experience with "{{ $course->title }}"?</p>
                    <div class="rating-stars mb-4 fs-2 text-light" style="cursor:pointer;">
                        <i class="fas fa-star star-btn" data-val="1"></i>
                        <i class="fas fa-star star-btn" data-val="2"></i>
                        <i class="fas fa-star star-btn" data-val="3"></i>
                        <i class="fas fa-star star-btn" data-val="4"></i>
                        <i class="fas fa-star star-btn" data-val="5"></i>
                    </div>
                    <input type="hidden" name="rating" id="ratingInput" value="0" required>
                    <textarea name="review" class="form-control rounded-4 bg-light focus-ring" rows="3" placeholder="Write a brief review (optional)..."></textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="submit" class="btn btn-premium w-100 rounded-pill py-2 fw-bold" id="submitRatingBtn" disabled>Submit Rating</button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.star-btn { transition: color 0.2s; }
.star-btn:hover, .star-btn.active { color: #ffc107 !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', () => {
    let stars = document.querySelectorAll('.star-btn');
    let ratingInput = document.getElementById('ratingInput');
    let submitBtn = document.getElementById('submitRatingBtn');

    stars.forEach(star => {
        star.addEventListener('click', function() {
            let val = parseInt(this.getAttribute('data-val'));
            ratingInput.value = val;
            submitBtn.disabled = false;
            
            stars.forEach((s, idx) => {
                if(idx < val) {
                    s.classList.remove('text-light');
                    s.classList.add('text-warning', 'active');
                } else {
                    s.classList.add('text-light');
                    s.classList.remove('text-warning', 'active');
                }
            });
        });
    });
});
</script>

<div class="glass-card p-4 mb-5 border-0">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold mb-0">Course Progress</h6>
        <div class="d-flex align-items-center gap-3">
            @php
                $uid = auth()->check() ? auth()->id() : 1;
                $isComplete = \App\Models\Certificate::where('user_id', $uid)->where('course_id', $course->id)->exists();
            @endphp
            @if($isComplete)
                <a href="/certificate/{{ $course->id }}/preview" target="_blank" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold shadow-sm">
                    <i class="fas fa-certificate me-1"></i> Preview & Print Certificate
                </a>
            @endif
            <span id="percent-text" class="fw-bold text-primary">0%</span>
        </div>
    </div>
    <div class="progress rounded-pill" style="height: 12px; background: #e2e8f0;">
        <div id="bar" class="progress-bar rounded-pill" style="width: 0%; background-image: var(--primary-gradient) !important;"></div>
    </div>
</div>

@if($course->class_mode == 'online' && ($course->google_meet_link || $course->live_link))
<div class="glass-card p-4 mb-5 border-0 border-start border-4 border-success position-relative overflow-hidden shadow-sm" style="background: rgba(25, 135, 84, 0.05);">
    <div class="position-absolute top-0 end-0 opacity-10">
        <i class="fas fa-video fa-6x text-success mt-n3 me-n3"></i>
    </div>
    <div class="d-flex align-items-center gap-3 position-relative z-1">
        <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
            <i class="fas fa-video"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1 text-success">Online Live Class Active</h5>
            <p class="mb-1 text-muted small">
                Faculty has launched the virtual classroom. @if($course->live_time) Scheduled for <span class="fw-bold">{{ \Carbon\Carbon::parse($course->live_time)->format('M d, Y - h:i A') }}</span> @endif
            </p>
            <div class="bg-white p-2 rounded shadow-sm d-inline-block border">
                <div class="small text-primary fw-bold"><i class="fas fa-chalkboard-teacher me-1"></i> Today's Host: <span class="text-dark">{{ $course->host_name ?: 'Not Assigned' }}</span></div>
                @if($course->host_email)
                <div class="small text-muted"><i class="fas fa-envelope me-1"></i> {{ $course->host_email }}</div>
                @endif
            </div>
        </div>
        <div class="ms-auto text-end">
            <a href="{{ $course->google_meet_link ?? $course->live_link }}" target="_blank" class="btn btn-success rounded-pill px-4 shadow-sm fw-bold">
                <i class="fab fa-google me-2"></i> Join Live Class
            </a>
        </div>
    </div>
</div>
@elseif($course->class_mode == 'offline')
<div class="glass-card p-4 mb-5 border-0 border-start border-4 border-secondary position-relative overflow-hidden shadow-sm" style="background: rgba(108, 117, 125, 0.05);">
    <div class="position-absolute top-0 end-0 opacity-10">
        <i class="fas fa-building fa-6x text-secondary mt-n3 me-n3"></i>
    </div>
    <div class="d-flex align-items-center gap-3 position-relative z-1">
        <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 48px; height: 48px;">
            <i class="fas fa-chalkboard-teacher"></i>
        </div>
        <div>
            <h5 class="fw-bold mb-1 text-dark">Offline Campus Class</h5>
            <p class="mb-0 text-muted small">
                This session is currently being held physically on campus. Please report to your designated classroom.
            </p>
        </div>
    </div>
</div>
@endif

<h5 class="fw-bold mb-3"><i class="fas fa-book-open text-primary me-2"></i> Course Modules</h5>
<div class="row g-4 mb-5">
    @foreach($course->lessons as $lesson)
    <div class="col-12">
        <div class="glass-card p-0 border-0 overflow-hidden shadow-sm">
            <div class="p-4 border-bottom bg-light-subtle d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0"><i class="fas fa-play-circle text-primary me-2"></i> {{ $lesson->title }}</h5>
                <div class="text-end">
                    @if($lesson->uploader)
                        <div class="small text-muted mb-1 fw-bold"><i class="fas fa-user-edit me-1"></i> Uploaded by: {{ $lesson->uploader->name }}</div>
                    @endif
                    <span class="badge rounded-pill bg-white text-dark border px-3 py-2 small fw-bold text-uppercase">
                        {{ $lesson->type }}
                    </span>
                </div>
            </div>
            
            <div class="p-4">
                @php
                    $extension = pathinfo($lesson->file, PATHINFO_EXTENSION);
                    $isVideo = in_array(strtolower($extension), ['mp4', 'webm', 'ogg']);
                    $isPdf = strtolower($extension) === 'pdf';
                @endphp

                <div class="mb-4 rounded-4 overflow-hidden border shadow-sm">
                    @if($lesson->type == 'youtube' || str_contains($lesson->file, 'youtube.com') || str_contains($lesson->file, 'youtu.be'))
                        @php
                            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $lesson->file, $match);
                            $ytId = $match[1] ?? null;
                        @endphp
                        @if($ytId)
                            <div class="ratio ratio-16x9">
                                <iframe src="https://www.youtube.com/embed/{{ $ytId }}" frameborder="0" allowfullscreen></iframe>
                            </div>
                        @else
                            <div class="alert alert-warning m-3">Invalid YouTube Link</div>
                        @endif
                    @elseif($isVideo)
                      <video controls width="100%">
                        <source src="{{ asset('storage/'.$lesson->file) }}">
                      </video>
                    @elseif($isPdf)
                      <div class="p-5 text-center bg-white">
                        <i class="fas fa-file-pdf fa-4x text-danger mb-3"></i>
                        <h5>{{ $lesson->title }}</h5>
                        <a href="{{ asset('storage/'.$lesson->file) }}" target="_blank" class="btn btn-premium mt-2">View Document</a>
                      </div>
                    @else
                      <div class="p-4 text-center">
                        <a href="{{ asset('storage/'.$lesson->file) }}" target="_blank" class="btn btn-outline-primary rounded-pill">
                            <i class="fas fa-download me-2"></i> Download Material
                        </a>
                      </div>
                    @endif
                </div>

                <div class="text-end">
                    <button class="btn btn-premium px-4 py-2 rounded-pill complete" data-id="{{ $lesson->id }}">
                        Mark as Completed <i class="fas fa-check-circle ms-2"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($course->tasks && $course->tasks->count() > 0)
<hr class="my-5 opacity-25">
<h5 class="fw-bold mb-3"><i class="fas fa-tasks text-warning me-2"></i> Assignments & Tasks</h5>
<div class="row g-4 mb-5">
    @foreach($course->tasks as $task)
    @php
        $uid = auth()->check() ? auth()->id() : 1;
        $submission = DB::table('task_submissions')->where('task_id', $task->id)->where('user_id', $uid)->first();
    @endphp
    <div class="col-md-6">
        <div class="glass-card p-4 h-100 border-0 shadow-sm d-flex flex-column">
            <div class="d-flex justify-content-between mb-3">
                <h6 class="fw-bold mb-0 text-dark">{{ $task->title }}</h6>
                @if($task->due_date)
                    <span class="badge bg-light text-dark border"><i class="fas fa-calendar-alt me-1"></i> Due: {{ \Carbon\Carbon::parse($task->due_date)->format('M d') }}</span>
                @endif
            </div>
            <p class="text-muted small mb-4">{{ $task->description }}</p>
            
            <div class="mt-auto">
                @if($submission)
                    <div class="p-3 bg-success-subtle rounded text-success small fw-bold">
                        <i class="fas fa-check-double me-2"></i> File Submitted
                        @if($submission->grade)
                            <div class="mt-2 text-dark">Grade: <span class="badge bg-primary">{{ $submission->grade }}/100</span></div>
                        @endif
                    </div>
                @else
                    <form action="/courses/task/{{ $task->id }}/submit" method="POST" enctype="multipart/form-data" class="d-flex gap-2">
                        @csrf
                        <input type="file" name="file" class="form-control form-control-sm" required>
                        <button type="submit" class="btn btn-primary btn-sm px-3 shadow-sm rounded-pill fw-bold">Upload</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@if($course->quizzes && $course->quizzes->filter(fn($q) => $q->is_active)->count() > 0)
<hr class="my-5 opacity-25">
<h5 class="fw-bold mb-3"><i class="fas fa-question-circle text-primary me-2"></i> Interactive Assessments</h5>
<div class="row g-4 mb-5">
    @foreach($course->quizzes->filter(fn($q) => $q->is_active) as $quiz)
    @php
        // Hardcoded identity bypass for existing demo code structure (id 1) if not authed
        $uid = auth()->check() ? auth()->id() : 1;
        $attempt = \App\Models\QuizAttempt::where('quiz_id', $quiz->id)->where('user_id', $uid)->first();
    @endphp
    <div class="col-md-6">
        <div class="glass-card p-4 h-100 border-0 shadow-sm d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="fw-bold mb-0 text-dark">{{ $quiz->title }}</h6>
                <span class="badge bg-light text-dark border shadow-sm">Pass: {{ $quiz->min_score }} pts</span>
            </div>
            <p class="text-muted small mb-4">Total Questions: {{ $quiz->questions ? $quiz->questions->count() : 0 }}</p>
            
            <div class="mt-auto">
                @if($attempt)
                    <div class="p-3 {{ $attempt->passed ? 'bg-success-subtle border border-success text-success' : 'bg-danger-subtle border border-danger text-danger' }} rounded small fw-bold text-center">
                        <i class="fas {{ $attempt->passed ? 'fa-medal' : 'fa-times-circle' }} me-2"></i> 
                        Score: {{ $attempt->score }} / {{ $quiz->questions->sum('points') }} ({{ $attempt->passed ? 'PASSED' : 'FAILED' }})
                    </div>
                @else
                    <a href="/courses/{{ $course->id }}/quiz/{{ $quiz->id }}" class="btn btn-primary btn-sm px-4 shadow-sm rounded-pill fw-bold w-100 py-2">
                        Start Assessment <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                @endif
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif

@php
    $uid = auth()->check() ? auth()->id() : 1;
    $initialCompleted = App\Models\Progress::where('user_id', $uid)
        ->whereIn('lesson_id', $course->lessons->pluck('id'))
        ->where('completed', true)
        ->count();
    $initialPercent = $course->lessons->count() > 0 ? round(($initialCompleted / $course->lessons->count()) * 100) : 0;
@endphp

<script>
let total = {{ $course->lessons->count() }};
let done = {{ $initialCompleted }};

// Set initial state
$('#bar').css('width', '{{ $initialPercent }}%');
$('#percent-text').text('{{ $initialPercent }}%');

$('.complete').click(function(){
    let btn = $(this);
    if(btn.hasClass('btn-success')) return;

    // OPTIMISTIC UPDATE: Instant UI feedback
    done++;
    let percent = Math.round((done/total)*100);
    $('#bar').css('width', percent+'%');
    $('#percent-text').text(percent+'%');
    
    btn.prop('disabled', true)
       .addClass('btn-success px-4 opacity-75')
       .removeClass('btn-premium')
       .html('Completed <i class="fas fa-check-circle ms-2"></i>');

    // TADKA: Instant Confetti
    confetti({
        particleCount: 100,
        spread: 70,
        origin: { y: 0.6 },
        colors: ['#6366f1', '#a855f7', '#000000']
    });

    // Background AJAX
    $.ajax({
        url: '/progress',
        method: 'POST',
        data: {
            lesson_id: btn.data('id'),
            _token: '{{ csrf_token() }}'
        },
        error: function() {
            // Revert on failure (rare)
            done--;
            let revertPercent = Math.round((done/total)*100);
            $('#bar').css('width', revertPercent+'%');
            $('#percent-text').text(revertPercent+'%');
            btn.prop('disabled', false).removeClass('btn-success').addClass('btn-premium').html('Mark as Completed');
            alert('Failed to save progress. Please try again.');
        }
    });
});

// Marking already completed buttons
@php $currentUserId = auth()->check() ? auth()->id() : 1; @endphp
@foreach($course->lessons as $lesson)
    @if(App\Models\Progress::where('user_id', $currentUserId)->where('lesson_id', $lesson->id)->where('completed', true)->exists())
        $('.complete[data-id="{{ $lesson->id }}"]')
            .addClass('btn-success px-4 opacity-75')
            .removeClass('btn-premium')
            .html('Completed <i class="fas fa-check-circle ms-2"></i>')
            .prop('disabled', true);
    @endif
@endforeach
</script>

@endsection
