</div>

<div class="m-media-scroll-top">наверх</div>

<div class="m-media-load-img">
    <img src="<?= MEDIA_FOLDER ?>/img/notify-black.svg" alt="notify">
    <img src="<?= MEDIA_FOLDER ?>/img/flag-black.svg" alt="flag">
    <img src="<?= MEDIA_FOLDER ?>/img/arrow-black.svg" alt="arrow">
    <img src="<?= MEDIA_FOLDER ?>/img/fav-black.svg" alt="fav">
    <img src="<?= MEDIA_FOLDER ?>/img/fav-fill.svg" alt="fav-fill">
    <img src="<?= MEDIA_FOLDER ?>/img/flag-fill.svg" alt="flag-fill">
</div>
<div class="m-media-yandex-share">
    <script src="https://yastatic.net/share2/share.js"></script>
</div>

</section>

<script async defer src="//assets.pinterest.com/js/pinit.js"></script>

<?
require("modals.php");

require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");

if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
?>