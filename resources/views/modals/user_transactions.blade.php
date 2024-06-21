@php
$total_equity = 0;

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

function daysDifferenceFromToday($datetimeString) {
    // Create a DateTime object from the provided string
    $givenDate = new DateTime($datetimeString);
    // Get the current date
    $now = new DateTime();
    // Calculate the difference in days
    $difference = $now->diff($givenDate);
    return $difference->days;
}
@endphp

<div class="modal fade" id="user-transaction" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-lg">
                <div class="card-head d-flex justify-content-between align-items-center">
                    <h3 class="popup-title">All transactions <em class="ti ti-angle-right"></em> <small class="tnx-id">{{ set_id($user->id) }}</small></h3>
                    <div class="d-flex align-items-center guttar-20px">
                        @if (auth()->user()->role == 'admin')
                        <div class="flex-col d-sm-block d-none">
                            <a href="/admin/transactions?search={{ $user->id }}&by=usr&filter=1" class="btn btn-light btn-sm btn-auto btn-primary" target="_blank"><span class="back">Detailed View</span> <em class="fas fa-arrow-right"></em></a>
                        </div>
                        @endif
                        <div class="flex-col d-sm-none">
                            <a href="/admin/transactions?search={{ $user->id }}&by=usr&filter=1" class="btn btn-light btn-icon btn-sm btn-primary" target="_blank"><em class="fas fa-arrow-right"></em></a>
                        </div>
                    </div>
                </div>
                <ul class="data-details-alt">
                    @forelse($transactions as $tnx)
                    @php
                    $today = new \DateTime();
                    $tnx_time = new \DateTime($tnx->tnx_time);
                    $diffTime = abs($today->getTimestamp() - $tnx_time->getTimestamp());
                    $diffTime = $diffTime/86400;
                    $current_equity = round($tnx->amount + $tnx->amount*121/365* $diffTime /100, 6);
                    $total_equity = $total_equity + $current_equity;
                    @endphp
                    <li class="text-dark row no-gutters justify-content-between">
                        
                        <div class="col-md col-sm-6"><strong class="text-dark">{{ ucfirst($tnx->tnx_type) }}</strong> <br> <span class="small"><a href="{{ route('admin.transactions.view', $tnx->id) }}" target="_blank">{{ $tnx->tnx_id }}</a></span></div>
                        <div class="col-md col-sm-6"><span class="text-dark">{{ ucfirst($tnx->plan) }}</span> <br> <span class="small">{{ ucfirst($tnx->duration) }}</span></div>
                        
                        <div class="col-md col-sm-6"><strong class="text-dark">{{ $tnx->total_tokens }}</strong></div>
                        
                        <div class="col-md col-sm-6"><strong class="text-dark">{{ $tnx->equity }} </strong> 
                            
                            
                        </div>
                        
                        @php
                        $end_date = (new DateTime('@' . $tnx->created_at))->add(new DateInterval('P'.((int) filter_var($tnx->duration, FILTER_SANITIZE_NUMBER_INT)).'M'));
                        $now = new DateTime();
                        $interval = $now->diff($end_date);
                        $class = '';
                        if ($interval->invert == 1 && $interval->days > 20) {
                            $class = 'black';
                        } elseif ($interval->invert == 0 && $interval->days > 2) {
                            $class = 'green';
                        } elseif ($interval->invert == 0 && ($interval->days == 2 || $interval->days == 1 || $interval->days == 0)) {
                            $class = 'blue';
                        } elseif ($interval->invert == 1 || ($interval->invert == 0 && $interval->days > 2)) {
                            $class = 'red';
                        }
                        @endphp
                        
                        <div class="col-12 col-lg">
                            <small class="text-light data-details-date">{{ _date($tnx->tnx_time) }}</small>
                            <small class="data-details-date" style="color:{{$class}}">{{ _date($end_date->format('Y-m-d H:i:s')) }}</small>
                        </div>  
                        
                    </li>
                    @empty
                    <li><div class="col-md col-sm-6"><strong class="text-dark">No approved transactions found</strong></div></li>
                    @endforelse
                </ul>{{-- .data-details --}}
<!--                <h3 class="popup-title">Total Expected Equity <small class="tnx-id">{{ $total_equity }}</small></h3>-->
            </div>
        </div>
    </div>
</div>