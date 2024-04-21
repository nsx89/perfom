<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

CModule::IncludeModule("iblock");

$method = htmlspecialcharsbx($_POST['method']);

switch ($method) {
	case 'timer_subs':
		$email = htmlspecialcharsbx(trim($_POST['email']));
		if (empty($email)) {
			echo 'Введите Email'; exit;
		}

		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			echo 'Введите корректный Email'; exit;
		}

		$IBLOCK_ID = 68;

		$res = CIBlockElement::GetList(Array(), Array('IBLOCK_ID' => $IBLOCK_ID, 'NAME' => $email));
	    if ($res->SelectedRowsCount() > 0) {
	    	echo 'Ваш Email уже был добавлен ранее'.$num; exit;
	    }

		$el = new CIBlockElement;
        $array = Array(
            "IBLOCK_ID"         => $IBLOCK_ID,
            "NAME"              => $email,
            "ACTIVE"         => "Y",
        );
        $el->Add($array);

        echo 'Спасибо! Ваш Email добавлен'; exit;

		break;
}

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");
?>

