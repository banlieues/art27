<script>
jQuery(document).ready(function()
{
   $("#nav_general").sticky({topSpacing:0});
   $("#nav_bd").sticky({topSpacing:80});
   $("#nav_dashboard").sticky({topSpacing:80});
  $("#menu_fixed_bureau").sticky({topSpacing:120});
  $("#menu_fixed_pot").sticky({topSpacing:120});
   $("#menu_fixed_equipe").sticky({topSpacing:120});
  $("#menu_dashoard").sticky({topSpacing:70});
  
  $(document).off("click","a[aria-controls='tabbureau']").on("click","a[aria-controls='tabbureau']", function(e)
	{
	    $("#menu_fixed_bureau").sticky('update');
	      $('html, body').animate({scrollTop:0}, 'slow');
	});
	
$(document).off("click","a[aria-controls='tabpot']").on("click","a[aria-controls='tabpot']", function(e)
	{
	    $("#menu_fixed_pot").sticky('update');
	    $('html, body').animate({scrollTop:0}, 'slow');
	});
	
	
$(document).off("click","a[aria-controls='tabequipe']").on("click","a[aria-controls='tabequipe']", function(e)
	{
	    $("#menu_fixed_equipe").sticky('update');
	    $('html, body').animate({scrollTop:0}, 'slow');
	});	
	
	
  });
  </script>