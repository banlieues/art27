<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<?php $autorisationManager = \Config\Services::autorisationModel();?>

<div class="row banData">
    <div class="col-12">
        <div class="card border-top-office">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php //if($autorisationManager->is_autorise("contact_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbRequete?> requête<?=plurial_s($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-office text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php //endif;?>

                           

                        </div>
                    </form>
                </div> 
              

            <?php //if($autorisationManager->is_autorise("contact_r")):?>                                
                <?php if ($nbRequete>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_office")?><?php endif;?>

                    <div class="table-responsive"> 
                    <table style='' id='requete' class="requete_table_principal requete_table table table-bordered table-condensed">
                <thead>
                    <tr>
                        <?=$getTh?>
                    </tr>
                </thead>   
                <tbody>
                       <?php foreach($lists as $list):?>
                       <tr>
                       <td>
                           <button text_alert="la requête   
                                            <?=$list->nom?>"
                                    
                                        id_delete="<?=$list->id_requete?>" href="<?=base_url()?>/delete/deleteQuery" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                           </td>
                        <td>
                            <?=$list->id_requete?>
                        </td>
                          
                           <td><?=$list->date_create?></td>
                            <td><?=$list->nom?></td>
                            <td class="modif_container">

                            <?php 
                                            echo view($path.'\is_dasboard', [
                                                    "is_dasboard" => $list->is_dasboard,
                                                    "id_requete"=>$list->id_requete
                                                    
                                                ]);
                                                ?>
                                
                          
                            </td>
                          <!--  <td><?php // echo tronque_string($list->query,200)?></td> -->
                            <td><a class="btn btn-dark " href="<?=base_url()?>/queries/execute/<?=$list->id_requete?>/0">Exécuter requête</a></td>
                       </tr>

                       <?php endforeach;?>
                   </tbody>
                    
                </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_office")?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de requêtes enregistrées</h3></div>        
                <?php endif;?>  
            <?php //endif;//autorisation?>  
        </div>        
    </div>

</div>    

<?php $this->endSection(); ?>


<?php $this->section("script_injected_foot"); ?>
    <?php echo view($path."banQueries_js");?>  
    <?=view("Demande\Views\js_demande")?>                  
<?php $this->endSection(); ?>

