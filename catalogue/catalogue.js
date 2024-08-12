$(document).ready(function() {
    lightbox.option({
        'albumLabel': "%1 из %2",
    })
    if($('[data-type="pag"]').length > 0) {
        pagination();
        ePagination();
    }

    createFilters();

    /**
     * показать все описание
     */
    $('.cat-wrap').on('click','[data-type="see-more-desc-btn"]',function () {
        $(this).hide();
        $('.cat-wrap').find('[data-type="see-more-desc"]').slideDown();
    })

    /**
     * шарим в соцсети
     */
    $('[data-type="share"]').on('click',function(){
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
            $('[data-type="share-wrap"]').fadeOut();
        } else {
            $(this).addClass('active');
            $('[data-type="share-wrap"]').fadeIn();
        }
    })
    $(document).mouseup(function (e) {
        var container = $('[data-type="share-wrap"]');
        var target = e.target;
        if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(target).is('[data-type="share"]')){
            $('[data-type="share-wrap"]').fadeOut();
            $('[data-type="share"]').removeClass('active');
        }
    });

    /**
     * добавить в корзину на странице товара
     */
    $('[data-type="prod-page-add"]').on('click',function(){
        console.log('ololo');
        let productId = $('[data-type="prod-info"]').attr('data-id'),
            prodQty = parseInt($('[data-type="prod-page-qty"]').val()),
            iscomp = $('[data-type="prod-info"]').attr('data-iscomp'),
            btn = $(this),
            link = '<a href="/cart/" class="prod-buy-link">перейти в корзину <span>'+prodQty+'</span></a>',
            wrap = $(this).closest('[data-type="prod-info"]');
        checkCompAvailability(productId,iscomp).then(function(data) {
            if(data == true) {
                if($('[data-type="prod-info"]').attr('data-maur-spec') == 1) {
                    $.get('/ajax/mauritania_moulded_check.php?id='+productId+'&qty='+prodQty, function(data) {
                        data = JSON.parse(data);
                        if(data['err'] == 0) {
                            addProdCart(productId,prodQty,iscomp);
                            btn.closest('[data-type="prod-info"]').find('.prod-qty').attr('data-inbasket','1');
                            btn.closest('.prod-buy').after(link);
                            btn.closest('.prod-buy').remove();
                            sendAddAnalytics(wrap,'add',prodQty);
                            if (typeof yaCounter22165486 !== 'undefined') {
                                yaCounter22165486.reachGoal('add_basket');
                            }
                        } else {
                            $('[data-type="maur-pop-content"]').html(data['mess']);
                            $('[data-type="maur-pop"]').fadeIn(clouseMaurPop());
                            $('[data-type="overlay"]').fadeIn();
                        }
                    })
                } else {
                    addProdCart(productId,prodQty,iscomp);
                    btn.closest('[data-type="prod-info"]').find('.prod-qty').attr('data-inbasket','1');
                    btn.closest('.prod-buy').after(link);
                    btn.closest('.prod-buy').remove();
                    sendAddAnalytics(wrap,'add',prodQty);
                    if (typeof yaCounter22165486 !== 'undefined') {
                        yaCounter22165486.reachGoal('add_basket');
                        yaCounter22165486.reachGoal('311094860');
                        console.log('add-basket');
                    }

                }
            }
        })
    })

    /**
     * изменить количество товара
     */
    $('[data-type="prod-page-plus"]').on('click',function() {
        console.log('plus');
        let qty = parseInt($('[data-type="prod-page-qty"]').val())+1,
            productId = $('[data-type="prod-info"]').attr('data-id'),
            resp = checkMauritaniaSpecial(productId,1),
            wrap = $(this).closest('[data-type="prod-info"]');
        if($(this).closest('.prod-qty').attr('data-inbasket') == '1') {
            if(resp) {
                addProdCart(productId,1,0);
                $('[data-type="prod-page-qty"]').val(qty);
                wrap.attr('data-qty',qty);
                sendAddAnalytics(wrap,'add',1);
            }
        } else {
            $('[data-type="prod-page-qty"]').val(qty);
            wrap.attr('data-qty',qty);
        }

    })
    $('[data-type="prod-page-minus"]').on('click',function() {
        let qty = parseInt($('[data-type="prod-page-qty"]').val())-1,
            nmbr = parseInt($('[data-type="prod-page-qty"]').val()),
            productId = $('[data-type="prod-info"]').attr('data-id'),
            wrap = $(this).closest('[data-type="prod-info"]');
        if(qty <= 0) {
            qty = 1;
        } else {
            if($(this).closest('.prod-qty').attr('data-inbasket') == '1') {
                let qtyDiff = qty-nmbr,
                    resp = checkMauritaniaSpecial(productId,qtyDiff);
                if(resp) {
                    addProdCart(productId,-1,0);
                    $('[data-type="prod-page-qty"]').val(qty);
                    wrap.attr('data-qty',qty);
                    sendAddAnalytics(wrap,'remove', -1);
                }
            } else {
                $('[data-type="prod-page-qty"]').val(qty);
                wrap.attr('data-qty',qty);
            }
        }

    })
    $('[data-type="prod-page-qty"]').on('change',function(){
        let qty = parseInt($('[data-type="prod-page-qty"]').val()),
            productId = $('[data-type="prod-info"]').attr('data-id'),
            wrap = $(this).closest('[data-type="prod-info"]'),
            qtyTrue = 0;
        if(qty <= 0) {
            qtyTrue = 1;
        } else {
            qtyTrue = qty;
        }
        if($(this).closest('.prod-qty').attr('data-inbasket') == '1') {
            let qtyDiff = qtyTrue - parseInt(wrap.attr('data-qty')),
                resp = checkMauritaniaSpecial(productId,qtyDiff);
            if(resp) {
                if(qtyDiff > 0) {
                    sendAddAnalytics(wrap,'add',qtyDiff);
                } else if(qtyDiff < 0) {
                    sendAddAnalytics(wrap,'remove',-qtyDiff);
                }
                addProdCart(productId,qtyDiff,0);
                $('[data-type="prod-page-qty"]').val(qtyTrue);
                wrap.attr('data-qty',qtyTrue);
            }
        } else {
            $('[data-type="prod-page-qty"]').val(qtyTrue);
            wrap.attr('data-qty',qtyTrue);
        }
    })
    /**
     * сравнить
     */
    $('[data-type="compare"]').on('click',function() {
        if(!$(this).hasClass('active')) {
            let productId = $('[data-type="prod-info"]').attr('data-id'),
                user = $('[data-type="personal-data"]').attr('data-id'),
                mess = 'В сравнении<a href="/comparison/" title="перейти"></a>';
            $(this).addClass('active');
            $(this).html(mess);

            /*if(user && $(this).attr('data-user') == 'user') {
            $.ajax({
                type: "POST",
                url: "/personal/ajax.php",
                data: 'product_id='+productId+'&type=compare&user_id='+user,
            });
            } else {*/
                let compare = $.cookie('favorite');
                if (compare == undefined) {
                    compare = [];
                } else {
                    compare = JSON.parse(compare);
                }
                if (compare.length > 0) {
                    let index = $.inArray(productId,compare);
                    if( index == -1) {
                        compare.push(productId);
                    }
                } else {
                    compare.push(productId);
                }
                let domain = location.hostname;
                $.cookie('compare', JSON.stringify(compare), {
                    domain: domain,
                    path: '/'
                });
            //}
        }
        return false;
    })
    /**
     * добавить в корзину образец на странице товара
     */
    $('[data-type="buy-sample-page"]').on('click',function(){
        let productId = 's'+$('[data-type="prod-info"]').attr('data-id'),
            prodQty = 1,
            iscomp = $('[data-type="prod-info"]').attr('data-iscomp'),
            list = basketList(),
            exist = false,
            i = 0;
        if(list.length > 0) {
            while (i < list.length) {
                if (list[i].id == productId) {
                    exist = true;
                    break;
                }
                i++;
            }
        }
        if(exist) {
            let mess = "<p>Вы можете купить <br>только один образец данного изделия.</p><p>Для&nbsp;уточнения вопроса обратитесь к&nbsp;менеджеру.</p>";
            $('[data-type="maur-pop-content"]').html(mess);
            $('[data-type="maur-pop"]').fadeIn(clouseMaurPop());
            $('[data-type="overlay"]').fadeIn();
            return;
        } else {
            $(this).addClass('active');
            addProdCart(productId,prodQty,iscomp);
            if (typeof yaCounter22165486 !== 'undefined') {
                yaCounter22165486.reachGoal('buy_sample');
            }
        }
    })


    //activeCategoryUp();

})// document.ready

function activeCategoryUp() {
    let list = $('.cat-filt-item-cont.category'),
        topUl = list.find('ul').first(),
        bottomUl = list.find('[data-type="cat-collapse-wrap"]');
    if(list.find('.active').length > 0) {
        let active = list.find('.active');
        list.find('.active').remove();
        topUl.prepend(active);
    }
    topUl.find('li').each(function() {
       if(!$(this).hasClass('active') && $(this).attr('data-type') != 'first-cat') {
           let li = $(this);
           $(this).remove();
           bottomUl.prepend(li);
       }
    })
}

/**
 * развернуть/свернуть категории
 */
$('.cat-filters').on('click','[data-type="cat-show"]',function () {
    $('[data-type="cat-collapse-wrap"]').show();
    $(this).html('Свернуть <i class="icon-angle-up"></i>')
    $(this).attr('data-type','cat-hide');
})
$('.cat-filters').on('click','[data-type="cat-hide"]',function () {
    $('[data-type="cat-collapse-wrap"]').hide();
    $(this).html('Показать все <i class="icon-angle-up"></i>')
    $(this).attr('data-type','cat-show');
})
/**
 * категории в мобилке
 */
$('[data-type="show-category-mob"]').on('click',function() {
    let wrap = $('.cat-filters');
    wrap.find('[data-type="filt-item"]').each(function() {
        if($(this).hasClass('mob-filt-wrap')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    })
    wrap.find('.cat-filt-item').addClass('active');
    wrap.addClass('active');
    $('.cat-filt-item.mob-filt-wrap').show();
    $('.cat-filters-wrap').hide();
    $('.cat-sort-mob').hide();
})
/**
 * фильтры
 */
$('[data-type="show-filters-mob"]').on('click',function() {
    let wrap = $('.cat-filters');
    wrap.find('[data-type="filt-item"]').each(function() {
        if($(this).hasClass('mob-filt-wrap')) {
            $(this).removeClass('active');
        } else {
            $(this).addClass('active');
        }
    })
    wrap.addClass('active');
    $('.cat-filt-item.mob-filt-wrap').hide();
    $('.cat-filters-wrap').show();
    $('.cat-sort-mob').show();
})
$('[data-type="close-filt-mob"]').on('click',function() {
    let wrap = $('.cat-filters');
    wrap.removeClass('active');
    $('.cat-filt-item.mob-filt-wrap').show();
    $('.cat-filters-wrap').show();
    $('.cat-sort-mob').show();
})
$('.wrapper').on('click','*[data-type="filt-title"]',function() {
    let wrap = $(this).closest('[data-type="filt-item"]');
    wrap.toggleClass('active');
})
$('.cat-filters').on('click','[data-type="filter-class"]',function() {
    var parent = $(this).closest('ul');
    $('.active', parent).not(this).removeClass('active');
    $(this).toggleClass('active');
    changeFiltInAddr();
    getFilteredItems();
})
$('.cat-filters').on('click','[data-type="filter-style"]',function() {
    var parent = $(this).closest('ul');
    $('.active', parent).not(this).removeClass('active');
    $(this).toggleClass('active');
    changeFiltInAddr();
    getFilteredItems();
})
$('.cat-wrap').on('click','[data-type="reset-filt"]',function() {
    let list = $('[data-type="items-list"]'),
        wait = '<div class="wait" data-type="wait"><img src="/img/preloader.gif"></div>',
        sections = list.attr('data-id'),
        req = '/ajax/catalogue.php?page=1&sections='+sections+'&type=newFilters',
        all = false;
    list.html(wait);
    $('.pagination').hide();
    $('[data-type="filter-class"]').each(function(){
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
    })
    $('[data-type="filter-style"]').each(function(){
        if($(this).hasClass('active')) {
            $(this).removeClass('active');
        }
    })
    $('.cat-filters').find('[data-name="category-filter"]').each(function(){
        $(this)[0].noUiSlider.set([$(this).attr('data-fmin'),$(this).attr('data-fmax')]);
    })
    let url = window.location.href.split('?');
    url = url[0];
    if($('[data-type="pag"]').attr('data-current') == 'all') {
        url += '?page=all';
        req += '&all=all';
        let all = true;
    }
    window.history.replaceState("", "", url);
    $.get(req, function(data){
        data = $.parseJSON(data);
        list.html(data.items);
        if(data.qty > 12) {
            $('[data-type="pag"]').pagination('updateItems', data.qty);
            if(!all) {
                $('[data-type="pag"]').pagination('drawPage', 1);
                $('.pagination').show();
            }
        }
    });
    let wrap = $('.cat-filters');
    wrap.removeClass('active');
    if ($(window).outerWidth() <= 1180) {
        $('.cat-filt-item.mob-filt-wrap').show();
        $('.cat-filters-wrap').show();
        $('.cat-sort-mob').show();
    }
})
$('.cat-wrap').on('click','[data-type="apply-filt"]',function() {
    let wrap = $('.cat-filters');
    wrap.removeClass('active');
    $('.cat-filt-item.mob-filt-wrap').show();
    $('.cat-filters-wrap').show();
    $('.cat-sort-mob').show();
})
function getFilteredItems() {
    let list = $('[data-type="items-list"]'),
        wait = '<div class="wait" data-type="wait"><img src="/img/preloader.gif"></div>',
        filters = getFilters(),
        sections = list.attr('data-id'),
        req = '/ajax/catalogue.php?page=1&'+filters+'&sections='+sections+'&type=newFilters';
    list.html(wait);
    $('.pagination').hide();
    $.get(req, function(data){
        data = $.parseJSON(data);
        list.html(data.items);
        if(data.qty > 12) {
            $('[data-type="pag"]').pagination('updateItems', data.qty);
            $('[data-type="pag"]').pagination('drawPage', 1);
            $('.pagination').show();
        }
    });
    return false;
}
function createFilters(){
    $('.cat-filters').find('[data-name="category-filter"]').each(function(){
        noUiSlider.create($(this)[0], {
            start: [ parseInt($(this).attr('data-from')), parseInt($(this).attr('data-to')) ],
            connect: true,
            step: 1,
            range: {
                'min': parseInt($(this).attr('data-fmin')),
                'max': parseInt($(this).attr('data-fmax'))
            },
        });
    });
    $('.cat-filters').find('[data-name="category-filter"]').each(function(){
        let filter = $(this);
        $(this)[0].noUiSlider.on('update', function (values, handle) {
            let tm = $(this)[0].target;
            if (handle) {
                tm.setAttribute('data-to',values[handle]);
                filter.closest('[data-type="filt-cont"]').find('[data-type="to"]').html(parseFloat(values[handle]).toFixed(0));
            } else {
                tm.setAttribute('data-from',values[handle]);
                filter.closest('[data-type="filt-cont"]').find('[data-type="from"]').html(parseFloat(values[handle]).toFixed(0));
            }
        });
        $(this)[0].noUiSlider.on('end',function() {
            changeFiltInAddr();
            getFilteredItems();
            return false;
        });
    });
}
function changeFiltInAddr() {
    let tmp = [],
        cls = '',
        stl = '',
        use = '',
        url='',
        addr = window.location.href.split('?');
    $('[data-type="filter-class"]').each(function(){
        if($(this).hasClass('active')) {
            cls += $(this).find('a').attr('data-val')+',';
        }
    })
    cls = cls.slice(0, -1);
    if(cls!=""){
        url = 'classes='+cls+'&';
    }
    $('[data-type="filter-style"]').each(function(){
        if($(this).hasClass('active')) {
            stl += $(this).find('a').attr('data-val')+',';
        }
    })
    stl = stl.slice(0, -1);
    if(stl!=""){
        url += 'styles='+stl+'&';
    }
    $('[data-type="filter-use"]').each(function(){
        if($(this).hasClass('active')) {
            use += $(this).find('a').attr('data-val')+',';
        }
    })
    use = use.slice(0, -1);
    if(use!=""){
        url += 'use='+use+'&';
    }
    $('[data-name="category-filter"]').each(function(){
        let from = $(this).attr('data-from'),
            to = $(this).attr('data-to'),
            min = $(this).attr('data-fmin'),
            max = $(this).attr('data-fmax'),
            id =  $(this).attr('data-type');
        if(parseFloat(from) != parseFloat(min) || parseFloat(to) != parseFloat(max)) {
            tmp.push('filter['+id+'][from]='+from);
            tmp.push('filter['+id+'][to]='+to);
        }
    })
    url += tmp.join('&');
    if(url[url.length - 1] == '&') url = url.slice(0,-1);
    url = addr[0]+'?'+url;
    //console.log(url);
    window.history.replaceState("", "", url);
    return false;
}
/**
 * сортировка
 */
let sort_obj = {};
sort_obj['main_param'] = $('[data-type="e-sort"]').attr('data-main-param') == "" ? 6 : $('[data-type="e-sort"]').attr('data-main-param');
sort_obj['prop_param'] = '';
sort_obj['id_sec'] = $('[data-val="catalogue"]').attr('data-id');
$('.cat-wrap').on('click','[data-type="sort-param"]',function () {
    let id_sort = $(this).attr('data-val'),
        sort = $(this).attr('data-sort');
    if (id_sort == 1 || id_sort == 2 || id_sort == 3 || id_sort == 4 || id_sort == 5 || id_sort == 6) {
        sort_obj['main_param'] = id_sort;
        sort_obj['prop_param'] = '';
        if(id_sort == 1) $(this).closest('.cat-sort-item').find('.cat-sort-item-title').attr('data-val','2');
        if(id_sort == 2) $(this).closest('.cat-sort-item').find('.cat-sort-item-title').attr('data-val','1');
        if(id_sort == 3) $(this).closest('.cat-sort-item').find('.cat-sort-item-title').attr('data-val','4');
        if(id_sort == 4) $(this).closest('.cat-sort-item').find('.cat-sort-item-title').attr('data-val','3');
    }
    else {
        let sort_direct = $(this).attr('data-sort'),
            prop = {
            'val': id_sort,
            'sort': sort_direct
            }
        sort_obj['prop_param'] = prop;
        if(sort_direct == 'asc') $(this).closest('.cat-sort-item').find('.cat-sort-item-title').attr('data-sort','desc');
        if(sort_direct == 'desc') $(this).closest('.cat-sort-item').find('.cat-sort-item-title').attr('data-sort','asc');
    }
    let sort_params = JSON.stringify(sort_obj);
    console.log('sort_params');
    console.log(sort_params);
    $.cookie('sort_params', sort_params, {domain: domain, path: '/'});
    $('[data-type="sort-param"]').each(function() {
        if($(this).attr('data-val') == id_sort && $(this).attr('data-sort') == sort) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
        }
    })
    $('.cat-sort-item').each(function() {
        if($(this).find('.active').length > 0 ) {
            $(this).addClass('active');
        } else {
            $(this).removeClass('active');
            if($(this).find('.cat-sort-item-title').attr('data-sort') == 'desc') {
                $(this).find('.cat-sort-item-title').attr('data-sort','asc');
            }
            if($(this).find('.cat-sort-item-title').attr('data-val') == '2') {
                $(this).find('.cat-sort-item-title').attr('data-val','1');
            }
            if($(this).find('.cat-sort-item-title').attr('data-val') == '4') {
                $(this).find('.cat-sort-item-title').attr('data-val','3');
            }
        }
    })
    let newPage = 1,
        wrap = $('[data-type="items-list"]'),
        filters = getFilters(),
        sections = wrap.attr('data-id'),
        req = '/ajax/catalogue.php?page='+newPage+'&'+filters+'&sections='+sections+'&sorting=1';
    if($('[data-type="pag"]').attr('data-current') == 'all') {
        req += '&all=all';
        newPage = 'all';
    }
    wrap.addClass('cat-items-loading');
    $.get(req, function(data){
        let elems = $.parseJSON(data);
        wrap.html(elems.items);
        wrap.removeClass('cat-items-loading');
        $('html,body').animate({scrollTop: 0}, 500);
        changePageInAddr(newPage);
        $('[data-type="pag"]').pagination('drawPage', 1);
        console.log(elems.sorting);
    });
    if($('.cat-filters').hasClass('active')) {
        $('.cat-filters').removeClass('active');
        $('.cat-filt-item.mob-filt-wrap').show();
        $('.cat-filters-wrap').show();
        $('.cat-sort-mob').show();
    }
})


$('*[data-type="show-prev"]').on('click',function() {
    let val = $(this).attr("data-val");
    $('.mob-preview-type').find('.active').removeClass('active');
    if(val == 1) {
        $('[data-val="catalogue"]').addClass('cat-items-big');
    } else {
        $('[data-val="catalogue"]').removeClass('cat-items-big');
    }
    $(this).addClass('active');
})

/**
 * категория
 */
$('.category').on('click','li',function(e) {
    e.preventDefault();
    let li = $(this),
        href = $(this).find('a').attr('href'),
        val = getLastSection(href),
        path = window.location.pathname,
        lastSection = getLastSection(path),
        firstSection = getFirstSection(path),
        newUrl = '';

    // мультик
    /*
    lastSection = lastSection.split('_');

    if(lastSection.indexOf(val) != -1) {
        if(lastSection.length > 1) {
            lastSection.forEach(function(item, index, array){
                if(item == val) {
                    array.splice(index,1);
                }
            })
            li.removeClass('active');
        } else {
            return false;
        }
    } else {
        lastSection.push(val);
        li.addClass('active');
    }
    newUrl = "/"+lastSection.join('_')+"/";*/
    $('[data-type="last-sec-desc"]').remove();

    if (firstSection == 'composite') {
        newUrl = "/" + firstSection + "/" + val + "/";
    }
    else {
        newUrl = "/" + val + "/";
    }
    $('.category').find('li').each(function(){
        if($(this).hasClass('active')) $(this).removeClass('active');
    });
    if(!li.hasClass('active'))  li.addClass('active');

    //activeCategoryUp();


    window.history.pushState(null, null, newUrl);
    let wait = '<div class="wait" data-type="wait"><img src="/img/preloader.gif"></div>',
        sort = $('.cat-sort-items'),
        list = $('[data-type="items-list"]'),
        pag = $('[data-type="pag"]'),
        wrap = $('.cat-products'),
        filters = $('.cat-filters-wrap').find('[data-type="filt-cont"]');
    filters.html(wait);
    $('.pagination').hide();

    if(window.innerWidth > 1180) {
        $('.cat-sort').before(wait);
        $('.cat-sort').hide();
        list.hide();
    } else {
        $('.cat-sort-mob').find('[data-type="e-sort"]').attr('data-main-param',1).html(wait);
        list.html(wait);
    }
    let cat = [];
    $('.category').find('li').each(function(){
        if($(this).hasClass('active')) cat.push($(this).find('a').attr('data-id'));
    })
    let sections = cat.join(','),
        req = '/ajax/catalogue.php?page=1&sections='+sections+'&type=rebuild';
    if($('.cat-filters').hasClass('active')) {
        $('.cat-filters').removeClass('active');
        $('.cat-filt-item.mob-filt-wrap').show();
        $('.cat-filters-wrap').show();
        $('.cat-sort-mob').show();
    }
    $.get(req, function(data){
        data = $.parseJSON(data);
        let activeF = false;
        if($('.cat-filters-wrap').hasClass('active')) activeF = true;
        $('.cat-filters-wrap').remove();
        $('.cat-sort-mob').before(data.filters);
        if(activeF) $('.cat-filters').find('.cat-filters-wrap').addClass('active');
        createFilters();
        list.html(data.items);
        list.attr('data-id',sections);
        if(data.desc != '') {
            let desc = '<div class="last-sec-desc" data-type="last-sec-desc">';
            desc += data.desc;
            desc += '</div>';
            $('.cat-products').append(desc);
        }
        if(data.breadcrumbs) {
            $('.breadcrumbs').replaceWith(data.breadcrumbs);
        }
        if(window.innerWidth > 1180) {
            wrap.find('.wait').remove();
            sort.html(data.sort);
            $('.cat-sort').show();
            list.show();
        } else {
            $('.cat-sort-mob').find('[data-type="e-sort"]').attr('data-main-param',1).html(data.sort);
        }
        if(data.qty > 12) {
            pag.pagination('updateItems', data.qty);
            pag.pagination('drawPage', 1);
            $('.pagination').show();
        } else {
            $('.pagination').hide();
        }

        //постранично скрыть
        $('.show-per-page').removeClass('active');
        $('.pag-wrap').show();
    });
    return false;
})
function getLastSection(path) {
    let lastSection = '';
    path = path.split('/');
    path = path.filter(function(el){
        return (el != null && el != "" || el === 0);
    })
    lastSection = path[path.length - 1];
    return lastSection;
}
function getFirstSection(path) {
    let firstSection = '';
    path = path.split('/');
    path = path.filter(function(el){
        return (el != null && el != "" || el === 0);
    })
    firstSection = path[0];
    return firstSection;
}


