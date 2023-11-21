<a class="icon-button text-body" 
    href="<?php echo base_url('report/block/view/' . $id_block);?>" 
    title="Afficher ou modifier le bloc"
    > 
    <?php echo fontawesome('info-circle');?> 
</a>
<button type="button" 
    class="icon-button"
    id_file=<?php echo $id_file;?>
    onclick="file_modal_info(this);" 
    title="Aperçu du bloc"
    > 
    <?php echo fontawesome('eye');?> 
</button>

