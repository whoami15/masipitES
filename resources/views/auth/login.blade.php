@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="auth-wrapper">
	<div class="auth-content container">
		<div class="card">
			<div class="row align-items-center">
				<div class="col-md-6">
					<div class="card-body">
            <img src="{{ URL::asset('assets/backend/images/logo-dark.png') }}" alt="" class="img-fluid mb-4">
            <form role="form" method="POST" action="{{ url('/login') }}">
              {!! csrf_field() !!}

              @if(session('banned'))
              <div class="alert alert-danger" role="alert">
                {!! session('banned') !!}
              </div>
              @endif

              @if(session('success'))
              <div class="alert alert-success" role="alert">
                {!! session('success') !!}
              </div>
              @endif

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
              <h4 class="mb-3 f-w-400">Login into your account</h4>
              <div class="input-group mb-2">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="feather icon-user"></i></span>
                </div>
                <input type="text" name="username" class="form-control" placeholder="Username" required="" autofocus="">
              </div>
              <div class="input-group mb-3">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="feather icon-lock"></i></span>
                </div>
                <input type="password" name="password" class="form-control" placeholder="Password" required="">
              </div>
              <div class="form-group">
                  {!! NoCaptcha::display() !!}
              </div>
              
              <button type="submit" class="btn btn-primary mb-4">Login</button>
            </form>
            <p class="mb-0 text-muted">Don’t have an account? <a href="{{ url('/register') }}" class="f-w-400">Signup</a></p>
          </div>
				</div>
				<div class="col-md-6 d-none d-md-block">
					<img src="{{ URL::asset('assets/backend/images/auth-bg.jpg') }}" alt="" class="img-fluid">
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

@section('footer_scripts')
{!! NoCaptcha::renderJs() !!}
@stop
