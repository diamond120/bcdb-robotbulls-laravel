@extends('layouts.user')
@section('title', $lang['purchase_coin'])

@section('content')


@php
$symbol = 'RBC';
$default_method = token_method();
$method = strtolower($default_method);
$token_price = (!empty($price)) ? json_encode($price) : '{}';
@endphp

@push('header')
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/accessibility.js"></script>

<script type="text/javascript" src="{{ asset('assets/js/canvasjs.min.js') }}"></script>
<style>
    .canvasjs-chart-credit {
        display: none !important;
    }
    .canvasjs-chart-credit {
        display: none !important;
    }
</style>

<!--for payment modal-->
<script>
    var access_url = "{{ route('user.ajax.token.access') }}";
    var minimum_token = {{ $min_token }}, maximum_token ={{ $max_token }}, token_price = {!! $token_price !!}, token_symbol = "{{ $symbol }}", base_bonus = 0, amount_bonus = {100 : 0}, decimals = {"min":6, "max":15 }, base_currency = "{{ base_currency() }}", base_method = "{{ $method }}";
</script>
@endpush                                                                                            

@include('layouts.messages')
<form action="javascript:void(0)" method="post" class="token-purchase">
    <div class="content-area card">
        <div class="card-innr">
            <h4 class="card-title pb-3">{{ __('RobotBulls Coin', ['symbol' => $symbol]) }}</h4>
            <h5 class="font-mid fs-24 text-dark">1 {{ $symbol }} = <span> {{ $current_price . base_currency(true) }} </span></h5>
            <div id="coingraph"></div>
        </div>
    </div>
    <div class="content-area card">
    <div class="card-innr">
            <div class="card-head pt-3">
                <h4 class="card-title">{{ $lang['amount_of_contribution'] }}</h4>
            </div>
            <div class="card-text">
                <p>{{ $lang['enter_amount_you_would_like_to_contribute'] }}</p>
            </div>
            @php
            $calc = token('calculate');
            $input_hidden_token = ($calc=='token') ? '<input class="pay-amount" type="hidden" id="pay-amount" value="">' : '';
            $input_hidden_amount = ($calc=='pay') ? '<input class="token-number" type="hidden" id="token-number" value="">' : ''; 

            $input_token_purchase = '<div class="token-pay-amount payment-get">'.$input_hidden_token.'<input class="input-bordered input-with-hint token-number" type="text" id="token-number" value="" min="'.$min_token.'" max="'.$max_token.'"><div class="token-pay-currency"><span class="input-hint input-hint-sap payment-get-cur payment-cal-cur ucap">'.$symbol.'</span></div></div>';
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
                        <span class="note-text text-light"> (<strong class="min-token">{{strval($min_token)}}</strong>
                        <span class="token-symbol ucap">{{ $symbol }}</span>) {{$lang['minimum_contribution_is_required']}}</span>
                    </div>
                    <div class="note-text note-text-alert"></div>
                </div>
            </div>

            <div class="pay-buttons">
                <div class="pay-buttons pt-0">
                    <a href="#" class="btn btn-primary btn-between disabled">{{ $lang['currently_unavailable'] }}&nbsp;<i class="ti ti-wallet"></i></a>
                </div>
            </div>
            
            <input type="hidden" id="data_amount" value="0">
            <input type="hidden" id="data_currency" value="{{ $default_method }}">
        
    </div> {{-- .card-innr --}}
</div> {{-- .content-area --}}
</form>

@push('sidebar')
<div class="aside sidebar-right col-lg-4">
    @if(!has_wallet() && gws('token_wallet_req')==1 && !empty(token_wallet()))
    <div class="d-none d-lg-block">
        {!! UserPanel::add_wallet_alert($lang) !!}
    </div>
    @endif
    {!! UserPanel::user_balance_card_rbc($lang,$contribution, ['vers' => 'side'], $symbol, $current_price) !!}
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

@push('footer')
<script>
    var graph = @php echo json_encode($graph); @endphp
</script>
<script type="text/javascript" src="{{ asset('assets/js/coin.js') }}"></script>
@endpush