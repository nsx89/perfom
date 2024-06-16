var currentWidth = $(window).width();

let mainSliderDo,
    mainPrefSliderDo,
    mainPrefInstallSliderDo,
    mainPrefFactorySliderDo,
    mainGallerySliderDo,
    mainEventsSliderDo,
    mainCatalogueSliderDo,
    mainNewsSliderDo,
    mainInstrSliderDo,
    mainArticlesSliderDo,
    mainRecommSliderDo,
    galleryTabSliderDo,
    galleryProdSliderDo,
    prodSimilarSliderDo,
    mainColSliderDo,
    mainDwnldSliderDo,
    newsTabSliderDo,
    lastNewsSliderDo,
    mainOffSliderDo,
    personalTabSliderDo,
    profileTabSliderDo,
    modTabSliderDo,
    mediaSliderDo;

$(window).on('resize', function() {

    if(currentWidth != $(window).width()) {
        currentWidth = $(window).width();

        //слайдеры с таймером для корректного ресайза:
        //1. инициализацию оборачиваем в функцию
        //2. перед инициализацией проверяем, есть ли уже инициализированный слайдер, если есть - дестроим
        //3. при ресайзе запускаем функцию с таймером

        //главный слайдер
        clearTimeout(mainSliderDo);
        mainSliderDo = setTimeout(mainSlider, 100);

        //преимущества
        clearTimeout(mainPrefSliderDo);
        mainPrefSliderDo = setTimeout(mainPrefSlider, 100);

        //преимущества монтаж
        clearTimeout(mainPrefInstallSliderDo);
        mainPrefInstallSliderDo = setTimeout(mainPrefInstallSlider, 100);

        //преимущества производство
        clearTimeout(mainPrefFactorySliderDo);
        mainPrefFactorySliderDo = setTimeout(mainPrefFactorySlider, 100);

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

        //нас рекомендуют
        clearTimeout(mainRecommSliderDo);
        mainRecommSliderDo = setTimeout(mainRecommSlider, 100);

        //инструкции
        clearTimeout(mainInstrSliderDo);
        mainInstrSliderDo = setTimeout(mainInstrSlider, 100);

        //галерея табы
        clearTimeout(galleryTabSliderDo);
        galleryTabSliderDo = setTimeout(galleryTabSlider, 100);

        //галерея слайдер продуктов
        clearTimeout(galleryProdSliderDo);
        galleryProdSliderDo = setTimeout(galleryProdSlider, 100);

        //слайдеры на странице товара
        clearTimeout(prodSimilarSliderDo);
        prodSimilarSliderDo = setTimeout(prodSimilarSlider, 100);

        //коллекции табы
        clearTimeout(mainColSliderDo);
        mainColSliderDo = setTimeout(mainColSlider, 100);

        //загрузки табы
        clearTimeout(mainDwnldSliderDo);
        mainDwnldSliderDo = setTimeout(mainDwnldSlider, 100);

        //новости табы
        clearTimeout(newsTabSliderDo);
        newsTabSliderDo = setTimeout(newsTabSlider, 100);

        //новости последние статьи
        clearTimeout(lastNewsSliderDo);
        lastNewsSliderDo = setTimeout(lastNewsSlider, 100);

        //контакты табы
        clearTimeout(mainOffSliderDo);
        mainOffSliderDo = setTimeout(mainOffSlider, 100);

        //кабинет пользователя табы
        clearTimeout(personalTabSliderDo);
        personalTabSliderDo = setTimeout(personalTabSlider, 100);

        //кабинет пользователя табы профиля
        clearTimeout(profileTabSliderDo);
        profileTabSliderDo = setTimeout(profileTabSlider, 100);

        //кабинет модератора табы
        clearTimeout(modTabSliderDo);
        modTabSliderDo = setTimeout(modTabSlider, 100);

        //слайдеры на странице Медиа
        clearTimeout(mediaSliderDo);
        mediaSliderDo = setTimeout(mediaSlider, 100);
    }
})

$(document).ready(function() {
    mainSlider();
    mainPrefSlider();
    mainPrefInstallSlider();
    mainPrefFactorySlider();
    mainGallerySlider();
    mainEventsSlider();
    mainCatalogueSlider();
    mainNewsSlider();
    mainRecommSlider();
    mainArticlesSlider();
    mainInstrSlider();
    galleryTabSlider();
    galleryProdSlider();
    prodSimilarSlider();
    mainColSlider();
    mainDwnldSlider();
    newsTabSlider();
    newsSlider();
    lastNewsSlider();
    mainOffSlider();
    personalTabSlider();
    profileTabSlider();
    modTabSlider();
    mediaSlider();

    //слайдер товара на странице товара
    if($( '#prodSlider' ).length > 0) {
        $( '#prodSlider' ).sliderPro({
            width: 713,
            height: 713,
            fade: true,
            loop: false,
            buttons: false,
            autoplay: false,
            thumbnailsPosition: 'left',
            thumbnailWidth: 127,
            thumbnailHeight: 127,
            touchSwipe: false,
            breakpoints: {
                1600: {
                    width: 500,
                    height: 500,
                    thumbnailWidth: 93,
                    thumbnailHeight: 93,
                },
                1050: {
                    width: 400,
                    height: 400,
                    thumbnailWidth: 73,
                    thumbnailHeight: 73,
                },
                950: {
                    width: 400,
                    height: 400,
                    thumbnailWidth: 73,
                    thumbnailHeight: 73,
                    thumbnailsPosition: 'bottom',
                },
                750: {
                    width: 300,
                    height: 300,
                    thumbnailWidth: 68,
                    thumbnailHeight: 68,
                    thumbnailsPosition: 'bottom',
                },
                600: {
                    width: 600,
                    height: 450,
                    thumbnailWidth: 73,
                    thumbnailHeight: 73,
                    thumbnailsPosition: 'bottom',
                },
                400: {
                    width: 400,
                    height: 400,
                    thumbnailWidth: 73,
                    thumbnailHeight: 73,
                    thumbnailsPosition: 'bottom',
                },
            }
        });
        $( '#prodSlider' ).ready(function() {
            $('[data-type="prod-preload"]').fadeOut();
        });
        if($('.sp-thumbnail-container').find('.sp-thumbnail-section').length>0) {
            $('.sp-thumbnail-section').closest('.sp-thumbnail-container').addClass('sp-thumbnail-container-section');
        }
        if($('.sp-thumbnail-container').find('.sp-thumbnail-draw').length>0) {
            $('.sp-thumbnail-draw').closest('.sp-thumbnail-container').addClass('sp-thumbnail-container-draw');
        }
    }
})


//главный слайдер
function mainSlider() {
    if ($('[data-type="main-slider"]').length > 0) {
        if($('[data-type="main-slider"]').hasClass('slick-initialized')) $('[data-type="main-slider"]').slick('unslick');
        $('.main-slider-preloader').hide();
        $('[data-type="main-slider"]').slick({
            dots: true,
            arrows: false,
            infinite: true,
            speed: 1500,
            slidesToShow: 1,
            adaptiveHeight: false,
            autoplay: true,
            autoplaySpeed: 4000,
            fade: true,
            cssEase: 'linear',
            pauseOnHover: false,
        });
    }
    return false;
}

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
                        centerPadding: '80px',
                        slidesToShow: 4,
                        slidesToScroll: 1,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 900,
                    settings: {
                        centerMode: true,
                        centerPadding: '80px',
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 600,
                    settings: {
                        centerMode: true,
                        centerPadding: '80px',
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 451,
                    settings: {
                        centerMode: true,
                        centerPadding: '20px',
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 351,
                    settings: {
                        centerMode: true,
                        centerPadding: '100px',
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

//слайдер преимущества монтаж
function mainPrefInstallSlider() {
    if ($('[data-type="install-pref-slider"]').length > 0) {
        if($('[data-type="install-pref-slider"]').hasClass('slick-initialized')) $('[data-type="install-pref-slider"]').slick('unslick');
        $('[data-type="install-pref-slider"]').slick({
            dots: false,
            arrows: false,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            swipeToSlide:true,
            responsive: [
                {
                    breakpoint: 801,
                    settings: {
                        centerMode: true,
                        centerPadding: '80px',
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 601,
                    settings: {
                        centerMode: true,
                        centerPadding: '180px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0
                    }
                },
                {
                    breakpoint: 451,
                    settings: "unslick"
                },
            ]
        });
    }
    return false;
}

//слайдер преимущества производство
function mainPrefFactorySlider() {
    if ($('[data-type="factory-pref-slider"]').length > 0) {
        if($('[data-type="factory-pref-slider"]').hasClass('slick-initialized')) $('[data-type="factory-pref-slider"]').slick('unslick');
        $('[data-type="factory-pref-slider"]').slick({
            dots: false,
            arrows: false,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
            swipeToSlide:true,
            responsive: [
                {
                    breakpoint: 851,
                    settings: {
                        centerMode: true,
                        centerPadding: '100px',
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 701,
                    settings: {
                        centerMode: true,
                        centerPadding: '20px',
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 451,
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

//слайдер галерея
function mainGallerySlider() {
    if($('[data-type="main-gallery-slider"]').length > 0) {

        var city = $('[data-type="main-gallery-slider"]').attr('data-city') || '';

        if (city != '') {
            $.ajax({
                type: "GET",
                url: "/include/main_gallery.php",
                data: {
                    'city': city
                },
                success: function(html){

                    $('.main-gallery-slider-loading').html(html);

                    setTimeout(function(){
                        mainGallerySliderSlick();
                    }, 200);

                    setTimeout(function(){
                        $('.main-gallery-slider-loading').removeClass('main-gallery-slider-loading');
                    }, 250);
                }
            });
        }
        else {
            mainGallerySliderSlick();
        }
    }
    return false;
}
function mainGallerySliderSlick() {
    if($('[data-type="main-gallery-slider"]').hasClass('slick-initialized')) $('[data-type="main-gallery-slider"]').slick('unslick');
    $('[data-type="main-gallery-slider"]').slick({
        dots: false,
        arrows: true,
        infinite: true,
        speed: 300,
        slidesToShow: 3,
        slidesToScroll: 3,
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
                breakpoint: 851,
                settings: {
                    centerPadding: '200px',
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    initialSlide: 1,
                    variableWidth: false,
                }
            },
            {
                breakpoint: 601,
                settings: {
                    centerPadding: '100px',
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    initialSlide: 1,
                    variableWidth: false,
                }
            },
            {
                breakpoint: 451,
                settings: {
                    centerPadding: '20px',
                    slidesToShow: 1,
                    slidesToScroll: 1,
                    initialSlide: 1,
                    variableWidth: false,
                }
            },
        ]
    });
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
        slideRect = $(point).closest('[data-type="gallery-slide" ]')[0].getBoundingClientRect(),
        popupWidth = popupRect.right - pointRect.right; //ширина попапа + отступ от точки

    //позиционирование по горизонтали
    let popupRectRight = popupRect.right + 20 > window.innerWidth, //считаем, влезит ли справа, true = не влезит
        popupRectLeft = pointRect.left - popupWidth < 0, //сразу посчитаем, влезит ли слева, true = не влезит
        popupRectRightNumb = popupRect.right + 20,
        popupRectLeftNumb = pointRect.left - popupWidth;

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
$('body').on('mouseenter','.show-materials-point',function() {
    defineGalleryPointPosition($(this));
})
$('body').on('mouseleave','.show-materials-item',function() {
    $(this).removeClass('active');
    let popup = $(this).find('.show-materials-popup');
    //очищаем ранее примененные этим скриптом стили, если были
    popup.removeClass('left');
    popup.removeClass('responsive');
    popup.css('bottom', '');
    popup.css('left', '');
    popup.css('right', '');
})
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
            slidesToScroll: 1,
            swipeToSlide:true,
            responsive: [
                {
                    breakpoint: 1181,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                        initialSlide: 2
                    }
                },
                {
                    breakpoint: 801,
                    settings: {
                        centerMode: true,
                        centerPadding: '20px',
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 701,
                    settings: {
                        centerMode: true,
                        centerPadding: '150px',
                        slidesToShow: 1,
                        slidesToScroll: 1,
                        initialSlide: 0,
                    }
                },
                {
                    breakpoint: 501,
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

//слайдер новости
function mainNewsSlider() {
    if($('[data-type="main-news-slider"]').length > 0) {
        if($('[data-type="main-news-slider"]').hasClass('slick-initialized')) $('[data-type="main-news-slider"]').slick('unslick');
        $('[data-type="main-news-slider"]').slick({
            dots: false,
            arrows: false,
            infinite: false,
            speed: 300,
            slidesToShow: 6,
            slidesToScroll: 6,
            swipeToSlide:true,
            responsive: [
                {
                    breakpoint: 1181,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 4,
                        initialSlide: 0
                    }
                },
                {
                    breakpoint: 851,
                    settings: {
                        centerMode: true,
                        centerPadding: '100px',
                        slidesToShow: 3,
                        slidesToScroll: 3,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 701,
                    settings: {
                        centerMode: true,
                        centerPadding: '20px',
                        slidesToShow: 2,
                        slidesToScroll: 2,
                        initialSlide: 1
                    }
                },
                {
                    breakpoint: 451,
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

//слайдер статьи
function mainArticlesSlider() {
    if($('[data-type="main-articles-slider"]').length > 0) {
        if($('[data-type="main-articles-slider"]').hasClass('slick-initialized')) $('[data-type="main-articles-slider"]').slick('unslick');
        if(window.innerWidth < 1181) {
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
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            initialSlide: 0,
                        }
                    },
                    {
                        breakpoint: 1030,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            initialSlide: 0,
                        }
                    },
                    {
                        breakpoint: 451,
                        settings: {
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            initialSlide: 0,
                        }
                    },
                ],
            });
        }
    }
    return false;
}

//слайдер нас рекомендуют
function mainRecommSlider() {
    if ($('[data-type="main-recomm-slider"]').length > 0) {
        if($('[data-type="main-recomm-slider"]').hasClass('slick-initialized')) $('[data-type="main-recomm-slider"]').slick('unslick');
        /*
* слайдер некорректно отображается, если выставлять кол-во слайдов 6 в breakpoint, поэтому костыли
*  */
        if(window.innerWidth <= 1920) {
            $('[data-type="main-recomm-slider"]').slick({
                dots: false,
                arrows: false,
                infinite: false,
                speed: 300,
                slidesToShow: 4,
                slidesToScroll: 4,
                swipeToSlide: true,
                centerMode: true,
                centerPadding: '90px',
                initialSlide: 2,
                responsive: [
                    {
                        breakpoint: 1181,
                        settings: {
                            centerMode: true,
                            centerPadding: '80px',
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            initialSlide: 1
                        }
                    },
                    {
                        breakpoint: 900,
                        settings: {
                            centerMode: true,
                            centerPadding: '80px',
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            initialSlide: 1
                        }
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            centerMode: true,
                            centerPadding: '80px',
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            initialSlide: 1
                        }
                    },
                    {
                        breakpoint: 451,
                        settings: {
                            centerMode: true,
                            centerPadding: '20px',
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            initialSlide: 1
                        }
                    },
                    {
                        breakpoint: 351,
                        settings: {
                            centerMode: true,
                            centerPadding: '100px',
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            initialSlide: 0
                        }
                    },
                ]
            });
        }
    }
    return false;
}

//слайдер инструкции
function mainInstrSlider() {
    if($('[data-type="instr-slider"]').length > 0) {
        if($('[data-type="instr-slider"]').hasClass('slick-initialized')) $('[data-type="instr-slider"]').slick('unslick');
        if(window.innerWidth <= 1181) {
            $('[data-type="instr-slider"]').slick({
                dots: false,
                arrows: false,
                infinite: false,
                speed: 300,
                slidesToShow: 6,
                slidesToScroll: 6,
                swipeToSlide:true,
                responsive: [
                    {
                        breakpoint: 1181,
                        settings: {
                            centerMode: true,
                            centerPadding: '20px',
                            slidesToShow: 4,
                            slidesToScroll: 4,
                            initialSlide: 2
                        }
                    },
                    {
                        breakpoint: 851,
                        settings: {
                            centerMode: true,
                            centerPadding: '90px',
                            slidesToShow: 3,
                            slidesToScroll: 3,
                            initialSlide: 1
                        }
                    },
                    {
                        breakpoint: 701,
                        settings: {
                            centerMode: true,
                            centerPadding: '80px',
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            initialSlide: 1
                        }
                    },
                    {
                        breakpoint: 451,
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
                            centerPadding: '20px',
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            initialSlide: 0
                        }
                    },
                ]
            });
        }
    }
    return false;
}

//галерея табы
function galleryTabSlider() {
    if ($('[data-type="gallery-tab-slider"]').length > 0) {
        if($('[data-type="gallery-tab-slider"]').hasClass('slick-initialized')) $('[data-type="gallery-tab-slider"]').slick('unslick');
        if(window.innerWidth <= 800) {
            $('[data-type="gallery-tab-slider"]').slick({
                dots: false,
                arrows: false,
                infinite: true,
                speed: 300,
                swipeToSlide:true,
                initialSlide: 0,
                variableWidth: true,
                focusOnSelect: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                outerEdgeLimit: true,
                centerMode: true,
            });
            defineInitial($('[data-type="gallery-tab-slider"]'));
            /*$('[data-type="gallery-tab-slider"]').on('afterChange', function(){
                var isVisible = isVisiblell($('[data-type="gallery-tab-slider"]'), $('[data-type="gallery-tab-slider"]').find('.slick-slide').last());
                let tabLength = 0;
                $('[data-type="gallery-tab-slider"]').find('.slick-slide').each(function() {
                    tabLength += $(this).outerWidth();
                })

                console.log(window.innerWidth - 20 >= $('[data-type="gallery-tab-slider"]').find('.slick-slide').last().offset().left+$('[data-type="gallery-tab-slider"]').find('.slick-slide').last().outerWidth());
                if (isVisible) {
                    $('.slider').find('.slick-next').hide();
                    console.log('edge');
                    $('[data-type="gallery-tab-slider"]').find('.slick-track').css('transform','translate3d(-740, 0, 0)');
                    //$('[data-type="gallery-tab-slider"]').find('.slick-track')[0].transform(-740, 0, 0);
                } else {
                    $('.slider').find('.slick-next').show();
                }

            });*/
        }
    }
    return false;
}

/*function isVisiblell($parent, $child) {
    var parentWidth = $parent.outerWidth(),
        parentHeight = $parent.outerHeight(),
        parentPos = $parent.offset(),
        childPos = $child.offset(),
        childWidth = $child.outerWidth(),
        childHeight = $child.outerHeight();

    var isVisibleX = (childPos.left >= parentPos.left &&
        (childPos.left + childWidth) <= (parentPos.left + parentWidth));

    var isVisibleY = (childPos.top >= parentPos.top &&
        (childPos.top + childHeight) <= (parentPos.top + parentHeight))

    return isVisibleY && isVisibleX;
}*/

//галерея продукты
function galleryProdSlider() {
    if($('[data-type="gallery-prod"]').length > 0) {
        if($('[data-type="gallery-prod"]').hasClass('slick-initialized')) $('[data-type="gallery-prod"]').slick('unslick');
        if(window.innerWidth <= 2276) {
            let slickParams = {
                dots: false,
                arrows: false,
                infinite: false,
                speed: 300,
                slidesToShow: 5,
                slidesToScroll: 1,
                swipeToSlide:true,
                centerMode: true,
                centerPadding: '90px',
                initialSlide: 2,
                responsive: [
                    {
                        breakpoint: 1279,
                        settings: {
                            slidesToShow: 4,
                        }
                    },
                    {
                        breakpoint: 1001,
                        settings: {
                            slidesToShow: 3,
                            initialSlide: 1,
                        }
                    },
                    {
                        breakpoint: 801,
                        settings: {
                            centerPadding: '20px',
                            slidesToShow: 2,
                            initialSlide: 1,
                        }
                    },
                ]
            };
            if(window.innerWidth > 1279 && window.innerWidth <= 2276 && $('[data-type="gallery-prod"]').find('.prod-prev').length > 5) {
                $('[data-type="gallery-prod"]').slick(slickParams);
            }
            else if(window.innerWidth > 1001 && window.innerWidth <= 1279 && $('[data-type="gallery-prod"]').find('.prod-prev').length > 4) {
                $('[data-type="gallery-prod"]').slick(slickParams);
            }
            else if(window.innerWidth > 801 && window.innerWidth <= 1001 && $('[data-type="gallery-prod"]').find('.prod-prev').length > 3) {
                $('[data-type="gallery-prod"]').slick(slickParams);
            }
            else if(window.innerWidth <= 801 && $('[data-type="gallery-prod"]').find('.prod-prev').length > 2) {
                $('[data-type="gallery-prod"]').slick(slickParams);
            } else {
                if($('[data-type="gallery-prod"]').hasClass('slick-initialized'))
                    $('[data-type="gallery-prod"]').slick('unslick');
            }

        } else {
            if($('[data-type="gallery-prod"]').hasClass('slick-initialized'))
                $('[data-type="gallery-prod"]').slick('unslick');
        }

    }
    return false;
}

//слайдеры на странице товара
function prodSimilarSlider() {
    if($('[data-type="similar-slider"]').length > 0) {
        if($('[data-type="similar-slider"]').hasClass('slick-initialized')) $('[data-type="similar-slider"]').slick('unslick');
        let slickParams = {
            dots: false,
            arrows: true,
            infinite: false,
            speed: 300,
            slidesToShow: 5,
            slidesToScroll: 1,
            swipeToSlide:true,
            responsive: [
                {
                    breakpoint: 1279,
                    settings: {
                        slidesToShow: 4,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1000,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 801,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '50px',
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        initialSlide: 1,
                    }
                },
                {
                    breakpoint: 601,
                    settings: {
                        arrows: false,
                        centerMode: true,
                        centerPadding: '40px',
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        initialSlide: 1
                    }
                },
            ]
        };
        $('[data-type="similar-slider"]').slick(slickParams);
        $('[data-type="similar-slider"]:odd').each(function() {
            let slideQty = $(this).find('.slick-slide').length;
            if(slideQty > 1) {
                slideQty = slideQty > 2 ? slideQty-1 : 0;
                console.log(slideQty);
                $(this).slick('slickGoTo',slideQty,false);
            } else {
                $(this).slick('unslick');
            }

        })

    }
    return false;
}

//коллекции табы
function mainColSlider() {
    if ($('[data-type="col-prod-nav"]').length > 0) {
        if($('[data-type="col-prod-nav"]').hasClass('slick-initialized')) $('[data-type="col-prod-nav"]').slick('unslick');
        if(window.innerWidth <= 600) {
            $('[data-type="col-prod-nav"]').slick({
                dots: false,
                arrows: false,
                infinite: true,
                speed: 300,
                swipeToSlide:true,
                initialSlide: 0,
                variableWidth: true,
                focusOnSelect: true,
                slidesToShow: 1,
                slidesToScroll: 1,
                outerEdgeLimit: true,
                centerMode: true,
            });
            defineInitial($('[data-type="col-prod-nav"]'));
        }
    }
    return false;
}

//загрузки табы
function mainDwnldSlider() {
    if ($('[data-type="main-dwnld"]').length > 0) {
        if($('[data-type="main-dwnld"]').hasClass('slick-initialized')) $('[data-type="main-dwnld"]').slick('unslick');
        if(window.innerWidth <= 950) {
            $('[data-type="main-dwnld"]').slick({
                dots: false,
                arrows: false,
                infinite: true,
                speed: 300,
                swipeToSlide:true,
                centerMode: true,
                initialSlide: 0,
                variableWidth: true,
                focusOnSelect: true,
            });
            defineInitial($('[data-type="main-dwnld"]'));
        }
    }
    return false;
}

//новости табы
function newsTabSlider() {
    if ($('[data-type="news-tab-slider"]').length > 0) {
        if($('[data-type="news-tab-slider"]').hasClass('slick-initialized')) $('[data-type="news-tab-slider"]').slick('unslick');
        if(window.innerWidth <= 800) {
            $('[data-type="news-tab-slider"]').slick({
                dots: false,
                arrows: false,
                infinite: true,
                speed: 300,
                swipeToSlide:true,
                centerMode: true,
                initialSlide: 0,
                variableWidth: true,
                focusOnSelect: true,
            });
            defineInitial($('[data-type="news-tab-slider"]'));
        }
    }
    return false;
}

//новости статья
function newsSlider() {
    if ($('[data-type="news-slider"]').length > 0) {
        $('[data-type="news-slider"]').ready(function () {
            $('[data-type="slider-wait"]').hide();
            $('[data-type="news-slider"]').slick({
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
                pauseOnHover: false,
            });
        })
    }
}

//новости последние статьи
function lastNewsSlider() {
    if($('[data-type="last-news"]').length > 0) {
        if($('[data-type="last-news"]').hasClass('slick-initialized')) $('[data-type="last-news"]').slick('unslick');
        if(window.innerWidth <= 1279) {
            $('[data-type="last-news"]').slick({
                dots: false,
                arrows: false,
                infinite: false,
                speed: 300,
                slidesToShow: 3,
                slidesToScroll: 3,
                swipeToSlide: true,
                centerMode: true,
                centerPadding: '40px',
                initialSlide: 1,
                responsive: [
                    {
                        breakpoint: 801,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 2,
                            initialSlide: 1,
                            centerPadding: '20px',
                        }
                    },
                    {
                        breakpoint: 451,
                        settings: {
                            centerPadding: '20px',
                            slidesToShow: 1,
                            slidesToScroll: 1,
                            initialSlide: 0
                        }
                    },
                ]
            });
        }
    }
    return false;
}

//контакты табы
function mainOffSlider() {
    if ($('[data-type="main-off"]').length > 0) {
        if($('[data-type="main-off"]').hasClass('slick-initialized')) $('[data-type="main-off"]').slick('unslick');
        if(window.innerWidth <= 800) {
            $('[data-type="main-off"]').slick({
                dots: false,
                arrows: false,
                infinite: true,
                speed: 300,
                swipeToSlide:true,
                centerMode: true,
                initialSlide: 0,
                variableWidth: true,
                focusOnSelect: true,
            });
            defineInitial($('[data-type="main-off"]'));
        }
    }
    return false;
}
//кабинет пользователя табы
function personalTabSlider() {
    if ($('[data-type="personal-tabs"]').length > 0) {
        if($('[data-type="personal-tabs"]').hasClass('slick-initialized')) $('[data-type="personal-tabs"]').slick('unslick');
        if(window.innerWidth <= 320) {
            $('[data-type="personal-tabs"]').slick({
                dots: false,
                arrows: false,
                infinite: true,
                speed: 300,
                swipeToSlide:true,
                centerMode: true,
                initialSlide: 0,
                variableWidth: true,
                focusOnSelect: true,
            });
            defineInitial($('[data-type="personal-tabs"]'));
        }
    }
    return false;
}

//кабинет пользователя табы профиля
function profileTabSlider() {
    if ($('[data-type="profile-nav-mob"]').length > 0) {
        if($('[data-type="profile-nav-mob"]').hasClass('slick-initialized')) $('[data-type="profile-nav-mob"]').slick('unslick');
        if(window.innerWidth <= 450) {
            $('[data-type="profile-nav-mob"]').slick({
                dots: false,
                arrows: false,
                infinite: true,
                speed: 300,
                swipeToSlide:true,
                centerMode: true,
                initialSlide: 0,
                variableWidth: true,
                focusOnSelect: true,
            });
            defineInitial($('[data-type="profile-nav-mob"]'));
        }
    }
    return false;
}

//кабинет модератора табы
function modTabSlider() {
    if ($('[data-type="moder-tabs"]').length > 0) {
        if($('[data-type="moder-tabs"]').hasClass('slick-initialized')) $('[data-type="moder-tabs"]').slick('unslick');
        if(window.innerWidth <= 700) {
            $('[data-type="moder-tabs"]').slick({
                dots: false,
                arrows: false,
                infinite: true,
                speed: 300,
                swipeToSlide:true,
                centerMode: true,
                initialSlide: 0,
                variableWidth: true,
                focusOnSelect: true,
            });
            setTimeout(function(){
                defineInitial($('[data-type="moder-tabs"]'));
            },100);
        }
    }
    return false;
}
//первый активный слайд - по центру
function defineInitial(wrap) {
    let initial = 0;
    $(wrap).find('.active').each(function() {
        if($(this).attr('data-slick-index') > 0 && initial == 0) initial = $(this).attr('data-slick-index');
    });
    if(initial != 0) {
        $(wrap).slick('slickGoTo',initial,false);
    }
    return false;
}

//слайдер в разделе Медиа
function mediaSlider() {
    if($('[data-type="media-slider"]').length > 0) {
        $('[data-type="media-slider"]').removeClass('m-media-products-loading');
        if($('[data-type="media-slider"]').hasClass('slick-initialized')) $('[data-type="media-slider"]').slick('unslick');
        let slickParams = {
            dots: false,
            arrows: true,
            infinite: false,
            speed: 300,
            slidesToShow: 3,
            slidesToScroll: 1,
            swipeToSlide:true,
            responsive: [
                {
                    breakpoint: 1279,
                    settings: {
                        slidesToShow: 3,
                        slidesToScroll: 1,
                    }
                },
                {
                    breakpoint: 1000,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        initialSlide: 1,
                    }
                },
                {
                    breakpoint: 720,
                    settings: {
                        slidesToShow: 2,
                        slidesToScroll: 1,
                        initialSlide: 1,
                        arrows: false
                    }
                },
            ]
        };
        $('[data-type="media-slider"]').slick(slickParams);
        $('[data-type="media-slider"]:odd').each(function() {
            let slideQty = $(this).find('.slick-slide').length;
            if(slideQty > 1) {
                slideQty = slideQty > 2 ? slideQty-1 : 0;
                console.log(slideQty);
                $(this).slick('slickGoTo',slideQty,false);
            } else {
                $(this).slick('unslick');
            }

        })

    }
    return false;
}