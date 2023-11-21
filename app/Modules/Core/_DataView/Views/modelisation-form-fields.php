<?php $request = \Config\Services::request(); ?>
<?php $validation = \Config\Services::validation(); ?>
<?php 
    if($request->getVar()): $getVar=$request->getVar(); endif;
?>
<?php $this->extend('Layout\index'); ?>
<?php $this->section("body"); ?>
<?php if(!empty($validation->getErrors())):?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">    
        <?php echo str_replace(array("<ul>","<li>","</li>","</ul>"),array(NULL,iconNotificationError().' ',"<br>",NULL),$validation->listErrors()); ?>
    </div>  
<?php endif;?>
<form action="<?=base_url()?>/modelisation/saveField" method="post">
    
    <?php if(session("notification")):?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="<?=icon("success-notification")?>"></i>  <?=session()->getFlashdata("notification")?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;?>
    <?php if(session("error")):?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?=session()->getFlashdata("error")?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;?>
  
<!--block title -->
    <div class="card-header p-1 p-xl-1 sticky_button bg-light">
        <div class="row">
            <div class="col-auto align-self-center">
                <h3 class="fs-4"><?php echo $titleView; ?></h3>
            </div>
            <div class="col align-self-center">
                <div class="text-end">
                <span class="zone-button-form">
                        <button type="submit" class="btn btn-success"><i class="<?=icon("modelisation")?>"></i> Enregistrer</button>
                    </span>   
            
                    <span class="zone-button-form">
                        <a href="<?=base_url()?>/modelisation/list_fields/<?=$entities->type?>" class="btn btn-danger"><i class="<?=icon("modelisation")?>"></i> Annuler </a>
                    </span> 
                </div>  
            </div>      
        </div>
    </div>


    <div class="card border-top-navy mb-4 ">
        <div class="card-header">
            <?php if($mode=="update"):?>
                Edition du champ
            <?php else:?>
                Nouveau champ
            <?php endif;?>   
        </div>
        <div class="card-body">
            <input type="hidden" name="mode" value="<?=$mode?>">

            <?php //Quel est l'index? ?>
            <div class="row mb-3">
                <label for="inputindex" class="col-sm-2 col-form-label"><b>Index</b></label>
                <div class="col-sm-10">

                    <?php if($mode=="update"):?>
                        <input readonly name="field_index" type="text" class="form-control-plaintext" id="inputindex" value="<?=$field->field_index?>">
                    <?php endif;?>

                    <?php if($mode=="insert"):?>
                        <input <?php if(isset($getVar["field_index"])):?> value="<?=$getVar["field_index"]?>" <?php endif;?> name="field_index" type="text" class="form-control" id="inputindex">
                    <?php endif;?>

                </div>
            </div>

            <?php //Quel est lentité du champ? ?>
            <div class="row mb-3">
                <label for="type" class="col-sm-2 col-form-label"><b>Fait partie de l'entité</b></label>
                <div class="col-sm-10">
                    
                        <input type="hidden" value="<?=$entity?>" name="entity">
                        <input readonly type="text" class="form-control-plaintext" id="" value="<?=ucfirst($entities->label)?>">
                        <input name="type" type="hidden" id="type" value="<?=$entities->type?>">
                   
                    <?php //if($mode=="insert"):?>
                        <!-- <select name="type" type="text" class="form-control-plaintext" id="type">
                        <option value="0">Choisir une entité</option>
                            <option value="contact">Contact</option> 
                        <option value="activities">Activité</option>
                        <option value="inscriptions">Inscription</option>
                        </select> -->
                    <?php //endif;?>
                </div>
            </div>

            <?php //Affichage label ?>
            <div class="row mb-3">
                <label for="label" class="col-sm-2 col-form-label"><b>Label</b></label>
                <div class="col-sm-10">
                    <?php 
                        if(isset($getVar["label"])): 
                            $value_label=$getVar["label"]; 
                        elseif(isset($field->label)):
                            $value_label=$field->label;
                        else:
                            $value_label=NULL;
                        endif;
                    ?>    
                    <input name="label" type="text" class="form-control" id="label" value="<?=$value_label?>">
                </div>
            </div>

            <?php //Affichage table sql et champ sql dans le cas d'update à titre d'information ?>
            <?php if($mode=="update"):?>
                <div class="row mb-3">
                    <label for="table" class="col-sm-2 col-form-label"><b>Table SQL</b></label>
                    <div class="col-sm-10">
                        <?php if($mode=="update"):?>
                            <input readonly name="table" type="text" class="form-control-plaintext" id="table" value="<?=$field->table?>">
                        <?php endif;?>
                        <?php if($mode=="insert"):?>
                            <!-- <input name="field_index" type="text" class="form-control-plaintext" id="inputindex"> -->
                        <?php endif;?>
                    </div>
                </div>
                <div class="row mb-3">
                    <label for="field_sql" class="col-sm-2 col-form-label"><b>Champ SQL</b></label>
                    <div class="col-sm-10">
                        <?php if($mode=="update"):?>
                            <input readonly name="field_sql" type="text" class="form-control-plaintext" id="field_sql" value="<?=$field->field_sql?>">
                        <?php endif;?>
                        <?php if($mode=="insert"):?>
                            <!-- <input name="field_index" type="text" class="form-control-plaintext" id="inputindex"> -->
                        <?php endif;?>
                    </div>
                </div>
            <?php endif;?>

            <?php //type de champ ?>
            <div class="row mb-3">
                <label for="type_field" class="col-sm-2 col-form-label"><b>Type de champ</b></label>
                <div class="col-sm-10">
                <?php if($mode=="update"):?>
                        <?php echo $dataGeneratorModel->getLabelTypeField($field->type_field);?> 
                        <input name="type_field" type="hidden" id="type_field" value="<?=$field->type_field?>">

                <?php else:?>
                    <select id="type_field" name="type_field">
                       <?php foreach($typeFields as $typeField):?>
                            <option <?php if(isset($getVar["type_field"])&&$getVar["type_field"]==$typeField->ref):?> selected <?php endif;?> value="<?=$typeField->ref?>"><?=$typeField->label?></option>
                        <?php endforeach;?>
                    </select>  
                <?php endif;?>    
                </div>
            </div>

            <?php //Champ obligatoire ?>
            <div class="row mb-3">
                <label for="rule_required" class="col-sm-2 col-form-label"><b>Obligatoire</b></label>
                <div class="col-sm-10">
                <div class="form-check">
                    <?php 
                        if(isset($getVar["mode"])&&isset($getVar["rule_required"])&&$getVar["rule_required"]==1): 
                            $is_checked=TRUE; 
                        elseif(!isset($getVar["mode"])&&isset($field->rule)&& strstr($field->rule,"required")):
                            $is_checked=TRUE;
                        else:
                            $is_checked=FALSE;
                        endif;
                    ?>   
                    <input <?php if($is_checked):?>checked<?php endif;?> name="rule_required" class="form-check-input" type="checkbox" name="gridRadios" id="rule_required" value="1">
                    <label class="form-check-label" for="rule_required">
                        Oui
                    </label>
                </div>
                </div>
            </div>

        </div>
    </div>  
<?php $num_item_list=0;?>
<?php 
    if(
            $mode=="insert"//le container de la liste liée existe toujours en insert
        ||
        (
            $mode=="update"//le container n'existe que si le type field est ok en mode
                &&isset($field->type_field)
                &&($field->type_field=="radio"||$field->type_field=="check"||$field->type_field=="select")
        )
        ):
        ?>

        <?php //determiner si liste liée est visibile ou pas
            if  
            (
                (
                    //On n'affiche pas liste liée si on doit réafficher le formulaire s'il y a une erreur, 
                    $mode=="insert"
                        &&isset($getVar["type_field"])&&!in_array($getVar["type_field"],["check","select","radio"])
                        
                )       
                ||
                (
                    //On n'affiche pas la liste liée si on est en mode insert et que la formulaire n'a pas été soumis une première fois
                    $mode=="insert"
                        &&!isset($getVar["type_field"])
                ) 
            )
            {
                $is_display=FALSE;
            }
            else //Dans tous les autres cas où affiche
            {
                $is_display=TRUE;
            }


        ?>
    <div <?php if(!$is_display):?> style="display:none" <?php endif;?> class="card border-top-navy mb-4 c_list">
        <div class="card-header">
            Liste liée
        </div>
        <div class="card-body container_list_item">
            <table class="table table-striped table-hover my-0 table-sm table-bordered">
            <?php if($mode=="update"): //thead différent selon que l'on est en insert ou update?>
                <input type="hidden" value="<?=$field->table_list?>" name="table_list">
                
                    <thead>
                        <tr>
                            <?php $include=["label","ref"];?>
                            <th>#</th>
                                <?php if(!empty($lists)):?>
                                    <?php foreach($lists[0] as $label=>$value):?>
                                        <?php if(in_array($label,$include)):?>
                                        
                                            <th>
                                                <?php if($label!="is_actif"):?>
                                                    <?=$label?>
                                                <?php endif;?>
                                            </th>
                                        <?php endif;?>
                                    <?php endforeach;?> 
                                <?php else:?>
                                    <th>Label</th>
                                <?php endif;?>    
                            <th></th>
                            <th></th>
                        </tr> 
                    </thead>
                <?php else://case insert?>  
                    <input type="hidden" value="none" name="table_list"> 
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Label</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                <?php endif;?>
                    <?php
                        //Cas où il y une erreur après soumission et qu'il faut réafficher les champs
                    ?>  
                    <?php if($request->getVar("num_item_list")):?>
                    
                    <tbody class="container_list_tr sortable-item-list">  
                        <?php $fields_possible=[];?>
                        <?php for($num_item_list=1;$num_item_list<=$request->getVar("num_item_list");$num_item_list++):?>
                            <tr style="width:100% tr_item">
                                <td>
                                    <?php if($getVar["id_item_##$num_item_list"]==0):?>
                                        <button class="cancel_item btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                    <?php endif;?>
                                    #<span class="num_item_list"><?=$num_item_list?></span>
                                    <input class="id_item" type="hidden" name="id_item_##<?=$num_item_list?>" value="<?=$getVar["id_item_##$num_item_list"]?>">
                                </td>
                                <?php if(isset($getVar["label_item_##$num_item_list"])):?>
                                    <td>  
                                        <input name="label_item_##<?=$num_item_list?>" type="text" class="form form-control label_item" value="<?=$getVar["label_item_##$num_item_list"]?>">
                                        <?php array_push($fields_possible,"label");?>
                                    </td>
                                <?php endif;?>        
                                <?php if(isset($getVar["ref_item_##$num_item_list"])):?>
                                    <td>  
                                    <?php if($getVar["id_item_##$num_item_list"]!=0):?>
                                        <?=$getVar["ref_item_##$num_item_list"]?>
                                    <?php endif;?>    
                                        <input name="ref_item_##<?=$num_item_list?>" type="<?php if($getVar["id_item_##$num_item_list"]==0):?>text<?php else:?>hidden<?php endif;?>" class="form form-control ref_item" value="<?=$getVar["ref_item_##$num_item_list"];?>">
                                        <?php array_push($fields_possible,"ref");?>
                                    </td>    
                                <?php endif;?>        
                                <td>
             
                                    <input name="is_actif_item_##<?=$num_item_list?>"  class="is_actif_item" <?php if(isset($getVar["is_actif_item_##$num_item_list"])&&$getVar["is_actif_item_##$num_item_list"]==0):?>checked<?php endif;?> type="checkbox" value="0"> Désactivé
                                </td>
                                <td>
                                    <i style="cursor:grab" class="far fa-hand-rock move_sortable_item_list"></i>
                                </td>    
                            </tr>
                        <?php endfor;?>  
                        <?php $num_item_list=$num_item_list-1;?>   
                    </tbody>
                    <?php
                        //Cas où je crée un nouveau champ (insert) mais que je n'ai pas encore soumis le formulaire
                        //Par défaut, les champs sont id, label, is_actif
                    ?>
                    <?php elseif($mode=="insert"&&!isset($getVar["num_item_list"])): ?> 
                    
                    <?php $fields_possible=["label"]; $num_item_list=1;?>
                    <tbody class="container_list_tr sortable-item-list">  
                        <tr style="width:100% tr_item">
                            <td>
                            <button class="cancel_item btn btn-danger btn-sm"><i class="fas fa-times"></i></button>
                                #<span class="num_item_list"><?=$num_item_list;?></span>
                                <input class="id_item" name="id_item_##1" type="hidden" value="0">
                            </td>
                            <?php foreach($fields_possible as $fp):?>
                                <td>
                                    <input type="text" name="label_item_##1" class="form form-control <?=$fp?>_item" value="">
                                </td>
                            <?php endforeach;?>
                            <td>
                                <input class="is_actif_item" name="is_actif_item_##1" type="checkbox" value="0"> Désactivé
                            </td>
                            <td>
                                <i style="cursor:grab" class="far fa-hand-rock move_sortable_item_list"></i>
                            </td>
                        </tr>
                    </tbody>
                    <?php
                        //Cas où j'update un champ mais que je n'ai pas encore soumis le formulaire
                        //Par défaut, les champs sont id, label, is_actif
                    ?>            
                     <?php else: //body qui s'affiche dans le cas d'un update sans avoir été soumis auparavant ?>   

                    <tbody class="container_list_tr sortable-item-list">
                        <?php $fields_possible=[];?>
                        <?php foreach($lists as $list):?>
                            <tr style="width:100% tr_item">
                                <?php $num_item_list=$num_item_list+1;?> 
                                <td>
                                    #<span class="num_item_list"><?=$num_item_list?></span>
                                    <input class="id_item" type="hidden" name="id_item_##<?=$num_item_list?>" value="<?=$list->id?>">
                                </td>
                                <?php foreach($list as $field=>$value):?>
                                        <?php if($field=="label"):?>
                                        <td>  

                                            <input name="label_item_##<?=$num_item_list?>" type="text" class="form form-control label_item" value="<?=$value;?>">
                                            <?php array_push($fields_possible,$field);?>
                                        </td>
                                        <?php elseif($field=="ref"):?>
                                        <td>  
                                            <?=$value?>
                                            <input name="ref_item_##<?=$num_item_list?>" type="hidden" class="form form-control ref_item" value="<?=$value;?>">
                                            <?php array_push($fields_possible,$field);?>
                                        </td>    
                                        <?php elseif($field=="is_actif"):?> 
                                        <td>        
                                            <input name="is_actif_item_##<?=$num_item_list?>"  class="is_actif_item" <?php if($value!=1):?>checked<?php endif;?> type="checkbox" value="0"> Désactivé
                                        </td>
                                        <?php endif;?>      
                                <?php endforeach;?>  
                              
                                <td>
                                    <i style="cursor:grab" class="far fa-hand-rock move_sortable_item_list"></i>
                                </td>
                            </tr>   
                        <?php endforeach;?>  
                    </tbody>

                    <?php endif;?>
                    <?php 
                        if(!empty($fields_possible)):
                            $fields_possible=array_unique($fields_possible);//Pour traiter les cas où il existe en plus de champs des REF
                        else:
                            $fields_possible=["label"]; //cas insert   
                        endif;    
                    ?>      
                    <?php
                        //Il s'agit de la ligne tr qui est clôner lorsqu'on ajoute une nouveau champ
                    ?>                          
                    <tr style="display:none" class="clone_tr">
                        <td>
                        <button class="cancel_item btn btn-danger btn-sm"><i class="fas fa-times"></i></button>

                            #<span class="num_item_list"></span>
                            <input class="id_item" type="hidden" value="0">
                        </td>
                        <?php foreach($fields_possible as $fp):?>
                            <td>
                                <input type="text" class="form form-control <?=$fp?>_item" value="">
                            </td>
                        <?php endforeach;?>
                        <td>
                            <input class="is_actif_item" type="checkbox" value="0"> Désactivé
                        </td>
                        <td>
                            <i style="cursor:grab" class="far fa-hand-rock move_sortable_item_list"></i>
                        </td>
                    </tr>
                </table>
                <div style="text-align:right">
                    <button class="add_item_in_list">Ajouter un item dans la liste</button>
                </div>
              
        </div>
    </div> 
<?php endif;?>       
<input id="number_line" class="num_item_list_input" type="hidden" name="num_item_list" value="<?=$num_item_list?>">



</form>

<?php $this->endSection(); ?>

