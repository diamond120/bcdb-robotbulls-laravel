@extends('layouts.admin')
@section('title', ucfirst($is_page).' User List')
@section('content')

<div class="page-content">
    <div class="container">
        @include('layouts.messages')
        @include('vendor.notice')
        <div class="card content-area content-area-mh">
            <div class="card-innr">
                <div class="card-head has-aside">
                    <h4 class="card-title">{{ ucfirst($is_page) }} User List</h4>
                    <div class="relative d-inline-block d-md-none">
                        <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                        <div class="toggle-class dropdown-content dropdown-content-center-left pd-2x">
                            <div class="card-opt data-action-list">
                                <ul class="btn-grp btn-grp-block guttar-20px guttar-vr-10px">
                                    <li><a class="btn btn-auto btn-info btn-outline btn-sm" href="{{ route('admin.users.wallet.change') }}">Wallet Change Request</a></li>
                                    <li>
                                        <a href="#" class="btn btn-auto btn-sm btn-primary" data-toggle="modal" data-target="#addUser">
                                            <em class="fas fa-plus-circle"> </em>
                                            <span>Add <span class="d-none d-md-inline-block">User</span></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="card-opt data-action-list d-none d-md-inline-flex">
                        <ul class="btn-grp btn-grp-block guttar-20px">
                            <li><a class="btn btn-info btn-outline btn-sm" href="{{ route('admin.users.wallet.change') }}">Wallet Change Request</a></li>
                            <li>
                                <a href="#" class="btn btn-auto btn-sm btn-primary" data-toggle="modal" data-target="#addUser">
                                    <em class="fas fa-plus-circle"> </em><span>Add <span class="d-none d-md-inline-block">User</span></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="page-nav-wrap">
                    <div class="page-nav-bar justify-content-between bg-lighter">
                        <div class="page-nav w-100 w-lg-auto">
                            @if (!is_page('activities') && !is_page('activities.all') && !is_page('activities.today') && !is_page('activities.last-week') && !is_page('activities.last-two-week') && !is_page('activities.last-month') && !is_page('activities.last-three-months') && !is_page('activities.last-six-months') && !is_page('activities.last-year'))
                            <ul class="nav">
                                <li class="nav-item{{ (is_page('users.user') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.users', 'user') }}">Investor / Users</a></li>
                                <li class="nav-item {{ (is_page('users.admin') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.users', 'admin') }}">Admin Account</a></li>
                                <li class="nav-item {{ (is_page('users') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.users') }}">All</a></li>
                                <li class="nav-item{{ (is_page('users.biggest') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.users', 'biggest') }}">Biggest</a></li>
                            </ul>
                            @else
                            <ul class="nav">
                                <li class="nav-item{{ (is_page('activities') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.activities', 'all') }}">All</a></li>
                                <li class="nav-item{{ (is_page('activities.today') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.activities', 'today') }}">Today</a></li>
                                <li class="nav-item{{ (is_page('activities.last-week') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.activities', 'last-week') }}">Last Week</a></li>
                                <li class="nav-item{{ (is_page('activities.last-two-week') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.activities', 'last-two-week') }}">Last 2 Week</a></li>
                                <li class="nav-item{{ (is_page('activities.last-month') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.activities', 'last-month') }}">Last Month</a></li>
                                <li class="nav-item{{ (is_page('activities.last-three-months') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.activities', 'last-three-months') }}">Last 3 Months</a></li>
                                <li class="nav-item{{ (is_page('activities.last-six-months') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.activities', 'last-six-months') }}">Last 6 Months</a></li>
                                <li class="nav-item{{ (is_page('activities.last-year') ? ' active' : '') }}"><a class="nav-link" href="{{ route('admin.activities', 'last-year') }}">Last Year</a></li>
                            </ul>
                            @endif
                        </div>
                        <div class="search flex-grow-1 pl-lg-4 w-100 w-sm-auto">
                            <form action="{{ route('admin.users') }}" method="GET" autocomplete="off">
                                <div class="input-wrap">
                                    <span class="input-icon input-icon-left"><em class="ti ti-search"></em></span>
                                    <input type="search" class="input-solid input-transparent" placeholder="Quick search with name/email/id/wallet address" value="{{ request()->get('s', '') }}" name="s">
                                </div>
                            </form>
                        </div>
                        <div class="tools w-100 w-sm-auto">
                            <ul class="btn-grp guttar-8px">
                                <li>
                                    <form action="{{ route('admin.ajax.users.delete') }}" method="POST">
                                        <li><a href="javascript:void(0)" title="Delete all unvarified users" data-toggle="tooltip" class="btn btn-danger btn-icon btn-outline btn-sm delete-unverified-user mr-md-2"> <em class="ti ti-trash"></em> </a></li>
                                    </form>
                                </li>

                                <li><a href="#" class="btn btn-light btn-sm btn-icon btn-outline bg-white advsearch-opt"> <em class="ti ti-panel"></em> </a></li>
                                <li>
                                    <div class="relative">
                                        <a href="#" class="btn btn-light bg-white btn-sm btn-icon toggle-tigger btn-outline"><em class="ti ti-server"></em> </a>
                                        <div class="toggle-class dropdown-content dropdown-content-sm dropdown-content-center shadow-soft">
                                            <ul class="dropdown-list">
                                                <li><h6 class="dropdown-title">Export</h6></li>
                                                <li><a href="{{ route('admin.export', array_merge([ 'table' => 'users', 'format' => 'entire'], request()->all())) }}">Entire</a></li>
                                                <li><a href="{{ route('admin.export',array_merge([ 'table' => 'users', 'format' => 'minimal'], request()->all())) }}">Minimal</a></li>
                                                <li><a href="{{ route('admin.export',array_merge([ 'table' => 'users', 'format' => 'compact'], request()->all())) }}">Compact</a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li>
                                    <div class="relative">
                                        <a href="#" class="btn btn-light bg-white btn-sm btn-icon toggle-tigger btn-outline"><em class="ti ti-settings"></em> </a>
                                        <div class="toggle-class dropdown-content dropdown-content-sm dropdown-content-center shadow-soft">
                                            <form class="update-meta" action="#" data-type="user_page_meta">
                                                <ul class="dropdown-list">
                                                    <li><h6 class="dropdown-title">Show</h6></li>
                                                    <li{!! (gmvl('user_per_page', 10)==10) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=10">10</a></li>
                                                    <li{!! (gmvl('user_per_page', 10)==20) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=20">20</a></li>
                                                    <li{!! (gmvl('user_per_page', 10)==50) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=50">50</a></li>
                                                    <li{!! (gmvl('user_per_page', 10)==100) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=100">100</a></li>
                                                    <li{!! (gmvl('user_per_page', 10)==200) ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="perpage=200">200</a></li>
                                                </ul>
                                                <ul class="dropdown-list">
                                                    <li><h6 class="dropdown-title">Order By</h6></li>
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
                                                    <li{!! (gmvl('user_ordered', 'DESC')=='DESC') ? ' class="active"' : '' !!}>
                                                        <a href="#" data-meta="ordered=DESC">DESC</a></li>
                                                    <li{!! (gmvl('user_ordered', 'DESC')=='ASC') ? ' class="active"' : '' !!}>
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
                        <form class="adv-search" id="adv-search" action="{{ route('admin.users') }}" method="GET" autocomplete="off">
                            <div class="adv-search">
                                <div class="row align-items-end guttar-20px guttar-vr-15px">
                                    <div class="col-lg-6">
                                       <div class="input-grp-wrap">
                                            <span class="input-item-label input-item-label-s2 text-exlight">Advanced Search</span>
                                            <div class="input-grp align-items-center bg-white">
                                                <div class="input-wrap flex-grow-1">
                                                    <input value="{{ request()->get('search') }}" class="input-solid input-solid-sm input-transparent" type="text" placeholder="Search by user" name="search">
                                                </div>
                                                <ul class="search-type">
                                                    <li class="input-wrap input-radio-wrap">
                                                        <input name="by" value="" class="input-radio-select" id="advs-by-name" type="radio" id="advs-by-name"{{ (empty(request()->by) || (request()->by!='email' && request()->by!='id')) ? ' checked' : '' }}>
                                                        <label for="advs-by-name">Name</label>
                                                    </li>
                                                    <li class="input-wrap input-radio-wrap">
                                                        <input name="by" value="email" class="input-radio-select" id="advs-by-email" type="radio" id="advs-by-email"{{ (isset(request()->by) && request()->by=='email') ? ' checked' : '' }}>
                                                        <label for="advs-by-email">Email</label>
                                                    </li>
                                                    <li class="input-wrap input-radio-wrap">
                                                        <input name="by" value="id" class="input-radio-select" id="advs-by-id" type="radio" id="advs-by-id"{{ (isset(request()->by) && request()->by=='id') ? ' checked' : '' }}>
                                                        <label for="advs-by-id">ID</label>
                                                    </li>
                                                    <li class="input-wrap input-radio-wrap">
                                                        <input name="by" value="walletAddress" class="input-radio-select" id="advs-by-walletAddress" type="radio" id="advs-by-walletAddress"{{ (isset(request()->by) && request()->by=='walletAddress') ? ' checked' : '' }}>
                                                        <label for="advs-by-walletAddress">Wallet</label>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-sm-4 col-mb-6">
                                        <div class="input-wrap input-item-middle text-left">
                                            <input {{ request()->get('wallet') == 'yes' ? 'checked' : '' }} name="wallet" value="yes" class="input-checkbox input-checkbox-md" id="has-wallet" type="checkbox">
                                            <label for="has-wallet">Has Wallet</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-sm-8 col-mb-6">
                                        <div class="input-wrap input-item-middle text-left">
                                            <input {{ request()->get('adm') == 'yes' ? 'checked' : '' }} name="adm" value="yes" class="input-checkbox input-checkbox-md" id="include-admin" type="checkbox">
                                            <label for="include-admin">Including Admin</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-lg-2 col-mb-6">
                                        <div class="input-wrap input-with-label">
                                            <label class="input-item-label input-item-label-s2 text-exlight">Account Status</label>
                                            <select name="state" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                                <option value="">Any Status</option>
                                                <option{{ request()->get('state') == 'active' ? ' selected' : '' }} value="active">Actived</option>
                                                <option{{ request()->get('state') == 'suspend' ? ' selected' : '' }} value="suspend">Suspended</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-lg-2 col-mb-6">
                                        <div class="input-wrap input-with-label">
                                            <label class="input-item-label input-item-label-s2 text-exlight">Reg Method</label>
                                            <select name="reg" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                                <option value="">Any Method</option>
                                                <option{{ request()->get('reg') == 'internal' ? ' selected' : '' }} value="internal">Internal</option>
                                                <option{{ request()->get('reg') == 'email' ? ' selected' : '' }} value="email">Email</option>
                                                <option{{ request()->get('reg') == 'google' ? ' selected' : '' }} value="google">Google</option>
                                                <option{{ request()->get('reg') == 'facebook' ? ' selected' : '' }} value="facebook">Facebook</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-lg-2 col-mb-6">
                                        <div class="input-wrap input-with-label">
                                            <label class="input-item-label input-item-label-s2 text-exlight">Verified Status</label>
                                            <select name="valid" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                                <option value="">Anything</option>
                                                <option{{ request()->get('valid') == 'email' ? ' selected' : '' }} value="email">Email Verified</option>
                                                <option{{ request()->get('valid') == 'kyc' ? ' selected' : '' }} value="kyc">KYC Verified</option>
                                                <option{{ request()->get('valid') == 'both' ? ' selected' : '' }} value="both">Both Verified</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-lg-2 col-mb-6">
                                        <div class="input-wrap input-with-label">
                                            <label class="input-item-label input-item-label-s2 text-exlight">Token Balance</label>
                                            <select name="token" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                                <option value="">Any Amount</option>
                                                <option {{ request()->get('token') == 'has' ? 'selected' : '' }} value="has">Has Token</option>
                                                <option {{ request()->get('token') == 'zero' ? 'selected' : '' }} value="zero">Zero Token</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-sm-4 col-lg-2 col-mb-6">
                                        <div class="input-wrap input-with-label">
                                            <label class="input-item-label input-item-label-s2 text-exlight">Is Referred By</label>
                                            <select name="refer" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                                <option value="">Anything</option>
                                                <option {{ request()->get('refer') == 'yes' ? 'selected' : '' }} value="yes">Yes</option>
                                                <option {{ request()->get('refer') == 'no' ? 'selected' : '' }} value="no">No</option>
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
                                    <div class="col-sm-4 col-lg-2 col-mb-6">
                                        <div class="input-wrap">
                                            <input type="hidden" name="filter" value="1">
                                            <button class="btn btn-sm btn-sm-s2 btn-auto btn-primary">
                                                <em class="ti ti-search width-auto"></em><span>Search</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    @if (request()->get('filter') || request()->s)
                    <div class="search-adv-result">
                        <div class="search-info">Found <span class="search-count">{{ $users->total() }}</span> Users{{ (isset(request()->adm) && request()->adm=='yes') ? ' including admin user.' : '.' }}</div>
                        <ul class="search-opt">
                            @if(request()->get('search'))
                                <li><a href="{{ qs_url(qs_filter('search')) }}">Search <span>'{{ request()->get('search') }}'</span>{{ (!empty(request()->by)) ? ' ('.(request()->by=='id' ? strtoupper(request()->by) : ucfirst(request()->by)).')' : ' (Name)' }}</a></li>
                            @endif
                            @if(request()->get('wallet'))
                                <li><a href="{{ qs_url(qs_filter('wallet')) }}">Has <span>Wallet</span></a></li>
                            @endif
                            @if(request()->get('token'))
                                <li><a href="{{ qs_url(qs_filter('token')) }}"><span>{{ ucfirst(request()->get('token')) }}</span> Token</a></li>
                            @endif
                            @if(request()->get('state'))
                                <li><a href="{{ qs_url(qs_filter('state')) }}">Status: <span>{{ ucfirst(request()->get('state')) }}</span></a></li>
                            @endif
                            @if(request()->get('reg'))
                                <li><a href="{{ qs_url(qs_filter('reg')) }}">Reg Method:  <span>{{ ucfirst(request()->get('reg')) }}</span></a></li>
                            @endif
                            @if(request()->get('valid'))
                                <li><a href="{{ qs_url(qs_filter('valid')) }}">Verified: <span>{{ (request()->valid=='kyc' ? strtoupper(request()->valid) : ucfirst(request()->valid)) }}</span></a></li>
                            @endif
                            @if(request()->get('refer'))
                                <li><a href="{{ qs_url(qs_filter('refer')) }}">Referred: <span>{{ ucfirst(request()->get('refer')) }}</span></a></li>
                            @endif
                            <li><a href="{{ route('admin.users') }}" class="link link-underline">Clear All</a></li>
                        </ul>
                    </div>
                    @endif
                </div>

                @if($users->total() > 0) 
                <table class="data-table user-list">
                    <thead>
                        <tr class="data-item data-head">
                            <th class="data-col data-col-wd-md filter-data dt-user">User</th>
                            <th class="data-col data-col-wd-md dt-email">Email</th>
                            <th class="data-col dt-token">Tokens</th>
                            <th class="data-col dt-verify">Verified Status</th>
                            <th class="data-col dt-login">Last/First Login</th>
                            <th class="data-col dt-status">Status</th>
                            <th class="data-col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users->getItems() as $user)
                        <tr class="data-item">
                            <td class="data-col data-col-wd-md dt-user">
                                <div class="d-flex align-items-center">
                                    <div class="fake-class">
                                        <span class="lead user-name text-wrap">
                                            <a href="{{ route('admin.users.view', [$user->id, 'details'] ) }}" target="_blank">{{ $user->name }}</a>
                                            @if($user->note) 
                                            <em class="fas fa-info-circle note-info" data-toggle="tooltip" data-placement="bottom" title="{{ $user->note }}"></em>
                                            <!-- There might be other similar <em> tags here -->
                                            @endif
                                        </span>
                                        <span class="sub user-id">{{ set_id($user->id, 'user') }}
                                            @if($user->role == 'admin') 
                                            <span class="badge badge-xs badge-dim badge-{{($user->type != 'demo')?'success':'danger'}}">ADMIN</span>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td class="data-col data-col-wd-md dt-email">
                                <span class="sub sub-s2 sub-email text-wrap">{{ explode_user_for_demo($user->email, auth()->user()->type ) }}</span>
                            </td>
                            <td class="data-col dt-token">
                                <span class="lead lead-btoken">{{ number_format($user->tokenBalance) }} <span class="small">({{ number_format($user->equity) }})</span></span>
                            </td>
                            <td class="data-col dt-verify">
                                <ul class="data-vr-list">
                                    <li><div class="data-state data-state-sm data-state-{{ $user->email_verified_at !== null ? 'approved' : 'pending'}}"></div> Email</li>
                                    @php 
                                    if(isset($user->kyc_info->status)){ $user->kyc_info->status = str_replace('rejected', 'canceled', $user->kyc_info->status); }
                                    $kyc_a_bf = isset($user->kyc_info->id) ? '<a href="'.route('admin.kyc.view', [$user->kyc_info->id, 'kyc_details' ]).'" target="_blank">' : ''; 
                                    $kyc_a_af = isset($user->kyc_info->id) ? '</a>' : '';
                                    @endphp 
                                    @if($user->role != 'admin')
                                    <li>{!! $kyc_a_bf !!}<div class="data-state data-state-sm data-state-{{ !empty($user->kyc_info) ? $user->kyc_info->status : 'missing' }}"></div>KYC {!! $kyc_a_af !!}</li>
                                    @endif
                                </ul>
                            </td>
                            <td class="data-col dt-login">
                                <span class="sub sub-s2 sub-time">{{ $user->latestActivity && $user->email_verified_at !== null ? _date($user->latestActivity->created_at) : 'Not logged yet' }}</span>
                                <span class="sub sub-s2 sub-time">{{ $user->created_at && $user->email_verified_at !== null ? _date($user->created_at) : 'Not logged yet' }}</span>
                            </td>
                            <td class="data-col dt-status">
                                <span class="dt-status-md badge badge-outline badge-md badge-{{ __status($user->status,'status') }}">{{ __status($user->status,'text') }}</span>
                                <span class="dt-status-sm badge badge-sq badge-outline badge-md badge-{{ __status($user->status,'status') }}">{{ substr(__status($user->status,'text'), 0, 1) }}</span>
                            </td>
                            <td class="data-col text-right">
                                <div class="relative d-inline-block">
                                    <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                                    <div class="toggle-class dropdown-content dropdown-content-top-left">
                                        <ul class="dropdown-list more-menu-{{$user->id}}">
                                            <li><a href="{{ route('admin.users.view', [$user->id, 'details'] ) }}"><em class="fa fa-user"></em> User Details</a></li>
                                            <li><a class="user-email-action" href="#EmailUser" data-uid="{{ $user->id }}" data-toggle="modal"><em class="far fa-envelope"></em>Send Email</a></li>
                                            <li><a class="user-email-action" href="#SMSUser" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openMessageModalAndSetName( {{$user->id}}, '{{$user->name}}' )"><em class="far fa-bell"></em>Send Message</a></li>
                                            
                                            <li><a class="user-email-action view-messages" href="#pastMessages" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openMessageModalAndSetName2( {{$user->id}}, '{{$user->name}}' )"><em class="fa fa-table"></em>Past Messages</a></li>
                                            
                                            @if($user->role=='user')
                                            <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>Transactions</a></li>
                                            @endif
                                            
                                            <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="activities" class="user-form-action user-action"><em class="fas fa-sign-out-alt"></em>Activities</a></li>
                                            <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="referrals" class="user-form-action user-action"><em class="fas fa-users"></em>Referrals</a></li>

                                            @if($user->id != save_gmeta('site_super_admin')->value)
                                            <li><a class="user-form-action user-action" href="#" data-type="reset_pwd" data-uid="{{ $user->id }}" ><em class="fas fa-shield-alt"></em>Reset Pass</a></li>
                                            @endif
                                            @if($user->google2fa == 1)
                                            <li><a class="user-form-action user-action" href="javascript:void(0)" data-type="reset_2fa" data-uid="{{ $user->id }}" ><em class="fas fa-unlink"></em>Reset 2FA</a></li>
                                            @endif

                                            @if(Auth::id() != $user->id && $user->id != save_gmeta('site_super_admin')->value) @if($user->status != 'suspend')
                                            <li><a href="#" data-uid="{{ $user->id }}" data-type="suspend_user" class="user-action front"><em class="fas fa-ban"></em>Suspend</a></li>

                                            @else
                                            <li><a href="#" id="front" data-uid="{{ $user->id }}" data-type="active_user" class="user-action"><em class="fas fa-ban"></em>Active</a></li>
                                            @endif @endif
                                            
                                            <li><a class="user-email-action" href="#note" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openModalAndSetTransactionId( {{$user->id}}, '{{$user->note}}', '{{$user->name}}' )"><em class="fa fa-book-open"></em>Note</a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        {{-- .data-item --}}
                        @endforeach
                    </tbody>
                </table>
                @else 
                    <div class="bg-light text-center rounded pdt-5x pdb-5x">
                        <p><em class="ti ti-server fs-24"></em><br>{{ ($is_page=='all') ? 'No investor / user found!' : 'No '.$is_page.' user here!' }}</p>
                        <p><a class="btn btn-primary btn-auto" href="{{ route('admin.users', 'user') }}">View All Users</a></p>
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
            </div>
            {{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}

@endsection

@section('modals')

<div class="modal fade" id="addUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Add New User</h3>
                <form action="{{ route('admin.ajax.users.add') }}" method="POST" class="adduser-form validate-modern" id="addUserForm" autocomplete="false">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">User Type</label>
                                <select name="role" class="select select-bordered select-block" required="required">
                                    <option value="user">
                                        Regular
                                    </option>
                                    <option value="admin">
                                        Admin
                                    </option>

                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="input-item input-with-label">
                        <label class="input-item-label">Full Name</label>
                        <div class="input-wrap">
                            <input name="name" class="input-bordered" minlength="3" required="required" type="text" placeholder="User Full Name">
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Phone</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" required="required" name="email" type="text" placeholder="Phone">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Password</label>
                                <div class="input-wrap">
                                    <input name="password" class="input-bordered" minlength="6" placeholder="Automatically generated if blank" type="password" autocomplete='new-password'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="input-item">
                        <input checked class="input-checkbox input-checkbox-sm" name="email_req" id="send-email" type="checkbox">
                        <label for="send-email">Required Email Verification
                        </label>
                    </div>
                    <div class="gaps-1x"></div>
                    <button class="btn btn-md btn-primary" type="submit">Add User</button>
                </form>
            </div>
        </div>
        {{-- .modal-content --}}
    </div>
    {{-- .modal-dialog --}}
</div>

<div class="modal fade" id="EmailUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Send Email to User </h3>
                <div class="msg-box"></div>
                <form class="validate-modern" id="emailToUser" action="{{ route('admin.ajax.users.email') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="input-item input-with-label">
                        <label class="clear input-item-label">Email Subject</label>
                        <div class="input-wrap">
                            <input type="text" name="subject" class="input-bordered cls" placeholder="New Message">
                        </div>
                    </div>
                    <div class="input-item input-with-label">
                        <label class="clear input-item-label">Email Greeting</label>
                        <div class="input-wrap">
                            <input type="text" name="greeting" class="input-bordered cls" placeholder="Hello User">
                        </div>
                    </div>
                    <div class="input-item input-with-label">
                        <label class="clear input-item-label">Your Message</label>
                        <div class="input-wrap">
                            <textarea required="required" name="message" class="input-bordered cls input-textarea input-textarea-sm" type="text" placeholder="Write something..."></textarea>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Email</button>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
        
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
                        
                        <div class="col-6">
                            <div class="input-wrap input-with-label">
                                <label class="input-item-label input-item-label-s2 text-exlight">Formality</label>
                                <select name="formality" class="select select-sm select-block select-bordered" data-dd-class="search-off">
                                    <option value="formal">Formal</option>
                                    <option value="informal">Informal</option>
                                    <option value="friend">Friend</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-6">
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

                        <div class="col-6">
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
    
<div class="modal fade" id="note" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title" id="note-title">User Note</h3>
                <div class="msg-box"></div>
                <form class="validate-modern" id="editTransactionNoteForm" action="{{ route('admin.ajax.transactions.editNote') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">User Note</label>
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
        console.log(data.message);
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

    
<!--note-->
<script>
window.addEventListener('DOMContentLoaded', (event) => {
  // Select all <em> tags with class "note-info"
  var emTags = document.getElementsByClassName('note-info');
  console.log("circle: ");

  // Loop through each <em> tag
  for(var i = 0; i < emTags.length; i++) {

      // Get the title attribute
      var title = emTags[i].getAttribute('data-original-title');
      console.log("title: "+title);

      if(title) {
          // Replace <br> with line breaks
          var newTitle = title.replace(/<br>/g, '\n');

          // Set the new title
          emTags[i].setAttribute('data-original-title', newTitle);
      }
  }
});
</script>
    
<!--
    <script>
        document.getElementById('sms_textarea').value = 'Hello '+document.getElementById('userAction').value+' \n\nNew line 3'
    </script>
-->

@endsection