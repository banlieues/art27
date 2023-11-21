<?php $validation = \Config\Services::validation(); ?>

<?php $this->extend('Layout\index'); ?>

<?php $this->section('script_foot_injected');?>
    <?php echo view('Demande\js_form'); ?>
    <?php echo view("Demande\js_demande", ["id_email_primary"=>$id_email_primary,"id_demande"=>$id_demande]);?>
    <?php echo view('Mapping\js/js_googlemaps');?>
<?php $this->endSection();?>

<?php $this->section('navbarsub');?>
    <?php echo $this->include('Demande\view-demande-title');?>
<?php $this->endSection();?>

<?php $this->section("body");?>

<!-- block creation & modification date -->
<?php if($typeDataView!="create" &&$typeDataView!="new_form"):?>
    <?php echo date_log($demande->date_insert_log, $demande->date_modification_log, $demande->user_create, $demande->user_modification);?>
<?php endif;?>

<!-- block form if mode update or create -->
<?php if($id_message_attach>0):?>
    <div class="card mb-2">
        <div class="card-header border-top-amethyst">
            <h5>Voulez-vous ajouter le message <?=$id_message_attach?> au fil de disscussion de la demande n°<?=$id_demande?>?</h5>
            <a href="<?=base_url()?>outlook/message_view/<?=$id_message_attach?>"
                id_message="<?php echo $id_message_attach;?>"
                class="view_message btn btn-warning message_lire get_content_modal_p"
                >
                <i class="<?=icon("message_view")?>"></i>
                Lire message
            </a>
            <a href="<?=base_url()?>demande/attach_message/<?=$id_demande?>/<?=$id_message_attach?>"
                id_message="<?php echo $id_message_attach;?>"
                class="btn btn-success"
                >
                <i class="<?=icon("link_relation")?>"></i>
                Ajouter le message
            </a>
            <a href="<?=base_url()?>demande/new/outlook/<?=$id_message_attach?>"
                id_message="<?php echo $id_message_attach;?>"
                class="btn btn-danger"
                >
                <i class="<?=icon("cancel")?>"></i>
                Annuler
            </a>
        </div>
    </div>
<?php endif;?>

<!-- block error -->
<?php if(!empty($validation->getErrors())):?>
    <div class="alert alert-danger" role="alert">
        <strong><i class="<?=icon("triangle_warning")?>"></i></strong>
        <?=count($validation->getErrors())?> erreur<?=plurial_s(count($validation->getErrors()));?> à corriger
    </div>
<?php endif;?>

<!-- block notification -->
<?php if(session()->getFlashdata("notification")):?>
    <div class="alert alert-<?php echo $themes->demande->color;?> alert-dismissible fade show" role="alert">
        <strong><i class="<?=icon("confirmation_ok")?>"></i></strong> <?=session()->getFlashdata("notification")?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif;?>

<!-- block non find -->
<?php if($typeDataView!="create" && empty($demande)):?>
    <div class="text-center mt-5">
        <h1><i class="<?=icon("triangle_warning")?>"></i> Pas de fiche associée à cet id</h1>
    </div>
<?php else:?>
    <?php $this->renderSection('demande-body');?>
<?php endif;?>

<?php $this->endSection(); ?>

