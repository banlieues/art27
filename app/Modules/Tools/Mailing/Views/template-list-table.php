<?php if(empty($nb_templates)):?>
    <div class="text-center m-5">
        <h3>Pas de modèle d'email trouvé avec ces critères.</h3>
    </div>  
<?php else:?>
    <div id="templates-list" class="table-responsive table-fullview"> 
        <table class="table table-bordered table-striped table-hover my-0 table-sm w-100">
            <thead class="table-light sticky-top">
                <tr>
                    <?php echo $getTh;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($templates as $template):?>
                    <tr>
                        <?php foreach($columns as $key=>$value):?>
                            <?php switch($key): 
                                case 'action':?>
                                    <td class="text-center">
                                        <a class="link-dark text-decoration-none"
                                            href="<?php echo base_url("mailing/template/view/$template->id_template?$get");?>"
                                            title="Modifier le modèle d'email"
                                            onclick="waiting_start(this);"
                                            >
                                            <?php echo fontawesome('edit');?>
                                        </a>
                                    </td>
                                <?php break;?>
                                <?php case 'author_lastname':?>
                                    <td> 
                                        <?php echo fullname($template->author_name, mb_strtoupper($template->author_lastname));?>
                                    </td>
                                <?php break;?>
                                <?php case 'is_activated' :?>
                                    <td>
                                        <?php echo $dataView->getValueReadBoolean($template->$key);?>
                                    </td>
                                <?php break;?>
                                <?php case 'label':?>
                                <?php case 'ref':?>
                                    <td>
                                        <?php echo $template->$key;?>
                                    </td>
                                <?php break;?>
                                <?php case 'updated_at':?>
                                    <td>
                                        <?php echo convert_date_en_to_fr_with_h($template->$key);?>
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