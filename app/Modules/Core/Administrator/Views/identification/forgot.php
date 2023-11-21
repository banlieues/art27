<?php $this->extend('Administrator\identification/main'); ?>

<?php $this->section("identification-body"); ?>

    <form action="<?php echo base_url('identification/forgot'); ?>" method="post" class="form bg-light p-3 rounded border border-top-<?php echo $themes->main->color;?>">
        <div><?php echo csrf_field(); ?></div>
        <div class="mb-3">
            <label class="form-label">
                Veuillez entrer votre adresse mail de contact auprès du <?php echo $themes->main->name;?>. Le système enverra alors à cette adresse un lien pour redéfinir votre mot de passe.
            </label>
            <input autocomplete="off" class="form-control" type="email" name="email" placeholder="<?php echo lang('Identification.email'); ?> ..." value="<?php echo set_value('email'); ?>">
            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'email') : '' ?></span>
        </div>
        <div class="mb-3">
            <button class="btn btn-success w-100">
                <i class="fa fa-check" aria-hidden="true"></i> <?php echo lang('Identification.send'); ?>
            </button>
        </div>
    </form>
    <div class="text-center">
        <?php if (ALLOW_REGISTRATIONS): ?>
            <a href="<?php echo base_url('identification/register') ?>" class="small link-dark">
                <?php echo lang('Identification.noaccountyet'); ?>
            </a> 
            - 
        <?php endif; ?>
        <a href="<?php echo base_url('identification/login') ?>" class="small link-dark">
            <?php echo lang('Identification.alreadyregistred');?>
        </a>
    </div>

<?php $this->endSection(); ?>
