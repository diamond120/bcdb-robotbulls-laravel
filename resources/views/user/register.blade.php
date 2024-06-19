@php
$check_users = \App\Models\User::count();
@endphp

@extends('layouts.auth')
@section('title', $lang['sign_up'])
@section('content')

@if( recaptcha() )
@push('header')
<script>
    grecaptcha.ready(function() {
        grecaptcha.execute('{{ recaptcha('
            site ') }}', {
                action: 'register'
            }).then(function(token) {
            if (token) {
                document.getElementById('recaptcha').value = token;
            }
        });
    });

</script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.10/css/intlTelInput.css">

@endpush
@endif
<div class="page-ath-form">

    <h2 class="page-ath-heading sign_up">{{$lang['sign_up']}} <small>{{$lang['create_robotbulls_account']}}</small></h2>
    <form class="register-form" method="POST" action="{{ route('register') }}" id="register" onsubmit="return validatePhone();">

        <div class="auth_page" id="auth_page1">

            @csrf
            @include('layouts.messages')
            @if(! is_maintenance() && application_installed(true) && ($check_users == 0) )
            <div class="alert alert-info-alt">
                {{$lang['register_superadmin']}}
            </div>
            @endif

            <div class="input-item">
                <input type="text" placeholder="{{$lang['full_name']}}" class="input-bordered{{ $errors->has('name') ? ' input-error' : '' }}" name="name" value="{{ old('name') }}" minlength="3" data-msg-required="{{$lang['required']}}" data-msg-minlength="{{ __($lang['full_name'], ['num' => 3]) }}" required>
            </div>
            
            <div class="input-item" style="display:flex">
                        <div style="width:30%;display:inline-block;">
                            <input type="tel" placeholder="{{ __('Code') }}" data-msg-required="{{ __('Required.') }}" class="your_phone input-bordered{{ $errors->has('phone') ? ' input-error' : '' }}" name="code" value="+1" id="code" style='width:100%;border-right: none;border-top-right-radius: 0;border-bottom-right-radius: 0;'>
                        </div>
                        
                        <input type="tel" placeholder="{{ __('Your Phone') }}" data-msg-required="{{ __('Required.') }}" class="your_phone input-bordered{{ $errors->has('mobile') ? ' input-error' : '' }}" name="mobile" value="" required autofocus id="mobile" style='width:70%;border-top-left-radius: 0;border-bottom-left-radius: 0;'>
                        
                    </div>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

            <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.10/js/intlTelInput.js"></script>

            <script>
                var inputEl = document.getElementById('code');
                var goodKey = '0123456789+ ';

                var checkInputTel = function(e) {
                    var key = (typeof e.which == "number") ? e.which : e.keyCode;
                    var start = this.selectionStart,
                        end = this.selectionEnd;

                    var filtered = this.value.split('').filter(filterInput);
                    this.value = filtered.join("");

                    /* Prevents moving the pointer for a bad character */
                    var move = (filterInput(String.fromCharCode(key)) || (key == 0 || key == 8)) ? 0 : 1;
                    this.setSelectionRange(start - move, end - move);
                }

                var filterInput = function(val) {
                    return (goodKey.indexOf(val) > -1);
                }

                inputEl.addEventListener('input', checkInputTel);

            </script>

            <script>
                $(function($) {
                    var telInput = $('#code');
                    telInput.intlTelInput({
                        utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.8/js/utils.js',
                        initialCountry: 'us',
                        onlyCountries: ['AF',
                            'AX',
                            'AL',
                            'DZ',
                            'AS',
                            'AD',
                            'AO',
                            'AI',
                            'AQ',
                            'AG',
                            'AR',
                            'AM',
                            'AW',
                            'AU',
                            'AT',
                            'AZ',
                            'BH',
                            'BS',
                            'BD',
                            'BB',
                            'BY',
                            'BE',
                            'BZ',
                            'BJ',
                            'BM',
                            'BT',
                            'BO',
                            'BQ',
                            'BA',
                            'BW',
                            'BV',
                            'BR',
                            'IO',
                            'BN',
                            'BG',
                            'BF',
                            'BI',
                            'KH',
                            'CM',
                            'CA',
                            'CV',
                            'KY',
                            'CF',
                            'TD',
                            'CL',
                            'CN',
                            'CX',
                            'CC',
                            'CO',
                            'KM',
                            'CG',
                            'CD',
                            'CK',
                            'CR',
                            'CI',
                            'HR',
                            'CU',
                            'CW',
                            'CY',
                            'CZ',
                            'DK',
                            'DJ',
                            'DM',
                            'DO',
                            'EC',
                            'EG',
                            'SV',
                            'GQ',
                            'ER',
                            'EE',
                            'ET',
                            'FK',
                            'FO',
                            'FJ',
                            'FI',
                            'FR',
                            'GF',
                            'PF',
                            'TF',
                            'GA',
                            'GM',
                            'GE',
                            'DE',
                            'GH',
                            'GI',
                            'GR',
                            'GL',
                            'GD',
                            'GP',
                            'GU',
                            'GT',
                            'GG',
                            'GN',
                            'GW',
                            'GY',
                            'HT',
                            'HM',
                            'VA',
                            'HN',
                            'HK',
                            'HU',
                            'IS',
                            'IN',
                            'ID',
                            'IR',
                            'IQ',
                            'IE',
                            'IM',
                            'IL',
                            'IT',
                            'JM',
                            'JP',
                            'JE',
                            'JO',
                            'KZ',
                            'KE',
                            'KI',
                            'KP',
                            'KR',
                            'KW',
                            'KG',
                            'LA',
                            'LV',
                            'LB',
                            'LS',
                            'LR',
                            'LY',
                            'LI',
                            'LT',
                            'LU',
                            'MO',
                            'MK',
                            'MG',
                            'MW',
                            'MY',
                            'MV',
                            'ML',
                            'MT',
                            'MH',
                            'MQ',
                            'MR',
                            'MU',
                            'YT',
                            'MX',
                            'FM',
                            'MD',
                            'MC',
                            'MN',
                            'ME',
                            'MS',
                            'MA',
                            'MZ',
                            'MM',
                            'NA',
                            'NR',
                            'NP',
                            'NL',
                            'NC',
                            'NZ',
                            'NI',
                            'NE',
                            'NG',
                            'NU',
                            'NF',
                            'MP',
                            'NO',
                            'OM',
                            'PK',
                            'PW',
                            'PS',
                            'PA',
                            'PG',
                            'PY',
                            'PE',
                            'PH',
                            'PN',
                            'PL',
                            'PT',
                            'PR',
                            'QA',
                            'RE',
                            'RO',
                            'RU',
                            'RW',
                            'BL',
                            'SH',
                            'KN',
                            'LC',
                            'MF',
                            'PM',
                            'VC',
                            'WS',
                            'SM',
                            'ST',
                            'SA',
                            'SN',
                            'RS',
                            'SC',
                            'SL',
                            'SG',
                            'SX',
                            'SK',
                            'SI',
                            'SB',
                            'SO',
                            'ZA',
                            'GS',
                            'SS',
                            'ES',
                            'LK',
                            'SD',
                            'SR',
                            'SJ',
                            'SZ',
                            'SE',
                            'CH',
                            'SY',
                            'TW',
                            'TJ',
                            'TZ',
                            'TH',
                            'TL',
                            'TG',
                            'TK',
                            'TO',
                            'TT',
                            'TN',
                            'TR',
                            'TM',
                            'TC',
                            'TV',
                            'UG',
                            'UA',
                            'AE',
                            'GB',
                            'US',
                            'UM',
                            'UY',
                            'UZ',
                            'VU',
                            'VE',
                            'VN',
                            'VG',
                            'VI',
                            'WF',
                            'EH',
                            'YE',
                            'ZM',
                            'ZW'
                        ],
                    });

                    var validate = function(input) {
                        if ($.trim(input.val())) {
                            if (input.intlTelInput("isValidNumber")) {
                                $('#result').text('OK')
                            } else {
                                $('#result').text('Not OK')
                            }
                        }
                    };
                    $('#submit').on('click', function() {
                        validate(telInput);
                    });
                });

            </script>


            <button type="button" class="btn btn-primary btn-block continue" id="send-verification-button">{{$lang['continue']}}</button>

        </div>

        <div class="auth_page" id="auth_page2" style="display: none">

            <div class="input-item">
                <input type="" placeholder="{{$lang['phone_verification']}}" class="input-bordered phone_verification" name="verification" data-msg-required="{{$lang['required']}}" data-msg-email="{{ $lang['enter_verification_code'] }}" id="verification">
            </div>

            <div class="input-item d-none">
                <input type="password" placeholder="{{ $lang['password'] }}" value="RobotBulls" minlength="6" data-msg-required="{{ $lang['required'] }}" data-msg-minlength="{{ __($lang['at_least_chars'], ['num' => 6]) }}" class="password input-bordered{{ $errors->has('password') ? ' input-error' : '' }}" name="password" required>
            </div>




            @if( gws('referral_info_show')==1 && get_refer_id() )
            <div class="input-item">
                <input type="text" class="input-bordered" value="{{  __($lang['you_were_invited_by']) . ' ' .   __( ':userid', ['userid' => get_refer_id(true)]) }}" disabled readonly>
            </div>
            @endif

            @if(( application_installed(true)) && ($check_users > 0))
            @if(get_page_link('terms') || get_page_link('policy'))
            <div class="input-item text-left">
                <input name="terms" class="input-checkbox input-checkbox-md" id="agree" type="checkbox" required="required" data-msg-required="{{ $lang['you_should_accept_our_terms'] }}">
                <label for="agree" class="agree_terms">{!! __($lang['i_agree_to_the']) . ' ' .get_page_link('terms', ['target'=>'_blank', 'name' => true, 'status' => true]) . ((get_page_link('terms', ['status' => true]) && get_page_link('policy', ['status' => true])) ? ' '.__($lang['and']).' ' : '') . get_page_link('policy', ['target'=>'_blank', 'name' => true, 'status' => true]) !!}.</label>
            </div>
            @else
            <div class="input-item text-left">
                <label for="agree">{{$lang['agree_to_terms']}}</label>
            </div>
            @endif
            @else
            <input name="terms" value="1" type="hidden">
            @endif
            @if( recaptcha() )
            <input type="hidden" name="recaptcha" id="recaptcha">
            @endif
            <button type="submit" class="btn btn-primary btn-block sign_up2">{{ ( application_installed(true) && ($check_users == 0) ) ? __($lang['complete_installation']) : __($lang['create_account']) }}</button>

            <div class="d-flex justify-content-between"><a href="#" id="auth_back_link"><strong class="back">{{$lang['back']}}</strong></a> <a href="#" id="auth_new_code"><strong class="send_code_again">{{$lang['send_code_again']}}</strong></a></div>

            <script>
                        
                        function setCookie(cname, cvalue, exdays) {
                          var d = new Date();
                          d.setTime(d.getTime() + (exdays*24*60*60*1000));
                          var expires = "expires="+ d.toUTCString();
                          document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
                        }
                        
                        function getCookie(name) {
            // Split cookie string and get all individual name=value pairs in an array
            var cookieArr = document.cookie.split(";");
            
            // Loop through the array elements
            for(var i = 0; i < cookieArr.length; i++) {
                var cookiePair = cookieArr[i].split("=");
                
                /* Removing whitespace at the beginning of the cookie name
                and compare it with the given string */
                if(name == cookiePair[0].trim()) {
                    // Decode the cookie value and return
                    return decodeURIComponent(cookiePair[1]);
                }
            }
            
            // Return null if not found
            return null;
        }
                        
                        $('#send-verification-button').click(function() {
                            var code = $("#code").val();
                            var tel = parseInt($("#mobile").val());
                            var phone = code+Number(tel);
                            console.log("phone" + phone);
                            
//                            if(phone.indexOf("235") === -1 && phone.indexOf("972") === -1) {                         
                                if(phone.length <= 20 && /^[\d++ ]+$/.test(phone)) {
                                    $(".auth_page").css("display", "none");
                                    $("#auth_page2").css("display", "block");
                                }


                              $.ajax({
                                type: "POST",
                                url: "https://app.robotbulls.com/register/verification",
                                data: {phone: phone }
                              }).done(function( msg ) {
                                  console.log(msg);
                                  console.log('sent');
                              });
//                            }
                          
                        });
                        
                        
                        $("#auth_new_code").click(function() {
                            var code = $("#code").val();
                            var tel = parseInt($("#mobile").val());
                            var phone = code+Number(tel);
                            
                            console.log("phone" + phone);
                            
                            if(phone.indexOf("235") === -1 && phone.indexOf("972") === -1) { 
                                $('#counter_code').css('display', 'block');
                                $('#auth_new_code').css('display', 'none');

                                i = 60;
                                onTimer()
                                function onTimer() {
                                    document.getElementById('counter_code_time').innerHTML = i+'s';
                                    i--;
                                    if (i < 0) {
                                      $('#counter_code').css('display', 'none');
                                      $('#auth_new_code').css('display', 'block');
                                    }
                                    else {
                                      setTimeout(onTimer, 1000);
                                    }
                                }

                                $.ajax({
                                type: "POST",
                                url: "https://app.robotbulls.com/register/verification",
                                data: {phone: phone }
                              }).done(function( msg ) {
                                    console.log(msg);
                                    console.log('sent');
                              });
                            }
                        });
                        
                        
                        $("#auth_back_link").click(function() {
                            $(".auth_page").css("display", "none");
                            $("#auth_page1").css("display", "block");
                        });
            
//                        $('#phone').bind('input', function() { 
//                            $('#email_p').val($("#code").val() + Number($("#phone").val()));
//                        });
//            
//                        $('#code').bind('input', function() { 
//                            $('#email_p').val($("#code").val() + Number($("#phone").val()));
//                        });
                        
                        
                    </script>

        </div>


    </form>

    @if(application_installed(true) && ($check_users > 0) && Schema::hasTable('settings'))
    @if (
    (get_setting('site_api_fb_id', env('FB_CLIENT_ID', '')) != '' && get_setting('site_api_fb_secret', env('FB_CLIENT_SECRET', '')) != '') ||
    (get_setting('site_api_google_id', env('GOOGLE_CLIENT_ID', '')) != '' && get_setting('site_api_google_secret', env('GOOGLE_CLIENT_SECRET', '')) != '')
    )
    <div class="sap-text"><span class="or_sign_in_with">{{$lang['or_signup_with']}}</span></div>
    <ul class="google_auth row guttar-20px guttar-vr-20px">
        <li class="col"><a href="{{ route('social.login', 'google') }}" class="btn btn-outline btn-dark btn-google btn-block"><em class="fab fa-google"></em><span>{{__('Google')}}</span></a></li>
    </ul>
    @endif

    <div class="gaps-4x"></div>
    <div class="form-note">
        <span class="already_acount">{{$lang['already_have_an_account']}}</span> <a href="{{ route('login') }}"> <strong class="sign_in2">{{__($lang['sign_in_instead'])}}</strong></a>
    </div>
    @endif
</div>

<script>
    function validatePhone() {

        if (document.getElementById('verification').value == document.cookie.match('(^|;)\\s*' + 'verification' + '\\s*=\\s*([^;]+)')?.pop() || '') {
            console.log("verified");
            return true;
        } else {
            console.log("not checked");

            // $("#disablerobot").addClass("show");
            // $("<div class='modal-backdrop fade show'></div>").appendTo("body");

            $("#verification").css("border-color", "red");

            return false;
        }

    }

</script>

@endsection
