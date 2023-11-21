<?php $dataView=\Config\Services::dataViewConstructor();?>

<form class="form_ajax_modif" action="<?=base_url()?>/rdv/setStatut">
    <?php echo $dataView->getElementFormByIndexNoLabelAjax("statut_rdv_select","rdv",$rdv->id_statut_rdv);?>
    <input type="hidden" name="id_rdv" value="<?=$rdv->id_rdv?>">
</form>