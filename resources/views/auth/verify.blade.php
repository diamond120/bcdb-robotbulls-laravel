@extends('layouts.auth')
@section('title', $lang['verify_email'])
@section('content')

<div class="{{ (gws('theme_auth_layout','default')=='center-dark'||gws('theme_auth_layout', 'default')=='center-light') ? 'page-ath-form' : 'page-ath-text' }}">

    @if (session('resent'))
    <div class="alert alert-success" role="alert">
        {{ $lang['a_fresh_verification_link_has_been_sent_to_your_email'] }}
    </div>
    @endif

    <div class="alert alert-warning text-center">{{ $lang['please_verify_your_email_address'] }}</div>
    @include('layouts.messages')
    <div class="gaps-0-5x"></div>
    {{ $lang['before_proceeding_check_email'] }}
    {{ $lang['if_you_did_not_receive_the_email'] }} 
    <div class="gaps-3x"></div>
    <a class="btn btn-primary" href="{{ route('verify.resend') }}">{{ $lang['resend_email'] }}</a>
    <div class="gaps-1-5x"></div>
    <a class="link link-ucap link-light" href="{{ route('log-out') }}">{{ $lang['sign_out'] }}</a>
    
</div>
@endsection