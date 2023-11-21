<?php foreach($columns as $key=>$value):?>
    <?php switch($key): 
        case 'action':?>
            <td>
                <button type="button"
                    class="btn btn-sm form_read"
                    onclick="row_edit(this);"
                    title="Editer la ligne de traduction"
                    >
                    <?php echo fontawesome('edit');?>
                </button>
                <button type="button"
                    class="btn btn-sm form_update"
                    form="rowUpdateForm_<?php echo $row->id_transl;?>"
                    onclick="row_save(this);"
                    style="display:none;"
                    title="Enregistrer les modifications"
                    >
                    <?php echo fontawesome('save');?>
                </button>
                <button type="button"
                    class="btn btn-sm form_update"
                    onclick="row_undo(this);"
                    style="display:none;"
                    title="Annuler les modifications"
                    >
                    <?php echo fontawesome('undo');?>
                </button>
                <button class="form_update btn btn-sm btn-link link-danger"
                    title="Supprimer la ligne de traduction"
                    style="display: none;"
                    onclick="row_delete_modal(this);"
                    >
                    <?php echo fontawesome('trash-alt');?>
                </button>
            </td>
        <?php break;?>
        <?php case 'label_fr' :?>
            <td style="width: 40%">
                <?php echo $row->$key;?>
            </td>
        <?php break;?>
        <?php case 'label_nl' :?>
            <td style="width: 40%">
                <div class="form_read">
                    <?php echo $row->$key;?>
                </div>
                <div class="form_update" style="display: none;">
                    <textarea
                        class="form-control"
                        name="label_nl"
                        form="rowUpdateForm_<?php echo $row->id_transl;?>"
                    ><?php echo $row->$key;?></textarea>
                </div>
            </td>
        <?php break;?>
        <?php case 'updated_at' :?>
            <td>
                <?php echo convert_date_en_to_fr_with_h($row->$key, '<br>');?>
            </td>
        <?php break;?>
        <?php case 'updated_by_nom' :?>
            <td>
                <?php echo fullname($row->updated_by_prenom, $row->updated_by_nom);?>
            </td>
        <?php break;?>
        <?php default :?>
            <td>
                <?php echo $row->$key;?>
            </td>
        <?php break;?>
    <?php endswitch;?>  
<?php endforeach;?>
