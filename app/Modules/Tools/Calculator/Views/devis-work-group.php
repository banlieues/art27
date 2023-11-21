<div id="DevisWork<?php echo $group->id_work ?? '##i##';?>Group<?php echo $group->id_group;?>Anchor"
    class="border border-top-0"
    >
    <div class="sticky_button p-2 bg-white border-bottom">
        <div class="row">
            <div class="col-1 text-center">
                <div class="form_read"
                    <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                    >
                    <?php if(!empty($group->is_prior)):?>
                        <img src="<?php echo base_url('images/logos/homegrade-logo-round-red.svg');?>" height="30px" width="30px" alt="Logo travaux prioritaires"/>
                    <?php endif;?>
                    <?php if(!empty($group->is_recommended)):?>
                        <img src="<?php echo base_url('images/logos/homegrade-logo-round-green.svg');?>" height="30px" width="30px" alt="Logo travaux conseillés"/>
                    <?php endif;?>
                </div>
            </div>
            <div class="col-6 d-flex">
                <div>
                    <?php echo $themes->calculator_group->icon;?>
                    <?php echo $group->label_fr;?>
                </div>
                <div class="form_read"
                    <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                    >
                    <?php if(!empty($group->quantity)):?>
                        <span class="ms-2 badge text-bg-secondary">
                            <?php echo $group->quantity;?>
                            <?php echo $group->measure;?>
                        </span>
                    <?php endif;?>
                </div>
                <div class="form_read"
                    <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                    >
                    <?php if(empty($group->ids_road) || empty($group->quantity)):?>
                        <span class="ms-2 badge text-bg-danger"> Incomplet </span>
                    <?php endif;?>
                </div>
            </div>
            <div class="col-5 form_read text-end"
                <?php if(
                    in_array($typeDataView, ['create', 'update']) || 
                    empty($group->quantity) || empty($group->ids_road)
                ):?> style="display: none;" <?php endif;?>
                >
                <div class="row">
                    <div class="col"> PU </div>
                    <div class="col"> P HT </div>
                    <div class="col"> TVA </div>
                    <div class="col"> P TVAC </div>
                </div>
            </div>
            <div class="col form_update"
                <?php if(in_array($typeDataView, ['read'])):?> style="display: none;" <?php endif;?>
                >
                <div class="row align-items-center">
                    <div class="col">
                        <div class="input-group input-group-sm">
                            <input type="text" 
                                class="form-control <?php if(empty($group->quantity)):?> border-danger <?php endif;?>"
                                form="DevisForm"
                                name="works[<?php echo $group->id_work ?? '##i##';?>][groups][<?php echo $group->id_group;?>][quantity]"
                                placeholder="Quantité"
                                value="<?php if(!empty($group->quantity)) echo $group->quantity;?>"
                            />
                            <span class="input-group-text"> <?php echo $group->measure;?> </span>
                        </div>
                    </div>
                    <div class="col-auto">
                        <div class="form-check form-check-inline">
                            <input type="checkbox"
                                class="form-check-input input-nullable"
                                form="DevisForm"
                                name="works[<?php echo $group->id_work ?? '##i##';?>][groups][<?php echo $group->id_group;?>][is_prior]"
                                value="1"
                                <?php if(!empty($group->is_prior)):?> checked <?php endif;?>
                            />
                            <div class="form-check-label">
                                <img src="<?php echo base_url('images/logos/homegrade-logo-round-red.svg');?>" height="20px" width="20px" alt="Logo travaux prioritaires"/>
                                <small> Prioritaire </small>
                            </div>
                        </div>
                        <div class="form-check form-check-inline">
                            <input type="checkbox"
                                class="form-check-input input-nullable"
                                form="DevisForm"
                                name="works[<?php echo $group->id_work ?? '##i##';?>][groups][<?php echo $group->id_group;?>][is_recommended]"
                                value="1"
                                <?php if(!empty($group->is_recommended)):?> checked <?php endif;?>
                            />
                            <div class="form-check-label">
                                <img src="<?php echo base_url('images/logos/homegrade-logo-round-green.svg');?>" height="20px" width="20px" alt="Logo travaux conseillés"/>
                                <small> Conseillé </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="border-bottom border-2">
        <div class="form_read container"
            <?php if(
                in_array($typeDataView, ['create', 'update']) || empty($group->ids_road)
            ):?> style="display: none;" <?php endif;?>
            >
            <?php echo $group->roads_html;?>
            <?php if(!empty($group->quantity) && !empty($group->ids_road)
            ):?>
                <div class="row text-end fw-bold">
                    <div class="col-1 offset-6" title="Total par groupe de travaux"> Total </div>
                    <div class="col-5">
                        <div class="row bg-warning" style="--bs-bg-opacity: .3;">
                            <div class="col"> <?php echo round($group->total->pu, 2);?> </div>
                            <div class="col"> <?php echo round($group->total->ht, 2);?> </div>
                            <div class="col"> <?php echo round($group->total->tva, 2);?> </div>
                            <div class="col"> <?php echo round($group->total->tvac, 2);?> </div>
                        </div>
                    </div>
                </div>
            <?php endif;?>
        </div>
        <div class="form_update py-4"
            <?php if(in_array($typeDataView, ['read'])):?> style="display: none;" <?php endif;?>
            >
            <?php echo $group->roads_form_html;?>
        </div>
    </div>
</div>
