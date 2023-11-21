<?php $this->extend('Layout\index');?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Enquêtes - <?php echo $target->title;?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom">
        <div> Enquêtes - <?php echo $target->title;?> </div>
        <div class="d-flex"> <?php echo view('Enquete\navigation');?> </div>
    </div>
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <script src="<?php echo base_url('node_modules/jspdf/dist/jspdf.umd.min.js'); ?>"></script>
    <?php echo view('Enquete\js/js_trend');?>
    <?php echo view('Enquete\js/js_enquete');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<?php echo view('Enquete\filter-alert');?>
<?php echo view('Enquete\trend-select');?>

<?php $this->endSection(); ?>



