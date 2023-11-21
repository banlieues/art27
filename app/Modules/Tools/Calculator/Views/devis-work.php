<div id="DevisWork<?php echo $work->id_work ?? '##i##';?>Anchor"
    id_work="<?php echo $work->id_work ?? '##i##';?>"
    >
    <input type="hidden" form="DevisForm" name="works[<?php echo $work->id_work ?? '##i##';?>][label]" value="<?php echo $work->label;?>"/>
    <input type="hidden" form="DevisForm" name="works[<?php echo $work->id_work ?? '##i##';?>][annotation]" value="<?php echo $work->annotation;?>"/>
    <input type="hidden" form="DevisForm" name="works[<?php echo $work->id_work ?? '##i##';?>][id_them]" value="<?php echo $work->id_them;?>"/>
    <div class="sticky_button d-flex align-items-center p-2 bg-body-secondary">
        <div class="mx-4"></div>
        <div class="mx-4"></div>
        <div class="flex-grow-1">
            Ouvrage : <?php echo $work->label;?>
            <?php if(!empty($work->annotation)):?>
                <br>
                <small> <?php echo $work->annotation;?> </small>
            <?php endif;?>
        </div>
        <div class="d-flex">
            <div class="form_update"
                <?php if(in_array($typeDataView, ['read'])):?> style="display: none;" <?php endif;?>
                >
                <button type="button"
                    class="btn btn-sm btn-outline-<?php echo $themes->calculator->color;?> ms-2"
                    onclick="work_edit_modal(this, '<?php echo $work->id_work ?? '##i##';?>');"
                    title="Modifier les données de l'ouvrage"
                    >
                    <?php echo fontawesome('edit');?>
                </button>
            </div>
            <div class="form_update"
                <?php if(in_array($typeDataView, ['read'])):?> style="display: none;" <?php endif;?>
                >
                <button class="btn btn-sm btn-outline-danger ms-2"
                    onclick="work_delete(this, <?php echo $work->id_work ?? '##i##';?>);"
                    >
                    <?php echo fontawesome('trash-alt');?>
                </button>
            </div>
        </div>
    </div>
    <?php if(!empty($work->groups)):?>
        <?php foreach($work->groups as $group):?>
            <?php echo view('Calculator\devis-work-group', ['group' => $group, ]);?>
        <?php endforeach;?>
    <?php endif;?>
    <?php if(!empty($work->is_complete)):?>
        <div class="form_read container"
            <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
            >
            <div class="row text-end fw-bold bg-warning" style="--bs-bg-opacity: .6;">
                <div class="col-6 offset-1"> Ouvrage - <?php echo $work->label;?> </div>
                <div class="col-5">
                    <div class="row">
                        <div class="col"> Total </div>
                        <div class="col"> <?php echo round($work->total->ht, 2);?> </div>
                        <div class="col"> <?php echo round($work->total->tva, 2);?> </div>
                        <div class="col"> <?php echo round($work->total->tvac, 2);?> </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif;?>
</div>



