<?php $validation = \Config\Services::validation(); ?>

<?php $this->extend('Layout\index'); ?>

<?php $this->section('script_foot_injected');?>
<?php $this->endSection();?>

<?php $this->section("body"); ?>

<!-- block form if mode update or create -->
<!-- block form if mode update or create -->

<?php if($typeDataView=="modelisation"):?>
    <form id="form-entity" class="form_gestion_entity" method="post" autocomplete="off"  action="<?=base_url("partenaire_culturel/save_modelisation")?>">
<?php endif;?>

<!-- block error -->
<?php if(!empty($validation->getErrors())):?>
    <div class="alert alert-danger" role="alert"> <strong><i class="<?=icon("triangle_warning")?>"></i></strong> <?=count($validation->getErrors())?> erreur<?=plurial_s(count($validation->getErrors()));?> à corriger</div>
<?php endif;?>

<!-- block notification -->
<?php if(session()->getFlashdata("notification")):?>
    <div class="alert alert-<?php echo $themes->partenaire_culturel->color;?> alert-dismissible fade show" role="alert">
        <strong><i class="<?=icon("confirmation_ok")?>"></i></strong> <?=session()->getFlashdata("notification")?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif;?>

<!-- block non find -->
<?php if($typeDataView!="create"&&empty($partenaire_culturel)&&$typeDataView!="modelisation"):?>
    <?php if($typeDataView=="associe"||$typeDataView=="new_form"):?>
<?php else:?>
    <div class="text-center mt-5">
        <h1><i class="<?=icon("triangle_warning")?>"></i> Pas de fiche associée à cet id</h1>
    </div>

    <?php $this->endSection(); ?>
<?php return;?>    
<?php endif;?>
<?php endif;?>

<?php echo $this->include('Partenaire_culturel\view-partenaire_culturel-title');?>

    <?php echo $this->include('Partenaire_culturel\view-partenaire_culturel-tab');?>


<!-- block creation&modification date -->
<?php if($typeDataView!="create"&&$typeDataView!="modelisation"&&$typeDataView!="new_form"):?>
<?php if(isset($partenaire_culturel->date_insert_log)||isset($partenaire_culturel->date_modification_log)):?>
    <?php echo date_log($partenaire_culturel->date_insert_log,$partenaire_culturel->date_modification_log,$partenaire_culturel->user_create,$partenaire_culturel->user_modification);?>
<?php endif;?>
<?php endif;?>

<?php $this->renderSection('partenaire_culturel-body');?>

<?php if($typeDataView=="modelisation"):?>     
</form>
<?php endif;?>

<?php $this->endSection(); ?>

