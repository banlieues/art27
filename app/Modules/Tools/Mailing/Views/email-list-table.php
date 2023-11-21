<?php if(empty($nb_emails)):?>
    <div class="text-center m-5">
        <h3>Pas d'email trouvé avec ces critères.</h3>
    </div>  
<?php else:?>
    <div id="emails-list" class="table-responsive table-fullview"> 
        <table class="table table-bordered table-striped table-hover my-0 table-sm w-100">
            <thead class="table-light sticky-top">
                <tr>
                    <?php echo $getTh;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($emails as $email):?>
                    <tr>
                        <?php foreach($columns as $key=>$value):?>
                            <?php switch($key): 
                                case 'author_lastname':?>
                                    <td> 
                                        <?php echo fullname($email->author_name, mb_strtoupper($email->author_lastname));?>
                                    </td>
                                <?php break;?>
                                <?php case 'datetime':?>
                                    <td>
                                        <?php echo convert_date_en_to_fr_with_h($email->$key);?>
                                    </td>
                                <?php break;?>
                                <?php case 'message':?>
                                    <td style="max-width:300px;max-height:100px;overflow:hidden;">
                                        <?php echo preg_replace('/^(\s*<br>\*)+/', '', $email->$key);?>
                                    </td>
                                <?php break;?>
                                <?php case 'reference':?>
                                <?php case 'subject':?>
                                    <td>
                                        <?php echo $email->$key;?>
                                    </td>
                                <?php break;?>
                                <?php case 'recipient':?>
                                    <td>
                                        <?php if(!empty($email->to_selected)):?>
                                            <?php foreach($email->to_selected as $to_selected):?>
                                                <?php echo fullname($to_selected->name, $to_selected->lastname) . htmlspecialchars('<' . $to_selected->email . '>');?> <br>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                        <?php if(!empty($email->to_text)):?>
                                            <?php foreach($email->to_text as $to_text):?>
                                                <?php echo htmlspecialchars($to_text);?> <br>
                                            <?php endforeach;?>
                                        <?php endif;?>
                                    </td>
                                <?php break;?>
                                <?php case 'sender_mail':?>
                                    <td>
                                        <?php echo $email->$key==PUBLIC_MAIL ? fontawesome('homegrade') : $email->$key;?>
                                    </td>
                                <?php break;?>
                                <?php case 'to_mail':?>
                                    <td>
                                        <?php echo $email->$key==PUBLIC_MAIL ? fontawesome('homegrade') : $email->$key;?>
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