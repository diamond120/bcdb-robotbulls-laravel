<!DOCTYPE html>
<html lang="{{ $lang['lang'] }}" class="js">

<head>
    <meta charset="utf-8">
    <meta name="apps" content="{{ site_whitelabel('apps') }}">
    <meta name="author" content="{{ site_whitelabel('author') }}">
    <meta name="description" content="RobotBulls is multinational fintech startup. We develop trading algorithms for market predictions based on Python combining big data analytics, alternative data, AI & MS to reach the best possible market performance of your personal portfolio. We believe in the efficiency of automated trading and aim to help more people enjoy its benefits.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-token" content="{{ site_token() }}">
    <link rel="shortcut icon" href="{{ site_favicon() }}">
    <link rel="apple-touch-icon-precomposed" href="{{ site_favicon() }}">
    <meta name="msapplication-TileImage" content="{{ site_favicon() }}">
    <title>@yield('title') | {{ site_whitelabel('title') }}</title>

    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@ionic/core/css/ionic.bundle.css" /> -->
    <!-- <script type="module" src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.esm.js"></script> -->
    <script type="module" src="{{ asset('assets/js/ionic.esm.js') }}"></script>
    <!-- <script type="module" src="{{ asset('assets/css/ionic-loader.css') }}"></script> -->
    <!-- <script nomodule src="https://cdn.jsdelivr.net/npm/@ionic/core/dist/ionic/ionic.js"></script>  -->

    <link rel="stylesheet" href="{{ asset(style_theme('vendor')) }}">
    <link rel="stylesheet" href="{{ asset(style_theme('user')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @stack('header')
    @if(get_setting('site_header_code', false))
    {{ html_string(get_setting('site_header_code')) }}
    @endif
    
    <script src="{{ asset('assets/js/jquery.min.js') }}"></script>
    
     <!--Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-2R911DF9NH"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', 'G-2R911DF9NH');
</script>
    
    <script src="https://js.stripe.com/v3/"></script>
    <script src="{{ asset('assets/js/charge_stripe.js') }}"></script>
    
    <!-- TikTok Pixel Code Start -->
<script>
!function (w, d, t) {
  w.TiktokAnalyticsObject=t;var ttq=w[t]=w[t]||[];ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"],ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e},ttq.load=function(e,n){var i="https://analytics.tiktok.com/i18n/pixel/events.js";ttq._i=ttq._i||{},ttq._i[e]=[],ttq._i[e]._u=i,ttq._t=ttq._t||{},ttq._t[e]=+new Date,ttq._o=ttq._o||{},ttq._o[e]=n||{};var o=document.createElement("script");o.type="text/javascript",o.async=!0,o.src=i+"?sdkid="+e+"&lib="+t;var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};


  ttq.load('C2HQ4AAQV140ORDJ449G');
  ttq.page();
}(window, document, 'ttq');
</script>
<!-- TikTok Pixel Code End -->
    
    <!-- <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.css"/>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css"/>-->
    <style> 
        
        /* body{
            --ion-background-color: #e0e8f3;
        }

        :host {
            --background: transparent;
        } */
        
        /* ion-content{
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

<body class="user-dashboard page-user theme-modern">

    <div class="topbar-wrap">
        <div class="topbar is-sticky" style="background:#2b7ffc">
            <div class="container">
                <div class="d-flex justify-content-between align-items-center">
                    <ul class="topbar-nav d-lg-none">
                        <li class="topbar-nav-item relative">
                            <div class="toggle-nav toggle-tigger" href="#">
                                <div class="toggle-icon">
                                    <span class="toggle-line"></span>
                                    <span class="toggle-line"></span>
                                    <span class="toggle-line"></span>
                                    <span class="toggle-line"></span>
                                </div>
                            </div>
                        </li>{{-- .topbar-nav-item --}}
                    </ul>{{-- .topbar-nav --}}

                    <a class="topbar-logo" href="{{ url('/') }}">
                        <img height="40" src="{{ site_whitelabel('logo-light') }}" srcset="{{ site_whitelabel('logo-light2x') }}" alt="{{ site_whitelabel('name') }}">
                    </a>
                    <ul class="topbar-nav">
                        <li class="topbar-nav-item relative">
                            <span class="user-welcome d-none d-lg-inline-block welcome">{{ $lang['demo'] }}</span>
                            <div class="toggle-tigger user-thumb" href="#"><em class="ti ti-user"></em></div>
                            <div class="toggle-class dropdown-content dropdown-content-right dropdown-arrow-right user-dropdown">
                                {!! UserPanel::user_balance($lang) !!}
                                {!! UserPanel::user_logout_link($lang) !!}
                            </div>
                        </li>{{-- .topbar-nav-item --}}
                    </ul>{{-- .topbar-nav --}}
                </div>
            </div>{{-- .container --}}
        </div>{{-- .topbar --}}

        <div class="navbar">
            <div class="container">
                <div class="navbar-innr">
                    <ul class="navbar-menu" id="main-nav">
                        <li><a href="{{ route('user.demo.home') }}" class="header1"><em class="ikon ikon-home-link"></em> {{__( $lang['dashboard'] )}}</a></li>
                        <li><a href="{{ route('user.demo.investment') }}" class="header2"><em class="ikon ikon-my-token"></em> {{ __( $lang['new_investment'] ) }}</a></li>
                        <li><a href="{{ route('user.home') }}" class="header2"><em class="ikon ikon-exchange"></em> {{ $lang['return_to_main_account'] }}</a></li>
                    </ul>
                    @if(!is_kyc_hide())
                    <ul class="navbar-btns">
                        @if(isset(Auth::user()->kyc_info->status) && Auth::user()->kyc_info->status == 'approved')
                        <li><span class="badge badge-outline badge-success badge-lg"><em class="text-success ti ti-files mgr-1x"></em><span class="text-success KYC_approved">{{__( $lang['kyc_approved'] )}}</span></span></li>
                        @else
                        <li><a href="{{ route('user.kyc') }}" class="btn btn-sm btn-outline btn-light"><em class="text-primary ti ti-files"></em><span><span class="KYC_button">{{__( $lang['kyc_application'] )}}</span></span></a></li>
                        @endif
                    </ul>
                    @endif
                </div>{{-- .navbar-innr --}}
            </div>{{-- .container --}}
        </div>{{-- .navbar --}}
    </div>{{-- .topbar-wrap --}}

    <div class="page-content">
        <div class="container">
            <div class="row">
                @php
                $has_sidebar = isset($has_sidebar) ? $has_sidebar : false;
                $col_side_cls = ($has_sidebar) ? 'col-lg-4' : 'col-lg-12';
                $col_cont_cls = ($has_sidebar) ? 'col-lg-8' : 'col-lg-12';
                $col_cont_cls2 = isset($content_class) ? css_class($content_class) : null;
                $col_side_cls2 = isset($aside_class) ? css_class($aside_class) : null;
                @endphp

                <div class="main-content {{ empty($col_cont_cls2) ? $col_cont_cls : $col_cont_cls2 }}">
                    @if(!has_wallet() && gws('token_wallet_req')==1 && !empty(token_wallet()))
                    <div class="d-lg-none">
                        {!! UserPanel::add_wallet_alert($lang) !!}
                    </div>
                    @endif
                    @yield('content')
                </div>

                @if ($has_sidebar==true)
                <div class="aside sidebar-right {{ empty($col_side_cls2) ? $col_side_cls : $col_side_cls2 }}">
                    <div class="d-none d-lg-block">
                        {!! UserPanel::add_wallet_alert($lang) !!}
                    </div>
                    {!! UserPanel::token_sales_ad($lang) !!}
                    {!! (!is_page(get_slug('referral')) ? UserPanel::user_referral_info($lang) : '') !!}
                    {!! UserPanel::user_kyc_info($lang) !!}
                </div>{{-- .col --}}
                @else
                @stack('sidebar')
                @endif

            </div>
        </div>{{-- .container --}}
    </div>{{-- .page-content --}}

    <div class="footer-bar">
        <div class="container">
            @if(is_show_social('site'))
            <div class="row justify-content-center">
                <div class="col-lg-5 text-center order-lg-last text-lg-right pdb-2x pb-lg-0">
                    {!! UserPanel::social_links($lang) !!}
                </div>
                <div class="col-lg-7">
                    <div class="d-flex align-items-center justify-content-center justify-content-lg-start guttar-15px pdb-1-5x pb-lg-2">
                        {!! UserPanel::copyrights($lang,'div') !!}
                        {!! UserPanel::language_switcher($lang) !!}
                    </div>
                    {!! UserPanel::footer_links($lang, null, ['class'=>'align-items-center justify-content-center justify-content-lg-start']) !!}
                </div>
            </div>{{-- .row --}}
            @else
            <div class="row align-items-center justify-content-center">
                <div class="col-lg-7">
                    {!! UserPanel::footer_links($lang, null, ['class'=>'guttar-20px']) !!}
                </div>
                <div class="col-lg-5 mt-2 mt-sm-0">
                    <div class="d-flex justify-content-between justify-content-lg-end align-items-center guttar-15px">
                        {!! UserPanel::copyrights($lang, 'div') !!}
                        {!! UserPanel::language_switcher($lang) !!}
                    </div>
                </div>
            </div>{{-- .row --}}
            @endif
        </div>{{-- .container --}}
    </div>{{-- .footer-bar --}}
    @yield('modals')
    <div id="ajax-modal"></div>
    <div class="page-overlay">
        <div class="spinner"><span class="sp sp1"></span><span class="sp sp2"></span><span class="sp sp3"></span></div>
    </div>



    @if(gws('theme_custom'))
    <link rel="stylesheet" href="{{ asset(style_theme('custom')) }}">
    @endif
    <script>
        var base_url = "{{ url('/') }}",
        {!! (has_route('transfer:user.send')) ? 'user_token_send = "'.route('transfer:user.send').'",' : '' !!}
        {!! (has_route('withdraw:user.request')) ? 'user_token_withdraw = "'.route('withdraw:user.request').'",' : '' !!}
        {!! (has_route('user.ajax.account.wallet')) ? 'user_wallet_address = "'.route('user.ajax.account.wallet').'",' : '' !!}
        csrf_token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    </script>
    <script>
        $("a").click(function() {
            console.log('a');
            $('.page-overlay').addClass('is-loading');
        });
    </script>
    <script src="{{ asset('assets/js/jquery.bundle.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
    <script src="{{ asset('assets/js/app.js').css_js_ver() }}"></script>

    <script src="{{ asset('assets/js/admin.chart.js') }}"></script>
    <script src="{{ asset('assets/js/form.js') }}"></script>
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>
    <!-- <script src="{{ asset('assets/js/language.js') }}"></script> -->
    
    <script type="text/javascript">
    (function(c,l,a,r,i,t,y){
        c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};
        t=l.createElement(r);t.async=1;t.src="https://www.clarity.ms/tag/"+i;
        y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y);
    })(window, document, "clarity", "script", "744gohv0op");
</script>

    @stack('footer')
    <script type="text/javascript">
        @if(session('resent'))
        show_toast("success", "{{ __( $lang['a_fresh_verification_link_has_been_sent_to_your_email'] ) }}");
        @endif

    </script>
    
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script>
    // $('.single-item').slick();
    
        $(".single-item").slick({
          arrows: false,
          dots: true
         });
    
    
        // $(".single-item").slick({
        //   slidesToShow: 1,
        //   slidesToScroll: 1,
        //   arrows: false,
        //   dots: true,
        //   fade: true
        //  });
    </script>


    @if(get_setting('site_footer_code', false))
    {{ html_string(get_setting('site_footer_code')) }}
    @endif
</body>

</html>
