@php
include config('app.dir') . "/config_u.php";
@endphp

@if ($details==true) 
    <div class="card-head d-flex justify-content-between align-items-center">
        <h4 class="card-title mb-0">{{ $lang['transaction_details'] }}</h4>
        <div class="trans-status">
            @if($transaction->status == 'approved')
            <span class="badge badge-success ucap">{{ $lang['approved'] }}</span>
            @elseif($transaction->status == 'pending')
            <span class="badge badge-warning ucap">{{ $lang['pending'] }}</span>
            @elseif($transaction->status == 'onhold')
            <span class="badge badge-info ucap">{{ $lang['in_progress'] }}</span>
            @else
            <span class="badge badge-danger ucap">{{ $lang['rejected'] }}</span>
            @endif
        </div>
    </div>

    @if($transaction->tnx_type=='purchase')
    <div class="trans-details">
        <div class="gaps-1x"></div>
        @if($transaction->status == 'approved')
        <p class="lead-lg text-primary"><strong>{{ $lang['successful_payment_message'] }}</strong> ({{ ucfirst($transaction->payment_method) }} <small>- {{ gateway_type($transaction->payment_method) }}</small>).</p>
        @endif
        <p>{!! __($lang['order_placed_message'], ['orderid' => '<strong class="text-primary">'.$transaction->tnx_id.'</strong>', 'datetime' => _date($transaction->created_at)]) !!}</p>
        @if($transaction->checked_time != NUll && ($transaction->status == 'rejected' || $transaction->status == 'canceled'))
        <p class="text-danger fs-14">{!! __($lang['order_status_message'], ['status' => '<strong>'.$transaction->status.'</strong>']) !!}</p>
        @endif
        <div class="gaps-0-5x"></div>
    </div>
    @endif

@endif

<div class="gaps-1x"></div>
<h6 class="card-sub-title">{{ $transaction->tnx_id }}</h6>
<ul class="data-details-list">
    <li>
        <div class="data-details-head">{{ $lang['types'] }}</div>
        <div class="data-details-des">{{ ucfirst($transaction->tnx_type) }}</div>
    </li>
    <li>
        <div class="data-details-head">{{ $lang['amount'] }}</div>
        <div class="data-details-des"><strong>{{ $transaction->amount }}</strong></div>
    </li>
    @if($transaction->tnx_type!='refund')
    <li>
        <div class="data-details-head">{{ $lang['plan'] }}</div>
        <div class="data-details-des">
            <span>{{ $transaction->plan }}</span>
        </div>
    </li>
    <li>
        <div class="data-details-head">{{ $lang['date'] }}</div>
        <div class="data-details-des">
            <span>{{_date($transaction->created_at)}}</span>
        </div>
    </li>
    @endif
    @if($transaction->tnx_type=='purchase')
    <!-- Additional purchase specific list items if any -->
    @endif
    @if($transaction->tnx_type=='refund')
    <!-- Additional refund specific list items if any -->
    @endif
    @if($transaction->tnx_type=='transfer')
    <li>
        <div class="data-details-head">{{ $lang['transfer_detail'] }}</div>
        <div class="data-details-des">
            <span>{{ $transaction->payment_to }}
        </div>
    </li>
    @endif
    @if($transaction->details && ($transaction->tnx_type!='purchase')) 
    <!-- Additional details if any -->
    @endif
    @if($transaction->tnx_type=='referral')
    <li>
        <div class="data-details-head">{{ $lang['referral_bonus_for'] }}</div>
        <div class="data-details-des">
            <span>{{$referral->name}}</span>
        </div>
    </li>
    @endif
    @php 
        $trnx_extra = (is_json($transaction->plan, true) ?? $transaction->plan);
    @endphp
    @if(!empty($trnx_extra->message))
    <li>
        <div class="data-details-head">{{ $lang['refund_note'] }}</div>
        <div class="data-details-des">
            <span>{{ $trnx_extra->message }}</span>
        </div>
    </li>
    @endif
</ul>

@if($transaction->checked_time != NUll && $transaction->status == 'approved')
    <p class="text-primary fs-12 pt-3"><em>{!! __($lang['transaction_approved_message'], ['time'=>_date($transaction->checked_time)]) !!}</em></p>
@endif
@if($transaction->status == 'pending')
    <p class="text-primary fs-12 pt-3">{{ $lang['transaction_under_review'] }}</p>
@elseif($transaction->status == 'rejected' || $transaction->status == 'canceled')
    @if($transaction->tnx_type=='purchase')
        @if($transaction->checked_time != NUll)
            <p class="text-danger fs-12 pt-3">{!! __($lang['transaction_canceled_by_admin'], ['time'=>_date($transaction->checked_time)]) !!}</p>
        @elseif($transaction->status == 'canceled')
            <p class="text-danger fs-13 pt-3"><em>{{ $lang['transaction_self_canceled'] }}</em></p>
        @endif
    @elseif($transaction->tnx_type=='transfer')
        <p class="text-danger fs-13 pt-3">{!! __($lang['transfer_request_canceled'], ['time'=>_date($transaction->checked_time)]) !!}</p>
    @endif
@endif

