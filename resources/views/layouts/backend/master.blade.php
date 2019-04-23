<!DOCTYPE html>
<html lang="en">
<head>
	<!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 11]>
		<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	<!-- Meta -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="_token" content="{!! csrf_token() !!}">
  <title>@yield('title') | Masipit Elementary School</title>
	<meta property="og:title" content="Masipit Elementary School Official Website">
  <meta name="author" content="MinSCAT Students">
  <meta property="og:locale" content="en_US">
  <meta name="description" content="Masipit Elementary School Official Website">
  <meta property="og:description" content="Masipit Elementary School Official Website">
  <link rel="canonical" href="/">
  <meta property="og:url" content="/">
  <meta property="og:site_name" content="Masipit Elementary School Official Website">
  <script type="application/ld+json">
    {
      "name": "Masipit Elementary School",
      "description": "Masipit Elementary School Official Website",
      "author":
      {
        "@type": "Person",
        "name": "MinSCAT Students"
      },
      "@type": "WebSite",
      "url": "",
      "headline": "Masipit Elementary School Official Website",
      "@context": "http://schema.org"
    }
  </script>
	<link rel="icon" href="{{ URL::asset('assets/backend/images/favicon.png') }}">
	<link rel="stylesheet" href="{{ URL::asset('assets/backend/fonts/fontawesome/css/fontawesome-all.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('assets/backend/plugins/animation/css/animate.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('assets/backend/plugins/notification/css/notification.min.css') }}">
  <link rel="stylesheet" href="{{ URL::asset('assets/backend/css/style.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('assets/backend/plugins/pnotify/css/pnotify.custom.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('assets/backend/css/pages/pnotify.css') }}">


  @yield('header_scripts')
</head>
<body class="">

  <div class="loader-bg">
		<div class="loader-track">
			<div class="loader-fill"></div>
		</div>
  </div>
  
    @include('layouts.backend.inc.sidebar')

    @include('layouts.backend.inc.header')

    <div class="pcoded-main-container">
      <div class="pcoded-wrapper">
        <div class="pcoded-content">
          <div class="pcoded-inner-content">
            <div class="main-body">
              <div class="page-wrapper">
                @yield('content')
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    
    <script src="{{ URL::asset('assets/backend/js/vendor-all.min.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/bootstrap/js/bootstrap.min.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/js/pcoded.min.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/chart-am4/js/core.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/chart-am4/js/charts.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/chart-am4/js/animated.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/chart-am4/js/maps.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/chart-am4/js/worldLow.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/chart-am4/js/continentsLow.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/ratting/js/jquery.barrating.min.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/js/pages/dashboard-project.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/pnotify/js/pnotify.custom.min.js') }}"></script>

    <script src="{{ URL::asset('assets/plugins/angular/angular.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/angular/angular.filter.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/angular/angular-animate.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/angular/angular-aria.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/angular/angular-messages.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/angular/angular-material.min.js') }}"></script>
    <script src="{{ URL::asset('assets/plugins/angular/angular-sanitize.js') }}"></script>

    @yield('footer_scripts')

</body>
</html>