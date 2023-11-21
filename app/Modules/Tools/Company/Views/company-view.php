<?php $this->extend('\Layout\index'); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="d-flex justify-content-between align-items-center p-2 border-bottom-<?php echo $themes->company->color;?>">
        <div> <?php echo $titleView;?> </div>
        <div class="d-flex">
            <?php if($Autorisation->is_autorise('company_u')):?>
                <a role="button"
                    class="btn btn-sm btn-outline-secondary ms-2"
                    href="<?php echo current_url();?>"
                    title="Annuler les modifications"
                    onclick="waiting_start(this);"
                    >
                    <?php echo fontawesome('undo');?>
                </a>
                <button class="btn btn-sm btn-<?php echo $themes->company->color;?> ms-2"
                    form="CompanyViewForm"
                    title="Enregistrer les modifications"
                    onclick="waiting_start(this);"
                    >
                    <?php echo fontawesome('save');?>
                </button>
            <?php endif;?>
            <a role="button"
                class="btn btn-sm btn-<?php echo $themes->company->color;?> ms-2"
                href="<?php echo base_url('company/companies');?>"
                onclick="waiting_start(this);"
                title="Retour à la liste des entreprises"
                >
                <?php echo fontawesome('turn-up');?>
                <?php echo $themes->company->icon;?>
            </a>
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Company\js/js_company');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<form id="<?php echo $formId;?>" class="updateForm needs-validation ajax_submit" 
    method="post" enctype="multipart/form-data"
    <?php if(!empty($company->id_company)):?>
        action="<?php echo base_url('company/company/view/' . $company->id_company);?>"
    <?php else:?>
        action="<?php echo base_url('company/company/view');?>"
    <?php endif;?> 
    novalidate
    >
    <?php echo view('Company\company-view-form.php');?>
</form>

<?php $this->endSection(); ?>

