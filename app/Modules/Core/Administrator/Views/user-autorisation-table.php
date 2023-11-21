<?php $cruds=["r","u","c","d"];?>
<div class="row" data-masonry='{"percentPosition": true }'>
    <?php foreach($entities_config as $entity_ref=>$entity_config):?>
        <div class="col-4 mb-2">
            <div class="card">
                <div class="card-header card-success">
                    <?php echo $themes->$entity_ref->icon ?? '';?>
                    <b><?php echo $entity_config?></b>
                </div>
                <div class="card-body">
                    <?php if(array_intersect([$entity_ref . '_r', $entity_ref . '_u', $entity_ref . '_c', $entity_ref . '_d'], $fieldsAutorisation)):?>
                        <table class="table table-bordered">
                            <thead>
                            <?php foreach(["r","u","c","d"] as $crud):?>
                                <th class="text-center" width="25%">
                                    <?php if(in_array($entity_ref . '_'. $crud, $fieldsAutorisation)):?>
                                        <?php switch($crud):
                                            case 'r': echo 'Lire'; break;
                                            case 'u': echo 'Modif'; break;
                                            case 'c': echo 'Créer'; break;
                                            case 'd': echo 'Supp'; break;
                                        endswitch;?>
                                    <?php endif;?>
                                </th>
                                <?php endforeach;?>
                            </thead>
                            <tbody>
                                <tr>
                                    <?php foreach(["r","u","c","d"] as $crud):?>
                                        <td class="text-center">
                                            <?php if(in_array($entity_ref . '_'. $crud, $fieldsAutorisation)):?>
                                                <div class="form_read"
                                                    <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                                                    >
                                                    <?php if(isset($userAutorisations) && !empty($userAutorisations->{$entity_ref.'_'.$crud})):?>
                                                        <?php if($userAutorisations->{$entity_ref.'_'.$crud}==2):?>
                                                            <?php echo fontawesome('check-double');?>
                                                        <?php elseif($userAutorisations->{$entity_ref.'_'.$crud}==1):?>
                                                            <?php echo fontawesome('check');?>
                                                        <?php endif;?>
                                                    <?php else:?>
                                                        &nbsp;
                                                    <?php endif;?>
                                                </div>
                                                <div class="form_update"
                                                    <?php if(in_array($typeDataView, ['read'])):?> style="display: none;" <?php endif;?>
                                                    >
                                                    <?php if($crud=='d'):?>
                                                        <div class="form-check">
                                                            <input type="checkbox"
                                                                class="input-nullable checkbox-radio form-check-input"
                                                                name="<?php echo $entity_ref . '_' . $crud;?>"
                                                                value="1"
                                                                <?php if(
                                                                    set_value($entity_ref . '_' . $crud)==1 ||
                                                                    (isset($userAutorisations) && isset($userAutorisations->{$entity_ref . '_' . $crud}) && $userAutorisations->{$entity_ref . '_' . $crud}==1) ||
                                                                    (!isset($userAutorisations) && in_array($entity_ref . '_'. $crud, $default_config))
                                                                ):?>
                                                                    checked
                                                                <?php endif;?>
                                                            />
                                                            <label class="form-check-label"> <?php echo fontawesome('user');?> </label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input type="checkbox"
                                                                class="input-nullable checkbox-radio form-check-input"
                                                                name="<?php echo $entity_ref . '_' . $crud;?>"
                                                                value="2"
                                                                <?php if(
                                                                    set_value($entity_ref . '_' . $crud)==2 ||
                                                                    (isset($userAutorisations) && isset($userAutorisations->{$entity_ref . '_' . $crud}) && $userAutorisations->{$entity_ref . '_' . $crud}==2) ||
                                                                    (!isset($userAutorisations) && in_array($entity_ref . '_'. $crud, $default_config))
                                                                ):?>
                                                                    checked
                                                                <?php endif;?>
                                                            />
                                                            <label class="form-check-label"> Tous </label>
                                                        </div>
                                                    <?php else:?>
                                                        <input type="checkbox"
                                                            class="input-nullable checkbox-radio form-check-input"
                                                            name="<?php echo $entity_ref . '_' . $crud;?>"
                                                            value="1"
                                                            <?php if(
                                                                !empty(set_value($entity_ref . '_' . $crud)) ||
                                                                (isset($userAutorisations) && !empty($userAutorisations->{$entity_ref . '_' . $crud})) ||
                                                                (!isset($userAutorisations) && in_array($entity_ref . '_'. $crud, $default_config))
                                                            ):?>
                                                                checked
                                                            <?php endif;?>
                                                        />
                                                    <?php endif;?>
                                                </div>
                                            <?php endif;?>
                                        </td>
                                    <?php endforeach;?>
                                </tr>
                            </tbody>
                        </table>
                    <?php endif;?>
                    <table class="table table-bordered">
                        <tbody>
                            <?php foreach($outils_config as $key_outil=>$outil_config):?>
                                <?php if(preg_match('/^' . $entity_ref . '/', $key_outil)):?>
                                    <tr>
                                        <td class="text-center">
                                            <div class="form_read"
                                                <?php if(in_array($typeDataView, ['create', 'update'])):?> style="display: none;" <?php endif;?>
                                                >
                                                    <?php if(isset($userAutorisations) && !empty($userAutorisations->$key_outil)):?> 
                                                        <?php echo fontawesome('check');?>
                                                    <?php endif;?>  
                                            </div>
                                            <div class="form_update"
                                                <?php if(in_array($typeDataView, ['read'])):?> style="display: none;" <?php endif;?>
                                                >
                                                <input type="checkbox"
                                                    class="input-nullable form-check-input"
                                                    name="<?php echo $key_outil?>"
                                                    value="1"
                                                    <?php if(
                                                        set_value($key_outil)==1 ||
                                                        (isset($userAutorisations) && !empty($userAutorisations->$key_outil)) ||
                                                        (!isset($userAutorisations) && in_array($key_outil, $default_config))
                                                    ):?> checked <?php endif;?>
                                                />
                                            </div>
                                        </td>
                                        <th colspan=3><?=$outil_config?></th>
                                    </tr>
                                <?php endif;?>
                            <?php endforeach;?>                               
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php endforeach;?>  
</div>
