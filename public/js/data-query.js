

<script>
    // --------------------------
    // Data query
    // --------------------------

    //import SlimSelect from 'slim-select';
    /* 
    * Author: Karol Butcher
    * v1 2021
    */

    // Jquery for select fields
    function setFieldsChoiceQueries(element)
    {
        var names_index = [];
        element.find(".col_name_index").each(function(index){
            names_index.push($(this).attr("name_index"));
        });
        element.find(".fields_order").val(names_index.join(","));
    }

    function setNumElementQueries()
    {
        var container=$(".requete_table_principal");
        var num=0;
        $("tr",container).each(function(){
            num=num+1;
            var tr=$(this);
            $(".ou_et",tr).attr("name","ou_et_##"+num);
            $(".par_ouvert",tr).attr("name","par_ouvert_##"+num);
            $(".r_liste_entity",tr).attr("name","entity_##"+num);
            $(".r_liste_champ",tr).attr("name","champ_##"+num);
            $(".operateur",tr).attr("name","operateur_##"+num);
            if($(".choice",tr).attr("type")=="select")
            {
                $(".choice",tr).attr("name","##"+num+"##_value[]");
            }
            else
            {
                $(".choice",tr).attr("name","##"+num+"##_value");
            }
            $(".par_ferme",tr).attr("name","par_ferme_##"+num);
            $(".tr_number",tr).html(num);
            
        });
        $("#number_line").val(num);
    }

    jQuery(document).ready(function()
    {
        //jquery for condition

        //event of change of entity

        $(document).off("change",".r_liste_entity").on("change",".r_liste_entity",function()
        {
            var entity=$(this).val();
            var tr=$(this).closest("tr");
            var val=$(this).val();
            if(val!=="0")
            {
                $(".c_liste_select_champ",tr).html("<div style='text-align:center !important'><i class='fa fa-spin fa-spinner'></i></div>");
                var url=$(this).attr("url")+"/"+entity;
                jQuery.ajax
                ({  
                    type:'POST',
                    url: url,
                    cache: false,
                    //dataType:'json',

                    success: function(html)
                    { 
                        $(".c_liste_select_champ",tr).html(html);
                        $(".c_liste_operateur",tr).hide();
                        $(".c_liste_input",tr).html("");
                        // $(".select_rs_chosen",tr).chosen({
                        //     disable_search_threshold: 10,
                        //     search_contains: true,
                        //     no_results_text: "Pas de résultats",
                        //     width: "200px"
                        // });
                        // new SlimSelect({
                        //     select: '.select_rs_chosen',
                        //     })
                        // setNumElementQueries();
                    }
                });
            }
            else
            {
                $(".c_liste_select_champ",tr).html("");
                $(".c_liste_operateur",tr).remove();
                $(".c_liste_input",tr).html("");
            }
        });
        
        $(document).off("change",".r_liste_champ").on("change",".r_liste_champ",function()
        {
            var champ=$(this).val();
            var tr=$(this).closest("tr");
            var val=$(this).val();
            var td=$(this).closest("td");

            if(val!=="0")
            {
                $(".c_liste_input",tr).html("<div style='text-align:center !important'><i class='fa fa-spin fa-spinner'></i></div>");
                $(".c_liste_operateur_champ",tr).html("<div style='text-align:center !important'><i class='fa fa-spin fa-spinner'></i></div>");

                var url=$(this).attr("url")+"/"+champ;

                jQuery.ajax
                ({  
                    type:'POST',
                    url: url,
                    cache: false,
                    dataType:'json',

                    success: function(html)
                    { 
                        $(".c_liste_input",tr).html(html.input);
                        $(".c_liste_operateur_champ",tr).html(html.operateur).show();
                        $(".select_rs_chosenff",tr).chosen({
                            disable_search_threshold: 10,
                            search_contains: true,
                            no_results_text: "Pas de résultats",
                            width: "200px"
                        });

                        // new SlimSelect({
                        //     select: '.select_rs_chosen',
                        //     })

                        $('.datepicker',tr).flatpickr({
                            altInput: true,
                            
                            dateFormat: 'd/m/Y',
                            locale: 'fr'
                        });
                        setNumElementQueries()
                    }
                });
            }
            else
            {
                $(".c_liste_operateur",tr).remove();
                $(".c_liste_input",tr).html("");
            }
        });

        $(document).off("click",".ajout_externe").on("click",".ajout_externe",function()
        {
            var tr=$(this).closest("tr");
            var table=$(this).closest("tbody");
            var clone=$(this).closest("tr").clone();
            clone.find("tr").removeClass("trr").addClass("trw");
            $(".delete_externe",clone).show();
            $(".cet_cou",clone).show();
            $(".c_liste_select_champ",clone).html("");
            $(".c_liste_operateur",clone).hide();
            $(".r_liste_entity",clone).val(0);
            $(".par_ouvert",clone).val(0);
            $(".par_ferme",clone).val(0);
            $(".c_liste_input",clone).html("");
            
            tr.after(clone);
            setNumElementQueries();
            return false;
        });

        $(document).off("click",".delete_externe").on("click",".delete_externe",function(){
            var tr=$(this).closest("tr");
            tr.remove();
            setNumElementQueries();
            return false;
        });

        //initialization of sortable
        $( ".fields-sortable-queries" ).sortable({
            disabled: true,
            update: function(event,ui){
                var element=$(this);
                setFieldsChoiceQueries(element);
            }
        });//sortable for fields inside a card

        //sortable only if the cursor touch the icon of hand after a input field
        $(document).on("mouseover",".move_sortable_queries",function(){ 
            $( ".fields-sortable-queries" ).sortable( "option", "disabled", false );
        });

        $(document).on("mouseout",".move_sortable_queries",function(){ 
            $( ".fields-sortable-queries" ).sortable( "option", "disabled", true );
        });

        $(document).on("click",".c_field_queries",function()
        { 
            var fields=$(this);
            var statut=fields.attr("statut");
            var name_index=fields.attr("name_index");
            var containerSelect=$(".fields-sortable-queries");
            var fieldsClone=fields.clone();

            if(statut=="no_select")
            {
                containerSelect.append(fieldsClone);
                var containerField=$(".c_field_queries_"+name_index,containerSelect);
                $(".c_field_queries_"+name_index).attr("statut","select");
                $(".c_field_queries_"+name_index).find(".plus_field").hide();
                
                $(".input_order_by",containerSelect).attr("name","order_by[]");
                $(".input_group_by",containerSelect).attr("name","group_by[]");

                fields.find(".check_queries").show();
                fields.css("opacity",0.2);
                containerField.removeClass("col-3");
                containerField.find(".minus_field").show().css("cursor","pointer");
                containerField.css("cursor","default");
                containerField.find(".move_sortable_queries").show().css("cursor","grab");
                containerField.find(".input_fields_select").attr("name","fields_select[]");
                containerField.find(".is_group_order_by").show();
                var is_empty=true;
                $(".c_field_queries",containerSelect).each(function() {
                    is_empty=false;
                });
                
                if(is_empty==true)
                {
                    $(".mention_no_fields").show();
                    $(".zone-button-group-by").hide();
            }
                else
                {
                    $(".mention_no_fields").hide();
                    $(".zone-button-group-by").show();
                }

                $(".c_field_queries",containerSelect).removeClass("c_field_queries");
            }
            else
            {
                var containerField=$(".c_field_queries_"+name_index,containerSelect);
                containerField.remove();
                $(".c_field_queries_"+name_index).attr("statut","no_select");
                $(".c_field_queries_"+name_index).find(".plus_field").show();
                $(".c_field_queries_"+name_index).find(".check_queries").hide();
                $(".c_field_queries_"+name_index).css("opacity",1);

                var is_empty=true;
                $(".cf",containerSelect).each(function() {
                    is_empty=false;
                });
                
                if(is_empty==true)
                {
                    $(".mention_no_fields").show();
                    $(".zone-button-group-by").hide();
                }
                else
                {
                    $(".mention_no_fields").hide();
                    $(".zone-button-group-by").show();
                }
            }
        });

        $(document).on("click",".c_field_queries_minus",function()
        { 
            var fields=$(this);
            var name_index=fields.attr("name_index");
            var containerSelect=$(".fields-sortable-queries");
            var fieldsClone=fields.clone();
 
            fields.closest(".cf").remove();
            $(".c_field_queries_"+name_index).attr("statut","no_select");
            $(".c_field_queries_"+name_index).find(".plus_field").show();
            $(".c_field_queries_"+name_index).find(".check_queries").hide();
            $(".input_checkbox"+name_index).prop("checked",false);
        
            $(".c_field_queries_"+name_index).css("opacity",1);

            var is_empty=true;
            $(".cf",containerSelect).each(function() {
                is_empty=false;
            });
            
            if(is_empty==true)
            {
                $(".mention_no_fields").show();
                $(".zone-button-group-by").hide();
            }
            else
            {
                $(".mention_no_fields").hide();
                $(".zone-button-group-by").show();
            }
        });

//fonction doit êtte ecrit après la déclaration de l'événement  $(document).on("click",".c_field_queries",function()
    <?php if(isset($fields_selected)&&!empty($fields_selected)):?>
   
            
            <?php foreach($fields_selected as $field):?>
                $(".c_field_queries_<?=$field?>").trigger("click");
            <?php endforeach;?>     
        
    <?php endif;?>
    
    });

</script>  
