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
            <a  class="btn btn-sm btn-<?php echo $themes->modelisation->color;?>" 
                href="<?=base_url("modelisation/$entity->type/field/new");?>"
                >
                <?php echo fontawesome('plus');?> 
                Créer un nouveau champ <b> <?php echo $entity->label?> </b>
            </a>
            <a role="button" class="btn btn-<?php echo $themes->modelisation->color;?> btn-sm ms-2" 
                href="<?php echo base_url("modelisation/$entity->type/fiche");?>"
                title="Aller à la fiche cette entité"
                >
                <?php echo fontawesome('long-arrow-alt-right')?> Aller à la fiche associée
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

<div class="row">
    <?php foreach($fields as $field):?>
        <div class="col-sm-12 col-md-3 mb-2 ban_element_container">
            <div class="card">
                <div class="card-body">
                    <div class="mb-2">
                        <h5 class="mb-0"> <?php echo $field->label?> </h5>
                        <small> (index: <?=$field->field_index?>) </small>
                    </div>
                    <a class="btn btn-sm btn-<?php echo $themes->modelisation->color;?>"
                        href="<?=base_url("modelisation/$entity->type/field/update/$field->field_index")?>"
                        >
                        <?php echo fontawesome('edit');?> Modifier
                    </a>
                    <button class="ban_deleteForm card-link btn btn-sm btn-secondary ms-2"
                        text_alert="le champ <?php echo $field->field_index?>"
                        id_delete="<?=$field->field_index?>"
                        href="<?php echo base_url("delete/deleteFieldIndex")?>"
                        >
                        <i class="<?=icon("delete")?>"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
    <?php endforeach;?> 
</div>      

<div class="container-fluid fixed-bottom text-center p-2 footer-form border-top"></div> 
 

<?php $this->endSection(); ?>