<?php $request = \Config\Services::request(); ?>

<?php foreach($indexes as $index):?>
    <?php if(strpos($index, "@#") !== false):?>
        <div class="row container_one_field" name_index="<?=$index?>">
            <div class="col">
                <div class="row">
                    <div class="col">
                        <i index="<?=$index?>" style="cursor:pointer" class="<?=icon("minus-field")?> text-danger deleteField"></i>
                    </div>
                    <div class="col-11 col_name_index" name_index="<?=$index?>">
                        <?=str_replace("@#",NULL,$index);?>
                    </div>
                </div>       
            </div>    
            <div class="col-2">  
                <i style="cursor:grab" class="<?=icon("moveVertical")?> move-sortable"></i>
            </div>
        </div>
    
    <?php else:?>
       
        <?php $field_sql=$dataView->getFiedlSql($index); //traduction index if index is not the name field of tablesql ?>
        <div class="tr_fiche_<?php echo $index;?> tr_fiche" index="<?php echo $index;?>">
            <div class="row mb-2 container_one_field">                      
                <div class="col-6">
                    <div class="row">
                        <?php if(isset($fields[$index])):?>
                            <div class="col">
                                <i index="<?=$index?>" style="cursor:pointer" class="<?=icon("minus-field")?> text-danger deleteField"></i>
                            </div>
                            <div class="col-10">
                                <b>
                                    <?php if($dataView->isRequired($fields[$index])):?>*<?php endif;?>
                                    <?=$fields[$index]["label"];?>
                                </b>
                            </div>
                        <?php endif;?>
                    </div>       
                </div>
               
                <?php if(isset($fields[$index])):?>
                    <div class="col-4 col_name_index" name_index="<?=$index?>">
                        <input class="form-control" readonly value="<?php echo $index;?>">
                    </div>
                    <div class="col-2">
                        <i style="cursor:grab" class="<?=icon("moveVertical")?> move-sortable"></i>
                    </div>   
                <?php endif;?>
            </div>
        </div>
    <?php endif; ?>
    
<?php endforeach;?>
