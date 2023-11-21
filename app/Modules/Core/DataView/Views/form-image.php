<div class="file-container">

    <input type="file" class="form-control my-2"
        accept="image/*"
        name="<?php echo $formdata;?>"
        onchange="loadImage(this, event);"
        <?php if(!empty($disabled)) echo $disabled;?>
        <?php if(!empty($file)):?> style="display: none;" <?php endif;?>
    />

    <div class="file-read-container" <?php if(empty($file)):?> style="display: none;" <?php endif;?>>
        <div class="row">
            <div class="col-10">
                <?php echo view('Components\file/read-image', [
                    'file' => !empty($file) ? $file : (object) ['id' => 0, 'clientName' => ''],
                    'index' => $index,
                ]);?>
            </div>
            <div class="col-2">
                <button type="button" class="btn btn-sm p-0"
                    onclick="unlink_image(this);"
                    >
                    <?php echo fontawesome('trash-alt');?>
                </button>
            </div>
        </div>
    </div>

    <?php if(!empty($file)):?>
        <input type="hidden" name="<?php echo $formdata;?>" value="<?php echo $file->id;?>"/>
    <?php endif;?>

</div>