<?php $request = \Config\Services::request(); ?>
<?php if(!empty($validation->getErrors())): $mode_error_on=TRUE; else: $mode_error_on=FALSE; endif; ?>

<?php if(!empty($modules)):?>
    <?php //debug($request->getVar());?>
    <input type="hidden" name="has_modules" value="1">

    <div class="row mb-4 mt-2">
        <div class="col">
            <div class="mb-2"><?=nl2br($activity->text_presentation)?></div>
        <p><b>*Sélectionner un ou plusieurs modules</b>
            <?php if(!$request->getVar("id_modules_5678")&&$request->getVar("has_modules")):?>
            <br><span class="text-danger"><i class="fas fa-exclamation-triangle"></i> Vous devez sélectionner au moins un module.</span> 
            <?php endif;?>
        </p>
        <?php foreach($modules as $module):?>
            <div class="form-check mb-2"> 
                <input <?php if($request->getVar("id_modules_5678")&&in_array($module->id_activity,$request->getVar("id_modules_5678"))):?> checked <?php endif;?> class="form-check-input" name="id_modules_5678[]" type="checkbox" value="<?=$module->id_activity?>" id="module<?=$module->idact?>">
                <label class="form-check-label" for="module<?=$module->idact?>">
                    <?=$module->titre?>
                    <?php if(!empty($module->date_debut)||!empty($module->date_debut)):?>
                         <small>du <?=convert_date_en_to_fr_with_h($module->date_debut,FALSE)?> au <?=convert_date_en_to_fr_with_h($module->date_fin,FALSE)?></small>
                   <?php endif;?>
  

                   
    
                </label>
            </div>  
        <?php endforeach;?> 
        </div>
    </div> 
<?php endif;?>    


<?php foreach($indexes as $index):?>
    <?php if(strpos($index, "@#") !== false):?>
       
        <div class="row container_one_field" name_index="<?=$index?>">
            <div class="col">
            <?php if($typeDataView=="modelisation"):?>
                <div class="row mb-2">
                    <div class="col"><i index="<?=$index?>" style="cursor:pointer" class="<?=icon("minus-field")?> text-danger deleteField"></i></div>
                    <div class="col-11 col_name_index" name_index="<?=$index?>"><?=str_replace("@#",NULL,$index);?></div>
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
        
        <div class="row mb-2 container_one_field mb-4">
            <div class="col-12">
                <?php if($typeDataView=="modelisation"):?>
                    <div class="row">
                        <div class="col"><i index="<?=$index?>" style="cursor:pointer" class="<?=icon("minus-field")?> text-danger deleteField"></i>
                        <b><?=$fields[$index]["label"];?> <?php if($dataView->isRequired($fields[$index])):?>* <?php endif;?> </b></div>
                    </div>
                <?php else:?>    
                    <b><?=$fields[$index]["label"];?> <?php if($dataView->isRequired($fields[$index])):?>* <?php endif;?> </b>
                <?php endif;?>        
            </div>
           
                <?php if($typeDataView=="read")://READ?>
                    <div class="col">
                        <?=$dataView->getValueRead($index,$fields[$index],$value->$field_sql);?>
                    </div>
                <?php elseif($typeDataView=="modelisation"):?>
                    <div class="col-10 col_name_index" name_index="<?=$index?>">
                        <input class="form-control" readonly value="<?php echo $index;?>">
                    </div> 
                    
                     <div class="col-2">
                        <i style="cursor:grab" class="<?=icon("moveVertical")?> move-sortable"></i>
                    </div>   
                   
                <?php else://Form ?>
                    <div class="col">
                            <?php if($validation->getError($index)&&$typeDataView!="read"): //Error message for form only ?>
                                <div class="text-danger">
                                    <i class="<?=icon("triangle_warning")?>"></i> <?= $error = $validation->getError($index); ?>
                                </div>
                
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
                                if(!$request->getVar($index)&&$mode_error_on):
                                    $valueIndex=NULL; 
                                endif;  
                            
                            ?>
                            <?=$dataView->getElementForm($index,$fields[$index],$valueIndex);?>
                            <?php /* Input with list of index of form, to use for verification of checkbox empty */ ?>
                            <input name="indexesForm[]" type="hidden" value="<?=$index?>">
                    </div>
                <?php endif;?>
                          
               
           
        </div>
    <?php endif; ?>   
     
<?php endforeach;?>

<?php if(isset($value_filtre)&&$value_filtre&&isset($has_spip_filtre)&&$has_spip_filtre==1):?>
    <?php if(!empty($filtres_spip)):?>
        <hr>
        <h4> Mots-clés Spip</h4>
        <p><i>A l'intérieur d'un groupe de mots-clés, le système va rechercher selon le critière "OU". Entre groupe de mots-clés différents, le système va rechercher selon le critère "ET"  </i></p>
        <?php foreach($filtres_spip as $key=>$filtre):?>
            <div class="row mb-2 container_one_field mb-4">
                <div class="col-12"><b><?=supprimer_numero($key);?></b></div>

                <div class="col-12">
                    
                    <select name="filtre_spip[]" multiple class="dselect">
                            <option value="">Choisir</option>
                            <?php  if(!empty($filtre)):?>
                                    <?php foreach($filtre as $value=>$label):?>

                                       
                                        <option <?php if(in_array($value,$filtre_spip_value)):?> selected <?php endif;?> value="<?=$value?>"><?=supprimer_numero($label)?></option>
                                       

                                    <?php endforeach;?>    

                            <?Php endif;?>    
                    </select>
                </div>
            </div> 
        <?php endforeach;?> 
    <?php endif;?>          
<?php endif;?>  