<?php $request = \Config\Services::request(); ?>
<?php foreach($indexes as $index):?>
    <?php if(strpos($index, "@#") !== false):?>
        <div class="row container_one_field" name_index="<?=$index?>">
            <div class="col">
                <?php if($typeDataView=="modelisation"):?>
                    <div class="row">
                        <div class="col">
                            <i index="<?=$index?>" style="cursor:pointer" class="<?=icon("minus-field")?> text-danger deleteField"></i>
                        </div>
                        <div class="col-11 col_name_index" name_index="<?=$index?>">
                            <?=str_replace("@#",NULL,$index);?>
                        </div>
                    </div>    
                <?php else:?>    
                    <?=str_replace("@#",NULL,$index);?>
                <?php endif;?>    
            </div>    
            <?php if($typeDataView=="modelisation"):?>
                <div class="col-2">  
                    <i style="cursor:grab" class="<?=icon("moveVertical")?> move-sortable"></i>
                </div>
            <?php endif;?>          
        </div>
    <?php else:?>
        <?php $field_sql=$dataView->getFiedlSql($index); //traduction index if index is not the name field of tablesql ?>
        <div
            <?php if($typeDataView!="modelisation"):?>
                class="tr_fiche_<?php echo $index;?> tr_fiche" index="<?php echo $index;?>"
            <?php endif;?>
            >
            <div class="row mb-2 container_one_field <?php if($typeDataView!="modelisation"):?>formcourscopy<?php endif;?> justify-content-between">                      
                <div class="col align-self-start">               
                    <?php if(isset($fields[$index])):?>
                        <label style='font-weight:bold' class='control-label'>
                            <?php if($dataView->isRequired($fields[$index])):?>*<?php endif;?>
                            <?=$fields[$index]["label"];?>
                        </label>
                    <?php endif;?>      
                </div>
                <?php if($typeDataView=="read")://READ?>
                    <div class="col align-self-end">
                        <?php if(isset($fields[$index])&&!empty($value->$field_sql)):?>
                            <?=$dataView->getValueRead($index,$fields[$index],$value->$field_sql);?>
                        <?php endif;?>
                    </div>
                <?php else://Form ?>
                    <div class="col align-self-end">
                        <?php if($validation->getError($index)&&$typeDataView!="read"): //Error message for form only ?>
                            <span class="text-danger">
                                <i class="<?=icon("triangle_warning")?>"></i>
                                <?= $error = $validation->getError($index); ?>
                            </span>
                        <?php endif; ?>
                        <?php
                            //verifiy if inout value has set after a submit.
                            if($request->getVar($index)): //value of form after submit 
                                $valueIndex=$request->getVar($index); 
                            else: 
                            
                                //if not getVar from submit then value in database (if case update) or Null(if case insert)
                                if(isset($value->$field_sql))://value of form before submit
                                    $valueIndex=$value->$field_sql; 
                                else:
                                    $valueIndex=NULL; 
                                endif;    
                            endif;

                            //Treatement of checkbox, if none checkbox (for a index of type checkox) checked, the input does not exist then $valueIndex = NULL
                            if(!$request->getVar($index)&&isset($mode_error_on)&&$mode_error_on):
                                $valueIndex=NULL; 
                            endif;
                        ?>
                        <?php if(isset($fields[$index])):?>
                            <?=$dataView->getElementForm($index,$fields[$index],$valueIndex);?>
                        <?php endif;?>
                        <?php /* Input with list of index of form, to use for verification of checkbox empty */ ?>
                        <input name="indexesForm[]" type="hidden" value="<?=$index?>">
                    </div>
                <?php endif;?>
            </div>
        </div>
    <?php endif; ?>
<?php endforeach;?>