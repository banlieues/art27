<?php if(count($documents)>0):?>
        <div class="card-header">
            
        <?php $num_aleatoire=rand(1,1500).$documents[0]->id.rand(1,300); ?>
        <span class="badge bg-dark"><?=count($documents);?></span>  <a data-bs-toggle="collapse" href="#collapseOneMessage<?=$num_aleatoire?>" role="button" aria-expanded="false" aria-controls="collapseOneMessage<?=$num_aleatoire?>">
                <small>documents joints</span></small>
            </a>
        
            <div class="collapse" id="collapseOneMessage<?=$num_aleatoire?>">
            <div class="card card-body">

                <ul>
                    <?php foreach($documents as $document):?>
                        
                            <li style="font-size:12px !important" class="text-primary"><a  target="_blank" href="<?=base_url()?><?=URL_DOCUMENT?><?=$document->url_file;?>"><?=$document->name?></a></li>
                        
                    <?php endforeach;?>    
                </ul>


            </div>
            </div>
        </div>
<?php endif;?>