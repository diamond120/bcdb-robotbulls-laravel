@php
$user = auth()->user();
use App\Models\Transaction;
$trnxs_2 = Transaction::where('user', Auth::id())
                    ->where('status', '!=', 'deleted')
                    ->where('status', '!=', 'canceled')
                    ->where('status', '!=', 'pending')
                    ->where('status', '!=', 'expired') 
                    ->where('status', '!=', 'rejected')
                    ->where('status', '!=', 'inactive')
                    ->where('status', '!=', 'bull')
                    ->where('status', '!=', 'new')
                    ->where('receive_currency', '!=', 'usd')
                    ->where('receive_currency', '!=', 'eur')
                    ->where('receive_currency', '!=', 'gbp')
                    ->where('receive_currency', '!=', 'cad')
                    ->where('receive_currency', '!=', 'aud')
                    ->where('receive_currency', '!=', 'try')
                    ->where('receive_currency', '!=', 'rub')
                    ->where('receive_currency', '!=', 'inr')
                    ->where('receive_currency', '!=', 'myr')
                    ->where('receive_currency', '!=', 'idr')
                    ->where('receive_currency', '!=', 'ngn')
                    ->where('receive_currency', '!=', 'mxn')
                    ->where('receive_currency', '!=', 'php')
                    ->where('receive_currency', '!=', 'chf')
                    ->where('receive_currency', '!=', 'thb')
                    ->where('receive_currency', '!=', 'sgd')
                    ->where('receive_currency', '!=', 'czk')
                    ->where('receive_currency', '!=', 'nok')
                    ->where('receive_currency', '!=', 'zar')
                    ->where('receive_currency', '!=', 'sek')
                    ->where('receive_currency', '!=', 'kes')
                    ->where('receive_currency', '!=', 'nad')
                    ->where('receive_currency', '!=', 'dkk')
                    ->where('receive_currency', '!=', 'hkd')
                    ->where('receive_currency', '!=', 'eth')
                    ->where('receive_currency', '!=', 'btc')
                    ->where('receive_currency', '!=', 'ltc')
                    ->where('receive_currency', '!=', 'xrp')
                    ->where('receive_currency', '!=', 'xlm')
                    ->where('receive_currency', '!=', 'bch')
                    ->where('receive_currency', '!=', 'bnb')
                    ->where('receive_currency', '!=', 'usdt')
                    ->where('receive_currency', '!=', 'trx')
                    ->where('receive_currency', '!=', 'usdc')
                    ->where('receive_currency', '!=', 'dash')
                    ->where('receive_currency', '!=', 'waves')
                    ->where('receive_currency', '!=', 'xmr')
                    ->whereNotIn('tnx_type', ['withdraw'])
                    ->orderBy('created_at', 'DESC')->get();

        $balance_crypto = 0;
        $trnxs_index = count($trnxs_2);

        for($i = 0; $i < $trnxs_index; $i++){
            if($trnxs_2[$i]->receive_currency == 'rbc'){
                $balance_crypto = $balance_crypto + $trnxs_2[$i]->receive_amount;
            }
        }
@endphp

<!DOCTYPE html>
<html lang="{{ $lang['lang'] }}" class="js">

<head>
    <meta charset="utf-8">
    <meta name="apps" content="RobotBulls">
    <meta name="author" content="RobotBulls">
    <meta name="description" content="Research in the field of Artificial Intelligence, we focuse on utilizing market volatility to our advantage. We have developed an automated AI system that specializes in market prediction. Our team has created a predictive model that analyzes past market experiences and current market trends to provide insights and make informed decisions. With this technology, our clients can have a more accurate understanding of market movements and make data-driven decisions.">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="site-token" content="{{ site_token() }}">
    <link rel="shortcut icon" href="{{ site_favicon() }}">
    <meta name="msapplication-TileImage" content="{{ site_favicon() }}">
    <title>@yield('title') | {{ site_whitelabel('title') }}</title>
    
    <link rel="manifest" href="{{ asset('assets/manifest.json') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="RobotBulls">
    <link rel="apple-touch-icon" href="{{ asset('images/logo-192x192.png') }}">
    
    <script type="module" src="{{ asset('assets/js/ionic.esm.js') }}"></script>

    <link rel="stylesheet" href="{{ asset(style_theme('vendor')) }}">
    <link rel="stylesheet" href="{{ asset(style_theme('user')) }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
    <script>
        function isStandalone() {
          return ('standalone' in window.navigator) && window.navigator.standalone;
        }
        if (isStandalone()) {
          const viewport = document.querySelector("meta[name=viewport]");
          viewport.setAttribute("content", "width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no");
        }
    </script>
    
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
        
</head>

<body class="user-dashboard page-user theme-modern">

    <div class="topbar-wrap">
        <div class="topbar is-sticky">
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
                            <span class="user-welcome d-none d-lg-inline-block welcome">{{__($lang['welcome'])}} {{ auth()->user()->name }}</span>
                            <div class="toggle-tigger user-thumb" href="#"><em class="ti ti-user"></em></div>
                            <div class="toggle-class dropdown-content dropdown-content-right dropdown-arrow-right user-dropdown">
                                {!! UserPanel::user_balance($lang) !!}
                                {!! UserPanel::user_menu_links($lang) !!}
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
                        <li><a href="{{ route('user.home') }}" class="header1"><em class="ikon ikon-home-link"></em> {{__( $lang['dashboard'] )}}</a></li>
                        <li><a href="{{ route('user.investment') }}" class="header2"><em class="ikon ikon-my-token"></em> {{ __( $lang['new_investment'] ) }}</a></li>
                        @if($balance_crypto > 0)
                        <li><a href="{{ route('user.token') }}" class="header3"><em class="ikon ikon-coins"></em> {{ __( $lang['coin_presale'] )}}</a></li>
                        @endif
                        <li><a href="{{ route('user.account') }}" class="header4"><em class="ikon ikon-user"></em> {{__( $lang['profile'] ) }}</a></li>
                    </ul>
                    @if(!is_kyc_hide())
                    <ul class="navbar-btns">
                        @if($user->whitelist_balance > 1000)
                        <li><a href="{{ route('user.investment') }}" class="btn btn-sm btn-outline btn-light"><span><span class="KYC_button">{{__($lang['whitelisting_balance']. round($user->whitelist_balance, 2) . ' ' . strtoupper($user->base_currency) )}}</span></span></a></li>
                        @elseif(isset(Auth::user()->kyc_info->status) && Auth::user()->kyc_info->status == 'approved')
                        <li><span class="badge badge-outline badge-success badge-lg"><em class="text-success ti ti-files mgr-1x"></em><span class="text-success KYC_approved">{{__($lang['kyc_approved'] )}}</span></span></li>
                        @else
                        <li><a href="{{ route('user.kyc') }}" class="btn btn-sm btn-outline btn-light"><em class="text-primary ti ti-files"></em><span><span class="KYC_button">{{__($lang['kyc_application'] )}}</span></span></a></li>
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
                    @yield('content')
                </div>

                @if ($has_sidebar!=true)
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
    <script src="{{ asset('assets/js/script.js').css_js_ver() }}"></script>

    @stack('footer')
    <script type="text/javascript">
        @if(session('resent'))
        show_toast("success", "{{ __( $lang['a_fresh_verification_link_has_been_sent_to_your_email'] ) }}");
        @endif
    </script>
    
    <script type="text/javascript" src="//cdn.jsdelivr.net/npm/slick-carousel@1.8.1/slick/slick.min.js"></script>

    <script>    
        $(".single-item").slick({
          arrows: false,
          dots: true
        });
    </script>

    @if(get_setting('site_footer_code', false))
    {{ html_string(get_setting('site_footer_code')) }}
    @endif
</body>

</html>
