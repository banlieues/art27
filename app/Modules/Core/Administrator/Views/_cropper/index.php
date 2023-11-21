<?php $this->extend("Layout\index"); ?>
<?php $this->section("body"); ?>

<div class="row">
    <div class="col-md-3">
        <?php echo $this->include('Administrator\sidebar'); ?>
    </div>

    <div class="col-md-9">
        <form action="<?php echo base_url('cropper/settings'); ?>" method="post" enctype="multipart/form-data">
            <div><?php echo csrf_field(); ?></div>
            <div class="float-end">
                <!--
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="<?php // echo icon('save'); ?>"></i> <?php // echo lang('Buttons.save'); ?>
                </button>
                -->
                <a class="btn btn-primary btn-sm" href="<?php echo base_url('cropper/settings'); ?>">
                    <i class="<?php echo icon('edit'); ?>"></i> <?php echo lang('Buttons.edit'); ?>
                </a>
            </div>

            <h4><?php echo $title.' : '.$subtitle; ?></h4>
            <div class="clearfix"></div>

            <div class="card flex-fill mb-4">
                <!-- <h5 class="card-header"><?php // echo lang('Cropper.settings_of'); ?> : <?php // echo lang('Cropper.cropper'); ?></h5> -->
                <h5 class="card-header">
                    <i class="<?php echo icon('cropper'); ?> float-end mt-1"></i>
                    <?php echo lang('Cropper.settings_of'); ?> :  <?php echo lang('Cropper.cropper'); ?>
                </h5>

                <div class="card-body-off">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <label for="select" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('cropper'); ?>" style="width:20px;"></i> <?php echo lang('Cropper.select'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" disabled class="form-control bg-white border-white width" name="" id="" value="<?php echo $cropper_settings['selected_cropper_name']; ?>">
                                    <!--
                                    <select class="form-select bg-white cropper" aria-label="Select a cropper" name="selectCropper">
                                        <option disabled hidden <?php // echo $cropper_settings['selected_cropper'] == null ? 'selected' : null; ?>><?php // echo lang('Cropper.selectcropper'); ?></option>
                                        <option value="0" <?php // echo $cropper_settings['selected_cropper'] == '0' ? 'selected' : null; ?>><?php // echo lang('Cropper.nocropper'); ?></option>
                                        <option value="1" <?php // echo $cropper_settings['selected_cropper'] == '1' ? 'selected' : null; ?>><?php // echo lang('Cropper.simplecropper'); ?></option>
                                        <option value="2" <?php // echo $cropper_settings['selected_cropper'] == '2' ? 'selected' : null; ?>>Advanced Cropper</option>
                                    </select>
                                    -->
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item param">
                            <div class="row">
                                <label for="viewMode" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('view'); ?>" style="width:20px;"></i> <?php echo lang('Cropper.viewmode'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="number" disabled step="0.1" class="form-control bg-white border-white width" name="viewMode" id="viewMode" value="<?php echo $cropper_settings['cropper_viewMode']; ?>" placeholder="">
                                    <!--
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" disabled name="viewMode" id="viewMode0" value="0" <?php // echo $cropper_settings['cropper_viewMode'] == '0' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="viewMode0">0</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" disabled name="viewMode" id="viewMode1" value="1" <?php // echo $cropper_settings['cropper_viewMode'] == '1' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="viewMode1">1</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" disabled name="viewMode" id="viewMode2" value="2" <?php // echo $cropper_settings['cropper_viewMode'] == '2' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="viewMode2">2</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" disabled name="viewMode" id="viewMode3" value="3" <?php // echo $cropper_settings['cropper_viewMode'] == '3' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="viewMode3">3</label>
                                    </div>
                                    -->
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item param">
                            <div class="row">
                                <label for="dragMode" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('drag'); ?>" style="width:20px;"></i> <?php echo lang('Cropper.dragmode'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" disabled step="0.1" class="form-control bg-white border-white width" name="dragMode" id="dragMode" value="<?php echo $cropper_settings['cropper_dragmode_name']; ?>" placeholder="">
                                    <!--
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" disabled name="dragMode" id="dragModecrop" value="crop" <?php // echo $cropper_settings['cropper_dragMode'] == 'crop' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="dragModecrop"><?php // echo lang('Cropper.crop'); ?></label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" disabled name="dragMode" id="dragModemove" value="move" <?php // echo $cropper_settings['cropper_dragMode'] == 'move' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="dragModemove"><?php // echo lang('Cropper.move'); ?></label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input" type="radio" disabled name="dragMode" id="dragModenone" value="none" <?php // echo $cropper_settings['cropper_dragMode'] == 'none' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="dragModenone"><?php // echo lang('Cropper.none'); ?></label>
                                    </div>
                                    -->
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item param">
                            <div class="row">
                                <label for="aspectRatio" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('ratio'); ?>" style="width:20px;"></i> <?php echo lang('Cropper.aspectratio'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="text" disabled step="0.1" class="form-control bg-white border-white width" name="aspectRatio" id="aspectRatio" value="<?php echo $cropper_settings['cropper_aspectratio_name']; ?>" placeholder="">
                                    <!--
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input aspectRatio" type="radio" disabled name="aspectRatio" id="aspectRatio11" value="11" <?php // echo $cropper_settings['cropper_aspectRatio'] == '11' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="aspectRatio11">1/1</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input aspectRatio" type="radio" disabled name="aspectRatio" id="aspectRatio43" value="43" <?php // echo $cropper_settings['cropper_aspectRatio'] == '43' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="aspectRatio43">4/3</label>
                                    </div>
                                    <div class="form-check form-check-inline mt-2">
                                        <input class="form-check-input aspectRatio" type="radio" disabled name="aspectRatio" id="aspectRatio169" value="169" <?php // echo $cropper_settings['cropper_aspectRatio'] == '169' ? 'checked' : null; ?>>
                                        <label class="form-check-label" for="aspectRatio169">16/9</label>
                                    </div>
                                    -->
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item param">
                            <div class="row">
                                <label for="minCropBoxWidth" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('width'); ?>" style="width:20px;"></i> <?php echo lang('Cropper.mincropboxwidth'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="number" disabled step="0.1" class="form-control bg-white border-white width" name="minCropBoxWidth" id="minCropBoxWidth" value="<?php echo $cropper_settings['cropper_minCropBoxWidth']; ?>" placeholder="">
                                </div>
                            </div>
                        </li>
                        <li class="list-group-item param">
                            <div class="row">
                                <label for="minCropBoxHeight" class="col-sm-3 col-form-label">
                                    <i class="<?php echo icon('height'); ?>" style="width:20px;"></i> <?php echo lang('Cropper.mincropboxheight'); ?>
                                </label>
                                <div class="col-sm-9">
                                    <input type="number" disabled step="0.1" class="form-control bg-white border-white height" name="minCropBoxHeight" id="minCropBoxHeight" value="<?php echo $cropper_settings['cropper_minCropBoxHeight']; ?>" placeholder="">
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="card-footer text-body-secondary"></div>
            </div>
        </form>
    </div>
</div>

<script>
// $(document).ready(function() {
document.addEventListener("DOMContentLoaded", function() {
    let cropper = $("select.cropper option:selected" ).val();
    if (cropper < 1) $( ".param" ).addClass( "d-none" );

    $(".cropper").change(function() {
        let option = $(this).val();
        if (option < 1) $( ".param" ).addClass( "d-none" );
        else $( ".param" ).removeClass( "d-none" );
    });
    
    $(".width, .aspectRatio").change(function() {
        let width = $(".width").val();
        let ratio = $('input[name="aspectRatio"]:checked').val();
        let value = parseFloat((width / get_ratio(ratio)).toFixed(1));
        $(".height").val(value);
    });
    $(".height, .aspectRatio").change(function() {
        let height = $(".height").val();
        let ratio = $('input[name="aspectRatio"]:checked').val();
        let value = parseFloat((height * get_ratio(ratio)).toFixed(1));
        $(".width").val(value);
    });
});

function get_ratio(ratio) {
    if (ratio == 11)  return 1 / 1;  // 1.0
    if (ratio == 43)  return 4 / 3;  // 1.333333333333333
    if (ratio == 169) return 16 / 9; // 1.777777777777778
}
</script>

<?php $this->endSection(); ?>

