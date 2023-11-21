<?php $this->extend("\Administrator\Views\administrator/index"); ?>

<?php $this->section("admin_body"); ?>

<div class="float-end">
    <a class="btn btn-primary btn-sm" href="<?php echo base_url('memberslist').'?page='.$page; ?>">
        <i class="<?php echo icon('goback'); ?>"></i> <?php echo lang('Buttons.goback'); ?>
    </a>
    <a class="btn btn-primary btn-sm" href="<?php echo base_url('memberslist/edit').'?id='.$member_id.'&page='.$page; ?>">
        <i class="<?php echo icon('edit'); ?>"></i> <?php echo lang('Buttons.edit'); ?>
    </a>
</div>

<h4><?php echo $title.' : '.$subtitle; ?></h4>

<?php if ($member_id): ?>
<div class="card flex-fill border-top-theme mb-4">
    <h5 class="card-header">
        <i class="<?php echo icon('role'); ?> float-end mt-1"></i>
        <?php echo lang('Member.currentmember'); ?> : <?php echo $useruser->username; ?>
    </h5>

    <div class="card-body-off">
        <div class="row">
            <div class="col-md-12 col-xs-12 col-sm-6 col-lg-12">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">
                        <div class="row">
                            <label for="email" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('email'); ?>" style="width:20px;"></i> <?php echo lang('Member.email'); ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="email" disabled class="form-control bg-white border-white" id="email" name="email" value="<?php echo $user->email; ?>" placeholder="">
                                <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email') : '' ?></span>
                            </div>
                        </div> 
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <label for="created_at" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('calendar'); ?>" style="width:20px;"></i> <?php echo lang('Member.created_at'); ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" disabled class="form-control bg-white border-white" id="created_at" name="created_at" value="<?php echo $user->created_at; ?>" placeholder="">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <label for="updated_at" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('calendar'); ?>" style="width:20px;"></i> <?php echo lang('Member.updated_at'); ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="text" disabled class="form-control bg-white border-white" id="updated_at" name="updated_at" value="<?php echo $user->updated_at; ?>"  placeholder="">
                            </div>
                        </div>
                    </li>
                    <li class="list-group-item">
                        <div class="row">
                            <label for="valided" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('valided'); ?>" style="width:20px;"></i> <?php echo lang('Member.valided'); ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="number" disabled class="form-control bg-white border-white" id="valided" name="valided" value="<?php echo $user->valided; ?>" min="0" max="1" placeholder="">
                                <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'valided') : '' ?></span>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="row">
                            <label for="is_actif" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('is_actif'); ?>" style="width:20px;"></i> <?php echo lang('Member.is_actif'); ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="number" disabled class="form-control bg-white border-white" id="is_actif" name="is_actif" value="<?php echo $user->is_actif; ?>" min="0" max="1" placeholder="">
                                <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'is_actif') : '' ?></span>
                            </div>
                        </div>
                    </li>

                    <li class="list-group-item">
                        <div class="row">
                            <label for="role_id" class="col-sm-3 col-form-label">
                                <i class="<?php echo icon('role'); ?>" style="width:20px;"></i> <?php echo lang('Member.role_id'); ?>
                            </label>
                            <div class="col-sm-9">
                                <input type="number" disabled class="form-control bg-white border-white" id="role_id" name="role_id" value="<?php echo $user->role_id; ?>" min="1" max="<?php echo $users_roles_total; ?>" placeholder="">
                                <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'role_id') : '' ?></span>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-footer text-muted"></div>
</div>
<?php else: ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo lang('Errors.invalidrequest'); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
<?php endif; ?>

<?php $this->endSection(); ?>
