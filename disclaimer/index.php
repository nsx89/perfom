<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
if (!CModule::IncludeModule('iblock')) exit;
$APPLICATION->SetTitle("Дисклеймер");
$APPLICATION->SetPageProperty("description", "Европласт - производство полиуретановых изделий, лидер на российском рынке");
require($_SERVER["DOCUMENT_ROOT"] . "/include/header.php");
?>
    <div class="main-banner company-banner">
        <div class="main-slide-caption white">
            Дисклеймер
        </div>
        <img src="/img/disclaimer.jpg" alt="дисклеймер">
    </div>
    <section class="dealers-main-txt">
        <div class="content-wrapper">
            <p>
                Все материалы, представленные на&nbsp;данном Сайте perfom-decor.ru принадлежат ООО&nbsp;«Декор» (ИНН&nbsp;777609512, КПП&nbsp;504801001, ОГРН&nbsp;1067760097822, 142350, Московская область, городской округ Чехов, деревня Ивачково, Лесная&nbsp;ул., вл.&nbsp;12 стр.&nbsp;7). Все права защищены. Никакие содержащиеся на&nbsp;Сайте материалы или&nbsp;их&nbsp;часть не&nbsp;могут быть воспроизведены, использованы или&nbsp;переданы третьим лицам в&nbsp;целях извлечения прибыли без&nbsp;предварительного согласия ООО&nbsp;«Декор» в&nbsp;письменной&nbsp;форме.
            </p>
            <p>
                Вся содержащаяся на&nbsp;Сайте информация носит исключительно ознакомительный характер, не&nbsp;является исчерпывающей и&nbsp;не&nbsp;является публичной офертой, определяемой положениями статьи&nbsp;437 Гражданского кодекса РФ. ООО&nbsp;«Декор» не&nbsp;гарантирует абсолютные точность, полноту и&nbsp;достоверность информации, содержащейся на&nbsp;Сайте. ООО&nbsp;«Декор» оставляет за&nbsp;собой право в&nbsp;любой момент вносить изменения в&nbsp;содержащуюся на&nbsp;Сайте информацию без&nbsp;дополнительного уведомления. Информацию необходимо уточнять по&nbsp;телефонам компании. ООО&nbsp;«Декор» ни&nbsp;в&nbsp;коем&nbsp;случае не&nbsp;несет ответственности перед&nbsp;какими-либо лицами за&nbsp;ущерб или&nbsp;убытки, понесенные ими в&nbsp;результате использования информации, содержащейся на&nbsp;данном&nbsp;Сайте.
            </p>
        </div>
    </section>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/include/footer.php");
if(defined("B_PROLOG_INCLUDED") && B_PROLOG_INCLUDED===true)
{
    require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog.php");
}
