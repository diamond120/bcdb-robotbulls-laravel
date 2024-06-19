<!DOCTYPE html>
<html lang="{{ $lang['lang'] }}" class="js">
<head>
    <meta charset="utf-8">
    <meta name="apps" content="RobotBulls">
    <meta name="author" content="{{ site_whitelabel('author') }}">
    <meta name="description" content="Research in the field of Artificial Intelligence, we focuse on utilizing market volatility to our advantage. We have developed an automated AI system that specializes in market prediction. Our team has created a predictive model that analyzes past market experiences and current market trends to provide insights and make informed decisions. With this technology, our clients can have a more accurate understanding of market movements and make data-driven decisions.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-token" content="{{ site_token() }}">
    <link rel="shortcut icon" href="{{ site_favicon() }}">
<!--    <link rel="apple-touch-icon-precomposed" href="{{ site_favicon() }}">-->
    <meta name="msapplication-TileImage" content="{{ site_favicon() }}">
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    <title>@yield('title') | {{ site_whitelabel('title') }}</title>
    
    <link rel="manifest" href="{{ asset('assets/manifest.json') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="RobotBulls">
<!--    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">-->
    <link rel="apple-touch-icon" href="{{ asset('images/logo-192x192.png') }}">

    

    <script type="module" src="{{ asset('assets/js/ionic.esm.js') }}"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.10/css/intlTelInput.css">
    <style>
        .intl-tel-input{
            width: 100%;
        }
    </style>
    <link rel="stylesheet" href="{{ asset(style_theme('vendor')) }}">
    <link rel="stylesheet" href="{{ asset(style_theme('user')) }}">
    
    <script>
        function isStandalone() {
          return ('standalone' in window.navigator) && window.navigator.standalone;
        }

        if (isStandalone()) {
          const viewport = document.querySelector("meta[name=viewport]");
          viewport.setAttribute("content", "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no");
        }
      </script>
    
    @if( recaptcha() )
    <script src="https://www.google.com/recaptcha/api.js?render={{ recaptcha('site') }}"></script>
    @endif
    @stack('header')
    @if(get_setting('site_header_code', false))
    {{ html_string(get_setting('site_header_code')) }}
    @endif
    
    <!-- TikTok Pixel Code Start -->
<script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};


  ttq.load('C2HQ4AAQV140ORDJ449G');
  ttq.page();
}(window, document, 'ttq');
</script>
<!-- TikTok Pixel Code End -->

<style>
        /* :host {
            --background: transparent !important;
        } */

        /* ion-content {
            --background: transparent;
        } */

        .topbar {
            /* border-top: 3px solid white; */
        }
        
        #top_loader {
            position: absolute;
            width: 100%;
            height: 3px;
            top: -3px;
            background-color: #253992;
            /* transition: .1s all ease-out; */
            left: 0;
        }

    </style>
    
</head>
@php 
$auth_layout = (gws('theme_auth_layout', 'default'));
$logo_light = ($auth_layout=='center-dark') ? 'logo-light' : 'logo';
$body_class = ($auth_layout=='center-dark'||$auth_layout=='center-light') ? ' page-ath-alt' : '';
$body_bgc   = ($auth_layout=='center-dark') ? ' bg-secondary' : '';
$wrap_class = ($auth_layout=='default') ? ' flex-row-reverse' : '';

$header_logo = '<div class="page-ath-header"><a href="'.url('/').'" class="page-ath-logo"><img class="page-ath-logo-img" src="'. site_whitelabel($logo_light) .'" srcset="'. site_whitelabel($logo_light.'2x') .'" alt="'. site_whitelabel('name') .'"></a></div>';
@endphp
<body class="page-ath theme-modern page-ath-modern{{ $body_class.$body_bgc }}">

    <div class="page-ath-wrap{{ $wrap_class }}">
        <div class="page-ath-content">
            {!! $header_logo !!}
            @yield('content')
            
            <div class="page-ath-footer">
                @if(is_show_social('login'))
                    {!! UserPanel::social_links('', ['class' => 'mb-3']) !!}
                    {!! UserPanel::footer_links(['lang' => true], ['class' => 'guttar-20px align-items-center']) !!}
                    {!! UserPanel::copyrights('div') !!}
                    {!! UserPanel::language_switcher_auth($lang) !!}
                @else
                    {!! UserPanel::footer_links(['lang' => true, 'copyright'=>true], ['class' => 'guttar-20px align-items-center']) !!}
                    {!! UserPanel::copyrights($lang, 'div') !!}    
                @endif
            </div>
        </div>
        @if ($auth_layout=='default' || $auth_layout=='alter')
        <div class="page-ath-gfx" style="background-image: url({{ asset('images/ath-gfx.png') }});">
            <div class="w-100 d-flex justify-content-center">
                <div class="col-md-8 col-xl-5">
                    {{-- <img src="{{ asset('images/intro.png') }}" alt=""> --}}
                </div>
            </div>
        </div>
        @endif
    </div>

@if(gws('theme_custom'))
    <link rel="stylesheet" href="{{ asset(style_theme('custom')) }}">
@endif
    <script>
        var base_url = "{{ url('/') }}",
        csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        layouts_style = "modern";
    </script>
    <script>
        $("a").click(function() {
            console.log('a');
            $('.page-overlay').addClass('is-loading');
        });
    </script>
    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
    <script type="text/javascript">
        jQuery(function(){
            var $frv = jQuery('.validate');
            if($frv.length > 0){ $frv.validate({ errorClass: "input-bordered-error error" }); }
        });
    </script>
    @stack('footer')

    @if(get_setting('site_footer_code', false))
    {{ html_string(get_setting('site_footer_code')) }}
    @endif
    
    <script>
  window.fbAsyncInit = function() {
    FB.init({
      appId            : '443687586984360',
      autoLogAppEvents : true,
      xfbml            : true,
      version          : 'v9.0'
    });
  };
</script>
<script async defer crossorigin="anonymous" src="https://connect.facebook.net/en_US/sdk.js"></script>

<script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "744gohv0op");
</script>
    
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-2R911DF9NH"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-2R911DF9NH');
</script>
 

</body>
</html>