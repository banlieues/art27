<?php $this->extend('Administrator\identification/main'); ?>

<?php $this->section("identification-body"); ?>

    <form class="form bg-light p-3 rounded border border-top-theme"
        action="<?php echo base_url("identification/reset?token=$token"); ?>"
        method="post"
        >
        <div>
            <?php echo csrf_field(); ?>
        </div>

        <input type="hidden" name="token" value="<?php echo $token;?>">

        <div class="mb-3">
            <label class="form-label"><?php echo lang('Identification.password'); ?></label>
            <input class="form-control" type="password" name="password" placeholder="<?php echo lang('Identification.password'); ?> ..." value="<?php echo set_value('password'); ?>">
            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'password') : ''; ?></span>
        </div>
        <div class="mb-3">
            <label class="form-label"><?php echo lang('Identification.confirmpassword'); ?></label>
            <input class="form-control" type="password" name="confirm" placeholder="<?php echo lang('Identification.confirmpassword'); ?> ..." value="<?php echo set_value('confirm'); ?>">
            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'confirm') : ''; ?></span>
        </div>

        <div class="mb-3">
            <button class="btn btn-danger w-100">
                <i class="fa fa-check" aria-hidden="true"></i> <?php echo lang('Identification.reset'); ?>
            </button>
        </div>
    </form>
    <div class="text-center">
        <?php if (ALLOW_REGISTRATIONS): ?>
        <a href="<?php echo base_url('identification/register'); ?>" class="small link-dark"><?php echo lang('Identification.noaccountyet'); ?></a> - 
            <?php endif; ?>
        <a href="<?php echo base_url('identification/login'); ?>" class="small link-dark"><?php echo lang('Identification.alreadyregistred'); ?></a>
    </div>

<?php $this->endSection(); ?>
