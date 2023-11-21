<?php $this->extend('Layout\index'); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Liste\js/js_liste');?>
<?php $this->endSection(); ?>

<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->liste->color;?>">
        <div class="h5 mb-0">
            <?php echo $themes->liste->icon;?>
            <?php echo $titleView;?>
        </div>
        <div class="d-flex align-items-center">
            <?php if($Autorisation->is_autorise('liste_c')):?>
                <button class="btn btn-sm btn-<?php echo $themes->liste->color;?> ms-2"
                    onclick="row_new_modal('<?php echo $table;?>');"
                    title="Ajouter un élément à cette liste"
                    >
                    <?php echo fontawesome('plus');?>
                    Elément
                </button>
            <?php endif;?>
            <a role="button"
                class="btn btn-sm btn-<?php echo $themes->liste->color;?> ms-2"
                href="<?php echo base_url("liste");?>"
                title="Retour à la liste des listes"
                >
                <?php echo fontawesome('turn-up');?>
                <?php echo $themes->liste->icon;?>
            </a>
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<?php echo view('Liste\table-table', ['typeDataView' => 'update']);?>   

<?php $this->endSection(); ?>
