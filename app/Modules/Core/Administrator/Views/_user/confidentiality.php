<?php $this->extend("\Administrator\Views\administrator/index"); ?>

<?php $this->section("admin_body"); ?>

<form action="<?php echo base_url('user/confidentiality'); ?>" method="post" enctype="multipart/form-data">
    <div class="float-end">
        <!-- TODO: Create form etc ... -->
        <a class="btn btn-success btn-sm" href="<?php echo base_url('user/confidentiality/?action=save'); ?>">
            <i class="<?php echo icon('save'); ?>"></i> <?php echo lang('Buttons.save'); ?>
        </a>
        <a class="btn btn-danger btn-sm" href="<?php echo base_url('user/confidentiality/?action=cancel'); ?>">
            <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
        </a>
    </div>
    <h4><?php echo $title.' : '.$subtitle; ?></h4>
    <div class="clearfix"></div>

    <div class="card flex-fill mb-4">
        <h5 class="card-header">
            <i class="<?php echo icon('confidentiality'); ?> float-end mt-1"></i>
            <?php echo lang('User.settings_of'); ?> : <?php echo $user->username; ?>
        </h5>
        <div class="card-body-off">
            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <div class="row">
                        <label for="select" class="col-sm-3 col-form-label">
                            <i class="<?php echo icon('confidentiality'); ?>" style="width:20px;"></i> Profile confidentiality
                        </label>
                        <div class="col-sm-9">
                            <select class="form-select" aria-label="Default select example">
                                <option selected disabled hidden>Choice option</option>
                                <option value="1">Everyone</option>
                                <option value="2">Just me</option>
                            </select>
                        </div>
                    </div>
                </li>
                <!--
                <li class="list-group-item">
                    <div class="row">
                        <label for="switch" class="col-sm-3 col-form-label">
                            <i class="<?php // echo icon('confidentiality'); ?>" style="width:20px;"></i> Hide my profile from the directory
                        </label>
                        <div class="col-sm-9">
                            <div class="form-check form-switch mt-2">
                                <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                                <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch</label>
                            </div>
                        </div>
                    </div>
                </li>
                -->
                <li class="list-group-item">
                    <div class="row">
                        <label for="radio" class="col-sm-3 col-form-label">
                            <i class="<?php echo icon('confidentiality'); ?>" style="width:20px;"></i> Hide my profile from the directory
                        </label>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="1">
                                <label class="form-check-label" for="inlineRadio1">Yes</label>
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="0" checked>
                                <label class="form-check-label" for="inlineRadio2">No</label>
                            </div>
                        </div>
                    </div>
                </li>
                <!--
                <li class="list-group-item">
                    <div class="row">
                        <label for="checkbox" class="col-sm-3 col-form-label">
                            <i class="<?php // echo icon('confidentiality'); ?>" style="width:20px;"></i> Inline checkbox
                        </label>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" value="option1" checked>
                                <label class="form-check-label" for="inlineCheckbox1">1</label>
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox2" value="option2">
                                <label class="form-check-label" for="inlineCheckbox2">2</label>
                            </div>
                            <div class="form-check form-check-inline mt-2">
                                <input class="form-check-input" type="checkbox" id="inlineCheckbox3" value="option3" disabled>
                                <label class="form-check-label" for="inlineCheckbox3">3 (disabled)</label>
                            </div>
                        </div>
                    </div>
                </li>
                -->
                
                <!--
                <li class="list-group-item">
                    <div class="row">
                        <label for="download_data" class="col-sm-3 col-form-label">
                            <i class="<?php // echo icon('download'); ?>" style="width:20px;"></i> Download your data
                        </label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="download_data" name="download_data" value="<?php // echo set_value('password'); ?>" placeholder="">
                            <span class="text-danger"><?php // echo isset($validation) ? display_error($validation, 'download_data') : '' ?></span>

                            <button type="submit" class="btn btn-primary btn-sm mt-2">
                                <i class="<?php // echo icon('data'); ?>"></i> Request data
                            </button>
                        </div>
                    </div>
                </li>

                <li class="list-group-item">
                    <div class="row">
                        <label for="data_erasure" class="col-sm-3 col-form-label">
                            <i class="<?php // echo icon('delete'); ?>" style="width:20px;"></i> Erasure of your data
                        </label>
                        <div class="col-sm-9">
                            <input type="password" class="form-control" id="data_erasure" name="data_erasure" value="<?php // echo set_value('password'); ?>" placeholder="">
                            <span class="text-danger"><?php // echo isset($validation) ? display_error($validation, 'data_erasure') : '' ?></span>

                            <button type="submit" class="btn btn-primary btn-sm mt-2">
                                <i class="<?php // echo icon('sendmail'); ?>"></i> Request data erasure
                            </button>
                        </div>
                    </div>
                </li>
                -->
            </ul>
        </div>
        <div class="card-footer text-body-secondary"></div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="card flex-fill mb-4">
                <h5 class="card-header">
                    <i class="<?php echo icon('confidentiality'); ?> float-end mt-1"></i> Download your data
                </h5>
                <div class="card-body-off">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <label for="download_data" class="col-sm-5 col-form-label">
                                    <i class="<?php echo icon('password'); ?>" style="width:20px;"></i> Your password
                                </label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="download_data" name="download_data" value="<?php echo set_value('password'); ?>" placeholder="...">
                                    <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'download_data') : '' ?></span>
                                    <div class="d-grid gap-1">
                                        <button type="submit" class="btn btn-primary btn-sm mt-2">
                                            <i class="<?php echo icon('sendmail'); ?>"></i> Request data
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-body-secondary"></div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card flex-fill mb-4">
                <h5 class="card-header">
                    <i class="<?php echo icon('confidentiality'); ?> float-end mt-1"></i> Erasure of your data
                </h5>
                <div class="card-body-off">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <label for="data_erasure" class="col-sm-5 col-form-label">
                                    <i class="<?php echo icon('password'); ?>" style="width:20px;"></i> Your password
                                </label>
                                <div class="col-sm-7">
                                    <input type="password" class="form-control" id="data_erasure" name="data_erasure" value="<?php echo set_value('password'); ?>" placeholder="...">
                                    <span class="text-danger"><?php echo isset($validation) ? display_error($validation, 'data_erasure') : '' ?></span>
                                    <div class="d-grid gap-1">
                                        <button type="submit" class="btn btn-primary btn-sm btn-block mt-2">
                                            <i class="<?php echo icon('sendmail'); ?>"></i> Request data erasure
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-body-secondary"></div>
            </div>
        </div>
    </div>
</form>

<?php $this->endSection(); ?>
