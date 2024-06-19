@extends('layouts.user')
@section('title', ucfirst($page->title))
@php
($has_sidebar = false)
@endphp

@section('content')
<div class="content-area content-area-mh card user-account-pages page-{{ $page->slug }}">
    <div class="card-innr">
        <div class="card-head">
            
            <div class="card-head d-flex justify-content-between align-items-center">
            
            <h2 class="card-title card-title-lg">{{ $lang['referral'] }}</h2>
            
            <div class="d-flex align-items-center guttar-20px">
                        <div class="flex-col d-sm-block d-none">
                            <a href="{{ route('user.home') }}" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em>{{$lang['back']}}</a>
                        </div>
                        <div class="flex-col d-sm-none">
                            <a href="{{ route('user.home') }}" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                    </div>      
            </div>
            
            @if($page->meta_description!=null)
            <p class="large">{{ replace_shortcode($page->meta_description) }}</p>
            @endif
        </div>
        <div class="card-text">
            <p>{{ $lang['referral_text'] }}</p>
            <p>{{ $lang['referral_text2'] }}</p>
            <p>{{ $lang['referral_text3'] }}</p>
        </div>

        <div class="gaps-1x"></div>
        <div class="referral-form">
            <h4 class="card-title card-title-sm">{{ $lang['refferal_url'] }}</h4>
            <div class="copy-wrap mgb-1-5x mgt-1-5x">
                <span class="copy-feedback"></span>
                <em class="copy-icon fas fa-link"></em>
                <input type="text" class="copy-address" value="{{ route('public.referral').'?ref='.set_id(auth()->id()) }}" disabled>
                <button class="copy-trigger copy-clipboard" data-clipboard-text="{{ route('public.referral').'?ref='.set_id(auth()->id()) }}"><em class="ti ti-files"></em></button>
            </div>
            <p class="text-light mgmt-1x"><em><small>{{ $lang['use_link_to_reffer_friends'] }}</small></em></p>
        </div>
        <div class="sap sap-gap"></div>
        <div class="card-head">
            <h4 class="card-title card-title-sm">{{ $lang['referral_lists'] }}</h4>
        </div>
        <table class="data-table dt-init refferal-table" data-items="10">
            <thead>
                <tr class="data-item data-head">
                    <th class="data-col refferal-name"><span>{{ $lang['user_name'] }}</span></th>
                    <th class="data-col refferal-tokens"><span>{{ $lang['earn_token'] }}</span></th>
                    <th class="data-col refferal-date"><span>{{ $lang['register_date'] }}</span></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reffered as $refer)
                <tr class="data-item">
                    <td class="data-col refferal-name">{{ $refer->name }}</td>
                    <td class="data-col refferal-tokens">{{ (referral_bonus($refer->id)) ? referral_bonus($refer->id) : __('~') }}</td>
                    <td class="data-col refferal-date">{{ _date($refer->created_at) }}</td>
                </tr>
                @empty
                <tr class="data-item">
                    <td class="data-col">{{ $lang['no_one_joined_yet'] }}</td>
                    <td class="data-col"></td>
                    <td class="data-col"></td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>



@endsection
