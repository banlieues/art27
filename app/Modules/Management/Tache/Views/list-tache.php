<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataview=\Config\Services::dataViewConstructor();?>

<div class="row banData">
    <div class="col-12">
        <div class="card border-top-<?php echo $themes->tache->color;?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php //if($autorisationManager->is_autorise("tache_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbTaches?> 
                                    tache<?=plurial_s($pager->getTotal())?>
                                    <input  id="mes_tache" <?php if($mes_tache==1):?>checked<?php endif;?> class="select_submit" name="mes_tache" value="1" type="checkbox"> <label for="mes_tache"> Mes tâches</label>

                                </h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?php echo $themes->tache->color;?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php //endif;?>

                            <?php //if($autorisationManager->is_autorise("tache_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a class="btn btn-<?php echo $themes->tache->color;?> ajouter_tache_modal btn-sm mt-1 text-white" id_demande="0" href="<?=base_url()?>/tache/form_tache">
                                    <?php echo $themes->tache->icon;?>  Ajouter tâche
                                    </a>
                                </div>
                            <?php //endif;?>

                        </div>
                    </form>
                </div> 

            <?php //if($autorisationManager->is_autorise("tache_r")):?>                                
                <?php if ($nbTaches>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->tache->color )?><?php endif;?>

                    <div class="table-responsive"> 
                        
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($taches as $tache):?>
                                <tr>
                                    <?php //if($autorisationManager->is_autorise("tache_d", $tache->id_user_create)):?>
                                    <td>
                                          
                                        <button text_alert="le tache   
                                          
                                        
                                                <?=$tache->sujet?> <?php if(!empty($tache->id_tache)):?> lié à la demande n°<?=$tache->id_tache?> <?=$tache->sujet?> <?php endif;?>
                                          " 
                                            id_delete="<?=$tache->id_tache?>" href="<?=base_url()?>/delete/deleteTache" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                          
                                    </td>
                                    <?php //endif;?> 

                                    <td>
                            
                                                <?=convert_date_en_to_fr_with_h($tache->date_tache)?>
                                    </td>

                                    <td>
                            
                                        <?=convert_date_en_to_fr_with_h($tache->echeance)?>


                                    </td>
                                   
                                                    
                                    <td width="200px" class="text-center">
                                        <div class="text-center">
         
                                        <?php if(!empty($tache->id_demande)): $id_demande=0; endif?>
                                        <?php 
                                        if(!empty($tache->id_demande)&&$tache->id_demande>0):
                                            $url_tache=base_url().'demande/fiche/'.$tache->id_demande.'/0/0/0/'.$tache->id_tache;
                                        else:
                                            $url_tache=base_url().'tache/form_tache/0/'.$tache->id_tache;
                                        endif;?>

                                        <a class="text-white btn-sm btn btn-<?php echo $themes->tache->color;?>" href="<?=$url_tache?>">
                                                       
                                                       <?=$themes->tache->icon?> <?=($tache->sujet)?>
                                               </a>
                                    </td>

                                    <td class="modif_container">
                                      
                                        <?php 
                                                /*echo view($module.'\form_tache_commentaire', [
                                                        "tache" => $tache,
                                                    
                                                        
                                                    ]);*/
                                                    ?>
                                            <?=nl2br($tache->note);?>
                                        </td>

                                    <td class="modif_container">

                                   
                                       <?=$tache->type_tache;?>
                                    </td>
                                    <td>
                                    <?=$tache->type_tache_libre;?>
                                    </td>

                                    <td class="modif_container CSelectTypeTache<?=$tache->id_tache?> data_id_type_<?=$tache->id_tache?>">


                                            <?=$tache->statut_tache;?>
                                </td>

                                   

                                    <td>
                                        <?php if(!empty($tache->id_demande)):?>

                                            <a href="<?=base_url("demande/fiche/$tache->id_demande")?>" class="btn btn-<?php echo $themes->demande->color;?>  btn-sm text-white"><?php echo $themes->demande->icon;?> N°<?=$tache->id_demande?></a>
                                        <?php endif;?>

                                    </td>

                                    <td>
                

                                    
                                        <?php //echo affiche_adresse_bien($tache->bien_associe)?>

                                    </td>

                                   
                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->tache->color )?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de tâches</h3></div>        
                <?php endif;?>  
            <?php //endif;//autorisation?>  
        </div>        
    </div>

</div>    

<?php $this->endSection(); ?>

<?php $this->section("script_foot_injected"); ?>
<?=view("Demande\js_demande")?>

<?php $this->endSection(); ?>

