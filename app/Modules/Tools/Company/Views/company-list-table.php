<?php if(empty($nb_companies)):?>
    <div class="text-center m-5">
        <h3> <?php echo t("Pas d'entreprise trouvée avec ces critères.", $namespace);?> </h3>
    </div>  
<?php else:?>
    <div id="companies-list" class="table-responsive table-fullview"> 
        <table class="table table-bordered table-striped table-hover my-0 table-sm w-100">
            <thead class="table-light sticky-top">
                <tr>
                    <?php echo $getTh;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($companies as $company):?>
                    <tr>
                        <?php foreach($columns as $key=>$value):?>
                            <?php switch($key): 
                                case 'action':?>
                                    <td class="text-center"> 
                                        <?php if(!empty($company->id_user_on_work) && $company->id_user_on_work!= session('loggedUserId')):?>
                                            <div id_company="<?php echo $company->id_company;?>" 
                                                class="badge bg-warning d-inline" 
                                                title="<?php echo t("Fiche en cours de traitement par", $namespace, ['withButton' => false]) . ' ' . $company->user_on_work;?>"
                                                user_on_work
                                                > 
                                                <?php echo fontawesome('cogs');?> 
                                            </div>
                                        <?php endif;?>
                                        <a role="button" 
                                            class="btn btn-sm btn-link link-dark"
                                            title="<?php echo t("Aller à la fiche de l'entreprise", $namespace, ['withButton' => false]);?>"
                                            href="<?php echo base_url("company/company/view/$company->id_company");?>"
                                            onclick="waiting_start(this);"
                                            > 
                                            <?php echo fontawesome('info-circle');?> 
                                        </a>
                                    </td>
                                <?php break;?>
                                <?php case 'comment':?>
                                    <td class="text-center">
                                        <?php if(!empty($company->$key)):?>
                                            <a role="button" class="modalView btn btn-sm btn-link link-dark"
                                                href="<?php echo base_url("company/company/view/$company->id_company/$key");?>"
                                                title="<?php echo t("Notes", $namespace, ['withButton' => false]);?>"
                                                data-view-title="Notes"
                                                >
                                                <?php echo fontawesome('clipboard');?>
                                            </a>
                                        <?php endif;?>
                                    </td>
                                <?php break;?>
                                <?php case 'user_lastname':?>
                                    <td> 
                                        <?php echo fullname($company->user_name, mb_strtoupper($company->user_lastname));?>
                                    </td>
                                <?php break;?>
                                <?php case 'created_at':?>
                                <?php case 'updated_at':?>
                                    <td>
                                        <?php echo convert_date_en_to_fr_with_h($company->$key);?>
                                    </td>
                                <?php break;?>
                                <?php default :?>
                                    <td>
                                        <?php if(is_object($company->$key)):?>
                                            <?php echo $company->$key->label;?>
                                        <?php else:?>
                                            <?php echo $company->$key;?>
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