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
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body'); ?>

<div class="row" data-masonry='{"percentPosition": true }'>
    <?php foreach($entities as $key=>$entity):?>
        <div class="col-4 mb-2">
            <div class="card">
                <div class="card-header card-success">
                    <b><?php echo $entity->label;?></b>
                </div>
                <div class="card-body">
                    <?php foreach($entity->tables as $table=>$title):?>
                        <div class="d-flex align-items-center">
                            <div class="me-2"> <?php echo $title;?> </div>
                            <div class="flex-grow-1"> <hr> </div>
                            <div class="d-flex">
                                <button type="button"
                                    class="btn"
                                    onclick="table_info_modal('<?php echo $table;?>');"
                                    title="Voir la liste <?php echo $title;?>"                          
                                    >
                                    <?php echo fontawesome('eye');?>
                                </button>
                                <?php if($Autorisation->is_autorise('liste_u')):?>
                                    <a class="btn"
                                        href="<?php echo base_url('liste/table/' . $table);?>"
                                        title="Editer la liste"
                                        >
                                        <?php echo fontawesome('edit');?>
                                    </a>
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    <?php endforeach;?>
</div>
<?php $this->endSection(); ?>
