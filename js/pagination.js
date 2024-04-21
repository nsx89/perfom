/**
 * based on simplePagination.js
 */
function pagination(){
    let items = $('[data-type="pag"]').attr('data-items'),
        onpage = $('[data-type="pag"]').attr('data-onpage'),
        current = $('[data-type="pag"]').attr('data-current');
    $('[data-type="pag"]').pagination({
        items: items,
        itemsOnPage: onpage,
        displayedPages: 4,
        edges: 1,
        prevText: '',
        nextText: 'Далее',
        currentPage: current,
        ellipsePageSet: false,
    });
}

function ePagination(init=false) {
    let newPage = $('[data-type="pag"]').pagination('getCurrentPage'),
        wrap = $('[data-type="items-list"]'),
        type = wrap.attr('data-val'),
        req = '',
        top = 0,
        all = '';
    if($('[data-type="pag"]').attr('data-current') == 'all') newPage = 'all';
    if(type == 'catalogue') {
        let filters = getFilters(),
            sections = wrap.attr('data-id');
        req = '/ajax/catalogue.php?page='+newPage+'&'+filters+'&sections='+sections;
        if(newPage == 'all') req += '&all=all';
    }
    if(type == 'blog') {
        if($('[data-type="pag"]').attr('data-current') == 'all') {
            all = '&all=true';
            newPage = 'all';
        }
        let sections = wrap.attr('data-id'),
            city = wrap.attr('data-city'),
            category = '';
        $('[data-type="news-tab"]').each(function(){
            if($(this).hasClass('active')){
                category = $(this).attr('data-val');
            }
        })
        if(category == 'all') {
            category = wrap.attr('data-cat');
        }
        req = '/ajax/new_page_news.php?page='+newPage+'&category='+category+'&city='+city+all;
        top = $('header').outerHeight() + $('[data-type="main-slider"]').outerHeight() ;
    }
    if(type == 'fav') {
        if($('[data-type="pag"]').attr('data-current') == 'all') {
            all = '&all=true';
        }
        let userId = '';
        if($('[data-type="personal-data"]').attr('data-id') != 0) userId = $('[data-type="personal-data"]').attr('data-id');
        req = '/personal/ajax.php?page='+newPage+'&type=next_wish&user_id='+userId+all;
    }
    if(type == 'gallery') {
        let filter = getFilters();
        if(filter == '') filter = 'filter=1';
        if($('[data-type="pag"]').attr('data-current') == 'all') {
            all = '&all=true';
            newPage = 'all';
        }
        req = '/ajax/new_page_gallery.php?page='+newPage+'&'+filter+all;
        top = $('header').outerHeight() + $('[data-type="main-slider"]').outerHeight() ;
    }
    $.get(req, function(data){
        let elems = $.parseJSON(data);
        if(type == 'catalogue') {
            wrap.html(elems.items);
            $('[data-type="pag"]').pagination('updateItems', parseInt(elems.qty));
            if(elems.qty <= 12) {
                $('.pagination').hide();
            } else if($('[data-type="pag"]').attr('data-current') == 'all') {
                $('.pagination').show();
                $('.pag-wrap').hide();
                $('.show-per-page').addClass('active');
            } else {
                $('.pagination').show();
                $('.pag-wrap').show();
                $('.show-per-page').removeClass('active');
            }
        } else {
            wrap.html(elems);
        }        
        if(!init) $('html,body').animate({scrollTop: top}, 500);
        changePageInAddr(newPage);
        return false;
    });
    return false;
}

function getFilters() {
    let url = window.location.href.split('?'),
        filters = '';
    if(url.length > 1) {
        url = url[1].split('&');
        url.forEach(function(item, i, arr) {
            let itemArr = item.split('=');
            if(itemArr[0] != 'page' && itemArr[0] != '') filters += '&' + item;
        })
    }
    filters = filters.slice(1);
    return filters;
}

function changePageInAddr(page) {
    let filters = getFilters(),
        url = window.location.href,
        addr = '',
        hash = '';
    url = url.split('#');
    if(url[1]) hash = '#' + url[1];
    url = url[0].split('?');
    url = url[0].split('/');
    url = url.filter(function (el) {
        return el != '';
    });
    url.splice(0,2);
    url.forEach(function(item) {
        addr += item + '/';
    })
    if(filters != '') {
        filters = "?"+filters;
        if(page > 1 || page == 'all') filters += '&page='+page;
    } else {
        if(page > 1 || page == 'all') filters = '?page='+page;
    }
    addr = '/'+addr+filters;
    addr += hash;
    window.history.replaceState("", "", addr);
}

$('[data-type="show-all"]').on('click', function() {
    $('.show-wait').show();
    let newPage = 1,
        wrap = $('[data-type="items-list"]'),
        type = wrap.attr('data-val'),
        req = '',
        top = 0;
    if(type == 'catalogue') {
        let url = window.location.href.split('?'),
            filters = '',
            sections = wrap.attr('data-id');
        if(url.length>1) {
            filters = url[1];
        }
        req = '/ajax/catalogue.php?page='+newPage+'&'+filters+'&sections='+sections+'&all=all';
    }
    if(type == 'blog') {
        let sections = wrap.attr('data-id'),
            city = wrap.attr('data-city'),
            category = '';
        $('[data-type="news-tab"]').each(function(){
            if($(this).hasClass('active')){
                category = $(this).attr('data-val');
            }
        })
        if(category == 'all') {
            category = wrap.attr('data-cat');
        }
        req = '/ajax/new_page_news.php?page='+newPage+'&category='+category+'&city='+city+'&all=true';
        top = $('header').outerHeight() + $('[data-type="main-slider"]').outerHeight() ;
    }
    if(type == 'fav') {
        let userId = '';
        if($('[data-type="personal-data"]').attr('data-id') != 0) userId = $('[data-type="personal-data"]').attr('data-id');
        req = '/personal/ajax.php?page='+newPage+'&type=next_wish&user_id='+userId+'&all=y';
    }
    if(type == 'gallery') {
        let filter = getFilters();
        if(filter == '') filter = 'filter=1';
        req = '/ajax/new_page_gallery.php?page='+newPage+'&'+filter+'&all=true';
        top = $('header').outerHeight() + $('[data-type="main-slider"]').outerHeight() ;
    }
    $.get(req, function(data){
        $('html,body').animate({scrollTop: top}, 500);
        let elems = $.parseJSON(data);
        if(type == 'catalogue') {
            wrap.html(elems.items);
        } else {
            wrap.html(elems);
        }
        $('.show-wait').hide();
        $('.pag-wrap').hide();
        $('.show-per-page').addClass('active');
        changePageInAddr('all')
        $('[data-type="pag"]').attr('data-current','all');
    });
    return false;
})

/**
 * показать постранично
 */
$('[data-type="per-page"]').on('click',function() {
    $('[data-type="pag"]').attr('data-current',1);
    $('[data-type="pag"]').pagination('selectPage',1);
    $('.pag-wrap').show();
    $('.show-per-page').removeClass('active');
})
