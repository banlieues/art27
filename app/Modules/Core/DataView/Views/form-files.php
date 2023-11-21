<div class="file-container">
    <?php if(!empty($files)):?>
        <?php foreach($files as $file):?>
            <div class="file-read-container">

                <div class="row align-items-center"> 

                    <?php echo view('Components\file/read-file', ['file' => $file]);?>
                    
                    <div class="col-2">
                        <button type="button" class="btn btn-sm p-0"
                            onclick="$(this).closest('.file-read-container').hide();
                            $(this).closest('.file-read-container').find('input').val(null);"
                            >
                            <?php echo fontawesome('trash-alt');?>
                        </button>
                    </div>

                </div>

                <input type="hidden" name="<?php echo $formdata;?>[]" value="<?php echo $file->id;?>"/>

            </div>
        <?php endforeach;?>
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
        name="<?php echo $formdata;?>[]"
        multiple
        <?php if(!empty($disabled)) echo $disabled;?>
    />
</div>
<script>
    // var loadImage = function(event) {
    //     var output = document.getElementById('img_<?php //echo $index;?>');
    //     output.src = URL.createObjectURL(event.target.files[0]);
    //     output.onload = function() {
    //         URL.revokeObjectURL(output.src) // free memory
    //     }
    // };
</script>