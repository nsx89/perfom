function showTab(navHash) {
  $('[data-type="nav"]').each(function() {
    $(this).removeClass('active');
  })
  $('[data-type="tab"]').each(function() {
    $(this).removeClass('active');
  })
  if(!navHash) navHash = location.hash;
  if (!navHash) {
    $('[data-type="nav"]').first().addClass('active');
    $('[data-type="tab"]').first().addClass('active');
  } else {
    $('[href="'+navHash+'"]').addClass('active');
    $('[id="'+navHash+'"]').addClass('active');
  }
  return false;
}
$('[data-type="nav"]').on('click',function() {
    showTab($(this).attr('href'));
})
$('.e-new-catalogue-collection').on('click','[data-type="all-photos"]',function() {
    $('.second-block').css('display','flex');
    $(this).html('Свернуть фото');
    $(this).attr('data-type','no-all-photos');
})
$('.e-new-catalogue-collection').on('click','[data-type="no-all-photos"]',function() {
  $('.second-block').hide();
  $(this).html('Все фото коллекции');
  $(this).attr('data-type','all-photos');
})
function pagination(){
  var items = $('[data-type="collection-page"]').attr('data-items');
  var onpage = $('[data-type="collection-page"]').attr('data-onpage');
  $('[data-type="collection-page"]').pagination({
    items: items,
    itemsOnPage: onpage,
    displayedPages: 4,
    edges: 1,
    nextText: 'Далее',
  });
}
function cataloguePagination() {
  var newPage = $('[data-type="collection-page"]').pagination('getCurrentPage');
  var id = $('[data-type="collection-page"]').attr('data-id');
  var req = '/ajax/new_page_collection.php?page='+newPage+'&id='+id;
  $.get(req, function(data){
    var elems = $.parseJSON(data);
    $('[data-type="collection-wrap"]').html(elems);
  });
}
function collectionInit() {
  showTab();
  if (typeof fancybox === "function") {
    $('[data-fancybox="slider"]').fancybox({
      buttons: [
        "download",
        "close"
      ],
      wheel: "false",
      transitionEffect: "slide",
      lang: "ru",
      cyclic: true,
      i18n: {
        ru: {
          CLOSE: "Закрыть",
          NEXT: "Следующий",
          PREV: "Предыдущий",
          ERROR: "The requested content cannot be loaded. <br/> Please try again later.",
          PLAY_START: "Start slideshow",
          PLAY_STOP: "Pause slideshow",
          FULL_SCREEN: "Полный экран",
          THUMBS: "Миниатюры",
          DOWNLOAD: "Скачать",
          SHARE: "Поделиться",
          ZOOM: "Увеличить"
        }
      }
    });
  }
  pagination();
}
$(document).ready(function() {
  //прокручиваем слайдер
  let box = $('.collection-wrap')[0].getBoundingClientRect(),
      top = box.top + pageYOffset - $('header').outerHeight();
  $('html,body').animate({scrollTop: top}, 500);
})
document.addEventListener("DOMContentLoaded", collectionInit);