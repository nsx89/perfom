const supportsTouch = ('ontouchstart' in document.documentElement);
let currentWidth = $(window).width();

let mainPrefSliderDo,
    mainGallerySliderDo,
    mainEventsSliderDo,
    mainCatalogueSliderDo,
    mainNewsSliderDo,
    mainArticlesSliderDo;


$(window).on('resize', function() {
    footerMenuPosition();
    if(currentWidth != $(window).width()) {
        currentWidth = $(window).width();
        //слайдеры с таймером для корректного ресайза:
        //1. инициализацию оборачиваем в функцию
        //2. перед инициализацией проверяем, есть ли уже инициализированный слайдер, если есть - дестроим
        //3. при ресайзе запускаем функцию с таймером

        //преимущества
        clearTimeout(mainPrefSliderDo);
        mainPrefSliderDo = setTimeout(mainPrefSlider, 100);

        //галерея
        clearTimeout(mainGallerySliderDo);
        mainGallerySliderDo = setTimeout(mainGallerySlider, 100);

        //события
        clearTimeout(mainEventsSliderDo);
        mainEventsSliderDo = setTimeout(mainEventsSlider, 100);

        //новости
        clearTimeout(mainNewsSliderDo);
        mainNewsSliderDo = setTimeout(mainNewsSlider, 100);

        //каталоги
        clearTimeout(mainCatalogueSliderDo);
        mainCatalogueSliderDo = setTimeout(mainCatalogueSlider, 100);

        //статьи
        clearTimeout(mainArticlesSliderDo);
        mainArticlesSliderDo = setTimeout(function() {
            if (window.innerWidth < 1181) {
                mainArticlesSlider();
            } else {
                if($('[data-type="main-articles-slider"]').hasClass('slick-initialized'))
                    $('[data-type="main-articles-slider"]').slick('unslick');
            }
        }, 100)
    }



});

$('document').ready(function() {
    if(supportsTouch === true) {
        $('body').addClass('touchable')
    }

    footerMenuPosition();

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
}) //document.ready

/* slider initialize */
//главный слайдер
$('[data-type="main-slider"]').ready(function() {
    $('.main-slider-preloader').hide();
    $('[data-type="main-slider"]').slick({
        dots: true,
        arrows: false,
        infinite: true,
        speed: 1500,
        slidesToShow: 1,
        adaptiveHeight: true,
        autoplay: true,
        autoplaySpeed: 4000,
        fade: true,
        cssEase: 'linear',
    });
})

//слайдер преимущества
function mainPrefSlider() {
    if ($('[data-type="main-pref-slider"]').length > 0) {
        if($('[data-type="main-pref-slider"]').hasClass('slick-initialized')) $('[data-type="main-pref-slider"]').slick('unslick');
        $('[data-type="main-pref-slider"]').slick({
            dots: false,
            arrows: false,
            infinite: false,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 1,
            swipeToSlide:true,
            responsive: [
                {
                    breakpoint: 1181,
                    settings: {
                        centerMode: true,
                        centerPadding: '100px',
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 900,
                    settings: {
                        centerMode: true,
                        centerPadding: '100px',
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 700,
                    settings: {
                        centerMode: true,
                        centerPadding: '100px',
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        centerMode: true,
                        centerPadding: '100px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 351,
                    settings: {
                        centerMode: true,
                        centerPadding: '20px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0
                    }
                },
            ]
        });
    }    
    return false;
}
mainPrefSlider();

//слайдер галерея
function mainGallerySlider() {
    if($('[data-type="main-gallery-slider"]').length > 0) {
        if($('[data-type="main-gallery-slider"]').hasClass('slick-initialized')) $('[data-type="main-gallery-slider"]').slick('unslick');
        $('[data-type="main-gallery-slider"]').slick({
            dots: false,
            arrows: false,
            infinite: true,
            speed: 300,
            slidesToShow: 2,
            slidesToScroll: 2,
            swipeToSlide:true,
            centerMode: true,
            centerPadding: '90px',
            variableWidth: true,
            initialSlide: 1,
            responsive: [
                {
                    breakpoint: 1920,
                    settings: {
                        variableWidth: false,
                   }
                },
                {
                    breakpoint: 700,
                    settings: {
                        centerPadding: '0px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0,
                        variableWidth: false,
                        centerMode: false,
                    }
                },
            ]
        });
    }
    return false;
}
//позиционирование всплывающих окон в галерее на главной
function defineGalleryPointPosition(point) {
    let popup = $(point).closest('.show-materials-item').find('.show-materials-popup');
    //очищаем ранее примененные этим скриптом стили, если были
    popup.removeClass('left');
    popup.removeClass('responsive');
    popup.css('bottom', '');
    popup.css('left', '');
    popup.css('right', '');

    $(point).closest('.show-materials-item').addClass('active');

    let popupRect = popup[0].getBoundingClientRect(),
        pointRect = $(point)[0].getBoundingClientRect(),
        slideRect = $(point).closest('.main-gallery-slide')[0].getBoundingClientRect(),
        popupWidth = popupRect.right - pointRect.right, //ширина попапа + отступ от точки
        slickTrack = $('.main-gallery').find('.slick-track')[0],
        matrix = window.getComputedStyle(slickTrack).transform;

        //matrix = matrix.split(/\(|,\s|\)/).slice(1,7);
        //let popupRectRight = Math.abs(matrix[4]) - Math.abs(popupRect.right) > window.innerWidth;

        //позиционирование по горизонтали
        let popupRectRight = popupRect.right + 20 > window.innerWidth, //считаем, влезит ли справа, true = не влезит
            popupRectLeft = pointRect.left - popupWidth < 0, //сразу посчитаем, влезит ли слева, true = не влезит
            popupRectRightNumb = popupRect.right + 20,
            popupRectLeftNumb = pointRect.left - popupWidth;
        /*console.log(pointRect.left);
        console.log('matrix=',matrix[4]);
        console.log('pointRect.right=',pointRect.left);
        console.log('popupRect.right=',popupRect.right);
        console.log('popupRect.left=',popupRect.left);
        console.log('popupRect.top=',popupRect.top);
        console.log('slideRect.top=',slideRect.top);
        console.log('window.innerWidth=',window.innerWidth);
        console.log('popupRectRightNumb=',popupRectRightNumb);
        console.log('popupRectLeftNumb=',popupRectLeftNumb);*/
        if(popupRectRight && !popupRectLeft) { //если не влазит справа, но влазит слева
            popup.addClass('left');
        } else if(popupRectRight && popupRectLeft) {//если не влазит ни справа, ни слева - смотрим, где больше места и позиционируем там + делаем резиновую верстку попапа
            if(popupRect.left > window.innerWidth - pointRect.right) {
                popup.addClass('left');
                popup.css('left','-'+(pointRect.left-20)+'px');
            } else {
                popup.css('right','-'+(window.innerWidth-pointRect.right-20)+'px');
            }
            popup.addClass('responsive');
        } else {
            popup.removeClass('left');
        }
        //позиционирование по вертикали
        if(popupRect.top < slideRect.top - 5) { //если не влазит сверху
            let bottom = slideRect.top - popupRect.top + 5 - parseInt(popup.css('bottom'));
            popup.css('bottom', '-'+parseInt(bottom)+'px');
        }
    return false;
}
$('.main-gallery').on('mouseenter','.show-materials-point',function() {
    defineGalleryPointPosition($(this));
})
$('.main-gallery').on('mouseleave','.show-materials-item',function() {
    $(this).removeClass('active');
    let popup = $(this).find('.show-materials-popup');
    //очищаем ранее примененные этим скриптом стили, если были
    popup.removeClass('left');
    popup.removeClass('responsive');
    popup.css('bottom', '');
    popup.css('left', '');
    popup.css('right', '');
})
mainGallerySlider();
//defineGalleryPointPosition();

//слайдер события
function mainEventsSlider() {
    if($('[data-type="main-events-slider"]').length > 0) {
        if($('[data-type="main-events-slider"]').hasClass('slick-initialized')) $('[data-type="main-events-slider"]').slick('unslick');
        $('[data-type="main-events-slider"]').slick({
            dots: false,
            arrows: false,
            infinite: false,
            speed: 300,
            slidesToShow: 2,
            slidesToScroll: 2,
            swipeToSlide:true,
            vertical: true,
            responsive: [
                {
                    breakpoint: 1181,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 2,
                        vertical: false,
                    }
                },
                {
                    breakpoint: 701,
                    settings: {
                        centerMode: true,
                        centerPadding: '230px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0,
                        vertical: false,
                    }
                },
                {
                    breakpoint: 501,
                    settings: {
                        centerMode: true,
                        centerPadding: '35px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0,
                        vertical: false,
                    }
                },
            ]
        });
    }
    return false;
}
mainEventsSlider();

//слайдер каталоги
function mainCatalogueSlider() {
    if($('[data-type="main-catalogue-slider"]').length > 0) {
        if($('[data-type="main-catalogue-slider"]').hasClass('slick-initialized')) $('[data-type="main-catalogue-slider"]').slick('unslick');
        $('[data-type="main-catalogue-slider"]').slick({
            dots: false,
            arrows: false,
            infinite: false,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 3,
            swipeToSlide:true,
            responsive: [
                {
                    breakpoint: 1181,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 851,
                    settings: {
                        centerMode: true,
                        centerPadding: '100px',
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 701,
                    settings: {
                        centerMode: true,
                        centerPadding: '230px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0
                    }
                },
                {
                    breakpoint: 501,
                    settings: {
                        centerMode: true,
                        centerPadding: '165px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0
                    }
                },
                {
                    breakpoint: 351,
                    settings: {
                        centerMode: true,
                        centerPadding: '35px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0
                    }
                },
            ]
        });
    }
    return false;
}
mainCatalogueSlider();

//слайдер новости
function mainNewsSlider() {
    if($('[data-type="main-news-slider"]').length > 0) {
        if($('[data-type="main-news-slider"]').hasClass('slick-initialized')) $('[data-type="main-news-slider"]').slick('unslick');
        $('[data-type="main-news-slider"]').slick({
            dots: false,
            arrows: false,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
            swipeToSlide:true,
            responsive: [
                {
                    breakpoint: 1181,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 851,
                    settings: {
                        centerMode: true,
                        centerPadding: '100px',
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 701,
                    settings: {
                        centerMode: true,
                        centerPadding: '230px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0
                    }
                },
                {
                    breakpoint: 501,
                    settings: {
                        centerMode: true,
                        centerPadding: '130px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0
                    }
                },
                {
                    breakpoint: 351,
                    settings: {
                        centerMode: true,
                        centerPadding: '35px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0
                    }
                },
            ]
        });
    }    
    return false;
}
mainNewsSlider();

//слайдер статьи
function mainArticlesSlider() {
    if($('[data-type="main-articles-slider"]').length > 0) {
        if($('[data-type="main-articles-slider"]').hasClass('slick-initialized')) $('[data-type="main-articles-slider"]').slick('unslick');
        
        $('[data-type="main-articles-slider"]').slick({
            dots: true,
            arrows: false,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 4,
            swipeToSlide:true,
            respondTo: 'slider',
            responsive: [
                {
                    breakpoint: 90000,
                    settings: "unslick"
                },
                {
                    breakpoint: 1181,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        initialSlide: 0,
                    }
                },
                {
                    breakpoint: 851,
                    settings: {
                        slidesToShow: 1,
                        slidesToScroll: 1,
                    }
                },
            ],
        });
    }    
    return false;
}
if(window.innerWidth < 1181) {
    mainArticlesSlider();
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