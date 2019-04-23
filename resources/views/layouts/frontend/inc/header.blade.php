<header>
	<div class="top-bar">
    	<div class="container">
        	<div class="row">
            	<div class="col-sm-6">
                    masipitES@gmail.com
                </div>
                <div class="col-sm-6">
                	<div class="social-icons">
                        <ul>
                            <li><a href="#" data-toggle="tooltip" title="" data-original-title="Twitter"><i class="fa fa-twitter"></i></a></li>                
                            <li><a href="#" data-toggle="tooltip" title="" data-original-title="Facebook"><i class="fa fa-facebook"></i></a></li>
                            <li><a href="#" data-toggle="tooltip" title="" data-original-title="Google Plus"><i class="fa fa-google-plus"></i></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="nav-wrap">
        <div class="container">
            <div id="menuzord" class="menuzord red pull-left">
                <a href="{{ url('/') }}" class="menuzord-brand"><img src="{{ URL::asset('assets/frontend/images/logo.png') }}" alt="logo"></a>
                <ul class="menuzord-menu">
                    <li class="active"><a href="{{ url('/') }}">Home</a>
                    </li>
                    <li><a href="{{ url('/news') }}">News</a></li>
                    <li><a href="{{ url('/events') }}">Events</a></li>
                    <li><a href="{{ url('/teachers') }}">Teachers</a></li>
                    <li><a href="{{ url('/contact') }}">Contact Us</a></li>
                    <li>
                    @auth
                        @if(Auth::user()->role == 0)
                        <a href="{{ url('/admin') }}">Dashboard
                        @elseif(Auth::user()->role == 1)
                        <a href="{{ url('/student') }}">Dashboard
                        @elseif(Auth::user()->role == 2)
                        <a href="{{ url('/faculty') }}">Dashboard
                        @endif
                        </a>
                    @else
                        <a href="{{ url('/login') }}">Login</a>
                    @endauth
                    </li>
                    
                </ul>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>
</header>