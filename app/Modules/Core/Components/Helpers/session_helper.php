<?php

function sessionUser($id_user=null)
{
    $id_user = !empty($id_user) ? $id_user : session('loggedUserId');

    $globals = new \Administrator\Config\Globals();
    $db = db_connect();
    $user = $db
        ->table($globals->t_user)
        ->select("$globals->t_user.*")
        ->select("$globals->t_l_user_role.label as role_label")
        ->join($globals->t_l_user_role, "$globals->t_l_user_role.id=$globals->t_user.role_id", "left")
        ->where("$globals->t_user.id", $id_user)
        ->get()->getRow();

    if(!empty($user) && !file_exists(AVATAR_PATH . $user->avatar)) $user->avatar = 'default.png';

    return $user;
}

// function sessionUserMail()
// {
//     return sessionUser()->email;
// }

// function sessionUserUsername()
// {   
//     return sessionUser()->username;
// }
