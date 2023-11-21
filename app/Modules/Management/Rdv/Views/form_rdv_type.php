<?php $dataView=\Config\Services::dataViewConstructor();?>

<form class="form_ajax_modif" action="<?=base_url()?>/rdv/setType">

    <?php echo $dataView->getElementFormByIndexNoLabelAjax("type_rdv_select","rdv",$rdv->id_type_rdv);?>

    <input type="hidden" name="id_rdv" value="<?=$rdv->id_rdv?>">
</form>