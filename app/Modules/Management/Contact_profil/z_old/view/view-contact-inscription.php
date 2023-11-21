<?php $autorisationManager = \Config\Services::autorisationModel();?>

<?php $this->extend('templates/index'); ?>
<?php $this->section("body"); ?>
<?php if(empty($contact)):?>
    <div class="text-center mt-5">
        <h1><i class="<?=icon("triangle_warning")?>"></i> Pas de fiche associée à cet id</h1>
    </div>
    <?php $this->endSection(); ?>
<?php return;?>    
<?php endif;?>

<!--block title -->
<div class="card-header p-1 p-xl-1 sticky_button bg-light">
    <div class="row">
            <div class="col-auto align-self-center">
                <h3 class="fs-4"><?php echo $titleView; ?></h3>
            </div>
            <div class="col align-self-center">
                <div class="text-end">
                <?php if($autorisationManager->is_autorise("contacts_u")):?> 
                    <a class="btn btn-amethyst btn-sm" href="<?=base_url()?>/contacts/formContact/<?=$contact->id_contact?>"><i class="<?=icon("contacts")?>"></i> Modifier la fiche du contact</a>
                <?php endif;?>
                <?php if($autorisationManager->is_autorise("registrations_c")):?> 
                    <a class="btn btn-success btn-sm" href="<?=base_url()?>/registrations/formInscription/0/0/0/<?=$contact->id_contact?>?uri=<?=full_url()?>"><i class="<?=icon("add")?>"></i> Inscription</a>
                <?php endif;?>
                </div>
            </div>   
    </div>
</div>



<!-- block onglet -->
<ul class="nav nav-tabs">
    <li class="nav-item">
        <a class="nav-link text-amethyst" aria-current="page" href="<?=base_url()?>/contacts/viewContact/<?=$contact->id_contact?>">
            <i class="<?=icon("contacts")?>"></i> Voir les données générales
        </a>
    </li>
    <?php if($autorisationManager->is_autorise("registrations_r")):?> 
    <li class="nav-item">
        <a class="nav-link active" href="<?=base_url()?>/contacts/viewContactInscription/<?=$contact->id_contact?>">
            <i class="<?=icon("registrations")?>"></i> <?=$nbInscriptions?> Inscription<?=plurial_s($nbInscriptions)?>
        </a>
    </li>
    <?php endif;?>
</ul>

<div class="row mb-2 banData">
    <div class="col-lg-12">
        <div class="card flex-fill">
            <div class="card-header border-top-amethyst">
                <h5 class="card-title"><i class="<?=icon("registrations")?>"></i> <?=$nbInscriptions?> inscription<?=plurial_s($nbInscriptions)?></h5>
            </div>
            <div class="card-body">
            
            <?php if($autorisationManager->is_autorise("utilisateur_a")):?> 
                <div class="card flex-fill mb-4">
                    <div class="card-body">
                        Ce contact est géré par l'utilisateur <?php if(!empty($user->id)):?><a class="btn btn-sm btn-primary" href="<?=base_url()?>/utilisator/index/<?=$user->id?>"><i class="fa fa-user"></i> <?=tronque_string($user->username,20);?></a><?php endif;?></td>

                    </div>
                </div>
            <?php endif;?>

               
                    <div class='card-header bg-light'>
                        <form class="form_with_order">
                            <div class="row">
                                <div class="col-lg-auto p-1 align-self-center">
                                    <select class="select_submit" name="statutSuivi" >
                                        <option value="0">Tous les statuts</option>
                                        <?php foreach($statutInscriptions as $statut):?>
                                            <option <?php if($statut->ref==$statutSuivi):?>selected<?php endif;?> value="<?=$statut->ref?>"><?=$statut->label?></option>
                                        <?php endforeach;?>    
                                    </select>
                                    <?=$nbInscriptions?> inscription<?=plurial_s($nbInscriptions); ?>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" value="<?=$itemSearch?>">
                                        <button class="btn btn-success btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div> 
                                </div>
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                </div>                
                            </div>
                        </form>                   
                    </div>
                    <?php if(empty($inscriptions)):?>
                            <h3 class="text-center mt-3">Pas d'inscriptions trouvées</h3>
                    <?php else:?>
                    <div class="table-responsive">
                        <table id="table_data" class="table table-bordered table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($inscriptions as $inscription): ?>
                                
                                <tr 
                                    <?php if($inscription->statut_action==2):?> class="table-danger" <?php endif;?>
                                    <?php if($inscription->statut_action==3):?> class="table-success" <?php endif;?> 
                                    
                                >
                                <?php if($autorisationManager->is_autorise("registrations_d")):?> 
                                    <td>
                                        <button text_alert="l'inscription    
                                                <?=$inscription->id_inscription?>"
                                        
                                            id_delete="<?=$inscription->id_inscription?>" href="<?=base_url()?>/delete/deleteInscription" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                    </td>
                                <?php endif;?>
                                    <td class="data_date_suivi_<?=$inscription->id_inscription?>"><?=convert_date_en_to_fr($inscription->date_suivi); ?></td>
                                    <td><a class="btn btn-sm btn-success text-wrap" href="<?=base_url("registrations/viewinscription/$inscription->id_inscription"); ?>"><i class="<?php echo icon('registrations'); ?>"></i></a></td>
                                    <td class="text-center"><?php if($inscription->residentiel=="Y"):?><i class="fas fa-bed fa-1x"></i><?php endif;?></td>

                                    <td class="col">
                                        <a class="btn btn-info btn-sm text-nowrap text-white" href="<?=base_url("activities/viewActivite/$inscription->id_activity"); ?>"><i class="<?php echo icon('activities'); ?>"></i> <?=$inscription->idact?></a>
                                        <?php if($inscription->statut_action==2):?> <br><b>Action annulée</b> <?php endif;?>
                                        <?php if($inscription->statut_action==3):?> <br><b>Action clôturée</b> <?php endif;?>    
                                    </td>
                                    <td><?=supprimer_numero($inscription->titre); ?></td>
                                    <td><?=convert_date_en_to_fr($inscription->date_debut); ?></td>
                                    <td><?=render_limit($inscription->nb_inscription,$inscription->max_part); ?></td>
                                    <td><a class="btn btn-amethyst btn-sm text-wrap text-white" href="<?=base_url("contacts/viewContact/$inscription->id_contact"); ?>"><i class="<?php echo icon('contacts'); ?>"></i> 
                                        <?php if(empty($inscription->nom)&&empty($inscription->prenom)):?>
                                            <?=$inscription->nom_court_institution; ?>
                                        <?php else:?>
                                            <?=tronque_string($inscription->nom.' '.$inscription->prenom,40); ?>
                                        <?php endif;?>
                                    </a></td>
                                    <?php if($autorisationManager->is_autorise("utilisateur_a")):?> 
                                    <td><?php if(!empty($inscription->id_user)):?><a class="btn btn-sm btn-primary" href="<?=base_url()?>/utilisator/index/<?=$inscription->id_user?>"><i class="fa fa-user"></i> <?=tronque_string($inscription->username,20);?></a><?php endif;?></td>
                                    <?php endif;?>
                                    <td>

                                        
                                        <?=affiche_an($inscription->age);?>
                                    </td>

                                    <td>
                                        <?php if($inscription->alimentation=="viande"):?>
                                            <i class="<?=icon("meat")?>"></i>
                                        <?php endif;?>
                                        <?php if($inscription->alimentation=="sansviande"):?>
                                           <i class="<?=icon("no-meat")?>"></i>
                                        <?php endif;?>
                                        <?php if($inscription->alimentation=="vegetarien"):?>
                                            <i class="<?=icon("no-meat-vegetarian")?>"></i>
                                        <?php endif;?>  
                                     </td>     


                                    <td class="CChangeSelectInscription<?=$inscription->id_inscription?>  data_statutsuivi_<?=$inscription->id_inscription?>">
                                        <?php 
                                            echo view('registrations/statutSuivi', [
                                            "inscription" => $inscription,
                                            "statutInscriptions"=>$statutInscriptions,
                                            
                                        ]);?>
                                    
                                    </td>
                                
                                    <td class="CChangeSelectPayement<?=$inscription->id_inscription?> data_statut_payement_<?=$inscription->id_inscription?>">
                                        <?php 
                                        echo view('registrations/statutPayement', [
                                                "inscription" => $inscription,
                                                "statutPayements"=>$statutPayements,
                                                
                                            ]);
                                            ?>
    
                                    </td>
                                
                                    <td>

                                    <?php $solde=calculer_solde(
                                        $inscription->prix,
                                        $inscription->prix_organisme,
                                        $inscription->prix_etudiant,
                                        $inscription->prix_special,
                                        $inscription->typepart,
                                        $inscription->demandeur_emploi,
                                        $inscription->historique_payement,
                                        false
                                );

                                
                                ?> 
                                    
                                    <?=affiche_solde($solde,$inscription->statutsuivi,$inscription->statut_payement)?>

                                                
                                    </td>
                                    <td class="text-center">
                                        <?php if($inscription->count_confirmation>0):?>
                                            <i class="<?=icon("confirmation_pdf")?>"></i>
                                        <?php endif;?>    

                                        <?php if(!empty(trim($inscription->bio))||!empty(trim($inscription->remarques_inscription))):?>
                                            <a href="<?=base_url()?>/registrations/getRemarquesInscription/<?=$inscription->id_inscription?>" class="text-success modalView" data-view-title="Remarques pour l'inscription de <?=$inscription->nom?> <?=$inscription->prenom?> à <?=$inscription->idact?>"><i class="<?=icon("triangle_warning")?>"></i></a>
                                        <?php endif;?>

                                        <?php if(!empty(trim($inscription->remarques_gestion))):?>
                                            
                                            <a href="<?=base_url()?>/registrations/getRemarquesGestion/<?=$inscription->id_inscription?>" class="text-success modalView" data-view-title="Historique système pour l'inscription de <?=$inscription->nom?> <?=$inscription->prenom?> à <?=$inscription->idact?>"><i class="<?=icon("remarques")?>"></i></a>
                                        <?php endif;?>
                                    </td>
                                        <td>
                                            <?php if(!in_array($inscription->statutsuivi, ["T","A","D","R"])):?>
                                                <a class="modalView btn btn-action" data-view-title="PDF de l'inscription de <?=$inscription->nom?> <?=$inscription->prenom?> à <?=$inscription->idact?>" href="<?=base_url()?>/documentsgenerator/list/<?=$inscription->id_activity?>/<?=$inscription->id_contact?>/<?=$inscription->id_inscription?>"><i style="font-size:30px" class="<?php echo icon('file-pdf'); ?> text-danger"></i> </a>

                                            <?php endif;?>
                                        </td>
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                     </div>
                    <?php if($pagerInscription->getPageCount()>1):?><?=$pagerInscription->links("default","bs_amethyst")?><?php endif;?>   
                <?php endif;?>    
                
            </div>    
        </div>    
    </div>
</div>

<?php $this->endSection(); ?>
