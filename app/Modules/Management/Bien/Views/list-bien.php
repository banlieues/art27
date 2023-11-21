<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="row banData">
    <div class="col-12">
        <div class="card border-top-<?=$themes->bien->color?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php if($autorisationManager->is_autorise("bien_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbBiens?> bien<?=plurial_s($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?=$themes->bien->color?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php endif;?>

                            <?php if($autorisationManager->is_autorise("bien_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a class="btn btn-<?=$themes->bien->color?> btn-sm mt-1" href="<?=base_url()?>/bien/new">
                                    <?=$themes->bien->icon?> Ajouter un bien
                                    </a>
                                </div>
                            <?php endif;?>

                        </div>
                    </form>
                </div> 

            <?php if($autorisationManager->is_autorise("bien_r")):?>                                
                <?php if ($nbBiens>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->bien->color)?><?php endif;?>

                    <div class="table-responsive"> 
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($biens as $bien):?>
                                <tr>
                                    <?php //if($autorisationManager->is_autorise("biens_d", $bien->id_user_create)):?>
                                    <td>
                                          
                                        <button text_alert="le bien   
                                          
                                        
                                                <?=$bien->adresse_fr?>
                                          " 
                                            id_delete="<?=$bien->id_bien?>" href="<?=base_url()?>/delete/deleteBien" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                          
                                    </td>
                                    <?php //endif;?> 

                                    <td>
                                    <a class="btn btn-sm btn-<?=$themes->bien->color?> text-wrap text-white" href="<?=base_url("bien/fiche/$bien->id_bien")?>"><?=$themes->bien->icon?> 
                                        <?php if(!empty(trim($bien->adresse_fr))):?>
                                            <?=$bien->adresse_fr;?> 
                                        <?php else:?>
                                            <i>Adresse inconnue</i> 
                                        <?php endif;?>
                                    </a>
                                    </td>
                                   
                                    <td>
                                    <a class="btn btn-sm btn-<?=$themes->bien->color?> text-wrap text-white" href="<?=base_url("bien/fiche/$bien->id_bien")?>"><?=$themes->bien->icon?> 
                                    <?php if(!empty(trim($bien->adresse_nl))):?>
                                            <?=$bien->adresse_nl;?> 
                                        <?php else:?>
                                            <i>Adresse inconnue</i> 
                                        <?php endif;?>
                                        </a>
                                    </td>

                                    <td>
                                        <?=$bien->bt;?>
                                    </td>
                                    <td>
                                        <?=$bien->etage_logement;?>
                                    </td>
                                    <td>
                                        <?=$bien->type;?>
                                    </td>

                                 

                                    <td>
                                        <?php if($bien->nb_demande>0):?>
                                            <span class="badge bg-amethyst"><?=$bien->nb_demande?> demande<?=plurial_s($bien->nb_demande)?></span>
                                        <?php endif;?>
                                    </td>

                                    <td>
                
                                        <?php echo affiche_adresse_contact($bien->contact_associee)?>

                                    </td>
                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->bien->color)?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de biens</h3></div>        
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

