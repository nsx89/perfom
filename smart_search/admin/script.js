/**
 * выбор отчетного периода
 */
$('.pacc-period').on('click',function(){
    $('.pacc-period').each(function(){
        $(this).removeClass('active');
        if($(this).attr('data-type')=='period') {
            $('.pacc-period-choose').addClass('unact');
            $('[ data-type="period-limit"]').each(function(){
                $(this).val('');
            })
            $('#tcal').css('visibility','');
            $('*[data-type="period-limit"]').removeClass('tcalActive');
        }
    })
    $(this).addClass('active');
    if($(this).attr('data-type')=='period') {
        $('.pacc-period-choose').removeClass('unact');
    }
})


/**
 * применить
 */
$('[ data-type="search-stat-btn"]').on('click',function () {
    $('[data-type="pacc-mess"]').fadeIn();
    let ssDate = {},
        period = $('.pacc-period.active').attr('data-val'),
        dateFrom = '',
        dateTo = '';
    if(period == 0) {
        dateFrom = $('[name="qm-from"]').val();
        dateTo = $('[name="qm-to"]').val();
        if(dateFrom == "" && dateTo == "") {
            alert ("Введите дату!")
            $('[data-type="pacc-mess"]').fadeOut();
            return;
        }
    }
    ssDate['from'] = dateFrom;
    ssDate['to'] = dateTo;
    ssDate['val'] = period;
    let ss_date = JSON.stringify(ssDate);
    $.cookie('ss_date', ss_date, {domain: domain, path: '/'});
    location.reload();
})
/**
 * сбросить дату
 */
$('[data-type="remove-date"]').on('click',function() {
    $('[data-type="pacc-mess"]').fadeIn();
    let ssDate = {
        'val':1,
        'from':'',
        'to':''
    };
    let ss_date = JSON.stringify(ssDate);
    $.cookie('ss_date', ss_date, {domain: domain, path: '/'});
    location.reload();
})