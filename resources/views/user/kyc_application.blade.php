@extends('layouts.user')
@section('title', $lang['begin_id_verification'])
@php
$has_sidebar = false;
@endphp

@section('content')
<div class="page-header page-header-kyc">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-xl-7 text-center">
            <h2 class="page-title">{{ $lang['begin_your_id_verification'] }}</h2>
            <p class="large">{{ $lang['verify_your_identity'] }}</p>
        </div>
    </div>
</div>
<div class="row justify-content-center">
    <div class="col-lg-10 col-xl-9">

@include('layouts.messages')

<div class="kyc-form-steps card mx-lg-4">
    <input type="hidden" id="file_uploads" value="{{ route('ajax.kyc.file.upload') }}" />
    <form class="validate-modern" action="{{ route('user.ajax.kyc.submit') }}" method="POST" id="kyc_submit">
        @csrf
        @include('layouts.kyc-form')
    </form>
</div>


    </div>
</div>

@endsection
