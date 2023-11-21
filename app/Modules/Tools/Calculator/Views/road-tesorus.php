<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->calculator->color;?> border-2">
        <div> <?php echo $themes->calculator->icon;?> <?php echo $titleView;?> </div>
        <div class="d-flex align-items-center">
            <div class="px-2"> <small> Aperçus </small> </div>
            <button type="button" class="btn py-0" 
                title="Liste"
                onclick="road_view_modal(this, 'calculator', 'list', 'xl');"
                > 
                <?php echo fontawesome('list-ol');?>
            </button>
            <button type="button" class="btn py-0" 
                title="Liste à cocher unique"
                onclick="road_view_modal(this, 'calculator', 'radio', 'xl');"
                > 
                <?php echo fontawesome('check-circle');?>
            </button>
            <button type="button" class="btn py-0" 
                title="Liste à choix multiple"
                onclick="road_view_modal(this, 'calculator', 'checkbox', 'xl');"
                > 
                <?php echo fontawesome('check-square');?>
            </button>
            <button type="button" class="btn py-0" 
                title="Sélection par tags"
                onclick="road_view_modal(this, 'calculator', 'tag', 'xl');"
                > 
                <?php echo fontawesome('tags');?>
            </button>
            <!-- <a role="button" class="btn btn-sm btn-dark" 
                href="<?php //echo base_url("calculator/roads");?>"
                >
                <?php //echo fontawesome('tag');?> Liste des postes
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
<?php $this->section('body'); ?>

    <?php echo $table;?>

<?php $this->endSection(); ?>