<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
    <div class="col-md-12">
        <form action="<?php echo base_url('documentstemplates/sendmail'); ?>" method="post" enctype="multipart/form-data">
            <div><input type="hidden" id="id" name="id" value="<?php echo $template_id ?? null; ?>"></div>
            <div><input type="hidden" id="page" name="page" value="<?php echo $page; ?>"></div>
            <div><?php echo csrf_field(); ?></div>
            <div class="float-end">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="<?php echo icon('sendmail'); ?>"></i> <?php echo lang('Buttons.send'); ?>
                </button>
                <a class="btn btn-danger btn-sm" href="<?php echo base_url('documentstemplates/?page='.$page); ?>">
                    <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
                </a>
            </div>

            <h4><?php echo $title.' : '.$subtitle; ?></h4>

            <?php if ($template_id): ?>
            <div class="card flex-fill border-top-office mb-4">
                <h5 class="card-header">
                    <?php echo lang('DocumentsTemplates.currentdocument'); ?> : <?php echo $documents_template['label']; ?>
                </h5>
                <div class="card-body">
                    <label for="email" class="col-form-label"><?php echo lang('DocumentsTemplates.sendto'); ?> :</label>
                    <input type="email" class="form-control mb-3" id="email" name="email" placeholder="Email adress ..." required>
                    <button type="submit" class="btn btn-success btn-sm">
                        <i class="<?php echo icon('send-email'); ?>"></i> <?php echo lang('Buttons.send'); ?>
                    </button>
                    <a class="btn btn-danger btn-sm" href="<?php echo base_url('documentstemplates/?page='.$page); ?>">
                        <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
                    </a>
                </div>
                <div class="card-footer text-body-secondary"></div>
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
