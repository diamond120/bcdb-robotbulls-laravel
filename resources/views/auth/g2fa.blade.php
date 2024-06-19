@extends('layouts.auth')
@section('title', $lang['two_factor_verification'])
@section('content')

<div class="page-ath-form">
    <h2 class="page-ath-heading">{{ $lang['2fa_authentication'] }}</h2>
    <p>{{ $lang['hello'] }} <strong>{{ auth()->user()->name }}</strong>,
    <br>{{ $lang['enter_the_verification_code_generated_by_2fa'] }}</p>
    @if(session()->has('2fa_notice'))
    <div class="alert alert-warning">{{ session('2fa_notice') }}</div>
    @endif
    <form id="active" action="{{ url()->current() }}" method="POST" autocomplete="off" class="validate-modern">
        @csrf
        <div class="input-item">
            <input name="one_time_password" type="text" required="required" data-msg-required="{{ $lang['required'] }}" data-msg-maxlength="{{ __( $lang['maximum_chars'], ['num' => 6]) }}" placeholder="{{ $lang['enter_your_authentication_code'] }}" class="input-bordered" autofocus="" maxlength="6">
        </div>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-primary btn-block">{{ $lang['sign_in'] }}</button>
            </div>
        </div>
    </form>
    <div class="gaps-2x"></div>
    <div class="form-note">
        {{ $lang['2fa_app_lost_or_uninstalled'] }}
    </div>
</div>

@endsection
