<?php $validation = \Config\Services::validation(); ?>

<?php $this->extend('Layout\index'); ?>

<?php $this->section("body"); ?>

<?php if(in_array($typeDataView,["create","update"])&&$tab=="fiche"): ?>
    <form method="post" action="<?=base_url()?>/partenaire_social/save">
<?php endif;?>

<?php if(in_array($typeDataView,["create","update"])&&$tab=="convention"): ?>
    <form method="post" action="<?=base_url()?>partenaire_social/save_convention">
<?php endif;?>

    <!-- block form if mode update or create -->
    <!-- block form if mode update or create -->


    <!-- block error -->
    <?php if(!empty($validation->getErrors())):?>
        <div class="alert alert-danger" role="alert"> <strong><i class="<?=icon("triangle_warning")?>"></i></strong> <?=count($validation->getErrors())?> erreur<?=plurial_s(count($validation->getErrors()));?> à corriger</div>
    <?php endif;?>

    <!-- block notification -->
    <?php if(session()->getFlashdata("notification")):?>
        <div class="alert alert-<?php echo $themes->partenaire_social->color;?> alert-dismissible fade show" role="alert">
            <strong><i class="<?=icon("confirmation_ok")?>"></i></strong> <?=session()->getFlashdata("notification")?>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;?>

    <!-- block non find -->
    <?php if($typeDataView!="create"&&empty($partenaire_social)&&$typeDataView!="modelisation"):?>
        <?php if($typeDataView=="associe"||$typeDataView=="new_form"):?>
    <?php else:?>
        <div class="text-center mt-5">
            <h1><i class="<?=icon("triangle_warning")?>"></i> Pas de fiche associée à cet id</h1>
        </div>

        <?php $this->endSection(); ?>
    <?php return;?>    
    <?php endif;?>
    <?php endif;?>

    <?php echo $this->include('Partenaire_social\view-partenaire_social-title');?>

        <?php echo $this->include('Partenaire_social\view-partenaire_social-tab');?>

    <!-- block creation&modification date -->
    <?php if($typeDataView!="create"):?>
    <?php if(isset($partenaire_social->date_insert_log)||isset($partenaire_social->date_modification_log)):?>
        <?php echo date_log($partenaire_social->date_insert_log,$partenaire_social->date_modification_log,$partenaire_social->user_create,$partenaire_social->user_modification);?>
    <?php endif;?>
    <?php endif;?>

    <?php $this->renderSection('partenaire_social-body');?>


<?php if(in_array($typeDataView,["create","update"])): ?>
</form>
<?php endif;?>

<?php $this->endSection(); ?>

<?php $this->section("script_foot_injected")?>

    <?php echo view($viewpath.'js_partenaire_social');?>

<?php $this->endSection(); ?>


