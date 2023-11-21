<?php if(in_array($typeDataView, ['update'])):?>
    <form id="TableRankUpdate" action="<?php echo base_url('liste/table/' . $table . '/rank/update')?>" method="post"></form>
<?php endif;?>

<div class="table-responsive">
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <?php foreach ($fields as $field) :?>
                    <?php if(!in_array($field, [$pk, 'label_original', 'rank', 'date_creation', 'date_modification', 'id_user', 'id_user_creation', 'id_user_modification', 'updated_at', 'updated_by', 'created_at', 'created_by'])):?>
                        <th class="<?php if(in_array($field, ['is_actif'])):?> text-center <?php endif;?>"> 
                            <?php if(in_array($field, ['label', 'label_fr', 'name'])):?> Label FR
                            <?php elseif(in_array($field, ['label_nl'])):?> Label NL
                            <?php elseif(in_array($field, ['comment'])):?> Notes
                            <?php elseif(in_array($field, ['is_actif'])):?> Activé
                            <?php else: echo $field;?>
                            <?php endif;?>
                            <small> [<?php echo $field;?>] </small>
                        </th>
                    <?php endif;?>
                <?php endforeach;?>
                <?php if(in_array($typeDataView, ['update'])):?>
                    <th></th>
                <?php endif;?>
            </tr>
        </thead>
        <tbody 
            <?php if(in_array($typeDataView, ['update'])):?>
                id="sort" action="<?php echo base_url('liste/table/' . $table . '/rank/update')?>"
            <?php endif;?>
            >
            <?php foreach($result as $row):?>  
                <tr
                    <?php if(!empty($has_rank)): ?> pk_value="<?php echo $row->$pk;?>" <?php endif;?>
                    >
                    <?php foreach($fields as $field):?>
                        <?php if(in_array($field, ['is_actif'])):?>
                            <td class="text-center">
                                <?php if(!empty($row->is_actif)):?> <?php echo fontawesome('check');?> <?php endif;?>
                            </td>
                        <?php elseif(!in_array($field, [$pk, 'label_original', 'rank', 'date_creation', 'date_modification', 'id_user', 'id_user_creation', 'id_user_modification', 'updated_at', 'updated_by', 'created_at', 'created_by'])):?>
                            <td> <?php echo $row->$field;?> </td>
                        <?php endif;?>
                    <?php endforeach;?>
                    <?php if(in_array($typeDataView, ['update'])):?>
                        <td>
                            <?php if(!empty($has_rank)): ?>
                                <a class="btn btn-sm">
                                    <?php echo fontawesome('sort');?>
                                </a>
                            <?php endif;?>
                            <a class="btn btn-sm edit ms-4"
                                onclick="row_edit_modal('<?php echo $table;?>', <?php echo $row->$pk;?>);"
                                title="Modifier l'élément de liste"
                                >
                                <?php echo fontawesome('edit');?>
                            </a>
                        </td>
                    <?php endif;?>
                </tr>
            <?php endforeach;?> 
        </tbody>
    </table>
</div>
