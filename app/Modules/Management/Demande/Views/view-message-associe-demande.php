<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="row">
    <h5><?=$titleView?>

    <a href="<?=base_url()?>outlook/message_view/<?=$id_message?>" id_message="<?php echo $id_message;?>" class="view_message btn btn-warning message_lire get_content_modal_p"><i class="<?=icon("message_view")?>"></i> Lire message</a>

</h5>
</div>
<div class="row banData">
    <div class="col-12">
    <div class="card border-top-amethyst">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php if($autorisationManager->is_autorise("demande_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'>
                                    <?=$nbDemandes?> demande<?=plurial_s($pager->getTotal())?>
                                        <select class="select_submit" name="statut_demande" >
                                            <option value="0">Tous les statuts</option>
                                            <?php foreach($statut_demandes as $statut):?>
                                                <option <?php if($id_statut_demande==$statut->id):?>selected<?php endif;?> value="<?=$statut->id?>"><?=$statut->label?></option>
                                            <?php endforeach;?>    
                                        </select>
                                        <input <?php if($mes_demandes==1):?>checked<?php endif;?>  class="select_submit" name="mes_demandes" value="1" type="checkbox"> Mes demandes
                                        <input <?php if($homegrade==1):?>checked<?php endif;?>  class="select_submit" name="homegrade" value="1" type="checkbox"> Homegrade
                                        
                                        
                                    </h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-amethyst text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php endif;?>

                            <?php if($autorisationManager->is_autorise("demande_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                <a class="btn btn-danger btn-sm mt-1" href="<?=base_url()?>demande/new/outlook/<?=$id_message?>">
                                    <i class="<?=icon("cancel")?>"></i> Annuler
                                </a>
                            </div>
                            <?php endif;?>

                        </div>
                    </form>
                </div> 
                <?php if($autorisationManager->is_autorise("demande_r")):?>                                

            <?php if ($nbDemandes>0): ?>
                <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_amethyst")?><?php endif;?>
                <div class="table-responsive"> 
                    <table id="table_data" class="table table-striped table-hover my-0 table-sm">
                        <thead>
                        <tr>
                                    <?=$getTh?>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach($demandes as $demande):?>
                            <tr
                            <?php if($demande->id_demande_statut==6):?> class="table-light"<?php endif;?>
                                <?php if($demande->id_demande_statut==1):?> class="table-success"<?php endif;?>
                            >

                               
                                <td><?=convert_date_en_to_fr_with_h($demande->date,FALSE)?></td>
                                <td><a href="<?=base_url("demande/fiche/$demande->id_demande/0/$id_message")?>" class="btn btn-info btn-sm"><?php echo $themes->demande->icon;?> Associer à n°<?=$demande->id_demande?></a></td>
                                <td><?=$demande->type?></td>
                                <td ><?=$demande->statut?></td>
                                <td><?=$demande->prenom_createur?> <?=$demande->nom_createur?></td>
                                <td><?=$demande->prenom_encharge?> <?=$demande->nom_encharge?> <?php //$demande->id_utilisateur?></td>
                                <td><?=$demande->sujet?></td>
                                <td>
                                <?php echo affiche_adresse_contact($demande->contact_associee)?>

                                          
                                </td>
                                <td>
                                <?php echo affiche_adresse_bien($demande->bien_associe)?>

                                   

                                </td>
                                
                            </tr>
                        <?php endforeach;?>
                        </tbody>   
                    </table>
                </div>   
                <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_amethyst")?><?php endif;?>
            <?php else:?>
                <div class="text-center m-5"><h3>Je n'ai pas trouvé de demandes</h3></div>        
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

