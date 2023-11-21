<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="row banData">
    <div class="col-12">
        <div class="card border-top-<?=$themes->message->color?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_with_order">
                        <div class="row">
                            <?php //if($autorisationManager->is_autorise("bien_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbMessages?> message<?=plurial_s($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?=$themes->message->color?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php // endif;?>

                            <?php //if($autorisationManager->is_autorise("bien_c")):?>                
                               
                            <?php //endif;?>

                        </div>
                    </form>
                </div> 

            <?php //if($autorisationManager->is_autorise("bien_r")):?>                                
                <?php if ($nbMessages>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->message->color)?><?php endif;?>

                    <div class="table-responsive"> 
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?php echo $getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($messages as $message):?>
                                <tr <?php if(!$message->vu):?> class="table-danger"<?php endif; ?>>
                                    <td>
                                        <?php if($message->vu):?>
                                            <i class="<?=icon("ok")?> text-success"></i>
                                        <?php endif;?>
                                    </td>
                                <td>
                                <?php if($message->entity=="demande"&&$message->id_entity>0):?>
                
                                            <a href="<?=base_url()?>demande/fiche/<?=$message->id_entity?>" class="btn-sm btn btn-<?=$themes->demande->color?>"> <?=$themes->demande->icon?> Demande n°<?=$message->id_entity?>
                                
                                    

                                        <?php elseif($message->entity=="bien"&&$message->id_entity>0):?>
                                            
                                            <a href="<?=base_url()?>bien/fiche/<?=$message->id_entity?>" class="btn-sm btn btn-<?=$themes->bien->color?>"> <?=$themes->bien->icon?> Bien n°<?=$message->id_entity?>
                                
                                    
                                        <?php elseif($message->entity=="contact"&&$message->id_entity>0):?>
                                            
                                            <a href="<?=base_url()?>contact/fiche/<?=$message->id_entity?>" class="btn-sm btn btn-<?=$themes->contact->color?>"> <?=$themes->contact->icon?> Contact n°<?=$message->id_entity?>
                                

                                        <?php elseif($message->entity=="rdv"&&$message->id_entity>0):?>
                                            
                                            <a href="<?=base_url()?>rdv/form_rdv/<?=$message->id_entity?>" class="btn-sm btn btn-<?=$themes->rdv->color?>"> <?=$themes->rdv->icon?> Rdv n°<?=$message->id_entity?>
                                
                                    

                                        <?php elseif($message->entity=="tache"&&$message->id_entity>0):?>
                                            
                                            <a href="<?=base_url()?>tache/form_tache/<?=$message->id_entity?>" class="btn-sm btn btn-<?=$themes->tache->color?>"> <?=$themes->tache->icon?> Tâche n°<?=$message->id_entity?>
                                
                                    
                                        <?php else: ?>
                                            
                                            <a href="<?=base_url()?>messagerie/message_view/<?=$message->id?>" class="view_message_note list-group-item list-group-item-action">

                                        <?php endif;?>
                                </td>
                                    <td><?=convert_date_en_to_fr_with_h($message->date_created)?></td>
                                   <td>#<?=$message->id?></td>
                                   <td><?=$message->prenom?> <?=$message->nom?></td>
                                   <td><?=$message->subject?></td>
                                   <td><?=nl2br($message->content)?></td>
                                
                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->message->color)?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de message</h3></div>        
                <?php endif;?>  
                 <?php //else:?>
                    <!-- <div class="text-center m-5">
                        <b>Vous n'avez pas l'autorisation pour accèder à ce contenu!</b>
                    </div>-->
            <?php //endif;//autorisation?>  
        </div>        
    </div>

</div>    

<?php $this->endSection(); ?>

