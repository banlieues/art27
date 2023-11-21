<?php $this->extend("Layout\user-index"); ?>

<?php $this->section("user-body"); ?>

<div class="float-end">
    <a class="btn btn-primary btn-sm" 
        <?php if($user->id!=session('loggedUserId')):?>
            href="<?php echo base_url("user/profile/edit?id_user=$user->id");?>"
        <?php else:?>
            href="<?php echo base_url("user/profile/edit");?>"
        <?php endif;?>
        >
        <i class="<?php echo icon('edit'); ?>"></i> <?php echo lang('Buttons.edit'); ?>
    </a>
</div>
<h4>
    <i class="<?php echo icon('profile'); ?>"></i>
    <?php if(!empty($id_user)):?>
        <?php echo lang('UserProfile.profile_of'); ?>
        <?php echo $user->prenom;?> <?php echo $user->nom;?>
    <?php else:?>
        Mon profil
    <?php endif;?>
</h4>
<div class="clearfix"></div>

<div class="card flex-fill mb-4">
    <div class="card-body-off">
        <div class="row">
            <div class="col-md-3 col-sm-6 col-xs-12 text-center ">
                <div class="text-center m-2">
                    <img src="<?php echo base_url(AVATAR_PATH . $user->avatar);?>" alt="Avatar" class="img-fluid img-thumbnail" />
                    <div class="text-center m-1">
                        <?php if($user->is_actif): ?>
                            <span class="badge bg-success"><?php echo lang('UserProfile.actif'); ?></span>
                        <?php else: ?>
                            <span class="badge bg-danger"><?php echo lang('UserProfile.inactif'); ?></span>
                        <?php endif; ?>
                        <span class="badge bg-dark"><?php echo $user->role_label; ?></span>
                    </div>
                </div>
            </div>
            <div class="col-md-9 col-sm-6 col-xs-12">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <label for="nom" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> Nom
                            </label>
                            <div class="col-sm-9">
                                <input type="nom" disabled class="form-control bg-white border-white" id="nom" value="<?php echo $user->nom; ?>" placeholder="">
                            </div>
                        </div> 
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <label for="nom" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> Prénom
                            </label>
                            <div class="col-sm-9">
                                <input type="nom" disabled class="form-control bg-white border-white" id="nom" value="<?php echo $user->prenom; ?>" placeholder="">
                            </div>
                        </div> 
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <label for="email" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('email'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.email'); ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="email" disabled class="form-control bg-white border-white" id="email" value="<?php echo $user->email; ?>" placeholder="">
                            </div>
                        </div> 
                    </li>

                    <li class="list-group-item">
                        <div class="row">
                            <label for="website" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('website'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.website'); ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" disabled class="form-control bg-white border-white" id="website" value="<?php echo $user->website; ?>" placeholder="">
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="row">
                            <label for="phone" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('phone'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.phone'); ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="phone" disabled class="form-control bg-white border-white" id="phone" value="<?php echo $user->phone; ?>" placeholder="">
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
