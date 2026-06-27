@extends('layout')
@section('content')
<div class="row">
@foreach($courses as $course)
<div class="col-md-4">
<div class="card mb-3">
<div class="card-body">
<h5>{{ $course->title }}</h5>
<p>{{ $course->description }}</p>
<a href="/course/{{ $course->id }}" class="btn btn-primary">View</a>
</div>
</div>
</div>
@endforeach
</div>
@endsection
