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
<?php $this->endSection(); ?>

<!-- ------------------------------------------------- -->
<!-- ------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="container">
    <div class="text-center py-4">
        <h4> Welcome to the Mail Module </h4>
        <p> Please follow the instructions for each case below </p>
    </div>
    <div class="d-flex justify-content-center align-items-center mb-4">
        <a role="button" class="btn btn-secondary mx-2" href="<?php echo base_url('mail/example/view/new');?>"> 
            New mail
        </a>
        <a role="button" class="btn btn-secondary mx-2" href="<?php echo base_url('mail/example/view/reply');?>"> 
            Mail reply
        </a>
        <a role="button" class="btn btn-secondary mx-2" href="<?php echo base_url('mail/example/view/forward');?>"> 
            Mail forward
        </a>
        <a role="button" class="btn btn-secondary mx-2 h-auto" href="<?php echo base_url('mail/list');?>"> Mail list </a>
    </div>
    <div class="d-flex justify-content-center">
        <a role="button" class="btn btn-dark" href="<?php echo base_url('mail/documentation');?>"> Documentation </a>
    </div>
</div>

<?php $this->endSection(); ?>


