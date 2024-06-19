@extends('layouts.user')
@section('title', ucfirst($lang['buycrypto_title']))
@php
($has_sidebar = false)
@endphp

@section('content')
<style>
    .video-wrapper {
        position: relative;
        padding-bottom: 56.25%; /* for 16:9 aspect ratio */
        height: 0;
        overflow: hidden;
        width: 100%; /* Full width on mobile */
/*        margin: 0 auto;  center the video if needed */
    }

    .video-wrapper iframe {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
    }

    /* On screens that are 1024px or wider */
    @media screen and (min-width: 992px) {
        .video-wrapper {
            width: 50%; /* 50% width on desktop */
            padding-bottom: 28.125%; /* Adjusted for 16:9 aspect ratio and 50% width */
        }
    }
</style>
<div class="content-area content-area-mh card user-account-pages page-buy-crypto">
    <div class="card-innr">
        <div class="card-head">
            
            <div class="card-head d-flex justify-content-between align-items-center">
            
            <h2 class="card-title card-title-lg">{{ $lang['buycrypto_title'] }}</h2>
            
            <div class="d-flex align-items-center guttar-20px">
                        <div class="flex-col d-sm-block d-none">
                            <a href="{{ route('user.home') }}" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em>{{$lang['back']}}</a>
                        </div>
                        <div class="flex-col d-sm-none">
                            <a href="{{ route('user.home') }}" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                    </div>      
            </div>
            

        </div>
        <div class="card-text">{!! $lang['buycrypto_text'] !!}</div>


    </div>
</div>



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
