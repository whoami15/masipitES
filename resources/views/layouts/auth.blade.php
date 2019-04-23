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
	<link rel="icon" href="//html.codedthemes.com/flash-able/bootstrap/assets/images/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="{{ URL::asset('assets/backend/fonts/fontawesome/css/fontawesome-all.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('assets/backend/plugins/animation/css/animate.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('assets/backend/css/style.css') }}">
  <style type="text/css">
      @media only screen and (max-width: 420px) {
          .g-recaptcha {
              transform: scale(0.77);
              -webkit-transform: scale(0.77);
              transform-origin: 0 0;
              -webkit-transform-origin: 0 0;
          }
      }
  </style>

  @yield('header_scripts')
</head>
<body>

    @yield('content')

    <!--<script src="{{ URL::asset('assets/plugins/jquery/jquery.min.js') }}"></script>-->
    <script src="{{ URL::asset('assets/backend/js/vendor-all.min.js') }}"></script>
    <script src="{{ URL::asset('assets/backend/plugins/bootstrap/js/bootstrap.min.js') }}"></script>

    @yield('footer_scripts')
</body>
</html>