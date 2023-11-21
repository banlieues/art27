<?php if(!empty($id_road_parent)):?>
    <input type="hidden" name="id_road_parent" value="<?php echo $id_road_parent;?>"/>
<?php endif;?>

<?php if($typeDataView=='read'):?>
    <div class="row mb-1">
        <label class="col-4 col-form-label"> Chemin </label>
        <label class="col-8 col-form-label"> <?php echo implode(' > ', $group->labels_main);?> </label>
    </div>
<?php endif;?>

<div class="row mb-2">
    <label class="col-4 col-form-label"> Label FR </label>
    <div class="col-8 form_read" 
        form="<?php echo $form_id;?>"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label fw-bold">
            <?php if(isset($group->label_fr)) echo $group->label_fr;?>
        </label>
    </div>
    <div class="col-8 form_update"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        form="<?php echo $form_id;?>"
        >
        <input type="text" 
            class="form-control" 
            form="<?php echo $form_id;?>" 
            name="label_fr" 
            value="<?php if(isset($group->label_fr)) echo $group->label_fr;?>" 
        />
    </div>
</div>
<div class="row mb-2">
    <label class="col-4 col-form-label"> Label NL </label>
    <div class="col-8 form_read"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label fw-bold">
            <?php if(isset($group->label_nl)) echo $group->label_nl;?>
        </label>
    </div>
    <div class="col-8 form_update"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <input type="text" 
            class="form-control" 
            form="<?php echo $form_id;?>" 
            name="label_nl" 
            value="<?php if(isset($group->label_nl)) echo $group->label_nl;?>" 
        />
    </div>
</div>
<div class="row mb-2">
    <label class="col-4 col-form-label"> Description FR </label>
    <div class="col-8 form_read text-break" 
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label">
            <?php if(isset($group->annotation_fr)) echo $group->annotation_fr;?>
        </label>
    </div>
    <div class="col-8 form_update"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <textarea class="form-control" rows="3"
            form="<?php echo $form_id;?>" 
            name="annotation_fr" 
        ><?php if(isset($group->annotation_fr)) echo $group->annotation_fr;?></textarea>
    </div>
</div>
<div class="row mb-2">
    <label class="col-4 col-form-label"> Description NL </label>
    <div class="col-8 form_read text-break" 
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label">
            <?php if(isset($group->annotation_nl)) echo $group->annotation_nl;?>
        </label>
    </div>
    <div class="col-8 form_update"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <textarea class="form-control" rows="3"
            form="<?php echo $form_id;?>" 
            name="annotation_nl" 
        ><?php if(isset($group->annotation_nl)) echo $group->annotation_nl;?></textarea>
    </div>
</div>
<div class="row mb-2">
    <label class="col-4 col-form-label"> Notes internes </label>
    <div class="col-8 form_read"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label">
            <?php if(isset($group->comment)) echo $group->comment;?>
        </label>
    </div>
    <div class="col-8 form_update"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <textarea class="form-control" rows="3" 
            form="<?php echo $form_id;?>" 
            name="comment" 
        ><?php if(isset($group->comment)) echo $group->comment;?></textarea>
    </div>
</div>
<div class="row mb-2">
    <label class="col-4 col-form-label"> Unité de mesure </label>
    <div class="col-8 form_read"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label">
            <?php if(isset($group->measure)) echo $group->measure;?>
        </label>
    </div>
    <div class="col-8 form_update"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <input type="text" 
            class="form-control" 
            form="<?php echo $form_id;?>" 
            name="measure" 
            value="<?php if(isset($group->measure)) echo $group->measure;?>"
        />
    </div>
</div>
<div class="row mb-2">
    <label class="col-4 col-form-label pt-0"> Activé </label>
    <div class="col-8 form_read"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="form-check-label">
            <?php echo !empty($group->isActive) ? 'Oui' : 'Non';?>
        </label>
    </div>
    <div class="col-8 form_update"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <div class="form-check form-check-inline">
            <input type="radio"
                id="groupIsActifYes"
                class="form-check-input"
                name="isActive"
                value="1"
                form="<?php echo $form_id;?>"
                <?php if((isset($group->isActive) && $group->isActive == 1) || !isset($group->isActive)):?> checked <?php endif;?>
            />
            <label class="form-check-label" for="groupIsActifYes">
                Oui
            </label>
        </div>
        <div class="form-check form-check-inline">
            <input type="radio"
                id="groupIsActifNo"
                class="form-check-input"
                name="isActive"
                value="0"
                form="<?php echo $form_id;?>"
                <?php if((isset($group->isActive) && $group->isActive == 0)):?> checked <?php endif;?>
            />
            <label class="form-check-label" for="groupIsActifNo">
                Non
            </label>
        </div>
    </div>
</div>
