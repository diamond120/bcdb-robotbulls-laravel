@extends('layouts.user')
@section('title', $lang['user_account'])

@section('content')

@push('sidebar')
<div class="aside sidebar-right col-lg-4">
    <div class="d-block">
        {!! UserPanel::add_wallet_alert($lang) !!}
    </div>
    {!! UserPanel::token_sales_ad($lang) !!}
    {!! UserPanel::user_kyc_info($lang) !!}
    {!! UserPanel::whitelist_wallet($lang, $user) !!}
</div>{{-- .col --}}
@endpush

@include('layouts.messages')
<div class="content-area card">
    <div class="card-innr">
        <div class="card-head">
            <h4 class="card-title profile_title">{{ $lang['profile_details'] }}</h4>
        </div>
        <ul class="nav nav-tabs nav-tabs-line" role="tablist">
            <li class="nav-item">
                <div class="toggle-tigger nav-link active personal_details" data-toggle="tab" href="#personal-data">{{$lang['personal_data']}}</div>
            </li>
        </ul>{{-- .nav-tabs-line --}}
        <div class="tab-content" id="profile-details">
            <div class="tab-pane fade show active" id="personal-data">
                <form class="validate-modern" action="{{ route('user.ajax.account.update') }}" method="POST" id="nio-user-personal" autocomplete="off">
                    @csrf
                    <input type="hidden" name="action_type" value="personal_data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="full-name" class="input-item-label full_name">{{$lang['full_name']}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered full_name" type="text" id="full-name" name="name" placeholder="{{ $lang['enter_full_name'] }}" minlength="3" value="{{ $user->name }}">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="email-address" class="input-item-label email_address">{{$lang['your_login']}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered email_address" type="text" id="email-address" name="phone" placeholder="{{ $lang['enter_phone_number'] }}" value="{{ $user->email }}">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>
                        <div class="col-md-6 d-none">
                            <div class="input-item input-with-label">
                                <label for="mobile-number" class="input-item-label mobile_number">{{$lang['mobile_number']}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered enter_mobile_number" type="text" id="mobile-number" name="mobile" placeholder="{{ $lang['enter_mobile_number'] }}" value="{{ $user->mobile }}">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="date-of-birth" class="input-item-label date_of_birth">{{$lang['date_of_birth']}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered date-picker-dob" type="text" id="date-of-birth" name="dateOfBirth" placeholder="mm/dd/yyyy" value="{{ ($user->dateOfBirth != NULL ? _date($user->dateOfBirth, 'm/d/Y') : '') }}">
                                </div>
                            </div>{{-- .input-item --}}
                        </div>{{-- .col --}}
                        <div class="col-md-6">
                            <div class="input-item input-with-label">
                                <label for="nationality" class="input-item-label nationality">{{$lang['nationality']}}</label>
                                <div class="input-wrap">
                                    <select class="select-bordered select-block" name="nationality" id="nationality" data-dd-class="search-on">
                                        <option value="" class="select_country">{{$lang['select_country']}}</option>
                                        @foreach($countries as $country)
                                        <option {{$user->nationality == $country ? 'selected ' : ''}}value="{{ $country }}">{{ $country }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>{{-- .input-item --}}
                        </div>{{-- .col --}}

                    </div>{{-- .row --}}
                    <div class="gaps-1x"></div>{{-- 10px gap --}}
                    <div class="d-sm-flex justify-content-between align-items-center">
                        <button type="submit" class="btn btn-primary update_profile">{{$lang['update_profile']}}</button>
                        <div class="gaps-2x d-sm-none"></div>
                    </div>
                </form>{{-- form --}}

            </div>{{-- .tab-pane --}}
        </div>{{-- .tab-content --}}
    </div>{{-- .card-innr --}}
</div>{{-- .card --}}
<div class="content-area card">
    <div class="card-innr">
        <div class="card-head">
            <h4 class="card-title two_fator_verifiation">{{$lang['two_factor_verification']}}</h4>
        </div>
        <p class="two_fator_verifiation_under_text">{{$lang['two_factor_verification_text']}}</p>
        <div class="d-sm-flex justify-content-between align-items-center pdt-1-5x">
            <span class="text-light ucap d-inline-flex align-items-center"><span class="mb-0"><small class="2fa_current_status">{{ __($lang['current_status']) }}</small></span> <span class="badge badge-{{ $user->google2fa == 1 ? 'info' : 'disabled' }} ml-2">{{ $user->google2fa == 1 ? __($lang['enabled']) : __($lang['disabled']) }}</span></span>
            <div class="gaps-2x d-sm-none"></div>
            <button type="button" data-toggle="modal" data-target="#g2fa-modal" class="update_profile order-sm-first btn btn-{{ $user->google2fa == 1 ? 'warning' : 'primary' }}">{{ ($user->google2fa != 1) ? __($lang['enable_2fa']) : __($lang['disable_2fa']) }}</button>
        </div>
    </div>{{-- .card-innr --}}
</div>

<div class="content-area card">
    <div class="card-innr">
        <div class="card-head">
            <h4 class="card-title two_fator_verifiation">{{$lang['contact_us']}}</h4>
        </div>
        <p class="two_fator_verifiation_under_text">{{$lang['contact_us_text']}}</p>
        <div class="d-sm-flex justify-content-between align-items-center pdt-1-5x">
            <button type="button" data-toggle="modal" data-target="#contact-modal" class="update_profile order-sm-first btn btn-primary" onclick="show_messages()">{{$lang['message_us']}}</button>
        </div>
    </div>{{-- .card-innr --}}
</div>

@endsection


@push('footer')
{{-- Modal Medium --}}
<div class="modal fade" id="g2fa-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body">
                <h3 class="popup-title two_fator_verifiation">{{ ($user->google2fa != 1) ? __($lang['enable']) : __($lang['disable']) }} {{ $lang['2fa_authentication'] }}</h3>
                <form class="validate-modern" action="{{ route('user.ajax.account.update') }}" method="POST" id="nio-user-2fa">
                    @csrf
                    <input type="hidden" name="action_type" value="google2fa_setup">
                    @if($user->google2fa != 1)
                    <div class="pdb-1-5x">
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
                        <div class="gaps-2x"></div>
                        <p class="text-danger 2fa_note"><strong>{{ $lang['note'] }}</strong> {{ $lang['lost_uninstall_authenticator_app'] }}</p>
                    </div>
                    @else
                    <div class="pdb-1-5x">
                        <div class="input-item">
                            <label for="google2fa_code 2fa_enter_google_auth_code">{{ $lang['enter_google_authenticator_code'] }}</label>
                            <input id="google2fa_code" type="number" class="input-bordered 2fa_enter_the_code_to_verify" name="google2fa_code" placeholder="{{ __(" Enter the Code to verify") }}">
                        </div>
                        <input name="google2fa" type="hidden" value="0">
                        <button type="submit" class="btn btn-primary update_profile">{{ $lang['disable_2fa'] }}</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>

{{-- Modal End --}}
<script type="text/javascript">
    (function($) {
        var $nio_user_2fa = $('#nio-user-2fa');
        if ($nio_user_2fa.length > 0) {
            ajax_form_submit($nio_user_2fa);
        }
    })(jQuery);

</script>
<script src="{{ asset('assets/js/account.js') }}"></script>
@endpush
