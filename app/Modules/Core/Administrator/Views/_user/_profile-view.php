<?php $this->extend("Layout\user-index"); ?>

<?php $this->section('navbarsub'); ?>
    <div class="container-fluid d-flex justify-content-between align-items-center py-2 border-bottom-<?php echo $themes->user->color;?>">
        <div class="h5 mb-0">
            <i class="<?php echo icon('profile'); ?>"></i>
            <?php echo $titleView;?>
            <img src="<?php echo base_url(AVATAR_PATH . $user->avatar);?>"
                alt="Avatar de l'utilisateur" 
                class="img-tiny rounded-circle avatar"
            />
        </div>
        <div class="d-flex">
            <a class="btn btn-<?php echo $themes->user->color;?> btn-sm ms-2" 
                href="<?php echo base_url("user/profile?$id_user_get");?>"
                >
                <?php echo fontawesome('edit');?> <?php echo lang('Buttons.edit'); ?>
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
    <div class="row">
        <div class="col-md-3 col-sm-6 col-xs-12 text-center ">
            <div class="text-center m-2">
                <img src="<?php echo base_url(AVATAR_PATH . $user->avatar);?>" 
                    alt="Avatar" 
                    class="img-fluid img-thumbnail" 
                />
                <div class="text-center m-1">
                    <?php if(!empty($user->is_actif)): ?>
                        <span class="badge bg-success"><?php echo lang('UserProfile.actif'); ?></span>
                    <?php else: ?>
                        <span class="badge bg-danger"><?php echo lang('UserProfile.inactif'); ?></span>
                    <?php endif; ?>
                    <span class="badge bg-dark"><?php echo $user->role_label; ?></span>
                </div>
            </div>
        </div>
        <div class="col-md-9 col-sm-6 col-xs-12">
            <div class="row mb-2">
                <label for="is_salle" class="col-sm-3 col-form-label">
                    Type
                </label>
                <div class="col-sm-9">
                    <input type="phone" disabled class="form-control bg-white border-white" id="phone" value="<?php echo $user->phone;?>" placeholder="">
                </div>
            </div>
            <div class="row mb-2">
                <label for="nom" class="col-sm-3 col-form-label">
                    Nom
                </label>
                <div class="col-sm-9">
                    <input type="nom" disabled class="form-control bg-white border-white" id="nom" value="<?php echo $user->nom; ?>" placeholder="">
                </div>
            </div> 
            <div class="row mb-2">
                <label for="nom" class="col-sm-3 col-form-label">
                    Prénom
                </label>
                <div class="col-sm-9">
                    <input type="nom" disabled class="form-control bg-white border-white" id="nom" value="<?php echo $user->prenom; ?>" placeholder="">
                </div>
            </div> 
            <div class="row mb-2">
                <label for="email" class="col-sm-3 col-form-label">
                    <?php echo lang('UserProfile.email'); ?>
                </label>
                <div class="col-sm-9">
                    <input type="email" disabled class="form-control bg-white border-white" id="email" value="<?php echo $user->email; ?>" placeholder="">
                </div>
            </div> 
            <div class="row mb-2">
                <label for="website" class="col-sm-3 col-form-label">
                    <?php echo lang('UserProfile.website'); ?>
                </label>
                <div class="col-sm-9">
                    <input type="text" disabled class="form-control bg-white border-white" id="website" value="<?php echo $user->website;?>" placeholder="">
                </div>
            </div>
            <div class="row mb-2">
                <label for="phone" class="col-sm-3 col-form-label">
                    <?php echo lang('UserProfile.phone'); ?>
                </label>
                <div class="col-sm-9">
                    <input type="phone" disabled class="form-control bg-white border-white" id="phone" value="<?php echo $user->phone;?>" placeholder="">
                </div>
            </div>

        </div>
    </div>

<?php $this->endSection(); ?>
