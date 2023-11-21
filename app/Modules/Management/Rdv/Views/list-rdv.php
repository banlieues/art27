<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataview=\Config\Services::dataViewConstructor();?>

<div class="row banData">
    <div class="col-12">
        <div class="card border-top-<?php echo $themes->rdv->color;?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php //if($autorisationManager->is_autorise("rdv_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbRdvs?> 
                                    rdv<?=plurial_s($pager->getTotal())?>
                                    <input  id="mes_rdv" <?php if($mes_rdv==1):?>checked<?php endif;?> class="select_submit" name="mes_rdv" value="1" type="checkbox"> <label for="mes_rdv"> Mes rendez-vous</label>

                                </h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?php echo $themes->rdv->color;?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php //endif;?>

                            <?php //if($autorisationManager->is_autorise("rdv_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a class="btn btn-<?php echo $themes->rdv->color;?> ajouter_rdv_modal btn-sm mt-1 text-white" id_demande="0" href="<?=base_url()?>/rdv/form_rdv">
                                    <?php echo $themes->rdv->icon;?>  Ajouter rdv
                                    </a>
                                </div>
                            <?php //endif;?>

                        </div>
                    </form>
                </div> 

            <?php //if($autorisationManager->is_autorise("rdv_r")):?>                                
                <?php if ($nbRdvs>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->rdv->color )?><?php endif;?>

                    <div class="table-responsive"> 
                        
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($rdvs as $rdv):?>
                                <tr>
                                    
                                    <?php if($autorisationManager->is_autorise("rdv_d")):?>
                                    <td>
                                          
                                        <button text_alert="le rdv   
                                          
                                        
                                                <?=$rdv->titre?> du <?=convert_date_en_to_fr_with_h($rdv->date_rdv_debut)?>
                                          " 
                                            id_delete="<?=$rdv->id_rdv?>" href="<?=base_url()?>rdv/deleteRdv" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                          
                                    </td>
                                    <?php endif;?> 

                                    <td>
                            
                                                <?=convert_date_en_to_fr_with_h($rdv->date_rdv_debut)?>
                                    </td>

                                    <td>
                            
                                        <?=convert_date_en_to_fr_with_h($rdv->date_rdv_fin)?>


                                    </td>
                                   
                                                    
                                    <td width="200px" class="text-center">
                                        <div class="text-center">
         
                                        <?php if(!empty($rdv->id_demande)): $id_demande=0; endif?>
                                        <?php 
                                        if(!empty($rdv->id_demande)&&$rdv->id_demande>0):
                                            $url_rdv=base_url().'demande/fiche/'.$rdv->id_demande.'/0/0/'.$rdv->id_rdv;
                                        else:
                                            $url_rdv=base_url().'rdv/form_rdv/0/'.$rdv->id_rdv;
                                        endif;?>

                                        <a class="text-white btn-sm btn btn-<?php echo $themes->rdv->color;?>" href="<?=$url_rdv?>">
                                                       
                                                       <?=$themes->rdv->icon?> <?=($rdv->titre)?>
                                               </a>
                                    </td>

                                    <td class="modif_container">
                                      
                                        <?php 
                                                /*echo view($module.'\form_rdv_commentaire', [
                                                        "rdv" => $rdv,
                                                    
                                                        
                                                    ]);*/
                                                    ?>
                                            <?=nl2br($rdv->note);?>
                                        </td>

                                    <td class="modif_container">

                                    <?php 
                                         /* echo view($module.'\form_rdv_type', [
                                                    "rdv" => $rdv,
                                                  
                                                    
                                                ]);*/
                                                ?>
                                       <?=$rdv->type_rdv;?>
                                    </td>

                                    <td class="modif_container CSelectTypeRdv<?=$rdv->id_rdv?> data_id_type_<?=$rdv->id_rdv?>">


                                            <?php 
                                         /*echo view($module.'\form_rdv_statut', [
                                                    "rdv" => $rdv,
                                                  
                                                    
                                                ]);*/
                                                ?>
                                            <?=$rdv->statut_rdv;?>
                                </td>

                                   

                                    <td>
                                        <?php if(!empty($rdv->id_demande)):?>

                                            <a href="<?=base_url("demande/fiche/$rdv->id_demande")?>" class="btn btn-<?php echo $themes->demande->color;?>  btn-sm text-white"><?php echo $themes->demande->icon;?> N°<?=$rdv->id_demande?></a>
                                        <?php endif;?>

                                    </td>

                                    <td>
                

                                    
                                        <?php //echo affiche_adresse_bien($rdv->bien_associe)?>

                                    </td>

                                   
                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->rdv->color )?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de rdvs</h3></div>        
                <?php endif;?>  
            <?php //endif;//autorisation?>  
        </div>        
    </div>

</div>    

<?php $this->endSection(); ?>

<?php $this->section("script_foot_injected"); ?>
<?=view("Demande\js_demande")?>

<?php $this->endSection(); ?>

