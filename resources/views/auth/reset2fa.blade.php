@extends('layouts.auth')
@section('title',  $lang['reset_2fa_authentication'] )
@section('content')
<div class="page-ath-form">
    <h2 class="page-ath-heading pb-0">{{ $lang['reset_2fa'] }}</h2>
    <p>{{ $lang['hello'] }} <strong>{{ $user->name }}</strong>, <br>{{ $lang['please_enter_your_account_password_to_reset_2fa'] }}</p>
    <div class="gaps-1x"></div>
    <form method="POST" action="{{ route('auth.2fa.reset') }}" class="reset-2fa-form validate-modern">
        @csrf
        <input type="hidden" name="token" value="{{ $token }}"> 
        @include('layouts.messages')
        <div class="input-item">
            <input type="password" placeholder="{{ $lang['enter_password'] }}" name="password" id="password" class="input-bordered" minlength="6" data-msg-required="{{ $lang['required'] }}" data-msg-minlength="{{ __('At least :num chars.', ['num' => 6]) }}" required>
        </div>

        <div class="gaps"></div>
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <button type="submit" class="btn btn-primary">{{ $lang['reset_2FA'] }}</button>
            </div>
        </div>

    </form>
</div>
@endsection
