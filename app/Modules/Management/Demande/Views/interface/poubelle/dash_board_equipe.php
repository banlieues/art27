<div class="row">
  <div class="col-lg-10">
      <?php if($this->fh_dao->get_autorisation("infop1")): ?>
	<div  id="well_dash_eq_information" class="well well_dash">
	    <h2><small>Informations</small></h2>
	    <div class="panel-group reste_ouvert" id="accordionssssss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqOne" aria-expanded="true" aria-controls="collapseEqOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_ouverte_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqTwo" aria-expanded="false" aria-controls="collapseEqTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqTwo">
		    <div  id="tb_datt"  aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		
		</div>
	 </div>
      <?php endif;?>
      <?php if($this->fh_dao->get_autorisation("visip1")): ?>
	 <div  id="well_dash_eq_visites" class="well well_dash">
	    <h2><small>Visites </small></h2>
	    <div class="panel-group reste_ouvert" id="visite_eq" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqvOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_commun_visite" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqvOne" aria-expanded="true" aria-controls="collapseEqvOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqvOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqvOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew" aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqvTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_incomplete_visite" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqvTwo" aria-expanded="false" aria-controls="collapseEqvTwo">
			   <?php echo icon("panel");?> Demandes incomplètes
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqvTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqvTwo">
		    <div  id="tb_datt"  aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqvThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_ouverte_visite" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqvThree" aria-expanded="false" aria-controls="collapseEqvThree">
		       <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqvThree" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqvThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		 <div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqvFour">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqvFour" aria-expanded="false" aria-controls="collapseEqvFour">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqvFour" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqvFour">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		 
		
		</div>
	 </div>
      <?php endif;?>
      <?php if($this->fh_dao->get_autorisation("accompp1")): ?>
	 <div  id="well_dash_eq_acc" class="well well_dash">
	 	 <h2><small>Accompagnements </small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqaccOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_acc_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqaccOne" aria-expanded="true" aria-controls="collapseEqaccOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqaccOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqaccOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqaccOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_acc_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqaccOTwo" aria-expanded="false" aria-controls="collapseEqaccOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqaccOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqaccOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqaccThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_rdv_planifie_acc" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqaccThree" aria-expanded="false" aria-controls="collapseEqaccThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqaccThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqaccThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
<?php endif;?>
      <?php if($this->fh_dao->get_autorisation("pretvp1")): ?>
 <div  id="well_dash_eq_pretv" class="well well_dash">
	 	 <h2><small>Prêt Vert </small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingEqPretvOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_pretv_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPretvOne" aria-expanded="true" aria-controls="collapseEqPretvOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPretvOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqPretvOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingEqPretvOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_pretv_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPretvOTwo" aria-expanded="false" aria-controls="collapseEqPretvOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPretvOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqPretvOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingEqPretvThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_pretv_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPretvThree" aria-expanded="false" aria-controls="collapseEqPretvThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPretvThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqPretvThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
      <?php endif;?>
      
<?php if($this->fh_dao->get_autorisation("facip2")): ?>
<div  id="well_dash_eq_urbanisme" class="well well_dash">
	 	 <h2><small>Facilitateur urbanisme </small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-warning">
		  <div class="panel-heading" role="tab" id="headingEqUrbanismeOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_urbanisme_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqUrbanismeOne" aria-expanded="true" aria-controls="collapseEqUrbanismeOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqUrbanismeOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqUrbanismeOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-warning">
		  <div class="panel-heading" role="tab" id="headingEqUrbanismeOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_urbanisme_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqUrbanismeOTwo" aria-expanded="false" aria-controls="collapseEqUrbanismeOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqUrbanismeOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqUrbanismeOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-warning">
		  <div class="panel-heading" role="tab" id="headingEqUrbanismeThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_urbanisme_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqUrbanismeThree" aria-expanded="false" aria-controls="collapseEqUrbanismeThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqUrbanismeThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqUrbanismeThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
      <?php endif;?>
 <?php if($this->fh_dao->get_autorisation("petitcopp2")): ?>     
<div  id="well_dash_eq_coproprietes" class="well well_dash">
	 	 <h2><small>Petites copropriétés </small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-danger">
		  <div class="panel-heading" role="tab" id="headingEqCoproOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_copro_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqCoproOne" aria-expanded="true" aria-controls="collapseEqCoproOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqCoproOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqCoproOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-danger">
		  <div class="panel-heading" role="tab" id="headingEqCoproOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_copro_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqCoproOTwo" aria-expanded="false" aria-controls="collapseEqCoproOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqCoproOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqCoproOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-danger">
		  <div class="panel-heading" role="tab" id="headingEqCoproThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_copro_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqCoproThree" aria-expanded="false" aria-controls="collapseEqCoproThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqCoproThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqCoproThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
      <?php endif;?>
   <?php if($this->fh_dao->get_autorisation("beexp2")): ?>  
 <div  id="well_dash_eq_beex" class="well well_dash">
	 	 <h2><small>Be exemplary </small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default" >
		  <div class="panel-heading" style="background-color:#9370DB;color:#fff;" role="tab" id="headingEqBeexOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_beex_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqBeexOne" aria-expanded="true" aria-controls="collapseEqBeexOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqBeexOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqBeexOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#9370DB;color:#fff;" role="tab" id="headingEqBeexOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_beex_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqBeexOTwo" aria-expanded="false" aria-controls="collapseEqBeexOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqBeexOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqBeexOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#9370DB;color:#fff;" role="tab" id="headingEqBeexThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_beex_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqBeexThree" aria-expanded="false" aria-controls="collapseEqBeexThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqBeexThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqBeexThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
 <?php endif;?>
    <?php if($this->fh_dao->get_autorisation("allocp2")): ?>       
 <div  id="well_dash_eq_alloyer" class="well well_dash">
	 	 <h2><small>Allocation loyer </small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-info">
		  <div class="panel-heading" role="tab" id="headingEqAlloyerOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_alloyer_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqAlloyerOne" aria-expanded="true" aria-controls="collapseEqAlloyerOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqAlloyerOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqAlloyerOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-info">
		  <div class="panel-heading" role="tab" id="headingEqAlloyerOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_alloyer_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqAlloyerOTwo" aria-expanded="false" aria-controls="collapseEqAlloyerOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqAlloyerOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqAlloyerOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-info">
		  <div class="panel-heading" role="tab" id="headingEqAlloyerThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_alloyer_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqAlloyerThree" aria-expanded="false" aria-controls="collapseEqAlloyerThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqAlloyerThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqAlloyerThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
<?php endif;?>
<?php if($this->fh_dao->get_autorisation("ptpatrip3")): ?>  
 <div  id="well_dash_eq_patrimoine" class="well well_dash">
	 	 <h2><small>Petit patrimoine </small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#FDF5E6;" role="tab" id="headingEqPatOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_patrimoine_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPatOne" aria-expanded="true" aria-controls="collapseEqPatOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPatOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqPatOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#FDF5E6;" role="tab" id="headingEqPatOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_patrimoine_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPatOTwo" aria-expanded="false" aria-controls="collapseEqPatOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPatOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqPatOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#FDF5E6;" role="tab" id="headingEqPatThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_patrimoine_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPatThree" aria-expanded="false" aria-controls="collapseEqPatThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPatThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqPatThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
<?php endif;?>
<?php if($this->fh_dao->get_autorisation("locaprop3")): ?>  
 <div  id="well_dash_eq_locpro" class="well well_dash">
	 	 <h2><small>Facilitation locataire / propriétaire </small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#FA8072;" role="tab" id="headingEqLpOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_locpro_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqLpOne" aria-expanded="true" aria-controls="collapseEqLpOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqLpOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqLpOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#FA8072;" role="tab" id="headingEqLpOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_locpro_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqLpOTwo" aria-expanded="false" aria-controls="collapseEqLpOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqLpOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqLpOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#FA8072;" role="tab" id="headingEqLpThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_locpro_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqLpThree" aria-expanded="false" aria-controls="collapseEqLpThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqLpThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqLpThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
<?php endif;?>
<?php if($this->fh_dao->get_autorisation("acousp3")): ?>  

<div  id="well_dash_eq_acous" class="well well_dash">
	 	 <h2><small>Acoustique</small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#F08080;" role="tab" id="headingEqacousOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_acous_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqacousOne" aria-expanded="true" aria-controls="collapseEqacousOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqacousOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqacousOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#F08080;" role="tab" id="headingEqacousOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_acous_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqacousOTwo" aria-expanded="false" aria-controls="collapseEqacousOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqacousOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqacousOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#F08080;" role="tab" id="headingEqacousThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_acous_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqacousThree" aria-expanded="false" aria-controls="collapseEqacousThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqacousThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqacousThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
 <?php endif;?>     
 
<?php if($this->fh_dao->get_autorisation("enrrevp3")): ?> 
<div  id="well_dash_eq_er" class="well well_dash">
	 	 <h2><small>Energies renouvelables</small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#20B2AA;" role="tab" id="headingEqerOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_energie_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqerOne" aria-expanded="true" aria-controls="collapseEqerOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqerOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqerOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#20B2AA;" role="tab" id="headingEqerOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_energie_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqerOTwo" aria-expanded="false" aria-controls="collapseEqerOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqerOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqerOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#20B2AA;" role="tab" id="headingEqerThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_energie_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqerThree" aria-expanded="false" aria-controls="collapseEqerThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqerThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqerThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
<?php endif;?>
      <?php if($this->fh_dao->get_autorisation("patrip4")): ?> 
  <div  id="well_dash_eq_patrimoinegr" class="well well_dash">
	 	 <h2><small>Patrimoine</small></h2>
	  <div class="panel-group reste_ouvert" id="accordionssss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqPatGrOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_patgr_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPatGrOne" aria-expanded="true" aria-controls="collapseEqPatGrOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPatGrOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqPatGrOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading"  role="tab" id="headingEqPatGrOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_patgr_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPatGrOTwo" aria-expanded="false" aria-controls="collapseEqPatGrOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPatGrOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqPatGrOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqPatGrThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_patgr_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPatGrThree" aria-expanded="false" aria-controls="collapseEqPatGrThree">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPatGrThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqPatGrThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
      <?php endif;?>
          <?php if($this->fh_dao->get_autorisation("ptinterventionp4")): ?> 
<div  id="well_dash_eq_pintervention" class="well well_dash">
	 	 <h2><small>Petites intervention</small></h2>
	  <div class="panel-group reste_ouvert" id="accordionssss" role="tablist" aria-multiselectable="true">
		<!--<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqPintOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_eq_demande_Pintervention_commun" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPintOne" aria-expanded="true" aria-controls="collapseEqPintOne">
		       <?php echo icon("panel");?> Demandes Pot commun
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPintOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqPintOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew" class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-default">
		  <div class="panel-heading"  role="tab" id="headingEqPintOTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_Pint_cons" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPintOTwo" aria-expanded="false" aria-controls="collapseEqPintOTwo">
			   <?php echo icon("panel");?> Demandes ouvertes conseillers
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPintOTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingEqPintOTwo">
		    <div  id="tb_datt" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>-->

		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqPintOne">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_Pintervention_rdv_a_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPintOne" aria-expanded="false" aria-controls="collapseEqPintOne">
		       <?php echo icon("panel");?> Rendez-vous à planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPintOne" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqPintOne">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqPintTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_Pintervention_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPintTwo" aria-expanded="false" aria-controls="collapseEqPintTwo">
		       <?php echo icon("panel");?> Rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPintTwo" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqPintTwo">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingEqPintThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_eq_demande_Pintervention_rdv_effectue" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseEqPintThree" aria-expanded="false" aria-controls="collapseEqPintThree">
		       <?php echo icon("panel");?> Rendez-vous effectués
		      </a>
		    </h4>
		  </div>
		  <div id="collapseEqPintThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingEqPintThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
	</div>
</div>
      <?php endif;?>
  </div>
   <div class="col-lg-2">
		<div id='menu_fixed_equipe'  class="menu_fixed_Eq" style="padding-right:10px; min-width: 200px !important">
		    <h4><i class="fa fa-desktop fa-1x"></i> Equipes</h4>
			    <ul class="list-group">
				 <li href="#well_dash_boite"  style="cursor:pointer" href=""  class="list-group-item">
			      <a style="background-color: mediumpurple; border-color:mediumpurple" class="btn btn-success" href="<?php echo base_url();?>app/index_bureau_ajout">
				  <i class="fa fa-commenting"></i> Nouvelle demande</a>
			    </li>
				    <?php  if($this->fh_dao->get_autorisation("infop1")): ?>
					<li href="#well_dash_eq_information"  style="cursor:pointer" href=""  class="list-group-item ascroll">
					  Informations
					</li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("visip1")): ?>
				    <li href="#well_dash_eq_visites" style="cursor:pointer" class="list-group-item tr_fiche_id_bien ascroll">
				     Visites
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("accompp1")): ?>
				    <li href="#well_dash_eq_acc" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Accompagnements
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("pretvp1")): ?>
				    <li href="#well_dash_eq_pretv" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Prêt Vert
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("facip2")): ?>
				    <li href="#well_dash_eq_urbanisme" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Facilitateur urbanisme
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("petitcopp2")): ?>
				    <li href="#well_dash_eq_coproprietes" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Petites copropriétés
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("beexp2")): ?>
				    <li href="#well_dash_eq_beex" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Be exemplary
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("allocp2")): ?>
				    <li href="#well_dash_eq_alloyer" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Allocation loyer
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("ptpatrip3")): ?>
				    <li href="#well_dash_eq_patrimoine" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Petit patrimoine
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("locaprop3")): ?>
				    <li href="#well_dash_eq_locpro" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Locataire / propriétaire
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("acousp3")): ?>
				    <li href="#well_dash_eq_acous" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Acoustique
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("enrrevp3")): ?>
				    <li href="#well_dash_eq_er" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Energies renouvelables
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("patrip4")): ?>
				     <li href="#well_dash_eq_patrimoinegr" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Patrimoine
				    </li>
				    <?php endif;?>
				    <?php  if($this->fh_dao->get_autorisation("ptinterventionp4")): ?>
				     <li href="#well_dash_eq_pintervention" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Petites interventions
				    </li>
				    <?php  endif; ?>
			    </ul>

		</div>
    </div>

</div>
