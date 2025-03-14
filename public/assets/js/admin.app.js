/*! TokenLite v1.1.7 | Copyright by Softnio. */ ! function (t) {
    var e = t("#ajax-modal"),
        a = ".input-switch, .select, .input-checkbox, .input-bordered";
    _button_submit = "button[type=submit]";
    var n = t("form#addUserForm");
    n.length > 0 && ajax_form_submit(n);
    var o = t("form#user_account_update");
    o.length > 0 && ajax_form_submit(o, !1);
    var i = t("form#notification");
    i.length > 0 && ajax_form_submit(i, !1);
    var s = t("form#security");
    s.length > 0 && ajax_form_submit(s, !1);
    var r = t("form#pwd_change");
    r.length > 0 && ajax_form_submit(r);
    var d = t(".activity-delete"),
        c = t("#activity_action").val();
    d.length > 0 && d.on("click", function () {
        swal({
            title: "Are you sure?",
            text: "Once Delete, You will not get back this log in future!",
            icon: "warning",
            buttons: !0,
            dangerMode: !0
        }).then(e => {
            if (e) {
                var a = t(this).data("id");
                t.post(c, {
                    _token: csrf_token,
                    delete_activity: a
                }).done(e => {
                    "success" == e.msg && ("all" == a ? t("#activity-log tr").fadeOut(1e3, function () {
                        t(this).remove(), t("#activity-log").hide()
                    }) : d.parents("tr.activity-" + a).fadeOut(1e3, function () {
                        t(this).remove()
                    }))
                })
            }
        })
    });
    t(document).on("click", "a#clear-cache", function (e) {
        e.preventDefault(), t.get(clear_cache_url).done(t => {
            cl(t), "success" == t.msg && (show_toast(t.msg, t.message, "ti ti-trash"), window.location.reload())
        })
    });
    var u = t("form#update_settings");
    if (u.length > 0) {
        var l = u.find(_button_submit);
        u.find(a).on("keyup change", function () {
            btn_actived(l)
        }), ajax_form_submit(u, !1)
    }
    var _ = t("form#update_social_settings");
    if (_.length > 0) {
        var m = _.find(_button_submit);
        _.find(a).on("keyup change", function () {
            btn_actived(m)
        }), ajax_form_submit(_, !1)
    }
    var f = t("form#update_general_settings");
    if (f.length > 0) {
        var h = f.find(_button_submit);
        f.find(a).on("keyup change", function () {
            btn_actived(h)
        }), ajax_form_submit(f, !1)
    }
    var g = t("form#update_api_settings");
    if (g.length > 0) {
        var p = g.find(_button_submit);
        g.find(a).on("keyup change", function () {
            btn_actived(p)
        }), ajax_form_submit(g, !1)
    }
    var v = t("form#update_code_settings");
    if (v.length > 0) {
        var k = v.find(_button_submit);
        v.find(a).on("keyup change", function () {
            btn_actived(k)
        }), ajax_form_submit(v, !1)
    }
    var b = t("form#email_settings");
    b.length > 0 && ajax_form_submit(b, !1);
    t(document).on("click", "a.et-item", function (a) {
        a.preventDefault();
        var n = null != t(this).data("slug") ? t(this).data("slug") : null != t(this).data("id") ? t(this).data("id") : "";
        t.post(get_et_url, {
            get_template: n,
            _token: csrf_token
        }).done(t => {
            cl(t), e.html(t), init_inside_modal(), e.children(".modal").modal("show")
        })
    }), $resendverifyemail = t(".resend-verify-email"), $resendverifyemail.on("click", function (e) {
        e.preventDefault();
        var a = t(this).attr("href");
        t.get(a, {
            _token: csrf_token
        }).done(t => {
            show_toast("success", "A fresh verification link has been sent to your email address.", "ti ti-email")
        })
    });
    t(document).on("click", ".user-email-action", function () {
        var e = t(this).data("uid");
        t("input#user_id").val(e)
    });
    var w = t("form#emailToUser");
    w.length > 0 && ajax_form_submit(w, !0);
    t(document).on("click", "a.user-action", function (a) {
        a.preventDefault();
        var n = t(this).data("type"),
            o = "suspend_user" == n ? "warning" : "info",
            i = t(this).data("uid");
        "transactions" == n || "activities" == n || "referrals" == n ? (e.empty(), e.parent().find(".modal-backdrop").remove(), t.post(show_user_info, {
            uid: i,
            req_type: n,
            _token: csrf_token
        }).done(a => {
            a.status && "die" == a.status ? show_toast(a.msg, a.message, "ti ti-lock") : (bs_modal_hide(t(this)), e.html(a), e.children(".modal").modal("show"))
        })) : "suspend_user" != n && "active_user" != n && "reset_pwd" != n && "reset_2fa" != n || swal({
            title: "Are you sure?",
            icon: o,
            buttons: ["Cancel", "Yes"],
            dangerMode: !0
        }).then(a => {
            a && t.post(view_user_url, {
                uid: i,
                req_type: n,
                _token: csrf_token
            }).done(a => {
                null != a.msg && show_toast(a.msg, a.message), cl(a), ("active_user" == n || (n = "suspend_user")) && t(this).fadeOut(200, function () {
                    t(this).remove()
                }), t(document).find(".status_user").find(".badge").removeAttr("class").addClass("badge badge-" + a.css + " ucap"), t(".more-menu-" + i).append('<li><a href="#" data-uid="' + i + '" data-type="' + a.status + '" class="user-action"><em class="fas fa-ban"></em>' + ("suspend_user" == n ? "Active" : "Suspend") + "</a></li>"), e.html(a), e.children(".modal").modal("show")
            })
        })
    }), t("a.get_kyc").on("click", function (n) {
        n.preventDefault();
        var o = t(this).data("type"),
            i = null != t(this).data("id") ? t(this).data("id") : "";
        t.post(get_kyc_url, {
            req_type: o,
            get_id: i,
            _token: csrf_token
        }).done(n => {
            if (cl(n), e.html(n), "kyc_settings" == o) {
                var i = t("form#kyc_settings"),
                    s = i.find(_button_submit),
                    r = !1;
                i.length > 0 && ajax_form_submit(i, !1), i.find(a).on("change", function () {
                    r = !0, btn_actived(s)
                }), s.on("click", function () {
                    r = !1
                }), t(".modal-close").on("click", function (e) {
                    e.preventDefault(), !0 === r ? confirm("You made some changes, \nDo you realy close without save?") && (bs_modal_hide(t(this)), r = !1) : bs_modal_hide(t(this))
                })
            }
            init_inside_modal(), e.children(".modal").modal("show")
        })
    });
    t(document).on("click", ".kyc_action", function () {
        kid = t(this).data("id"), t("input#kyc_id").val(kid)
    }), t("#actionkyc .status-btn").on("click", function (e) {
        e.preventDefault();
        var a = t(this).data("val");
        t("#actionkyc .status-btn").removeAttr("style"), t(this).css("border", "2px solid #34425d"), t("#actionkyc input#status").val(a)
    });
    $quick_update = ".update_kyc", $kyc_form = "#kyc_status_form", t(document).on("click", $quick_update, function (e) {
        e.preventDefault();
        var a = t($kyc_form).find("#kyc_id").val();
        t.post(update_kyc_url, {
            req_type: "update_kyc_status",
            _token: csrf_token,
            status: t(this).data("value"),
            kyc_id: a
        }).done(t => {
            cl(t), show_toast(t.msg, t.message, "ti ti-trash"), "success" != t.msg && "warning" != t.msg || window.location.reload()
        })
    }), t(document).on("click", "a.kyc_reject", function (e) {
        e.preventDefault(), swal({
            title: "Are you sure?",
            text: "Once Rejected, the client will get one email for Resubmit KYC!",
            icon: "warning",
            buttons: !0,
            dangerMode: !0
        }).then(e => {
            var a = t(this),
                n = t(this).data("current"),
                o = t(this).data("id"),
                i = t(".data-item-" + o);
            e && t.post(update_kyc_url, {
                req_type: "update_kyc_status",
                _token: csrf_token,
                status: "rejected",
                kyc_id: o
            }).done(e => {
                cl(e), show_toast("warning", e.message, "ti ti-trash"), i.find("span.badge").removeClass("badge-" + n).addClass("badge-danger"), i.find("span.badge").text("Rejected"), a.fadeOut(300), t(".more-menu-" + o).find(".kyc_approve").length < 1 && t(".more-menu-" + o).append('<li><a class="kyc_action" href="#" data-id="' + o + '" data-toggle="modal" data-target="#actionkyc"><em class="far fa-check-square"></em>Approve</a></li>'), t(".more-menu-" + o).find(".kyc_delete").length < 1 && t(".more-menu-" + o).append('<li><a href="javascript:void(0)" data-id="' + o + '" class="kyc_delete"><em class="fas fa-trash-alt"></em>Delete</a></li>')
            })
        })
    }), t(document).on("click", "a.kyc_delete", function (e) {
        e.preventDefault(), swal({
            title: "Are you sure?",
            text: "Once deleted, You can not restore this KYC application!",
            icon: "error",
            buttons: !0,
            dangerMode: !0
        }).then(e => {
            var a = t(this).data("id"),
                n = t(".data-item-" + a);
            e && t.post(update_kyc_url, {
                req_type: "delete",
                _token: csrf_token,
                kyc_id: a
            }).done(e => {
                cl(e), n.fadeOut(500, function () {
                    t(this).remove()
                }), show_toast(e.msg, e.message, "ti ti-trash")
            })
        })
    });
    var y = t("form#ico_stage");
    if (y.length > 0) {
        var x = y.find(_button_submit);
        y.find(a).on("keyup change", function () {
            btn_actived(x)
        }), ajax_form_submit(y, !1)
    }
    var j = t("form#update_tokens"),
        D = t("button.update-token");
    j.length > 0 && D.on("click", function () {
        confirm("Are you sure?") && (ajax_form_submit(j, !1), D.parents("form#update_tokens").submit())
    });
    var A = t("form#ico_stage_price");
    if (A.length > 0) {
        var q = A.find(_button_submit);
        A.find(a).on("keyup change", function () {
            btn_actived(q)
        }), ajax_form_submit(A, !1)
    }
    var C = t("form#ico_stage_bonus");
    if (C.length > 0) {
        var O = C.find(_button_submit);
        C.find(a).on("keyup change", function () {
            btn_actived(O)
        }), ajax_form_submit(C, !1)
    }
    var M = t("form#stage_setting_details_form");
    if (M.length > 0) {
        var Y = M.find(_button_submit);
        M.find(a).on("keyup change", function () {
            btn_actived(Y)
        }), ajax_form_submit(M, !1)
    }
    var S = t("form#stage_setting_purchase_form");
    if (S.length > 0) {
        var T = S.find(_button_submit);
        S.find(a).on("keyup change", function () {
            btn_actived(T)
        }), ajax_form_submit(S, !1), S.find(".active_method").change(function () {
            var e = t(".active_method").val();
            e = e.toLowerCase(), S.find(".all_methods").removeAttr("disabled");
            var a = S.find("#pw-" + e);
            !a.is(":checked") && a.click(), a.attr("disabled", !0)
        })
    }
    var F = t("form#referral_setting_form");
    if (F.length > 0) {
        var R = F.find(_button_submit);
        F.find(a).on("keyup change", function () {
            btn_actived(R)
        }), ajax_form_submit(F, !1)
    }
    var $ = t("form#upanel_setting_form");
    if ($.length > 0) {
        R = $.find(_button_submit);
        $.find(a).on("keyup change", function () {
            btn_actived(R)
        }), ajax_form_submit($, !1)
    }
    var N = t("form.payment_methods_form");
    if (N.length > 0) {
        var z = N.find(_button_submit);
        N.find(a).on("keyup change", function () {
            btn_actived(z)
        }), ajax_form_submit(N, !1)
    }
    var I = t("a.quick-action");
    I.length > 0 && "undefined" != typeof quick_update_url && I.on("click", function () {
        var e = t(this);
        t.post(quick_update_url, {
            _token: csrf_token,
            type: e.data("name")
        }).done(t => {
            show_toast(t.msg, t.message), setTimeout(function () {
                window.location.reload()
            }, 300)
        }).fail(t => {
            show_toast("error", "Something is wrong!")
        })
    });
    var K = t("form#pm_manage_form");
    if (K.length > 0) {
        var U = K.find(_button_submit);
        K.find(a).on("keyup change", function () {
            btn_actived(U)
        }), ajax_form_submit(K, !1)
    }
    t("a.get_pm_manage").on("click", function (a) {
        a.preventDefault();
        var n = t(this).data("type");
        e.empty(), t.post(pm_manage_url, {
            req_type: n,
            _token: csrf_token
        }).done(t => {
            cl(t), e.html(t), init_inside_modal(), e.children(".modal").modal("show")
        }).fail(function (t, e, a) {
            _log(t, e, a), show_toast("error", "Something is wrong!\n" + a), show_toast("error", "Something is wrong!\n" + a)
        })
    });
    t(document).on("click", "a.get_trnx", function (a) {
        a.preventDefault();
        var n = t(this).data("type"),
            o = null != t(this).data("id") ? t(this).data("id") : "";
        t.post(get_trnx_url, {
            req_type: n,
            get_id: o,
            _token: csrf_token
        }).done(t => {
            cl(t), e.html(t), init_inside_modal(), e.children(".modal").modal("show")
        })
    });
    t(document).on("click", ".stages-ajax-action", function (a) {
        a.preventDefault();
        var n = t(this),
            o = n.data(),
            i = null,
            s = o.action,
            r = o.stage;
        n.parents(".stage-action").find(".toggle-tigger").add(".toggle-class").removeClass("active"), "overview" == s && "undefined" != typeof stage_action_url && (i = stage_action_url), null !== i && r ? (o._token = csrf_token, t.post(i, o).done(function (t) {
            if (void 0 !== t.modal && t.modal) e.html(t.modal), init_inside_modal(), e.children(".modal").length > 0 && e.children(".modal").modal("show");
            else if (t.message) {
                var a = t.icon ? t.icon : "ti ti-info-alt";
                show_toast(t.msg, t.message, a)
            }
        }).fail(function (t, e, a) {
            show_toast("error", "Something is wrong!\n" + a, "ti ti-alert"), _log(t, e, a)
        })) : show_toast("info", "Nothing to proceed!", "ti ti-info-alt")
    }), t("a#update_stage").on("click", function (e) {
        e.preventDefault();
        var a, n = t(this).data("type"),
            o = t(this).data("id"),
            i = "";
        a = "active_stage" == n ? stage_active_url : stage_pause_url, "active_stage" == n ? i = "Once you make this stage active, other stage will inactive and stop sale on that stage." : "pause_stage" == n ? i = "Do you want to pause temporary your running sales and purchase option disabled?" : "resume_stage" == n && (i = "Do you want to resume your sales and contributor able to purchase token?"), swal({
            title: "Are you sure?",
            text: i,
            icon: "info",
            buttons: ["Cancel", "Yes"],
            dangerMode: !1
        }).then(e => {
            e && t.post(a, {
                _token: csrf_token,
                id: o,
                type: n
            }).done(t => {
                cl(t), show_toast(t.msg, t.message, "ti ti-eye"), "success" == t.msg && window.location.reload()
            }).fail(t => {
                cl(t)
            })
        })
    });
    var W = t("form#add_token");
    W.length > 0 && ajax_form_submit(W, !1);
    t(document).on("click", ".tnx-action", function () {
        var e = t(this),
            a = t(".modal-backdrop"),
            n = e.data("type"),
            o = e.data("id");
        token = e.data("token") ? e.data("token") : 0, chk_adjust = e.data("_chk") ? e.data("_chk") : 0, adjusted_token = e.data("_adjusted") ? e.data("_adjusted") : 0, base_bonus = e.data("_b_bonus") ? e.data("_b_bonus") : 0, token_bonus = e.data("_t_bonus") ? e.data("_t_bonus") : 0, amount = e.data("_amount") ? e.data("_amount") : 0, swal_icon = "approved" == n ? "info" : "warning", swal_cta = "approved" == n ? "Approve" : "Yes", swal_ctac = "approved" == n ? "" : "danger", "approved" == n && null != amount && amount <= 0 ? show_toast("warning", "Invalid Received Amount!") : swal({
            title: "Are you sure?",
            text: "refund" == n ? "If you refund for this transactions, then the token will be added to the stage and subtract the token from user balance." : "",
            icon: swal_icon,
            buttons: {
                cancel: {
                    text: "Cancel",
                    visible: !0
                },
                confirm: {
                    text: swal_cta,
                    className: swal_ctac
                }
            },
            content: {
                element: "refund" == n ? "input" : "span",
                attributes: {
                    placeholder: "refund" == n ? "Write a note..." : "",
                    type: "text"
                }
            },
            dangerMode: !1
        }).then(i => {
            null == i && "" != i || t.post(trnx_action_url, {
                _token: csrf_token,
                req_type: n,
                tnx_id: o,
                token: token,
                chk_adjust: chk_adjust,
                adjusted_token: adjusted_token,
                base_bonus: base_bonus,
                token_bonus: token_bonus,
                amount: amount,
                message: i
            }).done(i => {
                cl(i), show_toast(i.msg, i.message), i.status || ("approved" == n && (t("#tnx-item-" + o).find(".token-amount").text("+" + i.data.total_tokens), t("#tnx-item-" + o).find(".amount-pay").text("+" + i.data.amount), t("#ds-" + o).removeAttr("class").addClass("data-state data-state-approved"), t("#more-menu-" + o).html('<li><a href="' + base_url + "/admin/transactions/view/" + o + '"><em class="ti ti-eye"></em> View Details</a></li>')), "canceled" == n && (t("#more-menu-" + o).find("#canceled").fadeOut(400, function () {
                    t(this).remove()
                }), t("#ds-" + o).removeAttr("class").addClass("data-state data-state-canceled"), t("#more-menu-" + o).append('<li><a href="javascript:void(0)" class="tnx-action" data-type="deleted" data-id="' + o + '"><em class="fas fa-trash-alt"></em>Delete</a></li>')), "deleted" == n && t("#tnx-item-" + o).fadeOut(400, function () {
                    t(this).remove()
                })), e.parents("div.modal").modal("toggle"), a.remove(), void 0 !== i.reload && i.reload && setTimeout(function () {
                    window.location.reload()
                }, 150)
            }).fail(function (t, e, a) {
                _log(t, e, a), show_toast("error", "Something is wrong!\n" + a)
            })
        })
    });
    t(document).on("click", ".tnx-transfer-action", function (e) {
        e.preventDefault();
        var a = t(this),
            n = a.data("status");
        swal({
            title: "Are you sure?",
            icon: "rejected" == n ? "warning" : "info",
            text: "rejected" == n ? "The requested token amount will re-adjust into sender account balance once rejected." : "Another transaction will create for receiver and update balance with requested amount once approved.",
            buttons: {
                cancel: {
                    text: "Cancel",
                    visible: !0
                },
                confirm: {
                    text: "rejected" == n ? "Reject" : "Approve",
                    className: "rejected" == n ? "danger" : ""
                }
            },
            dangerMode: "rejected" == n
        }).then(function (e) {
            null == e && "" != e || t.post(transfer_action_url, a.data()).done(function (t) {
                var e = t.icon ? t.icon : "ti ti-info-alt";
                show_toast(t.msg, t.message, e), "success" == t.msg && setTimeout(function () {
                    window.location.reload()
                }, 1200)
            }).fail(function (t, e, a) {
                _log(t, e, a), show_toast("error", "Something is wrong!\n" + a, "ti ti-alert")
            })
        })
    });
    if (t(document).on("click", "#adjust_token", function (a) {
            a.preventDefault();
            var n = t(this).data("id");
            e.html(""), t.post(trnx_adjust_url, {
                _token: csrf_token,
                tnx_id: n
            }).done(t => {
                console.log(t), t.status && "die" == t.status ? show_toast(t.msg, t.message, "ti ti-lock") : (e.empty().html(t.modal), init_inside_modal(), e.children(".modal").modal("show"))
            }).fail(function (t, e, a) {
                _log(t, e, a), show_toast("error", "Something is wrong!\n" + a)
            })
        }), t(".wh-upload-zone").length > 0) {
        Dropzone.autoDiscover = !1;
        if (t(".whitepaper_upload").length > 0) {
            var E = new Dropzone(".whitepaper_upload", {
                url: whitepaper_uploads,
                uploadMultiple: !1,
                maxFilesize: 5,
                maxFiles: 1,
                acceptedFiles: "application/pdf",
                hiddenInputContainer: ".hiddenFiles",
                paramName: "whitepaper",
                headers: {
                    "X-CSRF-TOKEN": csrf_token
                }
            });
            E.on("success", function (t, e) {
                cl(e);
                var a = e.message;
                "danger" == e.msg && (alert(a), E.removeFile(t)), show_toast(e.msg, e.message, "ti ti-filter")
            })
        }
    }
    t(document).on("click", "a.get_page", function (a) {
        a.preventDefault();
        var n = t(this).data("slug");
        t.post(view_page_url, {
            page: n,
            _token: csrf_token
        }).done(a => {
            cl(a), "error" == a.msg && (t(".faq-" + get_id).fadeOut(400, function () {
                t(this).remove()
            }), show_toast(a.msg, a.message)), e.html(a), init_inside_modal(), e.children(".modal").modal("show")
        })
    });
    t(document).on("click", ".wallet-change-action", function (e) {
        e.preventDefault();
        var a = t(this).data("id"),
            n = t(this).data("action");
        t(this);
        swal({
            title: "Are you sure to " + n + " this request.",
            icon: "approve" == n ? "info" : "warning",
            buttons: ["Cancel", "Yes"],
            dangerMode: !0
        }).then(e => {
            e && t.post(wallet_change_url, {
                id: a,
                action: n,
                _token: csrf_token
            }).done(e => {
                cl(e), "success" == e.msg && t(".request-" + a).hide(400), show_toast(e.msg, e.message)
            })
        })
    }), t(".delete-unverified-user").on("click", function () {
        swal({
            title: "Want to delete unverified users?",
            icon: "warning",
            text: "Please proceed, If you really want to delete all the unverified users permanently from database.",
            buttons: ["Cancel", "Yes"],
            dangerMode: !0
        }).then(e => {
            e && t.post(unverified_delete_url, {
                _token: csrf_token
            }).done(t => {
                show_toast(t.msg, t.message), "no" != t.alt && setTimeout(function () {
                    window.location.reload()
                }, 2e3)
            }).fail(t => {
                cl(t)
            })
        })
    });
    var L = t(".advsearch-opt"),
        P = t(".search-adv-wrap"),
        Q = P.find("form");
    L.length > 0 && (L.on("click", function (e) {
        e.preventDefault(), t(this).toggleClass("active"), P.slideToggle()
    }), Q.find(":input").prop("disabled", !1), Q.submit(function () {
        return t(this).find(":input").filter(function () {
            return !this.value
        }).attr("disabled", "disabled"), !0
    }));
    var V = t(".date-opt"),
        X = t(".date-hide-show");
    V.length > 0 && ("custom" == V.val() && X.show(), V.on("change", function () {
        "custom" == t(this).val() ? X.show() : X.hide()
    }));
    var B = t("form.update-meta"),
        G = B.find("a");
    B.length > 0 && G.on("click", function (e) {
        e.preventDefault();
        var a = t(this),
            n = a.closest("form").data("type") ? a.closest("form").data("type") : "",
            o = null != a.data("meta") ? a.data("meta") : "";
        t.post(meta_update_url, {
            type: n,
            meta: o,
            _token: csrf_token
        }).done(t => {
            var e = "success" == t.msg ? "ti ti-check" : "ti ti-alert";
            show_toast(t.msg, t.message, e), "success" == t.msg && window.location.reload()
        })
    });
    var H = t(".goto-page");
    H.length > 0 && H.on("change", function () {
        window.location.href = t(this).val()
    })
}(jQuery);
