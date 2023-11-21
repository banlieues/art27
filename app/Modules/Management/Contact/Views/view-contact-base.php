<?php $validation = \Config\Services::validation(); ?>

<?php $this->extend('Layout\index'); ?>

<?php $this->section('script_foot_injected');?>
    <?php echo view('Demande\js_form');?>
    <?php //echo view("Demande\js_demande");?>
<?php $this->endSection();?>

<?php $this->section("navbarsub"); ?>
    <?php echo $this->include('Contact\view-contact-title');?>
<?php $this->endSection();?>

<?php $this->section("body"); ?>

<!-- block creation & modification date -->
<?php if($typeDataView!="create" && $typeDataView!="new_form"):?>
    <?php echo date_log($contact->date_insert_log,$contact->date_modification_log,$contact->user_create,$contact->user_modification);?>
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
    <div class="alert alert-<?php echo $themes->contact->color;?> alert-dismissible fade show" role="alert">
        <strong><i class="<?=icon("confirmation_ok")?>"></i></strong> <?=session()->getFlashdata("notification")?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif;?>

<!-- block non find -->
<?php if(!in_array($typeDataView, ["create", "associe", "new_form"]) && empty($contact)):?>
    <div class="text-center mt-5">
        <h1><i class="<?=icon("triangle_warning")?>"></i> Pas de fiche associée à cet id</h1>
    </div>
<?php else:?>
    <?php $this->renderSection('contact-body');?>
<?php endif;?>

<?php $this->endSection(); ?>

