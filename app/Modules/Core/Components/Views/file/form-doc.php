<div class="doc-container row align-items-center"> 
    <input type="hidden" name="<?php echo $ref;?>[]" value="<?php echo $doc->$pk_file;?>"/>
    <div class="col-1"> 
        <?php echo $doc->icon;?> 
    </div>
    <div class="col-10 text-truncate">
        <a role="button" class="link-dark" target="_blank" 
            href="<?php echo base_url("file/display/" . $doc->$pk_file . "/$doc->clientName");?>"
            title="<?php echo $doc->clientName;?>"
            >
            <small> <?php echo $doc->clientName;?> </small>
        </a>
    </div>
    <div class="col-1">
        <button type="button" class="btn btn-sm p-0"
            onclick="
                $(this).closest('.doc-container').fadeOut();
                $(this).closest('.doc-container').find('input').val(null);
            "
            >
            <?php echo fontawesome('trash-alt');?>
        </button>
    </div>
</div>

