<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
   

    <div class="col-md-12">
        <form action="<?php echo base_url('memberslist/delete'); ?>" method="post" enctype="multipart/form-data">
            <div><input type="hidden" id="id" name="id" value="<?php echo $member_id ?? null; ?>"></div>

         

            <?php if ($member_id): ?>
            <div class="card flex-fill border-top-theme mb-4">
          

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-6 col-lg-12">
                           Etes-vous sûr de vouloir supprimer l'utilisateur <?php echo $users_infos['username']; ?>?
                        </div>
                        <div class="float-start mt-2">
                            <button type="submit" class="btn btn-success btn-sm">
                                <i class="<?php echo icon('yes'); ?>"></i> <?php echo lang('Buttons.yes'); ?>
                            </button>
                            <a class="btn btn-danger btn-sm" href="<?php echo base_url('memberslist'); ?>">
                                <i class="<?php echo icon('no'); ?>"></i> <?php echo lang('Buttons.no'); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo lang('Errors.invalidrequest'); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php endif; ?>
        </form>
    </div>
</div>

<?php $this->endSection(); ?>
