<?php if(empty($nb_deposits)):?>
    <div class="text-center m-5">
        <h3>Pas de demande web trouvé avec ces critères.</h3>
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
                                    <td class="text-nowrap">
                                        <div class="d-flex align-items-center justify-content-end">
                                            <?php if(!empty($deposit->id_user_on_work)):?>
                                                <div id_deposit="<?php echo $deposit->id_deposit;?>" 
                                                    user_on_work
                                                    <?php if($deposit->id_user_on_work==session('loggedUserId')):?>
                                                        class="badge bg-<?php echo $themes->demande_web->color;?> d-inline px-1 ms-1" 
                                                        title="Les autres utilisateurs voient que vous êtes en cours de traitement sur cette demande."
                                                    <?php else:?>
                                                        class="badge bg-warning d-inline px-1 ms-1" 
                                                        title="Demande en cours de traitement par <?php echo $deposit->user_on_work;?>"
                                                    <?php endif;?>
                                                    > 
                                                    <?php echo fontawesome('cogs');?> 
                                                </div>
                                            <?php endif;?>
                                            <a role="button" 
                                                class="btn btn-sm btn-link link-dark ms-1" 
                                                onclick="set_worker('on', <?php echo $deposit->id_deposit;?>);"
                                                href="<?php echo base_url("demande/web/deposit/$deposit->id_deposit");?>"
                                                > 
                                                <?php echo fontawesome('info-circle');?> 
                                            </a>
                                            <!-- <button type="button" 
                                                class="btn btn-sm btn-link link-dark ms-1" 
                                                onclick="
                                                    set_worker('on', <?php echo $deposit->id_deposit;?>);
                                                    deposit_info_modal(this, <?php echo $deposit->id_deposit;?>)
                                                "
                                                > 
                                                <?php echo fontawesome('info-circle');?> 
                                            </button> -->
                                            <button type="button" 
                                                class="btn btn-sm btn-link link-danger ms-1" 
                                                onclick="
                                                    set_worker('on', <?php echo $deposit->id_deposit;?>);
                                                    deposit_delete_modal(this, <?php echo $deposit->id_deposit;?>);
                                                "
                                                > 
                                                <?php echo fontawesome('trash-alt');?> 
                                            </button>
                                        </div>
                                    </td>
                                <?php break;?>
                                <?php case 'comment':?>
                                    <td style="max-width:300px;max-height:100px;overflow:hidden;">
                                        <?php echo preg_replace('/^(\s*<br>\*)+/', '', $deposit->$key);?>
                                    </td>
                                <?php break;?>
                                <?php case 'contact_lastname':?>
                                    <td> 
                                        <?php echo fullname($deposit->contact_name, mb_strtoupper($deposit->contact_lastname));?>
                                    </td>
                                <?php break;?>
                                <?php case 'created_at':?>
                                    <td>
                                        <?php echo convert_date_en_to_fr_with_h($deposit->$key);?>
                                    </td>
                                <?php break;?>
                                <?php case 'urls_file':?>
                                    <td>
                                        <?php if(!empty($deposit->urls_file)):?>
                                            <?php foreach($deposit->urls_file as $url):?>
                                                <a href="<?php echo $url;?>" class="btn btn-sm" target="_blank"> <?php echo fontawesome('file');?> </a>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </td>
                                <?php break;?>
                                <?php default :?>
                                    <td>
                                        <?php echo $deposit->$key;?>
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