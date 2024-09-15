function dwnldTab() {
    //var val = location.hash.replace('#','');
    var val = location.hash;
    if(val != '') {
        $('*[data-type="main-tab"]').each(function() {
            if($(this).attr('href') == val) {
                $(this).addClass('active');
            }
            else {
                $(this).removeClass('active');
            }
        })
        $('[data-type="main-tab-cont"]').each(function() {
            $(this).removeClass('active');
        })
        $(val).addClass('active');
        if(val == '#price') {
            $('[data-type="pricelist-tab"]').each(function(){
                if($(this).hasClass('active')) {
                    var val = $(this).attr("data-val");
                    if($('#'+val).find('*').length == 0) {
                        $('[data-type="dwnld-preloader"]').show();
                        var req = '/ajax/get_pricelist.php?typelist='+val;
                        $.get(req, function(data){
                            var elems = $.parseJSON(data);
                            $('[data-type="dwnld-preloader"]').hide();
                            $('#'+val).html(elems);
                        });
                    }
                }
            })
        }
    } else {
        $('[data-type="main-tab"]').first().addClass('active');
        $($('[data-type="main-tab"]').attr('href')).addClass('active');
    }

}
dwnldTab();

$(document).ready(function() {
    /**
     * 3d модели: по умолчанию грузим интерьерные карнизы
     */
    loadModels(1542);
    loadModels2(1542);
    $('.dwnld-models-filt-cont1').find('[data-id="1542"]').closest('li').addClass('active');
    $('.dwnld-models-filt-cont2').find('[data-id="1542"]').closest('li').addClass('active');

    $('[data-type="pricelist-tab"]').each(function(){
        if($(this).hasClass('active')) {
            var val = $(this).attr("data-val");
            getPrices(val);
        }
    })
})
/*табы на странице 3d*/
$('[data-type="3d-tab"]').on('click',function(){
    $('[data-type="3d-tab"]').each(function(){
        $(this).removeClass('active');
    })
    $('[data-type="3d-tab-item"]').each(function() {
        $(this).removeClass('active');
    })
    var tabLink = $(this).attr('data-val');
    $(this).addClass('active');
    $('#'+tabLink).addClass('active');
    $('#'+tabLink).find('a').first().closest('li').addClass('active');
    loadModels($('#'+tabLink).find('a').first().attr('data-id'));
})

/**
 * сворачиваем/разворачиваем категории
 */
$('[data-type="dwnld-filt"]').on('click',function() {
    let wrap = $(this).closest('.dwnld-models-filt-wrap');
    if($(this).hasClass('closed')) {
        $(this).removeClass('closed');
        $(this).closest('.dwnld-models-filt-wrap').find('.dwnld-models-filt-cont').show();
    } else {
        $(this).addClass('closed');
        $(this).closest('.dwnld-models-filt-wrap').find('.dwnld-models-filt-cont').hide();
    }
})
/**
 * подгружаем через ajax 3d модели
 */
function loadModels(id){
    let req = '/ajax/get3dgroup.php?id='+id,
        wait = '<td class="dwnld-models-wait" colspan="7"><img src="/img/preloader.gif" alt="wait..."></td>';
    $('[data-type="ajax-wrap"]').html(wait);
    $.get(req, function(data){
        var elems = $.parseJSON(data);
        $('[data-type="ajax-wrap"]').html(elems);
    });
    $('[data-type="choose-all"]').each(function(){
        $(this).removeClass('active');
    })
}
/**
 * подгружаем через ajax 2d модели
 */
function loadModels2(id){
    let req = '/ajax/get2dgroup.php?id='+id,
        wait = '<td class="dwnld-models-wait" colspan="4"><img src="/img/preloader.gif" alt="wait..."></td>';
    $('[data-type="ajax-wrap2"]').html(wait);
    $.get(req, function(data){
        var elems = $.parseJSON(data);
        $('[data-type="ajax-wrap2"]').html(elems);
    });
    $('[data-type="choose-all2"]').each(function(){
        $(this).removeClass('active');
    })
}
/**
 * выбираем категория для 3d загрузок
 */
$('.dwnld-models-filt-cont1 a').on('click',function(e){
    e.preventDefault();
    $('.dwnld-models-filt-cont1 li').each(function(){
        $(this).removeClass('active');
    })
    $(this).parents('li').addClass('active');
    let id = $(this).attr('data-id');
    loadModels(id);
})
/**
 * выбираем категория для 3d загрузок
 */
$('.dwnld-models-filt-cont2 a').on('click',function(e){
    e.preventDefault();
    $('.dwnld-models-filt-cont2 li').each(function(){
        $(this).removeClass('active');
    })
    $(this).parents('li').addClass('active');
    let id = $(this).attr('data-id');
    loadModels2(id);
})
/**
 * выбираем форматы 3d загрузок
 */
$('.dwnld-3d-table1').on('click','[data-type="choose-all"]',function(){
    let type = $(this).attr('data-val'),
        isActive = false;
    if($(this).hasClass('active')) isActive = true;
    $('[data-type="choose-all"]').each(function() {
        if($(this).hasClass('active')) {
            let val = $(this).attr('data-val');
            $(this).removeClass('active');
            $('[data-type="'+val+'"]').each(function(){
                $(this).removeClass('active');
            })
        }
    })
    if(!isActive) {
        $(this).addClass('active');
        $('[data-type="'+type+'"]').each(function(){
            $(this).addClass('active');
        })
    }
    $('[data-type="dform-err"]').fadeOut();
})

$('.dwnld-3d-table1').on('click','[ data-click="choose-one"]',function(){
    let type = $(this).attr('data-type'),
        allPoint = $('[data-type="choose-all"][data-val="'+type+'"]'),
        chooseRow = $(this).closest('tr').find('[data-type="choose-row"]');

    if($(this).hasClass('active')){
        $(this).removeClass('active');
        if(allPoint.hasClass('active')) {
            allPoint.removeClass('active');
        }
        if(chooseRow.hasClass('active')) {
            chooseRow.removeClass('active');
        }
    }
    else {
        $(this).addClass('active');
        $('[data-type="dform-err"]').fadeOut();
    }
})

$('.dwnld-3d-table1').on('click','[ data-type="choose-row"]',function(){
    let raw = $(this).closest('tr');
    if($(this).hasClass('active')) {
        $(this).removeClass('active')
        raw.find('span').each(function(){
            $(this).removeClass('active');
        })
    } else {
        $(this).addClass('active')
        raw.find('span').each(function(){
            $(this).addClass('active');
        })
    }
})

/**
 * выбираем форматы 2d загрузок
 */
$('.dwnld-3d-table2').on('click','[data-type="choose-all2"]',function(){
    let type = $(this).attr('data-val'),
        isActive = false;
    if($(this).hasClass('active')) isActive = true;
    $('[data-type="choose-all2"]').each(function() {
        if($(this).hasClass('active')) {
            let val = $(this).attr('data-val');
            $(this).removeClass('active');
            $('[data-type="'+val+'"]').each(function(){
                $(this).removeClass('active');
            })
        }
    })
    if(!isActive) {
        $(this).addClass('active');
        $('[data-type="'+type+'"]').each(function(){
            $(this).addClass('active');
        })
    }
    $('[data-type="dform-err2"]').fadeOut();
})

$('.dwnld-3d-table2').on('click','[ data-click="choose-one2"]',function(){
    let type = $(this).attr('data-type'),
        allPoint = $('[data-type="choose-all2"][data-val="'+type+'"]'),
        chooseRow = $(this).closest('tr').find('[data-type="choose-row2"]');

    if($(this).hasClass('active')){
        $(this).removeClass('active');
        if(allPoint.hasClass('active')) {
            allPoint.removeClass('active');
        }
        if(chooseRow.hasClass('active')) {
            chooseRow.removeClass('active');
        }
    }
    else {
        $(this).addClass('active');
        $('[data-type="dform-err2"]').fadeOut();
    }
})

$('.dwnld-3d-table2').on('click','[ data-type="choose-row2"]',function(){
    let raw = $(this).closest('tr');
    if($(this).hasClass('active')) {
        $(this).removeClass('active')
        raw.find('span').each(function(){
            $(this).removeClass('active');
        })
    } else {
        $(this).addClass('active')
        raw.find('span').each(function(){
            $(this).addClass('active');
        })
    }
})

/**
 * download 3d
 */
$('[data-type="dform"]').on('click contextmenu', function() {
    $('[data-type="overlay"]').show();
});

$('[data-type="dwnld-3d"]').on('click',function() {
    var act = false;
    $('.dwnld-3d-table1').find('[data-click="choose-one"]').each(function(){
        if($(this).hasClass('active')) {
            act = true;
        }
    })
    if(act) {
        $('[data-type="dform"]').fadeIn();
        $('[data-type="overlay"]').fadeIn();
        $('.dform-radio-btns-wrap').show();
        $('.dform-result').hide();
        $('[data-type="dform-cansel"]').show();
        $('[data-type="dform-submit"]').show();
        $('.dform-close').hide();
        $('[data-type="overlay"]').attr('data-val','dwnld');
        $('[data-type="dform-submit"]').removeAttr("disabled");
    }
    else {
        $('[data-type="dform-err"]').fadeIn();
    }
});
$('[data-type="dform-close"]').on('click',function(){
    $('[data-type="dform"]').fadeOut();
    $('[data-type="overlay"]').fadeOut();
    setTimeout(function(){
        $('[data-type="overlay"]').fadeOut();
    }, 200);
});
$('body').on('click','[data-type="overlay"]',function(){
    $('[data-type="dform"]').fadeOut();
    $('[data-type="overlay"]').fadeOut();
});
$('[data-type="radio-new"]').on('click',function(){
    $('[data-type="radio-new"]').each(function() {
        $(this).removeClass('active');
    })
    $(this).addClass('active');
})
$('[data-type="radio-updt"]').on('click',function(){
    $('[data-type="radio-updt"]').each(function() {
        $(this).removeClass('active');
    })
    $(this).addClass('active');
})


/**
 * download 2d
 */
$('[data-type="dform2"]').on('click contextmenu', function() {
    $('[data-type="overlay"]').show();
});
$('[data-type="dwnld-2d"]').on('click',function() {
    var act = false;
    $('.dwnld-3d-table2').find('[data-click="choose-one2"]').each(function(){
        if($(this).hasClass('active')) {
            act = true;
        }
    })
    if(act) {
        $('[data-type="dform2"]').fadeIn();
        $('[data-type="overlay"]').fadeIn();
        $('.dform-radio-btns-wrap').show();
        $('.dform-result').hide();
        $('[data-type="dform-close2"]').hide();
        $('[data-type="dform-cansel2"]').show();
        $('[data-type="dform-submit2"]').show();
        $('[data-type="overlay"]').attr('data-val','dwnld');
        $('[data-type="dform-submit2"]').removeAttr("disabled");
    }
    else {
        $('[data-type="dform-err2"]').fadeIn();
    }
});
$('[data-type="dform-close2"]').on('click',function(){
    $('[data-type="dform2"]').fadeOut();
    $('[data-type="overlay"]').fadeOut();
    setTimeout(function(){
        $('[data-type="overlay"]').fadeOut();
    }, 200);
});
$('body').on('click','[data-type="overlay"]',function(){
    $('[data-type="dform2"]').fadeOut();
    $('[data-type="overlay"]').fadeOut();
});

$('[data-type="radio-new2"]').on('click',function(){
    $('[data-type="radio-new2"]').each(function() {
        $(this).removeClass('active');
    })
    $(this).addClass('active');
})
$('[data-type="radio-updt2"]').on('click',function(){
    $('[data-type="radio-updt2"]').each(function() {
        $(this).removeClass('active');
    })
    $(this).addClass('active');
})

/**
 * send form
 */
$('#fio').on('click',function() {
    $(this).removeClass('error');
    $('[data-type="dform-submit"]').removeAttr("disabled");
})
$('#email').on('click',function() {
    $(this).removeClass('error');
    $('[data-type="dform-submit"]').removeAttr("disabled");
})
$('.dform_policy_label').on('click',function() {
    $(this).removeClass('error');
    $('[data-type="dform-submit"]').removeAttr("disabled");
})
$('[data-type="dform-submit"]').on('click',function() {
    
    var form = $('[data-type=dform]');

    $(this).attr("disabled", "disabled");
    var err = 0;
    if($('#fio').val()=='') {
        $('#fio').addClass('error');
        err++;
    }
    if($('#email').val()=='') {
        $('#email').addClass('error');
        err++;
    }
    if(validateEmail($('#email').val())) {} else {
        $('#email').addClass('error');
        err++;
    }
    if(!$('#dform_policy').is(':checked')) {
        $('.dform_policy_label[for=dform_policy]').addClass('error');
        err++;
    }
    if(err == 0) {
        var mes = new Object();
        mes['cat_id'] = $('.dwnld-models-filt-cont1 .active a').attr('data-id')
        $('[data-type="choose-all"]').each(function(){
            if($(this).hasClass('active')) {
                mes[$(this).attr('data-val')+'[all]'] = 'on';
            }
        })
        $('[data-click="choose-one"]').each(function(){
            if($(this).hasClass('active')) {
                mes[$(this).attr('data-val')] = 'on';
            }
        })
        mes.email = $('#email').val();
        mes.fio = $('#fio').val();
        $('[data-type="radio-updt"]').each(function(){
            if($(this).hasClass('active')) {
                mes.update3D = $(this).attr('data-val');
            }
        })
        $('[data-type="radio-new"]').each(function(){
            if($(this).hasClass('active')) {
                mes.updateItem = $(this).attr('data-val');
            }
        })
        mes['privatePolicy'] = 'Y';
        mes['region'] = $('#reg').val();
        $('.dform-radio-btns-wrap', form).hide();
        $('.dform-wait', form).fadeIn();
        $.post('build_download_m.php', mes, function (data) {
            $('.dform-wait', form).hide();
            var mess = '';
            data = JSON.parse(data);
            if(data.errors.length == 0) {
                resMess = 'Ссылка для скачивания <br>отправлена на указанный e-mail';
            }
            else {
                resMess = 'При формировании пакета произошла ошибка. <br>Повторите попытку еще раз!';
            }
            $('.dform-result-mess', form).html(resMess);
            $('.dform-result', form).fadeIn();
            $('[data-type="dform-cansel"]').hide();
            $('[data-type="dform-submit"]').hide();
            $('.dform-close', form).fadeIn();
        });
    }
})
$('[data-type="dform-submit"]').on('click',function() {
    if (typeof yaCounter22165486 !== 'undefined') {
        yaCounter22165486.reachGoal('3d_model');
    }
})
$('[data-type="dform-cansel"]').on('click',function() {
    $('[data-type="dform"]').fadeOut();
    $('[data-type="overlay"]').fadeOut();
    setTimeout(function(){
        $('[data-type="overlay"]').hide();
    }, 200);
})
$('#email').change(function() {
        var result = $(this).val();
        if (result != '') {
            setTimeout(function() {
                var url = 'getemail.php?email=' + result;
                $.getJSON(url, function (res) {
                    if (res.item)  {
                        $('[data-type="d-form-new"]').hide();
                        $('[data-type="d-form-new-y"]').css('display','flex');
                    }
                    else {
                        $('[data-type="d-form-new"]').show();
                        $('[data-type="d-form-new-y"]').hide();
                    }
                    if (res.model) {
                        $('[data-type="d-form-3d"]').hide();
                        $('[data-type="d-form-3d-y"]').css('display','flex');
                    }
                    else {
                        $('[data-type="d-form-3d"]').show();
                        $('[data-type="d-form-3d-y"]').hide();
                    }
                    $('#fio').val(res.fio);
                });
            }, 500);
        }
    }
);

$('.dwnld-instr-item').on('click','a',function () {
    if (typeof yaCounter22165486 !== 'undefined') {
        yaCounter22165486.reachGoal('instruction_download');
    }
})
$('.dwnld-models-rules').on('click', function() {
    $(this).toggleClass('active');
})
$('[data-type="open-filt"]').on('click',function() {
    $('.dwnld-models-tabs').addClass('active');
    $('.dwnld-models-left-column').addClass('active');
})
$('[data-type="close-mod-filters"]').on('click',function() {
    $('.dwnld-models-tabs').removeClass('active');
    $('.dwnld-models-left-column').removeClass('active');
})

/*табы на странице прайс-листы*/
$('[data-type="pricelist-tab"]').on('click',function(){
    $('[data-type="pricelist-tab"]').each(function(){
        $(this).removeClass('active');
    })
    $('[data-type="pricelist-tab-item"]').each(function() {
        $(this).removeClass('active');
    })
    let tabLink = $(this).attr('data-val');
    $(this).addClass('active');
    $('#'+tabLink).addClass('active');
    var val = $(this).attr("data-val");
    getPrices(val);
})
function getPrices(val){
    if($('#'+val).find('*').length == 0) {
        $('[data-type="dwnld-preloader"]').show();
        var req = '/ajax/get_pricelist.php?typelist='+val;
        $.get(req, function(data){
            var elems = $.parseJSON(data);
            $('[data-type="dwnld-preloader"]').hide();
            $('#'+val).html(elems);
        });
    }
    return false;
}

$('.dwnld-pricelist-tab-wrap').on('click','[data-type="pricelist-link"]',function(){
    if (typeof yaCounter22165486 !== 'undefined') {
        if($('[data-val="pricelist-int"]').attr('active')) {
            yaCounter22165486.reachGoal('interior_download');
        } else {
            yaCounter22165486.reachGoal('facade_download');
        }
    }
    $('.dwnld-pricelist-mess').show();
    var typelist = $(this).attr('data-typelist');
    var city = $(this).attr('data-city');
    $.get('/ajax/save_pdf_pricelist.php?typelist='+typelist+'&city='+city, function (data) {
        if(data == "") {
            $('.dwnld-pricelist-mess').hide();
            alert("Произошла ошибка. Пожалуйста, повторите попытку.");
        }
        else {
            window.location.href = data;
            $('.dwnld-pricelist-mess').hide();
        }
    });
    return false;
})


/**
 * send form2
 */
$('#fio2').on('click',function() {
    $(this).removeClass('error');
    $('[data-type="dform-submit2"]').removeAttr("disabled");
})
$('#email2').on('click',function() {
    $(this).removeClass('error');
    $('[data-type="dform-submit2"]').removeAttr("disabled");
})
$('.dform_policy_label').on('click',function() {
    $(this).removeClass('error');
    $('[data-type="dform-submit2"]').removeAttr("disabled"); 
})
$('[data-type="dform-submit2"]').on('click',function() {

    var form = $('[data-type=dform2]');

    $(this).attr("disabled", "disabled");
    var err = 0;
    if($('#fio2').val()=='') {
        $('#fio2').addClass('error');
        err++;
    }
    if($('#email2').val()=='') {
        $('#email2').addClass('error');
        err++;
    }
    if(validateEmail($('#email2').val())) {} else {
        $('#email2').addClass('error');
        err++;
    }
    if(!$('#dform_policy2').is(':checked')) {
        $('.dform_policy_label[for=dform_policy2]').addClass('error');
        err++;
    }
    if(err == 0) {
        var mes = new Object();
        mes['cat_id'] = $('.dwnld-models-filt-cont2 .active a').attr('data-id');
        $('[data-type="choose-all2"]').each(function(){
            if($(this).hasClass('active')) {
                mes[$(this).attr('data-val')+'[all]'] = 'on';
            }
        })
        $('[data-click="choose-one2"]').each(function(){
            if($(this).hasClass('active')) {
                mes[$(this).attr('data-val')] = 'on';
            }
        })
        mes.email = $('#email2').val();
        mes.fio = $('#fio2').val();
        $('[data-type="radio-updt2"]').each(function(){
            if($(this).hasClass('active')) {
                mes.update2D = $(this).attr('data-val');
            }
        })
        $('[data-type="radio-new2"]').each(function(){
            if($(this).hasClass('active')) {
                mes.updateItem = $(this).attr('data-val');
            }
        })
        mes['privatePolicy'] = 'Y';
        mes['region'] = $('#reg2').val();
        $('.dform-radio-btns-wrap', form).hide();
        $('.dform-wait', form).fadeIn();

        $.post('build_download_m2.php', mes, function (data) {
            $('.dform-wait', form).hide();
            var mess = '';
            data = JSON.parse(data);
            if(data.errors.length == 0) {
                resMess = 'Ссылка для скачивания <br>отправлена на указанный e-mail';
            }
            else {
                resMess = 'При формировании пакета произошла ошибка. <br>Повторите попытку еще раз!';
            }
            $('.dform-result-mess', form).html(resMess);
            $('.dform-result', form).fadeIn();
            $('[data-type="dform-cansel2"]').hide();
            $('[data-type="dform-submit2"]').hide();
            $('.dform-close', form).fadeIn();
        });
    }
})
$('[data-type="dform-submit2"]').on('click',function() {
    if (typeof yaCounter22165486 !== 'undefined') {
        yaCounter22165486.reachGoal('2d_model');
    }
})
$('[data-type="dform-cansel2"]').on('click',function() {
    $('[data-type="dform2"]').fadeOut();
    $('[data-type="overlay"]').fadeOut();
    setTimeout(function(){
        $('[data-type="overlay"]').hide();
    }, 200);
})
$('#email2').change(function() {
    var result = $(this).val();
    if (result != '') {
        setTimeout(function() {
            var url = 'getemail.php?email=' + result;
            $.getJSON(url, function (res) {
                if (res.item)  {
                    $('[data-type="d-form-new2"]').hide();
                    $('[data-type="d-form-new-y2"]').css('display','flex');
                }
                else {
                    $('[data-type="d-form-new2"]').show();
                    $('[data-type="d-form-new-y2"]').hide();
                }
                if (res.model) {
                    $('[data-type="d-form-2d"]').hide();
                    $('[data-type="d-form-2d-y"]').css('display','flex');
                }
                else {
                    $('[data-type="d-form-2d"]').show();
                    $('[data-type="d-form-2d-y"]').hide();
                }
                $('#fio2').val(res.fio);
            });
        }, 500);
    }
});