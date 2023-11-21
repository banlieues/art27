<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>
<?php $contactConstructor=\Config\Services::contact();?>

<?php $this->extend('\Partenaire_social\view-partenaire_social-base'); ?>

<?php $this->section('partenaire_social-body');?>

<div class="row mb-2">

<!--badge bg-amethyst text-decoration-none-->
<div class="row">
  
    <div class="col-12">
        <div class="card border-top-amethyst">
                <div class="card-header sticky_button bg-light">
                    <form>
                        <div class="row">
                            <div class="col-lg-auto p-1 align-self-center">
                                <h5 class='card-title mb-0'>
                                <?=$nbDemandes?> demande<?=plurial_s($pager->getTotal())?>
                                  
                                    
                                    
                                </h5>
                            </div>
                            <div class="col-lg-8 mx-auto p-1 align-self-center"> 
                                <div class="input-group input-group-navbar text-lg-end">
                                    <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher une demande à associer" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                    <button class="btn btn-amethyst text-white btn-sm" type="submit"><i class="fa fa-search"></i></button>
                                </div>
                            </div>
                          
                        </div>
                    </form>
                </div> 
            <?php if ($nbDemandes>0): ?>

              <p class="m-5 text-center"> <i>Choississez une demande et cliquer sur enregister en haut de la page pour l'associer au partenaire_social</i></p>

                <div class="table-responsive"> 
                <form id="new_form" method="post" action="<?=base_url()?>/partenaire_social/save_associe_demande" >
                    <input type="hidden" name="id_partenaire_social" value="<?=$partenaire_social->id_partenaire_social?>">
                    <table id="table_data" class="table table-striped table-hover my-0 table-sm">
                        <thead>
                            <tr>
                              <th></th>
                                <th>Date</th>
                                <th>N°</th>
                                <th>Type</th>
                                <th>Statut</th>
                                <th>Créateur</th>
                                <th>En charge</td>
                                <th>Sujet</th>
                                <th>Demandeurs</th>
                                <th>Partenaire_social</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($demandes as $demande):?>
                            <tr
                            <?php if($demande->id_demande_statut==6):?> class="table-light"<?php endif;?>
                                <?php if($demande->id_demande_statut==1):?> class="table-success"<?php endif;?>
                            >
                                 <td><input name="id_personne_partenaire_social" value="<?=$demande->id_personne_partenaire_social?>" type="radio"></td>
                                <td><?=convert_date_en_to_fr_with_h($demande->date,FALSE)?></td>
                                <td><a href="<?=base_url("demande/fiche/$demande->id_demande")?>" class="btn btn-amethyst btn-sm text-white"><?php echo $themes->demande->icon;?> N°<?=$demande->id_demande?></a></td>
                                <td><?=$demande->type?></td>
                                <td ><?=$demande->statut?></td>
                                <td><?=$demande->prenom_createur?> <?=$demande->nom_createur?></td>
                                <td><?=$demande->prenom_encharge?> <?=$demande->nom_encharge?> <?php //$demande->id_utilisateur?></td>
                                <td><?=$demande->sujet?></td>
                                <td>
                                    <?php
                                       $contacts= $demandeModel->getContacts($demande->id_demande);
                                      
                                    ?>
                                    <?php if(!empty($contacts)):?>
                                      <?php foreach($contacts as $contact):?>
                                        <?php if(!empty($contact->nom_contact)||!empty($contact->prenom_contact)):?>
                                                <a href="<?=base_url()?>/contact/fiche/<?=$contact->id_contact?>" class="btn btn-success btn-sm mb-2">
                                                <?php echo $themes->contact->icon;?> <?=strtoupper($contact->nom_contact)?> <?=$contact->prenom_contact?>
                                                    <?php if(!empty($contact->relation_partenaire_social)):?>
                                                        (<?=$contact->relation_partenaire_social;?>)
                                                     <?php endif;?>   
                                                </a>
                                        <?Php endif;?>
                                        
                                     <?php endforeach;?>   

                                    <?php endif;?>          
                                </td>
                                <td>
                                    <?php
                                       $partenaire_socials_culturel= $demandeModel->getPartenaire_socials_culturel($demande->id_demande);
                                       //debug($partenaire_socials_culturel);
                                    ?>
                                    <?php if(!empty($partenaire_socials_culturel)):?>
                                        <?php foreach($partenaire_socials_culturel as $partenaire_social):?>
                                            <?php if(!empty($partenaire_social->adresse_fr)):?>
                                                <a href="<?=base_url()?>/partenaire_social/fiche/<?=$partenaire_social->id_partenaire_social?>" class="btn btn-secondary btn-sm mb-2">
                                                <?php echo $themes->partenaire_social->icon;?>
                                                    <?=$partenaire_social->adresse_fr?>
                                                </a>
                                            <?php endif;?>    
                                        <?php endforeach;?>    

                                    <?php endif;?>    

                                </td>
                                
                            </tr>
                        <?php endforeach;?>
                        </tbody>   
                    </table>
                  </form>
                </div>   
                <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_amethyst")?><?php endif;?>
            <?php else:?>
                <div class="text-center m-5"><h3>Je n'ai pas trouvé de demandes</h3></div>        
            <?php endif;?>    
        </div>        
    </div>

</div>    
   


</div>

<!-- input hidden to declare update or insert -->
<script>
      $("document").ready(function()
      {
            $(document).on("click","#btn_new_form", function(){

                  $("#new_form").submit();

                  return false;
            });
            
      }
      )
</script>

<?php $this->endSection();?>
