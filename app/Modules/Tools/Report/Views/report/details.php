<?php $this->extend('Layout\tamo/index'); ?>

<!-- TITLE -->
<?php $this->section('title'); ?>
    <?php echo t("Détails du $report->level_label", $namespace);?>
<?php $this->endSection(); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub_title'); ?>
    <?php echo t("Détails du $report->level_label", $namespace) . ' : ' . $report->label . $history;?>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Report\js/js_report');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<form action="<?php echo base_url('report/view/' . $report->id_report);?>" id="reportDetailsForm" method="post" class="updateForm needs-validation" novalidate>
    <div class="my-4 row">    
        <div class="col-sm-10">

            <?php echo view('Report\report/form');?>
            
        </div>
        <div class="col-sm-2">
            <div class="form-nav text-center">    
                <button type="button" class="btn btn-sm btn-outline-secondary mb-1 w-100" onclick="window.location.reload()"> 
                    Annuler les changements 
                </button>

                <button class="btn btn-sm btn-success mb-1 w-100"> 
                    Enregistrer les modifications 
                </button>

                <?php if(in_array($report->level, ['schema', 'template'])):?>
                    <button type="button" class="btn btn-sm btn-warning mb-1 w-100" 
                        onclick="report_duplicate_modal(this, <?php echo $report->id_report;?>);"
                        > 
                        <div> Dupliquer le <?php echo $level_label;?> </div>
                        <div style="line-height: 1 ;"> <small style="line-height: 1;"> Enregistrez d'abord les modifications </small> </div>
                    </button>
                <?php endif;?>

                <a role="button" class="btn btn-sm btn-primary mb-1 w-100" 
                    href="<?php echo base_url('report/download/' . $id_report);?>"
                    > 
                    Télécharger le <?php echo $report->level_label;?> en Word <br>
                    <div style="line-height: 1 ;"> <small> Enregistrez d'abord les modifications </small> </div>
                </a>

                <?php if($report->level == 'publication' && !empty($report->id_person) && !empty($report->id_request)):?>
                    <a role="button" class="btn btn-sm btn-primary mb-1 w-100" 
                        href="<?php echo base_url('report/download/' . $id_report . '/pdf');?>"
                        > 
                        Export <b>PDF</b> <br>
                        <div style="line-height: 1 ;"> <small> Enregistrez d'abord les modifications </small> </div>
                    </a>
                <?php endif;?>
                
                <button type="button" class="btn btn-sm btn-danger mb-1 w-100" onclick="report_delete_modal(this, <?php echo $report->id_report;?>)"> 
                    Supprimer le <?php echo $report->level_label;?>
                </button>
                
            </div>
        </div>
    </div>
</form>

<?php $this->endSection(); ?>






