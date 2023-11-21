<?php if(!empty($id_road_parent)):?>
    <input type="hidden" name="id_road_parent" value="<?php echo $id_road_parent;?>"/>
<?php endif;?>
<?php if(!empty($is_terminus)):?>
    <input type="hidden" name="is_terminus" value="1"/>
<?php endif;?>

<div class="row mb-1">
    <label class="col-3 col-form-label"> Référence </label>
    <div class="col-9">
        <input type="text" class="form-control-plaintext" value="<?php if(isset($road->reference)) echo $road->reference;?>"/>
    </div>
</div>
<div class="row mb-1">
    <label class="col-3 col-form-label"> Label FR </label>
    <div class="col-9">
        <input type="text" name="label_fr" class="form-control" value="<?php if(isset($road->label_fr)) echo $road->label_fr;?>"/>
    </div>
</div>
<div class="row mb-1">
    <label class="col-3 col-form-label"> Label NL </label>
    <div class="col-9">
        <input type="text" name="label_nl" class="form-control" value="<?php if(isset($road->label_nl)) echo $road->label_nl;?>"/>
    </div>
</div>
<div class="row mb-1">
    <label class="col-3 col-form-label"> Informations FR </label>
    <div class="col-9">
        <input type="text" name="annotation_fr" class="form-control" value="<?php if(isset($road->annotation_fr)) echo $road->annotation_fr;?>"/>
    </div>
</div>
<div class="row mb-1">
    <label class="col-3 col-form-label"> Informations NL </label>
    <div class="col-9">
        <input type="text" name="annotation_nl" class="form-control" value="<?php if(isset($road->annotation_nl)) echo $road->annotation_nl;?>"/>
    </div>
</div>
<div class="row mb-1">
    <label class="col-3 col-form-label pt-0"> Chemin actif ? </label>
    <div class="col-9">
        <div class="form-check form-check-inline">
            <input type="radio" id="roadIsActifYes" class="form-check-input" name="isActive" value="1" 
                <?php if((isset($road->isActive) && $road->isActive == 1) || !isset($road->isActive)):?> checked <?php endif;?>
                />
            <label class="form-check-label" for="roadIsActifYes">
                Oui
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" id="roadIsActifNo" class="form-check-input" name="isActive" value="0" 
                <?php if((isset($road->isActive) && $road->isActive == 0)):?> checked <?php endif;?>
                />
            <label class="form-check-label" for="roadIsActifNo">
                Non
            </label>
        </div>
    </div>
</div>
<div class="row mb-1">
    <label class="col-3 col-form-label pt-0"> Champ texte </label>
    <div class="col-9">
        <div class="form-check form-check-inline">
            <input type="radio" id="roadHasTextYes" class="form-check-input" name="has_text" value="1" 
                <?php if(!empty($road->has_text)):?> checked <?php endif;?>
                />
            <label class="form-check-label" for="roadHasTextYes">
                Oui
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio" id="roadHasTextNo" class="form-check-input" name="has_text" value="0" 
                <?php if(empty($road->has_text)):?> checked <?php endif;?>
                />
            <label class="form-check-label" for="roadHasTextNo">
                Non
            </label>
        </div>
    </div>
</div>