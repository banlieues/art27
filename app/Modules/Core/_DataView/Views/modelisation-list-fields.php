<?php $this->extend('Layout\index'); ?>
<?php $this->section("body"); ?>
<?php if(session("notification")):?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
         <i class="<?=icon("success-notification")?>"></i>  <?=session()->getFlashdata("notification")?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif;?>


<div class="card border-top-theme mb-4">
    <div class="card-header sticky_button bg-light">    
        <div class="row">
            <div class="col-lg-auto p-1 align-self-center">
                <h3 class="card-title mb-0"><?php echo $titleView; ?></h3>
            </div>  
            <div class="col-lg-auto mx-auto p-1 align-self-center"> 
            </div>    
            <div class="col-lg-auto p-1 align-self-center text-lg-end">
                    <a style="text-align:right !important" href="<?=base_url()?>/modelisation/editfield/insert/<?=$entities->type?>" class="btn btn-secondary btn-sm"><i class="<?=icon("modelisation")?>"></i> Créer un nouveau champ <?=$label_entity?> </a>
            </div> 
        </div>        
    </div>
    <div class="card-body text-center">
        <div class="row">
            <?php foreach($fields as $field):?>
                <div class="col-sm-12 col-md-3 mb-2 ban_element_container">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?=$field->label?><br>
                                <i><span style="font-size:12px !important">(index: <?=$field->field_index?>)</span></i>
                            </h5>
                            <div class="row">
                                <div style="text-align:right !important" class="col-6 text-right">
                                        <?php if(!empty($field->link_modif)):?>
                                            <a href="<?=base_url()?>/<?=$field->link_modif?>" class="btn btn-sm btn-dark">Gérer</a>

                                        <?php else:?>    
                                        <a href="<?=base_url()?>/modelisation/editfield/update/<?=$entities->type?>/<?=$field->field_index?>" class="btn btn-sm btn-dark">Modifier</a>
                                        <?php endif;?>
                                </div>
                                <div class="col-6 text-left">
                                        <button text_alert="le champ <?=$field->field_index?>" id_delete="<?=$field->field_index?>" href="<?=base_url()?>/delete/deleteFieldIndex" class="ban_deleteForm card-link btn btn-sm btn-danger text-nowrap"><i class="<?=icon("delete")?>"></i> Supprimer</button>
                                
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach;?> 
        </div>      
    </div>
</div>  
<div class="container-fluid fixed-bottom text-center p-2 footer-form border-top">
 
 
     
  
</div> 
 

<?php $this->endSection(); ?>