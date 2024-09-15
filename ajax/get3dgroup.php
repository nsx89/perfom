<?require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog.php");
require_once($_SERVER["DOCUMENT_ROOT"] . "/catalogue/filter.php");
if (!CModule::IncludeModule('iblock') || !CModule::IncludeModule("catalog")) {
    exit;
}

global $iblockid;
$iblockid = 12;

$group = $_GET['id'];

$models_path = $_SERVER["DOCUMENT_ROOT"]."/cron/catalog/data/models";
$models_web_path = "/cron/catalog/data/models";
$products = array();
$arFilter_element = null; // фильтровать страны - доработать

$prod_arr_first = Array();
$prod_arr_second = Array();
$arFilter = array('IBLOCK_ID'=>IB_CATALOGUE, 'SECTION_ID' => $group,'ACTIVE' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y', $arFilter_element, "PROPERTY_SHOW_PERFOM" => "Y");
$_element = CIBlockElement::GetList(array('SORT'=>'ASC', 'PROPERTY_ARTICUL'=>'ASC'), $arFilter, false, false, array("ID", "IBLOCK_ID", "PROPERTY_ARTICUL"));
while ($item = $_element->GetNextElement()) {

    $item = $item->fields;

        $model_files = array();
        foreach (array('max','gsm','dwg','3ds','obj') as $m_pre) {
            $path = $models_path . "/" . $m_pre . "/" . $item['PROPERTY_ARTICUL_VALUE'] . '.'.$m_pre;
            $web_path = $item['PROPERTY_ARTICUL_VALUE'] . '.'.$m_pre;
            if (!file_exists($path)) {
                continue;
            }
            $model_files[$m_pre] = array('FILE'=>$web_path,'data_val'=> 'd3'.$m_pre.'['.$web_path.']','data_type'=> 'd3'.$m_pre);
        }
    if(str_split($item['PROPERTY_ARTICUL_VALUE'],1)[0] == 6) {
        $prod_arr_first[] = array('ARTICUL'=>$item['PROPERTY_ARTICUL_VALUE'], 'FILES'=>$model_files);
    } else {
        $prod_arr_second[] = array('ARTICUL'=>$item['PROPERTY_ARTICUL_VALUE'], 'FILES'=>$model_files);
    }
}
$products = array_merge($prod_arr_first, $prod_arr_second);
ob_start();
$n = 0;
foreach($products as $item_data) { ?>
<? if($item_data['ARTICUL']!=='m_comp_b201'&&$item_data['ARTICUL']!=='m_comp_b101' && isset($item_data['FILES']) && count($item_data['FILES'])>0):?>
<tr>
<?if (isset($item_data['FILES']) && count($item_data['FILES'])>0) { ?>
    <td><span data-type="choose-row"></span></td>
<? } else { ?>
    <td></td>
<? } ?>
<td><?=$item_data['ARTICUL']?></td>
 <? foreach (array('max','3ds','gsm','dwg','obj') as $m_pre) { ?>
    <?if (isset($item_data['FILES']) && isset($item_data['FILES'][$m_pre])) {?>
        <td><span data-val="<?=$item_data['FILES'][$m_pre]['data_val']?>" data-type="<?=$item_data['FILES'][$m_pre]['data_type']?>" data-click="choose-one"></span></td>
    <? } else { ?>
        <td></td>
    <? } ?>
 <? } ?>
</tr>
<?$n++;?>
<? endif;?>
<? } ?>
<? if($n == 0) { ?>
    <tr>
        <td class="no-models" colspan="7">модели не найдены</td>
    </tr>
<? } ?>
<?
$html = ob_get_clean();
print json_encode($html);


?>
