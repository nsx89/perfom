<?
    require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
    if (!CModule::IncludeModule('iblock')) exit;
    $APPLICATION->SetTitle("NEW ART DECO");
    $APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");

    /*
        // Только модераторы и выше
        $user_id = $USER->GetID();
                $user_group_arr = [];
                $res = CUser::GetUserGroupList($user_id);
                while ($arGroup = $res->Fetch()) {
                    $user_group_arr[] = $arGroup['GROUP_ID'];
                }
                if(!$USER->IsAuthorized() || in_array(5,$user_group_arr)) {
                    LocalRedirect('/404.php');
                    exit;
                }
    */
    $prod_arr = array();
    //$arFilter = Array("IBLOCK_ID"=>IB_CATALOGUE,"ACTIVE"=>"Y","PROPERTY_MAURITANIA"=>"Y");
    $arFilter = Array("IBLOCK_ID"=>IB_CATALOGUE,"ACTIVE"=>"Y","PROPERTY_NEW_ART_DECO"=>"Y");
    $res = CIBlockElement::GetList(Array('SORT' => 'ASC'), $arFilter, false, Array(), Array()); // 'PROPERTY_ARTICUL' => 'ASC' //'SORT' => 'ASC'
    $coll_qty = $res->SelectedRowsCount();
    while($ob = $res->GetNextElement()){
        $arFields =  array_merge($ob->GetFields(), $ob->GetProperties());
        //print_r($arFields);
        $db_groups = CIBlockElement::GetElementGroups($arFields['ID']);
        while($ar_group = $db_groups->Fetch()) {
            $group = $ar_group;
        }
        $prod_arr[$group['CODE']]['info'] = $group;
        $prod_arr[$group['CODE']]['prod'][] = $arFields;
    }
    //print_r($prod_arr);

require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");

$breadcrumbs_arr = Array(
    Array(
        'name' => 'коллекции',
        'link' => '/collection/',
        'title' => 'коллекции',
    ),
    Array(
        'name' => 'new art deco',
        'link' => '/collection/new_art_deco/',
        'title' => 'new art deco',
    ),
);
?>

<div class="main-slider-wrap">
    <!--noindex--><div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div><!--/noindex-->
    <div class="main-slider collection-slider has-txt" data-type="main-slider">
        <div class="main-slide">
            <div class="main-slide-caption white">
                <h1>new art deco</h1>
                <div class="main-slide-caption-txt">
                    <p>Бренд «Европласт» представляет новую коллекцию интерьерной лепнины NEW&nbsp;ART&nbsp;DECO.</p>
                    <p>Это коллекция-конструктор, позволяющая дизайнеру выразить себя через многообразие органичных сочетаний, отсутствие ограничений и&nbsp;полноту свободы творчества.</p>
                </div>
            </div>
            <img class="img-load" src="/img/1.png" data-src="/collection/img/slider/new_art_deco/1.jpg" alt="new art deco">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">
                new art deco
                <div class="main-slide-caption-txt">
                    <p>Все элементы коллекции многофункциональны и&nbsp;позволяют создавать даже самые сложные решения за&nbsp;счет своей сочетаемости, <br>подстраиваясь под&nbsp;различные тенденции и&nbsp;стили интерьера.</p>
                </div>
            </div>
            <img class="img-load" src="/img/1.png" data-src="/collection/img/slider/new_art_deco/2.jpg" alt="new art deco">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption white">
                new art deco
                <div class="main-slide-caption-txt">
                    <p>Уникальный элемент коллекции&nbsp;- стеновые панели с&nbsp;самым глубоким рельефом на&nbsp;рынке, легко сочетающиеся со&nbsp;всеми остальными элементами коллекции.</p>
                </div>
            </div>
            <img class="img-load" src="/img/1.png" data-src="/collection/img/slider/new_art_deco/3.jpg" alt="new art deco">
        </div>
    </div>
</div>

<? $sort_group = array(
        'dekorativnii-paneli',
        'karnizy',
        'moldingi',
        'uglovye-jelementy',
        'plintusy',
        'dekorativnye-elementy',
        'rozetki',
        'kaminy',
        'elementy-kamina',
        'kronshtejny',
    );
?>
<section class="collection-wrap">
    <div class="content-wrapper">

        <? require_once($_SERVER["DOCUMENT_ROOT"] . "/include/breadcrumbs.php"); ?>
        
        <?if($coll_qty > 0) { ?>
        <div class="col-prod-nav" data-type="col-prod-nav">
            <?foreach($sort_group as $item) { ?>
                <a class="col-prod-nav-item" href="#<?=$prod_arr[$item]['info']['CODE']?>" data-type="nav"><?=$prod_arr[$item]['info']['NAME']?></a>
            <? } ?>
        </div>
        <div class="col-prod-tabs-wrap">
            <?foreach($sort_group as $item) { ?>
                <div class="col-prod-tab" id="#<?=$prod_arr[$item]['info']['CODE']?>" data-type="tab">
                    <div>
                        <?$array_item = array(); // убираем дубли ?>
                        <?foreach($prod_arr[$item]['prod'] as $prod) {
                            if (in_array($prod['ARTICUL']['VALUE'],$array_item)) continue;
                            $array_item[] = $prod['ARTICUL']['VALUE'];
                            echo get_product_preview($prod,false,false,false);
                        }?>
                    </div>
                </div>
            <? } ?>
        </div>
        <? } else {?>
            <div class="empty-collection">Не найдено товаров, относящихся к&nbsp;этой&nbsp;коллекции.</div>
        <? } ?>
    </div>
</section>

<section class="catalogue-collection catalogue-collection-video">
    <div class="content-wrapper">
        <h2 class="main-blocks-title">Видеопрезентация новой коллекции <br>New&nbsp;art&nbsp;deco от&nbsp;Виктора&nbsp;Дембовского</h2>
        <div class="news-item-video-section">
            <iframe class="iframe-load" width="800" height="450" src="/img/1x1.png" data-src="https://www.youtube.com/embed/36WB0CIErU4" title="YouTube video player" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen style="border: 0"></iframe>
        </div>
    </div>
</section>

<script defer src="/collection/collection.js"></script>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
