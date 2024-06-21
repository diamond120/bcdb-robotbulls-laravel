@extends('layouts.admin')
@section('title', 'Admin Dashboard')
@section('content')
@php 
$base_cur = base_currency();
$sm_class = strlen($stage->stage->total_tokens) > 12 ? ' sm' : '';
@endphp
<div class="page-content">
	<div class="container">
        @include('vendor.notice')
        @include('layouts.messages')
		<div class="row">
			<div class="col-lg-4 col-md-6">
                <div class="card height-auto">
                    <div class="card-innr">
                        <div class="tile-header">
                            <h6 class="tile-title">Since last week</h6>
                        </div>
                        <div class="tile-data">
                            <span class="tile-data-number{{ $sm_class }}">{{ to_num($stage->trnxs->last_week, 0, ',', false) }}</span>
<!--                            <span class="tile-data-status tile-data-active" title="Last week" data-toggle="tooltip" data-placement="right">since last week</span>-->
                        </div>
<!--                        <div class="tile-footer">-->
                            <!-- <div class="tile-recent">
                                <span class="tile-recent-number">{{ to_num($stage->trnxs->last_week, 0, ',', false) }}</span>
                                <span class="tile-recent-text">since last week</span>
                            </div> -->
<!--
                            <div class="tile-link">
                                <a href="{{ route('admin.stages') }}" class="link link-thin link-ucap link-dim">View</a>
                            </div>
                        </div>
-->
                        
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card height-auto">
                    <div class="card-innr">
                        <ul class="tile-nav nav">
<!--                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view-kyc-countries">Countries</a></li>-->
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#view-kycs">KYC</a></li>
                        	<li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#view-users">User</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="view-users">
                                <div class="tile-header">
                                    <h6 class="tile-title">Total Users</h6>
                                </div>
                                <div class="tile-data">
                                    <span class="tile-data-number{{ $sm_class }}">{{ to_num($users->all, 0, ',', false) }}</span>
                                    <span class="tile-data-status tile-data-active" title="Verified" data-toggle="tooltip" data-placement="right">{{ $users->verified }}%</span>
                                </div>
                                <div class="tile-footer">
                                    <div class="tile-recent">
                                        <span class="tile-recent-number">{{ to_num($users->last_week, 0, ',', false) }}</span>
                                        <span class="tile-recent-text">since last week</span>
                                    </div>
                                    <div class="tile-link">
                                        <a href="{{ route('admin.users') }}" class="link link-thin link-ucap link-dim">View</a>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="view-kycs">
                                <div class="tile-header">
                                    <h6 class="tile-title">Total KYC</h6>
                                </div>
                                <div class="tile-data">
                                    <span class="tile-data-number{{ $sm_class }}">{{ to_num($users->kyc_submit, 0, ',', false) }}</span>
                                    <span class="tile-data-status tile-data-active" title="Approved" data-toggle="tooltip" data-placement="right">{{ $users->kyc_approved }}%</span>
                                </div>
                                <div class="tile-footer">
                                    <div class="tile-recent">
                                        <span class="tile-recent-number">{{ to_num($users->kyc_last_week, 0, ',', false) }}</span>
                                        <span class="tile-recent-text">since last week</span>
                                    </div>
                                    <div class="tile-link">
                                        <a href="{{ route('admin.kycs') }}" class="link link-thin link-ucap link-dim">View</a>
                                    </div>
                                </div>
                            </div>
                            
<!--
                            <div class="tab-pane fade position-relative " id="view-kyc-countries" style="z-index:0">
                                <div class="phase-block guttar-20px justify-content-center">
                                    <div class="fake-class">
                                        <div class="chart-phase">
                                            <div class="phase-status-total">
                                                <span class="lead{{ $sm_class }}">KYC Countries</span>
                                            </div>
                                            <div class="chart-tokensale-s2">
                                                <canvas id="phaseStatus"></canvas>
                                            </div>
                                        </div>
                                    </div>   
                                </div>
                            </div>
-->
                            
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="token-statistics card card-token height-auto">
                    <div class="card-innr">
                        <ul class="tile-nav nav">
                             <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#equity-details">Details</a></li> 
                             <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#equity-overview">Overview</a></li> 
                        </ul>
                        <div class="tab-content">
                            <div class="token-balance token-balance-s3 tab-pane fade show active" id="equity-overview">
                                <div class="token-balance-text">
                                    <h6 class="card-sub-title">AMOUNT COLLECTED</h6>
                                    <span>Equity: </span> 
                                    <span class="lead">{{ to_num($total_equity, 'auto', ',') }}  {{ strtoupper($base_cur) }} </span>
                                    <br>
                                    <span>Tokens: </span> 
                                    <span class="lead">{{ to_num($total_tokens, 'auto', ',') }}  {{ strtoupper($base_cur) }} </span>

                                    <div class="">
                                        <span class="tile-recent-number">{{ to_num($users_with_tokens) }}</span>
                                        <span class="tile-recent-text">Users with equity</span>
                                    </div>
                                </div>
                            </div>
                            <div class="token-balance token-balance-s3 tab-pane fade" id="equity-details">
                                <ul>
                                    <li>{{ to_num($users_0_1000) }}: 0-1k <span class="small">{{to_num(round($balance_sum_0_1000), 'auto', ',')}}, {{to_num(round($equity_sum_0_1000), 'auto', ',')}}</span></li>
                                    <li>{{ to_num($users_1001_5000) }}: 1k-5k <span class="small">{{to_num(round($balance_sum_1001_5000), 'auto', ',')}}, {{to_num(round($equity_sum_1001_5000), 'auto', ',')}}</span></li>
                                    <li>{{ to_num($users_5001_10000) }}: 5k-10k <span class="small">{{to_num(round($balance_sum_5001_10000), 'auto', ',')}}, {{to_num(round($equity_sum_5001_10000), 'auto', ',')}}</span></li>
                                    <li>{{ to_num($users_10001_50000) }}: 10k-50k <span class="small">{{to_num(round($balance_sum_10001_50000), 'auto', ',')}}, {{to_num(round($equity_sum_10001_50000), 'auto', ',')}}</span></li>
                                    <li>{{ to_num($users_50001_100000) }}: 50k-100k <span class="small">{{to_num(round($balance_sum_50001_100000), 'auto', ',')}}, {{to_num(round($equity_sum_50001_100000), 'auto', ',')}}</span></li>
                                    <li>{{ to_num($users_100001_500000) }}: 100k-500k <span class="small">{{to_num(round($balance_sum_100001_500000), 'auto', ',')}}, {{to_num(round($equity_sum_100001_500000), 'auto', ',')}}</span></li>
                                    <li>{{ to_num($users_500000_1000000) }}: 500k-1M <span class="small">{{to_num(round($balance_sum_500000_1000000), 'auto', ',')}}, {{to_num(round($equity_sum_500000_1000000), 'auto', ',')}}</span></li>
                                    <li>{{ to_num($users_1000001_more) }}: 1M+ <span class="small">{{to_num(round($balance_sum_1000001_more), 'auto', ',')}}, {{to_num(round($equity_sum_1000001_more), 'auto', ',')}}</span></li>
                                    <li>TOTAL: {{to_num(round($balance_sum_total), 'auto', ',')}}, {{to_num(round($equity_sum_total), 'auto', ',')}}</li>
                                </ul>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="token-transaction card card-full-height">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title card-title-sm">Recent Transaction</h4>
                            <div class="card-opt">
                                <a href="{{ route('admin.transactions') }}" class="link ucap">View ALL <em class="fas fa-angle-right ml-2"></em></a>
                            </div>
                        </div>
                        <table class="table tnx-table">
                            <tbody>
                            	@forelse($trnxs->all as $tnx)
                                <tr>
                                    <td>
                                        <h5 class="lead mb-1">{{ $tnx->tnxUser()->name}}</h5>
                                        <span class="sub">{{ _date($tnx->tnx_time) }}</span>
                                    </td>
                                    <td class="d-none d-sm-table-cell">
                                        <h5 class="lead mb-1{{ ($tnx->tnx_type=='refund') ? ' text-danger' : '' }}">
                                            {{ (starts_with($tnx->total_tokens, '-') ? '' : '+').round(to_num($tnx->total_tokens, 'max'),2) }}
                                        </h5>
                                        <span class="sub ucap">{{ round(to_num($tnx->amount, 'max'),2).' '.$tnx->receive_currency }}</span>
                                    </td>
                                    <td class="text-right">
                                        <div class="data-state data-state-{{ __status($tnx->status, 'icon') }}"></div>
                                    </td>
                                </tr>
                                @empty
								<tr class="data-item text-center">
									<td class="data-col" colspan="4">No available transaction!</td>
								</tr>
								@endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-7">
                <div class="token-sale-graph card card-full-height">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title card-title-sm">Sales Graph</h4>
                            <div class="card-opt">
                                <a href="{{ url()->current() }}" class="link ucap link-light toggle-tigger toggle-caret">{{ isset($_GET['chart']) ? $_GET['chart'] : 30 }} Days</a>
								<div class="toggle-class dropdown-content">
									<ul class="dropdown-list">
										<li><a href="{{ url()->current() }}?chart=7">7 Days</a></li>
										<li><a href="{{ url()->current() }}?chart=15">15 Days</a></li>
										<li><a href="{{ url()->current() }}?chart=30">30 Days</a></li>
										<li><a href="{{ url()->current() }}?chart=90">90 Days</a></li>
										<li><a href="{{ url()->current() }}?chart=160">160 Days</a></li>
										<li><a href="{{ url()->current() }}?chart=360">360 Days</a></li>
									</ul>
								</div>
                            </div>
                        </div>
                        <div class="chart-tokensale chart-tokensale-long">
                            <canvas id="tknSale"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5">
                <div class="reg-statistic-graph card card-full-height">
                    <div class="card-innr">
                        <div class="card-head has-aside">
                            <h4 class="card-title card-title-sm">Registration Statistics</h4>
                            <div class="card-opt">
                                <a href="{{ url()->current() }}" class="link ucap link-light toggle-tigger toggle-caret">{{ isset($_GET['user']) ? $_GET['user'] : 30 }} Days</a>
                                <div class="toggle-class dropdown-content">
                                    <ul class="dropdown-list">
                                        <li><a href="{{ url()->current() }}?user=7">7 Days</a></li>
                            			<li><a href="{{ url()->current() }}?user=15">15 Days</a></li>
                            			<li><a href="{{ url()->current() }}?user=30">30 Days</a></li>
                            			<li><a href="{{ url()->current() }}?user=60">60 Days</a></li>
                            			<li><a href="{{ url()->current() }}?user=90">90 Days</a></li>
                            			<li><a href="{{ url()->current() }}?user=180">180 Days</a></li>
                            			<li><a href="{{ url()->current() }}?user=360">360 Days</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="chart-statistics mb-0">
                            <canvas id="regStatistics"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="card card-full-height">
                    <div class="card-innr">
                        <div class="phase-block guttar-20px justify-content-center">

                            <div class="fake-class">
                                <div class="chart-phase">
                                    <div class="phase-status-total">
                                        <span class="lead{{ $sm_class }}">KYC Countries</span>
                                    </div>
                                    <div class="chart-tokensale-s2">
                                        <canvas id="phaseStatus"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- .card --}}
            </div>
            
            <div class="col-lg-4">
                <div class="card card-full-height">
                    <div class="card-innr">
                        <div class="phase-block guttar-20px justify-content-center">

                            <div class="fake-class">
                                <div class="chart-phase">
                                    <div class="phase-status-total">
                                        <span class="lead{{ $sm_class }}">User Countries</span>
                                    </div>
                                    <div class="chart-tokensale-s2">
                                        <canvas id="userCountries"></canvas>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>{{-- .card --}}
            </div>
		</div>{{-- .row --}}
	</div>{{-- .container --}}
</div>{{-- .page-content --}}

@endsection

@push('footer')
<script type="text/javascript">
	var tnx_labels = [<?=$trnxs->chart->days?>], tnx_data = [<?=$trnxs->chart->data?>], 
    user_labels = [<?=$users->chart->days?>], user_data = [<?=$users->chart->data?>],
    theme_color = {base:"<?=theme_color('base')?>", text: "<?=theme_color('text')?>", heading: "<?=theme_color('heading')?>"};
//	var phase_data = [{{ round($stage->stage->soldout, 2) }}, {{ (($stage->stage->total_tokens - $stage->stage->soldout) > 0 ? round(($stage->stage->total_tokens - $stage->stage->soldout), 2) : 0) }}];
//    console.log(phase_data);
    
    //kyc countries
    var countries = JSON.parse(`{!! $kycs !!}`);
    let counts = countries.reduce((acc, country) => ((acc[country] = (acc[country] || 0) + 1), acc), {});
    countries.sort((a, b) => counts[b] - counts[a]);
    var phase_labels = [...new Set(countries)];
    var phase_dataObj = countries.reduce(function (acc, curr) {
      return acc[curr] ? acc[curr]++ : acc[curr] = 1, acc
    }, {});
    var phase_data = Object.values(phase_dataObj);
    console.log(phase_labels);
    console.log(phase_data);


</script>

<script src="{{ asset('assets/js/superadmin.chart.js') }}"></script>
@endpush