<?php if(empty($nb_questions)):?>
    <div class="text-center m-5">
        <h3>Pas de formulaire trouvé avec ces critères.</h3>
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
                <?php foreach($questions as $question):?>
                    <tr>
                        <?php foreach($columns as $key=>$value):?>
                            <?php switch($key): 
                                case 'action':?>
                                    <td class="text-nowrap">
                                        <button type="button"
                                            class="btn btn-sm"
                                            onclick="question_modal(this, <?php echo $question->id_question;?>)"
                                            >
                                            <?php echo fontawesome('eye');?>
                                        </button>
                                        <?php if(empty($question->nb_enquetes)):?> 
                                            <button type="button" class="btn btn-sm btn-link link-danger"
                                                onclick='question_delete_modal(<?php echo $question->id_question;?>, <?php echo $question->num_question;?>, "<?php echo $question->question_fr;?>");'
                                                > 
                                                <?php echo fontawesome('trash-alt');?> 
                                            </button> 
                                        <?php endif;?>
                                    </td>
                                <?php break;?>
                                <?php default :?>
                                    <td>
                                        <?php echo $question->$key;?>
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