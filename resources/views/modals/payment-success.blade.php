<a href="{{ route('user.transactions') }}" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>
<div class="popup-body text-center">
    <div class="gaps-2x"></div>
    <div class="pay-status pay-status-success">
        <em class="ti ti-check success"></em>
    </div>
    <div class="gaps-2x"></div>
    <h3>{{$lang['were_received_your_payment']}}</h3>
    <p>{{$lang['thank_you_for_your_contribution']}}</p>
    <div class="gaps-2x"></div>
    <a href="{{ route('user.transactions') }}" class="btn btn-primary">{{$lang['view_transaction']}}</a>
    <div class="gaps-1x"></div>
</div>
