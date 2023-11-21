<?php if(isset($nb_blocks) && $nb_blocks == 0):?>
    <button type="button" class="btn btn-sm pt-0" 
        onclick="tag_modal_delete(this, <?php echo $id_tag;?>);" 
        data-bs-toggle="tooltip" data-placement="top" title="Supprimer le tag"
        > 
        <?php echo fontawesome('trash-alt');?> 
    </button>
<?php endif;?>

