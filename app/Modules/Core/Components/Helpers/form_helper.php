<?php
// Form_Helper

function display_error($validation, $field)
{
    if ($validation->hasError($field))
    {
        return $validation->getError($field);
    }

    else
    {
        return false;
    }
}

if (!function_exists('get_info_view'))
{
    function get_info_view($updated_at, $updated_by, $created_at, $created_by, $implode=' - ')
    {
        $db = db_connect();

        $updated_datetime = !empty($updated_at) ? convert_date_en_to_fr_with_h($updated_at, true, false) : 'x';

        $updated_fullname = 'x';
        if(!empty($updated_by)) :
            $updated_user = $db->table('user_accounts')->where('id', $updated_by)->get()->getRow();
            if(!empty($updated_user->prenom) || !empty($updated_user->prenom)) :
                $updated_fullname = fullname($updated_user->prenom, $updated_user->nom);
            endif;
        endif;

        $created_datetime = !empty($created_at) ? convert_date_en_to_fr_with_h($created_at, true, false) : 'x';

        $created_fullname = 'x';
        if(!empty($created_by)) :
            $created_user = $db->table('user_accounts')->where('id', $created_by)->get()->getRow();
            if(!empty($created_user->prenom) || !empty($created_user->prenom)) :
                $created_fullname = fullname($created_user->prenom, $created_user->nom);
            endif;
        endif;

        $html_created = 'Créé le <b> ' . $created_datetime . ' </b> par <b> ' . $created_fullname . ' </b>';
        if($updated_datetime==$created_datetime && $updated_fullname==$updated_fullname) :
            $html_updated = 'Màj le <b> ' . $updated_datetime . ' </b> par <b>' . $updated_fullname . ' </b>' . $implode;
        else :
            $html_updated = '';
        endif;

        $html = '<span class="fst-italic small"> ' . $html_updated . $html_created . ' </span>';

        return $html;
    }
}
