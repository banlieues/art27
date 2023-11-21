<?php $this->extend('Layout\index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Mailing
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Mail\js/js_mail');?>
    <?php echo view('Mailing\js/js_mailing');?>
    <?php echo view('Components\js/summernote');?>
    <?php //echo view('Query\js/js_query');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="d-flex py-2 sticky_button bg-light justify-content-between align-items-center">
    <div class="h4">
        <?php echo $titleView;?>
    </div>
    <div id="submitButtons" class="form-nav text-center d-flex">
        <div class="me-2"> <?php echo $button_send;?> </div>
        <!-- <div> <?php //echo $button_save;?> </div>         -->
    </div>
</div>

<div class="row" id="mailMainContainer">
    <div class="col-sm-10 offset-1" id="mailFormContainer"> <?php echo view('\Mail\mail/form');?> </div>
</div>

<?php $this->endSection(); ?>