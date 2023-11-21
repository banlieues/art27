<?php if(empty($nb_deposits)):?>
    <div class="text-center m-5">
        <h3> <?php echo t("Pas de demande web trouvé avec ces critères.", $namespace);?> </h3>
    </div>  
<?php else:?>
    <div id="deposits-list" class="table-responsive table-fullview"> 
        <table class="table table-bordered table-striped table-hover my-0 table-sm w-100">
            <thead class="table-light sticky-top">
                <tr>
                    <?php echo $getTh;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($deposits as $deposit):?>
                    <tr>
                        <?php foreach($columns as $key=>$value):?>
                            <?php switch($key): 
                                case 'action':?>
                                    <td class="text-center"> 
                                        <?php if(!empty($deposit->id_user_on_work) && $deposit->id_user_on_work!= session('loggedUserId')):?>
                                            <div id_deposit="<?php echo $deposit->id_deposit;?>" 
                                                class="badge bg-warning d-inline" 
                                                title="<?php echo t("Fiche en cours de traitement par", $namespace, ['withButton'=>false]) . $deposit->user_on_work;?>"
                                                user_on_work
                                                > 
                                                <?php echo fontawesome('cogs');?> 
                                            </div>
                                        <?php endif;?>
                                        <button type="button" 
                                            class="btn btn-sm btn-link link-dark py-0" 
                                            title="<?php echo t("Voir les infos de la demande", $namespace, ['withButton'=>false]);?>"
                                            onclick="
                                                set_worker('on', <?php echo $deposit->id_deposit;?>);
                                                deposit_info_modal(this, <?php echo $deposit->id_deposit;?>)
                                            "
                                            > 
                                            <?php echo fontawesome('info-circle');?> 
                                        </button>
                                        <?php if($Autorisation->is_autorise('company_d')):?>
                                            <button type="button" 
                                                class="btn btn-sm btn-link link-danger p-0" 
                                                title="<?php echo t("Supprimer la demande", $namespace, ['withButton'=>false]);?>"
                                                onclick="
                                                    set_worker('on', <?php echo $deposit->id_deposit;?>);
                                                    deposit_delete_modal(this, <?php echo $deposit->id_deposit;?>);
                                                "
                                                > 
                                                <?php echo fontawesome('trash-alt');?> 
                                            </button>
                                        <?php endif;?>
                                    </td>
                                <?php break;?>
                                <?php case 'comment':?>
                                    <td class="text-center">
                                        <?php if(!empty($deposit->$key)):?>
                                            <a role="button" class="modalView btn btn-sm btn-link link-dark"
                                                href="<?php echo base_url("company/deposit/view/$deposit->id_deposit/$key");?>"
                                                title="<?php echo t("Notes", $namespace, ['withButton'=>false]);?>"
                                                data-view-title="<?php echo t("Notes", $namespace, ['withButton'=>false]);?>"
                                                >
                                                <?php echo fontawesome('clipboard');?>
                                            </a>
                                        <?php endif;?>
                                    </td>
                                <?php break;?>
                                <?php case 'gf_date_created':?>
                                    <td>
                                        <?php echo convert_date_en_to_fr_with_h($deposit->$key);?>
                                    </td>
                                <?php break;?>
                                <?php default :?>
                                    <td>
                                        <?php if(is_object($deposit->$key)):?>
                                            <?php echo $deposit->$key->label;?>
                                        <?php else:?>
                                            <?php echo $deposit->$key;?>
                                        <?php endif;?>
                                    </td>
                                <?php break;?>
                            <?php endswitch;?>  
                        <?php endforeach;?>
                    </tr>
                <?php endforeach;?>
            </tbody>   
        </table>
    </div>      
<?php endif;?>