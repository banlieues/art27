<?php $validation = \Config\Services::validation(); ?>

<?php $this->extend('Layout\index'); ?>

<?php $this->section('script_foot_injected');?>
    <?php echo view('Mapping\js/js_googlemaps');?>
<?php $this->endSection();?>

<?php $this->section("body"); ?>

<!-- block form if mode update or create -->
<!-- block form if mode update or create -->



<!-- block error -->
<?php if(!empty($validation->getErrors())):?>
    <div class="alert alert-danger" role="alert"> <strong><i class="<?=icon("triangle_warning")?>"></i></strong> <?=count($validation->getErrors())?> erreur<?=plurial_s(count($validation->getErrors()));?> à corriger</div>
<?php endif;?>

<!-- block notification -->
<?php if(session()->getFlashdata("notification")):?>
    <div class="alert alert-<?php echo $themes->rdv->color;?> alert-dismissible fade show" role="alert">
        <strong><i class="<?=icon("confirmation_ok")?>"></i></strong> <?=session()->getFlashdata("notification")?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif;?>

<!-- block non find -->
<?php if($typeDataView!="create"&&empty($rdv)):?>
    <?php if($typeDataView=="associe"||$typeDataView=="new_form"):?>
<?php else:?>
    <div class="text-center mt-5">
        <h1><i class="<?=icon("triangle_warning")?>"></i> Pas de fiche associée à cet id</h1>
    </div>

    <?php $this->endSection(); ?>
<?php return;?>    
<?php endif;?>
<?php endif;?>

<?php echo $this->include('Rdv\view-rdv-title');?>



<!-- block creation&modification date -->
<?php if($typeDataView!="create"&&$typeDataView!="new_form"):?>
<?php if(isset($rdv->date_insert_log)||isset($rdv->date_modification_log)):?>
    <?php echo date_log($rdv->date_insert_log,$rdv->date_modification_log,$rdv->user_create,$rdv->user_modification);?>
<?php endif;?>
<?php endif;?>

<?php $this->renderSection('rdv-body');?>



<?php $this->endSection(); ?>

