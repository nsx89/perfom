<?php
$arUrlRewrite=array (
  0 => 
  array (
    'CONDITION' => '#^/news/seminars-and-conferences/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  22 => 
  array (
    'CONDITION' => '#^/mag/seminars-and-conferences/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  2 => 
  array (
    'CONDITION' => '#^/online/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  44 => 
  array (
    'CONDITION' => '#^/video/([\\.\\-0-9a-zA-Z]+)(/?)([^/]*)#',
    'RULE' => 'alias=$1&videoconf',
    'ID' => 'bitrix:im.router',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  3 => 
  array (
    'CONDITION' => '#^/news/new-products/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  4 => 
  array (
    'CONDITION' => '#^/files/cache/(.*)/(.*)/(.*)#',
    'RULE' => 'SUBDIR=$1&IMAGE_TYPE=$2&IMAGE=$3',
    'ID' => '',
    'PATH' => '/catalogue/image.php',
    'SORT' => '100',
  ),
  29 => 
  array (
    'CONDITION' => '#^/moderation/order/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/moderation/order.php',
    'SORT' => '100',
  ),
  23 => 
  array (
    'CONDITION' => '#^/mag/new-products/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  5 => 
  array (
    'CONDITION' => '#^/news/exhibitions/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  6 => 
  array (
    'CONDITION' => '#^/news/new-stores/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  24 => 
  array (
    'CONDITION' => '#^/mag/exhibitions/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  7 => 
  array (
    'CONDITION' => '#^/bitrix/services/ymarket/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/bitrix/services/ymarket/index.php',
    'SORT' => '100',
  ),
  25 => 
  array (
    'CONDITION' => '#^/mag/new-stores/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  45 => 
  array (
    'CONDITION' => '#^/collection/new_art_deco#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/collection/new_art_deco.php',
    'SORT' => '100',
  ),
  14 => 
  array (
    'CONDITION' => '#^/responsive/catalogue/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/responsive/catalogue/index.php',
    'SORT' => 100,
  ),
  36 => 
  array (
    'CONDITION' => '#^/personal/registration#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/personal/registration.php',
    'SORT' => '100',
  ),
  37 => 
  array (
    'CONDITION' => '#^/personal/forget_pass#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/personal/forget_pass.php',
    'SORT' => '100',
  ),
  8 => 
  array (
    'CONDITION' => '#^/news/media/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  1 => 
  array (
    'CONDITION' => '#^/news/forum/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  35 => 
  array (
    'CONDITION' => '#^/personal/show_order#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/personal/show_order.php',
    'SORT' => '100',
  ),
  9 => 
  array (
    'CONDITION' => '#^/online/(/?)([^/]*)#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/desktop_app/router.php',
    'SORT' => 100,
  ),
  27 => 
  array (
    'CONDITION' => '#^/mag/media/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  26 => 
  array (
    'CONDITION' => '#^/mag/forum/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  41 => 
  array (
    'CONDITION' => '#^/cart/mounting/demo#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/cart/mounting/icomoon/demo.php',
    'SORT' => '100',
  ),
  10 => 
  array (
    'CONDITION' => '#^/stssync/calendar/#',
    'RULE' => '',
    'ID' => 'bitrix:stssync.server',
    'PATH' => '/bitrix/services/stssync/calendar/index.php',
    'SORT' => 100,
  ),
  20 => 
  array (
    'CONDITION' => '#^/gallery/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/gallery/item.php',
    'SORT' => '100',
  ),
  38 => 
  array (
    'CONDITION' => '#^/catalogue_test/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/catalogue_test/index.php',
    'SORT' => '100',
  ),
  42 => 
  array (
    'CONDITION' => '#^/cart/mounting#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/cart/mounting/index.php',
    'SORT' => '100',
  ),
  28 => 
  array (
    'CONDITION' => '#^/mag/([0-9]+)#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  15 => 
  array (
    'CONDITION' => '#^/contact_main#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/responsive/contact_main/index.php',
    'SORT' => 100,
  ),
  32 => 
  array (
    'CONDITION' => '#^/mosbuild2019#',
    'RULE' => 'ID=60419',
    'ID' => '',
    'PATH' => '/mag/item.php',
    'SORT' => '100',
  ),
  11 => 
  array (
    'CONDITION' => '#^/references/#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/mag/index.php',
    'SORT' => '100',
  ),
  12 => 
  array (
    'CONDITION' => '#^/catalogue/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalogue/index.php',
    'SORT' => 100,
  ),
  16 => 
  array (
    'CONDITION' => '#^/downloads#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/download/index.php',
    'SORT' => 100,
  ),
  19 => 
  array (
    'CONDITION' => '#^/dealer/lk#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/factory/lk/index.php',
    'SORT' => 100,
  ),
  17 => 
  array (
    'CONDITION' => '#^/mounting#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/install/index.php',
    'SORT' => 100,
  ),
  40 => 
  array (
    'CONDITION' => '#^/service#',
    'RULE' => 'ID=$1',
    'ID' => '',
    'PATH' => '/professional/index.php',
    'SORT' => '100',
  ),
  31 => 
  array (
    'CONDITION' => '#^/angles#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/angles/index.php',
    'SORT' => '100',
  ),
  18 => 
  array (
    'CONDITION' => '#^/about#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/company/index.php',
    'SORT' => 100,
  ),
  13 => 
  array (
    'CONDITION' => '#^/news#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/mag/index.php',
    'SORT' => 100,
  ),
  30 => 
  array (
    'CONDITION' => '#^/mag#',
    'RULE' => '',
    'ID' => '',
    'PATH' => '/mag/index.php',
    'SORT' => 100,
  ),
  43 => 
  array (
    'CONDITION' => '#^/#',
    'RULE' => '',
    'ID' => 'bitrix:catalog',
    'PATH' => '/catalogue/index.php',
    'SORT' => 20,
  ),
);
