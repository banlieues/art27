<?php $this->load->view("interface/choice_module"); ?>
<?php $this->load->view("interface/search_all"); ?>



<div style="margin-bottom: 20px" class='entities c_rubrique'>

  
<div style="margin-bottom:0 !important; margin:10px; border-color: TOMATO !important" class="panel panel-info">
    <div style='background-color: TOMATO !important; border-color: TOMATO !important' class="panel-heading">
	<div class='row'>
	    <div style='font-size:24px; min-height: 40px' class='col-lg-6'>
	    <?php echo icon("data");?> Base de données
	   
	    </div>
	</div>
    </div>

    <div style="padding:0 !important" class="panel-body">
  <!-- Nav tabs -->
  <ul style="background-color: #eaeaea; z-index: 600" id="nav_bd" class="nav nav-tabs" role="tablist">
 
    <li class="active" role="presentation"><a fh_descriptor="fhd_liste_demande" class="reload_ajax is_empty" href="#tablistdemande" aria-controls="tablistdemande" role="tab" data-toggle="tab"><i class="fa fa-commenting"></i> Demandes</a></li>

    <li role="presentation" ><a fh_descriptor="fhd_liste_demandeur" class="reload_ajax is_empty" href="#tablistdemandeur" aria-controls="tablistdemandeur" role="tab" data-toggle="tab"><i class="fa fa-user-o"></i> Demandeurs</a></li>    
    <li role="presentation"><a fh_descriptor="fhd_liste_bien" class="reload_ajax is_empty" href="#tablistbien" aria-controls="tablistbien" role="tab" data-toggle="tab"><i class="fa fa-building"></i> Bien </a></li>
        <li role="presentation" ><a fh_descriptor="fhd_liste_encodage" class="reload_ajax is_empty" href="#tablistencodage" aria-controls="tablistencodage" role="tab" data-toggle="tab"><i class="fa fa-database"></i> Encodage</a></li>    

   <?php if($this->fh_dao->get_autorisation_super_admin()): ?>
        	    	    <li style="margin-left:40px !important"  role="presentation" ><a fh_descriptor="fhd_liste_personne" class="reload_ajax is_empty" href="#tablistpersonne" aria-controls="tablistpersonne" role="tab" data-toggle="tab"><i class="fa fa-user"></i> Tous </a></li>
<?php endif;?>
    	    	    <li role="presentation" ><a fh_descriptor="fhd_liste_personne_pro" class="reload_ajax is_empty" href="#tablistpersonne_pro" aria-controls="tablistpersonne_pro" role="tab" data-toggle="tab"><i class="fa fa-user-secret"></i> Professionnels </a></li>

    <li style="margin-left:40px !important" role="presentation"><a fh_descriptor="fhd_liste_tache" class="reload_ajax is_empty" href="#tablisttache" aria-controls="tablisttache" role="tab" data-toggle="tab"><i class="fa fa-paperclip"></i> Tâches </a></li>
        <li  role="presentation"><a fh_descriptor="fhd_liste_rdv" class="reload_ajax is_empty" href="#tablistrdv" aria-controls="tablistrdv" role="tab" data-toggle="tab"><i class="fa fa-address-book"></i> Rendez-vous </a></li>



	
    <li role="presentation"><a fh_descriptor="fhd_liste_document" class="reload_aja is_empty" href="#tablistdocument" aria-controls="tablistdocument" role="tab" data-toggle="tab"><i class="fa fa-download"></i> Documents</a></li>

    <li role="presentation"><a fh_descriptor="fhd_liste_enquete" class="reload_ajax_enquete is_empty" href="#tablistenquete" aria-controls="tablistenquete" role="tab" data-toggle="tab"><i class="fa fa-check"></i> Enquêtes</a></li>

  </ul>

  <!-- Tab panes -->
  <div class="tab-content">
  

     <div role="tabpanel" class="tab-pane active tab-pane-list" id="tablistdemande"><?php echo $view_demandeur;?></div> 
     <?php if($this->fh_dao->get_autorisation_super_admin()): ?>
    <div role="tabpanel" class="tab-pane  tab-pane-list" id="tablistpersonne"></div>  
    <?php endif;?>
    <div role="tabpanel" class="tab-pane tab-pane-list" id="tablistdemandeur"></div>  
    
    <div role="tabpanel" class="tab-pane tab-pane-list" id="tablistbien"></div>
        <div role="tabpanel" class="tab-pane tab-pane-list" id="tablistencodage"></div>  

    <div role="tabpanel" class="tab-pane tab-pane-list" id="tablistpersonne_pro"></div>
   
    <div role="tabpanel" class="tab-pane tab-pane-list" id="tablisttache"></div>
        <div role="tabpanel" class="tab-pane tab-pane-list" id="tablistrdv"></div>
    <div role="tabpanel" class="tab-pane tab-pane-list" id="tablistdocument">
     <?php 
        $this->load->add_package_path(FHPATH."outlook");
       // $params["id_demande"]=$id_entity;
        $params_depot = array();
        $params_depot['view']='generale';
        echo $this->load->view("page_depot",$params_depot, TRUE);
        echo '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.css">';
        echo '<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.0/jquery-confirm.min.js"></script>';
      ?>
    </div>
    <div role="tabpanel" class="tab-pane tab-pane-list" id="tablistenquete"><?php $this->load->view("template/construction")?></div>

  </div>

</div>
</div>
</div>