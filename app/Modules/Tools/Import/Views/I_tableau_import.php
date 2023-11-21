<?php $this->extend('Layout\index'); ?>

<?php $this->section("body"); ?>
<h3><i class="<?=icon("import")?>"></i> <?=$titleView?>: Aperçu des 5 premières lignes de votre fichier</h3>
<div class="alert alert-success"><i>Veuillez préciser à quel index de champ correspond chaque titre!</i></div>

<form>
<div class="table-responsive">
    <table class="table table-bordered table-striped">
    <?php $i=1?>
        <?php foreach($csView as $label=>$values):?>
           
            <tr>
                <th>
                    <?=$label?>
                </th>
                <td class="col-3 value_select_container">
                    <form class="form_value_select">
                        <select name="index_crm" class="dselectdddd load_value_select">
                                <option value="0">Sélectionner un index</option>
                            <?php foreach($indexes as $index=>$label_index_crm):?>
                                <option <?php if($dataModel->search_index($label)==$index): echo "selected"; endif;?> value="<?=$index?>"><?=$label_index_crm?></option>

                            <?php endforeach;?>
                        </select>  
                        <input type="hidden" name="index_csv" value="<?=$label?>">
                        <input type="hidden" name="table_csv" value="<?=$name_temp?>">
                    </form>
                    <div class="chargeDowload">

                    </div>  
                </td>
               
                <?php foreach($values as $v):?>
                   <td><?=$v?></td>
                <?php endforeach;?>    
            </tr>
            <?php $i=$i+1?>
        <?php endforeach;?>    

    </table>
    <input type="hidden" name="name_temp" value="<?=$name_temp?>">
</div>
<div class="text-center">
    <button type="submit" class="btn btn-dark btn-sm">Lancer l'importation</button>
</div>
</form>

<?php $this->endSection(); ?>

<?php $this->section("script_foot_injected"); ?>
<script>

     function   load_value_select(el)
     {
        var value_select_container=el.closest(".value_select_container");

        value_select_container.find(".chargeDowload").html('<div class="text-center"><i class="fa fa-spin fa-spinner"></i>');

        var url="<?=base_url()?>/import/value_select";

        var dataString=$(".form_value_select",value_select_container).serialize();
        //alert(dataString);

        jQuery.ajax
            ({  
              type:'POST',
              url: url,
              cache: false,
              data: dataString,

              success: function(html)
              { 
                
                value_select_container.find(".chargeDowload").html(html);
                  
                  
              }
            });
     }             

    $(document).ready(function() {

        $(".load_value_select").each(function(){
            //alert();
            load_value_select($(this));
        })
        

        $(document).on("change",".load_value_select", function()
        {
            //alert();
            load_value_select($(this));
        })

       
    });   


</script>


<?php $this->endSection(); ?>