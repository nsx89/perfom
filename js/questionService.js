function validateEmailOrder(e) {
    return /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(e);
}
$('[data-type="q-form-select"]').styler(),
    $('[data-type="q-form-file"]').styler(),
    /*$(document).mouseup(function (e) {
        var a = $('[data-type="q-popup"]'),
            t = e.target;
        if ("none" != a.css("display") && 0 === a.has(e.target).length && !$(t).is('[data-type="q-popup"]')) return $('[data-type="q-overlay"]').fadeOut(), $('[data-type="q-popup"]').fadeOut(), !1;
    }),*/
    $(document).click(function(event){
		if ($(event.target).closest('[data-type="q-popup"]').length) return;
		if ($(event.target).closest('[data-type="q-popup-open"]').length) return;

		$('[data-type="q-overlay"]').fadeOut();
		$('[data-type="q-popup"]').fadeOut();

		event.stopPropagation();
	});
    $('[data-type="q-popup-close"]').on("click", function () {
        $('[data-type="q-overlay"]').fadeOut(), $('[data-type="q-popup"]').fadeOut();
    }),
    $(document).mouseup(function (e) {
        var a = $('[data-type="q-popup-res"]'),
            t = e.target;
        if ("none" != a.css("display") && 0 === a.has(e.target).length && !$(t).is('[data-type="q-popup-res"]')) return $('[data-type="q-overlay"]').fadeOut(), $('[data-type="q-popup-res"]').fadeOut(), !1;
    }),
    $('[data-type="q-popup-res-close"]').on("click", function () {
        $('[data-type="q-overlay"]').fadeOut(), $('[data-type="q-popup-res"]').fadeOut();
    }),
    $("body").on("click", '[data-type="q-popup-open"]', function () {
        console.log("open"), $('[data-type="q-popup"]').fadeIn(), 700 < window.innerWidth && $('[data-type="q-overlay"]').fadeIn();
    }),
    $("body").on("click", '[data-type="q-popup-open"]', function () {
        yaCounter22165486.reachGoal("question");
    }),
    $(".autogrow").click(function () {
        $(this).autogrow({ vertical: !0, horizontal: !1 });
    }),
    $().ready(function () {
        $('[ data-type="short-answ"]').jTruncate({ length: 218, minTrail: 20, moreText: "Раскрыть ответ полностью ", lessText: "Спяртать полный ответ ", ellipsisText: "...", moreAni: 0, lessAni: 0 }),
            $('[ data-type="subj"]').click(function () {
                $(".e-aqs-subj-wrap").each(function () {
                    $(this).slideUp();
                });
                var e = $(this).parents(".e-aqs-subj").find(".e-aqs-subj-wrap");
                e.is(":visible") ? e.slideUp() : e.slideDown();
            }),
            $('[data-type="mod-qst"]').jTruncate({ length: 150, minTrail: 20, moreText: "Подробнее", lessText: "Свернуть", ellipsisText: "...", moreAni: 0, lessAni: 0 }),
            $(".truncate_more_link").click(function () {
                var e = $(this).parents(".e-qm-quest-item").find(".e-qm-quest-item-answ");
                $(this).hasClass("truncate_more_i") && e.slideUp(), $(this).hasClass("truncate_less_i") && e.slideDown();
            }),
            $('[name="aqs-name"]').click(function () {
                $(this).closest("form").find(".e-aqs-form-button").prop("disabled", !1), "Введите ваше имя" == $(this).val() && ($(this).val(""), $(this).removeClass("e-aqs-form-err"));
            }),
            $('[name="aqs-email"]').click(function () {
                $(".e-aqs-form-button").prop("disabled", !1), ("Введите ваш e-mail" != $(this).val() && "Неверный формат e-mail" != $(this).val()) || ($(this).val(""), $(this).removeClass("e-aqs-form-err"));
            }),
            $('[name="aqs-email"]').change(function () {
                $(this).closest("form").find(".e-aqs-form-button").prop("disabled", !1), $(this).removeClass("e-aqs-form-err");
            }),
            $('[name="aqs-loc"]').click(function () {
                $(".e-aqs-form-button").prop("disabled", !1), $(this).removeClass("e-aqs-form-err"), "Введите город" == $(this).val() && $(this).val("");
            }),
            $('[name="aqs-loc"]').change(function () {
                $(this).closest("form").find(".e-aqs-form-button").prop("disabled", !1), $(this).removeClass("e-aqs-form-err"), "Введите город" == $(this).val() && $(this).val("");
            }),
            $(".e-aqs-select-wrap").click(function () {
                $(this).closest("form").find(".e-aqs-form-button").prop("disabled", !1), $(this).removeClass("e-aqs-form-err");
            }),
            $('[name="aqs-qst"]').click(function () {
                $(this).closest("form").find(".e-aqs-form-button").prop("disabled", !1),
                    ("Введите вопрос" != $(this).val() && "Вопрос должен содержать более 10 символов" != $(this).val()) || ($(this).closest("form").find('[name="aqs-qst"]').val(""), $(this).removeClass("e-aqs-form-err"));
            });
        var i = 0;
        $('[name="aqs-file"]').change(function () {
            $(this).closest("form").find(".e-aqs-form-button").prop("disabled", !1), $(this).closest("form").find(".e-aqs-file-lbl").hide();
            var e,
                a,
                t = "#000",
                s = $(this).val().replace(/.*\\/, "");
            "" == s
                ? ((s = ""), (t = "#000"), (i = 0), $(this).closest("form").find(".e-aqs-file-lbl").show())
                : ((e = s.split(".").pop()),
                  (a = this.files[0].size / 1024 / 1024),
                  "JPEG" == e.toUpperCase() || "JPG" == e.toUpperCase() || "PNG" == e.toUpperCase() || "PDF" == e.toUpperCase() || "RAR" == e.toUpperCase() || "ZIP" == e.toUpperCase()
                      ? 10 < a
                          ? ((s = "размер превышает 10МБ"), (t = "#ED5C5C"), $(this).val(""), i++)
                          : (i = 0)
                      : ((s = "недопустимый формат"), (t = "#ED5C5C"), $(this).val(""), i++)),
                $(this).closest("form").find(".e-aqs-file-name").html(s).css("color", t);
        }),
            $(".aqs_policy_label").click(function () {
                $(this).closest("form").find(".e-aqs-form-button").prop("disabled", !1), $(this).removeClass("e-aqs-form-err");
            }),
            $('[data-event="no-enter"]').keypress(function (e) {
                if ("13" == e.which) return !1;
            }),
            $(document).ready(function () {
                var e = location.href;
                $('[name="aqs-page"]').val(e), $("#cb-page").val(e);
            }),
            $(".e-aqs-form-button").click(function () {
                $(this).prop("disabled", !0), "undefined" != typeof yaCounter22165486 && yaCounter22165486.reachGoal("question_send");
                var e = Math.floor(1e4 * Math.random()),
                    a = $(this).closest("form");
                $.cookie("rand", e, { domain: domain, path: "/" });
                var t,
                    s = 0;
                ("" != a.find('[name="aqs-name"]').val() && "Введите ваше имя" != a.find('[name="aqs-name"]').val()) || (a.find('[name="aqs-name"]').addClass("e-aqs-form-err"), a.find('[name="aqs-name"]').val("Введите ваше имя"), s++),
                    "" == a.find('[name="aqs-email"]').val() || "Введите ваш e-mail" == a.find('[name="aqs-email"]').val()
                        ? (a.find('[name="aqs-email"]').addClass("e-aqs-form-err"), a.find('[name="aqs-email"]').val("Введите ваш e-mail"), s++)
                        : validateEmailOrder(a.find('[name="aqs-email"]').val()) || (a.find('[name="aqs-email"]').addClass("e-aqs-form-err"), a.find('[name="aqs-email"]').val("Неверный формат e-mail"), s++),
                    0 == a.find('[name="aqs-tel"]').val().length || a.find('[name="aqs-tel"]').val(),
                    !1 === checkTelNumber(a.find('[data-tel="yes"]')) && s++,
                    ("" != a.find('[name="aqs-subj"]').val() && a.find('[name="aqs-subj"]').val()) || (a.find(".e-aqs-select-wrap").addClass("e-aqs-form-err"), s++),
                    "" == a.find('[name="aqs-qst"]').val() || "Введите вопрос" == a.find('[name="aqs-qst"]').val()
                        ? (a.find('[name="aqs-qst"]').addClass("e-aqs-form-err"), a.find('[name="aqs-qst"]').val("Введите вопрос"), s++)
                        : a.find('[name="aqs-qst"]').val().length < 10 && (a.find('[name="aqs-qst"]').addClass("e-aqs-form-err"), a.find('[name="aqs-qst"]').val("Вопрос должен содержать более 10 символов"), s++),
                    0 == a.find('[name="aqs_policy"]').prop("checked") && (a.find(".aqs_policy_label").addClass("e-aqs-form-err"), s++),
                    ("" != a.find('[name="aqs-loc"]').val() && "Введите город" != a.find('[name="aqs-loc"]').val()) || (a.find('[name="aqs-loc"]').addClass("e-aqs-form-err"), a.find('[name="aqs-loc"]').val("Введите город"), s++),
                    0 == i &&
                        0 == s &&
                        (a.find(".e-aqs-form-button").hide(),
                        a.find(".e-aqs-form-loader").show(),
                        "" == a.find('[name="aqs-page"]').val() && a.find('[name="aqs-page"]').val("https://perfom-decor.ru/"),
                        (s = hash_data(a.find('[name="aqs-qst"]').val(), e)),
                        "" != a.find('[name="aqs-file"]').val()
                            ? ((e = a[0]),
                              (e = new FormData(e)),
                              (t = new XMLHttpRequest()).open("POST", "/ajax/question_service.php?type=qst&new_val=" + s),
                              (t.onreadystatechange = function () {
                                  var e;
                                  4 == t.readyState &&
                                      200 == t.status &&
                                      ((e = t.responseText),
                                      $.cookie("rand", null, { domain: domain, path: "/" }),
                                      $('[data-type="q-popup"]').hide(),
                                      $('[data-type="q-popup-res"]').find('[data-type="aqs-rqst"]').html(e),
                                      $('[data-type="q-popup-res"]').fadeIn(),
                                      a.find(".e-aqs-form-loader").hide(),
                                      a.find(".e-aqs-form-button").show(),
                                      a.trigger("reset"),
                                      a.find(".e-aqs-file-lbl").show(),
                                      a.find('[data-type="add-file"]').html(""),
                                      a.find('[data-type="add-file"]').css("color", "#000"),
                                      a.find(".e-aqs-select-wrap").find(".jq-selectbox__select-text").html("тема вопроса*"),
                                      a.find(".e-aqs-form-button").prop("disabled", !1),
                                      setTimeout(function () {
                                          $('[data-type="q-popup-res"]').fadeOut(), $('[data-type="q-overlay"]').fadeOut();
                                      }, 8e3));
                              }),
                              t.send(e))
                            : $.ajax({
                                  type: "POST",
                                  url: "/ajax/question_service.php?type=qst&new_val=" + s,
                                  data: a.serialize(),
                                  success: function (e) {
                                      $.cookie("rand", null, { domain: domain, path: "/" }),
                                          $('[data-type="q-popup"]').hide(),
                                          $('[data-type="q-popup-res"]').find('[data-type="aqs-rqst"]').html(e),
                                          $('[data-type="q-popup-res"]').fadeIn(),
                                          a.find(".e-aqs-form-loader").hide(),
                                          a.find(".e-aqs-form-button").show(),
                                          a.trigger("reset"),
                                          a.find(".e-aqs-select-wrap").find(".jq-selectbox__select-text").html("тема вопроса*"),
                                          a.find(".e-aqs-form-button").prop("disabled", !1),
                                          setTimeout(function () {
                                              $('[data-type="q-popup-res"]').fadeOut(), $('[data-type="q-overlay"]').fadeOut();
                                          }, 8e3);
                                  },
                              }));
            }),
            $('[data-type="new-subj"]').click(function () {
                var e = $(this).parents(".e-ap-redirect-btn");
                e.hasClass("e-ap-redirect-btn-act") ? e.removeClass("e-ap-redirect-btn-act") : e.addClass("e-ap-redirect-btn-act");
            }),
            $('[data-type="new-subj-item"]').click(function () {
                $(this).parents(".e-ap-redirect-btn");
                var e,
                    a,
                    t = $(this).parents(".e-ap-new-subj-items"),
                    s = t.find(".e-ap-new-subj-items-value");
                t.hasClass("active") ? (t.removeClass("active"), s.slideUp()) : ((a = (e = $(".e-ap-new-subj").find(".active")).find(".e-ap-new-subj-items-value")), e.removeClass("active"), a.slideUp(), t.addClass("active"), s.slideDown());
            }),
            $('[data-type="new-subj-form-subm"]').click(function () {
                $(this).prop("disabled", !0);
                var e,
                    a = 0;
                $('[name="ap-subj"]').each(function () {
                    $(this).prop("checked") && a++;
                }),
                    0 == a
                        ? (alert("Выберите новую тему!"), $('[data-type="new-subj-form-subm"]').prop("disabled", !1))
                        : ((e = $(".e-qs-user").attr("data-user")),
                          $.ajax({
                              type: "POST",
                              url: "/ajax/question_service.php?type=rdrct&name=" + e,
                              data: $('[data-type="new-subj-form"]').serialize(),
                              error: function () {
                                  request.status;
                                  alert("Произошла ошибка, повторите попытку еще раз.");
                              },
                              success: function (e) {
                                  "error" != e
                                      ? ($('[data-type="new-subj-form"]').trigger("reset"),
                                        $(".e-ap-redirect-btn").removeClass("e-ap-redirect-btn-act"),
                                        $(".e-ap-redirect-btn").hide(),
                                        $(".e-ap-new-subj-message").html(e),
                                        $(".e-ap-new-subj-message").show(),
                                        $('[data-type="ap-putoff"]').hide(),
                                        $(".e-ap-answ").hide(),
                                        $(".e-ap-headers-buttons-stat").html("Вопрос перенаправлен"),
                                        $(".e-ap-headers-buttons-stat").addClass("black"),
                                        $('[data-type="new-subj-form-subm"]').prop("disabled", !1))
                                      : alert("Произошла ошибка, повторите попытку еще раз.");
                              },
                          }));
            }),
            $('.e-ap-redirect-btn [type="reset"]').click(function () {
                $(this).parents(".e-ap-redirect-btn").removeClass("e-ap-redirect-btn-act");
            }),
            $('[data-type="reset-new-subj"]').click(function () {
                var e = $(this).parents(".e-ap-redirect-btn");
                $(".e-ap-new-subj").find(".active").removeClass("active"), e.removeClass("e-ap-redirect-btn-act");
            }),
            $('[data-type="ap-putoff"]').click(function () {
                $(this).prop("disabled", !0);
                var e = $('[data-type="ap-title"]').attr("data-id");
                $.ajax({
                    type: "POST",
                    url: "/ajax/question_service.php?type=putoff&id=" + e,
                    error: function () {
                        request.status;
                        alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                    success: function (e) {
                        "error" != e
                            ? ($('[data-type="ap-putoff"]').addClass("e-ap-putoff-btn-act"),
                              $('[data-type="ap-putoff-txt"]').html("Ответ на вопрос отложен"),
                              $('[data-type="ap-putoff"]').css("cursor", "default"),
                              $(".new-subj-title").addClass("new-subj-title-notactive"),
                              $(".e-ap-answ").addClass("e-ap-answ-putoff"),
                              $(".e-ap-renew").show(),
                              $(".e-ap-headers-buttons-stat").html("Вопрос отложен"),
                              $(".e-ap-headers-buttons-stat").addClass("black"),
                              $('[data-type="ap-putoff"]').prop("disabled", !1))
                            : alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                });
            }),
            $('[data-type="ap-renew"]').click(function () {
                var e = $('[data-type="ap-title"]').attr("data-id");
                $.ajax({
                    type: "POST",
                    url: "/ajax/question_service.php?type=puton&id=" + e,
                    error: function () {
                        request.status;
                        alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                    success: function (e) {
                        "error" != e
                            ? ($(".e-ap-putoff-btn").removeClass("e-ap-putoff-btn-act"),
                              $(".e-ap-putoff-btn span").html("Отложить вопрос"),
                              $(".e-ap-putoff-btn").css("cursor", "pointer"),
                              $(".new-subj-title").removeClass("new-subj-title-notactive"),
                              $(".e-ap-answ").removeClass("e-ap-answ-putoff"),
                              $(".e-ap-renew").hide())
                            : alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                });
            }),
            $('[data-type="ap-del"]').click(function () {
                $(this).prop("disabled", !0);
                var e = $('[data-type="ap-title"]').attr("data-id");
                $.ajax({
                    type: "POST",
                    url: "/ajax/question_service.php?type=del&id=" + e,
                    error: function () {
                        request.status;
                        alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                    success: function (e) {
                        location.reload();
                    },
                });
            }),
            $('[data-type="ap-check"]').click(function () {
                $(this).prop("disabled", !0);
                var e = $('[data-type="ap-title"]').attr("data-id");
                $(".e-qs-user").attr("data-user");
                $.ajax({
                    type: "POST",
                    url: "/ajax/question_service.php?type=check&id=" + e,
                    error: function () {
                        request.status;
                        alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                    success: function (e) {
                        location.reload();
                    },
                });
            }),
            $('[name="ap-answ-text"]').focus(function () {
                $('[data-type="answ-plchldr"]').css("opacity", "0"), $('[data-type="ap-answ-send"]').prop("disabled", !1);
            }),
            $('[name="ap-answ-text"]').blur(function () {
                "" == $(this).val() && $('[data-type="answ-plchldr"]').css("opacity", "1");
            }),
            $('[data-type="ap-answ-reset"]').click(function () {
                $('[data-type="answ-plchldr"]').css("opacity", "1"), $('[data-type="ap-answ-send"]').prop("disabled", !1), $('[data-type="ap-answ-send"]').html("Отправить ответ");
            }),
            $('[name="ap-answ-file"]').change(function () {
                var e,
                    a = $(this).closest(".answ-wrap");
                a.find('[data-type="ap-answ-send"]').prop("disabled", !1),
                    "" != $(this).val() ? ((e = $(this).val().replace(/.*\\/, "")), a.find(".e-ap-answ-file>span").html(e), a.find(".e-ap-answ-file").show()) : (a.find(".e-ap-answ-file>span").html(""), a.find(".e-ap-answ-file").hide());
            }),
            $('[data-type="ap-answ-reset"]').click(function () {
                $(".e-ap-answ-right textarea").val(""), $('[name="ap-file"]').val(""), $(".e-ap-answ-file>span").html(""), $(".e-ap-answ-file").hide(), $('[data-type="ap-answ-send"]').prop("disabled", !1);
            }),
            $('[data-type="ap-answ-send"]').click(function () {
                var e, a, t, s, i, d, n;
                $(this).prop("disabled", !0),
                    "" == $(".e-ap-answ-right textarea").val()
                        ? alert("Поле для ответа не заполнено.")
                        : ((e = $(".e-qs-user").attr("data-user")),
                          (a = $('[data-type="ap-title"]').attr("data-id")),
                          (t = (t = $(".e-ap-answ-right textarea").val()).replace(/\n/g, "<br>")),
                          "" != (s = $('[name="ap-answ-file"]').val()) && (i = s.replace(/.*\\/, "")),
                          "" != $('[name="ap-answ-file"]').val()
                              ? ((d = document.forms.apAnsw),
                                (d = new FormData(d)),
                                (n = new XMLHttpRequest()).open("POST", "/ajax/question_service.php?type=answ&id=" + a + "&name=" + e),
                                (n.onreadystatechange = function () {
                                    4 == n.readyState &&
                                        200 == n.status &&
                                        ((data = n.responseText),
                                        $(".e-ap-answ-file").hide(),
                                        $(".e-ap-answ").hide(),
                                        $(".e-ap-spec-answ-text").html(t),
                                        "" != s && ($(".e-ap-spec-answ-file a").attr("href", s), $(".e-ap-spec-answ-file a").html(i), $(".e-ap-spec-answ-file").show()),
                                        $(".e-ap-spec-answ-attr").html(data),
                                        $(".for-ajax").show(),
                                        $("#apAnsw").trigger("reset"),
                                        $(".e-ap-answ-file>span").html(""),
                                        $(".e-ap-putoff-btn").hide(),
                                        $(".e-ap-redirect-btn").hide(),
                                        $(".e-ap-headers-buttons-stat").html("Ответ отправлен"),
                                        $(".e-ap-headers-buttons-stat").addClass("green"),
                                        $(".e-ap-headers-buttons-stat").removeClass("black"),
                                        $(".e-ap-headers-buttons-stat").removeClass("red"),
                                        $('[data-type="ap-answ-send"]').prop("disabled", !1));
                                }),
                                n.send(d))
                              : $.ajax({
                                    type: "POST",
                                    url: "/ajax/question_service.php?type=answ&id=" + a + "&name=" + e,
                                    data: $("#apAnsw").serialize(),
                                    success: function (e) {
                                        $(".e-ap-answ-file").hide(),
                                            $(".e-ap-answ").hide(),
                                            $(".e-ap-spec-answ-text").html(t),
                                            "" != s && ($(".e-ap-spec-answ-file a").attr("href", s), $(".e-ap-spec-answ-file a").html(i), $(".e-ap-spec-answ-file").show()),
                                            $(".e-ap-spec-answ-attr").html(e),
                                            $(".for-ajax").show(),
                                            $("#apAnsw").trigger("reset"),
                                            $(".e-ap-answ-file>span").html(""),
                                            $(".e-ap-putoff-btn").hide(),
                                            $(".e-ap-redirect-btn").hide(),
                                            $(".e-ap-headers-buttons-stat").html("Ответ отправлен"),
                                            $(".e-ap-headers-buttons-stat").addClass("green"),
                                            $(".e-ap-headers-buttons-stat").removeClass("black"),
                                            $(".e-ap-headers-buttons-stat").removeClass("red"),
                                            $('[data-type="ap-answ-send"]').prop("disabled", !1);
                                    },
                                }));
            }),
            $('[data-type="ap-answ-send-mob"]').click(function () {
                var e, a, t, s, i, d, n;
                $(this).prop("disabled", !0),
                    "" == $(".e-ap-answ-right textarea").val()
                        ? alert("Поле для ответа не заполнено.")
                        : ((e = $(".e-qs-user").attr("data-user")),
                          (a = $('[data-type="ap-title"]').attr("data-id")),
                          (t = (t = $(".e-ap-answ-right textarea").val()).replace(/\n/g, "<br>")),
                          "" != (s = $('[name="ap-answ-file"]').val()) && (i = s.replace(/.*\\/, "")),
                          "" != $('[name="ap-answ-file"]').val()
                              ? ((form = document.forms.apAnsw),
                                (d = new FormData(form)),
                                (n = new XMLHttpRequest()).open("POST", "/ajax/question_service.php?type=answ&id=" + a + "&name=" + e),
                                (n.onreadystatechange = function () {
                                    4 == n.readyState &&
                                        200 == n.status &&
                                        ((data = n.responseText),
                                        $(".e-ap-answ-file").hide(),
                                        $(".e-ap-answ").hide(),
                                        $(".e-ap-spec-answ-text").html(t),
                                        "" != s && ($(".e-ap-spec-answ-file a").attr("href", s), $(".e-ap-spec-answ-file a").html(i), $(".e-ap-spec-answ-file").show()),
                                        $(".e-ap-spec-answ-attr").html(data),
                                        $(".for-ajax").show(),
                                        $("#apAnsw").trigger("reset"),
                                        $(".e-ap-answ-file>span").html(""),
                                        $(".e-ap-putoff-btn").hide(),
                                        $(".e-ap-redirect-btn").hide(),
                                        $(".e-ap-headers-buttons").hide(),
                                        $(".e-ap-headers-buttons-stat").html("Ответ отправлен"),
                                        $(".e-ap-headers-buttons-stat").addClass("green"),
                                        $(".e-ap-headers-buttons-stat").removeClass("black"),
                                        $(".e-ap-headers-buttons-stat").removeClass("red"),
                                        $('[data-type="ap-answ-send-mob"]').prop("disabled", !1));
                                }),
                                n.send(d))
                              : $.ajax({
                                    type: "POST",
                                    url: "/ajax/question_service.php?type=answ&id=" + a + "&name=" + e,
                                    data: $("#apAnsw").serialize(),
                                    success: function (e) {
                                        $(".e-ap-answ-file").hide(),
                                            $(".e-ap-answ").hide(),
                                            $(".e-ap-spec-answ-text").html(t),
                                            "" != s && ($(".e-ap-spec-answ-file a").attr("href", s), $(".e-ap-spec-answ-file a").html(i), $(".e-ap-spec-answ-file").show()),
                                            $(".e-ap-spec-answ-attr").html(e),
                                            $(".for-ajax").show(),
                                            $("#apAnsw").trigger("reset"),
                                            $(".e-ap-answ-file>span").html(""),
                                            $(".e-ap-putoff-btn").hide(),
                                            $(".e-ap-redirect-btn").hide(),
                                            $(".e-ap-headers-buttons").hide(),
                                            $(".e-ap-headers-buttons-stat").html("Ответ отправлен"),
                                            $(".e-ap-headers-buttons-stat").addClass("green"),
                                            $(".e-ap-headers-buttons-stat").removeClass("black"),
                                            $(".e-ap-headers-buttons-stat").removeClass("red"),
                                            $('[data-type="ap-answ-send-mob"]').prop("disabled", !1);
                                    },
                                }));
            }),
            $('[data-type="comm-text"]').focus(function () {
                $('[data-type="comm-plchldr"]').css("opacity", "0"), $('[data-type="mod-comment-send"]').prop("disabled", !1);
            }),
            $('[data-type="comm-text"]').blur(function () {
                "" == $(this).val() && $('[data-type="comm-plchldr"]').css("opacity", "1");
            }),
            $('[data-type="mod-comment-reset"]').click(function () {
                $('[data-type="comm-plchldr"]').css("opacity", "1");
            }),
            $('[data-type="mod-comment-send"]').click(function () {
                event.preventDefault(), $(this).prop("disabled", !0);
                var e,
                    a,
                    t,
                    s,
                    i = $(this).parents(".apComm"),
                    d = $(this).parents(".apComm").find("[data-type='comm-text']"),
                    n = 0,
                    r = '<div class="e-ap-comment-for">Комментарий для: ';
                $(this)
                    .parents(".apComm")
                    .find(".e-ap-edit-input")
                    .each(function (e, a) {
                        1 == $(a).prop("checked") && (n++, (r += $(a).closest("div").find(".e-ap-edit-label").html()), (r += ", "));
                    }),
                    (r = r.substring(0, r.length - 2)),
                    (r += "</div>"),
                    "" == d.val()
                        ? (alert("Введите комментарий!"), $('[data-type="mod-comment-send"]').prop("disabled", !1))
                        : 0 == n
                        ? (alert("Выберите, кому отправить комментарий!"), $('[data-type="mod-comment-send"]').prop("disabled", !1))
                        : ((e = $(".e-qs-user").attr("data-user")),
                          "report" == i.attr("data-pos") && ((a = i.attr("data-id")), (t = $(this).parents(".e-qm-quest-item-comments").find(".e-qm-quest-item-comment"))),
                          "answer" == i.attr("data-pos") && ((a = $('[data-type="ap-title"]').attr("data-id")), (t = $('[data-type="comm-wrap"]'))),
                          (s = i.serialize()),
                          (d = d.val().replace(/\n/g, "<br>")),
                          $.ajax({
                              type: "POST",
                              url: "/ajax/question_service.php?type=comm&id=" + a + "&name=" + e,
                              data: s,
                              error: function () {
                                  request.status;
                                  alert("Произошла ошибка, повторите попытку еще раз.");
                              },
                              success: function (e) {
                                  var a;
                                  "error" != e
                                      ? ((a = '<div class="e-ap-comment e-ap-comment-' + $('[name="comm-stat"]').val() + '">'),
                                        (a += '<div class="e-ap-comment-name">' + e + "</div>"),
                                        (a += '<div class="e-ap-comment-text">' + d + "</div>"),
                                        (a += r),
                                        (a += "</div>"),
                                        t.append(a),
                                        i.trigger("reset"),
                                        $('[data-type="comm-plchldr"]').css("opacity", "1"))
                                      : alert("Произошла ошибка, повторите попытку еще раз."),
                                      $('[data-type="mod-comment-send"]').prop("disabled", !1);
                              },
                          }));
            }),
            ($.mask.definitions[9] = !1),
            ($.mask.definitions.X = "[0-9]"),
            $(".tcal").mask("XX.XX.XXXX"),
            $(".e-qm-more").click(function () {
                var e = $(this).parents(".e-qm-quest-item-main").find(".e-qm-quest-item-answ"),
                    a = $(this).parents(".e-qm-quest-item-main").find(".e-qm-more"),
                    t = $(this).parents(".e-qm-quest-item-main").find(".e-qm-dialog");
                e.is(":visible")
                    ? (e.slideUp(), a.html('Подробнее <i class="icon-angle-down"></i>'))
                    : (e.slideDown(),
                      a.html('Свернуть <i class="icon-angle-up"></i>'),
                      t.hasClass("not-seen") &&
                          (t.removeClass("not-seen"),
                          (t = $(this).parents(".e-qm-quest-item-main").find(".apComm").attr("data-numb")),
                          $.ajax({ type: "POST", url: "/ajax/question_service.php?type=commseen&numb=" + t, success: function (e) {} })));
            }),
            $('[data-type="more"]').click(function () {
                $(this).parents(".e-qm-quest-item").find(".e-qm-quest-item-main").css("height", "auto");
                console.log("here");
            }),
            $(".e-qm-dialog").click(function () {
                $(this).removeClass("not-seen");
            }),
            $(".e-qm-filter-stat-list-wrap li").click(function () {
                var e = $(this).attr("data-val");
                $.cookie("qm_mod_stat", e, { domain: domain, path: "/" }), location.reload();
            }),
            $('[data-type="show-date"]').click(function () {
                var e,
                    a = $('[name="qm-from"]').val(),
                    t = $('[name="qm-to"]').val();
                "" == a && "" == t ? alert("Введите дату!") : (((e = {}).from = a), (e.to = t), (e.val = "0"), (e = JSON.stringify(e)), $.cookie("qm_mod_date", e, { domain: domain, path: "/" }), location.reload());
            }),
            $(".e-qm-filter-period-segment li").click(function () {
                var e = {},
                    a = $(this).attr("data-val");
                "1" == (e.val = a) || "2" == a || "3" == a ? (e.from = $(this).attr("data-from")) : "4" == a && (e.from = ""), (e.to = "");
                e = JSON.stringify(e);
                $.cookie("qm_mod_date", e, { domain: domain, path: "/" }), location.reload();
            }),
            $('[data-type="feedb-but"]').click(function () {
                $(this).prop("disabled", !0);
                var e = $(this).attr("data-val"),
                    a = $('[data-type="ap-title"]').attr("data-id"),
                    t = "",
                    t = "yes" == e ? '<div class="e-ap-score-value green">Да</div>' : '<div class="e-ap-score-value red">Нет</div>';
                $.ajax({
                    type: "POST",
                    url: "/ajax/question_service.php?type=fdbk_score&id=" + a + "&val=" + e,
                    error: function () {
                        request.status;
                        alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                    success: function (e) {
                        "error" != e ? ($('*[data-type="feedb-but"]').hide(), $(".e-ap-score").append(t)) : alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                });
            }),
            $('[data-type="mod-feedb-reset"]').click(function () {
                $(".e-ap-answ-right textarea").val(""), $('[name="ap-file"]').val(""), $(".e-ap-answ-file>span").html(""), $(".e-ap-answ-file").hide(), $('[data-type="ap-answ-send"]').prop("disabled", !1);
            }),
            $('[data-type="feedb-text"]').focus(function () {
                $('[data-type="feedb-plchldr"]').css("opacity", "0"), $('[data-type="mod-feedb-send"]').prop("disabled", !1);
            }),
            $('[data-type="feedb-text"]').blur(function () {
                "" == $(this).val() && $('[data-type="feedb-plchldr"]').css("opacity", "1");
            }),
            $('[data-type="mod-feedb-reset"]').click(function () {
                $('[data-type="feedb-plchldr"]').css("opacity", "1"), $('[data-type="mod-feedb-send"]').prop("disabled", !1);
            }),
            $('[data-type="mod-feedb-send"]').click(function () {
                $(this).prop("disabled", !0);
                var e,
                    a,
                    t,
                    s,
                    i,
                    d = $(this).parents(".apFeedb"),
                    n = $(this).parents(".apFeedb").find("[data-type='feedb-text']");
                "" == n.val()
                    ? alert("Введите комментарий!")
                    : ((n = n.val().replace(/\n/g, "<br>")),
                      (e = $('[data-type="ap-title"]').attr("data-id")),
                      (a = $('[data-type="feedb-comm"]')),
                      "spec" == $('[name="aqs-stat"]').val() ? (t = $(".e-qs-user").attr("data-user")) : (t = ""),
                      "" == $("#e-aqs-input-page").val() && $("#e-aqs-input-page").val("https://perfom-decor.ru/question_service/answer.php"),
                      "" != (n = $("#apFeedb").find('[name="ap-answ-file"]').val()) && n.replace(/.*\\/, ""),
                      "" != n
                          ? ((d = document.forms.apFeedb),
                            (i = new FormData(d)),
                            (s = new XMLHttpRequest()).open("POST", "/ajax/question_service.php?type=fdbk_comm&id=" + e + "&who=" + t),
                            (s.onreadystatechange = function () {
                                4 == s.readyState &&
                                    200 == s.status &&
                                    ((data = s.responseText),
                                    $("#apFeedb").trigger("reset"),
                                    $("#apFeedb").find(".e-ap-answ-file span").html(""),
                                    $("#apFeedb").find(".e-ap-answ-file").hide(),
                                    a.append(data),
                                    $('[data-type="mod-feedb-send"]').prop("disabled", !1));
                            }),
                            s.send(i))
                          : ((i = d.serialize()),
                            $.ajax({
                                type: "POST",
                                url: "/ajax/question_service.php?type=fdbk_comm&id=" + e + "&who=" + t,
                                data: i,
                                error: function () {
                                    request.status;
                                    alert("Произошла ошибка, повторите попытку еще раз."), $('[data-type="mod-feedb-send"]').prop("disabled", !1);
                                },
                                success: function (e) {
                                    "error" != e ? (d.trigger("reset"), a.append(e)) : alert("Произошла ошибка, повторите попытку еще раз."), $('[data-type="mod-feedb-send"]').prop("disabled", !1);
                                },
                            })));
            }),
            $('[name="dealer-mail"]').click(function () {
                $('[data-type="send-dealer"]').prop("disabled", !1), ("Введите e-mail дилера" != $(this).val() && "Неверный формат e-mail" != $(this).val()) || ($('[name="dealer-mail"]').val(""), $(this).removeClass("e-aqs-form-err"));
            }),
            $('[data-type="send-dealer"]').click(function () {
                $(this).prop("disabled", !0);
                var e,
                    a = $('[name="dealer-mail"]').val(),
                    t = 0;
                "" == a || "Введите e-mail дилера" == a
                    ? ($('[name="dealer-mail"]').addClass("e-aqs-form-err"), $('[name="dealer-mail"]').val("Введите e-mail дилера"), t++)
                    : validateEmailOrder(a) || ($('[name="dealer-mail"]').addClass("e-aqs-form-err"), $('[name="dealer-mail"]').val("Неверный формат e-mail"), t++),
                    0 == t &&
                        ((e = $('[data-type="ap-title"]').attr("data-id")),
                        (a = $(".e-qs-user").attr("data-user")),
                        (t = $("#dealerForm").serialize()),
                        $.ajax({
                            type: "POST",
                            url: "/ajax/question_service.php?type=dealer&id=" + e + "&who=" + a,
                            data: t,
                            error: function () {
                                request.status;
                                alert("Произошла ошибка, повторите попытку еще раз.");
                            },
                            success: function (e) {
                                var a;
                                "error" != e
                                    ? ($('[data-type="new-subj-form"]').trigger("reset"),
                                      $(".e-ap-redirect-btn").removeClass("e-ap-redirect-btn-act"),
                                      $(".e-ap-redirect-btn").hide(),
                                      (a = '<div class="e-ap-dealer-message-text">Вопрос переадресован дилеру</div>'),
                                      (a += '<div class="e-ap-dealer-message-attr">' + e + "</div>"),
                                      $(".e-ap-dealer-message").html(a),
                                      $(".e-ap-dealer-message").show(),
                                      $('[data-type="ap-putoff"]').hide(),
                                      $(".e-ap-answ").hide(),
                                      $("#dealerForm").hide(),
                                      $(".e-ap-headers-buttons-stat").html("Вопрос переадресован"),
                                      $(".e-ap-headers-buttons-stat").addClass("black"),
                                      $('[data-type="send-dealer"]').prop("disabled", !1))
                                    : alert("Произошла ошибка, повторите попытку еще раз.");
                            },
                        }));
            }),
            $(".e-ap-edit-label").click(function () {
                0 == $("#e-ap-edit").prop("checked") ? $('[data-type="ap-answ-send"]').html("Записать в базу") : $('[data-type="ap-answ-send"]').html("Отправить ответ");
            });
        var e = 50 < (e = $(".e-ap-spec-answ-text").height()) ? e : 50;
        $(".e-ap-spec-answ-edit").css("height", e),
            $(".e-ap-edit-label-answ").click(function () {
                var e,
                    t = $(this).closest(".edit-answer"),
                    r = t.find(".e-ap-spec-answ-text"),
                    p = "no";
                0 == t.find(".e-ap-edit-input-answ").prop("checked")
                    ? (r.hide(),
                      t.find(".e-ap-spec-answ-file").hide(),
                      (e = (e = t.find(".e-ap-spec-answ-edit").html()).replace(/&lt;br\/&gt;/g, "\n")),
                      t.find(".e-ap-spec-answ-edit").html(e),
                      t.find(".e-ap-spec-answ-edit").show(),
                      t.find('[data-type="edit-answ-btn"]').show(),
                      t.find('[data-type="edit-answ-reset-btn"]').show(),
                      t.find(".e-ap-spec-answ-file-edit").show(),
                      t.find(".e-ap-answ-edit-panel").css("display", "flex"),
                      $('[data-type="edit-answ-reset-btn"]').click(function () {
                          (t = $(this).closest(".edit-answer")).find(".e-ap-spec-answ-edit").html(""),
                              t.find(".e-ap-spec-answ-edit").val(""),
                              0 < t.find(".e-ap-spec-answ-file-edit").length && (t.find(".e-ap-spec-answ-file-edit").html(""), (p = "yes"));
                      }),
                      $('[name="ap-answ-file"]').change(function () {
                          t = $(this).closest(".edit-answer");
                          var e,
                              a = $(this).val().replace(/.*\\/, "");
                          0 < t.find(".e-ap-spec-answ-file-edit").length
                              ? ((e = "прикрепленный файл: <span>" + a + "<span>"), t.find(".e-ap-spec-answ-file-edit").html(e))
                              : ((e = '<div class="e-ap-spec-answ-file-edit">прикрепленный файл: <span>' + a + "<span></div>"), t.find(".e-ap-spec-answ-edit").after(e), t.find(".e-ap-spec-answ-file-edit").show());
                      }),
                      $('[data-type="edit-answ-btn"]').click(function () {
                          var e,
                              a,
                              t,
                              s,
                              i,
                              d,
                              n = $(this).closest(".edit-answer");
                          event.preventDefault(),
                              $(this).prop("disabled", !0),
                              "" == n.find(".e-ap-spec-answ-edit").val() && "" == n.find('[name="ap-answ-file"]').val()
                                  ? (alert("Перед сохранением необходимо внести изменения!"), $(this).prop("disabled", !1))
                                  : ((e = $('[data-type="ap-title"]').attr("data-id")),
                                    (a = (a = n.find(".e-ap-spec-answ-edit").val()).replace(/\n/g, "<br>")),
                                    "" != (t = n.find('[name="ap-answ-file"]').val()) && (s = t.replace(/.*\\/, "")),
                                    "" != n.find('[name="ap-answ-file"]').val()
                                        ? ((i = document.forms.editAnswer),
                                          (i = new FormData(i)),
                                          (d = new XMLHttpRequest()).open("POST", "/ajax/question_service.php?type=edit_answ&id=" + e),
                                          (d.onreadystatechange = function () {
                                              var e;
                                              4 == d.readyState &&
                                                  200 == d.status &&
                                                  (d.responseText,
                                                  r.html(a),
                                                  n.find("div").is(".e-ap-spec-answ-file")
                                                      ? (n.find(".e-ap-spec-answ-file a").attr("href", t), n.find(".e-ap-spec-answ-file a").html(s), n.find(".e-ap-spec-answ-file").show())
                                                      : ((e = '<div class="e-ap-spec-answ-file">прикрепленный файл: <a href="' + t + '" download>' + s + "</a></div>"), r.after(e)),
                                                  r.show(),
                                                  n.find(".e-ap-spec-answ-edit").hide(),
                                                  n.find('[data-type="edit-answ-btn"]').hide(),
                                                  n.find('[data-type="edit-answ-reset-btn"]').hide(),
                                                  n.find(".e-ap-spec-answ-file-edit").hide(),
                                                  n.find(".e-ap-answ-edit-panel").hide(),
                                                  n.find(".e-ap-edit-input-answ").removeAttr("checked"),
                                                  n.find('[data-type="edit-answ-btn"]').prop("disabled", !1));
                                          }),
                                          d.send(i))
                                        : $.ajax({
                                              type: "POST",
                                              url: "/ajax/question_service.php?type=edit_answ&id=" + e + "&remove=" + p,
                                              data: $("#editAnswer").serialize(),
                                              success: function (e) {
                                                  r.html(a),
                                                      r.show(),
                                                      n.find(".e-ap-spec-answ-edit").hide(),
                                                      n.find('[data-type="edit-answ-btn"]').hide(),
                                                      n.find('[data-type="edit-answ-reset-btn"]').hide(),
                                                      n.find(".e-ap-spec-answ-file-edit").hide(),
                                                      n.find(".e-ap-answ-edit-panel").hide(),
                                                      n.find(".e-ap-edit-input-answ").removeAttr("checked"),
                                                      n.find('[data-type="edit-answ-btn"]').prop("disabled", !1);
                                              },
                                          }));
                      }))
                    : (r.show(),
                      t.find(".e-ap-spec-answ-edit").hide(),
                      t.find('[data-type="edit-answ-btn"]').hide(),
                      t.find('[data-type="edit-answ-reset-btn"]').hide(),
                      t.find(".e-ap-spec-answ-file-edit").hide(),
                      t.find(".e-ap-answ-edit-panel").hide());
            }),
            $(".e-ap-edit-label-add-answ").click(function () {
                var e = $(this).closest(".apFeedb");
                0 == $("#answAdd").prop("checked") ? e.find('[data-type="mod-feedb-send"]').html("Записать в базу") : e.find('[data-type="mod-feedb-send"]').html("Отправить");
            }),
            $('[data-type="add-answ"]').each(function () {
                (e = 50 < (e = $(this).height()) ? e : 50), $(this).closest(".e-ap-comment").find(".e-ap-add-text-edit").css("height", e);
            }),
            $(".e-ap-edit-label-add").click(function () {
                var e,
                    a = $(this).closest(".e-ap-comment"),
                    l = a.find(".e-ap-comment-text"),
                    o = "no";
                0 == a.find(".e-ap-edit-input-add").prop("checked")
                    ? (l.hide(),
                      a.find(".e-ap-ask-file-current").hide(),
                      (e = (e = a.find(".e-ap-add-text-edit").html()).replace(/&lt;br\/&gt;/g, "\n")),
                      a.find(".e-ap-add-text-edit").html(e),
                      a.find(".e-ap-add-text-edit").show(),
                      a.find('[data-type="edit-add-btn"]').show(),
                      a.find('[data-type="edit-add-reset-btn"]').show(),
                      a.find(".e-ap-ask-file-edit").show(),
                      a.find(".e-ap-add-edit-panel").css("display", "flex"),
                      $('[data-type="edit-add-reset-btn"]').click(function () {
                          var e = $(this).closest(".e-ap-comment");
                          e.find(".e-ap-add-text-edit").html(""), e.find(".e-ap-add-text-edit").val(""), 0 < e.find(".e-ap-ask-file-edit").length && (e.find(".e-ap-ask-file-edit").html(""), (o = "yes"));
                      }),
                      $('[name="ap-add-file"]').change(function () {
                          var e,
                              a = $(this).closest(".e-ap-comment"),
                              t = $(this).val().replace(/.*\\/, "");
                          0 < a.find(".e-ap-ask-file-edit").length
                              ? ((e = "прикрепленный файл: <span>" + t + "</span>"), a.find(".e-ap-ask-file-edit").html(e))
                              : ((e = '<div class="e-ap-ask-file e-ap-ask-file-edit">прикрепленный файл: <span>' + t + "</span></div>"), a.find(".e-ap-add-text-edit").after(e), a.find(".e-ap-ask-file-edit").show());
                      }),
                      $('[data-type="edit-add-btn"]').click(function () {
                          event.preventDefault();
                          var e,
                              a,
                              t,
                              s,
                              i,
                              d,
                              n,
                              r = $(this).closest(".e-ap-comment"),
                              p = r.find(".editAdd");
                          $(this).prop("disabled", !0),
                              "" == r.find(".e-ap-add-text-edit").val() && "" == r.find('[name="ap-add-file"]').val()
                                  ? (alert("Перед сохранением необходимо внести изменения!"), $(this).prop("disabled", !1))
                                  : ((e = $('[data-type="ap-title"]').attr("data-id")),
                                    (t = r.find(".e-ap-add-text-edit").val()),
                                    (a = r.find('[value="add-edit"]').attr("id")),
                                    (t = t.replace(/\n/g, "<br>")),
                                    "" != (s = r.find('[name="ap-add-file"]').val()) && (i = s.replace(/.*\\/, "")),
                                    "" != r.find('[name="ap-add-file"]').val()
                                        ? ((d = "form" + a),
                                          (p = document.getElementById(d)),
                                          (d = new FormData(p)),
                                          console.log(d),
                                          (n = new XMLHttpRequest()).open("POST", "/ajax/question_service.php?type=edit_add&id=" + e + "&id_add=" + a),
                                          (n.onreadystatechange = function () {
                                              4 == n.readyState &&
                                                  200 == n.status &&
                                                  ((data = n.responseText),
                                                  l.html(t),
                                                  (isFile = r.find(".e-ap-ask-file-current")),
                                                  0 < isFile.length
                                                      ? (r.find(".e-ap-ask-file-current a").attr("href", s), r.find(".e-ap-ask-file-current a").html(i), r.find(".e-ap-ask-file-current").show())
                                                      : ((file = '<div class="e-ap-ask-file e-ap-ask-file-current">прикрепленный файл: <a href="' + s + '" download>' + i + "</a></div>"), l.after(file)),
                                                  l.show(),
                                                  r.find(".e-ap-add-text-edit").hide(),
                                                  r.find('[data-type="edit-add-btn"]').hide(),
                                                  r.find('[data-type="edit-add-reset-btn"]').hide(),
                                                  r.find(".e-ap-ask-file-edit").hide(),
                                                  r.find(".e-ap-add-edit-panel").hide(),
                                                  r.find(".e-ap-edit-input-add").removeAttr("checked"),
                                                  r.find('[data-type="edit-add-btn"]').prop("disabled", !1));
                                          }),
                                          n.send(d))
                                        : $.ajax({
                                              type: "POST",
                                              url: "/ajax/question_service.php?type=edit_add&id=" + e + "&remove=" + o + "&id_add=" + a,
                                              data: p.serialize(),
                                              success: function (e) {
                                                  l.html(t),
                                                      l.show(),
                                                      "yes" != o && r.find(".e-ap-ask-file-current").show(),
                                                      r.find(".e-ap-add-text-edit").hide(),
                                                      r.find('[data-type="edit-add-btn"]').hide(),
                                                      r.find('[data-type="edit-add-reset-btn"]').hide(),
                                                      r.find(".e-ap-ask-file-edit").hide(),
                                                      r.find(".e-ap-add-edit-panel").hide(),
                                                      r.find(".e-ap-edit-input-add").removeAttr("checked"),
                                                      r.find('[data-type="edit-add-btn"]').prop("disabled", !1);
                                              },
                                          }));
                      }))
                    : (l.show(),
                      a.find(".e-ap-ask-file-current").show(),
                      a.find(".e-ap-add-text-edit").hide(),
                      a.find('[data-type="edit-add-btn"]').hide(),
                      a.find('[data-type="edit-add-reset-btn"]').hide(),
                      a.find(".e-ap-ask-file-edit").hide(),
                      a.find(".e-ap-add-edit-panel").hide());
            }),
            $('[name="cb-name"]').click(function () {
                $('[data-type="cb-but"]').prop("disabled", !1), "Введите ваше имя" == $(this).val() && ($('[name="cb-name"]').val(""), $(this).removeClass("e-aqs-form-err"));
            }),
            $('[name="cb-tel"]').click(function () {
                $('[data-type="cb-but"]').prop("disabled", !1),
                    $(this).mask("P000000000000"),
                    $(this).removeClass("e-aqs-form-err"),
                    ("Введите номер телефона" != $(this).val() && "Неверный формат номера" != $(this).val()) || ($('[name="cb-tel"]').val(""), $(this).removeClass("e-aqs-form-err"));
            }),
            $(".cb_policy_label").click(function () {
                $('[data-type="cb-but"]').prop("disabled", !1), $(this).removeClass("e-aqs-form-err");
            }),
            $('[data-type="cb-but"]').click(function (e) {
                e.preventDefault();
                var a = Math.floor(1e4 * Math.random());
                $.cookie("rand", a, { domain: domain, path: "/" });
                e = 0;
                ("" != $('[name="cb-name"]').val() && "Введите ваше имя" != $('[name="cb-name"]').val()) ||
                    ($('[name="cb-name"]').addClass("e-aqs-form-err"), $('[name="cb-name"]').val("Введите ваше имя"), $(this).prop("disabled", !1), e++),
                    $(this).prop("disabled", !0),
                    0 == $('[name="cb-tel"]').val().length || "Введите номер телефона" == $('[name="cb-tel"]').val()
                        ? ($('[name="cb-tel"]').addClass("e-aqs-form-err"), $('[name="cb-tel"]').val("Введите номер телефона"), $(this).prop("disabled", !1), e++)
                        : ($('[name="cb-tel"]').val().length < 11 || 13 < $('[name="cb-tel"]').val().length) &&
                          ($('[name="cb-tel"]').addClass("e-aqs-form-err"), $('[name="cb-tel"]').val("Неверный формат номера"), $(this).prop("disabled", !1), e++),
                    0 == $("#cb_policy").prop("checked") && ($(".cb_policy_label").addClass("e-aqs-form-err"), e++),
                    0 == e &&
                        ((a = hash_data($('[name="cb-tel"]').val(), a)),
                        "" == $("#cb-page").val() && $("#cb-page").val("https://perfom-decor.ru/question_service/ask.php"),
                        $.ajax({
                            type: "POST",
                            url: "/ajax/question_service.php?type=call_back&new_val=" + a,
                            data: $("#callBackForm").serialize(),
                            success: function (e) {
                                console.log(e),
                                    $("#callBackForm").trigger("reset"),
                                    $('[data-type="cb-but"]').hide(),
                                    $(".call-back-rqst").show(),
                                    $(".call-back-rqst").css("z-index", "5"),
                                    $(".call-back-rqst").animate({ opacity: 1 }, 1e3),
                                    setTimeout(function () {
                                        $(".call-back-rqst").animate({ opacity: 0 }, 1e3), $(".call-back-rqst").css("z-index", "-1");
                                    }, 5e3),
                                    setInterval(function () {
                                        $(".call-back-rqst").hide(), $('[data-type="cb-but"]').show();
                                    }, 5e3),
                                    $('[data-type="cb-but"]').prop("disabled", !1);
                            },
                        }));
            }),
            $('[data-type="dealer-send"]').click(function () {
                $(this).prop("disabled", !0);
                var e,
                    a,
                    t,
                    s,
                    i = 0;
                $('[name="ap-subj"]').each(function () {
                    $(this).prop("checked") && (i++, (currInp = $(this)), console.log("ololo"));
                }),
                    0 == i
                        ? (alert("Выберите новую тему!"), $('[data-type="dealer-send"]').prop("disabled", !1))
                        : ((e = currInp.attr("data-type")),
                          (a = $(".e-qs-user").attr("data-user")),
                          console.log(currInp.val()),
                          "send-subj" == e
                              ? ((e = "rdrct"), (s = "subj"), (t = "Вопрос перенаправлен"))
                              : "send-spec" == e
                              ? ((e = "rdrct"), (s = "spec"), (t = "Вопрос перенаправлен"))
                              : "send-dlr" == e && ((e = "dealer"), (s = ""), (t = "Вопрос перенаправлен дилеру")),
                          $.ajax({
                              type: "POST",
                              url: "/ajax/question_service.php?type=" + e + "&send=" + s + "&name=" + a,
                              data: $('[data-type="new-subj-form"]').serialize(),
                              error: function () {
                                  request.status;
                                  alert("Произошла ошибка, повторите попытку еще раз.");
                              },
                              success: function (e) {
                                  "error" != e
                                      ? ($('[data-type="new-subj-form"]').trigger("reset"),
                                        $(".e-ap-redirect-btn").removeClass("e-ap-redirect-btn-act"),
                                        $(".e-ap-redirect-btn").hide(),
                                        $(".e-ap-headers-buttons").hide(),
                                        $(".e-ap-new-subj-message").html(e),
                                        $(".e-ap-new-subj-message").show(),
                                        $('[data-type="ap-putoff"]').hide(),
                                        $(".e-ap-answ").hide(),
                                        $(".e-ap-headers-buttons-stat").html(t),
                                        $(".e-ap-headers-buttons-stat").addClass("black"),
                                        $("*.e-ap-new-subj-items-value").hide(),
                                        $('[data-type="dealer-send"]').prop("disabled", !1))
                                      : alert("Произошла ошибка, повторите попытку еще раз.");
                              },
                          }));
            }),
            $('[data-type="dealer-send-reset"]').click(function () {
                var e = $(".e-ap-new-subj").find(".active"),
                    a = $(this).parents(".e-ap-redirect-btn");
                e.removeClass("active"), a.removeClass("e-ap-redirect-btn-act"), $("*.e-ap-new-subj-items-value").hide(), $('[data-type="dealer-send"]').prop("disabled", !1);
            }),
            $('[name="dealer-report"]').focus(function () {
                $('[data-type="answ-plchldr"]').css("opacity", "0"), $('[data-type="report-send"]').prop("disabled", !1);
            }),
            $('[name="dealer-report"]').blur(function () {
                "" == $(this).val() && $('[data-type="answ-plchldr"]').css("opacity", "1");
            }),
            $('[data-type="report-reset"]').click(function () {
                $('[data-type="answ-plchldr"]').css("opacity", "1"), $('[data-type="report-send"]').prop("disabled", !1);
            }),
            $('[data-type="report-send"]').click(function () {
                if (($(this).prop("disabled", !0), "" == $('[name="dealer-report"]').val())) alert("Необходимо заполнить поле комментария!"), $('[data-type="report-send"]').prop("disabled", !1);
                else {
                    var t = $('[data-type="ap-title"]').attr("data-id");
                    let e = new URL(document.location).searchParams,
                        a = e.get("d") ? e.get("d") : "";
                    $.ajax({
                        type: "POST",
                        url: "/ajax/question_service.php?type=report&id=" + t + "&d=" + a,
                        data: $('[data-type="ap-report"]').serialize(),
                        error: function () {
                            request.status;
                            alert("Произошла ошибка, повторите попытку еще раз.");
                        },
                        success: function (e) {
                            "error" != e ? location.reload() : alert("Произошла ошибка, повторите попытку еще раз.");
                        },
                    });
                }
            }),
            $('[data-type="new-subj-reg"]').click(function () {
                var e;
                $('[data-type="reg-list"]')
                    .find("a")
                    .each(function () {
                        $(this).attr("data-type", "region_change_link");
                    }),
                    $('[data-type="geo-open"]').removeClass("icon-geo"),
                    $('[data-type="geo-open"]').addClass("icon-close"),
                    $('[data-type="geo-open"]').css("z-index", "10"),
                    $('[data-type="geo-open"]').attr("data-type", "qs-geo-close"),
                    $('[data-type="reg-list"]').slideDown(),
                    void 0 === e ? (e = $('[data-type="reg-list-scroll"]').jScrollPane({ showArrows: !1, maintainPosition: !1 }).data("jsp")) : e.reinitialise(),
                    $("body").addClass("disabled");
            }),
            $("header").on("click", '[data-type="qs-geo-close"]', function () {
                $(this).css("z-index", "0"),
                    $(this).attr("data-type", "geo-open"),
                    $(this).addClass("icon-geo"),
                    $(this).removeClass("icon-close"),
                    $('[data-type="reg-list"]').slideUp(),
                    $('[data-type="reg-list"]')
                        .find("a")
                        .each(function () {
                            $(this).attr("data-type", "choose-reg");
                        }),
                    $('[data-type="curr-reg"]').html(),
                    $("body").removeClass("disabled"),
                    $('*[data-type="reg-count-wrap"]').removeClass("active"),
                    $('*[data-type="reg-city"]').hide();
            }),
            $("header").on("click", '[data-type="region_change_link"]', function () {
                var e = $(this).attr("data-value"),
                    a = $('[data-type="ap-title"]').attr("data-id"),
                    t = $(".e-qs-user").attr("data-user");
                $.ajax({
                    type: "POST",
                    url: "/ajax/question_service.php?type=rdrct&send=reg&name=" + t,
                    data: { reg: e, "ap-subj-id": a },
                    error: function () {
                        request.status;
                        alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                    success: function (a) {
                        if ("error" != a) {
                            $('[data-type="new-subj-form"]').trigger("reset"),
                                $(".e-ap-redirect-btn").removeClass("e-ap-redirect-btn-act"),
                                $(".e-ap-redirect-btn").hide(),
                                $(".e-ap-headers-buttons").hide(),
                                $(".e-ap-new-subj-message").html(a),
                                $(".e-ap-new-subj-message").show(),
                                $('[data-type="ap-putoff"]').hide(),
                                $(".e-ap-answ").hide(),
                                $(".e-ap-headers-buttons-stat").html("Вопрос перенаправлен"),
                                $(".e-ap-headers-buttons-stat").addClass("black"),
                                $("*.e-ap-new-subj-items-value").hide(),
                                $('[data-type="dealer-send"]').prop("disabled", !1);
                            let e = $("header").find('[data-type="qs-geo-close"]');
                            e.css("z-index", "0"),
                                e.attr("data-type", "geo-open"),
                                e.addClass("icon-geo"),
                                e.removeClass("icon-close"),
                                $('[data-type="reg-list"]').slideUp(),
                                $('[data-type="reg-list"]')
                                    .find("a")
                                    .each(function () {
                                        $(this).attr("data-type", "choose-reg");
                                    }),
                                $('[data-type="curr-reg"]').html(),
                                $("body").removeClass("disabled"),
                                $('*[data-type="reg-count-wrap"]').removeClass("active"),
                                $('*[data-type="reg-city"]').hide();
                        } else alert("Произошла ошибка, повторите попытку еще раз.");
                    },
                });
            });
    });
var idNum = 0,
    data = "elastic";
function ap_putoff_mob() {
    var a = $('[data-type="ap-putoff-mob"]');
    a.prop("disabled", !0);
    var e = $('[data-type="ap-title"]').attr("data-id");
    $.ajax({
        type: "POST",
        url: "/ajax/question_service.php?type=putoff&id=" + e,
        error: function () {
            request.status;
            alert("Произошла ошибка, повторите попытку еще раз.");
        },
        success: function (e) {
            "error" != e
                ? (a.remove(),
                  $(".e-ap-redirect-btn").before('<div class="e-ap-putoff-btn red" data-type="ap-renew-mob" onclick="ap_renew_mob()">Возобновить</div>'),
                  $(".e-ap-headers-buttons-stat").html("Вопрос отложен"),
                  $(".e-ap-headers-buttons-stat").addClass("black"))
                : alert("Произошла ошибка, повторите попытку еще раз.");
        },
    });
}
function ap_renew_mob() {
    var a = $('[data-type="ap-renew-mob"]'),
        e = $('[data-type="ap-title"]').attr("data-id");
    $.ajax({
        type: "POST",
        url: "/ajax/question_service.php?type=puton&id=" + e,
        error: function () {
            request.status;
            alert("Произошла ошибка, повторите попытку еще раз.");
        },
        success: function (e) {
            "error" != e
                ? (a.remove(),
                  $(".e-ap-redirect-btn").before('<div class="e-ap-putoff-btn" data-type="ap-putoff-mob" onclick="ap_putoff_mob()">Отложить</div>'),
                  $(".e-ap-headers-buttons-stat").html("Вопрос прочитан"),
                  $(".e-ap-headers-buttons-stat").addClass("black"))
                : alert("Произошла ошибка, повторите попытку еще раз.");
        },
    });
}
function hash_data(e, a) {
    (e = (e = e.replace(/[\n\r]/g, "")).split("")), (e = e[2] + a + e[5] + a + e[8]);
    return md5(e);
}
$("body").on("keyup", 'textarea[data^="' + data + '"]', function () {
    $(this).attr("data") == "" + data && ($(this).attr({ style: "overflow:hidden;" + $(this).attr("style"), data: "" + $(this).attr("data") + idNum }), idNum++),
        (tData = $(this).attr("data")),
        0 == $('div[data="' + tData.replace("" + data, "clone") + '"]').size()
            ? ((attr =
                  'style="display:none;padding:' +
                  $(this).css("padding") +
                  ";width:" +
                  $(this).css("width") +
                  ";min-height:" +
                  $(this).css("height") +
                  ";font-size:" +
                  $(this).css("font-size") +
                  ";line-height:" +
                  $(this).css("line-height") +
                  ";font-family:" +
                  $(this).css("font-family") +
                  ";white-space:" +
                  $(this).css("white-space") +
                  ";word-wrap:" +
                  $(this).css("word-wrap") +
                  ';letter-spacing:0.2px;" data="' +
                  tData.replace("" + data, "clone") +
                  '"'),
              (clone = "<div " + attr + ">" + $(this).val() + "</div>"),
              $("body").prepend(clone),
              idNum++)
            : ($('div[data="' + tData.replace("" + data, "clone") + '"]').html($(this).val()), $(this).css("height", "" + $('div[data="' + tData.replace("" + data, "clone") + '"]').css("height")));
}),
    (function (p) {
        p.fn.jTruncate = function (r) {
            r = p.extend({ length: 300, minTrail: 20, moreText: "more", lessText: "less", ellipsisText: "...", moreAni: "", lessAni: "" }, r);
            return this.each(function () {
                var e,
                    a,
                    t,
                    s,
                    i,
                    d = p(this),
                    n = d.html();
                n.length > r.length + r.minTrail
                    ? -1 != (e = n.indexOf(" ", r.length)) &&
                      ((e = n.indexOf(" ", r.length)),
                      (a = n.substring(0, e)),
                      (n = n.substring(e, n.length - 1)),
                      d.html(a + '<span class="truncate_ellipsis">' + r.ellipsisText + '</span><span class="truncate_more">' + n + "</span>"),
                      d.find(".truncate_more").css("display", "none"),
                      d.append('<div class="clearboth"><a href="#" class="truncate_more_link truncate_more_i">' + r.moreText + "</a></div>"),
                      (i = p(".truncate_more_link", d)),
                      (t = p(".truncate_more", d)),
                      (s = p(".truncate_ellipsis", d)),
                      i.click(function () {
                          return (
                              i.text() == r.moreText
                                  ? (t.show(r.moreAni), i.text(r.lessText), i.removeClass("truncate_more_i"), i.addClass("truncate_less_i"), s.css("display", "none"))
                                  : (t.hide(r.lessAni), i.text(r.moreText), i.addClass("truncate_more_i"), i.removeClass("truncate_less_i"), s.css("display", "inline")),
                              !1
                          );
                      }))
                    : (d.append('<div class="clearboth"><a href="#" class="truncate_more_link truncate_more_i">' + r.moreText + "</a></div>"),
                      (i = p(".truncate_more_link", d)).click(function () {
                          return (
                              i.hasClass("truncate_more_i") ? (i.text(r.lessText), i.removeClass("truncate_more_i"), i.addClass("truncate_less_i")) : (i.text(r.moreText), i.addClass("truncate_more_i"), i.removeClass("truncate_less_i")), !1
                          );
                      }));
            });
        };
    })(jQuery),
    $(document).ready(function () {
        function a() {
            let a = $('[data-type="search-faq"]'),
                e = $('[name="faq"]').val();
            0 < e.length &&
                (a.attr("disabled", "disabled"),
                $(".faq-answer-wrap").html("<div class='faq-wait'><img src='/images/AjaxLoader.gif' alt='Wait...'></div>"),
                $.get("/ajax/get_faq.php", { faq: e }, function (e) {
                    e = $.parseJSON(e);
                    $(".faq-answer-wrap").html(e), a.closest("form").trigger("reset"), a.removeAttr("disabled", "disabled");
                }));
        }
        $(".faq-answer-wrap").on("click", ".faq-answer-show", function () {
            $(this).hasClass("active")
                ? ($(this).closest(".faq-answer-item").find(".faq-answer").slideUp(), $(this).html('<span>Показать ответ</span> <i class="icon-angle-down"></i>'), $(this).removeClass("active"))
                : ($(this).closest(".faq-answer-item").find(".faq-answer").slideDown(), $(this).html('<span>Свернуть ответ</span> <i class="icon-angle-up"></i>'), $(this).addClass("active"));
        }),
            $(".faq-tag").on("click", function () {
                $(this)
                    .closest(".faq-tags-wrap")
                    .find(".faq-tag")
                    .each(function () {
                        $(this).removeClass("active");
                    }),
                    $(this).addClass("active"),
                    $.get("/ajax/get_faq.php", { tag: $(this).attr("data-val") }, function (e) {
                        e = $.parseJSON(e);
                        $(".faq-answer-wrap").html(e);
                    });
            }),
            0 < $("#faq-aqsMainForm").length && ($(".right-part-form").sticky({ topSpacing: 146, bottomSpacing: 366 }), $(".right-part-form").tinyscrollbar()),
            $('[data-type="search-faq"]').on("click", function () {
                a();
            }),
            $(".faq-search-wrap input").keydown(function (e) {
                13 == e.keyCode && (e.preventDefault(), a());
            });
    }),
    $(document).ready(function () {
        $('[data-type="need-comm"]').on("click", function () {
            let a = $(this).closest(".e-ap-need-comm-wrap"),
                e = $(this),
                t = '<p class="need-comm-send"><i class="new-icomoon icon-check-1"></i>Запрошен комментарий</p>',
                s = e.closest('[data-type="question-wrap"]').find('[data-type="ap-title"]').attr("data-id");
            a.html("ожидайте..."),
                $.get("/ajax/question_service.php", { id: s, type: "need_comm" }, function (e) {
                    (t += '<div class="need-comm-info"><span>Информация по дилеру:</span>' + e + "</div>"), a.html(t);
                });
        });
    });
