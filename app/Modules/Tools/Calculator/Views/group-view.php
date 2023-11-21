<?php $this->extend('\Layout\index'); ?>


<!-- NAVBARSUB TITLE -->
<?php $this->section('navbarsub'); ?>
    <div class="container-fluid py-2 d-flex justify-content-between align-items-center border-bottom-<?php echo $themes->calculator->color;?> border-2">
        <div>
            <div class="d-flex">
                <div class="me-2"> <?php echo $themes->calculator->icon;?> </div>
                <label>
                    <?php echo $titleView;?> :
                    <b> <small> <?php echo $group->path;?> </small> </b>
                </label>
                <?php echo view('\DataView\buttons/info-button', ['target_id'=>'GroupInfoCollapse']);?>
            </div>
            <div id="GroupInfoCollapse" class="collapse text-end px-2">
                <?php echo get_info_view($group->updated_at, $group->updated_by, $group->created_at, $group->created_by);?>
            </div>
        </div>
        <div class="d-flex align-items-center"> 
            <button type="button" class="form_read btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                title="Editer le groupe de travaux"
                onclick="js_form_update(this);"
                > <?php echo fontawesome('edit');?> 
            </button>
            <?php if($Autorisation->is_autorise('calculator_d', $group->created_by)):?>
                <button class="ban_deleteForm form_update btn btn-sm btn-outline-danger ms-2"
                    title="Supprimer le groupe de travaux"
                    style="display: none;"
                    id_delete="<?php echo $group->id_group;?>"
                    href="<?php echo base_url("calculator/group/$group->id_group/delete");?>"
                    text_alert="le groupe de travaux en cours"
                    >
                    <?php echo fontawesome('trash-alt');?>
                </button>
            <?php endif;?>
            <a role="button" class="form_update btn btn-sm btn-outline-secondary ms-2"
                style="display: none;"
                title="Annuler les modifications sur le groupe de travaux"
                href="<?php echo current_url();?>"
                > <?php echo fontawesome('undo');?> 
            </a>
            <form id="<?php echo $form_id;?>" method="post" action="<?php echo base_url('calculator/group/' . $group->id_group);?>">
                <button type="submit"
                    class="form_update btn btn-sm btn-success ms-2"
                    onclick="waiting_start(this);"
                    style="display: none;"
                    title="Sauvegarder les modifications sur le groupe de travaux"
                    form="<?php echo $form_id;?>" 
                    href="<?php echo current_url();?>"
                    > <?php echo fontawesome('save');?> 
                </button>
            </form>
            <a role="button" class="btn btn-sm btn-<?php echo $themes->calculator->color;?> ms-2"
                href="<?php echo base_url('calculator/groups');?>"
                title="Retourner à la liste des groupes de travaux"
                >
                <?php echo fontawesome('turn-up');?>
                <?php echo $themes->calculator_group->icon;?>
            </a> 
        </div>
    </div>
<?php $this->endSection(); ?>

<!-- SCRIPT FOOT -->
<?php $this->section('script_foot_injected'); ?>
    <?php echo view('Calculator\js/js_calculator');?>
    <?php echo view('Tesorus\js/js_tesorus');?>
<?php $this->endSection(); ?>

<!-- ------------------------------------------------------------------- -->
<!-- ------------------------------------------------------------------- -->

<!-- BODY -->
<?php $this->section('body');?>

<div class="row">
    <div class="col-6">
        <div class="card mb-4">
            <div class="card-header">
                <?php echo $themes->calculator_group->icon;?>
                Infos générales
            </div>
            <div class="card-body">
                <?php echo view('Calculator\group-form');?>
            </div>
        </div>
    </div>
    <div class="col-6">
        <div class="card mb-4">
            <div class="card-header">
                <?php echo $themes->calculator_post->icon;?>
                Postes associés
            </div>
            <div class="card-body">
                <?php if(!empty($group->group_roads_form)) echo $group->group_roads_form;?>
            </div>
        </div>
    </div>
</div>


<?php $this->endSection()?>
