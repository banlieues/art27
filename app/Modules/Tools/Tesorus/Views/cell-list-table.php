<?php if(empty($nb_cells)):?>
    <div class="text-center m-5">
        <h3>Pas de modèle d'email trouvé avec ces critères.</h3>
    </div>  
<?php else:?>
    <div id="cells-list" class="table-responsive table-fullview"> 
        <table class="table table-bordered table-striped table-hover my-0 table-sm w-100">
            <thead class="table-light sticky-top">
                <tr>
                    <?php echo $getTh;?>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cells as $cell):?>
                    <tr>
                        <?php foreach($columns as $key=>$value):?>
                            <?php switch($key): 
                                case 'action':?>
                                    <td class="text-center">
                                        <a class="link-dark text-decoration-none"
                                            title="Modifier la cellule"
                                            >
                                            <?php echo fontawesome('edit');?>
                                        </a>
                                    </td>
                                <?php break;?>
                                <?php case 'comment':?>
                                    <td class="text-center">
                                        <?php if(!empty($company->$key)):?>
                                            <a role="button" class="modalView btn btn-sm btn-link link-dark"
                                                href="<?php echo base_url("tesorus/cell/$cell->id_cell/modal/$key");?>"
                                                title="Notes"
                                                data-view-title="Notes"
                                                >
                                                <?php echo fontawesome('clipboard');?>
                                            </a>
                                        <?php endif;?>
                                    </td>
                                <?php break;?>
                                <?php case 'paths':?>
                                    <td>
                                        <?php echo implode('<br>', $cell->$key);?>
                                    </td>
                                <?php break;?>
                                <?php default:?>
                                    <td>
                                        <?php echo $cell->$key;?>
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