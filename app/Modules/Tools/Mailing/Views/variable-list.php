<?php $this->extend('Layout\index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Liste des variables
<?php $this->endSection(); ?>

<!-- NAVBARSUB NAVIGATION -->
<?php $this->section('navbarsub_navigation'); ?>
    <?php //echo $button_return . $button_variable_new;?>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php //echo view('Mailing\js/js_bootstrap');?>
    <?php echo view('Mailing\js/js_mailing');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

    <?php echo view('Mailing\variable/table');?>

<?php $this->endSection(); ?>

