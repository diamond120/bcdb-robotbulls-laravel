@extends('layouts.auth')
@section('title', $lang['or_sign_up_with'])
@section('content')

<div class="page-ath-form">

    <h2 class="page-ath-heading pb-0">{{ $lang['sign_up'] }}</h2>
    <div class="gaps-1x"></div>
    <p class="lead">{{ $notice }}</p>
    <form class="register-form validate validate-modern" method="POST" action="{{ route('social.register') }}" id="register">
        @csrf
        @include('layouts.messages')
        <div class="input-item">
            <input type="text" class="input-bordered{{ $errors->has('name') ? ' input-error' : '' }}" name="name" value="{{ $user->getName() }}" data-msg-required="{{ $lang['required'] }}" required>
        </div>
        <div class="input-item">
            <input type="email" class="input-bordered{{ $errors->has('email') ? ' input-error' : '' }}" name="email" value="{{ $user->getEmail() }}" data-msg-required="{{ $lang['required'] }}" data-msg-email="{{ $lang['enter_valid_email'] }}" required>
        </div>
		<input type="hidden" name="social" value="{{ $social }}">
        <input type="hidden" name="social_id" value="{{ $user->getId() }}">
        @if(get_page_link('terms') || get_page_link('policy'))
        <div class="input-item text-left">
            <input name="terms" class="input-checkbox input-checkbox-md" id="agree" type="checkbox" required="required" data-msg-required="{{ $lang['you_should_accept_our_terms'] }}">
            <label for="agree">{!! __( $lang['i_agree_to_the'] ) . ' ' .get_page_link('terms', ['target'=>'_blank', 'name' => true, 'status' => true]) . ((get_page_link('terms', ['status' => true]) && get_page_link('policy', ['status' => true])) ? ' '.__( $lang['and'] ).' ' : '') . get_page_link('policy', ['target'=>'_blank', 'name' => true, 'status' => true]) !!}.</label>
        </div>
        @else
        <div class="input-item text-left">
           <label for="agree">{{ $lang['agree_to_terms'] }}</label>
        </div>
        @endif
        <button type="submit" class="btn btn-primary btn-block">{{ $lang['create_account'] }}</button>
        <div class="gaps-1x"></div>
        <a href="{{ route('login') }}" class="btn-link">{{ $lang['cancel_signup'] }}</a>
    </form>
</div>
@endsection
