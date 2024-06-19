<a href="{{ route('user.token') }}" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>
<div class="popup-body text-center">
    <div class="gaps-2x"></div>
    <div class="pay-status pay-status-success">
        <em class="ti ti-check warning"></em>
    </div>
    <div class="gaps-2x"></div>
    <h3>{{$lang['were_reviewing_your_payment']}}</h3>
    <p>{{$lang['well_review_your_transaction']}}</p>
    <div class="gaps-2x"></div>
    <a href="{{ route('user.transactions') }}" class="btn btn-primary">{{ $lang['view_transaction'] }}</a>
    <div class="gaps-1x"></div>
</div>
