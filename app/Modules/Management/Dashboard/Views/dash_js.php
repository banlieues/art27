<script>
    function load_table(id,container,data_rank)
    {

	var url="<?php echo base_url();?>dashboard/load_table/"+id+"/"+data_rank;
	jQuery.ajax
		    ({	
			    type:'POST',
			    url: url,
			    cache: false,
			    success: function(html)
			    { 
					container.html(html);
				
				
			    },
				error: function(xhr, status, error){
				
					var response_text = jQuery.parseJSON(xhr.responseText);
        			container.html("Erreur ! Impossible d'afficher "+response_text.message);
    			},

			})
	//container.html("<div style='text-align:center; margin: 20px 0'><i class='fas fa-circle-notch fa-spin fa-2x'></i></div>");
    }
    
    jQuery(document).ready(function()
    {  

	$(".load_table_ajax").each(function(){
	    //alert();
	    var e=$(this);
	    var id=$(this).attr("id_user_table");
	    var data_rank=$(this).attr("data-rank");
	    var container=e.closest(".c_table");
	    load_table(id,container,data_rank);
	});
     
      $(document).off("change",".go_pannel_go").on("change",".go_pannel_go",function(){
	var val=$(this).val();
	var col=$("#"+val);
	
	$('html,body').animate({scrollTop: col.offset().top-180}, 'fast'      );
	return false;
     });
     
     $(document).off("click","#expand_all").on("click","#expand_all",function(){
	 //alert();
	 $(".dash_expand").each(function(){
	     var col=$(this).closest(".lg_dynamique");
	col.removeClass("col-lg-6");
	col.addClass("col-lg-12");
	$(this).hide();
	$(".dash_compress",col).show();
	 })
	//$(".dash_expand").trigger("click");
	return false;
     });
     
     
       $(document).off("click","#compress_all").on("click","#compress_all",function(){
	 //alert();
	 $(".dash_expand").each(function(){
	     var col=$(this).closest(".lg_dynamique");
	col.removeClass("col-lg-12");
	col.addClass("col-lg-6");
	$(this).hide();
	$(".dash_compress",col).show();
	 })
	//$(".dash_expand").trigger("click");
	return false;
     });
     
      $(document).off("click",".bt_maj").on("click",".bt_maj",function(){
	 //alert();
	if(confirm("Voulez-vous mettre à jour la requête d'origine?"))
        {
            var id_user_table=$(this).attr("id_user_table");
            var container=$(this).closest(".c_table");
            var container_reload=container.find(".card-body");
            container_reload.html("<div style='text-align:center; margin: 100px 0'><i class='fas fa-circle-notch fa-spin fa-2x'></i></div>");
            var dataString="id_user_table="+id_user_table;
            var url="<?php echo base_url();?>/dashboard/maj_requete_orig";
            
             jQuery.ajax
	    ({	
		    type:'POST',
		    url: url,
		   data: dataString,
		    cache: false,
		    success: function(html)
		    { 
			load_table(id_user_table,container);
		    }
		})
            
        }
	return false;
     });
     
     
     $(document).off("click",".dash_expand").on("click",".dash_expand",function(){
	var col=$(this).closest(".lg_dynamique");
	col.removeClass("col-lg-6");
	col.addClass("col-lg-12");
	$(this).hide();
	$(".dash_compress",col).show();
	$('html,body').animate({scrollTop: col.offset().top-180}, 'fast'      );
	return false;
     });
     
    
     
    
    
      $(document).off("change",".selectfiltreuser").on("change",".selectfiltreuser",function(){
	var url=$(this).val();
	window.location.href = url;
	return false;
     });
     
     $(document).off("click",".dash_compress").on("click",".dash_compress",function(){
	var col=$(this).closest(".lg_dynamique");
	col.removeClass("col-lg-12");
	col.addClass("col-lg-6");
	$(this).hide();
	$(".dash_expand",col).show();
	$('html,body').animate({scrollTop: col.offset().top-180}, 'fast'      );
	return false;
     });
     
      $(document).off("click",".id_table_delete").on("click",".id_table_delete", function(e) 
	{
		
		if(confirm("Voulez-vous supprimer la table du tableau de bord?")){
		 var id_user_table=$(this).attr("id_user_table");
		 var id_user=$(this).attr("id_user");
		 var id_onglet=$(this).attr("id_onglet");
		 var container=$(this).closest(".lg_dynamique");
		 var dataString="id_user_table="+id_user_table;
		 var url="<?php echo base_url();?>/dashboard/delete_table";
		// alert(dataString);
		 jQuery.ajax
	    ({	
		    type:'POST',
		    url: url,
		   data: dataString,
		    cache: false,
		    success: function(html)
		    { 
			alert("La table a été effacée !");
			container.remove();
//		
		    }
		})
		 }
		return false;
			
	});
	
	 $(document).off("click",".id_onglet_delete").on("click",".id_onglet_delete", function(e) 
	{
		
		if(confirm("Voulez-vous supprimer l'onglet du tableau de bord? Attention, cette action va aussi supprimer les tables qui dépendent de cet onglet. Si vous voulez garder les tables de l'onglet, déplacer d'abord les tables dans un autre onglet et ensuite supprimer l'onglet")){
		 var id_onglet=$(this).attr("id_onglet");
		 var id_user=$(this).attr("id_user");
		var dataString="id_onglet="+id_onglet;
		 var url="<?php echo base_url();?>/dashboard/delete_onglet";
		// alert(dataString);
		 jQuery.ajax
	    ({	
		    type:'POST',
		    url: url,
		   data: dataString,
		    cache: false,
		    success: function(html)
		    { 
			alert("L'onglet a été supprimé!");
			var adress="<?php echo base_url();?>/dashboard/index/"+id_user;
			window.location.href = adress;
		    }
		})
		 }
		return false;
			
	});

	$(document).off("click",".dash_dupliquer").on("click",".dash_dupliquer",function(){
		var col=$(this).closest(".lg_dynamique");
		var nom_table=$(".nom_table",col).text();
		
		if(confirm('Voulez-vous dupliquer la table "'+nom_table+' ?')){
			
			var id_user_table=$(this).attr("id_user_table");
			var dataString="id_user_table="+id_user_table;
			var url="<?php echo base_url();?>/dashboard/dupliquer_table";
			var container_reload=col.closest(".card-body");
			container_reload.html("<div style='text-align:center; margin: 100px 0'><i class='fas fa-circle-notch fa-spin fa-2x'></i></div>");
			jQuery.ajax
				({	
					type:'POST',
					url: url,
				data: dataString,
					cache: false,
					success: function(html)
					{ 
						location.reload();
					}
				})
				}
				return false;
     });
     
      $(document).off("click",".dash_option_table").on("click",".dash_option_table",function(){
	
			var col=$(this).closest(".lg_dynamique");
			col.removeClass("col-lg-6");
			col.addClass("col-lg-12");
			//$(".dash_expand",col).hide();
			//$(".dash_compress",col).hide();
			$('html,body').animate({scrollTop: col.offset().top-180}, 'fast'      );
			var container=$(this).closest(".c_table");
			$(".table_lecture",container).hide();
			$(".table_edition",container).show();
			$(this).hide();
			$(".dash_option_table_close",container).show();
			$(".mode_no_edition",container).hide();
	return false;
     });
     
     $(document).off("click",".dash_option_table_close").on("click",".dash_option_table_close",function(){
		
	var container=$(this).closest(".c_table");
	
	$(".table_lecture",container).show();
	$(".table_edition",container).hide();
	var col=$(this).closest(".lg_dynamique");
	var size=col.attr("size");
	if(size=="6"){
	    col.removeClass("col-lg-12");
	    col.addClass("col-lg-6");
	}
	$(this).hide();
	$(".dash_option_table",container).show();
	$(".mode_no_edition",container).show();
	$('html,body').animate({scrollTop: container.offset().top-180}, 'fast');
	return false;
     });
     
      $(document).off("submit",".form_option_insert").on("submit",".form_option_insert",function(){
	 
	var form=$(this);
	var id_user=form.attr("id_user");
	var id_onglet=$(".mon_onglet",form).val();
	var dataString=form.serialize();
	var url="<?php echo base_url()?>/dashboard/form_insert";
	var container=form.closest(".c_table");
	var container_reload=container.closest(".card-body");
	container_reload.html("<div style='text-align:center; margin: 100px 0'><i class='fas fa-circle-notch fa-spin fa-2x'></i></div>");
	 jQuery.ajax
	    ({	
		    type:'POST',
		    url: url,
		   data: dataString,
		    cache: false,
		    success: function(html)
		    { 
			//alert("La table a été créée !");
			var adress="<?php echo base_url();?>/dashboard/index/"+id_user+"/"+id_onglet;
			//location.reload();
			window.location.href = adress;
		    }
		})
	
	
	return false;
	});
     
      $(document).off("submit",".form_option_update").on("submit",".form_option_update",function(){
	 
	var form=$(this);
	var dataString=form.serialize();
	var url="<?php echo base_url()?>/dashboard/form_update";
	var container=form.closest(".c_table");
	var container_reload=container.find(".card-body");
	container_reload.html("<div style='text-align:center; margin: 100px 0'><i class='fas fa-circle-notch fa-spin fa-2x'></i></div>");
//alert(dataString);
	jQuery.ajax
		    ({	
			    type:'POST',
			    url: url,
			    data: dataString,
			    cache: false,
			    dataType: 'json',
			    success: function(data)
			    { 
				//alert(data.id);
				//alert(data.size);
				load_table(data.id,container);
				container.attr("size",data.size);
				if(data.size==="12")
				{
				    container.removeClass("col-lg-6");
				    container.addClass("col-lg-12");
				    
				}
				else
				{
				    container.removeClass("col-lg-12");
				    container.addClass("col-lg-6"); 
				    
				}
				
			    }
			})
	return false;
     });
     
     
     $(document).off("change",".select_requete").on("change",".select_requete",function(){
	  $('.soumettre_table').hide();
	     $('.alert-no-registrer').hide(); 
	 var select=$(this);
	 var val=select.val();
	 if(val==="0"){
	     $('.soumettre_table').hide();
	     $('.alert-no-registrer').show(); 
	     $(".load_field").html("");
             $(".load_filtre").html("");
	 }
	 else
	 {
	    
	     $(".load_field").html("<div class='text-center'><i class='fas fa-circle-notch fa-spin'></i></div>");
             $(".load_filtre").html("<div class='text-center'><i class='fas fa-circle-notch fa-spin'></i></div>");
	     var url="<?php echo base_url();?>/dashboard/get_field/"+val;
             var urlFiltre="<?php echo base_url();?>/dashboard/get_filtre/"+val;
	     //alert(url);
	     jQuery.ajax
	    ({	
		    type:'POST',
		    url: url,
		   
		    cache: false,
		    success: function(html)
		    { 
			 $(".load_field").html(html);
			$('.soumettre_table').show();
			$('.alert-no-registrer').hide(); 
		    }
		})
                
                 jQuery.ajax
	    ({	
		    type:'POST',
		    url: urlFiltre,
		   
		    cache: false,
		    success: function(html)
		    { 
			 $(".load_filtre").html(html);
			
		    }
		})
	     
         }
	 
     });
     
     
          $(".retrie").sortable({
        update: function(event,ui){
            $(this).children().each(function(index){
                if($(this).attr('data-rank')!== (index+1)){
                   if($(this).attr('data-rank') === 0){
                       index = 0 ;
                       $(this).attr('data-rank',(index)).addClass('updateTab');}
                   else if($(this).attr('data-rank') < 10 ){
                       $(this).attr('data-rank',(index+1)).addClass('updateTab');}
                   else{
                       $(this).attr('data-rank',((index+1)*10)).addClass('updateTab');
                   }
                }
           });
         newRankField();
       }
   });
$("#nav_onglet").sortable({
        update: function(event,ui){
            $(this).children().each(function(index){
                if($(this).attr('data-rank')!== (index+1)){
                   if($(this).attr('data-rank') === 0){
                       index = 0 ;
                       $(this).attr('data-rank',(index)).addClass('updateTab');}
                   else if($(this).attr('data-rank') < 10 ){
                       $(this).attr('data-rank',(index+1)).addClass('updateTab');}
                   else{
                       $(this).attr('data-rank',((index+1)*10)).addClass('updateTab');
                   }
                }
           });
         newRankOnglet();
       }
   });

 
     
         $("#accordion").sortable({
			disabled: true,
        update: function(event,ui){
            $(this).children().each(function(index){
                if($(this).attr('data-rank')!== (index+1)){
                   if($(this).attr('data-rank') === 0){
                       index = 0 ;
                       $(this).attr('data-rank',(index)).addClass('updateTab');}
                   else if($(this).attr('data-rank') < 10 ){
                       $(this).attr('data-rank',(index+1)).addClass('updateTab');}
                   else{
                       $(this).attr('data-rank',((index+1)*10)).addClass('updateTab');
                   }
                }
           });
         newRank();
       }
   });
     

   $(document).on("mouseover",".card_grab",function(){ 
        $( "#accordion" ).sortable( "option", "disabled", false );

    });

    $(document).on("mouseout",".card_grab",function(){ 
        $( "#accordion" ).sortable( "option", "disabled", true );

    });

     
     //enregistrer le changement de rank:
 function newRank(){
    var positions = [];
    $('.updateTab').each(function(){
        positions.push([$(this).find(".card-information").attr('data-index'),$(this).attr('data-rank')]);
        $(this).removeClass('updateTab');
    });
     
    var url = "<?php echo base_url();?>/dashboard/update_sortable";
   
  $.ajax({
      url: url ,
      method: 'POST',
      dataType: 'text',
      data: {
             update: 1,
             positions: positions
            }, 
            success: function(response){
              console.log(positions);
            }
            
        });
    }
    
     function newRankOnglet(){
    var positions = [];
    $('.updateTab').each(function(){
        positions.push([$(this).find(".card-information").attr('data-index'),$(this).attr('data-rank')]);
        $(this).removeClass('updateTab');
    });
     
    var url = "<?php echo base_url();?>/dashboard/update_sortable_onglet";
   
  $.ajax({
      url: url ,
      method: 'POST',
      dataType: 'text',
      data: {
             update: 1,
             positions: positions
            }, 
            success: function(response){
              console.log(positions);
            }
            
        });
    }
    
    function newRankField(){
    var positions = [];
    var id=0;
    $('.updateTab').each(function(){
        positions.push([$(this).find(".card-information").attr('data-index'),$(this).attr('data-rank')]);
        $(this).removeClass('updateTab');
	id=$(this).find(".card-information").attr('data-id');
    });
     
    var url = "<?php echo base_url();?>/dashboard/update_sortable_field/"+id;
   
  $.ajax({
      url: url ,
      method: 'POST',
      dataType: 'text',
      data: {
             update: 1,
             positions: positions
            }, 
            success: function(response){
              console.log(positions);
            }
            
        });
    }
     
   
    });
</script>

<?php //$this->load->view("interface/dashboard_js"); ?>