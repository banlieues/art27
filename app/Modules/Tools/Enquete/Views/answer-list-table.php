<?php if(empty($nb_answers)):?>
    <div class="text-center m-5">
        <h3>Pas d'enquête trouvée avec ces critères.</h3>
    </div>  
<?php else:?>
    <div id="answers-list" class="table-responsive table-fullview"> 
        <table class="table table-bordered table-striped table-hover my-0 table-sm w-100">
            <thead class="table-light sticky-top">
                <tr>
                    <?php echo $getTh;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($answers as $answer):?>
                    <tr>
                        <?php foreach($columns as $key=>$value):?>
                            <?php switch($key): 
                                case 'date_envoi':?>
                                    <td>
                                        <?php echo convert_date_en_to_fr_with_h($answer->$key);?>
                                    </td>
                                <?php break;?>
                                <?php case 'demandeur_lastname':?>
                                    <td>
                                        <a role="button" class="btn btn-sm btn-<?php echo $themes->demandeur->color;?> w-100"
                                            target="_blank" 
                                            href="<?php echo base_url("contact/fiche/$answer->id_contact");?>"
                                            title="Aller à la fiche du demandeur"
                                            >
                                            <?php echo fullname($answer->demandeur_name, $answer->demandeur_lastname);?>
                                        </a>
                                    </td>
                                <?php break;?>
                                <?php case 'path_fr':?>
                                    <td>
                                        <a role="button" class="btn btn-sm btn-<?php echo $themes->demande->color;?> w-100"
                                            target="_blank" 
                                            href="<?php echo base_url("demande/fiche/$answer->id_demande");?>"
                                            title="Aller à la fiche de la demande"
                                            >
                                            <?php echo $answer->path_fr;?>
                                        </a>
                                    </td>
                                <?php break;?>
                                <?php case 'nom':?>
                                    <td>
                                        <?php echo fullname($answer->prenom, $answer->nom);?>
                                    </td>
                                <?php break;?>
                                <?php case 'statut_answer':?>
                                    <td class="text-center">
                                        <?php if($answer->id_statut_answer==3):?>
                                            <button type="button" class="btn btn-sm btn-<?php echo $themes->enquete->color;?>"
                                                onclick="js_answer_details(this, <?php echo $answer->id_answer;?>);"
                                                title="Voir les réponses de cette enquête"
                                                >
                                                <?php echo $answer->$key;?>
                                            </button>
                                        <?php else:?>
                                            <?php echo $answer->$key;?>
                                        <?php endif;?>
                                    </td>
                                <?php break;?>
                                <?php default :?>
                                    <td>
                                        <?php if(is_object($answer->$key)):?>
                                            <?php echo $answer->$key->label;?>
                                        <?php else:?>
                                            <?php echo $answer->$key;?>
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