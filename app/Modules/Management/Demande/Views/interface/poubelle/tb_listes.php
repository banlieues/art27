<script>
jQuery(document).ready(function()
{ 
   $('#accordeonss').collapse({
	    parent: false
	})
});

</script>

<div style="padding:0; margin:0" class="col-lg-10">
<div id="tb_demande" style="background-color:white" class="well">
    <h3><i class="fa fa-commenting"></i> Mes demandes</h3>
<div class="panel-group" id="accordionss" role="tablist" aria-multiselectable="true">
  <div class="panel panel-success">
    <div class="panel-heading" role="tab" id="headingOne">
      <h4 class="panel-title">
        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
         <i class="fa fa-commenting"></i>  Mes nouvelles demandes
        </a>
      </h4>
    </div>
    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
      <div  id="tb_dnew" fh_descriptor="fhd_liste_demande_filtre_charge" aria-controls="tb_dnew"   class="panel-body tab-pane-list reload_ajax">
	    <?php echo $view_demandeur_nouvelle; ?>
      </div>
    </div>
      

  </div>
  <div class="panel panel-warning">
    <div class="panel-heading" role="tab" id="headingTwo">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
	    <i class="fa fa-commenting"></i> Mes demande(s) en attente
        </a>
      </h4>
    </div>
    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
      <div  id="tb_datt" fh_descriptor="fhd_liste_demande_filtre_charge_attente" aria-controls="tb_datt"   class="panel-body tab-pane-list reload_ajax">
	   <?php echo $view_demandeur_attente; ?>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
         <i class="fa fa-commenting"></i>   Mes demande(s) en suspens
        </a>
      </h4>
    </div>
    <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div  id="tb_dsus" fh_descriptor="fhd_liste_demande_filtre_charge_suspens" aria-controls="tb_dsus"   class="panel-body tab-pane-list reload_ajax">
	<?php echo $view_demandeur_suspens; ?>
      </div>
    </div>
  </div>
    <div class="panel panel-danger">
    <div class="panel-heading" role="tab" id="headingFour">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
        <i class="fa fa-commenting"></i>    Mes demande(s) annulées
        </a>
      </h4>
    </div>
    <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFour">
      <div  id="tb_dann" fh_descriptor="fhd_liste_demande_filtre_charge_annule" aria-controls="tb_dann"   class="panel-body tab-pane-list reload_ajax">
	<?php echo $view_demandeur_annule; ?>
      </div>
    </div>
  </div>
    <div class="panel panel-primary">
    <div class="panel-heading" role="tab" id="headingFive">
      <h4 class="panel-title">
        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
         <i class="fa fa-commenting"></i>   Mes demande(s) clôturées
        </a>
      </h4>
    </div>
    <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingFive">
            <div  id="tb_dclo" fh_descriptor="fhd_liste_demande_filtre_charge_cloturee" aria-controls="tb_dclo"   class="panel-body tab-pane-list reload_ajax">

	<?php echo $view_demandeur_cloture; ?>
      </div>
    </div>
  </div>
</div>
</div>

<div style="background-color:white" class="well">
     <h3> <i class="fa fa-address-book"></i> Mes prochains rendez-vous</h3>
     <div id="tb_rdv"  fh_descriptor="fhd_liste_rdv_futur" aria-controls="tb_rdv"  style="background-color:white" class="tab-pane-list reload_ajax">
    <?php echo $view_rdv_futur; ?>
     </div>
</div>

<div style="background-color:white" class="well">
    <div id="tb_tache" fh_descriptor="fhd_liste_tache_futur" aria-controls="tb_tache"  style="background-color:white" class="tab-pane-list reload_ajax">
     <h3> <i class="fa fa-paperclip"></i> Mes prochaines tâches</h3>
    <?php echo $view_tache_futur; ?>
    </div>
</div>
</div>

 <div class=" position_hide" style="position:fixed; right:40px; top:200px">
    
   <ul class="list-group">
					
       
					 <li href="#tb_demande"  style="cursor:pointer" href=""  class="list-group-item ascroll">
					   <i class="fa fa-commenting"></i>  Mes demandes 
					 </li>
					 <li href="#tb_rdv" style="cursor:pointer" class="list-group-item tr_fiche_id_bien ascroll">
					  <i class="fa fa-address-book"></i>  Mes rendez-vous 
					 </li>
					 <li href="#tb_tache" style="cursor:pointer" href="tr_demandeur" class="list-group-item ascroll">
					     <i class="fa fa-paperclip"></i> Mes tâches 
					    
					 </li>
					 
					
					
					 
				     </ul>

</div>