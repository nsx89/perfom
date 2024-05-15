$(document).ready(function() {

    if($('[data-type="pag"]').length > 0) {
        pagination();
        ePagination(true);
    }

    //переключение тега
    $('[data-type="g-filter"]').on('click',function() {
        let val = $(this).attr('data-val');
        let qty = 0;
        if($(this).hasClass('active')) {
            $('*[data-val="'+val+'"]').removeClass('active');
        } else {
            if(val == '1') {
                $('[data-type="g-filter"]').each(function() {
                    if($(this).attr('data-val') != 1) {
                        $(this).removeClass('active');
                    }
                })
            } else {
                $('*[data-val="1"]').removeClass('active');
            }
            $('*[data-val="'+val+'"]').addClass('active');
        }
        let filter = '',
            filterArr = [];
        $('[data-type="g-filter"]').each(function() {
            if($(this).hasClass('active') && $(this).attr('data-val') != '1' && !filterArr.includes($(this).attr('data-val'))) {
                filterArr.push($(this).attr('data-val'));
                filter += $(this).attr('data-val') + ',';
                qty += parseInt($(this).attr('data-count'));
            }
        })
        if($('[data-val="1"]').hasClass('active')) qty = $('[data-val="1"]').attr('data-count');
        let url = window.location.href;
        url = url.split('?');
        url = url[0];
        if(filter != '') {
            filter = filter.slice(0, -1);
            url += '?filter='+filter;
        }
        window.history.replaceState("", "", url);
        $('[data-type="pag"]').pagination('updateItems', parseInt(qty));
        $('[data-type="pag"]').pagination('drawPage', 1);
        $('[data-type="pag"]').attr('data-current',1);
        $('[data-type="pag"]').attr('data-all',qty);
        if(qty <= 8) {
            $('.pagination').hide();
        } else {
            $('.pagination').show();
            $('.pag-wrap').show();
            $('.show-per-page').removeClass('active');
        }
        ePagination();
    })
    /**
     * шарим в соцсети
     */
    $('[data-type="share"]').on('click',function(){
        $(this).addClass('active');
        $('[data-type="share-wrap"]').fadeIn();
    })
    $(document).mouseup(function (e) {
        var container = $('[data-type="share-wrap"]');
        if (container.css('display')!='none' && container.has(e.target).length === 0){
            $('[data-type="share-wrap"]').fadeOut();
            $('[data-type="share"]').removeClass('active');
        }
    });

    $('body').on('click', '.js-gallery-open', function(){
        $('.js-gallery-open').each(function(){

            var id = $(this).attr('data-src');
            var element = $(id);

            var item = $(this).closest('.gallery-img');
            var html = item.html();
            element.html(html);
            $('.gallery-img-resize', element).remove();

            /*var parent = $(this).closest('.gallery-img-resize');
            var obj_num = $(this).attr('data-obj_num');
            var img_num = $(this).attr('data-img_num');
            var obj_img = $('span', parent).html().trim();
            var obj_dir = $(this).attr('data-obj_dir');
            var flex = $(this).attr('data-flex');
            $.ajax({
                url: '/gallery/ajax.php',
                type: 'POST',
                data: {
                    'obj_num': obj_num
                    , 'img_num': img_num
                    , 'obj_img': obj_img
                    , 'obj_dir': obj_dir
                    , 'flex': flex
                },
                success: function(html) {
                    element.html(html);
                },
                error: function(html) {
                    console.log(html);
                }
            });*/
        });
    });
})






/**/
/*function namePositioning(){
    $('.gallery-img').each(function(){
        var wdthWrap = $(this).width();
        var offsetWrap = $(this).offset();
        $(this).find('.obj-elem-size').each(function(){
            var offsetNumb = $(this).offset();
            var wdthNumb = $(this).find('div.element-link-item').outerWidth();
            var rightOffset = parseFloat(wdthWrap) - (offsetNumb.left - offsetWrap.left);
            if(parseFloat(wdthNumb) >= rightOffset) {
                $(this).find('.element-link-item').addClass('left');
            }
            $(this).find('.element-link-item').hide();
        })
    })
}
$(document).ready(function() {
    namePositioning();
    $(document).on('afterShow.fb', function( e, instance, slide ) {
        var sld = slide['$content'];
        console.log();
        var imgWrap = $(sld).find('.gallery-img');
        var wdthWrap = $(imgWrap).width();
        var offsetWrap = $(imgWrap).offset();
        $(imgWrap).find('.obj-elem-size').each(function(){
            var offsetNumb = $(this).offset();
            var wdthNumb = $(this).find('div.element-link-item').outerWidth();
            var rightOffset = parseFloat(wdthWrap) - (offsetNumb.left - offsetWrap.left);
            if(parseFloat(wdthNumb) >= rightOffset) {
                $(this).find('.element-link-item').addClass('left');
            } else {
                $(this).find('.element-link-item').removeClass('left');
            }
            $(this).find('.element-link-item').hide();
        })
    });
})
$('[data-type="gallery-number"]').mouseenter(function(){
    $(this).closest('.obj-elem-size').find('.element-link-item').fadeIn();
    $(this).closest('.obj-elem-size').find('.element-link-item').css('z-index','3');
    $(this).css('z-index','4');
})
$('.obj-elem-size').mouseleave(function(){
    $(this).find('.element-link-item').fadeOut();
    $(this).find('.element-link-item').css('z-index','1');
    $(this).find('[data-type="gallery-number"]').css('z-index','2');

})
$(document).ready(function() {
    $('.element-link-item a').click(function(){
        $('.gallery-products-wrapper .prod-prev').each(function(){
            $(this).removeClass('active');
        })
        var el = $(this).attr('href');
        $(el).addClass('active');
        var headerHeight = $('header').height();
        var offset = $(el).offset().top - parseFloat(headerHeight) - 40;
        $('html,body').animate({scrollTop: offset}, 500);
        return false;
    });
});*/