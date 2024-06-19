@php
    $user = Auth::user();
@endphp

<div class="modal fade" id="whitelisting-modal" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body">
                <h3 class="popup-title two_fator_verifiation">{{ $lang['whitelisting'] }}</h3>
                <form class="validate-modern" action="{{ route('user.ajax.account.whitelisting') }}" method="POST" id="whitelisting-form">
                    
                    @csrf
                    <input type="hidden" name="action_type" value="whitelisting_setup">
                    <div class="pdb-1-5x">
                        <p>{{ $lang['whitelisting_text_modal'] }}</p>
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="input-item input-with-label">
                                    <label class="input-item-label">{{ $lang['currency'] }}</label>
                                    <div class="input-wrap">
                                        <select name="whitelisting_currency" class="select select-block select-bordered" required>
                                            <option value="eth">ETH</option>
<!--                                            <option value="usdc">USDC</option>-->
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="input-item input-with-label w-sm-60">
                                    <label class="input-item-label">{{ $lang['amount'] }}</label>
                                    <div class="input-wrap">
                                        <input class="input-bordered" required name="whitelisting_amount" placeholder="0.00">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="input-item input-with-label w-sm-60">
                                    <label class="input-item-label">{{ $lang['wallet_address'] }}</label>
                                    <div class="input-wrap">
                                        <input class="input-bordered" required name="whitelisting_address" value="">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="pdb-1-5x">
                            @if($user->whitelisting_comptete == 0)
                                <button type="submit" class="btn btn-primary update_profile" onclick="startWhitelistingProcess()">{{ $lang['request_whitelisting'] }}</button>
                            @else
                                <button class="btn btn-primary update_profile" disabled>{{ $lang['whitelisting_complete'] }}</button>
                            @endif
                        </div>
                        <span id="loading-animation" style="display:none;">
                            <img src="/assets/images/loading.gif" alt="Loading" style="width: 20px;"> {!! $lang['wait_for_whitelisting'] !!}
                        </span>
                    </div>
                        
                </form>
                
                
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>