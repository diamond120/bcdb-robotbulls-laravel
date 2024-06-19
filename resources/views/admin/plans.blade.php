@extends('layouts.admin')
@section('title', 'Ending Plans')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="row">
            <div class="main-content col-lg-12">
                @include('vendor.notice')
                <div class="card content-area content-area-mh">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title">Ending Plans</h4>
                        </div>

                        <div class="data-item data-head popup-body-innr">
                            <div class="row">
                                <span class="data-col col-4 text-center">Period</span>
                                <span class="data-col col-2 text-center">Amount</span>
                                <span class="data-col col-2 text-center">Equity</span>
                                <span class="data-col col-2 text-center">Prediction</span>
                                <span class="data-col col-2 text-center">Gains</span>
                            </div>
                        </div>


                        @foreach($plans as $period => $plan)
                        <div class="kyc-option popup-body-innr">
                            <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_general d-flex pt-1 pb-1">
                                <span class="data-col col-4 text-center">
                                    {{ $plan['period'] }}
                                    <span class="d-block small">{{ $plan['start_date'] }} - {{ $plan['end_date'] }}</span>
                                </span>
                                <span class="data-col col-2 text-center">
                                    {{ number_format($plan['amount']) }}
                                    <span class="d-block small">{{ number_format($plan['total_amount']) }}</span>
                                </span>
                                <span class="data-col col-2 text-center">
                                    {{ number_format($plan['equity']) }}
                                    <span class="d-block small">{{ number_format($plan['total_equity']) }}</span>
                                </span>
                                <span class="data-col col-2 text-center">
                                    {{ number_format($plan['prediction']) }}
                                    <span class="d-block small">{{ number_format($plan['total_prediction']) }}</span>
                                </span>
                                <span class="data-col col-2 text-center">
                                    {{ number_format($plan['prediction']-$plan['amount']) }}
                                    <span class="d-block small">{{ number_format($plan['total_prediction']-$plan['total_amount']) }}</span>
                                </span>
                            </div>
                            <div class="kyc-option-content toggle-content popup-body-innr" style="display: none;">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">

                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Amount</th>
                                                    <th>Equity</th>
                                                    <th>Prediction</th>
                                                    <th>Gains</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($plan['top_users'] as $user)
                                                <tr class="data-item">
                                                    <td class="data-col dt-user">
                                                        <div class="d-flex align-items-center">
                                                            <div class="fake-class">
                                                                <span class="lead user-name text-wrap">
                                                                    <a href="{{ route('admin.users.view', [$user->id, 'details']) }}" target="_blank">
                                                                        {{ $user->name }}
                                                                    </a>
                                                                </span>
                                                                <span class="sub user-id">
                                                                    {{ 'User ' . $user->id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalAmount) }}</span>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity) }}</span>
                                                    </td>
                                                    <td class="data-col dt-prediction">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->amount_prediction) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalPrediction) }}</span>
                                                    </td>
                                                    <td class="data-col dt-prediction">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->amount_prediction-$user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalPrediction-$user->runningTotalAmount) }}</span>
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

                                                                    <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>Transactions</a></li>

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
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach




                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}
                <div class="card content-area">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title">Other Plans</h4>
                        </div>
                        <div class="data-item data-head popup-body-innr">
                            <div class="row">
                                <span class="data-col col-3 text-center">Plan</span>
                                <span class="data-col col-3 text-center">Amount</span>
                                <span class="data-col col-3 text-center">Equity</span>
                                <span class="data-col col-3 text-center">Gains</span>
                            </div>
                        </div>
                        
                        <div class="kyc-option popup-body-innr">
                            <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_general d-flex pt-1 pb-1">
                                <span class="data-col col-3 text-center">
                                    BTC Bull
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['BTC_Bull_amount']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['BTC_Bull_equity']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['BTC_Bull_equity']-$plan['BTC_Bull_amount']) }}
                                </span>
                            </div>
                            
                            <div class="kyc-option-content toggle-content popup-body-innr" style="display: none;">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Amount</th>
                                                    <th>Equity</th>
                                                    <th>Gains</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($plan['top_users_btc'] as $user)
                                                <tr class="data-item">
                                                    <td class="data-col dt-user">
                                                        <div class="d-flex align-items-center">
                                                            <div class="fake-class">
                                                                <span class="lead user-name text-wrap">
                                                                    <a href="{{ route('admin.users.view', [$user->id, 'details']) }}" target="_blank">
                                                                        {{ $user->name }}
                                                                    </a>
                                                                </span>
                                                                <span class="sub user-id">
                                                                    {{ 'User ' . $user->id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalAmount) }}</span>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity) }}</span>
                                                    </td>
                                                    <td class="data-col dt-prediction">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity-$user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity-$user->runningTotalAmount) }}</span>
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

                                                                    <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>Transactions</a></li>

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
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="kyc-option popup-body-innr">
                            <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_general d-flex pt-1 pb-1">
                                <span class="data-col col-3 text-center">
                                    ETH Bull
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['ETH_Bull_amount']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['ETH_Bull_equity']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['ETH_Bull_equity']-$plan['ETH_Bull_amount']) }}
                                </span>
                            </div>
                            
                            <div class="kyc-option-content toggle-content popup-body-innr" style="display: none;">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Amount</th>
                                                    <th>Equity</th>
                                                    <th>Gains</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($plan['top_users_eth'] as $user)
                                                <tr class="data-item">
                                                    <td class="data-col dt-user">
                                                        <div class="d-flex align-items-center">
                                                            <div class="fake-class">
                                                                <span class="lead user-name text-wrap">
                                                                    <a href="{{ route('admin.users.view', [$user->id, 'details']) }}" target="_blank">
                                                                        {{ $user->name }}
                                                                    </a>
                                                                </span>
                                                                <span class="sub user-id">
                                                                    {{ 'User ' . $user->id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalAmount) }}</span>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity) }}</span>
                                                    </td>
                                                    <td class="data-col dt-prediction">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity-$user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity-$user->runningTotalAmount) }}</span>
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

                                                                    <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>Transactions</a></li>

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
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="kyc-option popup-body-innr">
                            <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_general d-flex pt-1 pb-1">
                                <span class="data-col col-3 text-center">
                                    RBC
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['RBC_amount']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['RBC_equity']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['RBC_equity']-$plan['RBC_amount']) }}
                                </span>
                            </div>
                            
                            <div class="kyc-option-content toggle-content popup-body-innr" style="display: none;">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Amount</th>
                                                    <th>Equity</th>
                                                    <th>Gains</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($plan['top_users_rbc'] as $user)
                                                <tr class="data-item">
                                                    <td class="data-col dt-user">
                                                        <div class="d-flex align-items-center">
                                                            <div class="fake-class">
                                                                <span class="lead user-name text-wrap">
                                                                    <a href="{{ route('admin.users.view', [$user->id, 'details']) }}" target="_blank">
                                                                        {{ $user->name }}
                                                                    </a>
                                                                </span>
                                                                <span class="sub user-id">
                                                                    {{ 'User ' . $user->id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalAmount) }}</span>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity) }}</span>
                                                    </td>
                                                    <td class="data-col dt-prediction">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity-$user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity-$user->runningTotalAmount) }}</span>
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

                                                                    <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>Transactions</a></li>

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
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="kyc-option popup-body-innr">
                            <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_general d-flex pt-1 pb-1">
                                <span class="data-col col-3 text-center">
                                    Referral
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['Referral_amount']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['Referral_equity']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['Referral_equity']-$plan['Referral_amount']) }}
                                </span>
                            </div>
                            
                            <div class="kyc-option-content toggle-content popup-body-innr" style="display: none;">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Amount</th>
                                                    <th>Equity</th>
                                                    <th>Gains</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($plan['top_users_referral'] as $user)
                                                <tr class="data-item">
                                                    <td class="data-col dt-user">
                                                        <div class="d-flex align-items-center">
                                                            <div class="fake-class">
                                                                <span class="lead user-name text-wrap">
                                                                    <a href="{{ route('admin.users.view', [$user->id, 'details']) }}" target="_blank">
                                                                        {{ $user->name }}
                                                                    </a>
                                                                </span>
                                                                <span class="sub user-id">
                                                                    {{ 'User ' . $user->id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalAmount) }}</span>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity) }}</span>
                                                    </td>
                                                    <td class="data-col dt-prediction">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity-$user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity-$user->runningTotalAmount) }}</span>
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

                                                                    <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>Transactions</a></li>

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
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="kyc-option popup-body-innr">
                            <div class="kyc-option-head toggle-content-tigger collapse-icon-right popup_general d-flex pt-1 pb-1">
                                <span class="data-col col-3 text-center">
                                    Bonus
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['Bonus_amount']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['Bonus_equity']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['Bonus_equity']-$plan['Bonus_amount']) }}
                                </span>
                            </div>
                            
                            <div class="kyc-option-content toggle-content popup-body-innr" style="display: none;">
                                <div class="row align-items-center">
                                    <div class="col-sm-12">
                                        <table class="w-100">
                                            <thead>
                                                <tr>
                                                    <th>User</th>
                                                    <th>Amount</th>
                                                    <th>Equity</th>
                                                    <th>Gains</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($plan['top_users_bonus'] as $user)
                                                <tr class="data-item">
                                                    <td class="data-col dt-user">
                                                        <div class="d-flex align-items-center">
                                                            <div class="fake-class">
                                                                <span class="lead user-name text-wrap">
                                                                    <a href="{{ route('admin.users.view', [$user->id, 'details']) }}" target="_blank">
                                                                        {{ $user->name }}
                                                                    </a>
                                                                </span>
                                                                <span class="sub user-id">
                                                                    {{ 'User ' . $user->id }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalAmount) }}</span>
                                                    </td>
                                                    <td class="data-col dt-token">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity) }}</span>
                                                    </td>
                                                    <td class="data-col dt-prediction">
                                                        <span class="lead lead-b token">
                                                            {{ number_format($user->total_equity-$user->total_amount) }}
                                                        </span>
                                                        <span class="small">{{ number_format($user->runningTotalEquity-$user->runningTotalAmount) }}</span>
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

                                                                    <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>Transactions</a></li>

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
                                                @endforeach
                                            </tbody>
                                        </table>

                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="kyc-option popup-body-innr">
                            <div class="kyc-option-head popup_general d-flex pt-1 pb-1">
                                <span class="data-col col-3 text-center">
                                    Total
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['Total_amount']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['Total_equity']) }}
                                </span>
                                <span class="data-col col-3 text-center">
                                    {{ number_format($plan['Total_equity']-$plan['Total_amount']) }}
                                </span>
                            </div>
                        </div>
                        
                    </div>{{-- .card-innr --}}
                </div>{{-- .card --}}
            </div>{{-- .col --}}
        </div>{{-- .container --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection



@push('footer')

<script>
    $(".toggle-content-tigger").click(function() {
        var $target = $(this).toggleClass("active").parent().find(".toggle-content").slideToggle();
        $('.toggle-content').not($target).hide();
        window.scrollTo({
            top: $(this).offset.top,
            left: 0,
            behavior: 'smooth'
        });
        return false;
    });

</script>

@endpush
