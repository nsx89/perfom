<?
//https://dev.1c-bitrix.ru/api_help/main/general/admin.section/menu.php

//список иконок
//https://gist.github.com/geff21st/aa4ee371e77c6a07e4b4fac852571d41


return array(
    'parent_menu' => 'global_menu_content', // раздел, где выводить пункт меню
    'text' => MEDIA_NAME,
    'icon' => 'blog_menu_icon', // имя класса для вывода иконки
    'url' => 'media.php?lang='.LANGUAGE_ID,
    // Подпункты
    'items' => [
        ['text' => 'Материалы', 'url' => 'media_pages.php?lang='.LANGUAGE_ID],
        ['text' => 'Категории', 'url' => 'media_category.php?lang='.LANGUAGE_ID],
        ['text' => 'Фильтры', 'url' => 'media_filter.php?lang='.LANGUAGE_ID],
        ['text' => 'Жалобы', 'url' => 'media_complain.php?lang='.LANGUAGE_ID],
    ],
    'sort' => '10',
);

?>