<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub');?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->company->color;?>">
        <div>
            <?php echo $titleView;?> (<strong> <?php echo $nb_companies;?> </strong>)
            <?php if(!empty($itemSearch)):?>
                <div class="d-inline small" title="Filtre : <?php echo $itemSearch;?>"><?php echo fontawesome('filter-warning');?></div>
            <?php endif;?>
        </div>
        <div> <?php echo view('DataView\buttons/pagination');?> </div>
        <div> <?php echo view('DataView\buttons/search', ['color' => $themes->company->color,]);?> </div>
        <div class="d-flex align-items-center">
            <a role="button"
                title="<?php echo t("Exporter la liste", $namespace, ['withButton'=>false]);?>"
                class="btn btn-sm btn-dark" 
                href="<?php echo base_url('company/companies/export/csv');?>"
                onclick="waiting_start_export(this);"
                >
                <?php echo fontawesome('file-download');?>
            </a>
            <?php if($Autorisation->is_autorise('company_c')):?>
                <a role="button" 
                    class="btn btn-sm btn-<?php echo $themes->company->color;?> ms-1" 
                    href="<?php echo base_url('company/company/view');?>"
                    title="<?php echo t("Nouvelle entreprise", $namespace, ['withButton' => false]);?>"
                    onclick="waiting_start(this);"
                    >
                    <?php echo fontawesome('plus');?>
                    <?php echo $themes->company->icon;?>
                </a>
            <?php endif;?>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('script_foot_injected');?>
    <?php echo view('Company\js/js_company');?>
<?php $this->endSection();?>

<?php $this->section("body"); ?>

<div class="banData">

    <?php echo $this->include('Company\company-list-table');?>

</div>    

<?php $this->endSection(); ?>

