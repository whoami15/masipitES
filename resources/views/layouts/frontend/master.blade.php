<!DOCTYPE html>
<!--[if lt IE 7]>
<html class="no-js lt-ie9 lt-ie8 lt-ie7">
<![endif]-->
<!--[if IE 7]>
<html class="no-js lt-ie9 lt-ie8">
<![endif]-->
<!--[if IE 8]>
<html class="no-js lt-ie9">
<![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="_token" content="{!! csrf_token() !!}">
    <title>@yield('title') | Masipit Elementary School</title>
    <meta property="og:title" content="Masipit Elementary School Official Website">
    <meta name="author" content="MinSCAT Students">
    <meta property="og:locale" content="en_US">
    <meta name="description" content="The Official Website of Masipit Elementary School">
    <meta property="og:image" content="{{ URL::asset('assets/frontend/images/og_image.jpg') }}">
    <meta property="og:image:width" content="400"> 
    <meta property="og:image:height" content="300">
    <meta property="og:description" content="The Official Website of Masipit Elementary School">
    <meta name="twitter:card" content="summary_large_image">
    <meta property="og:url" content="http://masipites.com">
    <meta property="og:site_name" content="Masipit Elementary School Official Website">
    <meta name="twitter:image:alt" content="Cover">
    <meta name="viewport" content="width=device-width">
    <link rel="shortcut icon" href="{{ URL::asset('assets/frontend/favicon/favicon.png') }}">
    <link rel="apple-touch-icon-precomposed" href="{{ URL::asset('assets/frontend/favicon/apple-touch-icon.png') }}">
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ URL::asset('assets/frontend/favicon/apple-touch-icon-57x57.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ URL::asset('assets/frontend/favicon/apple-touch-icon-72x72.png') }}" />
    <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ URL::asset('assets/frontend/favicon/apple-touch-icon-114x114.png') }}" />
    <link href='//fonts.googleapis.com/css?family=Open+Sans:400,400italic,600,600italic,700,300' rel='stylesheet' type='text/css'>
    <link href='//fonts.googleapis.com/css?family=Ubuntu:400,300italic,300,400italic,500,500italic,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" media="screen" href="{{ URL::asset('assets/frontend/css/base.css') }}" />
    <link rel="stylesheet" href="{{ URL::asset('assets/frontend/css/theme/red.css') }}">
    <script src="{{ URL::asset('assets/frontend/js/modernizr-2.6.2.min.js') }}"></script>
    <script type="application/ld+json">
      {
        "name": "Masipit Elementary School Official Website",
        "description": "The Official Website of Masipit Elementary School",
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

    @yield('header_scripts')
    
</head>
<body class="wide">

    @include('layouts.frontend.inc.header')

    @include('layouts.frontend.inc.sidebar')

    @yield('content')

    @include('layouts.frontend.inc.footer')

    <div id="back-top">
        <a class="img-circle" href="#top">
            <i class="fa fa-angle-up"></i>
        </a>
    </div>


    <script type="text/javascript" src="{{ URL::asset('assets/frontend/js/jquery-min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/frontend/js/bootstrap.min.js') }}"></script>
    <script type='text/javascript' src="{{ URL::asset('assets/frontend/js/menuzord.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/frontend/js/jquery.cookie.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('assets/frontend/js/owl.carousel.min.js') }}"></script>
    <script type='text/javascript' src="{{ URL::asset('assets/frontend/js/jflickrfeed.min.js') }}"></script>
    <script type='text/javascript' src="{{ URL::asset('assets/frontend/js/jquery.fancybox.js') }}"></script>
    <script type='text/javascript' src="{{ URL::asset('assets/frontend/js/jquery.cubeportfolio.min.js') }}"></script>
    <script type='text/javascript' src="{{ URL::asset('assets/frontend/js/jquery.validate.min.js') }}"></script>
    <script type='text/javascript' src="{{ URL::asset('assets/frontend/twitter/jquery.tweet.js') }}"></script>
    <!--<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>-->
	  <!--<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/jquery.gmap.min.j') }}"></script>-->
    <script type='text/javascript' src="{{ URL::asset('assets/frontend/js/site-custom.js') }}"></script>
    <script type='text/javascript' src="{{ URL::asset('assets/frontend/js/fullwidth-post.js') }}"></script>

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
<!-- l3g10n
        (___()'`; (MEOW)
        /,    /`
        \\"--\\
    ~~~~~~~~~~~~~~-->