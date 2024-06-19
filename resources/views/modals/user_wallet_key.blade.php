@php
    $user = Auth::user();

    $wallet_address = DB::table('wallets')
                ->where('user_id', $user->id)
                ->where('currency', "eth")
                ->first();
@endphp

<div class="modal fade" id="get-key-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body">
                <h3 class="popup-title two_fator_verifiation">{{ $lang['get_your_private_key'] }}</h3>
<!--                <form class="validate-modern" method="POST" id="whitelisting-form">-->
                    
                    @csrf
                    <input type="hidden" name="action_type" value="whitelisting_setup">
                    <div class="pdb-1-5x">
<!--                        <p>{{ $lang['get_public_wallet_address'] }}</p>-->
                        <div class="row">
                            <div class="col-12">
                                <div class=" input-with-label">
                                    <label class="input-item-label">{{ $lang['get_public_wallet_address'] }}</label>
                                    <div class="copy-wrap mgb-1-5x mgt-1-5x">
                                        <span class="copy-feedback"></span>
                                        <em class="copy-icon fas fa-link"></em>
                                        <textarea type="text" class="copy-address" disabled="" placeholder="{{Crypt::decryptString(strval($wallet_address->wallet_address))}}"></textarea>
                                        <button class="copy-trigger copy-clipboard" data-clipboard-text="{{Crypt::decryptString(strval($wallet_address->wallet_address))}}"><em class="ti ti-files"></em></button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12" id="checkboxes">
                                <div class="pdb-0-5x">
                                    <div class="input-item text-left">
                                        <input type="checkbox" data-msg-required="{{ __(" You should accept our terms and policy.") }}" class="input-checkbox input-checkbox-md" id="agree-terms" name="agree" required>
                                        <label for="agree-terms">{!! $lang['i_agree_to_the'] . get_page_link('terms', ['target'=>'_blank', 'name' => true, 'status' => true]) . ((get_page_link('terms', ['status' => true]) && get_page_link('policy', ['status' => true])) ? ' '.$lang['and'].' ' : '') . get_page_link('policy', ['target'=>'_blank', 'name' => true, 'status' => true]) !!}.</label>
                                    </div>
                                    <div class="input-item text-left">
                                        <input type="checkbox" data-msg-required="{{ $lang['note_1'] }}" class="input-checkbox input-checkbox-md" id="agree-note1" name="agree" required>
                                        <label for="agree-note1">{{ $lang['note_1'] }}</label>
                                    </div>
                                    <div class="input-item text-left">
                                        <input type="checkbox" data-msg-required="{{ $lang['note_2'] }}" class="input-checkbox input-checkbox-md" id="agree-note2" name="agree" required>
                                        <label for="agree-note2">{{ $lang['note_2'] }}</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-12" style="display: none" id="privatekey_field">
                                <div class="input-with-label">
                                    <label class="input-item-label">{{ $lang['your_private_key'] }}</label>
                                    <div class="copy-wrap mgb-1-5x mgt-1-5x">
                                        <span class="copy-feedback"></span>
                                        <em class="copy-icon fas fa-link"></em>
                                        <textarea type="text" class="copy-address" disabled="" placeholder="{{Crypt::decryptString(strval($wallet_address->privatekey))}}"></textarea>
                                        <button class="copy-trigger copy-clipboard" data-clipboard-text="{{}}"><em class="ti ti-files"></em></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pdb-1-5x">
                                <button type="submit" class="btn btn-primary update_profile" id="get_private_key_button" onclick="get_key_textarea()">{{ $lang['get_your_private_key'] }}</button>
                        </div>
                    </div>
                        
<!--                </form>-->
                
                
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>