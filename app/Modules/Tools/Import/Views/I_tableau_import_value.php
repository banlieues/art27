<div class="text-danger"><small class="bloque_etape2">Il existe des valeurs qui ne sont pas dans la liste liée à cet index</small></div>
<form class="traducteur_list">
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th>CSV</th>
                <th>CRM</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($diff as $d):?>
            <tr>
                <td class="col-6">
                    <?php echo $d?>
                    <input value="<?php echo $d?>" type="hidden" name="list[csv][]">
                </td>
                <td class="col-6">
                    <select name="list[crm][]">
                        <option value="0">Corriger par</option>
                        <option value="notraduction">Effacer la valeur dans le CSV</option>
                        <?php foreach($list_crm as $list):?>
                            <option value="<?=$list->label?>"><?=$list->label?></option>
                        <?php endforeach;?> 
                    </select>
                </td> 
            </tr>
            <?php endforeach;?>
            <input type="hidden" name="table_csv" value="<?=$table_csv?>">
            <input type="hidden" name="index_csv" value="<?=$index_csv?>">
        </tbody>
    </table>
    <div class="text-center">
        <button type="submit" class="btn btn-dark btn-sm">Corriger les données du CSV</button>
    </div>
</form>