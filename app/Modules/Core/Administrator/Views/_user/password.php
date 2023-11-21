<?php $this->extend("Layout\user-index"); ?>

<?php $this->section('navbarsub'); ?>
    <div class="container-fluid d-flex justify-content-between align-items-center py-2 border-bottom-<?php echo $themes->user->color;?>">
        <div class="h5 mb-0">
            <i class="<?php echo icon('password'); ?>"></i>
            <?php echo $titleView;?>
            <img src="<?php echo base_url(AVATAR_PATH . $user->avatar);?>"
                alt="Avatar de l'utilisateur" 
                class="img-tiny rounded-circle avatar"
            />
        </div>
        <div class="d-flex align-items-center">
            <button type="submit" form="UserPasswordForm" class="btn btn-sm btn-<?php echo $themes->user->color;?> ms-2">
                <?php echo fontawesome('save');?> <?php echo lang('Buttons.save'); ?>
            </button>
            <a class="btn btn-sm btn-outline-secondary ms-2" href="<?php echo current_url();?>">
                <?php echo fontawesome('undo');?> <?php echo lang('Buttons.cancel'); ?>
            </a>
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
    <form id="UserPasswordForm" action="<?php echo base_url('user/password'); ?>" method="post" enctype="multipart/form-data">
        <div class="row mb-2">
            <label for="new_password" class="col-sm-3 col-form-label">
                <i class="<?php echo icon('password'); ?>" style="width:20px;"></i> Nouveau mot de passe
            </label>
            <div class="col-sm-9">
                <input type="password" class="form-control" id="new_password" name="new_password" value="<?php echo set_value('password'); ?>" placeholder="...">
                <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'new_password') : '' ?></span>
            </div>
        </div>
        <div class="row mb-2">
            <label for="confirm_password" class="col-sm-3 col-form-label">
                <i class="<?php echo icon('password'); ?>" style="width:20px;"></i> Confirmer le mot de passe
            </label>
            <div class="col-sm-9">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" value="<?php echo set_value('password'); ?>" placeholder="...">
                <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'confirm_password') : '' ?></span>
            </div>
        </div>
        <input type="hidden" name="id_user" type="id_user" value="<?=$user->id ?>">
    </form>
<?php $this->endSection(); ?>


