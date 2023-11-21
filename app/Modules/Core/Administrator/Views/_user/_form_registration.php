<?php $request = \Config\Services::request(); ?>

<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
    <?php if(!$is_distant):?>
    <div class="col-md-3">
        <?php echo $this->include('../Modules/Utilisator/Views/sidebar'); ?>
    </div>
    <?php endif;?>
  
    <?php if($is_distant && session('loggedUserId')):?>
        <div style="text-align: right !important">
            <a style="text-align: right !important" class="text-left" href="<?=base_url("identification/logout_distance")?>">
                Se déconnecter
            </a>
        </div>
    <?php endif;?>
    <div class="col-md-<?php if(!$is_distant):?>9<?php else:?>12<?php endif;?> col-12">

        <h4>Inscription à une action</h4>
       
       
        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card flex-fill border-top-theme mb-4">
                   
                    <div class="card-body">
                        <form action="<?=base_url()?>/user/form_registration/<?=$user->id?>" method="get">
                        <input type="hidden" name="is_distant" value="<?=$is_distant?>">
                        <?php if($is_distant):?>
                            <input type="hidden" name="id_activity" value="<?=$id_activity?>">
                        <?php else:?>    
                            <div class="mb-3">
                                <label for="exampleFormControlInput1" class="form-label">Sélectionner une action</label>
                            
                                <?php if($id_activity==0):?>
                                    <div class="text-danger">
                                        <i class="<?=icon("triangle_warning")?>"></i>Vous devez choisir une de nos actions
                                    </div>
                                <?php endif;?>

                                <select name="id_activity" class="form-control submit_form_select">
                                    <option value="0">Sélectionner une action</option>
                                    <?php foreach($activities as $activity):?>
                                        <option <?php if($id_activity==$activity->id_activity):?>selected<?php endif;?> value="<?=$activity->id_activity?>"><?=$activity->titre;?></option>
                                    <?php endforeach?>
                                </select>




                            </div>
                        <?php endif;?>                

                        <div class="mb-1">

                            <label for="exampleFormControlTextarea1" class="form-label">Sélectionner un contact ou créér un nouveu contact</label>
                            <div class="row row-cols-2 row-cols-md-2 g-4">
                                        <?php if(!empty($contacts_possible)):?>
                                            <?php foreach($contacts_possible as $contact_possible):?>
                                                
                                                <div class="col">
                                                    <div class="card h-100">
                                                        <div class="card-body">
                                                            <h5 class="card-title">
                                                                <input class="submit_form" <?php if($id_contact==$contact_possible->id_contact):?> checked <?php endif;?> value="<?=$contact_possible->id_contact?>" name="id_contact" type="radio"> 
                                                                    <?php if(empty($contact_possible->prenom_contact)&&empty($contact_possible->nom_contact)):?>
                                                                        <?=$contact_possible->nom_court_institution?>
                                                                    <?php else:?>
                                                                        <?=$contact_possible->prenom_contact?> <?=$contact_possible->nom_contact?>
                                                                    <?php endif;?>
                                                            </h5>
                                                            
                                                            
                                                        </div>
                                                    
                                                    </div>
                                                </div>
                                            <?php endforeach;?>
                                           
                                        <?php endif;?> 
                                        <div class="col">
                                                    <div class="card h-100">
                                                        <div class="card-body">
                                                            <h5 class="card-title">
                                                                <input class="submit_form" <?php if($id_contact=="creer"):?> checked <?php endif;?> value="creer" name="id_contact" type="radio"> Créer un contact
                                                            </h5>
                                                            
                                                            
                                                        </div>
                                                    
                                                    </div>
                                            </div>   
                                </div>                            
                                                        
                        
                        </div>

                        
                          <button  style="display:none" class="btn btn-success" type="submit">Voir le formulaire</button>
                        </form>
                    </div>  
                </div>            
                       
        
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 col-xl-12">
                <div class="card flex-fill border-top-theme mb-4">
                   
                    <div class="card-body card_form_load">
                    <?php if(!is_null($form_registration)):?> 
                      <?=$form_registration?>
                      <?php endif;?>      
                        
                    </div>  
                </div>            
                       
        
            </div>
        </div>
    
</div>       

<?php $this->endSection(); ?>


