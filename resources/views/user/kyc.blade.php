@extends('layouts.user')
@section('title', $lang['kyc_verification'])
@php
$has_sidebar = false;

$kyc_title = ($user_kyc !== NULL && isset($_GET['thank_you'])) ? __($lang['begin_your_id_verification']) : __($lang['kyc_verification']);
$kyc_desc = ($user_kyc !== NULL && isset($_GET['thank_you'])) ? __($lang['verify_your_identity']) : __($lang['kyc_desc']);
@endphp

@section('content')
<div class="page-header page-header-kyc">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7 text-center">
            <h2 class="page-title kyc_verifiation">{{ $kyc_title }}</h2>
            <p class="large kyc_verifiation_under_text">{{ $kyc_desc }}</p>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">
        <div class="content-area card user-account-pages page-kyc">
            <div class="card-innr">
                @include('layouts.messages')
                <div class="kyc-status card mx-lg-4">
                    <div class="card-innr">
                        {{-- IF NOT SUBMITED --}}
                        @if($user_kyc == NULL)
                        <div class="status status-empty">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                            </div>
                            <span class="status-text text-dark kyc_small_title1">{{__($lang['not_submitted_necessary_documents'])}}{{ (token('before_kyc')=='1') ? __($lang['investments_verify_identity']) : ''}}</span>
                            <p class="px-md-5 kyc_small_title1_under_text">{{__($lang['submit_form_question_support'])}}</p>
                            <a href="{{ route('user.kyc.application') }}?state=new" class="btn btn-primary kyc_small_title1_cta">{{__($lang['click_here_to_complete_your_kyc'])}}</a>
                        </div> 
                        @endif
                        {{-- IF SUBMITED @Thanks --}}
                        @if($user_kyc !== NULL && isset($_GET['thank_you']))
                        <div class="status status-thank px-md-5">
                            <div class="status-icon">
                                <em class="ti ti-check"></em>
                            </div>
                            <span class="status-text large text-dark kyc_small_title1_submitted">{{__($lang['you_have_completed_the_process_of_kyc'])}}</span>
                            <p class="px-md-5 kyc_small_title1_submitted_under_text">{{__($lang['waiting_for_identity_verification'])}}</p>
                            <a href="{{ route('user.account') }}" class="btn btn-primary kyc_small_title1_submitted_cta">{{__($lang['back_to_profile'])}}</a>
                        </div>
                        @endif

                        {{-- IF PENDING --}}
                        @if($user_kyc !== NULL && $user_kyc->status == 'pending' && !isset($_GET['thank_you']))
                        <div class="status status-process">
                            <div class="status-icon">
                                <em class="ti ti-infinite"></em>
                            </div>
                            <span class="status-text text-dark kyc_small_title1_pending">{{__($lang['application_verification_under_process'])}}</span>
                            <p class="px-md-5 kyc_small_title1_pending_under_text">{{__($lang['still_working_on_identity_verification'])}}</p>
                        </div>
                        @endif

                        {{-- IF REJECTED/MISSING --}}
                        @if($user_kyc !== NULL && ($user_kyc->status == 'missing' || $user_kyc->status == 'rejected') && !isset($_GET['thank_you']))
                        <div class="status status{{ ($user_kyc->status == 'missing') ? '-warnning' : '-canceled' }}">
                            <div class="status-icon">
                                <em class="ti ti-na"></em>
                            </div>
                            <span class="status-text text-dark kyc_small_title1_rejected">
                                {{ $user_kyc->status == 'missing' ? __($lang['some_information_missing']) : __($lang['application_rejected']) }}
                            </span>
                            <p class="px-md-5 kyc_small_title1_rejected_under_text">{{__($lang['verification_information_incorrect_missing'])}}</p>
                            <a href="{{ route('user.kyc.application') }}?state={{ $user_kyc->status == 'missing' ? 'missing' : 'resubmit' }}" class="btn btn-primary kyc_small_title1_rejected_cta">{{$lang['submit_again']}}</a>
                        </div>
                        @endif

                        {{-- IF VERIFIED --}}
                        @if($user_kyc !== NULL && $user_kyc->status == 'approved' && !isset($_GET['thank_you']))
                        <div class="status status-verified">
                            <div class="status-icon">
                                <em class="ti ti-files"></em>
                            </div>
                            <span class="status-text text-dark kyc_small_title1_approved">{{__($lang['identity_verification_successful'])}}</span>
                            <p class="px-md-5 kyc_small_title1_approved_under_text">{{__($lang['team_members_verified_identity'])}}</p>
                            <div class="gaps-2x"></div>
                            <a href="{{ route('user.token') }}" class="btn btn-primary kyc_small_title1_approved_cta">{{__($lang['invest_now'])}}</a>
                        </div>
                        @endif

                    </div>
                </div>{{-- .card --}}
            </div>
        </div>
        {!! UserPanel::kyc_footer_info($lang) !!}
    </div>
</div>
@endsection
