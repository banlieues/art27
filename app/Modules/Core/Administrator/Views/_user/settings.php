<?php $this->extend("\Administrator\Views\administrator/index"); ?>

<?php $this->section("admin_body"); ?>

<div class="float-end">
    <!-- TODO: Create form etc ... -->
    <a class="btn btn-success btn-sm" href="<?php echo base_url('user/settings/?action=save'); ?>">
        <i class="<?php echo icon('save'); ?>"></i> <?php echo lang('Buttons.save'); ?>
    </a>
    <a class="btn btn-danger btn-sm" href="<?php echo base_url('user/settings/?action=cancel'); ?>">
        <i class="<?php echo icon('cancel'); ?>"></i> <?php echo lang('Buttons.cancel'); ?>
    </a>
</div>

<h4>
    <?php echo $title.' : '.$subtitle; ?>
</h4>

<div class="clearfix">

</div>

<div class="card flex-fill border-top-theme mb-4">
    <h5 class="card-header">
        <i class="<?php echo icon('settings'); ?> float-end mt-1"></i>
        <?php echo lang('User.settings_of'); ?> : <?php echo $user->username; ?>
    </h5>
    <div class="card-body-off">
        <ul class="list-group list-group-flush">
            <li class="list-group-item">
                <div class="row">
                    <label for="select" class="col-sm-3 col-form-label">
                        <i class="<?php echo icon('select'); ?>" style="width:20px;"></i> Select
                    </label>
                    <div class="col-sm-9">
                        <select class="form-select" aria-label="Default select example">
                            <option selected disabled hidden>Choice option</option>
                            <option value="1">One</option>
                            <option value="2">Two</option>
                            <option value="3">Three</option>
                        </select>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <label for="switch" class="col-sm-3 col-form-label">
                        <i class="<?php echo icon('switch'); ?>" style="width:20px;"></i> Switch
                    </label>
                    <div class="col-sm-9">
                        <div class="form-check form-switch mt-2">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckChecked" checked>
                            <label class="form-check-label" for="flexSwitchCheckChecked">Checked switch checkbox input</label>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <label for="radio" class="col-sm-3 col-form-label">
                        <i class="<?php echo icon('radio'); ?>" style="width:20px;"></i> Inline radio
                    </label>
                    <div class="col-sm-9">
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio1" value="option1" checked>
                            <label class="form-check-label" for="inlineRadio1">1</label>
                        </div>
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio2" value="option2">
                            <label class="form-check-label" for="inlineRadio2">2</label>
                        </div>
                        <div class="form-check form-check-inline mt-2">
                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="inlineRadio3" value="option3" disabled>
                            <label class="form-check-label" for="inlineRadio3">3 (disabled)</label>
                        </div>
                    </div>
                </div>
            </li>
            <li class="list-group-item">
                <div class="row">
                    <label for="checkbox" class="col-sm-3 col-form-label">
                        <i class="<?php echo icon('checkbox'); ?>" style="width:20px;"></i> Inline checkbox
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
        </ul>
    </div>
    <div class="card-footer text-body-secondary"></div>
</div>

<?php $this->endSection(); ?>
