<hr>
<form id="blockResultsForm">
    <div class="form-group row">
        <label class="col-3 col-form-label pt-0">Résultats de la recherche </label>
        <div class="col-9">
            <?php foreach($blocks as $block):?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="ids_block[]" value="<?php echo $block->id_block;?>">
                    <label class="form-check-label">
                        <?php echo $block->label;?>
                    </label>
                </div>
            <?php endforeach;?>
        </div>
    </div>
</form>