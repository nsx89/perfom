<?require($_SERVER["DOCUMENT_ROOT"] . "/include/forms.php");?>

<!-- searchbooster -->
<?/*  // Только для зоны RU
	global $APPLICATION;
    $city = $APPLICATION->get_cookie('my_city');
    $arFilter = Array("IBLOCK_ID"=>7, "ACTIVE"=>"Y", "ID"=>$city);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array(), Array("PROPERTY_country", "PROPERTY_discountregion"));
    while($ob = $res->GetNextElement()) {
        $item = array_merge($ob->GetFields(),$ob->GetProperties());
        $country = $item['PROPERTY_COUNTRY_VALUE'];
		$discountregion = $item['PROPERTY_DISCOUNTREGION_VALUE'];
		
		$res_dis = CIBlockElement::GetList(Array(), Array("IBLOCK_ID"=>8, "ACTIVE"=>"Y", "ID"=>$discountregion), false, Array(), Array("PROPERTY_discount"));
		while($ob_dis = $res_dis->GetNextElement()) {
		$item_dis = array_merge($ob_dis->GetFields(),$ob->GetProperties());
		$discount = $item_dis['PROPERTY_DISCOUNT_VALUE'];	
		}
    }

	if (($country == 3111) && !$discount) {
?>
<script>
	!function(e,t,n,c,o){e[o]=e[o]||function(){(e[o].a=e[o].a||[]).push(arguments)},e[o].h=c,e[o].n=o,e[o].i=1*new Date,s=t.createElement(n),a=t.getElementsByTagName(n)[0],
	s.async=1,s.src=c,a.parentNode.insertBefore(s,a)}(window,document,"script","https://cdn2.searchbooster.net/scripts/v2/init.js","searchbooster"),
	searchbooster({"apiKey":"59ef68dc-6fd7-470e-8ac7-81691b574adb","apiUrl":"https://api4.searchbooster.io","optimizeWidget":true,"theme":{"variables":{"color-primary":"#fe5000"}},"popup":true,"linkTargetBlank":true,"search":{"voice":true},"offer":{"cart":true},"completionSettings":{"orders":["popular","categories","brands","history","suggestions","special_offers","offers"],"totalCompletionCount":18,"limits":{"brands":6,"offers":12,"history":6,"popular":6,"categories":6,"suggestions":6,"special_offers":6}},"locale":"auto","initialized":function(sb) {
                            sb.mount({"selector":".header-search","widget":"search-popup","options":{}});
                        }});
</script>
<? } */?>

<? /*
<script>
    $(document).ready(function() {

        //Google Tag Manager
        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-M25XCJS');

        (function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-N96N627');


        //Facebook Pixel Code
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '744645889403904');
        fbq('track', 'PageView');

        <?if ($fbq_ViewContent) { ?>
        //fbq('track', 'ViewContent');
        <? } ?>

        //Facebook Pixel Code Olya
        !function(f,b,e,v,n,t,s)
        {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)}(window, document,'script',
            'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', '931543860792841');
        fbq('track', 'PageView');


        //Yandex.Metrika counter
        (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
            m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
        (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(22165486, "init", {
            id:22165486,
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true,
            ecommerce:"dataLayer"
        });
        window.dataLayer = window.dataLayer || [];

    })
</script>

<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-M25XCJS" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>

<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-N96N627" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>

<noscript>
    <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=744645889403904&ev=PageView&noscript=1" alt="fb">
</noscript>

<noscript>
    <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=931543860792841&ev=PageView&noscript=1" alt="fb">
</noscript>

<script defer src="https://www.googletagmanager.com/gtag/js?id=UA-45007479-1"></script>

<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}

$(document).ready(function() {
    setTimeout(function(){
        gtag('js', new Date());
        gtag('config', 'UA-45007479-1');
    }, 1500);
});
</script>

<noscript>
    <div><img src="https://mc.yandex.ru/watch/22165486" style="position:absolute; left:-9999px;" alt=""></div>
</noscript>
*/ ?>


<? 
$yandexId = '97077858';

$subdomen = _get_city_loc($my_city);
if (empty($subdomen)) $subdomen = HTTP_HOST;

if ($subdomen <> 'perfom-decor.ru') {
    switch ($my_city) {
        case '3196': $yandexId = '97603898'; break;
        case '3331': $yandexId = '97603906'; break;
        case '3152': $yandexId = '97603858'; break;

        case '3114': $yandexId = '97602887'; break;
        case '6191': $yandexId = '97603014'; break;
        case '3340': $yandexId = '97603302'; break;

        case '102940': $yandexId = '97603326'; break;
        case '3333': $yandexId = '97603374'; break;
        case '3116': $yandexId = '97603400'; break;

        case '3374': $yandexId = '97603428'; break;
        case '3118': $yandexId = '97603455'; break;
        case '3123': $yandexId = '97603533'; break;

        case '3304': $yandexId = '97603553'; break;
        case '3125': $yandexId = '97603578'; break;
        case '3235': $yandexId = '97603604'; break;

        case '3131': $yandexId = '97603617'; break;
        case '6567': $yandexId = '97603635'; break;
        case '3135': $yandexId = '97603664'; break;

        case '3138': $yandexId = '97603682'; break;
        case '3143': $yandexId = '97603701'; break;
        case '24220': $yandexId = '97603769'; break;
        case '3145': $yandexId = '97603789'; break;

        case '3302': $yandexId = '97603813'; break;
        case '3316': $yandexId = '97603837'; break;
        case '3149': $yandexId = '97603850'; break;

        case '3481': $yandexId = '97603867'; break;
        case '3154': $yandexId = '97603875'; break;

        case '3707': $yandexId = '97603915'; break;
        case '3163': $yandexId = '97603924'; break;
        case '3299': $yandexId = '97603934'; break;

        case '3170': $yandexId = '97603944'; break;
        case '3172': $yandexId = '97603951'; break;
        case '3178': $yandexId = '97603960'; break;

        case '3176': $yandexId = '97603971'; break;
        case '3180': $yandexId = '97604000'; break;
        case '3182': $yandexId = '97604013'; break;
        case '6789': $yandexId = '97604025'; break;

        case '52538': $yandexId = '97604037'; break;
        case '3318': $yandexId = '97604042'; break;
        case '3223': $yandexId = '97604044'; break;
        case '3275': $yandexId = '97604048'; break;
    }
}
?>

<!-- Yandex.Metrika counter -->
<script type="text/javascript">
   
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();
   for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
   k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(<?= $yandexId ?>, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true,
        ecommerce:"dataLayer"
   });

   window.dataLayer = window.dataLayer || [];
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/<?= $yandexId ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->


</body>
</html>

<?/*TODO: открыть*/?>
<? 
//if (empty($_GET['test'])) {
    require($_SERVER["DOCUMENT_ROOT"] . "/question_service/achtung_counter.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/question_service/need_comment.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/reports/questions_stat_monthly.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/order_managment/delayed_orders.php");

    //include_once($_SERVER["DOCUMENT_ROOT"].'/cron/urlrewrite/update.php');
//}
?>