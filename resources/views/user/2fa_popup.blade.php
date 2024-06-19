@php
$has_sidebar = false;
@endphp
@extends('layouts.user')
@section('title', $lang['user_dashboard'])

@push('header')
<link rel="apple-touch-icon" href="{{ asset('assets/css/2fa_popup.css') }}">
@endpush

@section('content')
@if( (!isset($_COOKIE["questionnaire"])) && $user->google2fa != 1)
<div class="content-area card">
    <div class="card-innr">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">

                <div class="card kyc-form-steps mx-lg-4">

                    <div class="card-head">
                        <div class="card-innr card-head d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0 questionnaire_title">{{$lang['welcome_to_robotbulls'] }}</h4>
                            <div class="d-flex align-items-center guttar-20px">
                                <div class="flex-col d-sm-block d-none">
                                    <a href="{{ route('user.home') }}" class="btn btn-light btn-sm btn-auto btn-primary btn-close-questionnaire"><em class="fas fa-close mr-3"></em><span class="q_close">{{$lang['close'] }}</span></a>
                                </div>
                                <div class="flex-col d-sm-none">
                                    <a href="{{ route('user.home') }}" class="btn btn-light btn-icon btn-sm btn-primary btn-close-questionnaire"><em class="fas fa-close"></em></a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="page-wrappper" id="questionaire_page">

<!--                        <form action="{{ route('user.submit.questionnaire') }}" id="contact-form" method="get">-->


                            <section class="contact_section contact_section_q1" id="contact_section_q1">
                                <div class="card-innr" style="height:auto;">
<!--                                <div class="popup-body">-->
                                    
                                    <form class="validate-modern" action="{{ route('user.ajax.account.update') }}" method="POST" id="nio-user-2fa">
                                        @csrf
                                        <input type="hidden" name="action_type" value="google2fa_setup">
                                        <div class="pdb-1-5x">
                                            <p class="h4 pb-3">{{$lang['for_your_security_setup_2fa'] }}</p>
                                            <p class="2fa_step1"><strong>{{ $lang['step1'] }}</strong> {{ $lang['install_this_app_from'] }} <a target="_blank" href="https://play.google.com/store/apps/details?id=com.google.android.apps.authenticator2">{{ __('Google Play') }} </a> {{ $lang['store_or'] }} <a target="_blank" href="https://itunes.apple.com/us/app/google-authenticator/id388497605">{{ __('App Store') }}</a>.</p>
                                            <p class="2fa_step2"><strong>{{ $lang['step2'] }}</strong> {{ $lang['scan_the_below_qr_code'] }}</p>
                                            <p><strong class="2fa_add_account">{{ $lang['manually_add_account'] }}</strong><br><span class="2fa_aount_name">{{ $lang['account_name'] }}</span> <strong class="text-head">{{ site_info() }}</strong> <br> <span class="key">{{ $lang['key'] }}</span> <strong class="text-head">{{ $google2fa_secret }}</strong></p>
                                            <div class="row g2fa-box">
                                                <div class="col-md-4">
                                                    <img class="img-thumbnail" src="{{ route('public.qrgen', ['text' => $google2fa]) }}" alt="">
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="input-item">
                                                        <label for="google2fa_code 2fa_enter_google_auth_code">{{ $lang['enter_google_authenticator_code'] }}</label>
                                                        <input id="google2fa_code" type="number" class="input-bordered 2fa_enter_the_code_to_verify" name="google2fa_code" placeholder="{{ __('Enter the Code to verify') }}">
                                                    </div>
                                                    <input type="hidden" name="google2fa_secret" value="{{ $google2fa_secret }}">
                                                    <input name="google2fa" type="hidden" value="1">
                                                    <button type="submit" class="btn btn-primary confirm_2fa">{{ $lang['confirm_2fa'] }}</button>
                                                </div>
                                            </div>
                                        </div>
<!--                                    </div>-->
                                    </form>
                                </div>

                            </section>
                            
<!--                        </form>-->

                    </div>

                </div>

            </div>

        </div>
    </div>
</div>
@endif
@endsection

@push('footer')
<script src="{{ asset('assets/js/2fa_popup.js') }}"></script>
@endpush
