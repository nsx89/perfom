const supportsTouch = ('ontouchstart' in document.documentElement);

var searchProdCount = 5;

$(window).on('resize', function() {
    footerMenuPosition();
    if(window.innerWidth < 1180) {
        $('[data-type="dropdown-menu"]').hide();
    }
});

$('document').ready(function() {

    setTimeout(function(){
        if ($('.img-load').length) {
            $('.img-load').each(function(){
                var src = $(this).attr('data-src');
                $(this).attr('src', src);
                $(this).attr('data-src', '');
            });
        }
    }, 300);

    setTimeout(function(){
        if ($('.iframe-load').length) {
            $('.iframe-load').each(function(){
                var src = $(this).attr('data-src');
                $(this).attr('src', src);
                $(this).attr('data-src', '');
            });
        }
    }, 600);
    
    if(supportsTouch === true) {
        $('body').addClass('touchable')
    }

    footerMenuPosition()

    /*$('[data-type="mobile-menu"]').on('click', 'a', function (e) {
        e.preventDefault();
        if (window.innerWidth <= 1180 && window.innerWidth > 700) {
            $('[data-type="menu-btn"]').find('.menu-icon').removeClass('opened');
            $('[data-type="menu-btn"]').find('.menu-icon').addClass('closed');
            $('[data-type="mobile-menu"]').removeClass('active');
            $('body').removeClass('disabled');
        }
    })*/

    $('[data-type="menu-btn"]').on('click', function () {
        let menuIcon = $(this).find('.menu-icon');
        if (menuIcon.hasClass('closed')) {
            menuIcon.removeClass('closed');
            menuIcon.addClass('opened');
            $('[data-type="mobile-menu"]').addClass('active');
            $('body').addClass('disabled');
        } else if (menuIcon.hasClass('opened')) {
            menuIcon.removeClass('opened');
            menuIcon.addClass('closed');
            $('[data-type="mobile-menu"]').removeClass('active');
            $('body').removeClass('disabled');
        }
    })
    $(document).mouseup(function (e) {
        if (window.innerWidth <= 1180) {
            var container = $('[data-type="mobile-menu"]');
            var btn = $('[data-type="menu-btn"]');
            if (container.is(":visible") && !container.is(e.target) && container.has(e.target).length === 0 && !btn.is(e.target) && btn.has(e.target).length === 0) {
                let menuIcon = $(this).find('.menu-icon');
                menuIcon.removeClass('opened');
                menuIcon.addClass('closed');
                $('[data-type="mobile-menu"]').removeClass('active');
                $('body').removeClass('disabled');
            }
            return false;
        }
    });

    /**
     * маска телефона
     */
    $('[data-tel="yes"]').each(function() {
        if($(this).attr('data-mask')) {
            $.mask.definitions['9'] = false;
            $.mask.definitions['X'] = "[0-9]";
            $(this).mask($(this).attr('data-mask'), { autoclear: false });
        }
    })
    $('[data-tel="yes"]').on('click',function() {
        let mask = $(this).attr('data-mask');
        mask = mask.replace(/X/g,'_');
        let position = mask.indexOf('_');
        if ($(this).val() === mask || $(this).val() == '') {
            $(this).get(0).setSelectionRange(position, position);
        }
        $(this).closest('[data-type="tel-wrap"]').removeClass('tel-error');
    })
    $('[data-tel="yes"]').on('focusout',function() {
        checkTelNumber($(this));
    })
    $('[data-tel="yes"]').on('change',function() {
        checkTelNumber($(this));
    })
    $('form').on('input','[data-tel="yes"]',function() {
        var input = $(this);
        setTimeout(function() {
            checkTelNumber(input)
        },10);
    })
    $('[data-type="online-format"]').on('change',function() {
        let phoneInput = $(this).closest('form').find('[data-tel="yes"]');
        if($(this).is(':checked')) {
            phoneInput.unmask();
            phoneInput.val('');
        } else {
            phoneInput.mask(phoneInput.attr('data-mask'), { autoclear: false });
            phoneInput.val('');
        }
        phoneInput.closest('[data-type="tel-wrap"]').removeClass('tel-error');
    })

    /**
     * header geolocation
     */
    $('header').on('click','[data-type="geo-open"]',function() {
        $(this).css('z-index','10');
        $(this).attr("data-type","geo-close");
        $(this).removeClass('icon-geo');
        $(this).addClass('icon-close');
        $('[data-type="reg-list"]').slideDown();
        regScroll.reinitialise();
        $('body').addClass('disabled');
    })

    if ($('.js-geo-open-auto').length) {
        $('.js-geo-open-auto').trigger('click');
    }

    $('header').on('click','[data-type="geo-close"]',function() {
        $(this).css('z-index','0');
        $(this).attr("data-type","geo-open");
        $(this).addClass('icon-geo');
        $(this).removeClass('icon-close');
        $('[data-type="reg-list"]').slideUp();
        $('[data-type="curr-reg"]').html();
        $('body').removeClass('disabled');
        $('*[data-type="reg-count-wrap"]').removeClass('active');
        $('*[data-type="reg-city"]').hide();
    })
    /**
     * choose a new region
     */
    $('#dropdown-down').on('click','[data-type="reg-list"] [data-type="choose-reg"]',function() {
        var regionId = $(this).attr('data-value');
        var href = $(this).attr('href');
        $.post('/ajax/changeregion.php', {regionId: regionId}, function (html) {
            //console.log(html);
            if (href) {
                window.location.href = href;
            }
            else {
                window.location.reload();
            }
        });
        return false;
    })
    if(typeof regScroll === 'undefined') {
        var regScroll = $('[data-type="reg-list-scroll"]').jScrollPane({
            showArrows: false,
            maintainPosition: false
        }).data('jsp');
    }
    $(window).bind('resize', function(){
        regScroll.reinitialise();
    })

    $('[data-type="reg-count"]').on('click',function() {
        let wrap = $(this).closest('[data-type="reg-count-wrap"]'),
            list  = wrap.find('[data-type="reg-city"]');
        if(wrap.hasClass('active')) {
            wrap.removeClass('active');
            list.hide();
            regScroll.reinitialise();
        } else {
            wrap.addClass('active');
            list.slideDown(function() {
                regScroll.reinitialise();
            });
        }
    })

    if ($('[data-type="terms-menu"]').length > 0) {
        $('[data-type="terms-menu"]').sticky({
            topSpacing: $('header').outerHeight(),
            bottomSpacing: $('footer').outerHeight() + 60
        });
    }
    $('[data-type="terms-menu"]').on('click','a',function(e) {
        e.preventDefault();
        let id = $(this).attr('href'),
            top = $(id).offset().top - $('header').outerHeight();
        $('body,html').animate({scrollTop: top}, 1000);
    })
    $('[data-type="main-tab"]').on('click',function(e) {
        e.preventDefault();
        let val = $(this).attr('href');
        $('[data-type="main-tab"]').each(function() {
            $(this).removeClass('active');
        })
        $('[data-type="main-tab-cont"]').each(function() {
            $(this).removeClass('active');
        })
        $('[href="'+val+'"]').addClass('active');
        $(val).addClass('active');
        let url = document.location.pathname+val;
        window.history.replaceState("", "", url);

        //персональный кабинет - профиль
        if(val == '#profile') {
            if(window.innerWidth > 1000) {
                $('[data-type="profile-nav"]').show();
                $('[data-type="profile-nav"]').find('.active').removeClass('active');
            } else {
                $('[data-type="profile-nav-mob"]').css('display','flex');
                $('[data-type="profile-nav-mob"]').find('.active').removeClass('active');
            }
            $('.profile-change-pass').hide();
            $('.profile-data-wrap').show();
            $('.profile-data-form').find('input').each(function() {
                if(!$(this).hasClass('unchangeable')) {
                    $(this).prop('readonly',true);
                    $(this).val($(this).attr('oldval'));
                    $('.personal-data-form-error').hide();
                }
            })
            $('.profile-data-form').removeClass('edited');
        } else {
            $('[data-type="profile-nav"]').hide();
            $('[data-type="profile-nav-mob"]').hide();
        }
    })


    if($('[data-fancybox]').length > 0) {
        Fancybox.bind("[data-fancybox]", {
            // Your custom options
        });
    }




}) //document.ready

/* slider initialize */

//проверить имейл
function validateEmail(email) {
    var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email);
}

/**
 * получить массив избранного
 * **/
var favoriteArr = [];

const xhrFav = new XMLHttpRequest();
xhrFav.open('GET', '/personal/ajax.php?type=get_favorite');
xhrFav.onload = function() {
    if (xhrFav.readyState !== 4 || xhrFav.status !== 200) {
        return;
    }
    if(xhrFav.response) {
        favoriteArr = JSON.parse(xhrFav.response);
    } else {
        return;
    }
}
xhrFav.onerror = function() {
    console.log("Favorite: Произошла непредвиденная ошибка.");
};
xhrFav.send();

/**
 * добавить в избранное
 */
$('.wrapper').on('click','[data-type="favorite"]',function(e) {

    e.preventDefault();

    let wrap = $(this).closest('[data-type="prod-prev"]');
    let favoriteQty = 0;
    if(wrap.length > 0) {
        var productId = wrap.attr('data-id');
    } else {
        var productId = $('[data-type="prod-info"]').attr('data-id');
    }

    let user = $('[data-type="personal-data"]').attr('data-id');
    let mess = 'Товар добавлен в&nbsp;избранное';
    if($(this).hasClass('active')) mess = 'Товар удален из&nbsp;избранного';

    $(this).toggleClass('active');

    if(user && $(this).attr('data-user') == 'user') {
        $.ajax({
            type: "POST",
            url: "/personal/ajax.php",
            data: 'product_id='+productId+'&type=favorite&user_id='+user,
            success: function(resp){
                favoriteQty = 1;
                favoriteArr = JSON.parse(resp);
            }
        });
    } else {
        let favorite = $.cookie('favorite');
        if (favorite == undefined) {
            favorite = [];
        } else {
            favorite = JSON.parse(favorite);
        }
        if (favorite.length > 0) {
            let index = $.inArray(productId,favorite);
            if( index == -1) {
                favorite.push(productId);
            }
            else {
                favorite.splice(index,1);
                //удаляем на странице избранного
                if($(this).closest('[data-val="fav"]').length > 0) $(this).closest('[data-type="prod-prev"]').remove();
            }
        } else {
            favorite.push(productId);
        }
        favoriteArr = favorite;
        let domain = location.hostname;
        $.cookie('favorite', JSON.stringify(favorite), {
            domain: domain,
            path: '/'
        });
        favoriteQty = favorite.length;
        $('.header-favorite').find('.header-icon-qty').html(favoriteQty);
        if(favoriteQty > 0) {
            $('.header-favorite').addClass('not-empty');
        } else {
            $('.header-favorite').removeClass('not-empty');
        }
        wrap.find('.prod-prev-favorite-mess').html(mess).addClass('show');
        setTimeout(function(){
            wrap.find('.prod-prev-favorite-mess').removeClass('show');
        },2000)
    }
    return false;
});
/**
 * добавить товар в корзину с превью
 */
$('.wrapper').on('click','[data-type="cart-add"]',function(){
    let wrap = $(this).closest('[data-type="prod-prev"]'),
        btn = $(this),
        productId = wrap.attr('data-id'),
        prodQty = 1,
        iscomp = wrap.attr('data-iscomp');
    checkCompAvailability(productId,iscomp).then(function(data) {
        if(data == true) {
            if(wrap.attr('data-maur-spec') == 1) {
                $.get('/ajax/mauritania_moulded_check.php?id='+productId+'&qty='+prodQty, function(data) {
                    data = JSON.parse(data);
                    if(data['err'] == 0) {
                        addProdCart(productId,prodQty,iscomp);
                        btn.addClass('active');
                        wrap.find('.prod-prev-cart-mess').addClass('show');
                        setTimeout(function() {
                            wrap.find('.prod-prev-cart-mess').removeClass('show');
                        },2000);
                        sendAddAnalytics(wrap,'add');
                        if (typeof yaCounter22165486 !== 'undefined') {
                            yaCounter22165486.reachGoal('small_basket');
                        }
                    } else {
                        $('[data-type="maur-pop-content"]').html(data['mess']);
                        $('[data-type="maur-pop"]').fadeIn(clouseMaurPop());
                        $('[data-type="overlay"]').fadeIn();
                    }
                })
            } else {
                addProdCart(productId,prodQty,iscomp);
                btn.addClass('active');
                wrap.find('.prod-prev-mob-btns').find('[data-type="cart-add"]').html('В корзине');
                wrap.find('.prod-prev-cart-mess').addClass('show');
                setTimeout(function() {
                    wrap.find('.prod-prev-cart-mess').removeClass('show');
                },2000);
                sendAddAnalytics(wrap,'add');
                if (typeof yaCounter22165486 !== 'undefined') {
                    yaCounter22165486.reachGoal('small_basket');
                }
            }
        }
    })
        .catch(function(err) {
            console.log(err)
        });
    return false;
});
/**
 * купить в 1 клик
 */
$('.wrapper').on('click','[data-type="one-click"]',function(event){
    let wrap = $(this).closest('[data-type="prod-prev"]'),
        btn = $(this),
        productId = wrap.attr('data-id'),
        prodQty = 1,
        iscomp = wrap.attr('data-iscomp');
    let wrap_category = $(this).closest('.cat-products'),
        wrap_glue = $(this).closest('.prod-similar-glue'),
        wrap_instyle = $(this).closest('.prod-similar-instyle');
    checkCompAvailability(productId,iscomp).then(function(data) {
        if(data == true) {
            if(wrap.attr('data-maur-spec') == 1) {
                $.get('/ajax/mauritania_moulded_check.php?id='+productId+'&qty='+prodQty, function(data) {
                    data = JSON.parse(data);
                    if(data['err'] == 0) {
                        addProdCart(productId,prodQty,iscomp);
                        btn.addClass('active');
                        sendAddAnalytics(wrap,'add');
                        if(typeof yaCounter22165486 !== 'undefined') yaCounter22165486.reachGoal('small_basket');
                        var url = "/cart";
                        $(location).attr('href',url);
                    } else {
                        $('[data-type="maur-pop-content"]').html(data['mess']);
                        $('[data-type="maur-pop"]').fadeIn(clouseMaurPop());
                        $('[data-type="overlay"]').fadeIn();
                    }
                })
            } else {
                addProdCart(productId,prodQty,iscomp);
                btn.addClass('active');
                var url = "/cart";
                $(location).attr('href',url);
                sendAddAnalytics(wrap,'add');
                if(typeof yaCounter22165486 !== 'undefined') {
                    yaCounter22165486.reachGoal('small_basket');
                    if (wrap_category.length) {
                        yaCounter22165486.reachGoal('311094809');
                        console.log('one-click-category');
                    }
                    if (wrap_glue.length) {
                        yaCounter22165486.reachGoal('311095461');
                        console.log('one-click-glue');
                    }
                    if (wrap_instyle.length) {
                        yaCounter22165486.reachGoal('311095472');
                        console.log('one-click-instyle');
                    }
                }
            }
        }
    })
    return false;
})

/**
 * закрыть всплывающее окно с элементами для мавритании
 */
$('[data-type="maur-pop-close"]').on('click',function(){
    $('[data-type="maur-pop"]').fadeOut();
    $('[data-type="overlay"]').fadeOut();
})

/**
 * всплывающее меню каталога в хедере
 */
$('[data-type="header-catalogue"]').on('mouseenter',function(){
    if(window.innerWidth >= 1180) {
        $('[data-type="dropdown-menu"]').slideDown();
    }
})
$('[data-type="header-catalogue"]').on('mouseleave',function(){
    hideDropdownMenu();
})
$('[data-type="dropdown-menu"]').on('mouseleave',function(){
    hideDropdownMenu();
})
function hideDropdownMenu() {
    setTimeout(function(){
        if($('[data-type="dropdown-menu"]:hover').length > 0||$('[data-type="header-catalogue"]:hover').length > 0) {
            return false;
        }
        else {
            $('[data-type="dropdown-menu"]').hide();
            return false;
        }
    },500)
    return false;
}

function footerMenuPosition() {
    if(window.innerWidth < 1300) {
        let footerMenuOffset = $('.footer-menu').offset(),
            secondColumn = $('.footer-menu>li').eq(1),
            firstColumnOffset = $('.footer-menu li').first().offset(),
            mrgn = firstColumnOffset.left - footerMenuOffset.left;
        secondColumn.css('margin-left',mrgn);
    }
}

function clouseMaurPop() {
    $(document).mouseup(function (e) {
        var container = $('[data-type="maur-pop"]');
        var target = e.target;
        if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(target).is('[data-type="maur-pop"]')){
            $('[data-type="maur-pop"]').fadeOut();
            $('[data-type="overlay"]').fadeOut();
        }
    });
}

function sendAddAnalytics(wrap,type,qty) {
    if(!type) type = 'add';
    if(!qty) {
        if(type == 'add' && wrap.find('[data-type="prod-page-qty"]').length > 0) {
            if(wrap.attr('data-type') == 'prod-info' || wrap.attr('data-type') == 'adh') {
                qty = wrap.find('[data-type="prod-page-qty"]').val();
            } else {
                qty = 1;
            }
        } else {
            qty = 1;
        }
    }
    let products = [
        {
            "id": wrap.attr('data-code'),
            "name": wrap.attr('data-name'),
            "price": parseFloat(wrap.attr('data-price')),
            "category": wrap.attr('data-cat-name'),
            "quantity": parseInt(qty)
        }
    ];
    let currencyCode = wrap.attr('data-curr')
    let yaArr = {
        "ecommerce": {
            "currencyCode" : currencyCode,
            [type] : {
                "products": products
            }
        }
    }
    let gaArr = {
        "currency" : currencyCode,
        "items": products
    }
    //console.log(yaArr);
    //let gaEvent = type == 'add' ? 'add_to_cart' : 'remove_from_cart';
    //window.dataLayer.push(yaArr);
    //gtag('event', gaEvent, gaArr);
}

function checkMauritaniaSpecial(id,qty) {
    var $specArr = [],
        $prodArr = [],
        specCount = 0,
        prodCount = 0,
        panelCount = 0,
        err = 0,
        mess = '';
    $('.cart-items').find('.cart-item').each(function() {
        var itemQty = parseInt($(this).attr('data-qty'));
        if($(this).attr('data-maur-spec') == 1) {
            specCount += itemQty;
            if($(this).attr('data-id') == id) specCount += qty;
        } else if($(this).attr('data-maur') == 1) {
            prodCount += itemQty;
            if($(this).attr('data-id') == id) prodCount += qty;
        } else if($(this).attr('data-maur') == 4) {
            panelCount += itemQty;
            if($(this).attr('data-id') == id) panelCount += qty;
        }
    })
    //console.log('specCount - '+specCount);
    //console.log('prodCount - '+prodCount);
    if(qty > 0) {
        if(prodCount*2 + Math.floor(panelCount/4) < specCount) {
            err++;
            mess = '<p>Данный товар невозможно добавить в&nbsp;корзину, <br>т.к.&nbsp;в&nbsp;корзине недостаточно соответствующх ему изделий <br>из&nbsp;коллекции <span>MAURITANIA</span>. </p><p>Для&nbsp;уточнения вопроса обратитесь к&nbsp;менеджеру.</p>';
        }
    }
    if(qty < 0) {
        if(prodCount*2 + panelCount/4 < specCount) {
            var specItemsQty = $('[data-maur-spec="1"]').length;
            var specQtyDiff = specCount - (prodCount*2 + Math.floor(panelCount/4));
            if(specQtyDiff == 0) {
                $('.cart-items').find('[data-maur-spec="1"]').each(function() {
                    addProdQty($(this).attr('data-id'),0);
                    $(this).remove();
                })
            } else if(specQtyDiff > 0) {
                $('.cart-items').find('[data-maur-spec="1"]').each(function() {
                    if(specQtyDiff > 0) {
                        var itemQty = $(this).attr('data-qty');
                        if(itemQty > specQtyDiff) {
                            sendAddAnalytics($(this),'remove',specQtyDiff);
                            $(this).find('[data-type="prod-page-qty"]').val(itemQty - specQtyDiff);
                            $(this).attr('data-qty',itemQty - specQtyDiff);
                            addProdCart($(this).attr('data-id'),-specQtyDiff,0);
                            specQtyDiff = 0;
                        } else if(itemQty == specQtyDiff) {
                            sendAddAnalytics($(this),'remove',specQtyDiff);
                            addProdQty($(this).attr('data-id'),0);
                            $(this).remove();
                            specQtyDiff = 0;
                        } else {
                            specQtyDiff -= itemQty;
                            sendAddAnalytics($(this),'remove',itemQty);
                            addProdQty($(this).attr('data-id'),0);
                            $(this).remove();
                        }
                    }
                })
            }
        }
    }
    if(err > 0) {
        $('[data-type="maur-pop-content"]').html(mess);
        $('[data-type="maur-pop"]').fadeIn(clouseMaurPop());
        $('[data-type="overlay"]').fadeIn();
        return false;
    } else {
        return true;
    }

}

function checkTelNumber(input) {
    let form = input.closest('form'),
        mask = input.attr('data-mask'),
        wrap = input.closest('[data-type="tel-wrap"]'),
        otherFormatInput = form.find('[data-type="online-format"]');
    form.find('[disabled="disabled"]').removeAttr('disabled');
    //console.log(input.val());
    if(input.attr('required') == "required" && input.val() == '') {
        wrap.addClass('tel-error');
        //console.log('false 1');
        if(form.find('[data-type="errorphone"]').length > 0) {
            $('[data-type="errorphone"]').css('display','block').animate({opacity:1});
        }
        return false;
    } else if(input.attr('required') != "required" && input.val() == '' || input.attr('required') != "required" && input.val() == mask.replace(/X/g,'_')) {
        //console.log('true 1');
        wrap.removeClass('tel-error');
        if(form.find('[data-type="errorphone"]').length > 0) {
            $('[data-type="errorphone"]').css('display','none').css('opacity','0');
        }
        return true;
    } else {
        if(!input.attr('data-mask')) {
            //console.log('true 2');
            wrap.removeClass('tel-error');
            if(form.find('[data-type="errorphone"]').length > 0) {
                $('[data-type="errorphone"]').css('display','none').css('opacity','0');
            }
            return true;
        } else {
            if(otherFormatInput.is(':checked')) {
                //console.log('true 3');
                wrap.removeClass('tel-error');
                if(form.find('[data-type="errorphone"]').length > 0) {
                    $('[data-type="errorphone"]').css('display','none').css('opacity','0');
                }
                return true;
            } else {
                if(input.val().length != mask.length  || input.val().includes("_")) {
                    wrap.addClass('tel-error');
                    if(form.find('[data-type="errorphone"]').length > 0) {
                        $('[data-type="errorphone"]').css('display','block').animate({opacity:1});
                    }
                    //console.log('false 2');
                    return false;
                } else {
                    wrap.removeClass('tel-error');
                    if(form.find('[data-type="errorphone"]').length > 0) {
                        $('[data-type="errorphone"]').css('display','none').css('opacity','0');
                    }
                    //console.log('true 4');
                    return true;
                }
            }
        }
    }
}

//проверка на наличие составных частей
function checkCompAvailability(id,iscomp) {
    return new Promise(function(resolve) {
        if (iscomp == 0) {
            resolve(true);
        } else {
            $.get('/ajax/check_comp.php?id='+id, function(data) {
                data = JSON.parse(data);
                if(data['qty'] > 0) {
                    let mess = '<p>К сожалению, следующие составные части товара <br>в&nbsp;настоящий момент недоступны для&nbsp;заказа <br>и&nbsp;не&nbsp;могут быть добавлены в&nbsp;корзину:</p>';
                    mess += '<p class="not-avail-comp">';
                    data['name'].forEach(function(item){
                        mess += '<span>'+item+'</span>';
                    })
                    mess += '</p>';
                    mess += '<p>Хотите положить доступные части товара в&nbsp;корзину?</p>';
                    mess += '<div class="not-avail-comp-btns">';
                    mess += '<div class="not-avail-comp-btn-no">Отменить</div>';
                    mess += '<div class="not-avail-comp-btn-yes">Положить в корзину</div>';
                    mess += '</div>';
                    $('[data-type="maur-pop-content"]').html(mess);
                    $('[data-type="maur-pop"]').fadeIn(clouseMaurPop());
                    $('[data-type="overlay"]').fadeIn();
                    $('[data-type="maur-pop"]').on('click','.not-avail-comp-btn-no',function () {
                        $('[data-type="maur-pop"]').fadeOut();
                        $('[data-type="overlay"]').fadeOut();
                        resolve(false);
                    })
                    $('[data-type="maur-pop"]').on('click','.not-avail-comp-btn-yes',function () {
                        $('[data-type="maur-pop"]').fadeOut();
                        $('[data-type="overlay"]').fadeOut();
                        resolve(true);
                    })
                } else {
                    resolve(true);
                }
            })
        }

    })
}

/**
 * получить куки корзины
 */
function basketList() {
    var list = $.cookie('basket');
    if (list == undefined) {
        list = new Array();
    } else {
        list = JSON.parse(list);
    }
    return list;
}

/**
 * обновить корзину в шапке
 */
function addCartHeader(){
    var list = basketList();
    var qty = 0;
    var i = 0;
    while (i < list.length) {
        qty += list[i].qty;
        i++;
    }
    //console.log(qty);
    $('[data-type="header-cart"]').find('.header-icon-qty').html(qty);
    if(qty > 0) {
        $('[data-type="header-cart"]').addClass('not-empty');
    }
    else {
        $('[data-type="header-cart"]').removeClass('not-empty');
        $.cookie('mount', null, {
            expires: -1,
            domain: domain,
            path: '/'
        });
        $('.mount-calc-amount').hide();
    }
    return false;
}

/**
 * добавить товар в корзину
 */
function addProdCart(id,qty,iscomp){
    if (iscomp == '1') {
        $.get('/ajax/construction.php?mode=get&id='+id, function(data) {
            data = $.parseJSON(data);
            var i = 0;
            while (i < data.length) {
                addCart(data[i]['ID'],qty);
                i++;
            }
        });
    }
    else {
        addCart(id,qty);
    }
}

/**
 * добавить товар в куки корзины
 */
function addCart(id,qty) {
    var list = basketList();
    var i = 0;
    var exist = false;
    if(list.length > 0) {
        while (i < list.length) {
            if (list[i].id == id) {
                list[i].qty += qty;
                exist = true;
                if(list[i].qty <= 0) {
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

function costFormat(cost) {
    cost = parseFloat(cost);
    cost = cost.toFixed(2);
    var parts = cost.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, " ");
    cost = parts.join(".");
    cost = cost + ' '+$('[data-type="header-cart"]').attr('data-curr');
    return cost;
}

/**
 * проверить, есть ли товар в корзине
 */
function isInCart(id) {
    let list = basketList(),
        i = 0;
    if(list.length > 0) {
        while (i < list.length) {
            if (list[i].id == id) {
                return true;
                break;
            }
            i++;
        }
        return false;
    }
    else {
        return false;
    }
}

/**
 * проверить, есть ли товар в избранном
 */
function isFav(id) {
    for(let i = 0; i < favoriteArr.length; i++) {
        if(favoriteArr[i] == id) return true;
    }
    return false;
}
