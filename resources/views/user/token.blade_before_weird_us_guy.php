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

$symbol = 'RBC';
$default_method = token_method();
$method = strtolower($default_method);


$exc_rate = (!empty($currencies)) ? json_encode($currencies) : '{}';
$token_price = (!empty($price)) ? json_encode($price) : '{}';

@endphp
                                                                                            
@push('header')
<script src="https://code.highcharts.com/stock/highstock.js"></script>
<script src="https://code.highcharts.com/stock/modules/data.js"></script>
<script src="https://code.highcharts.com/stock/modules/exporting.js"></script>
<script src="https://code.highcharts.com/stock/modules/accessibility.js"></script>
<script type="text/javascript">
window.onload = function () {
	
}
</script>
<script type="text/javascript" src="https://app.robotbulls.com/assets/js/canvasjs.min.js"></script>
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


<form action="javascript:void(0)" method="post" class="token-purchase">

<div class="content-area card">
    <div class="card-innr">
        
            
            
            <h4 class="card-title pb-3">{{ __('RobotBulls Coin', ['symbol' => $symbol]) }}</h4>
            <h5 class="font-mid fs-24 text-dark">1 {{ $symbol }} = <span> {{ $current_price . base_currency(true) }} </span></h5>
            <div id="coingraph"  style="height: 400px; width: 100%; margin: 0 auto; background-image: url(https://app.robotbulls.com/images/spinner.gif); background-repeat:no-repeat;background-size:30%;background-position: center center;"></div>
            
            
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


<!--
            <div class="token-overview-wrap">
                <div class="token-overview">
                    <div class="row">
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
-->
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
    var minimum_token = {{ $min_token }}, maximum_token ={{ $max_token }}, token_price = {!! $token_price !!}, token_symbol = "{{ $symbol }}", base_bonus = 0, amount_bonus = {100 : 0}, decimals = {"min":6, "max":15 }, base_currency = "{{ base_currency() }}", base_method = "{{ $method }}";
</script>
@endpush

@push('footer')
{{-- Modal Medium --}}
<div class="modal fade" id="google-auth" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body">
                <h3 class="popup-title two_fator_verifiation">White Paper</h3>
                <iframe src="https://app.robotbulls.com/white-paper" width="500" height="375" type="application/pdf"></iframe>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
{{-- Modal End --}}

<script>
    function getIndexOfK(arr, k) {
      for (var i = 0; i < arr.length; i++) {
        var index = arr[i].indexOf(k);
        if (index > -1) {
          return [i, index];
        }
      }
    }
    
    var graph = @php echo json_encode($graph); @endphp;
    console.log(graph);
    data = graph.map(function(elem) {
        return elem.map(function(elem2) {
            return parseFloat(elem2);
        });
    });
    console.log(data);
    
    
//    var d = new Date();
//    d.setHours(14, 30, 00);
//    yesterdayTimeStamp = d.getTime()- 24*60*60*1000;
//    yesterdayTimeStamp = yesterdayTimeStamp.toString().slice(0, -3);
//    yesterdayTimeStamp = yesterdayTimeStamp+"000";
//        
//    console.log("yesterdayTimeStamp "+yesterdayTimeStamp);
//    
//    
//    var data_difference = data.length - getIndexOfK(data, Number(yesterdayTimeStamp))[0];
//    for (i = 0; i < 250; ++i) {
//        data.pop();
//    }
//    
//    console.log("data.length = " + data.length);
//    console.log("getIndexOfK(data, Number(yesterdayTimeStamp)) = " + getIndexOfK(data, Number(yesterdayTimeStamp)));
//    console.log("data_difference = " + data_difference);
    
    data = data.reverse();
    console.log(data);
    console.log(typeof data[0][0]);
    console.log(typeof data[0][1]);
    
    var jsondata = JSON.stringify(data);
    
//    Highcharts.chart('container', {
        
//    var d = new Date();
//    d.setHours(15, 30, 00);
//    yesterdayTimeStamp = d.getTime()- 24*60*60*1000;
//    yesterdayTimeStamp = yesterdayTimeStamp.toString().slice(0, -3);
//    yesterdayTimeStamp = yesterdayTimeStamp+"000";
//        
//    console.log(yesterdayTimeStamp);
//    
//    var data_difference = data.length - getIndexOfK(data, Number(yesterdayTimeStamp))[0];
//    for (i = 0; i < data_difference-1; ++i) {
//        data.pop();
//    }
//        
//    console.log(data);
//    console.log(typeof data[0][0]);
//    console.log(typeof data[0][1]);
        //[[1671456600000, 4.097, 4.1, 4.021, 4.026], [1671543000000, 4.097, 4.1, 4.021, 4.026], [1671629400000, 4.097, 4.1, 4.021, 4.026], [1671715800000, 4.097, 4.1, 4.021, 4.026]],
        
    // create the chart
    Highcharts.stockChart('coingraph', {
        rangeSelector: {
            selected: 0
        },

        series: [{
            type: 'candlestick',
            name: 'RobotBulls Coin',
            data: data,
            dataGrouping: {
                units: [
                    [
                        'week', // unit name
                        [1] // allowed multiples
                    ], [
                        'month',
                        [1, 2, 3, 4, 6]
                    ]
                ]
            }
        }]
    });
//});

</script>

@endpush