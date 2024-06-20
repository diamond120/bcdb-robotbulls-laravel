@extends('layouts.admin')
@section('title', 'Transaction Details')

@php 
    function getDaysFromPeriod($period) {
        // Convert string into a date interval
        $interval = DateInterval::createFromDateString($period);
        // Get current date
        $now = new DateTime();
        // Calculate date in past
        $past = clone $now;
        $past->sub($interval);
        // Calculate difference in days
        $difference = $now->diff($past);
        return $difference->days;
    }
@endphp

@section('content')
<div class="page-content">
    <div class="container">
        <div class="card content-area">
            <div class="card-innr">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h4 class="card-title mb-0">Transaction Details <em class="ti ti-angle-right fs-14"></em> <strong><a href="{{ route('admin.users.view', [$trnx->tnxUser->id, 'details'] ) }}" target="_blank">{{ $trnx->tnxUser->name }}</a></strong><em class="ti ti-angle-right fs-14"></em> <small class="tnx-id">{{ $trnx->tnx_id }}</small>
                    @if (auth()->user()->role == 'admin')
                        <a href="#" class="btn-sm mt-1" data-toggle="modal" data-target="#changeTnx">
                            <em class="fs-14 ti ti-write"></em>
                        </a>
                    @endif
                    </h4>
                    <div class="d-flex align-items-center guttar-20px">
                        <div>
                        <a href="{{ (url()->previous()) ? url()->previous() : route('admin.transactions') }}" class="btn btn-sm btn-auto btn-primary"><em class="fas fa-arrow-left"></em><span class="d-sm-inline-block d-none">Back</span></a>
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <div>
                        <a href="/admin/transactions?search={{ $trnx->tnxUser->id }}&by=usr&filter=1" class="btn btn-sm btn-auto btn-primary" target="_blank"><span class="d-sm-inline-block d-none">All Transactions</span> <em class="fas fa-arrow-right"></em></a>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="gaps-1-5x"></div>
                <div class="data-details d-md-flex">
                    <div class="fake-class">
                        <span class="data-details-title">Transaction Date</span>
                        <span class="data-details-info">{{ _date($trnx->tnx_time) }}</span>
                    </div>
                    <div class="fake-class">
                        <span class="data-details-title">Transaction Status</span>
                        <span class="badge badge-{{ __status($trnx->status, 'status') }} ucap">{{ $trnx->status }}</span>
                    </div>
                    <div class="fake-class">
                        <span class="data-details-title">Invested Amount</span>
                        <span class="data-details-info"><strong>{{ $trnx->amount }}</strong></span>
                    </div>
                    <div class="fake-class">
                        <span class="data-details-title">Equity</span>
                        <span class="data-details-info"><strong>{{ $trnx->equity }}</strong></span>
                    </div>
                </div>
                <div class="gaps-3x"></div>
                <h6 class="card-sub-title">Transaction Info</h6>
                <ul class="data-details-list">
                    <li>
                        <div class="data-details-head">User</div>
                        <div class="data-details-des"><strong><a href="{{ route('admin.users.view', [$trnx->tnxUser->id, 'details'] ) }}" target="_blank">{{ $trnx->tnxUser->name }}</a> ({{$trnx->tnxUser->id}}) </strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">Mobile</div>
                        <div class="data-details-des"><strong>{{ $trnx->tnxUser->mobile }}</strong></div>
                    </li>
                    
                    <li>
                        <div class="data-details-head">Transaction Type</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->tnx_type) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">Plan</div>
                        <div class="data-details-des d-block"><strong>{{ ucfirst($trnx->plan) }}</strong>
                          
                        </div>
                    </li>
                    <li>
                        <div class="data-details-head">Duration</div>
                        <div class="data-details-des d-block"><strong id="duration">{{ ucfirst($trnx->duration) }}</strong>
                        </div>
                    </li>
                    <li>
                        <div class="data-details-head">Payment Gateway</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->payment_method) }} <small>- {{ gateway_type($trnx->payment_method) }}</small></strong></div>
                    </li>
                    
                </ul>{{-- .data-details --}}
                <div class="gaps-3x"></div>
                
                @if (auth()->user()->role == 'admin')
                <h6 class="card-sub-title">Details</h6>
                <ul class="data-details-list">
                    

                    
                    <li>
                        <div class="data-details-head">ID</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->id) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">tnx_id</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->tnx_id) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">tnx_type</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->tnx_type) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">tnx_time</div>
                        <div class="data-details-des"><strong id="tnx_time">{{ ucfirst($trnx->tnx_time) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">tokens</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->tokens) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">bonus_on_base</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->bonus_on_base) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">bonus_on_token</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->bonus_on_token) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">total_bonus</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->total_bonus) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">tnx_type</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->tnx_type) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">total_tokens</div>
                        <div class="data-details-des"><strong id="fiat">{{ ucfirst($trnx->total_tokens) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">stage</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->stage) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">user</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->user) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">amount</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->amount) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">receive_amount</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->receive_amount) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">receive_currency</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->receive_currency) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">base_amount</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->base_amount) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">base_currency</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->base_currency) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">base_currency_rate</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->base_currency_rate) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">currency</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->currency) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">currency_rate</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->currency_rate) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">all_currency_rate</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->all_currency_rate) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">wallet_address</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->wallet_address) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">payment_method</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->payment_method) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">payment_id</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->payment_id) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">payment_to</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->payment_to) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">checked_by</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->checked_by) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">added_by</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->added_by) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">checked_time</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->checked_time) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">details</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->details) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">extra</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->extra) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">status</div>
                        <div class="data-details-des d-block"><strong>{{ ucfirst($trnx->status) }}</strong>
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_trnx_btn" value="status_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none trnx_form status_form" action="{{ route('admin.ajax.transactions.trnx_edit_status') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $trnx->id ? $trnx->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des status_value status_value_input" style="width:100%" value="{{ $trnx->status }}" name="status">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>
                    <li>
                        <div class="data-details-head">dist</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->dist) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">refund</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->refund) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">created_at</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->created_at) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">updated_at</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->updated_at) }}</strong></div>
                    </li>
                    <li>
                        <div class="data-details-head">Plan</div>
                        <div class="data-details-des d-block"><strong>{{ ucfirst($trnx->plan) }}</strong>
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_trnx_btn" value="plan_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none trnx_form plan_form" action="{{ route('admin.ajax.transactions.trnx_edit_plan') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $trnx->id ? $trnx->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des plan_value plan_value_input" style="width:100%" value="{{ ucfirst($trnx->plan) }}" name="plan">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>
                    <li>
                        <div class="data-details-head">Duration</div>
                        <div class="data-details-des d-block"><strong>{{ ucfirst($trnx->duration) }}</strong>
                            @if (auth()->user()->role == 'admin')
                            <a href="#" class="ml-3 edit_trnx_btn" value="duration_form">
                                <em class="fs-14 ti ti-write"></em>
                            </a>
                            @endif
                        </div>
                        @if (auth()->user()->role == 'admin')
                        <form class="d-none trnx_form duration_form" action="{{ route('admin.ajax.transactions.trnx_edit_duration') }}" method="POST">
                            @csrf
                            <input class="d-none" value="{!! $trnx->id ? $trnx->id : '&nbsp;' !!}" name="id">
                            <input class="data-details-des duration_value duration_value_input" style="width:100%" value="{{ ucfirst($trnx->duration) }}" name="duration">
                            <button type="submit" style="border:none;background: transparent;cursor: pointer; padding: 0 15px;"><em class="fs-14 ti ti-save"></em></button>
                            <button style="border:none;background: transparent;cursor: pointer; padding: 0 15px;" class="cancel"><em class="fs-14 ti ti-close"></em></button>
                        </form>
                        @endif
                    </li>
                    <li>
                        <div class="data-details-head">equity</div>
                        <div class="data-details-des"><strong>{{ ucfirst($trnx->equity) }}</strong></div>
                    </li>
                    
                </ul>{{-- .data-details --}}
                <div class="gaps-0-5x"></div>
                @endif
            </div>
        </div>{{-- .card --}}
    </div>{{-- .container --}}
</div>{{-- .page-content --}}
@endsection

@section('modals')
@if (auth()->user()->role == 'admin')
<div class="modal fade" id="changeTnx">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">Manually Add Funds</h3>
                <form action="{{ route('admin.ajax.transactions.change') }}" method="POST" class="validate-modern" id="add_token" autocomplete="off">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Tranx Type</label>
                                <div class="input-wrap">
                                    <select name="type" class="select select-block select-bordered" required>
                                        <option value="purchase">Purchase</option>
                                        <option value="bonus">Bonus</option>
                                        <option value="demo">Demo</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label w-sm-60">
                                <label class="input-item-label">Tranx Date</label>
                                <div class="input-wrap">
                                    <input class="input-bordered date-picker" required="" type="text" name="tnx_date" value="{{ date('m/d/Y') }}">
                                    <span class="input-icon input-icon-right date-picker-icon"><em class="ti ti-calendar"></em></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">User</label>
                                <div class="input-wrap">
                                    <select name="user" required="" class="select-block select-bordered" data-dd-class="search-off">
                                        <option value="{{ $trnx->tnxUser->id }}">{{ $trnx->tnxUser->id }}</option>
                                    </select>
                                    <span class="input-note">Select account to add fund.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Token for Stage</label>
                                <div class="input-wrap">
                                    <select name="stage" class="select select-block select-bordered" required>
                                        @if($trnx->ico_stage)
                                        <option value="{{ $trnx->ico_stage->name }}"> {{ $trnx->ico_stage->name }} </option>
                                        @endif
                                    </select>
                                    <span class="input-note">Select Stage where from adjust tokens.</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Payment Gateway</label>
                                <div class="input-wrap">
                                    <select name="payment_method" class="select select-block select-bordered">
                                        <option value="Coinpayments">Coinpayments</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Transaction</label>
                                <div class="input-wrap">
                                    <select name="id" class="select select-block select-bordered">
                                        <option value="{{ $trnx->id }}">{{ $trnx->id }}</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Payment Amount</label>
                                <div class="row flex-n guttar-10px">
                                    <div class="col-7">
                                        <div class="input-wrap">
                                            <input class="input-bordered" type="number" name="amount" placeholder="Optional">
                                        </div>
                                    </div>
                                    <div class="col-5">
                                        <div class="input-wrap">
                                            <select name="currency" class="select select-block select-bordered">
                                               
                                                <option value="eur">EUR</option>
                                                <option value="chf">CHF</option>
                                                <option value="gbp">GBP</option>
                                                <option value="usd">USD</option>
                                                <option value="btc">BTC</option>
                                                <option value="eth">ETH</option>
                                                <option value="ltc">LTC</option>
                                                <option value="sol">SOL</option>
                                                <option value="cdf">CDF</option>
                                                <option value="cfa">cfa</option>
                                              
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <span class="input-note">Amount calculate based on stage if leave blank.</span>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Duration</label>
                                <div class="input-wrap">
                                    <select name="duration" class="select select-block select-bordered">
                                        <option value="3 Month">3 Month</option>
                                        <option value="6 Month">6 Month</option>
                                        <option value="12 Month">12 Month</option>
                                        <option value="">None</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Plan</label>
                                <div class="input-wrap">
                                    <select name="plan" class="select select-block select-bordered">
                                        <option value="General Bull">General Bull</option>
                                        <option value="Stocks Bull">Stocks Bull</option>
                                        <option value="Crypto Bull">Crypto Bull</option>
                                        <option value="Ecological Bull">Ecological Bull</option>
                                        <option value="NFT Bull">NFT Bull</option>
                                        <option value="Metaverse Bull">Metaverse Bull</option>
                                        <option value="BTC Bull">BTC Bull</option>
                                        <option value="ETH Bull">ETH Bull</option>
                                        <option value="AI Bull">AI Bull</option>
                                        <option value="Bonus">Bonus</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Status</label>
                                <div class="input-wrap">
                                    <select name="status" class="select select-block select-bordered">
                                        <option value="approved">Approved</option>
                                        <option value="pending">Pending</option>
                                        <option value="canceled">Canceled</option>
                                        <option value="deleted">Deleted</option>
                                    </select>
                                </div>
                                <span class="input-note">Select method for this transaction.</span>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Payment Address</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="wallet_address" placeholder="Optional">
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">Number of Token</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="number" name="total_tokens" max="{{ active_stage()->max_purchase }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label d-none d-sm-inline-block">&nbsp;</label>
                                <div class="input-wrap input-wrap-checkbox mt-sm-2">
                                    <input id="auto-bonus" class="input-checkbox input-checkbox-md" type="checkbox" name="bonus_calc">
                                    <label for="auto-bonus"><span>Bonus Adjusted from Stage</span></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <div class="gaps-3x"></div>
                    <div class="note note-plane note-light">
                        <em class="fas fa-info-circle"></em>
                        <p>If checked <strong>'Bonus Adjusted'</strong>, it will applied bonus based on selected stage (only for Purchase type).</p>
                    </div>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>
@endif
{{-- Modal End --}}

<script>

    document.addEventListener('DOMContentLoaded', function() {
    
    // Get all the edit buttons
    var editButtons = document.querySelectorAll('.edit_trnx_btn');

    // Loop through each button and attach the corresponding event listener
    editButtons.forEach(function(editButton) {
        // Get the form for the current button
        var form = document.querySelector( '.' + editButton.getAttribute("value") );

        console.log(editButton);
        console.log(form);
        
        // When the edit button is clicked
        editButton.addEventListener('click', function(e) {
            console.log("click");
            e.preventDefault();

            // Display the form (change from d-none to d-flex)
            form.classList.remove('d-none');
            form.classList.add('d-flex');
            editButton.parentElement.classList.remove('d-block');
            editButton.parentElement.classList.add('d-none');
        });

        // Get the cancel button inside the form
        var cancelButton = form.querySelector('.cancel');

        // When the cancel button is clicked
        cancelButton.addEventListener('click', function(e) {
            e.preventDefault();

            // Hide the form (change from d-flex to d-none)
            form.classList.remove('d-flex');
            form.classList.add('d-none');
            editButton.parentElement.classList.remove('d-none');
            editButton.parentElement.classList.add('d-block');
        });
    });
});

</script>


<script>
    
    function monthStringToDays(monthString) {
        // Extract the number from the string using a regex
        const numMonthsMatch = monthString.match(/\d+/);

        // If no number is found, return NaN or handle it accordingly
        if (!numMonthsMatch) {
            return NaN;
        }

        // Parse the string to get the number of months
        const numMonths = parseInt(numMonthsMatch[0], 10);

        // Get the current date
        const currentDate = new Date();

        // Create a new date object that's `numMonths` months in the future
        const futureDate = new Date(currentDate.getFullYear(), currentDate.getMonth() + numMonths, currentDate.getDate());

        // Calculate the difference between the two dates in milliseconds
        const diffMs = futureDate - currentDate;

        // Convert milliseconds to days
        const days = diffMs / (1000 * 60 * 60 * 24);

        return Math.round(days);
    }


    
    function getDaysDifference(dateString) {
        const date = new Date(dateString);
        const today = new Date();

        const timeDiff = today - date; // This will be in milliseconds
        const daysDiff = timeDiff / (1000 * 60 * 60 * 24); // Convert milliseconds to days

        return daysDiff;
    }
    
   
    
</script>

@endsection

