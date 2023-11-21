<?php $this->extend('Layout\index'); ?>

<?php $this->section("body"); ?>

<?php if(session("notification")):?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
         <i class="<?=icon("success-notification")?>"></i>  <?=session()->getFlashdata("notification")?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif;?>

<h3 class="fs-4">Modélisation</h3>
<div class="card border-top-primary mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0 text-align-left">Liste des entités du système</h5>
    </div>
    <div class="card-body">
        <div class="row">
            <?php foreach($entities as $entity):?>
                <div class="col-sm-12 col-md-3 mb-2">
                    <div class="card">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-0 text-align-left">
                                <?=ucfirst($entity->label)?>
                            </h5>
                            <a style="display:none" href="<?=base_url()?>" class="card-link btn btn-sm btn-primary mb-2">Config</a>
                            <a href="<?=base_url()?>/modelisation/list_fields/<?=$entity->type?>" class="card-link btn btn-sm btn-primary mb-2">Champs</a>
                            <?php
                            
                                switch($entity->type):
                                    case "registration":
                                        $urifiche="inscription";
                                        break;

                                   default:
                                            $urifiche=$entity->type;
                                            break;      


                                endswitch;    


                            ?>
                            <a href="<?=base_url()?>/<?=$entity->type?>/modelisation<?=$urifiche?>" class="card-link btn btn-sm btn-primary mb-2">Fiche</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;?> 
        </div>      
    </div>
</div>  
<?php $is_formulaire=FALSE; if($is_formulaire):?>
<div style="display:none" class="card border-top-primary mb-4">
    <div class="card-header">
        <h5>Liste des formulaire d'inscription</h5>
    </div>
    <div class="card-body">
       <?php if(count($injectedForms)>0):?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover table-sm">
                    <thead>
                        <th></th>
                        <th>Titre</th>
                        <th>Indexes</th>
                        <th>Conditions</th>
                        <th></th>
                        <th></th>
                    </thead>
                    <tbody>
                        <?php foreach($injectedForms as $injectedForm):?>
                            <tr style="text-align:left !important">
                                <td>
                                <button text_alert="le modèle de formulaire    
                                            <?=$injectedForm->title?>"
                                    
                                    id_delete="<?=$injectedForm->id_injected_form?>" href="<?=base_url()?>/delete/deleteInjectedForm" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> 
                                </button>

                                </td>
                                <td><span class="titleForm"><?=nl2br($injectedForm->title)?></span></td>
                                <td><?=str_replace([",","@#<hr>"],[", ","---"],$injectedForm->fields)?></td>
                                <td>

                                    <?php foreach($conditions as $condition):?>
                                        <?php if(!empty($injectedForm->$condition)):?>
                                            <span class="badge bg-secondary"><?=str_replace("activity","article",$condition)?>=<?=$injectedForm->$condition?></span>
                                        <?php endif;?>
                                    <?php endforeach;?>


                                        <?php if(isset($has_filtre_spip)&&$has_filtre_spip==1&&!empty($injectedForm->filtre_spip_labels)):
                                                $labels_spip=explode(",",$injectedForm->filtre_spip_labels);
                                                foreach($labels_spip as $label_spip):
                                        ?>
                                             <span class="badge bg-secondary mr-1 mb-1">SPIP <?=supprimer_numero($label_spip)?></span>

                                        <?php endforeach; 
                                        
                                        endif;?>    
                                   

                                </td>
                                <td><a class="card-link btn btn-sm btn-primary" href="<?=base_url()?>/modelisation/injectedForm/<?=$injectedForm->id_injected_form?>">Editer</a></td>
                                <td><a class="card-link btn btn-sm btn-primary previsualisationInjectedFormOnly" href="<?=base_url()?>/modelisation/injectedFormIframeOnly/<?=$injectedForm->id_injected_form?>">Prévisualiser</a></td>
                            </tr>
                        <?php endforeach;?>    
                    </tbody>
                </table>
            </div>    
        <?php else:?>

            <h3 class="text-center">Aucun formulaire trouvé</h3>

       <?php endif;?> 
       <div class="mt-4 text-center"><a class="btn btn-sm btn-primary" href="<?=base_url()?>/modelisation/injectedForm">Ajouter un formulaire</a></div>
    </div>
</div>  

<?php endif;?>


<?php $this->endSection(); ?>