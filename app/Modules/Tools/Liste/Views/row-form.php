<form id="<?php echo $form_id;?>" method="post" action="<?php echo $form_action;?>">

    <div class="validation-alert alert alert-warning" style="display: none;"> </div>

    <?php if(isset($fields->$pk)):?>
        <input type="hidden" name="<?php echo $pk;?>" value="<?php echo $fields->$pk;?>"/>
    <?php endif;?>
    <?php if(isset($fields->label_original)):?>
        <div class="form-group row mb-2">
            <label class="col-4 col-form-label" for="">Label d'origine</label>
            <div class="col-8">
                <input type="text" class="form-control"
                    <?php if(!empty($fields->label_original)):?>
                        value="<?php echo $fields->label_original;?>"
                        disabled
                    <?php endif;?>
                />
            </div>
        </div>    
    <?php endif;?>
    <?php foreach ($fields as $key=>$value):?>
        <?php if($key=='is_actif'):?>
            <div class="form-group row mb-2">
                <label class="col-4 col-form-label pt-0"> Activé </label>
                <div class="col-8">
                    <div class="form-check form-check-inline">
                        <input type="radio"
                            class="form-check-input"
                            id="<?php echo $key;?>_yes"
                            name="<?php echo $key;?>"
                            value="1"
                            <?php if(isset($value) && $value==1):?> checked <?php endif;?>
                        /> 
                        <label class="form-check-label" for="<?php echo $key;?>_yes"> Oui </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="radio"
                            class="form-check-input"
                            id="<?php echo $key;?>_no"
                            name="<?php echo $key;?>"
                            value="0"
                            <?php if(isset($value) && $value==0):?> checked <?php endif;?>
                        /> 
                        <label class="form-check-label" for="<?php echo $key;?>_no"> Non </label>
                    </div>
                </div>
            </div>            
        <?php elseif(!in_array($key, [$pk, 'rank', 'label_original', 'date_creation', 'user_creation', 'date_modification', 'user_modification', 'id_user', 'id_user_creation', 'id_user_modification', 'updated_at', 'updated_by', 'created_at', 'created_by', 'prenom_updated', 'nom_updated', 'prenom_created', 'nom_created'])):?>
            
            <div class="form-group row mb-2">
                <label class="col-4 col-form-label" for="">
                    <?php if(in_array($key, ['label', 'label_fr', 'name'])):?> Label FR
                    <?php elseif(in_array($key, ['label_nl'])):?> Label NL
                    <?php elseif(in_array($key, ['comment'])):?> Notes
                    <?php else: echo $key;?>
                    <?php endif;?>
                    <small> [<?php echo $key;?>] </small>
                </label>
                <div class="col-8">
                    <input type="text" class="form-control"  name="<?php echo $key;?>" value="<?php echo $value;?>">
                </div>
            </div>
        <?php endif;?>
    <?php endforeach;?>
    <?php if(isset($fields->updated_at) && isset($fields->updated_by) && isset($fields->created_at) && isset($fields->created_by)):?>
        <div class="text-end">
            <small>
                Màj par <?php echo fullname($fields->prenom_updated, $fields->nom_updated);?> le <?php echo convert_date_en_to_fr_with_h($fields->updated_at, true, false);?> <br>
                Créé par <?php echo fullname($fields->prenom_created, $fields->nom_created);?> le <?php echo convert_date_en_to_fr_with_h($fields->created_at, true, false);?> 
            </small>
        </div>
    <?php endif; ?>
</form>


