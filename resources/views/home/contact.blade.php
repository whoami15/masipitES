@extends('layouts.frontend.master')
@section('title', 'Contact Us')

@section('content')
<!-- xxx Body Content Start xxx -->
<section id="body-content">  	
        <div class="container">
            <div class="row">            	
                <div class="col-md-12"> 
                    <div class="about-wrap">
                        <div class="blog-outer">
                            <h3>Contact Us</h3>
                            <div class="row">
                                
                                <form role="form" method="POST" action="{{ url('/contact') }}" id="new_contact_form">
                                    {!! csrf_field() !!}

                                    @if(session('success'))
                                    <div class="col-sm-12">
                                        <div class="alert alert-success" role="alert">
                                            {!! session('success') !!}
                                        </div>
                                    </div>
                                    @endif

                                    @if(session('danger'))
                                    <div class="col-sm-12">
                                        <div class="alert alert-warning" role="alert">
                                        {!! session('danger') !!}
                                        </div>
                                    </div>
                                    @endif

                                    @if ($errors->any())
                                    <div class="col-sm-12">
                                        <div class="alert alert-warning" role="alert">
                                            @foreach ($errors->all() as $error)
                                            * {{ $error }}<br>
                                            @endforeach
                                        </div>
                                    </div>
                                    @endif
                                    <div class="col-sm-4">
                                        <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Name" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Email" required>
                                    </div>
                                    <div class="col-sm-4">
                                        <input type="text" name="subject" class="form-control" value="{{ old('subject') }}" placeholder="Subject" required>
                                    </div>
                                    <div class="col-sm-12">
                                        <textarea name="content" class="form-control" rows="8" placeholder="Message" required>{{ old('content') }}</textarea>
                                    </div>
                                    <div class="col-sm-12">
                                        <button type="submit" class="form-btn">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        
                        
                    </div>
                </div>
            </div>
        </div>
    </section>    
    <!-- xxx Body Content End xxx -->
@endsection