<?if(!empty($breadcrumbs_arr) && is_array($breadcrumbs_arr)) { ?>
<div class="breadcrumbs">
    <ul itemprop="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
            <a href="/" title="На главную" itemprop="item">
                <span>главная</span>
                <meta itemprop="position" content="1">
            </a>
        </li>
        <? foreach($breadcrumbs_arr as $b => $b_item) { ?>
            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                <a href="<?=$b_item['link']?>" title="<?=$b_item['title']?>" itemprop="item">
                    <span><?=$b_item['name']?></span>
                    <meta itemprop="position" content="<?=$b+2?>">
                </a>
            </li>
        <? } ?>
    </ul>
</div>
<? } ?>