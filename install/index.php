<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Монтаж");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
<div class="main-slider-wrap">
    <!--noindex--><div class="main-slider-preloader"><img src="/img/preloader.gif" alt="Подождите..."></div><!--/noindex-->
    <div class="main-slider" data-type="main-slider">
        <div class="main-slide">
            <div class="main-slide-caption">работа <br>точно в&nbsp;срок</div>
            <img src="/img/install/Perfom_mount_01.jpg" alt="работа точно в срок">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">гарантия <br>5&nbsp;лет</div>
            <img src="/img/install/Perfom_mount_03.jpg" alt="гарантия 5 лет">
        </div>
        <div class="main-slide">
            <div class="main-slide-caption">внимание <br>к&nbsp;каждой детали</div>
            <img src="/img/install/3.jpg" alt="внимание к каждой детали">
        </div>
    </div>
</div>
<section class="main-pref install-pref">
    <div class="content-wrapper">
        <div class="main-pref-txt">
            <h1 class="hidden">Работа точно в срок</h1>
            <h2>монтажная служба</h2>
            <p class="main-pref-annotation">
                Европласт выполняет монтаж собственной
                продукции силами штатной монтажной службы.
                в команде высококвалифицированные специалисты,
                которые быстро и качественно выполнят
                монтаж на вашем объекте.
            </p>
            <p>
                На работы, выполненные нашими специалистами,
                распространяется гарантия в 5 лет. специалисты производят
                работы в срок, уделяя внимание деталям. мы учитываем
                особенности каждого объекта.
            </p>
        </div>
        <div class="main-pref-slider" data-type="install-pref-slider">
            <div class="main-pref-slide install-pref-slide">
                <span class="install-pref-number">5</span>
                <span class="install-pref-txt">лет гарантии <br>на работы</span>
            </div>
            <div class="main-pref-slide install-pref-slide">
                <span class="install-pref-number">15</span>
                <span class="install-pref-txt">лет опыта <br>по монтажу</span>
            </div>
            <div class="main-pref-slide install-pref-slide">
                <span class="install-pref-number">1200</span>
                <span class="install-pref-txt">реализованных <br>проектов</span>
            </div>
            <div class="main-pref-slide install-pref-slide">
                <span class="install-pref-number">15</span>
                <span class="install-pref-txt">монтажных <br>бригад</span>
            </div>
        </div>

    </div>
</section>

<section class="main-instructions">
        <div class="content-wrapper">
            <h2 class="main-blocks-title">инструкции</h2>
            <a href="/download/#manuals" class="main-blocks-link install"><span>Смотреть все&nbsp;инструкции</span> <i class="icon-long-arrow"></i></a>
            <div class="instr-slider" data-type="instr-slider">
                
                <? /*
                <div class="dwnld-instr-item">
                    <a href="/download/manual_baljustrady.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                    <span class="dwnld-instr-item-title">Монтаж балюстрады <br>декоративной</span>
                    <div class="dwnld-instr-item-img">
                        <img src="/download/images/instr-1.png" alt="Монтаж балюстрады декоративной">
                    </div>
                    <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                        <i class="icon-download" data-type="save-pdf"></i>
                        Скачать pdf
                    </div>
                </div>
                */ ?>

                <div class="dwnld-instr-item">
                    <a href="/download/manual_plintusy.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                    <span class="dwnld-instr-item-title">Монтаж плинтуса <br>напольного</span>
                    <div class="dwnld-instr-item-img end">
                        <img src="/download/images/instr-2.png" alt="Монтаж плинтуса напольного">
                    </div>
                    <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                        <i class="icon-download" data-type="save-pdf"></i>
                        Скачать pdf
                    </div>
                </div>

                <div class="dwnld-instr-item">
                    <a href="/download/manual_karnizy_l.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                    <span class="dwnld-instr-item-title">Монтаж карниза <br>для скрытого освещения</span>
                    <div class="dwnld-instr-item-img end">
                        <img src="/download/images/instr-3.png" alt="Монтаж карниза для скрытого освещения">
                    </div>
                    <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                        <i class="icon-download" data-type="save-pdf"></i>
                        Скачать pdf
                    </div>
                 </div>

                <div class="dwnld-instr-item">
                    <a href="/download/manual_karnizy.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                    <span class="dwnld-instr-item-title">Монтаж карниза <br>потолочного</span>
                    <div class="dwnld-instr-item-img end">
                        <img src="/download/images/instr-4_new.png" alt="Монтаж карниза потолочного">
                    </div>
                    <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                        <i class="icon-download" data-type="save-pdf"></i>
                        Скачать pdf
                    </div>
                </div>

                <? /*
                <div class="dwnld-instr-item">
                    <a href="/download/manual_karnizi.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                    <span class="dwnld-instr-item-title">Монтаж карниза <br>под кровлю</span>
                    <div class="dwnld-instr-item-img end">
                        <img src="/download/images/instr-5.png" alt="Монтаж карниза под кровлю">
                    </div>
                    <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                        <i class="icon-download" data-type="save-pdf"></i>
                        Скачать pdf
                    </div>
                </div>
                */ ?>

                <div class="dwnld-instr-item">
                    <a href="/download/manual_moldingi.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                    <span class="dwnld-instr-item-title">Монтаж молдинга <br>настенного</span>
                    <div class="dwnld-instr-item-img end">
                        <img src="/download/images/instr-8_new.png" alt="Монтаж молдинга настенного">
                    </div>
                    <div class="dwnld-instr-item-btn">
                        <i class="icon-download" data-type="save-pdf"></i>
                        Скачать pdf
                    </div>
                </div>

                <div class="dwnld-instr-item">
                    <a href="/download/manual_rozetki.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                    <span class="dwnld-instr-item-title">Монтаж розетки <br>потолочной</span>
                    <div class="dwnld-instr-item-img">
                        <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-7.png" alt="Монтаж розетки потолочной">
                    </div>
                    <div class="dwnld-instr-item-btn" data-type="dwnld-instr">
                        <i class="icon-download" data-type="save-pdf"></i>
                        Скачать pdf
                    </div>
                </div>

                <div class="dwnld-instr-item">
                    <a href="/download/manual_nails.pdf" target="_blank" title="Скачать инструкцию по монтажу"></a>
                    <span class="dwnld-instr-item-title">Монтаж лепнины <br>с применением <br>финишных гвоздей</span>
                    <div class="dwnld-instr-item-img end">
                        <img class="img-load" src="/img/1x1.png" data-src="/download/images/instr-6_new.png" alt="Монтаж лепнины с применением финишных гвоздей">
                    </div>
                    <div class="dwnld-instr-item-btn">
                        <i class="icon-download" data-type="save-pdf"></i>
                        Скачать pdf
                    </div>
                </div>

            </div>
        </div>
    </section>

<section class="main-gallery install">
    <div class="content-wrapper">
            <div class="main-gallery-title-block">
                <h2 class="main-blocks-title">Реализованные проекты</h2>
                <a href="/gallery/" class="main-blocks-link install"><span>смотреть все проекты</span> <i class="icon-long-arrow"></i></a>
            </div>
            <div class="main-gallery-slider" data-type="main-gallery-slider">
 
				<? require_once($_SERVER["DOCUMENT_ROOT"] . "/include/main_gallery.php"); ?>
 
            </div>
        </div>
</section>

<? require_once($_SERVER["DOCUMENT_ROOT"] . '/include/main_contact.php');?>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}