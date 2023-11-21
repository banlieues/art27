<?php if(!empty($file)):?>
    <?php if(empty($file->isUndefined)):?>
        <div class="col-1">
            <?php echo $file->icon;?> 
        </div>
        <div class="col-8 text-truncate">
            <a role="button" class="link-dark" target="_blank" 
                href="<?php echo base_url("file/display/$file->id/$file->clientName");?>"
                title="<?php echo $file->clientName;?>"
                >
                <small> <?php echo $file->clientName;?> </small>
            </a>
        </div>
    <?php else:?>
        <div class="col-1">
            <?php echo fontawesome('link-slash');?> 
        </div>
        <div class="col-8">
            <small> <?php echo $file->clientName;?> </small>
        </div>
    <?php endif;?>
<?php endif;?>

