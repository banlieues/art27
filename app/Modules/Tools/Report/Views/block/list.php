<?php $this->extend('Layout\tamo/index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Détails du bloc <?php echo $block->label;?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub_title'); ?>
    Détails du bloc <?php echo $block->label . $history;?>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Report\js/js_report');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<?php echo view('Report\block/table');?>

<?php $this->endSection(); ?>
