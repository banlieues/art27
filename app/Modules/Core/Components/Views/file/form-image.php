<div class="img-container col-2">
    <div class="d-flex align-items-end h-100">
        <div class="d-flex align-items-center h-100 border rounded p-1">
            <a class="text-body" target="_blank" href="<?php echo base_url("file/display/" . $img->$pk_file . "/$img->clientName");?>">
                <img class="img-fluid" 
                    alt="Image <?php echo $img->clientName;?>"
                    src="<?php echo base_url("file/display/" . $img->$pk_file . "/$img->clientName");?>"
                    title="<?php echo $img->clientName;?>"
                />
            </a>
            <input type="hidden" name="<?php echo $ref;?>[]" value="<?php echo $img->$pk_file;?>"/>
        </div>
        <div>
            <button type="button" class="btn btn-sm pb-0"
                onclick="
                    $(this).closest('.img-container').fadeOut();
                    $(this).closest('.img-container').find('input').val(null);
                "
                >
                <?php echo fontawesome('trash-alt');?>
            </button>
        </div>
    </div>
</div>
