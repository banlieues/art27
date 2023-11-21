<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->tesorus->color;?>">
        <div>
            <?php echo $titleView;?>
            (<strong> <?php echo $nb_cells;?> </strong>)
            <?php if(!empty($itemSearch)):?>
                <div class="d-inline small" title="Filtre : <?php echo $itemSearch;?>">
                    <?php echo fontawesome('filter-warning');?>
                </div>
            <?php endif;?>
        </div>
        <div> <?php echo view('DataView\buttons/pagination');?> </div>
        <div> <?php echo view('DataView\buttons/search', ['color' => $themes->tesorus->color,]);?> </div>
        <div class="d-flex">
            <?php echo view('DataView\buttons/export_csv', [
                'module' => 'Tesorus',
                'model' => 'CellModel',
                'method' => 'CellsGet',
                'filename' => 'liste_cellules',
            ]);?>
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected');?>
<?php $this->endSection();?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section("body"); ?>

<div class="banData">

    <?php echo $this->include('Tesorus\cell-list-table');?>

</div>    

<?php $this->endSection(); ?>