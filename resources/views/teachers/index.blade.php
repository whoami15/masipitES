@extends('layouts.frontend.master')
@section('title', 'Teachers')

@section('content')
<!-- xxx Breadcrumb Start xxx -->
<section>
	<div class="breadcrumb">
    	<h1>Teachers</h1>
    </div>
</section>
<!-- xxx Breadcrumb End xxx -->

<!-- xxx Body Content Start xxx -->
<section id="body-content">    	
	<div class="container">
        <div class="row">
        @if($teachers)
                @foreach($teachers as $teacher)
            <div class="col-sm-4 col-md-4 col-lg-4 text-center">
                
                <div class="author-image">
                	<img src="{{ url('assets/frontend/images/teacher.jpg') }}" alt="" class="img-circle">
                </div>
                <br>
                <address>
                    <strong>{{ $teacher->full_name}}</strong><br>
                    <a href="mailto:{{ $teacher->email }}">{{ $teacher->email }}</a><br>
                    Position: {{ $teacher->position }}<br>
                    Department: {{ $teacher->department }}
                </address>
                <hr>
                
            </div>
            @endforeach
                @endif
            <!--Divider-->
            <div class="col-sm-12">
                <div class="divider-wrap">&nbsp;</div>
            </div>
            <!--Divider End-->            
        </div>
   	</div>    
</section>
<!-- xxx Body Content End xxx -->
@endsection