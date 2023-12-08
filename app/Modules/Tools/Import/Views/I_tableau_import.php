<?php $import=\Config\Services::import();?>
<?php $validation = \Config\Services::validation(); ?>
<?php $request = \Config\Services::request(); ?>

<?php $this->extend('Layout\index'); ?>

<?php $this->section("body"); ?>
<?php if($etape==1):?>
    <h3><i class="<?=icon("import")?>"></i> <?=$titleView?> ETAPE 1 : Précisez les index </h3>
<?php else:?>
    <h3><i class="<?=icon("import")?>"></i> <?=$titleView?> ETAPE 2 : Importation des données </h3>
    <div class="alert alert-success"><i>Sélectionner les lignes à importer et cocher les doublons qui pourraient correspondre à certaines données!</i></div>

<?php endif;?>


<?php if($etape==2):?>
<form id="form_insert_data" method="get" action="<?=base_url("import/insert")?>" >
<?php endif;?>

<div class="text-center">
<?php if($etape==1):?>
    <div style="display:none" id="erreur_bloquant" class="alert alert-danger">Pour passer à l'étape suivante de l'importation, veuillez corriger les problèmes détectés avec les index!</div>
    <a style="display:none" id="bt_etape_2" href="<?=base_url("import/table_importation/$name_temp/2")?>" class="btn btn-dark btn-sm m-3"> Vers Etape 2 : Traitement des données ! </a>
<?php else:?>
    <a href="<?=base_url("import/table_importation/$name_temp/1")?>" class="btn btn-dark btn-sm m-3"> Revenir étape 1 : Correspondance des champs </a>

    <button  type="submit" class="btn btn-dark btn-sm m-3"> Importer les données dans le CRM</a>
<?php endif;?>

</div>



<div class="table-responsive">
    <table class="table table-bordered table-striped">

  
                <tr>
                <?php if($etape==2):?>
                  
                <td><input checked id="option_check_all" type="checkbox"></td>
                       <?php endif;?>
                    <?php foreach($csView as $label=>$values):?>
                        <?php if($label!=$primary_name_temp&&$label!="is_imported"):?>
                               
                            <th>
                            <?php if($import->ne_pas_importer($label)=="ban666luci"):?>
                                Ne pas importer
                            <?php else:?>
                                <?=$label?>
                            <?php endif;?>
                            </th>
                        <?php endif;?>
                    <?php endforeach;?>
                </tr>
                <?php if($etape==1):?>
                <tr>
             
                    <?php foreach($csView as $label=>$values):?>
                        <?php $value_index_encours=0?>
                        <?php if($label!=$primary_name_temp&&$label!="is_imported"):?>
                            <td class="col-3 value_select_container">
                                <form class="form_value_select">
                                    <select id="" name="index_crm" class="dselectdddd load_value_select">
                                            <option value="0">Sélectionner un index</option>
                                            <option <?php if($import->ne_pas_importer($label)=="ban666luci"): $value_index_encours=$label?>selected<?php endif;?> value="ban666luci">Ne pas importer</option>
                                        <?php foreach($indexes as $index=>$label_index_crm):?>
                                            <option <?php if($dataModel->search_index($label)==$index):  $value_index_encours=$index; echo "selected"; endif;?> value="<?=$index?>"><?=$label_index_crm?></option>

                                        <?php endforeach;?>
                                    </select>  
                                    <script>
                                        $(document).ready( function () {
  
                                        $(".load_value_select").chosen({
                                                disable_search_threshold: 5,
                                                search_contains: true,
                                                no_results_text: "Pas de résultats pour ",
                                                placeholder_text_multiple: "Vous pouvez choisir plusieurs éléments",
                                                width: "100%"
                                            }); 
                                        
                                        } );
                                    </script>
                                    <input type="hidden" name="index_csv" value="<?=$label?>">
                                    <input type="hidden" name="table_csv" value="<?=$name_temp?>">
                                    <input class="new_index_csv" type="hidden" name="new_index_csv" value="">

                                </form>
                                <div class="chargeDowload">
                                        <?=$import->value_select($value_index_encours,$label,$name_temp,false)?>
                                </div>  
                            </td>
                        <?php endif;?>
                    <?php endforeach;?>
                </tr>
                <?php endif?>
     

        <tbody>    
            <?php foreach($csv as $label=>$values):?>
               
            <tr>
                    <?php foreach($values as $index=>$v):?>

                        <?php if($etape==2&&$index==$primary_name_temp):?>

                            <td>
        
                                <input class="do_check" checked type="checkbox" value="<?=$v?>" name="id_primary[]">
                            </td>
                        <?php endif?>  
                        <?php if($index!=$primary_name_temp&&$index!="is_imported"):?>
                            <td><?=$v?></td>
                        <?php endif;?>
                    <?php endforeach;?>    
            </tr>
            <?php endforeach;?>   
        </tbody> 

    </table>
    <input type="hidden" name="name_temp" value="<?=$name_temp?>">
</div>
<?php if($etape==2):?>
</form>
<?php endif;?>
<script>

        
   function bloque_2()
    {
        var visible=false;

        $(".bloque_etape2").each(function()
        {
            if($(this).is(":visible"))
            {
                visible=true;
            }
        });

        if(visible==true)
        {
            $("#erreur_bloquant").show();
            $("#bt_etape_2").hide();
        }
        else
        {
            $("#erreur_bloquant").hide();
            $("#bt_etape_2").show();
        }
        
    }

     function   load_value_select(el)
     {
        var value_select_container=el.closest(".value_select_container");

        var val=el.val();
        //alert(val);
        $(".new_index_csv",value_select_container).val(val);

       
            value_select_container.find(".chargeDowload").html('<div class="text-center"><i class="fa fa-spin fa-spinner"></i>');
  
            var index_crm=val;
            var index_csv=$('input[name="index_csv"]',value_select_container).val();
            var table_csv=$('input[name="table_csv"]',value_select_container).val();

            var url="<?=base_url()?>/import/value_select/"+index_crm+"/"+index_csv+"/"+table_csv;

            //alert(url);
            var dataString=$(".form_value_select",value_select_container).serialize();
            //alert(dataString);

            jQuery.ajax
                ({  
                    type:'post',
                    url: url,
                    cache: false,
                    data: dataString,
                    dataType: "json",

                    success: function(data)
                    { 
                     
                        value_select_container.find(".chargeDowload").html(data.message_error);
                       
                        $('input[name="index_csv"]',value_select_container).val(data.index_csv);

                        bloque_2();
                        
                    }
                });
       
    } 
    


    $(document).ready(function() {

     /*   $(".load_value_select").each(function(){
            //alert();
            load_value_select($(this));
        })*/
        
       bloque_2();
        $(document).on("change",".load_value_select", function()
        {
            //alert();
           
                load_value_select($(this));

        });

        $(document).on("change","#choix_action", function()
        {
            $("#erreur_choix_action").hide();
            var choix_action=$("#choix_action").val();
           
           if(choix_action=="sans_choix")
           {
               $("#erreur_choix_action").show();
               return false;
           }
           
          bloque_2();

        });


        $(document).on("submit","#form_insert_data", function()
        {
            var choix_action=$("#choix_action").val();
           
            if(choix_action=="sans_choix")
            {
                $("#erreur_choix_action").show();
               bloque_2();
                return false;
            }

            
        })


        $(document).on("change","#option_check_all", function()
        {
           if($(this).is(":checked"))
           {
                $(".do_check").prop("checked",true);
           }
           else
           {
                $(".do_check").prop("checked",false);
           }

           bloque_2();
        })

        
        $(document).on("submit",".traducteur_list",function()
        {
            if(confirm("Etes-vous sûr de corriger les valeurs du CSV? Cette opération est irréversible!"))
            {
                dataString=$(this).serialize();
                var url="<?=base_url()?>/import/traducteur_list";
                var el=$(this).closest(".value_select_container").find(".load_value_select");

                jQuery.ajax
                ({  
                    type:'post',
                    url: url,
                    cache: false,
                    data: dataString,
               

                    success: function(data)
                    { 
                     
                        location.reload();
                        
                    }
                });

                return false;
            }
           
        }
        
        );


       
    });   


</script>

<?php $this->endSection(); ?>




