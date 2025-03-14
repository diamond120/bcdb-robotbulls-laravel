function winwidth() {
    return $(window).width()
}

function ajax_form_submit(t = $(".validate-form"), e = !0, a = "ti ti-alert", n = !0) {
    t.find(".form-progress-btn");
    t.each(function () {
        var t = $(this);
        t.validate({
            errorElement: "span",
            errorClass: "input-border-error error",
            submitHandler: function (i) {
                $(i).ajaxSubmit({
                    beforeSubmit: function () {
                        return true;
                        if (!$(i).tokenValidity()) return setTimeout(function () {
                            show_toast("error", "Unable to perform!\n", "ti ti-na")
                        }, 400), !1
                    },
                    dataType: "json",
                    success: function (o) {
                        var s = "success" == o.msg ? "ti ti-check" : a;
                        if (btn_actived(t.find("button.save-disabled"), !1), show_toast(o.msg, o.message, s), "success" == o.msg)
                            if (!0 === e && $(i).clearForm(), bs_modal_toggle(t, n), o.link) setTimeout(function () {
                                window.location.href = o.link
                            }, 1200);
                            else {
                                var l = $(t);
                                1 == n && l.hasClass("_reload") && setTimeout(function () {
                                    window.location.reload()
                                }, 1200)
                            }
                        else cl(o)
                    },
                    error: function (t, e, a) {
                        cl(t), cl(a), show_toast("warning", "Something is wrong!\n(" + a + ")", "ti ti-na"), cl("Ajax error!!")
                    }
                })
            },
            invalidHandler: function (t, e) {}
        })
    })
}

function post_submit(t, e, a = null) {
    t && e ? $.post(t, e).done(function (t) {
        if (!$(form).tokenValidity()) return show_toast("error", "Something is wrong!", "ti ti-alert"), !1;
        void 0 !== t.modal && t.modal ? a && (a.html(t.modal), init_inside_modal(), a.children(".modal").length > 0 && a.children(".modal").modal("show")) : t.message && (show_toast(t.msg, t.message, t.icon ? t.icon : "ti ti-info-alt"), "success" == t.msg && (void 0 !== t.link && t.link ? setTimeout(function () {
            window.location.href = t.link
        }, 500) : void 0 !== t.reload && t.reload && setTimeout(function () {
            window.location.reload()
        }, 500)))
    }).fail(function (t, e, a) {
        _log(t, e, a), show_toast("error", "Something is wrong!\n" + a, "ti ti-alert")
    }) : show_toast("error", "Something is wrong!", "ti ti-alert")
}

function stick_nav_() {
    var t = $(".is-sticky"),
        e = $(".topbar"),
        a = $(".topbar-wrap");
    if (t.length > 0) {
        var n = t.offset();
        $(window).scroll(function () {
            var i = $(window).scrollTop(),
                o = e.height();
            i > n.top ? t.hasClass("has-fixed") || (t.addClass("has-fixed"), a.css("padding-top", o)) : t.hasClass("has-fixed") && (t.removeClass("has-fixed"), a.css("padding-top", 0))
        })
    }
}

function data_percent_() {
    var t = $("[data-percent]");
    t.length > 0 && t.each(function () {
        var t = $(this),
            e = t.data("percent");
        t.css("width", e + "%")
    })
}

function countdown_() {
    var t = $(".countdown-clock");
    t.length > 0 && t.each(function () {
        var t = $(this),
            e = t.attr("data-date");
        t.countdown(e).on("update.countdown", function (t) {
            $(this).html(t.strftime('<div><span class="countdown-time countdown-time-first">%D</span><span class="countdown-text">Day</span></div><div><span class="countdown-time">%H</span><span class="countdown-text">Hour</span></div><div><span class="countdown-time">%M</span><span class="countdown-text">Min</span></div><div><span class="countdown-time countdown-time-last">%S</span><span class="countdown-text">Sec</span></div>'))
        })
    })
}

function selects_() {
    var t = $(".select");
    t.length > 0 && t.each(function () {
        var t = $(this).data("dd-class") ? $(this).data("dd-class") : "";
        $(this).select2({
            theme: "flat",
            dropdownCssClass: t
        })
    });
    var e = $(".select-flat");
    e.length > 0 && e.each(function () {
        var t = $(this).data("dd-class") ? $(this).data("dd-class") : "";
        $(this).select2({
            theme: "flat",
            dropdownCssClass: t
        })
    });
    var a = $(".select-bordered");
    a.length > 0 && a.each(function () {
        var t = $(this).data("dd-class") ? $(this).data("dd-class") : "";
        $(this).select2({
            theme: "flat bordered",
            dropdownCssClass: t
        })
    })
}

function toggle_content_() {
    // var t = $(".toggle-content-tigger");
    // t.length > 0 && t.on("click", function (t) {
    //     $(this).toggleClass("active").parent().find(".toggle-content").slideToggle(), t.preventDefault()
    // });
    
    
    
    // var t2 = $(".toggle-content-tigger2");
    // t2.length > 0 && t2.on("click", function (t2) {
    //     $(this).toggleClass("active").parent().find(".toggle-content2").slideToggle(), t2.preventDefault()
    // });
}

function toggle_tigger_() {
    var t = ".toggle-tigger";
    $(t).length > 0 && $(document).on("click", t, function (e) {
        var a = $(this);
        $(t).not(a).removeClass("active"), $(".toggle-class").not(a.parent().children()).removeClass("active"), a.toggleClass("active").parent().find(".toggle-class").toggleClass("active"), e.preventDefault()
    }), $(document).on("click", "body", function (e) {
        var a = $(t),
            n = $(".toggle-class");
        n.is(e.target) || 0 !== n.has(e.target).length || a.is(e.target) || 0 !== a.has(e.target).length || (n.removeClass("active"), a.removeClass("active"))
    })
}

function activeNav(t) {
    winwidth() < 991 ? t.delay(500).addClass("navbar-mobile") : t.delay(500).removeClass("navbar-mobile")
}

function toggle_nav_() {
    var t = $(".toggle-nav"),
        e = $(".navbar");
    t.length > 0 && t.on("click", function (a) {
        t.toggleClass("active"), e.toggleClass("active"), a.preventDefault()
    }), $(document).on("click", "body", function (a) {
        t.is(a.target) || 0 !== t.has(a.target).length || e.is(a.target) || 0 !== e.has(a.target).length || (t.removeClass("active"), e.removeClass("active"))
    }), activeNav(e), $(window).on("resize", function () {
        activeNav(e)
    })
}

function tooltip_() {
    var t = $('[data-toggle="tooltip"]');
    t.length > 0 && t.tooltip()
}

function date_time_picker_() {
    var t = $(".date-picker"),
        e = $(".date-picker-dob"),
        a = $(".time-picker");
    t.length > 0 && t.each(function () {
        var t = "alt" == $(this).data("format") ? "dd-mm-yyyy" : "mm/dd/yyyy";
        $(this).datepicker({
            format: t,
            maxViewMode: 2,
            clearBtn: !0,
            autoclose: !0,
            todayHighlight: !0
        })
    }), e.length > 0 && e.each(function () {
        var t = "alt" == $(this).data("format") ? "dd-mm-yyyy" : "mm/dd/yyyy";
        $(this).datepicker({
            format: t,
            startView: 2,
            maxViewMode: 2,
            clearBtn: !0,
            autoclose: !0
        })
    });
    var n = $(".custom-date-picker");
    n.length > 0 && n.each(function () {
        var t = "alt" == $(this).data("format") ? "dd-mm-yyyy" : "mm/dd/yyyy";
        $(this).datepicker({
            format: t,
            maxViewMode: 2,
            clearBtn: !0,
            autoclose: !0,
            todayHighlight: !0,
            startDate: new Date
        })
    }), a.length > 0 && a.each(function () {
        $(this).parent().addClass("has-timepicker"), $(this).timepicker({
            timeFormat: "hh:mm p",
            interval: 15,
            change: function (t) {
                btn_actived($(this).closest("form").find("button[type=submit]"))
            }
        })
    })
}

function knob_() {
    var t = $(".knob");
    t.length > 0 && t.each(function () {
        $(this).knob({
            readOnly: !0,
            displayInput: !1
        })
    })
}

function switch_link(t, e, a) {
    t.length > 0 && t.each(function () {
        "add" === a && $(this).data("switch") === e.data("switch") && $(this).addClass("link-disable"), "remove" === a && $(this).data("switch") === e.data("switch") && $(this).removeClass("link-disable")
    })
}

function switch_toggle_() {
    var t = $(".switch-toggle"),
        e = $(".switch-toggle-link"),
        a = ".switch-content";
    t.length > 0 && t.each(function () {
        $(this).on("change", function () {
            var t = $(this),
                n = t.data("switch");
            t.is(":checked") ? ($(a + "." + n).addClass("switch-active").slideDown(300), switch_link(e, $(this), "remove")) : t.is(":checked") || ($(a + "." + n).removeClass("switch-active").slideUp(300), switch_link(e, $(this), "add"))
        }), $(this).is(":checked") ? ($(a + "." + $(this).data("switch")).addClass("switch-active").slideDown(100), switch_link(e, $(this), "remove")) : ($(a + "." + $(this).data("switch")).removeClass("switch-active").slideUp(100), switch_link(e, $(this), "add"))
    }), e.length > 0 && e.each(function () {
        $(this).on("click", function (t) {
            var e = $(this),
                n = e.data("switch");
            if (e.hasClass("link-disable")) return !1;
            $(this).toggleClass("active"), $(a + "." + n).toggleClass("switch-active").slideToggle(300), t.preventDefault()
        })
    })
}

function input_file_() {
    var t = $(".input-file");
    t.length > 0 && t.each(function () {
        var t = $(this),
            e = $(this).next(),
            a = e.text();
        t.on("change", function () {
            var n = t.val();
            e.html(n), e.is(":empty") && e.html(a)
        })
    })
}

function image_popop_() {
    var t = $(".image-popup");
    t.length > 0 && t.magnificPopup({
        type: "image",
        preloader: !0,
        removalDelay: 400,
        mainClass: "mfp-fade"
    })
}

function copytoclipboard(t, e, a) {
    var n = document.queryCommandSupported("copy"),
        i = t,
        o = e,
        s = a;
    i.parent().find(o).removeAttr("disabled").select(), !0 === n ? (document.execCommand("copy"), s.text("Copied to Clipboard").fadeIn().delay(1e3).fadeOut(), i.parent().find(o).attr("disabled", "disabled")) : window.prompt("Copy to clipboard: Ctrl+C or Command+C, Enter", text)
}

function feedback(t, e) {
    "success" === e ? $(t).parent().find(".copy-feedback").text("Copied to Clipboard").fadeIn().delay(1e3).fadeOut() : $(t).parent().find(".copy-feedback").text("Faild to Copy").fadeIn().delay(1e3).fadeOut()
}

function datatable_() {
    var t = $(".dt-init");
    t.length > 0 && t.each(function () {
        var t = $(this),
            e = t.data("items") ? t.data("items") : 5;
        t.DataTable({
            ordering: !1,
            autoWidth: !1,
            dom: '<t><"row align-items-center"<"col-sm-6 text-left"p><"col-sm-6 text-sm-right"i>>',
            pageLength: e,
            pagingType: "simple",
            bPaginate: $(".data-table tbody tr").length > e,
            iDisplayLength: e,
            language: {
                search: "",
                searchPlaceholder: "Type in to Search",
                info: "_START_ -_END_ of _TOTAL_",
                infoEmpty: "No records",
                infoFiltered: "( Total _MAX_  )",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Prev"
                }
            }
        })
    });
    var e = $(".dt-filter-init");
    e.length > 0 && e.each(function () {
        var t = $(this),
            e = t.data("items") ? t.data("items") : 6,
            a = t.DataTable({
                ordering: !1,
                autoWidth: !1,
                dom: '<"row justify-content-between pdb-1x"<"col-9 col-sm-6 text-left"f><"col-3 text-right"<"data-table-filter relative d-inline-block">>><t><"row align-items-center"<"col-sm-6 text-left"p><"col-sm-6 text-sm-right"i>>',
                pageLength: e,
                pagingType: "simple",
                bPaginate: $(".data-table tbody tr").length > e,
                iDisplayLength: e,
                language: {
                    search: "",
                    searchPlaceholder: "Type in to Search",
                    info: "_START_ -_END_ of _TOTAL_",
                    infoEmpty: "No records",
                    infoFiltered: "( Total _MAX_  )",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Prev"
                    }
                }
            });
        $(".data-filter").on("change", function () {
            var t = $(this).attr("name") && "filter" != $(this).attr("name") ? $(this).attr("name") : "filter-data",
                e = $(this).val();
            console.log(t, e), a.columns("." + t).search(e || "", !0, !1).draw()
        })
    })
}

function modal_fix() {
    var t = $(".modal"),
        e = $("body");
    t.on("shown.bs.modal", function () {
        e.hasClass("modal-open") || e.addClass("modal-open")
    })
}

function drop_toggle_() {
    var t = $(".drop-toggle");
    t.length > 0 && t.on("click", function (t) {
        winwidth() < 991 && ($(this).parent().children(".navbar-dropdown").slideToggle(400), $(this).parent().siblings().children(".navbar-dropdown").slideUp(400), $(this).parent().toggleClass("current"), $(this).parent().siblings().removeClass("current"), t.preventDefault())
    })
}

function form_validate_() {
    var t = $(".form-validate");
    t.length > 0 && t.each(function () {
        $(this).validate()
    })
}

function cl(t) {}
jQuery.validator.addMethod("greaterThan", function (t, e, a) {
    return /Invalid|NaN/.test(new Date(t)) ? isNaN(t) && isNaN($(a).val()) || Number(t) > Number($(a).val()) : new Date(t) > new Date($(a).val())
}, "Must be greater than {0}.");
const _log = (...t) => {
    for (var e of t) console.log(e)
};

function btn_actived(t, e = !0) {
    t.length > 0 && (!0 === e ? $(t).removeAttr("disabled") : $(t).attr("disabled", !0))
}

function bs_modal_toggle(t, e = !0) {
    var a = t.parents("div.modal");
    a.length > 0 && e && a.modal("toggle")
}

function bs_modal_hide(t, e = "hide", a = ".modal") {
    $(t).parents(a).length > 0 && $(t).parents(a).modal(e)
}

function toggle_section_modal_() {
    var t = $(".toggle-tigger");
    t.length > 0 && t.on("click", function (t) {
        $(this).toggleClass("active").parent().find(".toggle-content").slideToggle(), t.preventDefault()
    })
}

function init_inside_modal() {
    tooltip_(), toggle_content_(), data_percent_(), selects_()
}

function randString(t) {
    var e = $(t).attr("data-character-set").split(","),
        a = "";
    $.inArray("a-z", e) >= 0 && (a += "abcdefghijklmnopqrstuvwxyz"), $.inArray("A-Z", e) >= 0 && (a += "ABCDEFGHIJKLMNOPQRSTUVWXYZ"), $.inArray("0-9", e) >= 0 && (a += "0123456789"), $.inArray("#", e) >= 0 && (a += "![]{}()%&*$#^<>~@|");
    for (var n = "", i = 0; i < $(t).attr("data-size"); i++) n += a.charAt(Math.floor(Math.random() * a.length));
    return n
}

function show_toast(t, e, a = "ti ti-filter") {
    toastr.options = {
        closeButton: !0,
        positionClass: "toast-bottom-right"
    }, toastr[t]('<em class="toast-message-icon ' + a + '"></em> ' + e)
}

function show_alert(t, e, a = ".msg-box", n = 2500) {
    var i = $(a),
        o = '<div class="alert alert-' + t + ' alert-dismissible fade show" role="alert"><button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&nbsp;</span></button>' + e + "</div>";
    i.html(o), setTimeout(function () {
        i.empty()
    }, n)
}

function store(t, e) {
    "undefined" != typeof Storage ? localStorage.setItem(t, e) : window.alert("Please use a modern browser to properly view this template!")
}

function get(t) {
    if ("undefined" != typeof Storage) return localStorage.getItem(t);
    window.alert("Please use a modern browser to properly view this template!")
}! function (t) {
    "use strict";
    var e = t("body");
    t(document);
    "ontouchstart" in document.documentElement || e.addClass("no-touch"), stick_nav_(), data_percent_(), countdown_(), selects_(), toggle_content_(), toggle_tigger_(), toggle_nav_(), tooltip_(), date_time_picker_(), knob_(), switch_toggle_(), input_file_(), image_popop_(), datatable_(), modal_fix(), drop_toggle_(), form_validate_(), new ClipboardJS(".copy-clipboard").on("success", function (t) {
        feedback(t.trigger, "success"), t.clearSelection()
    }).on("error", function (t) {
        feedback(t.trigger, "fail")
    });
    var a = window.location.href,
        n = t(".navbar a");
    n.length > 0 && n.each(function () {
        a === this.href && t(this).closest("li").addClass("active").parent().closest("li").addClass("active")
    });
    var i = t(".page-overlay");
    t.ajaxSetup({
        headers: {
            "X-CSRF-TOKEN": t('meta[name="csrf-token"]').attr("content"),
            "X-TOKEN-SECRET": t('meta[name="site-token"]').attr("content")
        }
    }), t(document).ajaxStart(function () {
        i.addClass("is-loading")
    }), t(document).ajaxStop(function () {
        i.removeClass("is-loading")
    });
    t("#ajax-modal");
    var o = t(".close-modal"),
        s = t(".modal-backdrop");
    o.on("click", function (e) {
        e.preventDefault(), t(this).parents(".modal").modal("hide"), s.remove()
    })
}(jQuery);
