<?php if(empty($nb_enquetes)):?>
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
                <?php foreach($enquetes as $enquete):?>
                    <tr>
                        <?php foreach($columns as $key=>$value):?>
                            <?php switch($key): 
                                case 'action':?>
                                    <td>
                                        <button type="button"
                                            class="btn btn-sm"
                                            onclick="enquete_modal(this, <?php echo $enquete->id_enquete;?>)"
                                            >
                                            <?php echo fontawesome('eye');?>
                                        </button>
                                        <?php //if(empty($enquete->ids_question)):?>
                                            <!-- <button type="button" 
                                                class="btn btn-sm btn-link link-danger p-0" 
                                                onclick="enquete_delete_modal(this, <?php //echo $enquete->id_enquete;?>);"
                                                > 
                                                <?php //echo fontawesome('trash-alt');?> 
                                            </button> -->
                                        <?php //endif;?>
                                    </td>
                                <?php break;?>
                                <?php case 'label_fr' :?>
                                    <td>
                                        <?php echo $enquete->path_fr;?>
                                    </td>
                                <?php break;?>
                                <?php default :?>
                                    <td>
                                        <?php echo $enquete->$key;?>
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