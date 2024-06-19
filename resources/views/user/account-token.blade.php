@php
$has_sidebar = false;
$content_class = 'col-lg-8';
@endphp

@extends('layouts.user')
@section('title', __('My Investments'))

@push('header')
<script>
    var access_url = "{{ route('user.ajax.token.access') }}";
</script>
@endpush

@section('content')
<div class="card content-area content-area-mh">
    <div class="card-innr">
        
        <div class="card-head">
                    <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0 transaction_list">
                        {{ $lang['my_portfolio'] }}
                    </h4>
                    <div class="d-flex align-items-center guttar-20px">
                        <div class="flex-col d-sm-block d-none">
                            <a href="https://app.robotbulls.com/" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em><span class="back">{{$lang['back']}}</span></a>
                        </div>
                        <div class="flex-col d-sm-none">
                            <a href="https://app.robotbulls.com/" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                        </div>
                    </div>
                    </div>    
                </div>
        
        <div class="gaps-1x"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="float-right position-relative">
                    <div href="#" class="btn btn-light-alt btn-xs dt-filter-text btn-icon toggle-tigger"> <em class="ti ti-settings"></em> </div>
                    <div class="toggle-class toggle-datatable-filter dropdown-content dropdown-dt-filter-text dropdown-content-top-left text-left">
                        <ul class="dropdown-list dropdown-list-s2">
                            <li>
                                <h6 class="dropdown-title">{{ $lang['assets'] }}</h6>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-type" id="type-all" checked value="">
                                <label for="type-all">{{ $lang['all'] }}</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <table class="data-table dt-filter-init user-tnx">
            <thead>
                <tr class="data-item data-head">
                    <th class="data-col tnx-status dt-tnxno">{{ $lang['asset'] }}</th>
                    <th class="data-col dt-account">{{ $lang['amount'] }}</th>
                    <th class="data-col dt-token">{{ $lang['equity'] }}</th>
                    <th class="data-col dt-type tnx-type">
<!--                        <div class="dt-type-text">-->
                            {{ $lang['type'] }}
<!--                        </div>-->
                    </th>
<!--                    <th class="data-col"></th>-->
                </tr>
            </thead>
            <tbody>

                @for ($i = 0; $i < count($name_of_asset_classes); $i++)    

                <tr class="data-item tnx-item">
                    <td class="data-col dt-token text-center">
                        <span class="lead token-amount">{{ $name_of_asset_classes[$i] }}</span>
                    </td>
                    
                    <td class="data-col dt-amount text-center">
                        <span class="lead amount-pay">{{ $balance_of_asset_classes[$i] }}</span>
                        <span class="sub sub-symbol">{{ $balance_currency_of_asset_classes[$i] }} </span>
                    </td>
                    <td class="data-col text-center">
                        <span class="lead amount-pay amount-equity">{{ $equity_of_asset_classes[$i] }}</span>
                        <span class="sub sub-symbol">{{ $balance_currency_of_asset_classes[$i] }} </span>
                    </td>
               
                    <td class="data-col dt-type">
                        <span class="dt-type-md badge badge-outline badge-md badge-{{ __(__status($tnx_type_of_asset_classes[$i],'status')) }}">{{ ucfirst($tnx_type_of_asset_classes[$i]) }}</span>
                    </td>
                </tr>{{-- .data-item --}}
                
                @endfor
            </tbody>
        </table>

    </div>{{-- .card-innr --}}
</div>{{-- .card --}}

@push('sidebar')
    <div class="aside sidebar-right col-lg-4">
        <div class="reg-statistic-graph card card-token">
            <div class="card-innr">
                {!! UserPanel::portfolio($lang,$current_price, $contribution, ['vers' => 'side']) !!}
            </div>
        </div>
        
        @if($tax_amount != null && $tax_amount_rbc != null)
        <div class="kyc-info card">
            <div class="card-innr">
                <h6 class="card-title card-title-sm">Your Wallet status on the: <span class="tax_date">31.12.{{$tax_date}}</span></h6>
                <p>Your equity on the <span class="tax_date">31.12.{{$tax_date}}</span> was of: <b>{{$tax_amount}} {{$user->base_currency}}</b> or <b>{{$tax_amount_rbc}} RBC</b> @if($usdt_amount != false) or <b>{{$usdt_amount}} usdt </b>@endif</p>
                <p>The price of RBC on the <span class="tax_date">31.12.{{$tax_date}}</span> was of {{$tax_rbc_price}} {{$user->base_currency}}</p>
            </div>
        </div>
        @endif
    </div>
@endpush






@endsection



@push('footer')

<script>
    var name_of_asset_classes = "<?= implode(",",$name_of_asset_classes) ?>";
    console.log("name_of_asset_classes:");
    console.log(name_of_asset_classes);
    var balance_of_asset_classes = "<?= implode(",",$balance_of_asset_classes) ?>";
    console.log("balance_of_asset_classes:");
    console.log(balance_of_asset_classes);
    var equity_of_asset_classes = "<?= implode(",",$equity_of_asset_classes) ?>";
    console.log("equity_of_asset_classes:");
    console.log(equity_of_asset_classes);
</script>
<script src="{{ asset('assets/js/user_portfolio.js') }}"></script>

@endpush