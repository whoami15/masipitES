<header class="navbar pcoded-header navbar-expand-lg navbar-light headerpos-fixed">
	<div class="m-header">
		<a class="mobile-menu" id="mobile-collapse1" href="#!"><span></span></a>
		<a href="#" class="b-brand">
			<!-- <div class="b-bg">
				<i class="fas fa-bolt"></i>
			</div>
			<span class="b-title">Flash Able</span> -->
			<img src="{{ URL::asset('assets/backend/images/logo.png') }}" alt="" class="logo images">
			<img src="{{ URL::asset('assets/backend/images/logo-icon.png') }}" alt="" class="logo-thumb images">
		</a>
	</div>
	<a class="mobile-menu" id="mobile-header" href="#!">
		<i class="feather icon-more-horizontal"></i>
	</a>
	<div class="collapse navbar-collapse">
		<a href="#!" class="mob-toggler"></a>
		<ul class="navbar-nav ml-auto">
			<li>
				<div class="dropdown drp-user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown">
						<i class="icon feather icon-settings"></i>
					</a>
					<div class="dropdown-menu dropdown-menu-right profile-notification">
						<div class="pro-head">
							<img src="{{ URL::asset('assets/backend/images/user/avatar-1.jpg') }}" class="img-radius" alt="User-Profile-Image">
							<span>{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}</span>
							<a href="{{ url('/logout') }}" class="dud-logout" title="Logout">
								<i class="feather icon-log-out"></i>
							</a>
						</div>
						<ul class="pro-body">
							
              <li>
								@if(Auth::user()->role == 0)
								<a href="{{ url('/admin/profile') }}" class="dropdown-item"><i class="feather icon-user"></i> Profile
                @elseif(Auth::user()->role == 1)
                <a href="{{ url('/student/profile') }}" class="dropdown-item"><i class="feather icon-user"></i> Profile
                @elseif(Auth::user()->role == 2)
                <a href="{{ url('/faculty/profile') }}" class="dropdown-item"><i class="feather icon-user"></i> Profile
                @endif
                </a>
							</li>
              <li><a href="{{ url('/logout') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i class="feather icon-lock"></i> Logout</a></li>
							<form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
							{!! csrf_field() !!}
							</form>
						</ul>
					</div>
				</div>
			</li>
		</ul>
    </div>
</header>