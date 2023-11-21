<?php $this->extend("Layout\index"); ?>

<?php $this->section("body"); ?>

<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="row banData">
    <div class="col-12">
        <div class="card border-top-<?php echo $themes->contact->color;?>">
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
                                        <button class="btn btn-<?php echo $themes->contact->color;?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php endif;?>

                            <?php if($autorisationManager->is_autorise("contact_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a class="btn btn-<?php echo $themes->contact->color;?> btn-sm mt-1" href="<?=base_url()?>/contact/new/">
                                        <i class="<?=icon("contacts")?>"></i> Ajouter un contact
                                    </a>
                                </div>
                            <?php endif;?>

                        </div>
                    </form>
                </div> 

            <?php if($autorisationManager->is_autorise("contact_r")):?>                                
                <?php if ($nbContacts>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_success")?><?php endif;?>

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
                                    
                                    <td>
                                    <?php if($autorisationManager->is_autorise("contact_d", $contact->id_user_create)):?>
                                          
                                        <button text_alert="le contact   
                                          
                                        
                                                <?=$contact->nom_contact.' '.$contact->prenom_contact?>
                                          " 
                                            id_delete="<?=$contact->id_contact?>" href="<?=base_url()?>/delete/deleteContact" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                            <?php endif;?> 
                                    </td>
                                   

                                    <td>
                                        <?=$contact->type_personne;?>
                                    </td>
                                   
                                    <td>
                                        <a class="btn btn-sm btn-success text-wrap text-white" href="<?=base_url("contact/fiche/$contact->id_contact")?>"><i class="fas fa-user"></i> 
                                               
                                                <?=strtoupper($contact->nom_contact).' '.$contact->prenom_contact?>
                                        </a>
                                    </td>

                                    <td>
                                        <?=$contact->langue;?>
                                    </td>

                                    <td>
                                        <?=$contact->civilite;?>
                                    </td>

                                    <td>
                                        <?=$contact->telephone;?>
                                    </td>

                                    <td>
                                        <?=$contact->email;?>
                                    </td>

                                    <td>
                                        <?php if($contact->nb_demande>0):?>
                                            <span class="badge bg-amethyst"><?=$contact->nb_demande?> demande<?=plurial_s($contact->nb_demande)?></span>
                                        <?php endif;?>
                                    </td>

                                    <td>
                
                                        <?php echo affiche_adresse_bien($contact->bien_associe)?>

                                    </td>
                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_success")?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de contacts</h3></div>        
                <?php endif;?>  
                <?php else:?>
                    <div class="text-center m-5">
                        <b>Vous n'avez pas l'autorisation pour accèder à ce contenu!</b>
                    </div>
            <?php endif;//autorisation?>  
        </div>        
    </div>

</div>    

<?php $this->endSection(); ?>

