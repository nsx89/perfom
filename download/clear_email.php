<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
$APPLICATION->SetTitle("Отмена подписки");

if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/header.php");
?>
<style type="text/css">
  a:hover {
    color: #000 !important;
  }
</style>
<?
$email=$_GET['email'];

   $resc_email = CIBlock::GetList(Array(), Array('CODE' => 'email_base'));
   while($arrc = $resc_email->Fetch()) 
   {
      $blockid_email = $arrc["ID"];
   }

		$arFilter = Array('IBLOCK_ID' => $blockid_email, 'ACTIVE' => 'Y', 'NAME' => $email);
		$db_list_email = CIBlockElement::GetList(Array(), $arFilter);
		if ($fcontact_email = $db_list_email->GetNextElement()) { // есть е-маил
			$fcontact_email = array_merge($fcontact_email->GetFields(), $fcontact_email->GetProperties());
			CIBlockElement::SetPropertyValueCode($fcontact_email['ID'], 'distribution', 'N');
			CIBlockElement::SetPropertyValueCode($fcontact_email['ID'], 'distribution_item', 'N');
			CIBlockElement::SetPropertyValueCode($fcontact_email['ID'], 'off', 'Y');
			?>

    <div id="middle" style="height:450px;">
        <? /*<div id="search_wt">
            <div class="block_t_2">
                <div class="bt2_header">
                    <span>Вы отписались от всех рассылок ЕВРОПЛАСТ</span>
                </div>
                <br>
                <span style="padding-left: 24px; font-size: 18px;"><a href="http://evroplast.ru">Перейти на главную</a></span>
                <br>

                    <span>&nbsp;</span>

            </div>
        </div>*/?>
        <h1 style="text-align: center;
                    font-family: Arial;
                    color: #000;
                    margin-top: 40px;
                    text-transform: uppercase;
                    margin-bottom: 20px;">
                    Вы отписались от всех рассылок ЕВРОПЛАСТ
        </h1>
        <a href="/" style="color: #fe5000;
                          text-decoration: underline;
                          display:table;
                          font-size: 14px;
                          margin: 0 auto;
                          transition: .2s;">
        <i class="icon-arrow-left" style="font-size:10px;"></i>&nbsp;Перейти на главную</a>
    </div>
		<?
		}



?>




<? require($_SERVER["DOCUMENT_ROOT"] . "/include/catalogue/footer.php"); ?>
