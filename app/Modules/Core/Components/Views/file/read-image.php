<?php if(!empty($file)):?>
    <img class="img-fluid img_<?php echo $index;?>" 
        src="<?php echo base_url('file/display/' . $file->id . '/' . $file->clientName);?>"
    />
<?php endif;?>
