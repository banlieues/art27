<form id="<?php echo $form_id;?>">
    <div class="mb-2 row">
        <label class="col-2 col-form-label"> Module </label>
        <div class="col-10">
            <input class="form-control disabled" disabled value="<?php echo $row->module;?>"/>
        </div>
    </div>
    <div class="mb-2 row">
        <label class="col-2 col-form-label"> Référence </label>
        <div class="col-10">
            <input class="form-control disabled" disabled value="<?php echo $row->ref;?>"/>
        </div>
    </div>
    <div class="mb-2 row">
        <label class="col-2 col-form-label"> Description </label>
        <div class="col-10">
            <textarea class="form-control disabled"
                <?php if(!isset($row)):?>disabled<?php endif;?>
                ></textarea>
        </div>
    </div>
    <div class="mb-2 row">
        <label class="col-2 col-form-label"> Label FR </label>
        <div class="col-10">
            <textarea class="form-control" name="label_fr"><?php echo $row->label_fr;?></textarea>
        </div>
    </div>
    <div class="mb-2 row">
        <label class="col-2 col-form-label"> Label NL </label>
        <div class="col-10">
            <textarea name="label_nl" class="form-control" name="label_nl"><?php echo $row->label_nl;?></textarea>
        </div>
    </div>
</form>