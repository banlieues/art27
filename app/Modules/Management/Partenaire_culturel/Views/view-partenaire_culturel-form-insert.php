<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>

<?php $this->extend('\Partenaire_culturel\view-partenaire_culturel-base'); ?>

<?php $this->section('partenaire_culturel-body');?>
<form id="form_component_actif" class="form_component_actif" method="post" action="<?=base_url()?>/partenaire_culturel/save" >
<div class="row mb-2 load_ajax">

    <!-- columm possible -->
    <?php for($i=1;$i<3;$i++):?>
        <div 
            id="column<?=$i?>" 
            num_column="<?=$i?>" 
            class="col-lg-6 <?php if($typeDataView=="modelisation"):?>column-sortable<?php endif;?>"
            >
       
             
            <?php foreach($components as $component):?>
                <?php if($component->column==$i):?>
                 
                            <?php 
                                if(isset($component->name)&&!empty($component->name))
                                {
                                    $tableOfValue=$component->name;
                                }
                                else
                                {
                                    $tableOfValue=$component->type;
                                }
                                
                                //debug($component->type);

                                if(isset($$tableOfValue))
                                {
                                    $value_sql=$$tableOfValue;
                                }
                                else
                                {
                                    $value_sql=NULL;
                                }
                            ?>   
                               
                            <?php if(!$component->is_always_visible&&empty($value_sql)){continue;} ?>


                            <?php if($typeDataView=="read"||$typeDataView=="update"):?>

                                <?php //je regarde si on a valeur unique ou non 

                                    if(is_array($$tableOfValue))
                                    {
                                        
                                        $value_transfert=$value_sql;
                                        $count_clone=count($value_sql);
                                        if($count_clone==0) $count_clone=1;
                                    }
                                    else
                                    {
                                        unset( $value_transfert);
                                        $value_transfert[]=$value_sql;
                                        $count_clone=1;
                                    }

                                ?>

                            <?php else:?>
                                <?php  $value_transfert=NULL;?>
                                <?php $count_clone=1;?>

                            <?php endif;?>    


                        


                <?php for($cc=0;$cc<$count_clone;$cc++):?>  
                    <?php if(isset($value_transfert[$cc])):
                                        $vtcc=$value_transfert[$cc];
                                    else:
                                        $vtcc=NULL;
                                    endif;?>   

                                   
                
                    <div class="card flex-fill mb-4 card_sortable">
                        <?php $type_component=$component->type;?>
                        <div class="card-header border-top-<?php echo $themes->$type_component->color;?>">
                            <h5 class="card-title d-flex justify-content-between align-items-center">
                                <span class="text-<?php echo $themes->$type_component->color;?>"><?php echo $themes->$type_component->icon;?></span>
                                    <?php if($component->title=="##label_categorie_partenaire_culturel##"):?>
                                            <?= $dataViewConstructorModel->get_label_categorie_partenaire_culturel($component->categorie_profil_partenaire_culturel);?>
                                    <?php else:?>
                                        <?=$component->title?>       
                                    <?php endif;?>

                                    <?php if($component->type=="demande"&&$typeDataView!=="modelisation"):?>
                                        <?php //debug($vtcc)?>
                                        <?php if(isset($vtcc->id_demande)):?>
                            <a class="" style="text-decoration:none; color:black!important" href="<?=base_url()?>/demande/fiche/<?=$vtcc->id_demande?>"><i class="<?=icon("seen")?>"></i> 
                        </a>
                        <?php endif;?>

                        <?php endif;?>

                                <?php if($typeDataView=="modelisation"):?>    
                                    <i style="cursor:grab" class="<?=icon("moveHorizontal");?> move-sortable-column"></i>
                                    <?php else:?>
                                    <div style="width:20px"></div>  
                                <?php endif;?>
                                <?php if($typeDataView=="read"):?>  
                                    <?php if($component->is_insert_permit==1||($component->is_insert_permit==0&&!empty($vtcc))):?>

                                    <div class="btn-group btn_contextuel_menu_form">
                                        <span style="cursor:pointer" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="<?=icon("menu_contextuel")?>"></i>
                                        </span>
                                        
                                        <ul class="dropdown-menu">
                                            <li><a href="<?=base_url()?>/modelisation/modification_components" class="ban_modification_component dropdown-item"><i class="<?=icon("edit")?>"></i> Modifier</a></li>
                                           <?php if(!empty($component->is_link_externe)||!empty($component->is_multiple)):?>
                                                
                                                <?php if(isset($value_transfert[$cc]->id_partenaire_culturel_profil_gasap)):?>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a text_alert="cette relation" id_delete="<?=$value_transfert[$cc]->id_partenaire_culturel_profil_gasap?>"  href="<?=base_url()?>/partenaire_culturel/delete_relation_partenaire_culturel_profil/" class="ban_deleteForm ban_modification_component dropdown-item"><i class="<?=icon("unlink")?>"></i> Effacer la relation</a></li>
                                                <?php endif;?>
                                        <?php endif;?>

                                           
                                           
                                        </ul>
                                    </div>
                                        
                                    <?php endif;?>

                                <?php endif;?>
                            </h5>
                        </div>

                        <div <?php if($component->type=="demande"&&$typeDataView!=="modelisation"&&$count_clone>1):?>style="max-height: 40vh !important; overflow: auto"<?php endif;?> class="card_component card-body <?php if($typeDataView=="modelisation"):?>fields-sortable<?php endif;?>">
                           
                             
                            <?php if($typeDataView!="update"&&$typeDataView!="create"):?>
                                <?php if(isset($fields["id_partenaire_culturel"]["label"])&&$typeDataView=="update"&&$component->rank==1):?>
                                    <div class="row mb-2">
                                        
                                        <div class="col-lg-6"><b><?=$fields["id_partenaire_culturel"]["label"];?></b></div>
                                        <div class="col"><?=$partenaire_culturel->id_partenaire_culturel?></div>
                                    </div>
                                <?php endif;?>

                             
                                    <?php //debug($vtcc)?>


                                <?php if($typeDataView=="update"||$typeDataView=="read"):?>
                                    <div class="view_components_read">
                                <?php endif;?>
                                <?php if($component->is_insert_permit==1):?>
                                        <?=view("DataView\Views\get-dataView",[
                                            "validation"=>$validation,
                                            "typeDataView"=>$typeDataView,
                                            "fields"=>$fields,
                                            "value"=>$vtcc,
                                            "indexes"=>explode(",",trim($component->fields)),
                                            "num_container"=>$component->id_components,
                                    
                                            
                                            ])
                                        ?> 
                                <?php else:?>

                                    <?php if(!empty($vtcc)):?>
                                        <?=view("DataView\Views\get-dataView",[
                                            "validation"=>$validation,
                                            "typeDataView"=>$typeDataView,
                                            "fields"=>$fields,
                                            "value"=>$vtcc,
                                            "indexes"=>explode(",",trim($component->fields)),
                                            "num_container"=>$component->id_components,
                                    
                                            
                                            ])
                                        ?> 

                                    <?php else:?>

                                        <div class="text-center">Pas de fiches associées</div>

                                    <?php endif;?>

                                <?php endif;?>

                            <?php endif;?>


                              <?php if($typeDataView=="update"||$typeDataView=="read"):?> 
                                </div>
                            <?php endif;?>

                            <?php if($typeDataView=="read"||$typeDataView=="create"):?> 

                                    <?php if($typeDataView=="read") { $tview="update"; } else {$tview=$typeDataView;} ?>
                                            
                                    <div <?php if($typeDataView=="read"):?>style="display:none"<?php endif;?> class="view_components_update container_form_search_link">
                                        
                                  
                                   
                                 
                                            <?=view("DataView\Views\get-dataView",[
                                                "validation"=>$validation,
                                                "typeDataView"=>$tview,
                                                "fields"=>$fields,
                                                "value"=>$vtcc,
                                                "indexes"=>explode(",",trim($component->fields)),
                                                "num_container"=>$component->id_components,
                                                
                                                ])
                                            ?>  
                                        
                                        <input type="hidden" value="<?=$id_partenaire_culturel?>" name="id_entity">
                                      
                                         
                                        <input type="hidden" value="<?=$id_partenaire_culturel?>" name="id_partenaire_culturel">
                                              
                                        <?php if($component->type=="partenaire_culturel_caracteristique"):?>
                                            
                                            <?php if(isset($vtcc->id_partenaire_culturel_caracteristique)):?>
                                                <input type="hidden" value="<?=$vtcc->id_partenaire_culturel_caracteristique?>" name="id_partenaire_culturel_caracteristique">
                                            <?php else:?>
                                                <input type="hidden" value="0" name="id_partenaire_culturel_caracteristique">

                                            <?php endif;?>
                                        <?php endif;?>


                                        <?php
                                        if($component->type=="demande"):?>

                                            <?php $possible_id=["id_demande_caracteristique","id_contact","id_demande","id_contact_profil","id_personne_partenaire_culturel"]; ?>

                                            <?php foreach($possible_id as $pos):?>
                                                <?php if(isset($vtcc->$pos)):?>
                                                    <input type="hidden" value="<?=$vtcc->$pos?>" name="<?=$pos?>">
                                                <?php else:?>
                                                    <input type="hidden" value="0" name="<?=$pos?>">
                                                <?php endif;?>
                                            <?php endforeach;?>

                                        <?php endif;?>

                    

                                        
                                    
                                         
                                        

                                        
                                    

                                        <div class="row mb-2 container_one_field">

                        
                                                <div class="col-6">
                                                        
                                                    
                                                                                            
                                                </div>



                                                    <div class="col">
                                                    


                                                </div>
                                        </div>


                                
                               
                                    </div>
                                <?php endif;?>




                              

                     
                        </div>


                        <div class="card-footer text-center">

                        <?php if($component->type=="demande"&&$typeDataView!=="modelisation"&&$count_clone>1):?>
                                <a class="tout_voir" style="text-decoration:none; color:black!important" href="#"><i class="<?=icon("expand")?>"></i> Agrandir</a>
                                <a class="pas_tout_voir" style="display:none;text-decoration:none; color:black!important" href="#"><i class="<?=icon("reduire")?>"></i> Réduire</a>
                        <?php endif;?>
                       

                        </div>


                
                    </div>
                <?php endfor;?>  
             
                <?php endif;?>    
            <?php endforeach;?>      
        </div>
    <?php endfor;?> 
</div>

<!-- input hidden to declare update or insert -->
<input type="hidden" value="<?=$typeDataView?>" name="typeDataView">
<input type="hidden" value="<?=$id_partenaire_culturel?>" name="id_partenaire_culturel">
</form>


<?php $this->endSection();?>
