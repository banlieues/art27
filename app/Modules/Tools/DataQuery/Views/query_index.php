<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>


<form method="post" action="<?=base_url();?>queries/execute/<?=$id_requete?>/<?=$id_requete_provisoire?>/1">
    <div class="d-flex justify-content-between py-2 mb-2 mb-xl-3 sticky_button bg-light">
        <div>
            <h3 class="fs-4"><?php echo $title; ?></h3>
        </div>
        <div>
            <span class="zone-button-form">
                <button class="btn btn-sm btn-success" type="submit">
                    <i class="<?=icon("queries")?>"></i> 
                    Voir résultat
                </button>
                <a class="btn btn-sm btn-secondary" href="<?=base_url()?>/queries/">
                   
                    Annuler
                </a>
            </span>
            <span style="display:none" class="zone-submit-loading"> 
                <i class="fas fa-circle-notch fa-spin"></i> 
                Veuillez patientez
            </span>  
        </div> 
    </div>
    <?php if($id_requete>0):?>
        <h3><?=$nom_requete?> (<?=$id_requete?>)</h3>
    <?php endif;?>
    <div class="row">
        <div class="col-md-12">
            <div class="card border-top-pink mb-4 text-center">
                <div class="card-header">
                    Conditions
                </div>
                <div class="card-body">
                    <style>
                        .trw{background-color:white !important}
                        .trr{background-color:red !important} 
                    </style>

                    <table style='' id='requete' class="requete_table_principal requete_table table border-danger table-bordered table-condensed">
                        <?php for($i=1;$i<$nb_tour;$i++):?>
                        <tr class="tr_base trw text-center">
                            <th>#<span class="tr_number"><?=$i?></span></th>
                            <td>
                                <span class="cet_cou" <?php if($i==1):?>style="display:none !important<?php endif;?>">
                                    <select  class="ou_et"  name="ou_et_##<?=$i?>">
                                        <option <?php if(isset($getVar["ou_et_##$i"])&&$getVar["ou_et_##$i"]=="AND"):?> selected <?php endif;?> value="AND">Et</option>
                                        <option <?php if(isset($getVar["ou_et_##$i"])&&$getVar["ou_et_##$i"]=="OR"):?> selected <?php endif;?> value="OR">Ou</option>
                                    </select>
                                </span>
                            </td>
                            <td>
                                <select class="par_ouvert"  name="par_ouvert_##<?=$i?>">
                                    <option <?php if(isset($getVar["par_ouvert_##$i"])&&$getVar["par_ouvert_##$i"]==0):?> selected <?php endif;?> value="0"></option>
                                    <option <?php if(isset($getVar["par_ouvert_##$i"])&&$getVar["par_ouvert_##$i"]==1):?> selected <?php endif;?> value="1">(</option>
                                </select>
                            </td>
                            <td><?php 
                                    $entity_select=NULL;
                                    if(isset($getVar["entity_##$i"])):
                                        $entity_select=$getVar["entity_##$i"];
                                    endif;    

                                    if($entity_select=='0'): $entity_select=NULL; endif;

                                    echo $dataQueryConstructor->list_entity($i,$entity_select);
                                ?>
                            </td>
                            <td class="c_liste_select_champ">
                                <?php
                                    $field_select=NULL;
                                if(!is_null($entity_select)): 
                                        if(isset($getVar["entity_##$i"])):
                                            $field_select=$getVar["champ_##$i"];   
                                        endif;
                            
                                        echo $dataQueryConstructor->get_list_select_field($entity_select,$i,$field_select);
                                endif;
                                ?>    
                            </td>
                            <td class="c_liste_operateur_champ">
                                
                                <?php 
                                    if(!is_null($field_select)&&$field_select!==0&&isset($getVar["operateur_##$i"])):
                                        echo $dataQueryConstructor->get_operateur($field_select,$i,$getVar["operateur_##$i"]); 
                                    endif;   
                                    
                                ?>

                            </td>
                            <td class="c_liste_input">
                                <?php
                                    if(!is_null($field_select)&&$field_select!==0&&isset($getVar["operateur_##$i"])):
                                        $value=NULL;
                                        if(isset($getVar["##$i##_value"])&&!empty($getVar["##$i##_value"])):
                                            $value=$getVar["##$i##_value"];
                                        endif;   
                                        echo $dataQueryConstructor->get_input($field_select,$i,$value);
                                    endif;    
                                ?>                                    
                            </td>

                            <td>
                                <select class="par_ferme"  name="par_ferme_##<?=$i?>">
                                    <option <?php if(isset($getVar["par_ouvert_##$i"])&&$getVar["par_ferme_##$i"]==0):?> selected <?php endif;?> value="0"></option>
                                    <option <?php if(isset($getVar["par_ouvert_##$i"])&&$getVar["par_ferme_##$i"]==1):?> selected <?php endif;?> value="1">)</option>
                                </select>
                            </td>
                            <td>
                                <button class="ajout_externe"><i class="fa fa-plus"></i></button>&nbsp;
                                <button style="<?php if($i==1):?>display:none<?php endif;?>" class="delete_externe"><i class="fa fa-minus"></i></button>
                            </td>
                        
                        </tr>  
                        <?php endfor;?>
                        
                    </table>
                    <input id="number_line" type="hidden" name="number" value="<?php echo $number;?>">
                </div>
            
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-12 col-lg-9">
            <div class="card border-top-pink  mb-1">
                <div class="card-header">
                    Choisir des champs
                </div>
                <div class="card-body">
                    <?php //echo debug($entities);?>
                <?php foreach($entities as $entity):?>
                    
                        <?php if(isset($entity->type)):?>
                            <?php $type=$entity->type;?>
                            <?php if(isset($fields[$type])):?>
                                <div class="p-4 pt-2 mb-4 border border-<?php echo $themes->{$entity->ref}->color;?> rounded" style="">
                                    <div class="fs-5 mb-2">
                                        <div class="me-2 d-inline text-<?php echo $themes->{$entity->ref}->color;?>">
                                            <?php echo $themes->{$entity->ref}->icon;?>
                                        </div>
                                        <?=$entity->label?>
                                    </div>
                                    <div class="row">
                                        <?php foreach($fields[$type] as $field):?>
                                            <div style="cursor:pointer; text-align:left" 
                                                color="<?php echo $themes->{$entity->ref}->color;?>"
                                                statut="no_select" 
                                                name_index="<?=$field->field_index?>"
                                                class="col-3 cf c_field_queries c_field_queries_<?=$field->field_index?>"
                                                >
                                                <div class="card-text row">
                                                        <div class="col-lg-1">
                                                            <i class="<?=icon("plus-field")?> plus_field"></i> 
                                                        
                                                            <i style="display:none" class="<?=icon("check-field")?> text-success check_queries"></i> 
                                                            <i name_index="<?=$field->field_index?>" style="display:none" class="text-danger <?=icon("minus-field")?> text-success minus_field c_field_queries_minus"></i> 
                                                        </div>
                                                        <div class="col-lg-10">
                                                            <span class="labelField"><?=$field->label?></span>
                                                            <input type="hidden" class="input_fields_select" value="<?=$field->field_index?>">
                                                            <span class="is_group_order_by" style="font-size: 10px;display:none"><br>
                                                                    <input class="input_order_by input_checkbox<?=$field->field_index?>" <?php if(in_array($field->field_index,$order_by)):?>checked<?php endif;?> type="checkbox" value="<?=$field->field_index?>"> <label>Classé par</label>
                                                                    <span class="m-4"><input class="input_group_by input_checkbox<?=$field->field_index?>" <?php if(in_array($field->field_index,$group_by)):?>checked<?php endif;?> type="checkbox" value="<?=$field->field_index?>"> <label>Group by</label></span>


                                                            </span>
                                                          
                                                            </span>
                                                        </div>
                                                        <div class="col-lg-1">
                                                            <i style="display:none; cursor: grab "  class="<?=icon("moveVertical")?> move_sortable_queries"></i> 
                                                        </div>

                                                </div>   
                                            </div>
                                        <?php endforeach;?>  
                                    </div>  
                                </div>      
                            <?php endif;?>
                        <?php endif;?>    
                    <?php endforeach;?>
                </div>
            </div>
        </div>
        <div class="col-12 col-lg-3">
            <div class="card border-top-pink  mb-4 text-center">
                <div class="card-header">
                    Champs sélectionnés
                </div>
                <div class="card-body">
                    <h5 class="card-title mention_no_fields">Pas de champs sélectionnés</h5>
                    
                    <div class="card-text card_field_select">
                        <div class="fields-sortable-queries">
                            
                        </div>               
                    </div>
                    
                </div>
            
            </div>
        </div>
    </div>

    <!-- <div class="container-fluid fixed-bottom text-center p-2 footer-form border-top">

            <span class="zone-button-form">
                <button class="btn btn-success" type="submit"><i class="<?=icon("queries")?>"></i> Voir résultat</button>
                
                    <a class="btn btn-danger" href="<?=base_url()?>/queries/"><i class="<?=icon("queries")?>"></i> Annuler</a>
            
            </span>
            <span style="display:none" class="zone-submit-loading"> <i class="fas fa-circle-notch fa-spin"></i> Veuillez patientez</span>  
    
    </div>  -->
</form>



<?php $this->endSection(); ?>


<?php $this->section("script_injected_foot"); ?>

        
            <?php //echo view($path."banQueries_js");?> 

       
                    
<?php $this->endSection(); ?>



