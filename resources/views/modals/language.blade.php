<div class="modal fade" id="language-edit" tabindex="-1">
    <div class="modal-dialog modal-dialog-md modal-dialog-centered">
        <div class=" sd modal-content">
            <div class="popup-body popup-body-md">
                <h3 class="popup-title">{{$lang['edit_language']}}</h3>
                <div class="gaps-1x"></div>
                <form class="validate-modern lang-form-update _reload" action="{{ route('admin.ajax.lang.action') }}" method="POST">
                    <div class="row">
                        <div class="col-12">
                            <div class="input-item">
                                <input class="input-switch input-switch-sm" name="status" type="checkbox"{{ $lang->status == 1 ? ' checked' : '' }}{{ ($lang->code=='en') ? ' disabled' : ''}} id="lang-status">
                                <label for="lang-status">{{$lang['enable_or_disable_the_language']}}</label>
                            </div>
                        </div>
                        <div class="col-sm-6">  
                            <div class="input-item input-with-label">
                                <label class="input-item-label">{{$lang['language_name']}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="name" value="{{ $lang->name }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">{{$lang['code_name']}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="code" value="{{ $lang->code }}" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">{{$lang['language_label']}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="label" value="{{ $lang->label }}" required>
                                </div>
                                <span class="input-note">{{$lang['eg_english']}}</span>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="input-item input-with-label">
                                <label class="input-item-label">{{$lang['short_name']}}</label>
                                <div class="input-wrap">
                                    <input class="input-bordered" type="text" name="short" value="{{ $lang->short }}" required>
                                </div>
                                <span class="input-note">{{$lang['eg_en']}}</span>
                            </div>
                        </div>
                        <div class="col-12">
                            @csrf
                            <input type="hidden" name="actions" value="update">
                            <input type="hidden" name="lang" value="{{ $lang->code }}">
                            <button type="submit" class="btn btn-primary">{{$lang['update']}}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    (function($){
        var $lang_update = $(".lang-form-update");
        if ($lang_update.length > 0) {
            ajax_form_submit($lang_update);
        }
    })(jQuery);
</script>