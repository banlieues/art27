
<?php $this->extend('Layout\tamo/index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    Nouveau bloc
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub_title'); ?>
    Nouveau bloc
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Report\js/js_report');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<form id="blockNewForm" class="needs-validation py-2" action="<?php echo base_url('report/block_new');?>" method="post" enctype="multipart/form-data" novalidate>
    <div class="row">
        <div class="col-10">
            
            <?php echo view('Report\block/form.php');?>
            
        </div>
        <div class="col-2">
            <div class="form-nav text-center">    
                <div class="mb-3">
                    <button class="btn btn-sm btn-info w-100 mb-1"> 
                        Enregistrer le nouveau bloc 
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary w-100" 
                        onclick="window.location.reload();"
                        > 
                        <?php echo t("Annuler les changements", __NAMESPACE__);?> 
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>

<?php $this->endSection(); ?>


