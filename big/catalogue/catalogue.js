$(document).ready(function() {
    lightbox.option({
        'albumLabel': "%1 из %2",
    })
    pagination();
    $('.content-wrapper').on('click','[data-type="filt-title"]',function() {
        let wrap = $(this).closest('[data-type="filt-item"]');
        wrap.toggleClass('active');
    })
    $('[data-type="filt-cont"]').on('click','li',function() {
        $(this).toggleClass('active');
    })
    $('*[data-name="category-filter"]').each(function(){
        noUiSlider.create($(this)[0], {
            start: [ parseInt($(this).attr('data-from')), parseInt($(this).attr('data-to')) ],
            connect: true,
            step: 1,
            range: {
                'min': parseInt($(this).attr('data-fmin')),
                'max': parseInt($(this).attr('data-fmax'))
            }
        });
    });
    $('*[data-name="category-filter"]').each(function(){
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
    });

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
                1160: {
                    width: 425,
                    height: 425,
                    thumbnailWidth: 86,
                    thumbnailHeight: 86,
                },
            }
        });
        $( '#prodSlider' ).ready(function() {
            $('[data-type="prod-preload"]').fadeOut();
        });
        if($('.sp-thumbnail-container').find('#cut_img').length>0) {
            $('#cut_img').closest('.sp-thumbnail-container').addClass('sp-thumbnail-container-section');
        }
    }

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


})// document.ready

function prodAdhSlider() {
    if($('[data-type="adh-slider"]').length > 0) {
        if($('[data-type="adh-slider"]').hasClass('slick-initialized')) $('[data-type="adh-slider"]').slick('unslick');
        $('[data-type="adh-slider"]').slick({
            dots: false,
            arrows: true,
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
prodAdhSlider();

function prodSimilarSlider() {
    if($('[data-type="similar-slider"]').length > 0) {
        if($('[data-type="similar-slider"]').hasClass('slick-initialized')) $('[data-type="similar-slider"]').slick('unslick');
        $('[data-type="similar-slider"]').slick({
            dots: false,
            arrows: false,
            infinite: false,
            speed: 300,
            slidesToShow: 4,
            slidesToScroll: 1,
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
prodSimilarSlider();

