<?php $this->extend('Layout\index'); ?>

<?php $this->section("script_foot_injected"); ?>
    <?php echo view('Modelisation\js_modelisation');?>
<?php $this->endSection(); ?>

<?php $this->section('navbarsub');?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->modelisation->color;?> border-2">
        <div>
            <?php echo $titleView;?>
        </div>
        <div class="d-flex">
            <button type="submit"
                form="form-entity"
                class="btn btn-<?php echo $themes->modelisation->color;?> btn-sm ms-2"
                >
                <i class="<?=icon("save")?>"></i> Enregistrer
            </button>
            <a role="button" class="btn btn-secondary btn-sm ms-2" 
                href="<?php echo current_url();?>"
                >
                <i class="<?=icon("cancel")?>"></i> Annuler
            </a>
            <a role="button" class="btn btn-<?php echo $themes->modelisation->color;?> btn-sm ms-2" 
                href="<?php echo base_url("modelisation/$entity->type/fields");?>"
                title="Aller à la liste des champs associés à cette entité"
                >
                <?php echo fontawesome('long-arrow-alt-right')?> Aller à la liste des champs associés
            </a>
            <a role="button" class="btn btn-<?php echo $themes->modelisation->color;?> btn-sm ms-2" 
                href="<?php echo base_url("modelisation");?>"
                title="Retourner à la liste des entités"
                >
                <?php echo fontawesome('turn-up')?> Retourner à la liste des entités
            </a>
        </div>
    </div>
  
<?php $this->endSection();?>

<?php $this->section('body');?>

    <!-- block error -->
    <?php if(!empty($validation->getErrors())):?>
        <div class="alert alert-danger" role="alert"> <strong><i class="<?=icon("triangle_warning")?>"></i></strong> <?=count($validation->getErrors())?> erreur<?=plurial_s(count($validation->getErrors()));?> à corriger</div>
    <?php endif;?>

    <!-- block notification -->
    <?php if(session()->getFlashdata("notification")):?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong><i class="<?=icon("confirmation_ok")?>"></i></strong> <?=session()->getFlashdata("notification")?>.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif;?>

    <form id="form-entity" class="form_gestion_entity" method="post" autocomplete="off" action="<?php echo base_url("modelisation/$entity->type/fiche")?>">
        <div class="row mb-2 load_ajax">

            <!-- columm possible -->
            <?php for($i=1;$i<3;$i++):?>
                <div id="column<?=$i?>" num_column="<?=$i?>" class="col-lg-6 column-sortable">
                    <?php foreach($components as $component):?>
                        <?php if($component->column==$i):?>

                            <?php if($component->type=='osm_map'): continue;
                            elseif($component->type=='google_map'): continue;
                            elseif($component->type=='note'): continue;
                            endif;
                            
                            if(isset($component->name)&&!empty($component->name)): $tableOfValue=$component->name;
                            else: $tableOfValue=$component->type;
                            endif;
                            
                            if(isset($$tableOfValue)): $value_sql=$$tableOfValue;
                            else: $value_sql=NULL;
                            endif;
                            
                            if(!$component->is_always_visible && empty($value_sql)): continue;
                            endif;
                            
                            $value_transfert=NULL;
                            $count_clone=1;?>
                            
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
                                            <span class="text-<?php echo $themes->$type_component->color;?>">
                                                <?php echo $themes->$type_component->icon;?>
                                            </span>
                                            <?php echo $component->title?>
                                            <i style="cursor:grab" class="<?=icon("moveHorizontal");?> move-sortable-column"></i>
                                        </h5>
                                    </div>

                                    <div class="card_component card-body fields-sortable">
                                        <?php if($component->is_insert_permit==1):?>
                                        
                                            <?php echo view("Modelisation\dataview",[
                                                "validation"=>$validation,
                                                "fields"=>$fields,
                                                "value"=>$vtcc,
                                                "indexes"=>explode(",",trim($component->fields)),
                                                "num_container"=>$component->id_components,
                                                ])
                                            ?>
                                          
                                        <?php else:?>
                                         
                                            <?php if(!empty($vtcc)):?>
                                                <?php echo view("Modelisation\dataview",[
                                                    "validation"=>$validation,
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
                                        <input name="colIndex<?=$i?>@order@<?=$component->id_components?>@order@fields[]" class="fields_order" type="hidden" value="<?=$component->fields?>">    
                                    </div>
                                  
                               
                                    <div class="card-body pt-0 add_fields">
                                        <hr>
                                        <div class="row mb-2">
                                            <div class="col-lg-12">
                                                <span url="<?=base_url("modelisation/list_add_field/$component->type")?>" class="link_add_fields" state="close" style="cursor:pointer"><i class="<?=icon("plus-field")?>"></i> Ajouter un champ</span>
                                            </div>
                                        </div>
                                        <div style="display:none" type="<?=$component->type?>" class="possible_fields">
                                            <?=$ModelisationLibrary->getListAddField($component->type,$entity->ref);?>
                                        </div>
                                    </div>
                                </div>
                            <?php endfor;?>  
                        <?php endif;?>    
                    <?php endforeach;?>      
                </div>
            <?php endfor;?> 
        </div>
    </form>

<?php $this->endSection();?>