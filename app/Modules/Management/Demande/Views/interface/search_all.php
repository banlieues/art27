
<div  style='display:none; margin-top:20px; margin-bottom:20px' class='search_all'>
    <div class='row'>
	<div class='col-md-3'></div>
	<div class='col-md-6'>
       <form class="form_dao_search_all form">

		     <div class="input-group">
			 <span class="input-group-addon"><i class="fa fa-search" aria-hidden="true"></i></span>

			   <input 
			       <?php if($this->session->userdata("string_search")):?> value="<?php echo $this->session->userdata("string_search");?>" is_entry="1" <?php else:?> is_entry="2"<?php endif;?>
			       fh-href="http://local.dev.sare.banlieues.be/fh/fhc_user/page_liste_users/" type="text" class="form-control fh-dao-search-value fh-dao-search search fh_dao_all_entity " name='search' placeholder="Rechercher partout">
			   <input type="hidden" name='is_search' value='1'>
		     <span id="menu_bt_search" class="input-group-btn">



				     <button style="margin-left:10px !important" type="button" class="btn btn-success fh-dao-search-all-direct">Ok</button>
				      <button style="margin-left:2px !important" type="button" class="btn btn-danger fh-dao-search-all-zero">Annuler</button>
				     </span>

		   </div>
		</form> 
	    </div>
	 <div class='col-md-3'></div>
    </div>
    
  
    <div  class='row result_all'>
	<div style='background-color:white; margin-top:20px; display:none' class='result_all_loading'></div>
	<div style='display:none' class='listes_all'>
	<div style='background-color:white; margin-top:20px; padding:0 20px' class='result_all_demande'></div>
	<div style='background-color:white; margin-top:20px; padding:0 20px' class='result_all_demandeur'></div>
	<div style='background-color:white; margin-top:20px; padding:0 20px' class='result_all_bien'></div>
	<div style='background-color:white; margin-top:20px; padding:0 20px' class='result_all_professionnel'></div>
	<div style='background-color:white; margin-top:20px; padding:0 20px' class='result_all_tache'></div>
	<div style='background-color:white; margin-top:20px; padding:0 20px' class='result_all_rdv'></div>

	</div>
    </div>
     
</div>