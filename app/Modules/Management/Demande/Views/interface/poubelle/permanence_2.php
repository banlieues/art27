<div style="background:white" class="container-fluid "> 
    

    
    <div style="display:none" class="permanence_loading">
	<div style='text-align:center; margin:100px 0;'><i class='fa fa-spin fa-spinner fa-4x'></i><br>Veuillez patienter…</div>
	
    </div>  
<div class="row container_form_permanence">
   
    <div class="col-md-6">  
	
	
	
	<?php echo $form_insert;?>
    </div>   
     <div class="col-md-6"> 
	 <div class="tr_fiche_id_demandeur">
	     <form>
	     <label class="label_demandeur">Demandeur</label>
	    <div class="container_input_form_indirect container_input_form_indirect_demandeur"> 
	       <!--<button class="btn btn-warning btn-xs modal_demandeur masque_input_form_indirect_demandeur">Attaché un demandeur</button>-->
	       
<a has_attachement_direct="1" fh-descriptor="fhd_liste_demandeur" class="btn btn-success btn-xs fh_dao_fiche_insert" href="<?php echo base_url();?>fh/fhc_dao/get_fiche_insert">Attacher un demandeur</a>	       
	     <a 
		   style="display:none"
		   class="fh_dao_fiche_clone fh_dao_fiche_appel_fhattachement btn btn-success btn-xs a_input_form_indirect_demandeur"
		   fh-descriptor="fhd_liste_personne" 
		   href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/@remplace@" 
		   href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/@remplace@" 
		   href="<?php echo base_url();?>fh/fhc_dao/page_view/@remplace@/fhd_liste_personne"
		   
		   >Voir demandeur</a>
	    
	     <a style="display:none" container="input_form_indirect_demandeur" class="minus_form_indirect_attachement minus_input_form_indirect_demandeur btn btn-xs btn-danger"><i class="fa fa-minus"></i></a>

	<input type="hidden" name="id_demandeur" value="0" class="input_form_indirect_demandeur appel_fhattachement">
	    </div>
	     </form>
	 </div>
	
	 
	 <div style="display:none" style="margin-top:10px; margin-bottom:10px" class="tr_fiche_id_bien">
	     <form>
	    <label class="label_bien">Bien</label>
	    <div class="container_input_form_indirect container_input_form_indirect_bien"> 
		<!--<button class="btn btn-warning btn-xs  modal_bien masque_input_form_indirect_bien">Attaché un bien</button>-->
		<a has_attachement_direct="1" fh-descriptor="fhd_liste_bien" class="btn btn-success btn-xs fh_dao_fiche_insert" href="http://local.dev.homegrade.banlieues.be/fh/fhc_dao/get_fiche_insert">Attacher un bien </a>
		<a 
		    style="display:none"
		   class="fh_dao_fiche_clone btn btn-success btn-xs a_input_form_indirect_bien"
		   fh-descriptor="fhd_liste_bien" 
		   href-title="<?php echo base_url();?>fh/fhc_dao/get_fiche_title/9" 
		   href-ajax="<?php echo base_url();?>fh/fhc_dao/get_fiche/9" 
		   href="<?php echo base_url();?>fh/fhc_dao/page_view/9/fhd_liste_personne"
		   
		   >Voir bien</a>
		<a style="display:none" container="input_form_indirect_bien"  class="minus_form_indirect_attachement minus_input_form_indirect_bien btn btn-xs btn-danger"><i class="fa fa-minus"></i></a>

		<input type="hidden" name="id_bien" value="0" class="input_form_indirect_bien  appel_fhattachement">
	    </div>
	     </form>
	  </div>
	  <?php echo $form_insert_2; ?>
	 <div style="margin-top:10px; margin-bottom:10px" class="tr_is_occupe">
	     <form>
		 <input type="checkbox" name="is_occupe" value="1"> 
	    <label>Je m'en occupe</label>
	   
	     </form>
	  </div>
	 <button class="btn btn-success submit_permanence">Enregistrer</button>
	 <button style="display:none" class="btn btn-default attente_permanence"><i class='fa fa-spin fa-spinner'></i> Enregistrement en cours…</button>
    </div> 
 	
</div> 
</div>    


<script type="text/javascript">
  $(function () {
    

      $(document).off('click', '.modal_demandeur').on('click', '.modal_demandeur', function(){
	  var id_message=$(this).closest("tr").attr("id_message");
	
	var dataString="id_message="+id_message;
          $('#fh_dao_fiche_modal').modal();
          $('.fh_dao_fiche_modal_container').html("<div style='text-align:center; margin-top: 20px'><i class='fa fa-spin fa-spinner fa-4x'></i></div>");
          
          var adresse = "<?=base_url();?>app/load_demandeur/";
          jQuery.ajax
           ({  
              type:'POST',
              url: adresse,
              data: dataString,
              cache: false,

              success: function(html)
              { 
                    $('.fh_dao_fiche_modal_container').html(html);
              }
          
        });
	    return false;
          
      });
      
         $(document).off('click', '.modal_bien').on('click', '.modal_bien', function(){
	  var id_message=$(this).closest("tr").attr("id_message");
	
	var dataString="id_message="+id_message;
          $('#fh_dao_fiche_modal').modal();
          $('.fh_dao_fiche_modal_container').html("<div style='text-align:center; margin-top: 20px'><i class='fa fa-spin fa-spinner fa-4x'></i></div>");
          
          var adresse = "<?=base_url();?>app/load_bien/";
          jQuery.ajax
           ({  
              type:'POST',
              url: adresse,
              data: dataString,
              cache: false,

              success: function(html)
              { 
                    $('.fh_dao_fiche_modal_container').html(html);
              }
          
        });
	    return false;
          
      })
  })
</script>

