<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom">
        <div> <?php echo $titleView;?> </div>
    </div>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="h4 row text-center p-5">
    <div class="d-inline mb-2">
        <?php echo fontawesome('palette');?>
        <?php echo fontawesome('wrench');?>
        <?php echo fontawesome('hammer');?>
        <?php echo fontawesome('screwdriver');?>
        <?php echo fontawesome('pencil-alt');?>
        <?php echo fontawesome('pen-alt');?>
        <?php echo fontawesome('paint-brush');?>
        <?php echo fontawesome('paint-roller');?>
        <?php echo fontawesome('microscope');?>
        <?php echo fontawesome('syringe');?>
        <?php echo fontawesome('skull');?>
        <?php echo fontawesome('bomb');?>
    </div>
    <p> <b> Work in progress... </b> </p>
    <p> <?php echo t("La page \"$titleView\" est en cours de construction.", $namespace);?></p>
</div>

<?php $this->endSection(); ?>