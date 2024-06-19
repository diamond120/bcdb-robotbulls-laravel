@extends('layouts.user')
@section('title', $lang['purchase_coin'])

@section('content')
@php
$has_sidebar = false;
$content_class = 'col-lg-8';

$current_date = time();
$upcoming = is_upcoming();

$user = Auth::user();

$_b = 0; 
$bc = base_currency();
$default_method = token_method();
$symbol = token_symbol();
$method = strtolower($default_method);
$min_token = ($minimum) ? $minimum : active_stage()->min_purchase;

$sold_token = (active_stage()->soldout + active_stage()->soldlock);
$have_token = (active_stage()->total_tokens - $sold_token);
$sales_ended = (($sold_token >= active_stage()->total_tokens) || ($have_token < $min_token)) ? true : false;

$is_method = is_method_valid();

$sl_01 = ($is_method) ? '01 ' : '';
$sl_02 = ($sl_01) ? '02 ' : '';
$sl_03 = ($sl_02) ? '03 ' : '';


$exc_rate = (!empty($currencies)) ? json_encode($currencies) : '{}';
$token_price = (!empty($price)) ? json_encode($price) : '{}';
$amount_bonus = (!empty($bonus_amount)) ? json_encode($bonus_amount) : '{1 : 0}';
$decimal_min = (token('decimal_min')) ? token('decimal_min') : 0;
$decimal_max = (token('decimal_max')) ? token('decimal_max') : 0;

@endphp
                                                                                            
@push('header')
<script type="text/javascript">
window.onload = function () {
	
}
</script>
<!--<script type="text/javascript" src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>-->
<script type="text/javascript" src="/assets/js/canvasjs.min.js"></script>
<style>
    .canvasjs-chart-credit {
        display: none !important;
    }
    .canvasjs-chart-credit {
        display: none !important;
    }
</style>
@endpush

@include('layouts.messages')
@if ($upcoming)
<div class="alert alert-dismissible fade show alert-info" role="alert">
    <a href="javascript:void(0)" class="close" data-dismiss="alert" aria-label="close">&nbsp;</a>
    {{ __($lang['sales_start_at']) }} - {{ _date(active_stage()->start_date) }}
</div>
@endif

<div class="content-area card">
    <div class="card-innr">
        <form action="javascript:void(0)" method="post" class="token-purchase">
            
            <div class="coin-graph card-head has-aside">
                <h4 class="card-title card-title-sm equity_overview">{{ __($lang['choose_currency_and_calculate_price'], ['symbol' => $symbol]) }}</h4>
                <div class="card-opt">
                    <div href="'. url()->current() .'" class="link ucap link-light toggle-tigger toggle-caret equity_text fiveteen_days">{{ __($lang['30days']) }}</div>
                    <div class="toggle-class dropdown-content">
                        <ul class="dropdown-list">
                            <li><a href="{{ __(url()->current() ) . __('?price=7') }}" class="seven_days">{{ __($lang['7days']) }}</a></li>
                            <li><a href="{{ __(url()->current() ) . __('?price=30') }}" class="fiveteen_days">{{ __($lang['30days']) }}</a></li>
                        </ul>
                    </div>
                </div>
            </div>
            
            <div id="coin_graph" style="height: 300px; width: 100%;"></div>
            
            <div class="card-text">
                <p>{{ __($lang['you_can_buy_our_token'], ['symbol' => $symbol]) }}</p>
            </div>

            @if($is_method==true)
            <div class="token-currency-choose payment-list">
                <div class="row guttar-15px">    
                    
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="pay{{ $user->base_currency }}" name="paymethod" value="{{ $user->base_currency }}" checked="">
                            <label class="pay-option-label" for="pay{{ $user->base_currency }}">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-{{ $user->base_currency }} sd ikon ikon-sign-{{ $user->base_currency }}"></em>
                                    <span class="pay-cur">{{ strtoupper($user->base_currency) }}</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_'.$user->base_currency), '6') }} {{ strtoupper($user->base_currency) }}</span>
                            </label>
                        </div>       
                    </div>
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="paybch" name="paymethod" value="bch">
                            <label class="pay-option-label" for="paybch">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-bch sd ikon ikon-sign-bch"></em>
                                    <span class="pay-cur">BCH</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_bch'), '6') }} BCH</span>
                            </label>
                        </div>       
                    </div>
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="paybnb" name="paymethod" value="bnb">
                            <label class="pay-option-label" for="paybnb">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-bnb sd ikon ikon-sign-bnb"></em>
                                    <span class="pay-cur">BNB</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_bnb'), '6') }} BNB</span>
                            </label>
                        </div>       
                    </div>
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="paybtc" name="paymethod" value="btc">
                            <label class="pay-option-label" for="paybtc">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-btc sd ikon ikon-sign-btc"></em>
                                    <span class="pay-cur">BTC</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_btc'), '6') }} BTC</span>
                            </label>
                        </div>       
                    </div>                
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="paydash" name="paymethod" value="dash">
                            <label class="pay-option-label" for="paydash">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-dash sd ikon ikon-sign-dash"></em>
                                    <span class="pay-cur">DASH</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_dash'), '6') }} DASH</span>
                            </label>
                        </div>       
                    </div>
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="payeth" name="paymethod" value="eth">
                            <label class="pay-option-label" for="payeth">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-eth sd ikon ikon-sign-eth"></em>
                                    <span class="pay-cur">ETH</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_eth'), '6') }} ETH</span>
                            </label>
                        </div>       
                    </div>
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="payltc" name="paymethod" value="ltc">
                            <label class="pay-option-label" for="payltc">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-ltc sd ikon ikon-sign-ltc"></em>
                                    <span class="pay-cur">LTC</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_ltc'), '6') }} LTC</span>
                            </label>
                        </div>       
                    </div>
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="paytrx" name="paymethod" value="trx">
                            <label class="pay-option-label" for="paytrx">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-trx sd ikon ikon-sign-trx"></em>
                                    <span class="pay-cur">TRX</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_ltc'), '6') }} TRX</span>
                            </label>
                        </div>       
                    </div>
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="payusdc" name="paymethod" value="usdc">
                            <label class="pay-option-label" for="payusdc">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-usdc sd ikon ikon-sign-usdc"></em>
                                    <span class="pay-cur">USDC</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_ltc'), '6') }} USDC</span>
                            </label>
                        </div>       
                    </div>
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="payusdt" name="paymethod" value="usdt" >
                            <label class="pay-option-label" for="payusdt">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-usdt sd ikon ikon-sign-usdt"></em>
                                    <span class="pay-cur">USDT</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_usdt'), '6') }} USDT</span>
                            </label>
                        </div>       
                    </div>
                    <div class="col-6">
                        <div class="payment-item pay-option">
                            <input class="pay-option-check pay-method" type="radio" id="payxrp" name="paymethod" value="xrp">
                            <label class="pay-option-label" for="payxrp">
                                <span class="pay-title">
                                    <em class="pay-icon pay-icon-xrp sd ikon ikon-sign-xrp"></em>
                                    <span class="pay-cur">XRP</span>
                                </span>
                                <span class="pay-amount">{{ to_num(get_setting('pmc_auto_rate_xrp'), '6') }} XRP</span>
                            </label>
                        </div>       
                    </div>
                    
                    
                    
                    
                    
                </div>
            </div>
            @else 
            <div class="token-currency-default payment-item-default">
                <input class="pay-method" type="hidden" id="pay{{ base_currency() }}" name="paymethod" value="{{ base_currency() }}" checked>
            </div>
            @endif
            
            <div class="card-head">
                <h4 class="card-title">{{ $lang['amount_of_contribution'] }}</h4>
            </div>
            <div class="card-text">
                <p>{{ $lang['enter_amount_you_would_like_to_contribute'] }}</p>
            </div>
            @php
            $calc = token('calculate');
            $input_hidden_token = ($calc=='token') ? '<input class="pay-amount" type="hidden" id="pay-amount" value="">' : '';
            $input_hidden_amount = ($calc=='pay') ? '<input class="token-number" type="hidden" id="token-number" value="">' : ''; 

            $input_token_purchase = '<div class="token-pay-amount payment-get">'.$input_hidden_token.'<input class="input-bordered input-with-hint token-number" type="text" id="token-number" value="" min="'.$min_token.'" max="'.$stage->max_purchase.'"><div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">'.$symbol.'</span></div></div>';
            $input_pay_amount = '<div class="token-pay-amount payment-from">'.$input_hidden_amount.'<input class="input-bordered input-with-hint pay-amount" type="text" id="pay-amount" value=""><div class="token-pay-currency"><span class="input-hint input-hint-sap payment-from-cur payment-cal-cur pay-currency ucap">'.$method.'</span></div></div>';
            $input_token_purchase_num = '<div class="token-received"><div class="token-eq-sign">=</div><div class="token-received-amount"><h5 class="token-amount token-number-u">0</h5><div class="token-symbol">'.$symbol.'</div></div></div>';
            $input_pay_amount_num = '<div class="token-received token-received-alt"><div class="token-eq-sign">=</div><div class="token-received-amount"><h5 class="token-amount pay-amount-u">0</h5><div class="token-symbol pay-currency ucap">'.$method.'</div></div></div>';
            $input_sep = '<div class="token-eq-sign"><em class="fas fa-exchange-alt"></em></div>';
            @endphp
            <div class="token-contribute">
                <div class="token-calc">{!! $input_token_purchase.$input_pay_amount_num !!}</div>
            
                <div class="token-calc-note note note-plane token-note">
                    <div class="note-box">
                        <span class="note-icon">
                            <em class="fas fa-info-circle"></em>
                        </span>
                        <span class="note-text text-light"><strong class="min-amount">{{ token_calc($min_token, 'price')->$method }}</strong> <span class="pay-currency ucap">{{ $method }}</span> (<strong class="min-token">{{ $min_token }}</strong>
                        <span class="token-symbol ucap">{{ $symbol }}</span>) {{$lang['minimum_contribution_is_required']}}</span>
                    </div>
                    <div class="note-text note-text-alert"></div>
                </div>
            </div>

            @if(!empty($bonus_amount) && !$sales_ended)
            <div class="token-bonus-ui">
                <div class="bonus-bar{{ ($active_bonus) ? ' with-base-bonus' : '' }}">
                    @if(!empty($active_bonus))
                    <div class="bonus-base">
                        <span class="bonus-base-title">{{ $lang['bonus'] }}</span>
                        <span class="bonus-base-amount">{{ $lang['on_sale'] }}</span>
                        <span class="bonus-base-percent">{{ $active_bonus->amount }}%</span>
                    </div>
                    @endif
                    @php
                    $b_amt_bar = '';
                    if(!empty($bonus_amount)){
                        foreach($bonus_amount as $token => $bt_amt){
                            $_b = (100 / count($bonus_amount) );
                            $b_amt_bar .= ($bt_amt > 0 && $token > 0) ? '<div class="bonus-extra-item bonus-tire-'. $bt_amt .'" data-percent="'. round($_b, 0).'"><span class="bonus-extra-amount">'. $token .' '. $symbol .'</span><span class="bonus-extra-percent">'.$bt_amt.'%</span></div>' : '';
                        }
                    }
                    $b_amt_bar = (!empty($b_amt_bar)) ? '<div class="bonus-extra">'.$b_amt_bar.'</div>' : '';
                    @endphp
                    {!! $b_amt_bar !!}
                </div>
            </div>
            @endif
            @if(!$sales_ended)
            <div class="token-overview-wrap">
                <div class="token-overview">
                    <div class="row">
                        <div class="col-md-4 col-sm-6">
                            <div class="token-bonus token-bonus-sale">
                                <span class="token-overview-title">+ {{ __($lang['sale_bonus']) . ' ' . (empty($active_bonus) ? 0 :  $active_bonus->amount) }}%</span>
                                <span class="token-overview-value bonus-on-sale tokens-bonuses-sale">0</span>
                            </div>
                        </div>
                        @if(!empty($bonus_amount && !empty($b_amt_bar)) )
                        <div class="col-md-4 col-sm-6">
                            <div class="token-bonus token-bonus-amount">
                                <span class="token-overview-title">+ {{__($lang['amount_bonus'])}}</span>
                                <span class="token-overview-value bonus-on-amount tokens-bonuses-amount">0</span>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-4">
                            <div class="token-total">
                                <span class="token-overview-title font-bold">{{__($lang['total']) . ' '.$symbol }}</span>
                                <span class="token-overview-value token-total-amount text-primary payment-summary-amount tokens-total">0</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="note note-plane note-danger note-sm pdt-1x pl-0">
                    <p>{{$lang['contribution_calculated']}}</p>
                </div>
            </div>
            @endif

            @if(is_payment_method_exist() && !$upcoming && ($stage->status != 'paused') && !$sales_ended)
            <div class="pay-buttons">
                <div class="pay-buttons pt-0">
                    <a data-type="offline" href="#payment-modal" class="btn btn-primary btn-between payment-btn disabled token-payment-btn offline_payment">{{ $lang['make_payment'] }}&nbsp;<i class="ti ti-wallet"></i></a>
                </div>
                <div class="pay-notes">
                    <div class="note note-plane note-light note-md font-italic">
                        <em class="fas fa-info-circle"></em>
                        <p>{{__($lang['tokens_will_appear_in_your_wallet_after'], ['symbol' => $symbol]) }}</p>
                    </div>
                </div>
            </div>
            @else
            <div class="alert alert-info alert-center">
                {{ ($sales_ended) ? __($lang['token_sale_has_been_finished']) : __($lang['sale_will_start_soon']) }}
            </div>
            @endif
            <input type="hidden" id="data_amount" value="0">
            <input type="hidden" id="data_currency" value="{{ $default_method }}">
        </form>
    </div> {{-- .card-innr --}}
</div> {{-- .content-area --}}
@push('sidebar')
<div class="aside sidebar-right col-lg-4">
    @if(!has_wallet() && gws('token_wallet_req')==1 && !empty(token_wallet()))
    <div class="d-none d-lg-block">
        {!! UserPanel::add_wallet_alert($lang) !!}
    </div>
    @endif
    {!! UserPanel::user_balance_card_rbc($lang,$contribution, ['vers' => 'side']) !!}
    <div class="token-sales card">
        <div class="card-innr">
            <div class="card-head">
                <h5 class="card-title card-title-sm">{{ $lang['token_sales'] }}</h5>
            </div>
            <div class="token-rate-wrap row">
                <div class="token-rate col-md-6 col-lg-12">
                    <span class="card-sub-title">{{ $symbol }} {{$lang['token_price']}}</span>
                    <h4 class="font-mid text-dark">1 {{ $symbol }} = <span>{{ '1 '. base_currency(true) }}</span></h4>
                </div>
                <div class="token-rate col-md-6 col-lg-12">
                    <span class="card-sub-title">{{$lang['exchange_rate']}}</span>
                    @php
                    $exrpm = collect($pm_currency);
                    $exrpm = $exrpm->forget(base_currency())->take(2);
                    $exc_rate = '<span>1 '.base_currency(true) .' ';
                    foreach ($exrpm as $cur => $name) {
                        if($cur != base_currency() && get_exc_rate($cur) != '') {
                            $exc_rate .= ' = '.to_num(get_exc_rate($cur), 'max') . ' ' . strtoupper($cur);
                        }
                    }
                    $exc_rate .= '</span>';
                    @endphp
                    {!! $exc_rate !!}
                </div>
            </div>
            @if(!empty($active_bonus))
            <div class="token-bonus-current">
                <div class="fake-class">
                    <span class="card-sub-title">{{$lang['current_bonus']}}</span>
                    <div class="h3 mb-0">{{ $active_bonus->amount }} %</div>
                </div>
                <!--<div class="token-bonus-date">{{__('End at')}}<br>{{ _date($active_bonus->end_date, get_setting('site_date_format')) }}</div>-->
            </div>
            @endif
        </div>
    </div>
    {!! UserPanel::token_sales_ad2($lang,  ['class' => 'mb-0']) !!}
</div>{{-- .col.aside --}}
@endpush

@endsection

@section('modals')
<div class="modal fade modal-payment" id="payment-modal" tabindex="-1" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content"></div>
    </div>
</div>
@endsection

@push('header')
<script>
    var access_url = "{{ route('user.ajax.token.access') }}";
    var minimum_token = {{ $min_token }}, maximum_token ={{ $stage->max_purchase }}, token_price = {!! $token_price !!}, token_symbol = "{{ $symbol }}",
    base_bonus = {!! $bonus !!}, amount_bonus = {!! $amount_bonus !!}, decimals = {"min":{{ $decimal_min }}, "max":{{ $decimal_max }} }, base_currency = "{{ base_currency() }}", base_method = "{{ $method }}";
</script>
@endpush