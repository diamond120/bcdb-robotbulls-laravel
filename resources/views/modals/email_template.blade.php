<div class="modal fade" id="edit-email-template" tabindex="-1">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <a href="#" class="modal-close" data-dismiss="modal" aria-label="Close"><em class="ti ti-close"></em></a>
            <div class="popup-body popup-body-md">
                <form action="{{ route('admin.ajax.settings.email.template.update') }}" class="validate-modern" method="POST" id="update_et">
                    @csrf
                    <input type="hidden" name="id" value="{{ $template->id }}">
                    <input type="hidden" name="slug" value="{{ $template->slug }}">
                    <h3 class="popup-title">{{$lang['edit']}} . {{ $template->name }} .{{$lang['template']}}</h3>
                    <div class="msg-box"></div>
                    <div class="input-item input-with-label">
                        <label for="name" class="input-item-label"> {{$lang['name']}}</label>
                        <div class="input-wrap">
                            <input name="name" id="name" class="input-bordered" value="{{ $template->name }}" type="text" readonly="readonly">
                        </div>
                        
                    </div>
                    <div class="input-item input-with-label">
                        <label for="subject" class="input-item-label">{{$lang['template_subject']}}</label>
                        <div class="input-wrap">
                            <input name="subject" id="subject" class="input-bordered" value="{{ $template->subject }}" type="text" required="">
                        </div>
                    </div>
                    <div class="input-item input-with-label">
                        <label for="greeting" class="input-item-label">{{$lang['template_greeting']}}</label>
                        <div class="input-wrap">
                            <input name="greeting" id="greeting" class="input-bordered" value="{{ $template->greeting }}" type="text" required="">
                        </div>
                    </div>
                    <div class="input-item  input-with-label">
                        <label for="message" class="input-item-label">$lang['template_content']</label>
                        <div class="input-wrap">
                            <textarea id="message" name="message" class="input-bordered input-textarea editor" >{{ $template->message }}</textarea>
                        </div>
                        @if($template->slug == 'users-reset-password-email')
                        <span class="input-note">
                            {{$lang['line_automatically_added']}} <strong>{{$lang['your_new_password_is']}} </strong>
                        </span>
                        @endif
                    </div>
                    @if(str_contains($template->slug, 'admin'))
                    <div class="input-item input-with-label">
                        <span class="input-item-label">{{$lang['send_notification_to_admin']}}</span>
                        <div class="input-wrap">
                            <input type="checkbox" class="input-switch" name="notify" value="1" {{ $template->notify == 1 ? 'checked' : '' }} id="notify">
                            <label for="notify">{{$lang['notify']}}</label>
                        </div>
                    </div>
                    @endif
                    <div class="input-item input-with-label">
                        <span class="input-item-label">{{$lang['email_footer']}}</span>
                        <div class="input-wrap">
                            <input type="checkbox" class="input-switch" name="regards" value="1" {{ $template->regards == 'true' ? 'checked' : '' }} id="regards">
                        </div>
                        <label for="regards">{{$lang['global']}}</label>
                        <span class="text-info">You can use these shortcut: [[site_name]], [[site_email]], [[user_name]] 
                            @if($template->slug == 'send-user-email')
                            , [[message]]
                            @endif
                            @if(starts_with($template->slug, 'order-'))
                            , [[order_id]], [[order_details]], [[token_symbol]], [[token_name]], [[payment_from]], [[payment_gateway]], [[payment_amount]], [[total_tokens]]
                            @endif
                        </span> <br>
                        <span class="text-danger">{{$lang['dont_use_markdown_character']}}</span>
                    </div>
                    <div class="gaps-1x"></div>
                    <button type="submit" class="btn btn-md btn-primary ucap">{{$lang['update']}} </button>
                </form>
            </div>
        </div>{{-- .modal-content --}}
    </div>{{-- .modal-dialog --}}
</div>{{-- Modal End --}}

<script type="text/javascript">
    (function($) {
        var $_form = $('form#update_et');
        if ($_form.length > 0) {
            ajax_form_submit($_form, true);
        }
    })(jQuery);
</script>