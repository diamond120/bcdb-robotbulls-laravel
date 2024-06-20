@extends('layouts.admin')

@php

use App\Models\Transaction;

$trnxs = Transaction::where('user', Auth::id())
                    ->where('status', '!=', 'new')
                    ->where('status', '!=', 'deleted')
                    
                    ->where('status', '!=', 'canceled')
                    ->where('status', '!=', 'pending')
                    
                    ->where('status', '=', 'approved')
                    
                    ->whereNotIn('tnx_type', ['withdraw'])
                    ->whereNotIn('tnx_type', ['demo'])
                    ->orderBy('created_at', 'DESC')->get();

@endphp

@section('title', 'User Details')

@section('content')
<div class="page-content">
    <div class="container">
        <div class="card content-area">
            <div class="card-innr card-innr-fix">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">User Details <em class="ti ti-angle-right fs-14"></em> <small class="tnx-id">{{ set_id($user->id) }}</small></h4>
                    <div class="d-flex align-items-center guttar-20px">
                        <div class="flex-col d-sm-block d-none">
                            <a href="{{ (url()->previous()) ? url()->previous() : route('admin.users') }}" class="btn btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em>Back</a>
                        </div>
                        <div class="flex-col d-sm-none">
                            <a href="{{route('admin.users')}}" class="btn btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                        <div class="relative d-inline-block">
                            <a href="#" class="btn btn-dark btn-sm btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                            <div class="toggle-class dropdown-content dropdown-content-top-left">
                                <ul class="dropdown-list more-menu-{{$user->id}}">
                                    <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="transactions" class="user-form-action user-action"><em class="fas fa-random"></em>Transactions</a></li>
                                    <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="activities" class="user-form-action user-action"><em class="fas fa-sign-out-alt"></em>Activities</a></li>
                                    <li><a href="javascript:void(0)" data-uid="{{ $user->id }}" data-type="referrals" class="user-form-action user-action"><em class="fas fa-users"></em>Referrals</a></li>
                                    @if (auth()->user()->role == 'admin')
                                    <li><a class="user-email-action" href="#Referrant" data-uid="{{ $user->id }}" data-toggle="modal"><em class="fas fa-address-book"></em>Referrant</a></li>
                                    @endif
                                    @if (auth()->user()->role == 'admin')
                                    <li><a class="user-email-action" href="#EmailUser" data-uid="{{ $user->id }}" data-toggle="modal"><em class="far fa-envelope"></em>Send Email</a></li>
                                    @endif
                                    @if (auth()->user()->role == 'admin')
                                    <li><a class="user-email-action" href="#SMSUser" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openMessageModalAndSetName( {{$user->id}}, '{{$user->name}}' )"><em class="far fa-bell"></em>Send Message</a></li>
                                    @endif
                                            
                                    <li><a class="user-email-action view-messages" href="#pastMessages" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openMessageModalAndSetName2( {{$user->id}}, '{{$user->name}}' )"><em class="fa fa-table"></em>Past Messages</a></li>
                                    
                                    @if($user->id != save_gmeta('site_super_admin')->value)
                                    @if (auth()->user()->role == 'admin')
                                    <li><a class="user-form-action user-action d-none" href="#" data-type="reset_pwd" data-uid="{{ $user->id }}" ><em class="fas fa-shield-alt"></em>Reset Pass</a></li>
                                    @endif
                                    @endif
                                    
                                    <li><a class="user-form-action user-action" href="#questionnaire" data-toggle="modal" data-uid="{{ $user->id }}" ><em class="fas fa-question-circle"></em>Questionnaire</a></li>
                                    
                                    <li><a class="user-email-action" href="#note" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openModalAndSetTransactionId( {{$user->id}}, '{{$user->note}}', '{{$user->name}}' )"><em class="fa fa-book-open"></em>Note</a></li>
                                    
                                    @if(Auth::id() != $user->id && $user->id != save_gmeta('site_super_admin')->value)
                                    
                                    @if($user->status != 'suspend')
                                    @if (auth()->user()->role == 'admin')
                                    <li><a href="#" data-uid="{{ $user->id }}" data-type="suspend_user" class="user-action"><em class="fas fa-ban"></em>Suspend</a></li>
                                    @endif
                                        
                                    @else
                                    @if (auth()->user()->role == 'admin')
                                    <li><a href="#" data-uid="{{ $user->id }}" data-type="active_user" class="user-action"><em class="fas fa-ban"></em>Active</a></li>
                                    @endif
                                    @endif
                                    @endif
                                    
                                    
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="gaps-1-5x"></div>
                <div class="data-details d-md-flex">
                    <div class="fake-class">
                        <span class="data-details-title">Account Balance</span>
                        <span class="data-details-info large">{{ number_format($user->tokenBalance) }}</span>
                    </div>
                    <div class="fake-class">
                        <span class="data-details-title">Contributed</span>
                        <span class="data-details-info large">{{ number_format($user->contributed) }} <small>{{ strtoupper($user->base_currency) }} </small></span>
                    </div>
                    <div class="fake-class">
                        <span class="data-details-title">Equity</span>
                        <span class="data-details-info large">{{ number_format($user->equity) }} <small>{{ strtoupper($user->base_currency) }} </small></span>
                    </div>
                    <div class="status_user fake-class">
                        <span class="data-details-title">User Status</span>
                        <span class="badge badge-{{ __status($user->status, 'status' ) }} ucap">{{ $user->status }}</span>
                    </div>
                    <ul class="data-vr-list">
                        <li><div class="data-state data-state-sm data-state-{{ $user->email_verified_at !== null ? 'approved' : 'pending'}}"></div> Email</li>
                        @php
                        if(isset($user->kyc_info->status)){
                            $user->kyc_info->status = str_replace('rejected', 'canceled',$user->kyc_info->status);
                        }
                        @endphp
                        @php 
                            if(isset($user->kyc_info->status)){
                                $user->kyc_info->status = str_replace('rejected', 'canceled', $user->kyc_info->status); 
                            }
                            $kyc_a_bf = isset($user->kyc_info->id) ? '<a href="'.route('admin.kyc.view', [$user->kyc_info->id, 'kyc_details' ]).'" target="_blank">' : ''; 
                            $kyc_a_af = isset($user->kyc_info->id) ? '</a>' : '';
                        @endphp 
                        @if($user->role != 'admin')
                        @if (auth()->user()->role == 'admin')
                        <li>{!! $kyc_a_bf !!}<div class="data-state data-state-sm data-state-{{ !empty($user->kyc_info) ? $user->kyc_info->status : 'missing' }}"></div>KYC {!! $kyc_a_af !!}</li>
                        @endif
                        @endif
                    </ul>
                </div>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title">User Information</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head">Full Name</div>
                        <div class="data-details-des d-block">{!! $user->name ? $user->name : '&nbsp;' !!}
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_user_btn" value="name_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none user_form name_form" action="{{ route('admin.ajax.users.name_edit') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $user->id ? $user->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des name_value name_value_input" style="width:100%" value="{!! $user->name ? $user->name : '&nbsp;' !!}" name="name">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Email Address</div>
                        <div class="data-details-des email_value d-block">{!! explode_user_for_demo($user->email, auth()->user()->type) !!} 
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_user_btn" value="email_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none user_form email_form" action="{{ route('admin.ajax.users.email_edit') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $user->id ? $user->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des email_value email_value_input" style="width:100%" value="{!! explode_user_for_demo($user->email, auth()->user()->type) !!}" name="email">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Mobile Number</div>
                        <div class="data-details-des">{!! $user->mobile ? $user->mobile : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Date of Birth</div>
                        <div class="data-details-des">{!! $user->dateOfBirth ? _date($user->dateOfBirth) : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Nationality</div>
                        <div class="data-details-des">{!! $user->nationality ? $user->nationality : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Last Login</div>
                        <div class="data-details-des">{{ $user->latestActivity && $user->email_verified_at !== null ? _date($user->latestActivity->created_at) : 'Not logged yet' }}</div>
                    </li>{{-- li --}}
                </ul>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title">More Information</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head">Joining Date</div>
                        <div class="data-details-des">{!! $user->created_at ? _date($user->created_at) : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Referred By</div>
                        <div class="data-details-des">{!! ($user->referral != NULL && !empty($user->referee->name) ? '<span>'.$user->referee->name.' <small>('.set_id($user->referral).')</small></span>' : '<small class="text-light">Join without referral!</small>') !!}</div>
                    </li>{{-- li --}}
                    @if(isset($refered) && $refered && count($refered) > 0)
                    <li>
                        <div class="data-details-head">Total Referred</div>
                        <div class="data-details-des">{!! count($refered).' Contributors' !!}</div>
                    </li>{{-- li --}}
                    @endif
                    <li>
                        <div class="data-details-head">Reg Method</div>
                        <div class="data-details-des">{!! $user->registerMethod ? ucfirst($user->registerMethod) : '&nbsp;' !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Default Curr</div>
                        <div class="data-details-des d-block">{!! $user->base_currency !!}
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_user_btn" value="base_currency_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none user_form base_currency_form" action="{{ route('admin.ajax.users.base_currency_edit') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $user->id ? $user->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des base_currency_value base_currency_value_input" style="width:100%" value="{!! $user->base_currency !!}" name="base_currency">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">2FA Enabled</div>
                        <div class="data-details-des d-block">{!! $user->google2fa==1 ? 'Yes' : 'No' !!}
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_user_btn" value="two_fa_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none user_form two_fa_form" action="{{ route('admin.ajax.users.two_fa_edit') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $user->id ? $user->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des two_fa_value two_fa_value_input" style="width:100%" value="{!! $user->google2fa !!}" name="two_fa">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>{{-- li --}}
                    
                    <li>
                        <div class="data-details-head">Ambassador</div>
                        <div class="data-details-des d-block">{!! $user->ambassador==1 ? 'Yes' : 'No' !!}
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_user_btn" value="ambassador_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none user_form ambassador_form" action="{{ route('admin.ajax.users.ambassador_edit') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $user->id ? $user->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des ambassador_value ambassador_value_input" style="width:100%" value="{!! $user->ambassador !!}" name="ambassador">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>{{-- li --}}
                    
                    @if (auth()->user()->role == 'admin')
                    <li>
                        <div class="data-details-head">VIP User</div>
                        <div class="data-details-des d-block">{!! $user->vip_user==1 ? 'Yes' : 'No' !!}
                            <a href="#" class="ml-3 edit_user_btn" value="vip_user_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                        </div>
                        <form class="d-none user_form vip_user_form" action="{{ route('admin.ajax.users.vip_user_edit') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $user->id ? $user->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des vip_user_value vip_user_value_input" style="width:100%" value="{!! $user->vip_user !!}" name="vip_user">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                    </li>{{-- li --}}
                    @endif
                    
                    
                    <li>
                        <div class="data-details-head">Whitelisting Complete</div>
                        <div class="data-details-des d-block">{!! $user->whitelisting_comptete !!}
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_user_btn" value="whitelisting_comptete_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none user_form whitelisting_comptete_form" action="{{ route('admin.ajax.users.whitelisting_comptete_edit') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $user->id ? $user->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des whitelisting_comptete_value whitelisting_comptete_value_input" style="width:100%" value="{!! $user->whitelisting_comptete !!}" name="whitelisting_comptete">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>{{-- li --}}
                    
                    
                    <li>
                        <div class="data-details-head">Whitelisting Balance</div>
                        <div class="data-details-des d-block">{!! $user->whitelist_balance !!}
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_user_btn" value="whitelisting_balance_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none user_form whitelisting_balance_form" action="{{ route('admin.ajax.users.whitelisting_balance_edit') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $user->id ? $user->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des whitelisting_balance_value whitelisting_balance_value_input" style="width:100%" value="{!! $user->whitelist_balance !!}" name="whitelisting_balance">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>{{-- li --}}
                    
                   
                    
                    @if (auth()->user()->role == 'admin' && $user->role =="admin")
                    <li>
                        <div class="data-details-head">Add Referral Persission for Admins</div>
                        <div class="data-details-des d-block">{!! $user->referral_rights==1 ? 'Yes' : 'No' !!}
                            <a href="#" class="ml-3 edit_user_btn" value="referral_rights_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                        </div>
                        <form class="d-none user_form referral_rights_form" action="{{ route('admin.ajax.users.referral_rights_edit') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $user->id ? $user->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des referral_rights_value referral_rights_value_input" style="width:100%" value="{!! $user->referral_rights !!}" name="referral_rights">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                    </li>{{-- li --}}
                    @endif 
                    
                    <li>
                        <div class="data-details-head">Withdrawal Wallet Address</div>
                        <div class="data-details-des">
                            <span>
                                {!! $user->walletAddress ? $user->walletAddress : '<small class="text-light">Not added yet!</small>' !!} 
                                {!! ($user->walletType) ? "<small>(".ucfirst($user->walletType)." Wallet)</small>" : '' !!}
                            </span>
                        </div>
                    </li>{{-- li --}}
                    
                    @if (auth()->user()->role == 'admin')
                    @if($wallet != false)
                    <li>
                        <div class="data-details-head">RobotBulls Wallet Address</div>
                        <div class="data-details-des d-block">
                            <span> {{ Crypt::decryptString($wallet->wallet_address) }} </span>
                        </div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">RobotBulls Wallet Balance</div>
                        <div class="data-details-des d-block">
                            <span> {{ $wallet->balanceETH }} </span>
                            <span> {{ $wallet->balanceUSDT }} </span>
                            <span> {{ $wallet->balanceUSDC }} </span>
                        </div>
                    </li>{{-- li --}}
                    @endif
                    @endif
                    
                    <li>
                        <div class="data-details-head">Questionnaire Filled</div>
                        <div class="data-details-des">{!! ($user->q1 != NULL && !empty($user->q1) ? '<span> <a href="#questionnaire" data-toggle="modal">Filled</a></span>' : '<small class="text-light">Not Filled!</small>') !!}</div>
                    </li>{{-- li --}}
                    <li>
                        <div class="data-details-head">Note</div>
                        <div class="data-details-des">{!! $user->note !== null ? $user->note : '<small class="text-light">Not logged yet!</small>' !!}
                        
                            <a class="ml-3 edit_user_btn user-email-action" href="#note" data-uid="{{ $user->id }}" data-toggle="modal" onclick="openModalAndSetTransactionId( {{$user->id}}, '{{$user->note}}', '{{$user->name}}' )" style="margin:auto">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            
                            
                            
                        </div>
                    </li>{{-- li --}}
                </ul>
                <div class="gaps-3x"></div>
              
               

                    
                <!--</ul>-->
            </div>{{-- .card-innr --}}
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}

{{-- PWD Email Modal --}}

<div class="modal fade" id="questionnaire" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title" id="note-title">Questionnaire</h3>
                <div class="msg-box"></div>
                
                <ul class="data-details-list">
                    <li><div class="data-details-head" style="width:100%">Question 1: Did you invest in any financial markets before?</div></li>
                    <li><div class="data-details-des" style="border-left:none">{!! ($user->q1 != NULL && !empty($user->q1) ? '<span>'.$user->q1.'</span>' : '<small class="text-light">Not Filled!</small>') !!}</div></li>
                    <li><div class="data-details-head" style="width:100%">Question 2: Are you looking for crypto currency investments?</div></li>
                    <li><div class="data-details-des" style="border-left:none">{!! ($user->q2 != NULL && !empty($user->q2) ? '<span>'.$user->q2.'</span>' : '<small class="text-light">Not Filled!</small>') !!}</div></li>
                    <li><div class="data-details-head" style="width:100%">Question 3: Are you looking for investments shorter than 3 Months?</div></li>
                    <li><div class="data-details-des" style="border-left:none">{!! ($user->q3 != NULL && !empty($user->q3) ? '<span>'.$user->q3.'</span>' : '<small class="text-light">Not Filled!</small>') !!}</div></li>
                    <li><div class="data-details-head" style="width:100%">Question 4: How much are you going to invest in the financial markets this year?</div></li>
                    <li><div class="data-details-des" style="border-left:none">{!! ($user->q4 != NULL && !empty($user->q4) ? '<span>'.$user->q4.'</span>' : '<small class="text-light">Not Filled!</small>') !!}</div></li>
                    <li><div class="data-details-head" style="width:100%">Question 5: Does making money from companies involved in manufacturing military equipment, oil extraction or companies involved in pollution of the atmosphere with heavy chemicals consern you morally?</div></li>
                    <li><div class="data-details-des" style="border-left:none">{!! ($user->q5 != NULL && !empty($user->q5) ? '<span>'.$user->q5.'</span>' : '<small class="text-light">Not Filled!</small>') !!}</div></li>
                    <li><div class="data-details-head" style="width:100%">Question 6: If RobotBulls makes you over 30% return are you going to tell a friend about us?</div></li>
                    <li><div class="data-details-des" style="border-left:none">{!! ($user->q6 != NULL && !empty($user->q6) ? '<span>'.$user->q6.'</span>' : '<small class="text-light">Not Filled!</small>') !!}</div></li>
                </ul>
                
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>    

<div class="modal fade" id="EmailUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-lg">
                <h3 class="popup-title">Send Email to User </h3>
                <div class="msg-box"></div>
                <form id="emailToUser" action="{{ route('admin.ajax.users.email') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="input-item input-with-label">
                        <label class="clear input-item-label">Subject</label>
                        <input type="text" name="subject" class="input-bordered cls " placeholder="Email Subject">
                        <span class="input-note">If blank It's will replace with default from EMail Template</span>
                    </div>
                    <div class="input-item input-with-label">
                        <label class="clear input-item-label">Greeting</label>
                        <input type="text" name="greeting" class="input-bordered cls " placeholder="Email Greeting">
                        <span class="input-note">If blank It's will replace with default from EMail Template</span>
                    </div>
                    <div class="input-item input-with-label">
                        <label class="clear input-item-label">Message</label>
                        <textarea required="required" name="message" class="input-bordered cls input-textarea input-textarea-sm" type="text" placeholder="Write something..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Send</button>
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

<div class="modal fade" id="Referrant">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Add Referrant to User </h3>
                <div class="msg-box"></div>
                <form class="validate-modern" id="referrantToUser" action="{{ route('admin.ajax.users.referrant') }}" method="POST" autocomplete="off">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">User</label>
                                <div class="input-wrap">
                                    <select name="referrant" required="" class="select-block select-bordered" data-dd-class="search-on">
                                        @forelse($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @empty
                                        <option value="">No user found</option>
                                        @endif
                                    </select>
                                    <span class="input-note">Select referrant account.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Referrant</button>
                </form>
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
   
<script>

    document.addEventListener('DOMContentLoaded', function() {
    
    // Get all the edit buttons
    var editButtons = document.querySelectorAll('.edit_user_btn');

    // Loop through each button and attach the corresponding event listener
    editButtons.forEach(function(editButton) {
        // Get the form for the current button
        var form = document.querySelector( '.' + editButton.getAttribute("value") );

        console.log(editButton);
        console.log(form);
        
        // When the edit button is clicked
        editButton.addEventListener('click', function(e) {
            console.log("click");
            e.preventDefault();

            // Display the form (change from d-none to d-flex)
            form.classList.remove('d-none');
            form.classList.add('d-flex');
            editButton.parentElement.classList.remove('d-block');
            editButton.parentElement.classList.add('d-none');
        });

        // Get the cancel button inside the form
        var cancelButton = form.querySelector('.cancel');

        // When the cancel button is clicked
        cancelButton.addEventListener('click', function(e) {
            e.preventDefault();

            // Hide the form (change from d-flex to d-none)
            form.classList.remove('d-flex');
            form.classList.add('d-none');
            editButton.parentElement.classList.remove('d-none');
            editButton.parentElement.classList.add('d-block');
        });
    });
});


    
</script>

@endsection
