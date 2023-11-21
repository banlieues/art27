<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
    <div class="col-md-12">
        <form action="<?php echo base_url('depositbox/add'); ?>" method="post" enctype="multipart/form-data">
            <div><?php echo csrf_field(); ?></div>
            <div class="float-end">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="<?php echo icon('save'); ?>"></i> <?php echo lang('Buttons.save'); ?>
                </button>
                <a class="btn btn-danger btn-sm" href="<?php echo base_url('depositbox'); ?>">
                    <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
                </a>
            </div>

            <h4><?php echo $title.' : '.$subtitle; ?></h4>

            <div class="card flex-fill border-top-lightseagreen mb-4">
                <h5 class="card-header">
                    <?php echo lang('DepositBox.currentfile'); ?> : <?php echo lang('DepositBox.newfile'); ?>
                    <div class="float-end">
                        <i class="<?php echo icon('file'); ?> mt-1"></i>
                    </div>
                </h5>
                <div class="card-body-off">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="label" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('label'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.label'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="label" name="label" value="<?php echo set_value('label'); ?>" placeholder="<?php echo lang('DepositBox.label'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'label') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="description" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('description'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.description'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="description" name="description" value="<?php echo set_value('description'); ?>" placeholder="<?php echo lang('DepositBox.description'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'description') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="comment" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.comment'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" id="comment" name="comment"><?php echo set_value('email_body'); ?></textarea >
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'comment') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="keywords" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('key'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.keywords'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="keywords" name="keywords" value="<?php echo set_value('keywords'); ?>" placeholder="<?php echo lang('DepositBox.keywords'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'keywords') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="uploadfile" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.uploadfile'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="file" class="form-control" id="uploadfile" name="uploadfile" value="uploadfile">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'uploadfile') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="filename" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.filename'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input readonly type="text" class="form-control" id="filename" name="filename" value=""><?php echo set_value('filename'); ?></input >
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'filename') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="filesize" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.filesize'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input readonly type="number" class="form-control" id="filesize" name="filesize" value=""><?php echo set_value('filesize'); ?></input >
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'filesize') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="extension" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.extension'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input readonly class="form-control" id="extension" name="extension" value=""><?php echo set_value('extension'); ?></input >
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'extension') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="actived" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('actived'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.actived'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="actived" name="actived" min="0" max="1" value="<?php echo set_value('actived'); ?>" placeholder="<?php echo lang('DepositBox.actived'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'actived') : '' ?></span>
                                        </div>
                                    </div> 
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card-footer text-body-secondary"></div>
            </div>
        </form>
    </div>
</div>

<script>
// $(document).ready(function() {
document.addEventListener("DOMContentLoaded", function() {
    $('input[type="file"]').change(function(e) {
        let filename = e.target.files[0].name;
        let filesize = e.target.files[0].size;
        let extension = filename.replace(/^.*\./, '');
        $("#filename").val(filename);
        $("#filesize").val(filesize);
        $("#extension").val(extension);
    });
});
</script>

<?php $this->endSection(); ?>
