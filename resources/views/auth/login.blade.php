@extends('layouts.auth')
@section('title', __('Sign-in'))
@section('content')
@if( recaptcha() )
@push('header')
<script>
    grecaptcha.ready(function () { grecaptcha.execute('{{ recaptcha('site') }}', { action: 'login' }).then(function (token) { if(token) { document.getElementById('recaptcha').value = token; } }); });
</script>
@endpush
@endif
<div class="page-ath-form">
    <h2 class="page-ath-heading sign_in">{{ __('Sign in') }}<small>{{ __('with your') }} {{ site_info('name') }} {{ __('Account') }}</small></h2>
    
    <ul class="nav nav-tabs nav-tabs-line" role="tablist">
            <li class="nav-item">
                <a class="nav-link active phone" data-toggle="tab" href="#phone-login">Phone</a>
            </li>
        </ul>
        
        <div class="tab-content" id="profile-details">
            
            <div class="tab-pane fade show active" id="phone-login">
                <form class="login-form validate validate-modern"
                action="{{ (is_maintenance() ? route('admin.login') : route('login')) }}" method="POST">
                    
                    <div class="auth_page" id="auth_page1">
                    
                    @csrf
                    @include('layouts.messages')
                    <div class="input-item" style="display:flex">
                        <div style="width:30%;display:inline-block;">
                            <input type="tel" placeholder="{{ __('Code') }}" data-msg-required="{{ __('Required.') }}" class="your_phone input-bordered{{ $errors->has('phone') ? ' input-error' : '' }}" value="+1" id="code" style='width:100%;border-right: none;border-top-right-radius: 0;border-bottom-right-radius: 0;'>
                        </div>
                        
                        <input type="tel" placeholder="{{ __('Your Phone') }}" data-msg-required="{{ __('Required.') }}" class="your_phone input-bordered{{ $errors->has('phone') ? ' input-error' : '' }}" value="" required autofocus id="phone" style='width:70%;border-top-left-radius: 0;border-bottom-left-radius: 0;'>
                        
                        <input type="text" placeholder="{{ __('Your Phone') }}" data-msg-required="{{ __('Required.') }}" class="your_phone input-bordered{{ $errors->has('phone') ? ' input-error' : '' }}" name="email" value="" required autofocus id="email_p" style='display:none;'>
                        
                    </div>
                
                    <button type="button" class="btn btn-primary btn-block continue" id="send-verification-button">{{ __('Continue') }}</button>
                
                </div>
                
                    <div class="auth_page" id="auth_page2" style="display: none">
                
                <div class="input-item">
                    <input type="" placeholder="Phone Verification" class="input-bordered phone_verification" name="verification" data-msg-required="{{ __('Required.') }}" data-msg-email="{{ __('Enter verification code.') }}" id="verification">
                        
           
                </div>
        
                    <div class="input-item d-none">
                                <input type="password" placeholder="{{ __('Password') }}" value="RobotBulls" minlength="6"
                                    data-msg-required="{{ __('Required.') }}"
                                    data-msg-minlength="{{ __('At least :num chars.', ['num' => 6]) }}"
                                    class="password input-bordered{{ $errors->has('password') ? ' input-error' : '' }}" name="password" id='password_p' required>
                </div>
                    
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
                    
                    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.10/js/intlTelInput.js"></script>
                    
                    <script>
                    
                    var inputEl = document.getElementById('phone');
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
                    
                    <script>$(function($) {
            	var telInput = $('#code');
            	telInput.intlTelInput({
            		utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.8/js/utils.js',
            		initialCountry: 'us',
            		onlyCountries: [ 'AF',
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
            'ZW' ],
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
            });</script>
        
                    
                    @if(! is_maintenance())
                <div class="d-flex justify-content-between align-items-center">
                    <div class="input-item text-left">
                        <input class="input-checkbox input-checkbox-md" type="checkbox" name="remember" id="remember-me"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember-me" class="remember_me">{{ __('Remember Me') }}</label>
                    </div>
                    <div>
                        <a href="{{ route('password.request') }}" class="forgot_password">{{ __('Forgot password?')}}</a>
                        <div class="gaps-2x"></div>
                    </div>
                </div>
                @endif
                @if( recaptcha() )
                <input type="hidden" name="recaptcha" id="recaptcha">
                @endif
                <button type="submit" class="btn btn-primary btn-block sign_in2">{{__('Sign In')}}</button>
            
                <div class="d-flex justify-content-between"><a href="#" id="auth_back_link"><strong class="back">Back</strong></a> <a href="#" id="auth_new_code"><strong class="send_code_again">Send code again</strong></a><p id="counter_code" style="display:none"><strong class="counter_code" id="counter_code_time">60s</strong></p></div>
                
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
                            var tel = parseInt($("#phone").val());
                            var phone = code+Number(tel);
                            console.log("phone" + phone);
//                            if(phone.indexOf("235") === -1 && phone.indexOf("972") === -1) {                           
                                if(phone.length <= 20 && /^[\d++ ]+$/.test(phone)) {
                                    $(".auth_page").css("display", "none");
                                    $("#auth_page2").css("display", "block");
                                }

    //                            var phone_cookie = setCookie("phone", phone, 1);
                                // console.log(phone_cookie);
                                // console.log(document.getElementById("phone").value);

                                // console.log("all cookies = " + document.cookie);
                                // console.log("phone cookie = " + document.cookie["phone"]);
                                // console.log("phone cookie = " + document.cookie.match('(^|;)\\s*' + 'verification' + '\\s*=\\s*([^;]+)')?.pop() || '');


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
                            var tel = parseInt($("#phone").val());
                            var phone = code+Number(tel);
                            if(phone.indexOf("235") === -1 && phone.indexOf("972") === -1) { 
                                console.log("phone" + phone);

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
            
                        $('#phone').bind('input', function() { 
                            $('#email_p').val($("#code").val() + Number($("#phone").val()));
                        });
            
                        $('#code').bind('input', function() { 
                            $('#email_p').val($("#code").val() + Number($("#phone").val()));
                        });
                        
                        
                    </script>
            
            </div>
                    
            </form>
            </div>
            
            <div class="tab-pane fade" id="email-login">
                <form class="login-form validate validate-modern"
                action="{{ (is_maintenance() ? route('admin.login_email') : route('login')) }}" method="POST">
                @csrf
                @include('layouts.messages')
                <div class="input-item">
                    <input type="email" placeholder="{{ __('Your Email') }}" data-msg-required="{{ __('Required.') }}"
                        class="your_email input-bordered{{ $errors->has('email') ? ' input-error' : '' }}" name="email"
                        value="{{ old('email') }}" required autofocus>
                </div>
                <div class="input-item">
                    <input type="password" placeholder="{{ __('Password') }}" minlength="6"
                        data-msg-required="{{ __('Required.') }}"
                        data-msg-minlength="{{ __('At least :num chars.', ['num' => 6]) }}"
                        class="password input-bordered{{ $errors->has('password') ? ' input-error' : '' }}" name="password" required>
                </div>
                @if(! is_maintenance())
                <div class="d-flex justify-content-between align-items-center">
                    <div class="input-item text-left">
                        <input class="input-checkbox input-checkbox-md" type="checkbox" name="remember" id="remember-me"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember-me" class="remember_me">{{ __('Remember Me') }}</label>
                    </div>
                    <div>
                        <a href="{{ route('password.request') }}" class='forgot_password'>{{ __('Forgot password?')}}</a>
                        <div class="gaps-2x"></div>
                    </div>
                </div>
                @endif
                @if( recaptcha() )
                <input type="hidden" name="recaptcha" id="recaptcha">
                @endif
                <button type="submit" class="btn btn-primary btn-block sign_in2">{{__('Sign In')}}</button>
            </form>
            </div>
            
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
        
        <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/12.1.10/js/intlTelInput.js"></script>
        
        <script>
        
        var inputEl = document.getElementById('phone');
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
        
        <script>$(function($) {
	var telInput = $('#code');
	telInput.intlTelInput({
		utilsScript: 'https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/8.4.8/js/utils.js',
		initialCountry: 'us',
		onlyCountries: [ 'AF',
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
'ZW' ],
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
});</script>
            
    </div>
    
    @if(! is_maintenance())
    @if(Schema::hasTable('settings'))
    @if (
    (get_setting('site_api_fb_id', env('FB_CLIENT_ID', '')) != '' && get_setting('site_api_fb_secret',
    env('FB_CLIENT_SECRET', '')) != '') ||
    (get_setting('site_api_google_id', env('GOOGLE_CLIENT_ID', '')) != '' && get_setting('site_api_google_secret',
    env('GOOGLE_CLIENT_SECRET', '')) != '')
    )
    <div class="sap-text or_sign_in_with"><span>{{__('Or Sign in with')}}</span></div>
    <ul class="google_auth row guttar-20px guttar-vr-20px">
        @if(get_setting('site_api_fb_id', env('FB_CLIENT_ID', '')) != '' && get_setting('site_api_fb_secret',
        env('FB_CLIENT_SECRET', '')) != '')
        <li class="col"><a href="{{ route('social.login', 'facebook') }}"
                class="btn btn-outline btn-dark btn-facebook btn-block"><em
                    class="fab fa-facebook-f"></em><span>{{__('Facebook')}}</span></a></li>
        @endif
        @if(get_setting('site_api_google_id', env('GOOGLE_CLIENT_ID', '')) != '' &&
        get_setting('site_api_google_secret', env('GOOGLE_CLIENT_SECRET', '')) != '')
        <li class="col"><a href="{{ route('social.login', 'google') }}"
                class="btn btn-outline btn-dark btn-google btn-block"><em
                    class="fab fa-google"></em><span>{{__('Google')}}</span></a></li>
        @endif
    </ul>
    @endif
    @endif

    <div class="gaps-4x"></div>
    <div class="form-note">
        <span class="dont_have_account">{{__('Don’t have an account?')}} </span> <a href="{{ route('register') }}"> <strong class="sign_up_here">{{__('Sign up here')}}</strong></a>
    </div>
    @endif
</div>

@endsection