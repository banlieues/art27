<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->company->color;?>">
        <div>
            <?php echo $titleView;?>
            (<strong> <?php echo $nb_deposits;?> </strong>)
            <?php if(!empty($itemSearch)):?>
                <div class="d-inline small" title="Filtre : <?php echo $itemSearch;?>">
                    <?php echo fontawesome('filter-warning');?>
                </div>
            <?php endif;?>
        </div>
        <div>
            <?php echo view('DataView\buttons/pagination');?>
        </div>
        <div> 
            <?php echo view('DataView\buttons/search', ['color' => $themes->deposit->color,]);?>
        </div>
        <div class="d-flex">
            <?php echo view('DataView\buttons/export_csv', [
                'module' => 'Company',
                'model' => 'DepositModel',
                'method' => 'DepositsGet',
                'filename' => 'liste_depot_entreprises',
            ]);?>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section('script_foot_injected');?>
    <?php echo view('Company\js/js_company');?>
<?php $this->endSection();?>

<?php $this->section("body"); ?>

<div class="banData">

    <?php echo $this->include('Company\deposit-list-table');?>

</div>    

<?php $this->endSection(); ?>

