/* 
 * Author: Karol Butcher
 * v1 2021
 */

function setFieldsChoice(element)
{
    var names_index = [];
    element.find(".col_name_index").each(function(index){
            names_index.push($(this).attr("name_index"));
           
    });
    element.find(".fields_order").val(names_index.join(","));
}


jQuery(document).ready(function()
{
    //initialization of sortable
    $("#column1.ui-sortable, #column2.ui-sortable").sortable(
        {
            connectWith: ".column-sortable",
            disabled: true,
            update: function(event,ui){
                $(".fields_order").each(function(index){
                    var name=$(this).attr('name');
                    var numcolumn=$(this).closest(".column-sortable").attr("num_column");
                    var newnumcolumn="colIndex"+numcolumn+"@order@";
                    var name=name.replace("colIndex1@order@",newnumcolumn);
                    var name=name.replace("colIndex2@order@",newnumcolumn);
                    $(this).attr('name',name);
                    
                });
                
            }

        }
    ).disableSelection();//sortable for card

    $( ".fields-sortable" ).sortable({
        disabled: true,
        update: function(event,ui){
            var element=$(this);
            setFieldsChoice(element);
            
        }
      });//sortable for fields inside a card

    //sortable only if the cursor touch the icon of hand after a input field
    $(document).on("mouseover",".move-sortable",function(){ 
        $( ".fields-sortable" ).sortable( "option", "disabled", false );

    });

    $(document).on("mouseout",".move-sortable",function(){ 
        $( ".fields-sortable" ).sortable( "option", "disabled", true );

    });

     //sortable only if the cursor touch the icon of hand from card-header
    $(document).on("mouseover",".move-sortable-column",function(){ 
        $("#column1.ui-sortable, #column2.ui-sortable").sortable( "option", "disabled", false );

    });

    $(document).on("mouseout",".move-sortable-column",function(){ 
        $("#column1.ui-sortable, #column2.ui-sortable").sortable( "option", "disabled", true );

    });

    

    $(document).on("click",".addField",function(){ 
       
        var element=$(this);
        var index=element.attr('value');
        var container=element.closest(".card_sortable");
       
        $(".addField"+index).fadeOut();
        if($(".nocol_name_index[name_index="+index+"]",container).length)
        {
            $(".nocol_name_index[name_index="+index+"]",container).closest(".container_one_field").fadeIn();
            $(".nocol_name_index[name_index="+index+"]",container).removeClass("nocol_name_index").addClass("col_name_index");
            var element_sortable=$(".col_name_index[name_index="+index+"]",container).closest(".fields-sortable");
            setFieldsChoice(element_sortable);
        }
        else
        {
            var clone=$(".clone",container).clone().removeClass("clone").show();
            var labelField=element.find(".labelField").text();
            $(".labelField",clone).text(labelField);
            $(".col_name_index",clone).attr("name_index",index);
            $(".deleteField",clone).attr("index",index);
            
            $(".inputName",clone).val(index);
            $(".fields-sortable",container).append(clone);
            // var element_sortable=$(".col_name_index[name_index="+index+"]",container).closest(".fields-sortable");
            // setFieldsChoice(element_sortable);

            var indexAdd=container.find(".fields_order").val();
            container.find(".fields_order").val(indexAdd+","+index);

        }
       

    });

    $(document).on("click",".addHr",function(){ 
       
        var element=$(this);
        var index=element.attr('index');
        var container=element.closest(".card_sortable");
        var clone=$(".clone_hr",container).clone().removeClass("clone_hr").show();
        $(".fields-sortable",container).append(clone);
        var indexAdd=container.find(".fields_order").val();
        container.find(".fields_order").val(indexAdd+",@#<hr>");
       

    });

    $(document).on("click",".deleteField",function(){ 
       
        var element=$(this);
        var index=element.attr("index");
       
       
        element.closest(".container_one_field").fadeOut();
        element.closest(".container_one_field").find(".col_name_index").removeClass("col_name_index").addClass("nocol_name_index");
        
       if(index!=="@#<hr>")
       {
            $(".addField"+index).fadeIn();
       }
        
        var element_sortable=element.closest(".fields-sortable");
        setFieldsChoice(element_sortable);


    });
    
   
    $(document).on("click",".link_add_fields", function(){
        var link=$(this);
        var state=link.attr("state");
        var container=link.closest(".add_fields");
        
        if(state=="close")
        {
            //$(".possible_fields").hide();//close all possible fields
            $(".link_add_fields").attr("state","close")
            $(".possible_fields",container).slideDown(); //open possible fields selected
            link.attr("state","open");
         
            // jQuery.ajax
            // ({	
            //         type:'POST',
            //         url: url,
            //         cache: false,
            //         beforeSend: function() {
            //             // setting a timeout
            //             $(".possible_fields",container).html('<div class="text-center"><i class="fa fa-spin fa-spinner fa-2x"></i></div>');
            //         },
            //         success: function(html)
            //         { 
            //             $(".possible_fields",container).html(html)
            //         }
    
            // }); 
        }
        else
        {
            $(".possible_fields",container).slideUp();
            link.attr("state","close")
        }

    });

  //form gestion des fields 

  function setItemChoiceList()
  {
      var container=$(".sortable-item-list");
      var num=0;
      $("tr",container).each(function(){
          num=num+1;
          var tr=$(this);
          $(".id_item",tr).attr("name","id_item_##"+num);
          $(".label_item",tr).attr("name","label_item_##"+num);
          $(".ref_item",tr).attr("name","ref_item_##"+num);
          $(".is_actif_item",tr).attr("name","is_actif_item_##"+num);
          $(".num_item_list",tr).html(num);
          
          
      });
      $("#number_line").val(num);
  
      
  }

    $( ".sortable-item-list" ).sortable({
        disabled: true,
        update: function(event,ui){
            
            setItemChoiceList();
            
        }
    });//sortable for fields inside a card

    //sortable only if the cursor touch the icon of hand after a input field
    $(document).on("mouseover",".move_sortable_item_list",function(){ 
        $( ".sortable-item-list" ).sortable( "option", "disabled", false );

    });

    $(document).on("mouseout",".move_sortable_item_list",function(){ 
        $( ".sortable-item-lis" ).sortable( "option", "disabled", true );

    });

    
    $(document).on("click",".add_item_in_list",function(){
        var container_list_item=$(this).closest(".container_list_item");
        var clone_tr=$(".clone_tr",container_list_item).clone();
        clone_tr.show().removeClass("clone_tr");
        $(".container_list_tr").append(clone_tr);
        setItemChoiceList();
        return false;
    });

    $(document).on("click",".cancel_item",function(){
        $(this).closest("tr").remove();
        setItemChoiceList();
        return false;
    });

    $(document).on("change","#type_field",function(){
        var val=$(this).val();
        switch(val) {
            case 'select':
            case 'radio':
            case 'check':
                $(".c_list").show();
                break;

             default:
                $(".c_list").hide(); 
           
          } 
    })
  
});
