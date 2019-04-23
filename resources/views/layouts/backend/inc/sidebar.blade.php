<nav class="pcoded-navbar menupos-fixed menu-light brand-blue ">
	<div class="navbar-wrapper ">
		<div class="navbar-brand header-logo">
			<a href="#" class="b-brand">
				<!-- <div class="b-bg">
					<i class="fas fa-bolt"></i>
				</div>
				<span class="b-title">Flash Able</span> -->
				<img src="{{ URL::asset('assets/backend/images/logo.png') }}" alt="" class="logo images">
				<img src="{{ URL::asset('assets/backend/images/logo-icon.png') }}" alt="" class="logo-thumb images">
			</a>
			<a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
		</div>
		<div class="navbar-content scroll-div ">
			
			<ul class="nav pcoded-inner-navbar ">
                @if(Auth::user()->role == 0)
				<li class="nav-item pcoded-menu-caption">
					<label>Admin</label>
                </li>
                <li data-username="dashboard default ecommerce sales Helpdesk ticket CRM analytics project" class="nav-item">
					<a href="{{ url('/admin') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
                </li>
                <li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">Users</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/admin/list') }}" class="">List</a></li>
						<li class=""><a href="{{ url('/admin/pending') }}" class="">Pending</a></li>
						<li class=""><a href="{{ url('/admin/user/grade-level') }}" class="">Transfer Grade Level</a></li>
					</ul>
                </li>
                <li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">Announcements</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/admin/announcements') }}" class="">List</a></li>
					</ul>
                </li>
                <li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">Files</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/admin/files') }}" class="">List</a></li>
					</ul>
                </li>
				<li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">Security Keys</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/admin/security-keys') }}" class="">List</a></li>
                        <li class=""><a href="{{ url('/admin/security-keys/generate') }}" class="">Generate</a></li>
					</ul>
				</li>
				<li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">Messages</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/admin/messages/public') }}" class="">Public</a></li>
                        <!--li class=""><a href="{{ url('/admin/messages/private') }}" class="">Private</a></li-->
					</ul>
				</li>
				<li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">Frontend</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/admin/news') }}" class="">News</a></li>
						<li class=""><a href="{{ url('/admin/events') }}" class="">Events</a></li>
					</ul>
				</li>
				<li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Settings</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/admin/settings/subject') }}" class="">Subject</a></li>
						<li class=""><a href="{{ url('/admin/settings/grade-level') }}" class="">Grade Level</a></li>
						<!--li class=""><a href="{{ url('/admin/settings/school-year') }}" class="">School Year</a></li>
						<li class=""><a href="{{ url('/admin/settings/position') }}" class="">Position</a></li>
						<li class=""><a href="{{ url('/admin/settings/department') }}" class="">Department</a></li-->
					</ul>
				</li>
                @endif
                @if(Auth::user()->role == 1)
				<li class="nav-item pcoded-menu-caption">
					<label>Student</label>
                </li>
                <li data-username="dashboard default ecommerce sales Helpdesk ticket CRM analytics project" class="nav-item">
					<a href="{{ url('/student') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
				</li>
				<li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">E-learning</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/student/elearning') }}" class="">Learning Materials</a></li>
					</ul>
                </li>
                @endif
                @if(Auth::user()->role == 2)
                <li class="nav-item pcoded-menu-caption">
					<label>Faculty</label>
                </li>
                <li data-username="dashboard default ecommerce sales Helpdesk ticket CRM analytics project" class="nav-item">
					<a href="{{ url('/faculty') }}" class="nav-link"><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a>
				</li>
				<li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">Announcements</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/faculty/announcements') }}" class="">List</a></li>
                        <li class=""><a href="{{ url('/faculty/announcements/create') }}" class="">Create</a></li>
					</ul>
                </li>
                <li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">E-learning</span></a>
					<ul class="pcoded-submenu">
						<li class=""><a href="{{ url('/faculty/class') }}" class="">Class</a></li>
						<li class=""><a href="{{ url('/faculty/class/add') }}" class="">Add Class</a></li>
						<li class=""><a href="{{ url('/faculty/elearning') }}" class="">Learning Materials</a></li>
                        <li class=""><a href="{{ url('/faculty/elearning/upload') }}" class="">Upload Learning Material</a></li>
					</ul>
                </li>
                <li data-username="menu levels menu level 2.1 menu level 2.2" class="nav-item pcoded-hasmenu">
					<a href="#" class="nav-link"><span class="pcoded-micon"><i class="feather icon-menu"></i></span><span class="pcoded-mtext">Messages</span></a>
					<ul class="pcoded-submenu">
                        <li class=""><a href="{{ url('/faculty/messages/public') }}" class="">Public</a></li>
					</ul>
                </li>
				@endif
				<li data-username="dashboard view website" class="nav-item">
					<a href="{{ url('/') }}" class="nav-link" target="_blank"><span class="pcoded-micon"><i class="feather icon-globe"></i></span><span class="pcoded-mtext">View Site</span></a>
                </li>
			</ul>
		</div>
	</div>
</nav>