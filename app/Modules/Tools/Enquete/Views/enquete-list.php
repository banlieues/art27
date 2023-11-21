<?php $this->extend('Layout\index');?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->enquete->color;?>">
        <div>
            <?php echo $titleView;?>
            (<strong> <?php echo $nb_enquetes;?> </strong>)
            <?php if(!empty($itemSearch)):?>
                <div class="d-inline small" title="Filtre : <?php echo $itemSearch;?>">
                    <?php echo fontawesome('filter-warning');?>
                </div>
            <?php endif;?>
        </div>
        <div> <?php echo view('DataView\buttons/pagination');?> </div>
        <div> <?php echo view('DataView\buttons/search', ['color' => $themes->enquete->color,]);?> </div>
        <div class="d-flex">
            <?php echo view('DataView\buttons/export_csv', [
                'module' => 'Enquete',
                'model' => 'EnqueteModel',
                'method' => 'EnquetesGet',
                'filename' => 'liste_formulaires',
            ]);?>
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Enquete\js/js_enquete');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="banData">

    <?php echo view('Enquete\enquete-list-table');?>

</div>    
<?php $this->endSection(); ?>
