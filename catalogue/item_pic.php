<?
if (!$item) {
    exit;
}
if (isset($files_by_type['410'])) {
    $cut_path = $files_by_type['410'];
    $cut = 'detailed';
} elseif (isset($files_by_type['400'])) {
    $cut_path = $files_by_type['400'];
    if ($item['COMPOSITEPART']['VALUE']) { // для составного элемента
        $cut = 'dr';
    } else { // для простого элемента
        $cut = 'cut';
    }
}
$last_section_name_code = $last_section['CODE'];
list($img_width, $img_height) = getimagesize($_SERVER["DOCUMENT_ROOT"].$cut_path);
list($img_width, $img_height) = getimagesize($_SERVER["DOCUMENT_ROOT"].$cut_path);

if ($img_width >= $img_height) {
    $img_cur_w = 340;
    $img_cur_h = 340/$img_width*$img_height;
} else {
    $img_cur_w = 340/$img_height*$img_width;
    $img_cur_h = 340;
}

$cut_width = '';
if ($last_section_name_code == "nalichniki" || $last_section_name_code == "elementy-kamina") {
    $cut_width = $item['S11']['VALUE']; // для наличников x
} elseif (($last_section_name_code == "arochnye-obramlenija") || ($last_section_name_code == "gibkie-analogi-arochnie")) {
    $cut_width =  $item['S11']['VALUE']; // для арок x
} else {
    if ($item['S1']['VALUE']) {
        $cut_width =  $item['S1']['VALUE'];
    } elseif ($item['S7']['VALUE']) {
        $cut_width =  $item['S7']['VALUE'];
    } elseif ($item['S8']['VALUE']) {
        $cut_width =  $item['S8']['VALUE'];
    } elseif ($item['S9']['VALUE']) {
        $cut_width =  $item['S9']['VALUE'];
    } elseif ($item['S11']['VALUE']) {
        $cut_width =  $item['S11']['VALUE'];
    }
}
$cut_height = '';
if ($last_section_name_code == "nalichniki" || $last_section_name_code == "elementy-kamina") {
    $cut_height =  $item['S9']['VALUE']; // для наличников y
} elseif (($last_section_name_code == "arochnye-obramlenija") || ($last_section_name_code == "gibkie-analogi-arochnie")) {
    $cut_height =  $item['S9']['VALUE']; // для арок y
} elseif ($item['ARTICUL']['VALUE'] == "1.54.020") {
    $cut_height =  $item['S9']['VALUE']; // для 1.54.020
} else {
    if ($item['S3']['VALUE']) {
        $cut_height = $item['S3']['VALUE'];
    } elseif ($item['S10']['VALUE']) {
        $cut_height =  $item['S10']['VALUE'];
    }
}
$cut_class = '';
if($img_width >= $img_height && $cut != 'cut') $cut_class=' cut-h';
if($cut != 'cut') { ?>
    <div class="cut-img-wrap <?=$last_section_name_code?><?=$cut_class?>">
        <img class="<?=$cut?>-img" src="<?=$cut_path?>" alt="cut-img"> 
    </div>
<? } else { ?>
    <div class="cut-img-wrap <?=$last_section_name_code?>">
        <div class="cut-sec">
            <div class="left-sec"><span><?=$cut_width?></span></div>
            <div class="right-sec"></div>
            <div class="top-sec"><span><?=$cut_height?></span></div>
            <div class="bottom-sec"></div>
            <img class="<?=$cut?>-img" src="<?=$cut_path?>" alt="cut-img" style="max-width:<?=$img_cur_w?>px;max-height:<?=$img_cur_h?>px;">
        </div>
    </div>
<? } ?>
