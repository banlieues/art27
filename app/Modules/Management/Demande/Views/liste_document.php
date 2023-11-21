<?php if(!empty($documents)):?>

        <div class="row row-cols-2 row-cols-md-4 g-1">
            <?php foreach($documents as $document):?>
                <?php if(is_image($document->url_file)):?>
                <div class="col">
                    <div class="card h-80">
                     
                        <a class="my-lightbox-toggle" data-toggle="lightbox" data-gallery="gallery-images" href="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>">
                        <img src="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>" class="card-img-top" alt="<?=$document->name?>">
                </a>
                        <div class="card-body m-0 p-0">
                            
                        
                        </div>
                        
                    </div>
                </div>
                <?Php endif;?>
            <?php endforeach;?>
        </div>



        <ul class="p-2 mt-2">
            <?php foreach($documents as $document):?>
                <?php if(!is_image($document->url_file)):?>
                    <li class="text-primary"><a  target="_blank" href="<?=base_url()?>/<?=URL_DOCUMENT?><?=$document->url_file;?>"><?=$document->name?></a></li>
                <?php endif;?>
            <?php endforeach;?>    
                </ul>
        <?php else:?>
        
        <div class="row p-1 m-2">
            Pas de documents liés à cette demande
        </div>

       
<?php endif;?>

