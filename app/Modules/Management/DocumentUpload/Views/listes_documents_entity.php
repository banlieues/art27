<?php if(!empty($documents)):?>

        <div class="row row-cols-2 row-cols-md-4 g-1">
            <?php foreach($documents as $document):?>
                <?php if(is_image($document->url_file)):?>
                <div class="col">
                    <div class="card h-50">
                     
                        <a class="my-lightbox-toggle" data-toggle="lightbox" data-gallery="gallery-images" href="<?=base_url()?><?=PATH_DOCU_URL?><?=$document->url_file;?>">
                        <img src="<?=base_url()?><?=PATH_DOCU_URL?><?=$document->url_file;?>" class="card-img-top" alt="<?=$document->name?>">
                        </a>
                        <div class="card-body m-0 p-0">
                            
                        
                        </div>
                        
                    </div>
                </div>
                <?Php endif;?>
            <?php endforeach;?>
        </div>

            <table class="table table-bordered mt-2">
                <tbody>
                    <?php foreach($documents as $document):?>
                        <tr>
                            
                            <td>
                            <i class="fas fa-file fa-1x text-<?php echo $themes->document->color;?>"></i> <a class="text-dark" target="_blank" href="<?=base_url()?><?=PATH_DOCU_URL?><?=$document->url_file;?>"><?=$document->name?></a>

                            </td>
                            <td>
                            <?=$document->id_type?>
                            </td>
                            <td>
                                <?=$document->commentaire?>
                            </td>
                        </tr>

                    <?php endforeach;?>
                </tbody>
            </table>

        <?php else:?>
        
        <div class="row p-1 m-2">
            <div class="text-center">Pas de documents liés</div>
        </div>

       
<?php endif;?>

