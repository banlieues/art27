<label class="col-4 col-form-label pt-0"> Groupe de travaux </label>
<div class="col-8">
    <?php foreach($groups as $group):?>
        <div class="form-check">
            <input type="checkbox" 
                class="checkbox form-check-input"
                form="WorkForm"
                name="ids_group[]"
                onclick="devis_work_submit_button('WorkForm');"
                value="<?php echo $group->id_group;?>"
                <?php if(!empty($ids_group_selected) && in_array($group->id_group, $ids_group_selected)):?> checked <?php endif;?>
            />
            <label class="form-check-label"> <?php echo $group->label_fr;?> </label>
        </div>
    <?php endforeach;?>
</div>