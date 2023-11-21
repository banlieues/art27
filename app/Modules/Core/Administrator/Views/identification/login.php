<?php $this->extend('Administrator\identification/main'); ?>
<?php $this->section("identification-body"); ?>

<form action="<?php echo base_url("identification/login?$get"); ?>" method="post" class="form bg-light p-3 rounded border border-top-<?php echo $themes->main->color;?>">
    <div><?php echo csrf_field(); ?></div>
    <div class="mb-3">
        <label class="form-label"><?php echo lang('Identification.username'); ?></label>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-username"><i class="fa fa-user"></i></span>
            <input class="form-control" type="text" name="username" placeholder="<?php echo lang('Identification.username'); ?> ..." value="<?php echo set_value('username'); ?>" aria-label="Username" aria-describedby="addon-username">
        </div>
        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'username') : '' ?></span>
    </div>
    <div class="mb-3">
        <label class="form-label"><?php echo lang('Identification.password'); ?></label>
        <div class="input-group flex-nowrap">
            <span class="input-group-text" id="addon-password"><i class="fa fa-key"></i></span>
            <input class="form-control" type="password" name="password" placeholder="<?php echo lang('Identification.password'); ?> ..." value="<?php echo set_value('password'); ?>" aria-label="Password" aria-describedby="addon-password">
        </div>
        <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'password') : '' ?></span>
    </div>
    <div class="mb-3">
        <button class="btn btn-success w-100">
            <i class="fa fa-check" aria-hidden="true"></i> <?php echo lang('Identification.login'); ?>
        </button>
    </div>
</form>
<div class="text-center">
    <?php if (ALLOW_REGISTRATIONS): ?>
    <a href="<?php echo base_url('identification/register') ?>" class="small link-dark"><?php echo lang('Identification.noaccountyet'); ?></a> - 
    <?php endif; ?>
    <a href="<?php echo base_url('identification/forgot') ?>" class="small link-dark"><?php echo lang('Identification.forgetpassword'); ?></a>
</div>


<?php $this->endSection(); ?>
