
<!--badge bg-amethyst text-decoration-none-->
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<div class="container_pager_ajax">
<div class="row banData">
 
    <div class="col-12">
        <div class="card border-top-<?php echo $themes->document->color;?>">
                <div class="card-header sticky_button bg-light">
                    <form class="form_link_ajax form_with_order" action="<?=base_url()?>/demande/liste_document_ajouter_demande/<?=$id_demande?>">
                        <div class="row">
                            <?php //if($autorisationManager->is_autorise("documents_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbDocuments?> document<?=plurial_s($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button class="btn btn-<?php echo $themes->document->color;?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php //endif;?>

                            <?php //if($autorisationManager->is_autorise("documents_c")):?>                
                            
                            <?php //endif;?>

                        </div>
                    </form>
                </div> 

            <?php //if($autorisationManager->is_autorise("documents_r")):?>                                
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
                            <?php foreach($documents as $document):?>
                                <tr>
                                   

                                    <td>
                                                    Document n°<?php echo $document->id_document?><br>
                                                Uploadé le <?=convert_date_en_to_fr_with_h($document->date_created)?><br>
                                                par <?=$document->user?>

                                    </td>
                                                    
                                    <td width="200px" class="text-center">
                                        <div class="text-center">
                                            <?php if(is_image($document->url_file)):?>
                                                <img width="100px" src="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>" class="" alt="<?=$document->name?>">

                                            <?php else:?>
                                                <i class="fas fa-file fa-3x text-<?php echo $themes->document->color;?>"></i> 
                                            <?php endif;?> 
                                            </div>

                                        <a class="text-<?php echo $themes->document->color;?>" href="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>">
                                                       
                                                <?=($document->name)?>
                                        </a>
                                    </td>

                                    <td class="modif_container">

                                    <?php 
                                            echo view('DocumentUpload\form_document_commentaire', [
                                                    "document" => $document,
                                                  
                                                    
                                                ]);
                                                ?>
                                       
                                    </td>

                                    <td class="modif_container CSelectTypeDocument<?=$document->id_document?> data_id_type_<?=$document->id_document?>">


                                            <?php 
                                            echo view('DocumentUpload\form_document_type', [
                                                    "document" => $document,
                                                    "type_document"=>$type_document,
                                                    
                                                ]);
                                                ?>
  
                                </td>

                                   

                                    <td>
                                        <?php $id_demandes=[];?>
                                        <?php if(!empty($document->id_demandes)):?>

                                           <?php foreach(explode(",",$document->id_demandes) as $id_demande_link):?>
                                                <?php if($id_demande_link>0):?>
                                                    <?php array_push($id_demandes,$id_demande_link);?>
                                                    <a href="<?=base_url("demande/fiche/$id_demande_link")?>" target="_blank" class="btn btn-<?php echo $themes->demande->color;?>  btn-sm text-white mb-1"><?php echo $themes->demande->icon;?> N°<?=$id_demande_link?></a>
                                                <?php endif;?>
                                            <?php endforeach;?>
                                        <?php endif;?>

                                    </td>

                                    <td class="td_set_ajouter_document_demande">
                
                                        <?php if(!in_array($id_demande,$id_demandes)):?>
                                            <button href="<?=base_url()?>/demande/set_ajouter_document_demande/<?=$document->id_document?>/<?=$id_demande?>" class="btn btn-success btn-sm set_ajouter_document_demande">Lier à la demande n°<?=$id_demande?></button>
                                        <?php else:?>
                                            <b>Déjà lié à <?=$id_demande?></b>
                                        <?php endif;?>
                                        <?php //echo affiche_adresse_bien($document->bien_associe)?>

                                    </td>

                                   
                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_full_ajax")?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de documents</h3></div>        
                <?php endif;?>  
            <?php //endif;//autorisation?>  
        </div>        
    </div>
                </div>
</div>    


