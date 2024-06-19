@extends('layouts.user')
@section('title', $lang['user_activity'])
@section('content')
<div class="content-area card">
    <div class="card-innr">
        @include('layouts.messages')
        <div class="card-head d-flex justify-content-between">
            <h4 class="card-title card-title-md account_activities_log">{{$lang['account_activities_log']}}</h4>
            <div class="float-right">
                <input type="hidden" id="activity_action" value="{{ route('user.ajax.account.activity.delete') }}">
                <a href="javascript:void(0)" class="btn btn-auto btn-primary btn-xs activity-delete clear_all" data-id="all">{{$lang['clear_all']}}</a>
            </div>
        </div>
        <div class="card-text">
            <p class="account_activities_log_under_text">{{$lang['recent_activities_text']}} </p>
        </div>
        <div class="gaps-1x"></div>
        <table class="data-table dt-init activity-table" data-items="10">
            <thead>
            <tr>
                <th class="activity-time account_activities_log_date"><span>{{$lang['date']}}</span></th>
                <th class="activity-device account_activities_log_device"><span>{{$lang['device']}}</span></th>
                <th class="activity-browser account_activities_log_browser"><span>{{$lang['browser']}}</span></th>
                <th class="activity-ip account_activities_log_ip"><span>{{$lang['ip']}}</span></th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody id="activity-log">
            @forelse($activities as $activity)
                @php
                    $browser = explode('/', $activity->browser);
                    $device = explode('/', $activity->device);
                    $ip = ($activity->ip == '::1' || $activity->ip == '127.0.0.1') ? 'localhost' : $activity->ip ;
                @endphp
                <tr class="data-item activity-{{ $activity->id }}">
                    <td class="data-col">{{ _date($activity->created_at) }}</td>
                    <td class="data-col d-none d-sm-table-cell">{{ end($device) }}</td>
                    <td class="data-col">{{ $browser[0] }}</td>
                    <td class="data-col">{{ $ip }}</td>
                    <td class="data-col"><a href="javascript:void(0)" class="activity-delete fs-16" data-id="{{ $activity->id }}" title="Delete"><em class="ti-trash"></em></a></td>
                </tr>
            @empty

            @endforelse
            </tbody>
        </table>
    </div>{{-- .card-innr --}}
</div>{{-- .card --}}
@endsection


@push('footer')

<script type="text/javascript">

    var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", "Jan", "Feb", "Mar", "Apr", "May", "Jun",
        "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
    ];

    var robot_last_activated = "<?=$user->robot_last_activated?>";
    var d = new Date(robot_last_activated);

    if (d == undefined || d == "Invalid Date") {
        d = new Date();
    }

    var user_balance_prediction = <?=$user->tokenBalance?>;
    var user_equity = <?=$user->equity?>;
    
    var prediction_array = "<?=$user->prediction_array?>";


    var today = new Date().getTime();
    var today_d = new Date();
    var today_l = new Date(new Date().setDate(new Date().getDate() - 1));
    var today_minus_15 = new Date().getTime() - (15 * 24 * 60 * 60 * 1000);
    var n = new Date(today);

    

    var user_labels = [monthNames[n.getMonth()], monthNames[n.getMonth() + 1], monthNames[n.getMonth() + 2], monthNames[n.getMonth() + 3], monthNames[n.getMonth() + 4], monthNames[n.getMonth() + 5], monthNames[n.getMonth() + 6], monthNames[n.getMonth() + 7], monthNames[n.getMonth() + 8], monthNames[n.getMonth() + 9], monthNames[n.getMonth() + 10], monthNames[n.getMonth() + 11]];
     
    var user_data = [];
    
    prediction_array = prediction_array.split(",");
    
    var pred_i;
    for(pred_i = 0; pred_i < 12; pred_i++) {
        user_data.push(prediction_array[pred_i]);
    }
   

</script>

<script src="{{ asset('assets/js/admin.chart.js') }}"></script>

@endpush