/**
 * Created by nadida on 27.03.2020.
 */
var mountingData = '';
$.getJSON('mounting_data.php',{type:'getData'}, function (data) {
    mountingData = data;
})

$(window).on('resize', function() {
    if(window.innerWidth > 850) {
        $('.mount-total-wrap').sticky({
            topSpacing: $('header').outerHeight(),
            bottomSpacing: $('footer').outerHeight() + 50
        });
        $('.mount-total-wrap').removeClass('fixed');
        $('.mount-total-wrap').removeClass('open');
        $('.mount-total-wrap').removeClass('closed');
    } else {
        $('.mount-total-wrap').unstick();
        $('.mount-total-wrap').addClass('fixed');
        $('.mount-total-wrap').removeClass('open');
        $('.mount-total-wrap').addClass('closed');
    }
    defineTotalSumPosition();
    if($('.mount-total-wrap').innerHeight() + $('header').innerHeight() > document.documentElement.clientHeight) {
        $('.mount-total-wrap').addClass('scrollable');
    } else {
        $('.mount-total-wrap').removeClass('scrollable');
    }
});
$(document).ready(function() {
    if(window.innerWidth > 850) {
        $('.mount-total-wrap').sticky({
            topSpacing: $('header').outerHeight(),
            bottomSpacing: $('footer').outerHeight() + 50
        });
        $('.mount-total-wrap').removeClass('fixed');
        $('.mount-total-wrap').removeClass('open');
        $('.mount-total-wrap').removeClass('closed');
    } else {
        $('.mount-total-wrap').unstick();
        $('.mount-total-wrap').addClass('fixed');
        $('.mount-total-wrap').removeClass('open');
        $('.mount-total-wrap').addClass('closed');
    }
    defineTotalSumPosition();
    /*console.log(document.documentElement.clientHeight);
    if($('.mount-total-wrap').innerHeight() + $('header').innerHeight() > document.documentElement.clientHeight) {
        $('.mount-total-wrap').addClass('scrollable');
    } else {
        $('.mount-total-wrap').removeClass('scrollable');
    }*/
    mountTotal();
    if($('[data-type="mount-list"]').find('[data-type="mount-item"]').length == 0) {
        $('.mount-line').hide();
    }
});
$('.dwnld-models-rules').on('click', function() {
    $(this).toggleClass('active');
})
/**
 * количество товара
 * плюс
 */
$('[data-type="mount-list"]').on('click','[data-type="mount-item-plus"]',function() {
    let item = $(this).closest('[data-type="mount-item"]'),
        qty = parseInt(item.attr('data-qty')) + 1;
    changeQty(item,qty);
    $(this).closest('.cart-item-info-qty').removeClass('error');
})

/**
 * минус
 */
$('[data-type="mount-list"]').on('click','[data-type="mount-item-minus"]',function() {
    let item = $(this).closest('[data-type="mount-item"]'),
        qty = parseInt(item.attr('data-qty')) - 1;
    changeQty(item,qty);
    $(this).closest('.cart-item-info-qty').removeClass('error');
})

/**
 * ввод в input
 */
$('[data-type="mount-list"]').on('change','[data-type="mount-item-qty"]',function() {
    let item = $(this).closest('[data-type="mount-item"]'),
        qty = parseInt($(this).val());
    if(qty == '' || qty < 0 || isNaN(qty)) qty = 0;
    changeQty(item,qty);
    $(this).closest('.cart-item-info-qty').removeClass('error');
})

function changeQty(item,qty) {
    if(qty < 0) qty = 0;
    item.attr('data-qty',qty);
    item.find('[data-type="mount-item-qty"]').val(qty);
    item.find('.mount-item-cost-number-full span').html(qty);
    if(qty == 0) resetParams(item.attr('data-id'),true);
    item.find('[data-type="mount-err-mess"]').hide();
    item.find(' [data-type="count"]').removeAttr("disabled");
    if(item.attr('data-measure') == "i" && qty > 0) item.attr('data-completed','y');
    countItem(item);
    mountTotal();
}

/**
 * выбираем товар
 */
/*$('[data-type="mount-list"]').on('mouseup','[data-type="mount-item"]',function(e) {
    var deleteBtn = $(this).find('[data-type="remove-mount-item"]');
    if (!deleteBtn.is(e.target) && deleteBtn.has(e.target).length === 0) {
        var wrap = $('[data-type="mount-list"]');
        wrap.find('[data-type="mount-item"]').each(function () {
            $(this).removeClass('active');
        })
        $(this).addClass('active');

        let top = $(this).offset().top - $('header').innerHeight();
        $('body,html').animate({scrollTop: top}, 1000);

    }
})*/
$('[data-type="mount-list"]').on('mouseup','[data-type="open-param"]',function(e) {
    let id = $(this).closest('.mount-item').attr('data-id');
    $('[data-type="mount-list"]').find('[data-type="mount-item"]').each(function () {
        if($(this).attr('data-id') == id) {
            if(!$(this).hasClass('active')) {
                let top = $(this).offset().top - $('header').innerHeight();
                $('body,html').animate({scrollTop: top}, 1000);
            }
            $(this).toggleClass('active');
        } else {
            $(this).removeClass('active');
        }
    })
})
/**
 * удалить товар
 */
/*$('[data-type="mount-list"]').on('click','[data-type="remove-mount-item"]',function() {
    var itemId = $(this).closest('[data-type="mount-item"]').attr('data-id');
    if($(this).closest('[data-type="mount-item"]').hasClass('active')) {
        var nextItemId = $(this).closest('[data-type="mount-item"]').next().attr('data-id');
        if(nextItemId === undefined) {
            nextItemId = $(this).closest('[data-type="mount-item"]').prev().attr('data-id');
        }
        if(nextItemId !== undefined) {
            $('[data-id="'+nextItemId+'"]').addClass('active');
            $('[data-val-id="'+nextItemId+'"]').addClass('active');
        }
    }
    $(this).closest('[data-type="mount-item"]').remove();
    $('[data-val-id="'+itemId+'"]').remove();
    $('[data-total-id="'+itemId+'"]').remove();
    if($('[data-type="mount-list"]').find('[data-type="mount-item"]').length == 0) {
        let mess = '<div class="mount-list-empty"><span class="mount-list-empty-note">!</span>В вашей корзине не&nbsp;найдено товаров, монтаж которых может быть рассчитан с&nbsp;помощью данного&nbsp;сервиса.</div>';
        $('[data-type="mount-list"]').html(mess);
        $('.main-total-btn-wrap').addClass('not-active');
    }
    mountTotal();
})*/
/**
 * сбросить параметры
 */
$('[data-type="mount-list"]').on('click','[data-type="remove-mount-item"]',function() {
    let itemId = $(this).closest('[data-type="mount-item"]').attr('data-id');
    resetParams(itemId,true);
    mountTotal();
})
/**
 * выбираем опцию radio btn
 */
$('[data-type="mount-list"]').on('click','[data-type="option"]',function(e) {
    let item = $(this).closest('.mount-item');
    let plintusEndInfo = $(this).find('.plintus-end-more');
    if (!plintusEndInfo.is(e.target) && plintusEndInfo.has(e.target).length === 0) {
        let wrap = $(this).closest('[data-type="step"]');
        wrap.find('[data-type="option"]').each(function () {
            $(this).removeClass('active');
        })
        $(this).addClass('active');
        wrap.removeClass('error');
        $(this).closest('[data-type="mount-item"]').find('[data-type="mount-err-mess"]').fadeOut();
        $(this).closest('[data-type="mount-item"]').find('[data-type="count"]').removeAttr('disabled');

        //молдинги, разные типы - разные шаги

        if(wrap.attr('data-val') == '2' && $(this).attr('data-val') == "1" || wrap.attr('data-val') == '2' && $(this).attr('data-val') == "2" || wrap.attr('data-val') == '2' && $(this).attr('data-val') == "3"){
            $(this).closest('[data-type="mount-item"]').find('[data-val="6"]').remove();
            $(this).closest('[data-type="mount-item"]').find('[data-val="7"]').remove();
        }

        //рамки
        if (wrap.attr('data-val') == '2' && $(this).attr('data-val') == "1") {
            var el = '';
            /*el += '<div class="mount-item-calc-step" data-type="step" data-opt="input">';
            el += '<div class="mount-item-calc-step-headline">Шаг 2. Количество рамок</div>';
            el += '<div class="mount-item-calc-step-wrap input">';
            el += '<label for="frame-id">Введите количество рамок</label>';
            el += '<input type="text" id="frame-id" data-type="inp">';
            el += '<span class="inp-unit">, шт</span>';
            el += '</div>';
            el += '</div>';*/

            //$(this).closest('[data-type="mount-item"]').find('[data-type="add-steps"]').html(el);
            mountTotal();
        }

        //вдоль стены
        if (wrap.attr('data-val') == '2' && $(this).attr('data-val') == "2") {
            var el = '';
            el += '<div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="6">';
            el += '<div class="mount-item-calc-step-headline">Шаг 2. Тип помещения (количество углов)</div>';
            el += '<div class="mount-item-calc-step-wrap corners">';
            mountingData.corners.forEach(function (item) {
                let payForCorner = 0;
                if (parseInt(item) > parseInt(mountingData['corner-standart'])) payForCorner = parseInt(item) - parseInt(mountingData['corner-standart']);

                el += '<div class="mount-item-calc-step-param" data-type="option" data-val="' + item + '" data-factor="' + mountingData.corner + '" data-qty="' + payForCorner + '">';
                el += '<i class="m-icon-' + item + '-corners"></i>';
                el += '<div class="mount-item-calc-step-param-btn">' + item + '</div>';
                el += '</div>';
            })
            el += '</div>';
            el += '</div>';
            el += '</div>';
            $(this).closest('[data-type="mount-item"]').find('.mount-item-bottom-btns').before(el);
            mountTotal();

        }

        //по периметру потолка
        if (wrap.attr('data-val') == '2' && $(this).attr('data-val') == "3") {
            var el = '';
            el += '<div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="6">';
            el += '<div class="mount-item-calc-step-headline">Шаг 2. Тип помещения (количество углов)</div>';
            el += '<div class="mount-item-calc-step-wrap corners">';
            mountingData.corners.forEach(function (item) {
                let payForCorner = 0;
                if (parseInt(item) > parseInt(mountingData['corner-standart'])) payForCorner = parseInt(item) - parseInt(mountingData['corner-standart']);

                el += '<div class="mount-item-calc-step-param" data-type="option" data-val="' + item + '" data-factor="' + mountingData.corner + '" data-qty="' + payForCorner + '">';
                el += '<i class="m-icon-' + item + '-corners"></i>';
                el += '<div class="mount-item-calc-step-param-btn">' + item + '</div>';
                el += '</div>';
            })
            el += '</div>';
            el += '</div>';

            el += '<div class="mount-item-calc-step" data-type="step" data-opt="radio" data-val="7">';
            el += '<div class="mount-item-calc-step-headline">Шаг 3. Высота потолков, мм</div>';
            el += '<div class="mount-item-calc-step-wrap height">';
            el += '<div class="mount-item-calc-step-param" data-type="option" data-val="1" data-factor="' + mountingData['height-2500'] + '">';
            el += '<div class="mount-item-calc-step-param-btn">2 500 - 3 500</div>';
            el += '</div>';
            el += '<div class="mount-item-calc-step-param" data-type="option" data-val="2" data-factor="' + mountingData['height-3500'] + '">';
            el += '<div class="mount-item-calc-step-param-btn">3 500 - 4 500</div>';
            el += '</div>';
            el += '</div>';
            el += '</div>';


            $(this).closest('[data-type="mount-item"]').find('.mount-item-bottom-btns').before(el);
            mountTotal();

        }

    }
    countItem(item);
})

/**
 * фокус на input
 */
$('[data-type="mount-list"]').on('click','[data-type="inp"]',function() {
    var wrap = $(this).closest('[data-type="step"]');
    wrap.removeClass('error');
    $(this).closest('[data-type="mount-item"]').find('[data-type="mount-err-mess"]').fadeOut();
    $(this).closest('[data-type="mount-item"]').find('[data-type="count"]').removeAttr('disabled');

})
$('[data-type="mount-list"]').on('change','[data-for="pend"]',function() {
    let item = $(this).closest('.mount-item');
    countItem(item);
})
/**
 * input количество окончаний плинтуса
 */
$('[data-type="mount-list"]').on('change','[data-for="pend"]',function() {
    let wrap = $(this).closest('[data-type="step"]'),
        val = $(this).val();
    if(val == '') val = 0;
    val = parseInt(val);
    if(val <= 0) {
        val = 0;
        $(this).val(val);
    }
    wrap.find('[data-type="option"]').each(function() {
        $(this).attr('data-qty',val);
    })
    mountTotal();
})
/**
 * рассчитать
 */
$('[data-type="mount-list"]').on('click','[data-type="count"]',function() {
    let wrap = $(this).closest('[data-type="mount-item"]'),
        btn = $(this),
        item = wrap,
        err = checkErr(wrap,true);
    btn.attr('disabled','disabled');

    if(err == 0 && item.attr('data-qty') == 0) {
        let mess = "Количество товара должно&nbsp;быть больше&nbsp;0";
        wrap.find('[data-type="mount-err-mess"]').html(mess).fadeIn();
        wrap.find('.cart-item-info-qty').addClass('error');
    } else if(err == 0) {
        btn.removeAttr('disabled');
        item.attr('data-completed','y');
        mountTotal();
        let nextElem = item.next();
        item.removeClass('active');
        nextElem.addClass('active');
    }
})
/**
 * сохранить расчет
 */
$('[data-type="total-save"]').on('click',function() {
    /* проверка на незаполненность элемента
    let isEmpty = 0,
        elems = '';
    $('[data-type="mount-list"]').find('[data-type="mount-item"]').each(function() {
        if($(this).attr('data-completed') != 'y') {
            isEmpty++;
            elems += $(this).find('.mount-item-name').html() + ' - ' + $(this).attr('data-qty') + ' шт.<br>';
        }
    })
    if(isEmpty > 0) {
        let mess = "<p>Не завершен рассчет элементов:</p><p class='not-calc-elems'>"+elems+"</p><p>Укажите необходимые параметры для&nbsp;рассчета по&nbsp;каждому элементу или&nbsp;удалите ненужные элементы.</p>";
        $('[data-type="maur-pop-content"]').html(mess);
        $('[data-type="maur-pop"]').fadeIn(clouseMaurPop());
        $('[data-type="overlay"]').fadeIn();
    } else {*/
        let mountList = getMountParams(true,true);
        if(mountList === false) {
            $.cookie('mount', null, {
                expires: -1,
                domain: domain,
                path: '/'
            });
        } else {
            $.cookie('mount', JSON.stringify(mountList), {expires: basketExpires, domain: domain, path: '/'});
        }
        window.location.href = '/cart/';
    //}
})
/**
 * дублирвать товар
 */
$('[data-type="mount-list"]').on('click','[data-type="duplicate"]',function() {
    let firstItem = $(this).closest('[data-type="mount-item"]'),
        firstItemTotal = $('.mount-total-wrap').find('[data-total-id="'+firstItem.attr('data-id')+'"]'),
        secondItem = firstItem.clone(),
        secondItemTotal = firstItemTotal.clone(),
        prodId = firstItem.attr('data-prod-id');
    for(let i = 1, isNew = false; isNew == false; i++) {
        var newId = prodId+'-'+i;
        if($('[data-id='+newId+']').length == 0) isNew = true;
    }
    secondItem.attr({
        'data-id': newId
    });

    firstItem.after(secondItem).removeClass('active');
    if(secondItem.find('[data-type="step"][data-val="2"]').length > 0) {
        secondItem.find('[data-type="step"][data-val="6"]').remove();
        secondItem.find('[data-type="step"][data-val="7"]').remove();
    }
    secondItemTotal.attr('data-total-id',newId);
    firstItemTotal.after(secondItemTotal);
    resetParams(newId,false);
    mountTotal();
})
/**
 * сохранить в pdf
 */
$('[data-type="save-pdf"]').on('click',function() {
    let mountList = getMountParams(true);
    $.get('/cart/mounting/save_pdf.php?list='+JSON.stringify(mountList), function (data) {
        window.location.href = data;
        //window.open(data, '_blank');
        /*let link = document.createElement('a');
        link.setAttribute('href',data);
        link.setAttribute('download','download');
        onload=link.click();*/
    });
    return false;
})

/**
 * открыть инструкции
 */
$('[data-type="open-notes"]').on('click',function() {
    let wrap = $(this).closest('.e-mount-notes-wrap');
    if(wrap.hasClass('active')) {
        wrap.removeClass('active');
        wrap.find('.e-mount-notes').slideUp();
    } else {
        wrap.addClass('active');
        wrap.find('.e-mount-notes').slideDown();
    }
})
/**
* общий расчет проекта
*/
function mountTotal() {
    let itemWrap = $('[data-type="mount-list"]'),
        total = 0,
        totalAdd = 0;

    itemWrap.find('[data-type="mount-item"]').each(function(){
        let id = $(this).attr('data-id'),
            qty = $(this).attr('data-qty'),
            completed = $(this).attr('data-completed'),
            itemTotal = 0;
        if(completed == 'y') {
            itemTotal = mountItem(id);
            $('[data-total-id="'+id+'"]').addClass('completed');
        } else {
            $('[data-total-id="'+id+'"]').removeClass('completed');
        }
        $('[data-total-id="'+id+'"]').find('[data-type="total-qty"]').html(qty);
        $('[data-total-id="'+id+'"]').find('[data-type="total-cost"]').html(costFormat(itemTotal));
        $('[data-total-id="'+id+'"]').find('[data-type="total-cost"]').attr('data-val',itemTotal);

        total += itemTotal;
    })

    $('[data-type="total"]').find('[data-type="total-cost"]').html(costFormat(total));
    $('[data-type="total"]').find('[data-type="total-cost"]').attr('data-val',total);
}

/**
 * расчет одного элемента
 */
function mountItem(id) {
    let item = $('[data-type="mount-list"]').find('[data-id="'+id+'"]'),
        qty = parseInt(item.attr('data-qty')),
        completed = item.attr('data-completed'),
        measure = item.attr('data-measure'),
        price = parseInt(item.attr('data-price')),
        total = 0,
        err = checkErr(item,false);
    if(err > 0 || qty == 0) {
        item.attr('data-completed','n');
    } else {
        if(completed == 'y') {
            if(measure == 'm') {
                let length = parseInt(item.attr('data-length')),
                    baseLength = length/1000;
                total += baseLength * qty * price;
                item.find('[data-type="step"]').each(function() {
                    $(this).find('[data-type="option"]').each(function(o,option) {
                        if($(option).hasClass('active')) {
                            let factor = parseInt($(option).attr('data-factor'));
                            if($(option).attr('data-qty') !== undefined) {
                                let factorQty = parseInt($(option).attr('data-qty'));
                                total += factorQty * factor;
                            } else {
                                total += baseLength * qty * factor;
                            }
                        }
                    })
                })
            } else {
                total += qty * price;
            }
        }
    }
    total *= 1.07;
    item.find('.mount-item-cost-number').html(costFormat(total));
    return total;
}

function isEmptyObj(obj) {
    for (let key in obj) {
        return false;
    }
    return true;
}

/**
 * собрать указанные пользователем параметры по каждому элементу
 * notCompleted (true/false) : учитывать / не учитывать нерассчитанные элементы
 */
function getMountParams(notCompleted,checkTotal = false) {
    let mountList = [];
    let totalQty = 0;
    $('[data-type="mount-list"]').find('[data-type="mount-item"]').each(function() {
        let item = {};
        if($(this).attr('data-completed') == 'y' || notCompleted && !checkDoubles($(this).attr('data-prod-id'))) {
            let item = {
                'id': $(this).attr('data-prod-id'),
                'qty': $(this).attr('data-qty'),
                'bqty': $(this).attr('data-bqty'),
                };
            totalQty += $(this).attr('data-qty');
            $(this).find('[data-type="step"]').each(function(s,step) {
                let key = $(step).attr('data-val');
                let val = '';
                $(step).find('[data-type="option"]').each(function(o,option) {
                    if($(option).hasClass('active')) {
                       val = $(option).attr('data-val');
                       item[key] = val;
                       if(key == '4') item['5'] = $(option).attr('data-qty');
                    }
                })
            })
            if(!isEmptyObj(item)) mountList.push(item);
        }
    })
    if(checkTotal && totalQty == 0) return false;
    return mountList;
}

/**
 * проверка незаполненных полей по элементу
 * @param wrap
 * @param showErr
 * @returns {number}
 */
function checkErr(wrap,showErr=false) {
    let err = 0;
    wrap.find('[data-type="step"]').each(function(s,step) {
        let type = $(this).attr('data-val'),
            val = '';
        //если radio btn
        if($(this).attr('data-opt') == 'radio') {
            $(this).find('[data-type="option"]').each(function(o,option) {
                if($(option).hasClass('active')) {
                    val = $(option).attr('data-val');
                }
            })
        }
        //если input
        if($(this).attr('data-opt') == 'input') {
            $(this).find('[data-type="input"]').each(function(o,option) {
                if($(option).val!= '') {
                    val = $(option).val();
                }
            })
        }
        // окончания плинтуса
        if($(this).find('[data-type="inp"]').length > 0) {
            if($(this).find('[data-type="inp"]').val() > 0) {
                val = $(this).find('[data-type="inp"]').val();
            } else {
                val = '';
            }
        }
        if(val == '') {
            err++;
            if(showErr) {
                $(step).addClass('error');
                let mess = 'Выберите все опции и заполните все поля';
                wrap.find('[data-type="mount-err-mess"]').html(mess).fadeIn();
            }
        }
    })
    return err;
}

/**
 * клик по сумме справа
 */
$('[data-type="calc-column"]').on('click','.main-total-item', function () {
    let id = $(this).attr('data-total-id');
    $('[data-type="mount-item"]').each(function() {
        if($(this).attr('data-id') != id) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
            let top = $(this).offset().top - $('header').innerHeight();
            $('body,html').animate({scrollTop: top}, 1000);
        }
    })
})

/**
 * сбросить параметры элемента
 */
function resetParams(id,remove=false) {
    let wrap = $('[data-type="mount-list"]').find('[data-id="'+id+'"]'),
        wrapTotal =  $('[data-type="calc-column"]').find('[data-total-id="'+id+'"]');
    if(remove && checkDoubles(wrap.attr('data-prod-id'))) {
        if(wrap.hasClass('active')) {
            let nextItemId = wrap.next().attr('data-id');
            if(nextItemId === undefined) {
                nextItemId = wrap.prev().attr('data-id');
            }
            if(nextItemId !== undefined) {
                $('[data-id="'+nextItemId+'"]').addClass('active');
            }
        }
        wrap.remove();
        wrapTotal.remove();
    } else {
        wrap.attr({
            'data-qty':'0',
            'data-completed':'n',
        });
        wrap.find('[data-type="mount-item-qty"]').val(0);
        wrap.find('.mount-item-cost-number-full span').html(0);
        mountItem(id);
        wrap.find('[data-type="option"]').each(function() {
            $(this).removeClass('active');
            if($(this).closest('[data-type="step"]').attr('data-val') == '4' && $(this).attr('data-qty') > 0) $(this).attr('data-qty',0);
        })
        if(wrap.find('[data-for="pend"]').val() > 0) wrap.find('[data-for="pend"]').val(0);
        wrap.find('[data-type="step"]').each(function() {
            $(this).removeClass('error');
        })
        wrap.find('[data-type="mount-err-mess"]').hide();
        wrap.find('[data-type="count"]').removeAttr('disabled');
    }
}
function checkDoubles(id) {
    if($('[data-type="mount-list"]').find('[data-prod-id="'+id+'"]').length > 1) {
        return true;
    } else {
        return false;
    }
}

/**
 * общий расчет в мобильной версии
 */
$('[data-type="calc-column"]').on('click','.fixed', function () {
    if(window.innerWidth <= 850) {
        if($(this).hasClass('closed')) {
            $(this).removeClass('closed');
            $(this).addClass('open');
        } else {
            $(this).removeClass('open');
            $(this).addClass('closed');
        }
    }
})
$(document).scroll(function () {
    defineTotalSumPosition();
})
function defineTotalSumPosition() {
    if(window.innerWidth <= 850) {
        let sTop = $("html").scrollTop();
        let mountListTop = $('[data-type="mount-list"]').offset().top + $('[data-type="mount-list"]').innerHeight() - $(window).height();
        if(sTop >= mountListTop && $('.mount-total-wrap').hasClass('fixed')){
            $('.mount-total-wrap').removeClass('fixed');
            $('.mount-total-wrap').removeClass('open');
            $('.mount-total-wrap').removeClass('closed');
        } else if(sTop < mountListTop && !$('.mount-total-wrap').hasClass('fixed')) {
            $('.mount-total-wrap').addClass('fixed');
            $('.mount-total-wrap').removeClass('open');
            $('.mount-total-wrap').addClass('closed');
        }
    }
}

/**
 * если заполнены все параметры - расчитать
 */
function countItem(item) {
    let err = checkErr(item,false);
    if(err == 0 && item.attr('data-qty') == 0) {
        let mess = "Количество товара должно&nbsp;быть больше&nbsp;0";
        item.find('[data-type="mount-err-mess"]').html(mess).fadeIn();
        item.find('.cart-item-info-qty').addClass('error');
    } else if(err == 0) {
        item.attr('data-completed','y');
        mountTotal();
    }
    return false;
}