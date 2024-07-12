
<link href="/css/main.css?v=1" type="text/css"  rel="stylesheet"/>
<link href="/css/responsive.css" type="text/css"  rel="stylesheet"/>
<link href="<?= MEDIA_FOLDER ?>/css/error.css" type="text/css"  rel="stylesheet"/>
<div class="content-wrapper content-wrapper-error">
    <img src="/img/404.png?v=1" alt="404" class="error-img">
    <h1 class="error-title">Ошибка</h1>
    <div class="error-btns">
        <a href="<?= MEDIA_FOLDER ?>" class="error-back-main">На главную страницу</a>
    <? if($_SERVER['HTTP_REFERER'] !== false) { ?>
        <a href="<?=$_SERVER['HTTP_REFERER']?>" class="error-back">Назад</a>
    <? } ?>
    </div>
</div>

<? require_once("foot.php"); ?>