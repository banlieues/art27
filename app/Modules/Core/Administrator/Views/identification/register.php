<?php $this->extend('Administrator\identification/main'); ?>

<?php $this->section("identification-body"); ?>

<?php if (ALLOW_REGISTRATIONS): ?>

    <form action="<?php echo base_url('identification/register'); ?>" method="post" class="form bg-light p-3 rounded border">
        <div><?php echo csrf_field(); ?></div>
        <div class="mb-3">
            <label class="form-label"><?php echo lang('Identification.username'); ?></label>
            <input class="form-control" type="text" name="username" placeholder="<?php echo lang('Identification.username'); ?> ..." value="<?php echo set_value('username'); ?>">
            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'username') : '' ?></span>
        </div>
        <div class="mb-3">
            <label class="form-label"><?php echo lang('Identification.email'); ?></label>
            <input class="form-control" type="email" name="email" placeholder="<?php echo lang('Identification.email'); ?> ..." value="<?php echo set_value('email'); ?>">
            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email') : '' ?></span>
        </div>
        <div class="mb-3">
            <label class="form-label"><?php echo lang('Identification.password'); ?></label>
            <input class="form-control" type="password" name="password" placeholder="<?php echo lang('Identification.password'); ?> ..." value="<?php echo set_value('password'); ?>">
            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'password') : '' ?></span>
        </div>
        <div class="mb-3">
            <label class="form-label"><?php echo lang('Identification.confirmpassword'); ?></label>
            <input class="form-control" type="password" name="confirm" placeholder="<?php echo lang('Identification.confirmpassword'); ?> ..." value="<?php echo set_value('confirm'); ?>">
            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'confirm') : '' ?></span>
        </div>
        <div class="mb-3">
            <button class="btn btn-success w-100">
                <i class="fa fa-check" aria-hidden="true"></i> <?php echo lang('Identification.register'); ?>
            </button>
        </div>
    </form>
    <div class="text-center">
        <a href="<?php echo base_url('identification/login') ?>" class="small link-dark"><?php echo lang('Identification.alreadyregistred'); ?></a> - 
        <a href="<?php echo base_url('identification/forgot') ?>" class="small link-dark"><?php echo lang('Identification.forgetpassword'); ?></a>
    </div>

<?php else: ?>

    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo lang('Identification.registrationdisabled'); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <div class="text-center">
        <a href="<?php echo base_url('identification/login') ?>" class="small link-dark"><?php echo lang('Identification.alreadyregistred'); ?></a> - 
        <a href="<?php echo base_url('identification/forgot') ?>" class="small link-dark"><?php echo lang('Identification.forgetpassword'); ?></a>
    </div>

<?php endif; ?>

<?php $this->endSection(); ?>

