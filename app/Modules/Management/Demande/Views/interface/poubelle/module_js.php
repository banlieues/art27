<script>
jQuery(document).ready(function()
{
	$(document).off("click",".view_choice_module").on('click','.view_choice_module',function(){
	    var is_open=$(this).attr("is_open");
	
	    if(is_open==="0"){
		$(".choice_menu").show();
		$(this).attr("is_open","1");
		$(".position_hide").hide();
		$(".search_all").hide();
		$(".c_rubrique").hide();
		$(".show_search_all").removeClass("on");
	    
	    }
	    if(is_open==="1"){
		$(".choice_menu").hide();
		$(this).attr("is_open","0");
		$(".position_hide").show();
		$(".c_rubrique").show();
	    }
	    return false;
	});
	
	$(document).off("click",".ferme_menu_module").on('click','.ferme_menu_module',function(){
	  
	    $(".choice_menu").hide();
	    $(".view_choice_module").attr("is_open","0");
	    $(".position_hide").show();
	    $(".c_rubrique").show();
	
	    return false;
	});
	
	
	
	
});
</script>