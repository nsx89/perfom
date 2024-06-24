/**
 * пагинация /js/pagination.js
 */

$('[data-type="news-tab"]').on('click',function(){
    $('[data-type="news-tab"]').each(function(){
        $(this).removeClass('active');
    })
    $(this).addClass('active');
})
function newsInit() {
    pagination();
    let hash = location.hash;
    if(hash == '') hash = "#all"
    $('[data-type="news-tab"]').each(function(){
        $(this).removeClass('active');
    })
    let actTab = $('[href="'+hash+'"]').closest("li");
    actTab.addClass('active');
    let qty = actTab.attr('data-count');
    if(hash == "#all") qty = $('[data-type="pag"]').attr('data-all');
    $('[data-type="pag"]').pagination('updateItems', qty);
    if(qty <= 8 || $('[data-type="pag"]').attr('data-current') == 'all') {
        $('.pagination').hide();
    } else {
        $('.pagination').show();
    }
    ePagination(true);
    $('[data-type="news-tab"]').on('click',function(){
        let qty = $(this).attr('data-count');
        if($(this).attr('data-val') == 'all') qty = $('[data-type="pag"]').attr('data-all');
        $('[data-type="pag"]').pagination('updateItems', qty);
        $('[data-type="pag"]').pagination('drawPage', 1);
        $('[data-type="pag"]').attr('data-current',1);
        $('.pag-wrap').show();
        $('.show-per-page').removeClass('active');
        /*if(qty <= 8 || $('[data-type="pag"]').attr('data-current') == 'all') {
            $('.pagination').hide();
        } else {
            $('.pagination').show();
        }*/
        ePagination();
    })

}
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
lightbox.option({
    'albumLabel': "%1 из %2",
})
document.addEventListener("DOMContentLoaded", newsInit);