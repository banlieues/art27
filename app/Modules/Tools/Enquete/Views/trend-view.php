<?php $this->extend('Layout\index');?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Enquêtes <?php echo !empty($target->title) ? ' - ' . $target->title : '';?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom">
        <div> Enquêtes <?php echo !empty($target->title) ? ' - ' . $target->title : '';?> </div>
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

<?php if($reference):?>
    <script>
        let canvas = new Array();
    </script>
    <div class="trend-container" id="container-<?php echo $reference;?>"
        >
        <div class="waiting text-center w-100">
            <?php echo fontawesome('spinner');?> <br>
            Calcul en cours...
        </div>
        <canvas class="canvas" 
            id="trend-<?php echo $reference;?>" 
            reference=<?php echo $reference;?> 
            style="max-height: 500px; display:none;"
            >
        </canvas>                         
    </div>
<?php else:?>
    <div class="alert alert-warning">
        Veuillez selectionner une courbe de tendance.
    </div>
<?php endif;?>

<?php $this->endSection(); ?>



