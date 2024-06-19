@extends('layouts.admin')
@section('title', ucfirst($is_page).' Messages')
@section('content')

@php
use Carbon\Carbon;
use App\Models\User;
@endphp

<div class="page-content">
    <div class="container">
        @include('layouts.messages')
        @include('vendor.notice')
        <div class="card content-area content-area-mh">
            <div class="card-innr">
                <div class="card-head has-aside">
                    <h4 class="card-title">{{ ucfirst($is_page) }} Messages</h4>
<!--
                    <div class="relative d-inline-block d-md-none">
                        <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                        <div class="toggle-class dropdown-content dropdown-content-center-left pd-2x">
                            <div class="card-opt data-action-list">
                                <ul class="btn-grp btn-grp-block guttar-20px guttar-vr-10px">
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
-->
<!--
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
-->
                </div>

                @if (auth()->user()->id == '1')
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
                        <div class="search-info">Found  Users{{ (isset(request()->adm) && request()->adm=='yes') ? ' including admin user.' : '.' }}</div>
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
                @endif
                <br>

                <table class="data-table user-list">
                    <thead>
                        <tr class="data-item data-head">
                            <th class="data-col data-col-wd-md filter-data dt-user">User</th>
                            <th class="data-col data-col-wd-md dt-email">From</th>
                            <th class="data-col data-col-wd-md dt-email">Message</th>
                            <th class="data-col data-col-wd-md dt-email">Date</th>
                            <th class="data-col data-col-wd-md dt-email">Channel</th>
                            <th class="data-col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        @foreach($data as $item)
                        
                        
                        
                        @php
                            $client = User::find($item['user_id']);
                            $date = Carbon::parse($item['message_date']);
                            $dateInCET = $date->timezone('Europe/Brussels');
                            $channel = $item['channel'];
                            
                        if ($client) { // Check if the user is null
                            $client_name = $client->name;
                            $client_id = $client->id;
                            $client_phone = $client->email;
                        } else {
                            $client_name = $item['user_phone'];
                            $client_id = $item['user_id'];
                            $client_phone = $item['user_phone'];
                        }
                        
                        @endphp
                        
                        <tr>                            
                            <td class="data-col data-col-wd-md dt-user">
                                <div class="d-flex align-items-center">
                                    <div class="fake-class">
                                        <span class="lead user-name text-wrap">
                                            <a href="{{ route('admin.users.view', [$item['user_id'], 'details'] ) }}" target="_blank">{{ $client_name }}</a>
                                            @if($client) 
                                                @if($client->vip_user) 
                                                    <em class="fas fa-star" data-toggle="tooltip" data-placement="bottom" title="VIP USER"></em>
                                                    <!-- There might be other similar <em> tags here -->
                                                @endif
                                            @endif
                                        </span>
                                        @if ($client) 
                                            @if($client->vip_user == 0) 
                                            <span class="small">({{ round(floatval($client->tokenBalance),3) }} || {{ round(floatval($client->equity),3) }})</span>
                                            @endif
                                        @endif
                                        @if($client)
                                        <span class="sub user-id">{{ $item['user_id'] }}</span>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            
                            <td class="data-col dt-token">
                                <span class="lead lead-btoken">{{ $item['from'] }}</span>
                            </td>
                            
                            <td class="data-col dt-token">
                                <span class="lead lead-btoken">{{ $item['latest_message'] }}</span>
                            </td>
                            
                            <td class="data-col dt-token">
                                <span class="lead lead-btoken">{{ $dateInCET }}</span>
                            </td>
                            
                            <td class="data-col dt-token">
                                <span class="lead lead-btoken">{{ $channel }}</span>
                            </td>
                            
                            <td class="data-col text-right">
                                <div class="relative d-inline-block">
                                    <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                                    <div class="toggle-class dropdown-content dropdown-content-top-left">
                                        <ul class="dropdown-list more-menu-{{$client_id}}">
                                            
                                            @if($client)
                                            <li><a href="{{ route('admin.users.view', [$client->id, 'details'] ) }}"><em class="fa fa-user"></em> User Details</a></li>
                                            
                                            @if (auth()->user()->id == '1')
                                            <li><a class="user-email-action" href="#EmailUser" data-uid="{{ $client->id }}" data-toggle="modal"><em class="far fa-envelope"></em>Send Email</a></li>
                                            @endif
                                            @endif
                                            
                                            <li><a class="user-email-action" href="#CallUser" data-uid="{{ $client_id }}" data-toggle="modal" onclick="call( {{$client_phone}} )"><em class="fa fa-phone"></em>Make Call</a></li>
                                            
                                            <li><a class="user-email-action" href="#SMSUser" data-uid="{{ $client_id }}" data-toggle="modal" onclick="openMessageModalAndSetName( {{$client_id}}, '{{$client_name}}' )"><em class="far fa-bell"></em>Send Message</a></li>
                                            
                                            <li><a class="user-email-action view-messages" href="#pastMessages" data-uid="{{ $client_id }}" data-toggle="modal" onclick="openMessageModalAndSetName2( {{$client_id}}, '{{$client_name}}' )"><em class="fa fa-table"></em>Past Messages</a></li>
                                            
                                            @if($client)
                                            @if($client->role=='user')
                                            <li><a href="javascript:void(0)" data-uid="{{ $client->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>Transactions</a></li>
                                            @endif
                                            @endif
                                            
                                            @if($client)
                                            <li><a href="javascript:void(0)" data-uid="{{ $client->id }}" data-type="activities" class="user-form-action user-action"><em class="fas fa-sign-out-alt"></em>Activities</a></li>
                                            <li><a href="javascript:void(0)" data-uid="{{ $client->id }}" data-type="referrals" class="user-form-action user-action"><em class="fas fa-users"></em>Referrals</a></li>
                                            @endif
                                            
                                            @if (auth()->user()->id == '1')
                                            @if($client)
                                            @if($client->id != save_gmeta('site_super_admin')->value)
                                            <li><a class="user-form-action user-action" href="#" data-type="reset_pwd" data-uid="{{ $client->id }}" ><em class="fas fa-shield-alt"></em>Reset Pass</a></li>
                                            @endif
                                            @if($client->google2fa == 1)
                                            <li><a class="user-form-action user-action" href="javascript:void(0)" data-type="reset_2fa" data-uid="{{ $client->id }}" ><em class="fas fa-unlink"></em>Reset 2FA</a></li>
                                            @endif
                                            @endif
                                            @endif
                                            
                                            @if (auth()->user()->id == '1')
                                            @if($client)
                                            @if(Auth::id() != $client->id && $client->id != save_gmeta('site_super_admin')->value) @if($client->status != 'suspend')
                                            <li><a href="#" data-uid="{{ $client->id }}" data-type="suspend_user" class="user-action front"><em class="fas fa-ban"></em>Suspend</a></li>
                                            @else
                                            <li><a href="#" id="front" data-uid="{{ $client->id }}" data-type="active_user" class="user-action"><em class="fas fa-ban"></em>Active</a></li>
                                            @endif @endif
                                            @endif
                                            @endif
                                            
                                            @if($client)
                                                <li><a class="user-email-action" href="#note" data-uid="{{ $client->id }}" data-toggle="modal" onclick="openModalAndSetTransactionId( {{$client->id}}, '{{$client->note}}', '{{$client->name}}' )"><em class="fa fa-book-open"></em>Note</a></li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

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
                                    <option value="support">Support</option>
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

    if(channel === "support"){
        channel = "admin/" + channel;
    }
    
    document.getElementById("smsToUser").action = "/" + channel + "/send";
    
    document.getElementById("send-message").innerHTML = "Send " + channel.toUpperCase();

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

<script src="/assets/js/twilio.min.js"></script>

<!--Twilio make call-->
<script>

    let accessToken;
    let device;

    // Fetch accessToken from the server
    function fetchToken() {
        console.log("start fetch");
        const xhr = new XMLHttpRequest();
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                const jsonResponse = JSON.parse(xhr.responseText);
                accessToken = jsonResponse.token;
                setupDevice();
                console.log("start fetch 2");
            }
        };
        xhr.open('GET', '/twilio/token', true);
        xhr.send();
    }

    function setupDevice() {
        console.log("start fetch 3");
        device = new Twilio.Device(accessToken, {debug: true});

        device.on('ready', (device) => {
            console.log('Twilio.Device is now ready for connections');
        });

        device.on('error', (error) => {
            console.error('Twilio.Device Error:', error.message);
        });
    }

    function call(number) {
        console.log('Calling', number);
        const params = {To: number};
        device.connect(params);
    }

    function hangup() {
        device.disconnectAll();
    }

//    function makeCall(number) {
//        const number = document.getElementById('phoneNumber').value;
//        if (number) {
//            call(number);
//        } else {
//            alert("Please enter a phone number!");
//        }
//    }

    // Fetch token on script load
    fetchToken();
    
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
