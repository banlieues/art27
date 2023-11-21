<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
    <div class="col-md-3">
        <?php echo $this->include('Layout\user-sidebar'); ?>
    </div>

    <div class="col-md-9">
        <div class="float-end">
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('user/profile'); ?>/<?=$user->id?>">
                <i class="<?php echo icon('goback'); ?>"></i> <?php echo lang('Buttons.goback'); ?>
            </a>
            <a class="btn btn-primary btn-sm" href="<?php echo base_url('user/profile/edit'); ?>/<?=$user->id?>">
                <i class="<?php echo icon('edit'); ?>"></i> <?php echo lang('Buttons.edit'); ?>
            </a>
        </div>

        <h4><?php echo $title.' : '.$subtitle; ?></h4>
        <div class="clearfix"></div>

        <div class="card flex-fill mb-4">
            <h5 class="card-header">
                <i class="<?php echo icon('profile'); ?> float-end mt-1"></i>
                <?php if($user->id!=session('loggedUserId')):?>
                    <?php echo lang('UserProfile.profile_of'); ?>
                    <?php echo $user->prenom;?> <?php echo $user->nom;?>
                    <small>(<?php echo $user->username;?>)</small>
                <?php else:?>
                    Mon profil
                <?php endif;?>
            </h5>
            <div class="card-body-off">
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-6 col-lg-12 col-xl-3 text-center ">
                        <div class="text-center m-2">
                            <img src="<?php echo base_url(AVATAR_PATH . $user->avatar); ?>" alt="Avatar" class="img-fluid img-thumbnail" />
                            <div class="text-center m-1">
                                <?php if ($user->valided): ?>
                                <span class="badge bg-success"><?php echo lang('UserProfile.valid'); ?></span>
                                <?php else: ?>
                                <span class="badge bg-danger"><?php echo lang('UserProfile.invalid'); ?></span>
                                <?php endif; ?>

                                <?php if ($user->is_actif): ?>
                                <span class="badge bg-success"><?php echo lang('UserProfile.actif'); ?></span>
                                <?php else: ?>
                                <span class="badge bg-danger"><?php echo lang('UserProfile.inactif'); ?></span>
                                <?php endif; ?>
                                <br><span class="badge bg-theme"><?php echo $roles_infos['label']; ?></span>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-xs-12 col-sm-6 col-lg-12 col-xl-9">
                        <ul class="list-group list-group-flush">
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

                            <!--
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="created_at" class="col-sm-3 col-form-label">
                                        <i class="<?php // echo icon('calendar'); ?>" style="width:20px;"></i> <?php // echo lang('UserProfile.created_at'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="created_at" name="created_at" value="<?php // echo $user->created_at; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="updated_at" class="col-sm-3 col-form-label">
                                        <i class="<?php // echo icon('calendar'); ?>" style="width:20px;"></i> <?php // echo lang('UserProfile.updated_at'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="updated_at" name="updated_at" value="<?php // echo $user->updated_at; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            -->

                            <!--
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="role_id" class="col-sm-3 col-form-label">
                                        <i class="<?php // echo icon('role'); ?>" style="width:20px;"></i> <?php // echo lang('UserProfile.role'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="role_id" name="role_id" value="<?php // echo $user->role_id; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            -->

                            <!--
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="country_id" class="col-sm-3 col-form-label">
                                        <i class="<?php // echo icon('country'); ?>" style="width:20px;"></i> <?php // echo lang('UserProfile.country_id'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="country_id" value="<?php // echo $profile_infos['country_id']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            -->

                            <li class="list-group-item">
                                <div class="row">
                                    <label for="website" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('website'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.website'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="website" value="<?php echo $profile_infos['website']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="phone" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('phone'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.phone'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="phone" disabled class="form-control bg-white border-white" id="phone" value="<?php echo $profile_infos['phone']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="gsm" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('gsm'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.gsm'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="gsm" disabled class="form-control bg-white border-white" id="gsm" value="<?php echo $profile_infos['gsm']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="birthday" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('birthday'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.birthday'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="birthday" name="birthday" value="<?php echo $profile_infos['birthday']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="created_at" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('calendar'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.created_at'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="created_at" value="<?php echo $user->created_at; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="updated_at" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('calendar'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.updated_at'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="updated_at" value="<?php echo $user->updated_at; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="created_by" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.created_by'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="created_by" name="created_by" value="<?php echo $user->created_by; ?>"  placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="updated_by" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.updated_by'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="updated_by" name="updated_by" value="<?php echo $user->updated_by; ?>"  placeholder="">
                                    </div>
                                </div>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
            <div class="card-footer text-body-secondary"></div>
        </div>

        <div class="row">
            <div class="col-md-12 mb-4">
                <div class="card flex-fill">
                    <h5 class="card-header">
                        <i class="<?php echo icon('role'); ?> float-end mt-1"></i>
                        <?php echo lang('UserProfile.role_of'); ?> : <?php echo $user->username; ?>
                    </h5>
                    <div class="card-body-off">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="birthday" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('role'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.role_id'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="birthday" value="<?php echo $user->role_id; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="birthday" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('label'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.label'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="birthday" value="<?php echo $roles_infos['label']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="birthday" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('description'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.description'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="birthday" value="<?php echo $roles_infos['description']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-body-secondary"></div>
                </div>
            </div>

            <div class="col-md-12 mb-4">
                <div class="card flex-fill">
                    <h5 class="card-header">
                        <i class="<?php echo icon('genders'); ?> float-end mt-1"></i>
                        <?php echo lang('UserProfile.gender_of'); ?> : <?php echo $user->username; ?>
                    </h5>
                    <div class="card-body-off">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="birthday" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('genders'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.gender_id'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="birthday" value="<?php echo $genders_infos['id']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="gender_label" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('label'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.label'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="gender_label" value="<?php echo $genders_infos['label']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="gender_description" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('description'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.description'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="gender_description" value="<?php echo $genders_infos['description']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-body-secondary"></div>
                </div>
            </div>

            <div class="col-md-12 mb-4">
                <div class="card flex-fill">
                    <h5 class="card-header">
                        <i class="<?php echo icon('country'); ?> float-end mt-1"></i>
                        <?php echo lang('UserProfile.country_of'); ?> : <?php echo $user->username; ?>
                    </h5>
                    <div class="card-body-off">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="birthday" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('country'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.country_id'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="birthday" value="<?php echo $countries_infos['id']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="country_label" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('label'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.label'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="country_label" value="<?php echo $countries_infos['label']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="country_description" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('description'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.description'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="country_description" value="<?php echo $countries_infos['description']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <label for="capitale" class="col-sm-3 col-form-label">
                                        <i class="<?php echo icon('capitale'); ?>" style="width:20px;"></i> <?php echo lang('UserProfile.capitale'); ?>
                                    </label>
                                    <div class="col-sm-9">
                                        <input type="text" disabled class="form-control bg-white border-white" id="capitale" value="<?php echo $countries_infos['capitale']; ?>" placeholder="">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="card-footer text-body-secondary"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->endSection(); ?>
