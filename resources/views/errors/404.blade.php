@php
include "/home/robotbq/app.robotbulls.com/config_u.php";
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="js">
<head>
    <meta charset="utf-8">
    <meta name="apps" content="{{ app_info() }}">
    <meta name="author" content="Softnio">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{$lang['not_found']}}</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/vendor.bundle.css').css_js_ver() }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style-blue.css').css_js_ver() }}">
</head>
@php 
$bg_img = " style=\"background-image:url('".asset('assets/images/bg-error.png')."'\"";
@endphp

<body class="page-error error-404 theme-modern"{!! $bg_img !!}>

    <div class="vh100 d-flex align-items-center">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-7 col-xl-6 text-center">
                    <div class="error-content">
                        <span class="error-text-large">404</span>
                        <h4 class="text-dark">{{$lang['why_you_here']}}</h4>
                        <p>{{$lang['we_are_sorry']}}</p>
                        <a href="{{ url('/') }}" class="btn btn-primary">{{$lang['back_to_home']}}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="page-overlay">
        <div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
    </div>

    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
    <script>
        $("a").click(function() {
            console.log('a');
            $('.page-overlay').addClass('is-loading');
        });
    </script>
</body>
</html>