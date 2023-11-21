<div class="text-danger"><small>Il existe des valeurs qui ne sont pas dans la liste liée à cet index</small></div>
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
            </td>
            <td class="col-6">
                <select name="index_crm[]">
                    <option value="0">Corriger par</option>
                    <?php foreach($list_crm as $list):?>
                        <option value="<?=$list->label?>"><?=$list->label?></option>
                    <?php endforeach;?> 
                </select>
             </td> 
        </tr>
        <?php endforeach;?>
       
    </tbody>
</table>
<div class="text-center">
    <button class="btn btn-dark btn-sm">Corriger les données du CSV</button>
</div>