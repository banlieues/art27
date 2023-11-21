<?php $this->extend('Layout\index'); ?>

<!-- NAVBARSUB -->
<?php $this->section('navbarsub');?>
<div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->calculator->color;?> border-2">
    <div>
        <div class="d-flex">
            <div class="me-2"><?php echo $themes->calculator->icon;?></div>
            <label>
                <?php echo $titleView;?> :
                <b> <small> <?php echo $road->path;?> </small> </b>
            </label>
            <?php echo view('\DataView\buttons/info-button', ['target_id'=>'RoadInfoCollapse']);?>
        </div>
        <div id="RoadInfoCollapse" class="collapse text-end px-2">
            <?php echo get_info_view($road->updated_at, $road->updated_by, $road->created_at, $road->created_by);?>
        </div>
    </div>
    <div class="d-flex align-items-center"> 
        <a role="button"
            class="btn btn-sm btn-dark"
            href="<?php echo base_url('calculator/roads');?>"
            onclick="waiting_start(this);"
            title="Retourner à la liste des postes"
            >
            <?php echo fontawesome('turn-up');?>
            <?php echo $themes->calculator_post->icon;?>
        </a> 
    </div>
</div>

<?php $this->endSection()?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Calculator\js/js_calculator');?>
    <?php echo view('Tesorus\js/js_tesorus');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body');?>

<div class="row">
    <div class="col-2 order-last">
        <div class="sticky_button list-group">
            <a class="list-group-item list-group-item-action list-group-item-light small"
                href="#RoadInfo"
                >
                Infos sur le poste
            </a>
            <?php if(!empty($road->groups)):?>
                <a class="list-group-item list-group-item-action list-group-item-light small"
                    href="#RoadGroups"
                    >
                    Groupes associés
                </a>
            <?php endif;?>
            <a class="list-group-item list-group-item-action list-group-item-light small"
                href="#RoadPrices"
                >
                Liste des prix unitaires
            </a>
            <a class="list-group-item list-group-item-action list-group-item-light small"
                href="#RoadPricesNew"
                >
                Ajouter de nouveaux prix unitaires
            </a>
        </div>
    </div>
    <div class="col-10">
        <div id="RoadInfo" class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <?php echo fontawesome('tag');?> 
                    Infos sur le poste 
                </div>
                <div class="d-flex">
                    <button type="button" class="form_read btn btn-sm btn-link link-dark ms-2"
                        title="Editer le poste"
                        form="<?php echo $form_id_road_update;?>"
                        onclick="js_form_update(this);"
                        > <?php echo fontawesome('edit');?> 
                    </button>
                    <?php if($Autorisation->is_autorise('calculator_d', $road->created_by)):?>
                        <button class="ban_deleteForm form_update btn btn-sm btn-outline-danger ms-2"
                            title="Supprimer le poste"
                            style="display: none;"
                            form="<?php echo $form_id_road_update;?>"
                            id_delete="<?php echo $road->id_road;?>"
                            href="<?php echo base_url("calculator/road/$road->id_road/delete");?>"
                            text_alert="le poste sélectionné et l'ensemble des prix unitaires reliés"
                            >
                            <?php echo fontawesome('trash-alt');?>
                        </button>
                    <?php endif;?>
                    <a role="button" class="form_update btn btn-sm btn-outline-secondary ms-2"
                        style="display: none;"
                        title="Annuler les modifications sur le poste"
                        form="<?php echo $form_id_road_update;?>"
                        href="<?php echo current_url();?>"
                        onclick="waiting_start(this);"
                        > <?php echo fontawesome('undo');?> 
                    </a>
                    <form id="<?php echo $form_id_road_update;?>" method="post" action="<?php echo base_url('calculator/road/' . $road->id_road);?>">
                        <button type="submit" class="form_update btn btn-sm btn-success ms-2"
                            style="display: none;"
                            title="Sauvegarder les modifications sur le poste"
                            form="<?php echo $form_id_road_update;?>" 
                            href="<?php echo current_url();?>"
                            onclick="waiting_start(this);"
                            > <?php echo fontawesome('save');?> 
                        </button>
                    </form>
                </div>
            </div>
            <div class="card-body py-2">
                <?php echo view('Calculator\road-view-form');?>
            </div>
        </div>        
        <?php if(!empty($road->groups)):?>
            <div id="RoadGroups" class="card mb-4">
                <div class="card-header">
                    <?php echo $themes->calculator_group->icon;?> Groupes de travaux associés
                </div>
                <div class="card-body">
                    <?php foreach($road->groups as $group):?>
                        <div class="row">
                            <div class="col-1">
                                <a role="button" class="pt-0 btn btn-sm btn-link link-dark"
                                    target="_blank"
                                    title="Aller à la fiche du groupe"
                                    href="<?php echo base_url('calculator/group/' . $group->id_group);?>"
                                >
                                    <?php echo fontawesome('up-right-from-square');?>
                                </a>
                            </div>
                            <div class="col">
                                <?php echo $group->label_fr;?>
                            </div>
                        </div>
                    <?php endforeach;?>
                </div>
            </div>
        <?php endif;?>
        <div id="RoadPrices" class="card mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <div>
                    <?php echo fontawesome('cash-register');?>
                    Prix unitaires
                </div>
                <div class="d-flex"
                    title="Calculs sur <?php echo $road->period_month_calcul;?> mois à partir de la date du dernier devis enregistré"
                    >
                    <div class="badge bg-secondary mx-1">
                        Min : <?php echo $road->min_price;?> €
                    </div>
                    <div class="badge bg-secondary mx-1">
                        Moyenne : <?php echo $road->avg_price;?> €
                    </div>
                    <div class="badge bg-secondary mx-1">
                        Max : <?php echo $road->max_price;?> €
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php echo view('Calculator\road-view-price-table');?>
            </div>
        </div>
        <div id="RoadPricesNew" class="card mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <label>
                        <?php echo fontawesome('plus');?>
                        Ajouter de nouveaux PU pour le poste
                    </label>
                    <div class="d-flex">
                        <button type="button" 
                            class="form_read btn btn-sm"
                            data-bs-toggle="collapse"
                            data-bs-target="#PriceNewCollapse"
                            form="<?php echo $form_id_prices_new;?>"
                            onclick="js_form_update(this);"
                            >
                            <?php echo fontawesome('edit');?>
                        </button>
                        <a role="button" class="form_update btn btn-sm btn-outline-secondary ms-2"
                            style="display: none;"
                            title="Annuler les modifications sur le poste"
                            form="<?php echo $form_id_prices_new;?>"
                            href="<?php echo current_url();?>"
                            onclick="waiting_start(this);"
                            > <?php echo fontawesome('undo');?> 
                        </a>
                        <form id="<?php echo $form_id_prices_new;?>" method="post" action="<?php echo base_url('calculator/road/' . $road->id_road);?>">
                            <button type="submit"
                                class="form_update btn btn-sm btn-success ms-2"
                                form="<?php echo $form_id_prices_new;?>"
                                href="<?php echo current_url();?>"
                                onclick="waiting_start(this);"
                                style="display: none;"
                                >
                                <?php echo fontawesome('save');?> 
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div id="PriceNewCollapse" class="card-body plusminus-group collapse">
                <div class="plusminus-row row justify-content-between align-items-end">
                    <?php echo view('Calculator\road-view-price-new', ['i' => 0]);?>
                </div>
                <div class="plusminus-model row justify-content-between align-items-end" style="display: none;">
                    <?php echo view('Calculator\road-view-price-new', ['i' => '##i##']);?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection()?>
