<?php if($component->type=='osm_map'):?>
    <!-- OpenStreetMap -->
    <?php if(in_array($typeDataView, ['read'])):?>
        <?php echo view('DataView\templates/entity-component-osm_map', [
            'id' => $demande->id_demande,
        ]);?>
    <?php endif;?>
<?php elseif($component->type=='google_map'):?>
    <!-- GoogleMap -->
    <?php if(in_array($typeDataView, ['read'])):?>
        <?php echo view('DataView\templates/entity-component-google_map', [
            'id' => $demande->id_demande,
        ]);?>
    <?php endif;?>
<?php elseif($component->type=='note'):?>
    <!-- Notes -->
    <?php if($typeDataView=='read'):?>
        <?php $messagerie=\Config\Services::messagerie();?>
        <?php echo $messagerie->get_container_note($entity_ref, $id_entity_value);?>
    <?php endif;?>
<?php else:?>
    <!-- Component -->
    <?php $tableOfValue = (isset($component->name) && !empty($component->name)) ? $component->name : $component->type;?>
    <?php $value_sql = isset($$tableOfValue) ? $$tableOfValue : null;?>
    <?php if($component->is_always_visible && !empty($value_sql)):?>

        <?php if(in_array($typeDataView, ['read', 'update'])):?>
            <!-- je regarde si on a valeur unique ou non  -->
            <?php $value_transfert = is_array($$tableOfValue) ? $value_sql : [$value_sql];?>
            <?php $count_clone = is_array($$tableOfValue) ? count($value_sql) : 1;?>
            <?php $count_clone = $count_clone==0 ? 1 : $count_clone;?>
        <?php else:?>
            <?php $value_transfert = NULL;?>
            <?php $count_clone = 1;?>
        <?php endif;?>    
        <?php for($cc=0;$cc<$count_clone;$cc++):?>     
            <div class="card mb-4">
                <?php $vtcc = isset($value_transfert[$cc]) ? $value_transfert[$cc] : NULL;?>    
                
                <!-- card-header -->
                <div class="card-header border-top-<?php echo $themes->{$component->type}->color;?> d-flex justify-content-between align-items-center">
                    <h5 class="text-<?php echo $themes->{$component->type}->color;?>">
                        <?php echo $themes->{$component->type}->icon;?>
                    </h5>
                    
                    <?php if($component->title=="##label_categorie_demande##"):?>
                        <?php echo $dataViewConstructorModel->get_label_categorie_demande($component->categorie_profil_demande);?>
                    <?php else:?>
                        <?php echo $component->title?>       
                    <?php endif;?>

                    <?php if($entity_ref!=$component->type):?>
                        <a role="button"
                            class="btn btn-sm btn-link link-dark"
                            href="<?php echo base_url("$component->type/fiche/" . $vtcc->{'id_' . $component->type})?>"
                            >
                            <i class="<?=icon("seen")?>"></i>
                        </a>
                    <?php endif;?>

                    <?php if(in_array($typeDataView, ['read'])):?>  
                        <div class="btn-group btn_contextuel_menu_form">
                            <span style="cursor:pointer" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="<?=icon("menu_contextuel")?>"></i>
                            </span>
                            <ul class="dropdown-menu">
                                <li>
                                    <a role="button" class="ban_modification_component dropdown-item">
                                        <i class="<?=icon("edit")?>"></i>
                                        Modifier
                                    </a>
                                </li>
                                <?php if($entity_ref=='contact' && $component->type=='contact_profil'):?>
                                    <li>
                                        <a class="dropdown-item" href="<?php base_url("demande/new/bureau/0/$id_entity_value/" . $value_transfert[$cc]->id_contact_profil . "/0")?>">
                                            <?php echo $themes->demande->icon?>
                                            Nouvelle demande
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="<?php base_url("$id_entity_key/associe_demande/$id_entity_value/" . $value_transfert[$cc]->id_contact_profil)?>">
                                            <?php echo $themes->demande->icon?> 
                                            Associer à une demande existante
                                        </a>
                                    </li>          
                                <?php endif;?>
                                <?php if($entity_ref=='demande' && $entity_ref==!$component->type && !empty($vtcc->{'id_' . $component->type}) && !empty($id_personne_bien)):?>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <a class="ban_deleteForm ban_modification_component dropdown-item"
                                            text_alert="cette relation"
                                            id_delete="<?php echo $id_personne_bien?>"
                                            href="<?php echo base_url("demande/delete_$component->type/$id_personne_bien/$id_entity_value");?>"
                                            >
                                            <i class="<?=icon("unlink")?>"></i>
                                            Effacer la relation
                                        </a>
                                    </li>
                                <?php endif;?>
                            </ul>
                        </div>
                    <?php endif;?>
                </div>

                <!-- card-body -->
                <div class="card-body card_component" style="max-height: 30vh !important; overflow: auto">
                    <?php if(in_array($typeDataView, ['read'])):?>
                        <div class="view_components_read">
                            <?php echo view("DataView\Views\get-dataView",[
                                "validation" => $validation,
                                "typeDataView" => 'read',
                                "fields" => $fields,
                                "value" => $vtcc,
                                "indexes" => explode(",", trim($component->fields)),
                                "num_container" => $component->id_components,
                            ]);?>
                        </div>
                    <?php endif;?>
                    <?php if(in_array($typeDataView, ["read", "create"])):?>  
                        <div class="view_components_update container_form_search_link"
                            <?php if(in_array($typeDataView, ["read"])):?> style="display:none" <?php endif;?>
                            >
                            <?php if($component->is_search_contact):?>
                                <?php $contactConstructor=\Config\Services::contact();?>
                                <?php echo $contactConstructor->form_search_link();?>
                            <?php endif;?>
                            <form id="form-entity"
                                class="form_gestion_entity"
                                method="post"
                                action="<?php echo base_url("$entity_ref/save/$component->type")?>"
                                >
                                <?php echo view("DataView\Views\get-dataView",[
                                    "validation" => $validation,
                                    "typeDataView" => 'update',
                                    "fields" => $fields,
                                    "value" => $vtcc,
                                    "indexes" => explode(",", trim($component->fields)),
                                    "num_container" => $component->id_components,
                                ]);?>  
                                <input type="hidden" value="<?php echo $id_entity_value?>" name="id_entity">
                                <input type="hidden" value="<?php echo $id_entity_value?>" name="<?php echo $id_entity_key;?>">
                                <?php $DataViewModel = new \DataView\Models\dataViewConstructorModel();?>
                                <?php echo $DataViewModel->inputs_liste_key_primary($vtcc, $component->type);?>

                                <?php if($component->type=="demande"):
                                    $possible_id = ["id_demande, id_demande_caracteristique", "id_personne_bien"];
                                elseif($component->type=="contact"):
                                    $possible_id = ["id_contact", "id_contact_profil", "id_personne_bien"];
                                elseif($component->type=="bien"):
                                    $possible_id = ["id_bien", "id_bien_caracteristique", "id_personne_bien"];
                                else :
                                    $possible_id = [];
                                endif;?>

                                <?php foreach($possible_id as $pos):?>
                                    <?php if(isset($vtcc->$pos)):?>
                                        <input type="hidden" value="<?=$vtcc->$pos?>" name="<?=$pos?>"/>
                                    <?php else:?>
                                        <input type="hidden" value="0" name="<?=$pos?>"/>
                                    <?php endif;?>
                                <?php endforeach;?>

                                <?php if(!empty($component->categorie_profil_contact)):?>
                                    <input type="hidden" name="contact_contact_profil_categorie" value="<?php echo $component->categorie_profil_contact?>"/>
                                <?php endif;?>

                                <input type="hidden" value="<?=$typeDataView?>" name="typeDataView">

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

                <!-- card-footer -->
                <div class="card-footer text-center">
                    <button type="button"
                        class="tout_voir btn btn-sm btn-link link-dark text-decoration-none"
                        >
                        <i class="<?=icon("expand")?>"></i>
                        Agrandir
                    </button>
                    <button type="button"
                        class="pas_tout_voir btn btn-sm btn-link link-dark text-decoration-none"
                        style="display: none;"
                        >
                        <i class="<?=icon("reduire")?>"></i>
                        Réduire
                    </button>
                </div> 
            </div>
        <?php endfor;?>
    <?php endif;?>
<?php endif;?>
