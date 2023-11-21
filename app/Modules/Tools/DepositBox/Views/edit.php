<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
    <div class="col-md-12">
        <form action="<?php echo base_url('depositbox/save'); ?>" method="post" enctype="multipart/form-data">
            <div><input type="hidden" id="id" name="id" value="<?php echo $file_id ?? null; ?>"></div>
            <div><input type="hidden" name="page" value="<?php echo $page; ?>"></div>
            <div><?php echo csrf_field(); ?></div>
            <div class="float-end">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="<?php echo icon('save'); ?>"></i> <?php echo lang('Buttons.save'); ?>
                </button>
                <a class="btn btn-danger btn-sm" href="<?php echo base_url('depositbox').'?page='.$page; ?>">
                    <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
                </a>
            </div>

            <h4><?php echo $title.' : '.$subtitle; ?></h4>

            <?php if ($file_id): ?>
            <div class="card flex-fill border-top-lightseagreen mb-4">
                <h5 class="card-header">
                    <i class="<?php echo icon('role'); ?> float-end mt-1"></i>
                    <?php echo lang('DepositBox.currentrole'); ?> : <?php echo $depositbox_file['label']; ?>
                </h5>

                <div class="card-body-off">
                    <div class="row">
                        <div class="col-md-12 col-xs-12 col-sm-6 col-lg-12">
                            <ul class="list-group list-group-flush">
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="file_id" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('id'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.id'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="number" readonly class="form-control" id="file_id" name="file_id" value="<?php echo $depositbox_file['id']; ?>" placeholder="">
                                        </div>
                                    </div> 
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="label" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('label'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.label'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="label" name="label" value="<?php echo $depositbox_file['label']; ?>" placeholder="">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'label') : '' ?></span>
                                        </div>
                                    </div> 
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="description" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('description'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.description'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" class="form-control" id="description" name="description" value="<?php echo $depositbox_file['description']; ?>" placeholder="<?php echo lang('DepositBox.description'); ?> ...">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'description') : '' ?></span>
                                        </div>
                                    </div> 
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="comment" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.comment'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <textarea class="form-control" id="comment" name="comment"><?php echo $depositbox_file['comment']; ?></textarea >
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'comment') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="keywords" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('tags'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.keywords'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input type="text" class="form-control" id="keywords" name="keywords" value="<?php echo $depositbox_file['keywords']; ?>" placeholder="">
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'keywords') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="filename" class="col-md-3 col-form-label">
                                            <i class="<?php echo icon('file'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.filename'); ?>
                                        </label>
                                        <div class="col-md-9">
                                            <input readonly type="text" class="form-control" id="filename" name="filename" value="<?php echo $depositbox_file['filename']; ?>"></input >
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
                                            <input readonly type="number" class="form-control" id="filesize" name="filesize" value="<?php echo $depositbox_file['filesize']; ?>"></input >
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
                                            <input readonly class="form-control" id="extension" name="extension" value="<?php echo $depositbox_file['extension']; ?>"></input >
                                            <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'extension') : ''; ?></span>
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="created_at" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('calendar'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.created_at'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" readonly class="form-control" id="created_at" name="created_at" value="<?php echo $depositbox_file['created_at']; ?>" placeholder="">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="updated_at" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('calendar'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.updated_at'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" readonly class="form-control" id="updated_at" name="updated_at" value="<?php echo $depositbox_file['updated_at']; ?>"  placeholder="">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="created_by" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.created_by'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" readonly class="form-control" id="created_by" name="created_by" value="<?php echo $depositbox_file['created_by']; ?>" placeholder="">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="updated_by" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('user'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.updated_by'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="text" readonly class="form-control" id="updated_by" name="updated_by" value="<?php echo $depositbox_file['updated_by']; ?>"  placeholder="">
                                        </div>
                                    </div>
                                </li>
                                <li class="list-group-item">
                                    <div class="row">
                                        <label for="actived" class="col-sm-3 col-form-label">
                                            <i class="<?php echo icon('actived'); ?>" style="width:20px;"></i> <?php echo lang('DepositBox.actived'); ?>
                                        </label>
                                        <div class="col-sm-9">
                                            <input type="number" class="form-control" id="actived" name="actived" value="<?php echo $depositbox_file['actived']; ?>" min='0' max='1' placeholder="<?php echo lang('DepositBox.actived'); ?> ...">
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
