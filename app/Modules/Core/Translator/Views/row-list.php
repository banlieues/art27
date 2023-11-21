<?php $this->extend('Layout\index');?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->translator->color;?>">
        <div>
            <?php echo $titleView;?>
            (<strong> <?php echo $nb_rows;?> </strong>)
            <?php if(!empty($itemSearch) || !empty($isEmpty)):?>
                <div class="d-inline small"
                    title="Filtre : <?php if(!empty($itemSearch)) echo $itemSearch;?> <?php if(!empty($isEmpty)):?>[Non traduit]<?php endif;?>"
                    >
                    <?php echo fontawesome('filter-warning');?>
                </div>
            <?php endif;?>
        </div>
        <?php if($nb_rows>20):?>
            <div class="col-auto">
                <?php echo view('DataView\buttons/pagination');?>
            </div>
        <?php endif;?>
        <div class="col-auto"> 
            <input type="checkbox" 
                class="form-check-input" 
                name="isEmpty" 
                form="searchOrderForm"
                <?php if(!empty($isEmpty)):?> checked <?php endif;?>
            />
            <label class="form-check-label"> <?php echo t("Non traduit", $namespace);?> </label>
        </div>
        <div class="col-auto"> 
            <?php echo view('DataView\buttons/search', ['color' => $themes->translator->color,]);?>
        </div>
        <div class="col-auto">
            <div class="d-flex">
                <?php echo view('DataView\buttons/export_csv', [
                    'module' => 'Translator',
                    'model' => 'TranslatorModel',
                    'method' => 'RowsGet',
                    'filename' => 'liste_traduction',
                ]);?>
            </div>
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- INJECTED SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Translator\js/translator-rows');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="banData">
    <?php if(empty($nb_rows)):?>
        <div class="text-center m-5">
            <h3>Pas de ligne de traduction avec ces critères.</h3>
        </div>  
    <?php else:?>
        <div id="translation-list" class="table-responsive table-fullview"
            > 
            <table class="table table-bordered table-striped table-hover my-0 table-sm w-100">
                <thead class="table-light sticky-top">
                    <tr>
                        <?php echo $getTh;?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rows as $row):?>
                        <tr id_transl="<?php echo $row->id_transl;?>">
                            <?php echo view('Translator\row-tr', [ 'row' => $row, ]);?>
                        </tr>
                    <?php endforeach;?>
                </tbody>   
            </table>
        </div>      
    <?php endif;?>

</div>    
<?php $this->endSection(); ?>