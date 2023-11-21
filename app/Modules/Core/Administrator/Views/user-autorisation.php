<?php $this->extend("Layout\user-index"); ?>

<?php $this->section('navbarsub'); ?>
    <div class="container-fluid d-flex justify-content-between align-items-center py-2 border-bottom-<?php echo $themes->user->color;?>">
        <div class="h5 mb-0">
            <?php echo $themes->autorisation->icon;?>
            <?php echo $titleView;?>
            <?php if($user->id!=session('loggedUserId')):?>
                <img src="<?php echo base_url(AVATAR_PATH . $user->avatar);?>"
                    alt="Avatar de l'utilisateur" 
                    class="img-tiny rounded-circle avatar"
                />
            <?php endif;?>
        </div>
        <div class="d-flex">
            <?php if($Autorisation->is_autorise("autorisation_u")):?>
                <button type="button"
                    class="form_read btn btn-sm btn-<?php echo $themes->user->color;?> ms-2" 
                    title="Editer les autorisations"
                    <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                    onclick="js_form_update(this);"
                    >
                    <?php echo fontawesome('edit');?>
                    </button>
                <button type="submit"
                    form="AutorisationForm"
                    class="form_update btn btn-sm btn-<?php echo $themes->user->color;?> ms-2"
                    onclick="waiting_start(this);"
                    title="Enregistrer"
                    <?php if(in_array($typeDataView, ['read'])):?> style="display: none;" <?php endif;?>
                    >
                    <?php echo fontawesome('save');?>
                </button>
                <a href="<?php echo base_url("user/autorisation?$id_user_get")?>" 
                    class="form_update btn btn-sm btn-outline-secondary ms-2"
                    title="Annuler les modifications"
                    <?php if(in_array($typeDataView, ['read'])):?> style="display: none;" <?php endif;?>
                    >
                    <?php echo fontawesome('undo');?>
                </a>
            <?php endif;?>
            <a role="button"
                class="btn btn-sm btn-<?php echo $themes->user->color;?> ms-2"
                href="<?php echo base_url('user/list'); ?>"
                title="Aller à la liste des utilisateurs"
                >
                <?php echo fontawesome('turn-up');?> <?php echo $themes->user->icon;?>
            </a>
        </div>
    </div>
<?php $this->endSection(); ?>

<?php $this->section("user-body"); ?>

<form id="AutorisationForm" method="post" action="<?php echo base_url("user/autorisation?$id_user_get")?>">
    <?php echo view('Administrator\user-autorisation-table');?>
</form>

<?php $this->endSection(); ?>
