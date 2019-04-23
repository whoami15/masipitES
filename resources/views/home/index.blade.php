@extends('layouts.frontend.master')
@section('title', 'Home')

@section('content')
@include('layouts.frontend.inc.carousel')

<section id="body-content">
    <div class="container">
        <div class="row">
            <div class="clearfix"></div>
            @include('home.inc.main')
            @include('home.inc.right-sidebar')
        </div>
    </div>
</section>
@endsection