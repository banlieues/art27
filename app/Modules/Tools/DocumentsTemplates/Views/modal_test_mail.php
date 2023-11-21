<?php if (!empty($value)): ?>
<div id="sendmail_<?php echo $value->id ?? null; ?>">
    <form action="<?php echo base_url('documentstemplates/sendmail'); ?>" method="post" enctype="multipart/form-data">
        <div><input type="hidden" id="id" name="id" value="<?php echo $value->id ?? null; ?>"></div>
        <div><input type="hidden" id="page" name="page" value="<?php echo $page ?? null; ?>"></div>
        <div><?php echo csrf_field(); ?></div>
            <h5>
                <i class="<?php echo icon('file'); ?> mt-1"></i> <?php echo $value->label; ?>
            </h5>

            <div class="mb-3">
                <label for="email" class="col-form-label"><?php echo lang('DocumentsTemplates.sendto'); ?> :</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email adress ..." required>
            </div>

            <button type="submit" class="btn btn-success btn-sm">
                <i class="<?php echo icon('send-email'); ?>"></i> <?php echo lang('Buttons.send'); ?>
            </button>
            <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">
                <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
            </button>
    </form>
</div>
<?php else:?>
    <h3>Pas de formulaire disponible</h3>
<?php endif;?>