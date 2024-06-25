<section class="pers-acc-top" user-id="<?=$user_id?>">
    <div class="pers-acc-top-wrap" data-type="om-personal-tabs">
        <h1><i class="icon-profile"></i> <?=$user['LAST_NAME'] != '' ? $user['LAST_NAME'].' '.$user['NAME'] : $user['NAME']?></h1>
        <h2 class="om-manager-info"><?=$user['PERSONAL_PROFESSION']?></h2>
        <? $mail = $user['PERSONAL_MAILBOX'] != '' ? $user['PERSONAL_MAILBOX'] : $user['EMAIL']; ?>
        <div class="om-manager-info"><?=$mail?></div>
        <?if($USER->IsAuthorized() && in_array($user_stat,array('mod','admin'))) { ?>
            <a href="/question_service/moderation.php" class="pacc-user-mail"><i class="new-icomoon icon-chat"></i> переход в&nbsp;раздел "Справочная"</a>
        <? } ?>
        <a href="?logout=yes" class="pacc-logout om-manager-info">выйти</a>
        <div class="pers-acc-top-bg"><img src="/img/main-pref-bg.svg" alt="bg"></div>
    </div>
</section>

<style>
    .content {
        background: #F2F2F2;
    }
</style>