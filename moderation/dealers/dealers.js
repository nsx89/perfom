var format = 'old';
Modernizr.on('webp', function(result) {
    if (result) {
        format = 'webp';
    }
});

$(document).ready(function() {

    var referrer = document.referrer;
    if(referrer == '') referrer = '/moderation/';
    referrer += '#etc4';

    $('[data-type="pacc-back"]').each(function() {
        $(this).attr('href',referrer);
    })

    $('[href="#etc4"]').on('click',function() {
        $('[data-type="mod-res"]').hide();
        $('[data-type="md-dealer"]').show();
        let req = {};
        req.reg = $('[data-type="md-reg-val"]').attr('data-val');
        Object.assign(req, getSearchFilters());
        getDealers(req);
        $('.search-filter-wrap').css('display','flex');
    })
    $('[data-type="main-tab"]').on('click',function() {
        let url = window.location.href.split('?')[0];
        window.history.replaceState("", "", url);
    })




    //стартовые параметры
    let startUrl = window.location.href.split('?');
    if(startUrl[1]) {
        let params = startUrl[1].split('#')[0],
            paramsArr = params.split('&'),
            type = '',
            id = 'new',
            qwr = '',
            view = '',
            retail = '',
            subdealer = '',
            maindealer = '',
            published = '',
            nopublished = '';

        paramsArr.forEach(function(param) {
            let singleParamArr = param.split("=");
            if(singleParamArr[0] == 'type') {
                type = singleParamArr[1];
            }
            if(singleParamArr[0] == 'id') {
                id = singleParamArr[1];
            }
            if(singleParamArr[0] == 'qwr') {
                qwr = singleParamArr[1];
            }
            if(singleParamArr[0] == 'view') {
                view = singleParamArr[1];
            }
            if(singleParamArr[0] == 'retail') {
                retail = singleParamArr[1];
            }
            if(singleParamArr[0] == 'subdealer') {
                subdealer = singleParamArr[1];
            }
            if(singleParamArr[0] == 'maindealer') {
                maindealer = singleParamArr[1];
            }
            if(singleParamArr[0] == 'published') {
                published = singleParamArr[1];
            }
            if(singleParamArr[0] == 'nopublished') {
                nopublished = singleParamArr[1];
            }
        })


        if(type == 'edit') {
            window.prevUrl = window.location.href;
            $('[data-type="dealer"]').find('[data-type="md-edit-no"]').each(function() {
                $(this).attr('data-back',window.location.href);
            })
            //новая точка
            if(id == 'new') {
                showNewPoint();
            } else { //редактировать точку
                showEditDealer(id);
            }
        } else if(type == 'mod') {
            showEditDealer(id,'mod');
        } else if(type == 'saved') {
            showEditDealer(id,'saved');
        } else if(type == 'mod-spec-edit') {
            showEditDealer(id,'mod-spec-edit');
        } else if(type == 'moder') {
            if($('[data-val="moder"]').length > 0) {
                showModer();
            } else {
                changeUrl('type','');
                if(view == 'list') {
                    $('[data-val="list"]').addClass('active');
                    $('[data-type="list"]').addClass('active');
                    $('[data-type="wait-panel"]').show();
                } else {
                    $('[data-val="map"]').addClass('active');
                    $('[data-type="map"]').addClass('active');
                    $('[data-type="wait-panel"]').show();
                }
            }
        } else if(type == 'mod-spec') {
                showModerSpec();
        } else if(type == 'temp') {
            if($('[data-val="saved"]').length > 0) {
                showSaved();
            } else {
                changeUrl('type','');
                if(view == 'list') {
                    $('[data-val="list"]').addClass('active');
                    $('[data-type="list"]').addClass('active');
                    $('[data-type="wait-panel"]').show();
                } else {
                    $('[data-val="map"]').addClass('active');
                    $('[data-type="map"]').addClass('active');
                    $('[data-type="wait-panel"]').show();
                }
            }
        } else if(type == 'search') {
            if(qwr) {
                if(retail) $('.search-filter-wrap').find('[name="retail"]').attr('checked','checked');
                if(subdealer) $('.search-filter-wrap').find('[name="subdealer"]').attr('checked','checked');
                if(maindealer) $('.search-filter-wrap').find('[name="maindealer"]').attr('checked','checked');
                if(published) $('.search-filter-wrap').find('[name="published"]').attr('checked','checked');
                if(nopublished) $('.search-filter-wrap').find('[name="nopublished"]').attr('checked','checked');
                $('.search-filter-wrap').css('display','flex');
                if(view == 'list') {
                    $('[data-val="list"]').addClass('active');
                    $('[data-type="list"]').addClass('active');
                } else {
                    $('[data-val="map"]').addClass('active');
                    $('[data-type="map"]').addClass('active');
                }
                showSearchResults(true);
                $('[name="md-search"]').val(decodeURI(qwr));
                var req = {};
                req.q = decodeURI(qwr);
                Object.assign(req, getSearchFilters());
                $('[data-type="wait-panel"]').show();
                md_send_request(req);
            }
        } else  if(type == '') {
            if(retail) $('.search-filter-wrap').find('[name="retail"]').attr('checked','checked');
            if(subdealer) $('.search-filter-wrap').find('[name="subdealer"]').attr('checked','checked');
            if(maindealer) $('.search-filter-wrap').find('[name="maindealer"]').attr('checked','checked');
            if(published) $('.search-filter-wrap').find('[name="published"]').attr('checked','checked');
            if(nopublished) $('.search-filter-wrap').find('[name="nopublished"]').attr('checked','checked');
            $('.search-filter-wrap').css('display','flex');
            $('[data-type="md-view"]').each(function() {
                $(this).removeClass('active');
            })
            $('[data-val="cont"]').each(function() {
                $(this).removeClass('active');
            })
            if(view == 'list') {
                $('[data-val="list"]').addClass('active');
                $('[data-type="list"]').addClass('active');
                $('[data-type="wait-panel"]').show();
            } else {
                $('[data-val="map"]').addClass('active');
                $('[data-type="map"]').addClass('active');
                $('[data-type="wait-panel"]').show();
            }
        }
        if(view == 'list') {
            $('[data-val="list"]').addClass('active');
        } else {
            $('[data-val="map"]').addClass('active');
        }
    } else {
        $('[data-val="map"]').addClass('active');
        $('[data-type="map"]').addClass('active');
        $('[data-type="wait-panel"]').show();
        $('.search-filter-wrap').css('display','flex');
    }

    // карты
    var shown = false;
    ymaps.ready(function () {
        //общая карта
        if($('#mdMap').length > 0) {
            initMap();
            //если не поиск
            if(!startUrl[1] || startUrl[1] && startUrl[1].indexOf('search') == -1 && startUrl[1].indexOf('qwr') == -1) {
                let req = {};
                req.reg = $('[data-type="md-reg-val"]').attr('data-val');
                Object.assign(req, getSearchFilters());
                getDealers(req);
                //getDealers({reg: $('[data-type="md-reg-val"]').attr('data-val')});
            }
            if($('[data-type="map"]').hasClass('active')) {
                shown = true;
            } else {
                let regD = $('.md-bottom-panel').hasClass('active') ? true : false,
                    dealersQty = $('[data-type="dealer-list"]').find('.dealer-item').length;

                $('[data-val="map"]').on('click',function() {
                    if(shown === false) {
                        cetMapCenter(regD, dealersQty);
                        shown = true;
                        return false;
                    }
                })
                $('[data-type="md-edit-no"]').on('click',function() {
                    if(shown === false) {
                        cetMapCenter(regD, dealersQty);
                        shown = true;
                        return false;
                    }
                })

                //пересчет центра карты при добавлении класса 'active'
                var originalAddClassMethod = jQuery.fn.addClass;
                $.fn.addClass = function () {
                    var result = originalAddClassMethod.apply(this, arguments);
                    //Инициализируем событие смены класса
                    $(this).trigger('cssClassChanged');
                    return result;
                }
                $('[data-type="map"]').bind('cssClassChanged', function () {
                    if (!shown || myMap.getZoom() == 0) {
                        if ( $('[data-type="map"]').hasClass('active')) {
                            setTimeout(function () {
                                cetMapCenter(regD, dealersQty);
                                return false;
                            }, 100);
                            shown = true;
                        }

                    }
                });
                return false;
            }
        }
        //новая точка
        if($('#YMapsPointAdd').length > 0) {
            initNewDealerMap();

        }
    })

    // на карте/списком
    $('[data-type="md-view"]').on('click',function() {
        $('[data-type="md-view"]').each(function() {
            $(this).removeClass('active');
        })
        changeUrl('view',$(this).attr('data-val'));
        $(this).addClass('active');
        $('[data-val="cont"]').each(function() {
            $(this).removeClass('active');
        })
        $('[data-type="'+$(this).attr('data-val')+'"]').addClass('active');
        if($(this).attr('data-val') == 'map') correctZoomData();
        hideEditDealer();
    })

    $('.pacc-nav').on('click','[data-type="md-reg"]',function() {
        let iconGeo = $('[data-type="geo-open"]'),
            filtReg = $(this).find('[data-type="md-reg-val"]').html(),
            geoReg = $('[data-type="curr-reg"]').html();
        iconGeo.css('z-index','10');
        iconGeo.attr("data-type","md-geo-close");
        iconGeo.removeClass('icon-geo');
        iconGeo.addClass('icon-close');
        $('[data-type="curr-reg"]').html(filtReg);
        $('#dropdown-down').find('[data-type="choose-reg"]').each(function() {
            $(this).attr('data-type','md-choose-reg');
        })
        $('[data-type="reg-list"]').slideDown();
        if(typeof regScroll === 'undefined') {
            var regScroll = $('[data-type="reg-list-scroll"]').jScrollPane({
                showArrows: false,
                maintainPosition: false
            }).data('jsp');
        } else {
            regScroll.reinitialise();
        }
        $('body').addClass('disabled');

        $('#dropdown-down').on('click','[data-type="reg-list"] [data-type="md-choose-reg"]',function(e) {
            let regionId = $(this).attr('data-value'),
                regionName = $(this).html();
            e.preventDefault();
            $('[data-type="md-reg"]').find('[data-type="md-reg-val"]').attr('data-val',regionId).html(regionName);
            $('[data-type="md-reg"]').find('.e-new-sort-act').addClass('active');
            let req = {};
            req.reg = regionId;
            Object.assign(req, getSearchFilters());
            getDealers(req);
            changeUrl('reg',regionId);
            iconGeo.css('z-index','0');
            iconGeo.attr("data-type","geo-open");
            iconGeo.addClass('icon-geo');
            iconGeo.removeClass('icon-close');
            $('[data-type="reg-list"]').slideUp(function(){
                $('[data-type="curr-reg"]').html(geoReg);
            });
            $('body').removeClass('disabled');
            $('#dropdown-down').find('[data-type="md-choose-reg"]').each(function() {
                $(this).attr('data-type','choose-reg');
            })
        })

        $('header').on('click','[data-type="md-geo-close"]',function () {
            iconGeo.css('z-index','0');
            iconGeo.attr("data-type","geo-open");
            iconGeo.addClass('icon-geo');
            iconGeo.removeClass('icon-close');
            $('[data-type="reg-list"]').slideUp(function(){
                $('[data-type="curr-reg"]').html(geoReg);
            });
            $('body').removeClass('disabled');
            $('#dropdown-down').find('[data-type="md-choose-reg"]').each(function() {
                $(this).attr('data-type','choose-reg');
            })
        })
    })




    //поиск
    $('[name="md-search"]').keydown(function(event) {
        if($('[name="md-search"]').is(":focus")) {
            if(event.keyCode == 13) {
                event.preventDefault();
                return false;
            }
        }
    });
    $('[name="md-search"]').keyup(function(event) {
        if($('[name="md-search"]').is(":focus")) {
            if(event.keyCode != 13) {
                getSearchRequest();
            }
        }
    })
    $('.md-search').on('click','[data-type="md-btn-search"]',function(){
        if($(this).find('.icon-close').length > 0) {
            showSearchResults(false);
        }
    })
    $('.search-filter-wrap').on('change','.search-filter-item input',function () {
        if($('.md-search').find('.icon-close').length > 0) {
            getSearchRequest();
        } else {
            let req = {};
            req.reg = $('[data-type="md-reg-val"]').attr('data-val');
            Object.assign(req, getSearchFilters());
            getDealers(req);
        }
    })

    //сделать региональным дилером /убрать в поиске
    $('.md-content').on('click','[name="reg-dealer"]',function() {
        let checkbox = $(this),
            id = $(this).attr('data-val'),
            reg = $('[data-type="md-reg-val"]').attr('data-val'),
            preloader = '<div class="wait-reg-dealer"><img src="/img/preloader.gif" alt="Wait..." style="display:block;margin:0 auto;"></div>',
            act = 'remove';
        if(checkbox.closest('td').find('.wait-reg-dealer').length > 0) {
            checkbox.closest('td').find('.wait-reg-dealer').show();
        } else {
            checkbox.closest('.reg-dealer-wrap').after(preloader);
        }
        if($(this).prop("checked")) {
            act = 'add';
        }
        $.get('/moderation/dealers/ajax.php',{type:'reg-dealer',id:id, reg:reg, act:act}, function (data) {
            let reg = data;
            checkbox.closest('td').find('.wait-reg-dealer').hide();
            showSearchResults(false);
            //getDealers({reg:reg});
        });
    })

    //убрать рег. дилера на панели
    $('.md-bottom-panel').on('click','[data-type="reset-reg-dealer"]',function() {
        let preloader = '<div class="wait-reg-dealer"><img src="/img/preloader.gif" alt="Wait..." style="display:block;margin:0 auto;"></div>',
            reg = $('[data-type="md-reg-val"]').attr('data-val'),
            act = 'remove';
        $('.md-bottom-panel').find('[data-type="reset-reg-dealer"]').after(preloader);
        $.get('/moderation/dealers/ajax.php',{type:'reg-dealer',id:'all', reg:reg, act:act}, function (data) {
            let reg = data;
            //getDealers({reg:reg});
            let req = {};
            req.reg = reg;
            Object.assign(req, getSearchFilters());
            getDealers(req);
            $('.md-bottom-panel').find('.wait-reg-dealer').remove();
        });
    })

    //на модерации
    $('[data-val="moder"]').on('click',function() {
        showModer();
    })
    //модерация
    $('[data-val="moder-spec"]').on('click',function() {
        showModerSpec();
    })
    //сохранено
    $('[data-val="saved"]').on('click',function() {
        showSaved();
    })


    //загрузка изображений
    imgUploaderInit();

    //удаление изображений
    $('[data-type="drop-area-wrap"]').on('click','[ data-type="remove-md-photo"]',function () {
        let id = $(this).closest('.md-edit-photo').attr('data-id');
        removeUploadedImg(id);
    })
    var regList = $('[data-type="reg-list"]'),
        searchType = "reg-list",
        searchReg = regList.find('[data-type="curr-reg"]').attr('data-value');

    //выбрать город в форме
    //открыть выборку
    $('[data-type="point-reg"]').on('click',function() {
        $(this).closest('.md-edit-input-wrap').removeClass('error');
        let regVal = 'Не выбран',
            regBtn =  $('header').find('[data-type="geo-open"]');
        if($(this).find('[name="md-reg"]').val() != '') regVal = $(this).find('span').html();
        regBtn.attr("data-type","md-edit-geo-close");
        regBtn.removeClass('icon-geo');
        regBtn.addClass('icon-close');
        regList.attr('data-type','md-edit-reg-list').find('[data-type="curr-reg"]').attr('data-value',regVal).html(regVal);
        regList.slideDown();
        if(typeof regScroll === 'undefined') {
            var regScroll = $('[data-type="reg-list-scroll"]').jScrollPane({
                showArrows: false,
                maintainPosition: false
            }).data('jsp');
        } else {
            regScroll.reinitialise();
        }
        $('body').addClass('disabled');
        return false;
    })

    //выбрать регион
    $('header').on('click','[data-type="md-edit-reg-list"] [data-type="choose-reg"]',function(e) {
        e.preventDefault();
        let val = $(this).attr('data-value'),
            valName = $(this).html();
        $('[data-type="point-reg"]').find('[name="md-reg"]').val(val);
        $('[data-type="point-reg"]').find('span').addClass('choosed').html(valName);
        closeEditReg(searchReg);
        return false;
    })
    //закрыть выборку
    $('header').on('click','[data-type="md-edit-geo-close"]',function() {
        closeEditReg(searchReg);
        return false;
    })
    //добавить телефон
    //add phone
    $('.md-edit').on('click','[data-type="add-phone"]',function() {
        let wrap = $(this).closest('.md-edit-input-wrap');
        addPhoneLine(wrap);
    })
    //добавить персонал
    $('.md-edit-staff-item-add').on('click',function() {
        addStaffLine();
    })
    //удалить персонал
    $('.md-edit').on('click','[data-type="remove-staff"]',function() {
        if($('.md-edit-staff').find('.md-edit-staff-item').length > 1) {
            $(this).closest('.md-edit-staff-item').remove();
        } else {
            $(this).closest('.md-edit-staff-item').find('.md-edit-input-wrap').each(function() {
                if($(this).find('[type="radio"]').length > 0) {
                    $(this).find('input').each(function(i) {
                        if(i == 0) {
                            $(this).attr("checked","checked");
                        } else {
                            $(this).removeAttr("checked");
                        }
                    })
                } else {
                   $(this).find('input').val('');
                }
                if($(this).find('[type="tel"]').length > 1) {
                    $(this).find('[type="tel"]:not(:first)').remove();
                }
                $(this).removeClass('error');
            })
        }
    })


    // инпуты формы
    $('.md-edit').on('click','[data-type="form-data"]',function() {
        $('.md-edit-err').hide();
        $(this).closest('.md-edit-input-wrap').removeClass('error');
    })
    $('.md-edit').on('change','[data-type="form-data"]',function() {
        $('.md-edit-err').hide();
        $(this).closest('.md-edit-input-wrap').removeClass('error');
    })
    $('.md-edit').on('change','[data-type="form-data"]',function() {
        $('.md-edit-err').hide();
        $(this).closest('.md-edit-input-wrap').removeClass('error');
    })
    $('.md-edit-input-wrap-type').on('click','input',function() {
        if($(this).val() == 'subdealer') {
            $('.md-edit-input-wrap-contractor').show();
            $('.md-edit-input-wrap-contractor').find('input').attr('required','required');
        } else {
            $('.md-edit-input-wrap-contractor').removeClass('errorCntrctr').hide();
            $('.md-edit-input-wrap-contractor').find('input').removeAttr('required').val('');
        }
    })

    //контакт для заказа
    $('.md-edit').on('click','[name="md-order"]',function(){
        if($(this).prop('checked')) {
            $('.md-edit-cont-add-fields').addClass('active');
        } else {
            $('.md-edit-cont-add-fields').removeClass('active');
        }
    })

    var iconGeo = $('[data-type="geo-open"]'),
        geoReg = $('[data-type="curr-reg"]').html();

    //сделать дилером региона
    $('.md-edit').on('click','[data-type="md-add-reg"]',function() {
        let regVal = $(this).find('[name="md-reg"]').val() != '' ?  $(this).find('span').html() : '';
        iconGeo.css('z-index','10');
        iconGeo.attr("data-type","md-add-reg-geo-close");
        iconGeo.removeClass('icon-geo');
        iconGeo.addClass('icon-close');
        $('[data-type="header-geo-choose"]').css('visibility','hidden');
        $('#dropdown-down').find('[data-type="choose-reg"]').each(function() {
            $(this).attr('data-type','md-add-reg-list');
        })
        $('[data-type="reg-list"]').slideDown();
        if(typeof regScroll === 'undefined') {
            var regScroll = $('[data-type="reg-list-scroll"]').jScrollPane({
                showArrows: false,
                maintainPosition: false
            }).data('jsp');
        } else {
            regScroll.reinitialise();
        }
        $('body').addClass('disabled');
        return false;
    })

    //закрыть выборку
    $('header').on('click','[data-type="md-add-reg-geo-close"]',function () {
        iconGeo.css('z-index','0');
        iconGeo.attr("data-type","geo-open");
        iconGeo.addClass('icon-geo');
        iconGeo.removeClass('icon-close');
        $('[data-type="reg-list"]').slideUp(function(){
            $('[data-type="header-geo-choose"]').css('visibility','unset');
        });
        $('body').removeClass('disabled');
        $('#dropdown-down').find('[data-type="md-add-reg-list"]').each(function() {
            $(this).attr('data-type','choose-reg');
        })
        return false;
    })

    //выбрать регион
    $('#dropdown-down').on('click','[data-type="reg-list"] [data-type="md-add-reg-list"]',function(e) {
        let val = $(this).attr('data-value'),
            valName = $(this).html();
        e.preventDefault();
        let elem = '<div class="md-add-reg-inp">';
        elem += '<input type="hidden" id="md-reg-'+val+'" name="md-d-reg" data-type="form-data" value="'+val+'">';
        elem += '<label for="md-reg-'+val+'">'+valName+'</label>';
        elem += '<i class="icon-close" data-type="remove-d-reg"></i>';
        elem += '</div>';
        $('.md-edit').find('.md-add-reg-inp-wrap').append(elem);
        $('.md-edit').find('.md-edit-input-wrap-order-reg').show();
        iconGeo.css('z-index','0');
        iconGeo.attr("data-type","geo-open");
        iconGeo.addClass('icon-geo');
        iconGeo.removeClass('icon-close');
        $('[data-type="reg-list"]').slideUp(function(){
            $('[data-type="curr-reg"]').html(geoReg);
        });
        $('body').removeClass('disabled');
        $('#dropdown-down').find('[data-type="md-add-reg-list"]').each(function() {
            $(this).attr('data-type','choose-reg');
        })
        return false;
    })
    // удалить регион
    $('.md-edit').on('click','[data-type="remove-d-reg"]',function() {
        $(this).closest('.md-add-reg-inp').remove();
        if($('.md-edit').find('[name="md-d-reg"]').length == 0) {
            $('.md-edit').find('.md-edit-input-wrap-order-reg').hide();
            $('.md-edit').find('[name="md-only-reg"]').removeAttr('checked');
        }
        return false;
    })

    //сохранить форму
    $('[data-type="md-edit-yes"]').on('click',function() {
        let btn = $(this),
            err = checkEditForm(),
            form = $('.md-edit'),
            serData = form.serialize();
        btn.attr('disabled','disabled');
        let formData = collectFormData();
        console.log(formData);
        if(err > 0) {
            btn.removeAttr('disabled');
            $('.md-edit-err').html('Корректно заполните все обязательные поля').fadeIn();
            return false;
        } else if(serData == window.startFormData) {
            btn.removeAttr('disabled');
            $('.md-edit-err').html('Данные не изменились').fadeIn();
            return false;
        } else {
            let formData = collectFormData();
            formData['type'] = 'save';
            form.find('.md-edit-err').html('<img src="/img/preloader.gif" alt="Wait..." style="display:block;margin:0 auto;">').fadeIn();
            //console.log(formData);
            $.get('/moderation/dealers/ajax.php',formData, function (data) {
                data = JSON.parse(data);
                console.log(data);
                if(data['errQty'] > 0) {
                    let mess = '';
                    data['errMess'].forEach(function(item){
                        mess += item+'<br>';
                    })
                    $('.md-edit-err').html(mess).fadeIn();
                } else {
                    let folderId = data['id'] ? data['id'] : formData.id;
                    if(formData.id && formData.id != '') {
                        let mess = 'Изменения сохранены.'
                        $('.md-edit-err').html(mess).addClass('succ').fadeIn();
                    } else if(data['id']) {
                        let mess = 'Точка успешно сохранена.'
                        $('.md-edit-err').html(mess).addClass('succ').fadeIn();
                        changeUrl('type','edit');
                        changeUrl('id',data.id);
                        form.find('[name="md-id"]').val(data.id);
                        form.find('[data-type="drop-area"]').attr('data-folder',data.id);
                        if($('[name="md-folder"]').val() != '') $('[name="md-folder"]').val(data.id);
                        $('.md-panel-btn').each(function() {
                            $(this).removeClass('not-active');
                        })
                        $('[data-type="md-edit-rem"]').fadeIn();
                    }
                    if(folderId) {
                        if($('.md-edit-input-wrap-photo').find('[data-fancybox="md-edit-photo"]').length > 0) {
                            $('.md-edit-input-wrap-photo').find('[data-fancybox="md-edit-photo"]').each(function() {
                                let editPhoto = $(this),
                                    editPhotoName = editPhoto.attr('href').split('/');
                                editPhotoName = editPhotoName.pop();
                                let newHref = '/img/dealers/'+folderId+'/'+editPhotoName;
                                editPhoto.attr('href',newHref);
                                editPhoto.css('background-image','url('+newHref+')');
                            })
                        }
                    }
                    window.startFormData = $('.md-edit').serialize();
                }
                setTimeout(function() {
                    $('.md-edit-err').html('').removeClass('succ').fadeOut();
                },5000)
                btn.removeAttr('disabled');
            })
        }
    })

    // отменить форму
    $('[data-type="md-edit-no"]').on('click',function() {
        //если новая точка
        if($('[data-val="dealer"]').find('.icon-close').length > 0) {
            showNewPoint('close');
        } else {
        //если сущ. дилер
            if($(this).attr('data-back') && $(this).attr('data-back') != window.location.href) {
                $('[data-type="dealer"]').removeClass('active');
                $('[data-type="'+window.activeCont+'"]').addClass('active');
                window.history.replaceState("", "", $(this).attr('data-back'));
                if(window.activeCont == 'map' || window.activeCont == 'list') {
                    if($('.md-bottom-panel').hasClass('active')) $('.md-bottom-panel').show();
                }
            } else {
                $('[data-type="dealer"]').removeClass('active');
                $('[data-type="map"]').addClass('active');
            }
            clearEditForm();
        }

    })

    window.startFormData = $('.md-edit').serialize();
    window.prevUrl = window.location.href;
    $('[data-val="cont"]').each(function() {
        if($(this).hasClass('active')) {
            window.activeCont = $(this).attr('data-type');
        }
    })

    //новая точка
    $('[data-val="dealer"]').on('click',function() {
        //showNewPoint();
    })

    //показать дилера
    $('.md-content').on('click','[data-type="dealer-show"]',function(e) {
        e.preventDefault();
        window.prevUrl = window.location.href;
        $('[data-type="dealer"]').find('[data-type="md-edit-no"]').each(function() {
            $(this).attr('data-back',window.location.href);
        })
        $('[data-type="dealer"]').find('.pacc-back').show();
        let id = $(this).attr('data-id');
        showEditDealer(id);
        changeUrl('type','edit');
        changeUrl('id',id);
        if($('.md-bottom-panel').hasClass('active')) {
            $('.md-bottom-panel').hide();
        }

    })

    //закрыть попап
    $('[data-type="close-md-popup"]').on('click',function() {
        closeMdPopup();
    })
    $(document).mousedown(function (e) {
        var container = $('[data-type="md-popup"]');
        if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(event.target).is('[data-type="md-popup"]')){
            closeMdPopup();
        }
    });
    $('.md-content').on('click','[data-type="md-rem-ok"]',function() {
        closeMdPopup();
    })


    //удалить форму
    $('.md-content').on('click','[data-type="md-edit-rem"]',function() {
        let mess = '<div class="clear-all-window-title">Подтвердите действие</div>';
        mess += '<div class="clear-all-window-qst">Вы уверены, что хотите удалить точку?</div>';
        mess += '<div class="clear-all-window-btns">';
        mess += '<div class="clear-all-yes" data-type="md-rem-y">Удалить</div>';
        mess += '<div class="clear-all-no" data-type="md-rem-no">Отмена</div>';
        mess += '</div>';
        $('[data-type="md-popup"]').find('.md-popup-cont').html(mess);
        $('[data-type="md-popup"]').fadeIn();
        $('[data-type="overlay"]').fadeIn();
        /*$('[data-type="md-popup"]').on('click','[data-type="md-rem-ok"]',function() {
            window.location.reload();
        })*/
    })
    //удалить форму - отмена
    $('[data-type="md-popup"]').on('click','[data-type="md-rem-no"]',function() {
        closeMdPopup();
    })
    //удалить форму - удалить
    $('[data-type="md-popup"]').on('click','[data-type="md-rem-y"]',function() {
        $(this).attr('disabled','disabled');
        let id = $('.md-edit').find('[name="md-id"]').val();
        $('[data-type="md-popup"]').find('.md-popup-preloader').fadeIn();
        $.get('/moderation/dealers/ajax.php',{type:'remove',id:id}, function (data) {
            data = JSON.parse(data);
            if(data['errQty'] > 0) {
                $('[data-type="md-popup"]').find('.md-popup-preloader').hide();
                $('[data-type="md-popup"]').hide();
                let mess = '<div class="clear-all-window-title">Результат действия</div>';
                mess += '<div class="clear-all-window-qst error">При удалении произошла ошибка. <br>Попробуйте ещё раз.</div>';
                mess += '<div class="clear-all-window-btns">';
                mess += '<div class="clear-all-yes" data-type="md-rem-ok">OK</div>';
                mess += '</div>';
                $('[data-type="md-popup"]').find('.md-popup-cont').html(mess);
                $('[data-type="md-popup"]').fadeIn();
            } else {
                $('[data-type="md-popup"]').find('.md-popup-preloader').hide();
                $('[data-type="md-popup"]').hide();
                let mess = '<div class="clear-all-window-title">Результат действия</div>';
                mess += '<div class="clear-all-window-qst">Точка была успешно удалена</div>';
                mess += '<div class="clear-all-window-btns">';
                mess += '<div class="clear-all-yes" data-type="md-rem-ok">OK</div>';
                mess += '</div>';
                $('[data-type="md-popup"]').find('.md-popup-cont').html(mess);
                $('[data-type="md-popup"]').fadeIn();
                let msg = 'Данная точка удалена.'
                window.startFormData = $('.md-edit').serialize();
                //$('.md-edit').hide();
                let btn = '<a href="' + $('[data-val="first-back"]').attr('href') + '" class="md-edit-mess-back">Вернуться</a>';
                $('.md-edit-not-found').find('p').html(msg);
                $('.md-edit-not-found').find('p').after(btn);
                $('.md-edit-not-found').show();
                $('[data-val="md-edit-cont"]').each(function() {
                    $(this).hide();
                })
                $('*[data-type="om-personal-tabs"]').unstick();
                applyOMSticky();
                let top = $('header').outerHeight();
                $('body,html').animate({scrollTop: top}, 100);
            }

        })

    })

    // отправить на модерацию - изменить/сохранить
    $('.md-content').on('click','[data-type="md-edit-mod"]',function() {
        let btn = $(this),
            err = checkEditForm(),
            form = $('.md-edit'),
            serData = form.serialize();
        btn.attr('disabled', 'disabled');
        console.log(collectFormData());
        if(err > 0) {
            btn.removeAttr('disabled');
            $('.md-edit-err').html('Корректно заполните все обязательные поля').fadeIn();
            return false;
        } else if(serData == window.startFormData) {
            btn.removeAttr('disabled');
            $('.md-edit-err').html('Данные не изменились').fadeIn();
            return false;
        } else {
            let formData = collectFormData();
            formData['type'] = 'mod';
            form.find('.md-edit-err').html('<img src="/img/preloader.gif" alt="Wait..." style="display:block;margin:0 auto;">').fadeIn();
            $.get('/moderation/dealers/ajax.php',formData, function (data) {
                data = JSON.parse(data);
                if(data['errQty'] > 0) {
                    let mess = '';
                    data['errMess'].forEach(function(item){
                        mess += item+'<br>';
                    })
                    $('.md-edit-err').html(mess).fadeIn();
                } else {
                    let mess = 'Информация отправлена на модерацию. <br>Изменения будут отображены на сайте после модерации.';
                    let btn = '<a href="' + $('[data-val="first-back"]').attr('href') + '" class="md-edit-mess-back">Вернуться</a>';
                    //$('.md-edit-err').html(mess).addClass('succ').fadeIn();
                    window.startFormData = $('.md-edit').serialize();
                    //$('.md-edit').hide();
                    $('.md-edit-not-found').find('p').html(mess);
                    $('.md-edit-not-found').find('p').after(btn);
                    $('.md-edit-not-found').show();
                    $('[data-val="md-edit-cont"]').each(function() {
                        $(this).hide();
                    })
                    $('*[data-type="om-personal-tabs"]').unstick();
                    applyOMSticky();
                    let top = $('header').outerHeight();
                    $('body,html').animate({scrollTop: top}, 100);
                }
            })
        }
    })

    // отправить на модерацию - удалить
    $('.md-content').on('click','[data-type="md-rem-mod"]',function() {
        let btn = $(this),
            form = $('.md-edit'),
            id = form.find('[name="md-id"]').val();
        btn.attr('disabled', 'disabled');
        form.find('.md-edit-err').html('<img src="/img/preloader.gif" alt="Wait..." style="display:block;margin:0 auto;">').fadeIn();
        $.get('/moderation/dealers/ajax.php',{id:id,type:'mod-remove'}, function (data) {
                data = JSON.parse(data);
                if(data['errQty'] > 0) {
                    let mess = '';
                    data['errMess'].forEach(function(item){
                        mess += item+'<br>';
                    })
                    $('.md-edit-err').html(mess).fadeIn();
                } else {
                    let mess = 'Запрос на удаление контакта отправлен на&nbsp;модерацию. <br>Изменения будут отображены на&nbsp;сайте после модерации.';
                    let btn = '<a href="' + $('[data-val="first-back"]').attr('href') + '" class="md-edit-mess-back">Вернуться</a>';
                    //$('.md-edit-err').html(mess).addClass('succ').fadeIn();
                    //$('.md-edit').hide();
                    $('.md-edit-not-found').find('p').html(mess);
                    $('.md-edit-not-found').find('p').after(btn);
                    $('.md-edit-not-found').show();
                    $('[data-val="md-edit-cont"]').each(function() {
                        $(this).hide();
                    })
                    $('*[data-type="om-personal-tabs"]').unstick();
                    applyOMSticky();
                    let top = $('header').outerHeight();
                    $('body,html').animate({scrollTop: top}, 100);
                }
            })
    })

    // принять модерацию
    $('.md-content').on('click','[data-type="md-mod-yes"]',function() {
        let btn = $(this),
            form = $('.md-edit'),
            id = form.find('[name="md-id"]').val();
        btn.attr('disabled', 'disabled');
        form.find('.md-edit-err').html('<img src="/img/preloader.gif" alt="Wait..." style="display:block;margin:0 auto;">').fadeIn();
        $.get('/moderation/dealers/ajax.php',{id:id,type:'mod-accept'}, function (data) {
            data = JSON.parse(data);
            if(data['errQty'] > 0) {
                let mess = '';
                data['errMess'].forEach(function(item){
                    mess += item+'<br>';
                })
                $('.md-edit-err').html(mess).fadeIn();
            } else {
                let mess = 'Изменения приняты.';
                let btn = '<a href="' + $('[data-val="first-back"]').attr('href') + '" class="md-edit-mess-back">Вернуться</a>';
                //$('.md-edit-err').html(mess).addClass('succ').fadeIn();
                //$('.md-edit').hide();
                $('.md-edit-not-found').find('p').html(mess);
                $('.md-edit-not-found').find('p').after(btn);
                $('.md-edit-not-found').show();
                $('[data-val="md-edit-cont"]').each(function() {
                    $(this).hide();
                })
                $('*[data-type="om-personal-tabs"]').unstick();
                applyOMSticky();
                let top = $('header').outerHeight();
                $('body,html').animate({scrollTop: top}, 100);
            }
        })
    })

    // отклонить модерацию
    $('.md-content').on('click','[data-type="md-mod-no"]',function() {
        let btn = $(this),
            form = $('.md-edit'),
            id = form.find('[name="md-id"]').val();
        btn.attr('disabled', 'disabled');
        form.find('.md-edit-err').html('<img src="/img/preloader.gif" alt="Wait..." style="display:block;margin:0 auto;">').fadeIn();
        $.get('/moderation/dealers/ajax.php',{id:id,type:'mod-reject'}, function (data) {
            data = JSON.parse(data);
            if(data['errQty'] > 0) {
                let mess = '';
                data['errMess'].forEach(function(item){
                    mess += item+'<br>';
                })
                $('.md-edit-err').html(mess).fadeIn();
            } else {
                let mess = 'Изменения отклонены.';
                let btn = '<a href="' + $('[data-val="first-back"]').attr('href') + '" class="md-edit-mess-back">Вернуться</a>';
                //$('.md-edit-err').html(mess).addClass('succ').fadeIn();
                //$('.md-edit').hide();
                $('.md-edit-not-found').find('p').html(mess);
                $('.md-edit-not-found').find('p').after(btn);
                $('.md-edit-not-found').show();
                $('[data-val="md-edit-cont"]').each(function() {
                    $(this).hide();
                })
                $('*[data-type="om-personal-tabs"]').unstick();
                applyOMSticky();
                let top = $('header').outerHeight();
                $('body,html').animate({scrollTop: top}, 100);
            }
        })
    })

    // промежуточное сохранение
    $('.md-content').on('click','[data-type="md-temp-save"]',function() {
        let btn = $(this),
            form = $('.md-edit'),
            err = checkEditForm(),
            serData = form.serialize();
        btn.attr('disabled', 'disabled');
        if(err > 0) {
            btn.removeAttr('disabled');
            $('.md-edit-err').html('Корректно заполните все обязательные поля').fadeIn();
            return false;
        } else if(serData == window.startFormData) {
            btn.removeAttr('disabled');
            $('.md-edit-err').html('Данные не изменились').fadeIn();
            return false;
        } else {
            let formData = collectFormData();
            formData['type'] = 'mod-save';
            form.find('.md-edit-err').html('<img src="/img/preloader.gif" alt="Wait..." style="display:block;margin:0 auto;">').fadeIn();
            $.get('/moderation/dealers/ajax.php',formData, function (data) {
                data = JSON.parse(data);
                if(data['errQty'] > 0) {
                    let mess = '';
                    data['errMess'].forEach(function(item){
                        mess += item+'<br>';
                    })
                    $('.md-edit-err').html(mess).fadeIn();
                } else {
                    let folderId = data['id'] ? data['id'] : formData.id;
                    let mess = 'Промежуточное сохранение выполнено.';
                    let desc = '<p style="color:#4e4e4e">Сохраненные изменения видны только вам.</p>';
                    desc += '<p style="color:#4e4e4e">Перед отправкой на&nbsp;модерацию нажмите кнопку "Сохранить", <br>если вас есть несохраненные изменения, которые вы хотите отправить на&nbsp;модераию.</p>';
                    desc += '<p>Изменения будут отображены на сайте после модерации.</p>';
                    $('.md-edit-err').html(mess).addClass('succ').fadeIn();
                    $('.md-edit-mess').html(desc);
                    changeUrl('type','saved');
                    changeUrl('id',data.id);
                    if(form.find('[name="md-id"]').val() == '') {
                        $('[data-type="md-rem-mod"]').attr('data-type','md-temp-remove');
                    }
                    $('[data-type="md-edit-mod"]').attr('data-type','md-temp-mod');

                    form.find('[name="md-id"]').val(data.id);
                    form.find('[data-type="drop-area"]').attr('data-folder',data.id);
                    if($('[name="md-folder"]').val() != '') $('[name="md-folder"]').val(data.id);
                    $('[data-type="md-temp-remove"]').show();
                    //window.startFormData = $('.md-edit').serialize();
                    if(folderId) {
                        if($('.md-edit-input-wrap-photo').find('[data-fancybox="md-edit-photo"]').length > 0) {
                            $('.md-edit-input-wrap-photo').find('[data-fancybox="md-edit-photo"]').each(function() {
                                let editPhoto = $(this),
                                    editPhotoName = editPhoto.attr('href').split('/');
                                editPhotoName = editPhotoName.pop();
                                let newHref = '/img/dealers/'+folderId+'/'+editPhotoName;
                                editPhoto.attr('href',newHref);
                                editPhoto.css('background-image','url('+newHref+')');
                            })
                        }
                    }
                    setTimeout(function() {
                        $('.md-edit-err').html('').removeClass('succ').fadeOut();
                    },5000)
                    btn.removeAttr('disabled');
                }
            })
        }
    })

    //промежуточное сохранение - удалить
    $('.md-content').on('click','[data-type="md-temp-remove"]',function() {
        let mess = '<div class="clear-all-window-title">Подтвердите действие</div>';
        mess += '<div class="clear-all-window-qst">Вы уверены, что хотите удалить точку?</div>';
        mess += '<div class="clear-all-window-btns">';
        mess += '<div class="clear-all-yes" data-type="md-saved-rem-y">Удалить</div>';
        mess += '<div class="clear-all-no" data-type="md-rem-no">Отмена</div>';
        mess += '</div>';
        $('[data-type="md-popup"]').find('.md-popup-cont').html(mess);
        $('[data-type="md-popup"]').fadeIn();
        $('[data-type="overlay"]').fadeIn();
    })

    // промежуточное сохранение - точно удалить
    $('[data-type="md-popup"]').on('click','[data-type="md-saved-rem-y"]',function() {
        $(this).attr('disabled','disabled');
        let id = $('.md-edit').find('[name="md-id"]').val();
        $('[data-type="md-popup"]').find('.md-popup-preloader').fadeIn();
        $.get('/moderation/dealers/ajax.php',{type:'remove-saved',id:id}, function (data) {
            data = JSON.parse(data);
            if(data['errQty'] > 0) {
                $('[data-type="md-popup"]').find('.md-popup-preloader').hide();
                $('[data-type="md-popup"]').hide();
                let mess = '<div class="clear-all-window-title">Результат действия</div>';
                mess += '<div class="clear-all-window-qst error">При удалении произошла ошибка. <br>Попробуйте ещё раз.</div>';
                mess += '<div class="clear-all-window-btns">';
                mess += '<div class="clear-all-yes" data-type="md-rem-ok">OK</div>';
                mess += '</div>';
                $('[data-type="md-popup"]').find('.md-popup-cont').html(mess);
                $('[data-type="md-popup"]').fadeIn();
            } else {
                $('[data-type="md-popup"]').find('.md-popup-preloader').hide();
                $('[data-type="md-popup"]').hide();
                let mess = '<div class="clear-all-window-title">Результат действия</div>';
                mess += '<div class="clear-all-window-qst">Сохраненная точка была успешно удалена</div>';
                mess += '<div class="clear-all-window-btns">';
                mess += '<div class="clear-all-yes" data-type="md-rem-ok">OK</div>';
                mess += '</div>';
                $('[data-type="md-popup"]').find('.md-popup-cont').html(mess);
                $('[data-type="md-popup"]').fadeIn();
                clearEditForm();
                let msg = 'Сохраненная точка удалена.';
                let btn = '<a href="' + $('[data-val="first-back"]').attr('href') + '" class="md-edit-mess-back">Вернуться</a>';
                //$('.md-edit-err').html(mess).addClass('succ').fadeIn();
                window.startFormData = $('.md-edit').serialize();
                //$('.md-edit').hide();
                $('.md-edit-not-found').find('p').html(msg);
                $('.md-edit-not-found').find('p').after(btn);
                $('.md-edit-not-found').show();
                $('[data-val="md-edit-cont"]').each(function() {
                    $(this).hide();
                })
                $('*[data-type="om-personal-tabs"]').unstick();
                applyOMSticky();
                let top = $('header').outerHeight();
                $('body,html').animate({scrollTop: top}, 100);
            }
        })

    })

    // промежуточное сохранение - отправить на модерацию
    $('.md-content').on('click','[data-type="md-temp-mod"]',function() {
        let btn = $(this),
            form = $('.md-edit'),
            err = checkEditForm(),
            id = form.find('[name="md-id"]').val();
        btn.attr('disabled', 'disabled');
        if(err > 0) {
            btn.removeAttr('disabled');
            $('.md-edit-err').html('Корректно заполните все обязательные поля').fadeIn();
            return false;
        } else {
            form.find('.md-edit-err').html('<img src="/img/preloader.gif" alt="Wait..." style="display:block;margin:0 auto;">').fadeIn();
            $.get('/moderation/dealers/ajax.php',{id:id,type:'mod-saved'}, function (data) {
                data = JSON.parse(data);
                if(data['errQty'] > 0) {
                    let mess = '';
                    data['errMess'].forEach(function(item){
                        mess += item+'<br>';
                    })
                    $('.md-edit-err').html(mess).fadeIn();
                } else {
                    let mess = 'Информация отправлена на&nbsp;модерацию. <br>Изменения будут отображены на&nbsp;сайте после&nbsp;модерации.';
                    let btn = '<a href="' + $('[data-val="first-back"]').attr('href') + '" class="md-edit-mess-back">Вернуться</a>';
                    //$('.md-edit-err').html(mess).addClass('succ').fadeIn();
                    //window.startFormData = $('.md-edit').serialize();
                    //$('.md-edit-contact-top-left-cont').hide();
                    $('[ data-val="md-edit-cont"]').each(function() {
                        $(this).hide();
                    })
                    $('.md-edit-not-found').find('p').html(mess);
                    $('.md-edit-not-found').find('p').after(btn);
                    $('.md-edit-not-found').show();
                    $('*[data-type="om-personal-tabs"]').unstick();
                    applyOMSticky();
                    let top = $('header').outerHeight();
                    $('body,html').animate({scrollTop: top}, 100);
                }
            })
        }
    })

    //копировать карточку
    $('.md-content').on('click','[data-val="edit-copy"]',function() {
        $('[data-type="copy-window"]').fadeIn();
        $('[data-type="overlay"]').fadeIn();
    })
    $('[data-type="copy-window"]').on('click','[data-type="copy-window-yes"]',function() {
        let data = [];
        $('[data-type="copy-window"]').find('.copy-input-wrap').each(function() {
            let input = $(this).find('input');
            if(input.prop('checked')) {
                data.push(input.val());
            }
        })
        copyEditForm(data);
        let mess = 'Точка успешно скопирована.';
        $('.md-edit-err').html(mess).addClass('succ').fadeIn();
        changeUrl('type','edit');
        changeUrl('id','new');
        $('[data-type="md-edit-rem"]').hide();
        $('[data-type="md-rem-mod"]').hide();
        setTimeout(function(){
            $('.md-edit-err').html(mess).addClass('succ').fadeOut();
        },5000);
        window.startFormData = '';
        $('[data-type="copy-window"]').fadeOut();
        $('[data-type="overlay"]').fadeOut();
    })
    $('[data-type="copy-window"]').on('click','[data-type="close-copy-window"]',function() {
        $('[data-type="copy-window"]').fadeOut();
        $('[data-type="overlay"]').fadeOut();
    })
    $(document).mousedown(function (e) {
        var container = $('[data-type="copy-window"]');
        if (container.css('display')!='none' && container.has(e.target).length === 0 && !$(event.target).is('[data-type="copy-window"]')){
            $('[data-type="copy-window"]').fadeOut();
            $('[data-type="overlay"]').fadeOut();
        }
    });




    //подбор главного дилера
    var mainDealers = [];
    $.get('/moderation/dealers/ajax.php',{type:'get-main'}, function (data) {
        data = data = JSON.parse(data);
        mainDealers = data.map(function (n) { return { label: n }});
    })
    var allowedChars = new RegExp(/^[\wа-яА-ЯёЁ\s]+$/)

    function charsAllowed(value) {
        return allowedChars.test(value);
    }

    if(typeof autocomplete !== "undefined") {
        autocomplete({
            input: $('.md-edit').find('[name="md-contractor"]')[0],
            minLength: 1,
            onSelect: function (item, inputfield) {
                inputfield.value = item.label;
                $('.md-edit').find('[name="md-contractor"]').closest('.md-edit-input-wrap').removeClass('errorCntrctr');
            },
            fetch: function (text, callback) {
                var match = text.toLowerCase();
                callback(mainDealers.filter(function(n) { return n.label.toLowerCase().indexOf(match) !== -1; }));
            },
            render: function(item, value) {
                var itemElement = document.createElement("div");
                if (charsAllowed(value)) {
                    var regex = new RegExp(value, 'gi');
                    var inner = item.label.replace(regex, function(match) { return "<strong>" + match + "</strong>" });
                    itemElement.innerHTML = inner;
                } else {
                    itemElement.textContent = item.label;
                }
                return itemElement;
            },
            emptyMsg: "Совпадений не найдено",
            showErr: function(){
                $('.md-edit').find('[name="md-contractor"]').closest('.md-edit-input-wrap').addClass('errorCntrctr');
            },
            hideErr: function() {
                $('.md-edit').find('[name="md-contractor"]').closest('.md-edit-input-wrap').removeClass('errorCntrctr');
            },
            customize: function(input, inputRect, container, maxHeight) {
                if (maxHeight < 100) {
                    container.style.top = "";
                    container.style.bottom = (window.innerHeight - inputRect.bottom + input.offsetHeight) + "px";
                    container.style.maxHeight = "140px";
                }
            },
            disableAutoSelect: true,
        })
    }

    //кнопка вернуться
    /*$('.md-content-dealer').on('click','[data-type="pacc-back"]',function() {
        if (document.referrer == "") {
            window.location.href = '/moderation/#etc4'
        } else {
            history.back()
        }
    })*/














}) // end of document.ready
//добавить персонал
function addStaffLine() {
    let wrap = $('.md-edit-staff'),
        newItem = wrap.find('.md-edit-staff-item').last().clone(),
        nmbr = wrap.find('.md-edit-staff-item').length;
    newItem.find('.md-edit-input-wrap').each(function() {
        if($(this).find('[type="radio"]').length > 0) {
            $(this).find('[type="radio"]').each(function(i) {
                let oldId = $(this).attr("id"),
                    re = /-\d/,
                    newId = oldId.split(re),
                    newName = $(this).attr('name').split(re);
                newId = newId[0] + '-' + nmbr + i;
                newName = newName[0] + '-' + nmbr;
                $(this).attr('id',newId);
                $(this).attr('name',newName);
                $(this).closest('.md-edit-input-wrap').find('[for="'+oldId+'"]').attr("for",newId);
                if(i == 0) {
                    $(this).attr("checked","checked");
                } else {
                    $(this).removeAttr("checked");
                }
            })
        } else {
            let id = $(this).find('input').attr('id').split('-');
            id = id[0] + '-' + nmbr;
            $(this).find('input').attr('id',id).val('');
            $(this).find('label').attr("for",id);
        }
        if($(this).find('[type="tel"]').length > 1) {
            $(this).find('[type="tel"]:not(:first)').remove();
        }
    });
    wrap.append(newItem);
}

//закрыть попап
function closeMdPopup() {
    $('[data-type="overlay"]').fadeOut();
    $('[data-type="md-popup"]').fadeOut(function(){
        $('[data-type="md-popup"]').find('.md-popup-cont').html('');
    });
    $('[data-type="md-popup"]').find('.md-popup-preloader').hide();
}

//показать нижнюю панель
function showBottomPanel() {
    if($('.md-bottom-panel').hasClass('active')) {
        if($('[data-type="map"]').hasClass('active') || $('[data-type="list"]').hasClass('active')) {
            $('.md-bottom-panel').show();
        } else {
            $('.md-bottom-panel').hide();
        }
    }
    return false;
}

//добавить/убрать параметр в url
function changeUrl(name,val) {
    let url = window.location.href.split('#')[0],
        hash = window.location.hash,
        urlArr = url.split('?'),
        params = '';
    url = urlArr[0];
    if(urlArr[1]) {
        let paramsArr = urlArr[1].split('&');
        paramsArr.forEach(function(param) {
            let singleParamArr = param.split("=");
            if(singleParamArr[0] != name) {
                params += '&' + singleParamArr[0]+'='+singleParamArr[1];
            }
        })
    }
    if(val != '') params += '&' + name+'='+val;
    if(params != '') {
        params = '?'+params.slice(1);
    }
    url += params;
    if(hash) url += hash;
    console.log(url);
    window.history.replaceState("", "", url);
    return false;
}

//добавить строку для телефона
function addPhoneLine(wrap) {
    let nmbr = wrap.find('input').length,
        newId = wrap.find('input').attr('id').split('-');
    newId = newId[0] + '-' +nmbr;
    let input = wrap.find('input').last().clone().val('').attr('id',newId);
    wrap.append(input);
}

//-------------- МОДЕРАЦИЯ --------------------
function showModer() {
    let act = 'open',
        btn = $('[data-val="moder"]');
    if ($('.md-panel').find('[data-val="moder"]').find('.icon-close').length > 0) act = 'close';
    if (act == 'open') {
        hideEditDealer();
        //$('.search-filter-wrap').css('display','none');
        $('.md-panel-btn').each(function () {
            if (!$(this).hasClass('md-moder')) {
                $(this).addClass('not-active');
            }
        })
        $('.md-bottom-panel').hide();
        $('[data-val="cont"]').each(function () {
            $(this).removeClass('active');
        })
        $('[data-type="moder"]').addClass('active');
        btn.find('span').html('<i class="icon-close"></i>');
        changeUrl('type', 'moder');
        changeUrl('view', '');
    } else {
        $('.search-filter-wrap').css('display','flex');
        $('.md-panel-btn').each(function () {
            $(this).removeClass('not-active');
        })
        if ($('.md-bottom-panel').hasClass('active')) $('.md-bottom-panel').show();
        $('[data-type="moder"]').removeClass('active');
        $('[data-type="' + $('.md-view-wrap .active').attr('data-val') + '"]').addClass('active');
        changeUrl('view', $('.md-view-wrap .active').attr('data-val'));
        changeUrl('type', '');
        btn.find('span').html(btn.attr('data-qty'));
        correctZoomData();
    }
}

//-------------- МОДЕРАЦИЯ ДЛЯ СПЕЦИАЛИСТА ---------
function showModerSpec() {
    let act = 'open',
        btn = $('[data-val="moder-spec"]');
    if ($('.md-panel').find('[data-val="moder-spec"]').hasClass('active')) act = 'close';
    if (act == 'open') {
        hideEditDealer();
        //$('.search-filter-wrap').css('display','none');
        $('.md-panel-btn').each(function () {
            if (!$(this).hasClass('md-moder')) {
                $(this).addClass('not-active');
            }
        })
        $('.md-bottom-panel').hide();
        $('[data-val="cont"]').each(function () {
            $(this).removeClass('active');
        })
        $('[data-type="moder-spec"]').addClass('active');
        btn.addClass('active');
        changeUrl('type', 'mod-spec');
        changeUrl('view', '');
        $.get('/moderation/dealers/ajax.php',{type:'mod-spec'}, function (data) {
            $('.md-point-preloader').hide();
            data = JSON.parse(data);
            if (data.err.qty == 0) {
                let cont = getModSpecRows(data.dealers);
                $('[data-type="moder-spec"]').find('tbody').html(cont);
            } else {
                if (data.err.mess == 'Дилеры не найдены') {
                    let cont = '<tr><td colspan="6" class="mod-spec-not-found">Дилеры не найдены</td></tr>';
                    $('[data-type="moder-spec"]').find('tbody').html(cont);
                } else {
                    console.log(data.err.mess);
                }
            }
        });

    } else {
        $('.search-filter-wrap').css('display','flex');
        $('.md-panel-btn').each(function () {
            $(this).removeClass('not-active');
        })
        if ($('.md-bottom-panel').hasClass('active')) $('.md-bottom-panel').show();
        $('[data-type="moder-spec"]').removeClass('active');
        $('[data-type="' + $('.md-view-wrap .active').attr('data-val') + '"]').addClass('active');
        changeUrl('view', $('.md-view-wrap .active').attr('data-val'));
        changeUrl('type', '');
        btn.removeClass('active');
        correctZoomData();
    }
}

function getModSpecRows(dealers) {
    let cont = '';
    dealers.forEach(function(item,i) {
        console.log(item);
        cont += '<tr>';
        cont += '<td>';
        cont += '<div class="md-list-name">';
        cont += '<div class="md-list-name-top">';
        cont += '<div class="md-list-name-top-cont">';
        cont += '<span>'+(parseInt(i)+1)+'.</span>';
        if(item.point != '') {
            cont += item.point;
            cont += '<br>';
        }
        if(item.org != '') cont += item.org;
        cont += '</div>';
        if(item.main == 'Y') {
            cont += '<div class="md-main-dealer">главный дилер</div>';
        }
        if(item.orderDealer == 'Y') {
            cont += '<div class="md-main-dealer md-order-dealer">контакт для заказа</div>';
        }
        cont += '</div>';
        cont += '<div class="md-list-name-bottom">';
        cont += '<div class="dealer-status ' + item.statClass + '">' + item.stat + '</div>';
        cont += '</div>';
        cont += '</div>';
        cont += '</td>';

        cont += '<td>';
        cont += '<div class="mod-spec-act">';
        cont += '<div class="mod-spec-act-top">';
        cont += item.modAct+'<br>';
        cont += '<span class="mod-date">'+item.modActTime+'</span>';
        cont += '</div>';
        cont += '<div class="mod-spec-act-bottom">';
        cont += '<div class="mod-spec-act-res '+item.resultClass+'">'+item.result+'</div>';
        cont += '</div>';
        cont += '</div>';
        cont += '</td>';

        cont += '<td>';
        cont += '<a href="http://'+item.url+'" target="_blank">'+item.url+'</a>';
        cont += '</td>';

        cont += '<td>';
        if(item.addr != '') {
            //tr += '<p class="dealer-list-addr">' + item.addr + '</p>';
            if(item.addr != '') {
                cont += '<p class="dealer-list-addr">';
                if(item.city) cont += 'г. ' + item.city + '<br>';
                cont += item.addr + '</p>';
            }
        }
        if(item.phones != '') {
            cont += '<p class="dealer-list-phone">' + numberFormat(item.phones) + '</p>';
        }
        if(item.email != '') {
            cont += '<p class="dealer-list-mail">' + item.email + '</p>';
        }
        cont += '</td>';

        cont += '<td>';
        cont += item.pointStat;
        if(item.contractor && item.contractor != '') {
            cont += '<div class="table-subdealer"><span>Главный дилер:</span><br>';
            cont += item.contractor+'</div>';
        }
        cont += '</td>';

        cont += '<td>';
        cont += '<div class="md-list-time">';
        cont += '<div class="md-list-time-top">';
        if(item.weekdays != '') {
            cont += '<div class="md-list-time-item">';
            cont += '<div class="md-list-time-item-hours">' + item.weekdays + '</div>';
            cont += '<div class="md-list-time-item-day">Будни</div>';
            cont += '</div>';
        }
        if(item.saturday != '') {
            cont += '<div class="md-list-time-item">';
            cont += '<div class="md-list-time-item-hours">' + item.saturday + '</div>';
            cont += '<div class="md-list-time-item-day">Суббота</div>';
            cont += '</div>';
        }
        if(item.sunday != '') {
            cont += '<div class="md-list-time-item">';
            cont += '<div class="md-list-time-item-hours">' + item.sunday + '</div>';
            cont += '<div class="md-list-time-item-day">Воскресенье</div>';
            cont += '</div>';
        }
        if(item.weekend != '') {
            cont += '<div class="md-list-time-item">';
            cont += '<div class="md-list-time-item-hours">' + item.weekend + '</div>';
            cont += '<div class="md-list-time-item-day">Доп. выходные</div>';
            cont += '</div>';
        }
        if(item.without == 'Y') {
            cont += '<div class="md-list-time-item">';
            cont += '<div class="md-list-time-item-hours"></div>';
            cont += '<div class="md-list-time-item-day">Без выходных</div>';
            cont += '</div>';
        }

        cont += '</div>';
        cont += '<div class="md-list-time-bottom">';
        cont += '<a href="?type=mod-spec-edit&id='+item.id+'#etc4" class="dealer-item-see">Перейти</a>';
        cont += '</div>';
        cont += '</div>';


        cont += '</td>';
        cont += '</tr>';
    })
    return cont;

}

//-------------- ПРОМЕЖУТОЧНОЕ СОХРАНЕНИЕ --------------------
function showSaved() {
    let act = 'open',
        btn = $('[data-val="saved"]');
    if($('.md-panel').find('[data-val="saved"]').find('.icon-close').length > 0) act = 'close';
    if(act == 'open') {
        //$('.search-filter-wrap').css('display','none');
        hideEditDealer();
        $('.md-panel-btn').each(function() {
            if(!$(this).hasClass('md-saved')) {
                $(this).addClass('not-active');
            }
        })
        $('.md-bottom-panel').hide();
        $('[data-val="cont"]').each(function() {
            $(this).removeClass('active');
        })
        $('[data-type="saved"]').addClass('active');
        btn.find('span').html('<i class="icon-close"></i>');
        changeUrl('type','temp');
    } else {
        $('.search-filter-wrap').css('display','flex');
        $('.md-panel-btn').each(function() {
            $(this).removeClass('not-active');
        })
        if($('.md-bottom-panel').hasClass('active')) $('.md-bottom-panel').show();
        $('[data-type="saved"]').removeClass('active');
        $('[data-type="'+$('.md-view-wrap .active').attr('data-val')+'"]').addClass('active');
        btn.find('span').html(btn.attr('data-qty'));
        changeUrl('type','');
    }

}

//------------- ПОИСК -------------------------
//открыть/закрыть поиск
function showSearchResults(open) {
    if(open) {
        hideEditDealer();
        $('.md-panel-btn').each(function() {
            if(!$(this).hasClass('md-search') && !$(this).hasClass('md-view')) {
                $(this).addClass('not-active');
            }
        })
        $('[data-val="cont"]').each(function() {
            $(this).removeClass('active');
        })
        $('[data-type="'+$('.md-view.active').attr('data-val')+'"]').addClass('active');
        //$('[data-type="search"]').addClass('active');
        $('[data-type="md-btn-search"]').html('<i class="icon-close"></i>');
        $('.md-search-res').html('');
        $('.md-bottom-panel').hide();
        changeUrl('type','search');
        $('.md-view-wrap').attr('data-type','search-view');
        $('.search-filter-wrap').css('display','flex');
    } else {
        $('.md-panel-btn').each(function() {
            $(this).removeClass('not-active');
        })
        $('.md-view-wrap').removeClass('md-view-wrap-search');
        //$('[data-type="'+$('.md-view.active').attr('data-val')+'"]').addClass('active');
        $('[name="md-search"]').val('');
        $('[data-type="search"]').removeClass('active');
        $('[data-type="dealer"]').removeClass('active');
        clearEditForm();
        $('[data-type="md-btn-search"]').html('<i class="icon-search"></i>');
        $('.md-search-img').hide();
        $('.md-search-res').html('');
        if($('.md-bottom-panel').hasClass('active')) $('.md-bottom-panel').show();
        changeUrl('qwr','');
        changeUrl('type','');
        //resetSearchFilters();
        $('.md-view-wrap').removeAttr('data-type');
        let req = {};
        req.reg = $('[data-type="md-reg-val"]').attr('data-val');
        Object.assign(req, getSearchFilters());
        getDealers(req);
        //getDealers({reg:$('[data-type="md-reg-val"]').attr('data-val')});
        //$('.search-filter-wrap').css('display','none');
    }
}

function getSearchRequest() {
    let req = {};
    req.q = $('[name="md-search"]').val();
    if (req.q != '') {
        Object.assign(req, getSearchFilters());
        showSearchResults(true)
        $('.md-search-img').show();
        md_send_request(req);
    } else {
        setTimeout(function() {
            showSearchResults(false);
            //resetSearchFilters();
        },1000);
    }
}

function md_debounce(md_send_request, ms) {
    var timer = null;
    return function(args) {
        const onComplete = function() {
            md_send_request(args);
            timer = null;
        }
        if (timer) {
            clearTimeout(timer);
        }
        timer = setTimeout(onComplete, ms);
    };
}

function md_send_request(req) {
    req.reg = $('[data-type="md-reg-val"]').attr('data-val');
    req.type = 'search';
    getDealers(req);
    changeUrl('qwr',req.q);
}

var md_send_request = md_debounce(md_send_request, 1000);

function getSearchFilters() {
    let res = {};
    $('.search-filter-wrap').find('.search-filter-item').each(function() {
        let input = $(this).find('input');
        if(input.prop('checked')) {
            changeUrl(input.attr('name'),input.val());
            res[input.attr('name')] = input.val();
        } else {
            changeUrl(input.attr('name'),'');
        }
    })
    return res;
}

function resetSearchFilters() {
    $('.search-filter-wrap').find('.search-filter-item').each(function() {
        let input = $(this).find('input');
        input.removeAttr('checked');
        changeUrl(input.attr('name'),'');
    })
}

//--------------- НОВАЯ ТОЧКА ------------------
function showNewPoint(act) {
    let btn = $('[data-val="dealer"]');
    if(!act) {
        act = 'open';
        if($('.md-panel').find('[data-val="dealer"]').find('.icon-close').length > 0) act = 'close';
    }
    if(act == 'open') {
        $('.md-point-preloader').hide();
        clearEditForm();
        $('.md-panel-btn').each(function() {
            if(!$(this).hasClass('md-new')) {
                $(this).addClass('not-active');
            }
        })
        $('.md-bottom-panel').hide();
        $('[data-val="cont"]').each(function() {
            $(this).removeClass('active');
        })
        $('[data-type="dealer"]').addClass('active');
        changeUrl('type','edit');
        changeUrl('id','new');
        $('.md-edit-remove').hide();
        $('[data-type="md-rem-mod"]').hide();
    } else {
        $('.md-panel-btn').each(function() {
            $(this).removeClass('not-active');
        })
        if($('.md-bottom-panel').hasClass('active')) $('.md-bottom-panel').show();
        $('[data-type="dealer"]').removeClass('active');
        $('[data-type="'+$('.md-view-wrap .active').attr('data-val')+'"]').addClass('active');
        clearEditForm();
    }
}

//-------------- РЕДАКТИРОВАТЬ ДИЛЕРА ------------
function showEditDealer(id,mod) {
    let form = $('.md-edit');
    let sendData = {id:id};
    if(mod) sendData.type = mod;
    $.get('/moderation/dealers/get_dealer_list.php',sendData, function (data) {
        $('.md-point-preloader').hide();
        data = JSON.parse(data);
        if (data.err.qty == 0) {
            let item = data.dealers.items[0];
            console.log(item);
            setDealerData(item);
            form.show();
            $('.autogrow').each(function() {
                $(this).autogrow({vertical: true, horizontal: false});
            })
            $('*[data-type="om-personal-tabs"]').unstick();
            applyOMSticky();
            $('[data-val="cont"]').each(function() {
                if($(this).hasClass('active')) {
                    window.activeCont = $(this).attr('data-type');
                    $(this).removeClass('active');
                }
            })

            $('[data-val="edit-remove"]').show();
            setTimeout(function() {
                window.startFormData = form.serialize();
            },100)

            $('[data-type="dealer"]').addClass('active');
        } else {
            if(data.err.mess == 'Дилеры не найдены' || data.err.mess == 'Изменения приняты' || data.err.mess == 'Изменения отклонены') {
                $('[data-val="cont"]').each(function() {
                    if($(this).hasClass('active')) {
                        window.activeCont = $(this).attr('data-type');
                        $(this).removeClass('active');
                    }
                })
                $('.md-edit').show();
                $('[data-val="md-edit-cont"]').each(function() {
                    $(this).hide();
                })
                let btn = '<a href="' + $('[data-val="first-back"]').attr('href') + '" class="md-edit-mess-back">Вернуться</a>';
                $('.md-edit-not-found').find('p').html(data.err.mess);
                $('.md-edit-not-found').find('p').after(btn);
                $('.md-edit-not-found').show();
                $('*[data-type="om-personal-tabs"]').unstick();
                applyOMSticky();
                $('[data-type="dealer"]').addClass('active');
                let top = $('header').outerHeight();
                $('body,html').animate({scrollTop: top}, 100);
            } else {
                console.log(data.err.mess);
            }
        }
    });
    return false;
}

function setDealerData(item) {
    let form = $('.md-edit');
    if(item.id != '') form.find('[name="md-id"]').val(item.id);
    if(item.main == 'Y') form.find('[name="md-main"]').attr('checked','checked');
    if(item.orderDealer == 'Y') {
        form.find('[name="md-order"]').attr('checked','checked');
        $('.md-edit-cont-add-fields').addClass('active');
    }
    if(item.orderphones != '') form.find('[name="md-tel-order"]').val(item.orderphones);
    if(item.regArr) {
        item.regArr.forEach(function(reg) {
            let elem = '<div class="md-add-reg-inp">';
            elem += '<input type="hidden" id="md-reg-'+reg.id+'" name="md-d-reg" data-type="form-data" value="'+reg.id+'">';
            elem += '<label for="md-reg-'+reg.id+'">'+reg.name+'</label>';
            elem += '<i class="icon-close" data-type="remove-d-reg"></i>';
            elem += '</div>';
            form.find('.md-add-reg-inp-wrap').append(elem);
        })
        $('.md-edit').find('.md-edit-input-wrap-order-reg').show();
        if(item.onlyReg == 'Y') $('.md-edit').find('[name="md-only-reg"]').attr('checked','checked');
    }
    if(item.active == 'Y') form.find('[name="md-publish"]').attr('checked','checked');
    form.find('[name="md-org"]').val(item.org);
    form.find('[name="md-point"]').val(item.point);
    if(item.city != '' && item.cityId != '') {
        form.find('[name="md-reg"]').val(item.cityId);
        $('[data-type="point-reg"]').find('span').html(item.city).addClass('choosed');
    }
    form.find('[name="md-addr"]').val(item.addr);
    if(item.phones != '') {
        let phones = item.phones,
            wrap = form.find('[name="md-tel"]').closest('.md-edit-input-wrap');
        phones = phones.split(', ');
        phones.forEach(function(phone,i) {
            if(i > 0) {
                addPhoneLine(wrap);
            }
            wrap.find('[name="md-tel"]').eq(i).val(phone);

        })
    }
    if(item.email != '') form.find('[name="md-mail"]').val(item.email);
    if(item.orderemail != '') form.find('[name="md-mail-order"]').val(item.orderemail);
    if(item.qsemail != '') {
        let emails = item.qsemail,
            wrap = form.find('[name="md-mail-qs"]').closest('.md-edit-input-wrap');
        emails = emails.split(', ');
        emails.forEach(function(email,i) {
            if(i > 0) {
                addPhoneLine(wrap);
            }
            wrap.find('[name="md-mail-qs"]').eq(i).val(email);

        })
    }
    //form.find('[name="md-mail-qs"]').val(item.qsemail);
    if(item.url != '') form.find('[name="md-url"]').val(item.url);
    if(item.weekdays != '') form.find('[name="md-work"]').val(item.weekdays);
    if(item.saturday != '') form.find('[name="md-sat"]').val(item.saturday);
    if(item.sunday != '') form.find('[name="md-sun"]').val(item.sunday);
    if(item.without == 'Y') form.find('[name="md-without"]').attr('checked','checked');
    if(item.weekend != '') form.find('[name="md-weekend"]').val(item.weekend);
    if(form.attr('data-type') != 'mod') {
        form.find('[data-type="drop-area"]').attr('data-folder',item.id);
    }
    if(item.photo.length > 0) {
        item.photo.forEach(function(pic,i) {
            let img = '<div class="md-edit-photo" data-id="md-photo-'+(i+1)+'">' +
                '<i class="icon-close" title="Удалить" data-type="remove-md-photo"></i>'
                +'<a data-fancybox="md-edit-photo" href="'+pic.big.old+'" title="Увеличить" style="background-image:url('+pic.big.old+')"></a>'
                +'</div>';
            $('[data-type="drop-area-wrap"]').append(img);
        })
        $('[data-type="drop-area-wrap"]').show();
        form.find('[name="md-folder"]').val(item.id);
    }
    if(item.pointStat == "Субдилерская сеть") {
        form.find('[value="subdealer"]').attr('checked','checked');
        if(item.contractor != '') {
            form.find('[name="md-contractor"]').val(item.contractor);
            $('.md-edit-input-wrap-contractor').show();
        }
    }
    if(item.pointStat == "Собственная розница") {
        form.find('[value="retail"]').attr('checked','checked');
    }
    if(item.mall != '') form.find('[name="md-mall"]').val(item.mall);
    if(item.mark != '') form.find('[name="md-mark"]').val(item.mark);
    if(item.add != '') form.find('[name="md-add"]').val(item.add);
    if(item.assort && item.assort.length > 0) {
        item.assort.forEach(function(val) {
            form.find('[value="'+val+'"]').attr('checked','checked');
        })
    }
    if(item.serv && item.serv.length > 0) {
        item.serv.forEach(function(val) {
            form.find('[value="'+val+'"]').attr('checked','checked');
        })
    }
    if(item.equip != '') form.find('[name="md-equip"]').val(item.equip);
    if(item.servComm != '') form.find('[name="md-serv-comm"]').val(item.servComm);
    if(item.lat != '' && item.lon != '') {
        setTimeout(function() {
            form.find('[name="md-lat"]').val(item.lat);
            form.find('[name="md-lon"]').val(item.lon);
            if(typeof mapAdd !== 'undefined') {
                mapAdd.geoObjects.removeAll();
                myPlacemark = createPlacemark([item.lat,item.lon]);
                mapAdd.setCenter([item.lat,item.lon],9);
            }
        },100)
    }
    if(item.staff.length >0) {
        item.staff.forEach(function(item,i) {
            if(i > 0) {
                addStaffLine();
            }
            let staffLine = $('.md-edit-staff-item').last();
            if(item.fio != '') staffLine.find('[name="md-fio"]').val(item.fio);
            if(item.pos != '') staffLine.find('[name="md-pos"]').val(item.pos);
            if(item.phones != '') staffLine.find('[name="md-tel-staff"]').val(item.phones);
            if(item.email != '') staffLine.find('[name="md-mail-staff"]').val(item.email);
            let phones = item.phones,
                wrap = staffLine.find('[name="md-tel-staff"]').closest('.md-edit-input-wrap');
            phones = phones.split(', ');
            phones.forEach(function(phone,p) {
                if(p > 0) {
                    addPhoneLine(wrap);
                }
                wrap.find('[name="md-tel-staff"]').eq(p).val(phone);

            })
        })
    }
    if(item.modAct) {
        let act = '<span>Запрос на&nbsp;изменение: </span><br> при&nbsp;нажатии кнопки "Принять" контакт будет изменён.';
        if(item.modAct == 'new') act = '<span>Запрос на создание нового контакта: </span><br> при&nbsp;нажатии кнопки "Принять" контакт будет сохранен в&nbsp;базе.';
        if(item.modAct == 'rem') act = '<span>Запрос на удаление: </span><br> при&nbsp;нажатии кнопки "Принять" контакт будет удалён.';
        form.find('.md-mod-mess p').html(act);
    }
    if(item.mod) {
        let mess = '';
        item.mod.forEach(function(item,i) {
            if(item.type == 'rem') {
                mess += 'Модератору отправлен <span>запрос на удаление</span> данной точки ';
            }
            if(item.type == 'change') {
                mess += 'Модератору отправлен <span>запрос на изменение</span> информации ';
            }
            mess += item.date + '.<br>';
        })
        if(mess != '') {
            $('.md-edit-act').find('p').html(mess);
            $('.md-edit-act').show();
            form.find('.md-edit-btns').hide();
        }
    } else if(item.modResult) {
        let mess = 'Статус: '+item.modResult;
        $('.md-edit-act').find('p').html(mess);
        $('.md-edit-act').show();
        form.find('.md-edit-btns').hide();
    } else {
        $('.md-edit-act').hide();
        form.find('.md-edit-btns').show();
    }
}

function hideEditDealer() {
    $('[data-type="dealer"]').removeClass('active');
    clearEditForm();
}

//-------------- КАРТА ------------------------
//инициируем карту
function initMap() {
    myMap = new ymaps.Map('mdMap', {
        center: [55.764094, 37.617617],
        zoom: 12,
        controls: []
    }, {
        searchControlProvider: 'yandex#search'
    }),
        clusterer = new ymaps.Clusterer({
            clusterDisableClickZoom: true,
            clusterIcons: [
                {
                    href: '/img/map-cluster.png',
                    size: [38, 38],
                    offset: [-19, -19]
                }],
        }),
        getPointOptions = function (main,order) {
            let imageHref = "/img/map-marker.png";
            if (main == 'Y') imageHref = "/img/map-marker-main.png";
            if (order == 'Y') imageHref = "/img/map-marker-order.png";
            return {
                iconLayout: 'default#image',
                iconimgize: [28, 40],
                iconImageOffset: [-14, -40],
                iconImageHref: imageHref
            }
        },
        getPointData = function (i) {
            return {
                clusterCaption: nameArr[i],
                balloonContentBody: contentArr[i],
                name: idArr[i]
            };
        },
        contentArr = [],
        idArr = [],
        coordsArr = [],
        geoObjects = [],
        nameArr = [],
        list = '',
        tableList = '',
        sliderArr = [],
        bigMap = false;

    //
    /*$('[data-val="map"]').bind('cssClassChanged', function (event, ui) {
        if($('[data-val="map"]').hasClass('active')) {
            let regD = $('.md-bottom-panel').hasClass('active') ? true : false,
                dealersQty = $('[data-type="dealer-list"]').find('.dealer-item').length;
                if(shown === false) {
                    cetMapCenter(regD, dealersQty);
                    shown = true;
                    return false;
                }
        }
    });*/

    //клик по кнопке закрыть балуна - снимаем выделение в списке
    clusterer.events.add('balloonclose', function (e) {
        if (!myMap.balloon.isOpen()) {
            $('.dealer-item.active').each(function () {
                $(this).removeClass('active');
            })
            if($('.slick-slider').length > 0) {
                $('.slick-slider').slick('unslick');
            }
            $('[data-type="map-slider"]').html('');
        }
    })
    // клик на кластере или метке
    clusterer.events.add('click', function (e) {
        let cluster = e.get('target');
        let currentActiveObject = cluster.state.get('activeObject');
        if(currentActiveObject) {
            let addrId = currentActiveObject.properties.get('name');
            selectListItem(addrId);
        } else {
            let stateMonitor = new ymaps.Monitor(cluster.state);
            stateMonitor.add('activeObject', function(activeObject) {
                let addrId = activeObject.properties.get('name');
                selectListItem(addrId);
                return false;
            });
        }
        return false;
    });

    //переключение внутри кластера
    clusterer.events.add('balloonopen', function (e) {
        let cluster = e.get('cluster');
        if(cluster) {
            let stateMonitor = new ymaps.Monitor(cluster.state);
            stateMonitor.add('activeObject', function(activeObject) {
                let addrId = activeObject.properties.get('name');
                selectListItem(addrId)
            });
        } else {
            let addrId = e.get('target').properties.get('name');
            selectListItem(addrId);
        }
        return false;
    });

    //клик по адресу слева
    $('[data-type="dealer-list"]').on('click','.dealer-item',function(e) {
        var container = $(this).find('.dealer-item-see');
        // отсекаем клик по перейти
        if (container.has(e.target).length === 0 && !$(e.target).is(container)){
            var pointId = $(this).attr('data-id');
            $('.dealer-item').each(function() {
                $(this).removeClass('active');
            })
            $(this).addClass('active');

            let pm = ymaps.geoQuery(geoObjects).search('properties.name == "' + pointId + '"'),
                pmCords = pm.getCenter(myMap);
            myMap.setCenter(pmCords,16);
            let pmObj = pm.get(0);
            let objectState = clusterer.getObjectState(pmObj);
            if (objectState.isClustered) {
                objectState.cluster.state.set('activeObject', pmObj);
                clusterer.balloon.open(objectState.cluster);
            } else {
                pmObj.balloon.open();
            }
            setPointSlider(sliderArr[pointId]);
        }
    })

    //увеличить/уменьшить карту
    $('[data-type="map-size"]').on('click',function() {
        bigMap = !bigMap;
        if (bigMap) {
            $('.map-wrap').addClass('bigMap');
            $('[data-type="mod-res"]').css('z-index','10');
            $('[data-type="md-dealer"]').css('z-index','10');
        } else {
            $('.map-wrap').removeClass('bigMap');
            $('[data-type="mod-res"]').css('z-index','1');
            $('[data-type="md-dealer"]').css('z-index','1');
        }
        myMap.container.fitToViewport();
        $('.map-panel').data("plugin_tinyscrollbar").update();
        if($('.map-panel').find('.dealer-item.active').length > 0) {
            let addrId = $('.map-panel').find('.dealer-item.active').attr('data-id');
            selectListItem(addrId);
        }
    });
}

//строим карту и таблицу
function getDealers(req) {
        contentArr = [],
        idArr = [],
        coordsArr = [],
        geoObjects = [],
        nameArr = [],
        list = '',
        tableList = '',
        sliderArr = [];
        $('[data-type="wait-panel"]').show();
        $.post('/moderation/dealers/get_dealer_list.php',req, function (data) {
            $('[data-type="wait-panel"]').hide();
            //if($('.md-panel').find('[name="md-search"]').val() != '') $('.search-filter-wrap').css('display','flex');
            data = JSON.parse(data);
            if(typeof myMap !== 'undefined') {
                myMap.geoObjects.removeAll();
                $('[data-type="contacts-table-cont"]').html('');
                $('[data-type="dealer-list"]').html('')
            }
            //console.log(data.dealers.position.lat);
            if(data.err.qty > 0) {
                if(data.err.mess == 'Дилеры не найдены') {
                    let mess = '<div class="md-item-not-found">Дилеры не найдены</div>';
                    if($('.map-panel').data("plugin_tinyscrollbar")) {
                        $('[data-type="dealer-list"]').html(mess);
                        $('.map-panel').data("plugin_tinyscrollbar").update();
                    } else {
                        $('.map-panel').show();
                        $('[data-type="dealer-list"]').html(mess);
                        $(".map-panel").tinyscrollbar();
                    }
                    let tr = '<tr><td colspan="6" class="md-item-not-found-tr">Дилеры не найдены</td></tr>';
                    $('[data-type="contacts-table-cont"]').html(tr);
                } else {
                    console.log(data.err.mess);
                }

            } else {
                let res = data.dealers,
                    zoom = data.dealers.position.zoom > 12 ? 12 : data.dealers.position.zoom,
                    lat = data.dealers.position.lat,
                    lon = data.dealers.position.lon,
                    regD = data.dealers.position.regD;

                for (var i in res.items) {
                    //console.log(res.items[i]);
                    let item = res.items[i],
                        slider = '';

                    if (typeof item !== 'undefined') {

                        list += constructListItem(item,i); //панель на карте слева
                        tableList += constructTableItem(item,i); //таблица

                        if(item.photo.length > 0) {
                            slider += constructItemSlider(item.photo); //слайдер точки
                        }
                        sliderArr[item.id] = slider;

                        contentArr[contentArr.length] = constructPointItem(item); //метка на карте
                        idArr[idArr.length] = item.id;
                        coordsArr[coordsArr.length] = [item.lat, item.lon];
                        let pointName;
                        if(item.point != '') {
                            pointName = item.point;
                        } else if(item.org != '') {
                            pointName = item.org;
                        } else {
                            pointName = 'Без имени'
                        }
                        nameArr[nameArr.length]  = pointName;
                        let place = new ymaps.Placemark([item.lat, item.lon], getPointData(i), getPointOptions(item.main,item.orderDealer));

                        // клик по метке
                        /*place.events.add('click', function (e) {
                            let addrId = e.get('target').properties.get('name');
                            selectListItem(addrId);
                        });*/

                        geoObjects[i] = place;
                    }
                }

                if($('.map-panel').data("plugin_tinyscrollbar")) {
                    $('[data-type="dealer-list"]').html(list);
                    $('.map-panel').data("plugin_tinyscrollbar").update();

                } else {
                    $('.map-panel').show();
                    $('[data-type="dealer-list"]').html(list);
                    $(".map-panel").tinyscrollbar();
                }

                $('[data-type="contacts-table-cont"]').html(tableList);

                clusterer.removeAll();
                clusterer.add(geoObjects);
                myMap.geoObjects.add(clusterer);


                //myMap.setCenter([lat,lon],zoom);
                cetMapCenter(regD,res.items.length);


                if(regD) {
                    $('.md-bottom-panel').addClass('active').show();
                } else {
                    $('.md-bottom-panel').removeClass('active').hide();
                }
                showBottomPanel();
            }
        });
        return false;
}

//определяем центр карты и зум
function cetMapCenter(regD,qty) {
    //myMap.container.fitToViewport();
    if(window.innerWidth >= 1180) {

    }
    let zoomMarg = window.innerWidth > 900 ? [20,20,20,386] : [20,20,20,20];
    myMap.setBounds(myMap.geoObjects.getBounds(),{
        checkZoomRange:true,
        zoomMargin:zoomMarg,
    });
    //если одна точка, делаем масштаб покрупнее
    setTimeout(function(){
        let zoom = myMap.getZoom();
        if(zoom > 16 || qty < 2) {
            if(zoom > 16){
                if(regD) {
                    myMap.setZoom(12);
                } else {
                    myMap.setZoom(12); //было 16
                }
            }
        }
    },200);
    return false;
}

//корректируем карту если съехал zoom
function correctZoomData() {
    setTimeout(function() {
        console.log('here');
        if(myMap.getZoom() == 0) {
            let regD = $('.md-bottom-panel').hasClass('active') ? true : false,
                dealersQty = $('[data-type="dealer-list"]').find('.dealer-item').length;
            cetMapCenter(regD, dealersQty);
        }
    },100);
    return false;
}

//конструктор метки
function constructPointItem(item) {
    let dd = '';

    dd += '<div class="map-info-window">';
    dd += '<div class="point-name">';
    if(item.point != '') dd += item.point + '<br>';
    if(item.org != '') dd += '<span>' + item.org + '</span>';
    dd += '</div>';

    dd += '<div class="point-addr-cont">';
    if(item.addr != '') {
        dd += '<div class="point-addr">';
        if(item.city) dd += 'г. ' + item.city + '<br>';
        dd += item.addr + '</div>';
    }
    if(item.phones.trim().length > 0) {
        dd += '<div class="point-phone">';
        //dd += '<div class="point-phone-width">Тел.: </div>';
        dd += '<div class="point-phone-value">';
        dd += numberFormat(item.phones);
        dd += '</div>';
        dd += '</div>';
    }
    if(item.email != '') {
        dd += '<div class="point-email">' + item.email + '</div>';
    }
    if(item.url != '') {
        dd += '<a href="http://'+item.url+'" target="_blank" class="point-url"><span>URL</span>' + item.url + '</a>';
    }
    dd += '<div class="point-timetable">';
    dd += '<div class="point-timetable-name">Время работы</div>';
    dd += '<div class="point-timetable-cont">'
    dd += '<div class="point-timetable-items">'
    if(item.weekdays != '') {
        dd += '<div class="point-timetable-item"><div>Будни</div><div>'+item.weekdays+'</div></div>';
    }
    if(item.saturday != '') {
        dd += '<div class="point-timetable-item"><div>Суббота</div><div>'+item.saturday+'</div></div>';
    }
    if(item.sunday != '') {
        dd += '<div class="point-timetable-item"><div>Воскресенье</div><div>'+item.sunday+'</div></div>';
    }
    if(item.without == 'Y') {
        dd += '<div class="point-timetable-item"><div>Без выходных</div></div>';
    }
    if(item.weekend != '') {
        dd += '<div class="point-timetable-item"><div>Доп. выходные</div><div>'+item.weekend+'</div></div>';
    }
    dd += '</div>';
    dd += '<a href="?type=edit&id='+item.id+'#etc4" class="dealer-item-see">Перейти</a>';
    dd += '</div>';
    dd += '</div>';

    dd += '</div>';
    dd += '</div>';

    return dd;
}

//конструктор точки в списке слева
function constructListItem(item,i) {
    let ll = '';
    ll += '<div class="dealer-item" data-id="'+item.id+'">';
    ll += '<div class="dealer-item-number">'+(parseInt(i)+1)+'.</div>';
    ll += '<div class="dealer-item-content">';

    ll += '<div class="dealer-item-organization">';
    if(item.point != '') {
        ll += item.point;
        ll += '<br>';
    }
    if(item.org != '') ll += item.org;
    ll += '</div>';

    ll += '<div class="md-lbl-cont">';
    if(item.main == 'Y') {
        ll += '<div class="md-main-dealer">главный дилер</div>';
    }
    if(item.orderDealer == 'Y') {
        ll += '<div class="md-main-dealer md-order-dealer">контакт для заказа</div>';
    }
    ll += '</div>';


    if(item.addr != '') {
        ll += '<div class="point-addr">';
        if(item.city) ll += 'г. ' + item.city + '<br>';
        ll += item.addr + '</div>';
    }
    if(item.phones != '') {
        ll += '<div class="dealer-item-phone">';
        let phones = item.phones;
        if(phones.includes('<br>') === false && phones.includes('доб.') === false) phones = item.phones.replace(/,/gi, '<br>');
        ll += phones;
        ll += '</div>';
    }

    ll += '<div class="dealer-item-bottom-wrap">';
    ll += '<div class="dealer-item-status ' + item.statClass + '">' + item.stat + '</div>';
    ll += '<a href="?type=edit&id='+item.id+'#etc4" class="dealer-item-see">Перейти</a>';
    ll += '</div>';

    ll += '</div>';
    ll += '</div>';
    return ll;
}

//коструктор точки в таблице
function constructTableItem(item,i) {
    let tr = '';
    tr += '<tr>';
    tr += '<td>';
    tr += '<div class="md-list-name">';
    tr += '<div class="md-list-name-top">';
    tr += '<div class="md-list-name-top-cont">';
    tr += '<span>'+(parseInt(i)+1)+'.</span>';
    if(item.point != '') {
        tr += item.point;
        tr += '<br>';
    }
    if(item.org != '') tr += item.org;
    tr += '</div>';
    if(item.main == 'Y') {
        tr += '<div class="md-main-dealer">главный дилер</div>';
    }
    if(item.orderDealer == 'Y') {
        tr += '<div class="md-main-dealer md-order-dealer">контакт для заказа</div>';
    }
    tr += '</div>';
    tr += '<div class="md-list-name-bottom">';
    tr += '<div class="dealer-status ' + item.statClass + '">' + item.stat + '</div>';
    tr += '</div>';
    tr += '</div>';
    tr += '</td>';

    /*tr += '<td class="md-list-table-reg-dealer">';
    tr += '<div class="reg-dealer-wrap">';
    tr += '<input type="checkbox" id="reg-dealer-'+item.id+'" name="reg-dealer" data-val="'+item.id+'"';
    if(item.regDealer == 'Y') tr += ' checked';
    tr += '>';
    tr += '<label for="reg-dealer-'+item.id+'"></label>';
    tr += '</div>';
    tr += '</td>';*/

    tr += '<td>';
    tr += '<a href="http://'+item.url+'" target="_blank">'+item.url+'</a>';
    tr += '</td>';

    tr += '<td>';
    if(item.addr != '') {
        //tr += '<p class="dealer-list-addr">' + item.addr + '</p>';
        if(item.addr != '') {
            tr += '<p class="dealer-list-addr">';
            if(item.city) tr += 'г. ' + item.city + '<br>';
            tr += item.addr + '</p>';
        }
    }
    if(item.phones != '') {
        tr += '<p class="dealer-list-phone">' + numberFormat(item.phones) + '</p>';
    }
    if(item.email != '') {
        tr += '<p class="dealer-list-mail">' + item.email + '</p>';
    }
    tr += '</td>';

    tr += '<td>';
    tr += item.pointStat;
    if(item.contractor && item.contractor != '') {
        tr += '<div class="table-subdealer"><span>Главный дилер:</span><br>';
        tr += item.contractor+'</div>';
    }
    tr += '</td>';

    tr += '<td>';
    tr += '<div class="md-list-time">';
    tr += '<div class="md-list-time-top">';
    if(item.weekdays != '') {
        tr += '<div class="md-list-time-item">';
        tr += '<div class="md-list-time-item-hours">' + item.weekdays + '</div>';
        tr += '<div class="md-list-time-item-day">Будни</div>';
        tr += '</div>';
    }
    if(item.saturday != '') {
        tr += '<div class="md-list-time-item">';
        tr += '<div class="md-list-time-item-hours">' + item.saturday + '</div>';
        tr += '<div class="md-list-time-item-day">Суббота</div>';
        tr += '</div>';
    }
    if(item.sunday != '') {
        tr += '<div class="md-list-time-item">';
        tr += '<div class="md-list-time-item-hours">' + item.sunday + '</div>';
        tr += '<div class="md-list-time-item-day">Воскресенье</div>';
        tr += '</div>';
    }
    if(item.weekend != '') {
        tr += '<div class="md-list-time-item">';
        tr += '<div class="md-list-time-item-hours">' + item.weekend + '</div>';
        tr += '<div class="md-list-time-item-day">Доп. выходные</div>';
        tr += '</div>';
    }
    if(item.without == 'Y') {
        tr += '<div class="md-list-time-item">';
        tr += '<div class="md-list-time-item-hours"></div>';
        tr += '<div class="md-list-time-item-day">Без выходных</div>';
        tr += '</div>';
    }

    tr += '</div>';
    tr += '<div class="md-list-time-bottom">';
    tr += '<a href="?type=edit&id='+item.id+'#etc4" class="dealer-item-see">Перейти</a>';
    tr += '</div>';

    tr += '</div>';


    tr += '</td>';

    tr += '</tr>';


    return tr;


    /*

<td>
<div class="md-list-time">
    <div class="md-list-time-top">
    <div class="md-list-time-item">
    <div class="md-list-time-item-hours">10:00 - 20:00</div>

    </div>
    <div class="md-list-time-item">
    <div class="md-list-time-item-hours">10:00 - 19:00</div>
<div class="md-list-time-item-day">Суббота</div>
    </div>
    <div class="md-list-time-item">
    <div class="md-list-time-item-hours">10:00 - 19:00</div>
<div class="md-list-time-item-day">Воскресенье</div>
    </div>
    <div class="md-list-time-item">
    <div class="md-list-time-item-hours"></div>
    <div class="md-list-time-item-day">Без выходных</div>
</div>
</div>
<div class="md-list-time-bottom">

</div>
</div>
</td>
</tr>*/
}

//конструктор слайдера точки
function constructItemSlider(photoArr) {
    let slider = '';
    for (let i in photoArr) {
        let photo = photoArr[i],
            slide = '<div>';
        slide += '<a href="'+photo.big[format]+'" data-fancybox="point" data-title="slide-'+i+'" class="cover-link"></a>';
        slide += '<img src="'+photo.small[format]+'?v='+Math.random()+'" alt="slide-'+i+'" data-type="contacts-slide">';
        slide += '</div>';
        slider += slide;
    }
    return slider;
}

//обработка кривых номеров
function numberFormat(str) {
    return str.replace('Е512', '<br>Е512');
}

//выделить объект из списка по id
function selectListItem(addrId) {
    $('.dealer-item').each(function () {
        if ($(this).attr('data-id') == addrId) {
            $(this).addClass('active');
            let box = $('.map-panel').data("plugin_tinyscrollbar"),
                top = $(this).position().top,
                height = $(this).innerHeight(),
                wrapHeight = $('.map-panel').innerHeight();
            if (top >= wrapHeight - height) {
                box.update(top - wrapHeight + height + 20);
            } else {
                box.update();
            }
        } else {
            $(this).removeClass('active');
        }
    })
    setPointSlider(sliderArr[addrId]);

}

//открыть слайдер точки
function setPointSlider(cont) {
    if($('.slick-slider').length > 0) {
        $('.slick-slider').slick('unslick');
    }
    $('[data-type="map-slider"]').html(cont);
    /*$('[data-type="map-slider"]').find('[data-type="contacts-slide"]').each(function() {
        setAsyncImage($(this));
    })*/
    $('[data-type="map-slider"]').slick({
        dots: false,
        arrows: true,
        infinite: true,
        speed: 100,
        slidesToShow: 5,
        slidesToScroll: 5,
        adaptiveHeight: true,
        autoplay: false,
        autoplaySpeed: 4000,
        cssEase: 'linear',
        swipeToSlide:true,
        responsive: [
            {
                breakpoint: 1201,
                settings: {
                    slidesToShow: 4
                }
            },
            {
                breakpoint: 1001,
                settings: {
                    slidesToShow: 3,
                    arrows: false,
                    infinite: false,
                    centerMode: true,
                    centerPadding: '40px',
                    initialSlide: 1,
                }
            },
            {
                breakpoint: 501,
                settings: {
                    slidesToShow: 2,
                    arrows: false,
                    infinite: false,
                    centerMode: true,
                    centerPadding: '40px',
                    initialSlide: 1,
                }
            },
            /*{
                breakpoint: 401,
                settings: {
                    slidesToShow: 1,
                    arrows: false,
                    infinite: false,
                    centerMode: true,
                    centerPadding: '40px',
                    initialSlide: 0,
                }
            },*/
        ]
    });
}

//------------------- КАРТА НОВОЙ ТОЧКИ --------------------------
function initNewDealerMap(lat, lon) {
    if(!lat) lat = $('.md-edit').find('[name="md-lat"]').val();
    if(!lon) lon = $('.md-edit').find('[name="md-lon"]').val();
    var inputSearch = new ymaps.control.SearchControl({
        options: {
            size: 'large',
            provider: 'yandex#map',
            fitMaxWidth: true,
            noPlacemark: true
        },
    });
    if(lat) {//для показа карты с меткой при редактировании контакта
        mapAdd = new ymaps.Map('YMapsPointAdd', {
            center: [lat, lon],
            zoom: 9,
            controls: [inputSearch]
        });
        var myPlacemark = createPlacemark([lat, lon]);
        //document.getElementById('addLat').value = lat;
       // document.getElementById('addLon').value = lon;
    } else {//карта без метки при добавлении контакта
        var myPlacemark;
            mapAdd = new ymaps.Map('YMapsPointAdd', {
                center: [55.75, 37.57],
                zoom: 9,
                controls: [inputSearch]
            }, {
                searchControlProvider: 'yandex#search'
            });
    }
    mapAdd.events.add('click', function (e) {
        var coords = e.get('coords');
        setCoords(coords);
        mapAdd.geoObjects.removeAll();
        myPlacemark = createPlacemark(coords);
        getAddress(coords);
    });

    $('.md-edit').on('change','[name="md-lat"]',function() {
        lat = $('.md-edit').find('[name="md-lat"]').val();
        lon = $('.md-edit').find('[name="md-lon"]').val();
        mapAdd.geoObjects.removeAll();
        myPlacemark = createPlacemark([lat,lon]);
        mapAdd.setCenter([lat,lon],9);
    })
    $('.md-edit').on('change','[name="md-lon"]',function() {
        lat = $('.md-edit').find('[name="md-lat"]').val();
        lon = $('.md-edit').find('[name="md-lon"]').val();
        mapAdd.geoObjects.removeAll();
        myPlacemark = createPlacemark([lat,lon]);
        mapAdd.setCenter([lat,lon],9);
    })
    /*setTimeout(function() {
        $('#YMapsPointAdd').find('input').attr('autocomplete', 'new-password');
    },1000);*/


}

//вставляем координаты в инпуты
function setCoords(coords) {
    document.getElementById('mdAddLat').value = coords[0].toFixed(6);
    document.getElementById('mdAddLon').value = coords[1].toFixed(6);
    $('#mdAddLat').closest('.md-edit-input-wrap').removeClass('error');
    $('#mdAddLon').closest('.md-edit-input-wrap').removeClass('error');
}

// Создание метки
function createPlacemark(coords) {
    myPlacemark = new ymaps.Placemark(coords, {
        iconCaption: 'поиск...'
    }, {
        iconLayout: 'default#image',
        iconImageHref: '/img/map-marker.png',
        iconimgize: [28, 40],
        iconImageOffset: [-14, -40],
        draggable: true
    });
    mapAdd.geoObjects.add(myPlacemark);
    myPlacemark.events.add('dragend', function () {
        setCoords(myPlacemark.geometry.getCoordinates());
        getAddress(myPlacemark.geometry.getCoordinates());
    });
    return myPlacemark;
}

function getAddress(coords) {
    myPlacemark.properties.set('iconCaption', 'поиск...');
    ymaps.geocode(coords).then(function (res) {
        var firstGeoObject = res.geoObjects.get(0);
        myPlacemark.properties
            .set({
                iconCaption: [
                    firstGeoObject.getLocalities().length ? firstGeoObject.getLocalities() : firstGeoObject.getAdministrativeAreas(),
                    firstGeoObject.getThoroughfare() || firstGeoObject.getPremise()
                ].filter(Boolean).join(', '),
                balloonContent: firstGeoObject.getAddressLine()
            });
    });
}

//------------------- ЗАГРУЗКА ИЗОБРАЖЕНИЙ ------------------------
function imgUploaderInit() {
    let folder = (Math.floor(Math.random() * 9000) + 1000) +'_' + Date.now();
    if(!$('[data-type="drop-area"]').attr('data-folder') || $('[data-type="drop-area"]').attr('data-folder') == '') {
        $('[data-type="drop-area"]').attr('data-folder',folder);
    }
    $('[data-type="drop-area"]').dmUploader({
        url: '/moderation/dealers/img_uploader.php',
        maxFileSize: 3000000,
        extFilter: ["jpg", "jpeg", "png"],
       /* extraData: {
            "type": 'upload',
            "folder": folder
        },*/
        extraData: function() {
            return {
                "type": 'upload',
                "folder": $('[data-type="drop-area"]').attr('data-folder')
            };
        },
        onInit: function(){
            console.log('Callback: Plugin initialized');
        },
        onUploadSuccess: function(id, data){
            // A file was successfully uploaded
            if(data.status == "ok") {
                let nmbr = $('[data-type="drop-area-wrap"]').find('.md-edit-photo').length + 1,
                    folder = $('[data-type="drop-area"]').attr('data-folder');
                let img = '<div class="md-edit-photo" data-id="md-photo-'+nmbr+'">' +
                    '<i class="icon-close" title="Удалить" data-type="remove-md-photo"></i>'
                    +'<a data-fancybox="md-edit-photo" href="'+data.filename+'" title="Увеличить" style="background-image:url('+data.filename+')"></a>'
                    +'</div>';
                $('[data-type="drop-area-wrap"]').append(img).show();
                $('[name="md-folder"]').val(folder);
                //чтобы зачекать, были ли изменения в форме
                let op = parseInt($('[name="md-operations"]').val()) + 1;
                $('[name="md-operations"]').val(op);
            }
            if(data.status == "error") {
                showImgErr(data.message);
            }
        },
        onFallbackMode: function(){
            // When the browser doesn't support this plugin :(
            showImgErr('Текцщий браузер не поддерживает загрузку изображений таким способом. Возможно, вам стоит воспользоваться другим браузером.',true);
        },
        onFileExtError: function(){
            showImgErr('Допускаются разрешения: .jpeg, .jpg, .png');
        },
        onFileSizeError: function(file){
            showImgErr('Файл \'' + file.name + '\' не может быть добавлен: превышен допустимый размер - 3Мб');
        }

    });
}

function removeUploadedImg(id) {
    let folder = $('[data-type="drop-area"]').attr('data-folder'),
        filewrap = $('[data-type="drop-area-wrap"]').find('[data-id="'+id+'"]'),
        filename = filewrap.find('a').attr('href');
        //filename = filewrap.find('a').attr('href').split('/');
    //filename = filename[filename.length-1];
    let stat = $('.md-edit').attr('data-type');
    $.get('/moderation/dealers/img_uploader.php',{type:'remove',folder:folder, filename:filename, stat:stat},function(data) {
        if(data.status == 'ok') {
            $('[data-type="drop-area-wrap"]').find('[data-id="'+id+'"]').remove();
            //чтобы зачекать, были ли изменения в форме
            let op = parseInt($('[name="md-operations"]').val()) + 1;
            $('[name="md-operations"]').val(op);
            if($('[data-type="drop-area-wrap"]').find('.md-edit-photo').length == 0) {
                $('[name="md-folder"]').val('');
                $('[data-type="drop-area-wrap"]').hide();
            }
        } else {
            showImgErr(data.message);
        }
    });
}

function showImgErr(mess,notHide) {
    $('[data-type="drop-area-err"]').html(mess).fadeIn();
    if(notHide !== true) {
        setTimeout(function() {
            $('[data-type="drop-area-err"]').fadeOut();
        },5000);
    }

    return false;
}

//--------------------- ФОРМА ДИЛЕРА ----------------------------
//закрыть выбор регионов
//закрыть регион
function closeEditReg(reg) {
    let regList = $('#dropdown-down').find('[data-type="md-edit-reg-list"]'),
        regBtn = $('header').find('[data-type="md-edit-geo-close"]');
    regBtn.attr("data-type","geo-open");
    regBtn.addClass('icon-geo');
    regBtn.removeClass('icon-close');
    regList.slideUp(function(){
        regList.attr('data-type','reg-list').find('[data-type="curr-reg"]').attr('data-value',reg).html(reg);
    })
    $('body').removeClass('disabled');
    return false;
}

//закрыть регион в контакте для заказа
function closeOrderReg() {
    let regList = $('#dropdown-down').find('[data-type="md-add-reg-list"]'),
        regBtn = $('header').find('[data-act="md-add-reg-geo-close"]');
    $('[data-type="menu-border"]').last().attr('data-act','act');
    if($('header').offset().top>0){
        setTimeout(function(){
            $('[data-type="menu-border"]').last().show();
        },300);
    }
    regBtn.css('z-index','0');
    regBtn.attr("data-act","geo-open");
    regBtn.addClass('icon-geo');
    regBtn.removeClass('icon-close');
    regList.slideUp();
    if($('[data-type="header-cart-qty"]').html() != '0') {
        $('[data-type="header-cart-qty"]').show();
    }
    setTimeout(function(){
        regList.attr('data-type','reg-list').find('[data-type="header-geo-choose"]').show();
    },400);

    return false;
}

//собрать данные формы
function collectFormData() {
    let data = {};
    $('[data-type="main-form-data"]').find('.md-edit-input-wrap').each(function() {
        if($(this).find('[data-type="form-data"]').length > 0) {
            let i = $(this).find('[data-type="form-data"]').attr('name').split('md-');
            i = i[1];
            if($(this).find('[type="checkbox"]').length > 0) {
                if($(this).find('[type="checkbox"]').length == 1) {
                    let inp = $(this).find('[data-type="form-data"]');
                    data[i] = inp.prop('checked') ? 'Y' : 'N';
                } else {
                    let arr = [];
                    $(this).find('[data-type="form-data"]').each(function() {
                        if($(this).prop('checked')) {
                            arr.push($(this).val());
                        }

                    })
                    data[i] = arr;
                }
            } else if($(this).find('[type="radio"]').length > 0) {
                data[i] = $(this).find('input:checked').val();
            } else if($(this).find('[data-type="form-data"]').length > 1 || $(this).find('[name="md-tel"]').length > 0 || $(this).find('[name="md-mail-qs"]').length > 0) {
                let arr = [];
                console.log($(this).find('input').attr('name'));
                $(this).find('[data-type="form-data"]').each(function() {
                    if($(this).val() != '') {
                        arr.push($(this).val());
                    }
                })
                data[i] = arr;
            } else {
                if($(this).find('[data-type="md-add-reg"]').length > 0) {
                    let reg = Array();
                    $(this).find('[name="md-d-reg"]').each(function() {
                        reg.push($(this).val());
                    })
                    data[i] = reg;
                } else {
                    let inp = $(this).find('[data-type="form-data"]');
                    data[i] = inp.val();
                }
            }
        }
    });
    data['staff'] = [];
    $('.md-edit-staff').find('.md-edit-staff-item').each(function() {
         let arr = {},
             empty = 0,
             txtFields = 0;
         $(this).find('.md-edit-input-wrap').each(function() {
             let inp = $(this).find('[data-type="form-data"]'),
                 i = inp.attr('name').split('-');
             i = i[1];
             if($(this).find('[data-type="form-data"]').length > 0 && $(this).find('[data-type="form-data"]').attr('type') == 'tel') {
                 let arrTel = [];
                 $(this).find('[data-type="form-data"]').each(function() {
                     if($(this).val() != '') {
                         arrTel.push($(this).val());
                     }
                 })
                 arr[i] = arrTel;
             } else {
                 arr[i] = inp.val();
             }
             if(inp.attr('type') != 'radio') {
                 txtFields++;
                 if(inp.val() == '') empty++;
             }
         })
        if(empty == 0 || txtFields != empty) data['staff'].push(arr);

    });
    data['img'] = [];
    $('.md-edit').find('.md-edit-photo').each(function() {
        data['img'].push($(this).find('a').attr('href'));
    })
    if($('.md-edit').find('[name="md-id"]').val() != '') data.id = $('.md-edit').find('[name="md-id"]').val();
    data['img-operations'] = $('.md-edit').find('[name="md-operations"]').val();
    //console.log(data);
    return data;
}

function clearEditForm(arr) {
    let form = $('.md-edit');
    form.trigger('reset');
    form.find('[name="md-id"]').val('');
    form.find('.md-edit-input-wrap').each(function() {
        $(this).removeClass('error');
        let input = $(this).find('input'),
            textarea = $(this).find('textarea');
        if(input.attr('type') != "checkbox" && input.attr('type') != "radio") {
            input.val('');
        }
        textarea.val('');
        if(input.length > 1 && input.attr('type') == 'tel') {
            $(this).find('[type="tel"]:not(:first)').remove();
        }
        if($(this).attr('data-type') == 'point-reg') {
            $(this).find('span').html('Выбрать город*').removeClass('choosed');
        }
        if($(this).attr('data-type') == 'drop-area') {
            $(this).attr('data-folder','');
            $(this).find('[data-type="drop-area-wrap"]').html('');
            $(this).find('[data-type="drop-area-err"]').html('').hide();
            $(this).find('[name="md-folder"]').val();
        }
    })
    form.find('[type="checkbox"]').each(function(){
        $(this).removeAttr("checked");
    })
    $('.md-edit-input-wrap-contractor').removeClass('errorCntrctr').hide();
    $('.md-edit-input-wrap-contractor').find('input').removeAttr('required');
    $('.md-edit-staff').find('.md-edit-staff-item:not(:first)').remove();
    let folder = (Math.floor(Math.random() * 9000) + 1000) +'_' + Date.now();
    $('[data-type="drop-area"]').attr('data-folder',folder);
    form.find('[name="md-operations"]').val(0);
    form.find('.md-edit-err').html('').removeClass('succ').hide();
    form.find('button').each(function(){
        $(this).removeAttr('disabled');
    })
    form.find('[value="retail"]').attr('checked','checked');
    $('[data-type="dealer"]').find('[data-type="md-edit-no"]').each(function() {
        $(this).removeAttr('data-back');
    })
    //$('[data-type="dealer"]').find('.pacc-back').hide();

    if(typeof mapAdd !== 'undefined') {
        mapAdd.geoObjects.removeAll();
        mapAdd.setCenter([55.75, 37.57],9);
    }
    window.startFormData = $('.md-edit').serialize();
    //changeUrl('type','');
    changeUrl('id','');
    $('.md-edit-not-found').hide();
    form.show();
    $('*[data-type="om-personal-tabs"]').unstick();
    applyOMSticky();
}

//проверить на наличие ошибок
function checkEditForm() {
    let form = $('.md-edit'),
        err = 0;
    form.find('.md-edit-input-wrap').each(function() {
        if($(this).hasClass('errorCntrctr')) {
            $(this).addClass('error');
            err++;
        }
        if($(this).closest('.md-edit-staff-item').length > 0) {
            let isEmpty = true;
            $(this).closest('.md-edit-staff-item').find('.md-edit-input-wrap').each(function() {
                let input = $(this).find('input');
                if(input.length > 0 && input.attr('type') != 'radio' && input.val() != '') {
                    isEmpty = false;
                }
            })
            if(isEmpty == false && $(this).find('input').val() == '') {
                $(this).addClass('error');
                err++;
            }
        } else {
            if($(this).find('[data-type="form-data"]').length > 0) {
                let input = $(this).find('[data-type="form-data"]');
                if(input.attr("required") == "required" && input.val() == '') {
                    $(this).addClass('error');
                    err++;
                }
            }
        }
        $(this).find('[type="email"]').each(function() {
            if($(this).closest('.md-edit-staff-item').length == 0
                && $(this).val() != ''
                && validateEmail($(this).val()) == false) {
                $(this).closest('.md-edit-input-wrap').addClass('error');
                err++;
            }
        })
    })
    if($('.md-edit-cont-add-fields').find('.error').length > 0) {
        $('.md-edit-cont-add-fields').show();
    }
    if($('#mdContractor').closest('.errorCntrctr').length > 0) {
        $('#mdContractor').closest('.errorCntrctr').show();
    }
    return err;
}

//копировать форму
function copyEditForm(arr) {
    let form = $('.md-edit');
    form.find('.error').removeClass('error');
    form.find('.errorCntrctr').removeClass('errorCntrctr');
    form.find('[name="md-id"]').val('');
    //главный дилер
    form.find('[name="md-main"]').removeAttr("checked");
    //контакт для заказа
    form.find('[name="md-order"]').removeAttr("checked");
    $('.md-edit-cont-add-fields').find('input').val('');
    $('.md-edit-cont-add-fields').find('[name="md-mail-qs"]').each(function(index){
        if(index != 0) $(this).remove();
    });
    $('[name="md-only-reg"]').removeAttr("checked").closest('.md-edit-input-wrap-order-reg').hide();
    $('.md-add-reg-inp-wrap').find('.md-add-reg-inp').each(function() {
        $(this).remove();
    })
    $('.md-edit-cont-add-fields').removeClass('active');
    //опубликовать
    form.find('[name="md-publish"]').removeAttr("checked");
    //организация
    if(!arr.includes('org')) form.find('[name="md-org"]').val('');
    //точка продажи
    if(!arr.includes('point')) form.find('[name="md-point"]').val('');
    //город
    if(!arr.includes('reg')) {
        form.find('[name="md-reg"]').val('');
        form.find('.md-edit-choose-reg').find('span').html('Выбрать город*').removeClass('choosed');
    }
    //адрес
    if(!arr.includes('addr')) form.find('[name="md-addr"]').val('');
    //телефон
    form.find('[name="md-tel"]').each(function(index){
        $(this).val('');
        if(index != 0) $(this).remove();
    });
    //e-mail
    if(!arr.includes('mail')) form.find('[name="md-mail"]').val('');
    //тип точки
    form.find('[name="md-contractor"]').val('');
    form.find('[value="subdealer"]').removeAttr('checked');
    form.find('[value="retail"]').attr('checked','checked');
    form.find('.md-edit-input-wrap-contractor').hide();
    //время работы
    if(!arr.includes('time')) {
        form.find('[name="md-work"]').val('');
        form.find('[name="md-sat"]').val('');
        form.find('[name="md-sun"]').val('');
        form.find('[name="md-without"]').removeAttr('checked');
        form.find('[name="md-weekend"]').val('');
    }
    //фотографии
    let folder = (Math.floor(Math.random() * 9000) + 1000) +'_' + Date.now();
    $('[data-type="drop-area"]').attr('data-folder',folder);
    form.find('[name="md-operations"]').val(0);
    $('[name="md-folder"]').val('');
    form.find('[data-type="drop-area-wrap"]').html('');
    //координаты
    if(typeof mapAdd !== 'undefined') {
        mapAdd.geoObjects.removeAll();
        mapAdd.setCenter([55.75, 37.57],9);
    }
    form.find('[name="md-lat"]').val('');
    form.find('[name="md-lon"]').val('');
    //ТЦ, рынок и пр.
    form.find('[name="md-mall"]').val('');
    //ориентир
    form.find('[name="md-mark"]').val('');
    //доп. информация
    form.find('[name="md-add"]').val('');
    //служебный комментарий
    form.find('[name="md-serv-comm"]').val('');
    //ассортимент
    form.find('[name="md-assort"]').each(function() {
        $(this).removeAttr('checked');
    })
    //услуги
    form.find('[name="md-serv"]').each(function() {
        $(this).removeAttr('checked');
    })
    //торговое оборудование
    form.find('[name="md-equip"]').val('');
    //персонал
    if(!arr.includes('staff')) {
        $('.md-edit-staff').find('.md-edit-staff-item:not(:first)').remove();
        form.find('.md-edit-staff-item').find('input').val('');
        form.find('.md-edit-staff-item').find('[name="md-tel-staff"]').each(function(index) {
            if(index != 0) $(this).remove();
        });
    }
    window.startFormData = '';
}
Fancybox.bind("[data-fancybox]", {
    // Your custom options
});