<?php if(!isset($itemSearch)): $itemSearch=NULL; endif;?>
    <form class="form_link_ajax mb-4" action="<?=base_url()?>/doublon/search_by_id/<?=$entity?>">
        <div class="input-group input-group-navbar">
                <input autocomplete="off" name="itemSearch" type="text" class="form-control" placeholder="Rechercher…" aria-label="Rechercher" value="<?=$itemSearch?>">
                <button class="btn btn-dark text-white btn_search" type="submit"><i class="fa fa-search"></i></button>
        </div>

        <?php if(isset($itemSearch)&&!empty($itemSearch)):?>
                <div class="mt-3"><b><?=$nbResult?></b> fiche<?=plurial_s($nbResult); ?> trouvée<?=plurial_s($nbResult); ?></div>

                <?php if($nbResult>0):?>
                    <br><i>Cliquer sur le checkbox des fiches que vous voulez fusionner!</i>
                    <div class="row row-cols-1 row-cols-md-2 g-4 mb-3">
                            <?php foreach($fiches as $fiche):?>

                                <div class="col">
                                    <div class="card h-100 card_select_fusion">
                                        <div class="card-body">
                                            <p class="card-text">
                                               <input class="select_fusion" value="<?=$fiche->$idSearch?>" name="id_doublons[]" type="checkbox"> 
                                               <?php 
                                                    foreach($fieldsSearch as $field)
                                                    {
                                                        if(is_array($field))
                                                        {
                                                            $is_find=FALSE;
                                                            foreach($field as $subField)
                                                            {
                                                                if(!empty($fiche->$subField))
                                                                {
                                                                    echo $fiche->$subField;
                                                                    echo ' ';
                                                                    $is_find=TRUE;
                                                                }
                                                            }
                                                            if($is_find)
                                                                echo "<br>";
                                                        }
                                                        else
                                                        {
                                                            if(!empty($fiche->$field))
                                                            {
                                                                echo $fiche->$field;
                                                                echo "<br>";
                                                            }
                                                        }

                                                    }
                                                ?>
                                                  
                                            </p>
                                        </div>

                                    </div>
                                </div> 

                            <?php endforeach;?> 
                    </div>   

                <?php endif;?>    


                <?php if($pager->getPageCount()>1):?>
                    <?php 
                        $pager->setPath("doublon/search_by_id/$entity");
                        echo $pager->links("default","bs_full_ajax");
                    ?>
                    
                      
                <?php endif;?>
   

        <?php endif;?>    

    </form>
