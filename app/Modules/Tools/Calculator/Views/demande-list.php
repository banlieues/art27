<?php $this->extend('\Layout\index'); ?>

<?php $this->section('navbarsub');?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->calculator->color;?> border-2">
        <div>
            <?php echo $titleView;?> (<strong> <?php echo $nbDemandes;?> </strong>)
            <?php if(!empty($itemSearch)):?>
                <div class="d-inline small" title="Filtre : <?php echo $itemSearch;?>"><?php echo fontawesome('filter-warning');?></div>
            <?php endif;?>
        </div>
        <div>
            <select class="form-select form-select-sm"
                name="statut_demande"
                form="searchOrderForm"
                >
                <option value="0"> Tous les statuts </option>
                <?php foreach($statut_demandes as $statut):?>
                    <option value="<?=$statut->id?>" <?php if($id_statut_demande==$statut->id):?>selected<?php endif;?>>
                        <?=$statut->label?>
                    </option>
                <?php endforeach;?>    
            </select>
        </div>
        <div class="form-check form-check-sm">
            <input type="checkbox"
                class="form-check-input input-nullable"
                name="mes_demandes"
                value="1"
                form="searchOrderForm"
                <?php if($mes_demandes==1):?> checked <?php endif;?>
            />
            <label class="form-check-label small"> Mes demandes assignées </label>
        </div>
        <div> <?php echo view('DataView\buttons/pagination');?> </div>
        <div> <?php echo view('DataView\buttons/search', ['color' => $themes->calculator->color,]);?> </div>
    </div>
<?php $this->endSection();?>

<?php $this->section('body');?>


<div class="alert alert-warning">
    Seules les demandes avec des biens et des demandeurs identifiés (non anonymes) sont affichées.
</div>
<div class="banData">
    <?php if(empty($nbDemandes)):?>
        <div class="text-center m-5">
            <h3> <?php echo t("Pas de demandes trouvées avec ces critères.", $namespace);?> </h3>
        </div>  
    <?php else:?>
        <div id="demandes-list" class="table-responsive table-fullview"> 
            <table class="table table-bordered table-striped table-hover my-0 table-sm w-100">
                <thead class="table-light sticky-top">
                    <tr>
                        <?php echo $getTh;?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($demandes as $demande):?>
                        <tr
                            <?php if($demande->id_demande_statut==6):?> class="table-light"<?php endif;?>
                                <?php if($demande->id_demande_statut==1):?> class="table-success"<?php endif;?>
                            >
                            <td>
                                <a href="<?=base_url("demande/fiche/$demande->id_demande")?>" class="btn btn-amethyst btn-sm text-white"><i class="far fa-clipboard"></i> N°<?=$demande->id_demande?></a>
                            </td>
                            <td> <?=convert_date_en_to_fr_with_h($demande->date, FALSE)?> </td>
                            <td> <?=$demande->type?> </td>
                            <td> <?=$demande->statut?> </td>
                            <td> <?=$demande->prenom_createur?> <?=$demande->nom_createur?> </td>
                            <td> <?=$demande->prenom_encharge?> <?=$demande->nom_encharge?> </td>
                            <td> <?=$demande->sujet?> </td>
                            <td> <?php echo affiche_adresse_contact($demande->contact_associee)?> </td>
                            <td> <?php echo affiche_adresse_bien($demande->bien_associe)?> </td>
                            <td>
                                <?php if(!empty($demande->works)):?>
                                    <a class="btn btn-sm btn-outline-<?php echo $themes->calculator->color;?>"
                                        href="<?php echo base_url("calculator/devis/$demande->id_demande")?>"
                                        >
                                        <?php echo fontawesome('eye');?>
                                        Voir le devis
                                        <?php echo $themes->calculator->icon;?>
                                    </a>
                                <?php else:?>
                                    <a class="btn btn-sm btn-<?php echo $themes->calculator->color;?>"
                                        href="<?php echo base_url("calculator/devis/$demande->id_demande")?>"
                                        >
                                        <?php echo fontawesome('plus');?>
                                        Nouveau devis
                                        <?php echo $themes->calculator->icon;?>
                                    </a>  
                                <?php endif;?>                  
                            </td>
                            <td></td>
                        </tr>
                    <?php endforeach;?>
                </tbody>   
            </table>   
        </div>        
    <?php endif;?>
</div>    

<?php $this->endSection()?>
