<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<?php echo $controls->id_block;?>
<?php echo $controls->id_file;?>
<?php echo $controls->label;?>
<?php echo $controls->ids_road_them;?>
<div class="row">
    <div class="col-auto offset-3">
        <button type="button" class="btn btn-sm"
            onclick="block_tag_modal_new(<?php if(!empty($id_block)) echo $id_block;?>);"
            >
            <small class="font-weight-bold"> Ajouter un nouveau tag </small>
        </button>
    </div>
</div>
<?php echo $controls->ids_tag;?>
<?php echo $controls->comment;?>
<?php echo $controls->preview;?>
