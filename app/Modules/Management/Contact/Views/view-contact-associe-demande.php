<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>
<?php $contactConstructor=\Config\Services::contact();?>

<?php $this->extend('\Contact\view-contact-base'); ?>

<?php $this->section('contact-body');?>

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

              <p class="m-5 text-center"> <i>Choississez une demande et cliquer sur enregister en haut de la page pour l'associer au bien</i></p>

                <div class="table-responsive"> 
                <form id="new_form" method="post" action="<?=base_url()?>contact/save_associe_demande" >
                    <input type="hidden" name="id_contact" value="<?=$id_contact?>">
                    <input type="hidden" name="id_contact_profil" value="<?=$id_contact_profil?>">

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
                                <th>Bien</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach($demandes as $demande):?>
                            <tr
                            <?php if($demande->id_demande_statut==6):?> class="table-light"<?php endif;?>
                                <?php if($demande->id_demande_statut==1):?> class="table-success"<?php endif;?>
                            >
                                <td>
                                    <?php if($id_contact!=$demande->id_contact):?>
                                    <input name="id_demande" value="<?=$demande->id_demande?>" type="radio">
                                    <?php else:?>
                                        <b>Déjà demandeur!</b>
                                    <?php endif;?>
                                </td>
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
                                                    <?php if(!empty($contact->relation_bien)):?>
                                                        (<?=$contact->relation_bien;?>)
                                                     <?php endif;?>   
                                                </a>
                                        <?Php endif;?>
                                        
                                     <?php endforeach;?>   

                                    <?php endif;?>          
                                </td>
                                <td>
                                    <?php
                                       $biens= $demandeModel->getBiens($demande->id_demande);
                                       //debug($biens);
                                    ?>
                                    <?php if(!empty($biens)):?>
                                        <?php foreach($biens as $bien):?>
                                            <?php if(!empty($bien->adresse_fr)):?>
                                                <a href="<?=base_url()?>/bien/fiche/<?=$bien->id_bien?>" class="btn btn-secondary btn-sm mb-2">
                                                <?php echo $themes->bien->icon;?>
                                                    <?=$bien->adresse_fr?>
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
<!-- input hidden to declare update or insert -->


<?php $this->endSection();?>
