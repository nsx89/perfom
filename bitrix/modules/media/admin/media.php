<?php
require_once('header.php');

$APPLICATION->SetTitle('Медиа-центр');

$menu = include 'menu.php';
?>

<br>
<table class="main-grid-table">
    <tbody>
        <? foreach ($menu['items'] AS $item) { ?>
            <tr class="main-grid-row main-grid-row-body">
                <td class="main-grid-cell main-grid-cell-left">
                    <div class="main-grid-cell-inner">
                        <span class="main-grid-cell-content">
                            <strong><a class="main-grid-strong" href="/bitrix/admin/<?= $item['url'] ?>"><?= $item['text'] ?></a></strong>
                        </span>
                    </div>
                </td>
            </tr>
        <? } ?>
    </tbody>
</table>


<?php
require_once('footer.php');
?>