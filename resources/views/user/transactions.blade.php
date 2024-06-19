@php
$user = auth()->user();
@endphp
@extends('layouts.user')
@section('title', $lang['user_transactions'])

@push('header')
<script type="text/javascript">
    var view_transaction_url = "{{ route('user.ajax.transactions.view') }}";
</script>
@endpush

@section('content')
@include('layouts.messages')
<div class="card content-area content-area-mh">
    <div class="card-innr">

        <div class="card-head">
            <div class="card-head d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 transaction_list">
                    {{ $lang['transactions_list'] }}
                </h4>
                <div class="d-flex align-items-center guttar-20px">
                    <div class="flex-col d-sm-block d-none">
                        <a href="/" class="btn btn-light btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left mr-3"></em><span class="back">{{$lang['back']}}</span></a>
                    </div>
                    <div class="flex-col d-sm-none">
                        <a href="/" class="btn btn-light btn-icon btn-sm btn-primary"><em class="fas fa-arrow-left"></em></a>
                    </div>
                </div>
            </div>
        </div>

        <div class="gaps-1x"></div>
        <div class="row">
            <div class="col-md-12">
                <div class="float-right position-relative">
                    <span href="#" class="btn btn-light-alt btn-xs dt-filter-text btn-icon toggle-tigger"> <em class="ti ti-settings"></em> </span>
                    <div class="toggle-class toggle-datatable-filter dropdown-content dropdown-dt-filter-text dropdown-content-top-left text-left">
                        <ul class="dropdown-list dropdown-list-s2">
                            <li>
                                <h6 class="dropdown-title">{{ $lang['types'] }}</h6>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-type" id="type-all" checked value="">
                                <label for="type-all">{{ $lang['any_type'] }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-type" id="type-purchase" value="Purchase">
                                <label for="type-purchase">{{ $lang['purchase'] }}</label>
                            </li>
                            @foreach($has_trnxs as $name => $has)
                            @if($has==1)
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-type" id="type-{{ $name }}" value="{{ ucfirst($name) }}">
                                <label for="type-{{ $name }}">{{ ucfirst($name) }}</label>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                        <ul class="dropdown-list dropdown-list-s2">
                            <li>
                                <h6 class="dropdown-title">{{ $lang['status'] }}</h6>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" id="status-all" checked value="">
                                <label for="status-all">{{ $lang['show_all'] }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" id="status-approved" value="approved">
                                <label for="status-approved">{{ $lang['approved'] }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" value="pending" id="status-pending">
                                <label for="status-pending">{{ $lang['pending'] }}</label>
                            </li>
                            <li>
                                <input class="data-filter input-checkbox input-checkbox-sm" type="radio" name="tnx-status" value="canceled" id="status-canceled">
                                <label for="status-canceled">{{ $lang['canceled'] }}</label>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <table class="data-table dt-filter-init user-tnx">
            <thead>
                <tr class="data-item data-head">
                    <th class="data-col tnx-status dt-tnxno">{{ $lang['tranx_no'] }}</th>
                    <th class="data-col dt-token">{{ $lang['plan'] }}</th>
                    <!--                    <th class="data-col dt-amount">{{ __('Amount') }}</th>-->
                    <th class="data-col dt-account">{{ $lang['amount'] }}</th>
                    <th class="data-col dt-base-amount">{{ $user->base_currency }} {{ $lang['amount'] }}</th>
                    <th class="data-col dt-type tnx-type">
                        <div class="dt-type-text">{{ $lang['purchase_coin'] }}</div>
                    </th>
                    <th class="data-col"></th>
                </tr>
            </thead>
            <tbody>

                @foreach($trnxs as $trnx)

                @php
                $text_danger = ( $trnx->tnx_type=='refund' || ($trnx->tnx_type=='transfer' && $trnx->extra=='sent') ) ? ' text-danger' : '';
                $pmc_auto_rate = 'pmc_auto_rate_';
                $pmc_auto_rate .= strval($user->base_currency);
                @endphp

                <tr class="data-item tnx-item-{{ $trnx->id }}">
                    <td class="data-col dt-tnxno">
                        <div class="d-flex align-items-center">
                            <div class="data-state data-state-{{ str_replace(['progress','canceled'], ['pending','canceled'], __status($trnx->status, 'icon')) }}">
                                <span class="d-none">{{ ($trnx->status=='onhold') ? ucfirst('pending') : ucfirst($trnx->status) }}</span>
                            </div>
                            <div class="fake-class text-center">
                                <span class="lead tnx-id">{{ $trnx->tnx_id }}</span>
                                <span class="sub sub-date">{{_date($trnx->created_at)}}</span>
                            </div>
                        </div>
                    </td>
                    <td class="data-col dt-token text-center">
                        <span class="lead token-amount{{ $text_danger }}">{{ $trnx->plan }}</span>
                        <span class="sub sub-symbol">{{ $trnx->duration }}</span>
                    </td>

                    <td class="data-col dt-amount{{ $text_danger }} text-center">
                        @if ($trnx->tnx_type=='referral'||$trnx->tnx_type=='bonus')
                        <span class="lead amount-pay{{ $text_danger }}">{{ $trnx->receive_amount, 'max' }}</span>
                        <span class="sub sub-symbol">{{ strtoupper($trnx->receive_currency) }} </span>
                        @else
                        <span class="lead amount-pay{{ $text_danger }}">{{ $trnx->receive_amount, 'max' }}</span>
                        <span class="sub sub-symbol">{{ strtoupper($trnx->receive_currency) }} </span>
                        @endif
                    </td>
                    <td class="data-col dt-usd-amount text-center">
                        @if ($trnx->tnx_type=='referral'||$trnx->tnx_type=='bonus')
                        <span class="lead amount-pay{{ $text_danger }}">{{ round($trnx->base_amount,3) }}</span>
                        <span class="sub sub-symbol">{{ strtoupper($trnx->base_currency) }}</span>
                        @else
                        <span class="lead amount-pay{{ $text_danger }}">{{ round($trnx->base_amount,3) }}</span>
                        <span class="sub sub-symbol">{{ strtoupper($trnx->base_currency) }}</span>
                        @endif
                    </td>

                    <td class="data-col dt-type">
                        <span class="dt-type-md badge badge-outline badge-md badge-{{ __(__status($trnx->tnx_type,'status')) }}">{{ ucfirst($trnx->tnx_type) }}</span>
                        <span class="dt-type-sm badge badge-sq badge-outline badge-md badge-{{ __(__status($trnx->tnx_type, 'status')) }}">{{ ucfirst(substr($trnx->tnx_type, 0,1)) }}</span>
                    </td>
                    <td class="data-col text-right">
                        @if($trnx->status == 'pending' || $trnx->status == 'onhold')
                        @if($trnx->tnx_type != 'transfer')
                        <div class="relative d-inline-block d-md-none">
                            <a href="#" class="btn btn-light-alt btn-xs btn-icon toggle-tigger"><em class="ti ti-more-alt"></em></a>
                            <div class="toggle-class dropdown-content dropdown-content-center-left pd-2x">
                                <ul class="data-action-list">
                                    <li><a href="javascript:void(0)" class="btn btn-auto btn-primary btn-xs view-transaction" data-id="{{ $trnx->id }}"><span>{{$lang['pay']}}</span><em class="ti ti-wallet"></em></a></li>
                                    @if($trnx->checked_time != NUll)
                                    <li><a href="{{ route('user.ajax.transactions.delete', $trnx->id) }}" class="btn btn-danger-alt btn-xs btn-icon user_tnx_trash" data-tnx_id="{{ $trnx->id }}"><em class="ti ti-trash"></em></a></li>
                                    @endif
                                </ul>
                            </div>
                        </div>

                        <ul class="data-action-list d-none d-md-inline-flex">
                            <li><a href="javascript:void(0)" class="btn btn-auto btn-primary btn-xs view-transaction" data-id="{{ $trnx->id }}"><span>{{$lang['pay']}}</span><em class="ti ti-wallet"></em></a></li>
                            @if($trnx->checked_time != NUll)
                            <li><a href="{{ route('user.ajax.transactions.delete', $trnx->id) }}" class="btn btn-danger-alt btn-xs btn-icon user_tnx_trash" data-tnx_id="{{ $trnx->id }}"><em class="ti ti-trash"></em></a></li>
                            @endif
                        </ul>
                        @else
                        <a href="javascript:void(0)" class="view-transaction btn btn-light-alt btn-xs btn-icon" data-id="{{ $trnx->id }}"><em class="ti ti-eye"></em></a>
                        @endif
                        @else
                        <a href="javascript:void(0)" class="view-transaction btn btn-light-alt btn-xs btn-icon" data-id="{{ $trnx->id }}"><em class="ti ti-eye"></em></a>
                        @if($trnx->checked_time == NUll && ($trnx->status == 'rejected' || $trnx->status == 'canceled'))
                        <a href="{{ route('user.ajax.transactions.delete', $trnx->id) }}" class="btn btn-danger-alt btn-xs btn-icon user_tnx_trash" data-tnx_id="{{ $trnx->id }}"><em class="ti ti-trash"></em></a>
                        @endif
                        @endif
                    </td>
                </tr>{{-- .data-item --}}
                @endforeach
            </tbody>
        </table>

    </div>{{-- .card-innr --}}
</div>{{-- .card --}}
@endsection
