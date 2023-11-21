<?php $this->extend('Layout\index'); ?>

<?php $this->section('script_foot_injected');?>
    <?php echo view('Modelisation\js_modelisation');?>
<?php $this->endSection();?>

<?php $this->section('navbarsub');?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->modelisation->color;?> border-2">
        <div>
            <?php echo $titleView;?>
        </div>
        <div class="d-flex">
            <a role="button" class="btn btn-sm btn-secondary ms-2" 
                href="<?php echo current_url();?>"
                >
                <i class="<?=icon("cancel")?>"></i> Annuler
            </a>
            <button type="submit" form="FieldForm" class="btn btn-sm btn-<?php echo $themes->modelisation->color;?> ms-2">
                <?php echo fontawesome('save');?> Enregistrer
            </button>
            <a role="button" class="btn btn-sm btn-<?php echo $themes->modelisation->color;?> ms-2" 
                href="<?php echo base_url("modelisation/$entity->type/fields");?>"
                title="Aller à la liste des champs associés à cette entité"
                >
                <?php echo fontawesome('long-arrow-alt-right')?> Aller à la liste des champs associés
            </a>
            <a role="button" class="btn btn-sm btn-<?php echo $themes->modelisation->color;?> ms-2" 
                href="<?php echo base_url("modelisation/$entity->type/fields");?>"
                title="Retourner à la liste des champs de l'entité"
                >
                <?php echo fontawesome('turn-up')?> Retourner à la liste des champs
            </a>
        </div>
    </div>
<?php $this->endSection();?>

<?php $this->section("body"); ?>

<?php if($request->getVar()): $getVar=$request->getVar(); endif;?>
<form id="FieldForm" action="<?php echo current_url();?>" method="post" class="container mt-2">
    <div class="row justify-content-center">
        <div class="col-auto">
            <div class="card">
                <div class="card-header">
                    Métadonnées
                </div>
                <div class="card-body">
                    <div class="row mb-1">
                        <label for="inputindex" class="col-sm-4 col-form-label">
                            <b>Index</b>
                        </label>
                        <div class="col-sm-8">
                            <input type="text"
                                name="field_index"
                                class="form-control<?php if($mode=="update"):?>-plaintext<?php endif;?>"
                                id="inputindex"
                                <?php if($mode=="update"):?>
                                    value="<?=$field->field_index?>"
                                    readonly
                                <?php elseif(isset($getVar["field_index"])):?>
                                    value="<?=$getVar["field_index"]?>"
                                <?php endif;?>
                            />
                        </div>
                    </div>

                    <div class="row mb-1">
                        <label for="type" class="col-sm-4 col-form-label">
                            <b>Fait partie de l'entité</b>
                        </label>
                        <div class="col-sm-8">
                            <input type="hidden" value="<?=$entity->type?>" name="entity"/>
                            <input type="text" readonly class="form-control-plaintext" value="<?=ucfirst($entity->label)?>"/>
                            <input type="hidden" name="type" id="type" value="<?=$entity->type?>"/>
                        </div>
                    </div>

                    <div class="row mb-1">
                        <label for="label" class="col-sm-4 col-form-label">
                            <b>Label</b>
                        </label>
                        <div class="col-sm-8">
                            <input type="text"
                                name="label"
                                class="form-control"
                                id="label"
                                <?php if(isset($getVar["label"])):?>
                                    value="<?php echo $getVar["label"];?>" 
                                <?php elseif(isset($field->label)):?>
                                    value="<?php echo $field->label;?>"
                                <?php endif;?>
                            />
                        </div>
                    </div>

                    <!-- Affichage table sql et champ sql dans le cas d'update à titre d'information -->
                    <?php if($mode=="update"):?>
                        <div class="row mb-1">
                            <label for="table" class="col-sm-4 col-form-label">
                                <b>Table SQL</b>
                            </label>
                            <div class="col-sm-8">
                                <input readonly name="table" type="text" class="form-control-plaintext" id="table" value="<?=$field->table?>"/>
                            </div>
                        </div>
                        <div class="row mb-1">
                            <label for="field_sql" class="col-sm-4 col-form-label">
                                <b>Champ SQL</b>
                            </label>
                            <div class="col-sm-8">
                                <input readonly name="field_sql" type="text" class="form-control-plaintext" id="field_sql" value="<?=$field->field_sql?>"/>
                            </div>
                        </div>
                    <?php endif;?>

                    <!-- type de champ -->
                    <div class="row mb-1">
                        <label for="type_field" class="col-sm-4 col-form-label">
                            <b>Type de champ</b>
                        </label>
                        <div class="col-sm-8">
                            <?php if($mode=="update"):?>
                                <div class="form-control-plaintext"> <?php echo $DataViewModel->getLabelTypeField($field->type_field);?> </div>
                                <input name="type_field" type="hidden" id="type_field" value="<?=$field->type_field?>">
                            <?php else:?>
                                <select id="type_field" name="type_field">
                                <?php foreach($typeFields as $typeField):?>
                                        <option value="<?=$typeField->ref?>"
                                            <?php if(isset($getVar["type_field"] )&& $getVar["type_field"]==$typeField->ref):?> selected <?php endif;?>
                                            >
                                            <?=$typeField->label?>
                                        </option>
                                    <?php endforeach;?>
                                </select>  
                            <?php endif;?>    
                        </div>
                    </div>

                    <!-- Champ obligatoire -->
                    <div class="row mb-1">
                        <label for="rule_required" class="col-sm-4 col-form-label pt-0">
                            <b>Obligatoire</b>
                        </label>
                        <div class="col-sm-8">
                            <div class="form-check">
                                <?php if(isset($getVar["mode"])&&isset($getVar["rule_required"])&&$getVar["rule_required"]==1): 
                                    $is_checked=TRUE; 
                                elseif(!isset($getVar["mode"])&&isset($field->rule)&& strstr($field->rule,"required")):
                                    $is_checked=TRUE;
                                else:
                                    $is_checked=FALSE;
                                endif;?>   
                                <input type="checkbox"
                                    <?php if($is_checked):?> checked <?php endif;?>
                                    name="rule_required"
                                    class="form-check-input"
                                    id="rule_required"
                                    value="1"
                                />
                                <label class="form-check-label" for="rule_required">
                                    Oui
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-auto">
            <?php $num_item_list=0;?>
            <?php if(
                    $mode=="insert"//le container de la liste liée existe toujours en insert
                    || (
                        $mode=="update"//le container n'existe que si le type field est ok en mode
                        && isset($field->type_field)
                        && ($field->type_field=="radio" || $field->type_field=="check" || $field->type_field=="select")
                    )
                ):?>

                <!-- determiner si liste liée est visibile ou pas -->
                <?php $is_display = (
                    (
                        //On n'affiche pas liste liée si on doit réafficher le formulaire s'il y a une erreur, 
                        $mode=="insert"
                        && isset($getVar["type_field"]) 
                        && !in_array($getVar["type_field"],["check","select","radio"])
                    )       
                    || (
                        //On n'affiche pas la liste liée si on est en mode insert et que la formulaire n'a pas été soumis une première fois
                        $mode=="insert" && !isset($getVar["type_field"])
                    ) 
                ) ? FALSE : TRUE;?>

                <div <?php if(!$is_display):?> style="display:none" <?php endif;?> class="card border-top-navy mb-4 c_list">
                    <div class="card-header">
                        Liste liée
                    </div>
                    <div class="card-body container_list_item">
                        <input type="hidden" name="table_list"
                            <?php if($mode=="update"):?> value="<?=$field->table_list?>" <?php endif;?>
                        />
                        <table class="table table-striped table-hover table-sm table-bordered" mb-2>
                            <thead>
                                <tr>
                                    <?php if($mode=="update"): //thead différent selon que l'on est en insert ou update?>
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
                                    <?php else://case insert?>  
                                        <th>#</th>
                                        <th>Label</th>
                                        <th></th>
                                        <th></th>
                                    <?php endif;?>
                                </tr>
                            </thead>
                        
                            <?php //Cas où il y une erreur après soumission et qu'il faut réafficher les champs?>  
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
                                
                            <!-- Cas où je crée un nouveau champ (insert) mais que je n'ai pas encore soumis le formulaire -->
                            <!-- Par défaut, les champs sont id, label, is_actif -->
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
                                <!-- Cas où j'update un champ mais que je n'ai pas encore soumis le formulaire -->
                                <!-- Par défaut, les champs sont id, label, is_actif            -->
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

                            <!-- Il s'agit de la ligne tr qui est clôner lorsqu'on ajoute une nouveau champ -->
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

                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-sm btn-<?php echo $themes->modelisation->color;?> add_item_in_list"> Ajouter un item dans la liste </button>
                        </div>
                    </div>
                </div> 
            <?php endif;?>
            <input id="number_line" class="num_item_list_input" type="hidden" name="num_item_list" value="<?=$num_item_list?>">


        </div>
    </div>



</form>

<?php $this->endSection(); ?>