@php
        
$has_sidebar = false;      

@endphp
@extends('layouts.user')
@section('title', $lang['user_dashboard'])


@section('content')
<div class="content-area user-account-dashboard">
    @include('layouts.messages')
    <div class="row">
        
        @if( !isset($user->kyc_info->status) )
        <div class="col-lg-12">
            {!! UserPanel::kyc_popup($lang, ['class' => 'card-full-height']) !!}
        </div>
        @endif

        <div class="col-lg-4">
            {!! UserPanel::user_balance_card($lang,$current_price, $contribution, ['vers' => 'side', 'class'=> 'card-full-height'], $trnxs, $user, $balance, $equity) !!}
        </div>

        <div class="col-12 col-md-6 col-lg-4">
            <div class="card card-sales-progress card-full-height" id="chooseplansection">
                {!! UserPanel::bulls($lang, ['class' => 'card-full-height']) !!}
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="reg-statistic-graph card card-full-height">
                <div class="card-innr">
                    {!! UserPanel::prediction($lang) !!}
                </div>
            </div>
        </div>

        @if($user->tokenBalance > 1 && $user->robot == "active")
        <div class="col-12 col-lg-6 d-none d-lg-block">
            <div class="reg-statistic-graph card card-full-height">
                <div class="card-innr">
            {!! UserPanel::portfolio_org($lang,$trnxs,$current_price, $contribution, ['vers' => 'side']) !!}
                </div>
            </div>
        </div>
        @endif

        <div class="col-12 col-lg-6 <?php if($user->tokenBalance > 1 && $user->robot == "active") { echo 'd-none d-lg-block'; } ?>">
            {!! UserPanel::rbc_panel($lang, ['class' => 'card-full-height']) !!}
        </div>

        @if(count($trnxs) > 0)
        <div class="col-12 col-lg-6">
            {!! UserPanel::transactions($lang, ['class' => 'card-full-height']) !!}
        </div>
        @endif

        <div class="col-12 col-lg-6">
            <div class="card card-full-height">
                {!! UserPanel::how_to_buy_crypto($lang, ['class' => 'card-full-height']) !!}
            </div>
        </div>

    </div>
</div>



@endsection

@push('footer')
<script type="text/javascript">
    //set basic vars    
    
    
    //Prediction
    var prediction_array = "<?=$user->prediction_array?>";
    var equity_array = "<?=$user->equity_array?>";
    var user_balance = <?=$user->contributed?>;
    var user_equity = <?=$user->equity?>;
    var base_currency = "<?=$user->base_currency?>";
    
    //Portfolio
    var name_of_asset_classes = "<?= implode(",",$name_of_asset_classes) ?>";
    var balance_of_asset_classes = "<?= implode(",",$balance_of_asset_classes) ?>";
    var equity_of_asset_classes = "<?= implode(",",$equity_of_asset_classes) ?>";
    

</script>
<script src="{{ asset('assets/js/user_equity.js') }}"></script>
<script src="{{ asset('assets/js/user_prediction.js') }}"></script>
<script src="{{ asset('assets/js/user_portfolio_org.js') }}"></script>
@endpush
