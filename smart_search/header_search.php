<!--noindex-->
<div class="smart-search-wrap" style="display: none;" data-type="smart-search-wrap">
    <i class="icon-close smart-search-close" data-type="smart-search-close"></i>
    <div class="smart-search-top-panel">
        <form class="smart-search-form">
            <input type="text" placeholder="поиск" data-type="smart-search-input">
            <i class="icon-close" data-type="smart-search-clear"></i>
            <button type="button" class="smart-search-btn" data-type="smart-search-btn"><i class="icon-search"></i></button>
        </form>
        <div class="smart-search-result-short">
            <div class="smart-search-result-qty">
                Результаты поиска: <span>0</span>
            </div>
            <a href="/search/" class="smart-search-result-short-link" data-type="smart-search-show-all">Показать все товары <i class="icon-angle-right"></i></a>
        </div>
    </div>
    <div class="smart-search-bottom-panel">
        <div class="smart-search-wait"><img src="/img/preloader.gif" alt="wait..."></div>
        <div class="smart-search-results">
            <div class="smart-search-no-results">Поиск не дал результатов</div>
            <div class="smart-search-short-res">
                <div class="smart-search-res-table" data-type="search-scroll">
                    <div class="smart-search-res-item-wrap" data-type="smart-search-items-wrap"></div>
                </div>
                <a href="/search/" class="smart-search-res-btn" data-type="smart-search-show-all">Показать все товары</a>
            </div>
        </div>
    </div>
</div>
<link rel="stylesheet" href="/smart_search/search.css?<?=$random?>">
<script defer src="/smart_search/search.js?<?=$random?>"></script>
<!--/noindex-->