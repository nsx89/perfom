/**
 * Created by nadida on 22.11.2018.
 */
const managerScroll = $('[data-type="manager-list"]').jScrollPane({
    showArrows: false,
    maintainPosition: false
}).data('jsp');
$(window).on('resize', function() {
    applyOMSticky();
})

$(document).ready(function(){
    let currTab = location.hash;
    if(location.hash == '' && $('[data-type="moder-tabs"]').length > 0) {
        currTab = '#etc1';
    }
    if($('[data-type="moder-tabs"]').length == 0) currTab = '#etc4';
    if(currTab == '#etc4') {
        $('[data-type="mod-res"]').hide();
        $('[data-type="md-dealer"]').show();
    } else {
        $('[data-type="mod-res"]').show();
        $('[data-type="md-dealer"]').hide();
    }
    $('[data-type="moder-tabs"]').find('[data-type="main-tab"]').each(function() {
        $(this).removeClass('active');
    })
    $('[href="'+currTab+'"]').addClass('active');
    $('[data-type="main-tab-cont"]').each(function() {
        $(this).removeClass('active');
    })
    $(currTab).addClass('active');
    setTimeout(function() {
        if(currTab !== '#etc4' && $('[data-type="moder-tabs"]').find('.active').length == 0) {
            $('[href="#etc1"]').addClass('active');
            $('#etc1').addClass('active');
            $('[data-type="mod-res"]').show();
            $('[data-type="md-dealer"]').hide();
        }
    },100)

    applyOMSticky();
    $('[data-type="pacc-period"]').on('click',function(){
        if($(this).attr('data-type-val')=='period') {
            $('[data-type="pacc-period"]').each(function(){
                $(this).removeClass('active');
                if($(this).attr('data-type-val')=='period') {
                    $('[data-type="pacc-period-choose"]').addClass('unact');
                    $('[ data-type="period-limit"]').each(function(){
                        $(this).val('');
                    })
                }
            })
            $(this).addClass('active');
            if($(this).attr('data-type-val')=='period') {
                $('[data-type="pacc-period-choose"]').removeClass('unact');
            }
        }
        else {
            $('[data-type="pacc-period"]').each(function() {
                $(this).removeClass('active');
            })
            $('[data-type="pacc-period-choose"]').addClass('unact');
            $('[ data-type="period-limit"]').each(function(){
                $(this).val('');
            })
            $(this).addClass('active');
            var qmDate = {};
            val = $(this).attr('data-val');
            qmDate['val'] = val;

            if(val == "2" || val == "3" || val == "4") {
                qmDate['from'] = $(this).attr('data-from');
            }
            else if (val == "1") {
                qmDate['from'] = "";
            }
            qmDate['to'] = "";
            var qm_mod_date = JSON.stringify(qmDate);
            $.cookie('order_mod_date', qm_mod_date, {domain: domain, path: '/'});
            //var mess = '<span>Внимание!</span> Изменение фильтров<br>может занять некоторое время';
            //$('[data-type="pacc-mess"]').find('p').html(mess);
            //$('[data-type="pacc-mess"]').fadeIn();
            //location.reload();
        }
    })
      $('.pacc-nav-filt').on('click','[data-type="show-period"]',function(){
        //console.log('ololo');
        dateFrom = $('[name="qm-from"]').val();
        dateTo = $('[name="qm-to"]').val();
        if(dateFrom == "" && dateTo == "") {
          alert ("Введите дату!")
        }
        else {
          var qmDate = {};
          qmDate['from'] = dateFrom;
          qmDate['to'] = dateTo;
          qmDate['val'] = "0";
          var qm_mod_date = JSON.stringify(qmDate);
          $.cookie('order_mod_date', qm_mod_date, {domain: domain, path: '/'});
          //var mess = '<span>Внимание!</span> Изменение фильтров<br>может занять некоторое время';
          //$('[data-type="pacc-mess"]').find('p').html(mess);
          //$('[data-type="pacc-mess"]').fadeIn();
          //location.reload();
        }
      })
    $('[data-type="remove-date"]').on('click',function(){
        var qmDate = {};
        qmDate['val'] = "1";
        qmDate['from'] = "";
        qmDate['to'] = "";
        var qm_mod_date = JSON.stringify(qmDate);
        $.cookie('order_mod_date', qm_mod_date, {domain: domain, path: '/'});
        //var mess = '<span>Внимание!</span> Изменение фильтров<br>может занять некоторое время';
        //$('[data-type="pacc-mess"]').find('p').html(mess);
        //$('[data-type="pacc-mess"]').fadeIn();
        //location.reload();
    })
})
/**
 * choose new region
 */
$('[data-type="clear-filt-geo"]').on('click',function() {
  $.cookie('filt_reg', '', {domain: domain, path: '/'});
    $('[data-type="filter-reg"]').find('.e-new-act-name').html('Все');
    $('[data-type="filter-reg"]').find('.e-new-sort-act').removeClass('active');
})
$('.pacc-nav').on('click','[data-type="filter-reg"]',function() {
    let iconGeo = $('[data-type="geo-open"]'),
        filtReg = $('[data-type="filter-reg"]').find('.e-new-act-name').html(),
        geoReg = $('[data-type="curr-reg"]').html();
    iconGeo.css('z-index','10');
    iconGeo.attr("data-type","geo-close-reg");
    iconGeo.removeClass('icon-geo');
    iconGeo.addClass('icon-close');
    $('[data-type="curr-reg"]').html(filtReg);
    $('#dropdown-down').find('[data-type="choose-reg"]').each(function() {
        $(this).attr('data-type','choose-reg-filt');
    })
    $('[data-type="reg-list"]').slideDown();
    if(typeof regScroll === 'undefined') {
        var regScroll = $('[data-type="reg-list-scroll"]').jScrollPane({
            showArrows: false,
            maintainPosition: false
        }).data('jsp');
    } else {
        regScroll.reinitialise();
    }
    $('body').addClass('disabled');

    $('#dropdown-down').on('click','[data-type="reg-list"] [data-type="choose-reg-filt"]',function() {
        let regionId = $(this).attr('data-value'),
            regionName = $(this).html();
        $('[data-type="curr-reg"]').html(regionName);
        $('[data-type="filter-reg"]').find('.e-new-act-name').html(regionName);
        $('[data-type="filter-reg"]').find('.e-new-sort-act').addClass('active');
        $.cookie('filt_reg', regionId, {domain: domain, path: '/'});
        iconGeo.css('z-index','0');
        iconGeo.attr("data-type","geo-open");
        iconGeo.addClass('icon-geo');
        iconGeo.removeClass('icon-close');
        $('[data-type="reg-list"]').slideUp(function(){
            $('[data-type="curr-reg"]').html(geoReg);
        });
        $('body').removeClass('disabled');
        $('*[data-type="reg-count-wrap"]').removeClass('active');
        $('*[data-type="reg-city"]').hide();
        $('#dropdown-down').find('[data-type="choose-reg-filt"]').each(function() {
            $(this).attr('data-type','choose-reg');
        })
    })

    $('header').on('click','[data-type="geo-close-reg"]',function () {
        iconGeo.css('z-index','0');
        iconGeo.attr("data-type","geo-open");
        iconGeo.addClass('icon-geo');
        iconGeo.removeClass('icon-close');
        $('[data-type="reg-list"]').slideUp(function(){
            $('[data-type="curr-reg"]').html(geoReg);
        });
        $('body').removeClass('disabled');
        $('*[data-type="reg-count-wrap"]').removeClass('active');
        $('*[data-type="reg-city"]').hide();
        $('#dropdown-down').find('[data-type="choose-reg-filt"]').each(function() {
            $(this).attr('data-type','choose-reg');
        })
    })
})
$('[data-type="order-hide"]').on('click',function() {
    var id = $('[data-type="order-id"]').attr('data-id');
    $.get('/moderation/ajax_mod.php?event=remove&id='+id, function (data) {
        data = '<p class="e-final-text  e-final-title">Удаление заказа</p>';
        data += '<p class="e-final-text">Заказ успешно удалён.</p>';
        data += '<a href="/moderation/" class="e-final-btn">Вернуться на страницу модерации</a>';
        $('[data-type="popup-mess"]').html(data);
        $('[data-type="popup-mess"]').fadeIn();
        $('[data-type="overlay"]').fadeIn();
        $('body').addClass('disabled');
    })
})
$('[data-type="main-tab"]').on('click',function() {
    $('[data-type="mod-res"]').html('');
    if($(this).attr('data-val') == 'dealers') {
        $('[data-type="mod-res"]').hide();
        $('[data-type="md-dealer"]').show();
    } else {
        $('[data-type="mod-res"]').show();
        $('[data-type="md-dealer"]').hide();
    }
})

/**
 * показать отчет
 */
$('[data-type="show-online-store-report"]').on('click',function() {
    var err = 0;
    if($(this).closest('.pacc-nav').find('[data-type-val="period"]').hasClass('active')) {
        dateFrom = $('[name="qm-from"]').val();
        dateTo = $('[name="qm-to"]').val();
        if(dateFrom == "" && dateTo == "") {
            alert ("Введите дату!")
            err++;
        }
        else {
            var qmDate = {};
            qmDate['from'] = dateFrom;
            qmDate['to'] = dateTo;
            qmDate['val'] = "0";
            var qm_mod_date = JSON.stringify(qmDate);
            $.cookie('order_mod_date', qm_mod_date, {domain: domain, path: '/'});
        }
    }

    if(err == 0) {
        var mess = '<span>Внимание!</span> Формирование отчёта<br>может занять некоторое время';
        $('[data-type="pacc-mess"]').find('p').html(mess);
        $('[data-type="pacc-mess"]').fadeIn();
        $('[data-type="mod-res"]').html('');
        $.get('/moderation/ajax_online-store.php', function (data) {
            if (data != '') {
                data = $.parseJSON(data);
                $('[data-type="pacc-mess"]').fadeOut();
                $('[data-type="mod-res"]').html(data.stat + data.report);
                applyOMSticky();
            }
        })
    }
})
/**
 * сохранить pdf
 */
$('[data-type="mod-res"]').on('click','[data-type="save-pdf"]',function() {
  $.get('/ajax/save_stat_pdf.php', function (data) {
    window.location.href = data;
    //$.cookie('carturl', ret, {domain: domain, path: '/'});
  });
  return false;
});
/**
 * выбрать отчёт
 */
$(document).ready(function(){
    $('[data-type="choose-rep"]').on('click',function() {
        if(!$(this).hasClass('active')) {
            var val = $(this).attr('data-val');
            var tit = $(this).attr('data-tit');
            $('[data-type="choose-rep"]').each(function(){
                $(this).removeClass('active');
            })
            $('[data-type="rep-tab"]').each(function(){
                if($(this).hasClass('active')) {
                    $(this).find('.pacc-nav-filt-wrap').each(function() {
                        if($(this).attr('data-type') == 'date') {
                            noactiveDateFilter();
                            /*$(this).find('[data-val="2"]').addClass('active');
                            var wrap = $(this).find('[data-val="2"]').closest('.period-wrap');
                            wrap.find('[data-type="rep-period-choose"]').removeClass('unact');
                            var dateNow = new Date();
                            var defaultDate = '0' + (parseInt(dateNow.getMonth())+1) + '.' + dateNow.getFullYear();
                            wrap.find('input').val(defaultDate);*/
                        } else {
                            var type = $(this).attr('data-type');
                            $(this).find('.e-new-sort-type-item-wrap').each(function(){
                                $(this).removeClass('active');
                            })
                            sort_close($(this));
                            $(this).find('.e-new-act-name').html('Выбрать');
                        }
                    })
                    $(this).removeClass('active');
                }
            })
            $('[data-type="report-body"]').html('');
            $(this).addClass('active');
            $('[data-val="'+tit+'"]').addClass('active');
            $('[data-type="mod-res"]').html('');
        }
    })
})
/**
 * отчёт: фильтр отчетный период
 */
$(document).ready(function(){
    $('[data-type="rep-period"]').on('click',function(){
        noactiveDateFilter();
        $(this).addClass('active');
        if($(this).attr('data-type-val')=='period' || $(this).attr('data-type-val')=='period-month') {
            var wrap = $(this).closest('.period-wrap');
            wrap.find('[data-type="rep-period-choose"]').removeClass('unact');
        }
    })
    $('[data-type="remove-date-rep"]').on('click',function(){
        noactiveDateFilter();
        $('[data-val="1"]').addClass('active');
    })
    var ymc = ymCal(
        $( "#mFrom" ),
        null,
        "bottom",
        null,
        null,
        function( event, month, year, misc ) {
            if(event == 'ok') {
                if(month < 10) month = '0'+month;
                var outputDate = month+'.'+year;
                $('#mFrom').val(outputDate);
            }
        },
        5000,
        -8
    ),
        ymc1 = ymCal(
            $( "#mFromOnline" ),
            null,
            "bottom",
            null,
            null,
            function( event, month, year, misc ) {
                if(event == 'ok') {
                    if(month < 10) month = '0'+month;
                    var outputDate = month+'.'+year;
                    $('#mFromOnline').val(outputDate);
                }
            },
            5000,
            -8
        )

})
function noactiveDateFilter() {
    $('[data-type="rep-period"]').each(function(){
        $(this).removeClass('active');
        if($(this).attr('data-type-val')=='period') {
            var wrap = $(this).closest('.period-wrap');
            wrap.find('[data-type="rep-period-choose"]').addClass('unact');
            wrap.find('[ data-type="period-limit"]').each(function(){
            $(this).val('');
            })
        }
    })
}
/**
 * открыть/закрыть фильтры
 */
function sort_close(wrap) {
    wrap.find('[data-type="sort"]').hide()
    wrap.find('[data-type="open-sort"]').removeClass('icon-angle-up');
    wrap.find('[data-type="open-sort"]').addClass('icon-angle-down');
    wrap.find('[data-type="show-sort"]').attr('data-act','show');
    wrap.find('[data-type="show-sort"]').removeClass('e-new-filters-sort-act-hover');
    wrap.removeClass('pacc-nav-filt-wrap-manager');
}
$('[data-type="sort-wrap"]').on('click','[data-type="show-sort"]',function(){
    var wrap = $(this).closest('.pacc-nav-filt-wrap');
    if(wrap.attr('data-type') == 'manager') {
        wrap.addClass('pacc-nav-filt-wrap-manager');
    }
    wrap.find('[data-type="sort"]').show();
    wrap.find('[data-type="open-sort"]').removeClass('icon-angle-down');
    wrap.find('[data-type="open-sort"]').addClass('icon-angle-up');
    wrap.find('[data-type="show-sort"]').attr('data-act','close');
    wrap.find('[data-type="show-sort"]').addClass('e-new-filters-sort-act-hover');
    if(wrap.find('[data-type="manager-list"]').length > 0) {
        managerScroll.reinitialise();
    }
    return false;
})
$('[data-type="sort-wrap"]').on('click','[data-act="close"]',function() {
    var wrap = $(this).closest('.pacc-nav-filt-wrap');
    sort_close(wrap);
    return false;
})
$(document).mouseup(function (e){
    var div = $('[data-type="status"]');
    if (!div.is(e.target) && div.has(e.target).length === 0 && !div.find('[data-type="show-sort"]').is(e.target) && div.find('[data-type="show-sort"]').has(e.target).length === 0) {
        sort_close(div);
        return false; // скрываем его
    }
});
$(document).mouseup(function (e){
    var div = $('[data-type="manager"]');
    if (!div.is(e.target) && div.has(e.target).length === 0 && !div.find('[data-type="show-sort"]').is(e.target) && div.find('[data-type="show-sort"]').has(e.target).length === 0) {
        sort_close(div);
        return false; // скрываем его
    }
});
$(document).mouseup(function (e){
    var div = $('[data-type="payment"]');
    if (!div.is(e.target) && div.has(e.target).length === 0 && !div.find('[data-type="show-sort"]').is(e.target) && div.find('[data-type="show-sort"]').has(e.target).length === 0) {
        sort_close(div);
        return false; // скрываем его
    }
});
$(document).mouseup(function (e){
    var div = $('[data-type="delivery"]');
    if (!div.is(e.target) && div.has(e.target).length === 0 && !div.find('[data-type="show-sort"]').is(e.target) && div.find('[data-type="show-sort"]').has(e.target).length === 0) {
        sort_close(div);
        return false; // скрываем его
    }
});
/**
 * фильтры
 */
$('[data-type="sort-param"]').click(function () {
    var wrap = $(this).closest('.pacc-nav-filt-wrap');
    var type = wrap.attr('data-type');
    var val = $(this).attr('data-val');
    var valHtml = $(this).html();
    //if(type == 'manager') {
        if($(this).closest('.e-new-sort-type-item-wrap').hasClass('active')) {
            $(this).closest('.e-new-sort-type-item-wrap').removeClass('active');
            generateItems(wrap);
        } else {
            $(this).closest('.e-new-sort-type-item-wrap').addClass('active');
            generateItems(wrap);
        }
})
/**
 * фильтр менеджеров
 */
$('[data-type="sort-manager"]').on('click',function() {
    var wrap = $(this).closest('.pacc-nav-filt-wrap');

})
/**
 * сбросить фильтры
 */
$('[data-type="clear-filt"]').on('click',function() {
    var wrap = $(this).closest('.pacc-nav-filt-wrap');
    var type = wrap.attr('data-type');
    wrap.find('.e-new-sort-type-item-wrap').each(function(){
        $(this).removeClass('active');
    })
    sort_close(wrap);
    wrap.find('.e-new-act-name').html('Выбрать');
})
function generateItems(wrap) {
    var tit = '';
    wrap.find('[data-type="sort-param"]').each(function() {
        if($(this).closest('.e-new-sort-type-item-wrap').hasClass('active')) {
            tit += $(this).html() + ', ';
        }
    })
    if(tit == '') {
        tit = 'Выбрать';
    } else {
        tit = tit.substr(0,tit.length-2);
    }
    wrap.find('.e-new-act-name').html(tit);
}
/**
 * скачать отчет
 */
$('[data-type="download-report"]').on('click',function() {

    var link = $(this);

    var dataArr = collect_filters($(this));

    var mess = '<span>Внимание!</span> Формирование отчёта<br>может занять некоторое время';
    $('[data-type="pacc-mess"]').find('p').html(mess);
    $('[data-type="pacc-mess"]').fadeIn();

    $.get('/moderation/get_order_m.php', dataArr, function (data) {
        if (data != '') {
            console.log(data);
            $('[data-type="pacc-mess"]').fadeOut();
            window.location.href = data;
        }
    });
})

/**
 * показать отчет
 */
$('[data-type="show-report"]').on('click',function() {

    var dataArr = collect_filters($(this));

    var mess = '<span>Внимание!</span> Формирование отчёта<br>может занять некоторое время';
    $('[data-type="pacc-mess"]').find('p').html(mess);
    $('[data-type="pacc-mess"]').fadeIn();

    $.post('/moderation/show_report.php', dataArr, function (data) {
        $('[data-type="pacc-mess"]').fadeOut();
        if (data != '') {
            data = $.parseJSON(data);
            $('[data-type="mod-res"]').html(data);
            applyOMSticky();
        }
    });
})

/**
 * изменение статуса
 */
$('[data-type="new-status"]').on('click',function() {
    $('[data-type="status-list"]').fadeIn();
})
$(document).mouseup(function (e) {
    var container = $('[data-type="status-list"]');
    if (container.is(":visible") && container.has(e.target).length === 0 && !$(event.target).is('[data-type="status-list"]')){
        $('[data-type="status-list"]').fadeOut();
        return false;
    }
});
$('[data-type="stat-val"]').on('click',function() {
    var radioBtn = $(this);
    var val = $(this).attr('data-val');
    var status = $(this).html();
    var id = $('[data-type="order-id"]').attr('data-id');
    var moderator = $('.pers-acc-top').attr('user-id');
    var reason = '';
    if(val == 'canceled') {
        reason = $('.pacc-main-info').find('[name="reason-cancel"]').val();
    } else {
        $('[name="reason-cancel"]').val('');
    }
    $.post('ajax_mod.php', {event:'status',val:val,reason:reason,id:id,moderator:moderator}, function (data) {
        $('[data-type="stat-val"]').each(function() {
            $(this).removeClass('active');
        })
        radioBtn.addClass('active');
        $('[data-type="stat-wrap"]').html('<div class="order-stat">'+status+'</div>');
        if(val == 'shipped') {
            $('[data-type="stat-wrap"]').find('.order-stat').addClass('finished');
        } else {
            $('[data-type="stat-wrap"]').find('.order-stat').removeClass('finished');
        }
        $('[data-type="status-list"]').fadeOut();
    })
})

/**
 * мероприятие: показать отчет
 */
$('[data-type="show-event"]').on('click',function() {
    var mess = '<span>Внимание!</span> Формирование отчёта<br>может занять некоторое время';
    $('[data-type="pacc-mess"]').find('p').html(mess);
    $('[data-type="pacc-mess"]').fadeIn();
    $.post('/moderation/statistics_ajax.php', {type:'show'}, function (data) {
        $('[data-type="pacc-mess"]').fadeOut();
        if (data != '') {
            data = $.parseJSON(data);
            $('[data-type="mod-res"]').html(data);
            applyOMSticky();
        }
    });
})
/**
 * мероприятие: скачать отчет
 */
$('[data-type="download-event"]').on('click',function() {
    var mess = '<span>Внимание!</span> Формирование отчёта<br>может занять некоторое время';
    $('[data-type="pacc-mess"]').find('p').html(mess);
    $('[data-type="pacc-mess"]').fadeIn();
    $.post('/moderation/statistics_ajax.php', {type:'dwnld'}, function (data) {
        $('[data-type="pacc-mess"]').fadeOut();
        if (data != '') {
            location = 'reports/dealer2023.csv';
        }
    });
})

function collect_filters(btn) {

    var obj = new Object();
    obj.date = new Object();
    obj.status = new Object();
    obj.manager = new Object();
    obj.payment = new Object();
    obj.delivery = new Object();
    obj.type = '';

    var wrap = btn.closest('[data-type="rep-tab"]');

    obj.type = btn.attr('data-val');

    wrap.find('.pacc-nav-filt-wrap').each(function() {

        //дата
        if($(this).attr('data-type') == 'date') {
            $(this).find('[data-type="rep-period"]').each(function(){
                if($(this).hasClass('active')) {
                    if($(this).attr('data-val') == 2) {
                        var dateVal = $(this).closest('.period-wrap').find('[name="qm-from"]').val();
                        dateVal = dateVal.split('.');
                        var lastDay = getLastDayOfMonth(dateVal[1], parseInt(dateVal[0]) - 1);
                        obj.date.from = '01.'+dateVal[0]+'.'+dateVal[1];
                        obj.date.to = lastDay+'.'+dateVal[0]+'.'+dateVal[1];
                    }
                    if($(this).attr('data-val') == 3) {
                        obj.date.from =  $(this).closest('.period-wrap').find('[name="qm-from"]').val();
                        obj.date.to =  $(this).closest('.period-wrap').find('[name="qm-to"]').val();
                    }
                }
            })
        } else {
            //фильтры по полям типа статус заказа
            var arr = new Array();
            $(this).find('[data-type="sort-param"]').each(function(){
                if($(this).closest('.e-new-sort-type-item-wrap').hasClass('active')) {
                    arr.push($(this).attr('data-val'));
                }
            })
            obj[$(this).attr('data-type')] = Object.assign({}, arr);
        }

    });
    return obj;
}

function getLastDayOfMonth(year, month) {
    var date = new Date(year, month + 1, 0);
    return date.getDate();
}
function applyOMSticky() {
    if($('[data-type="om-personal-tabs"]').length > 0) {
        if(window.innerWidth > 1000) {
            let bottomSp = $('.md-edit-contact-left').length > 0 ? $('.md-edit-contact-left').outerHeight() : $('[data-type="mod-res"]').outerHeight();
            $('*[data-type="om-personal-tabs"]').sticky({
                topSpacing: $('header').outerHeight() + 20,
                bottomSpacing: $('footer').outerHeight() + 50 + bottomSp + 55
            });
            //console.log('ololo');
        } else {
            $('*[data-type="om-personal-tabs"]').unstick();
        }
    }
    return false;
}
