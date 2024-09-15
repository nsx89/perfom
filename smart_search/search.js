$('.smart-search-wrap').show();

var smartSearchObj = {},
    smartSearchProdList = {},
    smartSearchObjErr = false,
    smartSearchTimer,
    searchWord = '';

// создаем асинхронный ajax
// объект с товарами будет грузиться в фоне при входе клиента на сайт
// с загруженным объектом быстрее проходит поиск
// взято из:
// https://itchief.ru/javascript/ajax-introduction
const xhr = new XMLHttpRequest();
xhr.open('GET', '/smart_search/ajax.php?type=get_list');
xhr.onload = function() {
    if (xhr.readyState !== 4 || xhr.status !== 200) {
        console.log(`Smart Search: Ошибка: код - ${xhr.readyState}, статус - ${xhr.status}`);
        smartSearchObjErr = true;
        return;
    }
    //console.log(xhr.response);
    smartSearchObj = $.parseJSON(xhr.response);
    //console.log(smartSearchObj);

    /*smartSearchObj.forEach(function(item) {
        smartSearchProdList.push(item.name);
    })*/
    for (let key in smartSearchObj) {
        smartSearchProdList[key] = smartSearchObj[key].name.toLowerCase();
    }
    //console.log(smartSearchProdList);
    if($('[data-type="smart-search-input"]').length > 0) {
        //autocomplete($('[data-type="smart-search-input"]')[0], smartSearchProdList);
    }
    return;
}
// вызывается по мере поступления данных
xhr.onprogress = function(e) {
    // если сервер присылает заголовок Content-Length
    if (e.lengthComputable) {
        // e.loaded - количество загруженных байт
        // e.total - количество байт всего
        console.log(`Smart Search: Получено ${e.loaded} из ${e.total} байт`);
    } else {
        console.log(`Smart Search: Получено ${e.loaded} байт`);
    }
    return;
};
xhr.onerror = function() {
    console.log("Smart Search: Произошла непредвиденная ошибка.");
    return;
};
xhr.send();

$(document).ready(function() {

    $(window).bind('resize', function() {
        if(typeof searchScroll !== 'undefined') {
            searchScroll.reinitialise();
        }
    })
    //при нажатии enter
    $('[data-type="smart-search-input"]').keydown(function(event) {
        if(event.keyCode == 13) {
            event.preventDefault();
            smartSearchResClear();
            smartSearchShortRes();
            return false;
        }
    });
    $('[data-type="smart-search-input"]').keyup(function(event) {
        //при вводе текста
        if(event.keyCode != 13) {
            smartSearchResClear()
            smartSearchShortRes();
            setStatistics($(this));
        }
    });

    $('[data-type="search-open"]').on('click',function() {
        if($(this).hasClass('close')) {
            $('[data-type="smart-search-wrap"]').removeClass('active');
            $('[data-type="overlay"]').hide();
            $('body').removeClass('ss-disabled');
            smartSearchResClearTotal();
            $(this).removeClass('close')
        } else {
            $('[data-type="smart-search-wrap"]').addClass('active');
            $('body').addClass('ss-disabled');
            $(this).addClass('close');
            if(window.innerWidth  > 1180) {
                $('[data-type="overlay"]').show();
            }
            $(window).on('resize', function() {
                if(window.innerWidth  > 1180) {
                    $('[data-type="overlay"]').show();
                } else {
                    $('[data-type="overlay"]').hide();
                }
                return false;
            })
        }
        return false;
    })
    $('[data-type="smart-search-close"]').on('click',function() {
        $('[data-type="smart-search-wrap"]').removeClass('active');
        $('[data-type="overlay"]').hide();
        $('body').removeClass('ss-disabled');
        smartSearchResClearTotal();
        $('[data-type="search-open"]').removeClass('close');
    })
    $(document).mouseup(function (e) {
        if(window.innerWidth  > 1180) {
            let container = $('[data-type="smart-search-wrap"]');
            let target = e.target;
            if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(target).is('[data-type="smart-search-wrap"]')) {
                $('[data-type="smart-search-wrap"]').removeClass('active');
                $('[data-type="overlay"]').hide();
                $('body').removeClass('ss-disabled');
                smartSearchResClearTotal();
                $('[data-type="search-open"]').removeClass('close');
            }
        }
    });

    /**
     * поиск на странице
     */
    if($('[data-type="searchSP"]').length > 0) {
        let url = window.location.href;
        url = url.split('?');
        if(url.length > 1) {
            q = url[1].split('=')[1];
            if(q != '') {
                smartSearchFullResClear();
                smartSearchFullRes();
            }
        }
    }
    $('[data-type="searchSP"]').keydown(function(event) {
        if(event.keyCode == 13) {
            event.preventDefault();
            smartSearchFullResClear();
            smartSearchFullRes();
            return false;
        }
    });
    $('[data-type="searchSP"]').keyup(function(event) {
        //при вводе текста
        if(event.keyCode != 13) {
            smartSearchFullResClear();
            smartSearchFullRes();
            setStatistics($(this));
            return false;
        }
    });
    $('[data-type="search-resetSP"]').on('click',function () {
        $('[data-type="search-messSP"]').hide();
        $('[data-type="search-resSP-qty"]').hide();
        $('[data-type="search-resSP"]').hide();
        $('[data-type="search-resSP-prod"]').html('');
        $('[data-type="search-page"]').find('.icon-search').show();
        $('[data-type="search-page"]').find('.search-wait').hide();
        $('[data-type="search-formSP"]')[0].reset();
        $('[data-type="searchSP"]').val('');
        let url = window.location.href;
        url = url.split('?');
        url = url[0] + '?q= ';
        window.history.replaceState("", "", url);
    })

})
//end of document.ready

$('[data-type="smart-search-btn"]').on('click',function() {
    smartSearchResClear();
    smartSearchShortRes();
})
$('[data-type="smart-search-clear"]').on('click', function () {
    $('[data-type="smart-search-input"]').val('');
    $('.smart-search-short-res').hide();
    $('.smart-search-result-short-link').hide();
    $('.smart-search-result-short').hide();
    $('.smart-search-no-results').hide();
    $('.smart-search-bottom-panel').hide();
    $('.smart-search-wait').hide();
})


/**
 *  общие функции
 */

function debounce(func, ms) {
    let timer = null;
    return function(args) {
        const onComplete = function() {
            func(args);
            timer = null;
        }
        if (timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(onComplete, ms);
    };
}

//поиск совпадений в объекте
function findPartial( a, s ) {
    let res = [];
    let numRes = [];
    let n = 0;
    for (key in a) {
        if( a[key].indexOf(s) >= 0 ) {
            let show = smartSearchObj[key]['show'];
            let article = smartSearchObj[key]['article'].trim();
            if(smartSearchObj[key]['show'] == 'Y') {
                res.push(key);
            } else if(s.trim() == article.trim() || s.trim() == a[key].trim()) {
                numRes.push(key);
            }
        }
    }
    return res.concat(numRes);
}

// основной механизм поиска
function smartSearchProducts(q,input) {
    return new Promise(function(resolve) {
        let smartSearchRes = [];
        q = q.toLowerCase();
        // если есть артикул с "," преобразуем в "."
        q = q.replace(/,/g, ".");
        input.attr('data-search',q);
        //console.log(`q - ${q}`);
        // пробуем искать как есть
        smartSearchRes = findPartial(smartSearchProdList, q);
        //если ничего не найдено разбиваем, пропускаем через спеллер
        if (smartSearchRes == 0) {
            let qArr = q.split(' ');
            let qArrNew = [];
            let runSpeller = [];
            for (let i = 0; i < qArr.length; i++) {
                runSpeller[i] = new Promise(function (resolve, reject) {
                    let url = "https://speller.yandex.net/services/spellservice.json/checkText?text=" + qArr[i];
                    $.ajax(url)
                        .done(function (data, statusText, xhr) {
                            //console.log(`speller qrty - ${data.length}`);
                            if (data.length > 0) {
                                resolve(data[0].s[0]);
                            } else {
                                resolve(qArr[i]);
                            }
                        })
                        .fail(function (data, statusText, xhr) {
                            resolve(qArr[i]);
                        });
                });
            }
            Promise.all(runSpeller).then(function (values) {
                let qStrNew = values.join(' ');
                //если после пропуска через спеллер есть изменения, ищем заново
                if (q != qStrNew) {
                    // сначала соединяем все слова и пробуем найти
                    smartSearchRes = findPartial(smartSearchProdList, qStrNew);
                    // если ничего не найдено пробуем искать по отдельным словам
                    if (smartSearchRes.length == 0) {
                        for (var i = 0; i < values.length; ++i) {
                            let smartSearchWordRes = findPartial(smartSearchProdList, values[i]);
                            if (smartSearchWordRes.length > 0) {
                                input.attr('data-search',values[i]);
                                smartSearchRes = smartSearchRes.concat(smartSearchWordRes);
                            }
                            // очистим от дубликатов
                            uniqueRes = smartSearchRes.filter(function(item, pos) {
                                return smartSearchRes.indexOf(item) == pos;
                            })
                            smartSearchRes = uniqueRes;
                        }
                    } else {
                        input.attr('data-search',qStrNew);
                    }
                }
                resolve(smartSearchRes);
            }).catch(function(err) {
                console.log(err)
            });
        } else {
            resolve(smartSearchRes);
        }
    })
};

// проверка загрузки основного объекта из ajax запроса
function checkObjLoading() {
    return new Promise(function(resolve,reject) {
        const checker = setInterval(function(){
            if(Object.keys(smartSearchObj).length > 0 || smartSearchObjErr) {
                if(smartSearchObjErr) {
                    reject();
                } else {
                    resolve();
                }
                clearInterval(checker);

            }
        },100);
    })
}

//передача статистики
function setStatistics(input) {
    // если больше 3 секунд не было ввода,
    // считаем, что пользователь закончил ввод
    // отправляем статистику
    console.log(input.attr('data-type'));
    clearTimeout(smartSearchTimer);
    smartSearchTimer = setTimeout(function () {
        let q = input.attr('data-search') && input.attr('data-search')!= '' ? input.attr('data-search') : input.val().trim(),
            qty = input.attr('data-type') == 'smart-search-input' ? $('.smart-search-result-qty span').html() : $('[data-type="search-resSP-qty"] span').html();
        if(q != '' && searchWord != q) {
            console.log(`Statistics: ${q} - ${qty}`);
            $.ajax({
                type: "POST",
                url: "/smart_search/ajax.php",
                data: 'q='+q+'&qty='+qty+'&type=set_stat',
                success: function(resp){
                    console.log(resp);
                }
            })
            searchWord = q;
        }
    }, 3000);
    return false;
}

/**
 *  функции поиска в хедере
 */

function smartSearchResClear() {
    $('.smart-search-result-short').hide();
    $('.smart-search-short-res').hide();
    $('.smart-search-no-results').hide();
    $('.smart-search-bottom-panel').show();
    $('.smart-search-wait').show();
}

function smartSearchResClearTotal() {
    $('.smart-search-result-short').hide();
    $('.smart-search-short-res').hide();
    $('.smart-search-no-results').hide();
    $('.smart-search-bottom-panel').hide();
    $('.smart-search-wait').hide();
    $('[data-type="smart-search-input"]').val('');
}

function smartSearchShortRes() {

    let q = $('[data-type="smart-search-input"]').val().trim();
    console.log(`q - ${q}`);
    if(q != ''){
        checkObjLoading(q).then(function(){

            let res = smartSearchProducts(q,$('[data-type="smart-search-input"]'))
                .then(function(result){
                    $('.smart-search-wait').hide();
                    //console.log("Fulfilled: " + result);
                    //console.log(result);
                    if(result.length > 0) {
                        let smartSearchHtml = getSmartSearchShortPreview(result);
                        $('[data-type="smart-search-items-wrap"]').html(smartSearchHtml);
                        $('.smart-search-short-res').show(function(){
                            if(typeof searchScroll === 'undefined') {
                                var searchScroll = $('[data-type="search-scroll"]').jScrollPane({
                                    showArrows: false,
                                    maintainPosition: false
                                }).data('jsp');
                            } else {
                                searchScroll.reinitialise();
                            }
                        });
                        $('.smart-search-result-short-link').show();
                        $('.smart-search-result-qty span').html(result.length);
                        $('.smart-search-result-short').css('display','flex');
                        $('.smart-search-no-results').hide();
                        $('[data-type="smart-search-show-all"]').each(function() {
                            $(this).attr('href','/search/?q='+q).show();
                        })
                    } else {
                        $('[data-type="smart-search-items-wrap"]').html('');
                        $('.smart-search-short-res').hide();
                        $('[data-type="smart-search-show-all"]').hide();
                        $('.smart-search-result-short-link').hide();
                        $('.smart-search-result-qty span').html('0');
                        $('.smart-search-result-short').css('display','flex');
                        $('.smart-search-no-results').html('Поиск не дал результатов').show();
                    }
                })
                .catch(function(err) {
                    console.log(err)
                });
        }, function() {
            $('.smart-search-wait').hide();
            $('.smart-search-no-results').html('Произошла ошибка. Пожалуйста, перезагрузите страницу и повторите поиск ещё раз').show();
        }).catch(function(err) {
            console.log(err)
        });
    } else {
        $('[data-type="smart-search-input"]').val('');
        $('[data-type="smart-search-items-wrap"]').html('');
        $('[data-type="smart-search-show-all"]').hide();
        $('.smart-search-short-res').hide();
        $('.smart-search-result-short-link').hide();
        $('.smart-search-result-short').hide();
        $('.smart-search-no-results').hide();
        $('.smart-search-bottom-panel').hide();
        $('.smart-search-wait').hide();
        return false;
    }
    return false;
}
var smartSearchShort = debounce(smartSearchShort, 1000);

// рендерим превьюшки для быстрого поиска
function getSmartSearchShortPreview(arr) {
    let stop = 11; // сколько товаров выводить в быстром поиске
    let res = '';
    let curr = $('[data-type="header-cart"]').attr('data-curr');
    let user = $('[data-type="personal-data"]').attr('data-user');
    for(let i = 0; i < arr.length && i <= stop; i++) {
        let item = smartSearchObj[arr[i]],
            cartClass = isInCart(item.id) ? ' active' : '',
            inavailableClass = !item.availableToSell ? ' inactive' : '',
            canBuy = item.availableToSell ? 'data-type="cart-add"' : '',
            favClass = isFav(item.id) ? ' active' : '';
        res += '<div class="smart-search-res-item" data-type="prod-prev" data-id="'+item.id+'" data-iscomp="'+item.iscomp+'">';
        res += '<div class="smart-searh-res-img">';
        if(!item.comingSoon) {
            res += '<a class="smart-serch-res-link" href="' + item.link + '"></a>';
        }
        res += '<img src="'+item.img+'" alt="'+item.name+'">';
        res += '</div>';
        res += '<div class="smart-search-res-info">';
        if(!item.comingSoon) {
            res += '<a class="smart-serch-res-link" href="' + item.link + '"></a>';
        }
        res += '<p class="smart-search-res-name">'+item.name+'</p>';
        if(item.price != '') {
            res += '<p class="smart-search-res-price">' + costFormat(item.price) + '</p>';
        }
        res += '</div>';
        res += '<div class="ss-icons-wrap">';
        res += '<i class="smart-search-res-fav icon-favorite'+favClass+'" data-type="favorite" data-user="'+user+'" title="Добавить в избранное"></i>';
        res += '<div class="smart-search-res-add'+cartClass+inavailableClass+'" '+canBuy+'><i class="icon-cart"></i></div>';
        res += '</div>';
        res += '<div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>';
        res += '<div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>';
        res += '</div>';
    }
    return res;
}

/**
 *  функции поиска на странице
 */

function smartSearchFullResClear() {
    $('[data-type="search-messSP"]').hide();
    $('[data-type="search-resSP-qty"]').hide();
    $('[data-type="search-resSP"]').hide();
    $('[data-type="search-resSP-prod"]').html('');
    $('[data-type="search-page"]').find('.icon-search').hide();
    $('[data-type="search-page"]').find('.search-wait').show();
}

function smartSearchFullRes() {
    let q = $('[data-type="searchSP"]').val().trim();
    let url = window.location.href;
    url = url.split('?');
    url = url[0] + '?q=' + q;
    window.history.replaceState("", "", url);
    console.log(`q - ${q}`);
    if(q != ''){
        checkObjLoading(q).then(function(){

            let res = smartSearchProducts(q,$('[data-type="searchSP"]')).then(
                function(result){
                    $('[data-type="search-page"]').find('.search-wait').hide();
                    $('[data-type="search-page"]').find('.icon-search').show();
                    //console.log("Fulfilled: " + result);
                    //console.log(result);
                    if(result.length > 0) {
                        let smartSearchHtml = getSmartSearchFullPreview(result);
                        $('[data-type="search-resSP-prod"]').html(smartSearchHtml);
                        $('[data-type="search-resSP"]').show();
                        $('[data-type="search-resSP-qty"] span').html(result.length);
                        $('[data-type="search-resSP-qty"]').show();
                        $('[data-type="search-messSP"]').hide();
                    } else {
                        $('[data-type="search-resSP-prod"]').html('');
                        $('[data-type="search-resSP"]').hide();
                        $('[data-type="search-resSP-qty"] span').html('0');
                        $('[data-type="search-resSP-qty"]').show();
                        $('[data-type="search-messSP"]').html('Поиск не дал результатов').show();
                    }
                }
            ).catch(function(err) {
                console.log(err)
            });
        }, function() {
            $('.search-wait').hide();
            $('[data-type="search-page"]').find('.icon-search').show();
            $('[data-type="search-messSP"]').html('Произошла ошибка. Пожалуйста, перезагрузите страницу и повторите поиск ещё раз').show();
        }).catch(function(err) {
            console.log(err)
        });
    } else {
        $('[data-type="search-page"]').find('.search-wait').hide();
        $('[data-type="search-page"]').find('.icon-search').show();
        $('[data-type="search-messSP"]').hide();
        $('[data-type="search-resSP-qty"]').hide();
        $('[data-type="search-resSP"]').hide();
        $('[data-type="search-page"]').find('.icon-search').show();
        $('[data-type="search-resSP-prod"]').html('');
        return false;
    }

    return false;
}
var smartSearchFull = debounce(smartSearchFullRes, 1000);

// рендерим превьюшки для страницы поиска
function getSmartSearchFullPreview(arr) {
    let res = '';
    for(let i = 0; i < arr.length; i++) {
        let item = smartSearchObj[arr[i]],
            curr = $('[data-type="header-cart"]').attr('data-curr'),
            favclass = favoriteArr.includes(item.id) ? ' active' : '',
            cartClass = isInCart(item.id) ? ' active' : '',
            inavailableClass = !item.availableToSell ? ' inactive' : '',
            canBuy = item.availableToSell ? 'data-type="cart-add"' : '',
            canBuyOneClick = item.availableToSell ? 'data-type="one-click"' : '';

        res += '<div class="prod-prev'+item.class+'" data-type="prod-prev" data-id="'+item.id+'" data-name="'+item.name+'"  data-code="'+item.code+'" data-price="'+item.price+'" data-curr="'+curr+'" data-cat="'+item.catId+'" data-cat-name="'+item.catName+'" data-iscomp="'+item.iscomp+'"'+item.maur+'>';
        if(!item.comingSoon) {
            res += '<a href="'+item.link+'"></a>';
        }
        res += '<div class="prod-prev-top">';
        res += '<div class="prod-prev-title">';
        let nameWithoutArticle = item.name.replace(item.article,'');
        let itemName = '<span class="prod-prev-name">'+nameWithoutArticle+'</span>';
        if(item.catId != 1587) {
            itemName += '<span class="prod-prev-article">';
            itemName += item.article;
            if(item.foil) {
                itemName += '<i class="icon-light"></i>';
            }
            itemName += '</span>';
        }
        res += itemName;
        if(item.new) {
            res += '<div class="new-prod">новинка</div>';
        }
        res += '</div>';
        res += '<div class="prod-prev-img">';
        res += '<img src="'+item.img+'" alt="'+item.name+'">';
        res += '</div>';
        let btns = '';
        if(!item.availableToSell && item.availableDate) {
            btns += '<div className="prod-prev-no">';
            btns += '<p>Товар недоступен для&nbsp;заказа</p>';
            btns += '<p>Будет доступен для&nbsp;покупки <br>после '+item.availableDate+'</p>';
            btns += '</div>';
        } else if(item.comingSoon) {
            btns += '<p class="coming-soon">Скоро в&nbsp;продаже</p>';
        } else if(!item.availableToSell) {
            btns += '<p class="no-sale">Товар недоступен для&nbsp;заказа</p>';
        }
        res += '<div class="prod-prev-btns">';
        res += '<div class="prod-prev-icons">';
        res += '<i class="icon-favorite'+favclass+'" data-type="favorite" data-user="'+$('[data-type="personal-data"]').attr('data-user')+'" title="Добавить в избранное"></i>';
        res += '<i class="icon-cart'+cartClass+inavailableClass+'" '+canBuy+' title="Добавить в корзину"></i>';
        res += '</div>';
        res += '<div class="prod-prev-one-click'+inavailableClass+'" '+canBuyOneClick+'>купить в&nbsp;1&nbsp;клик</div>';
        res += '<div class="prod-prev-cart-mess prod-prev-mess">Товар добавлен в&nbsp;корзину</div>';
        res += '<div class="prod-prev-favorite-mess prod-prev-mess">Товар добавлен в&nbsp;избранное</div>';
        res += '</div>';
        res += '</div>';
        res += '<div class="prod-prev-bottom">';
        if(item.price != '') {
            res += '<div class="prod-prev-price">'+costFormat(item.price)+'</div>';
        }
        if(btns != '') {
            if(item.price != '') {
                res += '<div class="no-avail-wrap">';
            }
            res += btns;
            if(item.price != '') {
                res += '</div>';
            }
        }
        res += '<div class="prod-prev-params">';
        for (let key in item.params) {
            res += '<div class="prod-prev-param">';
            res += '<span>'+key+'</span>';
            res += '<span>'+item.params[key]+'</span>';
            res += '</div>';
        }

        res += '</div>';
        res += '<div class="prod-prev-mob">';
        res += itemName;
        if(item.availableToSell) {
            res += '<div class="prod-prev-mob-btns">';
            res += '<div class="prod-prev-cart'+cartClass+inavailableClass+'" '+canBuy+'>';
            if(cartClass != '') {
                res += 'В корзине';
            } else {
                res += 'В корзину';
            }
            res += '</div>';
            res += '<i class="icon-favorite'+favclass+'" data-type="favorite" data-user="'+$('[data-type="personal-data"]').attr('data-user')+'" title="Добавить в избранное"></i>';
            res += '</div>';
        }
        res += '</div>';
        res += '</div>';
        res += '</div>';
    }
    return res;
}