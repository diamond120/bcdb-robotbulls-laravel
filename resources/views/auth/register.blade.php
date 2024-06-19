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

            
            
            <div class="input-item" style="display:flex; padding-bottom:30px">
                <style>
                    .select-search {
                      position: relative;
                    }

                    .search-input {
                      width: 100%;
                      box-sizing: border-box;
                    }

                    .country-options {
                      position: absolute;
                      width: 200%;
                      background-color: #fff;
                      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                      max-height: 200px;
                      overflow-y: auto;
                      display: none;
                      z-index: 1000
                    }

                    .country-option {
                      padding: 4px 8px;
                      cursor: pointer;
                    }

                    .country-option:hover {
                      background-color: #f0f0f0;
                    }
                    .input-container {
                      position: relative;
                      display: inline-block;
                    }

                    .flag {
                      position: absolute;
                      width: 20px;
                      height: 20px;
                      left: 15px;
                      top: 50%;
                      transform: translateY(-50%);
                    }

                    .search-input {
                      padding-left: 40px; /* Adjust this value based on the flag size and desired spacing */
                    }

                  </style>
         
                
                <div class="select-search" style="width:30%;display:inline-block;">
                    <span class="flag"></span>
                    <input type="text" class="input-bordered intl-tel-input allow-dropdown search-input" placeholder="Code" data-msg-required="{{ __('Required.') }}" name="code" id="code" style='width:100%;border-right: none;border-top-right-radius: 0;border-bottom-right-radius: 0;' maxlength="4" data-flag="">
                    <div class="country-options"></div>
                </div>
                
                <input type="tel" placeholder="{{ __('Your Phone') }}" data-msg-required="{{ __('Required.') }}" class="your_phone input-bordered{{ $errors->has('mobile') ? ' input-error' : '' }}" name="mobile" value="" required autofocus id="mobile" style='width:70%;border-top-left-radius: 0;border-bottom-left-radius: 0;'>
                
                <input type="text" placeholder="{{ __('Your Phone') }}" data-msg-required="{{ __('Required.') }}" class="your_phone input-bordered{{ $errors->has('phone') ? ' input-error' : '' }}" name="email" value="" required autofocus id="email_p" style='display:none;'>
                
            </div>
            
            
            <script>
    const countries = [
  { name: "France", code: "+33" },
  { name: "Germany", code: "+49" },
  { name: "Luxembourg", code: "+352" },
  { name: "Switzerland", code: "+41" },
  { name: "Algeria", code: "+213" },
  { name: "Austria", code: "+43" },
  { name: "Belgium", code: "+32" },
  { name: "Bulgaria", code: "+359" },
  { name: "Cameroon", code: "+237" },
  { name: "Côte d'Ivoire", code: "+225" },
  { name: "Croatia", code: "+385" },
  { name: "Cyprus", code: "+357" },
  { name: "Czech Republic", code: "+420" },
  { name: "Denmark", code: "+45" },
  { name: "Estonia", code: "+372" },
  { name: "Finland", code: "+358" },
  { name: "French Guiana", code: "+594" },
  { name: "Greece", code: "+30" },
  { name: "Guadeloupe", code: "+590" },
  { name: "Hungary", code: "+36" },
  { name: "Ireland", code: "+353" },
  { name: "Italy", code: "+39" },
  { name: "Latvia", code: "+371" },
  { name: "Liechtenstein", code: "+423" },
  { name: "Lithuania", code: "+370" },
  { name: "Mali", code: "+223" },
  { name: "Malta", code: "+356" },
  { name: "Martinique", code: "+596" },
  { name: "Mayotte", code: "+262" },
  { name: "Monaco", code: "+377" },
  { name: "Morocco", code: "+212" },
  { name: "Netherlands", code: "+31" },
  { name: "Norway", code: "+47" },
  { name: "Poland", code: "+48" },
  { name: "Portugal", code: "+351" },
  { name: "Réunion", code: "+262" },
  { name: "Romania", code: "+40" },
  { name: "Saint-Barthélemy", code: "+590" },
  { name: "Saint-Martin", code: "+590" },
  { name: "Senegal", code: "+221" },
  { name: "Slovakia", code: "+421" },
  { name: "Slovenia", code: "+386" },
  { name: "South Africa", code: "+27" },
  { name: "Spain", code: "+34" },
  { name: "Sweden", code: "+46" },
  { name: "Taiwan", code: "+886" },
  { name: "Turkey", code: "+90" },
  { name: "United Kingdom", code: "+44" },
  { name: "Vatican", code: "+379" },
];
     
    const countryCodeMap = {
      "AT": "Austria",
      "BE": "Belgium",
      "BG": "Bulgaria",
      "HR": "Croatia",
      "CY": "Cyprus",
      "CZ": "Czech Republic",
      "DK": "Denmark",
      "EE": "Estonia",
      "FI": "Finland",
      "FR": "France",
      "DE": "Germany",
      "GR": "Greece",
      "HU": "Hungary",
      "IE": "Ireland",
      "IT": "Italy",
      "LV": "Latvia",
      "LT": "Lithuania",
      "LU": "Luxembourg",
      "MT": "Malta",
      "NL": "Netherlands",
      "NO": "Norway",
      "PL": "Poland",
      "PT": "Portugal",
      "RO": "Romania",
      "SK": "Slovakia",
      "SI": "Slovenia",
      "ES": "Spain",
      "SE": "Sweden",
      "CH": "Switzerland",
      "GB": "United Kingdom",
      "TW": "Taiwan",
      "TR": "Turkey",
      "ZA": "South Africa",
      "DZ": "Algeria",
      "AO": "Angola",
      "MA": "Morocco",
      "CI": "Côte d'Ivoire",
      "CM": "Cameroon",
      "SN": "Senegal",
      "ML": "Mali",
      "GF": "French Guiana",
      "RE": "Réunion",
      "MQ": "Martinique",
      "GP": "Guadeloupe",
      "YT": "Mayotte",
      "MF": "Saint-Martin",
      "BL": "Saint-Barthélemy",
      "LI": "Liechtenstein",
      "MC": "Monaco",
      "VA": "Vatican",
      "EN": "Switzerland",
    };

async function getUserLocation() {
  return new Promise((resolve, reject) => {
    navigator.geolocation.getCurrentPosition(resolve, reject);
  });
}

async function getCountryCodeFromCoordinates(lat, lon) {
  const apiKey = 'YOUR_API_KEY'; // Replace with your OpenCage API key
  const url = `https://api.opencagedata.com/geocode/v1/json?q=${lat}+${lon}&key=${apiKey}`;
  const response = await fetch(url);
  const data = await response.json();
  return data.results[0].components['ISO_3166-1_alpha-2'];
}
              

function getBrowserLanguageCountry() {

    const langCountryMap = {
      "de": "Germany",
      "nl": "Netherlands",
      "bg": "Bulgaria",
      "hr": "Croatia",
      "el": "Greece",
      "cs": "Czech Republic",
      "da": "Denmark",
      "et": "Estonia",
      "fi": "Finland",
      "fr": "France",
      "ga": "Ireland",
      "it": "Italy",
      "lv": "Latvia",
      "lt": "Lithuania",
      "lb": "Luxembourg",
      "mt": "Malta",
      "no": "Norway",
      "pl": "Poland",
      "pt": "Portugal",
      "ro": "Romania",
      "sk": "Slovakia",
      "sl": "Slovenia",
      "es": "Spain",
      "sv": "Sweden",
      "en": "United Kingdom",
      "tw": "Taiwan",
      "tr": "Turkey",
      "af": "South Africa",
      "ar": "Algeria",
    };
    
  var userLang = (navigator.language || navigator.userLanguage).substring(0, 2).toLowerCase();
  const mappedCountry = langCountryMap[userLang];
  return countries.find(country => country.name === mappedCountry);
}

var userLang = (navigator.language || navigator.userLanguage).substring(0, 2).toLowerCase();
console.log("userLang"+userLang)
function countryCodeToFlag(countryCode) {
  const OFFSET = 127397;
  const code = countryCode;
  const cc = code
    .toUpperCase()
    .replace(/./g, (char) => String.fromCodePoint(char.charCodeAt(0) + OFFSET));
  return cc;
}
                
const searchInput = document.querySelector('.search-input');
const countryOptions = document.querySelector('.country-options');
const flagElement = document.querySelector('.flag');

let countryCode;

function renderCountryOptions(search = '') {
  const showAllOptions = search === '' || search === '+';
  const filteredCountries = countries.filter(country =>
    showAllOptions || country.name.toLowerCase().includes(search.toLowerCase())
  );

  countryOptions.innerHTML = filteredCountries.map(country => `
    <div class="country-option" data-code="${country.code}">
      ${country.name} (${country.code})
    </div>
  `).join('');

  countryOptions.style.display = filteredCountries.length ? 'block' : 'none';
}

const userCountry = getBrowserLanguageCountry();

//if (userCountry) {
//  searchInput.value = userCountry.code;
//  countryCode = userCountry.code;
//  flagElement.textContent = countryCodeToFlag(userLang);
//  if(userLang == "en") {
    searchInput.value = "+41";
    countryCode = "+41";
    flagElement.textContent = countryCodeToFlag("ch"); 
//  }
//} else {
//  searchInput.value = '+41';
//  countryCode = '+41';
//  flagElement.textContent = countryCodeToFlag('ch'); 
//}

searchInput.addEventListener('input', (e) => {
  renderCountryOptions(e.target.value);
});

countryOptions.addEventListener('click', (e) => {
  if (e.target.classList.contains('country-option')) {
    countryCode = e.target.dataset.code;
    const countryName = e.target.textContent.trim().match(/^(.+?)\s\(\+\d+\)$/)[1];
    const languageCode = Object.keys(countryCodeMap).find(
      (key) => countryCodeMap[key] === countryName
    );
    console.log('Selected country code:', countryCode);
    console.log('Selected language code:', languageCode);
    console.log('Selected country name:', countryName);
    searchInput.value = countryCode;
    flagElement.textContent = countryCodeToFlag(languageCode);
    countryOptions.style.display = 'none';
  }
});

document.addEventListener('click', (e) => {
  if (!e.target.closest('.select-search')) {
    countryOptions.style.display = 'none';
  }
});

searchInput.addEventListener('input', () => {
  countryCode = searchInput.value;
  const matchingCountry = countries.find(country => country.code === countryCode);
  if (matchingCountry) {
    const languageCode = Object.keys(countryCodeMap).find(
      (key) => countryCodeMap[key] === matchingCountry.name
    );
    flagElement.textContent = countryCodeToFlag(languageCode);
  } else {
    flagElement.textContent = '';
  }
});




  </script>

            <button type="button" class="btn btn-primary btn-block continue" id="send-verification-button">{{$lang['continue']}}</button>

        </div>

        <div class="auth_page" id="auth_page2" style="display: none">

            <div class="input-item d-none" id="name_container">
                <input type="text" placeholder="{{$lang['full_name']}}" class="input-bordered{{ $errors->has('name') ? ' input-error' : '' }}" name="name" value="{{ old('name') }}" minlength="3" data-msg-required="{{$lang['required']}}" data-msg-minlength="{{ __($lang['full_name'], ['num' => 3]) }}" required>
            </div>
            
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
            <button type="submit" class="btn btn-primary btn-block sign_up2">{{ ( application_installed(true) && ($check_users == 0) ) ? __($lang['complete_installation']) : __($lang['sign_up']) }}</button>

            <div class="d-flex justify-content-between"><a href="#" id="auth_back_link"><strong class="back">{{$lang['back']}}</strong></a> <a href="#" id="auth_new_code"><strong class="send_code_again">{{$lang['send_code_again']}}</strong></a><p id="counter_code" style="display:none"><strong class="counter_code" id="counter_code_time">60s</strong></p></div>

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
                                  console.log(typeof msg);
                                    if(msg == "nouser") {
                                        console.log("test");
                                        $("#name_container").removeClass("d-none");
                                    } 
                                  if(msg == "user") {
                                        $("#name_container").remove();    
                                    }
                                  console.log('sent');
                              });
//                            }
                          
                        });
                        
                        
                        $("#auth_new_code").click(function() {
                            var code = $("#code").val();
                            var tel = parseInt($("#mobile").val());
                            var phone = code+Number(tel);
                            
                            console.log("phone" + phone);
                            
//                            if(phone.indexOf("235") === -1 && phone.indexOf("972") === -1) { 
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
//                            }
                        });
                        
                        
                        $("#auth_back_link").click(function() {
                            $(".auth_page").css("display", "none");
                            $("#auth_page1").css("display", "block");
                        });
            
                        $('#mobile').bind('input', function() { 
                            $('#email_p').val($("#code").val() + Number($("#mobile").val()));
                        });
            
                        $('#code').bind('input', function() { 
                            $('#email_p').val($("#code").val() + Number($("#mobile").val()));
                        });
                        
                        
                    </script>

        </div>


    </form>

    @if(application_installed(true) && ($check_users > 0) && Schema::hasTable('settings'))
    @if (
    (get_setting('site_api_fb_id', env('FB_CLIENT_ID', '')) != '' && get_setting('site_api_fb_secret', env('FB_CLIENT_SECRET', '')) != '') ||
    (get_setting('site_api_google_id', env('GOOGLE_CLIENT_ID', '')) != '' && get_setting('site_api_google_secret', env('GOOGLE_CLIENT_SECRET', '')) != '')
    )
<!--
    <div class="sap-text"><span class="or_sign_in_with">{{$lang['or_signup_with']}}</span></div>
    <ul class="google_auth row guttar-20px guttar-vr-20px">
        <li class="col"><a href="{{ route('social.login', 'google') }}" class="btn btn-outline btn-dark btn-google btn-block"><em class="fab fa-google"></em><span>{{__('Google')}}</span></a></li>
    </ul>
-->
    @endif

    <div class="gaps-2x"></div>
<!--
    <div class="form-note">
        <span class="already_acount">{{$lang['already_have_an_account']}}</span> <a href="{{ route('login') }}"> <strong class="sign_in2">{{__($lang['sign_in_instead'])}}</strong></a>
    </div>
-->
    @endif
</div>

<script>
    function validatePhone() {

//        if (document.getElementById('verification').value == document.cookie.match('(^|;)\\s*' + 'verification' + '\\s*=\\s*([^;]+)')?.pop() || '') {
//            console.log("verified");
//            return true;
//        } else {
//            console.log("not checked");
//
//            // $("#disablerobot").addClass("show");
//            // $("<div class='modal-backdrop fade show'></div>").appendTo("body");
//
//            $("#verification").css("border-color", "red");
//
//            return false;
//        }

    }

</script>

@endsection
