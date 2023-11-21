<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom">
        <div> <?php echo $titleView;?> </div>
    </div>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Mail\js/js_mail');?>
    <?php echo view('Mail\js/js_example');?>
    <?php echo view('Components\js/summernote');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

    <div class="row">
        <div class="col-10">
            <?php echo view('Mail\mail/form');?>
        </div>
        <div id="submitButtons" class="col-2 form-nav d-flex flex-column">
            <?php echo $button_send;?>
            <?php echo $button_save;?>
        </div>
    </div>

<?php $this->endSection(); ?>
