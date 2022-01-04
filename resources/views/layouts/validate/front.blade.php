<!DOCTYPE html>
<!--[if IE 9]> <html lang="en" class="ie9"> <![endif]-->
<!--[if !IE]><!-->
<html lang="en">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>@yield('title')</title>
    <meta name="description" content="Responsive HTML5 Template">
    <meta name="author" content="webthemez">

    <!-- Mobile Meta -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('front/images/favicon.ico')}}">

    <link href="{{asset('front/bootstrap/css/bootstrap.css')}}" rel="stylesheet">
    <link href="{{asset('front/fonts/font-awesome/css/font-awesome.css')}}" rel="stylesheet">
    <link href="{{asset('front/css/animations.css')}}" rel="stylesheet">
    <link href="{{asset('front/css/style.css')}}" rel="stylesheet">
    <link href="{{asset('front/css/custom.css')}}" rel="stylesheet">
</head>

<body class="no-trans">

@yield('content')

<script type="text/javascript" src="{{asset('front/plugins/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/bootstrap/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/plugins/modernizr.js')}}"></script>
<script type="text/javascript" src="{{asset('front/plugins/isotope/isotope.pkgd.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/plugins/jquery.backstretch.min.js')}}"></script>
<script type="text/javascript" src="{{asset('front/plugins/jquery.appear.js')}}"></script>
<script type="text/javascript"  src="{{asset('front/contact/jqBootstrapValidation.js')}}"></script>
<script type="text/javascript"  src="{{asset('front/contact/contact_me.js')}}"></script>
<!-- Custom Scripts -->
<script type="text/javascript" src="{{asset('front/js/custom.js')}}"></script>

</body>

</html>
