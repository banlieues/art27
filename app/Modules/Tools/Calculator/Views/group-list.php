<?php $this->extend('\Layout\index'); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->calculator->color;?> border-2">
        <div> <?php echo $themes->calculator->icon;?> <?php echo $titleView;?> </div>
        <button class="btn btn-sm btn-<?php echo $themes->calculator->color;?>"
            onclick="road_list_collapse(this, 'groupListCollapse');"
            >
            <?php echo fontawesome('up-right-and-down-left-from-center');?>
            Etirer la liste
        </button>
        <div class="d-flex align-items-center">
            <div class="px-2"> <small> Aperçus </small> </div>
            <button type="button" class="btn py-0" 
                title="Tags"
                onclick="group_tag_view_modal(this);"
                > 
                <?php echo fontawesome('tags');?>
            </button>
            <!-- <a role="button"
                class="btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                href="<?php echo base_url('calculator/groups/export/csv');?>"
                title="Exporter la liste des groupes en csv"
                >
                <?php echo fontawesome('file-download');?>
                <sub class="fw-bold"> CSV </sub>
            </a> -->
            <a role="button"
                class="btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                href="<?php echo base_url('calculator/export/sql');?>"
                title="Exporter les données du module en sql"
                >
                <?php echo fontawesome('file-download');?>
                <sub class="fw-bold"> SQL </sub>
            </a>
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Calculator\js/js_calculator');?>
    <?php echo view('Tesorus\js/js_tesorus');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body');?>

<?php echo $view;?>
    
<?php $this->endSection()?>
