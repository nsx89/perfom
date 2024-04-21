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

$_element = CIBlockElement::GetList(array('PROPERTY_ARTICUL'=>'ASC'), array('SECTION_ID' => $group, 'ACTIVE' => 'Y', 'INCLUDE_SUBSECTIONS' => 'Y', $arFilter_element));
    while ($item = $_element->GetNextElement()) {
        $item = array_merge($item->GetFields(), $item->GetProperties());


        $model_files = array();
        foreach (array('max','gsm','dwg','3ds','obj') as $m_pre) {
            $path = $models_path . "/" . $m_pre . "/" . $item['ARTICUL']['VALUE'] . '.'.$m_pre;
            $web_path = $item['ARTICUL']['VALUE'] . '.'.$m_pre;
            if (!file_exists($path)) {
                continue;
            }
            $model_files[$m_pre] = array('FILE'=>$web_path,'data_val'=> 'd3'.$m_pre.'['.$web_path.']','data_type'=> 'd3'.$m_pre);
        }
	$products[] = array('ARTICUL'=>$item['ARTICUL']['VALUE'], 'FILES'=>$model_files);
    }

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
