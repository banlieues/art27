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
                            <?php if(preg_match('/^Q\d+$/', $value[0])):?>
                                <?php foreach($questions as $question):?>
                                    <?php if($key==$question->name_question):?>
                                        <?php if(isset($answer->$key)):?>
                                            <td data-column="<?php echo $question->name_question;?>">
                                                <div role="button" title="<?php echo $question->question_fr;?>">
                                                    <?php echo $answer->{$key . '_label'};?>
                                                </div>
                                            </td>
                                        <?php else:?>
                                            <td></td>
                                        <?php endif;?>
                                    <?php endif;?>
                                <?php endforeach;?>
                            <?php elseif($key=='suggestions') :?>
                                <td class="text-center" data-column="<?php echo $key;?>">
                                    <?php if(!empty($answer->$key)):?>
                                        <a role="button" class="modalView btn btn-sm btn-link link-dark"
                                            href="<?php echo base_url("enquete/answer/view/$answer->id_answer/$key");?>"
                                            title="Suggestions"
                                            data-view-title="Suggestions"
                                            >
                                            <?php echo fontawesome('clipboard');?>
                                        </a>
                                    <?php endif;?>
                                </td>
                            <?php elseif($key=='date_reponse') :?>
                                <td data-column="<?php echo $key;?>">
                                    <?php echo convert_date_en_to_fr_with_h($answer->$key, '<br> à');?>
                                </td>
                            <?php elseif($key=='id_demande'):?>
                                <td class="text-center" data-column="<?php echo $key;?>">
                                    <a class="btn btn-sm btn-<?php echo $themes->demande->color;?>"
                                        target="_blank"
                                        href="<?php echo base_url("demande/fiche/$answer->id_demande");?>"
                                        >
                                        <?php echo $answer->$key;?>
                                    </a>
                                </td>
                            <?php elseif($key=='id_answer'):?>
                                <td class="text-center" data-column="<?php echo $key;?>">
                                    <button type="button" class="btn btn-sm btn-<?php echo $themes->enquete->color;?>"
                                        onclick="js_answer_details(this, <?php echo $answer->id_answer;?>);"
                                        title="Voir les réponses de cette enquête"
                                        >
                                        <?php echo $answer->$key;?>
                                    </button>
                                </td>
                            <?php else :?>
                                <td data-column="<?php echo $key;?>">
                                    <?php echo $answer->$key;?>
                                </td>  
                            <?php endif;?>  
                        <?php endforeach;?>
                    </tr>
                <?php endforeach;?>
            </tbody>   
        </table>
    </div>      
<?php endif;?>