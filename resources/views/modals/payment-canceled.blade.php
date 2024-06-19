@php
include "/home/robotbq/app.robotbulls.com/config_u.php";
@endphp

<a href="#" class="modal-close" data-dismiss="modal"><em class="ti ti-close"></em></a>

<div class="popup-body text-center">
    <div class="gaps-2x"></div>
    <div class="pay-status pay-status-error">
        <em class="ti ti-alert danger"></em>
    </div>
    <div class="gaps-2x"></div>
    <h3>{{$lang['payment_canceled']}}</h3>
    <p>{!! __( $lang['you_have_canceled_your_transfer'],[ 'orderid' => '<strong>'.$tnxns->tnx_id.'</strong>']) !!}</p> 
    <div class="gaps-2x"></div>
    <a href="javascript:void(0)" class="btn btn-danger close-modal">{{$lang['close']}}</a>  
    <div class="gaps-1x"></div>
</div>

<script type="text/javascript">
    (function($) {
        $('.close-modal, .modal-close').on('click', function(e){
            e.preventDefault();
            var $link = $(this).attr('href');
            $(this).parents('.modal').modal('hide');
            window.location.reload();
        });
    })(jQuery);
</script>