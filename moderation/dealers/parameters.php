<?

$user_relations = Array(
    Array(
        'mod' => Array(
            'a.chilichihin',
        ),
        'spec' => Array(
            'S.Buchina',
        )
    ),
    Array(
        'mod' => Array(
            'S.Avdeev',
            'n.ovchinnikova',
        ),
        'spec' => Array(
            'D.Rudykin',
            'N.Ryabchikova',
        )
    ),
);

$pUser = CUser::GetByID($USER->GetID());
$p_user = $pUser->Fetch();

/**
 * @return массив: id специалистов, зависимых от текущего модератора
 */
function get_dependent_spec() {
   global $p_user;
   global $user_relations;
   $res = Array();

   $p_login = $p_user['LOGIN'];

   foreach($user_relations as $u_rel) {
       if(in_array($p_login,$u_rel['mod'])) {
           foreach($u_rel['spec'] as $spec) {
               $rsUser = CUser::GetByLogin($spec);
               $arUser = $rsUser->Fetch();
               $res[] = $arUser['ID'];
           }
           break;
       }
   }

   return $res;
}

/**
 * @return строка: список email модераторов через ','
 */
function get_mod_emails() {
    global $p_user;
    global $user_relations;
    $res = '';

    $p_login = $p_user['LOGIN'];

    foreach($user_relations as $u_rel) {
        if(in_array($p_login,$u_rel['spec'])) {
            foreach($u_rel['mod'] as $mod) {
                $rsUser = CUser::GetByLogin($mod);
                $arUser = $rsUser->Fetch();
                $res.= $arUser['EMAIL'].',';
            }
            if($res != '') $res = substr($res,0,-1);
            break;
        }
    }

    return $res;
}

/**
 * @param $id
 * @return строка: email пользователя
 */
function get_user_email($id) {
    $res = '';
    $rsUser = CUser::GetByID($id);
    $arUser = $rsUser->Fetch();
    $res = $arUser['EMAIL'];

    return $res;

}