<?php $this->extend('\Layout\index'); ?>

<?php if(!empty($validation) && !empty($validation->listErrors())):?>
    <div id="formErrorValues" class="alert alert-warning my-4"> 
        <?php echo $validation->listErrors();?>
    </div>
<?php endif;?>

<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->calculator->color;?> border-2">
        <div> <?php echo $themes->calculator->icon;?> <?php echo $titleView;?> </div>
        <div class="d-flex">
            <?php if(!empty($devis)):?>
                <button type="button"
                    class="<?php if($typeDataView=='read'):?> form_update <?php endif;?> btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                    onclick="work_edit_modal(this);"
                    <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
                    >
                    <?php echo fontawesome('plus');?>
                    Ouvrage
                </button>
                <button type="button" class="form_read btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                    onclick="js_form_update(this);"
                    <?php if($typeDataView=='create'):?> style="display: none;" <?php endif;?>
                    >
                    <?php echo fontawesome('edit');?>
                </button>
                <a role="button" class="<?php if($typeDataView=='read'):?> form_update <?php endif;?> btn btn-sm btn-outline-secondary ms-2"
                    <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
                    title="Annuler les modifications sur le poste"
                    href="<?php echo current_url();?>"
                    onclick="waiting_start(this);"
                    >
                    <?php echo fontawesome('undo');?> 
                </a>
                <form id="DevisForm" method="post">
                    <button type="submit"
                        class="<?php if($typeDataView=='read'):?> form_update <?php endif;?> btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
                        form="DevisForm"
                        onclick="waiting_start(this);"
                        >
                        <?php echo fontawesome('save');?> 
                    </button>
                </form>
                <?php if(empty($devis->is_complete)):?>
                    <!-- <a role="button" 
                        class="form_read btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2" 
                        href="<?php echo base_url("calculator/devis/$devis->id_demande/mesurage");?>"
                        onclick="waiting_start_export(this);"
                        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                        target="_blank"
                        title="Enregistrer le document de mesurage et aller vers la demande pour envoi"
                        >
                        <?php echo fontawesome('paper-plane');?>
                        Mesurage
                    </a> -->
                    <a role="button" 
                        class="form_read btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2" 
                        href="<?php echo base_url("calculator/devis/$devis->id_demande/mesurage");?>"
                        onclick="waiting_start_export(this);"
                        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                        title="Créer le document mesurage"
                        >
                        <?php echo fontawesome('print');?>
                        Mesurage
                    </a>
                <?php else:?>
                    <!-- <a role="button" 
                        class="form_read btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2" 
                        href="<?php echo base_url("calculator/devis/$devis->id_demande/print");?>"
                        onclick="waiting_start_export(this);"
                        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                        target="_blank"
                        title="Envoyer et sauvegarder ce devis"
                        >
                        <?php echo fontawesome('paper-plane');?>
                        Devis
                    </a> -->
                    <a role="button" 
                        class="form_read btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2" 
                        href="<?php echo base_url("calculator/devis/$devis->id_demande/print");?>"
                        onclick="waiting_start_export(this);"
                        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                        title="Prévisualiser le devis sous Word (la conversion pdf non encore opérationnelle...)"
                        >
                        <?php echo fontawesome('magnifying-glass');?>
                        Devis
                    </a>
                <?php endif;?>
            <?php endif;?>
            <a role="button" 
                class="btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2" 
                href="<?php echo base_url("calculator/devis");?>"
                title="Retourner à la liste des demandes"
                onclick="waiting_start(this);"
                >
                <?php echo fontawesome('turn-up');?>
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

<?php if(is_null($devis)):?>
    <div class="alert alert-warning">
        Pas de devis trouvé avec cet identifiant.
    </div>
<?php else:?>
    <div class="row">
        <div class="col-3">
            <?php echo view('Calculator\devis-nav');?>
        </div>
        <div class="col">
            <?php echo view('Calculator\devis-info');?>
            <div class="card mb-4">
                <div id="DevisAnchor" class="card-header">
                    Liste des ouvrages
                </div>
                <div class="card-body p-4">
                    <?php foreach($roads as $road_0):?>
                        <div id="DevisRoad<?php echo $road_0->id_road;?>Anchor"
                            id_road_0=<?php echo $road_0->id_road;?>
                            <?php if(empty($devis->works) || !in_array($road_0->id_road, array_column($devis->works, 'id_them_parent'))):?> style="display: none !important;" <?php endif;?>
                            >
                            <div class="sticky_button d-flex p-2 fw-bold bg-dark text-white">
                                <?php echo $road_0->label_fr;?>
                            </div>
                            <?php if(!empty($road_0->children)):?>
                                <?php foreach($road_0->children as $road_1):?>
                                    <div id="DevisRoad<?php echo $road_0->id_road;?>Road<?php echo $road_1->id_road;?>Anchor"
                                        id_road_0=<?php echo $road_0->id_road;?>
                                        id_road_1=<?php echo $road_1->id_road;?>
                                        <?php if(empty($devis->works) || !in_array($road_1->id_road, array_column($devis->works, 'id_them'))):?> style="display: none !important;" <?php endif;?>
                                        >
                                        <div class="sticky_button d-flex p-2 bg-secondary text-white">
                                            <div class="mx-4"></div>
                                            <div class="flex-grow-1"> <?php echo $road_1->label_fr;?> </div>
                                        </div>
                                        <?php if(!empty($devis->works)):?>
                                            <?php foreach($devis->works as $work):?>
                                                <?php if($work->id_them==$road_1->id_road):?>
                                                    <?php echo view('Calculator\devis-work', [
                                                        'work' => $work,
                                                    ]);?>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </div>
                                <?php endforeach;?>
                            <?php endif;?>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        </div>
    </div>
<?php endif;?>


<?php $this->endSection()?>
