






<span class="text-right container_lu_lu">
        <?php $date_limite=strtotime("2021-12-06");

        $is_lu=is_lu_message($email->lus,session("loggedUserId"));
        $date_mail=strtotime($email->created_datetime);
        //echo $date_limite; die($date_mail);
        if($date_mail>=$date_limite)
        {
        ?>
            <?php if($is_lu):?>
            <span class="cestlu">
                <input class="btn_changement_lu" id_message=<?=$email->id_primary?>  statut="0" data-size="xs" type="checkbox" checked data-toggle="toggle" data-onlabel="Lu" data-offlabel="Non lu" data-onstyle="success" data-offstyle="danger"> <small>Lu</small>

            </span>
            <?php else:?>
                <span class="cestpaslu">
                <input class="btn_changement_lu" id_message=<?=$email->id_primary?>  statut="1" data-size="xs" type="checkbox" data-toggle="toggle" data-onlabel="Lu" data-offlabel="Non lu" data-onstyle="success" data-offstyle="danger"> <small>Non Lu</small>

            </span>

            <?php endif;?>

        <?php } ?>

        <span style="display:none" class="changement_statut_lu text-center"><i class="fa fa-circle-notch fa-spin"></i></span>
</span>


