
<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>

<div class="row banData">
<div class="container_pager_ajax">
    <div class="col-12">
        <div class="card border-top-<?php echo $themes->ticket->color;?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_link_ajax form_with_order" action="<?=base_url()?>/demande/liste_ticket_gerer_demande/<?=$id_demande?>">
                        <div class="row">
                            <?php //if($autorisationManager->is_autorise("tickets_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbDocuments?> ticket<?=plurial_s($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?php echo $themes->ticket->color;?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php //endif;?>

                            <?php //if($autorisationManager->is_autorise("tickets_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a id_demande="<?=$id_demande?>" class="btn btn-<?php echo $themes->ticket->color;?> ajouter_ticket_modal btn-sm mt-1" href="#">
                                    <?php echo $themes->ticket->icon;?>  Ajouter ticket
                                    </a>
                                </div>
                            <?php //endif;?>

                        </div>
                    </form>
                </div> 

            <?php //if($autorisationManager->is_autorise("tickets_r")):?>                                
                <?php if ($nbDocuments>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_full_ajax")?><?php endif;?>

                    <div class="table-responsive"> 
                        <table id="table_data" class="table table-bordered  table-striped table-hover my-0 table-sm">
                            <thead>
                                <tr>
                                    <?=$getTh?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($tickets as $ticket):?>
                                <tr>
                                   

                                    <td>
                                                    Document n°<?php echo $ticket->id_ticket?><br>
                                                Uploadé le <?=convert_date_en_to_fr_with_h($ticket->date_created)?><br>
                                                par <?=$ticket->user?>

                                    </td>
                                                    
                                    <td width="200px" class="text-center">
                                        <div class="text-center">
                                            <?php if(is_image($ticket->url_file)):?>
                                                <img width="100px" src="<?=base_url()?>/<?=URL_DOCUMENT?><?=$ticket->url_file;?>" class="" alt="<?=$ticket->name?>">

                                            <?php else:?>
                                                <i class="fas fa-file fa-3x text-<?php echo $themes->ticket->color;?>"></i> 
                                            <?php endif;?> 
                                            </div>

                                        <a class="text-<?php echo $themes->ticket->color;?>" href="<?=base_url()?>/<?=URL_DOCUMENT?><?=$ticket->url_file;?>">
                                                       
                                                <?=($ticket->name)?>
                                        </a>
                                    </td>

                                    <td class="modif_container">

                                    <?php 
                                            echo view('DocumentUpload\form_ticket_commentaire', [
                                                    "ticket" => $ticket,
                                                  
                                                    
                                                ]);
                                                ?>
                                       
                                    </td>

                                    <td class="modif_container CSelectTypeDocument<?=$ticket->id_ticket?> data_id_type_<?=$ticket->id_ticket?>">


                                            <?php 
                                            echo view('DocumentUpload\form_ticket_type', [
                                                    "ticket" => $ticket,
                                                    "type_ticket"=>$type_ticket,
                                                    
                                                ]);
                                                ?>
  
                                </td>

                                   

                                    <td>
                                        <?php if(!empty($ticket->id_demandes)):?>

                                           <?php foreach(explode(",",$ticket->id_demandes) as $id_demande_link):?>
                                                <?php if($id_demande>0):?>
                                                    <a href="<?=base_url("demande/fiche/$id_demande")?>" target="_blank" class="btn btn-<?php echo $themes->demande->color;?>  btn-sm text-white"><?php echo $themes->demande->icon;?> N°<?=$id_demande_link?></a>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                        <?php endif;?>

                                    </td>


                                   
                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_full_ajax")?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de tickets</h3></div>        
                <?php endif;?>  
            <?php //endif;//autorisation?>  
        </div>        
    </div>
                </div>
</div>    


