<?
global $USER;

if(!$USER->IsAuthorized()) {
    // ищем по email
    $filter = Array("EMAIL" => $_REQUEST['email']);
    $rsUsers = CUser::GetList(($by="ID"), ($order="ASC"), $filter);
    while ($arUser = $rsUsers->Fetch()) {
        $old_user_id = $arUser['ID'];
    }
    // если по email не найдено, регистрируем и высылаем письмо с регистрацией
    if($old_user_id == '') {

        //user registration

        $characters = 'ABCDEFGHIJKLMNPQRSTUVWXYZabcdefghijklmnpqrstuvwxyz-_?#123456789';
        $randstring = '';
        for ($i = 0; $i < 8; $i++) {
            $created_pass .= $characters[rand(0, strlen($characters)-1)];
        }

        $arResult = $USER->Register(
            $_REQUEST['email'],
            $_REQUEST['name'],
            $_REQUEST['lastname'],
            $created_pass,
            $created_pass,
            $_REQUEST['email']
        );

        //save user phone
        if($arResult['TYPE'] == 'OK') {

            $user = new CUser;
            $fields = Array(
                "PERSONAL_PHONE" => $_REQUEST['phone'],
                "PERSONAL_CITY"     => $_REQUEST['city'],
                "PERSONAL_ZIP"      => $_REQUEST['street'],
                "PERSONAL_STREET"   => $_REQUEST['house'],
                "PERSONAL_MAILBOX"  => $_REQUEST['apartment'],
            );
            $user->Update($arResult['ID'], $fields);
            $old_user_id = $arResult['ID'];

        }

    }
}