<?php $this->extend('Layout\index'); ?>
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
                    <a class="btn btn-amethyst btn-sm" href="<?=base_url()?>/contacts/formContact/<?=$contact->id_contact?>"><i class="<?=icon("contacts")?>"></i> Modifier la fiche du contact</a>
                    <a class="btn btn-success btn-sm" href="<?=base_url()?>/registrations/formInscription/0/0/0/<?=$contact->id_contact?>?uri=<?=full_url()?>"><i class="<?=icon("add")?>"></i> Inscription</a>
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
    <li class="nav-item">
        <a class="nav-link active" href="<?=base_url()?>/contacts/viewContactInscription/<?=$contact->id_contact?>">
            <i class="<?=icon("registrations")?>"></i> <?=$nbInscriptions?> Inscription<?=plurial_s($nbInscriptions)?>
        </a>
    </li>
</ul>

<div class="row mb-2">
    <div class="col-lg-12">
        <div class="card flex-fill">
            <div class="card-header border-top-amethyst">
                <h5 class="card-title"><i class="<?=icon("registrations")?>"></i> <?=$nbInscriptions?> inscription<?=plurial_s($nbInscriptions)?> pour <?=$contact->nom?> <?=$contact->prenom?></h5>
            </div>
            <div class="card-body">
            
<div class="card flex-fill mb-4">
    <div class="card-body">
        Ce contact est géré par <?php if(!empty($user->id_user)):?><a class="btn btn-sm btn-primary" href="<?=base_url()?>/utilisator/index/<?=$user->id_user?>"><i class="fa fa-user"></i> <?=tronque_string($user->username,20);?></a><?php endif;?></td>

    </div>
</div>

                <?php if(empty($inscriptions)):?>
                    <h3 class="text-center">Pas d'inscriptions enregistrées pour <?=$contact->nom?> <?=$contact->prenom?></h3>
                <?php else:?>
                    <div class="table-responsive">
                    <table id="table_data" class="table table-striped table-hover my-0 table-sm">
                        <thead>
                            <tr>
                                <th class="d-xl-table-cell">Date de suivi</th>
                                <th class="d-xl-table-cell">Inscription</th>
                                <th class="d-xl-table-cell"><i class="fas fa-bed fa-1x"></i></th>
                                <th class="d-xl-table-cell">Ref</th>
                                <th class="d-xl-table-cell">Action</th>
                                <th class="d-xl-table-cell">Date de début</th>
                                <th class="d-xl-table-cell">Limite</th>
                                <th class="d-xl-table-cell">Age</th>
                                
                                <th class="d-xl-table-cell">Statut</th>
                                <th class="d-xl-table-cell">Payement</th>
                                <th class="d-xl-table-cell">PDF</th>
                               
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($inscriptions as $inscription):?>
                            <tr  <?php if($inscription->action_annule==1):?> class="table-danger" <?php endif;?>>
                                <td><?=convert_date_en_to_fr($inscription->date_suivi)?></td>
                                <td><a class="btn btn-sm btn-success text-wrap" href="<?=base_url("registrations/viewinscription/$inscription->id_inscription")?>"><i class="<?=icon("registrations")?>"></i> <?=$inscription->id_inscription?></a></td>
                                <td class="text-center"><?php if($inscription->residentiel=="Y"):?><i class="fas fa-bed fa-1x"></i><?php endif;?></td>

                                <td class="col">
                                    <a class="btn btn-info btn-sm text-white text-wrap" href="<?=base_url("activities/viewActivite/$inscription->id_activity")?>"><i class="<?=icon("activities")?>"></i> <?=$inscription->idact?></a>
                                    <?php if($inscription->action_annule==1):?><div><b>Action annulée</b></div><?php endif;?>
                                </td>
                                <td><?=supprimer_numero($inscription->titre)?></td>
                                <td><?=convert_date_en_to_fr($inscription->date_debut)?></td>  
                                <td><?=render_limit($inscription->nb_inscription,$inscription->max_part)?></td>                              
                                <td><?=calcul_age_from($inscription->date_naissance,$inscription->date_debut)?></td>
                               

                                <td class="CChangeSelectInscription<?=$inscription->id_inscription?>">
                                    <?php 
                                        echo view('registrations/statutSuivi', [
                                        "inscription" => $inscription,
                                        "statutInscriptions"=>$statutInscriptions,
                                        
                                    ]);?>
                                </td>
                                <td class="CChangeSelectPayement<?=$inscription->id_inscription?>">
                                    <?php 
                                    echo view('registrations/statutPayement', [
                                            "inscription" => $inscription,
                                            "statutPayements"=>$statutPayements,
                                            
                                        ]);
                                        ?>
                                 </td>       
                                
                                 <?php if(!in_array($statutSuivi, ["T","A","D","R"])):?>
                                    <td>
                                        <?php if(!in_array($inscription->statutsuivi, ["T","A","D","R"])):?>
                                            <a class="modalView" data-view-title="PDF de l'inscription de <?=$inscription->nom?> <?=$inscription->prenom?> à <?=$inscription->idact?>" href="<?=base_url()?>/SpipPdf/listPdf/<?=$inscription->id_contact?>/<?=$inscription->id_activity?>"><i style="font-size:18px" class="far fa-file-pdf text-danger"></i></a>
                                        <?php endif;?>
                                    </td>
                                <?php endif;?>
                               
                               
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
