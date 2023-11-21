<?php $validation = \Config\Services::validation(); ?>
<?php $autorisationManager = \Config\Services::autorisationModel();?>

<?php $dataViewConstructorModel = \Config\Services::dataViewConstructorModel();?>

<?php $this->extend("\Tamo\layout/index"); ?>
<?php $this->section("body"); ?>

<!-- block form if mode update or create -->
<?php if($typeDataView!="read"&&$typeDataView!="modelisation"):?>
    <form id="form-entity" method="post" action="<?=base_url("contacts/save")?>">
<?php elseif($typeDataView=="modelisation"):?>
    <form id="form-entity" method="post" autocomplete="off"  action="<?=base_url("contact/save_modelisation")?>">
<?php endif;?>
<!-- block error -->
<?php if(!empty($validation->getErrors())):?>
    <div class="alert alert-danger" role="alert"> <strong><i class="<?=icon("triangle_warning")?>"></i></strong> <?=count($validation->getErrors())?> erreur<?=plurial_s(count($validation->getErrors()));?> à corriger</div>
<?php endif;?>

<!-- block notification -->
<?php if(session()->getFlashdata("notification")):?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong><i class="<?=icon("confirmation_ok")?>"></i></strong> <?=session()->getFlashdata("notification")?>.
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif;?>

<!-- block non find -->
<?php if($typeDataView!="create"&&empty($contact)&&$typeDataView!="modelisation"):?>
    <div class="text-center mt-5">
        <h1><i class="<?=icon("triangle_warning")?>"></i> Pas de fiche associée à cet id</h1>
    </div>
    <?php $this->endSection(); ?>
<?php return;?>    
<?php endif;?>



<!--block title -->
<div class="card-header p-1 p-xl-1 sticky_button bg-light">
    <div class="row">
        <div class="col-auto align-self-center">
            <h3 class="fs-4"><?php echo $titleView; ?></h3>
        </div>
        <div class="col align-self-center">
            <div class="text-end">
                <?php if($typeDataView=="read"):?>
                    <?php //if($autorisationManager->is_autorise("contacts_u")):?>
                        <a class="btn btn-amethyst btn-sm" href="<?=base_url()?>/contact/formContact/<?=$contact->id_contact?>"><i class="<?=icon("contact")?>"></i> Modifier la fiche du contact</a>
                    <?php //endif;?>  
                    <?php //if($autorisationManager->is_autorise("registrations_c")):?>
                        <a class="btn btn-success btn-sm" href="<?=base_url()?>/registrations/formInscription/0/0/0/<?=$contact->id_contact?>?uri=<?=full_url()?>"><i class="<?=icon("add")?>"></i> Inscription</a>
                    <?php //endif;?>
                <?php else:?>
                    <span class="zone-button-form">
                        <button class="btn btn-success btn-sm" type="submit"><i class="<?=icon("contact")?>"></i> Enregistrer</button>
                        <?php if($typeDataView=="update"):?>   
                            <a class="btn btn-danger btn-sm" href="<?=base_url()?>/contact/fiche/<?=$contact->id_contact?>"><i class="<?=icon("contact")?>"></i> Annuler</a>
                        <?php else:?>
                            <a class="btn btn-danger btn-sm" href="<?=base_url()?>/list-contact/"><i class="<?=icon("contact")?>"></i> Annuler</a>
                        <?php endif;?>
                    </span>
                    <span style="display:none" class="zone-submit-loading"> <i class="fas fa-circle-notch fa-spin"></i> Veuillez patientez</span>  
                <?php endif;?> 
            </div>  
        </div>      
    </div>
</div>

<!-- block onglet -->

<?php if($typeDataView=="read"):?>
    <ul class="nav nav-tabs">

        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="<?=base_url()?>/contact/fiche/<?=$contact->id_contact?>">
                <i class="<?=icon("contact")?>"></i> Données générales
            </a>
        </li>
   

    </ul>
<?php endif;?>

<!-- block creation&modification date -->
<?php if($typeDataView!="create"&&$typeDataView!="modelisation"):?>
<?php if($typeDataView!="create"&&$typeDataView!="modelisation"):?>
    <div class="my-1">
        <small>
            <i>
                <?php if(!empty($contact->created_at)):?>
                    <b>Date de création:</b> <?=convert_date_en_to_fr_with_h($contact->created_at,true); ?> 
                <?php endif;?>     
                <?php if(!empty($contact->updated_at)):?>
                    | <b>Dernière modification:</b> <?=convert_date_en_to_fr_with_h($contact->updated_at,true); ?>
                <?php endif;?>
        </i>
        </small>
    </div>
<?php endif;?>
<?php endif;?>





<!--- view of date -->




<div class="row mb-2">
<!-- columm possible -->
    <?php for($i=1;$i<3;$i++):?>
        <div id="column<?=$i?>" num_column="<?=$i?>" class="col-lg-6 <?php if($typeDataView=="modelisation"):?>column-sortable<?php endif;?>">
           
                <?php foreach($components as $component):?>
                    <?php if($component->column==$i):?>
                        <?php 
                                    $tableOfValue=str_replace(["activities","contacts"],["activite","contact"],$component->type);
                                    $value_sql=$$tableOfValue;
                                ?>   

                                
                                <?php if($typeDataView=="read"||$typeDataView=="update"):?>

                                    <?php //je regarde si on a valeur unique ou non 

                                                                if(is_array($$tableOfValue))
                                                                {
                                                                
                                                                    $value_transfert=$value_sql;
                                                                    $count_clone=count($value_sql);
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
                    
                        <div class="card flex-fill mb-4 card_sortable">
                            <div class="card-header border-top-<?=colorBorder($component->type)?>">
                                <h5 class="card-title d-flex justify-content-between align-items-center"><i class="<?=icon($component->type)?>"></i> <?=$component->title?> <?php if($count_clone>1):?>#<?=$cc+1?><?php endif;?>
                                <?php if($typeDataView=="modelisation"):?>    
                                    <i style="cursor:grab" class="<?=icon("moveHorizontal");?> move-sortable-column"></i>
                                <?php endif;?>
                                </h5>
                            </div>
                            <div class="card-body <?php if($typeDataView=="modelisation"):?>fields-sortable<?php endif;?>">
                                <?php if(isset($fields["id_contact"]["label"])&&$typeDataView=="update"&&$component->rank==1):?>
                                    <div class="row mb-2">
                                        
                                        <div class="col-lg-6"><b><?=$fields["id_contact"]["label"];?></b></div>
                                        <div class="col"><?=$contact->id_contact?></div>
                                    </div>
                                <?php endif;?>
                               
                                 <?php if(isset($value_transfert[$cc])):
                                    $vtcc=$value_transfert[$cc];
                                 else:
                                    $vtcc=NULL;
                                 endif;
                                ?>
                             
                                <?=view("DataView\Views\get-dataView",[
                                    "validation"=>$validation,
                                    "typeDataView"=>$typeDataView,
                                    "fields"=>$fields,
                                    "value"=>$vtcc,
                                    "indexes"=>explode(",",trim($component->fields))
                                    
                                    ])
                                ?>  
                              


                                 <?php if($typeDataView=="modelisation"):?>
                                        <input name="colIndex<?=$i?>@order@<?=$component->id_components?>@order@fields[]" class="fields_order" type="hidden" value="<?=$component->fields?>">    
                                <?php endif;?>
                            </div>
                            <?php if($typeDataView=="modelisation"):?>        
                                <div class="card-body pt-0 add_fields">
                                    <hr>
                                    <div class="row mb-2">
                                        <div class="col-lg-12">
                                      
                                            <span url="<?=base_url()?>/dataview/list_add_field/<?=$component->type?>/contact" class="link_add_fields" state="close" style="cursor:pointer"><i class="<?=icon("plus-field")?>"></i> Ajouter un champ</span>
                                        </div>
                                    </div>
                                    <div type="<?=$component->type?>" class="possible_fields">
                                         <?=$dataView->getListAddField($component->type,"contact");?>
                                    </div>
                                </div>
                            <?php endif;?>
                    
                        </div>
                <?php endfor;?>

                    <?php endif;?>    
                <?php endforeach;?>      
        </div>
    <?php endfor;?>    
</div>
<!-- end view data -->
<!-- input hidden to declare update or insert -->
<input type="hidden" value="<?=$typeDataView?>" name="typeDataView">
<input type="hidden" value="<?=$id_contact?>" name="id_contact">



<?php if($typeDataView!="read"):?>     
</form>
<?php endif;?>

<?php $this->endSection(); ?>
