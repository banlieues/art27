<?php $this->extend('Layout\tamo/index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    <?php if(in_array($level, ['schema', 'template'])):?>
        Nouveau <?php echo $level_label;?>
    <?php elseif(in_array($level, ['publication'])):?>
        Nouvelle <?php echo $level_label;?>
    <?php endif;?>    
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub_title'); ?>
    <?php if(in_array($level, ['schema', 'template'])):?>
        Nouveau <?php echo $level_label;?>
    <?php elseif(in_array($level, ['publication'])):?>
        Nouvelle <?php echo $level_label;?>
    <?php endif;?>    
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Report\js/js_report');?>
    <?php echo view('Tesorus\js/js_tesorus');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<form id="reportNewForm" class="needs-validation py-2" action="<?php echo base_url('report/' . $level . '/new');?>" method="post" enctype="multipart/form-data" novalidate>
    <div class="row">
        <div class="col-10">
            
            <?php echo view('Report\report/form.php');?>
            
        </div>
        <div class="col-2">
            <button class="btn btn-sm btn-info w-100 mb-1"> 
                <?php if(in_array($level, ['schema', 'template'])):?>
                    Enregistrer le nouveau <?php echo $level_label;?>
                <?php elseif(in_array($level, ['publication'])):?>
                    Enregistrer la nouvelle <?php echo $level_label;?>
                <?php endif;?>     
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary w-100" onclick="window.location.reload();"> 
                <?php echo t("Annuler les changements", $namespace);?> 
            </button>
        </div>
    </div>
</form>

<?php $this->endSection(); ?>


