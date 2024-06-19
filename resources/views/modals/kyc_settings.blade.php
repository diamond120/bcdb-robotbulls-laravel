@php
$wallet_opt = field_value_text('kyc_wallet_opt' , 'wallet_opt');
is_array($wallet_opt) ? true : $wallet_opt = array();
$custom = field_value_text('kyc_wallet_custom');
is_array($custom) ? true : $custom = array();
@endphp

<div class="modal fade" id="kyc-settings" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" ><em class="ti ti-close"></em></a>
            <div id="base-body" class="popup-body-full">
                {{-- Content Start --}}
                <div class="popup-header">
                    <h3 class="popup-title">{{$lang['kyc_form_setting']}}</h3>
                    <p>{{$lang['kyc_form_setting_text']}}</p>
                </div>
                <form action="{{ route('admin.ajax.kyc.update') }}" method="POST" id="kyc_settings">
                    @csrf
                    <input type="hidden" name="req_type" value="update_kyc_settings">
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right">
                            <h5 class="kyc-option-title">{{$lang['general_settings']}}</h5>
                        </div>
                        <div class="kyc-option-content toggle-content">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['application_hide_from_userend']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-opt-hide" name="kyc_opt_hide" value="1" {{ field_value('kyc_opt_hide') == 1 ? ' checked' : '' }} type="checkbox">
                                        <label for="kyc-opt-hide">{{$lang['enable']}}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['accessible_without_sign_up']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-public" name="kyc_public" {{ field_value('kyc_public') == 1 ? ' checked' : '' }} type="checkbox">
                                        <label for="kyc-public">{{$lang['enable']}}</label>
                                    </div>
                                    <div class="col-12">
                                        <div class="gaps-1x"></div>
                                        <p class="small">{{$lang['public_kyc_application_url']}} <a href="{{ route('public.kyc') }}">{{ route('public.kyc') }}</a></p>
                                    </div>
                                </div>
                            </div>

                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['email_verified_before_submit']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-before-email" name="kyc_before_email" value="1" {{ field_value('kyc_before_email') == 1 ? ' checked' : '' }} type="checkbox">
                                        <label for="kyc-before-email">{{$lang['enable']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['kyc_before_token_purchase']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-before-toekn" name="token_before_kyc" value="1" {{ token('before_kyc') == 1 ? 'checked' : '' }} type="checkbox">
                                        <label for="kyc-before-toekn">{{$lang['enable']}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>{{-- .kyc-option --}}
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right">
                            <h5 class="kyc-option-title">{{$lang['personal_form_options']}}</h5>
                        </div>
                        <div class="kyc-option-content toggle-content">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['first_name']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="fname-show" value="show"name="kyc_firstname[]"{{ field_value('kyc_firstname', 'show' ) ? ' checked' : '' }} type="checkbox" disabled>
                                        <label for="fname-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="fname-req" value="req" name="kyc_firstname[]"{{ field_value('kyc_firstname', 'req' ) ? ' checked' : '' }} type="checkbox" disabled>
                                        <label for="fname-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['last_name']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="lname-show" value="show" name="kyc_lastname[]"{{ field_value('kyc_lastname', 'show' ) ? ' checked' : '' }} type="checkbox">
                                        <label for="lname-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="lname-req" value="req" name="kyc_lastname[]"{{ field_value('kyc_lastname', 'req' ) ? ' checked' : '' }} type="checkbox">
                                        <label for="lname-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['email_address']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="kyc-email-show" value="show" name="kyc_email[]"{{ field_value('kyc_email', 'show' ) ? ' checked' : '' }} type="checkbox" disabled="disabled">
                                        <label for="kyc-email-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="kyc-email-req" value="req" name="kyc_email[]"{{ field_value('kyc_email', 'req' ) ? ' checked' : '' }} type="checkbox" disabled="disabled">
                                        <label for="kyc-email-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['phone_number']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="phone-show" value="show" name="kyc_phone[]"{{ field_value('kyc_phone', 'show' ) ? ' checked' : '' }} type="checkbox">
                                        <label for="phone-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="phone-req" value="req" name="kyc_phone[]"{{ field_value('kyc_phone', 'req' ) ? ' checked' : '' }} type="checkbox">
                                        <label for="phone-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['date_of_birth']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="dob-show" value="show" name="kyc_dob[]" {{ field_value('kyc_dob', 'show' ) ? 'checked' : '' }} type="checkbox">
                                        <label for="dob-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="dob-req" value="req" name="kyc_dob[]" {{ field_value('kyc_dob', 'req' ) ? 'checked' : '' }} type="checkbox">
                                        <label for="dob-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['gender']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="gender-show" value="show" name="kyc_gender[]" {{ field_value('kyc_gender', 'show' ) ? 'checked' : '' }} type="checkbox">
                                        <label for="gender-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="gender-req" value="req" name="kyc_gender[]" {{ field_value('kyc_gender', 'req' ) ? 'checked' : '' }} type="checkbox">
                                        <label for="gender-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['country']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="country-show" value="show" name="kyc_country[]" {{ field_value('kyc_country', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="country-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="country-req" value="req" name="kyc_country[]" {{ field_value('kyc_country', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="country-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['address_line1']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="address-l1-show" value="show" name="kyc_address1[]"{{ field_value('kyc_address1', 'show') ? ' checked' : '' }} type="checkbox">
                                        <label for="address-l1-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="address-l1-req" value="req" name="kyc_address1[]"{{ field_value('kyc_address1', 'req') ? ' checked' : '' }} type="checkbox">
                                        <label for="address-l1-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['address_line2']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="address-l2-show" value="show" name="kyc_address2[]"{{ field_value('kyc_address2', 'show') ? ' checked' : '' }} type="checkbox">
                                        <label for="address-l2-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="address-l2-req" value="req" name="kyc_address2[]"{{ field_value('kyc_address2', 'req') ? ' checked' : '' }} type="checkbox">
                                        <label for="address-l2-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['city']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="city-show" value="show" name="kyc_city[]" {{ field_value('kyc_city', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="city-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="city-req" value="req" name="kyc_city[]" {{ field_value('kyc_city', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="city-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['state']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="state-show" value="show" name="kyc_state[]" {{ field_value('kyc_state', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="state-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="state-req" value="req" name="kyc_state[]" {{ field_value('kyc_state', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="state-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['zip_code']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="zip-code-show" value="show" name="kyc_zip[]" {{ field_value('kyc_zip', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="zip-code-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="zip-code-req" value="req" name="kyc_zip[]" {{ field_value('kyc_zip', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="zip-code-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['telegram']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="telegram-show" value="show" name="kyc_telegram[]" {{ field_value('kyc_telegram', 'show') ? 'checked' : '' }} type="checkbox">
                                        <label for="telegram-show">{{$lang['show']}}</label>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-checkbox input-checkbox-sm" id="telegram-req" value="req" name="kyc_telegram[]" {{ field_value('kyc_telegram', 'req') ? 'checked' : '' }} type="checkbox">
                                        <label for="telegram-req">{{$lang['required']}}</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>{{-- .kyc-option --}}
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right">
                            <h5 class="kyc-option-title">{{$lang['document_verification_options']}}</h5>
                        </div>
                        <div class="kyc-option-content toggle-content">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['verify_by_passport']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="passport-enable"  name="kyc_document_passport" {{ get_setting('kyc_document_passport') ? 'checked' : '' }} type="checkbox">
                                        <label for="passport-enable">{{$lang['enable']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['verify_by_national_card']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="nid-enable" name="kyc_document_nidcard" {{ get_setting('kyc_document_nidcard') ? 'checked' : '' }} type="checkbox">
                                        <label for="nid-enable">{{$lang['enable']}}</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['verify_by_driving_license']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <input class="input-switch input-switch-sm" id="dlicense-enable" name="kyc_document_driving" {{ get_setting('kyc_document_driving') ? 'checked' : '' }} type="checkbox">
                                        <label for="dlicense-enable">{{$lang['enable']}}</label>
                                    </div>
                                </div>
                            </div>
                            <span class="input-note">
                                {{$lang['only_one_way_required']}}
                            </span>
                            <div class="gaps-1x"></div>
                        </div>
                    </div>{{-- .kyc-option --}}
                    <div class="kyc-option popup-body-innr">
                        <div class="kyc-option-head toggle-content-tigger collapse-icon-right">
                            <h5 class="kyc-option-title">{{$lang['pay_wallet_option']}}</h5>
                        </div>
                        <div class="kyc-option-content toggle-content">
                            <div class="input-item">
                                <div class="row align-items-center">
                                    <div class="col-sm-6">
                                        <h6 class="kyc-option-subtitle">{{$lang['wallet_address']}}</h6>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="input-wrap">
                                            <input class="input-switch input-switch-sm" id="wallet-show" value="show" name="kyc_wallet[]" {{ field_value('kyc_wallet', 'show') ? 'checked' : '' }} type="checkbox">
                                            <label for="wallet-show">{{$lang['show']}}</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-3 col-6">
                                        <div class="input-wrap">
                                            <input class="input-checkbox input-checkbox-sm" id="wallet-req" value="req" name="kyc_wallet[]" {{ field_value('kyc_wallet', 'req') ? 'checked' : '' }} type="checkbox">
                                            <label for="wallet-req">{{$lang['required']}}</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-item input-with-label">
                                        <h6 class="kyc-option-subtitle">{{$lang['supported_wallet']}}</h6>
                                        <div class="input-wrap">
                                            <select  name="kyc_wallet_opt[]" class="select select-block select-bordered" value="" data-placeholder="Select Options" multiple="multiple">
                                                @foreach($available_wallets as $name => $wallet)
                                                <option {{in_array($name, $wallet_opt )? 'selected' : ''}} value="{{ $name }}">{{ $wallet }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="input-item input-with-label">
                                        <h6 class="kyc-option-subtitle">{{$lang['note_for_wallet']}}</h6>
                                        <div class="input-wrap">
                                            <input class="input-bordered" type="text" name="kyc_wallet_note" value="{{ get_setting('kyc_wallet_note')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row guttar-15px align-items-center">
                                <div class="col-sm-6">
                                    <div class="input-item input-with-label">
                                        <h6 class="kyc-option-subtitle">{{$lang['own_custom_wallet']}}</h6>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-6">
                                   <div class="input-item input-with-label">
                                        <div class="input-wrap">
                                            <input class="input-bordered" placeholder="wallet-name" type="text" name = "kyc_wallet_custom[]" value="{{ (!empty($custom['cw_name']) ? $custom['cw_name'] : '') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-3 col-6">
                                    <div class="input-item input-with-label">
                                        <div class="input-wrap">
                                            <input class="input-bordered" placeholder="{{$lang['wallet_label']}}" type="text" name="kyc_wallet_custom[]" value="{{ (!empty($custom['cw_text']) ? $custom['cw_text'] : '') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="popup-footer">
                        <button class="btn btn-primary save-disabled" type="submit" disabled="disabled">{{$lang['update']}}</button>
                    </div>
                </form>
                {{-- Content End --}}
            </div>
        </div>
    </div>
</div>
