@php
$pm_check = (!empty($methods) ? true : false);
$dot_1 = '.'; $dot_2 = '';
if ($data->total_bonus > 0) {
$dot_1 = ''; $dot_2 = '.';
}
$user = auth()->user();

if(str_contains(url()->current(), 'contribute')){
    $cur = 'pmc_auto_rate_';
    $cur .= strval($user->base_currency);
    $data->token = round( floatval($data->token) * floatval(get_setting($cur)),2 );   
}

include "/home/robotbq/app.robotbulls.com/config_u.php";

@endphp

<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>
<div class="popup-body">
    <div class="popup-content">
        <form class="validate-modern" action="{{ route('user.ajax.payment') }}" method="POST" id="online_payment">
            @csrf
            <input type="hidden" name="pp_token" id="token_amount" value="{{ $data->token }}" test="{{ $data->amount }}">
            <input type="hidden" name="pp_currency" id="pay_currency" value="{{ $user->base_currency }}" test="{{ $data->currency }}">
            <input type="hidden" name="pp_crypto" id="pay_crypto" value="">
            <input type="hidden" name="payment_method" id="payment_method">
            
            <h4 class="popup-title">{{  $lang['start_your_plan'] }}</h4>
            <p class="lead">{!! ($data->total_bonus > 0)
                ? __($lang['please_make_a_payment_of'] . ' :amount.', ['amount' => '<strong>'.to_num($data->token, 'min').' <span class="pay-currency ucap">'.$user->base_currency.'</span></strong>', 'token_amount'=> '<strong><span class="token-total">'.$data->total_tokens.' '.token('symbol').'</span></strong>', 'token_bonus'=> '<strong><span class="token-bonuses">'.$data->total_bonus.' '.token('symbol').'</span></strong>'])

                : __($lang['please_make_a_payment_of'] . ' :amount.', ['amount' => '<strong>'.to_num($data->token, 'min').' <span class="pay-currency ucap">'.$user->base_currency.'</span></strong>', 'token_amount'=> '<strong><span class="token-total">'.$data->total_tokens.' '.token('symbol').'</span></strong>']) !!}
            </p>

            <p>{{ $lang['You_can_choose_any_payment_method'] }}</p>
            <h5 class="mgt-1-5x font-mid">{{ $lang['select_payment_method'] }}</h5>
            
            <ul class="pay-list guttar-12px">

                @if( strpos($_SERVER['HTTP_REFERER'], "demo/")!==false )
                <li class="pay-item" style="width: 100%;">
                    <div class="input-wrap">

                        <input type="radio" class="pay-check" value="demo" name="pay_option" required="required" id="pay-demo" data-msg-required="Select your payment method." aria-required="true">

                        <label class="pay-check-label" for="pay-demo" currency="{{ $user->base_currency }}">
                            <span class="pay-check-text" title="You can make a Demo transfer.">{{ $lang['demo_investment'] }}</span>
                            <img class="pay-check-img" src="https://app.robotbulls.com/assets/images/pay-demo.png" alt="Demo">
                        </label>

                    </div>
                </li>
                @else

                @if(str_contains(url()->current(), 'contribute'))
                <b class="h4 margin text-danger" style="margin-left: 5px;">{{ $lang['investment_currently_unavailable'] }}</b>
                @endif
                
                
                
                @if(str_contains(url()->current(), 'invest'))
<!--
                <li class="pay-item">
                     <div class="input-wrap">
                     <input type="radio" class="pay-check" value="coinpayments" name="pay_option" required="required" id="pay-btc" data-msg-required="Select your payment method." aria-required="true">
                     <label class="pay-check-label" for="pay-btc" currency="btc">
                         <span class="pay-check-text" title="{{ $lang['you_can_make_payment_with_btc'] }}">Bitcoin</span>
                         <img class="pay-check-img" src="https://app.robotbulls.com/assets/images/pay-btc.png" alt="btc">
                     </label>
                     </div>
                 </li>
-->
                <li class="pay-item col-12">
                     <div class="input-wrap">
                     <input type="radio" class="pay-check" value="coinpayments" name="pay_option" required="required" id="pay-eth" data-msg-required="Select your payment method." aria-required="true">
                     <label class="pay-check-label" for="pay-eth" currency="eth">
                         <span class="pay-check-text" title="{{ $lang['you_can_make_payment_with_eth'] }}">Ethereum</span>
                         <img class="pay-check-img" src="https://app.robotbulls.com/assets/images/pay-eth.png" alt="eth">
                     </label>
                     </div>
                 </li>
                <li class="pay-item">
                     <div class="input-wrap">
                     <input type="radio" class="pay-check" value="coinpayments" name="pay_option" required="required" id="pay-usdt" data-msg-required="Select your payment method." aria-required="true">
                     <label class="pay-check-label" for="pay-usdt" currency="usdt">
                         <span class="pay-check-text" title="{{ $lang['you_can_make_payment_with_usdt'] }}">USDT</span>
                         <img class="pay-check-img" src="https://app.robotbulls.com/assets/images/pay-usdt.png" alt="usdt">
                     </label>
                     </div>
                 </li>
                <li class="pay-item">
                     <div class="input-wrap">
                     <input type="radio" class="pay-check" value="coinpayments" name="pay_option" required="required" id="pay-usdc" data-msg-required="Select your payment method." aria-required="true">
                     <label class="pay-check-label" for="pay-usdc" currency="usdc">
                         <span class="pay-check-text" title="{{ $lang['you_can_make_payment_with_usdc'] }}">USDC</span>
                         <img class="pay-check-img" src="https://app.robotbulls.com/assets/images/pay-usdc.png" alt="usdc">
                     </label>
                     </div>
                 </li>
                
                
                @if(floatval($user->whitelist_balance) > 0)
                    <li class="pay-item col-12">
                        <div class="input-wrap">
                            <input type="radio" class="pay-check" 
                                   value="whitelisting_balance" 
                                   name="pay_option" 
                                   required="required" 
                                   id="pay-whitelisting_balance" 
                                   data-msg-required="Select your payment method." 
                                   aria-required="true"
                                   {{ $user->whitelist_balance < 5000 ? 'disabled' : '' }}>
                            <label class="pay-check-label" for="pay-whitelisting_balance" currency="">
                            <span class="pay-check-text" title="">
                                {{ $lang['whitelisting_balance'] }} {{ round($user->whitelist_balance,2) }} {{ strtoupper($user->base_currency) }}
                                @if($user->whitelist_balance < 5000)
                                <span class="small">Minimum 5000 {{ strtoupper($user->base_currency) }}</span>
                                @endif
                            </span>
                                <img class="pay-check-img" src="https://app.robotbulls.com/assets/images/pay-bank.png" alt="">
                            </label>
                        </div>
                    </li>
                @endif

                @endif
                @endif

                <script>
                    $(".pay-check-label").click(function() {
                        $('input[name="pp_currency"]').val($(this).attr("currency"));
                    });
                </script>
            </ul>


            @if(str_contains(url()->current(), '/invest'))
            <div class="note note-plane note-text-alert pb-3 mt-3">
                <em class="fa-info-circle fas"></em>
                <p class="text-light">{{ $lang['all_funds_will_be_transfered_to_rb_wallet'] }}</p>
            </div>
            @endif

            <div class="pdb-0-5x">
                <div class="input-item text-left">
                    <input type="checkbox" data-msg-required="{{ __(" You should accept our terms and policy.") }}" class="input-checkbox input-checkbox-md" id="agree-terms" name="agree" required>
                    <label for="agree-terms">{!! $lang['i_agree_to_the'] . get_page_link('terms', ['target'=>'_blank', 'name' => true, 'status' => true]) . ((get_page_link('terms', ['status' => true]) && get_page_link('policy', ['status' => true])) ? ' '.$lang['and'].' ' : '') . get_page_link('policy', ['target'=>'_blank', 'name' => true, 'status' => true]) !!}.</label>
                </div>
                <div class="input-item text-left">
                    <input type="checkbox" data-msg-required="{{ __(" You accept the plan and duration.") }}" class="input-checkbox input-checkbox-md" id="agree-plan" name="agree" required>
                    
                    
                    <label for="agree-plan">
                      {!! $lang['i_am_chosing_the'] . '<b>' . $data->plan . '</b>' !!}
                      @if($data->plan !== 'BTC Bull')
                        {!! $lang['for'] . '<b>' . $data->duration . '</b>' !!}
                      @endif
                      {!! $lang['the_funds_will_be_traded_until_the'] . '<b>' . (new DateTime())->add(new DateInterval('P' . intval($data->duration) . 'M'))->format('d/m/Y') . '</b>' !!}
                    .</label>

                    
                </div>
            </div>

            
            @if(!str_contains(url()->current(), 'contribute'))
            <ul class="d-flex flex-wrap align-items-center guttar-30px">
                <li id="staticButton"><button type="submit" class="btn btn-alt btn-primary payment-btn"> {{ $lang['make_transaction'] }} <em class="ti ti-arrow-right mgl-2x"></em></button></li>
            </ul>
            @endif
            
            <div class="gaps-3x"></div>
            <div class="note note-plane note-light">
                <em class="fas fa-question"></em>
                <p class="text-light"><b>{!! $lang['you_dont_have_crypto'] !!}</b>{{ $lang['click'] }} <a href="{{route('user.buycrypto')}}" target="_blank">{{ $lang['here'] }}</a> {{ $lang['to_learn_how_you_can_buy_crypto'] }}</p>
            </div>


        </form>
    </div>
</div>

<script type="text/javascript">
    (function($) {
        var $_p_form = $('form#online_payment');
        if ($_p_form.length > 0) { purchase_form_submit($_p_form); }
    })(jQuery);
</script>
