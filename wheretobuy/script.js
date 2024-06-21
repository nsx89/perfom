/*
* статичные точки не запрашиваем с сервера, а загоняем в объект - так быстрее
* */
var mainOffice = {
   0: {
       'id':        '00',
       'org':       'ООО "Декор"',
       'point':     '',
       'city':      '',
       'addr':      '117342, Россия, г.&nbsp;Москва,<br>ул.&nbsp;Обручева, д.&nbsp;52, стр.&nbsp;3',
       'mall':      '',
       'mark':      '',
       'lat':       '55.648341',
       'lon':       '37.561068',
       'zoom':      '9',
       'phones':    '+7 495 315 30 40',
       'email':     'decor@decor-evroplast.ru',
       'url':       '',
       'saturday':  '',
       'sunday':    '',
       'weekdays':  '',
       'weekend':   '',
       'without':   '',
    },
    1: {
        'id':        '01',
        'org':       'ООО "Декор"',
        'point':     '',
        'city':      '',
        'addr':      '142350, Россия, Московская область, городской округ Чехов, д.&nbsp;Ивачково, ул.&nbsp;Лесная, владение&nbsp;12, строение&nbsp;7',
        'mall':      '',
        'mark':      '',
        'lat':       '55.228931',
        'lon':       '37.480471',
        'zoom':      '5',
        'phones':    '+7 495 789-62-70',
        'email':     '',
        'url':       '',
        'saturday':  '',
        'sunday':    '',
        'weekdays':  '',
        'weekend':   '',
        'without':   '',
    },
    2: {
        'id':        '02',
        'org':       'ООО "Декор"',
        'point':     '',
        'city':      '',
        'addr':      '142350, Россия, Московская область, городской округ Чехов, д.&nbsp;Ивачково, ул.&nbsp;Лесная, владение&nbsp;12, строение&nbsp;7',
        'mall':      '',
        'mark':      '',
        'lat':       '55.228931',
        'lon':       '37.480471',
        'zoom':      '5',
        'phones':    '+7 495 789-62-70',
        'email':     '',
        'url':       '',
        'saturday':  '',
        'sunday':    '',
        'weekdays':  '',
        'weekend':   '',
        'without':   '',
    },
    /*
    2: {
        'id':        '02',
        'org':       '',
        'point':     '',
        'city':      '',
        'addr':      'Московская&nbsp;область, Ленинский&nbsp;район, с/п&nbsp;Булатниковское, с.&nbsp;Булатниково, ул.&nbsp;Центральная, дом&nbsp;1В, стр.&nbsp;10',
        'addr':      '142350, Россия, Московская область, городской округ Чехов, д.&nbsp;Ивачково, ул.&nbsp;Лесная, владение&nbsp;12, строение&nbsp;7',
        'mall':      '',
        'mark':      '',
        'lat':       '55.56821656917941',
        'lon':       '37.66324249999998',
        'zoom':      '11',
        'phones':    '',
        'email':     '',
        'url':       '',
        'saturday':  '',
        'sunday':    '',
        'weekdays':  '',
        'weekend':   '',
        'without':   '',
    },
    */
    3: {
        'id':        '00',
        'org':       'ООО "Декор"',
        'point':     '',
        'city':      '',
        'addr':      '117342, Россия, г.&nbsp;Москва,<br>ул.&nbsp;Обручева, д.&nbsp;52, стр.&nbsp;3',
        'mall':      '',
        'mark':      '',
        'lat':       '55.648341',
        'lon':       '37.561068',
        'zoom':      '9',
        'phones':    '+7 495 315 30 40',
        'email':     'decor@decor-evroplast.ru',
        'url':       '',
        'saturday':  '',
        'sunday':    '',
        'weekdays':  '',
        'weekend':   '',
        'without':   '',
    },
}
var mainOfficeEmails = {
    'h-off-1':   {  // головной офис
        'email': 'decor@decor-evroplast.ru',
        'phone': '+7 495 315 30 40',
    },
    'h-off-2':   {  // служба качества
        'email': 'quality@decor-evroplast.ru',
        'phone': '+7 (495) 789 62 76',
    },
    'h-off-3':   {  // производство
        'email': '',
        'phone': '+7 495 789-62-70',
    },
    'h-off-4':   {  // складской комплекс
        'email': '',
        'phone': '',
    },
    'h-off-5':   {  // дилерам
        'email': 'dealer@decor-evroplast.ru',
        'phone': '+7 495 315 30 40',
    },
    'h-off-6':   {  // строителям
        'email': 'prof@decor-evroplast.ru',
        'phone': '+7 495 315 30 40',
    },
    'h-off-7':   {  // дизайнерам и архитекторам
        'email': 'design@decor-evroplast.ru',
        'phone': '+7 495 315 30 40',
    },
    'h-off-8':   {  // пресс-служба
        'email': 'pr@decor-evroplast.ru',
        'phone': '+7 495 315 30 40',
    },
    'h-off-9':   {  // отдел персонала
        'email': 'hr@decor-evroplast.ru',
        'phone': '+7 (495) 789 62 70',
    },
    'h-off-10':   {  // интернет-магазин
        'email': 'marketing@decor-evroplast.ru',
        'phone': '+7 (495) 789 62 70',
    },
}
var dealersList;

$(document).ready(function() {
    $('[data-type="main-tab"]').each(function() {
        $(this).removeClass('active');
    })
    $('[data-type="main-tab-cont"]').each(function() {
        $(this).removeClass('active');
    })
    if(location.hash == '#head') { // головной офис
        $('[href="#head"]').addClass('active');
        $('#head').addClass('active');
    } else if(location.hash == '#outlets') { // точки продаж
        $('[href="#outlets"]').addClass('active');
        $('#outlets').addClass('active');
        constructDealerList(dealersList);
    } else { // представитель в регионе, если не москва
        if($('[href="#region"]').length > 0) {
            $('[href="#region"]').addClass('active');
            $('#region').addClass('active');
        } else {
            $('[data-type="main-tab"]').first().addClass('active');
            $('[data-type="main-tab-cont"]').first().addClass('active');
        }

    }

    //инициируем карту
    ymaps.ready(function () {
        if($('#map').length > 0) {
            initMap();
            setTimeout(function(){
                var coords = $('#map').attr('data-coords') || '';
                if(location.hash == '#head') { // головной офис
                    let active = $('.head-office-list').find('.active'),
                        val = active.attr('data-val'),
                        id = active.attr('data-id');
                        getHeadOfficePoint(id,val);
                } 
                else if(location.hash == '#outlets') { // точки продаж
                    getDealers(dealersList);
                } 
                else { // представитель в регионе, если не москва
                    if($('[href="#region"]').length > 0) {
                        if (typeof mainDealer !== 'undefined') {
                            getDealers(mainDealer);
                        }
                    } else {
                        /*let active = $('.head-office-list').find('.active'),
                            val = active.attr('data-val'),
                            id = active.attr('data-id');
                        getHeadOfficePoint(id,val);*/

                        if(document.location.pathname == '/contact/') {
                            if (coords == '') {
                                let active = $('.head-office-list').find('.active'),
                                    val = active.attr('data-val'),
                                    id = active.attr('data-id');
                                getHeadOfficePoint(id,val);
                            }
                            else {
                                if (typeof mainDealer !== 'undefined') {
                                    getDealers(mainDealer);
                                }
                                else {
                                    getDealers(dealersList);
                                }
                            }
                        } 
                        else {
                            getDealers(dealersList);
                        }

                    }
                }
            },50);
        }
    })

    //главные табы
    $('[data-type="main-tab"]').on('click',function() {
        let val = $(this).attr('href');
        $('#map').show();
        $('[data-type="list"]').hide();

        if(val == '#head') { // головной офис
            let active = $('.head-office-main'),
                val = active.attr('data-val'),
                id = active.attr('data-id');
            setTimeout(function(){
                getHeadOfficePoint(id,val);
            },50);
        } else if(val == '#outlets') {
            if($('[data-val="map"]').hasClass('active')) {
                $('#map').show();
                $('[data-type="list"]').hide();
            }
            if($('[data-val="list"]').hasClass('active')) {
                $('#map').hide();
                $('[data-type="list"]').show();
            }
            getDealers(dealersList);
        } else {
            if(typeof mainDealer !== 'undefined') {
                getDealers(mainDealer);
            }
        }

    })
    // головной офис - переключатели
    $('[data-type="h-off-tab"]').on('click',function() {
        let main = $('.head-office-main'),
        main_val = main.attr('data-val'),
        main_id = main.attr('data-id');

        let val = $(this).attr('data-val'),
           id = $(this).attr('data-id');

        //console.log(val);

        $('[data-type="h-off-tab"]').each(function() {
            if($(this).attr('data-val') == val) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        })
        $('.head-office-item').each(function() {
            if($(this).attr('data-val') == val) {
                $(this).addClass('active');
            } else {
                $(this).removeClass('active');
            }
        })

        var coords = $('#map').attr('data-coords') || '';
        if (coords != '') {
            switch(val) {
              case 'h-off-11': case 'h-off-5': case 'h-off-6': case 'h-off-7':
                setTimeout(function(){
                    if (typeof mainDealer !== 'undefined') {
                        getDealers(mainDealer);
                    }
                    else {
                        getDealers(dealersList);
                    }
                }, 100);
              default:
                // создаем метку, если адрес меняется
                getHeadOfficePoint(main_id, main_val);
            }
        }
        else {
            // создаем метку, если адрес меняется
            getHeadOfficePoint(main_id, main_val);
        }
    })
    //табы точек продаж
   $('[data-type="outlet-tab"]').on('click',function(){
        $($('[data-type="outlet-tab"]')).each(function() {
            $(this).removeClass('active');
        })
        $(this).addClass('active');
        if($(this).attr('data-val') == 'map') {
            $('[data-type="list"]').hide();
            $('#map').show();
        }
        if($(this).attr('data-val') == 'list') {
            $('[data-type="list"]').show();
            $('#map').hide();
        }
    })



}) // document.ready

//инициируем карту
function initMap() {
    var lat = 55.764094;
    var lng = 37.617617;
    var coords = $('#map').attr('data-coords') || '';
    if (coords != '') {
        var arr = coords.split(',');
        lat = $.trim(arr[0]);
        lng = $.trim(arr[1]);
    }
    myMap = new ymaps.Map('map', {
        center: [lat, lng],
        zoom: 12,
    }, {
        maxZoom: 16,
        balloonPanelMaxMapArea: 300000,
        searchControlProvider: 'yandex#search',

    }),
        clusterer = new ymaps.Clusterer({
            clusterDisableClickZoom: true,
            clusterIcons: [
                {
                    href: '/img/map-cluster-black.png',
                    size: [38, 38],
                    offset: [-19, -19]
                }],
        }),
        getPointOptions = function () {
            let imageHref = "/img/e_mark_new_2.svg";
            return {
                iconLayout: 'default#image',
                iconImageSize: [28, 42],
                iconImageOffset: [-14, -42],
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
        myMap.behaviors.disable('scrollZoom');
        if(supportsTouch === true) {
            //myMap.behaviors.disable('drag');
        }
}

//строим карту и таблицу
function getDealers(contact) {
    contentArr = [],
    idArr = [],
    coordsArr = [],
    geoObjects = [],
    nameArr = [],
    list = '',
    outletList = '',
    sliderArr = [];
    $('[data-type="wait-panel"]').show();
    if(contact) {
        constructMap(contact);
    } else {
        $.post('/ajax/get_contacts.php', function (data) {
            data = JSON.parse(data);
            dealersList = data;
            constructMap(data,true);
        });
    }
    return false;
}

//конструктор карты
function constructMap(data,needList=false) {
    $('[data-type="wait-panel"]').hide();

    if(typeof myMap !== 'undefined') {
        myMap.geoObjects.removeAll();
    }
    if(data.err.qty > 0) {
        if(data.err.mess == 'Дилеры не найдены') {
            console.log('here:'+data.err.mess);
        } else {
            console.log(data.err.mess);
        }

    } else {
        let res = data.dealers,
            zoom = data.dealers.position.zoom > 12 ? 12 : data.dealers.position.zoom,
            lat = data.dealers.position.lat,
            lon = data.dealers.position.lon;

        for (var i in res.items) {
            let item = res.items[i];

            if (typeof item !== 'undefined') {
                if(needList) {
                    if(typeof mainDealer !== 'undefined' && item.id != mainDealer.dealers.items[0].id || typeof mainDealer === 'undefined') {
                        outletList += constructListItem(item); //список
                    }
                }

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

                geoObjects[i] = place;
            }
        }

        //$('[data-type="contacts-table-cont"]').html(tableList);

        clusterer.removeAll();
        clusterer.add(geoObjects);
        myMap.geoObjects.add(clusterer);

        cetMapCenter(res.items.length);

        $('body').on('click','[data-type="close-point"]',function() {
            myMap.balloon.close();
        })

        if(needList) {
            $('[data-type="list"]').find('.content-wrapper').html(outletList);
        }


    }
    return false;
}

//определяем центр карты и зум
function cetMapCenter(qty) {
    //myMap.container.fitToViewport();
    myMap.setBounds(myMap.geoObjects.getBounds(),{
        checkZoomRange:true, //слишком приближает карту, maxZoom - 12
        zoomMargin:[20,20,20,20],
    });
    //если одна точка, делаем масштаб покрупнее
    /*$('[data-type="wait-panel"]').show();
    setTimeout(function(){
        let zoom = myMap.getZoom();
        if(zoom > 16 || qty < 2) {
            if(zoom > 16){
               myMap.setZoom(12); //было 16
            }
        }
        $('[data-type="wait-panel"]').hide();
    },200);*/
    return false;
}

//конструктор метки
function constructPointItem(item) {
    let dd = '';

    dd += '<div class="map-info-window">';
    dd += '<div class="point-name">';
    if(item.point != '') dd += item.point + '<br>';
    if(item.org != '') dd += item.org;
    dd += '<i class="icomoon icon-close" data-type="close-point"></i>';
    dd += '</div>';

    dd += '<div class="point-addr-cont">';
    if(item.addr != '') {
        dd += '<div class="point-addr">';
        if(item.city) dd += item.city + '<br>';
        dd += item.addr;
        if(item.mall != '') {
            dd += '<br>' + item.mall;
        }
        if(item.mark != '') {
            dd += '<br>' + item.mark;
        }
        dd += '</div>';
    }
    if(item.phones.trim().length > 0) {
        dd += '<div class="point-phone">';
        dd += '<div class="point-phone-width">телефон </div>';
        dd += '<div class="point-phone-value">';
        let phones = item.phones.split(', ');
        phones.forEach(function(phone,i) {
            if (i > 0) {
                dd += ', <br>';
            }
            dd += phone;
        });
        //dd += numberFormat(item.phones);
        dd += '</div>';
        dd += '</div>';
    }
    if(item.email != '') {
        dd += '<div class="point-email">';
        dd += '<div class="point-phone-width">почта</div>';
        dd += '<div class="point-phone-value">'+ item.email + '</div>';
        dd += '</div>';
    }
    if(item.url != '') {
        dd += '<div class="point-email">';
        dd += '<div class="point-phone-width">сайт </div>';
        dd += '<div class="point-phone-value"><a href="http://'+item.url+'" target="_blank" class="point-url">' + item.url + '</a></div>';
        if(item.url2 != '' && typeof item.url2 !== 'undefined') {
            dd += '<div class="point-phone-value"><a href="http://'+item.url2+'" target="_blank" class="point-url">' + item.url2 + '</a></div>';
        }
        dd += '</div>';
    }

    if(item.weekdays != '' || item.saturday != '' || item.sunday != '' || item.without == 'Y' || item.weekend != '') {
        dd += '<div class="point-timetable">';
        dd += '<div class="point-timetable-name">Время работы</div>';
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
        dd += '</div>';
    }
    dd += '</div>';

    return dd;
}

//конструктор точки в списке
function constructListItem(item) {
    let dd = '';
    dd += '';
    dd += '<div class="outlet-item">';
    dd += '<div class="e-cctb-title">';
    dd += '<span>';
    if(item.point != '') dd += item.point;
    if(item.point != '' && item.org != '' && item.point != item.org) dd += '<br>';
    if(item.org != '' && item.point != item.org) dd += item.org;
    dd += '</span>';
    dd += '</div>';
    dd += '<div class="e-cctb-content-2col">';
    if(item.addr != '') {
        dd += '<div class="e-cctb-item">';
        dd += '<span class="e-cctb-name">адрес</span>';
        dd += '<p>';
        if(item.city) dd += item.city + ', ';
        dd += item.addr;
        dd += '</p>';
        /*if(item.mall != '') {
            dd += '<p>' + item.mall + '</p>';
        }*/
        if(item.mark != '') {
            dd += '<p>' + item.mark + '</p>';
        }
        dd += '</div>';
    }
    if(item.url != '') {
        dd += '<div class="e-cctb-item">';
        dd += '<span class="e-cctb-name">сайт</span>';
        dd += '<p><a target="_blank" href="http://' + item.url +'">' + item.url + '</a></p>';
        dd += '</div>';
    }
    if(item.phones.trim().length > 0) {
        dd += '<div class="e-cctb-item">';
        dd += '<span class="e-cctb-name">телефон/факс</span>';
        dd += '<p>';
        let phones = item.phones.split(', ');
        phones.forEach(function(phone,i) {
            if (i > 0) {
                dd += ', <br>';
            }
            dd += phone;
        });
        dd += '</p>';
        dd += '</div>';
    }
    if(item.email != '') {
        dd += '<div class="e-cctb-item">';
        dd += '<span class="e-cctb-name">почта</span>';
        dd += '<p>'+ item.email + '</p>';
        dd += '</div>';
    }



    dd += '</div>';
    dd += '</div>';


    return dd;
}

//обработка кривых номеров
function numberFormat(str) {
    return str.replace('Е512', '<br>Е512');
}

//головной офис - добавить метку на карте
function getHeadOfficePoint(id,val) {

    //var coords = $('#map').attr('data-coords') || '';
    //if (coords != '') return;

    var off = mainOffice[id] || '';
    var email = mainOfficeEmails[val] || '';
    var lat = off.lat || '';
    var lon = off.lon || '';
    var zoom = off.zoom || '';

    var contact = {
        'err':      {
            'qty':  0,
            'mess': '',
        },
        'dealers':    {
            'position': {
                'lat':  lat,
                'lon':  lon,
                'zoom': zoom,
            },
            'items':    [],
        },
    };
    contact.dealers.items.push(Object.assign({}, off));
    contact.dealers.items[0].email = email.email;
    contact.dealers.items[0].phones = email.phone;
    getDealers(contact);
}

//конструктор списка дилеров
function constructDealerList(dealersList) {
    // сперва определим, запрашивался ли список дилеров ранее,
    // если нет - запросим через ajax
    // таким способом сократим количество запросов в базу через ajax при переключении вкладок
    /*if(typeof dealersList === 'undefined') {

    } else {

    }*/
}