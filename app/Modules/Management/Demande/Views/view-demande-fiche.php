<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>
<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>
<?php $contactConstructor=\Config\Services::contact();?>
<?php $messagerie=\Config\Services::messagerie();?>

<?php $this->extend('\Demande\view-demande-base'); ?>

<?php $this->section('demande-body');?>   

<div id="top_fiche">
</div>

<div id="top_rdv" <?php if(is_null($oneRdv)):?> style="display:none" <?php endif;?> class="card border-<?=$themes->rdv->color?> mb-2"> 
    <div class="card-header">
        <h5><small class="text-<?=$themes->rdv->color?>"><?=$themes->tache->icon?></small> Détail rendez-vous</h5>
    </div>
    <div class="card-body">  
        <div style="display:none" class="loading text-center mt-5"><i class="fas fa-circle-notch fa-spin"></i> <br>Chargement</div>
        <div id="container_rdv_create"  <?php if(is_null($oneRdv)):?>style="display:none"<?php endif;?> class="loader" >
            <?php if(!is_null($oneRdv)):?>
                <?php echo $oneRdv;?>
            <?php endif;?>
        </div>
    </div>  
</div>

<div class="row load_ajax">
    <div class="col-lg-3">
        <!-- start components -->
        <div class="row mb-2">
            <!-- columm possible -->
            <?php for($i=1;$i<3;$i++):?>
                <div id="column<?=$i?>" num_column="<?=$i?>" class="col-lg-12" >
                    <?php foreach($components as $component):?>
                        <?php if($component->column==$i):?>
                            <?php if($component->type=='osm_map'):?>
                                <!-- OpenStreetMap -->
                                <?php if($typeDataView=='read'):?>
                                    <?php echo view('DataView\templates/entity-component-osm_map', [
                                        'component' => $component,
                                        'entity_ref' => 'demande',
                                        'id' => $demande->id_demande,
                                    ]);?>
                                <?php endif;?>
                                <?php continue;?>
                            <?php elseif($component->type=='google_map'):?>
                                <!-- GooleMap -->
                                <?php if($typeDataView=='read'):?>
                                    <?php echo view('DataView\templates/entity-component-google_map', [
                                        'component' => $component,
                                        'entity_ref' => 'demande',
                                        'id' => $demande->id_demande,
                                    ]);?>
                                <?php endif;?>
                                <?php continue;?>
                            <?php endif;?>
                
                            <?php 
                                if(isset($component->name)&&!empty($component->name)) $tableOfValue=$component->name; else $tableOfValue=$component->type;
                                if(isset($$tableOfValue)) $value_sql=$$tableOfValue; else $value_sql=NULL;
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
                                <div class="card flex-fill mb-4">
                                    <?php if(isset($value_transfert[$cc])):
                                            $vtcc=$value_transfert[$cc];
                                        else:
                                            $vtcc=NULL;
                                        endif;
                                    ?>    
                                    <?php $type_component=$component->type;?>
                                    <div class="card-header border-top-<?php echo $themes->$type_component->color;?>">
                                        <h5 class="card-title d-flex justify-content-between align-items-center">
                                            <span class="text-<?php echo $themes->$type_component->color;?>"><?php echo $themes->$type_component->icon;?></span>
                                            <?php if($component->title=="##label_categorie_demande##"):?>
                                                    <?= $dataViewConstructorModel->get_label_categorie_demande($component->categorie_profil_demande);?>
                                            <?php else:?>
                                                <?=$component->title?>       
                                            <?php endif;?>
                                            <?php if($component->type=="bien" && !empty($vtcc->id_bien)):?>
                                                <a class="" style="text-decoration:none; color:black!important" href="<?=base_url()?>bien/fiche/<?=$vtcc->id_bien?>"><i class="<?=icon("seen")?>"></i></a>
                                            <?php endif;?>
                                
                                            <?php if($component->type=="contact"):?>
                                            <?php //debug($vtcc)?>
                                                <?php if(isset($vtcc->id_contact)):?>
                                                    <a class="" style="text-decoration:none; color:black!important" href="<?=base_url()?>contact/fiche/<?=$vtcc->id_contact?>"><i class="<?=icon("seen")?>"></i></a>
                                                <?php endif;?>
                                            <?php endif;?>

                                            <?php if($typeDataView=="read"):?>  
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
                                                        <?php if($component->type=="bien" && !empty($vtcc->id_bien)&&$id_personne_bien>0):?>
                                                            <li><hr class="dropdown-divider"></li>
                                                            <li>
                                                                <a class="ban_deleteForm ban_modification_component dropdown-item"
                                                                    text_alert="cette relation"
                                                                    id_delete="<?=$id_personne_bien?>"
                                                                    href="<?=base_url()?>demande/delete_bien/<?=$id_personne_bien?>/<?=$id_demande?>"
                                                                    >
                                                                        <i class="<?=icon("unlink")?>"></i>
                                                                        Effacer la relation
                                                                    </a>
                                                                </li>
                                                        <?php endif;?>
                                                        <?php if($component->type=="contact" && !empty($vtcc->id_contact)&&$id_personne_bien>0):?>
                                                                <li><hr class="dropdown-divider"></li>
                                                                <li><a text_alert="cette relation" id_delete="<?=$id_personne_bien?>"  href="<?=base_url()?>demande/delete_contact/<?=$id_personne_bien?>/<?=$id_demande?>" class="ban_deleteForm ban_modification_component dropdown-item"><i class="<?=icon("unlink")?>"></i> Effacer la relation</a></li>
                                                        <?php endif;?>                                                
                                                    </ul>
                                                </div>
                                            <?php endif;?>
                                        </h5>
                                    </div>

                                    <div style="max-height: 30vh !important; overflow: auto" class="card_component card-body">
                                        <?php if($typeDataView!="update" && $typeDataView!="create"):?>
                                            <?php if(isset($fields["id_demande"]["label"])&&$typeDataView=="update"&&$component->rank==1):?>
                                                <div class="row mb-2">
                                                    <div class="col-lg-6"><b><?=$fields["id_demande"]["label"];?></b></div>
                                                    <div class="col"><?=$demande->id_demande?></div>
                                                </div>
                                            <?php endif;?>
                                            <?php if($typeDataView=="update"||$typeDataView=="read"):?>
                                                <div class="view_components_read">
                                            <?php endif;?>

                                            <?=view("DataView\Views\get-dataView",[
                                                "validation"=>$validation,
                                                "typeDataView"=>$typeDataView,
                                                "fields"=>$fields,
                                                "value"=>$vtcc,
                                                "indexes"=>explode(",",trim($component->fields)),
                                                "num_container"=>$component->id_components,
                                            ])?> 
                                        <?php endif;?>
                                        <?php if($typeDataView=="update"||$typeDataView=="read"):?> 
                                            </div>
                                        <?php endif;?>

                                        <?php if($typeDataView=="read"||$typeDataView=="create"):?> 

                                            <?php if($typeDataView=="read") { $tview="update"; } else {$tview=$typeDataView;} ?>
                                                    
                                            <div <?php if($typeDataView=="read"):?>style="display:none"<?php endif;?> class="view_components_update container_form_search_link">
                                            
                                                <?php if($component->is_search_contact):?>
                                                    <?=$contactConstructor->form_search_link();?>
                                                <?php endif;?>

                                                <form id="form-entity" class="form_gestion_entity" method="post" action="<?=base_url("demande/save/".$component->type)?>">
                                                    <?=view("DataView\Views\get-dataView",[
                                                        "validation"=>$validation,
                                                        "typeDataView"=>$tview,
                                                        "fields"=>$fields,
                                                        "value"=>$vtcc,
                                                        "indexes"=>explode(",",trim($component->fields)),
                                                        "num_container"=>$component->id_components,
                                                    ])?>  
                                                
                                                    <input type="hidden" value="<?=$id_demande?>" name="id_entity">
                                                    <input type="hidden" value="<?=$id_demande?>" name="id_demande">

                                                    <?php if($component->type=="demande"):?>
                                                        <?php $possible_id=["id_demande_caracteristique","id_personne_bien"]; ?>
                                                        <?php foreach($possible_id as $pos):?>
                                                            <?php if(isset($vtcc->$pos)):?>
                                                                <input type="hidden" value="<?=$vtcc->$pos?>" name="<?=$pos?>">
                                                            <?php else:?>
                                                                <input type="hidden" value="0" name="<?=$pos?>">
                                                            <?php endif;?>
                                                        <?php endforeach;?>
                                                    <?php endif;?>
                                                
                                                    <?php if($component->type=="contact"):?>
                                                        <?php $possible_id=["id_contact","id_contact_profil","id_personne_bien"]; ?>
                                                        <?php foreach($possible_id as $pos):?>
                                                            <?php if(isset($vtcc->$pos)):?>
                                                                <input type="hidden" value="<?=$vtcc->$pos?>" name="<?=$pos?>">
                                                            <?php else:?>
                                                                <input type="hidden" value="0" name="<?=$pos?>">
                                                            <?php endif;?>
                                                        <?php endforeach;?>
                                                    <?php endif;?>

                                                    <?php if($component->type=="bien"):?>
                                                        <?php $possible_id=["id_bien","id_bien_caracteristique","id_personne_bien"]; ?>
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
                                                            <button class="btn btn-success btn-sm" type="submit">Enregistrer</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        <?php endif;?>
                                    </div>

                                    <div class="card-footer text-center">
                                        <a class="tout_voir" style="text-decoration:none; color:black!important" href="#"><i class="<?=icon("expand")?>"></i> Agrandir</a>
                                        <a class="pas_tout_voir" style="display:none;text-decoration:none; color:black!important" href="#"><i class="<?=icon("reduire")?>"></i> Réduire</a>
                                    </div>
                                </div>
                            <?php endfor;?>  
                        <?php endif;?>    
                    <?php endforeach;?>      
                </div>
            <?php endfor;?> 
        </div>
        <!-- end components -->
    </div>

    <!-- BEGIN FIL DISCUSSION -->
    <div class="col-lg-6">
        <?php
            $a_mail=NULL;
            $cc_mail=NULL;
            $cci_mail=NULL;
        ?>
        <div style="display:none" id="top_note_create" class="card mb-2">
            <div class="card-header">
                <h5>Nouvelle note</h5>
            </div> 
            <div id="container_form_note" class="card-body">
            </div>
        </div>

        <div id="top_tache" <?php if(is_null($oneTache)):?> style="display:none" <?php endif;?> class="card border-<?=$themes->tache->color?> mb-2"> 
            <div class="card-header">
                <h5><small class="text-<?=$themes->tache->color?>"><?=$themes->tache->icon?></small> Détail tâche</h5>
            </div>
            <div class="card-body">  
                <div style="display:none" class="loading text-center mt-5"><i class="fas fa-circle-notch fa-spin"></i> <br>Chargement</div>
                <div id="container_tache_create"  <?php if(is_null($oneTache)):?>style="display:none"<?php endif;?> class="loader">
                    <?php if(!is_null($oneTache)):?>
                        <?php echo $oneTache;?>
                    <?php endif;?>
                </div>
            </div>        
        </div>

        <div id="top_message" style="display:none" class="card border-amethyst mb-2"> 
            <div class="card-header">
                <h5>Nouveau message</h5>
            </div> 
            <div class="card-body">
                <?php //form envoi ?>
                <?php //echo $email_demandeur;?>
                <div class="card mb-2">
                    <div class="card-body">
                        <div id="loading_send" class="loading text-center my-5">
                            <i class="fas fa-circle-notch fa-spin"></i> <br>
                            Envoi du mail en cours…
                        </div>
                        <form id="form_send_message" method="post" action="<?=base_url()?>outlook/send_message">
                            <input id="is_brouillon" type="hidden" name="is_brouillon" value="0" >
                            <input id="id_mail_brouillon" type="hidden" name="id_mail_brouillon" value="0">
                            <div style="display:none" class="alert alert-danger alert_form_send_message"></div>
                            <input type="hidden" name="id_demande" value="<?=$id_demande?>">
                            <div class="form-group">
                                <label class="control-label"><b>*A</b></label>
                                <div id="output_to_mail" class="m-0 output_send_message"></div>
                                <input  name="to_mail" id="form_email" value="<?=$email_demandeur?>" type="text" class="form-control input-sm">
                            </div>
                            <div class="form-group mt-2">
                                <label class="control-label"><b>Cc</b></label>
                                <div id="output_cc_mail" class="m-0 output_send_message"></div>
                                <input name="cc_mail" id="form_cc" type="text" class="form-control input-sm">
                            </div>
                            <div class="form-group mt-2">
                                <label class="control-label"><b>Cci</b></label>
                                <div id="output_bcc_mail" class="m-0 output_send_message"></div>
                                <input name="bcc_mail" id="form_cci" type="text" class="form-control input-sm">
                            </div>
                            <div class="form-group mt-2">
                                <label class="control-label"><b>*Objet</b></label>
                                <div id="output_subject_msg" class="m-0 output_send_message"></div>
                                <div class="input-group mt-2">
                                    <span class="input-group-text" id="ref_message"></span>
                                    <input name="subject" id="form_sujet" type="text" class="form-control input-sm">
                                </div>
                            </div>
                            <input id="ref_message_input" name="ref_message" type="hidden">
                            <div class="form-group mt-2">
                                <label class="control-label"><b>*Message</b></label>
                                <div id="output_body_content" class="m-0 output_send_message"></div>
                                <textarea name="body_content" id="form_body" class="form-control input-sm">
                                </textarea>
                            </div>

                                <!-- container document join -->
                                <div class="card mt-2 js-sticky-widget">
                                <div class="card-header">
                                    <p class="card-title d-flex justify-content-between align-items-center">
                                        <b>Documents joints</b>
                                        <a style="display:none" id_demande="<?=$id_demande?>" href="#" class="ajouter_document_modal">Ajouter document à joindre</a>
                                    </p>
                                </div>
                                <div class="card-body" style="height: 30vh !important; overflow: auto">  
                                    <div class="loading text-center mt-5">
                                        <i class="fas fa-circle-notch fa-spin"></i> <br>
                                        Chargement
                                    </div>
                                    <div id="container_document_join"  href_base="<?=base_url()?>demande/liste_document_join/<?=$id_demande?>/" href_ajax="<?=base_url()?>demande/liste_document_join/<?=$id_demande?>/0" style="display:none" class="loader" ></div>
                                </div>
                                <div class="card-footer text-center">
                                    <a class="tout_voir" style="text-decoration:none; color:black!important" href="#"><i class="<?=icon("expand")?>"></i> Agrandir</a>
                                    <a class="pas_tout_voir" style="display:none;text-decoration:none; color:black!important" href="#"><i class="<?=icon("reduire")?>"></i> Réduire</a>
                                </div>
                            </div>   
                            <!-- container document join --> 

                            <!-- <button class="btn btn-success btn-sm mt-2">Envoyer</button>
                            <button id="message_new_cancel" class="btn btn-danger btn-sm mt-2">Annuler</button>-->
                        </form>
                    </div>
                </div>
            <?php //form envoi ?>
            </div>
        </div>

        <div class="card border-dark">  
            <div class="card-header">
                <div class="d-flex justify-content-between">
                    <h5>
                        Fil de discussion
                        <span id='inverser_ordre_fil' statut="asc" style='padding-right: 20px; cursor: pointer; padding-bottom: 10px'>
                            <small><i class="fa fa-sort" aria-hidden="true"></i> Inverser l'ordre</small>
                        </span> 
                    </h5>
                    <div class="text-end">
                        <button id_demande="<?=$id_demande?>" id="message_new" class="btn btn-info btn-sm text-white bt_redaction_message">Nouveau message</button>
                    </div>
                </div>
            </div>
            <div id="container_fil_message" style="height: 180vh !important; overflow: auto" class="card-body">  
                <div class="loading text-center mt-5"><i class="fas fa-circle-notch fa-spin"></i> <br>Chargement</div>
                <div id="container_fil" href_ajax="<?=base_url()?>demande/liste_fil/<?=$id_demande?>" style="display:none" class="loader"></div>
            </div>                        
        </div>                            
    </div>
    <!-- END FIL DISCUSSION -->

    <div class="col-lg-3">

        <!-- container document -->
        <div class="card">
            <div class="card-header border-top-<?=$themes->document->color?>">
                <h5 class="card-title d-flex justify-content-between align-items-center">
                    <span class="text-<?=$themes->document->color?>"><?=$themes->document->icon?></span>
                    Documents liés
                    <a id_demande="<?=$id_demande?>" href="#" class="gerer_document_modal text-dark">
                        <i class="far fa-window-restore"></i>
                    </a>
                    <?php if($typeDataView=="read"):?>  
                        <div class="btn-group btn_contextuel_menu_form_document">
                            <span style="cursor:pointer" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="<?=icon("menu_contextuel")?>"></i>
                            </span>
                            <ul class="dropdown-menu">
                                <li><a  id_demande="<?=$id_demande?>" href="#" class="dropdown-item gerer_document_modal">Gérer les documents de la demande</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a  id_demande="<?=$id_demande?>" href="#" class="dropdown-item ajouter_document_modal">Uploader des documents dans le CRM</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a  id_demande="<?=$id_demande?>" href="#" class="dropdown-item ajouter_document_modal_crm">Ajouter des documents du CRM à cette demande</a></li>
                            </ul>
                        </div>
                    <?php endif;?>
                </h5>
            </div>
            <div class="card-body" style="height: 30vh !important; overflow: auto">  
                <div class="loading text-center mt-5"><i class="fas fa-circle-notch fa-spin"></i> <br>Chargement</div>
                <div id="container_document" href_ajax="<?=base_url()?>demande/liste_document/<?=$id_demande?>" style="display:none" class="loader" ></div>
            </div>
            <div class="card-footer text-center">
                <a class="tout_voir" style="text-decoration:none; color:black!important" href="#"><i class="<?=icon("expand")?>"></i> Agrandir</a>
                <a class="pas_tout_voir" style="display:none;text-decoration:none; color:black!important" href="#"><i class="<?=icon("reduire")?>"></i> Réduire</a>
            </div>
        </div>   
        <!-- fin containr document -->

        <!-- container note -->
        <?php echo $messagerie->get_container_note("demande", $id_demande)?>
        <!-- fin container note -->


        <!-- container rdv -->
        <div id="liste_rdv" class="card mt-2 ">
            <div class="card-header border-top-<?=$themes->rdv->color?>">
                <h5 class="card-title d-flex justify-content-between align-items-center">
                    <span class="text-<?=$themes->rdv->color?>"><?=$themes->rdv->icon?></span>
                    Rendez-vous
                    <?php if($typeDataView=="read"):?>  
                        <div class="btn-group btn_contextuel_menu_form">
                            <span style="cursor:pointer" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="<?=icon("menu_contextuel")?>"></i>
                            </span>
                            <ul class="dropdown-menu">
                                <li><a href="<?=base_url()?>rdv/form_rdv/<?=$id_demande?>" class="dropdown-item" id="ajouter_rdv">Ajouter un rendez-vous</a></li>
                            </ul>
                        </div>
                    <?php endif;?>
                </h5>
            </div>
            <div class="card-body" style="height: 30vh !important; overflow: auto">  
                <div class="loading text-center mt-5"><i class="fas fa-circle-notch fa-spin"></i> <br>Chargement</div>
                <div id="container_rdv" href_ajax="<?=base_url()?>demande/liste_rdv/<?=$id_demande?>" style="display:none" class="loader" ></div>
            </div>
            <div class="card-footer text-center">
                <a class="tout_voir" style="text-decoration:none; color:black!important" href="#"><i class="<?=icon("expand")?>"></i> Agrandir</a>
                <a class="pas_tout_voir" style="display:none;text-decoration:none; color:black!important" href="#"><i class="<?=icon("reduire")?>"></i> Réduire</a>
            </div>
        </div>
        <!-- fin container rdv -->

        <!-- container tache -->
        <div id="liste_tache" class="card mt-2">
            <div class="card-header border-top-<?=$themes->tache->color?>">
                <h5 class="card-title d-flex justify-content-between align-items-center">
                    <span class="text-<?=$themes->tache->color?>"><?=$themes->tache->icon?></span>
                    Tâches
                    <?php if($typeDataView=="read"):?>  
                        <div class="btn-group btn_contextuel_menu_form">
                            <span style="cursor:pointer" class="dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="<?=icon("menu_contextuel")?>"></i>
                            </span>
                            <ul class="dropdown-menu">
                                <li><a href="<?=base_url()?>tache/form_tache/<?=$id_demande?>" class="dropdown-item" id="ajouter_tache">Ajouter une tâche</a></li>
                            </ul>
                        </div>
                    <?php endif;?>
                </h5>
            </div>
            <div style="height: 30vh !important; overflow: auto" class="card-body">  
                <div class="loading text-center mt-5"><i class="fas fa-circle-notch fa-spin"></i> <br>Chargement</div>
                <div id="container_tache" href_ajax="<?=base_url()?>demande/liste_tache/<?=$id_demande?>" style="display:none" class="loader" ></div>
            </div>
            <div class="card-footer text-center">
                <a class="tout_voir" style="text-decoration:none; color:black!important" href="#"><i class="<?=icon("expand")?>"></i> Agrandir</a>
                <a class="pas_tout_voir" style="display:none;text-decoration:none; color:black!important" href="#"><i class="<?=icon("reduire")?>"></i> Réduire</a>
            </div>
        </div>
        <!-- fin container rdv -->
    </div>
</div>
<!-- input hidden to declare update or insert -->
<input type="hidden" value="<?=$typeDataView?>" name="typeDataView">
<input type="hidden" value="<?=$id_demande?>" name="id_demande">

<?php $this->endSection();?>