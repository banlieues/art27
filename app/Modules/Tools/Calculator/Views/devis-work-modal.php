<form id="WorkForm">
    <input type="hidden" form="WorkForm" name="id_work" value="<?php echo $work->id_work ?? '##i##';?>"/>
    <?php if(isset($work)):?>
        <?php $work_data = clone_object($work);?>
        <?php unset($work_data->groups_html);?>
        <input type="hidden" form="WorkForm" name="work" value="<?php echo json_encode($work_data);?>"/>
    <?php endif;?>
    <div class="row mb-2">
        <label class="col-4 col-form-label"> Nom de l'ouvrage </label>
        <div class="col-8">
            <input type="text" 
                class="form-control"
                form="WorkForm"
                name="label"
                onchange="devis_work_submit_button('WorkForm');"
                value="<?php if(isset($work->label)) echo $work->label;?>"
            />
        </div>
    </div>
    <div class="row mb-2">
        <label class="col-4 col-form-label"> Description </label>
        <div class="col-8">
            <textarea 
                class="form-control"
                form="WorkForm"
                name="annotation"
            ><?php if(isset($work->annotation)) echo $work->annotation;?></textarea>
        </div>
    </div>
    <div class="row mb-2">
        <label class="col-4 col-form-label"> Thématique </label>
        <div class="col-8">
            <?php $ClientLibrary = new \Calculator\Libraries\ClientLibrary();?>
            <?php echo $ClientLibrary->GetThemTagHtml('id_them', 'WorkForm', $work->id_them ?? null);?>
        </div>
    </div>
    <div id="DevisGroups" class="row mb-2" <?php if(empty($work->groups_html)):?> style="display: none;" <?php endif;?>>
        <?php if(isset($work->groups_html)) echo $work->groups_html;?>
    </div>
</form>

