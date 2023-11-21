<div class="row">
  <div class="col-lg-10">
	<div  id="well_dash_pot_general" class="well well_dash">
	    <h2><small>Général</small></h2>
	    <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingPotOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_pot_demande_type_information" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePotOne" aria-expanded="true" aria-controls="collapsePotOne">
		       <?php echo icon("panel");?> Informations - conseils
		      </a>
		    </h4>
		  </div>
		  <div id="collapsePotOne" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingPotOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew" fh_descriptor="fhd_liste_demande_filtre_charge" aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingPotFour">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_type_accompagnement_nonspecifique" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePotFour" aria-expanded="false" aria-controls="collapsePotFour">
		       <?php echo icon("panel");?> Accompagnements (non spécifiques)
		      </a>
		    </h4>
		  </div>
		  <div id="collapsePotFour" class="collapse_reload collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingPotFour">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingPotTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_type_visite" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePotTwo" aria-expanded="false" aria-controls="collapsePotTwo">
			   <?php echo icon("panel");?> Visites
		      </a>
		    </h4>
		  </div>
		  <div id="collapsePotTwo" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingPotTwo">
		    <div  id="tb_datt" fh_descriptor="fhd_liste_demande_filtre_charge_attente" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		<!--<div class="panel panel-default">
		  <div class="panel-heading" role="tab" id="headingPotThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_type_visite_incomplete" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapsePotThree"a aria-expanded="false" aria-controls="collapsePotThree">
		       <?php //echo icon("panel");?> Visites - demandes incomplètes
		      </a>
		    </h4>
		  </div>
		  <div id="collapsePotThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingPotThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>-->
		 
		
		</div>
	 </div>

	 <div  id="well_dash_pot_acc_spec" class="well well_dash">
	    <h2><small>Accompagnement spécifique </small></h2>
	    <div class="panel-group reste_ouvert" id="accompagnement" role="tablist" aria-multiselectable="true">
		<div class="panel panel-success">
		  <div class="panel-heading" role="tab" id="headingAccOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_pot_demande_acc_vert" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAccOne" aria-expanded="true" aria-controls="collapseAccOne">
		       <?php echo icon("panel");?> Prêt vert Bruxelles
		      </a>
		    </h4>
		  </div>
		  <div id="collapseAccOne" class="collapse_reload panel-collapse collapse in" role="tabpanel" aria-labelledby="headingAccOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew" aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>
		
		<div class="panel panel-warning">
		  <div class="panel-heading" role="tab" id="headingAccTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_acc_urbanisme" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAccTwo" aria-expanded="false" aria-controls="collapseAccTwo">
			   <?php echo icon("panel");?> Facilitateur urbanisme
		      </a>
		    </h4>
		  </div>
		  <div id="collapseAccTwo" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingAccTwo">
		    <div  id="tb_datt" fh_descriptor="fhd_liste_demande_filtre_charge_attente" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		<div class="panel panel-danger">
		  <div class="panel-heading" role="tab" id="headingAccThree">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_acc_copropriete" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAccThree" aria-expanded="false" aria-controls="collapseAccThree">
		       <?php echo icon("panel");?> Petites copropriétés 
		      </a>
		    </h4>
		  </div>
		  <div id="collapseAccThree" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingAccThree">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		 <div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#9370DB;color:#fff;" ssrole="tab" id="headingAccFour">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_acc_exemplary" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAccFour" aria-expanded="false" aria-controls="collapseAccFour">
		       <?php echo icon("panel");?> Be exemplary
		      </a>
		    </h4>
		  </div>
		  <div id="collapseAccFour" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingAccFour">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		 <div class="panel panel-info">
		  <div class="panel-heading" role="tab" id="headingAccFive">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_acc_alloyer" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAccFive" aria-expanded="false" aria-controls="collapseAccFive">
		       <?php echo icon("panel");?> Allocation loyer
		      </a>
		    </h4>
		  </div>
		  <div id="collapseAccFive" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingAccFive">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>
		 <div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#FDF5E6;" role="tab" id="headingAccSix">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_acc_patrimoine" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAccSix" aria-expanded="false" aria-controls="collapseAccSix">
		       <?php echo icon("panel");?> Petit Patrimoine
		      </a>
		    </h4>
		  </div>
		  <div id="collapseAccSix" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingAccSix">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		 <div class="panel panel-default">
		  <div class="panel-heading" style="background-color:#FA8072;" role="tab" id="headingAccSeven">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_acc_fac_pro_loc" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseAccSeven" aria-expanded="false" aria-controls="collapseAccSeven">
		       <?php echo icon("panel");?> Facilitateur locataire / propriétaire 
		      </a>
		    </h4>
		  </div>
		  <div id="collapseAccSeven" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingAccSeven">
		    <div  id="tb_dsus"  aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		
		</div>
	 </div>

	 <div  id="well_dash_pot_gp_ref" class="well well_dash">
	 	 <h2><small>Groupes référents </small></h2>
	  <div class="panel-group reste_ouvert" id="accordionsss" role="tablist" aria-multiselectable="true">
		<div class="panel panel-danger">
		  <div class="panel-heading" style="background-color:#F08080;" role="tab" id="headingGrOne">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_pot_demande_acoustique" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseGrOne" aria-expanded="true" aria-controls="collapseGrOne">
		       <?php echo icon("panel");?> Acoustique
		      </a>
		    </h4>
		  </div>
		  <div id="collapseGrOne" class="collapse_reload collapse_reload panel-collapse collapse " role="tabpanel" aria-labelledby="headingGrOne">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>

		
		
		<div class="panel panel-info">
		  <div class="panel-heading" style="background-color:#20B2AA;" role="tab" id="headingGrTwo">
		    <h4 class="panel-title">
		      <a  class="link_dashboard" descriptor="fhdash_pot_demande_energie" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseGrTwo" aria-expanded="false" aria-controls="collapseGrTwo">
			   <?php echo icon("panel");?> Energies renouvelables
		      </a>
		    </h4>
		  </div>
		  <div id="collapseGrTwo" class="collapse_reload panel-collapse collapse" role="tabpanel" aria-labelledby="headingGrTwo">
		    <div  id="tb_datt"  aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
			
		    </div>
		  </div>
		</div>

		<div class="panel panel-default">
		<div class="panel-heading"  role="tab" id="headingGrThree">
		    <h4 class="panel-title">
		      <a class="link_dashboard" descriptor="fhdash_pot_demande_patrimoine" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseGrThree" aria-expanded="true" aria-controls="collapseGrThree">
		       <?php echo icon("panel");?> Patrimoine
		      </a>
		    </h4>
		  </div>
		  <div id="collapseGrThree" class="collapse_reload collapse_reload panel-collapse collapse " role="tabpanel" aria-labelledby="headingGrThree">
		    <div style="padding:0; margin:0 !important"  id="tb_dnew"  aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax"> 
		    </div>
		  </div>
		</div>

	</div>
</div>
  
  </div>
   <div class="col-lg-2">
		<div id='menu_fixed_pot'  class="menu_fixed_pot" style="padding-right:10px; min-width: 200px !important">
		    <h4><i class="fa fa-desktop fa-1x"></i> Pot commun</h4>
			    <ul class="list-group">
				 <li href="#well_dash_boite"  style="cursor:pointer" href=""  class="list-group-item">
			      <a style="background-color: mediumpurple; border-color:mediumpurple" class="btn btn-success" href="<?php echo base_url();?>app/index_bureau_ajout">
				  <i class="fa fa-commenting"></i> Nouvelle demande</a>
			    </li>
				    <li href="#well_dash_pot_general"  style="cursor:pointer" href=""  class="list-group-item ascroll">
				      Général
				    </li>
				    <li href="#well_dash_pot_acc_spec" style="cursor:pointer" class="list-group-item tr_fiche_id_bien ascroll">
				      Acc. spécifiques 
				    </li>
				    <li href="#well_dash_pot_gp_ref" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					   Groupes référents
				    </li>
			    </ul>

		</div>
    </div>

</div>
