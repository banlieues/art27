<?php $this->extend('Administrator\identification/main'); ?>

<?php $this->section("identification-body"); ?>

<!-- <div class="row mt-5">
    <div class="col-md-6 offset-md-3">
        <div class="card mb-3 border-top-theme">
            <div class="row g-0">
                <div class="col-md-5">
                    <img src="<?php echo base_url(get_random_image('images/login')); ?>" alt="<?php echo $themes->main->name;?>" class="img-cover">
                </div>
                <div class="col-md-7">
                    <img src="<?php echo base_url($themes->main->logo);?>"
                        alt="<?php echo $themes->main->name;?> Logo"
                        class="px-5 w-100"
                    />
                    <h3>
                        <?php echo lang('Identification.identification'); ?>
                        <i class="fa fa-lock float-end"></i>
                    </h3>
                    <p><?php echo lang('Identification.emailverifaccountvalid'); ?></p>
                </div>
            </div>
        </div>
        <?php include_once('copyright.php'); ?>
    </div>
</div> -->

<?php $this->endSection(); ?>
