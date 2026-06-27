@extends('layout')
@section('content')

<h3>{{ $course->title }}</h3>

<form method="POST" action="/enroll">
@csrf
<input type="hidden" name="course_id" value="{{ $course->id }}">
<input name="student_name" class="form-control mb-2" placeholder="Your Name" required>
<button class="btn btn-success">Enroll</button>
</form>

<hr>

@foreach($course->contents as $content)
<div class="card mb-2">
 <div class="card-body">
  <h6>{{ $content->title }}</h6>

  @if($content->type=='video')
  <video width="100%" controls>
   <source src="{{ asset('storage/'.$content->file_path) }}">
  </video>
  @else
  <a href="{{ asset('storage/'.$content->file_path) }}" target="_blank">View PDF</a>
  @endif

  <button class="btn btn-sm btn-primary complete mt-2">Mark Completed</button>
 </div>
</div>
@endforeach

<div class="progress mt-3">
 <div id="bar" class="progress-bar">0%</div>
</div>

<script>
let done=0,total={{ count($course->contents) }};
$('.complete').click(function(){
 done++;
 let p=Math.round(done/total*100);
 $('#bar').css('width',p+'%').text(p+'%');
 $(this).prop('disabled',true);
});
</script>
@endsection
