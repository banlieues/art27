<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $request = \Config\Services::request();?>

<?php if (!$request->isAJAX())
{
    $this->extend("Layout\index");
    $this->section("body");
}?>



<!--badge bg-amethyst text-decoration-none-->

<a  style="display:none" href="<?=base_url()?>/document" id="reload_page_document">je recharge</a>
<div class="row banData">
<?php if (!$request->isAJAX()):?>
    <div class="container_pager_ajax">
<?php endif;?>
    <div class="col-12">
        <div class="card border-top-<?php echo $themes->document->color;?>">
                <div class="card-header sticky_button bg-light">
                    <form id="form_search_ajax" class="form_with_order <?php if (!$request->isAJAX()):?>form_link_ajax<?php endif?>">
                        <div class="row">
                            <?php //if($autorisationManager->is_autorise("documents_r")):?>
                                <div class="col-lg-auto p-1 align-self-center">
                                    <h5 class='card-title mb-0'><?=$nbDocuments?> document<?=plurial_s($pager->getTotal())?></h5>
                                </div>
                                <div class="col-lg-auto mx-auto p-1 align-self-center"> 
                                    <div class="input-group input-group-navbar text-lg-end">
                                        <input name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" <?php if($itemSearch!==FALSE):?>value="<?=$itemSearch?>" <?php endif;?>>
                                        <button form="form_search_ajax" class="btn btn-<?php echo $themes->document->color;?> text-white btn-sm btn_search" type="submit"><i class="fa fa-search"></i></button>
                                    </div>
                                </div>
                            <?php //endif;?>

                            <?php //if($autorisationManager->is_autorise("documents_c")):?>                
                                <div class="col-lg-auto p-1 align-self-center text-lg-end">
                                    <a target="_blank" class="btn btn-<?php echo $themes->document->color;?> ajouter_document_modal btn-sm mt-1" entity="<?=$entity?>" id_entity="<?=$id_entity?>" href="#">
                                    <?php echo $themes->document->icon;?>  Ajouter document
                                    </a>
                                </div>
                            <?php //endif;?>

                        </div>
                    </form>
                </div> 

            <?php //if($autorisationManager->is_autorise("documents_r")):?>                                
                <?php if ($nbDocuments>0): ?>
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->document->color )?><?php endif;?>

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
                                    <?php //if($autorisationManager->is_autorise("document_d", $document->id_user)):?>
                                    
                                    <td>
                                          
                                        <button text_alert="le document   
                                          
                                        
                                                <?=$document->name?> <?php if(!empty($document->id_demande)):?> lié à la demande n°<?=$document->id_demande?> <?=$document->nom_demande?> <?php endif;?>
                                          " 
                                            id_delete="<?=$document->id_document?>" href="<?=base_url()?>/delete/deleteDocument" class="ban_deleteForm effacer card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> </button>
                                          
                                    </td>
                                    <?php //endif;?> 

                                    <td>
                                                    Document n°<?php echo $document->id_document?><br>
                                                Uploadé le <?=convert_date_en_to_fr_with_h($document->date_created)?>
                                    </td>
                                                    
                                    <td width="200px" class="text-center">
                                        <div class="text-center">
                                            <?php if(is_image($document->url_file)):?>
                                                <img width="100px" src="<?=base_url()?>/<?=PATH_DOCU_URL?><?=$document->url_file;?>" class="" alt="<?=$document->name?>">

                                            <?php else:?>
                                                <i class="fas fa-file fa-3x text-<?php echo $themes->document->color;?>"></i> 
                                            <?php endif;?> 
                                            </div>

                                        <a class="text-<?php echo $themes->document->color;?>" href="<?=base_url()?>/<?=PATH_DOCU_URL?><?=$document->url_file;?>">
                                                       
                                                <?=($document->name)?>
                                        </a>
                                    </td>

                                    <td class="modif_container">

                                    <?php 
                                            echo view($module.'\form_document_commentaire', [
                                                    "document" => $document,
                                                  
                                                    
                                                ]);
                                                ?>
                                       
                                    </td>

                                    <td class="modif_container CSelectTypeDocument<?=$document->id_document?> data_id_type_<?=$document->id_document?>">


                                            <?php 
                                            echo view($module.'\form_document_type', [
                                                    "document" => $document,
                                                    "type_document"=>$type_document,
                                                    
                                                ]);
                                                ?>
  
                                </td>

                                   

                                  

                                   
                                
                                </tr>
                            <?php endforeach;?>
                            </tbody>   
                        </table>
                    </div>   
                    <?php if($pager->getPageCount()>1):?><?=$pager->links("default","bs_".$themes->document->color )?><?php endif;?>
                <?php else:?>
                    <div class="text-center m-5"><h3>Je n'ai pas trouvé de documents</h3></div>        
                <?php endif;?>  
            <?php //endif;//autorisation?>  
        </div>        
    </div>
  
    <?php if (!$request->isAJAX()):?>
    </div>
    <?php else:?>
        <script> 
            $(document).ready(function() 
            {   
                delete_form();
            });
        </script>
<?php endif;?>
</div>    

<?php if (!$request->isAJAX()):?>
    <?php $this->endSection(); ?>

    <?php $this->section("script_foot_injected"); ?>
  

    <?php $this->endSection(); ?>
<?php endif;?>

