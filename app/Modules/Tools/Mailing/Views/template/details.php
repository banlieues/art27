<?php $this->extend('Layout\index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Modèle d'emails - Détails
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php //echo view('Mailing\js/js_bootstrap');?>
    <?php echo view('Components\js/summernote');?>
    <?php echo view('Mailing\js/js_mailing');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<form id="templateUpdateForm" method="post" action="<?php echo base_url('mailing/template/view/' . $id_template);?>">
    <?php echo view('Mailing\template\form');?>
</form>

<?php $this->endSection(); ?>
