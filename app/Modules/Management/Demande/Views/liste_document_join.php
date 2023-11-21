<?php if(!empty($documents)):?>

        <div class="row row-cols-1 row-cols-md-4 g-1">
            <?php foreach($documents as $document):?>
                <?php if(is_image($document->url_file)):?>
                <div class="col">
                    <div class="card h-80">
                        <div class="card-header text-center p-0">
                            <small class="text-body-secondary"><input <?php if(in_array($document->id,$id_document_deja_join)):?>checked<?php endif;?> name="id_document[]" value="<?=$document->id?>" class="select_document_for_action" type="checkbox"></small>
                        </div>
                        <a class="my-lightbox-toggle-join" data-toggle="lightbox" data-gallery="gallery-images-join" href="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>">
                        <img src="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>" class="card-img-top" alt="<?=$document->name?>">
                </a>
                        <div class="card-body m-0 p-0">
                            
                        
                        </div>
                        
                    </div>
                </div>
                <?Php endif;?>
            <?php endforeach;?>
        </div>



        <div class="list-group-flush mt-2">
            <?php foreach($documents as $document):?>
                <?php if(!is_image($document->url_file)):?>
                    <li class="list-group-item list-group-item-action text-primary"><input <?php if(in_array($document->id,$id_document_deja_join)):?>checked<?php endif;?> type="checkbox" name="id_document[]" value="<?=$document->id?>">  <a  target="_blank" href="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>"><?=$document->name?></a></li>
                <?php endif;?>
            <?php endforeach;?>    
        </div>
        <?php else:?>
        
        <div class="row p-1 m-2">
            Pas de documents liés à cette demande
        </div>

       
<?php endif;?>

