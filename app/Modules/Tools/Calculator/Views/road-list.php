<?php $this->extend('\Layout\index'); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->calculator->color;?> border-2">
        <div> <?php echo $themes->calculator->icon;?> <?php echo $titleView;?> </div>
        <button class="btn btn-sm btn-<?php echo $themes->calculator->color;?>"
            onclick="road_list_collapse(this, 'roadListCollapse');"
            >
            <?php echo fontawesome('up-right-and-down-left-from-center');?>
            Etirer la liste
        </button>
        <div class="d-flex align-items-center">
            <!-- <a role="button"
                class="btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                href="<?php echo base_url('calculator/roads/export');?>"
                title="Exporter la liste des postes en csv"
                onclick="waiting_start_export(this);"
                >
                <?php echo fontawesome('file-csv');?>
                Postes
            </a>
            <a role="button"
                class="btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                href="<?php echo base_url('calculator/prices/export');?>"
                onclick="waiting_start_export(this);"
                title="Exporter la liste des prix en csv"
                >
                <?php echo fontawesome('file-csv');?>
                PU
            </a> -->
            <a role="button"
                class="btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                href="<?php echo base_url('calculator/export/sql');?>"
                title="Exporter les données du module en sql"
                onclick="waiting_start_export(this);"
                >
                <?php echo fontawesome('file-download');?>
                <sub class="fw-bold"> SQL </sub>
            </a>
            <!-- <a role="button" class="btn btn-sm btn-dark" 
                href="<?php //echo base_url("calculator/tesorus");?>"
                >
                <?php //echo $themes->tesorus->icon;?> Thesaurus
            </a> -->
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Tesorus\js/js_tesorus');?>
    <?php echo view('Calculator\js/js_calculator');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body');?>

    <?php echo $view;?>
    
<?php $this->endSection()?>
