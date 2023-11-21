<script>

function charge_contenu_dashboard(container, descriptor)
{
   //alert(descriptor);
    var adresse="<?php echo base_url();?>app/charge_contenu_dashboard/"+descriptor;
    container.html("<div style='text-align:center; margin-top: 100px; margin-bottom: 100px'><i class='fa fa-spin fa-spinner fa-4x'></i></div>");

    jQuery.ajax
    ({	
	   type:'POST',
	    url: adresse,
	    //data: dataString,
	    cache: false,
	   
	    success: function(html)
	    { 
		//alert(html);
		if(html===" ")
		{
		   
		    container.html("<div style='text-align:center; margin-top: 100px; margin-bottom: 100px'>Pas de résultats</div>");
		    $("#menu_fixed_bureau").sticky({topSpacing:120});
  $("#menu_fixed_pot").sticky({topSpacing:120});
   $("#menu_fixed_equipe").sticky({topSpacing:120});
		    
		 }
		 else
		 {
		    container.html(html);
		    $("#menu_fixed_bureau").sticky({topSpacing:120});
  $("#menu_fixed_pot").sticky({topSpacing:120});
   $("#menu_fixed_equipe").sticky({topSpacing:120});
		    
		} 
		//recup_number(container);
	    }

    });
}

function recup_number(container)
{
      //container.each(function() {
	    var el=container;
	    var label=el.find(".label-info");
	    if(label.is(":visible")){
	    var text=el.find(".label-info").text();
	   el.closest(".panel").find(".panel-heading").prepend(text);}
	// });
 
    
 }
 
function charge_one_dashboard(i,limit)
{
	var el=$(".link_dashboard").eq(i);
	var descriptor=el.attr("descriptor");
	var container=el.closest(".panel").find(".panel-body");
	var collapse=el.closest(".panel").find(".panel-collapse");
	if(collapse.hasClass("in"))
	{
	    var adresse="<?php echo base_url();?>app/charge_contenu_dashboard/"+descriptor;
	    container.html("<div style='text-align:center; margin-top: 100px; margin-bottom: 100px'><i class='fa fa-spin fa-spinner fa-4x'></i></div>");

	    jQuery.ajax
	    ({	
		   type:'POST',
		    url: adresse,
		    //data: dataString,
		    cache: false,

		    success: function(html)
		    { 
			//alert(html);
			if(html===" ")
			{

			    container.html("<div style='text-align:center; margin-top: 100px; margin-bottom: 100px'>Pas de résultats</div>")
			 }
			 else
			 {
			    container.html(html);

			} 
			
			i++;
			 alert(limit);
			if(i<limit){
			   
			    charge_one_dashboard(i,limit);
			}
			//recup_number(container);
		    }

	    });
	    
	}
    
 
}
    
jQuery(document).ready(function()
{
     var limit=$(".link_dashboard").length;
				
	//charge_one_dashboard(0,limit);			
				
    $(".link_dashboard").each(function(){
	
	var descriptor=$(this).attr("descriptor");
	var container=$(this).closest(".panel").find(".panel-body");
	var collapse=$(this).closest(".panel").find(".panel-collapse");
	if(collapse.hasClass("in"))
	{
	    //alert();
	    charge_contenu_dashboard(container, descriptor);
	}
	
	
    });
    
     $(document).off("shown.bs.collapse",".collapse_reload").on("shown.bs.collapse",".collapse_reload", function(e){
    
	
	var container_panel=$(this).closest(".panel");
	var container=$(this).closest(".panel").find(".panel-body");
	var descriptor=container_panel.find("a").attr("descriptor");
	 charge_contenu_dashboard(container, descriptor);
    });
    
    $(document).off("click",".bt_charge_pot").on("click",".bt_charge_pot", function(e){
     var adresse="<?php echo base_url();?>app/dash_board_pot";
     var container=$("#tabpot");
     $(this).removeClass("bt_charge_pot");
     jQuery.ajax
		    ({	
			   type:'POST',
			    url: adresse,
			 
			    cache: false,

			    success: function(html)
			    { 
				container.html(html);
				
				$(".link_dashboard",container).each(function(){

				   var descriptor=$(this).attr("descriptor");
				   var container=$(this).closest(".panel").find(".panel-body");
				   var collapse=$(this).closest(".panel").find(".panel-collapse");
				   if(collapse.hasClass("in"))
				   {
				       //alert();
				       charge_contenu_dashboard(container, descriptor);
				   }


				 });
				
				
			    }
			    
		    });
     
 });
 
 $(document).off("click",".bt_charge_equipe").on("click",".bt_charge_equipe", function(e){
     var adresse="<?php echo base_url();?>app/dash_board_equipe";
     var container=$("#tabequipe");
     $(this).removeClass("bt_charge_equipe");
     jQuery.ajax
		    ({	
			   type:'POST',
			    url: adresse,
			 
			    cache: false,

			    success: function(html)
			    { 
				container.html(html);
				$(".link_dashboard",container).each(function(){

				   var descriptor=$(this).attr("descriptor");
				   var container=$(this).closest(".panel").find(".panel-body");
				   var collapse=$(this).closest(".panel").find(".panel-collapse");
				   if(collapse.hasClass("in"))
				   {
				       //alert();
				       charge_contenu_dashboard(container, descriptor);
				   }


				 });
				
			    }
			    
		    });
     
 });
    
    
  
  
});
  </script>