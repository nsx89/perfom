$(document).ready(function() {
    if(window.innerWidth > 800) {
    $('[data-type="cart-order"]').sticky({
        topSpacing: $('header').outerHeight(),
        bottomSpacing: $('footer').outerHeight() + 50
    });
    } else {
        $('[data-type="cart-order"]').unstick();
    }
    defineTotalSumPosition();
    //скрыть раздел с клеем, если его нет
    if($('.cart-items-adh').find('[data-type="adh"]').length === 0) {
        $('.cart-items-adh-title').hide();
    }
})
$(window).on('resize', function() {
    defineTotalSumPosition();
    if(window.innerWidth > 800) {
        $('[data-type="cart-order"]').sticky({
            topSpacing: $('header').outerHeight(),
            bottomSpacing: $('footer').outerHeight() + 50
        });
    } else {
        $('[data-type="cart-order"]').unstick();
    }
})

function cartCount(){
    let list = basketList(),
        qty = 0,
        i = 0;
    while (i < list.length) {
        qty += list[i].qty;
        i++;
    }
    $('[data-type="cart-qty"]').html(qty);
    return false;
}
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}
/**
 * стоимость доставки
 */
function setDeliveryPrice(totalDiscountPrice) {
    let deliveryPrice = 0,
        onlySamples = checkOnlySamples();
    if(totalDiscountPrice > 0) {
        $('[data-type="delivery"]').each(function() {
            if($(this).attr('data-val') == 'del' && $(this).hasClass('active')) {
                let km = Math.ceil($('[data-type="del-km"]').val());
                if(onlySamples) {
                    deliveryPrice += 500;
                    deliveryPrice += km * 50;
                    if(km > 20) {
                        $('[data-type="del-km"]').addClass('error');
                        $('[data-type="del-km-err"]').html('не более 20 км').show();
                    }
                } else {
                    if(totalDiscountPrice < 10000) {
                        deliveryPrice += 1000;
                        if(km > 0) {
                            deliveryPrice += km * 50;
                        }
                    } else {
                        if(km > 0) {
                            deliveryPrice += km * 30;
                        }
                    }
                }
            }
        })
    }
    $('[data-type="delivery-sum"]').attr('data-val',deliveryPrice);
    $('[data-type="delivery-sum"]').html(costFormat(deliveryPrice));
    return deliveryPrice;
}

/**
 * стоимость заказа
 */
function cartTotal() {
    $.getJSON('/ajax/show-cart.php', function (res) {
        //console.log(res);
        $('[data-type="sum"]').html(res.all_price);
        $('[data-type="discount"]').html(res.discount + '%');
        $('[data-type="discount-sum"]').html(res.discount_price);
        let total = res.total,
            noDel = total.replace(/\s/g,'');
        noDel = parseFloat(noDel);
        var deliveryPrice = 0;
        if($('[data-type="del-km"]').length != 0) {
            deliveryPrice = setDeliveryPrice(noDel);
        }
        total = noDel + deliveryPrice;
        total = costFormat(total);
        $('[data-type="total"]').html(total);
        $('[data-type="total"]').attr('data-without-del',noDel);
        if (res.discount == 0 || res.discount == null) {
            $('[data-type="cart-order"]').addClass('no-sale');
            $('[data-type="cart-order-del"]').css('display','flex');
            $('[data-type="cart-order-del"]').find('.cart-order-name').show();
        }
        else {
            $('[data-type="cart-order"]').removeClass('no-sale');
        }
    });
}

/**
 * пересчет стоимости отдельного товара
 */
function prodCost() {
    $('.cart-item').each(function(){
        let cost = parseFloat($(this).attr('data-cost')),
            curr = $(this).attr('data-curr'),
            qty = parseInt($(this).find('[data-type="prod-page-qty"]').val()),
            total = cost * qty;
        total = (total.toFixed(2)).replace(/(\d)(?=(\d\d\d)+([^\d]|$))/g, '$1 ');
        $(this).find('[data-type="prod-total"]').html(total + " " + curr);
    })
}
function addProdQty(id,qty){
    let list = basketList(),
        i = 0,
        exist = false;
    if(list.length > 0) {
        while (i < list.length) {
            if (list[i].id == id) {
                list[i].qty = qty;
                exist = true;
                if(qty <= 0) {
                    list.splice(i,1);
                }
                break;
            }
            i++;
        }
        if (exist === false) {
            if(qty > 0) {
                list.push({
                    'id': id,
                    'qty': qty
                });
            }
        }
    }
    else {
        if(qty > 0) {
            list = [{
                'id': id,
                'qty': qty
            }];
        }
    }
    $.cookie('basket', JSON.stringify(list), {
        expires: basketExpires,
        domain: domain,
        path: '/'
    });
    //console.log(JSON.parse($.cookie('basket')));
    addCartHeader();
    return false;
}

/**
 * изменить количество товара
 */
$('[data-type="prod-page-plus"]').on('click',function() {
    var wrap = $(this).closest('.cart-item');
    var nmbr = wrap.find('[data-type="prod-page-qty"]');
    var productId = wrap.attr('data-id');
    var resp = checkMauritaniaSpecial(productId,1);
    if(resp) {
        var qty = parseInt(nmbr.val())+1;
        nmbr.val(qty);
        wrap.attr('data-qty',qty);
        addProdCart(productId,1,0);
        cartCount();
        cartTotal();
        prodCost();
        sendAddAnalytics(wrap,'add',1);
        checkMountingList();
    }

})

$('[data-type="prod-page-minus"]').on('click',function() {
    var wrap = $(this).closest('.cart-item');
    var nmbr = wrap.find('[data-type="prod-page-qty"]');
    var qty = parseInt(nmbr.val())-1;
    var productId = wrap.attr('data-id');
    var qtyTrue = 0;
    if(qty > 0) {
        qtyTrue = qty;
    }
    else {
        if(wrap.attr('data-type') == 'adh' && qty <= 0) {
            qtyTrue = 0;
        }
        else {
            qtyTrue = 1;
            return false;
        }
    }
    if(qtyTrue < nmbr.val()) sendAddAnalytics(wrap,'remove', nmbr.val() - qtyTrue);
    var qtyDiff = qtyTrue-nmbr.val();
    var resp = checkMauritaniaSpecial(productId,qtyDiff);
    if(resp) {
        nmbr.val(qtyTrue);
        wrap.attr('data-qty',qtyTrue);
        addProdCart(productId,-1,0);
        cartCount();
        cartTotal();
        prodCost();
        checkMountingList();
    }
})

$('[data-type="prod-page-qty"]').on('change',function(){
    var wrap = $(this).closest('.cart-item');
    var nmbr = wrap.find('[data-type="prod-page-qty"]');
    var qty = parseInt(nmbr.val());
    var qtyTrue = 0;
    if(qty > 0) {
        qtyTrue = qty;
    }
    else {
        if(wrap.attr('data-type') == 'adh' && qty <= 0) {
            qtyTrue = 0;
        }
        else {
            qtyTrue = 1;
        }
    }
    var qtyDiff = qtyTrue - parseInt(wrap.attr('data-qty'));
    var productId = wrap.attr('data-id');
    var resp = checkMauritaniaSpecial(productId,qtyDiff);
    if(resp) {
        if(qtyDiff > 0) {
            sendAddAnalytics(wrap,'add',qtyDiff);
        } else if(qtyDiff < 0) {
            sendAddAnalytics(wrap,'remove',-qtyDiff);
        }
        nmbr.val(qtyTrue);
        wrap.attr('data-qty',qtyTrue);
        addProdCart(productId,qtyDiff,0);
        cartCount();
        cartTotal();
        prodCost();
        checkMountingList();
    } else {
        nmbr.val(wrap.attr('data-qty'));
    }
})


/**
 * удалить товар из корзины
 */
$('[data-type="remove-item"]').on('click',function() {
    var wrap = $(this).closest('.cart-item');
    var nmbr = wrap.find('[data-type="prod-page-qty"]').val();
    wrap.find('[data-type="prod-page-qty"]').val(0);
    var productId = wrap.attr('data-id');
    var resp = checkMauritaniaSpecial(productId,-1*nmbr);
    if(resp) {
        if(nmbr > 0) sendAddAnalytics(wrap,'remove',nmbr);
        addProdQty(productId,0);
        cartCount();
        cartTotal();
        prodCost();
        if(wrap.attr('data-type') == 'adh') {
            wrap.hide();
        }
        else {
            wrap.remove();
        }
        removeItemFromMountCalc(productId);
        //checkMountingList();
        defineTotalSumPosition();
    }
})


/**
 * рассчитать количество клея
 */
$('[data-type="adh-qty"]').on('click',function() {
    $(this).hide();
    $('[data-type="adh-qty-wait"]').css('display','flex');
    $.post('/ajax/glue-calculate.php', {task: 'glue_calc'}, function (data) {
        $('[data-type="adh-qty-wait"]').hide();
        $('[data-type="adh-qty"]').css('display','flex');
        var list = JSON.parse(data);
        $('[data-type="adh"]').each(function(){
            $(this).css('display','flex');
            let adhId = $(this).attr('data-id');
            let isInList = false;
            let adhObj = {};
            list.map(function (prod) {
                if (prod.id == adhId) {
                    isInList = true;
                    adhObj = prod;
                }
            })
            let wrap = $('[data-id="'+adhId+'"]');
            let inputQty = wrap.find('[data-type="prod-page-qty"]');
            if(isInList) {
                if(inputQty.val() < adhObj.qty) {
                    let new_qty = adhObj.qty - inputQty.val();
                    sendAddAnalytics(wrap,'add',new_qty);
                }
                inputQty.val(adhObj.qty);
                wrap.attr('data-qty',adhObj.qty);
            } else {
                if(inputQty.val() > 0) sendAddAnalytics(wrap,'remove',inputQty.val());
                inputQty.val(0);
                wrap.attr('data-qty',0);
            }
        })
        let basket = [];
        for(var prod in list) {
            if($('.cart-items').find('[data-id="'+list[prod].id+'"]').length > 0) {
                basket.push(list[prod]);
            }
        }
        $.cookie('basket', JSON.stringify(basket), {expires: basketExpires, domain: domain, path: '/'});
        cartCount();
        addCartHeader();
        cartTotal();
        prodCost();
        defineTotalSumPosition();
    });
})
/**
 * запрос на монтаж
 */
$('[data-type="mounting"]').on('click',function(){
    $(this).toggleClass('active');
})

/**
 * доставка/самовывоз
 */
$('[data-type="delivery"]').on('click',function() {
    if($(this).attr('data-click') == 'no') {

    } else {
        $('[data-type="delivery-wrap"]').removeClass('error');
        $('[data-type="delivery"]').each(function(){
            $(this).removeClass('active');
        })
        $('[ data-class="del-desc"]').each(function() {
            $(this).removeClass('active');
        })
        $('[data-type="'+$(this).attr('data-val')+'"]').addClass('active');
        $(this).addClass('active');
        cartTotal();
    }
    if($('.cart-forms').find('#sticky-wrapper').length > 0) {
        $('[data-type="cart-order"]').sticky('update');
    }
})
/**
 * при получении/онлайн
 */
$('[data-type="payment"]').on('click',function() {
    $('[data-type="payment-wrap"]').removeClass('error');
    $('[data-type="payment"]').each(function(){
        $(this).removeClass('active');
        if($(this).attr('data-val') == 'cash') {
            $('.cart-receiving-btns').hide();
        }
    })
    $(this).addClass('active');
    /*if($(this).attr('data-val') == 'cash') {
      $('.cart-receiving-btns').show();
      $('.cart-receiving-btns').find('[data-type="receiving"]').each(function() {
        $(this).removeClass('active');
      })
      $('.cart-receiving-btns').find('[data-val="receiving-cash"]').addClass('active');
    }*/
})
/**
 * наличными/картой
 */
$('[data-type="receiving"]').on('click',function() {
    $('[data-type="payment-wrap"]').removeClass('error');
    $('[data-type="receiving"]').each(function(){
        $(this).removeClass('active');
    })
    $(this).addClass('active');
})
/**
 * выбор пункта самовывоза
 */
$('[data-type="pickup-point"]').on('click',function() {
    $('[data-type="pickup-point-wrap"]').removeClass('error');
    $('[data-type="pickup-point"]').each(function(){
        $(this).removeClass('active');
    })
    $(this).addClass('active');
})
/**
 * сохранить pdf
 */
$('[data-type="save-pdf"]').on('click',function() {
    let loc = $('[data-type="header-cart"]').attr('data-reg');
    $.get('/ajax/save_pdf.php?loc='+loc, function (data) {
        window.location.href = data;
    });
    if (typeof yaCounter22165486 !== 'undefined') {
        yaCounter22165486.reachGoal('pdf');
    }
    return false;
});
/**
 * оформить заказ
 */
window.order = {};
window.order.del = '';
window.order.city = '';
window.order.street = '';
window.order.house = '';
window.order.aprt = '';
window.order.name = '';
window.order.lastname = '';
window.order.phone = '';
window.order.email = '';
window.order.comment = '';
window.order.payment = '';
//window.order.receiving = '';
window.order.save = '';
window.order.user = '';
window.order.deliveryPrice = '';
window.order.deliveryКm = '';
window.order.delpoint = '';
window.order.user = '';
window.order.mounting = '';

$('.del-form input').on('click',function(){
    $(this).removeClass('error');
    if($(this).attr('data-type') == 'del-km') {
        $('[data-type="del-km-err"]').hide();
    }
})
$('[data-type="save-del"]').on('click',function() {
    $(this).toggleClass('active');
})
$('[data-type="clear-del-data"]').on('click',function() {
    $('[data-type="addr-form"]').find('input').each(function() {
        $(this).val('');
    })
})

//считаем доставку
$('[data-type="del-km"]').focusout(function() {
    var km = $(this).val().replace(/,/, '.');
    if(km < 0 || km == '') km = 0;
    km = parseFloat(km);
    $(this).val(km);
    let onlySamples = checkOnlySamples();
    if(km == '' && km !== 0) {
        $(this).addClass('error');
    } else if(km > 20 && onlySamples) {
        $(this).addClass('error');
        $('[data-type="del-km-err"]').html('не более 20 км').show();
    } else if(km > 50) {
        $(this).addClass('error');
        $('[data-type="del-km-err"]').html('не более 50 км').show();
    } else {
        cartTotal();
    }
})

$('[data-type="form-submit"]').on('click',function(){

    if($('[data-type="header-cart-qty"]').html() == 0) {
        alert("Ваша корзина пуста!");
        return false;
    }
    var err = 0;

    //монтаж
    if($('[data-type="mounting"]').length != 0 && $('[data-type="mounting"]').hasClass('active')) {
        window.order.mounting = 'Y';
    } else {
        window.order.mounting = 'N';
    }

    //доставка
    if($('[data-type="delivery"]').length != 0) {
        $('[data-type="delivery"]').each(function () {
            if ($(this).hasClass('active')) {
                window.order.del = $(this).attr('data-val');
            }
        })
        if (window.order.del == '') {
            $('[data-type="delivery-wrap"]').addClass('error');
            err++;
            $('[data-type="delivery-wrap"]').closest('[data-type="cart-point"]').addClass('active');
        }
    }

    //пункт самовывоза
    if($('[data-type="pickup-point"]').length != 0 && window.order.del == 'pickup') {
        $('[data-type="pickup-point"]').each(function () {
            if ($(this).hasClass('active')) {
                window.order.delpoint = $(this).attr('data-val');
            }
        })
        if (window.order.del.point == '') {
            $('[data-type="pickup-point-wrap"]').addClass('error');
            err++;
            $('[data-type="pickup-point-wrap"]').closest('[data-type="cart-point"]').addClass('active');
        }
    }


    //км за МКАД
    if($('[data-type="del-km"]').length != 0) {
        window.order.deliveryКm = $('[data-type="del-km"]').val();
        if(window.order.deliveryКm == '') {
            $('[data-type="del-km"]').addClass('error');
            err++;
            $('[data-type="del-km"]').closest('[data-type="cart-point"]').addClass('active');
        }
        if(window.order.deliveryКm > 20 && checkOnlySamples()) {
            $('[data-type="del-km"]').addClass('error');
            $('[data-type="del-km-err"]').html('Не более 20 км!').show();
            err++;
            $('[data-type="del-km"]').closest('[data-type="cart-point"]').addClass('active');
        } else if(window.order.deliveryКm > 50) {
            $('[data-type="del-km"]').addClass('error');
            $('[data-type="del-km-err"]').html('Не более 50 км!').show();
            err++;
            $('[data-type="del-km"]').closest('[data-type="cart-point"]').addClass('active');
        }
    }

    //адреc
    if(window.order.del == 'del') {
        //город
        window.order.city = $('[data-type="city"]').val();
        if(window.order.city == '') {
            $('[data-type="city"]').addClass('error');
            err++;
            $('[data-type="city"]').closest('[data-type="cart-point"]').addClass('active');
        }
        //улица
        window.order.street = $('[data-type="street"]').val();
        if(window.order.street == '') {
            $('[data-type="street"]').addClass('error');
            err++;
            $('[data-type="street"]').closest('[data-type="cart-point"]').addClass('active');
        }
        //дом
        window.order.house = $('[data-type="house"]').val();
        if(window.order.house == '') {
            $('[data-type="house"]').addClass('error');
            err++;
            $('[data-type="house"]').closest('[data-type="cart-point"]').addClass('active');
        }
        //квартира
        window.order.aprt = $('[data-type="aprt"]').val();

        //id пользователя
        window.order.user = $('[data-type="personal-data"]').attr('data-id');

        //сохранить как адрес доставки по умолчанию
        if($('[data-type="save-del"]').hasClass('active')) {
            window.order.save = 'Y';
        }
    }

    //оплата
    if($('[data-type="payment"]').length != 0) {
        $('[data-type="payment"]').each(function(){
            if($(this).hasClass('active')) {
                window.order.payment = $(this).attr('data-val');
                if($(this).attr('data-val') == 'cash') {
                    //оплата при получении
                    /*if($('[data-type="receiving"]').length != 0) {
                      $('[data-type="receiving"]').each(function(){
                        if($(this).hasClass('active')) {
                          window.order.receiving = $(this).attr('data-val');
                        }
                      })
                      if(window.order.receiving == '') {
                        $('[data-type="payment-wrap"]').addClass('error');
                        err++;
                      }
                    }*/
                }
            }
        })
        if(window.order.payment == '') {
            $('[data-type="payment-wrap"]').addClass('error');
            err++;
            $('[data-type="payment-wrap"]').closest('[data-type="cart-point"]').addClass('active');
        }
    }

    //сохранить юзера
    if($('[data-type="form-submit"]').attr('data-user') == 'save') {
        window.order.user = 'save';
    }

    if(err != 0) {
        return false;
    }
    else {

        if(window.innerWidth > 800) {
            $('[data-type="order-first-step"]').hide();
            $('[data-type="p-form"]').show();
            if($('.cart-forms').find('#sticky-wrapper').length > 0) {
                $('[data-type="cart-order"]').sticky('update');
            }
        } else {
            $('[data-type="p-form"]').fadeIn();
            $('[data-type="overlay"]').fadeIn();
        }
    }
})
$('[data-type="form-submit"]').on('click',function(){
    yaCounter22165486.reachGoal('checkout');
})
/**
 * очистить корзину
 */
$('[data-type="clear-all"]').on('click',function() {
    $('[data-type="overlay"]').fadeIn();
    $('[data-type="clear-all-window"]').fadeIn();
})
$('[data-type="close-clear-all-window"]').on('click',function() {
    $('[data-type="overlay"]').fadeOut();
    $('[data-type="clear-all-window"]').fadeOut();
})
$(document).mousedown(function (e) {
    var container = $('[data-type="clear-all-window"]');
    if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(event.target).is('[data-type="clear-all-window"]')){
        $('[data-type="overlay"]').fadeOut();
        $('[data-type="clear-all-window"]').fadeOut();
    }
});
$('[data-type="clear-all-yes"]').on('click',function() {
    $.cookie('basket', null, {
        expires: -1,
        domain: domain,
        path: '/'
    });
    $.cookie('mount', null, {
        expires: -1,
        domain: domain,
        path: '/'
    });
    $('.mount-calc-amount').hide();
    $('[data-type="mount-warn"]').hide();
    $('.cart-item').each(function(){
        let qty = $(this).find('[data-type="prod-page-qty"]').val();
        if(qty > 0) sendAddAnalytics($(this),'remove',qty);
        if($(this).attr('data-type')!= 'adh') {
            $(this).remove();
        }
        else {
            $(this).find('[data-type="prod-page-qty"]').val(0);
        }
    })
    addCartHeader();
    cartCount();
    cartTotal();
    prodCost();
    $('[data-type="overlay"]').fadeOut();
    $('[data-type="clear-all-window"]').fadeOut();
})
$('[data-type="clear-all-no"]').on('click',function() {
    $('[data-type="overlay"]').fadeOut();
    $('[data-type="clear-all-window"]').fadeOut();
})
/**
 * форма оформить заказ
 */
function cleanOrderForm() {
    $('[data-type="p-form-inputs"] input').each(function(){
        $(this).removeClass('error');
        $(this).val('');
    })
    $('[data-type="p-form"]').find('[data-type="tel-wrap"]').removeClass('tel-error');
    $('[data-type="p-comment"]').removeClass('error').val('');
    $('#p_format').prop('checked', false);
    $('[data-type="pers-data"]').removeClass('active');
    $('[data-type="pers-data"]').removeClass('error');
    $('[data-type="p-submit"]').prop( "disabled", false );
}
$('[data-type="close-form"]').on('click',function(){
    $('[data-type="overlay"]').fadeOut();
    $('[data-type="p-form"]').fadeOut();
})
$(document).mousedown(function (e) {
    if(window.innerWidth <= 800) {
        let container = $('[data-type="p-form"]');
        if (container.css('display') != 'none' && container.has(e.target).length === 0 && !$(event.target).is('[data-type="p-form"]')) {
            $('[data-type="overlay"]').fadeOut();
            $('[data-type="p-form"]').fadeOut();
        }
    }
});
$('[data-type="clear-pers-data"]').on('click',function() {
    cleanOrderForm();
})
$('[data-type="p-reset"]').on('click',function(){
    cleanOrderForm();
    if(window.innerWidth > 800) {
        $('[data-type="p-form"]').hide();
        $('[data-type="order-first-step"]').show();
        if($('.cart-forms').find('#sticky-wrapper').length > 0) {
            $('[data-type="cart-order"]').sticky('update');
        }
    } else {
        $('[data-type="p-form"]').fadeOut();
        $('[data-type="overlay"]').fadeOut();
    }
})
$('[data-type="pers-data"]').on('click',function(e){
    $(this).removeClass('error');
    $('[data-type="p-submit"]').prop( "disabled", false );
    //отсекаем клик на ссылку
    var e = e || window.event;
    var target = e.target || e.srcElement;
    if (this == target) {
        $(this).toggleClass('active');
    };
})

$('[data-type="p-form-inputs"] input').on('click',function(){
    $(this).removeClass('error');
    $('[data-type="p-submit"]').removeAttr("disabled");
})
$('[data-type="p-form-inputs"] input').on('input',function(){
    $(this).removeClass('error');
    $('[data-type="p-submit"]').removeAttr("disabled");
})
$('[data-type="p-submit"]').on('click',function(){

    $(this).attr("disabled", "disabled");

    var err = 0;
    var form = $(this).closest('[data-type="p-form"]').find('form');

    window.order.name = $('[data-type="p-name"]').val();
    if(window.order.name == '') {
        $('[data-type="p-name"]').addClass('error');
        err++;
    }

    window.order.lastname = $('[data-type="p-last-name"]').val();
    if(window.order.lastname == '') {
        $('[data-type="p-last-name"]').addClass('error');
        err++;
    }

    window.order.phone = form.find('[name="p-phone"]').val();
    if(!checkTelNumber(form.find('[name="p-phone"]'))) {
        err++;
    }

    window.order.email = $('[data-type="p-mail"]').val();
    if(validateEmail(window.order.email) !== true) {
        $('[data-type="p-mail"]').addClass('error');
        err++;
    }

    window.order.comment = $('[data-type="p-comment"]').val();

    if($('[data-type="pers-data"]').length != 0) {
        if($('[data-type="pers-data"]').hasClass('active')) {

        }
        else {
            $('[data-type="pers-data"]').addClass('error');
            err++;
        }
    }
    if (err != 0) {
        return false;
    } else {
        var order_send = $.cookie('order_send');
        //order_send = 0;
        if (order_send == 1) {
            $('[data-type="p-form"]').fadeOut();
            $('[data-type="p-submit"]').removeAttr("disabled");
            data = '<p class="e-final-text  e-final-title">Оформление заказа</p>';
            data += '<p class="e-final-text">Это попытка повтора отправки заказа, Ваш&nbsp;заказ уже&nbsp;отправлен на&nbsp;обработку. </p>';
            data += '<p class="e-final-text">На Ваш почтовый ящик должно прийти письмо с&nbsp;формой&nbsp;заказа.</p>';
            data += '<p class="e-final-text">Если письмо от&nbsp;нашего сервиса отсутствует, повторите заказ&nbsp;позже.</p>';
            $('[data-type="order-req-content"]').html(data);
            $('[data-type="order-req"]').fadeIn();
            $('[data-type="order-req-close"]').on('click',function(){
                $('[data-type="overlay"]').fadeOut();
                $('[data-type="order-req"]').fadeOut();
            })
            $('[data-type="overlay"]').on('click',function(){
                $('[data-type="overlay"]').fadeOut();
                $('[data-type="order-req"]').fadeOut();
            })
            setTimeout(function(){
                $('[data-type="overlay"]').fadeOut();
                $('[data-type="order-req"]').fadeOut();
            },8000);
        } else {
            $('.order-loader').show();
            var order = {
                'del': window.order.del,
                'delpoint': window.order.delpoint,
                'km': window.order.deliveryКm,
                'city': window.order.city,
                'street': window.order.street,
                'house': window.order.house,
                'aprt': window.order.aprt,
                'name': window.order.name,
                'lastname': window.order.lastname,
                'phone': window.order.phone,
                'email': window.order.email,
                'comment': window.order.comment,
                'payment': window.order.payment,
                //'receiving': window.order.receiving,
                'save': window.order.save,
                'user': window.order.user,
                'mounting': window.order.mounting,
            };
            $.ajax({
                method: "POST",
                url: '/cart/order.php',
                data: order
            })
                .done(function (data) {
                    var data = $.parseJSON(data);
                    if(data['err'] > 0) {
                        $('.order-loader').hide();
                        if(window.innerWidth <= 800) $('[data-type="p-form"]').fadeOut();
                        $('[data-type="p-submit"]').removeAttr("disabled");
                        $('[data-type="order-req-content"]').html(data['data']);
                        $('[data-type="order-req"]').fadeIn();
                        $('[data-type="overlay"]').fadeIn();
                        $('[data-type="order-req-close"]').on('click',function(){
                            $('[data-type="overlay"]').fadeOut();
                            $('[data-type="order-req"]').fadeOut();
                        })
                        $('[data-type="overlay"]').on('click',function(){
                            $('[data-type="overlay"]').fadeOut();
                            $('[data-type="order-req"]').fadeOut();
                        })
                        setTimeout(function(){
                            $('[data-type="overlay"]').fadeOut();
                            $('[data-type="order-req"]').fadeOut();
                        },8000);
                    } else {
                        //yaCounter22165486.reachGoal('order_success');
                        if(data != "") {
                            var url = '';
                            if(data['data']) url+='?nmbr='+ data['data'];
                            window.location.href = '/cart/success.php'+url;
                        }
                    }

                });
        }
    }
})

$('[data-type="p-submit"]').on('click',function(){
    yaCounter22165486.reachGoal('order_success');
})

$('[data-type="save-cart"]').on('click',function() {
    $('[data-type="save-cart"]').attr('disabled','disabled');
    var list = $.cookie('basket');
    if($('[data-type="header-cart-qty"]').html() == 0) {
        alert("Ваша корзина пуста!");
        $('[data-type="save-cart"]').removeAttr('disabled');
        return false;
    }
    var userId = $('[data-type="personal-data"]').attr('data-id');
    $.post('/ajax/save_order.php', {order: list, id: userId}, function (data) {
        $('[data-type="save-cart"]').hide();
        $('[data-type="save-mess"]').fadeIn();
        $('[data-type="save-cart"]').removeAttr('disabled');
    });

})

$('[data-user="del"]').on('mouseover',function() {
    if($(this).attr('data-click') == 'no') {
        var top = window.scrollY;
        var topDel = $('[data-user="del"]').offset().top;
        if(topDel - top > 190) {
            $('.del-tooltip').removeClass('bottom');
            $('.del-tooltip').addClass('top');
        } else {
            $('.del-tooltip').removeClass('top');
            $('.del-tooltip').addClass('bottom');
        }
        $('.del-tooltip').fadeIn();
    }
})
$('[data-user="del"]').on('mouseout',function() {
    if($(this).attr('data-click') == 'no') {
        $('.del-tooltip').fadeOut();
    }
})

function hideDelivery() {
    var total = $('[data-type="total"]').attr('data-without-del');
    if(parseFloat(total) < 10000) {
        $('[data-user="del"]').removeClass('active');
        $('[data-user="del"]').attr('data-click','no');
        $('[data-user="del"]').addClass('no-click-del');
        //$('[data-user="del"]').hide();
        $('[data-user="del-wrap"]').removeClass('active');
        $('[data-user="pickup"]').addClass('active');
        $('[data-user="pickup-wrap"]').addClass('active');

        $('[data-type="delivery-sum"]').attr('data-val',0);
        $('[data-type="delivery-sum"]').html(costFormat(0));
        var total = $('[data-type="total"]').attr('data-without-del');
        $('[data-type="total"]').html(costFormat(total));
    } else {
        $('[data-user="del"]').removeClass('no-click-del');
        $('[data-user="del"]').attr('data-click','yes');
    }
    return false;
}




function checkOnlySamples() {
    let list = basketList(),
        i = 0;
    samplesQty = 0;
    while (i < list.length) {
        if(~list[i].id.toString().indexOf('s')) {
            samplesQty++;
        }
        i++;
    }
    let res = samplesQty == list.length ? true : false;
    return res;
}


function newCart() {
    cartCount();
    cartTotal();
    prodCost();
    checkMountingList();

    $('[data-type="tooltip-icon"]').on('click',function() {
        let val  = $(this).attr('data-val');
        $('[data-id="'+val+'"]').show();
        $(document).mousedown(function (e) {
            $('.cart-tooltip').each(function() {
                if($(this).css('display')!='none') {
                    if ($(this).has(e.target).length === 0 && !$(event.target).is('.cart-tooltip')){
                        $(this).hide();
                        return false;
                    }
                }
            })
        });
        $('[data-id="'+val+'"]').on('click', '.icon-close',function() {
            $('[data-id="'+val+'"]').hide();
            return false;
        })
        return false;
    })
}

/**
 * проверить соответствие корзины и кальк. монтажа (по колич.)
 */
function checkMountingList() {
    let res = true;
    let mountList = $.cookie('mount');
    if (mountList == undefined) {
        mountList = new Array();
    } else {
        mountList = JSON.parse(mountList);
    };
    let basket = basketList();
    for (let i = 0; i < basket.length && res === true; i++) {
        let inArr = 0;
        mountList.forEach(function(el,n,arr) {
            if(basket[i].id == el.id) {
                /*если не совпадает количество*/
                if(basket[i].qty != el.bqty && basket[i].qty != el.qty) res = false;
                inArr++;
            }
            if($('[data-id="'+el.id+'"]').length == 0) res = false;
        });
        /*если нет в массиве*/
        if($('[data-id="'+basket[i].id+'"]').attr('data-m-price') != undefined && inArr == 0) res = false;
    }
    if(res === true) {
        $('[data-type="mount-warn"]').hide();
    } else {
        $('[data-type="mount-warn"]').show();
    }
}

/**
 * при удалении тов. из корзины, удалить соотв. тов. в кальк. монтажа
 */
function removeItemFromMountCalc(id) {
    let basket = basketList();
    if(basket.length > 0) {
        $('[data-type="mount-warn"]').show();
    } else {
        $('.mount-calc-amount').hide();
        $('[data-type="mount-warn"]').hide();
    }
    /*console.log($('[data-id="'+id+'"]').attr('data-m-price'));
    //if($('[data-id="'+id+'"]').attr('data-m-price') != undefined) {
        console.log(id);
        let mountList = $.cookie('mount');
        if (mountList == undefined) {
            mountList = new Array();
        } else {
            mountList = JSON.parse(mountList);
        }
        mountList.forEach(function (el, n, arr) {
            if (id == el.id) {
                mountList.splice(n, 1);
                if(mountList.length == 0) {
                    $.cookie('mount', null, {
                        expires: -1,
                        domain: domain,
                        path: '/'
                    });
                } else {
                    $.cookie('mount', JSON.stringify(mountList), {
                        expires: basketExpires,
                        domain: domain,
                        path: '/'
                    });
                }
                checkMountingList();
                $.getJSON('/cart/mounting/mounting_data.php', {type: 'getMountList'}, function (data) {
                    //data = JSON.parse(data);
                    console.log(data);
                    if(data.total > 0) {
                        $('[data-type="cart-mount-total"]').html(costFormat(data.total));
                        $('.mount-calc-amount').show();
                    } else {
                        $('.mount-calc-amount').hide();
                        $('[data-type="mount-warn"]').hide();
                    }
                })
            }
        });

    //}*/
}
/**
 * общий расчет в мобильной версии
 */
$('.cart-forms').on('click','.fixed', function () {
    if(window.innerWidth <= 700) {
        if($(this).hasClass('closed')) {
            $(this).removeClass('closed');
            $(this).addClass('opened');
        } else {
            $(this).removeClass('opened');
            $(this).addClass('closed');
        }
    }
})
$(document).scroll(function () {
    defineTotalSumPosition();
})
function defineTotalSumPosition() {
    if(window.innerWidth <= 800) {
        let sTop = $("html").scrollTop();
        let mountListTop = $('.cart-items').offset().top + $('.cart-items').innerHeight() - $(window).height();
        if(sTop >= mountListTop && $('.cart-order-sum').hasClass('fixed')){
            $('.cart-order-sum').removeClass('fixed');
            $('.cart-order-sum').removeClass('opened');
            $('.cart-order-sum').removeClass('closed');
        } else if(sTop < mountListTop && !$('.cart-order-sum').hasClass('fixed')) {
            $('.cart-order-sum').addClass('fixed');
            $('.cart-order-sum').removeClass('opened');
            $('.cart-order-sum').addClass('closed');
        }
    }
}
$('.cart-order-sum').on('click','[data-type="go-to-check-out"]',function() {
    let mountListTop = $('.cart-items').offset().top + $('.cart-items').innerHeight() - $('header').innerHeight();
    $('body,html').animate({scrollTop: mountListTop}, 1000);
    /*$('.cart-order-sum').removeClass('fixed');
    $('.cart-order-sum').removeClass('opened');
    $('.cart-order-sum').removeClass('closed');*/
})

/**
 * открыть/закрыть пункты оформления корзины
 */
$('[data-type="cart-point-name"]').on('click', function() {
    let wrap = $(this).closest('[data-type="cart-point"]');
    wrap.toggleClass('active');
    defineTotalSumPosition();
    if($('.cart-forms').find('#sticky-wrapper').length > 0) {
        $('[data-type="cart-order"]').sticky('update');
    }
})

/*
свернуть клей в моб. версии
 */
function hideAdh(){
    if(window.innerWidth <= 800) {
        return true;
    }
}



document.addEventListener("DOMContentLoaded", newCart);