<div class="row mb-1">
    <label class="col-4 col-form-label"> Chemin </label>
    <label class="col-8 col-form-label"> <?php echo implode(' > ', $road->labels_main);?> </label>
</div>
<div class="row mb-1">
    <label class="col-4 col-form-label"> Label </label>
    <div class="col-8 form_read"
        form="<?php echo $form_id_road_update;?>"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label fw-bold">
            <?php echo $road->label_fr;?>
        </label>
    </div>
    <div class="col-8 form_update"
        form="<?php echo $form_id_road_update;?>"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <input type="text" 
            class="form-control" 
            form="<?php echo $form_id_road_update;?>" 
            name="<?php echo $form_id_road_update;?>[label_fr]" 
            value="<?php echo $road->label_fr;?>" 
            disabled
        />
    </div>
</div>
<div class="row mb-1">
    <label class="col-4 col-form-label"> Description FR </label>
    <div class="col-8 form_read"
        form="<?php echo $form_id_road_update;?>"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label text-break">
            <?php echo $road->annotation_fr;?>
        </label>
    </div>
    <div class="col-8 form_update"
        form="<?php echo $form_id_road_update;?>"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <textarea rows="3"
            class="form-control" 
            form="<?php echo $form_id_road_update;?>" 
            name="<?php echo $form_id_road_update;?>[annotation_fr]" 
            disabled
        ><?php echo $road->annotation_fr;?></textarea>
    </div>
</div>
<div class="row mb-1">
    <label class="col-4 col-form-label"> Description NL </label>
    <div class="col-8 form_read"
        form="<?php echo $form_id_road_update;?>"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label text-break">
            <?php echo $road->annotation_nl;?>
        </label>
    </div>
    <div class="col-8 form_update"
        form="<?php echo $form_id_road_update;?>"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <textarea rows="3"
            class="form-control" 
            form="<?php echo $form_id_road_update;?>" 
            name="<?php echo $form_id_road_update;?>[annotation_nl]" 
            disabled
        ><?php echo $road->annotation_nl;?></textarea>
    </div>
</div>
<div class="row mb-1">
    <label class="col-4 col-form-label"> Notes internes </label>
    <div class="col-8 form_read"
        form="<?php echo $form_id_road_update;?>"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label">
            <?php echo $road->comment;?>
        </label>
    </div>
    <div class="col-8 form_update"
        form="<?php echo $form_id_road_update;?>"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        >
        <textarea rows="3"
            class="form-control" 
            form="<?php echo $form_id_road_update;?>" 
            name="<?php echo $form_id_road_update;?>[comment]" 
            disabled
        ><?php echo $road->comment;?></textarea>
    </div>
</div>
<div class="row mb-1">
    <label class="col-4 col-form-label"> Période de calcul </label>
    <div class="col-8 form_read"
        form="<?php echo $form_id_road_update;?>"
        <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
        >
        <label class="col-form-label">
            <?php echo $road->period_month_calcul;?> mois
        </label>
    </div>
    <div class="col-8 form_update"
        <?php if($typeDataView=='read'):?> style="display: none;" <?php endif;?>
        form="<?php echo $form_id_road_update;?>"
        >
        <div class="input-group">
            <input type="text" 
                class="form-control" 
                form="<?php echo $form_id_road_update;?>" 
                name="<?php echo $form_id_road_update;?>[period_month_calcul]" 
                value="<?php echo $road->period_month_calcul;?>" 
                disabled
            /> 
            <span class="input-group-text"> mois </span>
        </div>
    </div>
</div>