@extends('layouts.admin')
@section('title', ucfirst($is_page).' Transactions')

@php 
    function getDaysFromPeriod($period) {
        // Convert string into a date interval
        $interval = DateInterval::createFromDateString($period);
        // Get current date
        $now = new DateTime();
        // Calculate date in past
        $past = clone $now;
        $past->sub($interval);
        // Calculate difference in days
        $difference = $now->diff($past);
        return $difference->days;
    }
    function daysDifferenceFromToday($datetimeString) {
        // Create a DateTime object from the provided string
        $givenDate = new DateTime($datetimeString);

        // Get the current date
        $now = new DateTime();

        // Calculate the difference in days
        $difference = $now->diff($givenDate);

        return $difference->days;
    }
@endphp

@section('content')
<div class="page-content">
    <div class="container">
        @include('layouts.messages')
        @include('vendor.notice')
        <div class="card content-area content-area-mh">
            <div class="card-innr">
                <div class="card-head has-aside">
                    <h4 class="card-title">{{ ucfirst($is_page) }} Transactions</h4>
                    @if (auth()->user()->role == 'admin')
                    <div class="card-opt">
                        <ul class="btn-grp btn-grp-block guttar-20px">
                            <li>
                                <a href="#" class="btn btn-sm btn-auto btn-primary" data-toggle="modal" data-target="#addTnx">
                                    <em class="fas fa-plus-circle"></em><span>Add <span class="d-none d-sm-inline-block">Funds</span></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endif
                </div>
                
                @if (auth()->user()->role == 'admin')
                <div class="page-nav-wrap">
                    <div class="page-nav-bar justify-content-between bg-lighter">
                        @if (auth()->user()->role == 'admin')
                        <div class="page-nav w-100 w-lg-auto">
                            <ul class="nav">
                                @if (auth()->user()->role == 'admin')
                                <li class="nav-item{{ (is_page('transactions.pending') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions', 'pending') }}">Pending</a></li>
                                <li class="nav-item {{ (is_page('transactions.approved') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions', 'approved') }}">Approved</a></li>
                                <li class="nav-item {{ (is_page('transactions.bonuses') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions', 'bonuses') }}">Bonuses</a></li>
                                <li class="nav-item {{ (is_page('transactions') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions') }}">All</a></li>
                                @endif
                                <li class="nav-item {{ (is_page('transactions.expiring') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions', 'expiring') }}">Expiring</a></li>
                                @if (auth()->user()->role == 'admin')
                                <li class="nav-item {{ (is_page('transactions.percentage') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions', 'percentage') }}">Percentage</a></li>
                                <li class="nav-item {{ (is_page('transactions.biggest') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.transactions', 'biggest') }}">Biggest</a></li>
                                @endif
                            </ul>
                        </div>
                        @endif
                        <div class="search flex-grow-1 pl-lg-4 w-100 w-sm-auto">
                            <form action="{{ route('admin.transactions') }}" method="GET" autocomplete="off">
                                <div class="input-wrap">
                                    <span class="input-icon input-icon-left"><em class="ti ti-search"></em></span>
                                    <input type="search" class="input-solid input-transparent" placeholder="Tranx ID to quick search" value="{{ request()->get('s', '') }}" name="s">
                                </div>
                            </form>
                        </div>
                        <div class="tools w-100 w-sm-auto">
                            <ul class="btn-grp guttar-8px">
                                <li><a href="#" class="btn btn-light btn-sm btn-icon btn-outline bg-white advsearch-opt"> <em class="ti ti-panel"></em> </a></li>
                                @if(is_super_admin())
                                @if (auth()->user()->role == 'admin')
                                <li>
                                    <div class="relative">
                                        <a href="#" class="btn btn-light bg-white btn-sm btn-icon toggle-tigger btn-outline"><em class="ti ti-server"></em> </a>
                                        <div class="toggle-class dropdown-content dropdown-content-sm dropdown-content-center shadow-soft">
                                            <ul class="dropdown-list">
                                                <li><h6 class="dropdown-title">Export</h6></li>
                                                <li><a href="{{ route('admin.export', array_merge([ 'table' => 'transactions', 'format' => 'entire'], request()->all())) }}">Entire</a></li>
                                                <li><a href="{{ route('admin.export', array_merge([ 'table' => 'transactions', 'format' => 'minimal'], request()->all())) }}">Minimal</a></li>
                                                <li><a href="{{ route('admin.export', array_merge([ 'table' => 'transactions', 'format' => 'compact'], request()->all())) }}">Plans</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                @endif
                                @endif
                                <li>
                                    <div class="relative">
                                        <a href="#" class="btn btn-light bg-white btn-sm btn-icon toggle-tigger btn-outline"><em class="ti ti-settings"></em> </a>
                                        <div class="toggle-class dropdown-content dropdown-content-sm dropdown-content-center shadow-soft">
                                            <form class="update-meta" action="#" data-type="tnx_page_meta">
                                                <ul class="dropdown-list">
                                                    <li><h6 class="dropdown-title">Show</h6></li>
                                                    <li{!! (gmvl('tnx_per_page', 10)==10) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=10">10</a></li>
                                                    <li{!! (gmvl('tnx_per_page', 10)==20) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=20">20</a></li>
                                                    <li{!! (gmvl('tnx_per_page', 10)==50) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=50">50</a></li>
                                                        <li{!! (gmvl('tnx_per_page', 10)==100) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=100">100</a></li>
                                                    <li{!! (gmvl('user_per_page', 10)==200) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=200">200</a></li>
                                                </ul>
                                                <ul class="dropdown-list">
                                                    <li><h6 class="dropdown-title">Order By</h6></li>
                                                    <li{!! (gmvl('user_order_by', 'id')=='created_at') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="orderby=created_at">Date</a></li>
                                                    <li{!! (gmvl('user_order_by', 'id')=='id') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="orderby=id">User ID</a></li>
                                                    <li{!! (gmvl('user_order_by', 'id')=='name') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="orderby=name">Name</a></li>
                                                    <li{!! (gmvl('user_order_by', 'id')=='token') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="orderby=token">Token</a></li>
                                                    <li{!! (gmvl('user_order_by', 'id')=='equity') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="orderby=equity">Equity</a></li>
                                                </ul>
                                                <ul class="dropdown-list">
                                                    <li><h6 class="dropdown-title">Order</h6></li>
                                                    <li{!! (gmvl('tnx_ordered', 'DESC')=='DESC') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="ordered=DESC">DESC</a></li>
                                                    <li{!! (gmvl('tnx_ordered', 'DESC')=='ASC') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="ordered=ASC">ASC</a></li>
                                                </ul>
                                            </form>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="search-adv-wrap hide">
                        <form class="adv-search" id="adv-search" action="{{ route('admin.transactions') }}" method="GET" autocomplete="off">
                            <div class="row align-items-end guttar-20px guttar-vr-15px">
                                <div class="col-lg-6">
                                    <div class="input-grp-wrap">
                                        <span class="input-item-label input-item-label-s2 text-exlight">Advanced Search</span>
                                        <div class="input-grp align-items-center bg-white">
                                            <div class="input-wrap flex-grow-1">
                                                <input value="{{ request()->get('search') }}" class="input-solid input-solid-sm input-transparent" type="text" placeholder="Search by ID" name="search">
                                            </div>
                                            <ul class="search-type">
                                                <li class="input-wrap input-radio-wrap">
                                                    <input name="by" class="input-radio-select" id="advs-by-tnx" value="" type="radio"{{ (empty(request()->by) || request()->by!='usr') ? ' checked' : '' }}>
                                                    <label for="advs-by-tnx">TRANX</label>
                                                </li>
                                                <li class="input-wrap input-radio-wrap">
                                                    <input name="by" class="input-radio-select" id="advs-by-user" value="usr" type="radio"{{ (isset(request()->by) && request()->by=='usr') ? ' checked' : '' }}>
                                                    <label for="advs-by-user">User</label>
                                                </li>
                                                <li class="input-wrap input-radio-wrap">
                                                    <input name="by" class="input-radio-select" id="advs-by-wallet_address" value="wallet_address" type="radio"{{ (isset(request()->by) && request()->by=='wallet_address') ? ' checked' : '' }}>
                                                    <label for="advs-by-wallet_address">Reference</label>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-4 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Tranx Type</label>
                                        <select  name="type" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">Any Type</option>
                                            <option {{ request()->get('type') == 'purchase' ? 'selected' : '' }} value="purchase">Purchase</option>
                                            <option {{ request()->get('type') == 'bonus' ? 'selected' : '' }} value="bonus">Bonus</option>
                                            <option {{ request()->get('type') == 'referral' ? 'selected' : '' }} value="referral">Referral</option>
                                            <option {{ request()->get('type') == 'transfer' ? 'selected' : '' }} value="transfer">Transfer</option>
                                            <option {{ request()->get('type') == 'refund' ? 'selected' : '' }} value="refund">Refund</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-4 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Status</label>
                                        <select name="state" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">Show All</option>
                                            <option {{ request()->get('state') == 'pending' ? 'selected' : '' }} value="pending">Pending</option>
                                            <option {{ request()->get('state') == 'onhold' ? 'selected' : '' }} value="onhold">Onhold</option>
                                            <option {{ request()->get('state') == 'approved' ? 'selected' : '' }} value="approved">Approved</option>
                                            <option {{ request()->get('state') == 'canceled' ? 'selected' : '' }} value="canceled">Canceled</option>
                                            <option {{ request()->get('state') == 'deleted' ? 'selected' : '' }} value="deleted">Deleted</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-sm-4 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Stage</label>
                                        <select name="stg" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">All Stage</option>
                                            @forelse($stages as $stage)
                                            <option {{ request()->get('stg') == $stage->id ? 'selected' : '' }} value="{{ $stage->id }}">{{ $stage->name }}</option>
                                            @empty
                                            <option value="">No active stage</option>
                                            @endif
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Pay Method</label>
                                        <select name="pmg" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">All</option>
                                            @foreach($gateway as $pmg)
                                            <option {{ request()->get('pmg') == $pmg ? 'selected' : '' }} value="{{ $pmg }}">{{ ucfirst($pmg) }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Pay Currency</label>
                                        <select name="pmc" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">All</option>
                                            @foreach($pm_currency as $gt => $full)
                                            @if(token('purchase_'.$gt) == 1)
                                            <option {{ request()->get('pmc') == $gt ? 'selected' : '' }} value="{{ strtolower($gt) }}">{{ strtoupper($gt) }}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Date Within</label>
                                        <select name="date" class="select select-sm select-block select-bordered date-opt" data-dd-class="search-off">
                                            <option value="">All Time</option>
                                            <option {{ request()->get('date') == 'today' ? 'selected' : '' }} value="today">Today</option>
                                            <option {{ request()->get('date') == 'this-month' ? 'selected' : '' }} value="this-month">This Month</option>
                                            <option {{ request()->get('date') == 'last-month' ? 'selected' : '' }} value="last-month">Last Month</option>
                                            <option {{ request()->get('date') == '90day' ? 'selected' : '' }} value="90day">Last 90 Days</option>
                                            <option {{ request()->get('date') == 'custom' ? 'selected' : '' }} value="custom">Custom Range</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Plan Duration</label>
                                        <select name="duration" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">All</option>
                                            <option {{ request()->get('duration') == '3 Month' ? 'selected' : '' }} value="3 Month">3 Month</option>
                                            <option {{ request()->get('duration') == '6 Month' ? 'selected' : '' }} value="6 Month">6 Month</option>
                                            <option {{ request()->get('duration') == '12 Month' ? 'selected' : '' }} value="12 Month">12 Month</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Bull</label>
                                        <select name="plan" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                            <option value="">All</option>
                                            <option {{ request()->get('plan') == 'general_bull' ? 'selected' : '' }} value="general_bull">General Bull</option>
                                            <option {{ request()->get('plan') == 'stocks_bull' ? 'selected' : '' }} value="stocks_bull">Stocks Bull</option>
                                            <option {{ request()->get('plan') == 'crypto_bull' ? 'selected' : '' }} value="crypto_bull">Crypto Bull</option>
                                            <option {{ request()->get('plan') == 'ecological_bull' ? 'selected' : '' }} value="ecological_bull">Ecological Bull</option>
                                            <option {{ request()->get('plan') == 'nft_bull' ? 'selected' : '' }} value="nft_bull">NFT Bull</option>
                                            <option {{ request()->get('plan') == 'metaverse_bull' ? 'selected' : '' }} value="metaverse_bull">Metaverse Bull</option>
                                            <option {{ request()->get('plan') == 'ipo_bull' ? 'selected' : '' }} value="ipo_bull">IPO Bull</option>
                                            <option {{ request()->get('plan') == 'commodities_bull' ? 'selected' : '' }} value="commodities_bull">Commodities Bull</option>
                                            <option {{ request()->get('plan') == 'ipo_bull' ? 'selected' : '' }} value="ipo_bull">Forex Bull</option>
                                            <option {{ request()->get('plan') == 'real_estate_bull' ? 'selected' : '' }} value="real_estate_bull">Real Estate Bull</option>
                                            <option {{ request()->get('plan') == 'btc_bull' ? 'selected' : '' }} value="btc_bull">BTC Bull</option>
                                            <option {{ request()->get('plan') == 'eth_bull' ? 'selected' : '' }} value="eth_bull">ETH Bull</option>
                                            <option {{ request()->get('plan') == 'ai_bull' ? 'selected' : '' }} value="ai_bull">AI Bull</option>
                                            <option {{ request()->get('plan') == 'rbc_plan' ? 'selected' : '' }} value="rbc_plan">RBC</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">Exclude User IDs</label>
                                        <div class="checkbox">
                                            <input type="checkbox" id="exclude_user_ids" name="exclude_user_ids" {{ request()->get('exclude_user_ids') ? 'checked' : '' }}>
                                            <label for="exclude_user_ids">Check to Exclude</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6 date-hide-show">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">From</label>
                                        <div class="relative">
                                            <input class="input-bordered input-solid-sm date-picker bg-white" value="{{ (request()->get('date') == 'custom') ? request()->get('from') : '' }}" type="text" id="date-from" name="from" data-format="alt">
                                            <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6 date-hide-show">
                                    <div class="input-wrap input-with-label">
                                        <label class="input-item-label input-item-label-s2 text-exlight">To</label>
                                        <div class="relative">
                                            <input class="input-bordered input-solid-sm date-picker bg-white" value="{{ (request()->get('date') == 'custom') ? request()->get('to') : '' }}" type="text" id="date-to" name="to" data-format="alt">
                                            <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4 col-lg-2 col-mb-6">
                                    <div class="input-wrap">
                                        <input type="hidden" name="filter" value="1">
                                        <button class="btn btn-sm btn-sm-s2 btn-auto btn-primary">
                                            <em class="ti ti-search width-auto"></em><span>Search</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if (request()->get('filter') || request()->s)
                    <div class="search-adv-result">
                        <div class="search-info">
                            Found <span class="search-count">{{ $trnxs->total() }}</span> Transactions{{ (request()->get('date') != 'custom') ? '.' : '' }}
                            @if (request()->get('date') == 'custom')
                            between <span>{{ _date(request()->get('from'), '', true) }}</span> to <span>{{ _date(request()->get('to'), '', true) }}</span>.
                            @endif
                        </div>
                        <ul class="search-opt">
                            @if(request()->get('search'))
                            <li><a href="{{ qs_url(qs_filter('search')) }}">Search <span>'{{ request()->get('search') }}'</span>{{ (request()->by=='usr') ? ' (User)' : '' }}</a></li>
                            @endif

                            @if(request()->get('type'))
                                <li><a href="{{ qs_url( qs_filter('type')) }}">Type: <span>{{ ucfirst(request()->get('type')) }}</span></a></li>
                            @endif

                            @if(request()->get('state'))
                                <li><a href="{{ qs_url( qs_filter('state')) }}">Status: <span>{{ ucfirst(request()->get('state')) }}</span></a></li>
                            @endif

                            @if(request()->get('stg'))
                                <li><a href="{{ qs_url( qs_filter('stg')) }}">Stage: <span>{{ ucfirst(request()->get('stg')) }}</span></a></li>
                            @endif

                            @if(request()->get('pmg'))
                                <li><a href="{{ qs_url( qs_filter('pmg')) }}">Pay Method: <span>{{ ucfirst(request()->get('pmg')) }}</span></a></li>
                            @endif

                            @if(request()->get('pmc'))
                                <li><a href="{{ qs_url( qs_filter('pmc')) }}">Currency: <span>{{ strtoupper(request()->get('pmc')) }}</span></a></li>
                            @endif

                            @if (request()->get('date') == 'today')
                                <li><a href="{{ qs_url( qs_filter('date')) }}">In today</span></a></li>
                            @endif

                            @if (request()->get('date') == 'this-month')
                                <li><a href="{{ qs_url( qs_filter('date')) }}"><span>In this month</span></a></li>
                            @endif

                            @if (request()->get('date') == 'last-month')
                                <li><a href="{{ qs_url( qs_filter('date')) }}"><span>In last month</span></a></li>
                            @endif

                            @if (request()->get('date') == '90day')
                                <li><a href="{{ qs_url( qs_filter('date')) }}"><span>In last 90 days</span></a></li>
                            @endif

                            @if (request()->get('duration') == '3 Months')
                                <li><a href="{{ qs_url( qs_filter('duration')) }}"><span>3 Months</span></a></li>
                            @endif

                            @if (request()->get('duration') == '6 Months')
                                <li><a href="{{ qs_url( qs_filter('duration')) }}"><span>6 Months</span></a></li>
                            @endif

                            @if (request()->get('duration') == '12 Months')
                                <li><a href="{{ qs_url( qs_filter('duration')) }}"><span>12 Months</span></a></li>
                            @endif

                            @if (request()->get('plan') == 'general_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>General Bull</span></a></li>
                            @endif

                            @if (request()->get('plan') == 'stocks_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>Stocks Bull</span></a></li>
                            @endif

                            @if (request()->get('plan') == 'crypto_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>Crypto Bull</span></a></li>
                            @endif

                            @if (request()->get('plan') == 'ecological_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>Ecological Bull</span></a></li>
                            @endif

                            @if (request()->get('plan') == 'nft_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>NFT Bulls</span></a></li>
                            @endif

                            @if (request()->get('plan') == 'metaverse_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>Metaverse Bull</span></a></li>
                            @endif

                            @if (request()->get('plan') == 'ipo_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>IPO Bull</span></a></li>
                            @endif

                            @if (request()->get('plan') == 'real_estate_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>Real Estate Bull</span></a></li>
                            @endif
                    
                            @if (request()->get('plan') == 'btc_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>BTC Bull</span></a></li>
                            @endif
                    
                            @if (request()->get('plan') == 'eth_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>ETH Bull</span></a></li>
                            @endif
                    
                            @if (request()->get('plan') == 'ai_bull')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>AI Bull</span></a></li>
                            @endif
                    
                            @if (request()->get('plan') == 'rbc_plan')
                                <li><a href="{{ qs_url( qs_filter('plan')) }}"><span>RBC</span></a></li>
                            @endif


                            <li><a href="{{ route('admin.transactions') }}" class="link link-underline">Clear All</a></li>
                        </ul>
                    </div>
                    @endif
                </div>
                @endif
                
                <br>
                
                @if($trnxs->total() > 0) 
                <table class="data-table admin-tnx">
                    <thead>
                        <tr class="data-item data-head">
                            <th class="data-col tnx-status dt-tnxno">Tranx ID</th>
                            <th class="data-col dt-token">User</th>
                            <th class="data-col dt-token">Plan</th>
                            <th class="data-col dt-amount">Amount</th>
                            <th class="data-col dt-usd-amount">Equity</th>
                            <th class="data-col pm-gateway dt-account">Pay From</th>
                            <th class="data-col dt-type tnx-type">Type</th>
                            <th class="data-col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trnxs as $trnx)
                        @php 
                            $text_danger = ( $trnx->tnx_type=='refund' || ($trnx->tnx_type=='transfer' && $trnx->extra=='sent') ) ? ' text-danger' : '';
                            $user = $users->find($trnx->user);
                        @endphp
                        <tr class="data-item" id="tnx-item-{{ $trnx->id }}">
                            <td class="data-col dt-tnxno">
                                <div class="d-flex align-items-center">
                                    <div id="ds-{{ $trnx->id }}" data-tplanoggle="tooltip" data-placement="top" title="{{ __status($trnx->status, 'text') }}" class="data-state data-state-{{ __status($trnx->status, 'icon') }}">
                                        <span class="d-none">{{ ucfirst($trnx->status) }}</span>
                                    </div>
                                    <div class="fake-class">
                                        <span class="lead tnx-id"><a href="{{ route('admin.transactions.view', $trnx->id) }}" target="_blank">{{ $trnx->tnx_id }} </a>
                                            @if($user->note) 
                                            <em class="fas fa-info-circle note-info" data-toggle="tooltip" data-placement="bottom" title="{{ $user->note }}"></em>
                                            <!-- There might be other similar <em> tags here -->
                                            @endif
                                            
                                        </span>
                                        @if (auth()->user()->role == 'admin')
                                        <span class="sub sub-date" style="color: #cdd2d7;">{{ date('d M, Y', strtotime($trnx->tnx_time)) }}</span>
                                        @endif
                                        <span class="sub sub-date">{{ date('d M, Y', strtotime($trnx->created_at)) }}</span>
                                        <?php
                                        
                                            $end_date = (new DateTime($trnx->created_at))->add(new DateInterval('P'.((int) filter_var($trnx->duration, FILTER_SANITIZE_NUMBER_INT)).'M'));

                                            $now = new DateTime();
                                            $now->add(new DateInterval('P1D'));  // add 1 day to the current date
                                            $interval = $now->diff($end_date);

                                            $class = '';
                                            if ($interval->invert == 1 && $interval->days >= 60) {
                                                $class = 'black';
                                            } elseif ($interval->invert == 0 && $interval->days > 2) {
                                                $class = 'green';
                                            } elseif ($interval->invert == 0 && ($interval->days <= 2)) {  // include the case previously covered by 'blue'
                                                $class = 'green';
                                            } elseif ($interval->invert == 1 || ($interval->invert == 0 && $interval->days > 2)) {
                                                $class = 'red';
                                            }

                                        ?>


                                        <span class="sub sub-date" style="color:<?php echo $class; ?>">
                                            <?php echo $end_date->format('d M, Y'); ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="data-col dt-token">
                                <span class="lead token-amount{{ $text_danger }}"><a href="{{ route('admin.users.view', [$trnx->tnxUser->id, 'details'] ) }}" target="_blank">{{ $trnx->tnxUser->name }} </a>
                                    @if($user->walletType && $user->walletAddress) 
                                        <em class="fas fa-wallet" data-toggle="tooltip" data-placement="bottom" title="{{ strtoupper($user->walletType) }}"></em>
                                        <!-- There might be other similar <em> tags here -->
                                    @endif
                                    @if($user->vip_user) 
                                        <em class="fas fa-star" data-toggle="tooltip" data-placement="bottom" title="VIP USER"></em>
                                        <!-- There might be other similar <em> tags here -->
                                    @endif
                                </span>
                                <span class="small">({{ round(floatval($trnx->tnxUser->tokenBalance),3) }} || {{ round(floatval($trnx->tnxUser->equity),3) }})</span>
                                <span class="sub sub-symbol">{{ $trnx->tnxUser->email }}</span>
                                @if(!empty(json_decode($trnx->tnxUser->referralInfo)))
                                    <span class="sub sub-symbol">R: {{ optional(json_decode($trnx->tnxUser->referralInfo))->name }}</span>
                                @endif
                            </td>
                            <td class="data-col dt-token">
                                <span class="lead token-amount{{ $text_danger }}">{{ $trnx->plan }}</span>
                                <span class="sub sub-symbol">{{ $trnx->duration }}</span>
                            </td>
                            <td class="data-col dt-amount">
                             
                                <span class="lead amount-pay{{ $text_danger }}">{{ round(floatval($trnx->amount),3) }} </span>
                                <span class="sub sub-symbol">{{ strtoupper($trnx->receive_currency) }} 
<!--                                    <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="1 {{ token('symbol') }} = {{ to_num($trnx->currency_rate, 'max').' '.strtoupper($trnx->receive_currency) }}"></em>-->
                                </span>
                            </td>
                            <td class="data-col dt-usd-amount{{ $text_danger }}">
                     
                                <span class="lead amount-receive{{ $text_danger }}">{{ round($trnx->equity, 2) }}
                                    
                                </span>
                                <span class="sub sub-symbol">{{ strtoupper($trnx->base_currency) }} 
<!--                                    <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="1 {{ token('symbol') }} = {{ to_num($trnx->base_currency_rate, 'max').' '.strtoupper($trnx->base_currency) }}"></em>-->
                                </span>
                            </td>
                            <td class="data-col dt-account">
                                <span class="sub sub-s2 pay-with">
                                    @if ($trnx->tnx_type=='bonus' && $trnx->added_by!=set_added_by('0')) 
                                        {{ 'Added by '.transaction_by($trnx->added_by) }}
                                    @elseif($trnx->tnx_type == 'refund')
                                        {{ $trnx->details }}
                                    @elseif($trnx->tnx_type == 'transfer')
                                        {{ $trnx->details }}
                                    @else
                                        {{ (is_gateway($trnx->payment_method, 'internal') ? gateway_type($trnx->payment_method, 'name') : ( (is_gateway($trnx->payment_method, 'online') || $trnx->payment_method=='bank') ? 'Pay via '.ucfirst($trnx->payment_method) : 'Pay with '.strtoupper($trnx->currency) ) ) }}
                                        @if($trnx->wallet_address && $trnx->tnx_type!='bonus')
                                        <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="{{ $trnx->wallet_address }}"></em>
                                        @endif
                                    @endif
                                </span>
                                @if($trnx->tnx_type == 'refund')
                                    @php 
                                    $extra = (is_json($trnx->extra, true) ?? $trnx->extra);
                                    @endphp
                                    <span class="sub sub-email"><a href="{{ route('admin.transactions.view', ($extra->trnx ?? $trnx->id)) }}">View Transaction</a></span>
                                @else
                                    <span class="sub sub-email">{{ $trnx->user }} 
<!--                                        <em class="fas fa-info-circle" data-toggle="tooltip" data-placement="bottom" title="{{ isset($trnx->tnxUser) ? explode_user_for_demo($trnx->tnxUser->email, auth()->user()->type) : '' }}"></em>-->
                                </span> 
                                @endif
                            </td>
                            <td class="data-col data-type">
                                <span class="dt-type-md badge badge-outline badge-md badge-{{$trnx->id}} badge-{{__status($trnx->tnx_type,'status')}}">{{ ucfirst($trnx->tnx_type) }}</span>
                                <span class="dt-type-sm badge badge-sq badge-outline badge-md badge-{{$trnx->id}} badge-{{__status($trnx->tnx_type,'status')}}">{{ ucfirst(substr($trnx->tnx_type, 0, 1)) }}</span>
                            </td>
                            <td class="data-col text-right">
                                @if($trnx->status == 'deleted')
                                <a href="{{ route('admin.transactions.view', $trnx->id) }}" target="_blank" class="btn btn-light-alt btn-xs btn-icon"><em class="ti ti-eye"></em></a>
                                @else 
                                <div class="relative d-inline-block">
                                    <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                                    <div class="toggle-class dropdown-content dropdown-content-top-left">
                                        <ul id="more-menu-{{ $trnx->id }}" class="dropdown-list">
                                            <li><a href="{{ route('admin.transactions.view', $trnx->id) }}">
                                                <em class="fa fa-eye"></em> Trnx Details</a></li>
                                            <li><a href="javascript:void(0)" data-uid="{{ $trnx->tnxUser->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>All Transactions</a></li>
                                            
                                            <li><a class="user-email-action" href="#SMSUser" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openMessageModalAndSetName( {{$user->id}}, '{{$user->name}}' )"><em class="far fa-bell"></em>Send Message</a></li>
                                            
                                            <li><a class="user-email-action view-messages" href="#pastMessages" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openMessageModalAndSetName2( {{$user->id}}, '{{$user->name}}' )"><em class="fa fa-table"></em>Past Messages</a></li>
                                            
                                            @if (auth()->user()->role == 'admin')
                                            <form action="{{ route('admin.ajax.transactions.withdraw') }}" method="POST" class="validate-modern d-none" autocomplete="off">
                                                @csrf
                                                <input class="input-bordered d-none" required="" type="text" name="trnx_id" value="{{ $trnx->id }}">
                                                <button type="submit" class="reinvest-button"><em class="fa fa-chart-line" style="margin-right: 10px;font-size: 14px;width: 22px;"></em> Quick Withdraw</button>
                                            </form>
                                            @endif
                                            
                                           
                                            
                                            @if (auth()->user()->role == 'admin')
                                            <form action="{{ route('admin.ajax.transactions.reinvest') }}" method="POST" class="validate-modern d-none" autocomplete="off">
                                                @csrf
                                                <input class="input-bordered d-none" required="" type="text" name="trnx_id" value="{{ $trnx->id }}">
                                                <button type="submit" class="reinvest-button"><em class="fa fa-chart-line" style="margin-right: 10px;font-size: 14px;width: 22px;"></em> Quick Reinvest</button>
                                            </form>
                                            @endif
                                            
                                            <li><a href="{{ route('admin.users.view', [$trnx->tnxUser->id, 'details'] ) }}"><em class="fa fa-user"></em> User Details</a></li>
                                            <li><a href="javascript:void(0)" data-uid="{{ $trnx->tnxUser->id }}" data-type="activities" class="user-form-action user-action"><em class="fas fa-sign-out-alt"></em>User Activities</a></li>
                                            <li><a href="javascript:void(0)" data-uid="{{ $trnx->tnxUser->id }}" data-type="referrals" class="user-form-action user-action"><em class="fas fa-users"></em>Referrals</a></li>
                                            @if( $trnx->tnx_type == 'transfer' && $trnx->status == 'pending')
                                            <li><a href="javascript:void(0)" class="tnx-transfer-action" data-status="approved" data-tnx_id="{{ $trnx->id }}">
                                                <em class="far fa-check-square"></em> Approve</a></li>
                                            <li><a href="javascript:void(0)" class="tnx-transfer-action" data-status="rejected" data-tnx_id="{{ $trnx->id }}">
                                                <em class="fas fa-ban"></em> Reject</a></li>
                                            @endif
                                            @if($trnx->status == 'approved' && $trnx->tnx_type == 'purchase' && $trnx->refund == null)
                                            @if (auth()->user()->role == 'admin')
                                            <li><a href="javascript:void(0)" class="tnx-action" data-type="refund" data-id="{{ $trnx->id }}">
                                                <em class="fas fa-reply"></em> Refund</a></li>
                                            @endif
                                            @endif
                                            
                                            <li><a class="user-email-action" href="#note" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openModalAndSetTransactionId( {{$user->id}}, '{{$user->note}}', '{{$user->name}}' )"><em class="fa fa-book-open"></em>Note</a></li>
                                            
                                            @if($trnx->status == 'pending' || $trnx->status == 'onhold')
                                                @if($trnx->payment_method == 'bank' || $trnx->payment_method == 'manual')
                                                <li><a href="javascript:void(0)" id="adjust_token" data-id="{{ $trnx->id }}">
                                                    <em class="far fa-check-square"></em>Approve</a></li>
                                                @endif
                                                @if ($trnx->payment_method == 'coinbase' && $trnx->status != 'canceled')
                                                <li><a href="{{ route('admin.transactions.check', ['tid' => $trnx->id]) }}">
                                                    <em class="fas fa-reply"></em>Check Status</a></li> 
                                                @endif
                                                @if($trnx->tnx_type != 'transfer')
                                                <li id="canceled"><a href="javascript:void(0)" class="tnx-action" data-type="canceled" data-id="{{ $trnx->id }}">
                                                    <em class="fas fa-ban"></em>Cancel</a></li>
                                                @endif
                                            @endif
                                            @if($trnx->status == 'canceled')
                                                @if( !empty($trnx->checked_by) && ($trnx->payment_method == 'bank' || $trnx->payment_method == 'manual'))
                                                <li><a href="javascript:void(0)" id="adjust_token" data-id="{{ $trnx->id }}">
                                                    <em class="far fa-check-square"></em>Approve</a></li>
                                                @endif
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                                @endif
                            </td>
                        </tr>{{-- .data-item --}}
                        @endforeach
                    </tbody>
                </table>
                @else 
                    <div class="bg-light text-center rounded pdt-5x pdb-5x">
                        <p><em class="ti ti-server fs-24"></em><br>{{ ($is_page=='all') ? 'No transaction found!' : 'No '.$is_page.' transaction here!' }}</p>
                        <p><a class="btn btn-primary btn-auto" href="{{ route('admin.transactions') }}">View All Transactions</a></p>
                    </div>
                @endif

                @if ($pagi->hasPages())
                <div class="pagination-bar">
                    <div class="d-flex flex-wrap justify-content-between guttar-vr-20px guttar-20px">
                        <div class="fake-class">
                            <ul class="btn-grp guttar-10px pagination-btn">
                                @if($pagi->previousPageUrl())
                                <li><a href="{{ $pagi->previousPageUrl() }}" class="btn ucap btn-auto btn-sm btn-light-alt">Prev</a></li>
                                @endif 
                                @if($pagi->nextPageUrl())
                                <li><a href="{{ $pagi->nextPageUrl() }}" class="btn ucap btn-auto btn-sm btn-light-alt">Next</a></li>
                                @endif
                            </ul>
                        </div>
                        <div class="fake-class">
                            <div class="pagination-info guttar-10px justify-content-sm-end justify-content-mb-end">
                                <div class="pagination-info-text ucap">Page </div>
                                <div class="input-wrap w-80px">
                                    <select class="select select-xs select-bordered goto-page" data-dd-class="search-{{ ($pagi->lastPage() > 7) ? 'on' : 'off' }}">
                                        @for ($i = 1; $i <= $pagi->lastPage(); $i++)
                                        <option value="{{ $pagi->url($i) }}"{{ ($pagi->currentPage() ==$i) ? ' selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            <div class="pagination-info-text ucap">of {{ $pagi->lastPage() }}</div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>{{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection

@section('modals')
@if (auth()->user()->role == 'admin')
<div class="modal fade" id="addTnx">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Manually Add Funds</h3>
                <form action="{{ route('admin.ajax.transactions.add') }}" method="POST" class="validate-modern" id="add_token" autocomplete="off">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Tranx Type</label>
                                <div class="input-wrap">
                                    <select name="type" class="select select-block select-bordered" required>
                                        <option value="purchase">Purchase</option>
                                        <option value="bonus">Bonus</option>
                                        <option value="referral">Referral</option>
                                        <option value="demo">Demo</option>
                                        <option value="withdraw">Withdraw</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Tranx Status</label>
                                <div class="input-wrap">
                                    <select name="status" class="select select-block select-bordered" required>
                                        <option value="approved">approved</option>
                                        <option value="rejected">rejected</option>
                                        <option value="pending">pending</option>
                                        <option value="deleted">deleted</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label w-sm-60">
                                <label class="input-item-label">Tranx Date</label>
                                <div class="input-wrap">
                                    <input class="input-bordered date-picker" required="" type="text" name="tnx_date" value="{{ date('m/d/Y') }}">
                                    <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">User</label>
                                <div class="input-wrap">
                                    <select name="user" required="" class="select-block select-bordered" data-dd-class="search-on">
                                        @forelse($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @empty
                                        <option value="">No user found</option>
                                        @endif
                                    </select>
                                    <span class="input-note">Select account to add fund.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Token for Stage</label>
                                <div class="input-wrap">
                                    <select name="stage" class="select select-block select-bordered" required>
                                        @forelse($stages as $stage)
                                        <option value="{{ $stage->id }}">{{ $stage->name }}</option>
                                        @empty
                                        <option value="">No active stage</option>
                                        @endif
                                    </select>
                                    <span class="input-note">Select Stage where from adjust tokens.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Payment Gateway</label>
                                <div class="input-wrap">
                                    <select name="payment_method" class="select select-block select-bordered">
                                        @foreach($pmethods as $pmn)
                                        <option value="{{ $pmn->payment_method }}">{{ ucfirst($pmn->payment_method) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                
                                <div class="row flex-n guttar-10px">
                                    <div class="col-6">
                                        <label class="input-item-label">Crypto Amount</label>
                                        <div class="input-wrap">
                                            <select name="crypto_currency" class="select select-block select-bordered">
                                                <option value="eur">EUR</option>
                                                <option value="usd">USD</option>
                                                <option value="chf">CHF</option>
                                                <option value="gbp">GBP</option>
                                                <option value="cad">CAD</option>
                                                <option value="aud">AUD</option>
                                                <option value="eth">ETH</option>
                                                <option value="btc">BTC</option>
                                                <option value="ltc">LTC</option>
                                                <option value="xrp">XRP</option>
                                                <option value="bch">BCH</option>
                                                <option value="bnb">BNB</option>
                                                <option value="usdt">USDT</option>
                                                <option value="trx">TRX</option>
                                                <option value="usdc">USDC</option>
                                                <option value="dash">DASH</option>
                                                <option value="doge">DOGE</option>
                                                <option value="cake">CAKE</option>
                                                <option value="rbc">RBC</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <label class="input-item-label">Payment Amount</label>
                                        <div class="input-wrap">
                                            <select name="payment_currency" class="select select-block select-bordered">
                                                <option value="eur">EUR</option>
                                                <option value="usd">USD</option>
                                                <option value="chf">CHF</option>
                                                <option value="gbp">GBP</option>
                                                <option value="cad">CAD</option>
                                                <option value="aud">AUD</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <span class="input-note">Amount calculate based on stage if leave blank.</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Duration</label>
                                <div class="input-wrap">
                                    <select id="duration" name="duration" class="select select-block select-bordered">
                                        <option value="3 Month">3 Month</option>
                                        <option value="6 Month">6 Month</option>
                                        <option value="12 Month">12 Month</option>
                                        <option value="">None</option>
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="ETH Bull">ETH Bull</option>
                                        <option value="AI Bull">AI Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="RBC">RBC</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Plan</label>
                                <div class="input-wrap">
                                    <select name="plan" class="select select-block select-bordered">
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="Commodities Bull">Commodities Bull</option>
                                        <option value="Forex Bull">Forex Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="ETH Bull">ETH Bull</option>
                                        <option value="AI Bull">AI Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="Referral RBC">Referral RBC</option>
                                        <option value="RBC">RBC</option>
                                        <option value="">None</option>
                                        <option value="Withdraw">Withdraw</option>
                                        <option value="BTC">BTC</option>
                                        <option value="ETH">ETH</option>
                                        <option value="DOGE">DOGE</option>
                                        <option value="LTE">LTE</option>
                                        <option value="XRP">XRP</option>
                                        <option value="BNB">BNB</option>
                                        <option value="USDT">USDT</option>
                                        <option value="CAKE">CAKE</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Payment Address</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="wallet_address" placeholder="Optional">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Number of Cryptos</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="number" name="crypto_amount" max="{{ active_stage()->max_purchase }}" required>
                                    <p id="eur_rbc">≈</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Amount of Investment in fiat</label>
                                <div class="input-wrap">
                                    <input id="fiat" class="input-bordered" type="number" name="fiat_amount" max="{{ active_stage()->max_purchase }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label d-none">&nbsp;</label>
                                <div class="input-wrap input-wrap-checkbox mt-sm-2">
                                    <input id="auto-bonus" class="input-checkbox input-checkbox-md" type="checkbox" name="bonus_calc">
                                    <label for="auto-bonus"><span>Bonus Adjusted from Stage</span></label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Referree</label>
                                <div class="input-wrap">
                                    <select name="referee" class="select-block select-bordered" data-dd-class="search-on">
                                        @forelse($users as $user)
                                        <option value="">No referee</option>
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @empty
                                        <option value="">No user found</option>
                                        @endif
                                    </select>
                                    <span class="input-note">Select referee account.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Percentage</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="number" id="percentage_amount" name="percentage_amount" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Funds</button>
                    <div class="gaps-3x"></div>
                    <div class="note note-plane note-light">
                        <em class="fas fa-info-circle"></em>
                        <p>If checked <strong>'Bonus Adjusted'</strong>, it will applied bonus based on selected stage (only for Purchase type).</p>
                    </div>
                    
                    <script>
                        // amount in RBC
                        document.getElementById('fiat').addEventListener('input', (event) => {
                            // Log the current input value to the console
                            var price = <?=$current_price?>;
                            document.getElementById("eur_rbc").innerHTML = "≈" + 1/ parseFloat(price)  * parseFloat(event.target.value) +" RBC";
//                            console.log('Input value:', event.target.value);
                        });
                        
                        function monthStringToDays(monthString) {
                            // Parse the string to get the number of months
                            const numMonths = parseInt(monthString, 10);
                            // Get the current date
                            const currentDate = new Date();
                            // Create a new date object that's `numMonths` months in the future
                            const futureDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + numMonths, currentDate.getDate());
                            // Calculate the difference between the two dates in milliseconds
                            const diffMs = futureDate - currentDate;
                            // Convert milliseconds to days
                            const days = diffMs / (1000 * 60 * 60 * 24);
                            return Math.round(days);
                        }
                        
                    </script>
                    
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
@endif
{{-- Modal End --}}

<div class="modal fade" id="SMSUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Send Message to <span id="user_name">User</span> </h3>
                <div class="msg-box"></div>
                <form class="validate-modern" id="smsToUser" action="{{ route('sms.send') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    
                    <div class="input-item input-with-label pb-0">
                        <label class="clear input-item-label">Your Message</label>
                        <div class="input-wrap">
                            <textarea required="required" id="sms_textarea" name="message" class="input-bordered cls input-textarea input-textarea-sm" type="text" placeholder="Write something..." style="resize:vertical; height: 200px;"></textarea>
                        </div>
                    </div>
                    
                
                <div class="hide mt-3 search-adv-wrap" style="display: block;">
                    <div class="row align-items-end guttar-20px guttar-vr-15px">
                        
                        <div class="col-mb-6">
                            <div class="input-wrap input-with-label">
                                <label class="input-item-label input-item-label-s2 text-exlight">Formality</label>
                                <select name="formality" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                    <option value="formal">Formal</option>
                                    <option value="informal">Informal</option>
                                    <option value="friend">Friend</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-mb-6">
                            <div class="input-wrap input-with-label">
                                <label class="input-item-label input-item-label-s2 text-exlight">Language</label>
                                <select name="lang" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                    <option value="en">English</option>
                                    <option value="fr">French</option>
                                    <option value="de">German</option>
                                </select>
                            </div>
                        </div>

                        <a class="btn btn-primary btn-auto btn-info" style="color: white;" onclick="generateMessage()">Generate</a>
                    </div>
                </div>
                
                <div class="hide mt-3 search-adv-wrap" style="display: block;">
                    <div class="row align-items-end guttar-20px guttar-vr-15px">

                        <div class="col-mb-6">
                            <div class="input-wrap input-with-label">
                                <label class="input-item-label input-item-label-s2 text-exlight">Channel</label>
                                <select name="channel" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                    <option value="sms">SMS</option>
                                    <option value="whatsapp">Whatsapp</option>
                                </select>
                            </div>
                        </div>

                        <a class="btn btn-primary btn-auto btn-info" style="color: white;" onclick="updateChannel()">Update</a>
                    </div>
                </div>
                    
                    <button type="submit" class="btn btn-auto btn-primary mt-3" id="send-message">Send SMS</button>

                </form>
                
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>

<div class="modal fade" id="pastMessages" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Past messages of <span id="user_name2">User</span> </h3>
                <div class="msg-box"></div>
                <ul class="data-details-alt" id="messagesList">
                    
                </ul>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>




@if (auth()->user()->role == 'admin')
<div class="modal fade" id="reinvestmodal" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Reinvestment of <span id="user_name3"></span></h3>
                <h4 class="text-nowrap">Total: <span id="user_generated_equity"></span></h4>
                <h4 class="text-nowrap">Previous: <span id="user_old_plan"></span></h4>
                <div class="msg-box"></div>
                <form class="validate-modern" id="reinvestform" action="{{ route('admin.ajax.transactions.reinvest2') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="trnx_id" id="trnx_id_reinvestment">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Duration</label>
                                <div class="input-wrap">
                                    <select name="duration" id="duration_reinvestment" class="select select-block select-bordered">
                                        <option value="3 Month">3 Month</option>
                                        <option value="6 Month">6 Month</option>
                                        <option value="12 Month">12 Month</option>
                                        <option value="">None</option>
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="RBC">RBC</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Plan</label>
                                <div class="input-wrap">
                                    <select id="plan_reinvestment" name="plan" class="select select-block select-bordered">
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="Commodities Bull">Commodities Bull</option>
                                        <option value="Forex Bull">Forex Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="ETH Bull">ETH Bull</option>
                                        <option value="AI Bull">AI Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="Referral RBC">Referral RBC</option>
                                        <option value="RBC">RBC</option>
                                        <option value="">None</option>
                                        <option value="Withdraw">Withdraw</option>
                                        <option value="BTC">BTC</option>
                                        <option value="ETH">ETH</option>
                                        <option value="DOGE">DOGE</option>
                                        <option value="LTE">LTE</option>
                                        <option value="XRP">XRP</option>
                                        <option value="BNB">BNB</option>
                                        <option value="USDT">USDT</option>
                                        <option value="CAKE">CAKE</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Percentage: <span id="old_percentage"></span></label>
                                <div class="input-wrap">
                                    <input class="input-bordered" id="percentage_amount_reinvestment" name="percentage_amount">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Amount to be reinvested</label>
                                <div class="input-wrap">
                                    <input id="amount_reinvestment" class="input-bordered" name="amount_reinvestment" max="{{ active_stage()->max_purchase }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Amount to be withdrawn: </label>
                                <div class="input-wrap">
                                    <input id="amount_withdraw" class="input-bordered" name="amount_withdraw" max="{{ active_stage()->max_purchase }}" value="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Method</label>
                                <div class="input-wrap">
                                    <select name="duration_new_plan1" class="select select-block select-bordered">
                                        <option value="account_wallet">Account wallet</option>
                                        <option value="bank_account">Bank account</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Duration</label>
                                <div class="input-wrap">
                                    <select name="duration_new_plan1" class="select select-block select-bordered">
                                        <option value="3 Month">3 Month</option>
                                        <option value="6 Month">6 Month</option>
                                        <option value="12 Month">12 Month</option>
                                        <option value="">None</option>
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="RBC">RBC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Plan 1</label>
                                <div class="input-wrap">
                                    <select name="plan_new_plan1" class="select select-block select-bordered">
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="Commodities Bull">Commodities Bull</option>
                                        <option value="Forex Bull">Forex Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="ETH Bull">ETH Bull</option>
                                        <option value="AI Bull">AI Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="Referral RBC">Referral RBC</option>
                                        <option value="RBC">RBC</option>
                                        <option value="">None</option>
                                        <option value="Withdraw">Withdraw</option>
                                        <option value="BTC">BTC</option>
                                        <option value="ETH">ETH</option>
                                        <option value="DOGE">DOGE</option>
                                        <option value="LTE">LTE</option>
                                        <option value="XRP">XRP</option>
                                        <option value="BNB">BNB</option>
                                        <option value="USDT">USDT</option>
                                        <option value="CAKE">CAKE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Percentage</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="number" id="percentage_reinvestment_new_plan1" name="percentage_reinvestment_new_plan1">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Amount</label>
                                <div class="input-wrap">
                                    <input id="amount_reinvestment_new_plan1" class="input-bordered" type="number" name="amount_reinvestment_new_plan1" max="{{ active_stage()->max_purchase }}" value="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Duration</label>
                                <div class="input-wrap">
                                    <select name="duration_new_plan2" class="select select-block select-bordered">
                                        <option value="3 Month">3 Month</option>
                                        <option value="6 Month">6 Month</option>
                                        <option value="12 Month">12 Month</option>
                                        <option value="">None</option>
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="RBC">RBC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Plan 2</label>
                                <div class="input-wrap">
                                    <select name="plan_new_plan2" class="select select-block select-bordered">
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="Commodities Bull">Commodities Bull</option>
                                        <option value="Forex Bull">Forex Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="ETH Bull">ETH Bull</option>
                                        <option value="AI Bull">AI Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="Referral RBC">Referral RBC</option>
                                        <option value="RBC">RBC</option>
                                        <option value="">None</option>
                                        <option value="Withdraw">Withdraw</option>
                                        <option value="BTC">BTC</option>
                                        <option value="ETH">ETH</option>
                                        <option value="DOGE">DOGE</option>
                                        <option value="LTE">LTE</option>
                                        <option value="XRP">XRP</option>
                                        <option value="BNB">BNB</option>
                                        <option value="USDT">USDT</option>
                                        <option value="CAKE">CAKE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Percentage</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="number" id="percentage_reinvestment_new_plan2" name="percentage_reinvestment_new_plan2">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Amount</label>
                                <div class="input-wrap">
                                    <input id="amount_reinvestment_new_plan2" class="input-bordered" type="number" name="amount_reinvestment_new_plan2" max="{{ active_stage()->max_purchase }}" value="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Duration</label>
                                <div class="input-wrap">
                                    <select name="duration_new_plan3" class="select select-block select-bordered">
                                        <option value="3 Month">3 Month</option>
                                        <option value="6 Month">6 Month</option>
                                        <option value="12 Month">12 Month</option>
                                        <option value="">None</option>
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="RBC">RBC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Plan 3</label>
                                <div class="input-wrap">
                                    <select name="plan_new_plan3" class="select select-block select-bordered">
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="Commodities Bull">Commodities Bull</option>
                                        <option value="Forex Bull">Forex Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="ETH Bull">ETH Bull</option>
                                        <option value="AI Bull">AI Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="Referral RBC">Referral RBC</option>
                                        <option value="RBC">RBC</option>
                                        <option value="">None</option>
                                        <option value="Withdraw">Withdraw</option>
                                        <option value="BTC">BTC</option>
                                        <option value="ETH">ETH</option>
                                        <option value="DOGE">DOGE</option>
                                        <option value="LTE">LTE</option>
                                        <option value="XRP">XRP</option>
                                        <option value="BNB">BNB</option>
                                        <option value="USDT">USDT</option>
                                        <option value="CAKE">CAKE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Percentage</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="number" id="percentage_reinvestment_new_plan3" name="percentage_reinvestment_new_plan3">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Amount</label>
                                <div class="input-wrap">
                                    <input id="amount_reinvestment_new_plan3" class="input-bordered" type="number" name="amount_reinvestment_new_plan3" max="{{ active_stage()->max_purchase }}" value="0" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Duration</label>
                                <div class="input-wrap">
                                    <select name="duration_new_plan4" class="select select-block select-bordered">
                                        <option value="3 Month">3 Month</option>
                                        <option value="6 Month">6 Month</option>
                                        <option value="12 Month">12 Month</option>
                                        <option value="">None</option>
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="RBC">RBC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Plan 4</label>
                                <div class="input-wrap">
                                    <select name="plan_new_plan4" class="select select-block select-bordered">
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="Commodities Bull">Commodities Bull</option>
                                        <option value="Forex Bull">Forex Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="ETH Bull">ETH Bull</option>
                                        <option value="AI Bull">AI Bull</option>
                                        <option value="Bonus">Bonus</option>
                                        <option value="Referral RBC">Referral RBC</option>
                                        <option value="RBC">RBC</option>
                                        <option value="">None</option>
                                        <option value="Withdraw">Withdraw</option>
                                        <option value="BTC">BTC</option>
                                        <option value="ETH">ETH</option>
                                        <option value="DOGE">DOGE</option>
                                        <option value="LTE">LTE</option>
                                        <option value="XRP">XRP</option>
                                        <option value="BNB">BNB</option>
                                        <option value="USDT">USDT</option>
                                        <option value="CAKE">CAKE</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Percentage</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="number" id="percentage_reinvestment_new_plan4" name="percentage_reinvestment_new_plan4">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Amount</label>
                                <div class="input-wrap">
                                    <input id="amount_reinvestment_new_plan4" class="input-bordered" type="number" name="amount_reinvestment_new_plan4" max="{{ active_stage()->max_purchase }}" value="0" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Approve</button>
                    <script> 
                        function monthStringToDays2(monthString) {
                            // Parse the string to get the number of months
                            const numMonths = parseInt(monthString, 10);
                            // Get the current date
                            const currentDate = new Date();
                            // Create a new date object that's `numMonths` months in the future
                            const futureDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + numMonths, currentDate.getDate());
                            // Calculate the difference between the two dates in milliseconds
                            const diffMs = futureDate - currentDate;
                            // Convert milliseconds to days
                            const days = diffMs / (1000 * 60 * 60 * 24);
                            return Math.round(days);
                        }
                        
                        
                        //withdrawal js
                        document.getElementById('amount_withdraw').addEventListener('input', (event) => {
                                document.getElementById('amount_reinvestment').value = (parseFloat(document.getElementById("user_generated_equity").innerText) - document.getElementById('amount_withdraw').value).toFixed(2);
                        });
                        //new plan 1 js
                        document.getElementById('amount_reinvestment_new_plan1').addEventListener('input', (event) => {
                                    document.getElementById('amount_reinvestment').value = (parseFloat(document.getElementById("user_generated_equity").innerText) -parseFloat(document.getElementById("amount_withdraw").value) - document.getElementById('amount_reinvestment_new_plan1').value).toFixed(2);
                        });
                        //new plan 2 js
                        document.getElementById('amount_reinvestment_new_plan2').addEventListener('input', (event) => {
                                    document.getElementById('amount_reinvestment').value = (parseFloat(document.getElementById("user_generated_equity").innerText) - parseFloat(document.getElementById("amount_withdraw").value) -parseFloat(document.getElementById("amount_reinvestment_new_plan1").value) -  document.getElementById('amount_reinvestment_new_plan2').value).toFixed(2);
                        });
                        //new plan 3 js
                        document.getElementById('amount_reinvestment_new_plan3').addEventListener('input', (event) => {
                                    document.getElementById('amount_reinvestment').value = (parseFloat(document.getElementById("user_generated_equity").innerText) - parseFloat(document.getElementById("amount_withdraw").value) -parseFloat(document.getElementById("amount_reinvestment_new_plan1").value) -parseFloat(document.getElementById("amount_reinvestment_new_plan2").value) -document.getElementById('amount_reinvestment_new_plan3').value).toFixed(2);
                        });
                        //new plan 4 js
                        document.getElementById('amount_reinvestment_new_plan4').addEventListener('input', (event) => {
                                    document.getElementById('amount_reinvestment').value = (parseFloat(document.getElementById("user_generated_equity").innerText) - parseFloat(document.getElementById("amount_withdraw").value) -parseFloat(document.getElementById("amount_reinvestment_new_plan1").value) -parseFloat(document.getElementById("amount_reinvestment_new_plan2").value) -parseFloat(document.getElementById("amount_reinvestment_new_plan3").value) -document.getElementById('amount_reinvestment_new_plan4').value).toFixed(2);
                        });
                        
                    </script>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
@endif
<div class="modal fade" id="note" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title" id="note-title">Transaction Note</h3>
                <div class="msg-box"></div>
                <form class="validate-modern" id="editTransactionNoteForm" action="{{ route('admin.ajax.transactions.editNote') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Transaction Note</label>
                                <div class="input-wrap">
                                    <textarea name="user_note" id="user_note" class="input-bordered" rows="4"></textarea>
                                </div>
                                <span class="input-note">Edit note for this transaction.</span>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Note</button>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
@endsection

<!--functions modals (name on modals, generate message, past messages)-->
<script>   
    
function openModalAndSetTransactionId(transactionId, transactionNote, transactionName) {
    // Set the value of the hidden input field
    document.getElementById('user_id').value = transactionId;
    document.getElementById('note-title').innerText = transactionName;

    // Replace <br> with \n
    let formattedNote = transactionNote.replace(/<br>/g, "\n");

    // Set the value of the note textarea
    document.getElementById('user_note').value = formattedNote;
}

function openMessageModalAndSetName(userId, userName) {
    document.getElementById('user_name').innerText = userName;
}
    
function openMessageModalAndSetName2(userId, userName) {
    document.getElementById('user_name2').innerText = userName;
    fetchMessagesForUser(userId);
}
    
function openReinvestmentModalAndSetName(userId, userName, generatedEquity, old_percentage, old_amount, duration, plan, trnx_id) {
    document.getElementById('trnx_id_reinvestment').value = trnx_id;
    document.getElementById('user_name3').innerText = userName;
    document.getElementById('user_generated_equity').innerText = generatedEquity;
    document.getElementById('amount_reinvestment').value = generatedEquity;
    document.getElementById("user_old_plan").innerText = old_amount+", "+duration+", "+plan;
    document.getElementById("duration_reinvestment").value = duration;
    document.getElementById("select2-duration_reinvestment-container").innerText = duration;
    document.getElementById("plan_reinvestment").value = plan;
    document.getElementById("select2-plan_reinvestment-container").innerText = plan;
}
    
function generateMessage() {
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    var formality = document.getElementsByName("formality")[0].value;
    var lang = document.getElementsByName("lang")[0].value;
    console.log(formality);
    var user_id = document.getElementById('user_id').value
    
    // Define the URL for the function
    const url = "/admin/ajax/expiring-transactions-by-user";

    // Define the data you want to send, in this case the user_id
    const data = {
        user_id: user_id,
        lang: lang,
        formality: formality
    };

    // Make the POST request using fetch
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest', 
            'X-CSRF-TOKEN': csrfToken 
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
//        console.log(data.message);
        document.getElementById('sms_textarea').value = data.message;
    })
    .catch(error => {
        console.log('There was a problem with the fetch operation:', error.message);
    });

}
    
function updateChannel() {
    var channel = document.getElementsByName("channel")[0].value;
    console.log(channel);
    
    document.getElementById("smsToUser").action = "/"+channel+"/send";
    
    document.getElementById("send-message").innerHTML = "Send "+channel.toUpperCase();
    
    //get past transactions
    //formality
}
    
function fetchMessagesForUser(userId) {
    console.log("click past messages")
    // Make an AJAX call using Fetch API
    fetch(`/admin/fetch-messages/${userId}`)
    .then(response => response.json())
    .then(data => {
        let messagesList = document.getElementById('messagesList');
        // First, clear the current messages
        messagesList.innerHTML = '';
        // Reverse the order of the messages
        data.reverse().forEach(message => {
            let messageItem = `
                <li class="text-dark row no-gutters justify-content-between">
                    <div class="col-md col-sm-3"><strong class="text-dark">${message.from}</strong></div>
                    <div class="col-md col-sm-3"><span class="text-dark">${message.message}</span></div>
                    <div class="col-md col-sm-3"><span class="text-dark">${message.created_at}</span></div>
                    <div class="col-md col-sm-3"><span class="text-dark">${message.channel}</span></div>
                    <div class="col-md col-sm-3"><span class="text-dark">${message.status}</span></div>
                </li>
            `;
            messagesList.innerHTML += messageItem;
        });
    });

}
    
</script>

<script>
window.addEventListener('DOMContentLoaded', (event) => {
  // Select all <em> tags with class "note-info"
  var emTags = document.getElementsByClassName('note-info');
  console.log("circle: ");

  // Loop through each <em> tag
  for(var i = 0; i < emTags.length; i++) {

      // Get the title attribute
      var title = emTags[i].getAttribute('data-original-title');
//      console.log("title: "+title);

      if(title) {
          // Replace <br> with line breaks
          var newTitle = title.replace(/<br>/g, '\n');

          // Set the new title
          emTags[i].setAttribute('data-original-title', newTitle);
      }
  }
});
</script>