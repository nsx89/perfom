<?
$wishlist = json_decode($user['PERSONAL_NOTES']);
$data_onpage = $_COOKIE['data_onpage'];
if (!isset($data_onpage)) {
    $data_onpage = 8;
}
//print_r($wishlist);
if( empty($wishlist) ) { ?>
  <div class="wishlist-desc">У вас нет товаров, отмеченных как Избранное <i class="new-icomoon icon-like"></i></div>
<? } else { ?>
  <div class="wishlist-desc">Мы собрали все товары, которые вы отметили как Избранное <i class="new-icomoon icon-like"></i></div>
  <a class="clear-wishlist" data-type="clear-wishlist">Очистить список</a>
    <section class="e-new-catalogue">
      <div class="e-new-cont" data-type="wishlist-cont">
        <? foreach($wishlist as $k=>$item) {
            if($k >= $data_onpage) continue;
            $arOrder = Array();
            $arFilter = Array("ID"=>$item,"ACTIVE"=>"Y");
            $arNavStartParams = Array();
            $arSelect = Array();
            $ar_res = CIBlockElement::GetList($arOrder,$arFilter,false,$arNavStartParams,$arSelect);

            while ( $ob = $ar_res->GetNextElement() ) {
                $product = array_merge($ob->GetFields(), $ob->GetProperties());
            }
            echo get_product_preview($product);
        } ?>
      </div>
    </section>
    <section class="e-new-catalogue-pagination">
      <div class="e-new-cont" data-type="products">
        <div class="pag-wrap">
          <div class="e-new-pag" data-items="<?=count($wishlist)?>" data-onpage="<?=$data_onpage?>" data-type="collection-page"></div>
          <div class="show-all-btn" data-type="show-all">Показать все</div>
          <div class="show-wait">
            <img src="/images/AjaxLoader.gif">
          </div>
        </div>
        <div class="e-new-catalogue-qty">
          <span>Показывать по:</span>
          <a data-val="8" data-type="on-page"<?=($data_onpage == 8)?' class="active"':''?>>8</a>
          <a data-val="12" data-type="on-page"<?=($data_onpage == 12)?' class="active"':''?>>12</a>
          <a data-val="16" data-type="on-page"<?=($data_onpage == 16)?' class="active"':''?>>16</a>
          <span>из <?=count($wishlist)?></span>
        </div>
      </div>
    </section>

<? } ?>