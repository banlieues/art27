<div class="row">
  <div class="col-lg-10">
	<div  id="well_dash_boite" class="well well_dash">
	    <h2><small>Mes nouvelles demandes</small></h2>
	    <div class="panel-group reste_ouvert" id="accordionss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_demande_new_charge" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
		       <?php echo icon("panel");?> Mes nouvelles demandes
		      </a>
		    </h4>
		  </div>
		  <div id="collapseOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew" fh_descriptor="fhd_liste_demande_filtre_charge" aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
<!--		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingTwo">
		    <h4 class="panel-title">
		      <a  role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
			   <?php echo icon("panel");?> Mes nouveaux courriels
		      </a>
		    </h4>
		  </div>
		  <div id="collapseTwo" class="collapse_reloa panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwo">
		    <div  id="tb_datt" fh_descriptor="fhd_liste_demande_filtre_charge_attente" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingThree">
		    <h4 class="panel-title">
		      <a  role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
		       <?php echo icon("panel");?> Mes nouveaux messages
		      </a>
		    </h4>
		  </div>
		  <div id="collapseThree" class="collapse_reloa panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>-->
		 
		
		</div>
	 </div>
	<div  id="well_dash_demande_ouverte"  class="well well_dash">
	     <h2><small>Mes demandes ouvertes</small></h5>
	    <div class="panel-group reste_ouvert" id="accordionOuvert" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingOneOuvert">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_bureau_demande_encours_charge" role="button" data-toggle="collapse" data-parent="#accordionOuvert" href="#collapseOneOuvert" aria-expanded="true" aria-controls="collapseOneOuvert">
		       <?php echo icon("panel");?> Mes demandes en cours
		      </a>
		    </h4>
		  </div>
		  <div id="collapseOneOuvert" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingOneOuvert">
		    <div  id="tb_dnew" aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>


		</div>
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingTwoOuvert">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_demande_attente_charge" role="button" data-toggle="collapse" data-parent="#accordionOuvert" href="#collapseTwoOuvert" aria-expanded="false" aria-controls="collapseTwoOuvert">
			   <?php echo icon("panel");?> Mes demandes en attente
		      </a>
		    </h4>
		  </div>
		  <div id="collapseTwoOuvert" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwoOuvert">
		    <div  id="tb_datt" fh_descriptor="fhd_liste_demande_filtre_charge_attente" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			 
		    </div>
		  </div>
		</div>
		
		 
		
		</div>
	</div>

	<div  id="well_dash_planning" class="well well_dash">
	    <h2><small>Planning</small></h2>
	    <div class="panel-group reste_ouvert" id="accordionssplannings" role="tablist" aria-multiselectable="true">
		<div class="panel panel-info">
		  <div class="panel-heading" role="tab" id="headingOnePlanning">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_rdv_planifie" role="button" data-toggle="collapse" data-parent="#accordionssplanning" href="#collapseOnePlanning" aria-expanded="true" aria-controls="collapseOnePlanning">
		       <?php echo icon("panel");?> Mes rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseOnePlanning" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOnePlanning">
		    <div  id="tb_dnew" aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		<div class="panel panel-info">
		  <div class="panel-heading" role="tab" id="headingSixPlanning">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_rdv_a_planifier" role="button" data-toggle="collapse" data-parent="#accordionssplanning" href="#collapseSixPlanning" aria-expanded="false" aria-controls="collapseSixPlanning">
			   <?php echo icon("panel");?> Mes rendez-vous à planifier
		      </a>
		    </h4>
		  </div>
		  <div id="collapseSixPlanning" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingSixPlanning">
		    <div  id="tb_datt"  aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
		    </div>
		  </div>
		</div>
		<div class="panel panel-info">
		  <div class="panel-heading" role="tab" id="headingTwoPlanning">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_rdv_confirme" role="button" data-toggle="collapse" data-parent="#accordionssplanning" href="#collapseTwoPlanning" aria-expanded="false" aria-controls="collapseTwoPlanning">
			   <?php echo icon("panel");?> Mes rendez-vous à confirmer
		      </a>
		    </h4>
		  </div>
		  <div id="collapseTwoPlanning" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwoPlanning">
		    <div  id="tb_datt"  aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
		    </div>
		  </div>
		</div>
		<div class="panel panel-info">
		  <div class="panel-heading" role="tab" id="headingThree">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_rdv_reporte" role="button" data-toggle="collapse" data-parent="#accordionssplanning" href="#collapseThreePlanning" aria-expanded="false" aria-controls="collapseThreePlanning">
		       <?php echo icon("panel");?> Mes rendez-vous reportés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseThreePlanning" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingThreePlanning">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
		    </div>
		  </div>
		</div>
		<div class="panel panel-warning">
		  <div class="panel-heading" role="tab" id="headingFourPlanning">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_rappels" role="button" data-toggle="collapse" data-parent="#accordionssplanning" href="#collapseFourPlanning" aria-expanded="false" aria-controls="collapseFourPlanning">
		       <?php echo icon("panel");?> Mes rappels
		      </a>
		    </h4>
		  </div>
		  <div id="collapseFourPlanning" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFourPlanning">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
		    </div>
		  </div>
		</div>
		<div class="panel panel-warning">
		  <div class="panel-heading" role="tab" id="headingFivePlanning">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_sansrappels" role="button" data-toggle="collapse" data-parent="#accordionssplanning" href="#collapseFivePlanning" aria-expanded="false" aria-controls="collapseFivePlanning">
		       <?php echo icon("panel");?> Mes tâches sans rappel
		      </a>
		    </h4>
		  </div>
		  <div id="collapseFivePlanning" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingFivePlanning">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
		    </div>
		  </div>
		</div>
		 
		
		</div>
	</div>



	<div id="well_dash_utilisateur_suiveur" class="well well_dash">
	    <h2><small>Back up</small></h2>
	    <div class="panel-group" id="accordionSuiveurs" role="tablist" aria-multiselectable="true">
<!--		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingOneSuiveur">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_demande_new_suiveur" role="button" data-toggle="collapse" data-parent="#accordionSuiveur" href="#collapseOneSuiveur" aria-expanded="true" aria-controls="collapseOneSuiveur">
		       <?php echo icon("panel");?> Les nouvelles demandes
		      </a>
		    </h4>
		  </div>
		  <div id="collapseOneSuiveur" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOneSuiveur">
		    <div  id="tb_dnew" aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax">
			 
		    </div>
		  </div>


		</div>
		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingTwoSuiveur">
		    <h4 class="panel-title">
		      <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordionSuiveur" href="#collapseTwoSuiveur" aria-expanded="false" aria-controls="collapseTwoSuiveur">
			   <?php echo icon("panel");?> Les nouveaux courriels
		      </a>
		    </h4>
		  </div>
		  <div id="collapseTwoSuiveur" class="collapse_reloa panel-collapse collapse in" role="tabpanel" aria-labelledby="headingTwoSuiveur">
		    <div  id="tb_datt" fh_descriptor="fhd_liste_demande_filtre_charge_attente" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
		    </div>
		  </div>
		</div>-->
		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingFourSuiveurNew">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_demande_new_suiveur" role="button" data-toggle="collapse" data-parent="#accordionSuiveur" href="#collapseFourSuiveurNew" aria-expanded="false" aria-controls="collapseFourSuiveurNew">
			   <?php echo icon("panel");?> Mes nouvelles demandes
		      </a>
		    </h4>
		  </div>
		  <div id="collapseFourSuiveurNew" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFourSuiveurNew">
		    <div  id="tb_datt"  aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
		    </div>
		  </div>
		</div>
		<div class="panel panel-info">
		  <div class="panel-heading" role="tab" id="headingFourSuiveur">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_rdv_planifie_suiveur" role="button" data-toggle="collapse" data-parent="#accordionSuiveur" href="#collapseFourSuiveur" aria-expanded="false" aria-controls="collapseFourSuiveur">
			   <?php echo icon("panel");?> Les rendez-vous planifiés
		      </a>
		    </h4>
		  </div>
		  <div id="collapseFourSuiveur" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingFourSuiveur">
		    <div  id="tb_datt"  aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
		    </div>
		  </div>
		</div>
		 <div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingFiveSuiveur">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_bureau_demande_ouvert_suiveur" role="button" data-toggle="collapse" data-parent="#accordionSuiveur" href="#collapseFiveSuiveur" aria-expanded="false" aria-controls="collapseFiveSuiveur">
			   <?php echo icon("panel");?> Les demandes ouvertes
		      </a>
		    </h4>
		  </div>
		  <div id="collapseFiveSuiveur" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingFiveSuiveur">
		    <div  id="tb_datt"  aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
		    </div>
		  </div>
		</div>
		
		</div>
	</div>


   </div>
	


    <div class="col-lg-2">
	<div id="menu_fixed_bureau" class="menu_fixed" style="padding-right:10px;  min-width: 200px !important">
	    <h4><i class="fa fa-desktop fa-1x"></i> Mon bureau</h4>
	    	    	 

		    <ul class="list-group">
			 <li href="#well_dash_boite"  style="cursor:pointer" href=""  class="list-group-item">
			      <a style="background-color: mediumpurple; border-color:mediumpurple" class="btn btn-success" href="<?php echo base_url();?>app/index_bureau_ajout">
				  <i class="fa fa-commenting"></i> Nouvelle demande</a>
			    </li>
			    <li href="#well_dash_boite"  style="cursor:pointer" href=""  class="list-group-item ascroll">
			        Mes nouvelles demandes
			    </li>
			    <li href="#well_dash_planning" style="cursor:pointer" class="list-group-item tr_fiche_id_bien ascroll">
			      Planning 
			    </li>
			    <li href="#well_dash_demande_ouverte" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
				 Mes demandes ouvertes

			    </li>
			    <li href="#well_dash_utilisateur_suiveur" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
				Back up

			    </li>
		    </ul>

	</div>
    </div>

</div>
