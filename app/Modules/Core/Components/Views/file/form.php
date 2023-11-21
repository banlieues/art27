<div class="file-container">
    <?php if(!empty($imgs)):?>
        <div class="imgs-container">
            <label class="col-form-label"> Images </label>
            <div class="row">
                <?php foreach($imgs as $img):?>
                    <?php echo view('Components\file/form-image', ['img' => $img]);?>
                <?php endforeach;?>
            </div>
        </div>
        <hr>  
    <?php endif;?>
    <?php if(!empty($docs)):?>
        <div class="docs-container">
            <label class="col-form-label"> Documents </label>
            <?php foreach($docs as $doc):?>
                <?php echo view('Components\file/form-doc', ['doc' => $doc]);?>
            <?php endforeach;?>
        </div>
        <hr>
    <?php endif;?>

    <input type="file" class="form-control my-2"
        accept="
            audio/*, 
            image/*, 
            video/*, 
            application/msword, 
            application/vnd.openxmlformats-officedocument.wordprocessingml.document, 
            application/vnd.oasis.opendocument.presentation,
            application/vnd.oasis.opendocument.spreadsheet,
            application/vnd.oasis.opendocument.text,
            application/pdf,
            application/vnd.ms-powerpoint,
            application/vnd.openxmlformats-officedocument.presentationml.presentation,
            text/plain,
            application/vnd.ms-excel,
            application/vnd.openxmlformats-officedocument.spreadsheetml.sheet
        "
        name="<?php echo $ref;?>[]"
        multiple
        <?php if(!empty($disabled)) echo $disabled;?>
    />
</div>