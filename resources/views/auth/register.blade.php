@extends('layouts.auth')

@section('title', 'Register')

@section('header_scripts')
<link href="{{ URL::asset('assets/plugins/angular/angular-material.min.css') }}" rel="stylesheet">
@stop

@section('content')
<div ng-app="registerApp" ng-controller="RegisterCtrl as frm">
<div class="auth-wrapper">
	<div class="auth-content container">
		<div class="card">
			<div class="row align-items-center">
				<div class="col-md-6">
					<div class="card-body">
                        <img src="{{ URL::asset('assets/backend/images/logo-dark.png') }}" alt="" class="img-fluid mb-4">
                        <form role="form" method="POST" action="{{ url('/register') }}">
                        {!! csrf_field() !!}

                        @if(session('danger'))
                        <div class="alert alert-warning" role="alert">
                        {!! session('danger') !!}
                        </div>
                        @endif

                        @if ($errors->has('g-recaptcha-response'))
                        <div class="alert alert-warning" role="alert">
                            {{ $errors->first('g-recaptcha-response') }}
                        </div>
                        @endif

                        @if ($errors->any())
                        <div class="alert alert-warning" role="alert">
                                @foreach ($errors->all() as $error)
                                * {{ $error }}<br>
                                @endforeach
                        </div>
                        @endif
                            <h4 class="mb-3 f-w-400">Sign up into your account</h4>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-user"></i></span>
                                </div>
                                <input type="text" name="first_name" id="first_name" value="{{ old('first_name') }}" class="form-control" placeholder="First Name" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-user"></i></span>
                                </div>
                                <input type="text" name="middle_name" id="middle_name" value="{{ old('middle_name') }}" class="form-control" placeholder="Middle Name" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-user"></i></span>
                                </div>
                                <input type="text" name="last_name" id="last_name" value="{{ old('last_name') }}" class="form-control" placeholder="Last Name" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-mail"></i></span>
                                </div>
                                <input type="email" name="email" id="email" value="{{ old('email') }}" class="form-control" placeholder="Email address" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-user"></i></span>
                                </div>
                                <input type="text" name="username" id="username" value="{{ old('username') }}" class="form-control" placeholder="Username" required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                </div>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Password"  required>
                            </div>
                            <div class="input-group mb-2">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                </div>
                                <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm Password"  required>
                            </div>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                </div>
                                <select name="role" id="inputState" class="form-control" ng-model="frm.role" required>
                                    <option selected="">Select Role</option>
                                    <option value="1">Student</option>
                                    <option value="2">Teacher</option>
                                </select>
                            </div>
                            <div ng-if="frm.role == 1">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                    </div>
                                    <input type="text" name="lrn" id="lrn" class="form-control" maxlength="12" placeholder="12 digit LRN">
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-user"></i></span>
                                    </div>
                                    <input type="text" name="id_no" id="id_no" value="{{ old('id_no') }}" class="form-control" placeholder="ID Number">
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-user"></i></span>
                                    </div>
                                    <select name="grade_level" id="grade_level" class="form-control" required>
                                        <option selected="">Select Grade Level</option>
                                        @if($grade_level)
                                        @foreach($grade_level as $grade)
                                        <option value="{{ $grade->id }}">{{ $grade->description }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div ng-if="frm.role == 2">
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-user"></i></span>
                                    </div>
                                    <select name="position" id="position" class="form-control" required>
                                        <option selected="">Select Position</option>
                                        @if($position)
                                        @foreach($position as $p)
                                        <option value="{{ $p->position }}">{{ $p->description }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-user"></i></span>
                                    </div>
                                    <select name="department" id="department" class="form-control" required>
                                        <option selected="">Select Department</option>
                                        @if($department)
                                        @foreach($department as $d)
                                        <option value="{{ $d->department }}">{{ $d->description }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="input-group mb-2">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="feather icon-lock"></i></span>
                                    </div>
                                    <input type="password" name="security_key" id="security_key" class="form-control" placeholder="Security Key">
                                </div>
                            </div>
                            <div class="form-group">
                                {!! NoCaptcha::display() !!}
                            </div>
                            <div class="form-group text-left mt-2">
                                <div class="checkbox checkbox-primary d-inline">
                                    <input type="checkbox" name="checkbox-fill-2" id="checkbox-fill-2" required>
                                    <label for="checkbox-fill-2" class="cr">I agree to the <a href="{{ url('/terms-of-use') }}">Terms of Use </a>and<a href="{{ url('/privacy-policy') }}"> Privacy Policy</a>.</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary mb-4">Sign up</button>
                        </form>
						<p class="mb-2">Already have an account? <a href="{{ url('/login') }}" class="f-w-400">Log in</a></p>
					</div>
				</div>
				<div class="col-md-6 d-none d-md-block">
					<img src="{{ URL::asset('assets/backend/images/auth-bg.jpg') }}" alt="" class="img-fluid">
				</div>
			</div>
		</div>
	</div>
</div>
</div>
@endsection

@section('footer_scripts')
{!! NoCaptcha::renderJs() !!}

<script src="{{ URL::asset('assets/plugins/angular/angular.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular.filter.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-animate.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-aria.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-messages.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-material.min.js') }}"></script>
<script src="{{ URL::asset('assets/plugins/angular/angular-sanitize.js') }}"></script>

<script>
    (function () {
        var registerApp = angular.module('registerApp', ['angular.filter']);
        registerApp.controller('RegisterCtrl', function ($scope, $http, $sce) {
            
        });

    })();


</script>
@stop
