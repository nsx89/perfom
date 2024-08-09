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
$yandexId = $yandexDefaultId = '97077858';

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
        case '3431': $yandexId = '97604062'; break;
        case '3756': $yandexId = '97604069'; break;
        case '3830': $yandexId = '97604087'; break;
        case '46432': $yandexId = '97604096'; break;
        case '6142': $yandexId = '97604102'; break;
        case '6137': $yandexId = '97604111'; break;
        case '3239': $yandexId = '97604121'; break;
        case '3260': $yandexId = '97604127'; break;
        case '3329': $yandexId = '97604136'; break;
        case '3133': $yandexId = '97604142'; break;
        case '3255': $yandexId = '97604151'; break;
        case '3127': $yandexId = '97604158'; break;
        case '6119': $yandexId = '97604166'; break;
        case '3187': $yandexId = '97604174'; break;
        case '3189': $yandexId = '97604190'; break;
        case '3192': $yandexId = '97604200'; break;
        case '3213': $yandexId = '97604205'; break;
        case '3194': $yandexId = '97604213'; break;
        case '6132': $yandexId = '97604225'; break;
        case '3129': $yandexId = '97604231'; break;
        case '3338': $yandexId = '97604242'; break;
        case '3733': $yandexId = '97604250'; break;
        case '3204': $yandexId = '97604267'; break;
        case '3211': $yandexId = '97604274'; break;
        case '3729': $yandexId = '97604278'; break;
        case '3215': $yandexId = '97604289'; break;
        case '3217': $yandexId = '97604294'; break;
        case '3209': $yandexId = '97604304'; break;
        case '3220': $yandexId = '97604323'; break;
        case '3168': $yandexId = '97604338'; break;
        case '130517': $yandexId = '97604348'; break;
        case '3326': $yandexId = '97604355'; break;
        case '3228': $yandexId = '97604364'; break;
        case '3161': $yandexId = '97604369'; break;
        case '3233': $yandexId = '97604380'; break;
        case '3231': $yandexId = '97604389'; break;
        case '6535': $yandexId = '97604397'; break;
        case '3237': $yandexId = '97604404'; break;
        case '3321': $yandexId = '97603510'; break;
    }
}
?>

<? if ($yandexId <> $yandexDefaultId) { ?>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
       
       (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
       m[i].l=1*new Date();
       for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
       k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
       (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

       ym(<?= $yandexDefaultId ?>, "init", {
            clickmap:true,
            trackLinks:true,
            accurateTrackBounce:true,
            webvisor:true,
            ecommerce:"dataLayer"
       });

       window.dataLayer = window.dataLayer || [];
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/<?= $yandexDefaultId ?>" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
    
<? } ?>

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

<!-- Pixel -->
<script type="text/javascript">!function(){var t=document.createElement("script");t.type="text/javascript",t.async=!0,t.src='https://vk.com/js/api/openapi.js?173',t.onload=function(){VK.Retargeting.Init("VK-RTRG-1869498-hPgXu"),VK.Retargeting.Hit()},document.head.appendChild(t)}();</script><noscript><img src="https://vk.com/rtrg?p=VK-RTRG-1869498-hPgXu" style="position:fixed; left:-999px;" alt=""/></noscript>
<!-- /Pixel -->

</body>
</html>

<?/*TODO: открыть*/?>
<? 
//if (empty($_GET['test'])) {
    require($_SERVER["DOCUMENT_ROOT"] . "/question_service/achtung_counter.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/question_service/need_comment.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/reports/questions_stat_monthly.php");
    require($_SERVER["DOCUMENT_ROOT"] . "/order_managment/delayed_orders.php");

    include_once($_SERVER["DOCUMENT_ROOT"].'/cron/urlrewrite/update.php');
//}
?>