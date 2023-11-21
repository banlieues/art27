<?php $this->extend('templates/index'); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="row banData">
    <div class="col-12">
        <div class="card border-top-amethyst">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php if($autorisationManager->is_autorise("contact_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbContacts?> contact<?=plurial_s($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-amethyst text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php endif;?>

                            <?php if($autorisationManager->is_autorise("contact_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a class="btn btn-amethyst btn-sm mt-1" href="<?=base_url()?>/contacts/formContact">
                                        <i class="<?=icon("contacts")?>"></i> Ajouter un contact
                                    </a>
                                </div>
                            <?php endif;?>

                        </div>
                    </form>
                </div> 

            <?php if($autorisationManager->is_autorise("contact_r")):?>                                
                <?php if ($nbContacts>0): ?>
                    <div class="table-responsive"> 
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($contacts as $contact):?>
                                <tr>
                                    <?php if($autorisationManager->is_autorise("contact_d")):?>
                                    <td>
                                          
                                        <button text_alert="le contact   
                                            <?php if(empty($contact->nom)&&empty($contact->prenom)):?>
                                                <?=$contact->nom_court_institution?>
                                            <?php else:?>    
                                                <?=$contact->nom.' '.$contact->prenom?>
                                            <?php endif;?>" 
                                            id_delete="<?=$contact->id_contact?>" href="<?=base_url()?>/delete/deleteContact" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                          
                                    </td>
                                    <?php endif;?> 


                                    <td><?=$contact->typepart?></td>
                                    <td>
                                        <a class="btn btn-sm btn-amethyst text-wrap text-white" href="<?=base_url("contacts/viewContact/$contact->id_contact")?>"><i class="<?=icon("contacts")?>"></i> 
                                            <?php if(empty($contact->nom)&&empty($contact->prenom)):?>
                                                <?=tronque_string($contact->nom_court_institution,25)?>
                                            <?php else:?>    
                                                <?=tronque_string($contact->nom.' '.$contact->prenom,25)?>
                                            <?php endif;?>
                                        </a>
                                    </td>
                                    <td><?=$contact->nom_court_institution?></td>
                                    <?php if($autorisationManager->is_autorise("utilisateur_a")):?>
                                    <td><?php if(!empty($contact->id_user)):?><a class="btn btn-sm btn-primary" href="<?=base_url()?>/utilisator/index/<?=$contact->id_user?>"><i class="fa fa-user"></i> <?=tronque_string($contact->username,20);?></a><?php endif;?></td>
                                    <?php endif;?>
                                    <?php if($autorisationManager->is_autorise("registrations_r")):?> 
                                    <td>
                                        <a class="btn btn-success btn-sm text-nowrap text-white" href="<?=base_url("contacts/viewContactInscription/$contact->id_contact")?>"><i class="<?=icon("activities")?>"></i> <?=$contact->nb_inscription?> inscription<?=plurial_s($contact->nb_inscription)?></a> 
                                    </td>
                                    <?php endif;?> 

                                    <td><?=$contact->codepostal?></td>
                                    <td><?=$contact->localite?></td>

                                    <td><?php //echo convert_date_en_to_fr($contact->date_naissance)?> <?=affiche_an($contact->age);?> </td>
                                
                                    <?php if($autorisationManager->is_autorise("registrations_c")):?>  
                                        <td>
                                    
                                                <a class="btn btn-success access-path text-nowrap btn-sm" href="<?=base_url()?>/registrations/formInscription/0/0/0/<?=$contact->id_contact?>?uri=<?=full_url()?>"><i class="<?=icon("add")?>"></i> Inscription</a>
                                        
                                        </td>
                                    <?php endif;?>
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_amethyst")?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de contacts</h3></div>        
                <?php endif;?>  
            <?php endif;//autorisation?>  
        </div>        
    </div>

</div>    

<?php $this->endSection(); ?>

