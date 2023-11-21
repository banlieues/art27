<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>
<?php $contactConstructor=\Config\Services::contact();?>
<?php $messagerie=\Config\Services::messagerie();?>

<?php $this->extend('\Contact\view-contact-base'); ?>

<?php $this->section('contact-body');?>

<div class="row mb-2 load_ajax">

    <!-- columm possible -->
    <?php for($i=1;$i<3;$i++):?>
        <div id="column<?=$i?>" num_column="<?=$i?>" class="col-lg-6">
            <?php foreach($components as $component):?>
                <?php if($component->column==$i):?>
                    <?php if($component->type=='osm_map'):?>
                        <!-- OpenStreetMap -->
                        <?php if($typeDataView=='read'):?>
                            <?php echo view('DataView\templates/entity-component-osm_map', [
                                'component' => $component,
                                'entity_ref' => 'bien',
                                'id' => $bien->id_bien,
                            ]);?>
                        <?php endif;?>
                        <?php continue;?>
                    <?php elseif($component->type=='google_map'):?>
                        <!-- GooleMap -->
                        <?php if($typeDataView=='read'):?>
                            <?php echo view('DataView\templates/entity-component-google_map', [
                                'component' => $component,
                                'entity_ref' => 'bien',
                                'id' => $bien->id_bien,
                            ]);?>
                            
                        <?php endif;?>
                        <?php continue;?>
                    <?php elseif($component->type=='note'):?>
                        <!-- Notes -->
                        <?php if($typeDataView=='read'):?>
                            <?php echo $messagerie->get_container_note("contact",$id_contact)?>
                        <?php endif;?>
                        <?php continue;?>
                    <?php endif;?>
                    <?php 
                        if(isset($component->name)&&!empty($component->name))
                        {
                            $tableOfValue=$component->name;
                        }
                        else
                        {
                            $tableOfValue=$component->type;
                        }
                            
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
                        <!-- je regarde si on a valeur unique ou non  -->

                        <?php
                            if(is_array($$tableOfValue))
                            {
                            
                                $value_transfert=$value_sql;
                                $count_clone=count($value_sql);
                                if($count_clone==0) $count_clone=1;
                            }
                            else
                            {
                                
                                $value_transfert[]=$value_sql;
                                $count_clone=1;
                            }
                        ?>

                    <?php else:?>
                        <?php  $value_transfert=NULL;?>
                        <?php $count_clone=1;?>
                    <?php endif;?>    
                         
                    <?php for($cc=0;$cc<$count_clone;$cc++):?>     
                        <?php
                            if(isset($value_transfert[$cc])):
                                $vtcc=$value_transfert[$cc];
                            else:
                                $vtcc=NULL;
                            endif;
                        ?>   
                        <div class="card flex-fill mb-4 card_sortable">
                            <?php $type_component=$component->type;?>
                            <div class="card-header border-top-<?php echo $themes->$type_component->color;?>">
                                <h5 class="card-title d-flex justify-content-between align-items-center">
                                    <span class="text-<?php echo $themes->$type_component->color;?>">
                                        <?php echo $themes->$type_component->icon;?>
                                    </span>
                                    <?php if($component->title=="##label_categorie_contact##"):?>
                                        <?= $dataViewConstructorModel->get_label_categorie_contact($component->categorie_profil_contact);?>
                                    <?php else:?>
                                        <?=$component->title?>       
                                    <?php endif;?>

                                    <?php if($component->type=="demande"):?>
                                        <?php //debug($vtcc)?>

                                        <?php if(isset($vtcc->id_demande)):?>
                                            <a class="" style="text-decoration:none; color:black!important" href="<?=base_url()?>/demande/fiche/<?=$vtcc->id_demande?>"><i class="<?=icon("seen")?>"></i></a>
                                        <?php endif;?>
                                    <?php endif;?>

                                    <?php if($typeDataView=="read"):?>  
                                        <?php if($component->is_insert_permit==1||($component->is_insert_permit==0&&!empty($vtcc))):?>
                                            <div class="btn-group btn_contextuel_menu_form">
                                                <span style="cursor:pointer" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                                    <i class="<?=icon("menu_contextuel")?>"></i>
                                                </span>
                                                
                                                <ul class="dropdown-menu">
                                                    <li><a class="ban_modification_component dropdown-item"><i class="<?=icon("edit")?>"></i> Modifier</a></li>

                                                    <?php if($component->type=="contact_profil"):?>

                                                    <li><a class="dropdown-item" href="<?php base_url()?>/demande/new/bureau/0/<?=$id_contact?>/<?=$value_transfert[$cc]->id_contact_profil?>/0"><?=$themes->demande->icon?> Nouvelle demande</a></li>

                                                        <li><a class="dropdown-item" href="<?php base_url()?>/contact/associe_demande/<?=$id_contact?>/<?=$value_transfert[$cc]->id_contact_profil?>"><?=$themes->demande->icon?> Associer à une demande existante</a> </li>          
                                                    
                                                    <?php endif;?>

                                                    <?php if(!empty($component->is_link_externe)||!empty($component->is_multiple)):?>  
                                                        <?php if(isset($value_transfert[$cc]->id_contact_profil_gasap)):?>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li><a text_alert="cette relation" id_delete="<?=$value_transfert[$cc]->id_contact_profil_gasap?>"  href="<?=base_url()?>/contact/delete_relation_contact_profil/" class="ban_deleteForm ban_modification_component dropdown-item"><i class="<?=icon("unlink")?>"></i> Effacer la relation</a></li>
                                                        <?php endif;?>
                                                    <?php endif;?>
                                                </ul>
                                            </div>
                                        <?php endif;?>    
                                    <?php endif;?>
                                </h5>
                            </div>

                            <div <?php if($component->type=="demande" && $count_clone>1):?>style="max-height: 40vh !important; overflow: auto"<?php endif;?> class="card_component card-body">
                                <?php if($typeDataView!="update" && $typeDataView!="create"):?>
                                    <?php if(isset($fields["id_contact"]["label"])&&$typeDataView=="update"&&$component->rank==1):?>
                                        <div class="row mb-2">
                                            <div class="col-lg-6"><b><?=$fields["id_contact"]["label"];?></b></div>
                                            <div class="col"><?=$contact->id_contact?></div>
                                        </div>
                                    <?php endif;?>

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
                                            ])?> 
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
                                    <div <?php if($typeDataView=="read"):?>style="display:none"<?php endif;?>   class="view_components_update container_form_search_link">
                                        <?php if($component->is_search_contact):?>
                                            <div class="container_form_search_link">
                                                <?php //echo $contactConstructor->form_search_link();?>
                                            </div>
                                        <?php endif;?>
                                        <form id="form-entity" class="form_gestion_entity" method="post" action="<?=base_url("contact/save/".$component->type)?>">
                                          
                                            <?=view("DataView\Views\get-dataView",[
                                                "validation"=>$validation,
                                                "typeDataView"=>$tview,
                                                "fields"=>$fields,
                                                "value"=>$vtcc,
                                                "indexes"=>explode(",",trim($component->fields)),
                                                "num_container"=>$component->id_components,
                                            ])?>
                                            <?php if($component->type=="demande"):?>
                                                <?php $possible_id=["id_bien","id_demande","id_demande_caracteristique","id_personne_bien"]; ?>
                                                <?php foreach($possible_id as $pos):?>
                                                    <?php if(isset($vtcc->$pos)):?>
                                                        <input type="hidden" value="<?=$vtcc->$pos?>" name="<?=$pos?>">
                                                    <?php else:?>
                                                        <input type="hidden" value="0" name="<?=$pos?>">
                                                    <?php endif;?>
                                                <?php endforeach;?>
                                            <?php endif;?>
                                            
                                            <input type="hidden" value="<?=$id_contact?>" name="id_entity">
                                            <input type="hidden" value="<?=$typeDataView?>" name="typeDataView">
                                            <?=$dataViewConstructorModel->inputs_liste_key_primary($vtcc,$component->type);?>

                                            <?php if($component->categorie_profil_contact>0&&!empty($component->categorie_profil_contact)):?>
                                                <input type="hidden" name="contact_contact_profil_categorie" value="<?=$component->categorie_profil_contact?>">
                                            <?php endif;?>

                                            <div class="row mb-2 container_one_field">
                                                <div class="col-6">                                         
                                                </div>
                                                <div class="col">
                                                    <button class="btn btn-success btn-sm" type="submit">Enregistrer</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                <?php endif;?>
                            </div>

                            <div class="card-footer text-center">
                                <?php if($component->type=="demande" && $count_clone>1):?>
                                    <a class="tout_voir" style="text-decoration:none; color:black!important" href="#"><i class="<?=icon("expand")?>"></i> Agrandir</a>
                                    <a class="pas_tout_voir" style="display:none;text-decoration:none; color:black!important" href="#"><i class="<?=icon("reduire")?>"></i> Réduire</a> | 
                                <?php endif;?>
                            </div>
                        </div>
                    <?php endfor;?>

                    <div class="">
                        <?php if($component->type=="contact_profil"):?>
                            <div style="display:none" class="card text-left new_contact_profil">
                                <div class="card-header">
                                    <h5 class="card-title d-flex justify-content-between align-items-center">
                                        <span class="text-<?php echo $themes->$type_component->color;?>"><?php echo $themes->$type_component->icon;?></span>Nouveau profil de contact
                                    </h5>
                                </div>

                                <div class="card-body">
                                    <div class="view_components_read">
                                        <form action="<?=base_url()?>/contact/save/contact_profil" method="post">
                                            <?=view("DataView\Views\get-dataView",[
                                                    "validation"=>$validation,
                                                    "typeDataView"=>"insert",
                                                    "fields"=>$fields,
                                                    "value"=>NULL,
                                                    "indexes"=>explode(",",trim($component->fields)),
                                                    "num_container"=>$component->id_components,
                                                ])?> 
                                            <input type="hidden" value="<?=$id_contact?>" name="id_entity">
                                            <input type="hidden" value="0" name="id_contact_profil">
                                        
                                            <input type="hidden" value="<?=$typeDataView?>" name="typeDataView">
                                            <button class="btn btn-success btn-sm">Ajouter le profil</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endif;?>
                    </div>
                <?php endif;?>    
            <?php endforeach;?>      
        </div>
    <?php endfor;?> 
</div>

<?php $this->endSection();?>



                                           

